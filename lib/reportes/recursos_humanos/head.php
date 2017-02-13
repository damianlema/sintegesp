<?php
//	Grupos...
function grupos($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//$pdf->Image('../../../imagenes/logo_gob.jpg', 5, 5, 60, 15, '', '');
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Grupos', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(30, 5, 'GRUPO', 1, 0, 'C', 1);
	$pdf->Cell(166, 5, 'DENOMINACION', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('C', 'L'));
	$pdf->SetWidths(array(30, 166));
}

//	Series...
function series($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Series de Cargo', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(85, 5, 'GRUPO', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'SERIE', 1, 0, 'C', 1);
	$pdf->Cell(85, 5, 'DENOMINACION', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L', 'C', 'L'));
	$pdf->SetWidths(array(85, 20, 85));
}

//	Cargos...
function cargos($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Cargos', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(75, 5, 'SERIE', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'CARGO', 1, 0, 'C', 1);
	$pdf->Cell(80, 5, 'DENOMINACION', 1, 0, 'C', 1);
	$pdf->Cell(15, 5, 'GRADO', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L', 'C', 'L', 'C'));
	$pdf->SetWidths(array(75, 20, 80, 15));
}

//	Nivel...
function nivel($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Nivel de Estudios', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(190, 5, 'DENOMINACION', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L'));
	$pdf->SetWidths(array(190));
}

//	Profesion...
function profesion($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Profesiones', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(120, 5, 'DENOMINACION', 1, 0, 'C', 1);
	$pdf->Cell(70, 5, 'ABREVIATURA', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L', 'L'));
	$pdf->SetWidths(array(120, 70));
}

//	Mensiones...
function mensiones($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Mensiones', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(190, 5, 'DENOMINACION', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L'));
	$pdf->SetWidths(array(190));
}

//	Estado Civil...
function edocivil($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Estados Civiles', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(190, 5, 'DENOMINACION', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L'));
	$pdf->SetWidths(array(190));
}

//	Parentesco...
function parentesco($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Parentescos', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(190, 5, 'DENOMINACION', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L'));
	$pdf->SetWidths(array(190));
}

//	Grupo Sanguineo...
function gsang($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, utf8_decode('Grupo Sanguíneo'), 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(190, 5, 'DENOMINACION', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L'));
	$pdf->SetWidths(array(190));
}

//	Tipos de Movimiento...
function tmov($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Tipos de Movimiento', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(190, 5, 'DENOMINACION', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L'));
	$pdf->SetWidths(array(190));
}

//	Lista de Trabajadores...
function listatrab($pdf, $campos) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Lista de Trabajadores', 0, 1, 'C');
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(200, 4, "Estado: ".utf8_decode(strtoupper($estado)), 0, 1, 'L');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	if ($campos[2] && $campos[3]) {
		$pdf->Cell(20, 5, 'Cedula', 1, 0, 'C', 1);
		$pdf->Cell(65, 5, 'Nombre y Apellido', 1, 0, 'C', 1);
		$pdf->Cell(55, 5, 'Cento de Costo', 1, 0, 'C', 1);
		$pdf->Cell(55, 5, 'Unidad Funcional', 1, 0, 'C', 1);
		$pdf->Ln();
		$pdf->SetAligns(array('R', 'L', 'L', 'L'));
		$pdf->SetWidths(array(20, 65, 55, 55));
	}elseif (!$campos[2] && $campos[3]) {
		$pdf->Cell(20, 5, 'Cedula', 1, 0, 'C', 1);
		$pdf->Cell(90, 5, 'Nombre y Apellido', 1, 0, 'C', 1);
		$pdf->Cell(85, 5, 'Unidad Funcional', 1, 0, 'C', 1);
		$pdf->Ln();
		$pdf->SetAligns(array('R', 'L', 'L'));
		$pdf->SetWidths(array(20, 90, 85));
	}elseif ($campos[2] && !$campos[3]) {
		$pdf->Cell(20, 5, 'Cedula', 1, 0, 'C', 1);
		$pdf->Cell(90, 5, 'Nombre y Apellido', 1, 0, 'C', 1);
		$pdf->Cell(85, 5, 'Centro de Costo', 1, 0, 'C', 1);
		$pdf->Ln();
		$pdf->SetAligns(array('R', 'L', 'L'));
		$pdf->SetWidths(array(20, 90, 85));
	}elseif (!$campos[2] && !$campos[3]) {
		$pdf->Cell(20, 5, 'Cedula', 1, 0, 'C', 1);
		$pdf->Cell(175, 5, 'Nombre y Apellido', 1, 0, 'C', 1);
		$pdf->Ln();
		$pdf->SetAligns(array('R', 'L'));
		$pdf->SetWidths(array(20, 175));
	}
}

