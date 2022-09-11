<?php
include "./app/inc/security.php";
include "./app/inc/server.php";
include "./app/inc/funtions.php";
require_once "./app/inc/db.php";

if (!isset($_SESSION["idmask"]) || $_SESSION["idmask"] == "") {
    header('Location:' . $exit); //si no existe la sesión
    exit;
}

$db = new ChefDB();
$user_tracing = $db->getUserTracing($_SESSION['idmask']);
$user_goal = $db->getUserGoal($_SESSION['idmask']);
$current_block = $_ENV['CAMPAIGN_BLOCK'];
$campaign_closure = $_ENV['CAMPAIGN_CLOSURE'];

$end_campaing_user_type = $db->getEndCampaingUserType($_SESSION['idmask']);
$end_campaing_text = "";

switch ($end_campaing_user_type) {
    case 1:
        // Ganó Ya redimió almenos 1 vez
        $end_campaing_text = 'Recuerda que puedes ver tus bonos redimidos durante la campaña. Descárgalos las veces que quieras. ¡Gracias por participar';
        break;
    case 2:
        // Ganador con bono disponible para redimir
        $end_campaing_text = 'Recuerda que aún tienes pendiente redimir tu bono en el comercio que más te guste. ¡Gracias por participar!';
        break;
    case 3:
        // No ganador - último no ganador
        $end_campaing_text = 'En esta ocasión, no ganaste. ¡Gracias por participar!';
        break;
    case 4:
        // No ganador - último vencido
        $end_campaing_text = 'El tiempo para redimir tus bonos, venció. ¡Gracias por participar!';
        break;
}

$percentage_current_block = $user_tracing ? $user_tracing['percentage_' . $current_block] : 0;
$amount_current_block = $user_tracing ? $user_tracing['amount_' . $current_block] : 0;
$percentage_current_block_bar = $percentage_current_block;
if ($percentage_current_block > 100) {
    $percentage_current_block_bar = 100;
}

$trx_current_block = $user_tracing ? $user_tracing['trx_' . $current_block] : 0;
$percentage_current_trx_block = (int)$user_goal['goal_trx_' . $current_block] != 0 ? ($trx_current_block / $user_goal['goal_trx_' . $current_block]) * 100 : 0;
$percentage_current_trx_block_bar = $percentage_current_trx_block;
if ($percentage_current_trx_block > 100) {
    $percentage_current_trx_block_bar = 100;
}

$current_date_text = $db->getTextInfo('current'); // texto rango fecha periodo actual
$update_date_text = $db->getTextInfo('update'); // texto fecha última actualización

// Ganador bono pendiente por redimir
$card_1 = '
<div class="col-md-3">
<div class="award  winner">
<div class="round-porcent">
@percentage@%
</div>
    
    <div class="title-price">
        <span class="info">
           Selecciona tu bono a redimir
        </span>
        <span class="date">Tu progreso del @date_text@</span>
    </div>
    <a href="/premios" class="btn yellow">Ver bonos</a>
</div>
</div>';

// Bono ya redimido
$card_2 = '
<div class="col-md-3">
<div class="award winner download">
<div class="round-porcent">
@percentage@%
</div>
    
    <div class="title-price">
    <img src="@logo@" alt="@logo_alt@">
        <span class="name">@name@</span>
        <span class="price">$@price@</span>
        <span class="date">Tu progreso del @date_text@</span>
    </div>
    <a href="/descargar?block=@block@"  class="btn yellow download-award">Descargar</a>
</div>
</div>';

// Redención vencida
$card_3 = '
<div class="col-md-3">
    <div class="award time-out">
        
        <div class="title-price">
            <span class="info">
             Tu progreso del
            </span>
            <span class="date">@date_text@</span>

            <div class="round-porcent">
            @percentage@%
            </div>
        </div>
        <a href="" class="btn disabled ">Vencido</a>
    </div>
</div>
';

