<?php

//	Orden de Pago...
function orden_pago_contabilidad($pdf, $head, $proveedor, $categoria, $anio, $mes, $tipo, $fuente, $estado) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(260, 5, utf8_decode('RELACIÓN DE ORDENES DE PAGO '.$estado), 0, 1, 'C');
	$pdf->Cell(260, 5, utf8_decode($fuente.' MES DE '.$mes.' '.$anio), 0, 1, 'C');
	if ($proveedor!="") {
		$pdf->SetFont('Arial', '', 6); $pdf->Cell(20, 4, 'Beneficiario: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6); $pdf->Cell(162, 4, $proveedor, 0, 1, 'L');
	}
	if ($categoria!="") {
		$pdf->SetFont('Arial', '', 6); $pdf->Cell(35, 4, utf8_decode('Categoría Programática: '), 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6); $pdf->Cell(162, 4, $categoria, 0, 1, 'L');
	}
	if ($tipo!="") {
		$pdf->SetFont('Arial', '', 6); $pdf->Cell(20, 4, 'Concepto: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6); $pdf->Cell(162, 4, $tipo, 0, 1, 'L');
	}
	//---------------------------------------------------
	$pdf->SetFont('Arial', 'B', 8);
	if ($head==1) {
		$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
		$pdf->SetWidths(array(18, 20, 60, 50, 20, 20, 20, 20, 20, 20));
		$pdf->Row(array('Fecha','Nro. O/P','Beneficiario','Concepto','Exento','Sub-Total', 'I.V.A', 'Total','Retenciones','Total Pagado'));
		$pdf->SetAligns(array('C', 'C', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'R'));
		//$pdf->SetHeight(array(4));
		/*
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(18, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Nro. O/P', 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(40, 5, 'Concepto', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Exento', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Sub-Total', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'I.V.A', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Total', 1, 1, 'C', 1);
		$pdf->Cell(18, 5, 'Retenciones', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Total Pagado', 1, 0, 'C', 1);*/
	}
	//---------------------------------------------------
	elseif ($head==2) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(18, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Nro. O/P', 1, 0, 'C', 1);
		$pdf->Cell(40, 5, 'Concepto', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Exento', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Sub-Total', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'I.V.A', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Retenciones', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'I.S.R.L', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Amort. Anticipo', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Total', 1, 1, 'C', 1);
	}
	elseif ($head==3) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(18, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Nro. O/P', 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Exento', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Sub-Total', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'I.V.A', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Retenciones', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'I.S.R.L', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Amort. Anticipo', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Total', 1, 1, 'C', 1);
	}
	elseif ($head==4) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(18, 5, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Nro. O/P', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Exento', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Sub-Total', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'I.V.A', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Retenciones', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'I.S.R.L', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Amort. Anticipo', 1, 0, 'C', 1);
		$pdf->Cell(18, 5, 'Total', 1, 1, 'C', 1);
	}
}

//	Orden de Compra/Servicio...
function orden_compra_servicio_contabilidad($pdf, $head, $proveedor, $categoria, $anio, $mes, $fuente, $estado, $tipo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 5, utf8_decode('ORDENES DE '.strtoupper($tipo).' '.$estado.' MES DE '.$mes.' '.$anio), 0, 1, 'C');
	if ($proveedor!="") {
		$pdf->SetFont('Arial', '', 6); $pdf->Cell(15, 4, 'Beneficiario: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6); $pdf->Cell(162, 4, $proveedor, 0, 1, 'L');
	}
	if ($categoria!="") {
		$pdf->SetFont('Arial', '', 6); $pdf->Cell(25, 4, utf8_decode('Categoría Programática: '), 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 6); $pdf->Cell(162, 4, $categoria, 0, 1, 'L');
	}
	//---------------------------------------------------
	if ($head==1) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(20, 5, 'Nro. O/C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha O/C', 1, 0, 'C', 1);
		$pdf->Cell(130, 5, 'Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Total', 1, 1, 'C', 1);
	}
	//---------------------------------------------------
	elseif ($head==2) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(20, 5, 'Nro. O/C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha O/C', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Total', 1, 1, 'C', 1);
	}
	//---------------------------------------------------
	elseif ($head==3) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(20, 5, 'Nro. O/C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha O/C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Nro. O/P', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha O/P', 1, 0, 'C', 1);
		$pdf->Cell(90, 5, 'Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Total', 1, 1, 'C', 1);
	}
	//---------------------------------------------------
	elseif ($head==4) {
		//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(20, 5, 'Nro. O/C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha O/C', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Nro. O/P', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'Fecha O/P', 1, 0, 'C', 1);
		$pdf->Cell(30, 5, 'Total', 1, 1, 'C', 1);
	}
}

