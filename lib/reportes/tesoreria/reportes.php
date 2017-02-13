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
$nom_mes['01']="Enero";
$nom_mes['02']="Febrero";
$nom_mes['03']="Marzo";
$nom_mes['04']="Abril";
$nom_mes['05']="Mayo";
$nom_mes['06']="Junio";
$nom_mes['07']="Julio";
$nom_mes['08']="Agosto";
$nom_mes['09']="Septiembre";
$nom_mes['10']="Octubre";
$nom_mes['11']="Noviembre";
$nom_mes['12']="Diciembre";
//	----------------------------------------
$dias_mes['01']=31;
$dias_mes['03']=31;
$dias_mes['04']=30;
$dias_mes['05']=31;
$dias_mes['06']=30;
$dias_mes['07']=31;
$dias_mes['08']=31;
$dias_mes['09']=30;
$dias_mes['10']=31;
$dias_mes['11']=30;
$dias_mes['12']=31;
//	----------------------------------------
$sql="SELECT anio_fiscal FROM configuracion";
$query_conf=mysql_query($sql) or die ($sql.mysql_error());
$conf=mysql_fetch_array($query_conf);
$anio_fiscal = $conf['anio_fiscal'];
//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Imprimir Cheque...
	case "emitir_pagos_cheque":
		$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->SetTopMargin(1);
		$pdf->Open();
		$pdf->AddPage();
		$sql="SELECT 	pagos_financieros.idorden_pago, 
						pagos_financieros.beneficiario, 
						pagos_financieros.monto_cheque, 
						beneficiarios.nombre,
						pagos_financieros.beneficiario as nombre_beneficiario,
						pagos_financieros.fecha_cheque,
						pagos_financieros.idcuenta_bancaria
					FROM pagos_financieros, beneficiarios, orden_pago 
					WHERE pagos_financieros.idpagos_financieros='".$id_emision_pago."'
						and orden_pago.idorden_pago = pagos_financieros.idorden_pago
						and beneficiarios.idbeneficiarios = orden_pago.idbeneficiarios";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_cheque']); $fecha=$d."/".$m."/".$a;
		$dia=$d;
		$mes=$m;
		$annio=$a;
		if($mes == 1){$mes = "Enero";}
		if($mes == 2){$mes = "Febrero";}
		if($mes == 3){$mes = "Marzo";}
		if($mes == 4){$mes = "Abril";}
		if($mes == 5){$mes = "Mayo";}
		if($mes == 6){$mes = "Junio";}
		if($mes == 7){$mes = "Julio";}
		if($mes == 8){$mes = "Agosto";}
		if($mes == 9){$mes = "Septiembre";}
		if($mes == 10){$mes = "Octubre";}
		if($mes == 11){$mes = "Noviembre";}
		if($mes == 12){$mes = "Diciembre";}
		//	--------------------
		
		$sql_banco = mysql_query("select idbanco from cuentas_bancarias where idcuentas_bancarias = '".$field["idcuenta_bancaria"]."'")or die(mysql_error());
		$bus_banco = mysql_fetch_array($sql_banco);
		
		$sql_configuracion_cheque = mysql_query("select * from configuracion_cheques where idbanco = '".$bus_banco["idbanco"]."'")or die(mysql_error());
		$bus_configuracion_cheque = mysql_fetch_array($sql_configuracion_cheque)or die(mysql_error());
		
		$monto_letras=$pdf->ValorEnLetras($field["monto_cheque"], "");
		$monto=number_format($field['monto_cheque'], 2, ',', '.');
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->SetXY($bus_configuracion_cheque["izquierda_monto_numeros"], $bus_configuracion_cheque["alto_monto_numeros"]); $pdf->Cell(20, 10, $monto);
		$pdf->SetXY($bus_configuracion_cheque["izquierda_beneficiario"], $bus_configuracion_cheque["alto_beneficiario"]); $pdf->Cell(150, 10, utf8_decode($field['nombre_beneficiario']));
		$pdf->SetXY($bus_configuracion_cheque["izquierda_monto_letras"], $bus_configuracion_cheque["alto_monto_letras"]); $pdf->MultiCell(150, 6, '                    '.$monto_letras);
		
		$sql_consulta = mysql_query("select * from configuracion");
		$bus_consulta = mysql_fetch_array($sql_consulta);		
		$pdf->SetXY($bus_configuracion_cheque["izquierda_fecha"], $bus_configuracion_cheque["alto_fecha"]); $pdf->Cell(130, 9, $bus_consulta["ciudad"]." ".$dia.' de '.$mes);
		$pdf->SetXY($bus_configuracion_cheque["izquierda_ano"], $bus_configuracion_cheque["alto_ano"]); $pdf->Cell(130, 9, $annio);
		break;
	
	//	Anexo Orden de Pago...
	case "emitir_pagos_orden":
		$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->SetTopMargin(1);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 1.5);
		$pdf->Open();
		$pdf->AddPage();
		emitir_pagos_orden($pdf, $id_emision_pago);
		break;
	

	//	COMPROBANTE CONTABLE...
	case "comprobante_contable":
		$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->SetTopMargin(1);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 1.5);
		$pdf->Open();
		//$pdf->AddPage();
		$pag = 0;
		$sql_asiento_contable = mysql_query("select * from asiento_contable where iddocumento = '".$id_emision_pago."'
																			and tipo_movimiento = 'emision_pagos'");
		$bus_asiento_contable = mysql_fetch_array($sql_asiento_contable);
		$fecha_contable = $bus_asiento_contable["fecha_contable"];
		$pag++;
		comprobante_contable($pdf, $fecha_contable, $pag);

		$sql_emision_pago = mysql_query("select * from pagos_financieros where idpagos_financieros = '".$id_emision_pago."'");
		$bus_emision_pago = mysql_fetch_array($sql_emision_pago);

		$sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_emision_pago["idorden_pago"]."'");
		$bus_orden_pago = mysql_fetch_array($sql_orden_pago);

		$sql_configuracion_tesoreria = mysql_query("select * from configuracion_tesoreria, dependencias
																where dependencias.iddependencia = configuracion_tesoreria.iddependencia");
		$bus_configuracion_tesoreria = mysql_fetch_array($sql_configuracion_tesoreria);

		//$sql_beneficiario = mysql_query("select * from beneficiarios where ")
		$dependencia = $bus_configuracion_tesoreria["denominacion"];
		$beneficiario = $bus_emision_pago["beneficiario"];
		$documento = $bus_emision_pago["numero_cheque"];
		$justificacion = $bus_orden_pago["justificacion"];
		list($a, $m, $d)=SPLIT( '[/.-]', $fecha_contable); $fecha=$d."/".$m."/".$a;
		

		$pdf->SetXY(5, 40);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(28, 5, 'DEPENDENCIA:', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(165, 5, utf8_decode($dependencia), 0, 0, 'L');
		$pdf->SetXY(5, 47);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(28, 5, 'FECHA REGISTRO:', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(165, 5, $fecha, 0, 0, 'L');
		$pdf->SetXY(5, 54);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(28, 5, 'DOCUMENTO No:', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(165, 5, utf8_decode($documento), 0, 0, 'L');

		$pdf->SetXY(5, 61);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(28, 5, 'BENEFICIARIO:', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(165, 5, utf8_decode($beneficiario), 0, 0, 'L');
		$pdf->SetXY(5, 68);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(205, 5, 'DESCRIPCION:', 0, 1, 'L', 1); $pdf->Ln(2);
		$pdf->SetXY(15, 75);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 9);
		$pdf->MultiCell(190, 4, utf8_decode($justificacion), 0, 'L');


		//AFECTACION CONTABLE

			$sql_asiento_contable = mysql_query("select * from asiento_contable where iddocumento = '".$id_emision_pago."'
																				and tipo_movimiento = 'emision_pagos'")or die(" siete ".mysql_error());
			if (mysql_num_rows($sql_asiento_contable)>0){
				$bus_asiento_contable = mysql_fetch_array($sql_asiento_contable);
				$sql_cuentas_asiento = mysql_query("select * from cuentas_asiento_contable where idasiento_contable ='".$bus_asiento_contable["idasiento_contable"]."'");
				$num_cuentas_asiento = mysql_num_rows($sql_cuentas_asiento);
				
				//if ($num_cuentas_asiento <=2){
					$y=$pdf->GetY()+10;
					$pdf->SetFont('Arial', 'B', 9);
					$pdf->SetXY(5, $y); $pdf->Cell(50, 5, utf8_decode('AFECTACION CONTABLE'), 0, 1, 'L');
					$y=$pdf->GetY();
					$pdf->SetY($y);	
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(5, 5, '#', 1, 0, 'C', 1);
					$pdf->Cell(25, 5, 'CUENTA', 1, 0, 'C', 1);
					$pdf->Cell(115, 5, 'DENOMINACION', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, 'DEBE', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, 'HABER', 1, 0, 'C', 1);
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 9); 
					
					
					for ($i=1; $i<=$num_cuentas_asiento; $i++) {
						$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_asiento);
						$y+=5;
						$idcampo = "id".$bus_cuentas_contables["tabla"];
						$sql_cuentas = mysql_query("select * from ".$bus_cuentas_contables["tabla"]." 
																			where ".$idcampo." = '".$bus_cuentas_contables["idcuenta"]."'")or die(mysql_error());
						$bus_cuenta = mysql_fetch_array($sql_cuentas);
						
						if($bus_cuentas_contables["afecta"] == 'debe'){
							$monto_debe  =number_format($bus_cuentas_contables["monto"], 2, ',', '.');
							$monto_haber = '';
							$denominacion = $bus_cuenta["denominacion"];
							$suma_debe = $suma_debe + $bus_cuentas_contables["monto"];
						}else{
							$monto_haber =number_format($bus_cuentas_contables["monto"], 2, ',', '.');
							$monto_debe = '';
							$denominacion = '         '.$bus_cuenta["denominacion"];
							$suma_haber = $suma_haber + $bus_cuentas_contables["monto"];
						}
						
						
						//$descripcion=SUBSTR($field[2], 0, 65); 
						$h=5; $l=5; $x1=10.00125; $w1=10; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $i, 0, 'L');
						$h=5; $l=5; $x1=25.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $bus_cuenta["codigo"], 0, 'L');
						$h=5; $l=5; $x1=45.00125; $w1=120; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, substr(utf8_decode($denominacion),0,50), 0, 'L');
						$h=5; $l=5; $x1=155.00125; $w1=30; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto_debe, 0, 'R');
						$h=5; $l=5; $x1=185.00125; $w1=30; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto_haber, 0, 'R');
					}
					$total_debe  =number_format($suma_debe, 2, ',', '.');
					$total_haber  =number_format($suma_haber, 2, ',', '.');

					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(145, 5, 'TOTAL =>', 1, 0, 'R', 1);
					$pdf->SetFont('Arial', 'B', 9); 
					$pdf->Cell(30, 5, $total_debe, 1, 0, 'R', 1);
					$pdf->Cell(30, 5, $total_haber, 1, 0, 'R', 1);
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					
					
				//}
			}

		$sql_configuracion_contabiliad = mysql_query("select * from configuracion_contabilidad");
		$bus_configuracion_contabilidad = mysql_fetch_array($sql_configuracion_contabiliad);
		$y+=20;
		$pdf->SetXY(5, $y);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(28, 5, 'Elaborado por:', 0, 0, 'L');
		$pdf->SetXY(80, $y);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(28, 5, 'Conformado por:', 0, 0, 'L');
		$pdf->SetXY(150, $y);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(28, 5, 'Aprobado por:', 0, 0, 'L');
		$y+=20;
		$pdf->SetXY(5, $y);
		$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(165, 5, utf8_decode($bus_configuracion_contabilidad["elaborado_por"]), 0, 0, 'L');
		$pdf->SetXY(80, $y);
		$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(165, 5, utf8_decode($bus_configuracion_contabilidad["conformado_por"]), 0, 0, 'L');
		$pdf->SetXY(150, $y);
		$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(165, 5, utf8_decode($bus_configuracion_contabilidad["aprobado_por"]), 0, 0, 'L');
		$y+=4;
		$pdf->SetXY(5, $y);
		$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(165, 5, utf8_decode($bus_configuracion_contabilidad["ci_elaborado_por"]), 0, 0, 'L');
		$pdf->SetXY(80, $y);
		$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(165, 5, utf8_decode($bus_configuracion_contabilidad["ci_conformado_por"]), 0, 0, 'L');
		$pdf->SetXY(150, $y);
		$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(165, 5, utf8_decode($bus_configuracion_contabilidad["ci_aprobado_por"]), 0, 0, 'L');
		$y+=4;
		$pdf->SetXY(5, $y);
		$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(165, 5, utf8_decode($bus_configuracion_contabilidad["cargo_elaborado_por"]), 0, 0, 'L');
		$pdf->SetXY(80, $y);
		$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(165, 5, utf8_decode($bus_configuracion_contabilidad["cargo_conformado_por"]), 0, 0, 'L');
		$pdf->SetXY(150, $y);
		$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(165, 5, utf8_decode($bus_configuracion_contabilidad["cargo_aprobado_por"]), 0, 0, 'L');
		

		break;



	//	Imprimir Orden de Pago...
	case "emitir_orden_pago":
		$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->SetTopMargin(0.5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 1.5);
		$pag=0;
		//-----------
		$sql = "SELECT idorden_pago FROM pagos_financieros WHERE idpagos_financieros = '".$id_emision_pago."'";
		$query_orden = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_orden) != 0) $field_orden = mysql_fetch_array($query_orden);
		$idorden_pago = $field_orden['idorden_pago'];
		//-----------
		$sql="SELECT orden_pago.numero_orden, 
					 orden_pago.fecha_orden, 
					 orden_pago.justificacion, 
					 orden_pago.numero_documento, 
					 orden_pago.fecha_documento, 
					 orden_pago.total,  
					 orden_pago.total_retenido, 
					 orden_pago.total_a_pagar, 
					 orden_pago.exento, 
					 orden_pago.sub_total,
					 beneficiarios.nombre,
					 pagos_financieros.beneficiario as nombre_beneficiario,
					 tipos_documentos.documento_compromete,
					 tipos_documentos.descripcion AS TipoDocumento,
					 (SELECT td.modulo FROM tipos_documentos td WHERE td.idtipos_documentos=tipos_documentos.documento_compromete) AS modulo, 
					 tipos_documentos.idtipos_documentos 
				FROM 
					 orden_pago, 
					 beneficiarios,
					 tipos_documentos,
					 pagos_financieros
				WHERE 
					 (orden_pago.idorden_pago='".$idorden_pago."') 
					 AND orden_pago.idorden_pago = pagos_financieros.idorden_pago
					 AND (orden_pago.idbeneficiarios=beneficiarios.idbeneficiarios) 
					 AND (orden_pago.tipo=tipos_documentos.idtipos_documentos)";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {
			$field=mysql_fetch_array($query);
			$idtipo_documento=$field['idtipos_documentos'];
			$tipo_documento=$field['TipoDocumento'];
			$numero=$field['numero_orden'];
			$fecha=$field['fecha_orden'];
			$justificacion=$field['justificacion'];
			$nmemo=$field['numero_documento'];
			$fmemo=$field['fecha_documento'];
			$total=number_format($field['total'], 2, ',', '.');
			$total_retenido=number_format($field["total_retenido"], 2, ',', '.');
			$total_pagar=number_format($field["total_a_pagar"], 2, ',', '.');
			$exento=number_format($field["exento"], 2, ',', '.');
			$sub_total=number_format($field["sub_total"], 2, ',', '.');
			if ($field["total_a_pagar"]="NULL"){
				$total_pagar=number_format($field["5"]-$field["total_retenido"], 2, ',', '.');
			}
			$field['modulo']=explode("-",$field['modulo']);
			$modulo=$field['modulo'];
			$beneficiario=$field['nombre_beneficiario'];
			$sql="SELECT partidas_orden_pago.idorden_pago, 
						 maestro_presupuesto.anio, 
						 tipo_presupuesto.denominacion AS TipoPresupuesto, 
						 fuente_financiamiento.denominacion AS FuenteFinanciamiento 
					FROM 
						 partidas_orden_pago, 
						 maestro_presupuesto, 
						 tipo_presupuesto, 
						 fuente_financiamiento 
					WHERE 
						 (partidas_orden_pago.idorden_pago='".$idorden_pago."' AND 
						 partidas_orden_pago.idmaestro_presupuesto=maestro_presupuesto.idRegistro AND 
						 maestro_presupuesto.idtipo_presupuesto=tipo_presupuesto.idtipo_presupuesto AND 
						 maestro_presupuesto.idfuente_financiamiento=fuente_financiamiento.idfuente_financiamiento)";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			$sql_tiene_retencion = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago =".$idorden_pago."");
			$num_tiene_retencion = mysql_num_rows($sql_tiene_retencion);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				$anio=$field['anio'];
				$tpresupuesto=$field['TipoPresupuesto'];
				$ffinanciamiento=$field['FuenteFinanciamiento'];			
			}
		}
		
		$pag++;
		ordenpago($pdf, $numero, $fecha, $tipo_documento, $pag, $idtipo_documento);
		
		list($d, $m, $a)=SPLIT( '[/.-]', $fmemo); $fmemo=$a."/".$m."/".$d;
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->SetXY(5, 130); 
		$pdf->Cell(20, 5, utf8_decode('AÑO:  '.$anio), 0, 0, 'L');
		$pdf->Cell(80, 5, 'TIPO DE PRESUPUESTO:  '.$tpresupuesto, 0, 0, 'L');
		$pdf->Cell(105, 5, 'FUENTE DE FINANCIAMIENTO:  '.$ffinanciamiento, 0, 0, 'L');
		
		$pdf->Rect(5, 135, 205, 0.1);
		
		//	-----------
		if ((in_array(1,$modulo)==true or in_array(13,$modulo)==true) and $num_tiene_retencion == 0) {
			$pdf->SetXY(160, 136); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(20, 5, 'ASIGNACIONES: ', 0, 0, 'R'); $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(30, 5, $sub_total, 0, 0, 'R');
			$pdf->SetXY(160, 144); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(20, 5, 'DEDUCCIONES: ', 0, 0, 'R'); $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(30, 5, $exento, 0, 0, 'R');
			$pdf->SetXY(160, 152); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(20, 5, 'TOTAL: ', 0, 0, 'R'); $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(30, 5, $total, 0, 0, 'R');
		} else {
			$pdf->SetXY(160, 136); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(20, 5, 'TOTAL: ', 0, 0, 'R'); $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(30, 5, $total, 0, 0, 'R');
			$pdf->SetXY(160, 144); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(20, 5, 'RETENCIONES: ', 0, 0, 'R'); $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(30, 5, $total_retenido, 0, 0, 'R');
			$pdf->SetXY(160, 152); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(20, 5, 'TOTAL A PAGAR: ', 0, 0, 'R'); $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(30, 5, $total_pagar, 0, 0, 'R');
		}
		
		$pdf->SetXY(5, 95);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(28, 5, 'BENEFICIARIO:', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(165, 5, utf8_decode($beneficiario), 0, 0, 'L');
		$pdf->SetXY(5, 100);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(205, 5, 'CONCEPTO DETALLADO:', 1, 1, 'L', 1); $pdf->Ln(2);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 10);
		$pdf->MultiCell(200, 4, utf8_decode($justificacion), 0, 'L');
		
		//	-----------
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetXY(5, 135); 
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(73, 5, utf8_decode('Retención'), 0, 0, 'L');
		$pdf->Cell(25, 5, 'Sobre', 0, 0, 'R');
		$pdf->Cell(20, 5, '% Apl.', 0, 0, 'R');
		$pdf->Cell(25, 5, 'Retenido', 0, 1, 'R');
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->Rect(5, 140, 145, 0.1);
		
		$linea_y=140;
		$sql="SELECT
				   tipo_retencion.idtipo_retencion,
				   tipo_retencion.codigo,
				   tipo_retencion.descripcion,
				   tipo_retencion.nombre_comprobante,
				   relacion_retenciones.idtipo_retencion,
				   SUM(relacion_retenciones.base_calculo) AS base_calculo,
				   relacion_retenciones.porcentaje_aplicado,
				   SUM(relacion_retenciones.monto_retenido) AS MontoRetenido,
				   retenciones.idretenciones,
				   orden_compra_servicio.numero_orden,
				   orden_compra_servicio.idorden_compra_servicio,
				   relacion_pago_compromisos.idorden_compra_servicio
			FROM
				   tipo_retencion
				   INNER JOIN relacion_retenciones ON (tipo_retencion.idtipo_retencion=relacion_retenciones.idtipo_retencion)
				   INNER JOIN retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones)
				   INNER JOIN orden_compra_servicio ON (retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio)
				   INNER JOIN relacion_pago_compromisos ON (orden_compra_servicio.idorden_compra_servicio=relacion_pago_compromisos.idorden_compra_servicio)
			WHERE
				   relacion_pago_compromisos.idorden_pago='".$idorden_pago."'
			GROUP BY tipo_retencion.idtipo_retencion
			ORDER BY tipo_retencion.idtipo_retencion";
		
		$sql = "SELECT
					tr.idtipo_retencion,
					tr.codigo,
					tr.descripcion,
					tr.divisor,
					tr.nombre_comprobante,
					rr.idtipo_retencion,
					SUM(rr.base_calculo) AS base_calculo,
					rr.porcentaje_aplicado,
					SUM(rr.monto_retenido) AS MontoRetenido,
					r.idretenciones
				FROM
					relacion_orden_pago_retencion ropr
					INNER JOIN retenciones r ON (ropr.idretencion = r.idretenciones)
					INNER JOIN relacion_retenciones rr ON (ropr.idretencion = rr.idretenciones)
					INNER JOIN tipo_retencion tr ON (rr.idtipo_retencion = tr.idtipo_retencion)
				WHERE ropr.idorden_pago = '".$idorden_pago."'
				GROUP BY tr.idtipo_retencion
				ORDER BY tr.idtipo_retencion";
		
		$query_retenciones=mysql_query($sql) or die ($sql.mysql_error());
		$rows_retenciones=mysql_num_rows($query_retenciones);
		while ($field_retenciones=mysql_fetch_array($query_retenciones)) {
			$total=number_format($field_retenciones['MontoRetenido'], 2, ',', '.');
			if ($field_retenciones['porcentaje_aplicado']==0) { $sobre=""; $porcentaje=""; }
			else {
				$sobre=number_format($field_retenciones['base_calculo'], 2, ',', '.');
				
				if(strlen($field_retenciones['divisor'])>3){
					$porcentaje=number_format($field_retenciones['porcentaje_aplicado']/$field_retenciones['divisor'], 3, ',', '.');
				}else{
					list($entero,$decimal)=explode('.',$field_retenciones['porcentaje_aplicado']);
					if ($decimal==0){
						$porcentaje=number_format($field_retenciones['porcentaje_aplicado']/$field_retenciones['divisor'], 2, ',', '.');
					}else{
						$porcentaje=number_format($field_retenciones['porcentaje_aplicado']/$field_retenciones['divisor'], 3, ',', '.');
					}
						
				}
			}
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY(5, $linea_y); 
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell(73, 5, utf8_decode($field_retenciones['descripcion']), 0, 0, 'L');
			$pdf->Cell(25, 5, $sobre, 0, 0, 'R');
			$pdf->Cell(20, 5, $porcentaje, 0, 0, 'R');
			$pdf->Cell(25, 5, $total, 0, 1, 'R');
			$linea_y+=4;
		}
		
		if ($rows_retenciones>5) $linea_mas=$rows_retenciones-5; else $linea_mas=0;
		
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->Rect(150, 135, 0.1, 30+($linea_mas*5));
		
		$y=160+($linea_mas*5);
		$pdf->SetXY(5, $y); 
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'CAT.PROGR.', 1, 0, 'C', 1);
		$pdf->Cell(35, 5, 'PARTIDA', 1, 0, 'C', 1);
		$pdf->Cell(110, 5, 'DESCRIPCION', 1, 0, 'C', 1);
		$pdf->Cell(35, 5, 'MONTO', 1, 0, 'C', 1);
		
		//OBTENGO LAS PARTIDAS Y LAS IMPRIMO
		$sql = "SELECT pop.monto, 
						mp.idRegistro, 
						mp.idcategoria_programatica, 
						c.codigo, 
						cp.denominacion, 
						cp.partida, 
						cp.generica, 
						cp.especifica, 
						cp.sub_especifica, 
						o.codigo AS codordinal, 
						o.denominacion AS nomordinal 
					FROM 
						partidas_orden_pago pop 
						INNER JOIN maestro_presupuesto mp ON (pop.idmaestro_presupuesto = mp.idRegistro) 
						INNER JOIN categoria_programatica c ON (mp.idcategoria_programatica = c.idcategoria_programatica) 
						INNER JOIN clasificador_presupuestario cp ON (mp.idclasificador_presupuestario = cp.idclasificador_presupuestario) 
						INNER JOIN ordinal o ON (mp.idordinal = o.idordinal)
					WHERE 
						pop.idorden_pago = '".$idorden_pago."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		
		//	NO TENGO NI IDEA PARA QUE SIRVE ESTA CONSULTA....
		/*$sql_consulta = mysql_query("select * from orden_pago,
													categoria_programatica
													where 
											orden_pago.idorden_pago = '".$_GET["idorden_pago"]."'
											and categoria_programatica.idcategoria_programatica = orden_pago.idcategoria_programatica");
		$bus_consulta = mysql_fetch_array($sql_consulta);
		*/
		//	-------------------------------------------------
		
		emitir_pagos_orden($pdf, $id_emision_pago);
		
		if ($rows>(5-$linea_mas)) { 
			$pag++; 
			ordenpago_anexo($pdf, $numero, $fecha, $pag, $idtipo_documento);
			$pdf->SetXY(5, 35); $y=35;
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(25, 5, 'CAT.PROGR.', 1, 0, 'C', 1);
			$pdf->Cell(35, 5, 'PARTIDA', 1, 0, 'C', 1);
			$pdf->Cell(110, 5, 'DESCRIPCION', 1, 0, 'C', 1);
			$pdf->Cell(35, 5, 'MONTO', 1, 0, 'C', 1);		
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 9);
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query);
				$y+=5;
				$monto=number_format($field['monto'], 2, ',', '.');
				if ($field['codordinal'] != "0000") {
					$partida = $field['partida']." ".$field['generica']." ".$field['especifica']." ".$field['sub_especifica']." ".$field['codordinal'];
					$descripcion = SUBSTR($field['nomordinal'], 0, 50); 
				} else {
					$partida = $field['partida']." ".$field['generica']." ".$field['especifica']." ".$field['sub_especifica'];
					$descripcion = SUBSTR($field['denominacion'], 0, 50); 
				}
				
				$h=5; $l=4; $x1=10.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $field['codigo'], 0, 'C');
				$h=5; $l=4; $x1=35.00125; $w1=35; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $partida, 0, 'C');
				$h=5; $l=4; $x1=70.00125; $w1=110; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, utf8_decode($descripcion), 0, 'L');
				$h=5; $l=4; $x1=180.00125; $w1=35; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto, 0, 'R');
				if ($y>250) {
					$pag++; 
					ordenpago_anexo($pdf, $numero, $fecha, $pag, $idtipo_documento);			
					$pdf->SetXY(5, 35); $y=35;
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(25, 5, 'CAT.PROGR.', 1, 0, 'C', 1);
					$pdf->Cell(35, 5, 'PARTIDA', 1, 0, 'C', 1);
					$pdf->Cell(110, 5, 'DESCRIPCION', 1, 0, 'C', 1);
					$pdf->Cell(35, 5, 'MONTO', 1, 0, 'C', 1);		
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 9);
				}
			}


			//AFECTACION CONTABLE

			$sql_asiento_contable = mysql_query("select * from asiento_contable where iddocumento = '".$idorden_pago."'
																				and tipo_movimiento = 'causado'")or die(" siete ".mysql_error());
			if (mysql_num_rows($sql_asiento_contable)>0){
				$bus_asiento_contable = mysql_fetch_array($sql_asiento_contable);
				$sql_cuentas_asiento = mysql_query("select * from cuentas_asiento_contable where idasiento_contable ='".$bus_asiento_contable["idasiento_contable"]."'");
				$num_cuentas_asiento = mysql_num_rows($sql_cuentas_asiento);
				
				//if ($num_cuentas_asiento <=2){
					$y=$pdf->GetY()+10;
					$pdf->SetFont('Arial', 'B', 9);
					$pdf->SetXY(5, $y); $pdf->Cell(50, 5, utf8_decode('AFECTACION CONTABLE'), 0, 1, 'L');
					$y=$pdf->GetY();
					$pdf->SetY($y);	
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(25, 5, 'CUENTA', 1, 0, 'C', 1);
					$pdf->Cell(120, 5, 'DENOMINACION', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, 'DEBE', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, 'HABER', 1, 0, 'C', 1);
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 9); 
					
					
					for ($i=1; $i<=$num_cuentas_asiento; $i++) {
						$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_asiento);
						$y+=5;
						$idcampo = "id".$bus_cuentas_contables["tabla"];
						$sql_cuentas = mysql_query("select * from ".$bus_cuentas_contables["tabla"]." 
																			where ".$idcampo." = '".$bus_cuentas_contables["idcuenta"]."'")or die(mysql_error());
						$bus_cuenta = mysql_fetch_array($sql_cuentas);
						
						if($bus_cuentas_contables["afecta"] == 'debe'){
							$monto_debe  =number_format($bus_cuentas_contables["monto"], 2, ',', '.');
							$monto_haber = '';
							$denominacion = $bus_cuenta["denominacion"];
						}else{
							$monto_haber =number_format($bus_cuentas_contables["monto"], 2, ',', '.');
							$monto_debe = '';
							$denominacion = '         '.$bus_cuenta["denominacion"];
						}
						
						//$descripcion=SUBSTR($field[2], 0, 65); 
						$h=5; $l=4; $x1=10.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $bus_cuenta["codigo"], 0, 'L');
						$h=5; $l=4; $x1=35.00125; $w1=120; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, substr(utf8_decode($denominacion),0,50), 0, 'L');
						$h=5; $l=4; $x1=165.00125; $w1=30; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto_debe, 0, 'C');
						$h=5; $l=4; $x1=185.00125; $w1=30; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto_haber, 0, 'R');
					}
					
					
				//}
			}

		} else {	
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 9);
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query);
				$y+=5;
				$monto=number_format($field['monto'], 2, ',', '.');
				if ($field['codordinal'] != "0000") {
					$partida = $field['partida']." ".$field['generica']." ".$field['especifica']." ".$field['sub_especifica']." ".$field['codordinal'];
					$descripcion = SUBSTR($field['nomordinal'], 0, 50); 
				} else {
					$partida = $field['partida']." ".$field['generica']." ".$field['especifica']." ".$field['sub_especifica'];
					$descripcion = SUBSTR($field['denominacion'], 0, 50); 
				}
				$h=5; $l=4; $x1=10.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l,$field['codigo'], 0, 'C');
				$h=5; $l=4; $x1=35.00125; $w1=35; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $partida, 0, 'C');
				$h=5; $l=4; $x1=70.00125; $w1=110; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, utf8_decode($descripcion), 0, 'L');
				$h=5; $l=4; $x1=180.00125; $w1=35; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto, 0, 'R');
				
			}
		}
		

		/*
		AQUI VAN LAS CUENTAS CONTABLES DEL CAUSADO
		*/
		if ($rows<4 && $y<180) {
			
			$sql_asiento_contable = mysql_query("select * from asiento_contable where iddocumento = '".$idorden_pago."'
																				and tipo_movimiento = 'causado'")or die(" siete ".mysql_error());
			if (mysql_num_rows($sql_asiento_contable)>0){
				$bus_asiento_contable = mysql_fetch_array($sql_asiento_contable);
				$sql_cuentas_asiento = mysql_query("select * from cuentas_asiento_contable where idasiento_contable ='".$bus_asiento_contable["idasiento_contable"]."'");
				$num_cuentas_asiento = mysql_num_rows($sql_cuentas_asiento);
				
				if ($num_cuentas_asiento <=2){
					$y=$pdf->GetY()+6;
					$pdf->SetFont('Arial', 'B', 9);
					$pdf->SetXY(5, $y); $pdf->Cell(50, 5, utf8_decode('AFECTACION CONTABLE'), 0, 1, 'L');
					$y=$pdf->GetY();
					$pdf->SetY($y);	
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(25, 5, 'CUENTA', 1, 0, 'C', 1);
					$pdf->Cell(120, 5, 'DENOMINACION', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, 'DEBE', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, 'HABER', 1, 0, 'C', 1);
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 9); 
					
					
					for ($i=1; $i<=$num_cuentas_asiento; $i++) {
						$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_asiento);
						$y+=5;
						$idcampo = "id".$bus_cuentas_contables["tabla"];
						$sql_cuentas = mysql_query("select * from ".$bus_cuentas_contables["tabla"]." 
																			where ".$idcampo." = '".$bus_cuentas_contables["idcuenta"]."'")or die(mysql_error());
						$bus_cuenta = mysql_fetch_array($sql_cuentas);
						
						if($bus_cuentas_contables["afecta"] == 'debe'){
							$monto_debe  =number_format($bus_cuentas_contables["monto"], 2, ',', '.');
							$monto_haber = '';
							$denominacion = $bus_cuenta["denominacion"];
						}else{
							$monto_haber =number_format($bus_cuentas_contables["monto"], 2, ',', '.');
							$monto_debe = '';
							$denominacion = '         '.$bus_cuenta["denominacion"];
						}
						
						//$descripcion=SUBSTR($field[2], 0, 65); 
						$h=5; $l=4; $x1=10.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $bus_cuenta["codigo"], 0, 'L');
						$h=5; $l=4; $x1=35.00125; $w1=120; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, substr(utf8_decode($denominacion),0,50), 0, 'L');
						$h=5; $l=4; $x1=165.00125; $w1=30; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto_debe, 0, 'C');
						$h=5; $l=4; $x1=185.00125; $w1=30; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto_haber, 0, 'R');
					}
					
					
				}else{
					$pag++; 
					ordenpago_anexo($pdf, $numero, $fecha, $pag, $idtipo_documento);
					$pdf->SetXY(5, 35); $y=35;
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);

					$pdf->SetFont('Arial', 'B', 9);
					$pdf->SetXY(5, $y); $pdf->Cell(50, 5, utf8_decode('AFECTACION CONTABLE'), 0, 1, 'L');
					$y=$pdf->GetY();
					$pdf->SetY($y);	
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(25, 5, 'CUENTA', 1, 0, 'C', 1);
					$pdf->Cell(120, 5, 'DENOMINACION', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, 'DEBE', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, 'HABER', 1, 0, 'C', 1);
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 9); 
					
					
					for ($i=1; $i<=$num_cuentas_asiento; $i++) {
						$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_asiento);
						$y+=5;
						$idcampo = "id".$bus_cuentas_contables["tabla"];
						$sql_cuentas = mysql_query("select * from ".$bus_cuentas_contables["tabla"]." 
																			where ".$idcampo." = '".$bus_cuentas_contables["idcuenta"]."'")or die(mysql_error());
						$bus_cuenta = mysql_fetch_array($sql_cuentas);
						
						if($bus_cuentas_contables["afecta"] == 'debe'){
							$monto_debe  =number_format($bus_cuentas_contables["monto"], 2, ',', '.');
							$monto_haber = '';
							$denominacion = $bus_cuenta["denominacion"];
						}else{
							$monto_haber =number_format($bus_cuentas_contables["monto"], 2, ',', '.');
							$monto_debe = '';
							$denominacion = '         '.$bus_cuenta["denominacion"];
						}
						
						//$descripcion=SUBSTR($field[2], 0, 65); 
						$h=5; $l=4; $x1=10.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $bus_cuenta["codigo"], 0, 'L');
						$h=5; $l=4; $x1=35.00125; $w1=120; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, substr(utf8_decode($denominacion),0,50), 0, 'L');
						$h=5; $l=4; $x1=165.00125; $w1=30; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto_debe, 0, 'C');
						$h=5; $l=4; $x1=185.00125; $w1=30; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto_haber, 0, 'R');
					}
				}

			}
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
					 SUM(retenciones.total_retenido) AS total_retenido,
					 orden_pago.sub_total AS sub_total_op,
					 orden_pago.impuesto AS impuesto_op,
					 orden_pago.exento AS exento_op,
					 orden_pago.total AS total_op,
					 orden_pago.total_retenido AS total_retenido_op
				FROM 
					 relacion_pago_compromisos
					 INNER JOIN orden_compra_servicio ON (relacion_pago_compromisos.idorden_compra_servicio=orden_compra_servicio.idorden_compra_servicio) 
					 INNER JOIN orden_pago ON (relacion_pago_compromisos.idorden_pago=orden_pago.idorden_pago) 
					 LEFT OUTER JOIN retenciones ON (orden_compra_servicio.idorden_compra_servicio=retenciones.iddocumento) 
				WHERE relacion_pago_compromisos.idorden_pago='".$idorden_pago."' 
				GROUP BY relacion_pago_compromisos.idorden_compra_servicio
				ORDER BY orden_compra_servicio.codigo_referencia";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {
			$pag++;
			ordenpago_compromisos_cancelados($pdf, $numero, $fecha, $pag, $idtipo_documento, $modulo);
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
				$pdf->Row(array($field['numero_orden'], $field['fecha_orden'], $field['numero_factura'], $field['numero_control'], $fecha_factura, $sub_total, $exento, $impuesto, $total, $retencion, $total_pagar));
				$linea=$pdf->GetY(); if($linea>260) {
					$pag++;
					ordenpago_compromisos_cancelados($pdf, $numero, $fecha, $pag, $idtipo_documento, $modulo);
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
	
	//	Anexo Orden de Pago del Oficio...
	case "emitir_pagos_orden_oficio":
		$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->SetTopMargin(1);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 1.5);
		$pdf->Open();
		$dia=date("d");
		$mes=date("m");
		$annio=date("Y");
		//	--------------------
		$pdf->AddPage();
		$sql="SELECT 
					pagos_financieros.idorden_pago, 
					beneficiarios.nombre as beneficiario, 
					pagos_financieros.monto_cheque, 
					pagos_financieros.numero_cheque, 
					cuentas_bancarias.numero_cuenta, 
					banco.denominacion, 
					pagos_financieros.fecha_cheque,
					pagos_financieros.numero_documento 
				FROM 
					pagos_financieros, 
					cuentas_bancarias, 
					banco,
					beneficiarios,
					orden_pago 
				WHERE 
					pagos_financieros.idpagos_financieros='".$id_emision_pago."' 
					AND pagos_financieros.idcuenta_bancaria=cuentas_bancarias.idcuentas_bancarias 
					AND cuentas_bancarias.idbanco=banco.idbanco
					and orden_pago.idorden_pago = pagos_financieros.idorden_pago
					and beneficiarios.idbeneficiarios = orden_pago.idbeneficiarios";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		//	--------------------
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->SetXY(120, 247); $pdf->Cell(25, 9); $pdf->Cell(130, 5, $field['denominacion']);
		$pdf->SetXY(120, 256); $pdf->Cell(25, 9); $pdf->Cell(130, 5, $field['numero_cuenta']);
		$pdf->SetXY(120, 265); $pdf->Cell(25, 9); $pdf->Cell(130, 5, $field['numero_documento']);
		
		$pdf->SetFont('Arial', '', 6);
		$pdf->SetXY(145, 249); $pdf->Cell(50, 9, 'Banco');
		$pdf->SetXY(145, 258); $pdf->Cell(50, 9, 'Numero de Cuenta');
		$pdf->SetXY(145, 267); $pdf->Cell(50, 9, 'Numero de Oficio');
		
		$pdf->Rect(145, 251, 60, 0.1);
		$pdf->Rect(145, 260, 60, 0.1);
		$pdf->Rect(145, 269, 60, 0.1);
		break;
		
	//	Relacion de Ingresos y Egresos...
	case "relacion_ingresos_egresos":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 1, 1);
		$pdf->SetAutoPageBreak(1, 1);
		//----------------------------------------------------
		list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fdesde=$d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fhasta=$d."/".$m."/".$a;
		$filtro0="(ief.fecha>='$desde' AND ief.fecha<='$hasta') ";
		$filtro = " ";
		if ($tipo=="0" && $movimiento=="0" && $banco=="0" && $cuenta=="0") {  // si no selecciono ningun criterio
			$head=1; $w=205; $x=5; 
		}
		elseif ($tipo!="0" && $movimiento!="0" && $banco!="0" && $cuenta!="0") {  // si selecciono todos los criterios
			$head=2; $filtro.=" ief.tipo='".$tipo."' AND tmb.idtipo_movimiento_bancario='".$movimiento."' AND ief.idbanco='".$banco."' AND ief.idcuentas_bancarias='".$cuenta."' "; $w=75; $x=70; 
		}
		elseif ($tipo!="0" && $movimiento=="0" && $banco=="0" && $cuenta=="0") {  // si selecciono solo el tipo de movimiento
			$head=3; $filtro.=" ief.tipo='".$tipo."'"; $w=215; $x=5; 
		}
		elseif ($tipo!="0" && $movimiento!="0" && $banco=="0" && $cuenta=="0") {  // si selecciono el tipo de movimiento y el movimiento
			$head=4; $filtro.=" ief.tipo='".$tipo."' AND tmb.idtipo_movimiento_bancario='".$movimiento."' "; $w=205; $x=5; 
		}
		elseif ($tipo!="0" && $movimiento!="0" && $banco!="0" && $cuenta=="0") {  // si selecciono el tipo, el movimiento y el banco pero no la cuenta
			$head=5; $filtro.=" ief.tipo='".$tipo."' AND tmb.idtipo_movimiento_bancario='".$movimiento."' AND ief.idbanco='".$banco."' "; $w=135; $x=45; 	
		}
		elseif ($tipo!="0" && $movimiento=="0" && $banco!="0" && $cuenta!="0") {  // si selecciono el tipo, el banco y la cuenta pero no el movimiento
			$head=8; $filtro.=" ief.tipo='".$tipo."' AND ief.idbanco='".$banco."' AND ief.idcuentas_bancarias='".$cuenta."' "; $w=80; $x=70; 	
		}
		elseif ($tipo=="0" && $movimiento=="0" && $banco!="0" && $cuenta=="0") {  // si selecciono solo el banco pero no la cuenta
			$head=6; $filtro.=" ief.idbanco='".$banco."' "; $w=155; $x=45; 	
		}
		elseif ($tipo=="0" && $movimiento=="0" && $banco!="0" && $cuenta!="0") {  // si selecciono solo el banco Y la cuenta
			$head=7; $filtro.=" ief.idbanco='".$banco."' AND ief.idcuentas_bancarias='".$cuenta."' "; $w=105; $x=45; 	
		}
		if ($filtro <> " "){
			$filtro0 = $filtro0." AND ";	
		}
		//----------------------------------------------------
		$sql="SELECT 
					b.denominacion AS Banco, 
					cb.numero_cuenta, 
					ief.numero_documento, 
					ief.fecha, 
					ief.tipo, 
					ief.monto, 
					ief.concepto, 
					tmb.siglas, 
					tmb.denominacion AS Movimiento, 
					tmb.afecta 
				FROM 
					ingresos_egresos_financieros ief 
					INNER JOIN banco b ON (ief.idbanco=b.idbanco) 
					INNER JOIN cuentas_bancarias cb ON (ief.idcuentas_bancarias=cb.idcuentas_bancarias) 
					INNER JOIN tipo_movimiento_bancario tmb ON (ief.idtipo_movimiento=tmb.idtipo_movimiento_bancario) 
				WHERE $filtro0 $filtro AND ief.estado <> 'anulado'
					ORDER BY ief.fecha";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		
		$rows=mysql_num_rows($query);
		if ($rows > 0){
		for ($i=1; $i<=$rows; $i++) {
			$field=mysql_fetch_array($query);
			//----------------------------------------------------	
			if ($i==1) relacion_ingresos_egresos($pdf, $fdesde, $fhasta, $tipo, $field['Movimiento'], $field['Banco'], $field['numero_cuenta'], $head);
			//----------------------------------------------------
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha']); $fecha=$d."/".$m."/".$a;
			$monto=number_format($field['monto'], 2, ',', '.');
			if ($field['tipo']=="egreso") { $suma-=$field['monto']; $sig="-("; $sigc=")"; } else { $suma+=$field['monto']; $sig=""; $sigc=""; }
			//----------------------------------------------------
			//	Filtro periodo
			if ($head==1)
				$pdf->Row(array($field['Banco'], $field['numero_cuenta'], $field['numero_documento'], $fecha, strtoupper($field['afecta']), strtoupper($field['siglas']), $sig.$monto.$sigc));
			//	Filtro todos
			elseif ($head==2) {	$pdf->Cell(65, 4); $pdf->Row(array($field['numero_documento'], $fecha, $sig.$monto.$sigc)); }
			//	Filtro tipo
			elseif ($head==3) 
				$pdf->Row(array($field['Banco'], $field['numero_cuenta'], $field['numero_documento'], $fecha, strtoupper($field['siglas']), $sig.$monto.$sigc));
			//	Filtro tipo + movimiento
			elseif ($head==4) 
				$pdf->Row(array(utf8_decode($field['concepto']), $field['Banco'], $field['numero_cuenta'], $field['numero_documento'], $fecha, $sig.$monto.$sigc));
			//	Filtro tipo + movimiento + banco 
			elseif ($head==5) {	
				$pdf->Cell(40, 4); $pdf->Row(array($field['numero_cuenta'], $field['numero_documento'], $fecha, $sig.$monto.$sigc)); }
			//	Filtro banco 
			elseif ($head==6) {	
				$pdf->Cell(40, 4); $pdf->Row(array($field['numero_cuenta'], $field['numero_documento'], $fecha, strtoupper($field['afecta']), strtoupper($field['siglas']), $sig.$monto.$sigc)); }
			elseif ($head==7) {	
				$pdf->Cell(40, 4); $pdf->Row(array($field['numero_documento'], $fecha, strtoupper($field['afecta']), strtoupper($field['siglas']), $sig.$monto.$sigc)); }
			//	Filtro tipo + BANCO + CUENTA
			elseif ($head==8) {
				$pdf->Cell(65, 4); $pdf->Row(array($field['numero_documento'], $fecha, $sig.$monto.$sigc));
			}
			$y=$pdf->GetY(); if ($linea>250) { relacion_ingresos_egresos($pdf, $fdesde, $fhasta, $tipo, $field['Movimiento'], $field['Banco'], $field['numero_cuenta'], $head); }
		}
		
		$suma=number_format($suma, 2, ',', '.');
		$pdf->Ln(2);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$y=$pdf->GetY();
		$pdf->Rect($x, $y, $w, 0.1);
		$pdf->Ln(1);
		$pdf->SetX($x);
		$pdf->Cell($w, 5, $suma, 0, 1, 'R', 1);
		}else{
			$sql="SELECT 
					b.denominacion AS Banco, 
					cb.numero_cuenta, 
					ief.numero_documento, 
					ief.fecha, 
					ief.tipo, 
					ief.monto, 
					tmb.siglas, 
					tmb.denominacion AS Movimiento, 
					tmb.afecta 
				FROM 
					ingresos_egresos_financieros ief 
					INNER JOIN banco b ON (ief.idbanco=b.idbanco) 
					INNER JOIN cuentas_bancarias cb ON (ief.idcuentas_bancarias=cb.idcuentas_bancarias) 
					INNER JOIN tipo_movimiento_bancario tmb ON (ief.idtipo_movimiento=tmb.idtipo_movimiento_bancario) 
				WHERE $filtro and ief.estado <> 'anulado'
					ORDER BY ief.fecha";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$field=mysql_fetch_array($query);
			relacion_ingresos_egresos($pdf, $fdesde, $fhasta, $tipo, $field['Movimiento'], $field['Banco'], $field['numero_cuenta'], $head);
			$pdf->Cell(40, 4); 
			$pdf->Row(array("SIN REGISTROS", '', '', '', ''));
		}
		break;
	
	//	Relacion de Cheques...
	case "relacion_cheques":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 1, 1);
		$pdf->SetAutoPageBreak(1, 1);
		//----------------------------------------------------
		list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fdesde=$d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fhasta=$d."/".$m."/".$a;
		$filtro=" WHERE (b.idbanco='".$banco."' AND p.idcuenta_bancaria='".$cuenta."') ";
		if ($estado=="0" && $desde=="" && $hasta=="" && $idbeneficiario=="") { $head=1; $w=205; $x=5; }
		elseif ($estado!="0" && $desde!="" && $hasta!="" && $idbeneficiario!="") { $head=2; $filtro.="AND p.estado='".$estado."' AND p.fecha_cheque>='".$desde."' AND p.fecha_cheque<='".$hasta."' AND op.idbeneficiarios='".$idbeneficiario."' "; $w=95; $x=65; }
		elseif ($estado!="0" && $desde=="" && $hasta=="" && $idbeneficiario=="") { $head=3; $filtro.="AND p.estado='".$estado."'"; $w=185; $x=10; }
		elseif ($estado=="0" && $desde=="" && $hasta=="" && $idbeneficiario!="") { $head=4; $filtro.="AND op.idbeneficiarios='".$idbeneficiario."' "; $w=115; $x=50; }
		elseif ($estado=="0" && $desde!="" && $hasta!="" && $idbeneficiario=="") { $head=5; $filtro.="AND p.fecha_cheque>='".$desde."' AND p.fecha_cheque<='".$hasta."' "; $w=205; $x=5; }
		elseif ($estado!="0" && $desde=="" && $hasta=="" && $idbeneficiario!="") { $head=6; $filtro.="AND p.estado='".$estado."' AND op.idbeneficiarios='".$idbeneficiario."' "; $w=205; $x=5; }
		elseif ($estado!="0" && $desde!="" && $hasta!="" && $idbeneficiario=="") { 
			$head=7; 
			if ($estado == 'conciliado') {
				$filtro.="AND p.estado='".$estado."' AND p.fecha_cheque>='".$desde."' AND p.fecha_cheque<='".$hasta."' ";
			} else {
				$filtro.="AND p.estado='".$estado."' AND p.fecha_cheque>='".$desde."' AND p.fecha_cheque<='".$hasta."' ";
			} 
			$w=205; $x=5; }
		elseif ($estado=="0" && $desde!="" && $hasta!="" && $idbeneficiario!="") { $head=8; $filtro.="AND op.idbeneficiarios='".$idbeneficiario."' AND p.fecha_cheque>='".$desde."' AND p.fecha_cheque<='".$hasta."' "; $w=205; $x=5; }
		//----------------------------------------------------
		$sql="SELECT 
					p.monto_cheque,
					p.beneficiario, 
					p.estado, 
					p.numero_cheque, 
					p.fecha_cheque, 
					c.numero_cuenta, 
					b.denominacion AS Banco, 
					op.idbeneficiarios,
					op.numero_orden,
					op.justificacion 
				FROM 
					pagos_financieros p 
					INNER JOIN cuentas_bancarias c ON (p.idcuenta_bancaria=c.idcuentas_bancarias) 
					INNER JOIN orden_pago op ON (p.idorden_pago=op.idorden_pago) 
					INNER JOIN banco b ON (c.idbanco=b.idbanco) $filtro
					ORDER BY p.fecha_cheque";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=1; $i<=$rows; $i++) {
			$field=mysql_fetch_array($query);
			//----------------------------------------------------	
			if ($i==1) relacion_cheques($pdf, $fdesde, $fhasta, $field['Banco'], $field['numero_cuenta'], $estado, $field['beneficiario'], $head);
			//----------------------------------------------------
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_cheque']); $fecha=$d."/".$m."/".$a;
			$monto=number_format($field['monto_cheque'], 2, ',', '.');
			$suma+=$field['monto_cheque'];
			//----------------------------------------------------
			if ($field['estado'] == 'conciliado') { $estado = 'C'; }
			if ($field['estado'] == 'transito') { $estado = 'T'; }
			if ($field['estado'] == 'anulado') { $estado = 'A'; }
			//	Filtro Banco + Cuenta Bancaria

			if ($concepto == 'si'){
				$pdf->SetFont('Arial', 'B', 8);
			}
			$linea_y=$pdf->GetY();
			$pdf->SetXY(5, $linea_y+3); 
			
			if ($head==1)
				$pdf->Row(array($field['numero_cheque'], $fecha, $field['numero_orden'], utf8_decode($field['beneficiario']), $estado, $monto));
			//	Filtro todos
			elseif ($head==2) { $pdf->Cell(55, 4); $pdf->Row(array($field['numero_cheque'], $fecha, $field['numero_orden'], $monto)); }
			//	Filtro Banco + Cuenta Bancaria + Estado
			elseif ($head==3) { $pdf->Cell(10, 4); $pdf->Row(array($field['numero_cheque'], $fecha, $field['numero_orden'], utf8_decode($field['beneficiario']), $monto)); }
			//	Filtro Banco + Cuenta Bancaria + Beneficiario
			elseif ($head==4) { $pdf->Cell(45, 4); $pdf->Row(array($field['numero_cheque'], $fecha, $field['numero_orden'], $estado, $monto)); }
			//	Filtro Banco + Cuenta Bancaria + Fecha
			elseif ($head==5) {
				//$pdf->Cell(1, 4);
				$pdf->Row(array($field['numero_cheque'], $fecha, $field['numero_orden'], utf8_decode($field['beneficiario']), $estado, $monto)); }
			//	Filtro Banco + Cuenta Bancaria + Estado + Beneficiario
			elseif ($head==6) { $pdf->Cell(55, 4); $pdf->Row(array($field['numero_cheque'], $fecha, $field['numero_orden'], $monto)); }
			//	Filtro Banco + Cuenta Bancaria + Estado + Fecha
			elseif ($head==7) { $pdf->Cell(10, 4); $pdf->Row(array($field['numero_cheque'], $fecha, $field['numero_orden'], utf8_decode($field['beneficiario']), $monto)); }
			//	Filtro Banco + Cuenta Bancaria + Beneficiario + Fecha
			elseif ($head==8) { $pdf->Cell(45, 4); $pdf->Row(array($field['numero_cheque'], $fecha, $field['numero_orden'], $estado, $monto)); }
			$linea_y=$pdf->GetY();
			if ($concepto == 'si'){
				$pdf->Ln(1);
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); 
				$pdf->SetTextColor(0, 0, 0);
				$pdf->SetXY(40, $linea_y+2); 
				$pdf->SetFont('Arial', 'I', 8);
				$pdf->MultiCell(135, 4, utf8_decode($field['justificacion']), 0, 'L');
				$pdf->SetFont('Arial', '', 8);
				$linea_y=$pdf->GetY();
				$pdf->SetXY(5, $linea_y+3); 			
			}
			
			$y=$pdf->GetY(); if ($y>250) { relacion_cheques($pdf, $fdesde, $fhasta, utf8_decode($field['Banco']), $field['numero_cuenta'], $estado, utf8_decode($field['beneficiario']), $head); }
		}
		$suma=number_format($suma, 2, ',', '.');
		$pdf->Ln(2);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$y=$pdf->GetY();
		$y=$pdf->GetY();
		$pdf->Rect(5, $y, 205, 0.1);		
		$pdf->Ln(1);		
		$pdf->Cell(205, 5, $suma, 0, 1, 'R', 1);
		break;
	
	//	Relacion Cheques-OP...
	case "relacion_cheques_op":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 1, 1);
		$pdf->SetAutoPageBreak(1, 1);
		//----------------------------------------------------
		$sql="SELECT 
					pf.idpagos_financieros, 
					pf.idorden_pago, 
					pf.monto_cheque, 
					pf.beneficiario, 
					pf.numero_cheque, 
					o.numero_orden, 
					pf.fecha_cheque 
				FROM 
					pagos_financieros pf 
					INNER JOIN orden_pago o ON (pf.idorden_pago=o.idorden_pago) 
				WHERE pf.idorden_pago='".$id_emision_pago."'
				and pf.estado <> 'anulado'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=1; $i<=$rows; $i++) {
			$field=mysql_fetch_array($query);
			if ($i==1) { relacion_cheques_op($pdf, $field['numero_orden']); }
			//----------------------------------------------------	
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_cheque']); $fecha=$d."/".$m."/".$a;
			$monto=number_format($field['monto_cheque'], 2, ',', '.');
			$suma+=$field['monto_cheque'];
			//----------------------------------------------------
			$pdf->Cell(10, 5); $pdf->Row(array($field['numero_cheque'], $fecha, utf8_decode($field['beneficiario']), $monto));
			$y=$pdf->GetY(); if ($linea>250) { relacion_cheques_op($pdf, $field['numero_orden']); }
		}
		$suma=number_format($suma, 2, ',', '.');
		$pdf->Ln(2);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$y=$pdf->GetY();
		$pdf->Rect(15, $y, 185, 0.1);
		$pdf->Ln(1);
		$pdf->Cell(195, 5, $suma, 0, 1, 'R', 1);
		break;
	
	//	Conciliacion...
	case "conciliacion":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(10, 1, 1);
		$pdf->SetAutoPageBreak(1, 1);
		//	----------------------------------------------------
		//	----------------------------------------------------
		$a=(int) $anio; 
		$m=(int) $mes; $mm = --$m; 
		if ($mm==0) { //--$a; 
			$alafecha="01/01/$a"; 
			$fapertura="$a-01-01"; 
		} 
		elseif ($m==2) { if ($a%4==0) $d=29; else $d=28; $alafecha="$d/02/$a"; $fapertura="$a-02-$d"; }
		else {
			if ($m<10) $m="0$m";
			$d=$dias_mes[$m];
			$alafecha="$d/$m/$a";
			$fapertura="$a-$m-$d";
		}
		//	----------------------------------------------------
		//	----------------------------------------------------
		list($d, $m, $a)=SPLIT( '[/.-]', $alafecha); 
		$alafecha_hasta = "$a-$m-$d";
		$alafecha_desde = "$a-$m-01"; 
		//	----------------------------------------------------
		if ($anio%4==0) $dias_mes['02']=29; else $dias_mes['02']=28;
		$d=$dias_mes[$mes];
		$desde="$anio-$mes-01"; $hasta="$anio-$mes-$d"; $fhasta="$d-$mes-$anio";
		//	----------------------------------------------------
		//	Obtengo la cabecera
		$sql="SELECT
					c.numero_cuenta,
					c.uso_cuenta,
					c.monto_apertura,
					c.fecha_apertura,
					b.denominacion AS Banco,
					t.denominacion AS Cuenta
				FROM
					cuentas_bancarias c
					INNER JOIN banco b ON (c.idbanco=b.idbanco)
					INNER JOIN tipo_cuenta_bancaria t ON (c.idtipo_cuenta=t.idtipo_cuenta)
				WHERE
					c.idcuentas_bancarias='".$cuenta."'";
		$query_head=mysql_query($sql) or die ($sql.mysql_error());
		$rows_head=mysql_num_rows($query_head);
		if ($rows_head!=0) $field_head=mysql_fetch_array($query_head);
		$periodo=strtoupper($nom_mes[$mes]).' '.$anio;
		//	----------------------------------------------------
		//	Imprimo el head
		conciliacion($pdf, $field_head['Banco'], $field_head['Cuenta'], $field_head['numero_cuenta'], $field_head['uso_cuenta'], $periodo);
		if ($field_head['fecha_apertura']<=$fapertura){
			//	Si la fecha de apertura es menor que el mes seleccionado entonces imprimo el reporte...
			//	----------------------------------------------------
			//	Imprimo el saldo disponible a la fecha
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 6);
			$pdf->SetAligns(array('L', 'R', 'R', 'R', 'R'));
			$pdf->SetWidths(array(75, 30, 30, 30, 30));
			
			
			// OBTENGO EL SALDO DEL BANCO A LA FECHA DESDE
			if ($field_head['fecha_apertura'] == $desde){
				$sum_saldo = $field_head["monto_apertura"];
				$sum_saldo_libro = $field_head["monto_libro"];
			}else{
				// OBTENGO LA SUMA DE LOS INGRESOS
				$sum_saldo = $field_head["monto_apertura"];
				$sum_saldo_libro = $field_head["monto_libro"];
				$sql_suma="SELECT
						SUM(i.monto) AS Monto
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='ingreso'
						AND i.fecha>='".$field_head['fecha_apertura']."' 
						AND i.fecha<'".$desde."'
						AND i.estado <> 'anulado'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo += $field_suma["Monto"];
				$sum_saldo_libro += $field_suma["Monto"];
				// RESTO LOS EGRESOS
				$sql_suma="SELECT
						SUM(i.monto) AS Monto
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='egreso'
						AND i.fecha>='".$field_head['fecha_apertura']."' 
						AND i.fecha<'".$desde."'
						AND i.estado <> 'anulado'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo -= $field_suma["Monto"];
				$sum_saldo_libro -= $field_suma["Monto"];
				//OBTENGO LA SUMA DE LOS CHEQUES CONCILIADOS
				
				$sql_suma="SELECT
						SUM(p.monto_cheque) AS Monto
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND p.estado='conciliado'
						AND p.fecha_conciliado>='".$field_head['fecha_apertura']."' 
						AND p.fecha_conciliado<'".$desde."'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo -= $field_suma["Monto"];
				$sum_saldo_libro -= $field_suma["Monto"];
				
			}
			
			$total_apertura = $sum_saldo;
			$monto_apertura = number_format($sum_saldo, 2, ',', '.');
			$pdf->SetFont('Arial', '', 9);
			$pdf->Row(array('SALDO DISPONIBLE AL '.$alafecha, $monto_apertura, '', '', ''));
			$pdf->Ln(4);
			
			//	----------------------------------------------------
			//	Obtengo la suma de los ingresos a la fecha
			$sql = "SELECT
						SUM(i.monto) AS Monto,
						
						(SELECT SUM(p.monto_cheque) AS Monto
							FROM pagos_financieros p
								WHERE
									p.idcuenta_bancaria='".$cuenta."'
									AND p.estado='anulado'
									AND p.fecha_anulacion>='$desde' AND p.fecha_anulacion<='$hasta') AS MontoNulos
					FROM
						ingresos_egresos_financieros i
						
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='ingreso'
						AND i.fecha>='$desde' AND i.fecha<='$hasta'
						AND i.estado <> 'anulado'
					";
			$query_acredita = mysql_query($sql) or die ($sql.mysql_error());
			$field_acredita = mysql_fetch_array($query_acredita);
			$total_acredita = $field_acredita['Monto'] + $field_acredita['MontoNulos'];
			
			//	Obtengo la suma de los egresos a la fecha
			$sql = "SELECT
						SUM(i.monto) AS Monto,
						(SELECT SUM(p.monto_cheque) AS Monto
							FROM pagos_financieros p
								WHERE
									p.idcuenta_bancaria='".$cuenta."'
									AND p.estado='conciliado'
									AND p.fecha_conciliado>='$desde' AND p.fecha_conciliado<='$hasta') AS MontoConciliado
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='egreso'
						AND i.fecha>='$desde' AND i.fecha<='$hasta'
						AND i.estado <> 'anulado'";
			
			
			$query_debita = mysql_query($sql) or die ($sql.mysql_error());
			$field_debita = mysql_fetch_array($query_debita);
			$total_debita = $field_debita['MontoConciliado'] + $field_debita['Monto'];
			//	-
			/*	Imprimo Deposito
			$pdf->SetFont('Arial', '', 10);
			$total_deposito = number_format($field_acredita['Monto'], 2,',', '.');
			$pdf->Row(array('TOTAL INGRESOS DURANTE EL MES', $total_deposito, '', ''));
			$pdf->Ln(4);*/
			
			//	Imprimo la sumatoria de ingresos
			//$total_ingresos = $total_apertura + $field_acredita['Monto'];
			//$pdf->SetFont('Arial', '', 8);
			//$total_ingreso = number_format($total_ingresos, 2,',', '.');
			//$pdf->Row(array('TOTAL INGRESOS DURANTE EL MES', $total_ingreso, '', ''));
			//$pdf->Ln(4);
			//	----------------------------------------------------
			
			// DESGLOCE DE INGRESOS
			$sql_tipo_movimiento = mysql_query("select * from tipo_movimiento_bancario where afecta = 'a'");
			while($bus_tipo_movimiento = mysql_fetch_array($sql_tipo_movimiento)){
				//	Obtengo la suma de los egresos a la fecha
				$sql = "SELECT
						SUM(i.monto) AS MontoMovimiento
					FROM
						ingresos_egresos_financieros i
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.idtipo_movimiento='".$bus_tipo_movimiento["idtipo_movimiento_bancario"]."'
						AND i.fecha>='$desde' AND i.fecha<='$hasta'
						AND i.estado <> 'anulado'
					";
				
				$query_movimiento = mysql_query($sql) or die ($sql.mysql_error());
				$field_movimiento = mysql_fetch_array($query_movimiento);
				
				if ($field_movimiento["MontoMovimiento"] > 0){
					//	Imprimo total del movimiento
					$monto_movimiento = number_format($field_movimiento['MontoMovimiento'], 2, ',', '.');
					$pdf->SetFont('Arial', '', 8);
					$pdf->Row(array('          '.utf8_decode($bus_tipo_movimiento["denominacion"]), $monto_movimiento, '', ''));
					$pdf->Ln(4);
				}
			}
			//	Imprimo total nulos
			$cheque_nulos = number_format($field_acredita['MontoNulos'], 2, ',', '.');
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array('          CHEQUES NULOS', $cheque_nulos, '', ''));
			$pdf->Ln(4);
			//	----------------------------------------------------
			
			
			//	Imprimo total de ingresos
			$pdf->SetFont('Arial', '', 9);
			$total_deposito = number_format($field_acredita['Monto'], 2,',', '.');
			$pdf->Row(array('TOTAL INGRESOS DURANTE EL MES', $total_deposito, '', ''));
			$pdf->Ln(4);
			
			//	Imprimo total de DISPONIBLE
			$pdf->SetFont('Arial', '', 9);
			$total_deposito = number_format($field_acredita['Monto']+$total_apertura, 2,',', '.');
			$pdf->Row(array('TOTAL DISPONIBLE DURANTE EL MES', $total_deposito, '', ''));
			$pdf->Ln(4);
			
			// IMPRIMO EL TOTAL DE EGRESOS POR CHEQUES CONCILIADOS
			$total_conciliado = $field_debita['MontoConciliado'];
			$total_egresos_conciliados = number_format($total_conciliado, 2, ',', '.');
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array('          EGRESOS DURANTE EL MES', '', $total_egresos_conciliados));
			$pdf->Ln(4);
			
			
			// DESGLOCE DE EGRESOS
			$sql_tipo_movimiento = mysql_query("select * from tipo_movimiento_bancario where afecta = 'd'");
			while($bus_tipo_movimiento = mysql_fetch_array($sql_tipo_movimiento)){
				//	Obtengo la suma de los egresos a la fecha
				$sql = "SELECT
						SUM(i.monto) AS MontoMovimiento
					FROM
						ingresos_egresos_financieros i
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.idtipo_movimiento='".$bus_tipo_movimiento["idtipo_movimiento_bancario"]."'
						AND i.fecha>='$desde' AND i.fecha<='$hasta'
						AND i.estado <> 'anulado'
					";
				
				$query_movimiento = mysql_query($sql) or die ($sql.mysql_error());
				$field_movimiento = mysql_fetch_array($query_movimiento);
				
				if ($field_movimiento["MontoMovimiento"] > 0){
					//	Imprimo total del movimiento
					$monto_movimiento = number_format($field_movimiento['MontoMovimiento'], 2, ',', '.');
					$pdf->SetFont('Arial', '', 8);
					$pdf->Row(array('          '.utf8_decode($bus_tipo_movimiento["denominacion"]), '', $monto_movimiento,  ''));
					$pdf->Ln(4);
				}
			}
			
			//	Imprimo egresos del mes
			$total_egresos = number_format($total_debita, 2, ',', '.');
			$pdf->SetFont('Arial', '', 9);
			$pdf->Row(array('TOTAL EGRESOS DURANTE EL MES', '', $total_egresos));
			$pdf->Ln(4);
			
			
			
			$sql_transito="SELECT
						SUM(p.monto_cheque) AS MontoTransito						
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND ((p.estado='transito' AND p.fecha_cheque<='$hasta')
							OR (p.estado='conciliado' AND p.fecha_cheque<='$hasta' AND p.fecha_conciliado>'$hasta')
							OR (p.estado='anulado' AND p.fecha_cheque<='$hasta' AND p.fecha_anulacion>'$hasta'))";
			
			$query_transito = mysql_query($sql_transito) or die ($sql_transito.mysql_error());
			$field_transito = mysql_fetch_array($query_transito);
			
			
			$saldo_banco = $total_apertura + $field_acredita['Monto'] + $field_acredita['MontoNulos'] - $total_debita ;
			
			$saldo_libro = $total_apertura + $field_acredita['Monto'] + $field_acredita['MontoNulos'] - $field_transito['MontoTransito'] - $total_debita;
			$saldo_b=number_format($saldo_banco, 2, ',', '.');
			$saldo_l=number_format($saldo_libro, 2, ',', '.');
			
			
			//$saldo_libro = $total_apertura + $field_acredita['Monto'] + $field_acredita['MontoNulos'] - $field_transito['MontoTransito'] - $total_debita;
			//$saldo_l=number_format($saldo_libro, 2, ',', '.');
			$pdf->SetFont('Arial', '', 9);			
			$pdf->Row(array('SALDO SEGUN BANCO AL '.$fhasta, '', '',  $saldo_b, '',));
			$pdf->Ln(2);
			
			
			//	----------------------------------------------------
			//list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fhasta=$d."/".$m."/".$a;
			//	Imprimo saldo segun libro
			//$saldo=number_format($saldo_libro, 2, ',', '.');
			//$pdf->SetFont('Arial', '', 8);
			//$pdf->Row(array('SALDO SEGUN LIBRO AL '.$fhasta, '', '', $saldo, ''));
			//$pdf->Ln(4);
			//	----------------------------------------------------
			//	Imprimo total cheques en transito
			
			
			
			$total_transito = number_format($field_transito['MontoTransito'], 2, ',', '.');
			$pdf->SetFont('Arial', '', 9);
			$pdf->Row(array('CHEQUES EN TRANSITO', '', '', $total_transito, ''));
			$pdf->Ln(4);
			//	----------------------------------------------------
			//	Imprimo saldo segun banco
			
			$pdf->SetFont('Arial', '', 9);
			$pdf->Row(array('SALDO SEGUN LIBRO AL '.$fhasta, '', '', '', $saldo_l));
			$pdf->Ln(2);
			
			//	----------------------------------------------------
			$pdf->Ln(2);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y=$pdf->GetY();
			$x=$pdf->GetX();
			$pdf->Rect($x, $y, 195, 0.1);
			$pdf->Ln(5);
			//	----------------------------------------------------
			$y=$pdf->GetY();
			$y+=20;
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$pdf->SetY($y);
			$pdf->Rect(70, $y, 75, 0.1);
			$pdf->SetFont('Arial', 'B', 8);
			//	Obtengo la firma
			$sql="SELECT
						primero_tesoreria,
						cargo_primero_tesoreria
					FROM
						configuracion_tesoreria";
			$query_firma=mysql_query($sql) or die ($sql.mysql_error());
			$rows_firma=mysql_num_rows($query_firma);
			if ($rows_firma!=0) $field_firma=mysql_fetch_array($query_firma);
			//	----------------------------------------------------
			$pdf->Cell(195, 5, $field_firma['primero_tesoreria'], 0, 1, 'C');
			$pdf->Cell(195, 5, $field_firma['cargo_primero_tesoreria'], 0, 1, 'C');
		} else {
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Cell(195, 20, 'AL '.$alafecha.' NO SE HABIA REGISTRADO MONTO DE APERTURA', 0, 1, 'C');
			$pdf->Ln(2);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y=$pdf->GetY();
			$x=$pdf->GetX();
			$pdf->Rect($x, $y, 195, 0.1);
			$pdf->Ln(5);
		}
		break;
	
	//	Estado de Cuenta...
	case "estado_cuenta":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(10, 1, 1);
		$pdf->SetAutoPageBreak(1, 1);
		//	----------------------------------------------------
		$a=(int) $anio; 
		$m=(int) $mes; $mm = --$m; 
		if ($mm==0) { //--$a; 
			$alafecha="01/01/$a"; 
			$fapertura="$a-01-01"; 
		} 
		elseif ($m==2) { if ($a%4==0) $d=29; else $d=28; $alafecha="$d/02/$a"; $fapertura="$a-02-$d"; }
		else {
			if ($m<10) $m="0$m";
			$d=$dias_mes[$m];
			$alafecha="$d/$m/$a";
			$fapertura="$a-$m-$d";
		}
		//	----------------------------------------------------
		list($d, $m, $a)=SPLIT( '[/.-]', $alafecha); 
		$alafecha_hasta = "$a-$m-$d";
		$alafecha_desde = "$a-$m-01"; 
		//	----------------------------------------------------
		if ($anio%4==0) $dias_mes['02']=29; else $dias_mes['02']=28;
		$d=$dias_mes[$mes];
		$desde="$anio-$mes-01"; $hasta="$anio-$mes-$d";
		//	----------------------------------------------------
		//	Obtengo la cabecera
		$sql="SELECT
					c.numero_cuenta,
					c.uso_cuenta,
					c.monto_apertura,
					c.fecha_apertura,
					b.denominacion AS Banco,
					t.denominacion AS Cuenta
				FROM
					cuentas_bancarias c
					INNER JOIN banco b ON (c.idbanco=b.idbanco)
					INNER JOIN tipo_cuenta_bancaria t ON (c.idtipo_cuenta=t.idtipo_cuenta)
				WHERE
					c.idcuentas_bancarias='".$cuenta."'";
		$query_head=mysql_query($sql) or die ($sql.mysql_error());
		$rows_head=mysql_num_rows($query_head);
		if ($rows_head!=0) $field_head=mysql_fetch_array($query_head);
		$periodo=strtoupper($nom_mes[$mes]).' '.$anio;
		//	----------------------------------------------------
		//	Imprimo el head
		estado_cuenta($pdf, $field_head['Banco'], $field_head['Cuenta'], $field_head['numero_cuenta'], $field_head['uso_cuenta'], $periodo);
		if ($field_head['fecha_apertura']<=$fapertura){
			//	Si la fecha de apertura es menor que el mes seleccionado entonces imprimo el reporte...
			//	----------------------------------------------------
			//	Imprimo el saldo disponible a la fecha
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 6);
			$pdf->SetAligns(array('C', 'C', 'L','L', 'R', 'R', 'R', 'R'));
			$pdf->SetWidths(array(14, 7 , 20, 55, 25, 25, 25, 25));
			
			
			// OBTENGO EL SALDO DEL BANCO A LA FECHA DESDE
			if ($field_head['fecha_apertura'] == $desde){
				$sum_saldo = $field_head["monto_apertura"];
			}else{
				// OBTENGO LA SUMA DE LOS INGRESOS
				$sum_saldo = $field_head["monto_apertura"];
				$sql_suma="SELECT
						SUM(i.monto) AS Monto
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='ingreso'
						AND i.fecha>='".$field_head['fecha_apertura']."' 
						AND i.fecha<'".$desde."'
						AND i.estado <> 'anulado'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo += $field_suma["Monto"];
				
				// RESTO LOS EGRESOS
				$sql_suma="SELECT
						SUM(i.monto) AS Monto
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='egreso'
						AND i.fecha>='".$field_head['fecha_apertura']."' 
						AND i.fecha<'".$desde."'
						AND i.estado <> 'anulado'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo -= $field_suma["Monto"];
				
				//OBTENGO LA SUMA DE LOS CHEQUES EN TRANSITO Y CONCILIADOS
				
				$sql_suma="SELECT
						SUM(p.monto_cheque) AS Monto
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND p.estado='conciliado'
						AND p.fecha_conciliado>='".$field_head['fecha_apertura']."' 
						AND p.fecha_conciliado<'".$desde."'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo -= $field_suma["Monto"];
				
			}
			
			$total_apertura = $sum_saldo;
			$monto_apertura = number_format($sum_saldo, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', '','SALDO DISPONIBLE AL '.$alafecha, '', '', '', $monto_apertura));
			$pdf->Ln(2);
			$total_saldo = $total_apertura;
			//	----------------------------------------------------
			//	Imprimo los ingresos
			$sql="(SELECT
						i.monto AS Monto,
						t.siglas,
						i.numero_documento as Documento,
						i.concepto As Denominacion,
						i.fecha AS Fecha
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='ingreso'
						AND i.fecha>='$desde' 
						AND i.fecha<='$hasta'
						AND i.estado <> 'anulado')
							
				UNION (
					SELECT
						p.monto_cheque AS Monto,
						t.siglas,
						p.numero_cheque as Documento,
						CONCAT(p.beneficiario,' (Anulado)') As Denominacion,
						p.fecha_cheque AS Fecha
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND p.estado='anulado'
						AND p.fecha_anulacion>='$desde' 
						AND p.fecha_anulacion<='$hasta')
						
				ORDER BY Fecha";
			$query_acredita=mysql_query($sql) or die ("AQUI1".$sql.mysql_error());
			while ($field_acredita=mysql_fetch_array($query_acredita)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field_acredita['Fecha']); $fecha=$d."/".$m."/".$a;
				$monto=number_format($field_acredita['Monto'], 2, ',', '.'); $sum_ingresos+=$field_acredita['Monto'];
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 6);
				$denominacion=substr($field_acredita['Denominacion'], 0, 100);
				$total_saldo += $field_acredita['Monto'];
				$saldo = number_format($total_saldo, 2, ',', '.');
				$pdf->Row(array($fecha, $field_acredita['siglas'], $field_acredita['Documento'], utf8_decode($denominacion), '', '', $monto, $saldo));
				//$pdf->Row(array(utf8_decode($denominacion), $fecha, $monto));
				$pdf->Ln(2);
				$y=$pdf->GetY(); if ($y>250) { estado_cuenta($pdf, $field_head['Banco'], $field_head['Cuenta'], $field_head['numero_cuenta'], $field_head['uso_cuenta'], $periodo); }
			}
			//	----------------------------------------------------
			//	Imprimo total acredita
			//$sum_ingresos += $total_apertura; 
			$total_acredita=number_format($sum_ingresos, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('','','','Total Acredita', '', '', $total_acredita));
			$pdf->Ln(4);
			$pdf->SetFont('Arial', '', 6);
			//	----------------------------------------------------
			//	Imprimo los egresos
			$sql="(SELECT
						p.monto_cheque AS Monto,
						t.siglas,
						p.numero_cheque as Documento,
						t.denominacion, 
						p.beneficiario As Denominacion,
						p.fecha_conciliado AS Fecha
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND p.estado='conciliado'
						AND p.fecha_conciliado>='$desde' AND p.fecha_conciliado<='$hasta')
							
				UNION (
					SELECT
						i.monto AS Monto,
						t.siglas,
						i.numero_documento as Documento,
						t.denominacion, 
						i.concepto As Denominacion,
						i.fecha AS Fecha
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='egreso'
						AND i.fecha>='$desde' AND i.fecha<='$hasta'
						AND i.estado <> 'anulado'
					)
						
				ORDER BY Fecha";
			$query_debita=mysql_query($sql) or die ("AQUI2".$sql.mysql_error());
			while ($field_debita=mysql_fetch_array($query_debita)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field_debita['Fecha']); $fecha=$d."/".$m."/".$a;
				$monto=number_format($field_debita['Monto'], 2, ',', '.'); $sum_egresos+=$field_debita['Monto'];
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 6);
				$denominacion=substr($field_debita['Denominacion'], 0, 55);
				$total_saldo -= $field_debita['Monto'];
				$saldo = number_format($total_saldo, 2, ',', '.');
				$pdf->Row(array($fecha, $field_debita['siglas'], $field_debita['Documento'], utf8_decode($denominacion), '', $monto, '', $saldo));
				$pdf->Ln(2);
				$y=$pdf->GetY(); if ($y>250) { estado_cuenta($pdf, $field_head['Banco'], $field_head['Cuenta'], $field_head['numero_cuenta'], $field_head['uso_cuenta'], $periodo); }
			}
			//	----------------------------------------------------
			//	Imprimo total debita
			$total_debita=number_format($sum_egresos, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('','','','Total Debita', '', $total_debita, '', ''));
			$pdf->Ln(4);
			$pdf->SetFont('Arial', '',6);
			//	----------------------------------------------------
			list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fhasta=$d."/".$m."/".$a;
			/*	Imprimo saldo segun libro
			$saldo=number_format($saldo_libro, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('SALDO SEGUN LIBRO AL '.$fhasta, '', $saldo));
			$pdf->Ln(2); */
			//	----------------------------------------------------
			//	Imprimo cheques en transito
			$sql="SELECT
						p.monto_cheque AS Monto,
						t.siglas,
						p.numero_cheque as Documento,
						t.denominacion, 
						CONCAT(p.beneficiario, ' (T)') As Denominacion,
						p.fecha_cheque AS Fecha
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND ((p.estado='transito' AND p.fecha_cheque<='$hasta')
							or (p.estado='conciliado' AND p.fecha_cheque<='$hasta' AND p.fecha_conciliado>'$hasta')
							or (p.estado='anulado' AND p.fecha_cheque<='$hasta' AND p.fecha_anulacion>'$hasta'))
						
				ORDER BY Fecha";
			$query_transito=mysql_query($sql) or die ($sql.mysql_error());
			while ($field_transito=mysql_fetch_array($query_transito)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field_transito['Fecha']); $fecha=$d."/".$m."/".$a;
				$monto=number_format($field_transito['Monto'], 2, ',', '.'); $sum_transito+=$field_transito['Monto'];
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 6);
				$denominacion=substr($field_transito['Denominacion'], 0, 65);
				
				//$total_saldo += $field_debita['Monto'];
				$saldo = number_format($total_saldo, 2, ',', '.');
				$pdf->Row(array($fecha, $field_transito['siglas'], $field_transito['Documento'], utf8_decode($denominacion), $monto, '', '', $saldo));
				
				$pdf->Ln(2);
				$y=$pdf->GetY(); if ($y>250) { estado_cuenta($pdf, $field_head['Banco'], $field_head['Cuenta'], $field_head['numero_cuenta'], $field_head['uso_cuenta'], $periodo); }
			}
			//	----------------------------------------------------
			//	Imprimo total cheques en transito
			$total_transito=number_format($sum_transito, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', '', utf8_decode('Total Cheques en Tránsito'), $total_transito));
			$pdf->Ln(4);
			$pdf->SetFont('Arial', '',6);
			//	----------------------------------------------------
			//	Imprimo saldo segun banco
			//$saldo_banco = $total_apertura + $sum_ingresos - $sum_egresos + $saldo_libro + $sum_transito;
			$saldo=number_format($total_saldo, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('','','','SALDO SEGUN BANCO AL '.$fhasta, '', '', '', $saldo));
			$pdf->Ln(2);
			//	----------------------------------------------------
			$pdf->Ln(2);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y=$pdf->GetY();
			$x=$pdf->GetX();
			$pdf->Rect($x, $y, 195, 0.1);
			$pdf->Ln(5);
			//	----------------------------------------------------
			$y=$pdf->GetY();
			$y+=20;
			if ($y>270) {
				//	Imprimo el head
				estado_cuenta($pdf, $field_head['Banco'], $field_head['Cuenta'], $field_head['numero_cuenta'], $field_head['uso_cuenta'], $periodo);
				$y=$pdf->GetY();
				$y+=20;
			}
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$pdf->SetY($y);
			$pdf->Rect(70, $y, 75, 0.1);
			$pdf->SetFont('Arial', 'B', 8);
			//	Obtengo la firma
			$sql="SELECT
						primero_tesoreria,
						cargo_primero_tesoreria
					FROM
						configuracion_tesoreria";
			$query_firma=mysql_query($sql) or die ($sql.mysql_error());
			$rows_firma=mysql_num_rows($query_firma);
			if ($rows_firma!=0) $field_firma=mysql_fetch_array($query_firma);
			//	----------------------------------------------------
			$pdf->Cell(195, 5, $field_firma['primero_tesoreria'], 0, 1, 'C');
			$pdf->Cell(195, 5, $field_firma['cargo_primero_tesoreria'], 0, 1, 'C');
		} else {
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Cell(195, 20, 'AL '.$alafecha.' NO SE HABIA REGISTRADO MONTO DE APERTURA', 0, 1, 'C');
			$pdf->Ln(2);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y=$pdf->GetY();
			$x=$pdf->GetX();
			$pdf->Rect($x, $y, 195, 0.1);
			$pdf->Ln(5);
		}
		break;
	
	//	Libro Diario Banco...
	case "libro_diario_banco":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(10, 1, 1);
		$pdf->SetAutoPageBreak(1, 1);
		//	----------------------------------------------------
		$a=(int) $anio; 
		$m=(int) $mes; $mm = --$m; 
		if ($mm==0) { //--$a; 
			$alafecha="01/01/$a"; 
			$fapertura="$a-01-01"; 
		} 
		elseif ($m==2) { if ($a%4==0) $d=29; else $d=28; $alafecha="$d/02/$a"; $fapertura="$a-02-$d"; }
		else {
			if ($m<10) $m="0$m";
			$d=$dias_mes[$m];
			$alafecha="$d/$m/$a";
			$fapertura="$a-$m-$d";
		}
		//	----------------------------------------------------
		list($d, $m, $a)=SPLIT( '[/.-]', $alafecha); 
		$alafecha_hasta = "$a-$m-$d";
		$alafecha_desde = "$a-$m-01";
		$desde_inicial = "$a-01-01";
		//	----------------------------------------------------
		if ($anio%4==0) $dias_mes['02']=29; else $dias_mes['02']=28;
		$d=$dias_mes[$mes];
		$desde="$anio-$mes-01"; $hasta="$anio-$mes-$d";
		//	----------------------------------------------------
		
		//	----------------------------------------------------
		//	Obtengo la cabecera
		$sql="SELECT
					c.numero_cuenta,
					c.uso_cuenta,
					c.monto_apertura,
					c.monto_libro,
					c.fecha_apertura,
					b.denominacion AS Banco,
					t.denominacion AS Cuenta
				FROM
					cuentas_bancarias c
					INNER JOIN banco b ON (c.idbanco=b.idbanco)
					INNER JOIN tipo_cuenta_bancaria t ON (c.idtipo_cuenta=t.idtipo_cuenta)
				WHERE
					c.idcuentas_bancarias='".$cuenta."'";
		$query_head=mysql_query($sql) or die ($sql.mysql_error());
		$rows_head=mysql_num_rows($query_head);
		if ($rows_head!=0) $field_head=mysql_fetch_array($query_head);
		$periodo=strtoupper($nom_mes[$mes]).' '.$anio;
		//	----------------------------------------------------
		//	Imprimo el head
		libro_diario_banco($pdf, $field_head['Banco'], $field_head['Cuenta'], $field_head['numero_cuenta'], $field_head['uso_cuenta'], $periodo);
		$sum_saldo=0;$sum_saldo_libro=0;
		if ($field_head['fecha_apertura']<=$fapertura){
			//	Si la fecha de apertura es menor que el mes seleccionado entonces imprimo el reporte...
			//	----------------------------------------------------
			
			// OBTENGO EL SALDO DEL BANCO A LA FECHA DESDE
			if ($field_head['fecha_apertura'] == $desde){
				$sum_saldo = $field_head["monto_apertura"];
				$sum_saldo_libro = $field_head["monto_libro"];
			}else{
				// OBTENGO LA SUMA DE LOS INGRESOS
				$sum_saldo = $field_head["monto_apertura"];
				$sum_saldo_libro = $field_head["monto_libro"];
				$sql_suma="SELECT
						SUM(i.monto) AS Monto
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='ingreso'
						AND i.fecha>='".$field_head['fecha_apertura']."' 
						AND i.fecha<'".$desde."'
						AND i.estado <> 'anulado'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo += $field_suma["Monto"];
				$sum_saldo_libro += $field_suma["Monto"];
				// RESTO LOS EGRESOS
				$sql_suma="SELECT
						SUM(i.monto) AS Monto
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='egreso'
						AND i.fecha>='".$field_head['fecha_apertura']."' 
						AND i.fecha<'".$desde."'
						AND i.estado <> 'anulado'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo -= $field_suma["Monto"];
				$sum_saldo_libro -= $field_suma["Monto"];
				//OBTENGO LA SUMA DE LOS CHEQUES EN TRANSITO Y CONCILIADOS
				
				$sql_suma="SELECT
						SUM(p.monto_cheque) AS Monto
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND (p.estado='conciliado' OR p.estado='transito')
						AND p.fecha_cheque>='".$field_head['fecha_apertura']."' 
						AND p.fecha_cheque<'".$desde."'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo -= $field_suma["Monto"];
				$sum_saldo_libro -= $field_suma["Monto"];
			}
			
			
			// OBTENGO LOS MOVIMIENTOS EN EL PERIODO
			$sql="(SELECT
						i.monto AS Monto,
						i.numero_documento as Documento,
						i.concepto As Denominacion,
						i.fecha AS Fecha,
						t.siglas,
						'Haber' AS Tipo,
						i.estado,
						i.fecha AS fecha_anulacion
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='ingreso'
						AND i.fecha>='$desde' AND i.fecha<='$hasta'
						AND i.estado <> 'anulado')
							
				UNION (
					SELECT
						p.monto_cheque AS Monto,
						p.numero_cheque as Documento,
						CONCAT(p.beneficiario, ' (Anulado)') As Denominacion,
						p.fecha_cheque AS Fecha,
						t.siglas,
						'Haber' AS Tipo,
						p.estado,
						p.fecha_anulacion AS fecha_anulacion
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND p.estado='anulado'
						AND p.fecha_anulacion>='$desde' AND p.fecha_anulacion<='$hasta')
						
				UNION
				
				(SELECT
					p.monto_cheque AS Monto,
					p.numero_cheque as Documento,
					CONCAT(p.beneficiario, ' (Conciliado)') As Denominacion,
					p.fecha_cheque AS Fecha,
					t.siglas,
					'Debe' AS Tipo,
					p.estado,
					p.fecha_anulacion AS fecha_anulacion
				FROM
					pagos_financieros p
					INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
				WHERE
					p.idcuenta_bancaria='".$cuenta."'
					AND p.estado='conciliado'
					AND p.fecha_cheque>='$desde' AND p.fecha_cheque<='$hasta'
				)
							
				UNION 
				
				(SELECT
					i.monto AS Monto,
					i.numero_documento as Documento,
					i.concepto As Denominacion,
					i.fecha AS Fecha,
					t.siglas,
					'Debe' AS Tipo,
					i.estado,
					i.fecha AS fecha_anulacion
				FROM
					ingresos_egresos_financieros i
					INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
				WHERE
					i.idcuentas_bancarias='".$cuenta."'
					AND i.idbanco='".$banco."'
					AND i.tipo='egreso'
					AND i.fecha>='$desde' AND i.fecha<='$hasta'
					AND i.estado <> 'anulado'
					AND i.anio_documento = '".$anio_fiscal."'
				)
				
				UNION
				
				(SELECT
						p.monto_cheque AS Monto,
						p.numero_cheque as Documento,
						CONCAT(p.beneficiario, ' (Transito)') As Denominacion,
						p.fecha_cheque AS Fecha,
						t.siglas,
						'Debe' AS Tipo,
						p.estado,
						p.fecha_anulacion AS fecha_anulacion
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND (p.estado='transito'
						AND p.fecha_cheque>='$desde' AND p.fecha_cheque<='$hasta')
						OR (p.estado='anulado'
						AND p.fecha_cheque>='$desde' AND p.fecha_cheque<='$hasta' AND p.fecha_anulacion>'$hasta')
				)
				
				ORDER BY Fecha";
			$query_detalle = mysql_query($sql) or die ($sql.mysql_error());
			
			$saldo = number_format($sum_saldo, 2, ',', '.');
			$saldo_libro = number_format($sum_saldo_libro, 2, ',', '.');
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array("",'', '', "VIENEN Bs.", '', '', $saldo_libro));
			$pdf->Ln(2);
			$sum_saldo = $sum_saldo_libro;
			while ($field_detalle = mysql_fetch_array($query_detalle)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field_detalle['Fecha']); $fecha=$d."/".$m."/".$a;
				//if ($field_detalle['estado'] == "conciliado") {
				//	$sum_transito += $field_detalle['Monto'];
				//}
				if ($field_detalle['Tipo'] == "Debe") {
					$debe = number_format($field_detalle['Monto'], 2, ',', '.'); 	
					$sum_debe += $field_detalle['Monto'];
					$sum_saldo -= $field_detalle['Monto'];
					$haber = "-";
				} else {
					$haber = number_format($field_detalle['Monto'], 2, ',', '.'); 	
					$sum_haber += $field_detalle['Monto'];
					$sum_saldo += $field_detalle['Monto'];
					$debe = "-";
				}
				$saldo = number_format($sum_saldo, 2, ',', '.');
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 6);
				$denominacion=substr($field_detalle['Denominacion'], 0, 150);
				$pdf->Row(array($fecha, $field_detalle['siglas'], $field_detalle['Documento'],utf8_decode($denominacion), $debe, $haber, $saldo));
				$pdf->Ln(2);
				$y=$pdf->GetY(); if ($y>250) { libro_diario_banco($pdf, $field_head['Banco'], $field_head['Cuenta'], $field_head['numero_cuenta'], $field_head['uso_cuenta'], $periodo); }
			}
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array("", "", '', "VAN Bs.", "", "", $saldo));
			//$pdf->Ln(2);
			
			$pdf->Ln(2);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y=$pdf->GetY();
			$x=$pdf->GetX();
			$pdf->Rect($x, $y, 195, 0.1);
			$pdf->Ln(2);
			
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			
			$sum_debe = number_format($sum_debe, 2, ',', '.'); 
			$sum_haber = number_format($sum_haber, 2, ',', '.'); 
			$pdf->Row(array('', '', '', 'TOTALES Bs.', $sum_debe, $sum_haber, ""));
						
		} else {
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Cell(195, 20, 'AL '.$alafecha.' NO SE HABIA REGISTRADO MONTO DE APERTURA', 0, 1, 'C');
			$pdf->Ln(2);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y=$pdf->GetY();
			$x=$pdf->GetX();
			$pdf->Rect($x, $y, 195, 0.1);
			$pdf->Ln(5);
		}
		break;

