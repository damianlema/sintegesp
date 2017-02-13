<?
include("../conf/conex.php");
Conectarse();

$sql_trabajadores = mysql_query("select * from trabajador");
while($bus_trabajadores = mysql_fetch_array($sql_trabajadores)){
	$sql_movimientos_personal = mysql_query("select * from movimientos_personal where idtipo_movimiento = '0' and idtrabajador = '".$bus_trabajadores["idtrabajador"]."'");
	$num_movimientos_personal = mysql_num_rows($sql_movimientos_personal);
	
	if($num_movimientos_personal == 0){
		$sql_ingresar = mysql_query("insert into movimientos_personal(idtrabajador,
																fecha_movimiento,
																idtipo_movimiento,
																justificacion,
																fecha_ingreso,
																idcargo,
																idubicacion_funcional,
																usuario,
																status,
																fechayhora,
																centro_costo)values('".$bus_trabajadores["idtrabajador"]."',
																			'".$bus_trabajadores["fecha_ingreso"]."',
																			'0',
																			'INGRESO',
																			'".$bus_trabajadores["fecha_ingreso"]."',
																			'".$bus_trabajadores["idcargo"]."',
																			'".$bus_trabajadores["idunidad_funcional"]."',
																			'jbello',
																			'a',
																			'".date("Y-m-d")."',
																			'".$bus_trabajadores["centro_costo"]."')")or die(mysql_error());
	}
}
?>