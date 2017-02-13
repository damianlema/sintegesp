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
require('../../../funciones/funciones.php');
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
//	----------------------------------------
Conectarse();
$ahora=date("d-m-Y H:i:s");
//	----------------------------------------

//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Relacion de XXXXXXXX...
	case "filtro_relacion_nomina":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		//	---------------------------------------
		$sql="(SELECT
				  cp.codigo AS Categoria,
				  ue.denominacion AS UnidadEjecutora,
				  a.descripcion AS Concepto,
				  a.tipo_concepto,
				  a.codigo AS CodConcepto,
				  acs.precio_unitario,
				  td.descripcion AS Titulo,
				  ocs.justificacion,
				  'Asignación' AS Tipo
				FROM
				  articulos_compra_servicio acs
				  INNER JOIN articulos_servicios a ON (acs.idarticulos_servicios=a.idarticulos_servicios)
				  INNER JOIN categoria_programatica cp ON (cp.idcategoria_programatica=acs.idcategoria_programatica)
				  INNER JOIN orden_compra_servicio ocs ON (acs.idorden_compra_servicio=ocs.idorden_compra_servicio)
				  INNER JOIN tipos_documentos td ON (ocs.tipo=td.idtipos_documentos AND (td.modulo like '%-1-%' OR td.modulo like '%-13-%'))
				  INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora=ue.idunidad_ejecutora)
				WHERE a.tipo_concepto='1' AND acs.idorden_compra_servicio='".$idorden."')
				UNION
				(SELECT
				  cp.codigo AS Categoria,
				  ue.denominacion AS UnidadEjecutora,
				  a.descripcion AS Concepto,
				  a.tipo_concepto,
				  a.codigo AS CodConcepto,
				  acs.precio_unitario,
				  td.descripcion AS Titulo,
				  ocs.justificacion,
				  'Deducción' AS Tipo
				FROM
				  articulos_compra_servicio acs
				  INNER JOIN articulos_servicios a ON (acs.idarticulos_servicios=a.idarticulos_servicios)
				  INNER JOIN orden_compra_servicio ocs ON (acs.idorden_compra_servicio=ocs.idorden_compra_servicio)
				  INNER JOIN tipos_documentos td ON (ocs.tipo=td.idtipos_documentos AND (td.modulo like '%-1-%' OR td.modulo like '%-13-%'))
				  INNER JOIN categoria_programatica cp ON (cp.idcategoria_programatica=acs.idcategoria_programatica)
				  INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora=ue.idunidad_ejecutora)
				WHERE a.tipo_concepto='2' AND acs.idorden_compra_servicio='".$idorden."')
				ORDER BY Categoria, Tipo";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//	---------------------
			if ($cat!=$field['Categoria']) {
				$cat=$field['Categoria'];
				//	------
				if ($i!=0) {
					$asignaciones=number_format($sum_asignaciones, 2, ',', '.');
					$deducciones=number_format($sum_deducciones, 2, ',', '.');
					$pdf->Ln(5);
					$y=$pdf->GetY();
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->Rect(5, $y, 205, 0.1);
					$pdf->Ln(5);
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->SetAligns(array('L', 'R', 'C', 'L', 'R', 'C'));
					$pdf->SetWidths(array(65, 30, 5, 65, 30, 5));
					$pdf->Row(array('TOTAL ASIGNACIONES', $asignaciones, '', 'TOTAL DEDUCCIONES', $deducciones, ''));
				}
				//	------
				filtro_relacion_nomina($pdf, $field['UnidadEjecutora'], $field['Categoria'], $field['Titulo'], $field['justificacion']);
				$sum_asignaciones=0; $sum_deducciones=0;
			}
			//	---------------------
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetAligns(array('L', 'R', 'C'));
			$pdf->SetWidths(array(65, 30, 5));
			//	---------------------
			if ($tipo!=$field['tipo_concepto']) {
				$tipo=$field['tipo_concepto'];
				$pdf->SetY(78);
			}
			if ($field['tipo_concepto']==1) { $pdf->SetX(5); $sum_asignaciones+=$field['precio_unitario']; }
			else { $pdf->SetX(105); $sum_deducciones+=$field['precio_unitario']; }
			//	---------------------
			$monto=number_format($field['precio_unitario'], 2, ',', '.');
			$pdf->Row(array(utf8_decode($field['Concepto']), $monto, ''));
			//	---------------------
		}
		$asignaciones=number_format($sum_asignaciones, 2, ',', '.');
		$deducciones=number_format($sum_deducciones, 2, ',', '.');
		$pdf->Ln(5);
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->Rect(5, $y, 205, 0.1);
		$pdf->Ln(5);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->SetAligns(array('L', 'R', 'C', 'L', 'R', 'C'));
		$pdf->SetWidths(array(65, 30, 5, 65, 30, 5));
		$pdf->Row(array('TOTAL ASIGNACIONES', $asignaciones, '', 'TOTAL DEDUCCIONES', $deducciones, ''));
		//	--------------------------------
		$sum_asignaciones=0; $sum_deducciones=0;
		anexo_filtro_relacion_nomina($pdf, $field['UnidadEjecutora'], $field['Categoria'], $field['Titulo'], $field['justificacion']);
		$sql="(SELECT
				  a.descripcion AS Concepto,
				  a.tipo_concepto, 
				  a.codigo AS CodConcepto,
				  SUM(acs.precio_unitario) AS Monto,
				  'Asignación' AS Tipo
				FROM
				  articulos_compra_servicio acs
				  INNER JOIN articulos_servicios a ON (acs.idarticulos_servicios=a.idarticulos_servicios)
				  INNER JOIN orden_compra_servicio ocs ON (acs.idorden_compra_servicio=ocs.idorden_compra_servicio)
				  WHERE a.tipo_concepto='1' AND acs.idorden_compra_servicio='".$idorden."'
				  GROUP BY (CodConcepto))
				
				UNION
				
				(SELECT
				  a.descripcion AS Concepto,
				  a.tipo_concepto, 
				  a.codigo AS CodConcepto,
				  SUM(acs.precio_unitario) AS Monto,
				  'Deducción' AS Tipo
				FROM
				  articulos_compra_servicio acs
				  INNER JOIN articulos_servicios a ON (acs.idarticulos_servicios=a.idarticulos_servicios)
				  INNER JOIN orden_compra_servicio ocs ON (acs.idorden_compra_servicio=ocs.idorden_compra_servicio)
				  WHERE a.tipo_concepto='2' AND acs.idorden_compra_servicio='".$idorden."'
				  GROUP BY (CodConcepto)) ";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//	---------------------
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetAligns(array('L', 'R', 'C'));
			$pdf->SetWidths(array(65, 30, 5));
			//	---------------------
			if ($tipo!=$field['tipo_concepto']) {
				$tipo=$field['tipo_concepto'];
				$pdf->SetY(78);
			}
			if ($field['tipo_concepto']==1) { $pdf->SetX(5); $sum_asignaciones+=$field['Monto']; }
			else { $pdf->SetX(105); $sum_deducciones+=$field['Monto']; }
			//	---------------------
			$monto=number_format($field['Monto'], 2, ',', '.');
			$pdf->Row(array(utf8_decode($field['Concepto']), $monto, ''));
			//	---------------------
		}
		$asignaciones=number_format($sum_asignaciones, 2, ',', '.');
		$deducciones=number_format($sum_deducciones, 2, ',', '.');
		$pdf->Ln(5);
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->Rect(5, $y, 205, 0.1);
		$pdf->Ln(5);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->SetAligns(array('L', 'R', 'C', 'L', 'R', 'C'));
		$pdf->SetWidths(array(65, 30, 5, 65, 30, 5));
		$pdf->Row(array('TOTAL ASIGNACIONES', $asignaciones, '', 'TOTAL DEDUCCIONES', $deducciones, ''));
		break;
		
	//	Relacion Anticipo A Terceros...
	case "relacion_anticipo_terceros":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();

		// OBTENGO LOS DATOS DEL CONCEPTO
		list($tablad, $idconceptod)=SPLIT( '[|]', $idconceptod);
		$sql_consulta = mysql_query("select * from ".$tablad." where id".$tablad." = '".$idconceptod."'")or die(mysql_error());
		$bus_consulta = mysql_fetch_array($sql_consulta);
		$nomconceptod = $bus_consulta["descripcion"];

		// OBTENGO LOS DATOS DEL APORTE
		list($tablaa, $idconceptoa)=SPLIT( '[|]', $idconceptoa);
		$sql_consulta = mysql_query("select * from ".$tablaa." where id".$tablaa." = '".$idconceptoa."'")or die(mysql_error());
		$bus_consulta = mysql_fetch_array($sql_consulta);
		$nomconceptoa = $bus_consulta["descripcion"];

		//	---------------------------------------
		// OBTENGO LOS DATOS DE LA NOMINA
		$sql_consulta_nomina = mysql_query("select * from tipo_nomina where idtipo_nomina = '".$tipo_nomina."'")or die(mysql_error());
		$bus_consulta_nomina = mysql_fetch_array($sql_consulta_nomina);
		$nom_nomina = $bus_consulta_nomina["titulo_nomina"];
		//	---------------------------------------
		if ($estado == ''){
			$where_estado = "gn.idtipo_nomina = '".$tipo_nomina."'";
		}else{
			$where_estado = "gn.idtipo_nomina = '".$tipo_nomina."' AND gn.estado = '".$estado."'";
		}
		list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fdesde = $d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fhasta = $d."/".$m."/".$a;
		if ($desde != "" && $hasta != "") $periodo = "$fdesde - $fhasta";
		
		relacion_anticipo_terceros($pdf, $nomconceptod, $nomconceptoa, $periodo, $nom_nomina, $estado);
		//	-----------------------------------------------------------------------------
		$sql = "	(SELECT tr.cedula, tr.apellidos, tr.nombres, sum(rgn.total) as totald, 0 as totala
					  FROM generar_nomina gn

					  INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina)
					  INNER JOIN relacion_generar_nomina rgn ON (gn.idgenerar_nomina = rgn.idgenerar_nomina)
					  INNER JOIN trabajador tr ON (rgn.idtrabajador = tr.idtrabajador)
					WHERE (rpn.desde >= '".$desde."' AND rpn.hasta <= '".$hasta."')
					  AND $where_estado
					  AND rgn.idconcepto = '".$idconceptod."'
					  AND rgn.tabla = '".$tablad."'
					GROUP BY tr.cedula)
				UNION
					(SELECT tr.cedula, tr.apellidos, tr.nombres, 0 as totald, sum(rgn.total) as totala
					  FROM generar_nomina gn

					  INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina)
					  INNER JOIN relacion_generar_nomina rgn ON (gn.idgenerar_nomina = rgn.idgenerar_nomina)
					  INNER JOIN trabajador tr ON (rgn.idtrabajador = tr.idtrabajador)
					WHERE (rpn.desde >= '".$desde."' AND rpn.hasta <= '".$hasta."')
					  AND $where_estado
					  AND rgn.idconcepto = '".$idconceptoa."'
					  AND rgn.tabla = '".$tablaa."'
					GROUP BY tr.cedula)
					ORDER BY $ordenar
					";
		//echo $sql;
		$query = mysql_query($sql) or die ($sql.mysql_error());			

		$sum_total=0;$sum_totald=0;$sum_totala=0;

		$i=0; $fila = 1;

		while ($field = mysql_fetch_array($query)) {
			if ($field['totala'] > 0){
				$total_aporte = $field['totala'];
				$sum_totala += $field['totala'];
			}
			if ($field['totald'] > 0){
				$total_deduccion = $field['totald'];
				$sum_totald += $field['totald'];
			}
			if($fila == 1){
				$fila++;
			}else{
				
				$totald = number_format($total_deduccion, 2, ',', '.');
				$totala = number_format($total_aporte, 2, ',', '.'); 
				$total = $total_deduccion + $total_aporte;
				$totali = number_format($total, 2, ',', '.'); 
				$sum_total = $sum_total + $total;

				$i++; $fila = 1;

				$pdf->Row(array($i, number_format($field['cedula'], 0, ',', '.'), utf8_decode($field['apellidos']).' '.utf8_decode($field['nombres']), $totald, $totala, $totali));
				$pdf->Ln(2);
			}
			$y = $pdf->GetY(); if ($y > 255) { relacion_anticipo_terceros($pdf, $nomconceptod, $nomconceptoa, $periodo, $nom_nomina, $estado); }
		}
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->Rect(5, $y, 200, 0.1);
		$pdf->Ln(5);
		$sum_totald = number_format($sum_totald, 2, ',', '.');
		$sum_totala = number_format($sum_totala, 2, ',', '.');
		$sum_total = number_format($sum_total, 2, ',', '.');

		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFont('Arial', 'B', 9);
		$pdf->Row(array('', '', '', $sum_totald, $sum_totala, $sum_total));
		//	---------------------------------------
		break;
	


	//	Relacion Conceptos Periodo...
	case "relacion_conceptos_periodo":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();

		// OBTENGO LOS DATOS DEL CONCEPTO
		list($tabla, $idconcepto)=SPLIT( '[|]', $idconcepto);
		$sql_consulta = mysql_query("select * from ".$tabla." where id".$tabla." = '".$idconcepto."'")or die(mysql_error());
		$bus_consulta = mysql_fetch_array($sql_consulta);
		$nomconcepto = $bus_consulta["descripcion"];
		//	---------------------------------------
		// OBTENGO LOS DATOS DE LA NOMINA
		$sql_consulta_nomina = mysql_query("select * from tipo_nomina where idtipo_nomina = '".$tipo_nomina."'")or die(mysql_error());
		$bus_consulta_nomina = mysql_fetch_array($sql_consulta_nomina);
		$nom_nomina = $bus_consulta_nomina["titulo_nomina"];
		//	---------------------------------------
		if ($estado == ''){
			$where_estado = "gn.idtipo_nomina = '".$tipo_nomina."'";
		}else{
			$where_estado = "gn.idtipo_nomina = '".$tipo_nomina."' AND gn.estado = '".$estado."'";
		}
		list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fdesde = $d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fhasta = $d."/".$m."/".$a;
		if ($desde != "" && $hasta != "") $periodo = "$fdesde - $fhasta";
		
		relacion_conceptos_periodo($pdf, $nomconcepto, $periodo, $nom_nomina, $estado);
		//	-----------------------------------------------------------------------------
		$sql = "SELECT tr.cedula, tr.apellidos, tr.nombres, sum(rgn.total) as total
					  FROM generar_nomina gn

					  INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina)
					  INNER JOIN relacion_generar_nomina rgn ON (gn.idgenerar_nomina = rgn.idgenerar_nomina)
					  INNER JOIN trabajador tr ON (rgn.idtrabajador = tr.idtrabajador)
					WHERE (rpn.desde >= '".$desde."' AND rpn.hasta <= '".$hasta."')
					  AND $where_estado
					  AND rgn.idconcepto = '".$idconcepto."'
					  AND rgn.tabla = '".$tabla."'
					GROUP BY rgn.idtrabajador
					ORDER BY $ordenar
					";

		$query = mysql_query($sql) or die ($sql.mysql_error());			


		$i=0; $sum_total=0;
		while ($field = mysql_fetch_array($query)) {
			$i++;
			$total = number_format($field['total'], 2, ',', '.'); $sum_total += $field['total'];
			$pdf->Row(array($i, number_format($field['cedula'], 0, ',', '.'), utf8_decode($field['apellidos']).' '.utf8_decode($field['nombres']), $total));
			$pdf->Ln(2);
			
			$y = $pdf->GetY(); if ($y > 255) { relacion_conceptos_periodo($pdf, $nomconcepto, $periodo, $nom_nomina, $estado); }
		}
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->Rect(5, $y, 200, 0.1);
		$pdf->Ln(5);
		$sum_total = number_format($sum_total, 2, ',', '.');
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFont('Arial', 'B', 9);
		$pdf->Row(array('', '', '', $sum_total));
		//	---------------------------------------
		break;
	




	//	Relacion de Nomina...
	case "relacion_nomina_trabajadores":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		
		if(isset($origen)){
			if($origen=='reportes'){
				$sql = "SELECT * FROM generar_nomina 
						WHERE idtipo_nomina = '".$nomina."' 
							AND idperiodo = '".$periodo."'
							AND (estado = 'procesado' OR estado = 'Pre Nomina')";
				$query_idnomina = mysql_query($sql) or die ($sql.mysql_error());
				$field_idnomina = mysql_fetch_array($query_idnomina)or die (mysql_error());
				$idgenerar_nomina = $field_idnomina["idgenerar_nomina"];
			}
		}

		if(isset($flagunidad)){

			if($flagunidad == 'S'){
				$sql_unidad = "SELECT denominacion FROM niveles_organizacionales
										WHERE idniveles_organizacionales = '".$unidad."'";
				$query_unidad = mysql_query($sql_unidad)or die(mysql_error());
				$field_unidad = mysql_fetch_array($query_unidad);
				$nomunidad = $field_unidad["denominacion"];
			}else{
				$nomunidad = '';
				$flagunidad = 'N';
			}
		}

		if(isset($flagcentro)){
			//print $flagcentro;
			if($flagcentro == 'S'){
				$sql_centro = "SELECT
										no.idcategoria_programatica,
										cp.codigo,
										ue.denominacion
									FROM
										categoria_programatica cp
										INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
										INNER JOIN niveles_organizacionales no ON (cp.idcategoria_programatica = no.idcategoria_programatica)
									WHERE no.idcategoria_programatica = '".$centro."'
									";
				$query_centro = mysql_query($sql_centro)or die(mysql_error());
				$field_centro = mysql_fetch_array($query_centro);
				$nomcentro = $field_centro["codigo"]." - ".$field_centro["denominacion"];
			}else{
				$nomcentro = '';
				$flagcentro = 'N';
			}
		}

		//	---------------------------------------
		//	Obtengo el id de la nomina generada....
		$sql = "SELECT * FROM generar_nomina 
				WHERE idgenerar_nomina = '".$idgenerar_nomina."' AND (estado = 'procesado' OR estado = 'Pre Nomina')";
		$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);
		//	---------------------------------------
		//	Obtengo los datos de la cabecera....
		$periodo = $field_nomina['idperiodo'];
		$nomina = $field_nomina['idtipo_nomina'];
		$idperiodo = $periodo;
		$sql = "SELECT 
					tn.titulo_nomina,
					rpn.desde,
					rpn.hasta,
					rpn.semana,
					tn.motivo_cuenta
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
		//	---------------------------------------
		relacion_nomina_trabajadores($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'], $field_titulo['semana'],
									$flagunidad, $nomunidad, $flagcentro, $nomcentro);
		//	---------------------------------------
		if ($flagunidad == "S" && $unidad != "0") $filtro_unidad = " AND mp.idubicacion_funcional = '".$unidad."'";
		if ($flagcentro == "S" && $centro != "0") $filtro_centro = " AND no2.idcategoria_programatica = '".$centro."'";
		if ($idtrabajador != "") $filtro_trabajador = " AND t.idtrabajador = '".$idtrabajador."'";
		//	---------------------------------------
		//	Obtengo los datos de los trabajadores que pertenecen a esta nomina generada...
		$sql = "(SELECT
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
					cp.codigo AS codcentro,
					t.vacaciones
				FROM
					relacion_generar_nomina rgn
					INNER JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					INNER JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
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
				GROUP BY rgn.idtrabajador)
				
				UNION
				
				(SELECT
					t.idtrabajador,
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
					cp.codigo AS codcentro,
					t.vacaciones
				FROM
					trabajador t
					INNER JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					LEFT JOIN niveles_organizacionales no ON (mp.idubicacion_funcional = no.idniveles_organizacionales)
					LEFT JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
					LEFT JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					INNER JOIN relacion_tipo_nomina_trabajador rtnt on (rtnt.idtrabajador = t.idtrabajador and rtnt.idtipo_nomina = '".$nomina."')
				WHERE
					mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal) 
												 FROM movimientos_personal 
												 WHERE 
												 	idtrabajador = t.idtrabajador AND
													fecha_movimiento <= '".$field_titulo['hasta']."') AND
					t.activo_nomina = 'si' AND
					t.vacaciones = 'si'
					$filtro_unidad
					$filtro_centro
					$filtro_trabajador
				GROUP BY t.idtrabajador)
				
				ORDER BY apellidos, nombres";
		$query_trabajador = mysql_query($sql) or die ($sql.mysql_error()); $i=0;	$filtro_centro_costo="";	$filtro_total_centro_costo="";
		$nro_trabajadores = mysql_num_rows($query_trabajador);
		while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
			if ($i==0) $agrupa_centro = $field_trabajador['codcentro'];			
			if ($i==0) $filtro_total_centro_costo .= " rgn.idtrabajador = '".$field_trabajador['idtrabajador']."'";
			else $filtro_total_centro_costo .= " OR rgn.idtrabajador = '".$field_trabajador['idtrabajador']."'";
			
			$i++; $tcentro_costo = $field_trabajador['centro_costo']; $tcodcentro_costo = $field_trabajador['codcentro']; $tnomcentro_costo = $field_trabajador['nomcentro']; $nro_trabajadores_centro++;
			
			list($a, $m, $d)=SPLIT( '[/.-]', $field_trabajador['fecha_ingreso']); $fingreso = $d."/".$m."/".$a;			
			$linea = $pdf->GetY(); 
			if ($linea > 200) relacion_nomina_trabajadores($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'], $field_titulo['semana'],
									$flagunidad,$nomunidad,$flagcentro,$nomcentro);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y = $pdf->GetY();
			$pdf->Rect(5, $y, 200, 0.1);
			$pdf->Ln(2);
			$sql_cuenta = mysql_query("select * from cuentas_bancarias_trabajador 
										where idtrabajador = '".$field_trabajador['idtrabajador']."' and 
											motivo = '".$field_titulo['motivo_cuenta']."'")or die(mysql_error());
			$reg_cuenta = mysql_fetch_array($sql_cuenta);

			if ($fcc==0) $filtro_centro_costo .= " rgn.idtrabajador = '".$field_trabajador['idtrabajador']."'";
			else $filtro_centro_costo .= " OR rgn.idtrabajador = '".$field_trabajador['idtrabajador']."'";
			$fcc++;
			//	Imprimo los datos del trabajador
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(35, 5, utf8_decode('CEDULA: '.number_format($field_trabajador['cedula'], 0, '', '.')), 0, 0, 'L');
			$pdf->Cell(30, 5, utf8_decode('FICHA: '.$field_trabajador['nro_ficha']), 0, 0, 'L');
			$pdf->Cell(40, 5, 'F.INGRESO: '.$fingreso, 0, 0, 'L');
			$pdf->Cell(15, 5, 'NOMBRE: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(80, 5, utf8_decode($field_trabajador['apellidos'].' '.$field_trabajador['nombres']), 0, 1, 'L');
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(155, 5, utf8_decode('CARGO: '.$field_trabajador['nomcargo']), 0, 0, 'L');
			$pdf->Cell(30, 5, utf8_decode('CUENTA: '.$reg_cuenta['nro_cuenta']), 0, 1, 'L');
			$pdf->Ln(2);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y = $pdf->GetY();
			$pdf->Rect(5, $y-1, 200, 0.1);
			
			$x=175;
			$sql = "SELECT 
						cn.descripcion,
						mp.valor
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
														idtrabajador = mp.idtrabajador 
														AND idconstante = cn.idconstantes_nomina
														AND fecha_movimiento <= '".$field_titulo['hasta']."')
					LIMIT 0,1";
			//echo $sql;
			$query_constantes = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_constantes=mysql_fetch_array($query_constantes)) {
				if ($x>225) { $pdf->Ln(); $x=0; }
				$pdf->Cell(70, 5, utf8_decode($field_constantes['descripcion']).': '.number_format($field_constantes['valor'], 2, ',', '.'), 0, 0, 'L');
				$x+=70;
			}
			$pdf->Ln(8);
			
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(100, 5, 'ASIGNACIONES', 0, 0, 'C');
			$pdf->Cell(100, 5, 'DEDUCCIONES', 0, 1, 'C');
			$pdf->Ln(2);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y = $pdf->GetY();
			$pdf->Rect(5, $y-1, 200, 0.1);
			
			$y = $pdf->GetY(); $ya = $y; $yd = $y;
			
			$total_asignaciones = 0;
			$total_deducciones = 0;
			
			if ($field_trabajador['vacaciones'] == "si") {				
				$pdf->SetXY(5, $ya);
				$pdf->SetFont('Arial', '', 15);
				$pdf->Cell(79, 5, utf8_decode('VACACIONES'), 0, 0, 'L');
				$pdf->Cell(19, 5, '', 0, 0, 'R');
				$ya+=4;
				$linea = $pdf->GetY(); 
				if ($linea > 250) relacion_nomina_trabajadores($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'], $field_titulo['semana'],
									$flagunidad,$nomunidad,$flagcentro,$nomcentro);
			} else {
				//	Imprimo las asignaciones del trabajador
				$sql = "(SELECT
							rgn.tabla,
							rgn.total,
							rgn.cantidad,
							cn.descripcion,
							rhtt.horas,
							cn.posicion
						FROM
							relacion_generar_nomina rgn
							INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
							LEFT JOIN relacion_formula_conceptos_nomina rfcn ON (cn.idconceptos_nomina = rfcn.idconcepto_nomina AND 
																				 rfcn.valor_oculto LIKE '%THT%')
							LEFT JOIN hoja_tiempo ht ON (ht.idtipo_hoja_tiempo = SUBSTRING(rfcn.valor_oculto, 5) AND
														 ht.idtipo_nomina = '".$nomina."' AND
														 ht.periodo = '".$idperiodo."')
							LEFT JOIN relacion_hoja_tiempo_trabajador rhtt ON (ht.idhoja_tiempo = rhtt.idhoja_tiempo AND
																			   rhtt.idtrabajador = '".$field_trabajador['idtrabajador']."')
						WHERE
							rgn.total > 0 AND
							rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
							rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn.tabla = 'conceptos_nomina'
						GROUP BY rgn.idconcepto)
				
						UNION
						
						(SELECT
							rgn.tabla,
							rgn.total,
							rgn.cantidad,
							cn.descripcion,
							'0.00' AS horas,
							cn.posicion
						FROM
							relacion_generar_nomina rgn
							INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
						WHERE
							rgn.total > 0 AND
							rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
							rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn.tabla = 'constantes_nomina'
						GROUP BY rgn.idconcepto)
							
						ORDER BY posicion";
				$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
					$pdf->SetXY(5, $ya);
					$pdf->SetFont('Arial', '', 8);
					
					if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
					else $cantidad = "";
					
					$hor = (int) $field_asignaciones['horas'];					
					if ($hor > 0) $horas = "(".number_format($hor, 2, ',', '.').")";
					else $horas = "";
					
					$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion'].' '.$horas), 0, 0, 'L');
					$pdf->Cell(19, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
					$ya+=4;
					$linea = $pdf->GetY(); 
					if ($linea > 250) relacion_nomina_trabajadores($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'], $field_titulo['semana'],
									$flagunidad,$nomunidad,$flagcentro,$nomcentro);
					$total_asignaciones+=$field_asignaciones['total'];
				} $cantidad = "";
				
				//	Imprimo las deducciones del trabajador	
				$sql = "(SELECT
							rgn.tabla,
							rgn.total,
							rgn.cantidad,
							cn.descripcion,
							cn.posicion,
							rhtt.horas
						FROM
							relacion_generar_nomina rgn
							LEFT JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
							LEFT JOIN relacion_formula_conceptos_nomina rfcn ON (cn.idconceptos_nomina = rfcn.idconcepto_nomina AND 
																				 rfcn.valor_oculto LIKE '%THT%')
							LEFT JOIN hoja_tiempo ht ON (ht.idtipo_hoja_tiempo = SUBSTRING(rfcn.valor_oculto, 5) AND
														 ht.idtipo_nomina = '".$nomina."' AND
														 ht.periodo = '".$idperiodo."')
							LEFT JOIN relacion_hoja_tiempo_trabajador rhtt ON (ht.idhoja_tiempo = rhtt.idhoja_tiempo AND
																			   rhtt.idtrabajador = '".$field_trabajador['idtrabajador']."')
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
							cn.posicion,
							'0.00' AS horas
						FROM
							relacion_generar_nomina rgn
							INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
						WHERE
							total > 0 AND
							rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
							rgn.tabla = 'constantes_nomina'
						GROUP BY rgn.idconcepto)
							
						ORDER BY posicion";
				$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
					$pdf->SetXY(108, $yd);
					$pdf->SetFont('Arial', '', 8);
					
					if ($field_deducciones['cantidad'] > 0) $cantidad = "(".$field_deducciones['cantidad'].")";
					else $cantidad = "";
					
					$hor = (int) $field_asignaciones['horas'];					
					if ($hor > 0) $horas = "(".number_format($hor, 2, ',', '.').")";
					else $horas = "";
					
					$pdf->Cell(79, 5, utf8_decode($field_deducciones['descripcion'].' '.$horas), 0, 0, 'L');
					$pdf->Cell(19, 5, number_format($field_deducciones['total'], 2, ',', '.'), 0, 0, 'R');
					$yd+=4;
					$linea = $pdf->GetY(); 
					if ($linea > 250) relacion_nomina_trabajadores($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'], $field_titulo['semana'],
									$flagunidad,$nomunidad,$flagcentro,$nomcentro);
					$total_deducciones+=$field_deducciones['total'];
				} $cantidad = "";
			}
			
			$pdf->Ln(8);
			
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			if ($ya > $yd) $ytotal = $ya; else $ytotal = $yd;
			$pdf->Rect(5, $ytotal+1, 200, 0.1);
			
			if ($field_trabajador['vacaciones'] == "si") {
				$pdf->Ln(20);
			} else {
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
				$pdf->SetFont('Arial', 'B', 13);
				$pdf->Cell(19, 5, number_format($total_neto, 2, ',', '.'), 0, 0, 'R');
				$pdf->Ln(5);
			}
			
			$pdf->SetFont('Arial', '', 8);
			//	imprimo las firmas
			$linea = $pdf->GetY(); if ($linea > 280) $pdf->AddPage();			
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y = $pdf->GetY();
			$pdf->Rect(40, $y-1, 40, 0.1);
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetX(40); $pdf->Cell(40, 5, 'RECIBI CONFORME', 0, 0, 'C');
			//--
			$pdf->Ln(10);
		}
		
		
		
		break;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//	Relacion de Nomina...
	case "listado":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		
		//	---------------------------------------
		//	Obtengo el id de la nomina generada....
		$sql = "SELECT * FROM generar_nomina WHERE idgenerar_nomina = '".$idgenerar_nomina."' AND (estado = 'procesado' OR estado = 'Pre Nomina')";
		$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);
		//	---------------------------------------
		//	Obtengo los datos de la cabecera....
		$periodo = $field_nomina['idperiodo'];
		$nomina = $field_nomina['idtipo_nomina'];
		$idperiodo = $periodo;
		$sql = "SELECT 
					tn.titulo_nomina,
					rpn.desde,
					rpn.hasta,
					rpn.semana
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
		//	---------------------------------------
		listado($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'], $field_titulo['semana']);
		//	---------------------------------------
		if ($flagunidad == "S" && $unidad != "0") $filtro_unidad = " AND mp.idunidad_funcional = '".$unidad."'";
		if ($flagcentro == "S" && $centro != "0") $filtro_centro = " AND no2.idcategoria_programatica = '".$centro."'";
		if ($idtrabajador != "") $filtro_trabajador = " AND t.idtrabajador = '".$idtrabajador."'";
		//	---------------------------------------
		//	Obtengo los datos de los trabajadores que pertenecen a esta nomina generada...
		$sql = "(SELECT
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
					cp.codigo AS codcentro,
					t.vacaciones
				FROM
					relacion_generar_nomina rgn
					INNER JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					INNER JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
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
					
				GROUP BY rgn.idtrabajador)
				
				UNION
				
				(SELECT
					t.idtrabajador,
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
					cp.codigo AS codcentro,
					t.vacaciones
				FROM
					trabajador t
					INNER JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					LEFT JOIN niveles_organizacionales no ON (mp.idubicacion_funcional = no.idniveles_organizacionales)
					LEFT JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
					LEFT JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					INNER JOIN relacion_tipo_nomina_trabajador rtnt on (rtnt.idtrabajador = t.idtrabajador and rtnt.idtipo_nomina = '".$nomina."')
				WHERE
					mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal) 
												 FROM movimientos_personal 
												 WHERE 
												 	idtrabajador = t.idtrabajador AND
													fecha_movimiento <= '".$field_titulo['hasta']."') AND
					t.activo_nomina = 'si' AND
					t.vacaciones = 'si'
					
				GROUP BY t.idtrabajador)
				
				ORDER BY apellidos, nombres, cedula";
		$query_trabajador = mysql_query($sql) or die ($sql.mysql_error()); $i=0;	$filtro_centro_costo="";	$filtro_total_centro_costo="";
		$nro_trabajadores = mysql_num_rows($query_trabajador);
		$x=1;
		while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
			
			$total_asignaciones = 0;
			$total_deducciones = 0;
			
			if ($field_trabajador['vacaciones'] == "si") {				
				
				
			} else {
				//	Imprimo las asignaciones del trabajador
				$sql = "(SELECT
							rgn.tabla,
							rgn.total,
							rgn.cantidad,
							cn.descripcion,
							rhtt.horas,
							cn.posicion
						FROM
							relacion_generar_nomina rgn
							INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
							LEFT JOIN relacion_formula_conceptos_nomina rfcn ON (cn.idconceptos_nomina = rfcn.idconcepto_nomina AND 
																				 rfcn.valor_oculto LIKE '%THT%')
							LEFT JOIN hoja_tiempo ht ON (ht.idtipo_hoja_tiempo = SUBSTRING(rfcn.valor_oculto, 5) AND
														 ht.idtipo_nomina = '".$nomina."' AND
														 ht.periodo = '".$idperiodo."')
							LEFT JOIN relacion_hoja_tiempo_trabajador rhtt ON (ht.idhoja_tiempo = rhtt.idhoja_tiempo AND
																			   rhtt.idtrabajador = '".$field_trabajador['idtrabajador']."')
						WHERE
							rgn.total > 0 AND
							rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
							rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn.tabla = 'conceptos_nomina'
						GROUP BY rgn.idconcepto)
				
						UNION
						
						(SELECT
							rgn.tabla,
							rgn.total,
							rgn.cantidad,
							cn.descripcion,
							'0.00' AS horas,
							cn.posicion
						FROM
							relacion_generar_nomina rgn
							INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
						WHERE
							rgn.total > 0 AND
							rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
							rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn.tabla = 'constantes_nomina'
						GROUP BY rgn.idconcepto)
							
						ORDER BY posicion";
				$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
										
					
					$total_asignaciones+=$field_asignaciones['total'];
				} 
				
				//	Imprimo las deducciones del trabajador	
				$sql = "(SELECT
							rgn.tabla,
							rgn.total,
							rgn.cantidad,
							cn.descripcion,
							cn.posicion,
							rhtt.horas
						FROM
							relacion_generar_nomina rgn
							LEFT JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
							LEFT JOIN relacion_formula_conceptos_nomina rfcn ON (cn.idconceptos_nomina = rfcn.idconcepto_nomina AND 
																				 rfcn.valor_oculto LIKE '%THT%')
							LEFT JOIN hoja_tiempo ht ON (ht.idtipo_hoja_tiempo = SUBSTRING(rfcn.valor_oculto, 5) AND
														 ht.idtipo_nomina = '".$nomina."' AND
														 ht.periodo = '".$idperiodo."')
							LEFT JOIN relacion_hoja_tiempo_trabajador rhtt ON (ht.idhoja_tiempo = rhtt.idhoja_tiempo AND
																			   rhtt.idtrabajador = '".$field_trabajador['idtrabajador']."')
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
							cn.posicion,
							'0.00' AS horas
						FROM
							relacion_generar_nomina rgn
							INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
						WHERE
							total > 0 AND
							rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
							rgn.tabla = 'constantes_nomina'
						GROUP BY rgn.idconcepto)
							
						ORDER BY posicion";
				$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
									
					$total_deducciones+=$field_deducciones['total'];
				} ;
			}
			
			if ($field_trabajador['vacaciones'] == "si") {				
				
				
			} else {
			$total_neto = $total_asignaciones - $total_deducciones;
			$sql_cuenta = mysql_query("select * from cuentas_bancarias_trabajador where idtrabajador = '".$field_trabajador['idtrabajador']."' and motivo = '2'")or die(mysql_error());
			$reg_cuenta = mysql_fetch_array($sql_cuenta);
			if ($reg_cuenta['nro_cuenta'] <> ''){
				$pdf->SetFont('Arial', '', 10);
				$pdf->Row(array($x, number_format($field_trabajador['cedula'], 0, '', '.'),
							utf8_decode($field_trabajador['apellidos'].', '.$field_trabajador['nombres']), 
							utf8_decode($reg_cuenta['nro_cuenta']),
							number_format($total_neto, 2, ',', '.')));
							$total_aporte = $total_aporte + $total_neto;
				$x++;
				$pdf->Ln(3);
				$linea = $pdf->GetY(); 
				if ($linea > 250) listado($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'], $field_titulo['semana']);
			}
			}
			
			
			
		}
		$y = $pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		$pdf->Rect(5, $y, 210, 0.1);
		$pdf->Ln(2);
		
		$pdf->SetFont('Arial', '', 12);
		$pdf->Cell(100, 5, 'TOTAL Bs.', 0, 0, 'L');
		$pdf->Cell(105, 5, number_format($total_aporte, 2, ',', '.'), 0, 0, 'R');
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$total_aporte_final = $total_aporte;
		$pdf->Ln(10);
		$pdf->SetFont('Arial', '', 12);
		$pdf->Cell(100, 5, 'TRABAJADORES SIN CUENTA', 0, 0, 'L');
		$pdf->Cell(105, 5, '', 0, 0, 'R');
		$pdf->Ln(5);
		$y = $pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		$pdf->Rect(5, $y, 210, 0.1);
		$pdf->Ln(2);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		
		$total_aporte=0;
		//	Obtengo los datos de los trabajadores que pertenecen a esta nomina generada...
		$sql = "(SELECT
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
					cp.codigo AS codcentro,
					t.vacaciones
				FROM
					relacion_generar_nomina rgn
					INNER JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					INNER JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
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
					
				GROUP BY rgn.idtrabajador)
				
				UNION
				
				(SELECT
					t.idtrabajador,
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
					cp.codigo AS codcentro,
					t.vacaciones
				FROM
					trabajador t
					INNER JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					LEFT JOIN niveles_organizacionales no ON (mp.idubicacion_funcional = no.idniveles_organizacionales)
					LEFT JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
					LEFT JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					INNER JOIN relacion_tipo_nomina_trabajador rtnt on (rtnt.idtrabajador = t.idtrabajador and rtnt.idtipo_nomina = '".$nomina."')
				WHERE
					mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal) 
												 FROM movimientos_personal 
												 WHERE 
												 	idtrabajador = t.idtrabajador AND
													fecha_movimiento <= '".$field_titulo['hasta']."') AND
					t.activo_nomina = 'si' AND
					t.vacaciones = 'si'
					
				GROUP BY t.idtrabajador)
				
				ORDER BY apellidos, nombres, cedula";
		$query_trabajador = mysql_query($sql) or die ($sql.mysql_error()); $i=0;	$filtro_centro_costo="";	$filtro_total_centro_costo="";
		$nro_trabajadores = mysql_num_rows($query_trabajador);
		while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
			
			$total_asignaciones = 0;
			$total_deducciones = 0;
			
			if ($field_trabajador['vacaciones'] == "si") {				
				
				
			} else {
				//	Imprimo las asignaciones del trabajador
				$sql = "(SELECT
							rgn.tabla,
							rgn.total,
							rgn.cantidad,
							cn.descripcion,
							rhtt.horas,
							cn.posicion
						FROM
							relacion_generar_nomina rgn
							INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
							LEFT JOIN relacion_formula_conceptos_nomina rfcn ON (cn.idconceptos_nomina = rfcn.idconcepto_nomina AND 
																				 rfcn.valor_oculto LIKE '%THT%')
							LEFT JOIN hoja_tiempo ht ON (ht.idtipo_hoja_tiempo = SUBSTRING(rfcn.valor_oculto, 5) AND
														 ht.idtipo_nomina = '".$nomina."' AND
														 ht.periodo = '".$idperiodo."')
							LEFT JOIN relacion_hoja_tiempo_trabajador rhtt ON (ht.idhoja_tiempo = rhtt.idhoja_tiempo AND
																			   rhtt.idtrabajador = '".$field_trabajador['idtrabajador']."')
						WHERE
							rgn.total > 0 AND
							rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
							rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn.tabla = 'conceptos_nomina'
						GROUP BY rgn.idconcepto)
				
						UNION
						
						(SELECT
							rgn.tabla,
							rgn.total,
							rgn.cantidad,
							cn.descripcion,
							'0.00' AS horas,
							cn.posicion
						FROM
							relacion_generar_nomina rgn
							INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
						WHERE
							rgn.total > 0 AND
							rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
							rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn.tabla = 'constantes_nomina'
						GROUP BY rgn.idconcepto)
							
						ORDER BY posicion";
				$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
										
					
					$total_asignaciones+=$field_asignaciones['total'];
				} 
				
				//	Imprimo las deducciones del trabajador	
				$sql = "(SELECT
							rgn.tabla,
							rgn.total,
							rgn.cantidad,
							cn.descripcion,
							cn.posicion,
							rhtt.horas
						FROM
							relacion_generar_nomina rgn
							LEFT JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
							LEFT JOIN relacion_formula_conceptos_nomina rfcn ON (cn.idconceptos_nomina = rfcn.idconcepto_nomina AND 
																				 rfcn.valor_oculto LIKE '%THT%')
							LEFT JOIN hoja_tiempo ht ON (ht.idtipo_hoja_tiempo = SUBSTRING(rfcn.valor_oculto, 5) AND
														 ht.idtipo_nomina = '".$nomina."' AND
														 ht.periodo = '".$idperiodo."')
							LEFT JOIN relacion_hoja_tiempo_trabajador rhtt ON (ht.idhoja_tiempo = rhtt.idhoja_tiempo AND
																			   rhtt.idtrabajador = '".$field_trabajador['idtrabajador']."')
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
							cn.posicion,
							'0.00' AS horas
						FROM
							relacion_generar_nomina rgn
							INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
						WHERE
							total > 0 AND
							rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
							rgn.tabla = 'constantes_nomina'
						GROUP BY rgn.idconcepto)
							
						ORDER BY posicion";
				$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
									
					$total_deducciones+=$field_deducciones['total'];
				} ;
			}
			
			if ($field_trabajador['vacaciones'] == "si") {				
				
				
			} else {
				$q=1;
				$total_neto = $total_asignaciones - $total_deducciones;
				$sql_cuenta = mysql_query("select * from cuentas_bancarias_trabajador where idtrabajador = '".$field_trabajador['idtrabajador']."' and motivo = '2'")or die(mysql_error());
				$tiene_cuenta = mysql_num_rows($sql_cuenta);
				if ($tiene_cuenta > 0){
					$reg_cuenta = mysql_fetch_array($sql_cuenta);
					$nro_cuenta = $reg_cuenta['nro_cuenta'];
				}else{
					$nro_cuenta ='';
				}
				
				if ($nro_cuenta == '' || $tiene_cuenta<=0){
					$pdf->SetFont('Arial', '', 10);
					$pdf->Row(array($q, number_format($field_trabajador['cedula'], 0, '', '.'),
								utf8_decode($field_trabajador['apellidos'].', '.$field_trabajador['nombres']), 
								'',
								number_format($total_neto, 2, ',', '.')));
								$total_aporte = $total_aporte + $total_neto;
								$q++;
					//$pdf->Ln(3);
					$linea = $pdf->GetY(); 
					if ($linea > 250) listado($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'], $field_titulo['semana']);
				}
			}
			
			
			
		}
		$y = $pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		$pdf->Rect(5, $y, 210, 0.1);
		$pdf->Ln(2);
		$pdf->SetFont('Arial', '', 12);
		//
		$pdf->Cell(100, 5, 'TOTAL Bs Cheques Individuales.', 0, 0, 'L');
		$pdf->Cell(105, 5, number_format($total_aporte, 2, ',', '.'), 0, 0, 'R');
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->Ln(14);
		$y = $pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		$pdf->Rect(5, $y, 210, 0.1);
		$pdf->Ln(2);
		$pdf->SetFont('Arial', '', 12);
		//$pdf->Ln(10);
		$pdf->Cell(100, 5, 'TOTAL NOMINA Bs.', 0, 0, 'L');
		$pdf->Cell(105, 5, number_format(($total_aporte_final+$total_aporte), 2, ',', '.'), 0, 0, 'R');
		
		break;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		
	//	Payroll...
	case "payroll_trabajadores":
		$pdf=new PDF_MC_Table8('P', 'mm', 'Letter');
		$pdf->SetTopMargin(1);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 1);
		$pdf->Open();
		
		
		$sql_configuracion = mysql_query("select * from configuracion");
		$reg_configuracion = mysql_fetch_array($sql_configuracion);
		$empresa = $reg_configuracion["nombre_institucion"];
		$rif = $reg_configuracion["rif"];
		//	---------------------------------------
		//	Obtengo el id de la nomina generada....
		$sql = "SELECT * FROM generar_nomina WHERE idgenerar_nomina = '".$idgenerar_nomina."' AND (estado = 'procesado' OR estado = 'Pre Nomina')";
		$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);
		//	---------------------------------------
		//	Obtengo los datos de la cabecera....
		$periodo = $field_nomina['idperiodo'];
		$nomina = $field_nomina['idtipo_nomina'];
		$idperiodo = $periodo;

		$sql = "SELECT 
					tn.titulo_nomina,
					pn.numero_periodos,
					rpn.desde,
					rpn.hasta,
					rpn.semana,
					tn.motivo_cuenta
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
		//if ($field_titulo["numero_periodos"] == 52){
			$semana = $field_titulo["semana"];
		//}else{
		//	$semana = '';
		//}
		//$semana = 0;
		//	---------------------------------------
		$pdf->AddPage();
		//	---------------------------------------
		if ($flagunidad == "S" && $unidad != "0") $filtro_unidad = " AND mp.idunidad_funcional = '".$unidad."'";
		if ($flagcentro == "S" && $centro != "0") $filtro_centro = " AND no2.idcategoria_programatica = '".$centro."'";
		if ($idtrabajador != "") {
			$filtro_trabajador1 = " AND rgn.idtrabajador = '".$idtrabajador."'";
			$filtro_trabajador2 = " AND t.idtrabajador = '".$idtrabajador."'";
			}
		//	---------------------------------------
		//	Obtengo los datos de los trabajadores que pertenecen a esta nomina generada...
		$sql = "(SELECT
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
					cp.codigo AS codcentro,
					t.vacaciones
				FROM
					relacion_generar_nomina rgn
					INNER JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					INNER JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
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
					$filtro_trabajador1
				GROUP BY rgn.idtrabajador)
				
				UNION
				
				(SELECT
					t.idtrabajador,
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
					cp.codigo AS codcentro,
					t.vacaciones
				FROM
					trabajador t
					INNER JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					LEFT JOIN niveles_organizacionales no ON (mp.idubicacion_funcional = no.idniveles_organizacionales)
					LEFT JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
					LEFT JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					INNER JOIN relacion_tipo_nomina_trabajador rtnt on (rtnt.idtrabajador = t.idtrabajador and rtnt.idtipo_nomina = '".$nomina."')
				WHERE
					mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal) 
												 FROM movimientos_personal 
												 WHERE 
												 	idtrabajador = t.idtrabajador AND
													fecha_movimiento <= '".$field_titulo['hasta']."') AND
					t.activo_nomina = 'si' AND
					t.vacaciones = 'si'
					$filtro_unidad
					$filtro_centro
					$filtro_trabajador2
				GROUP BY t.idtrabajador)
				
				ORDER BY apellidos, nombres";
		$query_trabajador = mysql_query($sql) or die ($sql.mysql_error()); $i=0; $j=1;
		$nro_trabajadores = mysql_num_rows($query_trabajador); $sw=0;
		while ($field_trabajador = mysql_fetch_array($query_trabajador)) { $sw++;
			if ($sw == 1) $pdf->SetY(10);
			else $pdf->SetY(150);
			
			relacion_empleado_payroll($pdf, $field_titulo['titulo_nomina'], $periodo, $j, $semana, $empresa, $rif);$j++;
			
			list($a, $m, $d)=SPLIT( '[/.-]', $field_trabajador['fecha_ingreso']); $fingreso = $d."/".$m."/".$a;
			
			$linea = $pdf->GetY(); if ($linea > 250) $pdf->AddPage();
			
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y = $pdf->GetY();
			$pdf->Rect(5, $y, 200, 0.1);
			$pdf->Ln(2);
			$sql_cuenta = mysql_query("select * from cuentas_bancarias_trabajador 
										where idtrabajador = '".$field_trabajador['idtrabajador']."' and 
											motivo = '".$field_titulo['motivo_cuenta']."'")or die(mysql_error());
			$reg_cuenta = mysql_fetch_array($sql_cuenta);

			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(15, 5, utf8_decode('CEDULA: '), 0, 0, 'L');
			$pdf->SetFont('Arial', '', 10);			
			$pdf->Cell(20, 5, utf8_decode(number_format($field_trabajador['cedula'], 0, '', '.')), 0, 0, 'L');
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(30, 5, utf8_decode('FICHA: '.$field_trabajador['nro_ficha']), 0, 0, 'L');
			$pdf->Cell(40, 5, 'F.INGRESO: '.$fingreso, 0, 0, 'L');
			$pdf->Cell(15, 5, 'NOMBRE: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Cell(80, 5, utf8_decode($field_trabajador['apellidos'].' '.$field_trabajador['nombres']), 0, 1, 'L');
			$pdf->SetFont('Arial', '', 8);
			
			$pdf->Cell(155, 5, utf8_decode('CARGO: '.substr($field_trabajador['nomcargo'], 0, 65)), 0, 0, 'L');
			$pdf->Cell(30, 5, utf8_decode('CUENTA: '.$reg_cuenta['nro_cuenta']), 0, 1, 'L');

			$pdf->Cell(50, 5, utf8_decode('UNIDAD: '.$field_trabajador['codunidad'].' '.$field_trabajador['nomunidad']), 0, 1, 'L');
			$pdf->Cell(90, 5, utf8_decode('C.COSTO: '.$field_trabajador['codcentro'].' '.$field_trabajador['nomcentro']), 0, 1, 'L');
			
			$x=175;
			$sql = "SELECT 
						cn.descripcion,
						mp.valor
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
														idtrabajador = mp.idtrabajador 
														AND idconstante = cn.idconstantes_nomina
														AND fecha_movimiento <= '".$field_titulo['hasta']."')
					LIMIT 0,1";

			//echo $sql;

			$query_constantes = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_constantes=mysql_fetch_array($query_constantes)) {
				if ($x>225) { $pdf->Ln(); $x=0; }
				$pdf->Cell(70, 5, utf8_decode($field_constantes['descripcion']).': '.number_format($field_constantes['valor'], 2, ',', '.'), 0, 0, 'L');
				$x+=70;
			}
			$pdf->Ln(4);
			
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y = $pdf->GetY();
			$pdf->Rect(5, $y+4, 200, 0.1);
			
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(100, 5, 'ASIGNACIONES', 0, 0, 'C');
			$pdf->Cell(100, 5, 'DEDUCCIONES', 0, 1, 'C');
			$pdf->Ln(1);
			$y = $pdf->GetY(); $ya = $y; $yd = $y;
			
			$total_asignaciones = 0;
			$total_deducciones = 0;
			if ($field_trabajador['vacaciones'] == "si") {				
				$pdf->SetXY(5, $ya);
				$pdf->SetFont('Arial', '', 15);
				$pdf->Cell(79, 5, utf8_decode('VACACIONES'), 0, 0, 'L');
				$pdf->Cell(19, 5, '', 0, 0, 'R');
				$ya+=4;
				$linea = $pdf->GetY(); 
				if ($linea > 250) relacion_empleado_payroll($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['nomunidad'], $field_titulo['semana']);
			} else {
				//	Imprimo las asignaciones del trabajador	
				$sql = "(SELECT
							rgn.tabla,
							rgn.total,
							rgn.cantidad,
							cn.descripcion,
							rhtt.horas,
							cn.posicion
						FROM
							relacion_generar_nomina rgn
							INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
							LEFT JOIN relacion_formula_conceptos_nomina rfcn ON (cn.idconceptos_nomina = rfcn.idconcepto_nomina AND 
																				 rfcn.valor_oculto LIKE '%THT%')
							LEFT JOIN hoja_tiempo ht ON (ht.idtipo_hoja_tiempo = SUBSTRING(rfcn.valor_oculto, 5) AND
														 ht.idtipo_nomina = '".$nomina."' AND
														 ht.periodo = '".$idperiodo."')
							LEFT JOIN relacion_hoja_tiempo_trabajador rhtt ON (ht.idhoja_tiempo = rhtt.idhoja_tiempo AND
																			   rhtt.idtrabajador = '".$field_trabajador['idtrabajador']."')
						WHERE
							rgn.total > 0 AND
							rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
							rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn.tabla = 'conceptos_nomina'
							GROUP BY rgn.idconcepto)
				
						UNION
						
						(SELECT
							rgn.tabla,
							rgn.total,
							rgn.cantidad,
							cn.descripcion,
							'0.00' AS horas,
							cn.posicion
						FROM
							relacion_generar_nomina rgn
							INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
						WHERE
							rgn.total > 0 AND
							rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
							rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn.tabla = 'constantes_nomina'
							GROUP BY rgn.idconcepto)
						ORDER BY posicion";
				$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
					$pdf->SetXY(5, $ya);
					if ($field_asignaciones['desagregar_concepto'] == "si")
						$pdf->SetFont('Arial', 'U', 8);
					else
						$pdf->SetFont('Arial', '', 8);
					
					if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
					else $cantidad = "";
					
					$hor = (int) $field_asignaciones['horas'];					
					if ($hor > 0) $horas = "(".number_format($hor, 2, ',', '.').")";
					else $horas = "";
					
					$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion'].' '.$horas), 0, 0, 'L');
					$pdf->Cell(19, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
					$ya+=4;
					$linea = $pdf->GetY(); if ($linea > 250) $pdf->AddPage();
					$total_asignaciones+=$field_asignaciones['total'];
					
					if ($field_asignaciones['desagregar_concepto'] == "si") {
						$sql = "SELECT
									cn.descripcion,
									cd.*
								FROM
									conceptos_desagregados cd
									INNER JOIN conceptos_nomina cn ON (cd.idconcepto = cn.idconceptos_nomina)
								WHERE
									cd.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND 
									cd.idtrabajador = '".$field_trabajador['idtrabajador']."'
								ORDER BY idconceptos_desagregados DESC";
						$query_desagregados = mysql_query($sql) or die ($sql.mysql_error());
						while ($field_desagregados = mysql_fetch_array($query_desagregados)) {
							$pdf->SetXY(10, $ya);
							$pdf->SetFont('Arial', 'I', 8);
							
							if ($field_desagregados['valor'] > 0) {
								$pdf->Cell(74, 5, utf8_decode($field_desagregados['descripcion']), 0, 0, 'L');
								$pdf->Cell(19, 5, number_format($field_desagregados['valor'], 2, ',', '.'), 0, 0, 'R');
								$ya+=4;
								$linea = $pdf->GetY(); if ($linea > 250) $pdf->AddPage();
							}
						}
					}
				} $cantidad = "";
				
				//	Imprimo las deducciones del trabajador	
				$sql = "(SELECT
							rgn.tabla,
							rgn.total,
							rgn.cantidad,
							cn.descripcion,
							cn.posicion,
							rhtt.horas
						FROM
							relacion_generar_nomina rgn
							LEFT JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
							LEFT JOIN relacion_formula_conceptos_nomina rfcn ON (cn.idconceptos_nomina = rfcn.idconcepto_nomina AND 
																				 rfcn.valor_oculto LIKE '%THT%')
							LEFT JOIN hoja_tiempo ht ON (ht.idtipo_hoja_tiempo = SUBSTRING(rfcn.valor_oculto, 5) AND
														 ht.idtipo_nomina = '".$nomina."' AND
														 ht.periodo = '".$idperiodo."')
							LEFT JOIN relacion_hoja_tiempo_trabajador rhtt ON (ht.idhoja_tiempo = rhtt.idhoja_tiempo AND
																			   rhtt.idtrabajador = '".$field_trabajador['idtrabajador']."')
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
							cn.posicion,
							'0.00' AS horas
						FROM
							relacion_generar_nomina rgn
							INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
						WHERE
							total > 0 AND
							rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
							rgn.tabla = 'constantes_nomina'
							GROUP BY rgn.idconcepto)
						ORDER BY posicion";
				$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
					$pdf->SetXY(108, $yd);
					$pdf->SetFont('Arial', '', 8);
					
					if ($field_deducciones['cantidad'] > 0) $cantidad = "(".$field_deducciones['cantidad'].")";
					else $cantidad = "";
					
					$hor = (int) $field_asignaciones['horas'];					
					if ($hor > 0) $horas = "(".number_format($hor, 2, ',', '.').")";
					else $horas = "";
					
					$pdf->Cell(79, 5, utf8_decode($field_deducciones['descripcion'].' '.$horas), 0, 0, 'L');
					$pdf->Cell(19, 5, number_format($field_deducciones['total'], 2, ',', '.'), 0, 0, 'R');
					$yd+=4;
					$linea = $pdf->GetY(); if ($linea > 250) $pdf->AddPage();
					$total_deducciones+=$field_deducciones['total'];
				} $cantidad = "";
			}
			
			$pdf->Ln(8);
			
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			if ($ya > $yd) $ytotal = $ya; else $ytotal = $yd;
			
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y = $pdf->GetY();
			$pdf->Rect(5, $ytotal+1, 200, 0.1);
			
			
			
			if ($field_trabajador['vacaciones'] == "si") {
				$pdf->Ln(20);
			} else {
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetXY(5, $ytotal+3);
				$pdf->Cell(79, 5, 'TOTAL ASIGNACIONES', 0, 0, 'L');
				$pdf->Cell(19, 5, number_format($total_asignaciones, 2, ',', '.'), 0, 0, 'R');
				$pdf->SetXY(108, $ytotal+3);
				$pdf->Cell(79, 5, 'TOTAL DEDUCCIONES', 0, 0, 'L');
				$pdf->Cell(19, 5, number_format($total_deducciones, 2, ',', '.'), 0, 1, 'R');
				
				$pdf->SetFont('Arial', 'B', 8);
				$total_neto = $total_asignaciones - $total_deducciones;
				$pdf->SetX(108, $ytotal+7);
				$pdf->Cell(79, 5, 'TOTAL NETO', 0, 0, 'L');
				$pdf->SetFont('Arial', 'B', 12);
				$pdf->Cell(19, 5, number_format($total_neto, 2, ',', '.'), 0, 0, 'R');			
				$linea = $pdf->GetY(); if ($linea > 250) $pdf->AddPage();			
				$pdf->Ln(10);
			}
			
			$pdf->SetFont('Arial', '', 8);
			//	imprimo las firmas
			$linea = $pdf->GetY(); if ($linea > 280) $pdf->AddPage();			
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y = $pdf->GetY();
			$pdf->Rect(160, $y-1, 40, 0.1);
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetX(160); $pdf->Cell(40, 5, 'RECIBI CONFORME', 0, 0, 'C');
			//--
			
			$pdf->Ln(10);
			if ($sw == 2) {
				$sw = 0;
				$pdf->AddPage();
			}
		}
		break;
		
	//	Resumen de Conceptos...
	case "nomina_resumen_conceptos":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();

		if(isset($origen)){
			if($origen=='reportes'){
				$sql = "SELECT * FROM generar_nomina 
						WHERE idtipo_nomina = '".$nomina."' 
							AND idperiodo = '".$periodo."'
							AND (estado = 'procesado' OR estado = 'Pre Nomina')";
				$query_idnomina = mysql_query($sql) or die ($sql.mysql_error());
				$field_idnomina = mysql_fetch_array($query_idnomina)or die (mysql_error());
				$idgenerar_nomina = $field_idnomina["idgenerar_nomina"];
			}
		}

		if(isset($flagunidad)){

			if($flagunidad == 'S'){
				$sql_unidad = "SELECT denominacion FROM niveles_organizacionales
										WHERE idniveles_organizacionales = '".$unidad."'";
				$query_unidad = mysql_query($sql_unidad)or die(mysql_error());
				$field_unidad = mysql_fetch_array($query_unidad);
				$nomunidad = $field_unidad["denominacion"];
			}else{
				$nomunidad = '';
				$flagunidad = 'N';
			}
		}

		if(isset($flagcentro)){
			//print $flagcentro;
			if($flagcentro == 'S'){
				$sql_centro = "SELECT
										no.idcategoria_programatica,
										cp.codigo,
										ue.denominacion
									FROM
										categoria_programatica cp
										INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
										INNER JOIN niveles_organizacionales no ON (cp.idcategoria_programatica = no.idcategoria_programatica)
									WHERE no.idcategoria_programatica = '".$centro."'
									";
				$query_centro = mysql_query($sql_centro)or die(mysql_error());
				$field_centro = mysql_fetch_array($query_centro);
				$nomcentro = $field_centro["codigo"]." - ".$field_centro["denominacion"];
			}else{
				$nomcentro = '';
				$flagcentro = 'N';
			}
		}

				//	---------------------------------------
		//	Obtengo el id de la nomina generada....
		$sql = "SELECT * FROM generar_nomina 
				WHERE idgenerar_nomina = '".$idgenerar_nomina."' AND (estado = 'procesado' OR estado = 'Pre Nomina')";
		$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);
		//	---------------------------------------
		//	Obtengo los datos de la cabecera....
		$periodo = $field_nomina['idperiodo'];
		$nomina = $field_nomina['idtipo_nomina'];
		$idperiodo = $periodo;
