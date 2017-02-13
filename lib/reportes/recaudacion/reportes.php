<?php
session_start();
set_time_limit(-1);
ini_set("memory_limit", "200M");
extract($_GET);
extract($_POST);
//	----------------------------------------
$MAXLINEA=30;
$MAXLINEALEG=15;
//	----------------------------------------
require('../../../conf/conex.php');
require('../../mc_table.php');
require('../../mc_table2.php');
require('../../mc_table3.php');
require('../../mc_table4.php');
require('../../mc_table5.php');
require('../../mc_table6.php');
require('../../mc_table7.php');
require('../../mc_table8.php');
require('../firmas.php');
require('head.php');
//	------------------------------------------
Conectarse();
$ahora=date("d-m-Y H:i:s");
//	----------------------------------------

//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
//	Contribuyentes (Datos Basicos)
if ($nombre == "contribuyente") {
	$pdf=new PDF_MC_Table8('P', 'mm', 'Letter');
	$pdf->Open();
	//	--------------------
	$sql = "SELECT
				c.razon_social,
				c.rif,
				c.telefono,
				c.email,
				c.nro_casa,
				c.punto_referencia,				
				cr.denominacion AS nomcarrera,
				cl.denominacion AS nomcalle,
				u.denominacion AS nomurbanizacion,
				s.denominacion AS nomsector,
				p.denominacion AS nomparroquia,
				m.denominacion AS nommunicipio,
				e.denominacion AS nomestado
			FROM
				contribuyente c
				LEFT JOIN carrera cr ON (c.carrera = cr.idcarrera)
				LEFT JOIN calle cl ON (cr.idcalle = cl.idcalle)
				LEFT JOIN urbanizacion u ON (cl.idurbanizacion = u.idurbanizacion)
				LEFT JOIN sectores s ON (u.idsectores = s.idsectores)
				LEFT JOIN parroquia p ON (s.idparroquia = p.idparroquia)
				LEFT JOIN municipios m ON (p.idmunicipios = m.idmunicipios)
				LEFT JOIN estado e ON (m.idestado = e.idestado)
			WHERE c.idcontribuyente = '".$idcontribuyente."'";
	$query_contribuyente = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_contribuyente) != 0) $field_contribuyente = mysql_fetch_array($query_contribuyente);
	$direccion = $field_contribuyente['punto_referencia'].' Casa/Local # '.$field_contribuyente['nro_casa'].' Carrera '.$field_contribuyente['nomcarrera'].' Calle '.$field_contribuyente['nomcalle'].' Urb./Barrio '.$field_contribuyente['nomurbanizacion'].' Sector '.$field_contribuyente['nomsector'].' Parroquia '.$field_contribuyente['nomparroquia'].' Municipio '.$field_contribuyente['nommunicipio'].' Estado '.$field_contribuyente['nomestado'];
	//	--------------------
	contribuyente($pdf);
	//	--------------------
	//	datos generales	
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(0, 0, 0);
	
	$pdf->SetFillColor(240, 240, 240); 
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(195, 5, 'DATOS BÁSICOS', 0, 0, 'L', 1);
	$pdf->Ln(7);
	
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', 'BU', 8);
	$pdf->Cell(195, 5, 'Datos Generales.', 0, 0, 'L', 1);
	$pdf->Ln(7);
	
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Nombre o Razón Social:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(110, 5, utf8_decode($field_contribuyente['razon_social']), 0, 0, 'L', 1);
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(20, 5, 'R.I.F./C.I.:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(30, 5, utf8_decode($field_contribuyente['rif']), 0, 0, 'L', 1);
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'E-mail:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(110, 5, utf8_decode($field_contribuyente['email']), 0, 0, 'L', 1);
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(20, 5, 'Teléfono:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(30, 5, $field_contribuyente['telefono'], 0, 0, 'L', 1);
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Dirección:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->MultiCell(160, 5, utf8_decode($direccion), 0, 'L', 1);
	$pdf->Ln(7);
	//	--------------------
	//	registro mercantil
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(0, 0, 0);
	
	$pdf->SetFillColor(240, 240, 240); 
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(195, 5, 'REGISTRO MERCANTIL', 0, 0, 'L', 1);
	$pdf->Ln(7);
	
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', 'BU', 8);
	$pdf->Cell(195, 5, 'Datos Generales.', 0, 0, 'L', 1);
	$pdf->Ln(7);
	
	$sql = "SELECT
				rmc.fecha_registro,
				rmc.objeto,
				rmc.libro,
				rmc.folio,
				rmc.capital_social,
				rmc.capital_suscrito,
				tp.descripcion AS nomtipo_persona,
				te.descripcion AS nomtipo_empresa,
				ts.descripcion AS nomtipo_sociedad
			FROM
				registro_mercantil_contribuyente rmc
				LEFT JOIN tipos_persona tp ON (rmc.tipo_persona = tp.idtipos_persona)
				LEFT JOIN tipo_empresa te ON (rmc.tipo_empresa = te.idtipo_empresa)
				LEFT JOIN tipo_sociedad ts ON (rmc.tipo_sociedad = ts.idtipo_sociedad)
			WHERE rmc.idcontribuyente = '".$idcontribuyente."'";
	$query_registro_mercantil = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_registro_mercantil = mysql_fetch_array($query_registro_mercantil)) {
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Tipo de Persona:', 0, 0, 'L', 1);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(65, 5, utf8_decode($field_registro_mercantil['nomtipo_persona']), 0, 0, 'L', 1);
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Tipo de Empresa:', 0, 0, 'L', 1);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(60, 5, utf8_decode($field_registro_mercantil['nomtipo_empresa']), 0, 0, 'L', 1);
		$pdf->Ln(5);
		if ($pdf->GetY() > 200) contribuyente1($pdf);
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Tipo de Sociedad:', 0, 0, 'L', 1);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(65, 5, utf8_decode($field_registro_mercantil['nomtipo_sociedad']), 0, 0, 'L', 1);
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Fecha de Registro:', 0, 0, 'L', 1);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(60, 5, formatoFecha($field_registro_mercantil['fecha_registro']), 0, 0, 'L', 1);
		$pdf->Ln(5);
		if ($pdf->GetY() > 200) contribuyente1($pdf);
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Objeto:', 0, 0, 'L', 1);
		$pdf->SetFont('Arial', '', 8); $pdf->MultiCell(160, 5, utf8_decode($field_registro_mercantil['objeto']), 0, 'L', 1);
		$pdf->Ln(1);
		if ($pdf->GetY() > 200) contribuyente1($pdf);
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Libro:', 0, 0, 'L', 1);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(65, 5, utf8_decode($field_registro_mercantil['libro']), 0, 0, 'L', 1);
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Folio:', 0, 0, 'L', 1);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(65, 5, utf8_decode($field_registro_mercantil['folio']), 0, 0, 'L', 1);
		$pdf->Ln(5);
		if ($pdf->GetY() > 200) contribuyente1($pdf);
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Capital Social:', 0, 0, 'L', 1);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(65, 5, utf8_decode($field_registro_mercantil['capital_social']), 0, 0, 'L', 1);
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Capital Suscrito:', 0, 0, 'L', 1);
		$pdf->SetFont('Arial', '', 8); $pdf->Cell(65, 5, utf8_decode($field_registro_mercantil['capital_suscrito']), 0, 1, 'L', 1);
		$pdf->SetFont('Arial', '', 2); $pdf->Cell(195, 5, str_repeat('.', 1000), 0, 1, 'L', 1);
		if ($pdf->GetY() > 200) contribuyente1($pdf);
	}	
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
	if ($pdf->GetY() > 230) contribuyente2($pdf);
	
	$sql = "SELECT
				nombre_socio,
				ci_socio,
				cargo_socio,
				acciones_socio
			FROM socios_contribuyente
			WHERE
				idcontribuyente = '".$idcontribuyente."'";
	$query_socios = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_socios = mysql_fetch_array($query_socios)) {
		$pdf->Cell(5, 5);
		$pdf->Row(array(utf8_decode($field_socios['nombre_socio']),
						number_format($field_socios['ci_socio'], 0, '' ,'.'),
						utf8_decode($field_socios['cargo_socio']),
						utf8_decode($field_socios['acciones_socio'])));
		if ($pdf->GetY() > 230) contribuyente2($pdf);
	}
	$pdf->Ln(7);
	//	--------------------
	//	actividades comerciales
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
	
	$sql = "SELECT aco.denominacion
			FROM
				actividades_contribuyente ac
				LEFT JOIN actividades_comerciales aco ON (ac.idactividad_comercial = aco.idactividades_comerciales)
			WHERE ac.idcontribuyente = '".$idcontribuyente."'";
	$query_actividades = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_actividades = mysql_fetch_array($query_actividades)) {
		$pdf->Cell(5, 5);
		$pdf->Row(array(utf8_decode($field_actividades['denominacion'])));
		$pdf->Ln(2);
		if ($pdf->GetY() > 230) contribuyente3($pdf);
	}
	$pdf->Ln(7);
	//	--------------------
	//	requisitos
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
	
	$sql = "SELECT 
				r.idrequisitos,
				r.denominacion,
				rrc.fecha_vencimiento
			FROM
				requisitos r
				LEFT JOIN relacion_requisitos_contribuyente rrc ON (r.idrequisitos = rrc.idrequisitos AND rrc.idcontribuyente = '".$idcontribuyente."')";
	$query_requisitos = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_requisitos = mysql_fetch_array($query_requisitos)) {
		if ($field_requisitos['fecha_vencimiento'] == "0000-00-00") {
			$fecha_vencimiento = "SIN VENCIMIENTO";
		}
		elseif ($field_requisitos['fecha_vencimiento'] == "") {
			$fecha_vencimiento = "";
		} else {
			$fecha_vencimiento = formatoFecha($field_requisitos['fecha_vencimiento']);
		}
		
		$x = $pdf->GetX() + 14;
		$y = $pdf->GetY() + 1.5;
		
		$pdf->Cell(5, 5);
		$pdf->Row(array($flag,
						utf8_decode($field_requisitos['denominacion']),
						$fecha_vencimiento));
		
		if ($fecha_vencimiento != "") $pdf->Image("../../../imagenes/check.jpg", $x, $y, 3, 3, '', '');
		else $pdf->Image("../../../imagenes/uncheck.jpg", $x, $y, 3, 3, '', '');
		if ($pdf->GetY() > 230) { contribuyente4($pdf); $newHoja=false; } else $newHoja=true;
	}
	$pdf->Ln(7);
	//	--------------------
	if ($newHoja) contribuyente($pdf);
	//	--------------------
	//	registro fotografico
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(0, 0, 0);
	
	$pdf->SetFillColor(240, 240, 240);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(195, 5, 'REGISTRO FOTOGRÁFICO', 0, 0, 'L', 1);
	$pdf->Ln(10);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	
	$x = 20;
	$y = $pdf->GetY();
	$sql = "SELECT * FROM imagenes_contribuyente WHERE idcontribuyente = '".$idcontribuyente."'";
	$query_imagenes = mysql_query($sql) or die ($sql.mysql_error());	$i=1;
	while ($field_imagenes = mysql_fetch_array($query_imagenes)) {
		$pdf->Image("../../../".$field_imagenes['ruta'], $x, $y, 80, 80, '', '');		
		$y+=90;
		$pdf->SetY($y);
		$pdf->MultiCell(160, 5, utf8_decode($field_imagenes['descripcion']), 0, 'L', 1);
		$y+=10;
		
		$i++;
		if ($i==3) { contribuyente($pdf); $i=1; $y=$pdf->GetY(); }
	}
}