//	Tipos de Persona...
function tipopersona($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Tipos de Persona', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(190, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L'));
	$pdf->SetWidths(array(190));
}

//	Tipos de Sociedad...
function tiposociedad($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Tipo de Sociedad', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(150, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Cell(40, 5, 'SIGLAS', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L', 'C'));
	$pdf->SetWidths(array(150, 40));
}

//	Tipos de Empresa...
function tipoempresa($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Tipos de Empresas', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(190, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L'));
	$pdf->SetWidths(array(190));
}

//	Tipos de Beneficiario...
function tipobene($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Tipos de Beneficiario', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(190, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L'));
	$pdf->SetWidths(array(190));
}

//	Estado de Beneficiario...
function edobene($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Estado de Beneficiario', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(190, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L'));
	$pdf->SetWidths(array(190));
}

//	Documentos Requeridos...
function docreq($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Documentos Requeridos', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(190, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L'));
	$pdf->SetWidths(array(190));
}

//	(Modulo) Certificacion de Compromisos...
function certificacion_compromiso_rrhh($pdf, $tipo_documento, $nro_orden, $fecha_orden, $pag, $documento, $modulo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	$pdf->SetMargins(8, 5, 5);
	$pdf->SetAutoPageBreak(1, 5);
	setLogo($pdf);
	$pdf->Ln(10);
	//	-----------------------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 11); 
	$pdf->SetXY(160, 10); $pdf->Cell(20, 5, utf8_decode('Página:'), 0, 1, 'R'); 
	$pdf->SetFont('Arial', '', 11); 
	$pdf->SetXY(180, 10); $pdf->Cell(20, 5, $pag, 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 11); 
	$pdf->SetXY(160, 15); $pdf->Cell(20, 5, 'Nro. Orden:', 0, 1, 'R'); 
	$pdf->SetFont('Arial', 'B', 11); 
	$pdf->SetXY(180, 15); $pdf->Cell(20, 5, $nro_orden, 0, 1, 'L'); 
	$pdf->SetFont('Arial', 'B', 11); 
	$pdf->SetXY(160, 20); $pdf->Cell(20, 5, 'Fecha Orden', 0, 1, 'R');
	$pdf->SetFont('Arial', '', 11); 
	$pdf->SetXY(180, 20); $pdf->Cell(20, 5, $fecha_orden, 0, 1, 'L');
	//	-----------------------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 6, utf8_decode('CERTIFICACION DE COMPROMISO'), 0, 1, 'C');
	$pdf->Cell(200, 6, utf8_decode($tipo_documento), 0, 1, 'C');
	//	-----------------------------------
	getFootCertificacion($pdf, $modulo, $tipo_documento, $documento);
}

function anexo_certificacion_compromiso_rrhh($pdf, $tipo_documento, $nro_orden, $fecha_orden, $pag, $documento, $modulo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	$pdf->SetMargins(8, 5, 5);
	$pdf->SetAutoPageBreak(1, 5);
	setLogo($pdf);
	$pdf->Ln(10);
	//	-----------------------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 11); 
	$pdf->SetXY(160, 10); $pdf->Cell(20, 5, utf8_decode('Página:'), 0, 1, 'R'); 
	$pdf->SetFont('Arial', '', 11); 
	$pdf->SetXY(180, 10); $pdf->Cell(20, 5, $pag, 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 11); 
	$pdf->SetXY(160, 15); $pdf->Cell(20, 5, 'Nro. Orden:', 0, 1, 'R'); 
	$pdf->SetFont('Arial', 'B', 11); 
	$pdf->SetXY(180, 15); $pdf->Cell(20, 5, $nro_orden, 0, 1, 'L'); 
	$pdf->SetFont('Arial', 'B', 11); 
	$pdf->SetXY(160, 20); $pdf->Cell(20, 5, 'Fecha Orden', 0, 1, 'R');
	$pdf->SetFont('Arial', '', 11); 
	$pdf->SetXY(180, 20); $pdf->Cell(20, 5, $fecha_orden, 0, 1, 'L');
	//	-----------------------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 7, utf8_decode('ANEXO'), 0, 1, 'C');
	$pdf->Cell(200, 7, utf8_decode('CERTIFICACION DE COMPROMISO'), 0, 1, 'C');
	//	---------------------------------
	$pdf->Ln(5);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 5, 'CAT. PROG.', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'PARTIDA', 1, 0, 'C', 1);
	$pdf->Cell(110, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Cell(40, 5, 'MONTO', 1, 1, 'C', 1);
	$pdf->Ln(1);
}

