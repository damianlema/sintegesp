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
require('../../fpdf.php');
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
$dias_mes[1]=31;
if ($anio_fiscal % 4 == 0) $dias_mes[2]=29; else $dias_mes[2]=28;
$dias_mes[3]=31;
$dias_mes[4]=30;
$dias_mes[5]=31;
$dias_mes[6]=30;
$dias_mes[7]=31;
$dias_mes[8]=31;
$dias_mes[9]=30;
$dias_mes[10]=31;
$dias_mes[11]=30;
$dias_mes[12]=31;
//	----------------------------------------

function busca_rango_tiempo($desde,$hasta,$mes_inicial,$mes_final,$filtro,$anio_fiscal){


	// IGUAL PROBLEMA SE PRESENTA CON LOS ORDINALES
	$sql="	(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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


//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Sectores...
	case "sector":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();

		sector($pdf);
		////////////
		$sql="SELECT codigo, denominacion FROM sector ORDER BY denominacion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array($field[0], utf8_decode($field[1])));
			$linea=$pdf->GetY(); if ($linea>250) { sector($pdf); }
		}

		break;

	//	Programas...
	case "programa":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		programa($pdf);
		////////////
		$sql="SELECT sector.denominacion, programa.codigo, programa.denominacion, sector.codigo FROM sector, programa WHERE (sector.idsector=programa.idsector) ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$sector=$field[3]." - ".$field[0];
			$pdf->Row(array(utf8_decode($sector), $field[1], utf8_decode($field[2])));
			$linea=$pdf->GetY(); if ($linea>250) { programa($pdf); }
		}
		break;

	//	Sub-Programas...
	case "sprograma":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		sprograma($pdf);
		////////////
		$sql="SELECT sector.denominacion,
					 programa.denominacion,
					 sub_programa.codigo,
					 sub_programa.denominacion,
					 sector.codigo,
					 programa.codigo
				FROM
					 sector,
					 programa,
					 sub_programa
				WHERE (sector.idsector=programa.idsector AND programa.idprograma=sub_programa.idPrograma)
				ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$sector=$field[4]." - ".$field[0];
			$progra=$field[5]." - ".$field[1];
			$pdf->Row(array(utf8_decode($sector), utf8_decode($progra), $field[2], utf8_decode($field[3])));
			$linea=$pdf->GetY(); if ($linea>250) { sprograma($pdf); }
		}
		break;

	//	Proyectos...
	case "proyecto":
		$pdf=new PDF_MC_Table('L', 'mm', 'Letter');
		$pdf->Open();
		proyecto($pdf);
		////////////
		$sql="SELECT sector.denominacion,
					 programa.denominacion,
					 sub_programa.denominacion,
					 proyecto.codigo,
					 proyecto.denominacion,
					 sector.codigo,
					 programa.codigo,
					 sub_programa.codigo
				FROM
					 sector,
					 programa,
					 sub_programa,
					 proyecto
				WHERE
					(sector.idsector=programa.idsector AND
					 programa.idprograma=sub_programa.idPrograma AND
					 sub_programa.idsub_programa=proyecto.idsub_programa)
				ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$sector=$field[5]." - ".$field[0];
			$progra=$field[6]." - ".$field[1];
			$sprogr=$field[7]." - ".$field[2];
			$pdf->Row(array(utf8_decode($sector), utf8_decode($progra), utf8_decode($sprogr), utf8_decode($field[3]), utf8_decode($field[4])));
			$linea=$pdf->GetY(); if ($linea>180) { proyecto($pdf); }
		}
		break;

	//	Actividades...
	case "actividad":
		$pdf=new PDF_MC_Table('L', 'mm', 'Legal');
		$pdf->Open();
		actividad($pdf);
		////////////
		$sql="SELECT sector.denominacion,
					 programa.denominacion,
					 sub_programa.denominacion,
					 proyecto.denominacion,
					 actividad.codigo,
					 actividad.denominacion,
					 sector.codigo,
					 programa.codigo,
					 sub_programa.codigo,
					 proyecto.codigo
				FROM
					 sector,
					 programa,
					 sub_programa,
					 proyecto,
					 actividad
				WHERE
					(sector.idsector=programa.idsector AND
					 programa.idprograma=sub_programa.idPrograma AND
					 sub_programa.idsub_programa=proyecto.idsub_programa AND
					 proyecto.idproyecto=actividad.idproyecto)
				ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$sector=$field[6]." - ".$field[0];
			$progra=$field[7]." - ".$field[1];
			$sprogr=$field[8]." - ".$field[2];
			$proyec=$field[9]." - ".$field[3];
			$pdf->Row(array(utf8_decode($sector), utf8_decode($progra), utf8_decode($sprogr), utf8_decode($proyec), $field[4], utf8_decode($field[5])));
			$linea=$pdf->GetY(); if ($linea>180) { actividad($pdf); }
		}
		break;

	//	Unidad Ejecutora...
	case "unidade":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		unidade($pdf);
		////////////
		$sql="SELECT codigo, denominacion, responsable FROM unidad_ejecutora ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array($field[0], utf8_decode($field[1]), utf8_decode($field[2])));
			$linea=$pdf->GetY(); if ($linea>250) { unidade($pdf); }
		}
		break;

	//	Categoria Programatica...
	case "catprog":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		catprog($pdf);
		////////////
		$sql="SELECT categoria_programatica.codigo,
					 unidad_ejecutora.denominacion,
					 unidad_ejecutora.responsable
				FROM
					 categoria_programatica,
					 unidad_ejecutora
				WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)
				ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Row(array($field[0], utf8_decode($field[1]), utf8_decode($field[2])));
			$linea=$pdf->GetY(); if ($linea>250) { catprog($pdf); }
		}
		break;

	//	Indice de Categorias Programaticas...
	case "icatprog":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		icatprog($pdf);
		////////////
		$sect=""; $prog=""; $sprog=""; $proy=""; $acti="";
		$sql="SELECT sector.codigo,
					 sector.denominacion,
					 programa.codigo,
					 programa.denominacion,
					 sub_programa.codigo,
					 sub_programa.denominacion,
					 proyecto.codigo,
					 proyecto.denominacion,
					 actividad.codigo,
					 actividad.denominacion,
					 sector.idSector,
					 programa.idprograma,
					 sub_programa.idsub_programa,
					 proyecto.idproyecto,
					 actividad.idActividad
				FROM
					sector,
					programa,
					sub_programa,
					proyecto,
					actividad
				WHERE
					sector.idSector=programa.idsector AND
					programa.idprograma=sub_programa.idPrograma AND
					sub_programa.idsub_programa=proyecto.idsub_programa AND
					proyecto.idproyecto=actividad.idproyecto
				ORDER BY sector.codigo, programa.codigo, sub_programa.codigo, proyecto.codigo, actividad.codigo";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 6);
			if ($sect!=$field[0]) {
				$sect=$field[0];
				$prog="";
				$sprog="";
				$pdf->Row(array($field[0], '', '', '', '', utf8_decode($field[1]), '', ''));
				$linea=$pdf->GetY(); if ($linea>250) { icatprog($pdf); }
			}
			//
			if ($sect==$field[0] && $prog!=$field[2]) {
				$prog=$field[2];
				$sprog="";
				$pdf->Row(array('', $field[2], '', '', '', utf8_decode($field[3]), '', ''));
				$linea=$pdf->GetY(); if ($linea>250) { icatprog($pdf); }
			}
			//
			if ($sect==$field[0] && $prog==$field[2] && $sprog!=$field[4]) {
				$sprog=$field[4];
				$pdf->Row(array('', '', $field[4], '', '', utf8_decode($field[5]), '', ''));
				$linea=$pdf->GetY(); if ($linea>250) { icatprog($pdf); }
			}
			$pdf->Row(array('', '', '', $field[6], '', utf8_decode($field[7]), '', ''));
			$linea=$pdf->GetY(); if ($linea>250) { icatprog($pdf); }
			//
			$sql1="SELECT categoria_programatica.idunidad_ejecutora,
						  unidad_ejecutora.denominacion,
						  unidad_ejecutora.responsable
					FROM
						  categoria_programatica,
						  unidad_ejecutora
					WHERE
						  (categoria_programatica.idsector='$field[10]' AND
						   categoria_programatica.idprograma='$field[11]' AND
						   categoria_programatica.idsub_programa='$field[12]' AND
						   categoria_programatica.idproyecto='$field[13]' AND
						   categoria_programatica.idActividad='$field[14]') AND
						  (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)";
			$query1=mysql_query($sql1) or die ($sql1.mysql_error());
			$rows1=mysql_num_rows($query1);
			if ($rows1!=0) { $field1=mysql_fetch_array($query1); }
			$pdf->Row(array('', '', '', '', $field[8], utf8_decode($field[9]), $field1[1], utf8_decode($field1[2])));
			$linea=$pdf->GetY(); if ($linea>250) { icatprog($pdf); }
		}
		break;

	//	Clasificador Presupuestario...
	case "clapre":
		$pdf=new PDF_MC_Table2('P', 'mm', 'Letter');
		$pdf->Open();
		clapre($pdf);
		////////////
		$sql="SELECT partida, generica, especifica, sub_especifica, denominacion FROM clasificador_presupuestario ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			//
			if ($field[1]=="00" && $field[2]=="00" && $field[3]=="00") {
				$pdf->SetHeight(array(4));
				$pdf->SetFillColor(200, 200, 200);
				$pdf->SetFont('Arial', '', 6);
			}
			elseif ($field[2]=="00" && $field[3]=="00") {
				$pdf->SetHeight(array(4));
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetFont('Arial', 'B', 6); }
			else {
				$pdf->SetHeight(array(3));
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetFont('Arial', '', 6);
			}
			$pdf->Row(array($field[0], $field[1], $field[2], $field[3], utf8_decode($field[4])));
			$linea=$pdf->GetY(); if ($linea>250) { clapre($pdf); }
		}
		break;

	//	Ordinales...
	case "ordinal":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		ordinal($pdf);
		////////////
		$sql="SELECT codigo, denominacion FROM ordinal ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array($field[0], utf8_decode($field[1])));
			$linea=$pdf->GetY(); if ($linea>250) { ordinal($pdf); }
		}
		break;

	//	Tipos de Presupuesto...
	case "tipopre":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		tipopre($pdf);
		////////////
		$sql="SELECT denominacion FROM tipo_presupuesto ORDER BY denominacion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0])));
			$linea=$pdf->GetY(); if ($linea>250) { tipopre($pdf); }
		}
		break;

	//	Fuentes de Financiamiento...
	case "fuentes":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		fuentes($pdf);
		////////////
		$sql="SELECT denominacion FROM fuente_financiamiento ORDER BY denominacion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0])));
			$linea=$pdf->GetY(); if ($linea>250) { fuentes($pdf); }
		}
		break;

	//	Presupuesto Original...
	case "preori":
		$pdf=new PDF_MC_Table5('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
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
		preori($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto);
		$sum_total = 0;
		$filtro = "";
		if ($anio_fiscal != "") $filtro .= " AND (maestro_presupuesto.anio = '".$anio_fiscal."')";
		if ($financiamiento != "") $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."')";
		if ($tipo_presupuesto != "") $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."')";
		if ($idcategoria_programatica != "") $filtro .= " AND (maestro_presupuesto.idcategoria_programatica = '".$idcategoria_programatica."')";
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

		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"] != $IdCategoria) {
				$IdCategoria = $field["IdCategoria"];
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(205, 5, utf8_decode($field["CodCategoria"]." - ".$field["Unidad"]), 1, 1, 'L', 1);
				$linea=$pdf->GetY(); if ($linea>250) { preori($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto); }
			}
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$monto_original=number_format($field["MontoOriginal"], 2, ',', '.');
			if ($field["Tipo"]=="partida") {
				$sum_total+=$field["MontoOriginal"];
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->SetHeight(array(5));
				$pdf->Row(array($clasificador, utf8_decode($field["NomPartida"]), $monto_original));
			}
			else if ($field["Tipo"]=="generica") {
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->SetHeight(array(5));
				$pdf->Row(array($clasificador, utf8_decode($field["NomPartida"]), $monto_original));
			}
			else if ($field["Tipo"]=="especifica") {
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 7);
				$pdf->SetHeight(array(3));
				if ($field['codordinal'] == '0000')
					$pdf->Row(array($clasificador, utf8_decode($field["NomPartida"]), $monto_original));
				else {
					$pdf->Row(array($clasificador, utf8_decode($field['codordinal'].' '.$field["nomordinal"]), $monto_original));
				}
			}
			$linea=$pdf->GetY(); if ($linea>250) { preori($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto); }
		}
		//---------------------------------------------
		//	IMPRIMO EL TOTAL
		$sum_total=number_format($sum_total, 2, ',', '.');
		//---------------------------------------------
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=205; $pdf->Rect($x, $y+2, $w, $h);
		$pdf->SetXY($x, $y+5);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetAligns(array('C', 'L', 'R'));
		$pdf->SetWidths(array(20, 150, 35));
		$pdf->SetHeight(array(5));
		$pdf->Row(array('', '', $sum_total));
		break;

	//	Resumen por Categorias...
	case "preres":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
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
		preres($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos);
		$filtro = "";
		if ($anio_fiscal != "") $filtro .= " AND (maestro_presupuesto.anio = '".$anio_fiscal."')";
		if ($financiamiento != "") $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."')";
		if ($tipo_presupuesto != "") $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."')";
		if ($idcategoria_programatica != "") $filtro .= " AND (maestro_presupuesto.idcategoria_programatica = '".$idcategoria_programatica."')";
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
		$query = mysql_query($sql) or die ($sql.mysql_error());


		while ($field = mysql_fetch_array($query)) {
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$formulado=number_format($field["Formulado"], 2, ',', '.');
			$modificado = number_format(($field['TotalAumento'] - $field['TotalDisminucion']), 2, ',', '.');
			$monto_actual = $field['Formulado'] + $field['TotalAumento'] - $field['TotalDisminucion'];
			$actual=number_format($monto_actual, 2, ',', '.');
			$compromiso=number_format($field["Compromiso"], 2, ',', '.');
			$reservado=number_format($field["ReservadoDisminuir"], 2, ',', '.');
			$precompromiso=number_format($field["PreCompromiso"], 2, ',', '.');
			$causado=number_format($field["Causado"], 2, ',', '.');
			$pagado=number_format($field["Pagado"], 2, ',', '.');
			$aumento=number_format($field["TotalAumento"], 2, ',', '.');
			$disminucion=number_format($field["TotalDisminucion"], 2, ',', '.');


			if ($chkrestar) $resta_disponible = $monto_actual-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"];
			else $resta_disponible = $monto_actual-$field["Compromiso"] - $field["ReservadoDisminuir"];


			$disponible=number_format($resta_disponible, 2, ',', '.');
			if ($field["Compromiso"]==0 or $field["Actual"]==0) $pcompromiso="0"; else $pcompromiso=(float) (($field["Compromiso"]*100)/$monto_actual); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
			if ($field["Causado"]==0 or $monto_actual==0) $pcausado="0"; else $pcausado=(float) (($field["Causado"]*100)/$monto_actual); $pcausado=number_format($pcausado, 2, ',', '.');
			if ($field["Pagado"]==0 or $monto_actual==0) $ppagado="0"; else $ppagado=(float) (($field["Pagado"]*100)/$monto_actual); $ppagado=number_format($ppagado, 2, ',', '.');
			if ($field["Compromiso"]==0 or $monto_actual==0) $pdisponible="0"; else
			if ($resta_disponible==0) $pdisponible="0"; else $pdisponible=(float) ((($monto_actual-$field["Compromiso"])*100)/$monto_actual);
			$pdisponible=number_format($pdisponible, 2, ',', '.');
			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"]!=$IdCategoria) {
				$IdCategoria=$field["IdCategoria"];
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(205, 7, utf8_decode($field["CodCategoria"]." - ".$field["Unidad"]), 1, 1, 'L', 1);
				$linea=$pdf->GetY(); if ($linea>250) { preres($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos); $pdf->SetY(57); }
			}
			if ($field["Tipo"]=="partida") {
				if (($chksobregiradas && $resta_disponible < 0) || !$chksobregiradas) {
					$sum_formulado+=$field["Formulado"];
					$resta_modificado = $field['TotalAumento'] - $field['TotalDisminucion'];
					//$resta_modificado = bcsub($field['TotalAumento'] - $field['TotalDisminucion'], 2);
					$sum_modificado += $resta_modificado;
					$sum_actual+=$monto_actual;
					$sum_compromiso+=$field["Compromiso"];
					$sum_precompromiso+=$field["PreCompromiso"];
					$sum_causado+=$field["Causado"];
					$sum_pagado+=$field["Pagado"];
					$sum_aumento+=$field["TotalAumento"];
					$sum_disminucion+=$field["TotalDisminucion"];
					$sum_reservado+=$field["ReservadoDisminuir"];
					$sum_disponible+=$resta_disponible;
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 6);
					$y=$pdf->GetY();
					$x=5;
					$nb=$pdf->NbLines(27, utf8_decode($field["NomPartida"])); $hf=3*$nb;
					$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $clasificador, 0, 1, 'C', 1); $x+=20;
					$pdf->SetXY($x, $y); $pdf->MultiCell(27, 3, utf8_decode($field["NomPartida"]), 0, 'L', 1); $x+=27;
					if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $formulado, 0, 1, 'R', 1); $x+=23; }
					if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $aumento, 0, 1, 'R', 1); $x+=23; }
					if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disminucion, 0, 1, 'R', 1); $x+=23; }
					if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $modificado, 0, 1, 'R', 1); $x+=23; }
					if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $actual, 0, 1, 'R', 1); $x+=23; }
					if ($campos[10]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $reservado, 0, 1, 'R', 1); $x+=23; }
					if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $precompromiso, 0, 1, 'R', 1); $x+=23; }
					if ($campos[6]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $compromiso, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcompromiso.' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[7]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $causado, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado.' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[8]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $pagado, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $ppagado.' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[9]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disponible, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pdisponible.' %', 0, 1, 'R', 1); $x+=11;
					}
					$pdf->Ln(2);
				}
			}
			else if ($field["Tipo"]=="generica") {
				if (($chksobregiradas && $resta_disponible < 0) || !$chksobregiradas) {
					$h++;
					if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
					else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
					$pdf->SetFont('Arial', 'B', 6);
					$y=$pdf->GetY();
					$x=5;
					$nb=$pdf->NbLines(27, utf8_decode($field["NomPartida"])); $hf=3*$nb;
					$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $clasificador, 0, 1, 'C', 1); $x+=20;
					$pdf->SetXY($x, $y); $pdf->MultiCell(27, 3, utf8_decode($field["NomPartida"]), 0, 'L', 1); $x+=27;
					if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $formulado, 0, 1, 'R', 1); $x+=23; }
					if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $aumento, 0, 1, 'R', 1); $x+=23; }
					if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disminucion, 0, 1, 'R', 1); $x+=23; }
					if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $modificado, 0, 1, 'R', 1); $x+=23; }
					if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $actual, 0, 1, 'R', 1); $x+=23; }
					if ($campos[10]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $reservado, 0, 1, 'R', 1); $x+=23; }
					if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $precompromiso, 0, 1, 'R', 1); $x+=23; }
					if ($campos[6]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $compromiso, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcompromiso.' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[7]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $causado, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado.' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[8]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $pagado, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $ppagado.' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[9]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disponible, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pdisponible.' %', 0, 1, 'R', 1); $x+=11;
					}
					$pdf->Ln(2);
				}
			}
			else if ($field["Tipo"]=="especifica") {
				if (($chksobregiradas && $resta_disponible < 0) || !$chksobregiradas) {
					$h++;
					if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
					else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
					$pdf->SetFont('Arial', '', 6);
					if ($field['codordinal'] == "0000") {
						$y=$pdf->GetY();
						$x=5;
						$nb=$pdf->NbLines(27, utf8_decode($field["NomPartida"])); $hf=3*$nb;
						$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $clasificador, 0, 1, 'C', 1); $x+=20;
						$pdf->SetXY($x, $y); $pdf->MultiCell(27, 3, utf8_decode($field["NomPartida"]), 0, 'L', 1); $x+=27;
						if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $formulado, 0, 1, 'R', 1); $x+=23; }
						if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $aumento, 0, 1, 'R', 1); $x+=23; }
						if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disminucion, 0, 1, 'R', 1); $x+=23; }
						if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $modificado, 0, 1, 'R', 1); $x+=23; }
						if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $actual, 0, 1, 'R', 1); $x+=23; }
						if ($campos[10]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $reservado, 0, 1, 'R', 1); $x+=23; }
						if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $precompromiso, 0, 1, 'R', 1); $x+=23; }
						if ($campos[6]) {
							$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $compromiso, 0, 1, 'R', 1); $x+=23;
							$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcompromiso.' %', 0, 1, 'R', 1); $x+=11;
						}
						if ($campos[7]) {
							$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $causado, 0, 1, 'R', 1); $x+=23;
							$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado.' %', 0, 1, 'R', 1); $x+=11;
						}
						if ($campos[8]) {
							$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $pagado, 0, 1, 'R', 1); $x+=23;
							$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $ppagado.' %', 0, 1, 'R', 1); $x+=11;
						}
						if ($campos[9]) {
							$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disponible, 0, 1, 'R', 1); $x+=23;
							$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pdisponible.' %', 0, 1, 'R', 1); $x+=11;
						}
						$pdf->Ln(2);
					} else {
						$y=$pdf->GetY();
						$x=5;
						$nb=$pdf->NbLines(27, utf8_decode($field['codordinal'].' '.$field["nomordinal"])); $hf=3*$nb;
						$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $clasificador, 0, 1, 'C', 1); $x+=20;
						$pdf->SetXY($x, $y); $pdf->MultiCell(27, 3, utf8_decode($field['codordinal'].' '.$field["nomordinal"]), 0, 'L', 1); $x+=27;
						if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $formulado, 0, 1, 'R', 1); $x+=23; }
						if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $aumento, 0, 1, 'R', 1); $x+=23; }
						if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disminucion, 0, 1, 'R', 1); $x+=23; }
						if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $modificado, 0, 1, 'R', 1); $x+=23; }
						if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $actual, 0, 1, 'R', 1); $x+=23; }
						if ($campos[10]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $reservado, 0, 1, 'R', 1); $x+=23; }
						if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $precompromiso, 0, 1, 'R', 1); $x+=23; }
						if ($campos[6]) {
							$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $compromiso, 0, 1, 'R', 1); $x+=23;
							$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcompromiso.' %', 0, 1, 'R', 1); $x+=11;
						}
						if ($campos[7]) {
							$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $causado, 0, 1, 'R', 1); $x+=23;
							$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado.' %', 0, 1, 'R', 1); $x+=11;
						}
						if ($campos[8]) {
							$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $pagado, 0, 1, 'R', 1); $x+=23;
							$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $ppagado.' %', 0, 1, 'R', 1); $x+=11;
						}
						if ($campos[9]) {
							$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disponible, 0, 1, 'R', 1); $x+=23;
							$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pdisponible.' %', 0, 1, 'R', 1); $x+=11;
						}
						$pdf->Ln(2);
					}
				}
			}
			$linea=$pdf->GetY(); if ($linea>175) { preres($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos); $pdf->SetY(44); }
			$formulado=""; $actual=""; $precompromiso=""; $compromiso=""; $pcompromiso=""; $causado=""; $pcausado=""; $pagado=""; $ppagado=""; $disponible=""; $pdisponible="";
		}
		//------------------------------------------------
		if ($sum_compromiso == 0 or $sum_actual == 0) $tpcompromiso = 0; else $tpcompromiso=(float) (($sum_compromiso*100)/$sum_actual); $tpcompromiso=number_format($tpcompromiso, 2, ',', '.');
		if ($sum_causado == 0 or $sum_actual) $tpcausado = 0; else $tpcausado=(float) (($sum_causado*100)/$sum_actual); $tpcausado=number_format($tpcausado, 2, ',', '.');
		if ($sum_pagado == 0 or $sum_actual == 0) $tppagado = 0; else $tppagado=(float) (($sum_pagado*100)/$sum_actual); $tppagado=number_format($tppagado, 2, ',', '.');
		if ($sum_disponible == 0 or $sum_actual == 0) $tpdisponible = 0; else $tpdisponible=(float) (($sum_disponible*100)/$sum_actual); $tpdisponible=number_format($tpdisponible, 2, ',', '.');
		$sum_formulado=number_format($sum_formulado, 2, ',', '.');
		$sum_modificado=number_format($sum_modificado, 2, ',', '.');
		$sum_actual=number_format($sum_actual, 2, ',', '.');
		$sum_reservado=number_format($sum_reservado, 2, ',', '.');
		$sum_precompromiso=number_format($sum_precompromiso, 2, ',', '.');
		$sum_compromiso=number_format($sum_compromiso, 2, ',', '.');
		$sum_causado=number_format($sum_causado, 2, ',', '.');
		$sum_pagado=number_format($sum_pagado, 2, ',', '.');
		$sum_aumento=number_format($sum_aumento, 2, ',', '.');
		$sum_disminucion=number_format($sum_disminucion, 2, ',', '.');
		$sum_disponible=number_format($sum_disponible, 2, ',', '.');
		//	IMPRIMO LOS TOTALES
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
		$pdf->SetXY($x, $y+5);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);

		$y=$pdf->GetY();
		$x=52;
		if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_formulado, 0, 1, 'R', 1); $x+=23; }
		if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_aumento, 0, 1, 'R', 1); $x+=23; }
		if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_disminucion, 0, 1, 'R', 1); $x+=23; }
		if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_modificado, 0, 1, 'R', 1); $x+=23; }
		if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_actual, 0, 1, 'R', 1); $x+=23; }
		if ($campos[10]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_reservado, 0, 1, 'R', 1); $x+=23; }
		if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_precompromiso, 0, 1, 'R', 1); $x+=23; }
		if ($campos[6]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_compromiso, 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tpcompromiso.' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[7]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_causado, 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tpcausado.' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[8]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_pagado, 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tppagado.' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[9]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_disponible, 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tpdisponible.' %', 0, 1, 'R', 1); $x+=11;
		}
		break;

	//	Resumen Consolidado...
	case "consolidado":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
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
		$desde_anterior = "$anio_fiscal-01-01";
		list($a, $m, $d)=SPLIT( '[/.-]', $_GET['desde']); $m=(int) $m; $d=(int) $d;
		$mes[1]=31; $mes[3]=31; $mes[4]=30; $mes[5]=31; $mes[6]=30; $mes[7]=31; $mes[8]=31; $mes[9]=30; $mes[10]=31; $mes[11]=30; $mes[12]=31;
		if ($a%4==0) $mes[2]=29; else $mes[2]=28;
		$dia_anterior=$d-1;
		if ($dia_anterior==0) { $mes_anterior=$m-1; $dia_anterior=$mes[$mes_anterior]; if ($mes_anterior<10) $mes_anterior="0".$mes_anterior; if ($dia_anterior<10) $dia_anterior="0".$dia_anterior; if ($mes_anterior==0 || $dia_anterior==0) $hasta_anterior="$anio_fiscal-01-01"; else $hasta_anterior=$a."-".$mes_anterior."-".$dia_anterior; }
		else { if ($m<10) $m="0".$m; if ($dia_anterior<10) $dia_anterior="0".$dia_anterior; if ($m==0 || $dia_anterior==0) $hasta_anterior="$anio_fiscal-01-01"; else $hasta_anterior=$a."-".$m."-".$dia_anterior; }
		//---------------------------------------------
		consolidado($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos, $fdesde, $fhasta);
		$filtro = "";
		if ($anio_fiscal != "") $filtro .= " AND (maestro_presupuesto.anio = '".$anio_fiscal."')";
		if ($financiamiento != "") $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."')";
		if ($tipo_presupuesto != "") $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."')";
		//	CONSULTO TODO PARA SUMAR LAS ESPECIFICAS
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
													fecha_solicitud >= '".$fdesde."' AND
													fecha_solicitud <= '".$fhasta."' AND
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
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
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
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
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
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
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
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
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
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
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
														(estado = 'procesado' OR
														 estado = 'pagado' OR
														 estado = 'conformado' OR
														 estado = 'parcial'))) AS CompraCompromisoAnterior,

					(SELECT SUM(monto)
					 FROM partidas_orden_compra_servicio
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_compra_servicio IN (SELECT idorden_compra_servicio
													 FROM orden_compra_servicio
													 WHERE
														fecha_orden >= '".$fdesde."' AND
														fecha_orden <= '".$fhasta."' AND
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
											op1.fecha_orden >= '".$desde_anterior."' AND
											op1.fecha_orden <= '".$hasta_anterior."' AND
											(op1.estado = 'procesado' OR
											 op1.estado = 'pagada' OR
											 op1.estado = 'conformado' OR
											 op1.estado = 'parcial'))) AS PagoCompromisoAnterior,

					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT op1.idorden_pago
										  FROM
											orden_pago op1
											INNER JOIN tipos_documentos td1 ON (op1.tipo = td1.idtipos_documentos AND td1.compromete = 'si')
										  WHERE
											op1.fecha_orden >= '".$fdesde."' AND
											op1.fecha_orden <= '".$fhasta."' AND
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
											fecha_orden >= '".$desde_anterior."' AND
											fecha_orden <= '".$hasta_anterior."' AND
											(estado = 'procesado' OR
											 estado = 'pagada' OR
											 estado = 'conformado' OR
											 estado = 'parcial'))) AS CausadoAnterior,

					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT idorden_pago
										  FROM orden_pago
										  WHERE
											fecha_orden >= '".$fdesde."' AND
											fecha_orden <= '".$fhasta."' AND
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
											fecha_cheque >= '".$fdesde."' AND
											fecha_cheque <= '".$fhasta."' AND
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
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
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
					(categoria_programatica.idunidad_ejecutora = unidad_ejecutora.idunidad_ejecutora) AND
					(maestro_presupuesto.idclasificador_presupuestario = clasificador_presupuestario.idclasificador_presupuestario) AND
					(maestro_presupuesto.idcategoria_programatica = categoria_programatica.idcategoria_programatica) AND
					(maestro_presupuesto.idordinal = ordinal.idordinal) $where_ordinal $filtro
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal)
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			$par = $field["Par"];
			$_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'];
			$_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'];
			$_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'];
			$_RESERVADO[$par] += $field['ReservadoDisminucion'];
			$_CAUSADO[$par] += $field['Causado'];
			$_PAGADO[$par] += $field['Pagado'];
			$_AUMENTO_ANTERIOR[$par] += $field['CreditoAnterior'] + $field['ReceptoraAnterior'] + $field['RectificacionAnterior'];
			$_DISMINUCION_ANTERIOR[$par] += $field['DisminucionAnterior'] + $field['CedentesAnterior'] + $field['RectificadoraAnterior'];
			$_COMPROMISO_ANTERIOR[$par] += $field['CompraCompromisoAnterior'] + $field['PagoCompromisoAnterior'];
			$_RESERVADO_ANTERIOR[$par] += $field['ReservadoDisminucionAnterior'];
			$_CAUSADO_ANTERIOR[$par] += $field['CausadoAnterior'];
			$_PAGADO_ANTERIOR[$par] += $field['PagadoAnterior'];
		}

		//	CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO	O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
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
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			$l++;
			$clasificador = $field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$par = $field["Par"];

			$modificacion_anterior = $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par];
			$actual_anterior = $field['Formulado'] + $modificacion_anterior;
			$disponible_anterior = $actual_anterior - $_COMPROMISO_ANTERIOR[$par] - $_RESERVADO_ANTERIOR[$par];

			if ($_COMPROMISO_ANTERIOR[$par] != 0 && $actual_anterior != 0) $pcompromiso_anterior = $_COMPROMISO_ANTERIOR[$par] * 100 / $actual_anterior; else $pcompromiso_anterior = 0;
			if ($_CAUSADO_ANTERIOR[$par] != 0 && $actual_anterior != 0) $pcausado_anterior = $_CAUSADO_ANTERIOR[$par] * 100 / $actual_anterior; else $pcausado_anterior = 0;
			if ($_PAGADO_ANTERIOR[$par] != 0 && $actual_anterior != 0) $ppagado_anterior = $_PAGADO_ANTERIOR[$par] * 100 / $actual_anterior; else $ppagado_anterior = 0;
			if ($disponible_anterior != 0 && $actual_anterior != 0) $pdisponible_anterior = $disponible_anterior * 100 / $actual_anterior; else $pdisponible_anterior = 0;

			$modificacion = $_AUMENTO[$par] - $_DISMINUCION[$par];
			$actual = $actual_anterior + $modificacion;
			$disponible = $actual - $_COMPROMISO[$par] - $_RESERVADO[$par];

			if ($_COMPROMISO[$par] != 0 && $actual != 0) $pcompromiso = $_COMPROMISO[$par] * 100 / $actual; else $pcompromiso = 0;
			if ($_CAUSADO[$par] != 0 && $actual != 0) $pcausado = $_CAUSADO[$par] * 100 / $actual; else $pcausado = 0;
			if ($_PAGADO[$par] != 0 && $actual != 0) $ppagado = $_PAGADO[$par] * 100 / $actual; else $ppagado = 0;
			if ($disponible != 0 && $actual != 0) $pdisponible = $disponible * 100 / $actual; else $pdisponible = 0;

			$pdf->SetFillColor(225, 225, 225);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 6);

			if (($chksobregiradas && $disponible < 0) || !$chksobregiradas) {
				//	imprimo la partida
				$y=$pdf->GetY();
				$x=5;
				$nb=$pdf->NbLines(73, utf8_decode($field["NomPartida"])); $hf=3*$nb;
				$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $clasificador, 0, 1, 'C', 1); $x+=20;
				$pdf->SetXY($x, $y); $pdf->MultiCell(323, 3, utf8_decode($field["NomPartida"]), 0, 'L', 1);
				$pdf->Ln(2);

				$pdf->SetFillColor(255, 255, 255);
				//	imprimo el periodo anterior
				$y=$pdf->GetY();
				$x=5;
				$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, '', 0, 1, 'C', 1); $x+=20;
				$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, 'Hasta el: '.formatoFecha($hasta_anterior), 0, 'R', 1); $x+=50;
				if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($field['Formulado'], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
				if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_AUMENTO_ANTERIOR[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
				if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_DISMINUCION_ANTERIOR[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
				if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($modificacion_anterior, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
				if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($actual_anterior, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
				if ($campos[10]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_RESERVADO_ANTERIOR[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
				if ($campos[6]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_COMPROMISO_ANTERIOR[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pcompromiso_anterior, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[7]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_CAUSADO_ANTERIOR[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pcausado_anterior, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[8]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_PAGADO_ANTERIOR[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($ppagado_anterior, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[9]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($disponible_anterior, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pdisponible_anterior, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
				}
				$pdf->Ln(2);

				//	imprimo el periodo seleccionado
				$y=$pdf->GetY();
				$x=5;
				$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, '', 0, 1, 'C', 1); $x+=20;
				$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, 'De '.formatoFecha($fdesde).' a '.formatoFecha($fhasta), 0, 'R', 1); $x+=50;
				if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
				if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_AUMENTO[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
				if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_DISMINUCION[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
				if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($modificacion, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
				if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($actual, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
				if ($campos[10]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_RESERVADO[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
				if ($campos[6]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_COMPROMISO[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pcompromiso, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[7]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_CAUSADO[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pcausado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[8]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_PAGADO[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($ppagado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[9]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($disponible, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pdisponible, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
				}
				$pdf->Ln(2);
				$linea=$pdf->GetY(); if ($linea>175) consolidado($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos, $fdesde, $fhasta);

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
				$sum_aumento_anterior += $_AUMENTO_ANTERIOR[$par];
				$sum_disminucion_anterior += $_DISMINUCION_ANTERIOR[$par];
				$sum_modificacion_anterior += $modificacion_anterior;
				$sum_actual_anterior += $actual;
				$sum_reservado_anterior += $_RESERVADO_ANTERIOR[$par];
				$sum_compromiso_anterior += $_COMPROMISO_ANTERIOR[$par];
				$sum_causado_anterior += $_CAUSADO_ANTERIOR[$par];
				$sum_pagado_anterior += $_PAGADO_ANTERIOR[$par];
				$sum_disponible_anterior += $disponible_anterior;
			}
		}
		//------------------------------------------------
		if ($sum_compromiso != 0 && $sum_actual != 0) $tpcompromiso = $sum_compromiso * 100 / $sum_actual; else $tpcompromiso = 0;
		if ($sum_causado != 0 && $sum_actual != 0) $tpcausado = $sum_causado * 100 / $sum_actual; else $tpcausado = 0;
		if ($sum_pagado != 0 && $sum_actual != 0) $tppagado = $sum_pagado * 100 / $sum_actual; else $tppagado = 0;
		if ($sum_disponible != 0 && $sum_actual != 0) $tpdisponible = $sum_disponible * 100 / $sum_actual; else $tpdisponible = 0;

		//	IMPRIMO LOS TOTALES
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
		$pdf->SetXY($x, $y+5);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);

		$y=$pdf->GetY();
		$x=75;
		if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_formulado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_aumento, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disminucion, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_modificacion, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_actual, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[10]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_reservado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[6]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_compromiso, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpcompromiso, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[7]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_causado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpcausado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[8]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_pagado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tppagado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;

		}
		if ($campos[9]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disponible, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpdisponible, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		break;

	//	Consolidado por Categorias...
	case "porsector":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
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
		porsector($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $fdesde, $fhasta);
		$filtro = "";
		if ($anio_fiscal != "") $filtro .= " AND (maestro_presupuesto.anio = '".$anio_fiscal."')";
		if ($financiamiento != "") $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."')";
		if ($tipo_presupuesto != "") $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."')";
		if ($idcategoria_programatica != "") $filtro .= " AND (maestro_presupuesto.idcategoria_programatica='".$idcategoria_programatica."')";
		//---------------------------------------------
		//	CONSULTO TODO PARA SUMAR LAS ESPECIFICAS
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
													fecha_solicitud >= '".$fdesde."' AND
													fecha_solicitud <= '".$fhasta."' AND
													estado = 'procesado')) AS Credito,

					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
															estado = 'procesado')) AS Receptora,

					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_rectificacion
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
															estado = 'procesado')) AS Rectificacion,

					(SELECT SUM(monto_debitar)
					 FROM partidas_disminucion_presupuesto
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 iddisminucion_presupuesto IN (SELECT iddisminucion_presupuesto
													   FROM disminucion_presupuesto
													   WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
															estado = 'procesado')) AS Disminucion,

					(SELECT SUM(monto_debitar)
					 FROM partidas_cedentes_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
															estado = 'procesado')) AS Cedentes,

					(SELECT SUM(monto_debitar)
					 FROM partidas_rectificadoras
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
															estado = 'procesado')) AS Rectificadora,

					(SELECT SUM(monto)
					 FROM partidas_orden_compra_servicio
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_compra_servicio IN (SELECT idorden_compra_servicio
													 FROM orden_compra_servicio
													 WHERE
														fecha_orden >= '".$fdesde."' AND
														fecha_orden <= '".$fhasta."' AND
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
											op1.fecha_orden >= '".$fdesde."' AND
											op1.fecha_orden <= '".$fhasta."' AND
											(op1.estado = 'procesado' OR op1.estado = 'pagado'))) AS PagoCompromiso,

					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT idorden_pago
										  FROM orden_pago
										  WHERE
											fecha_orden >= '".$fdesde."' AND
											fecha_orden <= '".$fhasta."' AND
											(estado = 'procesado' OR estado = 'pagada'))) AS Causado,

					(SELECT SUM(monto)
					 FROM partidas_orden_pago
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idorden_pago IN (SELECT idorden_pago
										  FROM pagos_financieros
										  WHERE
											fecha_cheque >= '".$fdesde."' AND
											fecha_cheque <= '".$fhasta."' AND
											(estado = 'conciliado' OR estado = 'transito'))) AS Pagado,

					(SELECT SUM(monto_debitar)
					 FROM partidas_disminucion_presupuesto
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 iddisminucion_presupuesto IN (SELECT iddisminucion_presupuesto
													   FROM disminucion_presupuesto
													   WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
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
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			$par = $field["CodCategoria"].$field["Par"];
			$_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'];
			$_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'];
			$_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'];
			$_RESERVADO[$par] += $field['ReservadoDisminucion'];
			$_CAUSADO[$par] += $field['Causado'];
			$_PAGADO[$par] += $field['Pagado'];
		}

		//	CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO	O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
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
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field = mysql_fetch_array($query);
			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"]!=$IdCategoria) {
				$IdCategoria=$field["IdCategoria"];
				$l=1;
				if ($i!=0) {
					$y=$pdf->GetY();
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
					$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
					//	IMPRIMO LOS TOTALES
					if ($sum_compromiso != 0 && $sum_actual != 0) $tpcompromiso = $sum_compromiso * 100 / $sum_actual; else $tpcompromiso = 0;
					if ($sum_causado != 0 && $sum_actual != 0) $tpcausado = $sum_causado * 100 / $sum_actual; else $tpcausado = 0;
					if ($sum_pagado != 0 && $sum_actual != 0) $tppagado = $sum_pagado * 100 / $sum_actual; else $tppagado = 0;
					if ($sum_disponible != 0 && $sum_actual != 0) $tpdisponible = $sum_disponible * 100 / $sum_actual; else $tpdisponible = 0;

					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetXY($x, $y+5);

					$y=$pdf->GetY();
					$x=98;

					if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_formulado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_aumento, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disminucion, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_modificado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_actual, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[10]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_reservado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[6]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_compromiso, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpcompromiso, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[7]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_causado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpcausado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[8]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_pagado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tppagado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[9]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disponible, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpdisponible, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
					}
				}
				$sum_formulado = 0; $sum_aumento = 0; $sum_disminucion = 0; $sum_modificado = 0; $sum_actual = 0;
				$sum_compromiso = 0; $sum_causado = 0; $sum_pagado = 0; $sum_disponible = 0;

				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(205, 7, $field["CodCategoria"].'    '.$field["Unidad"], 1, 1, 'L', 1);
				$linea=$pdf->GetY(); if ($linea>250) { porsector($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $fdesde, $fhasta); }

				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(100, 100, 100); $pdf->SetTextColor(255, 255, 255);
				$pdf->SetFont('Arial', 'B', 5);
				$pdf->Cell(20, 4, 'Partida', 0, 0, 'C', 1);
				$pdf->Cell(50, 4, 'Descripcion', 0, 0, 'L', 1);
				if ($campos[0]) $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
				if ($campos[1]) $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
				if ($campos[2]) $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
				if ($campos[3]) $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
				if ($campos[4]) $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
				if ($campos[10]) $pdf->Cell(23, 4, 'Reservado Disminuir', 0, 0, 'R', 1);
				if ($campos[6]) { $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1); }
				if ($campos[7]) { $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1); }
				if ($campos[8]) { $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1); }
				if ($campos[9]) { $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1); }
				$pdf->Ln(4);
			}

			$l++;
			$clasificador = $field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$par = $field['CodCategoria'].$field["Par"];

			$modificacion = $_AUMENTO[$par] - $_DISMINUCION[$par];
			$actual = $field['Formulado'] + $modificacion;
			$disponible = $actual - $_COMPROMISO[$par] - $_RESERVADO[$par];

			if ($_COMPROMISO[$par] != 0 && $actual != 0) $pcompromiso = $_COMPROMISO[$par] * 100 / $actual; else $pcompromiso = 0;
			if ($_CAUSADO[$par] != 0 && $actual != 0) $pcausado = $_CAUSADO[$par] * 100 / $actual; else $pcausado = 0;
			if ($_PAGADO[$par] != 0 && $actual != 0) $ppagado = $_PAGADO[$par] * 100 / $actual; else $ppagado = 0;
			if ($disponible != 0 && $actual != 0) $pdisponible = $disponible * 100 / $actual; else $pdisponible = 0;

			if ($l%2 == 0) $pdf->SetFillColor(255, 255, 255); else $pdf->SetFillColor(225, 225, 225);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 6);

			$y=$pdf->GetY();
			$x=5;
			$nb=$pdf->NbLines(50, utf8_decode($field["NomPartida"])); $hf=3*$nb;
			$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $clasificador, 0, 1, 'C', 1); $x+=20;
			$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, utf8_decode($field["NomPartida"]), 0, 'L', 1); $x+=50;
			if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($field['Formulado'], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_AUMENTO[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_DISMINUCION[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($modificacion, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($actual, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[10]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_RESERVADO[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[6]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_COMPROMISO[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pcompromiso, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[7]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_CAUSADO[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pcausado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[8]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_PAGADO[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($ppagado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[9]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($disponible, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pdisponible, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
			}
			$pdf->Ln(2);
			$linea=$pdf->GetY(); if ($linea>175) {
				porsector($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $fdesde, $fhasta);
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(100, 100, 100); $pdf->SetTextColor(255, 255, 255);
				$pdf->SetFont('Arial', 'B', 5);
				$pdf->Cell(20, 4, 'Partida', 0, 0, 'C', 1);
				$pdf->Cell(50, 4, 'Descripcion', 0, 0, 'L', 1);
				if ($campos[0]) $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
				if ($campos[1]) $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
				if ($campos[2]) $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
				if ($campos[3]) $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
				if ($campos[4]) $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
				if ($campos[10]) $pdf->Cell(23, 4, 'Reservado Disminuir', 0, 0, 'R', 1);
				if ($campos[6]) { $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1); }
				if ($campos[7]) { $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1); }
				if ($campos[8]) { $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1); }
				if ($campos[9]) { $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1); }
				$pdf->Ln(4);
			}

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
		//------------------------------------------------
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
		//	IMPRIMO LOS TOTALES
		if ($sum_compromiso != 0 && $sum_actual != 0) $tpcompromiso = $sum_compromiso * 100 / $sum_actual; else $tpcompromiso = 0;
		if ($sum_causado != 0 && $sum_actual != 0) $tpcausado = $sum_causado * 100 / $sum_actual; else $tpcausado = 0;
		if ($sum_pagado != 0 && $sum_actual != 0) $tppagado = $sum_pagado * 100 / $sum_actual; else $tppagado = 0;
		if ($sum_disponible != 0 && $sum_actual != 0) $tpdisponible = $sum_disponible * 100 / $sum_actual; else $tpdisponible = 0;

		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetXY($x, $y+5);

		$y=$pdf->GetY();
		$x=75;

		if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_formulado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_aumento, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disminucion, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_modificado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_actual, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[10]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_reservado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[6]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_compromiso, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpcompromiso, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[7]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_causado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpcausado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[8]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_pagado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tppagado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[9]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disponible, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpdisponible, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		break;

	//	Consolidado por Sector...
	case "consolidado_sector":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
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
		consolidado_sector($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $fdesde, $fhasta);
		$filtro = "";
		if ($anio_fiscal != "") $filtro .= " AND (mp.anio = '".$anio_fiscal."')";
		if ($financiamiento != "") $filtro .= " AND (mp.idfuente_financiamiento = '".$financiamiento."')";
		if ($tipo_presupuesto != "") $filtro .= " AND (mp.idtipo_presupuesto = '".$tipo_presupuesto."')";
		if ($idsector != "") $filtro .= " AND (c.idsector = '".$idsector."')";
		//---------------------------------------------
		//	CONSULTO TODO PARA SUMAR LAS ESPECIFICAS
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
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field = mysql_fetch_array($query);
			$par = $field["CodSector"].$field["Par"];
			$_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'];
			$_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'];
			$_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'];
			$_RESERVADO[$par] += $field['ReservadoDisminucion'];
			$_CAUSADO[$par] += $field['Causado'];
			$_PAGADO[$par] += $field['Pagado'];
		}

		//	CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO	O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
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
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field = mysql_fetch_array($query);
			//	SI CAMBIA DE SECTOR LA IMPRIMO
			if ($field["IdSector"]!=$IdSector) {
				$IdSector=$field["IdSector"];
				$l=1;
				if ($i!=0) {
					$sum_modificado = $sum_aumento - $sum_disminucion;
					$sum_actual = $sum_formulado + ($sum_modificado);

					$y=$pdf->GetY();
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
					$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
					//	IMPRIMO LOS TOTALES
					if ($sum_compromiso != 0 && $sum_actual != 0) $tpcompromiso = $sum_compromiso * 100 / $sum_actual; else $tpcompromiso = 0;
					if ($sum_causado != 0 && $sum_actual != 0) $tpcausado = $sum_causado * 100 / $sum_actual; else $tpcausado = 0;
					if ($sum_pagado != 0 && $sum_actual != 0) $tppagado = $sum_pagado * 100 / $sum_actual; else $tppagado = 0;
					if ($sum_disponible != 0 && $sum_actual != 0) $tpdisponible = $sum_disponible * 100 / $sum_actual; else $tpdisponible = 0;

					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetXY($x, $y+5);

					$y=$pdf->GetY();
					$x=75;

					if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_formulado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_aumento, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disminucion, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_modificado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_actual, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[10]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_reservado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[6]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_compromiso, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpcompromiso, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[7]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_causado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpcausado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[8]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_pagado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tppagado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[9]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disponible, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpdisponible, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
					}
				}
				$sum_formulado = 0; $sum_aumento = 0; $sum_disminucion = 0; $sum_modificado = 0; $sum_actual = 0;
				$sum_compromiso = 0; $sum_causado = 0; $sum_pagado = 0; $sum_disponible = 0;

				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(205, 7, $field["CodSector"].'    '.$field["Sector"], 1, 1, 'L', 1);
				$linea=$pdf->GetY(); if ($linea>250) { porsector($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto); }

				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(100, 100, 100); $pdf->SetTextColor(255, 255, 255);
				$pdf->SetFont('Arial', 'B', 5);
				$pdf->Cell(20, 4, 'Partida', 0, 0, 'C', 1);
				$pdf->Cell(50, 4, 'Descripcion', 0, 0, 'L', 1);
				if ($campos[0]) $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
				if ($campos[1]) $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
				if ($campos[2]) $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
				if ($campos[3]) $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
				if ($campos[4]) $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
				if ($campos[10]) $pdf->Cell(23, 4, 'Reservado Disminuir', 0, 0, 'R', 1);
				if ($campos[6]) { $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1); }
				if ($campos[7]) { $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1); }
				if ($campos[8]) { $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1); }
				if ($campos[9]) { $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1); }
				$pdf->Ln(4);
			}

			$l++;
			$clasificador = $field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$par = $field['CodSector'].$field["Par"];

			$modificacion = $_AUMENTO[$par] - $_DISMINUCION[$par];
			$actual = $field['Formulado'] + $modificacion;
			$disponible = $actual - $_COMPROMISO[$par] - $_RESERVADO[$par];

			if ($_COMPROMISO[$par] != 0 && $actual != 0) $pcompromiso = $_COMPROMISO[$par] * 100 / $actual; else $pcompromiso = 0;
			if ($_CAUSADO[$par] != 0 && $actual != 0) $pcausado = $_CAUSADO[$par] * 100 / $actual; else $pcausado = 0;
			if ($_PAGADO[$par] != 0 && $actual != 0) $ppagado = $_PAGADO[$par] * 100 / $actual; else $ppagado = 0;
			if ($disponible != 0 && $actual != 0) $pdisponible = $disponible * 100 / $actual; else $pdisponible = 0;

			if ($l%2 == 0) $pdf->SetFillColor(255, 255, 255); else $pdf->SetFillColor(225, 225, 225);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 6);

			$y=$pdf->GetY();
			$x=5;
			$nb=$pdf->NbLines(50, utf8_decode($field["NomPartida"])); $hf=3*$nb;
			$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $clasificador, 0, 1, 'C', 1); $x+=20;
			$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, utf8_decode($field["NomPartida"]), 0, 'L', 1); $x+=50;
			if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($field['Formulado'], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_AUMENTO[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_DISMINUCION[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($modificacion, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($actual, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[10]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_RESERVADO[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[6]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_COMPROMISO[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pcompromiso, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[7]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_CAUSADO[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pcausado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[8]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($_PAGADO[$par], 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($ppagado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[9]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($disponible, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($pdisponible, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
			}
			$pdf->Ln(2);
			$linea=$pdf->GetY(); if ($linea>175) {
				consolidado_sector($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $fdesde, $fhasta);
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(100, 100, 100); $pdf->SetTextColor(255, 255, 255);
				$pdf->SetFont('Arial', 'B', 5);
				$pdf->Cell(20, 4, 'Partida', 0, 0, 'C', 1);
				$pdf->Cell(50, 4, 'Descripcion', 0, 0, 'L', 1);
				if ($campos[0]) $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
				if ($campos[1]) $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
				if ($campos[2]) $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
				if ($campos[3]) $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
				if ($campos[4]) $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
				if ($campos[10]) $pdf->Cell(23, 4, 'Reservado Disminuir', 0, 0, 'R', 1);
				if ($campos[6]) { $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1); }
				if ($campos[7]) { $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1); }
				if ($campos[8]) { $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1); }
				if ($campos[9]) { $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1); }
				$pdf->Ln(4);
			}

			$sum_formulado += $field['Formulado'];
			$sum_aumento += $_AUMENTO[$par];
			$sum_disminucion += $_DISMINUCION[$par];
			$sum_modificacion += $modificacion;
			$sum_actual += $actual;
			$sum_compromiso += $_COMPROMISO[$par];
			$sum_reservado += $_RESERVADO[$par];
			$sum_causado += $_CAUSADO[$par];
			$sum_pagado += $_PAGADO[$par];
			$sum_disponible += $disponible;
		}
		//------------------------------------------------
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
		//	IMPRIMO LOS TOTALES

		$sum_modificado = $sum_aumento - $sum_disminucion;
		$sum_actual = $sum_formulado + ($sum_modificado);

		if ($sum_compromiso != 0 && $sum_actual != 0) $tpcompromiso = $sum_compromiso * 100 / $sum_actual; else $tpcompromiso = 0;
		if ($sum_causado != 0 && $sum_actual != 0) $tpcausado = $sum_causado * 100 / $sum_actual; else $tpcausado = 0;
		if ($sum_pagado != 0 && $sum_actual != 0) $tppagado = $sum_pagado * 100 / $sum_actual; else $tppagado = 0;
		if ($sum_disponible != 0 && $sum_actual != 0) $tpdisponible = $sum_disponible * 100 / $sum_actual; else $tpdisponible = 0;

		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetXY($x, $y+5);
		$y=$pdf->GetY();
		$x=75;
		if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_formulado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_aumento, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disminucion, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_modificado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_actual, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[10]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_reservado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[6]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_compromiso, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpcompromiso, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[7]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_causado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpcausado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[8]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_pagado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tppagado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[9]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disponible, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpdisponible, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		break;

	//	Detalle por Partida...
	case "detalle_por_partida":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//---------------------------------------------
		$campos = explode("|", $checks);
		//---------------------------------------------
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//---------------------------------------------
		detalle_por_partida($pdf, $_GET['idcategoria'], $_GET['idpartida'], $anio_fiscal, $financiamiento, $tipo_presupuesto, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idordinal, $campos);
		//----------------------------------------------------
		$filtro=" AND (maestro_presupuesto.idcategoria_programatica='".$_GET['idcategoria']."') AND (maestro_presupuesto.idclasificador_presupuestario='".$_GET['idpartida']."')";
		//---------------------------------------------
		if ($idordinal != "") $where_ordinal = " AND maestro_presupuesto.idordinal = '".$idordinal."' ";
		//---------------------------------------------
		$sql="(SELECT maestro_presupuesto.idRegistro,
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
					  (maestro_presupuesto.anio='".$anio_fiscal."' AND
					  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
					  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
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
						(maestro_presupuesto.anio='".$anio_fiscal."' AND
						maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
						maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
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
					(maestro_presupuesto.anio='".$anio_fiscal."' AND
					maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
					maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp))
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$idregistro=$field["idRegistro"];
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$monto_original=number_format($field["MontoOriginal"], 2, ',', '.');
			$monto_actual=number_format($field["MontoActual"], 2, ',', '.');
			$monto_compromiso=number_format($field["MontoCompromiso"], 2, ',', '.');
			$monto_pre_compromiso=number_format($field["MontoPreCompromiso"], 2, ',', '.');
			$monto_reservado=number_format($field["MontoReservadoDisminuir"], 2, ',', '.');
			$aumento=number_format($field["TotalAumento"], 2, ',', '.');
			$dimsinucion=number_format($field["TotalDisminucion"], 2, ',', '.');

			$monto_solicitud=number_format($field["MontoSolicitudAumento"], 2, ',', '.');
			$monto_causado=number_format($field["TotalCausados"], 2, ',', '.');
			$monto_pagado=number_format($field["TotalPagados"], 2, ',', '.');
			$disponible=(float) ($field["MontoActual"]-$field["MontoCompromiso"]-$field["MontoReservadoDisminuir"]-$field["MontoPreCompromiso"]);
			$monto_disponible=number_format($disponible, 2, ',', '.');
			if ($field["MontoCompromiso"]==0 || $field["MontoActual"]==0) $pcompromiso="0"; else $pcompromiso=(float) (($field[MontoCompromiso]*100)/$field["MontoActual"]); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
			if ($field["MontoCausado"]==0 || $field["MontoActual"]==0) $pcausado="0"; else $pcausado=(float) (($field["MontoCausado"]*100)/$field["MontoActual"]); $pcausado=number_format($pcausado, 2, ',', '.');
			if ($field["MontoPagado"]==0 || $field["MontoActual"]==0) $ppagado="0"; else $ppagado=(float) (($field["MontoPagado"]*100)/$field["MontoActual"]); $ppagado=number_format($ppagado, 2, ',', '.');
			if ($disponible==0 || $field["MontoActual"]==0) $pdisponible="0"; else $pdisponible=(float) (($disponible*100)/$field["MontoActual"]); $pdisponible=number_format($pdisponible, 2, ',', '.');

			if ($field["Tipo"]=="partida") {
			}
			else if ($field["Tipo"]=="generica") {
			}
			else if ($field["Tipo"]=="especifica") {
				//	----------------------------
				$sum_original+=$field["MontoOriginal"];
				$sum_aumento+=$field["TotalAumento"];
				$sum_disminucion+=$field["TotalDisminucion"];
				$sum_actual+=$field["MontoActual"];
				$sum_compromiso+=$field["MontoCompromiso"];
				$sum_causado+=$field["TotalCausados"];
				$sum_pagado+=$field["TotalPagados"];
				$sum_disponible+=$disponible;
				//	----------------------------
				$total_aumento=number_format($field["TotalAumento"], 2, ',', '.');
				$total_disminucion=number_format($field["TotalDisminucion"], 2, ',', '.');

				//	CONSULTO PARA OPBTENER LOS AUMENTOS Y DISMINUCIONES DE LAS PARTIDAS E IMPRIMIRLAS
				$sql="SELECT
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
							rcp.idmaestro_presupuesto='".$idregistro."' AND
							rc.anio='".$anio_fiscal."'
						ORDER BY Fecha";
				$query_rendicion=mysql_query($sql) or die ($sql.mysql_error());
				$num_rows_rendicion=mysql_num_rows($query_rendicion);
				$disponible = $field["MontoOriginal"];
				$asignacion_ajustada = $field["MontoOriginal"];
				$h=0;
				if ($num_rows_rendicion !=0) {
					while ($field_rendicion=mysql_fetch_array($query_rendicion)) {
						$aumento=number_format($field_rendicion['aumento_periodo'], 2, ',','.'); $sum_raumento+=$field_rendicion['aumento_periodo'];
						$disminucion=number_format($field_rendicion['disminucion_periodo'], 2, ',','.'); $sum_rdisminucion+=$field_rendicion['disminucion_periodo'];
						$modificado=number_format(($field_rendicion['aumento_periodo']-$field_rendicion['disminucion_periodo']), 2, ',','.');

						$asignacion_ajustada = $asignacion_ajustada + $field_rendicion['aumento_periodo'] -$field_rendicion['disminucion_periodo'];

						$compromisos=number_format($field_rendicion['total_compromisos_periodo'], 2, ',','.'); $sum_rcompromisos+=$field_rendicion['total_compromisos_periodo'];
						$causado=number_format($field_rendicion['total_causados_periodo'], 2, ',','.'); $sum_rcausado+=$field_rendicion['total_causados_periodo'];
						$pagado=number_format($field_rendicion['total_pagados_periodo'], 2, ',','.'); $sum_rpagado+=$field_rendicion['total_pagados_periodo'];


						if ($field_rendicion["total_compromisos_periodo"]==0 or $disponible==0)
							$pcompromiso="0";
						else
							$pcompromiso=(float) (($field_rendicion['total_compromisos_periodo']*100)/$disponible);
						$pcompromiso=number_format($pcompromiso, 2, ',', '.');

						if ($field_rendicion["total_causados_periodo"]==0 || $disponible==0)
							$pcausado="0";
						else
							$pcausado=(float) (($field_rendicion["total_causados_periodo"]*100)/$disponible);
						$pcausado=number_format($pcausado, 2, ',', '.');

						if ($field_rendicion["total_pagados_periodo"]==0 || $disponible==0)
							$ppagado="0";
						else
							$ppagado=(float) (($field_rendicion["total_pagados_periodo"]*100)/$disponible);
						$ppagado=number_format($ppagado, 2, ',', '.');

						$disponiblea = $disponible;
						$disponible = ($disponible + $field_rendicion['aumento_periodo'] - $field_rendicion['disminucion_periodo']) - $field_rendicion['total_compromisos_periodo'];


						if ($disponible==0 || $asignacion_ajustada==0)
							$pdisponible="0";
						else
							$pdisponible=(float) (($disponible*100)/$asignacion_ajustada);
						$pdisponible=number_format($pdisponible, 2, ',', '.');

						if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
						else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(225, 225, 225); $pdf->SetTextColor(0, 0, 0); }
						$pdf->SetFont('Arial', '', 6);

						$y=$pdf->GetY();
						$x=20;
						$nb=$pdf->NbLines(40, utf8_decode($field_rendicion['concepto'])); $hf=3*$nb;
						$pdf->SetXY($x, $y); $pdf->Cell(15, $hf, $field_rendicion['Fecha'], 0, 1, 'C', 1); $x+=15;
						$pdf->SetXY($x, $y); $pdf->MultiCell(40, 3, utf8_decode($field_rendicion['concepto']), 0, 'L', 1); $x+=40;
						if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
						if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $aumento, 0, 1, 'R', 1); $x+=23; }
						if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disminucion, 0, 1, 'R', 1); $x+=23; }
						if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $modificado, 0, 1, 'R', 1); $x+=23; }
						if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($asignacion_ajustada, 2, ',','.'), 0, 1, 'R', 1); $x+=23; }
						if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
						if ($campos[6]) {
							$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $compromisos, 0, 1, 'R', 1); $x+=23;
							$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcompromiso.' %', 0, 1, 'R', 1); $x+=11;
						}
						if ($campos[7]) {
							$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $causado, 0, 1, 'R', 1); $x+=23;
							$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado.' %', 0, 1, 'R', 1); $x+=11;
						}
						if ($campos[8]) {
							$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $pagado, 0, 1, 'R', 1); $x+=23;
							$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $ppagado.' %', 0, 1, 'R', 1); $x+=11;
						}
						if ($campos[9]) {
							$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($disponible, 2, ',','.'), 0, 1, 'R', 1); $x+=23;
							$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pdisponible.' %', 0, 1, 'R', 1); $x+=11;
						}
						$pdf->Ln(2);

						$linea=$pdf->GetY(); if ($linea>190) { detalle_por_partida_tablas($pdf, $campos); $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
						$h++;
					}
				} else {
					$h=0;
					$sql="(SELECT traslados_presupuestarios.status as codigo_referencia,
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
								 (traslados_presupuestarios.anio='".$anio_fiscal."'))

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
								(creditos_adicionales.anio='".$anio_fiscal."'))

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
								(rectificacion_presupuesto.anio='".$anio_fiscal."'))

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
								(traslados_presupuestarios.anio='".$anio_fiscal."'))

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
								(disminucion_presupuesto.anio='".$anio_fiscal."'))

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
								(rectificacion_presupuesto.anio='".$anio_fiscal."'))

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
								(orden_compra_servicio.anio='".$anio_fiscal."'))

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
								(requisicion.anio='".$anio_fiscal."'))

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
								(orden_pago.anio='".$anio_fiscal."'))

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
								partidas_orden_pago.idmaestro_presupuesto='".$idregistro."') AND
								(orden_pago.idorden_pago=partidas_orden_pago.idorden_pago) AND
								(orden_pago.idbeneficiarios=beneficiarios.idbeneficiarios))

							ORDER BY OrdenLista, fecha_solicitud;";
					$query_detalle=mysql_query($sql) or die ($sql.mysql_error());
					$rows_detalle=mysql_num_rows($query_detalle);
					for ($k=0; $k<$rows_detalle; $k++) {
						$detalle=mysql_fetch_array($query_detalle);
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
							if ($detalle['estado']=="pagada" or $detalle['estado']=="procesado") $imprimir_ocs="SI";
						}
						else if ($detalle['tipo']=="Cheque") {
							$disminucion=""; $aumento=""; $compromisos=""; $causado=""; $pagado=$monto;
							if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $pagado="($pagado)"; } else $anulado="";
							$detalle['tipo']=$detalle['justificacion']." ".$anulado;
							$imprimir_op="SI";
						}
						else if ($detalle['tipo']=="Requisicion") {
							$detalle['tipo']=$detalle['justificacion'];
							$precompromiso=number_format($detalle['monto_acreditar'], 2, ',', '.');
							$sum_precompromiso+=$detalle['monto_acreditar'];
						}

						$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255);
						if ($detalle['estado'] == "anulado" || $detalle['estado'] == "Anulado") {$pdf->SetTextColor(255, 0, 0);}
						else $pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('Arial', '', 6);
						if (($detalle['tipo']=="Requisicion" && ($detalle['ROC']=="") && $detalle['estado']=="procesado") || $detalle['tipo']!="Requisicion") {
							$y=$pdf->GetY();
							$x=5;
							$nb=$pdf->NbLines(40, utf8_decode($detalle['tipo'])); $hf=3*$nb;
							$pdf->SetXY($x, $y); $pdf->Cell(15, $hf, $detalle['nro_solicitud'], 0, 1, 'C', 1); $x+=15;
							$pdf->SetXY($x, $y); $pdf->Cell(15, $hf, $fecha_solicitud, 0, 1, 'C', 1); $x+=15;
							$pdf->SetXY($x, $y); $pdf->MultiCell(40, 3, utf8_decode($detalle['tipo']), 0, 'L', 1); $x+=40;
							if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
							if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $aumento, 0, 1, 'R', 1); $x+=23; }
							if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disminucion, 0, 1, 'R', 1); $x+=23; }
							if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
							if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
							if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
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
								$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23;
								$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, '', 0, 1, 'R', 1); $x+=11;
							}
							$pdf->Ln(2);
						}
						$linea=$pdf->GetY(); if ($linea>190) { detalle_por_partida_tablas($pdf, $campos); $pdf->SetY(40); $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }

						if ($imprimir_ocs=="SI") {
							if ($chkjustificacionoc == "1") {
								$sql = "SELECT
											rpc.idorden_compra_servicio,
											ocs.numero_orden,
											ocs.justificacion
										FROM
											relacion_pago_compromisos rpc
											INNER JOIN orden_compra_servicio ocs ON (rpc.idorden_compra_servicio = ocs.idorden_compra_servicio)
										WHERE rpc.idorden_pago = '".$detalle['id']."'";
								$query_pagados=mysql_query($sql) or die ($sql.mysql_error());
								while ($field_pagados=mysql_fetch_array($query_pagados)) {
									$ordenes = $field_pagados['numero_orden'].", ".$field_pagados['justificacion'];
									$y=$pdf->GetY();
									$x=35;
									$nb=$pdf->NbLines(15, utf8_decode($ordenes)); $hf=3*$nb;
									$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, $ordenes, 0, 'L', 1);;
									$pdf->Ln(2);
									$linea=$pdf->GetY(); if ($linea>190) { detalle_por_partida_tablas($pdf, $campos); }
								}
							} else {
								$sql = "SELECT
											rpc.idorden_compra_servicio,
											ocs.numero_orden
										FROM
											relacion_pago_compromisos rpc
											INNER JOIN orden_compra_servicio ocs ON (rpc.idorden_compra_servicio = ocs.idorden_compra_servicio)
										WHERE rpc.idorden_pago = '".$detalle['id']."'";
								$query_pagados=mysql_query($sql) or die ($sql.mysql_error());
								while ($field_pagados=mysql_fetch_array($query_pagados)) {
									$ordenes.=$field_pagados['numero_orden'].", ";
								}
								$y=$pdf->GetY();
								$x=35;
								$nb=$pdf->NbLines(15, utf8_decode($ordenes)); $hf=3*$nb;
								$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, $ordenes, 0, 'L', 1);;
								$pdf->Ln(2);
								$linea=$pdf->GetY(); if ($linea>190) { detalle_por_partida_tablas($pdf, $campos); }
							}
						}

						if ($imprimir_op=="SI") {
							if ($chkjustificacionop == "1") {
								$sql = "SELECT
											pf.idorden_pago,
											op.numero_orden,
											op.justificacion
										FROM
											pagos_financieros pf
											INNER JOIN orden_pago op ON (pf.idorden_pago = op.idorden_pago)
										WHERE pf.idpagos_financieros = '".$detalle['id']."'";
								$query_pagados=mysql_query($sql) or die ($sql.mysql_error());
								while ($field_pagados=mysql_fetch_array($query_pagados)) {
									$ordenes = $field_pagados['numero_orden'].", ".$field_pagados['justificacion'];
									$y=$pdf->GetY();
									$x=35;
									$nb=$pdf->NbLines(15, utf8_decode($ordenes)); $hf=3*$nb;
									$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, $ordenes, 0, 'L', 1);;
									$pdf->Ln(2);
									$linea=$pdf->GetY(); if ($linea>190) { detalle_por_partida_tablas($pdf, $campos); }
								}
							} else {
								$sql = "SELECT
											pf.idorden_pago,
											op.numero_orden
										FROM
											pagos_financieros pf
											INNER JOIN orden_pago op ON (pf.idorden_pago = op.idorden_pago)
										WHERE pf.idpagos_financieros = '".$detalle['id']."'";
								$query_pagados=mysql_query($sql) or die ($sql.mysql_error());
								while ($field_pagados=mysql_fetch_array($query_pagados)) {
									$ordenes.=$field_pagados['numero_orden'].", ";
								}
								$y=$pdf->GetY();
								$x=35;
								$nb=$pdf->NbLines(15, utf8_decode($ordenes)); $hf=3*$nb;
								$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, $ordenes, 0, 'L', 1);;
								$pdf->Ln(2);
								$linea=$pdf->GetY(); if ($linea>190) { detalle_por_partida_tablas($pdf, $campos); }
							}
						}

						$h++;
						$disminucion=""; $aumento=""; $disminucion=""; $compromisos=""; $causado=""; $pagado=""; $anulado=""; $monto_actual=""; $precompromiso=""; $imprimir_ocs=""; $imprimir_op=""; $ordenes="";
					}
				}
			}
		}
		if ($num_rows_rendicion!=0) {
			$sum_aumento=number_format($sum_raumento, 2, ',','.');
			$sum_disminucion=number_format($sum_rdisminucion, 2, ',','.');
			$sum_compromisos=number_format($sum_rcompromisos, 2, ',','.');
			$sum_causado=number_format($sum_rcausado, 2, ',','.');
			$sum_pagado=number_format($sum_rpagado, 2, ',','.');
			$sum_disponible=number_format($disponible, 2, ',','.');
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$y=$pdf->GetY();
			$pdf->Rect(5, $y+5, 345, 0.1);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetHeight(array(5));
			$pdf->SetFont('Arial', 'B', 6);

			$pdf->SetXY($x, $y+10);
			$y=$pdf->GetY();
			$x=75;

			$sum_modificado = $sum_raumento - $sum_rdisminucion;

			if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
			if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_aumento, 0, 1, 'R', 1); $x+=23; }
			if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_disminucion, 0, 1, 'R', 1); $x+=23; }
			if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_modificado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
			if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_precompromiso, 0, 1, 'R', 1); $x+=23; }
			if ($campos[6]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_compromisos, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tpcompromiso.' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[7]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_causado, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tpcausado.' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[8]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_pagado, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tppagado.' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[9]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_disponible, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tpdisponible.' %', 0, 1, 'R', 1); $x+=11;
			}

			//$pdf->Row(array('', '', '', '', $sum_aumento, $sum_disminucion, '', '', $sum_compromisos, '', $sum_causado, '', $sum_pagado, ''));
		} else {

			if ($sum_actual==0) $pcompromiso=0; else $pcompromiso=(float) (($sum_compromiso*100)/$sum_actual); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
			if ($sum_actual==0) $pcausado=0; else $pcausado=(float) (($sum_causado*100)/$sum_actual); $pcausado=number_format($pcausado, 2, ',', '.');
			if ($sum_actual==0) $ppagado=0; else $ppagado=(float) (($sum_pagado*100)/$sum_actual); $ppagado=number_format($ppagado, 2, ',', '.');
			if ($sum_actual==0) $pdisponible=0; else $pdisponible=(float) (($disponible*100)/$sum_actual); $pdisponible=number_format($pdisponible, 2, ',', '.');

			$sum_modificado = $sum_aumento - $sum_disminucion;
			$sum_original=number_format($sum_original, 2, ',', '.');
			$sum_aumento=number_format($sum_aumento, 2, ',', '.');
			$sum_disminucion=number_format($sum_disminucion, 2, ',', '.');
			$sum_actual=number_format($sum_actual, 2, ',', '.');
			$sum_compromiso=number_format($sum_compromiso, 2, ',', '.');
			$sum_causado=number_format($sum_causado, 2, ',', '.');
			$sum_pagado=number_format($sum_pagado, 2, ',', '.');
			$sum_disponible=number_format($sum_disponible, 2, ',', '.');
			$sum_precompromiso=number_format($sum_precompromiso, 2, ',', '.');

			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$y=$pdf->GetY();
			$pdf->Rect(5, $y+5, 345, 0.1);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetAligns(array('L', 'C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
			$pdf->SetWidths(array(25, 13, 54, 20, 20, 20, 20, 20, 20, 12, 20, 12, 20, 12));
			$pdf->SetHeight(array(5));
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->SetY($y+8);

			$pdf->SetXY($x, $y+10);
			$y=$pdf->GetY();
			$x=75;
			if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_original, 0, 1, 'R', 1); $x+=23; }
			if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_aumento, 0, 1, 'R', 1); $x+=23; }
			if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_disminucion, 0, 1, 'R', 1); $x+=23; }
			if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_modificado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
			if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_actual, 0, 1, 'R', 1); $x+=23; }
			if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_precompromiso, 0, 1, 'R', 1); $x+=23; }
			if ($campos[6]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_compromiso, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tpcompromiso.' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[7]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_causado, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tpcausado.' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[8]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_pagado, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tppagado.' %', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[9]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_disponible, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tpdisponible.' %', 0, 1, 'R', 1); $x+=11;
			}
			//$pdf->Row(array('', '', '', $sum_original, $sum_aumento, $sum_disminucion, $sum_actual, $sum_precompromiso, $sum_compromiso, $pcompromiso." %", $sum_causado, $pcausado." %", $sum_pagado, $ppagado." %"));
		}
		break;

	//	Ejecucion Detallada...
	case "ejecucion_detallada":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//---------------------------------------------
		$campos = explode("|", $checks);
		//---------------------------------------------
		if ($estado != "") {
			if ($estado == "procesadas/pagadas") {
				$filtro_traslados = "AND traslados_presupuestarios.estado = 'procesado'";
				$filtro_creditos = "AND creditos_adicionales.estado = 'procesado'";
				$filtro_rectificacion = "AND rectificacion_presupuesto.estado = 'procesado'";
				$filtro_disminucion = "AND disminucion_presupuesto.estado = 'procesado'";
				$filtro_requisicion = "AND requisicion.estado = 'procesado'";
				$filtro_ocs = "AND (orden_compra_servicio.estado = 'procesado' OR orden_compra_servicio.estado = 'pagado')";
				$filtro_op = "AND (orden_pago.estado = 'procesado' OR orden_pago.estado = 'pagada')";
				$filtro_pf = " AND pagos_financieros.estado <> 'anulado'";
			}
			elseif ($estado == "procesadas") {
				$filtro_traslados = "AND traslados_presupuestarios.estado = 'procesado'";
				$filtro_creditos = "AND creditos_adicionales.estado = 'procesado'";
				$filtro_rectificacion = "AND rectificacion_presupuesto.estado = 'procesado'";
				$filtro_disminucion = "AND disminucion_presupuesto.estado = 'procesado'";
				$filtro_requisicion = "AND requisicion.estado = 'procesado'";
				$filtro_ocs = "AND orden_compra_servicio.estado = 'procesado'";
				$filtro_op = "AND orden_pago.estado = 'procesado'";
				$filtro_pf = " AND pagos_financieros.estado <> 'anulado'";
			}
			elseif ($estado == "pagadas") {
				$filtro_traslados = "AND traslados_presupuestarios.estado = 'procesado'";
				$filtro_creditos = "AND creditos_adicionales.estado = 'procesado'";
				$filtro_rectificacion = "AND rectificacion_presupuesto.estado = 'procesado'";
				$filtro_disminucion = "AND disminucion_presupuesto.estado = 'procesado'";
				$filtro_requisicion = "AND requisicion.estado = 'procesado'";
				$filtro_ocs = "AND orden_compra_servicio.estado = 'pagado'";
				$filtro_op = "AND orden_pago.estado = 'pagada'";
				$filtro_pf = " AND pagos_financieros.estado <> 'anulado'";
			}
			elseif ($estado == "anuladas") {
				$filtro_traslados = "AND traslados_presupuestarios.estado = 'anulado'";
				$filtro_creditos = "AND creditos_adicionales.estado = 'anulado'";
				$filtro_rectificacion = "AND rectificacion_presupuesto.estado = 'anulado'";
				$filtro_disminucion = "AND disminucion_presupuesto.estado = 'anulado'";
				$filtro_requisicion = "AND requisicion.estado = 'anulado'";
				$filtro_ocs = "AND orden_compra_servicio.estado = 'anulado'";
				$filtro_op = "AND orden_pago.estado = 'anulado'";
				$filtro_pf = " AND pagos_financieros.estado = 'anulado'";
			}
		}
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//---------------------------------------------
		ejecucion_detallada($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos);
		if ($idcategoria_programatica!='') $filtro=" AND (maestro_presupuesto.idcategoria_programatica='".$idcategoria_programatica."')";
		//---------------------------------------------
		$sql = "SELECT
					rc.*
				FROM
					rendicion_cuentas rc
					INNER JOIN categoria_programatica cp ON (rc.idcategoria_programatica = cp.idcategoria_programatica)
					INNER JOIN unidad_ejecutora u ON (cp.idunidad_ejecutora = u.idunidad_ejecutora)
				WHERE
					rc.anio='".$anio_fiscal."' AND
					rc.idfuente_financiamiento='".$financiamiento."' AND
					rc.idtipo_presupuesto='".$tipo_presupuesto."' AND
					rc.idcategoria_programatica='".$idcategoria_programatica."'
				GROUP BY rc.idcategoria_programatica";
		$query_comprueba_rendicion = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_comprueba_rendicion) != 0) {
			$sql="(SELECT maestro_presupuesto.idRegistro,
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
							(maestro_presupuesto.anio='".$anio_fiscal."' AND
							maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
							maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
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
							(maestro_presupuesto.anio='".$anio_fiscal."' AND
							maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
							maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
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
							(maestro_presupuesto.anio='".$anio_fiscal."' AND
							maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
							maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
						GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (ordinal.codigo))
						ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
		} else {
			$sql="(SELECT maestro_presupuesto.idRegistro,
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
						  (maestro_presupuesto.anio='".$anio_fiscal."' AND
						  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
						  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
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
							(maestro_presupuesto.anio='".$anio_fiscal."' AND
							maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
							maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
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
							(maestro_presupuesto.anio='".$anio_fiscal."' AND
							maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
							maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
						GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (ordinal.codigo))
						ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
		}
		//---------------------------------------------
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$idregistro=$field["idRegistro"];
			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"]!=$IdCategoria) {
				$IdCategoria=$field["IdCategoria"];
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(205, 5, $field["CodCategoria"]." - ".$field["Unidad"], 1, 1, 'L', 1);
				$linea=$pdf->GetY(); if ($linea>175) { ejecucion_detallada($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos); }
			}
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$precompromiso=number_format($field["PreCompromiso"], 2, ',', '.');
			$total_aumento=number_format($field["TotalAumento"], 2, ',', '.');
			$total_disminucion=number_format($field["TotalDisminucion"], 2, ',', '.');
			$monto_actual = $field['MontoOriginal'] + $field['TotalAumento'] - $field['TotalDisminucion'];
			$total_actual = number_format($monto_actual , 2, ',', '.');
			$monto_original=number_format($field["MontoOriginal"], 2, ',', '.');
			$monto_compromiso=number_format($field["MontoCompromiso"], 2, ',', '.');
			$monto_causado=number_format($field["TotalCausados"], 2, ',', '.');
			$monto_pagado=number_format($field["TotalPagados"], 2, ',', '.');
			$modificado=number_format(($field["TotalAumento"] - $field['TotalDisminucion']), 2, ',', '.');
			if ($chkrestar)
				$disponible=(float) ($field["MontoActual"]-$field["PreCompromiso"]-$field["MontoCompromiso"]-$field["ReservadoDisminuir"]);
			else
				$disponible=(float) ($field["MontoActual"]-$field["MontoCompromiso"]-$field["ReservadoDisminuir"]);
			$monto_disponible=number_format($disponible, 2, ',', '.');
			if ($field["MontoCompromiso"]==0 || $monto_actual==0) $pcompromiso="0"; else $pcompromiso=(float) (($field["MontoCompromiso"]*100)/$monto_actual); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
			if ($field["TotalCausados"]==0 || $monto_actual==0) $pcausado="0"; else $pcausado=(float) (($field["TotalCausados"]*100)/$monto_actual); $pcausado=number_format($pcausado, 2, ',', '.');
			if ($field["TotalPagados"]==0 || $monto_actual==0) $ppagado="0"; else $ppagado=(float) (($field["TotalPagados"]*100)/$monto_actual); $ppagado=number_format($ppagado, 2, ',', '.');
			if ($disponible==0 || $monto_actual==0) $pdisponible="0"; else $pdisponible=(float) (($disponible*100)/$monto_actual); $pdisponible=number_format($pdisponible, 2, ',', '.');


			if ($field["Tipo"]=="partida") {
				$sum_original+=$field["MontoOriginal"];
				$sum_aumento+=$field["TotalAumento"];
				$sum_disminucion+=$field["TotalDisminucion"];
				$sum_modificado+=($field["TotalAumento"] - $field['TotalDisminucion']);
				$sum_compromiso+=$field["MontoCompromiso"];
				$sum_causado+=$field["TotalCausados"];
				$sum_pagado+=$field["TotalPagados"];


				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 6);

				$y=$pdf->GetY();
				$x=5;
				$nb=$pdf->NbLines(73, utf8_decode($field["NomPartida"])); $hf=3*$nb;
				$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $clasificador, 0, 1, 'C', 1); $x+=20;
				$pdf->SetXY($x, $y); $pdf->MultiCell(73, 3, utf8_decode($field["NomPartida"]), 0, 'L', 1); $x+=73;
				if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_original, 0, 1, 'R', 1); $x+=23; }
				if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $total_aumento, 0, 1, 'R', 1); $x+=23; }
				if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $total_disminucion, 0, 1, 'R', 1); $x+=23; }
				if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $modificado, 0, 1, 'R', 1); $x+=23; }
				if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $total_actual, 0, 1, 'R', 1); $x+=23; }
				//if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $precompromiso, 0, 1, 'R', 1); $x+=23; }
				if ($campos[6]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_compromiso, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcompromiso.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[7]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_causado, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[8]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_causado, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[9]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_disponible, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pdisponible.' %', 0, 1, 'R', 1); $x+=11;
				}
				$pdf->Ln(2);

				$linea=$pdf->GetY(); if ($linea>175) { ejecucion_detallada($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos); }
			}

			else if ($field["Tipo"]=="generica") {
				$h++;
				if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
				else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
				$pdf->SetFont('Arial', 'B', 6);

				$y=$pdf->GetY();
				$x=5;
				$nb=$pdf->NbLines(73, utf8_decode($field["NomPartida"])); $hf=3*$nb;
				$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $clasificador, 0, 1, 'C', 1); $x+=20;
				$pdf->SetXY($x, $y); $pdf->MultiCell(73, 3, utf8_decode($field["NomPartida"]), 0, 'L', 1); $x+=73;
				if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_original, 0, 1, 'R', 1); $x+=23; }
				if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $total_aumento, 0, 1, 'R', 1); $x+=23; }
				if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $total_disminucion, 0, 1, 'R', 1); $x+=23; }
				if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $modificado, 0, 1, 'R', 1); $x+=23; }
				if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $total_actual, 0, 1, 'R', 1); $x+=23; }
				//if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $precompromiso, 0, 1, 'R', 1); $x+=23; }
				if ($campos[6]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_compromiso, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcompromiso.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[7]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_causado, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[8]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_causado, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[9]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_disponible, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pdisponible.' %', 0, 1, 'R', 1); $x+=11;
				}
				$pdf->Ln(2);

				$linea=$pdf->GetY(); if ($linea>175) { ejecucion_detallada($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos); }
			}

			else if ($field["Tipo"]=="especifica") {
				//	----------------------------


				//	----------------------------
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 6);
				$total_aumento=number_format($field["TotalAumento"], 2, ',', '.');
				$total_disminucion=number_format($field["TotalDisminucion"], 2, ',', '.');
				//$sum_actual+=monto_actual;
				$pos=$pdf->GetY();
				if ($field['codordinal'] == '0000') $nompartida = utf8_decode($field["NomPartida"]);
				else $nompartida = utf8_decode($field['codordinal'].' '.$field["nomordinal"]);

				$y=$pdf->GetY();
				$x=5;
				$nb=$pdf->NbLines(73, $nompartida); $hf=3*$nb;
				$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $clasificador, 0, 1, 'C', 1); $x+=20;
				$pdf->SetXY($x, $y); $pdf->MultiCell(73, 3, $nompartida, 0, 'L', 1); $x+=73;
				if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_original, 0, 1, 'R', 1); $x+=23; }
				if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $total_aumento, 0, 1, 'R', 1); $x+=23; }
				if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $total_disminucion, 0, 1, 'R', 1); $x+=23; }
				if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $modificado, 0, 1, 'R', 1); $x+=23; }
				if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $total_actual, 0, 1, 'R', 1); $x+=23; }
				//if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $precompromiso, 0, 1, 'R', 1); $x+=23; }
				if ($campos[6]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_compromiso, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcompromiso.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[7]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_causado, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[8]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_causado, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[9]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $monto_disponible, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pdisponible.' %', 0, 1, 'R', 1); $x+=11;
				}

				$linea=$pdf->GetY(); if ($linea>175) { ejecucion_detallada($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos); }


				$sql="SELECT
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
						rcp.idmaestro_presupuesto='".$idregistro."' AND
						rc.anio='".$anio_fiscal."'";
				$query_rendicion=mysql_query($sql) or die ($sql.mysql_error());
				$num_rows_rendicion=mysql_num_rows($query_rendicion);
				$h=0;
				if ($num_rows_rendicion!=0) {
					while ($field_rendicion=mysql_fetch_array($query_rendicion)) {
						$aumento=number_format($field_rendicion['aumento_periodo'], 2, ',','.'); $sum_raumento+=$field_rendicion['aumento_periodo'];
						$disminucion=number_format($field_rendicion['disminucion_periodo'], 2, ',','.'); $sum_rdisminucion+=$field_rendicion['disminucion_periodo'];
						$modificado=number_format(($field["aumento_periodo"] - $field['disminucion_periodo']), 2, ',', '.'); $sum_rmodificado+=($field["aumento_periodo"] - $field['disminucion_periodo']);
						$compromisos=number_format($field_rendicion['total_compromisos_periodo'], 2, ',','.'); $sum_rcompromisos+=$field_rendicion['total_compromisos_periodo'];
						$causado=number_format($field_rendicion['total_causados_periodo'], 2, ',','.'); $sum_rcausado+=$field_rendicion['total_causados_periodo'];
						$pagado=number_format($field_rendicion['total_pagados_periodo'], 2, ',','.'); $sum_rpagado+=$field_rendicion['total_pagados_periodo'];
						$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('Arial', '', 6);

						$y=$pdf->GetY();
						$x=5;
						$nb=$pdf->NbLines(73, $field_rendicion['Fecha'].'-'.utf8_decode($field_rendicion['concepto'])); $hf=3*$nb;
						$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, '', 0, 1, 'C', 1); $x+=20;
						$pdf->SetXY($x, $y); $pdf->MultiCell(73, 3, $field_rendicion['Fecha'].'-'.utf8_decode($field_rendicion['concepto']), 0, 'L', 1); $x+=73;
						if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
						if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $aumento, 0, 1, 'R', 1); $x+=23; }
						if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disminucion, 0, 1, 'R', 1); $x+=23; }
						if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $modificado, 0, 1, 'R', 1); $x+=23; }
						if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
						//if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
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
							$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23;
							$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, '', 0, 1, 'R', 1); $x+=11;
						}
						$pdf->Ln(2);
						$linea=$pdf->GetY(); if ($linea>175) { ejecucion_detallada($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos); $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
						$h++;
					}
				} else {
					//	CONSULTO PARA OPBTENER LOS AUMENTOS Y DISMINUCIONES DE LAS PARTIDAS E IMPRIMIRLAS
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
								 '' AS ROC,
								   '01' AS OrdenLista
							FROM
								 traslados_presupuestarios,
								 partidas_receptoras_traslado
							WHERE
								 (traslados_presupuestarios.idtraslados_presupuestarios=partidas_receptoras_traslado.idtraslados_presupuestarios) AND
								 (partidas_receptoras_traslado.idmaestro_presupuesto='$idregistro') AND
								 (traslados_presupuestarios.anio='".$anio_fiscal."') AND
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
								(creditos_adicionales.anio='".$anio_fiscal."')

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
								(rectificacion_presupuesto.anio='".$anio_fiscal."')

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
								(traslados_presupuestarios.anio='".$anio_fiscal."')

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
								(disminucion_presupuesto.anio='".$anio_fiscal."')

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
								(rectificacion_presupuesto.anio='".$anio_fiscal."')

							UNION

							SELECT orden_compra_servicio.codigo_referencia as codigo_referencia,
								   orden_compra_servicio.idorden_compra_servicio AS id,
								   orden_compra_servicio.numero_orden,
								   orden_compra_servicio.fecha_orden as fecha_solicitud,
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
								(orden_compra_servicio.anio='".$anio_fiscal."')

							UNION

							SELECT requisicion.codigo_referencia as codigo_referencia,
								   requisicion.idrequisicion AS id,
								   requisicion.numero_requisicion,
								   requisicion.fecha_orden as fecha_solicitud,
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
								(requisicion.anio='".$anio_fiscal."')

							UNION

							SELECT orden_pago.codigo_referencia as codigo_referencia,
								   orden_pago.idorden_pago AS id,
								   orden_pago.numero_orden,
								   orden_pago.fecha_orden as fecha_solicitud,
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
								(orden_pago.anio='".$anio_fiscal."')

							UNION

							SELECT pagos_financieros.codigo_referencia as codigo_referencia,
								   pagos_financieros.idpagos_financieros AS id,
								   pagos_financieros.numero_cheque,
								   pagos_financieros.fecha_cheque as fecha_solicitud,
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
								partidas_orden_pago.idmaestro_presupuesto='$idregistro' $filtro_pf)

							ORDER BY OrdenLista, fecha_solicitud";
					$query_detalle=mysql_query($sql) or die ($sql.mysql_error());
					$rows_detalle=mysql_num_rows($query_detalle);
					for ($k=0; $k<$rows_detalle; $k++) {
						$detalle=mysql_fetch_array($query_detalle);
						$monto=number_format($detalle['monto_acreditar'], 2, ',', '.');
						list($a, $m, $d)=SPLIT( '[/.-]', $detalle['fecha_solicitud']); $fecha_solicitud=$d."/".$m."/".$a;
						if ($detalle['tipo']=="Traslado(+)" || $detalle['tipo']=="Credito Adicional" || $detalle['tipo']=="Rectificacion(+)") {
							$aumento=$monto; $disminucion=""; $compromisos=""; $causado=""; $modificado=$monto;
							if ($detalle['estado']=="Anulado") { $anulado="(ANULADO)"; $aumento="($aumento)"; } else $anulado="";
							$detalle['tipo']=$detalle['tipo']." ".$anulado;

						}
						else if ($detalle['tipo']=="Traslado(-)" || $detalle['tipo']=="Disminucion Presupuestaria" || $detalle['tipo']=="Rectificacion(-)") {
							$disminucion=$monto; $aumento=""; $compromisos=""; $causado=""; $modificado=$monto;
							if ($detalle['estado']=="Anulado") { $anulado="(ANULADO)"; $disminucion="($disminucion)"; } else $anulado="";
							$detalle['tipo']=$detalle['tipo']." ".$anulado;
						}
						else if ($detalle['tipo']=="Orden de Compra/Servicio") {
							$disminucion=""; $aumento=""; $modificado=""; $compromisos=$monto; $detalle['tipo']=$detalle['justificacion']; $causado="";
							if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $compromisos="($compromisos)"; } else $anulado="";
							$detalle['tipo']=$detalle['justificacion']." ".$anulado;
						}
						else if ($detalle['tipo']=="Orden de Pago") {
							$disminucion=""; $aumento=""; $modificado="";
							if ($detalle['Causa']=="si") { $causado=$monto; if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $causado="($causado)"; } else $anulado=""; }
							if ($detalle['Compromete']=="si"){ $compromisos=$monto; if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $compromisos="($compromisos)"; } else $anulado=""; }
							$detalle['tipo']=$detalle['justificacion']." ".$anulado;
							if ($detalle['estado']=="pagada") $imprimir_ocs="SI";
						}
						else if ($detalle['tipo']=="Cheque") {
							$disminucion=""; $aumento=""; $modificado=""; $compromisos=""; $causado=""; $pagado=$monto;
							if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $pagado="($pagado)"; } else $anulado="";
							$detalle['tipo']=$detalle['justificacion']." ".$anulado;
							$imprimir_op="SI";
						}
						else if ($detalle['tipo']=="Requisicion") {
							$modificado="";
							$detalle['tipo']=$detalle['justificacion'];
							$precompromiso=number_format($detalle['monto_acreditar'], 2, ',', '.');
							$sum_precompromiso+=$detalle['monto_acreditar'];
						}
						$h++;

						$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('Arial', '', 6);

						$y=$pdf->GetY();
						$x=5;
						$nb=$pdf->NbLines(73, utf8_decode($fecha_solicitud.' - '.$detalle['tipo'])); $hf=3*$nb;
						$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $detalle['nro_solicitud'], 0, 1, 'C', 1); $x+=20;
						$pdf->SetXY($x, $y); $pdf->MultiCell(73, 3, utf8_decode($fecha_solicitud.' - '.$detalle['tipo']), 0, 'L', 1); $x+=73;
						if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
						if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $aumento, 0, 1, 'R', 1); $x+=23; }
						if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disminucion, 0, 1, 'R', 1); $x+=23; }
						if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $modificado, 0, 1, 'R', 1); $x+=23; }
						if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
						//if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $precompromiso, 0, 1, 'R', 1); $x+=23; }
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
							$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23;
							$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, '', 0, 1, 'R', 1); $x+=11;
						}


						$pdf->Ln(2);
						$linea=$pdf->GetY(); if ($linea>175) { ejecucion_detallada($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos); $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }

						if ($imprimir_ocs=="SI") {
							if ($chkjustificacionoc == "1") {
								$sql = "SELECT
											rpc.idorden_compra_servicio,
											ocs.numero_orden,
											ocs.justificacion
										FROM
											relacion_pago_compromisos rpc
											INNER JOIN orden_compra_servicio ocs ON (rpc.idorden_compra_servicio = ocs.idorden_compra_servicio)
										WHERE rpc.idorden_pago = '".$detalle['id']."'";
								$query_pagados=mysql_query($sql) or die ($sql.mysql_error());
								while ($field_pagados=mysql_fetch_array($query_pagados)) {
									$ordenes = $field_pagados['numero_orden'].", ".$field_pagados['justificacion'];
									$y=$pdf->GetY();
									$x=25;
									$nb=$pdf->NbLines(15, utf8_decode($ordenes)); $hf=3*$nb;
									$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, $ordenes, 0, 'L', 1);;
									$pdf->Ln(2);
									$linea=$pdf->GetY(); if ($linea>190) { detalle_por_partida_tablas($pdf, $campos); }
								}
							} else {
								$sql = "SELECT
											rpc.idorden_compra_servicio,
											ocs.numero_orden
										FROM
											relacion_pago_compromisos rpc
											INNER JOIN orden_compra_servicio ocs ON (rpc.idorden_compra_servicio = ocs.idorden_compra_servicio)
										WHERE rpc.idorden_pago = '".$detalle['id']."'";
								$query_pagados=mysql_query($sql) or die ($sql.mysql_error());
								while ($field_pagados=mysql_fetch_array($query_pagados)) {
									$ordenes.=$field_pagados['numero_orden'].", ";
								}
								$y=$pdf->GetY();
								$x=35;
								$nb=$pdf->NbLines(15, utf8_decode($ordenes)); $hf=3*$nb;
								$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, $ordenes, 0, 'L', 1);;
								$pdf->Ln(2);
								$linea=$pdf->GetY(); if ($linea>190) { detalle_por_partida_tablas($pdf, $campos); }
							}
						}

						if ($imprimir_op=="SI") {
							if ($chkjustificacionop == "1") {
								$sql = "SELECT
											pf.idorden_pago,
											op.numero_orden,
											op.justificacion
										FROM
											pagos_financieros pf
											INNER JOIN orden_pago op ON (pf.idorden_pago = op.idorden_pago)
										WHERE pf.idpagos_financieros = '".$detalle['id']."'";
								$query_pagados=mysql_query($sql) or die ($sql.mysql_error());
								while ($field_pagados=mysql_fetch_array($query_pagados)) {
									$ordenes = $field_pagados['numero_orden'].", ".$field_pagados['justificacion'];
									$y=$pdf->GetY();
									$x=25;
									$nb=$pdf->NbLines(15, utf8_decode($ordenes)); $hf=3*$nb;
									$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, $ordenes, 0, 'L', 1);;
									$pdf->Ln(2);
									$linea=$pdf->GetY(); if ($linea>190) { detalle_por_partida_tablas($pdf, $campos); }
								}
							} else {
								$sql = "SELECT
											pf.idorden_pago,
											op.numero_orden
										FROM
											pagos_financieros pf
											INNER JOIN orden_pago op ON (pf.idorden_pago = op.idorden_pago)
										WHERE pf.idpagos_financieros = '".$detalle['id']."'";
								$query_pagados=mysql_query($sql) or die ($sql.mysql_error());
								while ($field_pagados=mysql_fetch_array($query_pagados)) {
									$ordenes.=$field_pagados['numero_orden'].", ";
								}
								$y=$pdf->GetY();
								$x=35;
								$nb=$pdf->NbLines(15, utf8_decode($ordenes)); $hf=3*$nb;
								$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, $ordenes, 0, 'L', 1);;
								$pdf->Ln(2);
								$linea=$pdf->GetY(); if ($linea>190) { detalle_por_partida_tablas($pdf, $campos); }
							}
						}

						$h++;
						$disminucion=""; $aumento=""; $disminucion=""; $compromisos=""; $causado=""; $pagado=""; $anulado=""; $monto_actual=""; $precompromiso=""; $imprimir_ocs=""; $imprimir_op=""; $ordenes="";
						$imprimir_ocs=""; $imprimir_op=""; $precompromiso=""; $aumento=""; $disminucion=""; $modificado=""; $compromisos=""; $causado=""; $pagado=""; $ordenes="";
					}
				}
				$disponible = 0;
			}
		}
		if ($num_rows_rendicion!=0) {
			$sum_aumento=number_format($sum_raumento, 2, ',','.');
			$sum_disminucion=number_format($sum_rdisminucion, 2, ',','.');
			$sum_compromisos=number_format($sum_rcompromisos, 2, ',','.');
			$sum_causado=number_format($sum_rcausado, 2, ',','.');
			$sum_pagado=number_format($sum_rpagado, 2, ',','.');

			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$y=$pdf->GetY();
			//$pdf->Rect(5, $y+5, 337, 0.1);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetHeight(array(5));
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->SetY($y+8);

			$y=$pdf->GetY();
			$x=5;
			$nb=$pdf->NbLines(73, ''); $hf=3*$nb;
			$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, '', 0, 1, 'C', 1); $x+=20;
			$pdf->SetXY($x, $y); $pdf->MultiCell(73, 3, '', 0, 'L', 1); $x+=73;
			if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
			if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_aumento, 0, 1, 'R', 1); $x+=23; }
			if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_disminucion, 0, 1, 'R', 1); $x+=23; }
			if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_modificado, 0, 1, 'R', 1); $x+=23; }
			if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
			//if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, '', 0, 1, 'R', 1); $x+=23; }
			if ($campos[6]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_compromisos, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, '', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[7]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_causado, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, '', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[8]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_pagado, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, '', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[9]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disponible, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, '', 0, 1, 'R', 1); $x+=11;
			}
			$pdf->Ln(2);

		} else {
			if ($sum_compromiso == 0 || $sum_actual == 0) $pcompromiso=0; else $pcompromiso=(float) (($sum_compromiso*100)/$sum_actual); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
			if ($sum_causado == 0 || $sum_actual == 0) $pcausado=0; else $pcausado=(float) (($sum_causado*100)/$sum_actual); $pcausado=number_format($pcausado, 2, ',', '.');
			if ($sum_pagado == 0 || $sum_actual == 0) $ppagado=0; else $ppagado=(float) (($sum_pagado*100)/$sum_actual); $ppagado=number_format($ppagado, 2, ',', '.');
			if ($disponible == 0 || $sum_actual == 0) $pdisponible=0; else $pdisponible=(float) (($disponible*100)/$sum_actual); $pdisponible=number_format($pdisponible, 2, ',', '.');
			$sum_actual=($sum_original+$sum_modificado);
			$sum_disp=$sum_actual - $sum_compromiso;

			$sum_original=number_format($sum_original, 2, ',', '.');
			$sum_aumento=number_format($sum_aumento, 2, ',', '.');
			$sum_disminucion=number_format($sum_disminucion, 2, ',', '.');
			$sum_actual=number_format($sum_actual, 2, ',', '.');
			$sum_compromiso=number_format($sum_compromiso, 2, ',', '.');
			$sum_causado=number_format($sum_causado, 2, ',', '.');
			$sum_pagado=number_format($sum_pagado, 2, ',', '.');
			$sum_disponible=number_format($sum_disp, 2, ',', '.');
			$sum_modificado=number_format($sum_modificado, 2, ',', '.');
			$sum_precompromiso=number_format($sum_precompromiso, 2, ',', '.');

			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$y=$pdf->GetY();
			$pdf->Rect(5, $y, 345, 0.1);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->Ln(3);
			$y=$pdf->GetY();
			$x=5;
			$nb=$pdf->NbLines(73, ''); $hf=3*$nb;
			$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, '', 0, 1, 'C', 1); $x+=20;
			$pdf->SetXY($x, $y); $pdf->MultiCell(73, 3, '', 0, 'L', 1); $x+=73;
			if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_original, 0, 1, 'R', 1); $x+=23; }
			if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_aumento, 0, 1, 'R', 1); $x+=23; }
			if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_disminucion, 0, 1, 'R', 1); $x+=23; }
			if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_modificado, 0, 1, 'R', 1); $x+=23; }
			if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_actual, 0, 1, 'R', 1); $x+=23; }
			//if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_precompromiso, 0, 1, 'R', 1); $x+=23; }
			if ($campos[6]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_compromiso, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, '', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[7]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_causado, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, '', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[8]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_causado, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, '', 0, 1, 'R', 1); $x+=11;
			}
			if ($campos[9]) {
				$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_disponible, 0, 1, 'R', 1); $x+=23;
				$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, '', 0, 1, 'R', 1); $x+=11;
			}
			$pdf->Ln(2);
		}
		break;

	//	Disponibilidad Presupuestaria...
	case "disponibilidad_presupuestaria":
		$pdf=new PDF_MC_Table5('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
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
		disponibilidad_presupuestaria($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto);
		//---------------------------------------------
		$filtro = "";
		if ($anio_fiscal != "") $filtro .= " AND (maestro_presupuesto.anio = '".$anio_fiscal."')";
		if ($financiamiento != "") $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."')";
		if ($tipo_presupuesto != "") $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."')";
		if ($idcategoria != "") $filtro .= " AND (maestro_presupuesto.idcategoria_programatica = '".$idcategoria."')";
		if ($idpartida != "") $filtro .= " AND (maestro_presupuesto.idclasificador_presupuestario = '".$idpartida."')";
		if ($idordinal != "") $filtro .= " AND (maestro_presupuesto.idordinal = '".$idordinal."')";
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
					SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
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
					SUM(maestro_presupuesto.monto_original) AS MontoOriginal,
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
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field = mysql_fetch_array($query);
			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"]!=$IdCategoria) {
				//	--------------------------------
				if ($i!=0) {
					//	IMPRIMO EL TOTAL DE LA CATEGORIA
					if ($sum_actual_categoria != 0) $porcentaje_categoria=(float) $sum_disponible_categoria*100/$sum_actual_categoria;
					else $porcentaje_categoria = 0;
					$psum_disponible_categoria=number_format($porcentaje_categoria, 2, ',', '.');
					$sum_actual_categoria=number_format($sum_actual_categoria, 2, ',', '.');
					$sum_disponible_categoria=number_format($sum_disponible_categoria, 2, ',', '.');
					//
					$y=$pdf->GetY();
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
					$h=0.1; $x=5; $w=205; $pdf->Rect($x, $y+2, $w, $h);
					$pdf->SetXY($x, $y+5);
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetWidths(array(30, 85, 35, 35, 20));
					$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R'));
					$pdf->SetHeight(array(5));
					$pdf->SetFont('Arial', 'B', 7);
					$pdf->Row(array('', utf8_decode('Total Categoría Programática:'), $sum_actual_categoria, $sum_disponible_categoria, $psum_disponible_categoria));
					$sum_actual_categoria=0;
					$sum_disponible_categoria=0;
				}
				//	--------------------------------
				$IdCategoria=$field["IdCategoria"];
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(205, 5, $field["CodCategoria"]." - ".$field["Unidad"], 1, 1, 'L', 1);
				$linea=$pdf->GetY(); if ($linea>250) { disponibilidad_presupuestaria($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto); }
			}
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$monto_actual=number_format($field["MontoActual"], 2, ',', '.');
			$disponible=(float) ($field["MontoActual"]-$field["MontoCompromiso"]-$field["MontoPreCompromiso"]-$field["MontoReservado"]);
			if ($disponible==0 || $field['MontoActual']==0) $pdisponible="0,00"; else  $porcentaje=(float) $disponible*100/$field['MontoActual'];
			$pdisponible=number_format($porcentaje, 2, ',', '.');
			$monto_disponible=number_format($disponible, 2, ',', '.');
			$pdf->SetWidths(array(30, 85, 35, 35, 20));
			$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R'));
			if ($field["Tipo"]=="partida") {
				$sum_total+=$field["MontoOriginal"];
				$sum_disponible+=$disponible;
				$sum_actual_categoria+=$field['MontoActual'];
				$sum_disponible_categoria+=$disponible;
				$sum_actual+=$field['MontoActual'];
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->SetHeight(array(5));
				$pdf->Row(array($clasificador, utf8_decode($field["NomPartida"]), $monto_actual, $monto_disponible, $pdisponible."%"));
			}
			else if ($field["Tipo"]=="generica") {
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->SetHeight(array(5));
				$pdf->Row(array($clasificador, utf8_decode($field["NomPartida"]), $monto_actual, $monto_disponible, $pdisponible."%"));
			}
			else if ($field["Tipo"]=="especifica") {
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 7);
				$pdf->SetHeight(array(3));
				if ($field['codordinal'] == "0000")
					$pdf->Row(array($clasificador, utf8_decode($field["NomPartida"]), $monto_actual, $monto_disponible, $pdisponible."%"));
				else
					$pdf->Row(array($clasificador, utf8_decode($field['codordinal'].' '.$field["nomordinal"]), $monto_actual, $monto_disponible, $pdisponible."%"));
			}
			$linea=$pdf->GetY(); if ($linea>250) { disponibilidad_presupuestaria($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto); }
		}
		//	IMPRIMO EL TOTAL DE LA CATEGORIA
		$porcentaje_categoria=(float) $sum_disponible_categoria*100/$sum_actual_categoria;
		$psum_disponible_categoria=number_format($porcentaje_categoria, 2, ',', '.');
		$sum_actual_categoria=number_format($sum_actual_categoria, 2, ',', '.');
		$sum_disponible_categoria=number_format($sum_disponible_categoria, 2, ',', '.');
		//---------------------------------------------
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=205; $pdf->Rect($x, $y+2, $w, $h);
		$pdf->SetXY($x, $y+5);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetWidths(array(30, 85, 35, 35, 20));
		$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R'));
		$pdf->SetHeight(array(5));
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Row(array('', utf8_decode('Total Categoría Programática:'), $sum_actual_categoria, $sum_disponible_categoria, $psum_disponible_categoria));
		//	IMPRIMO EL TOTAL
		$porcentaje=(float) $sum_disponible*100/$sum_actual;
		$psum_disponible=number_format($porcentaje, 2, ',', '.');
		$sum_actual=number_format($sum_actual, 2, ',', '.');
		$sum_disponible=number_format($sum_disponible, 2, ',', '.');
		//---------------------------------------------
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=205; $pdf->Rect($x, $y+2, $w, $h);
		$pdf->SetXY($x, $y+5);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetWidths(array(30, 85, 35, 35, 20));
		$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R'));
		$pdf->SetHeight(array(5));
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Row(array('', 'Total General:', $sum_actual, $sum_disponible, $psum_disponible));

		break;



	//	Resumen por Actividades...
	case "resumen_actividades":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
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
		resumen_actividades($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos);
		$filtro = "";
		if ($anio_fiscal != "") $filtro .= " AND (maestro_presupuesto.anio = '".$anio_fiscal."')";
		if ($financiamiento != "") $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."')";
		if ($tipo_presupuesto != "") $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."')";
		if ($idcategoria_programatica != "") $filtro .= " AND (maestro_presupuesto.idcategoria_programatica = '".$idcategoria_programatica."')";
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
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$i=0;
		while ($field=mysql_fetch_array($query)) {
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$formulado=number_format($field["Formulado"], 2, ',', '.');
			$modificado=number_format(($field["TotalAumento"] - $field['TotalDisminucion']), 2, ',', '.');
			$actual=number_format($field["Actual"], 2, ',', '.');
			$compromiso=number_format($field["Compromiso"], 2, ',', '.');
			$precompromiso=number_format($field["PreCompromiso"], 2, ',', '.');
			$causado=number_format($field["Causado"], 2, ',', '.');
			$pagado=number_format($field["Pagado"], 2, ',', '.');
			if ($chkrestar)
				$disponible=number_format(($field["Actual"]-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"]), 2, ',', '.');
			else
				$disponible=number_format(($field["Actual"]-$field["Compromiso"]-$field["ReservadoDisminuir"]), 2, ',', '.');
			if ($field["Compromiso"]==0 or $field["Actual"]==0) $pcompromiso="0"; else $pcompromiso=(float) (($field["Compromiso"]*100)/$field["Actual"]); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
			if ($field["Causado"]==0 or $field["Actual"]==0) $pcausado="0"; else $pcausado=(float) (($field["Causado"]*100)/$field["Actual"]); $pcausado=number_format($pcausado, 2, ',', '.');
			if ($field["Pagado"]==0 or $field["Actual"]==0) $ppagado="0"; else $ppagado=(float) (($field["Pagado"]*100)/$field["Actual"]); $ppagado=number_format($ppagado, 2, ',', '.');
			if ($field["Compromiso"]==0 or $field["Actual"]==0) $pdisponible="0"; else
			if (($field["Actual"]-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"])==0) $pdisponible="0"; else $pdisponible=(float) ((($field["Actual"]-$field["Compromiso"])*100)/$field["Actual"]);
			$pdisponible=number_format($pdisponible, 2, ',', '.');
			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($i==0) {
				$IdCategoria=$field["IdCategoria"];
				$CodCategoria=$field["CodCategoria"];
				$Unidad=utf8_decode($field["Unidad"]);
			}
			if ($field["IdCategoria"]!=$IdCategoria) {
				if ($sum_compromiso == 0 or $sum_actual == 0) $tpcompromiso = 0; else $tpcompromiso=(float) (($sum_compromiso*100)/$sum_actual); $tpcompromiso=number_format($tpcompromiso, 2, ',', '.');
				if ($sum_causado == 0 or $sum_actual) $tpcausado = 0; else $tpcausado=(float) (($sum_causado*100)/$sum_actual); $tpcausado=number_format($tpcausado, 2, ',', '.');
				if ($sum_pagado == 0 or $sum_actual == 0) $tppagado = 0; else $tppagado=(float) (($sum_pagado*100)/$sum_actual); $tppagado=number_format($tppagado, 2, ',', '.');
				if ($sum_disponible == 0 or $sum_actual == 0) $tpdisponible = 0; else $tpdisponible=(float) (($sum_disponible*100)/$sum_actual); $tpdisponible=number_format($tpdisponible, 2, ',', '.');
				$sum_aumento=number_format($sum_aumento, 2, ',', '.');
				$sum_disminucion=number_format($sum_disminucion, 2, ',', '.');
				$sum_formulado=number_format($sum_formulado, 2, ',', '.');
				$sum_modificado=number_format($sum_modificado, 2, ',', '.');
				$sum_actual=number_format($sum_actual, 2, ',', '.');
				$sum_precompromiso=number_format($sum_precompromiso, 2, ',', '.');
				$sum_compromiso=number_format($sum_compromiso, 2, ',', '.');
				$sum_causado=number_format($sum_causado, 2, ',', '.');
				$sum_pagado=number_format($sum_pagado, 2, ',', '.');
				$sum_disponible=number_format($sum_disponible, 2, ',', '.');

				$h++;
				if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
				else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }

				$y=$pdf->GetY();
				$x=5;
				$nb=$pdf->NbLines(50, utf8_decode($Unidad)); $hf=3*$nb;
				$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $CodCategoria, 0, 1, 'C', 1); $x+=20;
				$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, utf8_decode($Unidad), 0, 'L', 1); $x+=50;
				if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_formulado, 0, 1, 'R', 1); $x+=23; }
				if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_aumento, 0, 1, 'R', 1); $x+=23; }
				if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_disminucion, 0, 1, 'R', 1); $x+=23; }
				if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_modificado, 0, 1, 'R', 1); $x+=23; }
				if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_actual, 0, 1, 'R', 1); $x+=23; }
				if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_precompromiso, 0, 1, 'R', 1); $x+=23; }
				if ($campos[6]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_compromiso, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tpcompromiso.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[7]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_causado, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tpcausado.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[8]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_pagado, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tppagado.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[9]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_disponible, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tpdisponible.' %', 0, 1, 'R', 1); $x+=11;
				}
				$pdf->Ln(2);

				$linea=$pdf->GetY(); if ($linea>175) { resumen_actividades($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos); }

				$sum_aumento=""; $sum_disminucion=""; $sum_formulado=""; $sum_actual=""; $sum_modificado=""; $sum_precompromiso=""; $sum_precompromiso=""; $sum_compromiso=""; $tpcompromiso=""; $sum_causado=""; $tpcausado=""; $sum_pagado=""; $tppagado=""; $sum_disponible=""; $tpdisponible="";

				$IdCategoria=$field["IdCategoria"];
				$CodCategoria=$field["CodCategoria"];
				$Unidad=utf8_decode($field["Unidad"]);
			}

			if ($field["Tipo"]=="partida") {
				$sum_formulado+=$field["Formulado"];
				$sum_modificado += ($field["TotalAumento"] - $field['TotalDisminucion']);
				$sum_aumento += $field["TotalAumento"];
				$sum_disminucion += $field['TotalDisminucion'];
				$sum_actual+=$field["Actual"];
				$sum_compromiso+=$field["Compromiso"];
				$sum_precompromiso+=$field["PreCompromiso"];
				$sum_causado+=$field["Causado"];
				$sum_pagado+=$field["Pagado"];
				if ($chkrestar)
					$sum_disponible+=($field["Actual"]-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"]);
				else
					$sum_disponible+=($field["Actual"]-$field["PreCompromiso"]-$field["ReservadoDisminuir"]);
			}
			$i++;
		}
		if ($sum_compromiso == 0 or $sum_actual == 0) $tpcompromiso = 0; else $tpcompromiso=(float) (($sum_compromiso*100)/$sum_actual); $tpcompromiso=number_format($tpcompromiso, 2, ',', '.');
		if ($sum_causado == 0 or $sum_actual) $tpcausado = 0; else $tpcausado=(float) (($sum_causado*100)/$sum_actual); $tpcausado=number_format($tpcausado, 2, ',', '.');
		if ($sum_pagado == 0 or $sum_actual == 0) $tppagado = 0; else $tppagado=(float) (($sum_pagado*100)/$sum_actual); $tppagado=number_format($tppagado, 2, ',', '.');
		if ($sum_disponible == 0 or $sum_actual == 0) $tpdisponible = 0; else $tpdisponible=(float) (($sum_disponible*100)/$sum_actual); $tpdisponible=number_format($tpdisponible, 2, ',', '.');
		$sum_aumento=number_format($sum_aumento, 2, ',', '.');
		$sum_disminucion=number_format($sum_disminucion, 2, ',', '.');
		$sum_formulado=number_format($sum_formulado, 2, ',', '.');
		$sum_modificado=number_format($sum_modificado, 2, ',', '.');
		$sum_actual=number_format($sum_actual, 2, ',', '.');
		$sum_precompromiso=number_format($sum_precompromiso, 2, ',', '.');
		$sum_compromiso=number_format($sum_compromiso, 2, ',', '.');
		$sum_causado=number_format($sum_causado, 2, ',', '.');
		$sum_pagado=number_format($sum_pagado, 2, ',', '.');
		$sum_disponible=number_format($sum_disponible, 2, ',', '.');

		$h++;
		if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
		else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }

		$y=$pdf->GetY();
		$x=5;
		$nb=$pdf->NbLines(50, utf8_decode($Unidad)); $hf=3*$nb;
		$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $CodCategoria, 0, 1, 'C', 1); $x+=20;
		$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, utf8_decode($Unidad), 0, 'L', 1); $x+=50;
		if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_formulado, 0, 1, 'R', 1); $x+=23; }
		if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_aumento, 0, 1, 'R', 1); $x+=23; }
		if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_disminucion, 0, 1, 'R', 1); $x+=23; }
		if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_modificado, 0, 1, 'R', 1); $x+=23; }
		if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_actual, 0, 1, 'R', 1); $x+=23; }
		if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_precompromiso, 0, 1, 'R', 1); $x+=23; }
		if ($campos[6]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_compromiso, 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tpcompromiso.' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[7]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_causado, 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tpcausado.' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[8]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_pagado, 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tppagado.' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[9]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $sum_disponible, 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $tpdisponible.' %', 0, 1, 'R', 1); $x+=11;
		}
		$pdf->Ln(2);
		break;

	//	Consolidado por Sector...
	case "consolidado_sector_resumido":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//---------------------------------------------
		$campos = explode("|", $checks);
		//---------------------------------------------
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//---------------------------------------------
		consolidado_sector($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $fdesde, $fhasta);
		if ($_GET["idsector"]!=0) $filtro="AND (sector.idsector='".$_GET["idsector"]."')";
		////////////

		//	CONSULTO TODO PARA SUMAR LAS ESPECIFICAS
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
				WHERE
					mp.anio = '".$anio_fiscal."' AND
					mp.idfuente_financiamiento = '".$financiamiento."'  AND
					mp.idtipo_presupuesto = '".$tipo_presupuesto."'
				GROUP BY CodSector, idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal
				ORDER BY CodSector, idcategoria_programatica, Par, Gen, Esp, Sesp, idordinal
		";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			$par = $field["CodSector"].$field['Par'];

			$_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'];
			$_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'];
			$_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'];
			$_RESERVADO[$par] += $field['ReservadoDisminucion'];
			$_CAUSADO[$par] += $field['Causado'];
			$_PAGADO[$par] += $field['Pagado'];
		}

		$pdf->Ln(2);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(100, 100, 100); $pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 5);
		$pdf->Cell(10, 4, 'Sector', 0, 0, 'C', 1);
		$pdf->Cell(83, 4, 'Descripcion', 0, 0, 'L', 1);
		if ($campos[0]) $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
		if ($campos[1]) $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
		if ($campos[2]) $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
		if ($campos[3]) $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
		if ($campos[4]) $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
		if ($campos[6]) { $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1); }
		if ($campos[7]) { $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1); }
		if ($campos[8]) { $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1); }
		if ($campos[9]) { $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1); }
		$pdf->Ln(4);

		//	CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO	O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
		$sql="SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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
					  categoria_programatica.idsector AS IdSector,
					  sector.codigo AS CodSector,
					  sector.denominacion AS Sector,
					maestro_presupuesto.idRegistro AS IdPresupuesto,
					SUM(maestro_presupuesto.monto_original) AS Formulado,
					'0000' AS codordinal,
					'' AS nomordinal
				FROM
					maestro_presupuesto,
					categoria_programatica,
					unidad_ejecutora,
					clasificador_presupuestario,
					ordinal,
					sector
				WHERE
					((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
					(clasificador_presupuestario.sub_especifica='00') AND
					(ordinal.codigo='0000' AND maestro_presupuesto.idordinal = ordinal.idordinal) AND
					(maestro_presupuesto.anio='".$anio_fiscal."' AND
					maestro_presupuesto.idfuente_financiamiento='".$financiamiento."') AND
					 (categoria_programatica.idsector = sector.idSector) $filtro)
				GROUP BY (CodSector), (Par), (Gen), (Esp), (Sesp), (codordinal)
				ORDER BY CodSector, Par, Gen, Esp, Sesp, codordinal";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);

			//	SI CAMBIA DE SECTOR LA IMPRIMO
			if ($field["IdSector"]!=$IdSector) {
				$IdSector=$field["IdSector"];
				if ($i!=0) {	$l++;
					$sum_modificado = $sum_aumento - $sum_disminucion;
					$sum_actual = $sum_formulado + ($sum_modificado);

					//	IMPRIMO LOS TOTALES
					if ($sum_compromiso != 0 && $sum_actual != 0) $tpcompromiso = $sum_compromiso * 100 / $sum_actual; else $tpcompromiso = 0;
					if ($sum_causado != 0 && $sum_actual != 0) $tpcausado = $sum_causado * 100 / $sum_actual; else $tpcausado = 0;
					if ($sum_pagado != 0 && $sum_actual != 0) $tppagado = $sum_pagado * 100 / $sum_actual; else $tppagado = 0;
					if ($sum_disponible != 0 && $sum_actual != 0) $tpdisponible = $sum_disponible * 100 / $sum_actual; else $tpdisponible = 0;

					if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_formulado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_aumento, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disminucion, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_modificado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_actual, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
					if ($campos[6]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_compromiso, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpcompromiso, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[7]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_causado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpcausado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[8]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_pagado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tppagado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[9]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disponible, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpdisponible, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
					}
					$pdf->Ln(2);
					$linea=$pdf->GetY(); if ($linea>175) {
						consolidado_sector($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $fdesde, $fhasta);
						$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(100, 100, 100); $pdf->SetTextColor(255, 255, 255);
						$pdf->SetFont('Arial', 'B', 5);
						$pdf->Cell(10, 4, 'Partida', 0, 0, 'C', 1);
						$pdf->Cell(83, 4, 'Descripcion', 0, 0, 'L', 1);
						if ($campos[0]) $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
						if ($campos[1]) $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
						if ($campos[2]) $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
						if ($campos[3]) $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
						if ($campos[4]) $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
						if ($campos[6]) { $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1); }
						if ($campos[7]) { $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1); }
						if ($campos[8]) { $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1); }
						if ($campos[9]) { $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1); $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1); }
						$pdf->Ln(4);
					}
				}
				if ($l%2 == 0) $pdf->SetFillColor(255, 255, 255); else $pdf->SetFillColor(225, 225, 225);
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 6);

				$y=$pdf->GetY();
				$x=5;
				$nb=$pdf->NbLines(83, utf8_decode($field["Sector"])); $hf=3*$nb;
				$pdf->SetXY($x, $y); $pdf->Cell(10, $hf, $field["CodSector"], 0, 1, 'C', 1); $x+=10;
				$pdf->SetXY($x, $y); $pdf->MultiCell(83, 3, utf8_decode($field["Sector"]), 0, 'L', 1); $x+=83;

				$sum_formulado = 0; $sum_aumento = 0; $sum_disminucion = 0; $sum_modificado = 0; $sum_actual = 0;
				$sum_compromiso = 0; $sum_causado = 0; $sum_pagado = 0; $sum_disponible = 0;
			}

			$par = $field['CodSector'].$field['Par'];

			$modificacion = $_AUMENTO[$par] - $_DISMINUCION[$par];
			$actual = $field['Formulado'] + $modificacion;
			$disponible = $actual - $_COMPROMISO[$par] - $_RESERVADO[$par];

			$sum_formulado += $field['Formulado'];
			$sum_aumento += $_AUMENTO[$par];
			$sum_disminucion += $_DISMINUCION[$par];
			$sum_modificacion += $modificacion;
			$sum_actual += $actual;
			$sum_compromiso += $_COMPROMISO[$par];
			$sum_causado += $_CAUSADO[$par];
			$sum_pagado += $_PAGADO[$par];
			$sum_disponible += $disponible;

			$total_sum_formulado += $field['Formulado'];
			$total_sum_aumento += $_AUMENTO[$par];
			$total_sum_disminucion += $_DISMINUCION[$par];
			$total_sum_modificacion += $modificacion;
			$total_sum_actual += $actual;
			$total_sum_compromiso += $_COMPROMISO[$par];
			$total_sum_causado += $_CAUSADO[$par];
			$total_sum_pagado += $_PAGADO[$par];
			$total_sum_disponible += $disponible;
		}
		//------------------------------------------------
		$sum_modificado = $sum_aumento - $sum_disminucion;
		$sum_actual = $sum_formulado + ($sum_modificado);

		if ($sum_compromiso != 0 && $sum_actual != 0) $tpcompromiso = $sum_compromiso * 100 / $sum_actual; else $tpcompromiso = 0;
		if ($sum_causado != 0 && $sum_actual != 0) $tpcausado = $sum_causado * 100 / $sum_actual; else $tpcausado = 0;
		if ($sum_pagado != 0 && $sum_actual != 0) $tppagado = $sum_pagado * 100 / $sum_actual; else $tppagado = 0;
		if ($sum_disponible != 0 && $sum_actual != 0) $tpdisponible = $sum_disponible * 100 / $sum_actual; else $tpdisponible = 0;

		if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_formulado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_aumento, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disminucion, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_modificado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_actual, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[6]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_compromiso, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpcompromiso, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[7]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_causado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpcausado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[8]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_pagado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tppagado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[9]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($sum_disponible, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($tpdisponible, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		//------------------------------------------------
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
		//	TOTALES
		$total_sum_modificado = $total_sum_aumento - $total_sum_disminucion;
		$total_sum_actual = $total_sum_formulado + ($total_sum_modificado);

		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
		//	IMPRIMO LOS TOTALES
		if ($total_sum_compromiso != 0 && $total_sum_actual != 0) $total_tpcompromiso = $total_sum_compromiso * 100 / $total_sum_actual; else $total_tpcompromiso = 0;
		if ($total_sum_causado != 0 && $total_sum_actual != 0) $total_tpcausado = $total_sum_causado * 100 / $total_sum_actual; else $total_tpcausado = 0;
		if ($total_sum_pagado != 0 && $total_sum_actual != 0) $total_tppagado = $total_sum_pagado * 100 / $total_sum_actual; else $total_tppagado = 0;
		if ($total_sum_disponible != 0 && $total_sum_actual != 0) $total_tpdisponible = $total_sum_disponible * 100 / $total_sum_actual; else $total_tpdisponible = 0;

		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetXY($x, $y+5);

		$y=$pdf->GetY();
		$x=98;

		if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($total_sum_formulado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($total_sum_aumento, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($total_sum_disminucion, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($total_sum_modificado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($total_sum_actual, 2, ',', '.'), 0, 1, 'R', 1); $x+=23; }
		if ($campos[6]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($total_sum_compromiso, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($total_tpcompromiso, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[7]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($total_sum_causado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($total_tpcausado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[8]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($total_sum_pagado, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($total_tppagado, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		if ($campos[9]) {
			$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, number_format($total_sum_disponible, 2, ',', '.'), 0, 1, 'R', 1); $x+=23;
			$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, number_format($total_tpdisponible, 2, ',', '.').' %', 0, 1, 'R', 1); $x+=11;
		}
		break;

	//	Movimientos por Partidas...
	case "movimientos_por_partida":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//---------------------------------------------
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//---------------------------------------------
		movimientos_por_partida($pdf, $_GET['idcategoria'], $_GET['idpartida'], $anio_fiscal, $financiamiento, $tipo_presupuesto, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idordinal);
		//----------------------------------------------------
		$filtro=" AND (maestro_presupuesto.idcategoria_programatica='".$_GET['idcategoria']."') AND (maestro_presupuesto.idclasificador_presupuestario='".$_GET['idpartida']."')";
		//---------------------------------------------
		if ($idordinal != "") $where_ordinal = " AND maestro_presupuesto.idordinal = '".$idordinal."' ";
		//---------------------------------------------
		$sql="(SELECT maestro_presupuesto.idRegistro,
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
					  (maestro_presupuesto.anio='".$anio_fiscal."' AND
					  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
					  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
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
						(maestro_presupuesto.anio='".$anio_fiscal."' AND
						maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
						maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
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
						(maestro_presupuesto.anio='".$anio_fiscal."' AND
						maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
						maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
					GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp))
					ORDER BY CodCategoria, Par, Gen, Esp, Sesp";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$idregistro=$field["idRegistro"];
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$monto_original=number_format($field["MontoOriginal"], 2, ',', '.');
			$monto_actual=number_format($field["MontoActual"], 2, ',', '.');
			$monto_compromiso=number_format($field["MontoCompromiso"], 2, ',', '.');
			$monto_pre_compromiso=number_format($field["MontoPreCompromiso"], 2, ',', '.');
			$monto_reservado=number_format($field["MontoReservadoDisminuir"], 2, ',', '.');
			$monto_solicitud=number_format($field["MontoSolicitudAumento"], 2, ',', '.');
			$monto_causado=number_format($field["TotalCausados"], 2, ',', '.');
			$monto_pagado=number_format($field["TotalPagados"], 2, ',', '.');
			$disponible=(float) ($field["MontoActual"]-$field["MontoCompromiso"]-$field["MontoReservadoDisminuir"]-$field["MontoPreCompromiso"]);
			$monto_disponible=number_format($disponible, 2, ',', '.');
			if ($field["MontoCompromiso"]==0 || $field["MontoActual"]==0) $pcompromiso="0"; else $pcompromiso=(float) (($field[MontoCompromiso]*100)/$field["MontoActual"]); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
			if ($field["MontoCausado"]==0 || $field["MontoActual"]==0) $pcausado="0"; else $pcausado=(float) (($field["MontoCausado"]*100)/$field["MontoActual"]); $pcausado=number_format($pcausado, 2, ',', '.');
			if ($field["MontoPagado"]==0 || $field["MontoActual"]==0) $ppagado="0"; else $ppagado=(float) (($field["MontoPagado"]*100)/$field["MontoActual"]); $ppagado=number_format($ppagado, 2, ',', '.');
			if ($disponible==0 || $field["MontoActual"]==0) $pdisponible="0"; else $pdisponible=(float) (($disponible*100)/$field["MontoActual"]); $pdisponible=number_format($pdisponible, 2, ',', '.');

			if ($field["Tipo"]=="especifica") {
				//	----------------------------
				$sum_original+=$field["MontoOriginal"];
				$sum_aumento+=$field["TotalAumento"];
				$sum_disminucion+=$field["TotalDisminucion"];
				$sum_actual+=$field["MontoActual"];
				$sum_compromiso+=$field["MontoCompromiso"];
				$sum_reservado+=$field["MontoReservadoDisminuir"];
				$sum_solicitud+=$field["MontoSolicitudAumento"];
				$sum_causado+=$field["TotalCausados"];
				$sum_pagado+=$field["TotalPagados"];
				$sum_disponible+=$disponible;
				//	----------------------------
				$total_aumento=number_format($field["TotalAumento"], 2, ',', '.');
				$total_disminucion=number_format($field["TotalDisminucion"], 2, ',', '.');
				//	CONSULTO PARA OPBTENER LOS AUMENTOS Y DISMINUCIONES DE LAS PARTIDAS E IMPRIMIRLAS
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
							 (traslados_presupuestarios.anio='".$anio_fiscal."')

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
							(creditos_adicionales.anio='".$anio_fiscal."')

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
							(rectificacion_presupuesto.anio='".$anio_fiscal."')

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
							(traslados_presupuestarios.anio='".$anio_fiscal."')

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
							(disminucion_presupuesto.anio='".$anio_fiscal."')

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
							(rectificacion_presupuesto.anio='".$anio_fiscal."')

						ORDER BY fecha_solicitud";
				$query_detalle=mysql_query($sql) or die ($sql.mysql_error());
				$rows_detalle=mysql_num_rows($query_detalle);
				for ($k=0; $k<$rows_detalle; $k++) {
					$detalle=mysql_fetch_array($query_detalle);
					$monto=number_format($detalle['monto_acreditar'], 2, ',', '.');
					list($a, $m, $d)=SPLIT( '[/.-]', $detalle['fecha_solicitud']); $fecha_solicitud=$d."/".$m."/".$a;
					if ($detalle['tipo']=="Traslado(+)" || $detalle['tipo']=="Credito Adicional" || $detalle['tipo']=="Rectificacion(+)") {
						$disminucion=""; $compromisos=""; $causado="";
						if ($detalle['estado']=="Anulado") { $anulado="(ANULADO)"; $aumento="($aumento)"; $tr5="font-size:12px; color:#FF0000;"; } else $anulado="";
						if ($detalle['estado']=="elaboracion") $solicitud_aumento=$monto; else $aumento=$monto;
						$detalle['tipo']=$detalle['tipo']." ".$anulado;
					}
					else if ($detalle['tipo']=="Traslado(-)" || $detalle['tipo']=="Disminucion Presupuestaria" || $detalle['tipo']=="Rectificacion(-)") {
						$aumento=""; $compromisos=""; $causado="";
						if ($detalle['estado']=="Anulado") { $anulado="(ANULADO)"; $disminucion="($disminucion)"; $tr5="font-size:12px; color:#FF0000;"; } else $anulado="";
						if ($detalle['estado']=="elaboracion") $reservado_disminuir=$monto; else $disminucion=$monto;
						$detalle['tipo']=$detalle['tipo']." ".$anulado;
					}
					else if ($detalle['tipo']=="Orden de Compra/Servicio") {
						$disminucion=""; $aumento=""; $compromisos=$monto; $causado="";
						if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $compromisos="($compromisos)"; $tr5="font-size:12px; color:#FF0000;"; } else $anulado="";
						$detalle['tipo']=$detalle['justificacion']." ".$anulado;
					}
					else if ($detalle['tipo']=="Orden de Pago") {
						$disminucion=""; $aumento="";
						if ($detalle['Causa']=="si") { $causado=$monto; if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $causado="($causado)"; $tr5="font-size:12px; color:#FF0000;"; } else $anulado=""; }
						if ($detalle['Compromete']=="si"){ $compromisos=$monto; if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $compromisos="($compromisos)"; $tr5="font-size:12px; color:#FF0000;"; } else $anulado=""; }
						$detalle['tipo']=$detalle['justificacion']." ".$anulado;
						if ($detalle['estado']=="pagada") $imprimir_ocs="SI";
					}
					else if ($detalle['tipo']=="Cheque") {
						$disminucion=""; $aumento=""; $compromisos=""; $causado=""; $pagado=$monto;
						if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $pagado="($pagado)"; $tr5="font-size:12px; color:#FF0000;"; } else $anulado="";
						$detalle['tipo']=$detalle['justificacion']." ".$anulado;
						$imprimir_op="SI";
					}
					else if ($detalle['tipo']=="Requisicion") {
						$detalle['tipo']=$detalle['justificacion'];
						$precompromiso=number_format($detalle['monto_acreditar'], 2, ',', '');
						$sum_precompromiso+=$detalle['monto_acreditar'];
					}
					if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
					else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(225, 225, 225); $pdf->SetTextColor(0, 0, 0); }
					$pdf->SetFont('Arial', '', 6);
						$pdf->Row(array($detalle['nro_solicitud'], $fecha_solicitud, utf8_decode($detalle['tipo']), $aumento, $solicitud_aumento, $disminucion, $reservado_disminuir));
					$linea=$pdf->GetY(); if ($linea>190) { movimientos_por_partida_tablas($pdf); $pdf->SetY(40); $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }

					if ($imprimir_ocs=="SI") {
						$sql="SELECT rpc.idorden_compra_servicio, ocs.numero_orden FROM relacion_pago_compromisos rpc INNER JOIN orden_compra_servicio ocs ON (rpc.idorden_compra_servicio=ocs.idorden_compra_servicio) WHERE rpc.idorden_pago='".$detalle['id']."'";
						$query_pagados=mysql_query($sql) or die ($sql.mysql_error());
						while ($field_pagados=mysql_fetch_array($query_pagados)) {
							$ordenes.=$field_pagados['numero_orden'].", ";
						}
						$pdf->Row(array('', '', $ordenes, '', '', '', ''));
						$linea=$pdf->GetY(); if ($linea>190) { movimientos_por_partida_tablas($pdf); $pdf->SetY(40); }
					}

					if ($imprimir_op=="SI") {
						$sql="SELECT pf.idorden_pago, op.numero_orden FROM pagos_financieros pf INNER JOIN orden_pago op ON (pf.idorden_pago=op.idorden_pago) WHERE pf.idpagos_financieros='".$detalle['id']."'";
						$query_pagados=mysql_query($sql) or die ($sql.mysql_error());
						while ($field_pagados=mysql_fetch_array($query_pagados)) {
							$ordenes.=$field_pagados['numero_orden'].", ";
						}
						$pdf->Row(array('', '', $ordenes, '', '', '', ''));
						$linea=$pdf->GetY(); if ($linea>190) { movimientos_por_partida_tablas($pdf); $pdf->SetY(40); }
					}

					$h++;
					$disminucion=""; $aumento=""; $compromisos=""; $causado=""; $pagado=""; $anulado=""; $monto_actual=""; $total_disminucion=""; $precompromiso=""; $imprimir_ocs=""; $imprimir_op=""; $ordenes="";
				}
			}
		}
		//---------------------------------------------
		if ($sum_actual==0) $pcompromiso=0; else $pcompromiso=(float) (($sum_compromiso*100)/$sum_actual); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
		if ($sum_actual==0) $pcausado=0; else $pcausado=(float) (($sum_causado*100)/$sum_actual); $pcausado=number_format($pcausado, 2, ',', '.');
		if ($sum_actual==0) $ppagado=0; else $ppagado=(float) (($sum_pagado*100)/$sum_actual); $ppagado=number_format($ppagado, 2, ',', '.');
		if ($sum_actual==0) $pdisponible=0; else $pdisponible=(float) (($disponible*100)/$sum_actual); $pdisponible=number_format($pdisponible, 2, ',', '.');
		$sum_original=number_format($sum_original, 2, ',', '.');
		$sum_aumento=number_format($sum_aumento, 2, ',', '.');
		$sum_disminucion=number_format($sum_disminucion, 2, ',', '.');
		$sum_solicitud_aumento=number_format($sum_solicitud, 2, ',', '.');
		$sum_reservado_disminuir=number_format($sum_reservado, 2, ',', '.');
		$sum_actual=number_format($sum_actual, 2, ',', '.');
		$sum_compromiso=number_format($sum_compromiso, 2, ',', '.');
		$sum_causado=number_format($sum_causado, 2, ',', '.');
		$sum_pagado=number_format($sum_pagado, 2, ',', '.');
		$sum_disponible=number_format($sum_disponible, 2, ',', '.');
		$sum_precompromiso=number_format($sum_precompromiso, 2, ',', '.');

		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$y=$pdf->GetY();
		$pdf->Rect(5, $y+5, 270, 0.1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetAligns(array('C','C', 'L', 'R', 'R', 'R', 'R'));
		$pdf->SetWidths(array(30, 20, 100, 30, 30, 30, 30));
		$pdf->SetHeight(array(5));
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->SetY($y+8);
		$pdf->Row(array('', '', '', $sum_aumento, $sum_solicitud_aumento, $sum_disminucion, $sum_reservado_disminuir));
		break;

	//	Movimientos por Categoria...
	case "movimientos_por_categoria":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//---------------------------------------------
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//---------------------------------------------
		movimientos_por_categoria($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto);
		if ($idcategoria_programatica=='0') $filtro=""; else $filtro=" AND (maestro_presupuesto.idcategoria_programatica='".$idcategoria_programatica."')";
		//---------------------------------------------
		$sql="(SELECT maestro_presupuesto.idRegistro,
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
					  (maestro_presupuesto.anio='".$anio_fiscal."' AND
					  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
					  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
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
						(maestro_presupuesto.anio='".$anio_fiscal."' AND
						maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
						maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
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
					(maestro_presupuesto.anio='".$anio_fiscal."' AND
					maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
					maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$idregistro=$field["idRegistro"];
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$precompromiso=number_format($field["PreCompromiso"], 2, ',', '.');
			$total_aumento=number_format($field["TotalAumento"], 2, ',', '.');
			$solicitud_aumento=number_format($field["SolicitudAumento"], 2, ',', '.');
			$total_disminucion=number_format($field["TotalDisminucion"], 2, ',', '.');
			$reservado_disminuir=number_format($field["ReservadoDisminuir"], 2, ',', '.');
			$monto_original=number_format($field["MontoOriginal"], 2, ',', '.');
			$monto_actual=number_format($field["MontoActual"], 2, ',', '.');
			$monto_compromiso=number_format($field["MontoCompromiso"], 2, ',', '.');
			$monto_causado=number_format($field["TotalCausados"], 2, ',', '.');
			$monto_pagado=number_format($field["TotalPagados"], 2, ',', '.');
			$disponible=(float) ($field["MontoActual"]-$field["PreCompromiso"]-$field["MontoCompromiso"]-$field["ReservadoDisminuir"]);
			$monto_disponible=number_format($disponible, 2, ',', '.');
			if ($field["MontoCompromiso"]==0 || $field["MontoActual"]==0) $pcompromiso="0"; else $pcompromiso=(float) (($field["MontoCompromiso"]*100)/$field["MontoActual"]); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
			if ($field["TotalCausados"]==0 || $field["MontoActual"]==0) $pcausado="0"; else $pcausado=(float) (($field["TotalCausados"]*100)/$field["MontoActual"]); $pcausado=number_format($pcausado, 2, ',', '.');
			if ($field["TotalPagados"]==0 || $field["MontoActual"]==0) $ppagado="0"; else $ppagado=(float) (($field["TotalPagados"]*100)/$field["MontoActual"]); $ppagado=number_format($ppagado, 2, ',', '.');
			if ($disponible==0 || $field["MontoActual"]==0) $pdisponible="0"; else $pdisponible=(float) (($disponible*100)/$field["MontoActual"]); $pdisponible=number_format($pdisponible, 2, ',', '.');

			if ($field['TotalAumento']==0) $total_aumento="";
			if ($field['SolicitudAumento']==0) $solicitud_aumento="";
			if ($field['TotalDisminucion']==0) $total_disminucion="";
			if ($field['ReservadoDisminuir']==0) $reservado_disminuir="";

			if ($field["Tipo"]=="partida") {
				$par_clasificador = $clasificador;
				$par_denominacion = utf8_decode($field['NomPartida']);
				$par_monto_original = $monto_original;
				$par_total_aumento = $total_aumento;
				$par_solicitud_aumento = $solicitud_aumento;
				$par_total_disminucion = $total_disminucion;
				$par_reservado_disminuir = $reservado_disminuir;
				$par_monto_actual = $monto_actual;
			}
			else if ($field["Tipo"]=="generica") {
				$gen_clasificador = $clasificador;
				$gen_denominacion = utf8_decode($field['NomPartida']);
				$gen_monto_original = $monto_original;
				$gen_total_aumento = $total_aumento;
				$gen_solicitud_aumento = $solicitud_aumento;
				$gen_total_disminucion = $total_disminucion;
				$gen_reservado_disminuir = $reservado_disminuir;
				$gen_monto_actual = $monto_actual;
			}
			else if ($field["Tipo"]=="especifica") {
				//	CONSULTO PARA OPBTENER LOS AUMENTOS Y DISMINUCIONES DE LAS PARTIDAS E IMPRIMIRLAS
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
							 (traslados_presupuestarios.anio='".$anio_fiscal."')

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
							(creditos_adicionales.anio='".$anio_fiscal."')

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
							(rectificacion_presupuesto.anio='".$anio_fiscal."')

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
							(traslados_presupuestarios.anio='".$anio_fiscal."')

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
							(disminucion_presupuesto.anio='".$anio_fiscal."')

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
							(rectificacion_presupuesto.anio='".$anio_fiscal."')

						ORDER BY fecha_solicitud";
				$query_detalle=mysql_query($sql) or die ($sql.mysql_error());
				$rows_detalle=mysql_num_rows($query_detalle);

				if ($rows_detalle != 0) {
					//	SI CAMBIA DE CATEGORIA LA IMPRIMO
					if ($field["IdCategoria"]!=$IdCategoria) {
						$IdCategoria=$field["IdCategoria"];
						$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('Arial', 'B', 7);
						$pdf->Cell(205, 5, $field["CodCategoria"]." - ".$field["Unidad"], 1, 1, 'L', 1);
						$linea=$pdf->GetY(); if ($linea>250) { ejecucion_detallada($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos); }
					}

					//	 imprimo la partida
					if ($par_clasificador != "") {
						$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('Arial', 'B', 6);
						$pdf->Row(array($par_clasificador, $par_denominacion, $par_monto_original, $par_total_aumento, $par_solicitud_aumento, $par_total_disminucion, $par_reservado_disminuir, $par_monto_actual));
						$linea=$pdf->GetY(); if ($linea>175) { movimientos_por_categoria($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto); }
					}

					//	imprimo la generica
					if ($gen_clasificador != "") {
						$h++;
						if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
						else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
						$pdf->SetFont('Arial', 'B', 6);
						$pdf->Row(array($gen_clasificador, $gen_denominacion, $gen_monto_original, $gen_total_aumento, $gen_solicitud_aumento, $gen_total_disminucion, $gen_reservado_disminuir, $gen_monto_actual));
						$linea=$pdf->GetY(); if ($linea>175) { movimientos_por_categoria($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto); }
					}

					//	imprimo la especifica
					$sum_original+=$field["MontoOriginal"];
					$sum_aumento+=$field["TotalAumento"];
					$sum_disminucion+=$field["TotalDisminucion"];
					$sum_reservado_disminuir+=$field["ReservadoDisminuir"];
					$sum_solicitud_aumento+=$field["SolicitudAumento"];
					$sum_actual+=$field["MontoActual"];
					$sum_compromiso+=$field["MontoCompromiso"];
					$sum_causado+=$field["TotalCausados"];
					$sum_pagado+=$field["TotalPagados"];
					$sum_disponible+=$disponible;
					//	----------------------------
					$h++;
					if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
					else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
					$pdf->SetFont('Arial', '', 6); $pos=$pdf->GetY();

					if ($field['codordinal'] != "0000") $descripcion_partida = $field['codordinal']." ".$field['nomordinal'];
					else $descripcion_partida = $field['NomPartida'];

					$pdf->Row(array($clasificador, utf8_decode($descripcion_partida), $monto_original, $total_aumento, $solicitud_aumento, $total_disminucion, $reservado_disminuir, $monto_actual));

					$total_disminucion=""; $monto_actual=""; $reservado_disminuir=""; $solicitud_aumento="";
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
					$linea=$pdf->GetY(); if ($linea>175) { movimientos_por_categoria($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto); }

					for ($k=0; $k<$rows_detalle; $k++) {
						$detalle=mysql_fetch_array($query_detalle);
						$monto=number_format($detalle['monto_acreditar'], 2, ',', '.');
						list($a, $m, $d)=SPLIT( '[/.-]', $detalle['fecha_solicitud']); $fecha_solicitud=$d."/".$m."/".$a;
						if ($detalle['tipo']=="Traslado(+)" || $detalle['tipo']=="Credito Adicional" || $detalle['tipo']=="Rectificacion(+)") {
							$disminucion=""; $compromisos=""; $causado="";
							if ($detalle['estado']=="Anulado") { $anulado="(ANULADO)"; $aumento="($aumento)"; $tr5="font-size:12px; color:#FF0000;"; } else $anulado="";
							if ($detalle['estado']=="elaboracion") $solicitud_aumento=$monto; else $aumento=$monto;
							$detalle['tipo']=$detalle['tipo']." ".$anulado;
						}
						else if ($detalle['tipo']=="Traslado(-)" || $detalle['tipo']=="Disminucion Presupuestaria" || $detalle['tipo']=="Rectificacion(-)") {
							$aumento=""; $compromisos=""; $causado="";
							if ($detalle['estado']=="Anulado") { $anulado="(ANULADO)"; $disminucion="($disminucion)"; $tr5="font-size:12px; color:#FF0000;"; } else $anulado="";
							if ($detalle['estado']=="elaboracion") $reservado_disminuir=$monto; else $disminucion=$monto;
							$detalle['tipo']=$detalle['tipo']." ".$anulado;
						}
						else if ($detalle['tipo']=="Orden de Compra/Servicio") {
							$disminucion=""; $aumento=""; $compromisos=$monto; $causado="";
							if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $compromisos="($compromisos)"; $tr5="font-size:12px; color:#FF0000;"; } else $anulado="";
							$detalle['tipo']=$detalle['justificacion']." ".$anulado;
						}
						else if ($detalle['tipo']=="Orden de Pago") {
							$disminucion=""; $aumento="";
							if ($detalle['Causa']=="si") { $causado=$monto; if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $causado="($causado)"; $tr5="font-size:12px; color:#FF0000;"; } else $anulado=""; }
							if ($detalle['Compromete']=="si"){ $compromisos=$monto; if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $compromisos="($compromisos)"; $tr5="font-size:12px; color:#FF0000;"; } else $anulado=""; }
							$detalle['tipo']=$detalle['justificacion']." ".$anulado;
							if ($detalle['estado']=="pagada") $imprimir_ocs="SI";
						}
						else if ($detalle['tipo']=="Cheque") {
							$disminucion=""; $aumento=""; $compromisos=""; $causado=""; $pagado=$monto;
							if ($detalle['estado']=="anulado") { $anulado="(ANULADO)"; $pagado="($pagado)"; $tr5="font-size:12px; color:#FF0000;"; } else $anulado="";
							$detalle['tipo']=$detalle['justificacion']." ".$anulado;
							$imprimir_op="SI";
						}
						else if ($detalle['tipo']=="Requisicion") {
							$detalle['tipo']=$detalle['justificacion'];
							$precompromiso=number_format($detalle['monto_acreditar'], 2, ',', '');
							$sum_precompromiso+=$detalle['monto_acreditar'];
						}

						$ordenes = "";
						if ($imprimir_ocs=="SI") {
							$sql="SELECT rpc.idorden_compra_servicio, ocs.numero_orden FROM relacion_pago_compromisos rpc INNER JOIN orden_compra_servicio ocs ON (rpc.idorden_compra_servicio=ocs.idorden_compra_servicio) WHERE rpc.idorden_pago='".$detalle['id']."'";
						}

						if ($imprimir_op=="SI") {
							$sql="SELECT pf.idorden_pago, op.numero_orden FROM pagos_financieros pf INNER JOIN orden_pago op ON (pf.idorden_pago=op.idorden_pago) WHERE pf.idpagos_financieros='".$detalle['id']."'";
						}
						$query_pagados=mysql_query($sql) or die ($sql.mysql_error());
						while ($field_pagados=mysql_fetch_array($query_pagados)) {
							$ordenes.=$field_pagados['numero_orden'].", ";
						}

						$h++;

						if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
						else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
						$pdf->SetFont('Arial', '', 6);

						$pdf->Row(array($detalle['nro_solicitud'], utf8_decode($fecha_solicitud.' - '.$detalle['tipo']), '', $aumento, $solicitud_aumento, $disminucion, $reservado_disminuir, ''));
						$linea=$pdf->GetY(); if ($linea>175) { movimientos_por_categoria($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto); $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }

						$pdf->Row(array('', $ordenes, '', '', '', '', '', ''));
						$linea=$pdf->GetY(); if ($linea>175) { movimientos_por_categoria($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto); }

						$imprimir_ocs=""; $imprimir_op=""; $precompromiso=""; $aumento=""; $disminucion=""; $solicitud_aumento=""; $reservado_disminuir=""; $compromisos=""; $causado=""; $pagado="";
					}

					$par_clasificador = "";
					$par_denominacion = "";
					$par_monto_original = "";
					$par_total_aumento = "";
					$par_solicitud_aumento = "";
					$par_total_disminucion = "";
					$par_reservado_disminuir = "";
					$par_monto_actual = "";
					$gen_clasificador = "";
					$gen_denominacion = "";
					$gen_monto_original = "";
					$gen_total_aumento = "";
					$gen_solicitud_aumento = "";
					$gen_total_disminucion = "";
					$gen_reservado_disminuir = "";
					$gen_monto_actual = "";
				}
			}

			else if ($field["Tipo"]=="especifica") {}
		}
		//---------------------------------------------
		if ($sum_compromiso == 0 || $sum_actual == 0) $pcompromiso=0; else $pcompromiso=(float) (($sum_compromiso*100)/$sum_actual); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
		if ($sum_causado == 0 || $sum_actual == 0) $pcausado=0; else $pcausado=(float) (($sum_causado*100)/$sum_actual); $pcausado=number_format($pcausado, 2, ',', '.');
		if ($sum_pagado == 0 || $sum_actual == 0) $ppagado=0; else $ppagado=(float) (($sum_pagado*100)/$sum_actual); $ppagado=number_format($ppagado, 2, ',', '.');
		if ($disponible == 0 || $sum_actual == 0) $pdisponible=0; else $pdisponible=(float) (($disponible*100)/$sum_actual); $pdisponible=number_format($pdisponible, 2, ',', '.');
		$sum_original=number_format($sum_original, 2, ',', '.');
		$sum_aumento=number_format($sum_aumento, 2, ',', '.');
		$sum_disminucion=number_format($sum_disminucion, 2, ',', '.');
		$sum_solicitud_aumento=number_format($sum_solicitud_aumento, 2, ',', '.');
		$sum_reservado_disminuir=number_format($sum_reservado_disminuir, 2, ',', '.');
		$sum_actual=number_format($sum_actual, 2, ',', '.');
		$sum_compromiso=number_format($sum_compromiso, 2, ',', '.');
		$sum_causado=number_format($sum_causado, 2, ',', '.');
		$sum_pagado=number_format($sum_pagado, 2, ',', '.');
		$sum_disponible=number_format($sum_disponible, 2, ',', '.');
		$sum_precompromiso=number_format($sum_precompromiso, 2, ',', '.');

		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$y=$pdf->GetY();
		$pdf->Rect(5, $y+5, 270, 0.1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R'));
		$pdf->SetWidths(array(30, 60, 30, 30, 30, 30, 30, 30));
		$pdf->SetHeight(array(5));
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->SetY($y+8);
		$pdf->Row(array('', '', $sum_original, $sum_aumento, $sum_solicitud_aumento, $sum_disminucion, $sum_reservado_disminuir, $sum_actual));
		break;

	//	Proyeccion Presupuestaria...
	case "proyeccion_presupuestaria":
		$pdf=new PDF_MC_Table5('L', 'mm', 'A4');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//---------------------------------------------
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//---------------------------------------------
		proyeccion_presupuestaria($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto);
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
							(maestro_presupuesto.anio='".$anio_fiscal."' AND
							maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
							maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
						GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
						ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$disp=$field["Actual"]-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"];
			$formulado=number_format($field["Formulado"], 2, ',', '.');
			$actual=number_format($field["Actual"], 2, ',', '.');
			$compromiso=number_format($field["Compromiso"], 2, ',', '.');
			$precompromiso=number_format($field["PreCompromiso"], 2, ',', '.');
			$causado=number_format($field["Causado"], 2, ',', '.');
			$pagado=number_format($field["Pagado"], 2, ',', '.');
			if ($referencia=="U") {
				$proyeccion=number_format($field["UltimoPago"], 2, ',', '.');
				$dproy=$disp-$field["UltimoPago"];
				$dproyectado=number_format($dproy, 2, ',', '.');
				$sum_proyeccion+=$field["UltimoPago"];
				$sum_dproyectado+=$dproy;
			} elseif ($referencia=="P") {
				list($a, $mes_ultimo, $d)=SPLIT( '[/.-]', $field['UltimaFecha']);
				$m=(int) $mes_ultimo;
				$meses_pasados=$m;
				$meses_faltan=12-$m;
				if ($field["Compromiso"]!=0 && $meses_pasados!=0) $promedio=($field["Compromiso"]/$meses_pasados)*$meses_faltan;
				$proyeccion=number_format($promedio, 2, ',', '.');
				$dproy=$disp-$promedio;
				$dproyectado=number_format($dproy, 2, ',', '.');
				$sum_proyeccion+=$promedio;
				$sum_dproyectado+=$dproy;
			}
			$disponible=number_format($disp, 2, ',', '.');
			if ($field["Compromiso"]==0 or $field["Actual"]==0) $pcompromiso="0"; else $pcompromiso=(float) (($field["Compromiso"]*100)/$field["Actual"]); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
			if ($field["Causado"]==0 or $field["Actual"]==0) $pcausado="0"; else $pcausado=(float) (($field["Causado"]*100)/$field["Actual"]); $pcausado=number_format($pcausado, 2, ',', '.');
			if ($field["Pagado"]==0 or $field["Actual"]==0) $ppagado="0"; else $ppagado=(float) (($field["Pagado"]*100)/$field["Actual"]); $ppagado=number_format($ppagado, 2, ',', '.');
			if ($field["Compromiso"]==0 or $field["Actual"]==0) $pdisponible="0"; else
			if ($disp==0) $pdisponible="0"; else $pdisponible=(float) ((($field["Actual"]-$field["Compromiso"])*100)/$field["Actual"]);
			if ($field["UltimoPago"]==0 or $field["Actual"]==0) $pproyeccion="0"; else $pproyeccion=(float) (($field["UltimoPago"]*100)/$field["Actual"]); $pproyeccion=number_format($pproyeccion, 2, ',', '.');
			if ($dproy==0 or $field["Actual"]==0) $pdproyectado="0"; else $pdproyectado=(float) (($dproy*100)/$field["Actual"]); $pdproyectado=number_format($pdproyectado, 2, ',', '.');
			$pdisponible=number_format($pdisponible, 2, ',', '.');

			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"]!=$IdCategoria) {
				$IdCategoria=$field["IdCategoria"];
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(205, 7, utf8_decode($field["CodCategoria"]." - ".$field["Unidad"]), 1, 1, 'L', 1);
				$linea=$pdf->GetY(); if ($linea>250) { preres($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos); $pdf->SetY(57); }
			}
			if ($field["Tipo"]=="partida") {
				$sum_formulado+=$field["Formulado"];
				$sum_actual+=$field["Actual"];
				$sum_compromiso+=$field["Compromiso"];
				$sum_precompromiso+=$field["PreCompromiso"];
				$sum_causado+=$field["Causado"];
				$sum_pagado+=$field["Pagado"];
				$sum_disponible+=$disp;
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 6);
			}
			else if ($field["Tipo"]=="generica") {
				$h++;
				if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
				else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
				$pdf->SetFont('Arial', 'B', 6);
			}
			else if ($field["Tipo"]=="especifica") {
				$h++;
				if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
				else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
				$pdf->SetFont('Arial', '', 6);
			}

			if ($field['codordinal'] != "0000") $descpartida = $field['codordinal']." ".$field['nomordinal'];
			else $descpartida = $field['NomPartida'];

			$pdf->Row(array($clasificador, utf8_decode($descpartida), $actual, $compromiso, $pcompromiso." %", $disponible, $pdisponible." %", $proyeccion, $pproyeccion." %", $dproyectado, $pdproyectado." %"));
			$linea=$pdf->GetY(); if ($linea>175) { proyeccion_presupuestaria($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto); $pdf->SetY(44); }
			$formulado=""; $actual=""; $compromiso=""; $pcompromiso=""; $disponible=""; $pdisponible=""; $proyeccion=""; $pproyeccion=""; $dproyectado=""; $pdproyectado="";

		}
		//------------------------------------------------
		if ($sum_compromiso == 0 or $sum_actual == 0) $tpcompromiso = 0; else $tpcompromiso=(float) (($sum_compromiso*100)/$sum_actual); $tpcompromiso=number_format($tpcompromiso, 2, ',', '.');
		if ($sum_causado == 0 or $sum_actual) $tpcausado = 0; else $tpcausado=(float) (($sum_causado*100)/$sum_actual); $tpcausado=number_format($tpcausado, 2, ',', '.');
		if ($sum_pagado == 0 or $sum_actual == 0) $tppagado = 0; else $tppagado=(float) (($sum_pagado*100)/$sum_actual); $tppagado=number_format($tppagado, 2, ',', '.');
		if ($sum_proyeccion == 0 or $sum_actual == 0) $tpproyeccion= 0; else $tpproyeccion=(float) (($sum_proyeccion*100)/$sum_actual); $tpproyeccion=number_format($tpproyeccion, 2, ',', '.');
		if ($sum_dproyectado == 0 or $sum_actual == 0) $tpdproyectado = 0; else $tpdproyectado=(float) (($sum_dproyectado*100)/$sum_actual); $tpdproyectado=number_format($tpdproyectado, 2, ',', '.');
		if ($sum_disponible == 0 or $sum_actual == 0) $tpdisponible = 0; else $tpdisponible=(float) (($sum_disponible*100)/$sum_actual); $tpdisponible=number_format($tpdisponible, 2, ',', '.');
		$sum_formulado=number_format($sum_formulado, 2, ',', '.');
		$sum_compromiso=number_format($sum_compromiso, 2, ',', '.');
		$sum_disponible=number_format($sum_disponible, 2, ',', '.');
		$sum_proyeccion=number_format($sum_proyeccion, 2, ',', '.');
		$sum_dproyectado=number_format($sum_dproyectado, 2, ',', '.');

		//	IMPRIMO LOS TOTALES
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=285; $pdf->Rect($x, $y+2, $w, $h);
		$pdf->SetXY($x, $y+5);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
		$pdf->SetWidths(array(20, 90, 25, 25, 12, 25, 12, 25, 12, 25, 12));
		$pdf->SetHeight(array(5));
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Row(array('', '', $sum_formulado, $sum_compromiso, $tpcompromiso." %", $sum_disponible, $tpdisponible." %", $sum_proyeccion, $tpproyeccion." %", $sum_dproyectado, $tpdproyectado." %"));
		break;

	//	Simular Solicitud Presupuesto...
	case "simular_solicitud_presupuesto":
		$pdf=new PDF_MC_Table5('P', 'mm', 'A4');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//---------------------------------------------
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//---------------------------------------------
		simular_solicitud_presupuesto($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $porcentaje);
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
						GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
						ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$formulado=number_format($field["Formulado"], 2, ',', '.');
			$actual=number_format($field["Actual"], 2, ',', '.');
			$compromiso=number_format($field["Compromiso"], 2, ',', '.');
			$precompromiso=number_format($field["PreCompromiso"], 2, ',', '.');
			$causado=number_format($field["Causado"], 2, ',', '.');
			$pagado=number_format($field["Pagado"], 2, ',', '.');
			$disponible=number_format(($field["Actual"]-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"]), 2, ',', '.');
			if ($field["Compromiso"]==0 or $field["Actual"]==0) $pcompromiso="0"; else $pcompromiso=(float) (($field["Compromiso"]*100)/$field["Actual"]); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
			if ($field["Causado"]==0 or $field["Actual"]==0) $pcausado="0"; else $pcausado=(float) (($field["Causado"]*100)/$field["Actual"]); $pcausado=number_format($pcausado, 2, ',', '.');
			if ($field["Pagado"]==0 or $field["Actual"]==0) $ppagado="0"; else $ppagado=(float) (($field["Pagado"]*100)/$field["Actual"]); $ppagado=number_format($ppagado, 2, ',', '.');
			if ($field["Compromiso"]==0 or $field["Actual"]==0) $pdisponible="0"; else
			if (($field["Actual"]-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"])==0) $pdisponible="0"; else $pdisponible=(float) ((($field["Actual"]-$field["Compromiso"])*100)/$field["Actual"]);
			$pdisponible=number_format($pdisponible, 2, ',', '.');
			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"]!=$IdCategoria) {
				$IdCategoria=$field["IdCategoria"];
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(205, 7, utf8_decode($field["CodCategoria"]." - ".$field["Unidad"]), 1, 1, 'L', 1);
				$linea=$pdf->GetY(); if ($linea>250) { preres($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos); $pdf->SetY(57); }
			}
			if ($field["Tipo"]=="partida") {
				$sum_formulado+=$field["Formulado"];
				$sum_actual+=$field["Actual"];
				$sum_compromiso+=$field["Compromiso"];
				$sum_precompromiso+=$field["PreCompromiso"];
				$sum_causado+=$field["Causado"];
				$sum_pagado+=$field["Pagado"];
				$sum_disponible+=($field["Actual"]-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"]);
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 6);
			}
			else if ($field["Tipo"]=="generica") {
				$h++;
				if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
				else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
				$pdf->SetFont('Arial', 'B', 6);
			}
			else if ($field["Tipo"]=="especifica") {
				$h++;
				if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
				else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
				$pdf->SetFont('Arial', '', 6);
			}
			//	-----------
			if ($sobre=="O") $monto_anio=$field['Formulado'];
			elseif ($sobre=="A") $monto_anio=$field['Actual'];
			$monto_simulado = (($monto_anio*$porcentaje)/100)+$monto_anio;

			$monto=number_format($monto_anio, 2, ',', '.');
			$simulado=number_format($monto_simulado, 2, ',', '.');

			$sum_monto_anio+=$monto_anio;
			$sum_monto_simulado+=$monto_simulado;
			//	-----------

			if ($field['codordinal'] != "0000") $descripcion = $field['codordinal']." ".$field['nomordinal'];
			else $descripcion = $field['NomPartida'];

			$pdf->Row(array($clasificador, utf8_decode($descripcion), $monto, $simulado));
			$linea=$pdf->GetY(); if ($linea>250) { simular_solicitud_presupuesto($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $porcentaje); $pdf->SetY(44); }
			$formulado=""; $actual=""; $precompromiso=""; $compromiso=""; $pcompromiso=""; $causado=""; $pcausado=""; $pagado=""; $ppagado=""; $disponible=""; $pdisponible="";
		}
		//------------------------------------------------
		if ($sum_compromiso == 0 or $sum_actual == 0) $tpcompromiso = 0; else $tpcompromiso=(float) (($sum_compromiso*100)/$sum_actual); $tpcompromiso=number_format($tpcompromiso, 2, ',', '.');
		if ($sum_causado == 0 or $sum_actual) $tpcausado = 0; else $tpcausado=(float) (($sum_causado*100)/$sum_actual); $tpcausado=number_format($tpcausado, 2, ',', '.');
		if ($sum_pagado == 0 or $sum_actual == 0) $tppagado = 0; else $tppagado=(float) (($sum_pagado*100)/$sum_actual); $tppagado=number_format($tppagado, 2, ',', '.');
		if ($sum_disponible == 0 or $sum_actual == 0) $tpdisponible = 0; else $tpdisponible=(float) (($sum_disponible*100)/$sum_actual); $tpdisponible=number_format($tpdisponible, 2, ',', '.');
		$sum_formulado=number_format($sum_formulado, 2, ',', '.');
		$sum_actual=number_format($sum_actual, 2, ',', '.');
		$sum_precompromiso=number_format($sum_precompromiso, 2, ',', '.');
		$sum_compromiso=number_format($sum_compromiso, 2, ',', '.');
		$sum_causado=number_format($sum_causado, 2, ',', '.');
		$sum_pagado=number_format($sum_pagado, 2, ',', '.');
		$sum_disponible=number_format($sum_disponible, 2, ',', '.');
		$sum_monto_anio=number_format($sum_monto_anio, 2, ',', '.');
		$sum_monto_simulado=number_format($sum_monto_simulado, 2, ',', '.');
		//	IMPRIMO LOS TOTALES
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=190; $pdf->Rect($x, $y+2, $w, $h);
		$pdf->SetXY($x, $y+5);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetHeight(array(5));
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Row(array('', '', $sum_monto_anio, $sum_monto_simulado));
		break;

	//	Documentos por Partida...
	case "documentos_por_partida":
		$pdf=new PDF_MC_Table5('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//---------------------------------------------
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//---------------------------------------------
		$sql = "SELECT
					cp.codigo,
					u.denominacion
				FROM
					categoria_programatica cp
					INNER JOIN unidad_ejecutora u ON (u.idunidad_ejecutora = cp.idunidad_ejecutora)
				WHERE
					cp.idcategoria_programatica = '".$idcategoria."'";
		$query_categoria = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_categoria) != 0) $field_categoria = mysql_fetch_array($query_categoria);
		//---------------------------------------------
		if ($idordinal != "") $where_ordinal = "AND o.idordinal = '".$idordinal."'"; else $where_ordinal = "AND o.codigo = '0000'";
		$sql = "SELECT
					CONCAT(cp.partida, '.', cp.generica, '.', cp.especifica, '.', cp.sub_especifica) AS codigo,
					cp.denominacion,
					o.codigo AS codordinal,
					o.denominacion AS nomordinal
				FROM
					clasificador_presupuestario cp
					INNER JOIN maestro_presupuesto mp ON (cp.idclasificador_presupuestario = mp.idclasificador_presupuestario AND anio = '".$anio_fiscal."' AND mp.idcategoria_programatica = '".$idcategoria."' AND mp.idtipo_presupuesto = '".$tipo_presupuesto."' AND mp.idfuente_financiamiento = '".$financiamiento."')
					INNER JOIN ordinal o ON (mp.idordinal = o.idordinal $where_ordinal)
				WHERE
					cp.idclasificador_presupuestario = '".$idpartida."'";
		$query_partida = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_partida) != 0) $field_partida = mysql_fetch_array($query_partida);
		//---------------------------------------------
		if ($estado!="0") {
			$filtro_ocs="AND ocs.estado='".$estado."'";
			if ($estado=="pagado") $filtro_op="AND op.estado='pagada'"; else $filtro_op="AND op.estado='".$estado."'";
		}
		//---------------------------------------------
		documentos_por_partida($pdf, $_GET['idcategoria'], $_GET['idpartida'], $anio_fiscal, $financiamiento, $tipo_presupuesto, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $field_categoria, $field_partida);
		//---------------------------------------------
		if ($idordinal != "") $where_ordinal = "AND o.idordinal = '".$idordinal."'";
		//---------------------------------------------
		$sql="(SELECT
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
															AND mp.idcategoria_programatica='".$idcategoria."'
															AND mp.idclasificador_presupuestario='".$idpartida."')
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
															AND mp.idcategoria_programatica='".$idcategoria."'
															AND mp.idclasificador_presupuestario='".$idpartida."')
					INNER JOIN ordinal o ON (mp.idordinal = o.idordinal $where_ordinal)
				WHERE
					op.estado <> 'elaboracion' $filtro_op )


				ORDER BY codigo_referencia, fecha_orden";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha_orden=$d."/".$m."/".$a;
			$monto=number_format($field['total'], 2, ',', '.'); $sum_monto+=$field['total'];
			//	Imprimo el registro
			$pdf->Row(array($field['numero_orden'], $fecha_orden, strtoupper($field['estado']), utf8_decode($field['nombre']), $monto));
		}
		//---------------------------------------------
		$sum_monto=number_format($sum_monto, 2, ',', '.');
		//	Imprimo el total
		$pdf->SetDrawColor(0, 0, 0);
		$y=$pdf->GetY(); $y+=1;
		$pdf->Rect(5, $y, 205, 0.01);
		$pdf->SetY($y+2);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Row(array('', '', '', '', $sum_monto));
		break;

	//	Ejecucion Trimestral...
	case "ejecucion_trimestral":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
		$pdf->Open();
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetAutoPageBreak(1, 5);
		//---------------------------------------------
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//---------------------------------------------
		ejecucion_trimestral($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto);
		if ($idcategoria_programatica=='0') $filtro=""; else $filtro=" AND (maestro_presupuesto.idcategoria_programatica='".$idcategoria_programatica."')";
		//------------------------------------------------

		//	-----------------------------------------------------------------------------------------
		//	Recorro totods los registros para obtener los totales de las genericas y las partidas....
		//	-----------------------------------------------------------------------------------------
		$par = -1;
		$gen = -1;
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
					  SUM(maestro_presupuesto.total_aumento - maestro_presupuesto.total_disminucion) AS Modificacion,
					  'especifica' AS Tipo,
					  (SELECT SUM(pocs.monto)
						FROM partidas_orden_compra_servicio pocs
							INNER JOIN orden_compra_servicio ocs ON (pocs.idorden_compra_servicio=ocs.idorden_compra_servicio)
								WHERE
									pocs.idmaestro_presupuesto = IdPresupuesto AND
									(ocs.estado = 'procesado' OR ocs.estado = 'conformado' OR ocs.estado = 'pagado') AND
									(ocs.fecha_orden >= '$anio_fiscal-01-01' AND ocs.fecha_orden <= '$anio_fiscal-03-31')) AS CompraCompromisoI,
					  (SELECT SUM(pocs.monto)
						FROM partidas_orden_compra_servicio pocs
							INNER JOIN orden_compra_servicio ocs ON (pocs.idorden_compra_servicio=ocs.idorden_compra_servicio)
								WHERE
									pocs.idmaestro_presupuesto = IdPresupuesto AND
									(ocs.estado = 'procesado' OR ocs.estado = 'conformado' OR ocs.estado = 'pagado') AND
									(ocs.fecha_orden >= '$anio_fiscal-04-01' AND ocs.fecha_orden <= '$anio_fiscal-06-30')) AS CompraCompromisoII,
					  (SELECT SUM(pocs.monto)
						FROM partidas_orden_compra_servicio pocs
							INNER JOIN orden_compra_servicio ocs ON (pocs.idorden_compra_servicio=ocs.idorden_compra_servicio)
								WHERE
									pocs.idmaestro_presupuesto = IdPresupuesto AND
									(ocs.estado = 'procesado' OR ocs.estado = 'conformado' OR ocs.estado = 'pagado') AND
									(ocs.fecha_orden >= '$anio_fiscal-07-01' AND ocs.fecha_orden <= '$anio_fiscal-09-30')) AS CompraCompromisoIII,
					  (SELECT SUM(pocs.monto)
						FROM partidas_orden_compra_servicio pocs
							INNER JOIN orden_compra_servicio ocs ON (pocs.idorden_compra_servicio=ocs.idorden_compra_servicio)
								WHERE
									pocs.idmaestro_presupuesto = IdPresupuesto AND
									(ocs.estado = 'procesado' OR ocs.estado = 'conformado' OR ocs.estado = 'pagado') AND
									(ocs.fecha_orden >= '$anio_fiscal-10-01' AND ocs.fecha_orden <= '$anio_fiscal-12-31')) AS CompraCompromisoIV,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.compromete = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-01-01' AND op.fecha_orden <= '$anio_fiscal-03-31')) AS PagoCompromisoI,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.compromete = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-04-01' AND op.fecha_orden <= '$anio_fiscal-06-30')) AS PagoCompromisoII,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.compromete = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-07-01' AND op.fecha_orden <= '$anio_fiscal-09-30')) AS PagoCompromisoIII,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.compromete = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-10-01' AND op.fecha_orden <= '$anio_fiscal-12-31')) AS PagoCompromisoIV,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.causa = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-01-01' AND op.fecha_orden <= '$anio_fiscal-03-30')) AS CausaI,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.causa = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-04-01' AND op.fecha_orden <= '$anio_fiscal-06-31')) AS CausaII,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.causa = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-07-01' AND op.fecha_orden <= '$anio_fiscal-09-30')) AS CausaIII,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.causa = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-10-01' AND op.fecha_orden <= '$anio_fiscal-12-31')) AS CausaIV,
					  ordinal.codigo AS codordinal,
					  ordinal.denominacion AS nomordinal

				FROM
					  maestro_presupuesto
					  INNER JOIN categoria_programatica ON (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica)
					  INNER JOIN unidad_ejecutora ON (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)
					  INNER JOIN clasificador_presupuestario ON (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario)
					  INNER JOIN ordinal ON (maestro_presupuesto.idordinal = ordinal.idordinal)
				WHERE
					  (maestro_presupuesto.anio='".$anio_fiscal."' AND
					  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
					  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro
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
						'' AS CompraCompromisoI,
						'' AS CompraCompromisoII,
						'' AS CompraCompromisoIII,
						'' AS CompraCompromisoIV,
						'' AS PagoCompromisoI,
						'' AS PagoCompromisoII,
						'' AS PagoCompromisoIII,
						'' AS PagoCompromisoIV,
						'' AS CausaI,
						'' AS CausaII,
						'' AS CausaIII,
						'' AS CausaIV,
						'0000' AS codordinal,
						'' AS nomordinal
				FROM
					  maestro_presupuesto
					  INNER JOIN categoria_programatica ON (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica)
					  INNER JOIN unidad_ejecutora ON (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)
					  INNER JOIN clasificador_presupuestario ON (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario)
				WHERE
						(clasificador_presupuestario.sub_especifica='00') AND
					  (maestro_presupuesto.anio='".$anio_fiscal."' AND
					  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
					  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro
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
						'' AS CompraCompromisoI,
						'' AS CompraCompromisoII,
						'' AS CompraCompromisoIII,
						'' AS CompraCompromisoIV,
						'' AS PagoCompromisoI,
						'' AS PagoCompromisoII,
						'' AS PagoCompromisoIII,
						'' AS PagoCompromisoIV,
						'' AS CausaI,
						'' AS CausaII,
						'' AS CausaIII,
						'' AS CausaIV,
						'0000' AS codordinal,
						'' AS nomordinal
				FROM
					  maestro_presupuesto
					  INNER JOIN categoria_programatica ON (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica)
					  INNER JOIN unidad_ejecutora ON (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)
					  INNER JOIN clasificador_presupuestario ON (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario)
				WHERE
						(clasificador_presupuestario.sub_especifica='00') AND
					  (maestro_presupuesto.anio='".$anio_fiscal."' AND
					  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
					  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			if ($field['Tipo'] == "partida") $par++;
			elseif ($field['Tipo'] == "generica") $gen++;
			elseif ($field['Tipo'] == "especifica") {
				$comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI'];
				$comprometido_genericaII[$gen] += $field['CompraCompromisoII'] + $field['PagoCompromisoII'];
				$comprometido_genericaIII[$gen] += $field['CompraCompromisoIII'] + $field['PagoCompromisoIII'];
				$comprometido_genericaIV[$gen] += $field['CompraCompromisoIV'] + $field['PagoCompromisoIV'];

				$comprometido_partidaI[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI'];
				$comprometido_partidaII[$par] +=$field['CompraCompromisoII'] + $field['PagoCompromisoII'];
				$comprometido_partidaIII[$par] += $field['CompraCompromisoIII'] + $field['PagoCompromisoIII'];
				$comprometido_partidaIV[$par] += $field['CompraCompromisoIV'] + $field['PagoCompromisoIV'];

				$causa_genericaI[$gen] += $field['CausaI'];
				$causa_genericaII[$gen] += $field['CausaII'];
				$causa_genericaIII[$gen] += $field['CausaIII'];
				$causa_genericaIV[$gen] += $field['CausaIV'];

				$causa_partidaI[$par] +=  $field['CausaI'];
				$causa_partidaII[$par] +=$field['CausaII'];
				$causa_partidaIII[$par] += $field['CausaIII'];
				$causa_partidaIV[$par] += $field['CausaIV'];
			}
		}

		//	-----------------------------------------------------------------------------------------
		//
		//	-----------------------------------------------------------------------------------------

		$par = 0;
		$gen = 0;
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
					  SUM(maestro_presupuesto.total_aumento - maestro_presupuesto.total_disminucion) AS Modificacion,
					  'especifica' AS Tipo,
					  (SELECT SUM(pocs.monto)
						FROM partidas_orden_compra_servicio pocs
							INNER JOIN orden_compra_servicio ocs ON (pocs.idorden_compra_servicio=ocs.idorden_compra_servicio)
								WHERE
									pocs.idmaestro_presupuesto = IdPresupuesto AND
									(ocs.estado = 'procesado' OR ocs.estado = 'conformado' OR ocs.estado = 'pagado') AND
									(ocs.fecha_orden >= '$anio_fiscal-01-01' AND ocs.fecha_orden <= '$anio_fiscal-03-31')) AS CompraCompromisoI,
					  (SELECT SUM(pocs.monto)
						FROM partidas_orden_compra_servicio pocs
							INNER JOIN orden_compra_servicio ocs ON (pocs.idorden_compra_servicio=ocs.idorden_compra_servicio)
								WHERE
									pocs.idmaestro_presupuesto = IdPresupuesto AND
									(ocs.estado = 'procesado' OR ocs.estado = 'conformado' OR ocs.estado = 'pagado') AND
									(ocs.fecha_orden >= '$anio_fiscal-04-01' AND ocs.fecha_orden <= '$anio_fiscal-06-30')) AS CompraCompromisoII,
					  (SELECT SUM(pocs.monto)
						FROM partidas_orden_compra_servicio pocs
							INNER JOIN orden_compra_servicio ocs ON (pocs.idorden_compra_servicio=ocs.idorden_compra_servicio)
								WHERE
									pocs.idmaestro_presupuesto = IdPresupuesto AND
									(ocs.estado = 'procesado' OR ocs.estado = 'conformado' OR ocs.estado = 'pagado') AND
									(ocs.fecha_orden >= '$anio_fiscal-07-01' AND ocs.fecha_orden <= '$anio_fiscal-09-30')) AS CompraCompromisoIII,
					  (SELECT SUM(pocs.monto)
						FROM partidas_orden_compra_servicio pocs
							INNER JOIN orden_compra_servicio ocs ON (pocs.idorden_compra_servicio=ocs.idorden_compra_servicio)
								WHERE
									pocs.idmaestro_presupuesto = IdPresupuesto AND
									(ocs.estado = 'procesado' OR ocs.estado = 'conformado' OR ocs.estado = 'pagado') AND
									(ocs.fecha_orden >= '$anio_fiscal-10-01' AND ocs.fecha_orden <= '$anio_fiscal-12-31')) AS CompraCompromisoIV,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.compromete = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-01-01' AND op.fecha_orden <= '$anio_fiscal-03-31')) AS PagoCompromisoI,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.compromete = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-04-01' AND op.fecha_orden <= '$anio_fiscal-06-30')) AS PagoCompromisoII,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.compromete = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-07-01' AND op.fecha_orden <= '$anio_fiscal-09-30')) AS PagoCompromisoIII,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.compromete = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-10-01' AND op.fecha_orden <= '$anio_fiscal-12-31')) AS PagoCompromisoIV,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.causa = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-01-01' AND op.fecha_orden <= '$anio_fiscal-03-30')) AS CausaI,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.causa = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-04-01' AND op.fecha_orden <= '$anio_fiscal-06-31')) AS CausaII,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.causa = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-07-01' AND op.fecha_orden <= '$anio_fiscal-09-30')) AS CausaIII,
					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.causa = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada') AND
									(op.fecha_orden >= '$anio_fiscal-10-01' AND op.fecha_orden <= '$anio_fiscal-12-31')) AS CausaIV,
					  ordinal.codigo AS codordinal,
					  ordinal.denominacion AS nomordinal

				FROM
					  maestro_presupuesto
					  INNER JOIN categoria_programatica ON (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica)
					  INNER JOIN unidad_ejecutora ON (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)
					  INNER JOIN clasificador_presupuestario ON (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario)
					  INNER JOIN ordinal ON (maestro_presupuesto.idordinal = ordinal.idordinal)
				WHERE
					  (maestro_presupuesto.anio='".$anio_fiscal."' AND
					  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
					  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro
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
						'' AS CompraCompromisoI,
						'' AS CompraCompromisoII,
						'' AS CompraCompromisoIII,
						'' AS CompraCompromisoIV,
						'' AS PagoCompromisoI,
						'' AS PagoCompromisoII,
						'' AS PagoCompromisoIII,
						'' AS PagoCompromisoIV,
						'' AS CausaI,
						'' AS CausaII,
						'' AS CausaIII,
						'' AS CausaIV,
						'0000' AS codordinal,
						'' As nomordinal
				FROM
					  maestro_presupuesto
					  INNER JOIN categoria_programatica ON (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica)
					  INNER JOIN unidad_ejecutora ON (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)
					  INNER JOIN clasificador_presupuestario ON (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario)
				WHERE
						(clasificador_presupuestario.sub_especifica='00') AND
					  (maestro_presupuesto.anio='".$anio_fiscal."' AND
					  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
					  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro
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
						'' AS CompraCompromisoI,
						'' AS CompraCompromisoII,
						'' AS CompraCompromisoIII,
						'' AS CompraCompromisoIV,
						'' AS PagoCompromisoI,
						'' AS PagoCompromisoII,
						'' AS PagoCompromisoIII,
						'' AS PagoCompromisoIV,
						'' AS CausaI,
						'' AS CausaII,
						'' AS CausaIII,
						'' AS CausaIV,
						'0000' AS codordinal,
						'' AS nomordinal
				FROM
					  maestro_presupuesto
					  INNER JOIN categoria_programatica ON (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica)
					  INNER JOIN unidad_ejecutora ON (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)
					  INNER JOIN clasificador_presupuestario ON (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario)
				WHERE
						(clasificador_presupuestario.sub_especifica='00') AND
					  (maestro_presupuesto.anio='".$anio_fiscal."' AND
					  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND
					  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$modificacion=number_format($field["Modificacion"], 2, ',', '.');
			$formulado=number_format($field["Formulado"], 2, ',', '.');
			$actual=number_format($field["Actual"], 2, ',', '.');
			$compromiso=number_format($field["Compromiso"], 2, ',', '.');
			$precompromiso=number_format($field["PreCompromiso"], 2, ',', '.');
			$causado=number_format($field["Causado"], 2, ',', '.');
			$pagado=number_format($field["Pagado"], 2, ',', '.');
			$disponible=number_format(($field["Actual"]-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"]), 2, ',', '.');

			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"]!=$IdCategoria) {
				$IdCategoria=$field["IdCategoria"];
				$pdf->SetFont('Arial', 'B', 5);
				$pdf->Cell(205, 5, utf8_decode($field["CodCategoria"]." - ".$field["Unidad"]), 1, 1, 'L', 1);
				$linea=$pdf->GetY(); if ($linea>250) { ejecucion_trimestral($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto); $pdf->SetY(55); }
			}
			if ($field["Tipo"]=="partida") {
				$sum_formulado+=$field["Formulado"];
				$sum_actual+=$field["Actual"];
				$sum_compromiso+=$field["Compromiso"];
				$sum_precompromiso+=$field["PreCompromiso"];
				$sum_causado+=$field["Causado"];
				$sum_pagado+=$field["Pagado"];
				$sum_modificacion+=$field["Modificacion"];
				$sum_disponible+=($field["Actual"]-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"]);
				$pdf->SetFont('Arial', 'B', 4);

				$comprometidoI = $comprometido_partidaI[$par];
				$comprometidoII = $comprometido_partidaII[$par];
				$comprometidoIII = $comprometido_partidaIII[$par];
				$comprometidoIV = $comprometido_partidaIV[$par];

				$causaI = $causa_partidaI[$par];
				$causaII = $causa_partidaII[$par];
				$causaIII = $causa_partidaIII[$par];
				$causaIV = $causa_partidaIV[$par];

				$par++;
			}
			else if ($field["Tipo"]=="generica") {
				$pdf->SetFont('Arial', 'B', 4);

				$comprometidoI = $comprometido_genericaI[$gen];
				$comprometidoII = $comprometido_genericaII[$gen];
				$comprometidoIII = $comprometido_genericaIII[$gen];
				$comprometidoIV = $comprometido_genericaIV[$gen];

				$causaI = $causa_genericaI[$gen];
				$causaII = $causa_genericaII[$gen];
				$causaIII = $causa_genericaIII[$gen];
				$causaIV = $causa_genericaIV[$gen];

				$gen++;
			}
			else if ($field["Tipo"]=="especifica") {
				$pdf->SetFont('Arial', '', 4);

				$comprometidoI = $field['CompraCompromisoI'] + $field['PagoCompromisoI'];
				$comprometidoII = $field['CompraCompromisoII'] + $field['PagoCompromisoII'];
				$comprometidoIII = $field['CompraCompromisoIII'] + $field['PagoCompromisoIII'];
				$comprometidoIV = $field['CompraCompromisoIV'] + $field['PagoCompromisoIV'];

				$causaI = $field['CausaI'];
				$causaII = $field['CausaII'];
				$causaIII = $field['CausaIII'];
				$causaIV = $field['CausaIV'];
			}

			$total_comprometidoI = number_format($comprometidoI, 2, ',', '.');
			$total_comprometidoII = number_format($comprometidoII, 2, ',', '.');
			$total_comprometidoIII = number_format($comprometidoIII, 2, ',', '.');
			$total_comprometidoIV = number_format($comprometidoIV, 2, ',', '.');

			$total_causaI = number_format($causaI, 2, ',', '.');
			$total_causaII = number_format($causaII, 2, ',', '.');
			$total_causaIII = number_format($causaIII, 2, ',', '.');
			$total_causaIV = number_format($causaIV, 2, ',', '.');

			if ($field['codordinal'] != "0000") $descripcion = $field['codordinal'].' '.$field['nomordinal'];
			else $descripcion= $field['NomPartida'];

			$pdf->Row(array($clasificador, utf8_decode($descripcion), $formulado, $modificacion, $actual, $total_comprometidoI, $total_causaI, $total_causaI, $total_comprometidoII, $total_causaII, $total_causaII, $total_comprometidoIII, $total_causaIII, $total_causaIII, $total_comprometidoIV, $total_causaIV, $total_causaIV, $compromiso, $causado, $causado));
			$linea=$pdf->GetY(); if ($linea>175) { ejecucion_trimestral($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto); $pdf->SetY(44); }
			$formulado=""; $actual=""; $precompromiso=""; $compromiso=""; $pcompromiso=""; $causado=""; $pcausado=""; $pagado=""; $ppagado=""; $disponible=""; $pdisponible="";
			$compromisoIII=""; $compromisoIV="";
		}
		//------------------------------------------------
		if ($sum_compromiso == 0 or $sum_actual == 0) $tpcompromiso = 0; else $tpcompromiso=(float) (($sum_compromiso*100)/$sum_actual); $tpcompromiso=number_format($tpcompromiso, 2, ',', '.');
		if ($sum_causado == 0 or $sum_actual) $tpcausado = 0; else $tpcausado=(float) (($sum_causado*100)/$sum_actual); $tpcausado=number_format($tpcausado, 2, ',', '.');
		if ($sum_pagado == 0 or $sum_actual == 0) $tppagado = 0; else $tppagado=(float) (($sum_pagado*100)/$sum_actual); $tppagado=number_format($tppagado, 2, ',', '.');
		if ($sum_disponible == 0 or $sum_actual == 0) $tpdisponible = 0; else $tpdisponible=(float) (($sum_disponible*100)/$sum_actual); $tpdisponible=number_format($tpdisponible, 2, ',', '.');
		$sum_formulado=number_format($sum_formulado, 2, ',', '.');
		$sum_modificacion=number_format($sum_modificacion, 2, ',', '.');
		$sum_actual=number_format($sum_actual, 2, ',', '.');
		$sum_precompromiso=number_format($sum_precompromiso, 2, ',', '.');
		$sum_compromiso=number_format($sum_compromiso, 2, ',', '.');
		$sum_causado=number_format($sum_causado, 2, ',', '.');
		$sum_pagado=number_format($sum_pagado, 2, ',', '.');
		$sum_disponible=number_format($sum_disponible, 2, ',', '.');
		break;

	//	(Modulo) Traslados...
	case "traslado_presupuestario":
		$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pag=0;
		//-----------	CONSULTO TRASLADOS PRESUPUESTARIOS PARA OBTENER EL HEAD DEL PDF
		$sql="SELECT nro_solicitud, fecha_solicitud, nro_resolucion, fecha_resolucion, justificacion, anio, total_credito, total_debito, estado FROM traslados_presupuestarios WHERE (idtraslados_presupuestarios='".$_GET['id_traslado']."')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		if ($field=mysql_fetch_array($query)) {
			$nsolicitud=$field[0];
			list($a, $m, $d)=SPLIT( '[/.-]', $field[1]); $fsolicitud=$d."/".$m."/".$a;
			$nresolucion=$field[2];
			list($a, $m, $d)=SPLIT( '[/.-]', $field[3]); $fresolucion=$d."/".$m."/".$a;
			$justificacion=$field[4];
			$anio=$field[5];
			$credito=number_format($field[6], 2, ',', '.');
			$debito=number_format($field[7], 2, ',', '.');
			$estado=$field[8];
			$pag++;
		}
		if ($solicitud=="true") {
			traslado_presupuestario($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Disminuidas", $estado);
			//-----------
			$pdf->SetXY(5, 44);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(25, 7, utf8_decode('Nro. Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(50, 7, $nresolucion, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(28, 7, utf8_decode('Fecha Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(102, 7, $fresolucion, 0, 0, 'L');
			$pdf->Ln(7);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(205, 7, utf8_decode('Justificación: '), 0, 1, 'L');

			$pdf->SetAligns(array('L'));
			$pdf->SetWidths(array(205));
			$pdf->Row(array(utf8_decode($justificacion)));
			//$pdf->MultiCell(205, 5, $justificacion, 0, 'L');
			//-----------
			$pdf->SetXY(5, 98);

			$pdf->SetFont('Arial', '', 9); $pdf->Cell(12, 7, utf8_decode('Año:'), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(23, 7, $anio, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(27, 7, 'Total Disminuir: ', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(58, 7, $debito, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(27, 7, 'Total Aumentar: ', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(58, 7, $credito, 0, 0, 'L');
			//-----------
			$sql="SELECT partidas_cedentes_traslado.idpartida_cedentes_traslado,
						 partidas_cedentes_traslado.idmaestro_presupuesto,
						 partidas_cedentes_traslado.monto_debitar,
						 maestro_presupuesto.idcategoria_programatica,
						 maestro_presupuesto.idclasificador_presupuestario,
						 maestro_presupuesto.idordinal,
						 categoria_programatica.codigo AS CodCategoria,
						 clasificador_presupuestario.partida,
						 clasificador_presupuestario.generica,
						 clasificador_presupuestario.especifica,
						 clasificador_presupuestario.sub_especifica,
						 clasificador_presupuestario.denominacion AS NomPartida,
						 ordinal.codigo,
						 ordinal.denominacion AS NomOrdinal
					FROM
						 partidas_cedentes_traslado,
						 maestro_presupuesto,
						 categoria_programatica,
						 clasificador_presupuestario,
						 ordinal
					WHERE
						(partidas_cedentes_traslado.idtraslados_presupuestarios='".$_GET['id_traslado']."') AND
						(partidas_cedentes_traslado.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
						(maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						(maestro_presupuesto.idordinal=ordinal.idordinal)
						order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$pdf->SetXY(5, 119);
			while ($field=mysql_fetch_array($query)) {
				$linea=$pdf->GetY();
				if ($linea>250) { $pag++; anexo_traslado_presupuestario($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Disminuidas (Continuacion...)", $estado); $pdf->SetY(61); }
				//
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 9);
				$pdf->SetAligns(array('C', 'C', 'L', 'R'));
				$pdf->SetWidths(array(25, 35, 100, 45));
				$monto=number_format($field[2], 2, ',', '.');
				$sum_monto+=$field[2];
				if ($field['codigo']=="0000") { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']; $denominacion=$field['NomPartida']; }
				else { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']." ".$field['codigo']; $denominacion=$field['NomPartida']; }
				$pdf->Row(array($field['CodCategoria'], $partida, utf8_decode($denominacion), $monto));
			}
			$sum_monto=number_format($sum_monto, 2, ',', '.');
			$pdf->SetDrawColor(0, 0, 0);
			$y=$pdf->GetY(); $y+=1;
			$pdf->Rect(5, $y, 205, 0.01);
			$pdf->SetY($y+2);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 11);
			$pdf->SetAligns(array('C', 'C', 'R', 'R'));
			$pdf->SetWidths(array(25, 35, 100, 45));
			$pdf->Row(array('', '', 'Totales:', $sum_monto));
			$sum_monto=0;
			//-----------
			$y=$pdf->GetY();
			if ($y>220) { $pag++; anexo_traslado_presupuestario($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Aumentadas (Continuacion...)", $estado); $pdf->SetY(60); }
			else {
				$pdf->SetXY(5, $y+10);
				$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(205, 5, 'Partidas Aumentadas', 0, 1, 'C');
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(25, 5, 'CAT.PROGR.', 1, 0, 'C', 1);
				$pdf->Cell(35, 5, 'PARTIDA', 1, 0, 'C', 1);
				$pdf->Cell(100, 5, 'DESCRIPCION', 1, 0, 'C', 1);
				$pdf->Cell(45, 5, 'MONTO', 1, 0, 'C', 1);
				$pdf->Ln(6);
			}
			//
			$sql="SELECT partidas_receptoras_traslado.idpartida_receptoras_traslado,
						 partidas_receptoras_traslado.idmaestro_presupuesto,
						 partidas_receptoras_traslado.monto_acreditar,
						 maestro_presupuesto.idcategoria_programatica,
						 maestro_presupuesto.idclasificador_presupuestario,
						 maestro_presupuesto.idordinal,
						 categoria_programatica.codigo AS CodCategoria,
						 clasificador_presupuestario.partida,
						 clasificador_presupuestario.generica,
						 clasificador_presupuestario.especifica,
						 clasificador_presupuestario.sub_especifica,
						 clasificador_presupuestario.denominacion As NomPartida,
						 ordinal.codigo,
						 ordinal.denominacion AS NomOrdinal
					FROM
						 partidas_receptoras_traslado,
						 maestro_presupuesto,
						 categoria_programatica,
						 clasificador_presupuestario,
						 ordinal
					WHERE
						 (partidas_receptoras_traslado.idtraslados_presupuestarios='".$_GET['id_traslado']."') AND
						 (partidas_receptoras_traslado.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
						 (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						 (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						 (maestro_presupuesto.idordinal=ordinal.idordinal)
						 order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			while ($field=mysql_fetch_array($query)) {
				$linea=$pdf->GetY();
				if ($linea>250) { $pag++; anexo_traslado_presupuestario($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Aumentadas (Continuacion...)", $estado); $pdf->SetY(61); }
				//
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 9);
				$pdf->SetAligns(array('C', 'C', 'L', 'R'));
				$pdf->SetWidths(array(25, 35, 100, 45));
				$monto=number_format($field[2], 2, ',', '.');
				$sum_monto+=$field[2];
				if ($field['codigo']=="0000") { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']; $denominacion=$field['NomPartida']; }
				else { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']." ".$field['codigo']; $denominacion=$field['NomPartida']; }
				$pdf->Row(array($field['CodCategoria'], $partida, utf8_decode($denominacion), $monto));
			}
			$sum_monto=number_format($sum_monto, 2, ',', '.');
			$pdf->SetDrawColor(0, 0, 0);
			$y=$pdf->GetY(); $y+=1;
			$pdf->Rect(5, $y, 205, 0.01);
			$pdf->SetY($y+2);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 11);
			$pdf->SetAligns(array('C', 'C', 'R', 'R'));
			$pdf->SetWidths(array(25, 35, 100, 45));
			$pdf->Row(array('', '', 'Totales:', $sum_monto));
		}
		else if ($simulado=="true") {
			traslado_presupuestario_simulado($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Disminuidas", $estado);
			//-----------
			$pdf->SetXY(5, 42);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(25, 7, utf8_decode('Nro. Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(50, 7, $nresolucion, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(28, 7, utf8_decode('Fecha Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(102, 7, $fresolucion, 0, 0, 'L'); $pdf->Ln(7);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(205, 7, utf8_decode('Justificación: '), 0, 1, 'L');

			$pdf->SetAligns(array('L'));
			$pdf->SetWidths(array(200));
			$pdf->Row(array(utf8_decode($justificacion)));
			//$pdf->MultiCell(205, 5, $justificacion, 0, 'L');
			//-----------
			$pdf->SetXY(5, 98);

			$pdf->SetFont('Arial', '', 9); $pdf->Cell(12, 7, utf8_decode('Año:'), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(23, 7, $anio, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(27, 7, 'Total Disminuir: ', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(58, 7, $debito, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(27, 7, 'Total Aumentar: ', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(58, 7, $credito, 0, 0, 'L');
			//-----------
			$sql="SELECT partidas_cedentes_traslado.idpartida_cedentes_traslado,
						 partidas_cedentes_traslado.idmaestro_presupuesto,
						 partidas_cedentes_traslado.monto_debitar,
						 maestro_presupuesto.idcategoria_programatica,
						 maestro_presupuesto.idclasificador_presupuestario,
						 maestro_presupuesto.idordinal,
						 maestro_presupuesto.monto_actual,
						 maestro_presupuesto.total_compromisos,
						 categoria_programatica.codigo AS CodCategoria,
						 clasificador_presupuestario.partida,
						 clasificador_presupuestario.generica,
						 clasificador_presupuestario.especifica,
						 clasificador_presupuestario.sub_especifica,
						 clasificador_presupuestario.denominacion AS NomPartida,
						 ordinal.codigo,
						 ordinal.denominacion AS NomOrdinal
					FROM
						 partidas_cedentes_traslado,
						 maestro_presupuesto,
						 categoria_programatica,
						 clasificador_presupuestario,
						 ordinal
					WHERE
						(partidas_cedentes_traslado.idtraslados_presupuestarios='".$_GET['id_traslado']."') AND
						(partidas_cedentes_traslado.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
						(maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						(maestro_presupuesto.idordinal=ordinal.idordinal)
						order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$pdf->SetXY(5, 119);
			while ($field=mysql_fetch_array($query)) {
				$linea=$pdf->GetY();
				if ($linea>250) { $pag++; anexo_traslado_presupuestario_simulado($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Disminuidas (Continuacion...)", $estado); $pdf->SetY(61); }
				//
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'L', 'R', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(17, 20, 68, 20, 20, 20, 20, 20));
				$monto=number_format($field[2], 2, ',', '.');
				$sum_monto+=$field['monto_debitar'];	//
				$monto_actual=number_format($field['monto_actual'], 2, ',', '.');
				$sum_monto_actual+=$field['monto_actual'];	//
				$majustado=$field['monto_actual']-$field['monto_debitar'];
				$monto_ajustado=number_format($majustado, 2, ',', '.');
				$sum_monto_ajustado+=$majustado;	//
				$mdisponible=$field['monto_actual']-$field['total_compromisos'];
				$monto_disponible=number_format($mdisponible, 2, ',', '.');
				$sum_monto_disponible+=$mdisponible;	//
				$mdajustado=$majustado-$field['total_compromisos'];
				$monto_disponible_ajustado=number_format($mdajustado, 2, ',', '.');
				$sum_monto_disponible_ajustado+=$mdajustado;	//
				if ($field['codigo']=="0000") { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']; $denominacion=$field['NomPartida']; }
				else { $partida=$field['partida'].".".$field['genercia'].".".$field['especifica'].".".$field['sub_especifica']." ".$field['codigo']; $denominacion=$field['NomPartida']; }
				$pdf->Row(array($field['CodCategoria'], $partida, utf8_decode($denominacion), $monto_actual, $monto, $monto_ajustado, $monto_disponible, $monto_disponible_ajustado));
			}
			$sum_monto=number_format($sum_monto, 2, ',', '.');
			$sum_monto_actual=number_format($sum_monto_actual, 2, ',', '.');
			$sum_monto_ajustado=number_format($sum_monto_ajustado, 2, ',', '.');
			$sum_monto_disponible=number_format($sum_monto_disponible, 2, ',', '.');
			$sum_monto_disponible_ajustado=number_format($sum_monto_disponible_ajustado, 2, ',', '.');
			$pdf->SetDrawColor(0, 0, 0);
			$y=$pdf->GetY(); $y+=1;
			$pdf->Rect(5, $y, 205, 0.01);
			$pdf->SetY($y+2);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->SetAligns(array('R', 'R', 'R', 'R', 'R', 'R'));
			$pdf->SetWidths(array(105, 20, 20, 20, 20, 20));
			$pdf->Row(array('', $sum_monto_actual, $sum_monto, $sum_monto_ajustado, $sum_monto_disponible, $sum_monto_disponible_ajustado));
			$sum_monto=0; $sum_monto_actual=0; $sum_monto=0; $sum_monto_ajustado=0; $sum_monto_disponible=0; $sum_monto_disponible_ajustado=0;
			//-----------
			$y=$pdf->GetY();
			if ($y>220) { $pag++; anexo_traslado_presupuestario_simulado($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Aumentadas (Continuacion...)", $estado); $pdf->SetY(61); }
			else {
				$pdf->SetXY(5, $y+10);
				$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(205, 5, 'Partidas Aumentadas', 0, 1, 'C');
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 6);
				$pdf->Cell(17, 5, 'C.Prog', 1, 0, 'C', 1);
				$pdf->Cell(20, 5, 'Partida', 1, 0, 'C', 1);
				$pdf->Cell(68, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
				$pdf->Cell(20, 5, 'M.Actual', 1, 0, 'C', 1);
				$pdf->Cell(20, 5, 'M.Ajuste', 1, 0, 'C', 1);
				$pdf->Cell(20, 5, 'M.Ajustado', 1, 0, 'C', 1);
				$pdf->Cell(20, 5, 'Disponible', 1, 0, 'C', 1);
				$pdf->Cell(20, 5, 'D.Ajustado', 1, 0, 'C', 1);
				$pdf->Ln(6);
			}
			//
			$sql="SELECT partidas_receptoras_traslado.idpartida_receptoras_traslado,
						 partidas_receptoras_traslado.idmaestro_presupuesto,
						 partidas_receptoras_traslado.monto_acreditar,
						 maestro_presupuesto.idcategoria_programatica,
						 maestro_presupuesto.idclasificador_presupuestario,
						 maestro_presupuesto.idordinal,
						 maestro_presupuesto.monto_actual,
						 maestro_presupuesto.total_compromisos,
						 categoria_programatica.codigo AS CodCategoria,
						 clasificador_presupuestario.partida,
						 clasificador_presupuestario.generica,
						 clasificador_presupuestario.especifica,
						 clasificador_presupuestario.sub_especifica,
						 clasificador_presupuestario.denominacion AS NomPartida,
						 ordinal.codigo,
						 ordinal.denominacion AS NomOrdinal
					FROM
						 partidas_receptoras_traslado,
						 maestro_presupuesto,
						 categoria_programatica,
						 clasificador_presupuestario,
						 ordinal
					WHERE
						 (partidas_receptoras_traslado.idtraslados_presupuestarios='".$_GET['id_traslado']."') AND
						 (partidas_receptoras_traslado.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
						 (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						 (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						 (maestro_presupuesto.idordinal=ordinal.idordinal)
						 order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			while ($field=mysql_fetch_array($query)) {
				$linea=$pdf->GetY();
				if ($linea>250) { $pag++; anexo_traslado_presupuestario_simulado($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Aumentadas (Continuacion...)", $estado); $pdf->SetY(61); }
				//
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'L', 'R', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(17, 20, 68, 20, 20, 20, 20, 20));
				$monto=number_format($field[2], 2, ',', '.');
				$sum_monto+=$field['monto_acreditar'];	//
				$monto_actual=number_format($field['monto_actual'], 2, ',', '.');
				$sum_monto_actual+=$field['monto_actual'];	//
				$majustado=$field['monto_actual']+$field['monto_acreditar'];
				$monto_ajustado=number_format($majustado, 2, ',', '.');
				$sum_monto_ajustado+=$majustado;	//
				$mdisponible=$field['monto_actual']-$field['total_compromisos'];
				$monto_disponible=number_format($mdisponible, 2, ',', '.');
				$sum_monto_disponible+=$mdisponible;	//
				$mdajustado=$majustado-$field['total_compromisos'];
				$monto_disponible_ajustado=number_format($mdajustado, 2, ',', '.');
				$sum_monto_disponible_ajustado+=$mdajustado;	//
				if ($field['codigo']=="0000") { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']; $denominacion=$field['NomPartida']; }
				else { $partida=$field['partida'].".".$field['genercia'].".".$field['especifica'].".".$field['sub_especifica']." ".$field['codigo']; $denominacion=$field['NomPartida']; }
				$pdf->Row(array($field['CodCategoria'], $partida, utf8_decode($denominacion), $monto_actual, $monto, $monto_ajustado, $monto_disponible, $monto_disponible_ajustado));
			}
			$sum_monto=number_format($sum_monto, 2, ',', '.');
			$sum_monto_actual=number_format($sum_monto_actual, 2, ',', '.');
			$sum_monto_ajustado=number_format($sum_monto_ajustado, 2, ',', '.');
			$sum_monto_disponible=number_format($sum_monto_disponible, 2, ',', '.');
			$sum_monto_disponible_ajustado=number_format($sum_monto_disponible_ajustado, 2, ',', '.');
			$pdf->SetDrawColor(0, 0, 0);
			$y=$pdf->GetY(); $y+=1;
			$pdf->Rect(5, $y, 205, 0.01);
			$pdf->SetY($y+2);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->SetAligns(array('R', 'R', 'R', 'R', 'R', 'R'));
			$pdf->SetWidths(array(105, 20, 20, 20, 20, 20));
			$pdf->Row(array('', $sum_monto_actual, $sum_monto, $sum_monto_ajustado, $sum_monto_disponible, $sum_monto_disponible_ajustado));
		}
		break;

	//	(Modulo) Disminucion Presupuesto...
	case "disminucion_presupuestaria":
		$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pag=0;
		//-----------	CONSULTO DISMINUCION PRESUPUESTO PARA OBTENER EL HEAD DEL PDF
		$sql="SELECT nro_solicitud, fecha_solicitud, nro_resolucion, fecha_resolucion, justificacion, anio, total_disminucion, estado FROM disminucion_presupuesto WHERE (iddisminucion_presupuesto='".$_GET['id_disminucion']."')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		if ($field=mysql_fetch_array($query)) {
			$nsolicitud=$field[0];
			list($a, $m, $d)=SPLIT( '[/.-]', $field[1]); $fsolicitud=$d."/".$m."/".$a;
			$nresolucion=$field[2];
			list($a, $m, $d)=SPLIT( '[/.-]', $field[3]); $fresolucion=$d."/".$m."/".$a;
			$justificacion=$field[4];
			$anio=$field[5];
			$debito=number_format($field[6], 2, ',', '.');
			$estado=$field[7];
			$pag++;
		}
		if ($solicitud=="true") {
			disminucion_presupuestaria($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Disminuidas", 108, $estado);
			//-----------
			$pdf->SetXY(5, 42);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(25, 7, utf8_decode('Nro. Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(50, 7, $nresolucion, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(28, 7, utf8_decode('Fecha Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(102, 7, $fresolucion, 0, 0, 'L'); $pdf->Ln(7);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(205, 7, utf8_decode('Justificación: '), 0, 1, 'L');

			$pdf->SetAligns(array('L'));
			$pdf->SetWidths(array(200));
			$pdf->Row(array(utf8_decode($justificacion)));
			//-----------
			$pdf->SetXY(5, 98);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(12, 7, utf8_decode('Año:'), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(23, 7, $anio, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(27, 7, 'Total Disminuir: ', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(58, 7, $debito, 0, 0, 'L');
			//-----------
			$sql="SELECT partidas_disminucion_presupuesto.idpartida_disminucion_presupuesto,
						 partidas_disminucion_presupuesto.idmaestro_presupuesto,
						 partidas_disminucion_presupuesto.monto_debitar,
						 maestro_presupuesto.idcategoria_programatica,
						 maestro_presupuesto.idclasificador_presupuestario,
						 maestro_presupuesto.idordinal,
						 categoria_programatica.codigo AS CodCategoria,
						 clasificador_presupuestario.partida,
						 clasificador_presupuestario.generica,
						 clasificador_presupuestario.especifica,
						 clasificador_presupuestario.sub_especifica,
						 clasificador_presupuestario.denominacion AS NomPartida,
						 ordinal.codigo,
						 ordinal.denominacion AS NomOrdinal
					FROM
						 partidas_disminucion_presupuesto,
						 maestro_presupuesto,
						 categoria_programatica,
						 clasificador_presupuestario,
						 ordinal
					WHERE
						 (partidas_disminucion_presupuesto.iddisminucion_presupuesto='".$_GET['id_disminucion']."') AND
						 (partidas_disminucion_presupuesto.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
						 (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						 (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						 (maestro_presupuesto.idordinal=ordinal.idordinal)
						 order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$pdf->SetXY(5, 119);
			while ($field=mysql_fetch_array($query)) {
				$linea=$pdf->GetY();
				if ($linea>250) { $pag++; disminucion_presupuestaria($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Disminuidas (Continuacion...)", 50, $estado); $pdf->SetY(61); }
				//
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 9);
				$pdf->SetAligns(array('C', 'C', 'L', 'R'));
				$pdf->SetWidths(array(25, 35, 100, 45));
				$monto=number_format($field[2], 2, ',', '.');
				$sum_monto+=$field[2];
				if ($field['codigo']=="0000") { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']; $denominacion=$field['NomPartida']; }
				else { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']." ".$field['codigo']; $denominacion=$field['NomPartida']; }
				$pdf->Row(array($field['CodCategoria'], $partida, utf8_decode($denominacion), $monto));
			}
			$sum_monto=number_format($sum_monto, 2, ',', '.');
			$pdf->SetDrawColor(0, 0, 0);
			$y=$pdf->GetY(); $y+=1;
			$pdf->Rect(5, $y, 205, 0.01);
			$pdf->SetY($y+2);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 11);
			$pdf->SetAligns(array('C', 'C', 'R', 'R'));
			$pdf->SetWidths(array(25, 35, 100, 45));
			$pdf->Row(array('', '', 'Totales:', $sum_monto));
		}
		else if ($simulado=="true") {
			disminucion_presupuestaria_simulado($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Disminuidas", 108, $estado);
			//-----------
			$pdf->SetXY(5, 42);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(25, 7, utf8_decode('Nro. Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(50, 7, $nresolucion, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(28, 7, utf8_decode('Fecha Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(102, 7, $fresolucion, 0, 0, 'L'); $pdf->Ln(7);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(205, 7, utf8_decode('Justificación: '), 0, 1, 'L');

			$pdf->SetAligns(array('L'));
			$pdf->SetWidths(array(200));
			$pdf->Row(array(utf8_decode($justificacion)));
			//-----------
			$pdf->SetXY(5, 98);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(12, 7, utf8_decode('Año:'), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(23, 7, $anio, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(27, 7, 'Total Disminuir: ', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(58, 7, $debito, 0, 0, 'L');
			//-----------
			$sql="SELECT partidas_disminucion_presupuesto.idpartida_disminucion_presupuesto,
						 partidas_disminucion_presupuesto.idmaestro_presupuesto,
						 partidas_disminucion_presupuesto.monto_debitar,
						 maestro_presupuesto.idcategoria_programatica,
						 maestro_presupuesto.idclasificador_presupuestario,
						 maestro_presupuesto.idordinal,
						 maestro_presupuesto.monto_actual,
						 maestro_presupuesto.total_compromisos,
						 categoria_programatica.codigo AS CodCategoria,
						 clasificador_presupuestario.partida,
						 clasificador_presupuestario.generica,
						 clasificador_presupuestario.especifica,
						 clasificador_presupuestario.sub_especifica,
						 clasificador_presupuestario.denominacion AS NomPartida,
						 ordinal.codigo,
						 ordinal.denominacion AS NomOrdinal
					FROM
						 partidas_disminucion_presupuesto,
						 maestro_presupuesto,
						 categoria_programatica,
						 clasificador_presupuestario,
						 ordinal
					WHERE
						 (partidas_disminucion_presupuesto.iddisminucion_presupuesto='".$_GET['id_disminucion']."') AND
						 (partidas_disminucion_presupuesto.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
						 (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						 (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						 (maestro_presupuesto.idordinal=ordinal.idordinal)
						 order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$pdf->SetXY(5, 119);
			while ($field=mysql_fetch_array($query)) {
				$linea=$pdf->GetY();
				if ($linea>250) { $pag++; disminucion_presupuestaria_simulado($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Disminuidas (Continuacion...)", 50, $estado); $pdf->SetY(61); }
				//
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'L', 'R', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(17, 20, 68, 20, 20, 20, 20, 20));
				$monto=number_format($field[2], 2, ',', '.');
				$sum_monto+=$field['monto_debitar'];	//
				$monto_actual=number_format($field['monto_actual'], 2, ',', '.');
				$sum_monto_actual+=$field['monto_actual'];	//
				$majustado=$field['monto_actual']+$field['monto_debitar'];
				$monto_ajustado=number_format($majustado, 2, ',', '.');
				$sum_monto_ajustado+=$majustado;	//
				$mdisponible=$field['monto_actual']-$field['total_compromisos'];
				$monto_disponible=number_format($mdisponible, 2, ',', '.');
				$sum_monto_disponible+=$mdisponible;	//
				$mdajustado=$majustado-$field['total_compromisos'];
				$monto_disponible_ajustado=number_format($mdajustado, 2, ',', '.');
				$sum_monto_disponible_ajustado+=$mdajustado;	//
				if ($field['codigo']=="0000") { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']; $denominacion=$field['NomPartida']; }
				else { $partida=$field['partida'].".".$field['genercia'].".".$field['especifica'].".".$field['sub_especifica']." ".$field['codigo']; $denominacion=$field['NomPartida']; }
				$pdf->Row(array($field['CodCategoria'], $partida, utf8_decode($denominacion), $monto_actual, $monto, $monto_ajustado, $monto_disponible, $monto_disponible_ajustado));
			}
			$sum_monto=number_format($sum_monto, 2, ',', '.');
			$sum_monto_actual=number_format($sum_monto_actual, 2, ',', '.');
			$sum_monto_ajustado=number_format($sum_monto_ajustado, 2, ',', '.');
			$sum_monto_disponible=number_format($sum_monto_disponible, 2, ',', '.');
			$sum_monto_disponible_ajustado=number_format($sum_monto_disponible_ajustado, 2, ',', '.');
			$pdf->SetDrawColor(0, 0, 0);
			$y=$pdf->GetY(); $y+=1;
			$pdf->Rect(5, $y, 205, 0.01);
			$pdf->SetY($y+2);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->SetAligns(array('R', 'R', 'R', 'R', 'R', 'R'));
			$pdf->SetWidths(array(105, 20, 20, 20, 20, 20));
			$pdf->Row(array('', $sum_monto_actual, $sum_monto, $sum_monto_ajustado, $sum_monto_disponible, $sum_monto_disponible_ajustado));
		}
		break;

	//	(Modulo) Creditos Adicionales...
	case "credito_adicional":
		$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pag=0;
		//-----------	CONSULTO DISMINUCION PRESUPUESTO PARA OBTENER EL HEAD DEL PDF
		$sql="SELECT nro_solicitud, fecha_solicitud, nro_resolucion, fecha_resolucion, justificacion, anio, total_credito, estado FROM creditos_adicionales WHERE (idcreditos_adicionales='".$_GET['id_credito']."')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		if ($field=mysql_fetch_array($query)) {
			$nsolicitud=$field[0];
			list($a, $m, $d)=SPLIT( '[/.-]', $field[1]); $fsolicitud=$d."/".$m."/".$a;
			$nresolucion=$field[2];
			list($a, $m, $d)=SPLIT( '[/.-]', $field[3]); $fresolucion=$d."/".$m."/".$a;
			$justificacion=$field[4];
			$anio=$field[5];
			$credito=number_format($field[6], 2, ',', '.');
			$estado=$field[7];
			$pag++;
		}
		if ($solicitud=="true") {
			credito_adicional($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Aumentadas", 108, $estado);
			//-----------
			$pdf->SetXY(5, 42);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(25, 7, utf8_decode('Nro. Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(50, 7, $nresolucion, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(28, 7, utf8_decode('Fecha Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(102, 7, $fresolucion, 0, 0, 'L'); $pdf->Ln(7);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(205, 7, utf8_decode('Justificación: '), 0, 1, 'L');

			$pdf->SetAligns(array('L'));
			$pdf->SetWidths(array(200));
			$pdf->Row(array(utf8_decode($justificacion)));
			//-----------
			$pdf->SetXY(5, 98);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(12, 7, utf8_decode('Año:'), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(23, 7, $anio, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(27, 7, 'Total Aumentar: ', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(58, 7, $credito, 0, 0, 'L');
			//-----------
			$sql="SELECT partidas_credito_adicional.idpartida_credito_adicional,
						 partidas_credito_adicional.idmaestro_presupuesto,
						 partidas_credito_adicional.monto_acreditar,
						 maestro_presupuesto.idcategoria_programatica,
						 maestro_presupuesto.idclasificador_presupuestario,
						 maestro_presupuesto.idordinal,
						 categoria_programatica.codigo AS CodCategoria,
						 clasificador_presupuestario.partida,
						 clasificador_presupuestario.generica,
						 clasificador_presupuestario.especifica,
						 clasificador_presupuestario.sub_especifica,
						 clasificador_presupuestario.denominacion AS NomPartida,
						 clasificador_presupuestario.codigo_cuenta,
						 ordinal.codigo,
						 ordinal.denominacion As NomOrdinal
					FROM
						 partidas_credito_adicional,
						 maestro_presupuesto,
						 categoria_programatica,
						 clasificador_presupuestario,
						 ordinal
					WHERE
						 (partidas_credito_adicional.idcredito_adicional='".$_GET['id_credito']."') AND
						 (partidas_credito_adicional.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
						 (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						 (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						 (maestro_presupuesto.idordinal=ordinal.idordinal)
						 order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			$pdf->SetXY(5, 119);
			while ($field=mysql_fetch_array($query)) {
				$linea=$pdf->GetY();
				if ($linea>250) { $pag++; credito_adicional($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Aumentadas (Continuacion...)", 50, $estado); $pdf->SetY(61); }
				//
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 9);
				$pdf->SetAligns(array('C', 'C', 'L', 'R'));
				$pdf->SetWidths(array(25, 35, 100, 45));
				$monto=number_format($field[2], 2, ',', '.');
				$sum_monto+=$field[2];
				if ($field['codigo']=="0000") { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']; $denominacion=$field['NomPartida']; }
				else { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']." ".$field['codigo']; $denominacion=$field['NomPartida']; }
				$pdf->Row(array($field['CodCategoria'], $partida, utf8_decode($denominacion), $monto));
			}
			$sum_monto=number_format($sum_monto, 2, ',', '.');
			$pdf->SetDrawColor(0, 0, 0);
			$y=$pdf->GetY(); $y+=1;
			$pdf->Rect(5, $y, 205, 0.01);
			$pdf->SetY($y+2);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 11);
			$pdf->SetAligns(array('C', 'C', 'R', 'R'));
			$pdf->SetWidths(array(25, 35, 100, 45));
			$pdf->Row(array('', '', 'Totales:', $sum_monto));
		}
		else if ($simulado=="true") {
			credito_adicional_simulado($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Aumentadas", 109, $estado);
			//-----------
			$pdf->SetXY(5, 42);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(25, 7, utf8_decode('Nro. Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(50, 7, $nresolucion, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(28, 7, utf8_decode('Fecha Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(102, 7, $fresolucion, 0, 0, 'L'); $pdf->Ln(7);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(205, 7, utf8_decode('Justificación: '), 0, 1, 'L');

			$pdf->SetAligns(array('L'));
			$pdf->SetWidths(array(200));
			$pdf->Row(array(utf8_decode($justificacion)));
			//-----------
			$pdf->SetXY(5, 98);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(12, 7, utf8_decode('Año:'), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(23, 7, $anio, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(27, 7, 'Total Aumentar: ', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(58, 7, $credito, 0, 0, 'L');
			//-----------
			$sql="SELECT partidas_credito_adicional.idpartida_credito_adicional,
						 partidas_credito_adicional.idmaestro_presupuesto,
						 partidas_credito_adicional.monto_acreditar,
						 maestro_presupuesto.idcategoria_programatica,
						 maestro_presupuesto.idclasificador_presupuestario,
						 maestro_presupuesto.idordinal,
						 categoria_programatica.codigo AS CodCategoria,
						 clasificador_presupuestario.partida,
						 clasificador_presupuestario.generica,
						 clasificador_presupuestario.especifica,
						 clasificador_presupuestario.sub_especifica,
						 clasificador_presupuestario.denominacion AS NomPartida,
						 clasificador_presupuestario.codigo_cuenta,
						 ordinal.codigo,
						 ordinal.denominacion As NomOrdinal
					FROM
						 partidas_credito_adicional,
						 maestro_presupuesto,
						 categoria_programatica,
						 clasificador_presupuestario,
						 ordinal
					WHERE
						 (partidas_credito_adicional.idcredito_adicional='".$_GET['id_credito']."') AND
						 (partidas_credito_adicional.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
						 (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						 (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						 (maestro_presupuesto.idordinal=ordinal.idordinal)
						 order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			$pdf->SetXY(5, 119);
			while ($field=mysql_fetch_array($query)) {
				$linea=$pdf->GetY();
				if ($linea>250) { $pag++; credito_adicional_simulado($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Aumentadas (Continuacion...)", 50, $estado); $pdf->SetY(61); }
				//
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'L', 'R', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(17, 20, 68, 20, 20, 20, 20, 20));
				$monto=number_format($field[2], 2, ',', '.');
				$sum_monto+=$field['monto_acreditar'];	//
				$monto_actual=number_format($field['monto_actual'], 2, ',', '.');
				$sum_monto_actual+=$field['monto_actual'];	//
				$majustado=$field['monto_actual']+$field['monto_acreditar'];
				$monto_ajustado=number_format($majustado, 2, ',', '.');
				$sum_monto_ajustado+=$majustado;	//
				$mdisponible=$field['monto_actual']-$field['total_compromisos'];
				$monto_disponible=number_format($mdisponible, 2, ',', '.');
				$sum_monto_disponible+=$mdisponible;	//
				$mdajustado=$majustado-$field['total_compromisos'];
				$monto_disponible_ajustado=number_format($mdajustado, 2, ',', '.');
				$sum_monto_disponible_ajustado+=$mdajustado;	//
				if ($field['codigo']=="0000") { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']; $denominacion=$field['NomPartida']; }
				else { $partida=$field['partida'].".".$field['genercia'].".".$field['especifica'].".".$field['sub_especifica']." ".$field['codigo']; $denominacion=$field['NomPartida']; }
				$pdf->Row(array($field['CodCategoria'], $partida, utf8_decode($denominacion), $monto_actual, $monto, $monto_ajustado, $monto_disponible, $monto_disponible_ajustado));
			}
			$sum_monto=number_format($sum_monto, 2, ',', '.');
			$sum_monto_actual=number_format($sum_monto_actual, 2, ',', '.');
			$sum_monto_ajustado=number_format($sum_monto_ajustado, 2, ',', '.');
			$sum_monto_disponible=number_format($sum_monto_disponible, 2, ',', '.');
			$sum_monto_disponible_ajustado=number_format($sum_monto_disponible_ajustado, 2, ',', '.');
			$pdf->SetDrawColor(0, 0, 0);
			$y=$pdf->GetY(); $y+=1;
			$pdf->Rect(5, $y, 205, 0.01);
			$pdf->SetY($y+2);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->SetAligns(array('R', 'R', 'R', 'R', 'R', 'R'));
			$pdf->SetWidths(array(105, 20, 20, 20, 20, 20));
			$pdf->Row(array('', $sum_monto_actual, $sum_monto, $sum_monto_ajustado, $sum_monto_disponible, $sum_monto_disponible_ajustado));
		}
		break;

	//	(Modulo) Rectificacion de Partidas...
	case "rectificacion_partida":
		$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pag=0;
		//-----------	CONSULTO TRASLADOS PRESUPUESTARIOS PARA OBTENER EL HEAD DEL PDF
		$sql="SELECT nro_solicitud, fecha_solicitud, nro_resolucion, fecha_resolucion, justificacion, anio, total_credito, total_debito, estado FROM rectificacion_presupuesto WHERE (idrectificacion_presupuesto='".$_GET['id_rectificacion']."')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		if ($field=mysql_fetch_array($query)) {
			$nsolicitud=$field[0];
			list($a, $m, $d)=SPLIT( '[/.-]', $field[1]); $fsolicitud=$d."/".$m."/".$a;
			$nresolucion=$field[2];
			list($a, $m, $d)=SPLIT( '[/.-]', $field[3]); $fresolucion=$d."/".$m."/".$a;
			$justificacion=$field[4];
			$anio=$field[5];
			$credito=number_format($field[6], 2, ',', '.');
			$debito=number_format($field[7], 2, ',', '.');
			$estado=$field[8];
			$pag++;
		}
		if ($solicitud=="true") {
			rectificacion_partida($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Rectificadoras", $estado);
			//-----------
			$pdf->SetXY(5, 42);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(25, 7, utf8_decode('Nro. Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(50, 7, $nresolucion, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(28, 7, utf8_decode('Fecha Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(102, 7, $fresolucion, 0, 0, 'L'); $pdf->Ln(7);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(205, 7, utf8_decode('Justificación: '), 0, 1, 'L');

			$pdf->SetAligns(array('L'));
			$pdf->SetWidths(array(200));
			$pdf->Row(array(utf8_decode($justificacion)));
			//$pdf->MultiCell(205, 5, $justificacion, 0, 'L');
			//-----------
			$pdf->SetXY(5, 98);

			$pdf->SetFont('Arial', '', 9); $pdf->Cell(12, 7, utf8_decode('Año:'), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(23, 7, $anio, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(30, 7, 'Total Rectificadoras: ', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(58, 7, $debito, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(27, 7, 'Total Receptoras: ', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(58, 7, $credito, 0, 0, 'L');
			//-----------
			$sql="SELECT partidas_rectificadoras.idpartida_rectificadora,
						 partidas_rectificadoras.idmaestro_presupuesto,
						 partidas_rectificadoras.monto_debitar,
						 maestro_presupuesto.idcategoria_programatica,
						 maestro_presupuesto.idclasificador_presupuestario,
						 maestro_presupuesto.idordinal,
						 categoria_programatica.codigo AS CodCategoria,
						 clasificador_presupuestario.partida,
						 clasificador_presupuestario.generica,
						 clasificador_presupuestario.especifica,
						 clasificador_presupuestario.sub_especifica,
						 clasificador_presupuestario.denominacion AS NomPartida,
						 clasificador_presupuestario.codigo_cuenta,
						 ordinal.codigo,
						 ordinal.denominacion AS NomOrdinal
					FROM
						 partidas_rectificadoras,
						 maestro_presupuesto,
						 categoria_programatica,
						 clasificador_presupuestario,
						 ordinal
					WHERE
						 (partidas_rectificadoras.idrectificacion_presupuesto='".$_GET['id_rectificacion']."') AND
						 (partidas_rectificadoras.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
						 (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						 (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						 (maestro_presupuesto.idordinal=ordinal.idordinal)
						 order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$pdf->SetXY(5, 119);
			while ($field=mysql_fetch_array($query)) {
				$linea=$pdf->GetY();
				if ($linea>250) { $pag++; anexo_rectificacion_partida($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Rectificadoras (Continuacion...)", $estado); $pdf->SetY(61); }
				//
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 9);
				$pdf->SetAligns(array('C', 'C', 'L', 'R'));
				$pdf->SetWidths(array(25, 35, 100, 45));
				$monto=number_format($field[2], 2, ',', '.');
				$sum_monto+=$field[2];
				if ($field['codigo']=="0000") { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']; $denominacion=$field['NomPartida']; }
					else { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']." ".$field['codigo']; $denominacion=$field['NomPartida']; }
				$pdf->Row(array($field['CodCategoria'], $partida, utf8_decode($denominacion), $monto));
			}
			$sum_monto=number_format($sum_monto, 2, ',', '.');
			$pdf->SetDrawColor(0, 0, 0);
			$y=$pdf->GetY(); $y+=1;
			$pdf->Rect(5, $y, 205, 0.01);
			$pdf->SetY($y+2);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 11);
			$pdf->SetAligns(array('C', 'C', 'R', 'R'));
			$pdf->SetWidths(array(25, 35, 100, 45));
			$pdf->Row(array('', '', 'Totales:', $sum_monto));
			$sum_monto=0;
			//-----------
			$y=$pdf->GetY();
			if ($y>220) { $pag++; anexo_rectificacion_partida($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Receptoras (Continuacion...)", $estado); $pdf->SetY(61); }
			else {
				$pdf->SetXY(5, $y+10);
				$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(205, 5, 'Partidas Receptoras', 0, 1, 'C');
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(25, 5, 'CAT.PROGR.', 1, 0, 'C', 1);
				$pdf->Cell(35, 5, 'PARTIDA', 1, 0, 'C', 1);
				$pdf->Cell(100, 5, 'DESCRIPCION', 1, 0, 'C', 1);
				$pdf->Cell(45, 5, 'MONTO', 1, 0, 'C', 1);
				$pdf->Ln(6);
			}
			//
			$sql="SELECT partidas_receptoras_rectificacion.idpartida_receptoras_rectificacion,
						 partidas_receptoras_rectificacion.idmaestro_presupuesto,
						 partidas_receptoras_rectificacion.monto_acreditar,
						 maestro_presupuesto.idcategoria_programatica,
						 maestro_presupuesto.idclasificador_presupuestario,
						 maestro_presupuesto.idordinal,
						 categoria_programatica.codigo AS CodCategoria,
						 clasificador_presupuestario.partida,
						 clasificador_presupuestario.generica,
						 clasificador_presupuestario.especifica,
						 clasificador_presupuestario.sub_especifica,
						 clasificador_presupuestario.denominacion AS NomPartida,
						 clasificador_presupuestario.codigo_cuenta,
						 ordinal.codigo,
						 ordinal.denominacion AS NomOrdinal
					FROM
						 partidas_receptoras_rectificacion,
						 maestro_presupuesto,
						 categoria_programatica,
						 clasificador_presupuestario,
						 ordinal
					WHERE
						 (partidas_receptoras_rectificacion.idrectificacion_presupuesto='".$_GET['id_rectificacion']."') AND
						 (partidas_receptoras_rectificacion.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
						 (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						 (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						 (maestro_presupuesto.idordinal=ordinal.idordinal)
						 order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			while ($field=mysql_fetch_array($query)) {
				$linea=$pdf->GetY();
				if ($linea>250) { $pag++; anexo_rectificacion_partida($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Receptoras (Continuacion...)", $estado); $pdf->SetY(61); }
				//
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 9);
				$pdf->SetAligns(array('C', 'C', 'L', 'R'));
				$pdf->SetWidths(array(25, 35, 100, 45));
				$monto=number_format($field[2], 2, ',', '.');
				$sum_monto+=$field[2];
				if ($field['codigo']=="0000") { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']; $denominacion=$field['NomPartida']; }
				else { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']." ".$field['codigo']; $denominacion=$field['NomPartida']; }
				$pdf->Row(array($field['CodCategoria'], $partida, utf8_decode($denominacion), $monto));
			}
			$sum_monto=number_format($sum_monto, 2, ',', '.');
			$pdf->SetDrawColor(0, 0, 0);
			$y=$pdf->GetY(); $y+=1;
			$pdf->Rect(5, $y, 205, 0.01);
			$pdf->SetY($y+2);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 11);
			$pdf->SetAligns(array('C', 'C', 'R', 'R'));
			$pdf->SetWidths(array(25, 35, 100, 45));
			$pdf->Row(array('', '', 'Totales:', $sum_monto));
		}
		else if ($simulado=="true") {
			rectificacion_partida_simulado($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Rectificadoras", $estado);
			//-----------
			$pdf->SetXY(5, 42);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(25, 7, utf8_decode('Nro. Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(50, 7, $nresolucion, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(28, 7, utf8_decode('Fecha Resolución: '), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(102, 7, $fresolucion, 0, 0, 'L'); $pdf->Ln(7);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(205, 7, utf8_decode('Justificación: '), 0, 1, 'L');

			$pdf->SetAligns(array('L'));
			$pdf->SetWidths(array(200));
			$pdf->Row(array(utf8_decode($justificacion)));
			//$pdf->MultiCell(205, 5, $justificacion, 0, 'L');
			//-----------
			$pdf->SetXY(5, 98);

			$pdf->SetFont('Arial', '', 9); $pdf->Cell(12, 7, utf8_decode('Año:'), 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(23, 7, $anio, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(30, 7, 'Total Rectificadoras: ', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(58, 7, $debito, 0, 0, 'L');
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(27, 7, 'Total Receptoras: ', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(58, 7, $credito, 0, 0, 'L');
			//-----------
			$sql="SELECT partidas_rectificadoras.idpartida_rectificadora,
						 partidas_rectificadoras.idmaestro_presupuesto,
						 partidas_rectificadoras.monto_debitar,
						 maestro_presupuesto.idcategoria_programatica,
						 maestro_presupuesto.idclasificador_presupuestario,
						 maestro_presupuesto.idordinal,
						 maestro_presupuesto.monto_actual,
						 maestro_presupuesto.total_compromisos,
						 categoria_programatica.codigo AS CodCategoria,
						 clasificador_presupuestario.partida,
						 clasificador_presupuestario.generica,
						 clasificador_presupuestario.especifica,
						 clasificador_presupuestario.sub_especifica,
						 clasificador_presupuestario.denominacion AS NomPartida,
						 clasificador_presupuestario.codigo_cuenta,
						 ordinal.codigo,
						 ordinal.denominacion AS NomOrdinal
					FROM
						 partidas_rectificadoras,
						 maestro_presupuesto,
						 categoria_programatica,
						 clasificador_presupuestario,
						 ordinal
					WHERE
						 (partidas_rectificadoras.idrectificacion_presupuesto='".$_GET['id_rectificacion']."') AND
						 (partidas_rectificadoras.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
						 (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						 (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						 (maestro_presupuesto.idordinal=ordinal.idordinal)
						 order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$pdf->SetXY(5, 119);
			while ($field=mysql_fetch_array($query)) {
				$linea=$pdf->GetY();
				if ($linea>250) { $pag++; anexo_rectificacion_partida_simulado($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Rectificadoras (Continuacion...)", $estado); $pdf->SetY(61); }
				//
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'L', 'R', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(17, 20, 68, 20, 20, 20, 20, 20));
				$monto=number_format($field[2], 2, ',', '.');
				$sum_monto+=$field['monto_debitar'];	//
				$monto_actual=number_format($field['monto_actual'], 2, ',', '.');
				$sum_monto_actual+=$field['monto_actual'];	//
				$majustado=$field['monto_actual']-$field['monto_debitar'];
				$monto_ajustado=number_format($majustado, 2, ',', '.');
				$sum_monto_ajustado+=$majustado;	//
				$mdisponible=$field['monto_actual']-$field['total_compromisos'];
				$monto_disponible=number_format($mdisponible, 2, ',', '.');
				$sum_monto_disponible+=$mdisponible;	//
				$mdajustado=$majustado-$field['total_compromisos'];
				$monto_disponible_ajustado=number_format($mdajustado, 2, ',', '.');
				$sum_monto_disponible_ajustado+=$mdajustado;	//
				if ($field['codigo']=="0000") { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']; $denominacion=$field['NomPartida']; }
				else { $partida=$field['partida'].".".$field['genercia'].".".$field['especifica'].".".$field['sub_especifica']." ".$field['codigo']; $denominacion=$field['NomPartida']; }
				$pdf->Row(array($field['CodCategoria'], $partida, utf8_decode($denominacion), $monto_actual, $monto, $monto_ajustado, $monto_disponible, $monto_disponible_ajustado));
			}
			$sum_monto=number_format($sum_monto, 2, ',', '.');
			$sum_monto_actual=number_format($sum_monto_actual, 2, ',', '.');
			$sum_monto_ajustado=number_format($sum_monto_ajustado, 2, ',', '.');
			$sum_monto_disponible=number_format($sum_monto_disponible, 2, ',', '.');
			$sum_monto_disponible_ajustado=number_format($sum_monto_disponible_ajustado, 2, ',', '.');
			$pdf->SetDrawColor(0, 0, 0);
			$y=$pdf->GetY(); $y+=1;
			$pdf->Rect(5, $y, 205, 0.01);
			$pdf->SetY($y+2);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->SetAligns(array('R', 'R', 'R', 'R', 'R', 'R'));
			$pdf->SetWidths(array(105, 20, 20, 20, 20, 20));
			$pdf->Row(array('', $sum_monto_actual, $sum_monto, $sum_monto_ajustado, $sum_monto_disponible, $sum_monto_disponible_ajustado));
			$sum_monto=0; $sum_monto_actual=0; $sum_monto=0; $sum_monto_ajustado=0; $sum_monto_disponible=0; $sum_monto_disponible_ajustado=0;
			//-----------
			$y=$pdf->GetY();
			if ($y>220) { $pag++; anexo_rectificacion_partida_simulado($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Receptoras (Continuacion...)", $estado); $pdf->SetY(61); }
			else {
				$pdf->SetXY(5, $y+10);
				$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(205, 5, 'Partidas Receptoras', 0, 1, 'C');
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 6);
				$pdf->Cell(17, 5, 'C.Prog', 1, 0, 'C', 1);
				$pdf->Cell(20, 5, 'Partida', 1, 0, 'C', 1);
				$pdf->Cell(68, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
				$pdf->Cell(20, 5, 'M.Actual', 1, 0, 'C', 1);
				$pdf->Cell(20, 5, 'M.Ajuste', 1, 0, 'C', 1);
				$pdf->Cell(20, 5, 'M.Ajustado', 1, 0, 'C', 1);
				$pdf->Cell(20, 5, 'Disponible', 1, 0, 'C', 1);
				$pdf->Cell(20, 5, 'D.Ajustado', 1, 0, 'C', 1);
				$pdf->Ln(6);
			}
			//
			$sql="SELECT partidas_receptoras_rectificacion.idpartida_receptoras_rectificacion,
						 partidas_receptoras_rectificacion.idmaestro_presupuesto,
						 partidas_receptoras_rectificacion.monto_acreditar,
						 maestro_presupuesto.idcategoria_programatica,
						 maestro_presupuesto.idclasificador_presupuestario,
						 maestro_presupuesto.idordinal,
						 maestro_presupuesto.monto_actual,
						 maestro_presupuesto.total_compromisos,
						 categoria_programatica.codigo AS CodCategoria,
						 clasificador_presupuestario.partida,
						 clasificador_presupuestario.generica,
						 clasificador_presupuestario.especifica,
						 clasificador_presupuestario.sub_especifica,
						 clasificador_presupuestario.denominacion AS NomPartida,
						 ordinal.codigo,
						 ordinal.denominacion AS NomOrdinal
					FROM
						 partidas_receptoras_rectificacion,
						 maestro_presupuesto,
						 categoria_programatica,
						 clasificador_presupuestario,
						 ordinal
					WHERE
						 (partidas_receptoras_rectificacion.idrectificacion_presupuesto='".$_GET['id_rectificacion']."') AND
						 (partidas_receptoras_rectificacion.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
						 (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						 (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						 (maestro_presupuesto.idordinal=ordinal.idordinal)
						 order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			while ($field=mysql_fetch_array($query)) {
				$linea=$pdf->GetY();
				if ($linea>250) { $pag++; anexo_rectificacion_partida_simulado($pdf, $nsolicitud, $fsolicitud, $pag, "Partidas Receptoras (Continuacion...)", $estado); $pdf->SetY(63); }
				//
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('C', 'C', 'L', 'R', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(17, 20, 68, 20, 20, 20, 20, 20));
				$monto=number_format($field[2], 2, ',', '.');
				$sum_monto+=$field['monto_acreditar'];	//
				$monto_actual=number_format($field['monto_actual'], 2, ',', '.');
				$sum_monto_actual+=$field['monto_actual'];	//
				$majustado=$field['monto_actual']-$field['monto_acreditar'];
				$monto_ajustado=number_format($majustado, 2, ',', '.');
				$sum_monto_ajustado+=$majustado;	//
				$mdisponible=$field['monto_actual']-$field['total_compromisos'];
				$monto_disponible=number_format($mdisponible, 2, ',', '.');
				$sum_monto_disponible+=$mdisponible;	//
				$mdajustado=$majustado-$field['total_compromisos'];
				$monto_disponible_ajustado=number_format($mdajustado, 2, ',', '.');
				$sum_monto_disponible_ajustado+=$mdajustado;	//
				if ($field['codigo']=="0000") { $partida=$field['partida'].".".$field['generica'].".".$field['especifica'].".".$field['sub_especifica']; $denominacion=$field['NomPartida']; }
				else { $partida=$field['partida'].".".$field['genercia'].".".$field['especifica'].".".$field['sub_especifica']." ".$field['codigo']; $denominacion=$field['NomPartida']; }
				$pdf->Row(array($field['CodCategoria'], $partida, utf8_decode($denominacion), $monto_actual, $monto, $monto_ajustado, $monto_disponible, $monto_disponible_ajustado));
			}
			$sum_monto=number_format($sum_monto, 2, ',', '.');
			$sum_monto_actual=number_format($sum_monto_actual, 2, ',', '.');
			$sum_monto_ajustado=number_format($sum_monto_ajustado, 2, ',', '.');
			$sum_monto_disponible=number_format($sum_monto_disponible, 2, ',', '.');
			$sum_monto_disponible_ajustado=number_format($sum_monto_disponible_ajustado, 2, ',', '.');
			$pdf->SetDrawColor(0, 0, 0);
			$y=$pdf->GetY(); $y+=1;
			$pdf->Rect(5, $y, 205, 0.01);
			$pdf->SetY($y+2);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->SetAligns(array('R', 'R', 'R', 'R', 'R', 'R'));
			$pdf->SetWidths(array(105, 20, 20, 20, 20, 20));
			$pdf->Row(array('', $sum_monto_actual, $sum_monto, $sum_monto_ajustado, $sum_monto_disponible, $sum_monto_disponible_ajustado));
		}
		break;

	//	Compromisos en Transito...
	case "compromisos_en_transito":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		//	-----------------------------
		if ($categoria != "0") {
			$where_ocs = " AND ocs.idcategoria_programatica = '".$categoria."' ";
			$where_op = " AND op.idcategoria_programatica = '".$categoria."' ";
		}
		if ($desde != "" && $hasta != "") {
			$where_ocs .= " AND ocs.fecha_orden >= '".$desde."' AND ocs.fecha_orden <= '".$hasta."' ";
			$where_op .= " AND op.fecha_orden >= '".$desde."' AND op.fecha_orden <= '".$hasta."' ";
		}
		//	-----------------------------
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
					  ocs.anio = '".$anio_fiscal."' AND
					  ocs.idfuente_financiamiento = '".$financiamiento."' AND
					  ocs.idtipo_presupuesto = '".$tipo_presupuesto."' $where_ocs)

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
					  op.anio = '".$anio_fiscal."' AND
					  op.idfuente_financiamiento = '".$financiamiento."' AND
					  op.idtipo_presupuesto = '".$tipo_presupuesto."' $where_op)

				ORDER BY idcategoria_programatica, fecha_orden";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		while ($field=mysql_fetch_array($query)) {
			$linea = $pdf->GetY();
			if (($linea + $alto) > 260 || $i == 0 || $idcategoria != $field['idcategoria_programatica']) {
				if ($i != 0) $alto = getH($pdf, 4, 70, $field['justificacion']);
				if (($linea + $alto) > 260 || $i == 0) compromisos_en_transito($pdf);

				$idcategoria = $field['idcategoria_programatica'];
				if ($idcategoria == "0") $categoria_programatica = "MULTI-CATEGORIAS";
				else $categoria_programatica = $field['codcategoria'].' - '.$field['nomcategoria'];
				$pdf->SetFont('Arial', 'B', 6);
				$pdf->Cell(190, 5, $categoria_programatica, 0, 1, 'L');
				//	--------------------
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 6);
				$pdf->SetAligns(array('L', 'C', 'L', 'L', 'L', 'C', 'R'));
				$pdf->SetWidths(array(15, 15, 25, 55, 55, 20, 15));
				$pdf->Row(array('Nro. Orden', 'Fecha Orden', 'Tipo', utf8_decode('Justificación'), 'Beneficiario', 'Estado', 'Total'));
				$pdf->Ln(1);
			}

			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha_orden = "$d/$m/$a";

			$pdf->SetFont('Arial', '', 6);
			$pdf->Row(array($field['numero_orden'], $fecha_orden, utf8_decode(strtoupper($field['TipoDocumento'])), utf8_decode($field['justificacion']), utf8_decode($field['nombeneficiario']), strtoupper($field['estado']), number_format($field['total'], 2, ',', '.')));
			$pdf->Ln(1);

			$i++;
		}
		break;

	//	Movimientos de Presupuesto...
	case "movimientos_presupuesto":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		//	-----------------------------
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//	-----------------------------
		movimientos_presupuesto($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto);
		//	-----------------------------
		$sql="(SELECT partidas_cedentes_traslado.idpartida_cedentes_traslado AS IdMovimiento,
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
					(maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."') AND
					(maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."') AND
					(maestro_presupuesto.anio = '".$anio_fiscal."'))

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
					 (maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."') AND
					 (maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."') AND
					 (maestro_presupuesto.anio = '".$anio_fiscal."'))

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
					 (maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."') AND
					 (maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."') AND
					 (maestro_presupuesto.anio = '".$anio_fiscal."'))

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
					 (maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."') AND
					 (maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."') AND
					 (maestro_presupuesto.anio = '".$anio_fiscal."'))";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$pdf->Row(array($field['CodCategoria'], $field['CodPartida'], utf8_decode($field['NomPartida']), utf8_decode($field['TipoMovimiento']), number_format($field['Monto'], 2, ',', '.')));
			$pdf->Ln(1);
		}
		break;

	//	Consolidado Agrupado...
	case "consolidado_agrupado":
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
		if ($idcategoria_desde != "" && $idcategoria_hasta != "") $filtro = "AND (categoria_programatica.idcategoria_programatica >= '".$idcategoria_desde."' AND categoria_programatica.idcategoria_programatica <= '".$idcategoria_hasta."')";
		elseif ($idcategoria_desde != "") $filtro = "AND (categoria_programatica.idcategoria_programatica >= '".$idcategoria_desde."')";
		elseif ($idcategoria_hasta != "") $filtro = "AND categoria_programatica.idcategoria_programatica <= '".$idcategoria_hasta."')";
		//	---------------------------------------------
		consolidado_agrupado($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos);
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
			$formulado=number_format($field["Formulado"], 2, ',', '.');
			$modificado = number_format(($field['TotalAumento'] - $field['TotalDisminucion']), 2, ',', '.');
			$monto_actual = $field['Formulado'] + $field['TotalAumento'] - $field['TotalDisminucion'];
			$actual=number_format($monto_actual, 2, ',', '.');
			$compromiso=number_format($field["Compromiso"], 2, ',', '.');
			$precompromiso=number_format($field["PreCompromiso"], 2, ',', '.');
			$causado=number_format($field["Causado"], 2, ',', '.');
			$pagado=number_format($field["Pagado"], 2, ',', '.');
			$aumento=number_format($field["TotalAumento"], 2, ',', '.');
			$disminucion=number_format($field["TotalDisminucion"], 2, ',', '.');
			if ($chkrestar) $resta_disponible = $monto_actual-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"];
			else $resta_disponible = $monto_actual-$field["Compromiso"]-$field["ReservadoDisminuir"];
			$disponible=number_format($resta_disponible, 2, ',', '.');
			if ($field["Compromiso"]==0 or $field["Actual"]==0) $pcompromiso="0"; else $pcompromiso=(float) (($field["Compromiso"]*100)/$monto_actual); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
			if ($field["Causado"]==0 or $monto_actual==0) $pcausado="0"; else $pcausado=(float) (($field["Causado"]*100)/$monto_actual); $pcausado=number_format($pcausado, 2, ',', '.');
			if ($field["Pagado"]==0 or $monto_actual==0) $ppagado="0"; else $ppagado=(float) (($field["Pagado"]*100)/$monto_actual); $ppagado=number_format($ppagado, 2, ',', '.');
			if ($resta_disponible==0) $pdisponible="0"; else $pdisponible=(float) ((($resta_disponible)*100)/$monto_actual);
			$pdisponible=number_format($pdisponible, 2, ',', '.');

			if ($field["Tipo"]=="partida") {
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 6);

				$y=$pdf->GetY();
				$x=5;
				$nb=$pdf->NbLines(50, utf8_decode($field['NomPartida'])); $hf=3*$nb;
				$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $clasificador, 0, 1, 'C', 1); $x+=20;
				$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, utf8_decode($field['NomPartida']), 0, 'L', 1); $x+=50;
				if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $formulado, 0, 1, 'R', 1); $x+=23; }
				if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $aumento, 0, 1, 'R', 1); $x+=23; }
				if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disminucion, 0, 1, 'R', 1); $x+=23; }
				if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $modificado, 0, 1, 'R', 1); $x+=23; }
				if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $actual, 0, 1, 'R', 1); $x+=23; }
				if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $precompromiso, 0, 1, 'R', 1); $x+=23; }
				if ($campos[6]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $compromiso, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcompromiso.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[7]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $causado, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[8]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $pagado, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $ppagado.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[9]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disponible, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pdisponible.' %', 0, 1, 'R', 1); $x+=11;
				}
				$pdf->Ln(2);

				$total_formulado += $field["Formulado"];
				$total_aumento += $field["TotalAumento"];
				$total_disminucion += $field["TotalDisminucion"];
				$total_precompromiso += $field["PreCompromiso"];
				$total_compromiso += $field["Compromiso"];
				$total_causado += $field["Causado"];
				$total_pagado += $field["Pagado"];
				$total_disponible += $resta_disponible;
			}
			else if ($field["Tipo"]=="generica") {
				$h++;
				if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
				else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
				$pdf->SetFont('Arial', 'B', 6);

				$y=$pdf->GetY();
				$x=5;
				$nb=$pdf->NbLines(50, utf8_decode($field['NomPartida'])); $hf=3*$nb;
				$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $clasificador, 0, 1, 'C', 1); $x+=20;
				$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, utf8_decode($field['NomPartida']), 0, 'L', 1); $x+=50;
				if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $formulado, 0, 1, 'R', 1); $x+=23; }
				if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $aumento, 0, 1, 'R', 1); $x+=23; }
				if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disminucion, 0, 1, 'R', 1); $x+=23; }
				if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $modificado, 0, 1, 'R', 1); $x+=23; }
				if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $actual, 0, 1, 'R', 1); $x+=23; }
				if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $precompromiso, 0, 1, 'R', 1); $x+=23; }
				if ($campos[6]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $compromiso, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcompromiso.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[7]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $causado, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[8]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $pagado, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $ppagado.' %', 0, 1, 'R', 1); $x+=11;
				}
				if ($campos[9]) {
					$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disponible, 0, 1, 'R', 1); $x+=23;
					$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pdisponible.' %', 0, 1, 'R', 1); $x+=11;
				}
				$pdf->Ln(2);
			}
			else if ($field["Tipo"]=="especifica") {
				$h++;
				if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
				else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
				$pdf->SetFont('Arial', '', 6);
				if ($field['codordinal'] == "0000") {
					$y=$pdf->GetY();
					$x=5;
					$nb=$pdf->NbLines(50, utf8_decode($field['NomPartida'])); $hf=3*$nb;
					$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $clasificador, 0, 1, 'C', 1); $x+=20;
					$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, utf8_decode($field['NomPartida']), 0, 'L', 1); $x+=50;
					if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $formulado, 0, 1, 'R', 1); $x+=23; }
					if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $aumento, 0, 1, 'R', 1); $x+=23; }
					if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disminucion, 0, 1, 'R', 1); $x+=23; }
					if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $modificado, 0, 1, 'R', 1); $x+=23; }
					if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $actual, 0, 1, 'R', 1); $x+=23; }
					if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $precompromiso, 0, 1, 'R', 1); $x+=23; }
					if ($campos[6]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $compromiso, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcompromiso.' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[7]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $causado, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado.' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[8]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $pagado, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $ppagado.' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[9]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disponible, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pdisponible.' %', 0, 1, 'R', 1); $x+=11;
					}
					$pdf->Ln(2);
				} else {
					$y=$pdf->GetY();
					$x=5;
					$nb=$pdf->NbLines(50, utf8_decode($field['codordinal'].' '.$field['nomordinal'])); $hf=3*$nb;
					$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $clasificador, 0, 1, 'C', 1); $x+=20;
					$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, utf8_decode($field['codordinal'].' '.$field['nomordinal']), 0, 'L', 1); $x+=50;
					if ($campos[0]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $formulado, 0, 1, 'R', 1); $x+=23; }
					if ($campos[1]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $aumento, 0, 1, 'R', 1); $x+=23; }
					if ($campos[2]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disminucion, 0, 1, 'R', 1); $x+=23; }
					if ($campos[3]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $modificado, 0, 1, 'R', 1); $x+=23; }
					if ($campos[4]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $actual, 0, 1, 'R', 1); $x+=23; }
					if ($campos[5]) { $pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $precompromiso, 0, 1, 'R', 1); $x+=23; }
					if ($campos[6]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $compromiso, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcompromiso.' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[7]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $causado, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pcausado.' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[8]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $pagado, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $ppagado.' %', 0, 1, 'R', 1); $x+=11;
					}
					if ($campos[9]) {
						$pdf->SetXY($x, $y); $pdf->Cell(23, $hf, $disponible, 0, 1, 'R', 1); $x+=23;
						$pdf->SetXY($x, $y); $pdf->Cell(11, $hf, $pdisponible.' %', 0, 1, 'R', 1); $x+=11;
					}
					$pdf->Ln(2);
				}
			}
			$linea=$pdf->GetY();
			if ($linea>175) consolidado_agrupado($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $campos);
		}
		$total_modificado = $total_aumento - $total_disminucion;
		$total_ajustado = $total_formulado + $total_modificado;
		if ($total_compromiso == 0 || $total_ajustado == 0) $total_pcompromiso=0; else $total_pcompromiso=(float) (($total_compromiso*100)/$total_ajustado);
		if ($total_causado == 0 || $total_ajustado == 0) $total_pcausado=0; else $total_pcausado=(float) (($total_causado*100)/$total_ajustado);
		if ($total_pagado == 0 || $total_ajustado == 0) $total_ppagado=0; else $total_ppagado=(float) (($total_pagado*100)/$total_ajustado);
		if ($total_disponible == 0) $total_pdisponible=0; else $total_pdisponible=(float) ((($total_disponible)*100)/$total_ajustado);

		$pdf->SetDrawColor(0, 0, 0);
		$y=$pdf->GetY(); $y+=1;
		$pdf->Rect(5, $y, 343, 0.01);
		$pdf->SetY($y+2);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(20, 4);
		$pdf->Cell(50, 4);
		$pdf->Cell(23, 4, number_format($total_formulado, 2, ',', '.'), 0, 0, 'R', 1);
		$pdf->Cell(23, 4, number_format($total_aumento, 2, ',', '.'), 0, 0, 'R', 1);
		$pdf->Cell(23, 4, number_format($total_disminucion, 2, ',', '.'), 0, 0, 'R', 1);
		$pdf->Cell(23, 4, number_format($total_modificado, 2, ',', '.'), 0, 0, 'R', 1);
		$pdf->Cell(23, 4, number_format($total_ajustado, 2, ',', '.'), 0, 0, 'R', 1);
		$pdf->Cell(23, 4, number_format($total_precompromiso, 2, ',', '.'), 0, 0, 'R', 1);
		$pdf->Cell(23, 4, number_format($total_compromiso, 2, ',', '.'), 0, 0, 'R', 1);
		$pdf->Cell(11, 4, number_format($total_pcompromiso, 2, ',', '.'), 0, 0, 'R', 1);
		$pdf->Cell(23, 4, number_format($total_causado, 2, ',', '.'), 0, 0, 'R', 1);
		$pdf->Cell(11, 4, number_format($total_pcausado, 2, ',', '.'), 0, 0, 'R', 1);
		$pdf->Cell(23, 4, number_format($total_pagado, 2, ',', '.'), 0, 0, 'R', 1);
		$pdf->Cell(11, 4, number_format($total_ppagado, 2, ',', '.'), 0, 0, 'R', 1);
		$pdf->Cell(23, 4, number_format($total_disponible, 2, ',', '.'), 0, 0, 'R', 1);
		$pdf->Cell(11, 4, number_format($total_pdisponible, 2, ',', '.'), 0, 0, 'R', 1);
		break;


//	Mensual ONAPRE...
	case "mensual_onapre":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
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

		$filtro = "";
		if ($anio_fiscal != "") $filtro .= " (maestro_presupuesto.anio = '".$anio_fiscal."')";
		if ($financiamiento != "") $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."')";
		if ($tipo_presupuesto != "") $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."')";
		if ($idcategoria_programatica != "") $filtro .= " AND (maestro_presupuesto.idcategoria_programatica = '".$idcategoria_programatica."')";

		//---------------------------------------------
		$desde_inicial = $anio_fiscal.'-01-01';
		$mes_inicial = 1;
		if ($mes=='01'){
			$idesde = '01-01-'.$anio_fiscal; $ihasta = '31-01-'.$anio_fiscal;
			$desde = $anio_fiscal.'-01-01'; $hasta = $anio_fiscal.'-01-31'; $hasta_anterior = $anio_fiscal.'-01-31';
			$mes_final=1;
		}
		if ($mes=='02'){
		   $idesde = '01-02-'.$anio_fiscal; if ($anio_fiscal%4==0) $ihasta = '29-02-'.$anio_fiscal; else $ihasta = '28-02-'.$anio_fiscal;
		   $desde = $anio_fiscal.'-02-01'; if ($anio_fiscal%4==0) $hasta = $anio_fiscal.'-02-29'; else $hasta = $anio_fiscal.'-02-28';
			$hasta_anterior = $anio_fiscal.'-01-31';
			$mes_final=2; $mes_anterior=1;
		}
		if ($mes=='03'){
			$idesde = '01-03-'.$anio_fiscal; $ihasta = '31-03-'.$anio_fiscal;
			$desde = $anio_fiscal.'-03-01'; $hasta = $anio_fiscal.'-03-31';
				if ($anio_fiscal%4==0) $hasta_anterior = $anio_fiscal.'-02-29'; else $hasta_anterior = $anio_fiscal.'-02-28';
			$mes_final=3; $mes_anterior=2;
		}
		if ($mes=='04'){
			$idesde = '01-04-'.$anio_fiscal; $ihasta = '30-04-'.$anio_fiscal;
			$desde = $anio_fiscal.'-04-01'; $hasta = $anio_fiscal.'-04-30'; $hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final=4; $mes_anterior=3;
		}
		if ($mes=='05'){
			$idesde = '01-05-'.$anio_fiscal; $ihasta = '31-05-'.$anio_fiscal;
			$desde = $anio_fiscal.'-05-01'; $hasta = $anio_fiscal.'-05-31'; $hasta_anterior = $anio_fiscal.'-04-30';
			$mes_final=5; $mes_anterior=4;
		}
		if ($mes=='06'){
			$idesde = '01-06-'.$anio_fiscal; $ihasta = '30-06-'.$anio_fiscal;
			$desde = $anio_fiscal.'-06-01'; $hasta = $anio_fiscal.'-06-30'; $hasta_anterior = $anio_fiscal.'-05-31';
			$mes_final=6; $mes_anterior=5;
		}
		if ($mes=='07'){
			$idesde = '01-07-'.$anio_fiscal; $ihasta = '31-07-'.$anio_fiscal;
			$desde = $anio_fiscal.'-07-01'; $hasta = $anio_fiscal.'-07-31'; $hasta_anterior = $anio_fiscal.'-06-30';
			$mes_final=7; $mes_anterior=6;
		}
		if ($mes=='08'){
			$idesde = '01-08-'.$anio_fiscal; $ihasta = '31-08-'.$anio_fiscal;
			$desde = $anio_fiscal.'-08-01'; $hasta = $anio_fiscal.'-08-31'; $hasta_anterior = $anio_fiscal.'-07-31';
			$mes_final=8; $mes_anterior=7;
		}
		if ($mes=='09'){
			$idesde = '01-09-'.$anio_fiscal; $ihasta = '30-09-'.$anio_fiscal;
			$desde = $anio_fiscal.'-09-01'; $hasta = $anio_fiscal.'-09-30'; $hasta_anterior = $anio_fiscal.'-08-31';
			$mes_final=9; $mes_anterior=8;
		}
		if ($mes=='10'){
			$idesde = '01-10-'.$anio_fiscal; $ihasta = '31-10-'.$anio_fiscal;
			$desde = $anio_fiscal.'-10-01'; $hasta = $anio_fiscal.'-10-31'; $hasta_anterior = $anio_fiscal.'-09-30';
			$mes_final=10; $mes_anterior=9;
		}
		if ($mes=='11'){
			$idesde = '01-11-'.$anio_fiscal; $ihasta = '30-11-'.$anio_fiscal;
			$desde = $anio_fiscal.'-11-01'; $hasta = $anio_fiscal.'-11-30'; $hasta_anterior = $anio_fiscal.'-10-31';
			$mes_final=11; $mes_anterior=10;
		}
		if ($mes=='12'){
			$idesde = '01-12-'.$anio_fiscal; $ihasta = '31-12-'.$anio_fiscal;
			$desde = $anio_fiscal.'-12-01'; $hasta = $anio_fiscal.'-12-31'; $hasta_anterior = $anio_fiscal.'-11-30';
			$mes_final=12; $mes_anterior=11;
		}

		//---------------------------------------------
		mensual_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $mes);
		//---------------------------------------------
		//	CONSULTA SI ES ENERO

		if ($mes == 01){
			//LLAMA A LA FUNCION PARA GENERAR SENTENCIA SQL DE UN PERIODO
			$sql = busca_rango_tiempo($desde,$hasta,$mes_inicial,$mes_final,$filtro,$anio_fiscal);

			$sql_suma = $sql;
			$par = 0; $gen = 0; $esp = 0; $sub = 0;
			$query_suma = mysql_query($sql_suma) or die ($sql_suma.mysql_error());
			while ($field = mysql_fetch_array($query_suma)) {
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
				if ($field['MRendicionAumento1']>0){
					$aumento_rendicion = $field['MRendicionAumento1'] - $field['MCredito1'] - $field['MReceptora1'] - $field['MRectificacion1'] ;
				}
				if ($field['MRendicionDisminucion1']>0){
					$disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['MDisminucion1'] - $field['MCedentes1'] - $field['MRectificadora1'];
				}
				if ($field['Tipo'] == "partida") $par++;
				elseif ($field['Tipo'] == "generica") $gen++;
				elseif ($field['Tipo'] == "especifica") {
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
					$comprometido_partidaI[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

					$causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];

					/*if($field['PagadoI']>$field['CausaI']){
						$pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];
					}else{*/
						$pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_partidaI[$par] +=  $field['PagadoI'] + $field['MRendicionPagados1'];
					//}

				}
				elseif ($field['Tipo'] == "subespecifica") {
					$sub ++;
					// DETERMINO SI ES SUB ESPECIFICA PARA SUMARLA A LA ESPECIFICA
					$aumentado_subespecificaI[$sub] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
					$aumentado_especificaI[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
					$aumentado_genericaI[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
					$aumentado_partidaI[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

					$disminuido_subespecificaI[$sub] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
					$disminuido_especificaI[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
					$disminuido_genericaI[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
					$disminuido_partidaI[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

					$comprometido_subespecificaI[$sub] += $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];
					$comprometido_especificaI[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];
					$comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];
					$comprometido_partidaI[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];

					$causa_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];

					/*if($field['PagadoI']>$field['CausaI']){
						$pagado_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];
					}else{*/
						$pagado_subespecificaI[$sub] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_partidaI[$par] +=  $field['PagadoI'] + $field['MRendicionPagados1'];
					//}
				}
			}
		}else{

	      	//LLAMA A LA FUNCION PARA GENERAR SENTENCIA SQL DE UN PERIODO
			$sql = busca_rango_tiempo($desde_inicial,$hasta_anterior,$mes_inicial,$mes_anterior,$filtro,$anio_fiscal);

			//SI ES DE FEBRERO EN ADELANTE PRIMERO OBTENGO EL ACUMULADO HASTA EL MES ANTERIOR


			$sql_suma = $sql;
			$par = 0; $gen = 0;	$esp = 0; $sub = 0;
			$query_suma = mysql_query($sql_suma) or die ($sql_suma.mysql_error());
			while ($field = mysql_fetch_array($query_suma)) {
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
				if ($field['MRendicionAumento1']>0){
					$aumento_rendicion = $field['MRendicionAumento1'] - $field['MCredito1'] - $field['MReceptora1'] - $field['MRectificacion1'] ;
				}
				if ($field['MRendicionDisminucion1']>0){
					$disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['MDisminucion1'] - $field['MCedentes1'] - $field['MRectificadora1'];
				}
				if ($field['Tipo'] == "partida") $par++;
				elseif ($field['Tipo'] == "generica") $gen++;
				elseif ($field['Tipo'] == "especifica") {
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
					$comprometido_partidaI_anterior[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

					$causa_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_partidaI_anterior[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];

					/*if($field['PagadoI']>$field['CausaI']){
						$pagado_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_partidaI_anterior[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];
					}else if($field['PagadoI']>0){*/
						$pagado_especificaI_anterior[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_genericaI_anterior[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_partidaI_anterior[$par] +=  $field['PagadoI'] + $field['MRendicionPagados1'];
					//}

				}
				elseif ($field['Tipo'] == "subespecifica") {
					$sub ++;
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
					$comprometido_partidaI_anterior[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

					$causa_subespecificaI_anterior[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_partidaI_anterior[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];

					/*if($field['PagadoI']>$field['CausaI']){
						$pagado_subespecificaI_anterior[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_partidaI_anterior[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];
					}else if($field['PagadoI']>0){*/
						$pagado_subespecificaI_anterior[$sub] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_especificaI_anterior[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_genericaI_anterior[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_partidaI_anterior[$par] +=  $field['PagadoI'] + $field['MRendicionPagados1'];
					//}
				}
			}

			//OBTENGO LOS TOTALES DEL MES SELECCIONADO
			//LLAMA A LA FUNCION PARA GENERAR SENTENCIA SQL DE UN PERIODO
			$sql = busca_rango_tiempo($desde,$hasta,$mes_anterior+1,$mes_final,$filtro,$anio_fiscal);


			$sql_suma = $sql;
			$par = 0; $gen = 0; $esp = 0; $sub = 0;
			$query_suma = mysql_query($sql_suma) or die ($sql_suma.mysql_error());
			while ($field = mysql_fetch_array($query_suma)) {
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
				if ($field['MRendicionAumento1']>0){
					$aumento_rendicion = $field['MRendicionAumento1'] - $field['MCredito1'] - $field['MReceptora1'] - $field['MRectificacion1'] ;
				}
				if ($field['MRendicionDisminucion1']>0){
					$disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['MDisminucion1'] - $field['MCedentes1'] - $field['MRectificadora1'];
				}
				if ($field['Tipo'] == "partida") $par++;
				elseif ($field['Tipo'] == "generica") $gen++;
				elseif ($field['Tipo'] == "especifica") {
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
					$comprometido_partidaI[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

					$causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];

					/*if($field['PagadoI']>$field['CausaI']){
						$pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionCausado1'];
						$pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionCausado1'];
						$pagado_partidaI[$par] +=  $field['PagadoI'] + $field['MRendicionCausado1'];
					}else{*/
						$pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionPagados1'];
						$pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionPagados1'];
						$pagado_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionPagados1'];
					//}

				}
				elseif ($field['Tipo'] == "subespecifica") {
					$sub ++;
					// DETERMINO SI ES SUB ESPECIFICA PARA SUMARLA A LA ESPECIFICA
					$aumentado_subespecificaI[$sub] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
					$aumentado_especificaI[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
					$aumentado_genericaI[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
					$aumentado_partidaI[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

					$disminuido_subespecificaI[$sub] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
					$disminuido_especificaI[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
					$disminuido_genericaI[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
					$disminuido_partidaI[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

					$comprometido_subespecificaI[$sub] += $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];
					$comprometido_especificaI[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];
					$comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];
					$comprometido_partidaI[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];

					$causa_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];

					/*if($field['PagadoI']>$field['CausaI']){
						$pagado_subespecificaI[$sub] += $field['PagadoI'] + $field['MRendicionCausado1'];
						$pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionCausado1'];
						$pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionCausado1'];
						$pagado_partidaI[$par] +=  $field['PagadoI'] + $field['MRendicionCausado1'];
					}else if($field['PagadoI']>0){*/
						$pagado_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionPagados1'];
						$pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionPagados1'];
						$pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionPagados1'];
						$pagado_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionPagados1'];
					//}
				}
			}
		}

		$par = 0; $gen = 0; $esp = 0; $sub = 0;
		$sum_formulado=0; $sum_modificacion=0; $sum_actual=0; $sum_compromiso=0; $sum_causado=0; $sum_pagado=0;
		$total_formulado=0; $total_modificacion=0; $total_actual=0; $total_compromiso=0; $total_causado=0; $total_pagado=0;

		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {

			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];

			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"]!=$IdCategoria) {
				if($sum_formulado == 0 and $sum_presupuestaria == 0){
					$IdCategoria=$field["IdCategoria"];
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(205, 5, utf8_decode($field["CodCategoria"]." - ".$field["Unidad"]), 1, 1, 'L', 1);

				}else{
					//IMPRIMO LOS TOTALES DE LA CATEGORIA ANTERIOR
					$y=$pdf->GetY();
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
					$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
					$pdf->SetXY($x, $y+5);
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);
					if ($mes == "01") {
						$pdf->Row(array('', '', number_format($sum_formulado, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actual-$sum_pagado), 2, ',', '.')));
					}else{
						$pdf->Row(array('', '', number_format($sum_presupuestaria, 2, ',', '.'), number_format($sum_financiera, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actualf-$sum_pagado), 2, ',', '.')));
					}
					$sum_formulado = 0; $sum_aumento = 0; $sum_disminucion = 0; $sum_modificado = 0; $sum_actual = 0;
					$sum_compromiso = 0; $sum_causado = 0; $sum_pagado = 0; $sum_disponible = 0; $sum_modificacion = 0;
					$sum_presupuestaria = 0; $sum_financiera = 0; $sum_actualf = 0;

					//IMPRIMO LA CABECERA DE LA NUEVA CATEGORIA
					mensual_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $mes);
					$pdf->SetY(52);
					$IdCategoria=$field["IdCategoria"];
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(205, 5, utf8_decode($field["CodCategoria"]." - ".$field["Unidad"]), 1, 1, 'L', 1);
				}
			}

			if ($field["Tipo"]=="partida") {
				$pdf->SetFont('Arial', 'B', 7);

				$par++;
				if ($mes == "01") {
					$sum_formulado+=$field["Formulado"];
					$total_formulado+=$field["Formulado"];

					$modificado = $aumentado_partidaI[$par] - $disminuido_partidaI[$par];

					$sum_modificacion+=$modificado;
					$total_modificacion+=$modificado;

					$actual0 = $field["Formulado"] + $modificado;
					$sum_actual+=$actual0;
					$total_actual+=$actual0;

					$sum_compromiso+=$comprometido_partidaI[$par];
					$total_compromiso+=$comprometido_partidaI[$par];

					$sum_causado+=$causa_partidaI[$par];
					$total_causado+=$causa_partidaI[$par];

					$sum_pagado+=$pagado_partidaI[$par];
					$total_pagado+=$pagado_partidaI[$par];

					$sum_disponible+=($actual0-$comprometido);

					$comprometidoI = $comprometido_partidaI[$par];

					$causaI = $causa_partidaI[$par];

					$pagadoI = $pagado_partidaI[$par];
				}else{
					$sum_formulado+=$field["Formulado"];
                    $total_formulado+=$field["Formulado"];
					$aumentado_partidaI_anterior[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'];
					$disminuido_partidaI_anterior[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'];
					$comprometido_partidaI_anterior[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI'];
					$causa_partidaI_anterior[$par] +=  $field['CausaI'];
					$pagado_partidaI_anterior[$par] +=  $field['PagadoI'];

					$presupuestaria_anterior = ($field["Formulado"] +$aumentado_partidaI_anterior[$par] - $disminuido_partidaI_anterior[$par]) - $comprometido_partidaI_anterior[$par];

					$financiera_anterior = ($field["Formulado"] +$aumentado_partidaI_anterior[$par] - $disminuido_partidaI_anterior[$par]) - $pagado_partidaI_anterior[$par];

					$sum_presupuestaria+=$presupuestaria_anterior;
					$total_presupuestaria+=$presupuestaria_anterior;

					$sum_financiera+=$financiera_anterior;
					$total_financiera+=$financiera_anterior;

					$modificado = $aumentado_partidaI[$par] - $disminuido_partidaI[$par];

					$sum_modificacion+=$modificado;
					$total_modificacion+=$modificado;

					$actual0 = $presupuestaria_anterior + $modificado;
					$actualf = $financiera_anterior + $modificado;

					$sum_actual+=$actual0;
					$total_actual+=$actual0;

					$sum_actualf+=$actualf;
					$total_actualf+=$actualf;

					$sum_compromiso+=$comprometido_partidaI[$par];
					$total_compromiso+=$comprometido_partidaI[$par];

					$sum_causado+=$causa_partidaI[$par];
					$total_causado+=$causa_partidaI[$par];

					$sum_pagado+=$pagado_partidaI[$par];
					$total_pagado+=$pagado_partidaI[$par];

					$sum_disponible+=($actual0-$comprometido);

					$comprometidoI = $comprometido_partidaI[$par];

					$causaI = $causa_partidaI[$par];

					$pagadoI = $pagado_partidaI[$par];

				}

			}
			else if ($field["Tipo"]=="generica") {
				$pdf->SetFont('Arial', 'B', 7);

				$gen++;
				if ($mes == "01") {
					$modificado = $aumentado_genericaI[$gen] - $disminuido_genericaI[$gen];

					$actual0 = $field["Formulado"] + $modificado;

					$comprometidoI = $comprometido_genericaI[$gen];

					$causaI = $causa_genericaI[$gen];

					$pagadoI = $pagado_genericaI[$gen];

				}else{

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

					$presupuestaria_anterior = ($field["Formulado"] +$aumentado_genericaI_anterior[$gen] - $disminuido_genericaI_anterior[$gen]) - $comprometido_genericaI_anterior[$gen];

					$financiera_anterior = ($field["Formulado"] +$aumentado_genericaI_anterior[$gen] - $disminuido_genericaI_anterior[$gen]) - $pagado_genericaI_anterior[$gen];

					$actual0 = $presupuestaria_anterior + $modificado;

				}

			}
			else if ($field["Tipo"]=="especifica") {
				$pdf->SetFont('Arial', '', 7);

				$esp++;
				if ($mes == "01") {

					$modificado    = $aumentado_especificaI[$esp] - $disminuido_especificaI[$esp];
					$actual0       = $field["Formulado"] + $modificado;
					$comprometidoI = $comprometido_especificaI[$esp];
					$causaI        = $causa_especificaI[$esp];
					$pagadoI       = $pagado_especificaI[$esp];

				}else{

					$presupuestaria_anterior = ($field["Formulado"] +$aumentado_especificaI_anterior[$esp]
												- $disminuido_especificaI_anterior[$esp]) - $comprometido_especificaI_anterior[$esp];
					$financiera_anterior     = ($field["Formulado"] +$aumentado_especificaI_anterior[$esp]
												- $disminuido_especificaI_anterior[$esp]) - $pagado_especificaI_anterior[$esp];
					$modificado              = $aumentado_especificaI[$esp] - $disminuido_especificaI[$esp];
					$comprometidoI           = $comprometido_especificaI[$esp];
					$causaI                  = $causa_especificaI[$esp];
					$pagadoI                 = $pagado_especificaI[$esp];
					$actual0                 = $presupuestaria_anterior + $modificado;

				}
			}
			else if ($field["Tipo"]=="subespecifica") {
				$pdf->SetFont('Arial', '', 7);

				$sub++;
				if ($mes == "01") {

					$modificado    = $aumentado_subespecificaI[$sub] - $disminuido_subespecificaI[$sub];
					$actual0       = $field["Formulado"] + $modificado;
					$comprometidoI = $comprometido_subespecificaI[$sub];
					$causaI        = $causa_subespecificaI[$sub];
					$pagadoI       = $pagado_subespecificaI[$sub];

				}else{


					$presupuestaria_anterior = ($field["Formulado"] +$aumentado_subespecificaI_anterior[$sub] - $disminuido_subespecificaI_anterior[$sub]) - $comprometido_subespecificaI_anterior[$sub];
					$financiera_anterior     = ($field["Formulado"] +$aumentado_subespecificaI_anterior[$sub] - $disminuido_subespecificaI_anterior[$sub]) - $pagado_subespecificaI_anterior[$sub];
					$comprometidoI           = $comprometido_subespecificaI[$sub];
					$causaI                  = $causa_subespecificaI[$sub];
					$pagadoI                 = $pagado_subespecificaI[$sub];
					$modificado              = $aumentado_subespecificaI[$sub] - $disminuido_subespecificaI[$sub];
					$actual0                 = $presupuestaria_anterior + $modificado;

				}
			}

			$modificacion        =number_format($modificado, 2, ',', '.');
			$actual              =number_format($actual0, 2, ',', '.');
			$total_comprometidoI = number_format($comprometidoI, 2, ',', '.');
			$total_causaI        = number_format($causaI, 2, ',', '.');
			$total_pagadoI       = number_format($pagadoI, 2, ',', '.');


			if ($field['codordinal'] != "0000") $descripcion = $field['codordinal'].' '.$field['nomordinal'];
			else $descripcion= $field['NomPartida'];

			if ($mes == "01") {
				$compromiso = $comprometidoI;
				$causado = $causaI;
				$compromiso = number_format($compromiso, 2, ',', '.');
				$causado = number_format($causado, 2, ',', '.');
				$formulado = number_format($field["Formulado"], 2, ',', '.');
				$total_disponible = $disponible_compromiso;

				$pdf->Row(array($clasificador, utf8_decode($descripcion), $formulado, $modificacion, $actual, $total_comprometidoI, $total_causaI, number_format(($actual0-$comprometidoI), 2, ',', '.'), $total_pagadoI, number_format(($actual0-$pagadoI), 2, ',', '.')));

				$linea=$pdf->GetY(); if ($linea>195) { mensual_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $mes); $pdf->SetY(57); }

			} else {

				$compromiso = $comprometidoI;
				$causado = $causaI;
				$compromiso = number_format($compromiso, 2, ',', '.');
				$causado = number_format($causado, 2, ',', '.');

				$total_disponible = $disponible_compromiso;
				if (($financiera_anterior + $modificado)-$pagadoI<=0){
					$disponible_financiero = number_format(0, 2, ',', '.');
				}else{
					$disponible_financiero = number_format((($financiera_anterior + $modificado)-$pagadoI), 2, ',', '.');
				}

				$pdf->Row(array($clasificador, utf8_decode($descripcion), number_format($presupuestaria_anterior, 2, ',', '.'), number_format($financiera_anterior, 2, ',', '.'), $modificacion, $actual, $total_comprometidoI, $total_causaI, number_format(($actual0-$comprometidoI), 2, ',', '.'), $total_pagadoI, $disponible_financiero));

				$linea=$pdf->GetY(); if ($linea>195) { mensual_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $mes); $pdf->SetY(57); }
			}

		}

		//	IMPRIMO LOS TOTALES DE LA CATEGORIA

		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=348; $pdf->Rect($x, $y+2, $w, $h);
		$pdf->SetXY($x, $y+5);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		if ($mes == "01") {
			$pdf->Row(array('', '', number_format($sum_formulado, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actual-$sum_pagado), 2, ',', '.')));
		}else{
			if ($sum_actualf-$sum_pagado<=0){
				$total_disponible_financiero = number_format(0, 2, ',', '.');
			}else{
				$total_disponible_financiero = number_format(($sum_actualf-$sum_pagado), 2, ',', '.');
			}

			$pdf->Row(array('', '', number_format($sum_presupuestaria, 2, ',', '.'), number_format($sum_financiera, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), $total_disponible_financiero));
		}

		//SI EXISTEN TOTALES LOS IMPRIMOS
		if($sum_formulado < $total_formulado){
			$y=$pdf->GetY();
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
			$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
			$pdf->SetXY($x, $y+5);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			if ($mes == "01") {
				$pdf->Row(array('', '', number_format($total_formulado, 2, ',', '.'), number_format($total_modificacion, 2, ',', '.'), number_format($total_actual, 2, ',', '.'), number_format($total_compromiso, 2, ',', '.'), number_format($total_causado, 2, ',', '.'), number_format(($total_actual-$total_compromiso), 2, ',', '.'), number_format($total_pagado, 2, ',', '.'), number_format(($total_actual-$total_pagado), 2, ',', '.')));
			}else{
				if ($total_actualf-$total_pagado<=0){
					$total_disponible_financiero = number_format(0, 2, ',', '.');
				}else{
					$total_disponible_financiero = number_format(($total_actualf-$total_pagado), 2, ',', '.');
				}
				$pdf->Row(array('', '', number_format($total_presupuestaria, 2, ',', '.'), number_format($total_financiera, 2, ',', '.'), number_format($total_modificacion, 2, ',', '.'), number_format($total_actual, 2, ',', '.'), number_format($total_compromiso, 2, ',', '.'), number_format($total_causado, 2, ',', '.'), number_format(($total_actual-$total_compromiso), 2, ',', '.'), number_format($total_pagado, 2, ',', '.'), $total_disponible_financiero));
			}

		}

	break;
//	FIN REPORTE MENSUAL ONAPRE

//	TRIMESTRAL ONAPRE...
	case "trimestral_onapre":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
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

		$filtro = "";
		if ($anio_fiscal != "") $filtro .= " (maestro_presupuesto.anio = '".$anio_fiscal."')";
		if ($financiamiento != "") $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."')";
		if ($tipo_presupuesto != "") $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."')";
		if ($idcategoria_programatica != "") $filtro .= " AND (maestro_presupuesto.idcategoria_programatica = '".$idcategoria_programatica."')";

		//---------------------------------------------
		$mes_inicial = 1; $desde_inicial = $anio_fiscal.'-01-01';
		if ($mes=='01'){
			$idesde = '01-01-'.$anio_fiscal; $ihasta = '31-03-'.$anio_fiscal;
			$desde = $anio_fiscal.'-01-01'; $hasta = $anio_fiscal.'-03-31'; $hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final = 3;
			$trimestre_m = 'TRIMESTRE I';
		}
		if ($mes=='02'){
		   $idesde = '01-04-'.$anio_fiscal; $ihasta = '30-06-'.$anio_fiscal;
		   $desde = $anio_fiscal.'-04-01'; $hasta = $anio_fiscal.'-06-30';
			$hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final = 6; $mes_anterior = 3;
			$trimestre_m = 'TRIMESTRE II';

		}
		if ($mes=='03'){
			$idesde = '01-07-'.$anio_fiscal; $ihasta = '30-09-'.$anio_fiscal;
			$desde = $anio_fiscal.'-07-01'; $hasta = $anio_fiscal.'-09-30';
			$hasta_anterior = $anio_fiscal.'-06-30';
			$mes_final = 9; $mes_anterior = 6;
			$trimestre_m = 'TRIMESTRE III';
		}
		if ($mes=='04'){
			$idesde = '01-10-'.$anio_fiscal; $ihasta = '31-12-'.$anio_fiscal;
			$desde = $anio_fiscal.'-10-01'; $hasta = $anio_fiscal.'-12-31';
			$hasta_anterior = $anio_fiscal.'-09-30';
			$mes_final = 12; $mes_anterior = 9;
			$trimestre_m = 'TRIMESTRE IV';
		}

		//---------------------------------------------
		trimestral_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $mes);
		//---------------------------------------------
		//	CONSULTA SI ES PRIMER TRIMESTRE

		if ($mes == 01){

			//LLAMA A LA FUNCION PARA GENERAR SENTENCIA SQL DE UN PERIODO
			$sql = busca_rango_tiempo($desde,$hasta,$mes_inicial,$mes_final,$filtro,$anio_fiscal);

			$sql_suma = $sql;
			$par = 0; $gen = 0; $esp = 0; $sub = 0;
			$query_suma = mysql_query($sql_suma) or die ($sql_suma.mysql_error());
			while ($field = mysql_fetch_array($query_suma)) {
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
				if ($field['MRendicionAumento1']>0){
					$aumento_rendicion = $field['MRendicionAumento1'] - $field['MCredito1'] - $field['MReceptora1'] - $field['MRectificacion1'] ;
				}
				if ($field['MRendicionDisminucion1']>0){
					$disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['MDisminucion1'] - $field['MCedentes1'] - $field['MRectificadora1'];
				}
				if ($field['Tipo'] == "partida") $par++;
				elseif ($field['Tipo'] == "generica") $gen++;
				elseif ($field['Tipo'] == "especifica") {
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
					$comprometido_partidaI[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

					$causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];

					if($field['PagadoI']>$field['CausaI']){
						$pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];
					}else{
						$pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_partidaI[$par] +=  $field['PagadoI'] + $field['MRendicionPagados1'];
					}

				}
				elseif ($field['Tipo'] == "subespecifica") {
					$sub ++;
					// DETERMINO SI ES SUB ESPECIFICA PARA SUMARLA A LA ESPECIFICA
					$aumentado_subespecificaI[$sub] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
					$aumentado_especificaI[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
					$aumentado_genericaI[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
					$aumentado_partidaI[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

					$disminuido_subespecificaI[$sub] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
					$disminuido_especificaI[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
					$disminuido_genericaI[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
					$disminuido_partidaI[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

					$comprometido_subespecificaI[$sub] += $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];
					$comprometido_especificaI[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];
					$comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];
					$comprometido_partidaI[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];

					$causa_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];

					if($field['PagadoI']>$field['CausaI']){
						$pagado_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];
					}else{
						$pagado_subespecificaI[$sub] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_partidaI[$par] +=  $field['PagadoI'] + $field['MRendicionPagados1'];
					}
				}
			}
		}
	    else{
			//LLAMA A LA FUNCION PARA GENERAR SENTENCIA SQL DE UN PERIODO
			$sql = busca_rango_tiempo($desde_inicial,$hasta_anterior,$mes_inicial,$mes_anterior,$filtro,$anio_fiscal);


			$sql_suma = $sql;
			$par = 0; $gen = 0;	$esp = 0; $sub = 0;
			$query_suma = mysql_query($sql_suma) or die ($sql_suma.mysql_error());
			while ($field = mysql_fetch_array($query_suma)) {
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
				if ($field['MRendicionAumento1']>0){
					$aumento_rendicion = $field['MRendicionAumento1'] - $field['MCredito1'] - $field['MReceptora1'] - $field['MRectificacion1'] ;
				}
				if ($field['MRendicionDisminucion1']>0){
					$disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['MDisminucion1'] - $field['MCedentes1'] - $field['MRectificadora1'];
				}
				if ($field['Tipo'] == "partida") $par++;
				elseif ($field['Tipo'] == "generica") $gen++;
				elseif ($field['Tipo'] == "especifica") {
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
					$comprometido_partidaI_anterior[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

					$causa_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_partidaI_anterior[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];

					if($field['PagadoI']>$field['CausaI']){
						$pagado_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_partidaI_anterior[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];
					}else{
						$pagado_especificaI_anterior[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_genericaI_anterior[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_partidaI_anterior[$par] +=  $field['PagadoI'] + $field['MRendicionPagados1'];
					}

				}
				elseif ($field['Tipo'] == "subespecifica") {
					$sub ++;
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
					$comprometido_partidaI_anterior[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

					$causa_subespecificaI_anterior[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_partidaI_anterior[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];

					if($field['PagadoI']>$field['CausaI']){
						$pagado_subespecificaI_anterior[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_especificaI_anterior[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_genericaI_anterior[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
						$pagado_partidaI_anterior[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];
					}else{
						$pagado_subespecificaI_anterior[$sub] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_especificaI_anterior[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_genericaI_anterior[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
						$pagado_partidaI_anterior[$par] +=  $field['PagadoI'] + $field['MRendicionPagados1'];
					}
				}
			}


			//OBTENGO LOS TOTALES DEL TRIMESTRE SELECCIONADO
			//LLAMA A LA FUNCION PARA GENERAR SENTENCIA SQL DE UN PERIODO
			$sql = busca_rango_tiempo($desde,$hasta,$mes_anterior+1,$mes_final,$filtro,$anio_fiscal);


			$sql_suma = $sql;
			$par = 0; $gen = 0; $esp = 0; $sub = 0;
			$query_suma = mysql_query($sql_suma) or die ($sql_suma.mysql_error());
			while ($field = mysql_fetch_array($query_suma)) {
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
				if ($field['MRendicionAumento1']>0){
					$aumento_rendicion = $field['MRendicionAumento1'] - $field['MCredito1'] - $field['MReceptora1'] - $field['MRectificacion1'] ;
				}
				if ($field['MRendicionDisminucion1']>0){
					$disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['MDisminucion1'] - $field['MCedentes1'] - $field['MRectificadora1'];
				}
				if ($field['Tipo'] == "partida") $par++;
				elseif ($field['Tipo'] == "generica") $gen++;
				elseif ($field['Tipo'] == "especifica") {
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
					$comprometido_partidaI[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

					$causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];

					if($field['PagadoI']>$field['CausaI']){
						$pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionCausado1'];
						$pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionCausado1'];
						$pagado_partidaI[$par] +=  $field['PagadoI'] + $field['MRendicionCausado1'];
					}else{
						$pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionPagados1'];
						$pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionPagados1'];
						$pagado_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionPagados1'];
					}

				}
				elseif ($field['Tipo'] == "subespecifica") {
					$sub ++;
					// DETERMINO SI ES SUB ESPECIFICA PARA SUMARLA A LA ESPECIFICA
					$aumentado_subespecificaI[$sub] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
					$aumentado_especificaI[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
					$aumentado_genericaI[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
					$aumentado_partidaI[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

					$disminuido_subespecificaI[$sub] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
					$disminuido_especificaI[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
					$disminuido_genericaI[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
					$disminuido_partidaI[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

					$comprometido_subespecificaI[$sub] += $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];
					$comprometido_especificaI[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];
					$comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];
					$comprometido_partidaI[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];

					$causa_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
					$causa_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];

					if($field['PagadoI']>$field['CausaI']){
						$pagado_subespecificaI[$sub] += $field['PagadoI'] + $field['MRendicionCausado1'];
						$pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionCausado1'];
						$pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionCausado1'];
						$pagado_partidaI[$par] +=  $field['PagadoI'] + $field['MRendicionCausado1'];
					}else{
						$pagado_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionPagados1'];
						$pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionPagados1'];
						$pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionPagados1'];
						$pagado_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionPagados1'];
					}
				}
			}

		}

		$par = 0; $gen = 0; $esp = 0; $sub = 0;
		$sum_formulado=0; $sum_modificacion=0; $sum_actual=0; $sum_compromiso=0; $sum_causado=0; $sum_pagado=0;
		$total_formulado=0; $total_modificacion=0; $total_actual=0; $total_compromiso=0; $total_causado=0; $total_pagado=0;

		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {

			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];


			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"]!=$IdCategoria) {
				if($sum_formulado == 0 and $sum_presupuestaria == 0){
					$IdCategoria=$field["IdCategoria"];
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(205, 5, utf8_decode($field["CodCategoria"]." - ".$field["Unidad"]), 1, 1, 'L', 1);

				}else{
					//IMPRIMO LOS TOTALES DE LA CATEGORIA ANTERIOR
					$y=$pdf->GetY();
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
					$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
					$pdf->SetXY($x, $y+5);
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);
					if ($mes == "01") {
						$pdf->Row(array('', '', number_format($sum_formulado, 2, ',', '.'),
												number_format($sum_modificacion, 2, ',', '.'),
												number_format($sum_actual, 2, ',', '.'),
												number_format($sum_compromiso, 2, ',', '.'),
												number_format($sum_causado, 2, ',', '.'),
												number_format(($sum_actual-$sum_compromiso), 2, ',', '.'),
												number_format($sum_pagado, 2, ',', '.'),
												number_format(($sum_actual-$sum_pagado), 2, ',', '.')));
					}else{
						$pdf->Row(array('', '', number_format($sum_presupuestaria, 2, ',', '.'),
												number_format($sum_financiera, 2, ',', '.'),
												number_format($sum_modificacion, 2, ',', '.'),
												number_format($sum_actual, 2, ',', '.'),
												number_format($sum_compromiso, 2, ',', '.'),
												number_format($sum_causado, 2, ',', '.'),
												number_format(($sum_actual-$sum_compromiso), 2, ',', '.'),
												number_format($sum_pagado, 2, ',', '.'),
												number_format(($sum_actualf-$sum_pagado), 2, ',', '.')));
					}
					$sum_formulado = 0; $sum_aumento = 0; $sum_disminucion = 0; $sum_modificado = 0; $sum_actual = 0;
					$sum_compromiso = 0; $sum_causado = 0; $sum_pagado = 0; $sum_disponible = 0; $sum_modificacion = 0;
					$sum_presupuestaria = 0; $sum_financiera = 0; $sum_actualf = 0;

					//IMPRIMO LA CABECERA DE LA NUEVA CATEGORIA
					trimestral_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $mes);
					$pdf->Ln(2);
					$IdCategoria=$field["IdCategoria"];
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(205, 5, utf8_decode($field["CodCategoria"]." - ".$field["Unidad"]), 1, 1, 'L', 1);

				}
			}

			if ($field["Tipo"]=="partida") {
				$pdf->SetFont('Arial', 'B', 7);

				$par++;
				if ($mes == "01") {
					$sum_formulado+=$field["Formulado"];
					$total_formulado+=$field["Formulado"];

					$modificado = $aumentado_partidaI[$par] - $disminuido_partidaI[$par];

					$sum_modificacion+=$modificado;
					$total_modificacion+=$modificado;

					$actual0 = $field["Formulado"] + $modificado;
					$sum_actual+=$actual0;
					$total_actual+=$actual0;

					$sum_compromiso+=$comprometido_partidaI[$par];
					$total_compromiso+=$comprometido_partidaI[$par];

					$sum_causado+=$causa_partidaI[$par];
					$total_causado+=$causa_partidaI[$par];

					$sum_pagado+=$pagado_partidaI[$par];
					$total_pagado+=$pagado_partidaI[$par];


					$sum_disponible+=($actual0-$comprometido);

					$comprometidoI = $comprometido_partidaI[$par];

					$causaI = $causa_partidaI[$par];

					$pagadoI = $pagado_partidaI[$par];
				}else{
					$sum_formulado+=$field["Formulado"];
                    $total_formulado+=$field["Formulado"];
					$aumentado_partidaI_anterior[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'];
					$disminuido_partidaI_anterior[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'];
					$comprometido_partidaI_anterior[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI'];
					$causa_partidaI_anterior[$par] +=  $field['CausaI'];
					$pagado_partidaI_anterior[$par] +=  $field['PagadoI'];

					$presupuestaria_anterior = ($field["Formulado"] +$aumentado_partidaI_anterior[$par] - $disminuido_partidaI_anterior[$par]) - $comprometido_partidaI_anterior[$par];

					$financiera_anterior = ($field["Formulado"] +$aumentado_partidaI_anterior[$par] - $disminuido_partidaI_anterior[$par]) - $pagado_partidaI_anterior[$par];

					$sum_presupuestaria+=$presupuestaria_anterior;
					$total_presupuestaria+=$presupuestaria_anterior;

					$sum_financiera+=$financiera_anterior;
					$total_financiera+=$financiera_anterior;

					$modificado = $aumentado_partidaI[$par] - $disminuido_partidaI[$par];

					$sum_modificacion+=$modificado;
					$total_modificacion+=$modificado;

					$actual0 = $presupuestaria_anterior + $modificado;
					$actualf = $financiera_anterior + $modificado;

					$sum_actual+=$actual0;
					$total_actual+=$actual0;

					$sum_actualf+=$actualf;
					$total_actualf+=$actualf;

					$sum_compromiso+=$comprometido_partidaI[$par];
					$total_compromiso+=$comprometido_partidaI[$par];

					$sum_causado+=$causa_partidaI[$par];
					$total_causado+=$causa_partidaI[$par];

					$sum_pagado+=$pagado_partidaI[$par];
					$total_pagado+=$pagado_partidaI[$par];


					$sum_disponible+=($actual0-$comprometido);

					$comprometidoI = $comprometido_partidaI[$par];

					$causaI = $causa_partidaI[$par];

					$pagadoI = $pagado_partidaI[$par];


				}

			}
			else if ($field["Tipo"]=="generica") {
				$pdf->SetFont('Arial', 'B', 7);

				$gen++;
				if ($mes == "01") {
					$modificado = $aumentado_genericaI[$gen] - $disminuido_genericaI[$gen];

					$actual0 = $field["Formulado"] + $modificado;

					$comprometidoI = $comprometido_genericaI[$gen];

					$causaI = $causa_genericaI[$gen];

					$pagadoI = $pagado_genericaI[$gen];

				}else{

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

					$presupuestaria_anterior = ($field["Formulado"] +$aumentado_genericaI_anterior[$gen] - $disminuido_genericaI_anterior[$gen]) - $comprometido_genericaI_anterior[$gen];

					$financiera_anterior = ($field["Formulado"] +$aumentado_genericaI_anterior[$gen] - $disminuido_genericaI_anterior[$gen]) - $pagado_genericaI_anterior[$gen];


					$actual0 = $presupuestaria_anterior + $modificado;


				}


			}
			else if ($field["Tipo"]=="especifica") {
				$pdf->SetFont('Arial', '', 7);

				$esp++;
				if ($mes == "01") {

					$modificado = $aumentado_especificaI[$esp] - $disminuido_especificaI[$esp];

					$actual0 = $field["Formulado"] + $modificado;

					$comprometidoI = $comprometido_especificaI[$esp];

					$causaI = $causa_especificaI[$esp];

					$pagadoI = $pagado_especificaI[$esp];

				}else{


					$presupuestaria_anterior = ($field["Formulado"] +$aumentado_especificaI_anterior[$esp] - $disminuido_especificaI_anterior[$esp]) - $comprometido_especificaI_anterior[$esp];

					$financiera_anterior = ($field["Formulado"] +$aumentado_especificaI_anterior[$esp] - $disminuido_especificaI_anterior[$esp]) - $pagado_especificaI_anterior[$esp];

					$modificado = $aumentado_especificaI[$esp] - $disminuido_especificaI[$esp];

					$comprometidoI = $comprometido_especificaI[$esp];

					$causaI = $causa_especificaI[$esp];

					$pagadoI = $pagado_especificaI[$esp];

					$actual0 = $presupuestaria_anterior + $modificado;

				}
			}
			else if ($field["Tipo"]=="subespecifica") {
				$pdf->SetFont('Arial', '', 7);

				$sub++;
				if ($mes == "01") {

					$modificado = $aumentado_subespecificaI[$sub] - $disminuido_subespecificaI[$sub];

					$actual0 = $field["Formulado"] + $modificado;

					$comprometidoI = $comprometido_subespecificaI[$sub];

					$causaI = $causa_subespecificaI[$sub];

					$pagadoI = $pagado_subespecificaI[$sub];

				}else{


					$presupuestaria_anterior = ($field["Formulado"] +$aumentado_subespecificaI_anterior[$sub] - $disminuido_subespecificaI_anterior[$sub]) - $comprometido_subespecificaI_anterior[$sub];

					$financiera_anterior = ($field["Formulado"] +$aumentado_subespecificaI_anterior[$sub] - $disminuido_subespecificaI_anterior[$sub]) - $pagado_subespecificaI_anterior[$sub];

					$comprometidoI = $comprometido_subespecificaI[$sub];

					$causaI = $causa_subespecificaI[$sub];

					$pagadoI = $pagado_subespecificaI[$sub];

					$modificado = $aumentado_subespecificaI[$sub] - $disminuido_subespecificaI[$sub];
					$actual0 = $presupuestaria_anterior + $modificado;


				}
			}

			$modificacion=number_format($modificado, 2, ',', '.');

			$actual=number_format($actual0, 2, ',', '.');

			$total_comprometidoI = number_format($comprometidoI, 2, ',', '.');

			$total_causaI = number_format($causaI, 2, ',', '.');

			$total_pagadoI = number_format($pagadoI, 2, ',', '.');


			if ($field['codordinal'] != "0000") $descripcion = $field['codordinal'].' '.$field['nomordinal'];
			else $descripcion= $field['NomPartida'];

			if ($mes == "01") {
				$compromiso = $comprometidoI;
				$causado = $causaI;
				$compromiso = number_format($compromiso, 2, ',', '.');
				$causado = number_format($causado, 2, ',', '.');
				$formulado = number_format($field["Formulado"], 2, ',', '.');
				$total_disponible = $disponible_compromiso;

				$pdf->Row(array($clasificador, utf8_decode($descripcion), $formulado, $modificacion, $actual, $total_comprometidoI, $total_causaI, number_format(($actual0-$comprometidoI), 2, ',', '.'), $total_pagadoI, number_format(($actual0-$pagadoI), 2, ',', '.')));

				$linea=$pdf->GetY(); if ($linea>195) { trimestral_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $mes); $pdf->SetY(57); }


			} else {

				$compromiso = $comprometidoI;
				$causado = $causaI;
				$compromiso = number_format($compromiso, 2, ',', '.');
				$causado = number_format($causado, 2, ',', '.');

				$total_disponible = $disponible_compromiso;
				if (($financiera_anterior + $modificado)-$pagadoI<=0){
					$disponible_financiero = number_format(0, 2, ',', '.');
				}else{
					$disponible_financiero = number_format((($financiera_anterior + $modificado)-$pagadoI), 2, ',', '.');
				}

				$pdf->Row(array($clasificador, utf8_decode($descripcion), number_format($presupuestaria_anterior, 2, ',', '.'), number_format($financiera_anterior, 2, ',', '.'), $modificacion, $actual, $total_comprometidoI, $total_causaI, number_format(($actual0-$comprometidoI), 2, ',', '.'), $total_pagadoI, $disponible_financiero));

				$linea=$pdf->GetY(); if ($linea>195) { trimestral_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $mes); $pdf->SetY(57); }
			}

		}


		//	IMPRIMO LOS TOTALES DE LA CATEGORIA

		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=348; $pdf->Rect($x, $y+2, $w, $h);
		$pdf->SetXY($x, $y+5);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		if ($mes == "01") {
			$pdf->Row(array('', '', number_format($sum_formulado, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actual-$sum_pagado), 2, ',', '.')));
		}else{
			if ($sum_actualf-$sum_pagado<=0){
				$total_disponible_financiero = number_format(0, 2, ',', '.');
			}else{
				$total_disponible_financiero = number_format(($sum_actualf-$sum_pagado), 2, ',', '.');
			}

			$pdf->Row(array('', '', number_format($sum_presupuestaria, 2, ',', '.'), number_format($sum_financiera, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), $total_disponible_financiero));
		}

		//SI EXISTEN TOTALES LOS IMPRIMOS
		if($sum_formulado < $total_formulado){
			$y=$pdf->GetY();
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
			$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
			$pdf->SetXY($x, $y+5);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			if ($mes == "01") {
				$pdf->Row(array('', '', number_format($total_formulado, 2, ',', '.'), number_format($total_modificacion, 2, ',', '.'), number_format($total_actual, 2, ',', '.'), number_format($total_compromiso, 2, ',', '.'), number_format($total_causado, 2, ',', '.'), number_format(($total_actual-$total_compromiso), 2, ',', '.'), number_format($total_pagado, 2, ',', '.'), number_format(($total_actual-$total_pagado), 2, ',', '.')));
			}else{
				if ($total_actualf-$total_pagado<=0){
					$total_disponible_financiero = number_format(0, 2, ',', '.');
				}else{
					$total_disponible_financiero = number_format(($total_actualf-$total_pagado), 2, ',', '.');
				}
				$pdf->Row(array('', '', number_format($total_presupuestaria, 2, ',', '.'), number_format($total_financiera, 2, ',', '.'), number_format($total_modificacion, 2, ',', '.'), number_format($total_actual, 2, ',', '.'), number_format($total_compromiso, 2, ',', '.'), number_format($total_causado, 2, ',', '.'), number_format(($total_actual-$total_compromiso), 2, ',', '.'), number_format($total_pagado, 2, ',', '.'), $total_disponible_financiero));
			}

		}


	break;
// 	FIN REPORTE TRIMESTRAL ONAPRE


//	ANUAL ONAPRE...
	case "anual_onapre":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
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

		$filtro = "";
		if ($anio_fiscal != "") $filtro .= " (maestro_presupuesto.anio = '".$anio_fiscal."')";
		if ($financiamiento != "") $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."')";
		if ($tipo_presupuesto != "") $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."')";
		if ($idcategoria_programatica != "") $filtro .= " AND (maestro_presupuesto.idcategoria_programatica = '".$idcategoria_programatica."')";

		//---------------------------------------------
		$desde = $anio_fiscal.'-01-01';
		$hasta = $anio_fiscal.'-12-31';
		$idesde = '01-01-'.$anio_fiscal;
		$ihasta = '31-12-'.$anio_fiscal;
		$mes_inicial = 1;
		$mes_final = 12;
		//---------------------------------------------
		anual_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta);
		//---------------------------------------------

		//LLAMA A LA FUNCION PARA GENERAR SENTENCIA SQL DE UN PERIODO
		$sql = busca_rango_tiempo($desde,$hasta,$mes_inicial,$mes_final,$filtro,$anio_fiscal);
        $sql_suma = $sql;
        $par = 0; $gen = 0; $esp = 0; $sub = 0;
        $query_suma = mysql_query($sql_suma) or die ($sql_suma.mysql_error());
        while ($field = mysql_fetch_array($query_suma)) {
            $aumento_rendicion = 0;
            $disminucion_rendicion = 0;
            if ($field['MRendicionAumento1']>0){
                $aumento_rendicion = $field['MRendicionAumento1'] - $field['MCredito1'] - $field['MReceptora1'] - $field['MRectificacion1'] ;
            }
            if ($field['MRendicionDisminucion1']>0){
                $disminucion_rendicion = $field['MRendicionDisminucion1'] - $field['MDisminucion1'] - $field['MCedentes1'] - $field['MRectificadora1'];
            }
            if ($field['Tipo'] == "partida") $par++;
            elseif ($field['Tipo'] == "generica") $gen++;
            elseif ($field['Tipo'] == "especifica") {
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
                $comprometido_partidaI[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI'] + $field['MRendicionCompromiso1'];

                $causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                $causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                $causa_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];

                if($field['PagadoI']>$field['CausaI']){
                    $pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];
                }else{
                    $pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_partidaI[$par] +=  $field['PagadoI'] + $field['MRendicionPagados1'];
                }

            }
            elseif ($field['Tipo'] == "subespecifica") {
                $sub ++;
                // DETERMINO SI ES SUB ESPECIFICA PARA SUMARLA A LA ESPECIFICA
                $aumentado_subespecificaI[$sub] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                $aumentado_especificaI[$esp] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                $aumentado_genericaI[$gen] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;
                $aumentado_partidaI[$par] += $field['MCredito1'] + $field['MReceptora1'] + $field['MRectificacion1'] + $aumento_rendicion;

                $disminuido_subespecificaI[$sub] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                $disminuido_especificaI[$esp] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                $disminuido_genericaI[$gen] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;
                $disminuido_partidaI[$par] += $field['MDisminucion1'] + $field['MCedentes1'] + $field['MRectificadora1'] + $disminucion_rendicion;

                $comprometido_subespecificaI[$sub] += $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];
                $comprometido_especificaI[$esp] += $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];
                $comprometido_genericaI[$gen] += $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];
                $comprometido_partidaI[$par] +=  $field['CompraCompromisoI'] + $field['PagoCompromisoI']  + $field['MRendicionCompromiso1'];

                $causa_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
                $causa_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                $causa_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                $causa_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];

                if($field['PagadoI']>$field['CausaI']){
                    $pagado_subespecificaI[$sub] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_especificaI[$esp] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_genericaI[$gen] += $field['CausaI'] + $field['MRendicionCausado1'];
                    $pagado_partidaI[$par] +=  $field['CausaI'] + $field['MRendicionCausado1'];
                }else{
                    $pagado_subespecificaI[$sub] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_especificaI[$esp] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_genericaI[$gen] += $field['PagadoI'] + $field['MRendicionPagados1'];
                    $pagado_partidaI[$par] +=  $field['PagadoI'] + $field['MRendicionPagados1'];
                }
            }
        }

		$par = 0; $gen = 0; $esp = 0; $sub = 0;
		$sum_formulado=0; $sum_modificacion=0; $sum_actual=0; $sum_compromiso=0; $sum_causado=0; $sum_pagado=0;
		$total_formulado=0; $total_modificacion=0; $total_actual=0; $total_compromiso=0; $total_causado=0; $total_pagado=0;

		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {

			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];


			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"]!=$IdCategoria) {
				if($sum_formulado == 0 and $sum_actual == 0){
					$IdCategoria=$field["IdCategoria"];
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(205, 5, utf8_decode($field["CodCategoria"]." - ".$field["Unidad"]), 1, 1, 'L', 1);

				}else{
					//IMPRIMO LOS TOTALES DE LA CATEGORIA ANTERIOR
					$y=$pdf->GetY();
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
					$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
					$pdf->SetXY($x, $y+5);
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Row(array('', '', number_format($sum_formulado, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actual-$sum_pagado), 2, ',', '.')));

					$sum_formulado = 0; $sum_aumento = 0; $sum_disminucion = 0; $sum_modificado = 0; $sum_actual = 0;
					$sum_compromiso = 0; $sum_causado = 0; $sum_pagado = 0; $sum_disponible = 0; $sum_modificacion = 0;
					$sum_presupuestaria = 0; $sum_financiera = 0; $sum_actualf = 0;

					//IMPRIMO LA CABECERA DE LA NUEVA CATEGORIA
					anual_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta);
					$pdf->SetY(52);
					$IdCategoria=$field["IdCategoria"];
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(205, 5, utf8_decode($field["CodCategoria"]." - ".$field["Unidad"]), 1, 1, 'L', 1);

				}
			}

			if ($field["Tipo"]=="partida") {
				$pdf->SetFont('Arial', 'B', 7);

				$par++;

				$sum_formulado+=$field["Formulado"];
				$total_formulado+=$field["Formulado"];

				$modificado = $aumentado_partidaI[$par] - $disminuido_partidaI[$par];
				$sum_modificacion+=$modificado;
				$total_modificacion+=$modificado;

				$actual0 = $field["Formulado"] + $modificado;
				$sum_actual+=$actual0;
				$total_actual+=$actual0;

				$comprometidoI = $comprometido_partidaI[$par];
				$sum_compromiso+=$comprometido_partidaI[$par];
				$total_compromiso+=$comprometido_partidaI[$par];

				$causaI = $causa_partidaI[$par];
				$sum_causado+=$causa_partidaI[$par];
				$total_causado+=$causa_partidaI[$par];

				$pagadoI = $pagado_partidaI[$par];
				$sum_pagado+=$pagado_partidaI[$par];
				$total_pagado+=$pagado_partidaI[$par];

				$sum_disponible+=($actual0-$comprometido);

			}
			else if ($field["Tipo"]=="generica") {
				$pdf->SetFont('Arial', 'B', 7);

				$gen++;
				$modificado = $aumentado_genericaI[$gen] - $disminuido_genericaI[$gen];
				$actual0 = $field["Formulado"] + $modificado;
				$comprometidoI = $comprometido_genericaI[$gen];
				$causaI = $causa_genericaI[$gen];
				$pagadoI = $pagado_genericaI[$gen];

			}
			else if ($field["Tipo"]=="especifica") {
				$pdf->SetFont('Arial', '', 7);

				$esp++;
				$modificado = $aumentado_especificaI[$esp] - $disminuido_especificaI[$esp];
				$actual0 = $field["Formulado"] + $modificado;
				$comprometidoI = $comprometido_especificaI[$esp];
				$causaI = $causa_especificaI[$esp];
				$pagadoI = $pagado_especificaI[$esp];

			}
			else if ($field["Tipo"]=="subespecifica") {
				$pdf->SetFont('Arial', '', 7);

				$sub++;

				$modificado = $aumentado_subespecificaI[$sub] - $disminuido_subespecificaI[$sub];

				$actual0 = $field["Formulado"] + $modificado;

				$comprometidoI = $comprometido_subespecificaI[$sub];

				$causaI = $causa_subespecificaI[$sub];

				$pagadoI = $pagado_subespecificaI[$sub];

			}

			$formulado = number_format($field["Formulado"], 2, ',', '.');
			$modificacion=number_format($modificado, 2, ',', '.');
			$actual=number_format($actual0, 2, ',', '.');
			$total_comprometidoI = number_format($comprometidoI, 2, ',', '.');
			$total_causaI = number_format($causaI, 2, ',', '.');
			$disponible_compromiso = number_format(($actual0-$comprometidoI), 2, ',', '.');
			$total_pagadoI = number_format($pagadoI, 2, ',', '.');
			$disponible_pagado = number_format(($actual0-$pagadoI), 2, ',', '.');

			if ($field['codordinal'] != "0000") $descripcion = $field['codordinal'].' '.$field['nomordinal'];
			else $descripcion= $field['NomPartida'];

			$pdf->Row(array($clasificador, utf8_decode($descripcion), $formulado, $modificacion, $actual, $total_comprometidoI, $total_causaI, $disponible_compromiso, $total_pagadoI, $disponible_pagado));

			$linea=$pdf->GetY(); if ($linea>195) { anual_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta); $pdf->SetY(57); }

		}

		//	IMPRIMO LOS TOTALES DE LA CATEGORIA

		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=348; $pdf->Rect($x, $y+2, $w, $h);
		$pdf->SetXY($x, $y+5);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);

		$pdf->Row(array('', '', number_format($sum_formulado, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actual-$sum_pagado), 2, ',', '.')));

		//SI EXISTEN TOTALES LOS IMPRIMOS
		if($sum_formulado < $total_formulado){
			$y=$pdf->GetY();
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
			$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
			$pdf->SetXY($x, $y+5);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);

				$pdf->Row(array('', '', number_format($total_formulado, 2, ',', '.'), number_format($total_modificacion, 2, ',', '.'), number_format($total_actual, 2, ',', '.'), number_format($total_compromiso, 2, ',', '.'), number_format($total_causado, 2, ',', '.'), number_format(($total_actual-$total_compromiso), 2, ',', '.'), number_format($total_pagado, 2, ',', '.'), number_format(($total_actual-$total_pagado), 2, ',', '.')));

		}

	break;
//FIN EJECUCION ANUAL ONAPRE


//	Consolidado por Sector ONAPRE...
	case "consolidado_sector_onapre":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
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

		$filtro = "";
		if ($anio_fiscal != "") $filtro .= " AND (mp.anio = '".$anio_fiscal."')";
		if ($financiamiento != "") $filtro .= " AND (mp.idfuente_financiamiento = '".$financiamiento."')";
		if ($tipo_presupuesto != "") $filtro .= " AND (mp.idtipo_presupuesto = '".$tipo_presupuesto."')";
		if ($idsector != "") $filtro .= " AND (c.idsector = '".$idsector."')";
		//---------------------------------------------
		$mes_inicial = 1; $desde_inicial = $anio_fiscal.'-01-01';
		if ($trimestre=='00'){
			$idesde = '01-01-'.$anio_fiscal; $ihasta = '31-12-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-01-01'; $fhasta = $anio_fiscal.'-12-31'; $hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final = 12;
		}
		if ($trimestre=='01'){
			$idesde = '01-01-'.$anio_fiscal; $ihasta = '31-03-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-01-01'; $fhasta = $anio_fiscal.'-03-31'; $hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final = 3;
		}

		if ($trimestre=='02'){
		   $idesde = '01-04-'.$anio_fiscal; $ihasta = '30-06-'.$anio_fiscal;
		   $fdesde = $anio_fiscal.'-04-01'; $fhasta = $anio_fiscal.'-06-30';
			$hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final = 6; $mes_anterior = 3;

		}
		if ($trimestre=='03'){
			$idesde = '01-07-'.$anio_fiscal; $ihasta = '30-09-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-07-01'; $fhasta = $anio_fiscal.'-09-30';
			$hasta_anterior = $anio_fiscal.'-06-30';
			$mes_final = 9; $mes_anterior = 6;
		}
		if ($trimestre=='04'){
			$idesde = '01-10-'.$anio_fiscal; $ihasta = '31-12-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-10-01'; $fhasta = $anio_fiscal.'-12-31';
			$hasta_anterior = $anio_fiscal.'-09-30';
			$mes_final = 12; $mes_anterior = 9;
		}

		//consolidado_sector_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $fdesde, $fhasta, $trimestre);

		//	CONSULTO TODO PARA SUMAR LAS PARTIDAS


		if ($trimestre == '00' or $trimestre == '01'){

			//$sql = busca_rango_tiempo_consolidado($fdesde,$fhasta,$mes_inicial,$mes_final,$filtro,$anio_fiscal);

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



			$query = mysql_query($sql) or die ($sql.mysql_error());
			$rows = mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field = mysql_fetch_array($query);
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
				if ($field['MRendicionAumento1']>0){
                    $aumento_rendicion = $field['MRendicionAumento1']-$field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1']>0){
                    $disminucion_rendicion = $field['MRendicionDisminucion1']-$field['Disminucion']-$field['Cedentes'] + $field['Rectificadora'];
                }
				$par = $field["CodSector"].$field["Par"];
				$_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
				$_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
				$_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
				$_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
				$_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];
			}
		}else{
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
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$rows = mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field = mysql_fetch_array($query);
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
				if ($field['MRendicionAumento1']>0){
                    $aumento_rendicion = $field['MRendicionAumento1']-$field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1']>0){
                    $disminucion_rendicion = $field['MRendicionDisminucion1']-$field['Disminucion']-$field['Cedentes'] + $field['Rectificadora'];
                }
				$par = $field["CodSector"].$field["Par"];
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
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$rows = mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field = mysql_fetch_array($query);
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
				if ($field['MRendicionAumento1']>0){
                    $aumento_rendicion = $field['MRendicionAumento1']-$field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1']>0){
                    $disminucion_rendicion = $field['MRendicionDisminucion1']-$field['Disminucion']-$field['Cedentes'] + $field['Rectificadora'];
                }
				$par = $field["CodSector"].$field["Par"];
				$_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
				$_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
				$_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
				$_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
				$_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];
			}

		}


		//	CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO	O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
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
		$presupuestaria_anterior = 0; $financiera_anterior = 0; $actualf = 0;
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field = mysql_fetch_array($query);
			//	SI CAMBIA DE SECTOR LA IMPRIMO
			if ($field["IdSector"]!=$IdSector) {
				$IdSector=$field["IdSector"];
				$l=1;
				if ($i!=0) {

					//	IMPRIMO LOS TOTALES
					$y=$pdf->GetY();
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
					$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
					$pdf->SetXY($x, $y+5);
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);
					if ($trimestre == '00' or $trimestre == "01") {
						$pdf->Row(array('', '', number_format($sum_formulado, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actual-$sum_pagado), 2, ',', '.')));
					}else{
						$pdf->Row(array('', '', number_format($sum_presupuestaria_anterior, 2, ',', '.'), number_format($sum_financiera_anterior, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actualf-$sum_pagado), 2, ',', '.')));
					}

					$sum_formulado = 0; $sum_aumento = 0; $sum_disminucion = 0; $sum_modificado = 0; $sum_actual = 0;
					$sum_compromiso = 0; $sum_causado = 0; $sum_pagado = 0; $sum_disponible = 0; $sum_modificacion = 0;
					$sum_presupuestaria_anterior = 0; $sum_financiera_anterior = 0; $sum_actualf = 0;


					consolidado_sector_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $trimestre,$field["CodSector"], $field["Sector"]);

				}else{
					consolidado_sector_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $trimestre,$field["CodSector"], $field["Sector"]);
				}
			}
			$l++;
			$clasificador = $field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$par = $field['CodSector'].$field["Par"];

			$modificacion = $_AUMENTO[$par] - $_DISMINUCION[$par];
			$actual = $field['Formulado'] + $modificacion;


			if ($l%2 == 0) $pdf->SetFillColor(255, 255, 255); else $pdf->SetFillColor(225, 225, 225);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);

			$y=$pdf->GetY();
			$x=5;
			$nb=$pdf->NbLines(50, utf8_decode($field["NomPartida"])); $hf=3*$nb;
			/*$pdf->SetXY($x, $y); $pdf->Cell(20, $hf, $clasificador, 0, 1, 'C', 1); $x+=20;
			$pdf->SetXY($x, $y); $pdf->MultiCell(50, 3, utf8_decode($field["NomPartida"]), 0, 'L', 1); $x+=50;
			*/
			if ($trimestre == '00' or $trimestre == '01'){

				$pdf->Row(array($clasificador, utf8_decode($field["NomPartida"]),
												number_format($field['Formulado'], 2, ',', '.'),
												number_format($modificacion, 2, ',', '.'),
												number_format($actual, 2, ',', '.'),
												number_format($_COMPROMISO[$par], 2, ',', '.'),
												number_format($_CAUSADO[$par], 2, ',', '.'),
												number_format(($actual-$_COMPROMISO[$par]), 2, ',', '.'),
												number_format($_PAGADO[$par], 2, ',', '.'),
												number_format(($actual-$_PAGADO[$par]), 2, ',', '.')));

			}else{
				$modificacion = $_AUMENTO[$par] - $_DISMINUCION[$par];

				$presupuestaria_anterior = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_COMPROMISO_ANTERIOR[$par];
				$financiera_anterior = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_PAGADO_ANTERIOR[$par];
				$actual = $presupuestaria_anterior + $modificacion;
				$actualf = $financiera_anterior + $modificacion;

				$pdf->Row(array($clasificador, utf8_decode($field["NomPartida"]),
												number_format($presupuestaria_anterior, 2, ',', '.'),
												number_format($financiera_anterior, 2, ',', '.'),
												number_format($modificacion, 2, ',', '.'),
												number_format($actual, 2, ',', '.'),
												number_format($_COMPROMISO[$par], 2, ',', '.'),
												number_format($_CAUSADO[$par], 2, ',', '.'),
												number_format(($actual-$_COMPROMISO[$par]), 2, ',', '.'),
												number_format($_PAGADO[$par], 2, ',', '.'),
												number_format(($actualf-$_PAGADO[$par]), 2, ',', '.')));
			}
			$pdf->Ln(4);
			$linea=$pdf->GetY(); if ($linea>175) {
				consolidado_sector_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $fdesde, $fhasta, $trimestre, $field["CodSector"], $field["Sector"]);
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
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
		//	IMPRIMO LOS TOTALES
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
		$pdf->SetXY($x, $y+5);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		if ($trimestre == '00' or $trimestre == "01") {
			$pdf->Row(array('', '', number_format($sum_formulado, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actual-$sum_pagado), 2, ',', '.')));
		}else{
			$pdf->Row(array('', '', number_format($sum_presupuestaria_anterior, 2, ',', '.'), number_format($sum_financiera_anterior, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actualf-$sum_pagado), 2, ',', '.')));
		}
		if ($sum_formulado < $total_formulado){
            $y=$pdf->GetY();
            $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
            $h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
            //	IMPRIMO LOS TOTALES
            $y=$pdf->GetY();
            $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
            $h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
            $pdf->SetXY($x, $y+5);
            $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', 'B', 8);
            if ($trimestre == '00' or $trimestre == "01") {
                $pdf->Row(array('', '', number_format($total_formulado, 2, ',', '.'), number_format($total_modificacion, 2, ',', '.'), number_format($total_actual, 2, ',', '.'), number_format($total_compromiso, 2, ',', '.'), number_format($total_causado, 2, ',', '.'), number_format(($total_actual-$total_compromiso), 2, ',', '.'), number_format($total_pagado, 2, ',', '.'), number_format(($total_actual-$total_pagado), 2, ',', '.')));
            }else{
                $pdf->Row(array('', '', number_format($total_presupuestaria_anterior, 2, ',', '.'), number_format($total_financiera_anterior, 2, ',', '.'), number_format($total_modificacion, 2, ',', '.'), number_format($total_actual, 2, ',', '.'), number_format($total_compromiso, 2, ',', '.'), number_format($total_causado, 2, ',', '.'), number_format(($total_actual-$total_compromiso), 2, ',', '.'), number_format($total_pagado, 2, ',', '.'), number_format(($total_actualf-$total_pagado), 2, ',', '.')));
            }
        }
		break;
//	FIN REPORTE CONSOLIDADO SECTOR ONAPRE



//	Consolidado por Programa ONAPRE...
	case "consolidado_programa_onapre":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
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

		$filtro = "";
		if ($anio_fiscal != "") $filtro .= " AND (mp.anio = '".$anio_fiscal."')";
		if ($financiamiento != "") $filtro .= " AND (mp.idfuente_financiamiento = '".$financiamiento."')";
		if ($tipo_presupuesto != "") $filtro .= " AND (mp.idtipo_presupuesto = '".$tipo_presupuesto."')";
		$filtro .= " AND (c.idsector = '".$idsector."')";
		if ($idprograma != "") $filtro .= " AND (p.idprograma = '".$idprograma."')";

		//---------------------------------------------
		$mes_inicial = 1; $desde_inicial = $anio_fiscal.'-01-01';
		if ($trimestre=='00'){
			$idesde = '01-01-'.$anio_fiscal; $ihasta = '31-12-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-01-01'; $fhasta = $anio_fiscal.'-12-31'; $hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final = 12;
		}
		if ($trimestre=='01'){
			$idesde = '01-01-'.$anio_fiscal; $ihasta = '31-03-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-01-01'; $fhasta = $anio_fiscal.'-03-31'; $hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final = 3;
		}

		if ($trimestre=='02'){
		   $idesde = '01-04-'.$anio_fiscal; $ihasta = '30-06-'.$anio_fiscal;
		   $fdesde = $anio_fiscal.'-04-01'; $fhasta = $anio_fiscal.'-06-30';
			$hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final = 6; $mes_anterior = 3;

		}
		if ($trimestre=='03'){
			$idesde = '01-07-'.$anio_fiscal; $ihasta = '30-09-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-07-01'; $fhasta = $anio_fiscal.'-09-30';
			$hasta_anterior = $anio_fiscal.'-06-30';
			$mes_final = 9; $mes_anterior = 6;
		}
		if ($trimestre=='04'){
			$idesde = '01-10-'.$anio_fiscal; $ihasta = '31-12-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-10-01'; $fhasta = $anio_fiscal.'-12-31';
			$hasta_anterior = $anio_fiscal.'-09-30';
			$mes_final = 12; $mes_anterior = 9;
		}

		//	CONSULTO TODO PARA SUMAR LAS PARTIDAS


		if ($trimestre == '00' or $trimestre == '01'){
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
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$rows = mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field = mysql_fetch_array($query);
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
				if ($field['MRendicionAumento1']>0){
                    $aumento_rendicion = $field['MRendicionAumento1']-$field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1']>0){
                    $disminucion_rendicion = $field['MRendicionDisminucion1']-$field['Disminucion']-$field['Cedentes'] + $field['Rectificadora'];
                }
				$par = $field["CodPrograma"].$field["Par"];
				$_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
				$_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
				$_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
				$_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
				$_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];
			}
		}else{
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
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$rows = mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field = mysql_fetch_array($query);
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
				if ($field['MRendicionAumento1']>0){
                    $aumento_rendicion = $field['MRendicionAumento1']-$field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1']>0){
                    $disminucion_rendicion = $field['MRendicionDisminucion1']-$field['Disminucion']-$field['Cedentes'] + $field['Rectificadora'];
                }
				$par = $field["CodPrograma"].$field["Par"];
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
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$rows = mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field = mysql_fetch_array($query);
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
				if ($field['MRendicionAumento1']>0){
                    $aumento_rendicion = $field['MRendicionAumento1']-$field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1']>0){
                    $disminucion_rendicion = $field['MRendicionDisminucion1']-$field['Disminucion']-$field['Cedentes'] + $field['Rectificadora'];
                }
				$par = $field["CodPrograma"].$field["Par"];
				$_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
				$_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
				$_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
				$_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
				$_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];
			}

		}


		//	CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO	O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
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
		$presupuestaria_anterior = 0; $financiera_anterior = 0; $actualf = 0; $idprograma='';
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field = mysql_fetch_array($query);
			//	SI CAMBIA DE PROGRAMA LA IMPRIMO
			if ($field["idprograma"]!=$idprograma) {
				$idprograma=$field["idprograma"];
				$l=1;
				if ($i!=0) {

					//	IMPRIMO LOS TOTALES
					$y=$pdf->GetY();
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
					$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
					$pdf->SetXY($x, $y+5);
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);
					if ($trimestre == '00' or $trimestre == "01") {
						$pdf->Row(array('', '', number_format($sum_formulado, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actual-$sum_pagado), 2, ',', '.')));
					}else{
						$pdf->Row(array('', '', number_format($sum_presupuestaria_anterior, 2, ',', '.'), number_format($sum_financiera_anterior, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actualf-$sum_pagado), 2, ',', '.')));
					}

					$sum_formulado = 0; $sum_aumento = 0; $sum_disminucion = 0; $sum_modificado = 0; $sum_actual = 0;
					$sum_compromiso = 0; $sum_causado = 0; $sum_pagado = 0; $sum_disponible = 0; $sum_modificacion = 0;
					$sum_presupuestaria_anterior = 0; $sum_financiera_anterior = 0; $sum_actualf = 0;


					consolidado_programa_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $trimestre,$field["CodSector"], $field["Sector"], $field["CodPrograma"], $field["Programa"]);

				}else{
					consolidado_programa_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $trimestre,$field["CodSector"], $field["Sector"], $field["CodPrograma"], $field["Programa"]);
				}
			}

			$l++;
			$clasificador = $field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$par = $field['CodPrograma'].$field["Par"];

			$modificacion = $_AUMENTO[$par] - $_DISMINUCION[$par];
			$actual = $field['Formulado'] + $modificacion;


			if ($l%2 == 0) $pdf->SetFillColor(255, 255, 255); else $pdf->SetFillColor(225, 225, 225);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);

			$y=$pdf->GetY();
			$x=5;
			$nb=$pdf->NbLines(50, utf8_decode($field["NomPartida"])); $hf=3*$nb;

			if ($trimestre == '00' or $trimestre == '01'){

				$pdf->Row(array($clasificador, utf8_decode($field["NomPartida"]),
												number_format($field['Formulado'], 2, ',', '.'),
												number_format($modificacion, 2, ',', '.'),
												number_format($actual, 2, ',', '.'),
												number_format($_COMPROMISO[$par], 2, ',', '.'),
												number_format($_CAUSADO[$par], 2, ',', '.'),
												number_format(($actual-$_COMPROMISO[$par]), 2, ',', '.'),
												number_format($_PAGADO[$par], 2, ',', '.'),
												number_format(($actual-$_PAGADO[$par]), 2, ',', '.')));

			}else{
				$modificacion = $_AUMENTO[$par] - $_DISMINUCION[$par];

				$presupuestaria_anterior = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_COMPROMISO_ANTERIOR[$par];
				$financiera_anterior = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_PAGADO_ANTERIOR[$par];
				$actual = $presupuestaria_anterior + $modificacion;
				$actualf = $financiera_anterior + $modificacion;

				$pdf->Row(array($clasificador, utf8_decode($field["NomPartida"]),
												number_format($presupuestaria_anterior, 2, ',', '.'),
												number_format($financiera_anterior, 2, ',', '.'),
												number_format($modificacion, 2, ',', '.'),
												number_format($actual, 2, ',', '.'),
												number_format($_COMPROMISO[$par], 2, ',', '.'),
												number_format($_CAUSADO[$par], 2, ',', '.'),
												number_format(($actual-$_COMPROMISO[$par]), 2, ',', '.'),
												number_format($_PAGADO[$par], 2, ',', '.'),
												number_format(($actualf-$_PAGADO[$par]), 2, ',', '.')));
			}
			$pdf->Ln(4);
			$linea=$pdf->GetY(); if ($linea>175) {
				consolidado_programa_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $trimestre,$field["CodSector"], $field["Sector"], $field["CodPrograma"], $field["Programa"]);
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
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
		//	IMPRIMO LOS TOTALES
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
		$pdf->SetXY($x, $y+5);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		if ($trimestre == '00' or $trimestre == "01") {
			$pdf->Row(array('', '', number_format($sum_formulado, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actual-$sum_pagado), 2, ',', '.')));
		}else{
			$pdf->Row(array('', '', number_format($sum_presupuestaria_anterior, 2, ',', '.'), number_format($sum_financiera_anterior, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actualf-$sum_pagado), 2, ',', '.')));
		}

		if ($sum_formulado < $total_formulado){
            $y=$pdf->GetY();
            $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
            $h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
            //	IMPRIMO LOS TOTALES
            $y=$pdf->GetY();
            $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
            $h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
            $pdf->SetXY($x, $y+5);
            $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', 'B', 8);
            if ($trimestre == '00' or $trimestre == "01") {
                $pdf->Row(array('', '', number_format($total_formulado, 2, ',', '.'), number_format($total_modificacion, 2, ',', '.'), number_format($total_actual, 2, ',', '.'), number_format($total_compromiso, 2, ',', '.'), number_format($total_causado, 2, ',', '.'), number_format(($total_actual-$total_compromiso), 2, ',', '.'), number_format($total_pagado, 2, ',', '.'), number_format(($total_actual-$total_pagado), 2, ',', '.')));
            }else{
                $pdf->Row(array('', '', number_format($total_presupuestaria_anterior, 2, ',', '.'), number_format($total_financiera_anterior, 2, ',', '.'), number_format($total_modificacion, 2, ',', '.'), number_format($total_actual, 2, ',', '.'), number_format($total_compromiso, 2, ',', '.'), number_format($total_causado, 2, ',', '.'), number_format(($total_actual-$total_compromiso), 2, ',', '.'), number_format($total_pagado, 2, ',', '.'), number_format(($total_actualf-$total_pagado), 2, ',', '.')));
            }
        }

		break;
//	FIN REPORTE CONSOLIDADO POR PROGRAMAS ONAPRE


//	Consolidado por Categorias ONAPRE...
	case "consolidado_categoria_onapre":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
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
		//---------------------------------------------
		$mes_inicial = 1; $desde_inicial = $anio_fiscal.'-01-01';
		if ($trimestre=='00'){
			$idesde = '01-01-'.$anio_fiscal; $ihasta = '31-12-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-01-01'; $fhasta = $anio_fiscal.'-12-31'; $hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final = 12;
		}
		if ($trimestre=='01'){
			$idesde = '01-01-'.$anio_fiscal; $ihasta = '31-03-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-01-01'; $fhasta = $anio_fiscal.'-03-31'; $hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final = 3;
		}
		if ($trimestre=='02'){
		   $idesde = '01-04-'.$anio_fiscal; $ihasta = '30-06-'.$anio_fiscal;
		   $fdesde = $anio_fiscal.'-04-01'; $fhasta = $anio_fiscal.'-06-30';
			$hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final = 6; $mes_anterior = 3;

		}
		if ($trimestre=='03'){
			$idesde = '01-07-'.$anio_fiscal; $ihasta = '30-09-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-07-01'; $fhasta = $anio_fiscal.'-09-30';
			$hasta_anterior = $anio_fiscal.'-06-30';
			$mes_final = 9; $mes_anterior = 6;
		}
		if ($trimestre=='04'){
			$idesde = '01-10-'.$anio_fiscal; $ihasta = '31-12-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-10-01'; $fhasta = $anio_fiscal.'-12-31';
			$hasta_anterior = $anio_fiscal.'-09-30';
			$mes_final = 12; $mes_anterior = 9;
		}


		consolidado_categoria_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $trimestre);
		$filtro = "";
		if ($anio_fiscal != "") $filtro .= " AND (mp.anio = '".$anio_fiscal."')";
		if ($financiamiento != "") $filtro .= " AND (mp.idfuente_financiamiento = '".$financiamiento."')";
		if ($tipo_presupuesto != "") $filtro .= " AND (mp.idtipo_presupuesto = '".$tipo_presupuesto."')";
		if ($idcategoria_programatica != "") $filtro .= " AND (mp.idcategoria_programatica='".$idcategoria_programatica."')";
		//---------------------------------------------
		//	CONSULTO TODO PARA SUMAR LAS ESPECIFICAS
		if ($trimestre == '00' or $trimestre == '01'){

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
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$rows = mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field = mysql_fetch_array($query);
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
                if ($field['MRendicionAumento1']>0){
                    $aumento_rendicion = $field['MRendicionAumento1']-$field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1']>0){
                    $disminucion_rendicion = $field['MRendicionDisminucion1']-$field['Disminucion']-$field['Cedentes'] + $field['Rectificadora'];
                }
				$par = $field["CodCategoria"].$field["Par"];
				$_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
				$_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
				$_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
				$_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
				$_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];
			}

		}
		else{

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
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$rows = mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field = mysql_fetch_array($query);
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
                if ($field['MRendicionAumento1']>0){
                    $aumento_rendicion = $field['MRendicionAumento1']-$field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1']>0){
                    $disminucion_rendicion = $field['MRendicionDisminucion1']-$field['Disminucion']-$field['Cedentes'] + $field['Rectificadora'];
                }
				$par = $field["CodCategoria"].$field["Par"];
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
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$rows = mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field = mysql_fetch_array($query);
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
                if ($field['MRendicionAumento1']>0){
                    $aumento_rendicion = $field['MRendicionAumento1']-$field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1']>0){
                    $disminucion_rendicion = $field['MRendicionDisminucion1']-$field['Disminucion']-$field['Cedentes'] + $field['Rectificadora'];
                }
				$par = $field["CodCategoria"].$field["Par"];
				$_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
				$_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
				$_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
				$_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
				$_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];
			}


		}

		//	CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO	O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
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


		$presupuestaria_anterior = 0; $financiera_anterior = 0; $actualf = 0; $IdCategoria='';
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field = mysql_fetch_array($query);
			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"]!=$IdCategoria) {
				$IdCategoria=$field["IdCategoria"];
				$l=1;
				if ($i!=0) {


					//	IMPRIMO LOS TOTALES
					$y=$pdf->GetY();
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
					$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
					$pdf->SetXY($x, $y+5);
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);
					if ($trimestre == '00' or $trimestre == "01") {
						$pdf->Row(array('', '', number_format($sum_formulado, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actual-$sum_pagado), 2, ',', '.')));
					}else{
						$pdf->Row(array('', '', number_format($sum_presupuestaria_anterior, 2, ',', '.'), number_format($sum_financiera_anterior, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actualf-$sum_pagado), 2, ',', '.')));
					}

					$sum_formulado = 0; $sum_aumento = 0; $sum_disminucion = 0; $sum_modificado = 0; $sum_actual = 0;
					$sum_compromiso = 0; $sum_causado = 0; $sum_pagado = 0; $sum_disponible = 0; $sum_modificacion = 0;
					$sum_presupuestaria_anterior = 0; $sum_financiera_anterior = 0; $sum_actualf = 0;


					consolidado_categoria_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $trimestre);

					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 7);
					$pdf->Cell(205, 7, $field["CodCategoria"].'    '.$field["Unidad"], 1, 1, 'L', 1);
					$pdf->Ln(4);
				}else{
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 7);
					$pdf->Cell(205, 7, $field["CodCategoria"].'    '.$field["Unidad"], 1, 1, 'L', 1);
					$pdf->Ln(4);
				}
			}

			$l++;
			$clasificador = $field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$par = $field['CodCategoria'].$field["Par"];

			$modificacion = $_AUMENTO[$par] - $_DISMINUCION[$par];
			$actual = $field['Formulado'] + $modificacion;
			$disponible = $actual - $_COMPROMISO[$par] - $_RESERVADO[$par];

			if ($l%2 == 0) $pdf->SetFillColor(255, 255, 255); else $pdf->SetFillColor(225, 225, 225);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);


			$nb=$pdf->NbLines(50, utf8_decode($field["NomPartida"])); $hf=3*$nb;

			if ($trimestre == '00' or $trimestre == '01'){

				$pdf->Row(array($clasificador, utf8_decode($field["NomPartida"]),
												number_format($field['Formulado'], 2, ',', '.'),
												number_format($modificacion, 2, ',', '.'),
												number_format($actual, 2, ',', '.'),
												number_format($_COMPROMISO[$par], 2, ',', '.'),
												number_format($_CAUSADO[$par], 2, ',', '.'),
												number_format(($actual-$_COMPROMISO[$par]), 2, ',', '.'),
												number_format($_PAGADO[$par], 2, ',', '.'),
												number_format(($actual-$_PAGADO[$par]), 2, ',', '.')));

			}else{
				$modificacion = $_AUMENTO[$par] - $_DISMINUCION[$par];

				$presupuestaria_anterior = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_COMPROMISO_ANTERIOR[$par];
				$financiera_anterior = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_PAGADO_ANTERIOR[$par];
				$actual = $presupuestaria_anterior + $modificacion;
				$actualf = $financiera_anterior + $modificacion;

				$pdf->Row(array($clasificador, utf8_decode($field["NomPartida"]),
												number_format($presupuestaria_anterior, 2, ',', '.'),
												number_format($financiera_anterior, 2, ',', '.'),
												number_format($modificacion, 2, ',', '.'),
												number_format($actual, 2, ',', '.'),
												number_format($_COMPROMISO[$par], 2, ',', '.'),
												number_format($_CAUSADO[$par], 2, ',', '.'),
												number_format(($actual-$_COMPROMISO[$par]), 2, ',', '.'),
												number_format($_PAGADO[$par], 2, ',', '.'),
												number_format(($actualf-$_PAGADO[$par]), 2, ',', '.')));
			}
			$pdf->Ln(2);

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
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
		//	IMPRIMO LOS TOTALES
        $y=$pdf->GetY();
        $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
        $h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
        $pdf->SetXY($x, $y+5);
        $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 8);
        if ($trimestre == '00' or $trimestre == "01") {
            $pdf->Row(array('', '', number_format($sum_formulado, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actual-$sum_pagado), 2, ',', '.')));
        }else{
            $pdf->Row(array('', '', number_format($sum_presupuestaria_anterior, 2, ',', '.'), number_format($sum_financiera_anterior, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actualf-$sum_pagado), 2, ',', '.')));
        }

        if ($sum_formulado < $total_formulado){
            $y=$pdf->GetY();
            $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
            $h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
            //	IMPRIMO LOS TOTALES
            $y=$pdf->GetY();
            $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
            $h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
            $pdf->SetXY($x, $y+5);
            $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', 'B', 8);
            if ($trimestre == '00' or $trimestre == "01") {
                $pdf->Row(array('', '', number_format($total_formulado, 2, ',', '.'), number_format($total_modificacion, 2, ',', '.'), number_format($total_actual, 2, ',', '.'), number_format($total_compromiso, 2, ',', '.'), number_format($total_causado, 2, ',', '.'), number_format(($total_actual-$total_compromiso), 2, ',', '.'), number_format($total_pagado, 2, ',', '.'), number_format(($total_actual-$total_pagado), 2, ',', '.')));
            }else{
                $pdf->Row(array('', '', number_format($total_presupuestaria_anterior, 2, ',', '.'), number_format($total_financiera_anterior, 2, ',', '.'), number_format($total_modificacion, 2, ',', '.'), number_format($total_actual, 2, ',', '.'), number_format($total_compromiso, 2, ',', '.'), number_format($total_causado, 2, ',', '.'), number_format(($total_actual-$total_compromiso), 2, ',', '.'), number_format($total_pagado, 2, ',', '.'), number_format(($total_actualf-$total_pagado), 2, ',', '.')));
            }
        }

		break;
//FIN CONSOLIDADO CATEGORIA ONAPRE




// INICIO CONSOLIDADO GENERAL ONAPRE
	case "consolidado_general_onapre":
		$pdf=new PDF_MC_Table5('L', 'mm', 'Legal');
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
		$mes_inicial = 1; $desde_inicial = $anio_fiscal.'-01-01';
		if ($trimestre=='00'){
			$idesde = '01-01-'.$anio_fiscal; $ihasta = '31-12-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-01-01'; $fhasta = $anio_fiscal.'-12-31'; $hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final = 12;
		}
		if ($trimestre=='01'){
			$idesde = '01-01-'.$anio_fiscal; $ihasta = '31-03-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-01-01'; $fhasta = $anio_fiscal.'-03-31'; $hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final = 3;
		}

		if ($trimestre=='02'){
		   $idesde = '01-04-'.$anio_fiscal; $ihasta = '30-06-'.$anio_fiscal;
		   $fdesde = $anio_fiscal.'-04-01'; $fhasta = $anio_fiscal.'-06-30';
			$hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final = 6; $mes_anterior = 3;

		}
		if ($trimestre=='03'){
			$idesde = '01-07-'.$anio_fiscal; $ihasta = '30-09-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-07-01'; $fhasta = $anio_fiscal.'-09-30';
			$hasta_anterior = $anio_fiscal.'-06-30';
			$mes_final = 9; $mes_anterior = 6;
		}
		if ($trimestre=='04'){
			$idesde = '01-10-'.$anio_fiscal; $ihasta = '31-12-'.$anio_fiscal;
			$fdesde = $anio_fiscal.'-10-01'; $fhasta = $anio_fiscal.'-12-31';
			$hasta_anterior = $anio_fiscal.'-09-30';
			$mes_final = 12; $mes_anterior = 9;
		}

		//---------------------------------------------
		consolidado_general_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $trimestre, $idesde, $ihasta);
		$filtro = "";
		if ($anio_fiscal != "") $filtro .= " AND (maestro_presupuesto.anio = '".$anio_fiscal."')";
		if ($financiamiento != "") $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '".$financiamiento."')";
		if ($tipo_presupuesto != "") $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."')";

		//---------------------------------------------
		//	CONSULTO TODO PARA SUMAR LAS ESPECIFICAS
		if ($trimestre == '00' or $trimestre == '01'){

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
													fecha_solicitud >= '".$fdesde."' AND
													fecha_solicitud <= '".$fhasta."' AND
													estado = 'procesado')) AS Credito,

					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
															estado = 'procesado')) AS Receptora,


					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_rectificacion
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
															estado = 'procesado')) AS Rectificacion,


					(SELECT SUM(monto_debitar)
					 FROM partidas_disminucion_presupuesto
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 iddisminucion_presupuesto IN (SELECT iddisminucion_presupuesto
													   FROM disminucion_presupuesto
													   WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
															estado = 'procesado')) AS Disminucion,


					(SELECT SUM(monto_debitar)
					 FROM partidas_cedentes_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
															estado = 'procesado')) AS Cedentes,


					(SELECT SUM(monto_debitar)
					 FROM partidas_rectificadoras
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
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
														fecha_orden >= '".$fdesde."' AND
														fecha_orden <= '".$fhasta."' AND
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
											op1.fecha_orden >= '".$fdesde."' AND
											op1.fecha_orden <= '".$fhasta."' AND
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
											fecha_orden >= '".$fdesde."' AND
											fecha_orden <= '".$fhasta."' AND
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
											fecha_cheque >= '".$fdesde."' AND
											fecha_cheque <= '".$fhasta."' AND
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
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$rows = mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$par = $field["Par"];
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
                if ($field['MRendicionAumento1']>0){
                    $aumento_rendicion = $field['MRendicionAumento1']-$field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1']>0){
                    $disminucion_rendicion = $field['MRendicionDisminucion1']-$field['Disminucion']-$field['Cedentes'] + $field['Rectificadora'];
                }
				$_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
				$_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
				$_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
				$_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
				$_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];

			}







		}
		else{

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
													fecha_solicitud >= '".$desde_inicial."' AND
													fecha_solicitud <= '".$hasta_anterior."' AND
													estado = 'procesado')) AS Credito,

					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '".$desde_inicial."' AND
															fecha_solicitud <= '".$hasta_anterior."' AND
															estado = 'procesado')) AS Receptora,


					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_rectificacion
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '".$desde_inicial."' AND
															fecha_solicitud <= '".$hasta_anterior."' AND
															estado = 'procesado')) AS Rectificacion,


					(SELECT SUM(monto_debitar)
					 FROM partidas_disminucion_presupuesto
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 iddisminucion_presupuesto IN (SELECT iddisminucion_presupuesto
													   FROM disminucion_presupuesto
													   WHERE
															fecha_solicitud >= '".$desde_inicial."' AND
															fecha_solicitud <= '".$hasta_anterior."' AND
															estado = 'procesado')) AS Disminucion,


					(SELECT SUM(monto_debitar)
					 FROM partidas_cedentes_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '".$desde_inicial."' AND
															fecha_solicitud <= '".$hasta_anterior."' AND
															estado = 'procesado')) AS Cedentes,


					(SELECT SUM(monto_debitar)
					 FROM partidas_rectificadoras
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '".$desde_inicial."' AND
															fecha_solicitud <= '".$hasta_anterior."' AND
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
														fecha_orden >= '".$desde_inicial."' AND
														fecha_orden <= '".$hasta_anterior."' AND
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
											op1.fecha_orden >= '".$desde_inicial."' AND
											op1.fecha_orden <= '".$hasta_anterior."' AND
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
											fecha_orden >= '".$desde_inicial."' AND
											fecha_orden <= '".$hasta_anterior."' AND
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
											fecha_cheque >= '".$desde_inicial."' AND
											fecha_cheque <= '".$hasta_anterior."' AND
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
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$rows = mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$par = $field["Par"];
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
                if ($field['MRendicionAumento1']>0){
                    $aumento_rendicion = $field['MRendicionAumento1']-$field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
                }
                if ($field['MRendicionDisminucion1']>0){
                    $disminucion_rendicion = $field['MRendicionDisminucion1']-$field['Disminucion']-$field['Cedentes'] + $field['Rectificadora'];
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
													fecha_solicitud >= '".$fdesde."' AND
													fecha_solicitud <= '".$fhasta."' AND
													estado = 'procesado')) AS Credito,

					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios

														 WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
															estado = 'procesado')) AS Receptora,


					(SELECT SUM(monto_acreditar)
					 FROM partidas_receptoras_rectificacion
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
															estado = 'procesado')) AS Rectificacion,


					(SELECT SUM(monto_debitar)
					 FROM partidas_disminucion_presupuesto
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 iddisminucion_presupuesto IN (SELECT iddisminucion_presupuesto
													   FROM disminucion_presupuesto
													   WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
															estado = 'procesado')) AS Disminucion,


					(SELECT SUM(monto_debitar)
					 FROM partidas_cedentes_traslado
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idtraslados_presupuestarios IN (SELECT idtraslados_presupuestarios
														 FROM traslados_presupuestarios
														 WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
															estado = 'procesado')) AS Cedentes,


					(SELECT SUM(monto_debitar)
					 FROM partidas_rectificadoras
					 WHERE
						 idmaestro_presupuesto = maestro_presupuesto.idRegistro AND
						 idrectificacion_presupuesto IN (SELECT idrectificacion_presupuesto
														 FROM rectificacion_presupuesto
														 WHERE
															fecha_solicitud >= '".$fdesde."' AND
															fecha_solicitud <= '".$fhasta."' AND
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
														fecha_orden >= '".$fdesde."' AND
														fecha_orden <= '".$fhasta."' AND
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
											op1.fecha_orden >= '".$fdesde."' AND
											op1.fecha_orden <= '".$fhasta."' AND
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
											fecha_orden >= '".$fdesde."' AND
											fecha_orden <= '".$fhasta."' AND
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
											fecha_cheque >= '".$fdesde."' AND
											fecha_cheque <= '".$fhasta."' AND
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

			$query = mysql_query($sql) or die ($sql.mysql_error());
			$rows = mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$par = $field["Par"];
				$aumento_rendicion = 0;
				$disminucion_rendicion = 0;
				if ($field['MRendicionAumento1']>0){
					$aumento_rendicion = $field['MRendicionAumento1']-$field['Credito'] - $field['Receptora'] - $field['Rectificacion'];
				}
				if ($field['MRendicionDisminucion1']>0){
					$disminucion_rendicion = $field['MRendicionDisminucion1']-$field['Disminucion']-$field['Cedentes'] + $field['Rectificadora'];
				}
				$_AUMENTO[$par] += $field['Credito'] + $field['Receptora'] + $field['Rectificacion'] + $aumento_rendicion;
				$_DISMINUCION[$par] += $field['Disminucion'] + $field['Cedentes'] + $field['Rectificadora'] + $disminucion_rendicion;
				$_COMPROMISO[$par] += $field['CompraCompromiso'] + $field['PagoCompromiso'] + $field['MRendicionCompromiso1'];
				$_CAUSADO[$par] += $field['Causado'] + $field['MRendicionCausado1'];
				$_PAGADO[$par] += $field['Pagado'] + $field['MRendicionPagados1'];

			}



		}



		//	CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO	O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
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

		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);

			$l++;
			$clasificador = $field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$par = $field["Par"];

			$modificacion = $_AUMENTO[$par] - $_DISMINUCION[$par];
			$actual = $field['Formulado'] + $modificacion;
			$disponible = $actual - $_COMPROMISO[$par] - $_RESERVADO[$par];

			if ($l%2 == 0) $pdf->SetFillColor(255, 255, 255); else $pdf->SetFillColor(225, 225, 225);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);


			$nb=$pdf->NbLines(50, utf8_decode($field["NomPartida"])); $hf=3*$nb;

			if ($trimestre == '00' or $trimestre == '01'){

				$pdf->Row(array($clasificador, utf8_decode($field["NomPartida"]),
												number_format($field['Formulado'], 2, ',', '.'),
												number_format($modificacion, 2, ',', '.'),
												number_format($actual, 2, ',', '.'),
												number_format($_COMPROMISO[$par], 2, ',', '.'),
												number_format($_CAUSADO[$par], 2, ',', '.'),
												number_format(($actual-$_COMPROMISO[$par]), 2, ',', '.'),
												number_format($_PAGADO[$par], 2, ',', '.'),
												number_format(($actual-$_PAGADO[$par]), 2, ',', '.')));

			}else{
				$modificacion = $_AUMENTO[$par] - $_DISMINUCION[$par];

				$presupuestaria_anterior = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_COMPROMISO_ANTERIOR[$par];
				$financiera_anterior = ($field['Formulado'] + $_AUMENTO_ANTERIOR[$par] - $_DISMINUCION_ANTERIOR[$par]) - $_PAGADO_ANTERIOR[$par];
				$actual = $presupuestaria_anterior + $modificacion;
				$actualf = $financiera_anterior + $modificacion;

				$pdf->Row(array($clasificador, utf8_decode($field["NomPartida"]),
												number_format($presupuestaria_anterior, 2, ',', '.'),
												number_format($financiera_anterior, 2, ',', '.'),
												number_format($modificacion, 2, ',', '.'),
												number_format($actual, 2, ',', '.'),
												number_format($_COMPROMISO[$par], 2, ',', '.'),
												number_format($_CAUSADO[$par], 2, ',', '.'),
												number_format(($actual-$_COMPROMISO[$par]), 2, ',', '.'),
												number_format($_PAGADO[$par], 2, ',', '.'),
												number_format(($actualf-$_PAGADO[$par]), 2, ',', '.')));
			}
			$pdf->Ln(2);

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
		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
		//	IMPRIMO LOS TOTALES

			$y=$pdf->GetY();
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
			$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
			$pdf->SetXY($x, $y+5);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			if ($trimestre == '00' or $trimestre == "01") {
				$pdf->Row(array('', '', number_format($sum_formulado, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actual-$sum_pagado), 2, ',', '.')));
			}else{
				$pdf->Row(array('', '', number_format($sum_presupuestaria_anterior, 2, ',', '.'), number_format($sum_financiera_anterior, 2, ',', '.'), number_format($sum_modificacion, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format($sum_compromiso, 2, ',', '.'), number_format($sum_causado, 2, ',', '.'), number_format(($sum_actual-$sum_compromiso), 2, ',', '.'), number_format($sum_pagado, 2, ',', '.'), number_format(($sum_actualf-$sum_pagado), 2, ',', '.')));
			}
		break;
// FIN CONSOLOIDADO ONAPRE



	}




//	----------------------------------------
//	FIN CUERPO DE LOS REPORTES
//	----------------------------------------

$pdf->Output();
?>
