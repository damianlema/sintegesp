<?
include("conf/conex.php");
Conectarse();
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "3270,30"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 4251.39;
	if ($bus_consulta["valor"] == 3270.30){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 3270.30");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "3300,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 4280.10;
	if ($bus_consulta["valor"] == 3300.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 3300.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "3330,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 4309.02;
	if ($bus_consulta["valor"] == 3330.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 3330.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "3400,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 4375.80;
	if ($bus_consulta["valor"] == 3400.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 3400.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "3420,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 4394.70;
	if ($bus_consulta["valor"] == 3420.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 3420.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "3441,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 4421.69;
	if ($bus_consulta["valor"] == 3441.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 3441.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "3557,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 4547.93;
	if ($bus_consulta["valor"] == 3557.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 3557.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "3600,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 4579.20;
	if ($bus_consulta["valor"] == 3600.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 3600.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "3700,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 4680.50;
	if ($bus_consulta["valor"] == 3700.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 3700.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "3800,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 4780.40;
	if ($bus_consulta["valor"] == 3800.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 3800.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "3808,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 4786.66;
	if ($bus_consulta["valor"] == 3808.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 3808.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "4000,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 4980.80;
	if ($bus_consulta["valor"] == 4000.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 4000.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "4200,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 5174.40;
	if ($bus_consulta["valor"] == 4200.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 4200.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "4256,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 5234.88;
	if ($bus_consulta["valor"] == 4256.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 4256.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "4480,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 5456.64;
	if ($bus_consulta["valor"] == 4480.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 4480.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "4500,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 5481.00;
	if ($bus_consulta["valor"] == 4500.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 4500.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "4700,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 5672.90;
	if ($bus_consulta["valor"] == 4700.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 4700.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "4800,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 5780.64;
	if ($bus_consulta["valor"] == 4800.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 4800.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "5000,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 5980.00;
	if ($bus_consulta["valor"] == 5000.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 5000.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "5309,92"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 6265.71;
	if ($bus_consulta["valor"] == 5309.92){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 5309.92");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "5500,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 6380.00;
	if ($bus_consulta["valor"] == 5500.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 5500.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "5700,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 6555.00;
	if ($bus_consulta["valor"] == 5700.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 5700.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "7840,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 8780.80;
	if ($bus_consulta["valor"] == 7840.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 7840.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "9000,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 9981.00;
	if ($bus_consulta["valor"] == 9000.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 9000.00");
		$i=$i+1;
		echo "<br>";
	}
}
echo "<br>";
$i=1;
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
echo "10080,00"."<BR>";
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = 10987.20;
	if ($bus_consulta["valor"] == 10080.00){
		echo $i;
		//echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
		$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where valor = 10080.00");
		$i=$i+1;
		echo "<br>";
	}
}
?>