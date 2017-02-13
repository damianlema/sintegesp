<?php
session_start();
//	Relación de Facturas por Rendición...
function relacion_facturas_por_rendicion($pdf, $numero, $fecha, $justificacion, $beneficiario) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 10, utf8_decode('Relación de Facturas por Rendición'), 0, 1, 'C');
	$pdf->Ln(5);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(30, 5, 'Nro. Orden:', 0, 0, 'L');
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(170, 5, $numero, 0, 1, 'L');
	//	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(30, 5, utf8_decode('Fecha Orden:'), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(170, 5, formatoFecha($fecha), 0, 1, 'L');
	//	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(30, 5, utf8_decode('Beneficiario:'), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(170, 5, utf8_decode($beneficiario), 0, 1, 'L');
	//	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(30, 5, utf8_decode('Concepto:'), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 10);
	$pdf->MultiCell(170, 5, utf8_decode($justificacion), 0, 'L');
	$pdf->Ln(5);
	//	--------------------
	relacion_facturas_por_rendicion_1($pdf);
}

function relacion_facturas_por_rendicion_1($pdf) {
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('L', 'L', 'C', 'R', 'R', 'R', 'R'));
	$pdf->SetWidths(array(25, 55, 20, 25, 25, 25, 25));
	$pdf->Row(array('Nro. Factura', 'Proveedor', 'Fecha', 'Base', 'Exento', 'Impuesto', 'Total'));
}

function relacion_facturas_por_rendicion_2($pdf) {
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('L', 'C', 'L', 'L', 'R', 'R'));
	$pdf->SetWidths(array(10, 25, 35, 70, 25, 25));
	$pdf->Cell(10); $pdf->Row(array('Item', 'Cant.', 'Unidad', utf8_decode('Descripción'), 'P.Unitario', 'Total'));
}

function relacion_facturas_por_rendicion_3($pdf) {
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('L', 'L', 'L', 'R'));
	$pdf->SetWidths(array(25, 25, 115, 25));
	$pdf->Cell(10); $pdf->Row(array('Cat. Prog.', 'Partida', utf8_decode('Descripción'), 'Total'));
}

function apertura_caja_chica($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo, $documento) {
	if ($numero=="") $numero="En Elaboracion";
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(27, 17); $pdf->Cell(40, 5, '', 0, 0, 'C');
	$pdf->Cell(27, 17, '', 0, 0, 'C'); 
	//
	$pdf->Cell(15, 5); $pdf->Cell(25, 5, '', 0, 0, 'L');
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(35, 5, utf8_decode('Número: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 5, $numero, 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(15, 5); $pdf->Cell(120, 5, '', 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(34, 5, 'Fecha: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $fecha=$d."/".$m."/".$a;
	$pdf->Cell(35, 5, $fecha, 0, 1, 'L');	 
	$pdf->SetFont('Arial', '',11);
	$pdf->Cell(169, 5, utf8_decode('Página: '), 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 5, $pag, 0, 1, 'L'); 
	/////////////////////////////
	$pdf->SetFont('Arial', 'B', 13);
	$pdf->Cell(200, 12, 'APERTURA DE CAJA CHICA', 0, 1, 'C');
	/////////////////////////////
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(80, 5, 'PROVEEDOR', 1, 0, 'C', 1);
	$pdf->Cell(90, 5, 'DESPACHAR A', 1, 0, 'C', 1);
	$pdf->Cell(35, 5, 'REQUISICION', 1, 0, 'C', 1);
	//
	list($a, $m, $d)=SPLIT( '[/.-]', $frequisicion); $frequisicion=$d."/".$m."/".$a;
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 9);	
	$h=8; $y=37; $l=4;
	$x1=10.00125; $w1=80; $pdf->Rect($x1-5, $y, $w1, $h); 
	$x2=90.00125; $w2=90; $pdf->Rect($x2-5, $y, $w2, $h);
	$x3=180.00125; $w3=18; $pdf->Rect($x3-5, $y, $w3, $h);
	$x4=198.00125; $w4=17; $pdf->Rect($x4-5, $y, $w4, $h);
	// 
	$nombre=substr ($nombre, 0, 39);
	$despachar=substr ($despachar, 0, 48);
	$unidad=substr ($unidad, 0, 48);
	$pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, utf8_decode($nombre), 0, 'L'); 
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x1-5, $y); $hf=getH($pdf, $l, $w1, $nombre); $pdf->Ln($hf); $pdf->MultiCell($w1, $l, 'Rif.: '.$rif, 0, 'L'); 
	$pdf->SetXY($x2-5, $y); $pdf->MultiCell($w2, $l, utf8_decode($despachar), 0, 'L'); 
	$hf=getH($pdf, $l, $w2, $despachar); $pdf->Ln($hf);	$pdf->SetXY($x2-5, $y+$hf); $pdf->MultiCell($w2, $l, $unidad, 0, 'L');
	$pdf->SetXY($x3-5, $y); $pdf->MultiCell($w3, $l, 'No.', 1, 'C'); $pdf->SetXY($x4-5, $y); $pdf->MultiCell($w4, $l, 'Fecha', 1, 'C');
	$pdf->Ln($l);
	$pdf->SetXY($x3-5, $y+$l); $pdf->MultiCell($w3, $l, $nrequisicion, 0, 'C'); $pdf->SetXY($x4-5, $y+$l); $pdf->MultiCell($w4, $l, $frequisicion, 0, 'C');
	/////////////////////////////
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 9); 
	$pdf->MultiCell(200, 4, utf8_decode('Concepto: '.$justificacion), 0, 1, 'J', 1);
	$pdf->Rect(5, 45, 205, 24); 
		
	$pdf->SetY(70);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Ln();
	/////////////////////////////
	$reversa = 'no';
	getFootOrdenCompra($pdf, $modulo, $tipo, $documento, $reversa);
}

//	Tipos de Caja Chica...
function tipos_caja_chica($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 10, utf8_decode('Tipos de Caja Chica'), 0, 1, 'C');
	$pdf->Ln(5);
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('L', 'L', 'L', 'R'));
	$pdf->SetWidths(array(80, 40, 40, 40));
	$pdf->Row(array(utf8_decode('Denominación'), utf8_decode('Resolución Nro.'), 'Gaceta Nro.', 'U.T. Aprobadas'));
	$pdf->SetFont('Arial', '', 8);
}

?>