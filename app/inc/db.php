<?php
$timezone = 'America/Bogota';
date_default_timezone_set($timezone);

require_once __DIR__ . "/funtions.php";
require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/logger.class.php";

class ChefDB
{
  protected $mysqli;
  protected $site;

  public function __construct()
  {
    try {
      $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
      $dotenv->load();
      $this->site = $_ENV['SITE_URL'];
      $this->mysqli = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB']);
      mysqli_set_charset($this->mysqli, 'utf8');
    } catch (mysqli_sql_exception $e) {
      http_response_code(500);
      exit;
    }
  }

  public function getUser($id)
  {
    $usuario = stripslashes(trim(htmlspecialchars($id)));
    $tamu = strlen($usuario);
    if (preg_match("/(>|=|<+)/", $usuario)  or $tamu != 5) {
      $datos = array(
        "status" => 500
      );
    } else {
      $usuario = sha1($usuario);
      $datos = array(
        "status" => $usuario
      );
    }
    return $datos;
  }

  public function getUsercode($id, $cod)
  {
    $usuario = stripslashes(trim(htmlspecialchars($id)));
    $cod = stripslashes(trim(htmlspecialchars($cod)));
    $tamu = strlen($usuario);
    if (preg_match("/(>|=|<+)/", $usuario)  or strlen($usuario) != 5 or strlen($cod) != 5) {
      $datos = array(
        "status" => 500
      );
    } else {
      $result = $this->mysqli->query("SELECT idmask FROM usuarios WHERE doc = '$usuario' AND codigo = '$cod' LIMIT 1");
      $consulta = $result->fetch_row();
      if ($consulta[0] != "") {
        $datos = array(
          "status" => 200
        );
      } else {
        $stmt = $this->mysqli->prepare("INSERT INTO  login(idmask,tipo) VALUES ('$usuario',3)");
        $stmt->execute();
        $datos = array(
          "status" => 500
        );
      }
      $result->close();
    }
    return $datos;
  }

  public function postLogin($user, $birthdate)
  {
    $Logger= new Logger();
    if (preg_match("/(>|=|<+)/", $user)) {
      header('Location: ' . $this->site . '/exit');
      exit;
    } else {
      $query = "SELECT * FROM mc_users WHERE sha2(document, 256) = '" . $user . "' AND sha2(birthdate, 256) = '" . $birthdate . "' LIMIT 1";
      $result = $this->mysqli->query($query);
      $consulta = $result->fetch_assoc();
      $ip = $_SERVER["REMOTE_ADDR"];
      if ($consulta == "") {
        $stmt = $this->mysqli->prepare("INSERT INTO  mc_login(idmask,type,ip, date) VALUES ('" . $user . "',0, '" . $ip . "','" . date('Y-m-d H:i:s') . "')");
        $stmt->execute();

        $log_data = json_encode(['user'=>$user,'type'=>0,'ip'=>$ip,'date'=>date('Y-m-d H:i:s')]);
        $Logger->log(__FILE__, __LINE__,"| category=LOG | idMessage=login | data: $log_data | transaccion=Login,detail=LoginFail",1);

        header('Location: ' . $this->site . '/exit');
        exit;
      } else {

        $query = "SELECT * FROM `mc_invalid_codes` WHERE sha2(code,'256') = '" . $user . "'";
        $result = $this->mysqli->query($query)->fetch_assoc();
        if ($result) {
          header('Location: ' . $this->site . '/exit?code_expired=1');
          exit;
        }

        $idmask = $consulta['idmask'];
        $user_block =  $this->getUserBlock($_SESSION["idmask"], 1, $_ENV['CAMPAIGN_BLOCK']);

        $queryLog = "INSERT INTO  mc_login(idmask,type,ip, date) VALUES ('" . $idmask . "',1, '" . $ip . "', '" . date('Y-m-d H:i:s') . "')";
        $stmt = $this->mysqli->prepare($queryLog);
        $stmt->execute();

        $log_data = json_encode(['user'=>$idmask,'type'=>1,'ip'=>$ip,'date'=>date('Y-m-d H:i:s')]);
        $Logger->log(__FILE__, __LINE__,"| category=LOG | idMessage=login | data: $log_data | transaccion=Login,detail=LoginSuccess",1);

        $is_winner = $this->getIsWinner($idmask, $user_block); // Verificar si es ganador en el mes (block)  actual

        $_SESSION["block"] = $user_block;
        $_SESSION["id"] = $idmask;
        $_SESSION["idmask"] = $idmask;
        $_SESSION["award_price"] = $consulta['award_' . $user_block];
        $_SESSION['winner'] = $is_winner;
        $_SESSION["utmweb"] = false;
      }
    }
  }

