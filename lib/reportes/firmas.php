<?php
function setLogo($pdf) {
	$sql = "SELECT * FROM configuracion_logos";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$field = mysql_fetch_array($query);
	if ($field['logo'] != "") {
		if(file_exists("../../../imagenes/".$field['logo'])){
			$ruta = "../../../imagenes/".$field['logo'];
			$pdf->Image($ruta, 5, 5, $field['ancho_primero'], $field['alto_primero'], '', '');
			if ($field['segundo_logo'] != ""){
				if(file_exists("../../../imagenes/".$field['segundo_logo'])){
			 		$pdf->Image("../../../imagenes/".$field['segundo_logo'], 68, 5, $field['ancho_segundo'], $field['alto_segundo'], '', '');
				}
			}
		}
	} else $pdf->Image('../../../imagenes/logo_alcaldia.jpg', 5, 5, 60, 15, '', '');
}

function printLogo($pdf, $x, $y) {
	$sql = "SELECT * FROM configuracion_logos";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$field = mysql_fetch_array($query);
	if ($field['logo'] != "") {
		if(file_exists("../../../imagenes/".$field['logo'])){
			$ruta = "../../../imagenes/".$field['logo'];
			$pdf->Image($ruta, $x, $y, $field['ancho_primero'], $field['alto_primero'], '', '');
			if ($field['segundo_logo'] != ""){
				if(file_exists("../../../imagenes/".$field['segundo_logo'])){
			 		$pdf->Image("../../../imagenes/".$field['segundo_logo'], 68, 5, $field['ancho_segundo'], $field['alto_segundo'], '', '');
				}
			}
		}
	} else $pdf->Image('../../../imagenes/logo_alcaldia.jpg', $x, $y, 60, 15, '', '');
}

function printLogoPayroll($pdf, $x, $y) {
	$sql = "SELECT * FROM configuracion_logos";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$field = mysql_fetch_array($query);
	if ($field['logo'] != "") {
		if(file_exists("../../../imagenes/".$field['logo'])){
			$ruta = "../../../imagenes/".$field['logo'];
			$pdf->Image($ruta, $x, $y, 20, 20, '', '');
			if ($field['segundo_logo'] != ""){
				if(file_exists("../../../imagenes/".$field['segundo_logo'])){
			 		$pdf->Image("../../../imagenes/".$field['segundo_logo'], 68, 5, 20, 20, '', '');
				}
			}
		}
	} else $pdf->Image('../../../imagenes/logo_alcaldia.jpg', $x, $y, 60, 15, '', '');
}

