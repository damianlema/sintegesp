<?php

class Conexion extends mysqli {

	private static $db_host = 'localhost';
	private static $db_user = 'root';
	private static $db_pass = '';
	private static $db_name = 'gestion_mariguitar_2017abr2017';

	public function __construct(){
		parent::__construct(self::$db_host,self::$db_user,self::$db_pass,self::$db_name);
		$this->query("SET NAMES utf8;");
		$this->connect_errno ? die('ERROR: conexion a BD fallida') : null;
	}

	public function rows($x){
		return mysqli_num_rows($x);
	}

	public function recorrer($x){
		return mysqli_fetch_array($x);
	}

	public function liberar($x){
		return mysqli_free_result($x);
	}

}
?>