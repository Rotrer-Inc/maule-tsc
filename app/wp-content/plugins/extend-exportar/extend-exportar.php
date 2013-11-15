<?php
/*
Plugin Name: Extend Exportar
Plugin URI: http://neuralis.cl/
Description: Exportar transacciones sitio web
Version: 0.1
Author: Cristian Alvarado
Author URI: http://neuralis.cl
License: GPL2
*/

// Define current version constant
define( 'EXT-EXPORTA', '0.1' );

add_action('admin_init', 'js_extend_exporta');
add_action('admin_menu', 'extend_plugin_menu2');
add_action('wp_ajax_contacto_exporta', 'contacto_exporta');
add_action('wp_ajax_ajustes_detalle_recarga', 'ajustes_detalle_recarga');


#Carga archivo JS plugin 
function js_extend_exporta(){
	wp_register_style('plgCssExtendExp', WP_PLUGIN_URL . '/extend-exportar/css/ui-lightness/jquery-ui-1.8.14.custom.css');
	wp_enqueue_style('plgCssExtendExp');
	wp_register_script('plgJsExtendExportaUi', WP_PLUGIN_URL . '/extend-exportar/js/jquery-ui-1.8.14.custom.min.js');
	wp_enqueue_script('plgJsExtendExportaUi');
	wp_register_script('plgJsExtendExporta', WP_PLUGIN_URL . '/extend-exportar/js/extend-exportar.js');
	wp_enqueue_script('plgJsExtendExporta');
	
}

function extend_plugin_menu2(){
	add_menu_page('Webpay', 'Webpay', 'administrator', 'ajustes_exporta', 'ajustes_exporta_fn');
		add_submenu_page( 'ajustes_exporta', 'Detalle Recarga', 'Detalle Recarga', 'administrator', 'ajustes_detalle_recarga', 'ajustes_detalle_recarga' );
	add_menu_page('Folios', 'Folios', 'administrator', 'ajustes_folio', 'ajustes_folio_fn');
}

function contacto_exporta(){
	global $wpdb; // this is how you get access to the database
	
	@extract($_POST);
	if( $inicio && $fin):
		$where = ' where DATE(fecha) BETWEEN "'.$inicio.'" and "'.$fin.'"';
	endif;
	$querystr = 'SELECT id, asunto, nombre, email, fono, mensaje, DATE_FORMAT(fecha ,"%d-%m-%Y %h:%i:%s") as fecha FROM '.$wpdb->prefix.'contacto '.$where.' order by id ASC';
	$contactosReg = $wpdb->get_results($querystr);
	#$cuerpo = "Numero;Nombre;Email;Fono;Mensaje;Fecha"."\r\n";
	$i = 2;
	foreach($contactosReg as $registro):
		
		$i++;
		#$cuerpo .= $registro->id.";".$registro->nombre.";".$registro->email.";".$registro->fono.";".$registro->mensaje.";".$registro->fecha."\r\n";
	endforeach;
	
	$wpdb->flush();
}

function ajustes_folio_fn(){
	global $wpdb;
	$msg = '';
	if(isset($_POST) && !empty($_POST)){
		@extract($_POST);
		$wpdb->update( 
			$wpdb->prefix.'app_folio', 
			array( 
				'inicial' => $inicial,
				'final' => $final,
				'actual' => $actual,
				'alerta' => $alerta
			), 
			array( 'id' => 1 ), 
			array( 
				'%d',
				'%d',
				'%d',
				'%d'
			), 
			array( '%d' ) 
		);
		$msg = 'Los datos han actualizados correctamente';
	}
	$querystr = 'SELECT id, inicial, final, actual, alerta FROM '.$wpdb->prefix.'app_folio where id = 1';
	$folios = $wpdb->get_row($querystr);
?>
	<div class="wrap">
		<div id="icon-plugins" class="icon32"><br></div>
	    <h2>Administrador Folio</h2>
		<table border="0" cellspacing="10" class="widefat">
			<tbody>
				<tr>
					<td width="100%" valign="top">
						<span style="color:red;"><?php echo $msg; ?></span>
						<form id="folios_frm" name="folios_frm" method="post" action="">
							<table>
								<tbody>
									<tr valign="top">
										<th scope="row">Inicial:<span style="color:red;">*</span></th>
										<td>
											<input type="text" name="inicial" id="inicial" value="<?php echo $folios->inicial; ?>" />
										</td>
										<th scope="row">Final:<span style="color:red;">*</span></th>
										<td>
											<input type="text" name="final" id="final" value="<?php echo $folios->final; ?>" />
										</td>
										<th scope="row">Actual:<span style="color:red;">*</span></th>
										<td>
											<input type="text" name="actual" id="actual" value="<?php echo $folios->actual; ?>" />
										</td>
										<th scope="row">Alerta:<span style="color:red;">*</span></th>
										<td>
											<input type="text" name="alerta" id="alerta" value="<?php echo $folios->alerta; ?>" />
										</td>
										<th scope="row"></th>
										<td>
											<input type="submit" class="button-primary" name="frm_contacto" value="Actualizar">
										</td>
									</tr>
									<tr valign="top">
										
									</tr>
								</tbody>
							</table>
						</form>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
<?php
}

