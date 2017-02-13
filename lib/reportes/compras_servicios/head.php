<?php
session_start();
//	Beneficiarios...
function beneficiario($pdf, $tipo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(260, 10, 'Listado de Beneficiarios', 0, 1, 'C');
	//
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(260, 5, $tipo, 0, 1, 'L');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 5, 'RIF/CEDULA', 1, 0, 'C', 1);
	$pdf->Cell(70, 5, 'NOMBRE', 1, 0, 'C', 1);
	$pdf->Cell(70, 5, 'RESPONSABLE', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'TELEFONOS', 1, 0, 'C', 1);
	$pdf->Cell(65, 5, 'DIRECCION FISCAL', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('C', 'L', 'L', 'L', 'L'));
	$pdf->SetWidths(array(25, 70, 70, 25, 65));
}

//	Documentos Entregados...
function dbeneficiario($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	/////////////////////////////
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Documentos Entregados', 0, 1, 'C');
}

//	Ficha de Beneficiarios...
function ficha_beneficiarios($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Ficha de Beneficiarios', 0, 1, 'C');
}

//	Relacion Documentacion Vencida...
function relacion_documentacion_vencida($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Relación Documentación Vencida', 0, 1, 'C');
}

//	Unidades de Medida...
function unidadm($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Unidades de Medida', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(150, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Cell(40, 5, 'ABREVIADO', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L', 'C'));
	$pdf->SetWidths(array(150, 40));
}

//	Ramos de Materiales...
function ramos($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Ramos de Materiales', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(190, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L'));
	$pdf->SetWidths(array(190));
}

//	Impuestos...
function impuestos($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Impuestos', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(100, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Cell(50, 5, 'SIGLAS', 1, 0, 'C', 1);
	$pdf->Cell(40, 5, 'PORCENTAJE', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L', 'C', 'C'));
	$pdf->SetWidths(array(100, 50, 40));
}

//	Catalogo de Materiales...
function catalogo_materiales($pdf, $head, $tipo, $ramo, $titulo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, utf8_decode($titulo), 0, 1, 'C');
	if ($tipo!="") {
		if ($head==2) $pdf->Cell(25, 5);
		if ($head==4) $pdf->Cell(15, 5);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(30, 4, 'Tipo: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($tipo), 0, 1, 'L');
	}
	if ($ramo!="") {
		if ($head==2) $pdf->Cell(25, 5);
		if ($head==4) $pdf->Cell(15, 5);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(30, 4, utf8_decode('Ramo del Material: '), 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($ramo), 0, 1, 'L');
	}
	//---------------------------------------------------
	if ($head==1) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(35, 5, utf8_decode('Código'), 1, 0, 'C', 1);
		$pdf->Cell(70, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Unidad', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Tipo', 1, 0, 'C', 1);
		$pdf->Cell(55, 5, 'Ramo del Material', 1, 1, 'C', 1);
	}
	//---------------------------------------------------
	elseif ($head==2) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(25, 5);
		$pdf->Cell(35, 5, utf8_decode('Código'), 1, 0, 'C', 1);
		$pdf->Cell(90, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Unidad', 1, 1, 'C', 1);
	}
	elseif ($head==3) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(35, 5, utf8_decode('Código'), 1, 0, 'C', 1);
		$pdf->Cell(75, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Unidad', 1, 0, 'C', 1);
		$pdf->Cell(65, 5, 'Ramo ', 1, 1, 'C', 1);
	}
	elseif ($head==4) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(15, 5);
		$pdf->Cell(35, 5, utf8_decode('Código'), 1, 0, 'C', 1);
		$pdf->Cell(95, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Unidad', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Tipo ', 1, 1, 'C', 1);
	}
}

