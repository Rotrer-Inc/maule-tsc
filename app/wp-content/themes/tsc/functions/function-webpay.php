<?php
/*
 * Funcion setear enviar webpay y pregristro
 */
function setWebpaySubmit($sessionTsc){
	global $wpdb;
	
	$monto = $sessionTsc['recarga']['totalParaRecarga'];
	$rut_cliente = $sessionTsc['mitsc_rut'];
	#Datos del Post
	$TBK_ID_SESION=date("Ymdhis");
	
	$_SESSION["TBK_ID_SESION"]=$TBK_ID_SESION;
	
	$TBK_TIPO_TRANSACCION="TR_NORMAL";
	//$OPS_MONTO=$_POST["TBK_MONTO"];
	
	$OPS_MONTO= $monto . ".00"; ///$_POST['total'];
	
	$fuera = array("<", ">", "/", "b", "$", ".",",");
	
	$TBK_MONTO = str_replace($fuera, "", $OPS_MONTO);
	
	$TBK_ORDEN_COMPRA = $TBK_ID_SESION;
	
	#$TBK_URL_EXITO 	 = get_bloginfo("wpurl")."/KCC/exito.php";
	#$TBK_URL_FRACASO = get_bloginfo("wpurl")."/KCC/fracaso.php";
	
	$TBK_URL_EXITO 	 = get_page_link(28);
	$TBK_URL_FRACASO = get_page_link(30);

	if ($TBK_MONTO_CUOTA!=""){
		$partesMonto=split(",",$TBK_MONTO_CUOTA);
		$TBK_MONTO_CUOTA=$partesMonto[0].".00";
	}
	
	//envia a la base de datos
	$sacaCaracter = array("-",".");
	$rut = str_replace($sacaCaracter, "", $rut_cliente);
	
	$res = registrarWebPay($monto, $TBK_ORDEN_COMPRA, $rut_cliente, $cuenta);
	
	$RES = registrar_compraonline($TBK_ID_SESION, $monto, $rut_cliente, $sessionTsc['recarga']['tscs']);
	
	//GUARDAMOS LA INFORMACIÓN DE LA TRANSACCIÓN EN UN ARCHIVO...'
	#$myPath = "F:\\WebSite\\www\\KCC5-WIN\\cgi-bin\\trx\\$TBK_ID_SESION.log";
	$myPath = KKC_ROOT.DS."cgi-bin".DS."transaccioneslog".DS.$TBK_ID_SESION.".log";
	$fic = fopen($myPath, "w+");	
	$linea = "$TBK_MONTO;$TBK_ORDEN_COMPRA";
	fwrite ($fic,$linea);
	
	$arrSetWebpay = (Object) array(
		'TBK_MONTO' => $TBK_MONTO,
		'TBK_ORDEN_COMPRA' => $TBK_ORDEN_COMPRA,
		'TBK_URL_EXITO' => $TBK_URL_EXITO,
		'TBK_URL_FRACASO' => $TBK_URL_FRACASO,
		'TBK_MONTO' => $TBK_MONTO,
		'TBK_MONTO_CUOTA' => $TBK_MONTO_CUOTA,
		'TBK_ID_SESION' => $TBK_ID_SESION,
		'TBK_TIPO_TRANSACCION' => $TBK_TIPO_TRANSACCION
	);
	
	return $arrSetWebpay;
}
/*
 * Funcion registra datos primarios webpay
 */
function registrarWebPay($total , $TBK_ORDEN_COMPRA, $rut){
	global $wpdb;
	
	$now = date("Y-m-d H:i:s");
	
	$result = $wpdb->insert( 
					$wpdb->prefix.'app_compra_online', 
					array( 
						'total' => $total, 
						'TBK_ORDEN_COMPRA' => $TBK_ORDEN_COMPRA,
						'TBK_ID_SESION' => $TBK_ORDEN_COMPRA,
						'fecha_cotizacion' => $now,
						'id_cliente' => $rut
					), 
					array( 
						'%d', 
						'%s',
						'%s',
						'%s',
						'%s'
					) 
				);
	return $result;
}
/*
 * Funcion registra datos cargas tsc pendientes de confirmar pago
 */
