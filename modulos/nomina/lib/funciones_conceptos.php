<?php
//include("../../../conf/conex.php");
//Conectarse();
// FUNCIONES DE CONVERSION DE NUMEROS A LETRAS.
function centimos() {
	global $importe_parcial;

	$importe_parcial = number_format($importe_parcial, 2, ".", "") * 100;

	if ($importe_parcial > 0)
		$num_letra = " con ".decena_centimos($importe_parcial);
	else
		$num_letra = "";

	return $num_letra;
}

function unidad_centimos($numero){
	switch ($numero)
	{
		case 9:
		{
			$num_letra = "nueve céntimos";
			break;
		}
		case 8:
		{
			$num_letra = "ocho céntimos";
			break;
		}
		case 7:
		{
			$num_letra = "siete céntimos";
			break;
		}
		case 6:
		{
			$num_letra = "seis céntimos";
			break;
		}
		case 5:
		{
			$num_letra = "cinco céntimos";
			break;
		}
		case 4:
		{
			$num_letra = "cuatro céntimos";
			break;
		}
		case 3:
		{
			$num_letra = "tres céntimos";
			break;
		}
		case 2:
		{
			$num_letra = "dos céntimos";
			break;
		}
		case 1:
		{
			$num_letra = "un céntimo";
			break;
		}
	}
	return $num_letra;
}

function decena_centimos($numero)
{
	if ($numero >= 10)
	{
		if ($numero >= 90 && $numero <= 99)
		{
			  if ($numero == 90)
				  return "noventa céntimos";
			  else if ($numero == 91)
				  return "noventa y un céntimos";
			  else
				  return "noventa y ".unidad_centimos($numero - 90);
		}
		if ($numero >= 80 && $numero <= 89)
		{
			if ($numero == 80)
				return "ochenta céntimos";
			else if ($numero == 81)
				return "ochenta y un céntimos";
			else
				return "ochenta y ".unidad_centimos($numero - 80);
		}
		if ($numero >= 70 && $numero <= 79)
		{
			if ($numero == 70)
				return "setenta céntimos";
			else if ($numero == 71)
				return "setenta y un céntimos";
			else
				return "setenta y ".unidad_centimos($numero - 70);
		}
		if ($numero >= 60 && $numero <= 69)
		{
			if ($numero == 60)
				return "sesenta céntimos";
			else if ($numero == 61)
				return "sesenta y un céntimos";
			else
				return "sesenta y ".unidad_centimos($numero - 60);
		}
		if ($numero >= 50 && $numero <= 59)
		{
			if ($numero == 50)
				return "cincuenta céntimos";
			else if ($numero == 51)
				return "cincuenta y un céntimos";
			else
				return "cincuenta y ".unidad_centimos($numero - 50);
		}
		if ($numero >= 40 && $numero <= 49)
		{
			if ($numero == 40)
				return "cuarenta céntimos";
			else if ($numero == 41)
				return "cuarenta y un céntimos";
			else
				return "cuarenta y ".unidad_centimos($numero - 40);
		}
		if ($numero >= 30 && $numero <= 39)
		{
			if ($numero == 30)
				return "treinta céntimos";
			else if ($numero == 91)
				return "treinta y un céntimos";
			else
				return "treinta y ".unidad_centimos($numero - 30);
		}
		if ($numero >= 20 && $numero <= 29)
		{
			if ($numero == 20)
				return "veinte céntimos";
			else if ($numero == 21)
				return "veintiun céntimos";
			else
				return "veinti".unidad_centimos($numero - 20);
		}
		if ($numero >= 10 && $numero <= 19)
		{
			if ($numero == 10)
				return "diez céntimos";
			else if ($numero == 11)
				return "once céntimos";
			else if ($numero == 11)
				return "doce céntimos";
			else if ($numero == 11)
				return "trece céntimos";
			else if ($numero == 11)
				return "catorce céntimos";
			else if ($numero == 11)
				return "quince céntimos";
			else if ($numero == 11)
				return "dieciseis céntimos";
			else if ($numero == 11)
				return "diecisiete céntimos";
			else if ($numero == 11)
				return "dieciocho céntimos";
			else if ($numero == 11)
				return "diecinueve céntimos";
		}
	}
	else
		return unidad_centimos($numero);
}