//	Catalogo de Materiales...
function catalogo_materiales2($pdf, $head, $tipo, $ramo, $titulo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, utf8_decode($titulo), 0, 1, 'C');
	if ($tipo!="") {
		if ($head==2 || $head==3) $pdf->Cell(25, 5);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(30, 4, 'Tipo: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($tipo), 0, 1, 'L');
	}
	if ($ramo!="") {
		if ($head==2 || $head==3) $pdf->Cell(25, 5);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(30, 4, utf8_decode('Ramo del Material: '), 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($ramo), 0, 1, 'L');
	}
	//---------------------------------------------------
	if ($head==1 || $head==4) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(35, 5, utf8_decode('Código'), 1, 0, 'C', 1);
		$pdf->Cell(80, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'Tipo', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Afecta', 1, 1, 'C', 1);
	}
	//---------------------------------------------------
	elseif ($head==2 || $head==3) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(25, 5);
		$pdf->Cell(35, 5, utf8_decode('Código'), 1, 0, 'C', 1);
		$pdf->Cell(90, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Afecta', 1, 1, 'C', 1);
	}
}

//	SNC Actividades...
function sncactividad($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'SNC Actividades', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(150, 5, 'ACTIVIDAD', 1, 0, 'C', 1);
	$pdf->Cell(40, 5, 'SIGLA', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L', 'C'));
	$pdf->SetWidths(array(150, 40));
}

//	SNC Familia...
function sncfamilia($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'SNC Familia Actividad', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(60, 5, 'ACTIVIDAD', 1, 0, 'C', 1);
	$pdf->Cell(100, 5, 'FAMILIA', 1, 0, 'C', 1);
	$pdf->Cell(30, 5, 'CODIGO', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L', 'L', 'C'));
	$pdf->SetWidths(array(60, 100, 30));
}

//	SNC Grupo...
function sncgrupo($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'SNC Grupo Actividad', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(40, 5, 'CODIGO', 1, 0, 'C', 1);
	$pdf->Cell(150, 5, 'GRUPO', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('C', 'L'));
	$pdf->SetWidths(array(40, 150));
}

//	SNC Detalle...
function sncdetalle($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'SNC Detalle Actividad', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(60, 5, 'GRUPO ACTIVIDAD', 1, 0, 'C', 1);
	$pdf->Cell(100, 5, 'DETALLE', 1, 0, 'C', 1);
	$pdf->Cell(30, 5, 'CODIGO', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L', 'L', 'C'));
	$pdf->SetWidths(array(60, 100, 30));
}

//	SNC...
function snc($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Catálogo de Clasificación de Compras del Estado', 0, 1, 'C');
	$pdf->SetAligns(array('L'));
	$pdf->SetWidths(array(190));
}

//	Orden de Compra/Servicio...
function filtro_orden_compra_servicio($pdf, $head, $proveedor, $categoria, $desde, $hasta, $tipo, $articulo, $estado) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	if ($_SESSION['modulo'] == 3) $titulo = "Ordenes de Compra/Servicio";
	else $titulo = "Certificaciones de Compromisos";
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, $titulo, 0, 1, 'C');
	if ($proveedor!="") {
		$pdf->SetFont('Arial', '', 6); $pdf->Cell(33, 4, 'Proveedor: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6); $pdf->Cell(162, 4, utf8_decode($proveedor), 0, 1, 'L');
	}
	if ($categoria!="") {
		$pdf->SetFont('Arial', '', 6); $pdf->Cell(33, 4, utf8_decode('Categoría Programática: '), 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6); $pdf->Cell(162, 4, utf8_decode($categoria), 0, 1, 'L');
	}
	if ($desde!="" && $hasta!="") {
		list($d, $m, $a)=SPLIT( '[/.-]', $desde); $fecha_desde=$d."/".$m."/".$a;
		list($d, $m, $a)=SPLIT( '[/.-]', $hasta); $fecha_hasta=$d."/".$m."/".$a;
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
	if ($articulo!="") {
		$pdf->SetFont('Arial', '', 6); $pdf->Cell(33, 4, 'Articulo: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6); $pdf->Cell(162, 4, utf8_decode($articulo), 0, 1, 'L');
	}
	//---------------------------------------------------
	if ($head==1) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(20, 5, 'Nro. C', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Fecha C', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Nro. Factura', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(35, 5, 'Tipo', 1, 0, 'C', 1);
		$pdf->Cell(40, 5, 'Proveedor/Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(35, 5, 'Concepto', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Total', 1, 1, 'C', 1);
	}
	//---------------------------------------------------
	elseif ($head==2) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(25, 5, 'Nro. C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Nro. Factura', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Tipo', 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'Concepto', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
	}
	elseif ($head==3) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(20, 5, 'Nro. C', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Fecha C', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Nro. Factura', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(55, 5, 'Proveedor/Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(55, 5, 'Concepto', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Total', 1, 1, 'C', 1);
	}
	elseif ($head==4) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(25, 5, 'Nro. C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Nro. Factura', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(85, 5, 'Concepto', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
	}
	elseif ($head==5) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(25, 5, 'Nro. C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Nro. Factura', 1, 0, 'C', 1);
		$pdf->Cell(55, 5, 'Proveedor/Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(55, 5, 'Concepto', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
	}
	elseif ($head==6) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(25, 5, 'Nro. C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Nro. Factura', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Tipo', 1, 0, 'C', 1);
		$pdf->Cell(90, 5, 'Concepto', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
	}
	elseif ($head==7) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(25, 5, 'Nro. C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Nro. Factura', 1, 0, 'C', 1);
		$pdf->Cell(55, 5, 'Proveedor/Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(55, 5, 'Concepto', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
	}
	elseif ($head==8) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(25, 5, 'Nro. C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Nro. Factura', 1, 0, 'C', 1);
		$pdf->Cell(55, 5, 'Concepto', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
	}
	//---------------------------------------------------
	elseif ($head==9) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(20, 5, 'Nro. C', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Fecha C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Nro. Factura', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Tipo', 1, 0, 'C', 1);
		$pdf->Cell(45, 5, 'Proveedor/Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(45, 5, 'Concepto', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'Precio U.', 1, 1, 'C', 1);
	}
}

//	Totales por Proveedor...
function totales_por_proveedor($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Totales por Proveedor', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(30, 5, 'RIF', 1, 0, 'C', 1);
	$pdf->Cell(120, 5, 'RAZON SOCIAL', 1, 0, 'C', 1);
	$pdf->Cell(40, 5, 'TOTAL Bs.', 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetAligns(array('L', 'L', 'R'));
	$pdf->SetWidths(array(30, 120, 40));
}


//	Solicitud de Cotizaciones...
function filtro_solicitud_cotizacion($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Solicitudes de Cotización', 0, 1, 'C');
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

//	Requisicion de Compras/Servicios...
function filtro_requisicion($pdf, $head, $proveedor, $categoria, $desde, $hasta, $tipo, $articulo, $estado) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Requisiciones de Compra/Servicio', 0, 1, 'C');
	if ($proveedor!="") {
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, 'Proveedor: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($proveedor), 0, 1, 'L');
	}
	if ($categoria!="") {
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, utf8_decode('Categoría Programática: '), 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($categoria), 0, 1, 'L');
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
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, 'Articulo: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($articulo), 0, 1, 'L');
	}
	//---------------------------------------------------
	if ($head==1) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(40, 5, 'Tipo', 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'Proveedor', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
	}
	//---------------------------------------------------
	elseif ($head==2) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(40, 5, 'Tipo', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
	}
	elseif ($head==3) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(100, 5, 'Proveedor', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
	}
	elseif ($head==4) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
	}
	elseif ($head==5) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(40, 5, 'Tipo', 1, 0, 'C', 1);
		$pdf->Cell(80, 5, 'Proveedor', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);

	}
	elseif ($head==6) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'Tipo', 1, 1, 'C', 1);
		$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
	}
	elseif ($head==7) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(100, 5, 'Proveedor', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
	}
	elseif ($head==8) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
	}
	//---------------------------------------------------
	elseif ($head==9) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
		$pdf->Cell(40, 5, 'Tipo', 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'Proveedor', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'Precio U.', 1, 1, 'C', 1);
	}
}

