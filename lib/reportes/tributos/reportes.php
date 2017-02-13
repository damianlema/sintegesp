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
	//	Imprimir Retenciones...
	case "emitir_retenciones":
		$pdf=new PDF_MC_Table4('L', 'mm', 'Letter');
		$pdf->SetTopMargin(1);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 1.5);
		$pdf->Open();
		//	-----------------------------------------------------------
		$sql = "SELECT nro_linea_comprobante FROM configuracion_tributos";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$nro_linea_comprobante=$field['nro_linea_comprobante'];
		}
		//	-----------------------------------------------------------
		if ($origen == "tributos_internos") {
			$idorden_pago = $id_emision_pago;
		}
		else {
			$sql = "SELECT idorden_pago FROM pagos_financieros WHERE idpagos_financieros = '".$id_emision_pago."'";
			$query_pagos_financieros = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_pagos_financieros) != 0) {
				$field_pagos_financieros = mysql_fetch_array($query_pagos_financieros);
				$idorden_pago = $field_pagos_financieros[0];
			}
		}
		//	-----------------------------------------------------------
		$mes['01']="ENERO";
		$mes['02']="FEBRERO";
		$mes['03']="MARZO";
		$mes['04']="ABRIL";
		$mes['05']="MAYO";
		$mes['06']="JUNIO";
		$mes['07']="JULIO";
		$mes['08']="AGOSTO";
		$mes['09']="SEPTIEMBRE";
		$mes['10']="OCTUBRE";
		$mes['11']="NOVIEMBRE";
		$mes['12']="DICIEMBRE";
		//	Datos Agente de Retencion
		$query=mysql_query("SELECT nombre_institucion, domicilio_legal, ciudad, estado, rif FROM configuracion") or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$nombre_agente=$field['nombre_institucion'];
			$rif_agente=$field['rif'];
			$queryEstado=mysql_query("SELECT denominacion FROM estado where idestado = '".$field['estado']."'") or die ($sql.mysql_error());
			$fieldEstado=mysql_fetch_array($queryEstado);
			$direccion_fiscal=$field['domicilio_legal'];
		}
		//	Datos Sujeto Retenido
		if($estado == "procesado"){
			$sql="SELECT beneficiarios.nombre,
						beneficiarios.rif,
						beneficiarios.direccion,
						retenciones.iddocumento,
						retenciones.numero_documento,
						retenciones.idretenciones,
						retenciones.fecha_aplicacion_retencion, 
						relacion_retenciones.periodo, 
						relacion_retenciones.numero_retencion,
						relacion_retenciones.fecha_comprobante, 
						orden_pago.justificacion,
						tipo_retencion.nombre_comprobante 
					FROM
						orden_pago 
						INNER JOIN relacion_orden_pago_retencion ON (orden_pago.idorden_pago = relacion_orden_pago_retencion.idorden_pago)

						  INNER JOIN retenciones ON (relacion_orden_pago_retencion.idretencion=retenciones.idretenciones)
						  INNER JOIN relacion_retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones AND relacion_retenciones.generar_comprobante = 'si')
						  INNER JOIN tipo_retencion ON (relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion AND tipo_retencion.nombre_comprobante='IVA')
						  INNER JOIN orden_compra_servicio ON (retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio)
              			  INNER JOIN beneficiarios ON (beneficiarios.idbeneficiarios=orden_compra_servicio.idbeneficiarios)
          WHERE
						orden_pago.idorden_pago='".$idorden_pago."'";		
			$query=mysql_query($sql) or die ($sql.mysql_error());
			while ($field=mysql_fetch_array($query)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_aplicacion_retencion']); $fecha=$d." DE ".$mes[$m]." DE ".$a; $lafecha="$d/$m/$a";		
				$nombre_sujeto=$field['nombre'];
				$rif_sujeto=$field['rif'];
				$periodo_fiscal=$field['periodo'];	
				//********************************************** SALUD
				//$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				if ((8-strlen($field['numero_retencion'])) > 0){
					$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				}else{
					$numero_retencion=(string) $periodo_fiscal.$field['numero_retencion'];
				}
				
				//****************************************************************************
				//$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				$justificacion=$field['justificacion'];
				$direccion_sujeto=$field["direccion"];
			}
			//	-----------------------------------------------------------	
			$sql="SELECT   orden_pago.idorden_pago,
							  retenciones.iddocumento,
							  retenciones.fecha_factura,
							  retenciones.numero_factura,
							  retenciones.numero_control,
							  retenciones.exento,
							  retenciones.base,
							  retenciones.impuesto,
							  retenciones.fecha_aplicacion_retencion,
							  retenciones.total,
							  relacion_retenciones.porcentaje_aplicado,
							  relacion_retenciones.porcentaje_impuesto,
							  relacion_retenciones.monto_retenido,
							  relacion_retenciones.base_calculo,
							  orden_compra_servicio.numero_orden,
							  retenciones.idretenciones,
							  tipo_retencion.nombre_comprobante

					  FROM orden_pago
					
						  INNER JOIN relacion_orden_pago_retencion ON (orden_pago.idorden_pago = relacion_orden_pago_retencion.idorden_pago)
					
						  INNER JOIN retenciones ON (relacion_orden_pago_retencion.idretencion=retenciones.idretenciones)
						  INNER JOIN relacion_retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones AND relacion_retenciones.generar_comprobante = 'si')
						  INNER JOIN tipo_retencion ON (relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion AND tipo_retencion.nombre_comprobante='IVA')
						  INNER JOIN orden_compra_servicio ON (retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio)
					  WHERE orden_pago.idorden_pago='".$idorden_pago."'";
			
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				emitir_retenciones($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto);
				//	-----------------------------------------------------------
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 10);
				$pdf->SetAligns(array('C', 'C', 'L', 'L', 'C', 'C', 'C', 'C', 'R', 'R', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(8, 20, 20, 26, 17, 19, 6, 17, 25, 25, 25, 12, 25, 24));
				//	-----------------------------------------------------------
				for ($i=1; $i<=$rows; $i++) {
					$field=mysql_fetch_array($query);
					list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_factura']); $fecha_factura=$d."/".$m."/".$a;
					$exento=number_format($field['exento'], 2, ',', '.');
					$porcentaje_impuesto=number_format($field['porcentaje_impuesto'], 2, ',', '.');
					$base_calculo=number_format($field['impuesto'], 2, ',', '.'); $total_base_calculo+=$field['impuesto'];
					$monto_retenido=number_format($field['monto_retenido'], 2, ',', '.'); $total_monto_retenido+=$field['monto_retenido'];
					$base=number_format($field['base'], 2, ',', '.'); $total_base+=$field['base'];
					$total=number_format($field['total'], 2, ',', '.'); $monto_total+=$field['total'];
					$porcentaje_aplicado=number_format($field['porcentaje_aplicado'], 2, ',', '.');
					$pdf->Row(array($i, $fecha_factura, $field['numero_factura'], $field['numero_control'], '', '', 'C', '1', $total, $exento, $base, $porcentaje_impuesto, $base_calculo, $monto_retenido));
					$linea=$pdf->GetY(); if ($linea>170) { 
						emitir_retenciones($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto);
					}
				}
				$monto_total=number_format($monto_total, 2, ',', '.');
				$total_base=number_format($total_base, 2, ',', '.');
				$total_base_calculo=number_format($total_base_calculo, 2, ',', '.');
				$total_monto_retenido=number_format($total_monto_retenido, 2, ',', '.');
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->SetAligns(array('C', 'R', 'C', 'R', 'C', 'R', 'R'));
				$pdf->SetWidths(array(133, 25, 25, 25, 12, 25, 24));
				$pdf->Row(array('TOTALES', $monto_total, '', $total_base, '', $total_base_calculo, $total_monto_retenido));
				$pdf->Ln(5);
			}
		
			//	------------------------------------
			//	Datos Sujeto Retenido
			$sql="SELECT beneficiarios.nombre,
						beneficiarios.rif,
						beneficiarios.direccion,
						retenciones.iddocumento,
						retenciones.numero_documento,
						retenciones.idretenciones,
						retenciones.fecha_aplicacion_retencion, 
						relacion_retenciones.periodo, 
						relacion_retenciones.numero_retencion,
						relacion_retenciones.fecha_comprobante, 
						orden_pago.justificacion,
						tipo_retencion.nombre_comprobante 
					FROM
						orden_pago 
						INNER JOIN relacion_orden_pago_retencion ON (orden_pago.idorden_pago = relacion_orden_pago_retencion.idorden_pago)

						  INNER JOIN retenciones ON (relacion_orden_pago_retencion.idretencion=retenciones.idretenciones)
						  INNER JOIN relacion_retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones AND relacion_retenciones.generar_comprobante = 'si')
						  INNER JOIN tipo_retencion ON (relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion AND tipo_retencion.nombre_comprobante='ISLR')
						  INNER JOIN orden_compra_servicio ON (retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio)
						  INNER JOIN beneficiarios ON (beneficiarios.idbeneficiarios=orden_compra_servicio.idbeneficiarios)
					  WHERE
						orden_pago.idorden_pago='".$idorden_pago."'";
						
						
			
			$query=mysql_query($sql) or die ($sql.mysql_error());
			while ($field=mysql_fetch_array($query)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_aplicacion_retencion']); $fecha=$d." DE ".$mes[$m]." DE ".$a; $lafecha="$d/$m/$a";		
				$nombre_sujeto=$field['nombre'];
				$rif_sujeto=$field['rif'];
				$periodo_fiscal=$field['periodo'];		
				//$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				if ((8-strlen($field['numero_retencion'])) > 0){
					$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				}else{
					$numero_retencion=(string) $periodo_fiscal.$field['numero_retencion'];
				}
				$justificacion=substr($field['justificacion'],0,552);
			}
			//	Imprimo el impuesto sobre la renta
			$sql="SELECT   orden_pago.idorden_pago,
							  retenciones.iddocumento,
							  retenciones.fecha_factura,
							  retenciones.numero_factura,
							  retenciones.numero_control,
							  retenciones.exento,
							  retenciones.base,
							  retenciones.impuesto,
							  retenciones.fecha_aplicacion_retencion,
							  retenciones.total,
							  relacion_retenciones.porcentaje_aplicado,
							  relacion_retenciones.porcentaje_impuesto,
							  relacion_retenciones.monto_retenido,
							  relacion_retenciones.base_calculo,
							  orden_compra_servicio.numero_orden,
							  retenciones.idretenciones,
							  tipo_retencion.nombre_comprobante

					  FROM orden_pago
					
						  INNER JOIN relacion_orden_pago_retencion ON (orden_pago.idorden_pago = relacion_orden_pago_retencion.idorden_pago)
					
						  INNER JOIN retenciones ON (relacion_orden_pago_retencion.idretencion=retenciones.idretenciones)
						  INNER JOIN relacion_retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones AND relacion_retenciones.generar_comprobante = 'si')
						  INNER JOIN tipo_retencion ON (relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion AND tipo_retencion.nombre_comprobante='ISLR')
						  INNER JOIN orden_compra_servicio ON (retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio)
					  WHERE orden_pago.idorden_pago='".$idorden_pago."'";
						
						
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {		
				emitir_retenciones_islr($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto);
				//	-----------------------------------------------------------
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 10);
				$pdf->SetAligns(array('C', 'C', 'L', 'L', 'C', 'C', 'C', 'C', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(10, 25, 22, 22, 20, 21, 10, 17, 35, 35, 18, 35));
				//	-----------------------------------------------------------	
				$total_base=0; $total_base_calculo=0; $monto_total=0; $total_monto_retenido=0;
				for ($i=1; $i<=$rows; $i++) {
					$field=mysql_fetch_array($query);
					list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_factura']); $fecha_factura=$d."/".$m."/".$a;
					$exento=number_format($field['exento'], 2, ',', '.');
					$porcentaje_impuesto=number_format($field['porcentaje_impuesto'], 2, ',', '.');
					$base_calculo=number_format($field['impuesto'], 2, ',', '.'); $total_base_calculo+=$field['impuesto'];
					$monto_retenido=number_format($field['monto_retenido'], 2, ',', '.'); $total_monto_retenido+=$field['monto_retenido'];
					$base=number_format($field['base_calculo'], 2, ',', '.'); $total_base+=$field['base_calculo'];
					$total=number_format($field['total'], 2, ',', '.'); $monto_total+=$field['total'];
					$porcentaje_aplicado=number_format($field['porcentaje_aplicado'], 2, ',', '.');
					$pdf->Row(array($i, $fecha_factura, $field['numero_factura'], $field['numero_control'], '', '', 'C', '1', $total, $base, $porcentaje_aplicado, $monto_retenido));
					$linea=$pdf->GetY(); if ($linea>170) { 
						emitir_retenciones_islr($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto);
					}
				}
				$monto_total=number_format($monto_total, 2, ',', '.');
				$total_base=number_format($total_base, 2, ',', '.');
				$total_base_calculo=number_format($total_base_calculo, 2, ',', '.');
				$total_monto_retenido=number_format($total_monto_retenido, 2, ',', '.');
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->SetAligns(array('C', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(147, 35, 35, 18, 35));
				$pdf->Row(array('TOTALES', $monto_total, $total_base, '', $total_monto_retenido));
				$pdf->Ln(5);
			}
		
			//	------------------------------------ UNO POR MIL DELTA AMACURO --------------------------------------------------------------------
			//	Datos Sujeto Retenido
			$sql="SELECT beneficiarios.nombre,
						beneficiarios.rif,
						beneficiarios.direccion,
						retenciones.iddocumento,
						retenciones.numero_documento,
						retenciones.idretenciones,
						retenciones.fecha_aplicacion_retencion, 
						relacion_retenciones.periodo, 
						relacion_retenciones.numero_retencion,
						relacion_retenciones.fecha_comprobante, 
						orden_pago.justificacion,
						tipo_retencion.nombre_comprobante 
					FROM
						orden_pago 
						INNER JOIN relacion_orden_pago_retencion ON (orden_pago.idorden_pago = relacion_orden_pago_retencion.idorden_pago)

						  INNER JOIN retenciones ON (relacion_orden_pago_retencion.idretencion=retenciones.idretenciones)
						  INNER JOIN relacion_retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones AND relacion_retenciones.generar_comprobante = 'si')
						  INNER JOIN tipo_retencion ON (relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion AND tipo_retencion.nombre_comprobante='1x1000')
						  INNER JOIN orden_compra_servicio ON (retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio)
						  INNER JOIN beneficiarios ON (beneficiarios.idbeneficiarios=orden_compra_servicio.idbeneficiarios)
					  WHERE
						orden_pago.idorden_pago='".$idorden_pago."'";
			
			
			
			
			$query=mysql_query($sql) or die ($sql.mysql_error());
			while ($field=mysql_fetch_array($query)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_aplicacion_retencion']); $fecha=$d." DE ".$mes[$m]." DE ".$a; $lafecha="$d/$m/$a";
				$nombre_sujeto=$field['nombre'];
				$rif_sujeto=$field['rif'];
				$direccion_sujeto = $field["direccion"];
				$periodo_fiscal=$field['periodo'];		
				//$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				if ((8-strlen($field['numero_retencion'])) > 0){
					$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				}else{
					$numero_retencion=(string) $periodo_fiscal.$field['numero_retencion'];
				}
				$justificacion=substr($field['justificacion'],0,560);
			}
			
			//	Imprimo el uno por mil
			$sql="SELECT   	orden_pago.idorden_pago,
							orden_pago.porcentaje_anticipo,
							orden_pago.numero_orden,
							orden_pago.fecha_orden, 
						  	retenciones.iddocumento as idorden_compra_servicio,
						 	retenciones.fecha_factura,
						  	retenciones.numero_factura,
						  	retenciones.numero_control,
						  	retenciones.exento,
						  	retenciones.base,
						  	retenciones.impuesto,
						  	retenciones.fecha_aplicacion_retencion,
						  	retenciones.total,
						  	relacion_retenciones.porcentaje_aplicado,
						  	relacion_retenciones.porcentaje_impuesto,
						  	relacion_retenciones.monto_retenido,
						  	relacion_retenciones.base_calculo,
						  	orden_compra_servicio.numero_orden AS numero_orden_compra,
						  	retenciones.idretenciones,
						  	tipo_retencion.nombre_comprobante

					  FROM orden_pago
					
						  INNER JOIN relacion_orden_pago_retencion ON (orden_pago.idorden_pago = relacion_orden_pago_retencion.idorden_pago)
					
						  INNER JOIN retenciones ON (relacion_orden_pago_retencion.idretencion=retenciones.idretenciones)
						  INNER JOIN relacion_retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones AND relacion_retenciones.generar_comprobante = 'si')
						  INNER JOIN tipo_retencion ON (relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion AND tipo_retencion.nombre_comprobante='1x1000')
						  INNER JOIN orden_compra_servicio ON (retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio)
					  WHERE orden_pago.idorden_pago='".$idorden_pago."'";
			
			
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			$total_contrato = 0;
			if ($rows!=0) {	
				$query2 = mysql_query($sql) or die ($sql.mysql_error());
				while($sumatotalcontrato = mysql_fetch_array($query2)){
					$sql_compromiso = mysql_query("select * from orden_compra_servicio 
														where idorden_compra_servicio = '".$sumatotalcontrato["idorden_compra_servicio"]."'");
					$monto_compromiso = mysql_fetch_array($sql_compromiso);
					$total_contrato += $monto_compromiso["total"]; 
				}
			}
			if ($rows!=0) {	
				emitir_retenciones_uno($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto, $justificacion, $direccion_sujeto, $total_contrato);
				//	-----------------------------------------------------------
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 10);
				$pdf->SetAligns(array('C', 'C', 'C', 'C', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(10, 30, 30, 40, 40, 40, 40, 40));
				//	-----------------------------------------------------------	
				$total_base=0; $total_base_calculo=0; $monto_total=0; $total_monto_retenido=0;
				//$pdf->Ln(13);
				for ($i=1; $i<=$rows; $i++) {
					$field=mysql_fetch_array($query);
					list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha_orden=$d."/".$m."/".$a;
					$exento=number_format($field['exento'], 2, ',', '.');
					$porcentaje_impuesto=number_format($field['porcentaje_impuesto'], 2, ',', '.');
					$base_calculo=number_format($field['impuesto'], 2, ',', '.'); $total_base_calculo+=$field['impuesto'];
					$monto_retenido=number_format($field['monto_retenido'], 2, ',', '.'); $total_monto_retenido+=$field['monto_retenido'];
					$base=number_format($field['base_calculo'], 2, ',', '.'); $total_base+=$field['base_calculo'];
					$total=number_format($field['total'], 2, ',', '.'); $monto_total+=$field['total'];
					$porcentaje_aplicado=number_format($field['porcentaje_aplicado'], 2, ',', '.');
					$pdf->Row(array($i, $field['numero_orden'], $fecha_orden, $field["porcentaje_anticipo"], $base, $monto_retenido, '0,00', $monto_retenido));
					$linea=$pdf->GetY(); if ($linea>170) { 
						emitir_retenciones_uno($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto, $justificacion, $direccion_sujeto, $total_contrato);
					}
				}
				//$pdf->Ln();
				$monto_total=number_format($monto_total, 2, ',', '.');
				$total_base=number_format($total_base, 2, ',', '.');
				$total_base_calculo=number_format($total_base_calculo, 2, ',', '.');
				$total_monto_retenido=number_format($total_monto_retenido, 2, ',', '.');
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->SetAligns(array('C', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(110, 40, 40, 40, 40));
				$pdf->Row(array('TOTALES', $total_base, $total_monto_retenido, '', $total_monto_retenido));
				$pdf->Ln(5);
			}



//	------------------------------------ UNO POR MIL MONAGAS--------------------------------------------------------------------
			//	Datos Sujeto Retenido
			$sql="SELECT beneficiarios.nombre,
						beneficiarios.rif,
						beneficiarios.direccion,
						retenciones.iddocumento,
						retenciones.numero_documento,
						retenciones.idretenciones,
						retenciones.fecha_aplicacion_retencion, 
						relacion_retenciones.periodo, 
						relacion_retenciones.numero_retencion,
						relacion_retenciones.fecha_comprobante, 
						orden_pago.justificacion,
						tipo_retencion.nombre_comprobante 
					FROM
						orden_pago 
						INNER JOIN relacion_orden_pago_retencion ON (orden_pago.idorden_pago = relacion_orden_pago_retencion.idorden_pago)

						  INNER JOIN retenciones ON (relacion_orden_pago_retencion.idretencion=retenciones.idretenciones)
						  INNER JOIN relacion_retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones AND relacion_retenciones.generar_comprobante = 'si')
						  INNER JOIN tipo_retencion ON (relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion AND tipo_retencion.nombre_comprobante='1x1000M')
						  INNER JOIN orden_compra_servicio ON (retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio)
						  INNER JOIN beneficiarios ON (beneficiarios.idbeneficiarios=orden_compra_servicio.idbeneficiarios)
					  WHERE
						orden_pago.idorden_pago='".$idorden_pago."'";
			
			
			
			
			$query=mysql_query($sql) or die ($sql.mysql_error());
			while ($field=mysql_fetch_array($query)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_aplicacion_retencion']); $fecha=$d." DE ".$mes[$m]." DE ".$a; $lafecha="$d/$m/$a";
				$nombre_sujeto=$field['nombre'];
				$rif_sujeto=$field['rif'];
				$direccion_sujeto = $field["direccion"];
				$periodo_fiscal=$field['periodo'];		
				//$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				if ((8-strlen($field['numero_retencion'])) > 0){
					$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				}else{
					$numero_retencion=(string) $periodo_fiscal.$field['numero_retencion'];
				}
				$justificacion=substr($field['justificacion'],0,560);
			}
			
			//	Imprimo el uno por mil
			$sql="SELECT   	orden_pago.idorden_pago,
							orden_pago.porcentaje_anticipo,
							orden_pago.numero_orden,
							orden_pago.fecha_orden, 
						  	retenciones.iddocumento as idorden_compra_servicio,
						 	retenciones.fecha_factura,
						  	retenciones.numero_factura,
						  	retenciones.numero_control,
						  	retenciones.exento,
						  	retenciones.base,
						  	retenciones.impuesto,
						  	retenciones.fecha_aplicacion_retencion,
						  	retenciones.total,
						  	relacion_retenciones.porcentaje_aplicado,
						  	relacion_retenciones.porcentaje_impuesto,
						  	relacion_retenciones.monto_retenido,
						  	relacion_retenciones.base_calculo,
						  	orden_compra_servicio.numero_orden AS numero_orden_compra,
						  	retenciones.idretenciones,
						  	tipo_retencion.nombre_comprobante,
							tipo_retencion.divisor

					  FROM orden_pago
					
						  INNER JOIN relacion_orden_pago_retencion ON (orden_pago.idorden_pago = relacion_orden_pago_retencion.idorden_pago)
					
						  INNER JOIN retenciones ON (relacion_orden_pago_retencion.idretencion=retenciones.idretenciones)
						  INNER JOIN relacion_retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones AND relacion_retenciones.generar_comprobante = 'si')
						  INNER JOIN tipo_retencion ON (relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion AND tipo_retencion.nombre_comprobante='1x1000M')
						  INNER JOIN orden_compra_servicio ON (retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio)
					  WHERE orden_pago.idorden_pago='".$idorden_pago."'";
			
			
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			$total_contrato = 0;
			if ($rows!=0) {	
				$query2 = mysql_query($sql) or die ($sql.mysql_error());
				while($sumatotalcontrato = mysql_fetch_array($query2)){
					$sql_compromiso = mysql_query("select * from orden_compra_servicio 
														where idorden_compra_servicio = '".$sumatotalcontrato["idorden_compra_servicio"]."'");
					$monto_compromiso = mysql_fetch_array($sql_compromiso);
					$total_contrato += $monto_compromiso["total"]; 
				}
			}
			if ($rows!=0) {	
				emitir_retenciones_uno_monagas($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto, $justificacion, $direccion_sujeto, $total_contrato);
				//	-----------------------------------------------------------
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 10);
				$pdf->SetAligns(array('C', 'C', 'C', 'C', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(10, 30, 30, 40, 40, 40, 40, 40));
				//	-----------------------------------------------------------	
				$total_base=0; $total_base_calculo=0; $monto_total=0; $total_monto_retenido=0;
				//$pdf->Ln(13);
				for ($i=1; $i<=$rows; $i++) {
					$field=mysql_fetch_array($query);
					list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha_orden=$d."/".$m."/".$a;
					$exento=number_format($field['exento'], 2, ',', '.');
					$porcentaje_impuesto=number_format($field['porcentaje_impuesto'], 2, ',', '.');
					$base_calculo=number_format($field['impuesto'], 2, ',', '.'); $total_base_calculo+=$field['impuesto'];
					$monto_retenido=number_format($field['monto_retenido'], 2, ',', '.'); $total_monto_retenido+=$field['monto_retenido'];
					$base=number_format($field['base_calculo'], 2, ',', '.'); $total_base+=$field['base_calculo'];
					$total=number_format($field['total'], 2, ',', '.'); $monto_total+=$field['total'];
					$porcentaje_aplicado=number_format($field['porcentaje_aplicado']/$field['divisor'], 3, ',', '.');
					$pdf->Row(array($i, $field['numero_orden'], $fecha_orden, $field["porcentaje_anticipo"], $base, '0,001', $monto_retenido,  $monto_retenido));
					$linea=$pdf->GetY(); if ($linea>170) { 
						emitir_retenciones_uno_monagas($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto, $justificacion, $direccion_sujeto, $total_contrato);
					}
				}
				//$pdf->Ln();
				$monto_total=number_format($monto_total, 2, ',', '.');
				$total_base=number_format($total_base, 2, ',', '.');
				$total_base_calculo=number_format($total_base_calculo, 2, ',', '.');
				$total_monto_retenido=number_format($total_monto_retenido, 2, ',', '.');
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->SetAligns(array('C', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(110, 40, 40, 40, 40));
				$pdf->Row(array('TOTALES', $total_base, '', $total_monto_retenido, $total_monto_retenido));
				$pdf->Ln(5);
			}
			






			
			//	------------------------------------ MUNICIPAL --------------------------------------------------------------------
			//	Datos Sujeto Retenido
			$sql="SELECT beneficiarios.nombre,
						beneficiarios.rif,
						beneficiarios.direccion,
						retenciones.iddocumento,
						retenciones.numero_documento,
						retenciones.idretenciones,
						retenciones.fecha_aplicacion_retencion, 
						relacion_retenciones.periodo, 
						relacion_retenciones.numero_retencion,
						relacion_retenciones.fecha_comprobante, 
						orden_pago.justificacion,
						tipo_retencion.nombre_comprobante 
					FROM
						orden_pago 
						INNER JOIN relacion_orden_pago_retencion ON (orden_pago.idorden_pago = relacion_orden_pago_retencion.idorden_pago)

						  INNER JOIN retenciones ON (relacion_orden_pago_retencion.idretencion=retenciones.idretenciones)
						  INNER JOIN relacion_retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones AND relacion_retenciones.generar_comprobante = 'si')
						  INNER JOIN tipo_retencion ON (relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion AND tipo_retencion.nombre_comprobante='municipal')
						  INNER JOIN orden_compra_servicio ON (retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio)
						  INNER JOIN beneficiarios ON (beneficiarios.idbeneficiarios=orden_compra_servicio.idbeneficiarios)
					  WHERE
						orden_pago.idorden_pago='".$idorden_pago."'";
			
			
			
			
			$query=mysql_query($sql) or die ($sql.mysql_error());
			while ($field=mysql_fetch_array($query)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_aplicacion_retencion']); $fecha=$d." DE ".$mes[$m]." DE ".$a; $lafecha="$d/$m/$a";
				$nombre_sujeto=$field['nombre'];
				$rif_sujeto=$field['rif'];
				$direccion_sujeto = $field["direccion"];
				$periodo_fiscal=$field['periodo'];		
				//$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				if ((8-strlen($field['numero_retencion'])) > 0){
					$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				}else{
					$numero_retencion=(string) $periodo_fiscal.$field['numero_retencion'];
				}
				$justificacion=substr($field['justificacion'],0,560);
			}
			
			//	Imprimo el uno por mil
			$sql="SELECT   	orden_pago.idorden_pago,
							orden_pago.porcentaje_anticipo,
							orden_pago.numero_orden,
							orden_pago.fecha_orden, 
						  	retenciones.iddocumento as idorden_compra_servicio,
						 	retenciones.fecha_factura,
						  	retenciones.numero_factura,
						  	retenciones.numero_control,
						  	retenciones.exento,
						  	retenciones.base,
						  	retenciones.impuesto,
						  	retenciones.fecha_aplicacion_retencion,
						  	retenciones.total,
						  	relacion_retenciones.porcentaje_aplicado,
						  	relacion_retenciones.porcentaje_impuesto,
						  	relacion_retenciones.monto_retenido,
						  	relacion_retenciones.base_calculo,
						  	orden_compra_servicio.numero_orden AS numero_orden_compra,
						  	retenciones.idretenciones,
						  	tipo_retencion.nombre_comprobante

					  FROM orden_pago
					
						  INNER JOIN relacion_orden_pago_retencion ON (orden_pago.idorden_pago = relacion_orden_pago_retencion.idorden_pago)
					
						  INNER JOIN retenciones ON (relacion_orden_pago_retencion.idretencion=retenciones.idretenciones)
						  INNER JOIN relacion_retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones AND relacion_retenciones.generar_comprobante = 'si')
						  INNER JOIN tipo_retencion ON (relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion AND tipo_retencion.nombre_comprobante='municipal')
						  INNER JOIN orden_compra_servicio ON (retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio)
					  WHERE orden_pago.idorden_pago='".$idorden_pago."'";
			
			
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			$total_contrato = 0;
			if ($rows!=0) {	
				$query2 = mysql_query($sql) or die ($sql.mysql_error());
				while($sumatotalcontrato = mysql_fetch_array($query2)){
					$sql_compromiso = mysql_query("select * from orden_compra_servicio 
														where idorden_compra_servicio = '".$sumatotalcontrato["idorden_compra_servicio"]."'");
					$monto_compromiso = mysql_fetch_array($sql_compromiso);
					$total_contrato += $monto_compromiso["total"]; 
				}
			}
			if ($rows!=0) {	
				emitir_retenciones_municipal($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto, $justificacion, $direccion_sujeto, $total_contrato);
				//	-----------------------------------------------------------
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 10);
				$pdf->SetAligns(array('C', 'C', 'C', 'C', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(10, 30, 30, 40, 35, 25, 50, 50));
				//	-----------------------------------------------------------	
				$total_base=0; $total_base_calculo=0; $monto_total=0; $total_monto_retenido=0;
				//$pdf->Ln(13);
				for ($i=1; $i<=$rows; $i++) {
					$field=mysql_fetch_array($query);
					list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha_orden=$d."/".$m."/".$a;
					list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_factura']); $fecha_factura=$d."/".$m."/".$a;
					$exento=number_format($field['exento'], 2, ',', '.');
					$porcentaje_impuesto=number_format($field['porcentaje_impuesto'], 2, ',', '.');
					$base_calculo=number_format($field['impuesto'], 2, ',', '.'); $total_base_calculo+=$field['impuesto'];
					$monto_retenido=number_format($field['monto_retenido'], 2, ',', '.'); $total_monto_retenido+=$field['monto_retenido'];
					$base=number_format($field['base_calculo'], 2, ',', '.'); $total_base+=$field['base_calculo'];
					$total=number_format($field['total'], 2, ',', '.'); $monto_total+=$field['total'];
					$porcentaje_aplicado=number_format($field['porcentaje_aplicado'], 2, ',', '.');
					$pdf->Row(array($i, $field['numero_factura'], $field['numero_control'], $fecha_factura, $fecha_orden, $porcentaje_aplicado, $base, $monto_retenido));
					$linea=$pdf->GetY(); if ($linea>170) { 
						emitir_retenciones_municipal($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto, $justificacion, $direccion_sujeto, $total_contrato);
					}
				}
				//$pdf->Ln();
				$monto_total=number_format($monto_total, 2, ',', '.');
				$total_base=number_format($total_base, 2, ',', '.');
				$total_base_calculo=number_format($total_base_calculo, 2, ',', '.');
				$total_monto_retenido=number_format($total_monto_retenido, 2, ',', '.');
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->SetAligns(array('C', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(170, 50, 50));
				$pdf->Row(array('TOTALES', $total_base, $total_monto_retenido));
				$pdf->Ln(5);
			}
			
		} else {
			// ELSE DE SI ES PROCESADO O ANULADO EL TIPO DE CONSULTA ************************************************************************************
			//**************************************************************************************************************************************************
			//**************************************************************************************************************************************************
			$sql="SELECT beneficiarios.nombre,
						beneficiarios.rif, 
						relacion_pago_compromisos.idorden_compra_servicio, 
						orden_compra_servicio.numero_orden, 
						retenciones.idretenciones, 
						retenciones.fecha_retencion, 
						comprobantes_retenciones.periodo, 
						comprobantes_retenciones.numero_retencion,
						comprobantes_retenciones.fecha_retencion, 
						orden_pago.justificacion, 
						tipo_retencion.nombre_comprobante 
					FROM 
						orden_pago 
						INNER JOIN relacion_pago_compromisos ON (orden_pago.idorden_pago=relacion_pago_compromisos.idorden_pago) 
						INNER JOIN orden_compra_servicio ON (relacion_pago_compromisos.idorden_compra_servicio=orden_compra_servicio.idorden_compra_servicio) 
						INNER JOIN retenciones ON (orden_compra_servicio.idorden_compra_servicio=retenciones.iddocumento) 
						INNER JOIN beneficiarios ON (beneficiarios.idbeneficiarios=orden_pago.idbeneficiarios) 
						INNER JOIN comprobantes_retenciones ON (retenciones.idretenciones=comprobantes_retenciones.idretenciones and comprobantes_retenciones.estado = 'anulado')  
						INNER JOIN tipo_retencion ON (comprobantes_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion AND tipo_retencion.nombre_comprobante='IVA') 
					WHERE 
						orden_pago.idorden_pago='".$idorden_pago."'";	
			$query=mysql_query($sql) or die ("AQUI".$sql.mysql_error());
			while ($field=mysql_fetch_array($query)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_retencion']); $fecha=$d." DE ".$mes[$m]." DE ".$a; $lafecha="$d/$m/$a";		
				$nombre_sujeto=$field['nombre'];
				$rif_sujeto=$field['rif'];
				$periodo_fiscal=$field['periodo'];		
				//$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				if ((8-strlen($field['numero_retencion'])) > 0){
					$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				}else{
					$numero_retencion=(string) $periodo_fiscal.$field['numero_retencion'];
				}
				$justificacion=$field['justificacion'];
				$sql2="SELECT 
							orden_pago.idorden_pago, 
							relacion_pago_compromisos.idorden_compra_servicio, 
							retenciones.fecha_factura, 
							retenciones.numero_factura, 
							retenciones.numero_control, 
							retenciones.exento, 
							retenciones.base, 
							retenciones.fecha_retencion, 
							retenciones.total, 
							relacion_retenciones.porcentaje_aplicado, 
							relacion_retenciones.porcentaje_impuesto, 
							relacion_retenciones.monto_retenido, 
							relacion_retenciones.base_calculo, 
							orden_compra_servicio.numero_orden, 
							retenciones.idretenciones, 
							tipo_retencion.nombre_comprobante, 
							orden_compra_servicio.impuesto 
						FROM 
							orden_pago 
							INNER JOIN relacion_pago_compromisos ON (orden_pago.idorden_pago=relacion_pago_compromisos.idorden_pago)
							INNER JOIN orden_compra_servicio ON (relacion_pago_compromisos.idorden_compra_servicio=orden_compra_servicio.idorden_compra_servicio) 
							INNER JOIN retenciones ON (orden_compra_servicio.idorden_compra_servicio=retenciones.iddocumento) 
							INNER JOIN relacion_retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones) 
							INNER JOIN tipo_retencion ON (relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion AND tipo_retencion.nombre_comprobante='IVA')
						WHERE 
							orden_pago.idorden_pago='".$idorden_pago."'";
				$query2=mysql_query($sql2) or die ($sql2.mysql_error());
				$rows2=mysql_num_rows($query2);
				if ($rows2!=0) {
					emitir_retenciones($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto);
					//	-----------------------------------------------------------
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 10);
					$pdf->SetAligns(array('C', 'C', 'L', 'L', 'C', 'C', 'C', 'C', 'R', 'R', 'R', 'R', 'R', 'R'));
					$pdf->SetWidths(array(8, 22, 20, 20, 17, 19, 8, 17, 25, 25, 25, 13, 25, 25));
					//	-----------------------------------------------------------
					for ($i=1; $i<=$rows2; $i++) {
						$field2=mysql_fetch_array($query2);
						list($a, $m, $d)=SPLIT( '[/.-]', $field2['fecha_factura']); $fecha_factura=$d."/".$m."/".$a;
						$exento=number_format($field2['exento'], 2, ',', '.');
						$porcentaje_impuesto=number_format($field2['porcentaje_impuesto'], 2, ',', '.');
						$base_calculo=number_format($field2['impuesto'], 2, ',', '.'); $total_base_calculo+=$field2['impuesto'];
						$monto_retenido=number_format($field2['monto_retenido'], 2, ',', '.'); $total_monto_retenido+=$field2['monto_retenido'];
						$base=number_format($field2['base_calculo'], 2, ',', '.'); $total_base+=$field2['base_calculo'];
						$total=number_format($field2['total'], 2, ',', '.'); $monto_total+=$field2['total'];
						$porcentaje_aplicado=number_format($field2['porcentaje_aplicado'], 2, ',', '.');
						$pdf->Row(array($i, $fecha_factura, $field2['numero_factura'], $field2['numero_control'], '', '', 'C', '1', $total, $exento, $base, $porcentaje_impuesto, $base_calculo, $monto_retenido));
						$linea=$pdf->GetY(); if ($linea>170) { 
							emitir_retenciones($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto);
						}
					}
					$monto_total=number_format($monto_total, 2, ',', '.');
					$total_base=number_format($total_base, 2, ',', '.');
					$total_base_calculo=number_format($total_base_calculo, 2, ',', '.');
					$total_monto_retenido=number_format($total_monto_retenido, 2, ',', '.');
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 10);
					$pdf->SetAligns(array('C', 'R', 'C', 'R', 'C', 'R', 'R'));
					$pdf->SetWidths(array(131, 25, 25, 25, 13, 25, 25));
					$pdf->Row(array('SUBTOTAL', $monto_total, '', $total_base, '', $total_base_calculo, $total_monto_retenido));
					$pdf->Ln(5);
				}
			}
		
			//	------------------------------------
			//	Datos Sujeto Retenido
			$sql="SELECT 
						beneficiarios.nombre, 
						beneficiarios.rif, 
						relacion_pago_compromisos.idorden_compra_servicio, 
						orden_compra_servicio.numero_orden, 
						retenciones.idretenciones, 
						retenciones.fecha_retencion, 
						comprobantes_retenciones.periodo, 
						comprobantes_retenciones.numero_retencion,
						comprobantes_retenciones.fecha_retencion, 
						orden_pago.justificacion, 
						tipo_retencion.nombre_comprobante 
					FROM 
						beneficiarios, 
						relacion_pago_compromisos, 
						orden_compra_servicio,
						retenciones, 
						comprobantes_retenciones, 
						orden_pago, 
						tipo_retencion 
					WHERE 
						orden_pago.idorden_pago='".$idorden_pago."' 
						AND orden_pago.idorden_pago=relacion_pago_compromisos.idorden_pago 
						AND relacion_pago_compromisos.idorden_compra_servicio=orden_compra_servicio.idorden_compra_servicio 
						AND orden_compra_servicio.numero_orden=retenciones.numero_documento 
						AND retenciones.idretenciones=comprobantes_retenciones.idretenciones
						AND comprobantes_retenciones.estado = 'anulado' 
						AND orden_pago.idbeneficiarios=beneficiarios.idbeneficiarios 
						AND comprobantes_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion 
						AND tipo_retencion.nombre_comprobante='ISLR'";
			$query=mysql_query($sql) or die ("ALLA".$sql.mysql_error());
			while ($field=mysql_fetch_array($query)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_retencion']); $fecha=$d." DE ".$mes[$m]." DE ".$a; $lafecha="$d/$m/$a";		
				$nombre_sujeto=$field['nombre'];
				$rif_sujeto=$field['rif'];
				$periodo_fiscal=$field['periodo'];		
				//$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				if ((8-strlen($field['numero_retencion'])) > 0){
					$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				}else{
					$numero_retencion=(string) $periodo_fiscal.$field['numero_retencion'];
				}
				$justificacion=$field['justificacion'];
				
				$sql2="SELECT 
						orden_pago.idorden_pago, 
						relacion_pago_compromisos.idorden_compra_servicio, 
						retenciones.fecha_factura, 
						retenciones.numero_factura, 
						retenciones.numero_control, 
						retenciones.exento, 
						retenciones.base, 
						retenciones.fecha_retencion, 
						retenciones.total, 
						relacion_retenciones.porcentaje_aplicado, 
						relacion_retenciones.porcentaje_impuesto, 
						relacion_retenciones.monto_retenido, 
						relacion_retenciones.base_calculo, 
						orden_compra_servicio.numero_orden, 
						retenciones.idretenciones, 
						tipo_retencion.nombre_comprobante, 
						orden_compra_servicio.impuesto 
					FROM 
						relacion_pago_compromisos, 
						orden_compra_servicio, 
						retenciones, 
						orden_pago, 
						relacion_retenciones, 
						tipo_retencion 
					WHERE 
						(orden_pago.idorden_pago=relacion_pago_compromisos.idorden_pago 
						AND relacion_pago_compromisos.idorden_compra_servicio=orden_compra_servicio.idorden_compra_servicio 
						AND orden_compra_servicio.numero_orden=retenciones.numero_documento) 
						AND (retenciones.idretenciones=relacion_retenciones.idretenciones) 
						AND orden_pago.idorden_pago='".$idorden_pago."' 
						AND relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion 
						AND tipo_retencion.nombre_comprobante='ISLR'";
				$query2=mysql_query($sql2) or die ($sql2.mysql_error());
				$rows2=mysql_num_rows($query2);
				if ($rows2!=0) {		
					emitir_retenciones_islr($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto);
					//	-----------------------------------------------------------
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 10);
					$pdf->SetAligns(array('C', 'C', 'L', 'L', 'C', 'C', 'C', 'C', 'R', 'R', 'R', 'R'));
					$pdf->SetWidths(array(10, 25, 22, 22, 20, 21, 10, 17, 35, 35, 18, 35));
					//	-----------------------------------------------------------	
					$total_base=0; $total_base_calculo=0; $monto_total=0; $total_monto_retenido=0;
					for ($i=1; $i<=$rows2; $i++) {
						$field2=mysql_fetch_array($query2);
						list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_factura']); $fecha_factura=$d."/".$m."/".$a;
						$exento=number_format($field2['exento'], 2, ',', '.');
						$porcentaje_impuesto=number_format($field2['porcentaje_impuesto'], 2, ',', '.');
						$base_calculo=number_format($field2['impuesto'], 2, ',', '.'); $total_base_calculo+=$field2['impuesto'];
						$monto_retenido=number_format($field2['monto_retenido'], 2, ',', '.'); $total_monto_retenido+=$field2['monto_retenido'];
						$base=number_format($field2['base_calculo'], 2, ',', '.'); $total_base+=$field2['base_calculo'];
						$total=number_format($field2['total'], 2, ',', '.'); $monto_total+=$field2['total'];
						$porcentaje_aplicado=number_format($field2['porcentaje_aplicado'], 2, ',', '.');
						$pdf->Row(array($i, $fecha_factura, $field2['numero_factura'], $field2['numero_control'], '', '', 'C', '1', $total, $base, $porcentaje_aplicado, $monto_retenido));
						$linea=$pdf->GetY(); if ($linea>170) { 
							emitir_retenciones_islr($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto);
						}
					}
					$monto_total=number_format($monto_total, 2, ',', '.');
					$total_base=number_format($total_base, 2, ',', '.');
					$total_base_calculo=number_format($total_base_calculo, 2, ',', '.');
					$total_monto_retenido=number_format($total_monto_retenido, 2, ',', '.');
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 10);
					$pdf->SetAligns(array('C', 'R', 'R', 'R', 'R'));
					$pdf->SetWidths(array(147, 35, 35, 18, 35));
					$pdf->Row(array('SUBTOTAL', $monto_total, $total_base, '', $total_monto_retenido));
					$pdf->Ln(5);
				}
			}
			
			//	Imprimo el impuesto sobre la renta
			//	------------------------------------
			//	Datos Sujeto Retenido
			$sql="SELECT 
						beneficiarios.nombre, 
						beneficiarios.rif,
						beneficiarios.direccion, 
						relacion_pago_compromisos.idorden_compra_servicio, 
						orden_compra_servicio.numero_orden, 
						retenciones.idretenciones, 
						retenciones.fecha_retencion, 
						comprobantes_retenciones.periodo, 
						comprobantes_retenciones.numero_retencion, 
						comprobantes_retenciones.fecha_retencion,
						orden_pago.justificacion, 
						tipo_retencion.nombre_comprobante 
					FROM 
						beneficiarios, 
						relacion_pago_compromisos, 
						orden_compra_servicio, 
						retenciones, 
						comprobantes_retenciones, 
						orden_pago, 
						tipo_retencion 
					WHERE 
						orden_pago.idorden_pago='".$idorden_pago."' 
						AND orden_pago.idorden_pago=relacion_pago_compromisos.idorden_pago 
						AND relacion_pago_compromisos.idorden_compra_servicio=orden_compra_servicio.idorden_compra_servicio 
						AND orden_compra_servicio.numero_orden=retenciones.numero_documento 
						AND retenciones.idretenciones=comprobantes_retenciones.idretenciones 
						AND comprobantes_retenciones.estado = 'anulado'
						AND beneficiarios.idbeneficiarios=orden_pago.idbeneficiarios 
						AND comprobantes_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion 
						AND tipo_retencion.nombre_comprobante='1x1000'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			while ($field=mysql_fetch_array($query)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_retencion']); $fecha=$d." DE ".$mes[$m]." DE ".$a; $lafecha="$d/$m/$a";
				$nombre_sujeto=$field['nombre'];
				$rif_sujeto=$field['rif'];
				$direccion_fiscal = $field["direccion"];
				$periodo_fiscal=$field['periodo'];		
				//$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				if ((8-strlen($field['numero_retencion'])) > 0){
					$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				}else{
					$numero_retencion=(string) $periodo_fiscal.$field['numero_retencion'];
				}
				$justificacion=$field['justificacion'];
				
				$sql2="SELECT 
								orden_pago.idorden_pago, 
								orden_pago.porcentaje_anticipo,
								relacion_pago_compromisos.idorden_compra_servicio, 
								retenciones.fecha_factura, 
								retenciones.numero_factura, 
								retenciones.numero_control, 
								retenciones.exento, 
								retenciones.base, 
								retenciones.fecha_retencion, 
								retenciones.total, 
								relacion_retenciones.porcentaje_aplicado, 
								relacion_retenciones.porcentaje_impuesto, 
								relacion_retenciones.monto_retenido, 
								relacion_retenciones.base_calculo, 
								orden_compra_servicio.numero_orden, 
								retenciones.idretenciones, 
								tipo_retencion.nombre_comprobante, 
								orden_compra_servicio.impuesto,
								orden_pago.numero_orden,
								orden_pago.fecha_orden 
							FROM 
								relacion_pago_compromisos, 
								orden_compra_servicio, 
								retenciones, 
								relacion_retenciones, 
								tipo_retencion,
								orden_pago 
							WHERE 
								(orden_pago.idorden_pago=relacion_pago_compromisos.idorden_pago 
								AND relacion_pago_compromisos.idorden_compra_servicio=orden_compra_servicio.idorden_compra_servicio 
								AND orden_compra_servicio.numero_orden=retenciones.numero_documento)
								AND retenciones.idretenciones=relacion_retenciones.idretenciones 
								AND orden_pago.idorden_pago='".$idorden_pago."' 
								AND relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion 
								AND tipo_retencion.nombre_comprobante='1x1000'";
				$query2=mysql_query($sql2) or die ($sql2.mysql_error());
				$rows2=mysql_num_rows($query2);
				if ($rows2!=0) {		
					emitir_retenciones_uno($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto, $justificacion);
					//	-----------------------------------------------------------
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 10);
					$pdf->SetAligns(array('C', 'C', 'C', 'C', 'R', 'R', 'R', 'R'));
					$pdf->SetWidths(array(10, 30, 30, 40, 40, 40, 40, 40));
					//	-----------------------------------------------------------	
					$total_base=0; $total_base_calculo=0; $monto_total=0; $total_monto_retenido=0;
					for ($i=1; $i<=$rows2; $i++) {
						$field2=mysql_fetch_array($query2);
						list($a, $m, $d)=SPLIT( '[/.-]', $field2['fecha_orden']); $fecha_orden=$d."/".$m."/".$a;
						$exento=number_format($field2['exento'], 2, ',', '.');
						$porcentaje_impuesto=number_format($field2['porcentaje_impuesto'], 2, ',', '.');
						$base_calculo=number_format($field2['impuesto'], 2, ',', '.'); $total_base_calculo+=$field2['impuesto'];
						$monto_retenido=number_format($field2['monto_retenido'], 2, ',', '.'); $total_monto_retenido+=$field2['monto_retenido'];
						$base=number_format($field2['base_calculo'], 2, ',', '.'); $total_base+=$field2['base_calculo'];
						$total=number_format($field2['total'], 2, ',', '.'); $monto_total+=$field2['total'];
						$porcentaje_aplicado=number_format($field2['porcentaje_aplicado'], 2, ',', '.');
						$pdf->Row(array($i, $field2['numero_orden'], $fecha_orden, $field2["porcentaje_anticipo"], $base, $monto_retenido, '0,00', $monto_retenido));
						$linea=$pdf->GetY(); if ($linea>170) { 
							emitir_retenciones_uno($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto, $justificacion);
						}
					}
					$monto_total=number_format($monto_total, 2, ',', '.');
					$total_base=number_format($total_base, 2, ',', '.');
					$total_base_calculo=number_format($total_base_calculo, 2, ',', '.');
					$total_monto_retenido=number_format($total_monto_retenido, 2, ',', '.');
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 10);
					$pdf->SetAligns(array('C', 'R', 'R', 'R', 'R'));
					$pdf->SetWidths(array(110, 40, 40, 40, 40));
					$pdf->Row(array('SUBTOTAL', $total_base, $total_monto_retenido, '', $total_monto_retenido));
					$pdf->Ln(5);
				}
			}	
		}
		break;
	
	//	Registro de Retenciones...
	case "registro_retenciones":
		$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->Open();
		$pag=1;
		//----------------------------------------------------
		$sql_anio_retencion = mysql_query("select * from retenciones where idretenciones = '".$_GET["id_retencion"]."'");
		$bus_anio_documento = mysql_fetch_array($sql_anio_retencion);
		
		$sql="SELECT 
					retenciones.idretenciones, 
					retenciones.iddocumento, 
					retenciones.exento, 
					retenciones.impuesto, 
					retenciones.base, 
					retenciones.total, 
					retenciones.numero_factura, 
					retenciones.numero_control, 
					retenciones.fecha_factura,
					retenciones.fecha_retencion,  
					orden_compra_servicio.numero_orden, 
					orden_compra_servicio.fecha_orden, 
					orden_compra_servicio.justificacion, 
					beneficiarios.nombre AS Beneficiario, 
					beneficiarios.rif 
				FROM 
					retenciones, 
					orden_compra_servicio, 
					beneficiarios 
				WHERE 
					(retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio 
					AND orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios) 
					AND (retenciones.idretenciones='".$_GET["id_retencion"]."')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$total_restar=$field["total"];
		list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_orden"]); $forden=$d."/".$m."/".$a;	
		list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_factura"]); $ffactura=$d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_retencion"]); $fecha_retencion=$d."/".$m."/".$a;
		$exento=number_format($field["exento"], 2, ',', '.');
		$impuesto=number_format($field["impuesto"], 2, ',', '.');
		$total=number_format($field["total"], 2, ',', '.');
		$base=number_format($field["base"], 2, ',', '.');
		//----------------------------------------------------
		
		registro_retenciones($pdf, $pag, $fecha_retencion);
		
		$pdf->SetFont('Arial', '', 10);	$pdf->Cell(25, 6, 'Nro. Orden: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(30, 6, $field["numero_orden"], 0, 0, 'L');
		$pdf->SetFont('Arial', '', 10);	$pdf->Cell(13, 6, 'Fecha: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(30, 6, $forden, 0, 1, 'L');
		
		$pdf->SetFont('Arial', '', 10);	$pdf->Cell(25, 6, 'Beneficiario: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(170, 6, utf8_decode($field["rif"].' - '.$field["Beneficiario"]), 0, 1, 'L');
		
		$pdf->SetFont('Arial', '', 10);	$pdf->Cell(25, 6, utf8_decode('Justificación: '), 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 10); $pdf->MultiCell(170, 6, utf8_decode($field["justificacion"]), 0, 1, 'L');
		$pdf->Ln(5);
		$pdf->SetFont('Arial', '', 10);	$pdf->Cell(25, 6, 'Nro. Factura: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(115, 6, $field["numero_factura"], 0, 0, 'L');
		$pdf->SetFont('Arial', '', 10);	$pdf->Cell(20, 6, 'Exento: ', 0, 0, 'R');
		$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(30, 6, $exento, 0, 1, 'R');	
		$pdf->SetFont('Arial', '', 10);	$pdf->Cell(25, 6, 'Nro. Control: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(115, 6, $field["numero_control"], 0, 0, 'L');
		$pdf->SetFont('Arial', '', 10);	$pdf->Cell(20, 6, 'Sub-Total: ', 0, 0, 'R');
		$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(30, 6, $base, 0, 1, 'R');
		$pdf->SetFont('Arial', '', 10);	$pdf->Cell(25, 6, 'Fecha: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(115, 6, $ffactura, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 10);	$pdf->Cell(20, 6, 'Impuesto: ', 0, 0, 'R');
		$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(30, 6, $impuesto, 0, 1, 'R');
		$pdf->Cell(140, 6);
		$pdf->SetFont('Arial', '', 10);	$pdf->Cell(20, 6, 'Total: ', 0, 0, 'R');
		$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(30, 6, $total, 0, 1, 'R');
		$pdf->Ln(5);
		//----------------------------------------------------
		$pdf->SetFont('Arial', 'BU', 12);
		$pdf->Cell(195, 10, utf8_decode('Relación de Retenciones'), 0, 1, 'C');
		$pdf->Ln(3);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); 
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(20, 4, utf8_decode('Código'), 1, 0, 'C', 1);
		$pdf->Cell(90, 4, utf8_decode('Descripción'), 1, 0, 'C', 1);
		$pdf->Cell(30, 4, 'Sobre', 1, 0, 'C', 1);
		$pdf->Cell(20, 4, '%', 1, 0, 'C', 1);
		$pdf->Cell(30, 4, 'Total Retenido', 1, 1, 'C', 1);
		$pdf->Ln(2);
		//----------------------------------------------------
		$sql="SELECT 
					tipo_retencion.codigo, 
					tipo_retencion.descripcion, 
					relacion_retenciones.monto_retenido, 
					relacion_retenciones.base_calculo, 
					relacion_retenciones.porcentaje_aplicado 
				FROM 
					tipo_retencion, 
					relacion_retenciones 
				WHERE 
					(tipo_retencion.idtipo_retencion=relacion_retenciones.idtipo_retencion) 
					AND (relacion_retenciones.idretenciones='".$_GET["id_retencion"]."')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$retenido=number_format($field["monto_retenido"], 2, ',', '.');
			if ($field['porcentaje_aplicado']==0) { $sobre=""; $porcentaje=""; }
			else {
				$sobre=number_format($field["base_calculo"], 2, ',', '.');
				$porcentaje=number_format($field["porcentaje_aplicado"], 2, ',', '.').' %';
			}
			$suma_retenido+=$field["monto_retenido"];
			//
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetWidths(array(20, 90, 30, 20, 30));
			$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R'));
			$pdf->Row(array($field["codigo"], utf8_decode($field["descripcion"]), $sobre, $porcentaje, $retenido));
		}
		$pdf->Ln(3);
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->Rect(10, $y, 190, 0.1);
		
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$total_pagar=$total_restar-$suma_retenido;
		$total_retenido=number_format($suma_retenido, 2, ',', '.');
		$total_pagar=number_format($total_pagar, 2, ',', '.');
		$pdf->Cell(140, 6);
		$pdf->SetFont('Arial', '', 10);	$pdf->Cell(20, 6, 'Total Retenido: ', 0, 0, 'R');
		$pdf->SetFont('Arial', 'B', 12); $pdf->Cell(30, 6, $total_retenido, 0, 1, 'R');
		$pdf->Cell(140, 6);
		$pdf->SetFont('Arial', '', 10);	$pdf->Cell(20, 6, 'Total a Pagar: ', 0, 0, 'R');
		$pdf->SetFont('Arial', 'B', 12); $pdf->Cell(30, 6, $total_pagar, 0, 1, 'R');
		
		$sql="SELECT * FROM configuracion_tributos";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query)!=0) {
			$field=mysql_fetch_array($query);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->Rect(65, 220, 80, 0.1);
			$pdf->SetY(223);
			$pdf->SetFont('Arial', 'B', 12);
			$pdf->Cell(190, 5, utf8_decode($field['primero_tributos']), 0, 1, 'C');
			$pdf->Cell(190, 5, 'C.I. '.utf8_decode($field['ci_primero_tributos']), 0, 1, 'C');
			$pdf->Cell(190, 5, utf8_decode($field['cargo_primero_tributos']), 0, 1, 'C');
		}
		break;
	
	//	Registro de Retenciones Externas (Comprobantes de Retenciones)...
	case "comprobantes_retenciones_externas":
		$pdf=new PDF_MC_Table4('L', 'mm', 'Letter');
		$pdf->SetTopMargin(1);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 1.5);
		$pdf->Open();
		//	-----------------------------------------------------------
		$mes['01']="ENERO";
		$mes['02']="FEBRERO";
		$mes['03']="MARZO";
		$mes['04']="ABRIL";
		$mes['05']="MAYO";
		$mes['06']="JUNIO";
		$mes['07']="JULIO";
		$mes['08']="AGOSTO";
		$mes['09']="SEPTIEMBRE";
		$mes['10']="OCTUBRE";
		$mes['11']="NOVIEMBRE";
		$mes['12']="DICIEMBRE";
		//	Datos Agente de Retencion
		$query=mysql_query("SELECT nombre_institucion, domicilio_legal, ciudad, estado, rif FROM configuracion") or die (mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$nombre_agente=$field['nombre_institucion'];
			$rif_agente=$field['rif'];
			$direccion_fiscal=$field['domicilio_legal']." ".$field['ciudad']." ".$field['estado'];
		}
		//	Datos Sujeto Retenido
		$sql="SELECT
					b.nombre AS beneficiario, 
					b.rif AS ci_beneficiario, 
					rre.periodo,
					rre.numero_retencion, 
					r.fecha_retencion,
					tr.nombre_comprobante 
				FROM 
					retenciones r 
					INNER JOIN relacion_retenciones_externas rre ON (r.idretenciones=rre.idretencion) 
					INNER JOIN beneficiarios b ON (r.idbeneficiarios=b.idbeneficiarios)
					INNER JOIN tipo_retencion tr ON (rre.idtipo_retencion=tr.idtipo_retencion AND tr.nombre_comprobante='IVA') 
				WHERE 
					r.idretenciones='".$id_retencion."'
				GROUP BY r.idretenciones";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_retencion']); $fecha=$d." DE ".$mes[$m]." DE ".$a; $lafecha="$d/$m/$a";		
			$nombre_sujeto=$field['beneficiario'];
			$rif_sujeto=$field['ci_beneficiario'];
			$periodo_fiscal=$field['periodo'];		
			//$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion']; $numero=$field['numero_retencion'];
			if ((8-strlen($field['numero_retencion'])) > 0){
				$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
			}else{
				$numero_retencion=(string) $periodo_fiscal.$field['numero_retencion'];
			}
			$justificacion=$field['justificacion'];
		}
		//	-----------------------------------------------------------	
		$sql="SELECT 
					ree.fecha_factura, 
					ree.numero_factura, 
					ree.numero_control, 
					ree.exento, 
					ree.impuesto, 
					ree.alicuota AS porcentaje_impuesto, 
					ree.sub_total AS base, 
					r.fecha_retencion, 
					ree.total, 
					'' AS porcentaje_aplicado, 
					ree.monto_retenido, 
					ree.base_calculo, 
					r.idretenciones, 
					tr.nombre_comprobante
				FROM 
					retenciones r 
					INNER JOIN relacion_retenciones_externas ree ON (r.idretenciones=ree.idretencion) 
					INNER JOIN tipo_retencion tr ON (ree.idtipo_retencion=tr.idtipo_retencion AND tr.nombre_comprobante='IVA')
				WHERE 
					r.idretenciones='".$id_retencion."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {
			emitir_retenciones($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto);
			//	-----------------------------------------------------------
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetAligns(array('C', 'C', 'L', 'L', 'C', 'C', 'C', 'C', 'R', 'R', 'R', 'R', 'R', 'R'));
			$pdf->SetWidths(array(8, 20, 20, 26, 17, 19, 6, 17, 25, 25, 25, 12, 25, 24));
			//	-----------------------------------------------------------
			$j=1;
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query);
				//for($k=0;$k<20;$k++){
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_factura']); $fecha_factura=$d."/".$m."/".$a;
				$exento=number_format($field['exento'], 2, ',', '.');
				$porcentaje_impuesto=number_format($field['porcentaje_impuesto'], 2, ',', '.');
				$base_calculo=number_format($field['impuesto'], 2, ',', '.'); $total_base_calculo+=$field['impuesto'];
				$monto_retenido=number_format($field['monto_retenido'], 2, ',', '.'); $total_monto_retenido+=$field['monto_retenido'];
				$base=number_format($field['base'], 2, ',', '.'); $total_base+=$field['base'];
				$total=number_format($field['total'], 2, ',', '.'); $monto_total+=$field['total'];
				$porcentaje_aplicado=number_format($field['porcentaje_aplicado'], 2, ',', '.');
				$pdf->Row(array($i, $fecha_factura, $field['numero_factura'], $field['numero_control'], '', '', 'C', '1', $total, $exento, $base, $porcentaje_impuesto, $base_calculo, $monto_retenido));
				$linea=$pdf->GetY(); if ($linea>170) { 
					emitir_retenciones($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto);
				}
				$j++;
			}//}
			$monto_total=number_format($monto_total, 2, ',', '.');
			$total_base=number_format($total_base, 2, ',', '.');
			$total_base_calculo=number_format($total_base_calculo, 2, ',', '.');
			$total_monto_retenido=number_format($total_monto_retenido, 2, ',', '.');
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->SetAligns(array('C', 'R', 'C', 'R', 'C', 'R', 'R'));
			$pdf->SetWidths(array(133, 25, 25, 25, 12, 25, 24));
			$pdf->Row(array('SUBTOTAL', $monto_total, '', $total_base, '', $total_base_calculo, $total_monto_retenido));
			$pdf->Ln(5);
		}
		
		//	------------------------------------
		//	Datos Sujeto Retenido
		$sql="SELECT
					b.nombre AS beneficiario, 
					b.rif AS ci_beneficiario, 
					rre.periodo,
					rre.numero_retencion, 
					r.fecha_retencion,
					tr.nombre_comprobante 
				FROM 
					retenciones r 
					INNER JOIN relacion_retenciones_externas rre ON (r.idretenciones=rre.idretencion) 
					INNER JOIN beneficiarios b ON (r.idbeneficiarios=b.idbeneficiarios)
					INNER JOIN tipo_retencion tr ON (rre.idtipo_retencion=tr.idtipo_retencion AND tr.nombre_comprobante='ISLR') 
				WHERE 
					r.idretenciones='".$id_retencion."'
				GROUP BY r.idretenciones";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_retencion']); $fecha=$d." DE ".$mes[$m]." DE ".$a; $lafecha="$d/$m/$a";		
			$nombre_sujeto=$field['beneficiario'];
			$rif_sujeto=$field['ci_beneficiario'];
			$periodo_fiscal=$field['periodo'];		
			//$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
			if ((8-strlen($field['numero_retencion'])) > 0){
				$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
			}else{
				$numero_retencion=(string) $periodo_fiscal.$field['numero_retencion'];
			}
			$justificacion=substr($field['justificacion'],0,552);
		}
		//	Imprimo el impuesto sobre la renta
		$sql="SELECT 
					ree.fecha_factura, 
					ree.numero_factura, 
					ree.numero_control, 
					ree.exento, 
					ree.total AS base, 
					r.fecha_retencion, 
					(ree.sub_total+ree.exento) AS total, 
					ree.porcentaje AS porcentaje_aplicado, 
					'' AS porcentaje_impuesto, 
					ree.monto_retenido, 
					ree.base_calculo, 
					r.idretenciones, 
					tr.nombre_comprobante
				FROM 
					retenciones r 
					INNER JOIN relacion_retenciones_externas ree ON (r.idretenciones=ree.idretencion) 
					INNER JOIN tipo_retencion tr ON (ree.idtipo_retencion=tr.idtipo_retencion AND tr.nombre_comprobante='ISLR')
				WHERE 
					r.idretenciones='".$id_retencion."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {		
			emitir_retenciones_islr($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto);
			//	-----------------------------------------------------------
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetAligns(array('C', 'C', 'L', 'L', 'C', 'C', 'C', 'C', 'R', 'R', 'R', 'R'));
			$pdf->SetWidths(array(10, 25, 22, 22, 20, 21, 10, 17, 35, 35, 18, 35));
			//	-----------------------------------------------------------	
			$total_base=0; $total_base_calculo=0; $monto_total=0; $total_monto_retenido=0;
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query);
				//for($k=0;$k<10;$k++){
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_factura']); $fecha_factura=$d."/".$m."/".$a;
				$exento=number_format($field['exento'], 2, ',', '.');
				$porcentaje_impuesto=number_format($field['porcentaje_impuesto'], 2, ',', '.');
				$base_calculo=number_format($field['impuesto'], 2, ',', '.'); $total_base_calculo+=$field['impuesto'];
				$monto_retenido=number_format($field['monto_retenido'], 2, ',', '.'); $total_monto_retenido+=$field['monto_retenido'];
				$base=number_format($field['total'], 2, ',', '.'); $total_base+=$field['total'];
				$total=number_format($field['base'], 2, ',', '.'); $monto_total+=$field['base'];
				$porcentaje_aplicado=number_format($field['porcentaje_aplicado'], 2, ',', '.');
				$pdf->Row(array($i, $fecha_factura, $field['numero_factura'], $field['numero_control'], '', '', 'C', '1', $total, $base, $porcentaje_aplicado, $monto_retenido));
				$linea=$pdf->GetY(); if ($linea>170) { 
					emitir_retenciones_islr($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto);
				}
			}//}
			$monto_total=number_format($monto_total, 2, ',', '.');
			$total_base=number_format($total_base, 2, ',', '.');
			$total_base_calculo=number_format($total_base_calculo, 2, ',', '.');
			$total_monto_retenido=number_format($total_monto_retenido, 2, ',', '.');
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->SetAligns(array('C', 'R', 'R', 'R', 'R'));
			$pdf->SetWidths(array(147, 35, 35, 18, 35));
			$pdf->Row(array('SUBTOTAL', $monto_total, $total_base, '', $total_monto_retenido));
			$pdf->Ln(5);
		}
		
		//	------------------------------------
		//	Datos Sujeto Retenido
		$sql="SELECT
					b.nombre AS beneficiario, 
					b.rif AS ci_beneficiario,
					b.direccion,
					rre.periodo,
					rre.numero_retencion, 
					r.fecha_retencion,
					tr.nombre_comprobante 
				FROM 
					retenciones r 
					INNER JOIN relacion_retenciones_externas rre ON (r.idretenciones=rre.idretencion) 
					INNER JOIN beneficiarios b ON (r.idbeneficiarios=b.idbeneficiarios)
					INNER JOIN tipo_retencion tr ON (rre.idtipo_retencion=tr.idtipo_retencion AND tr.nombre_comprobante='1x1000') 
				WHERE 
					r.idretenciones='".$id_retencion."'
				GROUP BY r.idretenciones";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_retencion']); $fecha=$d." DE ".$mes[$m]." DE ".$a; $lafecha="$d/$m/$a";
			$nombre_sujeto=$field['beneficiario'];
			$rif_sujeto=$field['ci_beneficiario'];
			$periodo_fiscal=$field['periodo'];		
			//$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
			if ((8-strlen($field['numero_retencion'])) > 0){
				$numero_retencion=(string) $periodo_fiscal.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
			}else{
				$numero_retencion=(string) $periodo_fiscal.$field['numero_retencion'];
			}
			$direccion_sujeto = $field['direccion'];
		}
		//	Imprimo el uno por mil
		$sqluno="SELECT 
					ree.fecha_factura, 
					ree.numero_factura, 
					ree.numero_control, 
					ree.numero_orden,
					ree.fecha_orden,
					ree.exento, 
					r.fecha_retencion, 
					ree.total, 
					'' AS porcentaje_aplicado, 
					'' AS porcentaje_impuesto, 
					ree.monto_retenido, 
					ree.base_calculo as base, 
					ree.concepto_orden,
					ree.monto_contrato,
					r.idretenciones, 
					tr.nombre_comprobante
				FROM 
					retenciones r 
					INNER JOIN relacion_retenciones_externas ree ON (r.idretenciones=ree.idretencion) 
					INNER JOIN tipo_retencion tr ON (ree.idtipo_retencion=tr.idtipo_retencion AND tr.nombre_comprobante='1x1000')
				WHERE 
					r.idretenciones='".$id_retencion."'";
					
		$query_retenciones=mysql_query($sqluno) or die ($sql.mysql_error());
		$query_retenciones2 = $query_retenciones;
		$rows=mysql_num_rows($query_retenciones);
		if ($rows!=0) {	
			$field2=mysql_fetch_array($query_retenciones);
			$justificacion = substr($field2["concepto_orden"],0,560);
			$total_contrato = $field2["monto_contrato"];
			emitir_retenciones_uno($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto, $justificacion, $direccion_sujeto, $total_contrato);
			//	-----------------------------------------------------------
			
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetAligns(array('C', 'C', 'C', 'C', 'R', 'R', 'R', 'R'));
			$pdf->SetWidths(array(10, 30, 30, 40, 40, 40, 40, 40));
			//	-----------------------------------------------------------	
			$total_base=0; $total_base_calculo=0; $monto_total=0; $total_monto_retenido=0;
			//$field=mysql_fetch_array($query);
			$i=0;
			$sql_uno="SELECT 
					ree.fecha_factura, 
					ree.numero_factura, 
					ree.numero_control, 
					ree.numero_orden,
					ree.fecha_orden,
					ree.exento, 
					r.fecha_retencion, 
					ree.total, 
					'' AS porcentaje_aplicado, 
					'' AS porcentaje_impuesto, 
					ree.monto_retenido, 
					ree.base_calculo as base, 
					ree.concepto_orden,
					ree.monto_contrato,
					r.idretenciones, 
					tr.nombre_comprobante
				FROM 
					retenciones r 
					INNER JOIN relacion_retenciones_externas ree ON (r.idretenciones=ree.idretencion) 
					INNER JOIN tipo_retencion tr ON (ree.idtipo_retencion=tr.idtipo_retencion AND tr.nombre_comprobante='1x1000')
				WHERE 
					r.idretenciones='".$id_retencion."'";
			$query_uno = mysql_query($sql_uno);
			while ($field=mysql_fetch_array($query_uno)) {
				//for($k=0;$k<10;$k++){
					$i=$i+1;
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha_orden=$d."/".$m."/".$a;
				$exento=number_format($field['exento'], 2, ',', '.');
				$porcentaje_impuesto=number_format($field['porcentaje_impuesto'], 2, ',', '.');
				$base_calculo=number_format($field['impuesto'], 2, ',', '.'); $total_base_calculo+=$field['impuesto'];
				$monto_retenido=number_format($field['monto_retenido'], 2, ',', '.'); $total_monto_retenido+=$field['monto_retenido'];
				$base=number_format($field['base'], 2, ',', '.'); $total_base+=$field['base'];
				$total=number_format($field['total'], 2, ',', '.'); $monto_total+=$field['total'];
				$porcentaje_aplicado=number_format($field['porcentaje_aplicado'], 2, ',', '.');
				$pdf->Row(array($i, $field['numero_orden'], $fecha_orden, '0', $base, $monto_retenido, '0,00', $monto_retenido));
				$linea=$pdf->GetY(); if ($linea>170) { 
					emitir_retenciones_uno($pdf, $numero_retencion, $lafecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto, $justificacion, $direccion_sujeto, $total_contrato);
				}
			}
				$monto_total=number_format($monto_total, 2, ',', '.');
				$total_base=number_format($total_base, 2, ',', '.');
				$total_base_calculo=number_format($total_base_calculo, 2, ',', '.');
				$total_monto_retenido=number_format($total_monto_retenido, 2, ',', '.');
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->SetAligns(array('C', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(110, 40, 40, 40, 40));
				$pdf->Row(array('TOTALES', $total_base, $total_monto_retenido, '', $total_monto_retenido));
				$pdf->Ln(5);
			}
		break;
	
	//	Registro de Retenciones Externas (Registro de Retenciones)...
	case "registro_retenciones_externas":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		registro_retenciones_externas($pdf, $id_retencion);
		//	----------------------------------------------------
		//	Imprimo la tabla...
		$sql="SELECT
					rre.numero_orden, 
					rre.fecha_orden, 
					rre.numero_factura, 
					rre.numero_control, 
					rre.fecha_factura, 
					tr.descripcion AS tipo_retencion, 
					rre.monto_retenido, 
					rre.porcentaje, 
					rre.base_calculo 
				FROM 
					relacion_retenciones_externas rre 
					INNER JOIN tipo_retencion tr ON (rre.idtipo_retencion=tr.idtipo_retencion) 
				WHERE 
					rre.idretencion='".$id_retencion."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $forden=$d."/".$m."/".$a;
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_factura']); $ffactura=$d."/".$m."/".$a;
			$retenido=number_format($field["monto_retenido"], 2, ',', '.');
			if ($field['porcentaje']==0) { $sobre=""; $porcentaje=""; }
			else {
				$sobre=number_format($field["base_calculo"], 2, ',', '.');
				$porcentaje=number_format($field["porcentaje"], 2, ',', '.').' %';
			}
			$suma_retenido+=$field["monto_retenido"];
			
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 6);
			$pdf->SetAligns(array('L', 'C', 'L', 'L', 'C', 'L', 'R', 'R', 'R'));
			$pdf->Row(array($field['numero_orden'], $forden, $field['numero_factura'], $field['numero_control'], $ffactura, utf8_decode($field['tipo_retencion']), $sobre, $porcentaje, $retenido));
		}
		
		//	----------------------------------------------------
		//	Imprimo el total retenido...
		$pdf->Ln(3);
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->Rect(10, $y, 195, 0.1);
		
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$suma_retenido=number_format($suma_retenido, 2, ',', '.');
		$pdf->Cell(145, 6);
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(20, 6, 'Total Retenido: ', 0, 0, 'R');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $suma_retenido, 0, 1, 'R');
		break;



		
	//	Relacion de Retenciones...
	case "relacion_retenciones":
		$pdf=new PDF_MC_Table('L', 'mm', 'Letter');
		$pdf->Open();
		//----------------------------------------------------
		if ($_GET["desde"]!="" && $_GET["hasta"]!="") {
			list($a, $m, $d)=SPLIT( '[/.-]', $_GET["desde"]); $desde=$d."/".$m."/".$a;	
			list($a, $m, $d)=SPLIT( '[/.-]', $_GET["hasta"]); $hasta=$d."/".$m."/".$a;
			$periodo="DEL $desde AL $hasta";
			$filtro_periodo=" AND (retenciones.fecha_retencion>='".$_GET['desde']."' AND retenciones.fecha_retencion<='".$_GET['hasta']."')";
		} else $periodo="";
		if ($imprimir == 'todo'){ $filtro_imprimir = ''; $filtro_imprimirE = ''; $orden = 'ORDER BY fecha_orden, numero_retencion, fecha_aplicacion_retencion'; }
		if ($imprimir == 'comprobante'){ $filtro_imprimir = ' AND relacion_retenciones.numero_retencion <> 0'; $filtro_imprimirE = ' AND relacion_retenciones_externas.numero_retencion <> 0'; $orden = 'ORDER BY numero_retencion, fecha_aplicacion_retencion'; }
		if ($imprimir == 'sin_comprobante'){ $filtro_imprimir = ' AND relacion_retenciones.numero_retencion = 0'; $filtro_imprimirE = ' AND relacion_retenciones_externas.numero_retencion = 0'; $orden = 'ORDER BY fecha_orden, numero_retencion, fecha_aplicacion_retencion';}
		$sql="SELECT descripcion FROM tipo_retencion WHERE nombre_comprobante='".$_GET["idtipo_retencion"]."' GROUP BY nombre_comprobante";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$tipo_retencion=$field["descripcion"];
		//----------------------------------------------------
		relacion_retenciones($pdf, $tipo_retencion, $periodo);
		$pdf->Ln(2);
		//----------------------------------------------------
		$sql="(SELECT 
					retenciones.idretenciones,
					retenciones.fecha_retencion,
					retenciones.fecha_aplicacion_retencion,
					retenciones.numero_factura, 
					retenciones.numero_control, 
					retenciones.fecha_factura, 
					retenciones.estado, 
					orden_compra_servicio.numero_orden, 
					orden_compra_servicio.fecha_orden, 
					beneficiarios.nombre AS Beneficiario, 
					relacion_retenciones.monto_retenido,  
					relacion_retenciones.numero_retencion, 
					tipo_retencion.nombre_comprobante 
				FROM 
					retenciones 
					INNER JOIN orden_compra_servicio ON (retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio) 
					INNER JOIN beneficiarios ON (orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios) 
					INNER JOIN relacion_retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones) 
					INNER JOIN tipo_retencion ON (relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion) 
				WHERE 
					(tipo_retencion.nombre_comprobante='".$_GET["idtipo_retencion"]."') $filtro_periodo$filtro_imprimir)
					
				UNION 
				
				(SELECT 
					relacion_retenciones_externas.idrelacion_retenciones_externas as idretenciones,
					relacion_retenciones_externas.fecha_factura as fecha_retencion,
					retenciones.fecha_aplicacion_retencion as fecha_aplicacion_retencion,
					relacion_retenciones_externas.numero_factura, 
					relacion_retenciones_externas.numero_control, 
					relacion_retenciones_externas.fecha_factura,
					'externa' AS estado, 
					relacion_retenciones_externas.numero_orden, 
					relacion_retenciones_externas.fecha_factura as fecha_orden, 
					beneficiarios.nombre AS Beneficiario, 
					relacion_retenciones_externas.monto_retenido, 
					relacion_retenciones_externas.numero_retencion, 
					tipo_retencion.nombre_comprobante 
				FROM 
					retenciones  
					INNER JOIN beneficiarios ON (retenciones.idbeneficiarios=beneficiarios.idbeneficiarios) 
					INNER JOIN relacion_retenciones_externas ON (retenciones.idretenciones=relacion_retenciones_externas.idretencion) 
					INNER JOIN tipo_retencion ON (relacion_retenciones_externas.idtipo_retencion=tipo_retencion.idtipo_retencion) 
				WHERE 
					(tipo_retencion.nombre_comprobante='".$_GET["idtipo_retencion"]."') $filtro_periodo$filtro_imprimirE) 
					
				$orden";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			list($anio, $mes, $dia)=SPLIT( '[/.-]', $field["fecha_aplicacion_retencion"]);
			if ($field['numero_retencion']==0){ $nro_comprobante="";
			}else{ //$nro_comprobante=(string) $anio.$mes.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
			if ((8-strlen($field['numero_retencion'])) > 0){
				$nro_comprobante=(string) $anio.$mes.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
			}else{
				$nro_comprobante=(string) $anio.$mes.$field['numero_retencion'];
			}}
			$retenido=number_format($field["monto_retenido"], 2, ',', '.');
			$suma_retenido+=$field["monto_retenido"];
			list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_orden"]); $forden=$d."/".$m."/".$a;	
			list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_factura"]); $ffactura=$d."/".$m."/".$a;
			list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_aplicacion_retencion"]); $faplicacion=$d."/".$m."/".$a;			
			$estado=substr(strtoupper($field["estado"]),0,3);
			//
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetWidths(array(10, 25, 17, 89, 20, 20, 17, 25, 17, 25));
			$pdf->SetAligns(array('C', 'L', 'C', 'L', 'L', 'L', 'L', 'L', 'C', 'R'));
			$pdf->Row(array($estado, $field["numero_orden"], $forden, substr(utf8_decode($field["Beneficiario"]),0,50), $field["numero_factura"], $field["numero_control"], $ffactura, $nro_comprobante, $faplicacion, $retenido));
			$linea=$pdf->GetY(); if ($linea>190) { relacion_retenciones($pdf, $tipo_retencion, $periodo); $pdf->Ln(2); }
		}
		$suma_retenido=number_format($suma_retenido, 2, ',', '.');
		$pdf->Ln(2);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$y=$pdf->GetY();
		$pdf->Rect(10, $y, 265, 0.1);
		$pdf->Ln(1);
		$pdf->Cell(265, 5, $suma_retenido, 0, 1, 'R', 1);
		break;
		
	//	Relacion por Beneficiarios...
	case "retenciones_beneficiario":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		//----------------------------------------------------
		if ($_GET["desde"]!="" && $_GET["hasta"]!="") {
			list($a, $m, $d)=SPLIT( '[/.-]', $_GET["desde"]); $desde=$d."/".$m."/".$a;	
			list($a, $m, $d)=SPLIT( '[/.-]', $_GET["hasta"]); $hasta=$d."/".$m."/".$a;
			$periodo="DEL $desde AL $hasta";
			$filtro_periodo=" AND (retenciones.fecha_retencion>='".$_GET["desde"]."' AND retenciones.fecha_retencion<='".$_GET["hasta"]."')";
		} else $periodo="";
		if ($estado!="0") $filtro_estado=" AND retenciones.estado='".$estado."'";
		$sql="SELECT descripcion FROM tipo_retencion WHERE nombre_comprobante='".$_GET["idtipo_retencion"]."' GROUP BY nombre_comprobante";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$tipo_retencion=$field["descripcion"];
		
		$sql="SELECT nombre FROM beneficiarios WHERE idbeneficiarios='".$_GET["idbeneficiario"]."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$beneficiario=$field["nombre"];
		//----------------------------------------------------
		retenciones_beneficiario($pdf, $tipo_retencion, $periodo, $beneficiario);
		//----------------------------------------------------
		$sql="(SELECT 
					retenciones.idretenciones,
					retenciones.fecha_retencion,
					retenciones.fecha_aplicacion_retencion,
					retenciones.numero_factura, 
					retenciones.numero_control, 
					retenciones.fecha_factura, 
					retenciones.estado, 
					orden_compra_servicio.numero_orden, 
					orden_compra_servicio.fecha_orden, 
					beneficiarios.nombre AS Beneficiario, 
					relacion_retenciones.monto_retenido, 
					tipo_retencion.nombre_comprobante 
				FROM 
					retenciones 
					INNER JOIN orden_compra_servicio ON (retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio) 
					INNER JOIN beneficiarios ON (orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios) 
					INNER JOIN relacion_retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones) 
					INNER JOIN tipo_retencion ON (relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion) 
				WHERE 
					(tipo_retencion.nombre_comprobante='".$_GET["idtipo_retencion"]."') AND (beneficiarios.idbeneficiarios='".$_GET["idbeneficiario"]."') $filtro_periodo $filtro_estado)
					
				UNION 
				
				(SELECT 
					relacion_retenciones_externas.idrelacion_retenciones_externas as idretenciones,
					relacion_retenciones_externas.fecha_factura as fecha_retencion,
					relacion_retenciones_externas.fecha_factura as fecha_aplicacion_retencion,
					relacion_retenciones_externas.numero_factura, 
					relacion_retenciones_externas.numero_control, 
					relacion_retenciones_externas.fecha_factura, 
					'EXTERNA' AS estado, 
					relacion_retenciones_externas.numero_orden, 
					relacion_retenciones_externas.fecha_factura AS fecha_orden, 
					beneficiarios.nombre AS Beneficiario, 
					relacion_retenciones_externas.monto_retenido, 
					tipo_retencion.nombre_comprobante 
				FROM 
					retenciones  
					INNER JOIN beneficiarios ON (retenciones.idbeneficiarios=beneficiarios.idbeneficiarios) 
					INNER JOIN relacion_retenciones_externas ON (retenciones.idretenciones=relacion_retenciones_externas.idretencion) 
					INNER JOIN tipo_retencion ON (relacion_retenciones_externas.idtipo_retencion=tipo_retencion.idtipo_retencion) 
				WHERE 
					(tipo_retencion.nombre_comprobante='".$_GET["idtipo_retencion"]."') AND (beneficiarios.idbeneficiarios='".$_GET["idbeneficiario"]."') $filtro_periodo $filtro_estado) 
					
				ORDER BY idretenciones, fecha_retencion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$retenido=number_format($field["monto_retenido"], 2, ',', '.');
			$suma_retenido+=$field["monto_retenido"];
			list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_orden"]); $forden=$d."/".$m."/".$a;	
			list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_factura"]); $ffactura=$d."/".$m."/".$a;
			$estado=strtoupper($field["estado"]);
			//
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetWidths(array(30, 25, 25, 25, 25, 30, 30));
			$pdf->SetAligns(array('L', 'C', 'L', 'L', 'C', 'C', 'R'));
			$pdf->Row(array($field["numero_orden"], $forden, $field["numero_factura"], $field["numero_control"], $ffactura, strtoupper($field['estado']), $retenido));
			$linea=$pdf->GetY(); if ($linea>250) { retenciones_beneficiario($pdf, $tipo_retencion, $periodo, $beneficiario); }
		}
		$suma_retenido=number_format($suma_retenido, 2, ',', '.');
		$pdf->Ln(2);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$y=$pdf->GetY();
		$pdf->Rect(10, $y, 190, 0.1);
		$pdf->Ln(1);
		$pdf->Cell(190, 5, $suma_retenido, 0, 1, 'R', 1);
		break;
		
	//	Libro de Compras (I.V.A)...
	case "libro_compras_iva":
		$pdf=new PDF_MC_Table('L', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(1, 1, 1);
		$pdf->SetAutoPageBreak(1, 1);
		//----------------------------------------------------
		if ($quincena=="1") $dias=15;
		else {
			$dias_mes['01']=31; $dias_mes['03']=31; $dias_mes['04']=30; $dias_mes['05']=31; $dias_mes['06']=30;
			$dias_mes['07']=31; $dias_mes['08']=31; $dias_mes['09']=30; $dias_mes['10']=31; $dias_mes['11']=30; $dias_mes['12']=31;
			if ($anio%4==0) $dias_mes['02']=29; else $dias_mes['02']=28; 
			$dias=$dias_mes[$mes];
		}
		$periodo=$anio.$mes;
		if ($quincena=="1") $desde=$anio."-".$mes."-01"; else  $desde=$anio."-".$mes."-16";
		if ($quincena=="3") $desde=$anio."-".$mes."-01";
		$hasta=$anio."-".$mes."-".$dias;
		//----------------------------------------------------
		libro_compras_iva($pdf, $periodo, $desde, $hasta);
		//----------------------------------------------------
		$idtipo = 'IVA';
		$sql="(SELECT 
					r.fecha_aplicacion_retencion as fecha_aplicacion_retencion, 
					r.numero_documento, 
					r.numero_factura,
					r.numero_control,
					r.fecha_factura,
					r.total, 
					r.exento, 
					r.base, 
					r.impuesto, 
					rl.periodo, 
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.monto_retenido, 
					rl.porcentaje_impuesto, 
					rl.periodo, 
					o.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					r.estado,
					r.fecha_retencion,
					rl.numero_retencion_referencia
				FROM 
					retenciones r 
					INNER JOIN relacion_retenciones rl ON (r.idretenciones=rl.idretenciones AND rl.generar_comprobante = 'si') 
					INNER JOIN tipo_retencion tr ON (rl.idtipo_retencion=tr.idtipo_retencion) 
					INNER JOIN orden_compra_servicio o ON (r.numero_documento=o.numero_orden) 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (r.fecha_aplicacion_retencion>='".$desde."' AND r.fecha_aplicacion_retencion<='".$hasta."')
					$filtro_estado_r)
					
				UNION 
				
				(SELECT 
					r.fecha_aplicacion_retencion as fecha_aplicacion_retencion, 
					r.numero_documento,
					rl.numero_factura,
					rl.numero_control,
					rl.fecha_factura,
					rl.total, 
					rl.exento, 
					rl.sub_total as base, 
					rl.impuesto, 
					rl.periodo, 
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.monto_retenido, 
					rl.alicuota as porcentaje_impuesto, 
					rl.periodo, 
					r.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					r.estado,
					r.fecha_retencion,
					rl.numero_retencion_referencia
				FROM 
					retenciones r 
					INNER JOIN beneficiarios b ON (r.idbeneficiarios=b.idbeneficiarios) 
					INNER JOIN relacion_retenciones_externas rl ON (r.idretenciones=rl.idretencion) 
					INNER JOIN tipo_retencion tr ON (rl.idtipo_retencion=tr.idtipo_retencion)
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (r.fecha_aplicacion_retencion>='".$desde."' AND r.fecha_aplicacion_retencion<='".$hasta."'))
					
				UNION
				
				(SELECT 
					r.fecha_aplicacion_retencion as fecha_aplicacion_retencion, 
					r.numero_documento,
					r.numero_factura,
					r.numero_control,
					r.fecha_factura,
					r.total, 
					r.exento, 
					r.base, 
					r.impuesto, 
					cr.periodo, 
					cr.idtipo_retencion, 
					cr.numero_retencion, 
					rl.monto_retenido, 
					rl.porcentaje_impuesto, 
					cr.periodo, 
					o.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					cr.estado,
					cr.fecha_retencion,
					rl.numero_retencion_referencia
				FROM 
					retenciones r 
					INNER JOIN comprobantes_retenciones cr ON (r.idretenciones=cr.idretenciones AND cr.estado <> 'procesado') 
					INNER JOIN tipo_retencion tr ON (cr.idtipo_retencion=tr.idtipo_retencion) 
					INNER JOIN relacion_retenciones rl ON (cr.idretenciones = rl.idretenciones AND cr.idtipo_retencion = rl.idtipo_retencion AND rl.generar_comprobante = 'si')
					INNER JOIN orden_compra_servicio o ON (r.numero_documento=o.numero_orden) 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (cr.fecha_retencion>='".$desde."' AND cr.fecha_retencion<='".$hasta."')
					$filtro_estado_cr)
				
				ORDER BY numero_retencion_referencia";
		
					
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		$linea=1;
		for ($i=1; $i<=$rows; $i++) {
			$field=mysql_fetch_array($query);	
			//----------------------------------------------------	
			if ($i==1) $contador=$field['numero_retencion'];
			if ($contador>$field['numero_retencion']) $contador=$field['numero_retencion'];
			/*if ($contador!=$field['numero_retencion']) {
				$valida=$field['numero_retencion']-$contador;
				for ($k=0; $k<$valida; $k++) {
					$nro_comprobante=(string) $anio.$mes.str_repeat("0", 8-strlen($contador)).$contador;
					$pdf->Row(array($linea, '', '', '(ANULADO)', $nro_comprobante, '', '', '', '', '', '', '', '', ''));
					$linea++;
					$contador++;
					$y=$pdf->GetY(); if ($y>190) { libro_compras_iva($pdf, $periodo, $desde, $hasta); }
				}
				$contador=$field['numero_retencion'];
			}*/
			//----------------------------------------------------	
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_factura']); $fecha_factura=$d."/".$m."/".$a;
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_aplicacion_retencion']);
			if ((8-strlen($field['numero_retencion'])) > 0){
				$nro_comprobante=(string) $a.$m.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
			}else{
				$nro_comprobante=(string) $a.$m.$field['numero_retencion'];
			}
			$total=number_format($field['total'], 2, ',', '.');
			$exento=number_format($field['exento'], 2, ',', '.');
			$alicuota=number_format($field['porcentaje_impuesto'], 2, ',', '.');
			$impuesto=number_format($field['impuesto'], 2, ',', '.');
			$monto_retenido=number_format($field['monto_retenido'], 2, ',', '.');
			$base=number_format($field['base'], 2, ',', '.');
			//$suma_retenido+=$field['monto_retenido'];
			$estado = strtoupper($field['estado']);
			if ($estado == "ANULADO") { $pdf->SetTextColor(255, 0, 0); $field['nombre'] = "(ANULADO)";  }
			else { $pdf->SetTextColor(0, 0, 0); $suma_retenido+=$field['monto_retenido']; }
			//----------------------------------------------------
			$pdf->Row(array($linea, $fecha_factura, $field['rif'], utf8_decode($field['nombre']), $nro_comprobante, $field['numero_factura'], $field['numero_control'], '01', $total, $exento, $base, $alicuota, $impuesto, $monto_retenido));
			$y=$pdf->GetY(); if ($y>190) { libro_compras_iva($pdf, $periodo, $desde, $hasta); }
			$linea++;
			$contador++;
		}
		$suma_retenido=number_format($suma_retenido, 2, ',', '.');
		$pdf->Ln(2);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$y=$pdf->GetY();
		$pdf->Rect(1, $y, 275, 0.1);
		$pdf->Ln(1);
		$pdf->Cell(275, 5, $suma_retenido, 0, 1, 'R', 1);
		break;


