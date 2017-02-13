<?
function getDiaSemana($fecha) {
	$fecha=str_replace("/","-",$fecha);
	list($dia,$mes,$anio)=explode("-",$fecha);
	$dia = (((mktime ( 0, 0, 0, $mes, $dia, $anio) - mktime ( 0, 0, 0, 7, 17, $anio))/(60*60*24))+700000) % 7;
	//$dia--; if ($dia == 0) $dia = 7;
	return $dia;
}

echo getDiaSemana("18-07-2010");








/*
$fec_vencimi= date("Y-m-d", strtotime("2010-11-04 + 6"));  

echo $fec_vencimi
*/
?>