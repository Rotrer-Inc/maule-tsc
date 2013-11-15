<?php
/**
 * Template Name: Detalle tarjeta
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
$detalleRecargas= getDetalleRecarga( $nrotarj );
?>
<?php get_header(); ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<div class="content">
					<div class="inner">					
						<ul class="nav2">
							<li><a href="<?php print get_page_link(7)."?tid=".$nrotarj; ?>">Resumen de Saldo</a></li>
							<li><a href="<?php print get_page_link(11); ?>">Recargas no Activadas</a></li>
							<li class="current"><a href="<?php print get_page_link(9)."?tid=".$nrotarj; ?>">Seguimientos de Recargas</a></li>
						</ul>
						
				<div class="clear"></div>
						<h2>Detalle de Recargas</h2>
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
<<<<<<< HEAD
							      
								<em>*Saldo se activa al realizar la primera transacción</em>
							
							<div class="button-holder">
                     
								<a class="buttons button1" href="#">Recargar Tarjeta</a> <a class="buttons button2" href="<?php print APP_JQ."?action=exportaDetalle"; ?>">Detalle de recarga</a></div>
=======
							
							<div class="button-holder">
                            <p>
								<em>*Saldo se activa al realizar la primera transacción</em>
							</p>
								<a class="buttons button1" href="#">Recarga de Tarjeta</a>
								<a class="buttons button2" href="<?php print APP_JQ."?action=exportaDetalle"; ?>">Decarga Detalle</a>
							</div>
>>>>>>> 51091f05633421a29193c6361bf1f81a11c155c1
							<div class="clear"></div>
						</div>
						<div class="block block4">
							<h4>Ùltimas Recargas</h4>
					
							<?php if( !$detalleRecargas ){ ?>
								<h5>No existen detalle de recargas disponible.</h5>
							<?php }else{ ?>
								<table border="0">
									<thead>
										<tr>
											<th>Nº</th>
											<th>Fecha</th>
											<th>Hora</th>
											<th>Plaza</th>
											<th>Vía</th>
											<th>Monto</th>
										</tr>
									</thead>
									<tbody>
										<?php $i=1; foreach($detalleRecargas as $recarga){ ?>
										<tr>
											<td><?php print $i; ?></td>
											<td><?php print $recarga->fecharecarga; ?></td>
											<td><?php print $recarga->hora; ?></td>
											<td><?php print $recarga->nombre_plaza; ?></td>
											<td><?php print $recarga->via; ?></td>
											<td>$<?php print number_format($recarga->monto, 0 ,",", "."); ?></td>
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