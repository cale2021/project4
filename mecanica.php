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
                            <h2>¿Cómo ganar?</h2>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
                </div>
            </div>

        </div>


    </section>
    <div class="main-content">
        <div class="container">
            <div class="desciption-int">
                <h3>¿Viajar o probar nuevos sabores?</h3>
                <p>Con tus tarjetas de crédito Mastercard Banco de Bogotá tus compras se convierten en ese plan que tanto te gusta.</p>
                <p><strong>Estos son los pasos para ganar:</strong></p>
            </div>
            <div class="steps-win">
                <div class="step-item step-1">
                    <div class="head-item">
                        <img src="assets/mecanica/step-1.jpg" alt="">
                    </div>
                    <div class="content-item">
                        <h2>Sorprende a los que más quieres comprando con tu<br><strong>tarjeta de crédito</strong></h2>
                        <p>¡Úsala para comprar en tus tiendas favoritas! Cada compra te acerca a tu meta para ganar increíbles premios.</p>
                    </div>
                </div>
                <div class="step-item step-2">
                    <div class="head-item">
                        <img src="assets/mecanica/step-2.jpg" alt="">
                    </div>
                    <div class="content-item">
                        <h2>Cumple <strong>tu meta de compras</strong></h2>
                        <p>Además de recibir descuentos en tus comercios favoritos, cada compra te acerca más a tu meta y a la oportunidad de disfrutar planes inolvidables con tus amigos o familia.</p>
                    </div>
                </div>
                <div class="step-item step-3">
                    <div class="head-item">
                        <img src="assets/mecanica/step-3.jpg" alt="">
                    </div>
                    <div class="content-item">
                        <h2>Redime <strong>tu premio</strong></h2>
                        <p>Una vez que alcances tu meta podrás disfrutar ese plan que tanto te gusta. Usa el código que recibirás en tu correo para redimir tu bono.</p>
                    </div>
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