//	Libro Mensual (1x1000)...
	case "libro_compras_uno":
		$pdf=new PDF_MC_Table('L', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(1, 1, 1);
		$pdf->SetAutoPageBreak(1, 1);
		//	Datos Agente de Retencion
		$query=mysql_query("SELECT nombre_institucion, domicilio_legal, ciudad, estado, rif FROM configuracion") or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$nombre_agente=$field['nombre_institucion'];
			$rif_agente=$field['rif'];
			$queryEstado=mysql_query("SELECT denominacion FROM estado where idestado = '".$field['estado']."'") or die ($sql.mysql_error());
			$fieldEstado=mysql_fetch_array($queryEstado);
			$direccion_fiscal=$field['domicilio_legal']." ".$field['ciudad']." Estado ".$fieldEstado['denominacion'];
		}
		//----------------------------------------------------
		$dias_mes['01']=31; $dias_mes['03']=31; $dias_mes['04']=30; $dias_mes['05']=31; $dias_mes['06']=30;
		$dias_mes['07']=31; $dias_mes['08']=31; $dias_mes['09']=30; $dias_mes['10']=31; $dias_mes['11']=30; $dias_mes['12']=31;
		if ($anio%4==0) $dias_mes['02']=29; else $dias_mes['02']=28; 
		$dias=$dias_mes[$mes];
		if ($estado!="todos") $filtro_estado_r=" AND r.estado='".$estado."'";
		$periodo=$anio.$mes;
		$desde=$anio."-".$mes."-01"; 
		$hasta=$anio."-".$mes."-".$dias;
		//----------------------------------------------------
		libro_compras_uno($pdf, $periodo, $desde, $hasta, $nombre_agente, $rif_agente);
		//----------------------------------------------------
		$idtipo = '1x1000';
		$sql="(SELECT 
					r.fecha_aplicacion_retencion as fecha_retencion,
					r.idretenciones, 
					op.numero_orden,
					r.numero_documento, 
					r.numero_factura,
					r.numero_control,
					r.fecha_factura,
					r.fecha_aplicacion_retencion,
					r.total, 
					r.exento, 
					r.base, 
					r.impuesto, 
					rl.periodo, 
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.monto_retenido, 
					rl.porcentaje_impuesto, 
					rl.periodo, 
					o.idbeneficiarios,
					o.total as total_contrato,
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					r.estado,
					r.fecha_retencion
				FROM 
					retenciones r 
					INNER JOIN relacion_retenciones rl ON (r.idretenciones=rl.idretenciones AND rl.generar_comprobante = 'si') 
					INNER JOIN relacion_orden_pago_retencion ropr ON (r.idretenciones = ropr.idretencion)
					INNER JOIN orden_pago op ON (ropr.idorden_pago = op.idorden_pago)
					INNER JOIN tipo_retencion tr ON (rl.idtipo_retencion=tr.idtipo_retencion) 
					INNER JOIN orden_compra_servicio o ON (r.numero_documento=o.numero_orden) 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (r.fecha_aplicacion_retencion>='".$desde."' AND r.fecha_aplicacion_retencion<='".$hasta."')
					$filtro_estado_r)
					
				UNION 
				
				(SELECT 
				 	r.fecha_aplicacion_retencion as fecha_retencion, 
					r.idretenciones,
					rl.numero_orden as numero_documento,
					rl.numero_orden,
					rl.numero_factura,
					rl.numero_control,
					rl.fecha_factura,
					r.fecha_aplicacion_retencion,
					rl.total, 
					rl.exento, 
					rl.sub_total as base, 
					rl.impuesto, 
					rl.periodo, 
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.monto_retenido, 
					rl.alicuota as porcentaje_impuesto, 
					rl.periodo, 
					r.idbeneficiarios, 
					rl.monto_contrato as total_contrato,
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					r.estado,
					r.fecha_retencion
				FROM 
					retenciones r 
					INNER JOIN beneficiarios b ON (r.idbeneficiarios=b.idbeneficiarios) 
					INNER JOIN relacion_retenciones_externas rl ON (r.idretenciones=rl.idretencion) 
					INNER JOIN tipo_retencion tr ON (rl.idtipo_retencion=tr.idtipo_retencion)
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (r.fecha_aplicacion_retencion>='".$desde."' AND r.fecha_aplicacion_retencion<='".$hasta."')
					$filtro_estado_r)
					
				UNION
				
				(SELECT 
					r.fecha_aplicacion_retencion as fecha_retencion, 
					r.idretenciones,
					op.numero_orden,
					r.numero_documento,
					r.numero_factura,
					r.numero_control,
					r.fecha_factura,
					r.fecha_aplicacion_retencion,
					r.total, 
					r.exento, 
					r.base, 
					r.impuesto, 
					cr.periodo, 
					cr.idtipo_retencion, 
					cr.numero_retencion, 
					rl.monto_retenido, 
					rl.porcentaje_impuesto, 
					cr.periodo, 
					o.idbeneficiarios, 
					o.total as total_contrato,
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					cr.estado,
					cr.fecha_retencion
				FROM 
					retenciones r 
					INNER JOIN comprobantes_retenciones cr ON (r.idretenciones=cr.idretenciones AND cr.estado <> 'procesado') 
					INNER JOIN relacion_orden_pago_retencion ropr ON (r.idretenciones = ropr.idretencion)
					INNER JOIN orden_pago op ON (ropr.idorden_pago = op.idorden_pago)
					INNER JOIN tipo_retencion tr ON (cr.idtipo_retencion=tr.idtipo_retencion) 
					INNER JOIN relacion_retenciones rl ON (cr.idretenciones = rl.idretenciones AND cr.idtipo_retencion = rl.idtipo_retencion AND rl.generar_comprobante = 'si')
					INNER JOIN orden_compra_servicio o ON (r.numero_documento=o.numero_orden) 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (cr.fecha_retencion>='".$desde."' AND r.fecha_retencion<='".$hasta."')
					$filtro_estado_r)
				
				ORDER BY numero_retencion";
		
		
		//echo $sql;
				
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		$linea=1;
		for ($i=1; $i<=$rows; $i++) {
			$field=mysql_fetch_array($query);	
			$queryidop = mysql_query("select * from relacion_orden_pago_retencion where idretencion = '".$field["idretenciones"]."'");
			$campos = mysql_fetch_array($queryidop);
			$sql_concepto_externo = mysql_query("select * from relacion_retenciones_externas where idretencion = '".$field["idretenciones"]."'");
			if ($sql_concepto_externo){
				$bus_concepto = mysql_fetch_array($sql_concepto_externo);
				$concepto = $bus_concepto["concepto_orden"];
			}else{
				$concepto = '';
			}
			$sqlop = mysql_query("select * from orden_pago where idorden_pago = '".$campos["idorden_pago"]."'"); 
			$busop = mysql_fetch_array($sqlop);
			if ($concepto == ''){
				$concepto = $busop['justificacion'];
			}
			if ($busop["anticipo"]=='si'){
				$operacion = 'Anticipo';
			}
			if (($busop["anticipo"]=='no' or $busop["anticipo"]=='') && $busop["forma_pago"]=='parcial'){
				$operacion = utf8_decode('Valuación');
			}
			if (($busop["anticipo"]=='no' or $busop["anticipo"]=='') && $busop["forma_pago"]=='total'){
				$operacion = utf8_decode('Pago');
			}
			//----------------------------------------------------	
			//if ($i==1) $contador=$field['numero_retencion'];
			//if ($contador>$field['numero_retencion']) $contador=$field['numero_retencion'];
			//----------------------------------------------------	
			//list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_factura']); $fecha_factura=$d."/".$m."/".$a;
			
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_aplicacion_retencion']); $fecha_factura=$d."/".$m."/".$a;
			
			if ((8-strlen($field['numero_retencion'])) > 0){
				$nro_comprobante=(string) $a.$m.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
			}else{
				$nro_comprobante=(string) $a.$m.$field['numero_retencion'];
			}
			$total=number_format($field['total'], 2, ',', '.');
			$total_contrato=number_format($field['total_contrato'], 2, ',', '.');
			$exento=number_format($field['exento'], 2, ',', '.');
			$alicuota=number_format($field['porcentaje_impuesto'], 2, ',', '.');
			$impuesto=number_format($field['impuesto'], 2, ',', '.');
			$monto_retenido=number_format($field['monto_retenido'], 2, ',', '.');
			$base=number_format($field['base'], 2, ',', '.');
			//$suma_retenido+=$field['monto_retenido'];
			$estado = strtoupper($field['estado']);
			if ($estado == "ANULADO") { $pdf->SetTextColor(255, 0, 0); $field['nombre'] = "(ANULADO)";  }
			else { $pdf->SetTextColor(0, 0, 0); $suma_retenido+=$field['monto_retenido']; }
			//----------------------------------------------------
			$pdf->Row(array($linea, 
							utf8_decode($field['nombre']), 
							$field['rif'], 
							utf8_decode($concepto), 
							$total_contrato, 
							$operacion, 
							$fecha_factura,  
							'', 
							'', 
							$field['numero_orden'], 
							$total, 
							$total, 
							$base,
							$monto_retenido, 
							$monto_retenido));

			$y=$pdf->GetY(); if ($y>180 and $y<190) { libro_compras_uno($pdf, $periodo, $desde, $hasta, $nombre_agente, $rif_agente); }
			$linea++;
			$contador++;
		}
		$suma_retenido=number_format($suma_retenido, 2, ',', '.');
		$pdf->Ln(2);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$y=$pdf->GetY();
		$pdf->Rect(1, $y, 275, 0.1);
		$pdf->Ln(1);
		$pdf->Cell(275, 5, $suma_retenido, 0, 1, 'R', 1);
		
		$pdf->Ln(4);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(100, 100, 100); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 5);
		$pdf->SetAligns(array('C', 'C', 'C'));
		$pdf->SetWidths(array(92, 91, 90));
		$pdf->Row(array("Elaborado por","Presidencia o Direccion", "Administracion"));
		$pdf->Ln(5);
		$sql_configuracion = mysql_query("select * from configuracion_tributos");
		$bus_configuracion = mysql_fetch_array($sql_configuracion);
		$pdf->Row(array(utf8_decode($bus_configuracion["firma1"]), utf8_decode($bus_configuracion["firma2"]), utf8_decode($bus_configuracion["firma3"])));
		$pdf->Row(array(utf8_decode($bus_configuracion["cargofirma1"]), utf8_decode($bus_configuracion["cargofirma2"]), utf8_decode($bus_configuracion["cargofirma3"])));
		break;
	





	//	Retenciones Aplicadas...
	case "retenciones_aplicadas":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(10, 1, 1);
		$pdf->SetAutoPageBreak(1, 1);
		//----------------------------------------------------
		if ($quincena=="1") $dias=15;
		else {
			$dias_mes['01']=31; $dias_mes['03']=31; $dias_mes['04']=30; $dias_mes['05']=31; $dias_mes['06']=30;
			$dias_mes['07']=31; $dias_mes['08']=31; $dias_mes['09']=30; $dias_mes['10']=31; $dias_mes['11']=30; $dias_mes['12']=31;
			if ($anio%4==0) $dias_mes['02']=29; else $dias_mes['02']=28; 
			$dias=$dias_mes[$mes];
		}
		$periodo=$anio.$mes;
		if ($quincena=="1"){
			$desde=$anio."-".$mes."-01";
		}else if($quincena=="2"){
			$desde=$anio."-".$mes."-16";
		}else if($quincena=="3"){
			$desde=$anio."-".$mes."-01";
		}
		
		$hasta=$anio."-".$mes."-".$dias;
		
		if ($banco != 0 and $cuenta != 0){
			$bus_banco = mysql_query("select denominacion from banco where idbanco = '".$banco."'");
			$reg_banco = mysql_fetch_array($bus_banco);
			$nombre_banco = "BANCO: ".$reg_banco["denominacion"];
			$bus_cuenta = mysql_query("select numero_cuenta from cuentas_bancarias where idcuentas_bancarias = '".$cuenta."'");
			$reg_cuenta = mysql_fetch_array($bus_cuenta);
			$numero_cuenta = "CUENTA: ".$reg_cuenta["numero_cuenta"];
		}else{
			$nombre_banco = ''; 
			$numero_cuenta	= '';
		}
		if ($estado == "procesado") $filtro_estado_cr = "AND cr.estado <> 'anulado'";
		elseif ($estado == "anulado") $filtro_estado_r = "AND r.estado = 'anulado'"; 
		//----------------------------------------------------
		$sql="SELECT descripcion FROM tipo_retencion WHERE nombre_comprobante='".$idtipo."' GROUP BY nombre_comprobante";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) { $field=mysql_fetch_array($query); $tipo_retencion=$field['descripcion']; }
		//----------------------------------------------------
		retenciones_aplicadas($pdf, $periodo, $desde, $hasta, $tipo_retencion, $nombre_banco, $numero_cuenta);
		//----------------------------------------------------
		$sql="(SELECT 
					r.fecha_aplicacion_retencion as fecha_aplicacion_retencion, 
					r.numero_documento, 
					op.numero_orden,
					rl.periodo, 
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.monto_retenido, 
					rl.periodo, 
					o.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					r.estado,
					r.fecha_retencion,
					ropr.idorden_pago,
					r.tipo_retencion,
					rl.numero_retencion_referencia
					
				FROM 
					retenciones r 
					INNER JOIN relacion_retenciones rl ON (r.idretenciones=rl.idretenciones AND rl.generar_comprobante = 'si') 
					INNER JOIN relacion_orden_pago_retencion ropr ON (r.idretenciones = ropr.idretencion)
					INNER JOIN orden_pago op ON (ropr.idorden_pago = op.idorden_pago)
					INNER JOIN tipo_retencion tr ON (rl.idtipo_retencion=tr.idtipo_retencion) 
					INNER JOIN orden_compra_servicio o ON (r.numero_documento=o.numero_orden) 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (r.fecha_aplicacion_retencion>='".$desde."' AND r.fecha_aplicacion_retencion<='".$hasta."')
					$filtro_estado_r)
					
				UNION 
				
				(SELECT 
					r.fecha_aplicacion_retencion as fecha_aplicacion_retencion, 
					rl.numero_orden as numero_documento, 
					rl.numero_orden, 
					rl.periodo, 
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.monto_retenido, 
					rl.periodo, 
					r.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					r.estado,
					r.fecha_retencion,
					rl.numero_orden,
					r.tipo_retencion,
					rl.numero_retencion_referencia
				FROM 
					retenciones r 
					INNER JOIN beneficiarios b ON (r.idbeneficiarios=b.idbeneficiarios)
					INNER JOIN relacion_retenciones_externas rl ON (r.idretenciones=rl.idretencion) 
					INNER JOIN tipo_retencion tr ON (rl.idtipo_retencion=tr.idtipo_retencion)
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (r.fecha_aplicacion_retencion>='".$desde."' AND r.fecha_aplicacion_retencion<='".$hasta."'))
					
				UNION
				
				(SELECT 
					r.fecha_aplicacion_retencion as fecha_aplicacion_retencion, 
					r.numero_documento, 
					op.numero_orden,
					cr.periodo, 
					cr.idtipo_retencion, 
					cr.numero_retencion, 
					rl.monto_retenido, 
					cr.periodo, 
					o.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					cr.estado,
					cr.fecha_retencion,
					ropr.idorden_pago,
					r.tipo_retencion,
					rl.numero_retencion_referencia
				FROM 
					retenciones r 
					INNER JOIN comprobantes_retenciones cr ON (r.idretenciones=cr.idretenciones AND cr.estado <> 'procesado')
					INNER JOIN relacion_orden_pago_retencion ropr ON (r.idretenciones = ropr.idretencion)
					INNER JOIN orden_pago op ON (ropr.idorden_pago = op.idorden_pago)
					INNER JOIN tipo_retencion tr ON (cr.idtipo_retencion=tr.idtipo_retencion) 
					INNER JOIN relacion_retenciones rl ON (cr.idretenciones = rl.idretenciones AND cr.idtipo_retencion = rl.idtipo_retencion AND rl.generar_comprobante = 'si')
					INNER JOIN orden_compra_servicio o ON (r.numero_documento=o.numero_orden) 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (cr.fecha_retencion>='".$desde."' AND cr.fecha_retencion<='".$hasta."')
					$filtro_estado_cr)
				
				ORDER BY numero_retencion_referencia";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		
		for ($i=1; $i<=$rows; $i++) {
			$field=mysql_fetch_array($query);
			//----------------------------------------------------------------------------------
			if ($field["tipo_retencion"] <> '1'){
				if ($banco != '0' and $cuenta != '0'){
					$sql_cuenta_bancaria = mysql_query("select * from pagos_financieros where idorden_pago = '".$field["idorden_pago"]."'
																						AND idcuenta_bancaria = '".$cuenta."'");
					$cumple_condicion = mysql_num_rows($sql_cuenta_bancaria);
					if ($cumple_condicion > 0){
						$imprimir = 'si';
					}else{
						$imprimir = 'no';
					}
				}else{
					$imprimir = 'si';	
				}
			}else{
				$imprimir = 'si';
			}
			if ($imprimir == 'si'){
				//----------------------------------------------------------------------------------	
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_aplicacion_retencion']); $fecha_retencion=$d."/".$m."/".$a;
				if ((8-strlen($field['numero_retencion'])) > 0){
					$nro_comprobante=(string) $a.$m.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				}else{
					$nro_comprobante=(string) $a.$m.$field['numero_retencion'];
				}
				$monto_retenido=number_format($field['monto_retenido'], 2, ',', '.');		
				$estado = strtoupper($field['estado']);
				if ($estado == "ANULADO") { $pdf->SetTextColor(255, 0, 0); }
				else { $pdf->SetTextColor(0, 0, 0); $suma_retenido+=$field['monto_retenido']; }
				//----------------------------------------------------
				$pdf->Row(array($nro_comprobante, $field['numero_orden'], $field['rif'], utf8_decode($field['nombre'].' ('.$estado.')'), $fecha_retencion, $monto_retenido));
				$y=$pdf->GetY(); if ($y>250) { retenciones_aplicadas($pdf, $periodo, $desde, $hasta, $tipo_retencion, $nombre_banco, $numero_cuenta); }
				$imprimir = 'no';
			}
		}
		$suma_retenido=number_format($suma_retenido, 2, ',', '.');
		$pdf->Ln(2);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$y=$pdf->GetY();
		$pdf->Rect(10, $y, 195, 0.1);
		$pdf->Ln(1);
		$pdf->Cell(195, 5, $suma_retenido, 0, 1, 'R', 1);
		break;
		
	//	Anexos Orden de Pago...
	case "anexos_orden_pago":
		$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->SetTopMargin(0.5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 1.5);
		$pag=0;
		//-----------
		$sql="SELECT 
					 orden_pago.idorden_pago, 
					 orden_pago.fecha_orden, 
					 orden_pago.justificacion, 
					 orden_pago.numero_documento, 
					 orden_pago.fecha_documento, 
					 orden_pago.total,  
					 orden_pago.total_retenido, 
					 orden_pago.total_a_pagar, 
					 orden_pago.exento, 
					 orden_pago.sub_total
				FROM 
					 orden_pago
				WHERE 
					 (orden_pago.numero_orden='".$nro_orden."')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {
			$field=mysql_fetch_array($query);
			$idorden_pago=$field['idorden_pago'];
			$tipo_documento=$field['TipoDocumento'];
			$fecha=$field['fecha_orden'];
			$numero=$nro_orden;
		}
		
		//	RELACION DE RETENCIONES
		$sql="SELECT
				   tipo_retencion.idtipo_retencion,
				   tipo_retencion.codigo,
				   tipo_retencion.descripcion,
				   tipo_retencion.nombre_comprobante,
				   relacion_retenciones.idtipo_retencion,
				   SUM(relacion_retenciones.monto_retenido) AS MontoRetenido,
				   retenciones.idretenciones,
				   orden_compra_servicio.numero_orden,
				   relacion_pago_compromisos.idorden_compra_servicio
			FROM
				   tipo_retencion
				   INNER JOIN relacion_retenciones ON (tipo_retencion.idtipo_retencion=relacion_retenciones.idtipo_retencion)
				   INNER JOIN retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones)
				   INNER JOIN orden_compra_servicio ON (retenciones.numero_documento=orden_compra_servicio.numero_orden)
				   INNER JOIN relacion_pago_compromisos ON (orden_compra_servicio.idorden_compra_servicio=relacion_pago_compromisos.idorden_compra_servicio)
			WHERE
				   relacion_pago_compromisos.idorden_pago='".$idorden_pago."'
			GROUP BY tipo_retencion.nombre_comprobante
			ORDER BY tipo_retencion.idtipo_retencion DESC";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {
			$pag++;
			anexos_orden_pago_relacion($pdf, $numero, $fecha, $pag);
			
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query);
				$total=number_format($field['MontoRetenido'], 2, ',', '.'); $sum_total+=$field['MontoRetenido'];
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 10);
				$pdf->SetAligns(array('C', 'L', 'R'));
				$pdf->SetWidths(array(30, 135, 40));
				$pdf->Row(array(utf8_decode($field['codigo']), utf8_decode($field['descripcion']), $total));
			}
			$sum_total=number_format($sum_total, 2, ',', '.');
			$pdf->Ln(2);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 10);
			$y=$pdf->GetY();
			$pdf->Rect(5, $y, 205, 0.1);
			$pdf->Ln(1);
			$pdf->Cell(165, 5, '', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Cell(40, 5, $sum_total, 0, 1, 'R', 1);
		}
		
		$sum_exento=0; $sum_sub_total=0; $sum_impuesto=0; $sum_total=0; $sum_retencion=0; $sum_total_pagar=0;
		//	RELACION COMPROMISOS CANCELADOS
		$sql="SELECT relacion_pago_compromisos.idorden_compra_servicio, 
					 orden_compra_servicio.numero_orden, 
					 orden_compra_servicio.fecha_orden, 
					 orden_compra_servicio.exento, 
					 orden_compra_servicio.sub_total, 
					 orden_compra_servicio.impuesto, 
					 orden_compra_servicio.total, 
					 orden_compra_servicio.codigo_referencia,  
					 orden_compra_servicio.estado, 
					 retenciones.numero_factura, 
					 retenciones.numero_control, 
					 retenciones.fecha_factura, 
					 retenciones.total_retenido,
					 orden_pago.sub_total AS sub_total_op,
					 orden_pago.impuesto AS impuesto_op,
					 orden_pago.exento AS exento_op,
					 orden_pago.total AS total_op,
					 orden_pago.total_retenido AS total_retenido_op
				FROM 
					 relacion_pago_compromisos
					 INNER JOIN orden_compra_servicio ON (relacion_pago_compromisos.idorden_compra_servicio=orden_compra_servicio.idorden_compra_servicio) 
					 INNER JOIN orden_pago ON (relacion_pago_compromisos.idorden_pago=orden_pago.idorden_pago) 
					 LEFT OUTER JOIN retenciones ON (orden_compra_servicio.numero_orden=retenciones.numero_documento) 
				WHERE relacion_pago_compromisos.idorden_pago='".$idorden_pago."' 
				ORDER BY orden_compra_servicio.codigo_referencia";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {
			$pdf->AddPage(); $pag++;
			setLogo($pdf);
			
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(15, 4); $pdf->Cell(120, 5, '', 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell(50, 4, utf8_decode('Número: '), 0, 0, 'R');
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(35, 4, $numero, 0, 1, 'L');	
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(15, 4); $pdf->Cell(120, 5, '', 0, 0, 'L'); 
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell(50, 4, 'Fecha: ', 0, 0, 'R'); 
			$pdf->SetFont('Arial', 'B', 9);
			list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $fecha=$d."/".$m."/".$a;
			$pdf->Cell(35, 4, $fecha, 0, 1, 'L');	 
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell(185, 4, utf8_decode('Página: '), 0, 0, 'R'); 
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(35, 4, $pag, 0, 1, 'L'); 
			
			/////////////////////////////
			$pdf->Ln(10);
			$pdf->SetFont('Arial', 'B', 14);
			$pdf->Cell(200, 5, utf8_decode('Relación Compromisos Cancelados'), 0, 1, 'C');
			$pdf->Cell(200, 7, '', 0, 1, 'C');
			$pdf->Ln(5);		
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(20, 5, 'Nro.Orden', 1, 0, 'C', 1);
			$pdf->Cell(16, 5, 'Fecha', 1, 0, 'C', 1);
			$pdf->Cell(15, 5, 'Factura', 1, 0, 'C', 1);
			$pdf->Cell(15, 5, 'Control', 1, 0, 'C', 1);
			$pdf->Cell(15, 5, 'F.Factura', 1, 0, 'C', 1);
			if ($modulo==1) {
				$pdf->Cell(21, 5, 'Asignaciones', 1, 0, 'C', 1);
				$pdf->Cell(21, 5, 'Deducciones', 1, 0, 'C', 1);
			} else {
				$pdf->Cell(21, 5, 'Sub-Total', 1, 0, 'C', 1);
				$pdf->Cell(21, 5, 'Exento', 1, 0, 'C', 1);
			}
			$pdf->Cell(21, 5, 'Impuesto', 1, 0, 'C', 1);
			$pdf->Cell(21, 5, 'Total', 1, 0, 'C', 1);
			$pdf->Cell(21, 5, utf8_decode('Retención'), 1, 0, 'C', 1);
			$pdf->Cell(21, 5, 'Total a Pagar', 1, 1, 'C', 1);
			$pdf->Ln(2);
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_factura']); $fecha_factura=$d."/".$m."/".$a; if ($fecha_factura=="//") $fecha_factura="";
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha_orden=$d."/".$m."/".$a;
				if ($field['estado']=="parcial") {
					$exento=number_format($field['exento_op'], 2, ',', '.'); $sum_exento+=$field['exento_op'];
					$sub_total=number_format($field['sub_total_op'], 2, ',', '.'); $sum_sub_total+=$field['sub_total_op'];
					$impuesto=number_format($field['impuesto_op'], 2, ',', '.'); $sum_impuesto+=$field['impuesto_op'];
					$total=number_format($field['total_op'], 2, ',', '.'); $sum_total+=$field['total_op'];
					$retencion=number_format($field['total_retenido_op'], 2, ',', '.'); $sum_retencion+=$field['total_retenido_op'];
					$pagar=$field['total_op']-$field['total_retenido_op']; $sum_total_pagar+=$pagar;
					$total_pagar=number_format($pagar, 2, ',', '.');
				} else {
					$exento=number_format($field['exento'], 2, ',', '.'); $sum_exento+=$field['exento'];
					$sub_total=number_format($field['sub_total'], 2, ',', '.'); $sum_sub_total+=$field['sub_total'];
					$impuesto=number_format($field['impuesto'], 2, ',', '.'); $sum_impuesto+=$field['impuesto'];
					$total=number_format($field['total'], 2, ',', '.'); $sum_total+=$field['total'];
					$retencion=number_format($field['total_retenido'], 2, ',', '.'); $sum_retencion+=$field['total_retenido'];
					$pagar=$field['total']-$field['total_retenido']; $sum_total_pagar+=$pagar;
					$total_pagar=number_format($pagar, 2, ',', '.');
				}
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 7);
				$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'R', 'R', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(20, 16, 15, 15, 15, 21, 21, 21, 21, 21, 21));
				$pdf->Row(array($field['numero_orden'], $sum_total, $field['numero_factura'], $field['numero_control'], $fecha_factura, $sub_total, $exento, $impuesto, $total, $retencion, $total_pagar));
				$linea=$pdf->GetY(); if($linea>260) {
					$pdf->AddPage(); $pag++;
					setLogo($pdf);
					
					$pdf->SetFont('Arial', 'B', 9);
					$pdf->Cell(15, 4); $pdf->Cell(120, 5, '', 0, 0, 'L');
					$pdf->SetFont('Arial', '', 9);
					$pdf->Cell(50, 4, utf8_decode('Número: '), 0, 0, 'R');
					$pdf->SetFont('Arial', 'B', 9);
					$pdf->Cell(35, 4, $numero, 0, 1, 'L');	
					$pdf->SetFont('Arial', 'B', 9);
					$pdf->Cell(15, 4); $pdf->Cell(120, 5, '', 0, 0, 'L'); 
					$pdf->SetFont('Arial', '', 9);
					$pdf->Cell(50, 4, 'Fecha: ', 0, 0, 'R'); 
					$pdf->SetFont('Arial', 'B', 9);
					list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $fecha=$d."/".$m."/".$a;
					$pdf->Cell(35, 4, $fecha, 0, 1, 'L');	 
					$pdf->SetFont('Arial', '', 9);
					$pdf->Cell(185, 4, utf8_decode('Página: '), 0, 0, 'R'); 
					$pdf->SetFont('Arial', 'B', 9);
					$pdf->Cell(35, 4, $pag, 0, 1, 'L'); 
					
					/////////////////////////////
					$pdf->Ln(10);
					$pdf->SetFont('Arial', 'B', 14);
					$pdf->Cell(200, 5, utf8_decode('Relación Compromisos Cancelados'), 0, 1, 'C');
					$pdf->Cell(200, 7, '', 0, 1, 'C');
					$pdf->Ln(5);		
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 7);
					$pdf->Cell(20, 5, 'Nro.Orden', 1, 0, 'C', 1);
					$pdf->Cell(16, 5, 'Fecha', 1, 0, 'C', 1);
					$pdf->Cell(15, 5, 'Factura', 1, 0, 'C', 1);
					$pdf->Cell(15, 5, 'Control', 1, 0, 'C', 1);
					$pdf->Cell(15, 5, 'F.Factura', 1, 0, 'C', 1);
					if ($modulo==1) {
						$pdf->Cell(21, 5, 'Asignaciones', 1, 0, 'C', 1);
						$pdf->Cell(21, 5, 'Deducciones', 1, 0, 'C', 1);
					} else {
						$pdf->Cell(21, 5, 'Sub-Total', 1, 0, 'C', 1);
						$pdf->Cell(21, 5, 'Exento', 1, 0, 'C', 1);
					}
					$pdf->Cell(21, 5, 'Impuesto', 1, 0, 'C', 1);
					$pdf->Cell(21, 5, 'Total', 1, 0, 'C', 1);
					$pdf->Cell(21, 5, utf8_decode('Retención'), 1, 0, 'C', 1);
					$pdf->Cell(21, 5, 'Total a Pagar', 1, 1, 'C', 1);
					$pdf->Ln(2);
				}
			}
			$sum_exento=number_format($sum_exento, 2, ',', '.');
			$sum_sub_total=number_format($sum_sub_total, 2, ',', '.');
			$sum_impuesto=number_format($sum_impuesto, 2, ',', '.');
			$sum_total=number_format($sum_total, 2, ',', '.');
			$sum_retencion=number_format($sum_retencion, 2, ',', '.');
			$sum_total_pagar=number_format($sum_total_pagar, 2, ',', '.');
			$pdf->Ln(2);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			$y=$pdf->GetY();
			$pdf->Rect(5, $y, 205, 0.1);
			$pdf->Ln(1);
			$pdf->Cell(81, 5, 'Total Compromisos Relacionados ('.$rows.')', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(21, 5, $sum_sub_total, 0, 0, 'R', 1);
			$pdf->Cell(21, 5, $sum_exento, 0, 0, 'R', 1);
			$pdf->Cell(21, 5, $sum_impuesto, 0, 0, 'R', 1);
			$pdf->Cell(21, 5, $sum_total, 0, 0, 'R', 1);
			$pdf->Cell(21, 5, $sum_retencion, 0, 0, 'R', 1);
			$pdf->Cell(21, 5, $sum_total_pagar, 0, 1, 'R', 1);
		}
		break;
	
}
//	----------------------------------------
//	FIN CUERPO DE LOS REPORTES
//	----------------------------------------

$pdf->Output();
?>