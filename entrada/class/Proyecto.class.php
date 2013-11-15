<?php
require_once("bd2.Class.php");
class Proyectos {
	//Atributos
	
	######################################################
	#Atributo obligatorio para manejar la base de datos
	var $sql;
	######################################################
	
	#metodo constructor
	public function Proyectos(){
		$this->sql = new bd();
		$this->sql -> conectar();		
	}
	
	public function fechaEsp2Eng($fecha){
		list( $dia, $mes, $ano ) = split( '[/.-]', trim($fecha) );
		return "$ano-$mes-$dia";
	}
	
			public function registrarBcoEstado($rut,$tsc, $id_sesion){
		
		$tabla="pago_bcoestado";
		$campos=" rut_cliente , n_tsc, fecha_recarga , ID_SESSION";
		$valores=" '$rut', '$tsc', NOW() , '$id_sesion'";
		
		if ($this->sql->sql_insert($tabla, $campos, $valores)){				
			$result=1;
		}else {
			$result=0;			
		}
			
		return $result;
	
	
		}	

	public function registrarRecarga($id, $cuenta, $fecha, $hora, $rut,$nrMontoFull, $banco){
		$tabla="recargas_x_activar";
		$campos=" iudc, rut, nro_cuenta, fecha, hora, monto, banco ";
		$valores=" '$id', '$rut', $cuenta, '$fecha', '$hora',$nrMontoFull , '$banco'";
		//echo $tabla, $campos, $valores;
		if ($this->sql->sql_insert($tabla, $campos, $valores)){				
			$result=1;
		}else {
			$result=0;			
		}
		return $result;
	}


	public function registrarBcoChile_IDTRX($rut,$tsc){
		
		$tabla="pago_bcochile";
		$campos=" rut_cliente , n_tsc, fecha_recarga ";
		$valores=" '$rut', '$tsc', NOW()";
		
		if ($this->sql->sql_insert($tabla, $campos, $valores)){				
			$result=1;
		}else {
			$result=0;			
		}
		
		
		$sql_ULTIMO = "Select max(id) as maximo  from pago_bcochile";
		$res =$this->sql->get($sql_ULTIMO);
		$trx = $res[0]['maximo'] ;
		return $trx;
	
	
		}	
		
		
		
	
	public function folioCompraonline($id, $folio ){		
	
		$table = 'recarga_online_detalle';
		$campos = " `folio` = '$folio' ";
        $donde = "id = $id ";
        if ($this->sql->sql_update($table , $campos, $donde)){
			$result=1;
		}else {
			$result=0;
		}
		return $result;
	}
	
	public function registrarWebPay($total , $TBK_ORDEN_COMPRA, $rut, $cuenta){
		$tabla="compra_online";
		$campos=" total , TBK_ORDEN_COMPRA, fecha_cotizacion, id_cliente, cuenta_cliente ";
		$valores=" '$total', '$TBK_ORDEN_COMPRA', now(), '$rut', '$cuenta' ";
		if ($this->sql->sql_insert($tabla, $campos, $valores)){				
			$result=1;
		}else {
			$result=0;			
		}
		return $result;
	}

