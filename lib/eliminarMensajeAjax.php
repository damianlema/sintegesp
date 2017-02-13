<?php

include ("../conf/conex.php");
$conex=conectarse();

$idMensaje = $_POST['idMensaje'];

if(mysql_query("DELETE FROM microbloging WHERE id_mensaje='".$idMensaje."'",$conex))
{
	$estado = TRUE;
}
else 
{
	$estado = FALSE;
}

	echo json_encode(array("estado"=>$estado));

?>