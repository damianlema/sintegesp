<?php
$nombre_archivo = strtr($nombre_archivo, " ", "_");
$nombre_archivo = $nombre_archivo . ".xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: filename=\"" . $nombre_archivo . "\";");
session_start();
set_time_limit(-1);
extract($_GET);
extract($_POST);
ini_set("memory_limit", "200M");
require '../../../conf/conex.php';
require '../firmas.php';
Conectarse();

$ahora = date("d-m-Y H:i:s");
//    ----------------------------------------------------
$nom_mes['01'] = "Enero";
$nom_mes['02'] = "Febrero";
$nom_mes['03'] = "Marzo";
$nom_mes['04'] = "Abril";
$nom_mes['05'] = "Mayo";
$nom_mes['06'] = "Junio";
$nom_mes['07'] = "Julio";
$nom_mes['08'] = "Agosto";
$nom_mes['09'] = "Septiembre";
$nom_mes['10'] = "Octubre";
$nom_mes['11'] = "Noviembre";
$nom_mes['12'] = "Diciembre";
//    ----------------------------------------------------
$dias_mes['01'] = 31;
$dias_mes['03'] = 31;
$dias_mes['04'] = 30;
$dias_mes['05'] = 31;
$dias_mes['06'] = 30;
$dias_mes['07'] = 31;
$dias_mes['08'] = 31;
$dias_mes['09'] = 30;
$dias_mes['10'] = 31;
$dias_mes['11'] = 30;
$dias_mes['12'] = 31;

//    ----------------------------------------------------
$titulo   = "background-color:#999999; font-size:10px; font-weight:bold;";
$cat      = "background-color:RGB(225, 255, 255); font-size:10px; font-weight:bold;";
$cattotal = "background-color:RGB(255, 225, 255); font-size:10px; font-weight:bold;";
$par      = "font-size:10px; font-weight:bold;";
$gen      = "font-size:10px; text-decoration:underline;";
$esp      = "font-size:10px;";
$total    = "font-size:11px; font-weight:bold;";

$sql_config = mysql_query("select * from configuracion, estado where estado.idestado=configuracion.estado");
$config     = mysql_fetch_array($sql_config);

function busca_rango_tiempo($desde, $hasta, $mes_inicial, $mes_final, $filtro, $anio_fiscal)
{

    // IGUAL PROBLEMA SE PRESENTA CON LOS ORDINALES
    $sql = "	(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
					  SUM(maestro_presupuesto.total_aumento - maestro_presupuesto.total_disminucion) AS Modificacion,
					  'subespecifica' AS Tipo,

					  (SELECT SUM(pcad.monto_acreditar)
					FROM partidas_credito_adicional pcad
					INNER JOIN creditos_adicionales cad ON (pcad.idcredito_adicional=cad.idcreditos_adicionales)
						WHERE pcad.idmaestro_presupuesto=IdPresupuesto
							AND cad.fecha_solicitud>='$desde'
      						AND cad.fecha_solicitud<='$hasta'
      						AND cad.estado='procesado') AS MCredito1,

					(SELECT SUM(pdp.monto_debitar)
      					FROM partidas_disminucion_presupuesto pdp
					  INNER JOIN disminucion_presupuesto dip ON (pdp.iddisminucion_presupuesto=dip.iddisminucion_presupuesto)
					  WHERE pdp.idmaestro_presupuesto=IdPresupuesto
							and dip.fecha_solicitud>='$desde'
							AND dip.fecha_solicitud<='$hasta'
							AND dip.estado='procesado') AS MDisminucion1,

					(SELECT SUM(prt.monto_acreditar)
						FROM partidas_receptoras_traslado prt
						INNER JOIN traslados_presupuestarios trpa ON (prt.idtraslados_presupuestarios=trpa.idtraslados_presupuestarios)
						WHERE prt.idmaestro_presupuesto=IdPresupuesto
							AND trpa.fecha_solicitud>='$desde'
							AND trpa.fecha_solicitud<='$hasta'
							AND trpa.estado='procesado') AS MReceptora1,

					(SELECT SUM(pct.monto_debitar)
						FROM partidas_cedentes_traslado pct
						INNER JOIN traslados_presupuestarios trpd ON (pct.idtraslados_presupuestarios=trpd.idtraslados_presupuestarios)
						WHERE pct.idmaestro_presupuesto=IdPresupuesto
							AND trpd.fecha_solicitud>='$desde'
							AND trpd.fecha_solicitud<='$hasta'
							AND trpd.estado='procesado') AS MCedentes1,

					(SELECT SUM(prr.monto_acreditar)
						FROM partidas_receptoras_rectificacion prr
						INNER JOIN rectificacion_presupuesto rpr ON (prr.idrectificacion_presupuesto=rpr.idrectificacion_presupuesto)
						WHERE prr.idmaestro_presupuesto=IdPresupuesto
							AND rpr.fecha_solicitud>='$desde'
							AND rpr.fecha_solicitud<='$hasta'
							AND rpr.estado='procesado') AS MRectificacion1,

					(SELECT SUM(prec.monto_debitar)
						FROM partidas_rectificadoras prec
						INNER JOIN rectificacion_presupuesto rprec ON (prec.idrectificacion_presupuesto=rprec.idrectificacion_presupuesto)
						WHERE prec.idmaestro_presupuesto=IdPresupuesto
							AND rprec.fecha_solicitud>='$desde'
							AND rprec.fecha_solicitud<='$hasta'
							AND rprec.estado='procesado') AS MRectificadora1,

					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionAumento1,

					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionPagados1,

					  (SELECT SUM(pocs.monto)
						FROM partidas_orden_compra_servicio pocs
							INNER JOIN orden_compra_servicio ocs ON (pocs.idorden_compra_servicio=ocs.idorden_compra_servicio)
								WHERE
									pocs.idmaestro_presupuesto = IdPresupuesto AND
									(ocs.estado = 'procesado' OR ocs.estado = 'conformado'
									 OR ocs.estado = 'pagado' OR ocs.estado = 'ordenado' OR ocs.estado = 'parcial') AND
									(ocs.fecha_orden >= '$desde' AND ocs.fecha_orden <= '$hasta')) AS CompraCompromisoI,

					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.compromete = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada'
										or op.estado = 'conformado' or op.estado = 'parcial') AND
									(op.fecha_orden >= '$desde' AND op.fecha_orden <= '$hasta')) AS PagoCompromisoI,

					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.causa = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada'
										or op.estado = 'conformado' or op.estado = 'parcial') AND
									(op.fecha_orden >= '$desde' AND op.fecha_orden <= '$hasta')) AS CausaI,

					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN pagos_financieros pf ON (op.idorden_pago = pf.idorden_pago AND pf.estado <> 'anulado')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada'
										or op.estado = 'conformado' or op.estado = 'parcial') AND
									(pf.fecha_cheque >= '$desde' AND pf.fecha_cheque <= '$hasta')) AS PagadoI,

					  ordinal.codigo AS codordinal,
					  ordinal.denominacion AS nomordinal

				FROM
					  maestro_presupuesto
					  INNER JOIN categoria_programatica ON (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica)
					  INNER JOIN unidad_ejecutora ON (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)
					  INNER JOIN clasificador_presupuestario ON (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario)
					  INNER JOIN ordinal ON (maestro_presupuesto.idordinal = ordinal.idordinal)
				WHERE
					(clasificador_presupuestario.sub_especifica <> '00') and
					$filtro
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				UNION

				(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
					  SUM(maestro_presupuesto.total_aumento - maestro_presupuesto.total_disminucion) AS Modificacion,
					  'especifica' AS Tipo,

					  (SELECT SUM(pcad.monto_acreditar)
					FROM partidas_credito_adicional pcad
					INNER JOIN creditos_adicionales cad ON (pcad.idcredito_adicional=cad.idcreditos_adicionales)
						WHERE pcad.idmaestro_presupuesto=IdPresupuesto
							AND cad.fecha_solicitud>='$desde'
      						AND cad.fecha_solicitud<='$hasta'
      						AND cad.estado='procesado') AS MCredito1,

					(SELECT SUM(pdp.monto_debitar)
      					FROM partidas_disminucion_presupuesto pdp
					  INNER JOIN disminucion_presupuesto dip ON (pdp.iddisminucion_presupuesto=dip.iddisminucion_presupuesto)
					  WHERE pdp.idmaestro_presupuesto=IdPresupuesto
							and dip.fecha_solicitud>='$desde'
							AND dip.fecha_solicitud<='$hasta'
							AND dip.estado='procesado') AS MDisminucion1,

					(SELECT SUM(prt.monto_acreditar)
						FROM partidas_receptoras_traslado prt
						INNER JOIN traslados_presupuestarios trpa ON (prt.idtraslados_presupuestarios=trpa.idtraslados_presupuestarios)
						WHERE prt.idmaestro_presupuesto=IdPresupuesto
							AND trpa.fecha_solicitud>='$desde'
							AND trpa.fecha_solicitud<='$hasta'
							AND trpa.estado='procesado') AS MReceptora1,

					(SELECT SUM(pct.monto_debitar)
						FROM partidas_cedentes_traslado pct
						INNER JOIN traslados_presupuestarios trpd ON (pct.idtraslados_presupuestarios=trpd.idtraslados_presupuestarios)
						WHERE pct.idmaestro_presupuesto=IdPresupuesto
							AND trpd.fecha_solicitud>='$desde'
							AND trpd.fecha_solicitud<='$hasta'
							AND trpd.estado='procesado') AS MCedentes1,

					(SELECT SUM(prr.monto_acreditar)
						FROM partidas_receptoras_rectificacion prr
						INNER JOIN rectificacion_presupuesto rpr ON (prr.idrectificacion_presupuesto=rpr.idrectificacion_presupuesto)
						WHERE prr.idmaestro_presupuesto=IdPresupuesto
							AND rpr.fecha_solicitud>='$desde'
							AND rpr.fecha_solicitud<='$hasta'
							AND rpr.estado='procesado') AS MRectificacion1,

					(SELECT SUM(prec.monto_debitar)
						FROM partidas_rectificadoras prec
						INNER JOIN rectificacion_presupuesto rprec ON (prec.idrectificacion_presupuesto=rprec.idrectificacion_presupuesto)
						WHERE prec.idmaestro_presupuesto=IdPresupuesto
							AND rprec.fecha_solicitud>='$desde'
							AND rprec.fecha_solicitud<='$hasta'
							AND rprec.estado='procesado') AS MRectificadora1,

					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							AND rc.anio='$anio_fiscal'
      						AND (rc.mes>=$mes_inicial AND rc.mes<=$mes_final)) AS MRendicionAumento1,

					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionPagados1,

					  (SELECT SUM(pocs.monto)
						FROM partidas_orden_compra_servicio pocs
							INNER JOIN orden_compra_servicio ocs ON (pocs.idorden_compra_servicio=ocs.idorden_compra_servicio)
								WHERE
									pocs.idmaestro_presupuesto = IdPresupuesto AND
									(ocs.estado = 'procesado' OR ocs.estado = 'conformado'
										OR ocs.estado = 'pagado' OR ocs.estado = 'ordenado' OR ocs.estado = 'parcial') AND
									(ocs.fecha_orden >= '$desde' AND ocs.fecha_orden <= '$hasta')) AS CompraCompromisoI,

					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.compromete = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada'
										or op.estado = 'conformado' or op.estado = 'parcial') AND
									(op.fecha_orden >= '$desde' AND op.fecha_orden <= '$hasta')) AS PagoCompromisoI,

					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.causa = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada'
										or op.estado = 'conformado' or op.estado = 'parcial') AND
									(op.fecha_orden >= '$desde' AND op.fecha_orden <= '$hasta')) AS CausaI,

					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN pagos_financieros pf ON (op.idorden_pago = pf.idorden_pago AND pf.estado <> 'anulado')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada'
										or op.estado = 'conformado' or op.estado = 'parcial') AND
									(pf.fecha_cheque >= '$desde' AND pf.fecha_cheque <= '$hasta')) AS PagadoI,

					  ordinal.codigo AS codordinal,
					  ordinal.denominacion AS nomordinal
				FROM
					  maestro_presupuesto
					  INNER JOIN categoria_programatica ON (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica)
					  INNER JOIN unidad_ejecutora ON (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)
					  INNER JOIN clasificador_presupuestario ON (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario)
					  INNER JOIN ordinal ON (maestro_presupuesto.idordinal = ordinal.idordinal)
				WHERE
					(clasificador_presupuestario.sub_especifica='00') and
					$filtro
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
						SUM(maestro_presupuesto.total_aumento - maestro_presupuesto.total_disminucion) AS Modificacion,
						'generica' AS Tipo,
						'' AS MCredito1,
						'' AS MDisminucion1,
						'' AS MReceptora1,
						'' AS MCedentes1,
						'' as MRectificacion1,
						'' AS MRectificadora1,
						'' as MRendicionAumento1,
						'' as MRendicionDisminucion1,
						'' as MRendicionCompromiso1,
						'' as MRendicionCausado1,
						'' as MRendicionPagados1,
						'' AS CompraCompromisoI,
						'' AS PagoCompromisoI,
						'' AS CausaI,
						'' AS PagadoI,
						'0000' AS codordinal,
						'' AS nomordinal
				FROM
					  maestro_presupuesto
					  INNER JOIN categoria_programatica ON (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica)
					  INNER JOIN unidad_ejecutora ON (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)
					  INNER JOIN clasificador_presupuestario ON (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario)
				WHERE
					(clasificador_presupuestario.sub_especifica='00') and
					$filtro
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
						SUM(maestro_presupuesto.total_aumento - maestro_presupuesto.total_disminucion) AS Modificacion,
						'partida' AS Tipo,
						'' AS MCredito1,
						'' AS MDisminucion1,
						'' AS MReceptora1,
						'' AS MCedentes1,
						'' as MRectificacion1,
						'' AS MRectificadora1,
						'' as MRendicionAumento1,
						'' as MRendicionDisminucion1,
						'' as MRendicionCompromiso1,
						'' as MRendicionCausado1,
						'' as MRendicionPagados1,
						'' AS CompraCompromisoI,
						'' AS PagoCompromisoI,
						'' AS CausaI,
						'' AS PagadoI,
						'0000' AS codordinal,
						'' AS nomordinal
				FROM
					  maestro_presupuesto
					  INNER JOIN categoria_programatica ON (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica)
					  INNER JOIN unidad_ejecutora ON (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)
					  INNER JOIN clasificador_presupuestario ON (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario)
				WHERE
					(clasificador_presupuestario.sub_especifica='00') and
					$filtro
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";

    return $sql;
}

//    ----------------------------------------
//    CUERPO DE LOS REPORTES
//    ----------------------------------------
switch ($nombre) {
    //    Presupuesto Original...
    case "preori":
        $sql = "SELECT
					denominacion As TipoPresupuesto,
					(SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento = '" . $financiamiento . "') AS FuenteFinanciamiento
				FROM tipo_presupuesto
				WHERE idtipo_presupuesto = '" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------
        $filtro = "";
        echo "<table border='1'>";
        if ($financiamiento != "") {
            $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '" . $financiamiento . "')";
            echo "<tr><td colspan='3'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>";
        }
        if ($tipo_presupuesto != "") {
            $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
            echo "<tr><td colspan='3'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>";
        }
        if ($anio_fiscal != "") {
            $filtro .= " AND (maestro_presupuesto.anio = '" . $anio_fiscal . "')";
            echo "<tr><td colspan='3'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>";
        }
        echo "
		<tr>
			<th width='75' style='$titulo'>PARTIDA</th>
			<th width='825' style='$titulo'>DESCRIPCION</th>
			<th width='100' style='$titulo'>MONTO ORIGINAL</th>
		</tr>";
        //---------------------------------------------
        $sum_total = 0;
        if ($idcategoria_programatica != "") {
            $filtro .= " AND (maestro_presupuesto.idcategoria_programatica = '" . $idcategoria_programatica . "')";
        }

        //---------------------------------------------
        $sql = "(SELECT
					maestro_presupuesto.idcategoria_programatica AS IdCategoria,
					maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
					categoria_programatica.codigo AS CodCategoria,
					unidad_ejecutora.denominacion AS Unidad,
					clasificador_presupuestario.partida AS Par,
					clasificador_presupuestario.generica AS Gen,
					clasificador_presupuestario.especifica AS Esp,
					clasificador_presupuestario.sub_especifica AS Sesp,
					clasificador_presupuestario.denominacion AS NomPartida,
					maestro_presupuesto.idRegistro AS IdPresupuesto,
					maestro_presupuesto.monto_original AS MontoOriginal,
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
					((maestro_presupuesto.idcategoria_programatica = categoria_programatica.idcategoria_programatica) AND
					 (categoria_programatica.idunidad_ejecutora = unidad_ejecutora.idunidad_ejecutora) AND
					 (maestro_presupuesto.idclasificador_presupuestario = clasificador_presupuestario.idclasificador_presupuestario) AND
					 (maestro_presupuesto.idordinal = ordinal.idordinal) $filtro)
				 GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				UNION

				(SELECT
					maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
					SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
					'generica' AS Tipo,
					'0000' AS codordinal,
					'' AS nomordinal
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
					 (ordinal.codigo='0000' AND maestro_presupuesto.idordinal = ordinal.idordinal) $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				UNION

				(SELECT
					maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
					SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
					'partida' AS Tipo,
					'0000' AS codordinal,

					'' AS nomordinal
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
					 (ordinal.codigo='0000' AND maestro_presupuesto.idordinal = ordinal.idordinal) $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            //    SI CAMBIA DE CATEGORIA LA IMPRIMO
            if ($field["IdCategoria"] != $IdCategoria) {
                $IdCategoria = $field["IdCategoria"];
                echo "<tr><td colspan='3' style='$cat'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>";
            }
            $clasificador   = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $monto_original = number_format($field["MontoOriginal"], 2, ',', '.');
            if ($field["Tipo"] == "partida") {
                $sum_total += $field["MontoOriginal"];
                echo "
				<tr>
					<td style='$par'>" . $clasificador . "</td>
					<td style='$par'>" . utf8_decode($field["NomPartida"]) . "</td>
					<td style='$par' align='right'>" . $monto_original . "</td>
				</tr>";
            } else if ($field["Tipo"] == "generica") {
                echo "
				<tr>
					<td style='$gen'>" . $clasificador . "</td>
					<td style='$gen'>" . utf8_decode($field["NomPartida"]) . "</td>
					<td style='$gen' align='right'>" . $monto_original . "</td>
				</tr>";
            } else if ($field["Tipo"] == "especifica") {
                if ($field['codordinal'] == '0000') {
                    echo "
					<tr>
						<td style='$esp'>" . $clasificador . "</td>
						<td style='$esp'>" . utf8_decode($field["NomPartida"]) . "</td>
						<td style='$esp' align='right'>" . $monto_original . "</td>
					</tr>";
                } else {
                    echo "
					<tr>
						<td style='$esp'>" . $clasificador . "</td>
						<td style='$esp'>" . utf8_decode($field['codordinal'] . ' ' . $field["nomordinal"]) . "</td>
						<td style='$esp' align='right'>" . $monto_original . "</td>
					</tr>";
                }
            }
        }
        //---------------------------------------------
        //    IMPRIMO EL TOTAL
        $sum_total = number_format($sum_total, 2, ',', '.');
        echo "
			<tr>
				<td colspan='2'>&nbsp;</td>
				<td align='right' align='right' style='$total'>" . $sum_total . "</td>
			</tr>
		</table>";
        break;

    //    Resumen por Categorias...
    case "preres":
        $sql = "SELECT
					denominacion As TipoPresupuesto,
					(SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento = '" . $financiamiento . "') AS FuenteFinanciamiento
				FROM tipo_presupuesto
				WHERE idtipo_presupuesto = '" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------
        $campos = explode("|", $checks);
        //---------------------------------------------
        $filtro = "";
        echo "<table border='1'>";
        if ($financiamiento != "") {
            $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '" . $financiamiento . "')";
            echo "<tr><td colspan='17'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>";
        }
        if ($tipo_presupuesto != "") {
            $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
            echo "<tr><td colspan='17'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>";
        }
        if ($anio_fiscal != "") {
            $filtro .= " AND (maestro_presupuesto.anio = '" . $anio_fiscal . "')";
            echo "<tr><td colspan='17'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>";
        }
        echo "<tr>";
        echo "<th width='75' style='$titulo'>PARTIDA</th>";
        echo "<th width='825' style='$titulo'>DESCRIPCION</th>";
        if ($campos[0]) {
            echo "<th width='100' style='$titulo'>ASIG. INICIAL</th>";
        }

        if ($campos[1]) {
            echo "<th width='100' style='$titulo'>AUMENTOS</th>";
        }

        if ($campos[2]) {
            echo "<th width='100' style='$titulo'>DISMINUCION</th>";
        }

        if ($campos[3]) {
            echo "<th width='100' style='$titulo'>MODIFICACIONES</th>";
        }

        if ($campos[4]) {
            echo "<th width='100' style='$titulo'>ASIG. AJUSTADA</th>";
        }

        if ($campos[10]) {
            echo "<th width='100' style='$titulo'>RESERVADO PARA DISMINUIR</th>";
        }

        if ($campos[5]) {
            echo "<th width='100' style='$titulo'>PRE-COMPROMISO</th>";
        }

        if ($campos[6]) {
            echo "<th width='100' style='$titulo'>COMPROMISO</th><th width='50' style='$titulo'>% COMP.</th>";
        }

        if ($campos[7]) {
            echo "<th width='100' style='$titulo'>CAUSADO</th><th width='50' style='$titulo'>% CAU.</th>";
        }

        if ($campos[8]) {
            echo "<th width='100' style='$titulo'>PAGADO</th><th width='50' style='$titulo'>% PAG.</th>";
        }

        if ($campos[9]) {
            echo "<th width='100' style='$titulo'>DISPONIBLE</th><th width='50' style='$titulo'>% DISP.</th>";
        }

        echo "</tr>";
        //---------------------------------------------
        if ($idcategoria_programatica != "") {
            $filtro .= " AND (maestro_presupuesto.idcategoria_programatica = '" . $idcategoria_programatica . "')";
        }

        //------------------------------------------------
        $sql = "(SELECT
						maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
						(maestro_presupuesto.idordinal=ordinal.idordinal) $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				UNION

				(SELECT
						maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
						clasificador_presupuestario,
						ordinal
				WHERE
						((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						(clasificador_presupuestario.sub_especifica='00') AND
						(ordinal.codigo='0000' AND maestro_presupuesto.idordinal = ordinal.idordinal) $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				UNION

				(SELECT
						maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
						clasificador_presupuestario,
						ordinal
				WHERE
						((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						(clasificador_presupuestario.sub_especifica='00') AND
						(ordinal.codigo='0000' AND maestro_presupuesto.idordinal = ordinal.idordinal) $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            $clasificador  = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $formulado     = number_format($field["Formulado"], 2, ',', '');
            $modificado    = number_format(($field['TotalAumento'] - $field['TotalDisminucion']), 2, ',', '');
            $monto_actual  = $field['Formulado'] + $field['TotalAumento'] - $field['TotalDisminucion'];
            $actual        = number_format($monto_actual, 2, ',', '');
            $compromiso    = number_format($field["Compromiso"], 2, ',', '');
            $precompromiso = number_format($field["PreCompromiso"], 2, ',', '');
            $causado       = number_format($field["Causado"], 2, ',', '');
            $pagado        = number_format($field["Pagado"], 2, ',', '');
            $aumento       = number_format($field["TotalAumento"], 2, ',', '');
            $disminucion   = number_format($field["TotalDisminucion"], 2, ',', '');
            $reservado     = number_format($field["ReservadoDisminuir"], 2, ',', '');
            if ($chkrestar) {
                $resta_disponible = $monto_actual - $field["PreCompromiso"] - $field["Compromiso"] - $field["ReservadoDisminuir"];
            } else {
                $resta_disponible = $monto_actual - $field["Compromiso"] - $field["ReservadoDisminuir"];
            }

            $disponible = number_format($resta_disponible, 2, ',', '');
            if ($field["Compromiso"] == 0 or $field["Actual"] == 0) {
                $pcompromiso = "0";
            } else {
                $pcompromiso = (float) (($field["Compromiso"] * 100) / $monto_actual);
            }

            $pcompromiso = number_format($pcompromiso, 2, ',', '');
            if ($field["Causado"] == 0 or $monto_actual == 0) {
                $pcausado = "0";
            } else {
                $pcausado = (float) (($field["Causado"] * 100) / $monto_actual);
            }

            $pcausado = number_format($pcausado, 2, ',', '');
            if ($field["Pagado"] == 0 or $monto_actual == 0) {
                $ppagado = "0";
            } else {
                $ppagado = (float) (($field["Pagado"] * 100) / $monto_actual);
            }

            $ppagado = number_format($ppagado, 2, ',', '');
            if ($field["Compromiso"] == 0 or $monto_actual == 0) {
                $pdisponible = "0";
            } else
            if ($resta_disponible == 0) {
                $pdisponible = "0";
            } else {
                $pdisponible = (float) ((($monto_actual - $field["Compromiso"]) * 100) / $monto_actual);
            }

            $pdisponible = number_format($pdisponible, 2, ',', '');
            //    SI CAMBIA DE CATEGORIA LA IMPRIMO
            if ($field["IdCategoria"] != $IdCategoria) {
                $IdCategoria = $field["IdCategoria"];
                echo "<tr><td colspan='16' style='$cat'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>";
            }
            if ($field["Tipo"] == "partida") {
                if (($chksobregiradas && $resta_disponible < 0) || !$chksobregiradas) {
                    $sum_formulado += $field["Formulado"];
                    $resta_modificado = $field['TotalAumento'] - $field['TotalDisminucion'];
                    $sum_modificado += $resta_modificado;
                    $sum_actual += $monto_actual;
                    $sum_compromiso += $field["Compromiso"];
                    $sum_precompromiso += $field["PreCompromiso"];
                    $sum_causado += $field["Causado"];
                    $sum_pagado += $field["Pagado"];
                    $sum_aumento += $field["TotalAumento"];
                    $sum_disminucion += $field["TotalDisminucion"];
                    $sum_reservado += $field["ReservadoDisminuir"];
                    $sum_disponible += $resta_disponible;
                    echo "<tr>";
                    echo "<td style='$par'>" . $clasificador . "</td>";
                    echo "<td style='$par'>" . utf8_decode($field["NomPartida"]) . "</td>";
                    if ($campos[0]) {
                        echo "<td style='$par' align='right'>=DECIMAL(" . $formulado . "; 2)</td>";
                    }

                    if ($campos[1]) {
                        echo "<td style='$par' align='right'>=DECIMAL(" . $aumento . "; 2)</td>";
                    }

                    if ($campos[2]) {
                        echo "<td style='$par' align='right'>=DECIMAL(" . $disminucion . "; 2)</td>";
                    }

                    if ($campos[3]) {
                        echo "<td style='$par' align='right'>=DECIMAL(" . $modificado . "; 2)</td>";
                    }

                    if ($campos[4]) {
                        echo "<td style='$par' align='right'>=DECIMAL(" . $actual . "; 2)</td>";
                    }

                    if ($campos[10]) {
                        echo "<td style='$par' align='right'>=DECIMAL(" . $reservado . "; 2)</td>";
                    }

                    if ($campos[5]) {
                        echo "<td style='$par' align='right'>=DECIMAL(" . $precompromiso . "; 2)</td>";
                    }

                    if ($campos[6]) {
                        echo "<td style='$par' align='right'>=DECIMAL(" . $compromiso . "; 2)</td><td style='$par' align='right'>" . $pcompromiso . " %</td>";
                    }

                    if ($campos[7]) {
                        echo "<td style='$par' align='right'>=DECIMAL(" . $causado . "; 2)</td><td style='$par' align='right'>" . $pcausado . " %</td>";
                    }

                    if ($campos[8]) {
                        echo "<td style='$par' align='right'>=DECIMAL(" . $pagado . "; 2)</td><td style='$par' align='right'>" . $ppagado . " %</td>";
                    }

                    if ($campos[9]) {
                        echo "<td style='$par' align='right'>=DECIMAL(" . $disponible . "; 2)</td><td style='$par' align='right'>" . $pdisponible . " %</td>";
                    }

                    echo "</tr>";
                }
            } else if ($field["Tipo"] == "generica") {
                if (($chksobregiradas && $resta_disponible < 0) || !$chksobregiradas) {
                    echo "<tr>";
                    echo "<td style='$gen'>" . $clasificador . "</td>";
                    echo "<td style='$gen'>" . utf8_decode($field["NomPartida"]) . "</td>";
                    if ($campos[0]) {
                        echo "<td style='$gen' align='right'>=DECIMAL(" . $formulado . "; 2)</td>";
                    }

                    if ($campos[1]) {
                        echo "<td style='$gen' align='right'>=DECIMAL(" . $aumento . "; 2)</td>";
                    }

                    if ($campos[2]) {
                        echo "<td style='$gen' align='right'>=DECIMAL(" . $disminucion . "; 2)</td>";
                    }

                    if ($campos[3]) {
                        echo "<td style='$gen' align='right'>=DECIMAL(" . $modificado . "; 2)</td>";
                    }

                    if ($campos[4]) {
                        echo "<td style='$gen' align='right'>=DECIMAL(" . $actual . "; 2)</td>";
                    }

                    if ($campos[10]) {
                        echo "<td style='$gen' align='right'>=DECIMAL(" . $reservado . "; 2)</td>";
                    }

                    if ($campos[5]) {
                        echo "<td style='$gen' align='right'>=DECIMAL(" . $precompromiso . "; 2)</td>";
                    }

                    if ($campos[6]) {
                        echo "<td style='$gen' align='right'>=DECIMAL(" . $compromiso . "; 2)</td><td style='$gen' align='right'>" . $pcompromiso . " %</td>";
                    }

                    if ($campos[7]) {
                        echo "<td style='$gen' align='right'>=DECIMAL(" . $causado . "; 2)</td><td style='$gen' align='right'>" . $pcausado . " %</td>";
                    }

                    if ($campos[8]) {
                        echo "<td style='$gen' align='right'>=DECIMAL(" . $pagado . "; 2)</td><td style='$gen' align='right'>" . $ppagado . " %</td>";
                    }

                    if ($campos[9]) {
                        echo "<td style='$gen' align='right'>=DECIMAL(" . $disponible . "; 2)</td><td style='$gen' align='right'>" . $pdisponible . " %</td>";
                    }

                    echo "</tr>";
                }
            } else if ($field["Tipo"] == "especifica") {
                if (($chksobregiradas && $resta_disponible < 0) || !$chksobregiradas) {
                    if ($field['codordinal'] == "0000") {
                        $descripcion = utf8_decode($field["NomPartida"]);
                    } else {
                        $descripcion = utf8_decode($field["nomordinal"]);
                    }

                    echo "<tr>";
                    echo "<td style='$esp'>" . $clasificador . "</td>";
                    echo "<td style='$esp'>" . $descripcion . "</td>";
                    if ($campos[0]) {
                        echo "<td style='$esp' align='right'>=DECIMAL(" . $formulado . "; 2)</td>";
                    }

                    if ($campos[1]) {
                        echo "<td style='$esp' align='right'>=DECIMAL(" . $aumento . "; 2)</td>";
                    }

                    if ($campos[2]) {
                        echo "<td style='$esp' align='right'>=DECIMAL(" . $disminucion . "; 2)</td>";
                    }

                    if ($campos[3]) {
                        echo "<td style='$esp' align='right'>=DECIMAL(" . $modificado . "; 2)</td>";
                    }

                    if ($campos[4]) {
                        echo "<td style='$esp' align='right'>=DECIMAL(" . $actual . "; 2)</td>";
                    }

                    if ($campos[10]) {
                        echo "<td style='$esp' align='right'>=DECIMAL(" . $reservado . "; 2)</td>";
                    }

                    if ($campos[5]) {
                        echo "<td style='$esp' align='right'>=DECIMAL(" . $precompromiso . "; 2)</td>";
                    }

                    if ($campos[6]) {
                        echo "<td style='$esp' align='right'>=DECIMAL(" . $compromiso . "; 2)</td><td style='$esp' align='right'>" . $pcompromiso . " %</td>";
                    }

                    if ($campos[7]) {
                        echo "<td style='$esp' align='right'>=DECIMAL(" . $causado . "; 2)</td><td style='$esp' align='right'>" . $pcausado . " %</td>";
                    }

                    if ($campos[8]) {
                        echo "<td style='$esp' align='right'>=DECIMAL(" . $pagado . "; 2)</td><td style='$esp' align='right'>" . $ppagado . " %</td>";
                    }

                    if ($campos[9]) {
                        echo "<td style='$esp' align='right'>=DECIMAL(" . $disponible . "; 2)</td><td style='$esp' align='right'>" . $pdisponible . " %</td>";
                    }

                    echo "</tr>";
                }
            }
            $formulado     = "";
            $actual        = "";
            $precompromiso = "";
            $compromiso    = "";
            $pcompromiso   = "";
            $causado       = "";
            $pcausado      = "";
            $pagado        = "";
            $ppagado       = "";
            $disponible    = "";
            $pdisponible   = "";
            $reservado     = "";
        }
        //------------------------------------------------
        if ($sum_compromiso == 0 or $sum_actual == 0) {
            $tpcompromiso = 0;
        } else {
            $tpcompromiso = (float) (($sum_compromiso * 100) / $sum_actual);
        }

        $tpcompromiso = number_format($tpcompromiso, 2, ',', '');
        if ($sum_causado == 0 or $sum_actual) {
            $tpcausado = 0;
        } else {
            $tpcausado = (float) (($sum_causado * 100) / $sum_actual);
        }

        $tpcausado = number_format($tpcausado, 2, ',', '');
        if ($sum_pagado == 0 or $sum_actual == 0) {
            $tppagado = 0;
        } else {
            $tppagado = (float) (($sum_pagado * 100) / $sum_actual);
        }

        $tppagado = number_format($tppagado, 2, ',', '');
        if ($sum_disponible == 0 or $sum_actual == 0) {
            $tpdisponible = 0;
        } else {
            $tpdisponible = (float) (($sum_disponible * 100) / $sum_actual);
        }

        $tpdisponible      = number_format($tpdisponible, 2, ',', '');
        $sum_formulado     = number_format($sum_formulado, 2, ',', '');
        $sum_modificado    = number_format($sum_modificado, 2, ',', '');
        $sum_actual        = number_format($sum_actual, 2, ',', '');
        $sum_precompromiso = number_format($sum_precompromiso, 2, ',', '');
        $sum_compromiso    = number_format($sum_compromiso, 2, ',', '');
        $sum_causado       = number_format($sum_causado, 2, ',', '');
        $sum_pagado        = number_format($sum_pagado, 2, ',', '');
        $sum_aumento       = number_format($sum_aumento, 2, ',', '');
        $sum_disminucion   = number_format($sum_disminucion, 2, ',', '');
        $sum_disponible    = number_format($sum_disponible, 2, ',', '');
        $sum_reservado     = number_format($sum_reservado, 2, ',', '');

        //    IMPRIMO LOS TOTALES
        echo "<tr>";
        echo "<td style='$total'></td>";
        echo "<td style='$total'></td>";
        if ($campos[0]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . $sum_formulado . "; 2)</td>";
        }

        if ($campos[1]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . $sum_aumento . "; 2)</td>";
        }

        if ($campos[2]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . $sum_disminucion . "; 2)</td>";
        }

        if ($campos[3]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . $sum_modificado . "; 2)</td>";
        }

        if ($campos[4]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . $sum_actual . "; 2)</td>";
        }

        if ($campos[10]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . $sum_reservado . "; 2)</td>";
        }

        if ($campos[5]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . $sum_precompromiso . "; 2)</td>";
        }

        if ($campos[6]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . $sum_compromiso . "; 2)</td><td style='$esp' align='right'>" . $tpcompromiso . " %</td>";
        }

        if ($campos[7]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . $sum_causado . "; 2)</td><td style='$esp' align='right'>" . $tpcausado . " %</td>";
        }

        if ($campos[8]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . $sum_pagado . "; 2)</td><td style='$esp' align='right'>" . $tppagado . " %</td>";
        }

        if ($campos[9]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . $sum_disponible . "; 2)</td><td style='$esp' align='right'>" . $tpdisponible . " %</td>";
        }

        echo "</tr></table>";
        break;

    //    Resumen Consolidado...
    case "consolidado":
        $trimestre = "00";
        //---------------------------------------------

        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:14px; color:#000000; font-weight:bold; border-bottom-width: thin; border-bottom: solid #000000;";
        $tr3 = "background-color:#D8D8D8; font-size:12px; color:#000000; font-weight:bold; ";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:14px; color:#000000;";
        //---------------------------------------------
        if ($trimestre == "00") {
            $trimestre_m = '';
        }
        //---------------------------------------------
        $campos = explode("|", $checks);
        //---------------------------------------------
        $sql = "SELECT
					denominacion As TipoPresupuesto,
					(SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento = '" . $financiamiento . "') AS FuenteFinanciamiento
				FROM tipo_presupuesto
				WHERE idtipo_presupuesto = '" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------

        $filtro = "";
        if ($anio_fiscal != "") {
            $filtro .= " AND (maestro_presupuesto.anio = '" . $anio_fiscal . "')";
        }

        if ($financiamiento != "") {
            $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '" . $financiamiento . "')";
        }

        if ($tipo_presupuesto != "") {
            $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
        }

        //---------------------------------------------
        $mes_inicial   = 1;
        $desde_inicial = $anio_fiscal . '-01-01';
        if ($trimestre == '00') {
            $idesde         = '01-01-' . $anio_fiscal;
            $ihasta         = '31-12-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-01-01';
            $fhasta         = $anio_fiscal . '-12-31';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 12;
        }

        //---------------------------------------------
        //    CONSULTO TODO PARA SUMAR LAS ESPECIFICAS
        if ($trimestre == '00') {

            $sql = "SELECT
					maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
													fecha_solicitud >= '" . $fdesde . "' AND
													fecha_solicitud <= '" . $fhasta . "' AND
													estado = 'procesado')) AS Credito,

					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Receptora,


					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_rectificacion
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Rectificacion,


					(SELECT SUM(monto_debitar)
					 FROM partidas_disminucion_presupuesto
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 iddisminucion_presupuesto IN (SELECT iddisminucion_presupuesto
													   FROM disminucion_presupuesto
													   WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Disminucion,


					(SELECT SUM(monto_debitar)
					 FROM partidas_cedentes_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Cedentes,


					(SELECT SUM(monto_debitar)
					 FROM partidas_rectificadoras
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Rectificadora,

					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionAumento1,


					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionPagados1,


					(SELECT SUM(monto)
					 FROM partidas_orden_compra_servicio
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_compra_servicio IN (SELECT idorden_compra_servicio
													 FROM orden_compra_servicio
													 WHERE
														fecha_orden >= '" . $fdesde . "' AND
														fecha_orden <= '" . $fhasta . "' AND
														(estado = 'procesado' OR
														 estado = 'pagado' OR
														 estado = 'conformado' OR
														 estado = 'parcial'))) AS CompraCompromiso,


					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT op1.idorden_pago
										  FROM
											orden_pago op1
											INNER JOIN tipos_documentos td1 ON (op1.tipo = td1.idtipos_documentos AND td1.compromete = 'si')
										  WHERE
											op1.fecha_orden >= '" . $fdesde . "' AND
											op1.fecha_orden <= '" . $fhasta . "' AND
											(op1.estado = 'procesado' OR
											 op1.estado = 'pagada' OR
											 op1.estado = 'conformado' OR
											 op1.estado = 'parcial'))) AS PagoCompromiso,


					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT idorden_pago
										  FROM orden_pago
										  WHERE
											fecha_orden >= '" . $fdesde . "' AND
											fecha_orden <= '" . $fhasta . "' AND
											(estado = 'procesado' OR
											 estado = 'pagada' OR
											 estado = 'conformado' OR
											 estado = 'parcial'))) AS Causado,


					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT idorden_pago
										  FROM pagos_financieros
										  WHERE
											fecha_cheque >= '" . $fdesde . "' AND
											fecha_cheque <= '" . $fhasta . "' AND
											(estado = 'conciliado' OR estado = 'transito'))) AS Pagado,


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
					(categoria_programatica.idunidad_ejecutora = unidad_ejecutora.idunidad_ejecutora) AND
					(maestro_presupuesto.idclasificador_presupuestario = clasificador_presupuestario.idclasificador_presupuestario) AND
					(maestro_presupuesto.idcategoria_programatica = categoria_programatica.idcategoria_programatica) AND
					(maestro_presupuesto.idordinal = ordinal.idordinal) $where_ordinal $filtro
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal)
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
            $query = mysql_query($sql) or die($sql . mysql_error());
            $rows  = mysql_num_rows($query);
            for ($i = 0; $i < $rows; $i++) {
                $field                 = mysql_fetch_array($query);
                $par                   = $field["Par"];
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['Disminucion'] - $field['Cedentes'] + $field['Rectificadora'];
                }
                $_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
                $_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
                $_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
                $_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
                $_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];

            }

        }

        //    CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO    O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
        $sql = "SELECT
					maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
					'0000' AS codordinal,
					'' AS nomordinal
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
					 (ordinal.codigo='0000' AND maestro_presupuesto.idordinal = ordinal.idordinal) $filtro)
				GROUP BY (Par), (Gen), (Esp), (Sesp), (codordinal)
				ORDER BY Par, Gen, Esp, Sesp, codordinal";

        $presupuestaria_anterior = 0;
        $financiera_anterior     = 0;
        $actualf                 = 0;
        $IdCategoria             = '';
        $query                   = mysql_query($sql) or die($sql . mysql_error());
        $rows                    = mysql_num_rows($query);
        for ($i = 0; $i < $rows; $i++) {
            $field = mysql_fetch_array($query);

            if ($i == 0) {
                //---------------------------------------------
                if ($trimestre == '00') {
                    echo "
					<table>
						<tr><td colspan='10' style='$tr3'>CONSOLIDADO GENERAL</td></tr>
						<tr><td colspan='10'>Desde: " . $idesde . " Hasta: " . $ihasta . "</td></tr>
						<tr><td colspan='10'>" . $trimestre_m . "</td></tr>
						<tr><td colspan='10'>&nbsp;</td></tr>
						<tr><td colspan='10'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
						<tr><td colspan='10'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
						<tr><td colspan='10'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
						<tr><td colspan='10'>&nbsp;</td></tr>
						<tr><td colspan='10'>&nbsp;</td></tr>
					</table>";
                    echo "<table>
							<tr>";
                    echo "<th width='100' style='$titulo'>PARTIDA</th>";
                    echo "<th width='500' style='$titulo'>DESCRIPCION</th>";
                    if ($campos[0]) {
                        echo "<th width='100' style='$titulo'>ASIG. INICIAL</th>";
                    }

                    if ($campos[1]) {
                        echo "<th width='100' style='$titulo'>AUMENTO</th>";
                    }

                    if ($campos[2]) {
                        echo "<th width='100' style='$titulo'>DISMINUCION</th>";
                    }

                    if ($campos[3]) {
                        echo "<th width='100' style='$titulo'>MODIFICACION</th>";
                    }

                    if ($campos[4]) {
                        echo "<th width='100' style='$titulo'>ASIG. AJUSTADA</th>";
                    }

                    if ($campos[5]) {
                        echo "<th width='100' style='$titulo'>COMPROMISO</th>";
                    }

                    if ($campos[6]) {
                        echo "<th width='100' style='$titulo'>CAUSADO</th>";
                    }

                    if ($campos[7]) {
                        echo "<th width='100' style='$titulo'>PAGADO</th>";
                    }

                    if ($campos[8]) {
                        echo "<th width='100' style='$titulo'>DISPONIBLE</th>";
                    }

                    echo "</tr>
				        </table>";
                }
            }
            $l++;
            $clasificador = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $par          = $field["Par"];
            $estilo       = $tr5;

            if ($trimestre == '00') {
                $formulado             = $field['Formulado'];
                $formulado_m           = number_format($field['Formulado'], 2, ',', '.');
                $aumento               = $_AUMENTO[$par];
                $aumento_m             = number_format($_AUMENTO[$par], 2, ',', '.');
                $disminucion           = $_DISMINUCION[$par];
                $disminucion_m         = number_format($_DISMINUCION[$par], 2, ',', '.');
                $modificacion          = $_AUMENTO[$par] - $_DISMINUCION[$par];
                $modificacion_m        = number_format($modificacion, 2, ',', '.');
                $actual                = $field['Formulado'] + $modificacion;
                $actual_m              = number_format($actual, 2, ',', '.');
                $total_comprometidoI   = number_format($_COMPROMISO[$par], 2, ',', '.');
                $total_causaI          = number_format($_CAUSADO[$par], 2, ',', '.');
                $disponible_compromiso = number_format(($actual - $_COMPROMISO[$par]), 2, ',', '.');
                $total_pagadoI         = number_format($_PAGADO[$par], 2, ',', '.');
                $disponible_pagado     = number_format(($actual - $_PAGADO[$par]), 2, ',', '.');

                echo "
				<table>
				<tr>
					<td style='" . $estilo . "'>" . $clasificador . "</td>
					<td style='" . $estilo . "'>" . utf8_decode($field["NomPartida"]) . "</td>";
                if ($campos[0]) {
                    echo "
						<td style='" . $estilo . "' align='right'>" . $formulado_m . "</td>";
                }
                if ($campos[1]) {
                    echo "
						<td style='" . $estilo . "' align='right'>" . $aumento_m . "</td>";
                }
                if ($campos[2]) {
                    echo "
						<td style='" . $estilo . "' align='right'>" . $disminucion_m . "</td>";
                }
                if ($campos[3]) {
                    echo "
						<td style='" . $estilo . "' align='right'>" . $modificacion_m . "</td>";
                }
                if ($campos[4]) {
                    echo "
						<td style='" . $estilo . "' align='right'>" . $actual_m . "</td>";
                }
                if ($campos[5]) {
                    echo "
						<td style='" . $estilo . "' align='right'>" . $total_comprometidoI . "</td>";
                }
                if ($campos[6]) {
                    echo "
						<td style='" . $estilo . "' align='right'>" . $total_causaI . "</td>";
                }
                if ($campos[7]) {
                    echo "
						<td style='" . $estilo . "' align='right'>" . $total_pagadoI . "</td>";
                }
                if ($campos[8]) {
                    echo "
						<td style='" . $estilo . "' align='right'>" . $disponible_compromiso . "</td>";
                }
                echo "
					</tr>
					</table>";

            }

            $sum_formulado += $field['Formulado'];
            $sum_presupuestaria_anterior += $presupuestaria_anterior;
            $sum_financiera_anterior += $financiera_anterior;
            $sum_aumento += $_AUMENTO[$par];
            $sum_disminucion += $_DISMINUCION[$par];
            $sum_modificacion += $modificacion;
            $sum_actual += $actual;
            $sum_actualf += $actualf;
            $sum_compromiso += $_COMPROMISO[$par];
            $sum_causado += $_CAUSADO[$par];
            $sum_pagado += $_PAGADO[$par];
        }

        //------------------------------------------------

        if ($trimestre == '00') {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>";
            if ($campos[0]) {
                echo "
						<td style='$tr2' align='right'>" . number_format($sum_formulado, 2, ',', '.') . "</td>";
            }
            if ($campos[1]) {
                echo "
						<td style='$tr2' align='right'>" . number_format($sum_aumento, 2, ',', '.') . "</td>";
            }
            if ($campos[2]) {
                echo "
						<td style='$tr2' align='right'>" . number_format($sum_disminucion, 2, ',', '.') . "</td>";
            }
            if ($campos[3]) {
                echo "
						<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>";
            }
            if ($campos[4]) {
                echo "
						<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>";
            }
            if ($campos[5]) {
                echo "
						<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>";
            }
            if ($campos[6]) {
                echo "
						<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>";
            }
            if ($campos[7]) {
                echo "
						<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>";
            }
            if ($campos[8]) {
                echo "
						<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>";
            }
            echo "
					</tr>
					</table>";
        }

        break;

    //    Consolidado por Categorias...
    case "porsector":
        $campos = explode("|", $checks);
        //---------------------------------------------
        $sql = "SELECT
					denominacion As TipoPresupuesto,
					(SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento = '" . $financiamiento . "') AS FuenteFinanciamiento
				FROM tipo_presupuesto
				WHERE idtipo_presupuesto = '" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------
        $filtro = "";
        echo "<table border='1'>";
        if ($financiamiento != "") {
            $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '" . $financiamiento . "')";
            echo "<tr><td colspan='16'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>";
        }
        if ($tipo_presupuesto != "") {
            $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
            echo "<tr><td colspan='16'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>";
        }
        if ($anio_fiscal != "") {
            $filtro .= " AND (maestro_presupuesto.anio = '" . $anio_fiscal . "')";
            echo "<tr><td colspan='16'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>";
        }
        echo "<tr>";
        echo "<th width='100' style='$titulo'>PARTIDA</th>";
        echo "<th width='500' style='$titulo'>DESCRIPCION</th>";
        if ($campos[0]) {
            echo "<th width='100' style='$titulo'>ASIG. INICIAL</th>";
        }

        if ($campos[1]) {
            echo "<th width='100' style='$titulo'>AUMENTOS</th>";
        }

        if ($campos[2]) {
            echo "<th width='100' style='$titulo'>DISMINUCION</th>";
        }

        if ($campos[3]) {
            echo "<th width='100' style='$titulo'>MODIFICACIONES</th>";
        }

        if ($campos[4]) {
            echo "<th width='100' style='$titulo'>ASIG. AJUSTADA</th>";
        }

        if ($campos[10]) {
            echo "<th width='100' style='$titulo'>RESERVADO DISMINUIR</th>";
        }

        if ($campos[6]) {
            echo "<th width='100' style='$titulo'>COMPROMISO</th><th width='50' style='$titulo'>% COMP.</th>";
        }

        if ($campos[7]) {
            echo "<th width='100' style='$titulo'>CAUSADO</th><th width='50' style='$titulo'>% CAU.</th>";
        }

        if ($campos[8]) {
            echo "<th width='100' style='$titulo'>PAGADO</th><th width='50' style='$titulo'>% PAG.</th>";
        }

        if ($campos[9]) {
            echo "<th width='100' style='$titulo'>DISPONIBLE</th><th width='50' style='$titulo'>% DISP.</th>";
        }

        echo "</tr>";
        //---------------------------------------------
        $filtro = "";
        if ($anio_fiscal != "") {
            $filtro .= " AND (maestro_presupuesto.anio = '" . $anio_fiscal . "')";
        }

        if ($financiamiento != "") {
            $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '" . $financiamiento . "')";
        }

        if ($tipo_presupuesto != "") {
            $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
        }

        if ($idcategoria_programatica != "") {
            $filtro .= " AND (maestro_presupuesto.idcategoria_programatica='" . $idcategoria_programatica . "')";
        }

        //---------------------------------------------
        //    CONSULTO TODO PARA SUMAR LAS ESPECIFICAS
        $sql = "SELECT
					maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
													fecha_solicitud >= '" . $fdesde . "' AND
													fecha_solicitud <= '" . $fhasta . "' AND
													estado = 'procesado')) AS Credito,

					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Receptora,

					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_rectificacion
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Rectificacion,

					(SELECT SUM(monto_debitar)
					 FROM partidas_disminucion_presupuesto
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 iddisminucion_presupuesto IN (SELECT iddisminucion_presupuesto
													   FROM disminucion_presupuesto
													   WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Disminucion,

					(SELECT SUM(monto_debitar)
					 FROM partidas_cedentes_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Cedentes,

					(SELECT SUM(monto_debitar)
					 FROM partidas_rectificadoras
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Rectificadora,

					(SELECT SUM(monto)
					 FROM partidas_orden_compra_servicio
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_compra_servicio IN (SELECT idorden_compra_servicio
													 FROM orden_compra_servicio
													 WHERE
														fecha_orden >= '" . $fdesde . "' AND
														fecha_orden <= '" . $fhasta . "' AND
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
											op1.fecha_orden >= '" . $fdesde . "' AND
											op1.fecha_orden <= '" . $fhasta . "' AND
											(op1.estado = 'procesado' OR op1.estado = 'pagado'))) AS PagoCompromiso,

					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT idorden_pago
										  FROM orden_pago
										  WHERE
											fecha_orden >= '" . $fdesde . "' AND
											fecha_orden <= '" . $fhasta . "' AND
											(estado = 'procesado' OR estado = 'pagada'))) AS Causado,

					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT idorden_pago
										  FROM pagos_financieros
										  WHERE
											fecha_cheque >= '" . $fdesde . "' AND
											fecha_cheque <= '" . $fhasta . "' AND
											(estado = 'conciliado' OR estado = 'transito'))) AS Pagado,

					(SELECT SUM(monto_debitar)
					 FROM partidas_disminucion_presupuesto
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 iddisminucion_presupuesto IN (SELECT iddisminucion_presupuesto
													   FROM disminucion_presupuesto
													   WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
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
					 (ordinal.codigo='0000' AND maestro_presupuesto.idordinal = ordinal.idordinal) $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal)
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        for ($i = 0; $i < $rows; $i++) {
            $field = mysql_fetch_array($query);
            $par   = $field["CodCategoria"] . $field["Par"];
            $_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'];
            $_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'];
            $_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'];
            $_RESERVADO[$par] += $field['ReservadoDisminucion'];
            $_CAUSADO[$par] += $field['Causado'];
            $_PAGADO[$par] += $field['Pagado'];
        }

        //    CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO    O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
        $sql = "SELECT
					maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
					'0000' AS codordinal,
					'' AS nomordinal
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
					 (ordinal.codigo='0000' AND maestro_presupuesto.idordinal = ordinal.idordinal) $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal)
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        for ($i = 0; $i < $rows; $i++) {
            $field = mysql_fetch_array($query);
            //    SI CAMBIA DE CATEGORIA LA IMPRIMO
            if ($field["IdCategoria"] != $IdCategoria) {
                $IdCategoria = $field["IdCategoria"];
                $l           = 1;
                if ($i != 0) {
                    //    IMPRIMO LOS TOTALES
                    if ($sum_compromiso != 0 && $sum_actual != 0) {
                        $tpcompromiso = $sum_compromiso * 100 / $sum_actual;
                    } else {
                        $tpcompromiso = 0;
                    }

                    if ($sum_causado != 0 && $sum_actual != 0) {
                        $tpcausado = $sum_causado * 100 / $sum_actual;
                    } else {
                        $tpcausado = 0;
                    }

                    if ($sum_pagado != 0 && $sum_actual != 0) {
                        $tppagado = $sum_pagado * 100 / $sum_actual;
                    } else {
                        $tppagado = 0;
                    }

                    if ($sum_disponible != 0 && $sum_actual != 0) {
                        $tpdisponible = $sum_disponible * 100 / $sum_actual;
                    } else {
                        $tpdisponible = 0;
                    }

                    echo "<tr>";
                    echo "<td colspan='2'>&nbsp;</td>";
                    if ($campos[0]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_formulado, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[1]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_aumento, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[2]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_disminucion, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[3]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_modificado, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[4]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_actual, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[10]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_reservado, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[6]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_compromiso, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tpcompromiso, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[7]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_causado, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tpcausado, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[8]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_pagado, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tppagado, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[9]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_disponible, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tpdisponible, 2, ',', '') . "; 2)</td>";
                    }

                    echo "</tr>";
                }
                $sum_formulado   = 0;
                $sum_aumento     = 0;
                $sum_disminucion = 0;
                $sum_modificado  = 0;
                $sum_actual      = 0;
                $sum_compromiso  = 0;
                $sum_causado     = 0;
                $sum_pagado      = 0;
                $sum_disponible  = 0;

                echo "<tr><td colspan='16' style='$cat'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>";
            }
            $l++;
            $clasificador = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $par          = $field['CodCategoria'] . $field["Par"];

            $modificacion = $_AUMENTO[$par] - $_DISMINUCION[$par];
            $actual       = $field['Formulado'] + $modificacion;
            $disponible   = $actual - $_COMPROMISO[$par] - $_RESERVADO[$par];

            if ($_COMPROMISO[$par] != 0 && $actual != 0) {
                $pcompromiso = $_COMPROMISO[$par] * 100 / $actual;
            } else {
                $pcompromiso = 0;
            }

            if ($_CAUSADO[$par] != 0 && $actual != 0) {
                $pcausado = $_CAUSADO[$par] * 100 / $actual;
            } else {
                $pcausado = 0;
            }

            if ($_PAGADO[$par] != 0 && $actual != 0) {
                $ppagado = $_PAGADO[$par] * 100 / $actual;
            } else {
                $ppagado = 0;
            }

            if ($disponible != 0 && $actual != 0) {
                $pdisponible = $disponible * 100 / $actual;
            } else {
                $pdisponible = 0;
            }

            echo "<tr>";
            echo "<td style='$par'>" . $clasificador . "</td>";
            echo "<td style='$par'>" . utf8_decode($field["NomPartida"]) . "</td>";
            if ($campos[0]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($field['Formulado'], 2, ',', '') . "; 2)</td>";
            }

            if ($campos[1]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($_AUMENTO[$par], 2, ',', '') . "; 2)</td>";
            }

            if ($campos[2]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($_DISMINUCION[$par], 2, ',', '') . "; 2)</td>";
            }

            if ($campos[3]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($modificacion, 2, ',', '') . "; 2)</td>";
            }

            if ($campos[4]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($actual, 2, ',', '') . "; 2)</td>";
            }

            if ($campos[10]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($_RESERVADO[$par], 2, ',', '') . "; 2)</td>";
            }

            if ($campos[6]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($_COMPROMISO[$par], 2, ',', '') . "; 2)</td><td style='$par' align='right'>=DECIMAL(" . number_format($pcompromiso, 2, ',', '') . "; 2)</td>";
            }

            if ($campos[7]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($_CAUSADO[$par], 2, ',', '') . "; 2)</td><td style='$par' align='right'>=DECIMAL(" . number_format($pcausado, 2, ',', '') . "; 2)</td>";
            }

            if ($campos[8]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($_PAGADO[$par], 2, ',', '') . "; 2)</td><td style='$par' align='right'>=DECIMAL(" . number_format($ppagado, 2, ',', '') . "; 2)</td>";
            }

            if ($campos[9]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($disponible, 2, ',', '') . "; 2)</td><td style='$par' align='right'>=DECIMAL(" . number_format($pdisponible, 2, ',', '') . "; 2)</td>";
            }

            echo "</tr>";

            $sum_formulado += $field['Formulado'];
            $sum_aumento += $_AUMENTO[$par];
            $sum_disminucion += $_DISMINUCION[$par];
            $sum_modificacion += $modificacion;
            $sum_actual += $actual;
            $sum_compromiso += $_COMPROMISO[$par];
            $sum_causado += $_CAUSADO[$par];
            $sum_pagado += $_PAGADO[$par];
            $sum_disponible += $disponible;
        }
        //    IMPRIMO LOS TOTALES
        if ($sum_compromiso != 0 && $sum_actual != 0) {
            $tpcompromiso = $sum_compromiso * 100 / $sum_actual;
        } else {
            $tpcompromiso = 0;
        }

        if ($sum_causado != 0 && $sum_actual != 0) {
            $tpcausado = $sum_causado * 100 / $sum_actual;
        } else {
            $tpcausado = 0;
        }

        if ($sum_pagado != 0 && $sum_actual != 0) {
            $tppagado = $sum_pagado * 100 / $sum_actual;
        } else {
            $tppagado = 0;
        }

        if ($sum_disponible != 0 && $sum_actual != 0) {
            $tpdisponible = $sum_disponible * 100 / $sum_actual;
        } else {
            $tpdisponible = 0;
        }

        echo "<tr>";
        echo "<td colspan='2'>&nbsp;</td>";
        if ($campos[0]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_formulado, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[1]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_aumento, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[2]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_disminucion, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[3]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_modificado, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[4]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_actual, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[10]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_reservado, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[6]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_compromiso, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tpcompromiso, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[7]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_causado, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tpcausado, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[8]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_pagado, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tppagado, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[9]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_disponible, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tpdisponible, 2, ',', '') . "; 2)</td>";
        }

        echo "</tr></table>";
        break;

    //    Consolidado por Sector...
    case "consolidado_sector":
        $campos = explode("|", $checks);
        //---------------------------------------------
        $sql = "SELECT
					denominacion As TipoPresupuesto,
					(SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento = '" . $financiamiento . "') AS FuenteFinanciamiento
				FROM tipo_presupuesto
				WHERE idtipo_presupuesto = '" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------
        $filtro = "";
        echo "<table border='1'>";
        if ($financiamiento != "") {
            $filtro .= " AND (mp.idfuente_financiamiento = '" . $financiamiento . "')";
            echo "<tr><td colspan='16'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>";
        }
        if ($tipo_presupuesto != "") {
            $filtro .= " AND (mp.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
            echo "<tr><td colspan='16'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>";
        }
        if ($anio_fiscal != "") {
            $filtro .= " AND (mp.anio = '" . $anio_fiscal . "')";
            echo "<tr><td colspan='16'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>";
        }
        if ($idsector != "") {
            $filtro .= " AND (c.idsector = '" . $idsector . "')";
        }

        echo "<tr>";
        echo "<th width='100' style='$titulo'>PARTIDA</th>";
        echo "<th width='500' style='$titulo'>DESCRIPCION</th>";
        if ($campos[0]) {
            echo "<th width='100' style='$titulo'>ASIG. INICIAL</th>";
        }

        if ($campos[1]) {
            echo "<th width='100' style='$titulo'>AUMENTOS</th>";
        }

        if ($campos[2]) {
            echo "<th width='100' style='$titulo'>DISMINUCION</th>";
        }

        if ($campos[3]) {
            echo "<th width='100' style='$titulo'>MODIFICACIONES</th>";
        }

        if ($campos[4]) {
            echo "<th width='100' style='$titulo'>ASIG. AJUSTADA</th>";
        }

        if ($campos[10]) {
            echo "<th width='100' style='$titulo'>RESERVADO DISMINUIR</th>";
        }

        if ($campos[6]) {
            echo "<th width='100' style='$titulo'>COMPROMISO</th><th width='50' style='$titulo'>% COMP.</th>";
        }

        if ($campos[7]) {
            echo "<th width='100' style='$titulo'>CAUSADO</th><th width='50' style='$titulo'>% CAU.</th>";
        }

        if ($campos[8]) {
            echo "<th width='100' style='$titulo'>PAGADO</th><th width='50' style='$titulo'>% PAG.</th>";
        }

        if ($campos[9]) {
            echo "<th width='100' style='$titulo'>DISPONIBLE</th><th width='50' style='$titulo'>% DISP.</th>";
        }

        echo "</tr>";
        //---------------------------------------------
        //    CONSULTO TODO PARA SUMAR LAS ESPECIFICAS
        $sql = "SELECT
					cps.idclasificador_presupuestario,
					cps.denominacion AS NombrePartida,
					cps.partida AS Par,
					cps.generica AS Gen,
					cps.especifica AS Esp,
					cps.sub_especifica AS Sesp,
					cp.idcategoria_programatica,
					cp.codigo As CodCategoria,
					cp.idunidad_ejecutora,
					mp.idRegistro,
					mp.idordinal,
					c.idSector,
					c.codigo AS CodSector,
					c.denominacion AS Sector,

					(SELECT SUM(pca_2.monto_acreditar)
					 FROM partidas_credito_adicional pca_2
					 WHERE
						 pca_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pca_2.idcredito_adicional IN (SELECT ca_3.idcreditos_adicionales
														FROM creditos_adicionales ca_3
														WHERE
															ca_3.fecha_solicitud >= '$fdesde' AND
															ca_3.fecha_solicitud <= '$fhasta' AND
															ca_3.estado = 'procesado'))) AS Credito,

					(SELECT SUM(prt_2.monto_acreditar)
					 FROM partidas_receptoras_traslado prt_2
					 WHERE
						 prt_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prt_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$fdesde' AND
																  tp_3.fecha_solicitud <= '$fhasta' AND
																  tp_3.estado = 'procesado'))) AS Receptora,

					(SELECT SUM(prr_2.monto_acreditar)
					 FROM partidas_receptoras_rectificacion prr_2
					 WHERE
						 prr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
																FROM rectificacion_presupuesto rp_3
																WHERE
																  rp_3.fecha_solicitud >= '$fdesde' AND
																  rp_3.fecha_solicitud <= '$fhasta' AND
																  rp_3.estado = 'procesado'))) AS Rectificacion,

					(SELECT SUM(pdp_2.monto_debitar)
					 FROM partidas_disminucion_presupuesto pdp_2
					 WHERE
						 pdp_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pdp_2.iddisminucion_presupuesto IN (SELECT dp_3.iddisminucion_presupuesto
															  FROM disminucion_presupuesto dp_3
															  WHERE
																dp_3.fecha_solicitud >= '$fdesde' AND
																dp_3.fecha_solicitud <= '$fhasta' AND
																dp_3.estado = 'procesado'))) AS Disminucion,

					(SELECT SUM(pct_2.monto_debitar)
					 FROM partidas_cedentes_traslado pct_2
					 WHERE
						 pct_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pct_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$fdesde' AND
																  tp_3.fecha_solicitud <= '$fhasta' AND
																  tp_3.estado = 'procesado'))) AS Cedentes,

					(SELECT SUM(pr_2.monto_debitar)
					 FROM partidas_rectificadoras pr_2
					 WHERE
						 pr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
															   FROM rectificacion_presupuesto rp_3
															   WHERE
																 rp_3.fecha_solicitud >= '$fdesde' AND
																 rp_3.fecha_solicitud <= '$fhasta' AND
																 rp_3.estado = 'procesado'))) AS Rectificadora,

					(SELECT SUM(pocs_2.monto)
					 FROM partidas_orden_compra_servicio pocs_2
					 WHERE
						 pocs_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pocs_2.idorden_compra_servicio IN (SELECT ocs_3.idorden_compra_servicio
															 FROM orden_compra_servicio ocs_3
															 WHERE
															   ocs_3.fecha_orden >= '$fdesde' AND
															   ocs_3.fecha_orden <= '$fhasta' AND
															   (ocs_3.estado = 'procesado' OR
																ocs_3.estado = 'pagado' OR
																ocs_3.estado = 'conformado' OR
																ocs_3.estado = 'parcial')))) AS CompraCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM
												   orden_pago op_3
												   INNER JOIN tipos_documentos td_3 ON (op_3.tipo = td_3.idtipos_documentos AND td_3.compromete = 'si')
												 WHERE
												   op_3.fecha_orden >= '$fdesde' AND
												   op_3.fecha_orden <= '$fhasta' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS PagoCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM orden_pago op_3
												 WHERE
												   op_3.fecha_orden >= '$fdesde' AND
												   op_3.fecha_orden <= '$fhasta' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS Causado,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT pf_3.idorden_pago
												 FROM pagos_financieros pf_3
												 WHERE
												   pf_3.fecha_cheque >= '$fdesde' AND
												   pf_3.fecha_cheque <= '$fhasta' AND
												   (pf_3.estado = 'conciliado' OR
													pf_3.estado = 'transito' OR
													pf_3.estado = 'parcial')))) AS Pagado,

					(SELECT SUM(pdp_2.monto_debitar)
					 FROM partidas_disminucion_presupuesto pdp_2
					 WHERE
						 pdp_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pdp_2.iddisminucion_presupuesto IN (SELECT dp_3.iddisminucion_presupuesto
															  FROM disminucion_presupuesto dp_3
															  WHERE
																dp_3.fecha_solicitud >= '$fdesde' AND
																dp_3.fecha_solicitud <= '$fhasta' AND
																dp_3.estado = 'elaboracion'))) AS ReservadoDisminucion

				FROM
					maestro_presupuesto mp
					INNER JOIN clasificador_presupuestario cps ON (mp.idclasificador_presupuestario = cps.idclasificador_presupuestario)
					INNER JOIN ordinal o ON (mp.idordinal = o.idordinal)
					INNER JOIN categoria_programatica cp ON (mp.idcategoria_programatica = cp.idcategoria_programatica)
					INNER JOIN sector c On (cp.idsector = c.idsector)
				WHERE 1 $filtro
				GROUP BY CodSector, idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal
				ORDER BY CodSector, idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        for ($i = 0; $i < $rows; $i++) {
            $field = mysql_fetch_array($query);
            $par   = $field["CodSector"] . $field["Par"];
            $_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'];
            $_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'];
            $_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'];
            $_RESERVADO[$par] += $field['ReservadoDisminucion'];
            $_CAUSADO[$par] += $field['Causado'];
            $_PAGADO[$par] += $field['Pagado'];
        }

        //    CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO    O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
        $sql = "SELECT
					mp.idcategoria_programatica AS IdCategoria,
					mp.idclasificador_presupuestario AS idPartida,
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
					categoria_programatica.idsector AS IdSector,
					c.codigo AS CodSector,
					c.denominacion AS Sector,
					mp.idRegistro AS IdPresupuesto,
					SUM(mp.monto_original) AS Formulado,
					'0000' AS codordinal,
					'' AS nomordinal
				FROM
					maestro_presupuesto mp,
					categoria_programatica,
					unidad_ejecutora,
					clasificador_presupuestario,
					ordinal,
					sector c
				WHERE
					((mp.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					 (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					 (mp.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
					 (clasificador_presupuestario.sub_especifica='00') AND
					 (ordinal.codigo='0000' AND mp.idordinal = ordinal.idordinal) AND
					 (categoria_programatica.idsector = c.idSector) $filtro)
				GROUP BY (CodSector), (Par), (Gen), (Esp), (Sesp), (codordinal)
				ORDER BY CodSector, Par, Gen, Esp, Sesp, codordinal";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        for ($i = 0; $i < $rows; $i++) {
            $field = mysql_fetch_array($query);
            //    SI CAMBIA DE SECTOR LA IMPRIMO
            if ($field["IdSector"] != $IdSector) {
                $IdSector = $field["IdSector"];
                $l        = 1;
                if ($i != 0) {
                    $sum_modificado = $sum_aumento - $sum_disminucion;
                    $sum_actual     = $sum_formulado + ($sum_modificado);

                    //    IMPRIMO LOS TOTALES
                    if ($sum_compromiso != 0 && $sum_actual != 0) {
                        $tpcompromiso = $sum_compromiso * 100 / $sum_actual;
                    } else {
                        $tpcompromiso = 0;
                    }

                    if ($sum_causado != 0 && $sum_actual != 0) {
                        $tpcausado = $sum_causado * 100 / $sum_actual;
                    } else {
                        $tpcausado = 0;
                    }

                    if ($sum_pagado != 0 && $sum_actual != 0) {
                        $tppagado = $sum_pagado * 100 / $sum_actual;
                    } else {
                        $tppagado = 0;
                    }

                    if ($sum_disponible != 0 && $sum_actual != 0) {
                        $tpdisponible = $sum_disponible * 100 / $sum_actual;
                    } else {
                        $tpdisponible = 0;
                    }

                    echo "<tr>";
                    echo "<td colspan='2'>&nbsp;</td>";
                    if ($campos[0]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_formulado, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[1]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_aumento, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[2]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_disminucion, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[3]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_modificado, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[4]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_actual, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[10]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_reservado, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[6]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_compromiso, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tpcompromiso, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[7]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_causado, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tpcausado, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[8]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_pagado, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tppagado, 2, ',', '') . "; 2)</td>";
                    }

                    if ($campos[9]) {
                        echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_disponible, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tpdisponible, 2, ',', '') . "; 2)</td>";
                    }

                    echo "</tr>";
                }
                $sum_formulado   = 0;
                $sum_aumento     = 0;
                $sum_disminucion = 0;
                $sum_modificado  = 0;
                $sum_actual      = 0;
                $sum_compromiso  = 0;
                $sum_causado     = 0;
                $sum_pagado      = 0;
                $sum_disponible  = 0;
                $sum_reservado   = 0;

                echo "<tr><td colspan='16' style='$cat'>" . $field["CodSector"] . " - " . $field["Sector"] . "</td></tr>";
            }

            $l++;
            $clasificador = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $par          = $field['CodSector'] . $field["Par"];

            $modificacion = $_AUMENTO[$par] - $_DISMINUCION[$par];
            $actual       = $field['Formulado'] + $modificacion;
            $disponible   = $actual - $_COMPROMISO[$par] - $_RESERVADO[$par];

            if ($_COMPROMISO[$par] != 0 && $actual != 0) {
                $pcompromiso = $_COMPROMISO[$par] * 100 / $actual;
            } else {
                $pcompromiso = 0;
            }

            if ($_CAUSADO[$par] != 0 && $actual != 0) {
                $pcausado = $_CAUSADO[$par] * 100 / $actual;
            } else {
                $pcausado = 0;
            }

            if ($_PAGADO[$par] != 0 && $actual != 0) {
                $ppagado = $_PAGADO[$par] * 100 / $actual;
            } else {
                $ppagado = 0;
            }

            if ($disponible != 0 && $actual != 0) {
                $pdisponible = $disponible * 100 / $actual;
            } else {
                $pdisponible = 0;
            }

            echo "<tr>";
            echo "<td style='$par'>" . $clasificador . "</td>";
            echo "<td style='$par'>" . utf8_decode($field["NomPartida"]) . "</td>";
            if ($campos[0]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($field['Formulado'], 2, ',', '') . "; 2)</td>";
            }

            if ($campos[1]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($_AUMENTO[$par], 2, ',', '') . "; 2)</td>";
            }

            if ($campos[2]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($_DISMINUCION[$par], 2, ',', '') . "; 2)</td>";
            }

            if ($campos[3]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($modificacion, 2, ',', '') . "; 2)</td>";
            }

            if ($campos[4]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($actual, 2, ',', '') . "; 2)</td>";
            }

            if ($campos[10]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($_RESERVADO[$par], 2, ',', '') . "; 2)</td>";
            }

            if ($campos[6]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($_COMPROMISO[$par], 2, ',', '') . "; 2)</td><td style='$par' align='right'>=DECIMAL(" . number_format($pcompromiso, 2, ',', '') . "; 2)</td>";
            }

            if ($campos[7]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($_CAUSADO[$par], 2, ',', '') . "; 2)</td><td style='$par' align='right'>=DECIMAL(" . number_format($pcausado, 2, ',', '') . "; 2)</td>";
            }

            if ($campos[8]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($_PAGADO[$par], 2, ',', '') . "; 2)</td><td style='$par' align='right'>=DECIMAL(" . number_format($ppagado, 2, ',', '') . "; 2)</td>";
            }

            if ($campos[9]) {
                echo "<td style='$par' align='right'>=DECIMAL(" . number_format($disponible, 2, ',', '') . "; 2)</td><td style='$par' align='right'>=DECIMAL(" . number_format($pdisponible, 2, ',', '') . "; 2)</td>";
            }

            echo "</tr>";

            $sum_formulado += $field['Formulado'];
            $sum_aumento += $_AUMENTO[$par];
            $sum_disminucion += $_DISMINUCION[$par];
            $sum_modificacion += $modificacion;
            $sum_actual += $actual;
            $sum_reservado += $_RESERVADO[$par];
            $sum_compromiso += $_COMPROMISO[$par];
            $sum_causado += $_CAUSADO[$par];
            $sum_pagado += $_PAGADO[$par];
            $sum_disponible += $disponible;
        }
        //    IMPRIMO LOS TOTALES
        $sum_modificado = $sum_aumento - $sum_disminucion;
        $sum_actual     = $sum_formulado + ($sum_modificado);

        if ($sum_compromiso != 0 && $sum_actual != 0) {
            $tpcompromiso = $sum_compromiso * 100 / $sum_actual;
        } else {
            $tpcompromiso = 0;
        }

        if ($sum_causado != 0 && $sum_actual != 0) {
            $tpcausado = $sum_causado * 100 / $sum_actual;
        } else {
            $tpcausado = 0;
        }

        if ($sum_pagado != 0 && $sum_actual != 0) {
            $tppagado = $sum_pagado * 100 / $sum_actual;
        } else {
            $tppagado = 0;
        }

        if ($sum_disponible != 0 && $sum_actual != 0) {
            $tpdisponible = $sum_disponible * 100 / $sum_actual;
        } else {
            $tpdisponible = 0;
        }

        echo "<tr>";
        echo "<td colspan='2'>&nbsp;</td>";
        if ($campos[0]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_formulado, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[1]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_aumento, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[2]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_disminucion, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[3]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_modificado, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[4]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_actual, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[10]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_reservado, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[6]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_compromiso, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tpcompromiso, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[7]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_causado, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tpcausado, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[8]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_pagado, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tppagado, 2, ',', '') . "; 2)</td>";
        }

        if ($campos[9]) {
            echo "<td style='$total' align='right'>=DECIMAL(" . number_format($sum_disponible, 2, ',', '') . "; 2)</td><td style='$total' align='right'>=DECIMAL(" . number_format($tpdisponible, 2, ',', '') . "; 2)</td>";
        }

        echo "</tr>";
        break;

    //    Detalle por Partida...
    case "detalle_por_partida":
        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr3 = "background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
        $tr4 = "font-size:12px; font-weight:bold;";
        $tr5 = "font-size:12px;";
        $tr6 = "background-color:#999999; font-size:12px; border:1px solid #000000;";
        $tr7 = "font-size:12px; border:1px solid #000000;";

        $tr55  = "font-size:12px; color:#0000FF";
        $tr22  = "font-size:12px; color:#0000FF; font-weight:bold;";
        $tr555 = "font-size:12px; color:#FF0000";
        $tr222 = "font-size:12px; color:#FF0000; font-weight:bold;";
        //---------------------------------------------
        $sql   = "SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='" . $financiamiento . "') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------
        $filtro = " AND (maestro_presupuesto.idcategoria_programatica='" . $_GET['idcategoria'] . "') AND (maestro_presupuesto.idclasificador_presupuestario='" . $_GET['idpartida'] . "')";
        //---------------------------------------------
        if ($idordinal != "") {
            $where_ordinal = " AND maestro_presupuesto.idordinal = '" . $idordinal . "' ";
        }

        //----------------------------------------------------
        //    CONSULTO
        $sql                       = "SELECT * FROM rendicion_cuentas WHERE idcategoria_programatica = '" . $idcategoria . "' AND idtipo_presupuesto = '" . $tipo_presupuesto . "' AND idfuente_financiamiento = '" . $financiamiento . "' AND anio = '" . $anio_fiscal . "'";
        $query_rendicion_comprobar = mysql_query($sql) or die($sql . mysql_error());
        if (mysql_num_rows($query_rendicion_comprobar) != 0) {
            $sql = "SELECT
							 categoria_programatica.codigo,
							 unidad_ejecutora.denominacion as denounidad,
							 clasificador_presupuestario.denominacion,
							 clasificador_presupuestario.partida,
							 clasificador_presupuestario.generica,
							 clasificador_presupuestario.especifica,
							 clasificador_presupuestario.sub_especifica,
							 ordinal.codigo as codordinal,
							 ordinal.denominacion AS nomordinal,
							 maestro_presupuesto.monto_original,
							 SUM(rendicion_cuentas_partidas.disminucion_periodo) As total_disminucion,
							 '0' AS reservado_disminuir,
							 '0' AS pre_compromiso,
							 '0' AS solicitud_aumento,
							 SUM(rendicion_cuentas_partidas.aumento_periodo) as total_aumento,
							 SUM(rendicion_cuentas_partidas.total_compromisos_periodo) as total_compromisos,
							 SUM(rendicion_cuentas_partidas.total_causados_periodo) as total_causados,
							 SUM(rendicion_cuentas_partidas.total_pagados_periodo) as total_pagados,
							 rendicion_cuentas_partidas.idmaestro_presupuesto AS idRegistro
						FROM
							 rendicion_cuentas_partidas,
							 categoria_programatica,
							 unidad_ejecutora,
							 clasificador_presupuestario,
							 ordinal,
							 maestro_presupuesto
						WHERE
							(rendicion_cuentas_partidas.idmaestro_presupuesto = maestro_presupuesto.idRegistro) AND
							 (categoria_programatica.idcategoria_programatica='" . $idcategoria . "' AND
							 categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
							 (clasificador_presupuestario.idclasificador_presupuestario='" . $idpartida . "') AND
							 (maestro_presupuesto.idcategoria_programatica='" . $idcategoria . "' AND
							 maestro_presupuesto.idclasificador_presupuestario='" . $idpartida . "' AND
								(clasificador_presupuestario.sub_especifica='00') AND
							 maestro_presupuesto.idordinal=ordinal.idordinal)
							 $where_ordinal AND
							 (maestro_presupuesto.anio='" . $anio_fiscal . "' AND
							 maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
							 maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "')
						GROUP BY (categoria_programatica.codigo), (clasificador_presupuestario.partida), (clasificador_presupuestario.generica), (clasificador_presupuestario.especifica), (clasificador_presupuestario.sub_especifica)";
            $query        = mysql_query($sql) or die($sql . mysql_error());
            $field        = mysql_fetch_array($query);
            $total_actual = $field['monto_original'] + $field['total_aumento'] - $field['total_disminucion'];
            $total_actual = number_format($total_actual, 2, ',', '');
        } else {
            $sql = "SELECT categoria_programatica.codigo,
					 unidad_ejecutora.denominacion denounidad,
					 clasificador_presupuestario.denominacion,
					 clasificador_presupuestario.partida,
					 clasificador_presupuestario.generica,
					 clasificador_presupuestario.especifica,
					 clasificador_presupuestario.sub_especifica,
					 ordinal.codigo as codordinal,
					 ordinal.denominacion AS nomordinal,
					 maestro_presupuesto.monto_original,
					 maestro_presupuesto.total_disminucion,
					 maestro_presupuesto.reservado_disminuir,
					 maestro_presupuesto.pre_compromiso,
					 maestro_presupuesto.solicitud_aumento,
					 maestro_presupuesto.total_aumento,
					 maestro_presupuesto.monto_actual,
					 maestro_presupuesto.total_compromisos,
					 maestro_presupuesto.total_causados,
					 maestro_presupuesto.total_pagados,
					 maestro_presupuesto.idRegistro
				FROM
					 categoria_programatica,
					 unidad_ejecutora,
					 clasificador_presupuestario,
					 ordinal,
					 maestro_presupuesto
				WHERE
					 (categoria_programatica.idcategoria_programatica='" . $idcategoria . "' AND
					 categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					 (clasificador_presupuestario.idclasificador_presupuestario='" . $idpartida . "') AND
					 (maestro_presupuesto.idcategoria_programatica='" . $idcategoria . "' AND
					 maestro_presupuesto.idclasificador_presupuestario='" . $idpartida . "' AND
					 maestro_presupuesto.idordinal=ordinal.idordinal)
					 $where_ordinal AND
					 (maestro_presupuesto.anio='" . $anio_fiscal . "' AND
					 maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
					 maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "')";
            $query = mysql_query($sql) or die($sql . mysql_error());
            $field = mysql_fetch_array($query);

            $total_actual = number_format($field[15], 2, ',', '');
        }

        $partida = $field['partida'] . "." . $field['generica'] . "." . $field['especifica'] . "." . $field['sub_especifica'] . " " . $field['codordinal'] . " - ";
        if ($idordinal != "") {
            $partida .= $field['nomordinal'];
        } else {
            $partida .= $field['denominacion'];
        }

        $monto_original              = number_format($field[9], 2, ',', '');
        $total_disminucion           = number_format($field[10], 2, ',', '');
        $total_reservado_disminucion = number_format($field[11], 2, ',', '');

        // PRE COMPROMISO

        $total_pre_compromiso = number_format($field[12], 2, ',', '');
        if ($field[12] == 0) {
            $ptotal_precompromiso = 0;
        } else {
            $ptotal_precompromiso = (float) ($field[12] * 100 / $field[15]);
        }

        $ptotal_precompromiso = number_format($ptotal_precompromiso, 2, ',', '');
        $dtotal_precompromiso = (float) ($field[15] - $field[12] - $field[11] - $field[16]);
        $dtotal_precompromiso = number_format($dtotal_precompromiso, 2, ',', '');
        if ($field[12] == 0) {
            $pdtotal_precompromiso = 0;
        } else {
            $pdtotal_precompromiso = (float) (($field[15] - $field[12] - $field[11]) * 100 / $field[15]);
        }

        $pdtotal_precompromiso = number_format($pdtotal_precompromiso, 2, ',', '');

        $total_solicitud_aumento = number_format($field[13], 2, ',', '');
        $total_aumento           = number_format($field[14], 2, ',', '');

        // COMPROMISO

        $total_compromiso = number_format($field[16], 2, ',', '');
        if ($field[16] == 0) {
            $ptotal_compromiso = 0;
        } else {
            $ptotal_compromiso = (float) ($field[16] * 100 / $field[15]);
        }

        $ptotal_compromiso = number_format($ptotal_compromiso, 2, ',', '');
        $dtotal_compromiso = (float) ($field[15] - $field[16] - $field[12] - $field[11]);
        $dtotal_compromiso = number_format($dtotal_compromiso, 2, ',', '');
        if ($field[16] == 0) {
            $ptotal_compromiso = 0;
        } else {
            $pdtotal_compromiso = (float) (($field[15] - $field[16]) * 100 / $field[15]);
        }

        $pdtotal_compromiso = number_format($pdtotal_compromiso, 2, ',', '');

        // CAUSADO

        $total_causado = number_format($field[17], 2, ',', '');
        if ($field[17] == 0) {
            $ptotal_causado = 0;
        } else {
            $ptotal_causado = (float) ($field[17] * 100 / $field[15]);
        }

        $ptotal_causado = number_format($ptotal_causado, 2, ',', '');
        $dtotal_causado = (float) ($field[15] - $field[17]);
        $dtotal_causado = number_format($dtotal_causado, 2, ',', '');
        if ($field[17] == 0) {
            $pdtotal_causado = 0;
        } else {
            $pdtotal_causado = (float) (($field[15] - $field[17]) * 100 / $field[15]);
        }

        $pdtotal_causado = number_format($pdtotal_causado, 2, ',', '');

        // PAGADO

        $total_pagado = number_format($field['total_pagados'], 2, ',', '');
        if ($field['total_pagados'] == 0) {
            $ptotal_pagado = 0;
        } else {
            $ptotal_pagado = (float) ($field['total_pagados'] * 100 / $field['monto_actual']);
        }

        $ptotal_pagado = number_format($ptotal_pagado, 2, ',', '');
        $dtotal_pagado = (float) ($field['monto_actual'] - $field['total_pagados']);
        $dtotal_pagado = number_format($dtotal_pagado, 2, ',', '');
        if ($field['total_pagados'] == 0 || $field['monto_actual'] == 0) {
            $pdtotal_pagado = 0;
        } else {
            $pdtotal_pagado = (float) (($field['monto_actual'] - $field['total_pagados']) * 100 / $field['monto_actual']);
        }

        $pdtotal_pagado = number_format($pdtotal_pagado, 2, ',', '');

        /////////////////////////////
        echo "
		<table>
			<tr>
				<td style='$tr5'>Categoría Programática:</td>
				<td style='$tr2' align='left'>=TEXTO(" . $field['codigo'] . "; \"0000000000\")</td>
				<td style='$tr2' align='left'>" . utf8_decode($field['denounidad']) . "</td>
			</tr>
			<tr>
				<td style='$tr5'>Partida:</td>
				<td style='$tr2' align='left' colspan='5'>" . utf8_decode($partida) . "</td>
			</tr>
			<tr>
				<td style='$tr5'>Monto Original:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $monto_original . "; 2)</td>
			</tr>
			<tr>
				<td style='$tr5'>Total Aumentos:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $total_aumento . "; 2)</td>
			</tr>
			<tr>
				<td style='$tr55'>Total Solicitud Aumento:</td>
				<td style='$tr22' align='right'>=DECIMAL(" . $total_solicitud_aumento . "; 2)</td>
			</tr>
			<tr>
				<td style='$tr5'>Total Disminuciones:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $total_disminucion . "; 2)</td>
			</tr>
			<tr>
				<td style='$tr555'>Total Reservado para Disminuir:</td>
				<td style='$tr222' align='right'>=DECIMAL(" . $total_reservado_disminucion . "; 2)</td>
			</tr>
			<tr>
				<td style='$tr5'>Total Actual:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $total_actual . "; 2)</td>
			</tr>
			<tr>
				<td style='$tr5'>Total PreCompromisos:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $total_pre_compromiso . "; 2)</td>
				<td style='$tr2'>" . $ptotal_precompromiso . "%</td>
				<td style='$tr5' align='right'>Disponible:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $dtotal_precompromiso . "; 2)</td>
				<td style='$tr2'>" . $pdtotal_precompromiso . "%</td>
			</tr>
			<tr>
				<td style='$tr5'>Total Compromisos:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $total_compromiso . "; 2)</td>
				<td style='$tr2'>" . $ptotal_compromiso . "%</td>
				<td style='$tr5' align='right'>Disponible:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $dtotal_compromiso . "; 2)</td>
				<td style='$tr2'>" . $pdtotal_compromiso . "%</td>
			</tr>
			<tr>
				<td style='$tr5'>Total Causado:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $total_causado . "; 2)</td>
				<td style='$tr2'>" . $ptotal_causado . "%</td>
				<td style='$tr5' align='right'>Disponible:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $dtotal_causado . "; 2)</td>
				<td style='$tr2'>" . $pdtotal_causado . "%</td>
			</tr>
			<tr>
				<td style='$tr5'>Total Pagado:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $total_pagado . "; 2)</td>
				<td style='$tr2'>" . $ptotal_pagado . "%</td>
				<td style='$tr5' align='right'>Disponible:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $dtotal_pagado . "; 2)</td>
				<td style='$tr2'>" . $pdtotal_pagado . "%</td>
			</tr>
			<tr>
				<td style='$tr5' colspan='17'>Disponible Para Compromisos = Monto Actual - Total Pre Compromisos - Total Compromisos - Reservado Para Disminuir</td>
			</tr>
		</table>";

        echo "
		<table border='1'>
			<tr>
				<th width='200' style='$tr1'>DOCUMENTOssssssss</th>
				<th width='200' style='$tr1'>FECHA</th>
				<th width='750' style='$tr1'>DESCRIPCION</th>
				<th width='150' style='$tr1'>ASIG. INICIAL</th>
				<th width='150' style='$tr1'>AUMENTO</th>
				<th width='150' style='$tr1'>DISMINUCION</th>
				<th width='150' style='$tr1'>MODIFICADO</th>
				<th width='150' style='$tr1'>ASIG. AJUSTADA</th>
				<th width='150' style='$tr1'>PRE-COMPROMISO</th>
				<th width='150' style='$tr1'>COMPROMISO</th>
				<th width='150' style='$tr1'>% COMP</th>
				<th width='150' style='$tr1'>CAUSADO</th>
				<th width='150' style='$tr1'>% CAU</th>
				<th width='150' style='$tr1'>PAGADO</th>
				<th width='150' style='$tr1'>% PAG</th>
				<th width='150' style='$tr1'>DISPONIBLE</th>
				<th width='150' style='$tr1'>% DIS.</th>
			</tr>";
        //----------------------------------------------------
        $filtro = " AND (maestro_presupuesto.idcategoria_programatica='" . $_GET['idcategoria'] . "') AND (maestro_presupuesto.idclasificador_presupuestario='" . $_GET['idpartida'] . "')";
        //---------------------------------------------
        if ($idordinal != "") {
            $where_ordinal = " AND maestro_presupuesto.idordinal = '" . $idordinal . "' ";
        }

        //----------------------------------------------------
        $sql = "(SELECT maestro_presupuesto.idRegistro,
					  maestro_presupuesto.idcategoria_programatica AS IdCategoria,
					  maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
					  categoria_programatica.codigo AS CodCategoria,
					  unidad_ejecutora.denominacion AS Unidad,
					  clasificador_presupuestario.partida AS Par,
					  clasificador_presupuestario.generica AS Gen,
					  clasificador_presupuestario.especifica AS Esp,
					  clasificador_presupuestario.sub_especifica AS Sesp,
					  clasificador_presupuestario.denominacion AS NomPartida,
					  maestro_presupuesto.idRegistro AS IdPresupuesto,
					  SUM(maestro_presupuesto.monto_actual) AS MontoActual,
					  SUM(maestro_presupuesto.total_compromisos) AS MontoCompromiso,
					  SUM(maestro_presupuesto.reservado_disminuir) AS MontoReservadoDisminuir,
					  SUM(maestro_presupuesto.solicitud_aumento) AS MontoSolicitudAumento,
						SUM(maestro_presupuesto.pre_compromiso) AS MontoPreCompromiso,
					  SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
					  SUM(maestro_presupuesto.total_causados) AS TotalCausados,
					  SUM(maestro_presupuesto.total_pagados) AS TotalPagados,
					  SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
					  SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion, 'especifica' AS Tipo
				FROM
					  maestro_presupuesto,
					  categoria_programatica,
					  unidad_ejecutora,
					  clasificador_presupuestario
				WHERE
					  ((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					  (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					  (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) $where_ordinal AND
					  (maestro_presupuesto.anio='" . $anio_fiscal . "' AND
					  maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
					  maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp))

				UNION

				(SELECT maestro_presupuesto.idRegistro,
						maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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

								WHERE (clasificador_presupuestario.partida=Par AND
									   clasificador_presupuestario.generica=Gen AND
									   clasificador_presupuestario.especifica='00' AND
									   clasificador_presupuestario.sub_especifica='00')) AS NomPartida,
						maestro_presupuesto.idRegistro AS IdPresupuesto,
						SUM(maestro_presupuesto.monto_actual) AS MontoActual,
						SUM(maestro_presupuesto.total_compromisos) AS MontoCompromiso,
						SUM(maestro_presupuesto.reservado_disminuir) AS MontoReservadoDisminuir,
						SUM(maestro_presupuesto.solicitud_aumento) AS MontoSolicitudAumento,

						SUM(maestro_presupuesto.pre_compromiso) AS MontoPreCompromiso,
						SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
						SUM(maestro_presupuesto.total_causados) AS TotalCausados,
						SUM(maestro_presupuesto.total_pagados) AS TotalPagados,
						SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
						SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion,
						'generica' AS Tipo
					FROM
						maestro_presupuesto,
						categoria_programatica,
						unidad_ejecutora,
						clasificador_presupuestario
					WHERE
						((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) $where_ordinal AND
						(clasificador_presupuestario.sub_especifica='00') AND
						(maestro_presupuesto.anio='" . $anio_fiscal . "' AND
						maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
						maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
					GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp))

				UNION

				(SELECT maestro_presupuesto.idRegistro,
						maestro_presupuesto.idcategoria_programatica AS IdCategoria,
						maestro_presupuesto.idclasificador_presupuestario AS idPartida,
						categoria_programatica.codigo AS CodCategoria,
						unidad_ejecutora.denominacion AS Unidad,
						clasificador_presupuestario.partida AS Par,
						'00' AS Gen,
						'00' AS Esp,
						'00' AS Sesp,
						(SELECT clasificador_presupuestario.denominacion
							FROM clasificador_presupuestario
								WHERE (clasificador_presupuestario.partida=Par AND
									   clasificador_presupuestario.generica='00' AND
									   clasificador_presupuestario.especifica='00' AND
									   clasificador_presupuestario.sub_especifica='00')) AS NomPartida,
						maestro_presupuesto.idRegistro AS IdPresupuesto,
						SUM(maestro_presupuesto.monto_actual) AS MontoActual,
						SUM(maestro_presupuesto.total_compromisos) AS MontoCompromiso,
						SUM(maestro_presupuesto.reservado_disminuir) AS MontoReservadoDisminuir,
						SUM(maestro_presupuesto.solicitud_aumento) AS MontoSolicitudAumento,
						SUM(maestro_presupuesto.pre_compromiso) AS MontoPreCompromiso,
						SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
						SUM(maestro_presupuesto.total_causados) AS TotalCausados,
						SUM(maestro_presupuesto.total_pagados) AS TotalPagados,
						SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
						SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion,
						'partida' AS Tipo
				FROM
					maestro_presupuesto,
					categoria_programatica,
					unidad_ejecutora,
					clasificador_presupuestario
				WHERE
					((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) $where_ordinal AND
					(clasificador_presupuestario.sub_especifica='00') AND
					(maestro_presupuesto.anio='" . $anio_fiscal . "' AND
					maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
					maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp))
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            $idregistro           = $field["idRegistro"];
            $clasificador         = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $monto_original       = number_format($field["MontoOriginal"], 2, ',', '');
            $monto_actual         = number_format($field["MontoActual"], 2, ',', '');
            $monto_compromiso     = number_format($field["MontoCompromiso"], 2, ',', '');
            $monto_pre_compromiso = number_format($field["MontoPreCompromiso"], 2, ',', '');
            $monto_reservado      = number_format($field["MontoReservadoDisminuir"], 2, ',', '');
            $aumento              = number_format($field["TotalAumento"], 2, ',', '');
            $dimsinucion          = number_format($field["TotalDisminucion"], 2, ',', '');

            $monto_solicitud  = number_format($field["MontoSolicitudAumento"], 2, ',', '');
            $monto_causado    = number_format($field["TotalCausados"], 2, ',', '');
            $monto_pagado     = number_format($field["TotalPagados"], 2, ',', '');
            $disponible       = (float) ($field["MontoActual"] - $field["MontoCompromiso"] - $field["MontoReservadoDisminuir"] - $field["MontoPreCompromiso"]);
            $monto_disponible = number_format($disponible, 2, ',', '');
            if ($field["MontoCompromiso"] == 0 || $field["MontoActual"] == 0) {
                $pcompromiso = "0";
            } else {
                $pcompromiso = (float) (($field[MontoCompromiso] * 100) / $field["MontoActual"]);
            }

            $pcompromiso = number_format($pcompromiso, 2, ',', '');
            if ($field["MontoCausado"] == 0 || $field["MontoActual"] == 0) {
                $pcausado = "0";
            } else {
                $pcausado = (float) (($field["MontoCausado"] * 100) / $field["MontoActual"]);
            }

            $pcausado = number_format($pcausado, 2, ',', '');
            if ($field["MontoPagado"] == 0 || $field["MontoActual"] == 0) {
                $ppagado = "0";
            } else {
                $ppagado = (float) (($field["MontoPagado"] * 100) / $field["MontoActual"]);
            }

            $ppagado = number_format($ppagado, 2, ',', '');
            if ($disponible == 0 || $field["MontoActual"] == 0) {
                $pdisponible = "0";
            } else {
                $pdisponible = (float) (($disponible * 100) / $field["MontoActual"]);
            }

            $pdisponible = number_format($pdisponible, 2, ',', '');

            if ($field["Tipo"] == "partida") {
            } else if ($field["Tipo"] == "generica") {
            } else if ($field["Tipo"] == "especifica") {
                //    ----------------------------
                $sum_original += $field["MontoOriginal"];
                $sum_aumento += $field["TotalAumento"];
                $sum_disminucion += $field["TotalDisminucion"];
                $sum_actual += $field["MontoActual"];
                $sum_compromiso += $field["MontoCompromiso"];
                $sum_causado += $field["TotalCausados"];
                $sum_pagado += $field["TotalPagados"];
                $sum_disponible += $disponible;
                //    ----------------------------
                $total_aumento     = number_format($field["TotalAumento"], 2, ',', '');
                $total_disminucion = number_format($field["TotalDisminucion"], 2, ',', '');

                //    CONSULTO PARA OPBTENER LOS AUMENTOS Y DISMINUCIONES DE LAS PARTIDAS E IMPRIMIRLAS
                $sql = "SELECT
							CONCAT(rc.mes, '-', rc.anio) AS Fecha,
							rc.concepto,
							rcp.disminucion_periodo,
							rcp.aumento_periodo,
							rcp.total_compromisos_periodo,
							rcp.total_causados_periodo,
							rcp.total_causados_periodo,
							rcp.total_pagados_periodo
						FROM
							rendicion_cuentas rc
							INNER JOIN rendicion_cuentas_partidas rcp ON (rc.idrendicion_cuentas=rcp.idrendicion_cuentas)
						WHERE
							rcp.idmaestro_presupuesto='" . $idregistro . "' AND
							rc.anio='" . $anio_fiscal . "'";
                $query_rendicion    = mysql_query($sql) or die($sql . mysql_error());
                $num_rows_rendicion = mysql_num_rows($query_rendicion);
                $h                  = 0;
                if ($num_rows_rendicion != 0) {
                    while ($field_rendicion = mysql_fetch_array($query_rendicion)) {
                        $aumento = number_format($field_rendicion['aumento_periodo'], 2, ',', '');
                        $sum_raumento += $field_rendicion['aumento_periodo'];
                        $disminucion = number_format($field_rendicion['disminucion_periodo'], 2, ',', '');
                        $sum_rdisminucion += $field_rendicion['disminucion_periodo'];
                        $compromisos = number_format($field_rendicion['total_compromisos_periodo'], 2, ',', '');
                        $sum_rcompromisos += $field_rendicion['total_compromisos_periodo'];
                        $causado = number_format($field_rendicion['total_causados_periodo'], 2, ',', '');
                        $sum_rcausado += $field_rendicion['total_causados_periodo'];
                        $pagado = number_format($field_rendicion['total_pagados_periodo'], 2, ',', '');
                        $sum_rpagado += $field_rendicion['total_pagados_periodo'];

                        echo "
						<tr>
							<td style='$tr5' align='right'></td>
							<td style='$tr5' align='right'>" . $field_rendicion['Fecha'] . "</td>
							<td style='$tr5' align='right'>" . utf8_decode($field_rendicion['concepto']) . "</td>
							<td style='$tr5' align='right'></td>
							<td style='$tr5' align='right'>=DECIMAL(" . $aumento . "; 2)</td>
							<td style='$tr5' align='right'>=DECIMAL(" . $disminucion . "; 2)</td>
							<td style='$tr5' align='right'>=DECIMAL(" . $modificado . "; 2)</td>
							<td style='$tr5' align='right'></td>
							<td style='$tr5' align='right'></td>
							<td style='$tr5' align='right'>=DECIMAL(" . $compromiso . "; 2)</td>
							<td style='$tr5' align='right'>=DECIMAL(" . $pcompromiso . "; 2)</td>
							<td style='$tr5' align='right'>=DECIMAL(" . $causado . "; 2)</td>
							<td style='$tr5' align='right'>=DECIMAL(" . $pcausado . "; 2)</td>
							<td style='$tr5' align='right'>=DECIMAL(" . $pagado . "; 2)</td>
							<td style='$tr5' align='right'>=DECIMAL(" . $ppagado . "; 2)</td>
							<td style='$tr5' align='right'>=DECIMAL(" . $disponible . "; 2)</td>
							<td style='$tr5' align='right'>=DECIMAL(" . $pdisponible . "; 2)</td>
						</tr>";
                    }
                } else {
                    $sql = "(SELECT traslados_presupuestarios.status as codigo_referencia,
								 traslados_presupuestarios.idtraslados_presupuestarios AS id,
								 traslados_presupuestarios.nro_solicitud,
								 traslados_presupuestarios.fecha_solicitud,
								 traslados_presupuestarios.justificacion,
								 traslados_presupuestarios.estado,
								 partidas_receptoras_traslado.monto_acreditar,
								 'Traslado(+)' as tipo,
								 'no' AS Causa,
								 'no' As Compromete,
								 '' AS ROC,
								 '01' AS OrdenLista
							FROM
								 traslados_presupuestarios,
								 partidas_receptoras_traslado
							WHERE
								 (traslados_presupuestarios.idtraslados_presupuestarios=partidas_receptoras_traslado.idtraslados_presupuestarios) AND
								 (partidas_receptoras_traslado.idmaestro_presupuesto='$idregistro') AND
								 (traslados_presupuestarios.estado<>'elaboracion') AND
								 (traslados_presupuestarios.anio='" . $anio_fiscal . "'))

							UNION

							(SELECT creditos_adicionales.status as codigo_referencia,
								   creditos_adicionales.idcreditos_adicionales AS id,
								   creditos_adicionales.nro_solicitud,
								   creditos_adicionales.fecha_solicitud,
								   creditos_adicionales.justificacion,
								   creditos_adicionales.estado,
								   partidas_credito_adicional.monto_acreditar,
								   'Credito Adicional' as tipo,
								   'no' AS Causa,
								   'no' As Compromete,
								   '' AS ROC,
								   '01' AS OrdenLista
							FROM
								creditos_adicionales,
								partidas_credito_adicional
							WHERE
								(creditos_adicionales.idcreditos_adicionales=partidas_credito_adicional.idcredito_adicional) AND
								(partidas_credito_adicional.idmaestro_presupuesto='$idregistro') AND
								(creditos_adicionales.estado<>'elaboracion') AND
								(creditos_adicionales.anio='" . $anio_fiscal . "'))

							UNION

							(SELECT rectificacion_presupuesto.status as codigo_referencia,
								   rectificacion_presupuesto.idrectificacion_presupuesto AS id,
								   rectificacion_presupuesto.nro_solicitud,
								   rectificacion_presupuesto.fecha_solicitud,
								   rectificacion_presupuesto.justificacion,
								   rectificacion_presupuesto.estado,
								   partidas_receptoras_rectificacion.monto_acreditar,
								   'Rectificacion(+)' as tipo,
								   'no' AS Causa,
								   'no' As Compromete,
								   '' AS ROC,
								   '01' AS OrdenLista
							FROM
								rectificacion_presupuesto,
								partidas_receptoras_rectificacion
							WHERE
								(rectificacion_presupuesto.idrectificacion_presupuesto=partidas_receptoras_rectificacion.idrectificacion_presupuesto) AND
								(partidas_receptoras_rectificacion.idmaestro_presupuesto='$idregistro') AND
								(rectificacion_presupuesto.estado<>'elaboracion') AND
								(rectificacion_presupuesto.anio='" . $anio_fiscal . "'))

							UNION

							(SELECT traslados_presupuestarios.status as codigo_referencia,
								   traslados_presupuestarios.idtraslados_presupuestarios AS id,
								   traslados_presupuestarios.nro_solicitud,
								   traslados_presupuestarios.fecha_solicitud,
								   traslados_presupuestarios.justificacion,
								   traslados_presupuestarios.estado,
								   partidas_cedentes_traslado.monto_debitar,
								   'Traslado(-)' as tipo,
								   'no' AS Causa,
								   'no' As Compromete,
								   '' AS ROC,
								   '01' AS OrdenLista
							FROM
								traslados_presupuestarios,
								partidas_cedentes_traslado
							WHERE
								(traslados_presupuestarios.idtraslados_presupuestarios=partidas_cedentes_traslado.idtraslados_presupuestarios) AND
								(partidas_cedentes_traslado.idmaestro_presupuesto='$idregistro') AND
								(traslados_presupuestarios.estado<>'elaboracion') AND
								(traslados_presupuestarios.anio='" . $anio_fiscal . "'))

							UNION

							(SELECT disminucion_presupuesto.status as codigo_referencia,
								   disminucion_presupuesto.iddisminucion_presupuesto AS id,
								   disminucion_presupuesto.nro_solicitud,
								   disminucion_presupuesto.fecha_solicitud,
								   disminucion_presupuesto.justificacion,
								   disminucion_presupuesto.estado,
								   partidas_disminucion_presupuesto.monto_debitar,
								   'Disminucion Presupuestaria' as tipo,
								   'no' AS Causa,
								   'no' As Compromete,
								   '' AS ROC,
								   '01' AS OrdenLista
							FROM
								disminucion_presupuesto,
								partidas_disminucion_presupuesto
							WHERE
								(disminucion_presupuesto.iddisminucion_presupuesto=partidas_disminucion_presupuesto.iddisminucion_presupuesto) AND
								(partidas_disminucion_presupuesto.idmaestro_presupuesto='$idregistro') AND
								(disminucion_presupuesto.estado<>'elaboracion') AND
								(disminucion_presupuesto.anio='" . $anio_fiscal . "'))

							UNION

							(SELECT rectificacion_presupuesto.status as codigo_referencia,
								   rectificacion_presupuesto.idrectificacion_presupuesto AS id,
								   rectificacion_presupuesto.nro_solicitud,
								   rectificacion_presupuesto.fecha_solicitud,
								   rectificacion_presupuesto.justificacion,
								   rectificacion_presupuesto.estado,
								   partidas_rectificadoras.monto_debitar,
								   'Rectificacion(-)' as tipo,
								   'no' AS Causa,
								   'no' As Compromete,
								   '' AS ROC,
								   '01' AS OrdenLista
							FROM
								rectificacion_presupuesto,
								partidas_rectificadoras
							WHERE
								(rectificacion_presupuesto.idrectificacion_presupuesto=partidas_rectificadoras.idrectificacion_presupuesto) AND
								(partidas_rectificadoras.idmaestro_presupuesto='$idregistro') AND
								(rectificacion_presupuesto.estado<>'elaboracion') AND
								(rectificacion_presupuesto.anio='" . $anio_fiscal . "'))

							UNION

							(SELECT orden_compra_servicio.codigo_referencia as codigo_referencia,
								   orden_compra_servicio.idorden_compra_servicio AS id,
								   orden_compra_servicio.numero_orden,
								   orden_compra_servicio.fecha_orden,
								   beneficiarios.nombre,
								   orden_compra_servicio.estado,
								   partidas_orden_compra_servicio.monto,
								   'Orden de Compra/Servicio' as tipo,
								   'no' AS Causa,
								   'no' As Compromete,
								 '' AS ROC,
								   '02' AS OrdenLista
							FROM
								orden_compra_servicio,
								partidas_orden_compra_servicio,
								beneficiarios
							WHERE
								(orden_compra_servicio.idorden_compra_servicio=partidas_orden_compra_servicio.idorden_compra_servicio) AND
								(orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios) AND
								(partidas_orden_compra_servicio.idmaestro_presupuesto='$idregistro') AND
								(orden_compra_servicio.estado<>'elaboracion') AND
								(orden_compra_servicio.anio='" . $anio_fiscal . "'))

							UNION

							(SELECT requisicion.codigo_referencia as codigo_referencia,
								   requisicion.idrequisicion AS id,
								   requisicion.numero_requisicion,
								   requisicion.fecha_orden,
								   beneficiarios.nombre,
								   requisicion.estado,
								   partidas_requisiciones.monto,
								   'Requisicion' as tipo,
								   'no' AS Causa,
								   'no' As Compromete,
								   relacion_compra_requisicion.idrelacion_compra_requisicion AS ROC,
								   '02' AS OrdenLista
							FROM
								requisicion
								INNER JOIN beneficiarios ON (requisicion.idbeneficiarios=beneficiarios.idbeneficiarios)
								INNER JOIN partidas_requisiciones ON (requisicion.idrequisicion=partidas_requisiciones.idrequisicion)
								LEFT JOIN relacion_compra_requisicion ON (requisicion.idrequisicion=relacion_compra_requisicion.idrelacion_compra_requisicion)
							WHERE
								(partidas_requisiciones.idmaestro_presupuesto='$idregistro') AND
								(requisicion.estado='procesado') AND
								(requisicion.anio='" . $anio_fiscal . "'))

							UNION

							(SELECT orden_pago.codigo_referencia as codigo_referencia,
								   orden_pago.idorden_pago AS id,
								   orden_pago.numero_orden,
								   orden_pago.fecha_orden,
								   beneficiarios.nombre,
								   orden_pago.estado,
								   partidas_orden_pago.monto,
								   'Orden de Pago' as tipo,
								   tipos_documentos.causa,
								   tipos_documentos.compromete,
								 '' AS ROC,
								   '02' AS OrdenLista
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
								(orden_pago.estado<>'elaboracion') AND
								(orden_pago.anio='" . $anio_fiscal . "'))

							UNION

							(SELECT pagos_financieros.codigo_referencia as codigo_referencia,
								   pagos_financieros.idpagos_financieros AS id,
								   pagos_financieros.numero_cheque,
								   pagos_financieros.fecha_cheque,
								   beneficiarios.nombre,
								   pagos_financieros.estado,
								   partidas_orden_pago.monto,
								   'Cheque' as tipo,
								   'no' AS Causa,
								   'no' As Compromete,
								 '' AS ROC,
								   '02' AS OrdenLista
							FROM
								pagos_financieros,
								partidas_orden_pago,
								orden_pago,
								beneficiarios
							WHERE
								(pagos_financieros.estado<>'elaboracion') AND
								(pagos_financieros.idorden_pago=partidas_orden_pago.idorden_pago AND
								partidas_orden_pago.idmaestro_presupuesto='" . $idregistro . "') AND
								(orden_pago.idorden_pago=partidas_orden_pago.idorden_pago) AND
								(orden_pago.idbeneficiarios=beneficiarios.idbeneficiarios))

							ORDER BY OrdenLista, fecha_solicitud;";

                    echo $sql;
                    $query_detalle = mysql_query($sql) or die($sql . mysql_error());
                    $rows_detalle  = mysql_num_rows($query_detalle);
                    for ($k = 0; $k < $rows_detalle; $k++) {
                        $detalle         = mysql_fetch_array($query_detalle);
                        $monto           = number_format($detalle['monto_acreditar'], 2, ',', '');
                        list($a, $m, $d) = SPLIT('[/.-]', $detalle['fecha_solicitud']);
                        $fecha_solicitud = $d . "/" . $m . "/" . $a;
                        if ($detalle['tipo'] == "Traslado(+)" || $detalle['tipo'] == "Credito Adicional" || $detalle['tipo'] == "Rectificacion(+)") {
                            $aumento     = $monto;
                            $disminucion = "";
                            $compromisos = "";
                            $causado     = "";
                            if ($detalle['estado'] == "Anulado") {
                                $anulado = "(ANULADO)";
                                $aumento = "($aumento)";} else {
                                $anulado = "";
                            }

                            $detalle['tipo'] = $detalle['tipo'] . " " . $anulado;
                        } else if ($detalle['tipo'] == "Traslado(-)" || $detalle['tipo'] == "Disminucion Presupuestaria" || $detalle['tipo'] == "Rectificacion(-)") {
                            $disminucion = $monto;
                            $aumento     = "";
                            $compromisos = "";
                            $causado     = "";
                            if ($detalle['estado'] == "Anulado") {
                                $anulado     = "(ANULADO)";
                                $disminucion = "($disminucion)";} else {
                                $anulado = "";
                            }

                            $detalle['tipo'] = $detalle['tipo'] . " " . $anulado;
                        } else if ($detalle['tipo'] == "Orden de Compra/Servicio") {
                            $disminucion = "";
                            $aumento     = "";
                            $compromisos = $monto;
                            $causado     = "";
                            if ($detalle['estado'] == "anulado") {
                                $anulado     = "(ANULADO)";
                                $compromisos = "($compromisos)";} else {
                                $anulado = "";
                            }

                            $detalle['tipo'] = $detalle['justificacion'] . " " . $anulado;
                        } else if ($detalle['tipo'] == "Orden de Pago") {
                            $disminucion = "";
                            $aumento     = "";
                            if ($detalle['Causa'] == "si") {
                                $causado = $monto;if ($detalle['estado'] == "anulado") {
                                    $anulado = "(ANULADO)";
                                    $causado = "($causado)";} else {
                                    $anulado = "";
                                }
                            }
                            if ($detalle['Compromete'] == "si") {
                                $compromisos = $monto;if ($detalle['estado'] == "anulado") {
                                    $anulado     = "(ANULADO)";
                                    $compromisos = "($compromisos)";} else {
                                    $anulado = "";
                                }
                            }
                            $detalle['tipo'] = $detalle['justificacion'] . " " . $anulado;
                            if ($detalle['estado'] == "pagada") {
                                $imprimir_ocs = "SI";
                            }

                        } else if ($detalle['tipo'] == "Cheque") {
                            $disminucion = "";
                            $aumento     = "";
                            $compromisos = "";
                            $causado     = "";
                            $pagado      = $monto;
                            if ($detalle['estado'] == "anulado") {
                                $anulado = "(ANULADO)";
                                $pagado  = "($pagado)";} else {
                                $anulado = "";
                            }

                            $detalle['tipo'] = $detalle['justificacion'] . " " . $anulado;
                            $imprimir_op     = "SI";
                        } else if ($detalle['tipo'] == "Requisicion") {
                            $detalle['tipo'] = $detalle['justificacion'];
                            $precompromiso   = number_format($detalle['monto_acreditar'], 2, ',', '');
                            $sum_precompromiso += $detalle['monto_acreditar'];
                        }

                        if (($detalle['tipo'] == "Requisicion" && ($detalle['ROC'] == "") && $detalle['estado'] == "procesado") || $detalle['tipo'] != "Requisicion") {

                            if ($detalle['estado'] == "anulado" || $detalle['estado'] == "Anulado") {
                                $style = "color:#FF0000";
                            } else {
                                $style = "color:#000000";
                            }

                            echo "
							<tr style='$style'>
								<td style='$tr5' align='right'>" . $detalle['nro_solicitud'] . "</td>
								<td style='$tr5' align='right'>" . $fecha_solicitud . "</td>
								<td style='$tr5' align='right'>" . utf8_decode($detalle['tipo']) . "</td>
								<td style='$tr5' align='right'></td>
								<td style='$tr5' align='right'>=DECIMAL(" . $aumento . "; 2)</td>
								<td style='$tr5' align='right'>=DECIMAL(" . $disminucion . "; 2)</td>
								<td style='$tr5' align='right'></td>
								<td style='$tr5' align='right'></td>
								<td style='$tr5' align='right'>=DECIMAL(" . $precompromiso . "; 2)</td>
								<td style='$tr5' align='right'>=DECIMAL(" . $compromisos . "; 2)</td>
								<td style='$tr5' align='right'></td>
								<td style='$tr5' align='right'>=DECIMAL(" . $causado . "; 2)</td>
								<td style='$tr5' align='right'></td>
								<td style='$tr5' align='right'>=DECIMAL(" . $pagado . "; 2)</td>
								<td style='$tr5' align='right'></td>
								<td style='$tr5' align='right'></td>
								<td style='$tr5' align='right'></td>
							</tr>";
                        }

                        if ($imprimir_ocs == "SI") {
                            if ($chkjustificacionoc == "1") {
                                $sql = "SELECT
											rpc.idorden_compra_servicio,
											ocs.numero_orden,
											ocs.justificacion
										FROM
											relacion_pago_compromisos rpc
											INNER JOIN orden_compra_servicio ocs ON (rpc.idorden_compra_servicio = ocs.idorden_compra_servicio)
										WHERE rpc.idorden_pago='" . $detalle['id'] . "'";
                                $query_pagados = mysql_query($sql) or die($sql . mysql_error());
                                while ($field_pagados = mysql_fetch_array($query_pagados)) {
                                    echo "
									<tr>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'>" . $field_pagados['numero_orden'] . ", " . $field_pagados['justificacion'] . "</td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
									</tr>";
                                }
                            } else {
                                $sql = "SELECT
											rpc.idorden_compra_servicio,
											ocs.numero_orden
										FROM
											relacion_pago_compromisos rpc
											INNER JOIN orden_compra_servicio ocs ON (rpc.idorden_compra_servicio = ocs.idorden_compra_servicio)
										WHERE rpc.idorden_pago='" . $detalle['id'] . "'";
                                $query_pagados = mysql_query($sql) or die($sql . mysql_error());
                                while ($field_pagados = mysql_fetch_array($query_pagados)) {
                                    $ordenes .= $field_pagados['numero_orden'] . ", ";
                                }
                                echo "
								<tr>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'>" . $ordenes . "</td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
								</tr>";
                            }
                        }

                        if ($imprimir_op == "SI") {
                            if ($chkjustificacionop == "1") {
                                $sql = "SELECT
											pf.idorden_pago,
											op.numero_orden,
											op.justificacion
										FROM
											pagos_financieros pf
											INNER JOIN orden_pago op ON (pf.idorden_pago = op.idorden_pago)
										WHERE pf.idpagos_financieros='" . $detalle['id'] . "'";
                                $query_pagados = mysql_query($sql) or die($sql . mysql_error());
                                while ($field_pagados = mysql_fetch_array($query_pagados)) {
                                    echo "
									<tr>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'>" . $field_pagados['numero_orden'] . ", " . $field_pagados['justificacion'] . "</td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='right'></td>
									</tr>";
                                }
                            } else {
                                $sql = "SELECT
											pf.idorden_pago,
											op.numero_orden
										FROM
											pagos_financieros pf
											INNER JOIN orden_pago op ON (pf.idorden_pago = op.idorden_pago)
										WHERE pf.idpagos_financieros='" . $detalle['id'] . "'";
                                $query_pagados = mysql_query($sql) or die($sql . mysql_error());
                                while ($field_pagados = mysql_fetch_array($query_pagados)) {
                                    $ordenes .= $field_pagados['numero_orden'] . ", ";
                                }
                                echo "
								<tr>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'>" . $ordenes . "</td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
									<td style='$tr5' align='right'></td>
								</tr>";
                            }
                        }

                        $h++;
                        $disminucion   = "";
                        $aumento       = "";
                        $disminucion   = "";
                        $compromisos   = "";
                        $causado       = "";
                        $pagado        = "";
                        $anulado       = "";
                        $monto_actual  = "";
                        $precompromiso = "";
                        $imprimir_ocs  = "";
                        $imprimir_op   = "";
                        $ordenes       = "";
                    }
                }
            }
        }
        if ($num_rows_rendicion != 0) {
            $sum_aumento     = number_format($sum_raumento, 2, ',', '');
            $sum_disminucion = number_format($sum_rdisminucion, 2, ',', '');
            $sum_compromisos = number_format($sum_rcompromisos, 2, ',', '');
            $sum_causado     = number_format($sum_rcausado, 2, ',', '');
            $sum_pagado      = number_format($sum_rpagado, 2, ',', '');
            $sum_modificado  = $sum_raumento - $sum_rdisminucion;

            echo "
			<tr>
				<td style='$tr5' align='right'></td>
				<td style='$tr5' align='right'></td>
				<td style='$tr5' align='right'></td>
				<td style='$tr5' align='right'></td>
				<td style='$tr5' align='right'>=DECIMAL(" . $total_aumento . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $total_disminucion . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $total_modificado . "; 2)</td>
				<td style='$tr5' align='right'></td>
				<td style='$tr5' align='right'>=DECIMAL(" . $total_pre_compromiso . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $total_compromiso . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $ptotal_compromiso . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $total_causado . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $ptotal_causado . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $total_pagado . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $ptotal_pagado . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $sum_disponible . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $pdisponible . "; 2)</td>
			</tr>";
        } else {
            if ($sum_actual == 0) {
                $pcompromiso = 0;
            } else {
                $pcompromiso = (float) (($sum_compromiso * 100) / $sum_actual);
            }

            $pcompromiso = number_format($pcompromiso, 2, ',', '');
            if ($sum_actual == 0) {
                $pcausado = 0;
            } else {
                $pcausado = (float) (($sum_causado * 100) / $sum_actual);
            }

            $pcausado = number_format($pcausado, 2, ',', '');
            if ($sum_actual == 0) {
                $ppagado = 0;
            } else {
                $ppagado = (float) (($sum_pagado * 100) / $sum_actual);
            }

            $ppagado = number_format($ppagado, 2, ',', '');
            if ($sum_actual == 0) {
                $pdisponible = 0;
            } else {
                $pdisponible = (float) (($disponible * 100) / $sum_actual);
            }

            $pdisponible = number_format($pdisponible, 2, ',', '');

            $sum_modificado    = $sum_aumento - $sum_disminucion;
            $sum_original      = number_format($sum_original, 2, ',', '');
            $sum_aumento       = number_format($sum_aumento, 2, ',', '');
            $sum_disminucion   = number_format($sum_disminucion, 2, ',', '');
            $sum_actual        = number_format($sum_actual, 2, ',', '');
            $sum_compromiso    = number_format($sum_compromiso, 2, ',', '');
            $sum_causado       = number_format($sum_causado, 2, ',', '');
            $sum_pagado        = number_format($sum_pagado, 2, ',', '');
            $sum_disponible    = number_format($sum_disponible, 2, ',', '');
            $sum_precompromiso = number_format($sum_precompromiso, 2, ',', '');

            echo "
			<tr>
				<td style='$tr5' align='right'></td>
				<td style='$tr5' align='right'></td>
				<td style='$tr5' align='right'></td>
				<td style='$tr5' align='right'>=DECIMAL(" . $monto_original . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $total_aumento . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $total_disminucion . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $sum_modificado . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $sum_actual . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $total_pre_compromiso . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $total_compromiso . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $ptotal_compromiso . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $total_causado . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $ptotal_causado . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $total_pagado . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $ptotal_pagado . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $sum_disponible . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $tpdisponible . "; 2)</td>
			</tr>";
        }
        break;

    //    Ejecucion Detallada...
    case "ejecucion_detallada":

        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr3 = "background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:12px; color:#000000; ";
        $tr6 = "font-size:12px; color:#000000; background-color:#E6E6E6;";
        $tr7 = "font-size:12px; color:#FF0000; ";

        //---------------------------------------------
        $sql   = "SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='" . $financiamiento . "') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        $hasta                     = $anio_fiscal . '-12-31';
        if ($estado != "") {
            if ($estado == "procesadas/pagadas") {
                $filtro_traslados     = "AND traslados_presupuestarios.estado = 'procesado'";
                $filtro_creditos      = "AND creditos_adicionales.estado = 'procesado'";
                $filtro_rectificacion = "AND rectificacion_presupuesto.estado = 'procesado'";
                $filtro_disminucion   = "AND disminucion_presupuesto.estado = 'procesado'";
                $filtro_requisicion   = "AND requisicion.estado = 'procesado'";
                $filtro_ocs           = "AND (orden_compra_servicio.estado = 'procesado' OR orden_compra_servicio.estado = 'pagado')";
                $filtro_op            = "AND (orden_pago.estado = 'procesado' OR orden_pago.estado = 'pagada')";
                $filtro_pf            = " AND pagos_financieros.estado <> 'anulado' AND pagos_financieros.fecha_cheque <= '" . $hasta . "'";
            } elseif ($estado == "procesadas") {
                $filtro_traslados     = "AND traslados_presupuestarios.estado = 'procesado'";
                $filtro_creditos      = "AND creditos_adicionales.estado = 'procesado'";
                $filtro_rectificacion = "AND rectificacion_presupuesto.estado = 'procesado'";
                $filtro_disminucion   = "AND disminucion_presupuesto.estado = 'procesado'";
                $filtro_requisicion   = "AND requisicion.estado = 'procesado'";
                $filtro_ocs           = "AND orden_compra_servicio.estado = 'procesado'";
                $filtro_op            = "AND orden_pago.estado = 'procesado'";
                $filtro_pf            = " AND pagos_financieros.estado <> 'anulado' AND pagos_financieros.fecha_cheque <= '" . $hasta . "'";
            } elseif ($estado == "pagadas") {
                $filtro_traslados     = "AND traslados_presupuestarios.estado = 'procesado'";
                $filtro_creditos      = "AND creditos_adicionales.estado = 'procesado'";
                $filtro_rectificacion = "AND rectificacion_presupuesto.estado = 'procesado'";
                $filtro_disminucion   = "AND disminucion_presupuesto.estado = 'procesado'";
                $filtro_requisicion   = "AND requisicion.estado = 'procesado'";
                $filtro_ocs           = "AND orden_compra_servicio.estado = 'pagado'";
                $filtro_op            = "AND orden_pago.estado = 'pagada'";
                $filtro_pf            = " AND pagos_financieros.estado <> 'anulado' AND pagos_financieros.fecha_cheque <= '" . $hasta . "'";
            } elseif ($estado == "anuladas") {
                $filtro_traslados     = "AND traslados_presupuestarios.estado = 'anulado'";
                $filtro_creditos      = "AND creditos_adicionales.estado = 'anulado'";
                $filtro_rectificacion = "AND rectificacion_presupuesto.estado = 'anulado'";
                $filtro_disminucion   = "AND disminucion_presupuesto.estado = 'anulado'";
                $filtro_requisicion   = "AND requisicion.estado = 'anulado'";
                $filtro_ocs           = "AND orden_compra_servicio.estado = 'anulado'";
                $filtro_op            = "AND orden_pago.estado = 'anulado'";
                $filtro_pf            = " AND pagos_financieros.estado = 'anulado' AND pagos_financieros.fecha_cheque <= '" . $hasta . "'";
            }
        }

        echo "<table>";
        echo "<tr><td colspan='11' style='$tr2'>REPUBLICA BOLIVARIANA DE VENEZUELA</td></tr>";
        echo "<tr><td colspan='11' style='$tr2'>" . $config["nombre_institucion"] . "</td></tr>";
        echo "<tr><td colspan='11' style='$tr2'>ESTADO " . $config["denominacion"] . "</td></tr>";
        echo "<tr><td colspan='11' style='$tr2'>" . $config["rif"] . "</td></tr>";
        echo "<tr><td colspan='11' style='$tr2'></td></tr>";
        echo "<tr><td colspan='11' align='center' style='$tr2'>EJECUCION DETALLADA</td></tr>";
        echo "<tr><td colspan='11' style='$tr2'></td></tr>";
        echo "<tr><td colspan='11' style='$tr2'></td></tr></table>";

        //---------------------------------------------
        echo "
		<table border='0'>
			<tr><td colspan='11'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
			<tr><td colspan='11'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
			<tr><td colspan='11'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
			<tr><td colspan='11'></td></tr>
		</table>";

        echo "
			<table border='1'>
			<tr>
				<th width='100' style='$tr1'>PARTIDA</th>
				<th width='1450' style='$tr1'>DESCRIPCION</th>";
        if ($chkinicial == 1) {
            echo "<th width='250' style='$tr1'>ASIG. INICIAL</th>";
        }
        if ($chkaumentos == 1) {
            echo "<th width='250' style='$tr1'>AUMENTO</th>";
        }
        if ($chkdisminuciones == 1) {
            echo "<th width='250' style='$tr1'>DISMINUCION</th>";
        }
        if ($chkmodificaciones == 1) {
            echo "<th width='250' style='$tr1'>MODIFICADO</th>";
        }
        if ($chkajustada == 1) {
            echo "<th width='250' style='$tr1'>ASIG. AJUSTADA</th>";
        }
        if ($chkcompromiso == 1) {
            echo "<th width='250' style='$tr1'>COMPROMISO</th>";
        }
        if ($chkcausado == 1) {
            echo "<th width='250' style='$tr1'>CAUSADO</th>";
        }
        if ($chkpagado == 1) {
            echo "<th width='250' style='$tr1'>PAGADO</th>";
        }
        if ($chkdisponible == 1) {
            echo "<th width='250' style='$tr1'>DISPONIBLE</th>";
        }
        echo "</tr></table>";
        //---------------------------------------------
        $filtro = '';
        if ($idcategoria_programatica != '0') {
            $filtro = " AND (maestro_presupuesto.idcategoria_programatica='" . $idcategoria_programatica . "')";
        }

        //---------------------------------------------
        $sql = "SELECT
					rc.*
				FROM
					rendicion_cuentas rc
					INNER JOIN categoria_programatica cp ON (rc.idcategoria_programatica = cp.idcategoria_programatica)
					INNER JOIN unidad_ejecutora u ON (cp.idunidad_ejecutora = u.idunidad_ejecutora)
				WHERE
					rc.anio='" . $anio_fiscal . "' AND
					rc.idfuente_financiamiento='" . $financiamiento . "' AND
					rc.idtipo_presupuesto='" . $tipo_presupuesto . "' AND
					rc.idcategoria_programatica='" . $idcategoria_programatica . "'
				GROUP BY rc.idcategoria_programatica";
        $query_comprueba_rendicion = mysql_query($sql) or die($sql . mysql_error());
        if (mysql_num_rows($query_comprueba_rendicion) != 0) {
            $sql = "(SELECT maestro_presupuesto.idRegistro,
						  maestro_presupuesto.idcategoria_programatica AS IdCategoria,
						  maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
						  categoria_programatica.codigo AS CodCategoria,
						  unidad_ejecutora.denominacion AS Unidad,
						  clasificador_presupuestario.partida AS Par,
						  clasificador_presupuestario.generica AS Gen,
						  clasificador_presupuestario.especifica AS Esp,
						  clasificador_presupuestario.sub_especifica AS Sesp,
						  clasificador_presupuestario.denominacion AS NomPartida,
						  maestro_presupuesto.idRegistro AS IdPresupuesto,
						  SUM(rcp.monto_actual_periodo) AS MontoActual,
						  SUM(rcp.total_compromisos_periodo) AS MontoCompromiso,
						  '' AS PreCompromiso,
						  '' AS ReservadoDisminuir,
						  SUM(rcp.monto_original_periodo) AS MontoOriginal,
						  SUM(rcp.total_causados_periodo) AS TotalCausados,
						  SUM(rcp.total_pagados_periodo) AS TotalPagados,
						  SUM(rcp.aumento_periodo) AS TotalAumento,
						  SUM(rcp.disminucion_periodo) AS TotalDisminucion,
						  'especifica' AS Tipo,
						  ordinal.codigo AS codordinal,
						  ordinal.denominacion AS nomordinal
					FROM
						  maestro_presupuesto,
						  categoria_programatica,
						  unidad_ejecutora,
						  clasificador_presupuestario,
						  ordinal,
						  rendicion_cuentas_partidas rcp
					WHERE
						  (maestro_presupuesto.idRegistro = rcp.idmaestro_presupuesto) AND
							((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
							(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
							(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						  (maestro_presupuesto.idordinal=ordinal.idordinal) AND
							(maestro_presupuesto.anio='" . $anio_fiscal . "' AND
							maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
							maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
						GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

					UNION

					(SELECT maestro_presupuesto.idRegistro,
							maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
									WHERE (clasificador_presupuestario.partida=Par AND
										   clasificador_presupuestario.generica=Gen AND
										   clasificador_presupuestario.especifica='00' AND
										   clasificador_presupuestario.sub_especifica='00')) AS NomPartida,
							maestro_presupuesto.idRegistro AS IdPresupuesto,
							SUM(rcp.monto_actual_periodo) AS MontoActual,
						  SUM(rcp.total_compromisos_periodo) AS MontoCompromiso,
						  '' AS PreCompromiso,
						  '' AS ReservadoDisminuir,
						  SUM(rcp.monto_original_periodo) AS MontoOriginal,
						  SUM(rcp.total_causados_periodo) AS TotalCausados,
						  SUM(rcp.total_pagados_periodo) AS TotalPagados,
						  SUM(rcp.aumento_periodo) AS TotalAumento,
						  SUM(rcp.disminucion_periodo) AS TotalDisminucion,
						  'generica' AS Tipo,
						  '0000' AS codordinal,
						  '' AS nomordinal
					FROM
						  maestro_presupuesto,
						  categoria_programatica,
						  unidad_ejecutora,
						  clasificador_presupuestario,
						  ordinal,
						  rendicion_cuentas_partidas rcp
					WHERE
						  (maestro_presupuesto.idRegistro = rcp.idmaestro_presupuesto) AND
							((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
							(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
							(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
							(maestro_presupuesto.idordinal=ordinal.idordinal) AND
							(clasificador_presupuestario.sub_especifica='00') AND
							(ordinal.codigo = '0000') AND
							(maestro_presupuesto.anio='" . $anio_fiscal . "' AND
							maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
							maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
						GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (ordinal.codigo))

						UNION

						(SELECT maestro_presupuesto.idRegistro,
								maestro_presupuesto.idcategoria_programatica AS IdCategoria,
								maestro_presupuesto.idclasificador_presupuestario AS idPartida,
								categoria_programatica.codigo AS CodCategoria,
								unidad_ejecutora.denominacion AS Unidad,
								clasificador_presupuestario.partida AS Par,
								'00' AS Gen,
								'00' AS Esp,
								'00' AS Sesp,
								(SELECT clasificador_presupuestario.denominacion
									FROM clasificador_presupuestario
										WHERE (clasificador_presupuestario.partida=Par AND
											   clasificador_presupuestario.generica='00' AND
											   clasificador_presupuestario.especifica='00' AND
											   clasificador_presupuestario.sub_especifica='00')) AS NomPartida,
								maestro_presupuesto.idRegistro AS IdPresupuesto,
								SUM(rcp.monto_actual_periodo) AS MontoActual,
						  SUM(rcp.total_compromisos_periodo) AS MontoCompromiso,
						  '' AS PreCompromiso,
						  '' AS ReservadoDisminuir,
						  SUM(rcp.monto_original_periodo) AS MontoOriginal,
						  SUM(rcp.total_causados_periodo) AS TotalCausados,
						  SUM(rcp.total_pagados_periodo) AS TotalPagados,
						  SUM(rcp.aumento_periodo) AS TotalAumento,
						  SUM(rcp.disminucion_periodo) AS TotalDisminucion,
						  'partida' AS Tipo,
						  '0000' AS codordinal,
						  '' AS nomordinal
					FROM
						  maestro_presupuesto,
						  categoria_programatica,
						  unidad_ejecutora,
						  clasificador_presupuestario,
						  ordinal,
						  rendicion_cuentas_partidas rcp
					WHERE
						  (maestro_presupuesto.idRegistro = rcp.idmaestro_presupuesto) AND
							((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
							(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
							(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
							(maestro_presupuesto.idordinal=ordinal.idordinal) AND
							(clasificador_presupuestario.sub_especifica='00') AND
							(ordinal.codigo = '0000') AND
							(maestro_presupuesto.anio='" . $anio_fiscal . "' AND
							maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
							maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
						GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (ordinal.codigo))
						ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
        } else {
            $sql = "(SELECT maestro_presupuesto.idRegistro,
						  maestro_presupuesto.idcategoria_programatica AS IdCategoria,
						  maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
						  categoria_programatica.codigo AS CodCategoria,
						  unidad_ejecutora.denominacion AS Unidad,
						  clasificador_presupuestario.partida AS Par,
						  clasificador_presupuestario.generica AS Gen,
						  clasificador_presupuestario.especifica AS Esp,
						  clasificador_presupuestario.sub_especifica AS Sesp,
						  clasificador_presupuestario.denominacion AS NomPartida,
						  maestro_presupuesto.idRegistro AS IdPresupuesto,
						  SUM(maestro_presupuesto.monto_actual) AS MontoActual,
						  SUM(maestro_presupuesto.total_compromisos) AS MontoCompromiso,
						  SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso,
						  SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir,
						  SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
						  SUM(maestro_presupuesto.total_causados) AS TotalCausados,
						  SUM(maestro_presupuesto.total_pagados) AS TotalPagados,
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
						  (maestro_presupuesto.anio='" . $anio_fiscal . "' AND
						  maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
						  maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
					GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

					UNION

					(SELECT maestro_presupuesto.idRegistro,
							maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
									WHERE (clasificador_presupuestario.partida=Par AND
										   clasificador_presupuestario.generica=Gen AND
										   clasificador_presupuestario.especifica='00' AND
										   clasificador_presupuestario.sub_especifica='00')) AS NomPartida,
							maestro_presupuesto.idRegistro AS IdPresupuesto,
							SUM(maestro_presupuesto.monto_actual) AS MontoActual,
							SUM(maestro_presupuesto.total_compromisos) AS MontoCompromiso,
							SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso,
							SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir,
							SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
							SUM(maestro_presupuesto.total_causados) AS TotalCausados,
							SUM(maestro_presupuesto.total_pagados) AS TotalPagados,
							SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
							SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion,
							'generica' AS Tipo,
							'0000' AS codordinal,
							'' AS nomordinal
						FROM
							maestro_presupuesto,
							categoria_programatica,
							unidad_ejecutora,
							ordinal,
							clasificador_presupuestario
						WHERE
							((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
							(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
							(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
							(maestro_presupuesto.idordinal=ordinal.idordinal) AND
							(clasificador_presupuestario.sub_especifica='00') AND
							(ordinal.codigo = '0000') AND
							(maestro_presupuesto.anio='" . $anio_fiscal . "' AND
							maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
							maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
						GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (ordinal.codigo))

						UNION

						(SELECT maestro_presupuesto.idRegistro,
								maestro_presupuesto.idcategoria_programatica AS IdCategoria,
								maestro_presupuesto.idclasificador_presupuestario AS idPartida,
								categoria_programatica.codigo AS CodCategoria,
								unidad_ejecutora.denominacion AS Unidad,
								clasificador_presupuestario.partida AS Par,
								'00' AS Gen,
								'00' AS Esp,
								'00' AS Sesp,
								(SELECT clasificador_presupuestario.denominacion
									FROM clasificador_presupuestario
										WHERE (clasificador_presupuestario.partida=Par AND
											   clasificador_presupuestario.generica='00' AND
											   clasificador_presupuestario.especifica='00' AND
											   clasificador_presupuestario.sub_especifica='00')) AS NomPartida,
								maestro_presupuesto.idRegistro AS IdPresupuesto,
								SUM(maestro_presupuesto.monto_actual) AS MontoActual,
								SUM(maestro_presupuesto.total_compromisos) AS MontoCompromiso,
								SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso,
								SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir,
								SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
								SUM(maestro_presupuesto.total_causados) AS TotalCausados,
								SUM(maestro_presupuesto.total_pagados) AS TotalPagados,
								SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
								SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion,
								'partida' AS Tipo,
								'0000' AS codordinal,
								'' AS nomordinal
						FROM
							maestro_presupuesto,
							categoria_programatica,
							unidad_ejecutora,
							ordinal,
							clasificador_presupuestario
						WHERE
							((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
							(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
							(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
							(maestro_presupuesto.idordinal=ordinal.idordinal) AND
							(clasificador_presupuestario.sub_especifica='00') AND
							(ordinal.codigo = '0000') AND
							(maestro_presupuesto.anio='" . $anio_fiscal . "' AND
							maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
							maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
						GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (ordinal.codigo))
						ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
        }
        //---------------------------------------------
        //echo "<tr><td colspan='15' style='$tr2'>".$sql."</td></tr>";
        echo "<table>";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            $idregistro = $field["idRegistro"];
            //    SI CAMBIA DE CATEGORIA LA IMPRIMO
            if ($field["IdCategoria"] != $IdCategoria) {
                $IdCategoria = $field["IdCategoria"];

                echo "<tr><td colspan='15' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>";
            }
            $clasificador      = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $precompromiso     = number_format($field["PreCompromiso"], 2, ',', '.');
            $total_aumento     = number_format($field["TotalAumento"], 2, ',', '.');
            $total_disminucion = number_format($field["TotalDisminucion"], 2, ',', '.');
            $monto_actual      = $field['MontoOriginal'] + $field['TotalAumento'] - $field['TotalDisminucion'];
            $total_actual      = number_format($monto_actual, 2, ',', '.');
            $monto_original    = number_format($field["MontoOriginal"], 2, ',', '.');
            $monto_compromiso  = number_format($field["MontoCompromiso"], 2, ',', '.');
            $monto_causado     = number_format($field["TotalCausados"], 2, ',', '.');
            $monto_pagado      = number_format($field["TotalPagados"], 2, ',', '.');
            $modificado        = number_format(($field["TotalAumento"] - $field['TotalDisminucion']), 2, ',', '.');
            if ($chkrestar) {
                $disponible = (float) ($field["MontoActual"] - $field["PreCompromiso"] - $field["MontoCompromiso"] - $field["ReservadoDisminuir"]);
            } else {
                $disponible = (float) ($field["MontoActual"] - $field["MontoCompromiso"] - $field["ReservadoDisminuir"]);
            }

            $monto_disponible = number_format($disponible, 2, ',', '.');
            if ($field["MontoCompromiso"] == 0 || $monto_actual == 0) {
                $pcompromiso = "0";
            } else {
                $pcompromiso = (float) (($field["MontoCompromiso"] * 100) / $monto_actual);
            }

            $pcompromiso = number_format($pcompromiso, 2, ',', '');
            if ($field["TotalCausados"] == 0 || $monto_actual == 0) {
                $pcausado = "0";
            } else {
                $pcausado = (float) (($field["TotalCausados"] * 100) / $monto_actual);
            }

            $pcausado = number_format($pcausado, 2, ',', '');
            if ($field["TotalPagados"] == 0 || $monto_actual == 0) {
                $ppagado = "0";
            } else {
                $ppagado = (float) (($field["TotalPagados"] * 100) / $monto_actual);
            }

            $ppagado = number_format($ppagado, 2, ',', '');
            if ($disponible == 0 || $monto_actual == 0) {
                $pdisponible = "0";
            } else {
                $pdisponible = (float) (($disponible * 100) / $monto_actual);
            }

            $pdisponible = number_format($pdisponible, 2, ',', '');

            if ($field["Tipo"] == "partida") {
                //    ----------------------------
                $sum_original += $field["MontoOriginal"];
                $sum_aumento += $field["TotalAumento"];
                $sum_disminucion += $field["TotalDisminucion"];
                $sum_modificado += ($field["TotalAumento"] - $field['TotalDisminucion']);
                $sum_compromiso += $field["MontoCompromiso"];
                $sum_causado += $field["TotalCausados"];
                $sum_pagado += $field["TotalPagados"];
                $sum_disponible += $disponible;
                //    ----------------------------
                echo "
				<tr>
					<td style='$tr3'>" . $clasificador . "</td>
					<td style='$tr3'>" . utf8_decode($field["NomPartida"]) . "</td>";
                if ($chkinicial == 1) {
                    echo "<td style='$tr3' align='right'>" . $monto_original . "</td>";
                }
                if ($chkaumentos == 1) {
                    echo "<td style='$tr3' align='right'>" . $total_aumento . "</td>";
                }
                if ($chkdisminuciones == 1) {
                    echo "<td style='$tr3' align='right'>" . $total_disminucion . "</td>";
                }
                if ($chkmodificaciones == 1) {
                    echo "<td style='$tr3' align='right'>" . $modificado . "</td>";
                }
                if ($chkajustada == 1) {
                    echo "<td style='$tr3' align='right'>" . $total_actual . "</td>";
                }
                if ($chkcompromiso == 1) {
                    echo "<td style='$tr3' align='right'>" . $monto_compromiso . "</td>";
                }
                if ($chkcausado == 1) {
                    echo "<td style='$tr3' align='right'>" . $monto_causado . "</td>";
                }
                if ($chkpagado == 1) {
                    echo "<td style='$tr3' align='right'>" . $monto_causado . "</td>";
                }
                if ($chkdisponible == 1) {
                    echo "<td style='$tr3' align='right'>" . $monto_disponible . "</td>";
                }
                echo "</tr>";

            } else if ($field["Tipo"] == "generica") {
                echo "
				<tr>
					<td style='$tr4'>" . $clasificador . "</td>
					<td style='$tr4'>" . utf8_decode($field["NomPartida"]) . "</td>";
                if ($chkinicial == 1) {
                    echo "<td style='$tr4' align='right'>" . $monto_original . "</td>";
                }
                if ($chkaumentos == 1) {
                    echo "<td style='$tr4' align='right'>" . $total_aumento . "</td>";
                }
                if ($chkdisminuciones == 1) {
                    echo "<td style='$tr4' align='right'>" . $total_disminucion . "</td>";
                }
                if ($chkmodificaciones == 1) {
                    echo "<td style='$tr4' align='right'>" . $modificado . "</td>";
                }
                if ($chkajustada == 1) {
                    echo "<td style='$tr4' align='right'>" . $total_actual . "</td>";
                }
                if ($chkcompromiso == 1) {
                    echo "<td style='$tr4' align='right'>" . $monto_compromiso . "</td>";
                }
                if ($chkcausado == 1) {
                    echo "<td style='$tr4' align='right'>" . $monto_causado . "</td>";
                }
                if ($chkpagado == 1) {
                    echo "<td style='$tr4' align='right'>" . $monto_causado . "</td>";
                }
                if ($chkdisponible == 1) {
                    echo "<td style='$tr4' align='right'>" . $monto_disponible . "</td>";
                }
                echo "</tr>";

            } else if ($field["Tipo"] == "especifica") {

                $total_aumento     = number_format($field["TotalAumento"], 2, ',', '.');
                $total_disminucion = number_format($field["TotalDisminucion"], 2, ',', '.');
                $sum_actual += monto_actual;
                if ($field['codordinal'] == '0000') {
                    $nompartida = utf8_decode($field["NomPartida"]);
                } else {
                    $nompartida = utf8_decode($field['codordinal'] . ' ' . $field["nomordinal"]);
                }

                echo "
				<tr>
					<td style='$tr6'>" . $clasificador . "</td>
					<td style='$tr6'>" . utf8_decode($field["NomPartida"]) . "</td>";
                if ($chkinicial == 1) {
                    echo "<td style='$tr6' align='right'>" . $monto_original . "</td>";
                }
                if ($chkaumentos == 1) {
                    echo "<td style='$tr6' align='right'>" . $total_aumento . "</td>";
                }
                if ($chkdisminuciones == 1) {
                    echo "<td style='$tr6' align='right'>" . $total_disminucion . "</td>";
                }
                if ($chkmodificaciones == 1) {
                    echo "<td style='$tr6' align='right'>" . $modificado . "</td>";
                }
                if ($chkajustada == 1) {
                    echo "<td style='$tr6' align='right'>" . $total_actual . "</td>";
                }
                if ($chkcompromiso == 1) {
                    echo "<td style='$tr6' align='right'>" . $monto_compromiso . "</td>";
                }
                if ($chkcausado == 1) {
                    echo "<td style='$tr6' align='right'>" . $monto_causado . "</td>";
                }
                if ($chkpagado == 1) {
                    echo "<td style='$tr6' align='right'>" . $monto_causado . "</td>";
                }
                if ($chkdisponible == 1) {
                    echo "<td style='$tr6' align='right'>" . $monto_disponible . "</td>";
                }
                echo "</tr>";

                $sql = "SELECT
						CONCAT(rc.mes, '-', rc.anio) AS Fecha,
						rc.concepto,
						rcp.disminucion_periodo,
						rcp.aumento_periodo,
						rcp.total_compromisos_periodo,
						rcp.total_causados_periodo,
						rcp.total_causados_periodo,
						rcp.total_pagados_periodo
					FROM
						rendicion_cuentas rc
						INNER JOIN rendicion_cuentas_partidas rcp ON (rc.idrendicion_cuentas=rcp.idrendicion_cuentas)
					WHERE
						rcp.idmaestro_presupuesto='" . $idregistro . "' AND
						rc.anio='" . $anio_fiscal . "'";
                $query_rendicion    = mysql_query($sql) or die($sql . mysql_error());
                $num_rows_rendicion = mysql_num_rows($query_rendicion);
                $h                  = 0;
                if ($num_rows_rendicion != 0) {
                    while ($field_rendicion = mysql_fetch_array($query_rendicion)) {
                        $aumento = number_format($field_rendicion['aumento_periodo'], 2, ',', '.');
                        $sum_raumento += $field_rendicion['aumento_periodo'];
                        $disminucion = number_format($field_rendicion['disminucion_periodo'], 2, ',', '.');
                        $sum_rdisminucion += $field_rendicion['disminucion_periodo'];
                        $modificado = number_format(($field["aumento_periodo"] - $field['disminucion_periodo']), 2, ',', '.');
                        $sum_rmodificado += ($field["aumento_periodo"] - $field['disminucion_periodo']);
                        $compromisos = number_format($field_rendicion['total_compromisos_periodo'], 2, ',', '.');
                        $sum_rcompromisos += $field_rendicion['total_compromisos_periodo'];
                        $causado = number_format($field_rendicion['total_causados_periodo'], 2, ',', '.');
                        $sum_rcausado += $field_rendicion['total_causados_periodo'];
                        $pagado = number_format($field_rendicion['total_pagados_periodo'], 2, ',', '.');
                        $sum_rpagado += $field_rendicion['total_pagados_periodo'];

                        echo "
						<tr>
							<td style='$tr5'></td>
							<td style='$tr5'>" . $field_rendicion['Fecha'] . '-' . utf8_decode($field_rendicion['concepto']) . "</td>";
                        if ($chkinicial == 1) {
                            echo "<td style='$tr5' align='right'></td>";
                        }
                        if ($chkaumentos == 1) {
                            echo "<td style='$tr5' align='right'>" . $aumento . "</td>";
                        }
                        if ($chkdisminuciones == 1) {
                            echo "<td style='$tr5' align='right'>" . $disminucion . "</td>";
                        }
                        if ($chkmodificaciones == 1) {
                            echo "<td style='$tr5' align='right'>" . $modificado . "</td>";
                        }
                        if ($chkajustada == 1) {
                            echo "<td style='$tr5' align='right'></td>";
                        }
                        if ($chkcompromiso == 1) {
                            echo "<td style='$tr5' align='right'>" . $compromisos . "</td>";
                        }
                        if ($chkcausado == 1) {
                            echo "<td style='$tr5' align='right'>" . $causado . "</td>";
                        }
                        if ($chkpagado == 1) {
                            echo "<td style='$tr5' align='right'>" . $pagado . "</td>";
                        }
                        if ($chkdisponible == 1) {
                            echo "td style='$tr5' align='right'></td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    //    CONSULTO PARA OPBTENER LOS AUMENTOS Y DISMINUCIONES DE LAS PARTIDAS E IMPRIMIRLAS

                    $sql = "SELECT traslados_presupuestarios.status as codigo_referencia,
								 traslados_presupuestarios.idtraslados_presupuestarios AS id,
								 traslados_presupuestarios.nro_solicitud,
								 traslados_presupuestarios.fecha_solicitud,
								 traslados_presupuestarios.justificacion,
								 traslados_presupuestarios.estado,
								 partidas_receptoras_traslado.monto_acreditar,
								 'Traslado(+)' as tipo,
								 'no' AS Causa,
								 'no' As Compromete,
								 '' AS ROC,
								 '01' AS OrdenLista
							FROM
								 traslados_presupuestarios,
								 partidas_receptoras_traslado
							WHERE
								 (traslados_presupuestarios.idtraslados_presupuestarios=partidas_receptoras_traslado.idtraslados_presupuestarios) AND
								 (partidas_receptoras_traslado.idmaestro_presupuesto='$idregistro') AND
								 (traslados_presupuestarios.anio='" . $anio_fiscal . "') AND
								 (traslados_presupuestarios.estado <> 'elaboracion' $filtro_traslados)

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
								 '' AS ROC,
								   '01' AS OrdenLista
							FROM
								creditos_adicionales,
								partidas_credito_adicional
							WHERE
								(creditos_adicionales.idcreditos_adicionales=partidas_credito_adicional.idcredito_adicional) AND
								(partidas_credito_adicional.idmaestro_presupuesto='$idregistro') AND
								 (creditos_adicionales.estado <> 'elaboracion' $filtro_creditos) AND
								(creditos_adicionales.anio='" . $anio_fiscal . "')

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
								 '' AS ROC,
								   '01' AS OrdenLista
							FROM
								rectificacion_presupuesto,
								partidas_receptoras_rectificacion
							WHERE
								(rectificacion_presupuesto.idrectificacion_presupuesto=partidas_receptoras_rectificacion.idrectificacion_presupuesto) AND
								(partidas_receptoras_rectificacion.idmaestro_presupuesto='$idregistro') AND
								 (rectificacion_presupuesto.estado <> 'elaboracion' $filtro_rectificacion) AND
								(rectificacion_presupuesto.anio='" . $anio_fiscal . "')

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
								 '' AS ROC,
								   '01' AS OrdenLista
							FROM
								traslados_presupuestarios,
								partidas_cedentes_traslado
							WHERE
								(traslados_presupuestarios.idtraslados_presupuestarios=partidas_cedentes_traslado.idtraslados_presupuestarios) AND
								(partidas_cedentes_traslado.idmaestro_presupuesto='$idregistro') AND
								 (traslados_presupuestarios.estado <> 'elaboracion' $filtro_traslados) AND
								(traslados_presupuestarios.anio='" . $anio_fiscal . "')

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
								 '' AS ROC,
								   '01' AS OrdenLista
							FROM
								disminucion_presupuesto,
								partidas_disminucion_presupuesto
							WHERE
								(disminucion_presupuesto.iddisminucion_presupuesto=partidas_disminucion_presupuesto.iddisminucion_presupuesto) AND
								(partidas_disminucion_presupuesto.idmaestro_presupuesto='$idregistro') AND
								 (disminucion_presupuesto.estado <> 'elaboracion' $filtro_disminucion) AND
								(disminucion_presupuesto.anio='" . $anio_fiscal . "')

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
								 '' AS ROC,
								   '01' AS OrdenLista
							FROM
								rectificacion_presupuesto,
								partidas_rectificadoras
							WHERE
								(rectificacion_presupuesto.idrectificacion_presupuesto=partidas_rectificadoras.idrectificacion_presupuesto) AND
								(partidas_rectificadoras.idmaestro_presupuesto='$idregistro') AND
								 (rectificacion_presupuesto.estado <> 'elaboracion' $filtro_rectificacion) AND
								(rectificacion_presupuesto.anio='" . $anio_fiscal . "')

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
								 '' AS ROC,
								   '02' AS OrdenLista
							FROM
								orden_compra_servicio,
								partidas_orden_compra_servicio,
								beneficiarios
							WHERE
								(orden_compra_servicio.idorden_compra_servicio=partidas_orden_compra_servicio.idorden_compra_servicio) AND
								(orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios) AND
								(partidas_orden_compra_servicio.idmaestro_presupuesto='$idregistro') AND
								(orden_compra_servicio.estado <> 'elaboracion' $filtro_ocs) AND
								(orden_compra_servicio.anio='" . $anio_fiscal . "')

							UNION

							SELECT requisicion.codigo_referencia as codigo_referencia,
								   requisicion.idrequisicion AS id,
								   requisicion.numero_requisicion,
								   requisicion.fecha_orden,
								   beneficiarios.nombre,
								   requisicion.estado,
								   partidas_requisiciones.monto,
								   'Requisicion' as tipo,
								   'no' AS Causa,
								   'no' As Compromete,
								   relacion_compra_requisicion.idrelacion_compra_requisicion AS ROC,
								   '02' AS OrdenLista
							FROM
								requisicion
								INNER JOIN beneficiarios ON (requisicion.idbeneficiarios=beneficiarios.idbeneficiarios)
								INNER JOIN partidas_requisiciones ON (requisicion.idrequisicion=partidas_requisiciones.idrequisicion)
								LEFT JOIN relacion_compra_requisicion ON (requisicion.idrequisicion=relacion_compra_requisicion.idrelacion_compra_requisicion)
							WHERE
								(partidas_requisiciones.idmaestro_presupuesto='$idregistro') AND
								(requisicion.estado <> 'elaboracion' $filtro_requisicion) AND
								(requisicion.anio='" . $anio_fiscal . "')

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
								   '' AS ROC,
								   '02' AS OrdenLista
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
								(orden_pago.estado <> 'elaboracion' $filtro_op) AND
								(orden_pago.anio='" . $anio_fiscal . "')

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
								   '' AS ROC,
								   '02' AS OrdenLista
							FROM
								pagos_financieros,
								partidas_orden_pago
							WHERE
								(pagos_financieros.idorden_pago=partidas_orden_pago.idorden_pago AND
								partidas_orden_pago.idmaestro_presupuesto='" . $idregistro . "') $filtro_pf


							ORDER BY OrdenLista, fecha_solicitud";

                    $query_detalle = mysql_query($sql) or die($sql . mysql_error());
                    $rows_detalle  = mysql_num_rows($query_detalle);
                    for ($k = 0; $k < $rows_detalle; $k++) {
                        $detalle         = mysql_fetch_array($query_detalle);
                        $monto           = number_format($detalle['monto_acreditar'], 2, ',', '.');
                        list($a, $m, $d) = SPLIT('[/.-]', $detalle['fecha_solicitud']);
                        $fecha_solicitud = $d . "/" . $m . "/" . $a;

                        $tr5 = "font-size:12px; color:#000000; ";
                        $tr7 = "font-size:12px; color:#FF0000; ";

                        if ($detalle['tipo'] == "Traslado(+)" || $detalle['tipo'] == "Credito Adicional" || $detalle['tipo'] == "Rectificacion(+)") {
                            $aumento     = $monto;
                            $disminucion = "";
                            $compromisos = "";
                            $causado     = "";
                            $modificado  = $monto;
                            if ($detalle['estado'] == "Anulado") {
                                $anulado = "(ANULADO)";
                                $aumento = "($aumento)";} else {
                                $anulado = "";
                            }

                            $concepto = $detalle['tipo'] . " " . $anulado;

                        } else if ($detalle['tipo'] == "Traslado(-)" || $detalle['tipo'] == "Disminucion Presupuestaria" || $detalle['tipo'] == "Rectificacion(-)") {
                            $disminucion = $monto;
                            $aumento     = "";
                            $compromisos = "";
                            $causado     = "";
                            $modificado  = $monto;
                            if ($detalle['estado'] == "Anulado") {
                                $anulado     = "(ANULADO)";
                                $disminucion = "($disminucion)";} else {
                                $anulado = "";
                            }

                            $concepto = $detalle['tipo'] . " " . $anulado;
                        } else if ($detalle['tipo'] == "Orden de Compra/Servicio") {
                            $tipo        = $detalle['tipo'];
                            $disminucion = "";
                            $aumento     = "";
                            $modificado  = "";
                            $compromisos = $monto;
                            $causado     = "";
                            if ($detalle['estado'] == "anulado") {
                                $anulado     = "(ANULADO)";
                                $compromisos = "($compromisos)";} else {
                                $anulado = "";
                            }

                            $concepto = $detalle['justificacion'] . " " . $anulado;
                        } else if ($detalle['tipo'] == "Orden de Pago") {
                            $tipo        = $detalle['tipo'];
                            $disminucion = "";
                            $aumento     = "";
                            $modificado  = "";
                            $causado     = $monto;
                            if ($detalle['Causa'] == "si") {

                                if ($detalle['estado'] == "anulado") {
                                    $anulado = "(ANULADO)";
                                    $causado = "($causado)";
                                } else {
                                    $anulado  = "";
                                    $sql_comp = mysql_query("select orden_compra_servicio.numero_orden from orden_compra_servicio,relacion_pago_compromisos
															where relacion_pago_compromisos.idorden_pago='" . $detalle['id'] . "'
																and orden_compra_servicio.idorden_compra_servicio=relacion_pago_compromisos.idorden_compra_servicio");
                                    $field_oc       = mysql_fetch_array($sql_comp);
                                    $nro_compromiso = $field_oc["numero_orden"];
                                }
                            }
                            if ($detalle['Compromete'] == "si") {
                                $compromisos = $monto;
                                if ($detalle['estado'] == "anulado") {
                                    $anulado     = "(ANULADO)";
                                    $compromisos = "($compromisos)";
                                } else { $anulado = "";}
                            } else { $compromisos = '';}
                            $concepto = $detalle['justificacion'] . " " . $anulado;
                            if ($detalle['estado'] == "pagada" or $detalle['estado'] == "procesado") {
                                $imprimir_ocs = "SI";
                                $imprimir_op  = "SI";
                            }
                        } else if ($detalle['tipo'] == "Cheque") {
                            $disminucion = "";
                            $aumento     = "";
                            $modificado  = "";
                            $compromisos = "";
                            $causado     = "";
                            $sql_comp    = mysql_query("select orden_pago.numero_orden from orden_pago,pagos_financieros
															where pagos_financieros.idpagos_financieros='" . $detalle['id'] . "'
																and orden_pago.idorden_pago=pagos_financieros.idorden_pago");
                            $field_oc     = mysql_fetch_array($sql_comp);
                            $nro_pago     = $field_oc["numero_orden"];
                            $sql_chequeop = mysql_query("select partidas_orden_pago.monto from partidas_orden_pago,pagos_financieros
																where pagos_financieros.idpagos_financieros='" . $detalle['id'] . "'
																	and partidas_orden_pago.idorden_pago=pagos_financieros.idorden_pago
																	and partidas_orden_pago.idmaestro_presupuesto='" . $idregistro . "'");
                            $fiel_chequeop = mysql_fetch_array($sql_chequeop);
                            if ($detalle["monto_acreditar"] > $fiel_chequeop['monto']) {
                                $pagado = number_format($fiel_chequeop['monto'], 2, ',', '.');
                            } else {
                                $pagado = $monto;
                            }
                            if ($detalle['estado'] == "anulado") {
                                $anulado = "(ANULADO)";
                                $pagado  = "($pagado)";
                            } else {
                                $anulado = "";

                            }
                            $concepto = $detalle['justificacion'] . " " . $anulado;
                            //$imprimir_op="SI";
                        } else if ($detalle['tipo'] == "Requisicion") {
                            $modificado    = "";
                            $concepto      = $detalle['justificacion'];
                            $precompromiso = number_format($detalle['monto_acreditar'], 2, ',', '');
                            $sum_precompromiso += $detalle['monto_acreditar'];
                        }

                        if ($chkaumentos == 1 and $detalle['tipo'] == "Traslado(+)" || $detalle['tipo'] == "Credito Adicional" || $detalle['tipo'] == "Rectificacion(+)") {
                            if ($anulado == "(ANULADO)") {$tr5 = $tr7;}
                            echo "
							<tr>
								<td style='$tr5' align='left'>" . $detalle['nro_solicitud'] . "</td>
								<td style='$tr5'>" . utf8_decode($fecha_solicitud . ' - ' . $concepto) . "</td>";
                            if ($chkinicial == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkaumentos == 1) {
                                echo "<td style='$tr5' align='right'>" . $aumento . "</td>";
                            }
                            if ($chkdisminuciones == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkmodificaciones == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkajustada == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkcompromiso == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkcausado == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkpagado == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkdisponible == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            echo "</tr>";

                        }

                        if ($chkdisminuciones == 1 and $detalle['tipo'] == "Traslado(-)" || $detalle['tipo'] == "Disminucion Presupuestaria" || $detalle['tipo'] == "Rectificacion(-)") {
                            if ($anulado == "(ANULADO)") {$tr5 = $tr7;}
                            echo "
							<tr>
								<td style='$tr5' align='left'>" . $detalle['nro_solicitud'] . "</td>
								<td style='$tr5'>" . utf8_decode($fecha_solicitud . ' - ' . $concepto) . "</td>";
                            if ($chkinicial == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkaumentos == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkdisminuciones == 1) {
                                echo "<td style='$tr5' align='right'>" . $disminucion . "</td>";
                            }
                            if ($chkmodificaciones == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkajustada == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkcompromiso == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkcausado == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkpagado == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkdisponible == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            echo "</tr>";

                        }

                        if ($chkcompromiso == 1 and $detalle['tipo'] == "Orden de Compra/Servicio") {
                            if ($anulado == "(ANULADO)") {$tr5 = $tr7;}
                            echo "
							<tr>
								<td style='$tr5' align='left'>" . $detalle['nro_solicitud'] . "</td>
								<td style='$tr5'>" . utf8_decode($fecha_solicitud . ' - ' . $concepto) . "</td>";
                            if ($chkinicial == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkaumentos == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkdisminuciones == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkmodificaciones == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkajustada == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkcompromiso == 1) {
                                echo "<td style='$tr5' align='right'>" . $compromisos . "</td>";
                            }
                            if ($chkcausado == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkpagado == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkdisponible == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            echo "</tr>";
                            if ($chkjustificacionoc == 1) {
                                $sql = "SELECT
											numero_orden,
											justificacion
										FROM
											orden_compra_servicio
										WHERE idorden_compra_servicio='" . $detalle['id'] . "'";
                                $query_pagados = mysql_query($sql) or die($sql . mysql_error());
                                while ($field_pagados = mysql_fetch_array($query_pagados)) {
                                    echo "
									<tr>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='left'>" . utf8_decode($field_pagados['justificacion']) . "</td>";
                                    if ($chkinicial == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkaumentos == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkdisminuciones == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkmodificaciones == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkajustada == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkcompromiso == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkcausado == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkpagado == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkdisponible == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    echo "</tr>";

                                }
                            }
                        }

                        if ($chkcausado == 1 and $detalle['tipo'] == "Orden de Pago") {
                            if ($anulado == "(ANULADO)") {$tr5 = $tr7;}
                            echo "
						<tr>
							<td style='$tr5' align='left'>" . $detalle['nro_solicitud'] . "</td>
							<td style='$tr5'>" . utf8_decode($fecha_solicitud . " - " . $concepto . " - " . $nro_compromiso) . "</td>";

                            if ($chkinicial == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkaumentos == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkdisminuciones == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkmodificaciones == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkajustada == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkcompromiso == 1) {
                                echo "<td style='$tr5' align='right'>" . $compromisos . "</td>";
                            }
                            if ($chkcausado == 1) {
                                echo "<td style='$tr5' align='right'>" . $causado . "</td>";
                            }
                            if ($chkpagado == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkdisponible == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }

                            echo "</tr>";

                            if ($chkjustificacionop == 1) {
                                $sql = "SELECT
											op.numero_orden,
											op.justificacion
										FROM
											orden_pago op
										WHERE op.idorden_pago = '" . $detalle['id'] . "'";
                                $query_pagados = mysql_query($sql) or die($sql . mysql_error());
                                while ($field_pagados = mysql_fetch_array($query_pagados)) {
                                    echo "
									<tr>
										<td style='$tr5' align='right'></td>
										<td style='$tr5' align='left'>" . utf8_decode($field_pagados['justificacion']) . "</td>";
                                    if ($chkinicial == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkaumentos == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkdisminuciones == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkmodificaciones == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkajustada == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkcompromiso == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkcausado == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkpagado == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    if ($chkdisponible == 1) {
                                        echo "<td style='$tr5' align='right'></td>";
                                    }
                                    echo "</tr>";

                                }
                            }

                        }
                        //'".if($anulado=="(ANULADO)"){$tr6;}else{$tr5;}."'
                        if ($chkpagado == 1 and $detalle['tipo'] == "Cheque") {
                            if ($anulado == "(ANULADO)") {$tr5 = $tr7;}
                            echo "
							<tr>
							<td style='$tr5' align='left'>" . $detalle['nro_solicitud'] . "</td>
							<td style='$tr5'>" . utf8_decode($fecha_solicitud . ' - ' . $concepto . " - " . $nro_pago) . "</td>";

                            if ($chkinicial == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkaumentos == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkdisminuciones == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkmodificaciones == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkajustada == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkcompromiso == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkcausado == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }
                            if ($chkpagado == 1) {
                                echo "<td style='$tr5' align='right'>" . $pagado . "</td>";
                            }
                            if ($chkdisponible == 1) {
                                echo "<td style='$tr5' align='right'></td>";
                            }

                            echo "</tr>";

                        }

                    }
                }
                $disponible = 0;
            }

        }

        if ($num_rows_rendicion != 0) {
            $sum_aumento     = number_format($sum_raumento, 2, ',', '');
            $sum_disminucion = number_format($sum_rdisminucion, 2, ',', '');
            $sum_compromisos = number_format($sum_rcompromisos, 2, ',', '');
            $sum_causado     = number_format($sum_rcausado, 2, ',', '');
            $sum_pagado      = number_format($sum_rpagado, 2, ',', '');

            echo "
				<tr>
					<td style='$tr5' align='right'></td>
					<td style='$tr5' align='right'></td>";
            if ($chkinicial == 1) {
                echo "<td style='$tr5' align='right'></td>";
            }
            if ($chkaumentos == 1) {
                echo "<td style='$tr5' align='right'>=DECIMAL(" . $sum_aumento . "; 2)</td>";
            }
            if ($chkdisminuciones == 1) {
                echo "<td style='$tr5' align='right'>=DECIMAL(" . $sum_disminucion . "; 2)</td>";
            }
            if ($chkmodificaciones == 1) {
                echo "<td style='$tr5' align='right'></td>";
            }
            if ($chkajustada == 1) {
                echo "<td style='$tr5' align='right'></td>";
            }
            if ($chkcompromiso == 1) {
                echo "<td style='$tr5' align='right'>=DECIMAL(" . $sum_compromisos . "; 2)</td>
						<td style='$tr5' align='right'></td>";
            }
            if ($chkcausado == 1) {
                echo "<td style='$tr5' align='right'>=DECIMAL(" . $sum_causado . "; 2)</td>
						<td style='$tr5' align='right'></td>";
            }
            if ($chkpagado == 1) {
                echo "<td style='$tr5' align='right'>=DECIMAL(" . $sum_pagado . "; 2)</td>
							<td style='$tr5' align='right'></td>";
            }
            if ($chkdisponible == 1) {
                echo "<td style='$tr5' align='right'></td>
						<td style='$tr5' align='right'></td>";
            }
            echo "</tr>";

        } else {
            $sum_actual = $sum_original + $sum_modificado;
            $disponible = $sum_actual - $sum_compromiso;
            if ($sum_compromiso == 0 || $sum_actual == 0) {
                $pcompromiso = 0;
            } else {
                $pcompromiso = (float) (($sum_compromiso * 100) / $sum_actual);
            }

            $pcompromiso = number_format($pcompromiso, 2, ',', '');
            if ($sum_causado == 0 || $sum_actual == 0) {
                $pcausado = 0;
            } else {
                $pcausado = (float) (($sum_causado * 100) / $sum_actual);
            }

            $pcausado = number_format($pcausado, 2, ',', '');
            if ($sum_pagado == 0 || $sum_actual == 0) {
                $ppagado = 0;
            } else {
                $ppagado = (float) (($sum_pagado * 100) / $sum_actual);
            }

            $ppagado = number_format($ppagado, 2, ',', '');
            if ($disponible == 0 || $sum_actual == 0) {
                $pdisponible = 0;
            } else {
                $pdisponible = (float) (($disponible * 100) / $sum_actual);
            }

            $pdisponible       = number_format($pdisponible, 2, ',', '');
            $sum_original      = number_format($sum_original, 2, ',', '.');
            $sum_aumento       = number_format($sum_aumento, 2, ',', '.');
            $sum_disminucion   = number_format($sum_disminucion, 2, ',', '.');
            $sum_actual        = number_format($sum_actual, 2, ',', '.');
            $sum_compromiso    = number_format($sum_compromiso, 2, ',', '.');
            $sum_causado       = number_format($sum_causado, 2, ',', '.');
            $sum_pagado        = number_format($sum_pagado, 2, ',', '.');
            $sum_disponible    = number_format($sum_disponible, 2, ',', '.');
            $sum_modificado    = number_format($sum_modificado, 2, ',', '.');
            $sum_precompromiso = number_format($sum_precompromiso, 2, ',', '.');
            $sum_disponible    = number_format($disponible, 2, ',', '.');

            echo "
				<tr>
					<td style='$tr1' align='right'></td>
					<td style='$tr1' align='right'>TOTALES.....</td>";
            if ($chkinicial == 1) {
                echo "<td style='$tr1' align='right'>" . $sum_original . "</td>";
            }
            if ($chkaumentos == 1) {
                echo "<td style='$tr1' align='right'>" . $sum_aumento . "</td>";
            }
            if ($chkdisminuciones == 1) {
                echo "<td style='$tr1' align='right'>" . $sum_disminucion . "</td>";
            }
            if ($chkmodificaciones == 1) {
                echo "<td style='$tr1' align='right'>" . $sum_modificado . "</td>";
            }
            if ($chkajustada == 1) {
                echo "<td style='$tr1' align='right'>" . $sum_actual . "</td>";
            }
            if ($chkcompromiso == 1) {
                echo "<td style='$tr1' align='right'>" . $sum_compromiso . "</td>";
            }
            if ($chkcausado == 1) {
                echo "<td style='$tr1' align='right'>" . $sum_causado . "</td>";
            }
            if ($chkpagado == 1) {
                echo "<td style='$tr1' align='right'>" . $sum_causado . "</td>";
            }
            if ($chkdisponible == 1) {
                echo "<td style='$tr1' align='right'>" . $sum_disponible . "</td>";
            }
            echo "</tr>";

        }
        break;

    //    Disponibilidad Presupuestaria...
    case "disponibilidad_presupuestaria":
        $sql = "SELECT
					denominacion As TipoPresupuesto,
					(SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento = '" . $financiamiento . "') AS FuenteFinanciamiento
				FROM tipo_presupuesto
				WHERE idtipo_presupuesto = '" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------
        $filtro = "";
        echo "<table border='1'>";
        if ($financiamiento != "") {
            $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '" . $financiamiento . "')";
            echo "<tr><td colspan='5'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>";
        }
        if ($tipo_presupuesto != "") {
            $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
            echo "<tr><td colspan='5'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>";
        }
        if ($anio_fiscal != "") {
            $filtro .= " AND (maestro_presupuesto.anio = '" . $anio_fiscal . "')";
            echo "<tr><td colspan='5'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>";
        }
        echo "
		<tr>
			<th width='75' style='$titulo'>PARTIDA</th>
			<th width='700' style='$titulo'>DESCRIPCION</th>
			<th width='100' style='$titulo'>MONTO ACTUAL</th>
			<th width='100' style='$titulo'>MONTO DISPONIBLE</th>
			<th width='50' style='$titulo'>%</th>
		</tr>";
        //---------------------------------------------
        if ($idcategoria != "") {
            $filtro .= " AND (maestro_presupuesto.idcategoria_programatica = '" . $idcategoria . "')";
        }

        if ($idpartida != "") {
            $filtro .= " AND (maestro_presupuesto.idclasificador_presupuestario = '" . $idpartida . "')";
        }

        if ($idordinal != "") {
            $filtro .= " AND (maestro_presupuesto.idordinal = '" . $idordinal . "')";
        }

        //---------------------------------------------
        $sql = "(SELECT
					maestro_presupuesto.idcategoria_programatica AS IdCategoria,
					maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
					categoria_programatica.codigo AS CodCategoria,
					unidad_ejecutora.denominacion AS Unidad,
					clasificador_presupuestario.partida AS Par,
					clasificador_presupuestario.generica AS Gen,
					clasificador_presupuestario.especifica AS Esp,
					clasificador_presupuestario.sub_especifica AS Sesp,
					clasificador_presupuestario.denominacion AS NomPartida,
					maestro_presupuesto.idRegistro AS IdPresupuesto,
					SUM(maestro_presupuesto.monto_actual) AS MontoActual,
					SUM(maestro_presupuesto.total_compromisos) AS MontoCompromiso,
					SUM(maestro_presupuesto.pre_compromiso) AS MontoPreCompromiso,
					SUM(maestro_presupuesto.reservado_disminuir) AS MontoReservado,
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
					(maestro_presupuesto.idordinal=ordinal.idordinal) $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				UNION

				(SELECT
					maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
							WHERE (clasificador_presupuestario.partida=Par AND
									clasificador_presupuestario.generica=Gen AND
									clasificador_presupuestario.especifica='00' AND
									clasificador_presupuestario.sub_especifica='00')) AS NomPartida,
					maestro_presupuesto.idRegistro AS IdPresupuesto,
					SUM(maestro_presupuesto.monto_actual) AS MontoActual,
					SUM(maestro_presupuesto.total_compromisos) AS MontoCompromiso,
					SUM(maestro_presupuesto.pre_compromiso) AS MontoPreCompromiso,
					SUM(maestro_presupuesto.reservado_disminuir) AS MontoReservado,
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
					(clasificador_presupuestario.sub_especifica = '00') $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				UNION

				(SELECT
					maestro_presupuesto.idcategoria_programatica AS IdCategoria,
					maestro_presupuesto.idclasificador_presupuestario AS idPartida,
					categoria_programatica.codigo AS CodCategoria,
					unidad_ejecutora.denominacion AS Unidad,
					clasificador_presupuestario.partida AS Par,
					'00' AS Gen,
					'00' AS Esp,
					'00' AS Sesp,
					(SELECT clasificador_presupuestario.denominacion
						FROM clasificador_presupuestario
							WHERE (clasificador_presupuestario.partida=Par AND
									clasificador_presupuestario.generica='00' AND
									clasificador_presupuestario.especifica='00' AND
									clasificador_presupuestario.sub_especifica='00')) AS NomPartida,
					maestro_presupuesto.idRegistro AS IdPresupuesto,
					SUM(maestro_presupuesto.monto_actual) AS MontoActual,
					SUM(maestro_presupuesto.total_compromisos) AS MontoCompromiso,
					SUM(maestro_presupuesto.pre_compromiso) AS MontoPreCompromiso,
					SUM(maestro_presupuesto.reservado_disminuir) AS MontoReservado,
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
					(clasificador_presupuestario.sub_especifica = '00') $filtro)
					GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        for ($i = 0; $i < $rows; $i++) {
            $field = mysql_fetch_array($query);
            //    SI CAMBIA DE CATEGORIA LA IMPRIMO
            if ($field["IdCategoria"] != $IdCategoria) {
                //    --------------------------------
                if ($i != 0) {
                    //    IMPRIMO EL TOTAL DE LA CATEGORIA
                    if ($sum_actual_categoria != 0) {
                        $porcentaje_categoria = (float) $sum_disponible_categoria * 100 / $sum_actual_categoria;
                    } else {
                        $porcentaje_categoria = 0;
                    }

                    $psum_disponible_categoria = number_format($porcentaje_categoria, 2, ',', '');
                    $sum_actual_categoria      = number_format($sum_actual_categoria, 2, ',', '');
                    $sum_disponible_categoria  = number_format($sum_disponible_categoria, 2, ',', '');
                    //    --------------------------------
                    echo "
					<tr>
						<td style='$cattotal'></td>
						<td style='$cattotal'>" . utf8_decode('Total Categoría Programática:') . "</td>
						<td style='$cattotal' align='right'>=DECIMAL(" . $sum_actual_categoria . "; 2)</td>
						<td style='$cattotal' align='right'>=DECIMAL(" . $sum_disponible_categoria . "; 2)</td>
						<td style='$cattotal' align='right'>=DECIMAL(" . $psum_disponible_categoria . "; 2)</td>
					</tr>";
                    $sum_actual_categoria     = 0;
                    $sum_disponible_categoria = 0;
                }
                $IdCategoria = $field["IdCategoria"];
                echo "<tr><td colspan='5' style='$cat'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>";
            }
            $clasificador = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $monto_actual = number_format($field["MontoActual"], 2, ',', '');
            $disponible   = (float) ($field["MontoActual"] - $field["MontoCompromiso"] - $field["MontoPreCompromiso"] - $field["MontoReservado"]);
            if ($disponible == 0 || $field['MontoActual'] == 0) {
                $pdisponible = "0,00";
            } else {
                $porcentaje = (float) $disponible * 100 / $field['MontoActual'];
            }

            $pdisponible      = number_format($porcentaje, 2, ',', '');
            $monto_disponible = number_format($disponible, 2, ',', '');
            if ($field["Tipo"] == "partida") {
                $sum_total += $field["MontoOriginal"];
                echo "
				<tr>
					<td style='$par' align='center'>" . $clasificador . "</td>
					<td style='$par'>" . utf8_decode($field["NomPartida"]) . "</td>
					<td style='$par' align='right'>=DECIMAL(" . $monto_actual . "; 2)</td>
					<td style='$par' align='right'>=DECIMAL(" . $monto_disponible . "; 2)</td>
					<td style='$par' align='right'>=DECIMAL(" . $pdisponible . "; 2)</td>
				</tr>";
            } else if ($field["Tipo"] == "generica") {
                echo "
				<tr>
					<td style='$gen' align='center'>" . $clasificador . "</td>
					<td style='$gen'>" . utf8_decode($field["NomPartida"]) . "</td>
					<td style='$gen' align='right'>=DECIMAL(" . $monto_actual . "; 2)</td>
					<td style='$gen' align='right'>=DECIMAL(" . $monto_disponible . "; 2)</td>
					<td style='$gen' align='right'>=DECIMAL(" . $pdisponible . "; 2)</td>
				</tr>";
            } else if ($field["Tipo"] == "especifica") {
                $sum_actual += $field['MontoActual'];
                $sum_disponible += $disponible;
                $sum_actual_categoria += $field['MontoActual'];
                $sum_disponible_categoria += $disponible;
                if ($field['codordinal'] == "0000") {
                    echo "
					<tr>
						<td style='$esp' align='center'>" . $clasificador . "</td>
						<td style='$esp'>" . utf8_decode($field["NomPartida"]) . "</td>
						<td style='$esp' align='right'>=DECIMAL(" . $monto_actual . "; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(" . $monto_disponible . "; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(" . $pdisponible . "; 2)</td>
					</tr>";
                } else {
                    echo "
					<tr>
						<td style='$esp' align='center'>" . $clasificador . "</td>
						<td style='$esp'>" . utf8_decode($field['codordinal'] . ' ' . $field["nomordinal"]) . "</td>
						<td style='$esp' align='right'>=DECIMAL(" . $monto_actual . "; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(" . $monto_disponible . "; 2)</td>
						<td style='$esp' align='right'>=DECIMAL(" . $pdisponible . "; 2)</td>
					</tr>";
                }
            }
        }
        //    IMPRIMO EL TOTAL DE LA CATEGORIA
        $porcentaje_categoria      = (float) $sum_disponible_categoria * 100 / $sum_actual_categoria;
        $psum_disponible_categoria = number_format($porcentaje_categoria, 2, ',', '');
        $sum_actual_categoria      = number_format($sum_actual_categoria, 2, ',', '');
        $sum_disponible_categoria  = number_format($sum_disponible_categoria, 2, ',', '');
        //---------------------------------------------
        echo "
		<tr>
			<td style='$cattotal' align='center'></td>
			<td style='$cattotal'>" . utf8_decode('Total Categoría Programática:') . "</td>
			<td style='$cattotal' align='right'>=DECIMAL(" . $sum_actual_categoria . "; 2)</td>
			<td style='$cattotal' align='right'>=DECIMAL(" . $sum_disponible_categoria . "; 2)</td>
			<td style='$cattotal' align='right'>=DECIMAL(" . $psum_disponible_categoria . "; 2)</td>
		</tr>";
        //    IMPRIMO EL TOTAL
        $porcentaje      = (float) $sum_disponible * 100 / $sum_actual;
        $psum_disponible = number_format($porcentaje, 2, ',', '');
        $sum_actual      = number_format($sum_actual, 2, ',', '');
        $sum_disponible  = number_format($sum_disponible, 2, ',', '');
        //---------------------------------------------
        echo "
		<tr>
			<td style='$total' align='center'></td>
			<td style='$total'>" . utf8_decode('Total General:') . "</td>
			<td style='$total' align='right'>=DECIMAL(" . $sum_actual . "; 2)</td>
			<td style='$total' align='right'>=DECIMAL(" . $sum_disponible . "; 2)</td>
			<td style='$total' align='right'>=DECIMAL(" . $psum_disponible . "; 2)</td>
		</tr>
		</table>";
        break;

    /*    Disponibilidad Presupuestaria...
    case "disponibilidad_presupuestaria_periodo":
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
    echo "
    <tr>
    <th width='75' style='$titulo'>PARTIDA</th>
    <th width='700' style='$titulo'>DESCRIPCION</th>
    <th width='100' style='$titulo'>ASIG. INICIAL</th>
    <th width='100' style='$titulo'>AUMENTO</th>
    <th width='100' style='$titulo'>DISMINUCION</th>
    <th width='100' style='$titulo'>MODIFICADO</th>
    <th width='100' style='$titulo'>ASIG. AJUSTADA</th>
    <th width='100' style='$titulo'>PRE-COMPROMISO</th>
    <th width='100' style='$titulo'>COMPROMISO</th>
    <th width='50' style='$titulo'>%</th>
    <th width='100' style='$titulo'>CAUSADO</th>
    <th width='50' style='$titulo'>%</th>
    <th width='100' style='$titulo'>PAGADO</th>
    <th width='50' style='$titulo'>%</th>
    <th width='100' style='$titulo'>DISPONIBLE</th>
    <th width='50' style='$titulo'>%</th>
    </tr>";
    //----------------------------------------------------
    $filtro="";
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
    //
    if ($_GET['idcategoria'] != "" || $_GET['idcategoria'] != 0) $filtro=" AND (categoria_programatica.idcategoria_programatica='".$_GET['idcategoria']."') AND (maestro_presupuesto.idcategoria_programatica='".$_GET['idcategoria']."')";
    if ($_GET['idpartida']!="")    $filtro.=" AND (clasificador_presupuestario.idclasificador_presupuestario='".$_GET["idpartida"]."') AND (maestro_presupuesto.idclasificador_presupuestario='".$_GET['idpartida']."')";

    list($a, $m, $d)=SPLIT( '[/.-]', $desde); $idesde="$d-$m-$a";
    list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $ihasta="$d-$m-$a";
    list($a, $m, $d)=SPLIT( '[/.-]', $hasta_anterior); $ihasta_anterior="$d-$m-$a";
    //    CONSULTO
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
    //    -------------------------
    //    Imprimo la categoria programatica
    if ($field["idcategoria_programatica"]!=$cat) {
    $cat=$field["idcategoria_programatica"];
    echo "<tr><td colspan='16' style='$cat'>".$field["CodCategoria"]." - ".$field["UnidadEjecutora"]."</td></tr>";
    }
    //    -------------------------
    $Ejecutado1 = $field['Ejecutado1'] + $field['Ejecutado3'];
    $Ejecutado2 = $field['Ejecutado2'] + $field['Ejecutado4'];
    //    Imprimo la partida
    if ($partida != $flag) {
    //$sum_monto_original += $field["MontoOriginal"];
    $flag = $field["partida"].".".$field["generica"].".".$field["especifica"].".".$field["sub_especifica"];
    }
    //    -------------------------
    $partida=$field["partida"].".".$field["generica"].".".$field["especifica"].".".$field["sub_especifica"];
    if ($field['CodOrdinal'] == "0000") $denominacion_partida = utf8_decode($field["NombrePartida"]);
    else $denominacion_partida = utf8_decode($field['CodOrdinal'].' '.$field["Ordinal"]);
    //    -------------------------
    echo "
    <tr>
    <th style='$par'>".$partida."</th>
    <th style='$par' colspan='15' align='left'>".$denominacion_partida."</th>
    </tr>";
    //    -------------------------
    //    Imprimo hasta la fecha
    $monto_original=number_format($field["MontoOriginal"], 2, ',', '');
    if ($field['CodOrdinal'] == "0000" && $field['sub_especifica'] == "00") $sum_monto_original += $field["MontoOriginal"];
    $aumento1=$field["MCredito1"]+$field["MReceptora1"]+$field["MRectificacion1"];
    $sum_aumento += $aumento1;
    $total_aumento1=number_format($aumento1, 2, ',', '');
    $disminucion1=$field["MDisminucion1"]+$field["MCedentes1"]+$field["MRectificadora1"];
    $sum_disminucion += $disminucion1;
    $total_disminucion1=number_format($disminucion1, 2, ',', '');
    $modificado1 = $aumento1 -$disminucion1; $total_modificado1=number_format($modificado1, 2, ',', '');
    $actual1=$field["MontoOriginal"]+$aumento1-$disminucion1; $monto_actual1=number_format($actual1, 2, ',', '');
    $ejecutado1=number_format($Ejecutado1, 2, ',', '');
    $disponible1=$actual1-$Ejecutado1;
    $monto_disponible1=number_format($disponible1, 2, ',', '');
    if ($Ejecutado1=="" || $actual1==0) $pejecutado1="0,00"; else $pejecutado1=($Ejecutado1*100)/$actual1;
    $pejecutado1=number_format($pejecutado1, 2, ',', '');
    if ($disponible1==0 || $actual1==0) $pdisponible1="0,00"; else $pdisponible1=($disponible1*100)/$actual1;
    $pdisponible1=number_format($pdisponible1, 2, ',', '');//--
    $compromiso1=number_format($Ejecutado1, 2, ',', '');
    $sum_compromiso += $Ejecutado1;
    if ($Ejecutado1==0 || $actual1==0) $pcompromiso1="0,00"; else $pcompromiso1=($Ejecutado1*100)/$actual1;
    $pcompromiso1=number_format($pcompromiso1, 2, ',', '');
    $causado1=number_format($field["Causado1"], 2, ',', '');
    $sum_causado += $field['Causado1'];
    if ($field["Causado1"]==0 || $actual1==0) $pcausado1="0,00"; else $pcausado1=($field["Causado1"]*100)/$actual1;
    $pcausado1=number_format($pcausado1, 2, ',', '');
    $pagado1=number_format($field["Pagado1"], 2, ',', '');
    $sum_pagado += $field['Pagado1'];
    if ($field["Pagado1"]==0 || $actual1==0) $ppagado1="0,00"; else $ppagado1=($field["Pagado1"]*100)/$actual1;
    $ppagado1=number_format($ppagado1, 2, ',', '');

    $denominacion_partida = 'Hasta el: '.$ihasta_anterior;
    echo "
    <tr>
    <td style='$par' align='center'></td>
    <th style='$par' align='left'>".$denominacion_partida."</th>
    <td style='$par' align='right'>=DECIMAL(".$monto_original."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$total_aumento1."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$total_disminucion1."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$total_modificado1."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$total_actual1."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$precompromiso."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$compromiso1."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$pcompromiso1."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$causado1."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$pcausado1."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$pagado1."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$ppagado1."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$monto_disponible1."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$pdisponible1."; 2)</td>
    </tr>";
    //    -------------------------
    //    Imprimimo en el periodo
    $aumento2=$field["MCredito2"]+$field["MReceptora2"]+$field["MRectificacion2"];
    $sum_aumento += $aumento2;
    $total_aumento2=number_format($aumento2, 2, ',', '');
    $disminucion2=$field["MDisminucion2"]+$field["MCedentes2"]+$field["MRectificadora2"];
    $sum_disminucion += $disminucion2;
    $total_disminucion2=number_format($disminucion2, 2, ',', '');
    $modificado2 = $aumento2 -$disminucion2; $total_modificado2=number_format($modificado2, 2, ',', '');
    $actual2=$actual1+$aumento2-$disminucion2; $monto_actual2=number_format($actual2, 2, ',', '');    $sum_actual+=$actual2;
    $ejecutado2=number_format($Ejecutado2, 2, ',', '');
    $disponible2=$disponible1-$Ejecutado2+$modificado2;
    if ($field['sub_especifica'] == "00") $sum_disponible += $disponible2;
    $monto_disponible2=number_format($disponible2, 2, ',', '');
    if ($Ejecutado2=="" || $actual2==0) $pejecutado2="0,00"; else $pejecutado2=($Ejecutado2*100)/$actual2;
    $pejecutado2=number_format($pejecutado2, 2, ',', '');
    if ($disponible2==0 || $actual2==0) $pdisponible2="0,00"; else $pdisponible2=($disponible2*100)/$actual2;
    $pdisponible2=number_format($pdisponible2, 2, ',', '');//--
    $compromiso2=number_format($Ejecutado2, 2, ',', '');
    $sum_compromiso += $Ejecutado2;
    if ($Ejecutado2==0 || $actual2==0) $pcompromiso2="0,00"; else $pcompromiso2=($Ejecutado2*100)/$actual2;
    $pcompromiso2=number_format($pcompromiso2, 2, ',', '');
    $causado2=number_format($field["Causado2"], 2, ',', '');
    $sum_causado += $field['Causado2'];
    if ($field["Causado2"]==0 || $actual2==0) $pcausado2="0,00"; else $pcausado2=($field["Causado2"]*100)/$actual2;
    $pcausado2=number_format($pcausado2, 2, ',', '');
    $pagado2=number_format($field["Pagado2"], 2, ',', '');
    $sum_pagado += $field['Pagado2'];
    if ($field["Pagado2"]==0 || $actual2==0) $ppagado2="0,00"; else $ppagado2=($field["Pagado2"]*100)/$actual2;
    $ppagado2=number_format($ppagado2, 2, ',', '');

    $denominacion_partida = 'En el periodo: '.$idesde.' hasta '.$ihasta;
    echo "
    <tr>
    <td style='$par' align='center'></td>
    <th style='$par' align='left'>".$denominacion_partida."</th>
    <td style='$par' align='right'></td>
    <td style='$par' align='right'>=DECIMAL(".$total_aumento2."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$total_disminucion2."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$total_modificado2."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$total_actual2."; 2)</td>
    <td style='$par' align='right'></td>
    <td style='$par' align='right'>=DECIMAL(".$compromiso2."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$pcompromiso2."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$causado2."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$pcausado2."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$pagado2."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$ppagado2."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$monto_disponible2."; 2)</td>
    <td style='$par' align='right'>=DECIMAL(".$pdisponible2."; 2)</td>
    </tr>";

    //    ------------------------------------------------------------
    //    Imprimo el detalle por partida
    //    ------------------------------------------------------------
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
    $monto=number_format($detalle['monto_acreditar'], 2, ',', '');
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
    $denominacion_partida = $detalle['nro_solicitud'].' '.$fecha_solicitud.' '.utf8_decode($detalle['tipo']);

    echo "
    <tr>
    <td style='$esp' align='center'>$partida</td>
    <td style='$esp'>".$denominacion_partida."</td>
    <td style='$esp' align='right'></td>
    <td style='$esp' align='right'>=DECIMAL(".$aumento."; 2)</td>
    <td style='$esp' align='right'>=DECIMAL(".$disminucion."; 2)</td>
    <td style='$esp' align='right'>=DECIMAL(".$modificado."; 2)</td>
    <td style='$esp' align='right'>=DECIMAL(".$monto_actual."; 2)</td>
    <td style='$esp' align='right'>=DECIMAL(".$precompromiso."; 2)</td>
    <td style='$esp' align='right'>=DECIMAL(".$compromisos."; 2)</td>
    <td style='$esp' align='right'></td>
    <td style='$esp' align='right'>=DECIMAL(".$causado."; 2)</td>
    <td style='$esp' align='right'></td>
    <td style='$esp' align='right'>=DECIMAL(".$pagado."; 2)</td>
    <td style='$esp' align='right'></td>
    <td style='$esp' align='right'>=DECIMAL(".$disponible."; 2)</td>
    <td style='$esp' align='right'></td>
    </tr>";
    $disminucion=""; $aumento=""; $compromisos=""; $causado=""; $pagado=""; $anulado=""; $monto_actual=""; $precompromiso=""; $imprimir_ocs=""; $imprimir_op=""; $ordenes="";
    }
    }
    }

    //    Imprimo los totales
    $sum_modificado = $sum_aumento - $sum_disminucion;
    $sum_actual = $sum_monto_original + $sum_modificado;
    $sum_disponible = $sum_actual - $sum_compromiso;
    if ($sum_actual==0) $pcompromiso=0; else $pcompromiso=(float) (($sum_compromiso*100)/$sum_actual); $pcompromiso=number_format($pcompromiso, 2, ',', '');
    if ($sum_actual==0) $pcausado=0; else $pcausado=(float) (($sum_causado*100)/$sum_actual); $pcausado=number_format($pcausado, 2, ',', '');
    if ($sum_actual==0) $ppagado=0; else $ppagado=(float) (($sum_pagado*100)/$sum_actual); $ppagado=number_format($ppagado, 2, ',', '');
    if ($sum_actual==0) $pdisponible=0; else $pdisponible=(float) (($disponible*100)/$sum_actual); $pdisponible=number_format($pdisponible, 2, ',', '');

    echo "
    <tr>
    <td style='$total' align='center'></td>
    <td style='$total' align='center'></td>
    <td style='$total' align='right'>=DECIMAL(".$sum_monto_original."; 2)</td>
    <td style='$total' align='right'>=DECIMAL(".$sum_aumento."; 2)</td>
    <td style='$total' align='right'>=DECIMAL(".$sum_disminucion."; 2)</td>
    <td style='$total' align='right'>=DECIMAL(".$sum_modificado."; 2)</td>
    <td style='$total' align='right'>=DECIMAL(".$sum_actual."; 2)</td>
    <td style='$total' align='right'>=DECIMAL(".$sum_compromiso."; 2)</td>
    <td style='$total' align='right'>=DECIMAL(".$pcompromiso."; 2)</td>
    <td style='$total' align='right'>=DECIMAL(".$sum_causado."; 2)</td>
    <td style='$total' align='right'>=DECIMAL(".$pcausado."; 2)</td>
    <td style='$total' align='right'>=DECIMAL(".$sum_pagado."; 2)</td>
    <td style='$total' align='right'>=DECIMAL(".$ppagado."; 2)</td>
    <td style='$total' align='right'>=DECIMAL(".$sum_disponible."; 2)</td>
    <td style='$total' align='right'>=DECIMAL(".$pdisponible."; 2)</td>
    </tr>
    </table>";
    break;
     */
    //    Resumen por Actividades...
    case "resumen_actividades":
        $sql = "SELECT
					denominacion As TipoPresupuesto,
					(SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento = '" . $financiamiento . "') AS FuenteFinanciamiento
				FROM tipo_presupuesto
				WHERE idtipo_presupuesto = '" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------
        $filtro = "";
        echo "<table border='1'>";
        if ($financiamiento != "") {
            $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '" . $financiamiento . "')";
            echo "<tr><td colspan='16'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>";
        }
        if ($tipo_presupuesto != "") {
            $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
            echo "<tr><td colspan='16'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>";
        }
        if ($anio_fiscal != "") {
            $filtro .= " AND (maestro_presupuesto.anio = '" . $anio_fiscal . "')";
            echo "<tr><td colspan='16'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>";
        }
        echo "
		<tr>
			<th width='75' style='$titulo'>PARTIDA</th>
			<th width='825' style='$titulo'>DESCRIPCION</th>
			<th width='100' style='$titulo'>ASIG. INICIAL</th>
			<th width='100' style='$titulo'>AUMENTOS</th>
			<th width='100' style='$titulo'>DISMINUCION</th>
			<th width='100' style='$titulo'>MODIFICACIONES</th>
			<th width='100' style='$titulo'>ASIG. AJUSTADA</th>
			<th width='100' style='$titulo'>PRE-COMPROMISO</th>
			<th width='100' style='$titulo'>COMPROMISO</th>
			<th width='50' style='$titulo'>% COMP.</th>
			<th width='100' style='$titulo'>CAUSADO</th>
			<th width='50' style='$titulo'>% CAU.</th>
			<th width='100' style='$titulo'>PAGADO</th>
			<th width='50' style='$titulo'>% PAG.</th>
			<th width='100' style='$titulo'>DISPONIBLE</th>
			<th width='50' style='$titulo'>% DISP.</th>
		</tr>";
        //---------------------------------------------
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
					  'especifica' AS Tipo
				FROM
					  maestro_presupuesto,
					  categoria_programatica,
					  unidad_ejecutora,
					  clasificador_presupuestario
				WHERE
					  ((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					  (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					  (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp))

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
						'generica' AS Tipo
					FROM
						maestro_presupuesto,
						categoria_programatica,
						unidad_ejecutora,
						clasificador_presupuestario
					WHERE
						((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						(clasificador_presupuestario.sub_especifica='00') $filtro)
					GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp))

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
						'partida' AS Tipo
					FROM
						maestro_presupuesto,
						categoria_programatica,
						unidad_ejecutora,
						clasificador_presupuestario
					WHERE
						((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						(clasificador_presupuestario.sub_especifica='00') $filtro)
					GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp))

				ORDER BY CodCategoria, Par, Gen, Esp, Sesp";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $i     = 0;
        while ($field = mysql_fetch_array($query)) {
            $clasificador  = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $formulado     = number_format($field["Formulado"], 2, ',', '');
            $modificado    = number_format(($field["TotalAumento"] - $field['TotalDisminucion']), 2, ',', '');
            $actual        = number_format($field["Actual"], 2, ',', '');
            $compromiso    = number_format($field["Compromiso"], 2, ',', '');
            $precompromiso = number_format($field["PreCompromiso"], 2, ',', '');
            $causado       = number_format($field["Causado"], 2, ',', '');
            $pagado        = number_format($field["Pagado"], 2, ',', '');
            if ($chkrestar) {
                $disponible = number_format(($field["Actual"] - $field["PreCompromiso"] - $field["Compromiso"] - $field["ReservadoDisminuir"]), 2, ',', '');
            } else {
                $disponible = number_format(($field["Actual"] - $field["Compromiso"] - $field["ReservadoDisminuir"]), 2, ',', '');
            }

            if ($field["Compromiso"] == 0 or $field["Actual"] == 0) {
                $pcompromiso = "0";
            } else {
                $pcompromiso = (float) (($field["Compromiso"] * 100) / $field["Actual"]);
            }

            $pcompromiso = number_format($pcompromiso, 2, ',', '');
            if ($field["Causado"] == 0 or $field["Actual"] == 0) {
                $pcausado = "0";
            } else {
                $pcausado = (float) (($field["Causado"] * 100) / $field["Actual"]);
            }

            $pcausado = number_format($pcausado, 2, ',', '');
            if ($field["Pagado"] == 0 or $field["Actual"] == 0) {
                $ppagado = "0";
            } else {
                $ppagado = (float) (($field["Pagado"] * 100) / $field["Actual"]);
            }

            $ppagado = number_format($ppagado, 2, ',', '');
            if ($field["Compromiso"] == 0 or $field["Actual"] == 0) {
                $pdisponible = "0";
            } else
            if (($field["Actual"] - $field["PreCompromiso"] - $field["Compromiso"] - $field["ReservadoDisminuir"]) == 0) {
                $pdisponible = "0";
            } else {
                $pdisponible = (float) ((($field["Actual"] - $field["Compromiso"]) * 100) / $field["Actual"]);
            }

            $pdisponible = number_format($pdisponible, 2, ',', '');
            //    SI CAMBIA DE CATEGORIA LA IMPRIMO
            if ($i == 0) {
                $IdCategoria  = $field["IdCategoria"];
                $CodCategoria = $field["CodCategoria"];
                $Unidad       = utf8_decode($field["Unidad"]);
            }
            if ($field["IdCategoria"] != $IdCategoria) {
                if ($sum_compromiso == 0 or $sum_actual == 0) {
                    $tpcompromiso = 0;
                } else {
                    $tpcompromiso = (float) (($sum_compromiso * 100) / $sum_actual);
                }

                $tpcompromiso = number_format($tpcompromiso, 2, ',', '');
                if ($sum_causado == 0 or $sum_actual) {
                    $tpcausado = 0;
                } else {
                    $tpcausado = (float) (($sum_causado * 100) / $sum_actual);
                }

                $tpcausado = number_format($tpcausado, 2, ',', '');
                if ($sum_pagado == 0 or $sum_actual == 0) {
                    $tppagado = 0;
                } else {
                    $tppagado = (float) (($sum_pagado * 100) / $sum_actual);
                }

                $tppagado = number_format($tppagado, 2, ',', '');
                if ($sum_disponible == 0 or $sum_actual == 0) {
                    $tpdisponible = 0;
                } else {
                    $tpdisponible = (float) (($sum_disponible * 100) / $sum_actual);
                }

                $tpdisponible      = number_format($tpdisponible, 2, ',', '');
                $sum_aumento       = number_format($sum_aumento, 2, ',', '');
                $sum_disminucion   = number_format($sum_disminucion, 2, ',', '');
                $sum_formulado     = number_format($sum_formulado, 2, ',', '');
                $sum_modificado    = number_format($sum_modificado, 2, ',', '');
                $sum_actual        = number_format($sum_actual, 2, ',', '');
                $sum_precompromiso = number_format($sum_precompromiso, 2, ',', '');
                $sum_compromiso    = number_format($sum_compromiso, 2, ',', '');
                $sum_causado       = number_format($sum_causado, 2, ',', '');
                $sum_pagado        = number_format($sum_pagado, 2, ',', '');
                $sum_disponible    = number_format($sum_disponible, 2, ',', '');

                echo "
				<tr>
					<td style='$par'>" . $CodCategoria . "</td>
					<td style='$par'>" . utf8_decode($Unidad) . "</td>
					<td style='$par' align='right'>=DECIMAL(" . $sum_formulado . "; 2)</td>
					<td style='$par' align='right'>=DECIMAL(" . $aum_aumento . "; 2)</td>
					<td style='$par' align='right'>=DECIMAL(" . $sum_disminucion . "; 2)</td>
					<td style='$par' align='right'>=DECIMAL(" . $sum_modificado . "; 2)</td>
					<td style='$par' align='right'>=DECIMAL(" . $sum_actual . "; 2)</td>
					<td style='$par' align='right'>=DECIMAL(" . $sum_precompromiso . "; 2)</td>
					<td style='$par' align='right'>=DECIMAL(" . $sum_compromiso . "; 2)</td>
					<td style='$par' align='right'>" . $tpcompromiso . " %</td>
					<td style='$par' align='right'>=DECIMAL(" . $sum_causado . "; 2)</td>
					<td style='$par' align='right'>" . $tpcausado . " %</td>
					<td style='$par' align='right'>=DECIMAL(" . $sum_pagado . "; 2)</td>
					<td style='$par' align='right'>" . $tppagado . " %</td>
					<td style='$par' align='right'>=DECIMAL(" . $sum_disponible . "; 2)</td>
					<td style='$par' align='right'>" . $tpdisponible . " %</td>
				</tr>";

                $sum_aumento       = "";
                $sum_disminucion   = "";
                $sum_formulado     = "";
                $sum_actual        = "";
                $sum_modificado    = "";
                $sum_precompromiso = "";
                $sum_precompromiso = "";
                $sum_compromiso    = "";
                $tpcompromiso      = "";
                $sum_causado       = "";
                $tpcausado         = "";
                $sum_pagado        = "";
                $tppagado          = "";
                $sum_disponible    = "";
                $tpdisponible      = "";

                $IdCategoria  = $field["IdCategoria"];
                $CodCategoria = $field["CodCategoria"];
                $Unidad       = utf8_decode($field["Unidad"]);
            }

            if ($field["Tipo"] == "partida") {
                $sum_formulado += $field["Formulado"];
                $sum_modificado += ($field["TotalAumento"] - $field['TotalDisminucion']);
                $sum_aumento += $field["TotalAumento"];
                $sum_disminucion += $field['TotalDisminucion'];
                $sum_actual += $field["Actual"];
                $sum_compromiso += $field["Compromiso"];
                $sum_precompromiso += $field["PreCompromiso"];
                $sum_causado += $field["Causado"];
                $sum_pagado += $field["Pagado"];
                if ($chkrestar) {
                    $sum_disponible += ($field["Actual"] - $field["PreCompromiso"] - $field["Compromiso"] - $field["ReservadoDisminuir"]);
                } else {
                    $sum_disponible += ($field["Actual"] - $field["PreCompromiso"] - $field["ReservadoDisminuir"]);
                }

            }
            $i++;
        }
        if ($sum_compromiso == 0 or $sum_actual == 0) {
            $tpcompromiso = 0;
        } else {
            $tpcompromiso = (float) (($sum_compromiso * 100) / $sum_actual);
        }

        $tpcompromiso = number_format($tpcompromiso, 2, ',', '');
        if ($sum_causado == 0 or $sum_actual) {
            $tpcausado = 0;
        } else {
            $tpcausado = (float) (($sum_causado * 100) / $sum_actual);
        }

        $tpcausado = number_format($tpcausado, 2, ',', '');
        if ($sum_pagado == 0 or $sum_actual == 0) {
            $tppagado = 0;
        } else {
            $tppagado = (float) (($sum_pagado * 100) / $sum_actual);
        }

        $tppagado = number_format($tppagado, 2, ',', '');
        if ($sum_disponible == 0 or $sum_actual == 0) {
            $tpdisponible = 0;
        } else {
            $tpdisponible = (float) (($sum_disponible * 100) / $sum_actual);
        }

        $tpdisponible      = number_format($tpdisponible, 2, ',', '');
        $sum_aumento       = number_format($sum_aumento, 2, ',', '');
        $sum_disminucion   = number_format($sum_disminucion, 2, ',', '');
        $sum_formulado     = number_format($sum_formulado, 2, ',', '');
        $sum_modificado    = number_format($sum_modificado, 2, ',', '');
        $sum_actual        = number_format($sum_actual, 2, ',', '');
        $sum_precompromiso = number_format($sum_precompromiso, 2, ',', '');
        $sum_compromiso    = number_format($sum_compromiso, 2, ',', '');
        $sum_causado       = number_format($sum_causado, 2, ',', '');
        $sum_pagado        = number_format($sum_pagado, 2, ',', '');
        $sum_disponible    = number_format($sum_disponible, 2, ',', '');

        echo "
		<tr>
			<td style='$par'>" . $CodCategoria . "</td>
			<td style='$par'>" . utf8_decode($Unidad) . "</td>
			<td style='$par' align='right'>=DECIMAL(" . $sum_formulado . "; 2)</td>
			<td style='$par' align='right'>=DECIMAL(" . $aum_aumento . "; 2)</td>
			<td style='$par' align='right'>=DECIMAL(" . $sum_disminucion . "; 2)</td>
			<td style='$par' align='right'>=DECIMAL(" . $sum_modificado . "; 2)</td>
			<td style='$par' align='right'>=DECIMAL(" . $sum_actual . "; 2)</td>
			<td style='$par' align='right'>=DECIMAL(" . $sum_precompromiso . "; 2)</td>
			<td style='$par' align='right'>=DECIMAL(" . $sum_compromiso . "; 2)</td>
			<td style='$par' align='right'>" . $tpcompromiso . " %</td>
			<td style='$par' align='right'>=DECIMAL(" . $sum_causado . "; 2)</td>
			<td style='$par' align='right'>" . $tpcausado . " %</td>
			<td style='$par' align='right'>=DECIMAL(" . $sum_pagado . "; 2)</td>
			<td style='$par' align='right'>" . $tppagado . " %</td>
			<td style='$par' align='right'>=DECIMAL(" . $sum_disponible . "; 2)</td>
			<td style='$par' align='right'>" . $tpdisponible . " %</td>
		</tr>
		</table>";
        break;

    //    Movimientos por Partidas...
    case "movimientos_por_partida":
        $tr1   = "background-color:#999999; font-size:12px;";
        $tr2   = "font-size:12px; color:#000000; font-weight:bold;";
        $tr3   = "background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
        $tr4   = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5   = "font-size:12px; color:#000000;";
        $tr6   = "background-color:#999999; font-size:12px; border:1px solid #000000;";
        $tr7   = "font-size:12px; color:#000000; border:1px solid #000000;";
        $tr55  = "font-size:12px; color:#0000FF";
        $tr22  = "font-size:12px; color:#0000FF; font-weight:bold;";
        $tr555 = "font-size:12px; color:#FF0000";
        $tr222 = "font-size:12px; color:#FF0000; font-weight:bold;";
        //---------------------------------------------
        $sql   = "SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='" . $financiamiento . "') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //----------------------------------------------------
        if ($idordinal != "") {
            $where_ordinal = " AND maestro_presupuesto.idordinal = '" . $idordinal . "'";
        }
        //    CONSULTO
        $sql = "SELECT categoria_programatica.codigo,
					 unidad_ejecutora.denominacion denounidad,
					 clasificador_presupuestario.denominacion,
					 clasificador_presupuestario.partida,
					 clasificador_presupuestario.generica,
					 clasificador_presupuestario.especifica,
					 clasificador_presupuestario.sub_especifica,
					 ordinal.codigo as codordinal,
					 ordinal.denominacion as nomordinal,
					 maestro_presupuesto.monto_original,
					 maestro_presupuesto.total_disminucion,
					 maestro_presupuesto.reservado_disminuir,
					 maestro_presupuesto.pre_compromiso,
					 maestro_presupuesto.solicitud_aumento,
					 maestro_presupuesto.total_aumento,
					 maestro_presupuesto.monto_actual,
					 maestro_presupuesto.total_compromisos,
					 maestro_presupuesto.total_causados,
					 maestro_presupuesto.total_pagados,
					 maestro_presupuesto.idRegistro
				FROM
					 categoria_programatica,
					 unidad_ejecutora,
					 clasificador_presupuestario,
					 ordinal,
					 maestro_presupuesto
				WHERE
					 (categoria_programatica.idcategoria_programatica='" . $idcategoria . "' AND
					 categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					 (clasificador_presupuestario.idclasificador_presupuestario='" . $idpartida . "') AND
					 (maestro_presupuesto.idcategoria_programatica='" . $idcategoria . "' AND
					 maestro_presupuesto.idclasificador_presupuestario='" . $idpartida . "' AND
					 maestro_presupuesto.idordinal=ordinal.idordinal)
					 $where_ordinal AND
					 (maestro_presupuesto.anio='" . $anio_fiscal . "' AND
					 maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
					 maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "')";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $field = mysql_fetch_array($query);

        $partida = $field['partida'] . "." . $field['generica'] . "." . $field['especifica'] . "." . $field['sub_especifica'] . " " . $field['codordinal'] . " - ";
        if ($idordinal != "") {
            $partida .= $field['nomordinal'];
        } else {
            $partida .= $field['denominacion'];
        }

        $monto_original              = number_format($field[9], 2, ',', '');
        $total_disminucion           = number_format($field[10], 2, ',', '');
        $total_reservado_disminucion = number_format($field[11], 2, ',', '');

        // PRE COMPROMISO

        $total_pre_compromiso = number_format($field[12], 2, ',', '');
        if ($field[12] == 0) {
            $ptotal_precompromiso = 0;
        } else {
            $ptotal_precompromiso = (float) ($field[12] * 100 / $field[15]);
        }

        $ptotal_precompromiso = number_format($ptotal_precompromiso, 2, ',', '');
        $dtotal_precompromiso = (float) ($field[15] - $field[12] - $field[11] - $field[16]);
        $dtotal_precompromiso = number_format($dtotal_precompromiso, 2, ',', '');
        if ($field[12] == 0) {
            $pdtotal_precompromiso = 0;
        } else {
            $pdtotal_precompromiso = (float) (($field[15] - $field[12] - $field[11]) * 100 / $field[15]);
        }

        $pdtotal_precompromiso = number_format($pdtotal_precompromiso, 2, ',', '');

        $total_solicitud_aumento = number_format($field[13], 2, ',', '');
        $total_aumento           = number_format($field[14], 2, ',', '');
        $total_actual            = number_format($field[15], 2, ',', '');

        // COMPROMISO

        $total_compromiso = number_format($field[16], 2, ',', '');
        if ($field[16] == 0) {
            $ptotal_compromiso = 0;
        } else {
            $ptotal_compromiso = (float) ($field[16] * 100 / $field[15]);
        }

        $ptotal_compromiso = number_format($ptotal_compromiso, 2, ',', '');
        $dtotal_compromiso = (float) ($field[15] - $field[16] - $field[12] - $field[11]);
        $dtotal_compromiso = number_format($dtotal_compromiso, 2, ',', '');
        if ($field[16] == 0) {
            $ptotal_compromiso = 0;
        } else {
            $pdtotal_compromiso = (float) (($field[15] - $field[16]) * 100 / $field[15]);
        }

        $pdtotal_compromiso = number_format($pdtotal_compromiso, 2, ',', '');

        // CAUSADO

        $total_causado = number_format($field[17], 2, ',', '');
        if ($field[17] == 0) {
            $ptotal_causado = 0;
        } else {
            $ptotal_causado = (float) ($field[17] * 100 / $field[15]);
        }

        $ptotal_causado = number_format($ptotal_causado, 2, ',', '');
        $dtotal_causado = (float) ($field[15] - $field[17]);
        $dtotal_causado = number_format($dtotal_causado, 2, ',', '');
        if ($field[17] == 0) {
            $pdtotal_causado = 0;
        } else {
            $pdtotal_causado = (float) (($field[15] - $field[17]) * 100 / $field[15]);
        }

        $pdtotal_causado = number_format($pdtotal_causado, 2, ',', '');

        // PAGADO

        $total_pagado = number_format($field[18], 2, ',', '');
        if ($field[18] == 0) {
            $ptotal_pagado = 0;
        } else {
            $ptotal_pagado = (float) ($field[18] * 100 / $field[15]);
        }

        $ptotal_pagado = number_format($ptotal_pagado, 2, ',', '');
        $dtotal_pagado = (float) ($field[15] - $field[18]);
        $dtotal_pagado = number_format($dtotal_pagado, 2, ',', '');
        if ($field[18] == 0) {
            $pdtotal_pagado = 0;
        } else {
            $pdtotal_pagado = (float) (($field[15] - $field[18]) * 100 / $field[15]);
        }

        $pdtotal_pagado = number_format($pdtotal_pagado, 2, ',', '');

        /////////////////////////////
        echo "
		<table>
			<tr>
				<td style='$tr5'>Categor&iacute;a Program&aacute;tica:</td>
				<td style='$tr2' align='left'>=TEXTO(" . $field['CodCategoria'] . "; \"0000000000\")</td>
				<td style='$tr2' align='left'>" . utf8_decode($field['denounidad']) . "</td>
			</tr>
			<tr>
				<td style='$tr5'>Partida:</td>
				<td style='$tr2' align='left' colspan='5'>" . utf8_decode($partida) . "</td>
			</tr>
			<tr>
				<td style='$tr5'>Monto Original:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $monto_original . "; 2)</td>
			</tr>
			<tr>
				<td style='$tr5'>Total Aumentos:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $total_aumento . "; 2)</td>
			</tr>
			<tr>
				<td style='$tr55'>Total Solicitud Aumento:</td>
				<td style='$tr22' align='right'>=DECIMAL(" . $total_solicitud_aumento . "; 2)</td>
			</tr>
			<tr>
				<td style='$tr5'>Total Disminuciones:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $total_disminucion . "; 2)</td>
			</tr>
			<tr>
				<td style='$tr555'>Total Reservado para Disminuir:</td>
				<td style='$tr222' align='right'>=DECIMAL(" . $total_reservado_disminucion . "; 2)</td>
			</tr>
			<tr>
				<td style='$tr5'>Total Actual:</td>
				<td style='$tr2' align='right'>=DECIMAL(" . $total_actual . "; 2)</td>
			</tr>
		</table>";

        echo "
		<table border='1'>
			<tr><td colspan='7'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
			<tr><td colspan='7'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
			<tr><td colspan='7'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
			<tr>
				<th width='200' style='$tr1'>DOCUMENTO</th>
				<th width='200' style='$tr1'>FECHA</th>
				<th width='750' style='$tr1'>DESCRIPCION</th>
				<th width='150' style='$tr1'>AUMENTO</th>
				<th width='150' style='$tr1'>SOLICITUD AUMENTO</th>
				<th width='150' style='$tr1'>DISMINUCION</th>
				<th width='150' style='$tr1'>RESERVADO DISMINUCION</th>
			</tr>";
        //----------------------------------------------------
        $filtro = " AND (maestro_presupuesto.idcategoria_programatica='" . $_GET['idcategoria'] . "') AND (maestro_presupuesto.idclasificador_presupuestario='" . $_GET['idpartida'] . "')";
        //---------------------------------------------
        if ($idordinal != "") {
            $where_ordinal = " AND maestro_presupuesto.idordinal = '" . $idordinal . "' ";
        }

        //---------------------------------------------
        $sql = "(SELECT maestro_presupuesto.idRegistro,
					  maestro_presupuesto.idcategoria_programatica AS IdCategoria,
					  maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
					  categoria_programatica.codigo AS CodCategoria,
					  unidad_ejecutora.denominacion AS Unidad,
					  clasificador_presupuestario.partida AS Par,
					  clasificador_presupuestario.generica AS Gen,
					  clasificador_presupuestario.especifica AS Esp,
					  clasificador_presupuestario.sub_especifica AS Sesp,
					  clasificador_presupuestario.denominacion AS NomPartida,
					  maestro_presupuesto.idRegistro AS IdPresupuesto,
					  SUM(maestro_presupuesto.monto_actual) AS MontoActual,
					  SUM(maestro_presupuesto.total_compromisos) AS MontoCompromiso,
					  SUM(maestro_presupuesto.reservado_disminuir) AS MontoReservadoDisminuir,
					  SUM(maestro_presupuesto.solicitud_aumento) AS MontoSolicitudAumento,
						SUM(maestro_presupuesto.pre_compromiso) AS MontoPreCompromiso,
					  SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
					  SUM(maestro_presupuesto.total_causados) AS TotalCausados,
					  SUM(maestro_presupuesto.total_pagados) AS TotalPagados,
					  SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
					  SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion, 'especifica' AS Tipo
				FROM
					  maestro_presupuesto,
					  categoria_programatica,
					  unidad_ejecutora,
					  clasificador_presupuestario,
					  ordinal
				WHERE
					  ((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					  (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					  (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) $where_ordinal AND
					  (maestro_presupuesto.anio='" . $anio_fiscal . "' AND
					  maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
					  maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp))

				UNION

				(SELECT maestro_presupuesto.idRegistro,
						maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
								WHERE (clasificador_presupuestario.partida=Par AND
									   clasificador_presupuestario.generica=Gen AND
									   clasificador_presupuestario.especifica='00' AND
									   clasificador_presupuestario.sub_especifica='00')) AS NomPartida,
						maestro_presupuesto.idRegistro AS IdPresupuesto,
						SUM(maestro_presupuesto.monto_actual) AS MontoActual,
						SUM(maestro_presupuesto.total_compromisos) AS MontoCompromiso,
						SUM(maestro_presupuesto.reservado_disminuir) AS MontoReservadoDisminuir,
						SUM(maestro_presupuesto.solicitud_aumento) AS MontoSolicitudAumento,
						SUM(maestro_presupuesto.pre_compromiso) AS MontoPreCompromiso,
						SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
						SUM(maestro_presupuesto.total_causados) AS TotalCausados,
						SUM(maestro_presupuesto.total_pagados) AS TotalPagados,
						SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
						SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion,
						'generica' AS Tipo
					FROM
						maestro_presupuesto,
						categoria_programatica,
						unidad_ejecutora,
						clasificador_presupuestario
					WHERE
						((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) $where_ordinal AND
						(clasificador_presupuestario.sub_especifica='00') AND
						(maestro_presupuesto.anio='" . $anio_fiscal . "' AND
						maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
						maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
					GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp))

					UNION

					(SELECT maestro_presupuesto.idRegistro,
							maestro_presupuesto.idcategoria_programatica AS IdCategoria,
							maestro_presupuesto.idclasificador_presupuestario AS idPartida,
							categoria_programatica.codigo AS CodCategoria,
							unidad_ejecutora.denominacion AS Unidad,
							clasificador_presupuestario.partida AS Par,
							'00' AS Gen,
							'00' AS Esp,
							'00' AS Sesp,
							(SELECT clasificador_presupuestario.denominacion
								FROM clasificador_presupuestario
									WHERE (clasificador_presupuestario.partida=Par AND
										   clasificador_presupuestario.generica='00' AND
										   clasificador_presupuestario.especifica='00' AND
										   clasificador_presupuestario.sub_especifica='00')) AS NomPartida,
							maestro_presupuesto.idRegistro AS IdPresupuesto,
							SUM(maestro_presupuesto.monto_actual) AS MontoActual,
							SUM(maestro_presupuesto.total_compromisos) AS MontoCompromiso,
							SUM(maestro_presupuesto.reservado_disminuir) AS MontoReservadoDisminuir,
							SUM(maestro_presupuesto.solicitud_aumento) AS MontoSolicitudAumento,
							SUM(maestro_presupuesto.pre_compromiso) AS MontoPreCompromiso,
							SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
							SUM(maestro_presupuesto.total_causados) AS TotalCausados,
							SUM(maestro_presupuesto.total_pagados) AS TotalPagados,
							SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
							SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion,
							'partida' AS Tipo
					FROM
						maestro_presupuesto,
						categoria_programatica,
						unidad_ejecutora,
						clasificador_presupuestario
					WHERE
						((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) $where_ordinal AND
						(clasificador_presupuestario.sub_especifica='00') AND
						(maestro_presupuesto.anio='" . $anio_fiscal . "' AND
						maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
						maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
					GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp))
					ORDER BY CodCategoria, Par, Gen, Esp, Sesp";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            $idregistro           = $field["idRegistro"];
            $clasificador         = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $monto_original       = number_format($field["MontoOriginal"], 2, ',', '');
            $monto_actual         = number_format($field["MontoActual"], 2, ',', '');
            $monto_compromiso     = number_format($field["MontoCompromiso"], 2, ',', '');
            $monto_pre_compromiso = number_format($field["MontoPreCompromiso"], 2, ',', '');
            $monto_reservado      = number_format($field["MontoReservadoDisminuir"], 2, ',', '');
            $monto_solicitud      = number_format($field["MontoSolicitudAumento"], 2, ',', '');
            $monto_causado        = number_format($field["TotalCausados"], 2, ',', '');
            $monto_pagado         = number_format($field["TotalPagados"], 2, ',', '');
            $disponible           = (float) ($field["MontoActual"] - $field["MontoCompromiso"] - $field["MontoReservadoDisminuir"] - $field["MontoPreCompromiso"]);
            $monto_disponible     = number_format($disponible, 2, ',', '');
            if ($field["MontoCompromiso"] == 0 || $field["MontoActual"] == 0) {
                $pcompromiso = "0";
            } else {
                $pcompromiso = (float) (($field[MontoCompromiso] * 100) / $field["MontoActual"]);
            }

            $pcompromiso = number_format($pcompromiso, 2, ',', '');
            if ($field["MontoCausado"] == 0 || $field["MontoActual"] == 0) {
                $pcausado = "0";
            } else {
                $pcausado = (float) (($field["MontoCausado"] * 100) / $field["MontoActual"]);
            }

            $pcausado = number_format($pcausado, 2, ',', '');
            if ($field["MontoPagado"] == 0 || $field["MontoActual"] == 0) {
                $ppagado = "0";
            } else {
                $ppagado = (float) (($field["MontoPagado"] * 100) / $field["MontoActual"]);
            }

            $ppagado = number_format($ppagado, 2, ',', '');
            if ($disponible == 0 || $field["MontoActual"] == 0) {
                $pdisponible = "0";
            } else {
                $pdisponible = (float) (($disponible * 100) / $field["MontoActual"]);
            }

            $pdisponible = number_format($pdisponible, 2, ',', '');

            if ($field["Tipo"] == "especifica") {
                //    ----------------------------
                $sum_original += $field["MontoOriginal"];
                $sum_aumento += $field["TotalAumento"];
                $sum_disminucion += $field["TotalDisminucion"];
                $sum_actual += $field["MontoActual"];
                $sum_compromiso += $field["MontoCompromiso"];
                $sum_reservado += $field["MontoReservadoDisminuir"];
                $sum_solicitud += $field["MontoSolicitudAumento"];
                $sum_causado += $field["TotalCausados"];
                $sum_pagado += $field["TotalPagados"];
                $sum_disponible += $disponible;
                //    ----------------------------
                $total_aumento     = number_format($field["TotalAumento"], 2, ',', '');
                $total_disminucion = number_format($field["TotalDisminucion"], 2, ',', '');
                //    CONSULTO PARA OPBTENER LOS AUMENTOS Y DISMINUCIONES DE LAS PARTIDAS E IMPRIMIRLAS
                $sql = "SELECT traslados_presupuestarios.status as codigo_referencia,
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
							 (traslados_presupuestarios.anio='" . $anio_fiscal . "')

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
							(creditos_adicionales.anio='" . $anio_fiscal . "')

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
							(rectificacion_presupuesto.anio='" . $anio_fiscal . "')

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
							(traslados_presupuestarios.anio='" . $anio_fiscal . "')

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
							(disminucion_presupuesto.anio='" . $anio_fiscal . "')

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
							(rectificacion_presupuesto.anio='" . $anio_fiscal . "')

						ORDER BY fecha_solicitud";
                $query_detalle = mysql_query($sql) or die($sql . mysql_error());
                $rows_detalle  = mysql_num_rows($query_detalle);
                for ($k = 0; $k < $rows_detalle; $k++) {
                    $detalle         = mysql_fetch_array($query_detalle);
                    $monto           = number_format($detalle['monto_acreditar'], 2, ',', '');
                    list($a, $m, $d) = SPLIT('[/.-]', $detalle['fecha_solicitud']);
                    $fecha_solicitud = $d . "/" . $m . "/" . $a;
                    if ($detalle['tipo'] == "Traslado(+)" || $detalle['tipo'] == "Credito Adicional" || $detalle['tipo'] == "Rectificacion(+)") {
                        $disminucion = "";
                        $compromisos = "";
                        $causado     = "";
                        if ($detalle['estado'] == "Anulado") {
                            $anulado = "(ANULADO)";
                            $aumento = "($aumento)";
                            $tr5     = "font-size:12px; color:#FF0000;";} else {
                            $anulado = "";
                        }

                        if ($detalle['estado'] == "elaboracion") {
                            $solicitud_aumento = $monto;
                        } else {
                            $aumento = $monto;
                        }

                        $detalle['tipo'] = $detalle['tipo'] . " " . $anulado;
                    } else if ($detalle['tipo'] == "Traslado(-)" || $detalle['tipo'] == "Disminucion Presupuestaria" || $detalle['tipo'] == "Rectificacion(-)") {
                        $aumento     = "";
                        $compromisos = "";
                        $causado     = "";
                        if ($detalle['estado'] == "Anulado") {
                            $anulado     = "(ANULADO)";
                            $disminucion = "($disminucion)";
                            $tr5         = "font-size:12px; color:#FF0000;";} else {
                            $anulado = "";
                        }

                        if ($detalle['estado'] == "elaboracion") {
                            $reservado_disminuir = $monto;
                        } else {
                            $disminucion = $monto;
                        }

                        $detalle['tipo'] = $detalle['tipo'] . " " . $anulado;
                    } else if ($detalle['tipo'] == "Orden de Compra/Servicio") {
                        $disminucion = "";
                        $aumento     = "";
                        $compromisos = $monto;
                        $causado     = "";
                        if ($detalle['estado'] == "anulado") {
                            $anulado     = "(ANULADO)";
                            $compromisos = "($compromisos)";
                            $tr5         = "font-size:12px; color:#FF0000;";} else {
                            $anulado = "";
                        }

                        $detalle['tipo'] = $detalle['justificacion'] . " " . $anulado;
                    } else if ($detalle['tipo'] == "Orden de Pago") {
                        $disminucion = "";
                        $aumento     = "";
                        if ($detalle['Causa'] == "si") {
                            $causado = $monto;if ($detalle['estado'] == "anulado") {
                                $anulado = "(ANULADO)";
                                $causado = "($causado)";
                                $tr5     = "font-size:12px; color:#FF0000;";} else {
                                $anulado = "";
                            }
                        }
                        if ($detalle['Compromete'] == "si") {
                            $compromisos = $monto;if ($detalle['estado'] == "anulado") {
                                $anulado     = "(ANULADO)";
                                $compromisos = "($compromisos)";
                                $tr5         = "font-size:12px; color:#FF0000;";} else {
                                $anulado = "";
                            }
                        }
                        $detalle['tipo'] = $detalle['justificacion'] . " " . $anulado;
                        if ($detalle['estado'] == "pagada") {
                            $imprimir_ocs = "SI";
                        }

                    } else if ($detalle['tipo'] == "Cheque") {
                        $disminucion = "";
                        $aumento     = "";
                        $compromisos = "";
                        $causado     = "";
                        $pagado      = $monto;
                        if ($detalle['estado'] == "anulado") {
                            $anulado = "(ANULADO)";
                            $pagado  = "($pagado)";
                            $tr5     = "font-size:12px; color:#FF0000;";} else {
                            $anulado = "";
                        }

                        $detalle['tipo'] = $detalle['justificacion'] . " " . $anulado;
                        $imprimir_op     = "SI";
                    } else if ($detalle['tipo'] == "Requisicion") {
                        $detalle['tipo'] = $detalle['justificacion'];
                        $precompromiso   = number_format($detalle['monto_acreditar'], 2, ',', '');
                        $sum_precompromiso += $detalle['monto_acreditar'];
                    }
                    echo "
					<tr>
						<td style='$tr5'>" . $detalle['nro_solicitud'] . "</td>
						<td style='$tr5'>" . $fecha_solicitud . "</td>
						<td style='$tr5'>" . utf8_decode($detalle['tipo']) . "</td>
						<td style='$tr5' align='right'>=DECIMAL(" . $aumento . "; 2)</td>
						<td style='$tr5' align='right'>=DECIMAL(" . $solicitud_aumento . "; 2)</td>
						<td style='$tr5' align='right'>=DECIMAL(" . $disminucion . "; 2)</td>
						<td style='$tr5' align='right'>=DECIMAL(" . $reservado_disminuir . "; 2)</td>
					</tr>";

                    if ($imprimir_ocs == "SI") {
                        $sql           = "SELECT rpc.idorden_compra_servicio, ocs.numero_orden FROM relacion_pago_compromisos rpc INNER JOIN orden_compra_servicio ocs ON (rpc.idorden_compra_servicio=ocs.idorden_compra_servicio) WHERE rpc.idorden_pago='" . $detalle['id'] . "'";
                        $query_pagados = mysql_query($sql) or die($sql . mysql_error());
                        while ($field_pagados = mysql_fetch_array($query_pagados)) {
                            $ordenes .= $field_pagados['numero_orden'] . ", ";
                        }
                        echo "
						<tr>
							<td style='$tr5'></td>
							<td style='$tr5'></td>
							<td style='$tr5'>" . $ordenes . "</td>
							<td style='$tr5'></td>
							<td style='$tr5'></td>
							<td style='$tr5'></td>
							<td style='$tr5'></td>
						</tr>";
                    }

                    if ($imprimir_op == "SI") {
                        $sql           = "SELECT pf.idorden_pago, op.numero_orden FROM pagos_financieros pf INNER JOIN orden_pago op ON (pf.idorden_pago=op.idorden_pago) WHERE pf.idpagos_financieros='" . $detalle['id'] . "'";
                        $query_pagados = mysql_query($sql) or die($sql . mysql_error());
                        while ($field_pagados = mysql_fetch_array($query_pagados)) {
                            $ordenes .= $field_pagados['numero_orden'] . ", ";
                        }
                        echo "
						<tr>
							<td style='$tr5'></td>
							<td style='$tr5'></td>
							<td style='$tr5'>" . $ordenes . "</td>
							<td style='$tr5'></td>
							<td style='$tr5'></td>
							<td style='$tr5'></td>
							<td style='$tr5'></td>
						</tr>";
                    }
                    $disminucion       = "";
                    $aumento           = "";
                    $compromisos       = "";
                    $causado           = "";
                    $pagado            = "";
                    $anulado           = "";
                    $monto_actual      = "";
                    $total_disminucion = "";
                    $precompromiso     = "";
                    $imprimir_ocs      = "";
                    $imprimir_op       = "";
                    $ordenes           = "";
                }
            }
        }
        //---------------------------------------------
        if ($sum_actual == 0) {
            $pcompromiso = 0;
        } else {
            $pcompromiso = (float) (($sum_compromiso * 100) / $sum_actual);
        }

        $pcompromiso = number_format($pcompromiso, 2, ',', '');
        if ($sum_actual == 0) {
            $pcausado = 0;
        } else {
            $pcausado = (float) (($sum_causado * 100) / $sum_actual);
        }

        $pcausado = number_format($pcausado, 2, ',', '');
        if ($sum_actual == 0) {
            $ppagado = 0;
        } else {
            $ppagado = (float) (($sum_pagado * 100) / $sum_actual);
        }

        $ppagado = number_format($ppagado, 2, ',', '');
        if ($sum_actual == 0) {
            $pdisponible = 0;
        } else {
            $pdisponible = (float) (($disponible * 100) / $sum_actual);
        }

        $pdisponible             = number_format($pdisponible, 2, ',', '');
        $sum_original            = number_format($sum_original, 2, ',', '');
        $sum_aumento             = number_format($sum_aumento, 2, ',', '');
        $sum_disminucion         = number_format($sum_disminucion, 2, ',', '');
        $sum_solicitud_aumento   = number_format($sum_solicitud, 2, ',', '');
        $sum_reservado_disminuir = number_format($sum_reservado, 2, ',', '');
        $sum_actual              = number_format($sum_actual, 2, ',', '');
        $sum_compromiso          = number_format($sum_compromiso, 2, ',', '');
        $sum_causado             = number_format($sum_causado, 2, ',', '');
        $sum_pagado              = number_format($sum_pagado, 2, ',', '');
        $sum_disponible          = number_format($sum_disponible, 2, ',', '');
        $sum_precompromiso       = number_format($sum_precompromiso, 2, ',', '');

        echo "
		<tr>
			<td style='$tr5'></td>
			<td style='$tr5'></td>
			<td style='$tr5'></td>
			<td style='$tr5' align='right'>=DECIMAL(" . $sum_aumento . "; 2)</td>
			<td style='$tr5' align='right'>=DECIMAL(" . $sum_solicitud_aumento . "; 2)</td>
			<td style='$tr5' align='right'>=DECIMAL(" . $sum_disminucion . "; 2)</td>
			<td style='$tr5' align='right'>=DECIMAL(" . $sum_reservado_disminuir . "; 2)</td>
		</tr>
		</table>";
        break;

    //    Movimientos por Categoria...
    case "movimientos_por_categoria":
        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr3 = "background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:12px; color:#000000;";
        //---------------------------------------------
        $sql   = "SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='" . $financiamiento . "') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];

        //---------------------------------------------
        echo "
		<table border='1'>
			<tr><td colspan='8'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
			<tr><td colspan='8'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
			<tr><td colspan='8'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
			<tr>
				<th width='100' style='$tr1'>PARTIDA</th>
				<th width='750' style='$tr1'>DESCRIPCION</th>
				<th width='200' style='$tr1'>ASIG. INICIAL</th>
				<th width='200' style='$tr1'>AUMENTO</th>
				<th width='200' style='$tr1'>SOLICITUD AUMENTO</th>
				<th width='200' style='$tr1'>DISMINUCION</th>
				<th width='200' style='$tr1'>RESERVADO DISMINUCION</th>
				<th width='200' style='$tr1'>ASIG. AJUSTADA</th>
			</tr>";
        //---------------------------------------------
        if ($idcategoria_programatica == '0') {
            $filtro = "";
        } else {
            $filtro = " AND (maestro_presupuesto.idcategoria_programatica='" . $idcategoria_programatica . "')";
        }

        //---------------------------------------------
        $sql = "(SELECT maestro_presupuesto.idRegistro,
					  maestro_presupuesto.idcategoria_programatica AS IdCategoria,
					  maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
					  categoria_programatica.codigo AS CodCategoria,
					  unidad_ejecutora.denominacion AS Unidad,
					  clasificador_presupuestario.partida AS Par,
					  clasificador_presupuestario.generica AS Gen,
					  clasificador_presupuestario.especifica AS Esp,
					  clasificador_presupuestario.sub_especifica AS Sesp,
					  clasificador_presupuestario.denominacion AS NomPartida,
					  maestro_presupuesto.idRegistro AS IdPresupuesto,
					  SUM(maestro_presupuesto.monto_actual) AS MontoActual,
					  SUM(maestro_presupuesto.total_compromisos) AS MontoCompromiso,
					  SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso,
					  SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir,
					  SUM(maestro_presupuesto.solicitud_aumento) AS SolicitudAumento,
					  SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
					  SUM(maestro_presupuesto.total_causados) AS TotalCausados,
					  SUM(maestro_presupuesto.total_pagados) AS TotalPagados,
					  SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
					  SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion, 'especifica' AS Tipo,
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
					  (maestro_presupuesto.anio='" . $anio_fiscal . "' AND
					  maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
					  maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				UNION

				(SELECT maestro_presupuesto.idRegistro,
						maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
								WHERE (clasificador_presupuestario.partida=Par AND
									   clasificador_presupuestario.generica=Gen AND
									   clasificador_presupuestario.especifica='00' AND
									   clasificador_presupuestario.sub_especifica='00')) AS NomPartida,
						maestro_presupuesto.idRegistro AS IdPresupuesto,
						SUM(maestro_presupuesto.monto_actual) AS MontoActual,
						SUM(maestro_presupuesto.total_compromisos) AS MontoCompromiso,
						SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso,
						SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir,
						SUM(maestro_presupuesto.solicitud_aumento) AS SolicitudAumento,
						SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
						SUM(maestro_presupuesto.total_causados) AS TotalCausados,
						SUM(maestro_presupuesto.total_pagados) AS TotalPagados,
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
						(maestro_presupuesto.anio='" . $anio_fiscal . "' AND
						maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
						maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
					GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				UNION

				(SELECT maestro_presupuesto.idRegistro,
						maestro_presupuesto.idcategoria_programatica AS IdCategoria,
						maestro_presupuesto.idclasificador_presupuestario AS idPartida,
						categoria_programatica.codigo AS CodCategoria,
						unidad_ejecutora.denominacion AS Unidad,
						clasificador_presupuestario.partida AS Par,
						'00' AS Gen,
						'00' AS Esp,
						'00' AS Sesp,
						(SELECT clasificador_presupuestario.denominacion
							FROM clasificador_presupuestario
								WHERE (clasificador_presupuestario.partida=Par AND
									   clasificador_presupuestario.generica='00' AND
									   clasificador_presupuestario.especifica='00' AND
									   clasificador_presupuestario.sub_especifica='00')) AS NomPartida,
						maestro_presupuesto.idRegistro AS IdPresupuesto,
						SUM(maestro_presupuesto.monto_actual) AS MontoActual,
						SUM(maestro_presupuesto.total_compromisos) AS MontoCompromiso,
						SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso,
						SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir,
						SUM(maestro_presupuesto.solicitud_aumento) AS SolicitudAumento,
						SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
						SUM(maestro_presupuesto.total_causados) AS TotalCausados,
						SUM(maestro_presupuesto.total_pagados) AS TotalPagados,
						SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
						SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion,
						'partida' AS Tipo,
						'0000' AS codordinal,
						'' As nomordinal
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
					(maestro_presupuesto.anio='" . $anio_fiscal . "' AND
					maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
					maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            $idregistro          = $field["idRegistro"];
            $clasificador        = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $precompromiso       = number_format($field["PreCompromiso"], 2, ',', '');
            $total_aumento       = number_format($field["TotalAumento"], 2, ',', '');
            $solicitud_aumento   = number_format($field["SolicitudAumento"], 2, ',', '');
            $total_disminucion   = number_format($field["TotalDisminucion"], 2, ',', '');
            $reservado_disminuir = number_format($field["ReservadoDisminuir"], 2, ',', '');
            $monto_original      = number_format($field["MontoOriginal"], 2, ',', '');
            $monto_actual        = number_format($field["MontoActual"], 2, ',', '');
            $monto_compromiso    = number_format($field["MontoCompromiso"], 2, ',', '');
            $monto_causado       = number_format($field["TotalCausados"], 2, ',', '');
            $monto_pagado        = number_format($field["TotalPagados"], 2, ',', '');
            $disponible          = (float) ($field["MontoActual"] - $field["PreCompromiso"] - $field["MontoCompromiso"] - $field["ReservadoDisminuir"]);
            $monto_disponible    = number_format($disponible, 2, ',', '');
            if ($field["MontoCompromiso"] == 0 || $field["MontoActual"] == 0) {
                $pcompromiso = "0";
            } else {
                $pcompromiso = (float) (($field["MontoCompromiso"] * 100) / $field["MontoActual"]);
            }

            $pcompromiso = number_format($pcompromiso, 2, ',', '');
            if ($field["TotalCausados"] == 0 || $field["MontoActual"] == 0) {
                $pcausado = "0";
            } else {
                $pcausado = (float) (($field["TotalCausados"] * 100) / $field["MontoActual"]);
            }

            $pcausado = number_format($pcausado, 2, ',', '');
            if ($field["TotalPagados"] == 0 || $field["MontoActual"] == 0) {
                $ppagado = "0";
            } else {
                $ppagado = (float) (($field["TotalPagados"] * 100) / $field["MontoActual"]);
            }

            $ppagado = number_format($ppagado, 2, ',', '');
            if ($disponible == 0 || $field["MontoActual"] == 0) {
                $pdisponible = "0";
            } else {
                $pdisponible = (float) (($disponible * 100) / $field["MontoActual"]);
            }

            $pdisponible = number_format($pdisponible, 2, ',', '');

            if ($field['TotalAumento'] == 0) {
                $total_aumento = "";
            }

            if ($field['SolicitudAumento'] == 0) {
                $solicitud_aumento = "";
            }

            if ($field['TotalDisminucion'] == 0) {
                $total_disminucion = "";
            }

            if ($field['ReservadoDisminuir'] == 0) {
                $reservado_disminuir = "";
            }

            if ($field["Tipo"] == "partida") {
                $par_clasificador        = $clasificador;
                $par_denominacion        = utf8_decode($field['NomPartida']);
                $par_monto_original      = $monto_original;
                $par_total_aumento       = $total_aumento;
                $par_solicitud_aumento   = $solicitud_aumento;
                $par_total_disminucion   = $total_disminucion;
                $par_reservado_disminuir = $reservado_disminuir;
                $par_monto_actual        = $monto_actual;
            } else if ($field["Tipo"] == "generica") {
                $gen_clasificador        = $clasificador;
                $gen_denominacion        = utf8_decode($field['NomPartida']);
                $gen_monto_original      = $monto_original;
                $gen_total_aumento       = $total_aumento;
                $gen_solicitud_aumento   = $solicitud_aumento;
                $gen_total_disminucion   = $total_disminucion;
                $gen_reservado_disminuir = $reservado_disminuir;
                $gen_monto_actual        = $monto_actual;
            } else if ($field["Tipo"] == "especifica") {
                //    CONSULTO PARA OPBTENER LOS AUMENTOS Y DISMINUCIONES DE LAS PARTIDAS E IMPRIMIRLAS
                $sql = "SELECT traslados_presupuestarios.status as codigo_referencia,
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
							 (traslados_presupuestarios.anio='" . $anio_fiscal . "')

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
							(creditos_adicionales.anio='" . $anio_fiscal . "')

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
							(rectificacion_presupuesto.anio='" . $anio_fiscal . "')

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
							(traslados_presupuestarios.anio='" . $anio_fiscal . "')

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
							(disminucion_presupuesto.anio='" . $anio_fiscal . "')

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
							(rectificacion_presupuesto.anio='" . $anio_fiscal . "')

						ORDER BY fecha_solicitud";
                $query_detalle = mysql_query($sql) or die($sql . mysql_error());
                $rows_detalle  = mysql_num_rows($query_detalle);

                if ($rows_detalle != 0) {
                    //    SI CAMBIA DE CATEGORIA LA IMPRIMO
                    if ($field["IdCategoria"] != $IdCategoria) {
                        $IdCategoria = $field["IdCategoria"];
                        echo "<tr><td colspan='8' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>";
                    }

                    //     imprimo la partida
                    if ($par_clasificador != "") {
                        echo "
						<tr>
							<td style='$tr3'>" . $par_clasificador . "</td>
							<td style='$tr3'>" . $par_denominacion . "</td>
							<td style='$tr3' align='right'>=DECIMAL(" . $par_monto_original . "; 2)</td>
							<td style='$tr3' align='right'>=DECIMAL(" . $par_total_aumento . "; 2)</td>
							<td style='$tr3' align='right'>=DECIMAL(" . $par_solicitud_aumento . "; 2)</td>
							<td style='$tr3' align='right'>=DECIMAL(" . $par_total_disminucion . "; 2)</td>
							<td style='$tr3' align='right'>=DECIMAL(" . $par_reservado_disminuir . "; 2)</td>
							<td style='$tr3' align='right'>=DECIMAL(" . $par_monto_actual . "; 2)</td>
						</tr>";
                    }

                    //    imprimo la generica
                    if ($gen_clasificador != "") {
                        echo "
						<tr>
							<td style='$tr4'>" . $gen_clasificador . "</td>
							<td style='$tr4'>" . $gen_denominacion . "</td>
							<td style='$tr4' align='right'>=DECIMAL(" . $gen_monto_original . "; 2)</td>
							<td style='$tr4' align='right'>=DECIMAL(" . $gen_total_aumento . "; 2)</td>
							<td style='$tr4' align='right'>=DECIMAL(" . $gen_solicitud_aumento . "; 2)</td>
							<td style='$tr4' align='right'>=DECIMAL(" . $gen_total_disminucion . "; 2)</td>
							<td style='$tr4' align='right'>=DECIMAL(" . $gen_reservado_disminuir . "; 2)</td>
							<td style='$tr4' align='right'>=DECIMAL(" . $gen_monto_actual . "; 2)</td>
						</tr>";
                    }

                    //    imprimo la especifica
                    $sum_original += $field["MontoOriginal"];
                    $sum_aumento += $field["TotalAumento"];
                    $sum_disminucion += $field["TotalDisminucion"];
                    $sum_reservado_disminuir += $field["ReservadoDisminuir"];
                    $sum_solicitud_aumento += $field["SolicitudAumento"];
                    $sum_actual += $field["MontoActual"];
                    $sum_compromiso += $field["MontoCompromiso"];
                    $sum_causado += $field["TotalCausados"];
                    $sum_pagado += $field["TotalPagados"];
                    $sum_disponible += $disponible;
                    //    ----------------------------
                    if ($field['codordinal'] != "0000") {
                        $descripcion_partida = $field['codordinal'] . " " . $field['nomordinal'];
                    } else {
                        $descripcion_partida = $field['NomPartida'];
                    }

                    echo "
					<tr>
						<td style='$tr5'>" . $clasificador . "</td>
						<td style='$tr5'>" . utf8_decode($descripcion_partida) . "</td>
						<td style='$tr5' align='right'>=DECIMAL(" . $monto_original . "; 2)</td>
						<td style='$tr5' align='right'>=DECIMAL(" . $total_aumento . "; 2)</td>
						<td style='$tr5' align='right'>=DECIMAL(" . $solicitud_aumento . "; 2)</td>
						<td style='$tr5' align='right'>=DECIMAL(" . $total_disminucion . "; 2)</td>
						<td style='$tr5' align='right'>=DECIMAL(" . $reservado_disminuir . "; 2)</td>
						<td style='$tr5' align='right'>=DECIMAL(" . $monto_actual . "; 2)</td>
					</tr>";

                    $total_disminucion   = "";
                    $monto_actual        = "";
                    $reservado_disminuir = "";
                    $solicitud_aumento   = "";

                    for ($k = 0; $k < $rows_detalle; $k++) {
                        $detalle         = mysql_fetch_array($query_detalle);
                        $monto           = number_format($detalle['monto_acreditar'], 2, ',', '');
                        list($a, $m, $d) = SPLIT('[/.-]', $detalle['fecha_solicitud']);
                        $fecha_solicitud = $d . "/" . $m . "/" . $a;
                        if ($detalle['tipo'] == "Traslado(+)" || $detalle['tipo'] == "Credito Adicional" || $detalle['tipo'] == "Rectificacion(+)") {
                            $disminucion = "";
                            $compromisos = "";
                            $causado     = "";
                            if ($detalle['estado'] == "Anulado") {
                                $anulado = "(ANULADO)";
                                $aumento = "($aumento)";
                                $tr5     = "font-size:12px; color:#FF0000;";} else {
                                $anulado = "";
                            }

                            if ($detalle['estado'] == "elaboracion") {
                                $solicitud_aumento = $monto;
                            } else {
                                $aumento = $monto;
                            }

                            $detalle['tipo'] = $detalle['tipo'] . " " . $anulado;
                        } else if ($detalle['tipo'] == "Traslado(-)" || $detalle['tipo'] == "Disminucion Presupuestaria" || $detalle['tipo'] == "Rectificacion(-)") {
                            $aumento     = "";
                            $compromisos = "";
                            $causado     = "";
                            if ($detalle['estado'] == "Anulado") {
                                $anulado     = "(ANULADO)";
                                $disminucion = "($disminucion)";
                                $tr5         = "font-size:12px; color:#FF0000;";} else {
                                $anulado = "";
                            }

                            if ($detalle['estado'] == "elaboracion") {
                                $reservado_disminuir = $monto;
                            } else {
                                $disminucion = $monto;
                            }

                            $detalle['tipo'] = $detalle['tipo'] . " " . $anulado;
                        } else if ($detalle['tipo'] == "Orden de Compra/Servicio") {
                            $disminucion = "";
                            $aumento     = "";
                            $compromisos = $monto;
                            $causado     = "";
                            if ($detalle['estado'] == "anulado") {
                                $anulado     = "(ANULADO)";
                                $compromisos = "($compromisos)";
                                $tr5         = "font-size:12px; color:#FF0000;";} else {
                                $anulado = "";
                            }

                            $detalle['tipo'] = $detalle['justificacion'] . " " . $anulado;
                        } else if ($detalle['tipo'] == "Orden de Pago") {
                            $disminucion = "";
                            $aumento     = "";
                            if ($detalle['Causa'] == "si") {
                                $causado = $monto;if ($detalle['estado'] == "anulado") {
                                    $anulado = "(ANULADO)";
                                    $causado = "($causado)";
                                    $tr5     = "font-size:12px; color:#FF0000;";} else {
                                    $anulado = "";
                                }
                            }
                            if ($detalle['Compromete'] == "si") {
                                $compromisos = $monto;if ($detalle['estado'] == "anulado") {
                                    $anulado     = "(ANULADO)";
                                    $compromisos = "($compromisos)";
                                    $tr5         = "font-size:12px; color:#FF0000;";} else {
                                    $anulado = "";
                                }
                            }
                            $detalle['tipo'] = $detalle['justificacion'] . " " . $anulado;
                            if ($detalle['estado'] == "pagada") {
                                $imprimir_ocs = "SI";
                            }

                        } else if ($detalle['tipo'] == "Cheque") {
                            $disminucion = "";
                            $aumento     = "";
                            $compromisos = "";
                            $causado     = "";
                            $pagado      = $monto;
                            if ($detalle['estado'] == "anulado") {
                                $anulado = "(ANULADO)";
                                $pagado  = "($pagado)";
                                $tr5     = "font-size:12px; color:#FF0000;";} else {
                                $anulado = "";
                            }

                            $detalle['tipo'] = $detalle['justificacion'] . " " . $anulado;
                            $imprimir_op     = "SI";
                        } else if ($detalle['tipo'] == "Requisicion") {
                            $detalle['tipo'] = $detalle['justificacion'];
                            $precompromiso   = number_format($detalle['monto_acreditar'], 2, ',', '');
                            $sum_precompromiso += $detalle['monto_acreditar'];
                        }

                        $ordenes = "";
                        if ($imprimir_ocs == "SI") {
                            $sql = "SELECT rpc.idorden_compra_servicio, ocs.numero_orden FROM relacion_pago_compromisos rpc INNER JOIN orden_compra_servicio ocs ON (rpc.idorden_compra_servicio=ocs.idorden_compra_servicio) WHERE rpc.idorden_pago='" . $detalle['id'] . "'";
                        }

                        if ($imprimir_op == "SI") {
                            $sql = "SELECT pf.idorden_pago, op.numero_orden FROM pagos_financieros pf INNER JOIN orden_pago op ON (pf.idorden_pago=op.idorden_pago) WHERE pf.idpagos_financieros='" . $detalle['id'] . "'";
                        }
                        $query_pagados = mysql_query($sql) or die($sql . mysql_error());
                        while ($field_pagados = mysql_fetch_array($query_pagados)) {
                            $ordenes .= $field_pagados['numero_orden'] . ", ";
                        }

                        echo "
						<tr>
							<td style='$tr5'>" . $detalle['nro_solicitud'] . "</td>
							<td style='$tr5'>" . utf8_decode($fecha_solicitud . ' - ' . $detalle['tipo']) . "</td>
							<td style='$tr5'></td>
							<td style='$tr5' align='right'>=DECIMAL(" . $aumento . "; 2)</td>
							<td style='$tr5' align='right'>=DECIMAL(" . $solicitud_aumento . "; 2)</td>
							<td style='$tr5' align='right'>=DECIMAL(" . $disminucion . "; 2)</td>
							<td style='$tr5' align='right'>=DECIMAL(" . $reservado_disminuir . "; 2)</td>
							<td style='$tr5' align='right'></td>
						</tr>";

                        echo "
						<tr>
							<td style='$tr5' align='right'></td>
							<td style='$tr5' align='right'>" . $ordenes . "</td>
							<td style='$tr5' align='right'></td>
							<td style='$tr5' align='right'></td>
							<td style='$tr5' align='right'></td>
							<td style='$tr5' align='right'></td>
							<td style='$tr5' align='right'></td>
							<td style='$tr5' align='right'></td>
						</tr>";

                        $imprimir_ocs        = "";
                        $imprimir_op         = "";
                        $precompromiso       = "";
                        $aumento             = "";
                        $disminucion         = "";
                        $solicitud_aumento   = "";
                        $reservado_disminuir = "";
                        $compromisos         = "";
                        $causado             = "";
                        $pagado              = "";
                    }

                    $par_clasificador        = "";
                    $par_denominacion        = "";
                    $par_monto_original      = "";
                    $par_total_aumento       = "";
                    $par_solicitud_aumento   = "";
                    $par_total_disminucion   = "";
                    $par_reservado_disminuir = "";
                    $par_monto_actual        = "";
                    $gen_clasificador        = "";
                    $gen_denominacion        = "";
                    $gen_monto_original      = "";
                    $gen_total_aumento       = "";
                    $gen_solicitud_aumento   = "";
                    $gen_total_disminucion   = "";
                    $gen_reservado_disminuir = "";
                    $gen_monto_actual        = "";
                }
            }
        }
        //---------------------------------------------
        if ($sum_compromiso == 0 || $sum_actual == 0) {
            $pcompromiso = 0;
        } else {
            $pcompromiso = (float) (($sum_compromiso * 100) / $sum_actual);
        }

        $pcompromiso = number_format($pcompromiso, 2, ',', '');
        if ($sum_causado == 0 || $sum_actual == 0) {
            $pcausado = 0;
        } else {
            $pcausado = (float) (($sum_causado * 100) / $sum_actual);
        }

        $pcausado = number_format($pcausado, 2, ',', '');
        if ($sum_pagado == 0 || $sum_actual == 0) {
            $ppagado = 0;
        } else {
            $ppagado = (float) (($sum_pagado * 100) / $sum_actual);
        }

        $ppagado = number_format($ppagado, 2, ',', '');
        if ($disponible == 0 || $sum_actual == 0) {
            $pdisponible = 0;
        } else {
            $pdisponible = (float) (($disponible * 100) / $sum_actual);
        }

        $pdisponible             = number_format($pdisponible, 2, ',', '');
        $sum_original            = number_format($sum_original, 2, ',', '');
        $sum_aumento             = number_format($sum_aumento, 2, ',', '');
        $sum_disminucion         = number_format($sum_disminucion, 2, ',', '');
        $sum_solicitud_aumento   = number_format($sum_solicitud_aumento, 2, ',', '');
        $sum_reservado_disminuir = number_format($sum_reservado_disminuir, 2, ',', '');
        $sum_actual              = number_format($sum_actual, 2, ',', '');
        $sum_compromiso          = number_format($sum_compromiso, 2, ',', '');
        $sum_causado             = number_format($sum_causado, 2, ',', '');
        $sum_pagado              = number_format($sum_pagado, 2, ',', '');
        $sum_disponible          = number_format($sum_disponible, 2, ',', '');
        $sum_precompromiso       = number_format($sum_precompromiso, 2, ',', '');

        echo "
		<tr>
			<td style='$tr5'></td>
			<td style='$tr5'></td>
			<td style='$tr5' align='right'>=DECIMAL(" . $sum_original . "; 2)</td>
			<td style='$tr5' align='right'>=DECIMAL(" . $sum_aumento . "; 2)</td>
			<td style='$tr5' align='right'>=DECIMAL(" . $sum_solicitud_aumento . "; 2)</td>
			<td style='$tr5' align='right'>=DECIMAL(" . $sum_disminucion . "; 2)</td>
			<td style='$tr5' align='right'>=DECIMAL(" . $sum_reservado_disminuir . "; 2)</td>
			<td style='$tr5' align='right'>=DECIMAL(" . $sum_actual . "; 2)</td>
		</tr>";
        break;

    //    Proyeccion Presupuestaria...
    case "proyeccion_presupuestaria":
        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr3 = "background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:12px; color:#000000;";
        //---------------------------------------------
        $sql   = "SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='" . $financiamiento . "') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------
        echo "
		<table border='1'>
			<tr><td colspan='11'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
			<tr><td colspan='11'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
			<tr><td colspan='11'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
			<tr>
				<th width='100' style='$tr1'>PARTIDA</th>
				<th width='750' style='$tr1'>DESCRIPCION</th>
				<th width='200' style='$tr1'>ASIG. AJUSTADA</th>
				<th width='200' style='$tr1'>COMPROMISO AL DIA</th>
				<th width='60' style='$tr1'>% COMP</th>
				<th width='200' style='$tr1'>DISPONIBLE</th>
				<th width='60' style='$tr1'>%DISP</th>
				<th width='200' style='$tr1'>PROYECCION</th>
				<th width='60' style='$tr1'>%PROY</th>
				<th width='200' style='$tr1'>D.PROYECTADO</th>
				<th width='60' style='$tr1'>%DP</th>
			</tr>";
        //---------------------------------------------
        if ($idcategoria_programatica == '0') {
            $filtro = "";
        } else {
            $filtro = " AND (maestro_presupuesto.idcategoria_programatica='" . $idcategoria_programatica . "')";
        }

        //------------------------------------------------
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
					  'especifica' AS Tipo,

					  (SELECT MAX(ocs.codigo_referencia)
						FROM orden_compra_servicio ocs
						INNER JOIN partidas_orden_compra_servicio pocs ON (ocs.idorden_compra_servicio=pocs.idorden_compra_servicio)
						WHERE (pocs.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
					  ) AS UltimoCodigo,

					  (SELECT p.monto
						FROM orden_compra_servicio o
						INNER JOIN partidas_orden_compra_servicio p ON (o.idorden_compra_servicio=p.idorden_compra_servicio)
						WHERE (p.idmaestro_presupuesto=maestro_presupuesto.idRegistro AND o.codigo_referencia=UltimoCodigo AND (o.estado='procesado' OR o.estado='pagado')
							AND o.fecha_orden<'$anio_fiscal-$mes-01')
					  ) AS UltimoPago,

					  (SELECT MAX(ocs.fecha_orden)
						FROM orden_compra_servicio ocs
						INNER JOIN partidas_orden_compra_servicio pocs ON (ocs.idorden_compra_servicio=pocs.idorden_compra_servicio)
						WHERE (pocs.idmaestro_presupuesto=maestro_presupuesto.idRegistro)
					  ) AS UltimaFecha,
					  ordinal.codigo AS codordinal,
					  ordinal.denominacion AS nomordinal

				FROM
					  maestro_presupuesto,
					  categoria_programatica,
					  unidad_ejecutora,
					  clasificador_presupuestario, ordinal
				WHERE
					  ((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					  (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					  (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
					  (maestro_presupuesto.idordinal=ordinal.idordinal) AND
					  (maestro_presupuesto.anio='" . $anio_fiscal . "' AND
					  maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
					  maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
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
						'generica' AS Tipo,

						'' AS UltimoCodigo,
						'' AS UltimoPago,
						'' AS UltimaFecha,
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
						(maestro_presupuesto.anio='" . $anio_fiscal . "' AND
						maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
						maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
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
							'partida' AS Tipo,

							'' AS UltimoCodigo,
							'' AS UltimoPago,
							'' AS UltimaFecha,
							'0000' AS codordinal,
							'' As nomordinal

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
							(maestro_presupuesto.anio='" . $anio_fiscal . "' AND
							maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
							maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
						GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
						ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            $clasificador  = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $disp          = $field["Actual"] - $field["PreCompromiso"] - $field["Compromiso"] - $field["ReservadoDisminuir"];
            $formulado     = number_format($field["Formulado"], 2, ',', '');
            $actual        = number_format($field["Actual"], 2, ',', '');
            $compromiso    = number_format($field["Compromiso"], 2, ',', '');
            $precompromiso = number_format($field["PreCompromiso"], 2, ',', '');
            $causado       = number_format($field["Causado"], 2, ',', '');
            $pagado        = number_format($field["Pagado"], 2, ',', '');
            if ($referencia == "U") {
                $proyeccion  = number_format($field["UltimoPago"], 2, ',', '');
                $dproy       = $disp - $field["UltimoPago"];
                $dproyectado = number_format($dproy, 2, ',', '');
                $sum_proyeccion += $field["UltimoPago"];
                $sum_dproyectado += $dproy;
            } elseif ($referencia == "P") {
                list($a, $mes_ultimo, $d) = SPLIT('[/.-]', $field['UltimaFecha']);
                $m                        = (int) $mes_ultimo;
                $meses_pasados            = $m;
                $meses_faltan             = 12 - $m;
                if ($field["Compromiso"] != 0 && $meses_pasados != 0) {
                    $promedio = ($field["Compromiso"] / $meses_pasados) * $meses_faltan;
                }

                $proyeccion  = number_format($promedio, 2, ',', '');
                $dproy       = $disp - $promedio;
                $dproyectado = number_format($dproy, 2, ',', '');
                $sum_proyeccion += $promedio;
                $sum_dproyectado += $dproy;
            }
            $disponible = number_format($disp, 2, ',', '');
            if ($field["Compromiso"] == 0 or $field["Actual"] == 0) {
                $pcompromiso = "0";
            } else {
                $pcompromiso = (float) (($field["Compromiso"] * 100) / $field["Actual"]);
            }

            $pcompromiso = number_format($pcompromiso, 2, ',', '');
            if ($field["Causado"] == 0 or $field["Actual"] == 0) {
                $pcausado = "0";
            } else {
                $pcausado = (float) (($field["Causado"] * 100) / $field["Actual"]);
            }

            $pcausado = number_format($pcausado, 2, ',', '');
            if ($field["Pagado"] == 0 or $field["Actual"] == 0) {
                $ppagado = "0";
            } else {
                $ppagado = (float) (($field["Pagado"] * 100) / $field["Actual"]);
            }

            $ppagado = number_format($ppagado, 2, ',', '');
            if ($field["Compromiso"] == 0 or $field["Actual"] == 0) {
                $pdisponible = "0";
            } else
            if ($disp == 0) {
                $pdisponible = "0";
            } else {
                $pdisponible = (float) ((($field["Actual"] - $field["Compromiso"]) * 100) / $field["Actual"]);
            }

            if ($field["UltimoPago"] == 0 or $field["Actual"] == 0) {
                $pproyeccion = "0";
            } else {
                $pproyeccion = (float) (($field["UltimoPago"] * 100) / $field["Actual"]);
            }

            $pproyeccion = number_format($pproyeccion, 2, ',', '');
            if ($dproy == 0 or $field["Actual"] == 0) {
                $pdproyectado = "0";
            } else {
                $pdproyectado = (float) (($dproy * 100) / $field["Actual"]);
            }

            $pdproyectado = number_format($pdproyectado, 2, ',', '');
            $pdisponible  = number_format($pdisponible, 2, ',', '');

            //    SI CAMBIA DE CATEGORIA LA IMPRIMO
            if ($field["IdCategoria"] != $IdCategoria) {
                $IdCategoria = $field["IdCategoria"];
                echo "<tr><td colspan='11' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>";
            }
            if ($field["Tipo"] == "partida") {
                $sum_formulado += $field["Formulado"];
                $sum_actual += $field["Actual"];
                $sum_compromiso += $field["Compromiso"];
                $sum_precompromiso += $field["PreCompromiso"];
                $sum_causado += $field["Causado"];
                $sum_pagado += $field["Pagado"];
                $sum_disponible += $disp;
            }

            if ($field['codordinal'] != "0000") {
                $descpartida = $field['codordinal'] . " " . $field['nomordinal'];
            } else {
                $descpartida = $field['NomPartida'];
            }

            echo "
			<tr>
				<td style='$tr5'>" . $clasificador . "</td>
				<td style='$tr5'>" . utf8_decode($descpartida) . "</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $actual . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $compromiso . "; 2)</td>
				<td style='$tr5' align='right'>" . $pcompromiso . " %</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $disponible . "; 2)</td>
				<td style='$tr5' align='right'>" . $pdisponible . " %</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $proyeccion . "; 2)</td>
				<td style='$tr5' align='right'>" . $pproyeccion . " %</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $dproyectado . "; 2)</td>
				<td style='$tr5' align='right'>" . $pdproyectado . " %</td>
			</tr>";
            $formulado    = "";
            $actual       = "";
            $compromiso   = "";
            $pcompromiso  = "";
            $disponible   = "";
            $pdisponible  = "";
            $proyeccion   = "";
            $pproyeccion  = "";
            $dproyectado  = "";
            $pdproyectado = "";

        }
        //------------------------------------------------
        if ($sum_compromiso == 0 or $sum_actual == 0) {
            $tpcompromiso = 0;
        } else {
            $tpcompromiso = (float) (($sum_compromiso * 100) / $sum_actual);
        }

        $tpcompromiso = number_format($tpcompromiso, 2, ',', '');
        if ($sum_causado == 0 or $sum_actual) {
            $tpcausado = 0;
        } else {
            $tpcausado = (float) (($sum_causado * 100) / $sum_actual);
        }

        $tpcausado = number_format($tpcausado, 2, ',', '');
        if ($sum_pagado == 0 or $sum_actual == 0) {
            $tppagado = 0;
        } else {
            $tppagado = (float) (($sum_pagado * 100) / $sum_actual);
        }

        $tppagado = number_format($tppagado, 2, ',', '');
        if ($sum_proyeccion == 0 or $sum_actual == 0) {
            $tpproyeccion = 0;
        } else {
            $tpproyeccion = (float) (($sum_proyeccion * 100) / $sum_actual);
        }

        $tpproyeccion = number_format($tpproyeccion, 2, ',', '');
        if ($sum_dproyectado == 0 or $sum_actual == 0) {
            $tpdproyectado = 0;
        } else {
            $tpdproyectado = (float) (($sum_dproyectado * 100) / $sum_actual);
        }

        $tpdproyectado = number_format($tpdproyectado, 2, ',', '');
        if ($sum_disponible == 0 or $sum_actual == 0) {
            $tpdisponible = 0;
        } else {
            $tpdisponible = (float) (($sum_disponible * 100) / $sum_actual);
        }

        $tpdisponible    = number_format($tpdisponible, 2, ',', '');
        $sum_formulado   = number_format($sum_formulado, 2, ',', '');
        $sum_compromiso  = number_format($sum_compromiso, 2, ',', '');
        $sum_disponible  = number_format($sum_disponible, 2, ',', '');
        $sum_proyeccion  = number_format($sum_proyeccion, 2, ',', '');
        $sum_dproyectado = number_format($sum_dproyectado, 2, ',', '');
        //    IMPRIMO LOS TOTALES
        echo "
		<tr>
			<td style='$tr3'></td>
			<td style='$tr3'></td>
			<td style='$tr3' align='right'>=DECIMAL(" . $sum_formulado . "; 2)</td>
			<td style='$tr3' align='right'>=DECIMAL(" . $sum_compromiso . "; 2)</td>
			<td style='$tr3' align='right'>=DECIMAL(" . $tpcompromiso . "; 2)</td>
			<td style='$tr3' align='right'>=DECIMAL(" . $sum_disponible . "; 2)</td>
			<td style='$tr3' align='right'>=DECIMAL(" . $tpdisponible . "; 2)</td>
			<td style='$tr3' align='right'>=DECIMAL(" . $sum_proyeccion . "; 2)</td>
			<td style='$tr3' align='right'>=DECIMAL(" . $tpproyeccion . "; 2)</td>
			<td style='$tr3' align='right'>=DECIMAL(" . $sum_dproyectado . "; 2)</td>
			<td style='$tr3' align='right'>=DECIMAL(" . $tpdproyectado . "; 2)</td>
		</tr>";
        break;

    //    Simular Solicitud Presupuesto...
    case "simular_solicitud_presupuesto":
        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr3 = "background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:12px; color:#000000;";
        //---------------------------------------------
        $sql   = "SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='" . $financiamiento . "') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------
        echo "
		<table border='1'>
			<tr><td colspan='4'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
			<tr><td colspan='4'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
			<tr><td colspan='4'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
			<tr>
				<th width='100' style='$tr1'>PARTIDA</th>
				<th width='750' style='$tr1'>DESCRIPCION</th>
				<th width='200' style='$tr1'>MONTO AÑO " . $anio_fiscal . "</th>
				<th width='200' style='$tr1'>MONTO SIMULADO</th>
			</tr>";
        //---------------------------------------------
        if ($idcategoria_programatica == '0') {
            $filtro = "";
        } else {
            $filtro = " AND (maestro_presupuesto.idcategoria_programatica='" . $idcategoria_programatica . "')";
        }

        //------------------------------------------------
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
					  (maestro_presupuesto.anio='" . $anio_fiscal . "' AND
					  maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
					  maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
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
						(maestro_presupuesto.anio='" . $anio_fiscal . "' AND
						maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
						maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
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
							(maestro_presupuesto.anio='" . $anio_fiscal . "' AND
							maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
							maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
						GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
						ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            $clasificador  = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $formulado     = number_format($field["Formulado"], 2, ',', '');
            $actual        = number_format($field["Actual"], 2, ',', '');
            $compromiso    = number_format($field["Compromiso"], 2, ',', '');
            $precompromiso = number_format($field["PreCompromiso"], 2, ',', '');
            $causado       = number_format($field["Causado"], 2, ',', '');
            $pagado        = number_format($field["Pagado"], 2, ',', '');
            $disponible    = number_format(($field["Actual"] - $field["PreCompromiso"] - $field["Compromiso"] - $field["ReservadoDisminuir"]), 2, ',', '');
            if ($field["Compromiso"] == 0 or $field["Actual"] == 0) {
                $pcompromiso = "0";
            } else {
                $pcompromiso = (float) (($field["Compromiso"] * 100) / $field["Actual"]);
            }

            $pcompromiso = number_format($pcompromiso, 2, ',', '');
            if ($field["Causado"] == 0 or $field["Actual"] == 0) {
                $pcausado = "0";
            } else {
                $pcausado = (float) (($field["Causado"] * 100) / $field["Actual"]);
            }

            $pcausado = number_format($pcausado, 2, ',', '');
            if ($field["Pagado"] == 0 or $field["Actual"] == 0) {
                $ppagado = "0";
            } else {
                $ppagado = (float) (($field["Pagado"] * 100) / $field["Actual"]);
            }

            $ppagado = number_format($ppagado, 2, ',', '');
            if ($field["Compromiso"] == 0 or $field["Actual"] == 0) {
                $pdisponible = "0";
            } else
            if (($field["Actual"] - $field["PreCompromiso"] - $field["Compromiso"] - $field["ReservadoDisminuir"]) == 0) {
                $pdisponible = "0";
            } else {
                $pdisponible = (float) ((($field["Actual"] - $field["Compromiso"]) * 100) / $field["Actual"]);
            }

            $pdisponible = number_format($pdisponible, 2, ',', '');
            //    SI CAMBIA DE CATEGORIA LA IMPRIMO
            if ($field["IdCategoria"] != $IdCategoria) {
                $IdCategoria = $field["IdCategoria"];
                echo "<tr><td colspan='4' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>";
            }
            if ($field["Tipo"] == "partida") {
                $sum_formulado += $field["Formulado"];
                $sum_actual += $field["Actual"];
                $sum_compromiso += $field["Compromiso"];
                $sum_precompromiso += $field["PreCompromiso"];
                $sum_causado += $field["Causado"];
                $sum_pagado += $field["Pagado"];
                $sum_disponible += ($field["Actual"] - $field["PreCompromiso"] - $field["Compromiso"] - $field["ReservadoDisminuir"]);
            }
            //    -----------
            if ($sobre == "O") {
                $monto_anio = $field['Formulado'];
            } elseif ($sobre == "A") {
                $monto_anio = $field['Actual'];
            }

            $monto_simulado = (($monto_anio * $porcentaje) / 100) + $monto_anio;

            $monto    = number_format($monto_anio, 2, ',', '');
            $simulado = number_format($monto_simulado, 2, ',', '');

            $sum_monto_anio += $monto_anio;
            $sum_monto_simulado += $monto_simulado;
            //    -----------

            if ($field['codordinal'] != "0000") {
                $descripcion = $field['codordinal'] . " " . $field['nomordinal'];
            } else {
                $descripcion = $field['NomPartida'];
            }

            echo "
			<tr>
				<td style='$tr5'>" . $clasificador . "</td>
				<td style='$tr5'>" . utf8_decode($descripcion) . "</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $monto . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $simulado . "; 2)</td>
			</tr>";

            $formulado     = "";
            $actual        = "";
            $precompromiso = "";
            $compromiso    = "";
            $pcompromiso   = "";
            $causado       = "";
            $pcausado      = "";
            $pagado        = "";
            $ppagado       = "";
            $disponible    = "";
            $pdisponible   = "";
        }
        //------------------------------------------------
        if ($sum_compromiso == 0 or $sum_actual == 0) {
            $tpcompromiso = 0;
        } else {
            $tpcompromiso = (float) (($sum_compromiso * 100) / $sum_actual);
        }

        $tpcompromiso = number_format($tpcompromiso, 2, ',', '');
        if ($sum_causado == 0 or $sum_actual) {
            $tpcausado = 0;
        } else {
            $tpcausado = (float) (($sum_causado * 100) / $sum_actual);
        }

        $tpcausado = number_format($tpcausado, 2, ',', '');
        if ($sum_pagado == 0 or $sum_actual == 0) {
            $tppagado = 0;
        } else {
            $tppagado = (float) (($sum_pagado * 100) / $sum_actual);
        }

        $tppagado = number_format($tppagado, 2, ',', '');
        if ($sum_disponible == 0 or $sum_actual == 0) {
            $tpdisponible = 0;
        } else {
            $tpdisponible = (float) (($sum_disponible * 100) / $sum_actual);
        }

        $tpdisponible = number_format($tpdisponible, 2, ',', '');

        $sum_formulado      = number_format($sum_formulado, 2, ',', '');
        $sum_actual         = number_format($sum_actual, 2, ',', '');
        $sum_precompromiso  = number_format($sum_precompromiso, 2, ',', '');
        $sum_compromiso     = number_format($sum_compromiso, 2, ',', '');
        $sum_causado        = number_format($sum_causado, 2, ',', '');
        $sum_pagado         = number_format($sum_pagado, 2, ',', '');
        $sum_disponible     = number_format($sum_disponible, 2, ',', '');
        $sum_monto_anio     = number_format($sum_monto_anio, 2, ',', '');
        $sum_monto_simulado = number_format($sum_monto_simulado, 2, ',', '');

        //    IMPRIMO LOS TOTALES
        echo "
		<tr>
			<td style='$tr2'></td>
			<td style='$tr2'></td>
			<td style='$tr2' align='right'>=DECIMAL(" . $sum_monto_anio . "; 2)</td>
			<td style='$tr2' align='right'>=DECIMAL(" . $sum_monto_simulado . "; 2)</td>
		</tr>";
        break;

    //    Documentos por Partida...
    case "documentos_por_partida":
        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr3 = "background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:12px; color:#000000;";
        //---------------------------------------------
        $sql   = "SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='" . $financiamiento . "') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------
        $sql = "SELECT
					cp.codigo,
					u.denominacion
				FROM
					categoria_programatica cp
					INNER JOIN unidad_ejecutora u ON (u.idunidad_ejecutora = cp.idunidad_ejecutora)
				WHERE
					cp.idcategoria_programatica = '" . $idcategoria . "'";
        $query_categoria = mysql_query($sql) or die($sql . mysql_error());
        if (mysql_num_rows($query_categoria) != 0) {
            $field_categoria = mysql_fetch_array($query_categoria);
        }

        //---------------------------------------------
        if ($idordinal != "") {
            $where_ordinal = "AND o.idordinal = '" . $idordinal . "'";
        } else {
            $where_ordinal = "AND o.codigo = '0000'";
        }

        $sql = "SELECT
					CONCAT(cp.partida, '.', cp.generica, '.', cp.especifica, '.', cp.sub_especifica) AS codigo,
					cp.denominacion,
					o.codigo AS codordinal,
					o.denominacion AS nomordinal
				FROM
					clasificador_presupuestario cp
					INNER JOIN maestro_presupuesto mp ON (cp.idclasificador_presupuestario = mp.idclasificador_presupuestario AND anio = '" . $anio_fiscal . "' AND mp.idcategoria_programatica = '" . $idcategoria . "' AND mp.idtipo_presupuesto = '" . $tipo_presupuesto . "' AND mp.idfuente_financiamiento = '" . $financiamiento . "')
					INNER JOIN ordinal o ON (mp.idordinal = o.idordinal $where_ordinal)
				WHERE
					cp.idclasificador_presupuestario = '" . $idpartida . "'";
        $query_partida = mysql_query($sql) or die($sql . mysql_error());
        if (mysql_num_rows($query_partida) != 0) {
            $field_partida = mysql_fetch_array($query_partida);
        }

        //---------------------------------------------
        if ($estado != "0") {
            $filtro_ocs = "AND ocs.estado='" . $estado . "'";
            if ($estado == "pagado") {
                $filtro_op = "AND op.estado='pagada'";
            } else {
                $filtro_op = "AND op.estado='" . $estado . "'";
            }

        }
        //---------------------------------------------
        echo "
		<table border='1'>
			<tr><td colspan='5'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
			<tr><td colspan='5'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
			<tr><td colspan='5'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
			<tr><td colspan='5'>Categoria Programatica: " . utf8_decode($field_categoria['codigo'] . ' ' . $field_categoria['denominacion']) . "</td></tr>
			<tr><td colspan='5'>Partida: " . utf8_decode($partida) . "</td></tr>
			<tr>
				<th width='150' style='$tr1'>NUMERO</th>
				<th width='150' style='$tr1'>FECHA</th>
				<th width='150' style='$tr1'>ESTADO</th>
				<th width='500' style='$tr1'>BENEFICIARIO</th>
				<th width='250' style='$tr1'>MONTO</th>
			</tr>";
        //---------------------------------------------
        if ($idordinal != "") {
            $where_ordinal = "AND o.idordinal = '" . $idordinal . "'";
        }

        //---------------------------------------------
        $sql = "(SELECT
					ocs.numero_orden,
					ocs.fecha_orden,
					ocs.estado,
					ocs.total,
					b.nombre,
					ocs.codigo_referencia
				FROM
					orden_compra_servicio ocs
					INNER JOIN beneficiarios b ON (ocs.idbeneficiarios=b.idbeneficiarios)
					INNER JOIN tipos_documentos td ON (ocs.tipo=td.idtipos_documentos AND td.compromete='si')
					INNER JOIN partidas_orden_compra_servicio pocs ON (ocs.idorden_compra_servicio=pocs.idorden_compra_servicio)
					INNER JOIN maestro_presupuesto mp ON (pocs.idmaestro_presupuesto=mp.idRegistro
															AND mp.idcategoria_programatica='" . $idcategoria . "'
															AND mp.idclasificador_presupuestario='" . $idpartida . "')
					INNER JOIN ordinal o ON (mp.idordinal = o.idordinal $where_ordinal)
				WHERE
					ocs.estado <> 'elaboracion' $filtro_ocs)

				UNION

				(SELECT
					op.numero_orden,
					op.fecha_orden,
					op.estado,
					op.total,
					b.nombre,
					op.codigo_referencia
				FROM
					orden_pago op
					INNER JOIN beneficiarios b ON (op.idbeneficiarios=b.idbeneficiarios)
					INNER JOIN tipos_documentos td ON (op.tipo=td.idtipos_documentos AND td.compromete='si')
					INNER JOIN partidas_orden_pago pop ON (op.idorden_pago=pop.idorden_pago)
					INNER JOIN maestro_presupuesto mp ON (pop.idmaestro_presupuesto=mp.idRegistro
															AND mp.idcategoria_programatica='" . $idcategoria . "'
															AND mp.idclasificador_presupuestario='" . $idpartida . "')
					INNER JOIN ordinal o ON (mp.idordinal = o.idordinal $where_ordinal)
				WHERE
					op.estado <> 'elaboracion' $filtro_op )


				ORDER BY codigo_referencia, fecha_orden";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            list($a, $m, $d) = SPLIT('[/.-]', $field['fecha_orden']);
            $fecha_orden     = $d . "/" . $m . "/" . $a;
            $monto           = number_format($field['total'], 2, ',', '');
            $sum_monto += $field['total'];
            //    Imprimo el registro
            echo "
			<tr>
				<td style='$tr5'>" . $field['numero_orden'] . "</td>
				<td style='$tr5'>" . $fecha_orden . "</td>
				<td style='$tr5'>" . utf8_decode($field['estado']) . "</td>
				<td style='$tr5'>" . utf8_decode($field['nombre']) . "</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $monto . "; 2)</td>
			</tr>";
        }
        //---------------------------------------------
        $sum_monto = number_format($sum_monto, 2, ',', '');
        //    Imprimo el total
        echo "
		<tr>
			<td style='$tr2'></td>
			<td style='$tr2'></td>
			<td style='$tr2'></td>
			<td style='$tr2'></td>
			<td style='$tr2' align='right'>=DECIMAL(" . $sum_monto . "; 2)</td>
		</tr>";
        break;

    //    Resumen por Partida...
    case "resumen_por_partida":
        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr3 = "background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:12px; color:#000000;";
        //---------------------------------------------
        $sql   = "SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='" . $financiamiento . "') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------
        $sql   = "SELECT denominacion, CONCAT(partida, '.', generica, '.', especifica, '.', sub_especifica, '.') AS clasificador FROM clasificador_presupuestario WHERE idclasificador_presupuestario = '" . $idpartida . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $partida = $field['clasificador'] . " - " . $field['denominacion'];
        //---------------------------------------------
        echo "
		<table border='1'>
			<tr><td colspan='5'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
			<tr><td colspan='5'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
			<tr><td colspan='5'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
			<tr><td colspan='5'>Partida: " . utf8_decode($partida) . "</td></tr>
			<tr>
				<th width='100' style='$tr1'>CODIGO</th>
				<th width='750' style='$tr1'>UNIDAD EJECUTORA</th>
				<th width='200' style='$tr1'>ASIG. INICIAL</th>
				<th width='200' style='$tr1'>AUMENTO</th>
				<th width='200' style='$tr1'>DISMINUCION</th>
				<th width='200' style='$tr1'>MODIFICADO</th>
				<th width='200' style='$tr1'>ASIG. AJUSTADA</th>
				<th width='200' style='$tr1'>PRE-COMPROMISO</th>
				<th width='200' style='$tr1'>COMPROMISO</th>
				<th width='60' style='$tr1'>% COMP</th>
				<th width='200' style='$tr1'>CAUSADO</th>
				<th width='60' style='$tr1'>% CAU</th>
				<th width='200' style='$tr1'>PAGADO</th>
				<th width='60' style='$tr1'>% PAG</th>
				<th width='200' style='$tr1'>DISPONIBLE</th>
				<th width='60' style='$tr1'>%DISP</th>
			</tr>";
        //---------------------------------------------
        if ($idpartida == '0') {
            $filtro = "";
        } else {
            $filtro = " AND (maestro_presupuesto.idclasificador_presupuestario='" . $idpartida . "')";
        }

        //------------------------------------------------
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
					  'especifica' AS Tipo
				FROM
					  maestro_presupuesto,
					  categoria_programatica,
					  unidad_ejecutora,
					  clasificador_presupuestario
				WHERE
					  ((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					  (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					  (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
					  (maestro_presupuesto.anio='" . $anio_fiscal . "' AND
					  maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
					  maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "') $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp)
						ORDER BY CodCategoria, Par, Gen, Esp, Sesp";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            $clasificador  = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $formulado     = number_format($field["Formulado"], 2, ',', '');
            $aumento       = number_format($field["TotalAumento"], 2, ',', '');
            $disminucion   = number_format($field['TotalDisminucion'], 2, ',', '');
            $modificado    = number_format(($field["TotalAumento"] - $field['TotalDisminucion']), 2, ',', '');
            $actual        = number_format($field["Actual"], 2, ',', '');
            $compromiso    = number_format($field["Compromiso"], 2, ',', '');
            $precompromiso = number_format($field["PreCompromiso"], 2, ',', '');
            $causado       = number_format($field["Causado"], 2, ',', '');
            $pagado        = number_format($field["Pagado"], 2, ',', '');
            $disponible    = number_format(($field["Actual"] - $field["PreCompromiso"] - $field["Compromiso"] - $field["ReservadoDisminuir"]), 2, ',', '');
            if ($field["Compromiso"] == 0 or $field["Actual"] == 0) {
                $pcompromiso = "0";
            } else {
                $pcompromiso = (float) (($field["Compromiso"] * 100) / $field["Actual"]);
            }

            $pcompromiso = number_format($pcompromiso, 2, ',', '');
            if ($field["Causado"] == 0 or $field["Actual"] == 0) {
                $pcausado = "0";
            } else {
                $pcausado = (float) (($field["Causado"] * 100) / $field["Actual"]);
            }

            $pcausado = number_format($pcausado, 2, ',', '');
            if ($field["Pagado"] == 0 or $field["Actual"] == 0) {
                $ppagado = "0";
            } else {
                $ppagado = (float) (($field["Pagado"] * 100) / $field["Actual"]);
            }

            $ppagado = number_format($ppagado, 2, ',', '');
            if ($field["Compromiso"] == 0 or $field["Actual"] == 0) {
                $pdisponible = "0";
            } else
            if (($field["Actual"] - $field["PreCompromiso"] - $field["Compromiso"] - $field["ReservadoDisminuir"]) == 0) {
                $pdisponible = "0";
            } else {
                $pdisponible = (float) ((($field["Actual"] - $field["Compromiso"]) * 100) / $field["Actual"]);
            }

            $pdisponible = number_format($pdisponible, 2, ',', '');

            $sum_formulado += $field["Formulado"];
            $sum_modificado += ($field["TotalAumento"] - $field['TotalDisminucion']);
            $sum_aumento += $field["TotalAumento"];
            $sum_disminucion += $field['TotalDisminucion'];
            $sum_actual += $field["Actual"];
            $sum_compromiso += $field["Compromiso"];
            $sum_precompromiso += $field["PreCompromiso"];
            $sum_causado += $field["Causado"];
            $sum_pagado += $field["Pagado"];
            if ($chkrestar) {
                $sum_disponible += ($field["Actual"] - $field["PreCompromiso"] - $field["Compromiso"] - $field["ReservadoDisminuir"]);
            } else {
                $sum_disponible += ($field["Actual"] - $field["Compromiso"] - $field["ReservadoDisminuir"]);
            }

            echo "
			<tr>
				<td style='$tr5'>" . $field['CodCategoria'] . "</td>
				<td style='$tr5'>" . utf8_decode($field["Unidad"]) . "</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $formulado . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $aumento . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $disminucion . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $modificado . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $actual . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $precompromiso . "; 2)</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $compromiso . "; 2)</td>
				<td style='$tr5' align='right'>" . $pcompromiso . " %</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $causado . "; 2)</td>
				<td style='$tr5' align='right'>" . $pcausado . " %</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $pagado . "; 2)</td>
				<td style='$tr5' align='right'>" . $ppagado . " %</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $disponible . "; 2)</td>
				<td style='$tr5' align='right'>" . $pdisponible . " %</td>
			</tr>";
            $aumento       = "";
            $disminucion   = "";
            $formulado     = "";
            $actual        = "";
            $modificado    = "";
            $precompromiso = "";
            $compromiso    = "";
            $pcompromiso   = "";
            $causado       = "";
            $pcausado      = "";
            $pagado        = "";
            $ppagado       = "";
            $disponible    = "";
            $pdisponible   = "";
        }
        //------------------------------------------------
        if ($sum_compromiso == 0 or $sum_actual == 0) {
            $tpcompromiso = 0;
        } else {
            $tpcompromiso = (float) (($sum_compromiso * 100) / $sum_actual);
        }

        $tpcompromiso = number_format($tpcompromiso, 2, ',', '');
        if ($sum_causado == 0 or $sum_actual) {
            $tpcausado = 0;
        } else {
            $tpcausado = (float) (($sum_causado * 100) / $sum_actual);
        }

        $tpcausado = number_format($tpcausado, 2, ',', '');
        if ($sum_pagado == 0 or $sum_actual == 0) {
            $tppagado = 0;
        } else {
            $tppagado = (float) (($sum_pagado * 100) / $sum_actual);
        }

        $tppagado = number_format($tppagado, 2, ',', '');
        if ($sum_disponible == 0 or $sum_actual == 0) {
            $tpdisponible = 0;
        } else {
            $tpdisponible = (float) (($sum_disponible * 100) / $sum_actual);
        }

        $tpdisponible      = number_format($tpdisponible, 2, ',', '');
        $sum_formulado     = number_format($sum_formulado, 2, ',', '');
        $sum_aumento       = number_format($sum_aumento, 2, ',', '');
        $sum_disminucion   = number_format($sum_disminucion, 2, ',', '');
        $sum_actual        = number_format($sum_actual, 2, ',', '');
        $sum_modificado    = number_format($sum_modificado, 2, ',', '');
        $sum_precompromiso = number_format($sum_precompromiso, 2, ',', '');
        $sum_compromiso    = number_format($sum_compromiso, 2, ',', '');
        $sum_causado       = number_format($sum_causado, 2, ',', '');
        $sum_pagado        = number_format($sum_pagado, 2, ',', '');
        $sum_disponible    = number_format($sum_disponible, 2, ',', '');
        //    IMPRIMO LOS TOTALES
        echo "
		<tr>
			<td style='$tr2' colspan='2'>&nbsp;</td>
			<td style='$tr2' align='right'>=DECIMAL(" . $sum_formulado . "; 2)</td>
			<td style='$tr2' align='right'>=DECIMAL(" . $sum_aumento . "; 2)</td>
			<td style='$tr2' align='right'>=DECIMAL(" . $sum_disminucion . "; 2)</td>
			<td style='$tr2' align='right'>=DECIMAL(" . $sum_modificado . "; 2)</td>
			<td style='$tr2' align='right'>=DECIMAL(" . $sum_actual . "; 2)</td>
			<td style='$tr2' align='right'>=DECIMAL(" . $sum_precompromiso . "; 2)</td>
			<td style='$tr2' align='right'>=DECIMAL(" . $sum_compromiso . "; 2)</td>
			<td style='$tr2' align='right'>" . $tpcompromiso . " %</td>
			<td style='$tr2' align='right'>=DECIMAL(" . $sum_causado . "; 2)</td>
			<td style='$tr2' align='right'>" . $tpcausado . " %</td>
			<td style='$tr2' align='right'>=DECIMAL(" . $sum_pagado . "; 2)</td>
			<td style='$tr2' align='right'>" . $tppagado . " %</td>
			<td style='$tr2' align='right'>=DECIMAL(" . $sum_disponible . "; 2)</td>
			<td style='$tr2' align='right'>" . $tpdisponible . " %</td>
		</tr>";
        break;

//    Compromisos en Transito...
    case "compromisos_en_transito":
        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr3 = "background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:12px; color:#000000;";
        //    -----------------------------
        $sql   = "SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='" . $financiamiento . "') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //    -----------------------------
        echo "
		<table border='1'>
			<tr>
				<td colspan='6'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td>
			</tr>
			<tr>
				<td colspan='6'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td>
			</tr>
			<tr>
				<td colspan='6'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td>
			</tr>";
        //    -----------------------------
        if ($categoria != "0") {
            $where_ocs = " AND ocs.idcategoria_programatica = '" . $categoria . "' ";
            $where_op  = " AND op.idcategoria_programatica = '" . $categoria . "' ";
        }
        if ($desde != "" && $hasta != "") {
            $where_ocs .= " AND ocs.fecha_orden >= '" . $desde . "' AND ocs.fecha_orden <= '" . $hasta . "' ";
            $where_op .= " AND op.fecha_orden >= '" . $desde . "' AND op.fecha_orden <= '" . $hasta . "' ";
        }
        //    -----------------------------
        $sql = "(SELECT
					  ocs.numero_orden,
					  ocs.fecha_orden,
					  ocs.justificacion,
					  ocs.estado,
					  ocs.total,
					  td.descripcion AS TipoDocumento,
					  ocs.idcategoria_programatica,
					  cp.codigo As codcategoria,
					  ue.denominacion AS nomcategoria,
					  b.nombre AS nombeneficiario
				FROM
					  orden_compra_servicio ocs
					  INNER JOIN tipos_documentos td ON (ocs.tipo = td.idtipos_documentos)
					  LEFT JOIN categoria_programatica cp ON (ocs.idcategoria_programatica = cp.idcategoria_programatica)
					  LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					  LEFT JOIN beneficiarios b ON (ocs.idbeneficiarios = b.idbeneficiarios)
				WHERE
					  ocs.estado = 'procesado' AND
					  ocs.anio = '" . $anio_fiscal . "' AND
					  ocs.idfuente_financiamiento = '" . $financiamiento . "' AND
					  ocs.idtipo_presupuesto = '" . $tipo_presupuesto . "' $where_ocs)

				UNION

				(SELECT
					  op.numero_orden,
					  op.fecha_orden,
					  op.justificacion,
					  op.estado,
					  op.total,
					  td.descripcion AS TipoDocumento,
					  op.idcategoria_programatica,
					  cp.codigo As codcategoria,
					  ue.denominacion AS nomcategoria,
					  b.nombre AS nombeneficiario
				FROM
					  orden_pago op
					  INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos)
					  INNER JOIN relacion_pago_compromisos rpc ON (op.idorden_pago = rpc.idorden_pago)
					  INNER JOIN orden_compra_servicio ocs ON (rpc.idorden_compra_servicio = ocs.idorden_compra_servicio AND ocs.estado = 'pagado')
					  LEFT JOIN categoria_programatica cp ON (op.idcategoria_programatica = cp.idcategoria_programatica)
					  LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					  LEFT JOIN beneficiarios b ON (op.idbeneficiarios = b.idbeneficiarios)
				WHERE
					  op.estado = 'procesado' AND
					  op.anio = '" . $anio_fiscal . "' AND
					  op.idfuente_financiamiento = '" . $financiamiento . "' AND
					  op.idtipo_presupuesto = '" . $tipo_presupuesto . "' $where_op)

				ORDER BY idcategoria_programatica, fecha_orden";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        while ($field = mysql_fetch_array($query)) {
            if ($idcategoria != $field['idcategoria_programatica']) {
                $idcategoria = $field['idcategoria_programatica'];
                if ($idcategoria == "0") {
                    $categoria_programatica = "MULTI-CATEGORIAS";
                } else {
                    $categoria_programatica = $field['codcategoria'] . ' - ' . $field['nomcategoria'];
                }

                echo "
				<tr><th style='$tr1' colspan='6' align='left'>" . $categoria_programatica . "</th></tr>
				<tr>
					<th width='125' style='$tr1'>Nro. Orden</th>
					<th width='100' style='$tr1'>Fecha Orden</th>
					<th width='250' style='$tr1'>Tipo</th>
					<th width='500' style='$tr1'>" . htmlentities('Justificación') . "</th>
					<th width='500' style='$tr1'>Estado</th>
					<th width='100' style='$tr1'>Estado</th>
					<th width='175' style='$tr1'>Total</th>
				</tr>";
            }

            list($a, $m, $d) = SPLIT('[/.-]', $field['fecha_orden']);
            $fecha_orden     = "$d/$m/$a";
            $total           = number_format($field['total'], 2, ',', '');

            echo "
			<tr>
				<td style='$tr5'>" . $field['numero_orden'] . "</td>
				<td style='$tr5' align='center'>" . $fecha_orden . "</td>
				<td style='$tr5'>" . utf8_decode(strtoupper($field['TipoDocumento'])) . "</td>
				<td style='$tr5'>" . utf8_decode($field["justificacion"]) . "</td>
				<td style='$tr5'>" . utf8_decode($field["nombeneficiario"]) . "</td>
				<td style='$tr5' align='center'>" . strtoupper($field["estado"]) . "</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $total . "; 2)</td>
			</tr>";

            $i++;
        }
        break;

    //    Movimientos de Presupuesto...
    case "movimientos_presupuesto":
        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr3 = "background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:12px; color:#000000;";
        //---------------------------------------------
        $sql   = "SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='" . $financiamiento . "') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------
        echo "
		<table border='1'>
			<tr>
				<td colspan='5'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td>
			</tr>
			<tr>
				<td colspan='5'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td>
			</tr>
			<tr>
				<td colspan='5'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td>
			</tr>
			<tr>
				<th width='100' style='$tr1'>CAT. PROG.</th>
				<th width='100' style='$tr1'>PARTIDA</th>
				<th width='750' style='$tr1'>DESCRIPCION</th>
				<th width='100' style='$tr1'>TIPO</th>
				<th width='200' style='$tr1'>MONTO</th>
			</tr>";
        //    -----------------------------
        $sql = "(SELECT partidas_cedentes_traslado.idpartida_cedentes_traslado AS IdMovimiento,
					 partidas_cedentes_traslado.idmaestro_presupuesto,
					 partidas_cedentes_traslado.monto_debitar AS Monto,
					 maestro_presupuesto.idcategoria_programatica,
					 maestro_presupuesto.idclasificador_presupuestario,
					 maestro_presupuesto.idordinal,
					 categoria_programatica.codigo AS CodCategoria,
					 CONCAT(clasificador_presupuestario.partida, '.', clasificador_presupuestario.generica, '.', clasificador_presupuestario.especifica, '.', clasificador_presupuestario.sub_especifica) AS CodPartida,
					 clasificador_presupuestario.denominacion AS NomPartida,
					 ordinal.codigo,
					 ordinal.denominacion AS NomOrdinal,
					 'Traslados' As TipoMovimiento
				FROM
					 partidas_cedentes_traslado,
					 maestro_presupuesto,
					 categoria_programatica,
					 clasificador_presupuestario,
					 ordinal
				WHERE
					(partidas_cedentes_traslado.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
					(maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
					(maestro_presupuesto.idordinal=ordinal.idordinal) AND
					(maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "') AND
					(maestro_presupuesto.idfuente_financiamiento = '" . $financiamiento . "') AND
					(maestro_presupuesto.anio = '" . $anio_fiscal . "'))

				UNION

				(SELECT partidas_disminucion_presupuesto.idpartida_disminucion_presupuesto AS IdMovimiento,
					 partidas_disminucion_presupuesto.idmaestro_presupuesto,
					 partidas_disminucion_presupuesto.monto_debitar AS Monto,
					 maestro_presupuesto.idcategoria_programatica,
					 maestro_presupuesto.idclasificador_presupuestario,
					 maestro_presupuesto.idordinal,
					 categoria_programatica.codigo AS CodCategoria,
					 CONCAT(clasificador_presupuestario.partida, '.', clasificador_presupuestario.generica, '.', clasificador_presupuestario.especifica, '.', clasificador_presupuestario.sub_especifica) AS CodPartida,
					 clasificador_presupuestario.denominacion AS NomPartida,
					 ordinal.codigo,
					 ordinal.denominacion AS NomOrdinal,
					 'Disminuciones' As TipoMovimiento
				FROM
					 partidas_disminucion_presupuesto,
					 maestro_presupuesto,
					 categoria_programatica,
					 clasificador_presupuestario,
					 ordinal
				WHERE
					 (partidas_disminucion_presupuesto.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
					 (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					 (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
					 (maestro_presupuesto.idordinal=ordinal.idordinal) AND
					 (maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "') AND
					 (maestro_presupuesto.idfuente_financiamiento = '" . $financiamiento . "') AND
					 (maestro_presupuesto.anio = '" . $anio_fiscal . "'))

				UNION

				(SELECT partidas_credito_adicional.idpartida_credito_adicional AS IdMovimiento,
					 partidas_credito_adicional.idmaestro_presupuesto,
					 partidas_credito_adicional.monto_acreditar AS Monto,
					 maestro_presupuesto.idcategoria_programatica,
					 maestro_presupuesto.idclasificador_presupuestario,
					 maestro_presupuesto.idordinal,
					 categoria_programatica.codigo AS CodCategoria,
					 CONCAT(clasificador_presupuestario.partida, '.', clasificador_presupuestario.generica, '.', clasificador_presupuestario.especifica, '.', clasificador_presupuestario.sub_especifica) AS CodPartida,
					 clasificador_presupuestario.denominacion AS NomPartida,
					 ordinal.codigo,
					 ordinal.denominacion As NomOrdinal,
					 'Crédito Adicional' AS TipoMovimiento
				FROM
					 partidas_credito_adicional,
					 maestro_presupuesto,
					 categoria_programatica,
					 clasificador_presupuestario,
					 ordinal
				WHERE
					 (partidas_credito_adicional.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
					 (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					 (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
					 (maestro_presupuesto.idordinal=ordinal.idordinal) AND
					 (maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "') AND
					 (maestro_presupuesto.idfuente_financiamiento = '" . $financiamiento . "') AND
					 (maestro_presupuesto.anio = '" . $anio_fiscal . "'))

				UNION

				(SELECT partidas_rectificadoras.idpartida_rectificadora AS IdMovimiento,
					 partidas_rectificadoras.idmaestro_presupuesto,
					 partidas_rectificadoras.monto_debitar AS Monto,
					 maestro_presupuesto.idcategoria_programatica,
					 maestro_presupuesto.idclasificador_presupuestario,
					 maestro_presupuesto.idordinal,
					 categoria_programatica.codigo AS CodCategoria,
					 CONCAT(clasificador_presupuestario.partida, '.', clasificador_presupuestario.generica, '.', clasificador_presupuestario.especifica, '.', clasificador_presupuestario.sub_especifica) AS CodPartida,
					 clasificador_presupuestario.denominacion AS NomPartida,
					 ordinal.codigo,
					 ordinal.denominacion AS NomOrdinal,
					 'Rectificaciones' AS TipoMovimiento
				FROM
					 partidas_rectificadoras,
					 maestro_presupuesto,
					 categoria_programatica,
					 clasificador_presupuestario,
					 ordinal
				WHERE
					 (partidas_rectificadoras.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
					 (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					 (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
					 (maestro_presupuesto.idordinal=ordinal.idordinal) AND
					 (maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "') AND
					 (maestro_presupuesto.idfuente_financiamiento = '" . $financiamiento . "') AND
					 (maestro_presupuesto.anio = '" . $anio_fiscal . "'))";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            $monto = number_format($field['Monto'], 2, ',', '');
            echo "
			<tr>
				<td style='$tr5' align='center'>|" . $field['CodCategoria'] . "|</td>
				<td style='$tr5' align='center'>" . $field['CodPartida'] . "</td>
				<td style='$tr5'>" . utf8_decode($field['NomPartida']) . "</td>
				<td style='$tr5'>" . utf8_decode($field['TipoMovimiento']) . "</td>
				<td style='$tr5' align='right'>=DECIMAL(" . $monto . "; 2)</td>
			</tr>";
        }
        break;

    //MENSUAL ONAPRE
    case "mensual_onapre":
        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:12px; color:#000000; font-weight:bold; border-bottom-width: thin; border-bottom: solid #000000;";
        $tr3 = "background-color:#D8D8D8; font-size:12px; color:#000000; font-weight:bold; ";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:12px; color:#000000;";
        //---------------------------------------------
        //---------------------------------------------
        $sql = "SELECT
					denominacion As TipoPresupuesto,
					(SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento = '" . $financiamiento . "') AS FuenteFinanciamiento
				FROM tipo_presupuesto
				WHERE idtipo_presupuesto = '" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];

        $filtro = "";
        if ($anio_fiscal != "") {
            $filtro .= " (maestro_presupuesto.anio = '" . $anio_fiscal . "')";
        }

        if ($financiamiento != "") {
            $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '" . $financiamiento . "')";
        }

        if ($tipo_presupuesto != "") {
            $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
        }

        if ($idcategoria_programatica != "") {
            $filtro .= " AND (maestro_presupuesto.idcategoria_programatica = '" . $idcategoria_programatica . "')";
        }

        //---------------------------------------------
        $desde_inicial = $anio_fiscal . '-01-01';
        $mes_inicial   = 1;
        if ($mes == '01') {
            $idesde         = '01-01-' . $anio_fiscal;
            $ihasta         = '31-01-' . $anio_fiscal;
            $desde          = $anio_fiscal . '-01-01';
            $hasta          = $anio_fiscal . '-01-31';
            $hasta_anterior = $anio_fiscal . '-01-31';
            $mes_final      = 1;
        }
        if ($mes == '02') {
            $idesde = '01-02-' . $anio_fiscal;if ($anio_fiscal % 4 == 0) {
                $ihasta = '29-02-' . $anio_fiscal;
            } else {
                $ihasta = '28-02-' . $anio_fiscal;
            }

            $desde = $anio_fiscal . '-02-01';if ($anio_fiscal % 4 == 0) {
                $hasta = $anio_fiscal . '-02-29';
            } else {
                $hasta = $anio_fiscal . '-02-28';
            }

            $hasta_anterior = $anio_fiscal . '-01-31';
            $mes_final      = 2;
            $mes_anterior   = 1;
        }
        if ($mes == '03') {
            $idesde = '01-03-' . $anio_fiscal;
            $ihasta = '31-03-' . $anio_fiscal;
            $desde  = $anio_fiscal . '-03-01';
            $hasta  = $anio_fiscal . '-03-31';
            if ($anio_fiscal % 4 == 0) {
                $hasta_anterior = $anio_fiscal . '-02-29';
            } else {
                $hasta_anterior = $anio_fiscal . '-02-28';
            }

            $mes_final    = 3;
            $mes_anterior = 2;
        }
        if ($mes == '04') {
            $idesde         = '01-04-' . $anio_fiscal;
            $ihasta         = '30-04-' . $anio_fiscal;
            $desde          = $anio_fiscal . '-04-01';
            $hasta          = $anio_fiscal . '-04-30';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 4;
            $mes_anterior   = 3;
        }
        if ($mes == '05') {
            $idesde         = '01-05-' . $anio_fiscal;
            $ihasta         = '31-05-' . $anio_fiscal;
            $desde          = $anio_fiscal . '-05-01';
            $hasta          = $anio_fiscal . '-05-31';
            $hasta_anterior = $anio_fiscal . '-04-30';
            $mes_final      = 5;
            $mes_anterior   = 4;
        }
        if ($mes == '06') {
            $idesde         = '01-06-' . $anio_fiscal;
            $ihasta         = '30-06-' . $anio_fiscal;
            $desde          = $anio_fiscal . '-06-01';
            $hasta          = $anio_fiscal . '-06-30';
            $hasta_anterior = $anio_fiscal . '-05-31';
            $mes_final      = 6;
            $mes_anterior   = 5;
        }
        if ($mes == '07') {
            $idesde         = '01-07-' . $anio_fiscal;
            $ihasta         = '31-07-' . $anio_fiscal;
            $desde          = $anio_fiscal . '-07-01';
            $hasta          = $anio_fiscal . '-07-31';
            $hasta_anterior = $anio_fiscal . '-06-30';
            $mes_final      = 7;
            $mes_anterior   = 6;
        }
        if ($mes == '08') {
            $idesde         = '01-08-' . $anio_fiscal;
            $ihasta         = '31-08-' . $anio_fiscal;
            $desde          = $anio_fiscal . '-08-01';
            $hasta          = $anio_fiscal . '-08-31';
            $hasta_anterior = $anio_fiscal . '-07-31';
            $mes_final      = 8;
            $mes_anterior   = 7;
        }
        if ($mes == '09') {
            $idesde         = '01-09-' . $anio_fiscal;
            $ihasta         = '30-09-' . $anio_fiscal;
            $desde          = $anio_fiscal . '-09-01';
            $hasta          = $anio_fiscal . '-09-30';
            $hasta_anterior = $anio_fiscal . '-08-31';
            $mes_final      = 9;
            $mes_anterior   = 8;
        }
        if ($mes == '10') {
            $idesde         = '01-10-' . $anio_fiscal;
            $ihasta         = '31-10-' . $anio_fiscal;
            $desde          = $anio_fiscal . '-10-01';
            $hasta          = $anio_fiscal . '-10-31';
            $hasta_anterior = $anio_fiscal . '-09-30';
            $mes_final      = 10;
            $mes_anterior   = 9;
        }
        if ($mes == '11') {
            $idesde         = '01-11-' . $anio_fiscal;
            $ihasta         = '30-11-' . $anio_fiscal;
            $desde          = $anio_fiscal . '-11-01';
            $hasta          = $anio_fiscal . '-11-30';
            $hasta_anterior = $anio_fiscal . '-10-31';
            $mes_final      = 11;
            $mes_anterior   = 10;
        }
        if ($mes == '12') {
            $idesde         = '01-12-' . $anio_fiscal;
            $ihasta         = '31-12-' . $anio_fiscal;
            $desde          = $anio_fiscal . '-12-01';
            $hasta          = $anio_fiscal . '-12-31';
            $hasta_anterior = $anio_fiscal . '-11-30';
            $mes_final      = 12;
            $mes_anterior   = 11;
        }
        //---------------------------------------------
        if ($mes == "01") {
            echo "
			<table>
				<tr><td colspan='10' style='$tr3'>EJECUCION MENSUAL</td></tr>
				<tr><td colspan='10'>Desde: " . $idesde . " Hasta: " . $ihasta . "</td></tr>
				<tr><td colspan='10'>&nbsp;</td></tr>
				<tr><td colspan='10'>&nbsp;</td></tr>
				<tr><td colspan='10'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
				<tr><td colspan='10'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
				<tr><td colspan='10'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
				<tr><td colspan='10'>&nbsp;</td></tr>
			</table>
			<table border='1'>
				<tr>
					<th width='100' style='$tr1'>PARTIDA</th>
					<th width='1000' style='$tr1'>DESCRIPCION</th>
					<th width='200' style='$tr1'>PRESUPUESTO INICIAL</th>
					<th width='200' style='$tr1'>MODIFICACION</th>
					<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
					<th width='200' style='$tr1'>COMPROMETIDO</th>
					<th width='200' style='$tr1'>CAUSADO</th>
					<th width='200' style='$tr1'>DISPONIBIBILIDAD PRESUPUESTARIA</th>
					<th width='200' style='$tr1'>PAGADO</th>
					<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
				</tr>
			</table>";
        } else {
            echo "
			<table>
				<tr><td colspan='11' style='$tr3'>EJECUCION MENSUAL</td></tr>
				<tr><td colspan='11'>Desde: " . $idesde . " Hasta: " . $ihasta . "</td></tr>
				<tr><td colspan='11'>&nbsp;</td></tr>
				<tr><td colspan='11'>&nbsp;</td></tr>
				<tr><td colspan='11'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
				<tr><td colspan='11'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
				<tr><td colspan='11'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
				<tr><td colspan='11'>&nbsp;</td></tr>
			</table>
			<table border='1'>
				<tr>
					<th width='100' style='$tr1'>PARTIDA</th>
					<th width='1000' style='$tr1'>DESCRIPCION</th>
					<th width='200' style='$tr1'>PRESUPUESTARIA ANTERIOR</th>
					<th width='200' style='$tr1'>FINANCIERA ANTERIOR</th>
					<th width='200' style='$tr1'>MODIFICACION</th>
					<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
					<th width='200' style='$tr1'>COMPROMETIDO</th>
					<th width='200' style='$tr1'>CAUSADO</th>
					<th width='200' style='$tr1'>DISPONIBILIDAD PRESUPUESTARIA</th>
					<th width='200' style='$tr1'>PAGADO</th>
					<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
				</tr>
			</table>";
        }

        if ($mes == 01) {

            //LLAMA A LA FUNCION PARA GENERAR SENTENCIA SQL DE UN PERIODO
            $sql = busca_rango_tiempo($desde, $hasta, $mes_inicial, $mes_final, $filtro, $anio_fiscal);

            $sql_suma   = $sql;
            $par        = 0;
            $gen        = 0;
            $esp        = 0;
            $sub        = 0;
            $query_suma = mysql_query($sql_suma) or die($sql_suma . mysql_error());
            while ($field = mysql_fetch_array($query_suma)) {
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['MCredito1'] - $field['MReceptora1'] - $field['MRectificacion1'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['MDisminucion1'] - $field['MCedentes1'] - $field['MRectificadora1'];
                }
                if ($field['Tipo'] == "partida") {
                    $par++;
                } elseif ($field['Tipo'] == "generica") {
                    $gen++;
                } elseif ($field['Tipo'] == "especifica") {
                    $esp++;

                    // DETERMINO SI ES ESPECIFICA PARA SUMARLA A LA ESPECIFICA
                    $aumentado_especificaI[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_genericaI[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_partidaI[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

                    $disminuido_especificaI[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_genericaI[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_partidaI[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

                    $comprometido_especificaI[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_partidaI[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

                    $causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_partidaI[$par] += $field['CausaI'] + $field['MRendicionCausado1'];

                    /*if($field['PagadoI']>$field['CausaI']){
                    $pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];
                    }else if($field['PagadoI']>0){*/
                    $pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_partidaI[$par] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    //}

                } elseif ($field['Tipo'] == "subespecifica") {
                    $sub++;
                    // DETERMINO SI ES SUB ESPECIFICA PARA SUMARLA A LA ESPECIFICA
                    $aumentado_subespecificaI[$sub] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_especificaI[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_genericaI[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_partidaI[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

                    $disminuido_subespecificaI[$sub] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_especificaI[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_genericaI[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_partidaI[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

                    $comprometido_subespecificaI[$sub] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_especificaI[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_partidaI[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

                    $causa_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_partidaI[$par] += $field['CausaI'] + $field['MRendicionCausado1'];

                    /*if($field['PagadoI']>$field['CausaI']){
                    $pagado_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];
                    }else if($field['PagadoI']>0){*/
                    $pagado_subespecificaI[$sub] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_partidaI[$par] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    //}
                }
            }
        } else {

            //LLAMA A LA FUNCION PARA GENERAR SENTENCIA SQL DE UN PERIODO
            $sql = busca_rango_tiempo($desde_inicial, $hasta_anterior, $mes_inicial, $mes_anterior, $filtro, $anio_fiscal);

            $sql_suma   = $sql;
            $par        = 0;
            $gen        = 0;
            $esp        = 0;
            $sub        = 0;
            $query_suma = mysql_query($sql_suma) or die($sql_suma . mysql_error());
            while ($field = mysql_fetch_array($query_suma)) {
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['MCredito1'] - $field['MReceptora1'] - $field['MRectificacion1'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['MDisminucion1'] - $field['MCedentes1'] - $field['MRectificadora1'];
                }
                if ($field['Tipo'] == "partida") {
                    $par++;
                } elseif ($field['Tipo'] == "generica") {
                    $gen++;
                } elseif ($field['Tipo'] == "especifica") {
                    $esp++;
                    // DETERMINO SI ES SUB ESPECIFICA PARA SUMARLA A LA ESPECIFICA
                    $aumentado_especificaI_anterior[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_genericaI_anterior[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_partidaI_anterior[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

                    $disminuido_especificaI_anterior[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_genericaI_anterior[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_partidaI_anterior[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

                    $comprometido_especificaI_anterior[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_genericaI_anterior[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_partidaI_anterior[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

                    $causa_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_partidaI_anterior[$par] += $field['CausaI'] + $field['MRendicionCausado1'];

                    /*if($field['PagadoI']>$field['CausaI']){
                    $pagado_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_partidaI_anterior[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];
                    }else if($field['PagadoI']>0){*/
                    $pagado_especificaI_anterior[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_genericaI_anterior[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_partidaI_anterior[$par] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    //}

                } elseif ($field['Tipo'] == "subespecifica") {
                    $sub++;
                    // DETERMINO SI ES SUB ESPECIFICA PARA SUMARLA A LA ESPECIFICA
                    $aumentado_subespecificaI_anterior[$sub] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_especificaI_anterior[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_genericaI_anterior[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_partidaI_anterior[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

                    $disminuido_subespecificaI_anterior[$sub] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_especificaI_anterior[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_genericaI_anterior[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_partidaI_anterior[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

                    $comprometido_subespecificaI_anterior[$sub] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_especificaI_anterior[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_genericaI_anterior[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_partidaI_anterior[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

                    $causa_subespecificaI_anterior[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_partidaI_anterior[$par] += $field['CausaI'] + $field['MRendicionCausado1'];

                    /*if($field['PagadoI']>$field['CausaI']){
                    $pagado_subespecificaI_anterior[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_partidaI_anterior[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];
                    }else if($field['PagadoI']>0){*/
                    $pagado_subespecificaI_anterior[$sub] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_especificaI_anterior[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_genericaI_anterior[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_partidaI_anterior[$par] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    //}
                }
            }

            //OBTENGO LOS TOTALES DEL MES SELECCIONADO
            //LLAMA A LA FUNCION PARA GENERAR SENTENCIA SQL DE UN PERIODO
            $sql = busca_rango_tiempo($desde, $hasta, $mes_anterior + 1, $mes_final, $filtro, $anio_fiscal);

            $sql_suma   = $sql;
            $par        = 0;
            $gen        = 0;
            $esp        = 0;
            $sub        = 0;
            $query_suma = mysql_query($sql_suma) or die($sql_suma . mysql_error());
            while ($field = mysql_fetch_array($query_suma)) {
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['MCredito1'] - $field['MReceptora1'] - $field['MRectificacion1'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['MDisminucion1'] - $field['MCedentes1'] - $field['MRectificadora1'];
                }
                if ($field['Tipo'] == "partida") {
                    $par++;
                } elseif ($field['Tipo'] == "generica") {
                    $gen++;
                } elseif ($field['Tipo'] == "especifica") {
                    $esp++;

                    // DETERMINO SI ES ESPECIFICA PARA SUMARLA A LA ESPECIFICA
                    $aumentado_especificaI[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_genericaI[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_partidaI[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

                    $disminuido_especificaI[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_genericaI[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_partidaI[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

                    $comprometido_especificaI[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_partidaI[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

                    $causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_partidaI[$par] += $field['CausaI'] + $field['MRendicionCausado1'];

                    /*if($field['PagadoI']>$field['CausaI']){
                    $pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionCausado1'];
                    $pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionCausado1'];
                    $pagado_partidaI[$par] +=  $field['PagadoI'] + $field['MRendicionCausado1'];
                    }else if($field['PagadoI']>0){*/
                    $pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_partidaI[$par] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    //}

                } elseif ($field['Tipo'] == "subespecifica") {
                    $sub++;
                    // DETERMINO SI ES SUB ESPECIFICA PARA SUMARLA A LA ESPECIFICA
                    $aumentado_subespecificaI[$sub] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_especificaI[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_genericaI[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_partidaI[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

                    $disminuido_subespecificaI[$sub] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_especificaI[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_genericaI[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_partidaI[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

                    $comprometido_subespecificaI[$sub] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_especificaI[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_partidaI[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

                    $causa_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_partidaI[$par] += $field['CausaI'] + $field['MRendicionCausado1'];

                    /*if($field['PagadoI']>$field['CausaI']){
                    $pagado_subespecificaI[$sub] += $field['PagadoI'] + $field['MRendicionCausado1'];
                    $pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionCausado1'];
                    $pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionCausado1'];
                    $pagado_partidaI[$par] +=  $field['PagadoI'] + $field['MRendicionCausado1'];
                    }else if($field['PagadoI']>0){*/
                    $pagado_subespecificaI[$sub] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_partidaI[$par] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    //}
                }
            }

        }

        $par                = 0;
        $gen                = 0;
        $esp                = 0;
        $sub                = 0;
        $sum_formulado      = 0;
        $sum_modificacion   = 0;
        $sum_actual         = 0;
        $sum_compromiso     = 0;
        $sum_causado        = 0;
        $sum_pagado         = 0;
        $total_formulado    = 0;
        $total_modificacion = 0;
        $total_actual       = 0;
        $total_compromiso   = 0;
        $total_causado      = 0;
        $total_pagado       = 0;

        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            $clasificador = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];

            //    SI CAMBIA DE CATEGORIA LA IMPRIMO
            if ($field["IdCategoria"] != $IdCategoria) {
                if ($sum_formulado == 0 and $sum_presupuestaria == 0) {
                    $IdCategoria = $field["IdCategoria"];
                    if ($mes == "01") {
                        echo "<table><tr><td colspan='10' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr></table>";
                    } else {
                        echo "<table><tr><td colspan='11' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr></table>";
                    }
                } else {
                    if ($mes == "01") {
                        echo "
							<table border='1'>
							<tr>
								<td style='$tr2'>&nbsp;</td>
								<td style='$tr2'>TOTALES CATEGORIA</td>
								<td style='$tr2' align='right'>" . number_format($sum_formulado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "; 2)</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_pagado), 2, ',', '.') . "</td>
							</tr>
							</table>";
                    } else {
                        echo "<table border='1'>
							<tr>
								<td style='$tr2'>&nbsp;</td>
								<td style='$tr2'>TOTALES CATEGORIA</td>
								<td style='$tr2' align='right'>" . number_format($sum_presupuestaria, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_financiera, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actualf - $sum_pagado), 2, ',', '.') . "</td>
							</tr>
							</table>";
                    }
                    $IdCategoria        = $field["IdCategoria"];
                    $sum_formulado      = 0;
                    $sum_aumento        = 0;
                    $sum_disminucion    = 0;
                    $sum_modificado     = 0;
                    $sum_actual         = 0;
                    $sum_compromiso     = 0;
                    $sum_causado        = 0;
                    $sum_pagado         = 0;
                    $sum_disponible     = 0;
                    $sum_modificacion   = 0;
                    $sum_presupuestaria = 0;
                    $sum_financiera     = 0;
                    $sum_actualf        = 0;
                    if ($mes == "01") {
                        echo "<table>
								<tr><td colspan='10' style='$tr2'>&nbsp;</td></tr>
								<tr><td colspan='10' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>
							</table>";
                    } else {
                        echo "<table>
								<tr><td colspan='11' style='$tr2'>&nbsp;</td></tr>
								<tr><td colspan='11' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>
							</table>";
                    }
                }
            }

            if ($field["Tipo"] == "partida") {
                $estilo = $tr3;

                $par++;
                if ($mes == "01") {
                    $sum_formulado += $field["Formulado"];
                    $total_formulado += $field["Formulado"];

                    $modificado = $aumentado_partidaI[$par] - $disminuido_partidaI[$par];

                    $sum_modificacion += $modificado;
                    $total_modificacion += $modificado;

                    $actual0 = $field["Formulado"] + $modificado;
                    $sum_actual += $actual0;
                    $total_actual += $actual0;

                    $sum_compromiso += $comprometido_partidaI[$par];
                    $total_compromiso += $comprometido_partidaI[$par];

                    $sum_causado += $causa_partidaI[$par];
                    $total_causado += $causa_partidaI[$par];

                    $sum_pagado += $pagado_partidaI[$par];
                    $total_pagado += $pagado_partidaI[$par];

                    $sum_disponible += ($actual0 - $comprometido);

                    $comprometidoI = $comprometido_partidaI[$par];

                    $causaI = $causa_partidaI[$par];

                    $pagadoI = $pagado_partidaI[$par];
                } else {
                    $sum_formulado += $field["Formulado"];
                    $total_formulado += $field["Formulado"];

                    $aumentado_partidaI_anterior[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'];
                    $disminuido_partidaI_anterior[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'];
                    $comprometido_partidaI_anterior[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'];
                    $causa_partidaI_anterior[$par] += $field['CausaI'];
                    $pagado_partidaI_anterior[$par] += $field['PagadoI'];

                    $presupuestaria_anterior = ($field["Formulado"] + $aumentado_partidaI_anterior[$par] - $disminuido_partidaI_anterior[$par]) - $comprometido_partidaI_anterior[$par];

                    $financiera_anterior = ($field["Formulado"] + $aumentado_partidaI_anterior[$par] - $disminuido_partidaI_anterior[$par]) - $pagado_partidaI_anterior[$par];

                    $sum_presupuestaria += $presupuestaria_anterior;
                    $total_presupuestaria += $presupuestaria_anterior;

                    $sum_financiera += $financiera_anterior;
                    $total_financiera += $financiera_anterior;

                    $modificado = $aumentado_partidaI[$par] - $disminuido_partidaI[$par];

                    $sum_modificacion += $modificado;
                    $total_modificacion += $modificado;

                    $actual0 = $presupuestaria_anterior + $modificado;
                    $actualf = $financiera_anterior + $modificado;

                    $sum_actual += $actual0;
                    $total_actual += $actual0;

                    $sum_actualf += $actualf;
                    $total_actualf += $actualf;

                    $sum_compromiso += $comprometido_partidaI[$par];
                    $total_compromiso += $comprometido_partidaI[$par];

                    $sum_causado += $causa_partidaI[$par];
                    $total_causado += $causa_partidaI[$par];

                    $sum_pagado += $pagado_partidaI[$par];
                    $total_pagado += $pagado_partidaI[$par];

                    $sum_disponible += ($actual0 - $comprometido);

                    $comprometidoI = $comprometido_partidaI[$par];

                    $causaI = $causa_partidaI[$par];

                    $pagadoI = $pagado_partidaI[$par];

                }

            } else if ($field["Tipo"] == "generica") {
                $estilo = $tr2;

                $gen++;
                if ($mes == "01") {
                    $modificado = $aumentado_genericaI[$gen] - $disminuido_genericaI[$gen];

                    $actual0 = $field["Formulado"] + $modificado;

                    $comprometidoI = $comprometido_genericaI[$gen];

                    $causaI = $causa_genericaI[$gen];

                    $pagadoI = $pagado_genericaI[$gen];

                } else {

                    $modificado = $aumentado_genericaI[$gen] - $disminuido_genericaI[$gen];

                    $actual0 = $field["Formulado"] + $modificado;

                    $comprometidoI = $comprometido_genericaI[$gen];

                    $causaI = $causa_genericaI[$gen];

                    $pagadoI = $pagado_genericaI[$gen];

                    $aumentado_genericaI_anterior[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'];
                    $disminuido_genericaI_anterior[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'];

                    $comprometido_genericaI_anterior[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'];

                    $causa_genericaI_anterior[$gen] += $field['CausaI'];

                    $pagado_genericaI_anterior[$gen] += $field['PagadoI'];

                    $presupuestaria_anterior = ($field["Formulado"] + $aumentado_genericaI_anterior[$gen] - $disminuido_genericaI_anterior[$gen]) - $comprometido_genericaI_anterior[$gen];

                    $financiera_anterior = ($field["Formulado"] + $aumentado_genericaI_anterior[$gen] - $disminuido_genericaI_anterior[$gen]) - $pagado_genericaI_anterior[$gen];

                    $actual0 = $presupuestaria_anterior + $modificado;

                }

            } else if ($field["Tipo"] == "especifica") {
                $estilo = $tr5;

                $esp++;
                if ($mes == "01") {

                    $modificado = $aumentado_especificaI[$esp] - $disminuido_especificaI[$esp];

                    $actual0 = $field["Formulado"] + $modificado;

                    $comprometidoI = $comprometido_especificaI[$esp];

                    $causaI = $causa_especificaI[$esp];

                    $pagadoI = $pagado_especificaI[$esp];

                } else {

                    $presupuestaria_anterior = ($field["Formulado"] + $aumentado_especificaI_anterior[$esp] - $disminuido_especificaI_anterior[$esp]) - $comprometido_especificaI_anterior[$esp];

                    $financiera_anterior = ($field["Formulado"] + $aumentado_especificaI_anterior[$esp] - $disminuido_especificaI_anterior[$esp]) - $pagado_especificaI_anterior[$esp];

                    $modificado = $aumentado_especificaI[$esp] - $disminuido_especificaI[$esp];

                    $comprometidoI = $comprometido_especificaI[$esp];

                    $causaI = $causa_especificaI[$esp];

                    $pagadoI = $pagado_especificaI[$esp];

                    $actual0 = $presupuestaria_anterior + $modificado;

                }
            } else if ($field["Tipo"] == "subespecifica") {
                $estilo = $tr5;

                $sub++;
                if ($mes == "01") {

                    $modificado = $aumentado_subespecificaI[$sub] - $disminuido_subespecificaI[$sub];

                    $actual0 = $field["Formulado"] + $modificado;

                    $comprometidoI = $comprometido_subespecificaI[$sub];

                    $causaI = $causa_subespecificaI[$sub];

                    $pagadoI = $pagado_subespecificaI[$sub];

                } else {

                    $presupuestaria_anterior = ($field["Formulado"] + $aumentado_subespecificaI_anterior[$sub] - $disminuido_subespecificaI_anterior[$sub]) - $comprometido_subespecificaI_anterior[$sub];

                    $financiera_anterior = ($field["Formulado"] + $aumentado_subespecificaI_anterior[$sub] - $disminuido_subespecificaI_anterior[$sub]) - $pagado_subespecificaI_anterior[$sub];

                    $comprometidoI = $comprometido_subespecificaI[$sub];

                    $causaI = $causa_subespecificaI[$sub];

                    $pagadoI = $pagado_subespecificaI[$sub];

                    $modificado = $aumentado_subespecificaI[$sub] - $disminuido_subespecificaI[$sub];
                    $actual0    = $presupuestaria_anterior + $modificado;

                }
            }

            $modificacion = number_format($modificado, 2, ',', '.');

            $actual = number_format($actual0, 2, ',', '.');

            $total_comprometidoI = number_format($comprometidoI, 2, ',', '.');

            $total_causaI = number_format($causaI, 2, ',', '.');

            $total_pagadoI = number_format($pagadoI, 2, ',', '.');

            if ($field['codordinal'] != "0000") {
                $descripcion = $field['codordinal'] . ' ' . $field['nomordinal'];
            } else {
                $descripcion = $field['NomPartida'];
            }

            if ($mes == "01") {
                $formulado = number_format($field["Formulado"], 2, ',', '.');

                echo "
				<table>
				<tr>
					<td style='" . $estilo . "'>" . $clasificador . "</td>
					<td style='" . $estilo . "'>" . utf8_decode($descripcion) . "</td>
					<td style='" . $estilo . "' align='right'>" . $formulado . "</td>
					<td style='" . $estilo . "' align='right'>" . $modificacion . "</td>
					<td style='" . $estilo . "' align='right'>" . $actual . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_comprometidoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_causaI . "</td>
					<td style='" . $estilo . "' align='right'>" . number_format(($actual0 - $comprometidoI), 2, ',', '.') . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_pagadoI . "</td>
					<td style='" . $estilo . "' align='right'>" . number_format(($actual0 - $pagadoI), 2, ',', '.') . "</td>
				</tr>
				</table>";
            } else {
                $formulado = number_format($field["Formulado"], 2, ',', '.');

                $total_presupuestaria_anterior = number_format($presupuestaria_anterior, 2, ',', '.');

                $total_financiera_anterior = number_format($financiera_anterior, 2, ',', '.');

                $modificacion = number_format($modificado, 2, ',', '.');

                $actual = number_format($actual0, 2, ',', '.');

                $total_comprometidoI = number_format($comprometidoI, 2, ',', '.');

                $total_causaI = number_format($causaI, 2, ',', '.');

                $total_pagadoI = number_format($pagadoI, 2, ',', '.');
                if (($financiera_anterior + $modificado) - $pagadoI <= 0) {
                    $disponible_financiero = number_format(0, 2, ',', '.');
                } else {
                    $disponible_financiero = number_format((($financiera_anterior + $modificado) - $pagadoI), 2, ',', '.');
                }
                echo "
				<table>
				<tr>
					<td style='" . $estilo . "'>" . $clasificador . "</td>
					<td style='" . $estilo . "'>" . utf8_decode($descripcion) . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_presupuestaria_anterior . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_financiera_anterior . "</td>
					<td style='" . $estilo . "' align='right'>" . $modificacion . "</td>
					<td style='" . $estilo . "' align='right'>" . $actual . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_comprometidoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_causaI . "</td>
					<td style='" . $estilo . "' align='right'>" . number_format(($actual0 - $comprometidoI), 2, ',', '.') . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_pagadoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_financiero . "</td>
				</tr>
				</table>";
            }

        }
        //IMPRIMO LOS TOTALES DE LA CATEGORIA
        if ($mes == "01") {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES CATEGORIA</td>
					<td style='$tr2' align='right'>" . number_format($sum_formulado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        } else {
            if ($sum_actualf - $sum_pagado <= 0) {
                $total_disponible_financiero = number_format(0, 2, ',', '.');
            } else {
                $total_disponible_financiero = number_format(($sum_actualf - $sum_pagado), 2, ',', '.');
            }
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES CATEGORIA</td>
					<td style='$tr2' align='right'>" . number_format($sum_presupuestaria, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_financiera, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actualf - $sum_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        }

        //SI EXISTEN TOTALES LOS IMPRIMOS
        if ($sum_formulado < $total_formulado) {
            if ($mes == "01") {
                echo "<table border='1'>
				<tr><td colspan='10' style='$tr2'>&nbsp;</td></tr>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($total_formulado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
            } else {
                echo "<table border='1'>
				<tr><td colspan='11' style='$tr2'>&nbsp;</td></tr>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($total_presupuestaria, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_financiera, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actualf - $total_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
            }
        }

        break;
    //FIN MENSUAL ONAPRE

    //TRIMESTRAL ONAPRE
    case "trimestral_onapre":
        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:12px; color:#000000; font-weight:bold; border-bottom-width: thin; border-bottom: solid #000000;";
        $tr3 = "background-color:#D8D8D8; font-size:12px; color:#000000; font-weight:bold; ";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:12px; color:#000000;";
        //---------------------------------------------
        //---------------------------------------------
        $sql = "SELECT
					denominacion As TipoPresupuesto,
					(SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento = '" . $financiamiento . "') AS FuenteFinanciamiento
				FROM tipo_presupuesto
				WHERE idtipo_presupuesto = '" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];

        $filtro = "";
        if ($anio_fiscal != "") {
            $filtro .= " (maestro_presupuesto.anio = '" . $anio_fiscal . "')";
        }

        if ($financiamiento != "") {
            $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '" . $financiamiento . "')";
        }

        if ($tipo_presupuesto != "") {
            $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
        }

        if ($idcategoria_programatica != "") {
            $filtro .= " AND (maestro_presupuesto.idcategoria_programatica = '" . $idcategoria_programatica . "')";
        }

        //---------------------------------------------
        $mes_inicial   = 1;
        $desde_inicial = $anio_fiscal . '-01-01';
        if ($mes == '01') {
            $idesde         = '01-01-' . $anio_fiscal;
            $ihasta         = '31-03-' . $anio_fiscal;
            $desde          = $anio_fiscal . '-01-01';
            $hasta          = $anio_fiscal . '-03-31';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 3;
            $trimestre_m    = 'TRIMESTRE I';
        }
        if ($mes == '02') {
            $idesde         = '01-04-' . $anio_fiscal;
            $ihasta         = '30-06-' . $anio_fiscal;
            $desde          = $anio_fiscal . '-04-01';
            $hasta          = $anio_fiscal . '-06-30';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 6;
            $mes_anterior   = 3;
            $trimestre_m    = 'TRIMESTRE II';

        }
        if ($mes == '03') {
            $idesde         = '01-07-' . $anio_fiscal;
            $ihasta         = '30-09-' . $anio_fiscal;
            $desde          = $anio_fiscal . '-07-01';
            $hasta          = $anio_fiscal . '-09-30';
            $hasta_anterior = $anio_fiscal . '-06-30';
            $mes_final      = 9;
            $mes_anterior   = 6;
            $trimestre_m    = 'TRIMESTRE III';
        }
        if ($mes == '04') {
            $idesde         = '01-10-' . $anio_fiscal;
            $ihasta         = '31-12-' . $anio_fiscal;
            $desde          = $anio_fiscal . '-10-01';
            $hasta          = $anio_fiscal . '-12-31';
            $hasta_anterior = $anio_fiscal . '-09-30';
            $mes_final      = 12;
            $mes_anterior   = 9;
            $trimestre_m    = 'TRIMESTRE IV';
        }

        //---------------------------------------------
        if ($mes == "01") {
            echo "
			<table>
				<tr><td colspan='10' style='$tr3'>EJECUCION TRIMESTRAL</td></tr>
				<tr><td colspan='10'>Desde: " . $idesde . " Hasta: " . $ihasta . "</td></tr>
				<tr><td colspan='10'>" . $trimestre_m . "</td></tr>
				<tr><td colspan='10'>&nbsp;</td></tr>
				<tr><td colspan='10'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
				<tr><td colspan='10'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
				<tr><td colspan='10'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
				<tr><td colspan='10'>&nbsp;</td></tr>
			</table>
			<table border='1'>
				<tr>
					<th width='100' style='$tr1'>PARTIDA</th>
					<th width='1000' style='$tr1'>DESCRIPCION</th>
					<th width='200' style='$tr1'>PRESUPUESTO INICIAL</th>
					<th width='200' style='$tr1'>MODIFICACION</th>
					<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
					<th width='200' style='$tr1'>COMPROMETIDO</th>
					<th width='200' style='$tr1'>CAUSADO</th>
					<th width='200' style='$tr1'>DISPONIBIBILIDAD PRESUPUESTARIA</th>
					<th width='200' style='$tr1'>PAGADO</th>
					<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
				</tr>
			</table>";
        } else {
            echo "
			<table>
				<tr><td colspan='11' style='$tr3'>EJECUCION TRIMESTRAL</td></tr>
				<tr><td colspan='11'>Desde: " . $idesde . " Hasta: " . $ihasta . "</td></tr>
				<tr><td colspan='11'>" . $trimestre_m . "</td></tr>
				<tr><td colspan='11'>&nbsp;</td></tr>
				<tr><td colspan='11'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
				<tr><td colspan='11'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
				<tr><td colspan='11'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
				<tr><td colspan='11'>&nbsp;</td></tr>
			</table>
			<table border='1'>
				<tr>
					<th width='100' style='$tr1'>PARTIDA</th>
					<th width='1000' style='$tr1'>DESCRIPCION</th>
					<th width='200' style='$tr1'>PRESUPUESTARIA ANTERIOR</th>
					<th width='200' style='$tr1'>FINANCIERA ANTERIOR</th>
					<th width='200' style='$tr1'>MODIFICACION</th>
					<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
					<th width='200' style='$tr1'>COMPROMETIDO</th>
					<th width='200' style='$tr1'>CAUSADO</th>
					<th width='200' style='$tr1'>DISPONIBILIDAD PRESUPUESTARIA</th>
					<th width='200' style='$tr1'>PAGADO</th>
					<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
				</tr>
			</table>";
        }

        if ($mes == 01) {

            //LLAMA A LA FUNCION PARA GENERAR SENTENCIA SQL DE UN PERIODO
            $sql = busca_rango_tiempo($desde, $hasta, $mes_inicial, $mes_final, $filtro, $anio_fiscal);

            $sql_suma   = $sql;
            $par        = 0;
            $gen        = 0;
            $esp        = 0;
            $sub        = 0;
            $query_suma = mysql_query($sql_suma) or die($sql_suma . mysql_error());
            while ($field = mysql_fetch_array($query_suma)) {
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['MCredito1'] - $field['MReceptora1'] - $field['MRectificacion1'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['MDisminucion1'] - $field['MCedentes1'] - $field['MRectificadora1'];
                }
                if ($field['Tipo'] == "partida") {
                    $par++;
                } elseif ($field['Tipo'] == "generica") {
                    $gen++;
                } elseif ($field['Tipo'] == "especifica") {
                    $esp++;

                    // DETERMINO SI ES ESPECIFICA PARA SUMARLA A LA ESPECIFICA
                    $aumentado_especificaI[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_genericaI[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_partidaI[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

                    $disminuido_especificaI[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_genericaI[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_partidaI[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

                    $comprometido_especificaI[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_partidaI[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

                    $causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_partidaI[$par] += $field['CausaI'] + $field['MRendicionCausado1'];

                    if ($field['PagadoI'] > $field['CausaI']) {
                        $pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                        $pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                        $pagado_partidaI[$par] += $field['CausaI'] + $field['MRendicionCausado1'];
                    } else {
                        $pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
                        $pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
                        $pagado_partidaI[$par] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    }

                } elseif ($field['Tipo'] == "subespecifica") {
                    $sub++;
                    // DETERMINO SI ES SUB ESPECIFICA PARA SUMARLA A LA ESPECIFICA
                    $aumentado_subespecificaI[$sub] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_especificaI[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_genericaI[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_partidaI[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

                    $disminuido_subespecificaI[$sub] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_especificaI[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_genericaI[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_partidaI[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

                    $comprometido_subespecificaI[$sub] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_especificaI[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_partidaI[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

                    $causa_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_partidaI[$par] += $field['CausaI'] + $field['MRendicionCausado1'];

                    if ($field['PagadoI'] > $field['CausaI']) {
                        $pagado_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
                        $pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                        $pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                        $pagado_partidaI[$par] += $field['CausaI'] + $field['MRendicionCausado1'];
                    } else {
                        $pagado_subespecificaI[$sub] += $field['PagadoI'] + $field['MRendicionPagados1'];
                        $pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
                        $pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
                        $pagado_partidaI[$par] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    }
                }
            }
        } else {
            //LLAMA A LA FUNCION PARA GENERAR SENTENCIA SQL DE UN PERIODO
            $sql = busca_rango_tiempo($desde_inicial, $hasta_anterior, $mes_inicial, $mes_anterior, $filtro, $anio_fiscal);

            $sql_suma   = $sql;
            $par        = 0;
            $gen        = 0;
            $esp        = 0;
            $sub        = 0;
            $query_suma = mysql_query($sql_suma) or die($sql_suma . mysql_error());
            while ($field = mysql_fetch_array($query_suma)) {
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['MCredito1'] - $field['MReceptora1'] - $field['MRectificacion1'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['MDisminucion1'] - $field['MCedentes1'] - $field['MRectificadora1'];
                }
                if ($field['Tipo'] == "partida") {
                    $par++;
                } elseif ($field['Tipo'] == "generica") {
                    $gen++;
                } elseif ($field['Tipo'] == "especifica") {
                    $esp++;
                    // DETERMINO SI ES SUB ESPECIFICA PARA SUMARLA A LA ESPECIFICA
                    $aumentado_especificaI_anterior[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_genericaI_anterior[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_partidaI_anterior[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

                    $disminuido_especificaI_anterior[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_genericaI_anterior[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_partidaI_anterior[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

                    $comprometido_especificaI_anterior[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_genericaI_anterior[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_partidaI_anterior[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

                    $causa_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_partidaI_anterior[$par] += $field['CausaI'] + $field['MRendicionCausado1'];

                    if ($field['PagadoI'] > $field['CausaI']) {
                        $pagado_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                        $pagado_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                        $pagado_partidaI_anterior[$par] += $field['CausaI'] + $field['MRendicionCausado1'];
                    } else {
                        $pagado_especificaI_anterior[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
                        $pagado_genericaI_anterior[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
                        $pagado_partidaI_anterior[$par] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    }

                } elseif ($field['Tipo'] == "subespecifica") {
                    $sub++;
                    // DETERMINO SI ES SUB ESPECIFICA PARA SUMARLA A LA ESPECIFICA
                    $aumentado_subespecificaI_anterior[$sub] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_especificaI_anterior[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_genericaI_anterior[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_partidaI_anterior[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

                    $disminuido_subespecificaI_anterior[$sub] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_especificaI_anterior[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_genericaI_anterior[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_partidaI_anterior[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

                    $comprometido_subespecificaI_anterior[$sub] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_especificaI_anterior[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_genericaI_anterior[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_partidaI_anterior[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

                    $causa_subespecificaI_anterior[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_partidaI_anterior[$par] += $field['CausaI'] + $field['MRendicionCausado1'];

                    if ($field['PagadoI'] > $field['CausaI']) {
                        $pagado_subespecificaI_anterior[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
                        $pagado_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                        $pagado_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                        $pagado_partidaI_anterior[$par] += $field['CausaI'] + $field['MRendicionCausado1'];
                    } else {
                        $pagado_subespecificaI_anterior[$sub] += $field['PagadoI'] + $field['MRendicionPagados1'];
                        $pagado_especificaI_anterior[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
                        $pagado_genericaI_anterior[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
                        $pagado_partidaI_anterior[$par] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    }
                }
            }

            //OBTENGO LOS TOTALES DEL MES SELECCIONADO
            //OBTENGO LOS TOTALES DEL TRIMESTRE SELECCIONADO
            //LLAMA A LA FUNCION PARA GENERAR SENTENCIA SQL DE UN PERIODO
            $sql = busca_rango_tiempo($desde, $hasta, $mes_anterior + 1, $mes_final, $filtro, $anio_fiscal);

            $sql_suma   = $sql;
            $par        = 0;
            $gen        = 0;
            $esp        = 0;
            $sub        = 0;
            $query_suma = mysql_query($sql_suma) or die($sql_suma . mysql_error());
            while ($field = mysql_fetch_array($query_suma)) {
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['MCredito1'] - $field['MReceptora1'] - $field['MRectificacion1'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['MDisminucion1'] - $field['MCedentes1'] - $field['MRectificadora1'];
                }
                if ($field['Tipo'] == "partida") {
                    $par++;
                } elseif ($field['Tipo'] == "generica") {
                    $gen++;
                } elseif ($field['Tipo'] == "especifica") {
                    $esp++;

                    // DETERMINO SI ES ESPECIFICA PARA SUMARLA A LA ESPECIFICA
                    $aumentado_especificaI[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_genericaI[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_partidaI[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

                    $disminuido_especificaI[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_genericaI[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_partidaI[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

                    $comprometido_especificaI[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_partidaI[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

                    $causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_partidaI[$par] += $field['CausaI'] + $field['MRendicionCausado1'];

                    if ($field['PagadoI'] > $field['CausaI']) {
                        $pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionCausado1'];
                        $pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionCausado1'];
                        $pagado_partidaI[$par] += $field['PagadoI'] + $field['MRendicionCausado1'];
                    } else {
                        $pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionPagados1'];
                        $pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionPagados1'];
                        $pagado_partidaI[$par] += $field['CausaI'] + $field['MRendicionPagados1'];
                    }

                } elseif ($field['Tipo'] == "subespecifica") {
                    $sub++;
                    // DETERMINO SI ES SUB ESPECIFICA PARA SUMARLA A LA ESPECIFICA
                    $aumentado_subespecificaI[$sub] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_especificaI[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_genericaI[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                    $aumentado_partidaI[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

                    $disminuido_subespecificaI[$sub] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_especificaI[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_genericaI[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                    $disminuido_partidaI[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

                    $comprometido_subespecificaI[$sub] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_especificaI[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                    $comprometido_partidaI[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

                    $causa_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $causa_partidaI[$par] += $field['CausaI'] + $field['MRendicionCausado1'];

                    if ($field['PagadoI'] > $field['CausaI']) {
                        $pagado_subespecificaI[$sub] += $field['PagadoI'] + $field['MRendicionCausado1'];
                        $pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionCausado1'];
                        $pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionCausado1'];
                        $pagado_partidaI[$par] += $field['PagadoI'] + $field['MRendicionCausado1'];
                    } else {
                        $pagado_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionPagados1'];
                        $pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionPagados1'];
                        $pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionPagados1'];
                        $pagado_partidaI[$par] += $field['CausaI'] + $field['MRendicionPagados1'];
                    }
                }
            }

        }

        $par                = 0;
        $gen                = 0;
        $esp                = 0;
        $sub                = 0;
        $sum_formulado      = 0;
        $sum_modificacion   = 0;
        $sum_actual         = 0;
        $sum_compromiso     = 0;
        $sum_causado        = 0;
        $sum_pagado         = 0;
        $total_formulado    = 0;
        $total_modificacion = 0;
        $total_actual       = 0;
        $total_compromiso   = 0;
        $total_causado      = 0;
        $total_pagado       = 0;
        $IdCategoria        = 0;
        $query              = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            $clasificador = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];

            //    SI CAMBIA DE CATEGORIA LA IMPRIMO
            if ($field["IdCategoria"] != $IdCategoria) {
                if ($IdCategoria == 0) {
                    if ($mes == "01") {
                        echo "<table>
								<tr><td colspan='10' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>
							</table>";
                    } else {
                        echo "<table>
								<tr><td colspan='11' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>
							</table>";
                    }
                } else {}
                if ($sum_formulado == 0 and $sum_presupuestaria == 0 and $IdCategoria != 0) {
                    $IdCategoria = $field["IdCategoria"];
                    if ($mes == "01") {
                        echo "
							<table border='1'>
							<tr>
								<td style='$tr2'>&nbsp;</td>
								<td style='$tr2'>TOTALES CATEGORIA</td>
								<td style='$tr2' align='right'>" . number_format($sum_formulado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_pagado), 2, ',', '.') . "</td>
							</tr>
							</table>";
                    } else {
                        echo "<table border='1'>
							<tr>
								<td style='$tr2'>&nbsp;</td>
								<td style='$tr2'>TOTALES CATEGORIA</td>
								<td style='$tr2' align='right'>" . number_format($sum_presupuestaria, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_financiera, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actualf - $sum_pagado), 2, ',', '.') . "</td>
							</tr>
							</table>";
                    }
                    $sum_formulado      = 0;
                    $sum_aumento        = 0;
                    $sum_disminucion    = 0;
                    $sum_modificado     = 0;
                    $sum_actual         = 0;
                    $sum_compromiso     = 0;
                    $sum_causado        = 0;
                    $sum_pagado         = 0;
                    $sum_disponible     = 0;
                    $sum_modificacion   = 0;
                    $sum_presupuestaria = 0;
                    $sum_financiera     = 0;
                    $sum_actualf        = 0;
                    $IdCategoria        = $field["IdCategoria"];
                    if ($mes == "01") {
                        echo "<table>
								<tr><td colspan='10' style='$tr2'>&nbsp;</td></tr>
								<tr><td colspan='10' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>
							</table>";
                    } else {
                        echo "<table>
								<tr><td colspan='11' style='$tr2'>&nbsp;</td></tr>
								<tr><td colspan='11' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>
							</table>";
                    }
                } else {
                    if ($mes == "01") {
                        echo "
							<table border='1'>
							<tr>
								<td style='$tr2'>&nbsp;</td>
								<td style='$tr2'>TOTALES CATEGORIA</td>
								<td style='$tr2' align='right'>" . number_format($sum_formulado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_pagado), 2, ',', '.') . "</td>
							</tr>
							</table>";
                    } else {
                        echo "<table border='1'>
							<tr>
								<td style='$tr2'>&nbsp;</td>
								<td style='$tr2'>TOTALES CATEGORIA</td>
								<td style='$tr2' align='right'>" . number_format($sum_presupuestaria, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_financiera, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actualf - $sum_pagado), 2, ',', '.') . "</td>
							</tr>
							</table>";
                    }
                    $sum_formulado      = 0;
                    $sum_aumento        = 0;
                    $sum_disminucion    = 0;
                    $sum_modificado     = 0;
                    $sum_actual         = 0;
                    $sum_compromiso     = 0;
                    $sum_causado        = 0;
                    $sum_pagado         = 0;
                    $sum_disponible     = 0;
                    $sum_modificacion   = 0;
                    $sum_presupuestaria = 0;
                    $sum_financiera     = 0;
                    $sum_actualf        = 0;
                    $IdCategoria        = $field["IdCategoria"];
                    if ($mes == "01") {
                        echo "<table>
								<tr><td colspan='10' style='$tr2'>&nbsp;</td></tr>
								<tr><td colspan='10' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>
							</table>";
                    } else {
                        echo "<table>
								<tr><td colspan='11' style='$tr2'>&nbsp;</td></tr>
								<tr><td colspan='11' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>
							</table>";
                    }
                }
            }

            if ($field["Tipo"] == "partida") {
                $par++;
                $estilo = $tr3;
                if ($mes == "01") {

                    $sum_formulado += $field["Formulado"];
                    $total_formulado += $field["Formulado"];

                    $modificado = $aumentado_partidaI[$par] - $disminuido_partidaI[$par];
                    $sum_modificacion += $modificado;
                    $total_modificacion += $modificado;

                    $actual0 = $field["Formulado"] + $modificado;
                    $sum_actual += $actual0;
                    $total_actual += $actual0;

                    $comprometidoI = $comprometido_partidaI[$par];
                    $sum_compromiso += $comprometido_partidaI[$par];
                    $total_compromiso += $comprometido_partidaI[$par];

                    $causaI = $causa_partidaI[$par];
                    $sum_causado += $causa_partidaI[$par];
                    $total_causado += $causa_partidaI[$par];

                    $pagadoI = $pagado_partidaI[$par];
                    $sum_pagado += $pagado_partidaI[$par];
                    $total_pagado += $pagado_partidaI[$par];

                } else {
                    $sum_formulado += $field["Formulado"];
                    $total_formulado += $field["Formulado"];
                    $aumentado_partidaI_anterior[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'];
                    $disminuido_partidaI_anterior[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'];
                    $comprometido_partidaI_anterior[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'];
                    $causa_partidaI_anterior[$par] += $field['CausaI'];
                    $pagado_partidaI_anterior[$par] += $field['PagadoI'];

                    $presupuestaria_anterior = ($field["Formulado"] + $aumentado_partidaI_anterior[$par] - $disminuido_partidaI_anterior[$par]) - $comprometido_partidaI_anterior[$par];

                    $financiera_anterior = ($field["Formulado"] + $aumentado_partidaI_anterior[$par] - $disminuido_partidaI_anterior[$par]) - $pagado_partidaI_anterior[$par];

                    $sum_presupuestaria += $presupuestaria_anterior;
                    $total_presupuestaria += $presupuestaria_anterior;

                    $sum_financiera += $financiera_anterior;
                    $total_financiera += $financiera_anterior;

                    $modificado = $aumentado_partidaI[$par] - $disminuido_partidaI[$par];

                    $sum_modificacion += $modificado;
                    $total_modificacion += $modificado;

                    $actual0 = $presupuestaria_anterior + $modificado;
                    $actualf = $financiera_anterior + $modificado;

                    $sum_actual += $actual0;
                    $total_actual += $actual0;

                    $sum_actualf += $actualf;
                    $total_actualf += $actualf;

                    $sum_compromiso += $comprometido_partidaI[$par];
                    $total_compromiso += $comprometido_partidaI[$par];

                    $sum_causado += $causa_partidaI[$par];
                    $total_causado += $causa_partidaI[$par];

                    $sum_pagado += $pagado_partidaI[$par];
                    $total_pagado += $pagado_partidaI[$par];

                    $sum_disponible += ($actual0 - $comprometido);

                    $comprometidoI = $comprometido_partidaI[$par];

                    $causaI = $causa_partidaI[$par];

                    $pagadoI = $pagado_partidaI[$par];

                }

            } else if ($field["Tipo"] == "generica") {
                $gen++;
                $estilo = $tr2;
                if ($mes == "01") {
                    $modificado    = $aumentado_genericaI[$gen] - $disminuido_genericaI[$gen];
                    $actual0       = $field["Formulado"] + $modificado;
                    $comprometidoI = $comprometido_genericaI[$gen];
                    $causaI        = $causa_genericaI[$gen];
                    $pagadoI       = $pagado_genericaI[$gen];

                } else {

                    $modificado = $aumentado_genericaI[$gen] - $disminuido_genericaI[$gen];

                    $actual0 = $field["Formulado"] + $modificado;

                    $comprometidoI = $comprometido_genericaI[$gen];

                    $causaI = $causa_genericaI[$gen];

                    $pagadoI = $pagado_genericaI[$gen];

                    $aumentado_genericaI_anterior[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'];
                    $disminuido_genericaI_anterior[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'];

                    $comprometido_genericaI_anterior[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'];

                    $causa_genericaI_anterior[$gen] += $field['CausaI'];

                    $pagado_genericaI_anterior[$gen] += $field['PagadoI'];

                    $presupuestaria_anterior = ($field["Formulado"] + $aumentado_genericaI_anterior[$gen] - $disminuido_genericaI_anterior[$gen]) - $comprometido_genericaI_anterior[$gen];

                    $financiera_anterior = ($field["Formulado"] + $aumentado_genericaI_anterior[$gen] - $disminuido_genericaI_anterior[$gen]) - $pagado_genericaI_anterior[$gen];

                    $actual0 = $presupuestaria_anterior + $modificado;

                }

            } else if ($field["Tipo"] == "especifica") {
                $estilo = $tr5;
                $esp++;
                if ($mes == "01") {

                    $modificado    = $aumentado_especificaI[$esp] - $disminuido_especificaI[$esp];
                    $actual0       = $field["Formulado"] + $modificado;
                    $comprometidoI = $comprometido_especificaI[$esp];
                    $causaI        = $causa_especificaI[$esp];
                    $pagadoI       = $pagado_especificaI[$esp];

                } else {

                    $presupuestaria_anterior = ($field["Formulado"] + $aumentado_especificaI_anterior[$esp] - $disminuido_especificaI_anterior[$esp]) - $comprometido_especificaI_anterior[$esp];

                    $financiera_anterior = ($field["Formulado"] + $aumentado_especificaI_anterior[$esp] - $disminuido_especificaI_anterior[$esp]) - $pagado_especificaI_anterior[$esp];

                    $modificado = $aumentado_especificaI[$esp] - $disminuido_especificaI[$esp];

                    $comprometidoI = $comprometido_especificaI[$esp];

                    $causaI = $causa_especificaI[$esp];

                    $pagadoI = $pagado_especificaI[$esp];

                    $actual0 = $presupuestaria_anterior + $modificado;

                }
            } else if ($field["Tipo"] == "subespecifica") {
                $estilo = $tr5;
                $sub++;
                if ($mes == "01") {

                    $modificado    = $aumentado_subespecificaI[$sub] - $disminuido_subespecificaI[$sub];
                    $actual0       = $field["Formulado"] + $modificado;
                    $comprometidoI = $comprometido_subespecificaI[$sub];
                    $causaI        = $causa_subespecificaI[$sub];
                    $pagadoI       = $pagado_subespecificaI[$sub];

                } else {

                    $presupuestaria_anterior = ($field["Formulado"] + $aumentado_subespecificaI_anterior[$sub] - $disminuido_subespecificaI_anterior[$sub]) - $comprometido_subespecificaI_anterior[$sub];

                    $financiera_anterior = ($field["Formulado"] + $aumentado_subespecificaI_anterior[$sub] - $disminuido_subespecificaI_anterior[$sub]) - $pagado_subespecificaI_anterior[$sub];

                    $comprometidoI = $comprometido_subespecificaI[$sub];

                    $causaI = $causa_subespecificaI[$sub];

                    $pagadoI = $pagado_subespecificaI[$sub];

                    $modificado = $aumentado_subespecificaI[$sub] - $disminuido_subespecificaI[$sub];
                    $actual0    = $presupuestaria_anterior + $modificado;

                }
            }

            if ($field['codordinal'] != "0000") {
                $descripcion = $field['codordinal'] . ' ' . $field['nomordinal'];
            } else {
                $descripcion = $field['NomPartida'];
            }

            $formulado           = number_format($field["Formulado"], 2, ',', '.');
            $modificacion        = number_format($modificado, 2, ',', '.');
            $actual              = number_format($actual0, 2, ',', '.');
            $total_comprometidoI = number_format($comprometidoI, 2, ',', '.');
            $total_causaI        = number_format($causaI, 2, ',', '.');
            $total_pagadoI       = number_format($pagadoI, 2, ',', '.');
            //IMPRIMO
            if ($mes == "01") {
                $disponible_compromiso = number_format(($actual0 - $comprometidoI), 2, ',', '.');
                $disponible_pagado     = number_format(($actual0 - $pagadoI), 2, ',', '.');

                echo "
				<table>
				<tr>
					<td style='" . $estilo . "'>" . $clasificador . "</td>
					<td style='" . $estilo . "'>" . utf8_decode($descripcion) . "</td>
					<td style='" . $estilo . "' align='right'>" . $formulado . "</td>
					<td style='" . $estilo . "' align='right'>" . $modificacion . "</td>
					<td style='" . $estilo . "' align='right'>" . $actual . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_comprometidoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_causaI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_compromiso . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_pagadoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_pagado . "</td>
				</tr>
				</table>";
            } else {
                $compromiso = $comprometidoI;
                $causado    = $causaI;
                $compromiso = number_format($compromiso, 2, ',', '.');
                $causado    = number_format($causado, 2, ',', '.');

                $total_disponible = $disponible_compromiso;
                if (($financiera_anterior + $modificado) - $pagadoI <= 0) {
                    $disponible_financiero = number_format(0, 2, ',', '.');
                } else {
                    $disponible_financiero = number_format((($financiera_anterior + $modificado) - $pagadoI), 2, ',', '.');
                }

                $disponible_pptaria_anterior    = number_format($presupuestaria_anterior, 2, ',', '.');
                $disponible_financiera_anterior = number_format($financiera_anterior, 2, ',', '.');
                $disponible_compromiso          = number_format(($actual0 - $comprometidoI), 2, ',', '.');

                echo "
				<table>
				<tr>
					<td style='" . $estilo . "'>" . $clasificador . "</td>
					<td style='" . $estilo . "'>" . utf8_decode($descripcion) . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_pptaria_anterior . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_financiera_anterior . "</td>
					<td style='" . $estilo . "' align='right'>" . $modificacion . "</td>
					<td style='" . $estilo . "' align='right'>" . $actual . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_comprometidoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_causaI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_compromiso . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_pagadoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_financiero . "</td>
				</tr>
				</table>";
            }

        }
        //IMPRIMO LOS TOTALES DE LA CATEGORIA
        if ($mes == "01") {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES CATEGORIA</td>
					<td style='$tr2' align='right'>" . number_format($sum_formulado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        } else {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES CATEGORIA</td>
					<td style='$tr2' align='right'>" . number_format($sum_presupuestaria, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_financiera, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actualf - $sum_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        }

        //SI EXISTEN TOTALES LOS IMPRIMOS
        if ($sum_formulado < $total_formulado) {
            if ($mes == "01") {
                echo "<table border='1'>
				<tr><td colspan='10' style='$tr2'>&nbsp;</td></tr>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($total_formulado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
            } else {
                echo "<table border='1'>
				<tr><td colspan='11' style='$tr2'>&nbsp;</td></tr>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($total_presupuestaria, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_financiera, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actualf - $total_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
            }
        }

        break;
    //FIN TRIMESTRAL ONAPRE

    //ANUAL ONAPRE
    case "anual_onapre":
        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:12px; color:#000000; font-weight:bold; border-bottom-width: thin; border-bottom: solid #000000;";
        $tr3 = "background-color:#D8D8D8; font-size:12px; color:#000000; font-weight:bold; ";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:12px; color:#000000;";
        //---------------------------------------------
        //---------------------------------------------
        $sql = "SELECT
					denominacion As TipoPresupuesto,
					(SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento = '" . $financiamiento . "') AS FuenteFinanciamiento
				FROM tipo_presupuesto
				WHERE idtipo_presupuesto = '" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];

        $filtro = "";
        if ($anio_fiscal != "") {
            $filtro .= " (maestro_presupuesto.anio = '" . $anio_fiscal . "')";
        }

        if ($financiamiento != "") {
            $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '" . $financiamiento . "')";
        }

        if ($tipo_presupuesto != "") {
            $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
        }

        if ($idcategoria_programatica != "") {
            $filtro .= " AND (maestro_presupuesto.idcategoria_programatica = '" . $idcategoria_programatica . "')";
        }

        //---------------------------------------------
        $desde       = $anio_fiscal . '-01-01';
        $hasta       = $anio_fiscal . '-12-31';
        $idesde      = '01-01-' . $anio_fiscal;
        $ihasta      = '31-12-' . $anio_fiscal;
        $mes_inicial = 1;
        $mes_final   = 12;
        //IMPRIMO EL ENCABEZADO
        echo "
			<table>
				<tr><td colspan='10' style='$tr3'>EJECUCION ANUAL</td></tr>
				<tr><td colspan='10'>Desde: " . $idesde . " Hasta: " . $ihasta . "</td></tr>
				<tr><td colspan='10'></td></tr>
				<tr><td colspan='10'>&nbsp;</td></tr>
				<tr><td colspan='10'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
				<tr><td colspan='10'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
				<tr><td colspan='10'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
				<tr><td colspan='10'>&nbsp;</td></tr>
			</table>
			<table border='1'>
				<tr>
					<th width='100' style='$tr1'>PARTIDA</th>
					<th width='1000' style='$tr1'>DESCRIPCION</th>
					<th width='200' style='$tr1'>PRESUPUESTO INICIAL</th>
					<th width='200' style='$tr1'>MODIFICACION</th>
					<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
					<th width='200' style='$tr1'>COMPROMETIDO</th>
					<th width='200' style='$tr1'>CAUSADO</th>
					<th width='200' style='$tr1'>DISPONIBIBILIDAD PRESUPUESTARIA</th>
					<th width='200' style='$tr1'>PAGADO</th>
					<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
				</tr>
			</table>";

        //LLAMA A LA FUNCION PARA GENERAR SENTENCIA SQL DE UN PERIODO
        $sql = busca_rango_tiempo($desde, $hasta, $mes_inicial, $mes_final, $filtro, $anio_fiscal);

        $sql_suma   = $sql;
        $par        = 0;
        $gen        = 0;
        $esp        = 0;
        $sub        = 0;
        $query_suma = mysql_query($sql_suma) or die($sql_suma . mysql_error());
        while ($field = mysql_fetch_array($query_suma)) {
            $aumento_rendicion     = 0;
            $disminucion_rendicion = 0;
            if ($field['MRendicionAumento1'] > 0) {
                $aumento_rendicion = $field['MRendicionAumento1'] - $field['MCredito1'] - $field['MReceptora1'] - $field['MRectificacion1'];
            }
            if ($field['MRendicionDisminucion1'] > 0) {
                $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['MDisminucion1'] - $field['MCedentes1'] - $field['MRectificadora1'];
            }
            if ($field['Tipo'] == "partida") {
                $par++;
            } elseif ($field['Tipo'] == "generica") {
                $gen++;
            } elseif ($field['Tipo'] == "especifica") {
                $esp++;

                // DETERMINO SI ES ESPECIFICA PARA SUMARLA A LA ESPECIFICA
                $aumentado_especificaI[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                $aumentado_genericaI[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                $aumentado_partidaI[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

                $disminuido_especificaI[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                $disminuido_genericaI[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                $disminuido_partidaI[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

                $comprometido_especificaI[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                $comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                $comprometido_partidaI[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

                $causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                $causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                $causa_partidaI[$par] += $field['CausaI'] + $field['MRendicionCausado1'];

                if ($field['PagadoI'] > $field['CausaI']) {
                    $pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_partidaI[$par] += $field['CausaI'] + $field['MRendicionCausado1'];
                } else {
                    $pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_partidaI[$par] += $field['PagadoI'] + $field['MRendicionPagados1'];
                }

            } elseif ($field['Tipo'] == "subespecifica") {
                $sub++;
                // DETERMINO SI ES SUB ESPECIFICA PARA SUMARLA A LA ESPECIFICA
                $aumentado_subespecificaI[$sub] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                $aumentado_especificaI[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                $aumentado_genericaI[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                $aumentado_partidaI[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

                $disminuido_subespecificaI[$sub] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                $disminuido_especificaI[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                $disminuido_genericaI[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                $disminuido_partidaI[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

                $comprometido_subespecificaI[$sub] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                $comprometido_especificaI[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                $comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];
                $comprometido_partidaI[$par] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

                $causa_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
                $causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                $causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                $causa_partidaI[$par] += $field['CausaI'] + $field['MRendicionCausado1'];

                if ($field['PagadoI'] > $field['CausaI']) {
                    $pagado_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_partidaI[$par] += $field['CausaI'] + $field['MRendicionCausado1'];
                } else {
                    $pagado_subespecificaI[$sub] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_partidaI[$par] += $field['PagadoI'] + $field['MRendicionPagados1'];
                }
            }
        }

        $par                = 0;
        $gen                = 0;
        $esp                = 0;
        $sub                = 0;
        $sum_formulado      = 0;
        $sum_modificacion   = 0;
        $sum_actual         = 0;
        $sum_compromiso     = 0;
        $sum_causado        = 0;
        $sum_pagado         = 0;
        $total_formulado    = 0;
        $total_modificacion = 0;
        $total_actual       = 0;
        $total_compromiso   = 0;
        $total_causado      = 0;
        $total_pagado       = 0;

        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            $clasificador = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];

            //    SI CAMBIA DE CATEGORIA LA IMPRIMO
            if ($field["IdCategoria"] != $IdCategoria) {
                if ($sum_formulado == 0 and $sum_actual == 0) {
                    $IdCategoria = $field["IdCategoria"];

                    echo "<table><tr><td colspan='10' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr></table>";

                } else {

                    echo "
							<table border='1'>
							<tr>
								<td style='$tr2'>&nbsp;</td>
								<td style='$tr2'>TOTALES</td>
								<td style='$tr2' align='right'>" . number_format($sum_formulado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_pagado), 2, ',', '.') . "</td>
							</tr>
							</table>";
                    $sum_formulado      = 0;
                    $sum_aumento        = 0;
                    $sum_disminucion    = 0;
                    $sum_modificado     = 0;
                    $sum_actual         = 0;
                    $sum_compromiso     = 0;
                    $sum_causado        = 0;
                    $sum_pagado         = 0;
                    $sum_disponible     = 0;
                    $sum_modificacion   = 0;
                    $sum_presupuestaria = 0;
                    $sum_financiera     = 0;
                    $sum_actualf        = 0;
                    $IdCategoria        = $field["IdCategoria"];

                    echo "<table>
								<tr><td colspan='10' >&nbsp;</td></tr>
								<tr><td colspan='10' >&nbsp;</td></tr>
								<tr><td colspan='10' style='$tr2'>" . $field["CodCategoria"] . " - " . $field["Unidad"] . "</td></tr>
							</table>";

                }
            }

            // EVALUO EL NIVEL PARA TOMAR LOS DATOS
            if ($field["Tipo"] == "partida") {
                $par++;
                $estilo = $tr3;

                $sum_formulado += $field["Formulado"];
                $total_formulado += $field["Formulado"];

                $modificado = $aumentado_partidaI[$par] - $disminuido_partidaI[$par];
                $sum_modificacion += $modificado;
                $total_modificacion += $modificado;

                $actual0 = $field["Formulado"] + $modificado;
                $sum_actual += $actual0;
                $total_actual += $actual0;

                $comprometidoI = $comprometido_partidaI[$par];
                $sum_compromiso += $comprometido_partidaI[$par];
                $total_compromiso += $comprometido_partidaI[$par];

                $causaI = $causa_partidaI[$par];
                $sum_causado += $causa_partidaI[$par];
                $total_causado += $causa_partidaI[$par];

                $pagadoI = $pagado_partidaI[$par];
                $sum_pagado += $pagado_partidaI[$par];
                $total_pagado += $pagado_partidaI[$par];

            } else if ($field["Tipo"] == "generica") {
                $gen++;
                $estilo        = $tr2;
                $modificado    = $aumentado_genericaI[$gen] - $disminuido_genericaI[$gen];
                $actual0       = $field["Formulado"] + $modificado;
                $comprometidoI = $comprometido_genericaI[$gen];
                $causaI        = $causa_genericaI[$gen];
                $pagadoI       = $pagado_genericaI[$gen];

            } else if ($field["Tipo"] == "especifica") {
                $estilo = $tr5;
                $esp++;
                $modificado    = $aumentado_especificaI[$esp] - $disminuido_especificaI[$esp];
                $actual0       = $field["Formulado"] + $modificado;
                $comprometidoI = $comprometido_especificaI[$esp];
                $causaI        = $causa_especificaI[$esp];
                $pagadoI       = $pagado_especificaI[$esp];

            } else if ($field["Tipo"] == "subespecifica") {
                $estilo = $tr5;
                $sub++;

                $modificado = $aumentado_subespecificaI[$sub] - $disminuido_subespecificaI[$sub];

                $actual0 = $field["Formulado"] + $modificado;

                $comprometidoI = $comprometido_subespecificaI[$sub];

                $causaI = $causa_subespecificaI[$sub];

                $pagadoI = $pagado_subespecificaI[$sub];
                /*
            $modificado = $aumentado_subespecificaI[$sub] - $disminuido_subespecificaI[$sub];
            $actual0 = $field["Formulado"] + $modificado;
            $comprometidoI = $field['CompraCompromisoI'] + $field['PagoCompromisoI'];
            $causaI = $field['CausaI'];
            $pagadoI = $field['PagadoI'];*/

            }

            if ($field['codordinal'] != "0000") {
                $descripcion = $field['codordinal'] . ' ' . $field['nomordinal'];
            } else {
                $descripcion = $field['NomPartida'];
            }

            $formulado             = number_format($field["Formulado"], 2, ',', '.');
            $modificacion          = number_format($modificado, 2, ',', '.');
            $actual                = number_format($actual0, 2, ',', '.');
            $total_comprometidoI   = number_format($comprometidoI, 2, ',', '.');
            $total_causaI          = number_format($causaI, 2, ',', '.');
            $disponible_compromiso = number_format(($actual0 - $comprometidoI), 2, ',', '.');
            $total_pagadoI         = number_format($pagadoI, 2, ',', '.');
            $disponible_pagado     = number_format(($actual0 - $pagadoI), 2, ',', '.');
            echo "
				<table>
				<tr>
					<td style='" . $estilo . "'>" . $clasificador . "</td>
					<td style='" . $estilo . "'>" . utf8_decode($descripcion) . "</td>
					<td style='" . $estilo . "' align='right'>" . $formulado . "</td>
					<td style='" . $estilo . "' align='right'>" . $modificacion . "</td>
					<td style='" . $estilo . "' align='right'>" . $actual . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_comprometidoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_causaI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_compromiso . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_pagadoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_pagado . "</td>
				</tr>
				</table>";

        }
        //IMPRIMO LOS TOTALES DE LA CATEGORIA

        echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($sum_formulado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";

        //SI EXISTEN TOTALES LOS IMPRIMOS
        if ($sum_formulado < $total_formulado) {

            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($total_formulado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";

        }

        break;
    //FIN ANUAL ONAPRE

    //    Consolidado por Sector ONAPRE...
    case "consolidado_sector_onapre":
        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:14px; color:#000000; font-weight:bold; border-bottom-width: thin; border-bottom: solid #000000;";
        $tr3 = "background-color:#D8D8D8; font-size:12px; color:#000000; font-weight:bold; ";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:14px; color:#000000;";
        //---------------------------------------------
        if ($trimestre == "00") {
            $trimestre_m = '';
        } elseif ($trimestre == "01") {
            $trimestre_m = 'TRIMESTRE I';
        } elseif ($trimestre == "02") {
            $trimestre_m = 'TRIMESTRE II';
        } elseif ($trimestre == "03") {
            $trimestre_m = 'TRIMESTRE III';
        } elseif ($trimestre == "04") {
            $trimestre_m = 'TRIMESTRE IV';
        }
        //---------------------------------------------
        $campos = explode("|", $checks);
        //---------------------------------------------
        $sql = "SELECT
					denominacion As TipoPresupuesto,
					(SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento = '" . $financiamiento . "') AS FuenteFinanciamiento
				FROM tipo_presupuesto
				WHERE idtipo_presupuesto = '" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------

        $filtro = "";
        if ($anio_fiscal != "") {
            $filtro .= " AND (mp.anio = '" . $anio_fiscal . "')";
        }

        if ($financiamiento != "") {
            $filtro .= " AND (mp.idfuente_financiamiento = '" . $financiamiento . "')";
        }

        if ($tipo_presupuesto != "") {
            $filtro .= " AND (mp.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
        }

        if ($idsector != "") {
            $filtro .= " AND (c.idsector = '" . $idsector . "')";
        }

        //---------------------------------------------
        $desde         = $anio_fiscal . '-01-01';
        $hasta         = $anio_fiscal . '-12-31';
        $idesde        = '01-01-' . $anio_fiscal;
        $ihasta        = '31-12-' . $anio_fiscal;
        $mes_inicial   = 1;
        $mes_final     = 12;
        $mes_inicial   = 1;
        $desde_inicial = $anio_fiscal . '-01-01';
        if ($trimestre == '00') {
            $idesde         = '01-01-' . $anio_fiscal;
            $ihasta         = '31-12-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-01-01';
            $fhasta         = $anio_fiscal . '-12-31';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 12;
        }
        if ($trimestre == '01') {
            $idesde         = '01-01-' . $anio_fiscal;
            $ihasta         = '31-03-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-01-01';
            $fhasta         = $anio_fiscal . '-03-31';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 3;
        }

        if ($trimestre == '02') {
            $idesde         = '01-04-' . $anio_fiscal;
            $ihasta         = '30-06-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-04-01';
            $fhasta         = $anio_fiscal . '-06-30';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 6;
            $mes_anterior   = 3;

        }
        if ($trimestre == '03') {
            $idesde         = '01-07-' . $anio_fiscal;
            $ihasta         = '30-09-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-07-01';
            $fhasta         = $anio_fiscal . '-09-30';
            $hasta_anterior = $anio_fiscal . '-06-30';
            $mes_final      = 9;
            $mes_anterior   = 6;
        }
        if ($trimestre == '04') {
            $idesde         = '01-10-' . $anio_fiscal;
            $ihasta         = '31-12-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-10-01';
            $fhasta         = $anio_fiscal . '-12-31';
            $hasta_anterior = $anio_fiscal . '-09-30';
            $mes_final      = 12;
            $mes_anterior   = 9;
        }

        //    CONSULTO TODO PARA SUMAR LAS PARTIDAS

        if ($trimestre == '00' or $trimestre == '01') {
            $sql = "SELECT
					cps.idclasificador_presupuestario,
					cps.denominacion AS NombrePartida,
					cps.partida AS Par,
					cps.generica AS Gen,
					cps.especifica AS Esp,
					cps.sub_especifica AS Sesp,
					cp.idcategoria_programatica,
					cp.codigo As CodCategoria,
					cp.idunidad_ejecutora,
					mp.idRegistro,
					mp.idordinal,
					c.idSector,
					c.codigo AS CodSector,
					c.denominacion AS Sector,

					(SELECT SUM(pca_2.monto_acreditar)
					 FROM partidas_credito_adicional pca_2
					 WHERE
						 pca_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pca_2.idcredito_adicional IN (SELECT ca_3.idcreditos_adicionales
														FROM creditos_adicionales ca_3
														WHERE
															ca_3.fecha_solicitud >= '$fdesde' AND
															ca_3.fecha_solicitud <= '$fhasta' AND
															ca_3.estado = 'procesado'))) AS Credito,

					(SELECT SUM(prt_2.monto_acreditar)
					 FROM partidas_receptoras_traslado prt_2
					 WHERE
						 prt_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prt_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$fdesde' AND
																  tp_3.fecha_solicitud <= '$fhasta' AND
																  tp_3.estado = 'procesado'))) AS Receptora,

					(SELECT SUM(prr_2.monto_acreditar)
					 FROM partidas_receptoras_rectificacion prr_2
					 WHERE
						 prr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
																FROM rectificacion_presupuesto rp_3
																WHERE
																  rp_3.fecha_solicitud >= '$fdesde' AND
																  rp_3.fecha_solicitud <= '$fhasta' AND
																  rp_3.estado = 'procesado'))) AS Rectificacion,

					(SELECT SUM(pdp_2.monto_debitar)
					 FROM partidas_disminucion_presupuesto pdp_2
					 WHERE
						 pdp_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pdp_2.iddisminucion_presupuesto IN (SELECT dp_3.iddisminucion_presupuesto
															  FROM disminucion_presupuesto dp_3
															  WHERE
																dp_3.fecha_solicitud >= '$fdesde' AND
																dp_3.fecha_solicitud <= '$fhasta' AND
																dp_3.estado = 'procesado'))) AS Disminucion,

					(SELECT SUM(pct_2.monto_debitar)
					 FROM partidas_cedentes_traslado pct_2
					 WHERE
						 pct_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pct_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$fdesde' AND
																  tp_3.fecha_solicitud <= '$fhasta' AND
																  tp_3.estado = 'procesado'))) AS Cedentes,

					(SELECT SUM(pr_2.monto_debitar)
					 FROM partidas_rectificadoras pr_2
					 WHERE
						 pr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
															   FROM rectificacion_presupuesto rp_3
															   WHERE
																 rp_3.fecha_solicitud >= '$fdesde' AND
																 rp_3.fecha_solicitud <= '$fhasta' AND
																 rp_3.estado = 'procesado'))) AS Rectificadora,


					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionAumento1,


					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionPagados1,

					(SELECT SUM(pocs_2.monto)
					 FROM partidas_orden_compra_servicio pocs_2
					 WHERE
						 pocs_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pocs_2.idorden_compra_servicio IN (SELECT ocs_3.idorden_compra_servicio
															 FROM orden_compra_servicio ocs_3
															 WHERE
															   ocs_3.fecha_orden >= '$fdesde' AND
															   ocs_3.fecha_orden <= '$fhasta' AND
															   (ocs_3.estado = 'procesado' OR
																ocs_3.estado = 'pagado' OR
																ocs_3.estado = 'conformado' OR
																ocs_3.estado = 'parcial')))) AS CompraCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM
												   orden_pago op_3
												   INNER JOIN tipos_documentos td_3 ON (op_3.tipo = td_3.idtipos_documentos AND td_3.compromete = 'si')
												 WHERE
												   op_3.fecha_orden >= '$fdesde' AND
												   op_3.fecha_orden <= '$fhasta' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS PagoCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM orden_pago op_3
												 WHERE
												   op_3.fecha_orden >= '$fdesde' AND
												   op_3.fecha_orden <= '$fhasta' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS Causado,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT pf_3.idorden_pago
												 FROM pagos_financieros pf_3
												 WHERE
												   pf_3.fecha_cheque >= '$fdesde' AND
												   pf_3.fecha_cheque <= '$fhasta' AND
												   (pf_3.estado = 'conciliado' OR
													pf_3.estado = 'transito' OR
													pf_3.estado = 'parcial')))) AS Pagado

				FROM
					maestro_presupuesto mp
					INNER JOIN clasificador_presupuestario cps ON (mp.idclasificador_presupuestario = cps.idclasificador_presupuestario)
					INNER JOIN ordinal o ON (mp.idordinal = o.idordinal)
					INNER JOIN categoria_programatica cp ON (mp.idcategoria_programatica = cp.idcategoria_programatica)
					INNER JOIN sector c On (cp.idsector = c.idsector)
				WHERE 1 $filtro
				GROUP BY CodSector, idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal
				ORDER BY CodSector, idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal";
            $query = mysql_query($sql) or die($sql . mysql_error());
            $rows  = mysql_num_rows($query);
            for ($i = 0; $i < $rows; $i++) {
                $field                 = mysql_fetch_array($query);
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['Disminucion'] - $field['Cedentes'] + $field['Rectificadora'];
                }
                $par = $field["CodSector"] . $field["Par"];
                $_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
                $_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
                $_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
                $_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
                $_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];
            }
        } else {
            //OBTENGO LOS MONTO DEL PERIODO ANTERIOR
            $sql = "SELECT
					cps.idclasificador_presupuestario,
					cps.denominacion AS NombrePartida,
					cps.partida AS Par,
					cps.generica AS Gen,
					cps.especifica AS Esp,
					cps.sub_especifica AS Sesp,
					cp.idcategoria_programatica,
					cp.codigo As CodCategoria,
					cp.idunidad_ejecutora,
					mp.idRegistro,
					mp.idordinal,
					c.idSector,
					c.codigo AS CodSector,
					c.denominacion AS Sector,

					(SELECT SUM(pca_2.monto_acreditar)
					 FROM partidas_credito_adicional pca_2
					 WHERE
						 pca_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pca_2.idcredito_adicional IN (SELECT ca_3.idcreditos_adicionales
														FROM creditos_adicionales ca_3
														WHERE
															ca_3.fecha_solicitud >= '$desde_inicial' AND
															ca_3.fecha_solicitud <= '$hasta_anterior' AND
															ca_3.estado = 'procesado'))) AS Credito,

					(SELECT SUM(prt_2.monto_acreditar)
					 FROM partidas_receptoras_traslado prt_2
					 WHERE
						 prt_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prt_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$desde_inicial' AND
																  tp_3.fecha_solicitud <= '$hasta_anterior' AND
																  tp_3.estado = 'procesado'))) AS Receptora,

					(SELECT SUM(prr_2.monto_acreditar)
					 FROM partidas_receptoras_rectificacion prr_2
					 WHERE
						 prr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
																FROM rectificacion_presupuesto rp_3
																WHERE
																  rp_3.fecha_solicitud >= '$desde_inicial' AND
																  rp_3.fecha_solicitud <= '$hasta_anterior' AND
																  rp_3.estado = 'procesado'))) AS Rectificacion,

					(SELECT SUM(pdp_2.monto_debitar)
					 FROM partidas_disminucion_presupuesto pdp_2
					 WHERE
						 pdp_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pdp_2.iddisminucion_presupuesto IN (SELECT dp_3.iddisminucion_presupuesto
															  FROM disminucion_presupuesto dp_3
															  WHERE
																dp_3.fecha_solicitud >= '$desde_inicial' AND
																dp_3.fecha_solicitud <= '$hasta_anterior' AND
																dp_3.estado = 'procesado'))) AS Disminucion,

					(SELECT SUM(pct_2.monto_debitar)
					 FROM partidas_cedentes_traslado pct_2
					 WHERE
						 pct_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pct_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$desde_inicial' AND
																  tp_3.fecha_solicitud <= '$hasta_anterior' AND
																  tp_3.estado = 'procesado'))) AS Cedentes,

					(SELECT SUM(pr_2.monto_debitar)
					 FROM partidas_rectificadoras pr_2
					 WHERE
						 pr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
															   FROM rectificacion_presupuesto rp_3
															   WHERE
																 rp_3.fecha_solicitud >= '$desde_inicial' AND
																 rp_3.fecha_solicitud <= '$hasta_anterior' AND
																 rp_3.estado = 'procesado'))) AS Rectificadora,


					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionAumento1,


					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionPagados1,

					(SELECT SUM(pocs_2.monto)
					 FROM partidas_orden_compra_servicio pocs_2
					 WHERE
						 pocs_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pocs_2.idorden_compra_servicio IN (SELECT ocs_3.idorden_compra_servicio
															 FROM orden_compra_servicio ocs_3
															 WHERE
															   ocs_3.fecha_orden >= '$desde_inicial' AND
															   ocs_3.fecha_orden <= '$hasta_anterior' AND
															   (ocs_3.estado = 'procesado' OR
																ocs_3.estado = 'pagado' OR
																ocs_3.estado = 'conformado' OR
																ocs_3.estado = 'parcial')))) AS CompraCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM
												   orden_pago op_3
												   INNER JOIN tipos_documentos td_3 ON
												   (op_3.tipo = td_3.idtipos_documentos AND td_3.compromete = 'si')
												 WHERE
												   op_3.fecha_orden >= '$desde_inicial' AND
												   op_3.fecha_orden <= '$hasta_anterior' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS PagoCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM orden_pago op_3
												 WHERE
												   op_3.fecha_orden >= '$desde_inicial' AND
												   op_3.fecha_orden <= '$hasta_anterior' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS Causado,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT pf_3.idorden_pago
												 FROM pagos_financieros pf_3
												 WHERE
												   pf_3.fecha_cheque >= '$desde_inicial' AND
												   pf_3.fecha_cheque <= '$hasta_anterior' AND
												   (pf_3.estado = 'conciliado' OR
													pf_3.estado = 'transito' OR
													pf_3.estado = 'parcial')))) AS Pagado

				FROM
					maestro_presupuesto mp
					INNER JOIN clasificador_presupuestario cps ON (mp.idclasificador_presupuestario = cps.idclasificador_presupuestario)
					INNER JOIN ordinal o ON (mp.idordinal = o.idordinal)
					INNER JOIN categoria_programatica cp ON (mp.idcategoria_programatica = cp.idcategoria_programatica)
					INNER JOIN sector c On (cp.idsector = c.idsector)
				WHERE 1 $filtro
				GROUP BY CodSector, idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal
				ORDER BY CodSector, idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal";
            $query = mysql_query($sql) or die($sql . mysql_error());
            $rows  = mysql_num_rows($query);
            for ($i = 0; $i < $rows; $i++) {
                $field                 = mysql_fetch_array($query);
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['Disminucion'] - $field['Cedentes'] + $field['Rectificadora'];
                }
                $par = $field["CodSector"] . $field["Par"];
                $_AUMENTO_ANTERIOR[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
                $_DISMINUCION_ANTERIOR[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
                $_COMPROMISO_ANTERIOR[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
                $_CAUSADO_ANTERIOR[$par] += $field['Causado'] + $field['MRendicionCausado1'];
                $_PAGADO_ANTERIOR[$par] += $field['Pagado'] + $field['MRendicionPagados1'];
            }

            //OBTRENGO LOS TOTALES DEL PERIODO A CALCULAR
            $sql = "SELECT
					cps.idclasificador_presupuestario,
					cps.denominacion AS NombrePartida,
					cps.partida AS Par,
					cps.generica AS Gen,
					cps.especifica AS Esp,
					cps.sub_especifica AS Sesp,
					cp.idcategoria_programatica,
					cp.codigo As CodCategoria,
					cp.idunidad_ejecutora,
					mp.idRegistro,
					mp.idordinal,
					c.idSector,
					c.codigo AS CodSector,
					c.denominacion AS Sector,

					(SELECT SUM(pca_2.monto_acreditar)
					 FROM partidas_credito_adicional pca_2
					 WHERE
						 pca_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pca_2.idcredito_adicional IN (SELECT ca_3.idcreditos_adicionales
														FROM creditos_adicionales ca_3
														WHERE
															ca_3.fecha_solicitud >= '$fdesde' AND
															ca_3.fecha_solicitud <= '$fhasta' AND
															ca_3.estado = 'procesado'))) AS Credito,

					(SELECT SUM(prt_2.monto_acreditar)
					 FROM partidas_receptoras_traslado prt_2
					 WHERE
						 prt_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prt_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$fdesde' AND
																  tp_3.fecha_solicitud <= '$fhasta' AND
																  tp_3.estado = 'procesado'))) AS Receptora,

					(SELECT SUM(prr_2.monto_acreditar)
					 FROM partidas_receptoras_rectificacion prr_2
					 WHERE
						 prr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
																FROM rectificacion_presupuesto rp_3
																WHERE
																  rp_3.fecha_solicitud >= '$fdesde' AND
																  rp_3.fecha_solicitud <= '$fhasta' AND
																  rp_3.estado = 'procesado'))) AS Rectificacion,

					(SELECT SUM(pdp_2.monto_debitar)
					 FROM partidas_disminucion_presupuesto pdp_2
					 WHERE
						 pdp_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pdp_2.iddisminucion_presupuesto IN (SELECT dp_3.iddisminucion_presupuesto
															  FROM disminucion_presupuesto dp_3
															  WHERE
																dp_3.fecha_solicitud >= '$fdesde' AND
																dp_3.fecha_solicitud <= '$fhasta' AND
																dp_3.estado = 'procesado'))) AS Disminucion,

					(SELECT SUM(pct_2.monto_debitar)
					 FROM partidas_cedentes_traslado pct_2
					 WHERE
						 pct_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pct_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$fdesde' AND
																  tp_3.fecha_solicitud <= '$fhasta' AND
																  tp_3.estado = 'procesado'))) AS Cedentes,

					(SELECT SUM(pr_2.monto_debitar)
					 FROM partidas_rectificadoras pr_2
					 WHERE
						 pr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
															   FROM rectificacion_presupuesto rp_3
															   WHERE
																 rp_3.fecha_solicitud >= '$fdesde' AND
																 rp_3.fecha_solicitud <= '$fhasta' AND
																 rp_3.estado = 'procesado'))) AS Rectificadora,


					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionAumento1,


					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionPagados1,

					(SELECT SUM(pocs_2.monto)
					 FROM partidas_orden_compra_servicio pocs_2
					 WHERE
						 pocs_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pocs_2.idorden_compra_servicio IN (SELECT ocs_3.idorden_compra_servicio
															 FROM orden_compra_servicio ocs_3
															 WHERE
															   ocs_3.fecha_orden >= '$fdesde' AND
															   ocs_3.fecha_orden <= '$fhasta' AND
															   (ocs_3.estado = 'procesado' OR
																ocs_3.estado = 'pagado' OR
																ocs_3.estado = 'conformado' OR
																ocs_3.estado = 'parcial')))) AS CompraCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM
												   orden_pago op_3
												   INNER JOIN tipos_documentos td_3 ON (op_3.tipo = td_3.idtipos_documentos AND td_3.compromete = 'si')
												 WHERE
												   op_3.fecha_orden >= '$fdesde' AND
												   op_3.fecha_orden <= '$fhasta' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS PagoCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM orden_pago op_3
												 WHERE
												   op_3.fecha_orden >= '$fdesde' AND
												   op_3.fecha_orden <= '$fhasta' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS Causado,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT pf_3.idorden_pago
												 FROM pagos_financieros pf_3
												 WHERE
												   pf_3.fecha_cheque >= '$fdesde' AND
												   pf_3.fecha_cheque <= '$fhasta' AND
												   (pf_3.estado = 'conciliado' OR
													pf_3.estado = 'transito' OR
													pf_3.estado = 'parcial')))) AS Pagado

				FROM
					maestro_presupuesto mp
					INNER JOIN clasificador_presupuestario cps ON (mp.idclasificador_presupuestario = cps.idclasificador_presupuestario)
					INNER JOIN ordinal o ON (mp.idordinal = o.idordinal)
					INNER JOIN categoria_programatica cp ON (mp.idcategoria_programatica = cp.idcategoria_programatica)
					INNER JOIN sector c On (cp.idsector = c.idsector)
				WHERE 1 $filtro
				GROUP BY CodSector, idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal
				ORDER BY CodSector, idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal";
            $query = mysql_query($sql) or die($sql . mysql_error());
            $rows  = mysql_num_rows($query);
            for ($i = 0; $i < $rows; $i++) {
                $field                 = mysql_fetch_array($query);
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['Disminucion'] - $field['Cedentes'] + $field['Rectificadora'];
                }
                $par = $field["CodSector"] . $field["Par"];
                $_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
                $_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
                $_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
                $_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
                $_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];
            }

        }

        //    CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO    O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
        $sql = "SELECT
					mp.idcategoria_programatica AS IdCategoria,
					mp.idclasificador_presupuestario AS idPartida,
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
					categoria_programatica.idsector AS IdSector,
					c.codigo AS CodSector,
					c.denominacion AS Sector,
					mp.idRegistro AS IdPresupuesto,
					SUM(mp.monto_original) AS Formulado,
					'0000' AS codordinal,
					'' AS nomordinal
				FROM
					maestro_presupuesto mp,
					categoria_programatica,
					unidad_ejecutora,
					clasificador_presupuestario,
					ordinal,
					sector c
				WHERE
					((mp.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					 (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					 (mp.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
					 (clasificador_presupuestario.sub_especifica='00') AND
					 (ordinal.codigo='0000' AND mp.idordinal = ordinal.idordinal) AND
					 (categoria_programatica.idsector = c.idSector) $filtro)
				GROUP BY (CodSector), (Par), (Gen), (Esp), (Sesp), (codordinal)
				ORDER BY CodSector, Par, Gen, Esp, Sesp, codordinal";
        $presupuestaria_anterior = 0;
        $financiera_anterior     = 0;
        $actualf                 = 0;
        $query                   = mysql_query($sql) or die($sql . mysql_error());
        $rows                    = mysql_num_rows($query);
        for ($i = 0; $i < $rows; $i++) {
            $field = mysql_fetch_array($query);
            //    SI CAMBIA DE SECTOR LA IMPRIMO
            if ($field["IdSector"] != $IdSector) {
                $IdSector = $field["IdSector"];
                $l        = 1;
                if ($i != 0) {

                    //    IMPRIMO LOS TOTALES
                    if ($trimestre == '00' or $trimestre == "01") {
                        echo "<table border='1'>
							<tr>
								<td style='$tr2'>&nbsp;</td>
								<td style='$tr2'>TOTALES</td>
								<td style='$tr2' align='right'>" . number_format($sum_formulado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_pagado), 2, ',', '.') . "</td>
							</tr>
							</table>";
                    } else {
                        echo "<table border='1'>
							<tr>
								<td style='$tr2'>&nbsp;</td>
								<td style='$tr2'>TOTALES</td>
								<td style='$tr2' align='right'>" . number_format($sum_presupuestaria_anterior, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_financiera_anterior, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actualf - $sum_pagado), 2, ',', '.') . "</td>
							</tr>
							</table>";
                    }

                    $sum_formulado               = 0;
                    $sum_aumento                 = 0;
                    $sum_disminucion             = 0;
                    $sum_modificado              = 0;
                    $sum_actual                  = 0;
                    $sum_compromiso              = 0;
                    $sum_causado                 = 0;
                    $sum_pagado                  = 0;
                    $sum_disponible              = 0;
                    $sum_modificacion            = 0;
                    $sum_presupuestaria_anterior = 0;
                    $sum_financiera_anterior     = 0;
                    $sum_actualf                 = 0;

                    //---------------------------------------------
                    if ($trimestre == '00' or $trimestre == "01") {
                        echo "
						<table>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>SECTOR: " . $field["CodSector"] . '  ' . $field["Sector"] . "</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
						</table>
						<table border='1'>
							<tr>
								<th width='100' style='$tr1'>PARTIDA</th>
								<th width='1000' style='$tr1'>DESCRIPCION</th>
								<th width='200' style='$tr1'>PRESUPUESTO INICIAL</th>
								<th width='200' style='$tr1'>MODIFICACION</th>
								<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
								<th width='200' style='$tr1'>COMPROMETIDO</th>
								<th width='200' style='$tr1'>CAUSADO</th>
								<th width='200' style='$tr1'>DISPONIBIBILIDAD PRESUPUESTARIA</th>
								<th width='200' style='$tr1'>PAGADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
							</tr>
						</table>";
                    } else {
                        echo "
						<table>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>SECTOR: " . $field["CodSector"] . '  ' . $field["Sector"] . "</td></tr>
							<tr><td colspan='11'>&nbsp;</td></tr>
						</table>
						<table border='1'>
							<tr>
								<th width='100' style='$tr1'>PARTIDA</th>
								<th width='1000' style='$tr1'>DESCRIPCION</th>
								<th width='200' style='$tr1'>PRESUPUESTARIA ANTERIOR</th>
								<th width='200' style='$tr1'>FINANCIERA ANTERIOR</th>
								<th width='200' style='$tr1'>MODIFICACION</th>
								<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
								<th width='200' style='$tr1'>COMPROMETIDO</th>
								<th width='200' style='$tr1'>CAUSADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD PRESUPUESTARIA</th>
								<th width='200' style='$tr1'>PAGADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
							</tr>
						</table>";
                    }

                } else {
                    //---------------------------------------------
                    if ($trimestre == '00' or $trimestre == "01") {
                        echo "
						<table>
							<tr><td colspan='10' style='$tr3'>CONSOLIDADO POR SECTOR</td></tr>
							<tr><td colspan='10'>Desde: " . $idesde . " Hasta: " . $ihasta . "</td></tr>
							<tr><td colspan='10'>" . $trimestre_m . "</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
							<tr><td colspan='10'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
							<tr><td colspan='10'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
							<tr><td colspan='10'>SECTOR: " . $field["CodSector"] . '  ' . $field["Sector"] . "</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
						</table>
						<table border='1'>
							<tr>
								<th width='100' style='$tr1'>PARTIDA</th>
								<th width='1000' style='$tr1'>DESCRIPCION</th>
								<th width='200' style='$tr1'>PRESUPUESTO INICIAL</th>
								<th width='200' style='$tr1'>MODIFICACION</th>
								<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
								<th width='200' style='$tr1'>COMPROMETIDO</th>
								<th width='200' style='$tr1'>CAUSADO</th>
								<th width='200' style='$tr1'>DISPONIBIBILIDAD PRESUPUESTARIA</th>
								<th width='200' style='$tr1'>PAGADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
							</tr>
						</table>";
                    } else {
                        echo "
						<table>
							<tr><td colspan='11' style='$tr3'>CONSOLIDADO POR SECTOR</td></tr>
							<tr><td colspan='11'>Desde: " . $idesde . " Hasta: " . $ihasta . "</td></tr>
							<tr><td colspan='11'>" . $trimestre_m . "</td></tr>
							<tr><td colspan='11'>&nbsp;</td></tr>
							<tr><td colspan='11'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
							<tr><td colspan='11'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
							<tr><td colspan='11'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
							<tr><td colspan='10'>SECTOR: " . $field["CodSector"] . '  ' . $field["Sector"] . "</td></tr>
							<tr><td colspan='11'>&nbsp;</td></tr>
						</table>
						<table border='1'>
							<tr>
								<th width='100' style='$tr1'>PARTIDA</th>
								<th width='1000' style='$tr1'>DESCRIPCION</th>
								<th width='200' style='$tr1'>PRESUPUESTARIA ANTERIOR</th>
								<th width='200' style='$tr1'>FINANCIERA ANTERIOR</th>
								<th width='200' style='$tr1'>MODIFICACION</th>
								<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
								<th width='200' style='$tr1'>COMPROMETIDO</th>
								<th width='200' style='$tr1'>CAUSADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD PRESUPUESTARIA</th>
								<th width='200' style='$tr1'>PAGADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
							</tr>
						</table>";
                    }
                }
            }
            $l++;
            $clasificador = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $par          = $field['CodSector'] . $field["Par"];
            $estilo       = $tr5;

            if ($trimestre == '00' or $trimestre == '01') {
                $formulado             = $field['Formulado'];
                $formulado_m           = number_format($field['Formulado'], 2, ',', '.');
                $modificacion          = $_AUMENTO[$par] - $_DISMINUCION[$par];
                $modificacion_m        = number_format($modificacion, 2, ',', '.');
                $actual                = $field['Formulado'] + $modificacion;
                $actual_m              = number_format($actual, 2, ',', '.');
                $total_comprometidoI   = number_format($_COMPROMISO[$par], 2, ',', '.');
                $total_causaI          = number_format($_CAUSADO[$par], 2, ',', '.');
                $disponible_compromiso = number_format(($actual - $_COMPROMISO[$par]), 2, ',', '.');
                $total_pagadoI         = number_format($_PAGADO[$par], 2, ',', '.');
                $disponible_pagado     = number_format(($actual - $_PAGADO[$par]), 2, ',', '.');

                echo "
				<table>
				<tr>
					<td style='" . $estilo . "'>" . $clasificador . "</td>
					<td style='" . $estilo . "'>" . utf8_decode($field["NomPartida"]) . "</td>
					<td style='" . $estilo . "' align='right'>" . $formulado_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $modificacion_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $actual_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_comprometidoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_causaI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_compromiso . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_pagadoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_pagado . "</td>
				</tr>
				</table>";

            } else {

                $formulado                 = $field['Formulado'];
                $formulado_m               = number_format($field['Formulado'], 2, ',', '.');
                $presupuestaria_anterior   = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_COMPROMISO_ANTERIOR[$par];
                $presupuestaria_anterior_m = number_format($presupuestaria_anterior, 2, ',', '.');
                $financiera_anterior       = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_PAGADO_ANTERIOR[$par];
                $financiera_anterior_m     = number_format($financiera_anterior, 2, ',', '.');
                $modificacion              = $_AUMENTO[$par] - $_DISMINUCION[$par];
                $modificacion_m            = number_format($modificacion, 2, ',', '.');
                $actual                    = $presupuestaria_anterior + $modificacion;
                $actual_m                  = number_format($actual, 2, ',', '.');
                $actualf                   = $financiera_anterior + $modificacion;
                $actualf_m                 = number_format($actualf, 2, ',', '.');
                $total_comprometidoI       = number_format($_COMPROMISO[$par], 2, ',', '.');
                $total_causaI              = number_format($_CAUSADO[$par], 2, ',', '.');
                $disponible_compromiso     = number_format(($actual - $_COMPROMISO[$par]), 2, ',', '.');
                $total_pagadoI             = number_format($_PAGADO[$par], 2, ',', '.');
                $disponible_pagado         = number_format(($actual - $_PAGADO[$par]), 2, ',', '.');

                echo "
				<table>
				<tr>
					<td style='" . $estilo . "'>" . $clasificador . "</td>
					<td style='" . $estilo . "'>" . utf8_decode($field["NomPartida"]) . "</td>
					<td style='" . $estilo . "' align='right'>" . $presupuestaria_anterior_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $financiera_anterior_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $modificacion_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $actual_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_comprometidoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_causaI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_compromiso . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_pagadoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_pagado . "</td>
				</tr>
				</table>";

            }

            $sum_formulado += $field['Formulado'];
            $sum_presupuestaria_anterior += $presupuestaria_anterior;
            $sum_financiera_anterior += $financiera_anterior;
            $sum_aumento += $_AUMENTO[$par];
            $sum_disminucion += $_DISMINUCION[$par];
            $sum_modificacion += $modificacion;
            $sum_actual += $actual;
            $sum_actualf += $actualf;
            $sum_compromiso += $_COMPROMISO[$par];
            $sum_causado += $_CAUSADO[$par];
            $sum_pagado += $_PAGADO[$par];

            $total_formulado += $field['Formulado'];
            $total_presupuestaria_anterior += $presupuestaria_anterior;
            $total_financiera_anterior += $financiera_anterior;
            $total_aumento += $_AUMENTO[$par];
            $total_disminucion += $_DISMINUCION[$par];
            $total_modificacion += $modificacion;
            $total_actual += $actual;
            $total_actualf += $actualf;
            $total_compromiso += $_COMPROMISO[$par];
            $total_causado += $_CAUSADO[$par];
            $total_pagado += $_PAGADO[$par];

        }

        //------------------------------------------------

        if ($trimestre == '00' or $trimestre == "01") {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($sum_formulado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        } else {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($sum_presupuestaria_anterior, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_financiera_anterior, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actualf - $sum_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        }

        if ($trimestre == '00' or $trimestre == "01") {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($total_formulado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        } else {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($total_presupuestaria_anterior, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_financiera_anterior, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actualf - $total_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        }
        break;

    //    Consolidado por Programa ONAPRE...
    case "consolidado_programa_onapre":
        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:14px; color:#000000; font-weight:bold; border-bottom-width: thin; border-bottom: solid #000000;";
        $tr3 = "background-color:#D8D8D8; font-size:12px; color:#000000; font-weight:bold; ";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:14px; color:#000000;";
        //---------------------------------------------
        /*if ($trimestre == "00"){
        $pdf->Cell(270, 6, '', 0, 1, 'C');
        }elseif ($trimestre == "01"){
        $trimestre_m = 'TRIMESTRE I';
        }elseif($trimestre == "02"){
        $trimestre_m = 'TRIMESTRE II';
        }elseif($trimestre == "03"){
        $trimestre_m = 'TRIMESTRE III';
        }elseif($trimestre == "04"){
        $trimestre_m = 'TRIMESTRE IV';
        }*/
        //---------------------------------------------
        $campos = explode("|", $checks);
        //---------------------------------------------
        $sql = "SELECT
					denominacion As TipoPresupuesto,
					(SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento = '" . $financiamiento . "') AS FuenteFinanciamiento
				FROM tipo_presupuesto
				WHERE idtipo_presupuesto = '" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------

        $filtro = "";
        if ($anio_fiscal != "") {
            $filtro .= " AND (mp.anio = '" . $anio_fiscal . "')";
        }

        if ($financiamiento != "") {
            $filtro .= " AND (mp.idfuente_financiamiento = '" . $financiamiento . "')";
        }

        if ($tipo_presupuesto != "") {
            $filtro .= " AND (mp.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
        }

        $filtro .= " AND (c.idsector = '" . $idsector . "')";
        if ($idprograma != "") {
            $filtro .= " AND (p.idprograma = '" . $idprograma . "')";
        }

        //---------------------------------------------
        $mes_inicial   = 1;
        $desde_inicial = $anio_fiscal . '-01-01';
        if ($trimestre == '00') {
            $idesde         = '01-01-' . $anio_fiscal;
            $ihasta         = '31-12-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-01-01';
            $fhasta         = $anio_fiscal . '-12-31';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 12;
        }
        if ($trimestre == '01') {
            $idesde         = '01-01-' . $anio_fiscal;
            $ihasta         = '31-03-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-01-01';
            $fhasta         = $anio_fiscal . '-03-31';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 3;
        }

        if ($trimestre == '02') {
            $idesde         = '01-04-' . $anio_fiscal;
            $ihasta         = '30-06-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-04-01';
            $fhasta         = $anio_fiscal . '-06-30';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 6;
            $mes_anterior   = 3;

        }
        if ($trimestre == '03') {
            $idesde         = '01-07-' . $anio_fiscal;
            $ihasta         = '30-09-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-07-01';
            $fhasta         = $anio_fiscal . '-09-30';
            $hasta_anterior = $anio_fiscal . '-06-30';
            $mes_final      = 9;
            $mes_anterior   = 6;
        }
        if ($trimestre == '04') {
            $idesde         = '01-10-' . $anio_fiscal;
            $ihasta         = '31-12-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-10-01';
            $fhasta         = $anio_fiscal . '-12-31';
            $hasta_anterior = $anio_fiscal . '-09-30';
            $mes_final      = 12;
            $mes_anterior   = 9;
        }

        //    CONSULTO TODO PARA SUMAR LAS PARTIDAS

        if ($trimestre == '00' or $trimestre == '01') {
            $sql = "SELECT
					cps.idclasificador_presupuestario,
					cps.denominacion AS NombrePartida,
					cps.partida AS Par,
					cps.generica AS Gen,
					cps.especifica AS Esp,
					cps.sub_especifica AS Sesp,
					cp.idcategoria_programatica,
					cp.codigo As CodCategoria,
					cp.idunidad_ejecutora,
					mp.idRegistro,
					mp.idordinal,
					c.idSector,
					c.codigo AS CodSector,
					c.denominacion AS Sector,
					p.idprograma,
					p.codigo AS CodPrograma,
					p.denominacion AS Programa,

					(SELECT SUM(pca_2.monto_acreditar)
					 FROM partidas_credito_adicional pca_2
					 WHERE
						 pca_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pca_2.idcredito_adicional IN (SELECT ca_3.idcreditos_adicionales
														FROM creditos_adicionales ca_3
														WHERE
															ca_3.fecha_solicitud >= '$fdesde' AND
															ca_3.fecha_solicitud <= '$fhasta' AND
															ca_3.estado = 'procesado'))) AS Credito,

					(SELECT SUM(prt_2.monto_acreditar)
					 FROM partidas_receptoras_traslado prt_2
					 WHERE
						 prt_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prt_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$fdesde' AND
																  tp_3.fecha_solicitud <= '$fhasta' AND
																  tp_3.estado = 'procesado'))) AS Receptora,

					(SELECT SUM(prr_2.monto_acreditar)
					 FROM partidas_receptoras_rectificacion prr_2
					 WHERE
						 prr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
																FROM rectificacion_presupuesto rp_3
																WHERE
																  rp_3.fecha_solicitud >= '$fdesde' AND
																  rp_3.fecha_solicitud <= '$fhasta' AND
																  rp_3.estado = 'procesado'))) AS Rectificacion,

					(SELECT SUM(pdp_2.monto_debitar)
					 FROM partidas_disminucion_presupuesto pdp_2
					 WHERE
						 pdp_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pdp_2.iddisminucion_presupuesto IN (SELECT dp_3.iddisminucion_presupuesto
															  FROM disminucion_presupuesto dp_3
															  WHERE
																dp_3.fecha_solicitud >= '$fdesde' AND
																dp_3.fecha_solicitud <= '$fhasta' AND
																dp_3.estado = 'procesado'))) AS Disminucion,

					(SELECT SUM(pct_2.monto_debitar)
					 FROM partidas_cedentes_traslado pct_2
					 WHERE
						 pct_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pct_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$fdesde' AND
																  tp_3.fecha_solicitud <= '$fhasta' AND
																  tp_3.estado = 'procesado'))) AS Cedentes,

					(SELECT SUM(pr_2.monto_debitar)
					 FROM partidas_rectificadoras pr_2
					 WHERE
						 pr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
															   FROM rectificacion_presupuesto rp_3
															   WHERE
																 rp_3.fecha_solicitud >= '$fdesde' AND
																 rp_3.fecha_solicitud <= '$fhasta' AND
																 rp_3.estado = 'procesado'))) AS Rectificadora,


					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionAumento1,


					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionPagados1,

					(SELECT SUM(pocs_2.monto)
					 FROM partidas_orden_compra_servicio pocs_2
					 WHERE
						 pocs_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pocs_2.idorden_compra_servicio IN (SELECT ocs_3.idorden_compra_servicio
															 FROM orden_compra_servicio ocs_3
															 WHERE
															   ocs_3.fecha_orden >= '$fdesde' AND
															   ocs_3.fecha_orden <= '$fhasta' AND
															   (ocs_3.estado = 'procesado' OR
																ocs_3.estado = 'pagado' OR
																ocs_3.estado = 'conformado' OR
																ocs_3.estado = 'parcial')))) AS CompraCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM
												   orden_pago op_3
												   INNER JOIN tipos_documentos td_3 ON (op_3.tipo = td_3.idtipos_documentos AND td_3.compromete = 'si')
												 WHERE
												   op_3.fecha_orden >= '$fdesde' AND
												   op_3.fecha_orden <= '$fhasta' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS PagoCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM orden_pago op_3
												 WHERE
												   op_3.fecha_orden >= '$fdesde' AND
												   op_3.fecha_orden <= '$fhasta' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS Causado,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT pf_3.idorden_pago
												 FROM pagos_financieros pf_3
												 WHERE
												   pf_3.fecha_cheque >= '$fdesde' AND
												   pf_3.fecha_cheque <= '$fhasta' AND
												   (pf_3.estado = 'conciliado' OR
													pf_3.estado = 'transito' OR
													pf_3.estado = 'parcial')))) AS Pagado

				FROM
					maestro_presupuesto mp
					INNER JOIN clasificador_presupuestario cps ON (mp.idclasificador_presupuestario = cps.idclasificador_presupuestario)
					INNER JOIN ordinal o ON (mp.idordinal = o.idordinal)
					INNER JOIN categoria_programatica cp ON (mp.idcategoria_programatica = cp.idcategoria_programatica)
					INNER JOIN sector c On (cp.idsector = c.idsector)
					INNER JOIN programa p On (cp.idprograma = p.idprograma)
				WHERE 1 $filtro
				GROUP BY CodSector, CodPrograma, idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal
				ORDER BY CodSector, CodPrograma,  idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal";
            $query = mysql_query($sql) or die($sql . mysql_error());
            $rows  = mysql_num_rows($query);
            for ($i = 0; $i < $rows; $i++) {
                $field                 = mysql_fetch_array($query);
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['Disminucion'] - $field['Cedentes'] + $field['Rectificadora'];
                }
                $par = $field["CodPrograma"] . $field["Par"];
                $_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
                $_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
                $_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
                $_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
                $_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];
            }
        } else {
            //OBTENGO LOS MONTO DEL PERIODO ANTERIOR
            $sql = "SELECT
					cps.idclasificador_presupuestario,
					cps.denominacion AS NombrePartida,
					cps.partida AS Par,
					cps.generica AS Gen,
					cps.especifica AS Esp,
					cps.sub_especifica AS Sesp,
					cp.idcategoria_programatica,
					cp.codigo As CodCategoria,
					cp.idunidad_ejecutora,
					mp.idRegistro,
					mp.idordinal,
					c.idSector,
					c.codigo AS CodSector,
					c.denominacion AS Sector,
					p.idprograma,
					p.codigo AS CodPrograma,
					p.denominacion AS Programa,

					(SELECT SUM(pca_2.monto_acreditar)
					 FROM partidas_credito_adicional pca_2
					 WHERE
						 pca_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pca_2.idcredito_adicional IN (SELECT ca_3.idcreditos_adicionales
														FROM creditos_adicionales ca_3
														WHERE
															ca_3.fecha_solicitud >= '$desde_inicial' AND
															ca_3.fecha_solicitud <= '$hasta_anterior' AND
															ca_3.estado = 'procesado'))) AS Credito,

					(SELECT SUM(prt_2.monto_acreditar)
					 FROM partidas_receptoras_traslado prt_2
					 WHERE
						 prt_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prt_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$desde_inicial' AND
																  tp_3.fecha_solicitud <= '$hasta_anterior' AND
																  tp_3.estado = 'procesado'))) AS Receptora,

					(SELECT SUM(prr_2.monto_acreditar)
					 FROM partidas_receptoras_rectificacion prr_2
					 WHERE
						 prr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
																FROM rectificacion_presupuesto rp_3
																WHERE
																  rp_3.fecha_solicitud >= '$desde_inicial' AND
																  rp_3.fecha_solicitud <= '$hasta_anterior' AND
																  rp_3.estado = 'procesado'))) AS Rectificacion,

					(SELECT SUM(pdp_2.monto_debitar)
					 FROM partidas_disminucion_presupuesto pdp_2
					 WHERE
						 pdp_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pdp_2.iddisminucion_presupuesto IN (SELECT dp_3.iddisminucion_presupuesto
															  FROM disminucion_presupuesto dp_3
															  WHERE
																dp_3.fecha_solicitud >= '$desde_inicial' AND
																dp_3.fecha_solicitud <= '$hasta_anterior' AND
																dp_3.estado = 'procesado'))) AS Disminucion,

					(SELECT SUM(pct_2.monto_debitar)
					 FROM partidas_cedentes_traslado pct_2
					 WHERE
						 pct_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pct_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$desde_inicial' AND
																  tp_3.fecha_solicitud <= '$hasta_anterior' AND
																  tp_3.estado = 'procesado'))) AS Cedentes,

					(SELECT SUM(pr_2.monto_debitar)
					 FROM partidas_rectificadoras pr_2
					 WHERE
						 pr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
															   FROM rectificacion_presupuesto rp_3
															   WHERE
																 rp_3.fecha_solicitud >= '$desde_inicial' AND
																 rp_3.fecha_solicitud <= '$hasta_anterior' AND
																 rp_3.estado = 'procesado'))) AS Rectificadora,


					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionAumento1,


					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionPagados1,

					(SELECT SUM(pocs_2.monto)
					 FROM partidas_orden_compra_servicio pocs_2
					 WHERE
						 pocs_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pocs_2.idorden_compra_servicio IN (SELECT ocs_3.idorden_compra_servicio
															 FROM orden_compra_servicio ocs_3
															 WHERE
															   ocs_3.fecha_orden >= '$desde_inicial' AND
															   ocs_3.fecha_orden <= '$hasta_anterior' AND
															   (ocs_3.estado = 'procesado' OR
																ocs_3.estado = 'pagado' OR
																ocs_3.estado = 'conformado' OR
																ocs_3.estado = 'parcial')))) AS CompraCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM
												   orden_pago op_3
												   INNER JOIN tipos_documentos td_3 ON
												   (op_3.tipo = td_3.idtipos_documentos AND td_3.compromete = 'si')
												 WHERE
												   op_3.fecha_orden >= '$desde_inicial' AND
												   op_3.fecha_orden <= '$hasta_anterior' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS PagoCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM orden_pago op_3
												 WHERE
												   op_3.fecha_orden >= '$desde_inicial' AND
												   op_3.fecha_orden <= '$hasta_anterior' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS Causado,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT pf_3.idorden_pago
												 FROM pagos_financieros pf_3
												 WHERE
												   pf_3.fecha_cheque >= '$desde_inicial' AND
												   pf_3.fecha_cheque <= '$hasta_anterior' AND
												   (pf_3.estado = 'conciliado' OR
													pf_3.estado = 'transito' OR
													pf_3.estado = 'parcial')))) AS Pagado

				FROM
					maestro_presupuesto mp
					INNER JOIN clasificador_presupuestario cps ON (mp.idclasificador_presupuestario = cps.idclasificador_presupuestario)
					INNER JOIN ordinal o ON (mp.idordinal = o.idordinal)
					INNER JOIN categoria_programatica cp ON (mp.idcategoria_programatica = cp.idcategoria_programatica)
					INNER JOIN sector c On (cp.idsector = c.idsector)
					INNER JOIN programa p On (cp.idprograma = p.idprograma)
				WHERE 1 $filtro
				GROUP BY CodSector, CodPrograma, idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal
				ORDER BY CodSector, CodPrograma,  idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal";
            $query = mysql_query($sql) or die($sql . mysql_error());
            $rows  = mysql_num_rows($query);
            for ($i = 0; $i < $rows; $i++) {
                $field                 = mysql_fetch_array($query);
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['Disminucion'] - $field['Cedentes'] + $field['Rectificadora'];
                }
                $par = $field["CodPrograma"] . $field["Par"];
                $_AUMENTO_ANTERIOR[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
                $_DISMINUCION_ANTERIOR[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
                $_COMPROMISO_ANTERIOR[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
                $_CAUSADO_ANTERIOR[$par] += $field['Causado'] + $field['MRendicionCausado1'];
                $_PAGADO_ANTERIOR[$par] += $field['Pagado'] + $field['MRendicionPagados1'];
            }

            //OBTRENGO LOS TOTALES DEL PERIODO A CALCULAR
            $sql = "SELECT
					cps.idclasificador_presupuestario,
					cps.denominacion AS NombrePartida,
					cps.partida AS Par,
					cps.generica AS Gen,
					cps.especifica AS Esp,
					cps.sub_especifica AS Sesp,
					cp.idcategoria_programatica,
					cp.codigo As CodCategoria,
					cp.idunidad_ejecutora,
					mp.idRegistro,
					mp.idordinal,
					c.idSector,
					c.codigo AS CodSector,
					c.denominacion AS Sector,
					p.idprograma,
					p.codigo AS CodPrograma,
					p.denominacion AS Programa,

					(SELECT SUM(pca_2.monto_acreditar)
					 FROM partidas_credito_adicional pca_2
					 WHERE
						 pca_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pca_2.idcredito_adicional IN (SELECT ca_3.idcreditos_adicionales
														FROM creditos_adicionales ca_3
														WHERE
															ca_3.fecha_solicitud >= '$fdesde' AND
															ca_3.fecha_solicitud <= '$fhasta' AND
															ca_3.estado = 'procesado'))) AS Credito,

					(SELECT SUM(prt_2.monto_acreditar)
					 FROM partidas_receptoras_traslado prt_2
					 WHERE
						 prt_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prt_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$fdesde' AND
																  tp_3.fecha_solicitud <= '$fhasta' AND
																  tp_3.estado = 'procesado'))) AS Receptora,

					(SELECT SUM(prr_2.monto_acreditar)
					 FROM partidas_receptoras_rectificacion prr_2
					 WHERE
						 prr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
																FROM rectificacion_presupuesto rp_3
																WHERE
																  rp_3.fecha_solicitud >= '$fdesde' AND
																  rp_3.fecha_solicitud <= '$fhasta' AND
																  rp_3.estado = 'procesado'))) AS Rectificacion,

					(SELECT SUM(pdp_2.monto_debitar)
					 FROM partidas_disminucion_presupuesto pdp_2
					 WHERE
						 pdp_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pdp_2.iddisminucion_presupuesto IN (SELECT dp_3.iddisminucion_presupuesto
															  FROM disminucion_presupuesto dp_3
															  WHERE
																dp_3.fecha_solicitud >= '$fdesde' AND
																dp_3.fecha_solicitud <= '$fhasta' AND
																dp_3.estado = 'procesado'))) AS Disminucion,

					(SELECT SUM(pct_2.monto_debitar)
					 FROM partidas_cedentes_traslado pct_2
					 WHERE
						 pct_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pct_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$fdesde' AND
																  tp_3.fecha_solicitud <= '$fhasta' AND
																  tp_3.estado = 'procesado'))) AS Cedentes,

					(SELECT SUM(pr_2.monto_debitar)
					 FROM partidas_rectificadoras pr_2
					 WHERE
						 pr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
															   FROM rectificacion_presupuesto rp_3
															   WHERE
																 rp_3.fecha_solicitud >= '$fdesde' AND
																 rp_3.fecha_solicitud <= '$fhasta' AND
																 rp_3.estado = 'procesado'))) AS Rectificadora,


					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionAumento1,


					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionPagados1,

					(SELECT SUM(pocs_2.monto)
					 FROM partidas_orden_compra_servicio pocs_2
					 WHERE
						 pocs_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pocs_2.idorden_compra_servicio IN (SELECT ocs_3.idorden_compra_servicio
															 FROM orden_compra_servicio ocs_3
															 WHERE
															   ocs_3.fecha_orden >= '$fdesde' AND
															   ocs_3.fecha_orden <= '$fhasta' AND
															   (ocs_3.estado = 'procesado' OR
																ocs_3.estado = 'pagado' OR
																ocs_3.estado = 'conformado' OR
																ocs_3.estado = 'parcial')))) AS CompraCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM
												   orden_pago op_3
												   INNER JOIN tipos_documentos td_3 ON (op_3.tipo = td_3.idtipos_documentos AND td_3.compromete = 'si')
												 WHERE
												   op_3.fecha_orden >= '$fdesde' AND
												   op_3.fecha_orden <= '$fhasta' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS PagoCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM orden_pago op_3
												 WHERE
												   op_3.fecha_orden >= '$fdesde' AND
												   op_3.fecha_orden <= '$fhasta' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS Causado,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT pf_3.idorden_pago
												 FROM pagos_financieros pf_3
												 WHERE
												   pf_3.fecha_cheque >= '$fdesde' AND
												   pf_3.fecha_cheque <= '$fhasta' AND
												   (pf_3.estado = 'conciliado' OR
													pf_3.estado = 'transito' OR
													pf_3.estado = 'parcial')))) AS Pagado

				FROM
					maestro_presupuesto mp
					INNER JOIN clasificador_presupuestario cps ON (mp.idclasificador_presupuestario = cps.idclasificador_presupuestario)
					INNER JOIN ordinal o ON (mp.idordinal = o.idordinal)
					INNER JOIN categoria_programatica cp ON (mp.idcategoria_programatica = cp.idcategoria_programatica)
					INNER JOIN sector c On (cp.idsector = c.idsector)
					INNER JOIN programa p On (cp.idprograma = p.idprograma)
				WHERE 1 $filtro
				GROUP BY CodSector, CodPrograma, idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal
				ORDER BY CodSector, CodPrograma,  idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal";
            $query = mysql_query($sql) or die($sql . mysql_error());
            $rows  = mysql_num_rows($query);
            for ($i = 0; $i < $rows; $i++) {
                $field                 = mysql_fetch_array($query);
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['Disminucion'] - $field['Cedentes'] + $field['Rectificadora'];
                }
                $par = $field["CodPrograma"] . $field["Par"];
                $_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
                $_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
                $_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
                $_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
                $_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];
            }

        }

        //    CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO    O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
        $sql = "SELECT
					mp.idcategoria_programatica AS IdCategoria,
					mp.idclasificador_presupuestario AS idPartida,
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
					categoria_programatica.idsector AS IdSector,
					categoria_programatica.idprograma AS idprograma,
					c.codigo AS CodSector,
					c.denominacion AS Sector,
					p.codigo AS CodPrograma,
					p.denominacion AS Programa,
					mp.idRegistro AS IdPresupuesto,
					SUM(mp.monto_original) AS Formulado,
					'0000' AS codordinal,
					'' AS nomordinal
				FROM
					maestro_presupuesto mp,
					categoria_programatica,
					unidad_ejecutora,
					clasificador_presupuestario,
					ordinal,
					sector c,
					programa p
				WHERE
					((mp.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					 (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					 (mp.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
					 (clasificador_presupuestario.sub_especifica='00') AND
					 (ordinal.codigo='0000' AND mp.idordinal = ordinal.idordinal) AND
					 (categoria_programatica.idsector = c.idSector) AND
					 (categoria_programatica.idprograma = p.idprograma) $filtro)
				GROUP BY (CodSector), (CodPrograma), (Par), (Gen), (Esp), (Sesp), (codordinal)
				ORDER BY CodSector, CodPrograma, Par, Gen, Esp, Sesp, codordinal";
        $presupuestaria_anterior = 0;
        $financiera_anterior     = 0;
        $actualf                 = 0;
        $idprograma              = '';
        $query                   = mysql_query($sql) or die($sql . mysql_error());
        $rows                    = mysql_num_rows($query);
        for ($i = 0; $i < $rows; $i++) {
            $field = mysql_fetch_array($query);
            //    SI CAMBIA DE PROGRAMA LA IMPRIMO
            if ($field["idprograma"] != $idprograma) {
                $idprograma = $field["idprograma"];
                $l          = 1;
                if ($i != 0) {

                    //    IMPRIMO LOS TOTALES
                    if ($trimestre == '00' or $trimestre == "01") {
                        echo "<table border='1'>
							<tr>
								<td style='$tr2'>&nbsp;</td>
								<td style='$tr2'>TOTALES</td>
								<td style='$tr2' align='right'>" . number_format($sum_formulado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_pagado), 2, ',', '.') . "</td>
							</tr>
							</table>";
                    } else {
                        echo "<table border='1'>
							<tr>
								<td style='$tr2'>&nbsp;</td>
								<td style='$tr2'>TOTALES</td>
								<td style='$tr2' align='right'>" . number_format($sum_presupuestaria_anterior, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_financiera_anterior, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actualf - $sum_pagado), 2, ',', '.') . "</td>
							</tr>
							</table>";
                    }

                    $sum_formulado               = 0;
                    $sum_aumento                 = 0;
                    $sum_disminucion             = 0;
                    $sum_modificado              = 0;
                    $sum_actual                  = 0;
                    $sum_compromiso              = 0;
                    $sum_causado                 = 0;
                    $sum_pagado                  = 0;
                    $sum_disponible              = 0;
                    $sum_modificacion            = 0;
                    $sum_presupuestaria_anterior = 0;
                    $sum_financiera_anterior     = 0;
                    $sum_actualf                 = 0;

                    //---------------------------------------------
                    if ($trimestre == '00' or $trimestre == "01") {
                        echo "
						<table>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>SECTOR: " . $field["CodSector"] . '  ' . $field["Sector"] . "</td></tr>
							<tr><td colspan='10'>PROGRAMA: " . $field["CodPrograma"] . '  ' . $field["Programa"] . "</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
						</table>
						<table border='1'>
							<tr>
								<th width='100' style='$tr1'>PARTIDA</th>
								<th width='1000' style='$tr1'>DESCRIPCION</th>
								<th width='200' style='$tr1'>PRESUPUESTO INICIAL</th>
								<th width='200' style='$tr1'>MODIFICACION</th>
								<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
								<th width='200' style='$tr1'>COMPROMETIDO</th>
								<th width='200' style='$tr1'>CAUSADO</th>
								<th width='200' style='$tr1'>DISPONIBIBILIDAD PRESUPUESTARIA</th>
								<th width='200' style='$tr1'>PAGADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
							</tr>
						</table>";
                    } else {
                        echo "
						<table>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>SECTOR: " . $field["CodSector"] . '  ' . $field["Sector"] . "</td></tr>
							<tr><td colspan='10'>PROGRAMA: " . $field["CodPrograma"] . '  ' . $field["Programa"] . "</td></tr>
							<tr><td colspan='11'>&nbsp;</td></tr>
						</table>
						<table border='1'>
							<tr>
								<th width='100' style='$tr1'>PARTIDA</th>
								<th width='1000' style='$tr1'>DESCRIPCION</th>
								<th width='200' style='$tr1'>PRESUPUESTARIA ANTERIOR</th>
								<th width='200' style='$tr1'>FINANCIERA ANTERIOR</th>
								<th width='200' style='$tr1'>MODIFICACION</th>
								<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
								<th width='200' style='$tr1'>COMPROMETIDO</th>
								<th width='200' style='$tr1'>CAUSADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD PRESUPUESTARIA</th>
								<th width='200' style='$tr1'>PAGADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
							</tr>
						</table>";
                    }

                } else {
                    //---------------------------------------------
                    if ($trimestre == '00' or $trimestre == "01") {
                        echo "
						<table>
							<tr><td colspan='10' style='$tr3'>CONSOLIDADO POR PROGRAMA</td></tr>
							<tr><td colspan='10'>Desde: " . $idesde . " Hasta: " . $ihasta . "</td></tr>
							<tr><td colspan='10'>" . $trimestre_m . "</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
							<tr><td colspan='10'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
							<tr><td colspan='10'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>SECTOR: " . $field["CodSector"] . '  ' . $field["Sector"] . "</td></tr>
							<tr><td colspan='10'>PROGRAMA: " . $field["CodPrograma"] . '  ' . $field["Programa"] . "</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
						</table>
						<table border='1'>
							<tr>
								<th width='100' style='$tr1'>PARTIDA</th>
								<th width='1000' style='$tr1'>DESCRIPCION</th>
								<th width='200' style='$tr1'>PRESUPUESTO INICIAL</th>
								<th width='200' style='$tr1'>MODIFICACION</th>
								<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
								<th width='200' style='$tr1'>COMPROMETIDO</th>
								<th width='200' style='$tr1'>CAUSADO</th>
								<th width='200' style='$tr1'>DISPONIBIBILIDAD PRESUPUESTARIA</th>
								<th width='200' style='$tr1'>PAGADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
							</tr>
						</table>";
                    } else {
                        echo "
						<table>
							<tr><td colspan='11' style='$tr3'>CONSOLIDADO POR PROGRAMA</td></tr>
							<tr><td colspan='11'>Desde: " . $idesde . " Hasta: " . $ihasta . "</td></tr>
							<tr><td colspan='11'>" . $trimestre_m . "</td></tr>
							<tr><td colspan='11'>&nbsp;</td></tr>
							<tr><td colspan='11'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
							<tr><td colspan='11'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
							<tr><td colspan='11'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>SECTOR: " . $field["CodSector"] . '  ' . $field["Sector"] . "</td></tr>
							<tr><td colspan='10'>PROGRAMA: " . $field["CodPrograma"] . '  ' . $field["Programa"] . "</td></tr>
							<tr><td colspan='11'>&nbsp;</td></tr>
						</table>
						<table border='1'>
							<tr>
								<th width='100' style='$tr1'>PARTIDA</th>
								<th width='1000' style='$tr1'>DESCRIPCION</th>
								<th width='200' style='$tr1'>PRESUPUESTARIA ANTERIOR</th>
								<th width='200' style='$tr1'>FINANCIERA ANTERIOR</th>
								<th width='200' style='$tr1'>MODIFICACION</th>
								<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
								<th width='200' style='$tr1'>COMPROMETIDO</th>
								<th width='200' style='$tr1'>CAUSADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD PRESUPUESTARIA</th>
								<th width='200' style='$tr1'>PAGADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
							</tr>
						</table>";
                    }
                }
            }

            $l++;
            $clasificador = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $par          = $field['CodPrograma'] . $field["Par"];
            $estilo       = $tr5;

            if ($trimestre == '00' or $trimestre == '01') {
                $formulado             = $field['Formulado'];
                $formulado_m           = number_format($field['Formulado'], 2, ',', '.');
                $modificacion          = $_AUMENTO[$par] - $_DISMINUCION[$par];
                $modificacion_m        = number_format($modificacion, 2, ',', '.');
                $actual                = $field['Formulado'] + $modificacion;
                $actual_m              = number_format($actual, 2, ',', '.');
                $total_comprometidoI   = number_format($_COMPROMISO[$par], 2, ',', '.');
                $total_causaI          = number_format($_CAUSADO[$par], 2, ',', '.');
                $disponible_compromiso = number_format(($actual - $_COMPROMISO[$par]), 2, ',', '.');
                $total_pagadoI         = number_format($_PAGADO[$par], 2, ',', '.');
                $disponible_pagado     = number_format(($actual - $_PAGADO[$par]), 2, ',', '.');

                echo "
				<table>
				<tr>
					<td style='" . $estilo . "'>" . $clasificador . "</td>
					<td style='" . $estilo . "'>" . utf8_decode($field["NomPartida"]) . "</td>
					<td style='" . $estilo . "' align='right'>" . $formulado_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $modificacion_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $actual_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_comprometidoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_causaI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_compromiso . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_pagadoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_pagado . "</td>
				</tr>
				</table>";

            } else {

                $formulado                 = $field['Formulado'];
                $formulado_m               = number_format($field['Formulado'], 2, ',', '.');
                $presupuestaria_anterior   = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_COMPROMISO_ANTERIOR[$par];
                $presupuestaria_anterior_m = number_format($presupuestaria_anterior, 2, ',', '.');
                $financiera_anterior       = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_PAGADO_ANTERIOR[$par];
                $financiera_anterior_m     = number_format($financiera_anterior, 2, ',', '.');
                $modificacion              = $_AUMENTO[$par] - $_DISMINUCION[$par];
                $modificacion_m            = number_format($modificacion, 2, ',', '.');
                $actual                    = $presupuestaria_anterior + $modificacion;
                $actual_m                  = number_format($actual, 2, ',', '.');
                $actualf                   = $financiera_anterior + $modificacion;
                $actualf_m                 = number_format($actualf, 2, ',', '.');
                $total_comprometidoI       = number_format($_COMPROMISO[$par], 2, ',', '.');
                $total_causaI              = number_format($_CAUSADO[$par], 2, ',', '.');
                $disponible_compromiso     = number_format(($actual - $_COMPROMISO[$par]), 2, ',', '.');
                $total_pagadoI             = number_format($_PAGADO[$par], 2, ',', '.');
                $disponible_pagado         = number_format(($actual - $_PAGADO[$par]), 2, ',', '.');

                echo "
				<table>
				<tr>
					<td style='" . $estilo . "'>" . $clasificador . "</td>
					<td style='" . $estilo . "'>" . utf8_decode($field["NomPartida"]) . "</td>
					<td style='" . $estilo . "' align='right'>" . $presupuestaria_anterior_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $financiera_anterior_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $modificacion_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $actual_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_comprometidoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_causaI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_compromiso . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_pagadoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_pagado . "</td>
				</tr>
				</table>";

            }

            $sum_formulado += $field['Formulado'];
            $sum_presupuestaria_anterior += $presupuestaria_anterior;
            $sum_financiera_anterior += $financiera_anterior;
            $sum_aumento += $_AUMENTO[$par];
            $sum_disminucion += $_DISMINUCION[$par];
            $sum_modificacion += $modificacion;
            $sum_actual += $actual;
            $sum_actualf += $actualf;
            $sum_compromiso += $_COMPROMISO[$par];
            $sum_causado += $_CAUSADO[$par];
            $sum_pagado += $_PAGADO[$par];

            $total_formulado += $field['Formulado'];
            $total_presupuestaria_anterior += $presupuestaria_anterior;
            $total_financiera_anterior += $financiera_anterior;
            $total_aumento += $_AUMENTO[$par];
            $total_disminucion += $_DISMINUCION[$par];
            $total_modificacion += $modificacion;
            $total_actual += $actual;
            $total_actualf += $actualf;
            $total_compromiso += $_COMPROMISO[$par];
            $total_causado += $_CAUSADO[$par];
            $total_pagado += $_PAGADO[$par];
        }

        //------------------------------------------------

        if ($trimestre == '00' or $trimestre == "01") {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($sum_formulado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        } else {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($sum_presupuestaria_anterior, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_financiera_anterior, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actualf - $sum_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        }

        if ($trimestre == '00' or $trimestre == "01") {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($total_formulado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        } else {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($total_presupuestaria_anterior, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_financiera_anterior, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actualf - $total_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        }

        break;

    //    Consolidado por Categoria ONAPRE...
    case "consolidado_categoria_onapre":
        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:14px; color:#000000; font-weight:bold; border-bottom-width: thin; border-bottom: solid #000000;";
        $tr3 = "background-color:#D8D8D8; font-size:12px; color:#000000; font-weight:bold; ";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:14px; color:#000000;";
        //---------------------------------------------
        if ($trimestre == "00") {
            $trimestre_m = '';
        } elseif ($trimestre == "01") {
            $trimestre_m = 'TRIMESTRE I';
        } elseif ($trimestre == "02") {
            $trimestre_m = 'TRIMESTRE II';
        } elseif ($trimestre == "03") {
            $trimestre_m = 'TRIMESTRE III';
        } elseif ($trimestre == "04") {
            $trimestre_m = 'TRIMESTRE IV';
        }
        //---------------------------------------------
        $campos = explode("|", $checks);
        //---------------------------------------------
        $sql = "SELECT
					denominacion As TipoPresupuesto,
					(SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento = '" . $financiamiento . "') AS FuenteFinanciamiento
				FROM tipo_presupuesto
				WHERE idtipo_presupuesto = '" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------

        $filtro = "";
        if ($anio_fiscal != "") {
            $filtro .= " AND (mp.anio = '" . $anio_fiscal . "')";
        }

        if ($financiamiento != "") {
            $filtro .= " AND (mp.idfuente_financiamiento = '" . $financiamiento . "')";
        }

        if ($tipo_presupuesto != "") {
            $filtro .= " AND (mp.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
        }

        if ($idcategoria_programatica != "") {
            $filtro .= " AND (mp.idcategoria_programatica='" . $idcategoria_programatica . "')";
        }

        //---------------------------------------------
        $mes_inicial   = 1;
        $desde_inicial = $anio_fiscal . '-01-01';
        if ($trimestre == '00') {
            $idesde         = '01-01-' . $anio_fiscal;
            $ihasta         = '31-12-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-01-01';
            $fhasta         = $anio_fiscal . '-12-31';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 12;
        }
        if ($trimestre == '01') {
            $idesde         = '01-01-' . $anio_fiscal;
            $ihasta         = '31-03-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-01-01';
            $fhasta         = $anio_fiscal . '-03-31';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 3;
        }

        if ($trimestre == '02') {
            $idesde         = '01-04-' . $anio_fiscal;
            $ihasta         = '30-06-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-04-01';
            $fhasta         = $anio_fiscal . '-06-30';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 6;
            $mes_anterior   = 3;

        }
        if ($trimestre == '03') {
            $idesde         = '01-07-' . $anio_fiscal;
            $ihasta         = '30-09-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-07-01';
            $fhasta         = $anio_fiscal . '-09-30';
            $hasta_anterior = $anio_fiscal . '-06-30';
            $mes_final      = 9;
            $mes_anterior   = 6;
        }
        if ($trimestre == '04') {
            $idesde         = '01-10-' . $anio_fiscal;
            $ihasta         = '31-12-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-10-01';
            $fhasta         = $anio_fiscal . '-12-31';
            $hasta_anterior = $anio_fiscal . '-09-30';
            $mes_final      = 12;
            $mes_anterior   = 9;
        }

        //    CONSULTO TODO PARA SUMAR LAS PARTIDAS

        if ($trimestre == '00' or $trimestre == '01') {

            $sql = "SELECT
					cps.idclasificador_presupuestario,
					cps.denominacion AS NombrePartida,
					cps.partida AS Par,
					cps.generica AS Gen,
					cps.especifica AS Esp,
					cps.sub_especifica AS Sesp,
					cp.idcategoria_programatica,
					cp.codigo As CodCategoria,
					cp.idunidad_ejecutora,
					mp.idRegistro,
					mp.idordinal,


					(SELECT SUM(pca_2.monto_acreditar)
					 FROM partidas_credito_adicional pca_2
					 WHERE
						 pca_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pca_2.idcredito_adicional IN (SELECT ca_3.idcreditos_adicionales
														FROM creditos_adicionales ca_3
														WHERE
															ca_3.fecha_solicitud >= '$fdesde' AND
															ca_3.fecha_solicitud <= '$fhasta' AND
															ca_3.estado = 'procesado'))) AS Credito,

					(SELECT SUM(prt_2.monto_acreditar)
					 FROM partidas_receptoras_traslado prt_2
					 WHERE
						 prt_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prt_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$fdesde' AND
																  tp_3.fecha_solicitud <= '$fhasta' AND
																  tp_3.estado = 'procesado'))) AS Receptora,

					(SELECT SUM(prr_2.monto_acreditar)
					 FROM partidas_receptoras_rectificacion prr_2
					 WHERE
						 prr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
																FROM rectificacion_presupuesto rp_3
																WHERE
																  rp_3.fecha_solicitud >= '$fdesde' AND
																  rp_3.fecha_solicitud <= '$fhasta' AND
																  rp_3.estado = 'procesado'))) AS Rectificacion,

					(SELECT SUM(pdp_2.monto_debitar)
					 FROM partidas_disminucion_presupuesto pdp_2
					 WHERE
						 pdp_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pdp_2.iddisminucion_presupuesto IN (SELECT dp_3.iddisminucion_presupuesto
															  FROM disminucion_presupuesto dp_3
															  WHERE
																dp_3.fecha_solicitud >= '$fdesde' AND
																dp_3.fecha_solicitud <= '$fhasta' AND
																dp_3.estado = 'procesado'))) AS Disminucion,

					(SELECT SUM(pct_2.monto_debitar)
					 FROM partidas_cedentes_traslado pct_2
					 WHERE
						 pct_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pct_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$fdesde' AND
																  tp_3.fecha_solicitud <= '$fhasta' AND
																  tp_3.estado = 'procesado'))) AS Cedentes,

					(SELECT SUM(pr_2.monto_debitar)
					 FROM partidas_rectificadoras pr_2
					 WHERE
						 pr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
															   FROM rectificacion_presupuesto rp_3
															   WHERE
																 rp_3.fecha_solicitud >= '$fdesde' AND
																 rp_3.fecha_solicitud <= '$fhasta' AND
																 rp_3.estado = 'procesado'))) AS Rectificadora,


					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionAumento1,


					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionPagados1,

					(SELECT SUM(pocs_2.monto)
					 FROM partidas_orden_compra_servicio pocs_2
					 WHERE
						 pocs_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pocs_2.idorden_compra_servicio IN (SELECT ocs_3.idorden_compra_servicio
															 FROM orden_compra_servicio ocs_3
															 WHERE
															   ocs_3.fecha_orden >= '$fdesde' AND
															   ocs_3.fecha_orden <= '$fhasta' AND
															   (ocs_3.estado = 'procesado' OR
																ocs_3.estado = 'pagado' OR
																ocs_3.estado = 'conformado' OR
																ocs_3.estado = 'parcial')))) AS CompraCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM
												   orden_pago op_3
												   INNER JOIN tipos_documentos td_3 ON (op_3.tipo = td_3.idtipos_documentos AND td_3.compromete = 'si')
												 WHERE
												   op_3.fecha_orden >= '$fdesde' AND
												   op_3.fecha_orden <= '$fhasta' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS PagoCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM orden_pago op_3
												 WHERE
												   op_3.fecha_orden >= '$fdesde' AND
												   op_3.fecha_orden <= '$fhasta' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS Causado,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT pf_3.idorden_pago
												 FROM pagos_financieros pf_3
												 WHERE
												   pf_3.fecha_cheque >= '$fdesde' AND
												   pf_3.fecha_cheque <= '$fhasta' AND
												   (pf_3.estado = 'conciliado' OR
													pf_3.estado = 'transito' OR
													pf_3.estado = 'parcial')))) AS Pagado

				FROM
					maestro_presupuesto mp
					INNER JOIN clasificador_presupuestario cps ON (mp.idclasificador_presupuestario = cps.idclasificador_presupuestario)
					INNER JOIN ordinal o ON (mp.idordinal = o.idordinal)
					INNER JOIN categoria_programatica cp ON (mp.idcategoria_programatica = cp.idcategoria_programatica)
				WHERE 1 $filtro
				GROUP BY idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal
				ORDER BY idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal";
            $query = mysql_query($sql) or die($sql . mysql_error());
            $rows  = mysql_num_rows($query);
            for ($i = 0; $i < $rows; $i++) {
                $field                 = mysql_fetch_array($query);
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['Disminucion'] - $field['Cedentes'] + $field['Rectificadora'];
                }
                $par = $field["CodCategoria"] . $field["Par"];
                $_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
                $_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
                $_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
                $_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
                $_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];
            }

        } else {

            //OBTENGO LOS MONTO DEL PERIODO ANTERIOR
            $sql = "SELECT
					cps.idclasificador_presupuestario,
					cps.denominacion AS NombrePartida,
					cps.partida AS Par,
					cps.generica AS Gen,
					cps.especifica AS Esp,
					cps.sub_especifica AS Sesp,
					cp.idcategoria_programatica,
					cp.codigo As CodCategoria,
					cp.idunidad_ejecutora,
					mp.idRegistro,
					mp.idordinal,

					(SELECT SUM(pca_2.monto_acreditar)
					 FROM partidas_credito_adicional pca_2
					 WHERE
						 pca_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pca_2.idcredito_adicional IN (SELECT ca_3.idcreditos_adicionales
														FROM creditos_adicionales ca_3
														WHERE
															ca_3.fecha_solicitud >= '$desde_inicial' AND
															ca_3.fecha_solicitud <= '$hasta_anterior' AND
															ca_3.estado = 'procesado'))) AS Credito,

					(SELECT SUM(prt_2.monto_acreditar)
					 FROM partidas_receptoras_traslado prt_2
					 WHERE
						 prt_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prt_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$desde_inicial' AND
																  tp_3.fecha_solicitud <= '$hasta_anterior' AND
																  tp_3.estado = 'procesado'))) AS Receptora,

					(SELECT SUM(prr_2.monto_acreditar)
					 FROM partidas_receptoras_rectificacion prr_2
					 WHERE
						 prr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
																FROM rectificacion_presupuesto rp_3
																WHERE
																  rp_3.fecha_solicitud >= '$desde_inicial' AND
																  rp_3.fecha_solicitud <= '$hasta_anterior' AND
																  rp_3.estado = 'procesado'))) AS Rectificacion,

					(SELECT SUM(pdp_2.monto_debitar)
					 FROM partidas_disminucion_presupuesto pdp_2
					 WHERE
						 pdp_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pdp_2.iddisminucion_presupuesto IN (SELECT dp_3.iddisminucion_presupuesto
															  FROM disminucion_presupuesto dp_3
															  WHERE
																dp_3.fecha_solicitud >= '$desde_inicial' AND
																dp_3.fecha_solicitud <= '$hasta_anterior' AND
																dp_3.estado = 'procesado'))) AS Disminucion,

					(SELECT SUM(pct_2.monto_debitar)
					 FROM partidas_cedentes_traslado pct_2
					 WHERE
						 pct_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pct_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$desde_inicial' AND
																  tp_3.fecha_solicitud <= '$hasta_anterior' AND
																  tp_3.estado = 'procesado'))) AS Cedentes,

					(SELECT SUM(pr_2.monto_debitar)
					 FROM partidas_rectificadoras pr_2
					 WHERE
						 pr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
															   FROM rectificacion_presupuesto rp_3
															   WHERE
																 rp_3.fecha_solicitud >= '$desde_inicial' AND
																 rp_3.fecha_solicitud <= '$hasta_anterior' AND
																 rp_3.estado = 'procesado'))) AS Rectificadora,


					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionAumento1,


					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionPagados1,

					(SELECT SUM(pocs_2.monto)
					 FROM partidas_orden_compra_servicio pocs_2
					 WHERE
						 pocs_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pocs_2.idorden_compra_servicio IN (SELECT ocs_3.idorden_compra_servicio
															 FROM orden_compra_servicio ocs_3
															 WHERE
															   ocs_3.fecha_orden >= '$desde_inicial' AND
															   ocs_3.fecha_orden <= '$hasta_anterior' AND
															   (ocs_3.estado = 'procesado' OR
																ocs_3.estado = 'pagado' OR
																ocs_3.estado = 'conformado' OR
																ocs_3.estado = 'parcial')))) AS CompraCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM
												   orden_pago op_3
												   INNER JOIN tipos_documentos td_3 ON
												   (op_3.tipo = td_3.idtipos_documentos AND td_3.compromete = 'si')
												 WHERE
												   op_3.fecha_orden >= '$desde_inicial' AND
												   op_3.fecha_orden <= '$hasta_anterior' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS PagoCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM orden_pago op_3
												 WHERE
												   op_3.fecha_orden >= '$desde_inicial' AND
												   op_3.fecha_orden <= '$hasta_anterior' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS Causado,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT pf_3.idorden_pago
												 FROM pagos_financieros pf_3
												 WHERE
												   pf_3.fecha_cheque >= '$desde_inicial' AND
												   pf_3.fecha_cheque <= '$hasta_anterior' AND
												   (pf_3.estado = 'conciliado' OR
													pf_3.estado = 'transito' OR
													pf_3.estado = 'parcial')))) AS Pagado

				FROM
					maestro_presupuesto mp
					INNER JOIN clasificador_presupuestario cps ON (mp.idclasificador_presupuestario = cps.idclasificador_presupuestario)
					INNER JOIN ordinal o ON (mp.idordinal = o.idordinal)
					INNER JOIN categoria_programatica cp ON (mp.idcategoria_programatica = cp.idcategoria_programatica)
				WHERE 1 $filtro
				GROUP BY CodCategoria, Par, Gen, Esp, Sesp, idordinal
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, idordinal";
            $query = mysql_query($sql) or die($sql . mysql_error());
            $rows  = mysql_num_rows($query);
            for ($i = 0; $i < $rows; $i++) {
                $field                 = mysql_fetch_array($query);
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['Disminucion'] - $field['Cedentes'] + $field['Rectificadora'];
                }
                $par = $field["CodCategoria"] . $field["Par"];
                $_AUMENTO_ANTERIOR[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
                $_DISMINUCION_ANTERIOR[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
                $_COMPROMISO_ANTERIOR[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
                $_CAUSADO_ANTERIOR[$par] += $field['Causado'] + $field['MRendicionCausado1'];
                $_PAGADO_ANTERIOR[$par] += $field['Pagado'] + $field['MRendicionPagados1'];
            }

            //OBTRENGO LOS TOTALES DEL PERIODO A CALCULAR
            $sql = "SELECT
					cps.idclasificador_presupuestario,
					cps.denominacion AS NombrePartida,
					cps.partida AS Par,
					cps.generica AS Gen,
					cps.especifica AS Esp,
					cps.sub_especifica AS Sesp,
					cp.idcategoria_programatica,
					cp.codigo As CodCategoria,
					cp.idunidad_ejecutora,
					mp.idRegistro,
					mp.idordinal,


					(SELECT SUM(pca_2.monto_acreditar)
					 FROM partidas_credito_adicional pca_2
					 WHERE
						 pca_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pca_2.idcredito_adicional IN (SELECT ca_3.idcreditos_adicionales
														FROM creditos_adicionales ca_3
														WHERE
															ca_3.fecha_solicitud >= '$fdesde' AND
															ca_3.fecha_solicitud <= '$fhasta' AND
															ca_3.estado = 'procesado'))) AS Credito,

					(SELECT SUM(prt_2.monto_acreditar)
					 FROM partidas_receptoras_traslado prt_2
					 WHERE
						 prt_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prt_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$fdesde' AND
																  tp_3.fecha_solicitud <= '$fhasta' AND
																  tp_3.estado = 'procesado'))) AS Receptora,

					(SELECT SUM(prr_2.monto_acreditar)
					 FROM partidas_receptoras_rectificacion prr_2
					 WHERE
						 prr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (prr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
																FROM rectificacion_presupuesto rp_3
																WHERE
																  rp_3.fecha_solicitud >= '$fdesde' AND
																  rp_3.fecha_solicitud <= '$fhasta' AND
																  rp_3.estado = 'procesado'))) AS Rectificacion,

					(SELECT SUM(pdp_2.monto_debitar)
					 FROM partidas_disminucion_presupuesto pdp_2
					 WHERE
						 pdp_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pdp_2.iddisminucion_presupuesto IN (SELECT dp_3.iddisminucion_presupuesto
															  FROM disminucion_presupuesto dp_3
															  WHERE
																dp_3.fecha_solicitud >= '$fdesde' AND
																dp_3.fecha_solicitud <= '$fhasta' AND
																dp_3.estado = 'procesado'))) AS Disminucion,

					(SELECT SUM(pct_2.monto_debitar)
					 FROM partidas_cedentes_traslado pct_2
					 WHERE
						 pct_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pct_2.idtraslados_presupuestarios IN (SELECT tp_3.idtraslados_presupuestarios
																FROM traslados_presupuestarios tp_3
																WHERE
																  tp_3.fecha_solicitud >= '$fdesde' AND
																  tp_3.fecha_solicitud <= '$fhasta' AND
																  tp_3.estado = 'procesado'))) AS Cedentes,

					(SELECT SUM(pr_2.monto_debitar)
					 FROM partidas_rectificadoras pr_2
					 WHERE
						 pr_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pr_2.idrectificacion_presupuesto IN (SELECT rp_3.idrectificacion_presupuesto
															   FROM rectificacion_presupuesto rp_3
															   WHERE
																 rp_3.fecha_solicitud >= '$fdesde' AND
																 rp_3.fecha_solicitud <= '$fhasta' AND
																 rp_3.estado = 'procesado'))) AS Rectificadora,


					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionAumento1,


					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=mp.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionPagados1,

					(SELECT SUM(pocs_2.monto)
					 FROM partidas_orden_compra_servicio pocs_2
					 WHERE
						 pocs_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pocs_2.idorden_compra_servicio IN (SELECT ocs_3.idorden_compra_servicio
															 FROM orden_compra_servicio ocs_3
															 WHERE
															   ocs_3.fecha_orden >= '$fdesde' AND
															   ocs_3.fecha_orden <= '$fhasta' AND
															   (ocs_3.estado = 'procesado' OR
																ocs_3.estado = 'pagado' OR
																ocs_3.estado = 'conformado' OR
																ocs_3.estado = 'parcial')))) AS CompraCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM
												   orden_pago op_3
												   INNER JOIN tipos_documentos td_3 ON (op_3.tipo = td_3.idtipos_documentos AND td_3.compromete = 'si')
												 WHERE
												   op_3.fecha_orden >= '$fdesde' AND
												   op_3.fecha_orden <= '$fhasta' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS PagoCompromiso,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT op_3.idorden_pago
												 FROM orden_pago op_3
												 WHERE
												   op_3.fecha_orden >= '$fdesde' AND
												   op_3.fecha_orden <= '$fhasta' AND
												   (op_3.estado = 'procesado' OR
													op_3.estado = 'pagada' OR
													op_3.estado = 'conformado' OR
													op_3.estado = 'parcial')))) AS Causado,

					(SELECT SUM(pop_2.monto)
					 FROM partidas_orden_pago pop_2
					 WHERE
						 pop_2.idmaestro_presupuesto = mp.idRegistro AND
						 (pop_2.idorden_pago IN (SELECT pf_3.idorden_pago
												 FROM pagos_financieros pf_3
												 WHERE
												   pf_3.fecha_cheque >= '$fdesde' AND
												   pf_3.fecha_cheque <= '$fhasta' AND
												   (pf_3.estado = 'conciliado' OR
													pf_3.estado = 'transito' OR
													pf_3.estado = 'parcial')))) AS Pagado

				FROM
					maestro_presupuesto mp
					INNER JOIN clasificador_presupuestario cps ON (mp.idclasificador_presupuestario = cps.idclasificador_presupuestario)
					INNER JOIN ordinal o ON (mp.idordinal = o.idordinal)
					INNER JOIN categoria_programatica cp ON (mp.idcategoria_programatica = cp.idcategoria_programatica)
				WHERE 1 $filtro
				GROUP BY CodCategoria, Par, Gen, Esp, Sesp, idordinal
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, idordinal";
            $query = mysql_query($sql) or die($sql . mysql_error());
            $rows  = mysql_num_rows($query);
            for ($i = 0; $i < $rows; $i++) {
                $field                 = mysql_fetch_array($query);
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['Disminucion'] - $field['Cedentes'] + $field['Rectificadora'];
                }
                $par = $field["CodCategoria"] . $field["Par"];
                $_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
                $_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
                $_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
                $_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
                $_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];
            }

        }

        //    CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO    O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
        $sql = "SELECT
					mp.idcategoria_programatica AS IdCategoria,
					mp.idclasificador_presupuestario AS idPartida,
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
					categoria_programatica.idsector AS IdSector,
					categoria_programatica.idprograma AS idprograma,
					mp.idRegistro AS IdPresupuesto,
					SUM(mp.monto_original) AS Formulado,
					'0000' AS codordinal,
					'' AS nomordinal
				FROM
					maestro_presupuesto mp,
					categoria_programatica,
					unidad_ejecutora,
					clasificador_presupuestario,
					ordinal
				WHERE
					((mp.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					 (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					 (mp.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
					 (clasificador_presupuestario.sub_especifica='00') AND
					 (ordinal.codigo='0000' AND mp.idordinal = ordinal.idordinal) $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal)
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";

        $presupuestaria_anterior = 0;
        $financiera_anterior     = 0;
        $actualf                 = 0;
        $IdCategoria             = '';
        $query                   = mysql_query($sql) or die($sql . mysql_error());
        $rows                    = mysql_num_rows($query);
        for ($i = 0; $i < $rows; $i++) {
            $field = mysql_fetch_array($query);
            //    SI CAMBIA DE PROGRAMA LA IMPRIMO
            if ($field["IdCategoria"] != $IdCategoria) {
                $IdCategoria = $field["IdCategoria"];
                $l           = 1;
                if ($i != 0) {

                    //    IMPRIMO LOS TOTALES
                    if ($trimestre == '00' or $trimestre == "01") {
                        echo "<table border='1'>
							<tr>
								<td style='$tr2'>&nbsp;</td>
								<td style='$tr2'>TOTALES</td>
								<td style='$tr2' align='right'>" . number_format($sum_formulado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_pagado), 2, ',', '.') . "</td>
							</tr>
							</table>";
                    } else {
                        echo "<table border='1'>
							<tr>
								<td style='$tr2'>&nbsp;</td>
								<td style='$tr2'>TOTALES</td>
								<td style='$tr2' align='right'>" . number_format($sum_presupuestaria_anterior, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_financiera_anterior, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
								<td style='$tr2' align='right'>" . number_format(($sum_actualf - $sum_pagado), 2, ',', '.') . "</td>
							</tr>
							</table>";
                    }

                    $sum_formulado               = 0;
                    $sum_aumento                 = 0;
                    $sum_disminucion             = 0;
                    $sum_modificado              = 0;
                    $sum_actual                  = 0;
                    $sum_compromiso              = 0;
                    $sum_causado                 = 0;
                    $sum_pagado                  = 0;
                    $sum_disponible              = 0;
                    $sum_modificacion            = 0;
                    $sum_presupuestaria_anterior = 0;
                    $sum_financiera_anterior     = 0;
                    $sum_actualf                 = 0;

                    //---------------------------------------------
                    if ($trimestre == '00' or $trimestre == "01") {
                        echo "
						<table>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>CATEGORIA: " . $field["CodCategoria"] . '  ' . $field["Unidad"] . "</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
						</table>
						<table border='1'>
							<tr>
								<th width='100' style='$tr1'>PARTIDA</th>
								<th width='1000' style='$tr1'>DESCRIPCION</th>
								<th width='200' style='$tr1'>PRESUPUESTO INICIAL</th>
								<th width='200' style='$tr1'>MODIFICACION</th>
								<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
								<th width='200' style='$tr1'>COMPROMETIDO</th>
								<th width='200' style='$tr1'>CAUSADO</th>
								<th width='200' style='$tr1'>DISPONIBIBILIDAD PRESUPUESTARIA</th>
								<th width='200' style='$tr1'>PAGADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
							</tr>
						</table>";
                    } else {
                        echo "
						<table>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>CATEGORIA: " . $field["CodCategoria"] . '  ' . $field["Unidad"] . "</td></tr>
							<tr><td colspan='11'>&nbsp;</td></tr>
						</table>
						<table border='1'>
							<tr>
								<th width='100' style='$tr1'>PARTIDA</th>
								<th width='1000' style='$tr1'>DESCRIPCION</th>
								<th width='200' style='$tr1'>PRESUPUESTARIA ANTERIOR</th>
								<th width='200' style='$tr1'>FINANCIERA ANTERIOR</th>
								<th width='200' style='$tr1'>MODIFICACION</th>
								<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
								<th width='200' style='$tr1'>COMPROMETIDO</th>
								<th width='200' style='$tr1'>CAUSADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD PRESUPUESTARIA</th>
								<th width='200' style='$tr1'>PAGADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
							</tr>
						</table>";
                    }

                } else {
                    //---------------------------------------------
                    if ($trimestre == '00' or $trimestre == "01") {
                        echo "
						<table>
							<tr><td colspan='10' style='$tr3'>CONSOLIDADO POR CATEGORIA</td></tr>
							<tr><td colspan='10'>Desde: " . $idesde . " Hasta: " . $ihasta . "</td></tr>
							<tr><td colspan='10'>" . $trimestre_m . "</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
							<tr><td colspan='10'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
							<tr><td colspan='10'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>CATEGORIA: " . $field["CodCategoria"] . '  ' . $field["Unidad"] . "</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
						</table>
						<table border='1'>
							<tr>
								<th width='100' style='$tr1'>PARTIDA</th>
								<th width='1000' style='$tr1'>DESCRIPCION</th>
								<th width='200' style='$tr1'>PRESUPUESTO INICIAL</th>
								<th width='200' style='$tr1'>MODIFICACION</th>
								<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
								<th width='200' style='$tr1'>COMPROMETIDO</th>
								<th width='200' style='$tr1'>CAUSADO</th>
								<th width='200' style='$tr1'>DISPONIBIBILIDAD PRESUPUESTARIA</th>
								<th width='200' style='$tr1'>PAGADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
							</tr>
						</table>";
                    } else {
                        echo "
						<table>
							<tr><td colspan='11' style='$tr3'>CONSOLIDADO POR CATEGORIA</td></tr>
							<tr><td colspan='11'>Desde: " . $idesde . " Hasta: " . $ihasta . "</td></tr>
							<tr><td colspan='11'>" . $trimestre_m . "</td></tr>
							<tr><td colspan='11'>&nbsp;</td></tr>
							<tr><td colspan='11'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
							<tr><td colspan='11'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
							<tr><td colspan='11'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
							<tr><td colspan='10'>&nbsp;</td></tr>
							<tr><td colspan='10'>CATEGORIA: " . $field["CodCategoria"] . '  ' . $field["Unidad"] . "</td></tr>
							<tr><td colspan='11'>&nbsp;</td></tr>
						</table>
						<table border='1'>
							<tr>
								<th width='100' style='$tr1'>PARTIDA</th>
								<th width='1000' style='$tr1'>DESCRIPCION</th>
								<th width='200' style='$tr1'>PRESUPUESTARIA ANTERIOR</th>
								<th width='200' style='$tr1'>FINANCIERA ANTERIOR</th>
								<th width='200' style='$tr1'>MODIFICACION</th>
								<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
								<th width='200' style='$tr1'>COMPROMETIDO</th>
								<th width='200' style='$tr1'>CAUSADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD PRESUPUESTARIA</th>
								<th width='200' style='$tr1'>PAGADO</th>
								<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
							</tr>
						</table>";
                    }
                }
            }

            $l++;
            $clasificador = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $par          = $field['CodCategoria'] . $field["Par"];
            $estilo       = $tr5;

            if ($trimestre == '00' or $trimestre == '01') {
                $formulado             = $field['Formulado'];
                $formulado_m           = number_format($field['Formulado'], 2, ',', '.');
                $modificacion          = $_AUMENTO[$par] - $_DISMINUCION[$par];
                $modificacion_m        = number_format($modificacion, 2, ',', '.');
                $actual                = $field['Formulado'] + $modificacion;
                $actual_m              = number_format($actual, 2, ',', '.');
                $total_comprometidoI   = number_format($_COMPROMISO[$par], 2, ',', '.');
                $total_causaI          = number_format($_CAUSADO[$par], 2, ',', '.');
                $disponible_compromiso = number_format(($actual - $_COMPROMISO[$par]), 2, ',', '.');
                $total_pagadoI         = number_format($_PAGADO[$par], 2, ',', '.');
                $disponible_pagado     = number_format(($actual - $_PAGADO[$par]), 2, ',', '.');

                echo "
				<table>
				<tr>
					<td style='" . $estilo . "'>" . $clasificador . "</td>
					<td style='" . $estilo . "'>" . utf8_decode($field["NomPartida"]) . "</td>
					<td style='" . $estilo . "' align='right'>" . $formulado_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $modificacion_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $actual_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_comprometidoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_causaI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_compromiso . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_pagadoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_pagado . "</td>
				</tr>
				</table>";

            } else {

                $formulado                 = $field['Formulado'];
                $formulado_m               = number_format($field['Formulado'], 2, ',', '.');
                $presupuestaria_anterior   = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_COMPROMISO_ANTERIOR[$par];
                $presupuestaria_anterior_m = number_format($presupuestaria_anterior, 2, ',', '.');
                $financiera_anterior       = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_PAGADO_ANTERIOR[$par];
                $financiera_anterior_m     = number_format($financiera_anterior, 2, ',', '.');
                $modificacion              = $_AUMENTO[$par] - $_DISMINUCION[$par];
                $modificacion_m            = number_format($modificacion, 2, ',', '.');
                $actual                    = $presupuestaria_anterior + $modificacion;
                $actual_m                  = number_format($actual, 2, ',', '.');
                $actualf                   = $financiera_anterior + $modificacion;
                $actualf_m                 = number_format($actualf, 2, ',', '.');
                $total_comprometidoI       = number_format($_COMPROMISO[$par], 2, ',', '.');
                $total_causaI              = number_format($_CAUSADO[$par], 2, ',', '.');
                $disponible_compromiso     = number_format(($actual - $_COMPROMISO[$par]), 2, ',', '.');
                $total_pagadoI             = number_format($_PAGADO[$par], 2, ',', '.');
                $disponible_pagado         = number_format(($actual - $_PAGADO[$par]), 2, ',', '.');

                echo "
				<table>
				<tr>
					<td style='" . $estilo . "'>" . $clasificador . "</td>
					<td style='" . $estilo . "'>" . utf8_decode($field["NomPartida"]) . "</td>
					<td style='" . $estilo . "' align='right'>" . $presupuestaria_anterior_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $financiera_anterior_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $modificacion_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $actual_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_comprometidoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_causaI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_compromiso . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_pagadoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_pagado . "</td>
				</tr>
				</table>";

            }

            $sum_formulado += $field['Formulado'];
            $sum_presupuestaria_anterior += $presupuestaria_anterior;
            $sum_financiera_anterior += $financiera_anterior;
            $sum_aumento += $_AUMENTO[$par];
            $sum_disminucion += $_DISMINUCION[$par];
            $sum_modificacion += $modificacion;
            $sum_actual += $actual;
            $sum_actualf += $actualf;
            $sum_compromiso += $_COMPROMISO[$par];
            $sum_causado += $_CAUSADO[$par];
            $sum_pagado += $_PAGADO[$par];

            $total_formulado += $field['Formulado'];
            $total_presupuestaria_anterior += $presupuestaria_anterior;
            $total_financiera_anterior += $financiera_anterior;
            $total_aumento += $_AUMENTO[$par];
            $total_disminucion += $_DISMINUCION[$par];
            $total_modificacion += $modificacion;
            $total_actual += $actual;
            $total_actualf += $actualf;
            $total_compromiso += $_COMPROMISO[$par];
            $total_causado += $_CAUSADO[$par];
            $total_pagado += $_PAGADO[$par];
        }

        //------------------------------------------------

        if ($trimestre == '00' or $trimestre == "01") {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($sum_formulado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        } else {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($sum_presupuestaria_anterior, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_financiera_anterior, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actualf - $sum_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        }

        if ($trimestre == '00' or $trimestre == "01") {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($total_formulado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        } else {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($total_presupuestaria_anterior, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_financiera_anterior, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actual - $total_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($total_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($total_actualf - $total_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        }
        break;
//FIN REPORTE CONSOLIDADO CATEGORIA

//    Consolidado por Categoria ONAPRE...
    case "consolidado_general_onapre":
        $tr1 = "background-color:#999999; font-size:12px;";
        $tr2 = "font-size:14px; color:#000000; font-weight:bold; border-bottom-width: thin; border-bottom: solid #000000;";
        $tr3 = "background-color:#D8D8D8; font-size:12px; color:#000000; font-weight:bold; ";
        $tr4 = "font-size:12px; color:#000000; font-weight:bold;";
        $tr5 = "font-size:14px; color:#000000;";
        //---------------------------------------------
        if ($trimestre == "00") {
            $trimestre_m = '';
        } elseif ($trimestre == "01") {
            $trimestre_m = 'TRIMESTRE I';
        } elseif ($trimestre == "02") {
            $trimestre_m = 'TRIMESTRE II';
        } elseif ($trimestre == "03") {
            $trimestre_m = 'TRIMESTRE III';
        } elseif ($trimestre == "04") {
            $trimestre_m = 'TRIMESTRE IV';
        }
        //---------------------------------------------
        $campos = explode("|", $checks);
        //---------------------------------------------
        $sql = "SELECT
					denominacion As TipoPresupuesto,
					(SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento = '" . $financiamiento . "') AS FuenteFinanciamiento
				FROM tipo_presupuesto
				WHERE idtipo_presupuesto = '" . $tipo_presupuesto . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $rows  = mysql_num_rows($query);
        if ($rows != 0) {
            $field = mysql_fetch_array($query);
        }

        $nom_fuente_financiamiento = $field['FuenteFinanciamiento'];
        $nom_tipo_presupuesto      = $field['TipoPresupuesto'];
        //---------------------------------------------

        $filtro = "";
        if ($anio_fiscal != "") {
            $filtro .= " AND (maestro_presupuesto.anio = '" . $anio_fiscal . "')";
        }

        if ($financiamiento != "") {
            $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '" . $financiamiento . "')";
        }

        if ($tipo_presupuesto != "") {
            $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
        }

        //---------------------------------------------
        $mes_inicial   = 1;
        $desde_inicial = $anio_fiscal . '-01-01';
        if ($trimestre == '00') {
            $idesde         = '01-01-' . $anio_fiscal;
            $ihasta         = '31-12-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-01-01';
            $fhasta         = $anio_fiscal . '-12-31';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 12;
        }
        if ($trimestre == '01') {
            $idesde         = '01-01-' . $anio_fiscal;
            $ihasta         = '31-03-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-01-01';
            $fhasta         = $anio_fiscal . '-03-31';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 3;
        }

        if ($trimestre == '02') {
            $idesde         = '01-04-' . $anio_fiscal;
            $ihasta         = '30-06-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-04-01';
            $fhasta         = $anio_fiscal . '-06-30';
            $hasta_anterior = $anio_fiscal . '-03-31';
            $mes_final      = 6;
            $mes_anterior   = 3;

        }
        if ($trimestre == '03') {
            $idesde         = '01-07-' . $anio_fiscal;
            $ihasta         = '30-09-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-07-01';
            $fhasta         = $anio_fiscal . '-09-30';
            $hasta_anterior = $anio_fiscal . '-06-30';
            $mes_final      = 9;
            $mes_anterior   = 6;
        }
        if ($trimestre == '04') {
            $idesde         = '01-10-' . $anio_fiscal;
            $ihasta         = '31-12-' . $anio_fiscal;
            $fdesde         = $anio_fiscal . '-10-01';
            $fhasta         = $anio_fiscal . '-12-31';
            $hasta_anterior = $anio_fiscal . '-09-30';
            $mes_final      = 12;
            $mes_anterior   = 9;
        }

        //---------------------------------------------
        //    CONSULTO TODO PARA SUMAR LAS ESPECIFICAS
        if ($trimestre == '00' or $trimestre == '01') {

            $sql = "SELECT
					maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
													fecha_solicitud >= '" . $fdesde . "' AND
													fecha_solicitud <= '" . $fhasta . "' AND
													estado = 'procesado')) AS Credito,

					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Receptora,


					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_rectificacion
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Rectificacion,


					(SELECT SUM(monto_debitar)
					 FROM partidas_disminucion_presupuesto
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 iddisminucion_presupuesto IN (SELECT iddisminucion_presupuesto
													   FROM disminucion_presupuesto
													   WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Disminucion,


					(SELECT SUM(monto_debitar)
					 FROM partidas_cedentes_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Cedentes,


					(SELECT SUM(monto_debitar)
					 FROM partidas_rectificadoras
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Rectificadora,

					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionAumento1,


					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>=$mes_inicial AND rc.mes<=$mes_final) AS MRendicionPagados1,


					(SELECT SUM(monto)
					 FROM partidas_orden_compra_servicio
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_compra_servicio IN (SELECT idorden_compra_servicio
													 FROM orden_compra_servicio
													 WHERE
														fecha_orden >= '" . $fdesde . "' AND
														fecha_orden <= '" . $fhasta . "' AND
														(estado = 'procesado' OR
														 estado = 'pagado' OR
														 estado = 'conformado' OR
														 estado = 'parcial'))) AS CompraCompromiso,


					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT op1.idorden_pago
										  FROM
											orden_pago op1
											INNER JOIN tipos_documentos td1 ON (op1.tipo = td1.idtipos_documentos AND td1.compromete = 'si')
										  WHERE
											op1.fecha_orden >= '" . $fdesde . "' AND
											op1.fecha_orden <= '" . $fhasta . "' AND
											(op1.estado = 'procesado' OR
											 op1.estado = 'pagada' OR
											 op1.estado = 'conformado' OR
											 op1.estado = 'parcial'))) AS PagoCompromiso,


					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT idorden_pago
										  FROM orden_pago
										  WHERE
											fecha_orden >= '" . $fdesde . "' AND
											fecha_orden <= '" . $fhasta . "' AND
											(estado = 'procesado' OR
											 estado = 'pagada' OR
											 estado = 'conformado' OR
											 estado = 'parcial'))) AS Causado,


					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT idorden_pago
										  FROM pagos_financieros
										  WHERE
											fecha_cheque >= '" . $fdesde . "' AND
											fecha_cheque <= '" . $fhasta . "' AND
											(estado = 'conciliado' OR estado = 'transito'))) AS Pagado,


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
					(categoria_programatica.idunidad_ejecutora = unidad_ejecutora.idunidad_ejecutora) AND
					(maestro_presupuesto.idclasificador_presupuestario = clasificador_presupuestario.idclasificador_presupuestario) AND
					(maestro_presupuesto.idcategoria_programatica = categoria_programatica.idcategoria_programatica) AND
					(maestro_presupuesto.idordinal = ordinal.idordinal) $where_ordinal $filtro
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal)
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
            $query = mysql_query($sql) or die($sql . mysql_error());
            $rows  = mysql_num_rows($query);
            for ($i = 0; $i < $rows; $i++) {
                $field                 = mysql_fetch_array($query);
                $par                   = $field["Par"];
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['Disminucion'] - $field['Cedentes'] + $field['Rectificadora'];
                }
                $_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
                $_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
                $_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
                $_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
                $_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];

            }

        } else {

            //OBTENGO LOS MONTO DEL PERIODO ANTERIOR

            $sql = "SELECT
					maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
													fecha_solicitud >= '" . $desde_inicial . "' AND
													fecha_solicitud <= '" . $hasta_anterior . "' AND
													estado = 'procesado')) AS Credito,

					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '" . $desde_inicial . "' AND
															fecha_solicitud <= '" . $hasta_anterior . "' AND
															estado = 'procesado')) AS Receptora,


					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_rectificacion
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '" . $desde_inicial . "' AND
															fecha_solicitud <= '" . $hasta_anterior . "' AND
															estado = 'procesado')) AS Rectificacion,


					(SELECT SUM(monto_debitar)
					 FROM partidas_disminucion_presupuesto
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 iddisminucion_presupuesto IN (SELECT iddisminucion_presupuesto
													   FROM disminucion_presupuesto
													   WHERE
															fecha_solicitud >= '" . $desde_inicial . "' AND
															fecha_solicitud <= '" . $hasta_anterior . "' AND
															estado = 'procesado')) AS Disminucion,


					(SELECT SUM(monto_debitar)
					 FROM partidas_cedentes_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '" . $desde_inicial . "' AND
															fecha_solicitud <= '" . $hasta_anterior . "' AND
															estado = 'procesado')) AS Cedentes,


					(SELECT SUM(monto_debitar)
					 FROM partidas_rectificadoras
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '" . $desde_inicial . "' AND
															fecha_solicitud <= '" . $hasta_anterior . "' AND
															estado = 'procesado')) AS Rectificadora,

					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionAumento1,


					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes<=$mes_anterior) AS MRendicionPagados1,


					(SELECT SUM(monto)
					 FROM partidas_orden_compra_servicio
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_compra_servicio IN (SELECT idorden_compra_servicio
													 FROM orden_compra_servicio
													 WHERE
														fecha_orden >= '" . $desde_inicial . "' AND
														fecha_orden <= '" . $hasta_anterior . "' AND
														(estado = 'procesado' OR
														 estado = 'pagado' OR
														 estado = 'conformado' OR
														 estado = 'parcial'))) AS CompraCompromiso,


					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT op1.idorden_pago
										  FROM
											orden_pago op1
											INNER JOIN tipos_documentos td1 ON (op1.tipo = td1.idtipos_documentos AND td1.compromete = 'si')
										  WHERE
											op1.fecha_orden >= '" . $desde_inicial . "' AND
											op1.fecha_orden <= '" . $hasta_anterior . "' AND
											(op1.estado = 'procesado' OR
											 op1.estado = 'pagada' OR
											 op1.estado = 'conformado' OR
											 op1.estado = 'parcial'))) AS PagoCompromiso,


					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT idorden_pago
										  FROM orden_pago
										  WHERE
											fecha_orden >= '" . $desde_inicial . "' AND
											fecha_orden <= '" . $hasta_anterior . "' AND
											(estado = 'procesado' OR
											 estado = 'pagada' OR
											 estado = 'conformado' OR
											 estado = 'parcial'))) AS Causado,


					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT idorden_pago
										  FROM pagos_financieros
										  WHERE
											fecha_cheque >= '" . $desde_inicial . "' AND
											fecha_cheque <= '" . $hasta_anterior . "' AND
											(estado = 'conciliado' OR estado = 'transito'))) AS Pagado,


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
					(categoria_programatica.idunidad_ejecutora = unidad_ejecutora.idunidad_ejecutora) AND
					(maestro_presupuesto.idclasificador_presupuestario = clasificador_presupuestario.idclasificador_presupuestario) AND
					(maestro_presupuesto.idcategoria_programatica = categoria_programatica.idcategoria_programatica) AND
					(maestro_presupuesto.idordinal = ordinal.idordinal) $where_ordinal $filtro
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal)
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
            $query = mysql_query($sql) or die($sql . mysql_error());
            $rows  = mysql_num_rows($query);
            for ($i = 0; $i < $rows; $i++) {
                $field                 = mysql_fetch_array($query);
                $par                   = $field["Par"];
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['Disminucion'] - $field['Cedentes'] + $field['Rectificadora'];
                }
                $_AUMENTO_ANTERIOR[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
                $_DISMINUCION_ANTERIOR[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
                $_COMPROMISO_ANTERIOR[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
                $_CAUSADO_ANTERIOR[$par] += $field['Causado'] + $field['MRendicionCausado1'];
                $_PAGADO_ANTERIOR[$par] += $field['Pagado'] + $field['MRendicionPagados1'];

            }

            //OBTENGO LOS VALORES DEL PERIODO SELECCIONADO
            $sql = "SELECT
					maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
													fecha_solicitud >= '" . $fdesde . "' AND
													fecha_solicitud <= '" . $fhasta . "' AND
													estado = 'procesado')) AS Credito,

					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios

														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Receptora,


					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_rectificacion
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Rectificacion,


					(SELECT SUM(monto_debitar)
					 FROM partidas_disminucion_presupuesto
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 iddisminucion_presupuesto IN (SELECT iddisminucion_presupuesto
													   FROM disminucion_presupuesto
													   WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Disminucion,


					(SELECT SUM(monto_debitar)
					 FROM partidas_cedentes_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Cedentes,


					(SELECT SUM(monto_debitar)
					 FROM partidas_rectificadoras
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '" . $fdesde . "' AND
															fecha_solicitud <= '" . $fhasta . "' AND
															estado = 'procesado')) AS Rectificadora,

					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionAumento1,


					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=maestro_presupuesto.idRegistro
							AND rc.anio='$anio_fiscal'
      						AND rc.mes>$mes_anterior AND rc.mes<=$mes_final) AS MRendicionPagados1,


					(SELECT SUM(monto)
					 FROM partidas_orden_compra_servicio
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_compra_servicio IN (SELECT idorden_compra_servicio
													 FROM orden_compra_servicio
													 WHERE
														fecha_orden >= '" . $fdesde . "' AND
														fecha_orden <= '" . $fhasta . "' AND
														(estado = 'procesado' OR
														 estado = 'pagado' OR
														 estado = 'conformado' OR
														 estado = 'parcial'))) AS CompraCompromiso,


					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT op1.idorden_pago
										  FROM
											orden_pago op1
											INNER JOIN tipos_documentos td1 ON (op1.tipo = td1.idtipos_documentos AND td1.compromete = 'si')
										  WHERE
											op1.fecha_orden >= '" . $fdesde . "' AND
											op1.fecha_orden <= '" . $fhasta . "' AND
											(op1.estado = 'procesado' OR
											 op1.estado = 'pagada' OR
											 op1.estado = 'conformado' OR
											 op1.estado = 'parcial'))) AS PagoCompromiso,


					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT idorden_pago
										  FROM orden_pago
										  WHERE
											fecha_orden >= '" . $fdesde . "' AND
											fecha_orden <= '" . $fhasta . "' AND
											(estado = 'procesado' OR
											 estado = 'pagada' OR
											 estado = 'conformado' OR
											 estado = 'parcial'))) AS Causado,


					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT idorden_pago
										  FROM pagos_financieros
										  WHERE
											fecha_cheque >= '" . $fdesde . "' AND
											fecha_cheque <= '" . $fhasta . "' AND
											(estado = 'conciliado' OR estado = 'transito'))) AS Pagado,


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
					(categoria_programatica.idunidad_ejecutora = unidad_ejecutora.idunidad_ejecutora) AND
					(maestro_presupuesto.idclasificador_presupuestario = clasificador_presupuestario.idclasificador_presupuestario) AND
					(maestro_presupuesto.idcategoria_programatica = categoria_programatica.idcategoria_programatica) AND
					(maestro_presupuesto.idordinal = ordinal.idordinal) $where_ordinal $filtro
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal)
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";

            $query = mysql_query($sql) or die($sql . mysql_error());
            $rows  = mysql_num_rows($query);
            for ($i = 0; $i < $rows; $i++) {
                $field                 = mysql_fetch_array($query);
                $par                   = $field["Par"];
                $aumento_rendicion     = 0;
                $disminucion_rendicion = 0;
                if ($field['MRendicionAumento1'] > 0) {
                    $aumento_rendicion = $field['MRendicionAumento1'] - $field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1'] > 0) {
                    $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['Disminucion'] - $field['Cedentes'] + $field['Rectificadora'];
                }
                $_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
                $_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
                $_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
                $_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
                $_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];

            }

        }

        //    CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO    O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
        $sql = "SELECT
					maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
					'0000' AS codordinal,
					'' AS nomordinal
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
					 (ordinal.codigo='0000' AND maestro_presupuesto.idordinal = ordinal.idordinal) $filtro)
				GROUP BY (Par), (Gen), (Esp), (Sesp), (codordinal)
				ORDER BY Par, Gen, Esp, Sesp, codordinal";

        $presupuestaria_anterior = 0;
        $financiera_anterior     = 0;
        $actualf                 = 0;
        $IdCategoria             = '';
        $query                   = mysql_query($sql) or die($sql . mysql_error());
        $rows                    = mysql_num_rows($query);
        for ($i = 0; $i < $rows; $i++) {
            $field = mysql_fetch_array($query);

            if ($i == 0) {
                //---------------------------------------------
                if ($trimestre == '00' or $trimestre == '01') {
                    echo "
					<table>
						<tr><td colspan='10' style='$tr3'>CONSOLIDADO GENERAL</td></tr>
						<tr><td colspan='10'>Desde: " . $idesde . " Hasta: " . $ihasta . "</td></tr>
						<tr><td colspan='10'>" . $trimestre_m . "</td></tr>
						<tr><td colspan='10'>&nbsp;</td></tr>
						<tr><td colspan='10'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
						<tr><td colspan='10'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
						<tr><td colspan='10'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
						<tr><td colspan='10'>&nbsp;</td></tr>
						<tr><td colspan='10'>&nbsp;</td></tr>
					</table>
					<table border='1'>
						<tr>
							<th width='100' style='$tr1'>PARTIDA</th>
							<th width='1000' style='$tr1'>DESCRIPCION</th>
							<th width='200' style='$tr1'>PRESUPUESTO INICIAL</th>
							<th width='200' style='$tr1'>MODIFICACION</th>
							<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
							<th width='200' style='$tr1'>COMPROMETIDO</th>
							<th width='200' style='$tr1'>CAUSADO</th>
							<th width='200' style='$tr1'>DISPONIBIBILIDAD PRESUPUESTARIA</th>
							<th width='200' style='$tr1'>PAGADO</th>
							<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
						</tr>
					</table>";
                } else {
                    echo "
							<table>
								<tr><td colspan='11' style='$tr3'>CONSOLIDADO GENERAL</td></tr>
								<tr><td colspan='11'>Desde: " . $idesde . " Hasta: " . $ihasta . "</td></tr>
								<tr><td colspan='11'>" . $trimestre_m . "</td></tr>
								<tr><td colspan='11'>&nbsp;</td></tr>
								<tr><td colspan='11'>Fuente de Financiamiento: " . $nom_fuente_financiamiento . "</td></tr>
								<tr><td colspan='11'>Tipo de Presupuesto: " . $nom_tipo_presupuesto . "</td></tr>
								<tr><td colspan='11'>A&ntilde;o Fiscal: " . $anio_fiscal . "</td></tr>
								<tr><td colspan='10'>&nbsp;</td></tr>
								<tr><td colspan='11'>&nbsp;</td></tr>
							</table>
							<table border='1'>
								<tr>
									<th width='100' style='$tr1'>PARTIDA</th>
									<th width='1000' style='$tr1'>DESCRIPCION</th>
									<th width='200' style='$tr1'>PRESUPUESTARIA ANTERIOR</th>
									<th width='200' style='$tr1'>FINANCIERA ANTERIOR</th>
									<th width='200' style='$tr1'>MODIFICACION</th>
									<th width='200' style='$tr1'>PRESUPUESTO AJUSTADO</th>
									<th width='200' style='$tr1'>COMPROMETIDO</th>
									<th width='200' style='$tr1'>CAUSADO</th>
									<th width='200' style='$tr1'>DISPONIBILIDAD PRESUPUESTARIA</th>
									<th width='200' style='$tr1'>PAGADO</th>
									<th width='200' style='$tr1'>DISPONIBILIDAD FINANCIERA</th>
								</tr>
							</table>";
                }
            }

            $l++;
            $clasificador = $field["Par"] . "." . $field["Gen"] . "." . $field["Esp"] . "." . $field["Sesp"];
            $par          = $field["Par"];
            $estilo       = $tr5;

            if ($trimestre == '00' or $trimestre == '01') {
                $formulado             = $field['Formulado'];
                $formulado_m           = number_format($field['Formulado'], 2, ',', '.');
                $modificacion          = $_AUMENTO[$par] - $_DISMINUCION[$par];
                $modificacion_m        = number_format($modificacion, 2, ',', '.');
                $actual                = $field['Formulado'] + $modificacion;
                $actual_m              = number_format($actual, 2, ',', '.');
                $total_comprometidoI   = number_format($_COMPROMISO[$par], 2, ',', '.');
                $total_causaI          = number_format($_CAUSADO[$par], 2, ',', '.');
                $disponible_compromiso = number_format(($actual - $_COMPROMISO[$par]), 2, ',', '.');
                $total_pagadoI         = number_format($_PAGADO[$par], 2, ',', '.');
                $disponible_pagado     = number_format(($actual - $_PAGADO[$par]), 2, ',', '.');

                echo "
				<table>
				<tr>
					<td style='" . $estilo . "'>" . $clasificador . "</td>
					<td style='" . $estilo . "'>" . utf8_decode($field["NomPartida"]) . "</td>
					<td style='" . $estilo . "' align='right'>" . $formulado_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $modificacion_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $actual_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_comprometidoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_causaI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_compromiso . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_pagadoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_pagado . "</td>
				</tr>
				</table>";

            } else {

                $formulado                 = $field['Formulado'];
                $formulado_m               = number_format($field['Formulado'], 2, ',', '.');
                $presupuestaria_anterior   = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_COMPROMISO_ANTERIOR[$par];
                $presupuestaria_anterior_m = number_format($presupuestaria_anterior, 2, ',', '.');
                $financiera_anterior       = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_PAGADO_ANTERIOR[$par];
                $financiera_anterior_m     = number_format($financiera_anterior, 2, ',', '.');
                $modificacion              = $_AUMENTO[$par] - $_DISMINUCION[$par];
                $modificacion_m            = number_format($modificacion, 2, ',', '.');
                $actual                    = $presupuestaria_anterior + $modificacion;
                $actual_m                  = number_format($actual, 2, ',', '.');
                $actualf                   = $financiera_anterior + $modificacion;
                $actualf_m                 = number_format($actualf, 2, ',', '.');
                $total_comprometidoI       = number_format($_COMPROMISO[$par], 2, ',', '.');
                $total_causaI              = number_format($_CAUSADO[$par], 2, ',', '.');
                $disponible_compromiso     = number_format(($actual - $_COMPROMISO[$par]), 2, ',', '.');
                $total_pagadoI             = number_format($_PAGADO[$par], 2, ',', '.');
                $disponible_pagado         = number_format(($actual - $_PAGADO[$par]), 2, ',', '.');

                echo "
				<table>
				<tr>
					<td style='" . $estilo . "'>" . $clasificador . "</td>
					<td style='" . $estilo . "'>" . utf8_decode($field["NomPartida"]) . "</td>
					<td style='" . $estilo . "' align='right'>" . $presupuestaria_anterior_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $financiera_anterior_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $modificacion_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $actual_m . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_comprometidoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_causaI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_compromiso . "</td>
					<td style='" . $estilo . "' align='right'>" . $total_pagadoI . "</td>
					<td style='" . $estilo . "' align='right'>" . $disponible_pagado . "</td>
				</tr>
				</table>";

            }

            $sum_formulado += $field['Formulado'];
            $sum_presupuestaria_anterior += $presupuestaria_anterior;
            $sum_financiera_anterior += $financiera_anterior;
            $sum_aumento += $_AUMENTO[$par];
            $sum_disminucion += $_DISMINUCION[$par];
            $sum_modificacion += $modificacion;
            $sum_actual += $actual;
            $sum_actualf += $actualf;
            $sum_compromiso += $_COMPROMISO[$par];
            $sum_causado += $_CAUSADO[$par];
            $sum_pagado += $_PAGADO[$par];
        }

        //------------------------------------------------

        if ($trimestre == '00' or $trimestre == '01') {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($sum_formulado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        } else {
            echo "<table border='1'>
				<tr>
					<td style='$tr2'>&nbsp;</td>
					<td style='$tr2'>TOTALES</td>
					<td style='$tr2' align='right'>" . number_format($sum_presupuestaria_anterior, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_financiera_anterior, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_modificacion, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_actual, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_compromiso, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_causado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actual - $sum_compromiso), 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format($sum_pagado, 2, ',', '.') . "</td>
					<td style='$tr2' align='right'>" . number_format(($sum_actualf - $sum_pagado), 2, ',', '.') . "</td>
				</tr>
				</table>";
        }

        break;
//FIN REPORTE CONSOLIDADO GENERAL

}
