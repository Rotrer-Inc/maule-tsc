<?php
require( '../wp-load.php' );
global $wpdb;

//rescate de datos de POST. 
$TBK_RESPUESTA=$_POST["TBK_RESPUESTA"]; 
$TBK_ORDEN_COMPRA=$_POST["TBK_ORDEN_COMPRA"]; 
$TBK_MONTO=$_POST["TBK_MONTO"]; 
$TBK_ID_SESION=$_POST["TBK_ID_SESION"];

/****************** CONFIGURAR AQUI *******************/ 
$myPath	= KKC_ROOT.DS."cgi-bin".DS."transaccioneslog".DS."$TBK_ID_SESION.log"; 

//GENERA ARCHIVO PARA MAC 
$filename_txt	= KKC_ROOT.DS."cgi-bin".DS."validacionmac".DS."MAC01Normal$TBK_ID_SESION.txt"; 

// Ruta Checkmac 
$cmdline	= KKC_ROOT.DS."cgi-bin".DS."tbk_check_mac.cgi $filename_txt"; 

/****************** FIN CONFIGURACION *****************/


//lectura archivo que guardo pago.php 
if ($fic = fopen($myPath, "r")){
	$linea=fgets($fic); fclose($fic);
}

$detalle=explode(";", $linea);
if (count($detalle)>=1){
	$monto=$detalle[0]; 
	$ordenCompra=$detalle[1];
}

//guarda los datos del post uno a uno en archivo para la ejecución del MAC 
$fp=fopen($filename_txt,"wt"); 
while(list($key, $val)=each($_POST)){
	fwrite($fp, "$key=$val&");
}
fclose($fp);

#################################################################
#REVISA APROBACIONDE TRANSACCION DE WEBPAY SI $TBK_RESPUESTA =$0#
#################################################################
if($TBK_RESPUESTA == 0){
	$acepta = "ACEPTADO";
}else{
	#$acepta = "RECHAZADO";
	$error='No aceptado por Webpay, fecha de Error:'.date("d-M-Y / H:i:s");
	$wpdb->update( 
		$wpdb->prefix.'app_compra_online',
		array( 
			'errorTrx' => $error
		), 
		array( 'TBK_ORDEN_COMPRA' => $ordenCompra ), 
		array( 
			'%s'
		), 
		array( '%s' ) 
	);
	echo "ACEPTADO";
	exit();
}

######################################
#VALIDACION DE MAC(FIRMA DIGITAL)    #
######################################
if ($acepta=="ACEPTADO") {
	exec ($cmdline, $result, $retint); 
	if ($result[0] == "CORRECTO"){ 
		$acepta = "ACEPTADO";
	}else{
		$error='Check MAC adress, no coincide con el servidor, fecha de Error: '.date("d-M-Y / H:i:s");
		$wpdb->update( 
			$wpdb->prefix.'app_compra_online',
			array( 
				'errorTrx' => $error
			), 
			array( 'TBK_ORDEN_COMPRA' => $ordenCompra ), 
			array( 
				'%s'
			), 
			array( '%s' ) 
		);
		echo "RECHAZADO";
		exit();
	}
}


###############
#REVISA MONTOS#
###############
if ($acepta=="ACEPTADO") {
	$querystr = "SELECT total FROM ".$wpdb->prefix."app_compra_online WHERE TBK_ORDEN_COMPRA = '$ordenCompra'";
	$result = $wpdb->get_row( $querystr ); 
	
	$total = $result->total.'00';
	$acepta = ($total!=$TBK_MONTO) ? "RECHAZADO" : "ACEPTADO";
	if ($acepta=="RECHAZADO") {
		$error='Monto cancelado por Webpay ('.$TBK_MONTO.') con respecto a orden de compra Nuemero: '.$TBK_ORDEN_COMPRA .'('.$total.'), fecha de Error:'.date("d-M-Y / H:i:s");
		$wpdb->update( 
			$wpdb->prefix.'app_compra_online',
			array( 
				'errorTrx' => $error
			), 
			array( 'TBK_ORDEN_COMPRA' => $ordenCompra ), 
			array( 
				'%s'
			), 
			array( '%s' ) 
		);
		echo "RECHAZADO";
		exit();
	}
}


#########################
#VERIFICA SI ESTA PAGADO#
#########################
if ($acepta=="ACEPTADO"){
	$querystr = "SELECT COUNT(*) AS total FROM ".$wpdb->prefix."app_compra_online WHERE TBK_ORDEN_COMPRA = '$ordenCompra' AND TBK_RESPUESTA ='0' AND estado='APROBADO'";
	$result = $wpdb->get_row( $querystr );
	$oc = $result->total;
	
	$acepta = ($oc > 0) ? "RECHAZADO" : "ACEPTADO";
	 if ($acepta=="RECHAZADO") {
			$error='Numero de orden de compra '.$TBK_ORDEN_COMPRA.' ya pagado, fecha de Error:'.date("d-M-Y / H:i:s");
			$wpdb->update( 
				$wpdb->prefix.'app_compra_online',
				array( 
					'errorTrx' => $error
				), 
				array( 'TBK_ORDEN_COMPRA' => $ordenCompra ), 
				array( 
					'%s'
				), 
				array( '%s' ) 
			);
			echo "RECHAZADO";
			exit();
	 }
}


if ($acepta=="ACEPTADO"){
	echo "ACEPTADO";
}else{
	$error = 'Fallo valdiacion final de montos, orden compra, respuesta transbank, check mac, fecha de Error:'.date("d-M-Y / H:i:s");
	$wpdb->update( 
		$wpdb->prefix.'app_compra_online',
		array( 
			'errorTrx' => $error
		), 
		array( 'TBK_ORDEN_COMPRA' => $ordenCompra ), 
		array( 
			'%s'
		), 
		array( '%s' ) 
	);
	echo "RECHAZADO";
}
?>