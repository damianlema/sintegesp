<?php

//	Inventario de Inmuebles...
function inmuebles_inventario($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->Ln(20);
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(270, 10, utf8_decode('INVENTARIO DE INMUEBLES'), 0, 1, 'C');
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetAligns(array('L', 'L', 'C', 'R'));
	$pdf->SetWidths(array(30, 185, 20, 35));
	$pdf->SetHeight(array(3));
	$pdf->Row(array(utf8_decode('Código del Bien'), utf8_decode('Denominación Inmueble'), 'Fecha de Compra', 'Costo de Compra'));
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
}

//	Hojas de Trabajo...
function inmuebles_hoja($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	//setLogo($pdf);
	//	--------------------
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(190, 10, utf8_decode('INVENTARIO DE INMUEBLES'), 0, 1, 'C');
}

//	Bienes Muebles Inventario...
function muebles_inventario($pdf, $costo, $ubicacion, $seriales, $especificaciones, $codigo_anterior, $codigo_catalogo, $descripcion_catalogo, $fecha_compra, $valor_actual, $marca, $organizacion, $nivel_organizacion) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->Ln(20);
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(270, 10, utf8_decode('INVENTARIO DE BIENES MUEBLES'), 0, 1, 'C');
	
	//	Obtengo la Organizacion...
	if($organizacion!=0){
		$sql = "SELECT denominacion FROM organizacion WHERE idorganizacion = '".$organizacion."'";
		$query_organizacion = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_organizacion) != 0) $field_organizacion = mysql_fetch_array($query_organizacion);
		// Imprimo Organizacion...
		$pdf->SetFont('Arial', '', 10);
		$pdf->Cell(270, 5, utf8_decode('Organización: '.$field_organizacion['denominacion']), 0, 1, 'L');
	}
	//	-----------------------
	
	//	Obtengo el Nivel Organizacional...
	$sql = "SELECT denominacion FROM niveles_organizacionales WHERE idniveles_organizacionales = '".$nivel_organizacion."'";
	$query_nivel = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_nivel) != 0) $field_nivel = mysql_fetch_array($query_nivel);
	//	-----------------------
	
	
		
	
	if ($nivel_organizacion != 0) {
		// Imprimo Nivel
		$pdf->SetFont('Arial', '', 10);
		$pdf->Cell(270, 5, utf8_decode('Nivel Organización: '.$field_nivel['denominacion']), 0, 1, 'L');
		
		$pdf->Ln(5);
	}
	
		$pdf->SetFont('Arial', 'B', 9);
		//$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(100, 100, 100); $pdf->SetTextColor(255, 255, 255);
		$pdf->SetHeight(array(3));
		

//$costo, $ubicacion, $seriales, $especificaciones, $codigo_anterior, $codigo_catalogo, $descripcion_catalogo, $fecha_compra, $valor_actual,$marca	
		
		//TODOS SELECCIONADOS
		
		$titulo= 'Código del Bien';
		$alineacion_superior="C";
		$alineacion_inferior="L";
		$ancho="25";
		
		if ($codigo_anterior) {
			$titulo1 = "Código Anterior";
			$alineacion_superior1="C";
			$alineacion_inferior1="L";
			$ancho1="25";
		}
		if ($especificaciones) {
			$titulo2 = "Especificaciones";
			$alineacion_superior2="C";
			$alineacion_inferior2="L";
			$ancho2="110";
		}
		if ($fecha_compra) {
			$titulo3 = "Fecha de Compra";
			$alineacion_superior3="C";
			$alineacion_inferior3="C";
			$ancho3="20";
		}
		if ($costo) {
			$titulo4 = "Costo de Compra";
			$alineacion_superior4="C";
			$alineacion_inferior4="R";
			$ancho4="25";
		}
		if ($valor_actual) {
			$titulo5 = "Valor Actual";
			$alineacion_superior5="C";
			$alineacion_inferior5="R";
			$ancho5="25";
		}
		if ($seriales) {
			$titulo6 = "Serial";
			$alineacion_superior6="C";
			$alineacion_inferior6="L";
			$ancho6="40";
		}
		
		 
			$pdf->SetAligns(array($alineacion_superior,$alineacion_superior1,$alineacion_superior2,$alineacion_superior3,$alineacion_superior4,$alineacion_superior5,$alineacion_superior6));
			$pdf->SetWidths(array($ancho,$ancho1,$ancho2,$ancho3,$ancho4,$ancho5,$ancho6));
			$pdf->Row(array(utf8_decode($titulo), utf8_decode($titulo1), utf8_decode($titulo2), utf8_decode($titulo3), utf8_decode($titulo4), utf8_decode($titulo5), utf8_decode($titulo6)));
			$pdf->SetAligns(array($alineacion_inferior,$alineacion_inferior1,$alineacion_inferior2,$alineacion_inferior3,$alineacion_inferior4,$alineacion_inferior5,$alineacion_inferior6));
		
		
			
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 9);
	$pdf->SetHeight(array(5));
}