function anexo_certificacion_compromiso_rrhh_contable($pdf, $tipo_documento, $nro_orden, $fecha_orden, $pag, $documento, $modulo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	$pdf->SetMargins(8, 5, 5);
	$pdf->SetAutoPageBreak(1, 5);
	setLogo($pdf);
	$pdf->Ln(10);
	//	-----------------------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 11); 
	$pdf->SetXY(160, 10); $pdf->Cell(20, 5, utf8_decode('Página:'), 0, 1, 'R'); 
	$pdf->SetFont('Arial', '', 11); 
	$pdf->SetXY(180, 10); $pdf->Cell(20, 5, $pag, 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 11); 
	$pdf->SetXY(160, 15); $pdf->Cell(20, 5, 'Nro. Orden:', 0, 1, 'R'); 
	$pdf->SetFont('Arial', 'B', 11); 
	$pdf->SetXY(180, 15); $pdf->Cell(20, 5, $nro_orden, 0, 1, 'L'); 
	$pdf->SetFont('Arial', 'B', 11); 
	$pdf->SetXY(160, 20); $pdf->Cell(20, 5, 'Fecha Orden', 0, 1, 'R');
	$pdf->SetFont('Arial', '', 11); 
	$pdf->SetXY(180, 20); $pdf->Cell(20, 5, $fecha_orden, 0, 1, 'L');
	//	-----------------------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 7, utf8_decode('ANEXO'), 0, 1, 'C');
	$pdf->Cell(200, 7, utf8_decode('CERTIFICACION DE COMPROMISO'), 0, 1, 'C');
	//	---------------------------------
	$pdf->Ln(5);

}
function trabajadores_por_tipo_de_nomina($pdf, $nomina, $estado) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	$pdf->SetMargins(8, 5, 5);
	$pdf->SetAutoPageBreak(1, 5);
	setLogo($pdf);
	$pdf->Ln(10);
	//	-----------------------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(250, 7, utf8_decode('Lista de Trabajadores por Nómina'), 0, 1, 'C');

	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(250, 4, utf8_decode($nomina), 0, 1, 'C');
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(250, 4, "Estado: ".utf8_decode(strtoupper($estado)), 0, 1, 'L');
	//	---------------------------------
	$pdf->Ln(2);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('C','C', 'R', 'L', 'C', 'L', 'L', 'L'));
	$pdf->SetWidths(array(8, 14, 17, 60, 20, 55, 36, 55));
	$pdf->Row(array('No.','Ficha', utf8_decode('Cédula'), 'Apellidos y Nombres', 'F. Ingreso', 'Cargo', 'Cuenta', 'Centro Costo'));
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Ln(4);
}

function trabajadores_por_concepto_constante($pdf, $concepto, $estado,$evalua_rango,$valor_evaluar,$aumento_aplicar,$aumento_hasta,$nomina_concepto) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	$pdf->SetMargins(8, 5, 5);
	$pdf->SetAutoPageBreak(1, 5);
	setLogo($pdf);
	$pdf->Ln(10);
	//	-----------------------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 7, utf8_decode('Lista de Trabajadores por Concepto'), 0, 1, 'C');
	
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(200, 4, utf8_decode($concepto), 0, 1, 'C');
	$pdf->Ln(2);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(200, 4, "Todos los que ganan menos o igual a: ".number_format($valor_evaluar,2,",","."), 0, 1, 'L');
	$pdf->Ln(2);
	$pdf->SetFont('Arial', 'B', 10);
	if ($aumento_aplicar <> ''){
		$pdf->Cell(200, 4, "Porcentaje aumento: ".number_format($aumento_aplicar,2,",","."), 0, 1, 'L');
	}else{
		$pdf->Cell(200, 4, "Aumentar hasta: ".number_format($aumento_hasta,2,",","."), 0, 1, 'L');
	}
	if ($nomina_concepto <> ''){
		$sql = mysql_query("select * from tipo_nomina where idtipo_nomina = '".$nomina_concepto."'")or die(mysql_error());
		$reg = mysql_fetch_array($sql);
		$pdf->Ln(2);
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(200, 4, "Tipo de Nomina: ".utf8_decode($reg["titulo_nomina"]), 0, 1, 'L');
	}
	
	$pdf->Ln(2);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(200, 4, "Estado: ".utf8_decode(strtoupper($estado)), 0, 1, 'L');
	//	---------------------------------
	$pdf->Ln(2);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);	
	$pdf->SetAligns(array('C','C', 'R', 'L', 'R', 'R', 'R'));
	$pdf->SetWidths(array(8,20, 20, 92, 20, 20, 20));
	$pdf->Row(array('No.','Ficha', utf8_decode('Cédula'), 'Apellidos y Nombres', 'Sueldo Actual', 'Incremento', 'Nuevo Sueldo'));
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Ln(4);
}

