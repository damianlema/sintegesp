<?php
//Establece conexion con la base de datos
include ("../conf/conex.php");
$conex=conectarse();

//Obtiene el primary key del mensaje que se va a reenviar
$idMensaje = $_POST['idMensaje'];

//Se consultan los datos del mensaje
$consulta = mysql_query("SELECT * FROM microbloging WHERE id_mensaje= '".$idMensaje."'",$conex);
$reg = mysql_fetch_array($consulta);

//Se ingresa nuevo mensaje con datos del mensaje reenviado, asociado al nuevo usuario
if($consulta2=mysql_query("INSERT INTO microbloging (id_usuario, mensaje, fecha, hora, id_propietario)
							  VALUES('".$_SESSION['cedula_usuario']."', '".$reg[2]."','".date("Y-m-d")."','".date("g:i:s")."','".$reg[5]."')",$conex))
{
	//Si la consulta es exitosa
	$estado = TRUE;
}
else 
{
	//Si la consulta no es exitosa
	$estado = FALSE;
}
//Se imprime el formato JSON que retorna los valores
echo json_encode(array("estado"=>$estado));

?>