//	Bienes Muebles Por Catalogo...
function muebles_por_catalogo($pdf, $costo, $ubicacion, $seriales, $especificaciones, $codigo_anterior, $codigo_catalogo, $descripcion_catalogo, $fecha_compra, $valor_actual, $marca, $catalogo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->Ln(20);
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(270, 10, utf8_decode('INVENTARIO DE MUEBLES'), 0, 1, 'C');
	
	//	Obtengo el Catalogo...
	$sql = "SELECT * FROM detalle_catalogo_bienes WHERE iddetalle_catalogo_bienes = '".$catalogo."'";
	$query_catalogo = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_catalogo) != 0) $field_catalogo = mysql_fetch_array($query_catalogo);
	//	-----------------------
	
	// Imprimo Organizacion...
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(270, 5, utf8_decode('Catálogo: ('.$field_catalogo["codigo"].') '.$field_catalogo["denominacion"]), 0, 1, 'L');
		
	$pdf->Ln(5);
		
	$pdf->SetFont('Arial', 'B', 9);
	//$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(100, 100, 100); $pdf->SetTextColor(255, 255, 255);
	$pdf->SetHeight(array(3));
	
	$titulo= 'Código del Bien';
		$alineacion_superior="C";
		$alineacion_inferior="L";
		$ancho="25";
		
		if ($codigo_anterior) {
			$titulo1 = "Código Anterior";
			$alineacion_superior1="C";
			$alineacion_inferior1="L";
			$ancho1="25";
		}
		if ($especificaciones) {
			$titulo2 = "Especificaciones";
			$alineacion_superior2="C";
			$alineacion_inferior2="L";
			$ancho2="110";
		}
		if ($fecha_compra) {
			$titulo3 = "Fecha de Compra";
			$alineacion_superior3="C";
			$alineacion_inferior3="C";
			$ancho3="20";
		}
		if ($costo) {
			$titulo4 = "Costo de Compra";
			$alineacion_superior4="C";
			$alineacion_inferior4="R";
			$ancho4="25";
		}
		if ($valor_actual) {
			$titulo5 = "Valor Actual";
			$alineacion_superior5="C";
			$alineacion_inferior5="R";
			$ancho5="25";
		}
		if ($seriales) {
			$titulo6 = "Serial";
			$alineacion_superior6="C";
			$alineacion_inferior6="L";
			$ancho6="40";
		}
		
		 
			$pdf->SetAligns(array($alineacion_superior,$alineacion_superior1,$alineacion_superior2,$alineacion_superior3,$alineacion_superior4,$alineacion_superior5,$alineacion_superior6));
			$pdf->SetWidths(array($ancho,$ancho1,$ancho2,$ancho3,$ancho4,$ancho5,$ancho6));
			$pdf->Row(array(utf8_decode($titulo), utf8_decode($titulo1), utf8_decode($titulo2), utf8_decode($titulo3), utf8_decode($titulo4), utf8_decode($titulo5), utf8_decode($titulo6)));
			$pdf->SetAligns(array($alineacion_inferior,$alineacion_inferior1,$alineacion_inferior2,$alineacion_inferior3,$alineacion_inferior4,$alineacion_inferior5,$alineacion_inferior6));
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetHeight(array(5));
}

