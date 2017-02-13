<?php

//	Solicitud de Cotizaciones...
function filtro_solicitud_cotizacion($pdf, $head, $proveedor, $desde, $hasta, $tipo, $articulo, $estado) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, utf8_decode('Solicitudes de Cotización'), 0, 1, 'C');
	if ($proveedor!="") {
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, 'Proveedor: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, $proveedor, 0, 1, 'L');
	}
	if ($desde!="" && $hasta!="") {
		list($d, $m, $a)=SPLIT( '[/.-]', $desde); $fecha_desde=$d."/".$m."/".$a;
		list($d, $m, $a)=SPLIT( '[/.-]', $hasta); $fecha_hasta=$d."/".$m."/".$a;
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, 'Período: ', 0, 0, 'L');
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
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, 'Articulo: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, $articulo, 0, 1, 'L');
	}
	//---------------------------------------------------
	if ($head==1) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(45, 5, 'Tipo de Solicitud', 1, 0, 'C', 1);
		$pdf->Cell(80, 5, 'Proveedor', 1, 1, 'C', 1);
	}
	//---------------------------------------------------
	elseif ($head==2) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(125, 5, 'Tipo de Solicitud', 1, 1, 'C', 1);
	}
	elseif ($head==3) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(125, 5, 'Proveedor', 1, 1, 'C', 1);
	}
	elseif ($head==4) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 1, 'C', 1);
	}
	elseif ($head==5) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(45, 5, 'Tipo de Solicitud', 1, 0, 'C', 1);
		$pdf->Cell(80, 5, 'Proveedor', 1, 1, 'C', 1);
	}
	elseif ($head==6) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(125, 5, 'Tipo de Solicitud', 1, 1, 'C', 1);
	}
	elseif ($head==7) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(125, 5, 'Proveedor', 1, 1, 'C', 1);
	}
	elseif ($head==8) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 1, 'C', 1);
	}
}

//	Certificacion de Compromisos...
function filtro_compromisos_head($pdf, $head, $proveedor, $categoria, $desde, $hasta, $tipo, $articulo, $estado) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, utf8_decode('Lista de Certificación de Compromisos'), 0, 1, 'C');
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
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(95, 5, 'Proveedor', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 1, 'C', 1);
	}
	//---------------------------------------------------
	elseif ($head==2) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 1, 'C', 1);
	}
	elseif ($head==3) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(125, 5, 'Proveedor', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 1, 'C', 1);
	}
	elseif ($head==4) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 1, 'C', 1);
	}
	elseif ($head==5) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(80, 5, 'Proveedor', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 1, 'C', 1);
	}
	elseif ($head==6) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 1, 'C', 1);
	}
	elseif ($head==7) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(125, 5, 'Proveedor', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 1, 'C', 1);
	}
	elseif ($head==8) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Monto', 1, 1, 'C', 1);
	}
}