    public function modificaRegWebPay($TBK_MONTO, $TBK_ACCION, $TBK_CODIGO_COMERCIO, $TBK_CODIGO_COMERCIO_ENC,
                                      $TBK_TIPO_TRANSACCION, $TBK_RESPUESTA, $TBK_MONTO, $TBK_CODIGO_AUTORIZACION,
                                      $TBK_FINAL_NUMERO_TARJETA, $TBK_FECHA_CONTABLE, $TBK_FECHA_TRANSACCION,
                                      $TBK_FECHA_EXPIRACION, $TBK_HORA_TRANSACCION, $TBK_ID_SESION, $TBK_ID_TRANSACCION,
                                      $TBK_TIPO_PAGO, $TBK_NUMERO_CUOTAS, $TBK_TASA_INTERES_MAX, $TBK_MONTO_CUOTA,
                                      $TBK_VCI, $TBK_MAC, $TBK_ORDEN_COMPRA){
                                      
       
	   $TBK_CAMPOS =     "`fecha_pago` = now(),
                 `total` = $TBK_MONTO,
                 `forma_pago` = 'webpayvvv',
                 `TBK_ACCION` = '$TBK_ACCION',
                 `TBK_CODIGO_COMERCIO` = $TBK_CODIGO_COMERCIO ,
                 `TBK_CODIGO_COMERCIO_ENC` = '$TBK_CODIGO_COMERCIO_ENC',
                 `TBK_TIPO_TRANSACCION` = '$TBK_TIPO_TRANSACCION',
                 `TBK_RESPUESTA` = $TBK_RESPUESTA ,
                 `TBK_MONTO` = $TBK_MONTO ,
                 `TBK_CODIGO_AUTORIZACION` = $TBK_CODIGO_AUTORIZACION ,
                 `TBK_FINAL_NUMERO_TARJETA` = $TBK_FINAL_NUMERO_TARJETA ,
                 `TBK_FECHA_CONTABLE` = $TBK_FECHA_CONTABLE ,
                 `TBK_FECHA_TRANSACCION`  = '$TBK_FECHA_TRANSACCION' ,
                 `TBK_FECHA_EXPIRACION` = $TBK_FECHA_EXPIRACION ,
                 `TBK_HORA_TRANSACCION` = $TBK_HORA_TRANSACCION ,
                 `TBK_ID_SESION` = '$TBK_ID_SESION',
                 `TBK_ID_TRANSACCION` = $TBK_ID_TRANSACCION ,
                 `TBK_TIPO_PAGO` = '$TBK_TIPO_PAGO' ,
                 `TBK_NUMERO_CUOTAS` = $TBK_NUMERO_CUOTAS ,
                 `TBK_TASA_INTERES_MAX` = $TBK_TASA_INTERES_MAX ,
                 `TBK_MONTO_CUOTA` = $TBK_MONTO_CUOTA,
                 `TBK_VCI` = '$TBK_VCI',
                 `TBK_MAC` = '$TBK_MAC',
				 `estado` = 'APROBADO' ";

        $TBK_donde = "TBK_ORDEN_COMPRA = '$TBK_ORDEN_COMPRA'";

        $TBK_tabla = 'compra_online';

        if ($this->sql->sql_update($TBK_tabla , $TBK_CAMPOS, $TBK_donde)){
			$result=1;
		}else {
			$result=0;
		}
		return $result;
    }
	
	/*
	 #Metodo desechado por requerimientos 08-2009, no se debe mostrar saldo en sitio web
	 #$objWebpay->actualizaSaldoCliente($_SESSION['Adm']['rut'],$_SESSION['Adm']['cuenta'], $TBK_MONTO); (exito.php)
	 public function actualizaSaldoCliente($rut,$cuenta, $monto){
		#Obtiene saldo actual de la cuenta
		$sql = "SELECT saldo FROM cliente  WHERE rut='".trim($rut)."' AND nro_cuenta=".$cuenta;
		$resultSaldo = $this->get($sql);
		#Sumamos saldo actual más monto nuevo
		$saldoFinal = $resultSaldo[0]['saldo']+$monto;
		#Actualiza el saldo del cliente
		$table = 'cliente';
		$campos = " `saldo` = $saldoFinal, fecha = CURDATE(), hora = CURTIME() ";
        $donde = "rut = '$rut' AND nro_cuenta = $cuenta ";
        if ($this->sql_update($table , $campos, $donde)){
			$result=1;
		}else {
			$result=0;
		}
		return $result;
	}*/
	
	public function actualizaMailCliente($rut,$cuenta,$mail){		
		#Actualiza el mail del cliente
		$table = 'cliente';
		$campos = " `email` = '$mail' ";
        $donde = "rut = '$rut' AND nro_cuenta = $cuenta ";
        if ($this->sql->sql_update($table , $campos, $donde)){
			$result=1;
		}else {
			$result=0;
		}
		return $result;
	}
	
	public function actualizaFolio($FolioMasUno){		
		$table = 'folio';
		$campos = " `actual` = '$FolioMasUno' ";
        $donde = "id = 1 ";
        if ($this->sql->sql_update($table , $campos, $donde)){
			$result=1;
		}else {
			$result=0;
		}
		return $result;
	}
	
	public function datosClienteAcepta($rut,$cuenta){
		$sacaCaracter = array("-",".");
		$rut = str_replace($sacaCaracter, "", $rut);				
		$sqlClientesTAC = 'SELECT razonSocialRecep, giroRecep, direccionRecep, comunaRecep, ciudadRecep, emailRecep  FROM clientes_tac  WHERE rutRecep="'.trim($rut).'" AND nroCuentaRecep='.$cuenta;
		$result = $this->sql->get4Extract($sqlClientesTAC);
		return $result;
	}
}
?>