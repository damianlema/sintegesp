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
	//	Solicitud de Cotizaciones...
	case "filtro_solicitud_cotizacion":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$filtro=""; $dbeneficiario=0; $dcategoria=0; $dperiodo=1; $dtipo=0; $darticulo=0; $destado=0; $head=0;
		////////////
		if ($_GET['idbeneficiario']!="") { $filtro=" AND (beneficiarios.idbeneficiarios='".$_GET['idbeneficiario']."') "; $dbeneficiario=1; $head=2; }
		if ($_GET['idarticulo']!="") { 
			$filtro.=" AND (articulos_solicitud_cotizacion.idarticulos_servicios='".$_GET['idarticulo']."') "; $darticulo=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['desde']!="" && $_GET['hasta']!="") {
			list($d, $m, $a)=SPLIT( '[/.-]', $_GET['desde']); $fecha_desde=$a."-".$m."-".$d;
			list($d, $m, $a)=SPLIT( '[/.-]', $_GET['hasta']); $fecha_hasta=$a."-".$m."-".$d;
			$filtro.=" AND (solicitud_cotizacion.fecha_solicitud>='".$fecha_desde."' AND solicitud_cotizacion.fecha_solicitud<='".$fecha_hasta."') "; $dperiodo=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['idtipo']!="0") { 
			$filtro.=" AND (tipos_documentos.idtipos_documentos='".$_GET['idtipo']."') "; $dtipo=1;
			if ($dbeneficiario==1) $head=4; else $head=3;
		}
		if ($_GET['idestado']!="0") { 
			$filtro.=" AND (solicitud_cotizacion.estado='".$_GET['idestado']."') "; $destado=1;
			if ($head==0) $head=5;
			elseif ($head==2) $head=6;
			elseif ($head==3) $head=7;
			elseif ($head==4) $head=8;
		}
		if ($filtro=="") $head=1;
		////////////	
		if ($_GET['idarticulo']!="")
			$sql="SELECT solicitud_cotizacion.numero, 
						 solicitud_cotizacion.fecha_solicitud, 
						 solicitud_cotizacion.estado, 
						 proveedores_solicitud_cotizacion.idbeneficiarios, 
						 beneficiarios.nombre, 
						 tipos_documentos.descripcion, 
						 articulos_solicitud_cotizacion.idarticulos_servicios 
					FROM 
						 solicitud_cotizacion, 
						 proveedores_solicitud_cotizacion, 
						 beneficiarios, 
						 tipos_documentos, 
						 articulos_solicitud_cotizacion 
					WHERE 
						 (solicitud_cotizacion.idsolicitud_cotizacion=proveedores_solicitud_cotizacion.idsolicitud_cotizacion) AND
						 (proveedores_solicitud_cotizacion.idbeneficiarios=beneficiarios.idbeneficiarios) AND 
						 (solicitud_cotizacion.tipo=tipos_documentos.idtipos_documentos) AND 
						 (solicitud_cotizacion.idsolicitud_cotizacion=articulos_solicitud_cotizacion.idsolicitud_cotizacion) AND 
						 (tipos_documentos.modulo like '%-4-%') $filtro 
					ORDER BY solicitud_cotizacion.numero";
		else
			$sql="SELECT solicitud_cotizacion.numero, 
						 solicitud_cotizacion.fecha_solicitud, 
						 solicitud_cotizacion.estado, 
						 proveedores_solicitud_cotizacion.idbeneficiarios, 
						 beneficiarios.nombre, 
						 tipos_documentos.descripcion 
					FROM 
						 solicitud_cotizacion, 
						 proveedores_solicitud_cotizacion, 
						 beneficiarios, 
						 tipos_documentos 
					WHERE 
						 (solicitud_cotizacion.idsolicitud_cotizacion=proveedores_solicitud_cotizacion.idsolicitud_cotizacion) AND
						 (proveedores_solicitud_cotizacion.idbeneficiarios=beneficiarios.idbeneficiarios) AND 
						 (solicitud_cotizacion.tipo=tipos_documentos.idtipos_documentos) AND 
						 (tipos_documentos.modulo like '%-4-%') $filtro 
					ORDER BY solicitud_cotizacion.numero";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//---------------------------------------------------
		if ($head==1) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, "", $_GET['desde'], $_GET['hasta'], "", $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'C', 'L', 'L'));
				$pdf->SetWidths(array(25, 20, 25, 45, 80));
				$pdf->Row(array($field['numero'], $fecha, $estado, utf8_decode($field['descripcion']), utf8_decode($field['nombre'])));
			}
		}
		//----------------------------------------------------
		elseif ($head==2) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, $beneficiario, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'C', 'L'));
				$pdf->SetWidths(array(25, 20, 25, 125));
				$pdf->Row(array($field['numero'], $fecha, $estado, utf8_decode($field['descripcion'])));
			}
		}
		//----------------------------------------------------
		elseif ($head==3) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, "", $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'C', 'L'));
				$pdf->SetWidths(array(25, 20, 25, 125));
				$pdf->Row(array($field['numero'], $fecha, $estado, utf8_decode($field['nombre'])));
			}
		}
		//----------------------------------------------------
		elseif ($head==4) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, $beneficiario, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'C'));
				$pdf->SetWidths(array(25, 20, 25));
				$pdf->Row(array($field['numero'], $fecha, $estado));
			}
		}
		//----------------------------------------------------
		elseif ($head==5) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, "", $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'L', 'L'));
				$pdf->SetWidths(array(25, 20, 45, 80));
				$pdf->Row(array($field['numero'], $fecha, utf8_decode($field['descripcion']), utf8_decode($field['nombre'])));
			}
		}
		//----------------------------------------------------
		elseif ($head==6) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, $beneficiario, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'L'));
				$pdf->SetWidths(array(25, 20, 125));
				$pdf->Row(array($field['numero'], $fecha, utf8_decode($field['descripcion'])));
			}
		}
		//----------------------------------------------------
		elseif ($head==7) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, "", $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'L'));
				$pdf->SetWidths(array(25, 20, 125));
				$pdf->Row(array($field['numero'], $fecha, utf8_decode($field['nombre'])));
			}
		}
		//----------------------------------------------------
		elseif ($head==8) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, $beneficiario, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C'));
				$pdf->SetWidths(array(25, 20));
				$pdf->Row(array($field['numero'], $fecha));
			}
		}
		break;
		
	//	Certificacion de Compromisos...
	case "filtro_compromisos":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$filtro=""; $dbeneficiario=0; $dcategoria=0; $dperiodo=1; $dtipo=0; $darticulo=0; $destado=0; $head=0;
		////////////
		if ($_GET['idbeneficiario']!="") { $filtro=" AND (b.idbeneficiarios='".$_GET['idbeneficiario']."') "; $dbeneficiario=1; $head=2; }
		if ($_GET['idcategoria']!="") { 
			$filtro.=" AND (c.idcategoria_programatica='".$_GET['idcategoria']."') "; $dcategoria=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['idarticulo']!="") { 
			$filtro.=" AND (a.idarticulos_servicios='".$_GET['idarticulo']."') "; $darticulo=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['desde']!="" && $_GET['hasta']!="") {
			list($d, $m, $a)=SPLIT( '[/.-]', $_GET['desde']); $fecha_desde=$a."/".$m."/".$d;
			list($d, $m, $a)=SPLIT( '[/.-]', $_GET['hasta']); $fecha_hasta=$a."/".$m."/".$d;
			$filtro.=" AND (ocs.fecha_orden>='".$desde."' AND ocs.fecha_orden<='".$hasta."') "; $dperiodo=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['idtipo']!="0") { 
			$filtro.=" AND (td.idtipos_documentos='".$_GET['idtipo']."') "; $dtipo=1;
			if ($dbeneficiario==1) $head=4; else $head=3;
		}
		if ($_GET['idestado']!="0") { 
			$filtro.=" AND (ocs.estado='".$_GET['idestado']."') "; $destado=1;
			if ($head==0) $head=5;
			elseif ($head==2) $head=6;
			elseif ($head==3) $head=7;
			elseif ($head==4) $head=8;
		}
		if ($filtro=="") $head=1;
		if ($_SESSION['modulo']=="1") $or="OR td.modulo LIKE '%-13-%'";
		elseif ($_SESSION['modulo']=="13") $or="OR td.modulo LIKE '%-1-%'";
		else $or="";
		////////////
			$sql="SELECT
						ocs.numero_orden, 
						ocs.fecha_orden, 
						ocs.estado, 
						ocs.total, 
						td.descripcion, 
						b.nombre, 
						c.codigo, 
						ue.denominacion, 
						a.descripcion As articulo 
					FROM
						orden_compra_servicio ocs 
						INNER JOIN partidas_orden_compra_servicio pocs ON (ocs.idorden_compra_servicio=pocs.idorden_compra_servicio) 
						INNER JOIN maestro_presupuesto mp ON (pocs.idmaestro_presupuesto=mp.idRegistro) 
						INNER JOIN categoria_programatica c ON (mp.idcategoria_programatica=c.idcategoria_programatica) 
						INNER JOIN unidad_ejecutora ue ON (c.idunidad_ejecutora=ue.idunidad_ejecutora) 
						INNER JOIN tipos_documentos td ON (ocs.tipo=td.idtipos_documentos) 
						INNER JOIN beneficiarios b ON (ocs.idbeneficiarios=b.idbeneficiarios) 
						INNER JOIN articulos_compra_servicio acs ON (ocs.idorden_compra_servicio=acs.idorden_compra_servicio) 
						INNER JOIN articulos_servicios a ON (acs.idarticulos_servicios=a.idarticulos_servicios) 
					WHERE (td.modulo like '%-".$_SESSION['modulo']."-%' $or) $filtro
					GROUP BY (ocs.idorden_compra_servicio) ORDER BY ocs.codigo_referencia";
			
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//---------------------------------------------------
		if ($head==1) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$total=number_format($field['total'], 2, ',', '.'); $sum_total+=$field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'C', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 25, 95, 30));
				$pdf->Row(array($field['numero_orden'], $fecha, $estado, utf8_decode($field['nombre']), $total));
				$y=$pdf->GetY(); if ($y>250) filtro_compromisos_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
			}
			$sum_total=number_format($sum_total, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', '', '', $sum_total));
		}
		//----------------------------------------------------
		elseif ($head==2) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$total=number_format($field['total'], 2, ',', '.'); $sum_total+=$field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'C', 'R'));
				$pdf->SetWidths(array(25, 20, 25, 30));
				$pdf->Row(array($field['numero_orden'], $fecha, $estado, $total));
				$y=$pdf->GetY(); if ($y>250) filtro_compromisos_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
			}
			$sum_total=number_format($sum_total, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', '', $sum_total));
		}
		//----------------------------------------------------
		elseif ($head==3) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$total=number_format($field['total'], 2, ',', '.'); $sum_total+=$field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'C', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 25, 125, 30));
				$pdf->Row(array($field['numero_orden'], $fecha, $estado, utf8_decode($field['nombre']), $total));
				$y=$pdf->GetY(); if ($y>250) filtro_compromisos_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
			}
			$sum_total=number_format($sum_total, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', '', '', $sum_total));
		}
		//----------------------------------------------------
		elseif ($head==4) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$total=number_format($field['total'], 2, ',', '.'); $sum_total+=$field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'C', 'R'));
				$pdf->SetWidths(array(25, 20, 25, 30));
				$pdf->Row(array($field['numero_orden'], $fecha, $estado, $total));
				$y=$pdf->GetY(); if ($y>250) filtro_compromisos_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
			}
			$sum_total=number_format($sum_total, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', '', $sum_total));
		}
		//----------------------------------------------------
		elseif ($head==5) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$total=number_format($field['total'], 2, ',', '.'); $sum_total+=$field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 80, 30));
				$pdf->Row(array($field['numero_orden'], $fecha, utf8_decode($field['nombre']), $total));
				$y=$pdf->GetY(); if ($y>250) filtro_compromisos_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
			}
			$sum_total=number_format($sum_total, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', '', $sum_total));
		}
		//----------------------------------------------------
		elseif ($head==6) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$total=number_format($field['total'], 2, ',', '.'); $sum_total+=$field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'R'));
				$pdf->SetWidths(array(25, 20, 30));
				$pdf->Row(array($field['numero_orden'], $fecha, $total));
				$y=$pdf->GetY(); if ($y>250) filtro_compromisos_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
			}
			$sum_total=number_format($sum_total, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', $sum_total));
		}
		//----------------------------------------------------
		elseif ($head==7) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$total=number_format($field['total'], 2, ',', '.'); $sum_total+=$field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 125, 30));
				$pdf->Row(array($field['numero_orden'], $fecha, utf8_decode($field['nombre']), $total));
				$y=$pdf->GetY(); if ($y>250) filtro_compromisos_head($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
			}
			$sum_total=number_format($sum_total, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', '', $sum_total));
		}
		//----------------------------------------------------
		elseif ($head==8) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$total=number_format($field['total'], 2, ',', '.'); $sum_total+=$field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_compromisos_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'R'));
				$pdf->SetWidths(array(25, 20, 30));
				$pdf->Row(array($field['numero_orden'], $fecha, $total));
				$y=$pdf->GetY(); if ($y>250) filtro_compromisos_head($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
			}
			$sum_total=number_format($sum_total, 2, ',', '.');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('', '', $sum_total));
		}
		break;
		
	//	Ordenes de Pago...
	case "filtro_orden_pago":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetMargins(2, 2 , 2); 
		$pdf->SetAutoPageBreak(true,3);  
		$pdf->Open();

		$filtro=""; $filtro_inner=""; $dbeneficiario=0; $dcategoria=0; $dperiodo=1; $dtipo=0; $darticulo=0; $destado=0; $head=0; $dfuente=0;
		////////////
		$mostrarPartidas = $_GET["chkpar"];
		$mostrarConcepto = $_GET["chkconcepto"];
		$categoria='';
		if ($_GET['idbeneficiario']!="") { $filtro=" AND (beneficiarios.idbeneficiarios='".$_GET['idbeneficiario']."') "; $dbeneficiario=1; $head=2; }
		
		if ($_GET['idcategoria']!="") { 
			$filtro_inner = "INNER JOIN partidas_orden_pago ON (orden_pago.idorden_pago = partidas_orden_pago.idorden_pago)
  								INNER JOIN maestro_presupuesto ON (partidas_orden_pago.idmaestro_presupuesto = maestro_presupuesto.idRegistro)";
			$filtro.=" AND (orden_pago.idcategoria_programatica = '".$_GET['idcategoria']."' 
							or 
							(tipos_documentos.multi_categoria ='si'
								AND partidas_orden_pago.idorden_pago = orden_pago.idorden_pago
								AND maestro_presupuesto.idRegistro=partidas_orden_pago.idmaestro_presupuesto
								AND maestro_presupuesto.idcategoria_programatica='".$_GET['idcategoria']."')) "; 

			$dcategoria=1;
			$sql_categoria = mysql_query("select unidad_ejecutora.denominacion, categoria_programatica.codigo from unidad_ejecutora,categoria_programatica
													where categoria_programatica.idcategoria_programatica = '".$_GET['idcategoria']."'
													and unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora");
			$bus_categoria = mysql_fetch_array($sql_categoria);
			$categoria = $bus_categoria["codigo"]." - ".$bus_categoria["denominacion"];
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		
		if ($_GET['idfuente_financiamiento']!='0') { 
			$filtro.=" AND (orden_pago.idfuente_financiamiento = '".$_GET['idfuente_financiamiento']."') "; $dcategoria=1;
			$sql_fuente = mysql_query("select fuente_financiamiento.denominacion from fuente_financiamiento
													where fuente_financiamiento.idfuente_financiamiento = '".$_GET['idfuente_financiamiento']."'
													");
			$bus_fuente = mysql_fetch_array($sql_fuente);
			$fuente = $bus_fuente["denominacion"];
			if ($dfuente==1) $head=2; else $head=1;
		}
		if ($_GET['desde']!="" && $_GET['hasta']!="") {
			list($a, $m, $d)=SPLIT( '[/.-]', $_GET['desde']); $fecha_desde=$a."-".$m."-".$d;
			list($a, $m, $d)=SPLIT( '[/.-]', $_GET['hasta']); $fecha_hasta=$a."-".$m."-".$d;
			$filtro.=" AND (orden_pago.fecha_orden>='".$fecha_desde."' AND orden_pago.fecha_orden<='".$fecha_hasta."') "; $dperiodo=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['idmodulo']!="0") { 
			$sql_modulo=mysql_query("select nombre_modulo from modulo where id_modulo = '".$_GET["idmodulo"]."'");
			$bus_modulo=mysql_fetch_array($sql_modulo);
			$nombrem = $bus_modulo["nombre_modulo"];
			if ($nombrem == 'Compras y Servicios'){
				$sql_dependencia=mysql_query("select dependencias.denominacion from dependencias, configuracion_compras where dependencias.iddependencia = configuracion_compras.iddependencia");
			$bus_dependencia=mysql_fetch_array($sql_dependencia);
			$nombremodulo = $bus_dependencia["denominacion"];
			}
		}else{
			$nombremodulo = "";
		}
		if ($_GET['idtipo']!="0") { 
			$filtro.=" AND (orden_pago.tipo ='".$_GET['idtipo']."') "; $dtipo=1;
			if ($dbeneficiario==1) $head=4; else $head=3;
		}else{
			/*if ($_GET['idmodulo']!="0") {
				$filtro.=" AND (tipos_documentos.compromete='no'
								and tipos_documentos.documento_compromete in (select idtipos_documentos from tipos_documentos
                              where (tipos_documentos.compromete = 'si'
                                     or (tipos_documentos.compromete = 'no' and tipos_documentos.causa = 'no')) 
									 and tipos_documentos.modulo like '%-".$_GET['idmodulo']."-%')) ";
				
			}*/
			if ($dbeneficiario==1) $head=4; else $head=3;
		}
		if ($_GET['idestado']!="0") {
			if ($_GET['idestado'] != 'procesadapagada'){
				if ($_GET['idestado'] == 'procesado'){
					$filtro.=" AND (orden_pago.estado='procesado') "; $destado=1;
					$estado='PROCESADA';	
				}
				if ($_GET['idestado'] == 'conformado'){
					$filtro.=" AND (orden_pago.estado='conformado') "; $destado=1;
					$estado='CONFORMADA';
				}
				if ($_GET['idestado'] == 'devuelto'){
					$filtro.=" AND (orden_pago.estado='devuelto') "; $destado=1;
					$estado='DEVUELTA';	
				}
				if ($_GET['idestado'] == 'anulado'){
					$filtro.=" AND (orden_pago.estado='anulado') "; $destado=1;
					$estado='ANULADA';	
				}
				if ($_GET['idestado'] == 'pagada'){
					$filtro.=" AND (orden_pago.estado='pagada') "; $destado=1;
					$estado='PAGADA';	
				}
			}else{
				$filtro.=" AND (orden_pago.estado='procesado' or orden_pago.estado='pagada') "; $destado=1;
				$estado='PROCESADA / PAGADA';
			}
			
		}
		
		

		if ($_GET["chkfinanciero"]!="si"){$head=1; }else{ $head=2;}


		if ($_GET['chksinafectacion']!="si") { 
			$filtro.=" AND (tipos_documentos.causa!='no' and tipos_documentos.paga='no') ";
		}
		
		if ($_GET['chkanticipo']!="si") { 
		
			$sql="SELECT
					orden_pago.idorden_pago,
					  orden_pago.codigo_referencia,
					  orden_pago.tipo,
					  orden_pago.numero_orden,
					  orden_pago.fecha_orden,
					  orden_pago.idcategoria_programatica,
					  orden_pago.exento,
					  orden_pago.sub_total,
					  orden_pago.total,
					  orden_pago.total_retenido,
					  orden_pago.total_a_pagar,
					  orden_pago.justificacion,
					  orden_pago.estado,
					  beneficiarios.nombre,
					  pagos_financieros.numero_cheque,
					  tipos_documentos.descripcion
					 
				FROM 
					orden_pago
					  INNER JOIN beneficiarios ON (orden_pago.idbeneficiarios=beneficiarios.idbeneficiarios)
					  INNER JOIN tipos_documentos ON (orden_pago.tipo=tipos_documentos.idtipos_documentos)
					  INNER JOIN fuente_financiamiento ON (orden_pago.idfuente_financiamiento = fuente_financiamiento.idfuente_financiamiento)
					  INNER JOIN pagos_financieros ON (orden_pago.idorden_pago=pagos_financieros.idorden_pago)
					  $filtro_inner
				WHERE 
					orden_pago.idorden_pago<>''
					$filtro 
				GROUP BY orden_pago.idorden_pago ORDER BY orden_pago.codigo_referencia";
				
			
		}else{
			$filtro.=" AND (orden_pago.anticipo ='si') "; 
			$sql="SELECT
					orden_pago.idorden_pago,
					  orden_pago.codigo_referencia,
					  orden_pago.tipo,
					  orden_pago.numero_orden,
					  orden_pago.fecha_orden,
					  orden_pago.idcategoria_programatica,
					  orden_pago.exento,
					  orden_pago.sub_total,
					  orden_pago.total,
					  orden_pago.total_retenido,
					  orden_pago.total_a_pagar,
					  orden_pago.justificacion,
					  orden_pago.estado,
					  beneficiarios.nombre,
					  pagos_financieros.numero_cheque,
					  tipos_documentos.descripcion,
					  orden_pago.anticipo
				FROM 
					orden_pago
					  INNER JOIN beneficiarios ON (orden_pago.idbeneficiarios=beneficiarios.idbeneficiarios)
					  INNER JOIN tipos_documentos ON (orden_pago.tipo=tipos_documentos.idtipos_documentos)
					  INNER JOIN fuente_financiamiento ON (orden_pago.idfuente_financiamiento = fuente_financiamiento.idfuente_financiamiento)
					  INNER JOIN pagos_financieros ON (orden_pago.idorden_pago=pagos_financieros.idorden_pago)
					  $filtro_inner
				WHERE 
					orden_pago.idorden_pago<>''
					$filtro 
				GROUP BY orden_pago.idorden_pago ORDER BY orden_pago.codigo_referencia";
		}
		
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//echo $sql;
		//---------------------------------------------------
		$total_bruto = 0;
		$total_deducciones = 0;
		$total_monto = 0;
		$sum_bruto = 0;
		$sum_deducciones = 0;
		$sum_total = 0;
		$nro_ordenes = 0;
		$monto=0;$sum_partidas_orden=0;$entro1 = 0;
		

		if ($head==1) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				
				
				
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				 
				if ($field['estado'] != "anulado") { 
					if ($field['exento']==0 and $field['sub_total']==0){
						$bruto = $field['total'];
						$deducciones = 0;
						$total = $field['total_a_pagar']; 
						$sum_bruto += $field['total']; 
						$sum_deducciones += 0;
						$sum_total += $field['total_a_pagar']; 
					}elseif ($field['exento']==0 and $field['sub_total']!=0){
						$bruto = $field['total'];
						$deducciones = $field['total_retenido'];
						$total = $field['total_a_pagar']; 
						$sum_bruto += $field['total']; 
						$sum_deducciones += $field['total_retenido'];
						$sum_total += $field['total_a_pagar']; 
					}elseif ($field['exento']!=0 and $field['sub_total']!=0 and $field['total_retenido']==0){
						$bruto = $field['sub_total'];
						$deducciones = $field['exento'];
						$total = $field['total_a_pagar']; 
						$sum_bruto += $field['sub_total']; 
						$sum_deducciones += $field['excento'];
						$sum_total += $field['total_a_pagar'];
					}elseif ($field['exento']!=0 and $field['sub_total']==0){
						$bruto = $field['exento'];
						$deducciones = 0;
						$total = $field['total_a_pagar']; 
						$sum_bruto += $field['exento']; 
						$sum_deducciones += 0;
						$sum_total += $field['total_a_pagar'];
					}elseif ($field['exento']!=0 and $field['sub_total']!=0 and $field['total_retenido']!=0){
						$bruto = $field['total'];
						$deducciones = $field['total_retenido'];
						$total = $field['total_a_pagar']; 
						$sum_bruto += $field['total']; 
						$sum_deducciones += $field['total_retenido'];
						$sum_total += $field['total_a_pagar'];
						
					}
				}
				
				//$estado=strtoupper($field['estado']);
				
				if ($i==0) filtro_orden_pago($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $estado, $nombremodulo, $fuente);
				
				//$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); 
				//$pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); 
					$pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->Ln(1);
				$pdf->SetAligns(array('L', 'C', 'L', 'R', 'R', 'R'));
				$pdf->SetWidths(array(25, 15, 95, 25, 25, 25));
				$pdf->Row(array($field['numero_orden'], $fecha, utf8_decode($field['nombre']), 
									number_format($bruto, 2, ',', '.'), 
									number_format($deducciones, 2, ',', '.'),
									number_format($total, 2, ',', '.'))); 
				$nro_ordenes++;

				if ($mostrarConcepto == 'si'){
					$pdf->Ln(1);
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); 
					$pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 5);
					$pdf->SetAligns(array('L', 'L', 'J', 'L', 'L'));
					$pdf->SetWidths(array(25, 15, 95, 20, 20));
					$pdf->Row(array('', 'CONCEPTO:', $field['justificacion'])); 
					$y=$pdf->GetY(); 
					$entro1 = 1;
				}

				if ($mostrarPartidas == 'si'){
					$pdf->Ln(1);
					if ($_GET['idcategoria'] == ''){
						$sql_par = "select * from partidas_orden_pago where idorden_pago = '".$field["idorden_pago"]."'";
					}else{
						$sql_par ="select * from partidas_orden_pago,maestro_presupuesto 
															where partidas_orden_pago.idorden_pago = '".$field["idorden_pago"]."'
															and maestro_presupuesto.idRegistro = partidas_orden_pago.idmaestro_presupuesto
															and maestro_presupuesto.idcategoria_programatica = '".$_GET['idcategoria']."'";
					}
					$sql_partidas = mysql_query($sql_par);
					while ($bus_partidas = mysql_fetch_array($sql_partidas)){
						$sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '".$bus_partidas['idmaestro_presupuesto']."'");
						$bus_maestro = mysql_fetch_array($sql_maestro);
						$sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_maestro['idclasificador_presupuestario']."'");
						$bus_clasificador = mysql_fetch_array($sql_clasificador);
						$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); 
						$pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
						$pdf->SetAligns(array('L', 'L', 'L', 'R', 'R'));
						$pdf->SetWidths(array(40, 15, 55, 25, 30));
						$codigopartida = $bus_clasificador['partida']."-".$bus_clasificador['generica']."-".$bus_clasificador['especifica']."-".$bus_clasificador['sub_especifica'];
						$denominacionpartida = utf8_decode($bus_clasificador['denominacion']);
						$pdf->Row(array('', $codigopartida, $denominacionpartida, number_format($bus_partidas['monto'], 2, ',', '.'), '')); 
						$monto += $bus_partidas['monto'];
						$sum_partidas_orden += $bus_partidas['monto'];
						$y=$pdf->GetY(); 
						if ($y>257){ 
							filtro_orden_pago($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", "", $nombremodulo, $fuente); 
							$pdf->Ln(1);
						}
					}
					$pdf->Ln(1);
					$bruto2 = floatval($bruto);
					$sum_partidas_orden2 = floatval($sum_partidas_orden);
					//$resultado = floatval(number_format($sum_partidas_orden2,2, ',', '.') - number_format($bruto2,2, ',', '.'));
					$resultado = $sum_partidas_orden2 - $bruto2;
					$resultado2 = number_format($resultado,2);
					//$resultado = $sum_partidas_orden2 - $bruto2;
					if($resultado != 0 and $resultado > 0){
						$pdf->Ln(1);
						$punto=$pdf->GetY();
						$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
						$pdf->SetLineWidth(0.8);
						$pdf->Line(2, $punto, 212, $punto);
						$pdf->Ln(2);
						$pdf->SetAligns(array('R','R'));
						$pdf->SetWidths(array(140,40));
						$pdf->SetFont('Arial', 'B', 12);
						$pdf->Row(array('Diferencia entre Total Partidas y Monto Bruto de la Orden', number_format(($sum_partidas_orden2-$bruto2), 2, ',', '.')));
					}
					$entro1 = 1;
				}
				if($entro1 == 1){
					$pdf->Ln(1);
					$punto=$pdf->GetY();
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetLineWidth(0.3);
					$pdf->Line(2, $punto, 212, $punto);
				}
				$sum_partidas_orden=0;
				$y=$pdf->GetY(); if ($y>265) filtro_orden_pago($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", "", $nombremodulo, $fuente);
			}

			$pdf->Ln(1);
			$punto=$pdf->GetY();
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetLineWidth(0.7);
			$pdf->Line(2, $punto, 212, $punto);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); 
			$pdf->SetTextColor(0, 0, 0); 
			$pdf->Ln(2);
			$pdf->SetAligns(array('L', 'C', 'L', 'R', 'R', 'R'));
			$pdf->SetWidths(array(25, 15, 95, 25, 25, 25));
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('','','', number_format($sum_bruto, 2, ',', '.'), 
								number_format($sum_deducciones, 2, ',', '.'), 
								number_format($sum_total, 2, ',', '.')));
			$pdf->Ln(6);
			$pdf->SetAligns(array('L', 'C', 'L', 'R', 'R', 'R'));
			$pdf->SetWidths(array(45, 25, 95, 25, 25, 25));
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('Numero de Ordenes: '.$nro_ordenes, '','','',''));

			if ($mostrarPartidas == 'si'){
				$pdf->Ln(2);
				$pdf->SetAligns(array('L', 'R', 'L', 'R', 'R', 'R'));
				$pdf->SetWidths(array(45, 25, 95, 25, 25, 25));
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Row(array('Sumatoria de las partidas: ', number_format($monto, 2, ',', '.'),'','',''));
			}

		}
		//----------------------------------------------------
		elseif ($head==2) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				
				
				
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				 
				if ($field['estado'] != "anulado") { 
					if ($field['exento']==0 and $field['sub_total']==0){
						$bruto = $field['total'];
						$deducciones = 0;
						$total = $field['total_a_pagar']; 
						$sum_bruto += $field['total']; 
						$sum_deducciones += 0;
						$sum_total += $field['total_a_pagar']; 
					}elseif ($field['exento']==0 and $field['sub_total']!=0){
						$bruto = $field['total'];
						$deducciones = $field['total_retenido'];
						$total = $field['total_a_pagar']; 
						$sum_bruto += $field['total']; 
						$sum_deducciones += $field['total_retenido'];
						$sum_total += $field['total_a_pagar']; 
					}elseif ($field['exento']!=0 and $field['sub_total']!=0 and $field['total_retenido']==0){
						$bruto = $field['sub_total'];
						$deducciones = $field['exento'];
						$total = $field['total_a_pagar']; 
						$sum_bruto += $field['sub_total']; 
						$sum_deducciones += $field['excento'];
						$sum_total += $field['total_a_pagar'];
					}elseif ($field['exento']!=0 and $field['sub_total']==0){
						$bruto = $field['exento'];
						$deducciones = 0;
						$total = $field['total_a_pagar']; 
						$sum_bruto += $field['exento']; 
						$sum_deducciones += 0;
						$sum_total += $field['total_a_pagar'];
					}elseif ($field['exento']!=0 and $field['sub_total']!=0 and $field['total_retenido']!=0){
						$bruto = $field['total'];
						$deducciones = $field['total_retenido'];
						$total = $field['total_a_pagar']; 
						$sum_bruto += $field['total']; 
						$sum_deducciones += $field['total_retenido'];
						$sum_total += $field['total_a_pagar'];
						
					}
				}
				
				
				
				if ($i==0) filtro_orden_pago($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $estado, $nombremodulo, $fuente);
				
				//$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); 
				//$pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); 
					$pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->Ln(1);
				$pdf->SetAligns(array('L', 'C', 'L', 'C', 'R', 'R', 'R'));
				$pdf->SetWidths(array(25, 15, 75, 20, 25, 25, 25));
				$pdf->Row(array($field['numero_orden'], $fecha, utf8_decode($field['nombre']), $field['numero_cheque'],
									number_format($bruto, 2, ',', '.'), 
									number_format($deducciones, 2, ',', '.'),
									number_format($total, 2, ',', '.'))); 
				$nro_ordenes++;

				if ($mostrarConcepto == 'si'){
					$pdf->Ln(1);
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); 
					$pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 5);
					$pdf->SetAligns(array('L', 'L', 'J', 'L', 'L'));
					$pdf->SetWidths(array(25, 15, 95, 20, 20));
					$pdf->Row(array('', 'CONCEPTO:', $field['justificacion'])); 
					$y=$pdf->GetY(); 
					$entro1 = 1;
				}

				if ($mostrarPartidas == 'si'){
					$pdf->Ln(1);
					if ($_GET['idcategoria'] == ''){
						$sql_par = "select * from partidas_orden_pago where idorden_pago = '".$field["idorden_pago"]."'";
					}else{
						$sql_par ="select * from partidas_orden_pago,maestro_presupuesto 
															where partidas_orden_pago.idorden_pago = '".$field["idorden_pago"]."'
															and maestro_presupuesto.idRegistro = partidas_orden_pago.idmaestro_presupuesto
															and maestro_presupuesto.idcategoria_programatica = '".$_GET['idcategoria']."'";
					}
					$sql_partidas = mysql_query($sql_par);
					while ($bus_partidas = mysql_fetch_array($sql_partidas)){
						$sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '".$bus_partidas['idmaestro_presupuesto']."'");
						$bus_maestro = mysql_fetch_array($sql_maestro);
						$sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_maestro['idclasificador_presupuestario']."'");
						$bus_clasificador = mysql_fetch_array($sql_clasificador);
						$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); 
						$pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
						$pdf->SetAligns(array('L', 'L', 'L', 'R', 'R'));
						$pdf->SetWidths(array(40, 15, 55, 25, 30));
						$codigopartida = $bus_clasificador['partida']."-".$bus_clasificador['generica']."-".$bus_clasificador['especifica']."-".$bus_clasificador['sub_especifica'];
						$denominacionpartida = utf8_decode($bus_clasificador['denominacion']);
						$pdf->Row(array('', $codigopartida, $denominacionpartida, number_format($bus_partidas['monto'], 2, ',', '.'), '')); 
						$monto += $bus_partidas['monto'];
						$sum_partidas_orden += $bus_partidas['monto'];
						$y=$pdf->GetY(); 
						if ($y>257){ 
							filtro_orden_pago($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", "", $nombremodulo, $fuente); 
							$pdf->Ln(1);
						}
					}
					$pdf->Ln(1);
					$bruto2 = floatval($bruto);
					$sum_partidas_orden2 = floatval($sum_partidas_orden);
					//$resultado = floatval(number_format($sum_partidas_orden2,2, ',', '.') - number_format($bruto2,2, ',', '.'));
					$resultado = $sum_partidas_orden2 - $bruto2;
					$resultado2 = number_format($resultado,2);
					//$resultado = $sum_partidas_orden2 - $bruto2;
					if($resultado != 0 and $resultado > 0){
						$pdf->Ln(1);
						$punto=$pdf->GetY();
						$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
						$pdf->SetLineWidth(0.8);
						$pdf->Line(2, $punto, 212, $punto);
						$pdf->Ln(2);
						$pdf->SetAligns(array('R','R'));
						$pdf->SetWidths(array(140,40));
						$pdf->SetFont('Arial', 'B', 12);
						$pdf->Row(array('Diferencia entre Total Partidas y Monto Bruto de la Orden', number_format(($sum_partidas_orden2-$bruto2), 2, ',', '.')));
					}
					$entro1 = 1;
				}
				if($entro1 == 1){
					$pdf->Ln(1);
					$punto=$pdf->GetY();
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetLineWidth(0.3);
					$pdf->Line(2, $punto, 212, $punto);
				}
				$sum_partidas_orden=0;
				$y=$pdf->GetY(); if ($y>265) filtro_orden_pago($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", "", $nombremodulo, $fuente);
			}

			$pdf->Ln(1);
			$punto=$pdf->GetY();
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetLineWidth(0.7);
			$pdf->Line(2, $punto, 212, $punto);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); 
			$pdf->SetTextColor(0, 0, 0); 
			$pdf->Ln(2);
			$pdf->SetAligns(array('L', 'C', 'L', 'R', 'R', 'R'));
			$pdf->SetWidths(array(25, 15, 95, 25, 25, 25));
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('','','', number_format($sum_bruto, 2, ',', '.'), 
								number_format($sum_deducciones, 2, ',', '.'), 
								number_format($sum_total, 2, ',', '.')));
			$pdf->Ln(6);
			$pdf->SetAligns(array('L', 'C', 'L', 'R', 'R', 'R'));
			$pdf->SetWidths(array(45, 25, 95, 25, 25, 25));
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array('Numero de Ordenes: '.$nro_ordenes, '','','',''));

			if ($mostrarPartidas == 'si'){
				$pdf->Ln(2);
				$pdf->SetAligns(array('L', 'R', 'L', 'R', 'R', 'R'));
				$pdf->SetWidths(array(45, 25, 95, 25, 25, 25));
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Row(array('Sumatoria de las partidas: ', number_format($monto, 2, ',', '.'),'','',''));
			}
		}
		
		break;
	
	//	(Modulo) Orden de Pago...
	case "ordenpago":
		$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->SetTopMargin(0.5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 1.5);
		$pag=0;
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
					 orden_pago.anio,
					 beneficiarios.nombre,
					 tipos_documentos.documento_compromete,
					 tipos_documentos.descripcion AS TipoDocumento,
					 (SELECT td.modulo FROM tipos_documentos td WHERE td.idtipos_documentos=tipos_documentos.documento_compromete) AS modulo, 
					 tipos_documentos.idtipos_documentos,
					 tipo_presupuesto.denominacion AS TipoPresupuesto, 
					 fuente_financiamiento.denominacion AS FuenteFinanciamiento
				FROM 
					 orden_pago, 
					 beneficiarios,
					 tipos_documentos,
					 tipo_presupuesto,
					 fuente_financiamiento
				WHERE 
					 (orden_pago.idorden_pago='".$_GET['idorden_pago']."') 
					 AND (orden_pago.idbeneficiarios=beneficiarios.idbeneficiarios) 
					 AND (orden_pago.tipo=tipos_documentos.idtipos_documentos)
					 AND (orden_pago.idtipo_presupuesto=tipo_presupuesto.idtipo_presupuesto)
					 AND (orden_pago.idfuente_financiamiento=fuente_financiamiento.idfuente_financiamiento)"or die (mysql_error());
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {
			$field=mysql_fetch_array($query);
			$idtipo_documento=$field['idtipos_documentos'];
			$tipo_documento=$field['TipoDocumento'];
			$numero=$field['numero_orden'];
			$anio=$field['anio']; 
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
			$beneficiario=$field['nombre'];
			$tpresupuesto=$field['TipoPresupuesto'];
			$ffinanciamiento=$field['FuenteFinanciamiento'];
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
						 (partidas_orden_pago.idorden_pago='".$_GET['idorden_pago']."' AND 
						 partidas_orden_pago.idmaestro_presupuesto=maestro_presupuesto.idRegistro AND 
						 maestro_presupuesto.idtipo_presupuesto=tipo_presupuesto.idtipo_presupuesto AND 
						 maestro_presupuesto.idfuente_financiamiento=fuente_financiamiento.idfuente_financiamiento)";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			$sql_tiene_retencion = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago =".$_GET['idorden_pago']."");
			$num_tiene_retencion = mysql_num_rows($sql_tiene_retencion);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				//$anio=$field['anio'];
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
		$pdf->Cell(80, 5, 'TIPO DE PRESUPUESTO:  '.utf8_decode($tpresupuesto), 0, 0, 'L');
		$pdf->Cell(105, 5, 'FUENTE DE FINANCIAMIENTO:  '.utf8_decode($ffinanciamiento), 0, 0, 'L');
		
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
		$sql = "SELECT
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
				WHERE relacion_pago_compromisos.idorden_pago='".$idorden_pago."'
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
					$porcentaje=number_format($field_retenciones['porcentaje_aplicado']/$field_retenciones['divisor'], 2, ',', '.');
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
						pop.idorden_pago = '".$_GET['idorden_pago']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		
		//	NO TENGO NI IDEA PARA QUE SIRVE ESTA CONSULTA....
		/*$sql_consulta = mysql_query("select * from orden_pago,
													categoria_programatica
													where 
											orden_pago.idorden_pago = '".$_GET["idorden_pago"]."'
											and categoria_programatica.idcategoria_programatica = orden_pago.idcategoria_programatica");
		$bus_consulta = mysql_fetch_array($sql_consulta);*/
		//	-------------------------------------------------
		
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
				$field=mysql_fetch_array($query); //for($k=0;$k<5;$k++){
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

			$sql_asiento_contable = mysql_query("select * from asiento_contable where iddocumento = '".$_GET["idorden_pago"]."'
																				and tipo_movimiento = 'causado'")or die(" siete ".mysql_error());
			if (mysql_num_rows($sql_asiento_contable)>0){
				$bus_asiento_contable = mysql_fetch_array($sql_asiento_contable);
				$sql_cuentas_asiento = mysql_query("select * from cuentas_asiento_contable where idasiento_contable ='".$bus_asiento_contable["idasiento_contable"]."'");
				$num_cuentas_asiento = mysql_num_rows($sql_cuentas_asiento);
				
				if ($num_cuentas_asiento <=2 and $y<230){
					$y=$pdf->GetY()+5;
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
				}else{
					$pag++; 
					ordenpago_anexo($pdf, $numero, $fecha, $pag, $idtipo_documento);
					$y=$pdf->GetY()+5;
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
			
			$sql_asiento_contable = mysql_query("select * from asiento_contable where iddocumento = '".$_GET["idorden_pago"]."'
																				and tipo_movimiento = 'causado'")or die(" siete ".mysql_error());
			if (mysql_num_rows($sql_asiento_contable)>0){
				$bus_asiento_contable = mysql_fetch_array($sql_asiento_contable);
				$sql_cuentas_asiento = mysql_query("select * from cuentas_asiento_contable where idasiento_contable ='".$bus_asiento_contable["idasiento_contable"]."'");
				$num_cuentas_asiento = mysql_num_rows($sql_cuentas_asiento);
				
				if ($num_cuentas_asiento <=2){
					$y=$pdf->GetY()+2;
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
					 orden_compra_servicio.fecha_factura  as f_factura,
					 orden_compra_servicio.nro_factura  as n_factura, 
					 orden_compra_servicio.nro_control  as n_control,
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
					 LEFT OUTER JOIN relacion_orden_pago_retencion ON (relacion_orden_pago_retencion.idorden_pago=orden_pago.idorden_pago) 
					 LEFT OUTER JOIN retenciones ON (orden_compra_servicio.idorden_compra_servicio=retenciones.iddocumento) 
				WHERE relacion_pago_compromisos.idorden_pago='".$_GET['idorden_pago']."' 
				GROUP BY relacion_pago_compromisos.idorden_compra_servicio
				ORDER BY orden_compra_servicio.codigo_referencia";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {
			$pag++;
			ordenpago_compromisos_cancelados($pdf, $numero, $fecha, $pag, $idtipo_documento, $modulo);
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query);
				
				if($field["fecha_factura"] == ""){
					$f_factura = $field["f_factura"];
				}else{
					$f_factura = $field["fecha_factura"];
				}
				list($a, $m, $d)=SPLIT( '[/.-]', $f_factura); $fecha_factura=$d."/".$m."/".$a; if ($fecha_factura=="//") $fecha_factura="";
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
				if($field["numero_factura"] == ""){
					$n_factura = $field["n_factura"];
				}else{
					$n_factura = $field["numero_factura"];
				}
				
				if($field["numero_control"] == ""){
					$n_control = $field["n_control"];
				}else{
					$n_control = $field["numero_control"];
				}
				$pdf->Row(array($field['numero_orden'], $field["fecha_orden"], $n_factura, $n_control, $fecha_factura, $sub_total, $exento, $impuesto, $total, $retencion, $total_pagar));
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
		
	//	Ordenes de Pago por Financiamiento...
	case "ordenes_pago_por_financiamiento":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		$sql_fuente = mysql_query("select * from fuente_financiamiento where idfuente_financiamiento = '".$financiamiento."'");
		$field_fuente = mysql_fetch_array($sql_fuente);
		$ffinanciamiento = $field_fuente['denominacion'];
		//	---------------------------------------
		ordenes_pago_por_financiamiento($pdf, $estado, $ffinanciamiento);
		//	---------------------------------------
		$filtro = "";
		if ($anio_fiscal != "0") $filtro .= " AND op.anio = '".$anio_fiscal."' ";
		if ($tipo_presupuesto != "0") $filtro .= " AND op.idtipo_presupuesto = '".$tipo_presupuesto."' ";
		if ($financiamiento != "0") $filtro .= " AND op.idfuente_financiamiento = '".$financiamiento."' "; 
		if ($tipo != "0") $filtro .= " AND op.tipo = '".$tipo."' "; 
		if ($estado != "0") $filtro .= " AND op.estado = '".$estado."' "; 
		if ($desde != "") $filtro .= " AND op.fecha_orden >= '".$desde."'";
		if ($hasta != "") $filtro .= " AND op.fecha_orden <= '".$hasta."'";
		//	---------------------------------------
		$sql = "SELECT
					op.numero_orden,
					op.fecha_orden,
					b.nombre AS beneficiario,
					op.total,
					op.total_a_pagar,
					op.estado,
					td.compromete,
					td.causa
				FROM
					orden_pago op
					LEFT JOIN beneficiarios b ON (op.idbeneficiarios = b.idbeneficiarios)
					LEFT JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos)
				WHERE 1 $filtro";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a; 
			if ($field['estado'] != "anulado") $sum_total += $field['total'];
			$pdf->Row(array($field['numero_orden'], $fecha, utf8_decode($field['beneficiario']), strtoupper($field['estado']), number_format($field['total_a_pagar'], 2, ',', '.')));
			$linea=$pdf->GetY(); if ($linea>235) { ordenes_pago_por_financiamiento($pdf, $estado, $ffinanciamiento); } 
		}
		$pdf->SetFont('Arial', 'B', 9);
		if ($field['estado'] != "anulado") $pdf->Cell(205, 10, number_format($sum_total, 2, ',', '.'), 0, 1, 'R');		
		break;
				
	//	Sobregiro tributario...
	case "sobregiro_tributario":
		$pdf=new PDF_MC_Table('L', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		//	---------------------------------------
		sobregiro_tributario($pdf);
		$sobregiro = $cantidad_ut * $valor_ut;
		$sum_total_a_pagar = 0;
		//	---------------------------------------
		$sql = "SELECT 
 					orden_pago.idorden_pago, 
					orden_pago.codigo_referencia, 
					orden_pago.numero_orden, 
					orden_pago.fecha_orden, 
					orden_pago.idcategoria_programatica, 
					orden_pago.total, 
					orden_pago.total_a_pagar, 
					orden_pago.sub_total, 
					orden_pago.total_retenido, 
					orden_pago.justificacion, 
					categoria_programatica.codigo, 
					unidad_ejecutora.denominacion, 
					orden_pago.estado, 
					beneficiarios.nombre, 
					tipos_documentos.descripcion,
					pagos_financieros.numero_cheque
				FROM 
					orden_pago
					INNER JOIN beneficiarios ON (orden_pago.idbeneficiarios=beneficiarios.idbeneficiarios)
					INNER JOIN tipos_documentos ON (orden_pago.tipo=tipos_documentos.idtipos_documentos)
					LEFT JOIN pagos_financieros ON (orden_pago.idorden_pago=pagos_financieros.idorden_pago)
					LEFT JOIN categoria_programatica ON (orden_pago.idcategoria_programatica=categoria_programatica.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ON (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)
				WHERE orden_pago.total_a_pagar >= '".$sobregiro."'
				GROUP BY orden_pago.idorden_pago 
				ORDER BY orden_pago.codigo_referencia";
		$query = mysql_query($sql) or die($sql.mysql_error());
		while($field = mysql_fetch_array($query)) {
			$nro_ordenes++;
			$pdf->SetDrawColor(0, 0, 0); 
			$pdf->SetFillColor(255, 255, 255); 
			$pdf->SetTextColor(0, 0, 0); 
			$pdf->SetFont('Arial', '', 6);
			$pdf->SetAligns(array('C', 'C', 'C', 'C', 'L', 'L', 'R'));
			$pdf->SetWidths(array(20, 15, 20, 30, 60, 100, 20));
			$pdf->Row(array($field['numero_orden'],
							$fecha,
							$estado,
							utf8_decode($field['descripcion']),
							utf8_decode($field['nombre']),
							utf8_decode($field['justificacion']),
							number_format($field['total_a_pagar'], 2, ',', '.')));
			$y=$pdf->GetY(); if ($y>180) sobregiro_tributario($pdf);
			$sum_total_a_pagar += $field['total_a_pagar'];
		}
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetDrawColor(0, 0, 0); 
		$pdf->SetFillColor(255, 255, 255); 
		$pdf->SetTextColor(0, 0, 0); 
		$pdf->SetFont('Arial', '', 6);
		$pdf->Cell(265, 5, number_format($sum_total_a_pagar, 2, ',', '.'), 1, 1, 'R');
		break;
	
}
//	----------------------------------------
//	FIN CUERPO DE LOS REPORTES
//	----------------------------------------

$pdf->Output();
?>