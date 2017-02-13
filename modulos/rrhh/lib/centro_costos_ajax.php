<?
include("../../../conf/conex.php");
Conectarse();


extract($_POST);

if($ejecutar == "actualizarCentroCostos"){
	
	$sql_ingresar = mysql_query("update niveles_organizacionales 
										set idcategoria_programatica = '".$idcategoria_programatica."'
										where idniveles_organizacionales = '".$ubicacion_funcional."'")or die(mysql_error());
}




if($ejecutar == "consultarCategoria"){
	$sql_consulta = mysql_query("select idcategoria_programatica from niveles_organizacionales where 
											idniveles_organizacionales = '".$idniveles_organizacionales."'
											and idcategoria_programatica != ''")or die(mysql_error());
	$bus_consulta = mysql_fetch_array($sql_consulta);
	
	$sql_categoria = mysql_query("select ca.codigo,
										un.denominacion,
										ca.idcategoria_programatica 
											from 
										categoria_programatica ca,
										unidad_ejecutora un
											where 
										ca.idcategoria_programatica = '".$bus_consulta["idcategoria_programatica"]."'
										and un.idunidad_ejecutora = ca.idunidad_ejecutora")or die(mysql_error());
	$bus_categoria = mysql_fetch_array($sql_categoria);
	
	
	echo $bus_categoria["codigo"]."|.|".
		$bus_categoria["denominacion"]."|.|".
		$bus_categoria["idcategoria_programatica"];

}
?>