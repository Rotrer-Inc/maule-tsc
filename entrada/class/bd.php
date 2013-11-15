<?php
//Clase de base de datos.
// versi�n 1.1
// ********* Modificaciones ************
// Version 1.1
// Modificaciones realizadas en la construcci�n de las consultas
// Version 1.2
// Incluye metodos: numeroCampos, nombreCampos, count2(cantidad de registros por consulta), close, metodo constructor

class bd
{
	var $host;
	var $usuario;
	var $password;
	var $db;
	var $enlace;
	
	//metodo constructor
	function bd($conf = "")
	{
		if(empty($conf))
			global $conf;
		$this->host=$conf['host'];		
		$this->usuario=$conf['user'];
		$this->password=$conf['passwd'];		
		$this->db=$conf['database'];
		
		$this->enlace=$enlace;
	}
	function conectar(){
				if (!$this->enlace=@mysql_connect($this->host,$this->usuario,$this->password,$this->db))
				{
					die('<h1>Error conectando con la base de datos: ' . $this->host . "</h1>");
				}
				
				if (!@mysql_select_db($this->db,$this->enlace))
				{
					die('<h1>Error seleccionando la base de datos: ' . $this->db . '</h1>');
				}
				
				//$conn = mysql_connect($host, $user, $pass) or die("Error en la conexi�n");
				//mysql_select_db($base) or die("DB Error");
				//return $conn;
				return $this->enlace;
		}

	function getLink(){
			$link = $this->conectar();
			return $link;
		}

	function execute($sql){
			$link = $this->getLink();
			if ($result = mysql_query($sql)){
				return 1;
			} else {
				return 0;
			}
		}

	function execute2($sql){
			$link = $this->getLink();
			if ($result = mysql_query($sql)){
				return $result;
			} else {
				return 0;
			}
		}
	
	function execute3($sql){
			$link = $this->getLink();
			if ($result = mysql_query($sql)){
				return $result;
			} else {
				return 0;
			}
		}

	function get($sql){
			$link = $this->getLink();
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
			$link = $this->getLink();
			$result2 = $this->execute2($sql);
			$this->close();
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
			$link = $this->getLink();
			if ($result = mysql_query($sql)){
				$nro_campos = mysql_num_fields( $result );
				return $nro_campos;
			} else {
				return 0;
			}
		}
		
	function nombreCampos($sql, $pos){
			$link = $this->getLink();
			if ($result = mysql_query($sql)){
				$nombre_campos = mysql_field_name( $result , $pos );
				return $nombre_campos;
			} else {
				return 0;
			}
		}
	function count2($sql){
			$link = $this->getLink();
			if ($resQuery = $this->execute3($sql)) {
				if ($result = mysql_num_rows($resQuery)){
					return $result;
				} else {
					return 0;
				}	
			} else {
				return 0;
			}			
		}
	
	function close(){
			if(mysql_close($this->enlace)){
				return 1;
			} else {
				return 0;
			}
		}
}
?>