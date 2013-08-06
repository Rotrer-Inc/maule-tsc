<?php
session_start();
/*
 * Constantes Sitio
 */
define(APP_JQ, get_bloginfo("wpurl")."/wp-admin/admin-ajax.php");
/*
 * Funcion Login TSC
 */
add_action('wp_ajax_nopriv_login_tsc', 'login_tsc');
add_action('wp_ajax_login_tsc', 'login_tsc');
function login_tsc(){
	global $wpdb;
	if($_POST){
		$rut	 = sanitize_text_field( wp_kses($_POST["rut"], "") );
		$rut	 = strtoupper( str_replace(array(".","-"),"", $rut) );
		$nrotarj = sanitize_text_field( wp_kses($_POST["nrotarj"], "") );
		$querystr = "SELECT nroCuentaRecep AS tarjeta, saldo , razonSocialRecep
	 			FROM ".$wpdb->prefix."app_clientes_tac 
				WHERE nroCuentaRecep =  '".$nrotarj."' and rutRecep = '".$rut."'";
		$result = $wpdb->get_row( $querystr );
		if( $result ){
			$_SESSION['mitsc']= true;
			$_SESSION['mitsc_rut']=$rut;
			$_SESSION['mitsc_tsc']= $nrotarj;
			$_SESSION['mitsc_user']= $result->razonSocialRecep;
			print json_encode(array("data" => array("result" => 1, "msg" => "Autentificación correcta.")));
		}else{
			$_SESSION['mitsc']= false;
			$_SESSION['mitsc_rut']= "";
			print json_encode(array("data" => array("result" => 0, "msg" => "RUT y/o Número Tarjeta no coinciden.")));
		}
	}else{
		print json_encode(array("data" => array("result" => 0, "msg" => "Error al validar.")));
	}
	die();
}
/*
 * Funcion salir/cerrar sesion
 */
add_action('wp_ajax_nopriv_logout_tsc', 'logout_tsc');
add_action('wp_ajax_logout_tsc', 'logout_tsc');
function logout_tsc(){
	unset($_SESSION);
	session_destroy();
	print "OK";
	die();
}
/*
 * Funcion extrae info tarjeta y rut entregadas en login
 */
function getCurrentTSC( $nrotarj = null ){
	global $wpdb;
	
	if(!$nrotarj) $nrotarj = $_SESSION['mitsc_tsc'];
		
	$querystr = "SELECT nroCuentaRecep AS tarjeta, saldo ,DATE_FORMAT( fechasaldo, '%d-%m-%Y' ) AS fecha ,DATE_FORMAT( horasaldo, '%H:%i' ) AS hora  FROM ".$wpdb->prefix."app_clientes_tac WHERE nroCuentaRecep =  '".$nrotarj."'";
	$result = $wpdb->get_row( $querystr );
	if( $result ){
		return $result;
	}
	return false;
}
/*
 * Funcion tarjetas asociadas
 */
function getTarjetasAsociadas(){
	global $wpdb;
	$querystr = "SELECT nroCuentaRecep AS tarjeta, saldo ,DATE_FORMAT( fechasaldo, '%d-%m-%Y' ) AS fecha
			 ,DATE_FORMAT( horasaldo, '%H:%i' ) AS hora  
	 		FROM ".$wpdb->prefix."app_clientes_tac 
			WHERE rutRecep =  '".$_SESSION['mitsc_rut']."' 
			ORDER BY nroCuentaRecep ASC;";
	$result = $wpdb->get_results( $querystr );
	return $result;
}
/*
 * Funcion detalle de recargas por tarjeta
 */
function getDetalleRecarga( $nrotarj = null ){
	global $wpdb;
	
	if(!$nrotarj) $nrotarj = $_SESSION['mitsc_tsc'];
	
	$querystr = "SELECT positivo, `monto`, `hora`,`plaza`,`via`,`nro_cuenta`, DATE_FORMAT( fecha, '%d-%m-%Y' ) AS fecharecarga, p.nombre as nombre_plaza
				FROM `".$wpdb->prefix."app_recarga`
				left join ".$wpdb->prefix."app_plaza as p on p.id = plaza
				WHERE `nro_cuenta` = '$nrotarj'
				ORDER BY fecha DESC";
	$result = $wpdb->get_results( $querystr );
	return $result;
}
/*
 * Funciones recargas no activa
 */
