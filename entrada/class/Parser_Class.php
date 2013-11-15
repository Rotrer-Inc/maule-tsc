<?php
require_once("bd2.Class.php");
class Parser_Class
{
	//Atributos
	
	######################################################
	#Atributo obligatorio para manejar la base de datos
	var $sql;
	var $prefix = 'tsc_';
	######################################################
	
    //Atributos para archivo Usuario
			
	var $rutRecepU = ''; //9 digitos
    var $nroCuentaRecepU = 0; //5 digitos
    var $emailRecepU = ''; //50 digitos    
	var $razonSocialRecepU = ''; // 32 caracteres
	var $giroRecepU = ''; // 32 caracteres
	var $direccionRecepU = ''; // 50 caracteres
	var $direccionRecep2U = ''; // 50 caracteres
	var $direccionRecep3U = ''; // 50 caracteres
	var $prefijotelefonoU =''; // 4 caracteres
	var $fonoRecepU = ''; // 8 caracteres
	var $comunaRecepU = ''; // 50 caracteres
	var $ciudadRecepU = ''; // 32 caracteres
	var $ultimosaldoU = 0; ///6
	var $fechaU; /// YYMMDD
	var $horaU; /// HHMMSS 
	

				
	
    //Atributos para archivo recargas Efectuadas no informadas
    var $identificadorUnicoE = 0; // numCuenta + fecha + hora + monto
    var $montoE = 0; //6 digitos
    var $numCuentaE = 0; //5 digitos
    var $fechaE; //YYMMDD
    var $horaE; //HHMISS
    var $plazaE = 0; //2 digitos
    var $viaE = 0; // 2 digitos
	var $positivoE =0; /// 1 digito  (1:positivo 0:negativo)
	
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

    //Atributos para archivo transacciones 
    var $identificadorUnicoT = 0; // numCuenta + fecha + hora + monto
    var $montoT = 0; //6 digitos
    var $numCuentaT = 0; //5 digitos
    var $fechaT; //YYMMDD
    var $horaT; //HHMISS
    var $plazaT = 0; //2 digitos
    var $viaT = 0; // 2 digitos

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
			case 'T':
                $finalArchivo = $this->parseArchivoTransacciones($ruta, $archivo);
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
			/*
			    var $rutRecepU = ''; //9 digitos
    var $nroCuentaRecepU = 0; //5 digitos
    var $emailRecepU = ''; //50 digitos    
	var $razonSocialRecepU = ''; // 32 caracteres
	var $giroRecepU = ''; // 32 caracteres
	var $direccionRecepU = ''; // 50 caracteres
	var $direccionRecep2U = ''; // 50 caracteres
	var $direccionRecep3U = ''; // 50 caracteres
	var $prefijotelefonoU =''; // 4 caracteres
	var $fonoRecepU = ''; // 8 caracteres
	var $comunaRecepU = ''; // 50 caracteres
	var $ciudadRecepU = ''; // 32 caracteres
	var $ultimosaldoU = 0; ///6
	var $fechaU; /// YYMMDD
	var $horaU; /// HHMMSS 
	

			*/		
	                    $matrizLinea = str_split( fgets($fp) );//extrae linea a linea y llama a la clase Parser_Class para analizar
	                    $this->rutRecepU = $this->extraeCadena($matrizLinea, 0, 9);					//R[9]	rut
	                    $this->nroCuentaRecepU = intval($this->extraeCadena($matrizLinea, 10, 14));			//C[5]	cuenta
	                    $this->emailRecepU = trim ($this->extraeCadena($matrizLinea, 15, 64));				//M[50]	correo
	                    $this->razonSocialRecepU =trim ( $this->extraeCadena($matrizLinea,65 ,96 ));		//N[32]	nombre /razon
	                    $this->giroRecepU = trim ($this->extraeCadena($matrizLinea,97 ,128 ));			//G[32]	giro	
	                    $this->direccionRecepU =trim ( $this->extraeCadena($matrizLinea, 129,178 ));		//A1[50]direccion
	                    $this->direccionRecep2U =trim ( $this->extraeCadena($matrizLinea, 179,228 ));		//A2[50]direc 2 lin
	                    $this->direccionRecep3U =trim ( $this->extraeCadena($matrizLinea, 229,278 ));		//A3[50]dicerecion 3 l
	                    $this->prefijotelefonoU =trim ( $this->extraeCadena($matrizLinea, 279,282 ));		//P[4] prefijo
	                    $this->fonoRecepU =trim ( $this->extraeCadena($matrizLinea,283 , 290));			//F[8] fono
	                    $this->comunaRecepU =trim ( $this->extraeCadena($matrizLinea, 291,340 ));			//O[50] comuna
	                    $this->ciudadRecepU =trim ( $this->extraeCadena($matrizLinea, 341,372 ));			//T[32] ciudad
	                    $this->ultimosaldoU = intval($this->extraeCadena($matrizLinea, 373,378 ));			//Z[6]  ultimo saldo
	                    $this->fechaU = $this->extraeCadena($matrizLinea, 379,384 );				//F[6] YYMMDD
	                    $this->horaU = $this->extraeCadena($matrizLinea, 385,390 );					//H[6] HHMMSS	

