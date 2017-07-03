<?php
/**
 *
 *     "lista_presupuestos.php" Listado de Presupuestos para seleccionarlo
 *    Version: 1.0.1
 *    Fecha Ultima Modificacion: 28/10/2008
 *    Autor: Hector Lema
 *
 */
 
 
ob_start();
session_start();
include_once "../../conf/conex.php";
include "../../funciones/funciones.php";
$conexion_db        = conectarse();
$existen_registros  = 1;
$buscar_registros   = $_GET["busca"];
$m                  = $_GET["m"];
$guardo             = $_GET["g"];
$cuerpo             = $_GET["cuerpo"];
$juntos             = $_GET["j"];
$idcreditoadicional = $_GET["i"];
$filtra             = $_GET["llama"];

$sql_configuracion = mysql_query("select * from configuracion
											where status='a'"
    , $conexion_db);
$registro_configuracion       = mysql_fetch_assoc($sql_configuracion);
$anio_fijo                    = $registro_configuracion["anio_fiscal"];
$idtipo_presupuesto_fijo      = $registro_configuracion["idtipo_presupuesto"];
$idfuente_financiamiento_fijo = $registro_configuracion["idfuente_financiamiento"];

$sqltp = "select * from tipo_presupuesto where status='a'";

$sql_tipo_presupuesto = mysql_query($sqltp, $conexion_db);

$sql_fuente_financiamiento = mysql_query("select * from fuente_financiamiento
												where status='a'"
    , $conexion_db);

$sql_categoria_programatica = mysql_query("select * from categoria_programatica
												where status='a'
												order by codigo"
    , $conexion_db);