//	Solicitud de Calculo
elseif ($nombre == "solicitud_de_calculo") {
	$pdf=new PDF_MC_Table8('P', 'mm', 'Letter');
	$pdf->Open();
	//	--------------------
	solicitud_de_calculo($pdf);
	//	--------------------
	//	Imprimo los datos generales
	$sql = "SELECT
				sc.*,
				c.razon_social,
				c.rif,
				ts.descripcion AS nomtipo_solicitud
			FROM
				solicitud_calculo sc
				LEFT JOIN contribuyente c ON (sc.idcontribuyente = c.idcontribuyente)
				LEFT JOIN tipo_solicitud ts ON (sc.idtipo_solicitud = ts.idtipo_solicitud)
			WHERE
				sc.idsolicitud_calculo = '".$idsolicitud_calculo."'";
	$query_solicitud = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_solicitud) != 0) $field_solicitud = mysql_fetch_array($query_solicitud);
	//	--------------------
	//	datos generales	
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(0, 0, 0);	
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', 'BU', 8);
	$pdf->Cell(195, 5, 'DATOS GENERALES.', 0, 0, 'L', 1);
	$pdf->Ln(7);
	
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Nro. Solicitud:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(100, 5, utf8_decode($field_solicitud['numero_solicitud']), 0, 0, 'L', 1);
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 5, 'Estado:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(30, 5, utf8_decode(strtoupper($field_solicitud['estado'])), 0, 0, 'L', 1);
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Nombre o Razón Social:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(100, 5, utf8_decode($field_solicitud['razon_social']), 0, 0, 'L', 1);
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 5, 'R.I.F./C.I.:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(30, 5, $field_solicitud['rif'], 0, 0, 'L', 1);
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Tipo de Solicitud:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(100, 5, utf8_decode($field_solicitud['nomtipo_solicitud']), 0, 0, 'L', 1);
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 5, 'Fecha Solicitud:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(30, 5, formatoFecha($field_solicitud['fecha_solicitud']), 0, 0, 'L', 1);
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Año Fiscal:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(100, 5, $field_solicitud['anio'], 0, 0, 'L', 1);
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 5, 'Vencimiento:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(30, 5, formatoFecha($field_solicitud['vence']), 0, 0, 'L', 1);
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Desde:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(100, 5, formatoFecha($field_solicitud['desde']), 0, 0, 'L', 1);
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 5, 'Hasta:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->Cell(30, 5, formatoFecha($field_solicitud['hasta']), 0, 0, 'L', 1);
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(35, 5, 'Descripción:', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', '', 8); $pdf->MultiCell(160, 5, utf8_decode($field_solicitud['descripcion']), 0, 'L', 1);
	$pdf->Ln(7);
	//	--------------------
	//	imprimo los conceptos
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(0, 0, 0);	
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', 'BU', 8);
	$pdf->Cell(195, 5, 'CONCEPTOS.', 0, 0, 'L', 1);
	$pdf->Ln(7);
	
	$pdf->SetFillColor(235, 235, 235);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('L', 'C', 'R', 'R', 'C'));
	$pdf->SetWidths(array(75, 35, 25, 25, 35));
	$pdf->SetHeight(array(6));
	$pdf->Row(array('Concepto',
					'Tipo Base',
					'Alicuota',
					'Total',
					'Fracción de Pago'));
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Ln(2);
	
	$sql = "SELECT
				ct.*,
				csc.valor,
				csc.total_pagar,
				csc.tipo_cobro,
				csc.fraccion_pago
			FROM
				conceptos_solicitud_calculo csc
				LEFT JOIN concepto_tributario ct ON (csc.idconcepto = ct.idconcepto_tributario)
			WHERE
				csc.idsolicitud_calculo = '".$idsolicitud_calculo."'";
	$query_conceptos = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_conceptos = mysql_fetch_array($query_conceptos)) {
		if ($field_conceptos['tipo_cobro'] == "unidad_tributaria") $tipo_base = "Unidad Tributaria";
		elseif ($field_conceptos['tipo_cobro'] == "monto_variable") $tipo_base = "Monto Variable";
		
		if ($field_conceptos['fraccion_pago'] == "fecha_actual") $fraccion_pago = "Fecha Actual";
		if ($field_conceptos['fraccion_pago'] == "mensual") $fraccion_pago = "Mensual";
		if ($field_conceptos['fraccion_pago'] == "trimestral") $fraccion_pago = "Trimestral";
		if ($field_conceptos['fraccion_pago'] == "anual") $fraccion_pago = "Anual";
		
		$pdf->Row(array(utf8_decode('('.$field_conceptos['codigo'].') '.$field_conceptos['denominacion']),
						utf8_decode($tipo_base),
						number_format($field_conceptos['valor'], 2, ',' ,'.'),
						number_format($field_conceptos['total_pagar'], 2, ',' ,'.'),
						utf8_decode($fraccion_pago)));
		$suma_total += $field_conceptos['total_pagar'];
		
		if ($pdf->GetY() > 250) {
			solicitud_de_calculo($pdf);
			
			//	imprimo los conceptos
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetTextColor(0, 0, 0);	
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetFont('Arial', 'BU', 8);
			$pdf->Cell(195, 5, 'CONCEPTOS.', 0, 0, 'L', 1);
			$pdf->Ln(7);
			
			$pdf->SetFillColor(235, 235, 235);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->SetAligns(array('L', 'C', 'R', 'R', 'C'));
			$pdf->SetWidths(array(75, 35, 25, 25, 35));
			$pdf->SetHeight(array(6));
			$pdf->Row(array('Concepto',
							'Tipo Base',
							'Alicuota',
							'Total',
							'Fracción de Pago'));
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetDrawColor(255, 255, 255);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Ln(2);
		}
	}
	$pdf->SetFillColor(235, 235, 235);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Row(array('',
							'',
							'TOTAL',
							''.number_format($suma_total,2,",",".").'',
							''));
	
	$pdf->Ln(7);
	
	if ($field_solicitud['estado'] != "elaboracion") {
		//	--------------------
		//	imprimo los pagos pendientes
		$pdf->SetDrawColor(0, 0, 0);
		$pdf->SetTextColor(0, 0, 0);	
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetFont('Arial', 'BU', 8);
		$pdf->Cell(195, 5, 'PAGOS.', 0, 0, 'L', 1);
		$pdf->Ln(7);
		
		$pdf->SetFillColor(235, 235, 235);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->SetAligns(array('L', 'R', 'L'));
		$pdf->SetWidths(array(75, 25, 95));
		$pdf->SetHeight(array(6));
		$pdf->Row(array('Concepto',
						'Monto',
						'Fecha de proximo de pago'));
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Ln(2);
		
		$sql = "SELECT
					rfvc.*,
					ct.codigo,
					ct.denominacion,
					sc.numero_solicitud
				FROM
					rango_fecha_vencimiento_conceptos rfvc
					LEFT JOIN conceptos_solicitud_calculo csc ON (rfvc.idconcepto_solicitud_calculo = csc.idconceptos_solicitud_calculo)
					LEFT JOIN concepto_tributario ct ON (csc.idconcepto = ct.idconcepto_tributario)
					LEFT JOIN solicitud_calculo sc ON (csc.idsolicitud_calculo = sc.idsolicitud_calculo)
				WHERE
					csc.idsolicitud_calculo = '".$idsolicitud_calculo."'
				ORDER BY fecha_vencimiento";
		$query_pagos = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_pagos = mysql_fetch_array($query_pagos)) {
			list($a, $m, $d)=SPLIT( '[/.-]', $field_pagos['fecha_vencimiento']);
			$vencimiento = "Debe cancelarse antes del $d de ".nombreMes($field_pagos['fecha_vencimiento'])." de $a";
			
			if ($grupo != $field_pagos['fecha_vencimiento']) {
				$grupo = $field_pagos['fecha_vencimiento'];
				$nropago++;
				$pdf->SetFillColor(235, 235, 235);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(195, 5, 'PAGO '.$nropago, 0, 1, 'L', 1);
			}
			
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode('('.$field_pagos['codigo'].') '.$field_pagos['denominacion']),
							number_format($field_pagos['monto_total'], 2, ',', '.'),
							utf8_decode($vencimiento)));
			
			if ($pdf->GetY() > 250) {
				solicitud_de_calculo($pdf);
				//	imprimo los pagos pendientes
				$pdf->SetDrawColor(0, 0, 0);
				$pdf->SetTextColor(0, 0, 0);
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetFont('Arial', 'BU', 8);
				$pdf->Cell(195, 5, 'PAGOS.', 0, 0, 'L', 1);
				$pdf->Ln(7);
				$pdf->SetFillColor(235, 235, 235);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->SetAligns(array('L', 'R', 'L'));
				$pdf->SetWidths(array(75, 25, 95));
				$pdf->SetHeight(array(6));
				$pdf->Row(array('Concepto',
								'Monto',
								'Fecha de proximo de pago'));
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetDrawColor(255, 255, 255);
				$pdf->SetFont('Arial', '', 8);
				$pdf->Ln(2);
			}
		}
	}
}

