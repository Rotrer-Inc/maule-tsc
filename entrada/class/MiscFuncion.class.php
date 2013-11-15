<?php
require_once("bd2.Class.php");
class MiscFuncion{
	//Atributos
	
	######################################################
	#Atributo obligatorio para manejar la base de datos
	var $sql;
	######################################################
	
	#metodo constructor
	function MiscFuncion(){
		$this->sql = new bd();
		$this->sql -> conectar();		
	}
	
	function cargar_combo($tabla, $campo_texto, $campo_valor, $selec){
		$sql = "select * from $tabla order by $campo_texto asc";
		$res = $this->sql->get($sql);
		if ( count($res) > 0 ){			
			$op='<option value="">Seleccione</option>';			
			foreach($res as $arrCombo){
				$seleccionado ='';
				$valor = $arrCombo[$campo_valor];
				$texto = $arrCombo[$campo_texto];
				if ($valor == $selec){
					$seleccionado='selected="selected"';
				}
				$op.= '<option value="'.$valor.'" '.$seleccionado.' >'.$texto.'</option>';
			} 
			return $op;
		}else {
			return false;
		}
	}
	
	function formatoNumero($numero){
		$numero = ceil($numero);
		$num_formateado = "$".number_format($numero,0,'','.');
		return $num_formateado;
	}
	//metodo para separador de miles no monetario
	function formatoNumeroNoPesos($numero){
		$numero = ceil($numero);
		$num_formateado = number_format($numero,0,'','.');
		return $num_formateado;
	}
}
?>