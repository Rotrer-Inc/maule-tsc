<?php
require_once("bd2.Class.php");
class Parser_Class
{
	//Atributos
	
	######################################################
	#Atributo obligatorio para manejar la base de datos
	var $sql;
	######################################################
	
    //Atributos para archivo Usuario
    var $rutU = ''; //9 digitos
    var $numCuentaU = 0; //5 digitos
    var $correoU = ''; //50 digitos    

    //Atributos para archivo recargas Efectuadas no informadas
    var $identificadorUnicoE = 0; // numCuenta + fecha + hora + monto
    var $montoE = 0; //6 digitos
    var $numCuentaE = 0; //5 digitos
    var $fechaE; //YYMMDD
    var $horaE; //HHMISS
    var $plazaE = 0; //2 digitos
    var $viaE = 0; // 2 digitos
	
	//Atributos para archivo de clientes TAC
	var $rutRecep = ''; // 10 caracteres
	var $nroCuentaRecep = 0; // 5 digitos
	var $razonSocialRecep = ''; // 100 caracteres
	var $emailRecep = ''; // 100 caracteres
	var $giroRecep = ''; // 100 caracteres
	var $direccionRecep = ''; // 200 caracteres
	var $fonoRecep = ''; // 30 caracteres
	var $comunaRecep = ''; // 40 caracteres
	var $ciudadRecep = ''; // 30 caracteres
	
	#metodo constructor
	function Parser_Class(){
		$this->sql = new bd();
		$this->sql -> conectar();		
	}
	
    //extrae los caracteres de la matriz segun posicion de inicio y fin
	function extraeCadena($matriz2Parse, $posIni, $posFin){
		$posIni = $posIni - 1;
		for($posIni; $posIni < $posFin; $posIni++){
			$retornaString.= $matriz2Parse[$posIni];
		}
		return $retornaString;
	}
	
	function leeArchivo($ruta, $archivo){
		$finalArchivo = false;
        $inicioArchivo = substr(strtoupper($archivo), 0, 1);
        switch($inicioArchivo){
            case 'U':
                $finalArchivo = $this->parseArchivoCliente($ruta, $archivo);
                break;
            case 'E':
                $finalArchivo = $this->parseArchivoCargas($ruta, $archivo);
                break;
			case 'D':
                $finalArchivo = $this->parseArchivoClientesTAC($ruta, $archivo);
                break;
        }
        return $finalArchivo;
	}
	
    function parseArchivoCliente($ruta, $archivo){ 
        $finalArchivo = false;
        if( strtolower( substr($archivo, (strlen($archivo)-4),4) ) == '.dat'){//verifica extension de archivo DAT
	        /*********************Lectura del Archivo*********************/
			$fp = fopen($ruta.$archivo, "r");//abre el archivo modo lectura
			if($fp){
				do {//recorre el archivo
	                if(!feof($fp)){               			
	                    $matrizLinea = str_split( fgets($fp) );//extrae linea a linea y llama a la clase Parser_Class para analizar
	                    $this->rutU = $this->extraeCadena($matrizLinea, 0, 9);
	                    $this->numCuentaU = $this->extraeCadena($matrizLinea, 10, 14);
	                    $this->correoU = $this->extraeCadena($matrizLinea, 15, 65);
	                    $this->datos4dbUsuario();
	                }                
				}while(!feof($fp));
				if(feof($fp)){
					$finalArchivo = true; 
				}
				fclose($fp);//cierra el archivo			
			}		
			/*********************FIN Lectura del Archivo*********************/
		}
        return $finalArchivo;
    }
    
    function parseArchivoCargas($ruta, $archivo){
        $finalArchivo = false;
        if( strtolower( substr($archivo, (strlen($archivo)-4),4) ) == '.dat'){//verifica extension de archivo DAT
			/*********************Lectura del Archivo*********************/
			$fp = fopen($ruta.$archivo, "r");//abre el archivo modo lectura
			if($fp){
				do {//recorre el archivo
	                if(!feof($fp)){
	                    $matrizLinea = str_split( fgets($fp) );//extrae linea a linea y llama a la clase Parser_Class para analizar
	                    $this->identificadorUnicoE = $this->extraeCadena($matrizLinea, 0, 23);  // numCuenta + fecha + hora + monto
	                    $this->numCuentaE = $this->extraeCadena($matrizLinea, 0, 5);   //5
	                    $this->montoE = $this->extraeCadena($matrizLinea, 18, 23);   // 6
	                    $this->fechaE = $this->extraeCadena($matrizLinea, 24, 29);//6
	                    $this->horaE = $this->extraeCadena($matrizLinea, 30, 35); ///6
	                    $this->plazaE = $this->extraeCadena($matrizLinea, 36, 37);  //2
	                    $this->viaE = $this->extraeCadena($matrizLinea, 38, 39);  // 2                  
	                    $this->datos4dbRecarga();
	                }
				}while(!feof($fp));
				if(feof($fp)){
					$finalArchivo = true; 
				}
				fclose($fp);//cierra el archivo
			}
		}
		/*********************FIN Lectura del Archivo*********************/
        return $finalArchivo;
    } 
	
