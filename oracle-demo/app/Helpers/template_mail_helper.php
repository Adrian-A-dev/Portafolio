<?php





function confirmarCuenta($nombre, $token, $passwordDefault = '')
{
	if (!empty($passwordDefault)) {
		$parrafoPassword = '<p>
		Tu clave de Acceso es: <strong>"12345"</strong>. <br>Se le solicitará Cambio de contraseña al iniciar Sesion. 
		</p>';
	} else {
		$parrafoPassword = '';
	}


	$contenido = '<div class="jumbotron">
					<br>
					<br>
					<br>
					<h2 class="alert alert-primary text-center"><strong>Confirmación de Cuenta ' . NOMBRE_APP . '</strong></h2>
					<br>
					<br>
					<div class="card">
						<div class="card-body">
							<div class="text-center" style="background: #000; padding: 10px; border-radius: 20px 20px 0px 0px;">
							<a href="' . URL_EMPRESA . '"><img class="img-fluid" src="' . URL_LOGO . '" alt="Logo ' . NOMBRE_EMPRESA . '" ></a>
							</div>
							<hr>
							<p class="text-left"> ¡Hola ' . $nombre . '!</p>
							<p class="text-left"> Gracias por Crear tu cuenta en ' . NOMBRE_APP . '. <br><br>Para poder usar su cuenta, primero deberá confirmar la dirección de Correo electrónico haciendo Click en el Siguiente botón</p>
							<p class="text-center" style="color:#fff;">
								<a class="btn btn-primary" href="' . base_url('confirmar-cuenta/' . $token) . '" role="button" style="color:#fff;">Confirmar Correo electrónico</a>
							</p>                      
							' . $parrafoPassword . '
							<p class="text-left">¡Saludos Cordiales!<br><br>Equipo ' . NOMBRE_APP . '</p>
							<hr>
						</div>
					</div>
					<br>
					<hr>
					<p  class="text-center">Si Tiene dudas contáctese con nosotros <a href="mailto:' . MAIL_BASE . '" style="color:#000;"><b>' . MAIL_BASE . '</b></a></p>
				</div>';
	$html = baseMail('Confirmación de Cuenta', $contenido);
	return $html;
}
function BienvenidaCuenta($nombre)
{

	$contenido = '<div class="jumbotron">
					<br>
					<br>
					<br>
					<h2 class="alert alert-primary text-center"><strong>Bienvenidos a ' . NOMBRE_APP . '</strong></h2>
					<br>
					<br>
					<div class="card">
						<div class="card-body">
							<div class="text-center" style="background: #000; padding: 10px; border-radius: 20px 20px 0px 0px;">
							<a href="' . URL_EMPRESA . '"><img class="img-fluid" src="' . URL_LOGO . '" alt="Logo ' . NOMBRE_EMPRESA . '" ></a>
							</div>
							<hr>
							<p class="text-left"> ¡Hola ' . $nombre . '!</p>
							<p class="text-left"> Es un agrado darte la Bienvenida a ' . NOMBRE_APP . '.  <br><br> Ya puedes disfrutar de tu cuenta. </p>
							<p class="text-left"> ¿Qué esperas? Ingresa a tu cuenta ahora </p>
							<p class="text-center" style="color:#fff;">
								<a class="btn btn-primary" href="' . base_url('login') . '" role="button" style="color:#fff;">Ir a ' . NOMBRE_APP . '</a>
							</p>                      
							<p class="text-left">¡Saludos Cordiales!<br><br>Equipo ' . NOMBRE_APP . '</p>
							<hr>
						</div>
					</div>
					<br>
					<hr>
					<p  class="text-center">Si Tiene dudas contáctese con nosotros <a href="mailto:' . MAIL_BASE . '" style="color:#258df6;">' . MAIL_BASE . '</a></p>
				</div>';
	$html = baseMail('Confirmación de Cuenta', $contenido);
	return $html;
}