function unidad($numero)
{
	switch ($numero)
	{
		case 9:
		{
			$num = "nueve";
			break;
		}
		case 8:
		{
			$num = "ocho";
			break;
		}
		case 7:
		{
			$num = "siete";
			break;
		}
		case 6:
		{
			$num = "seis";
			break;
		}
		case 5:
		{
			$num = "cinco";
			break;
		}
		case 4:
		{
			$num = "cuatro";
			break;
		}
		case 3:
		{
			$num = "tres";
			break;
		}
		case 2:
		{
			$num = "dos";

			break;
		}
		case 1:
		{
			$num = "uno";
			break;
		}
	}
	return $num;
}

function decena($numero)
{
	if ($numero >= 90 && $numero <= 99)
	{
		$num_letra = "noventa ";
		
		if ($numero > 90)
			$num_letra = $num_letra."y ".unidad($numero - 90);
	}
	else if ($numero >= 80 && $numero <= 89)
	{
		$num_letra = "ochenta ";
		
		if ($numero > 80)
			$num_letra = $num_letra."y ".unidad($numero - 80);
	}
	else if ($numero >= 70 && $numero <= 79)
	{
			$num_letra = "setenta ";
		
		if ($numero > 70)
			$num_letra = $num_letra."y ".unidad($numero - 70);
	}
	else if ($numero >= 60 && $numero <= 69)
	{
		$num_letra = "sesenta ";
		
		if ($numero > 60)
			$num_letra = $num_letra."y ".unidad($numero - 60);
	}
	else if ($numero >= 50 && $numero <= 59)
	{
		$num_letra = "cincuenta ";
		
		if ($numero > 50)
			$num_letra = $num_letra."y ".unidad($numero - 50);
	}
	else if ($numero >= 40 && $numero <= 49)
	{
		$num_letra = "cuarenta ";
		
		if ($numero > 40)
			$num_letra = $num_letra."y ".unidad($numero - 40);
	}
	else if ($numero >= 30 && $numero <= 39)
	{
		$num_letra = "treinta ";
		
		if ($numero > 30)
			$num_letra = $num_letra."y ".unidad($numero - 30);
	}
	else if ($numero >= 20 && $numero <= 29)
	{
		if ($numero == 20)
			$num_letra = "veinte ";
		else
			$num_letra = "veinti".unidad($numero - 20);
	}
	else if ($numero >= 10 && $numero <= 19)
	{
		switch ($numero)
		{
			case 10:
			{
				$num_letra = "diez ";
				break;
			}
			case 11:
			{
				$num_letra = "once ";
				break;
			}
			case 12:
			{
				$num_letra = "doce ";
				break;
			}
			case 13:
			{
				$num_letra = "trece ";
				break;
			}
			case 14:
			{
				$num_letra = "catorce ";
				break;
			}
			case 15:
			{
				$num_letra = "quince ";
				break;
			}
			case 16:
			{
				$num_letra = "dieciseis ";
				break;
			}
			case 17:
			{
				$num_letra = "diecisiete ";
				break;
			}
			case 18:
			{
				$num_letra = "dieciocho ";
				break;
			}
			case 19:
			{
				$num_letra = "diecinueve ";
				break;
			}
		}
	}
	else
		$num_letra = unidad($numero);

	return $num_letra;
}

