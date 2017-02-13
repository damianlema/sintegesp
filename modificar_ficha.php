<?
include("conf/conex.php");
Conectarse();

$sql_trabajadores = mysql_query("select * from trabajador");
$i=1;
while($bus_trabajador = mysql_fetch_array($sql_trabajadores)){
	if(is_numeric($bus_trabajador["nro_ficha"])){
		$sub = substr($bus_trabajador["nro_ficha"],2,10);
		$nro_ficha = "EM".$sub;
		echo $i."-".$bus_trabajador["nro_ficha"]." POR -> ".$nro_ficha."<br>";
		$i++;
		$sql_actualizar = mysql_query("update trabajador set nro_ficha = '".$nro_ficha."' where idtrabajador = '".$bus_trabajador["idtrabajador"]."'");
	}
}
?>