//	Recaudacion
elseif ($nombre == "recaudacion") {
	$pdf=new PDF_MC_Table8('P', 'mm', 'Letter');
	$pdf->Open();
	//	--------------------
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(0, 0, 0);	
	$pdf->SetFillColor(255, 255, 255);
	recaudacion($pdf);
	//	--------------------
	//	obtengo los datos del contribuyente
	$sql = "SELECT
				pr.*,
				c.razon_social,
				c.rif,
				c.nro_casa,
				c.punto_referencia,				
				cr.denominacion AS nomcarrera,
				cl.denominacion AS nomcalle,
				u.denominacion AS nomurbanizacion,
				s.denominacion AS nomsector,
				p.denominacion AS nomparroquia,
				m.denominacion AS nommunicipio,
				e.denominacion AS nomestado,
				b1.denominacion AS nombanco1,
				b2.denominacion AS nombanco2,
				cb1.numero_cuenta
			FROM
				pagos_recaudacion pr
				INNER JOIN contribuyente c ON (pr.idcontribuyente = c.idcontribuyente)
				LEFT JOIN carrera cr ON (c.carrera = cr.idcarrera)
				LEFT JOIN calle cl ON (cr.idcalle = cl.idcalle)
				LEFT JOIN urbanizacion u ON (cl.idurbanizacion = u.idurbanizacion)
				LEFT JOIN sectores s ON (u.idsectores = s.idsectores)
				LEFT JOIN parroquia p ON (s.idparroquia = p.idparroquia)
				LEFT JOIN municipios m ON (p.idmunicipios = m.idmunicipios)
				LEFT JOIN estado e ON (m.idestado = e.idestado)
				LEFT JOIN banco b1 ON (pr.idbanco = b1.idbanco)
				LEFT JOIN banco b2 ON (pr.banco = b2.idbanco)
				LEFT JOIN cuentas_bancarias cb1 ON (pr.idcuenta = cb1.idcuentas_bancarias)
			WHERE
				pr.idpagos_recaudacion = '".$idpagos_recaudacion."'";
	$query_recaudacion = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_recaudacion) != 0) $field_recaudacion = mysql_fetch_array($query_recaudacion);
	//	--------------------
	$direccion = $field_recaudacion['punto_referencia'].' Casa/Local # '.$field_recaudacion['nro_casa'].' Carrera '.$field_recaudacion['nomcarrera'].' Calle '.$field_recaudacion['nomcalle'].' Urb./Barrio '.$field_recaudacion['nomurbanizacion'].' Sector '.$field_recaudacion['nomsector'].' Parroquia '.$field_recaudacion['nomparroquia'].' Municipio '.$field_recaudacion['nommunicipio'].' Estado '.$field_recaudacion['nomestado'];
	//	--------------------
	//	imprimo los datos generales	
	$pdf->SetFont('Arial', 'BU', 8);
	$pdf->Cell(195, 4, 'DATOS DEL CONTRIBUYENTE.', 0, 0, 'L', 1);
	$pdf->Ln(7);
	
	$pdf->SetFont('Arial', 'B', 8); 
	$pdf->Cell(150, 4, 'PERSONA NATURAL O RAZON SOCIAL', 1, 0, 'C', 1);
	$pdf->Cell(45, 4, 'RIF/C.I.', 1, 1, 'C', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(150, 4, utf8_decode($field_recaudacion['razon_social']), 1, 0, 'L', 1);
	$pdf->Cell(45, 4, $field_recaudacion['rif'], 1, 1, 'C', 1);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(195, 4, 'DIRECCION FISCAL', 1, 1, 'C', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->MultiCell(195, 4, utf8_decode(strtoupper($direccion)), 1, 'J', 1);
	$pdf->Ln(2);
	//	--------------------
	//	imprimo los conceptos
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('C', 'L', 'C', 'R'));
	$pdf->SetWidths(array(30, 105, 30, 30));
	$pdf->SetHeight(array(4));
	$pdf->Row(array('Nro. Solicitud',
					'Concepto',
					'Fecha Vencimiento',
					'Monto'));
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetDrawColor(255, 255, 255);	
	$pdf->Ln(2);
	
	//	obtengo los conceptos
	$sql = "SELECT
				fcc.*,
				rfvc.fecha_vencimiento,
				sc.numero_solicitud,
				ct.codigo,
				ct.denominacion
			FROM
				fracciones_conceptos_canceladas fcc
				INNER JOIN rango_fecha_vencimiento_conceptos rfvc ON (fcc.idrango_fecha_vencimiento_conceptos = rfvc.idrango_fecha_vencimiento_conceptos)
				INNER JOIN conceptos_solicitud_calculo csc ON (rfvc.idconcepto_solicitud_calculo = csc.idconceptos_solicitud_calculo)
				INNER JOIN solicitud_calculo sc ON (csc.idsolicitud_calculo = sc.idsolicitud_calculo)
				INNER JOIN concepto_tributario ct ON (csc.idconcepto = ct.idconcepto_tributario)
			WHERE fcc.idpagos_recaudacion = '".$idpagos_recaudacion."'";
	$query_conceptos = mysql_query($sql) or die ($sql.mysql_error());
	while($field_conceptos = mysql_fetch_array($query_conceptos)) {
		$pdf->SetFont('Arial', '', 8);
		$pdf->Row(array($field_conceptos['numero_solicitud'],
						'('.$field_conceptos['codigo'].') '.$field_conceptos['denominacion'],
						formatoFecha($field_conceptos['fecha_vencimiento']),
						number_format($field_conceptos['monto_cancelado'], 2, ',', '.')));
		
		if ($field_conceptos['monto_mora'] != 0) {
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(165, 4, 'TOTAL MORA', 0, 0, 'L');
			$pdf->Cell(30, 4, number_format($field_conceptos['monto_mora'], 2, ',', '.'), 0, 1, 'R');
		}
	}	
	$pdf->Ln(3);
	
	//	imprimo los otros datos
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(0, 0, 0);	
	$pdf->SetFillColor(255, 255, 255);
	
	$pdf->SetFont('Arial', 'B', 8); 
	$pdf->Cell(126, 4, 'RECAUDADOR(A)/FISCAL DE HACIENDA', 1, 0, 'L', 1);	
	$pdf->Cell(39, 4, 'Monto a Cancelar', 0, 0, 'R');
	$pdf->Cell(30, 4, number_format($field_recaudacion['total'], 2, ',', '.'), 1, 1, 'R');	
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(126, 4, utf8_decode($field_recaudacion['recibido_por']), 1, 0, 'L', 1);	
	$pdf->Cell(39, 4, 'Impuestos Retenidos', 0, 0, 'R');
	$pdf->Cell(30, 4, number_format($field_recaudacion[''], 2, ',', '.'), 1, 1, 'R');
	
	$pdf->SetFont('Arial', 'B', 8); 
	$pdf->Cell(63, 4, 'BANCO', 1, 0, 'L', 1); 
	$pdf->Cell(63, 4, 'FORMA DE PAGO', 1, 0, 'C', 1);
	$pdf->Cell(39, 4, 'Descuento', 0, 0, 'R');
	$pdf->Cell(30, 4, number_format($field_recaudacion['descuento'], 2, ',', '.'), 1, 1, 'R');
	
	$pdf->SetFont('Arial', '', 8); 
	$pdf->Cell(63, 4, $field_recaudacion['nombanco1'], 1, 0, 'L', 1); 
	$pdf->Cell(63, 4, $forma_pago, 1, 0, 'L', 1);
	$pdf->Cell(39, 4, 'Total Cancelar', 0, 0, 'R');
	$pdf->Cell(30, 4, number_format($field_recaudacion['total_cancelar'], 2, ',', '.'), 1, 1, 'R');
	
	$pdf->SetFont('Arial', 'B', 8); 
	$pdf->Cell(63, 4, 'NRO. CUENTA', 1, 0, 'L', 1); 
	$pdf->Cell(63, 4, 'BANCO', 1, 1, 'L', 1);
	
	$pdf->SetFont('Arial', '', 8); 
	$pdf->Cell(63, 4, $field_recaudacion['numero_cuenta'], 1, 0, 'L', 1); 
	$pdf->Cell(63, 4, $field_recaudacion['nombanco2'], 1, 1, 'L', 1);
	
	$pdf->SetFont('Arial', 'B', 8); 
	$pdf->Cell(63, 4, 'NRO. DEPOSITO', 1, 0, 'L', 1); 
	$pdf->Cell(63, 4, 'NRO. CHEQUE', 1, 1, 'L', 1);
	
	$pdf->SetFont('Arial', '', 8); 
	$pdf->Cell(63, 4, $field_recaudacion['nro_documento'], 1, 0, 'L', 1); 
	$pdf->Cell(63, 4, $field_recaudacion['nro_cheque_tarjeta'], 1, 1, 'L', 1);
	
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(0, 0, 0);	
	$pdf->SetFillColor(0, 0, 0);
	$y = $pdf->GetY() + 5;
	$pdf->SetY($y);
	$pdf->Rect(150, $y, 50, 0.1, 'DF');
	
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(0, 0, 0);	
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetX(150); $pdf->Cell(50, 4, 'Firma del Liquidador', 0, 1, 'C');
}
//	----------------------------------------
//	FIN CUERPO DE LOS REPORTES
//	----------------------------------------

$pdf->Output();
?>