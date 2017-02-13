<?php

//	Relacion de Nomina...
function filtro_relacion_nomina($pdf, $unidad, $codigo, $titulo, $justificacion) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(205, 5, utf8_decode($titulo), 0, 1, 'C');
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->MultiCell(205, 5, utf8_decode($justificacion), 0, 1, 'C');
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->Rect(5, 35, 205, 15);
	//	-----------------
	$pdf->SetY(50);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(20, 7, 'UNIDAD:', 0, 0, 'L'); $pdf->Cell(120, 7, $unidad, 0, 0, 'L');
	$pdf->Cell(20, 7, 'CODIGO:', 0, 0, 'L'); $pdf->Cell(40, 7, $codigo, 0, 1, 'L');
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->Rect(5, 57, 205, 0.1);
	//	-----------------
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(200, 15, 'T O T A L E S   P O R   U N I D A D', 0, 1, 'C');
	//	-----------------
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('L', 'R', 'L', 'R'));
	$pdf->SetWidths(array(70, 30, 70, 30));
	$pdf->Row(array('A S I G N A C I O N E S', '', 'D E D U C C I O N E S', ''));
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->Rect(5, 77, 65, 0.1); $pdf->Rect(105, 77, 65, 0.1);
}

function anexo_filtro_relacion_nomina($pdf, $unidad, $codigo, $titulo, $justificacion) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(205, 5, utf8_decode($titulo), 0, 1, 'C');
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->MultiCell(205, 5, utf8_decode($justificacion), 0, 1, 'C');
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->Rect(5, 35, 205, 15);
	//	-----------------
	$pdf->SetY(50);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(200, 15, 'T O T A L E S   G E N E R A L E S', 0, 1, 'C');
	//	-----------------
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('L', 'R', 'L', 'R'));
	$pdf->SetWidths(array(70, 30, 70, 30));
	$pdf->Row(array('A S I G N A C I O N E S', '', 'D E D U C C I O N E S', ''));
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->Rect(5, 77, 65, 0.1); $pdf->Rect(105, 77, 65, 0.1);
}



function relacion_conceptos_periodo($pdf, $nomconcepto, $periodo, $nom_nomina, $estado) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(205, 5, utf8_decode("Relación de Concepto por Rango de Tiempo"), 0, 1, 'C');
	$pdf->Ln(5);

	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(30, 5, utf8_decode('CONCEPTO: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(165, 5, utf8_decode($nomconcepto), 0, 1, 'L');
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(30, 5, utf8_decode('TIPO DE NOMINA: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(165, 5, utf8_decode($nom_nomina), 0, 1, 'L');
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(30, 5, utf8_decode('RANGO DE TIEMPO: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 8);
	if ($periodo != "") $pdf->Cell(175, 5, utf8_decode($periodo), 0, 1, 'L');
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(30, 5, utf8_decode('ESTADO: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(165, 5, strtoupper(utf8_decode($estado)), 0, 1, 'L');
	
	//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(10, 5, '#', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, utf8_decode('Cédula'), 1, 0, 'C', 1);
	$pdf->Cell(140, 5, utf8_decode('Apellidos y Nombres'), 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'Total Bs.', 1, 1, 'C', 1);
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetAligns(array('R', 'R', 'L', 'R'));
	$pdf->SetWidths(array(10, 25, 140, 25));
	$pdf->Ln(3);
}


function relacion_nomina_trabajadores($pdf, $nomina, $periodo, $unidad, $semana, $flagunidad, $nomunidad, $flagcentro, $nomcentro) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetY(20);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(205, 5, utf8_decode($nomina), 0, 1, 'C');
	$pdf->Cell(205, 5, utf8_decode($periodo), 0, 1, 'C');
	if ($semana != 0) $pdf->Cell(205, 5, utf8_decode("No. Periodo: ".$semana), 0, 1, 'C');
	if ($flagunidad == 'S'){
		$pdf->Cell(205, 5, utf8_decode("UBICACION FUNCIONAL: ".$nomunidad), 0, 1, 'C');
	}
	if ($flagcentro == 'S'){
		$pdf->Cell(205, 5, utf8_decode("CENTRO DE COSTO: ".$nomcentro), 0, 1, 'C');
	}
	$pdf->SetY(40);
}



function listado($pdf, $nomina, $periodo, $unidad, $semana) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetY(20);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(205, 5, utf8_decode($nomina), 0, 1, 'C');
	$pdf->Cell(205, 5, utf8_decode($periodo), 0, 1, 'C');
	//if ($semana != 0) $pdf->Cell(205, 5, utf8_decode("Semana ".$semana), 0, 1, 'C');
	$pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'C');
	$pdf->SetY(40);
	$pdf->SetY(20);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(205, 5, utf8_decode($nomina), 0, 1, 'C');
	$pdf->SetY(40);
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(10,20, 90, 50, 35));
	
	$pdf->Row(array('No.', utf8_decode('Cédula'), 'Nombres y Apellidos', 'Cuenta', 'Monto a Pagar'));
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetAligns(array('C', 'R', 'L', 'C', 'R'));
	$pdf->SetY(50);
}



function nomina_comparacion_simular($pdf, $nomina) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetY(20);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(205, 5, utf8_decode($nomina), 0, 1, 'C');
	$pdf->SetY(40);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(90, 5, 'CONCEPTO', 0, 0, 'L');
	$pdf->Cell(35, 5, 'MONTO NOMINA', 0, 0, 'R');
	$pdf->Cell(35, 5, 'MONTO SIMULADO', 0, 0, 'R');
	$pdf->Cell(35, 5, 'DIFERENCIA', 0, 1, 'R');
}

function nomina_comparacion_simular_partidas($pdf, $nomina) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetY(20);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(205, 5, utf8_decode($nomina), 0, 1, 'C');
	$pdf->SetY(40);
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); 
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R'));
	$pdf->SetWidths(array(25, 70, 35, 35, 35));
	$pdf->Row(array('CODIGO', 
					'DENOMINACION', 
					'MONTO NOMINA', 
					'MONTO SIMULADO', 
					'DIFERENCIA'));
	$pdf->Ln(2);
}

