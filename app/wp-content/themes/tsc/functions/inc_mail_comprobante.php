<?php
$body = '
<table align="left" style="width:600px; border:1px solid #DDDDDD;margin-top:10px; padding:5px" cellpadding="0" cellspacing="0">
  <tr>
      <td align="right">
		<img alt="Ruta Maule" src="https://www.rutamaule.cl/tsc/app/wp-content/themes/tsc/images/logo.jpg">    
      </td>
  </tr>  <tr>
      <td align="center">        
            <h1>Pago Exitoso Realizado por Webpay</h1>
 </td>
    </tr>
    
    <tr>
		<td align="left" width="100%" >	
			<div style="padding:5px;">
			  <h2>Datos del Comprador: </h2> 
			</div>
        </td>
	</tr>
	<tr>
		<td align="center" width="100%">	
			<table width=95% style="border:1px solid #DDDDDD;">
				<tr valign=top>
				  <td width="110" align=left><p><strong>Raz&oacute;n Social:</strong></p></td>
				  <td width="166"  align=left><p>'.$razonSocialRecep.'</p></td>
				  <td>&nbsp;</td>
				  <td align=left><p><strong>R.U.T:</strong></p></td>
				  <td width="110" align=left><p>'.
				  number_format(substr( $rut,0,-1), 0 ,",", ".") ."-". substr( $rut,-1).'</p></td>
			  </tr>
			</table>
		</td>
	</tr>
    <tr>
      <td align="left" >&nbsp;</td>
    </tr>
    <tr>
		<td align="left" width="100%" >	
			<div style="padding:5px;">
			  <h2>Datos de la Compra: </h2>
			</div>
        </td>
	</tr>
	<tr>
		<td align="center" width="100%">	
			<table width=95% style="border:1px solid #DDDDDD;">
				<tr valign=top>
				  <td width="110"  align=left><p><strong>Nro Orden:</strong></p></td>
				  <td width="166" align=left><p>'. $id.'</p></td>
				  <td >&nbsp;</td>
				  <td  align=left><p><strong>Monto (pesos chilenos):</strong></p></td>
				  <td width="110"  align=left><p>$&nbsp;'. number_format($TBK_MONTO,0,",",".").'</p></td>
			  </tr>
				<tr valign=top>
					<td align=left><p><strong>URL de Comercio</strong></p></td>
				  <td colspan="3" align=left><p class="thumbsup-intro-_ult_notinterior"><a href="http://www.rutamaipo.cl/" target="_blank">http://www.rutamaipo.cl/</a></p></td>
					<td align=left><p>&nbsp;</p></td>
                </tr>
			</table>
		</td>
	</tr>
	<tr>
	  <td align="left">&nbsp;</td>
  </tr>
	<tr>
		<td align="left" width="100%">	
			<div style="padding:5px;">
			  <h2>Datos de la Transacci&oacute;n:</h2>
			</div>
        </td>
	</tr>
	<tr>
		<td align="center">	
			<table width=95% border=0 align="center" style="border:1px solid #DDDDDD;" >
				<tr valign=top >
					<td width="110" align=left><p><strong>Fecha Transacci&oacute;n:</strong></p></td>
					<td width="166" align=left><p>'.$TBK_FECHA_TRANSACCION.'</p></td>
					<td >&nbsp;</td>
					<td ><p><strong>Instituci&oacute;n Bancar&iacute;a:</strong></p></td>
					<td width="110" align=left><p>Webpay</p></td>
                </tr>
				<tr valign=top>
					<td align=left><p><strong>Tipo Transacci&oacute;n:</strong></p></td>
					<td align=left><p>RECARGA</p></td>
					<td >&nbsp;</td>
					<td ><strong>ID Registro:</strong></td>
                                        <td align=left><p>'. $TBK_ID_TRANSACCION.'</p></td>
                </tr>
                                <tr valign=top>
                                        <td align=left>&nbsp;</td>
                                        <td align=left>&nbsp;</td>
                                        <td >&nbsp;</td>
                                        <td >&nbsp;</td>
                                        <td align=left>&nbsp;</td>
                </tr>
			</table>
		</td>
	</tr>
    <tr>
      <td align="left"><h2>Tarjetas Recargadas:</h2></td>
    </tr>
    <tr>
      <td align="center">			<table width="95%" style="border:1px solid #DDDDDD;">
				<tr valign="top">
				  <td align="center">'.$tarjetas_html.'</td>
</tr></table></td>
    </tr>
    
</table> ';
?>