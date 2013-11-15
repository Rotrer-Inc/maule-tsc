<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
set_time_limit (6000);
require_once("class/config.php");
require_once("class/Parser_Class.php");
//Creamos el objeto
$parserObj = new Parser_Class();

//variables de manejo
$creaDirectorio = false; 
$archivosAmover = array();
$i = 0;
$dir = $conf['serverRoot'].$conf['we'];
	//Abre el directorio
	if (is_dir($dir)) {//verifica si es directorio	
		$gd = opendir($dir);
        if ($gd) {//evalua y abre directorio        
			while (($archivo = readdir($gd)) !== false) {//recorre el directorio y extrae los "archivos"                
                if($archivo != '..' && $archivo !='.' && $archivo !=''){//excluye los manejos de directorio "." y ".."                    
                    $respValue = $parserObj->leeArchivo($dir, $archivo);
					if($respValue){					   
						if(unlink($dir.$archivo)){
							echo "Archivo procesado: ".$archivo."<br>";
						}
                    }
				}
			}
			closedir($gd);//cierra el directorio
		}
	}
$parserObj->sql->close();
?>