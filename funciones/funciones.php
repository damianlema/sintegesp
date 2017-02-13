<?php

function mensaje($texto)
{
    ?>
		<script>
			alert("<?php echo $texto; ?>");
        </script>
	<?php
}

function redirecciona($texto)
{
    ?>
		<script>
			window.location.href="<?php echo $texto; ?>";
        </script>
	<?php
}

function registra_transaccion($tipo, $login, $fh, $pc, $tabla)
{
    mysql_query("insert into registro_transacciones
								(tipo,usuario,fechayhora,estacion,tabla)
								values ('" . $tipo . "','$login','$fh','$pc','$tabla')"
    ) or die(mysql_error());
}

function tiempo_trans($hora1, $hora2)
{
    $separar[1]                     = explode(':', $hora1);
    $separar[2]                     = explode(':', $hora2);
    $total_minutos_trasncurridos[1] = ($separar[1][0] * 60) + $separar[1][1];
    $total_minutos_trasncurridos[2] = ($separar[2][0] * 60) + $separar[2][1];
    $total_minutos_trasncurridos    = $total_minutos_trasncurridos[2] - $total_minutos_trasncurridos[1];
    return ($total_minutos_trasncurridos);
}

function anio_fiscal()
{
    $sql        = "SELECT anio_fiscal FROM configuracion";
    $query_conf = mysql_query($sql) or die($sql . mysql_error());
    $conf       = mysql_fetch_array($query_conf);
    echo "<option value='" . $conf['anio_fiscal'] . "'>" . $conf['anio_fiscal'] . "</option>";
}