//	(Modulo) Orden de Compra/Servicio...
function ordencs($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo, $documento, $reversa) {
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
	if ($modulo=="3" || $modulo == "16") $pdf->Cell(200, 12, 'ORDEN DE '.$tipo, 0, 1, 'C');
	else if ($modulo=="4" || $modulo=="2" || $modulo=="12" || $modulo=="1" || $modulo=="14" || $modulo=="19") $pdf->Cell(200, 12, utf8_decode('CERTIFICACION DE '.$tipo), 0, 1, 'C');
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
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, utf8_decode($nombre), 0, 'L'); 
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
	if ($modulo == "16") {
		$pdf->Cell(25, 5, 'FACTURA', 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'PROVEEDOR', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'FECHA', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'BASE', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'EXENTO', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'IMPUESTO', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'TOTAL', 1, 0, 'C', 1);
	} else {
		$pdf->Cell(10, 5, 'ITEM', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'CANT.', 1, 0, 'C', 1);
		$pdf->Cell(15, 5, 'UNIDAD', 1, 0, 'C', 1);
		$pdf->Cell(95, 5, 'DESCRIPCION', 1, 0, 'C', 1);
		$pdf->Cell(35, 5, 'P.UNITARIO', 1, 0, 'C', 1);
		$pdf->Cell(35, 5, 'TOTAL', 1, 0, 'C', 1);
	}
	$pdf->Ln();
	/////////////////////////////
	getFootOrdenCompra($pdf, $modulo, $tipo, $documento, $reversa);
}