function trabajadores_por_unidad_funcional($pdf, $unidad_funcional, $estado) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	$pdf->SetMargins(8, 5, 5);
	$pdf->SetAutoPageBreak(1, 5);
	setLogo($pdf);
	$pdf->Ln(10);
	//	-----------------------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 7, utf8_decode('Lista de Trabajadores por Unidad Funcional'), 0, 1, 'C');
	
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(200, 4, utf8_decode($unidad_funcional), 0, 1, 'C');
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(200, 4, "Estado: ".utf8_decode(strtoupper($estado)), 0, 1, 'L');
	//	---------------------------------
	$pdf->Ln(2);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);	
	$pdf->SetAligns(array('C', 'C', 'R', 'L', 'C', 'L'));
	$pdf->SetWidths(array(8, 20, 20, 62, 20, 70));
	$pdf->Row(array('No.','Ficha', utf8_decode('Cédula'), 'Apellidos y Nombres', 'F. Ingreso', 'Cargo'));
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Ln(4);
}

function trabajadores_por_centro_costos($pdf, $centro_costo, $estado) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	$pdf->SetMargins(8, 5, 5);
	$pdf->SetAutoPageBreak(1, 5);
	setLogo($pdf);
	$pdf->Ln(10);
	//	-----------------------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 7, utf8_decode('Lista de Trabajadores por Centro de Costos'), 0, 1, 'C');
	
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(200, 4, utf8_decode($centro_costo), 0, 1, 'C');
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(200, 4, "Estado: ".utf8_decode(strtoupper($estado)), 0, 1, 'L');
	//	---------------------------------
	$pdf->Ln(2);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);	
	$pdf->SetAligns(array('C','C', 'R', 'L', 'C', 'L'));
	$pdf->SetWidths(array(8, 20, 20, 62, 20, 70));
	$pdf->Row(array('No.','Ficha', utf8_decode('Cédula'), 'Apellidos y Nombres', 'F. Ingreso', 'Cargo'));
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Ln(4);
}


function trabajadores_carga_familiar($pdf, $agrupador) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	$pdf->SetMargins(8, 5, 5);
	$pdf->SetAutoPageBreak(1, 5);
	setLogo($pdf);
	$pdf->Ln(10);
	//	-----------------------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 7, utf8_decode('Carga Familiar del Trabajador'), 0, 1, 'C');
	
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(200, 4, utf8_decode($agrupador), 0, 1, 'L');
	//	---------------------------------
	$pdf->Ln(2);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(245, 245, 245); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);	
	$pdf->SetAligns(array('C', 'R', 'L', 'C'));
	$pdf->SetWidths(array(20, 20, 100, 20));
	$pdf->Row(array('Ficha', utf8_decode('Cédula'), 'Nombres y Apellidos', 'F. Ingreso'));
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
}


function trabajadores_ficha($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	$pdf->SetMargins(8, 5, 5);
	$pdf->SetAutoPageBreak(1, 5);
	setLogo($pdf);
	$pdf->Ln(10);
	//	-----------------------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 7, utf8_decode('Ficha del Trabajador'), 0, 1, 'C');
	$pdf->Ln(5);
	//	-----------------------------------
}

