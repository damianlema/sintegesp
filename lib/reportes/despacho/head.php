<?php
//	Solicitud de Cotizaciones...
filtro_compromisos_despacho_head($pdf, $head, $proveedor, $categoria, $desde, $hasta, $tipo, $articulo, $estado) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(260, 10, utf8_decode('Lista de Certificación de Compromisos'), 0, 1, 'C');
	if ($proveedor!="") {
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, 'Proveedor: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, $proveedor, 0, 1, 'L');
	}
	if ($categoria!="") {
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, utf8_decode('Categoría Programática: '), 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, $categoria, 0, 1, 'L');
	}
	if ($desde!="" && $hasta!="") {
		list($d, $m, $a)=SPLIT( '[/.-]', $desde); $fecha_desde=$d."/".$m."/".$a;
		list($d, $m, $a)=SPLIT( '[/.-]', $hasta); $fecha_hasta=$d."/".$m."/".$a;
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, utf8_decode('Período: '), 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, $fecha_desde."  al  ".$fecha_hasta, 0, 1, 'L');
	}
	if ($tipo!="") {
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, 'Tipo de Solicitud: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, $tipo, 0, 1, 'L');
	}
	if ($estado!="") {
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, 'Estado: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, $estado, 0, 1, 'L');
	}
	if ($articulo!="") {
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, utf8_decode('Artículo: '), 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, $articulo, 0, 1, 'L');
	}
	//---------------------------------------------------
	if ($head==1) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(20, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(35, 5, 'Tipo de Solicitud', 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 0, 'C', 1);
		$pdf->Cell(80, 5, utf8_decode('Justificación'), 1, 1, 'C', 1);
	}
	//---------------------------------------------------
	elseif ($head==2) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(40, 5, 'Tipo de Solicitud', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 0, 'C', 1);
		$pdf->Cell(120, 5, utf8_decode('Justificación'), 1, 1, 'C', 1);
	}
	elseif ($head==3) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 0, 'C', 1);
		$pdf->Cell(100, 5, utf8_decode('Justificación'), 1, 1, 'C', 1);
	}
	elseif ($head==4) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 0, 'C', 1);
		$pdf->Cell(160, 5, utf8_decode('Justificación'), 1, 1, 'C', 1);
	}
	elseif ($head==5) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(35, 5, 'Tipo de Solicitud', 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 0, 'C', 1);
		$pdf->Cell(90, 5, utf8_decode('Justificación'), 1, 1, 'C', 1);
	}
	elseif ($head==6) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(40, 5, 'Tipo de Solicitud', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 0, 'C', 1);
		$pdf->Cell(145, 5, utf8_decode('Justificación'), 1, 1, 'C', 1);
	}
	elseif ($head==7) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 0, 'C', 1);
		$pdf->Cell(125, 5, utf8_decode('Justificación'), 1, 1, 'C', 1);
	}
	elseif ($head==8) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 0, 'C', 1);
		$pdf->Cell(185, 5, utf8_decode('Justificación'), 1, 1, 'C', 1);
	}
}
?>