						if ($this->ultimosaldoU =='') $this->ultimosaldoU=0; 
						//if ( $this->nroCuentaRecepU  =='')  $this->nroCuentaRecepU = 0;
	                    $re =  $this->datos4dbUsuarioTAC();
						//echo  $this->rutRecepU . $re .".<br>";
  
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
    
			/*********************FIN Lectura del Archivo*********************/
    function parseArchivoTransacciones($ruta, $archivo){
        $finalArchivo = false;
        if( strtolower( substr($archivo, (strlen($archivo)-4),4) ) == '.dat'){//verifica extension de archivo DAT
			/*********************Lectura del Archivo*********************/
			$fp = fopen($ruta.$archivo, "r");//abre el archivo modo lectura
			if($fp){
				do {//recorre el archivo
	                if(!feof($fp)){
	                    $matrizLinea = str_split( fgets($fp) );//extrae linea a linea y llama a la clase Parser_Class para analizar
	                    $this->identificadorUnicoT = $this->extraeCadena($matrizLinea, 0, 27);
	                    $this->numCuentaT = $this->extraeCadena($matrizLinea, 0, 5);
	                    $this->fechaT = $this->extraeCadena($matrizLinea, 6,  11);
	                    $this->horaT = $this->extraeCadena($matrizLinea, 12, 17);
	                    $this->plazaT = $this->extraeCadena($matrizLinea, 18, 19);
	                    $this->viaT = $this->extraeCadena($matrizLinea, 20, 21);                    
	                    $this->montoT = $this->extraeCadena($matrizLinea, 22, 27);
	                    $this->datos4dbTransaccion();
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
	
    function parseArchivoCargas($ruta, $archivo){
        $finalArchivo = false;
        if( strtolower( substr($archivo, (strlen($archivo)-4),4) ) == '.dat'){//verifica extension de archivo DAT
			/*********************Lectura del Archivo*********************/
			$fp = fopen($ruta.$archivo, "r");//abre el archivo modo lectura
			if($fp){
				do {//recorre el archivo
	                if(!feof($fp)){
	                    $matrizLinea = str_split( fgets($fp) );//extrae linea a linea y llama a la clase Parser_Class para analizar
	                    $this->identificadorUnicoE = $this->extraeCadena($matrizLinea, 0, 23);
	                    $this->numCuentaE = $this->extraeCadena($matrizLinea, 0, 5);
	                    $this->montoE = $this->extraeCadena($matrizLinea, 18, 23);
	                    $this->fechaE = $this->extraeCadena($matrizLinea, 24, 29);
	                    $this->horaE = $this->extraeCadena($matrizLinea, 30, 35);
	                    $this->plazaE = $this->extraeCadena($matrizLinea, 36, 37);
	                    $this->viaE = $this->extraeCadena($matrizLinea, 38, 39);   
						$this->positivoE = 1;//$this->extraeCadena($matrizLinea, 40, 40);   
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
        $sqlCliente = 'select rut from '.$this->prefix.'app_cliente where rut= "'.$this->rutU.'" and nro_cuenta= '.$this->numCuentaU;
        $existeCliente = $this->sql->get($sqlCliente);
        if(!$existeCliente){
            $table = $this->prefix.'app_cliente';
            $camps = 'rut, nro_cuenta, email';
            $vals = '"'.$this->rutU.'", "'.$this->numCuentaU.'", "'.$this->correoU.'"';
            $this->sql->sql_insert($table, $camps, $vals);
        }else{
            $table = $this->prefix.'app_cliente';
            $cambios = ' correoU="'.$this->correoU.'" ';
            $donde = 'rut="'.$this->rutU.'" and nro_cuenta='.$this->numCuentaU;
            $this->sql->sql_update($table, $cambios, $donde);
        }
    }
    
    function datos4dbRecarga(){
        $table = $this->prefix.'app_recarga';
        $camps = 'iudc, monto, fecha, hora, plaza, via, nro_cuenta, positivo';
        $vals = '"'.$this->identificadorUnicoE.'", '.$this->montoE.', "'.$this->fechaE.'", "'.$this->horaE.'", '.$this->plazaE.', '.$this->viaE.', '.$this->numCuentaE.', '.$this->positivoE;
        $this->sql->sql_insert($table, $camps, $vals);      
		
		$sqlactivar = 'select monto from '.$this->prefix.'app_regargas_x_activar where iudc = "'.$this->identificadorUnicoE.'" and nro_cuenta = '.$this->numCuentaE;

		$existeRecarga = $this->sql->get($sqlactivar);
		
		if( $existeRecarga ){
			$table = $this->prefix.'app_regargas_x_activar'; 
			$cambios= "activa = 1";           
			$donde = 'nro_cuenta='.$this->numCuentaE.' and iudc="'.$this->identificadorUnicoE.'"';
			$this->sql->sql_update($table, $cambios, $donde);
		}

    }
	
	 function datos4dbTransaccion(){
        $table = $this->prefix.'app_transaccion';
        $camps = 'iudc, monto, fecha, hora, plaza, via, nro_cuenta';
        $vals = '"'.$this->identificadorUnicoT.'", '.$this->montoT.', "'.$this->fechaT.'", "'.$this->horaT.'", '.$this->plazaT.', '.$this->viaT.', '.$this->numCuentaT;
        $this->sql->sql_insert($table, $camps, $vals);        
    }


//////////////////  para nuevo formato de archivo U ahora esta completo

 function datos4dbUsuarioTAC(){
		//busca registro de la persona
	///	echo "en usuario tac";
		$table = $this->prefix.'app_clientes_tac';
	
		$sqlCliente = 'select rutRecep from '.$table.' where rutRecep = "'.$this->rutRecepU.'" and nroCuentaRecep = '.$this->nroCuentaRecepU;
		//echo $sqlCliente;
		$existeCliente = $this->sql->get($sqlCliente);
		
		if(!$existeCliente ){
		//echo "insertando ";
			
			
			$camps = 'rutRecep, 
					  nroCuentaRecep, 
					  razonSocialRecep, 
					  emailRecep, 
					  giroRecep, 
					  direccionRecep, 
					  direccion2, 
					  direccion3, 
					  fonoRecep, 
					  comunaRecep, 
					  ciudadRecep, 
					  prefijotelefono, 
					  fechasaldo,
					  horasaldo,
					  saldo';
					  
					  
			$vals = '
					"'.$this->rutRecepU.'", 
					'.$this->nroCuentaRecepU.', 
					"'.$this->razonSocialRecepU.'", 
					"'.$this->emailRecepU.'", 
					"'.$this->giroRecepU.'",
					"'.$this->direccionRecepU.'",
					"'.$this->direccionRecep2U.'",
					"'.$this->direccionRecep3U.'",
					"'.$this->fonoRecepU.'",
					"'.$this->comunaRecepU.'",
					"'.$this->ciudadRecepU.'" ,
					"'.$this->prefijotelefonoU.'" , 
					"'.$this->fechaU.'" , 
					"'.$this->horaU.'" , 
					'.$this->ultimosaldoU.'	';
			$this->sql->sql_insert($table, $camps, $vals);
		//print (".<br>   - ". $table . " "  . $camps . " ". $vals);

		/*	if($this->sql_insert($table, $camps, $vals))
				echo $this->rutRecep." | ".$this->nroCuentaRecep." | ingreso <br>";
			else
				echo $this->rutRecep." | ".$this->nroCuentaRecep." | fallo ing <br>"; 
			*/	
		}elseif( !empty($this->rutRecepU) && !empty($this->nroCuentaRecepU) ){
			$coma = ' , ';
			//echo "update ";

			$cambios = '';
			if(!empty($this->razonSocialRecepU)){
				$cambios .= 'razonSocialRecep="'.$this->razonSocialRecepU.'"';
			}
			if(!empty($this->emailRecepU)){
				if(!empty($cambios)){
					$cambios .= $coma.'emailRecep="'.$this->emailRecepU.'"';
				}else{
					$cambios .= 'emailRecep="'.$this->emailRecepU.'"';
				}	
			}
			if(!empty($this->giroRecepU)){
				if(!empty($cambios)){
					$cambios .= $coma.'giroRecep="'.$this->giroRecepU.'"';
				}else{
					$cambios .= 'giroRecep="'.$this->giroRecepU.'"';
				}	
			}
			if(!empty($this->direccionRecepU)){
				if(!empty($cambios)){
					$cambios .= $coma.'direccionRecep="'.$this->direccionRecepU.'"';
				}else{
					$cambios .= 'direccionRecep="'.$this->direccionRecepU.'"';
				}	
			}
			if(!empty($this->direccionRecep2U)){
				if(!empty($cambios)){
					$cambios .= $coma.'direccion2="'.$this->direccionRecep2U.'"';
				}else{
					$cambios .= 'direccion2="'.$this->direccionRecep2U.'"';
				}	
			}
			if(!empty($this->direccionRecep3U)){
				if(!empty($cambios)){
					$cambios .= $coma.'direccion3="'.$this->direccionRecep3U.'"';
				}else{
					$cambios .= 'direccion3="'.$this->direccionRecep3U.'"';
				}	
			}
			
			if(!empty($this->fonoRecepU)){
				if(!empty($cambios)){
					$cambios .= $coma.'fonoRecep="'.$this->fonoRecepU.'"';
				}else{
					$cambios .= 'fonoRecep="'.$this->fonoRecepU.'"';
				}	
			}
			if(!empty($this->prefijotelefonoU)){
				if(!empty($cambios)){
					$cambios .= $coma.'	prefijotelefono="'.$this->prefijotelefonoU.'"';
				}else{
					$cambios .= 'prefijotelefono="'.$this->prefijotelefonoU.'"';
				}	
			}
			if(!empty($this->comunaRecepU)){
				if(!empty($cambios)){
					$cambios .= $coma.'comunaRecep="'.$this->comunaRecepU.'"';
				}else{
					$cambios .= 'comunaRecep="'.$this->comunaRecepU.'"';
				}	
			}
			if(!empty($this->ciudadRecepU)){
				if(!empty($cambios)){
					$cambios .= $coma.'ciudadRecep="'.$this->ciudadRecep.'"';
				}else{
					$cambios .= 'ciudadRecep="'.$this->ciudadRecep.'"';
				}	
			}
			
			if(!empty($this->ultimosaldoU)){
				if(!empty($cambios)){
					$cambios .= $coma.'saldo="'.$this->ultimosaldoU.'"';
				}else{
					$cambios .= 'saldo='.$this->ultimosaldoU.'';
				}	
			}
			
			if(!empty($this->fechaU)){
				if(!empty($cambios)){
					$cambios .= $coma.'fechasaldo="'.$this->fechaU.'"';
				}else{
					$cambios .= 'fechasaldo='.$this->fechaU.'';
				}	
			}
			
			if(!empty($this->horaU)){
				if(!empty($cambios)){
					$cambios .= $coma.'horasaldo="'.$this->horaU.'"';
				}else{
					$cambios .= 'horasaldo='.$this->horaU.'';
				}	
			}
						
			$donde = 'rutRecep="'.$this->rutRecepU.'" and nroCuentaRecep='.$this->nroCuentaRecepU;
			$this->sql->sql_update($table, $cambios, $donde);
			/*if($this->sql_update($table, $cambios, $donde))
				echo $this->rutRecep." | ".$this->nroCuentaRecep." | actualiza <br>";
			else
				echo $this->rutRecep." | ".$this->nroCuentaRecep." | fallo <br>";*/
		}
}


    function datos4dbClientesTAC(){
		//busca registro de la persona
		$sqlCliente = 'select rutRecep from '.$this->prefix.'app_clientes_tac where rutRecep = "'.$this->rutRecep.'" and nroCuentaRecep = '.$this->nroCuentaRecep;
		$existeCliente = $this->sql->get($sqlCliente);
		if(!$existeCliente && !empty($this->rutRecep) && !empty($this->nroCuentaRecep) ){
			$table = $this->prefix.'app_clientes_tac';
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
						
			$table = $this->prefix.'app_clientes_tac';
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