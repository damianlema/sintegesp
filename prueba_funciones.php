<?
include("conf/conex.php");
Conectarse();

function mesesentre($fecha1, $fecha2, $idtrabajador){
	if($fecha1 == "fecha_ingreso"){
		$sql_trabajador = mysql_query("select fecha_ingreso from trabajador where idtrabajador = '".$idtrabajador."'");
		$bus_trabajador = mysql_fetch_array($sql_trabajador);
		
		$mes = explode("-", $bus_trabajador["fecha_ingreso"]);
		$result = $fecha2-$mes[1];
		return $result;
	}	
}



function diasentre($fecha1, $fecha2, $idtrabajador){
	if($fecha1 == "fecha_ingreso"){
		$sql_trabajador = mysql_query("select fecha_ingreso from trabajador where idtrabajador = '".$idtrabajador."'");
		$bus_trabajador = mysql_fetch_array($sql_trabajador);
		
		$dia = explode("-", $bus_trabajador["fecha_ingreso"]);
		$result = $fecha2-$dia[2];
		return $result;
	}	
}


$n = 143;
$m = 12;
$d = 360;

echo "MESES: ".round($n/$m*mesesentre("fecha_ingreso", 12, 24),2)."<br />";
echo "DIAS: ".round($n/$d*diasentre("fecha_ingreso", 31, 24),2)."<br />";



echo "FORMULA: ";
echo (((143/12)*10)+((143/365)*13))*(2000/30);
?>