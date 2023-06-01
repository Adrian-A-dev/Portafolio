<!DOCTYPE html>
<html lang="es">

<head>
  <!-- Basic Page Info -->
  <meta charset="utf-8">
  <title> <?= isset($title) ? $title . ' | ' : '' ?> <?= NOMBRE_APP ?></title>

  <!-- Site favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="<?= asset(ASSETS_IMG) ?>/logo/favicon_turismo.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= asset(ASSETS_IMG) ?>/logo/favicon_turismo.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= asset(ASSETS_IMG) ?>/logo/favicon_turismo.png">

  <!-- Mobile Specific Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="<?= asset(ASSETS_VENDORS_ADMIN) ?>/styles/core.css">
  <link rel="stylesheet" type="text/css" href="<?= asset(ASSETS_VENDORS_ADMIN) ?>/styles/icon-font.min.css">
  <link rel="stylesheet" type="text/css" href="<?= asset(ASSETS_PLUGINS_ADMIN) ?>/datatables/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" type="text/css" href="<?= asset(ASSETS_PLUGINS_ADMIN) ?>/datatables/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" type="text/css" href="<?= asset(ASSETS_VENDORS_ADMIN) ?>/styles/style.css">

</head>

<body style="background: #a0b6ce;">
  <!-- <div class="pre-loader">
    <div class="pre-loader-box">
      <div class="loader-logo"><img src="<?= asset(ASSETS_IMG) ?>/logo/logo_turismo.png" alt="Logo <?= NOMBRE_EMPRESA ?>" style="object-fit: cover"></div>
      <div class='loader-progress' id="progress_div">
        <div class='bar' id='bar1'></div>
      </div>
      <div class='percent' id='percent1'>90%</div>
      <div class="loading-text">
        Cargando...
      </div>
    </div>
  </div> -->

  @include('layout.admin.topnav_admin')
  @if(auth()->user()->id_rol == 3)
  @include('layout.admin.sidenav_cliente')
  @elseif(auth()->user()->id_rol == 4)
  @include('layout.admin.sidenav_anfitrion')
  @else
  @include('layout.admin.sidenav_admin')
  @endif


  <div class="mobile-menu-overlay"></div>

  <div class="main-container">
    <div class="pd-ltr-20">
      @yield('contenido-admin')

      <br>
      <div class="footer-wrap pd-20 mb-20 card-box ">
        <p>Copyright© <strong class="text-primary"><?= NOMBRE_EMPRESA ?></strong> <?= date('Y') ?>. Todos los derechos reservados. <br> </p>
        <small>Panel Administrativo Desarrollado por <a href="#!" class="text-decoration-none" target="_blank"><?= NOMBRE_DESIGN ?></a></small>
      </div>
    </div>
  </div>
  <!-- js -->
  <script src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/scripts/core.js"></script>
  <script src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/scripts/script.min.js"></script>
  <script src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/scripts/process.js"></script>
  <script src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/scripts/layout-settings.js"></script>
  <script src="<?= asset(ASSETS_PLUGINS_ADMIN) ?>/apexcharts/apexcharts.min.js"></script>
  <script src="<?= asset(ASSETS_PLUGINS_ADMIN) ?>/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?= asset(ASSETS_PLUGINS_ADMIN) ?>/datatables/js/dataTables.bootstrap4.min.js"></script>


  @yield('js_content')

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.all.min.js"></script>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script>
    toastr.options = {
      "closeButton": true,
      "progressBar": true,
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
    $(function() {
      $('[data-toggle="tooltip"]').tooltip()
    })
  </script>
</body>

</html>