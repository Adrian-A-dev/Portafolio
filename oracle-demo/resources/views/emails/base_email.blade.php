<!doctype html>
<html lang="es">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->


	<title> <?= NOMBRE_APP ?> </title>
	<style>
		body {
			background-color: #f4f4f4;
			max-width: 1000px;
			margin: 0 auto;
			font-family: arial, sans-serif;
			padding: 5px;
		}

		.img-fluid {
			max-width: 240px;
		}

		.container {
			max-width: 1000px;
			width: 100%;
			margin: -50px auto;
			background-color: #f4f4f4;
		}

		a,
		p {
			color: #000;
		}

		.text-center {
			text-align: center;
		}

		.text-left {
			text-align: left;
			padding: 15px;
		}

		.logo {
			background: #000;
			padding: 20px;

		}

		.logo a img {
			width: 250px;
		}

		.jumbotron {
			background-color: #f4f4f4;
			color: #000;

		}

		strong {
			color: #fff;
		}

		.alert {
			padding: 10px;
			margin-top: -15px;
		}

		.alert-primary {
			text-align: center;
			color: #fff;
			background-color: #000;
			border-color: #b8daff;


		}

		.card {
			width: 60%;
			font-size: 17px;
			font-weight: 400;
			color: #000;
			padding: 20px;
			margin: 10px auto;
			background-color: #ffffff;
			border-radius: 30px;
			border: 3px solid rgba(0, 0, 0, .125);
		}

		.card-body li {
			list-style: none;

		}

		.btn-primary {
			display: inline-block;
			background: #000;
			color: #fff;
			padding: 15px 15px;
			border-radius: 4px;
			margin: 20px;
			text-decoration: none;
		}

		.btn-primary:hover {
			background: #93906a;
		}

		.footer {
			background: #48494a;
			text-align: center;
			color: #ddd;
			padding: 10px;
			font-size: 14px;
		}

		.footer span {
			text-decoration: underline;

		}

		@media only screen and (max-width: 767.99px) {

			h2 {
				font-size: 15px;
			}

			h3 {
				font-size: 15px;
			}

			.card {
				width: 70%;
				font-size: 13px;
				padding: 0;

			}

			.card-body {
				padding: 20px;
			}

			.card-body li {
				list-style: none;
				margin-left: 10px;

			}
		}

		@media only screen and (max-width: 480.99px) {
			.logo a img {
				width: 180px;
			}

			h2 {
				font-size: 14px;
			}

			h3 {
				font-size: 14px;
			}

			.card {
				width: 90%;
				font-size: 13px;
			}

			.card-body li {
				list-style: none;
				margin-left: 0px;
			}
		}

		@media only screen and (max-width: 350.99px) {
			.logo a img {
				width: 150px;
				margin-left: -10px;
			}

			.img-fluid {
				width: 130px;
			}

			.card-body li {
				list-style: none;
				margin-left: -30px;
			}
		}

		@media only screen and (max-width: 250.99px) {
			.logo a img {
				width: 150px;
				margin-left: -10px;
			}

			.img-fluid {
				width: 100px;
			}

			.card-body li {
				list-style: none;
				margin-left: -30px;
			}
		}
	</style>
</head>

<body>
	<div class="container">
		@yield('contenido')

		<div class="footer">
			Mensaje Generado Automáticamente. <strong>Por Favor No Responda este Mensaje</strong>
		</div>
	</div>
</body>

</html>