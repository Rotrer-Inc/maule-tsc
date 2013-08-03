<?php
/**
 * Template Name: Resumen saldo
 *
 * Description: Template resumen despues de logear
 */

get_header(); ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<div class="content">
					<div class="inner">					
						<ul class="nav2">
							<li class="current"><a href="#">Resumen de Saldo</a></li>
							<li><a href="#">Recargas no Activadas</a></li>
							<li><a href="#">Seguimientos de Recargas</a></li>
						</ul>
						<h2>Resumen de Saldo</h2>
						<div class="block2">
							<dl>
								<dt>Número Tarjeta</dt>
								<dd>123</dd>
							</dl>
							<dl>
								<dt>Saldo disponible al $fecha_saldo</dt>
								<dd>$10000</dd>
							</dl>
							<p>
								<em>*Saldo se activa al realizar la primera transacción</em>
							</p>
							<p class="button-holder">
								<a class="buttons button1" href="#">Recarga de Tarjeta</a>
								<a class="buttons button2" href="#" onClick="">Detalle de Recarga</a>
							</p>
							<div class="clear"></div>
						</div>
						<div class="block">
							<h4>Otras Tarjetas asociadas</h4>
							<code>
								echo "<h5>No existen tarjetas asociadas</h5>";
							}else{
							echo '<table border="0">
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
								<tbody>';
                                
								$objtsctodas=new rutamaule;
								$listatodas= $objtsctodas->mitsc_todas($_SESSION['mitsc_rut']);
								$count=1;
								while($rowtotal = mysql_fetch_array($listatodas)){
								$fecha_saldototal=$rowtotal['fecha']." ".$rowtotal['hora'];
								if($rowtotal['tarjeta']!=$_SESSION['mitsc_tsc']){
									echo '<tr>
											<td>'.$count.'</td>
											<td>'.$rowtotal['tarjeta'].'</td>
											<td>('.$fecha_saldototal.')</td>
											<td>$'.number_format($rowtotal['saldo'], 0 ,",", ".").' </td>';
											echo "<td><a href='#' onclick='detallesaldo(\"".$_SESSION['mitsc_rut']."\",".$rowtotal['tarjeta']."); return false'>Detalle de Recarga</a></td>";
											echo '<td><a class="link2" href="#">Recargas Tarjeta</a></td>
										</tr>';
										$count++;
									}
								}
								
								
								echo '</tbody>
							</table>';
                            </code>
                            
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