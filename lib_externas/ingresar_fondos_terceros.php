<?
include("../conf/conex.php");
Conectarse();

$idgenerar_nomina = 56;
$idconcepto = 23;
$idAporte = 56;

$sql_consulta = mysql_query("select * from relacion_generar_nomina where idgenerar_nomina = '".$idgenerar_nomina."'
																	and idconcepto = '".$idconcepto."'
																	and tabla = 'conceptos_nomina'")or die("ERROR CONSULTANDO:".mysql_error());
																	
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
		$total = $bus_consulta["total"]*2;
		$sql_ingresar = mysql_query("insert into relacion_generar_nomina(idgenerar_nomina, 
																		idtrabajador,
																		idconcepto,
																		tabla,
																		total)VALUES('".$idgenerar_nomina."',
																					'".$bus_consulta["idtrabajador"]."',
																					'".$idAporte."',
																					'".$bus_consulta["tabla"]."',
																					'".$total."')")or die("ERROR INGRESANDO:".mysql_error());
		echo "SE INGRESO EL CONCEPTO POR ".$total." CON EL ID ".mysql_insert_id()."<br>";
	}
?>