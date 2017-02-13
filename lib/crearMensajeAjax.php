<?php
//Establece la conexion con la base de datos
include ("../conf/conex.php");
$conex=conectarse();

//Insercion del mensaje
if($consulta=mysql_query("INSERT INTO microbloging (id_usuario, mensaje, fecha, hora, id_propietario)
							  VALUES('".$_SESSION['cedula_usuario']."', '".$_POST['mensaje']."','".date("Y-m-d")."','".date("g:i:s")."','".$_SESSION['cedula_usuario']."')"
								,$conex))
{
	//Si es exitoso
	$estado = TRUE;
}
else 
{
	//Si no es exitoso
	$estado = FALSE;
}
//Se imprime el formato JSON que retorna los valores
	echo json_encode(array("estado"=>$estado));

?>