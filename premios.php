<?php
try {
    require_once __DIR__ . "/app/inc/security.php";
    require_once __DIR__ . "/app/inc/server.php";
    require_once __DIR__ . "/app/inc/funtions.php";
    require_once __DIR__ . "/app/inc/db.php";

    if (!isset($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = hash('sha256', uniqid(mt_rand(), true));
    }

    $db = new ChefDB();
    if (!$debugmode)
        header("refresh:180;url=" . $exit . '?expires=1'); //lo sacamos del sistema

    // if (isset($_POST['user_name']) && isset($_POST['user_password']) && isset($_POST['csrf'])) {
    //     if (!$debugmode) {
    //         if (isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] == $_POST['csrf']) {
    //             unset($_SESSION['csrf_token']);
    //         } else {
    //             header('Location:' . $exit);
    //         }

    //         $response = filter_input(INPUT_POST, 'g-recaptcha-response', FILTER_SANITIZE_STRING);
    //         $valid = recaptcha_validate($response);
    //         if (!$valid) {
    //             header('Location:' . $exit . '?recaptcha_error=1');
    //             exit;
    //         }
    //     }

    //     if (strlen($_POST['user_name']) != 64 || strlen($_POST['user_password']) != 64) {
    //         header('Location:' . $exit);
    //         exit;
    //     }

    //     $db->postLogin($_POST['user_name'], $_POST['user_password']);
    //     if ($_SESSION['idmask']) {
    //         header('Location:' . $_SERVER['REQUEST_URI']);
    //         die();
    //     }
    // } else {
    //     if (!isset($_SESSION["idmask"]) || $_SESSION["idmask"] == "") {
    //         header('Location:' . $exit); //si existe la sesión
    //         exit;
    //     }
    // }
} catch (Exception $e) {
    header('Location:' . $exit); //si existe la sesión
    die();
}

if (!isset($_SESSION["idmask"]) || $_SESSION["idmask"] == "") {
    header('Location:' . $exit); //si no existe la sesión
    exit;
}

$block = $db->getUserBlock($_SESSION["idmask"], 1, $_ENV['CAMPAIGN_BLOCK']);
$_SESSION["block"] = $block;
$awards = $db->postPremios();
$redemption = $db->getOneRedemption($_SESSION["idmask"], $block)->fetch_assoc();

$codeValidate = false;
if (isset($_POST['code-validate'])) {

    if (!$debugmode) {
        if (isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] == $_POST['csrf']) {
            unset($_SESSION['csrf_token']);
        } else {
            header('Location:' . $exit);
        }
    }

    $code = test_input2($_POST['code-validate'], 64);
    if ($db->validateCode($code)) {
        $_SESSION['code-validate'] = true;
        $_SESSION['code-validate-attempts'] = 0;
        $codeValidate = true;
    } else {
        $_SESSION['code-validate-attempts'] = $_SESSION['code-validate-attempts'] + 1;
        $_SESSION['code-validate'] = false;
        $_SESSION["csrf_token"] = hash('sha256', uniqid(mt_rand(), true));
    }
}

$campaign_closure_only_redemptions = false;
if ($_ENV['CAMPAIGN_CLOSURE_REDEMPTIONS'] == 'Y') {
    $campaign_closure_only_redemptions = true;
}

$budgetLimitReached = $db->budgetLimitReached();

