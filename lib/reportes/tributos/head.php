<?php

//	Imprimir Retenciones...  
function emitir_retenciones($pdf, $numero_retencion, $fecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$sql_encabezado = mysql_query("select * from configuracion_tributos");
	$bus_encabezado = mysql_fetch_array($sql_encabezado);
	
	$pdf->SetXY(50, 7);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode($bus_encabezado["primera_linea"]), 0, 0, 'L'); 
	$pdf->SetXY(50, 13);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode($bus_encabezado["segunda_linea"]), 0, 0, 'L'); 
	$pdf->SetXY(50, 19);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode($bus_encabezado["tercera_linea"]), 0, 0, 'L'); 
	
	
	
	//********************************************************************************************
	$pdf->SetXY(190, 7);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode('COMPROBANTE Nº:'), 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(60, 6, $numero_retencion, 0, 0, 'L');
	$pdf->SetXY(190, 13);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode('FECHA:'), 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(60, 6, $fecha, 0, 0, 'L');
	$pdf->SetXY(190, 19);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode('PERIODO FISCAL:'), 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(60, 6, $periodo_fiscal, 0, 0, 'L');
	//	------------------------------------------
	$pdf->SetY(25); 
	$pdf->SetFont('Arial', 'B', 12); $pdf->Cell(270, 10, 'COMPROBANTE DE RETENCION DEL IMPUESTO AL VALOR AGREGADO (I.V.A)', 0, 1, 'C');
	$pdf->Ln(2);
	$pdf->SetY(40); 
	//	Imprimo el head de la primera pagina
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10); 
	$pdf->Cell(150, 5, 'NOMBRE O RAZON DEL AGENTE DE RETENCION:', 0, 0, 'L', 1);
	$pdf->Cell(80, 5, 'R.I.F. DEL AGENTE DE RETENCION', 0, 1, 'L', 1);
	$pdf->SetFont('Arial', '', 10); 
	$pdf->Cell(150, 5, utf8_decode($nombre_agente), 0, 0, 'L', 1);
	$pdf->Cell(80, 5, $rif_agente, 0, 1, 'L', 1);
	$pdf->Ln(3);
	//$pdf->Cell(40, 5, $periodo_fiscal, 0, 1, 'L', 1);
	$pdf->SetFont('Arial', 'B', 10); 
	$pdf->Cell(270, 5, 'DIRECCION FISCAL DEL AGENTE DE RETENCION', 0, 1, 'L', 1);
	$pdf->SetFont('Arial', '', 10); 
	$pdf->Cell(270, 5, utf8_decode($direccion_fiscal), 0, 1, 'L', 1);
	$pdf->Ln(3);
	$pdf->SetFont('Arial', 'B',10); 
	$pdf->Cell(150, 5, 'NOMBRE O RAZON SOCIAL DEL SUJETO RETENIDO', 0, 0, 'L', 1);
	$pdf->Cell(80, 5, 'R.I.F. DEL SUJETO RETENIDO', 0, 1, 'L', 1);
	$pdf->SetFont('Arial', '', 10); 
	$pdf->Cell(150, 5, utf8_decode($nombre_sujeto), 0, 0, 'L', 1);
	$pdf->Cell(80, 5, $rif_sujeto, 0, 1, 'L', 1);
	$pdf->Ln(2);
	//	-----------------------------------------------------------	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(8, 20, 20, 26, 17, 19, 6, 17, 25, 25, 25, 12, 25, 24));
	$pdf->Row(array(utf8_decode('Nº'), 'FECHA FACTURA', utf8_decode('NUMERO FACTURA'), utf8_decode('NUMERO CONTROL'), utf8_decode('Nº NOTA DEBITO'), utf8_decode('Nº NOTA CREDITO'), 'T. T.', utf8_decode('Nº FACT AFEC'), 'COMPRAS INC. I.V.A', 'COMPRAS EXENTAS', 'BASE IMPONIBLE', '% ALIC', 'I.V.A', 'I.V.A RETENIDO'));
	//	-----------------------------------------------------------	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(5, 175); $pdf->Cell(120, 6, 'AGENTE DE RETENCION, FIRMA Y SELLO', 1, 0, 'L'); $pdf->Cell(30, 6); $pdf->Cell(120, 6, 'SUJETO RETENIDO, FIRMA Y SELLO', 1, 0, 'L');
	$pdf->SetXY(5, 175); $pdf->MultiCell(120, 25, '', 1, 0, 'L'); $pdf->SetXY(155, 175); 
	$pdf->MultiCell(120, 25, '', 1, 0, 'L');
	$pdf->SetXY(75, 195); $pdf->Cell(50, 6, 'FECHA:               /              /', 0, 0, 'L');
	$pdf->SetXY(225, 195); $pdf->Cell(50, 6, 'FECHA:               /              /', 0, 0, 'L');
	//	-----------------------------------------------------------	
	$pdf->SetXY(5, 205);
	$pdf->SetFont('Arial', 'B', 10); 
	$pdf->Cell(270, 5, 'EL PRESENTE DOCUMENTO SE EMITE EN CUMPLIMIENTO DEL ART. 11 DEL DECRETO DE LEY DEL I.V.A.', 0, 1, 'L', 1);
	$pdf->SetXY(5, 88);
}

