<?
session_start();
include("../../../conf/conex.php");
$conexion = Conectarse();
extract($_POST);


 
if($ejecutar == "eliminar_retencion"){
	$sql_eliminar = mysql_query("delete from relacion_retenciones where idretenciones = '".$idretenciones."'")or die(mysql_error());
	$sql_eliminar2 = mysql_query("delete from retenciones where idretenciones = '".$idretenciones."'")or die(mysql_error());
	
}

