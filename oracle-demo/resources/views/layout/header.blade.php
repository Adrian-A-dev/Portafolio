<!doctype html>
<html class="no-js" lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title> <?= isset($title) ? $title . ' | ' : '' ?> <?= NOMBRE_APP ?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv='cache-control' content='no-cache'>
  <meta http-equiv='expires' content='0'>
  <meta http-equiv='pragma' content='no-cache'>
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <!-- Place favicon.ico in the root directory -->
  <link href="<?= asset(ASSETS_IMG) ?>/logo/favicon_turismo.png" type="images/x-icon" rel="shortcut icon">
  <!-- All css files are included here. -->
  <!-- Bootstrap fremwork main css -->
  <link rel="stylesheet" href="<?= asset(ASSETS_CSS) ?>/bootstrap.min.css">
  <!-- This core.css file contents all plugings css file. -->
  <link rel="stylesheet" href="<?= asset(ASSETS_CSS) ?>/core.css">
  <!-- Theme shortcodes/elements style -->
  <link rel="stylesheet" href="<?= asset(ASSETS_CSS) ?>/shortcode/shortcodes.css">
  <!-- Theme main style -->
  <link rel="stylesheet" href="<?= asset(ASSETS_CSS) ?>/style.css">
  <link rel="stylesheet" href="<?= asset(ASSETS_CSS) ?>/style-customizer.css">
  <!-- Responsive css -->
  <link rel="stylesheet" href="<?= asset(ASSETS_CSS) ?>/responsive.css">
  <!-- Modernizr JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="<?= asset(ASSETS_JS) ?>/vendor/modernizr-2.8.3.min.js"></script>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <style>
    #toast-container>div {
      opacity: 1 !important;
    }
  </style>
  @yield('css_contenido')
</head>

<body>
  <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

  <!-- Pre Loader
	============================================ -->
  <div class="preloader">
    <div class="loading-center">
      <div class="loading-center-absolute">
        <div class="object object_one"></div>
        <div class="object object_two"></div>
        <div class="object object_three"></div>
      </div>
    </div>
  </div>

  <div class="wrapper">

    @include('layout.main')
    @yield('contenido')
    @include('layout.footer')