//	Grupos...
function contabilidad_grupo($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(205, 10, utf8_decode('GRUPO'), 0, 1, 'C');
	$pdf->Ln(5);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(240, 240, 240); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetAligns(array('C', 'L'));
	$pdf->SetWidths(array(30, 100));
	$pdf->SetHeight(array(5));
	$pdf->Cell(35, 5); $pdf->Row(array(utf8_decode('Código'), utf8_decode('Denominación')));
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
}

//	Sub Grupo...
function contabilidad_subgrupo($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(205, 10, utf8_decode('SUB-GRUPO'), 0, 1, 'C');
	$pdf->Ln(5);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(240, 240, 240); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetAligns(array('L', 'C', 'L'));
	$pdf->SetWidths(array(70, 20, 70));
	$pdf->SetHeight(array(5));
	$pdf->Cell(20, 5); $pdf->Row(array('Grupo', utf8_decode('Código'), utf8_decode('Denominación')));
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
}

//	Rubro...
function contabilidad_rubro($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(205, 10, utf8_decode('RUBRO'), 0, 1, 'C');
	$pdf->Ln(5);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(240, 240, 240); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetAligns(array('L', 'C', 'L'));
	$pdf->SetWidths(array(70, 20, 70));
	$pdf->SetHeight(array(5));
	$pdf->Cell(20, 5); $pdf->Row(array('Sub-Grupo', utf8_decode('Código'), utf8_decode('Denominación')));
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
}

//	Cuenta...
function contabilidad_cuenta($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(205, 10, utf8_decode('CUENTA'), 0, 1, 'C');
	$pdf->Ln(5);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(240, 240, 240); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetAligns(array('L', 'C', 'L'));
	$pdf->SetWidths(array(70, 30, 70));
	$pdf->SetHeight(array(5));
	$pdf->Cell(15, 5); $pdf->Row(array('Rubro', utf8_decode('Código'), utf8_decode('Denominación')));
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
}

//	Sub Cuenta de P.O...
function contabilidad_subcuenta1($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(205, 10, utf8_decode('SUB-CUENTA DE PRIMER ORDEN'), 0, 1, 'C');
	$pdf->Ln(5);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(240, 240, 240); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetAligns(array('L', 'C', 'L'));
	$pdf->SetWidths(array(70, 30, 70));
	$pdf->SetHeight(array(5));
	$pdf->Cell(15, 5); $pdf->Row(array('Cuenta', utf8_decode('Código'), utf8_decode('Denominación')));
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
}

//	Sub Cuenta de S.O...
function contabilidad_subcuenta2($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(205, 10, utf8_decode('CUENTAS CONTABLES'), 0, 1, 'C');
	$pdf->Ln(1);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(240, 240, 240); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetAligns(array('L', 'L'));
	$pdf->SetWidths(array(28, 170));
	$pdf->SetHeight(array(3));
	$pdf->Cell(5, 5); $pdf->Row(array(utf8_decode('Código'), utf8_decode('Cuenta')));
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
}

