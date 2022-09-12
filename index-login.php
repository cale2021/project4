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
                        <img src="assets/logos/logo-banco-bogota.svg" alt="">
                    </a>
                </div>
            </div>
        </div>
        <div class="container">

            <div class="row cont-log">
                <div class="col-md-6">
                    <div class="content-title-login">
                        <div class="yellow-word">
                            <span>VIVE TUS</span> <br>
                            <span>COMPRAS Y GANA</span>
                        </div>
                        <p>
                            Usa tus Tarjetas de Crédito Mastercard del Banco de Bogotá, cumple tu meta <span>y podrás ganar un bono para redimir en el comercio que más te guste.</span> Regístrate aquí:
                        </p>
                    </div>
                    <div class="content-form">
                        <div class="content-form">
                            <!-- <form id="form_login" action="/mecanica" method="POST" autocomplete="off">
                                <div class="first-form">
                                    <div class="form-group">
                                        <label for="">Número de documento:</label>
                                        <input type="text" max="5" name="user_s" id="" placeholder="Ingresa con los últimos 5 dígitos de tu documento">
                                        <div class="invalid-feedback <?php echo (isset($_GET['login_error']) || isset($_GET['recaptcha_error'])) ? 'active' : ''; ?>">
                                            <?php if (isset($_GET['login_error']))
                                                echo 'Los datos que ingresaste no son correctos, verifica e intenta nuevamente.';
                                            else if (isset($_GET['recaptcha_error']))
                                                echo 'Error con el recaptcha, intenta nuevamente.';
                                            else
                                                echo 'Debes ingresar tu codigo único de usuario.'; ?>
                                        </div>
                                    </div>
                                    <a href="#" id="sendFirstForm" class="btn yellow">Continuar</a>
                                </div>

                                <div class="second-form">
                                    <div class="form-group date-inp">
                                        <label for="">Fecha de nacimiento:</label>
                                        <input type="text" id="datepicker" name="date_s" placeholder="Ingresa tu fecha de nacimiento">
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
                                                He leído y acepto los <a href="">Términos y condiciones</a>
                                            </div>

                                        </div>
                                    </div>
                                    <a href="#" class="send-form-login btn yellow">Ingreso</a>
                                </div>
                                <input type="hidden" value="" name="g-recaptcha-response" id="g-recaptcha-response">
                                <input type="hidden" value="" name="user_name">
                                <input type="hidden" value="" name="user_password">
                                <input type="hidden" value="<?php echo $_SESSION["csrf_token"]; ?>" name="csrf">
                            </form>

                        </div> -->

                        </div>


                    </div>
                    <div class="col-md-6"></div>
                </div>
            </div>

            <img src="assets/logos/vigilado-footer.png" alt="" class="vigilado login">

        </div>

        <?php include __DIR__ . '/partiales/footer.php';  ?>

        <?php include __DIR__ . '/partiales/assets-js.php'; ?>
        <?php
        if ($debugmode) { ?>
            <script src="/js/index-debug.js"></script>
            <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
            <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
        <?php } else { ?>
            <script src="https://www.google.com/recaptcha/api.js?render=6Lf6HAAaAAAAAPCJp2-Bv5ljpsxz9yef-3LX9gLs"></script>
            <script src="/js/index.js"></script>
        <?php } ?>
</body>

</html>