//	FUNCION PARA OBTENER EL NUMERO DE FIRMAS DE UN DOCUMENTO E IMPRIMIRLOS...
function getFootOrdenCompra($pdf, $modulo, $tipo, $documento, $reversa) {
	$sql = "SELECT
				MAX(nf.posicion) AS cuadrantes
			FROM
				nombres_firmas nf
				INNER JOIN configuracion_reportes cr ON (nf.idmodulo = cr.idtipo_documento AND nf.idtipo_reporte = cr.idtipo_reporte AND cr.idtipo_documento = '".$documento."')";
	$query_cuadrantes = mysql_query($sql) or die ($sql.mysql_error());
	$field_cuadrantes = mysql_fetch_array($query_cuadrantes);

	$sql = "SELECT
				cr.*,
				nf.tabla,
				nf.nombre_campo,
				nf.titulo,
				nf.campo_completo,
				nf.posicion,
				d.denominacion AS titulo_dependencia
			FROM 
				configuracion_reportes cr 
				INNER JOIN nombres_firmas nf ON (cr.idtipo_documento = nf.idmodulo) 
				INNER JOIN dependencias d ON (nf.iddependencia = d.iddependencia) 
			WHERE 
				cr.idtipo_documento = '".$documento."' 
			ORDER BY nf.posicion, nf.campo_completo";
	$query_firmas = mysql_query($sql) or die ($sql.mysql_error());
	$rows_firmas = mysql_num_rows($query_firmas);
	$firma = 0;
	$posicion = false;
	while ($field_firmas = mysql_fetch_array($query_firmas)) {
		$firma++;

		//	Imprimo los cuadros y los titulos de las unidades...
		$pdf->SetDrawColor(0, 0, 0);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 9);

		$l=4;
		if ($field_firmas['posicion'] == 1) {
			if ($field_cuadrantes['cuadrantes'] == 1) $w1=205;
			if ($field_cuadrantes['cuadrantes'] == 2) $w1=105;
			if ($field_cuadrantes['cuadrantes'] >= 3) $w1=70;
			$x1=5.00125;
			$y1=210; $y2=233;
			$h1=30;
		}
		elseif ($field_firmas['posicion'] == 2) {
			if ($field_cuadrantes['cuadrantes'] == 1) { $x1=5.00125; $w1=205; }
			if ($field_cuadrantes['cuadrantes'] == 2) { $x1=110.00125; $w1=100; }
			if ($field_cuadrantes['cuadrantes'] >= 3) { $x1=75.00125; $w1=70; }
			$y1=210; $y2=233;
			$h1=30;
		}
		elseif ($field_firmas['posicion'] == 3) {
			if ($field_cuadrantes['cuadrantes'] == 1) { $x1=5.00125; $w1=205; }
			if ($field_cuadrantes['cuadrantes'] == 2) { $x1=110.00125; $w1=100; }
			if ($field_cuadrantes['cuadrantes'] >= 3) { $x1=145.00125; $w1=65; }
			$y1=210; $y2=233;
			$h1=30;
		}
		elseif ($field_firmas['posicion'] == 4) {
			$x1=5.00125;
			$w1=70;
			$y1=240;
			$y2=263;
			$h1=30;
			$posicion4 = true;
		}

		//	1
		$pdf->Rect($x1, $y1, $w1, $h1);
		$pdf->Rect($x1, $y1+4, $w1, 0.1);
		$pdf->SetXY($x1, $y1); $pdf->Cell($w1, $l, utf8_decode(substr($field_firmas['titulo'], 0, 30)), 0, 0, 'L');

		//	2
		$nombre = $field_firmas['nombre_campo'];
		$ci = "ci_".$field_firmas['nombre_campo'];
		$sql = "SELECT ".$nombre.", ".$ci." FROM ".$field_firmas['tabla'];
		$query_nombres = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nombres) != 0) {
			$field_nombres = mysql_fetch_array($query_nombres);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);

			$pdf->SetXY($x1, $y2-6); $pdf->Cell(65, 5, $field_nombres[$nombre]);
			$pdf->SetXY($x1, $y2-6+4); $pdf->Cell(65, 5, $field_nombres[$ci]);
			$pdf->SetXY($x1, $y2-6+8); $pdf->Cell(65, 5, "Fecha:          /        /");
		}

	}
	if ($reversa == 'no'){
	if ($posicion4) {
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Rect(75, 240, 135, 30);
		$pdf->Rect(145, 240, 0.1, 30);
		$pdf->SetXY(75, 240); $pdf->Cell(50, 5, 'PROVEEDOR');
		$pdf->Rect(76, 255, 68, 0.1);
		$pdf->SetXY(75, 255); $pdf->Cell(50, 5, 'Apellidos y Nombres'); 
		$pdf->Rect(76, 265, 30, 0.1);
		$pdf->SetXY(75, 265); $pdf->Cell(25, 5, utf8_decode('Cédula de Identidad')); 
		$pdf->SetXY(122, 261); $pdf->Cell(25, 5, '       /       /'); 	
		$pdf->Rect(120, 265, 24, 0.1);
		$pdf->SetXY(128, 265); $pdf->Cell(25, 5, 'Fecha'); 
		$pdf->SetXY(165, 240); $pdf->Cell(50, 5, 'FIRMA Y SELLO');
	} else {
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Rect(5, 240, 205, 30);
		$pdf->Rect(110, 240, 0.1, 30);
		$pdf->SetXY(5, 240); $pdf->Cell(50, 5, 'PROVEEDOR');
		$pdf->Rect(6, 255, 103, 0.1);
		$pdf->SetXY(5, 255); $pdf->Cell(50, 5, 'Apellidos y Nombres'); 
		$pdf->Rect(6, 265, 30, 0.1);
		$pdf->SetXY(5, 265); $pdf->Cell(25, 5, utf8_decode('Cédula de Identidad')); 
		$pdf->SetXY(87, 261); $pdf->Cell(25, 5, '       /       /'); 	
		$pdf->Rect(85, 265, 24, 0.1);
		$pdf->SetXY(93, 265); $pdf->Cell(25, 5, 'Fecha'); 
		$pdf->SetXY(145, 240); $pdf->Cell(60, 5, 'FIRMA Y SELLO');
	}
	}
	$pdf->SetXY(5, 271); $pdf->SetFont('Arial', '', 10); $pdf->Cell(50, 5, $tipo);
}

