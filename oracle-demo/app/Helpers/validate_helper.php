<?php


function validateEmail($email)
{
	if ((strlen($email) > 96) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	} else {
		return false;
	}
}
//Validate text/String 
function validateText($text)
{
	if ((strlen($text) < 3) || !is_string($text)) {
		return true;
	} else {
		return false;
	}
}
function validatePassword($text)
{
	if ((strlen($text) < 4)) {
		return true;
	} else {
		return false;
	}
}
//Validate Date
function validateDate($date, $format = 'Y-m-d')
{
	if (validateDateFormat($date, $format)) {
		return false;
	} else {
		return true;
	}
}
//Validate Date format
function validateDateFormat($date, $format = 'Y-m-d')
{
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) == $date;
}

function validateRut($rut)
{
	$r = strtoupper(preg_replace('/[^Kk0-9]/i', '', $rut));
	if ($r == '111111111' || $r == '222222222' || $r == '333333333' || $r == '444444444' || $r == '555555555' || $r == '666666666' || $r == '777777777' || $r == '888888888' || $r == '999999999') {
		return false;
	}
	if (strlen($r) < 7) {
		return false;
	}
	$sub_rut = substr($r, 0, strlen($r) - 1);
	$sub_dv = substr($r, -1);
	$x = 2;
	$s = 0;
	for ($i = strlen($sub_rut) - 1; $i >= 0; $i--) {
		if ($x > 7) {
			$x = 2;
		}
		$s += $sub_rut[$i] * $x;
		$x++;
	}
	$dv = 11 - ($s % 11);
	if ($dv == 10) {
		$dv = 'K';
	}
	if ($dv == 11) {
		$dv = '0';
	}
	if ($dv == $sub_dv) {
		return true;
	} else {
		return false;
	}
}


function str_limit($value, $limit = '', $end = ''){
	if(empty($limit)){
		$limit = 100;
	}
	if (mb_strwidth($value, 'UTF-8') <= $limit) {
			return $value;
	}
	return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')).$end;
}


function formateaRut($rut)
{
	$rutLimpio = str_replace('.', '', $rut);
	$rutLimpio = trim(str_replace('-', '', $rutLimpio));
	$dvRut = substr($rutLimpio, -1);
	$rutLimpio = substr($rutLimpio, 0, -1);
	$rutFormateado = formatear_miles($rutLimpio) . '-' . $dvRut;
	return $rutFormateado;
}

function formatear_numero($numero)
{
	if(!empty($numero))
	{
		$pesos = '$ '.number_format($numero, 0, ',', '.');
	}
	else
	{
		$pesos = "No aplica";
	}
	return $pesos;
}
function formatear_miles($numero)
{
	if(!empty($numero))
	{
		$pesos = ''.number_format($numero, 0, ',', '.');
	}
	else
	{
		$pesos = "No aplica";
	}
	return $pesos;
}
function formatear_porcentaje($numero)
{
	
	if(!empty($numero))
	{
		if(!is_float($numero))
		{
			 
		}
		$porcentaje = str_replace('.', ',', $numero).' %'; 
	}
	else
	{
		$porcentaje = "No aplica";
	}
	return $porcentaje;
}


function getUrl($str){
	$str = strLower(trim($str));
	$str = limpiarStr($str);
	$str = preg_replace('([^A-Za-z0-9])', '-', $str);
	return $str;
}
function limpiarStr($str){
	$str = trim($str);
	$str = str_replace('á', 'a', $str);
	$str = str_replace('é', 'e', $str);
	$str = str_replace('í', 'i', $str);
	$str = str_replace('ó', 'o', $str);
	$str = str_replace('ú', 'u', $str);
	$str = str_replace('ñ', 'n', $str);
	return $str;
}
function limpiaMoneda($str){
	$str = trim($str);
	$str = str_replace('$', '', $str);
	$str = str_replace('.', '', $str);
	$str = preg_replace('([^0-9])', '', $str);
	return $str;
}
// function crear_carpeta($articulo_id, $prefix)
// {
// 	 $ruta_contenido = ROOT_PATH_BASE.'/galeria/'.$prefix.$articulo_id.'/';
// 	 if($ruta_contenido){
// 		return $ruta_contenido;
// 	 }else{
// 		mkdir($ruta_contenido,0755, TRUE);
// 		return $ruta_contenido;
// 	 }
   
	 
// }

function strUpper($str)
{ 
	$str = strtoupper(trim($str));
	$str = str_replace('á', 'Á', $str);
	$str = str_replace('é', 'É', $str);
	$str = str_replace('í', 'Í', $str);
	$str = str_replace('ó', 'Ó', $str);
	$str = str_replace('ú', 'Ú', $str);
	$str = str_replace('ñ', 'Ñ', $str);
	return $str;
}

function strLower($str)
{
	$str = strtolower(trim($str));
	$str = str_replace('Á', 'á', $str);
	$str = str_replace('É', 'é', $str);
	$str = str_replace('Í', 'í', $str);
	$str = str_replace('Ó', 'ó', $str);
	$str = str_replace('Ú', 'ú', $str);
	$str = str_replace('Ñ', 'ñ', $str);
	return $str;
}

