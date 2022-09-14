<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include __DIR__ . '/app/partials/metadata.php';
  include __DIR__ . '/partiales/assets-css.php';
  ?>

  <title>Banco de Bogotá</title>
</head>

<body>

  <div class="main-contentbg faq">
    <header>
      <div class="container logo">
        <div class="row">
          <div class="col-md-12">
            <div class="logo">
              <img src="assets/logos/logo-banco-bogota.png" alt="">
            </div>
          </div>
        </div>
      </div>
    </header>


    <div class="banner-int clsMainBanner">
      <div class="container">

        <div class="row">
          <div class="col-md-6">
            <div class="content-banner">
              <h1>Preguntas frecuentes</h1>
            </div>
          </div>
          <div class="col-md-5"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="content-int-faq">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div id="accordion" class="faq-content">
            <div class="card">
              <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                  <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Sed ut perspiciatis unde omnis iste natus unde ati lim
                    <img src="assets/icons/ico-down.svg" alt="">
                  </button>
                </h5>
              </div>

              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit.</p>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="card-header" id="headingTwo">
                <h5 class="mb-0">
                  <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    ¿Quiénes pueden participar en el Programa?
                    <img src="assets/icons/ico-down.svg" alt="">
                  </button>
                </h5>
              </div>
              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                  <p>

                    Pueden participar el titular de una Tarjeta de Crédito Mastercard Banco de Bogotá, que haya sido previamente seleccionado de acuerdo con los criterios definidos por el Banco para esta campaña y que, además hayan recibido una invitación por correo electrónico, o SMS y que posteriormente se hayan inscrito en la plataforma.
                  </p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>



  <?php
  include __DIR__ . '/partiales/footer.php';

  include __DIR__ . '/partiales/assets-js.php';
  ?>
</body>

</html>