<?php
include "./app/inc/security.php";
include "./app/inc/server.php";
include "./app/inc/funtions.php";
require_once "./app/inc/db.php";

// $db = new ChefDB();
// $test1 = hash('SHA256', $_SERVER['HTTP_USER_AGENT']); //explorador actual
// $test2 = hash('SHA256', $_SERVER['REMOTE_ADDR']); //ip actual
// $test3 = $test2 . $test1;
// // if (!$debugChef) {
// //     header("refresh:180;url=" . $exit . '?expires=1'); //lo sacamos del sistema
// // }
// if (isset($_POST['code'])) {
//     $secret = "6LdFyu0UAAAAAJpRA8cBFqpTv-th2bKtZcZejbAj";
//     $response = filter_input(INPUT_POST, 'g-recaptcha-response', FILTER_SANITIZE_STRING);
//     $ip = $_SERVER['REMOTE_ADDR'];
//     $url = 'https://www.google.com/recaptcha/api/siteverify';
//     $data = array('secret' => $secret, 'response' => $response, 'remoteip' => $ip);
//     $options = array(
//         'http' => array(
//             'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
//             'method'  => 'POST',
//             'content' => http_build_query($data)
//         )
//     );
//     $context  = stream_context_create($options);
//     $response = file_get_contents($url, false, $context);
//     $responseKeys = json_decode($response, true);

