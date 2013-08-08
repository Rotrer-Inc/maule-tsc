<?php
/*
 * Funcion procesa solicitud de recarga
 */
function processRecargaMulti( $post ){
	global $wpdb;
	@extract($post);
	$arrError = array();
	//Validar array post
	if(
		isset($multiTotalVaue) && !empty($multiTotalVaue) &&
		isset($multi_tsc) && !empty($multi_tsc) &&
		isset($multi_value) && !empty($multi_value)
	){
		$sumaValores = 0;
		//Validar tipo de dato númerico y montos minimos / maximos
		foreach ( $multi_value as $key => $valueTsc ) {
			if( $valueTsc < 6000 || $valueTsc > 999999 || !is_numeric( $valueTsc ) ){
				$arrError = (Object) array("status" => false, "msg" => "Datos inválidos, vuelva a ingresar montos.(2)");
				return $arrError;
			}
			$sumaValores += $valueTsc;
		}
		//Validar coincidencia de total multiple con suma de recargas
		if( $sumaValores != $multiTotalVaue ){
			$arrError = (Object) array("status" => false, "msg" => "Datos inválidos, vuelva a ingresar montos.(3)");
			return $arrError;
		}
	}  else {
		$arrError = (Object) array("status" => false, "msg" => "Datos inválidos, vuelva a ingresar montos.(1)");
		return $arrError;
	}
	//Validacion ok de montos
	$arrError = (Object) array("status" => true, "msg" => "Montos OK.");
	//Vaciar variable antes de guardar
	unset($_SESSION["recarga"]);
	//Almacenar en session los valores por tarjeta y monto total
	$_SESSION["recarga"]["totalParaRecarga"] = $sumaValores;
	foreach ( $multi_value as $key => $valueTsc ) {
		$_SESSION["recarga"]["tscs"][] = array(
			"tsc" => $multi_tsc[$key],
			"monto" => $valueTsc,
			"saldo" => $multi_tsc_saldo[$key]
		);
	}
	return $arrError;
}
function processRecargaSingle( $post ){
	global $wpdb;
	@extract($post); pr($post);
	$arrError = array();
	//Validar array post
	if(
		isset($single_tsc) && !empty($single_tsc) &&
		isset($single_value) && !empty($single_value)
	){
		//Validar tipo de dato númerico y montos minimos / maximos
		if( $single_value < 6000 || $single_value > 999999 || !is_numeric( $single_value ) ){
			$arrError = (Object) array("status" => false, "msg" => "Datos inválidos, vuelva a ingresar montos.(2)");
			return $arrError;
		}
	}  else {
		$arrError = (Object) array("status" => false, "msg" => "Datos inválidos, vuelva a ingresar montos.(1)");
		return $arrError;
	}
	//Validacion ok de montos
	$arrError = (Object) array("status" => true, "msg" => "Montos OK.");
	//Vaciar variable antes de guardar
	unset($_SESSION["recarga"]);
	//Almacenar en session los valores por tarjeta y monto total
	$_SESSION["recarga"]["totalParaRecarga"] = $single_value;
	$_SESSION["recarga"]["tscs"][] = array(
		"tsc" => $single_tsc,
		"monto" => $single_value,
		"saldo" => $single_tsc_saldo
	);
	return $arrError;
}
?>