if ($_POST) {
    $texto_buscar           = $_POST["textoabuscar"];
    $campo_busqueda         = $_POST["tipobusqueda"];
    $anio                   = $_POST["anio"];
    $tipo_presupuesto       = $_POST["tipo_presupuesto"];
    $fuente_financiamiento  = $_POST["fuente_financiamiento"];
    $categoria_programatica = $_REQUEST["categoria_programatica"];
    $filtro                 = "";

    if ($anio_fiscal != "") {
        $filtro .= " (maestro_presupuesto.anio = '" . $anio_fiscal . "')";
    }

    if ($fuente_financiamiento != "") {
        $filtro .= " AND (maestro_presupuesto.idfuente_financiamiento = '" . $fuente_financiamiento . "')";
    }

    if ($tipo_presupuesto != "") {
        $filtro .= " AND (maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "')";
    }

    if ($categoria_programatica != "") {
        $filtro .= " AND (maestro_presupuesto.idcategoria_programatica = '" . $categoria_programatica . "')";
    }

    if ($texto_buscar != "") {
        $filtro = $filtro . " and (clasificador_presupuestario.codigo_cuenta like '%" . $texto_buscar . "%'
								or clasificador_presupuestario.denominacion like '%" . $texto_buscar . "%')";
    }

    $sql = "	(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria,
					  maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
					  categoria_programatica.codigo AS CodCategoria,
					  unidad_ejecutora.denominacion AS Unidad,
					  tipo_presupuesto.denominacion AS TipoPresupuesto,
					  fuente_financiamiento.denominacion AS FuenteFinanciamiento,
					  clasificador_presupuestario.partida AS Par,
					  clasificador_presupuestario.generica AS Gen,
					  clasificador_presupuestario.especifica AS Esp,
					  clasificador_presupuestario.sub_especifica AS Sesp,
					  clasificador_presupuestario.denominacion AS NomPartida,
					  clasificador_presupuestario.codigo_cuenta,
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
      						AND cad.estado='procesado') AS MCredito1,

					(SELECT SUM(pdp.monto_debitar)
      					FROM partidas_disminucion_presupuesto pdp
					  	INNER JOIN disminucion_presupuesto dip ON (pdp.iddisminucion_presupuesto=dip.iddisminucion_presupuesto)
					  	WHERE pdp.idmaestro_presupuesto=IdPresupuesto
							AND dip.estado='procesado') AS MDisminucion1,

					(SELECT SUM(prt.monto_acreditar)
						FROM partidas_receptoras_traslado prt
						INNER JOIN traslados_presupuestarios trpa ON (prt.idtraslados_presupuestarios=trpa.idtraslados_presupuestarios)
						WHERE prt.idmaestro_presupuesto=IdPresupuesto
							AND trpa.estado='procesado') AS MReceptora1,

					(SELECT SUM(pct.monto_debitar)
						FROM partidas_cedentes_traslado pct
						INNER JOIN traslados_presupuestarios trpd ON (pct.idtraslados_presupuestarios=trpd.idtraslados_presupuestarios)
						WHERE pct.idmaestro_presupuesto=IdPresupuesto
							AND trpd.estado='procesado') AS MCedentes1,

					(SELECT SUM(prr.monto_acreditar)
						FROM partidas_receptoras_rectificacion prr
						INNER JOIN rectificacion_presupuesto rpr ON (prr.idrectificacion_presupuesto=rpr.idrectificacion_presupuesto)
						WHERE prr.idmaestro_presupuesto=IdPresupuesto
							AND rpr.estado='procesado') AS MRectificacion1,

					(SELECT SUM(prec.monto_debitar)
						FROM partidas_rectificadoras prec
						INNER JOIN rectificacion_presupuesto rprec ON (prec.idrectificacion_presupuesto=rprec.idrectificacion_presupuesto)
						WHERE prec.idmaestro_presupuesto=IdPresupuesto
							AND rprec.estado='procesado') AS MRectificadora1,

					(SELECT SUM(rcp.aumento_periodo)
						FROM rendicion_cuentas_partidas rcp
						INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							) AS MRendicionAumento1,

					(SELECT SUM(rcp.disminucion_periodo)
						FROM rendicion_cuentas_partidas rcp
						INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
						FROM rendicion_cuentas_partidas rcp
						INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
						FROM rendicion_cuentas_partidas rcp
						INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
						FROM rendicion_cuentas_partidas rcp
						INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							) AS MRendicionPagados1,

					(SELECT SUM(pocs.monto)
						FROM partidas_orden_compra_servicio pocs
						INNER JOIN orden_compra_servicio ocs ON (pocs.idorden_compra_servicio=ocs.idorden_compra_servicio)
							WHERE
									pocs.idmaestro_presupuesto = IdPresupuesto AND
									(ocs.estado = 'procesado' OR ocs.estado = 'conformado'
									 OR ocs.estado = 'pagado' OR ocs.estado = 'ordenado' OR ocs.estado = 'parcial')) AS CompraCompromisoI,

					(SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
						INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
						INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.compromete = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada'
										or op.estado = 'conformado' or op.estado = 'parcial')) AS PagoCompromisoI,

					(SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
						INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
						INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.causa = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada'
										or op.estado = 'conformado' or op.estado = 'parcial')) AS CausaI,

					(SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
						INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
						INNER JOIN pagos_financieros pf ON (op.idorden_pago = pf.idorden_pago AND pf.estado <> 'anulado')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada'
										or op.estado = 'conformado' or op.estado = 'parcial') ) AS PagadoI,

					  ordinal.codigo AS codordinal,
					  ordinal.denominacion AS nomordinal

				FROM
					  maestro_presupuesto
					  INNER JOIN categoria_programatica ON (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica)
					  INNER JOIN unidad_ejecutora ON (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)
					  INNER JOIN clasificador_presupuestario ON (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario)
					  INNER JOIN ordinal ON (maestro_presupuesto.idordinal = ordinal.idordinal)
					  INNER JOIN fuente_financiamiento ON (maestro_presupuesto.idfuente_financiamiento = fuente_financiamiento.idfuente_financiamiento)
					  INNER JOIN tipo_presupuesto ON (maestro_presupuesto.idtipo_presupuesto = tipo_presupuesto.idtipo_presupuesto)
				WHERE
					(clasificador_presupuestario.sub_especifica <> '00') and
					$filtro
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				UNION


				(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria,
					  maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
					  categoria_programatica.codigo AS CodCategoria,
					  unidad_ejecutora.denominacion AS Unidad,
					  tipo_presupuesto.denominacion AS TipoPresupuesto,
					  fuente_financiamiento.denominacion AS FuenteFinanciamiento,
					  clasificador_presupuestario.partida AS Par,
					  clasificador_presupuestario.generica AS Gen,
					  clasificador_presupuestario.especifica AS Esp,
					  clasificador_presupuestario.sub_especifica AS Sesp,
					  clasificador_presupuestario.denominacion AS NomPartida,
					  clasificador_presupuestario.codigo_cuenta,
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
      						AND cad.estado='procesado') AS MCredito1,

					(SELECT SUM(pdp.monto_debitar)
      					FROM partidas_disminucion_presupuesto pdp
					  INNER JOIN disminucion_presupuesto dip ON (pdp.iddisminucion_presupuesto=dip.iddisminucion_presupuesto)
					  WHERE pdp.idmaestro_presupuesto=IdPresupuesto
							AND dip.estado='procesado') AS MDisminucion1,

					(SELECT SUM(prt.monto_acreditar)
						FROM partidas_receptoras_traslado prt
						INNER JOIN traslados_presupuestarios trpa ON (prt.idtraslados_presupuestarios=trpa.idtraslados_presupuestarios)
						WHERE prt.idmaestro_presupuesto=IdPresupuesto
							AND trpa.estado='procesado') AS MReceptora1,

					(SELECT SUM(pct.monto_debitar)
						FROM partidas_cedentes_traslado pct
						INNER JOIN traslados_presupuestarios trpd ON (pct.idtraslados_presupuestarios=trpd.idtraslados_presupuestarios)
						WHERE pct.idmaestro_presupuesto=IdPresupuesto
							AND trpd.estado='procesado') AS MCedentes1,
					(SELECT SUM(prr.monto_acreditar)
						FROM partidas_receptoras_rectificacion prr
						INNER JOIN rectificacion_presupuesto rpr ON (prr.idrectificacion_presupuesto=rpr.idrectificacion_presupuesto)
						WHERE prr.idmaestro_presupuesto=IdPresupuesto
							AND rpr.estado='procesado') AS MRectificacion1,
					(SELECT SUM(prec.monto_debitar)
						FROM partidas_rectificadoras prec
						INNER JOIN rectificacion_presupuesto rprec ON (prec.idrectificacion_presupuesto=rprec.idrectificacion_presupuesto)
						WHERE prec.idmaestro_presupuesto=IdPresupuesto
							AND rprec.estado='procesado') AS MRectificadora1,

					(SELECT SUM(rcp.aumento_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
      						) AS MRendicionAumento1,

					(SELECT SUM(rcp.disminucion_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							) AS MRendicionDisminucion1,

					(SELECT SUM(rcp.total_compromisos_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							) AS MRendicionCompromiso1,

					(SELECT SUM(rcp.total_causados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							) AS MRendicionCausado1,

					(SELECT SUM(rcp.total_pagados_periodo)
					FROM rendicion_cuentas_partidas rcp
					INNER JOIN rendicion_cuentas rc ON (rcp.idrendicion_cuentas=rc.idrendicion_cuentas)
						WHERE rcp.idmaestro_presupuesto=IdPresupuesto
							) AS MRendicionPagados1,

					  (SELECT SUM(pocs.monto)
						FROM partidas_orden_compra_servicio pocs
							INNER JOIN orden_compra_servicio ocs ON (pocs.idorden_compra_servicio=ocs.idorden_compra_servicio)
								WHERE
									pocs.idmaestro_presupuesto = IdPresupuesto AND
									(ocs.estado = 'procesado' OR ocs.estado = 'conformado'
										OR ocs.estado = 'pagado' OR ocs.estado = 'ordenado' OR ocs.estado = 'parcial')) AS CompraCompromisoI,

					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.compromete = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada'
										or op.estado = 'conformado' or op.estado = 'parcial')) AS PagoCompromisoI,

					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN tipos_documentos td ON (op.tipo = td.idtipos_documentos AND td.causa = 'si')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada'
										or op.estado = 'conformado' or op.estado = 'parcial')) AS CausaI,

					  (SELECT SUM(pop.monto)
						FROM partidas_orden_pago pop
							INNER JOIN orden_pago op ON (pop.idorden_pago = op.idorden_pago)
							INNER JOIN pagos_financieros pf ON (op.idorden_pago = pf.idorden_pago AND pf.estado <> 'anulado')
								WHERE
									pop.idmaestro_presupuesto = IdPresupuesto AND
									(op.estado = 'procesado' or op.estado = 'pagada'
										or op.estado = 'conformado' or op.estado = 'parcial')) AS PagadoI,

					  ordinal.codigo AS codordinal,
					  ordinal.denominacion AS nomordinal

				FROM
					  maestro_presupuesto
					  INNER JOIN categoria_programatica ON (maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica)
					  INNER JOIN unidad_ejecutora ON (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)
					  INNER JOIN clasificador_presupuestario ON (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario)
					  INNER JOIN ordinal ON (maestro_presupuesto.idordinal = ordinal.idordinal)
					  INNER JOIN fuente_financiamiento ON (maestro_presupuesto.idfuente_financiamiento = fuente_financiamiento.idfuente_financiamiento)
					  INNER JOIN tipo_presupuesto ON (maestro_presupuesto.idtipo_presupuesto = tipo_presupuesto.idtipo_presupuesto)
				WHERE
					(clasificador_presupuestario.sub_especifica='00') and
					$filtro
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				UNION

				(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria,
						maestro_presupuesto.idclasificador_presupuestario AS idPartida,
						categoria_programatica.codigo AS CodCategoria,
						unidad_ejecutora.denominacion AS Unidad,
						tipo_presupuesto.denominacion AS TipoPresupuesto,
					  	fuente_financiamiento.denominacion AS FuenteFinanciamiento,
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
						clasificador_presupuestario.codigo_cuenta,
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
					  INNER JOIN fuente_financiamiento ON (maestro_presupuesto.idfuente_financiamiento = fuente_financiamiento.idfuente_financiamiento)
					  INNER JOIN tipo_presupuesto ON (maestro_presupuesto.idtipo_presupuesto = tipo_presupuesto.idtipo_presupuesto)
				WHERE
					(clasificador_presupuestario.sub_especifica='00') and
					$filtro
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))

				UNION

				(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria,
						maestro_presupuesto.idclasificador_presupuestario AS idPartida,
						categoria_programatica.codigo AS CodCategoria,
						unidad_ejecutora.denominacion AS Unidad,
						tipo_presupuesto.denominacion AS TipoPresupuesto,
					  	fuente_financiamiento.denominacion AS FuenteFinanciamiento,
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
						clasificador_presupuestario.codigo_cuenta,
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
					  INNER JOIN fuente_financiamiento ON (maestro_presupuesto.idfuente_financiamiento = fuente_financiamiento.idfuente_financiamiento)
					  INNER JOIN tipo_presupuesto ON (maestro_presupuesto.idtipo_presupuesto = tipo_presupuesto.idtipo_presupuesto)
				WHERE
					(clasificador_presupuestario.sub_especifica='00') and
					$filtro
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";

    $sql_suma   = $sql;
    $par        = 0;
    $gen        = 0;
    $esp        = 0;
    $sub        = 0;
    $query_suma = mysql_query($sql_suma) or die($sql_suma . mysql_error());

    if (mysql_num_rows($query_suma) <= 0) {$existen_registros = 1;} else { $existen_registros = 0;}

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
    $existen_registros = 0;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>


<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
function ponPresupuesto(idPresupuesto){
	m=document.buscar.modoactual.value
	//cuerpo=document.mostrar.cuerpo.value
	opener.document.forms[0].idmaestropresupuesto.value=idPresupuesto
	opener.document.forms[0].emergente.value="true"
	opener.document.forms[0].modoactual.value=m
	opener.document.forms[0].guardo.value=true
	//opener.document.forms[0].cuerpo.value=cuerpo
	opener.document.forms[0].juntos.value=document.buscar.juntos.value
	opener.document.forms[0].submit()
	window.close()
}
</SCRIPT>
</head>

	<body>
	<br>
	<h2 align=center>Listado de Presupuestos</h2>
	<h2 class="sqlmVersion"></h2>
	<br>
	<form name="buscar" action="lista_presupuestos.php" method="POST">
	<input type="hidden" name="modoactual" id="modoactual" <?php echo 'value="' . $m . '"'; ?>>
	<input type="hidden" name="guardo" id="guardo" <?php echo 'value="' . $guardo . '"'; ?>>
	<input type="hidden" name="nro" id="nro" <?php echo 'value="' . $idCredito . '"'; ?>>
	<input type="hidden" name="juntos" id="juntos" <?php echo 'value="' . $juntos . '"'; ?>>
	<input type="hidden" name="idcreditos_adicionales" id="idcreditos_adicionales" <?php echo 'value="' . $idcreditoadicional . '"'; ?>>
	<input type="hidden" name="cuerpo" id="cuerpo" <?php echo 'value="' . $cuerpo . '"'; ?>>
	<h4 align=center>Rango a Mostrar</h4>

	<table align=center cellpadding=2 cellspacing=0 width="90%">
		<tr>
			<td align='right' class='viewPropTitle'>A&ntilde;o:</td>
			<td class='viewProp' style="width:7%">
				<select name="anio" style="width:90%" disabled="disabled">
                        <?
anio_fiscal();
?>
				</select>			</td>
			<td align='right' class='viewPropTitle' style="width:15%">Tipo de Presupuesto:</td>
			<td class='viewProp' style="width:15%">
				<select name="tipo_presupuesto" style="width:98%">
					<option value="">&nbsp;</option>
					<?php
while ($rowtipo_presupuesto = mysql_fetch_array($sql_tipo_presupuesto)) {
    ?>
									<option <?php echo 'value="' . $rowtipo_presupuesto["idtipo_presupuesto"] . '"';if ($rowtipo_presupuesto["idtipo_presupuesto"] == $idtipo_presupuesto_fijo) {echo ' selected';} ?>>
										<?php echo $rowtipo_presupuesto["denominacion"]; ?>									</option>
					<?php
}
?>
				</select>			</td>

			<td align='right' class='viewPropTitle' style="width:18%">Fuente de Financiamiento:</td>
			<td class='viewProp' style="width:20%">
				<select name="fuente_financiamiento" style="width:98%">
					<option value="">&nbsp;</option>
					<?php
while ($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)) {
    ?>
									<option <?php echo 'value="' . $rowfuente_financiamiento["idfuente_financiamiento"] . '"';if ($rowfuente_financiamiento["idfuente_financiamiento"] == $idfuente_financiamiento_fijo) {echo ' selected';} ?>>
										<?php echo $rowfuente_financiamiento["denominacion"]; ?>									</option>
					<?php
}
?>
				</select>			</td>

			<td align='right' class='viewPropTitle' style="width:11%">Categoria Pro.:</td>
			<td class='viewProp' style="width:13%">
				<select name="categoria_programatica" style="width:95%">
					<option value="">&nbsp;</option>
					<?php