  public function budgetLimitReached()
  {
    $user_goal = $this->getUserGoal($_SESSION['idmask']);
    $user_block = $this->getUserBlock($_SESSION["idmask"], 1, $_ENV['CAMPAIGN_BLOCK']);
    $user_award = $user_goal['award_'.$user_block];

    $conteo = $this->postCount();
    $conteo = $conteo->fetch_row();
    $budget_limit_reached = false;
    $budget = $conteo[6];
    $budget_consumed = $conteo[1];
    if ($budget_consumed+$user_award >= $budget) {
        $budget_limit_reached = true;
    }
    return $budget_limit_reached;
  }

  public function postPremios()
  {
    $field = 's' . $_SESSION['award_price'] / 1000;
    $query = "SHOW COLUMNS FROM `mc_awards` LIKE '" . $field . "'";
    $result = $this->mysqli->query($query);
    if ($result->num_rows > 0) {
      $query = "SELECT * FROM mc_awards WHERE " . $field . " IS NOT NULL AND " . $field . " > 0 ORDER BY name";
      return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    } else return false;
  }

  /**
   * Cargar premio por id
   */
  public function getOneAward($id, $cuota, $active = true)
  {
    $operator = $active ? '':'=';
    $p = "s" . ($cuota / 1000);
    $query = "SHOW COLUMNS FROM `mc_awards` LIKE '" . $p . "'";
    $result = $this->mysqli->query($query);
    if ($result->num_rows > 0) {
      $query = "SELECT * FROM mc_awards WHERE id = " . $id . ' AND ' . $p . ' IS NOT NULL AND ' . $p . " >$operator 0";
      return $this->mysqli->query($query);
    } else return false;
  }

  /**
   * retorna las redenciones de un usuario
   */
  public function getOneRedemption($user, $block)
  {
    $query = "SELECT r.*, a.name, a.logo_image, a.image FROM `mc_redemptions` r INNER JOIN mc_awards a ON a.id = r.id_award WHERE idmask = '" . $user . "'  AND block='" . $block . "'";
    $gano = $this->mysqli->query($query);
    return $gano;
  }

  /**
   * retorna las redenciones de un usuario
   */
  public function getAllRedemptions($user)
  {
    $query = "SELECT r.*, a.name, a.logo_image, a.image FROM `mc_redemptions` r INNER JOIN mc_awards a ON a.id = r.id_award WHERE idmask = '" . $user . "'" ;
    $redemptions = $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    return $redemptions;
  }


  public function numRedemptionsByAward($value, $id_award)
  {
    $query = "SELECT count(1) total FROM mc_redemptions WHERE value = " . $value . " AND id_award = " . $id_award;
    $resultp = $this->mysqli->query($query)->fetch_assoc();
    return $resultp;
  }

  /**
   * carga todos los premios que esten activos y los desordena para mostrarlos en el slider del home -- por el momento --
   */
  public function premiosRand()
  {
    $resultp = $this->mysqli->query("SELECT * FROM premios WHERE s20 = 1 OR s25 = 1 OR s100 = 1 OR s50 = 1 OR s75 = 1 ORDER BY RAND()");
    return $resultp;
  }
  /**
   * Cargar premio por id
   */
  public function getPremio($id)
  {
    $resultp = $this->mysqli->query("SELECT * FROM mc_awards WHERE id = " . $id)->fetch_assoc();
    return $resultp;
  }

  public function postMeta($user)
  {
    $meta = $this->mysqli->query("SELECT * FROM metas WHERE idmask = '$user'  ");
    return $meta;
  }

  public function postSeguimiento($user)
  {
    $seguimiento = $this->mysqli->query("SELECT * FROM seguimiento WHERE idmask = '$user'  ");
    return $seguimiento;
  }
  public function postGano($user)
  {
    $query = "SELECT r.*, a.name, a.description, a.logo_image, a.image FROM `mc_redemptions` r INNER JOIN `mc_awards` a ON a.id = r.id_award  WHERE r.idmask = '" . $user . "'";
    $gano = $this->mysqli->query($query);
    return $gano;
  }