function relacion_empleado_payroll($pdf, $nomina, $periodo, $j, $semana, $empresa, $rif) {
	//	NUEVA PAGINA CABECERA
	$y = $pdf->GetY() - 7;
	//setLogo($pdf);
	printLogoPayroll($pdf, 5, $y);
	//$pdf->Ln(2);
	$pdf->SetFont('Arial', 'B', 10);	
	$pdf->Cell(205, 5, utf8_decode($empresa), 0, 1, 'C');
	$pdf->Cell(205, 5, utf8_decode($rif), 0, 1, 'C');
	$pdf->Ln(3);
	$pdf->SetFont('Arial', 'B', 8);	
	$pdf->Cell(205, 5, utf8_decode($nomina), 0, 1, 'C');
	$pdf->Cell(205, 5, utf8_decode($periodo), 0, 1, 'C');
	if ($semana != 0) $pdf->Cell(205, 5, utf8_decode("No. Periodo: ".$semana), 0, 1, 'C');
	$pdf->SetXY(190, $pdf->GetY()-5); $pdf->Cell(50, 5, 'Nro. '.$j, 0, 0, 'L');
	$pdf->SetY($pdf->GetY()+5);
	//$pdf->Ln(5);
}

function auditoria_nomina($pdf, $nomina, $periodo, $unidad, $titulo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(205, 5, utf8_decode('Reporte de Auditoria'), 0, 1, 'C');
	$pdf->SetFont('Arial', 'B', 8);
	if ($nomina!="") $pdf->Cell(205, 5, utf8_decode($nomina), 0, 1, 'C');
	if ($periodo!="") $pdf->Cell(205, 5, utf8_decode($periodo), 0, 1, 'C');
	if ($unidad!="") $pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'C');
	if ($titulo!="") $pdf->Cell(205, 5, utf8_decode($titulo), 0, 1, 'C');
	$pdf->Ln(1);
}

function nomina_resumen_anual_trabajador($pdf, $anio, $centro, $nomina) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(205, 5, utf8_decode('RESUMEN ANUAL '.$anio), 0, 1, 'C');
	if ($centro != "") $pdf->Cell(205, 5, utf8_decode($centro), 0, 1, 'C');
	if ($nomina != "") $pdf->Cell(205, 5, utf8_decode($nomina), 0, 1, 'C');
	$pdf->Ln(10);
}

