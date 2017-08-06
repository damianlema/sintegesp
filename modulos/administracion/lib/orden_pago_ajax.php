<?
session_start();
include "../../../conf/conex.php";
include "../../../funciones/funciones.php";
Conectarse();

extract($_POST);

function colsultarMaestro($anio, $idcategoria_programatica, $idclasificador_presupuestario, $idfuente_financiamiento, $idtipo_presupuesto, $idordinal, $cofinanciamiento)
{
    if ($cofinanciamiento == "si") {
        $sql_cofinanciamiento = mysql_query("select
												fc.idfuente_financiamiento
												 from
												 	fuentes_cofinanciamiento fc
												 where
												 	fc.idcofinanciamiento = '" . $idfuente_financiamiento . "'");

        $query = "SELECT * FROM
					maestro_presupuesto mp
					WHERE
						mp.anio								= '" . $anio . "'
					AND mp.idcategoria_programatica 		= '" . $idcategoria_programatica . "'
					AND mp.idclasificador_presupuestario 	= '" . $idclasificador_presupuestario . "'
					AND mp.idtipo_presupuesto 				= '" . $idtipo_presupuesto . "'
					AND mp.idordinal 						= '" . $idordinal . "'
					AND (";
        while ($bus_cofinanciamiento = mysql_fetch_array($sql_cofinanciamiento)) {
            $query .= " mp.idfuente_financiamiento = '" . $bus_cofinanciamiento["idfuente_financiamiento"] . "' OR";
        }

        $query = substr($query, 0, -2);
        $query .= ")";

        //$query .= " AND fc.idcofinanciamiento = '".$bus_cofinanciamiento["idcofinanciamiento"]."'";

        $sql_maestro = mysql_query($query) or die(mysql_error());
    } else {
        echo "AQUI";
        $sql_maestro = mysql_query("SELECT * FROM
												maestro_presupuesto
											WHERE
												anio 							= '" . $anio . "'
											AND idcategoria_programatica 		= " . $idcategoria_programatica . "
											AND idclasificador_presupuestario 	= " . $idclasificador_presupuestario . "
											AND idfuente_financiamiento 		= '" . $idfuente_financiamiento . "'
											AND idtipo_presupuesto 				= '" . $idtipo_presupuesto . "'
											AND idordinal 						= '" . $idordinal . "'");
    }

    //$anio." , ".$idcategoria_programatica." , ".$idclasificador_presupuestario." , ".$idfuente_financiamiento." , ".$idtipo_presupuesto." , ".$idordinal." , ".$cofinanciamiento;

    return $sql_maestro;
}

//*******************************************************************************************************************************************
//********************************************* CONSULTAR LISTA DE SOLICITUDES FINALIZADAS POR PROVEEDOR ************************************
//*******************************************************************************************************************************************

if ($ejecutar == "consultarSolicitudesProveedor") {
    if ($forma_pago == "parcial" or $forma_pago == "valuacion") {
        $estado = " and (estado = 'parcial' or estado = 'procesado' or estado = 'conformado')";
    } else {
        $estado = " and estado = 'procesado' or estado = 'conformado'";
    }
    //echo "id orden pago ".$id_orden_pago;
    if ($id_orden_pago != '') {
        $sql_orden = mysql_query("select * from orden_pago where idorden_pago = " . $id_orden_pago . "") or die("aqui1" . mysql_error());
        $bus_orden = mysql_fetch_array($sql_orden) or die("aqui2" . mysql_error());
        if ($bus_orden["estado"] == "procesado" || $bus_orden["estado"] == "anulado" || $bus_orden["estado"] == "pagada" || $bus_orden["estado"] == "conformado") {
            echo "La orden se encuentra en estado " . $bus_orden["estado"] . ", Por lo tanto no se puede modificar los compromisos seleccionados";
        } else {
            //echo "anio ".$anio." fiscal ".$_SESSION["anio_fiscal"];

            $sql_tipos_documentos = mysql_query("select * from tipos_documentos where idtipos_documentos = " . $tipo . "");
            $bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos);
            if ($bus_tipos_documentos["compromete"] == 'no') {
                $tipo_documento = $bus_tipos_documentos["documento_compromete"];
            } else {
                $tipo_documento = $bus_tipos_documentos["idtipos_documentos"];
            }

            $sql_configuracion = mysql_query("select * from configuracion_administracion");
            $bus_configuracion = mysql_fetch_array($sql_configuracion);
            $id_dependencia    = $bus_configuracion["iddependencia"];

            if ($anio < $_SESSION["anio_fiscal"]) {
                //echo "entro aqui";
                $sql_remision_documentos = mysql_query("select * from
									orden_compra_servicio,
									beneficiarios
						where orden_compra_servicio.idcategoria_programatica = '" . $id_categoria_programatica . "'
						and orden_compra_servicio.anio = '" . $anio . "'
						and orden_compra_servicio.idfuente_financiamiento = '" . $idfuente_financiamiento . "'
						and orden_compra_servicio.idtipo_presupuesto = '" . $idtipo_presupuesto . "'
						and orden_compra_servicio.tipo = '" . $tipo_documento . "'
						and orden_compra_servicio.idbeneficiarios = '" . $id_beneficiarios . "'
						and (orden_compra_servicio.estado = 'parcial'
							 or orden_compra_servicio.estado = 'procesado'
							 or orden_compra_servicio.estado = 'conformado')
						and beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios") or die("aqui" . mysql_error());

            } else {

                if ($bus_tipos_documentos["multi_categoria"] == "no") {
                    if (!$_SESSION["modulo"] == "4") {
                        echo "entro1";
                        $sql_remision_documentos = mysql_query("select * from orden_compra_servicio,
															beneficiarios
						where orden_compra_servicio.idcategoria_programatica = '" . $id_categoria_programatica . "'
						and orden_compra_servicio.idfuente_financiamiento = '" . $idfuente_financiamiento . "'
						and orden_compra_servicio.idtipo_presupuesto = '" . $idtipo_presupuesto . "'
						and orden_compra_servicio.tipo = '" . $tipo_documento . "'
						and orden_compra_servicio.idbeneficiarios = '" . $id_beneficiarios . "'
						and (orden_compra_servicio.estado = 'parcial' or orden_compra_servicio.estado = 'procesado' or orden_compra_servicio.estado = 'conformado')
						and beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios") or die("aqui" . mysql_error());

                    } else {
                        if ($_SESSION["version"] == "completo") {
                            //echo "entro2";
                            $sql_consulta = "(SELECT
												  ocs.*
								 FROM
								  orden_compra_servicio ocs
								 WHERE
								  ocs.iddependencia IN (SELECT modulo
														FROM agrupacion_modulos
														WHERE grupo = (SELECT grupo
																	   FROM agrupacion_modulos
																	   WHERE modulo = '" . $id_dependencia . "')) AND
								  ocs.idorden_compra_servicio NOT IN (SELECT id_documento
																	  FROM relacion_documentos_remision
																	  WHERE
																		tabla = 'orden_compra_servicio' AND
																		iddependencia_origen = ocs.iddependencia) AND
								  ocs.estado <> 'elaboracion'
								  and ocs.estado <> 'anulado'
								  and ocs.estado <> 'ordenado'
								  and ocs.estado <> 'pagado'
								  and ocs.idbeneficiarios = '" . $id_beneficiarios . "'
								  and ocs.anio = '" . $anio . "'
								  and ocs.tipo = '" . $tipo_documento . "'
								  and ocs.idfuente_financiamiento = '" . $idfuente_financiamiento . "'
								  and ocs.idcategoria_programatica = '" . $id_categoria_programatica . "'
								  and ocs.idtipo_presupuesto = '" . $idtipo_presupuesto . "')

												UNION

												(SELECT
												  ocs.* FROM
												  orden_compra_servicio ocs
												 WHERE
												  ocs.idorden_compra_servicio IN (SELECT rdr2.id_documento
										FROM
											recibir_documentos rd2
										INNER JOIN relacion_documentos_recibidos rdr2 ON (rd2.idrecibir_documentos = rdr2.idrecibir_documentos)
										WHERE
											rdr2.tabla = 'orden_compra_servicio' AND
											rd2.iddependencia_recibe IN (SELECT modulo
																	 FROM agrupacion_modulos
																	 WHERE grupo = (SELECT grupo
																					FROM agrupacion_modulos
																					WHERE modulo = '" . $id_dependencia . "'))
									 ) AND
												  ocs.estado <> 'elaboracion'
												  and ocs.estado <> 'anulado'
												  and ocs.estado <> 'ordenado'
												  and ocs.estado <> 'pagado'
												  and ocs.idbeneficiarios = '" . $id_beneficiarios . "'








												  and ocs.anio = '" . $anio . "'
												  and ocs.tipo = '" . $tipo_documento . "'
												  and ocs.idfuente_financiamiento = '" . $idfuente_financiamiento . "'
												  and ocs.idcategoria_programatica = '" . $id_categoria_programatica . "'
												  and ocs.idtipo_presupuesto = '" . $idtipo_presupuesto . "'
												)
												UNION
												(SELECT
												  ocs.* FROM
												  orden_compra_servicio ocs
												 WHERE
												  ocs.idorden_compra_servicio IN (SELECT rdr2.id_documento
										FROM
											recibir_documentos rd2
										INNER JOIN relacion_documentos_recibidos rdr2 ON (rd2.idrecibir_documentos = rdr2.idrecibir_documentos)
										WHERE
											rdr2.tabla = 'orden_compra_servicio' AND
											rd2.iddependencia_recibe = '" . $id_dependencia . "'
									 ) AND
												  ocs.estado <> 'elaboracion'
												  and ocs.estado <> 'anulado'
												  and ocs.estado <> 'ordenado'
												  and ocs.estado <> 'pagado'
												  and ocs.idbeneficiarios = '" . $id_beneficiarios . "'
												  and ocs.tipo = '" . $tipo_documento . "'
												  and ocs.anio = '" . $anio . "'
												  and ocs.idfuente_financiamiento = '" . $idfuente_financiamiento . "'
												  and ocs.idcategoria_programatica = '" . $id_categoria_programatica . "'
												  and ocs.idtipo_presupuesto = '" . $idtipo_presupuesto . "'
												)
								order by codigo_referencia";

                            //echo $sql_consulta;

                            $sql_remision_documentos = mysql_query($sql_consulta) or die("aqui" . mysql_error());

                        } else {
                            //echo "entro3";
                            $sql_remision_documentos = mysql_query("select * from
										orden_compra_servicio,
										beneficiarios
							where orden_compra_servicio.idcategoria_programatica = '" . $id_categoria_programatica . "'
							and orden_compra_servicio.anio = '" . $anio . "'
							and orden_compra_servicio.idfuente_financiamiento = '" . $idfuente_financiamiento . "'
							and orden_compra_servicio.idtipo_presupuesto = '" . $idtipo_presupuesto . "'
							and orden_compra_servicio.tipo = '" . $tipo_documento . "'
							and orden_compra_servicio.idbeneficiarios = '" . $id_beneficiarios . "'
							and (orden_compra_servicio.estado = 'parcial'
								 or orden_compra_servicio.estado = 'procesado'
								 or orden_compra_servicio.estado = 'conformado')
							and beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios") or die("aqui" . mysql_error());

                        }

                    }
                } else {

                    if ($_SESSION["version"] == "completo") {
                        //echo "entro4";

                        $sql_consulta = "select * from orden_compra_servicio,
															beneficiarios
						where
						orden_compra_servicio.idfuente_financiamiento = '" . $idfuente_financiamiento . "'
						and orden_compra_servicio.idtipo_presupuesto = '" . $idtipo_presupuesto . "'
						and orden_compra_servicio.idbeneficiarios = '" . $id_beneficiarios . "'
						and (orden_compra_servicio.estado = 'parcial' or orden_compra_servicio.estado = 'procesado' or orden_compra_servicio.estado = 'conformado')
						and beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios";

                    } else {
                        //echo "entro5";
                        $sql_consulta = "select * from orden_compra_servicio,
											beneficiarios
											where orden_compra_servicio.anio = '" . $anio . "'
											and orden_compra_servicio.idfuente_financiamiento = '" . $idfuente_financiamiento . "'
											and orden_compra_servicio.idtipo_presupuesto = '" . $idtipo_presupuesto . "'
											and (orden_compra_servicio.estado = 'procesado' or orden_compra_servicio.estado = 'conformado')";

                        $sql_consultar_tipos = mysql_query("select * from tipos_documentos where documento_asociado = '" . $tipo_documento . "'");
                        $num_consultar_tipos = mysql_num_rows($sql_consultar_tipos);
                        $i                   = 1;
                        if ($num_consultar_tipos > 1) {
                            $sql_consulta .= "and (";
                        }
                        while ($bus_consultar_tipos = mysql_fetch_array($sql_consultar_tipos)) {
                            $sql_consulta .= " orden_compra_servicio.tipo = '" . $bus_consultar_tipos["idtipos_documentos"] . "' ";
                            if ($num_consultar_tipos > 1) {
                                $sql_consulta .= "||";
                            }
                        }
                        if ($num_consultar_tipos > 1) {
                            $sql_consulta = substr($sql_consulta, 0, strlen($sql_consulta) - 2);
                            $sql_consulta .= ") ";
                        }

                        $sql_consulta .= "and orden_compra_servicio.idbeneficiarios = '" . $id_beneficiarios . "'
										and beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios
										group by orden_compra_servicio.idorden_compra_servicio";
                        //echo $sql_consulta;
                    }
                    $sql_remision_documentos = mysql_query($sql_consulta) or die("ERROR   ..." . mysql_error());
                }
            }

            $num = mysql_num_rows($sql_remision_documentos);
            if ($num > 0) {
                $sql_validar_numero_filas = mysql_query("select * from relacion_pago_compromisos where idorden_pago ='" . $id_orden_pago . "'");
                $num_validar_numero_filas = mysql_num_rows($sql_validar_numero_filas);
                ?>
	<form name="formSolicitudesFinalizadas" id="formSolicitudesFinalizadas">
	  <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
		<thead>
		  <tr>
			<td width="65%" class="Browse">Nro. Orden</td>
			<td width="18%" class="Browse">Just.</td>
			<td width="17%" class="Browse">Selec.</td>
		  </tr>
		</thead>
		<?
                if ($bus_orden["forma_pago"] == "parcial" or $bus_orden["forma_pago"] == "valuacion") {
                    ?>
			<tr>
				<td colspan="3" align="center"><a href="javascript:;" onclick="limpiarDatos(document.getElementById('id_orden_pago').value)"><strong>Limpiar Datos</strong></a></td>
			</tr>
			<?
                }

                $i = 0;
                while ($bus = mysql_fetch_array($sql_remision_documentos)) {
                    $num_validar = 0;
                    if ($bus) {
                        /*$sql_validar = mysql_query("select * from relacion_pago_compromisos where
                        idorden_compra_servicio = '".$bus["idorden_compra_servicio"]."'");
                        $num_validar = 0;
                        while($bus_validar = mysql_fetch_array($sql_validar)){
                        $sql_consultar_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_validar["idorden_pago"]."' and idorden_pago != '".$id_orden_pago."' and (estado = 'elaboracion' or estado = 'procesado')");
                        $num_consultar_pago = mysql_num_rows($sql_consultar_pago);
                        if($num_consultar_pago != 0){
                        $num_validar++;
                        }
                        }
                        echo $num_validar;*/

                        if ($bus_orden["forma_pago"] == 'parcial' or $bus_orden["forma_pago"] == 'valuacion') {
                            $forma_pago = 'parcial';

                            $sql_valida_retencion_parcial = mysql_query("select * from retenciones where iddocumento = '" . $bus["idorden_compra_servicio"] . "'
																				and tipo_pago = '" . $forma_pago . "'") or die("aqui " . mysql_error());

                            if (mysql_num_rows($sql_valida_retencion_parcial) <= 0) {
                                $num_validar++;
                            }
                        } else {
                            $num_validar = 0;
                        }
                        //echo " num ".$num_validar;
                        if ($num_validar == 0) {
                            ?>
						<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
							<td class="Browse"><?=$bus["numero_orden"]?></td>
							<td class="Browse" align="center">
							<a href="javascript:;" onClick="document.getElementById('divDetalleJustificacion<?=$i?>').style.display='block'"><img src="imagenes/ver.png" border="0" title="Ver Justificacion"></a>
							<div style="position:absolute; display:none; background-color: #FFFFCC; border:#000000 1px solid" id="divDetalleJustificacion<?=$i?>">
								<div align="right">
									<a href="javascript:;" onClick="document.getElementById('divDetalleJustificacion<?=$i?>').style.display='none'"><strong>X</strong></a>
								</div>
							<?=$bus["justificacion"]?>
							</div>
							</td>
							<td align="center" class="Browse">
							<?
                            //echo "select * from relacion_pago_compromisos where idorden_compra_servicio = ".$bus_remision["id_documento"]." and idorden_pago = ".$id_orden_pago."";

                            $sql_relacion = mysql_query("select * from relacion_pago_compromisos where idorden_compra_servicio = " . $bus["idorden_compra_servicio"] . " and idorden_pago = " . $id_orden_pago . "");
                            $num_relacion = mysql_num_rows($sql_relacion);

                            if ($bus_orden["forma_pago"] == "total") {
                                ?>
								<input type="checkbox"
								<?
                            } else {
                                ?>
								<input type="radio"
								<?
                            }
                            if ($num_relacion != 0) {
                                ?>
								checked="checked"
								<?
                            }

                            ?>

							onclick="seleccionDeseleccionListaSolicitud(<?=$num_relacion?>), actualizarJustificacion(<?=$bus["idorden_compra_servicio"]?>,document.getElementById('id_orden_pago').value, document.getElementById('anio').value), agregarPartidas(<?=$bus["idorden_compra_servicio"]?>, document.getElementById('id_orden_pago').value, document.getElementById('id_categoria_programatica').value)"
							name="solicitudes_ganadas"
							id="solicitudes_ganadas<?=$i?>"
							value="<?=$bus["idorden_compra_servicio"]?>">

							</td>
						</tr>
						<?
                            $i++;
                        }
                    }
                }
                ?>
	  </table>
	</form>
				<?
            } else {
                echo trim("noTieneGanadas");
            }

        }
    }
}

//*******************************************************************************************************************************************
//********************************************* INGRESAR MATERIALES A LA ORDEN DE COMPRA ****************************************************
//*******************************************************************************************************************************************

if ($ejecutar == "partidas") {
    //echo "entro aca";
    if ($accion != "consultar") {
        if ($accion == "eliminar") {
            //echo "delete from partidas_orden_pago where idorden_pago = ".$id_orden_pago." and idmaestro_presupuesto = ".$id_partida."";
            $sql_partidas = mysql_query("select * from partidas_orden_pago where idorden_pago = " . $id_orden_pago . " and idmaestro_presupuesto = " . $id_partida . "");
            $bus_partidas = mysql_fetch_array($sql_partidas);

            $sql_orden = mysql_query("select * from orden_pago where idorden_pago = '" . $id_orden_pago . "'");
            $bus_orden = mysql_fetch_array($sql_orden);

            $sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_orden["tipo"] . "'");
            $bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);

            if ($bus_tipo_documento["multi_categoria"] == "si") {
                $sql_actualizar = mysql_query("update orden_pago set sub_total = sub_total-" . $bus_partidas["monto"] . ",
											  						total = total-" . $bus_partidas["monto"] . ",
																	total_a_pagar = total_a_pagar-" . $bus_partidas["monto"] . "
																	where idorden_pago = '" . $id_orden_pago . "'") or die(mysql_error());
            } else {
                $sql_actualizar = mysql_query("update orden_pago set total = total-" . $bus_partidas["monto"] . ",
																	total_a_pagar = total_a_pagar-" . $bus_partidas["monto"] . "
																	where idorden_pago = '" . $id_orden_pago . "'") or die(mysql_error());
            }
            //ACTUALIZO LAS CUENTAS CONTABLES
            $sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = " . $id_orden_pago . "
																and tipo_movimiento = 'causado'");
            if (mysql_num_rows($sql_validar_asiento) > 0) {

                $bus_asiento_contable = mysql_fetch_array($sql_validar_asiento);

                $sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable
																where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																order by afecta");

                while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)) {
                    if ($bus_cuentas_contables["afecta"] == 'debe') {
                        $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = monto-'" . $bus_partidas["monto"] . "'
																	where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																		and idcuenta = '" . $bus_tipo_documento["idcuenta_debe"] . "'
																		and tabla = '" . $bus_tipo_documento["tabla_debe"] . "'");
                    } else {
                        $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = monto-'" . $bus_partidas["monto"] . "'
																	where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																		and idcuenta = '" . $bus_tipo_documento["idcuenta_haber"] . "'
																		and tabla = '" . $bus_tipo_documento["tabla_haber"] . "'");
                    }
                }
            }

            $sql_eliminar_partidas = mysql_query("delete from partidas_orden_pago where idorden_pago = " . $id_orden_pago . " and idmaestro_presupuesto = " . $id_partida . "") or die(mysql_error());
            registra_transaccion("Eliminar Partidas (Orden Pago: " . $id_orden_pago . ", Partida: " . $id_partida . ")", $login, $fh, $pc, 'partidas_orden_pago');
        }
    }

    $sql_orden = mysql_query("select * from orden_pago where idorden_pago = " . $id_orden_pago . "");
    $bus_orden = mysql_fetch_array($sql_orden);
    $sql       = mysql_query("select * from partidas_orden_pago,
										maestro_presupuesto,
										clasificador_presupuestario,
										categoria_programatica
										where
										partidas_orden_pago.idorden_pago = " . $id_orden_pago . "
										and maestro_presupuesto.idRegistro = partidas_orden_pago.idmaestro_presupuesto
										and clasificador_presupuestario.idclasificador_presupuestario = maestro_presupuesto.idclasificador_presupuestario
										and categoria_programatica.idcategoria_programatica = maestro_presupuesto.idcategoria_programatica
										order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta") or die(mysql_error());

    $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '" . $id_orden_pago . "'");
    $bus_orden_pago = mysql_fetch_array($sql_orden_pago);

    $sql_tipos_documentos = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_orden_pago["tipo"] . "'");
    $bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos);

    $num = mysql_num_rows($sql);

    if ($num != 0) {
        // si existen articulos en la orden los muestra
        ?>

	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <?
        if ($bus_orden["duplicados"] == 1) {
            ?>
			<td class="Browse"><div align="center">Duplicados</div></td>
			<?
        }
        if ($bus_tipos_documentos["multi_categoria"] == "si") {
            ?>
				<td width="2%" align="center" class="Browse">Categor&iacute;a</td>
			<?
        }
        ?>


            <td width="2%" align="center" class="Browse">A&ntilde;o</td>
            <td width="10%" align="center" class="Browse">Partida</td>
            <td width="48%" align="center" class="Browse">Denominaci&oacute;n</td>
              <?
        if ($_SESSION["mos_dis"] == 1) {
            ?>
              <td width="7%" align="center" class="Browse">Disponible</td>
             <?
        }
        if ($bus_orden["forma_pago"] == "parcial" or $bus_orden["forma_pago"] == "valuacion") {
            ?>
              <td width="7%" align="center" class="Browse">Monto Total</td>
              <td width="7%" align="center" class="Browse">Monto Pendiente</td>
             <?
        }
        ?>
              <td width="7%" align="center" class="Browse">Monto a Pagar</td>


			<?
        $sql_tipos_documentos = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_orden["tipo"] . "'");
        $bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos);

        if ($bus_orden["estado"] == "elaboracion") {
            if (($bus_tipos_documentos["compromete"] == "no" and $bus_tipos_documentos["paga"] == "no" and $bus_tipos_documentos["causa"] == "si" and ($bus_orden["forma_pago"] == "parcial" or $bus_orden["forma_pago"] == "valuacion")) || ($bus_tipos_documentos["compromete"] == "si" and $bus_tipos_documentos["causa"] == "si" and $bus_tipos_documentos["paga"] == "no")) {
                ?>
            <td class="Browse" colspan='2' width="5%"><div align="center">Acciones</div></td>
			<?
            }
        }
        ?>
          </tr>
          </thead>
          <?
        while ($bus = mysql_fetch_array($sql)) {

            $disponible_mostrar = consultarDisponibilidad($bus["idmaestro_presupuesto"]);

            $sql_categoria = mysql_query("select * from maestro_presupuesto,
														categoria_programatica
														where maestro_presupuesto.idRegistro = '" . $bus["idmaestro_presupuesto"] . "'
														and categoria_programatica.idcategoria_programatica = maestro_presupuesto.idcategoria_programatica");
            $bus_categoria = mysql_fetch_array($sql_categoria);

            if ($bus["estado"] == "sobregiro") {
                ?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
            } else {
                ?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			<?

            }
            if ($bus_tipos_documentos["multi_categoria"] == "si") {
                ?>
            		<td class='Browse' align='left'><?=$bus_categoria["codigo"]?></td>
            	<?
            }
            ?>
            <td class='Browse' align='left'>
			<?
            $sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = " . $bus["idmaestro_presupuesto"] . "");
            $bus_maestro = mysql_fetch_array($sql_maestro);
            ?>
			<?=$bus_maestro["anio"]?></td>
            <td class='Browse' align='left'>
			<?
            $sql_ordinal      = mysql_query("select * from ordinal where idordinal = " . $bus_maestro["idordinal"] . "");
            $bus_ordinal      = mysql_fetch_array($sql_ordinal);
            $sql_clasificador = mysql_query("select * from  clasificador_presupuestario where idclasificador_presupuestario = " . $bus_maestro["idclasificador_presupuestario"] . "");
            $bus_clasificador = mysql_fetch_array($sql_clasificador);
            if ($bus_ordinal["codigo"] != '0000') {
                $denominacion = $bus_ordinal["denominacion"];
            } else {
                $denominacion = $bus_clasificador["denominacion"];
            }
            ?>
			<?=$bus_clasificador["codigo_cuenta"] . " " . $bus_ordinal["codigo"]?></td>
            <td class='Browse' align='left'><?=$denominacion?></td>
            <?
            if ($_SESSION["mos_dis"] == 1) {
                ?>
            <td class='Browse' align='right'><?=number_format($disponible_mostrar, 2, ",", ".")?></td>
            <?
            }
            ?>
             <?//number_format($bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"],2,',','.')
            if ($bus_orden["forma_pago"] == "parcial" or $bus_orden["forma_pago"] == "valuacion") {
                $sql_partida_actual = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '" . $id_orden_pago . "'");
                $bus_partida_actual = mysql_fetch_array($sql_partida_actual);
                $sql_partidas       = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = '" . $bus_partida_actual["idorden_compra_servicio"] . "' and idmaestro_presupuesto = '" . $bus_maestro["idRegistro"] . "'");
                $bus_partidas       = mysql_fetch_array($sql_partidas);
                ?>

                <td class='Browse' align='right'><?=number_format($bus_partidas["monto"], 2, ",", ".")?></td><!-- MONTO TOTAL-->
                <td class='Browse' align='right'><!-- MONTO PENDIENTE POR PAGAR-->
                <?
                if ($bus["monto"] <= $bus_partidas["monto"]) {

                    //echo "AQUI";

                    $sql_relacion_pago = mysql_query("select * from relacion_pago_compromisos where idorden_compra_servicio = '" . $bus_partida_actual["idorden_compra_servicio"] . "'");
                    while ($bus_relacion_pago = mysql_fetch_array($sql_relacion_pago)) {
                        $sql_partidas_orden_pago = mysql_query("select *
											from partidas_orden_pago,
												orden_pago
									where partidas_orden_pago.idorden_pago = '" . $bus_relacion_pago["idorden_pago"] . "'
									and partidas_orden_pago.idmaestro_presupuesto = '" . $bus_maestro["idRegistro"] . "'
									and orden_pago.idorden_pago = partidas_orden_pago.idorden_pago
									and orden_pago.anticipo != 'si'
									and orden_pago.estado != 'elaboracion'
									and orden_pago.estado != 'anulado'") or die(mysql_error());
                        $bus_partidas_orden_pago = mysql_fetch_array($sql_partidas_orden_pago);
                        $suma_monto += $bus_partidas_orden_pago["monto"];
                    }
                    $monto = $bus_partidas["monto"] - $suma_monto;

                    $monto_a_mostrar = $monto;
                    $suma_monto      = 0;

                    //$monto_a_mostrar = $bus["monto"];
                    echo number_format($monto_a_mostrar, 2, ",", ".");

                } else {
                    //echo "alla";
                    $monto_a_mostrar = $bus_partidas["monto"];
                    echo number_format($bus_partidas["monto"], 2, ",", ".");
                }

                ?>
                </td>
            <?
            }
            ?>

            <td class='Browse' align="right">
			<?
            if ($bus_orden["estado"] == "elaboracion") {
                if (($bus_tipos_documentos["compromete"] == "no" and $bus_tipos_documentos["paga"] == "no" and $bus_tipos_documentos["causa"] == "si" and ($bus_orden["forma_pago"] == "parcial" or $bus_orden["forma_pago"] == "valuacion")) || ($bus_tipos_documentos["compromete"] == "si" and $bus_tipos_documentos["causa"] == "si" and $bus_tipos_documentos["paga"] == "no")) {
                    ?>
			 <input align="right" style="text-align:right" name="monto<?=$bus["idpartidas_orden_pago"]?>"
            												type="hidden"
                                                            id="monto<?=$bus["idpartidas_orden_pago"]?>"
                                                            size="20"
                                                            value="<?=$bus["monto"]?>">
            <input align="right" style="text-align:right" name="mostrarMonto<?=$bus["idpartidas_orden_pago"]?>"
            												type="text"
                                                            id="mostrarMonto<?=$bus["idpartidas_orden_pago"]?>"
                                                            size="20"
                                                            onclick="this.select()"
                                                            onblur="formatoNumero(this.name, 'monto<?=$bus["idpartidas_orden_pago"]?>')"
                                                            value="<?=number_format($bus["monto"], 2, ',', '.')?>">


			<?
                } else {
                    echo number_format($bus["monto"], 2, ',', '.');
                }
            } else {
                echo number_format($bus["monto"], 2, ',', '.');
            }
            ?>
			</td>
				<?
            if ($bus_orden["estado"] == "elaboracion") {
                if (($bus_tipos_documentos["compromete"] == "no" and $bus_tipos_documentos["paga"] == "no" and $bus_tipos_documentos["causa"] == "si" and ($bus_orden["forma_pago"] == "parcial" or $bus_orden["forma_pago"] == "valuacion")) || ($bus_tipos_documentos["compromete"] == "si" and $bus_tipos_documentos["causa"] == "si" and $bus_tipos_documentos["paga"] == "no")) {
                    ?>
                    <td class='Browse' align="center">
                        <a href="javascript:;" onClick="">
                                <img src="imagenes/refrescar.png" onClick="
                            actualizarValores('monto<?=$bus["idpartidas_orden_pago"]?>', 'mostrarMonto<?=$bus["idpartidas_orden_pago"]?>'),
                            asignarValor('monto<?=$bus["idpartidas_orden_pago"]?>','mostrarMonto<?=$bus["idpartidas_orden_pago"]?>'),
                            actualizarMonto(<?=$bus["idorden_pago"]?>, document.getElementById('monto<?=$bus["idpartidas_orden_pago"]?>').value,
                            <?=$bus_categoria["idRegistro"]?>)"
                            title="Actualizar Monto">
                        </a>
                    </td>
      <td class='Browse' align="center">
                        <a href="javascript:;" onClick="eliminarPartidas(<?=$bus["idorden_pago"]?>, <?=$bus["idmaestro_presupuesto"]?>)">
                            <img src="imagenes/delete.png" title="Eliminar Materiales">                        </a>                    </td>
		 	  <?
                }
            }
            ?>


</tr>
          <?
        }
        ?>
        </table>
        <br />

<?
    } else {
        ?>

	<input type="hidden" name="eliminoSolicitud" id="eliminoSolicitud" value="<?=$eliminoSolicitud?>">
	<?
        echo "No hay Partidas Asociados";
    }

}

//*******************************************************************************************************************************************
//********************************************* INGRESAR DATOS BASICOS DE LA ORDEN DE COMPRA ************************************************
//*******************************************************************************************************************************************
if ($ejecutar == "agregarDatosBasicos") {

    $sql = mysql_query("insert into orden_pago (tipo,
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
															numero_documento,
															fecha_documento,
															numero_proyecto,
															numero_contrato,
															exento,
															sub_total,
															impuesto,
															total,
															total_a_pagar,
															estado,
															status,
															usuario,
															fechayhora,
															forma_pago,
															anticipo)values(
																					'" . $tipo_orden . "',
																					'" . $fecha_validada . "',
																					'" . $id_beneficiarios . "',
																					'" . $categoria_programatica . "',
																					'" . $anio . "',
																					'" . $idfuente_financiamiento . "',
																					'" . $idtipo_presupuesto . "',
																					'" . $justificacion . "',
																					'" . $observaciones . "',
																					'" . $ordenado_por . "',
																					'" . $cedula_ordenado . "',
																					'" . $numero_documento . "',
																					'" . $fecha_documento . "',
																					'" . $numero_proyecto . "',
																					'" . $numero_contrato . "',
																					'" . $exento . "',
																					'" . $subtotal . "',
																					'" . $impuesto . "',
																					'" . $monto_sinafectacion . "',
																					'" . $textoTotalAPagarPrincipalOculto . "',
																					'elaboracion',
																					'a',
																					'" . $login . "',
																					'" . date("Y-m-d H:i:s") . "',
																					'" . $forma_pago . "',
																					'" . $anticipo . "')") or die(mysql_error());

    if ($sql) {
        $iddocumento = mysql_insert_id();
        echo mysql_insert_id();

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
																fechayhora,
																prioridad
																	)values(
																			'" . $idfuente_financiamiento . "',
																			'" . $justificacion . "',
																			'causado',
																			'" . $iddocumento . "',
																			'elaboracion',
																			'a',
																			'" . $login . "',
																			'" . date("Y-m-d H:i:s") . "',
																			'3')");

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
																			'" . $textoTotalAPagarPrincipalOculto . "')");
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
																			'" . $textoTotalAPagarPrincipalOculto . "')");

            }
        }
        registra_transaccion("Ingresar Datos Basicos de la Orden de Pago (" . $iddocumento . ")", $login, $fh, $pc, 'orden_pago');
    } else {
        registra_transaccion("Error Ingresando Datos Basicos de la Orden de Pago", $login, $fh, $pc, 'orden_pago');
        echo mysql_error();
        echo "fallo";
    }

}