function trabajadores_ficha_banda($pdf, $banda) {
	$pdf->SetFont('Arial', 'BI', 10);
	$pdf->SetFillColor(150, 150, 150); $pdf->SetTextColor(240, 240, 240);
	
	if ($banda == "DATOS") $pdf->Cell(200, 5, utf8_decode('Datos Básicos'), 0, 1, 'C', 1);
	elseif ($banda == "CUENTAS") {
		$pdf->Cell(200, 5, utf8_decode('Cuentas Bancarias'), 0, 1, 'C', 1);
		
		$pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(45, 5, 'Nro. Cuenta', 0, 0, 'L', 1);
		$pdf->Cell(85, 5, 'Banco', 0, 0, 'L', 1);
		$pdf->Cell(35, 5, 'Tipo', 0, 0, 'L', 1);
		$pdf->Cell(35, 5, 'Motivo', 0, 0, 'L', 1);
		$pdf->Ln(5);
	}
	elseif ($banda == "CARGA") {
		$pdf->Cell(200, 5, utf8_decode('Carga Familiar'), 0, 1, 'C', 1);
		
		$pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(20, 5, utf8_decode('Cédula'), 0, 0, 'R', 1);
		$pdf->Cell(120, 5, 'Apellidos y Nombres', 0, 0, 'L', 1);
		$pdf->Cell(25, 5, 'F. Nacimiento', 0, 0, 'C', 1);
		$pdf->Cell(35, 5, 'Parentesco', 0, 0, 'L', 1);
		$pdf->Ln(5);
	}
	elseif ($banda == "ESTUDIOS") {
		$pdf->Cell(200, 5, utf8_decode('Instrucción Académica'), 0, 1, 'C', 1);
		
		$pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(40, 5, 'Nivel', 0, 0, 'L', 1);
		$pdf->Cell(65, 5, utf8_decode('Profesión'), 0, 0, 'L', 1);
		$pdf->Cell(65, 5, utf8_decode('Mensión'), 0, 0, 'L', 1);
		$pdf->Cell(20, 5, utf8_decode('Egreso'), 0, 0, 'C', 1);
		$pdf->Cell(10, 5, 'Cons.', 0, 0, 'C', 1);
		$pdf->Ln(5);
	}
	elseif ($banda == "EXPERIENCIA") {
		$pdf->Cell(200, 5, utf8_decode('Experiencia Laboral'), 0, 1, 'C', 1);
		
		$pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(60, 5, 'Empresa', 0, 0, 'L', 1);
		$pdf->Cell(40, 5, 'Desde', 0, 0, 'C', 1);
		$pdf->Cell(40, 5, 'Hasta', 0, 0, 'C', 1);
		$pdf->Cell(60, 5, 'Cargo', 0, 0, 'C', 1);
		$pdf->Ln(5);
	}
	elseif ($banda == "MOVIMIENTO") {
		$pdf->Cell(200, 5, utf8_decode('Movimientos'), 0, 1, 'C', 1);
		
		$pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(30, 5, 'Fecha', 0, 0, 'C', 1);
		$pdf->Cell(40, 5, 'Tipo', 0, 0, 'L', 1);
		$pdf->Cell(130, 5, utf8_decode('Justificación'), 0, 0, 'L', 1);
		$pdf->Ln(5);
	}
	elseif ($banda == "PERMISOS") {
		$pdf->Cell(200, 5, utf8_decode('Permisos'), 0, 1, 'C', 1);
		
		$pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(30, 5, 'Desde', 0, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Hasta', 0, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Total', 0, 0, 'C', 1);
		$pdf->Cell(75, 5, 'Motivo', 0, 0, 'L', 1);
		$pdf->Cell(15, 5, 'Remu.', 0, 0, 'C', 1);
		$pdf->Cell(15, 5, 'B.Alim.', 0, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Just.', 0, 0, 'C', 1);
		$pdf->Ln(5);
	}
	$pdf->Ln(2);
	$pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
}















function certificacion_rrhh_viaticos($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo) {
	if ($numero=="") $numero="En Elaboracion";
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	$pdf->Image('../../../imagenes/logo_gob.jpg', 5, 5, 60, 12, '', '');
	//
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
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 6, utf8_decode('CERTIFICACION DE COMPROMISO'), 0, 1, 'C');
	$pdf->Cell(200, 6, utf8_decode($tipo), 0, 1, 'C');
	/////////////////////////////
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(80, 5, 'BENEFICIARIO', 1, 0, 'C', 1);
	$pdf->Cell(90, 5, 'UNIDAD EJECUTORA', 1, 0, 'C', 1);
	$pdf->Cell(35, 5, 'REQUISICION', 1, 0, 'C', 1);
	//
	list($a, $m, $d)=SPLIT( '[/.-]', $frequisicion); $frequisicion=$d."/".$m."/".$a;
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 9);	
	$h=8; $y=37; $l=4;
	$x1=10.00125; $w1=80; $pdf->Rect($x1-5, $y, $w1, $h); 
	$x2=90.00125; $w2=90; $pdf->Rect($x2-5, $y, $w2, $h);
	$x3=180.00125; $w3=15; $pdf->Rect($x3-5, $y, $w3, $h);
	$x4=195.00125; $w4=20; $pdf->Rect($x4-5, $y, $w4, $h);
	// 
	$nombre=substr ($nombre, 0, 39);
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
	$pdf->MultiCell(200, 4, utf8_decode('Justificación: '.$justificacion), 0, 1, 'L', 1);
	$pdf->Rect(5, 45, 205, 24); 
	
	$pdf->SetY(70);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(10, 5, 'ITEM', 1, 0, 'C', 1);
	$pdf->Cell(15, 5, 'CANT.', 1, 0, 'C', 1);
	$pdf->Cell(15, 5, 'UNIDAD', 1, 0, 'C', 1);
	$pdf->Cell(95, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Cell(35, 5, 'P.UNITARIO', 1, 0, 'C', 1);
	$pdf->Cell(35, 5, 'TOTAL', 1, 0, 'C', 1);
	$pdf->Ln();
	/////////////////////////////
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8); 
	$pdf->SetXY(10, 235); $pdf->Cell(65, 5, 'RECURSOS HUMANOS', 1, 1, 'C'); 
	$pdf->SetXY(75, 235); $pdf->Cell(70, 5, 'ADMINISTRACION', 1, 1, 'C'); 
	$pdf->SetXY(145, 235); $pdf->Cell(65, 5, 'PRESUPUESTO', 1, 1, 'C');
	
	$pdf->Rect(10, 240, 65, 30); $pdf->Rect(75, 240, 70, 30); $pdf->Rect(145, 240, 65, 30); 
	
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY(10, 239); $pdf->Cell(50, 8, 'Nombre y Apellido:', 0, 1, 'L'); 
	$pdf->SetXY(75, 239); $pdf->Cell(50, 8, 'Nombre y Apellido:', 0, 1, 'L'); 
	$pdf->SetXY(145, 239); $pdf->Cell(50, 8, 'Nombre y Apellido:', 0, 1, 'L'); 
	$pdf->SetXY(10, 253); $pdf->Cell(30, 8, 'Firma:', 0, 1, 'L'); 
	$pdf->SetXY(75, 253); $pdf->Cell(30, 8, 'Firma:', 0, 1, 'L'); 
	$pdf->SetXY(145, 253); $pdf->Cell(30, 8, 'Firma:', 0, 1, 'L'); 
	$pdf->SetXY(10, 263); $pdf->Cell(20, 8, 'Fecha:', 0, 1, 'L');
	$pdf->SetXY(75, 263); $pdf->Cell(20, 8, 'Fecha:', 0, 1, 'L');
	$pdf->SetXY(145, 263); $pdf->Cell(20, 8, 'Fecha:', 0, 1, 'L');
	//	-----------------------------------
	$sql="SELECT rh.primero_rrhh, rh.ci_primero_rrhh FROM configuracion_rrhh rh";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) {
		$field=mysql_fetch_array($query);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8); 
		$pdf->SetXY(15, 245); $pdf->Cell(65, 5, $field['primero_rrhh']);
		$pdf->SetXY(15, 250); $pdf->Cell(65, 5, $field['ci_primero_rrhh']);
	}
	//	-----------------------------------
	$sql="SELECT rh.primero_administracion, rh.ci_primero_administracion FROM configuracion_administracion rh";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) {
		$field=mysql_fetch_array($query);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8); 
		$pdf->SetXY(80, 245); $pdf->Cell(65, 5, $field['primero_administracion']);
		$pdf->SetXY(80, 250); $pdf->Cell(65, 5, $field['ci_primero_administracion']);
	}
	//	-----------------------------------
	$sql="SELECT rh.primero_presupuesto, rh.ci_primero_presupuesto FROM configuracion_presupuesto rh";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) {
		$field=mysql_fetch_array($query);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8); 
		$pdf->SetXY(150, 245); $pdf->Cell(65, 5, $field['primero_presupuesto']);
		$pdf->SetXY(150, 250); $pdf->Cell(65, 5, $field['ci_primero_presupuesto']);
	}
}







