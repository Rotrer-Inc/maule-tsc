<?php
session_start();
/*
 * Constantes Sitio
 */
define(APP_JQ, get_bloginfo("wpurl")."/wp-admin/admin-ajax.php");
/*
 * Includes funciones por seccion
 */
include 'functions/function-login.php';
include 'functions/function-misaldo.php';
include 'functions/function-actualizadatos.php';
include 'functions/function-recargas.php';
/*
 * Formater string RUT
 */
function getPuntosRut( $rut ){
	$rutTmp = substr($rut, 0, strlen($rut)-1);
	$dvTmp  = substr($rut, -1, 1);
	return number_format( $rutTmp, 0, "", ".") . '-' . $dvTmp;
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
/**
* Print_r convenience function, which prints out <PRE> tags around
* the output of given array. Similar to debug().
*/
	function pr($var) {
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}
/*
 * Imprimir querys
 */
##Debug queries print_r($wpdb->queries);
define( 'SAVEQUERIES', true );
?>