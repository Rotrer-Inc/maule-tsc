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
 * Personalización Panel Wordpress
 */
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
?>