//	FORMULARIO BM1
function bm1($pdf, $organizacion, $nivel_organizacion) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	
	$sql_configuracion_bienes = mysql_query("select * from configuracion_bienes");
	$reg_configuracion_bienes = mysql_fetch_array($sql_configuracion_bienes);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(60, 10);
	$pdf->Cell(100, 0, utf8_decode($reg_configuracion_bienes["cabecera_linea1"]), 0, 1, 'L');
	$pdf->SetXY(60, 14);
	$pdf->Cell(100, 0, utf8_decode($reg_configuracion_bienes["cabecera_linea2"]), 0, 1, 'L');
	$pdf->SetXY(60, 18);
	$pdf->Cell(100, 0, utf8_decode($reg_configuracion_bienes["cabecera_linea3"]), 0, 1, 'L');
	$pdf->SetXY(60, 22);
	$pdf->Cell(100, 0, utf8_decode($reg_configuracion_bienes["cabecera_linea4"]), 0, 1, 'L');
	
	
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'BU', 12);
	$pdf->Cell(270, 10, utf8_decode('HOJA DE INVENTARIO'), 0, 1, 'C');
	
	$sql_configuracion = mysql_query("select * from configuracion");
	$reg_configuracion = mysql_fetch_array($sql_configuracion);
	$sql = "SELECT * FROM organizacion WHERE idorganizacion = '".$organizacion."'";
	$query_organizacion = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_organizacion) != 0) $field_organizacion = mysql_fetch_array($query_organizacion);
	
	$pdf->SetAligns(array('L', 'L','L', 'L'));
	$pdf->SetWidths(array(41, 109, 20, 100));
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(40, 7, utf8_decode('ENTIDAD PROPIETARIA: '), 0, 0, 'L');
	$pdf->Line(45, 42,147,42);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(103, 7, utf8_decode($reg_configuracion['entidad_propietaria']), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(20, 7, utf8_decode('SERVICIO: '), 0, 0, 'L');
	$pdf->Line(166, 42,270,42);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(103, 7, utf8_decode($field_organizacion['denominacion']), 0, 0, 'L');
	
	
	$pdf->Ln(5);

	//	Obtengo el Nivel Organizacional...
	$sql = "SELECT denominacion FROM niveles_organizacionales WHERE idniveles_organizacionales = '".$nivel_organizacion."'";
	$query_nivel = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_nivel) != 0) $field_nivel = mysql_fetch_array($query_nivel);
	//	-----------------------
	$pdf->SetAligns(array('L', 'L'));
	$pdf->SetWidths(array(70, 200));
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(70, 7, utf8_decode('UNIDAD DE TRABAJO O DEPENDENCIA: '), 0, 0, 'L');
	$pdf->Line(71, 47,270,47);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(200, 7, utf8_decode($field_nivel['denominacion']), 0, 0, 'L');
	
	$pdf->Ln(5);
	
	$sql_estado=mysql_query("select * from estado where idestado= '".$field_organizacion['idestado']."'");
	$reg_estado=mysql_fetch_array($sql_estado);
	
	$sql_municipio=mysql_query("select * from municipios where idmunicipios= '".$field_organizacion['idmunicipio']."'");
	$reg_municipio=mysql_fetch_array($sql_municipio);
	
	$pdf->SetAligns(array('L', 'L','L', 'L'));
	$pdf->SetWidths(array(25, 125, 20, 100));
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(25, 7, utf8_decode('ESTADO: '), 0, 0, 'L');
	$pdf->Line(26, 52,140,52);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(115, 7, utf8_decode($reg_estado['denominacion']), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(20, 7, utf8_decode('MUNICIPIO: '), 0, 0, 'L');
	$pdf->Line(165, 52,270,52);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(100, 7, utf8_decode($reg_municipio['denominacion']), 0, 0, 'L');
	
	$pdf->Ln(5); 
	$fecha_actual=date("d/m/Y");
	$pdf->SetAligns(array('L', 'L','L', 'L'));
	$pdf->SetWidths(array(25, 175, 20, 50));
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(25, 7, utf8_decode('DIRECCIÓN: '), 0, 0, 'L');
	$pdf->Line(25, 57,200,57);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(175, 7, utf8_decode($field_organizacion['direccion']), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(20, 7, utf8_decode('FECHA: '), 0, 0, 'L');
	$pdf->Line(220, 57,270,57);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(50, 7, $fecha_actual, 0, 0, 'L');
	
	$pdf->Ln(5); 
	
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	//$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(100, 100, 100); $pdf->SetTextColor(255, 255, 255);
	$pdf->SetHeight(array(3));
		
	$pdf->Ln(5);
	$pdf->SetAligns(array('C','C','C','C','C','C'));
	$pdf->SetWidths(array(47,17,20,127,30,30));
	$pdf->Cell(47, 5, utf8_decode('Clasificación'), 1, 0, 'C');
	$pdf->Cell(17, 10, utf8_decode('Cantidad'), 1, 0, 'C');
	$pdf->Cell(20, 10, utf8_decode('No Ident.'), 1, 0, 'C');
	$pdf->Cell(127, 10, utf8_decode('Nombre y Descripción de los Elementos'), 1, 0, 'C');
	$pdf->Cell(30, 10, utf8_decode('Valor Unitario Bs.'), 1, 0, 'C');
	$pdf->Cell(30, 10, utf8_decode('Valor Total Bs.'), 1, 0, 'C');
	//$pdf->Row(array(utf8_decode('Clasificación')));
	
	$pdf->SetXY(5, 67);
	$pdf->SetAligns(array('C','C','C'));
	$pdf->SetWidths(array(12,20,15));
	$pdf->Cell(12, 5, utf8_decode('Grupo'), 1, 0, 'C');
	$pdf->Cell(20, 5, utf8_decode('Sub-Grupo'), 1, 0, 'C');
	$pdf->Cell(15, 5, utf8_decode('Sección'), 1, 0, 'C');
	$pdf->SetWidths(array(12,20,15,17,20,127,30,30));
	$pdf->SetAligns(array('C','C','C','C','L','L','R','R'));
	$pdf->Ln(7);
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetHeight(array(5));
}

//	FORMULARIO BM1
function bm2i($pdf, $idmovimiento, $organizacion, $nivel_organizacion) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	
	$sql_configuracion_bienes = mysql_query("select * from configuracion_bienes");
	$reg_configuracion_bienes = mysql_fetch_array($sql_configuracion_bienes);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(60, 10);
	$pdf->Cell(100, 0, utf8_decode($reg_configuracion_bienes["cabecera_linea1"]), 0, 1, 'L');
	$pdf->SetXY(60, 14);
	$pdf->Cell(100, 0, utf8_decode($reg_configuracion_bienes["cabecera_linea2"]), 0, 1, 'L');
	$pdf->SetXY(60, 18);
	$pdf->Cell(100, 0, utf8_decode($reg_configuracion_bienes["cabecera_linea3"]), 0, 1, 'L');
	$pdf->SetXY(60, 22);
	$pdf->Cell(100, 0, utf8_decode($reg_configuracion_bienes["cabecera_linea4"]), 0, 1, 'L');
	
	
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'BU', 12);
	$pdf->Cell(270, 10, utf8_decode('INCORPORACION DE BIENES MUEBLES'), 0, 1, 'C');
	
	$sql = "SELECT * FROM movimientos_bienes WHERE idmovimientos_bienes = '".$idmovimiento."'";
	$query_movimiento = mysql_query($sql) or die ($sql.mysql_error());
	$field_movimiento = mysql_fetch_array($query_movimiento);
	
	
	$sql_configuracion = mysql_query("select * from configuracion");
	$reg_configuracion = mysql_fetch_array($sql_configuracion);
	$sql = "SELECT * FROM organizacion WHERE idorganizacion = '".$organizacion."'";
	$query_organizacion = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_organizacion) != 0) $field_organizacion = mysql_fetch_array($query_organizacion);
	
	$pdf->SetAligns(array('L', 'L','L', 'L'));
	$pdf->SetWidths(array(41, 109, 20, 100));
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(40, 7, utf8_decode('ENTIDAD PROPIETARIA: '), 0, 0, 'L');
	$pdf->Line(45, 42,147,42);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(103, 7, utf8_decode($reg_configuracion['entidad_propietaria']), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(20, 7, utf8_decode('SERVICIO: '), 0, 0, 'L');
	$pdf->Line(166, 42,270,42);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(103, 7, utf8_decode($field_organizacion['denominacion']), 0, 0, 'L');
	
	
	$pdf->Ln(5);

	//	Obtengo el Nivel Organizacional...
	$sql = "SELECT denominacion FROM niveles_organizacionales WHERE idniveles_organizacionales = '".$nivel_organizacion."'";
	$query_nivel = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_nivel) != 0) $field_nivel = mysql_fetch_array($query_nivel);
	//	-----------------------
	$pdf->SetAligns(array('L', 'L'));
	$pdf->SetWidths(array(70, 200));
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(70, 7, utf8_decode('UNIDAD DE TRABAJO O DEPENDENCIA: '), 0, 0, 'L');
	$pdf->Line(71, 47,270,47);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(200, 7, utf8_decode($field_nivel['denominacion']), 0, 0, 'L');
	
	$pdf->Ln(5);
	
	$sql_estado=mysql_query("select * from estado where idestado= '".$field_organizacion['idestado']."'");
	$reg_estado=mysql_fetch_array($sql_estado);
	
	$sql_municipio=mysql_query("select * from municipios where idmunicipios= '".$field_organizacion['idmunicipio']."'");
	$reg_municipio=mysql_fetch_array($sql_municipio);
	
	$pdf->SetAligns(array('L', 'L','L', 'L'));
	$pdf->SetWidths(array(25, 125, 20, 100));
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(25, 7, utf8_decode('ESTADO: '), 0, 0, 'L');
	$pdf->Line(26, 52,140,52);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(115, 7, utf8_decode($reg_estado['denominacion']), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(20, 7, utf8_decode('MUNICIPIO: '), 0, 0, 'L');
	$pdf->Line(165, 52,270,52);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(100, 7, utf8_decode($reg_municipio['denominacion']), 0, 0, 'L');
	
	$pdf->Ln(5); 
	$fecha_actual=date("d/m/Y");
	$pdf->SetAligns(array('L', 'L','L', 'L'));
	$pdf->SetWidths(array(25, 175, 20, 50));
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(25, 7, utf8_decode('DIRECCIÓN: '), 0, 0, 'L');
	$pdf->Line(25, 57,200,57);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(175, 7, utf8_decode($field_organizacion['direccion']), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(20, 7, utf8_decode('FECHA: '), 0, 0, 'L');
	$pdf->Line(220, 57,270,57);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(50, 7, $fecha_actual, 0, 0, 'L');
	
	$pdf->Ln(5); 
	
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	//$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(100, 100, 100); $pdf->SetTextColor(255, 255, 255);
	$pdf->SetHeight(array(3));
		
	$pdf->Ln(5);
	$pdf->SetAligns(array('C','C','C','C','C','C'));
	$pdf->SetWidths(array(47, 27, 17,20,130,30));
	$pdf->Cell(47, 5, utf8_decode('Clasificación'), 1, 0, 'C');
	$pdf->Text(59, 66, utf8_decode('Concepto'), 0, 0, 'C');
	$pdf->Text(57, 69, utf8_decode('Movimiento'), 0, 0, 'C');
	$pdf->Cell(27, 10, utf8_decode(''), 1, 0, 'C');
	$pdf->Cell(17, 10, utf8_decode('Cantidad'), 1, 0, 'C');
	$pdf->Cell(20, 10, utf8_decode('No Ident.'), 1, 0, 'C');
	$pdf->Cell(127, 10, utf8_decode('Nombre y Descripción de los Elementos'), 1, 0, 'C');
	$pdf->Cell(30, 10, utf8_decode('Incorporación Bs.'), 1, 0, 'C');
	//$pdf->Row(array(utf8_decode('Clasificación')));
	
	$pdf->SetXY(5, 67);
	$pdf->SetAligns(array('C','C','C'));
	$pdf->SetWidths(array(12,20,15));
	$pdf->Cell(12, 5, utf8_decode('Grupo'), 1, 0, 'C');
	$pdf->Cell(20, 5, utf8_decode('Sub-Grupo'), 1, 0, 'C');
	$pdf->Cell(15, 5, utf8_decode('Sección'), 1, 0, 'C');
	$pdf->SetWidths(array(12,20,15,27,17,20,127,30,30));
	$pdf->SetAligns(array('C','C','C','C','C','L','L','R'));
	$pdf->Ln(7);
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetHeight(array(5));
}

//	Bienes Muebles Por Catalogo...
function bienes_catalogo($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->Ln(20);
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(185, 10, utf8_decode('CATALOGO DE BIENES'), 0, 1, 'C');
	
	$pdf->SetFont('Arial', '', 10);
}

//	Bienes Muebles Por Catalogo...
function bienes_estructura_organizativa($pdf) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->Ln(20);
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(185, 10, utf8_decode('ESTRUCTURA ORGANIZATIVA'), 0, 1, 'C');
	
	$pdf->SetFont('Arial', '', 10);
}

