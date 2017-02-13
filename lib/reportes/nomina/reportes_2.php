<?php
session_start();
set_time_limit(-1);
ini_set("memory_limit", "200M");
extract($_POST);
extract($_GET);
//	----------------------------------------
$MAXLINEA=30;
$MAXLINEALEG=15;
//	----------------------------------------
require('../../../conf/conex.php');
require('../../../funciones/funciones.php');
require('../../mc_table.php');
require('../../mc_table2.php');
require('../../mc_table3.php');
require('../../mc_table4.php');
require('../../mc_table5.php');
require('../../mc_table6.php');
require('../../mc_table7.php');
require('../../mc_table8.php');
require('../../mc_table9.php');
require('../firmas.php');
require('head.php');
//	----------------------------------------
Conectarse();
$ahora=date("d-m-Y H:i:s");
//	----------------------------------------

//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {	
	//	Auditoria de Nomina...
	case "auditoria_nomina":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		//	---------------------------------------
		//	Obtengo el id de la nomina generada....
		$sql = "SELECT idgenerar_nomina 
				FROM generar_nomina 
				WHERE idtipo_nomina = '".$nomina."' AND idperiodo = '".$periodo."' AND (estado = 'procesado' OR estado = 'Pre Nomina')";
		$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);
		//	---------------------------------------
		//	Obtengo los datos de la cabecera....
		$sql = "SELECT 
					tn.titulo_nomina,
					rpn.desde,
					rpn.hasta,
					rpn.idrango_periodo_nomina,
					rpn.idperiodo_nomina
				FROM 
					tipo_nomina tn
					INNER JOIN periodos_nomina pn ON (tn.idtipo_nomina = pn.idtipo_nomina)
					INNER JOIN generar_nomina gn ON (gn.idtipo_nomina = tn.idtipo_nomina AND idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."')
					INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina AND rpn.idrango_periodo_nomina = '".$periodo."')
				WHERE 
					tn.idtipo_nomina = '".$nomina."'
				GROUP BY tn.idtipo_nomina";
		$query_titulo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_titulo) != 0) $field_titulo = mysql_fetch_array($query_titulo);
		list($a, $m, $d)=SPLIT( '[/.-]', $field_titulo['desde']); $desde = $d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $field_titulo['hasta']); $hasta = $d."/".$m."/".$a;
		$periodo = "Del $desde al $hasta";		
		$idrango_anterior = (int) ($field_titulo['idrango_periodo_nomina'] - 1);
		//	---------------------------------------
		//	Obtengo el id de la nomina anterior....
		$sql = "SELECT 
					rpn.desde,
					rpn.hasta,
					gn.idgenerar_nomina
				FROM 
					tipo_nomina tn
					INNER JOIN periodos_nomina pn ON (tn.idtipo_nomina = pn.idtipo_nomina)
					INNER JOIN generar_nomina gn ON (gn.idtipo_nomina = tn.idtipo_nomina)
					INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina)
				WHERE 
					tn.idtipo_nomina = '".$nomina."' AND
					rpn.idrango_periodo_nomina = '".$idrango_anterior."' AND
					rpn.idperiodo_nomina = '".$field_titulo['idperiodo_nomina']."'
				GROUP BY tn.idtipo_nomina";
		$query_nomina_anterior = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nomina_anterior) != 0) $field_nomina_anterior = mysql_fetch_array($query_nomina_anterior);
		//	---------------------------------------
		auditoria_nomina($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'], 'Conceptos');
		//	---------------------------------------
		if ($flagunidad == "S" && $unidad != "0") $filtro_unidad = " AND t.idunidad_funcional = '".$unidad."'";
		if ($flagcentro == "S" && $centro != "0") $filtro_centro = " AND t.centro_costo = '".$centro."'";
		if ($idtrabajador != "") $filtro_trabajador = " AND rgn.idtrabajador = '".$idtrabajador."'";
		//	---------------------------------------
		//	Obtengo los conceptos y los trabajadores que fueron generados en este periodo y el anterior...
		$sql = "(SELECT
					rgn.idtrabajador, rgn.idconcepto, rgn.tabla, rgn.total AS total_actual,
					t.cedula, t.apellidos, t.nombres, t.fecha_ingreso, t.nro_ficha, t.centro_costo, t.idunidad_funcional,
					c.denominacion AS nomcargo, c.grado, c.paso,					
					nor.denominacion AS nomunidad, nor.codigo AS codunidad,
					ue.denominacion AS nomcentro,
					cp.codigo AS codcentro,					
					cn.descripcion,
					(SELECT rgn2.total
					 FROM
						relacion_generar_nomina rgn2
						INNER JOIN conceptos_nomina cn2 ON (rgn2.idconcepto = cn2.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.tipo_concepto = tcn2.idconceptos_nomina AND afecta = 'asignacion')
						WHERE
							rgn2.idconcepto = rgn.idconcepto AND
							rgn2.total > 0 AND
							rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
							rgn2.idtrabajador = rgn.idtrabajador AND
							rgn2.tabla = 'conceptos_nomina') AS total_anterior
				FROM
					relacion_generar_nomina rgn
					LEFT JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					LEFT JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					LEFT JOIN niveles_organizacionales nor ON (mp.idubicacion_funcional = nor.idniveles_organizacionales)
					LEFT JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
					LEFT JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
					INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
				WHERE
					rgn.total > 0 AND
					rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
					rgn.tabla = 'conceptos_nomina' AND
					mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal)
												 FROM movimientos_personal
												 WHERE idtrabajador = t.idtrabajador AND fecha_movimiento <= '".$field_titulo['hasta']."') AND
					(rgn.total - (SELECT rgn2.total
								  FROM
									relacion_generar_nomina rgn2
									INNER JOIN conceptos_nomina cn2 ON (rgn2.idconcepto = cn2.idconceptos_nomina)
									INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.tipo_concepto = tcn2.idconceptos_nomina AND afecta = 'asignacion')
								  WHERE
									rgn2.idconcepto = rgn.idconcepto AND
									rgn2.total > 0 AND
									rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
									rgn2.idtrabajador = rgn.idtrabajador AND
									rgn2.tabla = 'conceptos_nomina') > 0)
					GROUP BY tabla, idconcepto, idtrabajador)
		
				UNION
				
				(SELECT
					rgn.idtrabajador, rgn.idconcepto, rgn.tabla, rgn.total AS total_actual,
					t.cedula, t.apellidos, t.nombres, t.fecha_ingreso, t.nro_ficha, t.centro_costo, t.idunidad_funcional,
					c.denominacion AS nomcargo, c.grado, c.paso,
					nor.denominacion AS nomunidad, nor.codigo AS codunidad,
					ue.denominacion AS nomcentro,
					cp.codigo AS codcentro,					
					cn.descripcion,
					(SELECT rgn2.total
					 FROM
						relacion_generar_nomina rgn2
						INNER JOIN constantes_nomina cn2 ON (rgn2.idconcepto = cn2.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.afecta = tcn2.idconceptos_nomina AND tcn2.afecta = 'asignacion')
					 WHERE
						rgn2.idconcepto = rgn.idconcepto AND
						rgn2.total > 0 AND
						rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
						rgn2.idtrabajador = rgn.idtrabajador AND
						rgn2.tabla = 'constantes_nomina') AS total_anterior
				FROM
					relacion_generar_nomina rgn
					LEFT JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					LEFT JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					LEFT JOIN niveles_organizacionales nor ON (mp.idubicacion_funcional = nor.idniveles_organizacionales)
					LEFT JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
					LEFT JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
					INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
				WHERE
					rgn.total > 0 AND
					rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
					rgn.tabla = 'constantes_nomina' AND
					mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal)
												 FROM movimientos_personal
												 WHERE idtrabajador = t.idtrabajador AND fecha_movimiento <= '".$field_titulo['hasta']."') AND
					(rgn.total - (SELECT rgn2.total
								  FROM
									relacion_generar_nomina rgn2
									INNER JOIN constantes_nomina cn2 ON (rgn2.idconcepto = cn2.idconstantes_nomina)
									INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.afecta = tcn2.idconceptos_nomina AND tcn2.afecta = 'asignacion')
								  WHERE
									rgn2.idconcepto = rgn.idconcepto AND
									rgn2.total > 0 AND
									rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
									rgn2.idtrabajador = rgn.idtrabajador AND
									rgn2.tabla = 'constantes_nomina') > 0)
				GROUP BY tabla, idconcepto, idtrabajador)
				
				ORDER BY idconcepto, length(cedula), cedula";
		$query_trabajador = mysql_query($sql) or die ($sql.mysql_error()); $flag=false;
		while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
			
			if ($tabla != $field_trabajador['tabla'] || $idconcepto != $field_trabajador['idconcepto'] || $flag) {
				$tabla = $field_trabajador['tabla'];
				$idconcepto = $field_trabajador['idconcepto'];
				$flag=false;
				
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(50, 5, utf8_decode($field_trabajador['descripcion']), 0, 0, 'L');
				$pdf->Ln(5);
				
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
				$pdf->Rect(5, $pdf->GetY(), 205, 0.5, "DF");
				$pdf->Ln(5);
				
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->SetAligns(array('R', 'L', 'R', 'R', 'R'));
				$pdf->SetWidths(array(20, 115, 20, 20, 20));				
				$pdf->Row(array('Cedula',
								'Nombre y Apellido',
								'Anterior',
								'Actual',
								'Diferencia'));
				$pdf->Ln(2);
			}
			
			$diferencia = $field_trabajador['total_actual'] - $field_trabajador['total_anterior'];
			$pdf->SetFont('Arial', '', 8);	
			$pdf->Row(array(number_format($field_trabajador['cedula'], 0, '', '.'),
							utf8_decode($field_trabajador['nombres'].' '.$field_trabajador['apellidos']),
							number_format($field_trabajador['total_anterior'], 2, ',', '.'),
							number_format($field_trabajador['total_actual'], 2, ',', '.'),
							number_format($diferencia, 2, ',', '.')));
			$pdf->Ln(2);
			
			$linea = $pdf->GetY(); 
			if ($linea > 250) { auditoria_nomina($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'], 'Conceptos'); $flag=true; }
		}
		//	---------------------------------------
		
		$tabla = ""; $idconcepto = "";
		auditoria_nomina($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'], 'Nuevos Ingresos'); $flag=true;
		
		//	---------------------------------------
		//	Obtengo los conceptos y los trabajadores que fueron generados en este periodo y el anterior...
		$sql = "(SELECT
					rgn.idtrabajador, rgn.idconcepto, rgn.tabla, rgn.total AS total_actual,
					t.cedula, t.apellidos, t.nombres, t.fecha_ingreso, t.nro_ficha, t.centro_costo, t.idunidad_funcional,
					c.denominacion AS nomcargo, c.grado, c.paso,					
					nor.denominacion AS nomunidad, nor.codigo AS codunidad,
					ue.denominacion AS nomcentro,
					cp.codigo AS codcentro,					
					cn.descripcion,
					(SELECT rgn2.total
					 FROM
						relacion_generar_nomina rgn2
						INNER JOIN conceptos_nomina cn2 ON (rgn2.idconcepto = cn2.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.tipo_concepto = tcn2.idconceptos_nomina AND afecta = 'asignacion')
						WHERE
							rgn2.idconcepto = rgn.idconcepto AND
							rgn2.total > 0 AND
							rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
							rgn2.idtrabajador = rgn.idtrabajador AND
							rgn2.tabla = 'conceptos_nomina') AS total_anterior
				FROM
					relacion_generar_nomina rgn
					LEFT JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					LEFT JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					LEFT JOIN niveles_organizacionales nor ON (mp.idubicacion_funcional = nor.idniveles_organizacionales)
					LEFT JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
					LEFT JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
					INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
				WHERE
					rgn.total > 0 AND
					rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
					rgn.tabla = 'conceptos_nomina' AND
					mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal)
												 FROM movimientos_personal
												 WHERE idtrabajador = t.idtrabajador AND fecha_movimiento <= '".$field_titulo['hasta']."') AND
					(rgn.total - (SELECT rgn2.total
								  FROM
									relacion_generar_nomina rgn2
									INNER JOIN conceptos_nomina cn2 ON (rgn2.idconcepto = cn2.idconceptos_nomina)
									INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.tipo_concepto = tcn2.idconceptos_nomina AND afecta = 'asignacion')
								  WHERE
									rgn2.idconcepto = rgn.idconcepto AND
									rgn2.total > 0 AND
									rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
									rgn2.idtrabajador = rgn.idtrabajador AND
									rgn2.tabla = 'conceptos_nomina') > 0) AND
					mp.fecha_ingreso > '".$field_nomina_anterior['hasta']."'
					GROUP BY tabla, idconcepto, idtrabajador)
		
				UNION
				
				(SELECT
					rgn.idtrabajador, rgn.idconcepto, rgn.tabla, rgn.total AS total_actual,
					t.cedula, t.apellidos, t.nombres, t.fecha_ingreso, t.nro_ficha, t.centro_costo, t.idunidad_funcional,
					c.denominacion AS nomcargo, c.grado, c.paso,
					nor.denominacion AS nomunidad, nor.codigo AS codunidad,
					ue.denominacion AS nomcentro,
					cp.codigo AS codcentro,					
					cn.descripcion,
					(SELECT rgn2.total
					 FROM
						relacion_generar_nomina rgn2
						INNER JOIN constantes_nomina cn2 ON (rgn2.idconcepto = cn2.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.afecta = tcn2.idconceptos_nomina AND tcn2.afecta = 'asignacion')
					 WHERE
						rgn2.idconcepto = rgn.idconcepto AND
						rgn2.total > 0 AND
						rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
						rgn2.idtrabajador = rgn.idtrabajador AND
						rgn2.tabla = 'constantes_nomina') AS total_anterior
				FROM
					relacion_generar_nomina rgn
					LEFT JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					LEFT JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					LEFT JOIN niveles_organizacionales nor ON (mp.idubicacion_funcional = nor.idniveles_organizacionales)
					LEFT JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
					LEFT JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
					INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
				WHERE
					rgn.total > 0 AND
					rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
					rgn.tabla = 'constantes_nomina' AND
					mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal)
												 FROM movimientos_personal
												 WHERE idtrabajador = t.idtrabajador AND fecha_movimiento <= '".$field_titulo['hasta']."') AND
					(rgn.total - (SELECT rgn2.total
								  FROM
									relacion_generar_nomina rgn2
									INNER JOIN constantes_nomina cn2 ON (rgn2.idconcepto = cn2.idconstantes_nomina)
									INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.afecta = tcn2.idconceptos_nomina AND tcn2.afecta = 'asignacion')
								  WHERE
									rgn2.idconcepto = rgn.idconcepto AND
									rgn2.total > 0 AND
									rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
									rgn2.idtrabajador = rgn.idtrabajador AND
									rgn2.tabla = 'constantes_nomina') > 0) AND
					mp.fecha_ingreso > '".$field_nomina_anterior['hasta']."'
				GROUP BY tabla, idconcepto, idtrabajador)
				
				ORDER BY idconcepto, length(cedula), cedula";
		$query_trabajador = mysql_query($sql) or die ($sql.mysql_error()); $flag=false;
		while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
			
			if ($tabla != $field_trabajador['tabla'] || $idconcepto != $field_trabajador['idconcepto'] || $flag) {
				$tabla = $field_trabajador['tabla'];
				$idconcepto = $field_trabajador['idconcepto'];
				$flag=false;
				
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(50, 5, utf8_decode($field_trabajador['descripcion']), 0, 0, 'L');
				$pdf->Ln(5);
				
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
				$pdf->Rect(5, $pdf->GetY(), 205, 0.5, "DF");
				$pdf->Ln(5);
				
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->SetAligns(array('R', 'L', 'R', 'R', 'R'));
				$pdf->SetWidths(array(20, 115, 20, 20, 20));				
				$pdf->Row(array('Cedula',
								'Nombre y Apellido',
								'Anterior',
								'Actual',
								'Diferencia'));
				$pdf->Ln(2);
			}
			
			$diferencia = $field_trabajador['total_actual'] - $field_trabajador['total_anterior'];
			$pdf->SetFont('Arial', '', 8);	
			$pdf->Row(array(number_format($field_trabajador['cedula'], 0, '', '.'),
							utf8_decode($field_trabajador['nombres'].' '.$field_trabajador['apellidos']),
							number_format($field_trabajador['total_anterior'], 2, ',', '.'),
							number_format($field_trabajador['total_actual'], 2, ',', '.'),
							number_format($diferencia, 2, ',', '.')));
			$pdf->Ln(2);
			
			$linea = $pdf->GetY(); 
			if ($linea > 250) { auditoria_nomina($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'], 'Nuevos Ingresos'); $flag=true; }
		}
		//	---------------------------------------
		
		$tabla = ""; $idconcepto = "";
		auditoria_nomina($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'], 'Egresos'); $flag=true;
		
		//	---------------------------------------
		//	Obtengo los conceptos y los trabajadores que fueron generados en este periodo y el anterior...
		$sql = "(SELECT
					rgn.idtrabajador, rgn.idconcepto, rgn.tabla, rgn.total AS total_actual,
					t.cedula, t.apellidos, t.nombres, t.fecha_ingreso, t.nro_ficha, t.centro_costo, t.idunidad_funcional,
					c.denominacion AS nomcargo, c.grado, c.paso,					
					nor.denominacion AS nomunidad, nor.codigo AS codunidad,
					ue.denominacion AS nomcentro,
					cp.codigo AS codcentro,					
					cn.descripcion,
					(SELECT rgn2.total
					 FROM
						relacion_generar_nomina rgn2
						INNER JOIN conceptos_nomina cn2 ON (rgn2.idconcepto = cn2.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.tipo_concepto = tcn2.idconceptos_nomina AND afecta = 'asignacion')
						WHERE
							rgn2.idconcepto = rgn.idconcepto AND
							rgn2.total > 0 AND
							rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
							rgn2.idtrabajador = rgn.idtrabajador AND
							rgn2.tabla = 'conceptos_nomina') AS total_anterior
				FROM
					relacion_generar_nomina rgn
					LEFT JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					LEFT JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					LEFT JOIN niveles_organizacionales nor ON (mp.idubicacion_funcional = nor.idniveles_organizacionales)
					LEFT JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
					LEFT JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
					INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
				WHERE
					rgn.total > 0 AND
					rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
					rgn.tabla = 'conceptos_nomina' AND
					mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal)
												 FROM movimientos_personal
												 WHERE idtrabajador = t.idtrabajador AND fecha_movimiento <= '".$field_titulo['hasta']."') AND
					(rgn.total - (SELECT rgn2.total
								  FROM
									relacion_generar_nomina rgn2
									INNER JOIN conceptos_nomina cn2 ON (rgn2.idconcepto = cn2.idconceptos_nomina)
									INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.tipo_concepto = tcn2.idconceptos_nomina AND afecta = 'asignacion')
								  WHERE
									rgn2.idconcepto = rgn.idconcepto AND
									rgn2.total > 0 AND
									rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
									rgn2.idtrabajador = rgn.idtrabajador AND
									rgn2.tabla = 'conceptos_nomina') > 0) AND
					mp.fecha_egreso >= '".$field_titulo['desde']."' AND mp.fecha_egreso <= '".$field_titulo['hasta']."'
					GROUP BY tabla, idconcepto, idtrabajador)
		
				UNION
				
				(SELECT
					rgn.idtrabajador, rgn.idconcepto, rgn.tabla, rgn.total AS total_actual,
					t.cedula, t.apellidos, t.nombres, t.fecha_ingreso, t.nro_ficha, t.centro_costo, t.idunidad_funcional,
					c.denominacion AS nomcargo, c.grado, c.paso,
					nor.denominacion AS nomunidad, nor.codigo AS codunidad,
					ue.denominacion AS nomcentro,
					cp.codigo AS codcentro,					
					cn.descripcion,
					(SELECT rgn2.total
					 FROM
						relacion_generar_nomina rgn2
						INNER JOIN constantes_nomina cn2 ON (rgn2.idconcepto = cn2.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.afecta = tcn2.idconceptos_nomina AND tcn2.afecta = 'asignacion')
					 WHERE
						rgn2.idconcepto = rgn.idconcepto AND
						rgn2.total > 0 AND
						rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
						rgn2.idtrabajador = rgn.idtrabajador AND
						rgn2.tabla = 'constantes_nomina') AS total_anterior
				FROM
					relacion_generar_nomina rgn
					LEFT JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					LEFT JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					LEFT JOIN niveles_organizacionales nor ON (mp.idubicacion_funcional = nor.idniveles_organizacionales)
					LEFT JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
					LEFT JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
					INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
				WHERE
					rgn.total > 0 AND
					rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
					rgn.tabla = 'constantes_nomina' AND
					mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal)
												 FROM movimientos_personal
												 WHERE idtrabajador = t.idtrabajador AND fecha_movimiento <= '".$field_titulo['hasta']."') AND
					(rgn.total - (SELECT rgn2.total
								  FROM
									relacion_generar_nomina rgn2
									INNER JOIN constantes_nomina cn2 ON (rgn2.idconcepto = cn2.idconstantes_nomina)
									INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.afecta = tcn2.idconceptos_nomina AND tcn2.afecta = 'asignacion')
								  WHERE
									rgn2.idconcepto = rgn.idconcepto AND
									rgn2.total > 0 AND
									rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
									rgn2.idtrabajador = rgn.idtrabajador AND
									rgn2.tabla = 'constantes_nomina') > 0) AND
					mp.fecha_egreso >= '".$field_titulo['desde']."' AND mp.fecha_egreso <= '".$field_titulo['hasta']."'
				GROUP BY tabla, idconcepto, idtrabajador)
				
				ORDER BY idconcepto, length(cedula), cedula";
		$query_trabajador = mysql_query($sql) or die ($sql.mysql_error()); $flag=false;
		while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
			
			if ($tabla != $field_trabajador['tabla'] || $idconcepto != $field_trabajador['idconcepto'] || $flag) {
				$tabla = $field_trabajador['tabla'];
				$idconcepto = $field_trabajador['idconcepto'];
				$flag=false;
				
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(50, 5, utf8_decode($field_trabajador['descripcion']), 0, 0, 'L');
				$pdf->Ln(5);
				
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
				$pdf->Rect(5, $pdf->GetY(), 205, 0.5, "DF");
				$pdf->Ln(5);
				
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->SetAligns(array('R', 'L', 'R', 'R', 'R'));
				$pdf->SetWidths(array(20, 115, 20, 20, 20));				
				$pdf->Row(array('Cedula',
								'Nombre y Apellido',
								'Anterior',
								'Actual',
								'Diferencia'));
				$pdf->Ln(2);
			}
			
			$diferencia = $field_trabajador['total_actual'] - $field_trabajador['total_anterior'];
			$pdf->SetFont('Arial', '', 8);	
			$pdf->Row(array(number_format($field_trabajador['cedula'], 0, '', '.'),
							utf8_decode($field_trabajador['nombres'].' '.$field_trabajador['apellidos']),
							number_format($field_trabajador['total_anterior'], 2, ',', '.'),
							number_format($field_trabajador['total_actual'], 2, ',', '.'),
							number_format($diferencia, 2, ',', '.')));
			$pdf->Ln(2);
			
			$linea = $pdf->GetY(); 
			if ($linea > 250) { auditoria_nomina($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'], 'Egresos'); $flag=true; }
		}
		//	---------------------------------------
		break;
	
	//	Auditoria de Nomina...
	case "auditoria_nomina_eliminar":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		//	---------------------------------------
		//	Obtengo el id de la nomina generada....
		$sql = "SELECT idgenerar_nomina FROM generar_nomina WHERE idtipo_nomina = '".$nomina."' AND idperiodo = '".$periodo."' AND (estado = 'procesado' OR estado = 'Pre Nomina')";
		$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);
		//	---------------------------------------
		//	Obtengo los datos de la cabecera....
		$sql = "SELECT 
					tn.titulo_nomina,
					rpn.desde,
					rpn.hasta,
					rpn.idrango_periodo_nomina,
					rpn.idperiodo_nomina
				FROM 
					tipo_nomina tn
					INNER JOIN periodos_nomina pn ON (tn.idtipo_nomina = pn.idtipo_nomina)
					INNER JOIN generar_nomina gn ON (gn.idtipo_nomina = tn.idtipo_nomina AND idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."')
					INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina AND rpn.idrango_periodo_nomina = '".$periodo."')
				WHERE 
					tn.idtipo_nomina = '".$nomina."'
				GROUP BY tn.idtipo_nomina";
		$query_titulo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_titulo) != 0) $field_titulo = mysql_fetch_array($query_titulo);
		list($a, $m, $d)=SPLIT( '[/.-]', $field_titulo['desde']); $desde = $d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $field_titulo['hasta']); $hasta = $d."/".$m."/".$a;
		$periodo = "Del $desde al $hasta";		
		$idrango_anterior = (int) ($field_titulo['idrango_periodo_nomina'] - 1);
		//	---------------------------------------
		//	Obtengo el id de la nomina anterior....
		$sql = "SELECT 
					rpn.desde,
					rpn.hasta,
					gn.idgenerar_nomina
				FROM 
					tipo_nomina tn
					INNER JOIN periodos_nomina pn ON (tn.idtipo_nomina = pn.idtipo_nomina)
					INNER JOIN generar_nomina gn ON (gn.idtipo_nomina = tn.idtipo_nomina)
					INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina)
				WHERE 
					tn.idtipo_nomina = '".$nomina."' AND
					rpn.idrango_periodo_nomina = '".$idrango_anterior."' AND
					rpn.idperiodo_nomina = '".$field_titulo['idperiodo_nomina']."'
				GROUP BY tn.idtipo_nomina";
		$query_nomina_anterior = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nomina_anterior) != 0) $field_nomina_anterior = mysql_fetch_array($query_nomina_anterior);
		//	---------------------------------------
		auditoria_nomina($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad']);
		//	---------------------------------------
		if ($flagunidad == "S" && $unidad != "0") $filtro_unidad = " AND t.idunidad_funcional = '".$unidad."'";
		if ($flagcentro == "S" && $centro != "0") $filtro_centro = " AND t.centro_costo = '".$centro."'";
		if ($idtrabajador != "") $filtro_trabajador = " AND rgn.idtrabajador = '".$idtrabajador."'";
		//	---------------------------------------
		//	Obtengo los datos de los trabajadores que pertenecen a esta nomina generada...
		$sql = "SELECT
					rgn.idtrabajador,
					t.cedula,
					t.apellidos,
					t.nombres,
					t.fecha_ingreso,
					t.nro_ficha,
					c.denominacion AS nomcargo,
					c.grado,
					c.paso,
					t.centro_costo,
					t.idunidad_funcional,
					no.denominacion AS nomunidad,
					no.codigo AS codunidad,
					ue.denominacion AS nomcentro,
					cp.codigo AS codcentro
				FROM
					relacion_generar_nomina rgn
					LEFT JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					LEFT JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					LEFT JOIN niveles_organizacionales no ON (mp.idubicacion_funcional = no.idniveles_organizacionales)
					LEFT JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
					LEFT JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
				WHERE
					rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."'  AND 
					mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal) 
												 FROM movimientos_personal 
												 WHERE 
												 	idtrabajador = t.idtrabajador AND
													fecha_movimiento <= '".$field_titulo['hasta']."')
					$filtro_unidad
					$filtro_centro
					$filtro_trabajador
				GROUP BY rgn.idtrabajador
				ORDER BY codcentro, length(cedula), cedula";
		$query_trabajador = mysql_query($sql) or die ($sql.mysql_error()); $i=0;
		$nro_trabajadores = mysql_num_rows($query_trabajador);
		while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
			//	Imprimo los datos del trabajador
			auditoria_nomina_trabajador($pdf, $field_trabajador, $field_titulo, $field_nomina_anterior);
			
			//	Imprimo las asignaciones del trabajador
			$sql = "(SELECT
					 	rgn.idconcepto,
						rgn.tabla,
						rgn.total AS total_actual,
						cn.descripcion,
						(SELECT rgn2.total 
						 FROM
							relacion_generar_nomina rgn2
							INNER JOIN conceptos_nomina cn2 ON (rgn2.idconcepto = cn2.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.tipo_concepto = tcn2.idconceptos_nomina AND afecta = 'asignacion')
						 WHERE
							rgn2.idconcepto = rgn.idconcepto AND
							rgn2.total > 0 AND
							rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
							rgn2.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn2.tabla = 'conceptos_nomina') AS total_anterior
					FROM
						relacion_generar_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
						rgn.tabla = 'conceptos_nomina')
			
					UNION
					
					(SELECT
					 	rgn.idconcepto,
						rgn.tabla,
						rgn.total AS total_actual,
						cn.descripcion,
						(SELECT rgn2.total 
						 FROM
							relacion_generar_nomina rgn2
							INNER JOIN conceptos_nomina cn2 ON (rgn2.idconcepto = cn2.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.tipo_concepto = tcn2.idconceptos_nomina AND afecta = 'asignacion')
						 WHERE
							rgn2.idconcepto = rgn.idconcepto AND
							rgn2.total > 0 AND
							rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
							rgn2.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn2.tabla = 'conceptos_nomina') AS total_anterior
					FROM
						relacion_generar_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
						rgn.tabla = 'constantes_nomina')";
			$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'R', 'R', 'R'));
				$pdf->SetWidths(array(115, 25, 25, 25));
				
				$diferencia = $field_asignaciones['total_actual'] - $field_asignaciones['total_anterior'];
				
				if ($diferencia != 0) {
					$pdf->Row(array(utf8_decode($field_asignaciones['descripcion']),
									number_format($field_asignaciones['total_anterior'], 2, ',', '.'),
									number_format($field_asignaciones['total_actual'], 2, ',', '.'),
									number_format($diferencia, 2, ',', '.')));
					
					$linea = $pdf->GetY(); if ($linea > 200) auditoria_nomina_trabajador($pdf, $field_trabajador, $field_titulo, $field_nomina_anterior);
				}
			}
			
			/*
			//	Imprimo las deducciones del trabajador	
			$sql = "(SELECT
						rgn.tabla,
						rgn.total,
						rgn.cantidad,
						cn.descripcion,
						(SELECT rgn2.total 
						 FROM
							relacion_generar_nomina rgn2
							INNER JOIN conceptos_nomina cn2 ON (rgn2.idconcepto = cn2.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.tipo_concepto = tcn2.idconceptos_nomina AND afecta = 'deduccion')
						 WHERE
							rgn2.idconcepto = rgn.idconcepto AND
							rgn2.total > 0 AND
							rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
							rgn2.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn2.tabla = 'conceptos_nomina') AS total_anterior
					FROM
						relacion_generar_nomina rgn
						LEFT JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
					WHERE
						total > 0 AND
						rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
						rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn.tabla = 'conceptos_nomina'
					GROUP BY rgn.idconcepto)
			
					UNION
			
					(SELECT
						rgn.tabla,
						rgn.total,
						rgn.cantidad,
						cn.descripcion,
						(SELECT rgn2.total 
						 FROM
							relacion_generar_nomina rgn2
							INNER JOIN conceptos_nomina cn2 ON (rgn2.idconcepto = cn2.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.tipo_concepto = tcn2.idconceptos_nomina AND afecta = 'deduccion')
						 WHERE
							rgn2.idconcepto = rgn.idconcepto AND
							rgn2.total > 0 AND
							rgn2.idgenerar_nomina = '".$field_nomina_anterior['idgenerar_nomina']."' AND
							rgn2.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn2.tabla = 'conceptos_nomina') AS total_anterior
					FROM
						relacion_generar_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
					WHERE
						total > 0 AND
						rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
						rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn.tabla = 'constantes_nomina')";
			$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'R', 'R', 'R'));
				$pdf->SetWidths(array(115, 25, 25, 25));
				$pdf->Row(array(utf8_decode($field_deducciones['descripcion']),
								number_format($field_deducciones['total_anterior'], 2, ',', '.'),
								number_format($field_deducciones['total_actual'], 2, ',', '.'),
								number_format($diferencia, 2, ',', '.')));
				
				$linea = $pdf->GetY(); if ($linea > 200) auditoria_nomina_trabajador($pdf, $field_trabajador, $field_titulo, $field_nomina_anterior);
			}
			*/
			
			$pdf->Ln(10);
		}
		break;
		
		
	//	Relacion de Nomina...
	case "relacion_nomina_trabajadores_simular":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		//	---------------------------------------
		//	Obtengo el id de la nomina generada....
		$sql = "SELECT idsimular_nomina
				FROM simular_nomina
				WHERE idsimular_nomina = '".$idgenerar_nomina."'";
		$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);
		//	---------------------------------------
		//	Obtengo los datos de la cabecera....
		$sql = "SELECT tn.titulo_nomina
				FROM 
					tipo_nomina tn
					INNER JOIN simular_nomina gn ON (gn.idtipo_nomina = tn.idtipo_nomina AND idsimular_nomina = '".$field_nomina['idsimular_nomina']."')
				WHERE tn.idtipo_nomina = '".$nomina."'
				GROUP BY tn.idtipo_nomina";
		$query_titulo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_titulo) != 0) $field_titulo = mysql_fetch_array($query_titulo);
		//	---------------------------------------
		relacion_nomina_trabajadores($pdf, $field_titulo['titulo_nomina'], "", $field_titulo['nomunidad'],"");
		//	---------------------------------------
		if ($flagunidad == "S" && $unidad != "0") $filtro_unidad = " AND mp.idunidad_funcional = '".$unidad."'";
		if ($flagcentro == "S" && $centro != "0") $filtro_centro = " AND no2.idcategoria_programatica = '".$centro."'";
		if ($idtrabajador != "") $filtro_trabajador = " AND rgn.idtrabajador = '".$idtrabajador."'";
		//	---------------------------------------
		//	Obtengo los datos de los trabajadores que pertenecen a esta nomina generada...
		$sql = "SELECT
					rgn.idtrabajador,
					t.cedula,
					t.apellidos,
					t.nombres,
					t.fecha_ingreso,
					t.nro_ficha,
					c.denominacion AS nomcargo,
					c.grado,
					c.paso,
					t.centro_costo,
					t.idunidad_funcional,
					no.denominacion AS nomunidad,
					no.codigo AS codunidad,
					ue.denominacion AS nomcentro,
					cp.codigo AS codcentro
				FROM
					relacion_simular_nomina rgn
					INNER JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					INNER JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					LEFT JOIN niveles_organizacionales no ON (mp.idubicacion_funcional = no.idniveles_organizacionales)
					LEFT JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
					LEFT JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
				WHERE
					rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."'  AND 
					mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal) 
												 FROM movimientos_personal 
												 WHERE 
												 	idtrabajador = t.idtrabajador AND
													fecha_movimiento <= '".date("Y-m-d")."')
					$filtro_unidad
					$filtro_centro
					$filtro_trabajador
				GROUP BY rgn.idtrabajador
				ORDER BY codcentro, length(cedula), cedula";
		$query_trabajador = mysql_query($sql) or die ($sql.mysql_error()); $i=0;	$filtro_centro_costo="";	$filtro_total_centro_costo="";
		$nro_trabajadores = mysql_num_rows($query_trabajador);
		while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
			if ($i==0) $agrupa_centro = $field_trabajador['codcentro'];			
			if ($i==0) $filtro_total_centro_costo .= " rgn.idtrabajador = '".$field_trabajador['idtrabajador']."'";
			else $filtro_total_centro_costo .= " OR rgn.idtrabajador = '".$field_trabajador['idtrabajador']."'";			
			//	si cambia de centro de costo imprimo el resumen por centro de costo
			if ($agrupa_centro != $field_trabajador['codcentro']) {
				$agrupa_centro = $field_trabajador['codcentro'];
				//	---------------------------------------
				$titulo_centro = $tcodcentro_costo.' '.$tnomcentro_costo;
				nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
				//	---------------------------------------
				$filtro_centro = " AND no2.idcategoria_programatica = '".$tcentro_costo."'";
				//	---------------------------------------
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
				$y = $pdf->GetY();
				$pdf->Rect(5, $y, 200, 0.1);
				$pdf->Ln(2);
				
				$pdf->SetFont('Arial', 'U', 8);
				$pdf->Cell(100, 5, 'ASIGNACIONES', 0, 0, 'C');
				$pdf->Cell(100, 5, 'DEDUCCIONES', 0, 1, 'C');
				$pdf->Ln(2);
				$y = $pdf->GetY(); $ya = $y; $yd = $y;
			
				$total_asignaciones = 0;
				$total_deducciones = 0;
				if ($filtro_centro_costo != "") $filtro_cc = " AND ($filtro_centro_costo)";
				//	Imprimo las asignaciones del trabajador
				$sql = "(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								cn.descripcion
							FROM
								relacion_simular_nomina rgn
								INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
							WHERE
								rgn.total > 0 AND
								rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
								rgn.tabla = 'conceptos_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)
					
							UNION
							
							(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								cn.descripcion
							FROM
								relacion_simular_nomina rgn
								INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
							WHERE
								rgn.total > 0 AND
								rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
								rgn.tabla = 'constantes_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)";
				$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
					$pdf->SetXY(5, $ya);
					$pdf->SetFont('Arial', '', 8);
				
					if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
					else $cantidad = "";
					
					$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion'].' '.$cantidad), 0, 0, 'L');
					$pdf->Cell(19, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
					$ya+=4;
					$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
					$total_asignaciones+=$field_asignaciones['total'];
				} $cantidad = "";
				
				//	Imprimo las deducciones del trabajador
				$sql = "(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								cn.descripcion
							FROM
								relacion_simular_nomina rgn
								INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
							WHERE
								rgn.total > 0 AND
								rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
								rgn.tabla = 'conceptos_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)
					
							UNION
							
							(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								cn.descripcion
							FROM
								relacion_simular_nomina rgn
								INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
							WHERE
								rgn.total > 0 AND
								rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
								rgn.tabla = 'constantes_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)";
				$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
					$pdf->SetXY(108, $yd);
					$pdf->SetFont('Arial', '', 8);
				
					if ($field_deducciones['cantidad'] > 0) $cantidad = "(".$field_deducciones['cantidad'].")";
					else $cantidad = "";
					
					$pdf->Cell(79, 5, utf8_decode($field_deducciones['descripcion'].' '.$cantidad), 0, 0, 'L');
					$pdf->Cell(19, 5, number_format($field_deducciones['total'], 2, ',', '.'), 0, 0, 'R');
					$yd+=4;
					$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
					$total_deducciones+=$field_deducciones['total'];
				} $cantidad = "";
				$pdf->Ln(8);
				
				$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
				
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
				if ($ya > $yd) $ytotal = $ya; else $ytotal = $yd;
				$pdf->Rect(82, $ytotal+1, 20, 0.1);
				$pdf->Rect(185, $ytotal+1, 20, 0.1);
				
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetXY(5, $ytotal+3);
				$pdf->Cell(79, 5, 'TOTAL ASIGNACIONES', 0, 0, 'L');
				$pdf->Cell(19, 5, number_format($total_asignaciones, 2, ',', '.'), 0, 0, 'R');
				$pdf->SetXY(108, $ytotal+3);
				$pdf->Cell(79, 5, 'TOTAL DEDUCCIONES', 0, 0, 'L');
				$pdf->Cell(19, 5, number_format($total_deducciones, 2, ',', '.'), 0, 1, 'R');
				
				$total_neto = $total_asignaciones - $total_deducciones;
				$pdf->SetXY(108, $ytotal+7);
				$pdf->Cell(79, 5, 'TOTAL NETO', 0, 0, 'L');
				$pdf->Cell(19, 5, number_format($total_neto, 2, ',', '.'), 0, 0, 'R');
		
				$pdf->Ln(10);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
				$y = $pdf->GetY();
				$pdf->Rect(5, $y, 200, 0.1);
				$pdf->Ln(10);
				$pdf->Cell(79, 5, 'NUMERO TOTAL DE TRABAJADORES DEL CENTRO DE COSTO: '.$nro_trabajadores_centro, 0, 0, 'L');
				$nro_trabajadores_centro=0;
				
				relacion_nomina_trabajadores($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'],"");
				$fcc=0;
				$filtro_centro_costo="";
				//	---------------------------------------
				
			} $i++; $tcentro_costo = $field_trabajador['centro_costo']; $tcodcentro_costo = $field_trabajador['codcentro']; $tnomcentro_costo = $field_trabajador['nomcentro']; $nro_trabajadores_centro++;
			
			list($a, $m, $d)=SPLIT( '[/.-]', $field_trabajador['fecha_ingreso']); $fingreso = $d."/".$m."/".$a;
			
			$linea = $pdf->GetY(); if ($linea > 200) relacion_nomina_trabajadores($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'],"");
			
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y = $pdf->GetY();
			$pdf->Rect(5, $y, 200, 0.1);
			$pdf->Ln(2);
			
			if ($fcc==0) $filtro_centro_costo .= " rgn.idtrabajador = '".$field_trabajador['idtrabajador']."'";
			else $filtro_centro_costo .= " OR rgn.idtrabajador = '".$field_trabajador['idtrabajador']."'";
			$fcc++;
			//	Imprimo los datos del trabajador
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(35, 5, utf8_decode('CEDULA: '.number_format($field_trabajador['cedula'], 0, '', '.')), 0, 0, 'L');
			$pdf->Cell(30, 5, utf8_decode('FICHA: '.$field_trabajador['nro_ficha']), 0, 0, 'L');
			$pdf->Cell(40, 5, 'F.INGRESO: '.$fingreso, 0, 0, 'L');
			$pdf->Cell(80, 5, utf8_decode('NOMBRE: '.$field_trabajador['nombres'].' '.$field_trabajador['apellidos']), 0, 1, 'L');
			
			$pdf->Cell(90, 5, utf8_decode('CARGO: '.substr($field_trabajador['nomcargo'], 0, 25)), 0, 1, 'L');
			$pdf->Cell(50, 5, utf8_decode('UNIDAD: '.$field_trabajador['codunidad'].' '.substr($field_trabajador['nomunidad'], 0, 40)), 0, 1, 'L');
			$pdf->Cell(90, 5, utf8_decode('C.COSTO: '.$field_trabajador['codcentro'].' '.$field_trabajador['nomcentro']), 0, 1, 'L');
			
			$x=175;
			$sql = "SELECT 
						cn.descripcion,
						rct.valor
					FROM
						constantes_nomina cn
						INNER JOIN relacion_concepto_trabajador rct ON (cn.idconstantes_nomina = rct.idconcepto AND rct.tabla = 'constantes_nomina')
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'Neutro')
						INNER JOIN movimientos_personal mp ON (mp.idtrabajador = rct.idtrabajador)
					WHERE 
						cn.mostrar = 'si' AND
						rct.valor > 0 AND
						mp.idtrabajador = '".$field_trabajador['idtrabajador']."'  AND 
						mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal) 
													 FROM movimientos_personal 
													 WHERE 
														idtrabajador = mp.idtrabajador AND
														fecha_movimiento <= '".$field_titulo['hasta']."')
					LIMIT 0,1";
			$query_constantes = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_constantes=mysql_fetch_array($query_constantes)) {
				if ($x>225) { $pdf->Ln(); $x=0; }
				$pdf->Cell(70, 5, utf8_decode($field_constantes['descripcion']).': '.number_format($field_constantes['valor'], 2, ',', '.'), 0, 0, 'L');
				$x+=70;
			}
			$pdf->Ln(8);
			
			$pdf->SetFont('Arial', 'U', 8);
			$pdf->Cell(100, 5, 'ASIGNACIONES', 0, 0, 'C');
			$pdf->Cell(100, 5, 'DEDUCCIONES', 0, 1, 'C');
			$pdf->Ln(2);
			$y = $pdf->GetY(); $ya = $y; $yd = $y;
			
			$total_asignaciones = 0;
			$total_deducciones = 0;
			
			//	Imprimo las asignaciones del trabajador			
			$sql = "(SELECT
						rgn.tabla,
						rgn.total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
						rgn.tabla = 'conceptos_nomina')
			
					UNION
					
					(SELECT
						rgn.tabla,
						rgn.total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
						rgn.tabla = 'constantes_nomina')";
			$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
				$pdf->SetXY(5, $ya);
				$pdf->SetFont('Arial', '', 8);
				
				if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
				else $cantidad = "";
				
				$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion'].' '.$cantidad), 0, 0, 'L');
				$pdf->Cell(19, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
				$ya+=4;
				$linea = $pdf->GetY(); if ($linea > 250) relacion_nomina_trabajadores($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad']);
				$total_asignaciones+=$field_asignaciones['total'];
			} $cantidad = "";
			
			//	Imprimo las deducciones del trabajador	
			$sql = "(SELECT
						rgn.tabla,
						rgn.total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						LEFT JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
					WHERE
						total > 0 AND
						rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'conceptos_nomina'
					GROUP BY rgn.idconcepto)
			
					UNION
			
					(SELECT
						rgn.tabla,
						rgn.total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
					WHERE
						total > 0 AND
						rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'constantes_nomina')";
			$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
				$pdf->SetXY(108, $yd);
				$pdf->SetFont('Arial', '', 8);
				
				if ($field_deducciones['cantidad'] > 0) $cantidad = "(".$field_deducciones['cantidad'].")";
				else $cantidad = "";
				
				$pdf->Cell(79, 5, utf8_decode($field_deducciones['descripcion'].' '.$cantidad), 0, 0, 'L');
				$pdf->Cell(19, 5, number_format($field_deducciones['total'], 2, ',', '.'), 0, 0, 'R');
				$yd+=4;
				$linea = $pdf->GetY(); if ($linea > 250) relacion_nomina_trabajadores($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad']);
				$total_deducciones+=$field_deducciones['total'];
			} $cantidad = "";
			
			$pdf->Ln(8);
			
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			if ($ya > $yd) $ytotal = $ya; else $ytotal = $yd;
			$pdf->Rect(82, $ytotal+1, 20, 0.1);
			$pdf->Rect(185, $ytotal+1, 20, 0.1);
			
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetXY(5, $ytotal+3);
			$pdf->Cell(79, 5, 'TOTAL ASIGNACIONES', 0, 0, 'L');
			$pdf->Cell(19, 5, number_format($total_asignaciones, 2, ',', '.'), 0, 0, 'R');
			$pdf->SetXY(108, $ytotal+3);
			$pdf->Cell(79, 5, 'TOTAL DEDUCCIONES', 0, 0, 'L');
			$pdf->Cell(19, 5, number_format($total_deducciones, 2, ',', '.'), 0, 1, 'R');
			
			$total_neto = $total_asignaciones - $total_deducciones;
			$pdf->SetX(108, $ytotal+7);
			$pdf->Cell(79, 5, 'TOTAL NETO', 0, 0, 'L');
			$pdf->Cell(19, 5, number_format($total_neto, 2, ',', '.'), 0, 0, 'R');
			$pdf->Ln(10);
		}
		
		//	imprimo el resumen por centro de costos de los ultimos empleados
		//	---------------------------------------
		$titulo_centro = $tcodcentro_costo.' '.$tnomcentro_costo;
		nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
		//	---------------------------------------
		$filtro_centro = " AND no2.idcategoria_programatica = '".$tcentro_costo."'";
		//	---------------------------------------
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		$y = $pdf->GetY();
		$pdf->Rect(5, $y, 200, 0.1);
		$pdf->Ln(2);
		
		$pdf->SetFont('Arial', 'U', 8);
		$pdf->Cell(100, 5, 'ASIGNACIONES', 0, 0, 'C');
		$pdf->Cell(100, 5, 'DEDUCCIONES', 0, 1, 'C');
		$pdf->Ln(2);
		$y = $pdf->GetY(); $ya = $y; $yd = $y;
	
		$total_asignaciones = 0;
		$total_deducciones = 0;
		if ($filtro_centro_costo != "") $filtro_cc = " AND ($filtro_centro_costo)";
		//	Imprimo las asignaciones del trabajador			
		$sql = "(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'conceptos_nomina'
						$filtro_cc
					GROUP BY rgn.idconcepto)
			
					UNION
					
					(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'constantes_nomina'
						$filtro_cc
					GROUP BY rgn.idconcepto)";
		$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
			$pdf->SetXY(5, $ya);
			$pdf->SetFont('Arial', '', 8);
				
			if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
			else $cantidad = "";
				
			$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion'].' '.$cantidad), 0, 0, 'L');
			$pdf->Cell(19, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
			$ya+=4;
			$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
			$total_asignaciones+=$field_asignaciones['total'];
		}
		
		//	Imprimo las deducciones del trabajador
		$sql = "(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'conceptos_nomina'
						$filtro_cc
					GROUP BY rgn.idconcepto)
			
					UNION
					
					(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'constantes_nomina'
						$filtro_cc
					GROUP BY rgn.idconcepto)";
		$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
			$pdf->SetXY(108, $yd);
			$pdf->SetFont('Arial', '', 8);
				
			if ($field_deducciones['cantidad'] > 0) $cantidad = "(".$field_deducciones['cantidad'].")";
			else $cantidad = "";
				
			$pdf->Cell(79, 5, utf8_decode($field_deducciones['descripcion'].' '.$cantidad), 0, 0, 'L');
			$pdf->Cell(19, 5, number_format($field_deducciones['total'], 2, ',', '.'), 0, 0, 'R');
			$yd+=4;
			$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
			$total_deducciones+=$field_deducciones['total'];
		}
		
		$pdf->Ln(8);
		
		$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
		
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		if ($ya > $yd) $ytotal = $ya; else $ytotal = $yd;
		$pdf->Rect(82, $ytotal+1, 20, 0.1);
		$pdf->Rect(185, $ytotal+1, 20, 0.1);
		
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(5, $ytotal+3);
		$pdf->Cell(79, 5, 'TOTAL ASIGNACIONES', 0, 0, 'L');
		$pdf->Cell(19, 5, number_format($total_asignaciones, 2, ',', '.'), 0, 0, 'R');
		$pdf->SetXY(108, $ytotal+3);
		$pdf->Cell(79, 5, 'TOTAL DEDUCCIONES', 0, 0, 'L');
		$pdf->Cell(19, 5, number_format($total_deducciones, 2, ',', '.'), 0, 1, 'R');
		
		$total_neto = $total_asignaciones - $total_deducciones;
		$pdf->SetXY(108, $ytotal+7);
		$pdf->Cell(79, 5, 'TOTAL NETO', 0, 0, 'L');
		$pdf->Cell(19, 5, number_format($total_neto, 2, ',', '.'), 0, 0, 'R');
		
		$pdf->Ln(10);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		$y = $pdf->GetY();
		$pdf->Rect(5, $y, 200, 0.1);
		$pdf->Ln(10);
		$pdf->Cell(79, 5, 'NUMERO TOTAL DE TRABAJADORES DEL CENTRO DE COSTO: '.$nro_trabajadores_centro, 0, 0, 'L');
		
		//	imprimo el resumen general de la nomina
		//	---------------------------------------
		if ($flagunidad == "S" && $unidad != "0") $filtro_unidad = " AND mp.idunidad_funcional = '".$unidad."'"; else $filtro_unidad = "";
		if ($flagcentro == "S" && $centro != "0") $filtro_centro = " AND no2.idcategoria_programatica = '".$centro."'";
		//	---------------------------------------
		nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", "");
		//	---------------------------------------
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		$y = $pdf->GetY();
		$pdf->Rect(5, $y, 200, 0.1);
		$pdf->Ln(2);
		
		$pdf->SetFont('Arial', 'U', 8);
		$pdf->Cell(100, 5, 'ASIGNACIONES', 0, 0, 'C');
		$pdf->Cell(100, 5, 'DEDUCCIONES', 0, 1, 'C');
		$pdf->Ln(2);
		$y = $pdf->GetY(); $ya = $y; $yd = $y;
	
		$total_asignaciones = 0;
		$total_deducciones = 0;
		if ($filtro_total_centro_costo != "") $filtro_tcc = " AND ($filtro_total_centro_costo)";
		//	Imprimo las asignaciones del trabajador
		$sql = "(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'conceptos_nomina'
						$filtro_tcc
					GROUP BY rgn.idconcepto)
			
					UNION
					
					(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'constantes_nomina'
						$filtro_tcc
					GROUP BY rgn.idconcepto)";
		$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
			$pdf->SetXY(5, $ya);
			$pdf->SetFont('Arial', '', 8);
				
			if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
			else $cantidad = "";
				
			$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion'].' '.$cantidad), 0, 0, 'L');
			$pdf->Cell(19, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
			$ya+=4;
			$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
			$total_asignaciones+=$field_asignaciones['total'];
		}
		
		//	Imprimo las deducciones del trabajador
		$sql = "(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'conceptos_nomina'
						$filtro_tcc
					GROUP BY rgn.idconcepto)
			
					UNION
					
					(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'constantes_nomina'
						$filtro_tcc
					GROUP BY rgn.idconcepto)";
		$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
			$pdf->SetXY(108, $yd);
			$pdf->SetFont('Arial', '', 8);
				
			if ($field_deducciones['cantidad'] > 0) $cantidad = "(".$field_deducciones['cantidad'].")";
			else $cantidad = "";
				
			$pdf->Cell(79, 5, utf8_decode($field_deducciones['descripcion'].' '.$cantidad), 0, 0, 'L');
			$pdf->Cell(19, 5, number_format($field_deducciones['total'], 2, ',', '.'), 0, 0, 'R');
			$yd+=4;
			$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
			$total_deducciones+=$field_deducciones['total'];
		}
		
		$pdf->Ln(8);
		
		$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
		
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		if ($ya > $yd) $ytotal = $ya; else $ytotal = $yd;
		$pdf->Rect(82, $ytotal+1, 20, 0.1);
		$pdf->Rect(185, $ytotal+1, 20, 0.1);
		
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(5, $ytotal+3);
		$pdf->Cell(79, 5, 'TOTAL ASIGNACIONES', 0, 0, 'L');
		$pdf->Cell(19, 5, number_format($total_asignaciones, 2, ',', '.'), 0, 0, 'R');
		$pdf->SetXY(108, $ytotal+3);
		$pdf->Cell(79, 5, 'TOTAL DEDUCCIONES', 0, 0, 'L');
		$pdf->Cell(19, 5, number_format($total_deducciones, 2, ',', '.'), 0, 1, 'R');
		
		$total_neto = $total_asignaciones - $total_deducciones;
		$pdf->SetXY(108, $ytotal+7);
		$pdf->Cell(79, 5, 'TOTAL NETO', 0, 0, 'L');
		$pdf->Cell(19, 5, number_format($total_neto, 2, ',', '.'), 0, 0, 'R');
		
		$pdf->Ln(10);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		$y = $pdf->GetY();
		$pdf->Rect(5, $y, 200, 0.1);
		$pdf->Ln(10);
		$pdf->Cell(79, 5, 'NUMERO TOTAL DE TRABAJADORES: '.$nro_trabajadores, 0, 0, 'L');
		break;
		
	//	Resumen de Conceptos...
	case "nomina_resumen_conceptos_simular":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		//	---------------------------------------
		//	Obtengo el id de la nomina generada....
		$sql = "SELECT idsimular_nomina
				FROM simular_nomina
				WHERE idsimular_nomina = '".$idgenerar_nomina."'";
		$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);
		//	---------------------------------------
		//	Obtengo los datos de la cabecera....
		$sql = "SELECT 
					tn.titulo_nomina
				FROM 
					tipo_nomina tn
					INNER JOIN simular_nomina gn ON (gn.idtipo_nomina = tn.idtipo_nomina AND idsimular_nomina = '".$field_nomina['idsimular_nomina']."')
				WHERE 
					tn.idtipo_nomina = '".$nomina."'
				GROUP BY tn.idtipo_nomina";
		$query_titulo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_titulo) != 0) $field_titulo = mysql_fetch_array($query_titulo);
		//	---------------------------------------
		if ($flagunidad == "S" && $unidad != "0") $filtro_unidad = " AND mp.idunidad_funcional = '".$unidad."'";
		if ($flagcentro == "S" && $centro != "0") $filtro_centro = " AND no2.idcategoria_programatica = '".$centro."'";
		if ($idtrabajador != "") $filtro_trabajador = " AND rgn.idtrabajador = '".$idtrabajador."'";
		//	---------------------------------------
		//	Obtengo los datos de los trabajadores que pertenecen a esta nomina generada...
		$sql = "SELECT
					rgn.idtrabajador,
					t.cedula,
					t.apellidos,
					t.nombres,
					t.fecha_ingreso,
					t.nro_ficha,
					c.denominacion AS nomcargo,
					c.grado,
					c.paso,
					t.centro_costo,
					t.idunidad_funcional,
					no.denominacion AS nomunidad,
					no.codigo AS codunidad,
					ue.denominacion AS nomcentro,
					cp.codigo AS codcentro
				FROM
					relacion_simular_nomina rgn
					INNER JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					INNER JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					LEFT JOIN niveles_organizacionales no ON (mp.idubicacion_funcional = no.idniveles_organizacionales)
					LEFT JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
					LEFT JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
				WHERE
					rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."'  AND 
					mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal) 
												 FROM movimientos_personal 
												 WHERE 
												 	idtrabajador = t.idtrabajador AND
													fecha_movimiento <= '".date("Y-m-d")."')
					$filtro_unidad
					$filtro_centro
					$filtro_trabajador
				GROUP BY rgn.idtrabajador
				ORDER BY codcentro, length(cedula), cedula";
		$query_trabajador = mysql_query($sql) or die ($sql.mysql_error()); $i=0;	$filtro_centro_costo="";	$filtro_total_centro_costo="";
		$nro_trabajadores = mysql_num_rows($query_trabajador);
		while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
			if ($i==0) $agrupa_centro = $field_trabajador['codcentro'];			
			if ($i==0) $filtro_total_centro_costo .= " rgn.idtrabajador = '".$field_trabajador['idtrabajador']."'";
			else $filtro_total_centro_costo .= " OR rgn.idtrabajador = '".$field_trabajador['idtrabajador']."'";
			
			//	si cambia de centro de costo imprimo el resumen por centro de costo
			if ($agrupa_centro != $field_trabajador['codcentro']) {
				$agrupa_centro = $field_trabajador['codcentro'];
				//	---------------------------------------
				$titulo_centro = $tcodcentro_costo.' '.$tnomcentro_costo;
				nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
				//	---------------------------------------
				$filtro_centro = " AND no2.idcategoria_programatica = '".$tcentro_costo."'";
				//	---------------------------------------
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
				$y = $pdf->GetY();
				$pdf->Rect(5, $y, 200, 0.1);
				$pdf->Ln(2);
				
				$pdf->SetFont('Arial', 'U', 8);
				$pdf->Cell(100, 5, 'ASIGNACIONES', 0, 0, 'C');
				$pdf->Cell(100, 5, 'DEDUCCIONES', 0, 1, 'C');
				$pdf->Ln(2);
				$y = $pdf->GetY(); $ya = $y; $yd = $y;
			
				$total_asignaciones = 0;
				$total_deducciones = 0;
				if ($filtro_centro_costo != "") $filtro_cc = " AND ($filtro_centro_costo)";
				//	Imprimo las asignaciones del trabajador
				$sql = "(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								cn.descripcion
							FROM
								relacion_simular_nomina rgn
								INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
							WHERE
								rgn.total > 0 AND
								rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
								rgn.tabla = 'conceptos_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)
					
							UNION
							
							(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								cn.descripcion
							FROM
								relacion_simular_nomina rgn
								INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
							WHERE
								rgn.total > 0 AND
								rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
								rgn.tabla = 'constantes_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)";
				$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
					$pdf->SetXY(5, $ya);
					$pdf->SetFont('Arial', '', 8);
				
					if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
					else $cantidad = "";
					
					$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion'].' '.$cantidad), 0, 0, 'L');
					$pdf->Cell(19, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
					$ya+=4;
					$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
					$total_asignaciones+=$field_asignaciones['total'];
				} $cantidad = "";
				
				//	Imprimo las deducciones del trabajador
				$sql = "(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								cn.descripcion
							FROM
								relacion_simular_nomina rgn
								INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
							WHERE
								rgn.total > 0 AND
								rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
								rgn.tabla = 'conceptos_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)
					
							UNION
							
							(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								cn.descripcion
							FROM
								relacion_simular_nomina rgn
								INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
							WHERE
								rgn.total > 0 AND
								rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
								rgn.tabla = 'constantes_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)";
				$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
					$pdf->SetXY(108, $yd);
					$pdf->SetFont('Arial', '', 8);
				
					if ($field_deducciones['cantidad'] > 0) $cantidad = "(".$field_deducciones['cantidad'].")";
					else $cantidad = "";
					
					$pdf->Cell(79, 5, utf8_decode($field_deducciones['descripcion'].' '.$cantidad), 0, 0, 'L');
					$pdf->Cell(19, 5, number_format($field_deducciones['total'], 2, ',', '.'), 0, 0, 'R');
					$yd+=4;
					$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
					$total_deducciones+=$field_deducciones['total'];
				} $cantidad = "";
				$pdf->Ln(8);
				
				$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
				
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
				if ($ya > $yd) $ytotal = $ya; else $ytotal = $yd;
				$pdf->Rect(82, $ytotal+1, 20, 0.1);
				$pdf->Rect(185, $ytotal+1, 20, 0.1);
				
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetXY(5, $ytotal+3);
				$pdf->Cell(79, 5, 'TOTAL ASIGNACIONES', 0, 0, 'L');
				$pdf->Cell(19, 5, number_format($total_asignaciones, 2, ',', '.'), 0, 0, 'R');
				$pdf->SetXY(108, $ytotal+3);
				$pdf->Cell(79, 5, 'TOTAL DEDUCCIONES', 0, 0, 'L');
				$pdf->Cell(19, 5, number_format($total_deducciones, 2, ',', '.'), 0, 1, 'R');
				
				$total_neto = $total_asignaciones - $total_deducciones;
				$pdf->SetXY(108, $ytotal+7);
				$pdf->Cell(79, 5, 'TOTAL NETO', 0, 0, 'L');
				$pdf->Cell(19, 5, number_format($total_neto, 2, ',', '.'), 0, 0, 'R');
		
				$pdf->Ln(10);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
				$y = $pdf->GetY();
				$pdf->Rect(5, $y, 200, 0.1);
				$pdf->Ln(10);
				$pdf->Cell(79, 5, 'NUMERO TOTAL DE TRABAJADORES DEL CENTRO DE COSTO: '.$nro_trabajadores_centro, 0, 0, 'L');
				$nro_trabajadores_centro=0;
				$fcc=0;
				$filtro_centro_costo="";
			} $i++; $tcentro_costo = $field_trabajador['centro_costo']; $tcodcentro_costo = $field_trabajador['codcentro']; $tnomcentro_costo = $field_trabajador['nomcentro']; $nro_trabajadores_centro++;
			
			if ($fcc==0) $filtro_centro_costo .= " rgn.idtrabajador = '".$field_trabajador['idtrabajador']."'";
			else $filtro_centro_costo .= " OR rgn.idtrabajador = '".$field_trabajador['idtrabajador']."'";
			$fcc++;
		}
		
		//	imprimo el resumen por centro de costos de los ultimos empleados
		//	---------------------------------------
		$titulo_centro = $tcodcentro_costo.' '.$tnomcentro_costo;
		nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
		//	---------------------------------------
		$filtro_centro = " AND no2.idcategoria_programatica = '".$tcentro_costo."'";
		//	---------------------------------------
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		$y = $pdf->GetY();
		$pdf->Rect(5, $y, 200, 0.1);
		$pdf->Ln(2);
		
		$pdf->SetFont('Arial', 'U', 8);
		$pdf->Cell(100, 5, 'ASIGNACIONES', 0, 0, 'C');
		$pdf->Cell(100, 5, 'DEDUCCIONES', 0, 1, 'C');
		$pdf->Ln(2);
		$y = $pdf->GetY(); $ya = $y; $yd = $y;
	
		$total_asignaciones = 0;
		$total_deducciones = 0;
		if ($filtro_centro_costo != "") $filtro_cc = " AND ($filtro_centro_costo)";
		//	Imprimo las asignaciones del trabajador			
		$sql = "(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'conceptos_nomina'
						$filtro_cc
					GROUP BY rgn.idconcepto)
			
					UNION
					
					(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'constantes_nomina'
						$filtro_cc
					GROUP BY rgn.idconcepto)";
		$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
			$pdf->SetXY(5, $ya);
			$pdf->SetFont('Arial', '', 8);
				
			if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
			else $cantidad = "";
				
			$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion'].' '.$cantidad), 0, 0, 'L');
			$pdf->Cell(19, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
			$ya+=4;
			$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
			$total_asignaciones+=$field_asignaciones['total'];
		}
		
		//	Imprimo las deducciones del trabajador
		$sql = "(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'conceptos_nomina'
						$filtro_cc
					GROUP BY rgn.idconcepto)
			
					UNION
					
					(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'constantes_nomina'
						$filtro_cc
					GROUP BY rgn.idconcepto)";
		$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
			$pdf->SetXY(108, $yd);
			$pdf->SetFont('Arial', '', 8);
				
			if ($field_deducciones['cantidad'] > 0) $cantidad = "(".$field_deducciones['cantidad'].")";
			else $cantidad = "";
				
			$pdf->Cell(79, 5, utf8_decode($field_deducciones['descripcion'].' '.$cantidad), 0, 0, 'L');
			$pdf->Cell(19, 5, number_format($field_deducciones['total'], 2, ',', '.'), 0, 0, 'R');
			$yd+=4;
			$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
			$total_deducciones+=$field_deducciones['total'];
		}
		
		$pdf->Ln(8);
		
		$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
		
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		if ($ya > $yd) $ytotal = $ya; else $ytotal = $yd;
		$pdf->Rect(82, $ytotal+1, 20, 0.1);
		$pdf->Rect(185, $ytotal+1, 20, 0.1);
		
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(5, $ytotal+3);
		$pdf->Cell(79, 5, 'TOTAL ASIGNACIONES', 0, 0, 'L');
		$pdf->Cell(19, 5, number_format($total_asignaciones, 2, ',', '.'), 0, 0, 'R');
		$pdf->SetXY(108, $ytotal+3);
		$pdf->Cell(79, 5, 'TOTAL DEDUCCIONES', 0, 0, 'L');
		$pdf->Cell(19, 5, number_format($total_deducciones, 2, ',', '.'), 0, 1, 'R');
		
		$total_neto = $total_asignaciones - $total_deducciones;
		$pdf->SetXY(108, $ytotal+7);
		$pdf->Cell(79, 5, 'TOTAL NETO', 0, 0, 'L');
		$pdf->Cell(19, 5, number_format($total_neto, 2, ',', '.'), 0, 0, 'R');
		
		$pdf->Ln(10);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		$y = $pdf->GetY();
		$pdf->Rect(5, $y, 200, 0.1);
		$pdf->Ln(10);
		$pdf->Cell(79, 5, 'NUMERO TOTAL DE TRABAJADORES DEL CENTRO DE COSTO: '.$nro_trabajadores_centro, 0, 0, 'L');
		
		//	imprimo el resumen general de la nomina
		//	---------------------------------------
		if ($flagunidad == "S" && $unidad != "0") $filtro_unidad = " AND mp.idunidad_funcional = '".$unidad."'"; else $filtro_unidad = "";
		if ($flagcentro == "S" && $centro != "0") $filtro_centro = " AND no2.idcategoria_programatica = '".$centro."'";
		//	---------------------------------------
		nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", "");
		//	---------------------------------------
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		$y = $pdf->GetY();
		$pdf->Rect(5, $y, 200, 0.1);
		$pdf->Ln(2);
		
		$pdf->SetFont('Arial', 'U', 8);
		$pdf->Cell(100, 5, 'ASIGNACIONES', 0, 0, 'C');
		$pdf->Cell(100, 5, 'DEDUCCIONES', 0, 1, 'C');
		$pdf->Ln(2);
		$y = $pdf->GetY(); $ya = $y; $yd = $y;
	
		$total_asignaciones = 0;
		$total_deducciones = 0;
		if ($filtro_total_centro_costo != "") $filtro_tcc = " AND ($filtro_total_centro_costo)";
		//	Imprimo las asignaciones del trabajador
		$sql = "(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'conceptos_nomina'
						$filtro_tcc
					GROUP BY rgn.idconcepto)
			
					UNION
					
					(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'constantes_nomina'
						$filtro_tcc
					GROUP BY rgn.idconcepto)";
		$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
			$pdf->SetXY(5, $ya);
			$pdf->SetFont('Arial', '', 8);
				
			if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
			else $cantidad = "";
				
			$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion'].' '.$cantidad), 0, 0, 'L');
			$pdf->Cell(19, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
			$ya+=4;
			$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
			$total_asignaciones+=$field_asignaciones['total'];
		}
		
		//	Imprimo las deducciones del trabajador
		$sql = "(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'conceptos_nomina'
						$filtro_tcc
					GROUP BY rgn.idconcepto)
			
					UNION
					
					(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion
					FROM
						relacion_simular_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
					WHERE
						rgn.total > 0 AND
						rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
						rgn.tabla = 'constantes_nomina'
						$filtro_tcc
					GROUP BY rgn.idconcepto)";
		$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
			$pdf->SetXY(108, $yd);
			$pdf->SetFont('Arial', '', 8);
				
			if ($field_deducciones['cantidad'] > 0) $cantidad = "(".$field_deducciones['cantidad'].")";
			else $cantidad = "";
				
			$pdf->Cell(79, 5, utf8_decode($field_deducciones['descripcion'].' '.$cantidad), 0, 0, 'L');
			$pdf->Cell(19, 5, number_format($field_deducciones['total'], 2, ',', '.'), 0, 0, 'R');
			$yd+=4;
			$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
			$total_deducciones+=$field_deducciones['total'];
		}
		
		$pdf->Ln(8);
		
		$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
		
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		if ($ya > $yd) $ytotal = $ya; else $ytotal = $yd;
		$pdf->Rect(82, $ytotal+1, 20, 0.1);
		$pdf->Rect(185, $ytotal+1, 20, 0.1);
		
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(5, $ytotal+3);
		$pdf->Cell(79, 5, 'TOTAL ASIGNACIONES', 0, 0, 'L');
		$pdf->Cell(19, 5, number_format($total_asignaciones, 2, ',', '.'), 0, 0, 'R');
		$pdf->SetXY(108, $ytotal+3);
		$pdf->Cell(79, 5, 'TOTAL DEDUCCIONES', 0, 0, 'L');
		$pdf->Cell(19, 5, number_format($total_deducciones, 2, ',', '.'), 0, 1, 'R');
		
		$total_neto = $total_asignaciones - $total_deducciones;
		$pdf->SetXY(108, $ytotal+7);
		$pdf->Cell(79, 5, 'TOTAL NETO', 0, 0, 'L');
		$pdf->Cell(19, 5, number_format($total_neto, 2, ',', '.'), 0, 0, 'R');
		
		$pdf->Ln(10);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		$y = $pdf->GetY();
		$pdf->Rect(5, $y, 200, 0.1);
		$pdf->Ln(10);
		$pdf->Cell(79, 5, 'NUMERO TOTAL DE TRABAJADORES: '.$nro_trabajadores, 0, 0, 'L');
		break;
		
	//	Resumen Comparacion Simulacion...
	case "nomina_comparacion_simular":
		$pdf=new PDF_MC_Table9('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();	//$idcomparar="434";
		//	---------------------------------------
		//	Obtengo el id de la nomina generada....
		$sql = "SELECT 
					idsimular_nomina,
					(SELECT idgenerar_nomina FROM generar_nomina WHERE idperiodo = '".$idcomparar."') AS idgenerar_nomina,
					(SELECT idorden_compra_servicio FROM generar_nomina WHERE idgenerar_nomina = '".$idcomparar."') AS idorden_compra_servicio,
					idcertificacion_simular_nomina
				FROM simular_nomina
				WHERE idsimular_nomina = '".$idgenerar_nomina."'";
		$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);
		//	---------------------------------------
		//	Obtengo los datos de la cabecera....
		$sql = "SELECT tn.titulo_nomina
				FROM 
					tipo_nomina tn
					INNER JOIN simular_nomina gn ON (gn.idtipo_nomina = tn.idtipo_nomina AND idsimular_nomina = '".$field_nomina['idsimular_nomina']."')
				WHERE tn.idtipo_nomina = '".$nomina."'
				GROUP BY tn.idtipo_nomina";
		$query_titulo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_titulo) != 0) $field_titulo = mysql_fetch_array($query_titulo);
		//	---------------------------------------
		//	imprimo el resumen general de la nomina
		//	---------------------------------------
		nomina_comparacion_simular($pdf, $field_titulo['titulo_nomina']);
		//	---------------------------------------
		$total_asignaciones = 0;
		$total_deducciones = 0;
		//	Imprimo las asignaciones del trabajador
		$sql = "(SELECT
					rgn.tabla,
					SUM(rgn.total) AS total_simulacion,
					cn.descripcion,
					(SELECT SUM(rgn2.total)
					FROM
						relacion_generar_nomina rgn2
						INNER JOIN conceptos_nomina cn2 ON (rgn2.idconcepto = cn2.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.tipo_concepto = tcn2.idconceptos_nomina AND afecta = 'asignacion')
					WHERE
						rgn2.total > 0 AND
						rgn2.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn2.tabla = 'conceptos_nomina' AND
						rgn2.idconcepto = rgn.idconcepto
					GROUP BY rgn2.idconcepto) AS total_concepto
				FROM
					relacion_simular_nomina rgn
					INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
					INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
				WHERE
					rgn.total > 0 AND
					rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
					rgn.tabla = 'conceptos_nomina'
				GROUP BY rgn.idconcepto)
		
				UNION
				
				(SELECT
					rgn.tabla,
					SUM(rgn.total) AS total_simulacion,
					cn.descripcion,
					(SELECT SUM(rgn2.total)
					FROM
						relacion_generar_nomina rgn2
						INNER JOIN constantes_nomina cn2 ON (rgn2.idconcepto = cn2.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.afecta = tcn2.idconceptos_nomina AND tcn2.afecta = 'asignacion')
					WHERE
						rgn2.total > 0 AND
						rgn2.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn2.tabla = 'constantes_nomina' AND
						rgn2.idconcepto = rgn.idconcepto
					GROUP BY rgn2.idconcepto) AS total_concepto
				FROM
					relacion_simular_nomina rgn
					INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
					INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
				WHERE
					rgn.total > 0 AND
					rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
					rgn.tabla = 'constantes_nomina'
				GROUP BY rgn.idconcepto)";
		$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
			$diferencia = $field_asignaciones['total_simulacion'] - $field_asignaciones['total_concepto'];
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(90, 5, utf8_decode($field_asignaciones['descripcion']), 0, 0, 'L');
			$pdf->Cell(35, 5, number_format($field_asignaciones['total_concepto'], 2, ',', '.'), 0, 0, 'R');
			$pdf->Cell(35, 5, number_format($field_asignaciones['total_simulacion'], 2, ',', '.'), 0, 0, 'R');
			$pdf->Cell(35, 5, number_format($diferencia, 2, ',', '.'), 0, 1, 'R');
			$linea = $pdf->GetY(); if ($linea > 250) nomina_comparacion_simular($pdf, $field_titulo['titulo_nomina']);
			$total_asignaciones_concepto+=$field_asignaciones['total_concepto'];
			$total_asignaciones_simulacion+=$field_asignaciones['total_simulacion'];
		}
		
		//	Imprimo las deducciones del trabajador
		$sql = "(SELECT
					rgn.tabla,
					SUM(rgn.total) AS total_simulacion,
					cn.descripcion,
					(SELECT SUM(rgn2.total)
					FROM
						relacion_generar_nomina rgn2
						INNER JOIN conceptos_nomina cn2 ON (rgn2.idconcepto = cn2.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.tipo_concepto = tcn2.idconceptos_nomina AND afecta = 'deduccion')
					WHERE
						rgn2.total > 0 AND
						rgn2.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn2.tabla = 'conceptos_nomina' AND
						rgn2.idconcepto = rgn.idconcepto
					GROUP BY rgn2.idconcepto) AS total_concepto
				FROM
					relacion_simular_nomina rgn
					INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
					INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
				WHERE
					rgn.total > 0 AND
					rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
					rgn.tabla = 'conceptos_nomina'
				GROUP BY rgn.idconcepto)
		
				UNION
				
				(SELECT
					rgn.tabla,
					SUM(rgn.total) AS total_simulacion,
					cn.descripcion,
					(SELECT SUM(rgn2.total)
					FROM
						relacion_generar_nomina rgn2
						INNER JOIN constantes_nomina cn2 ON (rgn2.idconcepto = cn2.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn2 ON (cn2.afecta = tcn2.idconceptos_nomina AND tcn2.afecta = 'deduccion')
					WHERE
						rgn2.total > 0 AND
						rgn2.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn2.tabla = 'constantes_nomina' AND
						rgn2.idconcepto = rgn.idconcepto
					GROUP BY rgn.idconcepto) AS total_concepto
				FROM
					relacion_simular_nomina rgn
					INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
					INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
				WHERE
					rgn.total > 0 AND
					rgn.idsimular_nomina = '".$field_nomina['idsimular_nomina']."' AND
					rgn.tabla = 'constantes_nomina'
				GROUP BY rgn.idconcepto)";
		$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
			$diferencia = $field_deducciones['total_simulacion'] - $field_deducciones['total_concepto'];
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(90, 5, utf8_decode($field_deducciones['descripcion'].' '.$cantidad), 0, 0, 'L');
			$pdf->Cell(35, 5, number_format($field_deducciones['total_concepto'], 2, ',', '.'), 0, 0, 'R');
			$pdf->Cell(35, 5, number_format($field_deducciones['total_simulacion'], 2, ',', '.'), 0, 0, 'R');
			$pdf->Cell(35, 5, number_format($diferencia, 2, ',', '.'), 0, 1, 'R');
			$linea = $pdf->GetY(); if ($linea > 250) nomina_comparacion_simular($pdf, $field_titulo['titulo_nomina']);
			$total_deducciones_concepto+=$field_deducciones['total_concepto'];
			$total_deducciones_simulacion+=$field_deducciones['total_simulacion'];
		}
		//	---------------------------------
		//	IMPRIMIR LAS PARTIDAS
		//	---------------------------------
		nomina_comparacion_simular_partidas($pdf, $field_titulo['titulo_nomina']);
		//	consulto las partidas
		$sql = "SELECT
					psn.monto AS monto_simulado,
					CONCAT(cp.partida, '.', cp.generica, '.', cp.especifica, '.', cp.sub_especifica) AS codigo,
					cp.denominacion,
					(SELECT psn2.monto
					 FROM
						generar_nomina sn2
						INNER JOIN partidas_orden_compra_servicio psn2 ON (sn2.idorden_compra_servicio = psn2.idorden_compra_servicio)
						INNER JOIN maestro_presupuesto mp2 ON (psn2.idmaestro_presupuesto = mp2.idRegistro)
						INNER JOIN clasificador_presupuestario cp2 ON (mp2.idclasificador_presupuestario = cp2.idclasificador_presupuestario)
					 WHERE sn2.idorden_compra_servicio = '".$field_nomina['idorden_compra_servicio']."') AS monto_concepto
				FROM
					simular_nomina sn
					INNER JOIN partidas_simular_nomina psn ON (sn.idcertificacion_simular_nomina = psn.idcertificacion_simular_nomina)
					INNER JOIN maestro_presupuesto mp ON (psn.idmaestro_presupuesto = mp.idRegistro)
					INNER JOIN clasificador_presupuestario cp ON (mp.idclasificador_presupuestario = cp.idclasificador_presupuestario)
				WHERE sn.idcertificacion_simular_nomina = '".$field_nomina['idcertificacion_simular_nomina']."'
				ORDER BY codigo";
		$query_partidas=mysql_query($sql) or die ($sql.mysql_error());
		while ($field_partidas=mysql_fetch_array($query_partidas)) {
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); 
			$pdf->SetFont('Arial', '', 8);
			$diferencia = $field_partidas['monto_simulado'] - $field_partidas['monto_concepto'];
			$pdf->Row(array($field_partidas['codigo'], 
							utf8_decode($field_partidas['denominacion']), 
							number_format($field_partidas['monto_concepto'], 2, ',', '.'), 
							number_format($field_partidas['monto_simulado'], 2, ',', '.'), 
							number_format($diferencia, 2, ',', '.')));
		}
		
		break;
}
//	----------------------------------------
//	FIN CUERPO DE LOS REPORTES
//	----------------------------------------

$pdf->Output();
?>
