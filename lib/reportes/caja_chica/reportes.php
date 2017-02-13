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
require('head.php');
require('../firmas.php');
require('../../mc_table.php');
require('../../mc_table2.php');
require('../../mc_table3.php');
require('../../mc_table4.php');
require('../../mc_table5.php');
require('../../mc_table6.php');
require('../../mc_table7.php');
require('../../mc_table8.php');
require('../../../conf/conex.php');
//	----------------------------------------
Conectarse();
$ahora=date("d-m-Y H:i:s");
//	----------------------------------------

//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
//	Relación de Facturas por Rendición...
case "relacion_facturas_por_rendicion":
	$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
	$pdf->Open();
	//	--------------------------------------
	$sql = "SELECT
				ocs.numero_orden,
				ocs.fecha_orden,
				ocs.justificacion,
				b.nombre as beneficiario
			FROM
				orden_compra_servicio ocs
				LEFT JOIN beneficiarios b ON (ocs.idbeneficiarios = b.idbeneficiarios)
			WHERE
				ocs.idorden_compra_servicio = '".$idorden_compra_servicio."'";
	$query_head = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_head) != 0) $field_head = mysql_fetch_array($query_head);
	//	--------------------------------------
	relacion_facturas_por_rendicion($pdf, $field_head['numero_orden'], $field_head['fecha_orden'], $field_head['justificacion'], $field_head['beneficiario']);
	//	--------------------------------------
	$sql = "SELECT
				frcc.*,
				b.nombre AS nombeneficiario,
				(SELECT SUM(total) 
					FROM articulos_rendicion_caja_chica 
						WHERE idorden_compra_servicio = '".$idorden_compra_servicio."' AND 
							idfactura_rendicion_caja_chica = frcc.idfactura_rendicion_caja_chica) AS base,
				(SELECT SUM(exento) 
					FROM articulos_rendicion_caja_chica 
						WHERE idorden_compra_servicio = '".$idorden_compra_servicio."' AND 
							idfactura_rendicion_caja_chica = frcc.idfactura_rendicion_caja_chica) AS exento,
				(SELECT SUM(impuesto) 
					FROM articulos_rendicion_caja_chica 
						WHERE idorden_compra_servicio = '".$idorden_compra_servicio."' AND 
							idfactura_rendicion_caja_chica = frcc.idfactura_rendicion_caja_chica) AS impuesto
			FROM
				facturas_rendicion_caja_chica frcc
				LEFT JOIN beneficiarios b ON (frcc.idbeneficiarios = b.idbeneficiarios)
			WHERE
				frcc.idorden_compra_servicio = '".$idorden_compra_servicio."'";
	$query_facturas = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_facturas = mysql_fetch_array($query_facturas)) {
		$total = $field_facturas['base'] + $field_facturas['exento'] + $field_facturas['impuesto'];
		
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetAligns(array('L', 'L', 'C', 'R', 'R', 'R', 'R'));
		$pdf->SetWidths(array(25, 55, 20, 25, 25, 25, 25));
		$pdf->Row(array($field_facturas['nro_factura'], 
						$field_facturas['nombeneficiario'], 
						formatoFecha($field_facturas['fecha_factura']), 
						number_format($field_facturas['base'], 2, ',', '.'), 
						number_format($field_facturas['exento'], 2, ',', '.'), 
						number_format($field_facturas['impuesto'], 2, ',', '.'), 
						number_format($total, 2, ',', '.')));
		
		//	Si selecciono detallado
		if ($tipo == "detallado") {
			$pdf->Ln(2);
			relacion_facturas_por_rendicion_2($pdf);
			
			$sql="SELECT acs.idarticulos_servicios, 
						 acs.cantidad, 
						 acs.precio_unitario, 
						 acs.total, 
						 acs.exento, 
						 ass.idunidad_medida, 
						 ass.descripcion, 
						 um.abreviado 
					FROM 
						 articulos_compra_servicio acs
						 LEFT JOIN articulos_servicios ass ON (acs.idarticulos_servicios = ass.idarticulos_servicios)
						 LEFT JOIN unidad_medida um ON (ass.idunidad_medida = um.idunidad_medida)
					WHERE 
						 acs.idorden_compra_servicio = '".$idorden_compra_servicio."'";
			$query_detalle = mysql_query($sql) or die ($sql.mysql_error()); $i=0;
			while ($field_detalle = mysql_fetch_array($query_detalle)) {
				$i++;
				$pdf->Ln(1);
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'L', 'L', 'R', 'R'));
				$pdf->SetWidths(array(10, 25, 35, 70, 25, 25));
				$pdf->Cell(10); 
				$pdf->Row(array($i, 
								$field_detalle['abreviado'], 
								utf8_decode($field_detalle['descripcion']), 
								number_format($field_detalle['precio_unitario'], 2, ',', '.'), 
								number_format($field_detalle['total'], 2, ',', '.')));
				
			}
			
			if (mysql_num_rows($query_detalle) != 0 && $flagpartidas != "S") { $pdf->Ln(5); relacion_facturas_por_rendicion_1($pdf); }
		}
		
		//	Si selecciono mostrar partidas
		if ($flagpartidas == "S") {
			$pdf->Ln(2);
			relacion_facturas_por_rendicion_3($pdf);
			
			$sql = "SELECT
						pocs.monto,
						cp.denominacion,
						cp.partida,
						cp.generica,
						cp.especifica,
						cp.sub_especifica,
						c.codigo
					FROM
						partidas_orden_compra_servicio pocs
						LEFT JOIN maestro_presupuesto mp ON (pocs.idmaestro_presupuesto = mp.idRegistro)
						LEFT JOIN clasificador_presupuestario cp ON (mp.idclasificador_presupuestario = cp.idclasificador_presupuestario)
						LEFT JOIN categoria_programatica c ON (mp.idcategoria_programatica = c.idcategoria_programatica)
					WHERE
						pocs.idorden_compra_servicio = '".$idorden_compra_servicio."'
					ORDER BY codigo, partida, generica, especifica, sub_especifica";
			$query_partidas = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_partidas = mysql_fetch_array($query_partidas)) {
				$partida = $field_partidas['partida'].".".$field_partidas['generica'].".".$field_partidas['especifica'].".".$field_partidas['sub_especifica'];
				
				$pdf->Ln(1);
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'L', 'L', 'R'));
				$pdf->SetWidths(array(25, 25, 115, 25));
				$pdf->Cell(10); 
				$pdf->Row(array($field_partidas['codigo'],
								$partida, 
								utf8_decode($field_partidas['denominacion']), 
								number_format($field_partidas['monto'], 2, ',', '.')));
				
			}
			
			if (mysql_num_rows($query_partidas) != 0) { $pdf->Ln(5); relacion_facturas_por_rendicion_1($pdf); }
		}
		
		$pdf->Ln(2);
	}
	
	break;
	
	
	
