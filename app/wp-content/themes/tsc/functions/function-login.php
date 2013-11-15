<?php
/*
 * Funcion Login TSC
 */
add_action('wp_ajax_nopriv_login_tsc', 'login_tsc');
add_action('wp_ajax_login_tsc', 'login_tsc');
function login_tsc(){
	global $wpdb;
	if($_POST){
		if(
			isset($_POST["rut"]) && !empty($_POST["rut"]) &&
			isset($_POST["nrotarj"]) && !empty($_POST["nrotarj"])
		){
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
			print json_encode(array("data" => array("result" => 0, "msg" => "Ingrese RUT y/o Número Tarjeta.")));
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
?>