	function parseArchivoClientesTAC($ruta, $archivo){
		$i = 0;
        $finalArchivo = false;
	    if( strtolower( substr($archivo, (strlen($archivo)-4),4) ) == '.csv'){//verifica extension de archivo CSV
		    /*********************Lectura del Archivo*********************/
			$fp = fopen($ruta.$archivo, "r");//abre el archivo modo lectura
			if($fp){
				do {//recorre el archivo
	                if(!feof($fp)){
	                    $linea = fgets($fp);//extrae linea a linea y llama a la clase Parser_Class para analizar
	                    $matrizLinea = explode(";", $linea);
	                    if( count($matrizLinea) == 9 ){
	                    	if( strtoupper($matrizLinea[0]) != "S/I" ){
	                    		$this->rutRecep = str_replace("-", "", $matrizLinea[0]);
	                    	}else{
	                   			$this->rutRecep = '';
	                    	}
	                    	if( strtoupper($matrizLinea[1]) != "S/I" ){
	                    		$this->nroCuentaRecep = $matrizLinea[1];
	                    	}else{
	                   			$this->nroCuentaRecep = '';
	                    	}
							if( strtoupper($matrizLinea[2]) != "S/I" ){
	                    		$this->razonSocialRecep = $matrizLinea[2];
	                    	}else{
	                   			$this->razonSocialRecep = '';
	                    	}
	                    	if( strtoupper($matrizLinea[3]) != "S/I" ){
	                    		$this->emailRecep = $matrizLinea[3];
	                    	}else{
	                   			$this->emailRecep = '';
	                    	}
	                    	if( strtoupper($matrizLinea[4]) != "S/I" ){
	                    		$this->giroRecep = $matrizLinea[4];
	                    	}else{
	                   			$this->giroRecep = '';
	                    	}
	                    	if( strtoupper($matrizLinea[5]) != "S/I" ){
	                    		$this->direccionRecep = $matrizLinea[5];
	                    	}else{
	                   			$this->direccionRecep = '';
	                    	}
	                    	if( strtoupper($matrizLinea[6]) != "S/I" ){
	                    		$this->fonoRecep = $matrizLinea[6];
	                    	}else{
	                   			$this->fonoRecep = '';
	                    	}
	                    	if( strtoupper($matrizLinea[7]) != "S/I" ){
	                    		$this->comunaRecep = $matrizLinea[7];
	                    	}else{
	                   			$this->comunaRecep = '';
	                    	}
	                    	if( strtoupper($matrizLinea[8]) != "S/I" ){
	                    		$this->ciudadRecep = $matrizLinea[8];
	                    	}else{
	                   			$this->ciudadRecep = '';
	                    	}
							$this->datos4dbClientesTAC();
						}                                                        
	                }
				}while(!feof($fp));
				if(feof($fp)){					
					$finalArchivo = true; 
				}
				fclose($fp);//cierra el archivo
			}
			/*********************FIN Lectura del Archivo*********************/
		}
        return $finalArchivo;
    }   
	
	function datos4dbUsuario(){
        //busca registro de la persona
        $sqlCliente = 'select rut from cliente where rut= "'.$this->rutU.'" and nro_cuenta= '.$this->numCuentaU;
        $existeCliente = $this->sql->get($sqlCliente);
        if(!$existeCliente){
            $table = "cliente";
            $camps = 'rut, nro_cuenta, email';
            $vals = '"'.$this->rutU.'", "'.$this->numCuentaU.'", "'.$this->correoU.'"';
            $this->sql->sql_insert($table, $camps, $vals);
        }else{
            $table = "cliente";
            $cambios = ' correoU="'.$this->correoU.'" ';
            $donde = 'rut="'.$this->rutU.'" and nro_cuenta='.$this->numCuentaU;
            $this->sql->sql_update($table, $cambios, $donde);
        }
    }
    