function centena($numero)
{
	if ($numero >= 100)
	{
		if ($numero >= 900 & $numero <= 999)
		{
			$num_letra = "novecientos ";
			
			if ($numero > 900)
				$num_letra = $num_letra.decena($numero - 900);
		}
		else if ($numero >= 800 && $numero <= 899)
		{
			$num_letra = "ochocientos ";
			
			if ($numero > 800)
				$num_letra = $num_letra.decena($numero - 800);
		}
		else if ($numero >= 700 && $numero <= 799)
		{
			$num_letra = "setecientos ";
			
			if ($numero > 700)
				$num_letra = $num_letra.decena($numero - 700);
		}
		else if ($numero >= 600 && $numero <= 699)
		{
			$num_letra = "seiscientos ";
			
			if ($numero > 600)
				$num_letra = $num_letra.decena($numero - 600);
		}
		else if ($numero >= 500 && $numero <= 599)
		{
			$num_letra = "quinientos ";
			
			if ($numero > 500)
				$num_letra = $num_letra.decena($numero - 500);
		}
		else if ($numero >= 400 && $numero <= 499)
		{
			$num_letra = "cuatrocientos ";
			
			if ($numero > 400)
				$num_letra = $num_letra.decena($numero - 400);
		}
		else if ($numero >= 300 && $numero <= 399)
		{
			$num_letra = "trescientos ";
			
			if ($numero > 300)
				$num_letra = $num_letra.decena($numero - 300);
		}
		else if ($numero >= 200 && $numero <= 299)
		{
			$num_letra = "doscientos ";
			
			if ($numero > 200)
				$num_letra = $num_letra.decena($numero - 200);
		}
		else if ($numero >= 100 && $numero <= 199)
		{
			if ($numero == 100)
				$num_letra = "cien ";
			else
				$num_letra = "ciento ".decena($numero - 100);
		}
	}
	else
		$num_letra = decena($numero);
	
	return $num_letra;
}

function cien()
{
	global $importe_parcial;
	
	$parcial = 0; $car = 0;
	
	while (substr($importe_parcial, 0, 1) == 0)
		$importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);
	
	if ($importe_parcial >= 1 && $importe_parcial <= 9.99)
		$car = 1;
	else if ($importe_parcial >= 10 && $importe_parcial <= 99.99)
		$car = 2;
	else if ($importe_parcial >= 100 && $importe_parcial <= 999.99)
		$car = 3;
	
	$parcial = substr($importe_parcial, 0, $car);
	$importe_parcial = substr($importe_parcial, $car);
	
	$num_letra = centena($parcial).centimos();
	
	return $num_letra;
}

function cien_mil()
{
	global $importe_parcial;
	
	$parcial = 0; $car = 0;
	
	while (substr($importe_parcial, 0, 1) == 0)
		$importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);
	
	if ($importe_parcial >= 1000 && $importe_parcial <= 9999.99)
		$car = 1;
	else if ($importe_parcial >= 10000 && $importe_parcial <= 99999.99)
		$car = 2;
	else if ($importe_parcial >= 100000 && $importe_parcial <= 999999.99)
		$car = 3;
	
	$parcial = substr($importe_parcial, 0, $car);
	$importe_parcial = substr($importe_parcial, $car);
	
	if ($parcial > 0)
	{
		if ($parcial == 1)
			$num_letra = "mil ";
		else
			$num_letra = centena($parcial)." mil ";
	}
	
	return $num_letra;
}


function millon()
{
	global $importe_parcial;
	
	$parcial = 0; $car = 0;
	
	while (substr($importe_parcial, 0, 1) == 0)
		$importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);
	
	if ($importe_parcial >= 1000000 && $importe_parcial <= 9999999.99)
		$car = 1;
	else if ($importe_parcial >= 10000000 && $importe_parcial <= 99999999.99)
		$car = 2;
	else if ($importe_parcial >= 100000000 && $importe_parcial <= 999999999.99)
		$car = 3;
	
	$parcial = substr($importe_parcial, 0, $car);
	$importe_parcial = substr($importe_parcial, $car);
	
	if ($parcial == 1)
		$num_letras = "un millón ";
	else
		$num_letras = centena($parcial)." millones ";
	
	return $num_letras;
}