//	Acta de Incorporacion...
function acta_incorporacion_bienes($pdf, $pag, $desde, $hasta) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetXY(180, 10); 
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(15, 3, 'Pag: ', 0, 0, 'R');
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(30, 3, $pag, 0, 0, 'L');
	$pdf->SetXY(180, 14); 
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(15, 3, 'Fecha: ', 0, 0, 'R');
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(30, 3, date("d/m/Y"), 0, 0, 'L');
	$pdf->SetXY(180, 18); 
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(15, 3, 'Desde: ', 0, 0, 'R');
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(30, 3, $desde, 0, 0, 'L');
	$pdf->SetXY(180, 22); 
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(15, 3, 'Hasta: ', 0, 0, 'R');
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(30, 3, $hasta, 0, 1, 'L');
	
	
	$pdf->SetY(25);
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(205, 10, utf8_decode('ACTA DE INCORPORACION DE BIENES'), 0, 1, 'C');
	$pdf->Ln(5);
	
	
	//	Consulto los datos de configuracion...
	
	
	//	Si seleccione alguna dependencia...
	if ($nivel_organizacion != 0) {
		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(205, 10, utf8_decode('DEPENDENCIA: '.$nombre_nivel_organizacion), 0, 1, 'L');
		$texto = "El suscrito T.S.U. ENEIDA RODRIGUEZ, Jefe de Oficina de Bienes de la Procuraduria General del Estado, en cumpliemiento al artículo 8, Título ONCE de la Ley de Contraloría General del Estado Delta Amacuro, hace constar por medio de la presente, que los bienes que a continuación se especifican han sido incorporados al inventario General de esta Dependencia";
	} else 
		$texto = "El suscrito T.S.U. ENEIDA RODRIGUEZ, Jefe de Oficina de Bienes de la Procuraduria General del Estado, en cumpliemiento al artículo 8, Título ONCE de la Ley de Contraloría General del Estado Delta Amacuro, hace constar por medio de la presente, que los bienes que a continuación se especifican han sido incorporados al inventario General de esta Dependencia";
	
	$pdf->SetFont('Arial', '', 10);
	$pdf->MultiCell(205, 8, utf8_decode($texto), 0, 'J');
	$pdf->Ln(5);
	
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetAligns(array('L', 'C', 'R', 'R', 'L', 'L'));
	$pdf->SetWidths(array(25, 25, 25, 25, 30, 75));
	$pdf->SetHeight(array(3));
	$pdf->Row(array(utf8_decode('Código del Bien'), 'Fecha de Compra', 'Costo de Compra', 'Valor Actual', 'Serial', utf8_decode('Ubicación Física')));
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Ln(3);
}

