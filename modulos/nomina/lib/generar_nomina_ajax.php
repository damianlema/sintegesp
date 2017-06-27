<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
include("funciones_conceptos.php");
Conectarse();
extract($_POST);


if($ejecutar == 'seleccionarPeriodo'){
	$sql_consultar_periodo = mysql_query("select * from
										 			periodos_nomina pn,
													rango_periodo_nomina rpn
										 			where
													pn.idtipo_nomina = '".$idtipo_nomina."'
													and pn.periodo_activo = 'si'
													and rpn.idperiodo_nomina = pn.idperiodos_nomina")or die(mysql_error());

//
	?>
	<input name="idperiodo" type="hidden" id="idperiodo">
    <div>
    <div style="cursor:pointer; border:#666 solid 1px; width:200px; overflow:auto" onclick="document.getElementById('lista').style.display = 'block'" align="center" id="">
    <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
    <td id="div_seleccione">.:: Seleccione ::.</td>
    <td align="right"><img src="imagenes/flecha_abajo.jpg"></td>
    </tr>
    </table>

    </div>
    	<div style="display:none; border:#666 solid 1px; position:absolute; width:195px; height:150px; overflow:auto; padding:3px; background-color:#FFF" id="lista" onmouseover="document.getElementById('lista').style.display = 'block'" onmouseout="document.getElementById('lista').style.display = 'none'">
        	<ul>
            <?
				while($bus_consultar_periodo= mysql_fetch_array($sql_consultar_periodo)){
					$num_generar_nomina = 0;
					$sql_generar_nomina = mysql_query("select * from generar_nomina where idperiodo = '".$bus_consultar_periodo["idrango_periodo_nomina"]."' and estado = 'procesado' and idtipo_nomina = '".$idtipo_nomina."'")or die(mysql_error());

					$num_generar_nomina = mysql_num_rows($sql_generar_nomina);
					if($num_generar_nomina != 0){
						$color = "#FF0000";
						$ancho = "font-weight:bold";
					}else{
						$color = "#000000";
						$ancho = "font-weight:normal";
					}
						$desde = explode(" ", $bus_consultar_periodo["desde"]);
						$hasta = explode(" ", $bus_consultar_periodo["hasta"]);
					?>
					<li id="<?=$bus_consultar_periodo["idrango_periodo_nomina"]?>"
                    <?
                    if($color != "#FF0000"){
					?>
                    onclick="document.getElementById('div_seleccione').innerHTML = '<?=$desde[0]." - ".$hasta[0]?>', document.getElementById('idperiodo').value = '<?=$bus_consultar_periodo["idrango_periodo_nomina"]?>', document.getElementById('lista').style.display = 'none'"
                    <?
					}
					?>
                    style="cursor:pointer; color:<?=$color?>" onmouseover="this.style.backgroundColor = '#CCC'" onmouseout="this.style.backgroundColor = '#FFF'">
						<?
						echo $desde[0]." - ".$hasta[0];
						?>
					</li>
					<?

				}
			?>

            </ul>
        </div>
    </div>
    <?
}







if($ejecutar == "ingresarDatosBasicos"){
	$sql_consulta = mysql_query("select * from generar_nomina where idtipo_nomina = '".$idtipo_nomina."' and idperiodo = '".$idperiodo."' and estado = 'Procesado'");
	$num_consulta = mysql_num_rows($sql_consulta);
	if($num_consulta == 0){

		$sql_con = mysql_query("select * from generar_nomina
													where idtipo_nomina = '".$idtipo_nomina."'
															and idperiodo = '".$idperiodo."'
															and (estado = 'Pre Nomina' or estado = 'Elaboracion')")or die(mysql_error());
		while($bus_con = mysql_fetch_array($sql_con)){
			$sql_eliminar = mysql_query("delete from generar_nomina
											where idgenerar_nomina = '".$bus_con["idgenerar_nomina"]."'");
			$sql_relacion = mysql_query("delete from relacion_generar_nomina
													where
													idgenerar_nomina = '".$bus_con["idgenerar_nomina"]."'");
		}



	$sql_ingresr = mysql_query("insert into generar_nomina(descripcion,
														   idtipo_nomina,
														   idperiodo,
														   estado,
														   fecha_elaboracion,
														   status,
														   usuario,
														   fechayhora,
														   idbeneficiarios,
														   idfuente_financiamiento,
														   anio,
														   idtipo_presupuesto,
														   idcategoria_programatica
														   			)VALUES(
																	'".$justificacion."',
																	'".$idtipo_nomina."',
																	'".$idperiodo."',
																	'Elaboracion',
																	'".date("Y-m-d")."',
																	'a',
																	'".$login."',
																	'".$fh."',
																	'".$id_beneficiarios."',
																	'".$idfuente_financiamiento."',
																	'".$anio."',
																	'".$idtipo_presupuesto."',
																	'".$idcategoria_programatica."')");
	echo mysql_insert_id()."|.|".date("Y-m-d");
	}else{
		echo "existe";
	}
}





if($ejecutar == "consultarTrabajadores"){
	if($estado == "procesado"){
		$sql_consulta = mysql_query("select tr.nombres,
										tr.apellidos,
										tr.cedula,
										tr.idtrabajador,
										tr.nro_ficha
											FROM
										trabajador tr,
										relacion_generar_nomina rgn
											WHERE
										rgn.idgenerar_nomina = '".$idgenerar_nomina."'
										and rgn.idtrabajador =  tr.idtrabajador
										and (tr.nombres like '%".$buscar."%'
											 or tr.apellidos like '%".$buscar."%'
											 or tr.cedula like '%".$buscar."%')
										group by tr.idtrabajador
										order by tr.cedula")or die(mysql_error());
	}else{
		$sql_consulta = mysql_query("select tr.nombres,
										tr.apellidos,
										tr.cedula,
										tr.idtrabajador,
										tr.nro_ficha

											FROM
										trabajador tr,
										relacion_tipo_nomina_trabajador rtnt
											WHERE
										rtnt.idtipo_nomina = '".$idtipo_nomina."'
										and rtnt.activa = '1'
										and tr.status = 'a'
										and tr.activo_nomina = 'si'
										and rtnt.idtrabajador =  tr.idtrabajador
										and (tr.nombres like '%".$buscar."%'
											 or tr.apellidos like '%".$buscar."%'
											 or tr.cedula like '%".$buscar."%')
										group by tr.idtrabajador
										order by tr.cedula")or die(mysql_error());
	}


	?>

    <table>
            	<tr>
                <td>Buscar</td>
                <td><input type="text" name="campo_buscar_trabajador" id="campo_buscar_trabajador" value="<?=$buscar?>"></td>
                <td><input type="button" value="Buscar" id="boton_buscar" name="boton_buscar" class="button" onclick="consultarTrabajadores('Elaboracion', document.getElementById('campo_buscar_trabajador').value)"></td>
                </tr>
            </table>
            <br />

	<table class="Browse" cellpadding="0" cellspacing="0" width="98%" align="center">
        <thead>
        <tr>
    		<td width="5%" align="center" class="Browse">Nro</td>
            <td width="10%" align="center" class="Browse">Nro Ficha</td>
            <td width="10%" align="center" class="Browse">Cedula</td>
            <td width="42%" align="center" class="Browse">Nombre</td>
            <td width="42%" align="center" class="Browse">Apellido</td>
   		</tr>
        </thead>
        <?
		$i=1;
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="pdf.location.href='lib/reportes/nomina/reportes.php?nombre=payroll_trabajadores&idtrabajador=<?=$bus_consulta['idtrabajador']?>&nomina='+document.getElementById('idtipo_nomina').value+'&periodo='+document.getElementById('idperiodo').value+'&idgenerar_nomina='+document.getElementById('idgenerar_nomina').value; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block';">
                <td align='center' class='Browse'><?=$i?></td>
                <td align='center' class='Browse'><?=$bus_consulta["nro_ficha"]?></td>
                <td align='center' class='Browse'><?=number_format($bus_consulta["cedula"],0,",",".")?></td>
                <td align='left' class='Browse'><?=$bus_consulta["nombres"]?></td>
                <td align='left' class='Browse'><?=$bus_consulta["apellidos"]?></td>
   			</tr>
			<?
			$i++;
		}
		?>

    </table>

	<?
}










if($ejecutar =="anularNomina"){
	$sql_anular = mysql_query("update generar_nomina set estado = 'Anulado' where idgenerar_nomina = '".$idgenerar_nomina."'");
	$sql_generar_nom = mysql_query("select * from generar_nomina where idgenerar_nomina = '".$idgenerar_nomina."'")or die("AQUI: ".mysql_error());
	$bus_generar_nom = mysql_fetch_array($sql_generar_nom);


	$sql_relacion_generar_nomina = mysql_query("select * from relacion_generar_nomina
								   								where
															idgenerar_nomina = '".$idgenerar_nomina."'")or die("relacion_generar_nomina ".mysql_error());
	while($bus_relacion_generar_nomina = mysql_fetch_array($sql_relacion_generar_nomina)){
			$sql_consultar_concepto = mysql_query("select * from conceptos_nomina
												  	where
												  idconceptos_nomina = '".$bus_relacion_generar_nomina["idconcepto"]."'
												  and aplica_prestaciones = 'si'")or die("conceptos_nomina ".mysql_error());
            $columna_prestaciones = mysql_fetch_array($sql_consultar_concepto);
			$num_consultar_concepto = mysql_num_rows($sql_consultar_concepto);


			if($num_consultar_concepto > 0) {
                $sql_generar_nomina = mysql_query("select rpn.desde,
																rpn.hasta
																	FROM
																generar_nomina gn,
																periodos_nomina pn,
																rango_periodo_nomina rpn
																	WHERE
																gn.idgenerar_nomina = '" . $idgenerar_nomina . "'
																and rpn.idrango_periodo_nomina = gn.idperiodo
																group by desde") or die("varias tablas: " . mysql_error());
                $bus_generar_nomina = mysql_fetch_array($sql_generar_nomina);
                list($anioPrestaciones, $mesPrestaciones, $diaPrestaciones) = explode("-", $bus_generar_nomina["desde"]);

                if ($columna_prestaciones["columna_prestaciones"] == 'sueldo') {
                    $sql_restar = mysql_query("update tabla_prestaciones set
										  					sueldo = sueldo - '" . $bus_relacion_generar_nomina["total"] . "'
																where
															anio = '" . $anioPrestaciones . "'
															and mes = '" . $mesPrestaciones . "'
															and idtrabajador = '" . $bus_relacion_generar_nomina["idtrabajador"] . "'") or die("tabla_prestaciones" . mysql_error());
                }elseif ($columna_prestaciones["columna_prestaciones"] == 'oc'){
                    $sql_restar = mysql_query("update tabla_prestaciones set
										  					otros_complementos = otros_complementos - '" . $bus_relacion_generar_nomina["total"] . "'
																where
															anio = '" . $anioPrestaciones . "'
															and mes = '" . $mesPrestaciones . "'
															and idtrabajador = '" . $bus_relacion_generar_nomina["idtrabajador"] . "'") or die("tabla_prestaciones" . mysql_error());
                }elseif ($columna_prestaciones["columna_prestaciones"] == 'bv'){
                    $sql_restar = mysql_query("update tabla_prestaciones set
										  					bono_vacacional = bono_vacacional - '" . $bus_relacion_generar_nomina["total"] . "'
																where
															anio = '" . $anioPrestaciones . "'
															and mes = '" . $mesPrestaciones . "'
															and idtrabajador = '" . $bus_relacion_generar_nomina["idtrabajador"] . "'") or die("tabla_prestaciones" . mysql_error());
                }elseif ($columna_prestaciones["columna_prestaciones"] == 'bfa'){
                    $sql_restar = mysql_query("update tabla_prestaciones set
										  					bono_fin_anio = bono_fin_anio - '" . $bus_relacion_generar_nomina["total"] . "'
																where
															anio = '" . $anioPrestaciones . "'
															and mes = '" . $mesPrestaciones . "'
															and idtrabajador = '" . $bus_relacion_generar_nomina["idtrabajador"] . "'") or die("tabla_prestaciones" . mysql_error());
                }
				$sql_consulta_tabla_prestaciones = mysql_query("select * from tabla_prestaciones where
															anio = '".$anioPrestaciones."'
															and mes = '".$mesPrestaciones."'
															and idtrabajador = '".$bus_relacion_generar_nomina["idtrabajador"]."'");
				$bus_consulta_tabla_prestaciones = mysql_fetch_array($sql_consulta_tabla_prestaciones);
				if($bus_consulta_tabla_prestaciones["sueldo"] <= 0){
					$sql_eliminar_tabla_prestaciones = mysql_query("delete from tabla_prestaciones where
															anio = '".$anioPrestaciones."'
															and mes = '".$mesPrestaciones."'
															and idtrabajador = '".$bus_relacion_generar_nomina["idtrabajador"]."'")	;
				}


			}
		}

	registra_transaccion('Se anulo la nomina ('.$idgenerar_nomina.')',$login,$fh,$pc,'nomina',$conexion_db);
	anularOrdenCompra($bus_generar_nom["idorden_compra_servicio"]);
	anularOrdenCompraAportes($bus_generar_nom["idorden_compra_servicio_aporte"]);
}


//ANULO LA CERTIFICACION DE NOMINA
function anularOrdenCompra($id_orden_compra){

	$sql_configuracion = mysql_query("select fecha_cierre from configuracion");
	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	if(date("Y-m-d") > $bus_configuracion["fecha_cierre"]){
		$fecha_anulacion = $bus_configuracion["fecha_cierre"];
	}else{
		$fecha_anulacion = date("Y-m-d");
	}


	$sql_orden = mysql_query("update orden_compra_servicio set estado = 'anulado',
																fecha_anulacion = '".$fecha_anulacion."'
														where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
	$sql_actualizar_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
		while($bus_actualizar_partidas = mysql_fetch_array($sql_actualizar_partidas)){
			$sql_maestro = mysql_query("update maestro_presupuesto set
												total_compromisos = total_compromisos-".$bus_actualizar_partidas["monto"]."
												where idRegistro = ".$bus_actualizar_partidas["idmaestro_presupuesto"]."")or die(mysql_error());


					$sql_consulta_ordinal = mysql_query("select * from ordinal where codigo = '0000'")or die("ERROR CONSULTANDO EL ORDINAL NO APLICA".mysql_error());
					$bus_consulta_ordinal = mysql_fetch_array($sql_consulta_ordinal);

					$sql_consultar_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '".$bus_actualizar_partidas["idmaestro_presupuesto"]."' and idordinal != '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO EL MAESTRO 1:".mysql_error());
					$num_consulta_maestro = mysql_num_rows($sql_consultar_maestro);
					if($num_consulta_maestro != 0){
						$bus_consultar_maestro= mysql_fetch_array($sql_consultar_maestro);
						$sql_sub_espe = mysql_query("select * from maestro_presupuesto where
							idcategoria_programatica= '".$bus_consultar_maestro["idcategoria_programatica"]."'
						and idtipo_presupuesto = '".$bus_consultar_maestro["idtipo_presupuesto"]."'
						and idfuente_financiamiento = '".$bus_consultar_maestro["idfuente_financiamiento"]."'
						and idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."'
						and idordinal = '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO SUB ESPECIFICA".mysql_error());
						$num_sub_espe =mysql_num_rows($sql_sub_espe);
						if($num_sub_espe != 0){
							$bus_sub_epe = mysql_fetch_array($sql_sub_espe);
							$sql_maestro = mysql_query("update maestro_presupuesto set
															total_compromisos = total_compromisos - ".$bus_actualizar_partidas["monto"]."
															where idRegistro = '".$bus_sub_epe["idmaestro_presupuesto"]."'")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 2: ".mysql_error());

						}

						$sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."' and sub_especifica != '00'")or die("ERROR CONSULTANDO EL CLASIFICADOR ".mysql_error());
						$num_clasificador = mysql_num_rows($sql_clasificador);
						if($num_clasificador > 0){
							$bus_clasificador = mysql_fetch_array($sql_clasificador);
							$sql_consulta_clasificador = mysql_query("select * from clasificador_presupuestario where partida = '".$bus_clasificador["partida"]."'
							and generica = '".$bus_clasificador["generica"]."'
							and especifica ='".$bus_clasificador["especifica"]."'
							and sub_especifica= '00'")or die("ERROR CONSULTANDO EL CLASIFICADOR 2:".mysql_error());
							$bus_consulta_clasificador= mysql_fetch_array($sql_consulta_clasificador);
							$sql_id_maestro= mysql_query("select * from maestro_presupuesto where
							idcategoria_programatica= '".$bus_consultar_maestro["idcategoria_programatica"]."'
						and idtipo_presupuesto = '".$bus_consultar_maestro["idtipo_presupuesto"]."'
						and idfuente_financiamiento = '".$bus_consultar_maestro["idfuente_financiamiento"]."'
						and idclasificador_presupuestario = '".$bus_consulta_clasificador["idclasificador_presupuestario"]."'
						and idordinal = '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO EL MAESTRO 2:".mysql_error());
							$bus_id_maestro = mysql_fetch_array($sql_id_maestro);


							$sql_maestro = mysql_query("update maestro_presupuesto set
															total_compromisos = total_compromisos - ".$bus_actualizar_partidas["monto"]."
															where idRegistro = ".$bus_id_maestro["idmaestro_presupuesto"]."")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 3: ".mysql_error());

						}

					}

		}

	$sql_relacion_requisicion = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."");
	while($bus_relacion_requisicion = mysql_fetch_array($sql_relacion_requisicion)){
		$sql_insert_relacion_compras = mysql_query("update requisicion set estado = 'procesado' where idrequisicion = '".$bus_relacion_requisicion["idrequisicion"]."'");

	}

	$sql_relacion_solicitud = mysql_query("select * from relacion_compra_solicitud_cotizacion where idorden_compra = ".$id_orden_compra."");
	while($bus_relacion_solicitud = mysql_fetch_array($sql_relacion_solicitud)){
		$sql_insert_relacion_compras = mysql_query("update solicitud_cotizacion set estado = 'procesado' where idsolicitud_cotizacion = '".$bus_relacion_solicitud["idsolicitud_cotizacion"]."'");

	}

	$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_compra."
														and tipo_movimiento = 'compromiso'");
	if (mysql_num_rows($sql_validar_asiento) > 0){
		$sql_asiento_contable = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_compra." and tipo_movimiento='compromiso'")or die("aqui asiento ".mysql_error());
		$bus_asiento_contable = mysql_fetch_array($sql_asiento_contable)or die("aqui asiento ".mysql_error());
		$sql_contable = mysql_query("insert into asiento_contable (idfuente_financiamiento,
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
																			'".$bus_asiento_contable["idfuente_financiamiento"]."',
																			'".'ANULACION DE ASIENTO: '.$bus_asiento_contable["detalle"]."',
																			'".date("Y-m-d")."',
																			'compromiso',
																			'".$id_orden_compra."',
																			'anulado',
																			'a',
																			'".$login."',
																			'".date("Y-m-d H:i:s")."',
																			'2')")or die("aqui insert ".mysql_error());

		$sql_actualizar =mysql_query("update asiento_contable set reversado = 'si'
										where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'");
		if($sql_contable){
			$idasiento_contable = mysql_insert_id();
			$sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'");

			while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)){
				if ($bus_cuentas_contables["afecta"] == 'debe'){ $afecta = 'haber'; }else{ $afecta = 'debe'; }
				$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																tabla,
																idcuenta,
																afecta,
																monto
																	)values(
																			'".$idasiento_contable."',
																			'".$bus_cuentas_contables["tabla"]."',
																			'".$bus_cuentas_contables["idcuenta"]."',
																			'".$afecta."',
																			'".$bus_cuentas_contables["monto"]."')");
			}

		}

	}

	if($sql_orden){
		echo "exito";
		registra_transaccion("Anular orden Compra (".$id_orden_compra.")",$login,$fh,$pc,'orden_compra_servicios');
	}else{
		registra_transaccion("Anular Orden Compra ERROR (".$id_orden_compra.")",$login,$fh,$pc,'orden_compra_servicios');
		echo "fallo";
	}




}

//ANULO LA CERTIFICACION DE APORTES
function anularOrdenCompraAportes($idorden_compra_aporte){
	$idorden_compra = $idorden_compra_aporte;
		if($id_orden_compra != '' or $id_orden_compra != 0) {
		$sql_configuracion = mysql_query("select fecha_cierre from configuracion");
		$bus_configuracion = mysql_fetch_array($sql_configuracion);
		if(date("Y-m-d") > $bus_configuracion["fecha_cierre"]){
			$fecha_anulacion = $bus_configuracion["fecha_cierre"];
		}else{
			$fecha_anulacion = date("Y-m-d");
		}


		$sql_orden = mysql_query("update orden_compra_servicio set estado = 'anulado',
																	fecha_anulacion = '".$fecha_anulacion."'
															where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
		$sql_actualizar_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
		while($bus_actualizar_partidas = mysql_fetch_array($sql_actualizar_partidas)){
			$sql_maestro = mysql_query("update maestro_presupuesto set
												total_compromisos = total_compromisos-".$bus_actualizar_partidas["monto"]."
												where idRegistro = ".$bus_actualizar_partidas["idmaestro_presupuesto"]."")or die(mysql_error());


					$sql_consulta_ordinal = mysql_query("select * from ordinal where codigo = '0000'")or die("ERROR CONSULTANDO EL ORDINAL NO APLICA".mysql_error());
					$bus_consulta_ordinal = mysql_fetch_array($sql_consulta_ordinal);

					$sql_consultar_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '".$bus_actualizar_partidas["idmaestro_presupuesto"]."' and idordinal != '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO EL MAESTRO 1:".mysql_error());
					$num_consulta_maestro = mysql_num_rows($sql_consultar_maestro);
					if($num_consulta_maestro != 0){
						$bus_consultar_maestro= mysql_fetch_array($sql_consultar_maestro);
						$sql_sub_espe = mysql_query("select * from maestro_presupuesto where
							idcategoria_programatica= '".$bus_consultar_maestro["idcategoria_programatica"]."'
						and idtipo_presupuesto = '".$bus_consultar_maestro["idtipo_presupuesto"]."'
						and idfuente_financiamiento = '".$bus_consultar_maestro["idfuente_financiamiento"]."'
						and idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."'
						and idordinal = '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO SUB ESPECIFICA".mysql_error());
						$num_sub_espe =mysql_num_rows($sql_sub_espe);
						if($num_sub_espe != 0){
							$bus_sub_epe = mysql_fetch_array($sql_sub_espe);
							$sql_maestro = mysql_query("update maestro_presupuesto set
															total_compromisos = total_compromisos - ".$bus_actualizar_partidas["monto"]."
															where idRegistro = '".$bus_sub_epe["idmaestro_presupuesto"]."'")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 2: ".mysql_error());

						}

						$sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."' and sub_especifica != '00'")or die("ERROR CONSULTANDO EL CLASIFICADOR ".mysql_error());
						$num_clasificador = mysql_num_rows($sql_clasificador);
						if($num_clasificador > 0){
							$bus_clasificador = mysql_fetch_array($sql_clasificador);
							$sql_consulta_clasificador = mysql_query("select * from clasificador_presupuestario where partida = '".$bus_clasificador["partida"]."'
							and generica = '".$bus_clasificador["generica"]."'
							and especifica ='".$bus_clasificador["especifica"]."'
							and sub_especifica= '00'")or die("ERROR CONSULTANDO EL CLASIFICADOR 2:".mysql_error());
							$bus_consulta_clasificador= mysql_fetch_array($sql_consulta_clasificador);
							$sql_id_maestro= mysql_query("select * from maestro_presupuesto where
							idcategoria_programatica= '".$bus_consultar_maestro["idcategoria_programatica"]."'
						and idtipo_presupuesto = '".$bus_consultar_maestro["idtipo_presupuesto"]."'
						and idfuente_financiamiento = '".$bus_consultar_maestro["idfuente_financiamiento"]."'
						and idclasificador_presupuestario = '".$bus_consulta_clasificador["idclasificador_presupuestario"]."'
						and idordinal = '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO EL MAESTRO 2:".mysql_error());
							$bus_id_maestro = mysql_fetch_array($sql_id_maestro);


							$sql_maestro = mysql_query("update maestro_presupuesto set
															total_compromisos = total_compromisos - ".$bus_actualizar_partidas["monto"]."
															where idRegistro = ".$bus_id_maestro["idmaestro_presupuesto"]."")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 3: ".mysql_error());

						}

					}

		}

		$sql_relacion_requisicion = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."");
		while($bus_relacion_requisicion = mysql_fetch_array($sql_relacion_requisicion)){
			$sql_insert_relacion_compras = mysql_query("update requisicion set estado = 'procesado' where idrequisicion = '".$bus_relacion_requisicion["idrequisicion"]."'");

		}

		$sql_relacion_solicitud = mysql_query("select * from relacion_compra_solicitud_cotizacion where idorden_compra = ".$id_orden_compra."");
		while($bus_relacion_solicitud = mysql_fetch_array($sql_relacion_solicitud)){
			$sql_insert_relacion_compras = mysql_query("update solicitud_cotizacion set estado = 'procesado' where idsolicitud_cotizacion = '".$bus_relacion_solicitud["idsolicitud_cotizacion"]."'");

		}

		$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_compra."
															and tipo_movimiento = 'compromiso'");
		if (mysql_num_rows($sql_validar_asiento) > 0){
			$sql_asiento_contable = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_compra." and tipo_movimiento='compromiso'")or die("aqui asiento ".mysql_error());
			$bus_asiento_contable = mysql_fetch_array($sql_asiento_contable)or die("aqui asiento ".mysql_error());
			$sql_contable = mysql_query("insert into asiento_contable (idfuente_financiamiento,
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
																				'".$bus_asiento_contable["idfuente_financiamiento"]."',
																				'".'ANULACION DE ASIENTO: '.$bus_asiento_contable["detalle"]."',
																				'".date("Y-m-d")."',
																				'compromiso',
																				'".$id_orden_compra."',
																				'anulado',
																				'a',
																				'".$login."',
																				'".date("Y-m-d H:i:s")."',
																				'2')")or die("aqui insert ".mysql_error());

			$sql_actualizar =mysql_query("update asiento_contable set reversado = 'si'
											where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'");
			if($sql_contable){
				$idasiento_contable = mysql_insert_id();
				$sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'");

				while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)){
					if ($bus_cuentas_contables["afecta"] == 'debe'){ $afecta = 'haber'; }else{ $afecta = 'debe'; }
					$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla"]."',
																				'".$bus_cuentas_contables["idcuenta"]."',
																				'".$afecta."',
																				'".$bus_cuentas_contables["monto"]."')");
				}

			}

		}

		if($sql_orden){
			echo "exito";
			registra_transaccion("Anular orden Compra Aportes(".$id_orden_compra.")",$login,$fh,$pc,'orden_compra_servicios');
		}else{
			registra_transaccion("Anular Orden Compra Aportes ERROR (".$id_orden_compra.")",$login,$fh,$pc,'orden_compra_servicios');
			echo "fallo";
		}

	}
}



function call_concepto($idconcepto, $idtrabajador, $destino, $idgenerar_nomina, $idtipo_nomina, $idperiodo, $desagregar_concepto, $factor){
	//echo "FORMULA";
	$sql_relacion_formula= mysql_query("SELECT * FROM
												relacion_formula_conceptos_nomina
													WHERE
												idconcepto_nomina ='".$idconcepto."'
												and destino = '".$destino."'
												order by orden")or die(mysql_error());
	while($bus_relacion_formula = mysql_fetch_array($sql_relacion_formula)){
		$partes = explode("_", $bus_relacion_formula["valor_oculto"]);
        /*if ($destino == 'condicion') {
            echo $partes[0] . ' - ' . $partes[1];
        }*/
		switch($partes[0]){
			case "SI":// SI ES UN SIMBOLO ENTRA ACA
				$formula .= $partes[1];
			break;
			case "CN":// SI ES UNA CONSTANTE ENTRA ACA
				$sql_constantes= mysql_query("SELECT * FROM constantes_nomina
																					WHERE idconstantes_nomina='".$partes[1]."'");
											$bus_constantes = mysql_fetch_array($sql_constantes);
											if($bus_constantes["valor"] == 0){
												$sql_consulta_relacion= mysql_query("select * from relacion_concepto_trabajador
																							where tabla = 'constantes_nomina'
																							and idconcepto = '".$partes[1]."'
																							and idtrabajador = '".$idtrabajador."'
																							and idtipo_nomina = '".$idtipo_nomina."'");
												$bus_consulta_relacion = mysql_fetch_array($sql_consulta_relacion);
												$monto = $bus_consulta_relacion["valor"];
											}else{
												$monto = $bus_constantes["valor"];
											}
											//echo "AQUII :".$monto." ---- ";
											if($monto == ""){
												$formula .= "0";
											}else{
												$formula .= $monto;
											}

			break;
			case "CO":// SI ES UN CONCEPTO ENTRA ACA
				//echo "aqui";
				 $result = call_concepto($partes[1], $idtrabajador, 'principal', $idgenerar_nomina, $idtipo_nomina, $idperiodo, $desagregar_concepto, $factor);

				 $formula .= $result;


				if($desagregar_concepto == "si"){
					eval("\$t = ($result*$factor);");
					$sql_insertar = mysql_query("insert into conceptos_desagregados(idgenerar_nomina,
						idconcepto,
						idtrabajador,
						valor)VALUES('".$idgenerar_nomina."',
									'".$partes[1]."',
									'".$idtrabajador."',
									'".$t."')");
				}

			break;
			case "THT":// SI ES UNA HOJA DE TIEMPO
					$sql_hoja_tiempo = mysql_query("select * from hoja_tiempo
														where
														idtipo_hoja_tiempo = '".$partes[1]."'
														and periodo = '".$idperiodo."'")or die(mysql_error());
					$num_hoja_tiempo = mysql_num_rows($sql_hoja_tiempo);
					if($num_hoja_tiempo > 0){
						while($bus_hoja_tiempo = mysql_fetch_array($sql_hoja_tiempo)){
						$sql_relacion_trabajador = mysql_query("select horas from relacion_hoja_tiempo_trabajador
																where idhoja_tiempo = '".$bus_hoja_tiempo["idhoja_tiempo"]."'
																and idtrabajador = '".$idtrabajador."'");
						$bus_relacion_trabajador = mysql_fetch_array($sql_relacion_trabajador);
						if($bus_relacion_trabajador["horas"] == ""){
							$formula .= "0";
						}else{
							$formula .= $bus_relacion_trabajador["horas"];
						}
						}

					}else{
						$formula .= "0";
					}


										//echo $formula;
			break;
			case "TA":// SI ES UNA TABLA CONSTANTE ENTRA ACA
				$sql_consultar_tabla= mysql_query("SELECT * FROM
														tabla_constantes
														WHERE
														idtabla_constantes = '".$partes[1]."'");
				$bus_consultar_tabla = mysql_fetch_array($sql_consultar_tabla);
				$sql_consulta_siguiente_relacion = mysql_query("SELECT * FROM
																		relacion_formula_conceptos_nomina
																		WHERE idconcepto_nomina = '".$bus_relacion_formula["idconcepto_nomina"]."'
																		and orden = '".($bus_relacion_formula["orden"]+1)."'");
				$bus_consulta_siguiente_relacion = mysql_fetch_array($sql_consulta_siguiente_relacion);
				$partes_fu = explode("_", $bus_consulta_siguiente_relacion["valor_oculto"]);
				if($partes_fu[0] == "SI"){
						$sql_rango = mysql_query("SELECT * FROM
													rango_tabla_constantes
													WHERE
														idtabla_constantes = '".$bus_consultar_tabla["idtabla_constantes"]."'
														and ".$partes_fu[1]." >= desde
														and ".$partes_fu[1]." <= hasta
														order by idrango_tabla_constantes asc limit 0,1");
						$bus_rango = mysql_fetch_array($sql_rango);

						if($bus_rango["valor"] == ""){
							$formula .= "0";
						}else{
							$formula .= $bus_rango["valor"];
						}


				}else if($partes_fu[0] == "FU"){
						if(ereg("numerode", $partes_fu[1])){
							$tabla = explode("(", $partes_fu[1]);
							$tabla = substr($tabla[1], 0, strlen($tabla[1])-1);
							$final = explode("-",$tabla);

							$numero = numerode($final[0], $final[1], $final[2], $idtrabajador);
						}else if(ereg("tiempobono", $partes_fu[1])){
							$numero = tiempo_bono($idperiodo,$idtrabajador);
						}else if(ereg("diafinperiodo", $partes_fu[1])){
                            $numero = dia_fin_periodo($idperiodo);

                        }else if(ereg("anioempresa", $partes_fu[1])){
							$numero = anioempresa($idtrabajador);
							//$cantidad_numero = $numero;
						}else if(ereg("ingresohasta", $partes_fu[1])){
							$fechas = explode("(", $partes_fu[1]);
							//echo $fechas[0];
							$numero = ingresohasta($idtrabajador,$fechas[0]);
							//echo "años en la empresa ".$numero;
						}else if(ereg("tiempoentrefechas", $partes_fu[1])){
							$fechas = explode("(", $partes_fu[1]);
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$numero = tiempoentrefechas($fechas[0], $fechas[1]);
						}else if(ereg("diasferiadosentre", $partes_fu[1])){
							$fechas = explode("(", $partes_fu[1]);
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$numero = diasferiadosentre($fechas[0], $fechas[1]);
						}else if(ereg("mesactual", $partes_fu[1])){
							$sql_periodos = mysql_query("select * from
													rango_periodo_nomina
													where
													idrango_periodo_nomina = '".$idperiodo."'");
							$bus_periodos = mysql_fetch_array($sql_periodos);
							$datos = explode("-", $bus_periodos["desde"]);
							$numero = $datos[1];
						}else if(ereg("diasmes", $partes_fu[1])){
							$numero = diasmes(date("Y") , date("m"));
						}else if(ereg("mesesentre", $partes_fu[1])){
							$fechas = explode("(", $partes_fu[1]);
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$numero = mesesentre($fechas[0], $fechas[1], $idtrabajador);
						}else if(ereg("diasentre", $partes_fu[1])){
							$fechas = explode("(", $partes_fu[1]);
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$numero = diasentre($fechas[0], $fechas[1], $idtrabajador);
						}

						$numero = (int)$numero;

						$sql_rango = mysql_query("SELECT * FROM
													rango_tabla_constantes
													WHERE
														idtabla_constantes = '".$bus_consultar_tabla["idtabla_constantes"]."'
														and ".$numero." >= desde
														and ".$numero." <= hasta
														order by idrango_tabla_constantes asc limit 0,1");
						$bus_rango = mysql_fetch_array($sql_rango);
						//echo "RANGO ".$bus_rango["valor"];

						if($bus_rango["valor"] == ""){
							$formula .= "0";
						}else {
                            $formula .= $bus_rango["valor"];
                        }
				}
			break;
			case "FU": // SI ES UNA FUNCION ENTRA ACA
				//echo "aqui";
				$sql_consultar_anterior = mysql_query("SELECT * FROM
																relacion_formula_conceptos_nomina
																WHERE idconcepto_nomina = '".$bus_relacion_formula["idconcepto_nomina"]."'
																and orden = '".($bus_relacion_formula["orden"]-1)."'");

				$bus_consultar_anterior = mysql_fetch_array($sql_consultar_anterior);
				$partes_anterior = explode("_", $bus_consultar_anterior["valor_oculto"]);
					if($partes_anterior[0] != "TA"){
						//echo $partes[1].".............";
						if(ereg("numerode", $partes[1])){
							$tabla = explode("(", $partes[1]);
							$tabla = substr($tabla[1], 0, strlen($tabla[1])-1);
							$final = explode("-",$tabla);
							$result = numerode($final[0], $final[1], $final[2], $idtrabajador);
							if($result == ""){
								$formula .= "0";
							}else{
								$formula .= $result;
							}


						}else if(ereg("tiempobono", $partes[1])){
						//echo "ALLA....\n";
							$formula .= tiempo_bono($idperiodo,$idtrabajador);

						}else if(ereg("anioempresa", $partes[1])){
							$result .= anioempresa($idtrabajador);

							if($result == ""){
								$formula .= "0";
							}else{
								$formula .= $result;
							}
							//$cantidad_numero = $result;
							//echo $cantidad_numero;
						}else if(ereg("ingresohasta", $partes[1])){
							$fechas = explode("(", $partes[1]);

							$result = ingresohasta($idtrabajador,$fechas[1]);
							//echo "años en la empresa ".$numero;

							if($result <= 0){
								$formula .= "0";
							}else{
								$formula .= $result;
							}


						}else if(ereg("tiempoentrefechas", $partes[1])){
							$fechas = explode("(", $partes[1]);
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$result = tiempoentrefechas($fechas[0], $fechas[1]);

							if($result == ""){
								$formula .= "0";
							}else{
								$formula .= $result;
							}
						}else if(ereg("diasferiadosentre", $partes[1])){
							$fechas = explode("(", $partes[1]);
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$result = diasferiadosentre($fechas[0], $fechas[1]);

							if($result == ""){
								$formula .= "0";
							}else{
								$formula .= $result;
							}

						}else if(ereg("mesactual", $partes[1])){
							$sql_periodos = mysql_query("select * from
													rango_periodo_nomina
													where
													idrango_periodo_nomina = '".$idperiodo."'");
							$bus_periodos = mysql_fetch_array($sql_periodos);
							$datos = explode("-", $bus_periodos["desde"]);
							$formula .= $datos[1];

						}else if(ereg("diasmes", $partes[1])){
							$formula .= diasmes(date("Y") , date("m"));
						}else if(ereg("mesesentre", $partes[1])){
							$fechas = explode("(", $partes[1]);
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$result = mesesentre($fechas[0], $fechas[1], $idtrabajador);

							if($result == ""){
								$formula .= "0";
							}else{
								$formula .= $result;
							}

							//echo $formula;
						}else if(ereg("diasentre", $partes[1])){
							$fechas = explode("(", $partes[1]);
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$result = diasentre($fechas[0], $fechas[1], $idtrabajador);

							if($result == ""){
								$formula .= "0";
							}else{
								$formula .= $result;
							}
							//echo "ACAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA";
						}else if(ereg("continuidadAdministrativa", $partes[1])){
								$result = continuidadAdministrativa($idtrabajador, $partes[2]);
								if($result == ""){
									$formula .= "0";
								}else{
									$formula .= $result;
								}
						}else if(ereg("diafinperiodo", $partes[1])){
                            $result = dia_fin_periodo($idperiodo);
                            //echo " RESULTADO DIA FIN PERIODO ".$result;
                            if($result == ""){
                                $formula .= "0";
                            }else{
                                $formula .= $result;
                            }
                        }
						//echo $formula;
					}


			break;
		}
	}
	return $formula;
}






if($ejecutar == "generarNomina") {
	//echo "entro";
    $sql_oc = mysql_query("select * from generar_nomina where idgenerar_nomina = '" . $idgenerar_nomina . "'")or die(mysql_error());
    $bus_oc = mysql_fetch_array($sql_oc);
    $idcertificacion = $bus_oc["idorden_compra_servicio"];
    $idcertificacion_aporte = $bus_oc["idorden_compra_servicio_aporte"];
    $sql_limpiar = mysql_query("delete from conceptos_desagregados where idgenerar_nomina = '" . $idgenerar_nomina . "'");
    //echo $idcertificacion;
    if ($idcertificacion <> '') {
       $sql_eliminar_oc = mysql_query("delete from orden_compra_servicio where idorden_compra_servicio = '".$bus_oc["idorden_compra_servicio"]."'")or die (mysql_error());
       $sql_partidas_oc = mysql_query("delete from partidas_orden_compra_servicio where idorden_compra_servicio = '".$bus_oc["idorden_compra_servicio"]."'")or die (mysql_error());
       $sql_articulos_oc = mysql_query("delete from articulos_compra_servicio where idorden_compra_servicio = '".$bus_oc["idorden_compra_servicio"]."'")or die (mysql_error());
       $sql_asiento = mysql_query("select * from asiento_contable where iddocumento = '".$bus_oc["idorden_compra_servicio"]."'")or die (mysql_error());
       $bus_asiento = mysql_fetch_array($sql_asiento);
       $sql_easiento = mysql_query("delete from asiento_contable where iddocumento = '".$bus_oc["idorden_compra_servicio"]."'")or die (mysql_error());
       $sql_ecasiento = mysql_query("delete from cuentas_asiento_contable where idasiento_contable = '".$bus_asiento["idasiento_contable"]."'")or die (mysql_error());
       $idcertificacion = '';
    }
    if ($idcertificacion_aporte <> '') {
        $sql_eliminar_oc = mysql_query("delete from orden_compra_servicio where idorden_compra_servicio = '".$bus_oc["idorden_compra_servicio_aporte"]."'")or die (mysql_error());
        $sql_partidas_oc = mysql_query("delete from partidas_orden_compra_servicio where idorden_compra_servicio = '".$bus_oc["idorden_compra_servicio_aporte"]."'")or die (mysql_error());
        $sql_articulos_oc = mysql_query("delete from articulos_compra_servicio where idorden_compra_servicio = '".$bus_oc["idorden_compra_servicio_aporte"]."'")or die (mysql_error());
        $sql_asiento = mysql_query("select * from asiento_contable where iddocumento = '".$bus_oc["idorden_compra_servicio_aporte"]."'")or die (mysql_error());
        $bus_asiento = mysql_fetch_array($sql_asiento);
        $sql_easiento = mysql_query("delete from asiento_contable where iddocumento = '".$bus_oc["idorden_compra_servicio_aporte"]."'")or die (mysql_error());
        $sql_ecasiento = mysql_query("delete from cuentas_asiento_contable where idasiento_contable = '".$bus_asiento["idasiento_contable"]."'")or die (mysql_error());
        $idcertificacion_aporte = '';
    }

	$sql_limpiar = mysql_query("delete from relacion_generar_nomina where idgenerar_nomina = '".$idgenerar_nomina."'");


	if($estado == "Pre Nomina"){
		$sql_actualizar  = mysql_query("update generar_nomina
							   				set estado='".$estado."',
											fecha_procesado = '".date("Y-m-d")."'
												where
											idgenerar_nomina = '".$idgenerar_nomina."'");
	}

	$sql_trabajador = mysql_query("select tr.nombres,
										tr.apellidos,
										tr.cedula,
										tr.idtrabajador,
										no.idcategoria_programatica
											FROM
										trabajador tr,
										relacion_tipo_nomina_trabajador rtt,
										niveles_organizacionales no
											WHERE
										rtt.idtipo_nomina = '".$idtipo_nomina."'
										and rtt.activa = '1'
										and rtt.idtrabajador = tr.idtrabajador
										and tr.status = 'a'
										and tr.activo_nomina = 'si'
										and tr.vacaciones != 'si'
										and no.idniveles_organizacionales = tr.centro_costo
										group by tr.idtrabajador")or die(mysql_error());

	$k=0;
	//$num_trabajador = mysql_num_rows($sql_trabajador);

	//DETERMINO SI EL PERIODO DE LA NOMINA TIENE CONCEPTOS FRACCIONADOS (CUANDO SE EJECUTA UNA PARTE DE LOS
	//																	 CONCEPTOS EN UNA QUINCENA Y LA OTRA
	//																	 PARTE EN LA QUINCENA SIGUIENTE)
	$sql_division_periodo0 = mysql_query("select * from relacion_conceptos_periodos
												where idtipo_nomina = '".$idtipo_nomina."'
												and idperiodo = '".$idperiodo."'")or die(mysql_error());
	if (mysql_num_rows($sql_division_periodo0)){
		$existe_fraccion = mysql_num_rows($sql_division_periodo0)or die("error1111 ".mysql_error());
	}else{
		$existe_fraccion=0;
	}

	//OBTENGO EL RANGO DESDE-HASTA DEL PERIODO PARA VALIDAR LOS CONCEPTOS QUE TIENEN FECHA TOPE DE EJECUCION
	$sql_rango_periodo = mysql_query("select * from rango_periodo_nomina where idrango_periodo_nomina = '".$idperiodo."'");
	$bus_rango_periodo = mysql_fetch_array($sql_rango_periodo);

	//INICIO CICLO CON LOS TRABAJADORES DE LA NOMINA PARA EL CALCULO DE LOS CONCEPTOS ASOCIADOS A CADA TRABAJADOR
	while($bus_trabajador =mysql_fetch_array($sql_trabajador)){
			$idtrabajador = $bus_trabajador["idtrabajador"]	;
			$cantidad_numero = 0;

			//OBTENGO LOS CONCEPTOS QUE TIENE EL TRABAJADOR
			$sql_consulta_asociado = mysql_query("(SELECT * FROM
															relacion_concepto_trabajador
																WHERE
															idtrabajador = '".$idtrabajador."'
															and idtipo_nomina = '".$idtipo_nomina."'
															and fecha_ejecutar_desde = '0000-00-00'
															and fecha_ejecutar_hasta = '0000-00-00')
																UNION
																	(SELECT * FROM
																	relacion_concepto_trabajador
																		WHERE
																	idtrabajador = '".$idtrabajador."'
																	and idtipo_nomina = '".$idtipo_nomina."'
																	and fecha_ejecutar_desde <= '".$bus_rango_periodo["desde"]."'
																	and fecha_ejecutar_hasta >= '".$bus_rango_periodo["hasta"]."')")or die(mysql_error());

			//INICIO CICLO CON LOS CONCEPTOS ASOCIADOS QUE TIENE EL TRABAJADOR
			while($bus_consulta_asociado = mysql_fetch_array($sql_consulta_asociado)){
				$formula = "";
				$sql_consulta_tabla = mysql_query("SELECT * FROM ".$bus_consulta_asociado["tabla"]."
															where id".$bus_consulta_asociado["tabla"]." ='".$bus_consulta_asociado["idconcepto"]."'");
				$bus_consulta_tabla = mysql_fetch_array($sql_consulta_tabla);

				if($bus_consulta_asociado["tabla"] == "conceptos_nomina"){ // SI LA TABLA ES CONCEPTOS
					$sql_relacion_formula= mysql_query("SELECT * FROM
																relacion_formula_conceptos_nomina
																	WHERE
																idconcepto_nomina ='".$bus_consulta_tabla["idconceptos_nomina"]."'
																and destino = 'condicion'
																order by orden");
					$num_relacion_formula= mysql_num_rows($sql_relacion_formula);

					if($num_relacion_formula > 0){
						//echo "SI TIENE UN CONDICIONAL ".$idtrabajador;

						$result = call_concepto($bus_consulta_tabla["idconceptos_nomina"], $idtrabajador, 'condicion', $idgenerar_nomina, $idtipo_nomina, $idperiodo, $bus_consulta_tabla["desagregar_concepto"], $bus_consulta_tabla["factor_desagregacion"]);
						//echo "AQUI";
						@eval("\$res = $result;");
						//echo $res."----".$result;

						if($res == "1"){
						//echo "SI";
							$formula .= call_concepto($bus_consulta_tabla["idconceptos_nomina"], $idtrabajador, 'entonces',$idgenerar_nomina, $idtipo_nomina, $idperiodo, $bus_consulta_tabla["desagregar_concepto"], $bus_consulta_tabla["factor_desagregacion"]);
							//echo $formula;
						}else{
						//echo "NOOOOOOO";

							$result = call_concepto($bus_consulta_tabla["idconceptos_nomina"], $idtrabajador, 'principal', $idgenerar_nomina, $idtipo_nomina, $idperiodo, $bus_consulta_tabla["desagregar_concepto"], $bus_consulta_tabla["factor_desagregacion"]);
							$formula .= $result;

						}
					}else{
						$sql_relacion_formula= mysql_query("SELECT * FROM
																	relacion_formula_conceptos_nomina
																		WHERE
																	idconcepto_nomina ='".$bus_consulta_tabla["idconceptos_nomina"]."'
																	and destino= 'principal'
																	order by orden");
						while($bus_relacion_formula = mysql_fetch_array($sql_relacion_formula)){
							$partes = explode("_", $bus_relacion_formula["valor_oculto"]);
							switch($partes[0]){
								case "SI":// SI ES UN SIMBOLO ENTRA ACA
									//echo "AA";
									$formula .= $partes[1];
									//echo $formula;
								break;
								case "CN":// SI ES UNA CONSTANTE ENTRA ACA
									$sql_constantes= mysql_query("SELECT * FROM constantes_nomina
																			WHERE idconstantes_nomina='".$partes[1]."'");
									$bus_constantes = mysql_fetch_array($sql_constantes);
									if($bus_constantes["valor"] == 0){
										$sql_consulta_relacion= mysql_query("select * from relacion_concepto_trabajador
																					where tabla = 'constantes_nomina'
																					and idconcepto = '".$partes[1]."'
																					and idtrabajador = '".$idtrabajador."'
																					and idtipo_nomina = '".$idtipo_nomina."'");
										$bus_consulta_relacion = mysql_fetch_array($sql_consulta_relacion);
										$monto = $bus_consulta_relacion["valor"];
									}else{
										$monto = $bus_constantes["valor"];
									}
									//echo "MONTO: ".$monto." --- ";
									if($monto == ""){
										$formula .= "0";
									}else{
										$formula .= $monto;
									}
								break;
								case "CO":// SI ES UN CONCEPTO ENTRA ACA

										//echo "CONCEPTO";
										$result = call_concepto($partes[1], $idtrabajador, 'principal', $idgenerar_nomina, $idtipo_nomina, $idperiodo, $bus_consulta_tabla["desagregar_concepto"], $bus_consulta_tabla["factor_desagregacion"]);
										//echo "RESULTADO: ".$result."............    ";
										//echo $bus_consulta_tabla["desagregar_concepto"];

										if($bus_consulta_tabla["desagregar_concepto"] == "si"){
											$sql_insertar = mysql_query("insert into conceptos_desagregados(idgenerar_nomina,
											idconcepto,
											idtrabajador,
											valor)VALUES('".$idgenerar_nomina."',
													'".$partes[1]."',
													'".$idtrabajador."',
													'".eval($result*$bus_consulta_tabla["factor_desagregacion"])."')");
										}

										if($result == ""){
											$formula .= "0";
										}else{
											$formula .= $result;
										}

										//echo $formula;
								break;
								case "THT":// SI ES UNA HOJA DE TIEMPO

									$sql_hoja_tiempo = mysql_query("select * from hoja_tiempo where
																   		idtipo_hoja_tiempo = '".$partes[1]."'
																		and idtipo_nomina = '".$idtipo_nomina."'
																		and periodo = '".$idperiodo."'")or die(mysql_error());
									$num_hoja_tiempo = mysql_num_rows($sql_hoja_tiempo);

									if($num_hoja_tiempo > 0){
									while($bus_hoja_tiempo = mysql_fetch_array($sql_hoja_tiempo)){
										$sql_relacion_trabajador = mysql_query("select horas
																from relacion_hoja_tiempo_trabajador
																where idhoja_tiempo = '".$bus_hoja_tiempo["idhoja_tiempo"]."'
																and idtrabajador = '".$idtrabajador."'");
										$bus_relacion_trabajador = mysql_fetch_array($sql_relacion_trabajador);
										//echo $p++." - VALOR: ".$bus_relacion_trabajador["horas"]." AQUI \n";
										if($bus_relacion_trabajador["horas"] == "" || $bus_relacion_trabajador["horas"] == "()"){
											$formula .= "0";
											$cantidad_numero = 0;
										}else{
											$formula .= $bus_relacion_trabajador["horas"];
											$cantidad_numero = $bus_relacion_trabajador["horas"];
										}
									}
									}else{
										$formula .= "0";
									}
								break;
								case "TA":// SI ES UNA TABLA CONSTANTE ENTRA ACA
									//echo $partes[1]." - ";
									$sql_consultar_tabla= mysql_query("SELECT * FROM
																			tabla_constantes
																			WHERE
																			idtabla_constantes = '".$partes[1]."'");
									$bus_consultar_tabla = mysql_fetch_array($sql_consultar_tabla);
									$sql_consulta_siguiente_relacion = mysql_query("SELECT * FROM
																	relacion_formula_conceptos_nomina
																	WHERE idconcepto_nomina = '".$bus_relacion_formula["idconcepto_nomina"]."'
																	and orden = '".($bus_relacion_formula["orden"]+1)."'");
									$bus_consulta_siguiente_relacion = mysql_fetch_array($sql_consulta_siguiente_relacion);
									$partes_fu = explode("_", $bus_consulta_siguiente_relacion["valor_oculto"]);
									if($partes_fu[0] == "SI"){
											$sql_rango = mysql_query("SELECT * FROM
														rango_tabla_constantes
														WHERE
															idtabla_constantes = '".$bus_consultar_tabla["idtabla_constantes"]."'
															and '".$partes_fu[1]."' >= desde
															and '".$partes_fu[1]."' <= hasta
															order by idrango_tabla_constantes asc limit 0,1");
											$bus_rango = mysql_fetch_array($sql_rango);

											if($bus_rango["valor"] == ""){
												$formula .= "0";
												$cantidad_numero = 0;
											}else{
												$formula .= $bus_rango["valor"];
												$cantidad_numero = $bus_rango["valor"];
											}



									}else if($partes_fu[0] == "FU"){
											if(ereg("numerode", $partes_fu[1])){
												$tabla = explode("(", $partes_fu[1]);
												$tabla = substr($tabla[1], 0, strlen($tabla[1])-1);
												$final = explode("-",$tabla);
												$numero = numerode($final[0], $final[1], $final[2], $idtrabajador);
											}else if(ereg("anioempresa", $partes_fu[1])){
												$numero = anioempresa($idtrabajador);
												//$cantidad_numero = $numero;
											}else if(ereg("ingresohasta", $partes_fu[1])){
												$fechas = explode("(", $partes_fu[1]);
												//echo $fechas[1];
												$numero = ingresohasta($idtrabajador,$fechas[1]);
												//echo "años en la empresa3 ".$numero;

											}else if(ereg("tiempo_bono", $partes_fu[1])){

												$numero = tiempo_bono($idperiodo,$idtrabajador);

											}else if(ereg("diafinperiodo", $partes_fu[1])){

                                                $numero = dia_fin_periodo($idperiodo);

                                            }else if(ereg("tiempoentrefechas", $partes_fu[1])){
												$fechas = explode("(", $partes_fu[1]);
												$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
												$fechas = explode(",", $fechas);
												$numero = tiempoentrefechas($fechas[0], $fechas[1]);
											}else if(ereg("diasferiadosentre", $partes_fu[1])){
												$fechas = explode("(", $partes_fu[1]);
												$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
												$fechas = explode(",", $fechas);
												$numero = diasferiadosentre($fechas[0], $fechas[1]);
											}else if(ereg("mesactual", $partes_fu[1])){
												//echo "AQUI TE ENTRO";

												$sql_periodos = mysql_query("select * from
																		rango_periodo_nomina
																		where
																		idrango_periodo_nomina = '".$idperiodo."'");
												$bus_periodos = mysql_fetch_array($sql_periodos);
												$datos = explode("-", $bus_periodos["desde"]);
												$numero = $datos[1];

												//$numero = date("m");
												//echo " - ".$numero;
											}else if(ereg("diasmes", $partes_fu[1])){
												$numero = diasmes(date("Y") , date("m"));
											}else if(ereg("mesesentre", $partes_fu[1])){
												$fechas = explode("(", $partes_fu[1]);
												$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
												$fechas = explode(",", $fechas);
												$numero = mesesentre($fechas[0], $fechas[1], $idtrabajador);
											}else if(ereg("diasentre", $partes_fu[1])){
												$fechas = explode("(", $partes_fu[1]);
												$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
												$fechas = explode(",", $fechas);
												$numero = diasentre($fechas[0], $fechas[1], $idtrabajador);
											}else if(ereg("continuidadAdministrativa", $partes[1])){
													$result = continuidadAdministrativa($idtrabajador, $partes[2]);
													if($result == ""){
														$formula .= "0";
													}else{
														$formula .= $result;
													}
											}

											$numero = (int)$numero;


											$sql_rango = mysql_query("SELECT * FROM
																		rango_tabla_constantes
																		WHERE
															idtabla_constantes = '".$bus_consultar_tabla["idtabla_constantes"]."'
															and ".$numero." >= desde
															and ".$numero." <= hasta
															order by idrango_tabla_constantes asc limit 0,1")or die(mysql_error());
											$bus_rango = mysql_fetch_array($sql_rango);
											//echo "PRUEBA ".$bus_rango["valor"];


											if($bus_rango["valor"] == ""){
												$formula .= "0";
												$cantidad_numero = 0;
											}else{
												$formula .= $bus_rango["valor"];
												$cantidad_numero = $bus_rango["valor"];
											}



									}
								break;
								case "FU": // SI ES UNA FUNCION ENTRA ACA

									$sql_consultar_anterior = mysql_query("SELECT * FROM
																		relacion_formula_conceptos_nomina
																		WHERE idconcepto_nomina = '".$bus_relacion_formula["idconcepto_nomina"]."'
																		and orden = '".($bus_relacion_formula["orden"]-1)."'
																		and destino = 'principal'")or die(mysql_error());

									$bus_consultar_anterior = mysql_fetch_array($sql_consultar_anterior);
									$partes_anterior = explode("_", $bus_consultar_anterior["valor_oculto"]);
										//echo $partes_anterior[0].".....";
										if($partes_anterior[0] != "TA"){
											//echo $partes[1]."...";
											if(ereg("numerode", $partes[1])){
												$tabla = explode("(", $partes[1]);
												$tabla = substr($tabla[1], 0, strlen($tabla[1])-1);
												$final = explode("-",$tabla);
												$result = numerode($final[0], $final[1], $final[2], $idtrabajador);


												if($result == ""){
													$formula .= "0";
												}else{
													$formula .= $result;
												}

												$cantidad_numero = numerode($final[0], $final[1], $final[2], $idtrabajador);
											}else if(ereg("tiempobono", $partes[1])){
											//echo "AQUI";
											//$formula .= "PRUEBAAAA";
												$formula .= tiempo_bono($idperiodo,$idtrabajador);

												//echo "id ".$idtrabajador." ".$formula."...... \n";


											}else if(ereg("diafinperiodo", $partes[1])){
                                                $formula .= dia_fin_periodo($idperiodo);
                                            }else if(ereg("anioempresa", $partes[1])){
												$result = anioempresa($idtrabajador);

												if($result == ""){
													$formula .= "0";
												}else{
													$formula .= $result;
												}
												//$cantidad_numero = $result;
												//echo $cantidad_numero;
											}else if(ereg("ingresohasta", $partes[1])){
												$fechas = explode("(", $partes[1]);

												$result = ingresohasta($idtrabajador,$fechas[1]);
												//echo "años en la empresa ".$numero;

												if($result <= 0){
													$formula .= "0";
												}else{
													$formula .= $result;
												}


											}else if(ereg("tiempoentrefechas", $partes[1])){
												$fechas = explode("(", $partes[1]);
												$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
												$fechas = explode(",", $fechas);
												$result = tiempoentrefechas($fechas[0], $fechas[1]);


												if($result == ""){
													$formula .= "0";
												}else{
													$formula .= $result;
												}



											}else if(ereg("diasferiadosentre", $partes[1])){
												$fechas = explode("(", $partes[1]);
												$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
												$fechas = explode(",", $fechas);
												$result = diasferiadosentre($fechas[0], $fechas[1]);

												if($result == ""){
													$formula .= "0";
												}else{
													$formula .= $result;
												}

											}else if(ereg("mesactual", $partes[1])){
												$sql_periodos = mysql_query("select * from
																		rango_periodo_nomina
																		where
																		idrango_periodo_nomina = '".$idperiodo."'");
												$bus_periodos = mysql_fetch_array($sql_periodos);
												$datos = explode("-", $bus_periodos["desde"]);
												$formula .=$datos[1];

											}else if(ereg("diasmes", $partes[1])){
												$formula .= diasmes(date("Y") , date("m"));
											}else if(ereg("mesesentre", $partes[1])){

												$fechas = explode("(", $partes[1]);
												$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
												$fechas = explode(",", $fechas);

												$result = mesesentre($fechas[0], $fechas[1], $idtrabajador);

												if($result == ""){
													$formula .= "0";
												}else{
													$formula .= $result;
												}
											}else if(ereg("diasentre", $partes[1])){

												$fechas = explode("(", $partes[1]);
												$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
												$fechas = explode(",", $fechas);
												$result = diasentre($fechas[0], $fechas[1], $idtrabajador);

												if($result == ""){
													$formula .= "0";
												}else{
													$formula .= $result;
												}
												//echo $formula;
											}else if(ereg("continuidadAdministrativa", $partes[1])){
													$result = continuidadAdministrativa($idtrabajador, $partes[2]);
													//echo "AQUI ".$result." AAAAA\n";
													if($result == ""){
														$formula .= "0";
													}else{
														$formula .= $result;
													}


											}else if($partes_fu[0] == "FU"){
													if(ereg("numerode", $partes_fu[1])){
														$tabla = explode("(", $partes_fu[1]);
														$tabla = substr($tabla[1], 0, strlen($tabla[1])-1);
														$final = explode("-",$tabla);
														$numero = numerode($final[0], $final[1], $final[2], $idtrabajador);
													}else if(ereg("anioempresa", $partes_fu[1])){
														$numero = anioempresa($idtrabajador);
														//$cantidad_numero = $numero;
													}else if(ereg("ingresohasta", $partes_fu[1])){
														$fechas = explode("(", $partes_fu[1]);
														//echo $fechas[1];
														$numero = ingresohasta($idtrabajador,$fechas[1]);
														//echo "años en la empresa3 ".$numero;

													}else if(ereg("tiempobono", $partes_fu[1])){

														$numero = tiempo_bono($idperiodo,$idtrabajador);

													}else if(ereg("diafinperiodo", $partes_fu[1])){

                                                        $numero = dia_fin_periodo($idperiodo);

                                                    }else if(ereg("tiempoentrefechas", $partes_fu[1])){
														$fechas = explode("(", $partes_fu[1]);
														$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
														$fechas = explode(",", $fechas);
														$numero = tiempoentrefechas($fechas[0], $fechas[1]);
													}else if(ereg("diasferiadosentre", $partes_fu[1])){
														$fechas = explode("(", $partes_fu[1]);
														$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
														$fechas = explode(",", $fechas);
														$numero = diasferiadosentre($fechas[0], $fechas[1]);
													}else if(ereg("mesactual", $partes_fu[1])){
														//echo "AQUI TE ENTRO";

														$sql_periodos = mysql_query("select * from
																				rango_periodo_nomina
																				where
																				idrango_periodo_nomina = '".$idperiodo."'");
														$bus_periodos = mysql_fetch_array($sql_periodos);
														$datos = explode("-", $bus_periodos["desde"]);
														$numero = $datos[1];

														//$numero = date("m");
														//echo " - ".$numero;
													}else if(ereg("diasmes", $partes_fu[1])){
														$numero = diasmes(date("Y") , date("m"));
													}else if(ereg("mesesentre", $partes_fu[1])){
														$fechas = explode("(", $partes_fu[1]);
														$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
														$fechas = explode(",", $fechas);
														$numero = mesesentre($fechas[0], $fechas[1], $idtrabajador);
													}else if(ereg("diasentre", $partes_fu[1])){
														$fechas = explode("(", $partes_fu[1]);
														$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
														$fechas = explode(",", $fechas);
														$numero = diasentre($fechas[0], $fechas[1], $idtrabajador);
													}else if(ereg("continuidadAdministrativa", $partes[1])){
															$result = continuidadAdministrativa($idtrabajador, $partes[2]);
															if($result == ""){
																$formula .= "0";
															}else{
																$formula .= $result;
															}
													}

													$numero = (int)$numero;


													$sql_rango = mysql_query("SELECT * FROM
																				rango_tabla_constantes
																				WHERE
																	idtabla_constantes = '".$bus_consultar_tabla["idtabla_constantes"]."'
																	and ".$numero." >= desde
																	and ".$numero." <= hasta
																	order by idrango_tabla_constantes asc limit 0,1")or die(mysql_error());
													$bus_rango = mysql_fetch_array($sql_rango);
													//echo "PRUEBA ".$bus_rango["valor"];


													if($bus_rango["valor"] == ""){
														$formula .= "0";
														$cantidad_numero = 0;
													}else{
														$formula .= $bus_rango["valor"];
														$cantidad_numero = $bus_rango["valor"];
													}


											}
											//echo $partes[1]($idtrabajador)."...";
										}
								break;
							}
						}
							//echo $formula;

					}//CIERRA SI NO TIENE UNA CONDICION
				}else{
					if($bus_consulta_tabla["valor"] == 0){
						$formula .= $bus_consulta_asociado["valor"];
					}else{
						$formula .= $bus_consulta_tabla["valor"];
					}
				}
				//echo " | ".$formula;

				//echo "PASO";
				//echo $formula."-----";

				//echo $formula.".........\n";
				if($formula != ""){
					//echo 'f '.$formula.".........\n";
					@eval("\$total = $formula;");

					$sql_division_periodo = mysql_query("select * from relacion_conceptos_periodos
												where idconcepto = '".$bus_consulta_asociado["idconcepto"]."'
												and idtipo_nomina = '".$idtipo_nomina."'
												and idperiodo = '".$idperiodo."'")or die(mysql_error());

					if (mysql_num_rows($sql_division_periodo)){
						$num_division_periodo = mysql_num_rows($sql_division_periodo) or die("aqui numero relacion ".mysql_error());
					}else{
						$num_division_periodo = 0;
					}
					//echo $num_division_periodo." ";
					if($num_division_periodo > 0){
						$bus_division_periodo = mysql_fetch_array($sql_division_periodo)or die("aqui relacion ".mysql_error());
						$total = (($total * $bus_division_periodo["valor"])/100);
					}else{
						if ($existe_fraccion > 0){
							$total = 0;
						}
					}
					//echo $total." ........ \n";
				}




				$sql_consulta = mysql_query("select * from relacion_generar_nomina where
											idgenerar_nomina ='".$idgenerar_nomina."'
											and idtrabajador = '".$idtrabajador."'
											and idconcepto = '".$bus_consulta_asociado["idconcepto"]."'
											and tabla = '".$bus_consulta_asociado["tabla"]."'")or die("!!!!".mysql_error());
				$num_consulta = mysql_num_rows($sql_consulta);

				$sql_neutro = mysql_query("select * from ".$bus_consulta_asociado["tabla"]."
															where
															id".$bus_consulta_asociado["tabla"]." = ".$bus_consulta_asociado["idconcepto"]."")or die(mysql_error());
				$bus_neutro = mysql_fetch_array($sql_neutro);

				if($bus_consulta_asociado["tabla"] == "constantes_nomina"){
					$afecta = $bus_neutro["afecta"];
				}else{
					$afecta = $bus_neutro["tipo_concepto"];
				}

				$sql_afecta = mysql_query("select * from tipo_conceptos_nomina where idconceptos_nomina = '".$afecta."'");
				$bus_afecta = mysql_fetch_array($sql_afecta);
				if($bus_afecta["afecta"] == "Asignacion"){
					$id_afecta = 1;
				}else if($bus_afecta["afecta"] == "Deduccion"){
					$id_afecta = 2;
				}else if($bus_afecta["afecta"] == "Aporte"){
					$id_afecta = 4;
				}else{
					$id_afecta = 3;
				}
				//if ($bus_consulta_asociado["idconcepto"]== 43){
					//echo "CANTIDAD ".$cantidad_numero;
				//}
				if($total != 0){
				//if($id_afecta != 3){
					if($num_consulta == 0){
						$sql_guardar_total= mysql_query("insert into relacion_generar_nomina(idgenerar_nomina,
															 idtrabajador,
															 idconcepto,
															 tabla,
															 total,
															 cantidad)VALUES('".$idgenerar_nomina."',
																			'".$idtrabajador."',
																			'".$bus_consulta_asociado["idconcepto"]."',
																			'".$bus_consulta_asociado["tabla"]."',
																			'".$total."',
																			'".$cantidad_numero."')")or die("2222".mysql_error());

					}else{
						$sql_guardar_total= mysql_query("update relacion_generar_nomina set total = '".$total."'
												where idgenerar_nomina = '".$idgenerar_nomina."'
												and idtrabajador = '".$idtrabajador."'
												and idconcepto = '".$bus_consulta_asociado["idconcepto"]."'
												and tabla = '".$bus_consulta_asociado["tabla"]."'")or die("4444".mysql_error());

					}
				//}

				$cantidad_numero=0;
				if($bus_consulta_asociado["tabla"] == "conceptos_nomina"){
					$sql_consulta_concepto = mysql_query("select * from conceptos_nomina
														 	where idconceptos_nomina = '".$bus_consulta_asociado["idconcepto"]."'
															and aplica_prestaciones = 'si'");

                    $num_consulta_concepto = mysql_num_rows($sql_consulta_concepto);
                    $columna_prestaciones = mysql_fetch_array($sql_consulta_concepto);
					if($num_consulta_concepto > 0){
						if($estado == "Procesado"){
							$sql_generar_nomina = mysql_query("select rpn.desde,
																		rpn.hasta
																			FROM
																		generar_nomina gn,
																		periodos_nomina pn,
																		rango_periodo_nomina rpn
																			WHERE
																		gn.idgenerar_nomina = '".$idgenerar_nomina."'
																		and rpn.idrango_periodo_nomina = gn.idperiodo
																		group by desde")or die(mysql_error());
							$bus_generar_nomina = mysql_fetch_array($sql_generar_nomina);


							list($anioPrestaciones, $mesPrestaciones, $diaPrestaciones) = explode("-", $bus_generar_nomina["desde"]);


							$sql_consulta_prestaciones = mysql_query("select * from tabla_prestaciones where anio = '".$anioPrestaciones."'
																							and mes = '".$mesPrestaciones."'
																							and idtrabajador = '".$idtrabajador."'");
							$num_consulta_prestaciones = mysql_num_rows($sql_consulta_prestaciones);

							if($num_consulta_prestaciones == 0){
		                        if ($columna_prestaciones["columna_prestaciones"]=='sueldo') {
		                            $sql_ingresar = mysql_query("insert into tabla_prestaciones(anio,
																						mes,
																						sueldo,
																						idtrabajador,
																						usuario,
																						fechayhora,
																						pc)VALUES('" . $anioPrestaciones . "',
																									'" . $mesPrestaciones . "',
																									'" . $total . "',
																									'" . $idtrabajador . "',
																									'" . $login . "',
																									'" . $fh . "',
																									'" . $pc . "')") or die(mysql_error());
		                        }elseif ($columna_prestaciones["columna_prestaciones"]=='oc') {
		                            $sql_ingresar = mysql_query("insert into tabla_prestaciones(anio,
																						mes,
																						otros_complementos,
																						idtrabajador,
																						usuario,
																						fechayhora,
																						pc)VALUES('" . $anioPrestaciones . "',
																									'" . $mesPrestaciones . "',
																									'" . $total . "',
																									'" . $idtrabajador . "',
																									'" . $login . "',
																									'" . $fh . "',
																									'" . $pc . "')") or die(mysql_error());
		                        }elseif ($columna_prestaciones["columna_prestaciones"]=='bv') {
		                            $sql_ingresar = mysql_query("insert into tabla_prestaciones(anio,
																						mes,
																						bono_vacacional,
																						idtrabajador,
																						usuario,
																						fechayhora,
																						pc)VALUES('" . $anioPrestaciones . "',
																									'" . $mesPrestaciones . "',
																									'" . $total . "',
																									'" . $idtrabajador . "',
																									'" . $login . "',
																									'" . $fh . "',
																									'" . $pc . "')") or die(mysql_error());
		                        }elseif ($columna_prestaciones["columna_prestaciones"]=='bfa') {
		                            $sql_ingresar = mysql_query("insert into tabla_prestaciones(anio,
																						mes,
																						bono_fin_anio,
																						idtrabajador,
																						usuario,
																						fechayhora,
																						pc)VALUES('" . $anioPrestaciones . "',
																									'" . $mesPrestaciones . "',
																									'" . $total . "',
																									'" . $idtrabajador . "',
																									'" . $login . "',
																									'" . $fh . "',
																									'" . $pc . "')") or die(mysql_error());
		                        }

							}else{
								$bus_consulta_prestaciones = mysql_fetch_array($sql_consulta_prestaciones);
		                        if ($columna_prestaciones["columna_prestaciones"]=='sueldo') {
		                            $sql_actualizar = mysql_query("update tabla_prestaciones set sueldo = sueldo + '" . $total . "'
															  							where idtabla_prestaciones = '" . $bus_consulta_prestaciones["idtabla_prestaciones"] . "'");
		                        }elseif ($columna_prestaciones["columna_prestaciones"]=='oc') {
		                            $sql_actualizar = mysql_query("update tabla_prestaciones set otros_complementos = otros_complementos + '" . $total . "'
															  							where idtabla_prestaciones = '" . $bus_consulta_prestaciones["idtabla_prestaciones"] . "'");
		                        }elseif ($columna_prestaciones["columna_prestaciones"]=='bv') {
		                            $sql_actualizar = mysql_query("update tabla_prestaciones set bono_vacacional = bono_vacacional + '" . $total . "'
															  							where idtabla_prestaciones = '" . $bus_consulta_prestaciones["idtabla_prestaciones"] . "'");
		                        }elseif ($columna_prestaciones["columna_prestaciones"]=='bfa') {
		                            $sql_actualizar = mysql_query("update tabla_prestaciones set bono_fin_anio = bono_fin_anio + '" . $total . "'
															  							where idtabla_prestaciones = '" . $bus_consulta_prestaciones["idtabla_prestaciones"] . "'");
		                        }
							}
						}
					}
				}


			//echo eval("echo $formula;")." ";
				//if(eval("echo $formula;")){echo "si";}else{echo "no";}

			$conceptos[$k] = array($bus_consulta_asociado["tabla"], $bus_consulta_asociado["idconcepto"], $total, $idtrabajador,$bus_trabajador["idcategoria_programatica"]);
			$k++;
			$total = 0;

			}

			}

			//echo "AQUIII";
			//var_dump($conceptos);

	//echo "AQUI";
	}
	//CULMINA CICLO CON LOS TRABAJADORES DE LA NOMINA




	/// GUARDO LOS DATOS GENERALES DE LA CERTIFICACION
	$sql_generar_nomina = mysql_query("select tn.idtipo_documento,
									  gn.descripcion,
									  gn.idbeneficiarios
												  				from
												  			generar_nomina gn,
															tipo_nomina tn
																where
															gn.idgenerar_nomina = '".$idgenerar_nomina."'
															and tn.idtipo_nomina =  gn.idtipo_nomina")or die("AQUIIIII".mysql_error());
	$bus_generar_nomina = mysql_fetch_array($sql_generar_nomina);

	registra_transaccion('Se genero la nomina ('.$idgenerar_nomina.')',$login,$fh,$pc,'nomina',$conexion_db);

	$idtipo_documento = $bus_generar_nomina["idtipo_documento"];


	if($idcertificacion == "" || $idcertificacion == 0){
		$sql_cargar_certificacion = mysql_query("insert into orden_compra_servicio(tipo,
																			   fecha_elaboracion,
																			   idbeneficiarios,
																			   idcategoria_programatica,
																			   anio,
																			   idfuente_financiamiento,
																			   idtipo_presupuesto,
																			   idordinal,
																			   justificacion,
																			   estado,
																			   ubicacion,
																			   status,
																			   usuario,
																			   fechayhora)
																							VALUES('".$idtipo_documento."',
																						   			'".date("Y-m-d")."',
																									'".$bus_generar_nomina["idbeneficiarios"]."',
																									'".$idcentro_costo_fijo."',
																									'".$anio."',
																									'".$idfuente_financiamiento."',
																									'".$idtipo_presupuesto."',
																									'0',
																									'".$bus_generar_nomina["descripcion"]."',
																									'elaboracion',
																									'0',
																									'a',
																									'".$login."',
																									'".$fh."'
																									)")or die("ERROR CREANDO LA ORDEN DE COMPRA: ".mysql_error());
		$idcertificacion = mysql_insert_id();

	//echo "certi".$idcertificacion;
	/// GUARDO LOS DATOS GENERALES DE LA CERTIFICACION

		$sql_actualizar  = mysql_query("update generar_nomina set
											idorden_compra_servicio = '".$idcertificacion."'
												where
											idgenerar_nomina = '".$idgenerar_nomina."'");

		$sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$idtipo_documento."'");
		$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);

		if ($bus_cuentas_contables["tabla_debe"] != '' and $bus_cuentas_contables["idcuenta_debe"] != 0 and $bus_cuentas_contables["tabla_haber"] != '' and $bus_cuentas_contables["idcuenta_haber"] != ''){
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
																				'2',
																				'".$bus_generar_nomina["descripcion"]."',
																				'compromiso',
																				'".$idcertificacion."',
																				'elaboracion',
																				'a',
																				'".$login."',
																				'".date("Y-m-d H:i:s")."',
																				'2')");

			if($sql_contable){
				$idasiento_contable = mysql_insert_id();
				$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_debe"]."',
																				'".$bus_cuentas_contables["idcuenta_debe"]."',
																				'debe')");
				$sql_cuenta_contable_haber = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_haber"]."',
																				'".$bus_cuentas_contables["idcuenta_haber"]."',
																				'haber')");

			}
		}

	}

	foreach($conceptos as $con){



		if($con[0] == "conceptos_nomina"){
			//echo "EL MONTO TOTAL ES: ".$con[2];
			$sql_concepto = mysql_query("select * from conceptos_nomina where idconceptos_nomina = '".$con[1]."'")or die("tttttt".mysql_error());
			$bus_concepto = mysql_fetch_array($sql_concepto);

			$tipo_concepto = $bus_concepto["tipo_concepto"];
			$idclasificador_presupuestario = $bus_concepto["idclasificador_presupuestario"];
			$idordinal = $bus_concepto["idordinal"];
			$idarticulos_servicios = $bus_concepto["idarticulos_servicios"];

			$codigo_concepto = $bus_concepto["codigo"];
			$descripcion_concepto = $bus_concepto["descripcion"];

            $sql_tipo_concepto = mysql_query("select * from tipo_conceptos_nomina where idconceptos_nomina = '".$bus_concepto["tipo_concepto"]."'");
            $bus_tipo_concepto = mysql_fetch_array($sql_tipo_concepto);

            if($bus_tipo_concepto["afecta"] == "Asignacion"){
                $tipo_concepto = 1;
            }else if($bus_tipo_concepto["afecta"] == "Deduccion"){
                $tipo_concepto = 2;
            }else if($bus_tipo_concepto["afecta"] == "Aporte"){
                $tipo_concepto = 4;
            }else{
                $tipo_concepto = 3;
            }

            //GUARDO LA CERTIFICACION PARA LOS APORTES PATRONALES

            if($tipo_concepto == 4){

               if($idcertificacion_aporte == "" || $idcertificacion_aporte == 0){
                    $sql_cargar_certificacion = mysql_query("insert into orden_compra_servicio(tipo,
																			   fecha_elaboracion,
																			   idbeneficiarios,
																			   idcategoria_programatica,
																			   anio,
																			   idfuente_financiamiento,
																			   idtipo_presupuesto,
																			   idordinal,
																			   justificacion,
																			   estado,
																			   ubicacion,
																			   status,
																			   usuario,
																			   fechayhora)
																							VALUES('".$idtipo_documento."',
																						   			'".date("Y-m-d")."',
																									'".$bus_generar_nomina["idbeneficiarios"]."',
																									'".$idcentro_costo_fijo."',
																									'".$anio."',
																									'".$idfuente_financiamiento."',
																									'".$idtipo_presupuesto."',
																									'0',
																									' APORTE PATRONAL - ".$bus_generar_nomina["descripcion"]."',
																									'elaboracion',
																									'0',
																									'a',
																									'".$login."',
																									'".$fh."')")or die("ERROR CREANDO LA ORDEN DE COMPRA: ".mysql_error());
                    $idcertificacion_aporte = mysql_insert_id();

                    //echo "certi".$idcertificacion;
                    /// GUARDO LOS DATOS GENERALES DE LA CERTIFICACION


                    $sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$idtipo_documento."'");
                    $bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);

                    if ($bus_cuentas_contables["tabla_debe"] != '' and $bus_cuentas_contables["idcuenta_debe"] != 0 and $bus_cuentas_contables["tabla_haber"] != '' and $bus_cuentas_contables["idcuenta_haber"] != ''){
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
																				'2',
																				'".$bus_generar_nomina["descripcion"]."',
																				'compromiso',
																				'".$idcertificacion_aporte."',
																				'elaboracion',
																				'a',
																				'".$login."',
																				'".date("Y-m-d H:i:s")."',
																				'2')");

                        if($sql_contable){
                            $idasiento_contable = mysql_insert_id();
                            $sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_debe"]."',
																				'".$bus_cuentas_contables["idcuenta_debe"]."',
																				'debe')");
                            $sql_cuenta_contable_haber = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_haber"]."',
																				'".$bus_cuentas_contables["idcuenta_haber"]."',
																				'haber')");

                        }
                    }

                }
            }


            //VALIDO QUE EL CONCEPTO ESTE EN LA TABLA DE ARTICULOS SERVICIOS
			if($idarticulos_servicios == 0){

				$sql_ingresar_articulo = mysql_query("insert into articulos_servicios(codigo,
																				  tipo,
																				  descripcion,
																				  idunidad_medida,
																				  idramo_articulo,
																				  idclasificador_presupuestario,
																				  idordinal,
																				  idimpuestos,
																				  exento,
																				  status,
																				  tipo_concepto,
																				  usuario,
																				  fechayhora
																				 )VALUES('".$codigo_concepto."',
																				  					'".$idtipo_documento."',
																									'".$descripcion_concepto."',
																									'7',
																									'12',
																						'".$idclasificador_presupuestario."',
																									'".$idordinal."',
																									'1',
																									'1',
																									'a',
																									'".$tipo_concepto."',
																									'".$login."',
																									'".$fh."')")or die("HOLAAAAA".mysql_error());

				$id = mysql_insert_id();
				//echo "ESTE ES EL ID:  ".$id;
				if($id != 0){
					$sql_actualizar = mysql_query("update conceptos_nomina set idarticulos_servicios = '".$id."' where idconceptos_nomina = '".$con[1]."'")or die("wwwww".mysql_error());
					if ($idcentro_costo_fijo == 0or $idcentro_costo_fijo == ''){
						/*
						$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica
																  			from
																		trabajador tr,
																		niveles_organizacionales no
																			where
																		tr.idtrabajador = '".$con[3]."'
																		and no.idniveles_organizacionales = tr.centro_costo")or die("eeeeee".mysql_error());
						$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
						$idcentro_costo = $bus_categoria_programatica["idcategoria_programatica"];*/
						$idcentro_costo = $con[4];
					}else{
						$idcentro_costo = $idcentro_costo_fijo;
					}
					ingresarMaterial($id, $idcertificacion, $idcentro_costo, 1, $con[2], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal,$anio,$idcertificacion_aporte);
				}

				//}// FIN DE SI ES PATRONAL


            }else{
					$id = $idarticulos_servicios;
					/*$sql_actualizar = mysql_query("update conceptos_nomina set idarticulos_servicios = '".$id."' where idconceptos_nomina = '".$con[1]."'")or die("aquiii".mysql_error());
					$sql_actualizar = mysql_query("update articulos_servicios set
												  				idclasificador_presupuestario = '".$idclasificador_presupuestario."'
																where idarticulos_servicios = '".$id."'");
					//echo "update conceptos_nomina set idarticulos_servicios = '".$id."' where idconceptos_nomina = '".$con[1]."'";
					*/
					if ($idcentro_costo_fijo == 0 or $idcentro_costo_fijo == ''){
						/*
						$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica
																  			from
																		trabajador tr,
																		niveles_organizacionales no
																			where
																		tr.idtrabajador = '".$con[3]."'
																		and no.idniveles_organizacionales = tr.centro_costo")or die("eeeeee".mysql_error());
						$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
						$idcentro_costo = $bus_categoria_programatica["idcategoria_programatica"];
						*/
						$idcentro_costo = $con[4];
					}else{
						$idcentro_costo = $idcentro_costo_fijo;
					}
					if($tipo_concepto == 4){
						$sql_busqueda = mysql_query("select * from articulos_compra_servicio
																where
																idarticulos_servicios = '".$id."'
																and idcategoria_programatica = '".$idcentro_costo."'
																and idorden_compra_servicio = '".$idcertificacion_aporte."'")or die("ertyuui".mysql_error());
					}
					if($tipo_concepto == 1 || $tipo_concepto == 2){
						$sql_busqueda = mysql_query("select * from articulos_compra_servicio
																where
																idarticulos_servicios = '".$id."'
																and idcategoria_programatica = '".$idcentro_costo."'
																and idorden_compra_servicio = '".$idcertificacion."'")or die("ertyuui".mysql_error());
					}
					$num_busqueda = mysql_num_rows($sql_busqueda);
					//echo "Tipo Concepto....".$tipo_concepto."  ".$con[2]." - ";
					if($tipo_concepto == 1 || $tipo_concepto == 2){
						if($num_busqueda > 0){
							$bus_busqueda = mysql_fetch_array($sql_busqueda);
							actualizarPrecioCantidad($id, $idcertificacion, $idcentro_costo, 1, $con[2], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal, $bus_busqueda["idarticulos_compra_servicio"], $con[3],$anio,$idcertificacion_aporte);
						}else{
							ingresarMaterial($id, $idcertificacion, $idcentro_costo, 1, $con[2], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal,$anio,$idcertificacion_aporte);
						}
					}
					if($tipo_concepto == 4){
						if($num_busqueda > 0){
							$bus_busqueda = mysql_fetch_array($sql_busqueda);
							actualizarPrecioCantidad($id, $idcertificacion_aporte, $idcentro_costo, 1, $con[2], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal, $bus_busqueda["idarticulos_compra_servicio"], $con[3],$anio,$idcertificacion_aporte);
						}else{
							ingresarMaterial($id, $idcertificacion_aporte, $idcentro_costo, 1, $con[2], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal,$anio,$idcertificacion_aporte);
						}
					}
				}




			}else if($con[0] == "constantes_nomina"){
				// AQUI VA EL TEXTO SI ES UNA CONSTANTE

				$sql_constante = mysql_query("select * from constantes_nomina where idconstantes_nomina = '".$con[1]."'")or die("AQUIII".mysql_error());
				$bus_constante = mysql_fetch_array($sql_constante);


				$idclasificador_presupuestario = $bus_constante["idclasificador_presupuestario"];
				$idordinal = $bus_constante["idordinal"];
				$idarticulos_servicios = $bus_constante["idarticulos_servicios"];
				$codigo_concepto = $bus_constante["codigo"];
				$descripcion_concepto = $bus_constante["descripcion"];

                $sql_tipo_concepto = mysql_query("select * from tipo_conceptos_nomina where idconceptos_nomina = '".$bus_constante["afecta"]."'");
                $bus_tipo_concepto = mysql_fetch_array($sql_tipo_concepto);

                if($bus_tipo_concepto["afecta"] == "Asignacion"){
                    $tipo_constante = 1;
                }else if($bus_tipo_concepto["afecta"] == "Deduccion"){
                    $tipo_constante = 2;
                }else if($bus_tipo_concepto["afecta"] == "Aporte"){
                    $tipo_constante = 4;
                }else{
                    $tipo_constante = 3;
                }


            //GUARDO LA CERTIFICACION PARA LOS APORTES PATRONALES

				if($tipo_constante == 4){

					if($idcertificacion_aporte == "" || $idcertificacion_aporte == 0){
						$sql_cargar_certificacion = mysql_query("insert into orden_compra_servicio(tipo,
																			   fecha_elaboracion,
																			   idbeneficiarios,
																			   idcategoria_programatica,
																			   anio,
																			   idfuente_financiamiento,
																			   idtipo_presupuesto,
																			   idordinal,
																			   justificacion,
																			   estado,
																			   ubicacion,
																			   status,
																			   usuario,
																			   fechayhora)
																							VALUES('".$idtipo_documento."',
																						   			'".date("Y-m-d")."',
																									'".$bus_generar_nomina["idbeneficiarios"]."',
																									'".$idcentro_costo_fijo."',
																									'".$anio."',
																									'".$idfuente_financiamiento."',
																									'".$idtipo_presupuesto."',
																									'0',
																									' APORTE PATRONAL - ".$bus_generar_nomina["descripcion"]."',
																									'elaboracion',
																									'0',
																									'a',
																									'".$login."',
																									'".$fh."')")or die("ERROR CREANDO LA ORDEN DE COMPRA: ".mysql_error());
                    $idcertificacion_aporte = mysql_insert_id();

                    //echo "certi".$idcertificacion;
                    /// GUARDO LOS DATOS GENERALES DE LA CERTIFICACION


                    $sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$idtipo_documento."'");
                    $bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);

                    if ($bus_cuentas_contables["tabla_debe"] != '' and $bus_cuentas_contables["idcuenta_debe"] != 0 and $bus_cuentas_contables["tabla_haber"] != '' and $bus_cuentas_contables["idcuenta_haber"] != ''){
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
																				'2',
																				'".$bus_generar_nomina["descripcion"]."',
																				'compromiso',
																				'".$idcertificacion_aporte."',
																				'elaboracion',
																				'a',
																				'".$login."',
																				'".date("Y-m-d H:i:s")."',
																				'2')");

                        if($sql_contable){
                            $idasiento_contable = mysql_insert_id();
                            $sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_debe"]."',
																				'".$bus_cuentas_contables["idcuenta_debe"]."',
																				'debe')");
                            $sql_cuenta_contable_haber = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_haber"]."',
																				'".$bus_cuentas_contables["idcuenta_haber"]."',
																				'haber')");

	                        }
	                    }

	                }
	            }

			if($idarticulos_servicios == 0){

					$sql_ingresar_articulo = mysql_query("insert into articulos_servicios(codigo,
																				  tipo,
																				  descripcion,
																				  idunidad_medida,
																				  idramo_articulo,
																				  idclasificador_presupuestario,
																				  idordinal,
																				  idimpuestos,
																				  exento,
																				  status,
																				  tipo_concepto,
																				  usuario,
																				  fechayhora)VALUES('".$codigo_concepto."',
																				  					'".$idtipo_documento."',
																									'".$descripcion_concepto."',
																									'7',
																									'12',
																									'".$idclasificador_presupuestario."',
																									'".$idordinal."',
																									'1',
																									'1',
																									'a',
																									'".$tipo_constante."',
																									'".$login."',
																									'".$fh."')")or die("ERROR EN EL INSERT".mysql_error);

				$id = mysql_insert_id();
				if($id != 0){
					$sql_actualizar = mysql_query("update constantes_nomina set idarticulos_servicios = '".$id."' where idconstantes_nomina = '".$con[1]."'")or die("ERROR".mysql_error());
					if ($idcentro_costo_fijo == 0 or $idcentro_costo_fijo == ''){
						/*
						$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica
																  			from
																		trabajador tr,
																		niveles_organizacionales no
																			where
																		tr.idtrabajador = '".$con[3]."'
																		and no.idniveles_organizacionales = tr.centro_costo")or die("eeeeee".mysql_error());
						$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
						$idcentro_costo = $bus_categoria_programatica["idcategoria_programatica"];
						*/
						$idcentro_costo = $con[4];
					}else{
						$idcentro_costo = $idcentro_costo_fijo;
					}


					$sql_consulta_articulos = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id."'");
					$bus_consulta_articulos = mysql_fetch_array($sql_consulta_articulos);

					if($bus_consulta_articulos["tipo_concepto"] == 2 && $bus_consulta_articulos["tipo_concepto"] == 1){
						ingresarMaterial($id, $idcertificacion, $idcentro_costo, 1, $con[2], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal,$anio,$idcertificacion_aporte);
					}
					if($bus_consulta_articulos["tipo_concepto"] == 4){
						ingresarMaterial($id, $idcertificacion_aporte, $idcentro_costo, 1, $con[2], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal,$anio,$idcertificacion_aporte);
					}

				}

				//}

			}else{

					$id = $idarticulos_servicios;
					/*$sql_actualizar = mysql_query("update constantes_nomina set
												  				idarticulos_servicios = '".$id."' where idconstantes_nomina = '".$con[1]."'")or die("AQUIIII".mysql_error());
					$sql_actualizar = mysql_query("update articulos_servicios set
												  				idclasificador_presupuestario = '".$idclasificador_presupuestario."'
																where idarticulos_servicios = '".$id."'")or die("qqqqqqq".mysql_error());
					*/
					//echo "update conceptos_nomina set idarticulos_servicios = '".$id."' where idconceptos_nomina = '".$con[1]."'";
					if ($idcentro_costo_fijo == 0 or $idcentro_costo_fijo == ''){
						/*
						$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica
																  			from
																		trabajador tr,
																		niveles_organizacionales no
																			where
																		tr.idtrabajador = '".$con[3]."'
																		and no.idniveles_organizacionales = tr.centro_costo")or die("eeeeee".mysql_error());
						$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
						$idcentro_costo = $bus_categoria_programatica["idcategoria_programatica"];
						*/
						$idcentro_costo = $con[4];
					}else{
						$idcentro_costo = $idcentro_costo_fijo;
					}
					if($tipo_constante==1 || $tipo_constante ==2){
						$sql_buscar = mysql_query("select * from articulos_compra_servicio
																			where
																			idarticulos_servicios = '".$id."'
																			and idcategoria_programatica = '".$idcentro_costo."'
																			and idorden_compra_servicio = '".$idcertificacion."'")or die("qqqqqq".mysql_error());
						$num_buscar= mysql_num_rows($sql_buscar);
					}
					if($tipo_constante == 4){
						$sql_buscar = mysql_query("select * from articulos_compra_servicio
																			where
																			idarticulos_servicios = '".$id."'
																			and idcategoria_programatica = '".$idcentro_costo."'
																			and idorden_compra_servicio = '".$idcertificacion_aporte."'")or die("qqqqqq".mysql_error());
							$num_buscar= mysql_num_rows($sql_buscar);
					}


					//$sql_consulta_articulos = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id."'")or die(mysql_error());
					//$bus_consulta_articulos = mysql_fetch_array($sql_consulta_articulos);

					if($tipo_constante != 3 && $tipo_constante != 4){
						if($num_buscar > 0){
							//echo "entro constante ".$bus_consulta_articulos["tipo_concepto"]." id art ".$id;
							$bus_busqueda = mysql_fetch_array($sql_buscar);
							actualizarPrecioCantidad($id, $idcertificacion, $idcentro_costo, 1, $con[2], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal, $bus_busqueda["idarticulos_compra_servicio"], $con[3],$anio,$idcertificacion_aporte);
						}else{
							ingresarMaterial($id, $idcertificacion, $idcentro_costo, 1, $con[2], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal,$anio,$idcertificacion_aporte);
						}
					}
					if($tipo_constante == 4){
						if($num_buscar > 0){
							//echo "entro constante ".$bus_consulta_articulos["tipo_concepto"]." id art ".$id;
							$bus_busqueda = mysql_fetch_array($sql_buscar);
							actualizarPrecioCantidad($id, $idcertificacion_aporte, $idcentro_costo, 1, $con[2], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal, $bus_busqueda["idarticulos_compra_servicio"], $con[3],$anio,$idcertificacion_aporte);
						}else{
							ingresarMaterial($id, $idcertificacion_aporte, $idcentro_costo, 1, $con[2], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal,$anio,$idcertificacion_aporte);
						}
					}

			}
			}
		}

		if($estado == "Procesado"){

			if($idcertificacion_aporte == ''){
				$idcertificacion_aporte=0;
			}else{
				$sql_actualizar_certificacion = mysql_query("update orden_compra_servicio
														set total = sub_total - exento
														where idorden_compra_servicio = '".$idcertificacion_aporte."'");
			}
			$sql_actualizar_certificacion = mysql_query("update orden_compra_servicio
													set total = sub_total - exento
													where idorden_compra_servicio = '".$idcertificacion."'");
			/*$sql_actualizar  = mysql_query("update generar_nomina
						   				set estado='procesado',
										fecha_procesado = '".date("Y-m-d")."',
										idorden_compra_servicio = '".$idcertificacion."',
										idorden_compra_servicio_aporte = '".$idcertificacion_aporte."'
											where
										idgenerar_nomina = '".$idgenerar_nomina."'");
			*/
			$sql_actualizar  = mysql_query("update generar_nomina
						   				set estado='procesado',
										fecha_procesado = '".date("Y-m-d")."',
										idorden_compra_servicio_aporte = '".$idcertificacion_aporte."'
											where
										idgenerar_nomina = '".$idgenerar_nomina."'");

			echo "exito"."|.|".$idcertificacion."|.|".$idcertificacion_aporte;

		}else{
			if($idcertificacion_aporte == ''){
				$idcertificacion_aporte=0;
			}else{
				$sql_actualizar_certificacion = mysql_query("update orden_compra_servicio
														set total = sub_total - exento
														where idorden_compra_servicio = '".$idcertificacion_aporte."'");
			}

			$sql_actualizar_certificacion = mysql_query("update orden_compra_servicio
														set total = sub_total - exento
														where idorden_compra_servicio = '".$idcertificacion."'");

			/*
			$sql_actualizar  = mysql_query("update generar_nomina set
											idorden_compra_servicio = '".$idcertificacion."',
											idorden_compra_servicio_aporte = '".$idcertificacion_aporte."'
												where
											idgenerar_nomina = '".$idgenerar_nomina."'");
			*/
			$sql_actualizar  = mysql_query("update generar_nomina set
											idorden_compra_servicio_aporte = '".$idcertificacion_aporte."'
												where
											idgenerar_nomina = '".$idgenerar_nomina."'");

			echo $idcertificacion."|.|".$idcertificacion_aporte;
		}

	}






if($ejecutar == "procesarCertificacion"){
	$sql_consultar = mysql_query("select * from generar_nomina where idgenerar_nomina = '".$idgenerar_nomina."'");
	$bus_consultar = mysql_fetch_array($sql_consultar);
	registra_transaccion('Se proceso la certificacion de la nomina ('.$idgenerar_nomina.')',$login,$fh,$pc,'nomina',$conexion_db);
	if($tipo_generar == 'N'){
		$resultado = procesarCertificacion($bus_consultar["idorden_compra_servicio"]);
	}
	if($tipo_generar == 'A'){
		$resultado = procesarCertificacion($bus_consultar["idorden_compra_servicio_aporte"]);
	}
	echo $resultado;
}





if($ejecutar == "consultarEstadoCertificacion"){
	$sql_consulta = mysql_query("select * from orden_compra_servicio
									where idorden_compra_servicio = '".$idorden_compra_servicio."'")or die(mysql_error());
	$bus_consulta = mysql_fetch_array($sql_consulta)or die(mysql_error());

	$enviar = $bus_consulta["estado"]."|.|".$bus_consulta["numero_orden"];
	if($idorden_compra_servicio_aporte != 0 && $idorden_compra_servicio_aporte != ''){
		$sql_consulta = mysql_query("select * from orden_compra_servicio
									where idorden_compra_servicio = '".$idorden_compra_servicio_aporte."'")or die(mysql_error());
		$bus_consulta = mysql_fetch_array($sql_consulta)or die(mysql_error());
		$enviar = $enviar."|.|".$bus_consulta["estado"]."|.|".$bus_consulta["numero_orden"];
	}else{
		$enviar = $enviar."|.|0|.|0";
	}
	echo $enviar;
}







function procesarCertificacion($id_orden_compra){
	$sql_compra_servicio = mysql_query("select *
											from
										orden_compra_servicio
											where
										idorden_compra_servicio = ".$id_orden_compra."")or die("SELECT DE L ORDEN DE COMPRA: ".mysql_error());
	$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);

	if($bus_compra_servicio["estado"] == 'anulado'){

		$anio = $bus_compra_servicio["anio"];
		$idfuente_financiamiento = $bus_compra_servicio["idfuente_financiamiento"];
		$idtipo_presupuesto = $bus_compra_servicio["idtipo_presupuesto"];
		$idcentro_costo_fijo = $bus_compra_servicio["idcategoria_programatica"];
		$idtipo_documento = $bus_compra_servicio["tipo"];

		$sql_cargar_certificacion = mysql_query("insert into orden_compra_servicio(tipo,
														   fecha_elaboracion,
														   idbeneficiarios,
														   idcategoria_programatica,
														   anio,
														   idfuente_financiamiento,
														   idtipo_presupuesto,
														   idordinal,
														   justificacion,
														   estado,
														   ubicacion,
														   status,
														   usuario,
														   fechayhora)
																		VALUES('".$bus_compra_servicio["tipo"]."',
																	   			'".$bus_compra_servicio["fecha_elaboracion"]."',
																				'".$bus_compra_servicio["idbeneficiarios"]."',
																				'".$bus_compra_servicio["idcategoria_programatica"]."',
																				'".$bus_compra_servicio["anio"]."',
																				'".$bus_compra_servicio["idfuente_financiamiento"]."',
																				'".$bus_compra_servicio["idtipo_presupuesto"]."',
																				'0',
																				'".$bus_compra_servicio["justificacion"]."',
																				'elaboracion',
																				'0',
																				'a',
																				'".$login."',
																				'".$fh."')")or die("ERROR CREANDO LA ORDEN DE COMPRA: ".mysql_error());
		$idcertificacion = mysql_insert_id();

		$sql_actualizar_nomina = mysql_query("update generar_nomina set idorden_compra_servicio_aporte='".$idcertificacion."'
												where idorden_compra_servicio_aporte ='".$id_orden_compra."'");

		$sql_articulos_compra_servicio = mysql_query("select *
														from
													articulos_compra_servicio
														where
													idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR EN LA CONSULTA A LOS ARTICULOS: ".mysql_error());



		while($bus_articulos_compra_servicio = mysql_fetch_array($sql_articulos_compra_servicio)){
			$sql = mysql_query("insert into articulos_compra_servicio (idorden_compra_servicio,
																			idarticulos_servicios,
																			idcategoria_programatica,
																			cantidad,
																			precio_unitario,
																			porcentaje_impuesto,
																			impuesto,
																			total,
																			exento,
																			status,
																			usuario,
																			fechayhora,
																			idpartida_impuesto)values(
																			'" . $idcertificacion . "',
																			'" . $bus_articulos_compra_servicio["idarticulos_servicios"] . "',
																			'" . $bus_articulos_compra_servicio["idcategoria_programatica"] . "',
																			'" . $bus_articulos_compra_servicio["cantidad"] . "',
																			'" . $bus_articulos_compra_servicio["precio_unitario"] . "',
																			'" . $bus_articulos_compra_servicio["porcentaje_impuesto"] . "',
																			'" . $bus_articulos_compra_servicio["impuesto"] . "',
																			'" . $bus_articulos_compra_servicio["total"] . "',
																			'" . $bus_articulos_compra_servicio["exento"] . "',
																			'a',
																			'" . $login . "',
																			'" . date("Y-m-d H:i:s") . "',
																			'" . $id_partida_impuesto . "'
																			)") or die("AQUIIIIIIII " . mysql_error());
		}


		$sql_partidas_compra_servicio = mysql_query("select *
														from
													partidas_orden_compra_servicio
														where
													idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR EN LA CONSULTA A LOS ARTICULOS: ".mysql_error());

		while($bus_partidas_compra_servicio = mysql_fetch_array($sql_partidas_compra_servicio)){

			$sql_partida = mysql_query("insert into partidas_orden_compra_servicio
																			(estado,
																				monto,
																				monto_original,
																				idmaestro_presupuesto,
																				idorden_compra_servicio
																				)VALUES(
																			'".$bus_partidas_compra_servicio["estado"]."',
																			'".$bus_partidas_compra_servicio["monto"]."',
																			'".$bus_partidas_compra_servicio["monto"]."',
																			'".$bus_partidas_compra_servicio["idmaestro_presupuesto"]."',
																			'".$idcertificacion."')")or die("15: ".mysql_error());

		}


		$sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$idtipo_documento."'");
			$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);



			if ($bus_cuentas_contables["tabla_debe"] != '' and $bus_cuentas_contables["idcuenta_debe"] != 0 and $bus_cuentas_contables["tabla_haber"] != '' and $bus_cuentas_contables["idcuenta_haber"] != ''){
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
																				'".$bus_compra_servicio["idfuente_financiamiento"]."',
																				'".$bus_compra_servicio["justificacion"]."',
																				'compromiso',
																				'".$idcertificacion."',
																				'elaboracion',
																				'a',
																				'".$login."',
																				'".date("Y-m-d H:i:s")."',
																				'2')");

				if($sql_contable){
					$idasiento_contable = mysql_insert_id();
					$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																		tabla,
																		idcuenta,
																		afecta
																			)values(
																					'".$idasiento_contable."',
																					'".$bus_cuentas_contables["tabla_debe"]."',
																					'".$bus_cuentas_contables["idcuenta_debe"]."',
																					'debe')");
					$sql_cuenta_contable_haber = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																		tabla,
																		idcuenta,
																		afecta
																			)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_haber"]."',
																				'".$bus_cuentas_contables["idcuenta_haber"]."',
																				'haber')");
				}
			}


		$id_orden_compra = $idcertificacion;
	}


	$sql_actualizar_compra_servicio = mysql_query("update orden_compra_servicio
														set exento = 0,
														sub_total = 0
														where
							idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR EN LA CONSULTA A LOS ARTICULOS: ".mysql_error());

	$sql_partida = mysql_query("update partidas_orden_compra_servicio set
														monto = 0
														where idorden_compra_servicio = '".$id_orden_compra."'")or die("ERROR ACTUALIZANDO LAS PARTIDAS CON SOBREGIRO: ".mysql_error());

	///**********************************************************************************************


	$sql_articulos_compra_servicio = mysql_query("select *
														from
													articulos_compra_servicio
														where
													idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR EN LA CONSULTA A LOS ARTICULOS: ".mysql_error());
	$sql_compra_servicio = mysql_query("select *
											from
										orden_compra_servicio
											where
										idorden_compra_servicio = ".$id_orden_compra."")or die("SELECT DE L ORDEN DE COMPRA: ".mysql_error());
	$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
	$anio = $bus_compra_servicio["anio"];
	$idfuente_financiamiento = $bus_compra_servicio["idfuente_financiamiento"];
	$idtipo_presupuesto = $bus_compra_servicio["idtipo_presupuesto"];
	$idcentro_costo_fijo = $bus_compra_servicio["idcategoria_programatica"];

	while($bus_articulos_compra_servicio = mysql_fetch_array($sql_articulos_compra_servicio)){

		//*************************************************************************************
		$sql_articulos_servicios = mysql_query("select *
													from
												articulos_servicios
													where
												idarticulos_servicios = '".$bus_articulos_compra_servicio["idarticulos_servicios"]."'");
		$bus_articulos_servicios =  mysql_fetch_array($sql_articulos_servicios);
		$idordinal = $bus_articulos_servicios["idordinal"];

		$id_clasificador_presupuestario = $bus_articulos_servicios["idclasificador_presupuestario"];
		if ($idcentro_costo_fijo == 0 or $idcentro_costo_fijo == ''){
			$id_categoria_programatica = $bus_articulos_compra_servicio["idcategoria_programatica"];
		}else{
			$id_categoria_programatica = $idcentro_costo_fijo;
		}

		$total_articulo_individual = $bus_articulos_compra_servicio["cantidad"] * $bus_articulos_compra_servicio["precio_unitario"];

		if($bus_articulos_servicios["tipo_concepto"] == 1){
			$monto_total = $total_articulo_individual;
			$exento = 0;
		}else if($bus_articulos_servicios["tipo_concepto"] == 3){
			$monto_total = 0;
			$exento = 0;
		}else if($bus_articulos_servicios["tipo_concepto"] == 4){
			$monto_total = $total_articulo_individual;
			$exento = 0;
		}else{
			$monto_total = 0;
			$exento = $total_articulo_individual;
		}



			$sql_actualizar_compra_servicio = mysql_query("update orden_compra_servicio
														set exento = exento + '".$exento."',
														sub_total = sub_total + '".$monto_total."'
														where
													idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR EN LA CONSULTA A LOS ARTICULOS: ".mysql_error());


			$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_compra."
														and tipo_movimiento = 'compromiso'");
				if (mysql_num_rows($sql_validar_asiento) > 0){

					$bus_asiento_contable = mysql_fetch_array($sql_validar_asiento);

					$sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'
																order by afecta");

					while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)){
						$actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = monto + '".$monto_total."'
																		where idcuentas_asiento_contable = '".$bus_cuentas_contables["idcuentas_asiento_contable"]."'");
					}
				}


			//echo $bus_articulos_comora_servicio["idarticulos_compra_servicio"];

			/*
			$sql2 = mysql_query("update articulos_compra_servicio set total = '".$monto_total."',
									  exento = '".$exento."',
									  precio_unitario = '".$bus_articulos_compra_servicio["precio_unitario"]."',
									  cantidad = ".$bus_articulos_compra_servicio["cantidad"]."
									  where
									  idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")
									  or die("error update articulos".mysql_error());
			*/

			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
			$sql = mysql_query("select * from articulos_compra_servicio
									where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."
									")or die("error todos los articulos".mysql_error());
			$bus = mysql_fetch_array($sql);

		if($bus_articulos_servicios["tipo_concepto"] == 1 || $bus_articulos_servicios["tipo_concepto"] == 4){

				$total_imputable = $monto_total;
				//$total_imputable = $total_imputable+$total_impuesto;
//*********************************************************************************
				//echo "año ".$anio." categoria ".$id_categoria_programatica." clasificadr ".$id_clasificador_presupuestario." fuente ".$idfuente_financiamiento." tipo ".$idtipo_presupuesto." ordinal ".$idordinal;

				$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = '".$anio."'
												and idcategoria_programatica = '".$id_categoria_programatica."'
												and idclasificador_presupuestario = '".$id_clasificador_presupuestario."'
												and idfuente_financiamiento = '".$idfuente_financiamiento."'
												and idtipo_presupuesto = '".$idtipo_presupuesto."'
												and idordinal = '".$idordinal."'")
												or die("ERROR SELECCIONANDO EL MAESTRO PARA LOS IMPUESTOS: ".mysql_error());

				$bus_maestro = mysql_fetch_array($sql_maestro);

				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				//echo "disponible ....".$bus_maestro["idRegistro"];
				if($total_imputable > $disponible){

					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro',
														monto = monto + '".$monto_total."'
														where idorden_compra_servicio = ".$id_orden_compra."
														and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")
														or die("ERROR ACTUALIZANDO LAS PARTIDAS CON SOBREGIRO: ".mysql_error());
					$estado = "sin disponibilidad";
				}else{
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible',
									monto = monto + '".$monto_total."'
									where idorden_compra_servicio = ".$id_orden_compra."
									and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")
									or die("ERROR ACTUALIZANDO LAS PARTIDAS DISPONIBLES: ".mysql_error());

					$estado = "aprobado";

				}
				//echo $bus_maestro["idRegistro"]." ".$estado;

		}else{
			$estado = "aprobado";
		}
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA

		$sql2 = mysql_query("update articulos_compra_servicio set estado = '".$estado."'
								where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")
								or die("ERRO ACTUALIZANDO EL ESTADO DE LOS ARTICULOS: ".mysql_error());


	//*****************************************************************************************
	}// CIERRE DEL WHILE DE CONSULTA DE LOS ARTICULOS DE LA ORDEN


	$sql_actualizar_compra_servicio = mysql_query("update orden_compra_servicio
														set total = sub_total - exento
														where
													idorden_compra_servicio = ".$id_orden_compra."")
													or die("ERROR EN LA CONSULTA A LOS ARTICULOS: ".mysql_error());


	$sql_articulos = mysql_query("select * from articulos_compra_servicio
												where idorden_compra_servicio = ".$id_orden_compra."")
												or die("ERROR SELECCIONANDO LOS ARTICULOS: ".mysql_error());
	$num_articulos = mysql_num_rows($sql_articulos);

	if($num_articulos != 0){
		$sql_orden_duplicados = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
		$bus_orden_duplicados = mysql_fetch_array($sql_orden_duplicados);
		if($bus_orden_duplicados["duplicados"] == 0){
			$sql_articulos = mysql_query("select * from articulos_compra_servicio
													where idorden_compra_servicio = ".$id_orden_compra."
													and (estado = 'rechazado' or estado = 'sin disponibilidad')")
													or die("ERROR SELECCIONANDO ARTICULOS COMPRA SERVICIOS: ".mysql_error());
		$num_articulos = mysql_num_rows($sql_articulos);

		if($num_articulos == 0){
			$sql_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where
														idorden_compra_servicio = ".$id_orden_compra."
														and (estado = 'rechazado' or estado = 'sin disponibilidad')")
												or die("ERROR SELECCIONANDO LA RELACION DE IMPUESTOS ORDENES COMPRAS: ".mysql_error());
			$num_impuestos = mysql_num_rows($sql_impuestos);

			if($num_impuestos == 0){
				$sql_actualizar_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR SELECCIONANDO LAS PARTIDAS DE LA ORDEN DE COMPRAS: ".mysql_error());
				while($bus_actualizar_partidas = mysql_fetch_array($sql_actualizar_partidas)){

					$sql_maestro = mysql_query("update maestro_presupuesto set
															total_compromisos = total_compromisos + ".$bus_actualizar_partidas["monto"]."
															where idRegistro = ".$bus_actualizar_partidas["idmaestro_presupuesto"]."")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO: ".mysql_error());

					$sql_consulta_ordinal = mysql_query("select * from ordinal where codigo = '0000'")or die("ERROR CONSULTANDO EL ORDINAL NO APLICA".mysql_error());
					$bus_consulta_ordinal = mysql_fetch_array($sql_consulta_ordinal);

					$sql_consultar_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '".$bus_actualizar_partidas["idmaestro_presupuesto"]."' and idordinal != '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO EL MAESTRO 1:".mysql_error());
					$num_consulta_maestro = mysql_num_rows($sql_consultar_maestro);
					if($num_consulta_maestro != 0){
						$bus_consultar_maestro= mysql_fetch_array($sql_consultar_maestro);
						$sql_sub_espe = mysql_query("select * from maestro_presupuesto where
							idcategoria_programatica= '".$bus_consultar_maestro["idcategoria_programatica"]."'
						and idtipo_presupuesto = '".$bus_consultar_maestro["idtipo_presupuesto"]."'
						and idfuente_financiamiento = '".$bus_consultar_maestro["idfuente_financiamiento"]."'
						and idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."'
						and idordinal = '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO SUB ESPECIFICA".mysql_error());
						$num_sub_espe =mysql_num_rows($sql_sub_espe);
						if($num_sub_espe != 0){
							$bus_sub_epe = mysql_fetch_array($sql_sub_espe);
							$sql_maestro = mysql_query("update maestro_presupuesto set
															total_compromisos = total_compromisos + ".$bus_actualizar_partidas["monto"]."
															where idRegistro = '".$bus_sub_epe["idmaestro_presupuesto"]."'")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 2: ".mysql_error());

						}

						$sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."' and sub_especifica != '00'")or die("ERROR CONSULTANDO EL CLASIFICADOR ".mysql_error());
						$num_clasificador = mysql_num_rows($sql_clasificador);
						if($num_clasificador > 0){
							$bus_clasificador = mysql_fetch_array($sql_clasificador);
							$sql_consulta_clasificador = mysql_query("select * from clasificador_presupuestario where partida = '".$bus_clasificador["partida"]."'
							and generica = '".$bus_clasificador["generica"]."'
							and especifica ='".$bus_clasificador["especifica"]."'
							and sub_especifica= '00'")or die("ERROR CONSULTANDO EL CLASIFICADOR 2:".mysql_error());
							$bus_consulta_clasificador= mysql_fetch_array($sql_consulta_clasificador);
							$sql_id_maestro= mysql_query("select * from maestro_presupuesto where
								idcategoria_programatica= '".$bus_consultar_maestro["idcategoria_programatica"]."'
								and idtipo_presupuesto = '".$bus_consultar_maestro["idtipo_presupuesto"]."'
								and idfuente_financiamiento = '".$bus_consultar_maestro["idfuente_financiamiento"]."'
								and idclasificador_presupuestario = '".$bus_consulta_clasificador["idclasificador_presupuestario"]."'
								and idordinal = '".$bus_consulta_ordinal["idordinal"]."'
								and anio = '".$bus_consultar_maestro["anio"]."'")or die("ERROR CONSULTANDO EL MAESTRO 2:".mysql_error());

							$bus_id_maestro = mysql_fetch_array($sql_id_maestro)or die("ERROR EN CONSULTA".mysql_error());

							$sql_maestro = mysql_query("update maestro_presupuesto set
															total_compromisos = total_compromisos + ".$bus_actualizar_partidas["monto"]."
															where idRegistro = '".$bus_id_maestro["idmaestro_presupuesto"]."'")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 3: ".$sql_id_maestro." ".mysql_error());

						}

					}


				}

				$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR SELECCIONANDO LA ORDEN DE COMPRA: ".mysql_error());
				$bus_orden = mysql_fetch_array($sql_orden);
				$tipo_orden = $bus_orden["tipo"];

				$sql_configuracion = mysql_query("select * from configuracion");
				$bus_configuracion = mysql_fetch_array($sql_configuracion);
				$anio_fiscal = $bus_configuracion["anio_fiscal"];


				$sql_nro_orden = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$tipo_orden."");
				$bus_nro_orden = mysql_fetch_array($sql_nro_orden);


				if($bus_nro_orden["documento_asociado"] != 0){
						$sql_documento_asociado = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$bus_nro_orden["documento_asociado"]."");
						$bus_documento_asociado = mysql_fetch_array($sql_documento_asociado);
						$id_a_actualizar = $bus_documento_asociado["idtipos_documentos"];
						$codigo_orden = $bus_documento_asociado["siglas"]."-".$anio_fiscal."-".$bus_documento_asociado["nro_contador"];
						$nro_orden_compra = $bus_documento_asociado["nro_contador"];
					}else{
						$id_a_actualizar = $tipo_orden;
						$codigo_orden = $bus_nro_orden["siglas"]."-".$anio_fiscal."-".$bus_nro_orden["nro_contador"];
						$nro_orden_compra = $bus_nro_orden["nro_contador"];
					}

					$sql_existe_numero = mysql_query("select * from orden_compra_servicio where numero_orden = '".$codigo_orden."'")or die("cero".mysql_error());
					$bus_existe = mysql_num_rows($sql_existe_numero);

					while ($bus_existe > 0){
						$sql_actualizar_numero = mysql_query("update tipos_documentos set nro_contador = nro_contador + 1 where idtipos_documentos = ".$id_a_actualizar."")or die("uno".mysql_error());



						$sql_nro_orden = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$tipo_orden."");
						$bus_nro_orden = mysql_fetch_array($sql_nro_orden);
							if($bus_nro_orden["documento_asociado"] != 0){
								$sql_documento_asociado = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$bus_nro_orden["documento_asociado"]."");
								$bus_documento_asociado = mysql_fetch_array($sql_documento_asociado);
								$id_a_actualizar = $bus_documento_asociado["idtipos_documentos"];
								$codigo_orden = $bus_documento_asociado["siglas"]."-".$anio_fiscal."-".$bus_documento_asociado["nro_contador"];
								$nro_orden_pago = $bus_documento_asociado["nro_contador"];
							}else{
								$id_a_actualizar = $tipo_orden;
								$codigo_orden = $bus_nro_orden["siglas"]."-".$anio_fiscal."-".$bus_nro_orden["nro_contador"];
								$nro_orden_pago = $bus_nro_orden["nro_contador"];
							}


						$sql_existe_numero = mysql_query("select * from orden_compra_servicio where numero_orden = '".$codigo_orden."'")or die("cero".mysql_error());
						$bus_existe = mysql_num_rows($sql_existe_numero);
					}



				// ACA SE GENERA EL NUMERO DE CONTROL DE LA ORDEN DE COMPRA


				$codigo_referencia = 90000000000+$nro_orden_compra;

				$sql_actualizar_orden = mysql_query("update orden_compra_servicio set estado = 'procesado',
														numero_orden = '".$codigo_orden."',
														fecha_orden = '".date("Y-m-d")."',
														codigo_referencia = '".$codigo_referencia."'
													where idorden_compra_servicio = ".$id_orden_compra."")
													or die("error".mysql_error());





			//	echo "select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."";
				$sql_relacion_compra_requisicion = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."")or die("ERROR EN EL SELECT ".mysql_error());

				while ($bus_relacion_compra_requisicion = mysql_fetch_array($sql_relacion_compra_requisicion))
				{
				//echo "update requisicion set estado = 'ordenado' where idrequisicion = ".$bus_relacion_compra_requisicion["idrequisicion"]."";
					$sql_actualizar_requisicion = mysql_query("update requisicion set estado = 'ordenado' where idrequisicion = ".$bus_relacion_compra_requisicion["idrequisicion"]."")or die("ERROR EN EL UPDATE".mysql_error());
				}



				$sql_relacion_compra_solicitud = mysql_query("select * from relacion_compra_solicitud_cotizacion where idorden_compra = ".$id_orden_compra."");

				while ($bus_relacion_compra_solicitud = mysql_fetch_array($sql_relacion_compra_solicitud))
				{
					$sql_actualizar_solicitud = mysql_query("update solicitud_cotizacion set estado = 'ordenado',
																						nro_orden = '".$codigo_orden."'
																					where idsolicitud_cotizacion = ".$bus_relacion_compra_solicitud["idsolicitud_cotizacion"]."");
				}

				$sql_relacion_compra_requisicion = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."");

				while ($bus_relacion_compra_requisicion = mysql_fetch_array($sql_relacion_compra_requisicion))
				{
					$sql_actualizar_requisicion = mysql_query("update requisicion set estado = 'ordenado',
																						nro_orden = '".$codigo_orden."'
																					where idrequisicion = ".$bus_relacion_compra_requisicion["idrequision"]."");
				}

				//ACTUALIZAR ASIENTO CONTABLE

				$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_compra."
														and tipo_movimiento = 'compromiso'");
				if (mysql_num_rows($sql_validar_asiento) > 0){
					$sql_asiento_contable = mysql_query("update asiento_contable set estado = 'procesado',
																fecha_contable = '".date("Y-m-d")."'
											 where iddocumento = '".$id_orden_compra."'
											 		and tipo_movimiento = 'compromiso'")or die("error".mysql_error());
				}


				// ACTUALIZAR EL ULTIMO COSTO DE LOS PRODUCTOS
				$sql_select_articulos_compra = mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
				while($bus_select_articulos_compra = mysql_fetch_array($sql_select_articulos_compra)){
					$sql_select_ultimo_costo = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_select_articulos_compra["idarticulos_servicios"]."'");
					$bus_select_ultimo_costo = mysql_fetch_array($sql_select_ultimo_costo);
					$costo_actual = $bus_select_articulos_compra["precio_unitario"];
					$ultimo_costo = $bus_select_ultimo_costo["ultimo_costo"];
						if($bus_select_ultimo_costo["ultimo_costo"] == 0 || $bus_select_ultimo_costo["ultimo_costo"] == ""){
							$costo_promedio = $costo_actual;
						}else{
							$costo_promedio = ($costo_actual+$ultimo_costo)/2;
						}

					$sql_actualizar_articulo = mysql_query("update articulos_servicios set ultimo_costo = '".$costo_actual."',
													costo_promedio = '".$costo_promedio."',
													fecha_ultima_compra = '".date("Y-m-d")."'
													where idarticulos_servicios = '".$bus_select_articulos_compra["idarticulos_servicios"]."'");
				}


				if($sql_actualizar_orden){
					$sql_actualizar_numero = mysql_query("update tipos_documentos set nro_contador = nro_contador + 1 where idtipos_documentos = ".$tipo_orden."")or die("uno".mysql_error());
					return $codigo_orden;
					registra_transaccion("Proceso Certificacion de Compromiso (".$codigo_orden.", ID: ".$id_orden_compra.")",$login,$fh,$pc,'orden_compra_servicios');
				}else{
					return "fallo";
				}
			}else{
				return "falloImpuestos";
			}
		}else{
			return "falloMateriales";
		}
	  }else{
		return "duplicados";
	}
	}else{
		return "sinMateriales";
	}

}



// FIN DE LA FUNCION DE PROCESAR LA CERTIFICACION **************************************************************************************






function actualizarPrecioCantidad($id_articulo, $id_orden_compra, $id_categoria_programatica, $cantidad, $precio, $fuente_financiamiento, $tipo_presupuesto,$id_clasificador_presupuestario, $idordinal, $id_articulo_compra, $idtrabajador, $anio, $idcertificacion_aporte){

	$sql_articulos_servicios = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id_articulo."'")or die("XXXX".mysql_error());
	$bus_articulos_servicios = mysql_fetch_array($sql_articulos_servicios);

	//ACTUALIZO EL MATERIAL DE LA CERTIFICACION DE ASIGNACIONES Y DEDUCCIONES
	if ($bus_articulos_servicios["tipo_concepto"] == 1 || $bus_articulos_servicios["tipo_concepto"] == 2) {

		$sql_actualizar = mysql_query("update articulos_compra_servicio set
													total = total + ".$precio.",
													precio_unitario = precio_unitario + ".$precio."
													where
													idarticulos_compra_servicio = '".$id_articulo_compra."'
													and idorden_compra_servicio = '".$id_orden_compra."'")or die("ALLA".mysql_error());

		//$idordinal = $bus_articulos_servicios["idordinal"];


		$sql_articulos_compra_servicio = mysql_query("select * from articulos_compra_servicio where idarticulos_compra_servicio = '".$id_articulo_compra."'")or die("FFFF".mysql_error());
		$bus_articulos_compra_servicio = mysql_fetch_array($sql_articulos_compra_servicio);

		//$id_categoria_programatica = $id_categoria_programatica;
		//$id_clasificador_presupuestario = $bus_articulos_servicios["idclasificador_presupuestario"];
		//echo $id_clasificador_presupuestario;

		if($cantidad != 0){
			$total_articulo_individual = $cantidad * $precio;
		}else{
			$total_articulo_individual = $precio;
		}

//			echo "TOTAL ARTICULO INDIVIDUAL: ".$total_articulo_individual;

		//echo "TIPO CONCEPTO: ".$bus_articulos_servicios["tipo_concepto"]."<br />";
		if($bus_articulos_servicios["tipo_concepto"] == 1){

			$monto_total = $total_articulo_individual;
			$t_exento = 0;
		}else{
			$monto_total = 0;
			$t_exento = $total_articulo_individual;
		}

		// busco el precio y la cantidad anteriores para restarsela a los totales
		$sql_orden_compra_viejo = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'")or die("TTTTT".mysql_error());
		$bus_orden_compra_viejo = mysql_fetch_array($sql_orden_compra_viejo);

		$exento_viejo = $bus_orden_compra_viejo["exento"];
		$sub_total_viejo = $bus_orden_compra_viejo["sub_total"];


		// ACTUALIZO LOS TOTALES EN LA TABLA ORDEN_COMPRA_SERVICIO
		$total_anterior = $sub_total_viejo - $exento_viejo;
		$total_nuevo = $monto_total - $exento;
		$sql_actualiza_totales = mysql_query("update orden_compra_servicio set
													sub_total = sub_total  + '".$monto_total."',
													exento = exento + '".$t_exento."',
													total = total + '".$total_nuevo."'
													where idorden_compra_servicio=".$id_orden_compra." ")or die("4: ".mysql_error());


		$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_compra."
													and tipo_movimiento = 'compromiso'")or die(mysql_error());
		if (mysql_num_rows($sql_validar_asiento) > 0){

			$bus_asiento_contable = mysql_fetch_array($sql_validar_asiento);

			$sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'
														order by afecta")or die(" uno ".mysql_error());

			while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)){
				//echo " actualizar precio monto ".$monto_total;
				$actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = monto + '".$monto_total."'
																where idcuentas_asiento_contable = '".$bus_cuentas_contables["idcuentas_asiento_contable"]."'")or die("dos ".mysql_error());
			}
		}


		// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA


		$sql = mysql_query("select * from articulos_compra_servicio where
												idarticulos_compra_servicio = '".$id_articulo_compra."'")or die("5: ".mysql_error());
		$bus = mysql_fetch_array($sql);

		// en cualquiera de Estos estados el articulo tiene partida en el maestro
		if($bus["estado"] == "aprobado" || $bus["estado"] == "sin disponibilidad"){
			$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where
												idorden_compra_servicio = '".$id_orden_compra."'")or die("6: ".mysql_error());
			$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
			//echo "ID: ".$bus_compra_servicio["idcategoria_programatica"]." ";

			$partida_impuestos = 0;

			$sql_imputable = mysql_query("select precio_unitario from articulos_compra_servicio where idarticulos_compra_servicio = '".$id_articulo_compra."'")or die("12: ".mysql_error());

			$bus_imputable = mysql_fetch_array($sql_imputable);
			$suma_partida = $bus_imputable["precio_unitario"];
			//echo "TOTAL: ".$total_imputable. "ID ";
			//echo $bus_imputable["totales"];
			//echo $bus_imputable["exentos"];


			if($bus_articulos_servicios["tipo_concepto"] == 1){


				$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = '".$anio."'
												and idcategoria_programatica = '".$id_categoria_programatica."'
												and idclasificador_presupuestario = '".$id_clasificador_presupuestario."'
												and idfuente_financiamiento = '".$fuente_financiamiento."'
												and idtipo_presupuesto = '".$tipo_presupuesto."'
												and idordinal = '".$idordinal."'")or die("8: ".mysql_error());

				$existe_maestro=mysql_num_rows($sql_maestro);
				$bus_maestro = mysql_fetch_array($sql_maestro);
				if($existe_maestro<=0){
					$estado = "rechazado";
				}else{

					$sql_consultar_existe = mysql_query("select * from partidas_orden_compra_servicio where idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."' and idorden_compra_servicio = '".$id_orden_compra."'");
					$num_consultar_existe = mysql_num_rows($sql_consultar_existe);
					$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
					//$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);


					if($num_consultar_existe > 0){
						if($suma_partida > $disponible){
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro',
																	monto = monto + '".$precio."',
																	monto_original = monto_original + '".$precio."'
																	where
																	idorden_compra_servicio = '".$id_orden_compra."'
																	and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("15: ".mysql_error());
							$estado = "sin disponibilidad";

						}else{
							//echo "PRUEBA: ".$bus_maestro["idRegistro"]." ------ ";
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible',
															monto = monto + '".$precio."',
															monto_original = monto_original + '".$precio."'
															where
															idorden_compra_servicio = ".$id_orden_compra."
															and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("16: ".mysql_error());


							$estado = "aprobado";
						}
					}else{
						if($suma_partida > $disponible){
							$es = "sobregiro";
							$estado = "sin disponibilidad";
						}else{
							$es = "disponible";
							$estado = "aprobado";
						}

						if($total_articulo_individual != 0){
						//echo "entro aqui";
							$sql_partida = mysql_query("insert into partidas_orden_compra_servicio
																			(estado,
																				monto,
																				monto_original,
																				idmaestro_presupuesto,
																				idorden_compra_servicio
																				)VALUES(
																			'".$es."',
																			'".$precio."',
																			'".$precio."',
																			'".$bus_maestro["idRegistro"]."',
																			'".$id_orden_compra."')")or die("15: ".mysql_error());
						}

					}


				}//else{
					//echo "ALLA";
					//$estado = "aprobado";
				//}
			}
		}else{
			if($bus_articulos_servicios["tipo_concepto"] == 1){
				$estado = "rechazado";
			}
			if($bus_articulos_servicios["tipo_concepto"] == 2){
				$estado = "aprobado";
			}
		}

			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA

			$sql2 = mysql_query("update articulos_compra_servicio set estado = '".$estado."'
																where idarticulos_compra_servicio = '".$id_articulo_compra."'")or die("17: ".mysql_error());

		if($sql2){
				//registra_transaccion("Actualizar Precio Cantidad de Orden de Compra (".$id_articulo_compra.")",$login,$fh,$pc,'orden_compra_servicios');

				//echo "exito";
		}else{
				//registra_transaccion("Actualizar Precio Cantidd de Orden de Compra ERROR (".$id_articulo_compra.")",$login,$fh,$pc,'orden_compra_servicios');

				//echo $sql2." MYSQL ERROR: ".mysql_error();
		}
	}


	//ACTUALIZO EL MATERIAL DE LA CERTIFICACION DE APORTES
	if ($bus_articulos_servicios["tipo_concepto"] == 4) {

		$sql_actualizar = mysql_query("update articulos_compra_servicio set
													total = total + ".$precio.",
													precio_unitario = precio_unitario + ".$precio."
													where
													idarticulos_compra_servicio = '".$id_articulo_compra."'
													and idorden_compra_servicio = '".$idcertificacion_aporte."'")or die("ALLA".mysql_error());

		//$idordinal = $bus_articulos_servicios["idordinal"];


		$sql_articulos_compra_servicio = mysql_query("select * from articulos_compra_servicio where idarticulos_compra_servicio = '".$id_articulo_compra."'")or die("FFFF".mysql_error());
		$bus_articulos_compra_servicio = mysql_fetch_array($sql_articulos_compra_servicio);

		$id_categoria_programatica = $id_categoria_programatica;
		//$id_clasificador_presupuestario = $bus_articulos_servicios["idclasificador_presupuestario"];
		//echo $id_clasificador_presupuestario;

		if($cantidad != 0){
			$total_articulo_individual = $cantidad * $precio;
		}else{
			$total_articulo_individual = $precio;
		}

//			echo "TOTAL ARTICULO INDIVIDUAL: ".$total_articulo_individual;

		//echo "TIPO CONCEPTO: ".$bus_articulos_servicios["tipo_concepto"]."<br />";
		if($bus_articulos_servicios["tipo_concepto"] == 4){

			$monto_total = $total_articulo_individual;
			$t_exento = 0;
		}else{
			$monto_total = 0;
			$t_exento = $total_articulo_individual;
		}

		// busco el precio y la cantidad anteriores para restarsela a los totales
		$sql_orden_compra_viejo = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$idcertificacion_aporte."'")or die("TTTTT".mysql_error());
		$bus_orden_compra_viejo = mysql_fetch_array($sql_orden_compra_viejo);

		$exento_viejo = $bus_orden_compra_viejo["exento"];
		$sub_total_viejo = $bus_orden_compra_viejo["sub_total"];


		// ACTUALIZO LOS TOTALES EN LA TABLA ORDEN_COMPRA_SERVICIO
		$total_anterior = $sub_total_viejo - $exento_viejo;
		$total_nuevo = $monto_total - $exento;
		$sql_actualiza_totales = mysql_query("update orden_compra_servicio set
													sub_total = sub_total  + '".$monto_total."',
													exento = exento + '".$t_exento."',
													total = total + '".$total_nuevo."'
													where idorden_compra_servicio=".$idcertificacion_aporte." ")or die("4: ".mysql_error());


		$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$idcertificacion_aporte."
													and tipo_movimiento = 'compromiso'")or die(mysql_error());
		if (mysql_num_rows($sql_validar_asiento) > 0){

			$bus_asiento_contable = mysql_fetch_array($sql_validar_asiento);

			$sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'
														order by afecta")or die(" uno ".mysql_error());

			while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)){
				//echo " actualizar precio monto ".$monto_total;
				$actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = monto + '".$monto_total."'
																where idcuentas_asiento_contable = '".$bus_cuentas_contables["idcuentas_asiento_contable"]."'")or die("dos ".mysql_error());
			}
		}


		// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA


		$sql = mysql_query("select * from articulos_compra_servicio where
												idarticulos_compra_servicio = '".$id_articulo_compra."'")or die("5: ".mysql_error());
		$bus = mysql_fetch_array($sql);

		// en cualquiera de Estos estados el articulo tiene partida en el maestro
		if($bus["estado"] == "aprobado" || $bus["estado"] == "sin disponibilidad"){
			$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where
												idorden_compra_servicio = '".$idcertificacion_aporte."'")or die("6: ".mysql_error());
			$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
			//echo "ID: ".$bus_compra_servicio["idcategoria_programatica"]." ";

			$partida_impuestos = 0;

			$sql_imputable = mysql_query("select precio_unitario from articulos_compra_servicio where idarticulos_compra_servicio = '".$id_articulo_compra."'")or die("12: ".mysql_error());

			$bus_imputable = mysql_fetch_array($sql_imputable);
			$suma_partida = $bus_imputable["precio_unitario"];
			//echo "TOTAL: ".$total_imputable. "ID ";
			//echo $bus_imputable["totales"];
			//echo $bus_imputable["exentos"];


			if($bus_articulos_servicios["tipo_concepto"] == 4){


				$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = '".$anio."'
												and idcategoria_programatica = '".$id_categoria_programatica."'
												and idclasificador_presupuestario = '".$id_clasificador_presupuestario."'
												and idfuente_financiamiento = '".$fuente_financiamiento."'
												and idtipo_presupuesto = '".$tipo_presupuesto."'
												and idordinal = '".$idordinal."'")or die("8: ".mysql_error());

				$existe_maestro=mysql_num_rows($sql_maestro);
				$bus_maestro = mysql_fetch_array($sql_maestro);
				if($existe_maestro<=0){
					$estado = "rechazado";
				}else{

					$sql_consultar_existe = mysql_query("select * from partidas_orden_compra_servicio where idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."' and idorden_compra_servicio = '".$idcertificacion_aporte."'");
					$num_consultar_existe = mysql_num_rows($sql_consultar_existe);
					$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
					//$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);


					if($num_consultar_existe > 0){
						if($suma_partida > $disponible){
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro',
																	monto = monto + '".$precio."',
																	monto_original = monto_original + '".$precio."'
																	where
																	idorden_compra_servicio = '".$idcertificacion_aporte."'
																	and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("15: ".mysql_error());
							$estado = "sin disponibilidad";

						}else{
							//echo "PRUEBA: ".$bus_maestro["idRegistro"]." ------ ";
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible',
															monto = monto + '".$precio."',
															monto_original = monto_original + '".$precio."'
															where
															idorden_compra_servicio = ".$idcertificacion_aporte."
															and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("16: ".mysql_error());


							$estado = "aprobado";
						}
					}else{
						if($suma_partida > $disponible){
							$es = "sobregiro";
							$estado = "sin disponibilidad";
						}else{
							$es = "disponible";
							$estado = "aprobado";
						}

						if($total_articulo_individual != 0){
						//echo "entro aqui";
							$sql_partida = mysql_query("insert into partidas_orden_compra_servicio
																			(estado,
																				monto,
																				monto_original,
																				idmaestro_presupuesto,
																				idorden_compra_servicio
																				)VALUES(
																			'".$es."',
																			'".$precio."',
																			'".$precio."',
																			'".$bus_maestro["idRegistro"]."',
																			'".$idcertificacion_aporte."')")or die("15: ".mysql_error());
						}

					}


				}//else{
					//echo "ALLA";
					//$estado = "aprobado";
				//}
			}
		}else{
			$estado = "rechazado";
		}

			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA

			$sql2 = mysql_query("update articulos_compra_servicio set estado = '".$estado."'
																where idarticulos_compra_servicio = '".$id_articulo_compra."'")or die("17: ".mysql_error());

		if($sql2){
				//registra_transaccion("Actualizar Precio Cantidad de Orden de Compra (".$id_articulo_compra.")",$login,$fh,$pc,'orden_compra_servicios');

				//echo "exito";
		}else{
				//registra_transaccion("Actualizar Precio Cantidd de Orden de Compra ERROR (".$id_articulo_compra.")",$login,$fh,$pc,'orden_compra_servicios');

				//echo $sql2." MYSQL ERROR: ".mysql_error();
		}
	}



}









function ingresarMaterial($id_material, $id_orden_compra, $id_categoria_programatica, $cantidad, $precio_unitario, $fuente_financiamiento, $tipo_presupuesto,$id_clasificador_presupuestario, $idordinal,$anio,$idcertificacion_aporte){

//VERIFICO EL TIPO DE CONCEPTO, SI ES ASIGNACION(1) O DEDUCCION(2) VAN A LA CERTIFICACION DE Nomina

	$sql_articulos_servicios = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id_material."'");
	$bus_articulos_servicios = mysql_fetch_array($sql_articulos_servicios);

	if ($bus_articulos_servicios["tipo_concepto"] == 1 || $bus_articulos_servicios["tipo_concepto"] == 2) {

		$sql = mysql_query("select * from articulos_compra_servicio where
														idarticulos_servicios = ".$id_material."
														and idorden_compra_servicio = ".$id_orden_compra."
														and idcategoria_programatica = '".$id_categoria_programatica."'")or die("TTTTTT".mysql_error());
		$num = mysql_num_rows($sql);

		// SI EL ARTICULO NO EXISTE ENTRE LOS ARTICULOS CARGADOS
		if($num == 0){

			if($cantidad != 0){
				$total_articulo_individual = $cantidad * $precio_unitario;
			}else{
				$total_articulo_individual = $precio_unitario;
			}
			$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
			$bus_orden = mysql_fetch_array($sql_orden);

			//BUSCO EL IMPUESTO QUE SE LE APLICA AL ARTICULO PARA SABER SI TIENE PARTIDA PROPIA O SE VA A CARGAR A LA PARTIDA DEL ARTICULO


			// AGREGO EL ARTICULO DE LA SOLICITUD DE COTIZACION A LA ORDEN DE COMPRA

			if($bus_articulos_servicios["tipo_concepto"] == 1){
				$monto_total = $total_articulo_individual;
				$exento = 0;
			}else{
				$monto_total = 0;
				$exento = $total_articulo_individual;
			}

			//echo "MONTO TOTAL: ".$monto_total;
			//echo "EXENTO: ".$exento;


			if($total_articulo_individual != 0) {

					$sql = mysql_query("insert into articulos_compra_servicio (idorden_compra_servicio,
																			idarticulos_servicios,
																			idcategoria_programatica,
																			cantidad,
																			precio_unitario,
																			porcentaje_impuesto,
																			impuesto,
																			total,
																			exento,
																			status,
																			usuario,
																			fechayhora,
																			idpartida_impuesto)values(
																			'" . $id_orden_compra . "',
																			'" . $id_material . "',
																			'" . $id_categoria_programatica . "',
																			'" . $cantidad . "',
																			'" . $precio_unitario . "',
																			'" . $porcentaje_impuesto . "',
																			'" . $total_impuesto_individual . "',
																			'" . $monto_total . "',
																			'" . $exento . "',
																			'a',
																			'" . $login . "',
																			'" . date("Y-m-d H:i:s") . "',
																			'" . $id_partida_impuesto . "'
																			)") or die("AQUIIIIIIII " . mysql_error());

					$id_ultimo_generado = mysql_insert_id();


					// OBTENGO EL ULTIMO ID INGRESADO EN LA TABLA DE ARTICULOS PARA ACTUALIZARLE EL ESTADO DESPUES DE ANALIZAR LA
					//DISPONIBILIDAD DE LAS PARTIDAS
					$total_general = $monto_total - $exento;
					$actualiza_totales = mysql_query("update orden_compra_servicio set
													sub_total = sub_total + '" . $monto_total . "',
													exento = exento + '" . $exento . "',
													total = total + '" . $total_general . "'
													where idorden_compra_servicio=" . $id_orden_compra . " ") or die ("11111111 " . mysql_error());

					//echo "material individual".$id_orden_compra;
					$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = " . $id_orden_compra . "
															and tipo_movimiento = 'compromiso'");
					if (mysql_num_rows($sql_validar_asiento) > 0) {

						$bus_asiento_contable = mysql_fetch_array($sql_validar_asiento);

						$sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																	order by afecta");

						while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)) {
							$actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = monto + '" . $monto_total . "'
																			where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'");
						}
					}

				}


				$sql_articulos = mysql_query("select * from articulos_servicios where idarticulos_servicios = " . $id_material . "");
				$bus_articulos = mysql_fetch_array($sql_articulos);
				// 	se realiza la consulta en la tabla maestro para verificar si hay partidas para este articulo, de lo contrario el articulo se coloca en un estado de rechazado para que
				//	en la tabla aparesca la fila en rojo y muestre al usuario que no hay partidas para ese articulo
				if ($bus_articulos_servicios["tipo_concepto"] == 1) {

					$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = '" . $anio . "'
															and idcategoria_programatica = '" . $id_categoria_programatica . "'
															and idclasificador_presupuestario = '" . $id_clasificador_presupuestario . "'
															and idfuente_financiamiento = '" . $fuente_financiamiento . "'
															and idtipo_presupuesto = '" . $tipo_presupuesto . "'
															and idordinal = '" . $idordinal . "'"
					) or die($anio . "ERROR SQL MAESTRO: " . mysql_error());

					$num_maestro = mysql_num_rows($sql_maestro);

					if ($num_maestro == 0) { // VALIDO QUE EXISTA UNA PARTIDA EN EL MAESTRO DE PRESUPUESTO PARA ESE ARTICULO
						$estado = "rechazado";  // si no tiene partida en el maestro de presupuesto le coloca RECHAZADO para pintarlo de color ROJO
					} else {
						$bus_maestro = mysql_fetch_array($sql_maestro);
						$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
						//$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
						//echo $bus_maestro["idRegistro"];
						// de lo contrario verifica si en la partida que esta asignada este articulo hay disponibilidad de dinero para comprar la cantidad que se esta pidiendo, si para
						// esta partida no hay disponibilidad se coloca el estado en SIN DISPONIBILIDAD para que al mostrar el articulo en color en AMARILLO para indicar que no
						// hay presupuesto para este articulo

						$sql_imputable = mysql_query("select (precio_unitario) as total from articulos_compra_servicio where idarticulos_compra_servicio = '" . $id_ultimo_generado . "'");
						// SUMO EL TOTAL DE TODOS LOS ARTICULOS QUE ESTAN IMPUTANDO ESA PARTIDA PARA COMPARARLO CON EL DISPONIBLE EN EL MAESTRO DE PRESUPUESTO
						$bus_imputable = mysql_fetch_array($sql_imputable);
						$total_imputable = $bus_imputable["total"];

						if ($total_imputable > $disponible) { // si el total a imputar es mayor al disponible en la partida
							//echo "ESTA SOBREGIRADOOOOOOOOO";
							$estado = "sin disponibilidad";
							$estado_partida = "sobregiro";
						} else {
							//si nada de esto sucede se coloca el estado en aprobado y el material se muestra normalmente
							$estado = "aprobado";
							$estado_partida = "disponible";
						}
						/*echo "select * from partidas_orden_compra_servicio where idorden_compra_servicio=".$id_orden_compra."
																				and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."";*/

						$sql_partidas_orden_compra = mysql_query("select * from partidas_orden_compra_servicio where
																			idorden_compra_servicio='" . $id_orden_compra . "'
																			and idmaestro_presupuesto = '" . $bus_maestro["idRegistro"] . "'")
						or die("66666 " . mysql_error());
						$num = mysql_num_rows($sql_partidas_orden_compra);

						if ($num == 0) { // SI NO EXISTE LA PARTIDA LA INGRESO

							//echo "INGRESANDO PARTIDA ...... ";
							if ($total_imputable != 0) {
								//echo "ENTRO AQUI";
								$ingresar_partida = mysql_query("insert into partidas_orden_compra_servicio (idorden_compra_servicio,
																									idmaestro_presupuesto,
																									monto,
																									monto_original,
																									estado,
																									status,
																									usuario,
																									fechayhora)
																								values (" . $id_orden_compra . ",
																										" . $bus_maestro["idRegistro"] . ",
																										" . $precio_unitario . ",
																										" . $precio_unitario . ",
																										'" . $estado_partida . "',
																										'a',
																										'" . $login . "',
																										'" . date("Y-m-d H:i:s") . "')")
								or die("ERROR GUARDANDO PARTIDAS:" . mysql_error());
							}
						} else { // DE LO CONTRARIO LA ACTUALIZO
							//echo "AQUI";
							$actualiza_partida = mysql_query("update partidas_orden_compra_servicio set
																			monto = monto + '" . $precio_unitario . "',
																			monto_original = monto_original + '" . $precio_unitario . "',
																			estado='" . $estado_partida . "'
																			where idorden_compra_servicio='" . $id_orden_compra . "'
																			and idmaestro_presupuesto = '" . $bus_maestro["idRegistro"] . "'")
							or die ($total_item . "ERROR MODIFICANDO PARTIDAS: " . mysql_error());
						}

					}
				} else { // SI ES DEDUCCION ******************************************************************
					$estado = "disponible";
				}
				// actualizo el estado del material ingresado
				$sql_update_articulos_compras = mysql_query("update articulos_compra_servicio set estado = '" . $estado . "'
																	where idarticulos_compra_servicio = " . $id_ultimo_generado . "");


				if ($sql) {
					//registra_transaccion("Ingresar Material Individual en Orden de Compra (".$id_ultimo_generado.")",$login,$fh,$pc,'orden_compra_servicios');
					//echo "exito";
				} else {
					//echo "fallo";
				}
			}
	}
	if ($bus_articulos_servicios["tipo_concepto"] == 4) {
	//CARGO EL MATERIAL A LA CERTIFICACION DE APORTES PATRONALES;
		$sql = mysql_query("select * from articulos_compra_servicio where
														idarticulos_servicios = ".$id_material."
														and idorden_compra_servicio = ".$idcertificacion_aporte."
														and idcategoria_programatica = '".$id_categoria_programatica."'")or die("TTTTTT".mysql_error());
		$num = mysql_num_rows($sql);

		// SI EL ARTICULO NO EXISTE ENTRE LOS ARTICULOS CARGADOS
		if($num == 0){

			if($cantidad != 0){
				$total_articulo_individual = $cantidad * $precio_unitario;
			}else{
				$total_articulo_individual = $precio_unitario;
			}
			$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
			$bus_orden = mysql_fetch_array($sql_orden);

			//BUSCO EL IMPUESTO QUE SE LE APLICA AL ARTICULO PARA SABER SI TIENE PARTIDA PROPIA O SE VA A CARGAR A LA PARTIDA DEL ARTICULO


			// AGREGO EL ARTICULO DE LA SOLICITUD DE COTIZACION A LA ORDEN DE COMPRA

			//if($bus_articulos_servicios["tipo_concepto"] == 4){
			$monto_total = $total_articulo_individual;
			$exento = 0;
			//}else{
			//	$monto_total = 0;
			//	$exento = $total_articulo_individual;
			//}

			//echo "MONTO TOTAL: ".$monto_total;
			//echo "EXENTO: ".$exento;


			if($total_articulo_individual != 0) {

					$sql = mysql_query("insert into articulos_compra_servicio (idorden_compra_servicio,
																			idarticulos_servicios,
																			idcategoria_programatica,
																			cantidad,
																			precio_unitario,
																			porcentaje_impuesto,
																			impuesto,
																			total,
																			exento,
																			status,
																			usuario,
																			fechayhora,
																			idpartida_impuesto)values(
																			'" . $idcertificacion_aporte . "',
																			'" . $id_material . "',
																			'" . $id_categoria_programatica . "',
																			'" . $cantidad . "',
																			'" . $precio_unitario . "',
																			'" . $porcentaje_impuesto . "',
																			'" . $total_impuesto_individual . "',
																			'" . $monto_total . "',
																			'" . $exento . "',
																			'a',
																			'" . $login . "',
																			'" . date("Y-m-d H:i:s") . "',
																			'" . $id_partida_impuesto . "'
																			)") or die("AQUIIIIIIII " . mysql_error());

					$id_ultimo_generado = mysql_insert_id();


					// OBTENGO EL ULTIMO ID INGRESADO EN LA TABLA DE ARTICULOS PARA ACTUALIZARLE EL ESTADO DESPUES DE ANALIZAR LA
					//DISPONIBILIDAD DE LAS PARTIDAS
					$total_general = $monto_total - $exento;
					$actualiza_totales = mysql_query("update orden_compra_servicio set
													sub_total = sub_total + '" . $monto_total . "',
													exento = exento + '" . $exento . "',
													total = total + '" . $total_general . "'
													where idorden_compra_servicio=" . $idcertificacion_aporte . " ") or die ("11111111 " . mysql_error());

					//echo "material individual".$idcertificacion_aporte;
					$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = " . $idcertificacion_aporte . "
															and tipo_movimiento = 'compromiso'");
					if (mysql_num_rows($sql_validar_asiento) > 0) {

						$bus_asiento_contable = mysql_fetch_array($sql_validar_asiento);

						$sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '" . $bus_asiento_contable["idasiento_contable"] . "'
																	order by afecta");

						while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)) {
							$actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = monto + '" . $monto_total . "'
																			where idcuentas_asiento_contable = '" . $bus_cuentas_contables["idcuentas_asiento_contable"] . "'");
						}
					}

				}


				$sql_articulos = mysql_query("select * from articulos_servicios where idarticulos_servicios = " . $id_material . "");
				$bus_articulos = mysql_fetch_array($sql_articulos);
				// 	se realiza la consulta en la tabla maestro para verificar si hay partidas para este articulo, de lo contrario el articulo se coloca en un estado de rechazado para que
				//	en la tabla aparesca la fila en rojo y muestre al usuario que no hay partidas para ese articulo
				if ($bus_articulos_servicios["tipo_concepto"] == 4) {

					$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = '" . $anio . "'
															and idcategoria_programatica = '" . $id_categoria_programatica . "'
															and idclasificador_presupuestario = '" . $id_clasificador_presupuestario . "'
															and idfuente_financiamiento = '" . $fuente_financiamiento . "'
															and idtipo_presupuesto = '" . $tipo_presupuesto . "'
															and idordinal = '" . $idordinal . "'"
					) or die($anio . "ERROR SQL MAESTRO: " . mysql_error());

					$num_maestro = mysql_num_rows($sql_maestro);

					if ($num_maestro == 0) { // VALIDO QUE EXISTA UNA PARTIDA EN EL MAESTRO DE PRESUPUESTO PARA ESE ARTICULO
						$estado = "rechazado";  // si no tiene partida en el maestro de presupuesto le coloca RECHAZADO para pintarlo de color ROJO
					} else {
						$bus_maestro = mysql_fetch_array($sql_maestro);
						$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
						//$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
						//echo $bus_maestro["idRegistro"];
						// de lo contrario verifica si en la partida que esta asignada este articulo hay disponibilidad de dinero para comprar la cantidad que se esta pidiendo, si para
						// esta partida no hay disponibilidad se coloca el estado en SIN DISPONIBILIDAD para que al mostrar el articulo en color en AMARILLO para indicar que no
						// hay presupuesto para este articulo

						$sql_imputable = mysql_query("select (precio_unitario) as total from articulos_compra_servicio where idarticulos_compra_servicio = '" . $id_ultimo_generado . "'");
						// SUMO EL TOTAL DE TODOS LOS ARTICULOS QUE ESTAN IMPUTANDO ESA PARTIDA PARA COMPARARLO CON EL DISPONIBLE EN EL MAESTRO DE PRESUPUESTO
						$bus_imputable = mysql_fetch_array($sql_imputable);
						$total_imputable = $bus_imputable["total"];

						if ($total_imputable > $disponible) { // si el total a imputar es mayor al disponible en la partida
							//echo "ESTA SOBREGIRADOOOOOOOOO";
							$estado = "sin disponibilidad";
							$estado_partida = "sobregiro";
						} else {
							//si nada de esto sucede se coloca el estado en aprobado y el material se muestra normalmente
							$estado = "aprobado";
							$estado_partida = "disponible";
						}
						/*echo "select * from partidas_orden_compra_servicio where idorden_compra_servicio=".$id_orden_compra."
																				and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."";*/

						$sql_partidas_orden_compra = mysql_query("select * from partidas_orden_compra_servicio where
																			idorden_compra_servicio='" . $idcertificacion_aporte . "'
																			and idmaestro_presupuesto = '" . $bus_maestro["idRegistro"] . "'")
						or die("66666 " . mysql_error());
						$num = mysql_num_rows($sql_partidas_orden_compra);

						if ($num == 0) { // SI NO EXISTE LA PARTIDA LA INGRESO

							//echo "INGRESANDO PARTIDA ...... ";
							if ($total_imputable != 0) {
								//echo "ENTRO AQUI";
								$ingresar_partida = mysql_query("insert into partidas_orden_compra_servicio (idorden_compra_servicio,
																									idmaestro_presupuesto,
																									monto,
																									monto_original,
																									estado,
																									status,
																									usuario,
																									fechayhora)
																								values (" . $idcertificacion_aporte . ",
																										" . $bus_maestro["idRegistro"] . ",
																										" . $precio_unitario . ",
																										" . $precio_unitario . ",
																										'" . $estado_partida . "',
																										'a',
																										'" . $login . "',
																										'" . date("Y-m-d H:i:s") . "')")
								or die("ERROR GUARDANDO PARTIDAS:" . mysql_error());
							}
						} else { // DE LO CONTRARIO LA ACTUALIZO
							//echo "AQUI";
							$actualiza_partida = mysql_query("update partidas_orden_compra_servicio set
																			monto = monto + '" . $precio_unitario . "',
																			monto_original = monto_original + '" . $precio_unitario . "',
																			estado='" . $estado_partida . "'
																			where idorden_compra_servicio='" . $idcertificacion_aporte . "'
																			and idmaestro_presupuesto = '" . $bus_maestro["idRegistro"] . "'")
							or die ($total_item . "ERROR MODIFICANDO PARTIDAS: " . mysql_error());
						}

					}
				}
				// actualizo el estado del material ingresado
				$sql_update_articulos_compras = mysql_query("update articulos_compra_servicio set estado = '" . $estado . "'
																	where idarticulos_compra_servicio = " . $id_ultimo_generado . "");


				if ($sql) {
					//registra_transaccion("Ingresar Material Individual en Orden de Compra (".$id_ultimo_generado.")",$login,$fh,$pc,'orden_compra_servicios');
					//echo "exito";
				} else {
					//echo "fallo";
				}
			}

	}

}









if($ejecutar == "validarErroresConceptos"){
	$sql_articulos_orden_compra_servicio = mysql_query("select * from articulos_compra_servicio,
																		unidad_medida,
																		articulos_servicios,
																		categoria_programatica
									 where
									 	articulos_compra_servicio.idorden_compra_servicio = '".$id_orden_compra."'
									  	and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios
										and articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida
										and categoria_programatica.idcategoria_programatica = articulos_compra_servicio.idcategoria_programatica
										order by categoria_programatica.codigo,articulos_compra_servicio.idarticulos_compra_servicio")or die(mysql_error());
	$error= "no";
	while($bus_articulos_orden_compra_servicio = mysql_fetch_array($sql_articulos_orden_compra_servicio)){
		if($bus_articulos_orden_compra_servicio["estado"] == "rechazado" || $bus_articulos_orden_compra_servicio["estado"] == "sin disponibilidad"){
			$error = "si";
		}
	}
	echo $error;
}










if($ejecutar == "consultarConceptos"){
	$sql_suma_asignaciones = mysql_query("select SUM(articulos_compra_servicio.total) as total_asignaciones
											from
											articulos_servicios,
											articulos_compra_servicio
											where
											articulos_compra_servicio.idorden_compra_servicio = '".$id_orden_compra."'
											and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios
											and articulos_servicios.tipo_concepto = 1")or die(mysql_error());
	$bus_suma_asignaciones = mysql_fetch_array($sql_suma_asignaciones);


	$sql_suma_deducciones = mysql_query("select SUM(articulos_compra_servicio.precio_unitario) as total_deducciones
													 	from
														articulos_servicios,
														articulos_compra_servicio
														where
														articulos_compra_servicio.idorden_compra_servicio = '".$id_orden_compra."'
														and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios
														and articulos_servicios.tipo_concepto = 2")or die(mysql_error());
	$bus_suma_deducciones = mysql_fetch_array($sql_suma_deducciones);

	$total = $bus_suma_asignaciones["total_asignaciones"] - $bus_suma_deducciones["total_deducciones"];
	$sql_actualizar_compra_servicio = mysql_query("update orden_compra_servicio
														set exento = '".$bus_suma_deducciones["total_deducciones"]."',
														sub_total = '".$bus_suma_asignaciones["total_asignaciones"]."',
														total = '".$total."'
														where
													idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR EN LA ACTUALIZACION DE LOS TOTALES ".mysql_error());

	if($tipo == "detallado"){


	$sql_articulos_orden_compra_servicio = mysql_query("select * from articulos_compra_servicio,
																		unidad_medida,
																		articulos_servicios,
																		categoria_programatica
									 where
									 	articulos_compra_servicio.idorden_compra_servicio = '".$id_orden_compra."'
									  	and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios
										and articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida
										and categoria_programatica.idcategoria_programatica = articulos_compra_servicio.idcategoria_programatica
										order by articulos_servicios.tipo_concepto,
										categoria_programatica.codigo,
										articulos_compra_servicio.idarticulos_compra_servicio")or die(mysql_error());

	$num = mysql_num_rows($sql_articulos_orden_compra_servicio);





	?>
    <table cellpadding="3" cellspacing="10">
        <tr>
            <td style="cursor:pointer" bgcolor="#EAEAEA" onclick="consultarConceptos('<?=$id_orden_compra?>', 'resumido')"><strong>Resumido</strong></td>
            <td style="cursor:pointer" bgcolor="#EAEAEA" onclick="consultarConceptos('<?=$id_orden_compra?>', 'detallado')"><strong>Detallado</strong></td>
        </tr>
    </table>
    <table style="margin-left:8px">
        <tr>
            <td style="background-color:#9CF" width="150px" align="center"><strong>Asignaciones</strong></td>
            <td style="background-color:#FFC" width="150px" align="center"><strong>Deducciones</strong></td>
            <td  align="center" bgcolor="#EAEAEA"><strong>Asignaciones: </strong><?=number_format($bus_suma_asignaciones["total_asignaciones"],2,",",".")?></td>
            <td align="center" bgcolor="#EAEAEA">&nbsp;&nbsp;<strong>Deducciones: </strong><?=number_format($bus_suma_deducciones["total_deducciones"],2,",",".")?></td>
            <td align="center" bgcolor="#EAEAEA">&nbsp;&nbsp;<strong>Total a Pagar: </strong><?=number_format($bus_suma_asignaciones["total_asignaciones"]-$bus_suma_deducciones["total_deducciones"],2,",",".")?></td>
        </tr>
    </table>
    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>

            <td class="Browse"><div align="center">Categoria</div></td>
            <td class="Browse"><div align="center">Tipo</div></td>
            <td class="Browse"><div align="center">Descripci&oacute;n</div></td>
            <td class="Browse"><div align="center">Monto</div></td>

          </tr>
          </thead>
          <?

          while($bus_articulos_orden_compra_servicio = mysql_fetch_array($sql_articulos_orden_compra_servicio)){

            if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 1){
				$color = "#9CF";
				$tipo_concepto =  "Asignacion";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 2){
				$tipo_concepto =  "Deduccion";
				$color = "#FFC";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 4){
				$tipo_concepto =  "Aporte Patronal";
			}else{
				$tipo_concepto = "Neutro";
			}
		if($tipo_concepto != "Aporte Patronal" and $tipo_concepto != 'Neutro'){
          	if($bus_articulos_orden_compra_servicio["estado"] == "rechazado"){
			?>
			<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
			<?
			}else if($bus_articulos_orden_compra_servicio["estado"] == "sin disponibilidad"){
			?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
			}else{
			?>
			<tr style="background-color:<?=$color?>" onMouseOver="setRowColor(this, 0, 'over', '<?=$color?>', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '<?=$color?>', '#EAFFEA', '#FFFFAA')">
			<?

			}
		  ?>

           <td class='Browse' align='left'><?=$bus_articulos_orden_compra_servicio["codigo"]?></td>
            <td class='Browse' align='left'>
			<?=$tipo_concepto?>
            </td>
             <td class='Browse' align='left'><?=$bus_articulos_orden_compra_servicio[30]?>
              <div align="right"></div></td>


          <td class="Browse" align="right">
				<?
				echo number_format($bus_articulos_orden_compra_servicio["precio_unitario"],2,',','.');
                ?>             </td>
				<?
                if($bus_orden["estado"] == "elaboracion"){
				?>
            	<td class='Browse' align="center">
					<a href="javascript:;" onclick=""><a href="javascript:;" onclick=""><img src="imagenes/refrescar.png" onclick="
                                actualizarPrecioCantidad(<?=$bus_articulos_orden_compra_servicio["idorden_compra_servicio"]?>,
                                document.getElementById('precio<?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>').value,
                                document.getElementById('cantidad<?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>').value,
                                <?=$bus_articulos_orden_compra_servicio["idarticulos_servicios"]?>,
                                <?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>,
                                document.getElementById('anio').value,
                                document.getElementById('fuente_financiamiento').value,
                                document.getElementById('tipo_presupuesto').value,
                                document.getElementById('id_ordinal').value,
                                document.getElementById('contribuyente_ordinario').value,
                                document.getElementById('anio').value)"
                                title="Actualizar Precio y Cantidad" /></a></td>
<td class='Browse' align="center">
                    <a href="javascript:;" onClick="eliminarMateriales(<?=$bus_articulos_orden_compra_servicio["idorden_compra_servicio"]?>, <?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>, <?=$bus_articulos_orden_compra_servicio["idsolicitud_cotizacion"]?>, document.getElementById('id_categoria_programatica').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value, document.getElementById('contribuyente_ordinario').value,
document.getElementById('anio').value)">
           			<img src="imagenes/delete.png" title="Eliminar Materiales">           		</a>            </td>
              <?
                }
				?>
          </tr>
          <?
          }

		  }

          ?>
        </table>
        <?

	}else{
	// AQUI VA SI ES RESUMIDO	 **********************************************************************************************************
		$sql_asignaciones = mysql_query("select SUM(articulos_compra_servicio.total) as total,
												SUM(articulos_compra_servicio.cantidad) as cantidad,
												SUM(articulos_compra_servicio.precio_unitario) as precio_unitario,
												articulos_servicios.descripcion,
												articulos_servicios.tipo_concepto,
												articulos_compra_servicio.estado
																		from
																		articulos_compra_servicio,
																		unidad_medida,
																		articulos_servicios,
																		categoria_programatica
									 where
									 	articulos_compra_servicio.idorden_compra_servicio = '".$id_orden_compra."'
									  	and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios
										and articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida
										and categoria_programatica.idcategoria_programatica = articulos_compra_servicio.idcategoria_programatica
										group by articulos_servicios.idarticulos_servicios
										order by articulos_servicios.tipo_concepto")or die(mysql_error());
	?>

	<table cellpadding="3" cellspacing="10">
        <tr>
        <td style="cursor:pointer" bgcolor="#EAEAEA" onclick="consultarConceptos('<?=$id_orden_compra?>', 'resumido')"><strong>Resumido</strong></td>
        <td style="cursor:pointer" bgcolor="#EAEAEA" onclick="consultarConceptos('<?=$id_orden_compra?>', 'detallado')"><strong>Detallado</strong></td>
        </tr>
    </table>
    <table style="margin-left:8px">
        <tr>
        <td style="background-color:#9CF" width="150px" align="center"><strong>Asignaciones</strong></td>
        <td style="background-color:#FFC" width="150px" align="center"><strong>Deducciones</strong></td>
        <td  align="center" bgcolor="#EAEAEA"><strong>Asignaciones: </strong><?=number_format($bus_suma_asignaciones["total_asignaciones"],2,",",".")?></td>
        <td align="center" bgcolor="#EAEAEA">&nbsp;&nbsp;<strong>Deducciones: </strong><?=number_format($bus_suma_deducciones["total_deducciones"],2,",",".")?></td>
        <td align="center" bgcolor="#EAEAEA">&nbsp;&nbsp;<strong>Total a Pagar: </strong><?=number_format($bus_suma_asignaciones["total_asignaciones"]-$bus_suma_deducciones["total_deducciones"],2,",",".")?></td>
        </tr>
    </table>
    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>

            <!-- <td class="Browse"><div align="center">Categoria</div></td> -->
            <td class="Browse"><div align="center">Tipo</div></td>
            <td class="Browse"><div align="center">Descripcion</div></td>
            <td class="Browse"><div align="center">Monto</div></td>

          </tr>
          </thead>
          <?

          while($bus_articulos_orden_compra_servicio = mysql_fetch_array($sql_asignaciones)){

            if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 1){
				$color = "#9CF";
				$tipo_concepto =  "Asignacion";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 2){
				$tipo_concepto =  "Deduccion";
				$color = "#FFC";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 4){
				$tipo_concepto =  "Aporte Patronal";
			}else{
				$tipo_concepto = "Neutro";
			}

			if($tipo_concepto != "Aporte Patronal"){
          	if($bus_articulos_orden_compra_servicio["estado"] == "rechazado"){
			?>
			<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
			<?
			}else if($bus_articulos_orden_compra_servicio["estado"] == "sin disponibilidad"){
			?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
			}else{
			?>
			<tr style="background-color:<?=$color?>" onMouseOver="setRowColor(this, 0, 'over', '<?=$color?>', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '<?=$color?>', '#EAFFEA', '#FFFFAA')">
			<?

			}
		  ?>

         <!--  <td class='Browse' align='left'><?=$bus_articulos_orden_compra_servicio["codigo"]?></td>-->
            <td class='Browse' align='left'>
			<?=$tipo_concepto?>
            </td>
             <td class='Browse' align='left'><?=$bus_articulos_orden_compra_servicio["descripcion"]?>
              <div align="right"></div></td>


          <td class="Browse" align="right">
				<?
				echo number_format($bus_articulos_orden_compra_servicio["precio_unitario"],2,',','.');
                ?>             </td>
				<?
                if($bus_orden["estado"] == "elaboracion"){
				?>
            <td class='Browse' align="center">
			<a href="javascript:;" onclick=""><a href="javascript:;" onclick=""><img src="imagenes/refrescar.png" onclick="
                                actualizarPrecioCantidad(<?=$bus_articulos_orden_compra_servicio["idorden_compra_servicio"]?>,
                                document.getElementById('precio<?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>').value,
                                document.getElementById('cantidad<?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>').value,
                                <?=$bus_articulos_orden_compra_servicio["idarticulos_servicios"]?>,
                                <?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>,
                                document.getElementById('anio').value,
                                document.getElementById('fuente_financiamiento').value,
                                document.getElementById('tipo_presupuesto').value,
                                document.getElementById('id_ordinal').value,
                                document.getElementById('contribuyente_ordinario').value,
                                document.getElementById('anio').value)"
                                title="Actualizar Precio y Cantidad" /></a></td>
<td class='Browse' align="center">
                    <a href="javascript:;" onClick="eliminarMateriales(<?=$bus_articulos_orden_compra_servicio["idorden_compra_servicio"]?>, <?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>, <?=$bus_articulos_orden_compra_servicio["idsolicitud_cotizacion"]?>, document.getElementById('id_categoria_programatica').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value, document.getElementById('contribuyente_ordinario').value)">
           			<img src="imagenes/delete.png" title="Eliminar Materiales">           		</a>            </td>
              <?
                }
				?>
          </tr>
          <?
		  }
          }
          ?>
        </table>





	<?




	}
}




if($ejecutar == "consultarConceptosAportes"){
	$sql_suma_asignaciones = mysql_query("select SUM(articulos_compra_servicio.total) as total_asignaciones
											from
											articulos_servicios,
											articulos_compra_servicio
											where
											articulos_compra_servicio.idorden_compra_servicio = '".$id_orden_compra_aportes."'
											and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios
											and articulos_servicios.tipo_concepto = 4")or die(mysql_error());
	$bus_suma_asignaciones = mysql_fetch_array($sql_suma_asignaciones);


	$total_deducciones = 0;

	$total = $bus_suma_asignaciones["total_asignaciones"] - $total_deducciones;
	$sql_actualizar_compra_servicio = mysql_query("update orden_compra_servicio
														set exento = '".$total_deducciones."',
														sub_total = '".$bus_suma_asignaciones["total_asignaciones"]."',
														total = '".$total."'
														where
													idorden_compra_servicio = ".$id_orden_compra_aportes."")or die($id_orden_compra_aportes."aaaaaERROR EN LA ACTUALIZACION DE LOS TOTALES APORTES ".mysql_error());

	if($tipo == "detallado"){


	$sql_articulos_orden_compra_servicio = mysql_query("select * from articulos_compra_servicio,
																		unidad_medida,
																		articulos_servicios,
																		categoria_programatica
									 where
									 	articulos_compra_servicio.idorden_compra_servicio = '".$id_orden_compra_aportes."'
									  	and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios
										and articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida
										and categoria_programatica.idcategoria_programatica = articulos_compra_servicio.idcategoria_programatica
										order by articulos_servicios.tipo_concepto,
										categoria_programatica.codigo,
										articulos_compra_servicio.idarticulos_compra_servicio")or die(mysql_error());

	$num = mysql_num_rows($sql_articulos_orden_compra_servicio);





	?>
    <table cellpadding="3" cellspacing="10">
        <tr>
            <td style="cursor:pointer" bgcolor="#EAEAEA" onclick="consultarConceptosAportes('<?=$id_orden_compra_aportes?>', 'resumido')"><strong>Resumido</strong></td>
            <td style="cursor:pointer" bgcolor="#EAEAEA" onclick="consultarConceptosAportes('<?=$id_orden_compra_aportes?>', 'detallado')"><strong>Detallado</strong></td>
        </tr>
    </table>
    <table style="margin-left:8px">
        <tr>
            <td style="background-color:#9CF" width="150px" align="center"><strong>Aportes Patronales</strong></td>
            <td  align="center" bgcolor="#EAEAEA"><strong>Total Aportes: </strong><?=number_format($bus_suma_asignaciones["total_asignaciones"],2,",",".")?></td>
            <td align="center" bgcolor="#EAEAEA">&nbsp;&nbsp;<strong>Total a Pagar: </strong><?=number_format($bus_suma_asignaciones["total_asignaciones"]-$bus_suma_deducciones["total_deducciones"],2,",",".")?></td>
        </tr>
    </table>
    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <?
            if($bus_orden["duplicados"] == 1){
			?>
			<td class="Browse"><div align="center">Duplicados</div></td>
			<?
			}
			?>
            <td class="Browse"><div align="center">Categoria</div></td>
            <td class="Browse"><div align="center">Tipo</div></td>
            <td class="Browse"><div align="center">Descripci&oacute;n</div></td>
            <td class="Browse"><div align="center">Monto</div></td>
			<?
            if($bus_orden["estado"] == "elaboracion"){
            ?>
            <td class="Browse" colspan="2"><div align="center">Acciones</div></td>
			<?
            }
            ?>
          </tr>
          </thead>
          <?

          while($bus_articulos_orden_compra_servicio = mysql_fetch_array($sql_articulos_orden_compra_servicio)){

            if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 1){
				$color = "#9CF";
				$tipo_concepto =  "Asignacion";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 2){
				$tipo_concepto =  "Deduccion";
				$color = "#FFC";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 4){
				$tipo_concepto =  "Aporte Patronal";
			}else{
				$tipo_concepto = "Neutro";
			}
		if($tipo_concepto == "Aporte Patronal"){
          	if($bus_articulos_orden_compra_servicio["estado"] == "rechazado"){
			?>
			<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
			<?
			}else if($bus_articulos_orden_compra_servicio["estado"] == "sin disponibilidad"){
			?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
			}else{
			?>
			<tr style="background-color:<?=$color?>" onMouseOver="setRowColor(this, 0, 'over', '<?=$color?>', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '<?=$color?>', '#EAFFEA', '#FFFFAA')">
			<?

			}
		  ?>
          <?
          if($bus_orden["duplicados"] == 1){
			  if($bus_articulos_orden_compra_servicio["duplicado"] == 1){
			  ?>
				<td class='Browse' align='center'><img src="imagenes/advertencia.png" title="Articulo Duplicado"></td>
			   <?
			   }else{
			   	?>
				<td class='Browse' align='center'>&nbsp;</td>
			   <?
			   }
		   }
		   ?>
           	<td class='Browse' align='left'><?=$bus_articulos_orden_compra_servicio["codigo"]?></td>
            <td class='Browse' align='left'>
			<?=$tipo_concepto?>
            </td>
            <td class='Browse' align='left'><?=$bus_articulos_orden_compra_servicio[30]?>
              <div align="right"></div></td>


          	<td class="Browse" align="right">
				<?
				echo number_format($bus_articulos_orden_compra_servicio["precio_unitario"],2,',','.');
                ?>             </td>

        </tr>
          <?
          }

		  }

          ?>
        </table>
        <?

	}else{
	// AQUI VA SI ES RESUMIDO	 **********************************************************************************************************
		$sql_asignaciones = mysql_query("select SUM(articulos_compra_servicio.total) as total,
												SUM(articulos_compra_servicio.cantidad) as cantidad,
												SUM(articulos_compra_servicio.precio_unitario) as precio_unitario,
												articulos_servicios.descripcion,
												articulos_servicios.tipo_concepto,
												articulos_compra_servicio.estado
																		from
																		articulos_compra_servicio,
																		unidad_medida,
																		articulos_servicios,
																		categoria_programatica
									 where
									 	articulos_compra_servicio.idorden_compra_servicio = '".$id_orden_compra_aportes."'
									  	and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios
										and articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida
										and categoria_programatica.idcategoria_programatica = articulos_compra_servicio.idcategoria_programatica
										group by articulos_servicios.idarticulos_servicios
										order by articulos_servicios.tipo_concepto")or die(mysql_error());
	?>

	<table cellpadding="3" cellspacing="10">
        <tr>
        <td style="cursor:pointer" bgcolor="#EAEAEA" onclick="consultarConceptosAportes('<?=$id_orden_compra_aportes?>', 'resumido')"><strong>Resumido</strong></td>
        <td style="cursor:pointer" bgcolor="#EAEAEA" onclick="consultarConceptosAportes('<?=$id_orden_compra_aportes?>', 'detallado')"><strong>Detallado</strong></td>
        </tr>
    </table>
    <table style="margin-left:8px">
        <tr>
            <td style="background-color:#9CF" width="150px" align="center"><strong>Aportes Patronales</strong></td>
            <td  align="center" bgcolor="#EAEAEA"><strong>Total Aportes: </strong><?=number_format($bus_suma_asignaciones["total_asignaciones"],2,",",".")?></td>
            <td align="center" bgcolor="#EAEAEA">&nbsp;&nbsp;<strong>Total a Pagar: </strong><?=number_format($bus_suma_asignaciones["total_asignaciones"]-$bus_suma_deducciones["total_deducciones"],2,",",".")?></td>
        </tr>
    </table>
    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <?
            if($bus_orden["duplicados"] == 1){
			?>
			<td class="Browse"><div align="center">Duplicados</div></td>
			<?
			}
			?>
            <!-- <td class="Browse"><div align="center">Categoria</div></td> -->
            <td class="Browse"><div align="center">Tipo</div></td>
            <td class="Browse"><div align="center">Descripcion</div></td>
            <td class="Browse"><div align="center">Monto</div></td>
			<?
            if($bus_orden["estado"] == "elaboracion"){
            ?>
            <td class="Browse" colspan="2"><div align="center">Acciones</div></td>
			<?
            }
            ?>
          </tr>
          </thead>
          <?

          while($bus_articulos_orden_compra_servicio = mysql_fetch_array($sql_asignaciones)){

            if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 1){
				$color = "#9CF";
				$tipo_concepto =  "Asignacion";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 2){
				$tipo_concepto =  "Deduccion";
				$color = "#FFC";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 4){
				$tipo_concepto =  "Aporte Patronal";
			}else{
				$tipo_concepto = "Neutro";
			}

			if($tipo_concepto == "Aporte Patronal"){
          	if($bus_articulos_orden_compra_servicio["estado"] == "rechazado"){
			?>
			<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
			<?
			}else if($bus_articulos_orden_compra_servicio["estado"] == "sin disponibilidad"){
			?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
			}else{
			?>
			<tr style="background-color:<?=$color?>" onMouseOver="setRowColor(this, 0, 'over', '<?=$color?>', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '<?=$color?>', '#EAFFEA', '#FFFFAA')">
			<?

			}
		  ?>
          <?
          if($bus_orden["duplicados"] == 1){
			  if($bus_articulos_orden_compra_servicio["duplicado"] == 1){
			  ?>
				<td class='Browse' align='center'><img src="imagenes/advertencia.png" title="Articulo Duplicado"></td>
			   <?
			   }else{
			   	?>
				<td class='Browse' align='center'>&nbsp;</td>
			   <?
			   }
		   }
		   ?>
         <!--  <td class='Browse' align='left'><?=$bus_articulos_orden_compra_servicio["codigo"]?></td>-->
            <td class='Browse' align='left'>
			<?=$tipo_concepto?>
            </td>
             <td class='Browse' align='left'><?=$bus_articulos_orden_compra_servicio["descripcion"]?>
              <div align="right"></div></td>


          <td class="Browse" align="right">
				<?
				echo number_format($bus_articulos_orden_compra_servicio["precio_unitario"],2,',','.');
                ?>             </td>

          </tr>
          <?
		  }
          }
          ?>
        </table>





	<?




	}
}









if($ejecutar == "consultarPartidas"){

$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
$bus_orden = mysql_fetch_array($sql_orden);
//and idclasificador_presupuestario = ".$bus_orden["idclasificador_presupuestario"]."

$sql_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."' order by idmaestro_presupuesto");

$num_partidas = mysql_num_rows($sql_partidas);
if($num_partidas != 0){
	?>
        <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <td class="Browse"><div align="center">Categoria</div></td>
            <td class="Browse" colspan="4"><div align="center">Partida</div></td>
            <td class="Browse"><div align="center">Descripci&oacute;n</div></td>
            <td class="Browse"><div align="center">Disponible</div></td>
            <td class="Browse"><div align="center">Monto a Comprometer</div></td>
          </tr>
          </thead>
          <?
          while($bus_partidas = mysql_fetch_array($sql_partidas)){

		  $sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = ".$bus_partidas["idmaestro_presupuesto"]."");
		  $bus_maestro = mysql_fetch_array($sql_maestro);




          	if($bus_partidas["estado"] == "sobregiro"){
		  ?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
			}else if($bus_partidas["estado"] == "disponible"){
			?>

            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			<?
			}


          $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_maestro["idclasificador_presupuestario"]."'");
		  $bus_clasificador = mysql_fetch_array($sql_clasificador);
		  ?>



            <td class='Browse' align='left'>
			<?
            $sql_categoria_programatica = mysql_query("select * from categoria_programatica where idcategoria_programatica = '".$bus_maestro["idcategoria_programatica"]."'");
			$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
			echo $bus_categoria_programatica["codigo"];
			?>
            </td>
            <td class='Browse' align='left'><?=$bus_clasificador["partida"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["generica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["sub_especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["denominacion"]?></td>

    	      <td class='Browse' align="right"><?=number_format(consultarDisponibilidad($bus_maestro["idRegistro"]),2,',','.')?></td>
	          <td class='Browse' align='right'><?=number_format($bus_partidas["monto"],2,',','.')?></td>
          </tr>
          <?
          }
          ?>
        </table>
<?
    }else{
	echo "No hay Partidas Asociadas";
    }
}



if($ejecutar == "consultarPartidasAportes"){

$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra_aportes."'");
$bus_orden = mysql_fetch_array($sql_orden);
//and idclasificador_presupuestario = ".$bus_orden["idclasificador_presupuestario"]."

$sql_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra_aportes."' order by idmaestro_presupuesto");

$num_partidas = mysql_num_rows($sql_partidas);
if($num_partidas != 0){
	?>
        <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <td class="Browse"><div align="center">Categoria</div></td>
            <td class="Browse" colspan="4"><div align="center">Partida</div></td>
            <td class="Browse"><div align="center">Descripci&oacute;n</div></td>
            <td class="Browse"><div align="center">Disponible</div></td>
            <td class="Browse"><div align="center">Monto a Comprometer</div></td>
          </tr>
          </thead>
          <?
          while($bus_partidas = mysql_fetch_array($sql_partidas)){

		  $sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = ".$bus_partidas["idmaestro_presupuesto"]."");
		  $bus_maestro = mysql_fetch_array($sql_maestro);




          	if($bus_partidas["estado"] == "sobregiro"){
		  ?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
			}else if($bus_partidas["estado"] == "disponible"){
			?>

            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			<?
			}


          $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_maestro["idclasificador_presupuestario"]."'");
		  $bus_clasificador = mysql_fetch_array($sql_clasificador);
		  ?>



            <td class='Browse' align='left'>
			<?
            $sql_categoria_programatica = mysql_query("select * from categoria_programatica where idcategoria_programatica = '".$bus_maestro["idcategoria_programatica"]."'");
			$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
			echo $bus_categoria_programatica["codigo"];
			?>
            </td>
            <td class='Browse' align='left'><?=$bus_clasificador["partida"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["generica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["sub_especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["denominacion"]?></td>

    	      <td class='Browse' align="right"><?=number_format(consultarDisponibilidad($bus_maestro["idRegistro"]),2,',','.')?></td>
	          <td class='Browse' align='right'><?=number_format($bus_partidas["monto"],2,',','.')?></td>
          </tr>
          <?
          }
          ?>
        </table>
<?
    }else{
	echo "No hay Partidas Asociadas";
    }
}


if($ejecutar == "modificarNomina"){
	$sql_actualizar= mysql_query("update generar_nomina set descripcion = '".$justificacion."',
																		idfuente_financiamiento = '".$idfuente_financiamiento."',
																		idtipo_presupuesto = '".$idtipo_presupuesto."',
																		anio = '".$anio."',
																		idcategoria_programatica = '".$idcentro_costo_fijo."',
																		idbeneficiarios = '".$id_beneficiarios."'
										where idgenerar_nomina ='".$idgenerar_nomina."'");

	$sql_buscar_certificacion = mysql_query("select * from generar_nomina where idgenerar_nomina ='".$idgenerar_nomina."'");
	$bus_buscar_certificacion = mysql_fetch_array($sql_buscar_certificacion);
	$id_orden_compra = $bus_buscar_certificacion["idorden_compra_servicio"];
	//ACTUALIZO LA CERTIFICACION DE COMPROMISO

	$sql_certificacion = mysql_query("update orden_compra_servicio set justificacion = '".$justificacion."',
																		idfuente_financiamiento = '".$idfuente_financiamiento."',
																		idtipo_presupuesto = '".$idtipo_presupuesto."',
																		anio = '".$anio."',
																		idcategoria_programatica = '".$idcentro_costo_fijo."',
																		idbeneficiarios = '".$id_beneficiarios."'
															where idorden_compra_servicio = ".$id_orden_compra."");

	//ACTUALIZO EL ASIENTO CONTABLE
	$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_compra."
														and tipo_movimiento = 'compromiso'");
	if (mysql_num_rows($sql_validar_asiento) > 0){
		$sql_asiento_contable = mysql_query("update asiento_contable set detalle = '".$justificacion."'
														where iddocumento = ".$id_orden_compra."
															and tipo_movimiento = 'compromiso'
															and (estado = 'elaboracion' or estado = 'procesado')")or die("error aqui1 ".mysql_error());

		$sql_asiento_contable_anulado = mysql_query("update asiento_contable set detalle = '".'ANULACION DE ASIENTO: '.$justificacion."'
														where iddocumento = ".$id_orden_compra."
														and tipo_movimiento = 'compromiso'
														and estado = 'anulado'")or die("error aqui ".mysql_error());
	}
}










if($ejecutar == "nuevaCertificacion") {
	//echo "entro";
    $sql_oc = mysql_query("select * from generar_nomina where idgenerar_nomina = '" . $idgenerar_nomina . "'")or die(mysql_error());
    $bus_oc = mysql_fetch_array($sql_oc);
    $idcertificacion = $bus_oc["idorden_compra_servicio"];
    $idcertificacion_aporte = $bus_oc["idorden_compra_servicio_aporte"];
    $idfuente_financiamiento = $bus_oc["idfuente_financiamiento"];
    $idtipo_presupuesto = $bus_oc["idtipo_presupuesto"];;
    $anio = $bus_oc["anio"];;
    $sql_limpiar = mysql_query("delete from conceptos_desagregados where idgenerar_nomina = '" . $idgenerar_nomina . "'");
    //echo $idcertificacion;
    if ($idcertificacion <> '') {
       $sql_eliminar_oc = mysql_query("delete from orden_compra_servicio where idorden_compra_servicio = '".$bus_oc["idorden_compra_servicio"]."'")or die (mysql_error());
       $sql_partidas_oc = mysql_query("delete from partidas_orden_compra_servicio where idorden_compra_servicio = '".$bus_oc["idorden_compra_servicio"]."'")or die (mysql_error());
       $sql_articulos_oc = mysql_query("delete from articulos_compra_servicio where idorden_compra_servicio = '".$bus_oc["idorden_compra_servicio"]."'")or die (mysql_error());
       $sql_asiento = mysql_query("select * from asiento_contable where iddocumento = '".$bus_oc["idorden_compra_servicio"]."'")or die (mysql_error());
       $bus_asiento = mysql_fetch_array($sql_asiento);
       $sql_easiento = mysql_query("delete from asiento_contable where iddocumento = '".$bus_oc["idorden_compra_servicio"]."'")or die (mysql_error());
       $sql_ecasiento = mysql_query("delete from cuentas_asiento_contable where idasiento_contable = '".$bus_asiento["idasiento_contable"]."'")or die (mysql_error());
       $idcertificacion = '';
    }
    if ($idcertificacion_aporte <> '') {
        $sql_eliminar_oc = mysql_query("delete from orden_compra_servicio where idorden_compra_servicio = '".$bus_oc["idorden_compra_servicio_aporte"]."'")or die (mysql_error());
        $sql_partidas_oc = mysql_query("delete from partidas_orden_compra_servicio where idorden_compra_servicio = '".$bus_oc["idorden_compra_servicio_aporte"]."'")or die (mysql_error());
        $sql_articulos_oc = mysql_query("delete from articulos_compra_servicio where idorden_compra_servicio = '".$bus_oc["idorden_compra_servicio_aporte"]."'")or die (mysql_error());
        $sql_asiento = mysql_query("select * from asiento_contable where iddocumento = '".$bus_oc["idorden_compra_servicio_aporte"]."'")or die (mysql_error());
        $bus_asiento = mysql_fetch_array($sql_asiento);
        $sql_easiento = mysql_query("delete from asiento_contable where iddocumento = '".$bus_oc["idorden_compra_servicio_aporte"]."'")or die (mysql_error());
        $sql_ecasiento = mysql_query("delete from cuentas_asiento_contable where idasiento_contable = '".$bus_asiento["idasiento_contable"]."'")or die (mysql_error());
        $idcertificacion_aporte = '';
    }

	/// GUARDO LOS DATOS GENERALES DE LA CERTIFICACION
	$sql_generar_nomina = mysql_query("select tn.idtipo_documento,
									  gn.descripcion,
									  gn.idbeneficiarios,
									  gn.idcategoria_programatica
												  				from
												  			generar_nomina gn,
															tipo_nomina tn
																where
															gn.idgenerar_nomina = '".$idgenerar_nomina."'
															and tn.idtipo_nomina =  gn.idtipo_nomina")or die("AQUIIIII".mysql_error());
	$bus_generar_nomina = mysql_fetch_array($sql_generar_nomina);
	$idtipo_documento = $bus_generar_nomina["idtipo_documento"];
	$idcentro_costo_fijo = $bus_generar_nomina["idcategoria_programatica"];

	$sql_relacion_generar_nomina = mysql_query("select * from relacion_generar_nomina
												where idgenerar_nomina = '".$idgenerar_nomina."'
												")or die(mysql_error());

	while($bus_conceptos =mysql_fetch_array($sql_relacion_generar_nomina)){
		$idconcepto = $bus_conceptos["idconcepto"];

		if($idcertificacion == "" || $idcertificacion == 0){
			$sql_cargar_certificacion = mysql_query("insert into orden_compra_servicio(tipo,
																			   fecha_elaboracion,
																			   idbeneficiarios,
																			   idcategoria_programatica,
																			   anio,
																			   idfuente_financiamiento,
																			   idtipo_presupuesto,
																			   idordinal,
																			   justificacion,
																			   estado,
																			   ubicacion,
																			   status,
																			   usuario,
																			   fechayhora)
																							VALUES('".$idtipo_documento."',
																						   			'".date("Y-m-d")."',
																									'".$bus_generar_nomina["idbeneficiarios"]."',
																									'".$idcentro_costo_fijo."',
																									'".$anio."',
																									'".$idfuente_financiamiento."',
																									'".$idtipo_presupuesto."',
																									'0',
																									'".$bus_generar_nomina["descripcion"]."',
																									'elaboracion',
																									'0',
																									'a',
																									'".$login."',
																									'".$fh."')")or die("ERROR CREANDO LA ORDEN DE COMPRA: ".mysql_error());
			$idcertificacion = mysql_insert_id();



			$sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$idtipo_documento."'");
			$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);



			if ($bus_cuentas_contables["tabla_debe"] != '' and $bus_cuentas_contables["idcuenta_debe"] != 0 and $bus_cuentas_contables["tabla_haber"] != '' and $bus_cuentas_contables["idcuenta_haber"] != ''){
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
																				'2',
																				'".$bus_generar_nomina["descripcion"]."',
																				'compromiso',
																				'".$idcertificacion."',
																				'elaboracion',
																				'a',
																				'".$login."',
																				'".date("Y-m-d H:i:s")."',
																				'2')");

				if($sql_contable){
					$idasiento_contable = mysql_insert_id();
					$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																		tabla,
																		idcuenta,
																		afecta
																			)values(
																					'".$idasiento_contable."',
																					'".$bus_cuentas_contables["tabla_debe"]."',
																					'".$bus_cuentas_contables["idcuenta_debe"]."',
																					'debe')");
					$sql_cuenta_contable_haber = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																		tabla,
																		idcuenta,
																		afecta
																			)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_haber"]."',
																				'".$bus_cuentas_contables["idcuenta_haber"]."',
																				'haber')");
				}
			}
		}


		if($bus_conceptos['tabla'] == "conceptos_nomina"){
			//echo "EL MONTO TOTAL ES: ".$con[2];
			$sql_concepto = mysql_query("select * from conceptos_nomina where idconceptos_nomina = '".$bus_conceptos['idconcepto']."'")or die("tttttt".mysql_error());
			$bus_concepto = mysql_fetch_array($sql_concepto);

			$tipo_concepto = $bus_concepto["tipo_concepto"];
			$idclasificador_presupuestario = $bus_concepto["idclasificador_presupuestario"];
			$idordinal = $bus_concepto["idordinal"];
			$idarticulos_servicios = $bus_concepto["idarticulos_servicios"];
			$codigo_concepto = $bus_concepto["codigo"];
			$descripcion_concepto = $bus_concepto["descripcion"];

	        $sql_tipo_concepto = mysql_query("select * from tipo_conceptos_nomina where idconceptos_nomina = '".$bus_concepto["tipo_concepto"]."'");
	        $bus_tipo_concepto = mysql_fetch_array($sql_tipo_concepto);

	        if($bus_tipo_concepto["afecta"] == "Asignacion"){
	            $tipo_concepto = 1;
	        }else if($bus_tipo_concepto["afecta"] == "Deduccion"){
	            $tipo_concepto = 2;
	        }else if($bus_tipo_concepto["afecta"] == "Aporte"){
	            $tipo_concepto = 4;
	        }else{
	            $tipo_concepto = 3;
	        }

	            //GUARDO LA CERTIFICACION PARA LOS APORTES PATRONALES

	        if($tipo_concepto == 4){

                $sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$idtipo_documento."'");
                $bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);

                if($idcertificacion_aporte == "" || $idcertificacion_aporte == 0){
						$sql_cargar_certificacion = mysql_query("insert into orden_compra_servicio(tipo,
																			   fecha_elaboracion,
																			   idbeneficiarios,
																			   idcategoria_programatica,
																			   anio,
																			   idfuente_financiamiento,
																			   idtipo_presupuesto,
																			   idordinal,
																			   justificacion,
																			   estado,
																			   ubicacion,
																			   status,
																			   usuario,
																			   fechayhora)
																							VALUES('".$idtipo_documento."',
																						   			'".date("Y-m-d")."',
																									'".$bus_generar_nomina["idbeneficiarios"]."',
																									'".$idcentro_costo_fijo."',
																									'".$anio."',
																									'".$idfuente_financiamiento."',
																									'".$idtipo_presupuesto."',
																									'0',
																									' APORTE PATRONAL - ".$bus_generar_nomina["descripcion"]."',
																									'elaboracion',
																									'0',
																									'a',
																									'".$login."',
																									'".$fh."')")or die("ERROR CREANDO LA ORDEN DE COMPRA: ".mysql_error());
                    $idcertificacion_aporte = mysql_insert_id();

	                if ($bus_cuentas_contables["tabla_debe"] != '' and $bus_cuentas_contables["idcuenta_debe"] != 0 and $bus_cuentas_contables["tabla_haber"] != '' and $bus_cuentas_contables["idcuenta_haber"] != ''){
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
																			'2',
																			'".$bus_generar_nomina["descripcion"]."',
																			'compromiso',
																			'".$idcertificacion_aporte."',
																			'elaboracion',
																			'a',
																			'".$login."',
																			'".date("Y-m-d H:i:s")."',
																			'2')");

	                    if($sql_contable){
	                        $idasiento_contable = mysql_insert_id();
	                        $sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_debe"]."',
																				'".$bus_cuentas_contables["idcuenta_debe"]."',
																				'debe')");
	                        $sql_cuenta_contable_haber = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_haber"]."',
																				'".$bus_cuentas_contables["idcuenta_haber"]."',
																				'haber')");

	                    }
	                }
            	}

	            //VALIDO QUE EL CONCEPTO ESTE EN LA TABLA DE ARTICULOS SERVICIOS PARA LOS APORTES
				if($idarticulos_servicios == 0){

					$sql_ingresar_articulo = mysql_query("insert into articulos_servicios(codigo,
																					  tipo,
																				  descripcion,
																				  idunidad_medida,
																				  idramo_articulo,
																				  idclasificador_presupuestario,
																				  idordinal,
																				  idimpuestos,
																				  exento,
																				  status,
																				  tipo_concepto,
																				  usuario,
																				  fechayhora
																				 )VALUES('".$codigo_concepto."',
																				  					'".$idtipo_documento."',
																									'".$descripcion_concepto."',
																									'7',
																									'12',
																									'".$idclasificador_presupuestario."',
																									'".$idordinal."',
																									'1',
																									'1',
																									'a',
																									'".$tipo_concepto."',
																									'".$login."',
																									'".$fh."')")or die("HOLAAAAA".mysql_error());

					$id = mysql_insert_id();
					//echo "ESTE ES EL ID:  ".$id;
					if($id != 0){
						$sql_actualizar = mysql_query("update conceptos_nomina set idarticulos_servicios = '".$id."' where idconceptos_nomina = '".$con[1]."'")or die("wwwww".mysql_error());
						if ($idcentro_costo_fijo == 0 or $idcentro_costo_fijo == ''){
							$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica
																  			from
																		trabajador tr,
																		niveles_organizacionales no
																			where
																		tr.idtrabajador = '".$bus_conceptos['idtrabajador']."'
																		and no.idniveles_organizacionales = tr.centro_costo")or die("eeeeee".mysql_error());
							$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
							$idcentro_costo = $bus_categoria_programatica["idcategoria_programatica"];
						}else{
							$idcentro_costo = $idcentro_costo_fijo;
						}
						ingresarMaterial($id, $idcertificacion_aporte, $idcentro_costo, 1, $bus_conceptos["total"], $idfuente_financiamiento, $tipo_presupuesto,$idclasificador_presupuestario,$idordinal,$anio,$idcertificacion_aporte);
					}


	            }else{
					$id = $idarticulos_servicios;
					if ($idcentro_costo_fijo == 0 or $idcentro_costo_fijo == ''){
						$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica
															  			from
																	trabajador tr,
																	niveles_organizacionales no
																		where
																	tr.idtrabajador = '".$bus_conceptos['idtrabajador']."'
																	and no.idniveles_organizacionales = tr.centro_costo")or die("eeeeee".mysql_error());
						$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
						$idcentro_costo = $bus_categoria_programatica["idcategoria_programatica"];
					}else{
						$idcentro_costo = $idcentro_costo_fijo;
					}

					$sql_busqueda = mysql_query("select * from articulos_compra_servicio
																where
																idarticulos_servicios = '".$id."'
																and idcategoria_programatica = '".$bus_categoria_programatica["idcategoria_programatica"]."'
																and idorden_compra_servicio = '".$idcertificacion_aporte."'")or die("ertyuui".mysql_error());

					$num_busqueda = mysql_num_rows($sql_busqueda);

					if($num_busqueda > 0){
						$bus_busqueda = mysql_fetch_array($sql_busqueda);
						actualizarPrecioCantidad($id, $idcertificacion_aporte, $idcentro_costo, 1, $bus_conceptos["total"], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal, $bus_busqueda["idarticulos_compra_servicio"], $bus_conceptos['idtrabajador'],$anio,$idcertificacion_aporte);
					}else{
						ingresarMaterial($id, $idcertificacion_aporte, $idcentro_costo, 1, $bus_conceptos["total"], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal,$anio,$idcertificacion_aporte);
					}
				}
	        } //CIERRO LA VALIDACION SI EL CONCEPTO ES DE APORTE

	        //VALIDO SI ES UN CONCEPTO DE TIPO ASIGNACION O DEDUCCION
	        if($tipo_concepto == 1 || $tipo_concepto == 2){
	            //VALIDO QUE EL CONCEPTO ESTE EN LA TABLA DE ARTICULOS SERVICIOS
				if($idarticulos_servicios == 0){

					$sql_ingresar_articulo = mysql_query("insert into articulos_servicios(codigo,
																			  tipo,
																			  descripcion,
																			  idunidad_medida,
																			  idramo_articulo,
																			  idclasificador_presupuestario,
																			  idordinal,
																			  idimpuestos,
																			  exento,
																			  status,
																			  tipo_concepto,
																			  usuario,
																			  fechayhora
																			 )VALUES('".$codigo_concepto."',
																			  					'".$idtipo_documento."',
																								'".$descripcion_concepto."',
																								'7',
																								'12',
																								'".$idclasificador_presupuestario."',
																								'".$idordinal."',
																								'1',
																								'1',
																								'a',
																								'".$tipo_concepto."',
																								'".$login."',
																								'".$fh."')")or die("HOLAAAAA".mysql_error());

					$id = mysql_insert_id();
					//echo "ESTE ES EL ID:  ".$id;
					if($id != 0){
						$sql_actualizar = mysql_query("update conceptos_nomina set idarticulos_servicios = '".$id."' where idconceptos_nomina = '".$con[1]."'")or die("wwwww".mysql_error());

						if ($idcentro_costo_fijo == 0 or $idcentro_costo_fijo == ''){
							$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica
																  			from
																		trabajador tr,
																		niveles_organizacionales no
																			where
																		tr.idtrabajador = '".$bus_conceptos['idtrabajador']."'
																		and no.idniveles_organizacionales = tr.centro_costo")or die("eeeeee".mysql_error());
							$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
							$idcentro_costo = $bus_categoria_programatica["idcategoria_programatica"];

						}else{
							$idcentro_costo = $idcentro_costo_fijo;
						}
						ingresarMaterial($id, $idcertificacion, $idcentro_costo, 1, $bus_conceptos["total"], $idfuente_financiamiento, $tipo_presupuesto,$idclasificador_presupuestario,$idordinal,$anio,$idcertificacion_aporte);
					}

	            }else{
					$id = $idarticulos_servicios;
					//$sql_actualizar = mysql_query("update conceptos_nomina set idarticulos_servicios = '".$id."' where idconceptos_nomina = '".$con[1]."'")or die("aquiii".mysql_error());
					/*$sql_actualizar = mysql_query("update articulos_servicios set
												  				idclasificador_presupuestario = '".$idclasificador_presupuestario."'
																where idarticulos_servicios = '".$id."'");
					*/
					if ($idcentro_costo_fijo == 0 or $idcentro_costo_fijo == ''){
						$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica
															  			from
																	trabajador tr,
																	niveles_organizacionales no
																		where
																	tr.idtrabajador = '".$bus_conceptos['idtrabajador']."'
																	and no.idniveles_organizacionales = tr.centro_costo")or die("eeeeee".mysql_error());
						$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
						$idcentro_costo = $bus_categoria_programatica["idcategoria_programatica"];
					}else{
						$idcentro_costo = $idcentro_costo_fijo;
					}

					$sql_busqueda = mysql_query("select * from articulos_compra_servicio
																where
																idarticulos_servicios = '".$id."'
																and idcategoria_programatica = '".$idcentro_costo."'
																and idorden_compra_servicio = '".$idcertificacion."'")or die("ertyuui".mysql_error());

					$num_busqueda = mysql_num_rows($sql_busqueda);

					if($num_busqueda > 0){
						$bus_busqueda = mysql_fetch_array($sql_busqueda);
						actualizarPrecioCantidad($id, $idcertificacion, $idcentro_costo, 1, $bus_conceptos["total"], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal, $bus_busqueda["idarticulos_compra_servicio"], $bus_conceptos['idtrabajador'],$anio,$idcertificacion_aporte);
					}else{
						ingresarMaterial($id, $idcertificacion, $idcentro_costo, 1, $bus_conceptos["total"], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal,$anio,$idcertificacion_aporte);
					}
				}
			} //FIN DE LA VALIDACION DEL CONCEPTO TIPO ASIGNACION O DEDUCCION

		}else if($bus_conceptos['tabla'] == "constantes_nomina"){
			// AQUI VA EL TEXTO SI ES UNA CONSTANTE

			$sql_constante = mysql_query("select * from constantes_nomina where idconstantes_nomina = '".$bus_conceptos['idconcepto']."'")or die("AQUIII".mysql_error());
			$bus_constante = mysql_fetch_array($sql_constante);


			$idclasificador_presupuestario = $bus_constante["idclasificador_presupuestario"];
			$idordinal = $bus_constante["idordinal"];
			$idarticulos_servicios = $bus_constante["idarticulos_servicios"];
			$codigo_concepto = $bus_constante["codigo"];
			$descripcion_concepto = $bus_constante["descripcion"];

            $sql_tipo_concepto = mysql_query("select * from tipo_conceptos_nomina where idconceptos_nomina = '".$bus_constante["afecta"]."'");
            $bus_tipo_concepto = mysql_fetch_array($sql_tipo_concepto);

            if($bus_tipo_concepto["afecta"] == "Asignacion"){
                $tipo_constante = 1;
            }else if($bus_tipo_concepto["afecta"] == "Deduccion"){
                $tipo_constante = 2;
            }else if($bus_tipo_concepto["afecta"] == "Aporte"){
                $tipo_constante = 4;
            }else{
                $tipo_constante = 3;
            }


        //GUARDO LA CERTIFICACION PARA LOS APORTES PATRONALES

			if($tipo_constante == 4){

				if($idcertificacion_aporte == "" || $idcertificacion_aporte == 0){
					$sql_cargar_certificacion = mysql_query("insert into orden_compra_servicio(tipo,
																		   fecha_elaboracion,
																			   idbeneficiarios,
																			   idcategoria_programatica,
																			   anio,
																			   idfuente_financiamiento,
																			   idtipo_presupuesto,
																			   idordinal,
																			   justificacion,
																			   estado,
																			   ubicacion,
																			   status,
																			   usuario,
																			   fechayhora)
																							VALUES('".$idtipo_documento."',
																						   			'".date("Y-m-d")."',
																									'".$bus_generar_nomina["idbeneficiarios"]."',
																									'".$idcentro_costo_fijo."',
																									'".$anio."',
																									'".$idfuente_financiamiento."',
																									'".$idtipo_presupuesto."',
																									'0',
																									' APORTE PATRONAL - ".$bus_generar_nomina["descripcion"]."',
																									'elaboracion',
																									'0',
																									'a',
																									'".$login."',
																									'".$fh."')")or die("ERROR CREANDO LA ORDEN DE COMPRA: ".mysql_error());
                    $idcertificacion_aporte = mysql_insert_id();

                    $sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$idtipo_documento."'");
                    $bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);

                    if ($bus_cuentas_contables["tabla_debe"] != '' and $bus_cuentas_contables["idcuenta_debe"] != 0 and $bus_cuentas_contables["tabla_haber"] != '' and $bus_cuentas_contables["idcuenta_haber"] != ''){
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
																				'2',
																				'".$bus_generar_nomina["descripcion"]."',
																				'compromiso',
																				'".$idcertificacion_aporte."',
																				'elaboracion',
																				'a',
																				'".$login."',
																				'".date("Y-m-d H:i:s")."',
																				'2')");

                        if($sql_contable){
                            $idasiento_contable = mysql_insert_id();
                            $sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_debe"]."',
																				'".$bus_cuentas_contables["idcuenta_debe"]."',
																				'debe')");
                            $sql_cuenta_contable_haber = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_haber"]."',
																				'".$bus_cuentas_contables["idcuenta_haber"]."',
																				'haber')");

                        }
                    }
                }

				if($idarticulos_servicios == 0){

					$sql_ingresar_articulo = mysql_query("insert into articulos_servicios(codigo,
																				  tipo,
																				  descripcion,
																				  idunidad_medida,
																				  idramo_articulo,
																				  idclasificador_presupuestario,
																				  idordinal,
																				  idimpuestos,
																				  exento,
																				  status,
																				  tipo_concepto,
																				  usuario,
																				  fechayhora)VALUES('".$codigo_concepto."',
																				  					'".$idtipo_documento."',
																									'".$descripcion_concepto."',
																									'7',
																									'12',
																									'".$idclasificador_presupuestario."',
																									'".$idordinal."',
																									'1',
																									'1',
																									'a',
																									'".$tipo_constante."',
																									'".$login."',
																									'".$fh."')")or die("ERROR EN EL INSERT".mysql_error);

					$id = mysql_insert_id();
					if($id != 0){
						$sql_actualizar = mysql_query("update constantes_nomina set idarticulos_servicios = '".$id."' where idconstantes_nomina = '".$con[1]."'")or die("ERROR".mysql_error());
						if ($idcentro_costo_fijo == 0 or $idcentro_costo_fijo == ''){
							$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica
																  			from
																		trabajador tr,
																		niveles_organizacionales no
																			where
																		tr.idtrabajador = '".$bus_conceptos['idtrabajador']."'
																		and no.idniveles_organizacionales = tr.centro_costo")or die("eeeeee".mysql_error());
							$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
							$idcentro_costo = $bus_categoria_programatica["idcategoria_programatica"];
						}else{
							$idcentro_costo = $idcentro_costo_fijo;
						}

						$sql_consulta_articulos = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id."'");
						$bus_consulta_articulos = mysql_fetch_array($sql_consulta_articulos);

						ingresarMaterial($id, $idcertificacion_aporte, $idcentro_costo, 1, $bus_conceptos['total'], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal,$anio,$idcertificacion_aporte);
					}
				}else{

					$id = $idarticulos_servicios;
					/*$sql_actualizar = mysql_query("update constantes_nomina set
												  				idarticulos_servicios = '".$id."' where idconstantes_nomina = '".$bus_conceptos['idconcepto']."'")or die("AQUIIII".mysql_error());
					$sql_actualizar = mysql_query("update articulos_servicios set
												  				idclasificador_presupuestario = '".$idclasificador_presupuestario."'
																where idarticulos_servicios = '".$id."'")or die("qqqqqqq".mysql_error());
					//echo "update conceptos_nomina set idarticulos_servicios = '".$id."' where idconceptos_nomina = '".$con[1]."'";
					*/
					if ($idcentro_costo_fijo == 0 or $idcentro_costo_fijo == ''){
						$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica
															  			from
																	trabajador tr,
																	niveles_organizacionales no
																		where
																	tr.idtrabajador = '".$bus_conceptos['idtrabajador']."'
																	and no.idniveles_organizacionales = tr.centro_costo")or die("eeeeee".mysql_error());
						$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
						$idcentro_costo = $bus_categoria_programatica["idcategoria_programatica"];
					}else{
						$idcentro_costo = $idcentro_costo_fijo;
					}

					$sql_buscar = mysql_query("select * from articulos_compra_servicio
																		where
																		idarticulos_servicios = '".$id."'
																		and idcategoria_programatica = '".$idcentro_costo."'
																		and idorden_compra_servicio = '".$idcertificacion_aporte."'")or die("qqqqqq".mysql_error());
					$num_buscar= mysql_num_rows($sql_buscar);

					if($num_buscar > 0){
						//echo "entro constante ".$bus_consulta_articulos["tipo_concepto"]." id art ".$id;
						$bus_busqueda = mysql_fetch_array($sql_buscar);
						actualizarPrecioCantidad($id, $idcertificacion_aporte, $idcentro_costo, 1, $bus_conceptos['total'], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal, $bus_busqueda["idarticulos_compra_servicio"], $bus_conceptos['idtrabajador'],$anio,$idcertificacion_aporte);
					}else{
						ingresarMaterial($id, $idcertificacion_aporte, $idcentro_costo, 1, $bus_conceptos['total'], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal,$anio,$idcertificacion_aporte);
					}

				}
			} // FIN DE SI ES TIPO APORTE

			// INICIO SI LA CONSTANTE ES TIPO ASIGNACION O DEDUCCION
			if($tipo_concepto==1 || $tipo_concepto ==2){

				if($idarticulos_servicios == 0){

					$sql_ingresar_articulo = mysql_query("insert into articulos_servicios(codigo,
																				  tipo,
																				  descripcion,
																				  idunidad_medida,
																				  idramo_articulo,
																				  idclasificador_presupuestario,
																				  idordinal,
																				  idimpuestos,
																				  exento,
																				  status,
																				  tipo_concepto,
																				  usuario,
																				  fechayhora)VALUES('".$codigo_concepto."',
																				  					'".$idtipo_documento."',
																									'".$descripcion_concepto."',
																									'7',
																									'12',
																									'".$idclasificador_presupuestario."',
																									'".$idordinal."',
																									'1',
																									'1',
																									'a',
																									'".$tipo_constante."',
																									'".$login."',
																									'".$fh."')")or die("ERROR EN EL INSERT".mysql_error);

					$id = mysql_insert_id();
					if($id != 0){
						$sql_actualizar = mysql_query("update constantes_nomina set idarticulos_servicios = '".$id."' where idconstantes_nomina = '".$con[1]."'")or die("ERROR".mysql_error());
						if ($idcentro_costo_fijo == 0 or $idcentro_costo_fijo == ''){
							$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica
																  			from
																		trabajador tr,
																		niveles_organizacionales no
																			where
																		tr.idtrabajador = '".$bus_conceptos['idtrabajador']."'
																		and no.idniveles_organizacionales = tr.centro_costo")or die("eeeeee".mysql_error());
							$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
							$idcentro_costo = $bus_categoria_programatica["idcategoria_programatica"];
						}else{
							$idcentro_costo = $idcentro_costo_fijo;
						}

						$sql_consulta_articulos = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id."'");
						$bus_consulta_articulos = mysql_fetch_array($sql_consulta_articulos);

						ingresarMaterial($id, $idcertificacion, $idcentro_costo, 1, $bus_conceptos['total'], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal,$anio,$idcertificacion_aporte);
					}
				}else{

					$id = $idarticulos_servicios;
					/*$sql_actualizar = mysql_query("update constantes_nomina set
												  				idarticulos_servicios = '".$id."' where idconstantes_nomina = '".$bus_conceptos['idconcepto']."'")or die("AQUIIII".mysql_error());
					$sql_actualizar = mysql_query("update articulos_servicios set
												  				idclasificador_presupuestario = '".$idclasificador_presupuestario."'
																where idarticulos_servicios = '".$id."'")or die("qqqqqqq".mysql_error());
					//echo "update conceptos_nomina set idarticulos_servicios = '".$id."' where idconceptos_nomina = '".$con[1]."'";
					*/
					if ($idcentro_costo_fijo == 0 or $idcentro_costo_fijo == ''){
						$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica
															  			from
																	trabajador tr,
																	niveles_organizacionales no
																		where
																	tr.idtrabajador = '".$bus_conceptos['idtrabajador']."'
																	and no.idniveles_organizacionales = tr.centro_costo")or die("eeeeee".mysql_error());
						$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
						$idcentro_costo = $bus_categoria_programatica["idcategoria_programatica"];
					}else{
						$idcentro_costo = $idcentro_costo_fijo;
					}

					$sql_buscar = mysql_query("select * from articulos_compra_servicio
																		where
																		idarticulos_servicios = '".$id."'
																		and idcategoria_programatica = '".$idcentro_costo."'
																		and idorden_compra_servicio = '".$idcertificacion."'")or die("qqqqqq".mysql_error());
					$num_buscar= mysql_num_rows($sql_buscar);

					if($num_buscar > 0){
						//echo "entro constante ".$bus_consulta_articulos["tipo_concepto"]." id art ".$id;
						$bus_busqueda = mysql_fetch_array($sql_buscar);
						actualizarPrecioCantidad($id, $idcertificacion, $idcentro_costo, 1, $bus_conceptos['total'], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal, $bus_busqueda["idarticulos_compra_servicio"], $bus_conceptos['idtrabajador'],$anio,$idcertificacion_aporte);
					}else{
						ingresarMaterial($id, $idcertificacion, $idcentro_costo, 1, $bus_conceptos['total'], $idfuente_financiamiento, $idtipo_presupuesto,$idclasificador_presupuestario,$idordinal,$anio,$idcertificacion_aporte);
					}

				}
			} // FIN DE SI ES TIPO ASIGNACION O DEDUCCION

		}

	}

	//ACTUALIZO LOS TOTALES DE LAS CERTIFICACIONES
	if($idcertificacion_aporte == ''){
		$idcertificacion_aporte=0;
	}else{
		$sql_actualizar_certificacion = mysql_query("update orden_compra_servicio
												set total = sub_total - exento
												where idorden_compra_servicio = '".$idcertificacion_aporte."'");
	}
	echo $idcertificacion."|.|".$idcertificacion_aporte;
	$sql_actualizar_certificacion = mysql_query("update orden_compra_servicio
												set total = sub_total - exento
												where idorden_compra_servicio = '".$idcertificacion."'");

	$sql_actualizar  = mysql_query("update generar_nomina set
											idorden_compra_servicio = '".$idcertificacion."',
											idorden_compra_servicio_aporte = '".$idcertificacion_aporte."'
												where
											idgenerar_nomina = '".$idgenerar_nomina."'");

}







?>