//	FUNCION PARA OBTENER EL NUMERO DE FIRMAS DE UN DOCUMENTO E IMPRIMIRLOS...
function getFootOrdenPago($pdf, $modulo, $tipo, $documento) {
	$sql = "SELECT
				MAX(nf.posicion) AS cuadrantes
			FROM
				nombres_firmas nf
				INNER JOIN configuracion_reportes cr ON (nf.idmodulo = cr.idtipo_documento AND nf.idtipo_reporte = cr.idtipo_reporte AND cr.idtipo_documento = '".$documento."')";
	$query_cuadrantes = mysql_query($sql) or die ($sql.mysql_error());
	$field_cuadrantes = mysql_fetch_array($query_cuadrantes);

	$sql = "SELECT
				cr.*,
				nf.tabla,
				nf.nombre_campo,
				nf.titulo,
				nf.campo_completo,
				nf.posicion,
				d.denominacion AS titulo_dependencia
			FROM 
				configuracion_reportes cr
				LEFT JOIN nombres_firmas nf ON (cr.idtipo_documento = nf.idmodulo)
				LEFT JOIN dependencias d ON (nf.iddependencia = d.iddependencia)
			WHERE cr.idtipo_documento = '".$documento."'
			ORDER BY nf.posicion, nf.campo_completo";
	$query_firmas = mysql_query($sql) or die ($sql.mysql_error());
	$rows_firmas = mysql_num_rows($query_firmas);
	$firma = 0;
	$posicion = false;
	while ($field_firmas = mysql_fetch_array($query_firmas)) {
		$firma++;

		//	Imprimo los cuadros y los titulos de las unidades...
		$pdf->SetDrawColor(0, 0, 0);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 9);

		$l=4;
		if ($field_firmas['posicion'] == 1) {
			if ($field_cuadrantes['cuadrantes'] == 1) $w1=205;
			if ($field_cuadrantes['cuadrantes'] == 2) $w1=105;
			if ($field_cuadrantes['cuadrantes'] >= 3) $w1=70;
			$x1=5.00125;
			if ($field_firmas['campo_completo'] == 0) {
				$y1=195; $y2=238;
				$h1=50;
			}
			elseif ($field_firmas['campo_completo'] == 1) {
				$y1=195; $y2=213;
				$h1=25;
			}
			elseif ($field_firmas['campo_completo'] == 2) {
				$y1=220; $y2=238;
				$h1=25;
			}
		}
		elseif ($field_firmas['posicion'] == 2) {
			if ($field_cuadrantes['cuadrantes'] == 1) { $x1=5.00125; $w1=205; }
			if ($field_cuadrantes['cuadrantes'] == 2) { $x1=110.00125; $w1=100; }
			if ($field_cuadrantes['cuadrantes'] >= 3) { $x1=75.00125; $w1=70; }
			if ($field_firmas['campo_completo'] == 0) {
				$y1=195; $y2=238;
				$h1=50;
			}
			elseif ($field_firmas['campo_completo'] == 1) {
				$y1=195; $y2=213;
				$h1=25;
			}
			elseif ($field_firmas['campo_completo'] == 2) {
				$y1=220; $y2=238;
				$h1=25;
			}
		}
		elseif ($field_firmas['posicion'] == 3) {
			if ($field_cuadrantes['cuadrantes'] == 1) { $x1=5.00125; $w1=205; }
			if ($field_cuadrantes['cuadrantes'] == 2) { $x1=110.00125; $w1=100; }
			if ($field_cuadrantes['cuadrantes'] >= 3) { $x1=145.00125; $w1=65; }
			if ($field_firmas['campo_completo'] == 0) {
				$y1=195; $y2=238;
				$h1=50;
			}
			elseif ($field_firmas['campo_completo'] == 1) {
				$y1=195; $y2=213;
				$h1=25;
			}
			elseif ($field_firmas['campo_completo'] == 2) {
				$y1=220; $y2=238;
				$h1=25;
			}
		}
		elseif ($field_firmas['posicion'] == 4) {
			$x1=5.00125;
			$w1=70;
			$y1=245;
			$y2=263;
			$h1=25;
			$posicion4 = true;
		}
		
		//	1
		$pdf->Rect($x1, $y1, $w1, $h1); 
		$pdf->SetXY($x1, $y1); $pdf->Cell($w1, $l, utf8_decode(substr($field_firmas['titulo'], 0, 30)), 0, 0, 'L');
		$pdf->SetXY($x1, $y1+3); $pdf->MultiCell($w1, $l, utf8_decode($field_firmas['titulo_dependencia']), 0, 'L');
		
		//	2
		$nombre = ""; $ci = "";
		$nombre = $field_firmas['nombre_campo'];
		$ci = "ci_".$field_firmas['nombre_campo'];
		if ($nombre != "" && $ci != "") {
			$sql = "SELECT ".$nombre.", ".$ci." FROM ".$field_firmas['tabla'];
			$query_nombres = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_nombres) != 0) {
				$field_nombres = mysql_fetch_array($query_nombres);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->SetXY($x1, $y2-6); $pdf->Cell(65, 5, $field_nombres[$nombre]);
				$pdf->SetXY($x1, $y2-6+4); $pdf->Cell(65, 5, $field_nombres[$ci]);
			}
		}
		$pdf->SetXY($x1, $y2-6+8); $pdf->Cell(65, 5, "Fecha:          /        /");
	}	
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Rect(5, 245, 140, 25);
	$pdf->Rect(75, 245, 0.1, 25);
	$pdf->SetXY(5, 245); $pdf->Cell(50, 5, 'PROVEEDOR / BENEFICIARIO');
	$pdf->Rect(6, 255, 68, 0.1);
	$pdf->SetXY(5, 255); $pdf->Cell(50, 5, 'Apellidos y Nombres'); 
	$pdf->Rect(6, 265, 30, 0.1);
	$pdf->SetXY(5, 265); $pdf->Cell(25, 5, utf8_decode('Cédula de Identidad')); 
	$pdf->SetXY(52, 261); $pdf->Cell(25, 5, '       /       /'); 	
	$pdf->Rect(50, 265, 24, 0.1);
	$pdf->SetXY(58, 265); $pdf->Cell(25, 5, 'Fecha'); 
	$pdf->SetXY(95, 245); $pdf->Cell(50, 5, 'FIRMA Y SELLO');
	
	$pdf->SetXY(5, 271); $pdf->SetFont('Arial', '', 10); $pdf->Cell(50, 5, $tipo);
}

