<?php

require_once __DIR__ . "/app/inc/code-generator/class.coupon.php";
use SubStacks\SMS_Marketing\Coupon;

if ($_SERVER['REMOTE_ADDR'] == '181.141.237.226' || $_SERVER['REMOTE_ADDR'] == '::1') {
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ . "/app/inc/db.php";
    $db = new ChefDB();

    echo '===============================================================<br>';
    echo '============================= Inicio ==============================<br>';
    echo '=============='.date('Y-m-d H:i:s').'================<br>';
    echo '===============================================================<br>';

    echo '------------------------------------<br>';
    echo 'Insertando idmask<br>';
    echo '------------------------------------<br>';
    $CountNewMask = $db->InsertUsersIntoCodesTable();
    echo '------------------------------------<br>';
    echo $CountNewMask.' idmask insertados<br>';
    echo '------------------------------------<br>';
    
    $users = $db->getUsersCodeNull();

    $length = 10;
    while ($user = $users->fetch_array()) {
        do {
            $code = Coupon::generate([
                'length' => $length,
                'letters' => true,
                'numbers' => true,
                'symbols' => true,
                'mixed_case' => true,
            ]);
            $insert = $db->insertCode($user['idmask'], $code);
        } while (!$insert);
    }

    echo '------------------------------------<br>';
    echo 'Cantidad códigos generados: '.$users->num_rows.'<br>';
    echo '------------------------------------<br>';

    echo '===============================================================<br>';
    echo '============================= Fin ===============================<br>';
    echo '=============='.date('Y-m-d H:i:s').'================<br>';
    echo '===============================================================<br>';

}