function notificaCreacion($nombre_empresa, $correo_empresa, $nombre_para = MAIL_BASE_NOMBRE)
{
	$contenido = '<div class="jumbotron">
				<br>
				<br>
				<br>
				<h2 class="alert alert-primary text-center"><strong>Nuevo Registro ' . NOMBRE_APP . '</strong></h2>
				<br>
				<br>
				<div class="card">
					<div class="card-body">
						<div class="text-center" style="background: #fff; padding: 10px; border-radius: 20px 20px 0px 0px;">
						<a href="' . URL_EMPRESA . '"><img class="img-fluid" src="' . URL_LOGO . '" alt="Logo ' . NOMBRE_EMPRESA . '" ></a>
						</div>
						<hr>
						<p class="text-left"> ¡Hola ' . $nombre_para . '!</p>
						<p class="text-left"> Se acaba de registrar una nueva empresa dentro del Sistema. <br><br>
							Nombre:  <strong>' . $nombre_empresa . '</strong> <br>
							Correo:  <strong>' . $correo_empresa . '</strong> </p>
						<p class="text-left">¡Saludos Cordiales!<br><br>Equipo ' . NOMBRE_APP . '</p>
						<hr>
					</div>
				</div>
				<br>
				<hr>
			</div>';
	$html = baseMail('Nuevo Registro', $contenido);
	return 	$html;
}


function cambiarPassword($nombre, $token)
{
	$contenido = '<div class="jumbotron">
					<br>
					<br>
					<br>
					<h2 class="alert alert-primary text-center"><strong>Restablecer Contraseña ' . NOMBRE_APP . '</strong></h2>
					<br>
					<br>
					<div class="card">
						<div class="card-body">
							<div class="text-center" style="background: #000; padding: 10px; border-radius: 20px 20px 0px 0px;">
							<a href="' . URL_EMPRESA . '"><img class="img-fluid" src="' . URL_LOGO . '" alt="Logo ' . NOMBRE_EMPRESA . '" ></a>
							</div>
							<hr>
							<p class="text-left"> ¡Hola ' . $nombre . '!</p>
							<p class="text-left"> Hemos recibido una solicitud para restablecer tu contraseña de acceso a ' . NOMBRE_APP . '. <br><br>Para poder Restablecer Contraseña haz Click en el Siguiente botón</p>
							<p class="text-center" style="color:#fff;">
								<a class="btn btn-primary" href="' . base_url('restablecimiento-contrasena/' . $token) . '" role="button" style="color:#fff;">Restablecer Contraseña</a>
							</p>                      
							<p class="text-left">Recuerda que tienes 15 minutos para cambiar tu contraseña. Pasado ese tiempo, tendrás que hacer una nueva solicitud.
							<br><br>Si no has solicitado el cambio de tu contraseña, no te preocupes, solo ignora este mensaje.</p>
							<br>
							<p class="text-left">¡Saludos Cordiales!<br><br>Equipo ' . NOMBRE_APP . '</p>
							<hr>
						</div>
					</div>
					<br>
					<hr>
					<p  class="text-center">Si Tiene dudas contáctese con nosotros <a href="mailto:' . MAIL_BASE . '" style="color:#258df6;">' . MAIL_BASE . '</a></p>
				</div>';
	$html = baseMail('Cambio de Contraseña', $contenido);
	return $html;
}

