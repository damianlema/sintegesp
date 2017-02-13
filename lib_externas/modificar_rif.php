<?
include("conf/conex.php");
Conectarse();

$sql_consulta = mysql_query("select * from beneficiarios");
$i = 1;
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nueva_consulta = ereg_replace("-", "", $bus_consulta["rif"]);
	$sql_actualizar = mysql_query("update beneficiarios set rif = '".$nueva_consulta."' where idbeneficiarios = '".$bus_consulta["idbeneficiarios"]."'")or die(mysql_error());
	echo $i." ".$nueva_consulta."<br>";	
	$i++;
}

?>