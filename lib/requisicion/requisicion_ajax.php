<?
session_start();
include("../../conf/conex.php");
include("../../funciones/funciones.php");
Conectarse();

extract($_POST);







function colsultarMaestro($anio, $idcategoria_programatica, $idclasificador_presupuestario, $idfuente_financiamiento, $idtipo_presupuesto, $idordinal, $cofinanciamiento){
	if($cofinanciamiento == "si"){
	$sql_cofinanciamiento = mysql_query("select 
												fc.idfuente_financiamiento
												 from
												 	fuentes_cofinanciamiento fc
												 where 
												 	fc.idcofinanciamiento = '".$idfuente_financiamiento."'");
												 
		$query = "SELECT * FROM
					maestro_presupuesto mp
					WHERE 
						mp.anio								= '".$anio."' 
					AND mp.idcategoria_programatica 		= '".$idcategoria_programatica."'  
					AND mp.idclasificador_presupuestario 	= '".$idclasificador_presupuestario."'  
					AND mp.idtipo_presupuesto 				= '".$idtipo_presupuesto."' 
					AND mp.idordinal 						= '".$idordinal."'
					AND (";
		while($bus_cofinanciamiento = mysql_fetch_array($sql_cofinanciamiento)){
			$query .= " mp.idfuente_financiamiento = '".$bus_cofinanciamiento["idfuente_financiamiento"]."' OR";
		}
			
		
		$query = substr($query, 0, -2);
		$query .= ")";
		
		//$query .= " AND fc.idcofinanciamiento = '".$bus_cofinanciamiento["idcofinanciamiento"]."'";
		
		$sql_maestro = mysql_query($query)or die(mysql_error());
	}else{
	//echo "AQUI";
		$sql_maestro = mysql_query("SELECT * FROM 
												maestro_presupuesto 
											WHERE 
												anio 							= '".$anio."' 
											AND idcategoria_programatica 		= ".$idcategoria_programatica."  
											AND idclasificador_presupuestario 	= ".$idclasificador_presupuestario." 
											AND idfuente_financiamiento 		= '".$idfuente_financiamiento."' 
											AND idtipo_presupuesto 				= '".$idtipo_presupuesto."' 
											AND idordinal 						= '".$idordinal."'");
	}

	
	return $sql_maestro;
}









//*******************************************************************************************************************************************
//********************************************* CONSULTAR LISTA DE SOLICITUDES FINALIZADAS POR PROVEEDOR ************************************
//*******************************************************************************************************************************************

if($ejecutar == "consultarSolicitudesProveedor"){
	$sql_orden = mysql_query("select * from requisicion where idrequisicion = ".$id_requisicion."");
	$bus_orden = mysql_fetch_array($sql_orden);
	if($bus_orden["estado"] == "procesado" || $bus_orden["estado"] == "anulado"){
		echo "La requisicion se encuentra en estado ".$bus_orden["estado"].", Por lo tanto no se puiede modificar las solicitudes seleccionadas";
	}else{
		$sql = mysql_query("select * from proveedores_solicitud_cotizacion, solicitud_cotizacion where proveedores_solicitud_cotizacion.ganador = 'y' and proveedores_solicitud_cotizacion.idbeneficiarios = ".$id_beneficiarios." and solicitud_cotizacion.estado = 'finalizado' and solicitud_cotizacion.idsolicitud_cotizacion = proveedores_solicitud_cotizacion.idsolicitud_cotizacion and solicitud_cotizacion.tipo = '".$tipo."'");
		$num = mysql_num_rows($sql);
		if($num > 0){
			?>
<form name="formSolicitudesFinalizadas" id="formSolicitudesFinalizadas">
  <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
	<thead>
	  <tr>
		<td width="65%" class="Browse">Nro. Solicitud</td>
		<td width="18%" class="Browse">Just.</td>
		<td width="17%" class="Browse">Selec.</td>
	  </tr>
	<thead>
			<?
			$i = 0;
			while($bus = mysql_fetch_array($sql)){
				$sql2 = mysql_query("select * from solicitud_cotizacion where idsolicitud_cotizacion = ".$bus["idsolicitud_cotizacion"]."");
				$bus2 = mysql_fetch_array($sql2);
				?>
				<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
					<td class="Browse"><?=$bus2["numero"]?></td>
					<td class="Browse" align="center">
					<a href="#" onClick="document.getElementById('divDetalleJustificacion<?=$i?>').style.display='block'"><img src="imagenes/ver.png" border="0" title="Ver Justificacion"></a>
					<div style="position:absolute; display:none; background-color: #FFFFCC; border:#000000 1px solid" id="divDetalleJustificacion<?=$i?>">
						<div align="right">
							<a href="#" onClick="document.getElementById('divDetalleJustificacion<?=$i?>').style.display='none'"><strong>X</strong></a>
						</div>
					<?=$bus2["justificacion"]?>
					</div>
					</td>
					<td align="center" class="Browse">
					<?
					$sql_relacion = mysql_query("select * from relacion_requisicion_solicitud_cotizacion where idsolicitud_cotizacion = ".$bus["idsolicitud_cotizacion"]." and idrequisicion = ".$id_requisicion."");
					$num_relacion = mysql_num_rows($sql_relacion);
					?>
					<input type="checkbox"  
					<?
					if($num_relacion != 0){
						?>
						checked="checked" 
						<?
					}
					?>
															onclick="seleccionDeseleccionListaSolicitud(<?=$num?>), agregarMateriales(<?=$bus["idsolicitud_cotizacion"]?>, document.getElementById('id_requisicion').value, document.getElementById('id_categoria_programatica').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value,document.getElementById('contribuyente_ordinario').value)" 
															name="solicitudes_ganadas<?=$i?>" 
															id="solicitudes_ganadas<?=$i?>" 
															value="<?=$bus["idsolicitud_cotizacion"]?>">
					</td>
				</tr>
				<?
				$i++;
			}
			?>
  </table>
</form>
			<?
		}else{
			echo "noTieneGanadas";
		}
		
	}
}


//*******************************************************************************************************************************************
//********************************************* RECALCULAR REQUISICION ****************************************************
//*******************************************************************************************************************************************

if($ejecutar == "recalcular"){
	$suma_totales = mysql_query("SELECT sum(total) as total, sum(impuesto) as impuesto, sum(exento) as exento FROM articulos_requisicion 
																	   WHERE idrequisicion = '".$id_requisicion."'")or die("uno".mysql_error());
	$bus_suma = mysql_fetch_array($suma_totales)or die("dos".mysql_error());
	$total_suma = $bus_suma["exento"]+$bus_suma["total"]+$bus_suma["impuesto"];	
	$actualiza_totales = mysql_query("update requisicion set exento = '".$bus_suma["exento"]."',
															 exento_original = '".$bus_suma["exento"]."',
															 sub_total = '".$bus_suma["total"]."',
															 sub_total_original = '".$bus_suma["total"]."',
															 impuesto = '".$bus_suma["impuesto"]."',
															 total = '".$total_suma."'
														where idrequisicion = '".$id_requisicion."'")or die("AQUIII".mysql_error());
	$sql_requisicion = mysql_query("select * from requisicion where idrequisicion = '".$id_requisicion."'");
	$requisicion = mysql_fetch_array($sql_requisicion);
	$sql_beneficiarios = mysql_query("select * from beneficiarios where idbeneficiarios = '".$requisicion["idbeneficiarios"]."'");
	$beneficiarios = mysql_fetch_array($sql_beneficiarios);
	$limpiar_partidas = mysql_query("update partidas_requisiciones set monto = 0 where idrequisicion = '".$id_requisicion."'");
	$sql = mysql_query("select * from articulos_requisicion where idrequisicion = '".$id_requisicion."'");
	while($articulo_recalcular = mysql_fetch_array($sql)){
		$exento_articulo = $articulo_recalcular["exento"];
		$total_articulo = $articulo_recalcular["total"];
		$impuesto_articulo = $articulo_recalcular["impuesto"];
		$sql_articulo = mysql_query("select * from articulos_servicios where idarticulos_servicios= '".$articulo_recalcular["idarticulos_servicios"]."'");
		if ($beneficiarios["contribuyente_ordinario"]=="si"){
			$total_articulo_individual=$articulo_recalcular["total"] + $articulo_recalcular["exento"];
			$total_impuesto_individual=$articulo_recalcular["impuesto"];
		}else{
			$total_articulo_individual=$articulo_recalcular["total"] + $articulo_recalcular["exento"];
			$total_impuesto_individual=0;
		}
		//BUSCO EL IMPUESTO QUE SE LE APLICA AL ARTICULO PARA SABER SI TIENE PARTIDA PROPIA O SE VA A CARGAR A LA PARTIDA DEL ARTICULO
		$sql2 = mysql_query("select impuestos.destino_partida as destinoPartida,
								 impuestos.idimpuestos as idImpuestos, 
								 impuestos.porcentaje as porcentajeImpuesto, 
								 impuestos.idclasificador_presupuestario as clasificadorImpuestos,
								 articulos_servicios.idclasificador_presupuestario as clasificadorArticulos 
								 from impuestos, articulos_servicios 
									 where 
									 articulos_servicios.idarticulos_servicios = ".$articulo_recalcular["idarticulos_servicios"]." 
									 and impuestos.idimpuestos = articulos_servicios.idimpuestos") or die("ERROR:".mysql_error());
		$bus2 = mysql_fetch_array($sql2);

		$id_clasificador_presupuestario = $bus2["clasificadorArticulos"];
		$id_clasificador_impuestos = $bus2["clasificadorImpuestos"];
		$id_impuestos = $bus2["idImpuestos"];
		$destino_partida = $bus2["destinoPartida"];
		$porcentaje_impuesto = $bus2["porcentajeImpuesto"];
		
		if($destino_partida == 0){ // EL IMPUESTO SE CARGA A LA PARTIDA DEL ARTICULO
			$id_partida_impuesto = 0;
		}else{
			$id_partida_impuesto = $id_clasificador_impuestos; // EL IMPUESTO TIENE PARTIDA PROPIA
		}
		$sql_ordinal_impuesto= mysql_query("select * from ordinal where codigo='0000'");
		$bus_ordinal_impuesto = mysql_fetch_array($sql_ordinal_impuesto);
								// consulta maestro con el clasificador de impuesto
		if ($destino_partida <> 0){
			
			$sql_maestro_impuestos = mysql_query("select * from maestro_presupuesto where anio = ".$_SESSION["anio_fiscal"]." 
															and idcategoria_programatica = ".$requisicion["idcategoria_programatica"]." 
															and idclasificador_presupuestario = ".$id_clasificador_impuestos."
															and idfuente_financiamiento = '".$requisicion["idfuente_financiamiento"]."'
															and idtipo_presupuesto = '".$requisicion["idtipo_presupuesto"]."'
															and idordinal = '".$bus_ordinal_impuesto["idordinal"]."'");
	

			$bus_maestro_impuesto = mysql_fetch_array($sql_maestro_impuestos);
			$sql_partida = mysql_query("update partidas_requisiciones set monto = monto +'".$total_impuesto_individual."'
								   					where idrequisicion = '".$id_requisicion."'
													and idmaestro_presupuesto = '".$bus_maestro_impuesto["idRegistro"]."'")or die("actualiza impuesto".mysql_error());
		}else{
			$total_articulo_individual = $total_articulo_individual + $total_impuesto_individual;
		}
		$sql_maestro_partida = mysql_query("select * from maestro_presupuesto where anio = ".$_SESSION["anio_fiscal"]." 
															and idcategoria_programatica = ".$requisicion["idcategoria_programatica"]." 
															and idclasificador_presupuestario = ".$id_clasificador_presupuestario."
															and idfuente_financiamiento = '".$requisicion["idfuente_financiamiento"]."'
															and idtipo_presupuesto = '".$requisicion["idtipo_presupuesto"]."'
															and idordinal = '".$bus_ordinal_impuesto["idordinal"]."'");
	

		$bus_maestro_partida = mysql_fetch_array($sql_maestro_partida);
		$sql_partida = mysql_query("update partidas_requisiciones set monto = monto +'".$total_articulo_individual."'
								   					where idrequisicion = '".$id_requisicion."'
													and idmaestro_presupuesto = '".$bus_maestro_partida["idRegistro"]."'")or die("actualiza spartidas ".mysql_error());
		
	}

}
//*******************************************************************************************************************************************
//********************************************* INGRESAR MATERIALES A LA ORDEN DE COMPRA ****************************************************
//*******************************************************************************************************************************************

if($ejecutar == "materiales"){
//si la accion a ejecutar es ditinta a consultar, entonces procede a agregar o eliminar de acuerdo a la opcion que venga
	if($accion != "consultar"){
	// si la accion que viene es ingresar desde cotizacion se procede a consultar los datos de la solicitud para ingresar los materiales asociados
	
		if($accion == "ingresarSolicitudCreada"){
			
			$sql = mysql_query("select * from relacion_requisicion_solicitud_cotizacion where idrequisicion = ".$id_requisicion."
																				and idsolicitud_cotizacion = ".$id_solicitud."");
			$num = mysql_num_rows($sql);
			if($num == 0){
				//PROCEDE A AGREGAR LA SOLICITUD SELECCIONADA EN LA TABLA QUE LLEVA LA RELACION CON LA ORDEN DE COMPRA
				$sql3 = mysql_query("insert into relacion_requisicion_solicitud_cotizacion (idrequisicion, 
																				idsolicitud_cotizacion)values(
																				'".$id_requisicion."',
																				'".$id_solicitud."')")or die(mysql_error());
			
				$sql = mysql_query("select * from articulos_solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."");
				$sql_orden = mysql_query("select * from requisicion where idrequisicion = ".$id_requisicion."");
				$bus_orden = mysql_fetch_array($sql_orden);
				
				while($articulo_ingresar = mysql_fetch_array($sql)){
						$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios= '".$articulo_ingresar["idarticulos_servicios"]."'");
						$bus_ordinal = mysql_fetch_array($sql_ordinal);
						$idordinal = $bus_ordinal["idordinal"];
					//valido si el proveedor no es contribuyente ordinario todo se carga al exento
					if ($contribuyente_ordinario=="si"){
						$total_articulo_individual=$articulo_ingresar["total"] + $articulo_ingresar["exento"];
						$total_impuesto_individual=$articulo_ingresar["impuesto"];
					}else{
						$total_articulo_individual=$articulo_ingresar["total"] + $articulo_ingresar["exento"];
						$total_impuesto_individual=0;
					}
					
					//BUSCO EL IMPUESTO QUE SE LE APLICA AL ARTICULO PARA SABER SI TIENE PARTIDA PROPIA O SE VA A CARGAR A LA PARTIDA DEL ARTICULO
					$sql2 = mysql_query("select impuestos.destino_partida as destinoPartida,
											 impuestos.idimpuestos as idImpuestos, 
											 impuestos.porcentaje as porcentajeImpuesto, 
											 impuestos.idclasificador_presupuestario as clasificadorImpuestos,
											 articulos_servicios.idclasificador_presupuestario as clasificadorArticulos 
											 from impuestos, articulos_servicios 
												 where 
												 articulos_servicios.idarticulos_servicios = ".$articulo_ingresar["idarticulos_servicios"]." 
												 and impuestos.idimpuestos = articulos_servicios.idimpuestos") or die("ERROR:".mysql_error());
					$bus2 = mysql_fetch_array($sql2);
			
					$id_clasificador_presupuestario = $bus2["clasificadorArticulos"];
					$id_clasificador_impuestos = $bus2["clasificadorImpuestos"];
					$id_impuestos = $bus2["idImpuestos"];
					$destino_partida = $bus2["destinoPartida"];
					$porcentaje_impuesto = $bus2["porcentajeImpuesto"];
					
					if($destino_partida == 0){ // EL IMPUESTO SE CARGA A LA PARTIDA DEL ARTICULO
						$id_partida_impuesto = 0;
					}else{
						$id_partida_impuesto = $id_clasificador_impuestos; // EL IMPUESTO TIENE PARTIDA PROPIA
					}
					
					// AGREGO EL ARTICULO DE LA SOLICITUD DE COTIZACION A LA ORDEN DE COMPRA
					$sql2 = mysql_query("insert into articulos_requisicion (idrequisicion,
																				idarticulos_servicios,
																				cantidad,
																				precio_unitario,
																				porcentaje_impuesto,
																				impuesto,
																				total,
																				exento,
																				idsolicitud_cotizacion,
																				status,
																				usuario,
																				fechayhora,
																				idpartida_impuesto)value(
																								".$id_requisicion.",
																								".$articulo_ingresar["idarticulos_servicios"].",
																								'".$articulo_ingresar["cantidad"]."',
																								'".$articulo_ingresar["precio_unitario"]."',
																								'".$articulo_ingresar["porcentaje_impuesto"]."',
																								'".$articulo_ingresar["impuesto"]."',
																								'".$articulo_ingresar["total"]."',
																								'".$articulo_ingresar["exento"]."',
																								".$id_solicitud.",
																								'a',
																								'".$login."',
																								'".date("Y-m-d H:i:s")."',
																								".$id_partida_impuesto.")")or die("ERROR INGRESANDO:".mysql_error());
					
					$id_ultimo_generado = mysql_insert_id(); 	// OBTENGO EL ULTIMO ID INGRESADO EN LA TABLA DE ARTICULOS PARA ACTUALIZARLE EL ESTADO DESPUES DE ANALIZAR LA
																//DISPONIBILIDAD DE LAS PARTIDAS
					
					// ACTUALIZO LOS TOTALES EN LA TABLA requisicion
					$sql_actualiza_totales = mysql_query("update requisicion set 
					sub_total = sub_total + '".$articulo_ingresar["total"]."',
					sub_total_original = sub_total_original + '".$articulo_ingresar["total"]."',
					impuesto = impuesto + '".$articulo_ingresar["impuesto"]."',
					exento = exento + '".$articulo_ingresar["exento"]."',
					exento_original = exento_original + '".$articulo_ingresar["exento"]."',
					total = total + '".$articulo_ingresar["impuesto"]."' + '".$articulo_ingresar["total"]."' + '".$articulo_ingresar["exento"]."'
					where idrequisicion=".$id_requisicion." ")or die (mysql_error());
					
				if ($contribuyente_ordinario=="si"){
					if ($destino_partida<>0){ // SI EL IMPUESTO TIENE PARTIDA PROPIA
						$sql_existe_partida=mysql_query("select * from relacion_impuestos_requisiciones 
																				where idrequisicion=".$id_requisicion." 
																					and idimpuestos=".$id_impuestos."");
						$num=mysql_num_rows($sql_existe_partida); // VERIFICO SI ESE IMPUESTO YA FUE INGRESADO A LA TABLA DE RELACION DE IMPUESTOS CON ORDEN DE COMPRA
						if ($num==0) {
							$sql2 = mysql_query("insert into relacion_impuestos_requisiciones (idrequisicion,
																								idimpuestos,
																								base_calculo,
																								base_calculo_original,
																								porcentaje,
																								total)
																						value(
																								".$id_requisicion.",
																								".$id_impuestos.",
																								'".$articulo_ingresar["total"]."',
																								'".$articulo_ingresar["total"]."',
																								".$porcentaje_impuesto.",
																								'".$articulo_ingresar["impuesto"]."'
																								)")or die(mysql_error());
						}else {
							// SI YA EXISTE EN LA TABLA LE SUMO EL IMPUESTO DEL NUEVO ARTICULO AL TOTAL
							$sql2=mysql_query("update relacion_impuestos_requisiciones set 
														base_calculo = base_calculo + '".$articulo_ingresar["total"]."',
														base_calculo_original = base_calculo_original + '".$articulo_ingresar["total"]."',
														total = total +".$articulo_ingresar["impuesto"]." 
														where idrequisicion = '".$id_requisicion."'")	or die(mysql_error());
						}
						
						// VALIDO LA PARTIDA DEL IMPUESTO EXISTA EN EL MAESTRO DE PRESUPUESTO 
						
						
						$sql_ordinal_impuesto= mysql_query("select * from ordinal where codigo='0000'");
						$bus_ordinal_impuesto = mysql_fetch_array($sql_ordinal_impuesto);
												// consulta maestro con el clasificador de impuesto
						$sql_maestro_impuestos = mysql_query("select * from maestro_presupuesto where anio = ".$_SESSION["anio_fiscal"]." 
																			and idcategoria_programatica = ".$bus_orden["idcategoria_programatica"]." 
																			and idclasificador_presupuestario = ".$id_clasificador_impuestos."
																			and idfuente_financiamiento = '".$fuente_financiamiento."'
																			and idtipo_presupuesto = '".$tipo_presupuesto."'
																			and idordinal = '".$bus_ordinal_impuesto["idordinal"]."'");
					

						$bus_maestro = mysql_fetch_array($sql_maestro_impuestos);
						$num_maestro_impuesto = mysql_num_rows($sql_maestro_impuestos);
						if($num_maestro_impuesto > 0){ // valido que exista una partida para el impuesto
							// obtengo el disponible de la partida para compararlo con el total de impuesto y saber si existe disponibilidad
							//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
							$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
							
							$sql_total_impuestos = mysql_query("select * from relacion_impuestos_requisiciones where 
																					idrequisicion = ".$id_requisicion." 
																				and idimpuestos = ".$id_impuestos."");
							$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
							$total_impuesto_imputable = $bus_total_impuestos["total"];	
									
							if($total_impuesto_imputable > $disponible){
								$estado_partida="sobregiro"; // si no tiene disponibilidad cambio el estado para colorearlo de AMARILLO
								$sql_update_estado_relacion_impuestos = mysql_query("update relacion_impuestos_requisiciones 
																			set estado = 'sin disponibilidad' 
																			where idrequisicion = ".$id_requisicion."");
							}else{
								$estado_partida="disponible"; // si existe disponibilidad coloco el estado como DISPONIBLE para que aparezca en color normal
								$sql_update_estado_relacion_impuestos = mysql_query("update relacion_impuestos_requisiciones 
																						set estado = 'disponible' 
																						where idrequisicion = ".$id_requisicion."");
							}
							// BUSCO LA PARTIDA DEL IMPUESTO EN LAS PARTIDAS DE LA ORDEN DE COMPRA 
							$sql_partidas_orden_compra=mysql_query("select * from partidas_requisiciones where idrequisicion=".$id_requisicion." 
																					and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'") 
																				or die(mysql_error());
							$num=mysql_num_rows($sql_partidas_orden_compra);
							if ($num==0){ // SI NO EXISTE LA PARTIDA EN LA TABLA DE PARTIDAS DE LA ORDEN DE COMPRA LA AGREGO
								$ingresar_partida=mysql_query("insert into partidas_requisiciones (idrequisicion, 
																											idmaestro_presupuesto,
																											monto,
																											monto_original,
																											estado,
																											status,
																											usuario,
																											fechayhora) 
																										values (".$id_requisicion.",
																												'".$bus_maestro["idRegistro"]."',
																												".$total_impuesto_individual.",
																												".$total_impuesto_individual.",
																												'".$estado_partida."',
																												'a',
																												'".$login."',
																												'".date("Y-m-d H:i:s")."')")
																											or die(mysql_error());
							}else{ // SI YA EXISTE LA PARTIDA, LE ACTUALIZO EL ESTADO Y EL TOTAL DE IMPUESTO IMPUTADO A ESA PARTIDA
								$actualiza_partida=mysql_query("update partidas_requisiciones 
																			set monto = monto +".$total_impuesto_individual." ,
																			monto_original = monto_original +".$total_impuesto_individual." ,
																			estado='".$estado_partida."' 
																			where idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")
																			or die (mysql_error());
							}	
						}else{ // SI NO EXISTE PARTIDA PARA EL IMPUESTO LO COLOCA COMO RECHAZADO PARA COLOREARLO DE ROJO
							$sql_update_estado_relacion_impuestos = mysql_query("update relacion_impuestos_requisiciones set estado = 'rechazado' 
																				where idrequisicion = ".$id_requisicion."");
						} // CIERRO LA VALIDACION PARA SABER SI TIENE PARTIDA EN EL MAESTRO DE PRESUPUESTO
						
					} // CIERRO LA VALIDACION DE SI EL IMPUESTO TIENE PARTIDA PROPIA
					
				} // CIERRO LA VALIDACION SI ES CONTRIBUYENTE ORDINARIO
				
					$sql_articulos = mysql_query("select * from articulos_servicios where idarticulos_servicios = ".$articulo_ingresar["idarticulos_servicios"]."");
					$bus_articulos = mysql_fetch_array($sql_articulos);
					// 	se realiza la consulta en la tabla maestro para verificar si hay partidas para este articulo, de lo contrario el articulo se coloca en un estado de rechazado para que
					//	en la tabla aparesca la fila en rojo y muestre al usuario que no hay partidas para ese articulo
					
				
				
				
				// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************

			
			$sql_maestro = colsultarMaestro($_SESSION["anio_fiscal"], $bus_orden["idcategoria_programatica"], $id_clasificador_presupuestario, $fuente_financiamiento, $tipo_presupuesto, $idordinal, $cofinanciamiento);	
				

					$num_maestro = mysql_num_rows($sql_maestro);
						
						if($num_maestro == 0){ // VALIDO QUE EXISTA UNA PARTIDA EN EL MAESTRO DE PRESUPUESTO PARA ESE ARTICULO
							$estado = "rechazado";  // si no tiene partida en el maestro de presupuesto le coloca RECHAZADO para pintarlo de color ROJO
						}else{
							
							
							while($bus_maestro = mysql_fetch_array($sql_maestro)){
							//$disponible = $bus_maestro["monto_actual"] - $bus_maestro["total_compromisos"];
							$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
							// de lo contrario verifica si en la partida que esta asignada este articulo hay disponibilidad de dinero para comprar la cantidad que se esta pidiendo, si para 
							// esta partida no hay disponibilidad se coloca el estado en SIN DISPONIBILIDAD para que al mostrar el articulo en color en AMARILLO para indicar que no 
							// hay presupuesto para este articulo

							$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_requisicion.exento) as exentos from articulos_requisicion, articulos_servicios 
													where
														articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
														and articulos_requisicion.idarticulos_servicios = articulos_servicios.idarticulos_servicios
														and articulos_requisicion.idrequisicion = ".$id_requisicion."");
							// SUMO EL TOTAL DE TODOS LOS ARTICULOS QUE ESTAN IMPUTANDO ESA PARTIDA PARA COMPARARLO CON EL DISPONIBLE EN EL MAESTRO DE PRESUPUESTO
							$bus_imputable = mysql_fetch_array($sql_imputable);
							$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"]; 
							
							
							if ($destino_partida == 0 and $contribuyente_ordinario == "si")	{// valido que el impuesto se sume a la partida o si tiene partida propia
								$sql_impuesto_imputable = mysql_query("select SUM(impuesto) as totales_impuestos from articulos_requisicion, 
																							articulos_servicios 
																						where
													articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
													and articulos_requisicion.idarticulos_servicios = articulos_servicios.idarticulos_servicios
													and articulos_requisicion.idrequisicion = ".$id_requisicion." 
													and idpartida_impuesto = 0");
								$bus_impuesto_imputable = mysql_fetch_array($sql_imputable);
								$total_impuesto_imputable = $bus_impuesto_imputable["totales_impuestos"];
								$total_imputable = $total_imputable + $total_impuesto_imputable;
								$total_articulo_individual = $total_articulo_individual + $total_impuesto_individual;
							}
							
							
							
							if($cofinanciamiento == "si"){
								$sql_cofinanciamiento = mysql_query("select * from 
															fuentes_cofinanciamiento 
																where 
															idcofinanciamiento = '".$fuente_financiamiento."' 
															and  idfuente_financiamiento = '".$bus_maestro["idfuente_financiamiento"]."'");
								$bus_cofinanciamiento = mysql_fetch_array($sql_cofinanciamiento);
								$porcent = "0.".$bus_cofinanciamiento["porcentaje"];
								$total_imputable_nuevo = ($total_imputable * $porcent);
							}else{
								$total_imputable_nuevo = $total_imputable;
							}
							
							
							
							if($total_imputable_nuevo > $disponible){ // si el total a imputar es mayor al disponible en la partida
								$estado = "sin disponibilidad";
								$estado_partida = "sobregiro";
							}else{
								//si nada de esto sucede se coloca el estado en aprobado y el material se muestra normalmente
								$estado = "aprobado";
								$estado_partida = "disponible";
							}
							
							
							
							// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************

			
			$sql_maestro = colsultarMaestro($_SESSION["anio_fiscal"], $bus_orden["idcategoria_programatica"], $id_clasificador_presupuestario, $fuente_financiamiento, $tipo_presupuesto, $idordinal, $cofinanciamiento);	
							
									
							while($bus_maestro = mysql_fetch_array($sql_maestro)){
							$sql_partidas_orden_compra=mysql_query("select * from partidas_requisiciones where 
																			idrequisicion=".$id_requisicion." 
																			and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'") 
																			or die(mysql_error());
							$num=mysql_num_rows($sql_partidas_orden_compra);
							
							if ($num==0){ // SI NO EXISTE LA PARTIDA LA INGRESO
								$ingresar_partida=mysql_query("insert into partidas_requisiciones (idrequisicion, 
																											idmaestro_presupuesto,
																											monto,
																											monto_original,
																											estado,
																											status,
																											usuario,
																											fechayhora) 
																										values (".$id_requisicion.",
																											".$bus_maestro["idRegistro"].",
																											".$total_imputable_nuevo.",
																											".$total_imputable_nuevo.",
																											'".$estado_partida."',
																											'a',
																											'".$login."',
																											'".date("Y-m-d H:i:s")."')")
																											or die("ERROR GUARDANDO PARTIDAS:". mysql_error());
							}else{ // DE LO CONTRARIO LA ACTUALIZO
								$actualiza_partida=mysql_query("update partidas_requisiciones 
															set monto = (monto +".$total_imputable_nuevo."),
															monto_original = (monto_original +".$total_imputable_nuevo."),
															estado='".$estado_partida."' 
															where idrequisicion=".$id_requisicion." 
															and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")
															or die ($total_item."ERROR MODIFICANDO PARTIDAS: ".mysql_error());
							}														
							}
							
							}
						
						}
						
						// actualizo el estado del material ingresado				
						$sql_update_articulos_compras = mysql_query("update articulos_requisicion set estado = '".$estado."' 
																			where idarticulos_requisicion = ".$id_ultimo_generado."");
																			
						$sql_consulta_iguales = mysql_query("select * from articulos_requisicion where idarticulos_servicios = ".$articulo_ingresar["idarticulos_servicios"]." and idrequisicion = ".$id_requisicion."");
						$num_consulta_iguales = mysql_num_rows($sql_consulta_iguales);
							if($num_consulta_iguales > 1){
								$sql_cambiar_iguales = mysql_query("update articulos_requisicion set duplicado = 1 where idarticulos_servicios = ".$articulo_ingresar["idarticulos_servicios"]." and idrequisicion = ".$id_requisicion."")or die(mysql_error());
								$sql_cambiar_iguales = mysql_query("update requisicion set duplicados = 1 where idrequisicion = ".$id_requisicion."")or die(mysql_error());			
							}
							
							
					}   // FIN DEL WHILE DE INGRESAR LOS ARTICULOS DE LA SOLICITUD DE COTIZACION
				
			}else{  // SI  LA SOLICITUD DE COTIZACION YA ESTABA SELECCIONADA PROCEDE A ELIMINARLA
				
				// PROCEDE A ELIMINAR LOS MATERIALES PORQUE YA EXISTEN Y LOS ESTOY DESELECCIONANDO
				$sql = mysql_query("delete from relacion_requisicion_solicitud_cotizacion where idrequisicion = '".$id_requisicion."' and idsolicitud_cotizacion = '".$id_solicitud."'");
					
					
				$sql = mysql_query("select * from relacion_requisicion_solicitud_cotizacion where idrequisicion = '".$id_requisicion."'");
				$num = mysql_num_rows($sql);
				// SI YA NO EXISTEN MAS SOLICITUDES MARCADAS EN LA ORDEN DE COMPRA PROCEDE A BORRA TODOS LOS REGISTRSO DE ESA ORDEN
				if($num == 0){ 
					$sql = mysql_query("delete from articulos_requisicion where idrequisicion = '".$id_requisicion."'");
					$sql = mysql_query("delete from partidas_requisiciones where idrequisicion = '".$id_requisicion."'");	
					$sql = mysql_query("delete from relacion_impuestos_requisiciones where idrequisicion = '".$id_requisicion."'");
					// ACTUALIZO LOS TOTALES EN LA TABLA requisicion
					$sql_actualiza_totales = mysql_query("update requisicion set 	sub_total = 0,
																					sub_total_original = 0,
																					impuesto = 0,
																					exento = 0,
																					exento_original = 0,
																					total = 0
																					where idrequisicion=".$id_requisicion." ")or die (mysql_error());
				// SI AUN EXISTEN SOLICITUDES MARCADAS ELIMINA LOS REGISTRSO DE LA QUE SE ESTA DESMARCANDO
				}else{
					// BUSCO TODOS LOS ARTICULOS DE ESA SOLICITUD DE COTIZACION PARA ELIMINARLOS
					$sql_articulos_compra = mysql_query("select * from articulos_requisicion 
																		where idrequisicion = '".$id_requisicion."' 
																		and idsolicitud_cotizacion = '".$id_solicitud."'");
					
					
					
					registra_transaccion("Ingresar Materiales ya Existentes en Orden de Compra (".$id_requisicion.")",$login,$fh,$pc,'requisicion');
					
					
					while($bus_articulos_compra = mysql_fetch_array($sql_articulos_compra)){
						$sql_ordinal = mysql_query("select * form articulos_servicios where idarticulos_servicios ='".$bus_articulos_compra["idarticulos_servicios"]."'");
						$bus_ordinal = mysql_fetch_array($sql_ordinal);
						$idordinal = $bus_ordinal["idordinal"];
					
						// *****************************************************************************************************************
						// ******************************************* ELIMINAR PARTIDAS ***************************************************
						// *****************************************************************************************************************
						$sql = mysql_query("select impuestos.destino_partida as destinoPartida,
													 impuestos.idimpuestos as idImpuestos, 
													 impuestos.porcentaje as porcentajeImpuesto, 
													 impuestos.idclasificador_presupuestario as clasificadorImpuestos, 
													 articulos_servicios.idclasificador_presupuestario as clasificadorArticulos,
													 articulos_servicios.exento as exento
													 from impuestos, articulos_servicios 
													 where 
													 articulos_servicios.idarticulos_servicios = ".$bus_articulos_compra["idarticulos_servicios"]."
													 and articulos_servicios.idimpuestos = impuestos.idimpuestos");
						$bus = mysql_fetch_array($sql);
	
						$id_clasificador_presupuestario = $bus["clasificadorArticulos"];
						$id_clasificador_impuestos = $bus["clasificadorImpuestos"];
						$id_impuestos = $bus["idImpuestos"];
						$destino_partida = $bus["destinoPartida"];
						$porcentaje_impuesto = $bus["porcentajeImpuesto"];
						$total = $bus_articulos_compra["cantidad"] * $bus_articulos_compra["precio_unitario"];
						if ($bus["exento"] == 0){
							if ($contribuyente_ordinario == "si"){
								$porcentaje_impuesto = $bus["porcentajeImpuesto"]/100;
								$impuesto_por_producto = $total * $porcentaje_impuesto;
								$exento = 0;
							}else{
								$exento = $total;
								$total = 0;
								$porcentaje_impuesto = 0;
								$impuesto_por_producto = 0;
							}
						}else{
							$exento = $total;
							$total = 0;
							$porcentaje_impuesto = 0;
							$impuesto_por_producto = 0;
						}
						// ACTUALIZO LOS TOTALES EN LA TABLA requisicion
						$sql_actualiza_totales = mysql_query("update requisicion set 
			sub_total = sub_total - '".$bus_articulos_compra["total"]."',
			sub_total_original = sub_total_original - '".$bus_articulos_compra["total"]."',
			impuesto = impuesto - '".$bus_articulos_compra["impuesto"]."',
			exento = exento - '".$bus_articulos_compra["exento"]."',
			exento_original = exento_original - '".$bus_articulos_compra["exento"]."',
			total = total - '".$bus_articulos_compra["impuesto"]."' - '".$bus_articulos_compra["total"]."' - '".$bus_articulos_compra["exento"]."'
																					where idrequisicion=".$id_requisicion." ")or die (mysql_error());
						

						$sql_compra_servicio = mysql_query("select * from requisicion where idrequisicion = ".$id_requisicion."");
								$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
					if ($contribuyente_ordinario=="si"){
						if($destino_partida == 1){// EL IMPUESTO TIENE PARTIDA
							//actualizo el total de impuesto restandole el impuesto de ese producto
							$sql_total_impuestos = mysql_query("update relacion_impuestos_requisiciones 
														set total=total-".$impuesto_por_producto." ,
														base_calculo = base_calculo - '".$bus_articulos_compra["total"]."',
														base_calculo_original = base_calculo_original - '".$bus_articulos_compra["total"]."'
														where idrequisicion = ".$id_requisicion." 
														and idimpuestos = ".$id_impuestos."");
							// valido que el impuesto tenga partida
							$sql_total_impuestos = mysql_query("select * from relacion_impuestos_requisiciones where idrequisicion = ".$id_requisicion." 
																			and idimpuestos = ".$id_impuestos."
																			and estado <> 'rechazado'");												
							$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
							$existe_partida = mysql_num_rows($sql_total_impuestos);
							if ($existe_partida > 0) {
								// busco la partida del impuesto en la tabla maestro_presupuesto 
							
							
							$sql_ordinal_impuesto= mysql_query("select * from ordinal where codigo='0000'");
							$bus_ordinal_impuesto= mysql_fetch_array($sql_ordinal_impuesto);
							
							
							
							
							$sql_maestro_impuestos = mysql_query("select * from maestro_presupuesto where anio = ".$_SESSION["anio_fiscal"]." 
														and idcategoria_programatica = ".$bus_orden["idcategoria_programatica"]." 
														and idclasificador_presupuestario = ".$id_clasificador_impuestos.",
														and idfuente_financiamiento = '".$fuente_financiamiento."',
														and idtipo_presupuesto = '".$tipo_presupuesto."',
														and idordinal = '".$bus_ordinal_impuesto["idordinal"]."'");
								
								/*$sql_maestro_impuestos = mysql_query("select * from maestro_presupuesto 
																				where anio = ".$_SESSION["anio_fiscal"]." 
																						and idcategoria_programatica = ".$bus_compra_servicio["idcategoria_programatica"]." 
																						and idclasificador_presupuestario = ".$id_clasificador_impuestos."");*/
								$bus_maestro = mysql_fetch_array($sql_maestro_impuestos);
								
								$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
								
								//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
								$total_impuesto_imputable = $bus_total_impuestos["total"];
								// valido que el total de impuesto no sea mayor que el disponible para cambiar el estado				
								if($total_impuesto_imputable > $disponible){
									$sql_partida = mysql_query("update partidas_requisiciones set estado = 'sobregiro', 
																					monto = '".$total_impuesto_imputable."',
																					monto_original = '".$total_impuesto_imputable."' 
																					where 
																						idrequisicion = ".$id_requisicion."
																						and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'");
										
								}else{
									$sql_partida = mysql_query("update partidas_requisiciones set estado = 'disponible', 
																							monto = '".$total_impuesto_imputable."',
																							monto_original = '".$total_impuesto_imputable."'
																					where 
																							idrequisicion = ".$id_requisicion."
																							and idcategoria_programatica = ".$bus_compra_servicio["idcategoria_programatica"]." 
																							and idclasificador_presupuestario = ".$id_clasificador_impuestos."");
								}
								$partida_impuestos = $id_clasificador_impuestos;
							}
						}else{
							$partida_impuestos = 0;
						}
					} 
						// cierro la validacion del impuesto descontandolo a la partida
		
						$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_requisicion.exento) as exentos from articulos_requisicion, articulos_servicios where
													articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
													and articulos_requisicion.idarticulos_servicios = articulos_servicios.idarticulos_servicios
													and articulos_requisicion.idrequisicion = ".$id_requisicion." ");
													
						$bus_imputable = mysql_fetch_array($sql_imputable);
						$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"];
					if ($contribuyente_ordinario == "si"){	
						if($destino_partida == 0){ // valido si el impuesto tiene partida propia y se lo sumo al total imputable 
							$sql_total_impuesto = mysql_query("select SUM(impuesto) as totales_impuesto from articulos_requisicion, articulos_servicios where
													articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
													and articulos_requisicion.idarticulos_servicios = articulos_servicios.idarticulos_servicios
													and articulos_requisicion.idrequisicion = ".$id_requisicion." and idpartida_impuesto = 0");
							$bus_total_impuesto = mysql_fetch_array($sql_total_impuesto);
							$total_impuesto = $bus_total_impuesto["totales_impuesto"];
							$total_imputable = ($total_imputable+$total_impuesto)-($total+$impuesto_por_producto);				
						}
					} 
						if ($destino_partida == 1) {
							$total_imputable = $total_imputable - $total;
						}
						
							
							// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************

			
						$sql_maestro = colsultarMaestro($_SESSION["anio_fiscal"], $bus_orden["idcategoria_programatica"], $id_clasificador_presupuestario, $fuente_financiamiento, $tipo_presupuesto, $idordinal, $cofinanciamiento);	
							
							

														
						while($bus_maestro = mysql_fetch_array($sql_maestro)){
						
						$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
						
						//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
						
						
						
						if($cofinanciamiento == "si"){
							$sql_cofinanciamiento = mysql_query("select * from 
														fuentes_cofinanciamiento 
															where 
														idcofinanciamiento = '".$fuente_financiamiento."' 
														and  idfuente_financiamiento = '".$bus_maestro["idfuente_financiamiento"]."'");
							$bus_cofinanciamiento = mysql_fetch_array($sql_cofinanciamiento);
							$porcent = "0.".$bus_cofinanciamiento["porcentaje"];
							$total_imputable_nuevo = ($total_imputable * $porcent);
						}else{
							$total_imputable_nuevo = $total_imputable;
						}
						
						
						
						if($total_imputable_nuevo > $disponible){ // actualizo la tabla de partidas 
							$sql_partida = mysql_query("update partidas_requisiciones set estado = 'sobregiro', 
																						monto = '".$total_imputable_nuevo."',
																						monto_original = '".$total_imputable_nuevo."'  
																						where 
																						idrequisicion = ".$id_requisicion."
																						and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
							$estado = "sin disponibilidad";
						}else{
							$sql_partida = mysql_query("update partidas_requisiciones set estado = 'disponible', 
																							monto = '".$total_imputable_nuevo."',
																							monto_original = '".$total_imputable_nuevo."' 
																						where 
																							idrequisicion = ".$id_requisicion."
																							and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'");
								$estado = "aprobado";
						}
						// borro el articulo que reste a las partidas
						$sql = mysql_query("delete from articulos_requisicion where 
																idarticulos_requisicion = '".$bus_articulos_compra["idarticulos_requisicion"]."'");
						
						
						$sql_consulta_iguales = mysql_query("select * from articulos_requisicion where idarticulos_servicios = ".$bus_articulos_compra["idarticulos_servicios"]." and idrequisicion = ".$id_requisicion."");
						$num_consulta_iguales = mysql_num_rows($sql_consulta_iguales);
							if($num_consulta_iguales == 1){
								$sql_cambiar_iguales = mysql_query("update articulos_requisicion set duplicado = 0 where idarticulos_servicios = ".$bus_articulos_compra["idarticulos_servicios"]." and idrequisicion = ".$id_requisicion."")or die(mysql_error());
								$sql_cambiar_iguales = mysql_query("update requisicion set duplicados = 0 where idrequisicion = ".$id_requisicion."")or die(mysql_error());			
							}		
										
										// ********************************************* ELIMINAR PARTIDAS *************************************************
					}
					
					}// CIERRE DLE WHILE DE ELIMINAR
					//borro todas las partidas que esten en cero en la orden de compra
					$sql = mysql_query("delete from partidas_requisiciones where idrequisicion = ".$id_requisicion."
																									and monto <= 0");

				} // CIERRE DEL IF PARA SABER SI ERA LA UTLIMA SOLICITUD SELECCIONADA Y BORRAR TODO
							
			} // CIERRE DE LA ELIMINACION DE LA SOLICITUD QUE SE DESMARCO
		
		}else if($accion == "eliminar"){ // ACCION PARA ELIMINAR UN ARTICULO DE LA ORDEN DE COMPRA
		
			// si la accion es eliminar se hacen varias consultas para eliminar los articulos de la tabla articulos_requisicion, ademas se elimina la relacion del 
			// articulo con la solicitud y se crean variables para luego verificar si ya no hay mas articulos por una solicitud para que la solicitud se deseleccione de 
			// la lista de solicitudes del proveedor
			
		$sql_material_eliminar = mysql_query("select * from articulos_requisicion where idarticulos_requisicion = '".$id_material."'");
		$bus_material_eliminar = mysql_fetch_array($sql_material_eliminar);
		$sql_ordinal= mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_material_eliminar["idarticulos_servicios"]."'");
		$bus_ordinal = mysql_fetch_array($sql_ordinal);
		$idordinal= $bus_ordinal["idordinal"];
		
			$id_solicitud_cotizacion= $bus_material_eliminar["idsolicitud_cotizacion"];
			// elimino el articulo seleccionado
			$sql = mysql_query("delete from articulos_requisicion where idarticulos_requisicion = ".$id_material."")or die("ELIMINAR MATERIAL: ".mysql_error());
			
			$sql_consulta_iguales = mysql_query("select * from articulos_requisicion where idarticulos_servicios = ".$bus_material_eliminar["idarticulos_servicios"]." and idrequisicion = ".$id_requisicion."");
						$num_consulta_iguales = mysql_num_rows($sql_consulta_iguales);
							if($num_consulta_iguales == 1){
								$sql_cambiar_iguales = mysql_query("update articulos_requisicion set 
																duplicado = 0 
																where idarticulos_servicios = ".$bus_material_eliminar["idarticulos_servicios"]." 
																and idrequisicion = ".$id_requisicion."")or die(mysql_error());
								$sql_cambiar_iguales = mysql_query("update requisicion set 
																			duplicados = 0 
																			where 
																			idrequisicion = ".$id_requisicion."")or die(mysql_error());			
							}		
			
		$sql_en_solicitudes = mysql_query("select * from articulos_requisicion where idsolicitud_cotizacion = ".$id_solicitud_cotizacion." 
																					and idrequisicion = ".$id_requisicion."");
			$num_en_solicitudes = mysql_num_rows($sql_en_solicitudes);
			
			$sql_todos_articulos = mysql_query("select * from articulos_requisicion where idrequisicion = ".$id_requisicion."");
			$num_todos_articulos = mysql_num_rows($sql_todos_articulos);
			if($num_todos_articulos == 0){
				// si no existen mas articulos en la orden de compra elimino los registros de esa orden en el resto de las tablas		
				$sql = mysql_query("delete from relacion_requisicion_solicitud_cotizacion where idrequisicion = ".$id_requisicion."");
				$sql = mysql_query("delete from relacion_impuestos_requisiciones where idrequisicion = ".$id_requisicion."");
				$sql = mysql_query("delete from partidas_requisiciones where idrequisicion = ".$id_requisicion."");
				// ACTUALIZO LOS TOTALES EN LA TABLA requisicion
				$sql_actualiza_totales = mysql_query("update requisicion set 	sub_total = 0,
																				sub_total_original = 0,
																						impuesto = 0,
																						exento = 0,
																						exento_original = 0,
																						total = 0
																where idrequisicion=".$id_requisicion." ")or die (mysql_error());
				$eliminoSolicitud = $id_solicitud_cotizacion;
			}else{
				if($num_en_solicitudes == 0){
					// si ya no existen mas articulos relacionadoa a esa la solicitud, procedo a eliminarla
					$sql = mysql_query("delete from relacion_requisicion_solicitud_cotizacion where 
																						idsolicitud_cotizacion = ".$id_solicitud_cotizacion." 
																						and idrequisicion = ".$id_requisicion."");
					$eliminoSolicitud = $id_solicitud_cotizacion;
				}
				
				// SI NO ES ARTICULO UNICO
			
				// *****************************************************************************************************************
				// ******************************************* ELIMINAR UNA PARTIDA ***************************************************
				// *****************************************************************************************************************
				$sql = mysql_query("select impuestos.destino_partida as destinoPartida,
											 impuestos.idimpuestos as idImpuestos, 
											 impuestos.porcentaje as porcentajeImpuesto, 
											 impuestos.idclasificador_presupuestario as clasificadorImpuestos, 
											 articulos_servicios.idclasificador_presupuestario as clasificadorArticulos,
											 articulos_servicios.exento
											from impuestos, articulos_servicios 
											where 
											 articulos_servicios.idarticulos_servicios = ".$bus_material_eliminar["idarticulos_servicios"]."
											 and articulos_servicios.idimpuestos = impuestos.idimpuestos") or die(mysql_error());
											 
				$bus = mysql_fetch_array($sql);
				
				$id_clasificador_presupuestario = $bus["clasificadorArticulos"];
				$id_clasificador_impuestos = $bus["clasificadorImpuestos"];
				$id_impuestos = $bus["idImpuestos"];
				$destino_partida = $bus["destinoPartida"];
				$total = $bus_material_eliminar["cantidad"] * $bus_material_eliminar["precio_unitario"];
				if ($bus["exento"] == 0) {
					//echo $contribuyente_ordinario;
					if ($contribuyente_ordinario == "si"){
					//echo "ENTRO ACA";
						$porcentaje_impuesto = $bus["porcentajeImpuesto"]/100;
						$impuesto_por_producto = $total * $porcentaje_impuesto;
						$exento = 0;
					}else{
						$exento = $total;
						$total = 0;
						$porcentaje_impuesto = 0;
						$impuesto_por_producto = 0;
					}
				}else{
					$exento = $total;
					$total = 0;
					$porcentaje_impuesto = 0;
					$impuesto_por_producto = 0;
				}
				//echo $impuesto_por_producto;
				// ACTUALIZO LOS TOTALES EN LA TABLA requisicion
				$sql_actualiza_totales = mysql_query("update requisicion set 
																sub_total = sub_total - '".$bus_material_eliminar["total"]."',
																sub_total_original = sub_total_original - '".$bus_material_eliminar["total"]."',
																impuesto = impuesto - '".$bus_material_eliminar["impuesto"]."',
																exento = exento - '".$bus_material_eliminar["exento"]."',
																exento_original = exento_original - '".$bus_material_eliminar["exento"]."',
		total = total - '".$bus_material_eliminar["impuesto"]."' - '".$bus_material_eliminar["total"]."' - '".$bus_material_eliminar["exento"]."'
															where idrequisicion=".$id_requisicion." ")or die (mysql_error());
																			
				// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
				$sql_compra_servicio = mysql_query("select * from requisicion where idrequisicion = ".$id_requisicion."");
				$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
				
			
				if($destino_partida == 1){// EL IMPUESTO TIENE PARTIDA
					if ($contribuyente_ordinario=="si"){
					//echo $impuesto_por_producto;
					$sql_total_impuestos = mysql_query("update relacion_impuestos_requisiciones set 
															total = total - ".$impuesto_por_producto.",
															base_calculo = base_calculo - ".$bus_material_eliminar["total"].",
															base_calculo_original = base_calculo_original - ".$bus_material_eliminar["total"]." 
															where idrequisicion = ".$id_requisicion." 
															and idimpuestos = ".$id_impuestos."")or die("ACA 3:".mysql_error());
					// valido que el impuesto tenga partida
					$sql_total_impuestos = mysql_query("select * from relacion_impuestos_requisiciones where idrequisicion = ".$id_requisicion." 
																	and idimpuestos = ".$id_impuestos."
																	and estado <> 'rechazado'")or die("ACA 1:".mysql_error());												
					$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
					$existe_partida = mysql_num_rows($sql_total_impuestos);
					
					if ($existe_partida > 0) {
						// consulta maestro con el clasificador de impuesto
						
					$sql_ordinal_impuesto= mysql_query("select * from ordinal where codigo='0000'");
					$bus_ordinal_impuesto= mysql_fetch_array($sql_ordinal_impuesto);

					$sql_maestro_impuestos = mysql_query("select * from maestro_presupuesto where anio = ".$_SESSION["anio_fiscal"]." 
																			and idcategoria_programatica = ".$id_categoria_programatica." 
																			and idclasificador_presupuestario = ".$id_clasificador_impuestos."
																			and idfuente_financiamiento = '".$fuente_financiamiento."'
																			and idtipo_presupuesto = '".$tipo_presupuesto."'
																			and idordinal = '".$$bus_ordinal_impuesto["idordinal"]."'")or die(mysql_error());
						$bus_maestro = mysql_fetch_array($sql_maestro_impuestos);
						
						$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
						
						
						//$disponible = $bus_maestro["monto_actual"] - $bus_maestro["total_compromisos"];
						$sql_total_impuestos = mysql_query("select * from relacion_impuestos_requisiciones 
																							where idrequisicion = ".$id_requisicion." 
																							and idimpuestos = ".$id_impuestos."")or die("ACA 2:".mysql_error());
																		
						$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
						$total_impuesto_imputable = $bus_total_impuestos["total"]-impuesto_por_producto;
						//echo $total_impuesto_imputable;				
						if($total_impuesto_imputable > $disponible){
							// si el impuesto imputable es mayor que el disponible 
							$sql_partida = mysql_query("update partidas_requisiciones set estado = 'sobregiro', 
																				monto = '".$total_impuesto_imputable."',
																				monto_original = '".$total_impuesto_imputable."' 
																				where 
																				idrequisicion = ".$id_requisicion."
																				and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");				
						}else{
							// si existe disponibilidad para esa partida
							$sql_partida = mysql_query("update partidas_requisiciones set 
																			estado = 'disponible', 
																			monto = '".$total_impuesto_imputable."',
																			monto_original = '".$total_impuesto_imputable."' 
																			where 
																			idrequisicion = ".$id_requisicion."
																			and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
						}
						$partida_impuestos = $id_clasificador_impuestos;
					}
				}
				}else{
					$partida_impuestos = 0;
				}


				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_requisicion.exento) as exentos from articulos_requisicion, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_requisicion.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_requisicion.idrequisicion = ".$id_requisicion." ");
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"];
				
				if($destino_partida == 0 and $contribuyente_ordinario == "si"){
					$sql_total_impuesto = mysql_query("select SUM(impuesto) as totales_impuesto from articulos_requisicion, articulos_servicios where
												articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
												and articulos_requisicion.idarticulos_servicios = articulos_servicios.idarticulos_servicios
												and articulos_requisicion.idrequisicion = ".$id_requisicion." and idpartida_impuesto = 0");
					$bus_total_impuesto = mysql_fetch_array($sql_total_impuesto);
					$total_impuesto = $bus_total_impuesto["totales_impuesto"];
					$total_imputable = $total_imputable + $total_impuesto;	
				}
				
					
				// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************

			
			$sql_maestro = colsultarMaestro($_SESSION["anio_fiscal"], $id_categoria_programatica, $id_clasificador_presupuestario, $fuente_financiamiento, $tipo_presupuesto, $idordinal, $cofinanciamiento);	

				while($bus_maestro = mysql_fetch_array($sql_maestro)){
				
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				
				if($cofinanciamiento == "si"){
					$sql_cofinanciamiento = mysql_query("select * from 
												fuentes_cofinanciamiento 
													where 
												idcofinanciamiento = '".$fuente_financiamiento."' 
												and  idfuente_financiamiento = '".$bus_maestro["idfuente_financiamiento"]."'");
					$bus_cofinanciamiento = mysql_fetch_array($sql_cofinanciamiento);
					$porcent = "0.".$bus_cofinanciamiento["porcentaje"];
					$total_imputable_nuevo = ($total_imputable * $porcent);
				}else{
					$total_imputable_nuevo = $total_imputable;
				}
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				
				if($total_imputable_nuevo > $disponible){ // si el total imputable es mayor al disponible
					$sql_partida = mysql_query("update partidas_requisiciones set estado = 'sobregiro', 
																			monto = '".$total_imputable_nuevo."',
																			monto_original = '".$total_imputable_nuevo."' 
																		where 
																			idrequisicion = ".$id_requisicion."
																			and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
				}else{	// si el total imputable es menor o igual al disponible
					$sql_partida = mysql_query("update partidas_requisiciones set estado = 'disponible', 
																			monto = '".$total_imputable_nuevo."',
																			monto_original = '".$total_imputable_nuevo."' 
																		where 
																			idrequisicion = ".$id_requisicion."
																			and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
				}
					
							
				$sql = mysql_query("delete from partidas_requisiciones where idrequisicion = ".$id_requisicion."
																							and monto <= 0");	
				// ********************************************* ELIMINAR PARTIDAS ************************************************
				
			}
			}// CIERRE SI NO ES ARTICULO UNICO
		}// CIERRE SI ES ELIMINAR O NO
	}

									  
	$sql_orden = mysql_query("select * from requisicion where idrequisicion = ".$id_requisicion."");
	$bus_orden = mysql_fetch_array($sql_orden);
	$sql = mysql_query("select * from articulos_requisicion,  unidad_medida, articulos_servicios
									 where 
									 	articulos_requisicion.idrequisicion = ".$id_requisicion." and
									  	articulos_servicios.idarticulos_servicios = articulos_requisicion.idarticulos_servicios and 
									  	articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida");
	
	$num = mysql_num_rows($sql);
	if($num != 0){
		// si existen articulos en la orden los muestra
	?>
    
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <?
            if($bus_orden["duplicados"] == 1){
			?>
			<td class="Browse"><div align="center">Duplicados</div></td>
			<?
			}
			?>
            <td class="Browse" width="10%"><div align="center">C&oacute;digo</div></td>
            <td class="Browse" width="45%"><div align="center">Descripci&oacute;n</div></td>
            <td class="Browse" width="5%"><div align="center">UND</div></td>
            <td class="Browse" width="8%"><div align="center">Cantidad</div></td>
            <td class="Browse" width="10%"><div align="center">Precio Unitario</div></td>
            <td class="Browse" width="13%"><div align="center">Total</div></td>
            <td class="Browse" width="9%"><div align="center">Impuesto</div></td>
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
          while($bus = mysql_fetch_array($sql)){

          	if($bus["estado"] == "rechazado"){
			?>
			<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
			<?
			}else if($bus["estado"] == "sin disponibilidad"){
			?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
			}else{
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			<?
			
			}
		  ?>
          <?
          if($bus_orden["duplicados"] == 1){
			  if($bus["duplicado"] == 1){
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
            <td class='Browse' align='left' style="font-size:8px"><?=$bus["codigo"]?></td>
            <td class='Browse' align='left'><?=$bus[26]?></td>
            <td class='Browse' align='left'><?=$bus["abreviado"]?></td>
            <td class='Browse' align="right">
			<?
            if($bus_orden["estado"] == "elaboracion"){
            ?>
            <input align="right" style="text-align:right" name="cantidad<?=$bus["idarticulos_requisicion"]?>" 
            												type="text" 
                                                            id="cantidad<?=$bus["idarticulos_requisicion"]?>" 
                                                            size="10"
                                                            value="<?=$bus["cantidad"]?>">
            <?
            }else{
			echo $bus["cantidad"];
			}
			?>			</td>
            <td class="Browse" align='right'>
			<?
            if($bus_orden["estado"] == "elaboracion"){
            ?>
            <input align="right" style="text-align:right" name="precio<?=$bus["idarticulos_requisicion"]?>" 
            												type="hidden" 
                                                            id="precio<?=$bus["idarticulos_requisicion"]?>" 
                                                            size="10"
                                                            value="<?=$bus["precio_unitario"]?>">
            <input align="right" style="text-align:right" name="mostrarPrecio<?=$bus["idarticulos_requisicion"]?>" 
            												type="text" 
                                                            id="mostrarPrecio<?=$bus["idarticulos_requisicion"]?>" 
                                                            size="10"
                                                            onclick="this.select()"
                                                            value="<?=number_format($bus["precio_unitario"],2,',','.')?>">
                                                            <input type="hidden" name="eliminoSolicitud" id="eliminoSolicitud" value="<?=$eliminoSolicitud?>">
            <?
            }else{
			echo $bus["precio_unitario"];
			}
			?>            </td>
           
            <td class="Browse" align="right">
				<? if($bus["total"] == "" and $bus["8"] == ""){
                	echo "0,00";
                }else{
					$total = $bus["total"] + $bus["8"];
               		echo number_format($total,2,',','.');
                }
                ?>            
            </td>
             <td class="Browse" align="right">
				<? if($bus["impuesto"] == "" ){
                	echo "0,00";
                }else{
					$impuesto = $bus["impuesto"];
               		echo number_format($impuesto,2,',','.');
                }
                ?>            
            </td>
				<?
                if($bus_orden["estado"] == "elaboracion"){
				?>
            <td class='Browse' align="center">
<? /*
******************************************************************************************************************************
CUANDO ACTUALIZA PRECIO NO ESTA ENVIANDO EL RESTO DE LOS DATOS DE PRESUPUESTO: AÑO, TIPO_PRESUPUESTO, ORDINAL
FUENTE_FINANCIAMIENTO

*****************************************************************************************************************************
*/ ?><img src="imagenes/refrescar.png" onClick="
                    			asignarValor('precio<?=$bus["idarticulos_requisicion"]?>','mostrarPrecio<?=$bus["idarticulos_requisicion"]?>'), 
                                actualizarPrecioCantidad(<?=$bus["idrequisicion"]?>, 
                                document.getElementById('precio<?=$bus["idarticulos_requisicion"]?>').value,
                                document.getElementById('cantidad<?=$bus["idarticulos_requisicion"]?>').value, 
                                <?=$bus["idarticulos_servicios"]?>, 
                                <?=$bus["idarticulos_requisicion"]?>, 
                                document.getElementById('id_categoria_programatica').value,
                                document.getElementById('anio').value,
                                document.getElementById('fuente_financiamiento').value,
                                document.getElementById('tipo_presupuesto').value,
                                document.getElementById('id_ordinal').value,
                                document.getElementById('contribuyente_ordinario').value)" 
                                title="Actualizar Precio y Cantidad" 
                                style="cursor:pointer"/></td>  
<td class='Browse' align="center">
                    <a href="javascript:;" onClick="eliminarMateriales(<?=$bus["idrequisicion"]?>, <?=$bus["idarticulos_requisicion"]?>, <?=$bus["idsolicitud_cotizacion"]?>, document.getElementById('id_categoria_programatica').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value, document.getElementById('contribuyente_ordinario').value)">
           			<img src="imagenes/delete.png" title="Eliminar Materiales">           		</a>            </td>
              <?
                }
				?>
          </tr>
          <?
          }
          ?>
</table>
<?
	}else{
	?>
    
	<input type="hidden" name="eliminoSolicitud" id="eliminoSolicitud" value="<?=$eliminoSolicitud?>">
	<?
	echo "No hay Materiales Asociados";
	}


}

//*******************************************************************************************************************************************
//********************************************* INGRESAR DATOS BASICOS DE LA ORDEN DE COMPRA ************************************************
//*******************************************************************************************************************************************
if($ejecutar == "agregarDatosBasicos"){


	$sql = mysql_query("insert into requisicion (tipo,
															fecha_elaboracion,
															idbeneficiarios,
															idcategoria_programatica,
															anio,
															idfuente_financiamiento,
															idtipo_presupuesto,
															justificacion,
															observaciones,
															estado,
															status,
															usuario,
															fechayhora,
															cofinanciamiento)values(
																					'".$tipo_orden."',
																					'".$fecha_validada."',
																					'".$id_beneficiarios."',
																					'".$categoria_programatica."',
																					'".$anio."',
																					'".$fuente_financiamiento."',
																					'".$tipo_presupuesto."',
																					'".$justificacion."',
																					'".$observaciones."',
																					'elaboracion',
																					'a',
																					'".$login."',
																					'".date("Y-m-d H:i:s")."',
																					'".$cofinanciamiento."')");
if($sql){
	echo mysql_insert_id();
	registra_transaccion("Ingresar datos Basicos de Orden Compra (".mysql_insert_id().")",$login,$fh,$pc,'requisicions');
}else{
	registra_transaccion("Ingresar datos Basicos de Orden Compra ERROR",$login,$fh,$pc,'requisicions');
	echo "fallo";
}
	

}


//*******************************************************************************************************************************************
//*********************************** LISTA TODAS LAS SOLICITUDES SELECCIONADAS EN UNA ORDEN DE COMPRA ***************************************
//*******************************************************************************************************************************************
if($ejecutar == "listarSolicitudesSeleccionadas"){
	$sql = mysql_query("select * from relacion_requisicion_solicitud_cotizacion where idrequisicion = ".$id_requisicion."");
	$num = mysql_num_rows($sql);
	if($num > 0){	
		?>																		
	<table width="75%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <td class="Browse"><div align="center">Nro Solicitud</div></td>
            <td class="Browse"><div align="center">Fecha</div></td>
            <td class="Browse"><div align="center">Nro Items</div></td>
            <td class="Browse"><div align="center">Monto</div></td>
          </tr>
      </thead>
          <? 
          while($bus = mysql_fetch_array($sql)){
		  	$sql2 = mysql_query("select * from solicitud_cotizacion where idsolicitud_cotizacion = ".$bus["idsolicitud_cotizacion"]."");
			$bus2 = mysql_fetch_array($sql2);
          ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class='Browse' align='center'><?=$bus2["numero"]?></td>
            <td class='Browse' align='left'><?=$bus2["fecha_solicitud"]?></td>
            <td class='Browse' align='center'><?=$bus2["nro_items"]?></td>
            <td class='Browse' align='right'><?=number_format($bus2["total"],2,",",".")?></td>
      </tr>
          <?
          }
          ?>
</table>
    <?
		}else{
		echo "<center>No hay Solicitudes Seleccionadas</center>";
		}	
}



//*******************************************************************************************************************************************
//********************************************* ACTUALIZAR PRECIO CANTIDAD DE ARTICULOS ORDEN COMPRAS ***************************************
//*******************************************************************************************************************************************

if($ejecutar == "actualizarPrecioCantidad"){

			$sql = mysql_query("select 	impuestos.destino_partida as destinoPartida, 
										impuestos.idimpuestos as idImpuestos, 
										impuestos.porcentaje as porcentajeImpuesto, 
										impuestos.idclasificador_presupuestario as clasificadorImpuestos, 
										articulos_servicios.idclasificador_presupuestario as clasificadorArticulos ,
										articulos_servicios.exento as exento
									from impuestos, articulos_servicios 
									where articulos_servicios.idarticulos_servicios = ".$id_articulo." 
										and articulos_servicios.idimpuestos = impuestos.idimpuestos")or die("1: ".mysql_error());
			$bus = mysql_fetch_array($sql);
			
			$sql_ordinal= mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id_articulo."'");
			$bus_ordinal = mysql_fetch_array($sql_ordinal);
			$idordinal= $bus_ordinal["idordinal"];
			
			
			$id_clasificador_presupuestario = $bus["clasificadorArticulos"];
			$id_clasificador_impuestos = $bus["clasificadorImpuestos"];
			$id_impuestos = $bus["idImpuestos"];
			$destino_partida = $bus["destinoPartida"];
			$partida_impuestos = 0;
			// actualizo el total con los nuevos datos enviados ( cantidad y precio )
			$total = $cantidad * $precio;
			if ($bus["exento"] == 0){
				if ($contribuyente_ordinario=="si"){
					$porcentaje_impuesto = $bus["porcentajeImpuesto"]/100;
					$impuesto_por_producto = $total * $porcentaje_impuesto;
					$exento = 0;
				}else{
					$exento = $total;
					$total = 0;
					$porcentaje_impuesto = 0;
					$impuesto_por_producto = 0;
				}
			}else{
				$exento = $total;
				$total = 0;
				$porcentaje_impuesto = 0;
				$impuesto_por_producto = 0;
			}
			
			// busco el precio y la cantidad anteriores para restarsela a los totales
			$sql_consulta_precio_viejo = mysql_query("select * from articulos_requisicion where 
													idarticulos_requisicion = ".$id_articulo_compra."")or die("2: ".mysql_error());
			$bus_consulta_precio_viejo = mysql_fetch_array($sql_consulta_precio_viejo);
			$precio_viejo = $bus_consulta_precio_viejo["precio_unitario"];
			$cantidad_vieja = $bus_consulta_precio_viejo["cantidad"];
			if ($bus["exento"] == 0){
				if ($contribuyente_ordinario=="si"){
					$porcentaje_impuesto = $bus["porcentajeImpuesto"]/100;
					$impuesto_viejo = ($precio_viejo * $cantidad_vieja) * $porcentaje_impuesto;
					$exento_viejo = 0;
					$total_viejo = $precio_viejo * $cantidad_vieja;
				}else{
					$porcentaje_impuesto = 0;
					$impuesto_viejo = 0;
					$exento_viejo = $precio_viejo * $cantidad_vieja;
					$total_viejo = 0;
				}
			}else{
				$porcentaje_impuesto = 0;
				$impuesto_viejo = 0;
				$exento_viejo = $precio_viejo * $cantidad_vieja;
				$total_viejo = 0;
			}
			// actualizo la tabla de articulos de la orden de compra con la nueva cantidad y el nuevo precio										
			$sql2 = mysql_query("update articulos_requisicion set porcentaje_impuesto = '".$porcentaje_impuesto."',
																	 impuesto = '".$impuesto_por_producto."',
																	  total = '".$total."', 
																	  precio_unitario = '".$precio."', 
																	  cantidad = '".$cantidad."',
																	  exento = '".$exento."'
																	  where 
																	  idarticulos_requisicion = ".$id_articulo_compra."")or die("3: ".mysql_error());
		
			
			// ACTUALIZO LOS TOTALES EN LA TABLA requisicion
			$total_anterior = $total_viejo + $impuesto_viejo + $exento;
			$total_nuevo = $total + $impuesto_por_producto + $exento;
			$sql_actualiza_totales = mysql_query("update requisicion set 
															sub_total = sub_total - '".$total_viejo."' + '".$total."',
															sub_total_original = sub_total_original - '".$total_viejo."' + '".$total."',
															impuesto = impuesto - '".$impuesto_viejo."' + '".$impuesto_por_producto."',
															exento = exento - '".$exento_viejo."' + '".$exento."',
															exento_original = exento_original - '".$exento_viejo."' + '".$exento."',
															total = total - '".$total_anterior."' + '".$total_nuevo."'
															where idrequisicion=".$id_requisicion." ")or die("4: ".mysql_error());
			
			
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
			
			$sql = mysql_query("select * from articulos_requisicion where idarticulos_requisicion = ".$id_articulo_compra."")or die("5: ".mysql_error());
			$bus = mysql_fetch_array($sql);
			if($bus["estado"] == "aprobado" || $bus["estado"] == "sin disponibilidad"){ // en cualquiera de stos estados el articulo tiene partida en el maestro
					$sql_compra_servicio = mysql_query("select * from requisicion where idrequisicion = ".$id_requisicion."")or die("6: ".mysql_error());
					$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
					//echo "ID: ".$bus_compra_servicio["idcategoria_programatica"]." ";
				if($destino_partida == 1 and $contribuyente_ordinario == "si"){
					// actualizo el total del impuesto sumando el nuevo impuesto y restandole el anterior *********************************************************************************************************************************************************
					
					$sql_total_impuestos = mysql_query("update relacion_impuestos_requisiciones 
													set total = total + ('".$impuesto_por_producto."'-'".$impuesto_viejo."'),
													base_calculo = base_calculo + '".$total_anterior."' + '".$total_nuevo."',
													base_calculo_original = base_calculo_original + '".$total_anterior."' + '".$total_nuevo."'
													where idrequisicion = ".$id_requisicion." 
													and idimpuestos = ".$id_impuestos."")or die("7: ".mysql_error());
					// consulta maestro con el clasificador de impuesto
					
					$sql_ordinal_impuesto = mysql_query("select * from ordinal where codigo='0000'");
					$bus_ordinal_impuesto= mysql_fetch_array($sql_ordinal_impuesto);
					
					
					
					$sql_maestro_impuestos = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
												and idcategoria_programatica = ".$bus_compra_servicio["idcategoria_programatica"]." 
												and idclasificador_presupuestario = ".$id_clasificador_impuestos."
												and idfuente_financiamiento = '".$idfuente_financiamiento."'
												and idtipo_presupuesto = '".$idtipo_presupuesto."'
												and idordinal = '".$bus_ordinal_impuesto["idordinal"]."'")or die("8: ".mysql_error());
																								
																								
																								
					$bus_maestro = mysql_fetch_array($sql_maestro_impuestos);
					
					
					$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
					
					//echo $disponible;
					//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
					$sql_total_impuestos = mysql_query("select * from relacion_impuestos_requisiciones where idrequisicion = ".$id_requisicion." 
																	and idimpuestos = ".$id_impuestos."")or die("9: ".mysql_error());
																	
					$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
					$total_impuesto_imputable = $bus_total_impuestos["total"];
					$total_impuesto_imputable2 = $impuesto_por_producto - $impuesto_viejo;
								
					if($total_impuesto_imputable > $disponible){ // comparo el impuesto imputable con el disponible en la partida para verificar su estado
					
						$sql_partida = mysql_query("update partidas_requisiciones set estado = 'sobregiro', 
																			monto = '".$total_impuesto_imputable."',
																			monto_original = '".$total_impuesto_imputable."' 
																			where 
																			idrequisicion = ".$id_requisicion."
																			and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("10: ".mysql_error());
						
					}else{

						$sql_partida = mysql_query("update partidas_requisiciones set estado = 'disponible', 
																			monto = '".$total_impuesto_imputable."',
																			monto_original = '".$total_impuesto_imputable."' 
																			where 
																			idrequisicion = '".$id_requisicion."'
																			and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("16: ".mysql_error());
						$sql_relacion = mysql_query("update relacion_impuestos_requisiciones set estado = 'disponible'
															where idrequisicion = ".$id_requisicion." 
																	and idimpuestos = ".$id_impuestos."");
					}
					
					$partida_impuestos = $id_clasificador_impuestos;
				}else{
					$partida_impuestos = 0;
				}

				/*
				$sql2 = mysql_query("update articulos_requisicion set idpartida_impuesto = ".$partida_impuestos."  
																		where idarticulos_requisicion = ".$id_articulo_compra."");
				
				*/		
				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_requisicion.exento) as exentos from articulos_requisicion, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_requisicion.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_requisicion.idrequisicion = ".$id_requisicion." ")or die("12: ".mysql_error());
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"];
				//echo $bus_imputable["totales"];
				//echo $bus_imputable["exentos"];
				if ($destino_partida == 0 and $contribuyente_ordinario=="si"){
					$sql_total_impuesto = mysql_query("select SUM(impuesto) as totales_impuesto from articulos_requisicion, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_requisicion.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_requisicion.idrequisicion = ".$id_requisicion." and idpartida_impuesto = 0")or die("13: ".mysql_error());
					$bus_total_impuesto = mysql_fetch_array($sql_total_impuesto);
					$total_impuesto = $bus_total_impuesto["totales_impuesto"];
					$total_imputable = $total_imputable + $total_impuesto;
				}

				
				
				
					// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************

			
			$sql_maestro = colsultarMaestro($anio, $bus_compra_servicio["idcategoria_programatica"], $id_clasificador_presupuestario, $idfuente_financiamiento, $idtipo_presupuesto, $idordinal, $cofinanciamiento);	

				
				while($bus_maestro = mysql_fetch_array($sql_maestro)){
				
				
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				
				if($cofinanciamiento == "si"){
					$sql_cofinanciamiento = mysql_query("select * from 
												fuentes_cofinanciamiento 
													where 
												idcofinanciamiento = '".$idfuente_financiamiento."' 
												and  idfuente_financiamiento = '".$bus_maestro["idfuente_financiamiento"]."'");
					$bus_cofinanciamiento = mysql_fetch_array($sql_cofinanciamiento);
					$porcent = "0.".$bus_cofinanciamiento["porcentaje"];
					$total_imputable_nuevo = ($total_imputable * $porcent);
				}else{
					$total_imputable_nuevo = $total_imputable;
				}
				
				
				
				if($total_imputable_nuevo > $disponible){
					$sql_partida = mysql_query("update partidas_requisiciones set estado = 'sobregiro', 
																		monto = '".$total_imputable_nuevo."',
																		monto_original = '".$total_imputable_nuevo."' 
																		where 
																		idrequisicion = ".$id_requisicion."
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("15: ".mysql_error());
					$estado = "sin disponibilidad";
				}else{
					$sql_partida = mysql_query("update partidas_requisiciones set estado = 'disponible', 
																			monto = '".$total_imputable_nuevo."',
																			monto_original = '".$total_imputable_nuevo."'
																			where 
																			idrequisicion = ".$id_requisicion."
																			and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("16: ".mysql_error());
				
					$estado = "aprobado";
				}
				}
			}else{
				$estado = "rechazado";
			}
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA		
		
			$sql2 = mysql_query("update articulos_requisicion set estado = '".$estado."',
																	idpartida_impuesto = ".$partida_impuestos." 
																where idarticulos_requisicion = ".$id_articulo_compra."")or die("17: ".mysql_error());
			
		if($sql2){
				registra_transaccion("Actualizar Precio Cantidad de Orden de Compra (".$id_articulo_compra.")",$login,$fh,$pc,'requisicions');

				echo "exito";
		}else{
				registra_transaccion("Actualizar Precio Cantidd de Orden de Compra ERROR (".$id_articulo_compra.")",$login,$fh,$pc,'requisicions');

				echo $sql2." MYSQL ERROR: ".mysql_error();
		}
}



//*******************************************************************************************************************************************
//********************************************* ACTUALIZAR DATOS BASICOS DE LAS ORDENES DE COMPRA ***************************************
//*******************************************************************************************************************************************
if($ejecutar == "actualizarDatosBasicos"){
	if($accion == "actualizar"){
	//echo "BENEFICIARIO: ".$id_beneficiarios;
		$sql = mysql_query("update requisicion set tipo = '".$tipo_orden."',
															idbeneficiarios = '".$id_beneficiarios."',
															idcategoria_programatica = '".$id_categoria_programatica."',
															anio = '".$anio."',
															idfuente_financiamiento = '".$fuente_financiamiento."',
															idtipo_presupuesto = '".$tipo_presupuesto."',
															justificacion = '".$justificacion."',
															observaciones = '".$observaciones."',
															usuario = '".$login."',
															cofinanciamiento = '".$cofinanciamiento."',
															fechayhora = '".date("Y-m-d H:i:s")."' where idrequisicion = ".$id_requisicion."")or die(mysql_error());
	registra_transaccion("Actualizar Datos Basicos de Requisicion (".$id_requisicion.")",$login,$fh,$pc,'requisicions');

	}
$sql = mysql_query("select * from requisicion where idrequisicion = '".$id_requisicion."'")or die(mysql_error());;
$bus = mysql_fetch_array($sql);															
$bus_consulta=$bus;

$fecha_anulacion='';
switch($bus["estado"]){
	  case "elaboracion":
		  $mostrar_estado = "En Elaboraci&oacute;n";
		  break;
	  case "procesado":
	  	  $mostrar_estado = "Procesado";
		  break;
	  case "conformado":
	  	  $mostrar_estado = "Conformado";
		  break;
	   case "devuelto":
	   	  $sql_tipo_documento =  mysql_query("select * from tipos_documentos where descripcion = 'Compras'");
		  $bus_tipo_documento =  mysql_fetch_array($sql_tipo_documento);
		  $sql_conformar_documento = mysql_query("select * from conformar_documentos where iddocumento = '".$bus["idrequisicion"]."' and tipo = '".$bus_tipo_documento["idtipos_documentos"]."'")or die(mysql_error());
		  $bus_conformar_documento = mysql_fetch_array($sql_conformar_documento);
		  $sql_razones_devolucion = mysql_query("select * from razones_devolucion where idrazones_devolucion = '".$bus_conformar_documento["idrazones_devolucion"]."'");
		  $bus_razones_devolucion = mysql_fetch_array($sql_razones_devolucion);
	  	  $mostrar_estado = "Devuelto : "; 
		  if($bus_razones_devolucion["descripcion"] == ""){ 
		  	$mostrar_estado .="Sin Razones";
		  }else{ 
		  	$mostrar_estado .=$bus_razones_devolucion["descripcion"];
		  }
		  break;
	  case "anulado":
	  	  $mostrar_estado = "Anulado";
		  break;
	  case "ordenado":
	  	  $sql_relacion_compra = mysql_query("select orden_compra_servicio.numero_orden as numero_orden
										from relacion_compra_requisicion, orden_compra_servicio
										where relacion_compra_requisicion.idrequisicion = '".$bus["idrequisicion"]."'
										and relacion_compra_requisicion.idorden_compra = orden_compra_servicio.idorden_compra_servicio")or die(mysql_error());
	  	  $bus_relacion_compra = mysql_fetch_array($sql_relacion_compra);
		  $mostrar_estado = "Ordenado : ".$bus_relacion_compra["numero_orden"]."";
		  break;
	  case "pagado":
	  	$sql_relacion_pago = mysql_query("select * from relacion_pago_compromisos where idrequisicion = '".$bus["idrequisicion"]."'")or die(mysql_error());
		$bus_relacion_pago = mysql_fetch_array($sql_relacion_pago);
		$sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_relacion_pago["idorden_pago"]."'");
		$bus_orden_pago = mysql_fetch_array($sql_orden_pago);
		$sql_cheque = mysql_query("select * from pagos_financieros where idorden_pago = '".$bus_orden_pago["idorden_pago"]."'");
		$bus_cheque = mysql_fetch_array($sql_cheque);
	  	$mostrar_estado = "Pagado : ".$bus_orden_pago["numero_requisicion"]." : ".$bus_orden_pago["fecha_orden"]." : ".$bus_cheque["numero_cheque"]." : ".$bus_cheque["fecha_cheque"];
		  break;
	  }


	$sql_categoria_programatica = mysql_query("select * from categoria_programatica where idcategoria_programatica = '".$bus_consulta["idcategoria_programatica"]."'");
	$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
	$sql_unidad_ejecutora = mysql_query("select * from unidad_ejecutora where idunidad_ejecutora = '".$bus_categoria_programatica["idunidad_ejecutora"]."'");
	$bus_unidad_ejecutora = mysql_fetch_array($sql_unidad_ejecutora);

	$categoria_programatica = $bus_categoria_programatica["codigo"].' '.$bus_unidad_ejecutora["denominacion"];


	$sql_proveedor = mysql_query("select * from beneficiarios where idbeneficiarios = '".$bus_consulta["idbeneficiarios"]."'");
	$bus_proveedor = mysql_fetch_array($sql_proveedor);
	
	$beneficiario = $bus_proveedor["nombre"];
	$contribuyente = $bus_proveedor["contribuyente_ordinario"];

	echo $bus_consulta["numero_requisicion"]				."|.|".
		$bus_consulta["fecha_orden"]						."|.|".
		$bus_consulta["fecha_elaboracion"]					."|.|".
		$bus_consulta["estado"]								."|.|".
		'<STRONG>'.$mostrar_estado.'</strong>'				."|.|".
		$bus_consulta["tipo"]								."|.|".
		$bus_consulta["idcategoria_programatica"]			."|.|".
		$categoria_programatica								."|.|".
		$bus_consulta["idfuente_financiamiento"]			."|.|".
		$bus_consulta["cofinanciamiento"]					."|.|".
		$bus_consulta["idtipo_presupuesto"]					."|.|".
		$bus_consulta["anio"]								."|.|".
		$bus_consulta["justificacion"]						."|.|".
		$bus_consulta["observaciones"]						."|.|".
		$bus_consulta["idbeneficiarios"]					."|.|".
		$beneficiario										."|.|".
		$contribuyente										."|.|";

 															
}


//*******************************************************************************************************************************************
//********************************************* ACTUALIZAR LISTA DE TOTALES DE LAS ORDENES DE COMPRA ***************************************
//*******************************************************************************************************************************************
if($ejecutar == "actualizarListaDeTotales"){
$sql_relacion = mysql_query("select * from relacion_impuestos_requisiciones where idrequisicion = ".$id_requisicion."");
$bus_relacion = mysql_fetch_array($sql_relacion);

$sql_requisicion = mysql_query("select * from requisicion where idrequisicion = '".$id_requisicion."'");
$bus_requisicion = mysql_fetch_array($sql_requisicion);

if($bus_orden["estado"] == "elaboracion"){
		$sql = mysql_query("select * from articulos_requisicion where idrequisicion = ".$id_requisicion."");
			
			while($bus = mysql_fetch_array($sql)){
				$exento += $bus["exento"];
				$sub_total += $bus["total"];
				$total_impuesto += $bus["impuesto"];
				$total_general += ($bus["total"] + $bus["impuesto"] + $bus["exento"]);	
			}
		$actualiza_totales = mysql_query("update requisicion set 	sub_total = '".$sub_total."',
																	sub_total_original = '".$sub_total."',
																	impuesto = '".$total_impuesto."',
																	exento = '".$exento."',
																	exento_original = '".$exento."',
																	total = '".$total_general."'
																	where idrequisicion=".$id_requisicion." ")or die (mysql_error());
																	
}


if($bus_relacion["estado"] == "sin disponibilidad"){
	$color = "#FFFF00";
}else if($bus_relacion["estado"] == "rechazado"){
	$color = "#FF0000";
}else{
$color = "";
}

$exento = $bus_requisicion["exento"];
$sub_total = $bus_requisicion["sub_total"];
$total_impuesto = $bus_requisicion["impuesto"];
$total_general = $bus_requisicion["total"];
							
		?>
        <b>Exento:</b> <?=number_format($exento,2,',','.')?> | <b>Sub Total:</b> <?=number_format($sub_total,2,',','.')?> | <span style="background-color:<?=$color?>"><b>Impuestos:</b> <?=number_format($total_impuesto,2,',','.')?></span> | <b>Total Bs:</b> <?=number_format($total_general,2,',','.')?>
        <?
}




//*******************************************************************************************************************************************
//********************************************* ACTUALIZAR LISTA DE TOTALES DE LAS PARTIDAS ***************************************
//*******************************************************************************************************************************************
if($ejecutar == "actualizarTotalesPartidas"){
		$sql = mysql_query("select * from partidas_requisiciones where idrequisicion = ".$id_requisicion."");
			
			while($bus = mysql_fetch_array($sql)){
				$monto += $bus["monto"];
			}
							
		?>
        <?="<strong>Total Bsf: </strong>".number_format($monto,2,',','.')?>
        <?
}



//*******************************************************************************************************************************************
//********************************************* LISTA DE PARTIDAS ASOCIADAS A LOS MATERIALES SELECCIONADOS **********************************

//*******************************************************************************************************************************************
if($ejecutar == "mostrarPartidas"){
$sql_orden = mysql_query("select * from requisicion where idrequisicion = '".$id_requisicion."'");
$bus_orden = mysql_fetch_array($sql_orden);
//and idclasificador_presupuestario = ".$bus_orden["idclasificador_presupuestario"]." 

$sql_partidas = mysql_query("select * from partidas_requisiciones where idrequisicion = ".$id_requisicion."");
																		
$num_partidas = mysql_num_rows($sql_partidas);
if($num_partidas != 0){
	?>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
		<thead>
          <tr>
            <td class="Browse" colspan="4" width="10%"><div align="center">Partida</div></td>
            <td class="Browse" width="60%"><div align="center">Descripci&oacute;n</div></td>
            <td class="Browse" width="15%"><div align="center">Disponible</div></td>
            <td class="Browse" width="15%"><div align="center">Monto a Comprometer</div></td>
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
			
			
          $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = ".$bus_maestro["idclasificador_presupuestario"]."");
		  $bus_clasificador = mysql_fetch_array($sql_clasificador);
		  ?>
            <td class='Browse' align='left'><?=$bus_clasificador["partida"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["generica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["sub_especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["denominacion"]?></td>

    	      <td class='Browse' align="right"><?=number_format($bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"]- $bus_maestro["pre_compromiso"] - $bus_maestro["reservado_disminuir"],2,',','.')?></td>
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



// *****************************************************************************************************************************************
// ************************************************* PROCESAR ORDEN DE COMPRA Y SERVICIO ***************************************************
// *****************************************************************************************************************************************

if($ejecutar == "procesarOrden"){

	$sql_articulos_requisicion = mysql_query("select * from articulos_requisicion where idrequisicion = ".$id_requisicion."")or die(mysql_error());
	$sql_compra_servicio = mysql_query("select * from requisicion where idrequisicion = ".$id_requisicion."")or die(mysql_error());
	$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
	
	while($bus_articulos_requisicion = mysql_fetch_array($sql_articulos_requisicion)){
	$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios='".$bus_articulos_requisicion["idarticulos_servicios"]."'");
	$bus_ordinal = mysql_fetch_array($sql_ordinal);
	$idordinal =  $bus_ordinal["idordinal"];
	//*************************************************************************************
	
			$sql_impuestos = mysql_query("select impuestos.destino_partida as destinoPartida, 
										impuestos.idimpuestos as idImpuestos, 
										impuestos.porcentaje as porcentajeImpuesto, 
										impuestos.idclasificador_presupuestario as clasificadorImpuestos, 
										articulos_servicios.idclasificador_presupuestario as clasificadorArticulos,
										articulos_servicios.exento as exento
									from impuestos, articulos_servicios 
									where articulos_servicios.idarticulos_servicios = ".$bus_articulos_requisicion["idarticulos_servicios"]." 
										and articulos_servicios.idimpuestos = impuestos.idimpuestos")or die("AQUI... ".mysql_error());
			$bus_impuestos = mysql_fetch_array($sql_impuestos);
			
			$id_clasificador_presupuestario = $bus_impuestos["clasificadorArticulos"];
			$id_clasificador_impuestos = $bus_impuestos["clasificadorImpuestos"];
			$id_impuestos = $bus_impuestos["idImpuestos"];
			$destino_partida = $bus_impuestos["destinoPartida"];
			
			//echo "Porcentaje: ".$bus[3];
			
			$total = $bus_articulos_requisicion["cantidad"] * $bus_articulos_requisicion["precio_unitario"];
			if ($bus_impuestos["exento"] == 0){
				if ($contribuyente_ordinario == "si"){
					$porcentaje_impuesto = $bus_impuestos ["porcentajeImpuesto"]/100;
					$impuesto_por_producto = $total * $porcentaje_impuesto;
					$exento = 0;
				}else{
					$exento = $total;
					$total = 0;
					$porcentaje_impuesto = 0;
					$impuesto_por_producto = 0;
				}
			}else{
				$exento = $total;
				$total = 0;
				$porcentaje_impuesto = 0;
				$impuesto_por_producto = 0;
			}	
													
			$sql2 = mysql_query("update articulos_requisicion set porcentaje_impuesto = '".$porcentaje_impuesto."',
																	 impuesto = '".$impuesto_por_producto."',
																	  total = '".$total."', 
																	  exento = '".$exento."',
																	  precio_unitario = '".$bus_articulos_requisicion["precio_unitario"]."', 
																	  cantidad = ".$bus_articulos_requisicion["cantidad"]."
																	 where 
																	  idarticulos_requisicion = ".$bus_articulos_requisicion["idarticulos_requisicion"]."")or die("error update articulos".mysql_error());
		
		
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
			$sql = mysql_query("select * from articulos_requisicion 
														where idarticulos_requisicion = ".$bus_articulos_requisicion["idarticulos_requisicion"]." 
															")or die("error todos los articulos".mysql_error());
			$bus = mysql_fetch_array($sql);
			
			if($bus["estado"] == "aprobado" || $bus["estado"] == "sin disponibilidad"){
			
				if($destino_partida == 1 and $contribuyente_ordinario == "si"){
//*************************************************************************************************************					
					
					$sql_ordinal_impuesto= mysql_query("select * from ordinal where codigo='0000'");
					$bus_ordinal_impuesto = mysql_fetch_array($sql_ordinal_impuesto);
					
					
					$sql_maestro_impuestos = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
												and idcategoria_programatica = ".$bus_compra_servicio["idcategoria_programatica"]." 
												and idclasificador_presupuestario = ".$id_clasificador_impuestos."
												and idfuente_financiamiento = '".$idfuente_financiamiento."'
												and idtipo_presupuesto = '".$idtipo_presupuesto."'
												and idordinal = '".$bus_ordinal_impuesto["idordinal"]."'")or die("ERROR AQUI:".mysql_error());
																							
																							
					$bus_maestro = mysql_fetch_array($sql_maestro_impuestos);
					
					
					$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
					
					$sql_total_impuestos = mysql_query("select * from relacion_impuestos_requisiciones 
																where idrequisicion = ".$id_requisicion." 
																	and idimpuestos = ".$id_impuestos."")or die("ERROR ACA: ".mysql_error());
																	
					$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
					$total_impuesto_imputable = $bus_total_impuestos["total"];				
						if($total_impuesto_imputable > $disponible){
							$sql_partida = mysql_query("update partidas_requisiciones set estado = 'sobregiro', 
									monto = '".$total_impuesto_imputable."',
									monto_original = '".$total_impuesto_imputable."' 
									where idrequisicion = ".$id_requisicion."
									and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("error partida impuesto".mysql_error());
							
						}else{
							$sql_partida = mysql_query("update partidas_requisiciones set estado = 'disponible', 
									monto = '".$total_impuesto_imputable."',
									monto_original = '".$total_impuesto_imputable."' 
									where idrequisicion = ".$id_requisicion."
									and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")or die("error partida impuesto".mysql_error());
						}
					$partida_impuestos = $id_clasificador_impuestos;
				}else{
					$partida_impuestos = 0;
				}

				
				$sql2 = mysql_query("update articulos_requisicion set idpartida_impuesto = ".$partida_impuestos."  
																	where idarticulos_requisicion = ".$bus_articulos_requisicion["idarticulos_requisicion"]."")or die(mysql_error());
				
						
				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_requisicion.exento) as exentos from articulos_requisicion, articulos_servicios 
										where articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_requisicion.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_requisicion.idrequisicion = ".$id_requisicion." ")or die("PROBLEMAS AQUI:".mysql_error());
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"];
				if ($destino_partida == 0 and $contribuyente_ordinario == "si"){
					$sql_total_impuesto = mysql_query("select SUM(impuesto) as totales_impuesto from articulos_requisicion, articulos_servicios where
												articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
												and articulos_requisicion.idarticulos_servicios = articulos_servicios.idarticulos_servicios
												and articulos_requisicion.idrequisicion = ".$id_requisicion." and idpartida_impuesto = 0")or die(mysql_error());
					$bus_total_impuesto = mysql_fetch_array($sql_total_impuesto);
					$total_impuesto = $bus_total_impuesto["totales_impuesto"];
				}				
				$total_imputable = $total_imputable+$total_impuesto;
//*********************************************************************************				
				
				
				
				// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************

			
			$sql_maestro = colsultarMaestro($anio, $bus_compra_servicio["idcategoria_programatica"], $id_clasificador_presupuestario, $idfuente_financiamiento, $idtipo_presupuesto, $idordinal, $cofinanciamiento);	
				
			
				//echo "AQUI: ".$idordinal;
				while($bus_maestro = mysql_fetch_array($sql_maestro)){
				
				
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				
				
				if($cofinanciamiento == "si"){
					$sql_cofinanciamiento = mysql_query("select * from 
												fuentes_cofinanciamiento 
													where 
												idcofinanciamiento = '".$fuente_financiamiento."' 
												and  idfuente_financiamiento = '".$bus_maestro["idfuente_financiamiento"]."'");
					$bus_cofinanciamiento = mysql_fetch_array($sql_cofinanciamiento);
					$porcent = "0.".$bus_cofinanciamiento["porcentaje"];
					$total_imputable_nuevo = ($total_imputable * $porcent);
				}else{
					$total_imputable_nuevo = $total_imputable;
				}
				
				
				
				if($total_imputable_nuevo > $disponible){
					$sql_partida = mysql_query("update partidas_requisiciones set estado = 'sobregiro', 
																			monto = '".$total_imputable_nuevo."',
																			monto_original = '".$total_imputable_nuevo."'  
																			where idrequisicion = ".$id_requisicion."
																				and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("M".mysql_error());
					$estado = "sin disponibilidad";
				}else{
					$sql_partida = mysql_query("update partidas_requisiciones set estado = 'disponible', 
																			monto = '".$total_imputable_nuevo."',
																			monto_original = '".$total_imputable_nuevo."' 
																			where idrequisicion = ".$id_requisicion."
																				and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")or die("M".mysql_error());
				
					$estado = "aprobado";
				}
				}
			}else{
				$estado = "rechazado";
			}
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA		
				
				
				
			$sql2 = mysql_query("update articulos_requisicion set estado = '".$estado."' 
													where idarticulos_requisicion = ".$bus_articulos_requisicion["idarticulos_requisicion"]."")or die("X".mysql_error());
			
	
	//*****************************************************************************************
	}// CIERRE DEL WHILE DE CONSULTA DE LOS ARTICULOS DE LA ORDEN





	$sql_articulos = mysql_query("select * from articulos_requisicion 
												where idrequisicion = ".$id_requisicion."")or die("X".mysql_error());
	$num_articulos = mysql_num_rows($sql_articulos);
	
	if($num_articulos != 0){
		$sql_orden_duplicados = mysql_query("select * from requisicion where idrequisicion = ".$id_requisicion."");
		$bus_orden_duplicados = mysql_fetch_array($sql_orden_duplicados);
			if($bus_orden_duplicados["duplicados"] == 0){
				$sql_articulos = mysql_query("select * from articulos_requisicion 
														where idrequisicion = ".$id_requisicion." 
														and (estado = 'rechazado' or estado = 'sin disponibilidad')")or die("X".mysql_error());
		$num_articulos = mysql_num_rows($sql_articulos);
		
		if($num_articulos == 0){
			$sql_impuestos = mysql_query("select * from relacion_impuestos_requisiciones where 
														idrequisicion = ".$id_requisicion." 
														and (estado = 'rechazado' or estado = 'sin disponibilidad')")or die("X".mysql_error());
			$num_impuestos = mysql_num_rows($sql_impuestos);
			
			if($num_impuestos == 0){
				$sql_actualizar_partidas = mysql_query("select * from partidas_requisiciones where idrequisicion = ".$id_requisicion."")or die("X".mysql_error());
				while($bus_actualizar_partidas = mysql_fetch_array($sql_actualizar_partidas)){
					$sql_maestro = mysql_query("update maestro_presupuesto set 
															pre_compromiso = pre_compromiso + ".$bus_actualizar_partidas["monto"]."
															where idRegistro = ".$bus_actualizar_partidas["idmaestro_presupuesto"]."")or die("X".mysql_error());
				}
					
				$sql_orden = mysql_query("select * from requisicion where idrequisicion = ".$id_requisicion."")or die("X".mysql_error());
				$bus_orden = mysql_fetch_array($sql_orden);
				$tipo_orden = $bus_orden["tipo"];
			
				$sql_configuracion = mysql_query("select * from configuracion");
				$bus_configuracion = mysql_fetch_array($sql_configuracion);
				$anio_fiscal = $bus_configuracion["anio_fiscal"];
				
				
				$sql_configuracion_compras = mysql_query("select * from configuracion_compras");
				$bus_configuracion_compras = mysql_fetch_array($sql_configuracion_compras);
				
				$nro_requisicion = $bus_configuracion_compras["nro_requisicion"];
				
				$numero_completo = "RQ-".$bus_configuracion["anio_fiscal"]."-".$nro_requisicion;
				

				$sql_existe_numero = mysql_query("select * from requisicion where numero_requisicion = '".$numero_completo."'")or die("cero".mysql_error());
				$bus_existe = mysql_num_rows($sql_existe_numero);
				
				while ($bus_existe > 0){
					
					$sql_actualizar_conf_compras = mysql_query("update configuracion_compras set nro_requisicion = nro_requisicion+1");
					
					$sql_configuracion_compras = mysql_query("select * from configuracion_compras");
					$bus_configuracion_compras = mysql_fetch_array($sql_configuracion_compras);
					
					$nro_requisicion = $bus_configuracion_compras["nro_requisicion"];
					
					$numero_completo = "RQ-".$_SESSION["anio_fiscal"]."-".$nro_requisicion;
						
					$sql_existe_numero = mysql_query("select * from requisicion where numero_requisicion = '".$numero_completo."'")or die("tres".mysql_error());
					$bus_existe = mysql_num_rows($sql_existe_numero);
				}
				
				
				
				// ACA SE GENERA EL NUMERO DE CONTROL DE LA ORDEN DE COMPRA
				$sql_actualizar_conf_compras = mysql_query("update configuracion_compras set nro_requisicion = nro_requisicion+1");

				$codigo_referencia = 90000000000+$bus_nro_orden["nro_contador"];
				
				$sql_actualizar_orden = mysql_query("update requisicion set estado = 'procesado', 
																						numero_requisicion = '".$numero_completo."',
																						fecha_orden = '".$fecha_validada."',
																						codigo_referencia = '".$codigo_referencia."'
																					where idrequisicion = ".$id_requisicion."")or die("error".mysql_error());
				
				
				if($sql_actualizar_orden){
					echo "exito";
				}
				
				
				/*$sql_relacion_compra_solicitud = mysql_query("select * from relacion_requisicion_solicitud_cotizacion where idrequisicion = ".$id_requisicion."");
				
				while ($bus_relacion_compra_solicitud = mysql_fetch_array($sql_relacion_compra_solicitud))
				{
					$sql_actualizar_solicitud = mysql_query("update solicitud_cotizacion set estado = 'ordenado', 
																						nro_orden = '".$codigo_orden."' 
																					where idsolicitud_cotizacion = ".$bus_relacion_compra_solicitud["idsolicitud_cotizacion"]."");
				}*/			
				/*if($sql_actualizar_orden){
					$sql_actualizar_numero = mysql_query("update tipos_documentos set nro_contador = nro_contador + 1 where idtipos_documentos = ".$tipo_orden."")or die("uno".mysql_error());
					
					registra_transaccion("Procesar Orden de Compra y Servicio (".$codigo_orden.")",$login,$fh,$pc,'requisicions');
				}else{
					echo "fallo";
				}*/
			}else{
				echo "falloImpuestos";
			}
		}else{
			echo "falloMateriales";
		}
	  }else{
		echo "duplicados";
	}
	}else{
		echo "sinMateriales";
	}
}


// *****************************************************************************************************************************************
// ************************************************* ANULAR ORDEN DE COMPRA Y SERVICIO ***************************************************
// *****************************************************************************************************************************************


if($ejecutar == "anularOrden"){
$sql = mysql_query("select * from usuarios where login = '".$login."' and clave = '".md5($clave)."'");
$num = mysql_num_rows($sql);
if($num > 0){

	$sql_orden = mysql_query("update requisicion set estado = 'anulado' where idrequisicion = ".$id_requisicion."")or die(mysql_error());
	$sql_actualizar_partidas = mysql_query("select * from partidas_requisiciones where idrequisicion = ".$id_requisicion."")or die(mysql_error());
		while($bus_actualizar_partidas = mysql_fetch_array($sql_actualizar_partidas)){
			$sql_maestro = mysql_query("update maestro_presupuesto set 
												pre_compromiso = pre_compromiso - ".$bus_actualizar_partidas["monto"]."
												where idRegistro = ".$bus_actualizar_partidas["idmaestro_presupuesto"]."")or die(mysql_error());
		}
	
	if($sql_orden){
		echo "exito";
		registra_transaccion("Anular orden Compra (".$id_requisicion.")",$login,$fh,$pc,'requisicions');
	}else{
		registra_transaccion("Anular Orden Compra ERROR (".$id_requisicion.")",$login,$fh,$pc,'requisicions');
		echo "fallo";
	}
	}else{
		echo "claveIncorrecta";
	}
}


// *****************************************************************************************************************************************
// ************************************************* DUPLICAR ORDEN DE COMPRA Y SERVICIO ***************************************************
// *****************************************************************************************************************************************


if($ejecutar == "duplicarOrden"){
	$sql_orden = mysql_query("select * from requisicion where idrequisicion = ".$id_requisicion."");
	$bus_orden = mysql_fetch_array($sql_orden);// DUPLICACION DE LOS DATOS BASICOS DE LA ORDEN DE COMPRA
	$sql_insert_orden = mysql_query("insert into requisicion(tipo,
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
																		nro_items,
																		sub_total,
																		sub_total_original,
																		impuesto,
																		total,
																		exento,
																		exento_original,
																		estado,
																		idrazones_devolucion,
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
																		nro_factura,
																		fecha_factura,
																		nro_control,
																		cofinanciamiento)value(
																						'".$bus_orden["tipo"]."',
																						'".$bus_orden["fecha_elaboracion"]."',
																						'".$bus_orden["proceso"]."',
																						'".$bus_orden["numero_documento"]."',
																						'".$bus_orden["idbeneficiarios"]."',
																						'".$bus_orden["idcategoria_programatica"]."',
																						'".$bus_orden["anio"]."',
																						'".$bus_orden["idfuente_financiamiento"]."',
																						'".$bus_orden["idtipo_presupuesto"]."',
																						'".$bus_orden["justificacion"]."',
																						'".$bus_orden["observaciones"]."',
																						'".$bus_orden["nro_items"]."',
																						'".$bus_orden["sub_total"]."',
																						'".$bus_orden["sub_total"]."',
																						'".$bus_orden["impuesto"]."',
																						'".$bus_orden["total"]."',
																						'".$bus_orden["exento"]."',
																						'".$bus_orden["exento"]."',
																						'elaboracion',
																						'".$bus_orden["idrazones_devolucion"]."',
																						'".$bus_orden["numero_remision"]."',
																						'".$bus_orden["fecha_remision"]."',
																						'".$bus_orden["recibido_por"]."',
																						'".$bus_orden["cedula_recibido"]."',
																						'".$bus_orden["fecha_recibido"]."',
																						'".$bus_orden["ubicacion"]."',
																						'".$bus_orden["status"]."',
																						'".$login."',
																						'".date("Y-m-d H:i:s")."',
																						'".$bus_orden["duplicados"]."',
																						'".$bus_orden["nro_factura"]."',
																						'".$bus_orden["fecha_factura"]."',
																						'".$bus_orden["nro_control"]."',
																						'".$bus_orden["cofinanciamiento"]."')")or die(mysql_error());
	$nueva_orden_compra = mysql_insert_id();
	$sql_articulos = mysql_query("select * from articulos_requisicion where idrequisicion = ".$id_requisicion."");
	while($bus_articulos = mysql_fetch_array($sql_articulos)){// DUPLICACION DE LOS ARTICULOS
		$sql_insert_articulos = mysql_query("insert into articulos_requisicion(idrequisicion,
																					idarticulos_servicios,
																					cantidad,
																					precio_unitario,
																					porcentaje_impuesto,
																					impuesto,
																					total,
																					exento,
																					idsolicitud_cotizacion,
																					estado,
																					duplicado,
																					status,
																					usuario,
																					fechayhora,
																					idpartida_impuesto)values('".$nueva_orden_compra."',
																											'".$bus_articulos["idarticulos_servicios"]."',
																											'".$bus_articulos["cantidad"]."',
																											'".$bus_articulos["precio_unitario"]."',
																											'".$bus_articulos["porcentaje_impuesto"]."',
																											'".$bus_articulos["impuesto"]."',
																											'".$bus_articulos["total"]."',
																											'".$bus_articulos["exento"]."',
																											'".$bus_articulos["idsolicitud_cotizacion"]."',
																											'".$bus_articulos["estado"]."',
																											'".$bus_articulos["duplicado"]."',
																											'".$bus_articulos["status"]."',
																											'".$login."',
																											'".date("Y-m-d H:i:s")."',
																											'".$bus_articulos["idpartida_impuesto"]."')");
	}
	

	$sql_partidas = mysql_query("select * from partidas_requisiciones where idrequisicion = ".$id_requisicion."");
	while($bus_partidas = mysql_fetch_array($sql_partidas)){// DUPLICAR LAS PARTIDAS
		$sql_insert_partidas = mysql_query("insert into partidas_requisiciones(idrequisicion,
																						idmaestro_presupuesto,
																						monto,
																						monto_original,
																						estado,
																						status,
																						usuario,
																						fechayhora)values('".$nueva_orden_compra."',
																									'".$bus_partidas["idmaestro_presupuesto"]."',
																									'".$bus_partidas["monto"]."',
																									'".$bus_partidas["monto_original"]."',
																									'".$bus_partidas["estado"]."',
																									'".$bus_partidas["status"]."',
																									'".$login."',
																									'".date("Y-m-d H:i:S")."')");
	
	}
	
	$sql_relacion_impuestos = mysql_query("select * from relacion_impuestos_requisiciones where idrequisicion = ".$id_requisicion."");
	while($bus_relacion_impuestos = mysql_fetch_array($sql_relacion_impuestos)){
		$sql_insert_relacion_impuestos = mysql_query("insert into relacion_impuestos_requisiciones(idrequisicion,
																							idimpuestos,
																							porcentaje,
																							base_calculo,
																							base_calculo_original,
																							total,
																							estado)values(
																						'".$nueva_orden_compra."',
																						'".$bus_relacion_impuestos["idimpuestos"]."',
																						'".$bus_relacion_impuestos["porcentaje"]."',
																						'".$bus_relacion_impuestos["base_calculo"]."',
																						'".$bus_relacion_impuestos["base_calculo_original"]."',
																						'".$bus_relacion_impuestos["total"]."',
																						'".$bus_relacion_impuestos["estado"]."')");
		
	}
	
	$sql_relacion_compra = mysql_query("select * from relacion_requisicion_solicitud_cotizacion where idrequisicion = ".$id_requisicion."");
	while($bus_relacion_compra = mysql_fetch_array($sql_relacion_compra)){
		$sql_insert_relacion_compras = mysql_query("insert into relacion_compra_solicitud_servicio(idrequisicion_solicitud,
																									idsolicitud_cotizacion)values(
																									'".$nueva_orden_compra."',
																									'".$bus_relacion_compra["idsolicitud_cotizacion"]."')");

	}
	registra_transaccion("Duplicar Orden Compra (Id Anterior:".$id_requisicion.", Id Nuevo:".$nueva_orden_compra.")",$login,$fh,$pc,'requisicions');
	echo $nueva_orden_compra;
}


// **************************************************************************************************************************************************
// **********************************************         INGRESAR PARTIDA INDIVIDUAL        ********************************************************
// **************************************************************************************************************************************************



if($ejecutar == "ingresarMaterialIndividual"){

	$sql = mysql_query("select * from articulos_requisicion where idarticulos_servicios = ".$id_material." and idrequisicion = ".$id_requisicion."");
	$num = mysql_num_rows($sql);
	// SI EL ARTICULO NO EXISTE ENTRE LOS ARTICULOS CARGADOS
	if($num == 0){

		$total_articulo_individual = $cantidad * $precio_unitario;
		$sql_orden = mysql_query("select * from requisicion where idrequisicion = ".$id_requisicion."");
		$bus_orden = mysql_fetch_array($sql_orden);
		
		$sql_ordinal  = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id_material."'");
		$bus_ordinal= mysql_fetch_array($sql_ordinal);
		$idordinal = $bus_ordinal["idordinal"];
		
		
		//BUSCO EL IMPUESTO QUE SE LE APLICA AL ARTICULO PARA SABER SI TIENE PARTIDA PROPIA O SE VA A CARGAR A LA PARTIDA DEL ARTICULO
		$sql2 = mysql_query("select impuestos.destino_partida as destinoPartida,
								 impuestos.idimpuestos as idImpuestos, 
								 impuestos.porcentaje as porcentajeImpuesto, 
								 impuestos.idclasificador_presupuestario as clasificadorImpuestos,
								 articulos_servicios.idclasificador_presupuestario as clasificadorArticulos,
								 articulos_servicios.exento as exento 
								 from impuestos, articulos_servicios 
									 where 
									 articulos_servicios.idarticulos_servicios = ".$id_material." 
									 and impuestos.idimpuestos = articulos_servicios.idimpuestos") or die("ERROR:".mysql_error());
		$bus2 = mysql_fetch_array($sql2);

		$id_clasificador_presupuestario = $bus2["clasificadorArticulos"];
		$id_clasificador_impuestos = $bus2["clasificadorImpuestos"];
		$id_impuestos = $bus2["idImpuestos"];
		$destino_partida = $bus2["destinoPartida"];
		$porcentaje_impuesto = $bus2["porcentajeImpuesto"];
		
		if ($bus2["exento"] == 0){
			if ($contribuyente_ordinario=="si"){
				$porcentaje_impuesto = $bus2["porcentajeImpuesto"];
				$total_impuesto_individual = ($total_articulo_individual * $porcentaje_impuesto) / 100;
				$exento = 0;
			}else{
				$exento = $total_articulo_individual;
				$total_articulo_individual = 0;
				$porcentaje_impuesto = 0;
				$total_impuesto_individual = 0;
			}
		}else{
			$exento = $total_articulo_individual;
			$total_articulo_individual = 0;
			$porcentaje_impuesto = 0;
			$total_impuesto_individual = 0;
		}	
		
		if($destino_partida == 0){ // EL IMPUESTO SE CARGA A LA PARTIDA DEL ARTICULO
			$id_partida_impuesto = 0;
		}else{
			$id_partida_impuesto = $id_clasificador_impuestos; // EL IMPUESTO TIENE PARTIDA PROPIA
		}

		// AGREGO EL ARTICULO DE LA SOLICITUD DE COTIZACION A LA ORDEN DE COMPRA
		
		$sql = mysql_query("insert into articulos_requisicion (idrequisicion,
																	idarticulos_servicios,
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
																	'".$id_requisicion."',
																	'".$id_material."',
																	'".$cantidad."',
																	'".$precio_unitario."',
																	'".$porcentaje_impuesto."',
																	'".$total_impuesto_individual."',
																	'".$total_articulo_individual."',
																	'".$exento."',																	
																	'a',
																	'".$login."',
																	'".date("Y-m-d H:i:s")."',
																	'".$id_partida_impuesto."'
																	)")or die("AQUIIIIIIII ".mysql_error());

		$id_ultimo_generado = mysql_insert_id(); 	// OBTENGO EL ULTIMO ID INGRESADO EN LA TABLA DE ARTICULOS PARA ACTUALIZARLE EL ESTADO DESPUES DE ANALIZAR LA
													//DISPONIBILIDAD DE LAS PARTIDAS
		$actualiza_totales = mysql_query("update requisicion set 	sub_total = sub_total + '".$total_articulo_individual."',
																	sub_total_original = sub_total_original + '".$total_articulo_individual."',
																	impuesto = impuesto + '".$total_impuesto_individual."',
																	exento = exento + '".$exento."',
																	exento_original = exento_original + '".$exento."',
											total = total + '".$total_articulo_individual."' + '".$total_impuesto_individual."' + '".$exento."'
																					where idrequisicion=".$id_requisicion." ")or die ("11111111 ".mysql_error());
		
		if ($destino_partida<>0 and $contribuyente_ordinario=="si"){ // SI EL IMPUESTO TIENE PARTIDA PROPIA
			$sql_existe_partida=mysql_query("select * from relacion_impuestos_requisiciones 
																	where idrequisicion=".$id_requisicion." 
																		and idimpuestos=".$id_impuestos."")or die("222222 ".mysql_error());
			$num=mysql_num_rows($sql_existe_partida); // VERIFICO SI ESE IMPUESTO YA FUE INGRESADO A LA TABLA DE RELACION DE IMPUESTOS CON ORDEN DE COMPRA
			if ($num==0) {
				$sql2 = mysql_query("insert into relacion_impuestos_requisiciones (idrequisicion,
																					idimpuestos,
																					porcentaje,
																					base_calculo,
																					base_calculo_original,
																					total)
																			value(
																					".$id_requisicion.",
																					".$id_impuestos.",
																					".$porcentaje_impuesto.",
																					'".$total_articulo_individual."',
																					'".$total_articulo_individual."',
																					'".$total_impuesto_individual."'
																					)")or die("ERRORRRRRRRRRRRR ".mysql_error());
			}else {
				// SI YA EXISTE EN LA TABLA LE SUMO EL IMPUESTO DEL NUEVO ARTICULO AL TOTAL
				$sql2=mysql_query("update relacion_impuestos_requisiciones set total=total+".$total_impuesto_individual." where idrequisicion = '".$id_requisicion."'")or die("3333333 ".mysql_error());
			}
			
			// VALIDO LA PARTIDA DEL IMPUESTO EXISTA EN EL MAESTRO DE PRESUPUESTO 
			
									// consulta maestro con el clasificador de impuesto
			$sql_ordinal_impuesto = mysql_query("select * from ordinal where codigo='0000'");
			$bus_ordinal_impuesto = mysql_fetch_array($sql_ordinal_impuesto);
			
			$sql_maestro_impuestos = mysql_query("select * from maestro_presupuesto where anio = ".$_SESSION["anio_fiscal"]." 
														and idcategoria_programatica = ".$bus_orden["idcategoria_programatica"]." 
														and idclasificador_presupuestario = ".$id_clasificador_impuestos."
														and idfuente_financiamiento = '".$fuente_financiamiento."'
														and idtipo_presupuesto = '".$tipo_presupuesto."'
														and idordinal = '".$bus_ordinal_impuesto["idordinal"]."'")or die("ES AQUI EL ERRORRRRRRRRRRRRRRRR". mysql_error());
	
			
			$bus_maestro = mysql_fetch_array($sql_maestro_impuestos);
			$num_maestro_impuesto = mysql_num_rows($sql_maestro_impuestos);
			if($num_maestro_impuesto > 0){ // valido que exista una partida para el impuesto
				// obtengo el disponible de la partida para compararlo con el total de impuesto y saber si existe disponibilidad
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				$sql_total_impuestos = mysql_query("select * from relacion_impuestos_requisiciones where 
																		idrequisicion = ".$id_requisicion." 
																	and idimpuestos = ".$id_impuestos."");
				$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
				$total_impuesto_imputable = $bus_total_impuestos["total"];	
						
				if($total_impuesto_imputable > $disponible){
					$estado_partida="sobregiro"; // si no tiene disponibilidad cambio el estado para colorearlo de AMARILLO
					$sql_update_estado_relacion_impuestos = mysql_query("update relacion_impuestos_requisiciones set estado = 'sin disponibilidad' 
																			where idrequisicion = ".$id_requisicion."");
				}else{
					$estado_partida="disponible"; // si existe disponibilidad coloco el estado como DISPONIBLE para que aparezca en color normal
					$sql_update_estado_relacion_impuestos = mysql_query("update relacion_impuestos_requisiciones set estado = 'disponible' 
																			where idrequisicion = ".$id_requisicion."");
				}
				// BUSCO LA PARTIDA DEL IMPUESTO EN LAS PARTIDAS DE LA ORDEN DE COMPRA 
				$sql_partidas_orden_compra=mysql_query("select * from partidas_requisiciones where idrequisicion=".$id_requisicion." 
																		and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."") 
																	or die("AQUI ESTA EL ERROR:".mysql_error());
				$num=mysql_num_rows($sql_partidas_orden_compra);
				if ($num==0){ // SI NO EXISTE LA PARTIDA EN LA TABLA DE PARTIDAS DE LA ORDEN DE COMPRA LA AGREGO
					$ingresar_partida=mysql_query("insert into partidas_requisiciones (idrequisicion, 
																								idmaestro_presupuesto,
																								monto,
																								monto_original,
																								estado,
																								status,
																								usuario,
																								fechayhora) 
																							values (".$id_requisicion.",
																									".$bus_maestro["idRegistro"].",
																									".$total_impuesto_individual.",
																									".$total_impuesto_individual.",
																									'".$estado_partida."',
																									'a',
																									'".$login."',
																									'".date("Y-m-d H:i:s")."')")
																								or die("4444444 ".mysql_error());
				}else{ // SI YA EXISTE LA PARTIDA, LE ACTUALIZO EL ESTADO Y EL TOTAL DE IMPUESTO IMPUTADO A ESA PARTIDA
					$actualiza_partida=mysql_query("update partidas_requisiciones set 
																		monto=monto+".$total_impuesto_individual.",
																		monto_original=monto_original+".$total_impuesto_individual.",
																		estado='".$estado_partida."' 
																		where idrequisicion=".$id_requisicion." 
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")
																		or die ("555 ".mysql_error());
				}	
			}else{ // SI NO EXISTE PARTIDA PARA EL IMPUESTO LO COLOCA COMO RECHAZADO PARA COLOREARLO DE ROJO
				$sql_update_estado_relacion_impuestos = mysql_query("update relacion_impuestos_requisiciones set estado = 'rechazado' 
																	where idrequisicion = ".$id_requisicion."");
			} // CIERRO LA VALIDACION PARA SABER SI TIENE PARTIDA EN EL MAESTRO DE PRESUPUESTO
			
			
		} // CIERRO LA VALIDACION DE SI EL IMPUESTO TIENE PARTIDA PROPIA
		
		
		$sql_articulos = mysql_query("select * from articulos_servicios where idarticulos_servicios = ".$id_material."");
		$bus_articulos = mysql_fetch_array($sql_articulos);
		// 	se realiza la consulta en la tabla maestro para verificar si hay partidas para este articulo, de lo contrario el articulo se coloca en un estado de rechazado para que
		//	en la tabla aparesca la fila en rojo y muestre al usuario que no hay partidas para ese articulo
		
		
		
		// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************

			
			$sql_maestro = colsultarMaestro($_SESSION["anio_fiscal"], $bus_orden["idcategoria_programatica"], $id_clasificador_presupuestario, $fuente_financiamiento, $tipo_presupuesto, $idordinal, $cofinanciamiento);	
		//echo $sql_maestro;

		$num_maestro = mysql_num_rows($sql_maestro);
			
			if($num_maestro == 0){ // VALIDO QUE EXISTA UNA PARTIDA EN EL MAESTRO DE PRESUPUESTO PARA ESE ARTICULO
				$estado = "rechazado";  // si no tiene partida en el maestro de presupuesto le coloca RECHAZADO para pintarlo de color ROJO
				
			}else{
				while($bus_maestro = mysql_fetch_array($sql_maestro)){
				
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				
				// de lo contrario verifica si en la partida que esta asignada este articulo hay disponibilidad de dinero para comprar la cantidad que se esta pidiendo, si para 
				// esta partida no hay disponibilidad se coloca el estado en SIN DISPONIBILIDAD para que al mostrar el articulo en color en AMARILLO para indicar que no 
				// hay presupuesto para este articulo
				
				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_requisicion.exento) as exentos from articulos_requisicion, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_requisicion.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_requisicion.idrequisicion = ".$id_requisicion."");
				// SUMO EL TOTAL DE TODOS LOS ARTICULOS QUE ESTAN IMPUTANDO ESA PARTIDA PARA COMPARARLO CON EL DISPONIBLE EN EL MAESTRO DE PRESUPUESTO
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"]; 
				
				if ($destino_partida == 0 and $contribuyente_ordinario=="si")	{// valido que el impuesto se sume a la partida o si tiene partida propia
					$sql_impuesto_imputable = mysql_query("select SUM(impuesto) as totales_impuestos from articulos_requisicion, 
																				articulos_servicios 
																			where
										articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
										and articulos_requisicion.idarticulos_servicios = articulos_servicios.idarticulos_servicios
										and articulos_requisicion.idrequisicion = ".$id_requisicion." 
										and idpartida_impuesto = 0");
					$bus_impuesto_imputable = mysql_fetch_array($sql_imputable);
					$total_impuesto_imputable = $bus_impuesto_imputable["totales_impuestos"];
					$total_imputable = $total_imputable + $total_impuesto_imputable;
					$total_articulo_individual = $total_articulo_individual + $total_impuesto_individual;
				}
				
				
				
				
				if($cofinanciamiento == "si"){
					$sql_cofinanciamiento = mysql_query("select * from 
												fuentes_cofinanciamiento 
													where 
												idcofinanciamiento = '".$fuente_financiamiento."' 
												and  idfuente_financiamiento = '".$bus_maestro["idfuente_financiamiento"]."'");
					$bus_cofinanciamiento = mysql_fetch_array($sql_cofinanciamiento);
					$porcent = "0.".$bus_cofinanciamiento["porcentaje"];
					$total_imputable_nuevo = ($total_imputable * $porcent);
				}else{
					$total_imputable_nuevo = $total_imputable;
				}
				
				
				
				if($total_imputable_nuevo > $disponible){ // si el total a imputar es mayor al disponible en la partida
					$estado = "sin disponibilidad";
					$estado_partida = "sobregiro";
				}else{
					//si nada de esto sucede se coloca el estado en aprobado y el material se muestra normalmente
					$estado = "aprobado";
					$estado_partida = "disponible";
				}
				/*echo "select * from partidas_requisiciones where idrequisicion=".$id_requisicion." 
																		and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."";*/
					
				$sql_partidas_orden_compra=mysql_query("select * from partidas_requisiciones where idrequisicion=".$id_requisicion." 
																		and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."") 
																	or die("66666 ".mysql_error());
				$num=mysql_num_rows($sql_partidas_orden_compra);
				
				
				
				
				
				
				
				
				
				if ($num==0){ // SI NO EXISTE LA PARTIDA LA INGRESO
					$ingresar_partida=mysql_query("insert into partidas_requisiciones (idrequisicion, 
																								idmaestro_presupuesto,
																								monto,
																								monto_original,
																								estado,
																								status,
																								usuario,
																								fechayhora) 
																							values (".$id_requisicion.",
																									".$bus_maestro["idRegistro"].",
																									".$total_imputable_nuevo.",
																									".$total_imputable_nuevo.",
																									'".$estado_partida."',
																									'a',
																									'".$login."',
																									'".date("Y-m-d H:i:s")."')")
																								or die("ERROR GUARDANDO PARTIDAS:". mysql_error());
				}else{ // DE LO CONTRARIO LA ACTUALIZO
					$actualiza_partida=mysql_query("update partidas_requisiciones set 
																		monto = monto + ".$total_imputable_nuevo.",
																		monto_original = monto_original + ".$total_imputable_nuevo.",
																		estado='".$estado_partida."' 
																		where idrequisicion=".$id_requisicion." 
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")
																		or die ($total_item."ERROR MODIFICANDO PARTIDAS: ".mysql_error());
				}														
	
			}
			}
			// actualizo el estado del material ingresado				
			$sql_update_articulos_compras = mysql_query("update articulos_requisicion set estado = '".$estado."' 
																where idarticulos_requisicion = ".$id_ultimo_generado."")or die(mysql_error());


		
		
		if($sql){
			registra_transaccion("Ingresar Material Individual en Orden de Compra (".$id_ultimo_generado.")",$login,$fh,$pc,'requisicions');
			echo "exito";
		}else{
			echo "fallo";
		}
		
		
}else{
echo "existe";
}

}



if($ejecutar == "consultarTipoCarga"){
$sql_orden_compra = mysql_query("select * from requisicion where idrequisicion = '".$id_requisicion."'");
$bus_orden_compra = mysql_fetch_array($sql_orden_compra);
echo $bus_orden_compra["tipo_carga_orden"];
}


if($ejecutar == "actualizarTipoCargaOrden"){
	$sql_actualizar_ordne = mysql_query("update requisicion set tipo_carga_orden = '".$tipo_carga_orden."' where idrequisicion = '".$id_requisicion."'")or die(mysql_error());
}
//if($ejecutar == "")

?>