//     if (!$responseKeys["success"]) {
//         header('Location:' . $exit . '?recaptcha_error=1');
//         exit;
//     }
//     $db->postLogin($_POST['code']);
// } else {
//     if ($_SESSION["id"] == "") {
//         header('Location:' . $exit); //si existe la sesión
//         exit;
//     }
// }
// $gano = $db->postGano($_SESSION["id"]);
// $gano = $gano->fetch_row();
// //cuando el usuario ya redimio
// $boolGano = (isset($gano[3]) && !empty($gano[3])) ? true : false;
// if ($boolGano) {
//     header('location:/redimio');
// }
if (!isset($_SESSION["idmask"]) || $_SESSION["idmask"] == "") {
    header('Location:' . $exit); //si existe la sesión
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'app/partials/metadata.php'; ?>
    <?php include __DIR__ . '/partiales/assets-css.php'; ?>
    <title>Banco de Bogotá</title>
</head>

<body>
    <?php include 'partiales/header.php'; ?>


    <?php include 'app/partials/tagManagerBody.php'; ?>
    <section class="clsMainBanner alianzas">
        <div>
            <div class="container banner-int">
                <div class="row">
                    <div class="col-md-6">
                        <div class="content-banner">

                            <h4>Aprovecha estas ofertas, llega más rápido a tu meta y ahorra</h4>
                            <p class="basic-text white">Alcanza tu meta, redime un bono en moda o en entretenimiento, restaurantes y mucho más. Sigue comprando y llévate uno de los 10 bonos mensuales de Aviatur.</p>
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
        <section class="clsAlianzas">

            <div class="container grid-alianzas">
                <div class="row">

                    <div class="col-md-4">
                        <div class="card-alianza">
                            <div class="content-image">
                                <img src="assets/alianzas/bg-ifood.png" alt="bg-merqueo" class="bg-alianza ">
                            </div>
                            <div class="description">
                                <div class="content-logo">
                                    <img src="assets/alianzas/logo-ifood.png" alt="logo-merqueo" class="logo-alian wow animated zoomIn">
                                    <div class="discount">
                                        <h4>iFood</h4>

                                    </div>
                                </div>
                                <div class="offer">
                                    <p>Vive más experiencias comiendo lo que más te gusta.Recibe un bono de <strong>$15.000 pesos</strong> por compras superiores a $20.000 en restaurantes participantes en la App iFood. Activa este descuento usando el código <strong>BOGOTAFOOD15.</strong> </p>
                                </div>

                            </div>
                            <a href="https://vivetuscomprasygana.com/assets/alianzas/pdf/tyc-ifood.pdf" target="_blank" class="btn yellow">Ver más</a>

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card-alianza">
                            <div class="content-image">
                                <img src="assets/alianzas/bg-merqueo.png" alt="bg-merqueo" class="bg-alianza ">
                            </div>
                            <div class="description">
                                <div class="content-logo">
                                    <img src="assets/alianzas/logo-merqueo.png" alt="logo-merqueo" class="logo-alian wow animated zoomIn">
                                    <div class="discount">
                                        <h4>Haz mercado rápido, fácil y con los mejores precios en Merqueo</h4>

                                    </div>
                                </div>
                                <div class="offer">
                                    Usa el código <strong>BBOGOTAMASTER</strong> y recibe el 25% de descuento en compras superiores a $150.000, pagando en línea con tus <strong> Tarjetas Mastercard del Banco de Bogotá.</strong>
                                    <p>Promoción válida hasta el 30 de abril de 2022</p>
                                    <p>Aplican términos y condiciones</p>
                                </div>

                            </div>
                            <a href="https://vivetuscomprasygana.com/assets/alianzas/pdf/tyc-merqueo.pdf" target="_blank" class="btn yellow">Ver más</a>

                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="card-alianza">
                            <div class="content-image">
                                <img src="assets/alianzas/bg-laika.png" alt="bg-laika" class="bg-alianza ">
                            </div>
                            <div class="description">
                                <div class="content-logo">
                                    <img src="assets/alianzas/logo-laika.png" alt="logo-laika" class="logo-alian wow animated zoomIn">
                                    <div class="discount">
                                        <h4>¡Consiente tu peludo!</h4>

                                    </div>
                                </div>
                                <div class="offer">
                                    Pagando con tu tarjeta <strong>Mastercard Banco de Bogotá</strong> recibe la membresía de Laika a precio exclusivo: <strong>$55.000 COP.</strong> Precio normal <del><strong>$69.900</strong></del> (Aprox. 20% de descuento) Código: MASTERPETSLAIKA.
                                </div>

                            </div>
                            <a href="https://vivetuscomprasygana.com/assets/alianzas/pdf/tyc-laika.pdf" target="_blank" class="btn yellow">Ver más</a>

                        </div>
                    </div>

                    <!-- End Alianza salud ya -->
                    <div class="col-md-4">
                        <div class="card-alianza">
                            <div class="content-image">
                                <img src="assets/alianzas/bg-auteco.png" alt="" class="bg-alianza ">
                            </div>
                            <div class="description">
                                <div class="content-logo">
                                    <img src="assets/alianzas/logo-auteco.png" alt="" class="logo-alian wow animated zoomIn">
                                    <div class="discount">
                                        <h4>Muévete a donde quieras</h4>

                                    </div>
                                </div>
                                <div class="offer">
                                    <p><strong>10% de descuento</strong> en referencias seleccionadas de Auteco Mobility por medio de su plataforma online: <a href="https://www.autecomobility.com/">www.autecomobility.com</a> (Aplica para Bicicletas, Patinetas y Motos seleccionadas) pagando con tarjetas Mastercard Banco de Bogotá.</p>
                                </div>

                            </div>
                            <a href="https://vivetuscomprasygana.com/assets/alianzas/pdf/tyc-auteco.pdf" target="_blank" class="btn yellow">Ver más</a>

                        </div>
                    </div>





                    <?php
                    if (false) { ?>
                        <!-- End Alianza salud ya -->
                        <div class="col-md-4">
                            <div class="card-alianza">
                                <div class="content-image">
                                    <img src="assets/alianzas/bg-citiparking.png" alt="" class="bg-alianza ">
                                </div>
                                <div class="description">
                                    <div class="content-logo">
                                        <img src="assets/alianzas/logo-cityparking.png" alt="" class="logo-alian wow animated zoomIn">
                                        <div class="discount">
                                            <h4>1 hora gratis</h4>

                                        </div>
                                    </div>
                                    <div class="offer">
                                        <p>Paga tu estacionamiento en City Parking con tu <span>Tarjeta de Crédito Mastercard Banco de Bogotá y recibe</span> 1 hora gratis.</p>


                                    </div>

                                </div>
                                <a href="https://vivetuscomprasygana.com/assets/alianzas/pdf/tyc-city-parking.pdf" target="_blank" class="btn yellow">Ver más</a>

                            </div>
                        </div>
                        <!-- Alianza salud ya -->
                        <div class="col-md-4">
                            <div class="card-alianza">
                                <div class="content-image">
                                    <img src="assets/alianzas/bg-nespresso.png" alt="" class="bg-alianza ">
                                </div>
                                <div class="description">
                                    <div class="content-logo">
                                        <img src="assets/alianzas/logo-nespresso.png" alt="" class="logo-alian wow animated zoomIn">
                                        <div class="discount">
                                            <h4>¡Regala café, regala momentos únicos en Amor y Amistad!</h4>

                                        </div>
                                    </div>
                                    <div class="offer">

                                        <p>Compra con tus <strong>Tarjetas Mastercard Banco de Bogotá</strong> en Nespresso y recibe:</p>
                                        <p>- <span>Espumador de leche gratis,</span> por la compra de Máquina de café EssenzaMini Silver + 50 cápsulas con el código <span>COMBOCAFE.</span> </p>
                                        <p>- <span>40% de descuento</span> en Pack de 100 cápsulas + Espumador de leche con el código <span>COMBOCONESPUMADOR.</span> </p>

                                    </div>

                                </div>
                                <a href="https://vivetuscomprasygana.com" target="_blank" class="btn yellow">Ver más</a>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-alianza">
                                <div class="content-image">
                                    <img src="assets/alianzas/bg-muybacano.jpg" alt="" class="bg-alianza">
                                    <div class="overlay">

                                        <img src="assets/alianzas/logo-muybacano.png" alt="" class="logo-alian wow animated zoomIn">

                                        <div class="content-title">
                                            <h3>MUY BACANO</h3>
                                            <p>¡Encuentra todo lo que te gusta con precios más bajos!</p>
                                        </div>
                                    </div>

                                </div>
                                <div class="description">

                                    <p> Disfruta lo mejor de Muy Bacano con tu <strong>Tarjeta de Crédito Mastercard del Banco de Bogotá</strong> y recibe 15% de descuento con el <strong>código: MBBDB</strong> </p>
                                    <p>Promoción válida del 19 de febrero al 19 de mayo de 2021 </p>
                                    <p>*El descuento no tiene límite de uso durante su vigencia y, además, es acumulable con otras promociones activas de la página.</p>


                                </div>

                                <a href="https://vivetuscomprasygana.com/assets/alianzas/pdf/tyc-muybacano.pdf" target="_blank" class="btn yellow">Ver más</a>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card-alianza">
                                <div class="content-image">
                                    <img src="assets/alianzas/bg-muebles.jpg" alt="" class="bg-alianza">
                                    <div class="overlay">

                                        <img src="assets/alianzas/logo-muebles.png" alt="" class="logo-alian wow animated zoomIn">

                                        <div class="content-title">
                                            <h3>MUEBLES 2020</h3>
                                            <p>Renueva el ambiente de todos tus espacios</p>
                                        </div>
                                    </div>

                                </div>
                                <div class="description">

                                    <p>Cambia los muebles de tu hogar comprando con tu <strong>Tarjeta de Crédito Mastercard Banco de Bogotá</strong> y recibe hasta el 50% de descuento con el código mastermuebles2020.</p>

                                    <p>Promoción válida del 1 de febrero al 19 de mayo de 2021.</p>


                                </div>

                                <a href="https://vivetuscomprasygana.com/assets/alianzas/pdf/tyc-muebles.pdf" target="_blank" class="btn yellow">Ver más</a>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card-alianza">
                                <div class="content-image">
                                    <img src="assets/alianzas/bg-samsung.png" alt="" class="bg-alianza">
                                    <div class="overlay">

                                        <img src="assets/alianzas/logo-samsung.png" alt="samsung" class="logo-alian wow animated zoomIn">

                                        <div class="content-title">
                                            <h3>Samsung</h3>
                                            <p>Mantente siempre <br> conectado</p>
                                        </div>
                                    </div>

                                </div>
                                <div class="description">

                                    <p> Paga con tus <strong>Tarjetas de Crédito Mastercard Banco de Bogotá</strong> e ingresa a la plataforma el código: <strong>Mastercard</strong> y obtén 20% de descuento en productos seleccionados. </p>
                                    <p> Válido hasta el 21 de abril de 2021.</p>


                                </div>

                                <a href="https://vivetuscomprasygana.com/assets/alianzas/pdf/tyc-samsung.pdf" target="_blank" class="btn yellow">Ver más</a>

                            </div>
                        </div>




                        <div class="col-md-4">
                            <div class="card-alianza">
                                <div class="content-image">
                                    <img src="assets/alianzas/bg-takami.png" alt="" class="bg-alianza">
                                    <div class="overlay">

                                        <img src="assets/alianzas/logo-takami.png" alt="distrihogar" class="logo-alian wow animated zoomIn">

                                        <div class="content-title">
                                            <h3>Takami</h3>
                                            <p>Cocina para <br> Todos los dias</p>
                                        </div>
                                    </div>

                                </div>
                                <div class="description">
                                    <p> Pagando con tus <strong>Tarjetas de Crédito Mastercard® Banco de Bogotá,</strong> tienes descuentos del 20% en menús para terminar en casa de restaurantes seleccionados de Takami pidiendo a través de Rappi o en takami.co/mastercard</p>
                                    <p>*Válido hasta el 19 de abril de 2021. </p>
                                </div>

                                <a href="https://vivetuscomprasygana.com/assets/alianzas/pdf/tyc-takami.pdf" target="_blank" class="btn yellow">Ver más</a>

                            </div>
                        </div>


                        <!-- Alianza Domicilios -->
                        <div class="col-md-4">
                            <div class="card-alianza">
                                <div class="content-image">
                                    <img src="assets/alianzas/bg-domicilios.png" alt="" class="bg-alianza">
                                    <div class="overlay">

                                        <img src="assets/alianzas/logo-domicilios.png" alt="" class="logo-alian wow animated zoomIn">

                                        <div class="content-title">
                                            <h3>Domicilios.com</h3>
                                            <p>Siempre recibes más con tu MasterCard</p>
                                        </div>
                                    </div>

                                </div>
                                <div class="description">

                                    <p> Pide tus platos favoritos en Domicilios.com, paga online con tu <strong>Tarjeta Débito o Crédito Mastercard Banco de Bogotá.</strong> </p>
                                    <p>Lunes, miércoles y jueves obtienes hasta el 30% de descuento en restaurantes. Además, cambiando tu método de pago por Tarjeta Débito o Crédito Mastercard Banco de Bogotá, recibes un cupón de $10.000 COP que puedes usar en la siguiente compra. </p>
                                    <p>Válida del 22 de febrero al 22 de marzo de 2021.</p>


                                </div>

                                <a href="https://vivetuscomprasygana.com/assets/alianzas/pdf/tyc-domicilios.pdf" target="_blank" class="btn yellow">Ver más</a>

                            </div>
                        </div>
                        <!-- End Alianza Domiciulios -->
                        <!-- Alianza Domicilios -->
                        <div class="col-md-4">
                            <div class="card-alianza">
                                <div class="content-image">
                                    <img src="assets/alianzas/bg-fitpal.png" alt="" class="bg-alianza">
                                    <div class="overlay">

                                        <img src="assets/alianzas/logo-fitpal.png" alt="" class="logo-alian wow animated zoomIn">

                                        <div class="content-title">
                                            <h3>Fitpal</h3>
                                            <p>Cuidar tu cuerpo y mente desde casa, es esencial</p>
                                        </div>
                                    </div>

                                </div>
                                <div class="description">
                                    <p>Paga tu suscripción mensual a Fitpal Home con tu <strong>Tarjetas de Crédito Mastercard del Banco de Bogotá.</strong> y recibe un mes gratis. </p>
                                    <p>Promoción válida del 25 de enero al 15 de marzo de 2021.</p>


                                </div>

                                <a href="https://www.fitpal.co/terms-home" class="btn yellow">Ver más</a>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-alianza">
                                <div class="content-image">
                                    <img src="assets/alianzas/bg-casagranjero.png" alt="" class="bg-alianza">
                                    <div class="overlay">


                                        <!-- Alianza Domicilios -->
                                        <div class="col-md-4">
                                            <div class="card-alianza">
                                                <div class="content-image">
                                                    <img src="assets/alianzas/bg-domicilios.png" alt="" class="bg-alianza">
                                                    <div class="overlay">

                                                        <img src="assets/alianzas/logo-domicilios.png" alt="" class="logo-alian wow animated zoomIn">

                                                        <div class="content-title">
                                                            <h3>Domicilios.com</h3>
                                                            <p>Pide Domicilio y Recibe tu Pedido Donde Estés</p>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="description">

                                                    <p>30% de descuento pagando con tus <strong>Tarjetas de Crédito Mastercard del Banco de Bogotá.</strong> </p>
                                                    <p>Aplica para restaurantes y café los martes y viernes.</p>


                                                </div>

                                                <a href="https://vivetuscomprasygana.com/assets/alianzas/pdf/tyc-domicilios.pdf" target="_blank" class="btn yellow">Ver más</a>

                                            </div>
                                        </div>
                                        <!-- End Alianza Domiciulios -->

                                        <div class="col-md-4">
                                            <div class="card-alianza">
                                                <div class="content-image">
                                                    <img src="assets/alianzas/bg-casagranjero.png" alt="" class="bg-alianza">
                                                    <div class="overlay">


                                                        <img src="assets/alianzas/logo-casagranjero.png" alt="LA CASA DEL GRANJERO" class="logo-alian wow animated zoomIn">

                                                        <div class="content-title">
                                                            <h3>LA CASA DEL GRANJERO</h3>
                                                            <p>Encuentra todo <br> lo que mascota necesita</p>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="description">

                                                    <p> Paga con tu <strong>Tarjeta de Crédito Mastercard del Banco de Bogotá</strong> y recibe <strong>7%</strong> en servicios seleccionados para tu mascota, en La Casa Del Granjero. </p>
                                                    <p>Promoción válida del 5 de febrero al 15 de mayo de 2021.</p>


                                                </div>

                                                <a href="https://vivetuscomprasygana.com/assets/alianzas/pdf/tyc-casa-del-granjero.pdf" target="_blank" class="btn yellow">Ver más</a>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card-alianza">
                                                <div class="content-image">
                                                    <img src="assets/alianzas/bg-distrihogar.png" alt="" class="bg-alianza">
                                                    <div class="overlay">

                                                        <img src="assets/alianzas/logo-distrihogar.png" alt="distrihogar" class="logo-alian wow animated zoomIn">

                                                        <div class="content-title">
                                                            <h3>DISTRIHOGAR</h3>
                                                            <p>Mantente siempre <br> conectado</p>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="description">

                                                    <p> Paga con tus <strong>Tarjetas de Crédito Mastercard Banco de Bogotá</strong> e ingresa a la plataforma el código: <strong>Mastercard</strong> y obtén 20% de descuento en productos seleccionados. </p>
                                                    <p> Válido hasta el 21 de abril de 2021.</p>


                                                </div>

                                                <a href="https://vivetuscomprasygana.com/assets/alianzas/pdf/tyc-samsung.pdf" target="_blank" class="btn yellow">Ver más</a>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card-alianza">
                                                <div class="content-image">
                                                    <img src="assets/alianzas/bg-cornershop.png" alt="" class="bg-alianza">
                                                    <div class="overlay">

                                                        <img src="assets/alianzas/logo-cornershop.png" alt="" class="logo-alian wow animated zoomIn">

                                                        <div class="content-title">
                                                            <h3>Cornershop</h3>
                                                            <p>TUS COMPRAS EL MISMO DIA</p>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="description">
                                                    <p>Paga tu suscripción mensual a Fitpal Home con tu <strong>Tarjetas de Crédito Mastercard del Banco de Bogotá.</strong> y recibe un mes gratis. </p>
                                                    <p>Promoción válida del 25 de enero al 15 de marzo de 2021.</p>


                                                </div>

                                                <a href="https://www.fitpal.co/terms-home" class="btn yellow">Ver más</a>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card-alianza">
                                                <div class="content-image">
                                                    <img src="assets/alianzas/bg-plaz.png" alt="" class="bg-alianza">
                                                    <div class="overlay">

                                                        <img src="assets/alianzas/logo-plaz.png" alt="" class="logo-alian wow animated zoomIn">

                                                        <div class="content-title">
                                                            <h3>Plaz</h3>
                                                            <p>Mercado fresco donde sea</p>
                                                        </div>
                                                    </div>

                                                </div>

                                                <a href="" class="btn yellow">Ver más</a>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="card-alianza">
                                                    <div class="content-image">
                                                        <img src="assets/alianzas/bg-emi.png" alt="" class="bg-alianza">
                                                        <div class="overlay">

                                                            <img src="assets/alianzas/logo-emi.png" alt="" class="logo-alian wow animated zoomIn">

                                                            <div class="content-title">
                                                                <h3>EMI</h3>
                                                                <p>Emi cuida tu bienestar y el de toda la familia</p>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <a href="" class="btn yellow">Ver más</a>

                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card-alianza">
                                                    <div class="content-image">
                                                        <img src="assets/alianzas/bg-cosmocookies.png" alt="" class="bg-alianza">
                                                        <div class="overlay">

                                                            <img src="assets/alianzas/logo-cosmocookies.svg" alt="" class="logo-alian wow animated zoomIn">

                                                            <div class="content-title">
                                                                <h3>Cosmo cookies</h3>
                                                                <p>Un universo De sabores</p>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <a href="" class="btn yellow">Ver más</a>

                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card-alianza">
                                                    <div class="content-image">
                                                        <img src="assets/alianzas/bg-cascabel.png" alt="" class="bg-alianza">
                                                        <div class="overlay">

                                                            <img src="assets/alianzas/logo-cascabel.svg" alt="" class="logo-alian wow animated zoomIn">

                                                            <div class="content-title">
                                                                <h3>Cascabel</h3>
                                                                <p>Reposteria gourmet</p>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <a href="" class="btn yellow">Ver más</a>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="card-alianza">
                                                    <div class="content-image">
                                                        <img src="assets/alianzas/bg-patprimo.png" alt="" class="bg-alianza">
                                                        <div class="overlay">

                                                            <img src="assets/alianzas/logo-patprimo.png" alt="" class="logo-alian wow animated zoomIn">

                                                            <div class="content-title">
                                                                <h3>PATPRIMO</h3>
                                                                <p>Descubre Ropa de Moda para Mujer, Hombre e infantil.</p>
                                                            </div>
                                                        </div>

                                                    </div>



                                                    <a href="" class="btn yellow">Ver más</a>

                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card-alianza">
                                                    <div class="content-image">
                                                        <img src="assets/alianzas/bg-seven.png" alt="" class="bg-alianza">
                                                        <div class="overlay">

                                                            <img src="assets/alianzas/logo-seven.png" alt="" class="logo-alian wow animated zoomIn">

                                                            <div class="content-title">
                                                                <h3>Seven seven</h3>
                                                                <p>ROPA DE MODA PARA LOS 7 DÍAS DE LA SEMANA.</p>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <a href="" class="btn yellow">Ver más</a>

                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card-alianza">
                                                    <div class="content-image">
                                                        <img src="assets/alianzas/bg-peopleplays.png" alt="" class="bg-alianza">
                                                        <div class="overlay">

                                                            <img src="assets/alianzas/logo-peopleplays.png" alt="" class="logo-alian wow animated zoomIn">

                                                            <div class="content-title">
                                                                <h3>People plays</h3>
                                                                <p>Ropa deportiva compra online y recibe en casa.</p>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <a href="" class="btn yellow">Ver más</a>

                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>


                                    <!-- End Alianza plaz -->

                                    </div>

                                </div>

                                <a href="https://www.bancodebogota.com/wps/themes/html/minisitios/alianzasnuevo/index.html" target="_blank" class="btn blue-alinz">Ingresar a más ofertas en Outlet Banco de Bogotá</a>


        </section>
        <img src="assets/logos/vigilado-int.svg" alt="" class="vigilado interno">
    </div>


    <?php
    include __DIR__ . '/partiales/footer.php';

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

</body>

</html>