//	FUNCION PARA OBTENER EL NUMERO DE FIRMAS DE UN DOCUMENTO E IMPRIMIRLOS...
function getFootOrdenPagoTesoreria($pdf, $modulo, $tipo, $documento) {
	$sql = "SELECT 
				MAX(nf.posicion) AS cuadrantes
			FROM 
				nombres_firmas nf
				INNER JOIN configuracion_reportes cr ON (nf.idmodulo = cr.idtipo_documento AND nf.idtipo_reporte = cr.idtipo_reporte AND cr.idtipo_documento = '".$documento."')";
	$query_cuadrantes = mysql_query($sql) or die ($sql.mysql_error());
	$field_cuadrantes = mysql_fetch_array($query_cuadrantes);
			
	$sql = "SELECT 
				cr.*, 
				nf.tabla, 
				nf.nombre_campo, 
				nf.titulo, 
				nf.campo_completo, 
				nf.posicion, 
				d.denominacion AS titulo_dependencia 
			FROM 
				configuracion_reportes cr 
				INNER JOIN nombres_firmas nf ON (cr.idtipo_documento = nf.idmodulo) 
				INNER JOIN dependencias d ON (nf.iddependencia = d.iddependencia) 
			WHERE 
				cr.idtipo_documento = '".$documento."' 
			ORDER BY nf.posicion, nf.campo_completo";
	$query_firmas = mysql_query($sql) or die ($sql.mysql_error());
	$rows_firmas = mysql_num_rows($query_firmas);
	$firma = 0;
	$posicion = false;
	while ($field_firmas = mysql_fetch_array($query_firmas)) {
		$firma++;
				
		//	Imprimo los cuadros y los titulos de las unidades...
		$pdf->SetDrawColor(0, 0, 0); 
		$pdf->SetFillColor(255, 255, 255); 
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 9);
		
		$l=4;
		if ($field_firmas['posicion'] == 1) {
			if ($field_cuadrantes['cuadrantes'] == 1) $w1=205;
			if ($field_cuadrantes['cuadrantes'] == 2) $w1=105;
			if ($field_cuadrantes['cuadrantes'] >= 3) $w1=70;
			$x1=5.00125;
			if ($field_firmas['campo_completo'] == 0) {
				$y1=195; $y2=238;
				$h1=50;
			}
			elseif ($field_firmas['campo_completo'] == 1) {
				$y1=195; $y2=213;
				$h1=25;
			}
			elseif ($field_firmas['campo_completo'] == 2) {
				$y1=220; $y2=238;
				$h1=25;
			}
		}
		elseif ($field_firmas['posicion'] == 2) {
			if ($field_cuadrantes['cuadrantes'] == 1) { $x1=5.00125; $w1=205; }
			if ($field_cuadrantes['cuadrantes'] == 2) { $x1=110.00125; $w1=100; }
			if ($field_cuadrantes['cuadrantes'] >= 3) { $x1=75.00125; $w1=70; }
			if ($field_firmas['campo_completo'] == 0) {
				$y1=195; $y2=238;
				$h1=50;
			}
			elseif ($field_firmas['campo_completo'] == 1) {
				$y1=195; $y2=213;
				$h1=25;
			}
			elseif ($field_firmas['campo_completo'] == 2) {
				$y1=220; $y2=238;
				$h1=25;
			}
		}
		elseif ($field_firmas['posicion'] == 3) {
			if ($field_cuadrantes['cuadrantes'] == 1) { $x1=5.00125; $w1=205; }
			if ($field_cuadrantes['cuadrantes'] == 2) { $x1=110.00125; $w1=100; }
			if ($field_cuadrantes['cuadrantes'] >= 3) { $x1=145.00125; $w1=65; }
			if ($field_firmas['campo_completo'] == 0) {
				$y1=195; $y2=238;
				$h1=50;
			}
			elseif ($field_firmas['campo_completo'] == 1) {
				$y1=195; $y2=213;
				$h1=25;
			}
			elseif ($field_firmas['campo_completo'] == 2) {
				$y1=220; $y2=238;
				$h1=25;
			}
		}
		elseif ($field_firmas['posicion'] == 4) {
			$x1=5.00125;
			$w1=70;
			$y1=245;
			$y2=263;
			$h1=25;
			$posicion4 = true;
		}
		
		//	1
		$pdf->Rect($x1, $y1, $w1, $h1); 
		$pdf->SetXY($x1, $y1); $pdf->Cell($w1, $l, utf8_decode(substr($field_firmas['titulo'], 0, 30)), 0, 0, 'L');
		$pdf->SetXY($x1, $y1+3); $pdf->MultiCell($w1, $l, utf8_decode(substr($field_firmas['titulo_dependencia'], 0, 30)), 0, 'L');
		
		//	2
		$nombre = $field_firmas['nombre_campo'];
		$ci = "ci_".$field_firmas['nombre_campo'];
		$sql = "SELECT ".$nombre.", ".$ci." FROM ".$field_firmas['tabla'];
		$query_nombres = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nombres) != 0) {
			$field_nombres = mysql_fetch_array($query_nombres);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8); 
			
			$pdf->SetXY($x1, $y2-6); $pdf->Cell(65, 5, $field_nombres[$nombre]);
			$pdf->SetXY($x1, $y2-6+4); $pdf->Cell(65, 5, $field_nombres[$ci]);
			$pdf->SetXY($x1, $y2-6+8); $pdf->Cell(65, 5, "Fecha:          /        /");
			
			//$pdf->SetXY($x1, $y2); $pdf->Cell(65, 5, $field_nombres[$nombre]);
			//$pdf->SetXY($x1, $y2+3); $pdf->Cell(65, 5, $field_nombres[$ci]);
		}
		
	}
	
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Rect(5, 245, 140, 25);
	$pdf->Rect(75, 245, 0.1, 25);
	$pdf->SetXY(5, 245); $pdf->Cell(50, 5, 'PROVEEDOR / BENEFICIARIO');
	$pdf->Rect(6, 255, 68, 0.1);
	$pdf->SetXY(5, 255); $pdf->Cell(50, 5, 'Apellidos y Nombres'); 
	$pdf->Rect(6, 265, 30, 0.1);
	$pdf->SetXY(5, 265); $pdf->Cell(25, 5, utf8_decode('Cédula de Identidad')); 
	$pdf->SetXY(52, 261); $pdf->Cell(25, 5, '       /       /'); 	
	$pdf->Rect(50, 265, 24, 0.1);
	$pdf->SetXY(58, 265); $pdf->Cell(25, 5, 'Fecha'); 
	$pdf->SetXY(95, 245); $pdf->Cell(50, 5, 'FIRMA Y SELLO');
	
	$pdf->SetXY(5, 271); $pdf->SetFont('Arial', '', 10); $pdf->Cell(50, 5, $tipo);
}