if (!isset($_SESSION['code-validate-attempts'])) {
    $_SESSION['code-validate-attempts'] = 0;
} else {
    if ($_SESSION['code-validate-attempts'] > 3) {
        header('location: /exit');
        die();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include __DIR__ . '/app/partials/metadata.php';
    include __DIR__ . '/partiales/assets-css.php';
    ?>

    <title>Banco de Bogotá</title>
</head>

<body>
    <?php include 'partiales/header.php'; ?>


    <?php include 'app/partials/tagManagerBody.php'; ?>
    <section class="clsMainBanner awards">
        <div>
            <div class="container banner-int">
                <div class="row">
                    <div class="col-md-6">
                        <div class="content-banner">

                            <h4>Selecciona el bono que deseas redimir</h4>
                            <p class="basic-text white">¡Cada compra te acerca a una grandiosa experiencia! Sigue usando tu Tarjeta de Crédito Mastercard Banco de Bogotá y gana hasta 4 bonos digitales mensuales y uno de los bonos de Aviatur.</p>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
                </div>
                <div class="content-title mechanic">
                    <span>VUELVE LA TEMPORADA DE</span>
                    <div class="yellow-word">
                        <span>VIVE TUS COMPRAS Y GANA</span>

                    </div>
                    <span>GRANDES EXPERIENCIAS</span>

                </div>
            </div>

        </div>


    </section>

    <div class="main-content">
        <section class="clsAwards">
            <div class="container content-tabs-title">

                <div class="row">

                    <div class="col-md-12">
                        <ul class="nav  tabs-awards-nav" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Bonos para redimir</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Bonos Aviatur</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="content-awards-bones">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="grid-awards">
                                <div class="row">
                                    <?php
                                    if ($redemption) {
                                        $_SESSION['winner'] = false;
                                    ?>
                                        <div class="col-md-4 tarj">
                                            <div class="award">
                                                <img src="<?php echo $redemption['logo_image']; ?>" alt="<?php echo $redemption['name']; ?>">
                                                <div class="title-price">
                                                    <span class="name"><?php echo $redemption['name']; ?></span>
                                                    <span class="price">$<?php echo number_format($redemption['value'], 0, ',', '.'); ?></span>
                                                </div>
                                                <a href="/descargar?block=<?php echo $block; ?>" class="btn yellow">Descargar</a>
                                            </div>
                                        </div>
                                        <?php
                                    } else {
                                        if ($awards) {
                                            $is_winner = $db->getIsWinner($_SESSION['idmask'], $block); // Verificar si es ganador en en el mes (block)  actual
                                            $_SESSION['winner'] = $is_winner;
                                            foreach ($awards as $award) {
                                                $award_price = $_SESSION['award_price'];
                                                $label_award_price = number_format($award_price, 0, ',', '.');
                                                $is_leal_coins = false;
                                                if (isset($award['leal_coins']) && $award['leal_coins'] == '1') {
                                                    /*
                                                    Condicional para mostrar un valor de 50% más para los bonos de puntos leal
                                                    este valor adicional lo asume el proveedor Puntos Leal.
                                                    */
                                                    $label_award_price = number_format($award_price * 1.5, 0, ',', '.'); // se multiplica por un factor de 1.5 para obtener un 50% adicional al valor original
                                                    $is_leal_coins = true;
                                                }
                                        ?>
                                                <div class="col-md-4 tarj">
                                                    <div class="award">
                                                        <img src="<?php echo $award['logo_image']; ?>" alt="<?php echo $award['name']; ?>">
                                                        <div class="title-price">
                                                            <span class="name"><?php echo $award['name']; ?></span>
                                                            <span class="price">$<?php echo $label_award_price; ?></span>
                                                            <?php

                                                            if ($is_leal_coins) {
                                                            ?>
                                                                <span class="legalcoins">
                                                                    Tienes $<?php echo number_format($award_price, 0, ',', '.'); ?>
                                                                    de redención inmediata, el 50% que te regala Leal Coins,
                                                                    se verá reflejado un día hábil después de redimir el cupón.
                                                                </span>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                        <?php if ($_SESSION['winner'] && !$budgetLimitReached && !$campaign_closure_only_redemptions) { ?>
                                                            <a href="#" id="<?php echo $award['id']; ?>" data-id="<?php echo $award['id']; ?>" data-name="<?php echo $award['name']; ?>" data-logo="<?php echo $award['logo_image']; ?>" data-image="<?php echo $award['image']; ?>" data-toggle="modal" data-target="<?php echo (!$codeValidate) ? '#modalCode' : '#contentAward'; ?>" class="btn yellow open-modal-confirm">Redimir</a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                            <div class="row content-tabsmec">
                                <div class="col-md-6">
                                    <div class="content-title-sect">
                                        <h3 class="tabsTitle">Gana bonos Aviatur</h3>
                                        <span>Premios</span>
                                        <p class="descAwards">¡Gana uno de los <span>10 bonos mensuales de Aviatur!</span> Entregaremos 40 bonos durante toda la actividad, podrás redimir el tuyo en cualquiera de los servicios que ofrece Aviatur: vuelos, paquetes, hoteles, experiencias y más.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row content-main-awards">
                                        <div class="col-md-12">
                                            <div class="item aviatur-uno">
                                                <div class="overlay">
                                                    <div class="desc">
                                                        <h3>BONO AVIATUR</h3>

                                                        <span class="award-desc">
                                                            GANA UNO DE LOS 10 BONOS MENSUALES POR $3.000.000
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="item aviatur-dos">
                                                <div class="overlay">
                                                    <div class="desc">
                                                        <h3>BONO AVIATUR</h3>

                                                        <span class="award-desc">
                                                            GANA UNO DE LOS 10 BONOS MENSUALES POR $1.000.000
                                                        </span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </section>
        <img src="assets/logos/vigilado-int.svg" alt="" class="vigilado interno">
    </div>
    <!-- Button trigger modal -->



    <!-- Modal -->
    <div class="modal fade modal-code" id="modalCode" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <form class="" id="form-validate" action="" method="POST" autocomplete="off">
                        <?php if ($_SESSION['code-validate-attempts'] > 0) { ?>
                            <span class="alert code">Código incorrecto verifique de nuevo</span>
                        <?php } ?>

                        <label for="">Ingresa aquí el código de verificación que has recibido <br> en el correo electrónico para redimir tu bono</label>
                        <input id="codeValidate" name="code-validate" type="text" placeholder="Código">
                        <input type="hidden" value="<?php echo $_SESSION["csrf_token"]; ?>" name="csrf">
                        <input type="hidden" value="<?php echo (isset($_POST['award-id-hidden'])) ? $_POST['award-id-hidden'] : ""; ?>" name="award-id-hidden" id="award-id-hidden">
                        <button type="submit" class="btn yellow">Continuar</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <?php
    include __DIR__ . '/partiales/footer.php';
    include __DIR__ . '/partiales/modal-awards.php';
    include __DIR__ . '/partiales/modalLimitAwards.php';
    include __DIR__ . '/partiales/assets-js.php';
    ?>

    <?php

    $idTag = (isset($_SESSION["idmask"])) ? $_SESSION["idmask"] : '';

    ?>

    <script type="text/javascript" nonce="<?php echo $cspNonce[1]; ?>">
        $(function($) {
            var userId = "<?php echo $idTag; ?>";
            window.dataLayer.push({
                event: 'UserID ',
                userId: userId
            });
        });
    </script>

    <?php
    if ($codeValidate && !$redemption) { ?>
        <script type="text/javascript" nonce="<?php echo $cspNonce[0]; ?>">
            $(function($) {
                var id = <?php echo  $_POST['award-id-hidden']; ?>;
                var ele = $('#' + id);
                $('.modal-header').css('background-image', 'url(' + ele.data('image') + ')');
                $('.modal-logo').attr('src', ele.data('logo'));
                $('.modal-name').html(ele.data('name'));
                $('.modal-link').attr('href', 'redimir/' + ele.data('id'));
                $('#contentAward').modal('show');
            });
        </script>
    <?php } else { ?>



    <?php } ?>

    <?php if ($_SESSION['code-validate-attempts'] > 0 && !$codeValidate) { ?>
        <script text="text/javascript" nonce="<?php echo $cspNonce[1]; ?>">
            $(function($) {
                $('.modal-code').modal('show');
            });
        </script>
    <?php } ?>

    <?php
    if ($budgetLimitReached) { ?>
        <script text="text/javascript" nonce="<?php echo $cspNonce[3]; ?>">
            $(function($) {

                $('#modalLimitAwards').modal('show');
            });
        </script>
    <?php } ?>


</body>

</html>