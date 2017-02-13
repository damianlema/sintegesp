<?php
session_start();
include ("../../conf/conex.php");
$conexion_db=conectarse();
$cedula=$_SESSION["cedula_usuario"];
mysql_query("update usuarios 
						set estado='i'
						where login = '".$_SESSION["login"]."'"); // modifica el estatus del usuario para indicar que tiene sesion abierta 

//desconectar();

$sql_consulta = mysql_query("select * from usuarios where cedula='".$cedula."'");
$bus_consulta = mysql_fetch_array($sql_consulta);



$anio_actual = $_SESSION["anio_fiscal"];
$mes_actual = date("m");

$arregloMeses["01"] = "enero";
$arregloMeses["02"] = "febrero";
$arregloMeses["03"] = "marzo";
$arregloMeses["04"] = "abril";
$arregloMeses["05"] = "mayo";
$arregloMeses["06"] = "junio";
$arregloMeses["07"] = "julio";
$arregloMeses["08"] = "agosto";
$arregloMeses["09"] = "septiembre";
$arregloMeses["10"] = "octubre";
$arregloMeses["11"] = "noviembre";
$arregloMeses["12"] = "diciembre";




$sql_transacciones = mysql_query("select * from gestion_".$anio_actual.".registro_transacciones where usuario = '".$bus_consulta["login"]."'")or die(mysql_error());
$num_transacciones = mysql_num_rows($sql_transacciones);
if($num_transacciones > 0){	
	while($bus_transacciones = mysql_fetch_array($sql_transacciones)){
		$sql_respaldar = mysql_query("insert into gestion_respaldo_".$anio_actual.".".$arregloMeses[$mes_actual]." 
																			(tipo, tabla, usuario, fechayhora, estacion)
																			values
																			('".$bus_transacciones["tipo"]."',
																			'".$bus_transacciones["tabla"]."',
																			'".$bus_transacciones["usuario"]."',
																			'".$bus_transacciones["fechayhora"]."',
																			'".$bus_transacciones["estacion"]."')");
		$sql_eliminar = mysql_query("delete from gestion_".$anio_actual.".registro_transacciones where 
													idregistro_transacciones = ".$bus_transacciones["idregistro_transacciones"]."");
	}
}else{
	echo "exito";
}


session_unset();
session_destroy();
?>