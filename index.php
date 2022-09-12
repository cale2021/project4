<?php
require_once __DIR__ . '/app/inc/security.php';

if (isset($_SESSION["idmask"])) {
    $exit = $_ENV['SITE_URL'] . "/exit";
    header('Location:' . $exit); //si existe la sesión
    exit;
}

$_SESSION["csrf_token"] = hash('sha256', uniqid(mt_rand(), true));
$recaptcha_error = isset($_GET['recaptcha_error']) ? true : false;
$login_error = isset($_GET['login_error']) ? true : false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'app/partials/metadata.php'; ?>
    <?php include __DIR__ . '/partiales/assets-css.php'; ?>
    <title>Banco de Bogotá</title>
</head>

<style nonce="<?php echo $cspNonceStyles[0]; ?>">
    .second-form {
        display: <?php echo 'none'; ?>;
    }
</style>

<body>
    <?php include 'app/partials/tagManagerBody.php';
    ?>


    <div class="main-content bg-tpl-init">
        <div class="header-init">
            <div class="container">
                <div class="main-logo">
                    <a>
                        <img src="assets/logos/logo-banco-bogota.png" alt="">
                    </a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row cont-log">
                <div class="col-md-6">
                    <div class="content-banner-login">
                        <div class="content-title">
                            <h1>
                                Vive Tus Compras y Gana Grandes<br><strong>Experiencias</strong>
                            </h1>
                            <img src="assets/icons/list-cards.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-login">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="description-basic">
                        <p>Usa tus tarjetas de crédito Mastercard Banco de Bogotá y convierte tus compras en experiencias inolvidables.</p>
                        <p><strong>Descubre cómo y prepárate para ganar</strong></p>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="content-form">
                        <div class="content-form">
                            <form id="form_login" action="/mecanica" method="POST" autocomplete="off">
                                <div class="first-form">
                                    <div class="form-group">
                                        <label for="">Ingresa tu código de acceso</label>
                                        <input type="text" max="5" name="user_s" id="" placeholder="5 últimos dígitos de tu cédula">
                                        <div class="invalid-feedback <?php echo (isset($_GET['login_error']) || isset($_GET['recaptcha_error'])) ? 'active' : ''; ?>">
                                            <?php if (isset($_GET['login_error']))
                                                echo 'Los datos que ingresaste no son correctos, verifica e intenta nuevamente.';
                                            else if (isset($_GET['recaptcha_error']))
                                                echo 'Error con el recaptcha, intenta nuevamente.';
                                            else
                                                echo 'Debes ingresar tu codigo único de usuario.'; ?>
                                        </div>
                                    </div>
                                    <a href="#" id="sendFirstForm" class="btn blue">Continuar</a>
                                </div>

                                <div class="second-form">
                                    <div class="form-group date-inp">
                                        <label for="">Ingresa tu fecha de nacimiento</label>
                                        <input type="text" id="datepicker" name="date_s" placeholder="dd/mm/aaaa">
                                        <div class="invalid-feedback">
                                            Debe ingresar su fecha de nacimiento.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="invalid-feedback">
                                            Debe aceptar los términos y condiciones.
                                        </div>
                                        <div class="accept-tyc">
                                            <input type="checkbox" name="tyc" id="tyc" value="s">
                                            <div class="">
                                            He leído y acepto los <a href="">términos y condiciones</a>
                                            </div>

                                        </div>
                                    </div>
                                    <a href="#" class="send-form-login btn blue">Ingreso</a>
                                </div>
                                <input type="hidden" value="" name="g-recaptcha-response" id="g-recaptcha-response">
                                <input type="hidden" value="" name="user_name">
                                <input type="hidden" value="" name="user_password">
                                <input type="hidden" value="<?php echo $_SESSION["csrf_token"]; ?>" name="csrf">
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/partiales/footer.php';  ?>
    <?php include __DIR__ . '/partiales/assets-js.php'; ?>
    <?php include __DIR__ . '/partiales/modal-close-campaign.php'; ?>
    <?php
    if ($debugmode) { ?>
        <script src="/js/index-debug.js"></script>
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <?php } else { ?>
        <script src="https://www.google.com/recaptcha/api.js?render=6Lf6HAAaAAAAAPCJp2-Bv5ljpsxz9yef-3LX9gLs"></script>
        <script src="/js/index.js"></script>
    <?php } ?>

    <script text="text/javascript" nonce="<?php echo $cspNonce[2]; ?>">
        $(function($) {
            $('#modalCloseCampaign').modal('show');
        });
    </script>
</body>

</html>