function convertir_a_letras($numero)
{// FUNCION PARA CONVERTIR NUMEROS EN LETRAS ... USA TODAS LAS QUE ESTAN ARRIBA DE ELLA
	global $importe_parcial;
	
	$importe_parcial = $numero;
	
	if ($numero < 1000000000)
	{
		if ($numero >= 1000000 && $numero <= 999999999.99)
			$num_letras = millon().cien_mil().cien();
		else if ($numero >= 1000 && $numero <= 999999.99)
			$num_letras = cien_mil().cien();
		else if ($numero >= 1 && $numero <= 999.99)
			$num_letras = cien();
		else if ($numero >= 0.01 && $numero <= 0.99)
		{
			if ($numero == 0.01)
				$num_letras = "un céntimo";
			else
				$num_letras = convertir_a_letras(($numero * 100)."/100")." céntimos";
		}
	}
	return $num_letras;
}
//	-------------------------------

//	-------------------------------
function getFechaFin($fecha, $dias) {
	$sumar=true;
	$dia_semana=getDiaSemana($fecha); $dia_semana++;
	list($dia, $mes, $anio)=SPLIT('[/.-]', $fecha);
	$d=(int) $dia; $m=(int) $mes; $a=(int) $anio;
	for ($i=1; $i<=$dias;) {
		$dia_semana++;
		if ($dia_semana==8) $dia_semana=1;
		if ($dia_semana>=1 && $dia_semana<=5) $i++; 
		$d++;
		$dias_mes=getDiasMes($a, $m);
		if ($d>$dias_mes) { 
			$d=1; $m++; 
			if ($m>12) { $m=1; $a++; }
		}
	}
	if ($d<10) $d="0$d";
	if ($m<10) $m="0$m";
	echo "$d-$m-$a";
}
//	-------------------------------

//	-------------------------------
function getDiasPermiso($fdesde, $fhasta) {
	list($dd, $mm, $ad)=SPLIT('[/.-]', $fdesde); $dd = (int) $md; $md = (int) $dd; $ad = (int) $ad;
	$dias_completos = getFechaDias($fdesde, $fhasta);
	$dia_semana = getDiaSemana($fdesde);
	$dias_permiso = 0;
	
	for ($i=0; $i<=$dias_completos; $i++) {
		if ($dia_semana == 7) $dia_semana = 1;
		if ($dia_semana >= 2 && $dia_semana <= 6) $dias_permiso++;
		$dia_semana++;
	}
	return $dias_permiso;
}
//	-------------------------------

//	-------------------------------
function diasferiadosentre($fdesde, $fhasta) {
	list($anio_desde, $mes_desde, $dia_desde )=SPLIT('[/.-]', $fdesde);
	list($anio_hasta, $mes_hasta, $dia_hasta)=SPLIT('[/.-]', $fhasta);
	
	$sql = "SELECT * FROM 
					dias_feriados 
						WHERE 
					(mes >= '".$mes_desde."' and mes <= '".$mes_hasta."') 
						AND 
					(dia >= '".$dia_desde."' AND dia <= '".$dia_hasta."')";
	//echo $sql;
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	return $rows;
}

 //echo diasferiadosentre("2010-01-01","2010-07-01");
//	-------------------------------

//	-------------------------------
function getDiaSemana($fecha) {
	$fecha=str_replace("/","-",$fecha);
	list($dia,$mes,$anio)=explode("-",$fecha);
	$dia = (((mktime ( 0, 0, 0, $mes, $dia, $anio) - mktime ( 0, 0, 0, 7, 17, $anio))/(60*60*24))+700000) % 7;
	//$dia--; if ($dia == 0) $dia = 7;
	return $dia;
}
//	-------------------------------

//	-------------------------------
function diasmes($anio, $mes) {
	//echo $mes;
	$dias_mes["01"]=31;
	if ($anio%4==0) $dias_mes["02"]=29; else $dias_mes["02"]=28;
	$dias_mes["03"]=31;
	$dias_mes["04"]=30;
	$dias_mes["05"]=31;
	$dias_mes["06"]=30;
	$dias_mes["07"]=31;
	$dias_mes["08"]=31;
	$dias_mes["09"]=30;
	$dias_mes["10"]=31;
	$dias_mes["11"]=30;
	$dias_mes["12"]=31;
	return $dias_mes[$mes];
}
//	-------------------------------
//echo diasmes(date("Y") , date("m"));
//	-------------------------------
function getNombreMes($mes) {
	$nombre[1]="Enero";
	$nombre[2]="Febrero";
	$nombre[3]="Marzo";
	$nombre[4]="Abril";
	$nombre[5]="Mayo";
	$nombre[6]="Junio";
	$nombre[7]="Julio";
	$nombre[8]="Agosto";
	$nombre[9]="Septiembre";
	$nombre[10]="Octubre";
	$nombre[11]="Noviembre";
	$nombre[12]="Diciembre";
	return $nombre[$mes];
}
//	-------------------------------