function nomina_resumen_conceptos($pdf, $nomina, $periodo, $unidad, $centro, $flagunidad, $flagcentro, $nomunidad, $nomcentro) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	//if ($nomunidad != "") $unidad = "UNIDAD FUNCIONAL: ".$nomunidad;
	//if ($nomcentro != "") $centro = "CENTRO DE COSTO: ".$nomcentro;
	//	--------------------
	$pdf->SetY(20);
	$pdf->SetFont('Arial', 'B', 8);

	if ($flagunidad == 'S'){
		$pdf->Cell(205, 5, utf8_decode("UBICACION FUNCIONAL: ".$nomunidad), 0, 1, 'C');
	}
	if ($flagcentro == 'S'){
		$pdf->Cell(205, 5, utf8_decode("CENTRO DE COSTO: ".$nomcentro), 0, 1, 'C');
	}



	$pdf->Cell(205, 5, utf8_decode($nomina), 0, 1, 'C');
	$pdf->Cell(205, 5, utf8_decode($periodo), 0, 1, 'C');
	$pdf->Ln(5);
	//if ($unidad != "") $pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'L');
	//if ($centro != "") $pdf->Cell(205, 5, utf8_decode($centro), 0, 1, 'L');
	$pdf->SetY(30);
}

function nomina_detalle_conceptos($pdf, $nomina, $periodo, $concepto, $unidad) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(205, 5, utf8_decode($nomina), 0, 1, 'C');
	$pdf->Cell(205, 5, utf8_decode($concepto), 0, 1, 'C');
	$pdf->Cell(205, 5, utf8_decode($periodo), 0, 1, 'C');
	$pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'C');
	$pdf->Ln(10);
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); 
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(10, 8, utf8_decode('No.'), 1, 0, 'C', 1);
	$pdf->Cell(25, 8, utf8_decode('Cédula'), 1, 0, 'C', 1);
	$pdf->Cell(125, 8, 'Trabajador', 1, 0, 'C', 1);
	$pdf->Cell(30, 8, 'Monto', 1, 1, 'C', 1);
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 10);
	$pdf->SetAligns(array('C', 'R', 'L', 'R'));
	$pdf->SetWidths(array(10, 25, 125, 30));
	$pdf->SetHeight(array(8));
}

function nomina_constantes_lista($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 5, utf8_decode('Constantes'), 0, 1, 'C');
	$pdf->Ln(10);
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'L'));
	$pdf->SetWidths(array(25, 20, 20, 20, 115));
	$pdf->Row(array('Clasificador Presupuestario', 'Ordinal', utf8_decode('Código'), 'Abrev.', utf8_decode('Descripción')));
	$pdf->SetFont('Arial', '', 8);
}

function nomina_tipos_conceptos_lista($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 5, utf8_decode('Tipos de Conceptos'), 0, 1, 'C');
	$pdf->Ln(10);
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('C', 'L', 'C'));
	$pdf->SetWidths(array(20, 100, 30));
	$pdf->Cell(25, 5); $pdf->Row(array(utf8_decode('Código'), utf8_decode('Descripción'), 'Afecta'));
	$pdf->SetFont('Arial', '', 8);
}

function nomina_tabla_constantes($pdf, $field_head) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 5, utf8_decode('Tabla de Constantes'), 0, 1, 'C');
	$pdf->Ln(10);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(20, 5, utf8_decode('Código:'), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 5, utf8_decode($field_head['codigo']), 0, 1, 'L');
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(20, 5, utf8_decode('Descripción:'), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 5, utf8_decode($field_head['descripcion']), 0, 1, 'L');
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(20, 5, utf8_decode('Desde:'), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(20, 5, formatoFecha($field_head['desde']), 0, 0, 'L');	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(20, 5, utf8_decode('Hasta:'), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(20, 5, formatoFecha($field_head['hasta']), 0, 1, 'L');
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(20, 5, utf8_decode('Unidad:'), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 5, utf8_decode($field_head['unidad']), 0, 1, 'L');
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('C', 'C', 'C', 'R'));
	$pdf->SetWidths(array(15, 25, 25, 25));
	$pdf->Cell(55, 5); $pdf->Row(array('Nro.', 'Desde', 'Hasta', 'Valor'));
	$pdf->SetFont('Arial', '', 8);
}