//	Desagregacion...
function contabilidad_desagregacion($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	$pdf->Ln(20);
	//	--------------------
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(205, 10, utf8_decode('DESAGREGACION'), 0, 1, 'C');
	$pdf->Ln(5);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(240, 240, 240); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetAligns(array('L', 'C', 'L'));
	$pdf->SetWidths(array(75, 50, 75));
	$pdf->SetHeight(array(5));
	$pdf->Row(array('Sub Cuenta Segundo Orden', utf8_decode('Código'), utf8_decode('Denominación')));
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
}

//	Libro diario Detallado...
function contabilidad_libro_diario($pdf,$idesde,$ihasta,$anio_fiscal) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	
	//	--------------------
	$pdf->SetFont('Arial', 'BU', 12);
	$pdf->Cell(270, 5, utf8_decode('LIBRO DIARIO DETALLADO'), 0, 1, 'C');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(270, 5, utf8_decode('DESDE: ').$idesde.utf8_decode(' HASTA: ').$ihasta, 0, 1, 'C');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(270, 5, utf8_decode('AÑO FISCAL: ').$anio_fiscal, 0, 1, 'C');
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(221, 5, '  ', 0, 0, 'C');
	$pdf->Cell(50, 5, utf8_decode('CUENTAS MAYORES'), 1, 1, 'C');

	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(17, 129, 25, 25, 25, 25, 25));
	$pdf->SetHeight(array(5));
	$pdf->Row(array('Fecha', utf8_decode('Descripcion del Asiento'), utf8_decode('Sub-Division Estadistica'), utf8_decode('Division Estadistica'), utf8_decode('Sub Cuenta'), utf8_decode('Debe'), utf8_decode('Haber')));
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R'));
	$pdf->Ln(3);
}

//	Libro diario Resumido...
function contabilidad_libro_diarioR($pdf,$idesde,$ihasta,$anio_fiscal) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	
	//	--------------------
	$pdf->SetFont('Arial', 'BU', 12);
	$pdf->Cell(270, 5, utf8_decode('LIBRO DIARIO RESUMIDO'), 0, 1, 'C');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(270, 5, utf8_decode('DESDE: ').$idesde.utf8_decode(' HASTA: ').$ihasta, 0, 1, 'C');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(270, 5, utf8_decode('AÑO FISCAL: ').$anio_fiscal, 0, 1, 'C');
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(17, 129, 25, 25, 25, 25, 25));
	$pdf->SetHeight(array(5));
	$pdf->Row(array('Fecha', utf8_decode('Descripcion del Asiento'), utf8_decode('Sub-Division Estadistica'), utf8_decode('Division Estadistica'), utf8_decode('Sub Cuenta'), utf8_decode('Debe'), utf8_decode('Haber')));
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R'));
	$pdf->Ln(3);
}


//	Libro diario Resumido...
function contabilidad_libro_mayor($pdf,$idesde,$ihasta,$anio_fiscal, $codigo, $denominacion) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	
	//	--------------------
	$pdf->SetFont('Arial', 'BU', 12);
	$pdf->Cell(270, 5, utf8_decode('LIBRO MAYOR'), 0, 1, 'C');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(270, 5, utf8_decode('DESDE: ').$idesde.utf8_decode(' HASTA: ').$ihasta, 0, 1, 'C');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(270, 5, utf8_decode('AÑO FISCAL: ').$anio_fiscal, 0, 1, 'C');
	$pdf->SetFont('Arial', '', 10);
	$pdf->Ln(5);
	$pdf->Cell(40, 5, utf8_decode('CUENTA DEL MAYOR: '), 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(230, 5, utf8_decode($codigo).' '.utf8_decode($denominacion), 0, 1, 'L');
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->SetWidths(array(17, 154, 25, 25, 25, 25));
	$pdf->SetHeight(array(5));
	$pdf->Row(array('Fecha', utf8_decode('Descripcion del Asiento'), utf8_decode('Folio'), utf8_decode('Debe'), utf8_decode('Haber'), utf8_decode('Saldo')));
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R'));
	$pdf->Ln(3);
}
?>