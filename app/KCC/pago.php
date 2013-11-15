<?php 
session_start();
$_SESSION['total'] = $_SESSION['recarga']['totalParaRecarga'];
$_SESSION['ID_SESION'] = $_SESSION['TBK_ID_SESION'];
$_SESSION['ID_USER'] = $_SESSION['mitsc_rut'];

$TBK_MONTO=$_SESSION['total'];
$TBK_MONTO2 = $_SESSION['total'];
$ORDEN_COMPRA = date("Ymdhis");
$TBK_ORDEN_COMPRA = $ORDEN_COMPRA;
#$TBK_ORDEN_COMPRA = $_SESSION['ID_SESION'];
#$TBK_ID_SESION = $_SESSION['ID_SESION'];
$TBK_ID_SESION = date("Ymdhis");
$iduser = $_SESSION['ID_USER'];

/****************** CONFIGURACION *******************/
$TBK_TIPO_TRANSACCION = "TR_NORMAL";

$TBK_URL_EXITO = "http://dev.neuralis.cl/rutamaule/tarjeta-prepago/app/KCC/exito.php";
//$TBK_URL_EXITO = $_SESSION['url_exito'].'?sessionid='.$_SESSION['ID_SESION'];
$TBK_URL_FRACASO = "http://dev.neuralis.cl/rutamaule/tarjeta-prepago/app/KCC/fracaso.php";
//$TBK_URL_FRACASO = $_SESSION['url_fracaso'];

$url_cgi = "http://dev.neuralis.cl/rutamaule/tarjeta-prepago/app/KCC/cgi-bin/tbk_bp_pago.cgi";

//Archivos de datos para uso de pagina de cierre
$myPath	= "/home/neuralis/public_html/dev/rutamaule/tarjeta-prepago/app/KCC/cgi-bin/transaccioneslog/$TBK_ID_SESION.log";

/****************** FIN CONFIGURACION *****************/ 
//formato Moneda 
$partesMonto=split(",",$TBK_MONTO); 
$TBK_MONTO=$partesMonto[0]."00";
//Grabado de datos en archivo de transaccion 
$fic = fopen($myPath, "w+"); 
$linea="$TBK_MONTO;$TBK_ORDEN_COMPRA"; 
fwrite ($fic,$linea);
fclose($fic);
//----------------------------- INGRESO TABLA WP_WEBPAY --------------------
#include('../wp-config.php');
#$conexion=mysql_connect("localhost",DB_USER,DB_PASSWORD);
#mysql_select_db(DB_NAME);
#if (!$conexion){
//echo 'error al conectar';
#die;
#}
#mysql_query ("SET NAMES 'utf8'");
#mysql_query("INSERT INTO wp_webpay (TBK_ORDEN_COMPRA, TBK_MONTO, TBK_TIPO_TRANSACCION,TBK_RESPUESTA,TBK_ID_SESION,TBK_CODIGO_AUTORIZACION,TBK_FINAL_NUMERO_TARJETA,TBK_FECHA_EXPIRACION,TBK_FECHA_CONTABLE,TBK_FECHA_TRANSACCION,TBK_HORA_TRANSACCION,TBK_ID_TRANSACCION,TBK_TIPO_PAGO,TBK_NUMERO_CUOTAS,TBK_MAC,TBK_MONTO_CUOTA,TBK_TASA_INTERES_MAX,fecha_pago,total,user_id,errorTrx,estado,fecha_cotizacion) VALUES ('$TBK_ORDEN_COMPRA', '$TBK_MONTO2', '$TBK_TIPO_TRANSACCION','','$TBK_ID_SESION','','','','','','','','','','','','','','$TBK_MONTO2','$iduser','','','')");
?>
<html>
<body onLoad="document.frm.submit();">
<form action="<?php echo $url_cgi;?>" name="frm" method="post">
<input type="hidden" name="TBK_TIPO_TRANSACCION" value="<?php echo $TBK_TIPO_TRANSACCION;?>"/>
<input type="hidden" name="TBK_MONTO" value="<?php echo $TBK_MONTO;?>"/>
<input type="hidden" name="TBK_ORDEN_COMPRA" value="<?php echo $TBK_ORDEN_COMPRA;?>"/>
<input type="hidden" name="TBK_ID_SESION" value="<?php echo $TBK_ID_SESION;?>"/>
<input type="hidden" name="TBK_URL_EXITO" value="<?php echo $TBK_URL_EXITO;?>"/>
<input type="hidden" name="TBK_URL_FRACASO" value="<?php echo $TBK_URL_FRACASO;?>"/>
<input type="submit" style="display:none;">
</form>
</body>