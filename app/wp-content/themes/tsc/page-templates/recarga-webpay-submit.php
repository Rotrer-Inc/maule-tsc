<?php
/**
 * Template Name: Recarga Webpay Submit
 *
 * Description: Template recarga webpay submit
 */
session_start();
$sessionTsc = $_SESSION;
session_name("TBK");
$setWebpay = setWebpaySubmit($sessionTsc);
?>
<script>
function PagoEnLinea(){ 
	document.frmEnvia.submit();
}
setTimeout('document.frm_pagokcc5.submit()',0);
</script>
<form action="<?php print get_bloginfo("wpurl"); ?>/KCC/cgi-bin/tbk_bp_pago.cgi" name="frm_pagokcc5" method="post" style="display:none;">
    <input type="hidden" name="TBK_TIPO_TRANSACCION" value="<?php print $setWebpay->TBK_TIPO_TRANSACCION;?>"/>
    <input type="hidden" name="TBK_MONTO" value="<?php print $setWebpay->TBK_MONTO;?>"/> 
    <input type="hidden" name="TBK_ORDEN_COMPRA" value="<?php print $setWebpay->TBK_ORDEN_COMPRA;?>"/>
    <input type="hidden" name="TBK_ID_SESION" value="<?php print $setWebpay->TBK_ID_SESION;?>"/>
    <input type="hidden" name="TBK_URL_EXITO" value="<?php print $setWebpay->TBK_URL_EXITO;?>"/>
    <input type="hidden" name="TBK_URL_FRACASO" value="<?php print $setWebpay->TBK_URL_FRACASO;?>"/>               
	<input type="submit" value="enviar" />
</form>