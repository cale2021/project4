<?php
$isLogin = false;
if (isset($_SESSION["idmask"]) || !empty($_SESSION["idmask"])) {
    $isLogin = true;
}
$anchoActive = true;
if ($_SERVER['SCRIPT_NAME'] != '/index.php') {
    $anchoActive = false;
}
?>
<header>
    <div class="container">
        <div class="row rw-menu">
            <div class="col-9 col-md-6">
                <div class="content-logo">
                    <a>
                        <img src="assets/logos/logo-banco-bogota.png" alt="">

                    </a>
                </div>
            </div>
            <div class="col-3 col-md-6 menu">
                <nav class="navbar navbar-expand-lg">

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <img src="assets/icons/menu.svg" alt="">
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item <?php echo ($_SERVER['PHP_SELF'] == '/mecanica.php') ? 'active' : ''; ?>">
                                <a class="nav-link" href="/mecanica">Mecánica<?php echo ($_SERVER['PHP_SELF'] == '/mecanica.php') ? '<span class="sr-only">(current)</span>' : ''; ?></a>
                            </li>
                            <li class="nav-item <?php echo ($_SERVER['PHP_SELF'] == '/progreso.php') ? 'active' : ''; ?>">
                                <a class="nav-link" href="/progreso">Progreso<?php echo ($_SERVER['PHP_SELF'] == '/progreso.php') ? '<span class="sr-only">(current)</span>' : ''; ?></a>
                            </li>
                            <li class="nav-item <?php echo ($_SERVER['PHP_SELF'] == '/alianzas.php') ? 'active' : ''; ?>">
                                <a class="nav-link" href="/alianzas">Alianzas<?php echo ($_SERVER['PHP_SELF'] == '/alianzas.php') ? '<span class="sr-only">(current)</span>' : ''; ?></a>
                            </li>
                            <li class="nav-item <?php echo ($_SERVER['PHP_SELF'] == '/premios.php') ? 'active' : ''; ?>">
                                <a class="nav-link" href="/premios">Premios<?php echo ($_SERVER['PHP_SELF'] == '/premios.php') ? '<span class="sr-only">(current)</span>' : ''; ?></a>
                            </li>
                            <li class="nav-item logout">
                                <a class="nav-link" href="/exit">Cerrar sesión
                                    <img src="assets/icons/ico-logout.png" alt="">
                                </a>
                            </li>

                        </ul>

                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>