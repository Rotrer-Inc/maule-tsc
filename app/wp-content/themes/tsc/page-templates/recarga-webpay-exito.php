<?php
/**
 * Template Name: Recarga Webpay Exito
 *
 * Description: Template recarga webpay exito
 */
session_start();
if($_POST){
	$responseExito = saveExito($_POST);
	switch($responseExito->TBK_TIPO_PAGO){
		case 'VN':
			$tipopago = 'Crédito';
			$tipocuota = 'Sin Cuotas';
			$numerocuotas = '00';
		break;
		case 'VC':
			$tipopago = 'Crédito';
			$tipocuota = 'Cuotas normales';
			$numerocuotas = $responseExito->TBK_NUMERO_CUOTAS;
		break;
		case 'SI':
			$tipopago = 'Crédito';
			$tipocuota = 'Sin interés';
			$numerocuotas = $responseExito->TBK_NUMERO_CUOTAS;
		break;
		case 'CI':
			$tipopago = 'Crédito';
			$tipocuota = 'Cuotas Comercio';
			$numerocuotas = 'Numero no definido';
		break;
		case 'VD':
			$tipopago = 'Redcompra';
			$tipocuota = 'Débito';
			$numerocuotas = '00';
		break;	
	}
}else{
	wp_redirect("url");
	exit();
}
?>
<?php get_header(); ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<div class="comprobanteholder">
			<!--- NUEVO -->
			<table width="800" border="0" align="center" cellpadding="10px" cellspacing="0" style="border:1px solid #ccc;" id="comprobante">
			  <tr>
			    <td>
			    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
			          <tr>
			            <td style="padding:10px;" bgcolor="#003C69" colspan="2"><span style="font-family:Calibri, Arial, Helvetica, sans-serif; font-weight:700;color:#fff;">N&uacute;mero de cotizaci&oacute;n: <?php print $responseExito->TBK_ORDEN_COMPRA; ?></span></td>
			          </tr>
			          <tr>
			          	<td width="300px" height="70" valign="middle" style="padding:10px 10px 0;"><span style="font-family:Calibri,Arial, Helvetica, sans-serif;color:#003C69;text-transform:uppercase;font-size:40px;font-weight:bold;">RUTA DEL MAULE</span></td>
			            <td height="70" valign="middle" style="padding:10px 10px 0;"><span style="font-family:Calibri, Arial, Helvetica, sans-serif; font-weight:400; font-size:20px;color:#666;">Comprobante de Recarga de Tarjeta</span></td>
			            
			          </tr>
			          <tr>
			          	<td colspan="2" style="border-bottom:2px solid #003C69;">&nbsp;</td>
			          </tr>
			          <tr>
			          	<td style="padding:30px" colspan="2">
			            	<table style="text-align:left;font-family:Calibri, Arial, Helvetica, sans-serif;color:#666;font-size:14px;border-bottom:1px solid #ccc;border-right:1px solid #ccc;" width="600" border="0" align="center" cellpadding="5" cellspacing="0">
			                  <tr>
			                    <th width="40%" scope="row" style="background:#E5EBF0;border-left:1px solid #ccc;border-top:1px solid #ccc;font-weight:bold;font-size:14px;">Nombre del comercio</th>
			                    <td width="60%" style="border-left:1px solid #ccc;border-top:1px solid #ccc;font-size:12px;">RUTA DEL MAULE</td>
			                  </tr>
			                  <tr>
			                    <th width="40%" scope="row" style="background:#E5EBF0;border-left:1px solid #ccc;border-top:1px solid #ccc;font-weight:bold;font-size:14px;">Url Comercio</th>
			                    <td width="60%" style="border-left:1px solid #ccc;border-top:1px solid #ccc;font-size:12px;"><a href="http://www.rutamaule.cl">http://www.rutamaule.cl</a></td>
			                  </tr>
			                  <tr>
			                    <th width="40%" scope="row" style="background:#E5EBF0;border-left:1px solid #ccc;border-top:1px solid #ccc;font-weight:bold;font-size:14px;">Nombre comprador</th>
			                    <td width="60%" style="border-left:1px solid #ccc;border-top:1px solid #ccc;font-size:12px;"><?php print $responseExito->razonSocialRecep; ?></td>
			                  </tr>
			                  <tr>
			                    <th width="40%" scope="row" style="background:#E5EBF0;border-left:1px solid #ccc;border-top:1px solid #ccc;font-weight:bold;font-size:14px;">4 ultimos digitos de la tarjeta:</th>
			                    <td width="60%" style="border-left:1px solid #ccc;border-top:1px solid #ccc;font-size:12px;"><?php print $responseExito->TBK_FINAL_NUMERO_TARJETA; ?></td>
			                  </tr>
			                  <tr>
			                    <th width="40%" scope="row" style="background:#E5EBF0;border-left:1px solid #ccc;border-top:1px solid #ccc;font-weight:bold;font-size:14px;">Orden de Compra</th>
			                    <td width="60%" style="border-left:1px solid #ccc;border-top:1px solid #ccc;font-size:12px;"><?php print $responseExito->TBK_ORDEN_COMPRA; ?></td>
			                  </tr>
			                  <tr>
			                    <th width="40%" scope="row" style="background:#E5EBF0;border-left:1px solid #ccc;border-top:1px solid #ccc;font-weight:bold;font-size:14px;">Total</th>
			                    <td width="60%" style="border-left:1px solid #ccc;border-top:1px solid #ccc;font-size:12px;"><?php print number_format(substr($responseExito->TBK_MONTO, 0, strlen($responseExito->TBK_MONTO)-2),0,",","."); ?></td>
			                  </tr>
			                  <tr>
			                    <th width="40%" scope="row" style="background:#E5EBF0;border-left:1px solid #ccc;border-top:1px solid #ccc;font-weight:bold;font-size:14px;">Fecha Transaccion</th>
			                    <td width="60%" style="border-left:1px solid #ccc;border-top:1px solid #ccc;font-size:12px;"><?php print $responseExito->fecha_cotizacion; ?></td>
			                  </tr>
			                  <tr>
			                    <th width="40%" scope="row" style="background:#E5EBF0;border-left:1px solid #ccc;border-top:1px solid #ccc;font-weight:bold;font-size:14px;">Codigo Autorizacion</th>
			                    <td width="60%" style="border-left:1px solid #ccc;border-top:1px solid #ccc;font-size:12px;"><?php print $responseExito->TBK_CODIGO_AUTORIZACION; ?></td>
			                  </tr>
			                  <tr>
			                    <th width="40%" scope="row" style="background:#E5EBF0;border-left:1px solid #ccc;border-top:1px solid #ccc;font-weight:bold;font-size:14px;">Tipo Transaccion</th>
			                    <td width="60%" style="border-left:1px solid #ccc;border-top:1px solid #ccc;font-size:12px;">VENTA</td>
			                  </tr>
			                  <tr>
			                    <th width="40%" scope="row" style="background:#E5EBF0;border-left:1px solid #ccc;border-top:1px solid #ccc;font-weight:bold;font-size:14px;">Tipo Pago</th>
			                    <td width="60%" style="border-left:1px solid #ccc;border-top:1px solid #ccc;font-size:12px;"><?php print $tipopago; ?></td>
			                  </tr>
			                  <tr>
			                    <th width="40%" scope="row" style="background:#E5EBF0;border-left:1px solid #ccc;border-top:1px solid #ccc;font-weight:bold;font-size:14px;">Numero de Cuotas</th>
			                    <td width="60%" style="border-left:1px solid #ccc;border-top:1px solid #ccc;font-size:12px;"><?php print $numerocuotas; ?></td>
			                  </tr>
			                  <tr>
			                    <th width="40%" scope="row" style="background:#E5EBF0;border-left:1px solid #ccc;border-top:1px solid #ccc;font-weight:bold;font-size:14px;">Tipo de Cuotas</th>
			                    <td width="60%" style="border-left:1px solid #ccc;border-top:1px solid #ccc;font-size:12px;"><?php print $tipocuota; ?></td>
			                  </tr>
			                  <tr>
				                  <td colspan="2">
					                  <?php print $responseExito->html_tarjetas ?>
				                  </td>
			                  </tr>
			                  <tr>
			                    <td style="padding:10px;text-align:right;border-top:1px solid #ccc;border-left:1px solid #ccc;" colspan="2"><span style="text-align:right;text-transform:uppercase;font-weight:700;font-size:16px;">Total: <?php print number_format(substr($responseExito->TBK_MONTO, 0, strlen($responseExito->TBK_MONTO)-2),0,",","."); ?></span></td>
			                  </tr>
			                  <tr>
				                  <td colspan="2">
					                  <input type="button" value="Imprimir" id="imprimir_webpay">
				                  </td>
			                  </tr>
			                </table>
                            <table>
                            	<tr>
				                  <td colspan="2" style="border:none;text-align:right;font-size:12px;font-weight:bold;"><a href="http://dev.neuralis.cl/rutamaule/tarjeta-prepago/app/wp-content/themes/tsc/politicas-de-devolucion.pdf" target="_blank" style="color:#003C69;">Políticas de Devolución </a></td>
			                  	</tr>
                            </table>
			            </td>
			          </tr>
			        </table>
			    </td>
			  </tr>
			</table>
			<!-- END NUEVO -->
		</div>	
	<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>