while ($rowcategoria_programatica = mysql_fetch_array($sql_categoria_programatica)) {
    ?>
									<option <?php echo 'value="' . $rowcategoria_programatica["idcategoria_programatica"] . '"'; ?>>
										<?php echo $rowcategoria_programatica["codigo"]; ?>									</option>
					<?php
}
?>
				</select>			</td>
	  </tr>
	</table>
	<br>
	<h2 class="sqlmVersion"></h2>
	<br>

	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Buscar:</td>
			<td class='viewProp'><input type="text" name="textoabuscar" maxlength="60" size="60"></td>
			<td>
				<input align=center class="button" name="buscar" type="submit" value="Buscar">
			</td>
		</tr>
	</table>
	</form>
	<br>
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="95%">
				<tr>
					<td>
						<form name="grilla" action="lista_presupuestos.php" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<td width="2%" align="center" class="Browse">A&ntilde;o</td>
								  <td width="2%" align="center" class="Browse">Tipo</td>
								  <td width="7%" align="center" class="Browse">Fuente Financiamiento</td>
								  <td width="7%" align="center" class="Browse">Categoria Programatica</td>
								  <td width="6%" align="center" class="Browse">Partida</td>
                                  <td width="55%" align="center" class="Browse">Denominaci&oacute;n</td>
								  <td width="9%" align="center" class="Browse">Monto Actual</td>
								  <td width="9%" align="center" class="Browse">Disponible</td>
								  <td width="3%" colspan="2" align="center" class="Browse">Acci&oacute;n</td>
							  </tr>
							</thead>
							<?php //  llena la grilla con los registros de la tabla de programas
