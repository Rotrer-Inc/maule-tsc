<?php
/**
 * Template Name: Recarga NO Activas
 */
$noactivas = getRecargaNoActivas();
?>
<?php get_header(); ?>
			<?php while ( have_posts() ) : the_post(); ?>
			<div class="content">
				<div class="inner">					
					<ul class="nav2">
						<li><a href="<?php print get_page_link(7); ?>">Resumen de Saldo</a></li>
						<li class="current"><a href="<?php print get_page_link(11); ?>">Recargas no Activadas</a></li>
						<li><a href="<?php print get_page_link(9); ?>">Seguimientos de Recargas</a></li>
					</ul>
					
				<div class="clear"></div>
					<h2>Detalle Recargas no Activadas</h2>
					<div class="block block4">
						<p class="button-holder v2">
							<a class="button3" href="<?php print APP_JQ."?action=exportaNoActivas"; ?>">Descargar detalle</a>
						</p>
						<div class="accord">
							<?php foreach ( $noactivas as $key => $nactiva ) { ?>
							<h4 class="<?php print (count($nactiva->recargas) > 0) ? 'active' : ''; ?>">Tarjeta Prepago Nº <?php print $nactiva->tarjeta; ?><span></span></h4>
							<div class="block2">
								<table border="0">
								<?php if($nactiva->recargas){ ?>
									<thead>
										<tr>
											<th>Fecha</th>
											<th>Hora</th>
											<th>Monto</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ( $nactiva->recargas as $key => $value ) { ?>
										<tr>
											<td><?php print $value->fecharecarga; ?></td>
											<td><?php print $value->hora; ?></td>
											<td>$<?php print number_format($value->monto, 0 ,",", "."); ?></td>
										</tr>
									<?php } ?>
									</tbody>
								<?php }else{ ?>
									<thead>
										<tr>
											<th>Sin recarga NO activas.</th>
										</tr>
									</thead>
								<?php } ?>
								</table>
							</div>
							<?php } ?>
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