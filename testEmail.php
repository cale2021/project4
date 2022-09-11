<?php
if ($_SERVER['REMOTE_ADDR'] != '201.233.8.9') {
    die();
}
$timezone = 'America/Bogota';
date_default_timezone_set($timezone);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



echo date('Y-m-d H:i:s');

die();

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . "/app/inc/funtions.php";


if ($_SERVER['REMOTE_ADDR'] == '201.233.8.9') {
    die('es mi pc');
}

// require_once __DIR__ . "/app/inc/db.php";

// $db = new ChefDB();

// $result = $db->getBadRedention(50, 2)->fetch_assoc();
// $db->postGanopremio($premioId, $_SESSION["id"], $result['idtxt'], $result['json'], $_SESSION["cuota"]);
// $db->updateBadRedemption($result['id'], '1111111');
// echo '<pre>';
// print_r($result);
// echo '</pre>';


// $db->postError($_SESSION["id"], $brand, $idbono, "0");
            
die();


// $mail = new PHPMailer(true);
$message = '<h3>Notificación total bonos</h3><p>El total de bonos de la campaña ' . $_SERVER['HTTP_HOST'] . ' paso el valor de: prueba' .
    ' La siguiente notificación se realizará después de prueba,000' . '</p>' .
    '<p>Fecha: ' . date('Y-m-d H:i:s') . '</p>';
sendEmail('acardonas@chefcompany.co', $message, 'Notificación total bonos ' . $_SERVER['HTTP_HOST'] . ' [prueba]');