//	-------------------------------
function getFechaDias($fechad, $fechah){ // DEVUELVE DIFERENCIAS EN DIAS PERO CONTANDO LOS FINES DESEMANA
	list($dd, $md, $ad)=SPLIT( '[/.-]', $fechad);
	list($dh, $mh, $ah)=SPLIT( '[/.-]', $fechah);
	
	//	Calculo timestam de las dos fechas
	$timestampd = mktime(0, 0, 0, $md, $dd, $ad);
	$timestamph = mktime(0, 0, 0, $mh, $dh, $ah);
	
	//	Resto a una fecha la otra
	$segundos_diferencia = $timestampd - $timestamph;
	
	//convierto segundos en días
	$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
	
	//obtengo el valor absoulto de los días (quito el posible signo negativo)
	$dias_diferencia = abs($dias_diferencia);
	
	//quito los decimales a los días de diferencia
	$dias_diferencia = floor($dias_diferencia); 
	
	return $dias_diferencia;
}
//	---------------------------------

//	---------------------------------
//	FUNCION PARA OBTENER LA EDAD DE UNA FECHA INGRESADA
function getAnios($fecha) { // RETORNA LA EDAD DE UNA PERSONA EN AÑOS MES Y DIA
	$dia=date("d");
	$mes=date("m");
	$anno=date("Y");
	$dia_nac=substr($fecha, 8, 2);
	$mes_nac=substr($fecha, 5, 2);
	$anno_nac=substr($fecha, 0, 4);
		if($mes_nac>$mes){
			$calc_edad= $anno-$anno_nac-1;
		}else{
			if($mes==$mes_nac AND $dia_nac>$dia AND $anno == $anno_nac){
				$calc_edad= $anno-$anno_nac-1;
			}else{
				$calc_edad= $anno-$anno_nac;
			}
		}
	return $calc_edad;
}
//	---------------------------------



function tiempo_bono($idperiodo, $idtrabajador){
	
	
	//echo "PRUEBA id".$idtrabajador;
	//echo "select * from rango_periodo_nomina where idrango_periodo_nomina = '".$idperiodo."'";
	$sql_periodo = mysql_query("select * from rango_periodo_nomina where idrango_periodo_nomina = '".$idperiodo."'");
	$bus_periodo = mysql_fetch_array($sql_periodo)or die("select * from rango_periodo_nomina where idrango_periodo_nomina = '".$idperiodo."': ".mysql_error());
	
	list($anio_periodo_desde,$mes_periodo_desde,$dia_periodo_desde) = explode("-",$bus_periodo["desde"]);
	list($anio_periodo_hasta,$mes_periodo_hasta,$dia_periodo_hasta) = explode("-",$bus_periodo["hasta"]);
	
	//echo "select * from trabajador where idtrabajador = '".$idtrabajador."'";
	$sql_fecha_ingreso = mysql_query("select * from trabajador where idtrabajador = '".$idtrabajador."'")or die("Trabajador: ".mysql_error());
	$bus_fecha_ingreso = mysql_fetch_array($sql_fecha_ingreso);
	
	list($anio_fecha_ingreso,$mes_fecha_ingreso,$dia_fecha_ingreso) = explode("-",$bus_fecha_ingreso["fecha_ingreso"]);
	
	if($mes_fecha_ingreso == $mes_periodo_desde){
		//echo "aqui";
		if($dia_fecha_ingreso >= $dia_periodo_desde && $dia_fecha_ingreso <= $dia_periodo_hasta){

			if($anio_fecha_ingreso == date("Y")){
				//echo "POR ALLA";
				return "0";
			}else{
				return "1";
			}
		}else{
			return "0";
		}
	}else{
		return "0";
	}
	
	
}

