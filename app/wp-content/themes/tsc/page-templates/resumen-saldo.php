<?php
/**
 * Template Name: Resumen saldo
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
?>
<?php get_header(); ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<div class="content">
					<div class="inner">					
						<ul class="nav2">
							<li class="current"><a href="<?php print get_page_link(7); ?>">Resumen de Saldo</a></li>
							<li><a href="<?php print get_page_link(11); ?>">Recargas no Activadas</a></li>
							<li><a href="<?php print get_page_link(9)."?tid=".$dataUser->tarjeta; ?>">Seguimientos de Recargas</a></li>
						</ul>
						
				<div class="clear"></div>
						<h2>Resumen de Saldo</h2>
						<div class="block2">
							<dl>
								<dt>Número Tarjeta</dt>
								<dd><?php print $dataUser->tarjeta; ?></dd>
							</dl>
							<dl>
								<dt>Saldo disponible al <?php print $dataUser->fecha; ?> <?php print $dataUser->hora; ?></dt>
								<dd>
									<?php if( $dataUser->saldo > 0 ){ ?>
									$<?php print number_format($dataUser->saldo, 0 ,",", ".") ?>
									<?php }else{?>
									Sin Saldo <br>
									<?php } ?>
								</dd>
							</dl>
							
							<div class="clear"></div>	<em>*Saldo se activa al realizar la primera transacción</em>
							<div class="button-holder">
                          
								<a class="buttons button1" href="<?php print get_page_link(5); ?>">Recargar Tarjeta</a>
								<a class="buttons button2" href="<?php print get_page_link(9)."?tid=".$dataUser->tarjeta; ?>">Detalle de recarga</a>
							</div>
							<div class="clear"></div>
						</div>
						
							<div class="clear"></div>
						<div class="block block4">
							<h4>Otras Tarjetas asociadas</h4>
						
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
											<td><a href="<?php print get_page_link(9)."?tid=".$asociada->tarjeta; ?>">Detalle de Recarga</a></td>
											<td><a href="<?php print get_page_link(5)."?tid=".$asociada->tarjeta; ?>" class="link2" >Recargar Tarjeta</a></td>
										</tr>
										<?php $i++; } ?>
									</tbody>
								</table>
							<?php } ?>
                          
                            
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