function comprobantePago($nombre_correo, $empresa, $plan, $valor_plan)
{
	$contenido = '<div class="jumbotron">
					<br>
					<br>
					<br>
					<h2 class="alert alert-primary text-center"><strong>Pago de Plan ' . NOMBRE_APP . '</strong></h2>
					<br>
					<br>
					<div class="card">
						<div class="card-body">
							<div class="text-center" style="background: #fff; padding: 10px; border-radius: 20px 20px 0px 0px;">
							<a href="' . URL_EMPRESA . '"><img class="img-fluid" src="' . URL_LOGO . '" alt="Logo ' . NOMBRE_EMPRESA . '" ></a>
							</div>
							<hr>
							<p class="text-left"> ¡Hola ' . $nombre_correo . '!</p>
							<p class="text-left"> La empresa ' . $empresa . ' a enviado un comprobante de pago por la contratación de un plan en ' . NOMBRE_APP . '. <br><br></p>
							<p class="text-left">Plan Contratado: <strong>' . $plan . '</strong>
							<br>Valor de Plan: <strong>$' . formatear_miles($valor_plan) . '</strong></p>
							<br>
							<p class="text-left">¡Saludos Cordiales!<br><br>Equipo ' . NOMBRE_APP . '</p>
							<hr>
						</div>
					</div>
					<br>
					<hr>
					<p  class="text-center">Si Tiene dudas contáctese con nosotros <a href="mailto:' . MAIL_BASE . '" style="color:#258df6;">' . MAIL_BASE . '</a></p>
				</div>';
	$html = baseMail('Nuevo Comprobante de Pago', $contenido);
	return $html;
}
function baseMail($titulo, $contenido)
{
	$html = '<!doctype html>
	<html lang="es">
		<head>
			<!-- Required meta tags -->
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
			<!-- Bootstrap CSS -->
	
	
			<title> ' . $titulo . ' | ' . NOMBRE_APP . '</title>
			<style>
				body{
					background-color: #93906a;
					max-width: 1000px;
					margin: 0 auto;
					font-family: arial, sans-serif;
					padding: 5px;
				}
				.img-fluid{
					max-width: 240px;
				}
				.container {
					max-width: 1000px;
					width: 100%;
					margin: -50px auto;
					background-color: #93906a;
				}
				a,p{
					color:#000;
				}
				.text-center{
					text-align:center;
				}
				.text-left{
					text-align:left;
					padding:15px;
				}            
				.logo{
					background: #000;
					padding: 20px;
	
				}
				.logo a img{
					width: 250px;
				}
				.jumbotron{
					background-color: #93906a;
					color:#000;
	
				}
				strong{
					color:#fff;
				}
				.alert{
					padding:10px;
					margin-top: -15px;
				}
				.alert-primary {
					text-align: center;
					color: #fff;
					background-color: #000;
					border-color: #b8daff;  
	
	
				}
				.card{
					width: 60%;
					font-size: 17px;
					font-weight: 400;
					color:#000;
					padding: 20px;
					margin: 10px auto ;
					background-color:#ffffff; 
					border-radius: 30px;
					border: 3px solid rgba(0,0,0,.125);
				}
				.card-body li{
					list-style: none;
	
				}
				.btn-primary{
					display: inline-block;
					background: #000;
					color: #fff;
					padding:15px 15px;
					border-radius: 4px;
					margin: 20px;
					text-decoration: none;
				}
				.btn-primary:hover{
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
	
					h2{
						font-size: 15px;
					}
					h3{
						font-size:15px;
					}
					.card{
						width: 70%;
						font-size: 13px;
						padding: 0;
	
					} 
					.card-body{
						padding: 20px;
					}
					.card-body li{
						list-style: none;
						margin-left:10px;
	
					}  
				}
				@media only screen and (max-width: 480.99px) {
					.logo a img{
						width: 180px;           
					}
					h2{
						font-size: 14px;
					}
					h3{
						font-size:14px;
					}
					.card{
						width: 90%;
						font-size: 13px;   
					} 
					.card-body li{
						list-style: none;
						margin-left:0px;
					}
				}
				@media only screen and (max-width: 350.99px) {
					.logo a img{
						width: 150px;
						margin-left:-10px;
					}
					.img-fluid{
						width: 130px;
					}
					.card-body li{
						list-style: none;
						margin-left:-30px;
					}
				}
				@media only screen and (max-width: 250.99px) {
					.logo a img{
						width: 150px;
						margin-left:-10px;
					}
					.img-fluid{
						width: 100px;
					}
					.card-body li{
						list-style: none;
						margin-left:-30px;
					}
				}             
			</style>
		</head>
		<body>
			<div class="container">
				' . $contenido . '
	
				<div class="footer">
					Mensaje Generado Automáticamente. <strong>Por Favor No Responda este Mensaje</strong> 
				</div>
			</div>
		</body>
	</html>';
	return $html;
}

