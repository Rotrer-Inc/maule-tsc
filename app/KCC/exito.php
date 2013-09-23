<?php
session_start();
$TBK_ID_SESION	= $_POST["TBK_ID_SESION"];
$TBK_ORDEN_COMPRA	= $_POST["TBK_ORDEN_COMPRA"]; 

/****************** CONFIGURAR AQUI *******************/ 

$myPath	="/home/neuralis/public_html/dev/rutamaule/tarjeta-prepago/app/KCC/cgi-bin/validacionmac/MAC01Normal$TBK_ID_SESION.txt"; 
$pathSubmit	= "http://dev.neuralis.cl/sportika/";


/****************** FIN CONFIGURACION *****************/

//Rescate de los valores informados por transbank 
$fic = fopen($myPath, "r"); 
$linea=fgets($fic); 
fclose($fic);
$detalle=explode("&", $linea);
$TBK_ORDEN_COMPRA=explode("=",$detalle[0]);
$TBK_TIPO_TRANSACCION=explode("=",$detalle[1]);
$TBK_RESPUESTA=explode("=",$detalle[2]);
$TBK_MONTO=explode("=",$detalle[3]);
$TBK_CODIGO_AUTORIZACION=explode("=",$detalle[4]);
$TBK_FINAL_NUMERO_TARJETA=explode("=",$detalle[5]);
$TBK_FECHA_CONTABLE=explode("=",$detalle[6]);
$TBK_FECHA_TRANSACCION=explode("=",$detalle[7]);
$TBK_HORA_TRANSACCION=explode("=",$detalle[8]);
$TBK_ID_TRANSACCION=explode("=",$detalle[10]);
$TBK_TIPO_PAGO=explode("=",$detalle[11]);
$TBK_NUMERO_CUOTAS=explode("=",$detalle[12]);
$TBK_MAC=explode("=",$detalle[14]);
$TBK_FECHA_CONTABLE[1]=substr($TBK_FECHA_CONTABLE[1],2,2)."-".substr($TBK_FECHA_CONTABLE[1],0,2);
$TBK_FECHA_TRANSACCION[1]=substr($TBK_FECHA_TRANSACCION[1],2,2)."-".substr($TBK_FECHA_TRANSACCION[1],0,2);
$TBK_HORA_TRANSACCION[1]=substr($TBK_HORA_TRANSACCION[1],0,2).":".substr($TBK_HORA_TRANSACCION[1],2,2).":".substr($TBK_HORA_TRANSACCION[1],4,2);
$idsession = $_SESSION['ID_SESION'];
$TBK_URL_EXITO = $_SESSION['url_exito'].'?webpay&sessionid='.$TBK_ID_SESION;


//----------------------------- UPDATE TABLA WP_WEBPAY APROBADO --------------------
#include('../wp-config.php');

#$conexion=mysql_connect("localhost",DB_USER,DB_PASSWORD);
#mysql_select_db(DB_NAME);
#if (!$conexion){
//echo 'error al conectar';
#die;
#}
#mysql_query ("SET NAMES 'utf8'");
echo '<pre>';
print_r($detalle);
echo '</pre>';

#$upd = mysql_query("UPDATE wp_webpay SET TBK_RESPUESTA = '".$TBK_RESPUESTA[1]."', TBK_CODIGO_AUTORIZACION = '".$TBK_CODIGO_AUTORIZACION[1]."', TBK_FINAL_NUMERO_TARJETA = '".$TBK_FINAL_NUMERO_TARJETA[1]."', TBK_FECHA_CONTABLE = '".$TBK_FECHA_CONTABLE[1]."', TBK_FECHA_TRANSACCION = '".$TBK_FECHA_TRANSACCION[1]."', TBK_HORA_TRANSACCION = '".$TBK_HORA_TRANSACCION[1]."', TBK_ID_TRANSACCION = '".$TBK_ID_TRANSACCION[1]."', TBK_TIPO_PAGO = '".$TBK_TIPO_PAGO[1]."', TBK_NUMERO_CUOTAS = '".$TBK_NUMERO_CUOTAS[1]."', TBK_MAC = '".$TBK_MAC[1]."', fecha_pago = NOW(), estado = 'APROBADO', fecha_cotizacion = NOW() WHERE TBK_ID_SESION = '".$idsession."'");

#header("Location: ".$TBK_URL_EXITO."");
?>