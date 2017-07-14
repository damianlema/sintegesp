<?php
/*****************************************************************************
 * @Consulta para cargar lista de traslados presupuestarios
 * @versión: 1.0
 * @fecha creación:
 * @autor:
 ******************************************************************************
 * @fecha modificacion
 * @autor
 * @descripcion
 ******************************************************************************/

/**
 *
 * */
class ListaPresupuesto
{
    private $tipo_presupuesto_fijo;
    private $fuente_financiamiento_fijo;
    private $categoria_programatica_fijo;

    public function __construct()
    {

        $db                                = new Conexion();
        $sql                               = $db->query("SELECT * FROM configuracion WHERE status='a'");
        $registros                         = $db->recorrer($sql);
        $this->tipo_presupuesto_fijo       = $registros['idtipo_presupuesto'];
        $this->fuente_financiamiento_fijo  = $registros['idfuente_financiamiento'];
        $this->categoria_programatica_fijo = $registros['idcategoria_programatica'];

    }

    //metodo para cargar el select Tipos de Presupuesto
    public function llenaTipoPresupuesto()
    {

        $db  = new Conexion();
        $sql = $db->query("SELECT * FROM tipo_presupuesto");
        while ($registros = $db->recorrer($sql)) {
            ?>
			<option <?php echo 'value="' . $registros["idtipo_presupuesto"] . '"';
            if ($registros["idtipo_presupuesto"] == $this->tipo_presupuesto_fijo) {echo ' selected';}
            ?>>
				<?=$registros["denominacion"]?>
			</option>
			<?
        }

    }

    //Metodo para cargar el select de Fuentes de Financiamiento
    public function llenaFuenteFinanciamiento()
    {

        $db  = new Conexion();
        $sql = $db->query("SELECT * FROM fuente_financiamiento");
        while ($registros = $db->recorrer($sql)) {
            ?>
			<option <?php echo 'value="' . $registros["idfuente_financiamiento"] . '"';
            if ($registros["idfuente_financiamiento"] == $this->fuente_financiamiento_fijo) {echo ' selected';}
            ?>>
				<?=$registros["denominacion"]?>
			</option>
			<?
        }

    }

    //Metodo para cargar el select de Categorias Programaticas
    public function llenaCategoriaProgramatica()
    {

        $db  = new Conexion();
        $sql = $db->query("	SELECT  categoria_programatica.idcategoria_programatica,
									categoria_programatica.codigo,
									unidad_ejecutora.denominacion,
									categoria_programatica.idunidad_ejecutora,
									unidad_ejecutora.idunidad_ejecutora
							FROM categoria_programatica, unidad_ejecutora
							WHERE categoria_programatica.idunidad_ejecutora = unidad_ejecutora.idunidad_ejecutora
							ORDER BY categoria_programatica.codigo");
        ?>
		<option value="0">.:: Seleccione ::.</option>
		<?php

        while ($registros = $db->recorrer($sql)) {
            ?>
			<option <?php echo 'value="' . $registros["idcategoria_programatica"] . '"';
            if ($registros["idcategoria_programatica"] == $this->categoria_programatica_fijo) {echo ' selected';}
            ?>>
				<?=$registros["codigo"]?> - <?=$registros["denominacion"]?>
			</option>
			<?
        }

    }

    //Metodo para buscar y llenar dataTable con resultados de busqueda
    //
    public function buscarListaPresupuesto()
    {
        $texto_buscar           = real_escape_string($_POST['texto_busqueda']);
        $tipo_presupuesto       = $_POST["tipo_presupuesto"];
        $fuente_financiamiento  = $_POST["fuente_financiamiento"];
        $categoria_programatica = $_POST["categoria_programatica"];

        $filtro = "";

        if ($fuente_financiamiento != "") {
            $filtro .= " (maestro_presupuesto.idfuente_financiamiento = '" . $fuente_financiamiento . "')";
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

        $sql = "  (SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria,
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

        $sql_suma = $sql;
        $par      = 0;
        $gen      = 0;
        $esp      = 0;
        $sub      = 0;

        $db  = new Conexion();
        $reg = $db->query($sql_suma);

        if ($db->rows($reg) > 0) {

            while ($field = $db->recorrer($reg)) {
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

            //AQUI SE DEBE LLENAR LA TABLA CON EL RESULTADO DE LA BUSQUEDA
        } else {
            echo "vacio";
        }
    }

}