function getRecargaNoActivas(){
	global $wpdb;
	
	$querystr = "SELECT nroCuentaRecep AS tarjeta, saldo ,DATE_FORMAT( fechasaldo, '%d-%m-%Y %H:%i' ) AS fecha 
	 		FROM ".$wpdb->prefix."app_clientes_tac 
			WHERE  rutRecep =  '".$_SESSION['mitsc_rut']."' 
			ORDER BY nroCuentaRecep ASC;";
	$result = $wpdb->get_results( $querystr );
	
	foreach( $result as $campos ) {
		if ($campos->saldo > 0)
			$saldo = $campos->saldo;
		else
			$saldo = 0;
		
		$mensaje[] = (Object) array(
			'tarjeta' => $campos->tarjeta,
			'saldo' => $saldo,
			'fecha' => $campos->fecha,
			'recargas' => getDetalleNoActiva($campos->tarjeta)
		);
	}
	
	return $mensaje;
}
function getDetalleNoActiva( $nrotarj ){
	global $wpdb;
	
	$querystr = "SELECT  `monto`, `nro_cuenta`, DATE_FORMAT( fecha, '%d-%m-%Y' ) AS fecharecarga, hora
				FROM `".$wpdb->prefix."app_recargas_x_activar`
				WHERE activa = 0 and `nro_cuenta` = '$nrotarj'
				ORDER BY fecharecarga DESC";
	$result = $wpdb->get_results( $querystr );
	
	return $result;
}
/*
 * Funcion transacciones peajes
 */
function getTransaccionesPeaje( $nrotarj, $limit = 5 ){
	global $wpdb;
	
	$fecha = date("Y-m-d" , mktime(0,0,0,date('m') -3,date('d'),date('y')));
	$fecha2 = date("d-m-Y" , mktime(0,0,0,date('m') -3,date('d'),date('y')));

    $querystr = "SELECT `monto`, `hora`,`plaza`,`via`,`nro_cuenta`, DATE_FORMAT( fecha, '%d-%m-%Y' ) AS fechatransaccion, p.nombre as nombre_plaza
			FROM `".$wpdb->prefix."app_transaccion`
			left join ".$wpdb->prefix."app_plaza as p on p.id = plaza
			WHERE `nro_cuenta` = '$nrotarj'  and fecha >= '$fecha'
			ORDER BY fecha DESC
			limit 0, $limit";

	$result = $wpdb->get_results( $querystr );
	
	return $result;
}
/*
 * Funcion Exportar Transacciones
 */
add_action('wp_ajax_nopriv_exportaTransaccion', 'exportaTransaccion');
add_action('wp_ajax_exportaTransaccion', 'exportaTransaccion');
function exportaTransaccion(){
	global $wpdb;
	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: filename=\"transacciones_".date("Ymd")."_".$_SESSION['mitsc_rut'].".xls\";");

	$querystr="SELECT 
		`nro_cuenta` AS N_TSC, 
		`monto` as VALOR_PEAJE, 
		DATE_FORMAT( fecha, '%d-%m-%Y' ) AS FECHA_TRANSACCION,
		`hora` as HORA,
		p.nombre AS PLAZA,
		`via` AS VIA 
		FROM `".$wpdb->prefix."app_transaccion`AS r
		LEFT JOIN ".$wpdb->prefix."app_clientes_tac AS c ON r.nro_cuenta = nroCuentaRecep
		LEFT JOIN ".$wpdb->prefix."app_plaza AS p ON p.id = plaza
		WHERE rutRecep =  '".$_SESSION['mitsc_rut']."' 
		ORDER BY nro_cuenta, fecha DESC";
	$result = $wpdb->get_results( $querystr );
	
	//creo tabla
	$i = 0;
	echo '<table border="1">';
	echo '<tr>';
		echo '<td bgcolor="#4f81bd">Nro. TSC</td>';
		echo '<td bgcolor="#4f81bd">Valor Peaje</td>';
		echo '<td bgcolor="#4f81bd">Fecha Transacción</td>';
		echo '<td bgcolor="#4f81bd">Hora Transacción</td>';
		echo '<td bgcolor="#4f81bd">Plaza</td>';
		echo '<td bgcolor="#4f81bd">Vía</td>';
	echo '</tr>';
	foreach ( $result as $key => $value ) {
		if ($i%2 ==0) $bgcolor = '#b8cce4';
		else $bgcolor = '#dbe5f1';
		echo '<tr>';
			echo '<td bgcolor="'.$bgcolor.'">'.$value->N_TSC.'</td>';
			echo '<td bgcolor="'.$bgcolor.'">'.number_format($value->VALOR_PEAJE, 0 ,",", ".").'</td>';
			echo '<td bgcolor="'.$bgcolor.'">'.$value->FECHA_TRANSACCION.'</td>';
			echo '<td bgcolor="'.$bgcolor.'">'.$value->HORA.'</td>';
			echo '<td bgcolor="'.$bgcolor.'">'.$value->PLAZA.'</td>';
			echo '<td bgcolor="'.$bgcolor.'">'.$value->VIA.'</td>';
		echo '</tr>';
		$i++;
	}
	echo '</table>';
	
	die();
}
/*
 * Funcion Exportar Transacciones
 */