function nomina_tipo_hoja_tiempo($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 5, utf8_decode('Tipos de Hoja de Tiempo'), 0, 1, 'C');
	$pdf->Ln(10);
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('L', 'L'));
	$pdf->SetWidths(array(80, 30));
	$pdf->Cell(45, 5); $pdf->Row(array(utf8_decode('Descripción'), 'Unidad'));
	$pdf->SetFont('Arial', '', 8);
}

function nomina_hoja_tiempo($pdf, $field) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 5, utf8_decode('Hoja de Tiempo'), 0, 1, 'C');
	$pdf->Ln(10);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(40, 5, utf8_decode('Tipo de Hoja de Tiempo:'), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(50, 5, utf8_decode($field['nomtipo_hoja_tiempo']), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(40, 5, utf8_decode('Tipo de Nómina:'), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(40, 5, utf8_decode($field['titulo_nomina']), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(40, 5, utf8_decode('Centro de Costos:'), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(40, 5, utf8_decode($field['nomcentro']), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(40, 5, utf8_decode('Período:'), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(40, 5, utf8_decode($field['nomperiodo']), 0, 1, 'L');
	$pdf->Ln(10);
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('R', 'L', 'L', 'C'));
	$pdf->SetWidths(array(25, 55, 55, 25));
	$pdf->Cell(20, 5); $pdf->Row(array(utf8_decode('Cédula'), 'Nombre', 'Apellido', 'Horas'));
	$pdf->SetFont('Arial', '', 8);
}

function nomina_tipo_nomina($pdf, $idtipo_nomina, $listado, $ficha, $field_tipo_nomina, $field_periodos, $titulo, $periodos, $fracciones, $jornadas) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 5, utf8_decode($titulo), 0, 1, 'C');
	$pdf->Ln(10);
	//	--------------------
	
	//	Si selecciono listado y se va imprimir todo el listado de tipos de nomina
	if ($listado == "true") {		
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->SetAligns(array('L', 'L', 'L', 'C'));
		$pdf->SetWidths(array(70, 50, 70, 10));
		$pdf->Row(array(utf8_decode('Título de la Nómina'), 'Motivo de Cuenta', 'Tipo de Documento', 'Act.'));
		$pdf->SetFont('Arial', '', 8);
	} 
	
	//	si selecciono ficha
	elseif ($ficha == "true") {
		if ($idtipo_nomina != "") {
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(40, 5, utf8_decode('Título de la Nómina:'), 0, 0, 'L');
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(50, 5, utf8_decode($field_tipo_nomina['titulo_nomina']), 0, 1, 'L');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(40, 5, utf8_decode('Motivo de Cuenta:'), 0, 0, 'L');
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(40, 5, utf8_decode($field_tipo_nomina['nommotivo_cuenta']), 0, 1, 'L');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(40, 5, utf8_decode('Tipo de Documento:'), 0, 0, 'L');
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(40, 5, utf8_decode($field_tipo_nomina['nomtipo_documento']), 0, 1, 'L');
			$pdf->Ln(5);
			
			if ($periodos) {
				$pdf->SetFont('Arial', 'B', 12);
				$pdf->Cell(200, 5, utf8_decode('Periodos'), 0, 1, 'C');
				$pdf->Ln(5);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(40, 5, utf8_decode('Año:'), 0, 0, 'L');
				$pdf->SetFont('Arial', '', 8);
				$pdf->Cell(50, 5, utf8_decode($field_periodos['anio']), 0, 1, 'L');
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(40, 5, utf8_decode('Descripción del Período:'), 0, 0, 'L');
				$pdf->SetFont('Arial', '', 8);
				$pdf->Cell(40, 5, utf8_decode($field_periodos['descripcion_periodo']), 0, 1, 'L');
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(40, 5, utf8_decode('Fecha de Inicio:'), 0, 0, 'L');
				$pdf->SetFont('Arial', '', 8);
				$pdf->Cell(40, 5, utf8_decode(formatoFecha($field_periodos['fecha_inicio'])), 0, 1, 'L');
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(40, 5, utf8_decode('Número de Periodos:'), 0, 0, 'L');
				$pdf->SetFont('Arial', '', 8);
				$pdf->Cell(40, 5, utf8_decode($field_periodos['numero_periodos']), 0, 1, 'L');
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(40, 5, utf8_decode('Activo:'), 0, 0, 'L');
				$pdf->SetFont('Arial', '', 8);
				$pdf->Cell(40, 5, utf8_decode($field_periodos['periodo_activo']), 0, 1, 'L');
				$pdf->Ln(5);
				
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->SetAligns(array('C', 'C', 'C', 'C'));
				$pdf->SetWidths(array(20, 30, 30, 30));
				$pdf->Cell(45, 5); $pdf->Row(array(utf8_decode('Nro.'), 'Desde', 'Hasta', 'Sugiere Pago'));
				$pdf->SetFont('Arial', '', 8);
			}
			
			elseif ($fracciones) {
				$pdf->SetFont('Arial', 'B', 12);
				$pdf->Cell(200, 5, utf8_decode('Fracciones'), 0, 1, 'C');
				$pdf->Ln(5);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(40, 5, utf8_decode('Tipo de Fracción:'), 0, 0, 'L');
				$pdf->SetFont('Arial', '', 8);
				$pdf->Cell(50, 5, utf8_decode($field_tipo_nomina['tipo_fraccion']), 0, 1, 'L');
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(40, 5, utf8_decode('Nro. de Fracciones:'), 0, 0, 'L');
				$pdf->SetFont('Arial', '', 8);
				$pdf->Cell(40, 5, utf8_decode($field_tipo_nomina['numero_fracciones']), 0, 1, 'L');
				$pdf->Ln(5);
				
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->SetAligns(array('C', 'C', 'C', 'C'));
				$pdf->SetWidths(array(30, 30, 30));
				$pdf->Cell(55, 5); $pdf->Row(array('Nro', '% o Valor', 'Aplica a'));
				$pdf->SetFont('Arial', '', 8);
			}
			
			elseif ($jornadas) {
				$pdf->SetFont('Arial', 'B', 12);
				$pdf->Cell(200, 5, utf8_decode('Jornadas'), 0, 1, 'C');
				$pdf->Ln(5);
				
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(20, 5); 
				$pdf->Cell(30, 5, '', 0, 0, 'C');
				$pdf->Cell(30, 5, 'Jornada Completa', 0, 0, 'C');
				$pdf->Cell(30, 5, 'Media Jornada', 0, 0, 'C');
				$pdf->Cell(30, 5, 'No laborable', 0, 1, 'C');
				
				$sql = "SELECT * FROM jornada_tipo_nomina WHERE idtipo_nomina = '".$idtipo_nomina."'";
				$query_jornada = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_jornada) != 0) {
					$field_jornada = mysql_fetch_array($query_jornada);
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(20, 5); $pdf->Cell(30, 5, 'Lunes', 0, 0, 'C');					
					$pdf->SetFont('Arial', '', 8);
					$pdf->Cell(30, 5, $completa, 0, 0, 'C');
					$pdf->Cell(30, 5, $media, 0, 0, 'C');
					$pdf->Cell(30, 5, $no, 0, 1, 'C');
					
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(20, 5); $pdf->Cell(30, 5, 'Martes', 0, 0, 'C');					
					$pdf->SetFont('Arial', '', 8);
					$pdf->Cell(30, 5, $completa, 0, 0, 'C');
					$pdf->Cell(30, 5, $media, 0, 0, 'C');
					$pdf->Cell(30, 5, $no, 0, 1, 'C');
					
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(20, 5); $pdf->Cell(30, 5, 'Miercoles', 0, 0, 'C');					
					$pdf->SetFont('Arial', '', 8);
					$pdf->Cell(30, 5, $completa, 0, 0, 'C');
					$pdf->Cell(30, 5, $media, 0, 0, 'C');
					$pdf->Cell(30, 5, $no, 0, 1, 'C');
					
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(20, 5); $pdf->Cell(30, 5, 'Jueves', 0, 0, 'C');					
					$pdf->SetFont('Arial', '', 8);
					$pdf->Cell(30, 5, $completa, 0, 0, 'C');
					$pdf->Cell(30, 5, $media, 0, 0, 'C');
					$pdf->Cell(30, 5, $no, 0, 1, 'C');
					
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(20, 5); $pdf->Cell(30, 5, 'Viernes', 0, 0, 'C');					
					$pdf->SetFont('Arial', '', 8);
					$pdf->Cell(30, 5, $completa, 0, 0, 'C');
					$pdf->Cell(30, 5, $media, 0, 0, 'C');
					$pdf->Cell(30, 5, $no, 0, 1, 'C');
				}
			}
		}	
	}
}

function sobregiro_partidas($pdf, $nomina, $periodo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(205, 5, utf8_decode($nomina), 0, 1, 'C');
	$pdf->Cell(205, 5, utf8_decode($periodo), 0, 1, 'C');
	$pdf->Ln(10);
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('C', 'C', 'L', 'R', 'R'));
	$pdf->SetWidths(array(25, 25, 85, 35, 35));
	$pdf->Row(array('Categoria',
					utf8_decode('Código'),
					utf8_decode('Descripción'),
					'Disponible',
					'Monto a Comprometer'));
	$pdf->SetFont('Arial', '', 8);
}



