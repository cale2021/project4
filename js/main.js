$(document).ready(function () {


    //init data modal awards




    //disabled modal block event code verification

    //disabled back button

    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };

    $(".nav-login ul > li").on("click", function (e) {
        e.preventDefault();
        if (!$(this).hasClass("active")) {
            $(".nav-login ul > li").removeClass("active");
            !$(this).addClass("active");
        }
    });


    //set title tabs

    $('#aviatur-tab').on('click', function (e) {
        $('.tabsTitle').text('Gana bonos Aviatur');
        $('.descAwards').fadeIn('slow').html('¡Gana uno de los <span>10 bonos mensuales de Aviatur!</span>  Entregaremos 40 bonos durante toda la actividad, podrás redimir el tuyo en cualquiera de los servicios que ofrece Aviatur: vuelos, paquetes, hoteles, experiencias y más.');
    });

    $('#home-tab-meca').on('click', function (e) {
        $('.tabsTitle').fadeIn('slow').text('Elige un bono de la galería que hemos armado para ti');
        $('.descAwards').fadeIn('slow').html('Cumple tus metas y redime tu bono digital en el comercio que más te guste.')
    });



    //Rotate icon faqs

    $('.faq-content button').on('click', function (e) {


        if ($(this).hasClass('collapsed')) {
            $(this).children('img').addClass('active-item');

        } else {
            $(this).children('img').removeClass('active-item');
        }
    });

    wow = new WOW({
        boxClass: "wow", // default
        animateClass: "animated", // default
        offset: 0, // default
        mobile: false, // default
        live: true, // default
    });
    wow.init();

    $(".navbar-toggler").on("click", function (e) {
        if (!$(".navbar-collapse").hasClass("active")) {
            $(".navbar-collapse").addClass("active");
        }
    });

    $(".close-menu").on("click", function (e) {
        console.log("dsds");
        e.preventDefault();
        $(".navbar-collapse").removeClass("active");
    });

    var tamanioWindow = $(window).width();

    var header = document.getElementById("myHeader");
    if (header) {
        window.onscroll = function () {
            myFunction();
        };

        var sticky = header.offsetTop;

        function myFunction() {
            if (window.pageYOffset > sticky) {
                header.classList.add("sticky");
            } else {
                header.classList.remove("sticky");
            }
        }
    }

    //Filter

    $('body .card-header .btn-link').on('click', function (e) {

        console.log($(this).attr('aria-expanded'));

        if ($(' img ', this).hasClass('active')) {
            $(' img ', this).removeClass('active');
        } else {
            $(' img ', this).addClass('active');
        }



    });

    $('.modal-link').on('click', function (e) {
        window.dataLayer.push({
            event: 'confirmación_redención',
            campaign: 'Bogota ola 3',
            brand: $('.modal-name').text()
        });

    });

    $('.redeem-bono').on('click', function () {
        var ele = $(this);
        $('.modal-header').css('background-image', 'url(' + ele.data('banner') + ')');
        $('.modal-logo').attr('src', ele.data('logo'));
        $('.modal-link').attr('href', 'redimir.php?premio=' + ele.data('id'));

    });

    //Download event

    $('.download-award').on('click', function (e) {

        // window.dataLayer.push({
        //     event: 'descarga_bono',
        //     campaign: 'Bogota ola 3',
        //     brand: $('.award.winner.download .name').text()
        // });
    })


    //Premios
    $('.open-modal-confirm').on('click', function (e) {
        e.preventDefault();
        var ele = $(this);
        $('#award-id-hidden').attr('value', ele.data('id'));
        $('.modal-header').css('background-image', 'url(' + ele.data('image') + ')');
        $('.modal-logo').attr('src', ele.data('logo'));
        $('.modal-name').html(ele.data('name'));
        $('.modal-link').attr('href', 'redimir/' + ele.data('id'));
        // window.dataLayer.push({
        //     event: 'intencion_redención',
        //     campaign: 'Bogota ola 3',
        //     brand: ele.data('name'),
        // });

    });

    $('input[name="code-validate"]').on('input', function () {
        if ($(this).attr('type') != 'password') {
            this.value = this.value.replace(/[^0-9,a-z,A-Z,!,#,$,%,&]/g, '');
            this.value = this.value.substr(0, 10);
        }
    });

    $('#form-validate').submit(function () {
        var doc = $('input[name="code-validate"]').val();
        console.log('devdss');
        if (doc.length == 5) {
            $('input[name="code-validate"]').attr('type', 'password');
            $('input[name="code-validate"]').val(CryptoJS.SHA256(doc));
            return true;
        }
    });



});
$(".navbar-nav a.men").click(function (e) {
    e.preventDefault(); //evitar el eventos del enlace normal
    var strAncla = $(this).attr("href"); //id del ancla

    $("body,html")
        .stop(true, true)
        .animate({
            scrollTop: $(strAncla).offset().top,
        },
            1000
        );
});