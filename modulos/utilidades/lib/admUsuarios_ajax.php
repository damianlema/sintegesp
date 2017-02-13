<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();

extract($_POST);

if($ejecutar == "eliminarUsuario"){
	$sql_eliminar = mysql_query("update usuarios set status = 'e' where cedula='".$cedula."'")or die(mysql_error());
	registra_transaccion('Eliminar Usuarios ('.$cedula.')',$login,$fh,$pc,'usuarios',$conexion_db);
	echo "exito";
}


if($ejecutar == "activarUsuario"){
	$sql_eliminar = mysql_query("update usuarios set status = 'a' where cedula='".$cedula."'");
	registra_transaccion('Activar Usuarios ('.$cedula.')',$login,$fh,$pc,'usuarios',$conexion_db);
	echo "exito";
}










// se verifica si vienen datos por el POST


?>