// No gana
$card_4 = '
<div class="col-md-3">
    <div class="award no-winner">
       
        <div class="title-price">
        <span class="info">Tu progreso</span>
        <span class="date">@date_text@ fue de</span>
        <div class="round-porcent">
        @percentage@%
        </div>
            <span class="date">
                En este mes no ganaste, pero sigue participando para cumplir tu meta
                y ganar el próximo mes.
            </span>
        </div>
       
    </div>
</div>
';

$current_block_int = (int)$current_block;
$awards_show_html = '';

for ($i = 1; $i <= $current_block_int; $i++) {
    $user_tracing_winner = $user_tracing ? $user_tracing['winner_' . $i] : 0;
    $user_tracing_percentage = $user_tracing ? $user_tracing['percentage_' . $i] : 0;
    switch ($user_tracing_winner) {
        case '0': // No gana
            if ($i < $current_block_int || $campaign_closure == 'Y') {
                $block_card_4 = $card_4;
                $card_date_text = $db->getTextInfo('card', $i); // texto fecha
                $block_card_4 = progressReplaceCardDateValue($block_card_4, $card_date_text);
                $block_card_4 = progressReplaceCardPercentageValue($block_card_4, $user_tracing_percentage);
                $awards_show_html .= $block_card_4; // No gana mes anterior
            }
            break;
        case '1': // Ganador
            $block = $i;
            $redemption = $db->getOneRedemption($_SESSION["idmask"], $block)->fetch_assoc();
            if (!$redemption) {
                $block_card_1 = $card_1;
                $card_date_text = $db->getTextInfo('card', $i); // texto fecha
                $block_card_1 = progressReplaceCardDateValue($block_card_1, $card_date_text);
                $block_card_1 = progressReplaceCardPercentageValue($block_card_1, $user_tracing_percentage);
                $awards_show_html .= $block_card_1; // Ganador Redención pendiente
            } else {
                $block_card_2 = $card_2;
                $block_card_2 = progressReplaceCard2Values($block_card_2, $redemption);
                $card_date_text = $db->getTextInfo('card', $i); // texto fecha
                $block_card_2 = progressReplaceCardDateValue($block_card_2, $card_date_text);
                $block_card_2 = progressReplaceCardPercentageValue($block_card_2, $user_tracing_percentage);
                $awards_show_html .= $block_card_2; // Ganador Bono ya redimido
            }
            break;
        case '2': // Redención vencida
            $block = $i;
            $redemption = $db->getOneRedemption($_SESSION["idmask"], $block)->fetch_assoc();
            if (!$redemption) {
                $block_card_3 = $card_3;
                $card_date_text = $db->getTextInfo('card', $i); // texto fecha
                $block_card_3 = progressReplaceCardDateValue($block_card_3, $card_date_text);
                $block_card_3 = progressReplaceCardPercentageValue($block_card_3, $user_tracing_percentage);
                $awards_show_html .= $block_card_3;
            } else {
                $block_card_2 = $card_2;
                $block_card_2 = progressReplaceCard2Values($block_card_2, $redemption);
                $card_date_text = $db->getTextInfo('card', $i); // texto fecha
                $block_card_2 = progressReplaceCardDateValue($block_card_2, $card_date_text);
                $block_card_2 = progressReplaceCardPercentageValue($block_card_2, $user_tracing_percentage);
                $awards_show_html .= $block_card_2; // Ganador Bono ya redimido
            }
            break;
        default:
            $prueba = 0;
            break;
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'app/partials/metadata.php'; ?>
    <?php include __DIR__ . '/partiales/assets-css.php'; ?>
    <title>Banco de Bogotá</title>
</head>

<style nonce="<?php echo $cspNonceStyles[1]; ?>">
    .round-number {
        left: calc(<?php echo $percentage_current_block_bar . '%'; ?> - 155px);
    }

    .progress-bar.monto {
        width: <?php echo $percentage_current_block_bar . '%' ?>;
    }

    .progress-bar.trx {
        width: <?php echo $percentage_current_trx_block_bar . '% !important' ?>;
    }
</style>

<body>
    <?php include 'partiales/header.php'; ?>


    <?php include 'app/partials/tagManagerBody.php'; ?>
    <section class="clsMainBanner progreso">
        <div>
            <div class="container banner-int">
                <div class="row">
                    <div class="col-md-6">
                        <div class="content-banner">
                            <h4>Conoce el progreso de tus compras</h4>
                            <p class="basic-text white">¡Descubre en cada compra más experiencias por vivir! Sigue realizando tus compras con tus Tarjetas de Crédito Mastercard del Banco de Bogotá y prepárate para ganar.</p>
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
        <section class="clsProgress">
            <div class="container  content-tabs-title">

                <div class="row">
                    <?php
                    if ($campaign_closure == 'Y') {
                    ?>
                        <div class="col-md-12 content-title-sect">
                            <h3>Vive tus compras ha terminado</h3>
                            <span><?php echo $end_campaing_text; ?></span>
                        </div>
                    <?php
                    }
                    ?>

                    <div class="col-md-6 content-title-sect">
                        <?php
                        if ($campaign_closure !== 'Y') {
                        ?>
                            <h3>Así van tus compras</h3>

                        <?php
                        if (!empty($current_date_text)) {
                        ?>
                            <span>Tu progreso del <?php echo $current_date_text; ?></span>
                        <?php
                        }
                        ?>

                            <p>Sigue comprando para cumplir tu meta mensual, recuerda que tienes hasta 4 oportunidades de ganar. </p>
                        <?php
                        } else {
                        ?>
                            <p>Continúa usando tus Tarjetas de Crédito Mastercard del Banco de Bogotá y disfruta todos sus beneficios.</p>
                        <?php
                        }
                        ?>
                    </div>
                </div>



            </div>
            <div class="container">
                <div class="row goalsUser">
                    <div class="col-md-6">
                        <div class="content-progress-bar">
                            <h3>Meta en monto</h3>
                            <div class="round-number">
                                <div><span>
                                        <p>llevas</p> <?php echo '$' . number_format($amount_current_block, 0, ',', '.'); ?> </div>

                                <?php echo $percentage_current_block; ?>%</span>

                            </div>
                            <div class="progress">
                                <div class="progress-bar monto" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <dov class="content-limit">
                                <span class="min">$0</span>
                                <span class="max"><?php echo '$' . number_format($user_goal['goal_' . $current_block], 0, ',', '.'); ?></span>
                            </dov>


                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="content-progress-bar">
                            <h3>Meta en transacciones</h3>
                            <div class="round-number">
                                <div><span>
                                        <p>llevas</p> <?php echo  number_format($trx_current_block, 0, ',', '.'); ?> transacciones </div>



                            </div>
                            <div class="progress">
                                <div class="progress-bar trx" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <dov class="content-limit">
                                <span class="min">0</span>
                                <span class="max"><?php echo  number_format($user_goal['goal_trx_' . $current_block], 0, ',', '.'); ?></span>
                            </dov>


                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="content-not">
                            <div class="last-messa">
                                <strong>Última actualización:</strong> <?php echo $update_date_text; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div id="container-redemptions" class="container grid-awards content-awards-redeem">
                <div class="row">
                    <?php echo $awards_show_html; ?>
                </div>
            </div>

        </section>
        <img src="assets/logos/vigilado-int.svg" alt="" class="vigilado interno">
    </div>

    <?php
    include __DIR__ . '/partiales/footer.php';

    include __DIR__ . '/partiales/assets-js.php'; ?>

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

    <?php if (isset($_GET['redenciones']) && $_GET['redenciones'] == 1) {
    ?>
        <script type="text/javascript" nonce="<?php echo $cspNonce[0]; ?>">
            $(function($) {
                $('body,html').stop(true, true).animate({
                    scrollTop: $('#container-redemptions').offset().top
                }, 1000);
            });
        </script>
    <?php
    }

    ?>

</body>

</html>