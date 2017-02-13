<?php
$nombre_archivo = strtr($nombre_archivo, " ", "_"); $nombre_archivo=$nombre_archivo.".xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: filename=\"".$nombre_archivo."\";");
session_start();
extract($_POST);
extract($_GET);
set_time_limit(-1);
ini_set("memory_limit", "200M");
require('../../../conf/conex.php');
Conectarse();
$ahora=date("d-m-Y H:i:s");
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

//	----------------------------------------------------
$titulo="background-color:#999999; font-size:10px; font-weight:bold;";
$cat="background-color:RGB(225, 255, 255); font-size:10px; font-weight:bold;";
$par="font-size:10px; font-weight:bold;";
$gen="font-size:10px; text-decoration:underline;";
$esp="font-size:10px;";
$total="font-size:11px; font-weight:bold;";
?>

<?php
//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Reporte Nuevo...
	case "rendicion_mensual":
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
		echo "
		<table border='1'>
			<tr>
				<td colspan='13'>Fuente de Financiamiento: ".$nom_fuente_financiamiento."</td>
			</tr>
			<tr>
				<td colspan='13'>Tipo de Presupuesto: ".$nom_tipo_presupuesto."</td>
			</tr>
			<tr>
				<td colspan='13'>A&ntilde;o Fiscal: ".$anio_fiscal."</td>
			</tr>
			<tr>
				<td colspan='13'>Mes: ".$nom_mes[$mes]."</td>
			</tr>
			<tr>
				<th width='75' style='$titulo'>PARTIDA</th>
				<th width='825' style='$titulo'>DESCRIPCION</th>
				<th width='100' style='$titulo'>PRESUPUESTARIA ANTERIOR</th>
				<th width='100' style='$titulo'>FINANCIERA ANTERIOR</th>
				<th width='100' style='$titulo'>AUMENTOS</th>
				<th width='100' style='$titulo'>DISMINUCION</th>
				<th width='100' style='$titulo'>MODIFICACIONES</th>
				<th width='100' style='$titulo'>PRESUPUESTO AJUSTADO</th>
				<th width='100' style='$titulo'>COMPROMISO</th>
				<th width='100' style='$titulo'>CAUSADO</th>
				<th width='100' style='$titulo'>DISPONIBILIDAD PRESUPUESTARIA</th>
				<th width='100' style='$titulo'>PAGADO</th>
				<th width='100' style='$titulo'>DISPONIBILIDAD FINANCIERA</th>
			</tr>";
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
				$IdCategoria=$field["IdCategoria"];
				echo "<tr><td colspan='13' style='$cat'>".$field["CodCategoria"]." - ".$field["Unidad"]."</td></tr>";
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
				
				echo "
				<tr>
					<td style='$par'>".$clasificador."</td>
					<td style='$par'>".utf8_decode($field["NomPartida"])."</td>
					<td style='$par' align='right'>=DECIMAL(".number_format($actualant, 2, ',', '')."; 2)</td>
					<td style='$par' align='right'>=DECIMAL(".number_format($actualant, 2, ',', '')."; 2)</td>
					<td style='$par' align='right'>=DECIMAL(".number_format($field["TotalAumento"], 2, ',', '')."; 2)</td>
					<td style='$par' align='right'>=DECIMAL(".number_format($field["TotalDisminucion"], 2, ',', '')."; 2)</td>
					<td style='$par' align='right'>=DECIMAL(".number_format($modificado, 2, ',', '')."; 2)</td>
					<td style='$par' align='right'>=DECIMAL(".number_format($actual, 2, ',', '')."; 2)</td>
					<td style='$par' align='right'>=DECIMAL(".number_format($field["Compromiso"], 2, ',', '')."; 2)</td>
					<td style='$par' align='right'>=DECIMAL(".number_format($field["Causado"], 2, ',', '')."; 2)</td>
					<td style='$par' align='right'>=DECIMAL(".number_format($disponibilidad_presupuestaria, 2, ',', '')."; 2)</td>
					<td style='$par' align='right'>=DECIMAL(".number_format($field["Pagado"], 2, ',', '')."; 2)</td>
					<td style='$par' align='right'>=DECIMAL(".number_format($disponibilidad_financiera, 2, ',', '')."; 2)</td>
				</tr>";
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
				
				echo "
				<tr>
					<td style='$gen'>".$clasificador."</td>
					<td style='$gen'>".utf8_decode($field["NomPartida"])."</td>
					<td style='$gen' align='right'>=DECIMAL(".number_format($actualant, 2, ',', '')."; 2)</td>
					<td style='$gen' align='right'>=DECIMAL(".number_format($actualant, 2, ',', '')."; 2)</td>
					<td style='$gen' align='right'>=DECIMAL(".number_format($field["TotalAumento"], 2, ',', '')."; 2)</td>
					<td style='$gen' align='right'>=DECIMAL(".number_format($field["TotalDisminucion"], 2, ',', '')."; 2)</td>
					<td style='$gen' align='right'>=DECIMAL(".number_format($modificado, 2, ',', '')."; 2)</td>
					<td style='$gen' align='right'>=DECIMAL(".number_format($actual, 2, ',', '')."; 2)</td>
					<td style='$gen' align='right'>=DECIMAL(".number_format($field["Compromiso"], 2, ',', '')."; 2)</td>
					<td style='$gen' align='right'>=DECIMAL(".number_format($field["Causado"], 2, ',', '')."; 2)</td>
					<td style='$gen' align='right'>=DECIMAL(".number_format($disponibilidad_presupuestaria, 2, ',', '')."; 2)</td>
					<td style='$gen' align='right'>=DECIMAL(".number_format($field["Pagado"], 2, ',', '')."; 2)</td>
					<td style='$gen' align='right'>=DECIMAL(".number_format($disponibilidad_financiera, 2, ',', '')."; 2)</td>
				</tr>";
			}
			else if ($field["Tipo"]=="especifica") {
				$aumentoant = $field["MCreditoAnt"] + $field["MReceptoraAnt"] + $field["MRectificacionAnt"];
				$disminucionant +=$field["MDisminucionAnt"]+$field["MCedentesAnt"]+$field["MRectificadoraAnt"];
				$actualant = $field['Formulado'] + $aumentoant - $disminucionant;
				
				if ($field['codordinal'] == "0000") {
					echo "
					<tr>
						<td style='$esp'>".$clasificador."</td>
						<td style='$esp'>".utf8_decode($field["NomPartida"])."</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($actualant, 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($actualant, 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($field["TotalAumento"], 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($field["TotalDisminucion"], 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($modificado, 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($actual, 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($field["Compromiso"], 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($field["Causado"], 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($disponibilidad_presupuestaria, 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($field["Pagado"], 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($disponibilidad_financiera, 2, ',', '')."; 2)</td>
					</tr>";
				} else {
					echo "
					<tr>
						<td style='$esp'>".$clasificador."</td>
						<td style='$esp'>".utf8_decode($field["NomPartida"])."</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($actualant, 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($actualant, 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($field["TotalAumento"], 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($field["TotalDisminucion"], 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($modificado, 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($actual, 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($field["Compromiso"], 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($field["Causado"], 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($disponibilidad_presupuestaria, 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($field["Pagado"], 2, ',', '')."; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(".number_format($disponibilidad_financiera, 2, ',', '')."; 2)</td>
					</tr>";
				}
			}
			$formulado=""; $actual=""; $precompromiso=""; $compromiso=""; $pcompromiso=""; $causado=""; $pcausado=""; $pagado=""; $ppagado=""; $disponible=""; $pdisponible=""; $aumento=""; $disminucion=""; $aumentoant=""; $dp="";
		}
		//	IMPRIMO EL TOTAL
		echo "
		<tr>
			<td colspan='3'>&nbsp;</td>
			<td style='$total' align='right'>=DECIMAL(".number_format($sum_actualant, 2, ',', '')."; 2)</td>
			<td style='$total' align='right'>=DECIMAL(".number_format($sum_aumento, 2, ',', '')."; 2)</td>
			<td style='$total' align='right'>=DECIMAL(".number_format($sum_disminucion, 2, ',', '')."; 2)</td>
			<td style='$total' align='right'>=DECIMAL(".number_format($sum_modificado, 2, ',', '')."; 2)</td>
			<td style='$total' align='right'>=DECIMAL(".number_format($sum_actual, 2, ',', '')."; 2)</td>
			<td style='$total' align='right'>=DECIMAL(".number_format($sum_compromiso, 2, ',', '')."; 2)</td>
			<td style='$total' align='right'>=DECIMAL(".number_format($sum_causado, 2, ',', '')."; 2)</td>
			<td style='$total' align='right'>=DECIMAL(".number_format($sum_disponibilidad_presupuestaria, 2, ',', '')."; 2)</td>
			<td style='$total' align='right'>=DECIMAL(".number_format($sum_pagado, 2, ',', '')."; 2)</td>
			<td style='$total' align='right'>=DECIMAL(".number_format($sum_disponibilidad_financiera, 2, ',', '')."; 2)</td>
		</tr>
		</table>";
		break;
		
	//	Consolidado por Partidas...
	case "consolidado_por_partida":
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
		$filtro = "";
		echo "<table border='1'>";
		if ($financiamiento != "") {
			echo "<tr><td colspan='16'>Fuente de Financiamiento: ".$nom_fuente_financiamiento."</td></tr>";
		}
		if ($tipo_presupuesto != "") {
			echo "<tr><td colspan='16'>Tipo de Presupuesto: ".$nom_tipo_presupuesto."</td></tr>";
		}
		if ($anio_fiscal != "") {
			echo "<tr><td colspan='16'>A&ntilde;o Fiscal: ".$anio_fiscal."</td></tr>";
		}
		echo "<tr>";
		echo "<th width='75' style='$titulo'>PARTIDA</th>";
		echo "<th width='825' style='$titulo'>DESCRIPCION</th>";
		if ($campos[0]) echo "<th width='100' style='$titulo'>ASIG. INICIAL</th>";
		if ($campos[1]) echo "<th width='100' style='$titulo'>AUMENTOS</th>";
		if ($campos[2]) echo "<th width='100' style='$titulo'>DISMINUCION</th>";
		if ($campos[3]) echo "<th width='100' style='$titulo'>MODIFICACIONES</th>";
		if ($campos[4]) echo "<th width='100' style='$titulo'>ASIG. AJUSTADA</th>";
		if ($campos[5]) echo "<th width='100' style='$titulo'>PRE-COMPROMISO</th>";
		if ($campos[6]) echo "<th width='100' style='$titulo'>COMPROMISO</th><th width='50' style='$titulo'>% COMP.</th>";
		if ($campos[7]) echo "<th width='100' style='$titulo'>CAUSADO</th><th width='50' style='$titulo'>% CAU.</th>";
		if ($campos[8]) echo "<th width='100' style='$titulo'>PAGADO</th><th width='50' style='$titulo'>% PAG.</th>";
		if ($campos[9]) echo "<th width='100' style='$titulo'>DISPONIBLE</th><th width='50' style='$titulo'>% DISP.</th>";
		echo "</tr>";
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
				echo "<tr>";
				echo "<td style='$total' colspan='16'>".utf8_decode($clasificador.' '.$field['NomPartida'])."</td>";
				echo "</tr>";
			}
			
			echo "<tr>";
			echo "<td style='$esp'>".$field['CodCategoria']."</td>";
			echo "<td style='$esp'>".utf8_decode($field['Unidad'])."</td>";
			if ($campos[0]) echo "<td style='$esp' align='right'>=DECIMAL(".number_format($field['Formulado'], 2, ',', '')."; 2)</td>";
			if ($campos[1]) echo "<td style='$esp' align='right'>=DECIMAL(".number_format($aumento, 2, ',', '')."; 2)</td>";
			if ($campos[2]) echo "<td style='$esp' align='right'>=DECIMAL(".number_format($disminucion, 2, ',', '')."; 2)</td>";
			if ($campos[3]) echo "<td style='$esp' align='right'>=DECIMAL(".number_format($modificado, 2, ',', '')."; 2)</td>";
			if ($campos[4]) echo "<td style='$esp' align='right'>=DECIMAL(".number_format($actual, 2, ',', '')."; 2)</td>";
			if ($campos[5]) echo "<td style='$esp' align='right'>=DECIMAL(".number_format($precompromiso, 2, ',', '')."; 2)</td>";
			if ($campos[6]) echo "<td style='$esp' align='right'>=DECIMAL(".number_format($compromiso, 2, ',', '')."; 2)</td><td style='$esp' align='right'>".number_format($pcompromiso, 2, ',', '')." %</td>";
			if ($campos[7]) echo "<td style='$esp' align='right'>=DECIMAL(".number_format($causado, 2, ',', '')."; 2)</td><td style='$esp' align='right'>".number_format($pcausad, 2, ',', '')." %</td>";
			if ($campos[8]) echo "<td style='$esp' align='right'>=DECIMAL(".number_format($pagado, 2, ',', '')."; 2)</td><td style='$esp' align='right'>".number_format($ppagado, 2, ',', '')." %</td>";
			if ($campos[9]) echo "<td style='$esp' align='right'>=DECIMAL(".number_format($disponible, 2, ',', '')."; 2)</td><td style='$esp' align='right'>".number_format($pdisponible, 2, ',', '')." %</td>";
			echo "</tr>";
		}
		break;
	
	//	Consolidado agrupado...
	case "consolidado_agrupado":
		$campos = explode("|", $checks);
		//	---------------------------------------------
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//	---------------------------------------------
		if ($idcategoria_desde != "" && $idcategoria_hasta != "") $filtro = "AND (categoria_programatica.idcategoria_programatica >= '".$idcategoria_desde."' AND categoria_programatica.idcategoria_programatica <= '".$idcategoria_hasta."')";
		elseif ($idcategoria_desde != "") $filtro = "AND (categoria_programatica.idcategoria_programatica >= '".$idcategoria_desde."')";
		elseif ($idcategoria_hasta != "") $filtro = "AND categoria_programatica.idcategoria_programatica <= '".$idcategoria_hasta."')";
		//	---------------------------------------------
		$filtro = "";
		echo "<table border='1'>";
		if ($financiamiento != "") {
			$filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."')";
			echo "<tr><td colspan='16'>Fuente de Financiamiento: ".$nom_fuente_financiamiento."</td></tr>";
		}
		if ($tipo_presupuesto != "") {
			$filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."')";
			echo "<tr><td colspan='16'>Tipo de Presupuesto: ".$nom_tipo_presupuesto."</td></tr>";
		}
		if ($anio_fiscal != "") {
			$filtro .= " AND (maestro_presupuesto.anio = '".$anio_fiscal."')";
			echo "<tr><td colspan='16'>A&ntilde;o Fiscal: ".$anio_fiscal."</td></tr>";
		}
		echo "<tr>";
		echo "<th width='75' style='$titulo'>PARTIDA</th>";
		echo "<th width='825' style='$titulo'>DESCRIPCION</th>";
		if ($campos[0]) echo "<th width='100' style='$titulo'>ASIG. INICIAL</th>";
		if ($campos[1]) echo "<th width='100' style='$titulo'>AUMENTOS</th>";
		if ($campos[2]) echo "<th width='100' style='$titulo'>DISMINUCION</th>";
		if ($campos[3]) echo "<th width='100' style='$titulo'>MODIFICACIONES</th>";
		if ($campos[4]) echo "<th width='100' style='$titulo'>ASIG. AJUSTADA</th>";
		if ($campos[10]) echo "<th width='100' style='$titulo'>RESERVADO PARA DISMINUIR</th>";
		if ($campos[5]) echo "<th width='100' style='$titulo'>PRE-COMPROMISO</th>";
		if ($campos[6]) echo "<th width='100' style='$titulo'>COMPROMISO</th><th width='50' style='$titulo'>% COMP.</th>";
		if ($campos[7]) echo "<th width='100' style='$titulo'>CAUSADO</th><th width='50' style='$titulo'>% CAU.</th>";
		if ($campos[8]) echo "<th width='100' style='$titulo'>PAGADO</th><th width='50' style='$titulo'>% PAG.</th>";
		if ($campos[9]) echo "<th width='100' style='$titulo'>DISPONIBLE</th><th width='50' style='$titulo'>% DISP.</th>";
		echo "</tr>";
		//	---------------------------------------------
		$sql = "(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria, 
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
					  (maestro_presupuesto.idordinal=ordinal.idordinal) AND 
					  (maestro_presupuesto.anio='".$anio_fiscal."' AND 
					  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
					  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
				GROUP BY (Par), (Gen), (Esp), (Sesp), (codordinal))
				
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
						'' AS nomordinal
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
					GROUP BY (Par), (Gen), (Esp), (Sesp), (codordinal))
					
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
						'' AS nomordinal
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
					GROUP BY (Par), (Gen), (Esp), (Sesp), (codordinal))
				
				ORDER BY Par, Gen, Esp, Sesp, codordinal";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($j=1; $j<=$rows; $j++) {
			$field = mysql_fetch_array($query);
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];			
			$formulado=number_format($field["Formulado"], 2, ',', '');
			$modificado = number_format(($field['TotalAumento'] - $field['TotalDisminucion']), 2, ',', '');
			$monto_actual = $field['Formulado'] + $field['TotalAumento'] - $field['TotalDisminucion'];
			$actual=number_format($monto_actual, 2, ',', '');
			$compromiso=number_format($field["Compromiso"], 2, ',', '');
			$precompromiso=number_format($field["PreCompromiso"], 2, ',', '');
			$causado=number_format($field["Causado"], 2, ',', '');
			$pagado=number_format($field["Pagado"], 2, ',', '');
			$aumento=number_format($field["TotalAumento"], 2, ',', '');
			$disminucion=number_format($field["TotalDisminucion"], 2, ',', '');
			if ($chkrestar) $resta_disponible = $monto_actual-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"];
			else $resta_disponible = $monto_actual-$field["Compromiso"]-$field["ReservadoDisminuir"];
			$disponible=number_format($resta_disponible, 2, ',', '');
			if ($field["Compromiso"]==0 or $field["Actual"]==0) $pcompromiso="0"; else $pcompromiso=(float) (($field["Compromiso"]*100)/$monto_actual); $pcompromiso=number_format($pcompromiso, 2, ',', '');
			if ($field["Causado"]==0 or $monto_actual==0) $pcausado="0"; else $pcausado=(float) (($field["Causado"]*100)/$monto_actual); $pcausado=number_format($pcausado, 2, ',', '');
			if ($field["Pagado"]==0 or $monto_actual==0) $ppagado="0"; else $ppagado=(float) (($field["Pagado"]*100)/$monto_actual); $ppagado=number_format($ppagado, 2, ',', '');
			if ($resta_disponible==0) $pdisponible="0"; else $pdisponible=(float) ((($resta_disponible)*100)/$monto_actual); 
			$pdisponible=number_format($pdisponible, 2, ',', '');
			if ($field["Tipo"]=="partida") {
				$total_formulado += $field["Formulado"];
				$total_aumento += $field["TotalAumento"];
				$total_disminucion += $field["TotalDisminucion"];
				$total_precompromiso += $field["PreCompromiso"];
				$total_compromiso += $field["Compromiso"];
				$total_causado += $field["Causado"];
				$total_pagado += $field["Pagado"];
				$total_disponible += $resta_disponible;
				
				echo "<tr>";
				echo "<td style='$par'>".$clasificador."</td>";
				echo "<td style='$par'>".utf8_decode($field["NomPartida"])."</td>";
				if ($campos[0]) echo "<td style='$par' align='right'>=DECIMAL(".$formulado."; 2)</td>";
				if ($campos[1]) echo "<td style='$par' align='right'>=DECIMAL(".$aumento."; 2)</td>";
				if ($campos[2]) echo "<td style='$par' align='right'>=DECIMAL(".$disminucion."; 2)</td>";
				if ($campos[3]) echo "<td style='$par' align='right'>=DECIMAL(".$modificado."; 2)</td>";
				if ($campos[4]) echo "<td style='$par' align='right'>=DECIMAL(".$actual."; 2)</td>";
				if ($campos[5]) echo "<td style='$par' align='right'>=DECIMAL(".$precompromiso."; 2)</td>";
				if ($campos[6]) echo "<td style='$par' align='right'>=DECIMAL(".$compromiso."; 2)</td><td style='$par' align='right'>".$pcompromiso." %</td>";
				if ($campos[7]) echo "<td style='$par' align='right'>=DECIMAL(".$causado."; 2)</td><td style='$par' align='right'>".$pcausado." %</td>";
				if ($campos[8]) echo "<td style='$par' align='right'>=DECIMAL(".$pagado."; 2)</td><td style='$par' align='right'>".$ppagado." %</td>";
				if ($campos[9]) echo "<td style='$par' align='right'>=DECIMAL(".$disponible."; 2)</td><td style='$par' align='right'>".$pdisponible." %</td>";
				echo "</tr>";
			}
			elseif ($field["Tipo"]=="generica") {
				echo "<tr>";
				echo "<td style='$gen'>".$clasificador."</td>";
				echo "<td style='$gen'>".utf8_decode($field["NomPartida"])."</td>";
				if ($campos[0]) echo "<td style='$gen' align='right'>=DECIMAL(".$formulado."; 2)</td>";
				if ($campos[1]) echo "<td style='$gen' align='right'>=DECIMAL(".$aumento."; 2)</td>";
				if ($campos[2]) echo "<td style='$gen' align='right'>=DECIMAL(".$disminucion."; 2)</td>";
				if ($campos[3]) echo "<td style='$gen' align='right'>=DECIMAL(".$modificado."; 2)</td>";
				if ($campos[4]) echo "<td style='$gen' align='right'>=DECIMAL(".$actual."; 2)</td>";
				if ($campos[5]) echo "<td style='$gen' align='right'>=DECIMAL(".$precompromiso."; 2)</td>";
				if ($campos[6]) echo "<td style='$gen' align='right'>=DECIMAL(".$compromiso."; 2)</td><td style='$gen' align='right'>".$pcompromiso." %</td>";
				if ($campos[7]) echo "<td style='$gen' align='right'>=DECIMAL(".$causado."; 2)</td><td style='$gen' align='right'>".$pcausado." %</td>";
				if ($campos[8]) echo "<td style='$gen' align='right'>=DECIMAL(".$pagado."; 2)</td><td style='$gen' align='right'>".$ppagado." %</td>";
				if ($campos[9]) echo "<td style='$gen' align='right'>=DECIMAL(".$disponible."; 2)</td><td style='$gen' align='right'>".$pdisponible." %</td>";
				echo "</tr>";
			}
			elseif ($field["Tipo"]=="especifica") {
				if ($field['codordinal'] == "0000") $descripcion = utf8_decode($field["NomPartida"]);
				else $descripcion = utf8_decode($field['codordinal'].' '.$field["nomordinal"]);
				echo "<tr>";
				echo "<td style='$esp'>".$clasificador."</td>";
				echo "<td style='$esp'>".$descripcion."</td>";
				if ($campos[0]) echo "<td style='$esp' align='right'>=DECIMAL(".$formulado."; 2)</td>";
				if ($campos[1]) echo "<td style='$esp' align='right'>=DECIMAL(".$aumento."; 2)</td>";
				if ($campos[2]) echo "<td style='$esp' align='right'>=DECIMAL(".$disminucion."; 2)</td>";
				if ($campos[3]) echo "<td style='$esp' align='right'>=DECIMAL(".$modificado."; 2)</td>";
				if ($campos[4]) echo "<td style='$esp' align='right'>=DECIMAL(".$actual."; 2)</td>";
				if ($campos[5]) echo "<td style='$esp' align='right'>=DECIMAL(".$precompromiso."; 2)</td>";
				if ($campos[6]) echo "<td style='$esp' align='right'>=DECIMAL(".$compromiso."; 2)</td><td style='$esp' align='right'>".$pcompromiso." %</td>";
				if ($campos[7]) echo "<td style='$esp' align='right'>=DECIMAL(".$causado."; 2)</td><td style='$esp' align='right'>".$pcausado." %</td>";
				if ($campos[8]) echo "<td style='$esp' align='right'>=DECIMAL(".$pagado."; 2)</td><td style='$esp' align='right'>".$ppagado." %</td>";
				if ($campos[9]) echo "<td style='$esp' align='right'>=DECIMAL(".$disponible."; 2)</td><td style='$esp' align='right'>".$pdisponible." %</td>";
				echo "</tr>";
			}
		}
		$total_modificado = $total_aumento - $total_disminucion;
		$total_ajustado = $total_formulado + $total_modificado;
		if ($total_compromiso == 0 || $total_ajustado == 0) $total_pcompromiso=0; else $total_pcompromiso=(float) (($total_compromiso*100)/$total_ajustado);
		if ($total_causado == 0 || $total_ajustado == 0) $total_pcausado=0; else $total_pcausado=(float) (($total_causado*100)/$total_ajustado);
		if ($total_pagado == 0 || $total_ajustado == 0) $total_ppagado=0; else $total_ppagado=(float) (($total_pagado*100)/$total_ajustado);
		if ($total_disponible == 0) $total_pdisponible=0; else $total_pdisponible=(float) ((($total_disponible)*100)/$total_ajustado); 
		
		echo "<tr>";
		echo "<td style='$total' colspan='2' align='right'>Total</td>";
		if ($campos[0]) echo "<td style='$total' align='right'>=DECIMAL(".number_format($total_formulado, 2, ',', '')."; 2)</td>";
		if ($campos[1]) echo "<td style='$total' align='right'>=DECIMAL(".number_format($total_aumento, 2, ',', '')."; 2)</td>";
		if ($campos[2]) echo "<td style='$total' align='right'>=DECIMAL(".number_format($total_disminucion, 2, ',', '')."; 2)</td>";
		if ($campos[3]) echo "<td style='$total' align='right'>=DECIMAL(".number_format($total_modificado, 2, ',', '')."; 2)</td>";
		if ($campos[4]) echo "<td style='$total' align='right'>=DECIMAL(".number_format($total_ajustado, 2, ',', '')."; 2)</td>";
		if ($campos[5]) echo "<td style='$total' align='right'>=DECIMAL(".number_format($total_precompromiso, 2, ',', '')."; 2)</td>";
		if ($campos[6]) echo "<td style='$total' align='right'>=DECIMAL(".number_format($total_compromiso, 2, ',', '')."; 2)</td><td style='$total' align='right'>".number_format($total_pcompromiso, 2, ',', '')." %</td>";
		if ($campos[7]) echo "<td style='$total' align='right'>=DECIMAL(".number_format($total_causado, 2, ',', '')."; 2)</td><td style='$total' align='right'>".number_format($total_pcausado, 2, ',', '')." %</td>";
		if ($campos[8]) echo "<td style='$total' align='right'>=DECIMAL(".number_format($total_pagado, 2, ',', '')."; 2)</td><td style='$total' align='right'>".number_format($total_ppagado, 2, ',', '')." %</td>";
		if ($campos[9]) echo "<td style='$total' align='right'>=DECIMAL(".number_format($total_disponible, 2, ',', '')."; 2)</td><td style='$total' align='right'>".number_format($total_pdisponible, 2, ',', '')." %</td>";
		echo "</tr>";
		break;
}
?>