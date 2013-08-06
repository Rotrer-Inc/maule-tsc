<?php
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

	if($result)
		foreach ($result as $tsc){
			if ($tsc->nroCuentaRecep == $_SESSION['mitsc_tsc'])
				$selec = ' selected="selected" ';
			else $selec = '';
			$select .= '<option value="'.$tsc->nroCuentaRecep.'" '.$selec.'>'. $tsc->nroCuentaRecep. '</option>';				
		}
	
	return $select;
}
/*
 * Funcion obtener ciudades(tabla provincias)
 */
function getCiudad( $ciudadName ){
	global $wpdb;
	
	$querystr = "SELECT * FROM  ".$wpdb->prefix."app_con_provincia ORDER BY id_region";
	$result = $wpdb->get_results( $querystr );
	
	if($result)
		foreach ($result as $ciudad){
			if ($ciudad->nombre == $ciudadName)
				$selec=' selected="selected" ';
			else $selec='';
			$select .= '<option value="'.$ciudad->nombre.'" '.$selec.'>'. $ciudad->nombre . '</option>';				
		}
		
	return $select;
}
/*
 * Funcion obtener region
 */
function getRegion(){
	global $wpdb;
	
	$querystr = "SELECT * FROM  ".$wpdb->prefix."app_con_region ORDER BY orden";
	$result = $wpdb->get_results( $querystr );
	
	if($result) 
		foreach ($result as $region){
			$select .= '<option value="'.$region->id.'" >'. $region->nombre;
			if ($region->id !=13 )
				$select .= " ". $region->texto;
			$select .=  '</option>';				
		}
		
	return $select;
}

/*
 * Funcion obtener comunas
 */
function getComunas( $comunaName ){
	global $wpdb;
	
	$querystr = "SELECT * FROM  ".$wpdb->prefix."app_con_comuna ORDER BY id_region, nombre";
	$result = $wpdb->get_results( $querystr );
	if ($result )
		foreach ($result as $comuna){
			if ($comuna->nombre == $comunaName)
				$selec=' selected="selected" ';
			else $selec='';
			$select .= '<option value="'.$comuna->nombre.'" '.$selec.'>'. $comuna->nombre . '</option>';				
		}
		
	return $select;
}
/*
 * Funcion enviar datos a actualizar
 */
add_filter('wp_mail_content_type','set_content_type');
function set_content_type($content_type){
	return 'text/html';
}
function sendActualizaTsc( $post ){
	@extract($post);
	// Additional headers
	if ($numerocuenta  >0) {
		$mail->Mailer = 'smtp';
		$hoy = date('d-m-Y H:i:s');
		$fromname = $razonsocial ;
		$asunto = "Actualizar a $fromname - Tarjeta de Prepago $numerocuenta: ";
		$body = "<br /><br />
		   <strong>Rut:</strong> $rut <br />
		   <strong>N&deg; Cuenta:</strong> $numerocuenta <br />
		   <strong>Raz&oacute;n Social o Nombre:</strong> $razonsocial<br />
		   <strong>Giro:</strong>  $giro <br />
		  <strong> Email:</strong> $email <br />
		   <strong>Tel&eacute;fono:</strong> $prefijo - $fono <br />
		   <strong>Direcci&oacute;n:</strong> $direccion <br />
		   <strong>Comuna :</strong> $comuna <br />
		  <strong> Ciudad:</strong> $ciudad <br />

		   <br />
		   <br />
		   <br />
		   Enviado el: $hoy
		   ";
		#$destinatario = "jgerding@cintra.cl";
		$destinatario = "cristian@rotrer.com";
		wp_mail( $destinatario, "Tarjeta de Prepago $numerocuenta  -" .$razonsocial, $body );
		
		return true;
	}
}
?>
