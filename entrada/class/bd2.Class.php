<?php
//Clase de base de datos.
// versi�n 1.1
// ********* Modificaciones ************
// Version 1.1
// Modificaciones realizadas en la construcci�n de las consultas
// Version 1.2
// Incluye metodos: numeroCampos, nombreCampos, count2(cantidad de registros por consulta), close, metodo constructor
// Version 1.3
//Modificado metodos de conexion para evitar errores por sobre conexion
//Metodo getLink() removido, la clase que hereda a la clase bd es quien conecta la bd en su metodo constructor
/*
function getLink(){			
			$link = $this->conectar();
			return $link;
		}
*/


class bd
{
	var $host;
	var $usuario;
	var $password;
	var $db;
	var $enlace;
	
	//metodo constructor
	function bd()
	{
		global $conf;
		$this->host=$conf['host'];		
		$this->usuario=$conf['user'];
		$this->password=$conf['passwd'];		
		$this->db=$conf['database'];		
		$this->enlace=$enlace;
	}
	function conectar(){
				if (!$this->enlace=mysql_connect($this->host,$this->usuario,$this->password,$this->db))
				{
					die('<h1>Error conectando con la base de datos: ' . $this->host . "</h1>");
				}
				
				if (!@mysql_select_db($this->db,$this->enlace))
				{
					die('<h1>Error seleccionando la base de datos: ' . $this->db . '</h1>');
				}				
		}	

	function execute($sql){
			$result = mysql_query($sql);			
			if ($result){
				return 1;
			} else {
				return 0;
			}
		}

	function execute2($sql){
			$result = mysql_query($sql);			
			if ($result){
				return $result;
			} else {
				return 0;
			}
		}

	function get($sql){
			$result = $this->execute2($sql);			
			$ar = array();
			if ($result!=0){
				while ($fila = mysql_fetch_assoc($result)) {
				   array_push($ar,$fila);
			   	}
			}
			return $ar;
		}
	
	function get4Extract($sql){
			$result2 = $this->execute2($sql);
			if ($result2)				
				return @mysql_fetch_array($result2); 
			else 
				return false;

		}
		
	function sql_insert($table, $camps, $vals){
			$sql = "INSERT INTO 
						".$table." (".$camps.")
						 VALUES (".$vals.")";
			$result = $this->execute($sql);
			
			return $result;
		}

	function sql_update($table, $cambios, $donde){
			$sql="UPDATE ".$table."
					SET ".$cambios."
					WHERE ".$donde.";";
			$result = $this->execute($sql);
			
			return $result;
		}

	function sql_del($tabla, $donde){
			$sql="DELETE FROM 
					".$tabla." WHERE 
					".$donde."";
			$result = $this->execute($sql);
			
			return $result;
		}
	
	function numeroCampos($sql){
			$result = mysql_query($sql);
			if ($result){
				$nro_campos = mysql_num_fields( $result );
				mysql_free_result($result);
				
				return $nro_campos;
			} else {
				mysql_free_result($result);				
				return 0;
			}
		}
		
	function nombreCampos($sql, $pos){
			$result = mysql_query($sql);
			if ($result){
				$nombre_campos = mysql_field_name( $result , $pos );
				mysql_free_result($result);
				
				return $nombre_campos;
			} else {
				mysql_free_result($result);
				
				return 0;
			}
		}
	function count2($sql){			
			$resQuery = $this->execute2($sql);
			if ($resQuery) {
				if ($result = mysql_num_rows($resQuery)){
					mysql_free_result($resQuery);
					
					return $result;
				} else {
					mysql_free_result($resQuery);
					
					return 0;
				}	
			} else {
				return 0;
			}			
		}
	
	function close(){
			@mysql_close($this->enlace);				
		}
}
?>