add_action('wp_ajax_nopriv_exportaDetalle', 'exportaDetalle');
add_action('wp_ajax_exportaDetalle', 'exportaDetalle');
function exportaDetalle(){
	global $wpdb;
	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: filename=\"recargas_".date("Ymd")."_".$_SESSION['mitsc_rut'].".xls\";");

	$querystr="SELECT 
			`nro_cuenta` AS N_TSC, 
			positivo AS SIGNO,
			`monto` as MONTO, 
			DATE_FORMAT( fecha, '%d-%m-%Y' ) AS FECHA_RECARGA,
			`hora` as HORA,
			p.nombre AS PLAZA,
			`via` AS VIA 
			FROM `".$wpdb->prefix."app_recarga`AS r
			LEFT JOIN ".$wpdb->prefix."app_clientes_tac AS c ON r.nro_cuenta = nroCuentaRecep
			LEFT JOIN ".$wpdb->prefix."app_plaza AS p ON p.id = plaza
			WHERE rutRecep =  '".$_SESSION['mitsc_rut']."' 
			ORDER BY nro_cuenta, fecha DESC";
	$result = $wpdb->get_results( $querystr );
	
	//creo tabla
	$i = 0;
	echo '<table border="1">';
	echo '<tr>';
		echo '<td bgcolor="#4f81bd">Nro. TSC</td>';
		echo '<td bgcolor="#4f81bd">Monto</td>';
		echo '<td bgcolor="#4f81bd">Fecha Recarga</td>';
		echo '<td bgcolor="#4f81bd">Hora Recarga</td>';
		echo '<td bgcolor="#4f81bd">Plaza</td>';
		echo '<td bgcolor="#4f81bd">Vía</td>';
	echo '</tr>';
	foreach ( $result as $key => $value ) {
		if ($i%2 ==0) $bgcolor = '#b8cce4';
		else $bgcolor = '#dbe5f1';
		echo '<tr>';
			echo '<td bgcolor="'.$bgcolor.'">'.$value->N_TSC.'</td>';
			echo '<td bgcolor="'.$bgcolor.'">'.number_format($value->MONTO, 0 ,",", ".").'</td>';
			echo '<td bgcolor="'.$bgcolor.'">'.$value->FECHA_RECARGA.'</td>';
			echo '<td bgcolor="'.$bgcolor.'">'.$value->HORA.'</td>';
			echo '<td bgcolor="'.$bgcolor.'">'.$value->PLAZA.'</td>';
			echo '<td bgcolor="'.$bgcolor.'">'.$value->VIA.'</td>';
		echo '</tr>';
		$i++;
	}
	echo '</table>';
	
	die();
}
/*
 * Funcion Exportar Transacciones
 */
