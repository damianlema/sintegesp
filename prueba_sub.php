<?
include("conf/conex.php");
Conectarse();
$i=1;
$sql_consulta = mysql_query("SELECT * FROM orden_compra_servicio WHERE tipo =204");

while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$sql_beneficiario = mysql_query("SELECT * FROM beneficiarios WHERE idbeneficiarios ='".$bus_consulta["idbeneficiarios"]."'");
	$bus_beneficiario = mysql_fetch_array($sql_beneficiario);
	echo "<br>"." Cedula RIF ".$bus_beneficiario["rif"]." ".$bus_beneficiario["nombre"]." ";
	$letra = substr($bus_beneficiario["rif"],0,1);
	echo $letra." ";
	if ( $letra == "V"){
		$rif = substr($bus_beneficiario["rif"],2,8);
		echo " CEDULA ".$rif."<br>";
	}
}