function relacion_forma_1312($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------

	$sql_configuracion = mysql_query("select * from configuracion");
	$bus_configuracion = mysql_fetch_array($sql_configuracion);

	$sql_configuracion_rrhh = mysql_query("select * from configuracion_rrhh");
	$bus_configuracion_rrhh = mysql_fetch_array($sql_configuracion_rrhh);

	list($d1, $m1, $a1)=SPLIT( '[/.-]', $fdesde);
	list($d2, $m2, $a2)=SPLIT( '[/.-]', $fhasta); 
	list($a3, $m3, $d3)=SPLIT( '[/.-]', $bus_configuracion_rrhh["fecha_inscripcion"]); 
	//setLogo($pdf);
	$pdf->Image('../../../imagenes/logo_ivss.jpg', 5, 5, 20, 20, '', '');
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(30);
	$pdf->Cell(400, 5, utf8_decode('REPUBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'L');
	$pdf->Cell(30);
	$pdf->Cell(400, 5, utf8_decode('MINISTERIO DEL PODER POPULAR PARA EL PROCESO SOCIAL DE TRABAJO'), 0, 1, 'L');
	$pdf->Cell(30);
	$pdf->Cell(400, 5, utf8_decode('INSTITUTO VENEZOLANO DE LOS SEGUROS SOCIALES'), 0, 1, 'L');
	$pdf->Ln(1);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 14);
	$pdf->Cell(350, 5, utf8_decode('REGISTRO PATRONAL DE ASEGURADOS'), 0, 1, 'C');
	$pdf->Ln(4);
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 10);
	$pdf->SetAligns(array('C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(150,40,116,40));
	$pdf->Cell(2, 0); $pdf->Row(array(utf8_decode('RAZÓN SOCIAL DE LA EMPRESA O NOMBRE DEL EMPLEADOR'), 
													utf8_decode('No. DE RIF'), 
													utf8_decode('DOMICILIO FISCAL DE LA EMPRESA U ORGANISMO PUBLICO'),
													'No. PATRONAL'));
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetAligns(array('L', 'C', 'L', 'C'));
	$pdf->SetWidths(array(150,40,116,40));
	$pdf->Cell(2, 0); $pdf->Row(array(utf8_decode($bus_configuracion["nombre_institucion"]), 
													utf8_decode($bus_configuracion["rif"]), 
													utf8_decode($bus_configuracion["domicilio_legal"]),
													utf8_decode($bus_configuracion_rrhh["numero_patronal_ivss"])));
	$pdf->Ln(2);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 10);
	$pdf->SetAligns(array('C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(90,40,45,20,20));
	$pdf->Cell(50, 0); $pdf->Row(array(utf8_decode('PERIODO COMPRENDIDO ENTRE LAS FECHAS'), '',
													utf8_decode('FECHA DE INSCRIPCION'), 
													utf8_decode('RÉGIMEN'),
													'RIESGO'));
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetAligns(array('C', 'C', 'C', 'C','C', 'C', 'C', 'C','C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(15,15,15,15,15,15,40,15,15,15,20,20));
	$pdf->Cell(50, 0); $pdf->Row(array(utf8_decode('DÍA'),
													utf8_decode('MES'), 
													utf8_decode('AÑO'),
													utf8_decode('DÍA'),
													utf8_decode('MES'), 
													utf8_decode('AÑO'),
													'',
													utf8_decode('DÍA'),
													utf8_decode('MES'), 
													utf8_decode('AÑO'),
													'',
													''));
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('C', 'C', 'C', 'C','C', 'C', 'C', 'C','C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(15,15,15,15,15,15,40,15,15,15,20,20));
	$pdf->Cell(50, 0); $pdf->Row(array($d1,$m1,$a1,$d2,$m2,$a2,'',$d3,$m3,$a3,
													utf8_decode($bus_configuracion_rrhh["regimen"]), 
													utf8_decode($bus_configuracion_rrhh["riesgo"]),
													));
	$pdf->Ln(2);
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 5);
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(53, 10, 12,15,10,60,12,15,15,30,14,14,14,14,14,14,20,10));
	$pdf->Cell(2, 0); $pdf->Row(array(utf8_decode('APELLIDOS Y NOMBRES'), 
													utf8_decode('NACIONALIDAD'), 
													'CEDULA DE IDENTIDAD No.',
													'FECHA DE NACIMIENTO',
													'SEXO',
													'DIRECCION DEL TRABAJADOR',
													'No. DE REGISTRO EN EL IVSS',
													'FECHA DE INGRESO',
													'FECHA DE RETIRO',
													'SALARIO O SUELDO',
													'COTIZACION SEMANAL TRABAJADOR (IVSS)',
													'APORTE SEMANAL EMPLEADOR (IVSS)',
													'TOTAL DE APORTES AL IVSS',
													'COTIZACION SEMANAL TRABAJADOR R.P.E.',
													'APORTE SEMANAL EMPLEADOR R.P.E.',
													'TOTALES APORTES POR R.P.E.',
													'OCUPACION U OFICIO',
													'OTROS'));
	
	$pdf->SetFont('Arial', '', 8);
}


function relacion_anticipo_terceros($pdf, $nomconceptod, $nomconceptoa, $periodo, $nom_nomina, $estado) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(205, 5, utf8_decode("Relación de Fondos de Terceros"), 0, 1, 'C');
	$pdf->Ln(5);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(30, 5, utf8_decode('TIPO DE NOMINA: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(165, 5, utf8_decode($nom_nomina), 0, 1, 'L');
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(30, 5, utf8_decode('RANGO DE TIEMPO: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 8);
	if ($periodo != "") $pdf->Cell(175, 5, utf8_decode($periodo), 0, 1, 'L');
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(30, 5, utf8_decode('ESTADO: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(165, 5, strtoupper(utf8_decode($estado)), 0, 1, 'L');
	
	//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(10, 22, 90, 25, 25, 28));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->Cell(2, 0); $pdf->Row(array(utf8_decode('#'), 
													utf8_decode('C.I.'), 
													'Apellidos y Nombres',
													utf8_decode($nomconceptod),
													utf8_decode($nomconceptoa),
													'Total Bs.'));
	

	/*$pdf->MultiCell(10, 5, '#', 1, 0, 'C', 1);
	$pdf->MultiCell(20, 5, utf8_decode('Cédula'), 1, 0, 'C', 1);
	$pdf->MultiCell(90, 5, utf8_decode('Apellidos y Nombres'), 1, 0, 'C', 1);
	$pdf->MultiCell(25, 5, utf8_decode($nomconceptod), 1, 0, 'C', 1);
	$pdf->MultiCell(25, 5, utf8_decode($nomconceptoa), 1, 0, 'C', 1);
	$pdf->MultiCell(28, 5, 'Total Bs.', 1, 1, 'C', 1);
	*/
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetAligns(array('R', 'R', 'L', 'R', 'R', 'R'));
	$pdf->SetWidths(array(10, 22, 90, 25, 25, 28));
	$pdf->Ln(3);
}



?>