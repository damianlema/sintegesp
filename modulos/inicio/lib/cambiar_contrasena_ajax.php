<?
session_start();
include("../../../conf/conex.php");
conectarse();
//Conectarse();
extract($_POST);

if($ejecutar == "cambiarClave"){
	$sql_validar = mysql_query("select * from usuarios where login = '".$login."' and clave = '".md5($clave_actual)."'")or die(mysql_error());
	$num_validar = mysql_num_rows($sql_validar);
	
	if($num_validar > 0){
	//echo "update usuarios set clave = '".md5($nueva_clave)."' where login  = '".$login."' and clave = '".md5($nueva_clave)."'";
		$sql_actualizar = mysql_query("update usuarios set clave = '".md5($nueva_clave)."' where login  = '".$login."' and clave = '".md5($clave_actual)."'")or die(mysql_error());
		if($sql_actualizar){
			echo "exito";
		}
	}else{
		echo "clave_incorrecta";
	}
}
?>