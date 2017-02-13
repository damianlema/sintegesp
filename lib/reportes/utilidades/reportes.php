<?php
session_start();
set_time_limit(-1);
ini_set("memory_limit", "200M");
extract($_GET);
extract($_POST);
//	----------------------------------------
$MAXLINEA=30;
$MAXLINEALEG=15;
//	----------------------------------------
require('../../../conf/conex.php');
require('../../mc_table.php');
require('../../mc_table2.php');
require('../../mc_table3.php');
require('../../mc_table4.php');
require('../../mc_table5.php');
require('../../mc_table6.php');
require('../../mc_table7.php');
require('../../mc_table8.php');
require('../firmas.php');
require('head.php');
//	----------------------------------------
Conectarse();
$ahora=date("d-m-Y H:i:s");
//	----------------------------------------

//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Auditoria...
	case "auditoria":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		//-----------------------------------------
		if ($_GET['cedula']!="todos") {
			$sql="SELECT cedula, nombres, apellidos FROM usuarios WHERE login='".$_GET['cedula']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				if ($field[1]==$field[2]) $usuario=$field[1]; else $usuario=$field[0]." ".$field[1]." ".$field[2];
			}	
		} else $usuario="";
		auditoria($pdf, $_GET['fecha_desde'], $_GET['fecha_hasta'], $usuario);
		//-----------------------------------------
		// REALIZO LA CONEXION A LA BASE DE DATOS DE RESPALDO
		//$dbhost = "192.168.0.10"; 
		//$dbuser = "jose"; 
		//$dbpassword = "1234"; 
			
		$arregloMeses["01"] = "enero";
		$arregloMeses["02"] = "febrero";
		$arregloMeses["03"] = "marzo";
		$arregloMeses["04"] = "abril";
		$arregloMeses["05"] = "mayo";
		$arregloMeses["06"] = "junio";
		$arregloMeses["07"] = "julio";
		$arregloMeses["08"] = "agosto";
		$arregloMeses["09"] = "septiembre";
		$arregloMeses["10"] = "octubre";
		$arregloMeses["11"] = "noviembre";
		$arregloMeses["12"] = "diciembre";		
		
		
		// CREO EL ARREGLO CON TODOS LOS NOMBRES DE LAS TABLAS DE LA BD DE RESPALDO
		$contador = 0;
		for($anio=2009; $anio <= 2014; $anio++){
			for($mes=1; $mes <= 12; $mes++){
				if($mes < 10){
					$mesNuevo = "0".$mes;
				}else{
					$mesNuevo = $mes;
				}
				$arreglo_tablas[$contador] = $arregloMeses[$mesNuevo]."_".$anio;
				$contador++;
			}
		}
		
		// SE CREA UN ARREGLO CON LOS MESES DEL A&ntilde;O		
		$tamanio_arraglo = count($arreglo_tablas);	
		if($fecha_desde != "" and $fecha_hasta != ""){
			// SE CREAN LAS VARIABLES DE MES Y A&ntilde;O DESDE
			$explode_fecha_desde = explode("-",$fecha_desde);
			$mes_fecha_desde = $explode_fecha_desde[1];
			$anio_fecha_desde = $explode_fecha_desde[0];
			
			// SE CREAN LAS VARIABLES DE MES Y A&ntilde;O HASTA
			$explode_fecha_hasta = explode("-",$fecha_hasta);
			$mes_fecha_hasta = $explode_fecha_hasta[1];
			$anio_fecha_hasta = $explode_fecha_hasta[0];
	
			
	
			// SE BUSCA EL INDICE DEL ARREGLO DESDE
			
		
				for($j=0; $j < $tamanio_arraglo; $j++){
					 if($arreglo_tablas[$j] == $arregloMeses[$mes_fecha_desde]."_".$anio_fecha_desde){
							  $indice_desde = $j;
					 }
				}
				
				// SE BUSCA EL INDICE DEEL ARREGLO HASTA
				for($k=0; $k < $tamanio_arraglo; $k++){
					 if($arreglo_tablas[$k] == $arregloMeses[$mes_fecha_hasta]."_".$anio_fecha_hasta){
							  $indice_hasta = $k;
					 }
				}
		}// fin del si que valida si vienen las fechas
		
		
		
		// SE CREA EL QUERY PARA LA CONSULTA
		$sql = "";
		
		
		if($fecha_desde != "" and $fecha_hasta != ""){// si vienen las fechas
		
			if($mes_fecha_desde == $mes_fecha_hasta){
				// HACER UN CODIGO QUE ME PERMITA RECORRER TODAS LAS TABLAS CON DISTINTOS QUERYS
				
					for($i=1; $i<=12; $i++){
						if($i<10){
							$numero = "0".$i;
						}else{
							$numero = $i;
						}
						$sql .= " (select tipo, tabla, usuario, fechayhora, estacion 
										from 
									gestion_respaldo_".$_SESSION["anio_fiscal"].".".$arregloMeses[$numero]."
										where 
									fechayhora between '".$fecha_desde." 00:00:00' and '".$fecha_hasta." 23:59:59' ";
							
							
						if($documento != ""){
							$sql .= "and tipo like '%".$documento."%' ";
						}
						
						if($cedula != "todos"){
							$sql .= "and usuario = '".$cedula."')";
						}else{
							$sql .= ")";
						}
						
						if($i < 12){
							$sql .= " UNION ";
						}else{
							$sql .= " order by fechayhora DESC";
						}
					}
					
				
			}else{
			//echo "INDICE DESDE: ".$indice_hasta;
					
					for($i=1; $i<=12; $i++){
						if($i<10){
							$numero = "0".$i;
						}else{
							$numero = $i;
						}
					
						$sql .= " (select tipo, tabla, usuario, fechayhora, estacion 
										from 
									gestion_respaldo_".$_SESSION["anio_fiscal"].".".$arregloMeses[$numero]."
										where 
									fechayhora between '".$fecha_desde." 00:00:00' and '".$fecha_hasta." 23:59:59' ";
						
						if($documento != ""){
							$sql .= "and tipo like '%".$documento."%' ";
						}
						
						if($cedula != "todos"){
							$sql .= "and usuario = '".$cedula."')";
						}else{
							$sql .= ")";
						}
						
						if($i < 12){
							$sql .= " UNION ";
						}else{
							$sql .= " order by fechayhora DESC";
						}
					}
				}
			
			
		}else{ 	
					for($i=1; $i<=12; $i++){
						if($i<10){
							$numero = "0".$i;
						}else{
							$numero = $i;
						}
					
						$sql .= " (select tipo, tabla, usuario, fechayhora, estacion 
									from 
									gestion_respaldo_".$_SESSION["anio_fiscal"].".".$arregloMeses[$numero]."";
						
						if($documento != ""){
							$sql .= "and tipo like '%".$documento."%' ";
						}
						
						if($cedula != "todos"){
							$sql .= " where usuario = '".$cedula."')";
						}else{
							$sql .= ")";
						}
						
						if($i < 12){
							$sql .= " UNION ";
						}else{
							$sql .= " order by fechayhora DESC";
						}
				
					}
		}
		
		$query = mysql_query($sql)or die(mysql_error());	
		//-----------------------------------------
		if($fecha_desde != "" and $fecha_hasta != ""){
			$query_registro = "select * from registro_transacciones where fechayhora between '".$fecha_desde." 00:00:00' and '".$fecha_hasta." 23:59:59' ";
			if($cedula != "todos"){
				$query_registro .= "and usuario = '".$cedula."' order by fechayhora DESC";
			}else{
				$query_registro .= "order by fechayhora DESC";
			}
		}else{
			$query_registro = "select * from registro_transacciones ";
			if($cedula != "todos"){
				$query_registro .= "where usuario = '".$cedula."' order by fechayhora DESC";
			}else{
				$query_registro .= "order by fechayhora DESC";
			}	
		}	
		$sql_registro_transacciones = mysql_query($query_registro)or die(mysql_error());
		$numero = 1;		
		//-----------------------------------------
		while($bus_registro_transacciones = mysql_fetch_array($sql_registro_transacciones)){
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
			$pdf->SetAligns(array('L', 'L', 'L', 'L', 'L', 'L'));
			$pdf->SetWidths(array(15, 70, 45, 20, 25, 20));
			$pdf->Row(array($numero, $bus_registro_transacciones["tipo"], $bus_registro_transacciones["tabla"], $bus_registro_transacciones["usuario"], $bus_registro_transacciones["fechayhora"], $bus_registro_transacciones["estacion"]));
			$numero++;
			$y=$pdf->GetY(); if ($y>250) auditoria($pdf, $_GET['fecha_desde'], $_GET['fecha_hasta'], $usuario);
		}
		while($bus = mysql_fetch_array($query)){
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
			$pdf->SetAligns(array('L', 'L', 'L', 'L', 'L', 'L'));
			$pdf->SetWidths(array(15, 70, 45, 20, 25, 20));
			$pdf->Row(array($numero, $bus["tipo"], $bus["tabla"], $bus["usuario"], $bus["fechayhora"], $bus["estacion"]));
			$numero++;
			$y=$pdf->GetY(); if ($y>250) auditoria($pdf, $_GET['fecha_desde'], $_GET['fecha_hasta'], $usuario);
		}
		break;
	
}
//	----------------------------------------
//	FIN CUERPO DE LOS REPORTES
//	----------------------------------------

$pdf->Output();
?>