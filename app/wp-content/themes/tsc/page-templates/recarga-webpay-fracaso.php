<?php
/**
 * Template Name: Recarga Webpay Fracaso
 *
 * Description: Template recarga webpay fracaso
 */
session_start();
if($_POST){
	$responseExito = saveFracaso($_POST);
}else{
	wp_redirect("url");
	exit();
}
?>
<?php get_header(); ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<div class="comprobanteholder">
			<!--- NUEVO -->
            
            <div class="content">
            <div class="inner-fracaso">
<<<<<<< HEAD
						
				<div class="clear"></div>
            <h2>Fracaso</h2>
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" id="fracaso">
=======
            <h2>Fracaso</h2>
			<table id="fracaso" width="100%" border="0" cellspacing="0" cellpadding="0">
>>>>>>> 51091f05633421a29193c6361bf1f81a11c155c1
              <tr>
                <th scope="col">Orden de compra: <?php print $responseExito->TBK_ORDEN_COMPRA; ?></th>
              </tr>
              <tr>
                <td>
                	<div id="contenido-fracaso">
                        <p>Las posibles causas de este rechazo son:</p>
                        <ul>
                        	<li>Error en el ingreso de los datos de su tarjeta de crédito o debito (fecha y/o código de seguridad).</li>
                            <li>Su tarjeta de crédito o debito no cuenta con el cupo necesario para cancelar la compra.</li>
                            <li>Tarjeta aún no habilitada en el sistema financiero.</li>
                        </ul>
                        <p>Si el problema persiste favor comunicarse con su banco emisor.</p>
                    </div>
                </td>
              </tr>
            </table>
            <!--<input type="button" value="Imprimir" id="imprimir_webpay">-->
            </div>
            </div>
            
			<!-- END NUEVO -->
		</div>	
	<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>