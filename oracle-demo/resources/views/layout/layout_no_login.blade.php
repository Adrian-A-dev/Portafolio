<!DOCTYPE html>
<html>
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
  <link rel="stylesheet" type="text/css" href="<?= asset(ASSETS_VENDORS_ADMIN) ?>/styles/style.css">
  <link rel="stylesheet" type="text/css" href="<?= asset(ASSETS_PLUGINS_ADMIN) ?>/jquery-steps/jquery.steps.css">
</head>

<body class="login-page">
  <div class="login-header box-shadow">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <div class="brand-logo">
        <a href="\">
          <img src="<?= asset(ASSETS_IMG) ?>/logo/logo_turismo.png" alt="Logo <?= NOMBRE_EMPRESA ?>">
        </a>
      </div>
      <div class="login-menu">
        <ul>
          <li><a class="text-white" href="/"><i class="fa fa-reply"></i> Inicio</a></li>
        </ul>
      </div>
    </div>
  </div>


  @yield('contenido')

  <!-- js -->
  <script src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/scripts/core.js"></script>
  <script src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/scripts/script.min.js"></script>
  <script src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/scripts/process.js"></script>
  <script src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/scripts/layout-settings.js"></script>
  <script src="<?= asset(ASSETS_PLUGINS_ADMIN) ?>/jquery-steps/jquery.steps.js"></script>
  <script src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/scripts/steps-setting.js"></script>


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

</body>

</html>