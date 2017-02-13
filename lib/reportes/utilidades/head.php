<?php
function auditoria($pdf, $fecha_desde, $fecha_hasta, $usuario) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Auditora del Sistema', 0, 1, 'C');
	//-----------------------	
	if ($usuario!="") {
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(15, 4, 'Usuario: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(180, 4, $usuario, 0, 1, 'L');
	}
	if ($fecha_desde!="") {
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(15, 4, 'Desde: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(20, 4, $fecha_desde, 0, 0, 'L');
	}
	if ($fecha_hasta!="") {
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(15, 4, 'Hasta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(20, 4, $fecha_hasta, 0, 1, 'L');
	}
	$pdf->Ln();
	//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(15, 5, 'Nro.', 1, 0, 'C', 1);
	$pdf->Cell(70, 5, 'Tipo', 1, 0, 'C', 1);
	$pdf->Cell(45, 5, 'Tabla', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'Usuario', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'Fechay Hora', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'Estacin', 1, 1, 'C', 1);
}
?>