    function datos4dbRecarga(){
        $table = "recarga";
        $camps = 'iudc, monto, fecha, hora, plaza, via, nro_cuenta';
        $vals = '"'.$this->identificadorUnicoE.'", '.$this->montoE.', "'.$this->fechaE.'", "'.$this->horaE.'", '.$this->plazaE.', '.$this->viaE.', '.$this->numCuentaE;
        $this->sql->sql_insert($table, $camps, $vals);        
    }
    
    function datos4dbClientesTAC(){
		//busca registro de la persona
		$sqlCliente = 'select rutRecep from clientes_tac where rutRecep = "'.$this->rutRecep.'" and nroCuentaRecep = '.$this->nroCuentaRecep;
		$existeCliente = $this->sql->get($sqlCliente);
		if(!$existeCliente && !empty($this->rutRecep) && !empty($this->nroCuentaRecep) ){
			$table = "clientes_tac";
			$camps = 'rutRecep, 
					  nroCuentaRecep, 
					  razonSocialRecep, 
					  emailRecep, 
					  giroRecep, 
					  direccionRecep, 
					  fonoRecep, 
					  comunaRecep, 
					  ciudadRecep';
			$vals = '
					"'.$this->rutRecep.'", 
					'.$this->nroCuentaRecep.', 
					"'.$this->razonSocialRecep.'", 
					"'.$this->emailRecep.'", 
					"'.$this->giroRecep.'",
					"'.$this->direccionRecep.'",
					"'.$this->fonoRecep.'",
					"'.$this->comunaRecep.'",
					"'.$this->ciudadRecep.'"					
					';
			$this->sql->sql_insert($table, $camps, $vals);
			/*if($this->sql_insert($table, $camps, $vals))
				echo $this->rutRecep." | ".$this->nroCuentaRecep." | ingreso <br>";
			else
				echo $this->rutRecep." | ".$this->nroCuentaRecep." | fallo ing <br>";*/ 
		}elseif( !empty($this->rutRecep) && !empty($this->nroCuentaRecep) ){
			$coma = ' , ';
			$cambios = '';
			if(!empty($this->razonSocialRecep)){
				$cambios .= 'razonSocialRecep="'.$this->razonSocialRecep.'"';
			}
			if(!empty($this->emailRecep)){
				if(!empty($cambios)){
					$cambios .= $coma.'emailRecep="'.$this->emailRecep.'"';
				}else{
					$cambios .= 'emailRecep="'.$this->emailRecep.'"';
				}	
			}
			if(!empty($this->giroRecep)){
				if(!empty($cambios)){
					$cambios .= $coma.'giroRecep="'.$this->giroRecep.'"';
				}else{
					$cambios .= 'giroRecep="'.$this->giroRecep.'"';
				}	
			}
			if(!empty($this->direccionRecep)){
				if(!empty($cambios)){
					$cambios .= $coma.'direccionRecep="'.$this->direccionRecep.'"';
				}else{
					$cambios .= 'direccionRecep="'.$this->direccionRecep.'"';
				}	
			}
			if(!empty($this->fonoRecep)){
				if(!empty($cambios)){
					$cambios .= $coma.'fonoRecep="'.$this->fonoRecep.'"';
				}else{
					$cambios .= 'fonoRecep="'.$this->fonoRecep.'"';
				}	
			}
			if(!empty($this->comunaRecep)){
				if(!empty($cambios)){
					$cambios .= $coma.'comunaRecep="'.$this->comunaRecep.'"';
				}else{
					$cambios .= 'comunaRecep="'.$this->comunaRecep.'"';
				}	
			}
			if(!empty($this->ciudadRecep)){
				if(!empty($cambios)){
					$cambios .= $coma.'ciudadRecep="'.$this->ciudadRecep.'"';
				}else{
					$cambios .= 'ciudadRecep="'.$this->ciudadRecep.'"';
				}	
			}
						
			$table = "clientes_tac";            
			$donde = 'rutRecep="'.$this->rutRecep.'" and nroCuentaRecep='.$this->nroCuentaRecep;
			$this->sql->sql_update($table, $cambios, $donde);
			/*if($this->sql_update($table, $cambios, $donde))
				echo $this->rutRecep." | ".$this->nroCuentaRecep." | actualiza <br>";
			else
				echo $this->rutRecep." | ".$this->nroCuentaRecep." | fallo <br>";*/
		}
    }
}

?>