function ConfirmacionPassword($nombre)
{
	$contenido = '<div class="jumbotron">
					<br>
					<br>
					<br>
					<h2 class="alert alert-primary text-center"><strong>Cambio de Contraseña ' . NOMBRE_APP . '</strong></h2>
					<br>
					<br>
					<div class="card">
						<div class="card-body">
							<div class="text-center" style="background: #000; padding: 10px; border-radius: 20px 20px 0px 0px;">
							<a href="' . URL_EMPRESA . '"><img class="img-fluid" src="' . URL_LOGO . '" alt="Logo ' . NOMBRE_EMPRESA . '" ></a>
							</div>
							<hr>
							<p class="text-left"> ¡Hola ' . $nombre . '!</p>
							<p class="text-left">  Se ha Realizado el cambio de contraseña en tu cuenta de ' . NOMBRE_APP . ' de manera Correcta<br></p>
							<p class="text-left"> Si no has solicitado el cambio de tu contraseña, realiza un restablecimiento de esta <a href="' . base_url('restablecer-contrasena') . '" class="aqui">Aquí</a>.</p>                    
							<br>
							<p class="text-left">¡Saludos Cordiales!<br><br>Equipo ' . NOMBRE_APP . '</p>
							<hr>
						</div>
					</div>
					<br>
					<hr>
					<p  class="text-center">Si Tiene dudas contáctese con nosotros <a href="mailto:' . MAIL_BASE . '" style="color:#258df6;">' . MAIL_BASE . '</a></p>
				</div>';
	$html = baseMail('Cambio de Contraseña', $contenido);
	return $html;
}