if ($_POST) {
    if ($existen_registros == 0) {

        $par   = 0;
        $gen   = 0;
        $esp   = 0;
        $sub   = 0;
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($llenar_grilla = mysql_fetch_array($query)) {
            $cp = $llenar_grilla["IdPresupuesto"];
            //echo $cp;
            if ($llenar_grilla["Tipo"] == "partida" and $texto_buscar == '') {
                $par++;
                $modificado    = $aumentado_partidaI[$par] - $disminuido_partidaI[$par];
                $actual0       = $llenar_grilla["Formulado"] + $modificado;
                $comprometidoI = $comprometido_partidaI[$par];
                $disponible    = $actual0 - $comprometidoI;

                ?>

			                                <tr bgcolor='#F5BC42' onMouseOver="setRowColor(this, 0, 'over', '#F5BC42', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#F5BC42', '#EAFFEA', '#FFFFAA')">
												<?php
echo "<td align='center' class='Browse' width='5%'>" . $anio . "</td>";
                echo "<td align='center' class='Browse' width='6%'>" . $llenar_grilla["TipoPresupuesto"] . "</td>";
                echo "<td align='left' class='Browse'width='10%'>" . $llenar_grilla["FuenteFinanciamiento"] . "</td>";
                echo "<td align='center' class='Browse' width='8%'>" . $llenar_grilla["CodCategoria"] . "</td>";
                echo "<td align='left' class='Browse' width='12%'>" . $llenar_grilla["Par"]
                    . "." . $llenar_grilla["Gen"]
                    . "." . $llenar_grilla["Esp"]
                    . "." . $llenar_grilla["Sesp"]
                    . "  " . $llenar_grilla["codordinal"] . "</td>";
                if ($llenar_grilla["codordinal"] != 0000) {
                    echo "<td align='left' class='Browse'>" . $llenar_grilla["NomPartida"] . "</td>";
                } else {
                    echo "<td align='left' class='Browse'>" . $llenar_grilla["NomPartida"] . "</td>";
                }
                echo "<td align='right' class='Browse' width='10%'>" . number_format($actual0, 2, ",", ".") . "</td>";
                echo "<td align='right' class='Browse' width='10%'>" . number_format($disponible, 2, ",", ".") . "</td>";
                echo "<td align='center' class='Browse' width='5%'> <button name='poner_ppto' type='button' style='background-color:#e7dfce;border-style:none;'></td>";
                echo "</tr>";
            }
            if ($llenar_grilla["Tipo"] == "generica" and $texto_buscar == '') {
                $gen++;
                $modificado    = $aumentado_genericaI[$gen] - $disminuido_genericaI[$gen];
                $actual0       = $llenar_grilla["Formulado"] + $modificado;
                $comprometidoI = $comprometido_genericaI[$gen];
                $disponible    = $actual0 - $comprometidoI;

                ?>

			                                <tr bgcolor='#E8C987' onMouseOver="setRowColor(this, 0, 'over', '#E8C987', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#E8C987', '#EAFFEA', '#FFFFAA')">
												<?php
echo "<td align='center' class='Browse' width='5%'>" . $anio . "</td>";
                echo "<td align='center' class='Browse' width='6%'>" . $llenar_grilla["TipoPresupuesto"] . "</td>";
                echo "<td align='left' class='Browse'width='10%'>" . $llenar_grilla["FuenteFinanciamiento"] . "</td>";
                echo "<td align='center' class='Browse' width='8%'>" . $llenar_grilla["CodCategoria"] . "</td>";
                echo "<td align='left' class='Browse' width='12%'>" . $llenar_grilla["Par"]
                    . "." . $llenar_grilla["Gen"]
                    . "." . $llenar_grilla["Esp"]
                    . "." . $llenar_grilla["Sesp"]
                    . "  " . $llenar_grilla["codordinal"] . "</td>";
                if ($llenar_grilla["codordinal"] != 0000) {
                    echo "<td align='left' class='Browse'>" . $llenar_grilla["NomPartida"] . "</td>";
                } else {
                    echo "<td align='left' class='Browse'>" . $llenar_grilla["NomPartida"] . "</td>";
                }
                echo "<td align='right' class='Browse' width='10%'>" . number_format($actual0, 2, ",", ".") . "</td>";
                echo "<td align='right' class='Browse' width='10%'>" . number_format($disponible, 2, ",", ".") . "</td>";
                echo "<td align='center' class='Browse' width='5%'> <button name='poner_ppto' type='button' style='background-color:#e7dfce;border-style:none;'></td>";
                echo "</tr>";
            }
            if ($llenar_grilla["Tipo"] == "especifica") {
                $esp++;
                $modificado    = $aumentado_especificaI[$esp] - $disminuido_especificaI[$esp];
                $actual0       = $llenar_grilla["Formulado"] + $modificado;
                $comprometidoI = $comprometido_especificaI[$esp];
                $disponible    = $actual0 - $comprometidoI;

                ?>

			                                <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="ponPresupuesto('<?=$cp?>')">
												<?php
echo "<td align='center' class='Browse' width='5%'>" . $anio . "</td>";
                echo "<td align='center' class='Browse' width='6%'>" . $llenar_grilla["TipoPresupuesto"] . "</td>";
                echo "<td align='left' class='Browse'width='10%'>" . $llenar_grilla["FuenteFinanciamiento"] . "</td>";
                echo "<td align='center' class='Browse' width='8%'>" . $llenar_grilla["CodCategoria"] . "</td>";
                echo "<td align='left' class='Browse' width='12%'>" . $llenar_grilla["Par"]
                    . "." . $llenar_grilla["Gen"]
                    . "." . $llenar_grilla["Esp"]
                    . "." . $llenar_grilla["Sesp"]
                    . "  " . $llenar_grilla["codordinal"] . "</td>";
                if ($llenar_grilla["codordinal"] != 0000) {
                    echo "<td align='left' class='Browse'>" . $llenar_grilla["NomPartida"] . "</td>";
                } else {
                    echo "<td align='left' class='Browse'>" . $llenar_grilla["NomPartida"] . "</td>";
                }
                echo "<td align='right' class='Browse' width='10%'>" . number_format($actual0, 2, ",", ".") . "</td>";
                echo "<td align='right' class='Browse' width='10%'>" . number_format($disponible, 2, ",", ".") . "</td>";
                echo "<td align='center' class='Browse' width='5%'> <button name='poner_ppto' type='button' style='background-color:#e7dfce;border-style:none;cursor:pointer;' onclick='ponPresupuesto(" . $cp . ")'><img src='../../imagenes/validar.png'></td>";
                echo "</tr>";
            }
            if ($llenar_grilla["Tipo"] == "subespecifica") {
                $sub++;
                $modificado    = $aumentado_subespecificaI[$sub] - $disminuido_subespecificaI[$sub];
                $actual0       = $llenar_grilla["Formulado"] + $modificado;
                $comprometidoI = $comprometido_subespecificaI[$sub];
                $disponible    = $actual0 - $comprometidoI;

                ?>

			                                <tr bgcolor='#FFF0D0' onMouseOver="setRowColor(this, 0, 'over', '#FFF0D0', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFF0D0', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="ponPresupuesto('<?=$cp?>')">
												<?php
echo "<td align='center' class='Browse' width='5%'>" . $anio . "</td>";
                echo "<td align='center' class='Browse' width='6%'>" . $llenar_grilla["TipoPresupuesto"] . "</td>";
                echo "<td align='left' class='Browse'width='10%'>" . $llenar_grilla["FuenteFinanciamiento"] . "</td>";
                echo "<td align='center' class='Browse' width='8%'>" . $llenar_grilla["CodCategoria"] . "</td>";
                echo "<td align='left' class='Browse' width='12%'>" . $llenar_grilla["Par"]
                    . "." . $llenar_grilla["Gen"]
                    . "." . $llenar_grilla["Esp"]
                    . "." . $llenar_grilla["Sesp"]
                    . "  " . $llenar_grilla["codordinal"] . "</td>";
                if ($llenar_grilla["codordinal"] != 0000) {
                    echo "<td align='left' class='Browse'>" . $llenar_grilla["NomPartida"] . "</td>";
                } else {
                    echo "<td align='left' class='Browse'>" . $llenar_grilla["NomPartida"] . "</td>";
                }
                echo "<td align='right' class='Browse' width='10%'>" . number_format($actual0, 2, ",", ".") . "</td>";
                echo "<td align='right' class='Browse' width='10%'>" . number_format($disponible, 2, ",", ".") . "</td>";
                echo "<td align='center' class='Browse' width='5%'> <button name='poner_ppto' type='button' style='background-color:#F6EDDA;border-style:none;cursor:pointer;' onclick='ponPresupuesto(" . $cp . ")'><img src='../../imagenes/validar.png'></td>";
                echo "</tr>";
            }
        }
    }
}
//}
 ?>
						</table>
						</form>
					</td>
				</tr>
			</table>
		</div>
</body>
</html>