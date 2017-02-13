<?
include("conf/conex.php");
Conectarse();
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador 
										where 
										tabla = 'constantes_nomina' 
										and idconcepto = '1'
										and (idtipo_nomina = '1'
											or idtipo_nomina = '2'
											or idtipo_nomina = '3'
											or idtipo_nomina = '9')")or die(mysql_error());
											
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$sql_tipo_nomina = mysql_query("select * from tipo_nomina where idtipo_nomina = '".$bus_consulta["idtipo_nomina"]."'");
	$bus_tipo_nomina = mysql_fetch_array($sql_tipo_nomina);
	
	$sd = $bus_consulta["valor"]/30;
	echo "TIPO DE NOMINA: ".$bus_tipo_nomina["titulo_nomina"]." .... MENSUAL: ".$bus_consulta["valor"]." ...... DIARIO: ".$sd."<br>";
}
?>