//	Ordenes de Pago...
function filtro_orden_pago($pdf, $head, $proveedor, $categoria, $desde, $hasta, $tipo, $estado, $nombremodulo, $fuente) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 10, 'Reporte de Ordenes de Pago', 0, 1, 'C');
	if ($proveedor!="") {
		$pdf->SetFont('Arial', '', 6); $pdf->Cell(33, 4, 'Proveedor/Beneficiario: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6); $pdf->Cell(162, 4, $proveedor, 0, 1, 'L');
	}
	if ($nombremodulo!="") {
		$pdf->SetFont('Arial', '', 6); $pdf->Cell(33, 4, 'Dependencia: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6); $pdf->Cell(162, 4, $nombremodulo, 0, 1, 'L');
	}
	if ($categoria!="") {
		$pdf->SetFont('Arial', '', 6); $pdf->Cell(33, 4, utf8_decode('Categoría Programática: '), 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6); $pdf->Cell(162, 4, $categoria, 0, 1, 'L');
	}
	if ($fuente!="") {
		$pdf->SetFont('Arial', '', 6); $pdf->Cell(33, 4, utf8_decode('Fuente de Financiamiento: '), 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6); $pdf->Cell(162, 4, $fuente, 0, 1, 'L');
	}
	if ($desde!="" && $hasta!="") {
		list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fecha_desde=$d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fecha_hasta=$d."/".$m."/".$a;
		$pdf->SetFont('Arial', '', 6); $pdf->Cell(33, 4, utf8_decode('Período: '), 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6); $pdf->Cell(162, 4, $fecha_desde."  al  ".$fecha_hasta, 0, 1, 'L');
	}
	if ($tipo!="") {
		$pdf->SetFont('Arial', '', 6); $pdf->Cell(33, 4, 'Tipo de Solicitud: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6); $pdf->Cell(162, 4, $tipo, 0, 1, 'L');
	}
	if ($estado!="") {
		$pdf->SetFont('Arial', '', 6); $pdf->Cell(33, 4, 'Estado: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6); $pdf->Cell(162, 4, $estado, 0, 1, 'L');
	}
	//---------------------------------------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);

	if ($head==1) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		
		$pdf->Cell(25, 5, 'Nro. Orden', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(95, 5, 'Beneficiario / Proveedor', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Monto Bruto', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Retenido/Deducido', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Total Pagado', 1, 0, 'C', 1);
		//$pdf->Cell(20, 5, '', 0, 1, 'C', 1);
	}
	//---------------------------------------------------
	elseif ($head==2) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		
		$pdf->Cell(25, 5, 'Nro. Orden', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(75, 5, 'Beneficiario / Proveedor', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Doc. Financiero', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Monto Bruto', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Retenido/Deducido', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Total Pagado', 1, 0, 'C', 1);
		//$pdf->Cell(20, 5, '', 0, 1, 'C', 1);
	}
	$pdf->Ln(4);
	/*
	elseif ($head==3) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		
		$pdf->Cell(20, 5, 'Nro. Orden', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(40, 5, 'Beneficiario / Proveedor', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Documento Pago', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Total Bruto', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Total Retenido', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Total Pagado', 1, 0, 'C', 1);
		//$pdf->Cell(20, 5, '', 0, 1, 'C', 1);
		$pdf->Ln(4);
	}
	elseif ($head==4) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		
		$pdf->Cell(20, 5, 'Nro. Orden', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(40, 5, 'Beneficiario / Proveedor', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Documento Pago', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Total Bruto', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Total Retenido', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Total Pagado', 1, 0, 'C', 1);
		//$pdf->Cell(20, 5, '', 0, 1, 'C', 1);
	}
	elseif ($head==5) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		
		$pdf->Cell(20, 5, 'Nro. Orden', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(40, 5, 'Beneficiario / Proveedor', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Documento Pago', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Total Bruto', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Total Retenido', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Total Pagado', 1, 0, 'C', 1);
		//$pdf->Cell(20, 5, '', 0, 1, 'C', 1);
	}
	elseif ($head==6) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(25, 5, 'Nro. Orden', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(40, 5, 'Tipo de Solicitud', 1, 0, 'C', 1);
		$pdf->Cell(125, 5, 'Concepto', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Documento Pago', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Total Gasto', 1, 0, 'C', 1);
		//$pdf->Cell(20, 5, '', 0, 1, 'C', 1);
	}
	elseif ($head==7) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(25, 5, 'Nro. Orden', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(55, 5, 'Proveedor', 1, 0, 'C', 1);
		$pdf->Cell(110, 5, 'Concepto', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Documento Pago', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Total Gasto', 1, 0, 'C', 1);
		//$pdf->Cell(20, 5, '', 0, 1, 'C', 1);
	}
	elseif ($head==8) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(25, 5, 'Nro. Orden', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(160, 5, 'Concepto', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Documento Pago', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Total Gasto', 1, 0, 'C', 1);
		//$pdf->Cell(20, 5, '', 0, 1, 'C', 1);
	}
	*/
}

//	(Modulo) Orden de Pago...
function ordenpago($pdf, $numero, $fecha, $tipo_documento, $pag, $idtipo_documento) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(15, 4); $pdf->Cell(100, 5, '', 0, 0, 'L');
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 4, utf8_decode('Número: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 4, $numero, 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(15, 4); $pdf->Cell(100, 5, '', 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 4, 'Fecha: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $fecha=$d."/".$m."/".$a;
	$pdf->Cell(35, 4, $fecha, 0, 1, 'L');	 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(165, 4, utf8_decode('Página: '), 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 4, $pag, 0, 1, 'L'); 
	/////////////////////////////
	$pdf->Ln(10);
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(200, 5, 'Orden de Pago', 0, 1, 'C');
	$pdf->Cell(200, 7, '', 0, 1, 'C');
	/////////////////////////////
	getFootOrdenPago($pdf, $modulo, $tipo_documento, $idtipo_documento);
}

function ordenpago_anexo($pdf, $numero, $fecha, $pag, $idtipo_documento) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	setLogo($pdf);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(15, 4); $pdf->Cell(100, 5, '', 0, 0, 'L');
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 4, utf8_decode('Número: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 4, $numero, 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(15, 4); $pdf->Cell(100, 5, '', 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 4, 'Fecha: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $fecha=$d."/".$m."/".$a;
	$pdf->Cell(35, 4, $fecha, 0, 1, 'L');	 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(165, 4, utf8_decode('Página: '), 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 4, $pag, 0, 1, 'L'); 
	/////////////////////////////
	$pdf->Ln(10);
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(200, 5, 'Orden de Pago', 0, 1, 'C');
	$pdf->Cell(200, 7, '', 0, 1, 'C');
}

function ordenpago_compromisos_cancelados($pdf, $numero, $fecha, $pag, $idtipo_documento, $modulo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	setLogo($pdf);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(15, 4); $pdf->Cell(100, 5, '', 0, 0, 'L');
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 4, utf8_decode('Número: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 4, $numero, 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(15, 4); $pdf->Cell(100, 5, '', 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 4, 'Fecha: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $fecha=$d."/".$m."/".$a;
	$pdf->Cell(35, 4, $fecha, 0, 1, 'L');	 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(165, 4, utf8_decode('Página: '), 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
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
	if (in_array(1,$modulo)==true) {
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

//	Ordenes de Pago por Financiamiento...
function ordenes_pago_por_financiamiento($pdf, $estado, $ffinanciamiento) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 10, 'Ordenes de Pago', 0, 1, 'C');
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 10, 'ESTADO: '.strtoupper($estado), 0, 1, 'C');
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 10, 'FUENTE DE FINANCIAMIENTO: '.strtoupper($ffinanciamiento), 0, 1, 'C');
	$pdf->Ln(5);
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(35, 5, 'Nro. Orden', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'Fecha Orden', 1, 0, 'C', 1);
	$pdf->Cell(90, 5, 'Beneficiario', 1, 0, 'C', 1);
	$pdf->Cell(30, 5, 'Estado', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'Monto', 1, 1, 'C', 1);
	//	--------------------
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetAligns(array('L', 'C', 'L', 'C', 'R'));
	$pdf->SetWidths(array(35, 25, 90, 30, 25));
}

//	Sobregiro tributario...
function sobregiro_tributario($pdf) {
	global $cantidad_ut;
	global $valor_ut;
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(265, 10, 'Ordenes de Pago', 0, 1, 'C');
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 4, 'Cantidad de U.T: ', 0, 0, 'L');
	$pdf->Cell(240, 4, $cantidad_ut, 0, 1, 'L');
	$pdf->Cell(25, 4, 'Valor de U.T: ', 0, 0, 'L');
	$pdf->Cell(240, 4, $valor_ut, 0, 1, 'L');
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(20, 5, 'Nro.', 1, 0, 'C', 1);
	$pdf->Cell(15, 5, 'Fecha', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'Estado', 1, 0, 'C', 1);
	$pdf->Cell(30, 5, 'Tipo de Solicitud', 1, 0, 'C', 1);
	$pdf->Cell(60, 5, 'Proveedor', 1, 0, 'C', 1);
	$pdf->Cell(100, 5, 'Concepto', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'Monto', 1, 1, 'C', 1);
}
?>