//*******************************************************************************************************************************************
//*********************************** LISTA TODAS LAS SOLICITUDES SELECCIONADAS EN UNA ORDEN DE COMPRA ***************************************
//*******************************************************************************************************************************************
if ($ejecutar == "listarSolicitudesSeleccionadas") {
    //echo 'id orden pago '.$id_orden_pago;
    $sql = mysql_query("select * from relacion_pago_compromisos where idorden_pago = " . $id_orden_pago . "") or die('uno' . mysql_error());
    if ($sql) {
        if (mysql_num_rows($sql) > 0) {
            $num = mysql_num_rows($sql) or die('dos' . mysql_error());
        } else {
            $num = 0;
        }
    } else {
        $num = 0;
    }
    $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '" . $id_orden_pago . "'") or die('tres' . mysql_error());
    $bus_orden_pago = mysql_fetch_array($sql_orden_pago) or die('cuatro' . mysql_error());

    $sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_orden_pago["tipo"] . "'") or die('cinco' . mysql_error());
    $bus_tipo_documento = mysql_fetch_array($sql_tipo_documento) or die('seis' . mysql_error());

    $sql_tiene_retencion = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago =" . $id_orden_pago . "") or die('siete' . mysql_error());
    if (mysql_num_rows($sql_tiene_retencion) > 0) {
        $num_tiene_retencion = mysql_num_rows($sql_tiene_retencion) or die('ocho' . mysql_error());
    }

    //echo "MULTI CATEGORIA: ".$bus_tipo_documento["multi_categoria"];
    if ($num > 0) {
        ?>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
		<thead>
          <tr>
            <td width="15%" class="Browse"><div align="center">Nro Compromiso</div></td>
            <td width="10%" class="Browse"><div align="center">Fecha</div></td>
            <td width="15%" class="Browse"><div align="center">Monto</div></td>
           <?
        if ($bus_tipo_documento["multi_categoria"] == "no" or $num_tiene_retencion != 0) {
            ?>
            <td width="15%" class="Browse"><div align="center">Nro. Factura</div></td>
            <td width="15%" class="Browse"><div align="center">Nro. Control</div></td>
            <td width="17%" class="Browse"><div align="center">Fecha Factura</div></td>
            <td width="12%" class="Browse"><div align="center">Monto Retenido</div></td>
            <td width="5%" class="Browse"><div align="center">Act.</div></td>
           <?
        }
        ?>
    	</tr>
          </thead>
          <?
        while ($bus = mysql_fetch_array($sql)) {
            $sql_retenciones = mysql_query("select * from retenciones where iddocumento = '" . $bus["idorden_compra_servicio"] . "'");
            $bus_retenciones = mysql_fetch_array($sql_retenciones);

            $sql2 = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = " . $bus["idorden_compra_servicio"] . "");
            $bus2 = mysql_fetch_array($sql2);
            ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class='Browse' align='center' style="font-size:9px">&nbsp;<?=$bus2["numero_orden"]?></td>
            <td class='Browse' align='left'>&nbsp;<?=$bus2["fecha_orden"]?></td>
            <td class='Browse' align='right'>&nbsp;<?=number_format($bus2["total"], 2, ",", ".")?></td>
            <?
            if ($bus_tipo_documento["multi_categoria"] == "no" or $num_tiene_retencion != 0) {
                ?>
            <td class='Browse' align='center'>
				<?if ($bus_retenciones["tipo_pago"] == 'total') {
                    ?>
                    <input size="18" type="text" name="numero_factura<?=$bus_retenciones["idretenciones"]?>" id="numero_factura<?=$bus_retenciones["idretenciones"]?>" value="<?
                    if ($bus_retenciones["numero_factura"] == "") {
                        echo $bus2["nro_factura"];
                    } else {
                        echo $bus_retenciones["numero_factura"];
                    }
                    ?>">
                <?} else {?> &nbsp; <?}?>
            </td>
            <td class='Browse' align='center'>
            	<?if ($bus_retenciones["tipo_pago"] == 'total') {
                    ?>
            		<input size="18" type="text" name="numero_control<?=$bus_retenciones["idretenciones"]?>" id="numero_control<?=$bus_retenciones["idretenciones"]?>" value="<?
                    if ($bus_retenciones["numero_control"] != "") {
                        echo $bus_retenciones["numero_control"];
                    } else {
                        echo $bus2["nro_control"];
                    }?>">
            	<?} else {?> &nbsp; <?}?>
            </td>
            <td class='Browse' align='center'>
            	<?if ($bus_retenciones["tipo_pago"] == 'total') {
                    ?>
                	<input size="12" type="text" name="fecha_factura<?=$bus_retenciones["idretenciones"]?>" id="fecha_factura<?=$bus_retenciones["idretenciones"]?>" value="<?
                    if ($bus_retenciones["fecha_factura"] != "") {
                        echo $bus_retenciones["fecha_factura"];
                    } else {
                        echo $bus2["fecha_factura"];
                    }
                    ?>"><img src="imagenes/jscalendar0.gif" name="f_trigger_c<?=$bus_retenciones["idretenciones"]?>" width="16" height="16" id="f_trigger_c<?=$bus_retenciones["idretenciones"]?>" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onClick="Calendar.setup({
                                inputField    : 'fecha_factura<?=$bus_retenciones["idretenciones"]?>',
                                button        : 'f_trigger_c<?=$bus_retenciones["idretenciones"]?>',
                                align         : 'Tr',
                                ifFormat      : '%Y-%m-%d'
                                }); "/>
            	<?} else {?> &nbsp; <?}?>
            </td>
            <td class='Browse' align='right'>
				<?if ($bus_retenciones["tipo_pago"] == 'total') {?>
					<?=number_format($bus_retenciones["total_retenido"], 2, ",", ".")?>
            	<?} else {?> &nbsp; <?}?>
            </td>
            <td class='Browse' align='center'>
            	<?if ($bus_retenciones["tipo_pago"] == 'total') {?>
            		<img src="imagenes/refrescar.png" border="0" style="cursor:pointer" onclick="actualizarDatosFactura('<?=$bus_retenciones["idretenciones"]?>', document.getElementById('numero_factura<?=$bus_retenciones["idretenciones"]?>').value, document.getElementById('numero_control<?=$bus_retenciones["idretenciones"]?>').value, document.getElementById('fecha_factura<?=$bus_retenciones["idretenciones"]?>').value)">
            	<?} else {?> &nbsp; <?}?>
            </td>
          <?
            }
        }
        ?>
        </table>
<?
    } else {
        echo "<center>No hay Compromisos Seleccionados</center>";
    }
}

//*******************************************************************************************************************************************
//********************************************* ACTUALIZAR PRECIO CANTIDAD DE UNA PARTIDA EN LA ORDEN DE PAGO *******************************
//*******************************************************************************************************************************************