function dia_fin_periodo($idperiodo){
    $sql_periodo = mysql_query("select * from rango_periodo_nomina where idrango_periodo_nomina = '".$idperiodo."'");
    $bus_periodo = mysql_fetch_array($sql_periodo)or die("select * from rango_periodo_nomina where idrango_periodo_nomina = '".$idperiodo."': ".mysql_error());

    //list($anio_periodo_hasta,$mes_periodo_hasta,$dia_periodo_hasta) = explode("-",$bus_periodo["hasta"]);
    $dia_periodo_hasta=substr($bus_periodo["hasta"], 8, 2);
    return $dia_periodo_hasta;
}

function getFechaIngreso($idtrabajador){
	$sql = "SELECT fecha_ingreso FROM trabajador WHERE idtrabajador = '".$idtrabajador."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0){ 
		$field = mysql_fetch_array($query);
		return $field["fecha_ingreso"];
	}else{
		return 0;	
	}
}





function mesesentre($fecha1, $fecha2, $idtrabajador){
	if($fecha1 == "fechaIngreso"){
		$sql_trabajador = mysql_query("select fecha_ingreso from trabajador where idtrabajador = '".$idtrabajador."'");
		$bus_trabajador = mysql_fetch_array($sql_trabajador);
		
		$mes = explode("-", $bus_trabajador["fecha_ingreso"]);
		$result = $fecha2-$mes[1];
		return $result;
	}	
}



function diasentre($fecha1, $fecha2, $idtrabajador){
	if($fecha1 == "fechaIngreso"){
		$sql_trabajador = mysql_query("select fecha_ingreso from trabajador where idtrabajador = '".$idtrabajador."'");
		$bus_trabajador = mysql_fetch_array($sql_trabajador);
		
		$dia = explode("-", $bus_trabajador["fecha_ingreso"]);
		$result = $fecha2-$dia[2];
		return $result;
	}	
}



function edad($edad){
			list($anio,$mes,$dia) = explode("-",$edad);
			$anio_dif = date("Y") - $anio;
			$mes_dif = date("m") - $mes;
			$dia_dif = date("d") - $dia;
			if ($dia_dif < 0 || $mes_dif < 0)
			$anio_dif--;
			return $anio_dif;
		}