function consultarDisponibilidad($id_maestro)
{
    //RECALCULAR LA PARTIDA PARA VALIDARLA
    $sql_maestro_presupuesto = mysql_query("select * from maestro_presupuesto WHERE idRegistro = '" . $id_maestro . "'") or die("ERROR SELECCIONAR MAESTRO: " . mysql_error());
    while ($bus_maestro_presupuesto = mysql_fetch_array($sql_maestro_presupuesto)) {
        $sql_clasificador_presupuestario = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '" .
            $bus_maestro_presupuesto["idclasificador_presupuestario"] . "'");
        $bus_clasificador_presupuestario = mysql_fetch_array($sql_clasificador_presupuestario);
        //echo "<strong>&nbsp;".$i."-&nbsp;</strong>".$bus_clasificador_presupuestario["partida"]." ".$bus_clasificador_presupuestario["generica"]." ".    $bus_clasificador_presupuestario["especifica"]." ".$bus_clasificador_presupuestario["sub_especifica"]." ".$bus_clasificador_presupuestario["denominacion"]."<br>";

        $partida        = $bus_clasificador_presupuestario["partida"];
        $generica       = $bus_clasificador_presupuestario["generica"];
        $especifica     = $bus_clasificador_presupuestario["especifica"];
        $sub_especifica = $bus_clasificador_presupuestario["sub_especifica"];
        $i++;

        $sql_suma_orden_compra = mysql_query("select SUM(partidas_orden_compra_servicio.monto) as total_suma,
											orden_compra_servicio.idorden_compra_servicio,
											partidas_orden_compra_servicio.idpartidas_orden_compra_servicio
										from partidas_orden_compra_servicio,
											orden_compra_servicio
										where partidas_orden_compra_servicio.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
											and partidas_orden_compra_servicio.idorden_compra_servicio = orden_compra_servicio.idorden_compra_servicio
											and (orden_compra_servicio.estado = 'procesado' ||
											orden_compra_servicio.estado = 'conformado' ||
											orden_compra_servicio.estado = 'pagado' ||
											orden_compra_servicio.estado = 'parcial' ||
											orden_compra_servicio.estado = 'ordenado')
										group by partidas_orden_compra_servicio.idmaestro_presupuesto")
        or die("ERROR SELECCIONANDO PARTIDAS ORDEN COMPRA: " . mysql_error());

        $bus_suma_orden_compra = mysql_fetch_array($sql_suma_orden_compra);
        $total_suma_compras    = $bus_suma_orden_compra["total_suma"];

        //echo "TOTAL SUMA COMPRAS: ".$total_suma_compras."<br />";

        $sql_actualizar_maestro = mysql_query("update maestro_presupuesto
														set total_compromisos = '" . $total_suma_compras . "'
														where idRegistro = '" . $bus_maestro_presupuesto["idRegistro"] . "'")
        or die("ERROR ACTUALIZANDO MAESTRO POR COMPRAS: " . mysql_error());

        // ACTUALIZAR LOS COMPROMISOS DE ORDEN DE PAGO DIRECTO

        $sql_orden_pago_directo = mysql_query("select SUM(partidas_orden_pago.monto) as monto
															from partidas_orden_pago, tipos_documentos, orden_pago
															where partidas_orden_pago.idorden_pago = orden_pago.idorden_pago
															and orden_pago.tipo = tipos_documentos.idtipos_documentos
															and tipos_documentos.compromete = 'si'
															and partidas_orden_pago.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
															and (orden_pago.estado = 'procesado' or orden_pago.estado = 'pagada' or orden_pago.estado = 'conformado' or orden_pago.estado = 'parcial')") or die(mysql_error());
        $bus_orden_pago_directo  = mysql_fetch_array($sql_orden_pago_directo);
        $total_suma_pago_directo = $bus_orden_pago_directo["monto"];

        //echo "TOTAL SUMA PAGOS DIRECTOS: ".$total_suma_pago_directo."<br />";

        $sql_actualizar_maestro = mysql_query("update maestro_presupuesto
																	set total_compromisos = total_compromisos+'" . $total_suma_pago_directo . "'
																	where idRegistro = '" . $bus_maestro_presupuesto["idRegistro"] . "'")
        or die("ERROR ACTUALIZANDO MAESTRO POR COMPRAS: " . mysql_error());

        // ACTUALIZAR LOS COMPROMISOS DE ORDEN DE PAGO DIRECTO

        $sql_suma_orden_pago = mysql_query("select partidas_orden_pago.monto as total_monto,
										orden_pago.estado as estado_pago
											from
										partidas_orden_pago,
										orden_pago
											where
										partidas_orden_pago.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
										and partidas_orden_pago.idorden_pago = orden_pago.idorden_pago
										and (orden_pago.estado = 'procesado' or
										orden_pago.estado = 'pagada'
										or orden_pago.estado = 'conformado'
										or orden_pago.estado = 'parcial')
                    group by partidas_orden_pago.idorden_pago") or die("ERROR SELECCIONANDO PARTIDAS PAGOS: " . mysql_error());

        while ($bus_suma_orden_pago = mysql_fetch_array($sql_suma_orden_pago)) {

            if ($bus_suma_orden_pago["estado_pago"] == 'pagada') {
                $total_suma_pago_procesado += $bus_suma_orden_pago["total_monto"];
                $total_suma_pago_pagado += $bus_suma_orden_pago["total_monto"];
            } else {
                $total_suma_pago_procesado += $bus_suma_orden_pago["total_monto"];
            }
        }

        /*$sql_consulta_pagos_financieros = mysql_query("select * from pagos_financieros");
        while($bus_consultar_pagos_financieros = mysql_fetch_array($sql_consulta_pagos_financieros)){
        $total_suma_pago_pagado = $bus_consultar_pagos_financieros["monto_cheque"];
        }*/

        $sql_consultar_partidas_disminucion = mysql_query("select SUM(partidas_disminucion_presupuesto.monto_debitar) as total from
								partidas_disminucion_presupuesto, disminucion_presupuesto where
								partidas_disminucion_presupuesto.iddisminucion_presupuesto = disminucion_presupuesto.iddisminucion_presupuesto
								and partidas_disminucion_presupuesto.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
								and disminucion_presupuesto.estado = 'procesado'");
        $bus_consultar_partidas_disminucion = mysql_fetch_array($sql_consultar_partidas_disminucion);

        $total_partida_disminucion = $bus_consultar_partidas_disminucion["total"];

        $sql_partidas_cedentes_traslado = mysql_query("select SUM(partidas_cedentes_traslado.monto_debitar) as total from
								partidas_cedentes_traslado, traslados_presupuestarios where
								partidas_cedentes_traslado.idtraslados_presupuestarios = traslados_presupuestarios.idtraslados_presupuestarios
								and partidas_cedentes_traslado.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
								and traslados_presupuestarios.estado = 'procesado'");

        $bus_partidas_cedentes_traslado = mysql_fetch_array($sql_partidas_cedentes_traslado);

        $total_partidas_cedentes_traslado = $bus_partidas_cedentes_traslado["total"];

        $sql_partidas_rectificadoras = mysql_query("select SUM(partidas_rectificadoras.monto_debitar) as total from
									partidas_rectificadoras, rectificacion_presupuesto where
									partidas_rectificadoras.idrectificacion_presupuesto = rectificacion_presupuesto.idrectificacion_presupuesto
									and partidas_rectificadoras.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
									and rectificacion_presupuesto.estado = 'procesado'");

        $bus_partidas_rectificadoras = mysql_fetch_array($sql_partidas_rectificadoras);

        $total_partidas_rectificadoras = $bus_partidas_rectificadoras["total"];

        $total_disminuciones = $total_partida_disminucion + $total_partidas_cedentes_traslado + $total_partidas_rectificadoras;

        $sql_partidas_credito_adicional = mysql_query("select SUM(partidas_credito_adicional.monto_acreditar) as total from
										partidas_credito_adicional, creditos_adicionales
										where
										partidas_credito_adicional.idcredito_adicional = creditos_adicionales.idcreditos_adicionales
										and partidas_credito_adicional.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
										and creditos_adicionales.estado = 'procesado'") or die(mysql_error());

        $bus_partidas_credito_adicional = mysql_fetch_array($sql_partidas_credito_adicional);

        $total_partidas_credito_adicional = $bus_partidas_credito_adicional["total"];

        $sql_partidas_receptoras_rectificacion = mysql_query("select SUM(partidas_receptoras_rectificacion.monto_acreditar) as total from
						partidas_receptoras_rectificacion, rectificacion_presupuesto
						where
						partidas_receptoras_rectificacion.idrectificacion_presupuesto = rectificacion_presupuesto.idrectificacion_presupuesto
						and partidas_receptoras_rectificacion.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
						and rectificacion_presupuesto.estado = 'procesado'");
        $bus_partidas_receptoras_rectificacion = mysql_fetch_array($sql_partidas_receptoras_rectificacion);

        $total_partidas_receptoras_rectificacion = $bus_partidas_receptoras_rectificacion["total"];

        $sql_partidas_receptoras_traslado = mysql_query("select SUM(partidas_receptoras_traslado.monto_acreditar) as total from
							partidas_receptoras_traslado,traslados_presupuestarios
							where
							partidas_receptoras_traslado.idtraslados_presupuestarios = traslados_presupuestarios.idtraslados_presupuestarios and
							partidas_receptoras_traslado.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
							and traslados_presupuestarios.estado = 'procesado'") or die(mysql_error());
        $bus_partidas_receptoras_traslado = mysql_fetch_array($sql_partidas_receptoras_traslado);

        $total_partidas_receptoras_traslado = $bus_partidas_receptoras_traslado["total"];
        //echo $total_partidas_receptoras_traslado;
        $total_aumento = $total_partidas_credito_adicional + $total_partidas_receptoras_rectificacion + $total_partidas_receptoras_traslado;

        // NUEVOS CRITERIOS*************************************************************************************************************

        $sql_consultar_partidas_disminucion2 = mysql_query("select SUM(partidas_disminucion_presupuesto.monto_debitar) as total from
								partidas_disminucion_presupuesto, disminucion_presupuesto where
								partidas_disminucion_presupuesto.iddisminucion_presupuesto = disminucion_presupuesto.iddisminucion_presupuesto
								and partidas_disminucion_presupuesto.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
								and disminucion_presupuesto.estado = 'elaboracion'");
        $bus_consultar_partidas_disminucion2 = mysql_fetch_array($sql_consultar_partidas_disminucion2);

        $total_partida_disminucion = $bus_consultar_partidas_disminucion["total"];

        $sql_partidas_cedentes_traslado2 = mysql_query("select SUM(partidas_cedentes_traslado.monto_debitar) as total from
								partidas_cedentes_traslado, traslados_presupuestarios where
								partidas_cedentes_traslado.idtraslados_presupuestarios = traslados_presupuestarios.idtraslados_presupuestarios
								and partidas_cedentes_traslado.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
								and traslados_presupuestarios.estado = 'elaboracion'");

        $bus_partidas_cedentes_traslado2 = mysql_fetch_array($sql_partidas_cedentes_traslado2);

        $total_partidas_cedentes_traslado2 = $bus_partidas_cedentes_traslado2["total"];

        $sql_partidas_rectificadoras2 = mysql_query("select SUM(partidas_rectificadoras.monto_debitar) as total from
									partidas_rectificadoras, rectificacion_presupuesto where
									partidas_rectificadoras.idrectificacion_presupuesto = rectificacion_presupuesto.idrectificacion_presupuesto
									and partidas_rectificadoras.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
									and rectificacion_presupuesto.estado = 'elaboracion'");

        $bus_partidas_rectificadoras2 = mysql_fetch_array($sql_partidas_rectificadoras2);

        $total_partidas_rectificadoras2 = $bus_partidas_rectificadoras2["total"];

        $total_disminuciones2 = $total_partida_disminucion2 + $total_partidas_cedentes_traslado2 + $total_partidas_rectificadoras2;

        $sql_partidas_credito_adicional2 = mysql_query("select SUM(partidas_credito_adicional.monto_acreditar) as total from
										partidas_credito_adicional, creditos_adicionales
										where
										partidas_credito_adicional.idcredito_adicional = creditos_adicionales.idcreditos_adicionales
										and partidas_credito_adicional.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
										and creditos_adicionales.estado = 'elaboracion'") or die(mysql_error());

        $bus_partidas_credito_adicional2 = mysql_fetch_array($sql_partidas_credito_adicional2);

        $total_partidas_credito_adicional2 = $bus_partidas_credito_adicional2["total"];

        $sql_partidas_receptoras_rectificacion2 = mysql_query("select SUM(partidas_receptoras_rectificacion.monto_acreditar) as total from
						partidas_receptoras_rectificacion, rectificacion_presupuesto
						where
						partidas_receptoras_rectificacion.idrectificacion_presupuesto = rectificacion_presupuesto.idrectificacion_presupuesto
						and partidas_receptoras_rectificacion.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
						and rectificacion_presupuesto.estado = 'elaboracion'");
        $bus_partidas_receptoras_rectificacion2 = mysql_fetch_array($sql_partidas_receptoras_rectificacion2);

        $total_partidas_receptoras_rectificacion2 = $bus_partidas_receptoras_rectificacion2["total"];

        $sql_partidas_receptoras_traslado2 = mysql_query("select SUM(partidas_receptoras_traslado.monto_acreditar) as total from
							partidas_receptoras_traslado,traslados_presupuestarios
							where
							partidas_receptoras_traslado.idtraslados_presupuestarios = traslados_presupuestarios.idtraslados_presupuestarios and
							partidas_receptoras_traslado.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
							and traslados_presupuestarios.estado = 'elaboracion'");
        $bus_partidas_receptoras_traslado2 = mysql_fetch_array($sql_partidas_receptoras_traslado2);

        $total_partidas_receptoras_traslado2 = $bus_partidas_receptoras_traslado2["total"];

        $total_aumento2 = $total_partidas_credito_adicional2 + $total_partidas_receptoras_rectificacion2 + $total_partidas_receptoras_traslado2;

        // REQUISICIONES

        $sql_suma_requisicion = mysql_query("select SUM(partidas_requisiciones.monto) as total_suma,
										requisicion.idrequisicion,
										partidas_requisiciones.idpartidas_requisiciones
										from partidas_requisiciones,
										requisicion
										where partidas_requisiciones.idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'
										and partidas_requisiciones.idrequisicion = requisicion.idrequisicion
										and requisicion.estado = 'procesado'
										group by partidas_requisiciones.idmaestro_presupuesto")
        or die("ERROR SELECCIONANDO PARTIDAS REQUISICIONES: " . mysql_error());

        $bus_suma_requisicion   = mysql_fetch_array($sql_suma_requisicion);
        $total_suma_requisicion = $bus_suma_requisicion["total_suma"];
        // NUEVOS CRITERIOS*************************************************************************************************************

        $sql_consultar_rendicion = mysql_query("select * from rendicion_cuentas_partidas where idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'");
        $num_consultar_rendicion = mysql_num_rows($sql_consultar_rendicion);

        if ($num_consultar_rendicion > 0) {

            $sql_rendicion = mysql_query("select SUM(total_compromisos_periodo) as total_compromisos,
													SUM(total_causados_periodo) as total_causados,
													SUM(total_pagados_periodo) as total_pagados,
													SUM(aumento_periodo) as total_aumento,
													SUM(disminucion_periodo) as total_disminuciones
														from
													rendicion_cuentas_partidas
														where
													idmaestro_presupuesto = '" . $bus_maestro_presupuesto["idRegistro"] . "'");
            $bus_rendicion = mysql_fetch_array($sql_rendicion);

            $sql_actualizar_maestro = mysql_query("update maestro_presupuesto
															set total_compromisos = " . $bus_rendicion["total_compromisos"] . "
															where idRegistro = '" . $bus_maestro_presupuesto["idRegistro"] . "'");

            $total_suma_pago_procesado = $bus_rendicion["total_causados"];
            $total_suma_pago_pagado    = $bus_rendicion["total_pagados"];
            $total_aumento             = $bus_rendicion["total_aumento"];
            $total_disminuciones       = $bus_rendicion["total_disminuciones"];
            $total_disminuciones2      = 0;
            $total_aumento2            = 0;
            $total_suma_requisicion    = 0;

        }

        $sql_update_disminuciones_aumento = mysql_query("update maestro_presupuesto
															set total_disminucion = '" . $total_disminuciones . "',
															total_aumento = '" . $total_aumento . "',
															total_causados = '" . $total_suma_pago_procesado . "',
															total_pagados = '" . $total_suma_pago_pagado . "',
															reservado_disminuir = '" . $total_disminuciones2 . "',
															solicitud_aumento = '" . $total_aumento2 . "',
															pre_compromiso = '" . $total_suma_requisicion . "'
															where idRegistro = '" . $bus_maestro_presupuesto["idRegistro"] . "'");

        $monto_actual = $total_aumento - $total_disminuciones;

        $sql_update_monto_actual = mysql_query("update maestro_presupuesto
															set monto_actual = monto_original + total_aumento - total_disminucion
															where idRegistro = '" . $bus_maestro_presupuesto["idRegistro"] . "'");

        $total_disminuciones       = 0;
        $total_aumento             = 0;
        $total_suma_pago_procesado = 0;
        $total_suma_pago_pagado    = 0;
        $total_disminuciones2      = 0;
        $total_aumento2            = 0;
        $total_suma_requisicion    = 0;

    }

    // VALIDAR DISPONIBILIDADES **************************************************************************************
    // VALIDO SI ES ORDINAL
    $sql_es_ordinal = mysql_query("select * FROM
									maestro_presupuesto mp,
									ordinal o
										WHERE
									o.idordinal = mp.idordinal
									and o.codigo = '0000'
									and mp.idRegistro = '" . $id_maestro . "'") or die(mysql_error());
    $num_es_ordinal = mysql_num_rows($sql_es_ordinal);

    if ($num_es_ordinal > 0) {
        $es_ordinal = 'no';
    } else {
        $es_ordinal = 'si';
    }

    // VALIDO SI ES SUB ESPECIFICA
    $sql_es_sub = mysql_query("SELECT * FROM
									maestro_presupuesto mp,
									clasificador_presupuestario cp
										WHERE
									cp.idclasificador_presupuestario = mp.idclasificador_presupuestario
									and cp.sub_especifica = '00'
									and mp.idRegistro = '" . $id_maestro . "'") or die(mysql_error());
    $num_es_sub = mysql_num_rows($sql_es_sub);

    if ($num_es_sub > 0) {
        $es_sub = 'no';
    } else {
        $es_sub = 'si';
    }
    //echo "ID: ".$llenar_grilla["idRegistro_maestro"]." SUB: ".$es_sub."<br>";

    $sql_ordinal = mysql_query("select * from ordinal where codigo = '0000'");
    $bus_ordinal = mysql_fetch_array($sql_ordinal);
    // SI ES ESPECIFICA
    if ($es_sub == 'no' and $es_ordinal == 'no') {
        //echo "aqui";
        $sql_maestro = mysql_query("SELECT
					cp.partida,
					cp.generica,
					cp.especifica,
					cp.sub_especifica,
					mp.idRegistro,
					(mp.monto_actual - mp.pre_compromiso - mp.total_compromisos - mp.reservado_disminuir) as disponible,
					mp.monto_actual,
					mp.monto_original,
					mp.idcategoria_programatica,
					mp.idtipo_presupuesto,
					mp.idfuente_financiamiento,
					mp.idclasificador_presupuestario
						FROM
					maestro_presupuesto mp,
					clasificador_presupuestario cp
					WHERE
						mp.idRegistro = '" . $id_maestro . "'
						and cp.idclasificador_presupuestario = mp.idclasificador_presupuestario") or die(mysql_error());
        $bus_maestro = mysql_fetch_array($sql_maestro);

        $sql_clasificador_sub = mysql_query("SELECT * FROM
												clasificador_presupuestario
													WHERE
												partida = '" . $bus_maestro["partida"] . "'
												and generica = '" . $bus_maestro["generica"] . "'
												and especifica = '" . $bus_maestro["especifica"] . "'
												and sub_especifica != '00'") or die(mysql_error());
        $num_clasificador_sub = mysql_num_rows($sql_clasificador_sub);

        if ($num_clasificador_sub > 0) {
            while ($bus_clasificador_sub = mysql_fetch_array($sql_clasificador_sub)) {
                $sql_suma = mysql_query("SELECT
							monto_actual,
							monto_original
								FROM
							maestro_presupuesto mp
								WHERE
							idcategoria_programatica = '" . $bus_maestro["idcategoria_programatica"] . "'
							and idtipo_presupuesto = '" . $bus_maestro["idtipo_presupuesto"] . "'
							and idfuente_financiamiento = '" . $bus_maestro["idfuente_financiamiento"] . "'
							and idclasificador_presupuestario = '" . $bus_clasificador_sub["idclasificador_presupuestario"] . "'");
                $bus_suma = mysql_fetch_array($sql_suma);
                $disponible_especifica += $bus_suma["monto_actual"];
            }

        } else {
            //echo "aqui";
            $sql_maestro = mysql_query("SELECT
					(mp.monto_actual - mp.pre_compromiso - mp.total_compromisos - mp.reservado_disminuir) as disponible,
					mp.idcategoria_programatica,
					mp.idtipo_presupuesto,
					mp.idfuente_financiamiento,
					mp.idclasificador_presupuestario,
					mp.monto_actual,
					mp.monto_original
						FROM maestro_presupuesto mp
					WHERE
						idRegistro = '" . $id_maestro . "'");
            $bus_maestro = mysql_fetch_array($sql_maestro);

            $sql_ordinales = mysql_query("SELECT *
						FROM maestro_presupuesto
						WHERE
						idcategoria_programatica = '" . $bus_maestro["idcategoria_programatica"] . "'
						and idtipo_presupuesto = '" . $bus_maestro["idtipo_presupuesto"] . "'
						and idfuente_financiamiento = '" . $bus_maestro["idfuente_financiamiento"] . "'
						and idclasificador_presupuestario = '" . $bus_maestro["idclasificador_presupuestario"] . "'
						and idordinal != '" . $bus_ordinal["idordinal"] . "'");
            while ($bus_ordinales = mysql_fetch_array($sql_ordinales)) {
                $sql_suma = mysql_query("SELECT
					(monto_actual - pre_compromiso - total_compromisos - reservado_disminuir) as disponible,
					monto_actual,
					monto_original
						FROM maestro_presupuesto
					WHERE
						idcategoria_programatica = '" . $bus_ordinales["idcategoria_programatica"] . "'
						and idtipo_presupuesto = '" . $bus_ordinales["idtipo_presupuesto"] . "'
						and idfuente_financiamiento = '" . $bus_ordinales["idfuente_financiamiento"] . "'
						and idclasificador_presupuestario = '" . $bus_ordinales["idclasificador_presupuestario"] . "'
						and idordinal = '" . $bus_ordinales["idordinal"] . "'");
                $bus_suma = mysql_fetch_array($sql_suma);

                $disponible_especifica += $bus_suma["monto_original"];
            }

        }

        //echo "aqui".$disponible_especifica;
        $disponible_mostrar = ($bus_maestro["disponible"] - $disponible_especifica);

    }
    $disponible_especifica = 0;

    // SI ES SUB ESPECIFICA
    if ($es_sub == "si" and $es_ordinal == 'no') {
        //echo "aqui";
        $sql_maestro = mysql_query("SELECT
					(mp.monto_actual - mp.pre_compromiso - mp.total_compromisos - mp.reservado_disminuir) as disponible,
					mp.idcategoria_programatica,
					mp.idtipo_presupuesto,
					mp.idfuente_financiamiento,
					mp.idclasificador_presupuestario,
					mp.monto_actual,
					mp.monto_original
						FROM maestro_presupuesto mp
					WHERE
						idRegistro = '" . $id_maestro . "'");
        $bus_maestro = mysql_fetch_array($sql_maestro);

        $sql_ordinales = mysql_query("SELECT *
						FROM maestro_presupuesto
						WHERE
						idcategoria_programatica = '" . $bus_maestro["idcategoria_programatica"] . "'
						and idtipo_presupuesto = '" . $bus_maestro["idtipo_presupuesto"] . "'
						and idfuente_financiamiento = '" . $bus_maestro["idfuente_financiamiento"] . "'
						and idclasificador_presupuestario = '" . $bus_maestro["idclasificador_presupuestario"] . "'
						and idordinal != '" . $bus_ordinal["idordinal"] . "'");
        while ($bus_ordinales = mysql_fetch_array($sql_ordinales)) {
            $sql_suma = mysql_query("SELECT
				(monto_actual - pre_compromiso - total_compromisos - reservado_disminuir) as disponible,
				monto_actual,
				monto_original
					FROM maestro_presupuesto
				WHERE
					idcategoria_programatica = '" . $bus_ordinales["idcategoria_programatica"] . "'
					and idtipo_presupuesto = '" . $bus_ordinales["idtipo_presupuesto"] . "'
					and idfuente_financiamiento = '" . $bus_ordinales["idfuente_financiamiento"] . "'
					and idclasificador_presupuestario = '" . $bus_ordinales["idclasificador_presupuestario"] . "'
					and idordinal = '" . $bus_ordinales["idordinal"] . "'");
            $bus_suma = mysql_fetch_array($sql_suma);
            $disponible_especifica += $bus_suma["monto_original"];
        }

        $disponible_mostrar = ($bus_maestro["disponible"] - $disponible_especifica);
    }
    $disponible_especifica = 0;

    // SI ES ORDINAL

    if ($es_ordinal == 'si') {
        $sql_maestro_presupuesto = mysql_query("SELECT
		(monto_actual - total_compromisos - pre_compromiso - reservado_disminuir) as disponible
			FROM
		maestro_presupuesto
			WHERE
		idRegistro = '" . $id_maestro . "'") or die(mysql_error());

        $bus_maestro_presupuesto = mysql_fetch_array($sql_maestro_presupuesto);
        $disponible_mostrar      = $bus_maestro_presupuesto["disponible"];
    }

    return $disponible_mostrar;
}

function diasDiferencia($fecha1, $fecha2)
{
    //defino fecha 1
    $fecha1 = explode("-", $fecha1);
    $fecha2 = explode("-", $fecha2);
    $ano1   = $fecha1[0];
    $mes1   = $fecha1[1];
    $dia1   = $fecha1[2];
    //defino fecha 2
    $ano2 = $fecha2[0];
    $mes2 = $fecha2[1];
    $dia2 = $fecha2[2];
    //calculo timestam de las dos fechas
    $timestamp1 = mktime(0, 0, 0, $mes1, $dia1, $ano1);
    $timestamp2 = mktime(4, 12, 0, $mes2, $dia2, $ano2);
    //resto a una fecha la otra
    $segundos_diferencia = $timestamp1 - $timestamp2;
    //echo $segundos_diferencia;
    //convierto segundos en días
    $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
    //obtengo el valor absoulto de los días (quito el posible signo negativo)
    $dias_diferencia = abs($dias_diferencia);
    //quito los decimales a los días de diferencia
    $dias_diferencia = floor($dias_diferencia);
    return $dias_diferencia;
}

function meses($mes)
{
    $arr_mes = array("01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre");
    return $arr_mes[$mes];
}

?>