//	(Modulo) Apertura de Caja Chica...
case "apertura_caja_chica":
	$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(5);
	$pdf->SetAutoPageBreak(1, 2.5);
	$pag=0;
	//-----------
	$sql="SELECT nombre_institucion FROM configuracion";
	$query=mysql_query($sql);
	$rows=mysql_num_rows($query);
	if ($rows!=0) $field=mysql_fetch_array($query);
	$despachar=$field['nombre_institucion'];
	//-----------
	//OBTENGO LOS DATOS DE CABECERA Y LOS ENVIO A LA FUNCION PARA QUE LAS IMPRIMA
	$sql="SELECT orden_compra_servicio.numero_orden, 
				 orden_compra_servicio.fecha_orden, 
				 tipos_documentos.descripcion, 
				 orden_compra_servicio.idbeneficiarios, 
				 beneficiarios.nombre, 
				 beneficiarios.rif, 
				 categoria_programatica.idcategoria_programatica, 
				 unidad_ejecutora.denominacion, 
				 orden_compra_servicio.justificacion, 
				 orden_compra_servicio.numero_requisicion, 
				 orden_compra_servicio.fecha_requisicion, 
				 orden_compra_servicio.sub_total, 
				 orden_compra_servicio.impuesto, 
				 orden_compra_servicio.total, 
				 orden_compra_servicio.exento, 
				 categoria_programatica.codigo, 
				 orden_compra_servicio.descuento, 
				 orden_compra_servicio.exento_original, 
				 orden_compra_servicio.sub_total_original, 
				 tipos_documentos.idtipos_documentos  
			FROM 
				 orden_compra_servicio, 
				 tipos_documentos, 
				 beneficiarios, 
				 categoria_programatica, 
				 unidad_ejecutora 
			WHERE 
				 (orden_compra_servicio.idorden_compra_servicio='".$_GET['id_orden_compra']."') AND 
				 (orden_compra_servicio.tipo=tipos_documentos.idtipos_documentos) AND 
				 (orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios) AND 
				 (orden_compra_servicio.idcategoria_programatica=categoria_programatica.idcategoria_programatica AND
				 categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) {
		$field=mysql_fetch_array($query);
		$documento=$field['idtipos_documentos'];
		$id_categoria_programatica=$field[6];
		$categoria_programatica=$field[15];
		$numero=$field[0];
		$fecha=$field[1];
		//if ($field[2]=="Compras") $tipo="Compra"; else $tipo="Servicio";
		$tipo=$field[2];
		$nombre=$field[4];
		$rif=$field[5];
		$unidad=$field[7];
		$justificacion=$field[8];
		$nrequisicion=$field[9];
		$frequisicion=$field[10];
		$impuesto=number_format($field[12], 2, ',', '.');
		$total_general=number_format($field[13], 2, ',', '.');
		$descuento=$field['descuento'];
		$valor_descuento=number_format($field['descuento'], 2, ',', '.');
		if ($descuento>0) {
			$agregar_descuento=1;
			$exento=number_format($field['exento_original'], 2, ',', '.');
			$sub_total=number_format($field['sub_total_original'], 2, ',', '.');
		} else { 
			$exento=number_format($field[14], 2, ',', '.');
			$sub_total=number_format($field[11], 2, ',', '.');
			$agregar_descuento=0;
		}
		
		$sql="SELECT partidas_orden_compra_servicio.idorden_compra_servicio, 
					 maestro_presupuesto.anio, 
					 tipo_presupuesto.denominacion AS TipoPresupuesto, 
					 fuente_financiamiento.denominacion AS FuenteFinanciamiento 
				FROM 
					 partidas_orden_compra_servicio, 
					 maestro_presupuesto, 
					 tipo_presupuesto, 
					 fuente_financiamiento 
				WHERE 
					 (partidas_orden_compra_servicio.idorden_compra_servicio='".$_GET['id_orden_compra']."' AND
					 partidas_orden_compra_servicio.idmaestro_presupuesto=maestro_presupuesto.idRegistro AND 
					 maestro_presupuesto.idtipo_presupuesto=tipo_presupuesto.idtipo_presupuesto AND
					 maestro_presupuesto.idfuente_financiamiento=fuente_financiamiento.idfuente_financiamiento)";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {
			$field=mysql_fetch_array($query);
			$anio=$field['anio'];
			$tpresupuesto=$field['TipoPresupuesto'];
			$ffinanciamiento=$field['FuenteFinanciamiento'];			
		}
	}
	$pag=1;
	apertura_caja_chica($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo, $documento);
	//-----------
	
	$y=150;
	//IMPRIMO LOS TOTALES Y OBTENGO EL TIPO DE IMPUESTO
	$sql="SELECT relacion_impuestos_ordenes_compras.idimpuestos, 
				 impuestos.siglas, 
				 impuestos.porcentaje, 
				 relacion_impuestos_ordenes_compras.total 
			FROM 
				 relacion_impuestos_ordenes_compras, 
				 impuestos 
			WHERE 
				 (relacion_impuestos_ordenes_compras.idorden_compra_servicio='".$_GET['id_orden_compra']."') AND 
				 (relacion_impuestos_ordenes_compras.idimpuestos=impuestos.idimpuestos)";
	$query_impuestos=mysql_query($sql) or die ($sql.mysql_error());
	$nro_impuestos=mysql_num_rows($query_impuestos);

	//OBTENGO LAS PARTIDAS Y LAS IMPRIMO
	$sql="SELECT partidas_orden_compra_servicio.idmaestro_presupuesto, 
					 partidas_orden_compra_servicio.monto, 
					 clasificador_presupuestario.denominacion, 
					 clasificador_presupuestario.partida, 
					 clasificador_presupuestario.generica, 
					 clasificador_presupuestario.especifica, 
					 clasificador_presupuestario.sub_especifica, 
					 maestro_presupuesto.idclasificador_presupuestario, 
					 categoria_programatica.codigo 
				FROM 
					partidas_orden_compra_servicio, 
					clasificador_presupuestario, 
					maestro_presupuesto, 
					categoria_programatica 
				WHERE 
					(partidas_orden_compra_servicio.idorden_compra_servicio='".$id_orden_compra."') AND 
					(partidas_orden_compra_servicio.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND 
					(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
					maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica
				ORDER BY 
					 categoria_programatica.codigo,
					 clasificador_presupuestario.partida, 
					 clasificador_presupuestario.generica, 
					 clasificador_presupuestario.especifica, 
					 clasificador_presupuestario.sub_especifica";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows_partidas=mysql_num_rows($query);
	
	$ycuadro=$y+((3+$nro_impuestos+$agregar_descuento)*5);
	if ($ycuadro>=220) {
		$pag++;
		apertura_caja_chica($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo, $documento);
		if ($rows_partidas>6) $y=215-((3+$nro_impuestos)*5); else $y=180-((3+$nro_impuestos)*5);
	}
	else if ($ycuadro<=155) $y=150;
	else $y=210-((3+$nro_impuestos)*5);
	//-----------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(150, 150, 150); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Rect(5.00125, $y-6, 205, 0.1);
	$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y-3); $pdf->MultiCell($w, l, 'TOTAL: ', 0, 'R'); 	
	$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y-3); $pdf->MultiCell($w, l, $total_general, 0, 'R');
	$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y+2); $pdf->MultiCell($w, l, '', 0, 'R'); 	
	$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y+2); $pdf->MultiCell($w, l, '', 0, 'R');
	
	$y=$pdf->GetY();
	$pdf->SetFont('Arial', 'B', 12);
	$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, '', 0, 'R'); 	
	$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, '', 0, 'R');
	
	$y=$pdf->GetY();
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY(5, $y-13); $pdf->Cell(50, 5, utf8_decode('AÑO:  '.$anio), 0, 1, 'L');
	$pdf->SetXY(5, $y-8); $pdf->Cell(100, 5, 'TIPO DE PRESUPUESTO:  '.$tpresupuesto, 0, 1, 'L'); 
	$pdf->SetXY(5, $y-3); $pdf->Cell(100, 5, 'FUENTE DE FINANCIAMIENTO:  '.$ffinanciamiento, 0, 1, 'L');
	$pdf->SetXY(5, $y+2); $pdf->Cell(100, 5, 'CATEGORIA PROGRAMATICA:  '.$categoria_programatica.' '.$unidad, 0, 1, 'L');
	//-----------
	break;
	

//	Tipos de Caja Chica...
case "tipos_caja_chica":
	$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
	$pdf->Open();
	//	--------------------------------------
	tipos_caja_chica($pdf);
	//	--------------------------------------
	$sql = "select * from tipo_caja_chica";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		$pdf->Row(array(utf8_decode($field['denominacion']),
						$field['gaceta_nro'], 
						$field['resolucion_nro'], 
						number_format($field['unidades_tributarias_aprobadas'], 2, ',', '.')));
		
		if ($pdf->GetY() > 200) tipos_caja_chica($pdf);
	}
	
	break;
}
//	----------------------------------------
//	FIN CUERPO DE LOS REPORTES
//	----------------------------------------

$pdf->Output();
?>