function anexo_certificacion_rrhh_viaticos($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $modulo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	$pdf->Image('../../../imagenes/logo_gob.jpg', 5, 5, 30, 20, '', '');
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(15, 5); $pdf->Cell(120, 5, '', 0, 0, 'L');
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 5,utf8_decode('Número: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 5, $numero, 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(15, 5); $pdf->Cell(120, 5, '', 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 5, 'Fecha: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $fecha=$d."/".$m."/".$a;
	$pdf->Cell(35, 5, $fecha, 0, 1, 'L');	 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(185, 5, utf8_decode('Página: '), 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 5, $pag, 0, 1, 'L'); 
	/////////////////////////////
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(200, 8, 'Anexo', 0, 1, 'C');
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 6, utf8_decode('CERTIFICACION DE COMPROMISO'), 0, 1, 'C');
	$pdf->Cell(200, 6, utf8_decode($tipo), 0, 1, 'C');
	/////////////////////////////
	$pdf->Ln();
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 5, 'CAT.PROGR.', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'PARTIDA', 1, 0, 'C', 1);
	$pdf->Cell(110, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Cell(45, 5, 'MONTO', 1, 0, 'C', 1);
	$pdf->Ln();
	/////////////////////////////
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8); 
	$pdf->SetXY(10, 235); $pdf->Cell(65, 5, 'RECURSOS HUMANOS', 1, 1, 'C'); 
	$pdf->SetXY(75, 235); $pdf->Cell(70, 5, 'ADMINISTRACION', 1, 1, 'C'); 
	$pdf->SetXY(145, 235); $pdf->Cell(65, 5, 'PRESUPUESTO', 1, 1, 'C');
	
	$pdf->Rect(10, 240, 65, 30); $pdf->Rect(75, 240, 70, 30); $pdf->Rect(145, 240, 65, 30); 
	
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY(10, 239); $pdf->Cell(50, 8, 'Nombre y Apellido:', 0, 1, 'L'); 
	$pdf->SetXY(75, 239); $pdf->Cell(50, 8, 'Nombre y Apellido:', 0, 1, 'L'); 
	$pdf->SetXY(145, 239); $pdf->Cell(50, 8, 'Nombre y Apellido:', 0, 1, 'L'); 
	$pdf->SetXY(10, 253); $pdf->Cell(30, 8, 'Firma:', 0, 1, 'L'); 
	$pdf->SetXY(75, 253); $pdf->Cell(30, 8, 'Firma:', 0, 1, 'L'); 
	$pdf->SetXY(145, 253); $pdf->Cell(30, 8, 'Firma:', 0, 1, 'L'); 
	$pdf->SetXY(10, 263); $pdf->Cell(20, 8, 'Fecha:', 0, 1, 'L');
	$pdf->SetXY(75, 263); $pdf->Cell(20, 8, 'Fecha:', 0, 1, 'L');
	$pdf->SetXY(145, 263); $pdf->Cell(20, 8, 'Fecha:', 0, 1, 'L');
	//	-----------------------------------
	$sql="SELECT rh.primero_rrhh, rh.ci_primero_rrhh FROM configuracion_rrhh rh";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) {
		$field=mysql_fetch_array($query);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8); 
		$pdf->SetXY(15, 245); $pdf->Cell(65, 5, $field['primero_rrhh']);
		$pdf->SetXY(15, 250); $pdf->Cell(65, 5, $field['ci_primero_rrhh']);
	}
	//	-----------------------------------
	$sql="SELECT rh.primero_administracion, rh.ci_primero_administracion FROM configuracion_administracion rh";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) {
		$field=mysql_fetch_array($query);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8); 
		$pdf->SetXY(80, 245); $pdf->Cell(65, 5, $field['primero_administracion']);
		$pdf->SetXY(80, 250); $pdf->Cell(65, 5, $field['ci_primero_administracion']);
	}
	//	-----------------------------------
	$sql="SELECT rh.primero_presupuesto, rh.ci_primero_presupuesto FROM configuracion_presupuesto rh";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) {
		$field=mysql_fetch_array($query);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8); 
		$pdf->SetXY(150, 245); $pdf->Cell(65, 5, $field['primero_presupuesto']);
		$pdf->SetXY(150, 250); $pdf->Cell(65, 5, $field['ci_primero_presupuesto']);
	}
}