function emitir_retenciones_islr($pdf, $numero_retencion, $fecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	
	$sql_encabezado = mysql_query("select * from configuracion_tributos");
	$bus_encabezado = mysql_fetch_array($sql_encabezado);
	
	$pdf->SetXY(50, 7);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode($bus_encabezado["primera_linea"]), 0, 0, 'L'); 
	$pdf->SetXY(50, 13);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode($bus_encabezado["segunda_linea"]), 0, 0, 'L'); 
	$pdf->SetXY(50, 19);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode($bus_encabezado["tercera_linea"]), 0, 0, 'L'); 
	
	//	--------------------
	$pdf->SetXY(190, 7);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode('COMPROBANTE Nº:'), 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(60, 6, $numero_retencion, 0, 0, 'L');
	$pdf->SetXY(190, 13);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode('FECHA:'), 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(60, 6, $fecha, 0, 0, 'L');
	$pdf->SetXY(190, 19);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode('PERIODO FISCAL:'), 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(60, 6, $periodo_fiscal, 0, 0, 'L');
	//	------------------------------------------
	$pdf->SetY(25); 
	$pdf->SetFont('Arial', 'B', 12);	$pdf->Cell(270, 10, 'COMPROBANTE DE RETENCION DEL IMPUESTO SOBRE LA RENTA (I.S.R.L.)', 0, 1, 'C');
	$pdf->Ln(2);
	$pdf->SetY(40); 
	//	Imprimo el head de la primera pagina
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10); 
	$pdf->Cell(150, 5, 'NOMBRE O RAZON DEL AGENTE DE RETENCION:', 0, 0, 'L', 1);
	$pdf->Cell(80, 5, 'R.I.F. DEL AGENTE DE RETENCION', 0, 1, 'L', 1);
	$pdf->SetFont('Arial', '', 10); 
	$pdf->Cell(150, 5, utf8_decode($nombre_agente), 0, 0, 'L', 1);
	$pdf->Cell(80, 5, $rif_agente, 0, 1, 'L', 1);
	$pdf->Ln(3);
	//$pdf->Cell(40, 5, $periodo_fiscal, 0, 1, 'L', 1);
	$pdf->SetFont('Arial', 'B', 10); 
	$pdf->Cell(270, 5, 'DIRECCION FISCAL DEL AGENTE DE RETENCION', 0, 1, 'L', 1);
	$pdf->SetFont('Arial', '', 10); 
	$pdf->Cell(270, 5, utf8_decode($direccion_fiscal), 0, 1, 'L', 1);
	$pdf->Ln(3);
	$pdf->SetFont('Arial', 'B',10); 
	$pdf->Cell(150, 5, 'NOMBRE O RAZON SOCIAL DEL SUJETO RETENIDO', 0, 0, 'L', 1);
	$pdf->Cell(80, 5, 'R.I.F. DEL SUJETO RETENIDO', 0, 1, 'L', 1);
	$pdf->SetFont('Arial', '', 10); 
	$pdf->Cell(150, 5, utf8_decode($nombre_sujeto), 0, 0, 'L', 1);
	$pdf->Cell(80, 5, $rif_sujeto, 0, 1, 'L', 1);
	$pdf->Ln(2);
	//	-----------------------------------------------------------	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(10, 25, 22, 22, 20, 21, 10, 17, 35, 35, 18, 35));
	$pdf->Row(array(utf8_decode('Nº'), 'FECHA FACTURA', utf8_decode('NUMERO FACTURA'), utf8_decode('NUMERO CONTROL'), utf8_decode('Nº NOTA DEBITO'), utf8_decode('Nº NOTA CREDITO'), 'T. T.', utf8_decode('Nº FACT AFEC'), 'MONTO FACTURA', 'BASE IMPONIBLE', '% ALIC', 'I.S.L.R. RETENIDO'));
	//	-----------------------------------------------------------	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(5, 180); $pdf->Cell(120, 6, 'AGENTE DE RETENCION, FIRMA Y SELLO', 1, 0, 'L'); $pdf->Cell(30, 6); $pdf->Cell(120, 6, 'SUJETO RETENIDO, FIRMA Y SELLO', 1, 0, 'L');
	$pdf->SetXY(5, 180); $pdf->MultiCell(120, 30, '', 1, 0, 'L'); $pdf->SetXY(155, 180); $pdf->MultiCell(120, 30, '', 1, 0, 'L');
	$pdf->SetXY(75, 205); $pdf->Cell(50, 6, 'FECHA:               /              /', 0, 0, 'L');
	$pdf->SetXY(225, 205); $pdf->Cell(50, 6, 'FECHA:               /              /', 0, 0, 'L');
	//	-----------------------------------------------------------	
	$pdf->SetXY(5, 88);
}

