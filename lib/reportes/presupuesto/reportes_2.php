<?php
session_start();
extract($_POST);
extract($_GET);
set_time_limit(-1);
ini_set("memory_limit", "200M");
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
//	----------------------------------------------------
$nom_mes['01']="Enero";
$nom_mes['02']="Febrero";
$nom_mes['03']="Marzo";
$nom_mes['04']="Abril";
$nom_mes['05']="Mayo";
$nom_mes['06']="Junio";
$nom_mes['07']="Julio";
$nom_mes['08']="Agosto";
$nom_mes['09']="Septiembre";
$nom_mes['10']="Octubre";
$nom_mes['11']="Noviembre";
$nom_mes['12']="Diciembre";
//	----------------------------------------------------
$dias_mes['01']=31;
$dias_mes['03']=31;
$dias_mes['04']=30;
$dias_mes['05']=31;
$dias_mes['06']=30;
$dias_mes['07']=31;
$dias_mes['08']=31;
$dias_mes['09']=30;
$dias_mes['10']=31;
$dias_mes['11']=30;
$dias_mes['12']=31;
//	----------------------------------------
Conectarse();
$ahora=date("d-m-Y H:i:s");
//	----------------------------------------

//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Reporte Nuevo...
	case "rendicion_mensual":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//	---------------------------------------------
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//---------------------------------------------
		$m = (int) $mes;
		$m_anterior = $m - 1;
		if ($m_anterior == 0) { $m_anterior = 12; $anio_anterior = $anio_fiscal - 1; } else { $anio_anterior = $anio_fiscal; }		
		if ($m_anterior < 10) $mes_anterior = "0$m_anterior"; else $mes_anterior = $m_anterior;
		if ($anio_anterior % 4 == 0) $dias_mes['02'] = 29; else $dias_mes['02'] = 28;
		$dias_mes_anterior = $dias_mes[$mes_anterior];
		$hasta_anterior = "$anio_fiscal-$mes_anterior-$dias_mes_anterior";
		//---------------------------------------------
		rendicion_mensual($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $nom_mes[$mes]);
		//---------------------------------------------
		if ($idcategoria_programatica=='0') $filtro=""; else $filtro=" AND (maestro_presupuesto.idcategoria_programatica='".$idcategoria_programatica."')";
		//------------------------------------------------
		$sql="(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria, 
					  maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
					  categoria_programatica.codigo AS CodCategoria, 
					  unidad_ejecutora.denominacion AS Unidad, 
					  clasificador_presupuestario.partida AS Par,
					  clasificador_presupuestario.generica AS Gen, 
					  clasificador_presupuestario.especifica AS Esp, 
					  clasificador_presupuestario.sub_especifica AS Sesp,
					  clasificador_presupuestario.denominacion AS NomPartida, 
					  maestro_presupuesto.idRegistro AS IdPresupuesto,
					  SUM(maestro_presupuesto.monto_original) AS Formulado, 
					  SUM(maestro_presupuesto.monto_actual) AS Actual, 
					  SUM(maestro_presupuesto.total_causados) AS Causado, 
					  SUM(maestro_presupuesto.total_pagados) AS Pagado, 
					  SUM(maestro_presupuesto.total_compromisos) AS Compromiso,
					  SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso, 
					  SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir,
				      SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
					  SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion,
					  'especifica' AS Tipo, 
					  ordinal.codigo AS codordinal, 
					  ordinal.denominacion AS nomordinal,
					  (SELECT SUM(partidas_credito_adicional.monto_acreditar)
						FROM partidas_credito_adicional
							WHERE (partidas_credito_adicional.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
								AND (partidas_credito_adicional.idcredito_adicional in 
									(SELECT creditos_adicionales.idcreditos_adicionales 
										FROM creditos_adicionales 
											WHERE creditos_adicionales.fecha_solicitud>='$anio_fiscal-01-01' 
												AND creditos_adicionales.fecha_solicitud<='$hasta_anterior' AND creditos_adicionales.estado='procesado'))) AS MCreditoAnt,
					  (SELECT SUM(partidas_receptoras_traslado.monto_acreditar)
						FROM partidas_receptoras_traslado
							WHERE (partidas_receptoras_traslado.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
								AND (partidas_receptoras_traslado.idtraslados_presupuestarios in 
									(SELECT traslados_presupuestarios.idtraslados_presupuestarios 
										FROM traslados_presupuestarios 
											WHERE traslados_presupuestarios.fecha_solicitud>='$anio_fiscal-01-01' 
												AND traslados_presupuestarios.fecha_solicitud<='$hasta_anterior' AND traslados_presupuestarios.estado='procesado'))) AS MReceptoraAnt,
					  (SELECT SUM(partidas_receptoras_rectificacion.monto_acreditar)
						FROM partidas_receptoras_rectificacion
							WHERE (partidas_receptoras_rectificacion.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
								AND (partidas_receptoras_rectificacion.idrectificacion_presupuesto in 
									(SELECT rectificacion_presupuesto.idrectificacion_presupuesto 
										FROM rectificacion_presupuesto 
											WHERE rectificacion_presupuesto.fecha_solicitud>='$anio_fiscal-01-01' 
												AND rectificacion_presupuesto.fecha_solicitud<='$hasta_anterior' AND rectificacion_presupuesto.estado='procesado'))) AS MRectificacionAnt,
					  (SELECT SUM(partidas_disminucion_presupuesto.monto_debitar)
						FROM partidas_disminucion_presupuesto
							WHERE (partidas_disminucion_presupuesto.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
								AND (partidas_disminucion_presupuesto.iddisminucion_presupuesto in 
									(SELECT disminucion_presupuesto.iddisminucion_presupuesto 
										FROM disminucion_presupuesto 
											WHERE disminucion_presupuesto.fecha_solicitud>='$anio_fiscal-01-01' 
												AND disminucion_presupuesto.fecha_solicitud<='$hasta_anterior' AND disminucion_presupuesto.estado='procesado'))) AS MDisminucionAnt,
					  (SELECT SUM(partidas_cedentes_traslado.monto_debitar)
						FROM partidas_cedentes_traslado
							WHERE (partidas_cedentes_traslado.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
								AND (partidas_cedentes_traslado.idtraslados_presupuestarios in 
									(SELECT traslados_presupuestarios.idtraslados_presupuestarios 
										FROM traslados_presupuestarios 
											WHERE traslados_presupuestarios.fecha_solicitud>='$anio_fiscal-01-01' 
												AND traslados_presupuestarios.fecha_solicitud<='$hasta_anterior' AND traslados_presupuestarios.estado='procesado'))) AS MCedentesAnt,
					  (SELECT SUM(partidas_rectificadoras.monto_debitar)
						FROM partidas_rectificadoras
							WHERE (partidas_rectificadoras.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
								AND (partidas_rectificadoras.idrectificacion_presupuesto in 
									(SELECT rectificacion_presupuesto.idrectificacion_presupuesto 
										FROM rectificacion_presupuesto 
											WHERE rectificacion_presupuesto.fecha_solicitud>='$anio_fiscal-01-01' 
												AND rectificacion_presupuesto.fecha_solicitud<='$hasta_anterior' AND rectificacion_presupuesto.estado='procesado'))) AS MRectificadoraAnt
				FROM 
					  maestro_presupuesto, 
					  categoria_programatica, 
					  unidad_ejecutora, 
					  clasificador_presupuestario, 
					  ordinal
				WHERE 
					  ((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					  (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					  (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
					  (maestro_presupuesto.idordinal=ordinal.idordinal) AND 
					  (maestro_presupuesto.anio='".$anio_fiscal."' AND 
					  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
					  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
				
				UNION
				
				(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria, 
						maestro_presupuesto.idclasificador_presupuestario AS idPartida,
						categoria_programatica.codigo AS CodCategoria, 
						unidad_ejecutora.denominacion AS Unidad, 
						clasificador_presupuestario.partida AS Par,
						(SELECT clasificador_presupuestario.generica 
						 FROM clasificador_presupuestario
						 WHERE (clasificador_presupuestario.idclasificador_presupuestario=idPartida)) AS Gen, 
						'00' AS Esp, 
						'00' AS Sesp,
						(SELECT clasificador_presupuestario.denominacion 
						 FROM clasificador_presupuestario 
						 WHERE 
							(clasificador_presupuestario.partida=Par AND
							 clasificador_presupuestario.generica=Gen AND 
							 clasificador_presupuestario.especifica='00' AND 
							 clasificador_presupuestario.sub_especifica='00')) AS NomPartida, 
						maestro_presupuesto.idRegistro AS IdPresupuesto,
						SUM(maestro_presupuesto.monto_original) AS Formulado, 
						SUM(maestro_presupuesto.monto_actual) AS Actual, 
						SUM(maestro_presupuesto.total_causados) AS Causado, 
						SUM(maestro_presupuesto.total_pagados) AS Pagado, 
						SUM(maestro_presupuesto.total_compromisos) AS Compromiso, 
						SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso, 
						SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir,
						SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
						SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion,
						'generica' AS Tipo,
						'0000' AS codordinal, 
						'' AS nomordinal,
						'' AS MCreditoAnt,
					    '' AS MReceptoraAnt,
					    '' AS MRectificacionAnt,
						'' AS MDisminucionAnt,
						'' AS MCedentesAnt,
						'' As MRectificadoraAnt
					FROM 
						maestro_presupuesto, 
						categoria_programatica, 
						unidad_ejecutora, 
						clasificador_presupuestario
					WHERE 
						((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
						(clasificador_presupuestario.sub_especifica='00') AND 
						(maestro_presupuesto.anio='".$anio_fiscal."' AND 
						maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
						maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
					GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
					
				UNION
					
				(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria, 
						maestro_presupuesto.idclasificador_presupuestario AS idPartida,
						categoria_programatica.codigo AS CodCategoria, 
						unidad_ejecutora.denominacion AS Unidad, 
						clasificador_presupuestario.partida AS Par,
						'00' AS Gen, 
						'00' AS Esp, 
						'00' AS Sesp,
						(SELECT clasificador_presupuestario.denominacion 
						 FROM clasificador_presupuestario
						 WHERE 
							(clasificador_presupuestario.partida=Par AND 
							clasificador_presupuestario.generica='00' AND 
							clasificador_presupuestario.especifica='00' AND 
							clasificador_presupuestario.sub_especifica='00')) AS NomPartida, 
						maestro_presupuesto.idRegistro AS IdPresupuesto,
						SUM(maestro_presupuesto.monto_original) AS Formulado, 
						SUM(maestro_presupuesto.monto_actual) AS Actual, 
						SUM(maestro_presupuesto.total_causados) AS Causado, 
						SUM(maestro_presupuesto.total_pagados) AS Pagado, 
						SUM(maestro_presupuesto.total_compromisos) AS Compromiso,
						SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso,
						SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir,
						SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
						SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion,
						'partida' AS Tipo,
						'0000' AS codordinal, 
						'' AS nomordinal,
						'' AS MCreditoAnt,
						'' AS MReceptoraAnt,
						'' AS MRectificacionAnt,
						'' AS MDisminucionAnt,
						'' AS MCedentesAnt,
						'' As MRectificadoraAnt
					FROM 
						maestro_presupuesto, 
						categoria_programatica, 
						unidad_ejecutora, 
						clasificador_presupuestario
					WHERE 
						((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
						(clasificador_presupuestario.sub_especifica='00') AND 
						(maestro_presupuesto.anio='".$anio_fiscal."' AND 
						maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
						maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
					GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$modificado = ($field['TotalAumento'] - $field['TotalDisminucion']);
			$actual = $field['Formulado'] + $field['TotalAumento'] - $field['TotalDisminucion'];
			if ($chkrestar) $disponible = $actual-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"];
			else $disponible = $actual-$field["Compromiso"]-$field["ReservadoDisminuir"];
			$disponibilidad_presupuestaria = $actual - $field['Compromiso'];
			$disponibilidad_financiera = $actual - $field['Pagado'];			
			if ($field["Compromiso"]==0 or $actual==0) $pcompromiso="0"; else $pcompromiso=(float) (($field["Compromiso"]*100)/$actual);
			if ($field["Causado"]==0 or $actual==0) $pcausado="0"; else $pcausado=(float) (($field["Causado"]*100)/$actual);
			if ($field["Pagado"]==0 or $actual==0) $ppagado="0"; else $ppagado=(float) (($field["Pagado"]*100)/$actual);
			if ($disponible==0) $pdisponible="0"; else $pdisponible=(float) ((($actual-$field["Compromiso"])*100)/$actual);
			
			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"]!=$IdCategoria) {
				$IdCategoria = $field["IdCategoria"];
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(205, 5, utf8_decode($field["CodCategoria"]." - ".$field["Unidad"]), 1, 1, 'L', 1); 
				$linea=$pdf->GetY(); if ($linea>250) { rendicion_mensual($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $nom_mes[$mes]); }
			}
			if ($field["Tipo"]=="partida") {
				$sql = "SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria, 
								  maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
								  categoria_programatica.codigo AS CodCategoria, 
								  unidad_ejecutora.denominacion AS Unidad, 
								  clasificador_presupuestario.partida AS Par,
								  clasificador_presupuestario.generica AS Gen, 
								  clasificador_presupuestario.especifica AS Esp, 
								  clasificador_presupuestario.sub_especifica AS Sesp,
								  clasificador_presupuestario.denominacion AS NomPartida, 
								  maestro_presupuesto.idRegistro AS IdPresupuesto,
								  SUM(maestro_presupuesto.monto_original) AS Formulado, 
								  SUM(maestro_presupuesto.monto_actual) AS Actual, 
								  SUM(maestro_presupuesto.total_causados) AS Causado, 
								  SUM(maestro_presupuesto.total_pagados) AS Pagado, 
								  SUM(maestro_presupuesto.total_compromisos) AS Compromiso,
								  SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso, 
								  SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir,
								  SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
								  SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion,
								  'especifica' AS Tipo, 
								  ordinal.codigo AS codordinal, 
								  ordinal.denominacion AS nomordinal,
								  (SELECT SUM(partidas_credito_adicional.monto_acreditar)
									FROM partidas_credito_adicional
										WHERE (partidas_credito_adicional.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
											AND (partidas_credito_adicional.idcredito_adicional in 
												(SELECT creditos_adicionales.idcreditos_adicionales 
													FROM creditos_adicionales 
														WHERE creditos_adicionales.fecha_solicitud>='$anio_fiscal-01-01' 
															AND creditos_adicionales.fecha_solicitud<='$hasta_anterior' AND creditos_adicionales.estado='procesado'))) AS MCreditoAnt,
								  (SELECT SUM(partidas_receptoras_traslado.monto_acreditar)
									FROM partidas_receptoras_traslado
										WHERE (partidas_receptoras_traslado.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
											AND (partidas_receptoras_traslado.idtraslados_presupuestarios in 
												(SELECT traslados_presupuestarios.idtraslados_presupuestarios 
													FROM traslados_presupuestarios 
														WHERE traslados_presupuestarios.fecha_solicitud>='$anio_fiscal-01-01' 
															AND traslados_presupuestarios.fecha_solicitud<='$hasta_anterior' AND traslados_presupuestarios.estado='procesado'))) AS MReceptoraAnt,
								  (SELECT SUM(partidas_receptoras_rectificacion.monto_acreditar)
									FROM partidas_receptoras_rectificacion
										WHERE (partidas_receptoras_rectificacion.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
											AND (partidas_receptoras_rectificacion.idrectificacion_presupuesto in 
												(SELECT rectificacion_presupuesto.idrectificacion_presupuesto 
													FROM rectificacion_presupuesto 
														WHERE rectificacion_presupuesto.fecha_solicitud>='$anio_fiscal-01-01' 
															AND rectificacion_presupuesto.fecha_solicitud<='$hasta_anterior' AND rectificacion_presupuesto.estado='procesado'))) AS MRectificacionAnt,
								  (SELECT SUM(partidas_disminucion_presupuesto.monto_debitar)
									FROM partidas_disminucion_presupuesto
										WHERE (partidas_disminucion_presupuesto.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
											AND (partidas_disminucion_presupuesto.iddisminucion_presupuesto in 
												(SELECT disminucion_presupuesto.iddisminucion_presupuesto 
													FROM disminucion_presupuesto 
														WHERE disminucion_presupuesto.fecha_solicitud>='$anio_fiscal-01-01' 
															AND disminucion_presupuesto.fecha_solicitud<='$hasta_anterior' AND disminucion_presupuesto.estado='procesado'))) AS MDisminucionAnt,
								  (SELECT SUM(partidas_cedentes_traslado.monto_debitar)
									FROM partidas_cedentes_traslado
										WHERE (partidas_cedentes_traslado.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
											AND (partidas_cedentes_traslado.idtraslados_presupuestarios in 
												(SELECT traslados_presupuestarios.idtraslados_presupuestarios 
													FROM traslados_presupuestarios 
														WHERE traslados_presupuestarios.fecha_solicitud>='$anio_fiscal-01-01' 
															AND traslados_presupuestarios.fecha_solicitud<='$hasta_anterior' AND traslados_presupuestarios.estado='procesado'))) AS MCedentesAnt,
								  (SELECT SUM(partidas_rectificadoras.monto_debitar)
									FROM partidas_rectificadoras
										WHERE (partidas_rectificadoras.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
											AND (partidas_rectificadoras.idrectificacion_presupuesto in 
												(SELECT rectificacion_presupuesto.idrectificacion_presupuesto 
													FROM rectificacion_presupuesto 
														WHERE rectificacion_presupuesto.fecha_solicitud>='$anio_fiscal-01-01' 
															AND rectificacion_presupuesto.fecha_solicitud<='$hasta_anterior' AND rectificacion_presupuesto.estado='procesado'))) AS MRectificadoraAnt
							FROM 
								  maestro_presupuesto, 
								  categoria_programatica, 
								  unidad_ejecutora, 
								  clasificador_presupuestario, 
								  ordinal
							WHERE 
								  (clasificador_presupuestario.partida = '".$field['Par']."') AND
								  (maestro_presupuesto.idcategoria_programatica = '".$field['IdCategoria']."') AND
								  ((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
								  (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
								  (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
								  (maestro_presupuesto.idordinal=ordinal.idordinal) AND 
								  (maestro_presupuesto.anio='".$anio_fiscal."' AND 
								  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
								  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
							GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal)";
				$query_partida = mysql_query($sql) or die ($sql);
				while ($field_partida = mysql_fetch_array($query_partida)) {
					$aumentoant += ($field_partida["MCreditoAnt"] + $field_partida["MReceptoraAnt"] + $field_partida["MRectificacionAnt"]);
					$disminucionant +=$field_partida["MDisminucionAnt"]+$field_partida["MCedentesAnt"]+$field_partida["MRectificadoraAnt"];
				}
				$actualant = $field['Formulado'] + $aumentoant - $disminucionant;
				$sum_disponibilidad_presupuestaria+=$disponibilidad_presupuestaria;
				$sum_disponibilidad_financiera+=$disponibilidad_financiera;
				$sum_actualant+=$actualant;
				$sum_formulado+=$field["Formulado"];
				$sum_modificado += ($field['TotalAumento'] - $field['TotalDisminucion']);
				$sum_actual+=$actual;
				$sum_compromiso+=$field["Compromiso"];
				$sum_causado+=$field["Causado"];
				$sum_pagado+=$field["Pagado"];
				$sum_aumento+=$field["TotalAumento"];
				$sum_disminucion+=$field["TotalDisminucion"];
				$sum_disponible+=$disponible;
				
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->SetHeight(array(5));
				$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(20, 50, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23));
				$pdf->Row(array($clasificador, 
								utf8_decode($field["NomPartida"]), 
								number_format($actualant, 2, ',', '.'), 
								number_format($actualant, 2, ',', '.'), 
								number_format($field["TotalAumento"], 2, ',', '.'), 
								number_format($field["TotalDisminucion"], 2, ',', '.'), 
								number_format($modificado, 2, ',', '.'), 
								number_format($actual, 2, ',', '.'), 
								number_format($field["Compromiso"], 2, ',', '.'), 
								number_format($field["Causado"], 2, ',', '.'), 
								number_format($disponibilidad_presupuestaria, 2, ',', '.'), 
								number_format($field["Pagado"], 2, ',', '.'), 
								number_format($disponibilidad_financiera, 2, ',', '.')));
			}
			else if ($field["Tipo"]=="generica") {
				$sql = "SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria, 
								  maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
								  categoria_programatica.codigo AS CodCategoria, 
								  unidad_ejecutora.denominacion AS Unidad, 
								  clasificador_presupuestario.partida AS Par,
								  clasificador_presupuestario.generica AS Gen, 
								  clasificador_presupuestario.especifica AS Esp, 
								  clasificador_presupuestario.sub_especifica AS Sesp,
								  clasificador_presupuestario.denominacion AS NomPartida, 
								  maestro_presupuesto.idRegistro AS IdPresupuesto,
								  SUM(maestro_presupuesto.monto_original) AS Formulado, 
								  SUM(maestro_presupuesto.monto_actual) AS Actual, 
								  SUM(maestro_presupuesto.total_causados) AS Causado, 
								  SUM(maestro_presupuesto.total_pagados) AS Pagado, 
								  SUM(maestro_presupuesto.total_compromisos) AS Compromiso,
								  SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso, 
								  SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir,
								  SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
								  SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion,
								  'especifica' AS Tipo, 
								  ordinal.codigo AS codordinal, 
								  ordinal.denominacion AS nomordinal,
								  (SELECT SUM(partidas_credito_adicional.monto_acreditar)
									FROM partidas_credito_adicional
										WHERE (partidas_credito_adicional.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
											AND (partidas_credito_adicional.idcredito_adicional in 
												(SELECT creditos_adicionales.idcreditos_adicionales 
													FROM creditos_adicionales 
														WHERE creditos_adicionales.fecha_solicitud>='$anio_fiscal-01-01' 
															AND creditos_adicionales.fecha_solicitud<='$hasta_anterior' AND creditos_adicionales.estado='procesado'))) AS MCreditoAnt,
								  (SELECT SUM(partidas_receptoras_traslado.monto_acreditar)
									FROM partidas_receptoras_traslado
										WHERE (partidas_receptoras_traslado.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
											AND (partidas_receptoras_traslado.idtraslados_presupuestarios in 
												(SELECT traslados_presupuestarios.idtraslados_presupuestarios 
													FROM traslados_presupuestarios 
														WHERE traslados_presupuestarios.fecha_solicitud>='$anio_fiscal-01-01' 
															AND traslados_presupuestarios.fecha_solicitud<='$hasta_anterior' AND traslados_presupuestarios.estado='procesado'))) AS MReceptoraAnt,
								  (SELECT SUM(partidas_receptoras_rectificacion.monto_acreditar)
									FROM partidas_receptoras_rectificacion
										WHERE (partidas_receptoras_rectificacion.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
											AND (partidas_receptoras_rectificacion.idrectificacion_presupuesto in 
												(SELECT rectificacion_presupuesto.idrectificacion_presupuesto 
													FROM rectificacion_presupuesto 
														WHERE rectificacion_presupuesto.fecha_solicitud>='$anio_fiscal-01-01' 
															AND rectificacion_presupuesto.fecha_solicitud<='$hasta_anterior' AND rectificacion_presupuesto.estado='procesado'))) AS MRectificacionAnt,
								  (SELECT SUM(partidas_disminucion_presupuesto.monto_debitar)
									FROM partidas_disminucion_presupuesto
										WHERE (partidas_disminucion_presupuesto.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
											AND (partidas_disminucion_presupuesto.iddisminucion_presupuesto in 
												(SELECT disminucion_presupuesto.iddisminucion_presupuesto 
													FROM disminucion_presupuesto 
														WHERE disminucion_presupuesto.fecha_solicitud>='$anio_fiscal-01-01' 
															AND disminucion_presupuesto.fecha_solicitud<='$hasta_anterior' AND disminucion_presupuesto.estado='procesado'))) AS MDisminucionAnt,
								  (SELECT SUM(partidas_cedentes_traslado.monto_debitar)
									FROM partidas_cedentes_traslado
										WHERE (partidas_cedentes_traslado.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
											AND (partidas_cedentes_traslado.idtraslados_presupuestarios in 
												(SELECT traslados_presupuestarios.idtraslados_presupuestarios 
													FROM traslados_presupuestarios 
														WHERE traslados_presupuestarios.fecha_solicitud>='$anio_fiscal-01-01' 
															AND traslados_presupuestarios.fecha_solicitud<='$hasta_anterior' AND traslados_presupuestarios.estado='procesado'))) AS MCedentesAnt,
								  (SELECT SUM(partidas_rectificadoras.monto_debitar)
									FROM partidas_rectificadoras
										WHERE (partidas_rectificadoras.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
											AND (partidas_rectificadoras.idrectificacion_presupuesto in 
												(SELECT rectificacion_presupuesto.idrectificacion_presupuesto 
													FROM rectificacion_presupuesto 
														WHERE rectificacion_presupuesto.fecha_solicitud>='$anio_fiscal-01-01' 
															AND rectificacion_presupuesto.fecha_solicitud<='$hasta_anterior' AND rectificacion_presupuesto.estado='procesado'))) AS MRectificadoraAnt
							FROM 
								  maestro_presupuesto, 
								  categoria_programatica, 
								  unidad_ejecutora, 
								  clasificador_presupuestario, 
								  ordinal
							WHERE 
								  (clasificador_presupuestario.partida = '".$field['Par']."') AND
								  (clasificador_presupuestario.generica = '".$field['Gen']."') AND
								  (maestro_presupuesto.idcategoria_programatica = '".$field['IdCategoria']."') AND
								  ((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
								  (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
								  (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
								  (maestro_presupuesto.idordinal=ordinal.idordinal) AND 
								  (maestro_presupuesto.anio='".$anio_fiscal."' AND 
								  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
								  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
							GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal)";
				$query_generica = mysql_query($sql) or die ($sql);
				while ($field_generica = mysql_fetch_array($query_generica)) {
					$aumentoant += ($field_generica["MCreditoAnt"] + $field_generica["MReceptoraAnt"] + $field_generica["MRectificacionAnt"]);
					$disminucionant +=$field_generica["MDisminucionAnt"]+$field_generica["MCedentesAnt"]+$field_generica["MRectificadoraAnt"];
				}
				$actualant = $field['Formulado'] + $aumentoant - $disminucionant;
				
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->SetHeight(array(5));
				$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(20, 50, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23));
				$pdf->Row(array($clasificador, 
								utf8_decode($field["NomPartida"]), 
								number_format($actualant, 2, ',', '.'), 
								number_format($actualant, 2, ',', '.'), 
								number_format($field["TotalAumento"], 2, ',', '.'), 
								number_format($field["TotalDisminucion"], 2, ',', '.'), 
								number_format($modificado, 2, ',', '.'), 
								number_format($actual, 2, ',', '.'), 
								number_format($field["Compromiso"], 2, ',', '.'), 
								number_format($field["Causado"], 2, ',', '.'), 
								number_format($disponibilidad_presupuestaria, 2, ',', '.'), 
								number_format($field["Pagado"], 2, ',', '.'), 
								number_format($disponibilidad_financiera, 2, ',', '.')));
			}
			else if ($field["Tipo"]=="especifica") {
				$aumentoant = $field["MCreditoAnt"] + $field["MReceptoraAnt"] + $field["MRectificacionAnt"];
				$disminucionant +=$field["MDisminucionAnt"]+$field["MCedentesAnt"]+$field["MRectificadoraAnt"];
				$actualant = $field['Formulado'] + $aumentoant - $disminucionant;
				
				if ($field['codordinal'] == "0000") {
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 7);
					$pdf->SetHeight(array(5));
					$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
					$pdf->SetWidths(array(20, 50, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23));
					$pdf->Row(array($clasificador, 
									utf8_decode($field["NomPartida"]), 
									number_format($actualant, 2, ',', '.'), 
									number_format($actualant, 2, ',', '.'), 
									number_format($field["TotalAumento"], 2, ',', '.'), 
									number_format($field["TotalDisminucion"], 2, ',', '.'), 
									number_format($modificado, 2, ',', '.'), 
									number_format($actual, 2, ',', '.'), 
									number_format($field["Compromiso"], 2, ',', '.'), 
									number_format($field["Causado"], 2, ',', '.'), 
									number_format($disponibilidad_presupuestaria, 2, ',', '.'), 
									number_format($field["Pagado"], 2, ',', '.'), 
									number_format($disponibilidad_financiera, 2, ',', '.')));
				} else {
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 7);
					$pdf->SetHeight(array(5));
					$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
					$pdf->SetWidths(array(20, 50, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23));
					$pdf->Row(array($clasificador, 
									utf8_decode($field["NomPartida"]), 
									number_format($actualant, 2, ',', '.'), 
									number_format($actualant, 2, ',', '.'), 
									number_format($field["TotalAumento"], 2, ',', '.'), 
									number_format($field["TotalDisminucion"], 2, ',', '.'), 
									number_format($modificado, 2, ',', '.'), 
									number_format($actual, 2, ',', '.'), 
									number_format($field["Compromiso"], 2, ',', '.'), 
									number_format($field["Causado"], 2, ',', '.'), 
									number_format($disponibilidad_presupuestaria, 2, ',', '.'), 
									number_format($field["Pagado"], 2, ',', '.'), 
									number_format($disponibilidad_financiera, 2, ',', '.')));
				}
			}
			$formulado=""; $actual=""; $precompromiso=""; $compromiso=""; $pcompromiso=""; $causado=""; $pcausado=""; $pagado=""; $ppagado=""; $disponible=""; $pdisponible=""; $aumento=""; $disminucion=""; $aumentoant=""; $dp="";
		}
		//	IMPRIMO EL TOTAL
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=355; $pdf->Rect($x, $y+2, $w, $h);
		$pdf->SetXY($x, $y+5);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
		$pdf->SetWidths(array(20, 50, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23));
		$pdf->SetHeight(array(5));
		$pdf->Row(array('', 
						'', 
						'', 
						number_format($sum_actualant, 2, ',', '.'), 
						number_format($sum_aumento, 2, ',', '.'), 
						number_format($sum_disminucion, 2, ',', '.'), 
						number_format($sum_modificado, 2, ',', '.'), 
						number_format($sum_actual, 2, ',', '.'), 
						number_format($sum_compromiso, 2, ',', '.'), 
						number_format($sum_causado, 2, ',', '.'), 
						number_format($sum_disponibilidad_presupuestaria, 2, ',', '.'), 
						number_format($sum_pagado, 2, ',', '.'), 
						number_format($sum_disponibilidad_financiera, 2, ',', '.')));
		break;
		
	//	Consolidado por Partidas...
	case "consolidado_por_partida":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//	---------------------------------------------
		$campos = explode("|", $checks);
		//	---------------------------------------------
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//	---------------------------------------------
		list($a, $m, $d)=SPLIT( '[/.-]', $desde); $periodod=$d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $periodoh=$d."/".$m."/".$a;
		$periodo = "$periodod a $periodoh";
		//---------------------------------------------
		$desde_anterior = "$anio_fiscal-01-01";
		if ($desde == $desde_anterior) $hasta_anterior = $desde_anterior;
		else {
			list($a, $m, $d) = SPLIT( '[/.-]', $desde); $anio = (int) $a; $mes = (int) $m; $dia = (int) $d;
			$dia = $d - 1; 
			if ($dia == 0) { 
				$mes = $m - 1;
				$dia = $dias_mes[$mes];
				if ($mes == 0) $anio = $anio_fiscal - 1;
			}
			if ($mes < 10) $mes = "0$mes";
			if ($dia < 10) $dia = "0$dia";
			$hasta_anterior = "$anio-$mes-$dia";
		}
		//---------------------------------------------
		if ($idpartida != "") $filtro = "AND (maestro_presupuesto.idclasificador_presupuestario = '".$idpartida."')";
		//	---------------------------------------------
		conlidado_por_categoria($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos, $periodo);
		//	---------------------------------------------
		$sql="SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria, 
					  maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
					  categoria_programatica.codigo AS CodCategoria, 
					  unidad_ejecutora.denominacion AS Unidad, 
					  clasificador_presupuestario.partida AS Par,
					  clasificador_presupuestario.generica AS Gen, 
					  clasificador_presupuestario.especifica AS Esp, 
					  clasificador_presupuestario.sub_especifica AS Sesp,
					  clasificador_presupuestario.denominacion AS NomPartida, 
					  maestro_presupuesto.idRegistro AS IdPresupuesto,
					  SUM(maestro_presupuesto.monto_original) AS Formulado,
					  
					  (SELECT SUM(monto_acreditar)
					   FROM partidas_credito_adicional
					   WHERE
						idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idcredito_adicional IN (SELECT idcreditos_adicionales
												FROM creditos_adicionales
												WHERE
													fecha_solicitud >= '".$desde_anterior."' AND
													fecha_solicitud <= '".$hasta_anterior."' AND
													estado = 'procesado')) AS CreditoAnterior,
					  
					  (SELECT SUM(monto_acreditar)
					   FROM partidas_credito_adicional
					   WHERE
						idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idcredito_adicional IN (SELECT idcreditos_adicionales
												FROM creditos_adicionales
												WHERE
													fecha_solicitud >= '".$desde."' AND
													fecha_solicitud <= '".$hasta."' AND
													estado = 'procesado')) AS Credito,
					
					  (SELECT SUM(monto_acreditar)
					   FROM partidas_receptoras_traslado
					   WHERE
						idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														FROM traslados_presupuestarios
														WHERE
															fecha_solicitud >= '".$desde_anterior."' AND
															fecha_solicitud <= '".$hasta_anterior."' AND
															estado = 'procesado')) AS ReceptoraAnterior,
					
					  (SELECT SUM(monto_acreditar)
					   FROM partidas_receptoras_traslado
					   WHERE
						idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														FROM traslados_presupuestarios
														WHERE
															fecha_solicitud >= '".$desde."' AND
															fecha_solicitud <= '".$hasta."' AND
															estado = 'procesado')) AS Receptora,
					
					  (SELECT SUM(monto_acreditar)
					   FROM partidas_receptoras_rectificacion
					   WHERE
						idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														FROM rectificacion_presupuesto
														WHERE
															fecha_solicitud >= '".$desde_anterior."' AND
															fecha_solicitud <= '".$hasta_anterior."' AND
															estado = 'procesado')) AS RectificacionAnterior,
					
					  (SELECT SUM(monto_acreditar)
					   FROM partidas_receptoras_rectificacion
					   WHERE
						idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														FROM rectificacion_presupuesto
														WHERE
															fecha_solicitud >= '".$desde."' AND
															fecha_solicitud <= '".$hasta."' AND
															estado = 'procesado')) AS Rectificacion,
					  
					  (SELECT SUM(monto_debitar)
					   FROM partidas_disminucion_presupuesto
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						iddisminucion_presupuesto IN (SELECT iddisminucion_presupuesto
													  FROM disminucion_presupuesto
													  WHERE
															fecha_solicitud >= '".$desde_anterior."' AND
															fecha_solicitud <= '".$hasta_anterior."' AND
															estado = 'procesado')) AS DisminucionAnterior,
					  
					  (SELECT SUM(monto_debitar)
					   FROM partidas_disminucion_presupuesto
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						iddisminucion_presupuesto IN (SELECT iddisminucion_presupuesto
													  FROM disminucion_presupuesto
													  WHERE
													  		fecha_solicitud >= '".$desde."' AND
															fecha_solicitud <= '".$hasta."' AND
															estado = 'procesado')) AS Disminucion,
					
					  (SELECT SUM(monto_debitar)
					   FROM partidas_cedentes_traslado
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														FROM traslados_presupuestarios
														WHERE
															fecha_solicitud >= '".$desde_anterior."' AND
															fecha_solicitud <= '".$hasta_anterior."' AND
															estado = 'procesado')) AS CedentesAnterior,
					
					  (SELECT SUM(monto_debitar)
					   FROM partidas_cedentes_traslado
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														FROM traslados_presupuestarios
														WHERE
															fecha_solicitud >= '".$desde."' AND
															fecha_solicitud <= '".$hasta."' AND
															estado = 'procesado')) AS Cedentes,
					
					  (SELECT SUM(monto_debitar)
					   FROM partidas_rectificadoras
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														FROM rectificacion_presupuesto
														WHERE
															fecha_solicitud >= '".$desde_anterior."' AND
															fecha_solicitud <= '".$hasta_anterior."' AND
															estado = 'procesado')) AS RectificadoraAnterior,
					
					  (SELECT SUM(monto_debitar)
					   FROM partidas_rectificadoras
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														FROM rectificacion_presupuesto
														WHERE
															fecha_solicitud >= '".$desde."' AND
															fecha_solicitud <= '".$hasta."' AND
															estado = 'procesado')) AS Rectificadora,
										
					  (SELECT SUM(monto)
					   FROM partidas_orden_compra_servicio
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idorden_compra_servicio IN (SELECT idorden_compra_servicio
													FROM orden_compra_servicio
													WHERE
														fecha_orden >= '".$desde_anterior."' AND
														fecha_orden <= '".$hasta_anterior."' AND
														(estado = 'procesado' OR estado = 'pagado'))) AS CompraCompromisoAnterior,
										
					  (SELECT SUM(monto)
					   FROM partidas_orden_compra_servicio
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idorden_compra_servicio IN (SELECT idorden_compra_servicio
													FROM orden_compra_servicio
													WHERE
														fecha_orden >= '".$desde."' AND
														fecha_orden <= '".$hasta."' AND
														(estado = 'procesado' OR estado = 'pagado'))) AS CompraCompromiso,
					
					  (SELECT SUM(monto)
					   FROM partidas_orden_pago
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idorden_pago IN (SELECT op1.idorden_pago
										 FROM 
										 	orden_pago op1
											INNER JOIN tipos_documentos td1 ON (op1.tipo = td1.idtipos_documentos AND td1.compromete = 'si')
										 WHERE
										 	op1.fecha_orden >= '".$desde_anterior."' AND
										 	op1.fecha_orden <= '".$hasta_anterior."' AND
										 	(op1.estado = 'procesado' OR op1.estado = 'pagado'))) AS PagoCompromisoAnterior,
					
					  (SELECT SUM(monto)
					   FROM partidas_orden_pago
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idorden_pago IN (SELECT op1.idorden_pago
										 FROM 
										 	orden_pago op1
											INNER JOIN tipos_documentos td1 ON (op1.tipo = td1.idtipos_documentos AND td1.compromete = 'si')
										 WHERE
										 	op1.fecha_orden >= '".$desde."' AND
										 	op1.fecha_orden <= '".$hasta."' AND
										 	(op1.estado = 'procesado' OR op1.estado = 'pagado'))) AS PagoCompromiso,
					
					  (SELECT SUM(monto)
					   FROM partidas_orden_pago
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idorden_pago IN (SELECT idorden_pago
										 FROM orden_pago
										 WHERE
											fecha_orden >= '".$desde_anterior."' AND
											fecha_orden <= '".$hasta_anterior."' AND
											(estado = 'procesado' OR estado = 'pagada'))) AS CausadoAnterior,
					
					  (SELECT SUM(monto)
					   FROM partidas_orden_pago
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idorden_pago IN (SELECT idorden_pago
										 FROM orden_pago
										 WHERE
											fecha_orden >= '".$desde."' AND
											fecha_orden <= '".$hasta."' AND
											(estado = 'procesado' OR estado = 'pagada'))) AS Causado,
					
					  (SELECT SUM(monto)
					   FROM partidas_orden_pago
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idorden_pago IN (SELECT idorden_pago
										 FROM pagos_financieros
										 WHERE
											fecha_cheque >= '".$desde_anterior."' AND
											fecha_cheque <= '".$hasta_anterior."' AND
											(estado = 'conciliado' OR estado = 'transito'))) AS PagadoAnterior,
					
					  (SELECT SUM(monto)
					   FROM partidas_orden_pago
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						idorden_pago IN (SELECT idorden_pago
										 FROM pagos_financieros
										 WHERE
											fecha_cheque >= '".$desde."' AND
											fecha_cheque <= '".$hasta."' AND
											(estado = 'conciliado' OR estado = 'transito'))) AS Pagado,
					  
					  (SELECT SUM(monto_debitar)
					   FROM partidas_disminucion_presupuesto
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						iddisminucion_presupuesto IN (SELECT iddisminucion_presupuesto
													  FROM disminucion_presupuesto
													  WHERE
													  		fecha_solicitud >= '".$desde_anterior."' AND
															fecha_solicitud <= '".$hasta_anterior."' AND
															estado = 'elaboracion')) AS ReservadoDisminucionAnterior,
					  
					  (SELECT SUM(monto_debitar)
					   FROM partidas_disminucion_presupuesto
					   WHERE
					 	idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						iddisminucion_presupuesto IN (SELECT iddisminucion_presupuesto
													  FROM disminucion_presupuesto
													  WHERE
													  		fecha_solicitud >= '".$desde."' AND
															fecha_solicitud <= '".$hasta."' AND
															estado = 'elaboracion')) AS ReservadoDisminucion,
					  
					  'especifica' AS Tipo, 
					  ordinal.codigo AS codordinal, 
					  ordinal.denominacion AS nomordinal
				FROM 
					  maestro_presupuesto, 
					  categoria_programatica, 
					  unidad_ejecutora, 
					  clasificador_presupuestario, 
					  ordinal
				WHERE 
					  ((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					  (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					  (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
						(clasificador_presupuestario.sub_especifica='00') AND 
						(ordinal.codigo='0000' AND maestro_presupuesto.idordinal = ordinal.idordinal) AND
					  (maestro_presupuesto.anio='".$anio_fiscal."' AND 
					  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
					  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal)
				ORDER BY Par, Gen, Esp, Sesp, codordinal, CodCategoria";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($j=1; $j<=$rows; $j++) {
			$field=mysql_fetch_array($query);
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];			
			$aumento_anterior = $field['CreditoAnterior'] + $field['ReceptoraAnterior'] + $field['RectificacionAnterior'];
			$disminucion_anterior = $field['DisminucionAnterior'] + $field['CedentesAnterior'] + $field['RectificadoraAnterior'];
			$modificado_anterior = $aumento_anterior - $disminucion_anterior; 
			$actual_anterior = $field['Formulado'] + $modificado_anterior;
			$compromiso_anterior = $field['CompraCompromisoAnterior'] + $field['PagoCompromisoAnterior'];
			$disponible_anterior = $actual_anterior - $field['CompromisoAnterior'] - $field['ReservadoDisminucionAnterior'];			
			$aumento = $field['Credito'] + $field['Receptora'] + $field['Rectificacion'];
			$disminucion = $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'];
			$modificado = $aumento - $disminucion;
			$actual = $actual_anterior + $modificado;
			$compromiso = $field['CompraCompromiso'] + $field['PagoCompromiso'];
			$disponible = $actual - $field['Compromiso'] - $field['ReservadoDisminucion'];			
			if ($compromiso != 0 && $actual != 0) $pcompromiso = $compromiso * 100 / $actual; else $pcompromiso = 0;		
			if ($field['Causado'] != 0 && $actual != 0) $pcausado = $field['Causado'] * 100 / $actual; else $pcausado = 0;
			if ($field['Pagado'] != 0 && $actual != 0) $ppagado = $field['Pagado'] * 100 / $actual; else $ppagado = 0;
			if ($disponible != 0 && $actual != 0) $pdisponible = $disponible * 100 / $actual; else $pdisponible = 0;
			
			if ($grupo != $clasificador) {
				$grupo = $clasificador;
				$pdf->Ln(4);
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(225, 225, 225); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(50, 4, utf8_decode($clasificador.' '.$field['NomPartida']), 0, 1, 'L');
				$pdf->Ln(2);
			}
			
			$h++;
			if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
			else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
			$pdf->SetFont('Arial', '', 6);
			$y=$pdf->GetY();
			$x=5;
			$nb=$pdf->NbLines(50, utf8_decode($field['Unidad'])); $hf=3*$nb;
			$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $field['CodCategoria'], 0, 1, 'C', 1); $x+=20;
			$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, utf8_decode($field['Unidad']), 0, 'L', 1); $x+=50;
			if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($field['Formulado'], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($aumento, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($disminucion, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($modificado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($actual, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $precompromiso, 0, 1, 'R', 1); $x+=23; }
			if ($campos[6]) { 
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($compromiso, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pcompromiso, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[7]) { 
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($field['Causado'], 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pcausado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[8]) { 
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($field['Pagado'], 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($ppagado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[9]) { 
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($disponible, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pdisponible, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
			}
			$pdf->Ln(2);
		
			$linea=$pdf->GetY(); 
			if ($linea>175) conlidado_por_categoria($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos, $periodo);
		}
		break;
		
	//	Disponibilidad Presupuestaria por Periodo...
	
	case "disponibilidad_presupuestaria_periodo":
		$pdf=new PDF_MC_Table2('L', 'mm', 'Legal');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//---------------------------------------------
		$campos = explode("|", $checks);
		//---------------------------------------------
		$sql = "SELECT
					denominacion As TipoPresupuesto, 
					(SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento = '".$financiamiento."') AS FuenteFinanciamiento
				FROM tipo_presupuesto
				WHERE idtipo_presupuesto = '".$tipo_presupuesto."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		if ($rows != 0) $field = mysql_fetch_array($query);
		$nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto = $field['TipoPresupuesto'];
		//---------------------------------------------
		disponibilidad_presupuestaria_periodo($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos, $desde, $hasta);
		//---------------------------------------------
		$filtro = "";
		if ($anio_fiscal != "") $filtro .= " AND (maestro_presupuesto.anio = '".$anio_fiscal."')";
		if ($financiamiento != "") $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."')";
		if ($tipo_presupuesto != "") $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."')";
		if ($idcategoria != "") $filtro .= " AND (maestro_presupuesto.idcategoria_programatica = '".$idcategoria."')";
		if ($idpartida != "") $filtro .= " AND (maestro_presupuesto.idclasificador_presupuestario = '".$idpartida."')";
		if ($idordinal != "") $filtro .= " AND (maestro_presupuesto.idordinal = '".$idordinal."')";
		//---------------------------------------------
		list($a, $m, $d)=SPLIT( '[/.-]', $_GET['desde']); $m=(int) $m; $d=(int) $d;
		$mes[1]=31; $mes[3]=31; $mes[4]=30; $mes[5]=31; $mes[6]=30; $mes[7]=31; $mes[8]=31; $mes[9]=30; $mes[10]=31; $mes[11]=30; $mes[12]=31;
		if ($a%4==0) $mes[2]=29; else $mes[2]=28;
		$dia_anterior=$d-1; 
		if ($dia_anterior==0) { $mes_anterior=$m-1; $dia_anterior=$mes[$mes_anterior]; if ($mes_anterior<10) $mes_anterior="0".$mes_anterior; if ($dia_anterior<10) $dia_anterior="0".$dia_anterior; if ($mes_anterior==0 || $dia_anterior==0) $hasta_anterior="$anio_fiscal-01-01"; else $hasta_anterior=$a."-".$mes_anterior."-".$dia_anterior; }
		else { if ($m<10) $m="0".$m; if ($dia_anterior<10) $dia_anterior="0".$dia_anterior; if ($m==0 || $dia_anterior==0) $hasta_anterior="$anio_fiscal-01-01"; else $hasta_anterior=$a."-".$m."-".$dia_anterior; }
		list($a, $m, $d)=SPLIT( '[/.-]', $desde); $idesde="$d-$m-$a";
		list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $ihasta="$d-$m-$a";
		list($a, $m, $d)=SPLIT( '[/.-]', $hasta_anterior); $ihasta_anterior="$d-$m-$a";
		
		//	CONSULTO
		$sql="SELECT 
				clasificador_presupuestario.idclasificador_presupuestario, 
				clasificador_presupuestario.denominacion AS NombrePartida, 
				clasificador_presupuestario.partida,
				clasificador_presupuestario.generica, 
				clasificador_presupuestario.especifica, 
				clasificador_presupuestario.sub_especifica,
				clasificador_presupuestario.codigo_cuenta,
				categoria_programatica.idcategoria_programatica, 
				categoria_programatica.codigo AS CodCategoria, 
				categoria_programatica.idunidad_ejecutora, 
				unidad_ejecutora.denominacion AS UnidadEjecutora,
				maestro_presupuesto.idRegistro, 
				maestro_presupuesto.idordinal, 
				maestro_presupuesto.monto_original AS MontoOriginal, 
				ordinal.codigo AS CodOrdinal, 
				ordinal.denominacion AS Ordinal,
				(SELECT SUM(partidas_credito_adicional.monto_acreditar)
					FROM partidas_credito_adicional
						WHERE (partidas_credito_adicional.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_credito_adicional.idcredito_adicional in 
								(SELECT creditos_adicionales.idcreditos_adicionales 
									FROM creditos_adicionales 
										WHERE creditos_adicionales.fecha_solicitud>='$anio_fiscal-01-01' 
											AND creditos_adicionales.fecha_solicitud<='$hasta_anterior' AND creditos_adicionales.estado='procesado'))) AS MCredito1,
				
				(SELECT SUM(partidas_credito_adicional.monto_acreditar)
					FROM partidas_credito_adicional
						WHERE (partidas_credito_adicional.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_credito_adicional.idcredito_adicional in 
								(SELECT creditos_adicionales.idcreditos_adicionales 
									FROM creditos_adicionales 
										WHERE creditos_adicionales.fecha_solicitud>='$desde' 
											AND creditos_adicionales.fecha_solicitud<='$hasta' AND creditos_adicionales.estado='procesado'))) AS MCredito2,
				
				(SELECT SUM(partidas_disminucion_presupuesto.monto_debitar)
					FROM partidas_disminucion_presupuesto
						WHERE (partidas_disminucion_presupuesto.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_disminucion_presupuesto.iddisminucion_presupuesto in 
								(SELECT disminucion_presupuesto.iddisminucion_presupuesto 
									FROM disminucion_presupuesto 
										WHERE disminucion_presupuesto.fecha_solicitud>='$anio_fiscal-01-01' 
											AND disminucion_presupuesto.fecha_solicitud<='$hasta_anterior' AND disminucion_presupuesto.estado='procesado'))) AS MDisminucion1,
				
				(SELECT SUM(partidas_disminucion_presupuesto.monto_debitar)
					FROM partidas_disminucion_presupuesto
						WHERE (partidas_disminucion_presupuesto.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_disminucion_presupuesto.iddisminucion_presupuesto in 
								(SELECT disminucion_presupuesto.iddisminucion_presupuesto 
									FROM disminucion_presupuesto 
										WHERE disminucion_presupuesto.fecha_solicitud>='$desde' 
											AND disminucion_presupuesto.fecha_solicitud<='$hasta' AND disminucion_presupuesto.estado='procesado'))) AS MDisminucion2,
				
				(SELECT SUM(partidas_receptoras_traslado.monto_acreditar)
					FROM partidas_receptoras_traslado
						WHERE (partidas_receptoras_traslado.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_receptoras_traslado.idtraslados_presupuestarios in 
								(SELECT traslados_presupuestarios.idtraslados_presupuestarios 
									FROM traslados_presupuestarios 
										WHERE traslados_presupuestarios.fecha_solicitud>='$anio_fiscal-01-01' 
											AND traslados_presupuestarios.fecha_solicitud<='$hasta_anterior' AND traslados_presupuestarios.estado='procesado'))) AS MReceptora1,
				
				(SELECT SUM(partidas_receptoras_traslado.monto_acreditar)
					FROM partidas_receptoras_traslado
						WHERE (partidas_receptoras_traslado.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_receptoras_traslado.idtraslados_presupuestarios in 
								(SELECT traslados_presupuestarios.idtraslados_presupuestarios 
									FROM traslados_presupuestarios 
										WHERE traslados_presupuestarios.fecha_solicitud>='$desde' 
											AND traslados_presupuestarios.fecha_solicitud<='$hasta' AND traslados_presupuestarios.estado='procesado'))) AS MReceptora2,
				
				(SELECT SUM(partidas_cedentes_traslado.monto_debitar)
					FROM partidas_cedentes_traslado
						WHERE (partidas_cedentes_traslado.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_cedentes_traslado.idtraslados_presupuestarios in 
								(SELECT traslados_presupuestarios.idtraslados_presupuestarios 
									FROM traslados_presupuestarios 
										WHERE traslados_presupuestarios.fecha_solicitud>='$anio_fiscal-01-01' 
											AND traslados_presupuestarios.fecha_solicitud<='$hasta_anterior' AND traslados_presupuestarios.estado='procesado'))) AS MCedentes1,
				
				(SELECT SUM(partidas_cedentes_traslado.monto_debitar)
					FROM partidas_cedentes_traslado
						WHERE (partidas_cedentes_traslado.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_cedentes_traslado.idtraslados_presupuestarios in 
								(SELECT traslados_presupuestarios.idtraslados_presupuestarios 
									FROM traslados_presupuestarios 
										WHERE traslados_presupuestarios.fecha_solicitud>='$desde' 
											AND traslados_presupuestarios.fecha_solicitud<='$hasta' AND traslados_presupuestarios.estado='procesado'))) AS MCedentes2,
				
				(SELECT SUM(partidas_receptoras_rectificacion.monto_acreditar)
					FROM partidas_receptoras_rectificacion
						WHERE (partidas_receptoras_rectificacion.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_receptoras_rectificacion.idrectificacion_presupuesto in 
								(SELECT rectificacion_presupuesto.idrectificacion_presupuesto 
									FROM rectificacion_presupuesto 
										WHERE rectificacion_presupuesto.fecha_solicitud>='$anio_fiscal-01-01' 
											AND rectificacion_presupuesto.fecha_solicitud<='$hasta_anterior' AND rectificacion_presupuesto.estado='procesado'))) AS MRectificacion1,
				
				(SELECT SUM(partidas_receptoras_rectificacion.monto_acreditar)
					FROM partidas_receptoras_rectificacion	
						WHERE (partidas_receptoras_rectificacion.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_receptoras_rectificacion.idrectificacion_presupuesto in 
								(SELECT rectificacion_presupuesto.idrectificacion_presupuesto 
									FROM rectificacion_presupuesto 
										WHERE rectificacion_presupuesto.fecha_solicitud>='$desde' 
											AND rectificacion_presupuesto.fecha_solicitud<='$hasta' AND rectificacion_presupuesto.estado='procesado'))) AS MRectificacion2,
				
				(SELECT SUM(partidas_rectificadoras.monto_debitar)
					FROM partidas_rectificadoras
						WHERE (partidas_rectificadoras.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_rectificadoras.idrectificacion_presupuesto in 
								(SELECT rectificacion_presupuesto.idrectificacion_presupuesto 
									FROM rectificacion_presupuesto 
										WHERE rectificacion_presupuesto.fecha_solicitud>='$anio_fiscal-01-01' 
											AND rectificacion_presupuesto.fecha_solicitud<='$hasta_anterior' AND rectificacion_presupuesto.estado='procesado'))) AS MRectificadora1,
				
				(SELECT SUM(partidas_rectificadoras.monto_debitar)
					FROM partidas_rectificadoras
						WHERE (partidas_rectificadoras.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_rectificadoras.idrectificacion_presupuesto in 
								(SELECT rectificacion_presupuesto.idrectificacion_presupuesto 
									FROM rectificacion_presupuesto 
										WHERE rectificacion_presupuesto.fecha_solicitud>='$desde' 
											AND rectificacion_presupuesto.fecha_solicitud<='$hasta' AND rectificacion_presupuesto.estado='procesado'))) AS MRectificadora2,
				
				(SELECT SUM(partidas_orden_compra_servicio.monto)
					FROM partidas_orden_compra_servicio
						WHERE (partidas_orden_compra_servicio.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_orden_compra_servicio.idorden_compra_servicio in 
								(SELECT orden_compra_servicio.idorden_compra_servicio 
									FROM orden_compra_servicio 
										WHERE orden_compra_servicio.fecha_orden>='$anio_fiscal-01-01' 
											AND orden_compra_servicio.fecha_orden<='$hasta_anterior' AND 
												(orden_compra_servicio.estado='procesado' OR orden_compra_servicio.estado='pagado' OR orden_compra_servicio.estado='conformado' OR orden_compra_servicio.estado='parcial')))) AS Compromete1,
				
				(SELECT SUM(partidas_orden_compra_servicio.monto)
					FROM partidas_orden_compra_servicio	
						WHERE (partidas_orden_compra_servicio.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_orden_compra_servicio.idorden_compra_servicio in 
								(SELECT orden_compra_servicio.idorden_compra_servicio 
									FROM orden_compra_servicio 
										WHERE orden_compra_servicio.fecha_orden>='$desde' 
											AND orden_compra_servicio.fecha_orden<='$hasta' AND 
												(orden_compra_servicio.estado='procesado' OR orden_compra_servicio.estado='pagado' OR orden_compra_servicio.estado='conformado' OR orden_compra_servicio.estado='parcial')))) AS Compromete2,
				
				(SELECT SUM(partidas_orden_pago.monto)
					FROM partidas_orden_pago
						WHERE (partidas_orden_pago.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_orden_pago.idorden_pago in 
								(SELECT orden_pago.idorden_pago 
									FROM orden_pago 
										WHERE orden_pago.fecha_orden>='$anio_fiscal-01-01' 
											AND orden_pago.fecha_orden<='$hasta_anterior' AND 
												(orden_pago.estado='procesado' OR orden_pago.estado='pagada' OR orden_pago.estado='conformado' OR orden_pago.estado='parcial')))) AS Causado1,
				
				(SELECT SUM(partidas_orden_pago.monto)
					FROM partidas_orden_pago	
						WHERE (partidas_orden_pago.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_orden_pago.idorden_pago in 
								(SELECT orden_pago.idorden_pago 
									FROM orden_pago 
										WHERE orden_pago.fecha_orden>='$desde' 
											AND orden_pago.fecha_orden<='$hasta' AND 
												(orden_pago.estado='procesado' OR orden_pago.estado='pagada' OR orden_pago.estado='conformado' OR orden_pago.estado='parcial')))) AS Causado2,
				
				(SELECT SUM(partidas_orden_pago.monto)
					FROM partidas_orden_pago
						WHERE (partidas_orden_pago.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_orden_pago.idorden_pago in 
								(SELECT pagos_financieros.idorden_pago 
									FROM pagos_financieros 
										WHERE pagos_financieros.fecha_cheque>='$anio_fiscal-01-01' 
											AND pagos_financieros.fecha_cheque<='$hasta_anterior' AND 
												(pagos_financieros.estado='conciliado' OR pagos_financieros.estado='transito' OR pagos_financieros.estado='parcial')))) AS Pagado1,
				
				(SELECT SUM(partidas_orden_pago.monto)
					FROM partidas_orden_pago	
						WHERE (partidas_orden_pago.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_orden_pago.idorden_pago in 
								(SELECT pagos_financieros.idorden_pago 
									FROM pagos_financieros 
										WHERE pagos_financieros.fecha_cheque>='$desde' 
											AND pagos_financieros.fecha_cheque<='$hasta' AND 
												(pagos_financieros.estado='conciliado' OR pagos_financieros.estado='transito' OR pagos_financieros.estado='parcial')))) AS Pagado2,
				
				(SELECT SUM(partidas_orden_compra_servicio.monto)
					FROM partidas_orden_compra_servicio
						WHERE (partidas_orden_compra_servicio.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_orden_compra_servicio.idorden_compra_servicio in 
								(SELECT orden_compra_servicio.idorden_compra_servicio 
									FROM orden_compra_servicio 
										WHERE orden_compra_servicio.fecha_orden>='$anio_fiscal-01-01' 
											AND orden_compra_servicio.fecha_orden<='$hasta_anterior' AND 
												(orden_compra_servicio.estado='procesado' OR orden_compra_servicio.estado='pagado' OR orden_compra_servicio.estado='conformado' OR orden_compra_servicio.estado='parcial')))) AS Ejecutado1,
				
				(SELECT SUM(partidas_orden_compra_servicio.monto)
					FROM partidas_orden_compra_servicio
						WHERE (partidas_orden_compra_servicio.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_orden_compra_servicio.idorden_compra_servicio in 
								(SELECT orden_compra_servicio.idorden_compra_servicio 
									FROM orden_compra_servicio 
										WHERE orden_compra_servicio.fecha_orden>='$desde' 
											AND orden_compra_servicio.fecha_orden<='$hasta' AND 
												(orden_compra_servicio.estado='procesado' OR orden_compra_servicio.estado='pagado' OR orden_compra_servicio.estado='conformado' OR orden_compra_servicio.estado='parcial')))) AS Ejecutado2,				 
				 
				 (SELECT SUM(partidas_orden_pago.monto)
					FROM partidas_orden_pago
						WHERE (partidas_orden_pago.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_orden_pago.idorden_pago in 
								(SELECT orden_pago.idorden_pago 
									FROM orden_pago 
										INNER JOIN tipos_documentos ON (orden_pago.tipo=tipos_documentos.idtipos_documentos 
													AND tipos_documentos.compromete='si')
										WHERE orden_pago.fecha_orden>='$anio_fiscal-01-01' 
											AND orden_pago.fecha_orden<='$hasta_anterior' AND 
												(orden_pago.estado='procesado' OR orden_pago.estado='pagada' OR orden_pago.estado='conformado' OR orden_pago.estado='parcial')))) AS Ejecutado3,
				 
				 (SELECT SUM(partidas_orden_pago.monto)
					FROM partidas_orden_pago
						WHERE (partidas_orden_pago.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
							AND (partidas_orden_pago.idorden_pago in 
								(SELECT orden_pago.idorden_pago 
									FROM orden_pago 
										INNER JOIN tipos_documentos ON (orden_pago.tipo=tipos_documentos.idtipos_documentos 
													AND tipos_documentos.compromete='si')
										WHERE orden_pago.fecha_orden>='$desde' 
											AND orden_pago.fecha_orden<='$hasta' AND 
												(orden_pago.estado='procesado' OR orden_pago.estado='pagada' OR orden_pago.estado='conformado' OR orden_pago.estado='parcial')))) AS Ejecutado4
				
			FROM
				clasificador_presupuestario, 
				categoria_programatica, 
				unidad_ejecutora, 
				maestro_presupuesto, 
				ordinal
			WHERE
				(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) 
				AND (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) 
				AND (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) 
				AND (maestro_presupuesto.idordinal=ordinal.idordinal) $filtro
			ORDER BY 
				categoria_programatica.codigo, 
				clasificador_presupuestario.partida,
				clasificador_presupuestario.generica, 
				clasificador_presupuestario.especifica, 
				clasificador_presupuestario.sub_especifica, 
				ordinal.codigo";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$idregistro=$field["idRegistro"];		
			//	-------------------------
			//	Imprimo la categoria programatica
			if ($field["idcategoria_programatica"]!=$cat) {
				$cat=$field["idcategoria_programatica"];
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(225, 225, 225); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 6);
				$pdf->Cell(340, 5, $field["CodCategoria"]." - ".$field["UnidadEjecutora"], 1, 1, 'L', 1);
				$linea=$pdf->GetY(); if ($linea>190) { disponibilidad_presupuestaria_periodo($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos, $fdesde, $fhasta); }
			}
			//	-------------------------	
			
			$Ejecutado1 = $field['Ejecutado1'] + $field['Ejecutado3'];
			$Ejecutado2 = $field['Ejecutado2'] + $field['Ejecutado4'];
			
			//	Imprimo la partida
			if ($partida != $flag) { 
				//$sum_monto_original += $field["MontoOriginal"]; 
				$flag = $field["partida"].".".$field["generica"].".".$field["especifica"].".".$field["sub_especifica"]; 
			}
			
			$partida=$field["partida"].".".$field["generica"].".".$field["especifica"].".".$field["sub_especifica"];
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 6);
			
			if ($field['CodOrdinal'] == "0000") $denominacion_partida = utf8_decode($field["NombrePartida"]);
			else $denominacion_partida = utf8_decode($field['CodOrdinal'].' '.$field["Ordinal"]);
				
			$y=$pdf->GetY();
			$x=5;
			$nb=$pdf->NbLines(55, utf8_decode($denominacion_partida)); $hf=3*$nb;
			$pdf->SetXY($x, $y); $pdf->Cell(15, $hf, $partida, 0, 1, 'C', 1); $x+=15;
			$pdf->SetXY($x, $y); $pdf->MultiCell(55, 3, $denominacion_partida, 0, 'L', 1); $x+=55;
			$pdf->Ln(2);
			$linea=$pdf->GetY(); if ($linea>190) { disponibilidad_presupuestaria_periodo($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos, $desde, $hasta); }
			//	-------------------------
			//	Imprimo hasta la fecha
			
			$monto_original=number_format($field["MontoOriginal"], 2, ',', '.');
			if ($field['CodOrdinal'] == "0000" && $field['sub_especifica'] == "00") $sum_monto_original += $field["MontoOriginal"];
			$aumento1=$field["MCredito1"]+$field["MReceptora1"]+$field["MRectificacion1"];
			$sum_aumento += $aumento1;
			$total_aumento1=number_format($aumento1, 2, ',', '.');
			$disminucion1=$field["MDisminucion1"]+$field["MCedentes1"]+$field["MRectificadora1"]; 
			$sum_disminucion += $disminucion1;
			$total_disminucion1=number_format($disminucion1, 2, ',', '.');
			$modificado1 = $aumento1 -$disminucion1; $total_modificado1=number_format($modificado1, 2, ',', '.');
			$actual1=$field["MontoOriginal"]+$aumento1-$disminucion1; $monto_actual1=number_format($actual1, 2, ',', '.');
			$ejecutado1=number_format($Ejecutado1, 2, ',', '.');
			$disponible1=$actual1-$Ejecutado1; 
			$monto_disponible1=number_format($disponible1, 2, ',', '.');
			if ($Ejecutado1=="" || $actual1==0) $pejecutado1="0,00"; else $pejecutado1=($Ejecutado1*100)/$actual1;
			$pejecutado1=number_format($pejecutado1, 2, ',', '.');
			if ($disponible1==0 || $actual1==0) $pdisponible1="0,00"; else $pdisponible1=($disponible1*100)/$actual1;
			$pdisponible1=number_format($pdisponible1, 2, ',', '.');//--
			$compromiso1=number_format($Ejecutado1, 2, ',', '.'); 
			$sum_compromiso += $Ejecutado1;
			if ($Ejecutado1==0 || $actual1==0) $pcompromiso1="0,00"; else $pcompromiso1=($Ejecutado1*100)/$actual1;
			$pcompromiso1=number_format($pcompromiso1, 2, ',', '.');
			$causado1=number_format($field["Causado1"], 2, ',', '.'); 
			$sum_causado += $field['Causado1'];
			if ($field["Causado1"]==0 || $actual1==0) $pcausado1="0,00"; else $pcausado1=($field["Causado1"]*100)/$actual1;
			$pcausado1=number_format($pcausado1, 2, ',', '.');
			$pagado1=number_format($field["Pagado1"], 2, ',', '.'); 
			$sum_pagado += $field['Pagado1'];
			if ($field["Pagado1"]==0 || $actual1==0) $ppagado1="0,00"; else $ppagado1=($field["Pagado1"]*100)/$actual1;
			$ppagado1=number_format($ppagado1, 2, ',', '.');
			
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 6);
			
			$denominacion_partida = 'Hasta el: '.$ihasta_anterior;
			$y=$pdf->GetY();
			$x=5;
			$nb=$pdf->NbLines(55, utf8_decode($denominacion_partida)); $hf=3*$nb;
			$pdf->SetXY($x, $y); $pdf->Cell(15, $hf, $partida, 0, 1, 'C', 1); $x+=15;
			$pdf->SetXY($x, $y); $pdf->MultiCell(55, 3, $denominacion_partida, 0, 'L', 1); $x+=55;
			if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_original, 0, 1, 'R', 1); $x+=23; }
			if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $total_aumento1, 0, 1, 'R', 1); $x+=23; }
			if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $total_disminucion1, 0, 1, 'R', 1); $x+=23; }
			if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $total_modificado1, 0, 1, 'R', 1); $x+=23; }
			if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_actual1, 0, 1, 'R', 1); $x+=23; }
			if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $precompromiso, 0, 1, 'R', 1); $x+=23; }
			if ($campos[6]) { 
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $compromiso1, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcompromiso1.' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[7]) { 
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $causado1, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado1.' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[8]) { 
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $pagado1, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $ppagado1.' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[9]) { 
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_disponible1, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pdisponible1.' %', 0, 1, 'R', 1); $x+=11;
			}
			$pdf->Ln(2);
			$linea=$pdf->GetY(); if ($linea>190) { disponibilidad_presupuestaria_periodo($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos, $desde, $hasta); }		
			//	-------------------------
			//	Imprimimo en el periodo
			$aumento2=$field["MCredito2"]+$field["MReceptora2"]+$field["MRectificacion2"]; 
			$sum_aumento += $aumento2;
			$total_aumento2=number_format($aumento2, 2, ',', '.');
			$disminucion2=$field["MDisminucion2"]+$field["MCedentes2"]+$field["MRectificadora2"]; 
			$sum_disminucion += $disminucion2;
			$total_disminucion2=number_format($disminucion2, 2, ',', '.');
			$modificado2 = $aumento2 -$disminucion2; $total_modificado2=number_format($modificado2, 2, ',', '.');
			$actual2=$actual1+$aumento2-$disminucion2; $monto_actual2=number_format($actual2, 2, ',', '.');	$sum_actual+=$actual2;
			$ejecutado2=number_format($Ejecutado2, 2, ',', '.');
			$disponible2=$disponible1-$Ejecutado2+$modificado2; 
			if ($field['sub_especifica'] == "00") $sum_disponible += $disponible2;
			$monto_disponible2=number_format($disponible2, 2, ',', '.');
			if ($Ejecutado2=="" || $actual2==0) $pejecutado2="0,00"; else $pejecutado2=($Ejecutado2*100)/$actual2;
			$pejecutado2=number_format($pejecutado2, 2, ',', '.');
			if ($disponible2==0 || $actual2==0) $pdisponible2="0,00"; else $pdisponible2=($disponible2*100)/$actual2;
			$pdisponible2=number_format($pdisponible2, 2, ',', '.');//--
			$compromiso2=number_format($Ejecutado2, 2, ',', '.'); 
			$sum_compromiso += $Ejecutado2;
			if ($Ejecutado2==0 || $actual2==0) $pcompromiso2="0,00"; else $pcompromiso2=($Ejecutado2*100)/$actual2;
			$pcompromiso2=number_format($pcompromiso2, 2, ',', '.');
			$causado2=number_format($field["Causado2"], 2, ',', '.'); 
			$sum_causado += $field['Causado2'];
			if ($field["Causado2"]==0 || $actual2==0) $pcausado2="0,00"; else $pcausado2=($field["Causado2"]*100)/$actual2;
			$pcausado2=number_format($pcausado2, 2, ',', '.');
			$pagado2=number_format($field["Pagado2"], 2, ',', '.'); 
			$sum_pagado += $field['Pagado2'];
			if ($field["Pagado2"]==0 || $actual2==0) $ppagado2="0,00"; else $ppagado2=($field["Pagado2"]*100)/$actual2;
			$ppagado2=number_format($ppagado2, 2, ',', '.');
			
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 6);
			$denominacion_partida = 'En el periodo: '.$idesde.' hasta '.$ihasta;
			$y=$pdf->GetY();
			$x=5;
			$nb=$pdf->NbLines(55, utf8_decode($denominacion_partida)); $hf=3*$nb;
			$pdf->SetXY($x, $y); $pdf->Cell(15, $hf, $partida, 0, 1, 'C', 1); $x+=15;
			$pdf->SetXY($x, $y); $pdf->MultiCell(55, 3, $denominacion_partida, 0, 'L', 1); $x+=55;
			if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
			if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $total_aumento2, 0, 1, 'R', 1); $x+=23; }
			if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $total_disminucion2, 0, 1, 'R', 1); $x+=23; }
			if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $total_modificado2, 0, 1, 'R', 1); $x+=23; }
			if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_actual2, 0, 1, 'R', 1); $x+=23; }
			if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
			if ($campos[6]) { 
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $compromiso2, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcompromiso2.' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[7]) { 
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $causado2, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado2.' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[8]) { 
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $pagado2, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $ppagado2.' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[9]) { 
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_disponible2, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pdisponible2.' %', 0, 1, 'R', 1); $x+=11;
			}
			$pdf->Ln(2);
			$linea=$pdf->GetY(); if ($linea>190) { disponibilidad_presupuestaria_periodo($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos, $desde, $hasta); }
			
			//	------------------------------------------------------------
			//	Imprimo el detalle por partida
			//	------------------------------------------------------------
			if ($tipo_reporte == "detallado") {
				$sql="SELECT traslados_presupuestarios.status as codigo_referencia, 
							 traslados_presupuestarios.idtraslados_presupuestarios AS id, 
							 traslados_presupuestarios.nro_solicitud, 
							 traslados_presupuestarios.fecha_solicitud, 
							 traslados_presupuestarios.justificacion, 
							 traslados_presupuestarios.estado, 
							 partidas_receptoras_traslado.monto_acreditar, 
							 'Traslado(+)' as tipo,
							 'no' AS Causa,
							 'no' As Compromete,
							 '' AS ROC
						FROM 
							 traslados_presupuestarios, 
							 partidas_receptoras_traslado
						WHERE 
							 (traslados_presupuestarios.idtraslados_presupuestarios=partidas_receptoras_traslado.idtraslados_presupuestarios) AND 
							 (partidas_receptoras_traslado.idmaestro_presupuesto='$idregistro') AND
							 (traslados_presupuestarios.anio='".$anio_fiscal."') AND 
							 (traslados_presupuestarios.fecha_solicitud>='$desde' AND traslados_presupuestarios.fecha_solicitud<='$hasta' AND traslados_presupuestarios.estado='procesado')
				
						UNION
						
						SELECT creditos_adicionales.status as codigo_referencia, 
							   creditos_adicionales.idcreditos_adicionales AS id, 
							   creditos_adicionales.nro_solicitud, 
							   creditos_adicionales.fecha_solicitud, 
							   creditos_adicionales.justificacion, 
							   creditos_adicionales.estado, 
							   partidas_credito_adicional.monto_acreditar, 
							   'Credito Adicional' as tipo,
							   'no' AS Causa,
							   'no' As Compromete,
							 '' AS ROC
						FROM 
							creditos_adicionales, 
							partidas_credito_adicional
						WHERE 
							(creditos_adicionales.idcreditos_adicionales=partidas_credito_adicional.idcredito_adicional) AND 
							(partidas_credito_adicional.idmaestro_presupuesto='$idregistro') AND 
							(creditos_adicionales.anio='".$anio_fiscal."') AND 
							(creditos_adicionales.fecha_solicitud>='$desde' AND creditos_adicionales.fecha_solicitud<='$hasta' AND creditos_adicionales.estado='procesado')
						
						UNION
						
						SELECT rectificacion_presupuesto.status as codigo_referencia, 
							   rectificacion_presupuesto.idrectificacion_presupuesto AS id, 
							   rectificacion_presupuesto.nro_solicitud, 
							   rectificacion_presupuesto.fecha_solicitud, 
							   rectificacion_presupuesto.justificacion, 
							   rectificacion_presupuesto.estado, 
							   partidas_receptoras_rectificacion.monto_acreditar, 
							   'Rectificacion(+)' as tipo,
							   'no' AS Causa,
							   'no' As Compromete,
							 '' AS ROC
						FROM 
							rectificacion_presupuesto, 
							partidas_receptoras_rectificacion
						WHERE 
							(rectificacion_presupuesto.idrectificacion_presupuesto=partidas_receptoras_rectificacion.idrectificacion_presupuesto) AND 
							(partidas_receptoras_rectificacion.idmaestro_presupuesto='$idregistro') AND 
							(rectificacion_presupuesto.anio='".$anio_fiscal."') AND 
							(rectificacion_presupuesto.fecha_solicitud>='$desde' AND rectificacion_presupuesto.fecha_solicitud<='$hasta' AND rectificacion_presupuesto.estado='procesado')
						
						UNION
						
						SELECT traslados_presupuestarios.status as codigo_referencia, 
							   traslados_presupuestarios.idtraslados_presupuestarios AS id, 
							   traslados_presupuestarios.nro_solicitud, 
							   traslados_presupuestarios.fecha_solicitud, 
							   traslados_presupuestarios.justificacion, 
							   traslados_presupuestarios.estado, 
							   partidas_cedentes_traslado.monto_debitar, 
							   'Traslado(-)' as tipo,
							   'no' AS Causa,
							   'no' As Compromete,
							 '' AS ROC
						FROM 
							traslados_presupuestarios, 
							partidas_cedentes_traslado
						WHERE 
							(traslados_presupuestarios.idtraslados_presupuestarios=partidas_cedentes_traslado.idtraslados_presupuestarios) AND 
							(partidas_cedentes_traslado.idmaestro_presupuesto='$idregistro') AND
							(traslados_presupuestarios.anio='".$anio_fiscal."') AND
							(traslados_presupuestarios.fecha_solicitud>='$desde' AND traslados_presupuestarios.fecha_solicitud<='$hasta' AND traslados_presupuestarios.estado='procesado')
						
						UNION
						
						SELECT disminucion_presupuesto.status as codigo_referencia, 
							   disminucion_presupuesto.iddisminucion_presupuesto AS id, 
							   disminucion_presupuesto.nro_solicitud, 
							   disminucion_presupuesto.fecha_solicitud, 
							   disminucion_presupuesto.justificacion, 
							   disminucion_presupuesto.estado, 
							   partidas_disminucion_presupuesto.monto_debitar, 
							   'Disminucion Presupuestaria' as tipo,
							   'no' AS Causa,
							   'no' As Compromete,
							 '' AS ROC
						FROM 
							disminucion_presupuesto, 
							partidas_disminucion_presupuesto
						WHERE 
							(disminucion_presupuesto.iddisminucion_presupuesto=partidas_disminucion_presupuesto.iddisminucion_presupuesto) AND 
							(partidas_disminucion_presupuesto.idmaestro_presupuesto='$idregistro') AND
							(disminucion_presupuesto.anio='".$anio_fiscal."') AND 
							(disminucion_presupuesto.fecha_solicitud>='$desde' AND disminucion_presupuesto.fecha_solicitud<='$hasta' AND disminucion_presupuesto.estado='procesado')
						
						UNION
						
						SELECT rectificacion_presupuesto.status as codigo_referencia, 
							   rectificacion_presupuesto.idrectificacion_presupuesto AS id, 
							   rectificacion_presupuesto.nro_solicitud, 
							   rectificacion_presupuesto.fecha_solicitud, 
							   rectificacion_presupuesto.justificacion, 
							   rectificacion_presupuesto.estado, 
							   partidas_rectificadoras.monto_debitar, 
							   'Rectificacion(-)' as tipo,
							   'no' AS Causa,
							   'no' As Compromete,
							 '' AS ROC
						FROM 
							rectificacion_presupuesto, 
							partidas_rectificadoras
						WHERE 
							(rectificacion_presupuesto.idrectificacion_presupuesto=partidas_rectificadoras.idrectificacion_presupuesto) AND 
							(partidas_rectificadoras.idmaestro_presupuesto='$idregistro') AND
							(rectificacion_presupuesto.anio='".$anio_fiscal."') AND 
							(rectificacion_presupuesto.fecha_solicitud>='$desde' AND rectificacion_presupuesto.fecha_solicitud<='$hasta' AND rectificacion_presupuesto.estado='procesado')
						
						UNION
						
						SELECT orden_compra_servicio.codigo_referencia as codigo_referencia, 
							   orden_compra_servicio.idorden_compra_servicio AS id, 
							   orden_compra_servicio.numero_orden, 
							   orden_compra_servicio.fecha_orden, 
							   beneficiarios.nombre, 
							   orden_compra_servicio.estado, 
							   partidas_orden_compra_servicio.monto, 
							   'Orden de Compra/Servicio' as tipo,
							   'no' AS Causa,
							   'no' As Compromete,
							 '' AS ROC
						FROM 
							orden_compra_servicio, 
							partidas_orden_compra_servicio, 
							beneficiarios
						WHERE 
							(orden_compra_servicio.idorden_compra_servicio=partidas_orden_compra_servicio.idorden_compra_servicio) AND 
							(orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios) AND 
							(partidas_orden_compra_servicio.idmaestro_presupuesto='$idregistro') AND
							(orden_compra_servicio.anio='".$anio_fiscal."') AND 
							(orden_compra_servicio.fecha_orden>='$desde' AND orden_compra_servicio.fecha_orden<='$hasta' AND (orden_compra_servicio.estado='procesado' OR orden_compra_servicio.estado='pagado' OR orden_compra_servicio.estado='conformado'))
						
						UNION
						
						SELECT orden_pago.codigo_referencia as codigo_referencia, 
							   orden_pago.idorden_pago AS id, 
							   orden_pago.numero_orden, 
							   orden_pago.fecha_orden, 
							   beneficiarios.nombre, 
							   orden_pago.estado, 
							   partidas_orden_pago.monto, 
							   'Orden de Pago' as tipo,
							   tipos_documentos.causa,
							   tipos_documentos.compromete,
							 '' AS ROC
						FROM 
							orden_pago, 
							partidas_orden_pago, 
							beneficiarios,
							tipos_documentos
						WHERE 
							(orden_pago.idorden_pago=partidas_orden_pago.idorden_pago) AND 
							(orden_pago.idbeneficiarios=beneficiarios.idbeneficiarios) AND 
							(partidas_orden_pago.idmaestro_presupuesto='$idregistro') AND
							(orden_pago.tipo=tipos_documentos.idtipos_documentos) AND 
							(orden_pago.anio='".$anio_fiscal."') AND 
							(orden_pago.fecha_orden>='$desde' AND orden_pago.fecha_orden<='$hasta' AND (orden_pago.estado='procesado' OR orden_pago.estado='pagada' OR orden_pago.estado='conformado'))
						
						UNION
						
						SELECT pagos_financieros.codigo_referencia as codigo_referencia, 
							   pagos_financieros.idpagos_financieros AS id, 
							   pagos_financieros.numero_cheque, 
							   pagos_financieros.fecha_cheque, 
							   pagos_financieros.beneficiario, 
							   pagos_financieros.estado, 
							   partidas_orden_pago.monto, 
							   'Cheque' as tipo,
							   'no' AS Causa,
							   'no' As Compromete,
							 '' AS ROC
						FROM 
							pagos_financieros,
							partidas_orden_pago
						WHERE 
							(pagos_financieros.idorden_pago=partidas_orden_pago.idorden_pago AND
							partidas_orden_pago.idmaestro_presupuesto='".$idregistro."') AND 
							(pagos_financieros.fecha_cheque>='$desde' AND pagos_financieros.fecha_cheque<='$hasta' AND (pagos_financieros.estado='conciliado' OR pagos_financieros.estado='transito'))
							
						ORDER BY fecha_solicitud";
				$query_detalle=mysql_query($sql) or die ($sql.mysql_error());
				$rows_detalle=mysql_num_rows($query_detalle);
				while ($detalle=mysql_fetch_array($query_detalle)) {
					$monto=number_format($detalle['monto_acreditar'], 2, ',', '.');			
					list($a, $m, $d)=SPLIT( '[/.-]', $detalle['fecha_solicitud']); $fecha_solicitud=$d."/".$m."/".$a;
					if ($detalle['tipo']=="Traslado(+)" || $detalle['tipo']=="Credito Adicional" || $detalle['tipo']=="Rectificacion(+)") { 
						$aumento=$monto; $disminucion=""; $compromisos=""; $causado=""; 
						if ($detalle['estado']=="Anulado") { $anulado="(ANULADO)"; $aumento="($aumento)"; } else $anulado="";
						$detalle['tipo']=$detalle['tipo']." ".$anulado;
					}
					else if ($detalle['tipo']=="Traslado(-)" || $detalle['tipo']=="Disminucion Presupuestaria" || $detalle['tipo']=="Rectificacion(-)") { 
						$disminucion=$monto; $aumento=""; $compromisos=""; $causado=""; 
						if ($detalle['estado']=="Anulado") { $anulado="(ANULADO)"; $disminucion="($disminucion)"; } else $anulado="";
						$detalle['tipo']=$detalle['tipo']." ".$anulado;
					}
					else if ($detalle['tipo']=="Orden de Compra/Servicio") { 
						$disminucion=""; $aumento=""; $compromisos=$monto; $causado=""; 
						if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $compromisos="($compromisos)"; } else $anulado="";
						$detalle['tipo']=$detalle['justificacion']." ".$anulado;
					}
					else if ($detalle['tipo']=="Orden de Pago") { 
						$disminucion=""; $aumento="";
						if ($detalle['Causa']=="si") { $causado=$monto; if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $causado="($causado)"; } else $anulado=""; } 
						if ($detalle['Compromete']=="si"){ $compromisos=$monto; if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $compromisos="($compromisos)"; } else $anulado=""; }					
						$detalle['tipo']=$detalle['justificacion']." ".$anulado;
						if ($detalle['estado']=="pagada") $imprimir_ocs="SI";
					}			
					else if ($detalle['tipo']=="Cheque") { 
						$disminucion=""; $aumento=""; $compromisos=""; $causado=""; $pagado=$monto; 
						if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $pagado="($pagado)"; } else $anulado="";
						$detalle['tipo']=$detalle['justificacion']." ".$anulado;
						$imprimir_op="SI";
					}
					
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 6);
					$denominacion_partida = $detalle['nro_solicitud'].' '.$fecha_solicitud.' '.utf8_decode($detalle['tipo']);
					$y=$pdf->GetY();
					$x=5;
					$nb=$pdf->NbLines(55, utf8_decode($denominacion_partida)); $hf=3*$nb;
					$pdf->SetXY($x, $y); $pdf->Cell(15, $hf, $partida, 0, 1, 'C', 1); $x+=15;
					$pdf->SetXY($x, $y); $pdf->MultiCell(55, 3, $denominacion_partida, 0, 'L', 1); $x+=55;
					if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
					if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $aumento, 0, 1, 'R', 1); $x+=23; }
					if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disminucion, 0, 1, 'R', 1); $x+=23; }
					if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $modificado, 0, 1, 'R', 1); $x+=23; }
					if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_actual, 0, 1, 'R', 1); $x+=23; }
					if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $precompromiso, 0, 1, 'R', 1); $x+=23; }
					if ($campos[6]) { 
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $compromisos, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, '', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[7]) { 
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $causado, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, '', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[8]) { 
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $pagado, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, '', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[9]) { 
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disponible, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, '', 0, 1, 'R', 1); $x+=11;
					}
					$pdf->Ln(2);
					$linea=$pdf->GetY(); if ($linea>190) { disponibilidad_presupuestaria_periodo($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos, $desde, $hasta); }
					$disminucion=""; $aumento=""; $compromisos=""; $causado=""; $pagado=""; $anulado=""; $monto_actual=""; $precompromiso=""; $imprimir_ocs=""; $imprimir_op=""; $ordenes="";			
				}
			}
		}
		
		//	Imprimo los totales
		$sum_modificado = $sum_aumento - $sum_disminucion;
		$sum_actual = $sum_monto_original + $sum_modificado;
		
		$sum_disponible = $sum_actual - $sum_compromiso;
		
		if ($sum_actual==0) $pcompromiso=0; else $pcompromiso=(float) (($sum_compromiso*100)/$sum_actual); $pcompromiso=number_format($pcompromiso, 2, ',', '.');		
		if ($sum_actual==0) $pcausado=0; else $pcausado=(float) (($sum_causado*100)/$sum_actual); $pcausado=number_format($pcausado, 2, ',', '.');
		if ($sum_actual==0) $ppagado=0; else $ppagado=(float) (($sum_pagado*100)/$sum_actual); $ppagado=number_format($ppagado, 2, ',', '.');
		if ($sum_actual==0) $pdisponible=0; else $pdisponible=(float) (($disponible*100)/$sum_actual); $pdisponible=number_format($pdisponible, 2, ',', '.');
		
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$y=$pdf->GetY();
		$pdf->Rect(5, $y+2, 345, 0.1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		
		
		$y=$pdf->GetY()+5;
		$x=5;
		$nb=$pdf->NbLines(55, utf8_decode('')); $hf=3*$nb;
		$pdf->SetXY($x, $y); $pdf->Cell(15, $hf, '', 0, 1, 'C', 1); $x+=15;
		$pdf->SetXY($x, $y); $pdf->MultiCell(55, 3, '', 0, 'L', 1); $x+=55;
		if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_monto_original, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_aumento, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disminucion, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_modificado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_actual, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
		if ($campos[6]) { 
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_compromiso, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pcompromiso, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[7]) { 
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_causado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pcausado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[8]) { 
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_pagado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($ppagado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[9]) { 
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disponible, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pdisponible, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		break;
		
	
}
//	----------------------------------------
//	FIN CUERPO DE LOS REPORTES
//	----------------------------------------

$pdf->Output();
?>