function base_nueva()
{
	$contenido = '';
	$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="format-detection" content="telephone=no" />
		<title>Mailing Hitch</title>
		<style type="text/css">
			/* RESET STYLES */
			html { background-color:#E1E1E1; margin:0; padding:0; }
			body, #bodyTable, #bodyCell, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;font-family:Helvetica, Arial, "Lucida Grande", sans-serif;}
			table{border-collapse:collapse;}
			table[id=bodyTable] {width:100%!important;margin:auto;max-width:500px!important;color:#7A7A7A;font-weight:normal;}
			img, a img{border:0; outline:none; text-decoration:none;height:auto; line-height:100%;}
			a {text-decoration:none !important;border-bottom: 1px solid;}
			h1, h2, h3, h4, h5, h6{color:#5F5F5F; font-weight:normal; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left; letter-spacing:normal;margin-top:0;margin-right:0;margin-bottom:10px;margin-left:0;padding-top:0;padding-bottom:0;padding-left:0;padding-right:0;}

			/* MAIL STYLES */
			.ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
			.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;}
			table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
			#outlook a{padding:0;}
			body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; font-weight:normal!important;}
			h1{display:block;font-size:26px;font-style:normal;font-weight:normal;line-height:100%;}
			h2{display:block;font-size:20px;font-style:normal;font-weight:normal;line-height:120%;}
			h3{display:block;font-size:17px;font-style:normal;font-weight:normal;line-height:110%;}
			h4{display:block;font-size:18px;font-style:italic;font-weight:normal;line-height:100%;}
			.flexibleImage{height:auto;}
			.linkRemoveBorder{border-bottom:0 !important;}
			table[class=flexibleContainerCellDivider] {padding-bottom:0 !important;padding-top:0 !important;}
			body, #bodyTable{background-color:#E1E1E1;}
			#emailHeader{background-color:#E1E1E1;}
			#emailBody{background-color:#FFFFFF;}
			#emailFooter{background-color:#E1E1E1;}
			.nestedContainer{background-color:#F8F8F8; border:1px solid #CCCCCC;}
			.emailButton{background-color:#205478; border-collapse:separate;}
			.buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:18px; font-weight:bold; line-height:100%; padding:15px; text-align:center;}
			.buttonContent a{color:#FFFFFF; display:block; text-decoration:none!important; border:0!important;}
			.emailCalendar{background-color:#FFFFFF; border:1px solid #CCCCCC;}
			.emailCalendarMonth{background-color:#205478; color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; padding-top:10px; padding-bottom:10px; text-align:center;}
			.emailCalendarDay{color:#205478; font-family:Helvetica, Arial, sans-serif; font-size:60px; font-weight:bold; line-height:100%; padding-top:20px; padding-bottom:20px; text-align:center;}
			.imageContentText {margin-top: 10px;line-height:0;}
			.imageContentText a {line-height:0;}
			#invisibleIntroduction {display:none !important;}
			span[class=ios-color-hack] a {color:#275100!important;text-decoration:none!important;}
			span[class=ios-color-hack2] a {color:#205478!important;text-decoration:none!important;}
			span[class=ios-color-hack3] a {color:#8B8B8B!important;text-decoration:none!important;}
			.a[href^="tel"], a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:none!important;cursor:default!important;}
			.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:auto!important;cursor:default!important;}

			/* MOBILE STYLES */
			@media only screen and (max-width: 480px){
				body{width:100% !important; min-width:100% !important;}
				table[id="emailHeader"],
				table[id="emailBody"],
				table[id="emailFooter"],
				table[class="flexibleContainer"],
				td[class="flexibleContainerCell"] {width:100% !important;}
				td[class="flexibleContainerBox"], td[class="flexibleContainerBox"] table {display: block;width: 100%;text-align: left;}
				td[class="imageContent"] img {height:auto !important; width:100% !important; max-width:100% !important; }
				img[class="flexibleImage"]{height:auto !important; width:100% !important;max-width:100% !important;}
				img[class="flexibleImageSmall"]{height:auto !important; width:auto !important;}
				table[class="flexibleContainerBoxNext"]{padding-top: 10px !important;}
				table[class="emailButton"]{width:100% !important;}
				td[class="buttonContent"]{padding:0 !important;}
				td[class="buttonContent"] a{padding:15px !important;}
			}
		</style>
		<!--[if mso 12]>
			<style type="text/css">
				.flexibleContainer{display:block !important; width:100% !important;}
			</style>
		<![endif]-->
		<!--[if mso 14]>
			<style type="text/css">
				.flexibleContainer{display:block !important; width:100% !important;}
			</style>
		<![endif]-->
	</head>
	<body bgcolor="#E1E1E1" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<center style="background-color:#E1E1E1;">
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
				<tr>
					<td align="center" valign="top" id="bodyCell">
						<table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="550" id="emailBody">
							<tr mc:hideable style="background-color:#1472b8">
								<td align="center" valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												<table border="0" cellpadding="0" cellspacing="0" width="550" class="flexibleContainer">
													<tr>
														<td valign="top" width="550" class="flexibleContainerCell">
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							
							<tr>
								<td align="center" valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
										<tr>
											<td align="center" valign="top">
												<table border="0" cellpadding="0" cellspacing="0" width="550" class="flexibleContainer">
													<tr>
														<td align="center" valign="top" width="550" class="flexibleContainerCell">
															<table border="0" cellpadding="48" cellspacing="0" width="100%">
																<tr>
																	<td align="center" valign="top">

																		<table border="0" cellpadding="0" cellspacing="0" width="100%">
																			<tr>
																				<td valign="top" class="textContent">
																					
																					'.$contenido.'
																				</td>
																			</tr>
																		</table>

																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
							<td align="center" valign="top">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td align="center" valign="top">
											<table border="0" cellpadding="0" cellspacing="0" width="100%" class="flexibleContainer" style="background-color:#172e44">
												<tr>
													<td align="center" valign="top" width="550" class="flexibleContainerCell">
														<table border="0" cellpadding="15" cellspacing="0" width="100%">
															<tr>
																<td align="center" valign="top">

																	<table border="0" cellpadding="0" cellspacing="0" width="100%">
																		<tr>
																			<td valign="top" class="textContent">
																				<div style="text-align:center;font-family:Arial,sans-serif;font-style:normal;font-size:18px;margin-bottom:0;margin-top:3px;color:#FFFFFF;line-height:135%;">¿No quieres recibir más nuestros Mensajes?<br><a href="#" style="color: #fff; font-weight: bold;">Desuscribete aquí</a></div>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						</table>
					</td>
				</tr>
			</table>
		</center>
	</body>
</html>';
	return $html;
}