function emitir_retenciones_uno($pdf, $numero_retencion, $fecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto, $justificacion, $direccion_sujeto, $total_contrato) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	
	$sql_encabezado = mysql_query("select * from configuracion_tributos");
	$bus_encabezado = mysql_fetch_array($sql_encabezado);
	
	$pdf->SetXY(50, 7);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode($bus_encabezado["primera_linea"]), 0, 0, 'L'); 
	$pdf->SetXY(50, 13);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode($bus_encabezado["segunda_linea"]), 0, 0, 'L'); 
	$pdf->SetXY(50, 19);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode($bus_encabezado["tercera_linea"]), 0, 0, 'L'); 
	
	//	--------------------
	$pdf->SetXY(190, 7);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode('COMPROBANTE Nº:'), 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(60, 6, $numero_retencion, 0, 0, 'L');
	$pdf->SetXY(190, 13);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode('FECHA:'), 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(60, 6, $fecha, 0, 0, 'L');
	$pdf->SetXY(190, 19);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode('PERIODO FISCAL:'), 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(60, 6, $periodo_fiscal, 0, 0, 'L');
	//	------------------------------------------
	$pdf->SetY(25); 
	$pdf->SetFont('Arial', 'B', 12);	$pdf->Cell(270, 10, 'COMPROBANTE DE RETENCION DEL UNO POR MIL', 0, 1, 'C');
	$pdf->Ln(1);
	$pdf->SetY(35); 
	//	Imprimo el head de la primera pagina
	$pdf->Rect(4, 35, 271, 20);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'BU', 10); $pdf->Cell(270, 5, 'AGENTE DE RETENCION:', 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'NOMBRE O RAZON SOCIAL:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); $pdf->Cell(170, 5, utf8_decode($nombre_agente), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'R.I.F.:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); $pdf->Cell(170, 5, utf8_decode($rif_agente), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'DOMICILIO FISCAL:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 8); $pdf->Cell(170, 5, utf8_decode(substr($direccion_fiscal, 0, 120)), 0, 1, 'L');
	$pdf->Ln(3);
	$pdf->Rect(4, 58, 271, 20);
	$pdf->SetFont('Arial', 'BU', 10); $pdf->Cell(270, 5, 'CONTRIBUYENTE:', 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'NOMBRE O RAZON SOCIAL:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); $pdf->Cell(170, 5, utf8_decode($nombre_sujeto), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'R.I.F.:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); $pdf->Cell(170, 5, utf8_decode($rif_sujeto), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'DOMICILIO FISCAL:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 8); $pdf->Cell(170, 5, utf8_decode(substr($direccion_sujeto, 0, 120)), 0, 1, 'L');
	$pdf->Ln(3);
	//$pdf->Rect(4, 86, 271, 20);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(70, 5, 'CONCEPTO DE LA OBRA O SERVICIO:', 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 8); 
	//$pdf->MultiCell(200, 4, utf8_decode($justificacion), 0, 'L');
	$pdf->MultiCell(200, 4, utf8_decode($justificacion), 0, 'J');
	$pdf->Ln(2);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(70, 5, 'MONTO TOTAL DEL CONTRATO:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); 
	$pdf->Cell(170, 5, number_format($total_contrato, 2, ',', '.'), 0, 1, 'L');
	$pdf->SetXY(5, 108);
	//	-----------------------------------------------------------	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(10, 30, 30, 40, 40, 40, 40, 40));
	$pdf->Row(array(utf8_decode('Nº'), utf8_decode('Nº ODEN DE PAGO'), 'FECHA', utf8_decode('% VALUACION ANTICIPO'), 'BASE IMPONIBLE', 'IMPUESTO A RETENER', 'DEDUCCIONES', 'TOTAL A ENTERAR'));
	//	-----------------------------------------------------------	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(5, 180); $pdf->Cell(120, 6, 'AGENTE DE RETENCION, FIRMA Y SELLO', 1, 0, 'L'); $pdf->Cell(30, 6); $pdf->Cell(120, 6, 'SUJETO RETENIDO, FIRMA Y SELLO', 1, 0, 'L');
	$pdf->SetXY(5, 180); $pdf->MultiCell(120, 30, '', 1, 0, 'L'); $pdf->SetXY(155, 180); $pdf->MultiCell(120, 30, '', 1, 0, 'L');
	$pdf->SetXY(75, 205); $pdf->Cell(50, 6, 'FECHA:               /              /', 0, 0, 'L');
	$pdf->SetXY(225, 205); $pdf->Cell(50, 6, 'FECHA:               /              /', 0, 0, 'L');
	//	-----------------------------------------------------------	
	$pdf->SetXY(5, 118);
}


function emitir_retenciones_uno_monagas($pdf, $numero_retencion, $fecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto, $justificacion, $direccion_sujeto, $total_contrato) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	
	$sql_encabezado = mysql_query("select * from configuracion_tributos");
	$bus_encabezado = mysql_fetch_array($sql_encabezado);
	
	$pdf->SetXY(50, 7);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode($bus_encabezado["primera_linea"]), 0, 0, 'L'); 
	$pdf->SetXY(50, 13);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode($bus_encabezado["segunda_linea"]), 0, 0, 'L'); 
	$pdf->SetXY(50, 19);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode($bus_encabezado["tercera_linea"]), 0, 0, 'L'); 
	
	//	--------------------
	$pdf->SetXY(190, 7);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode('COMPROBANTE Nº:'), 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(60, 6, $numero_retencion, 0, 0, 'L');
	$pdf->SetXY(190, 13);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode('FECHA:'), 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(60, 6, $fecha, 0, 0, 'L');
	$pdf->SetXY(190, 19);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode('PERIODO FISCAL:'), 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(60, 6, $periodo_fiscal, 0, 0, 'L');
	//	------------------------------------------
	$pdf->SetY(25); 
	$pdf->SetFont('Arial', 'B', 12);	$pdf->Cell(270, 10, 'COMPROBANTE DE RETENCION DEL UNO POR MIL', 0, 1, 'C');
	$pdf->Ln(1);
	$pdf->SetY(35); 
	//	Imprimo el head de la primera pagina
	$pdf->Rect(4, 35, 271, 20);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'BU', 10); $pdf->Cell(270, 5, 'AGENTE DE RETENCION:', 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'NOMBRE O RAZON SOCIAL:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); $pdf->Cell(170, 5, utf8_decode($nombre_agente), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'R.I.F.:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); $pdf->Cell(170, 5, utf8_decode($rif_agente), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'DOMICILIO FISCAL:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 8); $pdf->Cell(170, 5, utf8_decode(substr($direccion_fiscal, 0, 120)), 0, 1, 'L');
	$pdf->Ln(3);
	$pdf->Rect(4, 58, 271, 20);
	$pdf->SetFont('Arial', 'BU', 10); $pdf->Cell(270, 5, 'CONTRIBUYENTE:', 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'NOMBRE O RAZON SOCIAL:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); $pdf->Cell(170, 5, utf8_decode($nombre_sujeto), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'R.I.F.:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); $pdf->Cell(170, 5, utf8_decode($rif_sujeto), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'DOMICILIO FISCAL:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 8); $pdf->Cell(170, 5, utf8_decode(substr($direccion_sujeto, 0, 120)), 0, 1, 'L');
	$pdf->Ln(3);
	//$pdf->Rect(4, 86, 271, 20);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(70, 5, 'CONCEPTO DE LA OBRA O SERVICIO:', 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 8); 
	//$pdf->MultiCell(200, 4, utf8_decode($justificacion), 0, 'L');
	$pdf->MultiCell(200, 4, utf8_decode($justificacion), 0, 'J');
	$pdf->Ln(2);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(70, 5, 'MONTO TOTAL DEL CONTRATO:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); 
	$pdf->Cell(170, 5, number_format($total_contrato, 2, ',', '.'), 0, 1, 'L');
	$pdf->SetXY(5, 108);
	//	-----------------------------------------------------------	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(10, 30, 30, 40, 40, 40, 40, 40));
	$pdf->Row(array(utf8_decode('Nº'), utf8_decode('Nº ODEN DE PAGO'), 'FECHA', utf8_decode('% VALUACION ANTICIPO'), 'BASE IMPONIBLE', '% APLICADO', 'IMPUESTO A RETENER',  'TOTAL A ENTERAR'));
	//	-----------------------------------------------------------	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(5, 180); $pdf->Cell(120, 6, 'AGENTE DE RETENCION, FIRMA Y SELLO', 1, 0, 'L'); $pdf->Cell(30, 6); $pdf->Cell(120, 6, 'SUJETO RETENIDO, FIRMA Y SELLO', 1, 0, 'L');
	$pdf->SetXY(5, 180); $pdf->MultiCell(120, 30, '', 1, 0, 'L'); $pdf->SetXY(155, 180); $pdf->MultiCell(120, 30, '', 1, 0, 'L');
	$pdf->SetXY(75, 205); $pdf->Cell(50, 6, 'FECHA:               /              /', 0, 0, 'L');
	$pdf->SetXY(225, 205); $pdf->Cell(50, 6, 'FECHA:               /              /', 0, 0, 'L');
	//	-----------------------------------------------------------	
	$pdf->SetXY(5, 118);
}


function emitir_retenciones_municipal($pdf, $numero_retencion, $fecha, $nombre_agente, $rif_agente, $periodo_fiscal, $direccion_fiscal, $nombre_sujeto, $rif_sujeto, $justificacion, $direccion_sujeto, $total_contrato) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	
	$sql_encabezado = mysql_query("select * from configuracion_tributos");
	$bus_encabezado = mysql_fetch_array($sql_encabezado);
	
	$pdf->SetXY(50, 7);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode($bus_encabezado["primera_linea"]), 0, 0, 'L'); 
	$pdf->SetXY(50, 13);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode($bus_encabezado["segunda_linea"]), 0, 0, 'L'); 
	$pdf->SetXY(50, 19);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode($bus_encabezado["tercera_linea"]), 0, 0, 'L'); 
	
	//	--------------------
	$pdf->SetXY(190, 7);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode('COMPROBANTE Nº:'), 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(60, 6, $numero_retencion, 0, 0, 'L');
	$pdf->SetXY(190, 13);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode('FECHA:'), 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(60, 6, $fecha, 0, 0, 'L');
	$pdf->SetXY(190, 19);
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(40, 6, utf8_decode('PERIODO FISCAL:'), 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(60, 6, $periodo_fiscal, 0, 0, 'L');
	//	------------------------------------------
	$pdf->SetY(25); 
	$pdf->SetFont('Arial', 'B', 12);	$pdf->Cell(270, 10, 'COMPROBANTE DE RETENCION MUNICIPAL', 0, 1, 'C');
	$pdf->Ln(1);
	$pdf->SetY(35); 
	//	Imprimo el head de la primera pagina
	$pdf->Rect(4, 35, 271, 25);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'BU', 10); $pdf->Cell(270, 5, 'AGENTE DE RETENCION:', 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'NOMBRE O RAZON SOCIAL:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); $pdf->Cell(170, 5, utf8_decode($nombre_agente), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'CODIGO DEL AGENTE:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); $pdf->Cell(170, 5, 'A00204', 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'R.I.F.:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); $pdf->Cell(170, 5, utf8_decode($rif_agente), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'DOMICILIO FISCAL:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 8); $pdf->Cell(170, 5, utf8_decode(substr($direccion_fiscal, 0, 120)), 0, 1, 'L');
	$pdf->Ln(4);
	$pdf->Rect(4, 63, 271, 22);
	$pdf->SetFont('Arial', 'BU', 10); $pdf->Cell(270, 5, 'CONTRIBUYENTE:', 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'NOMBRE O RAZON SOCIAL:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); $pdf->Cell(170, 5, utf8_decode($nombre_sujeto), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'R.I.F.:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); $pdf->Cell(170, 5, utf8_decode($rif_sujeto), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(55, 5, 'DOMICILIO FISCAL:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 8); $pdf->Cell(170, 5, utf8_decode(substr($direccion_sujeto, 0, 120)), 0, 1, 'L');
	$pdf->Ln(2);
	//	-----------------------------------------------------------	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(10, 30, 30, 40, 35, 25, 50, 50));
	$pdf->Row(array(utf8_decode('Nº'), 'Nro. FACTURA', 'Nro. CONTROL','FECHA FACTURA', 'FECHA DE PAGO', 'ALICUOTA', 'MONTO BRUTO SIN IVA', 'MONTO RETENIDO'));
	//	-----------------------------------------------------------	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(5, 180); $pdf->Cell(120, 6, 'AGENTE DE RETENCION, FIRMA Y SELLO', 1, 0, 'L'); $pdf->Cell(30, 6); $pdf->Cell(120, 6, 'SUJETO RETENIDO, FIRMA Y SELLO', 1, 0, 'L');
	$pdf->SetXY(5, 180); $pdf->MultiCell(120, 30, '', 1, 0, 'L'); $pdf->SetXY(155, 180); $pdf->MultiCell(120, 30, '', 1, 0, 'L');
	$pdf->SetXY(75, 205); $pdf->Cell(50, 6, 'FECHA:               /              /', 0, 0, 'L');
	$pdf->SetXY(225, 205); $pdf->Cell(50, 6, 'FECHA:               /              /', 0, 0, 'L');
	//	-----------------------------------------------------------	
	$pdf->SetXY(5, 91);
}



//	Imprimir Retenciones...
function registro_retenciones($pdf, $pag, $fecha_retencion) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10); 
	$pdf->SetXY(160, 10); $pdf->Cell(20, 5, utf8_decode('Página:'), 0, 1, 'R'); 
	$pdf->SetFont('Arial', '', 10); 
	$pdf->SetXY(180, 10); $pdf->Cell(20, 5, $pag, 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10); 
	$pdf->SetXY(160, 15); $pdf->Cell(20, 5, utf8_decode('Fecha:'), 0, 1, 'R'); 
	$pdf->SetFont('Arial', '', 10); 
	$pdf->SetXY(180, 15); $pdf->Cell(20, 5, $fecha_retencion, 0, 1, 'L');
	//	-----------------------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(195, 10, 'Registro de Retenciones', 0, 1, 'C');
	$pdf->Ln(6);
}