//	FUNCION PARA OBTENER EL NUMERO DE FIRMAS DE UN DOCUMENTO E IMPRIMIRLOS...
function getFootCertificacion($pdf, $modulo, $tipo, $documento) {
	$sql = "SELECT 
				MAX(nf.posicion) AS cuadrantes
			FROM 
				nombres_firmas nf
				INNER JOIN configuracion_reportes cr ON (nf.idmodulo = cr.idtipo_documento AND nf.idtipo_reporte = cr.idtipo_reporte AND cr.idtipo_documento = '".$documento."')";
	$query_cuadrantes = mysql_query($sql) or die ($sql.mysql_error());
	$field_cuadrantes = mysql_fetch_array($query_cuadrantes);
			
	$sql = "SELECT 
				cr.*, 
				nf.tabla, 
				nf.nombre_campo, 
				nf.titulo, 
				nf.campo_completo, 
				nf.posicion, 
				d.denominacion AS titulo_dependencia 
			FROM 
				configuracion_reportes cr 
				INNER JOIN nombres_firmas nf ON (cr.idtipo_documento = nf.idmodulo) 
				INNER JOIN dependencias d ON (nf.iddependencia = d.iddependencia) 
			WHERE 
				cr.idtipo_documento = '".$documento."' 
			ORDER BY nf.posicion, nf.campo_completo";
	$query_firmas = mysql_query($sql) or die ($sql.mysql_error());
	$rows_firmas = mysql_num_rows($query_firmas);
	$firma = 0;
	$posicion = false;
	while ($field_firmas = mysql_fetch_array($query_firmas)) {
		$firma++;
				
		//	Imprimo los cuadros y los titulos de las unidades...
		$pdf->SetDrawColor(0, 0, 0); 
		$pdf->SetFillColor(255, 255, 255); 
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 9);
		
		$l=4;
		if ($field_firmas['posicion'] == 1) {
			if ($field_cuadrantes['cuadrantes'] == 1) $w1=205;
			if ($field_cuadrantes['cuadrantes'] == 2) $w1=105;
			if ($field_cuadrantes['cuadrantes'] >= 3) $w1=70;
			$x1=5.00125;
			$y1=235; $y2=258;
			$h1=30;
		}
		elseif ($field_firmas['posicion'] == 2) {
			if ($field_cuadrantes['cuadrantes'] == 1) { $x1=5.00125; $w1=205; }
			if ($field_cuadrantes['cuadrantes'] == 2) { $x1=110.00125; $w1=100; }
			if ($field_cuadrantes['cuadrantes'] >= 3) { $x1=75.00125; $w1=70; }
			$y1=235; $y2=258;
			$h1=30;
		}
		elseif ($field_firmas['posicion'] == 3) {
			if ($field_cuadrantes['cuadrantes'] == 1) { $x1=5.00125; $w1=205; }
			if ($field_cuadrantes['cuadrantes'] == 2) { $x1=110.00125; $w1=100; }
			if ($field_cuadrantes['cuadrantes'] >= 3) { $x1=145.00125; $w1=65; }
			$y1=235; $y2=258;
			$h1=30;
		}
		
		//	1
		$pdf->Rect($x1, $y1, $w1, $h1);
		$pdf->Rect($x1, $y1+4, $w1, 0.1); 
		$pdf->SetXY($x1, $y1); $pdf->Cell($w1, $l, utf8_decode(substr($field_firmas['titulo'], 0, 30)), 0, 0, 'L');
		
		//	2
		$nombre = $field_firmas['nombre_campo'];
		$ci = "ci_".$field_firmas['nombre_campo'];
		$sql = "SELECT ".$nombre.", ".$ci." FROM ".$field_firmas['tabla'];
		$query_nombres = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nombres) != 0) {
			$field_nombres = mysql_fetch_array($query_nombres);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			
			$pdf->SetXY($x1, $y2-6); $pdf->Cell(65, 5, $field_nombres[$nombre]);
			$pdf->SetXY($x1, $y2-6+4); $pdf->Cell(65, 5, $field_nombres[$ci]);
			$pdf->SetXY($x1, $y2-6+8); $pdf->Cell(65, 5, "Fecha:          /        /");
		}
		
	}
	
	$pdf->SetXY(10, 265); $pdf->SetFont('Arial', '', 10); $pdf->Cell(50, 5, $tipo);
}

