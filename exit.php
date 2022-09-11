<?php
include "./app/inc/security.php";
include "./app/inc/server.php";
include "./app/inc/funtions.php";
logOut(false);
$addExpire = '';
if (isset($_GET['expires'])) {
    $addExpire = '?expires=1';
}
if (isset($_GET['error_bonus'])) {
    $addExpire = '?error_bonus=1';
}
if (isset($_GET['code_expired'])) {
    $addExpire = '/redimir?code_expired=1';
}
if (isset($_GET['recaptcha_error'])) {
    $addExpire = '?recaptcha_error=1';
}
header('location: ' . $site . $addExpire);
