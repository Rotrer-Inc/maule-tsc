<?php
/**
 * Template Name: Recarga Paso 2
 *
 * Description: Template recarga
 */
if($_POST){
	if( count($_POST) == 4){
		$responseRecarga = processRecargaMulti($_POST);
	}else{
		$responseRecarga = processRecargaSingle($_POST);
	}
	#pr($_SESSION["recarga"]);
}else{
	wp_redirect( get_bloginfo("url") );
	exit();
}
?>
<?php get_header(); ?>
			<?php while ( have_posts() ) : the_post(); ?>
			<div class="content">
				<div class="inner">					
					<h2>Recarga</h2>
					<div class="nav3">
						<ul>
							<li><span>1</span> Seleccionar</li>
							<li class="current"><span>2</span> Medio de Pago</li>
							<li><span>3</span> Comprobante</li>
						</ul>
					</div>
					<div class="block">
					  <div class="accord2">
						<h3><span>2</span>Medios de pago</h3>
						  <h4 class="active">Monto a pagar <span></span></h4>
							<div class="">
							<div class="block2">
								<?php if($responseRecarga->status){ ?>
								<table border="0">
									<thead>
										<tr>
											<th>Nº</th>
											<th>Número de Tarjeta</th>
											<th>Saldo Actual</th>
											<th>Monto a Recargar ($)</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ( $_SESSION["recarga"]["tscs"] as $key => $valueTsc ) { ?>
										<tr>
											<td><?php print $key+1; ?></td>
											<td><?php print $valueTsc["tsc"]; ?></td>
											<td>$<?php print number_format($valueTsc["saldo"], 0 ,",", ".") ?></td>
											<td>$<?php print number_format($valueTsc["monto"], 0 ,",", ".") ?></td>
										</tr>
									<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td></td>
											<td></td>
											<td>Total</td>
											<td class="price">$<?php print number_format($_SESSION["recarga"]["totalParaRecarga"], 0 ,",", ".") ?></td>
										</tr>
									</tfoot>
								</table>
								<?php }else{ ?>
								<h1>Error: <?php print $responseRecarga->msg; ?></h1>
								<?php } ?>
							</div>


						</div>
					  </div>
					</div>

					<div class="pagos">
						<h4>Pagar con:</h4>
						<img class="webpay alignright" src="<?php echo get_template_directory_uri(); ?>/images/webpay.gif" alt="webpay" />
						<p class="button-holder">
							<?php if($responseRecarga->status){ ?>
							<a class="buttons button4" href="webpay.php">Confirmar</a>
							<?php } ?>
							<a class="buttons button2" href="<?php print get_page_link(5); ?>">Volver</a>
						</p>
					</div>

				</div>
			</div>
			<div class="content2">
				<div class="inner">
					<p>Les recordamos que todas las recargas se validan en las vías de peaje, cada 6 horas y en horarios fijos. Los horarios de validación son: 11:00 am a 17:00 pm y de 23:00 pm a 05:00 am. Es decir, si realiza una recarga a las 09:00 am esta ya estará disponible en las vías de peajes a las 11:00 am, así cuando aproxime su Tarjeta de Prepago a cualquier lector de vía de peaje, el saldo se grabará en el chip de su plástico.</p>
					<p>Todas las recargas que se realicen vía Web, quedan inmediatamente disponible para su revisión en el modulo “Recargas No Activas”, donde podrá identificar, el día, el monto y el horario de la recarga. La Recarga se hará efectiva la próxima vez que utilice su TSC en las plazas de peajes de la concesión. Es recién acá, cuando la consulta de saldo se actualizará en el sitio.</p>
				</div>
			</div>	
			<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>