function registrar_compraonline($IDSESSION, $monto, $rut_cliente, $tarjetas){
	global $wpdb;
	
	$now = date("Y-m-d H:i:s");
	
	$result = $wpdb->insert( 
					$wpdb->prefix.'app_recarga_online', 
					array( 
						'fecha_recarga' => date("Y-m-d H:i:s"),
						'id_tabla_pago' => $IDSESSION,
						'institucion' => 'Webpay', 
						'id_transaccion_institucion' => '',
						'estado' => 'PENDIENTE',
						'tabla_pago' => $wpdb->prefix.'app_compra_online',
						'monto_total' => $monto,
						'rut_cliente' => $rut_cliente
					), 
					array( 
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
						'%s'
					) 
				);
		  
	if ( $result ){
		$querystr = "Select max(id) as maximo  from ".$wpdb->prefix."app_recarga_online";
		$result = $wpdb->get_row( $querystr );
		$id_recarga = $result->maximo ;
		
		if ($tarjetas)	
			foreach ($tarjetas as $recargas) {
				$wpdb->insert( 
					$wpdb->prefix.'app_recarga_online_detalle', 
					array( 
						'id_recarga_online' => $id_recarga,
						'tsc' => $recargas["tsc"],
						'institucion' => 'Webpay',
						'monto' => str_replace( ".","", $recargas['monto']),
						'monto_total' => $monto
					), 
					array( 
						'%d',
						'%d',
						'%s',
						'%d',
						'%d'
					) 
				);
			}
		
		$result = 1;
	}else {
		$result = 0;
	}
	
	return $result;
}
function saveFracaso($post){
	global $wpdb;
	
	$TBK_ID_SESION	= $post["TBK_ID_SESION"];
	$TBK_ORDEN_COMPRA	= $post["TBK_ORDEN_COMPRA"];
	
	/*
	*verificando que no se ha realizado la rendicion
	*/
	$querystr = "Select `estado`  from ".$wpdb->prefix."app_compra_online where TBK_ORDEN_COMPRA = '$TBK_ORDEN_COMPRA'";
	$result = $wpdb->get_row( $querystr );
	if($result){
		if($result->estado == "APROBADO"){
			$actualizar= false;   /// ya esta actaulizado 
		}else{
			$actualizar = true;   /// si no esta se puede actualizar
		}
	}else{
		$actualizar= false;
	}
	
	if($actualizar){
		$registroWebPay = registroWebpayExito($TBK_ID_SESION);
	}else{
		$registroWebPay = false;
	}
	#Extrae Data Webpay Exito
	$query = "Select id_cliente, razonSocialRecep, TBK_ACCION, TBK_CODIGO_COMERCIO, TBK_ORDEN_COMPRA, DATE_FORMAT(fecha_cotizacion, '%d-%m-%Y') as fecha_cotizacion,  
			TBK_CODIGO_COMERCIO_ENC, TBK_TIPO_TRANSACCION, TBK_RESPUESTA, TBK_MONTO, TBK_CODIGO_AUTORIZACION, 
			TBK_FINAL_NUMERO_TARJETA, TBK_FECHA_CONTABLE, TBK_FECHA_TRANSACCION, TBK_FECHA_EXPIRACION, TBK_HORA_TRANSACCION,
			TBK_ID_SESION, TBK_ID_TRANSACCION, TBK_TIPO_PAGO, TBK_NUMERO_CUOTAS, TBK_TASA_INTERES_MAX, TBK_MONTO_CUOTA, TBK_VCI, TBK_MAC
			from  ".$wpdb->prefix."app_compra_online 
			left join ".$wpdb->prefix."app_clientes_tac on id_cliente = rutRecep
			where `TBK_ID_SESION` = '$TBK_ID_SESION' LIMIT 1";
	$resultTsc = $wpdb->get_row( $query );
	
	return $resultTsc;
}
function saveExito($post){
	global $wpdb;
	
	$TBK_ID_SESION	= $post["TBK_ID_SESION"];
	$TBK_ORDEN_COMPRA	= $post["TBK_ORDEN_COMPRA"];
	
	/*
	*verificando que no se ha realizado la rendicion
	*/
	$querystr = "Select `estado`  from ".$wpdb->prefix."app_compra_online where TBK_ORDEN_COMPRA = '$TBK_ORDEN_COMPRA'";
	$result = $wpdb->get_row( $querystr );
	if($result){
		if($result->estado == "APROBADO"){
			$actualizar= false;   /// ya esta actaulizado 
		}else{
			$actualizar = true;   /// si no esta se puede actualizar
		}
	}else{
		$actualizar= false;
	}
	
	if($actualizar){
		$registroWebPay = registroWebpayExito($TBK_ID_SESION);
	}else{
		$registroWebPay = false;
	}
	
	if($registroWebPay){
		//SI SE COMPLETO PROCESO DE REGISTRO WEBPAY CONTINUA CON AM			
		#Seccion para generar archivo acepta.com  FACTURACION POR XML uno por cada TSC recargada en la transaccion
		$querystr = "select * from ".$wpdb->prefix."app_folio where id = 1";
		$datosFolio = $wpdb->get_row( $querystr );
		
		if(!empty($datosFolio->actual)){
			#aumentar 1
			$folioMasUno = $datosFolio->actual + 1;
		}else{
			#setear inicial + 1
			$folioMasUno = $datosFolio->inicial + 1;
		}
		#Archivo salida acepta.com
		$lenFolioFinal = strlen($datosFolio->final);
		
		if($lenFolioFinal < 7)
			$lenFolioFinal = 7;
	
		$sqlrecarga = "SELECT d.id as id_recarga,  d.tsc as ntsc, d.monto as monto_recarga, d.monto_total, r.rut_cliente as rut
				FROM ".$wpdb->prefix."app_recarga_online AS r
				LEFT JOIN ".$wpdb->prefix."app_recarga_online_detalle AS d ON d.id_recarga_online = r.id
				WHERE r.id_tabla_pago = '$TBK_ID_SESION' ";

		$tarjetas = $wpdb->get_results( $sqlrecarga );
		
		$dia=date("ymd");
		$hora=date("His");
		$date=$dia.$hora;
		
		$j=0;
		foreach($tarjetas as $recargas){
			$j++;
			#html para envio de mail 
			$tarjetas_html .='	<tr>
									<td >'. $j.'</td>
									<td >'.$recargas->ntsc.'</td>
									<td >$'.$recargas->monto_recarga .'</td>
								</tr>';
								
			$detalle_factura .=' 
			<Detalle>
				  <NroLinDet>'. $j.'</NroLinDet>
				  <NmbItem>Tarifas de Peajes TSC Nº '.$recargas->ntsc .'</NmbItem>
				  <MontoItem>'.$recargas->monto_recarga.'</MontoItem>
			  </Detalle>';					

			#GENERACION  DE CONTENIDO PARA ARCHIVO R (rendicion)
			$cuentaTSC = str_pad($recargas->ntsc, 5, "0", STR_PAD_LEFT); 
			$nrMontoFull = str_pad($recargas->monto_recarga, 6, "0", STR_PAD_LEFT); 
			$identificadorUnico = $cuentaTSC.$date.$nrMontoFull."\n";
			$datosParaArchivo.= $identificadorUnico;
			$id_recarga = $recargas->id_recarga;
			$rut = $recargas->rut;
			$ntsc = $recargas->ntsc;
			
			#ACTUALIZA FOLIO EN RECARGA
			folioCompraonline($id_recarga, $folioMasUno);  
			$r = registrarRecarga($identificadorUnico, $cuentaTSC, $dia, $hora, $rut, $nrMontoFull, "Webpay");
		}//// foreach /// RECARCAS
			
		######################### FACTURACION  ###########################################
		$nombreXmlAcepta = str_pad($folioMasUno, 7, "0", STR_PAD_LEFT) .".xml";
		$fullPathArchivoAceptaDotCom = ACEPTA_DOT_COM.DS.$nombreXmlAcepta;
		$creaArchivoAceptaDotCom =  fopen($fullPathArchivoAceptaDotCom, "w");
		
		$rutAcepta = substr($rut,0,-1).'-'.substr($rut, -1);
		
		if($creaArchivoAceptaDotCom){
			
			$datosTac = datosClienteAcepta($_SESSION['mitsc_rut'], $_SESSION['mitsc_tsc']);#obtiene datos complementarios desde la tabla clientes_tac

			$rutAceptaDotCom = substr($rut,0,-1).'-'.substr($rut, -1);#formato de rut para acepta.com
			#validacion de giro receptor para acepta.com, no debe ir vacio
			if(empty($datosTac->giroRecep))
				$datosTac->giroRecep = '.';
			
			#genera xml acepta.com
			$xmlAceptaDotCom = '<?xml version="1.0" encoding="ISO-8859-1" ?>
									<Documento id="T33F16148">
											<DTE>	
											  <Encabezado>
											      <IdDoc>
											          <TipoDTE>34</TipoDTE>
											          <Folio>'.$folioMasUno.'</Folio>
											          <FchEmis>'.date("Y-m-d").'</FchEmis>
											      </IdDoc>
											      <Emisor>
											          <RUTEmisor>96875230-8</RUTEmisor>
											          <RznSoc>Autopista del Maipo Sociedad Concesionaria S.A</RznSoc>
											          <GiroEmis>Concesiones Viales</GiroEmis>
											          <Acteco>452020</Acteco>
											          <DirOrigen>Avenida Andres Bello 2711 Piso 17</DirOrigen>
											          <CmnaOrigen>Las Condes</CmnaOrigen>
											          <CiudadOrigen>Santiago</CiudadOrigen>
									                  <CorreoEmisor>ventas@autopistadelmaipo.cl</CorreoEmisor>
											      </Emisor>
											      <Receptor>
											          <RUTRecep>'.$rutAceptaDotCom.'</RUTRecep>
											          <RznSocRecep>'.$datosTac->razonSocialRecep.'</RznSocRecep>
											          <GiroRecep>'.$datosTac->giroRecep.'</GiroRecep>
											          <DirRecep>'.$datosTac->direccionRecep.'</DirRecep>
											          <CmnaRecep>'.$datosTac->comunaRecep.'</CmnaRecep>
											          <CiudadRecep>'.$datosTac->ciudadRecep.'</CiudadRecep>
											      </Receptor>
											      <Totales>
											          <MntExe>'.$TBK_MONTO.'</MntExe>		          
											          <MntTotal>'.$TBK_MONTO.'</MntTotal>
									                  <TmstFirma>'.date("Y-m-d").'T'.date("H:i:s").'</TmstFirma>
											      </Totales>
											  </Encabezado>
											  '.$detalle_factura.'		 
											</DTE>
											<DatosAdjuntos>
											  <MailReceptor>'.$datosTac->emailRecep.'</MailReceptor>
											  <MailEmisor>ventas@autopistadelmaipo.cl</MailEmisor>
									          <Subject>Factura Recarga TSC Autopista del Maipo</Subject>
											  <Copias>0</Copias>
											</DatosAdjuntos>		
									</Documento>';
			#END genera xml acepta.com
			fwrite($creaArchivoAceptaDotCom, $xmlAceptaDotCom);
			fclose($creaArchivoAceptaDotCom);
			
			#ACTUALIZA FOLIO 
			actualizaFolio($folioMasUno);
			
			#copia en Directorio F:\Aceptax
			#$fullPathArchivoAceptaDotComCC = $conf['aceptaDotCom_bcoChileAut'].$nombreXmlAcepta;
			#copy($fullPathArchivoAceptaDotCom, $fullPathArchivoAceptaDotComCC);
			
			#copia en Directorio C:\\custodium.com\\autopistadelmaipo\\var\\ca4xml\\input\\
			#$fullPathArchivoAceptaDotComCCC = $conf['aceptaDotCom_bcoChileAce'].$nombreXmlAcepta;
			#copy($fullPathArchivoAceptaDotCom, $fullPathArchivoAceptaDotComCCC);
		}
		
		#verifica la cantidad de folios restantes si quedan menos que la alerta de folios enviara mail		
		if( ($datosFolio->final - $datosFolio->actual) <= $datosFolio->alerta){
				/*
				$bodyAlerta = 'Favor revisar disponibilidad de folios en sitio web de Autopista del Maipo,<br>
							 Datos de Folios:<br>
							 Folio Inicial:'.$datosFolio->inicial.'<br>
							 Folio Final:'.$datosFolio->final.'<br>
							 <strong>Folio Actual:'.$folioMasUno.'</strong>';
				$mailFolios = new phpmailer();
				$mailFolios->Mailer = 'smtp';
				$mailFolios->SMTPAuth = true;
				$mailFolios->Username = 'ventas@autopistadelmaipo.cl';
				$mailFolios->Password = 'vt4582jk';
				$mailFolios->FromName = 'WebSite Autopista del Maipo ';
				$mailFolios->From = 'ventas@autopistadelmaipo.cl';
				$mailDestino = 'jgerding@cintra.cl';
				$mailFolios->AddAddress($mailDestino);
				#$mailDestino = 'cristian.alvarado@neuralis.cl';
				#$mailFolios->AddAddress($mailDestino);
				$mailDestino2 = 'jorellana@cintra.cl';
				#$mailFolios->AddAddress($mailDestino2);
				$mailFolios->Subject    = "Aviso de baja en folios";
				$mailFolios->IsHTML = true;
				$mailFolios->ContentType = "text/html";
				$mailFolios->Body = $bodyAlerta;
				$mailFolios->Send();
				*/
			$asunto = "Actualizar a $fromname - Tarjeta de Prepago $numerocuenta: ";
			$bodyAlerta = 'Favor revisar disponibilidad de folios en sitio web de Autopista del Maipo,<br>
							 Datos de Folios:<br>
							 Folio Inicial:'.$datosFolio->inicial.'<br>
							 Folio Final:'.$datosFolio->final.'<br>
							 <strong>Folio Actual:'.$folioMasUno.'</strong>';
			#$destinatario = "jgerding@cintra.cl";
			$destinatario = "leonrov@gmail.com";
			wp_mail( $destinatario, "WebSite Autopista del Maipo<ventas@autopistadelmaipo.cl>", $bodyAlerta );
		}//FIN if( ($datosFolio[0]['final'] - $datosFolio[0]['actual']) <= $datosFolio[0]['alerta']){
		
		######################### FIN FACTURACION  ###########################################
		
		###################################################################################################
		######################### GENERACION DE ARCHIVO DE RENDICION 1 archivo por transaccion con n's TSC ##
		###################################################################################################
		$archivoPago = "R".$date.".DAT";  #Timestamp para el archivo
		
		$fullPathArchivoPagoWsTemp = WS_TEMP.DS.$archivoPago;#Archivo temporal de salida
		$fullPathArchivoPagoWS 	   = WS.DS.$archivoPago;#Archivo salida para sistema de peaje
		$fullPathArchivoPagoWsResp = WS_RES.DS.$archivoPago;#Archivo salida respaldo
		
		#Si crea temporal, copia los archivos definitivos
		if($creaArchivoPago =  fopen($fullPathArchivoPagoWsTemp, "w")){
		
			if(fwrite($creaArchivoPago, $datosParaArchivo)) {
				fclose($creaArchivoPago);
				
				$cpPagoWS = copy($fullPathArchivoPagoWsTemp, $fullPathArchivoPagoWS);
				
				$cpPagoWSResp = copy($fullPathArchivoPagoWsTemp, $fullPathArchivoPagoWsResp);
				
				if( $cpPagoWS && $cpPagoWSResp ){
					unlink($fullPathArchivoPagoWsTemp);
					$_SESSION['Adm']['generaSalida'] = false;
				}
				
			}else{
				/*
				$mail = new phpmailer();
				$mail->Mailer = 'smtp';
				$mail->SMTPAuth = true;
				$mail->Username = 'ventas@autopistadelmaipo.cl';
				$mail->Password = 'vt4582jk';
				$mail->FromName = 'Autopista del Maipo';
				$mail->From = 'ventas@autopistadelmaipo.cl';
				$mail->AddAddress('benito.gutierrez@neuralis.cl');			
				$mail->Subject = "Error al Generar Archivo";
				$mail->IsHTML = true;
				$mail->ContentType = "text/html";
				$bodynew = "Nombrearchivo : ".$date."<br>";
				$bodynew .="Monto : ".$monto_recarga."<br>";
				$bodynew .="Numero TSC : ".$ntsc."<br>";
				$mail->Body = $bodynew;
				$mail->Send();
				*/
				$bodynew = "Nombrearchivo : ".$date."<br>";
				$bodynew .="Monto : ".$monto_recarga."<br>";
				$bodynew .="Numero TSC : ".$ntsc."<br>";
				$destinatario = "leonrov@gmail.com";
				wp_mail( $destinatario, "Error al Generar Archivo<ventas@autopistadelmaipo.cl>", $bodynew );
			}
		}//FIN if($creaArchivoPago =  fopen($fullPathArchivoPagoWsTemp, "w")){	
		
		#include('inc_mail_comprobante.php');
		#verifica que algun mail de cliente antes de enviar correo
		if(isset($datosTac->emailRecep) && !empty($datosTac->emailRecep)){
			/*
			$mail = new phpmailer();
			$mail->Mailer = 'smtp';
			$mail->SMTPAuth = true;
			$mail->Username = 'ventas@autopistadelmaipo.cl';
			$mail->Password = 'vt4582jk';
			$mail->FromName = 'Autopista del Maipo';
			$mail->From = 'ventas@autopistadelmaipo.cl';
		//	$mail->AddAddress("denisse@neuralis.cl");
		//	$mail->AddAddress('jorellana@cintra.cl');
			$mail->AddAddress($emailRecep);			
			$mail->Subject = "Comprobante de recarga TSC WebPay ";
			$mail->IsHTML = true;
			$mail->ContentType = "text/html";
			$mail->AddEmbeddedImage("../images/imgtpl/logo-am-mail.jpg", "mi-logo", "logo-am-mail.jpg","base64", "image/jpeg");
			$mail->AddAttachment('../images/imgtpl/logo-am-mail.jpg');// attachment
			$mail->Body = $body;
			$mail->Send();
			*/
		}
			/*
			//copia de mail para Jefe Comercial
			$mail4Comercial = new phpmailer();
			$mail4Comercial->Mailer = 'smtp';
			$mail4Comercial->SMTPAuth = true;
			$mail4Comercial->Username = 'ventas@autopistadelmaipo.cl';
			$mail4Comercial->Password = 'vt4582jk';
			$mail4Comercial->FromName = 'Autopista del Maipo';
			$mail4Comercial->From = 'ventas@autopistadelmaipo.cl';
		///	$mail4Comercial->AddAddress("denisse@neuralis.cl");
			//$mail4Comercial->AddAddress("denissegc@gmail.com");
			$mail4Comercial->AddAddress("jgerding@cintra.cl");
			$mail4Comercial->Subject = "Comprobante de recarga TSC WebPay (Copia interna, area comercial - Nueva Facturación) ";
			$mail4Comercial->IsHTML = true;
			$mail4Comercial->ContentType = "text/html";
			$mail4Comercial->AddEmbeddedImage("../images/imgtpl/logo-am-mail.jpg", "mi-logo", "logo-am-mail.jpg","base64", "image/jpeg");
			$mail4Comercial->AddAttachment('../images/imgtpl/logo-am-mail.jpg');// attachment
			$mail4Comercial->Body = $body ;
			$mail4Comercial->Send();
			*/
		
		#$updateExitoReg = registrosWebPay($TBK_ID_SESION , 'exito');

		#Extrae Data Webpay Exito
		$query = "Select id_cliente, razonSocialRecep, TBK_ACCION, TBK_CODIGO_COMERCIO, TBK_ORDEN_COMPRA, DATE_FORMAT(fecha_cotizacion, '%d-%m-%Y') as fecha_cotizacion,  
				TBK_CODIGO_COMERCIO_ENC, TBK_TIPO_TRANSACCION, TBK_RESPUESTA, TBK_MONTO, TBK_CODIGO_AUTORIZACION, 
				TBK_FINAL_NUMERO_TARJETA, TBK_FECHA_CONTABLE, TBK_FECHA_TRANSACCION, TBK_FECHA_EXPIRACION, TBK_HORA_TRANSACCION,
				TBK_ID_SESION, TBK_ID_TRANSACCION, TBK_TIPO_PAGO, TBK_NUMERO_CUOTAS, TBK_TASA_INTERES_MAX, TBK_MONTO_CUOTA, TBK_VCI, TBK_MAC
				from  ".$wpdb->prefix."app_compra_online 
				left join ".$wpdb->prefix."app_clientes_tac on id_cliente = rutRecep
				where `TBK_ID_SESION` = '$TBK_ID_SESION' LIMIT 1";
		$resultTsc = $wpdb->get_row( $query );

		/*
		*esto se registra en EXITO
		*/
		$response = $wpdb->update( 
						$wpdb->prefix.'app_recarga_online',
						array( 'estado' => 'exito', 'id_transaccion_institucion' => $resultTsc->TBK_ID_TRANSACCION ),
						array( 'id_tabla_pago' => $TBK_ID_SESION ), 
						array( '%s', '%s' ),
						array( '%s' ) 
					);
		
		#Agrega tarjetas recargadas
		$resultTsc->html_tarjetas = getTarjetaRecarga($TBK_ID_SESION);

		return $resultTsc; /*HASTA ACA*/
	}else{
		return false;
	}//FIN if($registroWebPay){

}
function registroWebpayExito($TBK_ID_SESION){
	global $wpdb;
	
	/****************** CONFIGURAR AQUI *******************/ 
	$myPath	= KKC_ROOT.DS."cgi-bin".DS."validacionmac".DS."MAC01Normal$TBK_ID_SESION.txt"; 
	/****************** FIN CONFIGURACION *****************/
	
	//Rescate de los valores informados por transbank 
	$fic = @fopen($myPath, "r");
	if(!$fic) return false;
	$linea=fgets($fic); 
	fclose($fic);
	$detalle=explode("&", $linea);
	$TBK_ORDEN_COMPRA=explode("=",$detalle[0]);
	$TBK_TIPO_TRANSACCION=explode("=",$detalle[1]);
	$TBK_RESPUESTA=explode("=",$detalle[2]);
	$TBK_MONTO=explode("=",$detalle[3]);
	$TBK_CODIGO_AUTORIZACION=explode("=",$detalle[4]);
	$TBK_FINAL_NUMERO_TARJETA=explode("=",$detalle[5]);
	$TBK_FECHA_CONTABLE=explode("=",$detalle[6]);
	$TBK_FECHA_TRANSACCION=explode("=",$detalle[7]);
	$TBK_HORA_TRANSACCION=explode("=",$detalle[8]);
	$TBK_ID_TRANSACCION=explode("=",$detalle[10]);
	$TBK_TIPO_PAGO=explode("=",$detalle[11]);
	$TBK_NUMERO_CUOTAS=explode("=",$detalle[12]);
	$TBK_VCI=explode("=",$detalle[13]);
	$TBK_MAC=explode("=",$detalle[14]);
	$TBK_FECHA_CONTABLE[1]=substr($TBK_FECHA_CONTABLE[1],2,2)."-".substr($TBK_FECHA_CONTABLE[1],0,2);
	$TBK_FECHA_TRANSACCION[1]=substr($TBK_FECHA_TRANSACCION[1],2,2)."-".substr($TBK_FECHA_TRANSACCION[1],0,2);
	$TBK_HORA_TRANSACCION[1]=substr($TBK_HORA_TRANSACCION[1],0,2).":".substr($TBK_HORA_TRANSACCION[1],2,2).":".substr($TBK_HORA_TRANSACCION[1],4,2);
	
	$response = $wpdb->update( 
					$wpdb->prefix.'app_compra_online',
					array( 
						'fecha_pago' => date("Ymdhis"),
						'total' => $TBK_MONTO[1],
						'forma_pago' => 'webpayvvv',
						'TBK_ACCION' => '',
						'TBK_CODIGO_COMERCIO' => $TBK_CODIGO_COMERCIO[1],
						'TBK_CODIGO_COMERCIO_ENC' => $TBK_CODIGO_COMERCIO_ENC[1],
						'TBK_TIPO_TRANSACCION' => $TBK_TIPO_TRANSACCION[1],
						'TBK_RESPUESTA' => $TBK_RESPUESTA[1],
						'TBK_MONTO' => $TBK_MONTO[1],
						'TBK_CODIGO_AUTORIZACION' => $TBK_CODIGO_AUTORIZACION[1],
						'TBK_FINAL_NUMERO_TARJETA' => $TBK_FINAL_NUMERO_TARJETA[1],
						'TBK_FECHA_CONTABLE' => $TBK_FECHA_CONTABLE[1],
						'TBK_FECHA_TRANSACCION' => $TBK_FECHA_TRANSACCION[1],
						'TBK_FECHA_EXPIRACION' => $TBK_FECHA_EXPIRACION[1],
						'TBK_HORA_TRANSACCION' => $TBK_HORA_TRANSACCION[1],
						'TBK_ID_SESION' => $TBK_ID_SESION,
						'TBK_ID_TRANSACCION' => $TBK_ID_TRANSACCION[1],
						'TBK_TIPO_PAGO' => $TBK_TIPO_PAGO[1],
						'TBK_NUMERO_CUOTAS' => $TBK_NUMERO_CUOTAS[1],
						'TBK_TASA_INTERES_MAX' => $TBK_TASA_INTERES_MAX[1],
						'TBK_MONTO_CUOTA' => $TBK_MONTO_CUOTA[1],
						'TBK_VCI' => $TBK_VCI[1],
						'TBK_MAC' => $TBK_MAC[1],
						'estado' => 'APROBADO'
					), 
					array( 'TBK_ORDEN_COMPRA' => $TBK_ORDEN_COMPRA[1] ), 
					array( 
						'%s',
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
						'%d',
						'%s',
						'%s',
						'%s'
					),  
					array( '%s' ) 
				);
		return $response;
}
function folioCompraonline($id_recarga, $folio){
	global $wpdb;
	
	$response = $wpdb->update( 
					$wpdb->prefix.'app_recarga_online_detalle',
					array( 'folio' => $folio ),
					array( 'id' => $id ), 
					array( '%d' ),
					array( '%d' ) 
				);
	return $response;
}
function registrarRecarga($id, $cuenta, $fecha, $hora, $rut,$nrMontoFull, $banco){
	global $wpdb;
	
	$result = $wpdb->insert( 
					$wpdb->prefix.'app_recargas_x_activar',
					array( 
						'iudc' => $id,
						'rut' => $rut,
						'nro_cuenta' => $cuenta,
						'fecha' => $fecha,
						'hora' => $hora,
						'monto' => $nrMontoFull,
						'banco' => $banco
					), 
					array( 
						'%s',
						'%s',
						'%d',
						'%s',
						'%s',
						'%d',
						'%s'
					) 
				);

	return $result;
}
function datosClienteAcepta($rut,$cuenta){
	global $wpdb;
	
	$sacaCaracter = array("-",".");
	$rut = str_replace($sacaCaracter, "", $rut);				
	
	$sqlClientesTAC = 'SELECT razonSocialRecep, giroRecep, direccionRecep, comunaRecep, ciudadRecep, emailRecep  FROM '.$wpdb->prefix.'app_clientes_tac  WHERE rutRecep="'.trim($rut).'" AND nroCuentaRecep='.$cuenta;

	$results = $wpdb->get_row( $sqlClientesTAC );

	return $results;
}
function actualizaFolio($FolioMasUno){
	global $wpdb;
	
	$response = $wpdb->update( 
					$wpdb->prefix.'app_folio',
					array( 'actual' => $FolioMasUno ),
					array( 'id' => 1 ), 
					array( '%d' ),
					array( '%d' ) 
				);
	return $response;
}
/*
function registrosWebPay($TBK_ID_SESION ,$TPL){
	global $wpdb;

	$myPath	= KKC_ROOT.DS."cgi-bin".DS."validacionmac".DS."MAC01Normal$TBK_ID_SESION.txt"; 
	//Rescate de los valores informados por transbank 
	$fic = fopen($myPath, "r"); 
	$linea=fgets($fic); 
	fclose($fic);
	$detalle=explode("&", $linea);
	$TBK_ID_TRANSACCION=explode("=",$detalle[10]);

	//////////////////////
	//esto se registra en EXITO
	////////////
	$response = $wpdb->update( 
					$wpdb->prefix.'app_recarga_online',
					array( 'estado' => $TPL, 'id_transaccion_institucion' => $TBK_ID_TRANSACCION[1] ),
					array( 'id_tabla_pago' => $TBK_ID_SESION ), 
					array( '%s', '%s' ),
					array( '%s' ) 
				);

	
	$query = "Select id_cliente, razonSocialRecep from  ".$wpdb->prefix."app_compra_online 
		  left join ".$wpdb->prefix."app_clientes_tac on id_cliente = rutRecep
		   where `TBK_ID_SESION` = '$TBK_ID_SESION'   LIMIT 1
		  ";
	$results = $wpdb->get_row( $query );

	return $results;
}
*/
function getTarjetaRecarga($IDSESSION){
	global $wpdb;
	
	$query = "SELECT d.id as id_recarga,  d.tsc as ntsc, d.monto as monto_recarga, d.monto_total, r.rut_cliente as rut
		FROM ".$wpdb->prefix."app_recarga_online AS r
		LEFT JOIN ".$wpdb->prefix."app_recarga_online_detalle AS d ON d.id_recarga_online = r.id
		WHERE r.id_tabla_pago = '$IDSESSION' ;";  

	$registro = $wpdb->get_results( $query );

	$html ='';
	if (count($registro)>0) {			 
		$html .='<div class="acordiontarjeta" >
					<table  id="theTable" >
						<thead>
							<tr>
								<th  scope="col"><strong>N&deg;</strong></th>
								<th class="sortable-numeric"  scope="col"><strong>N&deg; TSC</strong></th>
								<th class="sortable-numeric"  scope="col"><strong>Monto Cargado</strong></th>                            
							</tr>
						</thead>
						<tbody>';
		$i=1;
		foreach ($registro as $recargas) {
			$html .='<tr >
						<td>'.$i++.'</td>
						<td >'.$recargas->ntsc.'</td>
						<td > $'.number_format($recargas->monto_recarga, 0 ,",", ".").'</td>
					</tr>';
		} //   recargas			
		$html .='
						</tbody>
					</table>
				</div>';
	}else{
		$html .='<div class="tsc7_tlin">No Existe el Nº de TSC </div>'; 
	}

	return $html;
}
?>