function ajustes_exporta_fn(){
	global $wpdb;
	$whereDate = '';
	@extract($_POST);
	@extract($_GET);

	if($inicio && $fin):
		$filtroFecha = '&inicio='.$inicio.'&fin='.$fin;
		$whereDate = ' where DATE(comp.fecha_pago) BETWEEN "'.$inicio.'" and "'.$fin.'"';
	endif;
	$querystr = 'select 
				comp.id, DATE_FORMAT(comp.fecha_pago, "%d-%m-%Y %H:%i:%s") as fecha_pago, comp.total, comp.id_cliente, 
				comp.TBK_ORDEN_COMPRA, comp.TBK_MONTO, comp.TBK_CODIGO_AUTORIZACION,
				comp.TBK_FINAL_NUMERO_TARJETA, comp.TBK_ID_SESION, comp.TBK_ID_TRANSACCION,
				comp.TBK_TIPO_PAGO, comp.TBK_NUMERO_CUOTAS, comp.estado, DATE_FORMAT(comp.fecha_cotizacion, "%d-%m-%Y %H:%i:%s") as fecha_cotizacion
				from '.$wpdb->prefix.'app_compra_online as comp '.$whereDate;
	
	$total_query = "SELECT COUNT(1) FROM (${querystr}) AS combined_table";
    $total = $wpdb->get_var( $total_query );
    $items_per_page = 20;
    $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
    $offset = ( $page * $items_per_page ) - $items_per_page;

	$results = $wpdb->get_results($querystr." order by comp.fecha_pago DESC limit ${offset}, ${items_per_page}");
	$wpdb->flush();
	$urlDetalle = admin_url( 'admin.php?page=ajustes_detalle_recarga' );
?>
	<div class="wrap">
		<div id="icon-plugins" class="icon32"><br></div>
	    <h2>Transacciones Webpay</h2>
		<table border="0" cellspacing="10" class="widefat">
			<tbody>
				<tr>
					<td width="100%" valign="top">
						<h3>Webpay</h3>
						<p>Seleccione la fecha de inicio y fin.</p>
						<form id="contacto_frm_exp" name="contacto_frm_exp" method="post" action="">
							<input type="hidden" name="redir" id="redir" value="<?php echo admin_url( 'admin.php?page=ajustes_exporta' ); ?>">
							<table>
								<tbody>
									<tr valign="top">
										<th scope="row">Inicio:<span style="color:red;">*</span></th>
										<td>
											<input type="text" name="inicio" id="inicio" value="<?php echo $inicio; ?>" />
										</td>
										<th scope="row">Fin:<span style="color:red;">*</span></th>
										<td>
											<input type="text" name="fin" id="fin" value="<?php echo $fin; ?>" />
										</td>
										<th scope="row"></th>
										<td>
											<input type="submit" class="button-primary" name="frm_contacto" value="Buscar" style="width:45%">
											<input type="buttom" class="button-primary" name="frm_limpiar" id="frm_limpiar" value="Limpiar" style="text-align: center; width: 45%;">
										</td>
									</tr>
									<tr valign="top">
										
									</tr>
								</tbody>
							</table>
						</form>
					</td>
				</tr>
			</tbody>
		</table>
		<p></p>
		<table border="0" cellspacing="10" class="widefat">
			<tbody>
				<tr>
					<td width="100%" valign="top">
						<h3>Últimas transacciones</h3>
						<table>
							<tbody>
								<tr valign="top">
									<th scope="row">ID</th>
									<th scope="row">RUT CLIENTE</th>
									<th scope="row">Fecha Pago</th>
									<th scope="row">Total</th>
									<th scope="row">TBK ORDEN COMPRA</th>
									<th scope="row">TBK MONTO</th>
									<th scope="row">TBK CODIGO AUTORIZACION</th>
									<th scope="row">TBK FINAL NUMERO TARJETA</th>
									<th scope="row">TBK ID SESION</th>
									<th scope="row">TBK ID TRANSACCION</th>
									<th scope="row">TBK TIPO PAGO</th>
									<th scope="row">TBK NUMERO CUOTAS</th>
									<th scope="row">ESTADO</th>
									<th scope="row">Fecha Cotización</th>
									<th scope="row">Detalle</th>
								</tr>
								<?php foreach ($results as $key => $res) { ?>
								<tr valing="top">
									<td><?php echo $res->id; ?></td>
									<td><?php echo $res->id_cliente; ?></td>
									<td><?php echo $res->fecha_pago; ?></td>
									<td><?php echo $res->total; ?></td>
									<td><?php echo $res->TBK_ORDEN_COMPRA; ?></td>
									<td><?php echo $res->TBK_MONTO; ?></td>
									<td><?php echo $res->TBK_CODIGO_AUTORIZACION; ?></td>
									<td><?php echo $res->TBK_FINAL_NUMERO_TARJETA; ?></td>
									<td><?php echo $res->TBK_ID_SESION; ?></td>
									<td><?php echo $res->TBK_ID_TRANSACCION; ?></td>
									<td><?php echo $res->TBK_TIPO_PAGO; ?></td>
									<td><?php echo $res->TBK_NUMERO_CUOTAS; ?></td>
									<td><?php echo ($res->estado) ? $res->estado : ''; ?></td>
									<td><?php echo $res->fecha_cotizacion; ?></td>
									<td><a href="<?php echo $urlDetalle. '&oc='; ?><?php echo ($res->estado == 'APROBADO') ? $res->TBK_ORDEN_COMPRA : '0'; ?>">Ver Detalle</a></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<table>
							<tr>
								<td><?php echo paginate_links( array(
							        'base' => add_query_arg( 'cpage', '%#%' ),
							        'format' => '',
							        'prev_text' => __('&laquo;'),
							        'next_text' => __('&raquo;'),
							        'total' => ceil($total / $items_per_page),
							        'current' => $page,
							        'add_fragment' => $filtroFecha
							    )); ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div><!--<span style="color:red;">*</span>-->
<?php
}

function ajustes_detalle_recarga(){
	global $wpdb;
	@extract($_GET);
	if(!isset($oc) && empty($oc)){
		$urlDetalle = admin_url( 'admin.php?page=ajustes_exporta' );
		echo '<script type="text/javascript">location.href = "'.$urlDetalle.'";</script>';
		exit();
	}else{
		$querystr = 'select 
					rec.id, rec.rut_cliente, DATE_FORMAT(rec.fecha_recarga, "%d-%m-%Y %H:%i:%s") as fecha_recarga, rec.id_tabla_pago, rec.id_transaccion_institucion, rec.estado, rec.monto_total
					from '.$wpdb->prefix.'app_recarga_online as rec
					where rec.id_tabla_pago = "'.$oc.'"';
		$results = $wpdb->get_row($querystr);

		$querystrTsc = 'select 
					recdet.id, recdet.id_recarga_online, recdet.tsc, recdet.monto, recdet.institucion, recdet.monto_total, recdet.folio
					from '.$wpdb->prefix.'app_recarga_online_detalle as recdet
					where recdet.id_recarga_online = "'.$results->id.'"';
		$resultsTsc = $wpdb->get_results($querystrTsc);
		$wpdb->flush();
?>
	<div class="wrap">
		<div id="icon-plugins" class="icon32"><br></div>
	    <h2>Transacciones Webpay</h2>
		<table border="0" cellspacing="10" class="widefat">
			<tbody>
				<tr>
					<td width="100%" valign="top">
						<h3>Recarga OC Nº: <?php echo $oc; ?></h3>
						<table>
							<tbody>
								<tr valign="top">
									<th scope="row">Rut Cliente</th>
									<th scope="row">Fecha Recarga</th>
									<th scope="row">Orden Compra</th>
									<th scope="row">ID Transacción TBK</th>
									<th scope="row">Estado</th>
									<th scope="row">Monto Total</th>
								</tr>
								<tr valign="top">
									<td><?php echo $results->rut_cliente; ?></td>
									<td><?php echo $results->fecha_recarga; ?></td>
									<td><?php echo $results->id_tabla_pago; ?></td>
									<td><?php echo $results->id_transaccion_institucion; ?></td>
									<td><?php echo strtoupper($results->estado); ?></td>
									<td><?php echo $results->monto_total; ?></td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%" valign="top">
						<h3>Detalle TSC</h3>
						<table>
							<tbody>
								<tr valign="top">
									<th scope="row">ID</th>
									<th scope="row">ID Recarga Online</th>
									<th scope="row">TSC</th>
									<th scope="row">Monto</th>
									<th scope="row">Institución</th>
									<th scope="row">Monto Total</th>
									<th scope="row">Folio</th>
								</tr>
								<?php foreach ($resultsTsc as $key => $res) { ?>
								<tr valign="top">
									<td><?php echo $res->id; ?></td>
									<td><?php echo $res->id_recarga_online; ?></td>
									<td><?php echo $res->tsc; ?></td>
									<td><?php echo $res->monto; ?></td>
									<td><?php echo strtoupper($res->institucion); ?></td>
									<td><?php echo $res->monto_total; ?></td>
									<td><?php echo $res->folio; ?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div><!--<span style="color:red;">*</span>-->
<?php
	}
}