function numerode($idparentezco, $condicion, $edad, $idtrabajador){
	$numero = 0;
	if($condicion != "todos"){
		$sql_consultar= mysql_query("select cf.fecha_nacimiento 
												from 
													carga_familiar cf,
													parentezco pa
												where 
													cf.idtrabajador = '".$idtrabajador."'
													and cf.idparentezco = pa.idparentezco
													and pa.idparentezco = '".$idparentezco."'")or die(mysql_error());
												
		while($bus_consultar = mysql_fetch_array($sql_consultar)){
			switch($condicion){
				case ">":
					if(edad($bus_consultar["fecha_nacimiento"]) > $edad){
						$numero++;
					}
				break;
				case "<":
					if(edad($bus_consultar["fecha_nacimiento"]) < $edad){
						$numero++;
					}
				break;
				case ">=":
					if(edad($bus_consultar["fecha_nacimiento"]) >= $edad){
						$numero++;
					}
				break;
				case "<=":
					if(edad($bus_consultar["fecha_nacimiento"]) <= $edad){
						$numero++;
					}
				break;
				case "=":
					if(edad($bus_consultar["fecha_nacimiento"]) == $edad){
						$numero++;
					}
				break;
			}
		}
		$num_consultar = $numero;
	}else{
		$sql_consultar= mysql_query("select cf.idcarga_familiar 
												from 
													carga_familiar cf,
													parentezco pa
												where 
													cf.idtrabajador = '".$idtrabajador."'
													and cf.idparentezco = pa.idparentezco
													and pa.idparentezco = '".$idparentezco."'")or die(mysql_error());	
		$num_consultar= mysql_num_rows($sql_consultar);
	}
	
	
	return $num_consultar;
}


function tiempoentrefechas($desde, $hasta){
	$s1 = strtotime($desde)-strtotime($hasta);
	$d1 = intval($s1/86400);
	$d1 = ($d1*(-1));
	return $d1;
}



function anioempresa($idtrabajador) { // SE ESTA UTILIZANDO
	//echo getFechaIngreso($idtrabajador)."<br />";
	$anios = getAnios(getFechaIngreso($idtrabajador));
	//echo $anios;
	return $anios;
}

function ingresohasta($idtrabajador,$fecha) { // SE ESTA UTILIZANDO
	$fingreso = getFechaIngreso($idtrabajador);
		
	$dia_ing=substr($fecha, 8, 2);
	$mes_ing=substr($fecha, 5, 2);
	$anno_ing=substr($fecha, 0, 4);
	
	$dia_nac=substr($fingreso, 8, 2);
	$mes_nac=substr($fingreso, 5, 2);
	$anno_nac=substr($fingreso, 0, 4);
	
		if($mes_nac>$mes_ing){
			$calc_edad= $anno_ing-$anno_nac-1;
		}else{
			if($mes_ing==$mes_nac AND $dia_nac>$dia_ing AND $anno_ing == $anno_nac){
				$calc_edad= $anno_ing-$anno_nac-1;
			}else{
				$calc_edad= $anno_ing-$anno_nac;
			}
		}
	
	$anios = $calc_edad;
	if ($idtrabajador == 2271){ echo $anios.' '; }
	return $anios;
}



function getLunesMes($_PERIODO) {
	list($ap, $mp)=SPLIT('[/.-]', $_PERIODO); $periodo = "01-$mp-$ap";
	list($a, $m)=SPLIT('[/.-]', $periodo); $a = (int) $a; $m = (int) $m;
	$primer_dia_semana = getDiaSemana($periodo);
	$dias_mes = getDiasMes($a, $m);
	
	if ($primer_dia_semana == 0 && $dias_mes == 31) $lunes = 5;
	elseif ($primer_dia_semana == 1 && ($dias_mes == 30 || $dias_mes == 31)) $lunes = 5;
	elseif ($primer_dia_semana == 2 && ($dias_mes == 29 || $dias_mes == 30 || $dias_mes == 31)) $lunes = 5;
	else $lunes = 4;
	return $lunes;
}


function getMesesAntiguedad($_CODPERSONA, $_PERIODO) {
	list($ap, $mp)=SPLIT('[/.-]', $_PERIODO);
	$ap = (int) $ap;
	$mp = (int) $mp;
	list($di, $mi, $ai)=SPLIT('[/.-]', getFechaIngreso($_CODPERSONA));
	$ai = (int) $ai;
	$mi = (int) $mi;
	
	if ($ap == $ai) $meses = $mp - $mi;	
	else {
		$anios = $ap - $ai - 1;
		if ($mp > $mi) $meses = (12 + ($mp - $mi)) + (12 * $anios);
		else {
			$meses = ((12 - $mi) + $mp) + (12 * $anios);
		}
	}
	return $meses;
}



function redondear($valor, $decimales) {
	$ceros = (string) str_repeat("0", $decimales);
	$unidad = "1".$ceros;
	$unidad = (int) $unidad;
	
	$multiplicamos = $valor * $unidad;
	
	list($parte_entera, $numero_redondeo)=SPLIT('[.]', $multiplicamos);
	$numero_redondeo = substr($numero_redondeo, 0, 1);
	if ($numero_redondeo >= 5) $parte_entera++;
	
	$resultado = $parte_entera / $unidad;
	
	return $resultado;
}






function diferenciaEntreFechas($fechaInicio, $fechaActual){
list($anioInicio, $mesInicio, $diaInicio) = explode("-", $fechaInicio);
list($anioActual, $mesActual, $diaActual) = explode("-", $fechaActual);

$b = 0;  
$mes = $mesInicio-1;  
if($mes==2){  
if(($anioActual%4==0 && $anioActual%100!=0) || $anioActual%400==0){  
$b = 29;  
}else{  
$b = 28;  
}  
}  
else if($mes<=7){  
if($mes==0){  
 $b = 31;  
}  
else if($mes%2==0){  
$b = 30;  
}  
else{  
$b = 31;  
}  
}  
else if($mes>7){  
if($mes%2==0){  
$b = 31;  
}  
else{  
$b = 30;  
}  
}  
if(($anioInicio>$anioActual) || ($anioInicio==$anioActual && $mesInicio>$mesActual) ||   
($anioInicio==$anioActual && $mesInicio == $mesActual && $diaInicio>$diaActual)){  
echo "La fecha de inicio ha de ser anterior a la fecha Actual";  
}else{  
if($mesInicio <= $mesActual){  
$anios = $anioActual - $anioInicio;  
if($diaInicio <= $diaActual){  
$meses = $mesActual - $mesInicio;  
$dies = $diaActual - $diaInicio;  
}else{  
if($mesActual == $mesInicio){  
$anios = $anios - 1;  
}  
$meses = ($mesActual - $mesInicio - 1 + 12) % 12;  
$dies = $b-($diaInicio-$diaActual);  
}  
}else{  
$anios = $anioActual - $anioInicio - 1;  
if($diaInicio > $diaActual){  
$meses = $mesActual - $mesInicio -1 +12;  
$dies = $b - ($diaInicio-$diaActual);  
}else{  
$meses = $mesActual - $mesInicio + 12;  
$dies = $diaActual - $diaInicio;  
}  
}  
return $anios."|.|".$meses."|.|".$dies;  
}


}





function continuidadAdministrativa($idtrabajador, $tipo){
	$sql_trabajador = mysql_query("select * from trabajador where idtrabajador = '".$idtrabajador."'");
	$bus_trabajador = mysql_fetch_array($sql_trabajador);
	
	$fecha_ingreso = $bus_trabajador["fecha_ingreso"];
	$fecha_inicio_continuidad = $bus_trabajador["fecha_inicio_continuidad"];
	$fecha_actual =  date("Y-m-d");
	
	
	
	
	$result = diferenciaEntreFechas($fecha_ingreso, $fecha_actual);
		list($anioS, $mesS, $diaS) = explode("|.|", $result);
		$sumaAnio += $anioS;
		$sumaMes  += $mesS;
		$sumaDia  += $diaS;
	
	$result2 = diferenciaEntreFechas($fecha_inicio_continuidad, $fecha_ingreso);
	list($anioS, $mesS, $diaS) = explode("|.|", $result2);
		$sumaAnio += $anioS;
		$sumaMes  += $mesS;
		$sumaDia  += $diaS;
	
	
	$sql_tiempo_cesante = mysql_query("select * from periodos_cedentes_trabajadores where idtrabajador = '".$idtrabajador."'");
	while($bus_tiempo_cesante = mysql_fetch_array($sql_tiempo_cesante)){
		$resultadoS = diferenciaEntreFechas($bus_tiempo_cesante["desde"], $bus_tiempo_cesante["hasta"]);
		list($anioS, $mesS, $diaS) = explode("|.|", $resultadoS);
		$sumaAnioCesante += $anioS;
		$sumaMesCesante  += $mesS;
		$sumaDiaCesante  += $diaS;
	}
	
	$totalAnios =  $sumaAnio - $sumaAnioCesante;
	$totalMeses =  $sumaMes  - $sumaMesCesante;
	$totalDias  =  $sumaDia  - $sumaDiaCesante;
	
	if($tipo == "dias"){
		$mostrar = ($totalDias+($totalMeses*30)+($totalAnios*365));
	}else if($tipo == "meses"){
		$mostrar = ($totalMeses+($totalAnios*12));	
	}else{
		$mostrar = $totalAnios;	
	}
	
	//echo "ANIOS: ".$totalAnios." Meses: ".$totalMeses." Dias: ".$totalDias."<br />";
	
	return $mostrar;
	
}


?>