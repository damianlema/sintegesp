<?
include("conf/conex.php");
Conectarse();

$sql_consultar = mysql_query("select * from trabajador where nro_ficha like '%RF%' or nro_ficha like '%RE%'");

while($bus_consultar = mysql_fetch_array($sql_consultar)){
	$sql_nomenclatura = mysql_query("select * from nomenclatura_fichas where descripcion = 'OR'");
	$bus_nomenclatura = mysql_fetch_array($sql_nomenclatura);
	
	$numero_con_ceros = str_pad($bus_nomenclatura["numero"], 4, "0", STR_PAD_LEFT);
	$nueva_ficha = "OR".$numero_con_ceros;
	
	
	
	$sql_actualizar = mysql_query("update trabajador set nro_ficha = '".$nueva_ficha."' where idtrabajador = '".$bus_consultar["idtrabajador"]."'");
	$sql_actualizar = mysql_query("update nomenclatura_fichas set numero = numero + 1 where idnomenclatura_fichas= '".$bus_nomenclatura["idnomenclatura_fichas"]."'");
	
	echo "Actualizado el numero de ficha: <strong>".$bus_consultar["nro_ficha"]."</strong> POR EL NUMERO: <strong>".$nueva_ficha."</strong><br>";
}
?>