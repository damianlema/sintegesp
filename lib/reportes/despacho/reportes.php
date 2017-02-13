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
	//	Certificacion de Compromisos...
	case "filtro_compromisos_despacho":
		$pdf=new PDF_MC_Table('L', 'mm', 'Letter');
		$pdf->Open();
		$filtro=""; $dbeneficiario=0; $dcategoria=0; $dperiodo=1; $dtipo=0; $darticulo=0; $destado=0; $head=0;
		////////////
		if ($_GET['idbeneficiario']!="") { $filtro=" AND (beneficiarios.idbeneficiarios='".$_GET['idbeneficiario']."') "; $dbeneficiario=1; $head=2; }
		if ($_GET['idcategoria']!="") { 
			$filtro.=" AND (categoria_programatica.idcategoria_programatica='".$_GET['idcategoria']."') "; $dcategoria=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['idarticulo']!="") { 
			$filtro.=" AND (articulos_servicios.idarticulos_servicios='".$_GET['idarticulo']."') "; $darticulo=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['desde']!="" && $_GET['hasta']!="") {
			list($d, $m, $a)=SPLIT( '[/.-]', $_GET['desde']); $fecha_desde=$a."/".$m."/".$d;
			list($d, $m, $a)=SPLIT( '[/.-]', $_GET['hasta']); $fecha_hasta=$a."/".$m."/".$d;
			$filtro.=" AND (orden_compra_servicio.fecha_orden>='".$desde."' AND orden_compra_servicio.fecha_orden<='".$hasta."') "; $dperiodo=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['idtipo']!="0") { 
			$filtro.=" AND (tipos_documentos.idtipos_documentos='".$_GET['idtipo']."') "; $dtipo=1;
			if ($dbeneficiario==1) $head=4; else $head=3;
		}
		if ($_GET['idestado']!="0") { 
			$filtro.=" AND (orden_compra_servicio.estado='".$_GET['idestado']."') "; $destado=1;
			if ($head==0) $head=5;
			elseif ($head==2) $head=6;
			elseif ($head==3) $head=7;
			elseif ($head==4) $head=8;
		}
		if ($filtro=="") $head=1;
		if ($_SESSION['modulo']=="1") $or="OR tipos_documentos.modulo like '%-13-%'";
		elseif ($_SESSION['modulo']=="13") $or="OR tipos_documentos.modulo like '%-1-%'";
		else $or="";
		////////////
		$sql="SELECT
				  categoria_programatica.idcategoria_programatica,
				  categoria_programatica.codigo,
				  unidad_ejecutora.denominacion,
				  articulos_servicios.descripcion As articulo,
				  articulos_compra_servicio.idarticulos_servicios,
				  tipos_documentos.descripcion,
				  orden_compra_servicio.numero_orden,
				  orden_compra_servicio.fecha_orden,
				  orden_compra_servicio.estado,
				  orden_compra_servicio.justificacion,
				  orden_compra_servicio.total AS Monto,
				  beneficiarios.nombre
				FROM
				  orden_compra_servicio
				  INNER JOIN articulos_compra_servicio ON (articulos_compra_servicio.idorden_compra_servicio=articulos_compra_servicio.idorden_compra_servicio)
				  INNER JOIN articulos_servicios ON (articulos_compra_servicio.idarticulos_servicios=articulos_servicios.idarticulos_servicios)
				  INNER JOIN categoria_programatica ON (categoria_programatica.idcategoria_programatica=articulos_compra_servicio.idcategoria_programatica)
				  INNER JOIN tipos_documentos ON (orden_compra_servicio.tipo=tipos_documentos.idtipos_documentos AND (tipos_documentos.modulo like '%-".$_SESSION['modulo']."-%' $or))
				  INNER JOIN beneficiarios ON (orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios)
				  INNER JOIN unidad_ejecutora ON (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)
				WHERE 1 $filtro
				GROUP BY (orden_compra_servicio.idorden_compra_servicio) 
				ORDER BY orden_compra_servicio.codigo_referencia";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//---------------------------------------------------
		if ($head==1) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$monto=number_format($field['Monto'], 2, ',', '.'); $sum_monto+=$field['Monto'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_despacho_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'C', 'L', 'L', 'R', 'L'));
				$pdf->SetWidths(array(20, 15, 20, 35, 60, 30, 80));
				$pdf->Row(array($field['numero_orden'], $fecha, $estado, utf8_decode($field['descripcion']), utf8_decode($field['nombre']), $monto, utf8_decode($field['justificacion'])));
				$y=$pdf->GetY(); if ($y>175) filtro_compromisos_despacho_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
			}
			$monto=number_format($sum_monto, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', '', '', '', $monto, ''));
		}
		//----------------------------------------------------
		elseif ($head==2) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$monto=number_format($field['Monto'], 2, ',', '.'); $sum_monto+=$field['Monto'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_despacho_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'C', 'L', 'R', 'L'));
				$pdf->SetWidths(array(25, 20, 25, 40, 30, 120));
				$pdf->Row(array($field['numero_orden'], $fecha, $estado, utf8_decode($field['descripcion']), $monto, utf8_decode($field['justificacion'])));
				$y=$pdf->GetY(); if ($y>175) filtro_compromisos_despacho_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
			}
			$monto=number_format($sum_monto, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', '', '', $monto, ''));
		}
		//----------------------------------------------------
		elseif ($head==3) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$monto=number_format($field['Monto'], 2, ',', '.'); $sum_monto+=$field['Monto'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_despacho_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'C', 'L', 'R', 'L'));
				$pdf->SetWidths(array(25, 20, 25, 60, 30, 100));
				$pdf->Row(array($field['numero_orden'], $fecha, $estado, utf8_decode($field['nombre']), $monto, utf8_decode($field['justificacion'])));
				$y=$pdf->GetY(); if ($y>175) filtro_compromisos_despacho_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
			}
			$monto=number_format($sum_monto, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', '', '', $monto, ''));
		}
		//----------------------------------------------------
		elseif ($head==4) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$monto=number_format($field['Monto'], 2, ',', '.'); $sum_monto+=$field['Monto'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_despacho_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'C', 'R', 'L'));
				$pdf->SetWidths(array(25, 20, 25, 30, 160));
				$pdf->Row(array($field['numero_orden'], $fecha, $estado, $monto, utf8_decode($field['justificacion'])));
				$y=$pdf->GetY(); if ($y>175) filtro_compromisos_despacho_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
			}
			$monto=number_format($sum_monto, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', '', $monto, ''));
		}
		//----------------------------------------------------
		elseif ($head==5) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$monto=number_format($field['Monto'], 2, ',', '.'); $sum_monto+=$field['Monto'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_despacho_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'L', 'L', 'R', 'L'));
				$pdf->SetWidths(array(25, 20, 35, 60, 30, 90));
				$pdf->Row(array($field['numero_orden'], $fecha, utf8_decode($field['descripcion']), utf8_decode($field['nombre']), $monto, utf8_decode($field['justificacion'])));
				$y=$pdf->GetY(); if ($y>175) filtro_compromisos_despacho_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
			}
			$monto=number_format($sum_monto, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', '', '', $monto, ''));
		}
		//----------------------------------------------------
		elseif ($head==6) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$monto=number_format($field['Monto'], 2, ',', '.'); $sum_monto+=$field['Monto'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_despacho_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'L', 'R', 'L'));
				$pdf->SetWidths(array(25, 20, 40, 30, 145));
				$pdf->Row(array($field['numero_orden'], $fecha, utf8_decode($field['descripcion']), $monto, utf8_decode($field['justificacion'])));
				$y=$pdf->GetY(); if ($y>175) filtro_compromisos_despacho_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
			}
			$monto=number_format($sum_monto, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', '', $monto, ''));
		}
		//----------------------------------------------------
		elseif ($head==7) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$monto=number_format($field['Monto'], 2, ',', '.'); $sum_monto+=$field['Monto'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_despacho_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'L', 'R', 'L'));
				$pdf->SetWidths(array(25, 20, 60, 30, 125));
				$pdf->Row(array($field['numero_orden'], $fecha, utf8_decode($field['nombre']), $monto, utf8_decode($field['justificacion'])));
				$y=$pdf->GetY(); if ($y>175) filtro_compromisos_despacho_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
			}
			$monto=number_format($sum_monto, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', '', $monto, ''));
		}
		//----------------------------------------------------
		elseif ($head==8) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$monto=number_format($field['Monto'], 2, ',', '.'); $sum_monto+=$field['Monto'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_despacho_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'R', 'L'));
				$pdf->SetWidths(array(25, 20, 30, 185));
				$pdf->Row(array($field['numero_orden'], $fecha, $monto, utf8_decode($field['justificacion'])));
				$y=$pdf->GetY(); if ($y>175) filtro_compromisos_despacho_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
			}
			$monto=number_format($sum_monto, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', $monto, ''));
		}
		break;
	
}
//	----------------------------------------
//	FIN CUERPO DE LOS REPORTES
//	----------------------------------------

$pdf->Output();
?>