<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<? 
if (!($link=mysql_connect("localhost","root","1234"))){
   		echo "Error conectando al Servidor: ".mysql_error(); 
}

if (!mysql_select_db("gestion_barrancas_2012",$link)) {

      echo "Error conectando a la base de datos."; 
} 
   mysql_query("SET NAMES 'utf8'");
 
$sql = mysql_query("SELECT * from subcuenta_primer_cuentas_contables");

while ($bus = mysql_fetch_array($sql)){
	
	$denominacion = ucwords(mb_convert_case($bus["denominacion"], MB_CASE_LOWER, "UTF-8"));
	echo $denominacion."<br>";
	$actualizar = mysql_query("update subcuenta_primer_cuentas_contables set denominacion = '".$denominacion."'
									where idsubcuenta_primer_cuentas_contables = '".$bus["idsubcuenta_primer_cuentas_contables"]."'")or die(mysql_error());

}
/*
$sql2 = mysql_query("SELECT * from subcuenta_segundo_cuentas_contables");

while ($bus2 = mysql_fetch_array($sql2)){
	$denominacion = ucwords(mb_convert_case($bus2["denominacion"], MB_CASE_LOWER, "UTF-8"));;
	echo $denominacion."<br>";
	$actualizar = mysql_query("update subcuenta_segundo_cuentas_contables set denominacion = '".$denominacion."'
									where idsubcuenta_segundo_cuentas_contables = '".$bus2["idsubcuenta_segundo_cuentas_contables"]."'")or die(mysql_error());

}*/
?>