//	
function listar_foros($pdf, $padre, $titulo, $tab, $cel) {
	global $foros;
	$x = $tab + 15;
	$w = $cel - 15;
	foreach($foros[$padre] as $foro => $datos) {			
		if(isset($foros[$foro])) {
			//$nuevo_titulo = ($titulo == '') ? $datos['denominacion'] : "$titulo - {$datos['denominacion']} -";
			
			$pdf->SetX($x);
			$pdf->MultiCell($w, 4, $datos['codigo'].' - '.$datos['denominacion'], 0, 'L');
			$pdf->Ln(3);
			
			listar_foros($pdf, $foro, $titulo, $x, $w);
		} else {
			$pdf->SetX($x);
			$pdf->MultiCell($w, 4, $datos['codigo'].' - '.$datos['denominacion'], 0, 'L');
			$pdf->Ln(3);
		}
	}
}


//	Bienes Muebles Inventario...
function movimiento_bienes($pdf, $idmovimiento, $pag) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$sql = "SELECT * FROM movimientos_bienes WHERE idmovimientos_bienes = '".$idmovimiento."'";
	$query_movimiento = mysql_query($sql) or die ($sql.mysql_error());
	$field_movimiento = mysql_fetch_array($query_movimiento);
	
	
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(15, 4); $pdf->Cell(100, 5, '', 0, 0, 'L');
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 4, utf8_decode('Número: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 4, $field_movimiento["nro_movimiento"], 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(15, 4); $pdf->Cell(100, 5, '', 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 4, 'Fecha: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	list($a, $m, $d)=SPLIT( '[/.-]', $field_movimiento["fecha_movimiento"]); $fecha=$d."/".$m."/".$a;
	$pdf->Cell(35, 4, $fecha, 0, 1, 'L');	 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(165, 4, utf8_decode('Página: '), 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 4, $pag, 0, 1, 'L'); 
	
	
	$pdf->Ln(16);
	$pdf->SetFont('Arial', 'BU', 14);
	$pdf->Cell(200, 10, utf8_decode('ACTA DE MOVIMIENTO DE BIENES'), 0, 1, 'C');
	
	
	//	-----------------------
	//	Obtengo el Tipo de Movimiento...
	if ($field_movimiento["idtipo_movimiento"] != 0){
		$sql = "SELECT * FROM tipo_movimiento_bienes WHERE idtipo_movimiento_bienes = '".$field_movimiento["idtipo_movimiento"]."'";
		$query_tipo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_tipo) != 0) $field_tipo = mysql_fetch_array($query_tipo);
	
		$pdf->Ln(5);	
	if ($field_tipo["origen_bien"] == 'nuevo') {
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Afecta: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->Cell(35, 5, strtoupper(utf8_decode($field_movimiento["tipo"])), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 10);
		
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Tipo: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->Cell(35, 5, strtoupper(utf8_decode($field_movimiento["afecta"])), 0, 1, 'L');
		
		
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Movimiento: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->Cell(35, 5, $field_tipo["codigo"]." - ".utf8_decode($field_tipo["denominacion"]), 0, 1, 'L');
		
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Concepto: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->MultiCell(175, 5, utf8_decode($field_movimiento["justificacion"]), 0, 'L');
		
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Documento: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->Cell(175, 5, utf8_decode($field_movimiento["nro_documento_bien_nuevo"]."                 Fecha: ".$field_movimiento["fecha_documento_bien_nuevo"]), 0, 1, 'L');
		
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Proveedor: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->Cell(175, 5, utf8_decode($field_movimiento["proveedor_bien_nuevo"]), 0, 1, 'L');
		
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Factura / NE: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->Cell(175, 5, utf8_decode($field_movimiento["nro_factura_bien_nuevo"]."                 Fecha: ".$field_movimiento["fecha_factura_bien_nuevo"]), 0, 1, 'L');
		
		$pdf->Ln(3);
		$pdf->SetAligns(array('C', 'L', 'L', 'L', 'R'));
		$pdf->SetWidths(array(20, 20, 60, 70, 35));
		$pdf->Row(array(utf8_decode('Código Bien'), utf8_decode('Catálogo'), utf8_decode('Descripción'), utf8_decode('Nivel Organizacional'), 'Costo Ajustado'));
		
		$pdf->SetAligns(array('L', 'L', 'L', 'L', 'R'));
		$pdf->SetWidths(array(20, 20, 60, 70, 35));
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Ln(3);
	
	}
	
	
	//	-----------------------
	//	Obtengo el Tipo de Movimiento...
		$sql = "SELECT * FROM tipo_movimiento_bienes WHERE idtipo_movimiento_bienes = '".$field_movimiento["idtipo_movimiento"]."'";
		$query_tipo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_tipo) != 0) $field_tipo = mysql_fetch_array($query_tipo);
		$pdf->Ln(5);	
	if ($field_tipo["afecta"] == '1' and $field_tipo["origen_bien"] == 'existente' and $field_tipo["cambia_ubicacion"] == 'no') {
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Afecta: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->Cell(35, 5, strtoupper(utf8_decode($field_movimiento["tipo"])), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 10);
		
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Tipo: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->Cell(35, 5, strtoupper(utf8_decode($field_movimiento["afecta"])), 0, 1, 'L');
		
		
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Movimiento: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->Cell(35, 5, $field_tipo["codigo"]." - ".utf8_decode($field_tipo["denominacion"]), 0, 1, 'L');
		
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Concepto: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->MultiCell(175, 5, utf8_decode($field_movimiento["justificacion"]), 0, 'L');
		
		$pdf->Ln(3);
		$pdf->SetAligns(array('C', 'L', 'L', 'L', 'R'));
		$pdf->SetWidths(array(20, 20, 60, 70, 35));
		$pdf->Row(array(utf8_decode('Código Bien'), utf8_decode('Catálogo'), utf8_decode('Descripción'), utf8_decode('Nivel Organizacional'), 'Monto Mejoras Bs.'));
		
		$pdf->SetAligns(array('L', 'L', 'L', 'L', 'R'));
		$pdf->SetWidths(array(20, 20, 60, 70, 35));
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Ln(3);
	
	}
	
	if ($field_tipo["afecta"] == '2' and $field_tipo["origen_bien"] == 'existente' and $field_tipo["cambia_ubicacion"] == 'no') {
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Afecta: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->Cell(35, 5, strtoupper(utf8_decode($field_movimiento["tipo"])), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 10);
		
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Tipo: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->Cell(35, 5, strtoupper(utf8_decode($field_movimiento["afecta"])), 0, 1, 'L');
		
		
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Movimiento: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->Cell(35, 5, $field_tipo["codigo"]." - ".utf8_decode($field_tipo["denominacion"]), 0, 1, 'L');
		
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Concepto: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->MultiCell(175, 5, utf8_decode($field_movimiento["justificacion"]), 0, 'L');
		
		$pdf->Ln(3);
		$pdf->SetAligns(array('C', 'L', 'L', 'L'));
		$pdf->SetWidths(array(20, 20, 60, 105));
		$pdf->Row(array(utf8_decode('Código Bien'), utf8_decode('Catálogo'), utf8_decode('Descripción'), utf8_decode('Nivel Organizacional')));
		
		$pdf->SetAligns(array('L', 'L', 'L', 'L'));
		$pdf->SetWidths(array(20, 20, 60, 105));
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Ln(3);
	
	}
	}else{
			
		
		
		
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Afecta: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->Cell(35, 5, strtoupper(utf8_decode($field_movimiento["tipo"])), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 10);
		
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Tipo: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->Cell(35, 5, strtoupper(utf8_decode('TRASLADO')), 0, 1, 'L');
		
		
		$pdf->SetFont('Arial', 'B', 11);
		$pdf->Cell(30, 5, utf8_decode('Concepto: '), 0, 0, 'R'); 
		$pdf->SetFont('Arial', '', 11);
		$pdf->MultiCell(175, 5, utf8_decode($field_movimiento["justificacion"]), 0, 'L');
		
		$pdf->Ln(3);
		$pdf->SetAligns(array('C', 'L', 'L', 'L'));
		$pdf->SetWidths(array(20, 20, 60, 105));
		$pdf->Row(array(utf8_decode('Código Bien'), utf8_decode('Catálogo'), utf8_decode('Descripción'), utf8_decode('Ubicación Organizacional ORIGEN / DESTINO')));
		
		$pdf->SetAligns(array('L', 'L', 'L', 'L'));
		$pdf->SetWidths(array(20, 20, 60, 105));
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Ln(3);
	
		
	}
}

?>