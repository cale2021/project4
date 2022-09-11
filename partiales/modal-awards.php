<!-- Modal -->
<div class="modal fade modal-awards" id="contentAward" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="content-image modal-header">
                    <div class="content-logo">
                        <img src="assets/bonos/logo-rappi.png" alt="" class="logo modal-logo">
                    </div>
                </div>
                <div class="desc">
                    <h3>Bono <span class="modal-name">MacDonalds</span></h3>
                    <p>¡Presenta tu bono en el comercio correspondiente y sigue participando por el premio mayor!</p>
                    <p class="message">Con tu Tarjeta de Crédito Mastercard Banco de Bogotá puedes llevarte una gran sorpresa cada mes</p>
                </div>
                <?php if (isset($_SESSION['winner']) && $_SESSION['winner']) { ?>
                    <a href="" class="btn yellow modal-link">Redimir bono</a>
                <?php } ?>
            </div>

        </div>
    </div>
</div>