<?php
try {
    require_once __DIR__ . "/app/inc/security.php";
    require_once __DIR__ . "/app/inc/server.php";
    require_once __DIR__ . "/app/inc/funtions.php";
    require_once __DIR__ . "/app/inc/db.php";
    $db = new ChefDB();
    if (!$debugmode)
        header("refresh:180;url=" . $exit . '?expires=1'); //lo sacamos del sistema

    if (isset($_POST['user_name']) && isset($_POST['user_password']) && isset($_POST['csrf'])) {
        if (!$debugmode) {
            if (isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] == $_POST['csrf']) {
                unset($_SESSION['csrf_token']);
            } else {
                header('Location:' . $exit);
            }

            $response = filter_input(INPUT_POST, 'g-recaptcha-response', FILTER_SANITIZE_STRING);
            $valid = recaptcha_validate($response);
            if (!$valid) {
                header('Location:' . $exit . '?recaptcha_error=1');
                exit;
            }
        }

        if (strlen($_POST['user_name']) != 64 || strlen($_POST['user_password']) != 64) {
            header('Location:' . $exit);
            exit;
        }

        $db->postLogin($_POST['user_name'], $_POST['user_password']);
        if ($_SESSION['idmask']) {
            header('Location:' . $_SERVER['REQUEST_URI']);
            die();
        }
    } else {
        if (!isset($_SESSION["idmask"]) || $_SESSION["idmask"] == "") {
            header('Location:' . $exit); //si existe la sesión
            exit;
        }
    }
} catch (Exception $e) {
    header('Location:' . $exit); //si existe la sesión
    die();
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
    <section class="clsMainBanner mecanica">
        <div>
            <div class="container banner-int">
                <div class="row">
                    <div class="col-md-6">
                        <div class="content-banner">


                            <p class="basic-text white">Venimos recargados con increíbles bonos para que vivas nuevas experiencias.</p>
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
        <section class="clsSteps">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 content-title-sect">
                        <h3>Conoce cómo participar</h3>
                        <span>Mecánica</span>

                        <p>Usa tu <span>Tarjeta de Crédito Mastercard Banco de Bogotá</span> en todas tus compras y gana. </p>
                        <p>¡Llega más rápido a tus metas y ahorra en tus compras con la oferta de la semana y los comercios aliados!</p>
                    </div>
                    <div class="col-md-6 steps-meca">
                        <div class="item">
                            <span class="round wow animated fadeInDown">
                                1
                            </span>
                            <p>Cumple tu meta mensual de facturación.</p>
                        </div>
                        <div class="item">
                            <span class="round wow animated fadeInDown">
                                2
                            </span>
                            <p>Cumple tu meta mensual de *transacciones.</p>
                        </div>
                        <div class="item">
                            <span class="round wow animated fadeInDown">
                                3
                            </span>
                            <p>Gana un bono digital redimible en el comercio que más te guste. Puedes ganar un bono mensual.</p>
                        </div>
                        <div class="item">
                            <span class="round wow animated fadeInDown">
                                4
                            </span>
                            <p>Sigue usando tu Tarjeta de Crédito Banco de Bogotá y participa por uno de los 10 bonos mensuales de Aviatur.
                                <span class="note-step">Solo será válida una *transacción diaria por comercio.</span>
                            </p>

                        </div>

                    </div>
                </div>
            </div>
        </section>

        <section class="awards mec">
            <div class="container">
                <div class="row content-tabsmec">
                    <div class="col-md-6">
                        <div class="content-title-sect">
                            <h3 class="tabsTitle">Elige un bono de la galería que hemos armado para ti</h3>
                            <span>Premios</span>
                            <p class="descAwards">Cumple tus metas y redime tu bono digital en el comercio que más te guste.</p>
                        </div>
                        <div class="content-nav-mec">
                            <ul class="nav  tabs-awards-nav" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab-meca" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Bonos para redimir</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="aviatur-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Bonos Aviatur</a>
                                </li>

                            </ul>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active awabtn" id="home" role="tabpanel" aria-labelledby="home-tab-meca">
                                <div class="grid-awards mecanica ">


                                    <div class="row">
                                        <div class="col-md-4 items-awards-mec">
                                            <div class="award">
                                                <img src="assets/bonos/logo-frisby.png" alt="Logo Frisby" class="wow animated zoomIn">
                                            </div>
                                        </div>
                                        <div class="col-md-4 items-awards-mec">
                                            <div class="award">
                                                <img src="assets/bonos/logo-mcdonalds.png" alt="Logo mcdonalds" class="wow animated zoomIn">

                                            </div>
                                        </div>
                                        <div class="col-md-4 items-awards-mec">
                                            <div class="award">
                                                <img src="assets/bonos/logo-alkosto.png" alt="Logo Alkosto" class="wow animated zoomIn">

                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 items-awards-mec">
                                            <div class="award">
                                                <img src="assets/bonos/logo-crepes.png" alt="Logo Crepes & Waffles" class="wow animated zoomIn">

                                            </div>
                                        </div>
                                        <div class="col-md-4 items-awards-mec">
                                            <div class="award">
                                                <img src="assets/bonos/logo-pepe.png" alt="Logo Pepe Ganga" class="wow animated zoomIn">

                                            </div>
                                        </div>
                                        <div class="col-md-4 items-awards-mec">
                                            <div class="award">
                                                <img src="assets/bonos/logo-inkanta.png" alt="Logo Inkanta" class="wow animated zoomIn">

                                            </div>
                                        </div>


                                    </div>

                                    <a href="/premios" class="btn yellow">Ver más premios</a>
                                </div>

                            </div>
                            <div class="tab-pane fade awabtn" id="profile" role="tabpanel" aria-labelledby="aviatur-tab">
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
                                <a href="/premios.php#profile" class="btn yellow">Ver más premios</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            </secction>
            <img src="assets/logos/vigilado-int.svg" alt="" class="vigilado interno">
    </div>

    <div class="modal fade clsModalFinal" id="modalFinal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <h2>VIVE TUS COMPRAS Y GANA</h2>

                    <p>¡Quedan pocos días para ganar el premio mayor!</p>
                    <p>Usa tus <strong>Tarjetas de Crédito Mastercard del Banco de Bogotá</strong> y participa.</p>
                    <button type="" class="btn yellow" data-dismiss="modal" aria-label="Close">Continuar</button>

                </div>

            </div>
        </div>
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
    <script text="text/javascript" nonce="<?php echo $cspNonce[3]; ?>">
        $(function($) {
            //$('#modalFinal').modal('show');
        });
    </script>

</body>

</html>