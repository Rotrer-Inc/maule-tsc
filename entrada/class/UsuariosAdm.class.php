<?php
session_start();
require_once("bd2.Class.php");
class Usuarios {
	//Atributos
	
	######################################################
	#Atributo obligatorio para manejar la base de datos
	var $sql;
	######################################################
	
	#metodo constructor
	function Usuarios(){
		$this->sql = new bd();
		$this->sql -> conectar();		
	}
	//chequea el usuario y lo logea
	function checkUser($rut,$cuenta) {
		$sacaCaracter = array("-",".");
		$rut = str_replace($sacaCaracter, "", $rut);
		if(strlen($rut)<=8)
			$rut = "0".$rut;
		$sql = "SELECT * FROM cliente WHERE rut='".trim($rut)."' AND nro_cuenta=".$cuenta;
		$result = $this->sql->get($sql);
		$sqlTac = "SELECT * FROM clientes_tac WHERE rutRecep='".trim($rut)."' AND nroCuentaRecep=".$cuenta;
		$resultTac = $this->sql->get($sqlTac);
		if (count($result)>0 && count($resultTac)>0 ) {
		#if (count($result)>0 ) {
			session_start();
			$_SESSION['Adm']['login'] = true;
			$_SESSION['Adm']['rut'] = $rut;					
			$_SESSION['Adm']['cuenta'] = $cuenta;
			$_SESSION['Adm']['generaSalida'] = true;
			return 1;
		} else {
			echo "<SCRIPT>alert(\"RUT o Nro. Cuenta erroneas, favor revise bien sus datos.\");</SCRIPT>";
			$_SESSION['Adm']['login'] = false;
			return 0;
		}
	
	}
	
	function datosCliente($rut,$cuenta, $tac = false){
		$sacaCaracter = array("-",".");
		$rut = str_replace($sacaCaracter, "", $rut);
		if(strlen($rut)<=8)
			$rut = "0".$rut;
		if(!$tac){
			$sqlClientesTAC = 'SELECT rutRecep as rut, nroCuentaRecep as nro_cuenta, emailRecep as email FROM clientes_tac  WHERE rutRecep="'.trim($rut).'" AND nroCuentaRecep='.$cuenta;
			$resClientesTAC = $this->sql->get($sqlClientesTAC);
			//Si el cliente posee email en la base de datos de AM retorna registro de AM, sino retorna registro de CS
			if(!empty($resClientesTAC[0]['email'])){
				$result = $this->sql->get4Extract($sqlClientesTAC);
			}else{
				$sqlCliente = 'SELECT rut, nro_cuenta, email FROM cliente  WHERE rut="'.trim($rut).'" AND nro_cuenta='.$cuenta;
				$result = $this->sql->get4Extract($sqlCliente);
			}
		}else{
			$sqlClientesTAC = 'SELECT rutRecep as rut, nroCuentaRecep as nro_cuenta, emailRecep as email, razonSocialRecep, giroRecep, direccionRecep, fonoRecep, comunaRecep, ciudadRecep FROM clientes_tac  WHERE rutRecep="'.trim($rut).'" AND nroCuentaRecep='.$cuenta;
			$result = $this->sql->get4Extract($sqlClientesTAC);
		}
		return $result;
	}
	
	function historialCliente($cuenta){
		$sql = 'SELECT monto, DATE_FORMAT(fecha, "%d-%m-%Y") AS fecha, hora, plaza, via, nro_cuenta FROM recarga  WHERE nro_cuenta='.$cuenta.' order by fecha ASC';
		$result = $this->sql->get($sql);
		return $result;
	}
	
	function actualizaDatosCliente($post){
		extract($post);
		$table = "clientes_tac";
		$cambios = ' razonSocialRecep="'.strtoupper($nombre).'",
					 emailRecep="'.$email.'",
					 giroRecep="'.strtoupper($giro).'",
					 direccionRecep="'.strtoupper($direccion).'",
					 fonoRecep="'.$telefono.'",
					 ciudadRecep="'.strtoupper($ciudad).'",
					 comunaRecep="'.strtoupper($comuna).'"
				   ';
		$donde = 'rutRecep="'.$rut.'" and nroCuentaRecep='.$nro_cuenta;
		$result = $this->sql->sql_update($table, $cambios, $donde);
		return $result;
	}
}
?>