if ($ejecutar == "actualizarMonto") {
    $estado       = "disponible";
    $aprobado     = true;
    $sql_consulta = mysql_query("select * from partidas_orden_pago where idorden_pago = '" . $id_orden_pago . "' and idmaestro_presupuesto = '" . $id_partida . "'") or die("ERROR SELECCIONANDO LAS PARTIDAS: " . mysql_error());
    $bus_consulta = mysql_fetch_array($sql_consulta);
    $disponible   = consultarDisponibilidad($bus_consulta["idmaestro_presupuesto"]);
    if ($disponible < $monto) {
        //echo "partidasSinDisponibilidad ".$bus_consulta["idpartidas_orden_pago"];
        $estado   = "sobregiro";
        $aprobado = false;
    }

    if ($aprobado) {
        $sql_actualizar = mysql_query("update partidas_orden_pago
								set estado = '" . $estado . "', monto = '" . $monto . "'
								where idorden_pago = '" . $id_orden_pago . "'
								and idmaestro_presupuesto = '" . $id_partida . "'") or die("ERROR ACTUALIZANDO LAS PARTIDAS:" . mysql_error());
    } else {
        $sql_actualizar = mysql_query("update partidas_orden_pago
								set estado = '" . $estado . "', monto = '" . $monto . "'
								where idorden_pago = '" . $id_orden_pago . "'
								and idmaestro_presupuesto = '" . $id_partida . "'") or die("ERROR ACTUALIZANDO LAS PARTIDAS:" . mysql_error());
    }

    $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '" . $id_orden_pago . "'");
    $bus_orden_pago = mysql_fetch_array($sql_orden_pago);

    if ($bus_orden_pago["forma_pago"] != "parcial" && $bus_orden_pago["forma_pago"] != "valuacion") {
        $sql_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_orden_pago["tipo"] . "'");
        $bus_documento = mysql_fetch_array($sql_documento);
        if ($bus_documento["multi_categoria"] == "si") {
            if ($aprobado) {
                $sql_actualizar = mysql_query("update partidas_orden_pago
								set estado = '" . $estado . "', monto = '" . $monto . "'
								where idorden_pago = '" . $id_orden_pago . "'
								and idmaestro_presupuesto = '" . $id_partida . "'") or die("ERROR ACTUALIZANDO LAS PARTIDAS:" . mysql_error());
                $sql_actualizar_orden_pago = mysql_query("update orden_pago
												set total = (total-'" . $bus_consulta["monto"] . "')+'" . $monto . "',
													sub_total = (sub_total-'" . $bus_consulta["monto"] . "')+'" . $monto . "',
													total_a_pagar = total-total_retenido
													where
													idorden_pago = '" . $id_orden_pago . "'") or die("ERROR ACTUALIZANDO LA ORDEN DE PAGO" . mysql_error());

            } else {
                $sql_actualizar = mysql_query("update partidas_orden_pago
								set estado = '" . $estado . "', monto = '" . $monto . "'
								where idorden_pago = '" . $id_orden_pago . "'
								and idmaestro_presupuesto = '" . $id_partida . "'") or die("ERROR ACTUALIZANDO LAS PARTIDAS:" . mysql_error());
                $sql_actualizar_orden_pago = mysql_query("update orden_pago
												set total = (total-'" . $bus_consulta["monto"] . "')+'" . $monto . "',
													sub_total = (sub_total-'" . $bus_consulta["monto"] . "')+'" . $monto . "',
													total_a_pagar = total-total_retenido
													where
													idorden_pago = '" . $id_orden_pago . "'") or die("ERROR ACTUALIZANDO LA ORDEN DE PAGO" . mysql_error());
            }
        } else {
            if ($aprobado) {
                $sql_actualizar = mysql_query("update partidas_orden_pago
								set estado = '" . $estado . "', monto = '" . $monto . "'
								where idorden_pago = '" . $id_orden_pago . "'
								and idmaestro_presupuesto = '" . $id_partida . "'") or die("ERROR ACTUALIZANDO LAS PARTIDAS:" . mysql_error());
                $sql_actualizar_orden_pago = mysql_query("update orden_pago
												set total = (total-'" . $bus_consulta["monto"] . "')+'" . $monto . "',
													total_a_pagar = total-total_retenido
													where
													idorden_pago = '" . $id_orden_pago . "'") or die("ERROR ACTUALIZANDO LA ORDEN DE PAGO" . mysql_error());
            } else {
                $sql_actualizar = mysql_query("update partidas_orden_pago
								set estado = '" . $estado . "', monto = '" . $monto . "'
								where idorden_pago = '" . $id_orden_pago . "'
								and idmaestro_presupuesto = '" . $id_partida . "'") or die("ERROR ACTUALIZANDO LAS PARTIDAS:" . mysql_error());
                $sql_actualizar_orden_pago = mysql_query("update orden_pago
												set total = (total-'" . $bus_consulta["monto"] . "')+'" . $monto . "',
													total_a_pagar = total-total_retenido
													where
													idorden_pago = '" . $id_orden_pago . "'") or die("ERROR ACTUALIZANDO LA ORDEN DE PAGO" . mysql_error());
            }
        }
        //ACTUALIZO LAS CUENTAS CONTABLES
        $sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = " . $id_orden_pago . "
																and tipo_movimiento = 'causado'");
        if (mysql_num_rows($sql_validar_asiento) > 0) {

            $bus_asiento_contable = mysql_fetch_array($sql_validar_asiento);

            $sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable
																where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																order by afecta");

            while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)) {
                //echo " entro actualizar ".$bus_consulta["monto"];
                if ($bus_cuentas_contables["afecta"] == 'debe') {
                    $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = (monto-'" . $bus_consulta["monto"] . "') + '" . $monto . "'
																	where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																		and idcuenta = '" . $bus_documento["idcuenta_debe"] . "'
																		and tabla = '" . $bus_documento["tabla_debe"] . "'");
                } else {
                    $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = (monto-'" . $bus_consulta["monto"] . "') + '" . $monto . "'
																	where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																		and idcuenta = '" . $bus_documento["idcuenta_haber"] . "'
																		and tabla = '" . $bus_documento["tabla_haber"] . "'");
                }
            }
        }
    } else {
        $estado         = "disponible";
        $sql_actualizar = mysql_query("update partidas_orden_pago
								set estado = '" . $estado . "', monto = '" . $monto . "'
								where idorden_pago = '" . $id_orden_pago . "'
								and idmaestro_presupuesto = '" . $id_partida . "'") or die("ERROR ACTUALIZANDO LAS PARTIDAS:" . mysql_error());

    }

//echo "update partidas_orden_pago set monto = '".$monto."' where idorden_pago = '".$id_orden_pago."' and idmaestro_presupuesto = '".$id_partida."'";
    echo "exito";

}

//*******************************************************************************************************************************************
//********************************************* ACTUALIZAR DATOS BASICOS DE LAS ORDENES DE PAGO *******************************************
//*******************************************************************************************************************************************
if ($ejecutar == "actualizarDatosBasicos") {
    if ($accion == "actualizar") {
        $sql_tipo_documento    = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $tipo_orden . "'");
        $bus_tipo_documento    = mysql_fetch_array($sql_tipo_documento);
        $sql_actualiza_totales = mysql_query("update orden_pago set total_a_pagar = exento+sub_total+impuesto-total_retenido,
																	where idorden_pago = '" . $id_orden_pago . "'");
        $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '" . $id_orden_pago . "'");
        $bus_orden_pago = mysql_fetch_array($sql_orden_pago);

        if ($bus_orden_pago["forma_pago"] == "parcial" or $bus_orden_pago["forma_pago"] == "valuacion") {
            $exento    = "'" . $exento_actual . "'";
            $sub_total = "'" . $sub_total_actual . "'";
            $impuesto  = "'" . $impuesto_actual . "'";
            $total     = "'" . $total_actual . "'";
        } else {
            $exento    = "'" . $exento . "'";
            $sub_total = "'" . $sub_total . "'";
            $impuesto  = "'" . $impuesto . "'";
            $total     = "'" . $total . "'";
        }

        if ($bus_tipo_documento["multi_categoria"] == "si") {
            $multi_categoria2 = 'si';
        } else {
            $multi_categoria2 = 'no';
        }

        if ($bus_tipo_documento["compromete"] == "no" and $bus_tipo_documento["causa"] == "no" and $bus_tipo_documento["paga"] == "no") {
            $sql = mysql_query("update orden_pago set tipo = '" . $tipo_orden . "',
																idbeneficiarios = '" . $id_beneficiarios . "',
																idcategoria_programatica = '" . $id_categoria_programatica . "',
																anio = '" . $anio . "',
																idfuente_financiamiento = '" . $idfuente_financiamiento . "',
																idtipo_presupuesto = '" . $idtipo_presupuesto . "',
																justificacion = '" . $justificacion . "',
																observaciones = '" . $observaciones . "',
																ordenado_por = '" . $ordenado_por . "',
																cedula_ordenado = '" . $cedula_ordenado . "',
																numero_documento = '" . $numero_documento . "',
																fecha_documento = '" . $fecha_documento . "',
																numero_proyecto = '" . $numero_proyecto . "',
																numero_contrato = '" . $numero_contrato . "',
																exento = " . $exento . ",
																sub_total = " . $sub_total . ",
																impuesto = " . $impuesto . ",
																total = " . $total . ",
																total_a_pagar = " . $total . "-total_retenido,
																usuario = '" . $login . "',
																fechayhora = '" . date("Y-m-d H:i:s") . "',
																forma_pago = '" . $forma_pago . "' where idorden_pago = '" . $id_orden_pago . "'")
            or die("PRIMER QUERY:" . mysql_error());
            //actualizo las cuentas contables por si se modifico el monto
            $sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = " . $id_orden_pago . "
														and tipo_movimiento = 'causado'") or die(" valido si existe asiento " . mysql_error());

            if (mysql_num_rows($sql_validar_asiento) > 0) {
                $bus_asiento_contable = mysql_fetch_array($sql_validar_asiento);

                $sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
															order by afecta");

                while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)) {
                    //echo " valor float ".$total;
                    $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = " . $total . "
																	where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'") or die(" error aqui " . mysql_error());

                }
            }

            registra_transaccion("Actualizar Datos Basicos sin Afectacion Presupuestaria (" . $id_orden_pago . ")", $login, $fh, $pc, 'orden_pago');
        } else {
            $sql          = mysql_query("select * from orden_pago where idorden_pago = " . $id_orden_pago . "");
            $bus_consulta = mysql_fetch_array($sql);

            //VALIDO SI CAMBIO DE CATEGORIA PROGRAMATICA PARA HACER EL CAMBIO DE LAS
            //PARTIDAS PRESUPUESTARIAS A LA NUEVA CATEGORIA PROGRAMATICA

            $idcategoria_programatica_actual = $bus_consulta["idcategoria_programatica"];
            if ($idcategoria_programatica_actual != $id_categoria_programatica) {
                $sql_partidas = mysql_query("select * from partidas_orden_pago where idorden_pago = '" . $id_orden_pago . "'");
                while ($consulta = mysql_fetch_array($sql_partidas)) {
                    $sql_maestro       = mysql_query("select * from maestro_presupuesto where idRegistro='" . $consulta["idmaestro_presupuesto"] . "'");
                    $registro_maestro  = mysql_fetch_array($sql_maestro);
                    $sql_nuevo_maestro = mysql_query("select * from maestro_presupuesto WHERE
														idcategoria_programatica = '" . $id_categoria_programatica . "'
														and idclasificador_presupuestario = '" . $registro_maestro["idclasificador_presupuestario"] . "'
														and anio = '" . $registro_maestro["anio"] . "'
														and idtipo_presupuesto = '" . $registro_maestro["idtipo_presupuesto"] . "'
														and idfuente_financiamiento = '" . $registro_maestro["idfuente_financiamiento"] . "'
														and idordinal = '" . $registro_maestro["idordinal"] . "'
														");
                    $existe_registro = mysql_num_rows($sql_nuevo_maestro);
                    //VALIDO SI EXISTE LA PARTIDA EN LA NUEVA CATEGORIA PROGRAMATICA PARA ACTUALIZAR EL idmaestro_presupuesto EN
                    //LA TABLA partidas_orden_pago
                    if ($existe_registro > 0) {
                        $registro_nuevo_maestro      = mysql_fetch_array($sql_nuevo_maestro);
                        $idmaestro_presupuesto_nuevo = $registro_nuevo_maestro["idRegistro"];
                        $disponible                  = consultarDisponibilidad($idmaestro_presupuesto_nuevo);
                        if ($bus_tipo_documento["compromete"] == "si") {
                            if ($disponible < $consulta["monto"]) {
                                $estado = 'sobregiro';
                            } else {
                                $estado = 'disponible';
                            }
                        }
                        $actualizar_partida_orden_pago = mysql_query("update partidas_orden_pago set idmaestro_presupuesto = '" . $idmaestro_presupuesto_nuevo . "',
																								 estado = '" . $estado . "'
																	where idpartidas_orden_pago = '" . $consulta["idpartidas_orden_pago"] . "'");
                    } else {
                        //SI NO EXISTE LA PARTIDA EN LA NUEVA CATEGORIA PROGRAMATICA LA ELIMINO DE LA TABLA partidas_orden_pago
                        $actualizar_partida_orden_pago = mysql_query("delete from partidas_orden_pago WHERE
																			idpartidas_orden_pago = '" . $consulta["idpartidas_orden_pago"] . "'");
                    }
                }
            }

            //VALIDO SI CAMBIO DE FUENTE DE FINANCIAMIENTO PARA HACER EL CAMBIO DE LAS
            //PARTIDAS PRESUPUESTARIAS A LA NUEVA FUENTE DE FINANCIAMIENTO

            $idfuente_financiamiento_actual = $bus_consulta["idfuente_financiamiento"];
            if ($idfuente_financiamiento_actual != $idfuente_financiamiento) {
                $sql_partidas = mysql_query("select * from partidas_orden_pago where idorden_pago = '" . $id_orden_pago . "'");
                while ($consulta = mysql_fetch_array($sql_partidas)) {
                    $sql_maestro       = mysql_query("select * from maestro_presupuesto where idRegistro='" . $consulta["idmaestro_presupuesto"] . "'");
                    $registro_maestro  = mysql_fetch_array($sql_maestro);
                    $sql_nuevo_maestro = mysql_query("select * from maestro_presupuesto WHERE
														idcategoria_programatica = '" . $registro_maestro["idcategoria_programatica"] . "'
														and idclasificador_presupuestario = '" . $registro_maestro["idclasificador_presupuestario"] . "'
														and anio = '" . $registro_maestro["anio"] . "'
														and idtipo_presupuesto = '" . $registro_maestro["idtipo_presupuesto"] . "'
														and idfuente_financiamiento = '" . $idfuente_financiamiento . "'
														and idordinal = '" . $registro_maestro["idordinal"] . "'
														");
                    $existe_registro = mysql_num_rows($sql_nuevo_maestro);
                    //VALIDO SI EXISTE LA PARTIDA EN LA NUEVA FUENTE DE FINANCIAMIENTO PARA ACTUALIZAR EL
                    //idmaestro_presupuesto EN LA TABLA partidas_orden_pago
                    if ($existe_registro > 0) {
                        $registro_nuevo_maestro      = mysql_fetch_array($sql_nuevo_maestro);
                        $idmaestro_presupuesto_nuevo = $registro_nuevo_maestro["idRegistro"];
                        $disponible                  = consultarDisponibilidad($idmaestro_presupuesto_nuevo);
                        if ($bus_tipo_documento["compromete"] == "si") {
                            if ($disponible < $consulta["monto"]) {
                                $estado = 'sobregiro';
                            } else {
                                $estado = 'disponible';
                            }
                        }
                        $actualizar_partida_orden_pago = mysql_query("update partidas_orden_pago set idmaestro_presupuesto = '" . $idmaestro_presupuesto_nuevo . "',
																								 estado = '" . $estado . "'
																	where idpartidas_orden_pago = '" . $consulta["idpartidas_orden_pago"] . "'");
                    } else {
                        //SI NO EXISTE LA PARTIDA EN LA NUEVA FUENTE DE FINANCIAMIENTO LA ELIMINO DE LA
                        //TABLA partidas_orden_pago
                        $actualizar_partida_orden_pago = mysql_query("delete from partidas_orden_pago WHERE
																			idpartidas_orden_pago = '" . $consulta["idpartidas_orden_pago"] . "'");
                    }
                }
            }

            $sql = mysql_query("update orden_pago set tipo = '" . $tipo_orden . "',
																idbeneficiarios = '" . $id_beneficiarios . "',
																idcategoria_programatica = '" . $id_categoria_programatica . "',
																anio = '" . $anio . "',
																idfuente_financiamiento = '" . $idfuente_financiamiento . "',
																idtipo_presupuesto = '" . $idtipo_presupuesto . "',
																justificacion = '" . $justificacion . "',
																observaciones = '" . $observaciones . "',
																ordenado_por = '" . $ordenado_por . "',
																cedula_ordenado = '" . $cedula_ordenado . "',
																numero_documento = '" . $numero_documento . "',
																fecha_documento = '" . $fecha_documento . "',
																numero_proyecto = '" . $numero_proyecto . "',
																numero_contrato = '" . $numero_contrato . "',
																exento = " . $exento . ",
																sub_total = " . $sub_total . ",
																impuesto = " . $impuesto . ",
																total = " . $total . ",
																total_a_pagar = " . $total . "-total_retenido,
																usuario = '" . $login . "',
																fechayhora = '" . date("Y-m-d H:i:s") . "',
																forma_pago = '" . $forma_pago . "'
																where idorden_pago = '" . $id_orden_pago . "'") or die("SEGUNDO QUERY:" . mysql_error());
            registra_transaccion("Actualizar Datos Basicos con Afectacion Presupuestaria (" . $id_orden_pago . ")", $login, $fh, $pc, 'orden_pago');
        }
    }
    $sql                = mysql_query("select * from orden_pago where idorden_pago = " . $id_orden_pago . "");
    $bus_consulta       = mysql_fetch_array($sql);
    $sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_consulta["tipo"] . "'");
    $bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);

    if ($bus_consulta["idcategoria_programatica"] != '0') {
        $sql_categoria_programatica = mysql_query("select * from categoria_programatica where idcategoria_programatica = " . $bus_consulta["idcategoria_programatica"] . "");
        $bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
        $sql_unidad_ejecutora       = mysql_query("select * from unidad_ejecutora where idunidad_ejecutora = " . $bus_categoria_programatica["idunidad_ejecutora"] . "");
        $bus_unidad_ejecutora       = mysql_fetch_array($sql_unidad_ejecutora);
        $categoria_programatica     = $bus_categoria_programatica["codigo"] . ' ' . $bus_unidad_ejecutora["denominacion"];
    } else {
        $categoria_programatica = 'Categoria no utilizada';
    }

    $sql_proveedor = mysql_query("select * from beneficiarios where idbeneficiarios = " . $bus_consulta["idbeneficiarios"] . "");
    $bus_proveedor = mysql_fetch_array($sql_proveedor);

    $beneficiario  = $bus_proveedor["nombre"];
    $contribuyente = $bus_proveedor["contribuyente_ordinario"];

    $sql_tiene_retencion = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago =" . $id_orden_pago . "");
    $num_tiene_retencion = mysql_num_rows($sql_tiene_retencion);

    switch ($bus_consulta["estado"]) {
        case "elaboracion":
            $mostrar_estado = "En Elaboraci&oacute;n";
            break;
        case "procesado":
            $mostrar_estado = "Procesado";
            break;
        case "conformado":
            $mostrar_estado = "Conformado";
            break;
        case "anulado":
            $mostrar_estado  = "Anulado";
            $fecha_anulacion = '<strong>' . ' ... Fecha de Anulaci&oacute;n: ' . $bus_consulta["fecha_anulacion"] . '</strong>';
            break;
        case "parcial":
            $mostrar_estado = "Parcial";
            break;
        case "pagada":
            $sql_cheque     = mysql_query("select * from pagos_financieros where idorden_pago = '" . $bus_consulta["idorden_pago"] . "'");
            $bus_cheque     = mysql_fetch_array($sql_cheque);
            $mostrar_estado = "Pagado : " . $bus_cheque["numero_cheque"] . " : " . $bus_cheque["fecha_cheque"];
            break;
    }

    $exento_total     = 0;
    $subtotal_total   = 0;
    $impuesto_total   = 0;
    $total_total      = 0;
    $total_retenido   = 0;
    $total_a_pagar    = 0;
    $exento_totalop   = 0;
    $subtotal_totalop = 0;
    $impuesto_totalop = 0;
    $total_totalop    = 0;
    $total_retenidoop = 0;
    $total_a_pagarop  = 0;

    if ($bus_consulta["forma_pago"] == "parcial" or $bus_consulta["forma_pago"] == "valuacion") {
        $sql_relacion = mysql_query("select * from relacion_pago_compromisos where idorden_pago = " . $id_orden_pago . "") or die(mysql_error());
        $bus_relacion = mysql_fetch_array($sql_relacion);
        //echo "select * from orden_compra_servicio where idorden_compra_servicio = ".$bus_relacion["idorden_compra_servicio"]."";
        $num_relacion = mysql_num_rows($sql_relacion);
        if ($num_relacion > 0) {
            $sql_orden_compra = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = " . $bus_relacion["idorden_compra_servicio"] . "") or die(mysql_error());
            $bus_orden_compra = mysql_fetch_array($sql_orden_compra);
            $exento_total     = $bus_orden_compra["exento"];
            $subtotal_total   = $bus_orden_compra["sub_total"];
            $impuesto_total   = $bus_orden_compra["impuesto"];
            $total_total      = $bus_orden_compra["exento"] + $bus_orden_compra["sub_total"] + $bus_orden_compra["impuesto"];
        }
        $exento_totalop   = $bus_consulta["exento"];
        $subtotal_totalop = $bus_consulta["sub_total"];
        $impuesto_totalop = $bus_consulta["impuesto"];
        $total_totalop    = $bus_consulta["total"];
        $total_retenidoop = $bus_consulta["total_retenido"];
        $total_a_pagarop  = $bus_consulta["total_a_pagar"];
    } else {
//      echo "ACA";
        $exento_total   = $bus_consulta["exento"];
        $subtotal_total = $bus_consulta["sub_total"];
        $impuesto_total = $bus_consulta["impuesto"];
        $total_total    = $bus_consulta["total"];
        $total_retenido = $bus_consulta["total_retenido"];
        $total_a_pagar  = $bus_consulta["total_a_pagar"];
        //$total_a_pagar = $exento_total + $subtotal_total + $impuesto_total - $total_retenido;
    }

    //echo 'TOTAL A PAGAR '.$total_a_pagar;

    //<!-- TABLA DE DATOS PENDIENTES -->

    $exento_pendiente    = 0;
    $sub_total_pendiente = 0;
    $impuesto_pendiente  = 0;
    $total_pendiente     = 0;

    $sql_relacion = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '" . $id_orden_pago . "'");
    $bus_relacion = mysql_fetch_array($sql_relacion);
    // AQUI TENGO EL ID DE LA ORDEN DE COMPRA
    //        echo "select * from relacion_pago_compromisos where idorden_compra_servicio = '".$bus_relacion["idorden_compra_servicio"]."'";
    if ($bus_consulta["estado"] != "elaboracion") {
        $sql_relacion_pagos = mysql_query("select * from relacion_pago_compromisos
																			where idorden_compra_servicio = '" . $bus_relacion["idorden_compra_servicio"] . "'");
    } else {
        //$sql_relacion_pagos = mysql_query("select * from relacion_pago_compromisos where idorden_compra_servicio = '".$bus_relacion["idorden_compra_servicio"]."'
        //                                                        and idorden_pago != '".$id_orden_pago."'");
        /*
        echo "(select * from gestion_".$anio.".relacion_pago_compromisos
        where idorden_compra_servicio = '".$bus_relacion["idorden_compra_servicio"]."'
        and idorden_pago != '".$id_orden_pago."')
        UNION
        select * from gestion_".$_SESSION["anio_fiscal"].".relacion_pago_compromisos
        where idorden_compra_servicio = '".$bus_relacion["idorden_compra_servicio"]."'
        and idorden_pago != '".$id_orden_pago."')";
         */

        $sql_relacion_pagos = mysql_query("select * from relacion_pago_compromisos
																			where idorden_compra_servicio = '" . $bus_relacion["idorden_compra_servicio"] . "'
																			and idorden_pago != '" . $id_orden_pago . "'");
    }
    $num_relacion_pagos = mysql_num_rows($sql_relacion_pagos);
    //echo " documentos relacionados".$num_relacion_pagos;
    if ($num_relacion_pagos != 0) {
        //echo "PASO POR ACA";
        while ($bus_relacion_pagos = mysql_fetch_array($sql_relacion_pagos)) {
            $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '" . $bus_relacion_pagos["idorden_pago"] . "'
																			and anticipo != 'si' and estado != 'elaboracion' and estado != 'anulado'");
            //if (mysql_num_rows($sql_orden_pago) > 0){
            $bus_orden_pago = mysql_fetch_array($sql_orden_pago);
            /*}else{
            $sql_orden_pago = mysql_query("select * from orden_pago
            where idorden_pago = '".$bus_relacion_pagos["idorden_pago"]."'
            and anticipo != 'si' and estado != 'elaboracion' and estado != 'anulado'");
            $bus_orden_pago = mysql_fetch_array($sql_orden_pago);
            }*/
            $suma_total_exento += $bus_orden_pago["exento"];
            $suma_total_subtotal += $bus_orden_pago["sub_total"];
            $suma_total_impuesto += $bus_orden_pago["impuesto"];
            $suma_total_total += $bus_orden_pago["total"];

            $id_orden_compra_servicio = $bus_relacion_pagos["idorden_compra_servicio"];
        }
        //echo $suma_total_impuesto;
        $sql_orden_compra = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '" . $id_orden_compra_servicio . "'");
        $bus_orden_compra = mysql_fetch_array($sql_orden_compra);

        //echo $bus_orden_compra["exento"];

        $exento_pendiente    = $bus_orden_compra["exento"] - $suma_total_exento;
        $sub_total_pendiente = $bus_orden_compra["sub_total"] - $suma_total_subtotal;
        $impuesto_pendiente  = $bus_orden_compra["impuesto"] - $suma_total_impuesto;
        $total_pendiente     = $bus_orden_compra["total"] - $suma_total_total;
        //$total_pendiente = $exento_pendiente + $sub_total_pendiente + $impuesto_pendiente;

    } else {
        $exento_pendiente    = $exento_total;
        $sub_total_pendiente = $subtotal_total;
        $impuesto_pendiente  = $impuesto_total;
        $total_pendiente     = $total_total;
    }
    //echo "EXENTO: ".$exento_pendiente;

    // <!-- TABLA DE DATOS ACTUALES -->

    $sql_relacion = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '" . $id_orden_pago . "'");
    $bus_relacion = mysql_fetch_array($sql_relacion);
    $num_relacion = mysql_num_rows($sql_relacion);
    if ($num_relacion != 0) {
        //echo "select * from relacion_pagos_compromisos where idorden_compra_servicio = ".$bus_relacion["idorden_compra_servicio"]."";
        $sql_orden_compra = mysql_query("select * from relacion_pago_compromisos where idorden_compra_servicio = " . $bus_relacion["idorden_compra_servicio"] . "");
        $num_orden_compra = mysql_num_rows($sql_orden_compra);
        if ($num_orden_compra == 0) {
            if ($bus_consulta["estado"] == "procesado") {

                $es_parcial = false; //MUESTRA LOS MONTOS DE LA ORDEN DE PAGO
            } else {

                $es_parcial = true; // MUESTRA LOS MONTOS PENDIENTES
            }
        } else {
            $es_parcial = false;
        }
    }

    echo $bus_consulta["numero_orden"] . "|.|" .
    $bus_consulta["fecha_orden"] . "|.|" .
    $bus_consulta["fecha_elaboracion"] . "|.|" .
    $bus_consulta["estado"] . "|.|" .
    '<STRONG>' . $mostrar_estado . '</strong>' . "|.|" .
    $fecha_anulacion . "|.|" .
    $bus_consulta["tipo"] . "|.|" .
    $bus_consulta["idcategoria_programatica"] . "|.|" .
    $categoria_programatica . "|.|" .
    $bus_consulta["idfuente_financiamiento"] . "|.|" .
    $bus_consulta["cofinanciamiento"] . "|.|" .
    $bus_consulta["idtipo_presupuesto"] . "|.|" .
    $bus_consulta["anio"] . "|.|" .
    $bus_consulta["justificacion"] . "|.|" .
    $bus_consulta["observaciones"] . "|.|" .
    $bus_consulta["ordenado_por"] . "|.|" .
    $bus_consulta["cedula_ordenado"] . "|.|" .
    $bus_consulta["idbeneficiarios"] . "|.|" .
    $beneficiario . "|.|" .
    $contribuyente . "|.|" .
    $bus_tipo_documento["compromete"] . "|.|" .
    $bus_tipo_documento["causa"] . "|.|" .
    $bus_tipo_documento["paga"] . "|.|" .
    $exento_total . "|.|" .
    number_format($exento_total, 2, ',', '.') . "|.|" .
    $subtotal_total . "|.|" .
    number_format($subtotal_total, 2, ',', '.') . "|.|" .
    $impuesto_total . "|.|" .
    number_format($impuesto_total, 2, ',', '.') . "|.|" .
    $total_total . "|.|" .
    number_format($total_total, 2, ',', '.') . "|.|" .
    $total_retenido . "|.|" .
    number_format($total_retenido, 2, ',', '.') . "|.|" .
    $total_a_pagar . "|.|" .
    number_format($total_a_pagar, 2, ',', '.') . "|.|" .
    $bus_tipo_documento["multi_categoria"] . "|.|" .
    $num_tiene_retencion . "|.|" .
    $exento_pendiente . "|.|" .
    number_format($exento_pendiente, 2, ',', '.') . "|.|" .
    $sub_total_pendiente . "|.|" .
    number_format($sub_total_pendiente, 2, ',', '.') . "|.|" .
    $impuesto_pendiente . "|.|" .
    number_format($impuesto_pendiente, 2, ',', '.') . "|.|" .
    $total_pendiente . "|.|" .
    number_format($total_pendiente, 2, ',', '.') . "|.|" .
    $bus_consulta["forma_pago"] . "|.|" .
    $es_parcial . "|.|" .
    $bus_consulta["anticipo"] . "|.|" .
    $bus_consulta["numero_proyecto"] . "|.|" .
    $bus_consulta["numero_contrato"] . "|.|" .
    $exento_totalop . "|.|" .
    number_format($exento_totalop, 2, ',', '.') . "|.|" .
    $subtotal_totalop . "|.|" .
    number_format($subtotal_totalop, 2, ',', '.') . "|.|" .
    $impuesto_totalop . "|.|" .
    number_format($impuesto_totalop, 2, ',', '.') . "|.|" .
    $total_totalop . "|.|" .
    number_format($total_totalop, 2, ',', '.') . "|.|" .
    $total_retenidoop . "|.|" .
    number_format($total_retenidoop, 2, ',', '.') . "|.|" .
    $total_a_pagarop . "|.|" .
    number_format($total_a_pagarop, 2, ',', '.') . "|.|" .
        $id_orden_pago . "|.|" .
        $bus_tipo_documento["multi_categoria"] . "|.|";

    ?>

<?
}

//*******************************************************************************************************************************************
//********************************************* ACTUALIZAR LISTA DE TOTALES DE LAS ORDENES DE COMPRA ***************************************
//*******************************************************************************************************************************************
if ($ejecutar == "actualizarListaDeTotales") {
    $sql_relacion = mysql_query("select * from relacion_impuestos_ordenes_compras where idorden_pago = " . $id_orden_pago . "");
    $bus_relacion = mysql_fetch_array($sql_relacion);

    $sql = mysql_query("select * from articulos_compra_servicio where idorden_pago = " . $id_orden_pago . "");

    while ($bus = mysql_fetch_array($sql)) {
        $exento += $bus["exento"];
        $sub_total += $bus["total"];
        $total_impuesto += $bus["impuesto"];
        $total_general += ($bus["total"] + $bus["impuesto"] + $bus["exento"]);
    }
    $actualiza_totales = mysql_query("update orden_pago set sub_total = '" . $sub_total . "',
															impuesto = '" . $total_impuesto . "',
															exento = '" . $exento . "',
															total = (exento+sub_total+impuesto)',
															total_a_pagar = total-total_retenido
															where idorden_pago=" . $id_orden_pago . " ") or die(mysql_error());

    if ($bus_relacion["estado"] == "sin disponibilidad") {
        $color = "#FFFF00";
    } else if ($bus_relacion["estado"] == "rechazado") {
        $color = "#FF0000";
    } else {
        $color = "";
    }

    ?>
        <b>Exento:</b> <?=number_format($exento, 2, ',', '.')?>
        | <b>Sub Total:</b> <?=number_format($sub_total, 2, ',', '.')?>
        | <span style="background-color:<?=$color?>"><b>Impuestos:</b> <?=number_format($total_impuesto, 2, ',', '.')?></span>
        | <b>Total Bs:</b> <?=number_format($total_general, 2, ',', '.')?>
        <?
}

//*******************************************************************************************************************************************
//********************************************* ACTUALIZAR LISTA DE TOTALES DE LAS PARTIDAS ***************************************
//*******************************************************************************************************************************************
if ($ejecutar == "actualizarTotalesPartidas") {
    $sql                = mysql_query("select * from orden_pago where idorden_pago = " . $id_orden_pago . "");
    $bus                = mysql_fetch_array($sql);
    $sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus["tipo"] . "'");
    $bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);
    if ($bus_tipo_documento["compromete"] == 'no' and $bus_tipo_documento["causa"] == 'si' and $bus_tipo_documento["paga"] == 'no') {
        if ($bus_tipo_documento["multi_categoria"] == "no") {
            $sql_partidas = mysql_query("select * from partidas_orden_pago where idorden_pago = '" . $id_orden_pago . "'");
            while ($bus_partidas = mysql_fetch_array($sql_partidas)) {
                $monto += $bus_partidas["monto"];
            }
            echo "<strong>Total Bsf: </strong>" . number_format($monto, 2, ',', '.');
        } else {
            $monto = $bus["sub_total"] - $bus["exento"];
            echo "<strong>Total Bsf: </strong>" . number_format($bus["sub_total"], 2, ',', '.');
        }
        //$sql_actualizar = mysql_query("update orden_pago set total = ".$monto." where idorden_pago = ".$id_orden_pago."");
    } else {
        $sql_partidas = mysql_query("select * from partidas_orden_pago where idorden_pago = '" . $id_orden_pago . "'");
        while ($bus_partidas = mysql_fetch_array($sql_partidas)) {
            $monto += $bus_partidas["monto"];
        }
        echo "<strong>Total Bsf: </strong>" . number_format($monto, 2, ',', '.');
    }

}

// ????????????????????????????????  CORREGIR  ???????????????????????????????????????????????????????????????????????????????????????????????

//*******************************************************************************************************************************************
//********************************************* LISTA DE PARTIDAS ASOCIADAS A LOS MATERIALES SELECCIONADOS **********************************
//*******************************************************************************************************************************************
if ($ejecutar == "mostrarPartidas") {
    $sql = mysql_query("select * from partidas_orden_pago where idorden_pago = " . $id_orden_pago . " and
																		idcategoria_programatica = " . $id_categoria_programatica . "");

    $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '" . $id_orden_pago . "'");
    $bus_orden_pago = mysql_fetch_array($sql_orden_pago);

    $sql_tipos_documentos = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_orden_pago["tipo"] . "'");
    $bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos);

    $sql_categoria = mysql_query("select * from orden_pago,
									relacion_pago_compromisos,
									orden_compra_servicio,
									articulos_compra_servicio,
									categoria_programatica
									where orden_pago.idorden_pago = '" . $id_orden_pago . "'
									and relacion_pago_compromisos.idorden_pago = orden_pago.idorden_pago
									and orden_compra_servicio.idorden_compra_servicio = relacion_pago_compromisos.idorden_compra_servicio
									and articulos_compra_servicio.idorden_compra_servicio = orden_compra_servicio.idorden_compra_servicio
									and categoria_programatica.idcategoria_programatica = orden_compra_servicio.idcategoria_programatica");

    $bus_categoria = mysql_fetch_array($sql_categoria);

    $num = mysql_num_rows($sql);
    if ($num != 0) {
        ?>
    <table width="85%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>

           <?
        if ($bus_tipos_documentos["multi_categoria"] == "si") {
            ?>
				<td class="Browse" colspan="4"><div align="center">Categoria</div></td>
			   <?
        }
        ?>
            <td class="Browse" colspan="4"><div align="center">Partida</div></td>

            <td class="Browse"><div align="center">Descripci&oacute;n</div></td>
            <td class="Browse"><div align="center">Disponible</div></td>
            <td class="Browse"><div align="center">Monto a Comprometer</div></td>
          </tr>
          </thead>
          <?
        while ($bus = mysql_fetch_array($sql)) {
            if ($bus["estado"] == "sobregiro") {
                ?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
            } else if ($bus["estado"] == "disponible") {
                ?>

            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			<?
            }

            $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = " . $bus["idclasificador_presupuestario"] . "");
            $bus_clasificador = mysql_fetch_array($sql_clasificador);
            ?>
            <td class='Browse' align='left'><?=$bus_categoria["codigo"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["partida"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["generica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["sub_especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["denominacion"]?></td>
          <?
            $sql_maestro = mysql_query("select * from maestro_presupuesto where idclasificador_presupuestario = " . $bus["idclasificador_presupuestario"] . "");
            $bus_maestro = mysql_fetch_array($sql_maestro);
            ?>
    	      <td class='Browse' align="right"><?=number_format($bus_maestro["monto_actual"] - $bus_maestro["total_compromisos"], 2, ',', '.')?></td>
	          <td class='Browse' align='right'><?=number_format($bus["monto"], 2, ',', '.')?></td>
          </tr>
          <?
        }
        ?>
        </table>
<?
    } else {
        echo "No hay Partidas Asociadas";
    }
}

// ??????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????

// *****************************************************************************************************************************************
// ************************************************* PROCESAR ORDEN DE COMPRA Y SERVICIO ***************************************************
// *****************************************************************************************************************************************

if ($ejecutar == "procesarOrdenSinAfectacion") {
    $sql_configuracion = mysql_query("select * from configuracion");
    $bus_configuracion = mysql_fetch_array($sql_configuracion);
    $anio_fiscal       = $bus_configuracion["anio_fiscal"];

    $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '" . $id_orden_pago . "'");
    $bus_orden_pago = mysql_fetch_array($sql_orden_pago);

    $tipo_orden    = $bus_orden_pago["tipo"];
    $sql_nro_orden = mysql_query("select * from tipos_documentos where idtipos_documentos = " . $tipo_orden . "");
    $bus_nro_orden = mysql_fetch_array($sql_nro_orden);

    if ($bus_nro_orden["documento_asociado"] != 0) {
        $sql_documento_asociado = mysql_query("select * from tipos_documentos where idtipos_documentos = " . $bus_nro_orden["documento_asociado"] . "");
        $bus_documento_asociado = mysql_fetch_array($sql_documento_asociado);
        $id_a_actualizar        = $bus_documento_asociado["idtipos_documentos"];
        $codigo_orden           = $bus_documento_asociado["siglas"] . "-" . $anio_fiscal . "-" . $bus_documento_asociado["nro_contador"];
        $nro_orden_pago         = $bus_documento_asociado["nro_contador"];
    } else {
        $id_a_actualizar = $tipo_orden;
        $codigo_orden    = $bus_nro_orden["siglas"] . "-" . $anio_fiscal . "-" . $bus_nro_orden["nro_contador"];
        $nro_orden_pago  = $bus_nro_orden["nro_contador"];
    }

    $sql_existe_numero = mysql_query("select * from orden_pago where numero_orden = '" . $codigo_orden . "'") or die("cero" . mysql_error());
    $bus_existe        = mysql_num_rows($sql_existe_numero);

    while ($bus_existe > 0) {
        $sql_actualizar_numero = mysql_query("update tipos_documentos set nro_contador = nro_contador + 1 where idtipos_documentos = " . $id_a_actualizar . "") or die("uno" . mysql_error());

        $sql_nro_orden = mysql_query("select * from tipos_documentos where idtipos_documentos = " . $tipo_orden . "");
        $bus_nro_orden = mysql_fetch_array($sql_nro_orden);
        if ($bus_nro_orden["documento_asociado"] != 0) {
            $sql_documento_asociado = mysql_query("select * from tipos_documentos where idtipos_documentos = " . $bus_nro_orden["documento_asociado"] . "");
            $bus_documento_asociado = mysql_fetch_array($sql_documento_asociado);
            $id_a_actualizar        = $bus_documento_asociado["idtipos_documentos"];
            $codigo_orden           = $bus_documento_asociado["siglas"] . "-" . $anio_fiscal . "-" . $bus_documento_asociado["nro_contador"];
            $nro_orden_pago         = $bus_documento_asociado["nro_contador"];
        } else {
            $id_a_actualizar = $tipo_orden;
            $codigo_orden    = $bus_nro_orden["siglas"] . "-" . $anio_fiscal . "-" . $bus_nro_orden["nro_contador"];
            $nro_orden_pago  = $bus_nro_orden["nro_contador"];
        }

        $sql_existe_numero = mysql_query("select * from orden_pago where numero_orden = '" . $codigo_orden . "'") or die("cero" . mysql_error());
        $bus_existe        = mysql_num_rows($sql_existe_numero);
    }

    $codigo_referencia = 90000000000 + $nro_orden_pago;

    $sql_actualizar_orden = mysql_query("update orden_pago set estado = 'procesado',
																				numero_orden = '" . $codigo_orden . "',
																				fecha_orden = '" . $fecha_validada . "',
																				codigo_referencia = '" . $codigo_referencia . "'
																			where idorden_pago = " . $id_orden_pago . "");

    $sql_asiento_contable = mysql_query("update asiento_contable set estado = 'procesado',
																			fecha_contable = '" . $fecha_validada . "'
											 					where iddocumento = " . $id_orden_pago . "
											 							and tipo_movimiento = 'causado'") or die("error" . mysql_error());
    $sql_retenciones  = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago = '" . $id_orden_pago . "'");
    $existe_retencion = mysql_num_rows($sql_retenciones);
    if ($existe_retencion > 0) {
        while ($lista_retenciones = mysql_fetch_array($sql_retenciones)) {
            $sql_retencion = mysql_query("update retenciones set estado = 'procesado' where idretenciones = '" . $lista_retenciones["idretencion"] . "'");
        }
    }

    echo "exito";
    registra_transaccion("Procesar Orden sin Afectacion Presupuestaria (" . $codigo_orden . ")", $login, $fh, $pc, 'orden_pago');

}

// *****************************************************************************************************************************************
// ************************************************* PROCESAR ORDEN DE COMPRA Y SERVICIO ***************************************************
// *****************************************************************************************************************************************

if ($ejecutar == "procesarOrden") {
    $sql_monto_pagar = mysql_query("update orden_pago set exento = '" . $exento_a_pagar . "',
														sub_total = '" . $sub_total_a_pagar . "',
														impuesto = '" . $impuesto_a_pagar . "',
														total = '" . $sub_total_a_pagar + $exento_a_pagar + $impuesto_a_pagar . "',
														total_retenido = '" . $total_retencion . "',
														total_a_pagar = '" . $total_a_pagar . "'
														 where idorden_pago = '" . $id_orden_pago . "'");

    $sql_orden           = mysql_query("select * from orden_pago where idorden_pago = " . $id_orden_pago . "");
    $bus_orden           = mysql_fetch_array($sql_orden);
    $anio                = $bus_orden["anio"];
    $error_suma_partidas = false;
    if ($bus_orden["forma_pago"] == "parcial" or $bus_orden["forma_pago"] == "valuacion") {
        $sql_monto_partidas = mysql_query("select * from partidas_orden_pago where idorden_pago = '" . $id_orden_pago . "'");
        while ($bus_monto_partidas = mysql_fetch_array($sql_monto_partidas)) {
            $suma_monto_partidas += $bus_monto_partidas["monto"];
        }
        if (bcsub($suma_monto_partidas, $bus_orden["total"], 2) != 0) {
            $error_suma_partidas = true;
        } else {
            $error_suma_partidas = false;
        }
    }

//echo $suma_monto_partidas." ||| ".$bus_orden["total"];

    if ($error_suma_partidas == false) {
        $aprobado     = true;
        $sql_partidas = mysql_query("select * from partidas_orden_pago where idorden_pago = " . $id_orden_pago . "");
        $num_partidas = mysql_num_rows($sql_partidas);
        if ($num_partidas > 0) {
            while ($bus_partidas = mysql_fetch_array($sql_partidas)) {
                $sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = " . $bus_partidas["idmaestro_presupuesto"] . "");
                $bus_maestro = mysql_fetch_array($sql_maestro);
                /*
                $disponible_actual = $bus_maestro["monto_actual"] ;
                $disponible_resta = $bus_maestro["total_compromisos"] + $bus_maestro["pre_compromiso"] + $bus_maestro["reservado_disminuir"];
                $disponible = bcsub ( $disponible_actual, $disponible_resta , 2);*/

                $disponible = consultarDisponibilidad($bus_partidas["idmaestro_presupuesto"]);
                //$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
                //echo "Total Actual: ".$bus_maestro["monto_actual"]."<br />";
                //echo "Total Compromisos: ".$bus_maestro["total_compromisos"]."<br />";
                //echo $disponible;
                $sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_orden["tipo"] . "'");
                $bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);
                if ($bus_tipo_documento["compromete"] == "si") {
                    if ($disponible < $bus_partidas["monto"]) {
                        $sql_update_disponible = mysql_query("update partidas_orden_pago set estado = 'sobregiro'
																					where idpartidas_orden_pago = " . $bus_partidas["idpartidas_orden_pago"] . "");

                        echo "partidasSinDisponibilidad ";
                        $aprobado = false;
                    } else {
                        if ($bus_partidas["estado"] == 'sobregiro') {
                            $sql_update_disponible = mysql_query("update partidas_orden_pago set estado = 'disponible'
																					where idpartidas_orden_pago = " . $bus_partidas["idpartidas_orden_pago"] . "");
                        }
                    }
                }
            }

            if ($aprobado == true) {
                $tipo_orden = $bus_orden["tipo"];
                //echo "select * from tipos_documentos where idtipos_documentos = ".$tipo_orden."";
                $sql_nro_orden = mysql_query("select * from tipos_documentos where idtipos_documentos = " . $tipo_orden . "");
                $bus_nro_orden = mysql_fetch_array($sql_nro_orden);
                if ($bus_nro_orden["compromete"] == 'no' and $bus_nro_orden["causa"] == 'no' and $bus_nro_orden["paga"] == 'no') {
                    $sin_afectacion = true;
                } else {
                    $sin_afectacion = false;
                }
                $sql_partidas = mysql_query("select * from partidas_orden_pago where idorden_pago = " . $id_orden_pago . "");

                if ($sin_afectacion != true) {
                    if ($bus_nro_orden["compromete"] == 'no') {
                        while ($bus_partidas = mysql_fetch_array($sql_partidas)) {
                            $sql_update_maestro = mysql_query("update maestro_presupuesto set total_causados= total_causados+" . $bus_partidas["monto"] . "
																					where idRegistro = " . $bus_partidas["idmaestro_presupuesto"] . "");

                            $sql_consulta_ordinal = mysql_query("select * from ordinal where codigo = '0000'") or die("ERROR CONSULTANDO EL ORDINAL NO APLICA" . mysql_error());
                            $bus_consulta_ordinal = mysql_fetch_array($sql_consulta_ordinal);

                            $sql_consultar_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '" . $bus_partidas["idmaestro_presupuesto"] . "' and idordinal != '" . $bus_consulta_ordinal["idordinal"] . "'") or die("ERROR CONSULTANDO EL MAESTRO 1:" . mysql_error());
                            $num_consulta_maestro  = mysql_num_rows($sql_consultar_maestro);
                            if ($num_consulta_maestro != 0) {
                                $bus_consultar_maestro = mysql_fetch_array($sql_consultar_maestro);
                                $sql_sub_espe          = mysql_query("select * from maestro_presupuesto where
							idcategoria_programatica= '" . $bus_consultar_maestro["idcategoria_programatica"] . "'
						and idtipo_presupuesto = '" . $bus_consultar_maestro["idtipo_presupuesto"] . "'
						and idfuente_financiamiento = '" . $bus_consultar_maestro["idfuente_financiamiento"] . "'
						and idclasificador_presupuestario = '" . $bus_consultar_maestro["idclasificador_presupuestario"] . "'
						and idordinal = '" . $bus_consulta_ordinal["idordinal"] . "'") or die("ERROR CONSULTANDO SUB ESPECIFICA" . mysql_error());
                                $num_sub_espe = mysql_num_rows($sql_sub_espe);
                                if ($num_sub_espe != 0) {
                                    $bus_sub_epe = mysql_fetch_array($sql_sub_espe);
                                    $sql_maestro = mysql_query("update maestro_presupuesto set
															total_compromisos = total_compromisos + " . $bus_partidas["monto"] . "
															where idRegistro = '" . $bus_sub_epe["idmaestro_presupuesto"] . "'") or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 2: " . mysql_error());

                                }

                                $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '" . $bus_consultar_maestro["idclasificador_presupuestario"] . "' and sub_especifica != '00'") or die("ERROR CONSULTANDO EL CLASIFICADOR " . mysql_error());
                                $num_clasificador = mysql_num_rows($sql_clasificador);
                                if ($num_clasificador > 0) {
                                    $bus_clasificador          = mysql_fetch_array($sql_clasificador);
                                    $sql_consulta_clasificador = mysql_query("select * from clasificador_presupuestario where partida = '" . $bus_clasificador["partida"] . "'
							and generica = '" . $bus_clasificador["generica"] . "'
							and especifica ='" . $bus_clasificador["especifica"] . "'
							and sub_especifica= '00'") or die("ERROR CONSULTANDO EL CLASIFICADOR 2:" . mysql_error());
                                    $bus_consulta_clasificador = mysql_fetch_array($sql_consulta_clasificador);

                                    $sql_id_maestro = mysql_query("select * from maestro_presupuesto where
							idcategoria_programatica= '" . $bus_consultar_maestro["idcategoria_programatica"] . "'
						and idtipo_presupuesto = '" . $bus_consultar_maestro["idtipo_presupuesto"] . "'
						and idfuente_financiamiento = '" . $bus_consultar_maestro["idfuente_financiamiento"] . "'
						and idclasificador_presupuestario = '" . $bus_consulta_clasificador["idclasificador_presupuestario"] . "'
						and idordinal = '" . $bus_consulta_ordinal["idordinal"] . "'") or die("ERROR CONSULTANDO EL MAESTRO 2:" . mysql_error());
                                    $bus_id_maestro = mysql_fetch_array($sql_id_maestro);

                                    $sql_maestro = mysql_query("update maestro_presupuesto set
															total_compromisos = total_compromisos + " . $bus_partidas["monto"] . "
															where idRegistro = '" . $bus_id_maestro["idmaestro_presupuesto"] . "'") or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 3: " . mysql_error());

                                }

                            }

                        }
                    } else {
                        while ($bus_partidas = mysql_fetch_array($sql_partidas)) {
                            $sql_update_maestro = mysql_query("update maestro_presupuesto set
																			total_compromisos = total_compromisos + '" . $bus_partidas["monto"] . "',
																			total_causados = total_causados + '" . $bus_partidas["monto"] . "'
																			where idRegistro = " . $bus_partidas["idmaestro_presupuesto"] . "") or die("ERROR AL ACTUALIZAR TOTAL COMPROMISOS Y TOTAL CAUSADOS: " . mysql_error());

                            $sql_consulta_ordinal = mysql_query("select * from ordinal where codigo = '0000'") or die("ERROR CONSULTANDO EL ORDINAL NO APLICA" . mysql_error());
                            $bus_consulta_ordinal = mysql_fetch_array($sql_consulta_ordinal);

                            $sql_consultar_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '" . $bus_partidas["idmaestro_presupuesto"] . "' and idordinal != '" . $bus_consulta_ordinal["idordinal"] . "'") or die("ERROR CONSULTANDO EL MAESTRO 1:" . mysql_error());
                            $num_consulta_maestro  = mysql_num_rows($sql_consultar_maestro);
                            if ($num_consulta_maestro != 0) {
                                $bus_consultar_maestro = mysql_fetch_array($sql_consultar_maestro);
                                $sql_sub_espe          = mysql_query("select * from maestro_presupuesto where
							idcategoria_programatica= '" . $bus_consultar_maestro["idcategoria_programatica"] . "'
						and idtipo_presupuesto = '" . $bus_consultar_maestro["idtipo_presupuesto"] . "'
						and idfuente_financiamiento = '" . $bus_consultar_maestro["idfuente_financiamiento"] . "'
						and idclasificador_presupuestario = '" . $bus_consultar_maestro["idclasificador_presupuestario"] . "'
						and idordinal = '" . $bus_consulta_ordinal["idordinal"] . "'") or die("ERROR CONSULTANDO SUB ESPECIFICA" . mysql_error());
                                $num_sub_espe = mysql_num_rows($sql_sub_espe);
                                if ($num_sub_espe != 0) {
                                    $bus_sub_epe = mysql_fetch_array($sql_sub_espe);
                                    $sql_maestro = mysql_query("update maestro_presupuesto set
															total_compromisos = total_compromisos + '" . $bus_partidas["monto"] . "',
															total_causados = total_causados + '" . $bus_partidas["monto"] . "'
															where idRegistro = " . $bus_sub_epe["idmaestro_presupuesto"] . "") or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 2: " . mysql_error());

                                }

                                $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '" . $bus_consultar_maestro["idclasificador_presupuestario"] . "' and sub_especifica != '00'") or die("ERROR CONSULTANDO EL CLASIFICADOR " . mysql_error());
                                $num_clasificador = mysql_num_rows($sql_clasificador);
                                if ($num_clasificador > 0) {
                                    $bus_clasificador          = mysql_fetch_array($sql_clasificador);
                                    $sql_consulta_clasificador = mysql_query("select * from clasificador_presupuestario where partida = '" . $bus_clasificador["partida"] . "'
							and generica = '" . $bus_clasificador["generica"] . "'
							and especifica ='" . $bus_clasificador["especifica"] . "'
							and sub_especifica= '00'") or die("ERROR CONSULTANDO EL CLASIFICADOR 2:" . mysql_error());
                                    $bus_consulta_clasificador = mysql_fetch_array($sql_consulta_clasificador);
                                    $sql_id_maestro            = mysql_query("select * from maestro_presupuesto where
							idcategoria_programatica= '" . $bus_consultar_maestro["idcategoria_programatica"] . "'
						and idtipo_presupuesto = '" . $bus_consultar_maestro["idtipo_presupuesto"] . "'
						and idfuente_financiamiento = '" . $bus_consultar_maestro["idfuente_financiamiento"] . "'
						and idclasificador_presupuestario = '" . $bus_consulta_clasificador["idclasificador_presupuestario"] . "'
						and idordinal = '" . $bus_consulta_ordinal["idordinal"] . "'") or die("ERROR CONSULTANDO EL MAESTRO 2:" . mysql_error());
                                    $bus_id_maestro = mysql_fetch_array($sql_id_maestro);

                                    $sql_maestro = mysql_query("update maestro_presupuesto set
															total_compromisos = total_compromisos + '" . $bus_partidas["monto"] . "',
															total_causados = total_causados + '" . $bus_partidas["monto"] . "'
															where idRegistro = " . $bus_id_maestro["idmaestro_presupuesto"] . "") or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 3: " . mysql_error());

                                }

                            }

                        }
                    }

                }
                $anio_fiscal = $_SESSION["anio_fiscal"];

                if ($bus_nro_orden["documento_asociado"] != 0) {
                    $sql_documento_asociado = mysql_query("select * from tipos_documentos where idtipos_documentos = " . $bus_nro_orden["documento_asociado"] . "");
                    $bus_documento_asociado = mysql_fetch_array($sql_documento_asociado);
                    $id_a_actualizar        = $bus_documento_asociado["idtipos_documentos"];
                    $codigo_orden           = $bus_documento_asociado["siglas"] . "-" . $anio_fiscal . "-" . $bus_documento_asociado["nro_contador"];
                    $nro_orden_pago         = $bus_documento_asociado["nro_contador"];
                } else {
                    $id_a_actualizar = $tipo_orden;
                    $codigo_orden    = $bus_nro_orden["siglas"] . "-" . $anio_fiscal . "-" . $bus_nro_orden["nro_contador"];
                    $nro_orden_pago  = $bus_nro_orden["nro_contador"];
                }

                $sql_existe_numero = mysql_query("select * from orden_pago where numero_orden = '" . $codigo_orden . "'") or die("cero" . mysql_error());
                $bus_existe        = mysql_num_rows($sql_existe_numero);

                while ($bus_existe > 0) {
                    $sql_actualizar_numero = mysql_query("update tipos_documentos set nro_contador = nro_contador + 1 where idtipos_documentos = " . $id_a_actualizar . "") or die("uno" . mysql_error());

                    $sql_nro_orden = mysql_query("select * from tipos_documentos where idtipos_documentos = " . $tipo_orden . "");
                    $bus_nro_orden = mysql_fetch_array($sql_nro_orden);
                    if ($bus_nro_orden["documento_asociado"] != 0) {
                        $sql_documento_asociado = mysql_query("select * from tipos_documentos where idtipos_documentos = " . $bus_nro_orden["documento_asociado"] . "");
                        $bus_documento_asociado = mysql_fetch_array($sql_documento_asociado);
                        $id_a_actualizar        = $bus_documento_asociado["idtipos_documentos"];
                        $codigo_orden           = $bus_documento_asociado["siglas"] . "-" . $anio_fiscal . "-" . $bus_documento_asociado["nro_contador"];
                        $nro_orden_pago         = $bus_documento_asociado["nro_contador"];
                    } else {
                        $id_a_actualizar = $tipo_orden;
                        $codigo_orden    = $bus_nro_orden["siglas"] . "-" . $anio_fiscal . "-" . $bus_nro_orden["nro_contador"];
                        $nro_orden_pago  = $bus_nro_orden["nro_contador"];
                    }

                    $sql_existe_numero = mysql_query("select * from orden_pago where numero_orden = '" . $codigo_orden . "'") or die("cero" . mysql_error());
                    $bus_existe        = mysql_num_rows($sql_existe_numero);
                }
                if ($sin_afectacion == false) {
                    $sql_suma_partidas = mysql_query("select SUM(monto) AS suma from partidas_orden_pago where idorden_pago = " . $id_orden_pago . "");
                    $bus_suma_partidas = mysql_fetch_array($sql_suma_partidas);

                    $sql_tipo_orden = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $tipo_orden . "'");
                    $bus_tipo_orden = mysql_fetch_array($sql_tipo_orden);

                    if ($bus_tipo_orden["multi_categoria"] == "si") {
                        $sql_actualizar_orden = mysql_query("update orden_pago set estado = 'procesado',
												numero_orden = '" . $codigo_orden . "',
												fecha_orden = '" . $fecha_validada . "',
												total = sub_total-exento
												where idorden_pago = " . $id_orden_pago . "") or die("AQUI FUE EL ERROR: " . mysql_error());
                    } else {
                        $sql_actualizar_orden = mysql_query("update orden_pago set estado = 'procesado',
															numero_orden = '" . $codigo_orden . "',
															fecha_orden = '" . $fecha_validada . "',
															total = " . $bus_suma_partidas["suma"] . ",
															total_retenido = '" . $total_retencion . "',
															total_a_pagar = '" . $total_a_pagar . "'
														where idorden_pago = " . $id_orden_pago . "") or die("AQUI FUE EL ERROR: " . mysql_error());

                    }

                } else {
                    $sql_actualizar_orden = mysql_query("update orden_pago set estado = 'procesado',
																numero_orden = '" . $codigo_orden . "',
																fecha_orden = '" . $fecha_validada . "'
															where idorden_pago = " . $id_orden_pago . "") or die("AQUI FUE EL ERROR: " . mysql_error());
                }

                $sql_actualizar_numero = mysql_query("update tipos_documentos set nro_contador = nro_contador + 1 where idtipos_documentos = " . $id_a_actualizar . "") or die("uno" . mysql_error());
                //echo $id_a_actualizar;

                //VERIFICO LA FORMA DE PAGO PARA CAMBIAR EL ESTADO DE LOS DOCUMENTOS Y RETENCIONES RELACIONADAS
                if ($bus_orden["forma_pago"] == "parcial" or $bus_orden["forma_pago"] == "valuacion") {

                    $sql_relacion_orden_compra = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '" . $id_orden_pago . "'");
                    $bus_relacion_orden_compra = mysql_fetch_array($sql_relacion_orden_compra);

                    $sql_orden_compra   = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '" . $bus_relacion_orden_compra["idorden_compra_servicio"] . "'");
                    $bus_orden_compra   = mysql_fetch_array($sql_orden_compra);
                    $total_orden_compra = $bus_orden_compra["total"];

                    $sql_relacion            = mysql_query("select * from relacion_pago_compromisos where idorden_compra_servicio = " . $bus_orden_compra["idorden_compra_servicio"] . "");
                    $suma_total_ordenes_pago = 0;
                    while ($bus_relacion = mysql_fetch_array($sql_relacion)) {
                        $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = " . $bus_relacion["idorden_pago"] . " and (estado != 'elaboracion' and estado != 'anulado')");
                        $bus_orden_pago = mysql_fetch_array($sql_orden_pago);
                        if ($bus_orden_pago["anticipo"] != 'si') {
                            $suma_total_ordenes_pago += $bus_orden_pago["total"];
                        }
                    }
                    //echo "SUMA TOTAL ORDENES DE PAGO: ".$suma_total_ordenes_pago." SUMA TOTAL ORDENES DE COMPRA: ".$total_orden_compra;
                    if ($suma_total_ordenes_pago < $total_orden_compra) {
                        $sql_actualizar = mysql_query("update orden_compra_servicio set estado = 'parcial' where idorden_compra_servicio = '" . $bus_orden_compra["idorden_compra_servicio"] . "'");
                    } else {
                        $sql_actualizar = mysql_query("update orden_compra_servicio set estado = 'pagado' where idorden_compra_servicio = '" . $bus_orden_compra["idorden_compra_servicio"] . "'");
                    }

                    $sql_relacion_pago_retencion = mysql_query("select * from relacion_orden_pago_retencion
																			   		where idorden_pago = '" . $id_orden_pago . "'");
                    while ($bus_relacion_pago_retencion = mysql_fetch_array($sql_relacion_pago_retencion)) {
                        $sql_update_retenciones = mysql_query("update retenciones set estado = 'pagada'
																		where idretenciones = '" . $bus_relacion_pago_retencion["idretencion"] . "'");
                    }

                } else {

                    //if($bus_orden["forma_pago"] != "parcial"){
                    $sql_relacion_pago_compromisos = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '" . $id_orden_pago . "'");
                    while ($bus_relacion_pago_compromisos = mysql_fetch_array($sql_relacion_pago_compromisos)) {
                        $sql_update_retenciones = mysql_query("update retenciones set estado = 'pagada'
																		where iddocumento = '" . $bus_relacion_pago_compromisos["idorden_compra_servicio"] . "'");
                        $sql_update_orden_compra = mysql_query("update orden_compra_servicio set estado = 'pagado'
																		where idorden_compra_servicio = '" . $bus_relacion_pago_compromisos["idorden_compra_servicio"] . "'");
                    }

                }

                echo "exito";

                $codigo_referencia = 90000000000 + $nro_orden_pago;

                $sql_actualizar_orden = mysql_query("update orden_pago set codigo_referencia = '" . $codigo_referencia . "' where idorden_pago = '" . $id_orden_pago . "'");

                registra_transaccion("Procesar Orden de Pago (" . $codigo_orden . ")", $login, $fh, $pc, 'orden_pago');
            }

            // SI LA ORDEN NO TIENE PARTIDA PRESUPUESTARIA
        } else {
            $tipo_orden    = $bus_orden["tipo"];
            $sql_nro_orden = mysql_query("select * from tipos_documentos where idtipos_documentos = " . $tipo_orden . "");
            $bus_nro_orden = mysql_fetch_array($sql_nro_orden);
            if ($bus_nro_orden["compromete"] == 'no' and $bus_nro_orden["causa"] == 'no' and $bus_nro_orden["paga"] == 'no') {
                $sin_afectacion = true;
            } else {
                $sin_afectacion = false;
            }
            if ($sin_afectacion == false) {
                echo "sinPartidas";
            } else {
                $anio_fiscal = $_SESSION["anio_fiscal"];

                if ($bus_nro_orden["documento_asociado"] != 0) {
                    $sql_documento_asociado = mysql_query("select * from tipos_documentos where idtipos_documentos = " . $bus_nro_orden["documento_asociado"] . "");
                    $bus_documento_asociado = mysql_fetch_array($sql_documento_asociado);
                    $id_a_actualizar        = $bus_documento_asociado["idtipos_documentos"];
                    $codigo_orden           = $bus_documento_asociado["siglas"] . "-" . $anio_fiscal . "-" . $bus_documento_asociado["nro_contador"];
                    $nro_orden_pago         = $bus_documento_asociado["nro_contador"];
                } else {
                    $id_a_actualizar = $tipo_orden;
                    $codigo_orden    = $bus_nro_orden["siglas"] . "-" . $anio_fiscal . "-" . $bus_nro_orden["nro_contador"];
                    $nro_orden_pago  = $bus_nro_orden["nro_contador"];
                }

                $sql_existe_numero = mysql_query("select * from orden_pago where numero_orden = '" . $codigo_orden . "'") or die("cero" . mysql_error());
                $bus_existe        = mysql_num_rows($sql_existe_numero);

                while ($bus_existe > 0) {
                    $sql_actualizar_numero = mysql_query("update tipos_documentos set nro_contador = nro_contador + 1 where idtipos_documentos = " . $id_a_actualizar . "") or die("uno" . mysql_error());

                    $sql_nro_orden = mysql_query("select * from tipos_documentos where idtipos_documentos = " . $tipo_orden . "");
                    $bus_nro_orden = mysql_fetch_array($sql_nro_orden);
                    if ($bus_nro_orden["documento_asociado"] != 0) {
                        $sql_documento_asociado = mysql_query("select * from tipos_documentos where idtipos_documentos = " . $bus_nro_orden["documento_asociado"] . "");
                        $bus_documento_asociado = mysql_fetch_array($sql_documento_asociado);
                        $id_a_actualizar        = $bus_documento_asociado["idtipos_documentos"];
                        $codigo_orden           = $bus_documento_asociado["siglas"] . "-" . $anio_fiscal . "-" . $bus_documento_asociado["nro_contador"];
                        $nro_orden_pago         = $bus_documento_asociado["nro_contador"];
                    } else {
                        $id_a_actualizar = $tipo_orden;
                        $codigo_orden    = $bus_nro_orden["siglas"] . "-" . $anio_fiscal . "-" . $bus_nro_orden["nro_contador"];
                        $nro_orden_pago  = $bus_nro_orden["nro_contador"];
                    }

                    $sql_existe_numero = mysql_query("select * from orden_pago where numero_orden = '" . $codigo_orden . "'") or die("cero" . mysql_error());
                    $bus_existe        = mysql_num_rows($sql_existe_numero);
                }

                $sql_actualizar_orden = mysql_query("update orden_pago set estado = 'procesado',
																numero_orden = '" . $codigo_orden . "',
																fecha_orden = '" . $fecha_validada . "'
															where idorden_pago = " . $id_orden_pago . "") or die("AQUI FUE EL ERROR: " . mysql_error());

                if ($bus_orden["forma_pago"] != "parcial" && $bus_orden["forma_pago"] != "valuacion") {
                    $sql_relacion_pago_compromisos = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '" . $id_orden_pago . "'");
                    while ($bus_relacion_pago_compromisos = mysql_fetch_array($sql_relacion_pago_compromisos)) {
                        $sql_update_retenciones = mysql_query("update retenciones set estado = 'pagado'
																		where iddocumento = '" . $bus_relacion_pago_compromisos["idorden_compra_servicio"] . "'");
                        $sql_update_orden_compra = mysql_query("update orden_compra_servicio set estado = 'pagado'
																		where idorden_compra_servicio = '" . $bus_relacion_pago_compromisos["idorden_compra_servicio"] . "'");
                    }

                } else {
                    $sql_relacion_pago_compromisos = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '" . $id_orden_pago . "'");
                    $bus_relacion_pago_compromisos = mysql_fetch_array($sql_relacion_pago_compromisos);
                    $id_orden_compra_servicios     = $bus_relacion_pago_compromisos["idorden_compra_servicio"];
                    $sql_relacion                  = mysql_query("select * from relacion_pago_compromisos where
																					idorden_compra_servicio = '" . $id_orden_compra_servicios . "'");
                    while ($bus_relacion = mysql_fetch_array($sql_relacion)) {
                        $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = " . $bus_relacion["idorden_pago"] . " and estado != 'elaboracion' and anticipo = 'no'");
                        $bus_orden_pago = mysql_fetch_array($sql_orden_pago);
                        $total += $bus_orden_pago["total"];
                    }

                    $sql_orden_compra = mysql_query("select * from orden_compra_servicio
																		where idorden_compra_servicio = '" . $id_orden_compra_servicios . "'");
                    $bus_orden_compra = mysql_fetch_array($sql_orden_compra);
                    //settype(integer,$bus_orden_compra["total"]);
                    //echo "TOTAL: ".gettype($total)." TOTAL DE LA ORDEN DE COMPRA: ".gettype($bus_orden_compra["total"]);
                    if ($total == $bus_orden_compra["total"]) {
                        //echo "SON IGUALES";
                        $sql_update_retenciones  = mysql_query("update retenciones set estado = 'pagada' where iddocumento = '" . $id_orden_compra_servicios . "'");
                        $sql_update_orden_compra = mysql_query("update orden_compra_servicio set estado = 'ordenado' where idorden_compra_servicio = '" . $id_orden_compra_servicios . "'");
                    }

                }

                echo "exito";

                $codigo_referencia = 90000000000 + $nro_orden_pago;

                $sql_actualizar_orden = mysql_query("update orden_pago set codigo_referencia = '" . $codigo_referencia . "' where idorden_pago = '" . $id_orden_pago . "'");

                registra_transaccion("Procesar Orden de Pago (" . $codigo_orden . ")", $login, $fh, $pc, 'orden_pago');
            }

        }
    } else {
        echo "montosDiferentes";
    }
}

// *****************************************************************************************************************************************
// ************************************************* DUPLICAR ORDEN DE COMPRA Y SERVICIO ***************************************************
// *****************************************************************************************************************************************

if ($ejecutar == "duplicarOrden") {
    $sql_orden = mysql_query("select * from orden_pago where idorden_pago = " . $id_orden_pago . "");
    $bus_orden = mysql_fetch_array($sql_orden); // DUPLICACION DE LOS DATOS BASICOS DE LA ORDEN DE COMPRA

    $sql_consultar_tipo = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_orden["tipo"] . "'");
    $bus_consultar_tipo = mysql_fetch_array($sql_consultar_tipo);

    if (($bus_consultar_tipo["compromete"] == "no" and $bus_consultar_tipo["paga"] == "no" and $bus_consultar_tipo["causa"] == "no") || ($bus_consultar_tipo["causa"] == "si" and $bus_consultar_tipo["compromete"] == "si" and $bus_consultar_tipo["paga"] == "no")) {

        $sql_insert_orden = mysql_query("insert into orden_pago(tipo,
																		fecha_elaboracion,
																		proceso,
																		numero_documento,
																		idbeneficiarios,
																		idcategoria_programatica,
																		anio,
																		idfuente_financiamiento,
																		idtipo_presupuesto,
																		justificacion,
																		observaciones,
																		nro_documento,
																		fecha_documento,
																		ordenado_por,
																		cedula_ordenado,
																		numero_proyecto,
																		numero_contrato,
																		exento,
																		sub_total,
																		impuesto,
																		total,
																		total_a_pagar,
																		estado,
																		idrazones_devolucion,
																		observaciones_devolucion,
																		numero_remision,
																		fecha_remision,
																		recibido_por,
																		cedula_recibido,
																		fecha_recibido,
																		ubicacion,
																		status,
																		usuario,
																		fechayhora,
																		duplicados,
																		forma_pago)value(
																						'" . $bus_orden["tipo"] . "',
																						'" . date("Y-m-d") . "',
																						'" . $bus_orden["proceso"] . "',
																						'" . $bus_orden["numero_documento"] . "',
																						'" . $bus_orden["idbeneficiarios"] . "',
																						'" . $bus_orden["idcategoria_programatica"] . "',
																						'" . $bus_orden["anio"] . "',
																						'" . $bus_orden["idfuente_financiamiento"] . "',
																						'" . $bus_orden["idtipo_presupuesto"] . "',
																						'" . $bus_orden["justificacion"] . "',
																						'" . $bus_orden["observaciones"] . "',
																						'" . $bus_orden["nro_documento"] . "',
																						'" . $bus_orden["fecha_documento"] . "',
																						'" . $bus_orden["ordenado_por"] . "',
																						'" . $bus_orden["cedula_ordenado"] . "',
																						'" . $bus_orden["numero_proyecto"] . "',
																						'" . $bus_orden["numero_contrato"] . "',
																						'" . $bus_orden["exento"] . "',
																						'" . $bus_orden["sub_total"] . "',
																						'" . $bus_orden["impuesto"] . "',
																						'" . $bus_orden["total"] . "',
																						'" . $bus_orden["total_a_pagar"] . "',
																						'elaboracion',
																						'" . $bus_orden["idrazones_devolucion"] . "',
																						'" . $bus_orden["observaciones_devolucion"] . "',
																						'" . $bus_orden["numero_remision"] . "',
																						'" . $bus_orden["fecha_remision"] . "',
																						'" . $bus_orden["recibido_por"] . "',
																						'" . $bus_orden["cedula_recibido"] . "',
																						'" . $bus_orden["fecha_recibido"] . "',
																						'0',
																						'" . $bus_orden["status"] . "',
																						'" . $login . "',
																						'" . $fh . "',
																						'" . $bus_orden["duplicados"] . "',
																						'" . $bus_orden["forma_pago"] . "')") or die(mysql_error());

        $nueva_orden_pago = mysql_insert_id();

        //if($bus_orden["estado"] != "anulado"){
        $sql_partidas = mysql_query("select * from partidas_orden_pago where idorden_pago = '" . $id_orden_pago . "'");
        while ($bus_partidas = mysql_fetch_array($sql_partidas)) {

            $sql_insertar_partidas = mysql_query("insert into partidas_orden_pago(idorden_pago,
																					idmaestro_presupuesto,
																					monto,
																					estado,
																					status,
																					usuario,
																					fechayhora)values('" . $nueva_orden_pago . "',
																										'" . $bus_partidas["idmaestro_presupuesto"] . "',
																										'" . $bus_partidas["monto"] . "',
																										'" . $bus_partidas["estado"] . "',
																										'a',
																										'" . $login . "',
																										'" . $fh . "')");
        }
        //}
        //DUPLICO LAS CUENTAS CONTABLES
        $sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_orden["tipo"] . "'");
        $bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);

        if ($bus_cuentas_contables["tabla_debe"] != '' and $bus_cuentas_contables["idcuenta_debe"] != 0 and $bus_cuentas_contables["tabla_haber"] != '' and $bus_cuentas_contables["idcuenta_haber"] != '') {
            $sql_contable = mysql_query("insert into asiento_contable (idfuente_financiamiento,
																	detalle,
																	tipo_movimiento,
																	iddocumento,
																	estado,
																	status,
																	usuario,
																	fechayhora,
																	prioridad
																		)values(
																				'" . $bus_orden["idfuente_financiamiento"] . "',
																				'" . $bus_orden["justificacion"] . "',
																				'causado',
																				'" . $nueva_orden_pago . "',
																				'elaboracion',
																				'a',
																				'" . $login . "',
																				'" . date("Y-m-d H:i:s") . "',
																				'3')");

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
																				'" . $bus_orden["total_a_pagar"] . "')") or die(" error 1 " . mysql_error());
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
																				'" . $bus_orden["total_a_pagar"] . "')") or die(" error 2 " . mysql_error());

            }

        }

    } else {
        // SI NO ES NI SIN AFECTACION NI PAGO DIRECTO SINO QUE ES CUALQUIERA DE LOS TIPO CONTRA CUALQUIER COMPROMISO
        $sql_insert_orden = mysql_query("insert into orden_pago(tipo,
																		fecha_elaboracion,
																		proceso,
																		numero_documento,
																		idbeneficiarios,
																		idcategoria_programatica,
																		anio,
																		idfuente_financiamiento,
																		idtipo_presupuesto,
																		justificacion,
																		observaciones,
																		nro_documento,
																		fecha_documento,
																		ordenado_por,
																		cedula_ordenado,
																		numero_proyecto,
																		numero_contrato,
																		estado,
																		idrazones_devolucion,
																		observaciones_devolucion,
																		numero_remision,
																		fecha_remision,
																		recibido_por,
																		cedula_recibido,
																		fecha_recibido,
																		ubicacion,
																		status,
																		usuario,
																		fechayhora,
																		duplicados,
																		forma_pago)value(
																						'" . $bus_orden["tipo"] . "',
																						'" . $bus_orden["fecha_elaboracion"] . "',
																						'" . $bus_orden["proceso"] . "',
																						'" . $bus_orden["numero_documento"] . "',
																						'" . $bus_orden["idbeneficiarios"] . "',
																						'" . $bus_orden["idcategoria_programatica"] . "',
																						'" . $bus_orden["anio"] . "',
																						'" . $bus_orden["idfuente_financiamiento"] . "',
																						'" . $bus_orden["idtipo_presupuesto"] . "',
																						'" . $bus_orden["justificacion"] . "',
																						'" . $bus_orden["observaciones"] . "',
																						'" . $bus_orden["nro_documento"] . "',
																						'" . $bus_orden["fecha_documento"] . "',
																						'" . $bus_orden["ordenado_por"] . "',
																						'" . $bus_orden["cedula_ordenado"] . "',
																						'" . $bus_orden["numero_proyecto"] . "',
																						'" . $bus_orden["numero_contrato"] . "',
																						'elaboracion',
																						'" . $bus_orden["idrazones_devolucion"] . "',
																						'" . $bus_orden["observaciones_devolucion"] . "',
																						'" . $bus_orden["numero_remision"] . "',
																						'" . $bus_orden["fecha_remision"] . "',
																						'" . $bus_orden["recibido_por"] . "',
																						'" . $bus_orden["cedula_recibido"] . "',
																						'" . $bus_orden["fecha_recibido"] . "',
																						'0',
																						'" . $bus_orden["status"] . "',
																						'" . $login . "',
																						'" . $fh . "',
																						'" . $bus_orden["duplicados"] . "',
																						'" . $bus_orden["forma_pago"] . "')") or die(mysql_error());
        $nueva_orden_pago = mysql_insert_id();

        $sql_actualizar = mysql_query("update orden_pago set exento = 0,
															impuesto = 0,
															sub_total = 0,
															total = 0,
															total_retenido = 0,
															total_a_pagar = 0
															where idorden_pago = '" . $nueva_orden_pago . "'");

        //DUPLICO LAS CUENTAS CONTABLES
        $sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_orden["tipo"] . "'");
        $bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);

        if ($bus_cuentas_contables["tabla_debe"] != '' and $bus_cuentas_contables["idcuenta_debe"] != 0 and $bus_cuentas_contables["tabla_haber"] != '' and $bus_cuentas_contables["idcuenta_haber"] != '') {
            $sql_contable = mysql_query("insert into asiento_contable (idfuente_financiamiento,
																	detalle,
																	tipo_movimiento,
																	iddocumento,
																	estado,
																	status,
																	usuario,
																	fechayhora,
																	prioridad
																		)values(
																				'" . $bus_orden["idfuente_financiamiento"] . "',
																				'" . $bus_orden["justificacion"] . "',
																				'causado',
																				'" . $nueva_orden_pago . "',
																				'elaboracion',
																				'a',
																				'" . $login . "',
																				'" . date("Y-m-d H:i:s") . "',
																				'3')");

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
																				0)") or die(" error 1 " . mysql_error());
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
																				0)") or die(" error 2 " . mysql_error());

            }

        }
    }

    /*if($bus_orden["estado"] == "anulado"){

    $sql_relacion_pagos = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '".$id_orden_pago."'");
    while($bus_relacion_pagos = mysql_fetch_array($sql_relacion_pagos)){
    $sql_insertar = mysql_query("insert into relacion_pago_compromisos(idorden_pago,
    idorden_compra_servicio)values(
    '".$nueva_orden_pago."',
    '".$bus_relacion_pagos["idorden_compra_servicio"]."')");
    }

    $sql_relacion_retencion = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago = '".$id_orden_pago."'");

    while($bus_relacion_retencion = mysql_fetch_array($sql_relacion_retencion)){
    $sql_insertar = mysql_query("insert into relacion_orden_pago_retencion(idretencion,
    idorden_pago,
    status,
    fechayhora,
    usuario)values(
    '".$bus_relacion_retencion["idretencion"]."',
    '".$nueva_orden_pago."',
    'a',
    '".$fh."',
    '".$login."')");
    }

    $sql_relacion_partidas = mysql_query("select * from partidas_orden_pago where idorden_pago = '".$id_orden_pago."'");
    while($bus_relacion_partidas = mysql_fetch_array($sql_relacion_partidas)){
    $sql_insertar = mysql_query("insert into partidas_orden_pago(idorden_pago,
    idmaestro_presupuesto,
    monto,
    estado,
    status,
    usuario,
    fechayhora)values(
    '".$nueva_orden_pago."',
    '".$bus_relacion_partidas["idmaestro_presupuesto"]."',
    '".$bus_relacion_partidas["monto"]."',
    '".$bus_relacion_partidas["estado"]."',
    'a',
    '".$fh."',
    '".$login."')");
    }

    }*/

    /*
    $sql_relacion_orde_pago_retencion = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago = '".$nueva_orden_pago."'");

    if (mysql_num_rows($sql_relacion_orde_pago_retencion) > 0){
    while ($bus_relacion_retenciones = mysql_fetch_array($sql_relacion_orde_pago_retencion)){
    //VALIDO SI TIENE UNA RETENCION RELACIONADA PARA BUSCAR LAS CUENTAS CONTABLES DEL TIPO DE RETENCION
    //ACTUALIZAR CUENTAS CONTABLES
    $sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_pago."
    and tipo_movimiento = 'causado'");
    $bus_asiento_contable = mysql_fetch_array($sql_validar_asiento);

    //VALIDO SI TIENE RETENCIONES PARA DIFERENCIAR LA CUENTA CONTABLE

    $sql_retencion = mysql_query("select * from retenciones where idretenciones = '".$bus_relacion_retenciones["idretencion"]."'");
    $bus_retencion = mysql_fetch_array($sql_retencion);
    $num_retencion = mysql_num_rows($sql_retencion);
    if($num_retencion > 0){
    //OBTENGO LA RELACION DE RETENCIONES PARA SABER DE EL TIPO DE RETENCION Y LAS CUENTAS QUE AFECTA
    $sql_relacion_retenciones = mysql_query("select * from relacion_retenciones where idretenciones = ".$bus_retencion["idretenciones"]."");
    while ($bus_relacion_retenciones = mysql_fetch_array($sql_relacion_retenciones)){
    $sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = ".$bus_relacion_retenciones["idtipo_retencion"]."");
    $bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
    //valido la cuenta que afecta la retencion por el debe,
    //si es la misma que la que ya existe no la afecto,
    //si no es la misma la busco en el haber para ver si existe y restar el monto retenido
    $sql_cuentas_contables_debe = mysql_query("select * from cuentas_asiento_contable
    where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'
    and idcuenta = '".$bus_tipo_retencion["idcuenta_debe"]."'
    and tabla = '".$bus_tipo_retencion["tabla_debe"]."'
    and afecta = 'debe'");
    $num_cuenta_retencion_debe = mysql_num_rows($sql_cuentas_contables_debe);

    if ($num_cuenta_retencion_debe == 0){
    //entro aqui porque la cuenta no existe afectando el debe por lo que no la voy a afectar por el debe
    //la busco en el haber para restarla
    $sql_cuentas_contables_haber = mysql_query("select * from cuentas_asiento_contable
    where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'
    and idcuenta = '".$bus_tipo_retencion["idcuenta_debe"]."'
    and tabla = '".$bus_tipo_retencion["tabla_debe"]."'
    and afecta = 'haber'");
    $num_cuenta_retencion_haber = mysql_num_rows($sql_cuentas_contables_haber);
    if ($num_cuenta_retencion_haber > 0){
    //valido que la cuenta a insertar no este registrada
    $sql_cuentas_contables_haber_nueva = mysql_query("select * from cuentas_asiento_contable
    where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'
    and idcuenta = '".$bus_tipo_retencion["idcuenta_haber"]."'
    and tabla = '".$bus_tipo_retencion["tabla_haber"]."'
    and afecta = 'haber'");
    $bus_cuenta_retencion_haber_nueva = mysql_fetch_array($sql_cuentas_contables_haber_nueva);
    $num_cuenta_retencion_haber_nueva = mysql_num_rows($sql_cuentas_contables_haber_nueva);
    if ($num_cuenta_retencion_haber_nueva == 0){
    //si la cuenta no existe la insertamos la cuenta
    $ingreso_cuenta_haber_retencion = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
    tabla,
    idcuenta,
    monto,
    afecta)values(
    '".$bus_asiento_contable["idasiento_contable"]."',
    '".$bus_tipo_retencion["tabla_haber"]."',
    '".$bus_tipo_retencion["idcuenta_haber"]."',
    '".$bus_relacion_retenciones["monto_retenido"]."',
    'haber'
    )");
    }else{
    //si la cuenta existe le sumo el monto retenido
    $actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto + '".$bus_relacion_retenciones["monto_retenido"]."'
    where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'
    and idcuenta = '".$bus_tipo_retencion["idcuenta_haber"]."'
    and tabla = '".$bus_tipo_retencion["tabla_haber"]."'
    and afecta = 'haber'")or die("actualizando monto contable ".mysql_error());
    }
    }
    }
    }
    //actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
    $sql_suma_retenido = mysql_query("select sum(monto) as monto_retenido from cuentas_asiento_contable
    where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'
    and idcuenta <> '".$bus_tipo_retencion["idcuenta_debe"]."'
    and tabla <> '".$bus_tipo_retencion["tabla_debe"]."'
    and afecta = 'haber'");
    $monto_retenido = mysql_fetch_array($sql_suma_retenido);
    //actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
    //echo 'RETENIDO '.$monto_retenido["monto_retenido"];
    $actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto - '".$monto_retenido["monto_retenido"]."'
    where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'
    and idcuenta = '".$bus_tipo_retencion["idcuenta_debe"]."'
    and tabla = '".$bus_tipo_retencion["tabla_debe"]."'
    and afecta = 'haber'");
    }
    }
    }*/

    echo $nueva_orden_pago;
    registra_transaccion("Duplicar Orden Pago (Id Antiguo: " . $id_orden_pago . ", Nuevo ID: " . $nueva_orden_pago . ")", $login, $fh, $pc, 'orden_pago');
}

if ($ejecutar == "ingresarPartidaIndividual") {
    $sql = mysql_query("select * from partidas_orden_pago where idmaestro_presupuesto = " . $id_partida . " and idorden_pago = " . $id_orden_pago . "");
    $num = mysql_num_rows($sql);
    // SI LA PARTIDA NO EXISTE ENTRE LAS PARTIDAS CARGADAS
    if ($num == 0) {

        $disponible = consultarDisponibilidad($id_partida);

        if ($disponible < $monto) {
            $estado = "sobregiro";
        } else {
            $estado = "disponible";
        }

        $sql = mysql_query("insert into partidas_orden_pago (idorden_pago,
																	idmaestro_presupuesto,
																	monto,
																	estado,
																	status,
																	usuario,
																	fechayhora
																		)values(
																			'" . $id_orden_pago . "',
																			'" . $id_partida . "',
																			'" . $monto . "',
																			'" . $estado . "',
																			'a',
																			'" . $login . "',
																			'" . date("Y-m-d H:i:s") . "'
																			)") or die(mysql_error());
        $sql_consulta = mysql_query("select * from orden_pago where idorden_pago = '" . $id_orden_pago . "'");
        $bus_consulta = mysql_fetch_array($sql_consulta);

        $sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_consulta["tipo"] . "'");
        $bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);

        if ($bus_tipo_documento["multi_categoria"] == "si") {

            $sql_actualizar = mysql_query("update orden_pago set
										  					sub_total = sub_total+'" . $monto . "',
															total = total+'" . $monto . "',
															total_a_pagar = total_a_pagar+'" . $monto . "'
															where idorden_pago = " . $id_orden_pago . "") or die(mysql_error());
        } else {
            $sql_actualizar = mysql_query("update orden_pago set total = total+'" . $monto . "',
															total_a_pagar = total_a_pagar+'" . $monto . "'
															where idorden_pago = " . $id_orden_pago . "") or die(mysql_error());
        }

        //ACTUALIZO LAS CUENTAS CONTABLES
        $sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = " . $id_orden_pago . "
														and tipo_movimiento = 'causado'");
        if (mysql_num_rows($sql_validar_asiento) > 0) {

            $bus_asiento_contable = mysql_fetch_array($sql_validar_asiento);

            $sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
														order by afecta");

            while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)) {
                if ($bus_cuentas_contables["afecta"] == 'debe') {
                    $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = monto + '" . $monto . "'
																where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																and idcuenta = '" . $bus_tipo_documento["idcuenta_debe"] . "'
																and tabla = '" . $bus_tipo_documento["tabla_debe"] . "'");
                } else {
                    $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = monto + '" . $monto . "'
																where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																and idcuenta = '" . $bus_tipo_documento["idcuenta_haber"] . "'
																and tabla = '" . $bus_tipo_documento["tabla_haber"] . "'");
                }
            }
        }

        echo "exito";
        registra_transaccion("Ingresar Partidas Individuales (ID Orden: " . $id_orden_pago . ", ID PARTIDA: " . $id_partida . ")", $login, $fh, $pc, 'orden_pago');

    } else {
        registra_transaccion("Error al ingresar Partidas individuales en la orden de pago (La partida Existe)", $login, $fh, $pc, 'orden_pago');
        echo "existe";
    }
}

if ($ejecutar == "anularOrden") {
    $sql = mysql_query("select * from usuarios where login = '" . $login . "' and clave = '" . md5($clave) . "'");
    $num = mysql_num_rows($sql);
    if ($num > 0) {

        $sql_configuracion = mysql_query("select fecha_cierre from configuracion");
        $bus_configuracion = mysql_fetch_array($sql_configuracion);

        if (date("Y-m-d") > $bus_configuracion["fecha_cierre"]) {
            $fecha_anulacion = $bus_configuracion["fecha_cierre"];
        } else {
            $fecha_anulacion = $fecha_anulacion_op;
        }

        $sql_orden = mysql_query("update orden_pago set estado = 'anulado',
													fecha_anulacion = '" . $fecha_anulacion . "'
													where idorden_pago = " . $id_orden_pago . "") or die(mysql_error());

        $sql_op    = mysql_query("select * from orden_pago where idorden_pago = " . $id_orden_pago . "");
        $bus_op    = mysql_fetch_array($sql_op);
        $sql_tipod = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_op["tipo"] . "'");
        $bus_tipod = mysql_fetch_array($sql_tipod);
        //$sql_actualizar_partidas = mysql_query("select * from partidas_orde_pago where idorden_pago = ".$id_orden_pago."")or die(mysql_error());
        $sql_partidas = mysql_query("select * from partidas_orden_pago where idorden_pago = " . $id_orden_pago . "");
        while ($bus_partidas = mysql_fetch_array($sql_partidas)) {
            if ($bus_tipod["compromete"] == 'si') {
                $sql_update_maestro = mysql_query("update maestro_presupuesto set total_compromisos= total_compromisos-" . $bus_partidas["monto"] . ",
																					total_causados= total_causados-" . $bus_partidas["monto"] . "
																					where idRegistro = " . $bus_partidas["idmaestro_presupuesto"] . "") or die(mysql_error());
            } else {
                $sql_update_maestro = mysql_query("update maestro_presupuesto set
																					total_causados= total_causados-" . $bus_partidas["monto"] . "
																					where idRegistro = " . $bus_partidas["idmaestro_presupuesto"] . "") or die(mysql_error());
            }
            $sql_consulta_ordinal = mysql_query("select * from ordinal where codigo = '0000'");
            $bus_consulta_ordinal = mysql_fetch_array($sql_consulta_ordinal);

            $sql_consultar_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '" . $bus_partidas["idmaestro_presupuesto"] . "'") or die(" aqui1 " . mysql_error());
            if ($sql_consulta_maestro) {
                $num_consulta_maestro = mysql_num_rows($sql_consulta_maestro);
            }
            if ($num_consulta_maestro != 0) {
                $bus_consultar_maestro = mysql_fetch_array($sql_consultar_maestro);
                $sql_sub_espe          = mysql_query("select * from maestro_presupuesto where
							idcategoria_programatica= '" . $bus_consultar_maestro["idcategoria_programatica"] . "'
						and idtipo_presupuesto = '" . $bus_consultar_maestro["idtipo_presupuesto"] . "'
						and idfuente_financiamiento = '" . $bus_consultar_maestro["idfuente_financiamiento"] . "'
						and idclasificador_presupuestario = '" . $bus_consultar_maestro["idclasificador_presupuestario"] . "'
						and idordinal = '" . $bus_consulta_ordinal["idordinal"] . "'");
                $num_sub_espe = mysql_num_rows($sql_sub_espe);
                if ($num_sub_espe != 0) {
                    $bus_sub_epe = mysql_fetch_array($sql_sub_espe);
                    if ($bus_tipod["compromete"] == 'si') {
                        $sql_maestro = mysql_query("update maestro_presupuesto set
															total_compromisos= total_compromisos-" . $bus_partidas["monto"] . ",
															total_causados= total_causados-" . $bus_partidas["monto"] . "
															where idRegistro = " . $bus_sub_epe["idmaestro_presupuesto"] . "") or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO: " . mysql_error());
                    } else {
                        $sql_maestro = mysql_query("update maestro_presupuesto set
															total_causados= total_causados-" . $bus_partidas["monto"] . "
															where idRegistro = " . $bus_sub_epe["idmaestro_presupuesto"] . "") or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO: " . mysql_error());
                    }

                }

                $sql_clasificador = mysql_query("select * clasificador_presupuestario where idclasificador_presupuestario = '" . $bus_consultar_maestro["idclasificador_presupuestario"] . "' and sub_especifica != '00'");
                $num_clasificador = mysql_num_rows($sql_clasificador);
                if ($num_clasificador > 0) {
                    $bus_clasificador          = mysql_fetch_array($sql_clasificador);
                    $sql_consulta_clasificador = mysql_query("select * from clasificador_presupuestario where partida = '" . $bus_clasificador["partida"] . "'
							and generica = '" . $bus_clasificador["generica"] . "'
							and especifica ='" . $bus_clasificador["especifica"] . "'
							and sub_especifica= '00'");
                    $bus_consulta_clasificador = mysql_fetch_array($sql_consulta_clasificador);
                    $sql_id_maestro            = mysql_query("select * from maestro_presupuesto where
							idcategoria_programatica= '" . $bus_consultar_maestro["idcategoria_programatica"] . "'
						and idtipo_presupuesto = '" . $bus_consultar_maestro["idtipo_presupuesto"] . "'
						and idfuente_financiamiento = '" . $bus_consultar_maestro["idfuente_financiamiento"] . "'
						and idclasificador_presupuestario = '" . $bus_consulta_clasificador["idclasificador_presupuestario"] . "'
						and idordinal = '" . $bus_consulta_ordinal["idordinal"] . "'");
                    $bus_id_maestro = mysql_fetch_array($sql_id_maestro);
                    if ($bus_tipod["compromete"] == 'si') {
                        $sql_maestro = mysql_query("update maestro_presupuesto set
															total_compromisos= total_compromisos-" . $bus_partidas["monto"] . ",
															total_causados= total_causados-" . $bus_partidas["monto"] . "
															where idRegistro = " . $bus_id_maestro["idmaestro_presupuesto"] . "") or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO: " . mysql_error());
                    } else {
                        $sql_maestro = mysql_query("update maestro_presupuesto set
															total_causados= total_causados-" . $bus_partidas["monto"] . "
															where idRegistro = " . $bus_id_maestro["idmaestro_presupuesto"] . "") or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO: " . mysql_error());
                    }

                }

            }
        }

        //ANULACION DE ASIENTO CONTABLE
        $sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = " . $id_orden_pago . "
															and tipo_movimiento = 'causado'");
        if (mysql_num_rows($sql_validar_asiento) > 0) {
            $sql_actualizar = mysql_query("update asiento_contable set reversado = 'si'
														where iddocumento = " . $id_orden_pago . "
															and tipo_movimiento = 'causado'");
            $sql_asiento_contable = mysql_query("select * from asiento_contable where iddocumento = " . $id_orden_pago . " and tipo_movimiento='causado'") or die("aqui asiento " . mysql_error());
            $bus_asiento_contable = mysql_fetch_array($sql_asiento_contable) or die("aqui asiento " . mysql_error());
            $sql_contable         = mysql_query("insert into asiento_contable (idfuente_financiamiento,
																	detalle,
																	fecha_contable,
																	tipo_movimiento,
																	iddocumento,
																	estado,
																	status,
																	usuario,
																	fechayhora,
																	prioridad
																		)values(
																				'" . $bus_asiento_contable["idfuente_financiamiento"] . "',
																				'" . 'ANULACION DE ASIENTO: ' . $bus_asiento_contable["detalle"] . "',
																				'" . date("Y-m-d") . "',
																				'causado',
																				'" . $id_orden_pago . "',
																				'anulado',
																				'a',
																				'" . $login . "',
																				'" . date("Y-m-d H:i:s") . "',
																				'3')") or die("aqui insert " . mysql_error());

            if ($sql_contable) {
                $idasiento_contable    = mysql_insert_id();
                $sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'");

                while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)) {
                    if ($bus_cuentas_contables["afecta"] == 'debe') {$afecta = 'haber';} else { $afecta = 'debe';}
                    $sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'" . $idasiento_contable . "',
																				'" . $bus_cuentas_contables["tabla"] . "',
																				'" . $bus_cuentas_contables["idcuenta"] . "',
																				'" . $afecta . "',
																				'" . $bus_cuentas_contables["monto"] . "')");
                }

            }
        }

        if ($sql_orden) {
            echo "exito";
            $sql_forma_pago = mysql_query("select * from orden_pago where idorden_pago = '" . $id_orden_pago . "'");
            $bus_forma_pago = mysql_fetch_array($sql_forma_pago);
            if ($bus_forma_pago["forma_pago"] == 'parcial' || $bus_forma_pago["forma_pago"] == 'valuacion') {
                $estado = 'parcial';
            } else {
                $estado = 'procesado';
            }
            $sql_buscar_relacion = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '" . $id_orden_pago . "'");
            while ($bus_buscar_relacion = mysql_fetch_array($sql_buscar_relacion)) {
                $sql_actualizar_estado = mysql_query("update orden_compra_servicio
																set
															estado = '" . $estado . "',
															ubicacion = 'recibido'
																where
															idorden_compra_servicio = '" . $bus_buscar_relacion["idorden_compra_servicio"] . "'");
            }
            $sql_buscar_relacion      = mysql_query("delete from relacion_pago_compromisos where idorden_pago = '" . $id_orden_pago . "'");
            $sql_buscar_relacion_pago = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago = '" . $id_orden_pago . "'");
            while ($bus_buscar_relacion = mysql_fetch_array($sql_buscar_relacion_pago)) {
                $bus_actualizar_retenciones = mysql_query("update retenciones set estado = 'procesado' where idretenciones = '" . $bus_buscar_relacion["idretencion"] . "'");
            }
            $sql_borrar_retencion_asociada = mysql_query("delete from relacion_orden_pago_retencion where idorden_pago = '" . $id_orden_pago . "'");
            registra_transaccion("Anular orden de Pago (" . $id_orden_pago . ")", $login, $fh, $pc, 'orden_pago');
        } else {
            echo "fallo";
            registra_transaccion("Anular orden Pago ERROR (" . $id_orden_pago . ")", $login, $fh, $pc, 'orden_pago');
        }
    } else {
        registra_transaccion("Error al Anular Orden de Pago (Clave Incorrecta: " . $clave . ", Orden: " . $id_orden_pago . ")", $login, $fh, $pc, 'orden_pago');
        echo "claveIncorrecta";
    }
}

/****************************************************************************************************************************************************
 ******************************************************** INGRESAR ORDEN CREADA ********************************************************************
 *****************************************************************************************************************************************************/

if ($ejecutar == "ingresarOrdenCreada") {

    $sql_numero_filas = mysql_query("select * from configuracion_tributos");
    $bus_numero_filas = mysql_fetch_array($sql_numero_filas);

    $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = " . $id_orden_pago . "");
    $bus_orden_pago = mysql_fetch_array($sql_orden_pago);

    $sql_tipo_documento                 = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_orden_pago["tipo"] . "'");
    $bus_tipo_documento                 = mysql_fetch_array($sql_tipo_documento);
    $sql_borrar_partidas_desseleccionar = mysql_query("select * from relacion_pago_compromisos
															  			where idorden_compra_servicio = '" . $id_orden_compra . "'");
    $orden_pago = mysql_fetch_array($sql_borrar_partidas_desseleccionar);

    //VALIDO QUE NO SEA PARCIAL O VALUACION PORQUE ESO DETERMINA SI SE PUEDEN SELECCIONAR UNO O VARIOS COMPROMISOS
    if ($bus_orden_pago["forma_pago"] != "parcial" && $bus_orden_pago["forma_pago"] != "valuacion") {
        $sql_retencion = mysql_query("select * from retenciones where iddocumento = '" . $id_orden_compra . "'");
        $bus_retencion = mysql_fetch_array($sql_retencion);
        $num_retencion = mysql_num_rows($sql_retencion);
        //VALIDO SI NO TIENE RETENCION EL COMPROMISO SELECCIONADO
        if ($num_retencion != 0) {
            $sql_validar_relacion = mysql_query("select * from relacion_orden_pago_retencion
																				where idorden_pago ='" . $id_orden_pago . "'
																				and idretencion = '" . $bus_retencion["idretenciones"] . "'");
            $num_validar_relacion = mysql_num_rows($sql_validar_relacion);
            //VALIDO QUE LA RETENCION NO ESTE ASOCIADA A ESTA ORDEN DE PAGO
            if ($num_validar_relacion == 0) {
                echo "ENTRO AQUI";
                //ELIMINO LA RETENCION DE OTRA CUALQUIER OTRA ORDEN QUE LA TENGA ASOCIADA
                $sql_eliminar_relacion_retencion = mysql_query("delete from relacion_orden_pago_retencion
																		where idretencion = '" . $bus_retencion["idretenciones"] . "'");
                //ACTUALIZO LA ORDEN DE PAGO QUE TENIA ESA RETENCION ASOCIADA PARA RESTARSELA
                $actualiza_retenido = mysql_query("update orden_pago set total_retenido = total_retenido-'" . $bus_retencion["total_retenido"] . "'
																					 where idorden_pago = '" . $orden_pago["idorden_pago"] . "'") or die(mysql_error());
                //REGISTRO LA RETENCION EN LA NUEVA ORDEN DE PAGO
                $sql_relacion_orden_pago_retencion = mysql_query("insert into relacion_orden_pago_retencion (idretencion,
																							idorden_pago,
																							status,
																							fechayhora,
																							usuario)values(
																								'" . $bus_retencion["idretenciones"] . "',
																								'" . $id_orden_pago . "',
																								'a',
																								'" . $fh . "',
																								'" . $login . "')");

            } else {
                //SI LA RETENCION YA ESTABA REGISTRADA EN LA ORDEN DE PAGO ACTUAL ENTRO A ELIMINARLA PORQUE SE ESTA DESASOCIANDO EL COMPROMISO DE LA ORDEN DE PAGO
                $actualiza_retenido = mysql_query("update orden_pago set total_retenido = total_retenido-'" . $bus_retencion["total_retenido"] . "'
																					 where idorden_pago = '" . $id_orden_pago . "'");
                $sql_eliminar_relacion_retencion = mysql_query("delete from relacion_orden_pago_retencion
																		where idretencion = '" . $bus_retencion["idretenciones"] . "'
																		and idorden_pago ='" . $id_orden_pago . "'");
            }
        }
    }

    $sql_validar_relacion = mysql_query("select * from relacion_pago_compromisos where idorden_pago ='" . $id_orden_pago . "'
																				and idorden_compra_servicio = '" . $id_orden_compra . "'");
    $num_validar_relacion = mysql_num_rows($sql_validar_relacion);
    //VALIDO QUE EL COMPRIMOS NO ESTE RELACIONADO CON LA ORDEN DE PAGO
    if ($num_validar_relacion == 0) {

        $sql_validar_numero_filas = mysql_query("select * from relacion_pago_compromisos where idorden_pago ='" . $id_orden_pago . "'");
        $num_validar_numero_filas = mysql_num_rows($sql_validar_numero_filas);

        if ($num_validar_numero_filas < $bus_numero_filas["nro_linea_comprobante"]) {
            //valido que el numero de compromisos seleccionado sea menor a la
            // cantidad de filas disponibles en el comprobante de retencion
            $existe = mysql_num_rows($sql_borrar_partidas_desseleccionar);
            //VALIDO QUE EL COMPROMISO ESTE ASOCIADO A OTRA ORDEN DE PAGO PARA BORRARLA DE ESA ORDEN DE COMPRA
            if ($existe > 0) {

                if ($bus_orden_pago["forma_pago"] != "parcial" && $bus_orden_pago["forma_pago"] != "valuacion") {

                    $sql_partidas_orden_compra = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = " . $id_orden_compra . "");
                    while ($bus_partidas_orden_compra = mysql_fetch_array($sql_partidas_orden_compra)) {

                        $id_maestro_presupuesto = $bus_partidas_orden_compra["idmaestro_presupuesto"];

                        $sql_seleccionar_partida = mysql_query("select * from partidas_orden_pago where idorden_pago = '" . $orden_pago["idorden_pago"] . "'
																								and idmaestro_presupuesto = '" . $id_maestro_presupuesto . "'");
                        $bus_seleccionar_partida = mysql_fetch_array($sql_seleccionar_partida);
                        $monto                   = $bus_seleccionar_partida["monto"] - $bus_partidas_orden_compra["monto"];
                        //SI LA PARTIDA QUEDA EN CERO LA ELIMINO SINO LE RESTO EL MONTO DE LA PARTIDA DEL COMPROMISO
                        if ($monto == 0) {
                            $sql_eliminar_partida = mysql_query("delete from partidas_orden_pago where idorden_pago = '" . $orden_pago["idorden_pago"] . "'
																								and idmaestro_presupuesto = '" . $id_maestro_presupuesto . "'");

                        } else {
                            $sql_eliminar_partida = mysql_query("update partidas_orden_pago set monto = monto-" . $bus_partidas_orden_compra["monto"] . "
																								where idorden_pago = '" . $orden_pago["idorden_pago"] . "'
																								and idmaestro_presupuesto = '" . $id_maestro_presupuesto . "'");
                        }
                    }
                    //ELIMINO EL COMPROMISO DE LA OTRA ORDEN DE PAGO
                    $sql_limpio_compromiso = mysql_query("DELETE FROM relacion_pago_compromisos
													 WHERE idorden_compra_servicio = '" . $id_orden_compra . "'
															and idorden_pago = '" . $orden_pago["idorden_pago"] . "'") or die(mysql_error());

                    $sql_select_orden_compra = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = " . $id_orden_compra . "");
                    $bus_select_orden_compra = mysql_fetch_array($sql_select_orden_compra);
                    //ACTUALIZO LOS TOTALES DE LA ORDEN DE PAGO QUE LO TENIA SELECCIONADO A EL COMPROMISO
                    $sql_update_orden_pago = mysql_query("update orden_pago set exento = exento-'" . $bus_select_orden_compra["exento"] . "',
																						sub_total = sub_total-'" . $bus_select_orden_compra["sub_total"] . "',
																						impuesto = impuesto-'" . $bus_select_orden_compra["impuesto"] . "'
																						 where idorden_pago = '" . $orden_pago["idorden_pago"] . "'");
                    $sql_total = mysql_query("select * from orden_pago where idorden_pago = '" . $orden_pago["idorden_pago"] . "'");
                    $bus_total = mysql_fetch_array($sql_total);
                    if ($bus_tipo_documento["multi_categoria"] == "no") {
                        $total = $bus_total["exento"] + $bus_total["sub_total"] + $bus_total["impuesto"];
                    } else {
                        $sub_total = $bus_total["sub_total"];
                        $total     = $bus_total["sub_total"] - $bus_total["exento"];
                    }
                    //echo $total;
                    //echo "update orden_pago set total = '".$total."' where idorden_pago = ".$id_orden_pago."";
                    $sql_update_orden_pago = mysql_query("update orden_pago set total = '" . $total . "',
																					total_a_pagar = '" . $total . "'-total_retenido
																					 where idorden_pago = '" . $orden_pago["idorden_pago"] . "'") or die(mysql_error());
                } else {
                    //echo " entro ";
                    $sql_borrar_partidas = mysql_query("select * from relacion_pago_compromisos
															  			where idorden_compra_servicio = '" . $id_orden_compra . "'");
                    while ($orden_pago = mysql_fetch_array($sql_borrar_partidas)) {
                        //echo $orden_pago["idorden_pago"];
                        $validar_estado_orden     = mysql_query("select * from orden_pago where idorden_pago = '" . $orden_pago["idorden_pago"] . "'");
                        $bus_validar_estado_orden = mysql_fetch_array($validar_estado_orden);
                        //echo " estado ".$bus_validar_estado_orden["estado"];
                        if ($bus_validar_estado_orden["estado"] == 'elaboracion') {
                            $sql_partidas_orden_compra = mysql_query("select * from partidas_orden_compra_servicio
																				where idorden_compra_servicio = " . $id_orden_compra . "");
                            while ($bus_partidas_orden_compra = mysql_fetch_array($sql_partidas_orden_compra)) {

                                $id_maestro_presupuesto = $bus_partidas_orden_compra["idmaestro_presupuesto"];

                                $sql_eliminar_partida = mysql_query("delete from partidas_orden_pago where idorden_pago = '" . $orden_pago["idorden_pago"] . "'
																										and idmaestro_presupuesto = '" . $id_maestro_presupuesto . "'");

                            }
                            //ELIMINO EL COMPROMISO DE LA OTRA ORDEN DE PAGO
                            $sql_limpio_compromiso = mysql_query("DELETE FROM relacion_pago_compromisos
															 WHERE idorden_compra_servicio = '" . $id_orden_compra . "'
																	and idorden_pago = '" . $orden_pago["idorden_pago"] . "'") or die(mysql_error());

                            $sql_select_orden_compra = mysql_query("select * from orden_compra_servicio
																					where idorden_compra_servicio = " . $id_orden_compra . "");
                            $bus_select_orden_compra = mysql_fetch_array($sql_select_orden_compra);
                            //ACTUALIZO LOS TOTALES DE LA ORDEN DE PAGO QUE LO TENIA SELECCIONADO A EL COMPROMISO
                            $sql_update_orden_pago = mysql_query("update orden_pago set exento = exento-'" . $bus_select_orden_compra["exento"] . "',
																								sub_total = sub_total-'" . $bus_select_orden_compra["sub_total"] . "',
																								impuesto = impuesto-'" . $bus_select_orden_compra["impuesto"] . "'
																								 where idorden_pago = '" . $orden_pago["idorden_pago"] . "'");
                            $sql_total = mysql_query("select * from orden_pago where idorden_pago = '" . $orden_pago["idorden_pago"] . "'");
                            $bus_total = mysql_fetch_array($sql_total);
                            if ($bus_tipo_documento["multi_categoria"] == "no") {
                                $total = $bus_total["exento"] + $bus_total["sub_total"] + $bus_total["impuesto"];
                            } else {
                                $sub_total = $bus_total["sub_total"];
                                $total     = $bus_total["sub_total"] - $bus_total["exento"];
                            }
                            //echo $total;
                            //echo "update orden_pago set total = '".$total."' where idorden_pago = ".$id_orden_pago."";
                            $sql_update_orden_pago = mysql_query("update orden_pago set total = '" . $total . "',
																							total_a_pagar = '" . $total . "'-total_retenido
																							 where idorden_pago = '" . $orden_pago["idorden_pago"] . "'") or die(mysql_error());

                        }
                    }
                }
            }

            //INSERTO EL COMPROMISO Y LO RELACIONO CON  LA ORDEN DE PAGO
            $sql_relacion_compromiso = mysql_query("insert into relacion_pago_compromisos (idorden_pago,
																							idorden_compra_servicio)values(
																							'" . $id_orden_pago . "',
																							'" . $id_orden_compra . "')");

            // VALIDO QUE LA FORMA DE PAGO NO SEA UN ANTICIPO PARA COPIAR LAS PARTIDAS DEL COMPROMISO EN LA ORDEN DE PAGO
            if ($anticipo != "si") {

                $sql_partidas_orden_compra = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = " . $id_orden_compra . "");
                while ($bus_partidas_orden_compra = mysql_fetch_array($sql_partidas_orden_compra)) {
                    $id_maestro_presupuesto = $bus_partidas_orden_compra["idmaestro_presupuesto"];

                    //echo "id maestro ".$id_maestro_presupuesto;
                    $sql = mysql_query("select * from partidas_orden_pago where
																			idmaestro_presupuesto = " . $id_maestro_presupuesto . "
																			and idorden_pago = " . $id_orden_pago . "");
                    $num = mysql_num_rows($sql);
                    // SI LA PARTIDA NO EXISTE ENTRE LAS PARTIDAS DE LA ORDEN DE PAGO
                    if ($num == 0) {

                        $sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = " . $id_maestro_presupuesto . "");
                        $bus_maestro = mysql_fetch_array($sql_maestro);

                        //*********************************************** AQUI SE CAMBIA EL ESTADO ************************
                        $sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_orden_pago["tipo"] . "'");
                        $bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);
                        if ($bus_tipo_documento["compromete"] == "si") {

                            $disponible_actual = $bus_maestro["monto_actual"];
                            $disponible_resta  = $bus_maestro["total_compromisos"] + $bus_maestro["pre_compromiso"] + $bus_maestro["reservado_disminuir"];
                            $disponible        = bcsub($disponible_actual, $disponible_resta, 2);

                            if ($disponible < $monto) {
                                $estado = "sobregiro";
                            } else {
                                $estado = "disponible";
                            }
                        } else {
                            $estado = 'disponible';
                        }
                        //*********************************************** AQUI SE CAMBIA EL ESTADO ************************

                        //VALIDO Y SUMO SI EXISTE LA PARTIDA EN LOS COMPROMISOS SELECCIONADOS PARA SUMAR EL MONTO
                        if ($bus_orden_pago["forma_pago"] == "parcial" or $bus_orden_pago["forma_pago"] == "valuacion") {
                            $sql_relacion_pago = mysql_query("select * from relacion_pago_compromisos
																			where idorden_compra_servicio = '" . $id_orden_compra . "'");
                            while ($bus_relacion_pago = mysql_fetch_array($sql_relacion_pago)) {
                                //echo " id orden pago ".$bus_relacion_pago["idorden_pago"];
                                $sql_estado_orden = mysql_query("select * from orden_pago where idorden_pago = '" . $bus_relacion_pago["idorden_pago"] . "'");
                                $bus_estado_orden = mysql_fetch_array($sql_estado_orden);
                                if ($bus_estado_orden["estado"] != 'elaboracion' or $bus_estado_orden["estado"] != 'anulado') {
                                    $sql_partidas_orden_pago = mysql_query("select * from partidas_orden_pago
												where idorden_pago = '" . $bus_relacion_pago["idorden_pago"] . "' and idmaestro_presupuesto = '" . $id_maestro_presupuesto . "'");
                                    $bus_partidas_orden_pago = mysql_fetch_array($sql_partidas_orden_pago);
                                    $suma_monto += $bus_partidas_orden_pago["monto"];
                                    //echo " monto op ".$bus_partidas_orden_pago["monto"];
                                }
                            }

                            $monto = $bus_partidas_orden_compra["monto"] - $suma_monto;
                            //echo " monto restante ".$monto;
                            //echo " restado ".$suma_monto;
                            $suma_monto = 0;

                        } else {
                            $monto = $bus_partidas_orden_compra["monto"];

                        }

                        //INSERTO LA PARTIDA QUE NO EXISTE EN LA ORDEN DE PAGO
                        $sql = mysql_query("insert into partidas_orden_pago (idorden_pago,
																				idmaestro_presupuesto,
																				monto,
																				estado,
																				status,
																				usuario,
																				fechayhora
																					)values(
																						'" . $id_orden_pago . "',
																						'" . $id_maestro_presupuesto . "',
																						'" . $monto . "',
																						'" . $estado . "',
																						'a',
																						'" . $login . "',
																						'" . date("Y-m-d H:i:s") . "'
																						)") or die(mysql_error());

                        registra_transaccion("Ingresar Partidas Individuales (ID Orden: " . $id_orden_pago . ", ID PARTIDA: " . $id_maestro_presupuesto . ")", $login, $fh, $pc, 'orden_pago');

                    } else {
                        // ACTUALIZO LA PARTIDA SI YA EXISTE DENTRO DE LA ORDEN DE PAGO
                        $sql = mysql_query("update partidas_orden_pago set monto = monto+" . $bus_partidas_orden_compra["monto"] . " where
																						idmaestro_presupuesto = " . $id_maestro_presupuesto . "
																						and idorden_pago = " . $id_orden_pago . "") or die(mysql_error());

                        registra_transaccion("Ingresar Partidas desde orden de compra (ID Orden: " . $id_orden_pago . ", ID PARTIDA: " . $id_maestro_presupuesto . ")", $login, $fh, $pc, 'orden_pago');

                    }
                }

            } else {
                //PROCESOS SI SE TRATA DE UN ANTICIPO

            } // FIN DE SI NO ES UN ANTICIPO

            $sql_select_orden_compra = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = " . $id_orden_compra . "");
            $bus_select_orden_compra = mysql_fetch_array($sql_select_orden_compra);

            //SI LA FORMA DE PAGO ES ANTICIPO O VALUACION OBTENGO LO PENDIENTE POR PAGAR PARA ACTUALIZAR LOS MONTOS DE LA ORDEN DE PAGO
            if ($bus_orden_pago["forma_pago"] == "parcial" or $bus_orden_pago["forma_pago"] == "valuacion") {
                $sql_relacion = mysql_query("select * from relacion_pago_compromisos where idorden_compra_servicio = '" . $id_orden_compra . "'");
                while ($bus_relacion = mysql_fetch_array($sql_relacion)) {
                    $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '" . $bus_relacion["idorden_pago"] . "'
																							and estado != 'anulado' and estado != 'elaboracion' and anticipo != 'si'");
                    $bus_orden_pago = mysql_fetch_array($sql_orden_pago);
                    $total_exento += $bus_orden_pago["exento"];
                    $total_sub_total += $bus_orden_pago["sub_total"];
                    $total_impuestos += $bus_orden_pago["impuesto"];
                }
                /*if ($anio < $_SESSION["anio_fiscal"]){
                $sql_relacion = mysql_query("select * from gestion_".$_SESSION["anio_fiscal"].".relacion_pago_compromisos
                where idorden_compra_servicio = '".$id_orden_compra."'");
                while($bus_relacion = mysql_fetch_array($sql_relacion)){
                $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_relacion["idorden_pago"]."'
                and estado != 'anulado' and estado != 'elaboracion' and anticipo != 'si'");
                $bus_orden_pago = mysql_fetch_array($sql_orden_pago);
                $total_exento += $bus_orden_pago["exento"];
                $total_sub_total += $bus_orden_pago["sub_total"];
                $total_impuestos += $bus_orden_pago["impuesto"];
                }
                }*/
                //echo $total_exento;
                $sql_orden_compra = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '" . $id_orden_compra . "'");
                $bus_orden_compra = mysql_fetch_array($sql_orden_compra);

                $exento_restante = $bus_orden_compra["exento"] - $total_exento;
                //echo $exento_restante;
                $subtotal_restante = $bus_orden_compra["sub_total"] - $total_sub_total;
                $impuesto_restante = $bus_orden_compra["impuesto"] - $total_impuestos;
                $total_restante    = $exento_restante + $subtotal_restante + $impuesto_restante;
                //$retenido_restante =

                //echo "update orden_pago set exento = '".$exento_restante."', sub_total = '".$subtotal_restante."', impuesto = '".$impuesto_restante."', total = '".$total_restante."' where idorden_pago = '".$idorden_pago."'";
                $sql_actualizar_orden_pago = mysql_query("update orden_pago set
																			exento = '" . $exento_restante . "',
																			sub_total = '" . $subtotal_restante . "',
																			impuesto = '" . $impuesto_restante . "',
																			total = '" . $total_restante . "',
																			total_a_pagar = '" . $total_restante . "'-total_retenido where idorden_pago = '" . $id_orden_pago . "'") or die(mysql_error());

            } else {
                //ACTUALIZO LOS MONTOS DE LA ORDEN DE PAGO SUMANDO LOS VALORES DEL COMPROMISO SELECCIONADO
                $sql_update_orden_pago = mysql_query("update orden_pago set exento = exento+'" . $bus_select_orden_compra["exento"] . "',
																				sub_total = sub_total+'" . $bus_select_orden_compra["sub_total"] . "',
																				impuesto = impuesto+'" . $bus_select_orden_compra["impuesto"] . "'
																				 where idorden_pago = " . $id_orden_pago . "");
                $sql_total = mysql_query("select * from orden_pago where idorden_pago = " . $id_orden_pago . "");
                $bus_total = mysql_fetch_array($sql_total);
                if ($bus_tipo_documento["multi_categoria"] == "no") {
                    $total = $bus_total["exento"] + $bus_total["sub_total"] + $bus_total["impuesto"];
                } else {
                    $sub_total = $bus_total["sub_total"];
                    $total     = $bus_total["sub_total"] - $bus_total["exento"];
                }
                //echo $total;
                //echo "update orden_pago set total = '".$total."' where idorden_pago = ".$id_orden_pago."";
                $sql_update_orden_pago = mysql_query("update orden_pago set total = '" . $total . "',
																				total_a_pagar = '" . $total . "'-total_retenido
																				 where idorden_pago = " . $id_orden_pago . "") or die(mysql_error());
            }

        } else {
            // si ya completo las filas de un comprobante evito que siga ingresando compromisos
            echo "filasagotadas";
        }
        $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = " . $id_orden_pago . "");
        $bus_orden_pago = mysql_fetch_array($sql_orden_pago);
        //echo " forma ".$bus_orden_pago["forma_pago"];
        if ($bus_orden_pago["forma_pago"] != "parcial" && $bus_orden_pago["forma_pago"] != "valuacion") {

            //ACTUALIZO LAS CUENTAS CONTABLES
            if ($bus_tipo_documento["multi_categoria"] == "no") {
                // SI LA ORDEN DE PAGO SE RELACIONA CON COMPROMISOS DE TIPO UNICATEGORIA COMO COMPRAS, SERVICIOS
                $sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = " . $id_orden_pago . "
																and tipo_movimiento = 'causado'");
                if (mysql_num_rows($sql_validar_asiento) > 0) {

                    $bus_asiento_contable = mysql_fetch_array($sql_validar_asiento);

                    $sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																order by afecta");

                    while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)) {
                        if ($bus_cuentas_contables["afecta"] == 'debe') {
                            //echo " total ".$total;
                            $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = '" . $total . "'
																		where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																		and idcuenta = '" . $bus_tipo_documento["idcuenta_debe"] . "'
																		and tabla = '" . $bus_tipo_documento["tabla_debe"] . "'");
                        } else {
                            $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = '" . $total . "'
																		where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																		and idcuenta = '" . $bus_tipo_documento["idcuenta_haber"] . "'
																		and tabla = '" . $bus_tipo_documento["tabla_haber"] . "'");
                        }
                    }
                }
                //VALIDO SI TIENE RETENCIONES PARA DIFERENCIAR LA CUENTA CONTABLE
                $sql_retencion = mysql_query("select * from retenciones where iddocumento = '" . $id_orden_compra . "'");
                $bus_retencion = mysql_fetch_array($sql_retencion);
                $num_retencion = mysql_num_rows($sql_retencion);
                if ($num_retencion > 0) {
                    //OBTENGO LA RELACION DE RETENCIONES PARA SABER DE EL TIPO DE RETENCION Y LAS CUENTAS QUE AFECTA
                    $sql_relacion_retenciones = mysql_query("select * from relacion_retenciones where idretenciones = " . $bus_retencion["idretenciones"] . "");
                    while ($bus_relacion_retenciones = mysql_fetch_array($sql_relacion_retenciones)) {
                        $sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = " . $bus_relacion_retenciones["idtipo_retencion"] . "");
                        $bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
                        //valido la cuenta que afecta la retencion por el debe,
                        //si es la misma que la que ya existe no la afecto,
                        //si no es la misma la busco en el haber para ver si existe y restar el monto retenido
                        $sql_cuentas_contables_debe = mysql_query("select * from cuentas_asiento_contable
																where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																	and idcuenta = '" . $bus_tipo_retencion["idcuenta_debe"] . "'
																	and tabla = '" . $bus_tipo_retencion["tabla_debe"] . "'
																	and afecta = 'debe'");
                        $num_cuenta_retencion_debe = mysql_num_rows($sql_cuentas_contables_debe);
                        if ($num_cuenta_retencion_debe == 0) {
                            //entro aqui porque la cuenta no existe afectando el debe por lo que no la voy a afectar por el debe
                            //la busco en el haber para restarla
                            $sql_cuentas_contables_haber = mysql_query("select * from cuentas_asiento_contable
																where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																	and idcuenta = '" . $bus_tipo_retencion["idcuenta_debe"] . "'
																	and tabla = '" . $bus_tipo_retencion["tabla_debe"] . "'
																	and afecta = 'haber'");
                            $num_cuenta_retencion_haber = mysql_num_rows($sql_cuentas_contables_haber);
                            if ($num_cuenta_retencion_haber > 0) {
                                //valido que la cuenta a insertar no este registrada
                                $sql_cuentas_contables_haber_nueva = mysql_query("select * from cuentas_asiento_contable
																where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																	and idcuenta = '" . $bus_tipo_retencion["idcuenta_haber"] . "'
																	and tabla = '" . $bus_tipo_retencion["tabla_haber"] . "'
																	and afecta = 'haber'");
                                $bus_cuenta_retencion_haber_nueva = mysql_fetch_array($sql_cuentas_contables_haber_nueva);
                                $num_cuenta_retencion_haber_nueva = mysql_num_rows($sql_cuentas_contables_haber_nueva);
                                if ($num_cuenta_retencion_haber_nueva == 0) {
                                    //si la cuenta no existe la insertamos la cuenta
                                    $ingreso_cuenta_haber_retencion = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																													tabla,
																													idcuenta,
																													monto,
																													afecta)values(
																													'" . $bus_asiento_contable["idasiento_contable"] . "',
																													'" . $bus_tipo_retencion["tabla_haber"] . "',
																													'" . $bus_tipo_retencion["idcuenta_haber"] . "',
																													'" . $bus_relacion_retenciones["monto_retenido"] . "',
																													'haber'
																													)");
                                } else {
                                    //si la cuenta existe le sumo el monto retenido
                                    $actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto + '" . $bus_relacion_retenciones["monto_retenido"] . "'
																		where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																				and idcuenta = '" . $bus_tipo_retencion["idcuenta_haber"] . "'
																				and tabla = '" . $bus_tipo_retencion["tabla_haber"] . "'
																				and afecta = 'haber'") or die("actualizando monto contable " . mysql_error());
                                }
                            }
                        }
                    }
                    //actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
                    $sql_suma_retenido = mysql_query("select sum(monto) as monto_retenido from cuentas_asiento_contable
																		where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																			and idcuenta <> '" . $bus_tipo_retencion["idcuenta_debe"] . "'
																			and tabla <> '" . $bus_tipo_retencion["tabla_debe"] . "'
																			and afecta = 'haber'");
                    $monto_retenido = mysql_fetch_array($sql_suma_retenido);
                    //actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
                    //echo 'RETENIDO '.$monto_retenido["monto_retenido"];
                    $actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto - '" . $monto_retenido["monto_retenido"] . "'
																			where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																				and idcuenta = '" . $bus_tipo_retencion["idcuenta_debe"] . "'
																				and tabla = '" . $bus_tipo_retencion["tabla_debe"] . "'
																				and afecta = 'haber'");
                }
            } else {
                // SI EL DOCUMENTO RELACIONADO ES DE TIPO MULTICATEGORIA COMO NOMINAS LAS RETENCIONES BIENEN DE LOS CONCEPTOS DE NOMINA
                $sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = " . $id_orden_pago . "
																and tipo_movimiento = 'causado'");
                if (mysql_num_rows($sql_validar_asiento) > 0) {

                    $sql_select_orden_compra = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = " . $id_orden_compra . "");
                    $bus_select_orden_compra = mysql_fetch_array($sql_select_orden_compra);
                    $bus_asiento_contable    = mysql_fetch_array($sql_validar_asiento);

                    $sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																order by afecta");

                    while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)) {
                        if ($bus_cuentas_contables["afecta"] == 'debe') {
                            //echo " total ".$total;
                            $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = '" . $sub_total . "'
																		where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																		and idcuenta = '" . $bus_tipo_documento["idcuenta_debe"] . "'
																		and tabla = '" . $bus_tipo_documento["tabla_debe"] . "'");
                        } else {
                            $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = '" . $sub_total . "'
																		where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																		and idcuenta = '" . $bus_tipo_documento["idcuenta_haber"] . "'
																		and tabla = '" . $bus_tipo_documento["tabla_haber"] . "'");
                        }
                    }
                }
                //VALIDO SI TIENE RETENCIONES PARA DIFERENCIAR LA CUENTA CONTABLE

                //$num_retencion = mysql_num_rows($sql_deduccion);
                if ($bus_select_orden_compra["exento"] > 0) {
                    //OBTENGO LOS CONCEPTOS DE TIPO DEDUCCION
                    $sql_deduccion = mysql_query("select articulos_compra_servicio.exento,
														 articulos_servicios.idcuenta_debe,
														 articulos_servicios.tabla_debe,
														 articulos_servicios.idcuenta_haber,
														 articulos_servicios.tabla_haber
														from articulos_compra_servicio, articulos_servicios
														where articulos_compra_servicio.idorden_compra_servicio = '" . $id_orden_compra . "'
														and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios
														and articulos_servicios.tipo_concepto = '2'");

                    while ($bus_relacion_deducciones = mysql_fetch_array($sql_deduccion)) {
                        //valido la cuenta que afecta la retencion por el debe,
                        //si es la misma que la que ya existe no la afecto,
                        //si no es la misma la busco en el haber para ver si existe y restar el monto retenido
                        $sql_cuentas_contables_debe = mysql_query("select * from cuentas_asiento_contable
																where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																	and idcuenta = '" . $bus_relacion_deducciones["idcuenta_debe"] . "'
																	and tabla = '" . $bus_relacion_deducciones["tabla_debe"] . "'
																	and afecta = 'debe'");
                        $num_cuenta_retencion_debe = mysql_num_rows($sql_cuentas_contables_debe);
                        //echo " existe en el debe ".$num_cuenta_retencion_debe;
                        if ($num_cuenta_retencion_debe == 0) {
                            //entro aqui porque la cuenta no existe afectando el debe por lo que no la voy a afectar por el debe
                            //la busco en el haber para restarla
                            $sql_cuentas_contables_haber = mysql_query("select * from cuentas_asiento_contable
																where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																	and idcuenta = '" . $bus_relacion_deducciones["idcuenta_debe"] . "'
																	and tabla = '" . $bus_relacion_deducciones["tabla_debe"] . "'
																	and afecta = 'haber'");
                            $num_cuenta_retencion_haber = mysql_num_rows($sql_cuentas_contables_haber);
                            //echo " existe en el haber ".$num_cuenta_retencion_haber;
                            if ($num_cuenta_retencion_haber > 0) {
                                //valido que la cuenta a insertar no este registrada
                                $sql_cuentas_contables_haber_nueva = mysql_query("select * from cuentas_asiento_contable
																where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																	and idcuenta = '" . $bus_relacion_deducciones["idcuenta_haber"] . "'
																	and tabla = '" . $bus_relacion_deducciones["tabla_haber"] . "'
																	and afecta = 'haber'");
                                $bus_cuenta_retencion_haber_nueva = mysql_fetch_array($sql_cuentas_contables_haber_nueva);
                                $num_cuenta_retencion_haber_nueva = mysql_num_rows($sql_cuentas_contables_haber_nueva);
                                if ($num_cuenta_retencion_haber_nueva == 0) {
                                    //si la cuenta no existe la insertamos la cuenta
                                    $ingreso_cuenta_haber_retencion = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																													tabla,
																													idcuenta,
																													monto,
																													afecta)values(
																													'" . $bus_asiento_contable["idasiento_contable"] . "',
																													'" . $bus_relacion_deducciones["tabla_haber"] . "',
																													'" . $bus_relacion_deducciones["idcuenta_haber"] . "',
																													'" . $bus_relacion_deducciones["exento"] . "',
																													'haber'
																													)");
                                } else {
                                    //si la cuenta existe le sumo el monto retenido
                                    $actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto + '" . $bus_relacion_deducciones["exento"] . "'
																		where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																				and idcuenta = '" . $bus_relacion_deducciones["idcuenta_haber"] . "'
																				and tabla = '" . $bus_relacion_deducciones["tabla_haber"] . "'
																				and afecta = 'haber'") or die("actualizando monto contable " . mysql_error());
                                }
                                //actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
                                $actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto - '" . $bus_relacion_deducciones["exento"] . "'
																			where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																				and idcuenta = '" . $bus_relacion_deducciones["idcuenta_debe"] . "'
																				and tabla = '" . $bus_relacion_deducciones["tabla_debe"] . "'
																				and afecta = 'haber'");
                            }
                        }
                    }
                }
            }
        }
    } else {

        //SI EL COMPROMISO YA ESTABA RELACIONADO CON LA ORDEN DE PAGO ES PORQUE LO ESTAN DESELECCIONANDO Y LO RESTO A LOS TOTALES Y A LAS PARTIDAS
        $sql_partidas_orden_compra = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = " . $id_orden_compra . "");
        while ($bus_partidas_orden_compra = mysql_fetch_array($sql_partidas_orden_compra)) {

            $id_maestro_presupuesto = $bus_partidas_orden_compra["idmaestro_presupuesto"];

            $sql_seleccionar_partida = mysql_query("select * from partidas_orden_pago where idorden_pago = '" . $id_orden_pago . "'
																						and idmaestro_presupuesto = '" . $id_maestro_presupuesto . "'");
            $bus_seleccionar_partida = mysql_fetch_array($sql_seleccionar_partida);
            $monto                   = $bus_seleccionar_partida["monto"] - $bus_partidas_orden_compra["monto"];
            if ($monto <= 0) {
                $sql_eliminar_partida = mysql_query("delete from partidas_orden_pago where idorden_pago = '" . $id_orden_pago . "'
																						and idmaestro_presupuesto = '" . $id_maestro_presupuesto . "'");
            } else {
                $sql_eliminar_partida = mysql_query("update partidas_orden_pago set monto = monto-" . $bus_partidas_orden_compra["monto"] . "
																						where idorden_pago = '" . $id_orden_pago . "'
																						and idmaestro_presupuesto = '" . $id_maestro_presupuesto . "'");
            }
        }

        //echo "HOLA:".$bus_orden_pago["forma_pago"];
        //ACTUALIZO LOS TOTALES PENDIENTES POR PAGAR PARA RESTARLE LOS MONTOS DEL COMPROMISO DESELECCIONADO
        if ($bus_orden_pago["forma_pago"] == "parcial" or $bus_orden_pago["forma_pago"] == "valuacion") {
            $sql_relacion = mysql_query("select * from relacion_pago_compromisos where idorden_compra_servicio = '" . $id_orden_compra . "'");
            while ($bus_relacion = mysql_fetch_array($sql_relacion)) {
                $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '" . $bus_relacion["idorden_pago"] . "'");
                $bus_orden_pago = mysql_fetch_array($sql_orden_pago);
                $total_exento += $bus_orden_pago["exento"];
                $total_sub_total += $bus_orden_pago["sub_total"];
                $total_impuestos += $bus_orden_pago["impuesto"];
            }
            /*if ($anio < $_SESSION["anio_fiscal"]){
            $sql_relacion = mysql_query("select * from gestion_".$_SESSION["anio_fiscal"].".relacion_pago_compromisos
            where idorden_compra_servicio = '".$id_orden_compra."'");
            while($bus_relacion = mysql_fetch_array($sql_relacion)){
            $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_relacion["idorden_pago"]."'
            and estado != 'anulado' and estado != 'elaboracion' and anticipo != 'si'");
            $bus_orden_pago = mysql_fetch_array($sql_orden_pago);
            $total_exento += $bus_orden_pago["exento"];
            $total_sub_total += $bus_orden_pago["sub_total"];
            $total_impuestos += $bus_orden_pago["impuesto"];
            }
            }*/
            $sql_orden_compra = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '" . $id_orden_compra . "'");
            $bus_orden_compra = mysql_fetch_array($sql_orden_compra);

            $exento_restante   = $bus_orden_compra["exento"] - $total_exento;
            $subtotal_restante = $bus_orden_compra["sub_total"] - $total_sub_total;
            $impuesto_restante = $bus_orden_compra["impuesto"] - $total_impuesto;
            $total_restante    = $exento_restante + $subtotal_restante + $impuesto_restante;

            $sql_retenciones = mysql_query("select * from retenciones where iddocumento = '" . $id_orden_compra . "'");
            $bus_retenciones = mysql_fetch_array($sql_retenciones);

            $sql_actualizar_orden_pago = mysql_query("update orden_pago set exento = 0,
																		 sub_total = 0,
																		 impuesto = 0,
																		 total = 0,
																		 total_retenido = 0,
																		 total_a_pagar = 0
																		 where idorden_pago = '" . $id_orden_pago . "'");

        } else {
            //SI EL PAGO ES TOTAL LE RESTO LOS MONTOS DEL COMPROMISO DESELECCIONADO
            $sql_select_orden_compra = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = " . $id_orden_compra . "");
            $bus_select_orden_compra = mysql_fetch_array($sql_select_orden_compra);
            $sql_update_orden_pago   = mysql_query("update orden_pago set exento = exento-'" . $bus_select_orden_compra["exento"] . "',
																			sub_total = sub_total-'" . $bus_select_orden_compra["sub_total"] . "',
																			impuesto = impuesto-'" . $bus_select_orden_compra["impuesto"] . "'
																			 where idorden_pago = " . $id_orden_pago . "");
            $sql_total = mysql_query("select * from orden_pago where idorden_pago = " . $id_orden_pago . "");
            $bus_total = mysql_fetch_array($sql_total);
            //echo "PRUEBAAAAAAA: ".$bus_tipo_documento["multi_categoria"];
            if ($bus_tipo_documento["multi_categoria"] == "no") {
                $total = $bus_total["exento"] + $bus_total["sub_total"] + $bus_total["impuesto"];
            } else {
                $sub_total = $bus_total["sub_total"];
                $total     = $bus_total["sub_total"] - $bus_total["exento"];
            }
            $sql_retenciones       = mysql_query("select * from retenciones where iddocumento = '" . $bus_select_orden_compra["idorden_compra_servicio"] . "'");
            $bus_retenciones       = mysql_fetch_array($sql_retenciones);
            $sql_update_orden_pago = mysql_query("update orden_pago set total = '" . $total . "',
																	total_a_pagar = '" . $total . "'-total_retenido
																	 where idorden_pago = " . $id_orden_pago . "") or die(mysql_error());
        }

        $sql_borrar_relacion = mysql_query("delete from relacion_pago_compromisos where idorden_pago ='" . $id_orden_pago . "'
																				and idorden_compra_servicio = '" . $id_orden_compra . "'");

        //ACTUALIZO LAS CUENTAS CONTABLES
        if ($bus_tipo_documento["multi_categoria"] == "no") {
            // SI LA ORDEN DE PAGO SE RELACIONA CON COMPROMISOS DE TIPO UNICATEGORIA COMO COMPRAS, SERVICIOS
            $sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = " . $id_orden_pago . "
															and tipo_movimiento = 'causado'");
            if (mysql_num_rows($sql_validar_asiento) > 0) {

                $bus_asiento_contable = mysql_fetch_array($sql_validar_asiento);

                $sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
															order by afecta");

                while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)) {
                    if ($bus_cuentas_contables["afecta"] == 'debe') {
                        $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = '" . $total . "'
																	where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																	and idcuenta = '" . $bus_tipo_documento["idcuenta_debe"] . "'
																	and tabla = '" . $bus_tipo_documento["tabla_debe"] . "'");
                    } else {
                        $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = '" . $total . "'
																	where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																	and idcuenta = '" . $bus_tipo_documento["idcuenta_haber"] . "'
																	and tabla = '" . $bus_tipo_documento["tabla_haber"] . "'");
                    }
                }
            }
            //VALIDO SI TIENE RETENCIONES PARA DIFERENCIAR LA CUENTA CONTABLE
            $sql_retencion = mysql_query("select * from retenciones where iddocumento = '" . $id_orden_compra . "'");
            $bus_retencion = mysql_fetch_array($sql_retencion);
            $num_retencion = mysql_num_rows($sql_retencion);
            if ($num_retencion > 0) {
                //OBTENGO LA RELACION DE RETENCIONES PARA SABER DE EL TIPO DE RETENCION Y LAS CUENTAS QUE AFECTA
                $sql_relacion_retenciones = mysql_query("select * from relacion_retenciones where idretenciones = " . $bus_retencion["idretenciones"] . "");
                while ($bus_relacion_retenciones = mysql_fetch_array($sql_relacion_retenciones)) {
                    $sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = " . $bus_relacion_retenciones["idtipo_retencion"] . "");
                    $bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
                    //valido la cuenta que afecta la retencion por el debe,
                    //si es la misma que la que ya existe no la afecto,
                    //si no es la misma la busco en el haber para ver si existe y restar el monto retenido
                    $sql_cuentas_contables_debe = mysql_query("select * from cuentas_asiento_contable
															where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																and idcuenta = '" . $bus_tipo_retencion["idcuenta_debe"] . "'
																and tabla = '" . $bus_tipo_retencion["tabla_debe"] . "'
																and afecta = 'debe'");
                    $num_cuenta_retencion_debe = mysql_num_rows($sql_cuentas_contables_debe);
                    if ($num_cuenta_retencion_debe == 0) {
                        //entro aqui porque la cuenta no existe afectando el debe por lo que no la voy a afectar por el debe
                        //la busco en el haber para restarla
                        $sql_cuentas_contables_haber = mysql_query("select * from cuentas_asiento_contable
															where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																and idcuenta = '" . $bus_tipo_retencion["idcuenta_debe"] . "'
																and tabla = '" . $bus_tipo_retencion["tabla_debe"] . "'
																and afecta = 'haber'");
                        $num_cuenta_retencion_haber = mysql_num_rows($sql_cuentas_contables_haber);
                        if ($num_cuenta_retencion_haber > 0) {
                            //valido que la cuenta a insertar no este registrada
                            $sql_cuentas_contables_haber_nueva = mysql_query("select * from cuentas_asiento_contable
															where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																and idcuenta = '" . $bus_tipo_retencion["idcuenta_haber"] . "'
																and tabla = '" . $bus_tipo_retencion["tabla_haber"] . "'
																and afecta = 'haber'");
                            $bus_cuenta_retencion_haber_nueva = mysql_fetch_array($sql_cuentas_contables_haber_nueva);
                            $num_cuenta_retencion_haber_nueva = mysql_num_rows($sql_cuentas_contables_haber_nueva);

                            $actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto - '" . $bus_relacion_retenciones["monto_retenido"] . "'
																	where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																			and idcuenta = '" . $bus_tipo_retencion["idcuenta_haber"] . "'
																			and tabla = '" . $bus_tipo_retencion["tabla_haber"] . "'
																			and afecta = 'haber'") or die("actualizando monto contable " . mysql_error());
                            $valido_cuenta_en_cero = mysql_query("delete from cuentas_asiento_contable
																	where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																			and idcuenta = '" . $bus_tipo_retencion["idcuenta_haber"] . "'
																			and tabla = '" . $bus_tipo_retencion["tabla_haber"] . "'
																			and afecta = 'haber'
																			and monto <= 0");
                        }
                    }
                }

                //actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
                $sql_suma_retenido = mysql_query("select sum(monto) as monto_retenido from cuentas_asiento_contable
																	where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																		and idcuenta <> '" . $bus_tipo_retencion["idcuenta_debe"] . "'
																		and tabla <> '" . $bus_tipo_retencion["tabla_debe"] . "'
																		and afecta = 'haber'");
                $monto_retenido = mysql_fetch_array($sql_suma_retenido);
                //actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
                //echo ' RETENIDO desactivo '.$monto_retenido["monto_retenido"];
                $actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto - '" . $monto_retenido["monto_retenido"] . "'
																		where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																			and idcuenta = '" . $bus_tipo_retencion["idcuenta_debe"] . "'
																			and tabla = '" . $bus_tipo_retencion["tabla_debe"] . "'
																			and afecta = 'haber'");
            }
        } else {
            // SI EL DOCUMENTO RELACIONADO ES DE TIPO MULTICATEGORIA COMO NOMINAS LAS RETENCIONES BIENEN DE LOS CONCEPTOS DE NOMINA
            $sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = " . $id_orden_pago . "
																and tipo_movimiento = 'causado'");
            if (mysql_num_rows($sql_validar_asiento) > 0) {

                $sql_select_orden_compra = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = " . $id_orden_compra . "");
                $bus_select_orden_compra = mysql_fetch_array($sql_select_orden_compra);
                $bus_asiento_contable    = mysql_fetch_array($sql_validar_asiento);

                $sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																order by afecta");

                while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)) {
                    if ($bus_cuentas_contables["afecta"] == 'debe') {
                        //echo " total ".$total;
                        $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = '" . $sub_total . "'
																		where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																		and idcuenta = '" . $bus_tipo_documento["idcuenta_debe"] . "'
																		and tabla = '" . $bus_tipo_documento["tabla_debe"] . "'");
                    } else {
                        $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = '" . $sub_total . "'
																		where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																		and idcuenta = '" . $bus_tipo_documento["idcuenta_haber"] . "'
																		and tabla = '" . $bus_tipo_documento["tabla_haber"] . "'");
                    }
                }
            }
            //VALIDO SI TIENE RETENCIONES PARA DIFERENCIAR LA CUENTA CONTABLE

            //$num_retencion = mysql_num_rows($sql_deduccion);
            if ($bus_select_orden_compra["exento"] > 0) {
                //OBTENGO LOS CONCEPTOS DE TIPO DEDUCCION
                $sql_deduccion = mysql_query("select articulos_compra_servicio.exento,
														 articulos_servicios.idcuenta_debe,
														 articulos_servicios.tabla_debe,
														 articulos_servicios.idcuenta_haber,
														 articulos_servicios.tabla_haber
														from articulos_compra_servicio, articulos_servicios
														where articulos_compra_servicio.idorden_compra_servicio = '" . $id_orden_compra . "'
														and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios
														and articulos_servicios.tipo_concepto = '2'");

                while ($bus_relacion_deducciones = mysql_fetch_array($sql_deduccion)) {
                    //valido la cuenta que afecta la retencion por el debe,
                    //si es la misma que la que ya existe no la afecto,
                    //si no es la misma la busco en el haber para ver si existe y restar el monto retenido
                    $sql_cuentas_contables_debe = mysql_query("select * from cuentas_asiento_contable
																where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																	and idcuenta = '" . $bus_relacion_deducciones["idcuenta_debe"] . "'
																	and tabla = '" . $bus_relacion_deducciones["tabla_debe"] . "'
																	and afecta = 'debe'");
                    $num_cuenta_retencion_debe = mysql_num_rows($sql_cuentas_contables_debe);
                    //echo " existe en el debe ".$num_cuenta_retencion_debe;
                    if ($num_cuenta_retencion_debe == 0) {
                        //entro aqui porque la cuenta no existe afectando el debe por lo que no la voy a afectar por el debe
                        //la busco en el haber para restarla
                        $sql_cuentas_contables_haber = mysql_query("select * from cuentas_asiento_contable
																where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																	and idcuenta = '" . $bus_relacion_deducciones["idcuenta_debe"] . "'
																	and tabla = '" . $bus_relacion_deducciones["tabla_debe"] . "'
																	and afecta = 'haber'");
                        $num_cuenta_retencion_haber = mysql_num_rows($sql_cuentas_contables_haber);
                        //echo " existe en el haber ".$num_cuenta_retencion_haber;
                        if ($num_cuenta_retencion_haber > 0) {
                            //valido que la cuenta a insertar no este registrada
                            $sql_cuentas_contables_haber_nueva = mysql_query("select * from cuentas_asiento_contable
																where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																	and idcuenta = '" . $bus_relacion_deducciones["idcuenta_haber"] . "'
																	and tabla = '" . $bus_relacion_deducciones["tabla_haber"] . "'
																	and afecta = 'haber'");
                            $bus_cuenta_retencion_haber_nueva = mysql_fetch_array($sql_cuentas_contables_haber_nueva);
                            $num_cuenta_retencion_haber_nueva = mysql_num_rows($sql_cuentas_contables_haber_nueva);

                            //si la cuenta existe le sumo el monto retenido
                            $actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto - '" . $bus_relacion_deducciones["exento"] . "'
																		where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																				and idcuenta = '" . $bus_relacion_deducciones["idcuenta_haber"] . "'
																				and tabla = '" . $bus_relacion_deducciones["tabla_haber"] . "'
																				and afecta = 'haber'") or die("actualizando monto contable " . mysql_error());

                            $valido_cuenta_en_cero = mysql_query("delete from cuentas_asiento_contable
																	where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																			and idcuenta = '" . $bus_relacion_deducciones["idcuenta_haber"] . "'
																			and tabla = '" . $bus_relacion_deducciones["tabla_haber"] . "'
																			and afecta = 'haber'
																			and monto <= 0");
                            //actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
                            /*$actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto + '".$bus_relacion_deducciones["exento"]."'
                        where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'
                        and idcuenta = '".$bus_relacion_deducciones["idcuenta_debe"]."'
                        and tabla = '".$bus_relacion_deducciones["tabla_debe"]."'
                        and afecta = 'haber'");*/
                        }
                    }
                }
            }

        }

        registra_transaccion("Eliminar compromiso desde orden de Pago (ID Orden: " . $id_orden_pago . ", ID PARTIDA: " . $id_maestro_presupuesto . ")", $login, $fh, $pc, 'orden_pago');
    }
}

if ($ejecutar == "actualizarFacturacionListaCompromisos") {
    $sql_update = mysql_query("update relacion_pago_compromisos set nro_factura = '" . $nro_factura . "',
																	fecha_factura ='" . $fecha_factura . "',
																	nro_control ='" . $nro_control . "'
																	where idorden_compra_servicio = '" . $id_orden_compra . "'
																	and idorden_pago = '" . $id_orden_pago . "'");
}

if ($ejecutar == "montoRetenidoTotal") {
    //echo "select * from relacion_orden_pago_retencion where idorden_pago = ".$id_orden_pago."";
    $sql_relacion_retencion = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago = '" . $id_orden_pago . "'");
    while ($bus_relacion_retencion = mysql_fetch_array($sql_relacion_retencion)) {
        $sql_retencion = mysql_query("select * from retenciones where idretenciones = '" . $bus_relacion_retencion["idretencion"] . "'") or die(mysql_error());
        $bus_retencion = mysql_fetch_array($sql_retencion);
        $tipo_pago     = $bus_retencion["tipo_pago"];
        $total_retenido += $bus_retencion["total_retenido"];
        //$total_a_pagar += ($bus_retencion["total"]-$bus_retencion["total_retenido"]);
    }

    $sql_orden     = mysql_query("select * from orden_pago where idorden_pago = '" . $id_orden_pago . "'");
    $bus_orden     = mysql_fetch_array($sql_orden);
    $total_a_pagar = $bus_orden["total"] - $total_retenido;
    $sql_orden     = mysql_query("update orden_pago set total_retenido = '" . $total_retenido . "',
													total_a_pagar = '" . $total_a_pagar . "' where idorden_pago = '" . $id_orden_pago . "'");

    //echo $bus_retencion["total"]."<br />";
    echo $tipo_pago . "-" . number_format($total_retenido, 2, ",", ".") . "-" . number_format($total_a_pagar, 2, ",", ".") . "-" . $total_retenido . "-" . $total_a_pagar;
}

if ($ejecutar == "mostrarListaRetenciones") {

    $sql_orden = mysql_query("select * from orden_pago where idorden_pago = '" . $id_orden_pago . "'") or die(mysql_error());
    $bus_orden = mysql_fetch_array($sql_orden);

    $muestra_retencion = 0;
    $sql_relacion      = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '" . $id_orden_pago . "'");
    if (mysql_num_rows($sql_relacion)) {
        $existe_retencion = mysql_num_rows($sql_relacion);

        if ($existe_retencion > 0) {
            $muestra_retencion = 1;
            $bus_relacion      = mysql_fetch_array($sql_relacion);
        }
    }

    if ($bus_orden["estado"] == "procesado" || $bus_orden["estado"] == "pagada" || $bus_orden["estado"] == "parcial" || $bus_orden["estado"] == "anulado") {
        $sql_relacion_retencion = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago = '" . $id_orden_pago . "'");
        $bus_relacion_retencion = mysql_fetch_array($sql_relacion_retencion);

        $sql_retenciones = mysql_query("select * from retenciones where idretenciones = '" . $bus_relacion_retencion["idretencion"] . "'");
        $num_retenciones = mysql_num_rows($sql_retenciones);
    } else {

        $sql_retenciones = mysql_query("select * from retenciones where iddocumento = '" . $bus_relacion["idorden_compra_servicio"] . "' and estado = 'procesado'");
        $num_retenciones = mysql_num_rows($sql_retenciones);
    }
    //echo $num_retenciones;
    if ($num_retenciones > 0 && ($bus_orden["forma_pago"] == 'parcial' || $bus_orden["forma_pago"] == 'valuacion')) {

        ?>
    <br />
    <h4 align=center>Lista de Retenciones</h4>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <td width="18%" class="Browse"><div align="center">Nro. Factura</div></td>
            <td width="18%" class="Browse"><div align="center">Nro. Control</div></td>
            <td width="25%" class="Browse"><div align="center">Fecha Factura</div></td>
            <td width="14%" class="Browse"><div align="center">Monto Retenido</div></td>
            <td width="5%" class="Browse"><div align="center">Act</div></td>
            <td width="10%" class="Browse"><div align="center">Seleccionar</div></td>
    </tr>
          </thead>
          <?
        if ($muestra_retencion == 1) {
            while ($bus_retenciones = mysql_fetch_array($sql_retenciones)) {
                $sql_relacion_pago_retencion = mysql_query("select * from relacion_orden_pago_retencion
																			where idretencion = '" . $bus_retenciones["idretenciones"] . "'
																			");
                if (mysql_num_rows($sql_relacion_pago_retencion) > 0) {
                    $bus_relacion_pago_retencion = mysql_fetch_array($sql_relacion_pago_retencion);
                    $valida_estado_op            = mysql_query("select * from orden_pago where idorden_pago = '" . $bus_relacion_pago_retencion["idorden_pago"] . "'");
                    $bus_valida_estado_op        = mysql_fetch_array($valida_estado_op);
                    $estado_validado             = $bus_valida_estado_op["estado"];
                } else {
                    $estado_validado = '';
                }
                //echo $bus_valida_estado_op["estado"];
                //if($estado_validado == 'elaboracion' or $estado_validado == ''){

                ?>
			  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td class='Browse' align='center'>
					<?if ($bus_retenciones["tipo_pago"] == 'parcial' or $bus_retenciones["tipo_pago"] == 'valuacion') {?>
						<input size="18" type="text" name="numero_factura<?=$bus_retenciones["idretenciones"]?>"
														id="numero_factura<?=$bus_retenciones["idretenciones"]?>"
														value="<?=$bus_retenciones["numero_factura"]?>">
					<?} else {?> &nbsp; <?}?>
				</td>
				<td class='Browse' align='center'>
					<?if ($bus_retenciones["tipo_pago"] == 'parcial' or $bus_retenciones["tipo_pago"] == 'valuacion') {?>
						<input size="18" type="text" name="numero_control<?=$bus_retenciones["idretenciones"]?>"
															id="numero_control<?=$bus_retenciones["idretenciones"]?>"
															value="<?=$bus_retenciones["numero_control"]?>">
					<?} else {?> &nbsp; <?}?>
				</td>
				<td class='Browse' align='center'>
					<?if ($bus_retenciones["tipo_pago"] == 'parcial' or $bus_retenciones["tipo_pago"] == 'valuacion') {?>
						<input size="12" type="text" name="fecha_factura<?=$bus_retenciones["idretenciones"]?>"
														id="fecha_factura<?=$bus_retenciones["idretenciones"]?>"
														value="<?=$bus_retenciones["fecha_factura"]?>">
														<img src="imagenes/jscalendar0.gif" name="f_trigger_c<?=$bus_retenciones["idretenciones"]?>" width="16" height="16" id="f_trigger_c<?=$bus_retenciones["idretenciones"]?>" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onClick="Calendar.setup({
									inputField    : 'fecha_factura<?=$bus_retenciones["idretenciones"]?>',
									button        : 'f_trigger_c<?=$bus_retenciones["idretenciones"]?>',
									align         : 'Tr',
									ifFormat      : '%Y-%m-%d'
									}); "/>
					<?} else {?> &nbsp; <?}?>
				</td>
				<td class='Browse' align='right'>&nbsp;<?=number_format($bus_retenciones["total_retenido"], 2, ",", ".")?></td>
				<td class='Browse' align='center'>
					<?if ($bus_retenciones["tipo_pago"] == 'parcial' or $bus_retenciones["tipo_pago"] == 'valuacion') {?>
						<img src="imagenes/refrescar.png" border="0" style="cursor:pointer" onclick="actualizarDatosFactura('<?=$bus_retenciones["idretenciones"]?>', document.getElementById('numero_factura<?=$bus_retenciones["idretenciones"]?>').value, document.getElementById('numero_control<?=$bus_retenciones["idretenciones"]?>').value, document.getElementById('fecha_factura<?=$bus_retenciones["idretenciones"]?>').value)">
					<?} else {?> &nbsp; <?}?>
				</td>
				<td class='Browse' align='center'>

					&nbsp;
					<?
                $total_a_pagar = $bus_retenciones["total"] - $bus_retenciones["total_retenido"];

                $sql_relacion_pago_retencion = mysql_query("select * from relacion_orden_pago_retencion
																				where idretencion = '" . $bus_retenciones["idretenciones"] . "'
																				and idorden_pago = '" . $id_orden_pago . "'");
                //echo " existe en retencion ".mysql_num_rows($sql_relacion_pago_retencion);
                $bus_relacion_pago_retencion = mysql_fetch_array($sql_relacion_pago_retencion);
                if (mysql_num_rows($sql_relacion_pago_retencion) > 0) {
                    $valida_estado_op     = mysql_query("select * from orden_pago where idorden_pago = '" . $bus_relacion_pago_retencion["idorden_pago"] . "'");
                    $bus_valida_estado_op = mysql_fetch_array($valida_estado_op);
                    //echo " op1 ".$bus_estado_pago["numero_orden"]." ".$bus_estado_pago["estado"];
                    if ($bus_valida_estado_op["estado"] != 'elaboracion') {
                        echo "<img src='imagenes/validar.png'>";
                    } else {
                        $sql_relacion_pago_retencion = mysql_query("select * from relacion_orden_pago_retencion, orden_pago
																			where relacion_orden_pago_retencion.idretencion = '" . $bus_retenciones["idretenciones"] . "'
																	and relacion_orden_pago_retencion.idorden_pago = '" . $bus_relacion_pago_retencion["idorden_pago"] . "'
																					and orden_pago.idorden_pago = relacion_orden_pago_retencion.idorden_pago
																					and orden_pago.estado <> 'anulado'");

                        //echo mysql_num_rows($sql_relacion_pago_retencion);
                        if ($existe_en_relacion = mysql_num_rows($sql_relacion_pago_retencion) > 0) {
                            $num_estado_pago = 1;

                        } else {

                            $num_estado_pago = 0;
                        }
                        //$sql_estado_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_relacion_pago_retencion["idorden_pago"]."'");
                        //$num_estado_pago = mysql_num_rows($sql_estado_pago);
                        if ($num_estado_pago > 0) {
                            $sql_estado_pago = mysql_query("select * from orden_pago where idorden_pago = '" . $bus_relacion_pago_retencion["idorden_pago"] . "'");
                            $bus_estado_pago = mysql_fetch_array($sql_estado_pago);
                            //echo " op ".$bus_estado_pago["numero_orden"]." ".$bus_estado_pago["estado"];
                            if ($bus_estado_pago["estado"] == 'procesado' || $bus_estado_pago["estado"] == 'pagada' || $bus_estado_pago["estado"] == 'conformado' || $bus_estado_pago["estado"] == 'parcial') {
                                echo "<img src='imagenes/validar.png'>";
                            } else {
                                ?>
									<input type="radio" id="retencion_<?=$bus_retenciones["idretenciones"]?>"
											name="retenciones"
											onclick="document.getElementById('textoMontoRetenidoFinalOculto').value = '<?=$bus_retenciones["total_retenido"]?>',
                                            document.getElementById('textoTotalAPagarFinalOculto').value = '<?=$total_a_pagar?>',
                                            document.getElementById('textoMontoRetenidoFinal').value = '<?=number_format($bus_retenciones["total_retenido"], 2, ",", ".")?>',
                                            document.getElementById('textoTotalAPagarFinal').value = '<?=number_format($total_a_pagar, 2, ",", ".")?>',
                                            document.getElementById('exento_actual_mostrado').value = '<?=number_format($bus_retenciones["exento"], 2, ",", ".")?>',
                                            document.getElementById('subtotal_actual_mostrado').value = '<?=number_format($bus_retenciones["base"], 2, ",", ".")?>',
                                            document.getElementById('impuesto_actual_mostrado').value = '<?=number_format($bus_retenciones["impuesto"], 2, ",", ".")?>',
                                            document.getElementById('monto_actual_mostrado').value = '<?=number_format($bus_retenciones["total"], 2, ",", ".")?>',
                                            document.getElementById('exento_actual').value = '<?=$bus_retenciones["exento"]?>',
                                            document.getElementById('subtotal_actual').value = '<?=$bus_retenciones["base"]?>',
                                            document.getElementById('impuesto_actual').value = '<?=$bus_retenciones["impuesto"]?>',
                                            document.getElementById('monto_actual').value = '<?=$bus_retenciones["total"]?>',
                                            agregarRetencionParcial('<?=$bus_retenciones["idretenciones"]?>',
                                            document.getElementById('id_orden_pago').value,
                                            '<?=$bus_retenciones["exento"]?>',
                                            '<?=$bus_retenciones["base"]?>',
                                            '<?=$bus_retenciones["impuesto"]?>',
                                            '<?=$bus_retenciones["total"]?>',
                                            '<?=$bus_retenciones["total_retenido"]?>',
                                            '<?=$total_a_pagar?>')"
											checked="checked">
							<?
                            }
                        }
                    }
                } else {
                    ?>
						<input type="radio" id="retencion_<?=$bus_retenciones["idretenciones"]?>"
										name="retenciones"
										onclick="document.getElementById('textoMontoRetenidoFinalOculto').value = '<?=$bus_retenciones["total_retenido"]?>',
                                        document.getElementById('textoTotalAPagarFinalOculto').value = '<?=$total_a_pagar?>',
                                        document.getElementById('textoMontoRetenidoFinal').value = '<?=number_format($bus_retenciones["total_retenido"], 2, ",", ".")?>',
                                        document.getElementById('textoTotalAPagarFinal').value = '<?=number_format($total_a_pagar, 2, ",", ".")?>',
                                        document.getElementById('exento_actual_mostrado').value = '<?=number_format($bus_retenciones["exento"], 2, ",", ".")?>',
                                        document.getElementById('subtotal_actual_mostrado').value = '<?=number_format($bus_retenciones["base"], 2, ",", ".")?>',
                                        document.getElementById('impuesto_actual_mostrado').value = '<?=number_format($bus_retenciones["impuesto"], 2, ",", ".")?>',
                                        document.getElementById('monto_actual_mostrado').value = '<?=number_format($bus_retenciones["total"], 2, ",", ".")?>',
                                        document.getElementById('exento_actual').value = '<?=$bus_retenciones["exento"]?>',
                                        document.getElementById('subtotal_actual').value = '<?=$bus_retenciones["base"]?>',
                                        document.getElementById('impuesto_actual').value = '<?=$bus_retenciones["impuesto"]?>',
                                        document.getElementById('monto_actual').value = '<?=$bus_retenciones["total"]?>',
                                        agregarRetencionParcial('<?=$bus_retenciones["idretenciones"]?>',
                                        document.getElementById('id_orden_pago').value,
                                        '<?=$bus_retenciones["exento"]?>',
                                        '<?=$bus_retenciones["base"]?>',
                                        '<?=$bus_retenciones["impuesto"]?>',
                                        '<?=$bus_retenciones["total"]?>',
                                        '<?=$bus_retenciones["total_retenido"]?>',
                                        '<?=$total_a_pagar?>')">
						<?
                }

                //}
                ?>
				</td>
		  </tr>
			  <?
            }
        }
    }
    ?>
        </table>
	<?

}

if ($ejecutar == "agregarRetencionParcial") {
    $sql_orden = mysql_query("select * from orden_pago where idorden_pago = '" . $id_orden_pago . "'");
    $bus_orden = mysql_fetch_array($sql_orden);

    $sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '" . $bus_orden["tipo"] . "'");
    $bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);

    $sql_relacion_retenciones_pago = mysql_query("select * from relacion_orden_pago_retencion
																where idretencion = '" . $id_retencion . "'
																and idorden_pago = '" . $id_orden_pago . "'");
    $num_relacion_retenciones_pago = mysql_num_rows($sql_relacion_retenciones_pago);
    //echo " orden pago ".$id_orden_pago;
    //SI YA EXISTE EN LA RELACION RETENCIONES ES PORQUE LA ESTA DESMARCANDO Y LA DEBO BORRAR Y LIMPIAR LAS CUENTAS CONTABLES
    if ($num_relacion_retenciones_pago != 0) {
        //echo " entro aqui ".$id_retencion;
        $sql_eliminar_orden_pago_retencion = mysql_query("delete from relacion_orden_pago_retencion
																		where idretencion = '" . $id_retencion . "'
																		and idorden_pago = '" . $id_orden_pago . "'");
        $sql_update_orden_pago = mysql_query("update orden_pago set exento = 0,
																	sub_total = 0,
																	impuesto = 0,
																	total = 0,
																	total_retenido = 0,
																	total_a_pagar = 0 where idorden_pago = '" . $id_orden_pago . "'") or die(mysql_error());

        //ACTUALIZO LAS CUENTAS CONTABLES
        // SI LA ORDEN DE PAGO SE RELACIONA CON COMPROMISOS DE TIPO UNICATEGORIA COMO COMPRAS, SERVICIOS
        $sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = " . $id_orden_pago . "
															and tipo_movimiento = 'causado'");
        if (mysql_num_rows($sql_validar_asiento) > 0) {

            $bus_asiento_contable = mysql_fetch_array($sql_validar_asiento);

            $sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
															order by afecta");

            while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)) {
                if ($bus_cuentas_contables["afecta"] == 'debe') {
                    $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = 0
																	where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																	and idcuenta = '" . $bus_tipo_documento["idcuenta_debe"] . "'
																	and tabla = '" . $bus_tipo_documento["tabla_debe"] . "'");
                } else {
                    $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = 0
																	where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																	and idcuenta = '" . $bus_tipo_documento["idcuenta_haber"] . "'
																	and tabla = '" . $bus_tipo_documento["tabla_haber"] . "'");
                }
            }
        }
        //VALIDO SI TIENE RETENCIONES PARA DIFERENCIAR LA CUENTA CONTABLE
        $sql_retencion = mysql_query("select * from retenciones where idretenciones = '" . $id_retencion . "'");
        $bus_retencion = mysql_fetch_array($sql_retencion);
        $num_retencion = mysql_num_rows($sql_retencion);
        if ($num_retencion > 0) {
            //OBTENGO LA RELACION DE RETENCIONES PARA SABER DE EL TIPO DE RETENCION Y LAS CUENTAS QUE AFECTA
            $sql_relacion_retenciones = mysql_query("select * from relacion_retenciones where idretenciones = " . $bus_retencion["idretenciones"] . "");
            while ($bus_relacion_retenciones = mysql_fetch_array($sql_relacion_retenciones)) {
                $sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = " . $bus_relacion_retenciones["idtipo_retencion"] . "");
                $bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
                //valido la cuenta que afecta la retencion por el debe,
                //si es la misma que la que ya existe no la afecto,
                //si no es la misma la busco en el haber para ver si existe y restar el monto retenido
                $sql_cuentas_contables_debe = mysql_query("select * from cuentas_asiento_contable
															where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																and idcuenta = '" . $bus_tipo_retencion["idcuenta_debe"] . "'
																and tabla = '" . $bus_tipo_retencion["tabla_debe"] . "'
																and afecta = 'debe'");
                $num_cuenta_retencion_debe = mysql_num_rows($sql_cuentas_contables_debe);
                if ($num_cuenta_retencion_debe == 0) {
                    //entro aqui porque la cuenta no existe afectando el debe por lo que no la voy a afectar por el debe
                    //la busco en el haber para restarla
                    $sql_cuentas_contables_haber = mysql_query("select * from cuentas_asiento_contable
															where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																and idcuenta = '" . $bus_tipo_retencion["idcuenta_debe"] . "'
																and tabla = '" . $bus_tipo_retencion["tabla_debe"] . "'
																and afecta = 'haber'");
                    $num_cuenta_retencion_haber = mysql_num_rows($sql_cuentas_contables_haber);
                    if ($num_cuenta_retencion_haber > 0) {
                        //valido que la cuenta a insertar no este registrada
                        $sql_cuentas_contables_haber_nueva = mysql_query("select * from cuentas_asiento_contable
															where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																and idcuenta = '" . $bus_tipo_retencion["idcuenta_haber"] . "'
																and tabla = '" . $bus_tipo_retencion["tabla_haber"] . "'
																and afecta = 'haber'");
                        $bus_cuenta_retencion_haber_nueva = mysql_fetch_array($sql_cuentas_contables_haber_nueva);
                        $num_cuenta_retencion_haber_nueva = mysql_num_rows($sql_cuentas_contables_haber_nueva);

                        $actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto - '" . $bus_relacion_retenciones["monto_retenido"] . "'
																	where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																			and idcuenta = '" . $bus_tipo_retencion["idcuenta_haber"] . "'
																			and tabla = '" . $bus_tipo_retencion["tabla_haber"] . "'
																			and afecta = 'haber'") or die("actualizando monto contable " . mysql_error());
                        $valido_cuenta_en_cero = mysql_query("delete from cuentas_asiento_contable
																	where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																			and idcuenta = '" . $bus_tipo_retencion["idcuenta_haber"] . "'
																			and tabla = '" . $bus_tipo_retencion["tabla_haber"] . "'
																			and afecta = 'haber'
																			and monto <= 0");
                    }
                }
            }

            //actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
            $sql_suma_retenido = mysql_query("select sum(monto) as monto_retenido from cuentas_asiento_contable
																	where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																		and idcuenta <> '" . $bus_tipo_retencion["idcuenta_debe"] . "'
																		and tabla <> '" . $bus_tipo_retencion["tabla_debe"] . "'
																		and afecta = 'haber'");
            $monto_retenido = mysql_fetch_array($sql_suma_retenido);
            //actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
            //echo ' RETENIDO desactivo '.$monto_retenido["monto_retenido"];
            $actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = 0
																		where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																			and idcuenta = '" . $bus_tipo_retencion["idcuenta_debe"] . "'
																			and tabla = '" . $bus_tipo_retencion["tabla_debe"] . "'
																			and afecta = 'haber'");
        }

    } else {

        $sql_insertar_orden_pago_retencion = mysql_query("insert into relacion_orden_pago_retencion (idretencion,
																							idorden_pago,
																							status,
																							fechayhora,
																							usuario)values(
																								'" . $id_retencion . "',
																								'" . $id_orden_pago . "',
																								'a',
																								'" . $fh . "',
																								'" . $login . "')");

        //echo " monto a pagar ".$monto_a_pagar;

        $sql_update_orden_pago = mysql_query("update orden_pago set exento = '" . $exento . "',
																	sub_total = '" . $subtotal . "',
																	impuesto = '" . $impuesto . "',
																	total = '" . $total . "',
																	total_retenido = '" . $monto_retenido . "',
																	total_a_pagar = '" . $monto_a_pagar . "' where idorden_pago = '" . $id_orden_pago . "'") or die(mysql_error());

        //ACTUALIZAR CUENTAS CONTABLES
        $sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = " . $id_orden_pago . "
																and tipo_movimiento = 'causado'");
        if (mysql_num_rows($sql_validar_asiento) > 0) {

            $bus_asiento_contable = mysql_fetch_array($sql_validar_asiento);

            $sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
														order by afecta");

            while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)) {
                if ($bus_cuentas_contables["afecta"] == 'debe') {
                    //echo " total ".$total;
                    $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = '" . $total . "'
																where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																and idcuenta = '" . $bus_tipo_documento["idcuenta_debe"] . "'
																and tabla = '" . $bus_tipo_documento["tabla_debe"] . "'");
                } else {
                    $actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = '" . $total . "'
																where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'
																and idcuenta = '" . $bus_tipo_documento["idcuenta_haber"] . "'
																and tabla = '" . $bus_tipo_documento["tabla_haber"] . "'");
                }
            }
        }
        //VALIDO SI TIENE RETENCIONES PARA DIFERENCIAR LA CUENTA CONTABLE

        $sql_retencion = mysql_query("select * from retenciones where idretenciones = '" . $id_retencion . "'");
        $bus_retencion = mysql_fetch_array($sql_retencion);
        $num_retencion = mysql_num_rows($sql_retencion);
        if ($num_retencion > 0) {
            //OBTENGO LA RELACION DE RETENCIONES PARA SABER DE EL TIPO DE RETENCION Y LAS CUENTAS QUE AFECTA
            $sql_relacion_retenciones = mysql_query("select * from relacion_retenciones where idretenciones = " . $bus_retencion["idretenciones"] . "");
            while ($bus_relacion_retenciones = mysql_fetch_array($sql_relacion_retenciones)) {
                $sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = " . $bus_relacion_retenciones["idtipo_retencion"] . "");
                $bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
                //valido la cuenta que afecta la retencion por el debe,
                //si es la misma que la que ya existe no la afecto,
                //si no es la misma la busco en el haber para ver si existe y restar el monto retenido
                $sql_cuentas_contables_debe = mysql_query("select * from cuentas_asiento_contable
														where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
															and idcuenta = '" . $bus_tipo_retencion["idcuenta_debe"] . "'
															and tabla = '" . $bus_tipo_retencion["tabla_debe"] . "'
															and afecta = 'debe'");
                $num_cuenta_retencion_debe = mysql_num_rows($sql_cuentas_contables_debe);

                if ($num_cuenta_retencion_debe == 0) {
                    //entro aqui porque la cuenta no existe afectando el debe por lo que no la voy a afectar por el debe
                    //la busco en el haber para restarla
                    $sql_cuentas_contables_haber = mysql_query("select * from cuentas_asiento_contable
														where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
															and idcuenta = '" . $bus_tipo_retencion["idcuenta_debe"] . "'
															and tabla = '" . $bus_tipo_retencion["tabla_debe"] . "'
															and afecta = 'haber'");
                    $num_cuenta_retencion_haber = mysql_num_rows($sql_cuentas_contables_haber);
                    if ($num_cuenta_retencion_haber > 0) {
                        //valido que la cuenta a insertar no este registrada
                        $sql_cuentas_contables_haber_nueva = mysql_query("select * from cuentas_asiento_contable
														where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
															and idcuenta = '" . $bus_tipo_retencion["idcuenta_haber"] . "'
															and tabla = '" . $bus_tipo_retencion["tabla_haber"] . "'
															and afecta = 'haber'");
                        $bus_cuenta_retencion_haber_nueva = mysql_fetch_array($sql_cuentas_contables_haber_nueva);
                        $num_cuenta_retencion_haber_nueva = mysql_num_rows($sql_cuentas_contables_haber_nueva);
                        if ($num_cuenta_retencion_haber_nueva == 0) {
                            //si la cuenta no existe la insertamos la cuenta
                            $ingreso_cuenta_haber_retencion = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																											tabla,
																											idcuenta,
																											monto,
																											afecta)values(
																											'" . $bus_asiento_contable["idasiento_contable"] . "',
																											'" . $bus_tipo_retencion["tabla_haber"] . "',
																											'" . $bus_tipo_retencion["idcuenta_haber"] . "',
																											'" . $bus_relacion_retenciones["monto_retenido"] . "',
																											'haber'
																											)");
                        } else {
                            //si la cuenta existe le sumo el monto retenido
                            $actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto + '" . $bus_relacion_retenciones["monto_retenido"] . "'
																where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																		and idcuenta = '" . $bus_tipo_retencion["idcuenta_haber"] . "'
																		and tabla = '" . $bus_tipo_retencion["tabla_haber"] . "'
																		and afecta = 'haber'") or die("actualizando monto contable " . mysql_error());
                        }
                    }
                }
            }
            //actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
            $sql_suma_retenido = mysql_query("select sum(monto) as monto_retenido from cuentas_asiento_contable
																where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																	and idcuenta <> '" . $bus_tipo_retencion["idcuenta_debe"] . "'
																	and tabla <> '" . $bus_tipo_retencion["tabla_debe"] . "'
																	and afecta = 'haber'");
            $monto_retenido = mysql_fetch_array($sql_suma_retenido);
            //actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
            //echo 'RETENIDO '.$monto_retenido["monto_retenido"];
            $actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto - '" . $monto_retenido["monto_retenido"] . "'
																	where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																		and idcuenta = '" . $bus_tipo_retencion["idcuenta_debe"] . "'
																		and tabla = '" . $bus_tipo_retencion["tabla_debe"] . "'
																		and afecta = 'haber'");
        }
    }
}

if ($ejecutar == "limpiarDatos") {
    $sql_relacion_pago_compromiso      = mysql_query("delete from relacion_pago_compromisos where idorden_pago = '" . $id_orden_pago . "'") or die(mysql_error());
    $sql_relacion_orden_pago_retencion = mysql_query("delete from relacion_orden_pago_retencion where idorden_pago = '" . $id_orden_pago . "'") or die(mysql_error());
    $sql_partidas_orden_pago           = mysql_query("delete from partidas_orden_pago where idorden_pago = '" . $id_orden_pago . "'") or die(mysql_error());
    $sql_update_orden_pago             = mysql_query("update orden_pago set exento = '0',
																	sub_total = '0',
																	impuesto = '0',
																	total = '0',
																	total_retenido = '0',
																	total_a_pagar = '0' where idorden_pago = '" . $id_orden_pago . "'") or die(mysql_error());
}

if ($ejecutar == "actualizarMontosFinales") {
    $sql_update = mysql_query("update orden_pago set exento = '" . $exento . "',
													sub_total = '" . $subtotal . "',
													impuesto = '" . $impuesto . "',
													total = '" . $total . "',
													total_a_pagar = '" . $total_a_pagar . "'
													where idorden_pago = '" . $id_orden_pago . "'");
}

if ($ejecutar == "actualizarJustificacion") {
    $sql_justificacion = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '" . $idorden_compra . "'");
    $bus_justificacion = mysql_fetch_array($sql_justificacion);
    $sql_update        = mysql_query("update orden_pago set justificacion = '" . $bus_justificacion["justificacion"] . "'
														where idorden_pago = '" . $idorden_pago . "'");
}

if ($ejecutar == "actualizarDatosFactura") {

    $sql_retenciones = mysql_query("select * from retenciones where idretenciones = '" . $idretenciones . "'");
    $bus_retenciones = mysql_fetch_array($sql_retenciones);

    $sql_proveedor = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio= '" . $bus_retenciones["iddocumento"] . "'") or die(mysql_error());
    $bus_proveedor = mysql_fetch_array($sql_proveedor);

    $sql_existe = mysql_query("select * from retenciones
    								INNER JOIN orden_compra_servicio on (retenciones.iddocumento = orden_compra_servicio.idorden_compra_servicio)
    								INNER JOIN beneficiarios on (orden_compra_servicio.idbeneficiarios = beneficiarios.idbeneficiarios)
  								where retenciones.numero_factura = '" . $numero_factura . "'
    									and beneficiarios.idbeneficiarios = '" . $bus_proveedor["idbeneficiarios"] . "'") or die(mysql_error());

    $num_existe_factura = mysql_num_rows($sql_existe);

    if ($num_existe_factura > 0) {
        $sql_datos_factura = mysql_query("update orden_compra_servicio set fecha_factura = '" . $fecha_factura . "',
																				nro_control = '" . $numero_control . "'
																		where idorden_compra_servicio = '" . $bus_retenciones["iddocumento"] . "'");
        $sql_datos_factura = mysql_query("update retenciones set fecha_factura = '" . $fecha_factura . "',
																	numero_control = '" . $numero_control . "'
																		where idretenciones = '" . $idretenciones . "'");

    } else {

        $sql_datos_factura = mysql_query("update orden_compra_servicio set nro_factura = '" . $numero_factura . "',
																		fecha_factura = '" . $fecha_factura . "',
																		nro_control = '" . $numero_control . "'
																		where idorden_compra_servicio = '" . $bus_retenciones["iddocumento"] . "'");
        $sql_datos_factura = mysql_query("update retenciones set numero_factura = '" . $numero_factura . "',
																		fecha_factura = '" . $fecha_factura . "',
																		numero_control = '" . $numero_control . "'
																		where idretenciones = '" . $idretenciones . "'");

    }

}

//*******************************************************************************************************************************************
//********************************************* LISTA DE CUENTAS CONTABLES  *****************************************************************
//*******************************************************************************************************************************************
if ($ejecutar == "mostrarCuentasContables") {

/*$sql_asiento_contable = mysql_query("select * from asiento_contable where iddocumento = '".$id_orden_compra."'
and tipo_movimiento = 'compromiso'")or die("aqui asiento ".mysql_error());*/

    ?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
			<thead>
			<tr>
				<td width="10%" class="Browse"><div align="center">C&oacute;digo</div></td>
				<td width="62%" class="Browse"><div align="center">Descripci&oacute;n</div></td>
				<td width="12%" class="Browse"><div align="center">Debe</div></td>
				<td width="12%" class="Browse"><div align="center">Haber</div></td>
			</tr>
			</thead>
<?
    $sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = " . $id_orden_pago . "
														and tipo_movimiento = 'causado'") or die(" aqui 1 " . mysql_error());
    if (mysql_num_rows($sql_validar_asiento) > 0) {
        while ($bus_asiento_contable = mysql_fetch_array($sql_validar_asiento)) {

            $sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
														order by afecta") or die("aqui cuenta " . mysql_error());

            $num_cuentas_contables = mysql_num_rows($sql_cuentas_contables) or die(" num " . mysql_error());
            //echo $num_cuentas_contables;
            if ($num_cuentas_contables != 0) {

                if ($bus_asiento_contable["estado"] != 'anulado') {
                    ?>

			  <?
                    //echo $bus_asiento_contable["idasiento_contable"];
                    $sql_cuentas_contables2 = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
														order by afecta") or die("aqui cuenta " . mysql_error());

                    while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables2)) {
                        ?>
				<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<?
                        $idcampo = "id" . $bus_cuentas_contables["tabla"];
                        //echo $idcampo;
                        $sql_cuentas = mysql_query("select * from " . $bus_cuentas_contables["tabla"] . "
																	where " . $idcampo . " = '" . $bus_cuentas_contables["idcuenta"] . "'") or die(" tablas " . mysql_error());
                        $bus_cuenta = mysql_fetch_array($sql_cuentas);

                        if ($bus_cuentas_contables["afecta"] == 'debe') {
                            ?>
					<td width="10%" align="left" class='Browse'><?=$bus_cuenta["codigo"]?></td>
					<td width="62%" align="left" class='Browse'><?=$bus_cuenta["denominacion"]?></td>
					<td width="12%" align="right" class='Browse'><?=number_format($bus_cuentas_contables["monto"], 2, ',', '.')?></td>
					<td width="12%" align="right" class='Browse'>&nbsp;</td>
				<?
                        } else {
                            ?>
					<td width="10%" align="left" class='Browse'><?=$bus_cuenta["codigo"]?></td>
					<td width="62%" align="left" class='Browse'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$bus_cuenta["denominacion"]?></td>
					<td width="12%" align="right" class='Browse'>&nbsp;</td>
					<td width="12%" align="right" class='Browse'><?=number_format($bus_cuentas_contables["monto"], 2, ',', '.')?></td>
				<?
                        }
                        ?>
			   </tr>
				<?
                    }

                } else {
                    //MUESTRALAS CUENTAS QUE REVERSAN EL ASIENTO POR LA ANULACION
                    ?>
			<tr bgcolor="#FFCC33" onMouseOver="setRowColor(this, 0, 'over', '#FFCC33', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFCC33', '#EAFFEA', '#FFFFAA')">
				<td align="left" class='Browse' colspan="4"><strong>Fecha de Reverso: <?=$bus_asiento_contable["fecha_contable"]?></strong></td>
            </tr>

			<?
                    while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)) {
                        ?>
				<tr bgcolor="#FFFF99" onMouseOver="setRowColor(this, 0, 'over', '#FFFF99', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF99', '#EAFFEA', '#FFFFAA')">
				<?
                        $idcampo     = "id" . $bus_cuentas_contables["tabla"];
                        $sql_cuentas = mysql_query("select * from " . $bus_cuentas_contables["tabla"] . "
																	where " . $idcampo . " = '" . $bus_cuentas_contables["idcuenta"] . "'") or die(mysql_error());
                        $bus_cuenta = mysql_fetch_array($sql_cuentas);

                        if ($bus_cuentas_contables["afecta"] == 'debe') {
                            ?>
					<td width="10%" align="left" class='Browse'><?=$bus_cuenta["codigo"]?></td>
					<td width="62%" align="left" class='Browse'><?=$bus_cuenta["denominacion"]?></td>
					<td width="12%" align="right" class='Browse'><?=number_format($bus_cuentas_contables["monto"], 2, ',', '.')?></td>
					<td width="12%" align="right" class='Browse'>&nbsp;</td>
				<?
                        } else {
                            ?>
					<td width="10%" align="left" class='Browse'><?=$bus_cuenta["codigo"]?></td>
					<td width="62%" align="left" class='Browse'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$bus_cuenta["denominacion"]?></td>
					<td width="12%" align="right" class='Browse'>&nbsp;</td>
					<td width="12%" align="right" class='Browse'><?=number_format($bus_cuentas_contables["monto"], 2, ',', '.')?></td>
				<?
                        }
                        ?>
			   </tr>
				<?
                    }

                }
            } else {
                echo "No se han Registrado Movimientos Contables para este documento";
            }
        }
    }
    ?>
</table>
<?
}

?>