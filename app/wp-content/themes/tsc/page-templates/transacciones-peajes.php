<?php
/**
 * Template Name: Transacciones Peajes
 */
if( !empty($_GET["tid"]) ){
	$nrotarj = sanitize_text_field( wp_kses($_GET["tid"], "") );
	#Si no es numerico despues de limpiar la variable
	if(!is_numeric( $nrotarj )){
		wp_redirect( get_bloginfo("url") );
		exit();
	}
	
}else{
	$nrotarj = null;
}
$dataUser = getCurrentTSC( $nrotarj );
$asociadas = getTarjetasAsociadas();
$transacciones = getTransaccionesPeaje( $nrotarj );
?>
<?php get_header(); ?>
			<?php while ( have_posts() ) : the_post(); ?>
			<div class="content">
				<div class="inner">	
					<h2>Resumen de peajes</h2>
					<div class="block">
						<p class="button-holder v2">
							<a class="button3" href="<?php print APP_JQ."?action=exportaTransaccion"; ?>">Descargar Resumen</a>
						</p>
						<div class="accord2">
							<h4 class="active">Tarjeta Prepago Nº <?php print $dataUser->tarjeta; ?><span></span></h4>
							<div class="">
								<p>Ultimas cinco transacciones efectuadas en plaza.</p>
							<?php if(!$transacciones){ ?>
							<?php }else{ ?>
								<table border="0" class="table2">
									<thead>
										<tr>
											<th>Fecha</th>
											<th>Hora</th>
											<th>Plaza</th>
											<th>Vía</th>
											<th>Monto</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ( $transacciones as $transac ) { ?>
										<tr>
											<td><?php print $transac->fechatransaccion; ?></td>
											<td><?php print $transac->hora; ?></td>
											<td><?php print $transac->nombre_plaza; ?></td>
											<td><?php print $transac->via; ?></td>
											<td>$<?php print number_format($transac->monto, 0 ,",", "."); ?></td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
								<a class="buttons button7" href="<?php print get_page_link(15)."?tid=".$dataUser->tarjeta; ?>">Ver todas</a>
							<?php } ?>
							</div>
							<div class="clear"></div>
							<h4>Otras Tarjetas asociadas<span></span></h4>
							<div class="">
							<?php if( !$asociadas ){ ?>
								<h5>No existen tarjetas asociadas</h5>
							<?php }else{ ?>
								<table border="0">
									<thead>
										<tr>
											<th>Nº</th>
											<th>Número de Tarjeta</th>
											<th>Último F.Saldo</th>
											<th>Monto</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php $i=1; foreach($asociadas as $asociada){ ?>
										<tr>
											<td><?php print $i; ?></td>
											<td><?php print $asociada->tarjeta; ?></td>
											<td><?php print $asociada->fecha; ?> <?php print $asociada->hora; ?></td>
											<td>$<?php print number_format($asociada->saldo, 0 ,",", "."); ?></td>
											<td><a href="<?php print get_page_link(13)."?tid=".$asociada->tarjeta; ?>">Ver Transacciones</a></td>
										</tr>
										<?php $i++; } ?>
									</tbody>
								</table>
							<?php } ?>
							</div>
						</div>
						<div class="clear"></div>
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