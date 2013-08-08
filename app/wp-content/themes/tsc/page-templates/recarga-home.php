<?php
/**
 * Template Name: Recarga Home
 *
 * Description: Template recarga
 */
$dataUser = getCurrentTSC();
$asociadas = getTarjetasAsociadas();
?>
<?php get_header(); ?>
			<?php while ( have_posts() ) : the_post(); ?>
			<div class="content">
				<div class="inner">					
					<h2>Recarga</h2>
					<div class="nav3">
						<ul>
							<li class="current"><span>1</span> Seleccionar</li>
							<li><span>2</span> Medio de Pago</li>
							<li><span>3</span> Comprobante</li>
						</ul>
					</div>
					<div class="block">
						<h3><span>1</span>Recargar Tarjetas Prepago</h3>
						<p>Recarga todas tus Tarjetas de Prepago por un monto fijo o si prefieres por diferentes valores, sólo debes elegir tu Tarjetas de Prepago a recargar, agregar el monto y confirmar la operación.</p>
						<div class="accord3">
							<h4 class="active">Cargar Tarjeta <?php print $dataUser->tarjeta; ?><span></span></h4>
							<div class="block2">
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
										<tr>
											<td>1</td>
											<td><?php print $dataUser->tarjeta; ?></td>
											<td>$<?php print number_format($dataUser->saldo, 0 ,",", ".") ?></td>
											<td>
												<form id="datosSingle" name="datosSingle" method="post" action="<?php print get_page_link(20); ?>">
													<input type="hidden" name="single_tsc" id="single_tsc" value="<?php print $dataUser->tarjeta; ?>">
													<input type="hidden" name="single_tsc_saldo" id="single_tsc_saldo" value="<?php print $dataUser->saldo; ?>">
													<input type="text" name="single_value" id="single_value" value="" autocomplete="off"/>
												</form>
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td></td>
											<td></td>
											<td>Total</td>
											<td class="price singlePriceTotal">$0</td>
										</tr>
									</tfoot>
								</table>
								<p class="alignright">
									<em id="enviar">Monto mínimo $6.000 y máximo $999.999 para cada TSC</em>
								</p>
								<p class="button-holder">
									<div class="errorSingle"><p></p></div>
									<a class="buttons button4" id="send_single" href="">Continuar</a>
								</p>
							</div>
							<h4>Cargas otras Tarjetas asociadas<span></span></h4>

							<div class="block2">
								<p class="info">
									<label>Recargar monto fijo a todas las Tarjetas</label>
									<input id="multiMontoFijo" name="multiMontoFijo" type="text" value=""/>
									<div class="errorMontoFijo"><p></p></div>
									<a class="buttons button4" id="multiDarIgual" href="">Aceptar</a>
								</p>
								<form id="datosMulti" name="datosMulti" method="post" action="<?php print get_page_link(20); ?>">
									<input type="hidden" name="multiTotalVaue" id="multiTotalVaue" value="">
										<div id="resultado">
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
												<?php foreach ( $asociadas as $key => $tsc ) { ?>
													<tr>
														<td><?php print $key; ?></td>
														<td><?php print $tsc->tarjeta; ?></td>
														<td>$<?php print number_format($tsc->saldo, 0 ,",", ".") ?></td>
														<td>
															<input type="hidden" name="multi_tsc[]" value="<?php print $tsc->tarjeta; ?>">
															<input type="hidden" name="multi_tsc_saldo[]" value="<?php print $tsc->saldo; ?>">
															<input type="text" name="multi_value[]" class="multi_value" value="" autocomplete="off" />
														</td>
													</tr>
												<?php } ?>
												</tbody>
												<tfoot>
													<tr>
														<td></td>
														<td></td>
														<td>Total</td>
														<td class="price multiPriceTotal">$0</td>
													</tr>
											</tfoot>
											</table>
										</div>
								</form>
								<p class="alignright">
									<em>Monto mínimo $6.000 y máximo $999.999 para cada TSC</em>
								</p>
								<p class="button-holder">
									<div class="errorMulti"><p></p></div>
									<a class="buttons button4" id="send_multi" href="">Continuar</a>
								</p>
							</div>
						</div>
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