//	(Modulo) Anexo Orden de Compra/Servicio...
function anexoordencs($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo, $documento, $reversa) {
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
	if ($modulo=="3") $pdf->Cell(200, 12, 'ORDEN DE '.$tipo, 0, 1, 'C');
	else if ($modulo=="4" || $modulo=="2" || $modulo=="12" || $modulo=="1" || $modulo=="14") $pdf->Cell(200, 12, utf8_decode('CERTIFICACION DE '.$tipo), 0, 1, 'C');
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
	getFootOrdenCompra($pdf, $modulo, $tipo, $documento, $reversa);
}




function anexorequisicion($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag) {
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
	if ($modulo=="3") $pdf->Cell(200, 12, 'REQUISICION DE '.$tipo, 0, 1, 'C');
	else if ($modulo=="4" || $modulo=="2" || $modulo=="12" || $modulo=="1" || $modulo=="14") $pdf->Cell(200, 12, utf8_decode('CERTIFICACION DE '.$tipo), 0, 1, 'C');
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
	getFootOrdenCompra($pdf, $modulo, $tipo, $documento, $reversa);
}


//	(Modulo) Requisiciones...
function requisicion($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar) {
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
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(200, 12, utf8_decode('Requisición de '.$tipo), 0, 1, 'C');
	/////////////////////////////
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(100, 5, 'PROVEEDOR', 1, 0, 'C', 1);
	$pdf->Cell(105, 5, 'UNIDAD SOLICITANTE', 1, 1, 'C', 1);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 9);	
	$h=8; $y=37; $l=4;
	$x1=10.00125; $w1=100; $pdf->Rect($x1-5, $y, $w1, $h); 
	$x2=110.00125; $w2=105; $pdf->Rect($x2-5, $y, $w2, $h);
	// 
	$nombre=substr ($nombre, 0, 55);
	$pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, utf8_decode($nombre), 0, 'L'); 
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x1-5, $y); $hf=getH($pdf, $l, $w1, $nombre); $pdf->Ln($hf); $pdf->MultiCell($w1, $l, 'Rif.: '.$rif, 0, 'L'); 
	$pdf->SetXY($x2-5, $y); $pdf->MultiCell($w2, $l, utf8_decode($despachar), 0, 'L'); 
	$hf=getH($pdf, $l, $w2, $despachar); $pdf->Ln($hf);	$pdf->SetXY($x2-5, $y+$hf); $pdf->MultiCell($w2, $l, $unidad, 0, 'L');
	//$pdf->Ln($l);
	/////////////////////////////
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8); 
	$pdf->MultiCell(200, 4, utf8_decode("Justificación: ".$justificacion), 0, 1, 'L', 1);
	$pdf->Rect(5, 45, 205, 14); 
	
	$pdf->SetY(60);
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
	$pdf->SetFont('Arial', 'B', 9);
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 9);
	$h=5; $y=245; $l=4; $x1=5.00125; $w1=103; $pdf->Rect($x1, $y, $w1, $h); $pdf->SetXY($x1, $y+1); $pdf->MultiCell($w1, $l, 'Unidad Solicitante', 0, 'C');
	$h=5; $y=245; $l=4; $x1=108.00125; $w1=102; $pdf->Rect($x1, $y, $w1, $h); $pdf->SetXY($x1, $y+1); $pdf->MultiCell($w1, $l, utf8_decode('Division de Compras'), 0, 'C');
	//
	$h=25; $y=250; $l=4; $x1=5.00125; $w1=103; $pdf->Rect($x1, $y, $w1, $h);
	$pdf->SetFont('Arial', '', 8); $pdf->SetXY($x1, $y+1); $pdf->MultiCell($w1, $l, 'Nombre y Apellido:', 0, 'L'); 
	$pdf->SetFont('Arial', '', 9); $pdf->SetXY($x1, $y+13); $pdf->MultiCell($w1, $l, 'Firma:', 0, 'L');
	$pdf->SetFont('Arial', '', 9); $pdf->SetXY($x1, $y+20); $pdf->MultiCell($w1, $l, 'Fecha:', 0, 'L');	 
	$pdf->SetFont('Arial', 'B', 9); $pdf->SetXY($x1+20, $y+20); $pdf->MultiCell($w1, $l, '    /              /', 0, 'L');
	//
	$h=25; $y=250; $l=4; $x1=108.00125; $w1=102; $pdf->Rect($x1, $y, $w1, $h);
	$pdf->SetFont('Arial', '', 8); $pdf->SetXY($x1, $y+1); $pdf->MultiCell($w1, $l, 'Nombre y Apellido:', 0, 'L'); 
	$pdf->SetFont('Arial', '', 9); $pdf->SetXY($x1, $y+13); $pdf->MultiCell($w1, $l, 'Firma:', 0, 'L');
	$pdf->SetFont('Arial', '', 9); $pdf->SetXY($x1, $y+20); $pdf->MultiCell($w1, $l, 'Fecha:', 0, 'L'); 	 
	$pdf->SetFont('Arial', 'B', 9); $pdf->SetXY($x1+20, $y+20); $pdf->MultiCell($w1, $l, '    /              /', 0, 'L');
	//	-------------------------------------------
	$sql="SELECT rh.primero_compras, rh.ci_primero_compras FROM configuracion_compras rh";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) {
		$field=mysql_fetch_array($query);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8); 
		$pdf->SetXY(110, 255); $pdf->Cell(65, 5, $field['primero_compras']);
		$pdf->SetXY(110, 259); $pdf->Cell(65, 5, $field['ci_primero_compras']);
	}
}

