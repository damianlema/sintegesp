<?
session_start();
include "../../../conf/conex.php";
include "../../../funciones/funciones.php";
Conectarse();
extract($_POST);

//*******************************************************************************************************************************************
//********************************************* TRANSFERIR COMPROMISOS NO CAUSADOS AL AÑO FISCAL SIGUIENTE ************************************
//*******************************************************************************************************************************************

if ($ejecutar == "trasladarCompromiso") {

    $sql_orden_a_trasladar = mysql_query("select * from gestion_" . $anio_compromiso . ".orden_compra_servicio where idorden_compra_servicio = " . $id_orden_compra . "");
    $bus_orden_trasladar   = mysql_fetch_array($sql_orden_a_trasladar);
    $tipo_orden            = $bus_orden_trasladar["tipo"];
    if ($bus_orden_trasladar["id_siguiente"] != 0) {
        echo "Orden ya trasladada";
    } else {
        $sql = mysql_query("insert into orden_compra_servicio (numero_orden,
															tipo,
															fecha_orden,
															fecha_elaboracion,
															idbeneficiarios,
															idcategoria_programatica,
															anio,
															idfuente_financiamiento,
															idtipo_presupuesto,
															justificacion,
															observaciones,
															ordenado_por,
															cedula_ordenado,
															numero_requisicion,
															fecha_requisicion,
															estado,
															status,
															usuario,
															fechayhora,
															nro_factura,
															fecha_factura,
															nro_control,
															iddependencia,
															cofinanciamiento,
															ubicacion,
															codigo_referencia,
															id_anterior,
															anio_anterior)values(
																					'" . $bus_orden_trasladar["numero_orden"] . "',
																					'" . $bus_orden_trasladar["tipo"] . "',
																					'" . $anio_receptor . ".-01-02',
																					'" . $bus_orden_trasladar["fecha_elaboracion"] . "',
																					'" . $bus_orden_trasladar["idbeneficiarios"] . "',
																					'" . $categoria_programatica . "',
																					'" . $anio_receptor . "',
																					'" . $fuente_financiamiento . "',
																					'" . $tipo_presupuesto . "',
																					'" . $bus_orden_trasladar["justificacion"] . "',
																					'" . $bus_orden_trasladar["observaciones"] . "',
																					'" . $bus_orden_trasladar["ordenado_por"] . "',
																					'" . $bus_orden_trasladar["cedula_ordenado"] . "',
																					'" . $bus_orden_trasladar["numero_requisicion"] . "',
																					'" . $bus_orden_trasladar["fecha_requisicion"] . "',
																					'parcial',
																					'a',
																					'" . $bus_orden_trasladar["usuario"] . "',
																					'" . $bus_orden_trasladar["fechayhora"] . "',
																					'" . $bus_orden_trasladar["nro_factura"] . "',
																					'" . $bus_orden_trasladar["fecha_factura"] . "',
																					'" . $bus_orden_trasladar["nro_control"] . "',
																					'" . $bus_orden_trasladar["iddependencia"] . "',
																					'" . $bus_orden_trasladar["cofinanciamiento"] . "',
																					'0',
																					'" . $bus_orden_trasladar["codigo_referencia"] . "',
																					'" . $id_orden_compra . "',
																					'" . $anio_compromiso . "')") or die("error creando nueva orden " . mysql_error());

        $id_nuevo_compromiso = mysql_insert_id();
        //actualizo la orden anterior para colocarle el id de la orden nueva del año siguiente
        $sql_actualizar_anterior = mysql_query("update gestion_" . $anio_compromiso . ".orden_compra_servicio set
																id_siguiente = " . $id_nuevo_compromiso . ",
																anio_siguiente = " . $anio_receptor . "
														where idorden_compra_servicio = " . $id_orden_compra . "") or die("error actualizando orden anterior " . mysql_error());

        //traslado los articulos_servicios a la nueva orden
        $sql_articulos_a_trasladar = mysql_query("select * from gestion_" . $anio_compromiso . ".articulos_compra_servicio where idorden_compra_servicio = " . $id_orden_compra . "");

        while ($bus_articulos_trasladar = mysql_fetch_array($sql_articulos_a_trasladar)) {

            $sql_articulos_servicios = mysql_query("select * from gestion_" . $anio_compromiso . ".articulos_servicios
															where idarticulos_servicios = '" . $bus_articulos_trasladar["idarticulos_servicios"] . "'")
            or die("error seleccionando articulo " . mysql_error());
            $bus_articulos_servicios = mysql_fetch_array($sql_articulos_servicios);

            //determino cuanto resta por causar para colocarselo al articulos_Servicios
            $sql_partidas = mysql_query("select * from gestion_" . $anio_compromiso . ".partidas_orden_compra_servicio
									where idorden_compra_servicio = " . $id_orden_compra . "");
            while ($bus_partidas = mysql_fetch_array($sql_partidas)) {

                $sql_maestro = mysql_query("select * from gestion_" . $anio_compromiso . ".maestro_presupuesto where idRegistro = " . $bus_partidas["idmaestro_presupuesto"] . "
															and idclasificador_presupuestario = '" . $bus_articulos_servicios["idclasificador_presupuestario"] . "'");
                if (mysql_num_rows($sql_maestro) > 0) {
                    //$bus_maestro = mysql_fetch_array($sql_maestro);
                    $sql_consultar_saldo = mysql_query("select SUM(par.monto) as total_causados
												 FROM
												gestion_" . $anio_compromiso . ".orden_compra_servicio oc
												INNER JOIN gestion_" . $anio_compromiso . ".relacion_pago_compromisos rpc ON (oc.idorden_compra_servicio = rpc.idorden_compra_servicio)
												INNER JOIN gestion_" . $anio_compromiso . ".orden_pago op ON (rpc.idorden_pago = op.idorden_pago)
												INNER JOIN gestion_" . $anio_compromiso . ".partidas_orden_pago par ON (par.idorden_pago = op.idorden_pago
																and par.idmaestro_presupuesto = '" . $bus_partidas["idmaestro_presupuesto"] . "')
												WHERE
												oc.idorden_compra_servicio = '" . $id_orden_compra . "'
												and (op.estado = 'parcial' or
													op.estado = 'pagada' or
													op.estado = 'procesado')") or die(mysql_error());
                    $bus_consultar_saldo = mysql_fetch_array($sql_consultar_saldo);

                    $resta = $bus_partidas["monto"] - $bus_consultar_saldo["total_causados"];
                }
            }

            if ($bus_articulos_trasladar["total"] > 0) {
                $total    = ($bus_articulos_trasladar["cantidad"] * $resta);
                $impuesto = ($bus_articulos_trasladar["cantidad"] * $resta) * $bus_articulos_trasladar["porcentaje_impuesto"];
                $exento   = 0;
            } else {
                $exento   = ($bus_articulos_trasladar["cantidad"] * $resta);
                $impuesto = 0;
                $total    = 0;
            }
            $sql_insertar_articulos = mysql_query("insert into articulos_compra_servicio 	(idorden_compra_servicio,
																						idarticulos_servicios,
																						idcategoria_programatica,
																						cantidad,
																						precio_unitario,
																						porcentaje_impuesto,
																						impuesto,
																						total,
																						exento,
																						estado,
																						idpartida_impuesto
																						)values(
																							'" . $id_nuevo_compromiso . "',
																							'" . $bus_articulos_trasladar["idarticulos_servicios"] . "',
																							'" . $categoria_programatica . "',
																							'" . $bus_articulos_trasladar["cantidad"] . "',
																							'" . $resta . "',
																							'" . $bus_articulos_trasladar["porcentaje_impuesto"] . "',
																							'" . $impuesto . "',
																							'" . $total . "',
																							'" . $exento . "',
																							'" . $bus_articulos_trasladar["estado"] . "',
																							'" . $bus_articulos_trasladar["idpartida_impuesto"] . "'
																						)") or die("error cargando articulos " . mysql_error());

        }

        //sumo para actualizar los totales del compromiso

        $sql_suma_totales = mysql_query("select SUM(TOTAL) as total,
											SUM(IMPUESTO) as impuesto,
											SUM(EXENTO) as exento
											from articulos_compra_servicio
											where idorden_compra_servicio = '" . $id_nuevo_compromiso . "'");
        $suma_totales    = mysql_fetch_array($sql_suma_totales);
        $total_general   = $suma_totales["impuesto"] + $suma_totales["total"] + $suma_totales["exento"];
        $actualizo_orden = mysql_query("update orden_compra_servicio set
												exento = '" . $suma_totales["exento"] . "',
												sub_total = '" . $suma_totales["total"] . "',
												exento_original = '" . $suma_totales["exento"] . "',
												sub_total_original = '" . $suma_totales["total"] . "',
												impuesto = '" . $suma_totales["impuesto"] . "',
												total = '" . $total_general . "'
												where idorden_compra_servicio = '" . $id_nuevo_compromiso . "'") or die(" actualizando oc " . mysql_error());

        //traslado lo que queda por causar
        $sql_partidas = mysql_query("select * from gestion_" . $anio_compromiso . ".partidas_orden_compra_servicio
									where idorden_compra_servicio = " . $id_orden_compra . "");

        while ($bus_partidas = mysql_fetch_array($sql_partidas)) {

            $sql_consultar_saldo = mysql_query("select SUM(par.monto) as total_causados
											 FROM
											gestion_" . $anio_compromiso . ".orden_compra_servicio oc
											INNER JOIN gestion_" . $anio_compromiso . ".relacion_pago_compromisos rpc ON (oc.idorden_compra_servicio = rpc.idorden_compra_servicio)
											INNER JOIN gestion_" . $anio_compromiso . ".orden_pago op ON (rpc.idorden_pago = op.idorden_pago)
											INNER JOIN gestion_" . $anio_compromiso . ".partidas_orden_pago par ON (par.idorden_pago = op.idorden_pago
															and par.idmaestro_presupuesto = '" . $bus_partidas["idmaestro_presupuesto"] . "')
											WHERE
											oc.idorden_compra_servicio = '" . $id_orden_compra . "'
											and (op.estado = 'parcial' or
												op.estado = 'pagada' or
												op.estado = 'procesado')") or die(mysql_error());
            $bus_consultar_saldo = mysql_fetch_array($sql_consultar_saldo);

            $resta = $bus_partidas["monto"] - $bus_consultar_saldo["total_causados"];

            $sql_maestro = mysql_query("select * from gestion_" . $anio_compromiso . ".maestro_presupuesto
											where idRegistro = " . $bus_partidas["idmaestro_presupuesto"] . "") or die(" 00 " . mysql_error());

            $bus_maestro = mysql_fetch_array($sql_maestro) or die(" 1 " . mysql_error());

            //valido si la partida existe para crearla o sumarle los montos de este compromiso

            $sql_maestro_nuevo = mysql_query("select * from gestion_" . $anio_receptor . ".maestro_presupuesto
													where
														anio = '" . $anio_receptor . "'
														and idcategoria_programatica = '" . $categoria_programatica . "'
														and idtipo_presupuesto = '" . $tipo_presupuesto . "'
														and idfuente_financiamiento = '" . $fuente_financiamiento . "'
														and idclasificador_presupuestario = '" . $bus_maestro["idclasificador_presupuestario"] . "'
														and idordinal = '" . $bus_maestro["idordinal"] . "'
												") or die("error buscando nueva partida " . mysql_error());

            if (mysql_num_rows($sql_maestro_nuevo) > 0) {
                //ya existe la partida, debo actualizarla
                $bus_maestro_nuevo = mysql_fetch_array($sql_maestro_nuevo);

                $insertar_partida_compra = mysql_query("insert into partidas_orden_compra_servicio (idorden_compra_Servicio,
																									idmaestro_presupuesto,
																									monto,
																									monto_original,
																									estado
																									)values(
																									'" . $id_nuevo_compromiso . "',
																									'" . $bus_maestro_nuevo["idRegistro"] . "',
																									'" . $resta . "',
																									'" . $resta . "',
																									'" . $bus_partidas["estado"] . "'
																									)") or die("creando partida orden compra " . mysql_error());

                $actualizar_maestro_nuevo = mysql_query("update maestro_presupuesto set
																	monto_original = monto_original+'" . $resta . "',
																	total_compromisos = total_compromisos+'" . $resta . "',
																	monto_actual = monto_actual+'" . $resta . "'
																	where idRegistro = '" . $bus_maestro_nuevo["idRegistro"] . "'"
                ) or die("actualizando maestro " . mysql_error());

            } else {
                //no existe la partida, debo crearla
                $insertar_maestro_nuevo = mysql_query("insert into maestro_presupuesto (anio,
																						idcategoria_programatica,
																						idtipo_presupuesto,
																						idfuente_financiamiento,
																						idclasificador_presupuestario,
																						idordinal,
																						monto_original,
																						monto_actual,
																						total_compromisos,
																						status
																						)values(
																						'" . $anio_receptor . "',
																						'" . $categoria_programatica . "',
																						'" . $tipo_presupuesto . "',
																						'" . $fuente_financiamiento . "',
																						'" . $bus_maestro["idclasificador_presupuestario"] . "',
																						'" . $bus_maestro["idordinal"] . "',
																						'" . $resta . "',
																						'" . $resta . "',
																						'" . $resta . "',
																						'a'
																						)
														") or die("creando maestro nuevo " . mysql_error());
                $id_nuevo_maestro        = mysql_insert_id();
                $insertar_partida_compra = mysql_query("insert into partidas_orden_compra_servicio (idorden_compra_Servicio,
																									idmaestro_presupuesto,
																									monto,
																									monto_original,
																									estado
																									)values(
																									'" . $id_nuevo_compromiso . "',
																									'" . $id_nuevo_maestro . "',
																									'" . $resta . "',
																									'" . $resta . "',
																									'" . $bus_partidas["estado"] . "'
																									)") or die("creando partida orden compra2 " . mysql_error());

            }

        }

        $sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $tipo_orden . "'");
        $bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);

        if ($bus_cuentas_contables["tabla_debe"] != '' and $bus_cuentas_contables["idcuenta_debe"] != 0 and $bus_cuentas_contables["tabla_haber"] != '' and $bus_cuentas_contables["idcuenta_haber"] != '') {
            $sql_contable = mysql_query("insert into asiento_contable (idfuente_financiamiento,
																detalle,
																tipo_movimiento,
																iddocumento,
																estado,
																status,
																usuario,
																fechayhora
																	)values(
																			'" . $fuente_financiamiento . "',
																			'" . $justificacion . "',
																			'compromiso',
																			'" . $id_nuevo_compromiso . "',
																			'elaboracion',
																			'a',
																			'" . $login . "',
																			'" . date("Y-m-d H:i:s") . "')");

            if ($sql_contable) {
                $idasiento_contable       = mysql_insert_id();
                $sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																tabla,
																idcuenta,
																afecta,
																monto
																	)values(
																			'" . $idasiento_contable . "',
																			'" . $bus_cuentas_contables["tabla_debe"] . "',
																			'" . $bus_cuentas_contables["idcuenta_debe"] . "',
																			'debe',
																			'" . $total_general . "')");
                $sql_cuenta_contable_haber = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																tabla,
																idcuenta,
																afecta,
																monto
																	)values(
																			'" . $idasiento_contable . "',
																			'" . $bus_cuentas_contables["tabla_haber"] . "',
																			'" . $bus_cuentas_contables["idcuenta_haber"] . "',
																			'haber',
																			'" . $total_general . "')");

            }
        }

        echo "exito";
    }

}