//	FUNCION PARA CALCULAR EL ALTO DE UN MULTICELL
function getH($pdf, $l, $w, $dato) {
	$nb=$pdf->NbLines(w, $dato);
	$h=$l*$nb;
	return $h;
}

//	
function checkNuevaPagina($pdf, $h) {
	if($pdf->GetY()+$h>$pdf->PageBreakTrigger)
		return 1;
	else 
		return 0;
}

//
function formatoFecha($fecha) {
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha); 
	return "$d/$m/$a";
}

//	
function obtenerEdad($fecha) {
	if ($fecha == "0000-00-00") return "";
	else {
		list($a, $m, $d)=SPLIT( '[/.-]', $fecha);
		$aNac = (int) $a; $mNac = (int) $m; $dNac = (int) $d;
		$aActual = date("Y"); $mActual = date("m"); $dActual = date("d");
		
		$anios = $aActual - $aNac;
		if ($mActual > $mNac) $anios--;
		elseif ($mActual == $mNac && $dActual > $dNac) $anios--;
		
		return $anios;
	}
}

//	
function nombreMes($fecha) {
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $m=(int)$m;
	$mes[1] = "Enero";
	$mes[2] = "Febrero";
	$mes[3] = "Marzo";
	$mes[4] = "Abril";
	$mes[5] = "Mayo";
	$mes[6] = "Junio";
	$mes[7] = "Julio";
	$mes[8] = "Agosto";
	$mes[9] = "Septiembre";
	$mes[10] = "Octubre";
	$mes[11] = "Noviembre";
	$mes[12] = "Diciembre";	
	return $mes[$m];
}
?>