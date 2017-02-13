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
require('head.php');
require('../firmas.php');
require('../../fpdf.php');
require('../../mc_table.php');
require('../../mc_table2.php');
require('../../mc_table3.php');
require('../../mc_table4.php');
require('../../mc_table5.php');
require('../../mc_table6.php');
require('../../mc_table7.php');
require('../../mc_table8.php');
require('../../mc_table10cont.php');
require('../../../conf/conex.php');
//	----------------------------------------
Conectarse();
$ahora=date("d-m-Y H:i:s");
//	----------------------------------------
$dias_mes[1]=31;
if ($anio_fiscal % 4 == 0) $dias_mes[2]=29; else $dias_mes[2]=28;
$dias_mes[3]=31;
$dias_mes[4]=30;
$dias_mes[5]=31;
$dias_mes[6]=30;
$dias_mes[7]=31;
$dias_mes[8]=31;
$dias_mes[9]=30;
$dias_mes[10]=31;
$dias_mes[11]=30;
$dias_mes[12]=31;
//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Orden de Pago...
	case "orden_pago_contabilidad":
		$pdf=new PDF_MC_Table('L', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(8, 10, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//	--------------------
		$dmes['01']=31; $dmes['03']=31; $dmes['04']=30; $dmes['05']=31; $dmes['06']=30; $dmes['07']=31; $dmes['08']=31; $dmes['09']=30; $dmes['10']=31; $dmes['11']=30; $dmes['12']=31;
		if ($anio%4==0) $dmes['02']=29; else $dmes['02']=28;
		$nmes['01']="ENERO";
		$nmes['02']="FEBRERO";
		$nmes['03']="MARZO";
		$nmes['04']="ABRIL";
		$nmes['05']="MAYO";
		$nmes['06']="JUNIO";
		$nmes['07']="JULIO";
		$nmes['08']="AGOSTO";
		$nmes['09']="SEPTIEMBRE";
		$nmes['10']="OCTUBRE";
		$nmes['11']="NOVIEMBRE";
		$nmes['12']="DICIEMBRE";
		$desde=$anio.'-'.$mes.'-01';
		$hasta=$anio.'-'.$mes.'-'.$dmes[$mes];
		//	--------------------
		if ($estado=="emitidas") $filtro="AND (op.estado='procesado' OR op.estado='pagada') AND op.idfuente_financiamiento='".$fuente."' AND op.fecha_orden>='$desde' AND op.fecha_orden<='$hasta'"; 
		else $filtro="AND op.estado='".$estado."' AND op.idfuente_financiamiento='".$fuente."' AND op.fecha_orden>='$desde' AND op.fecha_orden<='$hasta'"; $dbeneficiario=0; $dcategoria=0; $dperiodo=1; $dtipo=0; $darticulo=0; $head=1;
		if ($estado=="emitidas") $estado="EMITIDAS";
		elseif ($estado=="procesado") $estado="PROCESADAS";
		elseif ($estado=="pagada") $estado="PAGADAS";
		elseif ($estado=="anulado") $estado="ANULADAS";
		////////////
		if ($_GET['idbeneficiario']!="") { $filtro.=" AND (b.idbeneficiarios='".$_GET['idbeneficiario']."') "; $dbeneficiario=1; $head=2; }
		if ($_GET['idcategoria']!=0) { 
			$filtro.=" AND (cp.idcategoria_programatica='".$_GET['idcategoria']."') "; $dcategoria=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['idtipo']!="0") { 
			$filtro.=" AND (td.idtipos_documentos='".$_GET['idtipo']."') "; $dtipo=1;
			if ($dbeneficiario==1) $head=4; else $head=3;
		}
		if ($filtro=="") $head=1;
		////////////
		$sql="SELECT 
					op.idorden_pago, 
					op.codigo_referencia, 
					op.numero_orden, 
					op.fecha_orden, 
					op.idcategoria_programatica, 
					cp.codigo, 
					ue.denominacion, 
					op.estado, 
					b.nombre, 
					td.descripcion, 
					op.exento, 
					op.sub_total, 
					op.impuesto, 
					op.total, 
					op.total_retenido, 
					ropr.idretencion, 
					ff.denominacion AS Fuente, 
					td.excluir_contabilidad,
					(SELECT SUM(relacion_retenciones.monto_retenido) 
						FROM relacion_retenciones 
							WHERE relacion_retenciones.idretenciones=ropr.idretencion 
								AND relacion_retenciones.idtipo_retencion=
									(SELECT tipo_retencion.idtipo_retencion 
										FROM tipo_retencion 
											WHERE tipo_retencion.nombre_comprobante='ISLR' 
												AND tipo_retencion.idtipo_retencion=relacion_retenciones.idtipo_retencion)) AS islr 
					FROM orden_pago op 
					INNER JOIN beneficiarios b ON (op.idbeneficiarios=b.idbeneficiarios) 
					INNER JOIN tipos_documentos td ON (op.tipo=td.idtipos_documentos) 
					INNER JOIN fuente_financiamiento ff ON (op.idfuente_financiamiento=ff.idfuente_financiamiento) 
					LEFT JOIN categoria_programatica cp ON (op.idcategoria_programatica=cp.idcategoria_programatica) 
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora=ue.idunidad_ejecutora) 
					LEFT OUTER JOIN relacion_orden_pago_retencion ropr ON (op.idorden_pago=ropr.idorden_pago) 
					WHERE (td.modulo like '%-4-%') $filtro 
					AND td.excluir_contabilidad<>'si'
					GROUP BY (op.idorden_pago) 
					ORDER BY op.codigo_referencia";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//---------------------------------------------------
		if ($head==1) {
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query);
				$exento=number_format($field["exento"], 2, ',', '.'); $sum_exento+=$field['exento'];
				$sub_total=number_format($field["sub_total"], 2, ',', '.'); $sum_sub_total+=$field['sub_total'];
				$impuesto=number_format($field["impuesto"], 2, ',', '.'); $sum_impuesto+=$field['impuesto'];
				$total=number_format(($field["exento"]+$field["sub_total"]+$field["impuesto"]), 2, ',', '.'); 
					$sum_total+=($field["exento"]+$field["sub_total"]+$field["impuesto"]);
				$islr=number_format($field["islr"], 2, ',', '.'); $sum_islr+=$field['islr']; if ($islr=="0,00") $islr="";
				$total_retenido=number_format($field["total_retenido"], 2, ',', '.'); $sum_total_retenido+=$field['total_retenido'];
				$total_pago=number_format(($field["total"]-$field["total_retenido"]), 2, ',', '.'); 
					$sum_total_pago+=($field['total']-$field["total_retenido"]);
				
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($i==1) {
					orden_pago_contabilidad($pdf, $head, "", $categoria, $anio, $nmes[$mes], "", $field['Fuente'], $estado);
					//$pdf->Ln(1);
				}
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				
				$pdf->Row(array($fecha, $field['numero_orden'], utf8_decode($field['nombre']), utf8_decode($field['descripcion']), $exento, $sub_total, $impuesto, $total, $total_retenido, $total_pago));
				$y=$pdf->GetY(); 
				if ($y>190 || $i==$rows) {
					$van_exento=number_format($sum_exento, 2, ',', '.');
					$van_sub_total=number_format($sum_sub_total, 2, ',', '.');
					$van_impuesto=number_format($sum_impuesto, 2, ',', '.');
					$van_total_retenido=number_format($sum_total_retenido, 2, ',', '.');
					$van_islr=number_format($sum_islr, 2, ',', '.');
					$van_amort=number_format($sum_amort, 2, ',', '.');
					$van_total=number_format($sum_total, 2, ',', '.');
					$van_total_pago=number_format($sum_total_pago, 2, ',', '.');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); 
					$pdf->SetFont('Arial', 'B', 6);
					
					if ($i==$rows) $mensaje= 'TOTAL ............ >'; else $mensaje='VAN ............ >';
					$pdf->SetAligns(array('C', 'C', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'R'));
					$pdf->SetWidths(array(18, 20, 60, 50, 20, 20, 20, 20, 20, 20));
					$pdf->Row(array('','',$mensaje,'',$van_exento,$van_sub_total, $van_impuesto, $van_total,$van_total_retenido,$van_total_pago));
					/*$pdf->SetAligns(array('C', 'C', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'R'));
					
					$pdf->Cell(40, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, $van_exento, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_sub_total, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_impuesto, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_total, 1, 1, 'R', 1);
					$pdf->Cell(18, 5, $van_total_retenido, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_total_pago, 1, 1, 'R', 1);*/
					if ($i!=$rows) {
						orden_pago_contabilidad($pdf, $head, "", $categoria, $anio, $nmes[$mes], "", $field['Fuente'], $estado); 
						//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
						$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); 
						$pdf->SetFont('Arial', 'B', 6);
						
						$pdf->SetAligns(array('C', 'C', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'R'));
						$pdf->SetWidths(array(18, 20, 60, 50, 20, 20, 20, 20, 20, 20));
						$pdf->Row(array('','','VIENEN ............ >','',$van_exento,$van_sub_total, $van_impuesto, $van_total,$van_total_retenido,$van_total_pago));
						
						/*$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(78, 5, 'VIENEN ............ >', 1, 0, 'C', 1);
						$pdf->Cell(40, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(18, 5, $van_exento, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_sub_total, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_impuesto, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_total, 1, 1, 'R', 1);
						$pdf->Cell(18, 5, $van_total_retenido, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_total_pago, 1, 1, 'R', 1);*/
					}
				}
			}
		}
		//----------------------------------------------------
		elseif ($head==2) {
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query);
				$exento=number_format($field["exento"], 2, ',', '.'); $sum_exento+=$field['exento'];
				$sub_total=number_format($field["sub_total"], 2, ',', '.'); $sum_sub_total+=$field['sub_total'];
				$impuesto=number_format($field["impuesto"], 2, ',', '.'); $sum_impuesto+=$field['impuesto'];
				$total=number_format($field["total"], 2, ',', '.'); $sum_total+=$field['total'];
				$islr=number_format($field["islr"], 2, ',', '.'); $sum_islr+=$field['islr']; if ($islr=="0,00") $islr="";
				$total_retenido=number_format($field["total_retenido"], 2, ',', '.'); $sum_total_retenido+=$field['total_retenido'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($i==1) {
					orden_pago_contabilidad($pdf, $head, $beneficiario, $categoria, $anio, $nmes[$mes], "", $field['Fuente'], $estado);
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(40, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 1, 'C', 1);
				}
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(18, 18, 40, 18, 18, 18, 18, 18, 18, 18));
				$pdf->Row(array($fecha, $field['numero_orden'], utf8_decode($field['descripcion']), $exento, $sub_total, $impuesto, $total_retenido, $islr, $amort, $total));
				$y=$pdf->GetY(); 
				if ($y>190 || $i==$rows) {
					$van_exento=number_format($sum_exento, 2, ',', '.');
					$van_sub_total=number_format($sum_sub_total, 2, ',', '.');
					$van_impuesto=number_format($sum_impuesto, 2, ',', '.');
					$van_total_retenido=number_format($sum_total_retenido, 2, ',', '.');
					$van_islr=number_format($sum_islr, 2, ',', '.');
					$van_amort=number_format($sum_amort, 2, ',', '.');
					$van_total=number_format($sum_total, 2, ',', '.');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					if ($i==$rows) $pdf->Cell(40, 5, 'TOTAL ............ >', 1, 0, 'C', 1); else $pdf->Cell(40, 5, 'VAN ............ >', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, $van_exento, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_sub_total, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_impuesto, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_total_retenido, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_islr, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_amort, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_total, 1, 1, 'R', 1);
					if ($i!=$rows) {
						orden_pago_contabilidad($pdf, $head, $beneficiario, $categoria, $anio, $nmes[$mes], "", $field['Fuente'], $estado);
						//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
						$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
						$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(40, 5, 'VIENEN ............ >', 1, 0, 'C', 1);
						$pdf->Cell(18, 5, $van_exento, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_sub_total, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_impuesto, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_total_retenido, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_islr, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_amort, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_total, 1, 1, 'R', 1);
					}
				}
			}
		}
		//----------------------------------------------------
		elseif ($head==3) {
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query);
				$exento=number_format($field["exento"], 2, ',', '.'); $sum_exento+=$field['exento'];
				$sub_total=number_format($field["sub_total"], 2, ',', '.'); $sum_sub_total+=$field['sub_total'];
				$impuesto=number_format($field["impuesto"], 2, ',', '.'); $sum_impuesto+=$field['impuesto'];
				$total=number_format($field["total"], 2, ',', '.'); $sum_total+=$field['total'];
				$islr=number_format($field["islr"], 2, ',', '.'); $sum_islr+=$field['islr']; if ($islr=="0,00") $islr="";
				$total_retenido=number_format($field["total_retenido"], 2, ',', '.'); $sum_total_retenido+=$field['total_retenido'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($i==1) {
					orden_pago_contabilidad($pdf, $head, "", $categoria, $anio, $nmes[$mes], $tipo, $field['Fuente'], $estado);
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(60, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 1, 'C', 1);
				}
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(18, 18, 60, 18, 18, 18, 18, 18, 18, 18));
				$pdf->Row(array($fecha, $field['numero_orden'], utf8_decode($field['nombre']), $exento, $sub_total, $impuesto, $total_retenido, $islr, $amort, $total));
				$y=$pdf->GetY(); 
				if ($y>190 || $i==$rows) {
					$van_exento=number_format($sum_exento, 2, ',', '.');
					$van_sub_total=number_format($sum_sub_total, 2, ',', '.');
					$van_impuesto=number_format($sum_impuesto, 2, ',', '.');
					$van_total_retenido=number_format($sum_total_retenido, 2, ',', '.');
					$van_islr=number_format($sum_islr, 2, ',', '.');
					$van_amort=number_format($sum_amort, 2, ',', '.');
					$van_total=number_format($sum_total, 2, ',', '.');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					if ($i==$rows) $pdf->Cell(60, 5, 'TOTAL ............ >', 1, 0, 'C', 1); else $pdf->Cell(60, 5, 'VAN ............ >', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, $van_exento, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_sub_total, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_impuesto, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_total_retenido, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_islr, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_amort, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_total, 1, 1, 'R', 1);
					if ($i!=$rows) {
						orden_pago_contabilidad($pdf, $head, "", $categoria, $anio, $nmes[$mes], "", $field['Fuente'], $estado); 
						//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
						$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
						$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(60, 5, 'VIENEN ............ >', 1, 0, 'C', 1);
						$pdf->Cell(18, 5, $van_exento, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_sub_total, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_impuesto, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_total_retenido, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_islr, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_amort, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_total, 1, 1, 'R', 1);
					}
				}
			}
		}
		//----------------------------------------------------
		elseif ($head==4) {
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query);
				$exento=number_format($field["exento"], 2, ',', '.'); $sum_exento+=$field['exento'];
				$sub_total=number_format($field["sub_total"], 2, ',', '.'); $sum_sub_total+=$field['sub_total'];
				$impuesto=number_format($field["impuesto"], 2, ',', '.'); $sum_impuesto+=$field['impuesto'];
				$total=number_format($field["total"], 2, ',', '.'); $sum_total+=$field['total'];
				$islr=number_format($field["islr"], 2, ',', '.'); $sum_islr+=$field['islr']; if ($islr=="0,00") $islr="";
				$total_retenido=number_format($field["total_retenido"], 2, ',', '.'); $sum_total_retenido+=$field['total_retenido'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($i==1) {
					orden_pago_contabilidad($pdf, $head, $beneficiario, $categoria, $anio, $nmes[$mes], $tipo, $field['Fuente'], $estado);
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, '', 1, 1, 'C', 1);
				}
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(18, 18, 18, 18, 18, 18, 18, 18, 18));
				$pdf->Row(array($fecha, $field['numero_orden'], $exento, $sub_total, $impuesto, $total_retenido, $islr, $amort, $total));
				$y=$pdf->GetY(); 
				if ($y>190 || $i==$rows) {
					$van_exento=number_format($sum_exento, 2, ',', '.');
					$van_sub_total=number_format($sum_sub_total, 2, ',', '.');
					$van_impuesto=number_format($sum_impuesto, 2, ',', '.');
					$van_total_retenido=number_format($sum_total_retenido, 2, ',', '.');
					$van_islr=number_format($sum_islr, 2, ',', '.');
					$van_amort=number_format($sum_amort, 2, ',', '.');
					$van_total=number_format($sum_total, 2, ',', '.');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					if ($i==$rows) $pdf->Cell(18, 5, 'TOTAL', 1, 0, 'C', 1); else $pdf->Cell(18, 5, 'VAN', 1, 0, 'C', 1);
					$pdf->Cell(18, 5, $van_exento, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_sub_total, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_impuesto, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_total_retenido, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_islr, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_amort, 1, 0, 'R', 1);
					$pdf->Cell(18, 5, $van_total, 1, 1, 'R', 1);
					if ($i!=$rows) {
						orden_pago_contabilidad($pdf, $head, "", $categoria, $anio, $nmes[$mes], "", $field['Fuente'], $estado);
						//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
						$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
						$pdf->Cell(18, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(18, 5, 'VIENEN', 1, 0, 'C', 1);
						$pdf->Cell(18, 5, $van_exento, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_sub_total, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_impuesto, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_total_retenido, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_islr, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_amort, 1, 0, 'R', 1);
						$pdf->Cell(18, 5, $van_total, 1, 1, 'R', 1);
					}
				}
			}
		}
		break;
	
	//	Orden de Compra/Servicio...
	case "orden_compra_servicio_contabilidad":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(8, 10, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//	--------------------
		$dmes['01']=31; $dmes['03']=31; $dmes['04']=30; $dmes['05']=31; $dmes['06']=30; $dmes['07']=31; $dmes['08']=31; $dmes['09']=30; $dmes['10']=31; $dmes['11']=30; $dmes['12']=31;
		if ($anio%4==0) $dmes['02']=29; else $dmes['02']=28;
		$nmes['01']="ENERO";
		$nmes['02']="FEBRERO";
		$nmes['03']="MARZO";
		$nmes['04']="ABRIL";
		$nmes['05']="MAYO";
		$nmes['06']="JUNIO";
		$nmes['07']="JULIO";
		$nmes['08']="AGOSTO";
		$nmes['09']="SEPTIEMBRE";
		$nmes['10']="OCTUBRE";
		$nmes['11']="NOVIEMBRE";
		$nmes['12']="DICIEMBRE";
		$desde=$anio.'-'.$mes.'-01';
		$hasta=$anio.'-'.$mes.'-'.$dmes[$mes];
		//	--------------------
		if ($estado=="emitidas") $edo="AND (o.estado='procesado' OR o.estado='pagado')"; else $edo="AND o.estado='".$estado."'";
		$filtro="WHERE td.idtipos_documentos='".$tipo."' $edo AND o.idfuente_financiamiento='".$fuente."' AND o.fecha_orden>='$desde' AND o.fecha_orden<='$hasta'"; $dbeneficiario=0; $dcategoria=0; $dperiodo=1; $head=1;
		if ($estado=="procesado") $estado="PROCESADAS";
		elseif ($estado=="pagado") $estado="PAGADAS";
		elseif ($estado=="anulado") $estado="ANULADAS";
		elseif ($estado=="emitidas") $estado="EMITIDAS";
		////////////
		if ($_GET['idbeneficiario']!="") { $filtro.=" AND (b.idbeneficiarios='".$_GET['idbeneficiario']."') "; $dbeneficiario=1; $head=2; }
		if ($_GET['idcategoria']!="0") { 
			$filtro.=" AND (cp.idcategoria_programatica='".$_GET['idcategoria']."') "; $dcategoria=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($estado=="PAGADAS") if ($dbeneficiario==1) $head=4; else $head=3;
		////////////
		$sql = "SELECT 
					o.numero_orden AS numero_orden_compra, 
					o.fecha_orden AS fecha_orden_compra, 
					o.total, 
					b.nombre, 
					cp.codigo, 
					ue.denominacion, 
					ff.denominacion AS Fuente, 
					td.descripcion, 
					td.descripcion AS Tipo, 
					rpc.idorden_pago, 
					op.numero_orden AS numero_orden_pago, 
					op.fecha_orden AS fecha_orden_pago 
				FROM orden_compra_servicio o 
				INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
				INNER JOIN categoria_programatica cp ON (o.idcategoria_programatica=cp.idcategoria_programatica) 
				INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora=ue.idunidad_ejecutora) 
				INNER JOIN fuente_financiamiento ff ON (o.idfuente_financiamiento=ff.idfuente_financiamiento) 
				INNER JOIN tipos_documentos td ON (o.tipo=td.idtipos_documentos) 
				LEFT OUTER JOIN relacion_pago_compromisos rpc ON (o.idorden_compra_servicio=rpc.idorden_compra_servicio) 
				LEFT OUTER JOIN orden_pago op ON (rpc.idorden_pago=op.idorden_pago) $filtro 
				GROUP BY numero_orden_compra 
				ORDER BY o.codigo_referencia";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//---------------------------------------------------
		if ($head==1) {
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query);
				$total=number_format($field["total"], 2, ',', '.'); $sum_total+=$field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden_compra']); $fechaoc=$d."/".$m."/".$a;
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($i==1) {
					orden_compra_servicio_contabilidad($pdf, $head, "", $categoria, $anio, $nmes[$mes], $field['Fuente'], $estado, $field['Tipo']);
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(130, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, '', 1, 1, 'C', 1);
				}
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('L', 'C', 'L', 'R'));
				$pdf->SetWidths(array(20, 20, 130, 30));
				$pdf->Row(array($field['numero_orden_compra'], $fechaoc, utf8_decode($field['nombre']), $total));
				$y=$pdf->GetY(); 
				if ($y>250 || $i==$rows) {
					$van_total=number_format($sum_total, 2, ',', '.');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					if ($i==$rows) $pdf->Cell(130, 5, 'TOTAL ............ >', 1, 0, 'C', 1); else $pdf->Cell(130, 5, 'VAN ............ >', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, $van_total, 1, 1, 'R', 1);
					if ($i!=$rows) {
						orden_compra_servicio_contabilidad($pdf, $head, "", $categoria, $anio, $nmes[$mes], $field['Fuente'], $estado, $field['Tipo']);
						//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
						$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
						$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(130, 5, 'VIENEN ............ >', 1, 0, 'C', 1);
						$pdf->Cell(30, 5, $van_total, 1, 1, 'R', 1);
					}
				}
			}
		}
		//----------------------------------------------------
		elseif ($head==2) {
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query);
				$total=number_format($field["total"], 2, ',', '.'); $sum_total+=$field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden_compra']); $fechaoc=$d."/".$m."/".$a;
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($i==1) {
					orden_compra_servicio_contabilidad($pdf, $head, $beneficiario, $categoria, $anio, $nmes[$mes], $field['Fuente'], $estado, $field['Tipo']);
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, '', 1, 1, 'C', 1);
				}
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'R'));
				$pdf->SetWidths(array(20, 20, 30));
				$pdf->Row(array($field['numero_orden_compra'], $fechaoc, $total));
				$y=$pdf->GetY(); 
				if ($y>250 || $i==$rows) {
					$van_total=number_format($sum_total, 2, ',', '.');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, $van_total, 1, 1, 'R', 1);
					if ($i!=$rows) {
						orden_compra_servicio_contabilidad($pdf, $head, $beneficiario, $categoria, $anio, $nmes[$mes], $field['Fuente'], $estado, $field['Tipo']);
						//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
						$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
						$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(30, 5, $van_total, 1, 1, 'R', 1);
					}
				}
			}
		}
		//----------------------------------------------------
		elseif ($head==3) {
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query);
				$total=number_format($field["total"], 2, ',', '.'); $sum_total+=$field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden_compra']); $fechaoc=$d."/".$m."/".$a;
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden_pago']); $fechaop=$d."/".$m."/".$a;
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($i==1) {
					orden_compra_servicio_contabilidad($pdf, $head, "", $categoria, $anio, $nmes[$mes], $field['Fuente'], $estado, $field['Tipo']);
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(90, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, '', 1, 1, 'C', 1);
				}
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('L', 'C', 'L', 'C', 'L', 'R'));
				$pdf->SetWidths(array(20, 20, 20, 20, 90, 30));
				$pdf->Row(array($field['numero_orden_compra'], $fechaoc, $field['numero_orden_pago'], $fechaop, utf8_decode($field['nombre']), $total));
				$y=$pdf->GetY(); 
				if ($y>250 || $i==$rows) {
					$van_total=number_format($sum_total, 2, ',', '.');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					if ($i==$rows) $pdf->Cell(90, 5, 'TOTAL ............ >', 1, 0, 'C', 1); else $pdf->Cell(90, 5, 'VAN ............ >', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, $van_total, 1, 1, 'R', 1);
					if ($i!=$rows) {
						orden_compra_servicio_contabilidad($pdf, $head, "", $categoria, $anio, $nmes[$mes], $field['Fuente'], $estado, $field['Tipo']);
						//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
						$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
						$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(90, 5, 'VIENEN ............ >', 1, 0, 'C', 1);
						$pdf->Cell(30, 5, $van_total, 1, 1, 'R', 1);
					}
				}
			}
		}
		//----------------------------------------------------
		elseif ($head==4) {
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query);
				$total=number_format($field["total"], 2, ',', '.'); $sum_total+=$field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden_compra']); $fechaoc=$d."/".$m."/".$a;
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden_pago']); $fechaop=$d."/".$m."/".$a;
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($i==1) {
					orden_compra_servicio_contabilidad($pdf, $head, $beneficiario, $categoria, $anio, $nmes[$mes], $field['Fuente'], $estado, $field['Tipo']);
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, '', 1, 1, 'C', 1);
				}
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'L', 'C', 'R'));
				$pdf->SetWidths(array(20, 20, 20, 20, 30));
				$pdf->Row(array($field['numero_orden_compra'], $fechaoc, $field['numero_orden_pago'], $fechaop, $total));
				$y=$pdf->GetY(); 
				if ($y>250 || $i==$rows) {
					$van_total=number_format($sum_total, 2, ',', '.');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
					$pdf->Cell(30, 5, $van_total, 1, 1, 'R', 1);
					if ($i!=$rows) {
						orden_compra_servicio_contabilidad($pdf, $head, $beneficiario, $categoria, $anio, $nmes[$mes], $field['Fuente'], $estado, $field['Tipo']);
						//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
						$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(230, 230, 230); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
						$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(20, 5, '', 1, 0, 'C', 1);
						$pdf->Cell(30, 5, $van_total, 1, 1, 'R', 1);
					}
				}
			}
		}
		break;
	
	//	Grupos...
	case "contabilidad_grupo":
		$pdf = new PDF_MC_Table6('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//	--------------------
		contabilidad_grupo($pdf);
		//	--------------------
		$sql = "SELECT * FROM grupo_cuentas_contables ORDER BY codigo";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$pdf->Cell(35, 5); $pdf->Row(array($field['codigo'], utf8_decode($field['denominacion'])));
			$y = $pdf->GetY(); 
			if ($y > 260) { contabilidad_grupo($pdf); }
		}
		break;
	
	//	Sub Grupo...
	case "contabilidad_subgrupo":
		$pdf = new PDF_MC_Table6('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//	--------------------
		contabilidad_subgrupo($pdf);
		//	--------------------
		$sql = "SELECT 
						s.*, 
						g.codigo AS codgrupo, 
						g.denominacion AS nomgrupo 
				FROM 
						subgrupo_cuentas_contables s 
						INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables) 
				ORDER BY g.codigo, s.codigo";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$pdf->Cell(20, 5); $pdf->Row(array(utf8_decode($field['nomgrupo']), $field['codgrupo'].'.'.$field['codigo'], utf8_decode($field['denominacion'])));
			$y = $pdf->GetY(); 
			if ($y > 260) { contabilidad_subgrupo($pdf); }
		}
		break;
	
	//	Rubro...
	case "contabilidad_rubro":
		$pdf = new PDF_MC_Table6('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//	--------------------
		contabilidad_rubro($pdf);
		//	--------------------
		$sql = "SELECT 
						r.*, 
						s.codigo AS codsubgrupo, 
						s.denominacion AS nomsubgrupo , 
						g.codigo AS codgrupo 
				FROM 
						rubro_cuentas_contables r 
						INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables) 
						INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables) 
				ORDER BY g.codigo, s.codigo, r.codigo";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$pdf->Cell(20, 5); $pdf->Row(array(utf8_decode($field['nomsubgrupo']), $field['codgrupo'].'.'.$field['codsubgrupo'].'.'.$field['codigo'], utf8_decode($field['denominacion'])));
			$y = $pdf->GetY(); 
			if ($y > 260) { contabilidad_rubro($pdf); }
		}
		break;
	
	//	Cuenta...
	case "contabilidad_cuenta":
		$pdf = new PDF_MC_Table6('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//	--------------------
		contabilidad_cuenta($pdf);
		//	--------------------
		$sql = "SELECT 
						c.*, 
						r.codigo AS codrubro, 
						r.denominacion AS nomrubro, 
						s.codigo AS codsubgrupo, 
						g.codigo AS codgrupo 
				FROM 
						cuenta_cuentas_contables c 
						INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables) 
						INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables) 
						INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables) 
				ORDER BY g.codigo, s.codigo, r.codigo, c.codigo";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$pdf->Cell(15, 5); $pdf->Row(array(utf8_decode($field['nomrubro']), $field['codgrupo'].'.'.$field['codsubgrupo'].'.'.$field['codrubro'].'.'.$field['codigo'], utf8_decode($field['denominacion'])));
			$y = $pdf->GetY(); 
			if ($y > 260) { contabilidad_cuenta($pdf); }
		}
		break;
	
	//	Sub Cuenta de P.O...
	case "contabilidad_subcuenta1":
		$pdf = new PDF_MC_Table6('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//	--------------------
		contabilidad_subcuenta1($pdf);
		//	--------------------
		$sql = "SELECT 
						sc.*, 
						c.codigo AS codcuenta, 
						c.denominacion AS nomcuenta, 
						r.codigo AS codrubro, 
						s.codigo AS codsubgrupo, 
						g.codigo AS codgrupo 
				FROM 
						subcuenta_primer_cuentas_contables sc 
						INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta = c.idcuenta_cuentas_contables) 
						INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables) 
						INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables) 
						INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables) 
				ORDER BY g.codigo, s.codigo, r.codigo, c.codigo, sc.codigo";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$pdf->Cell(15, 5); $pdf->Row(array(utf8_decode($field['nomcuenta']), $field['codgrupo'].'.'.$field['codsubgrupo'].'.'.$field['codrubro'].'.'.$field['codcuenta'].'.'.$field['codigo'], utf8_decode($field['denominacion'])));
			$y = $pdf->GetY(); 
			if ($y > 260) { contabilidad_subcuenta1($pdf); }
		}
		break;
	
	//	Sub Cuenta de S.O...
	case "contabilidad_subcuenta2":
		$pdf = new PDF_MC_Table6('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//	--------------------
		contabilidad_subcuenta2($pdf);
		//	--------------------
		/*$sql = "SELECT 
						sc2.*, 
						sc.codigo AS codsubcuenta,  
						c.codigo AS codcuenta, 
						r.codigo AS codrubro, 
						s.codigo AS codsubgrupo, 
						g.codigo AS codgrupo 
				FROM 
						subcuenta_segundo_cuentas_contables sc2 
						INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables) 
						INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables) 
						INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables) 
						INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables) 
						INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables) 
				ORDER BY g.codigo, s.codigo, r.codigo, c.codigo, sc.codigo, sc2.codigo";*/
		$sql_grupo = "select * from grupo_cuentas_contables order by codigo";
		
		$query = mysql_query($sql_grupo) or die ($sql_grupo.mysql_error());
		$grupo=''; $subgrupo=''; $rubro=''; $cuenta=''; $subcuenta=''; 
		while ($bus_grupo = mysql_fetch_array($query)) {
			$pdf->Cell(5, 5); $pdf->Row(array($bus_grupo['codigo'], utf8_decode($bus_grupo['denominacion'])));
			$y = $pdf->GetY(); 
			if ($y > 260) { contabilidad_subcuenta2($pdf); }
			$sql_subgrupo = mysql_query("select * from subgrupo_cuentas_contables where idgrupo = '".$bus_grupo['idgrupos_cuentas_contables']."'");
			while ($bus_subgrupo = mysql_fetch_array($sql_subgrupo)) {
				$pdf->Cell(5, 5); $pdf->Row(array($bus_grupo['codigo'].'.'.$bus_subgrupo['codigo'], utf8_decode($bus_subgrupo['denominacion'])));
				$y = $pdf->GetY(); 
				if ($y > 260) { contabilidad_subcuenta2($pdf); }
				$sql_rubro = mysql_query("select * from rubro_cuentas_contables where idsubgrupo = '".$bus_subgrupo['idsubgrupo_cuentas_contables']."'");
				while ($bus_rubro = mysql_fetch_array($sql_rubro)) {
					$pdf->Cell(5, 5); $pdf->Row(array($bus_grupo['codigo'].'.'.$bus_subgrupo['codigo'].'.'.$bus_rubro['codigo'], utf8_decode($bus_rubro['denominacion'])));
					$y = $pdf->GetY(); 
					if ($y > 260) { contabilidad_subcuenta2($pdf); }
					$sql_cuenta = mysql_query("select * from cuenta_cuentas_contables where idrubro = '".$bus_rubro['idrubro_cuentas_contables']."'");
					while ($bus_cuenta = mysql_fetch_array($sql_cuenta)) {
						$pdf->Cell(5, 5); $pdf->Row(array($bus_grupo['codigo'].'.'.$bus_subgrupo['codigo'].'.'.$bus_rubro['codigo'].'.'.$bus_cuenta['codigo'], utf8_decode($bus_cuenta['denominacion'])));
						$y = $pdf->GetY(); 
						if ($y > 260) { contabilidad_subcuenta2($pdf); }
						$sql_subcuenta = mysql_query("select * from subcuenta_primer_cuentas_contables where idcuenta = '".$bus_cuenta['idcuenta_cuentas_contables']."'");
						while ($bus_subcuenta = mysql_fetch_array($sql_subcuenta)) {
							$pdf->Cell(5, 5); $pdf->Row(array($bus_grupo['codigo'].'.'.$bus_subgrupo['codigo'].'.'.$bus_rubro['codigo'].'.'.$bus_cuenta['codigo'].'.'.$bus_subcuenta['codigo'], utf8_decode($bus_subcuenta['denominacion'])));
							$y = $pdf->GetY(); 
							if ($y > 260) { contabilidad_subcuenta2($pdf); }
							$sql_subcuenta2 = mysql_query("select * from subcuenta_segundo_cuentas_contables where idsubcuenta_primer = '".$bus_subcuenta['idsubcuenta_primer_cuentas_contables']."'");
							while ($bus_subcuenta2 = mysql_fetch_array($sql_subcuenta2)) {
								$pdf->Cell(5, 5); $pdf->Row(array($bus_grupo['codigo'].'.'.$bus_subgrupo['codigo'].'.'.$bus_rubro['codigo'].'.'.$bus_cuenta['codigo'].'.'.$bus_subcuenta['codigo'].'.'.$bus_subcuenta2['codigo'], utf8_decode($bus_subcuenta2['denominacion'])));
								$y = $pdf->GetY(); 
								if ($y > 260) { contabilidad_subcuenta2($pdf); }
								$sql_desagrega = mysql_query("select * from desagregacion_cuentas_contables where idsubcuenta_segundo = '".$bus_subcuenta2["idsubcuenta_segundo_cuentas_contables"]."'");
								while ($desagrega = mysql_fetch_array($sql_desagrega)) {
									$pdf->Cell(5, 5); $pdf->Row(array($bus_grupo['codigo'].'.'.$bus_subgrupo['codigo'].'.'.$bus_rubro['codigo'].'.'.$bus_cuenta['codigo'].'.'.$bus_subcuenta['codigo'].'.'.$bus_subcuenta2['codigo'].'.'.$desagrega["codigo"], '       '.utf8_decode($desagrega['denominacion'])));
									$y = $pdf->GetY(); 
									if ($y > 260) { contabilidad_subcuenta2($pdf); }
								}
							}
						}
					}
				}
			}
		}
		break;
	
	//	Desagregacion...
	case "contabilidad_desagregacion":
		$pdf = new PDF_MC_Table6('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//	--------------------
		contabilidad_desagregacion($pdf);
		//	--------------------
		$sql = "SELECT 
						d.*, 
						sc2.codigo AS codsubcuenta2, 
						sc2.denominacion AS nomsubcuenta2, 
						sc.codigo AS codsubcuenta, 
						c.codigo AS codcuenta, 
						r.codigo AS codrubro, 
						s.codigo AS codsubgrupo, 
						g.codigo AS codgrupo 
				FROM 
						desagregacion_cuentas_contables d 
						INNER JOIN subcuenta_segundo_cuentas_contables sc2 ON (d.idsubcuenta_segundo = sc2.idsubcuenta_segundo_cuentas_contables) 
						INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables) 
						INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables) 
						INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables) 
						INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables) 
						INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables) 
				ORDER BY g.codigo, s.codigo, r.codigo, c.codigo, sc.codigo, sc2.codigo, d.codigo";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$pdf->Row(array(utf8_decode($field['nomsubcuenta2']), $field['codgrupo'].'.'.$field['codsubgrupo'].'.'.$field['codrubro'].'.'.$field['codcuenta'].'.'.$field['codsubcuenta2'].'.'.$field['codsubcuenta'].'.'.$field['codigo'], utf8_decode($field['denominacion'])));
			$y = $pdf->GetY(); 
			if ($y > 260) { contabilidad_desagregacion($pdf); }
		}
		break;

	//	Libro Diario Detallado...
	case "contabilidad_libro_diario":
		$pdf = new PDF_MC_Table5('L', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 15, 5);
		$pdf->SetAutoPageBreak(1, 10);

		//---------------------------------------------
		$desde_inicial = $anio_fiscal.'-01-01';
		$mes_inicial = 1;
		if ($mes=='01'){
			$idesde = '01-01-'.$anio_fiscal; $ihasta = '31-01-'.$anio_fiscal;
			$desde = $anio_fiscal.'-01-01'; $hasta = $anio_fiscal.'-01-31'; $hasta_anterior = $anio_fiscal.'-01-31';
			$mes_final=1;
		}
		if ($mes=='02'){
		   $idesde = '01-02-'.$anio_fiscal; if ($anio_fiscal%4==0) $ihasta = '29-02-'.$anio_fiscal; else $ihasta = '28-02-'.$anio_fiscal;
		   $desde = $anio_fiscal.'-02-01'; if ($anio_fiscal%4==0) $hasta = $anio_fiscal.'-02-29'; else $hasta = $anio_fiscal.'-02-28';
			$hasta_anterior = $anio_fiscal.'-01-31';
			$mes_final=2; $mes_anterior=1;
		}
		if ($mes=='03'){
			$idesde = '01-03-'.$anio_fiscal; $ihasta = '31-03-'.$anio_fiscal;
			$desde = $anio_fiscal.'-03-01'; $hasta = $anio_fiscal.'-03-31';
				if ($anio_fiscal%4==0) $hasta_anterior = $anio_fiscal.'-02-29'; else $hasta_anterior = $anio_fiscal.'-02-28';
			$mes_final=3; $mes_anterior=2;
		}
		if ($mes=='04'){
			$idesde = '01-04-'.$anio_fiscal; $ihasta = '30-04-'.$anio_fiscal;
			$desde = $anio_fiscal.'-04-01'; $hasta = $anio_fiscal.'-04-30'; $hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final=4; $mes_anterior=3;
		}
		if ($mes=='05'){
			$idesde = '01-05-'.$anio_fiscal; $ihasta = '31-05-'.$anio_fiscal;
			$desde = $anio_fiscal.'-05-01'; $hasta = $anio_fiscal.'-05-31'; $hasta_anterior = $anio_fiscal.'-04-30';
			$mes_final=5; $mes_anterior=4;
		}
		if ($mes=='06'){
			$idesde = '01-06-'.$anio_fiscal; $ihasta = '30-06-'.$anio_fiscal;
			$desde = $anio_fiscal.'-06-01'; $hasta = $anio_fiscal.'-06-30'; $hasta_anterior = $anio_fiscal.'-05-31';
			$mes_final=6; $mes_anterior=5;
		}
		if ($mes=='07'){
			$idesde = '01-07-'.$anio_fiscal; $ihasta = '31-07-'.$anio_fiscal;
			$desde = $anio_fiscal.'-07-01'; $hasta = $anio_fiscal.'-07-31'; $hasta_anterior = $anio_fiscal.'-06-30';
			$mes_final=7; $mes_anterior=6;
		}
		if ($mes=='08'){
			$idesde = '01-08-'.$anio_fiscal; $ihasta = '31-08-'.$anio_fiscal;
			$desde = $anio_fiscal.'-08-01'; $hasta = $anio_fiscal.'-08-31'; $hasta_anterior = $anio_fiscal.'-07-31';
			$mes_final=8; $mes_anterior=7;
		}
		if ($mes=='09'){
			$idesde = '01-09-'.$anio_fiscal; $ihasta = '30-09-'.$anio_fiscal;
			$desde = $anio_fiscal.'-09-01'; $hasta = $anio_fiscal.'-09-30'; $hasta_anterior = $anio_fiscal.'-08-31';
			$mes_final=9; $mes_anterior=8;
		}
		if ($mes=='10'){
			$idesde = '01-10-'.$anio_fiscal; $ihasta = '31-10-'.$anio_fiscal;
			$desde = $anio_fiscal.'-10-01'; $hasta = $anio_fiscal.'-10-31'; $hasta_anterior = $anio_fiscal.'-09-30';
			$mes_final=10; $mes_anterior=9;
		}
		if ($mes=='11'){
			$idesde = '01-11-'.$anio_fiscal; $ihasta = '30-11-'.$anio_fiscal;
			$desde = $anio_fiscal.'-11-01'; $hasta = $anio_fiscal.'-11-30'; $hasta_anterior = $anio_fiscal.'-10-31';
			$mes_final=11; $mes_anterior=10;
		}
		if ($mes=='12'){
			$idesde = '01-12-'.$anio_fiscal; $ihasta = '31-12-'.$anio_fiscal;
			$desde = $anio_fiscal.'-12-01'; $hasta = $anio_fiscal.'-12-31'; $hasta_anterior = $anio_fiscal.'-11-30';
			$mes_final=12; $mes_anterior=11;
		}




		//	Imprimo el encabezado en la primera hoja...
		contabilidad_libro_diario($pdf,$idesde,$ihasta,$anio_fiscal);

		//	--------------------
		$i = 0;
		$sql = "SELECT ac.*,
						cac.*
				FROM
						asiento_contable ac
						INNER JOIN cuentas_asiento_contable cac ON (ac.idasiento_contable = cac.idasiento_contable)
				WHERE
						(ac.fecha_contable >= '".$desde."' AND ac.fecha_contable <= '".$hasta."')
						AND (ac.estado = 'procesado' or ac.estado = 'anulado')
				ORDER BY ac.fecha_contable, ac.prioridad, ac.idasiento_contable, cac.afecta, cac.tabla, cac.idcuenta";
		//echo $sql;
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$sw=0;
		while ($field = mysql_fetch_array($query)) {
			$pdf->SetFont('Arial', '', 8);
			if ($sw != $field["idasiento_contable"]){
				$y = $pdf->GetY();
				if ($y > 175) { contabilidad_libro_diario($pdf,$idesde,$ihasta,$anio_fiscal); }
				$i++;
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'R', 'R', 'R', 'R', 'R'));
				$pdf->Row(array('', utf8_decode('ASIENTO No. ').$i, '', '', '', '', ''));

				$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R'));
				list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_contable"]); $fecha=$d."/".$m."/".$a;
				$pdf->SetFont('Arial', '', 7);
				$y = $pdf->GetY();
				if ($y > 175) { contabilidad_libro_diario($pdf,$idesde,$ihasta,$anio_fiscal); }
				$pdf->Row(array($fecha, utf8_decode($field["detalle"]), '', '', '', '', ''));
				$sw = $field["idasiento_contable"];
				$imprime_rubro = 'no';
				$rubro_anterior = '';
				$pdf->SetFont('Arial', '', 8);
			}
			$idcampo = "id".$field["tabla"];
			$sql_cuentas = mysql_query("select * from ".$field["tabla"]."
																	where ".$idcampo." = '".$field["idcuenta"]."'")or die(" tablas ".mysql_error());
			$bus_cuenta = mysql_fetch_array($sql_cuentas);

			if($field["tabla"] == 'cuenta_cuentas_contables'){
				if ($rubro_anterior != $bus_cuenta["idrubro"]){
					$imprime_rubro = 'no';
				}
				$sql_suma_rubro = mysql_query("SELECT SUM(monto) as monto, idasiento_contable, afecta, idcuenta, tabla
										  FROM cuentas_asiento_contable cac
											INNER JOIN cuenta_cuentas_contables ccc ON (cac.idcuenta = ccc.idcuenta_cuentas_contables)
											WHERE cac.idasiento_contable = '".$field["idasiento_contable"]."'
										  			AND cac.tabla = 'cuenta_cuentas_contables'
										  			AND ccc.idrubro = '".$bus_cuenta["idrubro"]."'
										GROUP BY ccc.idrubro, cac.afecta");

				$bus_suma_rubro = mysql_fetch_array($sql_suma_rubro);

				$sql_rubro = mysql_query("select * from rubro_cuentas_contables
																	where idrubro_cuentas_contables = '".$bus_cuenta["idrubro"]."'")or die(" tablas ".mysql_error());
				$bus_rubro = mysql_fetch_array($sql_rubro);
				if($field["afecta"] == 'debe'){
					$monto_debe =  number_format($bus_suma_rubro["monto"], 2, ',', '.');
					$monto_haber = '';
				}else{
					$monto_haber =  number_format($bus_suma_rubro["monto"], 2, ',', '.');
					$monto_debe = '';
				}
				$monto_cuenta = number_format($field["monto"], 2, ',', '.');

				$rubro_anterior = $bus_cuenta["idrubro"];

				if($imprime_rubro == 'no'){
					$y = $pdf->GetY();
					if ($y > 199) { contabilidad_libro_diario($pdf,$idesde,$ihasta,$anio_fiscal); }
					$pdf->Row(array('', utf8_decode($bus_rubro["codigo"]).' - '.utf8_decode($bus_rubro["denominacion"]), '', '', '', $monto_debe, $monto_haber));
					$imprime_rubro = 'si';
				}
				$y = $pdf->GetY();
				if ($y > 199) { contabilidad_libro_diario($pdf,$idesde,$ihasta,$anio_fiscal); }
				$pdf->Row(array('', utf8_decode('       '.' - '.$bus_cuenta["codigo"]).' - '.utf8_decode($bus_cuenta["denominacion"]), '', '', $monto_cuenta, '', ''));

			}
			if($field["tabla"] == 'rubro_cuentas_contables'){
				if($field["afecta"] == 'debe'){
					$monto_debe =  number_format($field["monto"], 2, ',', '.');
					$monto_haber = '';
				}else{
					$monto_haber =  number_format($field["monto"], 2, ',', '.');
					$monto_debe = '';
				}
				$y = $pdf->GetY();
				if ($y > 199) { contabilidad_libro_diario($pdf,$idesde,$ihasta,$anio_fiscal); }
				$pdf->Row(array('', utf8_decode($bus_cuenta["codigo"]).' - '.utf8_decode($bus_cuenta["denominacion"]), '', '', '', $monto_debe, $monto_haber));
			}
			$y = $pdf->GetY();
			if ($y > 199) { contabilidad_libro_diario($pdf,$idesde,$ihasta,$anio_fiscal); }


			//	Fin Imprimo Montos...
		}
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->Ln(5);
		$y = $pdf->GetY();
		$pdf->Rect(5, $y, 270, 0.05, 'DF');
		break;




	//	Libro Diario Resumido...
	case "contabilidad_libro_diarioR":
		$pdf = new PDF_MC_Table5('L', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 15, 5);
		$pdf->SetAutoPageBreak(1, 10);
		
		//---------------------------------------------
		$desde_inicial = $anio_fiscal.'-01-01';
		$mes_inicial = 1;
		if ($mes=='01'){
			$idesde = '01-01-'.$anio_fiscal; $ihasta = '31-01-'.$anio_fiscal;
			$desde = $anio_fiscal.'-01-01'; $hasta = $anio_fiscal.'-01-31'; $hasta_anterior = $anio_fiscal.'-01-31';
			$mes_final=1;
		}
		if ($mes=='02'){
		   $idesde = '01-02-'.$anio_fiscal; if ($anio_fiscal%4==0) $ihasta = '29-02-'.$anio_fiscal; else $ihasta = '28-02-'.$anio_fiscal;
		   $desde = $anio_fiscal.'-02-01'; if ($anio_fiscal%4==0) $hasta = $anio_fiscal.'-02-29'; else $hasta = $anio_fiscal.'-02-28';
			$hasta_anterior = $anio_fiscal.'-01-31';
			$mes_final=2; $mes_anterior=1;
		}
		if ($mes=='03'){
			$idesde = '01-03-'.$anio_fiscal; $ihasta = '31-03-'.$anio_fiscal;
			$desde = $anio_fiscal.'-03-01'; $hasta = $anio_fiscal.'-03-31';
				if ($anio_fiscal%4==0) $hasta_anterior = $anio_fiscal.'-02-29'; else $hasta_anterior = $anio_fiscal.'-02-28';
			$mes_final=3; $mes_anterior=2;
		}
		if ($mes=='04'){
			$idesde = '01-04-'.$anio_fiscal; $ihasta = '30-04-'.$anio_fiscal;
			$desde = $anio_fiscal.'-04-01'; $hasta = $anio_fiscal.'-04-30'; $hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final=4; $mes_anterior=3;
		}
		if ($mes=='05'){
			$idesde = '01-05-'.$anio_fiscal; $ihasta = '31-05-'.$anio_fiscal;
			$desde = $anio_fiscal.'-05-01'; $hasta = $anio_fiscal.'-05-31'; $hasta_anterior = $anio_fiscal.'-04-30';
			$mes_final=5; $mes_anterior=4;
		}
		if ($mes=='06'){
			$idesde = '01-06-'.$anio_fiscal; $ihasta = '30-06-'.$anio_fiscal;
			$desde = $anio_fiscal.'-06-01'; $hasta = $anio_fiscal.'-06-30'; $hasta_anterior = $anio_fiscal.'-05-31';
			$mes_final=6; $mes_anterior=5;
		}
		if ($mes=='07'){
			$idesde = '01-07-'.$anio_fiscal; $ihasta = '31-07-'.$anio_fiscal;
			$desde = $anio_fiscal.'-07-01'; $hasta = $anio_fiscal.'-07-31'; $hasta_anterior = $anio_fiscal.'-06-30';
			$mes_final=7; $mes_anterior=6;
		}
		if ($mes=='08'){
			$idesde = '01-08-'.$anio_fiscal; $ihasta = '31-08-'.$anio_fiscal; 
			$desde = $anio_fiscal.'-08-01'; $hasta = $anio_fiscal.'-08-31'; $hasta_anterior = $anio_fiscal.'-07-31';
			$mes_final=8; $mes_anterior=7;
		}
		if ($mes=='09'){
			$idesde = '01-09-'.$anio_fiscal; $ihasta = '30-09-'.$anio_fiscal;
			$desde = $anio_fiscal.'-09-01'; $hasta = $anio_fiscal.'-09-30'; $hasta_anterior = $anio_fiscal.'-08-31';
			$mes_final=9; $mes_anterior=8;
		}
		if ($mes=='10'){
			$idesde = '01-10-'.$anio_fiscal; $ihasta = '31-10-'.$anio_fiscal;
			$desde = $anio_fiscal.'-10-01'; $hasta = $anio_fiscal.'-10-31'; $hasta_anterior = $anio_fiscal.'-09-30';
			$mes_final=10; $mes_anterior=9;
		}
		if ($mes=='11'){
			$idesde = '01-11-'.$anio_fiscal; $ihasta = '30-11-'.$anio_fiscal;
			$desde = $anio_fiscal.'-11-01'; $hasta = $anio_fiscal.'-11-30'; $hasta_anterior = $anio_fiscal.'-10-31';
			$mes_final=11; $mes_anterior=10;
		}
		if ($mes=='12'){
			$idesde = '01-12-'.$anio_fiscal; $ihasta = '31-12-'.$anio_fiscal;
			$desde = $anio_fiscal.'-12-01'; $hasta = $anio_fiscal.'-12-31'; $hasta_anterior = $anio_fiscal.'-11-30';
			$mes_final=12; $mes_anterior=11;
		}




		//	Imprimo el encabezado en la primera hoja...
		contabilidad_libro_diarioR($pdf,$idesde,$ihasta,$anio_fiscal);
		
		//	--------------------
		$i = 0;
		$sql = "SELECT ac.*, 
						cac.*
				FROM 
						asiento_contable ac
						INNER JOIN cuentas_asiento_contable cac ON (ac.idasiento_contable = cac.idasiento_contable)
				WHERE 
						(ac.fecha_contable >= '".$desde."' AND ac.fecha_contable <= '".$hasta."')
						AND (ac.estado = 'procesado' or ac.estado = 'anulado')
				ORDER BY ac.fecha_contable, ac.tipo_movimiento, ac.idasiento_contable, ac.tipo_movimiento, cac.afecta, cac.tabla";
		//echo $sql;
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$sw=0;
		while ($field = mysql_fetch_array($query)) {
			$pdf->SetFont('Arial', '', 8);
			if ($sw != $field["idasiento_contable"]){
				$y = $pdf->GetY(); 
				if ($y > 175) { contabilidad_libro_diarioR($pdf,$idesde,$ihasta,$anio_fiscal); }
				$i++;
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'R', 'R', 'R', 'R', 'R'));
				$pdf->Row(array('', utf8_decode('ASIENTO No. ').$i, '', '', '', '', ''));
				
				$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R'));
				list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_contable"]); $fecha=$d."/".$m."/".$a;
				$pdf->SetFont('Arial', '', 7);
				$y = $pdf->GetY(); 
				if ($y > 175) { contabilidad_libro_diarioR($pdf,$idesde,$ihasta,$anio_fiscal); }
				$pdf->Row(array($fecha, utf8_decode($field["detalle"]), '', '', '', '', ''));
				$sw = $field["idasiento_contable"];
				$imprime_rubro = 'no';
				$pdf->SetFont('Arial', '', 8);
			}
			$idcampo = "id".$field["tabla"];
			$sql_cuentas = mysql_query("select * from ".$field["tabla"]." 
																	where ".$idcampo." = '".$field["idcuenta"]."'")or die(" tablas ".mysql_error());
			$bus_cuenta = mysql_fetch_array($sql_cuentas);

			if($field["tabla"] == 'cuenta_cuentas_contables'){
				$sql_suma_rubro = mysql_query("SELECT SUM(monto) as monto, idasiento_contable, afecta, idcuenta, tabla
										  FROM cuentas_asiento_contable cac
											INNER JOIN cuenta_cuentas_contables ccc ON (cac.idcuenta = ccc.idcuenta_cuentas_contables)
											WHERE cac.idasiento_contable = '".$field["idasiento_contable"]."'
										  AND cac.tabla = 'cuenta_cuentas_contables'
										GROUP BY ccc.idrubro, cac.afecta");

				$bus_suma_rubro = mysql_fetch_array($sql_suma_rubro);

				$sql_rubro = mysql_query("select * from rubro_cuentas_contables
																	where idrubro_cuentas_contables = '".$bus_cuenta["idrubro"]."'")or die(" tablas ".mysql_error());
				$bus_rubro = mysql_fetch_array($sql_rubro);
				if($field["afecta"] == 'debe'){
					$monto_debe =  number_format($bus_suma_rubro["monto"], 2, ',', '.');
					$monto_haber = '';
				}else{
					$monto_haber =  number_format($bus_suma_rubro["monto"], 2, ',', '.');
					$monto_debe = '';
				}
				$monto_cuenta = number_format($field["monto"], 2, ',', '.');

				if($imprime_rubro == 'no'){
					$y = $pdf->GetY(); 
					if ($y > 199) { contabilidad_libro_diarioR($pdf,$idesde,$ihasta,$anio_fiscal); }
					$pdf->Row(array('', utf8_decode($bus_rubro["codigo"]).' - '.utf8_decode($bus_rubro["denominacion"]), '', '', '', $monto_debe, $monto_haber));
					$imprime_rubro = 'si';
				}
				$y = $pdf->GetY(); 
				if ($y > 199) { contabilidad_libro_diarioR($pdf,$idesde,$ihasta,$anio_fiscal); }
				$pdf->Row(array('', utf8_decode('       '.' - '.$bus_cuenta["codigo"]).' - '.utf8_decode($bus_cuenta["denominacion"]), '', '', $monto_cuenta, '', ''));
				
			}
			if($field["tabla"] == 'rubro_cuentas_contables'){
				if($field["afecta"] == 'debe'){
					$monto_debe =  number_format($field["monto"], 2, ',', '.');
					$monto_haber = '';
				}else{
					$monto_haber =  number_format($field["monto"], 2, ',', '.');
					$monto_debe = '';
				}
				$y = $pdf->GetY(); 
				if ($y > 199) { contabilidad_libro_diarioR($pdf,$idesde,$ihasta,$anio_fiscal); }
				$pdf->Row(array('', utf8_decode($bus_cuenta["codigo"]).' - '.utf8_decode($bus_cuenta["denominacion"]), '', '', '', $monto_debe, $monto_haber));
			}
			$y = $pdf->GetY(); 
			if ($y > 199) { contabilidad_libro_diarioR($pdf,$idesde,$ihasta,$anio_fiscal); }

			
			//	Fin Imprimo Montos...
		}
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->Ln(5);
		$y = $pdf->GetY();
		$pdf->Rect(5, $y, 270, 0.05, 'DF'); 
		break;


//	Libro de Mayor...
case "contabilidad_libro_mayor":
	$pdf = new PDF_MC_Table5('L', 'mm', 'Letter');
	$pdf->Open();
	$pdf->SetMargins(5, 15, 5);
	$pdf->SetAutoPageBreak(1, 10);
	
	//---------------------------------------------
	$desde_inicial = $anio_fiscal.'-01-01';
	$mes_inicial = 1;
	if ($mes=='01'){
		$idesde = '01-01-'.$anio_fiscal; $ihasta = '31-01-'.$anio_fiscal;
		$desde = $anio_fiscal.'-01-01'; $hasta = $anio_fiscal.'-01-31'; $hasta_anterior = $anio_fiscal.'-01-31';
		$mes_final=1;
	}
	if ($mes=='02'){
	   $idesde = '01-02-'.$anio_fiscal; if ($anio_fiscal%4==0) $ihasta = '29-02-'.$anio_fiscal; else $ihasta = '28-02-'.$anio_fiscal;
	   $desde = $anio_fiscal.'-02-01'; if ($anio_fiscal%4==0) $hasta = $anio_fiscal.'-02-29'; else $hasta = $anio_fiscal.'-02-28';
		$hasta_anterior = $anio_fiscal.'-01-31';
		$mes_final=2; $mes_anterior=1;
	}
	if ($mes=='03'){
		$idesde = '01-03-'.$anio_fiscal; $ihasta = '31-03-'.$anio_fiscal;
		$desde = $anio_fiscal.'-03-01'; $hasta = $anio_fiscal.'-03-31';
			if ($anio_fiscal%4==0) $hasta_anterior = $anio_fiscal.'-02-29'; else $hasta_anterior = $anio_fiscal.'-02-28';
		$mes_final=3; $mes_anterior=2;
	}
	if ($mes=='04'){
		$idesde = '01-04-'.$anio_fiscal; $ihasta = '30-04-'.$anio_fiscal;
		$desde = $anio_fiscal.'-04-01'; $hasta = $anio_fiscal.'-04-30'; $hasta_anterior = $anio_fiscal.'-03-31';
		$mes_final=4; $mes_anterior=3;
	}
	if ($mes=='05'){
		$idesde = '01-05-'.$anio_fiscal; $ihasta = '31-05-'.$anio_fiscal;
		$desde = $anio_fiscal.'-05-01'; $hasta = $anio_fiscal.'-05-31'; $hasta_anterior = $anio_fiscal.'-04-30';
		$mes_final=5; $mes_anterior=4;
	}
	if ($mes=='06'){
		$idesde = '01-06-'.$anio_fiscal; $ihasta = '30-06-'.$anio_fiscal;
		$desde = $anio_fiscal.'-06-01'; $hasta = $anio_fiscal.'-06-30'; $hasta_anterior = $anio_fiscal.'-05-31';
		$mes_final=6; $mes_anterior=5;
	}
	if ($mes=='07'){
		$idesde = '01-07-'.$anio_fiscal; $ihasta = '31-07-'.$anio_fiscal;
		$desde = $anio_fiscal.'-07-01'; $hasta = $anio_fiscal.'-07-31'; $hasta_anterior = $anio_fiscal.'-06-30';
		$mes_final=7; $mes_anterior=6;
	}
	if ($mes=='08'){
		$idesde = '01-08-'.$anio_fiscal; $ihasta = '31-08-'.$anio_fiscal; 
		$desde = $anio_fiscal.'-08-01'; $hasta = $anio_fiscal.'-08-31'; $hasta_anterior = $anio_fiscal.'-07-31';
		$mes_final=8; $mes_anterior=7;
	}
	if ($mes=='09'){
		$idesde = '01-09-'.$anio_fiscal; $ihasta = '30-09-'.$anio_fiscal;
		$desde = $anio_fiscal.'-09-01'; $hasta = $anio_fiscal.'-09-30'; $hasta_anterior = $anio_fiscal.'-08-31';
		$mes_final=9; $mes_anterior=8;
	}
	if ($mes=='10'){
		$idesde = '01-10-'.$anio_fiscal; $ihasta = '31-10-'.$anio_fiscal;
		$desde = $anio_fiscal.'-10-01'; $hasta = $anio_fiscal.'-10-31'; $hasta_anterior = $anio_fiscal.'-09-30';
		$mes_final=10; $mes_anterior=9;
	}
	if ($mes=='11'){
		$idesde = '01-11-'.$anio_fiscal; $ihasta = '30-11-'.$anio_fiscal;
		$desde = $anio_fiscal.'-11-01'; $hasta = $anio_fiscal.'-11-30'; $hasta_anterior = $anio_fiscal.'-10-31';
		$mes_final=11; $mes_anterior=10;
	}
	if ($mes=='12'){
		$idesde = '01-12-'.$anio_fiscal; $ihasta = '31-12-'.$anio_fiscal;
		$desde = $anio_fiscal.'-12-01'; $hasta = $anio_fiscal.'-12-31'; $hasta_anterior = $anio_fiscal.'-11-30';
		$mes_final=12; $mes_anterior=11;
	}


	$sql_cuentas_mayor = mysql_query("SELECT * from rubro_cuentas_contables order by idrubro_cuentas_contables");
	while ($cuentas_mayor = mysql_fetch_array($sql_cuentas_mayor)) {
		$sql = "(SELECT ac.*, 
						cac.*
				FROM 
						asiento_contable ac
						INNER JOIN cuentas_asiento_contable cac ON (ac.idasiento_contable = cac.idasiento_contable)
				WHERE 
						(ac.fecha_contable >= '".$desde."' AND ac.fecha_contable <= '".$hasta."')
						AND (ac.estado = 'procesado' or ac.estado = 'anulado')
						AND (cac.tabla = 'rubro_cuentas_contables' and cac.idcuenta = '".$cuentas_mayor["idrubro_cuentas_contables"]."'))
			
			UNION 
				
				(SELECT ac.*, 
						cac.*
				FROM 
						asiento_contable ac
						INNER JOIN cuentas_asiento_contable cac ON (ac.idasiento_contable = cac.idasiento_contable)
						INNER JOIN cuenta_cuentas_contables ccc ON (cac.idcuenta = ccc.idcuenta_cuentas_contables)

				WHERE 
						(ac.fecha_contable >= '".$desde."' AND ac.fecha_contable <= '".$hasta."')
						AND (ac.estado = 'procesado' or ac.estado = 'anulado')
						AND (
								cac.tabla = 'cuenta_cuentas_contables' AND 
								ccc.idcuenta_cuentas_contables = cac.idcuenta AND
								ccc.idrubro = '".$cuentas_mayor["idrubro_cuentas_contables"]."'
							))
				ORDER BY fecha_contable, prioridad";

		//echo $sql;
		//	--------------------
		$i = 0;
		$suma_debe = 0; $suma_haber = 0;
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) > 0){
			//	Imprimo el encabezado en la primera hoja...
			contabilidad_libro_mayor($pdf,$idesde,$ihasta,$anio_fiscal, $cuentas_mayor["codigo"], $cuentas_mayor["denominacion"]);
			$sw=0;
			while ($field = mysql_fetch_array($query)) {
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R'));
				list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_contable"]); $fecha=$d."/".$m."/".$a;
				$pdf->SetFont('Arial', '', 8);
				$y = $pdf->GetY(); 
				if ($y > 175) { contabilidad_libro_mayor($pdf,$idesde,$ihasta,$anio_fiscal, $cuentas_mayor["codigo"], $cuentas_mayor["denominacion"]); }
				$debe = 0; $haber = 0;
				if($field["afecta"] == 'debe'){
					$debe = $field["monto"];
					$suma_debe += $field["monto"];
					$monto_debe =  number_format($field["monto"], 2, ',', '.');
					$monto_haber = '';
				}else{
					$haber = $field["monto"];
					$suma_haber += $field["monto"];
					$monto_haber =  number_format($field["monto"], 2, ',', '.');
					$monto_debe = '';
				}

				$saldo = number_format(($suma_debe-$suma_haber), 2, ',', '.');

				$pdf->Row(array($fecha, utf8_decode($field["detalle"]), '', $monto_debe, $monto_haber, $saldo));
				
				


				}
				
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->Ln(5);
			$y = $pdf->GetY();
			$pdf->Rect(5, $y, 270, 0.05, 'DF');
			$pdf->Ln(5);
			$total_debe =  number_format($suma_debe, 2, ',', '.');
			$total_haber =  number_format($suma_haber, 2, ',', '.');
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Row(array('', 'TOTALES Bs.', '', $total_debe, $total_haber, $saldo));
				
				//	Fin Imprimo Montos...
			}
		}
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->Ln(5);
	$y = $pdf->GetY();
	$pdf->Rect(5, $y, 270, 0.05, 'DF'); 
	break;

}
//	----------------------------------------
//	FIN CUERPO DE LOS REPORTES
//	----------------------------------------

$pdf->Output();
?>