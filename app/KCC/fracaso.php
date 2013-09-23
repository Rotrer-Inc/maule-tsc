<?PHP
session_start();
/****************** CONFIGURAR AQUI *******************/ 
$PATHSUBMIT	= "http://dev.neuralis.cl/sportika/"; 

/****************** FIN CONFIGURACION *****************/ 
$TBK_ID_SESION	= $_POST["TBK_ID_SESION"]; 
$TBK_ORDEN_COMPRA	= $_POST["TBK_ORDEN_COMPRA"];
$TBK_URL_FRACASO = $_SESSION["url_fracaso"]."?oc=".$TBK_ORDEN_COMPRA;
$idsession = $_SESSION['ID_SESION'];
//----------------------------- UPDATE TABLA WP_WEBPAY RECHAZADO--------------------
#include('../wp-config.php');
#global $wpdb;

#$estado = $wpdb->get_var( $wpdb->prepare("SELECT estado FROM wp_webpay WHERE TBK_ORDEN_COMPRA = '".$TBK_ORDEN_COMPRA."'"));
#$result = mysql_query("SELECT estado FROM wp_webpay WHERE TBK_ORDEN_COMPRA = '".$TBK_ORDEN_COMPRA."'") or die(mysql_error());
#	while($row = mysql_fetch_array($result)){
#		$estado = $row['estado'];
#}

if($estado != "APROBADO"){		
	#$upd = mysql_query("UPDATE wp_webpay SET estado = 'RECHAZADO', fecha_cotizacion = NOW() WHERE TBK_ORDEN_COMPRA = '".$TBK_ORDEN_COMPRA."'");
}

#header("Location: ".$TBK_URL_FRACASO."");
?> 