<?php
include "./app/inc/security.php";
include "./app/inc/server.php";
include "./app/inc/funtions.php";
require_once "./app/inc/db.php";
header("refresh:180;url=" . $exit . '?expires=1'); //lo sacamos del sistema
if ($_SESSION["idmask"] == "") {
    header('location: ./exit');
}
$db = new ChefDB();

if (isset($_GET['block'])){
    $block = $_GET['block'];
}
else{
    header('location: /premios');
    exit;
}

$redemption = $db->getOneRedemption($_SESSION["idmask"], $block)->fetch_assoc();
$premioId = $redemption['id_award'];
$award_price = $redemption['value'];
$premioArray = $db->getOneAward($premioId, $award_price, false)->fetch_assoc();
$voucher_provider = 'QUANTUM';
if ($premioArray['leal_coins'] == 1) {
    $voucher_provider = 'PUNTOS_LEAL';
}

if ($redemption && $voucher_provider == 'PUNTOS_LEAL'){
    $destination = __DIR__.$redemption['json'];
    $filename = 'boleta.pdf';
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$filename");
    header("Content-Type: application/pdf");
    header("Content-Transfer-Encoding: binary");
    readfile($destination);
}
elseif ($redemption && $voucher_provider == 'QUANTUM') {
    $awardFile = $redemption['json'];
    $destination = dirname(__FILE__) . '/app/awardtemp/redemption-' . $redemption['id'] . '.pdf';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $awardFile);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    $file = fopen($destination, "w+");
    fputs($file, $data);
    fclose($file);
    $filename = 'boleta.pdf';
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$filename");
    header("Content-Type: application/pdf");
    header("Content-Transfer-Encoding: binary");
    readfile($destination);
    unlink($destination);
}else{
    header('location: ./exit');
}
die();