//	Lista de Trabajadores...
function trabajadores_listado($pdf) {
    //	NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //	--------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //	--------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'Lista de Trabajadores', 0, 1, 'C');
    //	--------------------
    $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(20, 5, 'Cedula', 1, 0, 'C', 1);
    $pdf->Cell(175, 5, 'Nombre y Apellido', 1, 0, 'C', 1);
    $pdf->Ln();
    $pdf->SetAligns(array('R', 'L'));
    $pdf->SetWidths(array(20, 175));
}

//	Constancia de Trabajadores...
function trabajadores_constancia($pdf) {
    //	NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //	--------------------
    setLogo($pdf);
    $pdf->Ln(22);
    //	--------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'Constancia de Trabajo', 0, 1, 'C');
    //	--------------------
    $pdf->Ln(6);

}

function head_trabajadores_prestaciones($pdf, $nomina, $estado, $campos, $mes_prestaciones, $anio_prestaciones) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	$pdf->SetMargins(8, 5, 5);
	$pdf->SetAutoPageBreak(1, 5);
	setLogo($pdf);
	$pdf->Ln(10);
	//	-----------------------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 7, utf8_decode('Relación de Prestaciones Acumuladas'), 0, 1, 'C');

	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(200, 4, utf8_decode($nomina), 0, 1, 'C');
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(200, 4, "Estado: ".utf8_decode(strtoupper($estado)), 0, 1, 'L');
	$pdf->Ln(2);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(200, 4, utf8_decode("Hasta el mes/año: ").$mes_prestaciones.' / '.$anio_prestaciones, 0, 1, 'L');
	//	---------------------------------
	$pdf->Ln(2);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	if($campos[0] == 1 && $campos[1] == 1 && $campos[2] == 1){
		$pdf->SetAligns(array('C','C', 'C', 'C', 'C', 'C', 'C'));
		$pdf->SetWidths(array(10, 20, 70, 20, 28, 28, 28));
		$pdf->Row(array('No.',utf8_decode('Cédula'), 'Apellidos y Nombres', 'Fecha de Ingreso', 'Prestaciones Acumuladas', 'Intereses Acumulados', 'Prestaciones + Intereses'));
		$pdf->SetAligns(array('C','R', 'L', 'C', 'R', 'R', 'R'));
	}
	if($campos[0] == 1 && $campos[1] == 0 && $campos[2] == 0){
		$pdf->SetAligns(array('C','C', 'C', 'C', 'C'));
		$pdf->SetWidths(array(10, 20, 126, 20, 28));
		$pdf->Row(array('No.',utf8_decode('Cédula'), 'Apellidos y Nombres', 'Fecha de Ingreso', 'Prestaciones Acumuladas'));
		$pdf->SetAligns(array('C','R', 'L', 'C', 'R'));
	}
	if($campos[0] == 1 && $campos[1] == 1 && $campos[2] == 0){
		$pdf->SetAligns(array('C','C', 'C', 'C', 'C', 'C'));
		$pdf->SetWidths(array(10, 20, 98, 20, 28, 28));
		$pdf->Row(array('No.',utf8_decode('Cédula'), 'Apellidos y Nombres', 'Fecha de Ingreso', 'Prestaciones Acumuladas', 'Intereses Acumulados'));
		$pdf->SetAligns(array('C','R', 'L', 'C', 'R', 'R'));
	}
	if($campos[0] == 1 && $campos[1] == 0 && $campos[2] == 1){
		$pdf->SetAligns(array('C','C', 'C', 'C', 'C', 'C'));
		$pdf->SetWidths(array(10, 20, 98, 20, 28, 28));
		$pdf->Row(array('No.',utf8_decode('Cédula'), 'Apellidos y Nombres', 'Fecha de Ingreso', 'Prestaciones Acumuladas', 'Prestaciones + Intereses'));
		$pdf->SetAligns(array('C','R', 'L', 'C', 'R', 'R'));
	}
	if($campos[0] == 0 && $campos[1] == 1 && $campos[2] == 1){
		$pdf->SetAligns(array('C','C', 'C', 'C', 'C', 'C'));
		$pdf->SetWidths(array(10, 20, 98, 20, 28, 28));
		$pdf->Row(array('No.',utf8_decode('Cédula'), 'Apellidos y Nombres', 'Fecha de Ingreso', 'Intereses Acumulados', 'Prestaciones + Intereses'));
		$pdf->SetAligns(array('C','R', 'L', 'C', 'R', 'R'));
	}
	if($campos[0] == 0 && $campos[1] == 1 && $campos[2] == 0){
		$pdf->SetAligns(array('C','C', 'C', 'C', 'C'));
		$pdf->SetWidths(array(10, 20, 126, 20, 28));
		$pdf->Row(array('No.',utf8_decode('Cédula'), 'Apellidos y Nombres', 'Fecha de Ingreso', 'Intereses Acumulados'));
		$pdf->SetAligns(array('C','R', 'L', 'C', 'R'));
	}
	if($campos[0] == 0 && $campos[1] == 0 && $campos[2] == 1){
		$pdf->SetAligns(array('C','C', 'C', 'C', 'C'));
		$pdf->SetWidths(array(10, 20, 126, 20, 28));
		$pdf->Row(array('No.',utf8_decode('Cédula'), 'Apellidos y Nombres', 'Fecha de Ingreso', 'Prestaciones + Intereses'));
		$pdf->SetAligns(array('C','R', 'L', 'C', 'R'));
	}
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Ln(4);
}
?>