  public function postGanopremio($idp, $user, $idtxt, $json, $valor, $block)
  {
    $stmt = $this->mysqli->prepare("INSERT INTO mc_redemptions(id_award,idmask, date, value, idtxt, json, block)
                                    VALUES ('" . $idp . "','" . $user . "','" . date('Y-m-d H:i:s') . "','" . $valor . "','" . $idtxt . "','" . $json . "','" . $block . "')");
    $stmt->execute();
    return $stmt->insert_id;
    // $arrayData = [
    //   'precio' => 'premio',
    //   'idp' => $idp,
    //   'idmask' => $user,
    //   'cuota' => $valor,
    //   'idtxt' => $idtxt,
    //   'json' => $json
    // ];
    // binnacleSave($arrayData, 'Redeem');
  }

  public function postPremio($user)
  {
    $gano = $this->mysqli->query("SELECT * FROM bono WHERE idmask = '$user'  ");
    return $gano;
  }

  public function postlistPremios($p)
  {
    $p = "s" . $p;
    $premios = $this->mysqli->query("SELECT * FROM premios WHERE $p =1  ");
    return $premios;
  }
  public function postError($idu, $brand, $product, $type, $textResponse = '', $post_data = '')
  {
    $stmt = $this->mysqli->prepare("INSERT INTO mc_awards_logs(id_user,id_award,id_product_quantum,type_error, text_response, post_data, date)
    VALUES ('" . $idu . "','" . $brand . "','" . $product . "','" . $type . "', '" . $textResponse . "', '" . $post_data . "', '" . date('Y-m-d H:i:s') . "')");
    $r = $stmt->execute();

    $Logger= new Logger();
    $log_data = json_encode(['id_user'=>$idu,'id_award'=>$brand,'id_product_quantum'=>$product,'type_error'=> $type,'text_response'=> $textResponse,'post_data'=> $post_data,'date'=>date('Y-m-d H:i:s')]);
    $Logger->log(__FILE__, __LINE__,"| category=LOG | idMessage=redemption | data: $log_data | transaccion=Login,detail=RedemptionLog",1);
  }

  public function postContacto($idmask, $mensaje, $opcion1)
  {
    $idmask  =  stripslashes(trim(htmlspecialchars($idmask)));
    $mensaje =  stripslashes(trim(htmlspecialchars($mensaje)));
    $opcion1 =  stripslashes(trim(htmlspecialchars($opcion1)));

    if (preg_match("/(>|=|<+)/", $idmask)  or strlen($idmask) <= 1 or strlen($idmask) >= 10) {
      $datos = array(
        "status" => 500
      );
    } else {
      $stmt = $this->mysqli->prepare("INSERT INTO  rp_contacto(idmask,mensaje,opcion1) VALUES ('$idmask','$mensaje','$opcion1')");
      $r = $stmt->execute();

      if ($r) {
        $datos = array(
          "status" => 200
        );
      } else {
        $datos = array(
          "status" => 500
        );
      }
    }
    return $datos;
  }

  public function postCount()
  {
    $gano = $this->mysqli->query("SELECT count(r.id),sum(r.value), n.* FROM `mc_redemptions` r, `mc_notifications_setup` n where n.id = 1");
    return $gano;
  }

  public function postCountByMount($mount, $year)
  {
    $gano = $this->mysqli->query("SELECT count(id),sum(value) FROM `mc_redemptions` where MONTH(date) = " . $mount . " AND YEAR(date) = " . $year);
    return $gano;
  }

  /**
   * get data all users report
   */
  public function getTotalPremios()
  {

    $report = $this->mysqli->query("SELECT count(id) as numero_premios ,sum(cuota) as total_entregados  FROM bono ");
    return $report;
  }

  /**
   * Get user award
   */

  public function getUserByIdAward($id)
  {
    $res = $this->mysqli->query("SELECT u.nombre as usuario ,b.fecha, b.cuota, p.nombre as premio FROM usuarios u 
          inner join bono b on u.idmask = b.idmask 
          inner join premios p on b.idp = p.idq WHERE u.idmask = '" . $id . "'");
    return $res;
  }
  public function getUserLoginById($id)
  {
    $res = $this->mysqli->query("SELECT u.idmask, u.nombre, l.date, l.tipo  FROM usuarios u inner  join login l on u.idmask = l.idmask WHERE u.idmask = '" . $id . "'");
    return $res;
  }
  /**
   * actualizar campo con la siguiente concurrencia de notificacion
   */
  public function updateSiguienteNotificacion($id = 1)
  {
    $query = "UPDATE mc_notifications_setup SET current = current + concurrence WHERE id = " . $id;
    $stmt = $this->mysqli->prepare($query);
    $stmt->execute();
  }

  public function getBadRedention($value, $award)
  {
    $query = "SELECT * FROM `mc_redemptions_bad` where value = '" . $value . "' AND id_award = " . $award . " AND idmask = 0 LIMIT 1";
    return $this->mysqli->query($query);
  }
  /**
   * actualizar idmask en bad redemption
   */
  public function updateBadRedemption($id, $idmask)
  {
    $query = "UPDATE mc_redemptions_bad SET idmask = '" . $idmask . "' WHERE id = " . $id;
    $stmt = $this->mysqli->prepare($query);
    $stmt->execute();
  }

  /**
   * All login
   */

  /**
   * Listar login report
   */




  public function loginReport()
  {
    $report = $this->mysqli->query("SELECT l.idmask, l.date as fecha, l.ip FROM mc_login l where l.ip <> '181.129.44.26' AND l.ip <> '181.57.146.242' AND l.type <> 0");
    return $report;
  }

  public function redencionReport()
  {
    $report = $this->mysqli->query("SELECT r.idmask, p.name AS nombre, r.value, r.date AS fecha FROM mc_redemptions r INNER JOIN mc_awards p ON r.id_award = p.id;");
    return $report;
  }
  /**
   * validar el codigo que se ingresa es válido
   * @param code codigo cifrado a validar
   */
  public function validateCode($code)
  {
    if (preg_match("/(>|=|<+)/", $code)) {
      header('Location: ' . $this->site . '/exit');
      exit;
    } else {
      $query = "SELECT * FROM mc_codes WHERE code = '" . $code . "' AND idmask = '" . $_SESSION["idmask"] . "' LIMIT 1";
      $result = $this->mysqli->query($query);
      $consulta = $result->fetch_row();
      if (empty($consulta)) {
        return false;
      } else {
        return true;
      }
    }
  }

  #region [Funciones Mecánica Banco de Bogotá 2]

  public function getUserTracing($idmask)
  {
    $result = $this->mysqli->query("SELECT * FROM mc_tracing WHERE idmask = '$idmask' ");
    $tracing = $result->fetch_assoc();
    return $tracing;
  }

  public function getIsWinner($idmask, $block)
  {
    $is_winner = false;
    $user_tracing = $this->getUserTracing($idmask);
    $winner_block = (int) $user_tracing['winner_' . $block];
    if ($winner_block == 1) {
      $is_winner = true;
    }
    return $is_winner;
  }

  public function getTextInfo($type, $block = false)
  {
    if ($block) {
      $result = $this->mysqli->query("SELECT text FROM mc_text WHERE type = '$type' AND block = '$block'");
    } else {
      $result = $this->mysqli->query("SELECT text FROM mc_text WHERE type = '$type' ");
    }
    $text_info = $result->fetch_assoc();

    return $text_info['text'];
  }

  public function getUserGoal($idmask)
  {
    $result = $this->mysqli->query("SELECT * FROM mc_users WHERE idmask = '$idmask'");
    $goal = $result->fetch_assoc();
    return $goal;
  }

  public function getUserBlock($idmask, $start_block = 1, $end_block = 4)
  {
    $start_block = (int) $start_block;
    $end_block = (int) $end_block;
    $userTracing = $this->getUserTracing($idmask);
    $user_block = $start_block;
    for ($i = $start_block; $i <= $end_block; $i++) {
      if ((int)$userTracing['winner_' . $i] == 1) {
        $redemption = $this->getOneRedemption($idmask, $i);
        $redemption = $redemption->fetch_assoc();
        if (!$redemption) {
          $user_block = $i;
          break;
        } elseif ($redemption && $user_block < $end_block) {
          $user_block++;
        }
      }
    }
    return $user_block;
  }

  // Condiciones Tipos de Mensajes fin de campaña
  public function getEndCampaingUserType($idmask)
  {
    /*
      1: Ganó - ya redimió almenos 1 vez
      2: Ganador - tiene almenos 1 bono para redimir
      3: No ganador - último no ganador
      4: No ganador - último vencido
    */
    $end_campaing_user_type = 0;

    $user_reemptions = $this->getAllRedemptions($idmask);
    if ($user_reemptions && (count($user_reemptions) > 0)){
     $end_campaing_user_type = 1; // Ganó - ya redimió almenos 1 vez
    }else{
      $user_tracing = $this->getUserTracing($idmask);
      if ($user_tracing) {
        $winner = false;
        for ($i = 1; $i <= 4; $i++) {
          if ((int)$user_tracing['winner_' . $i] == 1) {
            $winner = true;
          }
          $last_block = (int)$user_tracing['winner_' . $i];
        }

        if ($winner) {
            $end_campaing_user_type = 2; // Ganador - tiene almenos 1 bono para redimir
        }

        if (!$winner && $last_block == 0) {
          $end_campaing_user_type = 3; // No ganador - último no ganador
        }

        if (!$winner && $last_block == 2) {
          $end_campaing_user_type = 4; // No ganador - último vencido
        }
      }
    }

    return $end_campaing_user_type;
  }


  #endregion [Funciones Mecánica Banco de Bogotá 2]

  #region [Generación de codigos]

  /**
 * Inserta Idmask en tabla mc_codes
 */
  public function InsertUsersIntoCodesTable()
  {
    $query = "INSERT INTO mc_codes (idmask)
    SELECT idmask
    FROM mc_users
    WHERE idmask not in (SELECT idmask FROM mc_codes);";
    $stmt = $this->mysqli->prepare($query);
    $stmt->execute();
    return $stmt->affected_rows;
  }


  /**
   * retorna el siguiente codigo disponible para adjuntar en los bonos virtuales
   * @param string $idmask id del usuario
   * @param integer $idcod id del codigo
   */
  public function getUsersCodeNull()
  {
    $query = "SELECT * FROM `mc_codes` WHERE code IS NULL";
    return $this->mysqli->query($query);
  }

  /**
   * retorna el siguiente codigo disponible para adjuntar en los bonos virtuales
   * @param string $idmask id del usuario
   * @param integer $idcod id del codigo
   */
  public function insertCode($idmask, $code)
  {
    $query = "SELECT * FROM `mc_codes` WHERE code = '" . $code . "'";
    $data = $this->mysqli->query($query)->fetch_array();
    if ($data) {
      return false;
    } else {
      $query = "UPDATE `mc_codes` SET code = '" . $code . "' WHERE idmask = '" . $idmask . "'";
      $stmt = $this->mysqli->prepare($query);
      $stmt->execute();
      return true;
    }
  }
  #endregion [Generación de codigos]


  #region [Funciones Puntos Leal]
  /**
   * retorna el siguiente codigo disponible para adjuntar en los bonos virtuales
   * @param integer $product id del producto
   * @return Array registro unico en caso de estar disponible
   */
  public function getNextCod($product = false, $value)
  {
    $query = "SELECT * FROM `mc_leal_cods` where id_award = " . $product . " AND value = '" . $value . "' AND idmask IS NULL LIMIT 1";
    return $this->mysqli->query($query)->fetch_assoc();
  }

  /**
   * retorna el siguiente codigo disponible para adjuntar en los bonos virtuales
   * @param string $idmask id del usuario
   * @param integer $idcod id del codigo
   */
  public function saveNextCod($idmask = false, $idcod = false)
  {
    $query = "UPDATE `mc_leal_cods` SET idmask = '" . $idmask . "' WHERE id = " . $idcod;
    $stmt = $this->mysqli->prepare($query);
    $stmt->execute();
  }

  /**
   * actualizar idmask en bad redemption
   */
  public function updateAwardStock($id_award, $value)
  {
    $query = "UPDATE mc_leal_stock SET consumed = consumed + 1 WHERE id_award = '" . $id_award . "' AND value = '" . $value . "'";
    $stmt = $this->mysqli->prepare($query);
    $stmt->execute();
  }

  /**
   * retorna el siguiente codigo disponible para adjuntar en los bonos virtuales
   * @param integer $product id del producto
   * @return Array registro unico en caso de estar disponible
   */
  public function checkStockRemain($product = false, $value)
  {
    $query = "SELECT s.*, (total - consumed) subtraction, a.name FROM `mc_leal_stock` s " .
      "INNER JOIN mc_awards a ON a.id = s.id_award " .
      "WHERE id_award = " . $product . " AND s.value = '" . $value . "'";
    return $this->mysqli->query($query)->fetch_assoc();
  }


  #endregion [Funciones Puntos Leal]


}
