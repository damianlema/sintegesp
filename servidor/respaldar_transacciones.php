<?
set_time_limit(-1);
include("conf/conex.php");
$conexion_gestion = Conectarse();


$anio_actual = date("y");
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




$sql_transacciones = mysql_query("select * from registro_transacciones")or die(mysql_error());
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
		$sql_eliminar = mysql_query("delete from registro_transacciones where 
													idregistro_transacciones = ".$bus_transacciones["idregistro_transacciones"]."");
	}
	if(!$sql_transacciones){
		echo "Error al consultar las transacciones: <br>".mysql_error();
	}else if(!$sql_respaldar){
		echo "Error al Respaldar las transacciones: <br>".mysql_error();
	}else if(!$sql_eliminar){
		echo "Error al Eliminar las transacciones: <br>".mysql_error();
	}else{
		echo "exito";
	}
}else{
echo "exito";
}






?>