function registro_retenciones_externas($pdf, $id_retencion) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(195, 10, 'Registro de Retenciones Externas', 0, 1, 'C');
	$pdf->Ln(6);
	//	----------------------------------------------------
	//	Obtengo los datos de la cabecera....
	$sql="SELECT 
				r.nro_retencion, 
				r.estado, 
				b.nombre AS beneficiario, 
				b.rif, 
				eg.nombre AS ente 
			FROM 
				retenciones r 
				INNER JOIN beneficiarios b ON (r.idbeneficiarios=b.idbeneficiarios) 
				INNER JOIN entes_gubernamentales eg ON (r.idente_gubernamental=eg.identes_gubernamentales) 
			WHERE 
				r.idretenciones='".$id_retencion."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) $field=mysql_fetch_array($query);	
	
	//	Imprimo los datos de la cabecera...
	$pdf->SetFont('Arial', '', 8);	$pdf->Cell(30, 6, utf8_decode('Nro. Retención: '), 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(170, 6, $field["nro_retencion"], 0, 1, 'L');	
	$pdf->SetFont('Arial', '', 8);	$pdf->Cell(30, 6, 'Estado: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(170, 6, strtoupper($field["estado"]), 0, 1, 'L');	
	$pdf->SetFont('Arial', '', 8);	$pdf->Cell(30, 6, 'Ente Gubernamental: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(170, 6, utf8_decode($field["ente"]), 0, 1, 'L');	
	$pdf->SetFont('Arial', '', 8);	$pdf->Cell(30, 6, 'Beneficiario: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(170, 6, utf8_decode($field["rif"].' - '.$field["beneficiario"]), 0, 1, 'L');
	$pdf->Ln(5);
	
	//	Imprimo el titulo de la tabla...
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(195, 10, utf8_decode('Relación de Retenciones Externas'), 0, 1, 'C');
	$pdf->Ln(3);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(20, 15, 20, 20, 15, 50, 15, 15, 25));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->Row(array('Nro. Orden', 'Fecha Orden', 'Nro. Factura', 'Nro. Control', 'Fecha Factura', utf8_decode('Tipo de Retención'), 'Sobre', '%', 'Monto Retenido'));
	$pdf->Ln(2);
}

//	Relacion de Retenciones...
function relacion_retenciones($pdf, $tipo_retencion, $periodo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(260, 10, utf8_decode('Relación de Retenciones'), 0, 1, 'C');
	$pdf->Ln(6);
	$pdf->SetFont('Arial', '', 10);	$pdf->Cell(32, 6, utf8_decode('Tipo de Retención: '), 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(228, 6, $tipo_retencion, 0, 1, 'L');
	if ($periodo!="") {
		$pdf->SetFont('Arial', '', 10);	$pdf->Cell(32, 6, 'Periodo: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(228, 6, $periodo, 0, 1, 'L');
	}
	$pdf->Ln(3);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); 
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(10, 4, 'Est', 1, 0, 'C', 1);
	$pdf->Cell(25, 4, 'Nro. Orden', 1, 0, 'C', 1);
	$pdf->Cell(17, 4, 'F.Orden', 1, 0, 'C', 1);
	$pdf->Cell(89, 4, 'Beneficiario', 1, 0, 'C', 1);
	$pdf->Cell(20, 4, 'Nro. Factura', 1, 0, 'C', 1);
	$pdf->Cell(20, 4, 'Nro. Control', 1, 0, 'C', 1);
	$pdf->Cell(17, 4, 'F.Factura', 1, 0, 'C', 1);
	$pdf->Cell(25, 4, 'Comprobante', 1, 0, 'C', 1);
	$pdf->Cell(17, 4, 'Fecha', 1, 0, 'C', 1);
	$pdf->Cell(25, 4, 'Total Retenido', 1, 1, 'C', 1);
}

//	Relacion por Beneficiarios...
function retenciones_beneficiario($pdf, $tipo_retencion, $periodo, $beneficiario) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(195, 10, utf8_decode('Relación de Retenciones por Beneficiario'), 0, 1, 'C');
	$pdf->Ln(6);
	$pdf->SetFont('Arial', '', 10);	$pdf->Cell(32, 6, 'Beneficiario: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(228, 6, utf8_decode($beneficiario), 0, 1, 'L');
	$pdf->SetFont('Arial', '', 10);	$pdf->Cell(32, 6, utf8_decode('Tipo de Retención: '), 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(228, 6, $tipo_retencion, 0, 1, 'L');
	if ($periodo!="") {
		$pdf->SetFont('Arial', '', 10);	$pdf->Cell(32, 6, 'Periodo: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(228, 6, $periodo, 0, 1, 'L');
	}
	$pdf->Ln(3);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); 
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(30, 4, 'Nro. Orden', 1, 0, 'C', 1);
	$pdf->Cell(25, 4, 'F.Orden', 1, 0, 'C', 1);
	$pdf->Cell(25, 4, 'Nro. Factura', 1, 0, 'C', 1);
	$pdf->Cell(25, 4, 'Nro. Control', 1, 0, 'C', 1);
	$pdf->Cell(25, 4, 'F.Factura', 1, 0, 'C', 1);
	$pdf->Cell(30, 4, 'Estado', 1, 0, 'C', 1);
	$pdf->Cell(30, 4, 'Total Retenido', 1, 1, 'C', 1);
	$pdf->Ln(2);
}

//	Libro de Compras (I.V.A)...
function libro_compras_iva($pdf, $periodo, $desde, $hasta) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(260, 10, 'Libro de Compras (I.V.A)', 0, 1, 'C');
	$pdf->Ln(3);
	
	list($a, $m, $d)=SPLIT( '[/.-]', $desde); $desde=$d."/".$m."/".$a;
	list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $hasta=$d."/".$m."/".$a;
	
	$pdf->SetFont('Arial', '', 8);	$pdf->Cell(15, 6, 'PERIODO: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(25, 6, $periodo, 0, 0, 'L');
	$pdf->SetFont('Arial', '', 8);	$pdf->Cell(12, 6, 'DESDE: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(25, 6, $desde, 0, 0, 'L');
	$pdf->SetFont('Arial', '', 8);	$pdf->Cell(12, 6, 'HASTA: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(25, 6, $hasta, 0, 1, 'L');
			
	$pdf->Ln(3);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 5);
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(10, 15, 20, 55, 25, 15, 15, 10, 20, 20, 20, 10, 20, 20));
	$pdf->Row(array(utf8_decode('Nº Oper'), 'Fecha de la Factura', 'R.I.F', utf8_decode('Nombre o Razón Social'), utf8_decode('Nº Comprobante'), utf8_decode('Nº de Factura'), utf8_decode('Nº de Control'), 'Tipo de Transac.', 'Total Compras Incluyendo el I.V.A', utf8_decode('Compras sin Derecho a Crédito I.V.A'), 'Base Imponible', '% Alicuota', 'Impuesto I.V.A', 'I.V.A Retenido'));
	//	-----------------------------------------------------------
	$pdf->Ln(1);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(100, 100, 100); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetAligns(array('C', 'C', 'C', 'L', 'C', 'L', 'L', 'C', 'R', 'C', 'R', 'C', 'R', 'R'));
	$pdf->SetWidths(array(10, 15, 20, 55, 25, 15, 15, 10, 20, 20, 20, 10, 20, 20));
}


//	Libro mensual (1x1000)...
function libro_compras_uno($pdf, $periodo, $desde, $hasta, $nombre_agente, $rif_agente) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$titulo = utf8_decode('Relación Mensual del Impuesto 1x1000');
	$pdf->Cell(260, 10, $titulo, 0, 1, 'C');
	$pdf->Ln(3);
	
	list($a, $m, $d)=SPLIT( '[/.-]', $desde); $desde=$d."/".$m."/".$a;
	list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $hasta=$d."/".$m."/".$a;
	
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(55, 5, 'NOMBRE O RAZON SOCIAL:', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(170, 5, utf8_decode($nombre_agente), 0, 1, 'L');
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(55, 5, 'R.I.F.:', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(170, 5, utf8_decode($rif_agente), 0, 1, 'L');
	$pdf->SetFont('Arial', '', 8);	$pdf->Cell(15, 6, 'PERIODO: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(25, 6, $periodo, 0, 0, 'L');
	$pdf->SetFont('Arial', '', 8);	$pdf->Cell(12, 6, 'DESDE: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(25, 6, $desde, 0, 0, 'L');
	$pdf->SetFont('Arial', '', 8);	$pdf->Cell(12, 6, 'HASTA: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(25, 6, $hasta, 0, 1, 'L');
			
	$pdf->Ln(3);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 5);
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(10, 45, 15, 50, 15, 11, 14, 11, 11,20, 15, 15, 15, 15, 15));
	$pdf->Row(array(utf8_decode('Nº Oper'), 'Nombre del Contribuyente', 'R.I.F', utf8_decode('Concepto de Operación'), utf8_decode('Monto Total del Contrato'), utf8_decode('Tipo de Operación'), utf8_decode('Fecha de Retención'), 'Fecha de Entera', 'Nro. Deposito', utf8_decode('Nro. Orden Pago'), 'Monto de la Orden de Pago', utf8_decode('Monto de la Operación'), 'Base Imponible', 'Impuesto a Retener','Deducciones o Monto a Enterar' ));
	//	-----------------------------------------------------------
	$pdf->Ln(1);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(100, 100, 100); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetAligns(array('C', 'L', 'L', 'L', 'R', 'L', 'C', 'C', 'L', 'L', 'R', 'R', 'R', 'R', 'R'));
	$pdf->SetWidths(array(10, 45, 15, 50, 15, 11, 14, 11, 11, 20, 15, 15, 15, 15, 15));
}


//	Retenciones Aplicadas...
function retenciones_aplicadas($pdf, $periodo, $desde, $hasta, $tipo_retencion, $nombre_banco, $numero_cuenta) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(195, 10, utf8_decode('Relación de Retenciones Aplicadas'), 0, 1, 'C');
	$pdf->Ln(3);
	
	list($a, $m, $d)=SPLIT( '[/.-]', $desde); $desde=$d."/".$m."/".$a;
	list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $hasta=$d."/".$m."/".$a;
	
	$pdf->SetFont('Arial', '', 8);	$pdf->Cell(15, 6, 'PERIODO: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(25, 6, $periodo, 0, 0, 'L');
	$pdf->SetFont('Arial', '', 8);	$pdf->Cell(12, 6, 'DESDE: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(25, 6, $desde, 0, 0, 'L');
	$pdf->SetFont('Arial', '', 8);	$pdf->Cell(12, 6, 'HASTA: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(25, 6, $hasta, 0, 1, 'L');
	
	$pdf->SetFont('Arial', '', 8);	$pdf->Cell(31, 6, utf8_decode('TIPO DE RETENCIÓN: '), 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(100, 6, $tipo_retencion, 0, 1, 'L');
	
	if ($nombre_banco != ''){
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(31, 6, utf8_decode($nombre_banco), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(31, 6, utf8_decode($numero_cuenta), 0, 1, 'L');
	}
	$pdf->Ln(3);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(30, 25, 25, 70, 20, 25));
	$pdf->Row(array('Nro. Comprobante', 'Nro. Orden', 'R.I.F', 'Beneficiario', 'Fecha', 'Monto'));
	//	-----------------------------------------------------------
	$pdf->Ln(1);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(100, 100, 100); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetAligns(array('C', 'L', 'C', 'L', 'C', 'R'));
	$pdf->SetWidths(array(30, 25, 25, 70, 20, 25));
}

//	Anexos Orden de Pago...
function anexos_orden_pago_relacion($pdf, $numero, $fecha, $pag) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage(); 
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
	$pdf->Cell(200, 5, utf8_decode('Relación de Retenciones'), 0, 1, 'C');
	$pdf->Cell(200, 7, '', 0, 1, 'C');
	$pdf->Ln(5);		
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(30, 5, utf8_decode('Código'), 1, 0, 'C', 1);
	$pdf->Cell(135, 5, utf8_decode('Denominación'), 1, 0, 'C', 1);
	$pdf->Cell(40, 5, 'Total', 1, 1, 'C', 1);
	$pdf->Ln(2);
}

?>