add_action('wp_ajax_nopriv_exportaNoActivas', 'exportaNoActivas');
add_action('wp_ajax_exportaNoActivas', 'exportaNoActivas');
function exportaNoActivas(){
	global $wpdb;
	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: filename=\"recargas_".date("Ymd")."_".$_SESSION['mitsc_rut'].".xls\";");
	
	$querystr="SELECT 
			`nro_cuenta` AS N_TSC, 
			`monto` as MONTO, 
			 DATE_FORMAT( fecha, '%d-%m-%Y' ) AS FECHA_RECARGA,
			`hora` as HORA
			FROM `".$wpdb->prefix."app_recargas_x_activar`AS r
			LEFT JOIN ".$wpdb->prefix."app_clientes_tac AS c ON r.nro_cuenta = nroCuentaRecep
			WHERE rutRecep =  '".$_SESSION['mitsc_rut']."' 
			ORDER BY nro_cuenta, fecha DESC";
	$result = $wpdb->get_results( $querystr );
	
	//creo tabla
	$i = 0;
	echo '<table border="1">';
	echo '<tr>';
		echo '<td bgcolor="#4f81bd">Nro. TSC</td>';
		echo '<td bgcolor="#4f81bd">Monto</td>';
		echo '<td bgcolor="#4f81bd">Fecha Recarga</td>';
		echo '<td bgcolor="#4f81bd">Hora Recarga</td>';
	echo '</tr>';
	foreach ( $result as $key => $value ) {
		if ($i%2 ==0) $bgcolor = '#b8cce4';
		else $bgcolor = '#dbe5f1';
		echo '<tr>';
			echo '<td bgcolor="'.$bgcolor.'">'.$value->N_TSC.'</td>';
			echo '<td bgcolor="'.$bgcolor.'">'.number_format($value->MONTO, 0 ,",", ".").'</td>';
			echo '<td bgcolor="'.$bgcolor.'">'.$value->FECHA_RECARGA.'</td>';
			echo '<td bgcolor="'.$bgcolor.'">'.$value->HORA.'</td>';
		echo '</tr>';
		$i++;
	}
	echo '</table>';
	
	die();
}
/*
 * Funcion info cliente por rut
 */
function getActualizaUsuario(){
	global $wpdb;
	
	$querystr = "SELECT * 
	 		FROM ".$wpdb->prefix."app_clientes_tac 
			where nroCuentaRecep =  '".$_SESSION['mitsc_tsc']."' ;"; 
	$result = $wpdb->get_row( $querystr );
	
	return $result;
}
/*
 * Funcion obtener tscs por rut cliente
 */
function getTscsPorRut(){
	global $wpdb;
	
	$querystr = "SELECT * 
	 		FROM ".$wpdb->prefix."app_clientes_tac 
			WHERE rutRecep =  '".$_SESSION['mitsc_rut']."' ;";
	$result = $wpdb->get_results( $querystr );
}
/*
 * Personalización Panel Wordpress
 */
show_admin_bar(false);

function remove_dashboard_widgets(){
  global$wp_meta_boxes;
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); 
}

add_action('wp_dashboard_setup', 'remove_dashboard_widgets');

function remove_some_wp_widgets(){
  unregister_widget('WP_Widget_Calendar');
  unregister_widget('WP_Widget_Search');
  unregister_widget('WP_Widget_Recent_Comments');
}

add_action('widgets_init','remove_some_wp_widgets', 1);

function remove_menu_items() {
  global $menu;
  $restricted = array(__('Links'), __('Comments'), __('Media'),
  /*__('Plugins'),*/ __('Tools'), __('Users'), __('Posts'), __('Appearance'));
  end ($menu);
  while (prev($menu)){
    $value = explode(' ',$menu[key($menu)][0]);
    if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){
      unset($menu[key($menu)]);}
    }
  }

add_action('admin_menu', 'remove_menu_items');

/*
 * Funciones debug vars
 */
#if (!function_exists('pr')) {
/**
* Print_r convenience function, which prints out <PRE> tags around
* the output of given array. Similar to debug().
*/
	function pr($var) {
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}
#}
/*
 * Imprimir querys
 */
##Debug queries print_r($wpdb->queries);
define( 'SAVEQUERIES', true );
?>