//	(Modulo) Remision de Documentos...
function remitirdoc($pdf, $numero, $fecha, $para, $de, $asunto, $justificacion, $ndocs, $pag) {
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
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(205, 16, utf8_decode('Remisión de Documentos'), 0, 1, 'C');
	/////////////////////////////
	$sql="SELECT denominacion FROM dependencias WHERE iddependencia='".$para."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) { $field=mysql_fetch_array($query); $para=$field[0]; }
	$sql="SELECT denominacion FROM dependencias WHERE iddependencia='".$de."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) { $field=mysql_fetch_array($query); $de=$field[0]; }
	/////////////////////////////
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(30, 7, 'Para:', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 10); $pdf->MultiCell(175, 7, utf8_decode($para), 0, 1, 'L');
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(30, 7, 'De:', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 10); $pdf->MultiCell(175, 7, utf8_decode($de), 0, 1, 'L');
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(30, 7, 'Asunto:', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 10); $pdf->MultiCell(175, 7, utf8_decode($asunto), 0, 1, 'L');
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(30, 7, 'Nro. Doc. Anexos:', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 10); $pdf->MultiCell(175, 7, $ndocs, 0, 1, 'L');
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(30, 7, '', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); $pdf->MultiCell(175, 7, utf8_decode($justificacion), 0, 1, 'L');
	$pdf->Ln(5);
	/////////////////////////////
	$pdf->SetFont('Arial', 'BU', 14);
	$pdf->Cell(205, 16, 'Documentos Anexos', 0, 1, 'C');
	/////////////////////////////	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 5, 'Nro. Doc.', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'Fecha', 1, 0, 'C', 1);
	$pdf->Cell(120, 5, 'Beneficiario', 1, 0, 'C', 1);
	$pdf->Cell(35, 5, 'Monto', 1, 1, 'C', 1);
	$pdf->Ln(1);
}

