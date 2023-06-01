<?php if (!isset($error404)) : ?>

    <!--hotel team start-->
    <div class="hotel-team pt-100 white_bg ">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="newsletter">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="newsletter-title">
                                    <h2>SUSCRIBETE A NOVEDADES</h2>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="newsletter-form">
                                    <form id="mc-form" class="mc-form">
                                        <input id="mc-email" type="email" autocomplete="off" placeholder="Ingresa correo electrónico..." />
                                        <button id="mc-submit" type="submit">Suscribirse</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--hotel team end-->


    <!--Footer start -->
    <div class="footer ptb-40">
        <div class="footer-bg-opacity"></div>
        <div class="container">
            <div class="row">

                <div class="col-md-4 hidden-sm col-xs-6">

                </div>
                <div class="col-md-4 col-sm-4 col-xs-6">
                    <div class="single-footer mt-0">
                        <div class="logo">
                            <img src="<?= asset(ASSETS_IMG) ?>/logo/logo_turismo.png" alt="logo">
                        </div>
                        <div class="hotel-contact text-center">
                            <p><span>Contacto:</span> <a href="tel:+569123456789">+569 1234 5678</a></p>
                            <p><span>Email: </span><a href="mailto:info@turismoreal.cl">info@turismoreal.cl</a></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php endif; ?>
<div class="copyright ptb-20 white_bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <p>Copyright© <strong class="text-dark"><?= NOMBRE_EMPRESA ?></strong> <?= date('Y') ?>. Todos los derechos reservados. <br> Desarrollado por <b><?= NOMBRE_DESIGN ?></b></p>
            </div>
        </div>
    </div>
</div>
<!-- Footer end -->
</div>





<!-- Placed js at the end of the document so the pages load faster -->

<!-- jquery latest version -->
<script src="<?= asset(ASSETS_JS) ?>/vendor/jquery-1.12.0.min.js"></script>
<!-- Bootstrap framework js -->
<script src="<?= asset(ASSETS_JS) ?>/bootstrap.min.js"></script>
<!--counter up js-->
<script src="<?= asset(ASSETS_JS) ?>/waypoints.min.js"></script>
<script src="<?= asset(ASSETS_JS) ?>/jquery.counterup.min.js"></script>
<!-- headlines js -->
<script src="<?= asset(ASSETS_JS) ?>/animated-headlines.js"></script>
<!-- Ajax mail js -->
<!-- Ajax mail js -->
<script src="<?= asset(ASSETS_JS) ?>/ajax-mail.js"></script>
<!-- parallax js -->
<script src="<?= asset(ASSETS_JS) ?>/parallax.js"></script>
<!-- textilate js -->
<script src="<?= asset(ASSETS_JS) ?>/textilate.js"></script>
<script src="<?= asset(ASSETS_JS) ?>/lettering.js"></script>
<!--isotope js-->
<script src="<?= asset(ASSETS_JS) ?>/isotope.pkgd.min.js"></script>
<script src="<?= asset(ASSETS_JS) ?>/packery-mode.pkgd.min.js"></script>
<!-- Owl carousel Js -->
<script src="<?= asset(ASSETS_JS) ?>/owl.carousel.min.js"></script>
<!--Magnificant js-->
<script src="<?= asset(ASSETS_JS) ?>/jquery.magnific-popup.js"></script>
<!-- All js plugins included in this file. -->
<script src="<?= asset(ASSETS_JS) ?>/plugins.js"></script>
<!-- Main js file that contents all jQuery plugins activation. -->

<script src="<?= asset(ASSETS_JS) ?>/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.all.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "hideDuration": "15000",
        
    }
    <?php if (session()->has('success_message')) : ?>
        toastr["success"](`<?= session()->get('success_message'); ?>`, "<?= session()->get('success_message_title'); ?>")
    <?php endif; ?>
    <?php if (session()->has('warning_message')) : ?>
        toastr["warning"](`<?= session()->get('warning_message'); ?>`, "<?= session()->get('warning_message_title'); ?>")
    <?php endif; ?>

    <?php if (session()->has('danger_message')) : ?>
        toastr["error"](`<?= session()->get('danger_message') ?>`, "<?= session()->get('danger_message_title'); ?>")
    <?php endif; ?>
    <?php if ($errors->any()) : $error = implode(',', $errors->all());
        $error = 'Se encontraron los siguientes errores: ' . $error; ?>
        toastr["error"](`<?= $error; ?>`, "Error de Validación")
    <?php endif; ?>
</script>
<script>
    function cargando(msg = 'Cargando...', tiempo = 10000000000) {
        Swal.fire({
            title: `${msg}`,
            html: `No cierre ni actualice la página`,
            allowOutsideClick: false,
            allowEscapeKey: false,
            timer: tiempo,
            didOpen: () => {
                Swal.showLoading()
            }
        })
    }
</script>

@yield('js_content')

</body>

</html>