//	---------------------------------------
		//	Obtengo los datos de la cabecera....
		$sql = "SELECT 
					tn.titulo_nomina,
					rpn.desde,
					rpn.hasta
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

		nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", 
			$titulo_centro, $flagunidad, $flagcentro, $nomunidad, $nomcentro);
		
		//	---------------------------------------
		if ($flagunidad == "S" && $unidad != "0") $filtro_unidad = " AND mp.idubicacion_funcional = '".$unidad."'";
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
					relacion_generar_nomina rgn
					INNER JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					INNER JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
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
		$query_trabajador = mysql_query($sql) or die ($sql.mysql_error()); $i=0;	$filtro_centro_costo="";	$filtro_total_centro_costo="";
		$nro_trabajadores = mysql_num_rows($query_trabajador);
		$van_centros=1;

		while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
			if ($i==0) $agrupa_centro = $field_trabajador['codcentro'];
			
			if ($i==0) $filtro_total_centro_costo .= " rgn.idtrabajador = '".$field_trabajador['idtrabajador']."'";
			else $filtro_total_centro_costo .= " OR rgn.idtrabajador = '".$field_trabajador['idtrabajador']."'";
			
			//	si cambia de centro de costo imprimo el resumen por centro de costo
			if ($agrupa_centro != $field_trabajador['codcentro']) {
				$agrupa_centro = $field_trabajador['codcentro'];
				//	---------------------------------------
				$titulo_centro = $tcodcentro_costo.' '.$tnomcentro_costo;
				$linea = $pdf->GetY(); 
					if ($linea > 250) {
						//nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
						//if ($unidad != "") $unidad = "UNIDAD FUNCIONAL: ".$unidad;
						if ($titulo_centro != "") $centro = "CENTRO DE COSTO: ".$titulo_centro;
						$pdf->Ln(10);
						//if ($unidad != "") $pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'L');
						$pdf->SetFont('Arial', 'B', 10);
						if ($centro != "") $pdf->Cell(205, 5, utf8_decode($centro), 0, 1, 'L');
					}else{
						if($van_centros==3){
							nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro, $flagunidad, $flagcentro, $nomunidad, $nomcentro);
							$van_centros=1;
						}
						$pdf->SetFont('Arial', 'B', 10);
						//if ($unidad != "") $unidad = "UNIDAD FUNCIONAL: ".$unidad;
						if ($titulo_centro != "") $centro = "CENTRO DE COSTO: ".$titulo_centro;
						$pdf->Ln(10);
						//if ($unidad != "") $pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'L');
						if ($centro != "") $pdf->Cell(205, 5, utf8_decode($centro), 0, 1, 'L');
						$van_centros++;
					}
	
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
								SUM(rgn.cantidad) AS cantidad,
								cn.descripcion
							FROM
								relacion_generar_nomina rgn
								INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
							WHERE
								rgn.total > 0 AND
								rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
								rgn.tabla = 'conceptos_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)
					
							UNION
							
							(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								SUM(rgn.cantidad) AS cantidad,
								cn.descripcion
							FROM
								relacion_generar_nomina rgn
								INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
							WHERE
								rgn.total > 0 AND
								rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
								rgn.tabla = 'constantes_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)";
				$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
					$pdf->SetXY(5, $ya);
					$pdf->SetFont('Arial', '', 7);
				
					if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
					else $cantidad = "";
					$cantidad = "";
					$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion'].' '.$cantidad), 0, 0, 'L');
                    $pdf->SetFont('Arial', '', 8);
					$pdf->Cell(19, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
					$ya+=4;
					/*$linea = $pdf->GetY(); if ($linea > 250) {
						//nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
						//if ($unidad != "") $unidad = "UNIDAD FUNCIONAL: ".$unidad;
						if ($titulo_centro != "") $centro = "CENTRO DE COSTO: ".$titulo_centro;
						$pdf->Ln(5);
						//if ($unidad != "") $pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'L');
						if ($centro != "") $pdf->Cell(205, 5, utf8_decode($centro), 0, 1, 'L');
					}*/
					$total_asignaciones+=$field_asignaciones['total'];
				} $cantidad = "";
				
				//	Imprimo las deducciones del trabajador
				$sql = "(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								SUM(rgn.cantidad) AS cantidad,
								cn.descripcion
							FROM
								relacion_generar_nomina rgn
								INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
							WHERE
								rgn.total > 0 AND
								rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
								rgn.tabla = 'conceptos_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)
					
							UNION
							
							(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								SUM(rgn.cantidad) AS cantidad,
								cn.descripcion
							FROM
								relacion_generar_nomina rgn
								INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
							WHERE
								rgn.total > 0 AND
								rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
								rgn.tabla = 'constantes_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)";
				$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
					$pdf->SetXY(108, $yd);
					$pdf->SetFont('Arial', '', 7);
				
					if ($field_deducciones['cantidad'] > 0) $cantidad = "(".$field_deducciones['cantidad'].")";
					else $cantidad = "";
					$cantidad = "";
					$pdf->Cell(79, 5, utf8_decode($field_deducciones['descripcion'].' '.$cantidad), 0, 0, 'L');
                    $pdf->SetFont('Arial', '', 8);
					$pdf->Cell(19, 5, number_format($field_deducciones['total'], 2, ',', '.'), 0, 0, 'R');
					$yd+=4;
					/*$linea = $pdf->GetY(); if ($linea > 250) {
						//nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
						//if ($unidad != "") $unidad = "UNIDAD FUNCIONAL: ".$unidad;
						if ($titulo_centro != "") $centro = "CENTRO DE COSTO: ".$titulo_centro;
						$pdf->Ln(5);
						//if ($unidad != "") $pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'L');
						if ($centro != "") $pdf->Cell(205, 5, utf8_decode($centro), 0, 1, 'L');
					}*/	
					$total_deducciones+=$field_deducciones['total'];
				} $cantidad = "";
				$pdf->Ln(6);
				
				/*$linea = $pdf->GetY(); if ($linea > 250) {
						//nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
						//if ($unidad != "") $unidad = "UNIDAD FUNCIONAL: ".$unidad;
						if ($titulo_centro != "") $centro = "CENTRO DE COSTO2: ".$titulo_centro;
						$pdf->Ln(5);
						//if ($unidad != "") $pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'L');
						if ($centro != "") $pdf->Cell(205, 5, utf8_decode($centro), 0, 1, 'L');
					}	
				*/
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
		
				$pdf->Ln(4);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
				$y = $pdf->GetY();
				$pdf->Rect(5, $y, 200, 0.1);
				$pdf->Ln(2);
				$pdf->Cell(79, 5, 'NUMERO TOTAL DE TRABAJADORES DEL CENTRO DE COSTO: '.$nro_trabajadores_centro, 0, 0, 'L');
				
				
				$pdf->Ln(4);
				
				
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
				$y = $pdf->GetY();
				$pdf->Rect(5, $y, 200, 0.1);
				$pdf->Ln(2);
				
				$pdf->SetFont('Arial', 'U', 8);
				$pdf->Cell(100, 5, 'APORTES', 0, 0, 'C');
				
				$pdf->Ln(4);
				$y = $pdf->GetY(); $ya = $y; $yd = $y;
			
				$total_aporte = 0;
				$total_deducciones = 0;
				if ($filtro_centro_costo != "") $filtro_cc = " AND ($filtro_centro_costo)";
				//	Imprimo los aportes del trabajador
				$sql = "(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								SUM(rgn.cantidad) AS cantidad,
								cn.descripcion
							FROM
								relacion_generar_nomina rgn
								INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND tcn.afecta = 'aporte')
							WHERE
								rgn.total > 0 AND
								rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
								rgn.tabla = 'conceptos_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)
					
							UNION
							
							(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								SUM(rgn.cantidad) AS cantidad,
								cn.descripcion
							FROM
								relacion_generar_nomina rgn
								INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'aporte')
							WHERE
								rgn.total > 0 AND
								rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
								rgn.tabla = 'constantes_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)";
				$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
					$pdf->SetXY(5, $ya);
					$pdf->SetFont('Arial', '', 7);
				
					if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
					else $cantidad = "";
					$cantidad = "";
					$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion'].' '.$cantidad), 0, 0, 'L');
                    $pdf->SetFont('Arial', '', 8);
					$pdf->Cell(79, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
					$ya+=4;
					/*$linea = $pdf->GetY(); if ($linea > 250) {
						//nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
						//if ($unidad != "") $unidad = "UNIDAD FUNCIONAL: ".$unidad;
						if ($titulo_centro != "") $centro = "CENTRO DE COSTO: ".$titulo_centro;
						$pdf->Ln(5);
						//if ($unidad != "") $pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'L');
						if ($centro != "") $pdf->Cell(205, 5, utf8_decode($centro), 0, 1, 'L');
					}*/	
					$total_aporte+=$field_asignaciones['total'];
				} $cantidad = "";
				
				$pdf->Rect(140, $ya+1, 24, 0.1);
				
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetXY(5, $ya+3);
				$pdf->Cell(79, 5, 'TOTAL APORTES', 0, 0, 'L');
				$pdf->Cell(79, 5, number_format($total_aporte, 2, ',', '.'), 0, 0, 'R');

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
		$linea = $pdf->GetY(); if ($linea > 250) {
			nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro, $flagunidad, $flagcentro, $nomunidad, $nomcentro);
			//if ($unidad != "") $unidad = "UNIDAD FUNCIONAL: ".$unidad;
			if ($titulo_centro != "") $centro = "CENTRO DE COSTO: ".$titulo_centro;
			$pdf->Ln(10);
			//if ($unidad != "") $pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'L');
			if ($centro != "") $pdf->Cell(205, 5, utf8_decode($centro), 0, 1, 'L');
		}else{
			if ($van_centros==3){
				nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro, $flagunidad, $flagcentro, $nomunidad, $nomcentro);
			}
			$pdf->SetFont('Arial', 'B', 10);
			if ($titulo_centro != "") $centro = "CENTRO DE COSTO: ".$titulo_centro;
			$pdf->Ln(10);
			//if ($unidad != "") $pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'L');
			if ($centro != "") $pdf->Cell(205, 5, utf8_decode($centro), 0, 1, 'L');
		}
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
						SUM(rgn.cantidad) AS cantidad,
						cn.descripcion
					FROM
						relacion_generar_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn.tabla = 'conceptos_nomina'
						$filtro_cc
					GROUP BY rgn.idconcepto)
			
					UNION
					
					(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						SUM(rgn.cantidad) AS cantidad,
						cn.descripcion
					FROM
						relacion_generar_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn.tabla = 'constantes_nomina'
						$filtro_cc
					GROUP BY rgn.idconcepto)";
		$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
			$pdf->SetXY(5, $ya);
			$pdf->SetFont('Arial', '', 7);
				
			if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
			else $cantidad = "";
			$cantidad = "";	
			$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion'].' '.$cantidad), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
			$pdf->Cell(19, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
			$ya+=4;
			/*$linea = $pdf->GetY(); if ($linea > 250) {
						//nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
						//if ($unidad != "") $unidad = "UNIDAD FUNCIONAL: ".$unidad;
						if ($titulo_centro != "") $centro = "CENTRO DE COSTO: ".$titulo_centro;
						$pdf->Ln(5);
						//if ($unidad != "") $pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'L');
						if ($centro != "") $pdf->Cell(205, 5, utf8_decode($centro), 0, 1, 'L');
					}*/
			$total_asignaciones+=$field_asignaciones['total'];
		}
		
		//	Imprimo las deducciones del trabajador
		$sql = "(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						SUM(rgn.cantidad) AS cantidad,
						cn.descripcion
					FROM
						relacion_generar_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
					WHERE
						rgn.total > 0 AND
						rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn.tabla = 'conceptos_nomina'
						$filtro_cc
					GROUP BY rgn.idconcepto)
			
					UNION
					
					(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						SUM(rgn.cantidad) AS cantidad,
						cn.descripcion
					FROM
						relacion_generar_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
					WHERE
						rgn.total > 0 AND
						rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn.tabla = 'constantes_nomina'
						$filtro_cc
					GROUP BY rgn.idconcepto)";
		$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
			$pdf->SetXY(108, $yd);
			$pdf->SetFont('Arial', '', 7);
				
			if ($field_deducciones['cantidad'] > 0) $cantidad = "(".$field_deducciones['cantidad'].")";
			else $cantidad = "";
			$cantidad = "";	
			$pdf->Cell(79, 5, utf8_decode($field_deducciones['descripcion'].' '.$cantidad), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
			$pdf->Cell(19, 5, number_format($field_deducciones['total'], 2, ',', '.'), 0, 0, 'R');
			$yd+=4;
			/*$linea = $pdf->GetY(); if ($linea > 250) {
						//nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
						//if ($unidad != "") $unidad = "UNIDAD FUNCIONAL: ".$unidad;
						if ($titulo_centro != "") $centro = "CENTRO DE COSTO: ".$titulo_centro;
						$pdf->Ln(5);
						//if ($unidad != "") $pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'L');
						if ($centro != "") $pdf->Cell(205, 5, utf8_decode($centro), 0, 1, 'L');
					}*/
			$total_deducciones+=$field_deducciones['total'];
		}
		
		$pdf->Ln(6);
		
		$linea = $pdf->GetY(); if ($linea > 250) {
						//nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
						//if ($unidad != "") $unidad = "UNIDAD FUNCIONAL: ".$unidad;
						if ($titulo_centro != "") $centro = "CENTRO DE COSTO: ".$titulo_centro;
						$pdf->Ln(5);
						//if ($unidad != "") $pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'L');
						if ($centro != "") $pdf->Cell(205, 5, utf8_decode($centro), 0, 1, 'L');
					}	
		
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
		
		
		
		
		$pdf->Ln(6);
				
				
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
				$y = $pdf->GetY();
				$pdf->Rect(5, $y, 200, 0.1);
				$pdf->Ln(2);
				
				$pdf->SetFont('Arial', 'U', 8);
				$pdf->Cell(100, 5, 'APORTES', 0, 0, 'C');
				
				$pdf->Ln(6);
				$y = $pdf->GetY(); $ya = $y; $yd = $y;
			
				$total_aporte = 0;
				$total_deducciones = 0;
				if ($filtro_centro_costo != "") $filtro_cc = " AND ($filtro_centro_costo)";
				//	Imprimo los aportes del trabajador
				$sql = "(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								SUM(rgn.cantidad) AS cantidad,
								cn.descripcion
							FROM
								relacion_generar_nomina rgn
								INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND tcn.afecta = 'aporte')
							WHERE
								rgn.total > 0 AND
								rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
								rgn.tabla = 'conceptos_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)
					
							UNION
							
							(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								SUM(rgn.cantidad) AS cantidad,
								cn.descripcion
							FROM
								relacion_generar_nomina rgn
								INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'aporte')
							WHERE
								rgn.total > 0 AND
								rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
								rgn.tabla = 'constantes_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)";
				$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
					$pdf->SetXY(5, $ya);
					$pdf->SetFont('Arial', '', 8);
				
					if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
					else $cantidad = "";
					$cantidad = "";
					$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion'].' '.$cantidad), 0, 0, 'L');
					$pdf->Cell(79, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
					$ya+=4;
					/*$linea = $pdf->GetY(); if ($linea > 250) {
						//nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
						//if ($unidad != "") $unidad = "UNIDAD FUNCIONAL: ".$unidad;
						if ($titulo_centro != "") $centro = "CENTRO DE COSTO: ".$titulo_centro;
						$pdf->Ln(5);
						//if ($unidad != "") $pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'L');
						if ($centro != "") $pdf->Cell(205, 5, utf8_decode($centro), 0, 1, 'L');
					}*/
					$total_aporte+=$field_asignaciones['total'];
				} $cantidad = "";
				
				$pdf->Rect(140, $ya+1, 24, 0.1);
				
				
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetXY(5, $ya+3);
				$pdf->Cell(79, 5, 'TOTAL APORTES', 0, 0, 'L');
				$pdf->Cell(79, 5, number_format($total_aporte, 2, ',', '.'), 0, 0, 'R');
		
		
		//	imprimo el resumen general de la nomina
		//	---------------------------------------
		if ($flagunidad == "S" && $unidad != "0") $filtro_unidad = " AND mp.idunidad_funcional = '".$unidad."'"; else $filtro_unidad = "";
		if ($flagcentro == "S" && $centro != "0") $filtro_centro = " AND no2.idcategoria_programatica = '".$centro."'";
		//	---------------------------------------
		nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro, $flagunidad, $flagcentro, $nomunidad, $nomcentro);
		//if ($unidad != "") $unidad = "UNIDAD FUNCIONAL: ".$unidad;
		//if ($centro != "") $centro = "CENTRO DE COSTO: ".$centro;
		$pdf->Ln(10);
		//if ($unidad != "") $pdf->Cell(205, 5, utf8_decode($unidad), 0, 1, 'L');
		//if ($centro != "") $pdf->Cell(205, 5, utf8_decode($centro), 0, 1, 'L');
					
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
						SUM(rgn.cantidad) AS cantidad,
						cn.descripcion
					FROM
						relacion_generar_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn.tabla = 'conceptos_nomina'
						$filtro_tcc
					GROUP BY rgn.idconcepto)
			
					UNION
					
					(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						SUM(rgn.cantidad) AS cantidad,
						cn.descripcion
					FROM
						relacion_generar_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
					WHERE
						rgn.total > 0 AND
						rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn.tabla = 'constantes_nomina'
						$filtro_tcc
					GROUP BY rgn.idconcepto)";
		$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
			$pdf->SetXY(5, $ya);
			$pdf->SetFont('Arial', '', 7);
				
			if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
			else $cantidad = "";
			$cantidad = "";	
			$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion'].' '.$cantidad), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
			$pdf->Cell(19, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
			$ya+=4;
			//$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
			$total_asignaciones+=$field_asignaciones['total'];
		}
		
		//	Imprimo las deducciones del trabajador
		$sql = "(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						SUM(rgn.cantidad) AS cantidad,
						cn.descripcion
					FROM
						relacion_generar_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
					WHERE
						rgn.total > 0 AND
						rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn.tabla = 'conceptos_nomina'
						$filtro_tcc
					GROUP BY rgn.idconcepto)
			
					UNION
					
					(SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						SUM(rgn.cantidad) AS cantidad,
						cn.descripcion
					FROM
						relacion_generar_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
					WHERE
						rgn.total > 0 AND
						rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
						rgn.tabla = 'constantes_nomina'
						$filtro_tcc
					GROUP BY rgn.idconcepto)";
		$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
			$pdf->SetXY(108, $yd);
			$pdf->SetFont('Arial', '', 7);
				
			if ($field_deducciones['cantidad'] > 0) $cantidad = "(".$field_deducciones['cantidad'].")";
			else $cantidad = "";
			$cantidad = "";	
			$pdf->Cell(79, 5, utf8_decode($field_deducciones['descripcion'].' '.$cantidad), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
			$pdf->Cell(19, 5, number_format($field_deducciones['total'], 2, ',', '.'), 0, 0, 'R');
			$yd+=4;
			//$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
			$total_deducciones+=$field_deducciones['total'];
		}
		
		$pdf->Ln(8);
		
		//$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
		
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
		
		
		
		
		
		
		
		$pdf->Ln(8);
				
				
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
				$y = $pdf->GetY();
				$pdf->Rect(5, $y, 200, 0.1);
				$pdf->Ln(2);
				
				$pdf->SetFont('Arial', 'U', 8);
				$pdf->Cell(100, 5, 'APORTES', 0, 0, 'C');
				
				$pdf->Ln(6);
				$y = $pdf->GetY(); $ya = $y; $yd = $y;
			
				$total_aporte = 0;
				$total_deducciones = 0;
				if ($filtro_centro_costo != "") $filtro_cc = " AND ($filtro_total_centro_costo)";
				//	Imprimo los aportes del trabajador
				$sql = "(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								SUM(rgn.cantidad) AS cantidad,
								cn.descripcion
							FROM
								relacion_generar_nomina rgn
								INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND tcn.afecta = 'aporte')
							WHERE
								rgn.total > 0 AND
								rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
								rgn.tabla = 'conceptos_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)
					
							UNION
							
							(SELECT
								rgn.tabla,
								SUM(rgn.total) AS total,
								SUM(rgn.cantidad) AS cantidad,
								cn.descripcion
							FROM
								relacion_generar_nomina rgn
								INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'aporte')
							WHERE
								rgn.total > 0 AND
								rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
								rgn.tabla = 'constantes_nomina'
								$filtro_cc
							GROUP BY rgn.idconcepto)";
				$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
					$pdf->SetXY(5, $ya);
					$pdf->SetFont('Arial', '', 8);
				
					if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
					else $cantidad = "";
					$cantidad = "";
					$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion'].' '.$cantidad), 0, 0, 'L');
					$pdf->Cell(79, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
					$ya+=4;
					//$linea = $pdf->GetY(); if ($linea > 250) nomina_resumen_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, "", $titulo_centro);
					$total_aporte+=$field_asignaciones['total'];
				} $cantidad = "";
				
				$pdf->Rect(140, $ya+1, 24, 0.1);
				
				
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetXY(5, $ya+3);
				$pdf->Cell(79, 5, 'TOTAL APORTES', 0, 0, 'L');
				$pdf->Cell(79, 5, number_format($total_aporte, 2, ',', '.'), 0, 0, 'R');
		
		
		
		
		break;
		
	//	Detalle de Conceptos...
	case "nomina_detalle_conceptos":
		$pdf=new PDF_MC_Table8('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(12);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		//	---------------------------------------
		list($tabla, $concepto)=SPLIT( '[|]', $concepto);
		$sql_consulta = mysql_query("select * from ".$tabla." where id".$tabla." = '".$concepto."'")or die(mysql_error());
		$bus_consulta = mysql_fetch_array($sql_consulta);
		//	---------------------------------------
		//	Obtengo el id de la nomina generada....
		$sql = "SELECT idgenerar_nomina FROM generar_nomina 
							WHERE idtipo_nomina = '".$nomina."' AND idperiodo = '".$periodo."' and estado != 'Anulado'";
		$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);
		//	---------------------------------------
		$query_nivel = ''; $titulo_nivel = '';
		if ($flagunidad == "S" && $unidad != "0") {
			$filtro_cab = "SELECT idniveles_organizacionales as id, codigo, denominacion FROM niveles_organizacionales WHERE idniveles_organizacionales = '".$unidad."'";
			$query_nivel = mysql_query($filtro_cab) or die ($filtro_cab.mysql_error());
		}
		if ($flagcentro == "S" && $centro != "0") { 
			$filtro_cab = "SELECT no.idcategoria_programatica as id, cp.codigo, ue.denominacion
																		FROM
																			categoria_programatica cp
																				INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
																				INNER JOIN niveles_organizacionales no ON (cp.idcategoria_programatica = no.idcategoria_programatica)
																	WHERE cp.idcategoria_programatica = '".$centro."'";
			$query_nivel = mysql_query($filtro_cab) or die ($filtro_cab.mysql_error());
		}
		//$query_nivel = mysql_query($filtro_cab) or die ($filtro_cab.mysql_error());
		if($query_nivel != ''){
			if (mysql_num_rows($query_nivel) != 0) {
				$field_nivel = mysql_fetch_array($query_nivel);
				$titulo_nivel = $field_nivel['codigo']." ".$field_nivel['denominacion'];
			}
		}
		//	---------------------------------------
		//	Obtengo los datos de la cabecera....
		$sql = "SELECT 
					tn.titulo_nomina,
					rpn.desde,
					rpn.hasta
				FROM 
					tipo_nomina tn
					INNER JOIN periodos_nomina pn ON (tn.idtipo_nomina = pn.idtipo_nomina)
					INNER JOIN generar_nomina gn ON (gn.idtipo_nomina = tn.idtipo_nomina AND idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."')
					INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina AND rpn.idrango_periodo_nomina = '".$periodo."')
				WHERE 
					tn.idtipo_nomina = '".$nomina."'";
		$query_titulo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_titulo) != 0) $field_titulo = mysql_fetch_array($query_titulo);
		list($a, $m, $d)=SPLIT( '[/.-]', $field_titulo['desde']); $desde = $d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $field_titulo['hasta']); $hasta = $d."/".$m."/".$a;
		$periodo = "Del $desde al $hasta";
		//	---------------------------------------
		nomina_detalle_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, $bus_consulta['descripcion'], $titulo_nivel);
		//	---------------------------------------
		if ($flagunidad == "S" && $unidad != "0") $filtro_unidad = " AND t.idunidad_funcional = '".$unidad."'";
		if ($flagcentro == "S" && $centro != "0") $filtro_centro = " AND no2.idcategoria_programatica = '".$centro."'";
		//	---------------------------------------
		//	Obtengo los datos de los trabajadores que pertenecen a esta nomina generada...
		

		$sql = "SELECT
					rgn.idtrabajador,
					t.cedula,
					t.apellidos,
					t.nombres,
					rgn.total
				FROM
					relacion_generar_nomina rgn
					INNER JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					INNER JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
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
													fecha_movimiento <= '".$field_titulo['hasta']."') AND
					rgn.idconcepto = '".$concepto."' AND
					rgn.tabla = '".$tabla."'
					AND rgn.total != 0
					$filtro_unidad
					$filtro_centro
				GROUP BY rgn.idtrabajador
				ORDER BY length(cedula), cedula";





		$query_trabajador = mysql_query($sql) or die ($sql.mysql_error());
		$x=1;
		while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 10);
			$pdf->Row(array($x, number_format($field_trabajador['cedula'], 0, '', '.'), utf8_decode($field_trabajador['nombres'].' '.$field_trabajador['apellidos']), number_format($field_trabajador['total'], 2, ',', '.')));
			
			$linea = $pdf->GetY(); if ($linea > 250) nomina_detalle_conceptos($pdf, $field_titulo['titulo_nomina'], $periodo, $field_titulo['concepto'], $titulo_nivel);
			$x++;
			$total += $field_trabajador['total'];
		}
		$pdf->Ln(1);
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Row(array('','', '', number_format($total, 2, ',', '.')));
		break;
		
	//	Relacion Anual por Trabajador...
	case "nomina_resumen_anual_trabajador":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		//	---------------------------------------
		if ($idtrabajador != "") $filtro = " AND t.idtrabajador = '".$idtrabajador."'";
		if ($nomina != "0") $filtro .= " AND rtnt.idtipo_nomina = '".$nomina."'";
		if ($centro != "0") $filtro_centro .= " AND t.centro_costo = '".$centro."'";
		//	---------------------------------------
		//	Obtengo los datos de los trabajadores que pertenecen a esta nomina generada...
		$sql = "SELECT
					rgn.idtrabajador,
					t.cedula,
					t.apellidos,
					t.nombres,
					t.fecha_ingreso,
					c.denominacion AS nomcargo,
					c.grado,
					c.paso,
					ue.denominacion AS nomcentro_costo,
					tn.titulo_nomina,
					cp.idcategoria_programatica,
					cp.codigo AS codcentro_costo,
					ue.idunidad_ejecutora,
					no.denominacion AS nomunidad,
					no.codigo AS codunidad
				FROM
					relacion_generar_nomina rgn
					INNER JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					LEFT JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador AND mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal) FROM movimientos_personal WHERE idtrabajador = t.idtrabajador))
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					INNER JOIN generar_nomina gn ON (rgn.idgenerar_nomina = gn.idgenerar_nomina)
					INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina AND rpn.hasta LIKE '".$anio_fiscal."%')
					INNER JOIN relacion_tipo_nomina_trabajador rtnt ON (t.idtrabajador = rtnt.idtrabajador)
					INNER JOIN tipo_nomina tn ON (rtnt.idtipo_nomina = tn.idtipo_nomina)
					LEFT JOIN niveles_organizacionales norg ON (t.centro_costo = norg.idniveles_organizacionales $filtro_centro)
					LEFT JOIN categoria_programatica cp ON (norg.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					LEFT JOIN niveles_organizacionales no ON (t.idunidad_funcional = no.idniveles_organizacionales)
				WHERE 1 $filtro
				GROUP BY rgn.idtrabajador";
		$query_trabajador = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
			list($a, $m, $d)=SPLIT( '[/.-]', $field_trabajador['fecha_ingreso']); $fingreso = $d."/".$m."/".$a;
			
			nomina_resumen_anual_trabajador($pdf, $anio_fiscal, "", $field_trabajador['titulo_nomina']);
			
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y = $pdf->GetY();
			$pdf->Rect(5, $y, 200, 0.1);
			$pdf->Ln(2);
			
			//	Imprimo los datos del trabajador
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(50, 5, utf8_decode('Cédula de Identidad: '), 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(52, 5, number_format($field_trabajador['cedula'], 0, '', '.'), 0, 0, 'L');
			$pdf->Ln(5);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(50, 5, utf8_decode('Apellidos y Nombres: '), 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(52, 5, utf8_decode($field_trabajador['nombres'].' '.$field_trabajador['apellidos']), 0, 0, 'L');
			$pdf->Ln(5);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(50, 5, 'Fecha Ingreso: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(52, 5, $fingreso, 0, 0, 'L');
			$pdf->Ln(5);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(50, 5, utf8_decode('Cargo: '), 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(52, 5, utf8_decode($field_trabajador['nomcargo']), 0, 0, 'L');
			$pdf->Ln(5);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(50, 5, utf8_decode('Unidad Funcional: '), 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(52, 5, utf8_decode($field_trabajador['codunidad'].' '.$field_trabajador['nomunidad']), 0, 0, 'L');
			$pdf->Ln(5);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(50, 5, utf8_decode('Centro de Costo: '), 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(52, 5, utf8_decode($field_trabajador['codcentro_costo'].' '.$field_trabajador['nomcentro_costo']), 0, 0, 'L');
			$pdf->Ln(5);
			$pdf->SetFont('Arial', '', 8);
			
			$x=165;
			$sql = "SELECT 
						cn.descripcion,
						rct.valor
					FROM
						constantes_nomina cn
						INNER JOIN relacion_concepto_trabajador rct ON (cn.idconstantes_nomina = rct.idconcepto AND tabla = 'constantes_nomina')
					WHERE 
						cn.mostrar = 'si' AND
						rct.idtrabajador = '".$field_trabajador['idtrabajador']."'";
			$query_constantes = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_constantes=mysql_fetch_array($query_constantes)) {
				//if ($x>200) { $pdf->Ln(); $x=5; }
				//$pdf->SetX($x); 
				$pdf->Cell(50, 5, utf8_decode($field_constantes['descripcion']).': '.number_format($field_constantes['valor'], 2, ',', '.'), 0, 0, 'L');
				//$x+=70;
			}
			
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
			$y = $pdf->GetY();
			$pdf->Rect(5, $y, 200, 0.1);
			$pdf->Ln(4);
			
			$pdf->SetFont('Arial', 'U', 8);
			$pdf->Cell(100, 5, 'ASIGNACIONES', 0, 0, 'C');
			$pdf->Cell(100, 5, 'DEDUCCIONES', 0, 1, 'C');
			$pdf->Ln(2);
			$y = $pdf->GetY(); $ya = $y; $yd = $y;
			
			//	Imprimo las asignaciones del trabajador			
			$sql = "(SELECT
						rgn.tabla,
						SUM(rgn.total) As total,
						cn.descripcion,
						rgn.idconcepto
					FROM
						relacion_generar_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN generar_nomina gn ON (rgn.idgenerar_nomina = gn.idgenerar_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
					WHERE
						rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' 
						AND rgn.tabla = 'conceptos_nomina' 
						AND gn.estado <> 'Anulado'
						
					GROUP BY idconcepto)
			
					UNION
					
					(SELECT
						rgn.tabla,
						rgn.total,
						cn.descripcion,
						rgn.idconcepto
					FROM
						relacion_generar_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN generar_nomina gn ON (rgn.idgenerar_nomina = gn.idgenerar_nomina)
						INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina AND rpn.hasta LIKE '".$anio_fiscal."%')
					WHERE
						rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
						rgn.tabla = 'constantes_nomina' AND
						cn.mostrar = 'si'
						AND gn.estado <> 'Anulado'
					GROUP BY idconcepto)";
			$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
			$mostrar=1;
			while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
				if ($nomina != "0"){
					$sql_validar = mysql_query("select * from relacion_concepto_trabajador 
						where idtrabajador = '".$field_trabajador['idtrabajador']."'
								and idtipo_nomina = '".$nomina."'
								and idconcepto = '".$field_asignaciones["idconcepto"]."'")or die(mysql_error());
					$existe = mysql_num_rows($sql_validar);
					if ($existe>=1) {$mostrar=1;}else{$mostrar=0;}
				}
				if ($mostrar == 1){
					//$sql_valido_concepto = mysql_query();
					$pdf->SetXY(5, $ya);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Cell(79, 5, utf8_decode($field_asignaciones['descripcion']), 0, 0, 'L');
					$pdf->Cell(19, 5, number_format($field_asignaciones['total'], 2, ',', '.'), 0, 0, 'R');
					$ya+=4;
					$linea = $pdf->GetY(); if ($linea > 200) nomina_resumen_anual_trabajador($pdf, $anio_fiscal, $field_trabajador['nomcentro_costo'], $field_trabajador['titulo_nomina']);
					$total_asignaciones+=$field_asignaciones['total'];
				}
			}
			
			//	Imprimo las deducciones del trabajador			
			$sql = "SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion,
						rgn.idconcepto
					FROM
						relacion_generar_nomina rgn
						LEFT JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
						INNER JOIN generar_nomina gn ON (rgn.idgenerar_nomina = gn.idgenerar_nomina)
						INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina AND rpn.hasta LIKE '".$anio_fiscal."-%')
						
					WHERE
						rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' 
						AND	rgn.tabla = 'conceptos_nomina'
						
					GROUP BY idconcepto";
			$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
			$mostrar=1;
			while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
				if ($nomina != "0"){
					$sql_validar = mysql_query("select * from relacion_concepto_trabajador 
						where idtrabajador = '".$field_trabajador['idtrabajador']."'
								and idtipo_nomina = '".$nomina."'
								and idconcepto = '".$field_deducciones["idconcepto"]."'")or die(mysql_error());
					$existe = mysql_num_rows($sql_validar);
					if ($existe>=1) {$mostrar=1;}else{$mostrar=0;}
				}
				if ($mostrar == 1){
					$pdf->SetXY(108, $yd);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Cell(79, 5, utf8_decode($field_deducciones['descripcion']), 0, 0, 'L');
					$pdf->Cell(19, 5, number_format($field_deducciones['total'], 2, ',', '.'), 0, 0, 'R');
					$yd+=4;
					$linea = $pdf->GetY(); if ($linea > 200) nomina_resumen_anual_trabajador($pdf, $anio_fiscal, $field_trabajador['nomcentro_costo'], $field_trabajador['titulo_nomina']);
					$total_deducciones+=$field_deducciones['total'];
				}
			}
			
			$pdf->Ln(8);
			
			$linea = $pdf->GetY(); if ($linea > 200) nomina_resumen_anual_trabajador($pdf, $anio_fiscal, $field_trabajador['nomcentro_costo'], $field_trabajador['titulo_nomina']);
			
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
			
			$total_asignaciones = 0;
			$total_deducciones = 0;
		}
		break;
		
	//	Lista de Constantes de Nomina...
	case "nomina_constantes_lista":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(10);
		$pdf->SetLeftMargin(10);
		$pdf->SetAutoPageBreak(1, 5);
		$pdf->Open();
		//	---------------------------------------
		nomina_constantes_lista($pdf);
		//	---------------------------------------		
		//	Imprimo el cuerpo del reporte
		$sql = "SELECT
					cn.*,
					cp.codigo_cuenta,
					o.codigo AS ordinal
				FROM
					constantes_nomina cn
					INNER JOIN clasificador_presupuestario cp ON (cn.idclasificador_presupuestario=cp.idclasificador_presupuestario)
					INNER JOIN ordinal o ON (cn.idordinal=o.idordinal)";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$pdf->Row(array($field['codigo_cuenta'], $field['ordinal'], $field['codigo'], $field['abreviatura'], utf8_decode($field['descripcion'])));
			
			$linea = $pdf->GetY(); if ($linea > 200) nomina_constantes_lista($pdf);
		}
		break;
		
	//	Lista de Tipos de Conceptos...
	case "nomina_tipos_conceptos_lista":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(10);
		$pdf->SetLeftMargin(10);
		$pdf->SetAutoPageBreak(1, 5);
		$pdf->Open();
		//	---------------------------------------
		nomina_tipos_conceptos_lista($pdf);
		//	---------------------------------------		
		//	Imprimo el cuerpo del reporte
		$sql = "SELECT
					tcn.*
				FROM
					tipo_conceptos_nomina tcn";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$pdf->Cell(25, 5);  $pdf->Row(array($field['codigo'], utf8_decode($field['descripcion']), utf8_decode($field['afecta'])));
			
			$linea = $pdf->GetY(); if ($linea > 200) nomina_tipos_conceptos_lista($pdf);
		}
		break;
		
	//	Tabla de Constantes...
	case "nomina_tabla_constantes":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(10);
		$pdf->SetLeftMargin(10);
		$pdf->SetAutoPageBreak(1, 5);
		$pdf->Open();
		//	---------------------------------------
		//	obtengo los datos de cabecera
		$sql = "SELECT
					tc.*
				FROM
					tabla_constantes tc
				WHERE
					tc.idtabla_constantes = '".$idtabla_constantes."'";
		$query_head = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_head) != 0) $field_head = mysql_fetch_array($query_head);
		//	---------------------------------------
		nomina_tabla_constantes($pdf, $field_head);
		//	---------------------------------------		
		//	Imprimo el cuerpo del reporte
		$sql = "SELECT
					rtc.*
				FROM
					rango_tabla_constantes rtc
					INNER JOIN tabla_constantes tc ON (rtc.idtabla_constantes = tc.idtabla_constantes)
				WHERE
					tc.idtabla_constantes = '".$idtabla_constantes."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$i=0;
		while ($field = mysql_fetch_array($query)) {
			$i++;
			$pdf->Cell(55, 5);  $pdf->Row(array($i, $field['desde'], $field['hasta'], number_format($field['valor'], 2, ',', '.')));
			$linea = $pdf->GetY(); if ($linea > 200) nomina_tabla_constantes($pdf);
		}
		break;
		
	//	Tipo de Hoja de Tiempo...
	case "nomina_tipo_hoja_tiempo":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(10);
		$pdf->SetLeftMargin(10);
		$pdf->SetAutoPageBreak(1, 5);
		$pdf->Open();
		//	---------------------------------------
		nomina_tipo_hoja_tiempo($pdf);
		//	---------------------------------------		
		//	Imprimo el cuerpo del reporte
		$sql = "SELECT
					tht.*
				FROM
					tipo_hoja_tiempo tht";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$pdf->Cell(45, 5);  $pdf->Row(array(utf8_decode($field['descripcion']), utf8_decode($field['unidad'])));
			
			$linea = $pdf->GetY(); if ($linea > 200) nomina_tipo_hoja_tiempo($pdf);
		}
		break;
		
	//	Hoja de Tiempo...
	case "nomina_hoja_tiempo":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(10);
		$pdf->SetLeftMargin(10);
		$pdf->SetAutoPageBreak(1, 5);
		$pdf->Open();
		//	---------------------------------------
		//	Imprimo la cabecera del reporte
		$sql = "SELECT
					ht.*,
					tht.descripcion AS nomtipo_hoja_tiempo,
					tn.titulo_nomina,
					no.denominacion AS nomcentro,
					CONCAT(rpn.desde, '  -  ', rpn.hasta) AS nomperiodo
				FROM
					hoja_tiempo ht
					INNER JOIN tipo_hoja_tiempo tht ON (ht.idtipo_hoja_tiempo = tht.idtipo_hoja_tiempo)
					INNER JOIN tipo_nomina tn ON (ht.idtipo_nomina = tn.idtipo_nomina)
					INNER JOIN niveles_organizacionales no ON (ht.centro_costo = no.idniveles_organizacionales)
					INNER JOIN rango_periodo_nomina rpn ON (ht.periodo = rpn.idrango_periodo_nomina)
				WHERE
					ht.idhoja_tiempo = '".$idhoja_tiempo."'";
		$query_head = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_head) != 0) $field_head = mysql_fetch_array($query_head);
		//	---------------------------------------
		nomina_hoja_tiempo($pdf, $field_head);
		//	---------------------------------------		
		//	Imprimo el cuerpo del reporte
		$sql = "SELECT
					rhtt.horas,
					t.apellidos,
					t.nombres,
					t.cedula
				FROM
					relacion_hoja_tiempo_trabajador rhtt
					INNER JOIN trabajador t On (rhtt.idtrabajador = t.idtrabajador)
				WHERE rhtt.idhoja_tiempo = '".$idhoja_tiempo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$pdf->Cell(20, 5); $pdf->Row(array(number_format($field['cedula'], 0, '', '.'), utf8_decode($field['nombres']), utf8_decode($field['apellidos']), $field['horas']));
			$linea = $pdf->GetY(); if ($linea > 200) nomina_tipo_hoja_tiempo($pdf, $field_head);
		}
		break;
		
	//	Tipo de Nóomina...
	case "nomina_tipo_nomina":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(10);
		$pdf->SetLeftMargin(10);
		$pdf->SetAutoPageBreak(1, 5);
		$pdf->Open();
		//	---------------------------------------
		//	Imprimo la cabecera del reporte
		$sql = "SELECT
					tn.titulo_nomina,
					tn.activa,
					tn.tipo_fraccion,
					tn.numero_fracciones,
					td.descripcion as nomtipo_documento
				FROM
					tipo_nomina tn
					INNER JOIN tipos_documentos td ON (tn.idtipo_documento = td.idtipos_documentos)";
		$query_head = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_head) != 0) $field_head = mysql_fetch_array($query_head);
		//	---------------------------------------
		
		//	---------------------------------------
		//	Si selecciono listado....
		//	---------------------------------------
		if ($listado == "true") {
			nomina_tipo_nomina($pdf, $idtipo_nomina, $listado, $ficha, $field_head, '', 'Listado de Tipos de Nómina', '', false, false);
			
			//	Imprimo el cuerpo del reporte
			$sql = "SELECT
						tn.titulo_nomina,
						tn.activa,
						td.descripcion as nomtipo_documento
					FROM
						tipo_nomina tn
						INNER JOIN tipos_documentos td ON (tn.idtipo_documento = td.idtipos_documentos)";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				if ($field['activa'] == "s") $flag="Si"; else $flag="No";
				$pdf->Row(array(utf8_decode($field['titulo_nomina']), utf8_decode($field['motivo_cuenta']), utf8_decode($field['nomtipo_documento']), $flag));
				$linea = $pdf->GetY(); if ($linea > 250) nomina_tipo_nomina($pdf, $idtipo_nomina, $listado, $ficha, $field_head, '', 'Listado de Tipos de Nómina', '', false, false);
			}
		}
		
		//	---------------------------------------
		//	Si selecciono ficha
		//	---------------------------------------
		elseif ($ficha == "true") {
			if ($idtipo_nomina != "") $filtro = "WHERE idtipo_nomina = '".$idtipo_nomina."'";			
			$sql = "SELECT * FROM tipo_nomina $filtro ORDER BY idtipo_nomina";
			$query_tipo_nomina = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_tipo_nomina = mysql_fetch_array($query_tipo_nomina)) {
				
				//	Obtengo el encabezado del periodo del tipo de nomina
				$sql = "SELECT * FROM periodos_nomina WHERE idtipo_nomina = '".$field_tipo_nomina['idtipo_nomina']."'";
				$query_periodo = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_periodo)) {
					$field_periodo = mysql_fetch_array($query_periodo);
					nomina_tipo_nomina($pdf, $field_tipo_nomina['idtipo_nomina'], $listado, $ficha, $field_head, $field_periodo, 'Tipo de Nómina', true, false, false);
					
					$sql = "SELECT * FROM rango_periodo_nomina WHERE idperiodo_nomina = '".$field_periodo['idperiodos_nomina']."'";
					$query_rangos = mysql_query($sql) or die ($sql.mysql_error());
					while ($field_rangos = mysql_fetch_array($query_rangos)) {
						$pdf->Cell(45, 5); $pdf->Row(array($field_rangos['numero'], formatoFecha($field_rangos['desde']), formatoFecha($field_rangos['hasta']), formatoFecha($field_rangos['sugiere_pago'])));
						$linea = $pdf->GetY(); if ($linea > 250) nomina_tipo_nomina($pdf, $field_tipo_nomina['idtipo_nomina'], $listado, $ficha, $field_head, $field_periodo, 'Tipo de Nómina', true, false, false);
					}
				}
				
				//	Obtengo las fracciones del tipo de nomina
				nomina_tipo_nomina($pdf, $idtipo_nomina, $listado, $ficha, $field_head, $field_periodo, 'Tipo de Nómina', false, true, false);
				$sql = "SELECT * FROM rango_fraccion_nomina WHERE idtipo_nomina = '".$field_tipo_nomina['idtipo_nomina']."'";
				$query_fracciones = mysql_query($sql) or die ($sql.mysql_error()); 
				while ($field_fracciones = mysql_fetch_array($query_fracciones)) {
					$pdf->Cell(45, 5); $pdf->Row(array($field_fracciones['numero'], $field_fracciones['valor'], $field_fracciones['']));
					$linea = $pdf->GetY(); if ($linea > 250) nomina_tipo_nomina($pdf, $field_tipo_nomina['idtipo_nomina'], $listado, $ficha, $field_head, $field_periodo, 'Tipo de Nómina', false, true, false);
				}
				
				//	Imprimo las Jornadas del Tipo de Nomina
				nomina_tipo_nomina($pdf, $field_tipo_nomina['idtipo_nomina'], $listado, $ficha, $field_head, $field_periodo, 'Tipo de Nómina', false, false, true);
			}
			
		}
		
		break;
		
	//	Sobregiro de Partidas...
	case "sobregiro_partidas":
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
					rpn.hasta
				FROM 
					tipo_nomina tn
					INNER JOIN periodos_nomina pn ON (tn.idtipo_nomina = pn.idtipo_nomina)
					INNER JOIN generar_nomina gn ON (gn.idtipo_nomina = tn.idtipo_nomina AND idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."')
					INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina AND rpn.idrango_periodo_nomina = '".$periodo."')
				WHERE 
					tn.idtipo_nomina = '".$nomina."'";
		$query_titulo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_titulo) != 0) $field_titulo = mysql_fetch_array($query_titulo);
		list($a, $m, $d)=SPLIT( '[/.-]', $field_titulo['desde']); $desde = $d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $field_titulo['hasta']); $hasta = $d."/".$m."/".$a;
		$periodo = "Del $desde al $hasta";
		//	---------------------------------------
		sobregiro_partidas($pdf, $field_titulo['titulo_nomina'], $periodo);
		//	---------------------------------------
		$sql = "SELECT
					pocs.*,
					CONCAT(p.partida, '.', p.generica, '.', p.especifica, '.', p.sub_especifica) AS codpartida,
					p.denominacion,
					c.codigo AS codcategoria
				FROM
					partidas_orden_compra_servicio pocs
					INNER JOIN maestro_presupuesto mp ON (pocs.idmaestro_presupuesto = mp.idRegistro)
					INNER JOIN clasificador_presupuestario p ON (mp.idclasificador_presupuestario = p.idclasificador_presupuestario)
					INNER JOIN categoria_programatica c ON (mp.idcategoria_programatica = c.idcategoria_programatica)
				WHERE
					pocs.idorden_compra_servicio = '".$id_orden_compra."' AND
					pocs.estado = 'sobregiro'
				ORDER BY codcategoria, codpartida";
		$query_partidas = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_partidas = mysql_fetch_array($query_partidas)) {
			$pdf->Row(array($field_partidas['codcategoria'],
							$field_partidas['codpartida'],
							utf8_decode($field_partidas['denominacion']),
							number_format(consultarDisponibilidad($field_partidas["idmaestro_presupuesto"]),2,',','.'),
							number_format($field_partidas["monto"],2,',','.')));
			
			$linea = $pdf->GetY(); if ($linea > 250) sobregiro_partidas($pdf, $field_titulo['titulo_nomina'], $periodo);
		}
		//	---------------------------------------
		break;
	
	//	Relacion Formato 1312 IVSS
	case "relacion_forma_1312":
		$pdf=new PDF_MC_Table('L', 'mm', 'Legal');
		$pdf->SetTopMargin(2);
		$pdf->SetLeftMargin(2);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();

		// OBTENGO LOS DATOS DEL CONCEPTO SSO
		list($tablasso, $idconceptosso)=SPLIT( '[|]', $idsso);
		$sql_consulta_sso = mysql_query("select * from ".$tablasso." where id".$tablasso." = '".$idconceptosso."'")or die(mysql_error());
		$bus_consulta_sso = mysql_fetch_array($sql_consulta_sso);
		$nomconcepto_sso = $bus_consulta_sso["descripcion"];
		//	---------------------------------------
		// OBTENGO LOS DATOS DEL CONCEPTO APORTES SSO
		list($tabla_aportesso, $idconcepto_aportesso)=SPLIT( '[|]', $idaportesso);
		$sql_consulta_aportesso = mysql_query("select * from ".$tabla_aportesso." where id".$tabla_aportesso." = '".$idconcepto_aportesso."'")or die(mysql_error());
		$bus_consulta_aportesso = mysql_fetch_array($sql_consulta_aportesso);
		$nomconcepto_aportesso = $bus_consulta_aportesso["descripcion"];
		//	---------------------------------------
		// OBTENGO LOS DATOS DEL CONCEPTO REGIMEN PRESTACIONAL DE EMPLEO
		list($tablarpe, $idconceptorpe)=SPLIT( '[|]', $idrpe);
		$sql_consulta_rpe = mysql_query("select * from ".$tablarpe." where id".$tablarpe." = '".$idconceptorpe."'")or die(mysql_error());
		$bus_consulta_rpe = mysql_fetch_array($sql_consulta_rpe);
		$nomconcepto_rpe = $bus_consulta_rpe["descripcion"];
		//	---------------------------------------
		// OBTENGO LOS DATOS DEL CONCEPTO APORTES REGIMEN PRESTACIONAL DE EMPLEO
		list($tabla_aporterpe, $idconcepto_aporterpe)=SPLIT( '[|]', $idaporterpe);
		$sql_consulta_aporterpe = mysql_query("select * from ".$tabla_aporterpe." where id".$tabla_aporterpe." = '".$idconcepto_aporterpe."'")or die(mysql_error());
		$bus_consulta_aporterpe = mysql_fetch_array($sql_consulta_aporterpe);
		$nomconcepto_aporterpe = $bus_consulta_aporterpe["descripcion"];
		//	---------------------------------------
		// OBTENGO LOS DATOS DE LA NOMINA
		$sql_consulta_nomina = mysql_query("select * from tipo_nomina where idtipo_nomina = '".$tipo_nomina."'")or die(mysql_error());
		$bus_consulta_nomina = mysql_fetch_array($sql_consulta_nomina);
		$nom_nomina = $bus_consulta_nomina["titulo_nomina"];
		//	---------------------------------------
		if ($estado == ''){
			$where_estado = "gn.idtipo_nomina = '".$tipo_nomina."'";
		}else{
			$where_estado = "gn.idtipo_nomina = '".$tipo_nomina."' AND gn.estado = '".$estado."'";
		}
		list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fdesde = $d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fhasta = $d."/".$m."/".$a;
		if ($desde != "" && $hasta != "") $periodo = "$fdesde - $fhasta";
		
		relacion_forma_1312($pdf, $fdesde, $fhasta);
		//	-----------------------------------------------------------------------------
		$sql = "SELECT tr.cedula, tr.apellidos, tr.nombres, sum(rgn.total) as total
					  FROM generar_nomina gn

					  INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina)
					  INNER JOIN relacion_generar_nomina rgn ON (gn.idgenerar_nomina = rgn.idgenerar_nomina)
					  INNER JOIN trabajador tr ON (rgn.idtrabajador = tr.idtrabajador)
					WHERE (rpn.desde >= '".$desde."' AND rpn.hasta <= '".$hasta."')
					  AND $where_estado
					  AND rgn.idconcepto = '".$idconcepto."'
					  AND rgn.tabla = '".$tabla."'
					GROUP BY rgn.idtrabajador
					ORDER BY $ordenar
					";

		$query = mysql_query($sql) or die ($sql.mysql_error());			


		$i=0; $sum_total=0;
		while ($field = mysql_fetch_array($query)) {
			$i++;
			$total = number_format($field['total'], 2, ',', '.'); $sum_total += $field['total'];
			$pdf->Row(array($i, number_format($field['cedula'], 0, ',', '.'), utf8_decode($field['apellidos']).' '.utf8_decode($field['nombres']), $total));
			$pdf->Ln(2);
			
			$y = $pdf->GetY(); if ($y > 255) { relacion_anticipo_terceros($pdf, $nomconcepto, $periodo, $nom_nomina, $estado); }
		}
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->Rect(5, $y, 200, 0.1);
		$pdf->Ln(5);
		$sum_total = number_format($sum_total, 2, ',', '.');
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFont('Arial', 'B', 9);
		$pdf->Row(array('', '', '', $sum_total));
		//	---------------------------------------
		break;
	





}
//	----------------------------------------
//	FIN CUERPO DE LOS REPORTES
//	----------------------------------------

$pdf->Output();
?>