//	(Modulo) Remision de Documentos...
	case "remitirdoc":
		$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		$pag=1;
		$suma_total = 0;
		//	OBTENGO EL HEAD DEL DOCUMENTO
		$sql="SELECT numero_documento, 
					 fecha_envio, 
					 iddependencia_destino, 
					 iddependencia_origen, 
					 asunto, 
					 justificacion, 
					 numero_documentos_enviados,
					 idcuenta_bancaria,
					 tipo_comprobante
				FROM 
					 autorizar_generar_comprobante 
				WHERE 
					 idautorizar_generar_comprobante='".$_GET['id_remision']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) { $field=mysql_fetch_array($query); $ndoc=$field[0]; $fdoc=$field[1]; } 
		remitirdoc($pdf, $ndoc, $fdoc, $field[2], $field[3], $field[4], $field[5], $field[6], $field[7], $field[8], $pag);
		$sql_limpia_temporal = mysql_query("delete from total_temporal_retenido");
		$query=mysql_query("SELECT * FROM relacion_documentos_generar_comprobante 
										WHERE idautorizar_generar_comprobante='".$_GET['id_remision']."'") or die ($sql.mysql_error());	
		while($bus_consulta=mysql_fetch_array($query)) {
			
			$sql_documentos = mysql_query("select * from orden_pago where idorden_pago = ".$bus_consulta["idorden_pago"]."");
			$bus_documentos = mysql_fetch_array($sql_documentos);

				
			$numero_documento = $bus_documentos["numero_orden"];
			$fecha = $bus_documentos["fecha_elaboracion"];
			$sql_beneficiario = mysql_query("select * from beneficiarios 
					where idbeneficiarios = '".$bus_documentos["idbeneficiarios"]."'");
			$bus_beneficiario = mysql_fetch_array($sql_beneficiario);	
			$beneficiario = substr($bus_beneficiario["nombre"],0,70);
			$monto = $bus_documentos["total"];
			
			$sql_suma_total_retenido = mysql_query("select SUM(relacion_retenciones.monto_retenido) as monto_retenido from relacion_orden_pago_retencion,
																			retenciones,
																			
																			relacion_retenciones
																		 where relacion_orden_pago_retencion.idorden_pago = '".$bus_consulta["idorden_pago"]."'
																		 and retenciones.idretenciones = relacion_orden_pago_retencion.idretencion
																		 and relacion_retenciones.idretenciones = retenciones.idretenciones
																		 ");
			$bus_suma_total_retenido = mysql_fetch_array($sql_suma_total_retenido);	
			$monto_retenido = number_format($bus_suma_total_retenido["monto_retenido"],2,",",".");
			
			$suma_total = $suma_total + $bus_suma_total_retenido["monto_retenido"];	
					
			list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $fecha=$d."/".$m."/".$a;
			$monto=number_format($monto, 2, ',', '.');
			

			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetAligns(array('L', 'C', 'L', 'R', 'R'));
			$pdf->SetWidths(array(30, 18, 107, 25,  25));
			
			$pdf->Row(array($numero_documento, $fecha, utf8_decode($beneficiario), $monto, $monto_retenido));
			$sql_limpia_temporal = mysql_query("delete from temporal_retenido");
			$sql_retenido = mysql_query("select * from relacion_orden_pago_retencion
													where relacion_orden_pago_retencion.idorden_pago = '".$bus_consulta["idorden_pago"]."'");
			
			while($bus_retenciones = mysql_fetch_array($sql_retenido)){
				$sql_retenido = mysql_query("select * from relacion_retenciones
														where relacion_retenciones.idretenciones = '".$bus_retenciones["idretencion"]."'
														and generar_comprobante = 'si'");
														
				while ($bus_retenido = mysql_fetch_array($sql_retenido)){
					
					$sql_busca_retenido = mysql_query("select * from temporal_retenido where idtipo = '".$bus_retenido["idtipo_retencion"]."'");
					if(mysql_num_rows($sql_busca_retenido)>0){
						$sql_actualiza_temporal = mysql_query("update temporal_retenido set
																					monto = monto + '".$bus_retenido["monto_retenido"]."'
																					where idtipo = '".$bus_retenido["idtipo_retencion"]."'");
					}else{
						$sql_temporal_impuesto= mysql_query("insert into temporal_retenido(
																			idtipo,
																			monto,
																			porcentaje
																			)values(
																			'".$bus_retenido["idtipo_retencion"]."',
																			'".$bus_retenido["monto_retenido"]."',
																			'".$bus_retenido["porcentaje_aplicado"]."'
																			)
																			");
					}
				}
			}
			$sql_lista_retenido = mysql_query("select * from temporal_retenido");		
			while ($lista_retenido = mysql_fetch_array($sql_lista_retenido)){
				$porcentaje = number_format($lista_retenido["porcentaje"],2,",",".");
				$monto_retenido_individual = number_format($lista_retenido["monto"],2,",",".");
				
				$sql_tipo = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$lista_retenido["idtipo"]."'");
				$bus_tipo = mysql_fetch_array($sql_tipo);
				
				$retencion = $bus_tipo["descripcion"];
				
				$pdf->SetWidths(array(30, 18, 30, 30,  30));
				$pdf->Row(array('','',$retencion, $porcentaje,  $monto_retenido_individual));
			
				$sql_busca_retenido = mysql_query("select * from total_temporal_retenido where idtipo = '".$lista_retenido["idtipo"]."'");
				if(mysql_num_rows($sql_busca_retenido)>0){
					$sql_actualiza_temporal = mysql_query("update total_temporal_retenido set
																					monto = monto + '".$lista_retenido["monto"]."'
																					where idtipo = '".$lista_retenido["idtipo"]."'");
				}else{
					if ($bus_tipo["asociado"]== 0){
						$sql_temporal_impuesto= mysql_query("insert into total_temporal_retenido(
																			idtipo,
																			monto
																			)values(
																			'".$lista_retenido["idtipo"]."',
																			'".$lista_retenido["monto"]."'
																			)
																			");
					}else{
						$sql_busca_retenido = mysql_query("select * from total_temporal_retenido where idtipo = '".$bus_tipo["asociado"]."'");
						if(mysql_num_rows($sql_busca_retenido)>0){
							$sql_actualiza_temporal = mysql_query("update total_temporal_retenido set
																							monto = monto + '".$lista_retenido["monto"]."'
																							where idtipo = '".$bus_tipo["asociado"]."'");
						}else{
							$sql_temporal_impuesto= mysql_query("insert into total_temporal_retenido(
																			idtipo,
																			monto
																			)values(
																			'".$bus_tipo["asociado"]."',
																			'".$lista_retenido["monto"]."'
																			)
																			");
						}
					}
				}
			}
			
			/*
				$sql_suma_total_retenido = mysql_query("select SUM(relacion_retenciones.monto_retenido) as monto_retenido from relacion_orden_pago_retencion,
																			retenciones,
																			
																			relacion_retenciones
																		 where relacion_orden_pago_retencion.idorden_pago = '".$bus_consulta["idorden_pago"]."'
																		 and retenciones.idretenciones = relacion_orden_pago_retencion.idretencion
																		 and relacion_retenciones.idretenciones = retenciones.idretenciones
																		 ");
				$bus_suma_total_retenido = mysql_fetch_array($sql_suma_total_retenido);	
				$monto_retenido = number_format($bus_suma_total_retenido["monto_retenido"],2,",",".");
				
				$suma_total = $suma_total + $bus_suma_total_retenido["monto_retenido"];*/
				
				
			$linea=$pdf->GetY(); 
			if ($linea>235) { 
				$pag++; 
				remitirdoc($pdf, $ndoc, $fdoc, $field[2], $field[3], $field[4], $field[5], $field[6], $field[7], $field[8], $pag); 
				$pdf->SetY(42); 
			}
		}
		$suma=number_format($suma_total, 2, ',', '.');
		$pdf->Ln(2);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$y=$pdf->GetY();
		$pdf->Rect(5, $y, 205, 0.1);
		$pdf->Ln(1);
		$pdf->Cell(205, 5, $suma, 0, 1, 'R', 1);
		
		$sql_lista_retenido = mysql_query("select * from total_temporal_retenido");		
		while ($lista_retenido = mysql_fetch_array($sql_lista_retenido)){
			
			$monto_retenido_individual = number_format($lista_retenido["monto"],2,",",".");
			
			$sql_tipo = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$lista_retenido["idtipo"]."'");
			$bus_tipo = mysql_fetch_array($sql_tipo);
			
			$retencion = $bus_tipo["descripcion"];
			$pdf->SetAligns(array('L', 'R'));
			$pdf->SetWidths(array(30, 18));
			$pdf->Row(array($retencion, $monto_retenido_individual));
		}
		$sql_configuracion = mysql_query("select * from configuracion_tesoreria");
		$bus_configuracion = mysql_fetch_array($sql_configuracion);
		
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$punto = $pdf->GetY();
		if ($punto >= 251){
			$pdf->SetY($punto+15);
		}else{
			$pdf->SetY($punto+25);
		}
		$pdf->SetFont('Arial', 'B', 9);
		$y=$pdf->GetY();
		$pdf->line(55, $y, 155, $y);
		$pdf->Cell(205, 5, utf8_decode($bus_configuracion["primero_tesoreria"]), 0, 1, 'C', 1);
		$pdf->Cell(205, 5, utf8_decode($bus_configuracion["cargo_primero_tesoreria"]), 0, 1, 'C', 1);
		break;


//	Imprimir Cheque individual trabajador...
	case "emitir_pagos_cheque2":
		$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->SetTopMargin(1);
		$pdf->Open();
		$pdf->AddPage();
		$sql="SELECT 	pagos_financieros.idorden_pago, 
						pagos_financieros.beneficiario, 
						pagos_financieros.monto_cheque, 
						beneficiarios.nombre,
						pagos_financieros.beneficiario as nombre_beneficiario,
						pagos_financieros.fecha_cheque,
						pagos_financieros.idcuenta_bancaria
					FROM pagos_financieros, beneficiarios, orden_pago 
					WHERE pagos_financieros.idpagos_financieros='".$id_emision_pago."'
						and orden_pago.idorden_pago = pagos_financieros.idorden_pago
						and beneficiarios.idbeneficiarios = orden_pago.idbeneficiarios";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_cheque']); $fecha=$d."/".$m."/".$a;
		$dia=$d;
		$mes=$m;
		$annio=$a;
		if($mes == 1){$mes = "Enero";}
		if($mes == 2){$mes = "Febrero";}
		if($mes == 3){$mes = "Marzo";}
		if($mes == 4){$mes = "Abril";}
		if($mes == 5){$mes = "Mayo";}
		if($mes == 6){$mes = "Junio";}
		if($mes == 7){$mes = "Julio";}
		if($mes == 8){$mes = "Agosto";}
		if($mes == 9){$mes = "Septiembre";}
		if($mes == 10){$mes = "Octubre";}
		if($mes == 11){$mes = "Noviembre";}
		if($mes == 12){$mes = "Diciembre";}
		//	--------------------
		
		$sql_banco = mysql_query("select idbanco from cuentas_bancarias where idcuentas_bancarias = '".$field["idcuenta_bancaria"]."'")or die(mysql_error());
		$bus_banco = mysql_fetch_array($sql_banco);
		
		$sql_configuracion_cheque = mysql_query("select * from configuracion_cheques where idbanco = '".$bus_banco["idbanco"]."'")or die(mysql_error());
		$bus_configuracion_cheque = mysql_fetch_array($sql_configuracion_cheque)or die(mysql_error());
		
		$monto_letras=$pdf->ValorEnLetras($field["monto_cheque"], "");
		$monto=number_format($field['monto_cheque'], 2, ',', '.');
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->SetXY($bus_configuracion_cheque["izquierda_monto_numeros"], $bus_configuracion_cheque["alto_monto_numeros"]); $pdf->Cell(20, 10, $monto);
		$pdf->SetXY($bus_configuracion_cheque["izquierda_beneficiario"], $bus_configuracion_cheque["alto_beneficiario"]); $pdf->Cell(150, 10, utf8_decode($field['nombre_beneficiario']));
		$pdf->SetXY($bus_configuracion_cheque["izquierda_monto_letras"], $bus_configuracion_cheque["alto_monto_letras"]); $pdf->MultiCell(150, 6, '                    '.$monto_letras);
		
		$sql_consulta = mysql_query("select * from configuracion");
		$bus_consulta = mysql_fetch_array($sql_consulta);		
		$pdf->SetXY($bus_configuracion_cheque["izquierda_fecha"], $bus_configuracion_cheque["alto_fecha"]); $pdf->Cell(130, 9, $bus_consulta["ciudad"]." ".$dia.' de '.$mes);
		$pdf->SetXY($bus_configuracion_cheque["izquierda_ano"], $bus_configuracion_cheque["alto_ano"]); $pdf->Cell(130, 9, $annio);
		break;



	
}
//	----------------------------------------
//	FIN CUERPO DE LOS REPORTES
//	----------------------------------------

$pdf->Output();
?>