function remitirdoccon($pdf, $numero, $fecha, $pag) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	setLogo($pdf);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(15, 5); $pdf->Cell(120, 5, '', 0, 0, 'L');
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(50, 5, 'Numero: ', 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(35, 5, $numero, 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(15, 5); $pdf->Cell(120, 5, '', 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(50, 5, 'Fecha: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 9);
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $fecha=$d."/".$m."/".$a;
	$pdf->Cell(35, 5, $fecha, 0, 1, 'L');	 
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(185, 5, 'Página: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(35, 5, $pag, 0, 1, 'L'); 
	/////////////////////////////
	$pdf->SetFont('Arial', 'BU', 14);
	$pdf->Cell(205, 16, 'Documentos Anexos', 0, 1, 'C');
	/////////////////////////////	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 5, 'Nro. Doc.', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'Fecha', 1, 0, 'C', 1);
	$pdf->Cell(120, 5, 'Beneficiario', 1, 0, 'C', 1);
	$pdf->Cell(35, 5, 'Monto', 1, 1, 'C', 1);
}

//	Ordenes de Compra por Financiamiento...
function ordenes_compra_por_financiamiento($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	if ($_SESSION['modulo'] == 3) $titulo = "Ordenes de Compra/Servicio";
	else $titulo = "Certificaciones de Compromisos";
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 10, $titulo, 0, 1, 'C');
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(40, 5, 'Nro. Orden', 1, 0, 'C', 1);
	$pdf->Cell(30, 5, 'Fecha Orden', 1, 0, 'C', 1);
	$pdf->Cell(105, 5, 'Beneficiario', 1, 0, 'C', 1);
	$pdf->Cell(30, 5, 'Monto', 1, 1, 'C', 1);
	//	--------------------
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetAligns(array('L', 'C', 'L', 'R'));
	$pdf->SetWidths(array(40, 30, 105, 30));
}

//	(Modulo) Remision de Documentos...
function recibido_y_remitido($pdf, $field_modulo, $fdesde, $fhasta, $tabla, $pag) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetXY(220, 10); 
	$pdf->Cell(34, 5, 'Fecha: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(35, 5, date("d/m/Y"), 0, 1, 'L');
	$pdf->SetXY(220, 14); 
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(34, 5, utf8_decode('Página: '), 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(35, 5, $pag, 0, 1, 'L'); 
	//	--------------------
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(270, 8, utf8_decode('Documentos Remitidos'), 0, 1, 'C');
	//	--------------------
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(20, 5, 'De: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(250, 5, utf8_decode($field_modulo['nombre_modulo']), 0, 1, 'L');
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(20, 5, 'Tipo Documento: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(250, 5, utf8_decode($tabla), 0, 1, 'L');
	if ($fdesde != "" && $fhasta != "")	{
		$pdf->SetFont('Arial', '', 6);
		$pdf->Cell(20, 5, 'Periodo: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(250, 5, $fdesde.' - '.$fhasta, 0, 1, 'L');
	}
	elseif ($fdesde != "") {
		$pdf->SetFont('Arial', '', 6);
		$pdf->Cell(20, 5, 'Desde: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(250, 5, $fdesde, 0, 1, 'L');
	}
	elseif ($fhasta != "") {
		$pdf->SetFont('Arial', '', 6);
		$pdf->Cell(20, 5, 'Hasta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(250, 5, $fhasta, 0, 1, 'L');
	}
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(30, 5, 'Nro. Doc.', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'Fecha Doc.', 1, 0, 'C', 1);
	$pdf->Cell(85, 5, 'Beneficiario', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'Fecha Rem.', 1, 0, 'C', 1);
	$pdf->Cell(85, 5, 'Para', 1, 0, 'C', 1);
	$pdf->Cell(30, 5, 'Estado', 1, 1, 'C', 1);
	$pdf->Ln(1);
}

//	(Modulo) Documentos Recibidos...
function documentos_recibidos($pdf, $field_modulo, $fdesde, $fhasta, $tabla, $pag) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetXY(220, 10); 
	$pdf->Cell(34, 5, 'Fecha: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(35, 5, date("d/m/Y"), 0, 1, 'L');
	$pdf->SetXY(220, 14); 
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(34, 5, utf8_decode('Página: '), 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(35, 5, $pag, 0, 1, 'L'); 
	//	--------------------
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(270, 8, utf8_decode('Documentos Recibidos'), 0, 1, 'C');
	//	--------------------
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(20, 5, 'Para: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(250, 5, utf8_decode($field_modulo['nombre_modulo']), 0, 1, 'L');
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(20, 5, 'Tipo Documento: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(250, 5, utf8_decode($tabla), 0, 1, 'L');
	if ($fdesde != "" && $fhasta != "")	{
		$pdf->SetFont('Arial', '', 6);
		$pdf->Cell(20, 5, 'Periodo: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(250, 5, $fdesde.' - '.$fhasta, 0, 1, 'L');
	}
	elseif ($fdesde != "") {
		$pdf->SetFont('Arial', '', 6);
		$pdf->Cell(20, 5, 'Desde: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(250, 5, $fdesde, 0, 1, 'L');
	}
	elseif ($fhasta != "") {
		$pdf->SetFont('Arial', '', 6);
		$pdf->Cell(20, 5, 'Hasta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(250, 5, $fhasta, 0, 1, 'L');
	}
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(30, 5, 'Nro. Doc.', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'Fecha Doc.', 1, 0, 'C', 1);
	$pdf->Cell(85, 5, 'Beneficiario', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'Fecha Rem.', 1, 0, 'C', 1);
	$pdf->Cell(85, 5, 'De', 1, 0, 'C', 1);
	$pdf->Cell(30, 5, 'Estado', 1, 1, 'C', 1);
	$pdf->Ln(1);
}

function scotizacion($pdf, $pag, $formato, $head) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetFont('Arial', '', 10);
	$pdf->SetXY(150, 15); 
	$pdf->Cell(34, 5, 'Fecha: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(35, 5, date("d/m/Y"), 0, 1, 'L');
	//	--------------------
	$pdf->Ln(10);
	$pdf->SetFont('Arial', 'B', 12);
	if ($formato['formato'] == "Acto Motivado") {
		$pdf->Cell(200, 8, utf8_decode('ACTO MOTIVADO'), 0, 1, 'C');
		$pdf->Ln(10);
	}
	elseif ($formato['formato'] == "Analisis de Cotizaciones") {
		$pdf->Cell(200, 8, utf8_decode('ANALISIS DE COTIZACIONES Nº '.$head['numero']), 0, 1, 'C');
		$pdf->Ln(10);
		$pdf->SetFont('Arial', '', 12);
		$pdf->MultiCell(200, 8, utf8_decode('A continuación se muestra un cuadro comparativo de proveedores que respondieron a la Requisición de la compra anexa.'), 0, 'J');
		
		$pdf->Ln(5);
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
		$pdf->SetWidths(array(20, 20, 25, 25, 20, 20, 20, 20, 25));
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->Row(array('Empresa',
						utf8_decode('Cotización'),
						'Precio',
						'Descuento',
						utf8_decode('Garantía y Serv. en la Zona               '),
						'Calidad',
						'Gto. Flt.',
						'Emp. Local',
						'Experiencia'));
	}
	else {
		$pdf->Cell(200, 8, utf8_decode('CONSULTA DE PRECIOS'), 0, 1, 'C');
	}
}

function scotizacionFooter($pdf, $nitems, $observaciones, $jefe, $cedula) {
	$pdf->SetY(250);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(200, 1, '', 1, 1, 'C', 1);
	$pdf->SetAligns(array('L', 'L'));
	$pdf->SetWidths(array(25, 125));
	$pdf->SetHeight(array(3));
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Row(array($nitems, utf8_decode($observaciones)));
	$pdf->SetXY(160, 250); $pdf->Cell(40, 5, "Firma y Sello", 1, 0, 'C');
	$pdf->SetXY(160, 254); $pdf->Cell(40, 4, $jefe, 1, 0, 'C');
	$pdf->SetXY(160, 257); $pdf->Cell(40, 4, $cedula, 1, 0, 'C');
}

?>