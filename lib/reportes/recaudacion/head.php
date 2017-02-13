<?php

//	Contribuyentes (Datos Basicos)
function contribuyente($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 10, utf8_decode('DATOS BÁSICOS CONTRIBUYENTE'), 0, 1, 'C');
	$pdf->Ln(5);
	//	--------------------
	
}
function contribuyente1($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 10, utf8_decode('DATOS BÁSICOS CONTRIBUYENTE'), 0, 1, 'C');
	$pdf->Ln(5);
	//	--------------------
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', 'BU', 8);
	$pdf->Cell(195, 5, 'Datos Generales.', 0, 0, 'L', 1);
	$pdf->Ln(7);
	
}
function contribuyente2($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 10, utf8_decode('DATOS BÁSICOS CONTRIBUYENTE'), 0, 1, 'C');
	$pdf->Ln(5);
	//	--------------------
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', 'BU', 8);
	$pdf->Cell(195, 5, 'Socios de la Empresa.', 0, 0, 'L', 1);
	$pdf->Ln(7);
	//	socios
	$pdf->SetFillColor(235, 235, 235);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(65, 30, 55, 35));
	$pdf->SetHeight(array(6));
	$pdf->Cell(5, 5);
	$pdf->Row(array('Nombre',
					'C.I.',
					'Cargo',
					'Acciones'));
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetAligns(array('L', 'C', 'L', 'L'));
	$pdf->Ln(2);
}
function contribuyente3($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 10, utf8_decode('DATOS BÁSICOS CONTRIBUYENTE'), 0, 1, 'C');
	$pdf->Ln(5);
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(0, 0, 0);
	
	$pdf->SetFillColor(240, 240, 240); 
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(195, 5, 'ACTIVIDADES COMERCIALES', 0, 0, 'L', 1);
	$pdf->Ln(7);
	
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetAligns(array('C'));
	$pdf->SetWidths(array(185));
	$pdf->SetHeight(array(6));
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetAligns(array('L'));
}
function contribuyente4($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 10, utf8_decode('DATOS BÁSICOS CONTRIBUYENTE'), 0, 1, 'C');
	$pdf->Ln(5);
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(0, 0, 0);
	
	$pdf->SetFillColor(240, 240, 240); 
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(195, 5, 'REQUISITOS', 0, 0, 'L', 1);
	$pdf->Ln(7);
	
	$pdf->SetFillColor(235, 235, 235);
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetAligns(array('C', 'L', 'C'));
	$pdf->SetWidths(array(20, 120, 45));
	$pdf->SetHeight(array(6));
	$pdf->Cell(5, 5);
	$pdf->Row(array('Sel.', 'Denominacion', 'Fecha Vencimiento'));
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Ln(2);
}

//	Solicitud de Calculo
function solicitud_de_calculo($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 10, utf8_decode('SOLICITUD DE CALCULO'), 0, 1, 'C');
	$pdf->Ln(5);
	//	--------------------
	
}

//	Recaudacion
function recaudacion($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 10, utf8_decode('INGRESOS DIARIOS'), 0, 1, 'C');
	$pdf->Ln(5);
	//	--------------------
	
}
?>