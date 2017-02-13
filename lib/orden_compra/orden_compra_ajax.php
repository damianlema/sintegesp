<?
session_start();
include("../../conf/conex.php");
include("../../funciones/funciones.php");
Conectarse();
extract($_POST);



function consultarMaestro($anio, $idcategoria_programatica, $idclasificador_presupuestario, $idfuente_financiamiento, $idtipo_presupuesto, $idordinal, $cofinanciamiento){
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
		//echo "año ".$anio." categ ".$idcategoria_programatica." clasi ".$idclasificador_presupuestario." ff ".$idfuente_financiamiento." tipo ".$idtipo_presupuesto." ordi ".$idordinal;
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

	//$anio." , ".$idcategoria_programatica." , ".$idclasificador_presupuestario." , ".$idfuente_financiamiento." , ".$idtipo_presupuesto." , ".$idordinal." , ".$cofinanciamiento;

	return $sql_maestro;
}







//*******************************************************************************************************************************************
//********************************************* CONSULTAR LISTA DE SOLICITUDES FINALIZADAS POR PROVEEDOR ************************************
//*******************************************************************************************************************************************

if($ejecutar == "consultarSolicitudesProveedor"){
	$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
	$bus_orden = mysql_fetch_array($sql_orden);
	if($bus_orden["estado"] == "procesado" || $bus_orden["estado"] == "anulado"){
		echo "La orden se encuentra en estado ".$bus_orden["estado"].", Por lo tanto no se puiede modificar los Documentos seleccionadas";
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
					<a href="#" onclick="document.getElementById('divDetalleJustificacion<?=$i?>').style.display='block'"><img src="imagenes/ver.png" border="0" title="Ver Justificacion"></a>
					<div style="position:absolute; display:none; background-color: #FFFFCC; border:#000000 1px solid" id="divDetalleJustificacion<?=$i?>">
						<div align="right">
							<a href="#" onclick="document.getElementById('divDetalleJustificacion<?=$i?>').style.display='none'"><strong>X</strong></a>
						</div>
					<?=$bus2["justificacion"]?>
					</div>
					</td>
					<td align="center" class="Browse">
					<?
					$sql_relacion = mysql_query("select * from relacion_compra_solicitud_cotizacion where idsolicitud_cotizacion = ".$bus["idsolicitud_cotizacion"]." and idorden_compra = ".$id_orden_compra."");
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
															onclick="seleccionDeseleccionListaSolicitud(<?=$num?>), agregarMateriales(<?=$bus["idsolicitud_cotizacion"]?>, document.getElementById('id_orden_compra').value, document.getElementById('id_categoria_programatica').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value,document.getElementById('contribuyente_ordinario').value)" 
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





if($ejecutar == "consultarRequisicion"){
	//echo "ENTRO ACA";
	$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
	$bus_orden = mysql_fetch_array($sql_orden);
	if($bus_orden["estado"] == "procesado" || $bus_orden["estado"] == "anulado"){
		echo "La orden se encuentra en estado ".$bus_orden["estado"].", Por lo tanto no se puede modificar las requisiciones seleccionadas";
	}else{
	//echo "select * from requisicion where idbeneficiarios = '".$id_beneficiarios."' and estado = 'procesado' and idcategoria_programatica = '".$id_categoria_programatica."'";
		$sql = mysql_query("select * from requisicion where idbeneficiarios = '".$id_beneficiarios."' and estado = 'procesado' and idcategoria_programatica = '".$id_categoria_programatica."'");
		$num = mysql_num_rows($sql);
		if($num > 0){
			?>
<form name="formSolicitudesFinalizadas" id="formSolicitudesFinalizadas">
  <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
	<thead>
	  <tr>
		<td width="65%" class="Browse">Nro. Requisicion</td>
		<td width="18%" class="Browse">Just.</td>
		<td width="17%" class="Browse">Selec.</td>
	  </tr>
	<thead>
			<?
			$i = 0;
			while($bus = mysql_fetch_array($sql)){
				$sql2 = mysql_query("select * from requisicion where idrequisicion = ".$bus["idrequisicion"]." and estado = 'procesado'");
				$bus2 = mysql_fetch_array($sql2);
				?>
				<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
					<td class="Browse"><?=$bus2["numero_requisicion"]?></td>
					<td class="Browse" align="center">
					<a href="#" onclick="document.getElementById('divDetalleJustificacion<?=$i?>').style.display='block'"><img src="imagenes/ver.png" border="0" title="Ver Justificacion"></a>
					<div style="position:absolute; display:none; background-color: #FFFFCC; border:#000000 1px solid" id="divDetalleJustificacion<?=$i?>">
						<div align="right">
							<a href="#" onclick="document.getElementById('divDetalleJustificacion<?=$i?>').style.display='none'"><strong>X</strong></a>
						</div>
					<?=$bus2["justificacion"]?>
					</div>
					</td>
					<td align="center" class="Browse">
					<?
					$sql_relacion = mysql_query("select * from relacion_compra_requisicion where idrequisicion = ".$bus["idrequisicion"]." and idorden_compra = ".$id_orden_compra."");
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
                        onclick="seleccionDeseleccionListaSolicitud(<?=$num?>), agregarRequisiciones(<?=$bus["idrequisicion"]?>, document.getElementById('id_orden_compra').value, document.getElementById('id_categoria_programatica').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value,document.getElementById('contribuyente_ordinario').value)" 
                        name="solicitudes_ganadas<?=$i?>" 
                        id="solicitudes_ganadas<?=$i?>" 
                        value="<?=$bus["idrequisicion"]?>">
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
//********************************************* INGRESAR MATERIALES A LA ORDEN DE COMPRA ****************************************************
//*******************************************************************************************************************************************

if($ejecutar == "materiales"){
//si la accion a ejecutar es ditinta a consultar, entonces procede a agregar o eliminar de acuerdo a la opcion que venga
	if($accion != "consultar"){
	// si la accion que viene es ingresar desde cotizacion se procede a consultar los datos de la solicitud para ingresar los materiales asociados
	
		
		
		
		
		
		if($accion == "agregarRequisiciones"){
		$sql_requisicion = mysql_query("select * from requisicion where idrequisicion = '".$idrequisicion."'");
		$bus_requisicion = mysql_fetch_array($sql_requisicion);
		
																				
		
		$sql_relacion_compra_requisicion = mysql_query("select * from relacion_compra_requisicion where 
																				idorden_compra = '".$id_orden_compra."' 
																				and idrequisicion = '".$idrequisicion."'");
		$num_relacion_compra_requisicion = mysql_num_rows($sql_relacion_compra_requisicion);
		if($num_relacion_compra_requisicion == 0){
			//echo "AGREGAR............";
			
			$sql_actualizar_orden_compra = mysql_query("update orden_compra_servicio set
															exento = exento + '".$bus_requisicion["exento"]."',
															exento_original = exento_original + '".$bus_requisicion["exento_original"]."',
															sub_total = sub_total + '".$bus_requisicion["sub_total"]."',
															sub_total_original = sub_total_original + '".$bus_requisicion["sub_total_original"]."',
															impuesto = impuesto + '".$bus_requisicion["impuesto"]."',
															descuento = descuento + '".$bus_requisicion["descuento"]."',
															total = total + '".$bus_requisicion["total"]."'
															where idorden_compra_servicio = '".$id_orden_compra."'")or die(mysql_error());

			//VALIDAR SI LA REQUISICION ESTA SELECCIONADA EN OTRA ORDEN EN ESTADO DE ELABORACION PARA ELIMINARLA Y DEJARLA SELECCIONADA EN LA ORDEN NUEVA
			$sql_validar_requisicion = mysql_query("select * from relacion_compra_requisicion where idrequisicion ='".$idrequisicion."'");
			if (mysql_num_rows($sql_validar_requisicion)>0){
				
				$bus_validar_requisicion = mysql_fetch_array($sql_validar_requisicion);	
				$sql_insert_relacion = mysql_query("delete from relacion_compra_requisicion where idrequisicion ='".$idrequisicion."'");
				$sql_articulos_requisicion = mysql_query("select * from articulos_requisicion where idrequisicion = '".$idrequisicion."'");
				while($bus_articulos_requisicion = mysql_fetch_array($sql_articulos_requisicion)){
					$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_articulos_requisicion["idarticulos_servicios"]."'");
					$sql_actualizar_articulo = mysql_query("update articulos_compra_servicio set 
														cantidad = cantidad-".$bus_articulos_requisicion["cantidad"]."
														where idorden_compra_servicio = '".$bus_validar_requisicion["idorden_compra_servicio"]."' 
														and idarticulos_servicios = '".$bus_articulos_requisicion["idarticulos_servicios"]."'")
														or die("ERROR UPDATE".mysql_error());
					
					$sql_validar_articulo = mysql_query("select * from articulos_compra_servicio
																where idorden_compra_servicio = '".$bus_validar_requisicion["idorden_compra_servicio"]."' 
																and idarticulos_servicios = '".$bus_articulos_requisicion["idarticulos_servicios"]."'")
																or die("ERROR SELECT ".mysql_error());
					$bus_validar_articulo = mysql_fetch_array($sql_validar_articulo);
					if ($bus_validar_articulo["cantidad"] <= 0){
						$sql_eliminar_articulo = mysql_query("delete from articulos_compra_servicio
																where idorden_compra_servicio = '".$bus_validar_requisicion["idorden_compra_servicio"]."' 
																and idarticulos_servicios = '".$bus_articulos_requisicion["idarticulos_servicios"]."'")
																or die("ERROR BORRANDO ".mysql_error());
					}
					
				}
				$sql_partidas_requisiciones = mysql_query("select * from partidas_requisiciones where idrequisicion = '".$idrequisicion."'");
				while($bus_partidas_requisiciones = mysql_fetch_array($sql_partidas_requisiciones)){
					$sql_actualizar_partida = mysql_query("update partidas_orden_compra_servicio set
														monto = monto - ".$bus_partidas_requisiciones["monto"].",
														monto_original = monto_original + ".$bus_partidas_requisiciones["monto"]."
														where idorden_compra_servicio = '".$bus_validar_requisicion["idorden_compra_servicio"]."' 
														and idmaestro_presupuesto = '".$bus_partidas_requisiciones["idmaestro_presupuesto"]."'");
					
					$sql_validar_partidas_compra = mysql_query("select * from partidas_orden_compra_servicio 
														where idorden_compra_servicio = '".$bus_validar_requisicion["idorden_compra_servicio"]."' 
														and idmaestro_presupuesto = '".$bus_partidas_requisiciones["idmaestro_presupuesto"]."'")
														or die("ERROR SELECT2 ".mysql_error());
					$bus_validar_partidas_compra = mysql_fetch_array($sql_validar_partidas_compra);
					if ($bus_validar_partidas_compra["monto"] <= 0){
						$sql_eliminar_partida = mysql_query("delete from partidas_orden_compra_servicio
														where idorden_compra_servicio = '".$bus_validar_requisicion["idorden_compra_servicio"]."' 
														and idmaestro_presupuesto = '".$bus_partidas_requisiciones["idmaestro_presupuesto"]."'")
														or die("ERROR SELECT2 ".mysql_error());
					}
				}
			}
			
			$sql_insert_relacion = mysql_query("insert into relacion_compra_requisicion (idrequisicion, 
																						idorden_compra)
																							VALUES
																						('".$idrequisicion."',
																						'".$id_orden_compra."')");
			
			
			$sql_articulos_requisicion = mysql_query("select * from articulos_requisicion where idrequisicion = '".$idrequisicion."'");
			while($bus_articulos_requisicion = mysql_fetch_array($sql_articulos_requisicion)){
				$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_articulos_requisicion["idarticulos_servicios"]."'");
				$bus_ordinal = mysql_fetch_array($sql_ordinal);
				$idordinal = $bus_ordinal["idordinal"];
				
				
					
				$sql_articulos_compra = mysql_query("select * from articulos_compra_servicio where idarticulos_servicios = '".$bus_articulos_requisicion["idarticulos_servicios"]."' and idorden_compra_servicio = '".$id_orden_compra."'");
				$num_articulos_compra = mysql_num_rows($sql_articulos_compra);
				if($num_articulos_compra == 0){
					
					$sql_insertar_articulo = mysql_query("insert into articulos_compra_servicio (idorden_compra_servicio,
																				idarticulos_servicios,
																				cantidad,
																				precio_unitario,
																				porcentaje_impuesto,
																				impuesto,
																				total,
																				exento,
																				idsolicitud_cotizacion,
																				estado,
																				status,
																				usuario,
																				fechayhora,
																				idpartida_impuesto)
																					VALUES
																				('".$id_orden_compra."',
																				'".$bus_articulos_requisicion["idarticulos_servicios"]."',
																				'".$bus_articulos_requisicion["cantidad"]."',
																				'".$bus_articulos_requisicion["precio_unitario"]."',
																				'".$bus_articulos_requisicion["porcentaje_impuesto"]."',
																				'".$bus_articulos_requisicion["impuesto"]."',
																				'".$bus_articulos_requisicion["total"]."',
																				'".$bus_articulos_requisicion["exento"]."',
																				'".$bus_articulos_requisicion["idrequisicion"]."',
																				'aprobado',
																				'a',
																				'".$login."',
																				'".$fh."',
																				'".$bus_articulos_requisicion["idpartida_impuesto"]."')")or die(mysql_error());
																				
				}else{
					$sql_actualizar_articulo = mysql_query("update articulos_compra_servicio set 
														cantidad=cantidad+".$bus_articulos_requisicion["cantidad"].",
														impuesto = impuesto + ".$bus_articulos_requisicion["impuesto"].",
														total = total + ".$bus_articulos_requisicion["total"].",
														exento = exento + ".$bus_articulos_requisicion["exento"]."
														where idorden_compra_servicio = '".$id_orden_compra."' 
														and idarticulos_servicios = '".$bus_articulos_requisicion["idarticulos_servicios"]."'");
				}
			}// CIERRE DEL WHILE DE LOS ARTICULOS
			
			
			
			$sql_partidas_requisiciones = mysql_query("select * from partidas_requisiciones where idrequisicion = '".$idrequisicion."'");
			while($bus_partidas_requisiciones = mysql_fetch_array($sql_partidas_requisiciones)){
				$sql_partidas_compra = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."' and idmaestro_presupuesto = '".$bus_partidas_requisiciones["idmaestro_presupuesto"]."'");
				$num_partidas_compra = mysql_num_rows($sql_partidas_compra);
				if($num_partidas_compra == 0){
					$sql_insertar_partida = mysql_query("insert into partidas_orden_compra_servicio(
																		idorden_compra_servicio,
																		idmaestro_presupuesto,
																		monto,
																		monto_original,
																		estado,
																		status,
																		usuario,
																		fechayhora)VALUES(
																		'".$id_orden_compra."',
																		'".$bus_partidas_requisiciones["idmaestro_presupuesto"]."',
																		'".$bus_partidas_requisiciones["monto"]."',
																		'".$bus_partidas_requisiciones["monto"]."',
																		'".$bus_partidas_requisiciones["estado"]."',
																		'a',
																		'".$login."',
																		'".fh."')");
				}else{
					$sql_actualizar_partida = mysql_query("update partidas_orden_compra_servicio set
														monto = monto + ".$bus_partidas_requisiciones["monto"].",
														monto_original = monto_original + ".$bus_partidas_requisiciones["monto"]."
														where idorden_compra_servicio = '".$id_orden_compra."' 
														and idmaestro_presupuesto = '".$bus_partidas_requisiciones["idmaestro_presupuesto"]."'");
				}
			}// CIERRE DEL WHILE DE PARTIDAS			
			
			
			
			$sql_relacion_impuestos_requisicion = mysql_query("select * from relacion_impuestos_requisiciones where idrequisicion = '".$idrequisicion."'");
			
			while($bus_relacion_impuestos_requisicion = mysql_fetch_array($sql_relacion_impuestos_requisicion)){
				$sql_relacion_impuestos_compras = mysql_query("select * from relacion_impuestos_ordenes_compras 
																			where idorden_compra_servicio = '".$id_orden_compra."' 
																			and idimpuestos = '".$bus_relacion_impuestos_requisicion["idimpuestos"]."'");
				$num_relacion_impuestos_compras = mysql_num_rows($sql_relacion_impuestos_compras);
				//echo "base impuesto ".$bus_relacion_impuestos_requisicion["base_calculo"];
				
				if($num_relacion_impuestos_compras == 0){
					$sql_insert_impuestos = mysql_query("insert into relacion_impuestos_ordenes_compras(
																					idorden_compra_servicio,
																					idimpuestos,
																					porcentaje,
																					base_calculo,
																					base_calculo_original,
																					total,
																					estado)VALUES(
																					'".$id_orden_compra."',
																					'".$bus_relacion_impuestos_requisicion["idimpuestos"]."',
																					'".$bus_relacion_impuestos_requisicion["porcentaje"]."',
																					'".$bus_relacion_impuestos_requisicion["base_calculo"]."',
																					'".$bus_relacion_impuestos_requisicion["base_calculo_original"]."',
																					'".$bus_relacion_impuestos_requisicion["total"]."',
																					'".$bus_relacion_impuestos_requisicion["estado"]."')");
				}else{
					$sql_update_impuestos = mysql_query("update relacion_impuestos_ordenes_compras set 
													base_calculo = base_calculo + '".$bus_relacion_impuestos_requisicion["base_calculo"]."',
													base_calculo_original = base_calculo_original + '".$bus_relacion_impuestos_requisicion["base_calculo_original"]."',
													total = total + ".$bus_relacion_impuestos_requisicion["total"]."
													where idorden_compra_servicio = '".$id_orden_compra."'
													and idimpuestos = '".$bus_relacion_impuestos_requisicion["idimpuestos"]."'")or die("aqui error ".mysql_error());
				}
			}// CIERRE DEL WHILE DE IMPUESTOS
				
		//}// CIERRA EL CONDICIONES 
		
		}else{// SI YA EXISTE LA RELACION ENTONCES ES PORQUE SE ESTA DESELECCIONANDO Y SE VA A ELIMINAR
		//echo "ENTRA ACA";
			
			$sql_actualizar_orden_compra = mysql_query("update orden_compra_servicio set
													exento = exento - '".$bus_requisicion["exento"]."',
													exento_original = exento_original - '".$bus_requisicion["exento_original"]."',
													sub_total = sub_total - '".$bus_requisicion["sub_total"]."',
													sub_total_original = sub_total_original - '".$bus_requisicion["sub_total_original"]."',
													impuesto = impuesto - '".$bus_requisicion["impuesto"]."',
													descuento = descuento - '".$bus_requisicion["descuento"]."',
													total = total - '".$bus_requisicion["total"]."'
													where idorden_compra_servicio = '".$id_orden_compra."'")or die(mysql_error());
			
			
			
			
			
			$sql_eliminar_relacion = mysql_query("delete from relacion_compra_requisicion where idorden_compra = '".$id_orden_compra."'
													and idrequisicion = '".$idrequisicion."'");
		
			$sql_select_articulos_requisicion = mysql_query("select * from articulos_requisicion where idrequisicion = '".$idrequisicion."'");
			while($bus_select_articulos_requisicion = mysql_fetch_array($sql_select_articulos_requisicion)){
				$sql_articulos_orden_compra = mysql_query("update articulos_compra_servicio set 
												cantidad = cantidad - '".$bus_select_articulos_requisicion["cantidad"]."',
												impuesto = impuesto - ".$bus_select_articulos_requisicion["impuesto"].",
												total = total - ".$bus_select_articulos_requisicion["total"].",
												exento = exento - ".$bus_select_articulos_requisicion["exento"]."
												where idorden_compra_servicio = '".$id_orden_compra."' 
												and idarticulos_servicios = '".$bus_select_articulos_requisicion["idarticulos_servicios"]."'")or die("ERROR".mysql_error());
				$sql_select_articulo =  mysql_query("select * from articulos_compra_servicio 
												where idorden_compra_servicio = '".$id_orden_compra."' 
												and idarticulos_servicios = '".$bus_select_articulos_requisicion["idarticulos_servicios"]."'");
				$bus_select_articulo = mysql_fetch_array($sql_select_articulo);
				if($bus_select_articulo["cantidad"] == 0){
					$sql_eliminar = mysql_query("delete from articulos_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."' 
												and idarticulos_servicios = '".$bus_select_articulos_requisicion["idarticulos_servicios"]."'");
				}
			}
			
			$sql_partidas_requisicion = mysql_query("select * from partidas_requisiciones where idrequisicion = '".$idrequisicion."'")or die("ERRROR SELECT PARTIDAS". mysql_error());
			while($bus_partidas_requisicion = mysql_fetch_array($sql_partidas_requisicion)){
				$sql_partidas_orden_compra = mysql_query("update partidas_orden_compra_servicio set
														monto = monto - ".$bus_partidas_requisicion["monto"].",
														monto_original = monto_original - ".$bus_partidas_requisicion["monto"]."
														 where 
														idorden_compra_servicio = '".$id_orden_compra."' 
														and idmaestro_presupuesto = '".$bus_partidas_requisicion["idmaestro_presupuesto"]."'")or die("ERROR EN ACTUALIZAR PARTIDAS". mysql_error());
				$sql_select_partida = mysql_query("select * from partidas_orden_compra_servicio where
														idorden_compra_servicio = '".$id_orden_compra."'  
														and idmaestro_presupuesto = '".$bus_partidas_requisicion["idmaestro_presupuesto"]."'");
				$bus_select_partidas = mysql_fetch_array($sql_select_partida);
				if($bus_select_partidas["monto"] == 0){
					$sql_eliminar_partidas = mysql_query("delete from partidas_orden_compra_servicio where
														idorden_compra_servicio = '".$id_orden_compra."' 
														and idmaestro_presupuesto = '".$bus_partidas_requisicion["idmaestro_presupuesto"]."'")or die("ERROR AL ELIMINAR LAS PARTIDAS". mysql_error());
				}
			}
			
			
			$sql_relacion_impuestos = mysql_query("select * from relacion_impuestos_requisiciones where idrequisicion = '".$idrequisicion."'");
				while($bus_relacion_impuestos = mysql_fetch_array($sql_relacion_impuestos)){
					$sql_actualizar_impuestos = mysql_query("update relacion_impuestos_ordenes_compras set
														base_calculo = base_calculo - '".$bus_relacion_impuestos["base_calculo"]."',
														base_calculo_original = base_calculo_original - '".$bus_relacion_impuestos["base_calculo_original"]."',
														total = total - ".$bus_relacion_impuestos["total"]."
														where idorden_compra_servicio = '".$id_orden_compra."'
														and idimpuestos = '".$bus_relacion_impuestos["idimpuestos"]."'");
														
					$sql_select_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras
													where idorden_compra_servicio = '".$id_orden_compra."'
													and idimpuestos = '".$bus_relacion_impuestos["idimpuestos"]."'")or die(mysql_error());
					$bus_select_impuestos = mysql_fetch_array($sql_select_impuestos);
					if($bus_select_impuestos["total"] == 0){
						$sql_eliminar_impuestos = mysql_query("delete from relacion_impuestos_ordenes_compras
													where idorden_compra_servicio = '".$id_orden_compra."'
													and idimpuestos = '".$bus_relacion_impuestos["idimpuestos"]."'");
					}
				}
			
			
					
		}// CIERRE DEL ELSE DE SI ES ELIMINAR

	}	
		
		
		
		
		
		
		
		
		
		
		if($accion == "ingresarSolicitudCreada"){
			
			$sql = mysql_query("select * from relacion_compra_solicitud_cotizacion where idorden_compra = ".$id_orden_compra."
																				and idsolicitud_cotizacion = ".$id_solicitud."");
			$num = mysql_num_rows($sql);
			if($num == 0){
				//PROCEDE A AGREGAR LA SOLICITUD SELECCIONADA EN LA TABLA QUE LLEVA LA RELACION CON LA ORDEN DE COMPRA
				$sql3 = mysql_query("insert into relacion_compra_solicitud_cotizacion (idorden_compra, 
																				idsolicitud_cotizacion)values(
																				'".$id_orden_compra."',
																				'".$id_solicitud."')")or die(mysql_error());
			
				$sql = mysql_query("select * from articulos_solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."");
				$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
				$bus_orden = mysql_fetch_array($sql_orden);
				
				while($articulo_ingresar = mysql_fetch_array($sql)){
				
				$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$articulo_ingresar["idarticulos_servicios"]."'");
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
					$sql2 = mysql_query("insert into articulos_compra_servicio (idorden_compra_servicio,
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
																								".$id_orden_compra.",
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
					
					// ACTUALIZO LOS TOTALES EN LA TABLA ORDEN_COMPRA_SERVICIO
					$sql_actualiza_totales = mysql_query("update orden_compra_servicio set 
					sub_total = sub_total + '".$articulo_ingresar["total"]."',
					sub_total_original = sub_total_original + '".$articulo_ingresar["total"]."',
					impuesto = impuesto + '".$articulo_ingresar["impuesto"]."',
					exento = exento + '".$articulo_ingresar["exento"]."',
					exento_original = exento_original + '".$articulo_ingresar["exento"]."',
					total = total + '".$articulo_ingresar["impuesto"]."' + '".$articulo_ingresar["total"]."' + '".$articulo_ingresar["exento"]."'
																					where idorden_compra_servicio=".$id_orden_compra." ")or die (mysql_error());
					
				if ($contribuyente_ordinario=="si"){
					if ($destino_partida<>0){ // SI EL IMPUESTO TIENE PARTIDA PROPIA
						$sql_existe_partida=mysql_query("select * from relacion_impuestos_ordenes_compras 
																				where idorden_compra_servicio=".$id_orden_compra." 
																					and idimpuestos=".$id_impuestos."");
						$num=mysql_num_rows($sql_existe_partida); // VERIFICO SI ESE IMPUESTO YA FUE INGRESADO A LA TABLA DE RELACION DE IMPUESTOS CON ORDEN DE COMPRA
						if ($num==0) {
							$sql2 = mysql_query("insert into relacion_impuestos_ordenes_compras (idorden_compra_servicio,
																								idimpuestos,
																								porcentaje,
																								base_calculo,
																								base_calculo_original,
																								total)
																						value(
																								".$id_orden_compra.",
																								".$id_impuestos.",
																								".$porcentaje_impuesto.",
																								'".$articulo_ingresar["total"]."',
																								'".$articulo_ingresar["total"]."',
																								'".$articulo_ingresar["impuesto"]."'
																								)")or die(mysql_error());
						}else {
							// SI YA EXISTE EN LA TABLA LE SUMO EL IMPUESTO DEL NUEVO ARTICULO AL TOTAL
							$sql2=mysql_query("update relacion_impuestos_ordenes_compras set 
														base_calculo = base_calculo + '".$articulo_ingresar["total"]."',
														base_calculo_original = base_calculo_original + '".$articulo_ingresar["total"]."',
														total = total +".$articulo_ingresar["impuesto"]."")	or die(mysql_error());
						}
						
						// VALIDO LA PARTIDA DEL IMPUESTO EXISTA EN EL MAESTRO DE PRESUPUESTO 
						
						$sql_ordinal_impuesto= mysql_query("select * from ordinal where codigo='0000'");
						$bus_ordinal_impuesto= mysql_fetch_array($sql_ordinal_impuesto);
												// consulta maestro con el clasificador de impuesto
						$sql_maestro_impuestos = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
																			and idcategoria_programatica = ".$bus_orden["idcategoria_programatica"]." 
																			and idclasificador_presupuestario = ".$id_clasificador_impuestos."
																			and idfuente_financiamiento = '".$fuente_financiamiento."'
																			and idtipo_presupuesto = '".$tipo_presupuesto."'
																			and idordinal = '".$bus_ordinal_impuesto["idordinal"]."'");
					

						$bus_maestro = mysql_fetch_array($sql_maestro_impuestos);
						$num_maestro_impuesto = mysql_num_rows($sql_maestro_impuestos);
						if($num_maestro_impuesto > 0){ // valido que exista una partida para el impuesto
							// obtengo el disponible de la partida para compararlo con el total de impuesto y saber si existe disponibilidad
							$disponible= consultarDisponibilidad($bus_maestro["idRegistro"]);
							//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
							$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where 
																					idorden_compra_servicio = ".$id_orden_compra." 
																				and idimpuestos = ".$id_impuestos."");
							$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
							$total_impuesto_imputable = $bus_total_impuestos["total"];	
									
							if($total_impuesto_imputable > $disponible){
								$estado_partida="sobregiro"; // si no tiene disponibilidad cambio el estado para colorearlo de AMARILLO
								$sql_update_estado_relacion_impuestos = mysql_query("update relacion_impuestos_ordenes_compras 
																					set estado = 'sin disponibilidad' 
																					where idorden_compra_servicio = ".$id_orden_compra."");
							}else{
								$estado_partida="disponible"; // si existe disponibilidad coloco el estado como DISPONIBLE para que aparezca en color normal
								$sql_update_estado_relacion_impuestos = mysql_query("update relacion_impuestos_ordenes_compras 
																						set estado = 'disponible' 
																						where idorden_compra_servicio = ".$id_orden_compra."");
							}
							// BUSCO LA PARTIDA DEL IMPUESTO EN LAS PARTIDAS DE LA ORDEN DE COMPRA 
							$sql_partidas_orden_compra=mysql_query("select * from partidas_orden_compra_servicio 
																			where idorden_compra_servicio=".$id_orden_compra." 
																			and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'") 
																			or die(mysql_error());
							$num=mysql_num_rows($sql_partidas_orden_compra);
							if ($num==0){ // SI NO EXISTE LA PARTIDA EN LA TABLA DE PARTIDAS DE LA ORDEN DE COMPRA LA AGREGO
								$ingresar_partida=mysql_query("insert into partidas_orden_compra_servicio (idorden_compra_servicio, 
																								idmaestro_presupuesto,
																								monto,
																								monto_original,
																								estado,
																								status,
																								usuario,
																								fechayhora) 
																							values (".$id_orden_compra.",
																									'".$bus_maestro["idRegistro"]."',
																									".$total_impuesto_individual.",
																									".$total_impuesto_individual.",
																									'".$estado_partida."',
																									'a',
																									'".$login."',
																									'".date("Y-m-d H:i:s")."')")
																								or die(mysql_error());
							}else{ // SI YA EXISTE LA PARTIDA, LE ACTUALIZO EL ESTADO Y EL TOTAL DE IMPUESTO IMPUTADO A ESA PARTIDA
								$actualiza_partida=mysql_query("update partidas_orden_compra_servicio 
																	set monto = monto + ".$total_impuesto_individual.",	
																	monto_original = monto_original + ".$total_impuesto_individual.",	
																	estado='".$estado_partida."' 
																	where idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")
																	or die (mysql_error());
							}	
						}else{ // SI NO EXISTE PARTIDA PARA EL IMPUESTO LO COLOCA COMO RECHAZADO PARA COLOREARLO DE ROJO
							$sql_update_estado_relacion_impuestos = mysql_query("update relacion_impuestos_ordenes_compras 
																				set estado = 'rechazado' 
																				where idorden_compra_servicio = ".$id_orden_compra."");
						} // CIERRO LA VALIDACION PARA SABER SI TIENE PARTIDA EN EL MAESTRO DE PRESUPUESTO
						
					} // CIERRO LA VALIDACION DE SI EL IMPUESTO TIENE PARTIDA PROPIA
					
				} // CIERRO LA VALIDACION SI ES CONTRIBUYENTE ORDINARIO
				
					$sql_articulos = mysql_query("select * from articulos_servicios 
															where idarticulos_servicios = ".$articulo_ingresar["idarticulos_servicios"]."");
					$bus_articulos = mysql_fetch_array($sql_articulos);
					// 	se realiza la consulta en la tabla maestro para verificar si hay partidas para este articulo, de lo contrario el articulo se coloca en un estado de rechazado para que
					//	en la tabla aparesca la fila en rojo y muestre al usuario que no hay partidas para ese articulo
					
					
					
					
					
			// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************

			
			$sql_maestro = consultarMaestro($anio, $bus_orden["idcategoria_programatica"], $id_clasificador_presupuestario, $fuente_financiamiento, $tipo_presupuesto, $idordinal, $cofinanciamiento);	

					/*$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
																						and idcategoria_programatica = ".$bus_orden["idcategoria_programatica"]." 
																						and idclasificador_presupuestario = ".$id_clasificador_presupuestario.""
																					)or die($anio."ERROR SQL MAESTRO: ".mysql_error());*/

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

							$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_compra_servicio.exento) as exentos from articulos_compra_servicio, articulos_servicios 
													where
														articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
														and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
														and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra."");
							// SUMO EL TOTAL DE TODOS LOS ARTICULOS QUE ESTAN IMPUTANDO ESA PARTIDA PARA COMPARARLO CON EL DISPONIBLE EN EL MAESTRO DE PRESUPUESTO
							$bus_imputable = mysql_fetch_array($sql_imputable);
							$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"]; 
							
						
							if ($destino_partida == 0 and $contribuyente_ordinario == "si")	{// valido que el impuesto se sume a la partida o si tiene partida propia
								$sql_impuesto_imputable = mysql_query("select SUM(impuesto) as totales_impuestos 
									from articulos_compra_servicio, 
									articulos_servicios 
									where
									articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
									and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
									and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." 
									and idpartida_impuesto = 0");
								$bus_impuesto_imputable = mysql_fetch_array($sql_imputable);
								$total_impuesto_imputable = $bus_impuesto_imputable["totales_impuestos"];
								$total_imputable = $total_imputable + $total_impuesto_imputable;
								$total_articulo_individual = $total_articulo_individual + $total_impuesto_individual;
							}
						
							if($total_imputable > $disponible){ // si el total a imputar es mayor al disponible en la partida
								$estado = "sin disponibilidad";
								$estado_partida = "sobregiro";
							}else{
								//si nada de esto sucede se coloca el estado en aprobado y el material se muestra normalmente
								$estado = "aprobado";
								$estado_partida = "disponible";
							}
							
							/*$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = '".$anio."' 
									and idcategoria_programatica = ".$bus_orden["idcategoria_programatica"]." 
									and idclasificador_presupuestario = ".$id_clasificador_presupuestario." 
									and idfuente_financiamiento = '".$fuente_financiamiento."' 
									and idtipo_presupuesto = '".$tipo_presupuesto."' 
									and idordinal = '".$idordinal."'");*/
									
							//$bus_maestro = mysql_fetch_array($sql_maestro);
							
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
							
							
							
							$sql_partidas_orden_compra=mysql_query("select * from partidas_orden_compra_servicio where 
																			idorden_compra_servicio=".$id_orden_compra." 
																			and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'") 
																			or die(mysql_error());
							$num=mysql_num_rows($sql_partidas_orden_compra);
							
							if ($num==0){ // SI NO EXISTE LA PARTIDA LA INGRESO
								$ingresar_partida=mysql_query("insert into partidas_orden_compra_servicio (idorden_compra_servicio, 
																											idmaestro_presupuesto,
																											monto,
																											monto_original,
																											estado,
																											status,
																											usuario,
																											fechayhora) 
																										values (".$id_orden_compra.",
																							".$bus_maestro["idRegistro"].",
																							".$total_imputable_nuevo.",
																							".$total_imputable_nuevo.",
																							'".$estado_partida."',
																							'a',
																							'".$login."',
																							'".date("Y-m-d H:i:s")."')")
																							or die("ERROR GUARDANDO PARTIDAS:". mysql_error());
							}else{ // DE LO CONTRARIO LA ACTUALIZO
								$actualiza_partida=mysql_query("update partidas_orden_compra_servicio 
																	set monto = (monto +".$total_imputable_nuevo."),
																	monto_original = (monto_original +".$total_imputable_nuevo."),
																	estado='".$estado_partida."' 
																	where idorden_compra_servicio=".$id_orden_compra." 
																	and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")
																	or die ($total_item."ERROR MODIFICANDO PARTIDAS: ".mysql_error());
							}	
							
							
							}// FIN DEL WHILE QUE MUESTRA TODOS LAS FUENTES DE FINANCIAMIENTO													
				
						}
						
						// actualizo el estado del material ingresado				
						$sql_update_articulos_compras = mysql_query("update articulos_compra_servicio set estado = '".$estado."' 
																			where idarticulos_compra_servicio = ".$id_ultimo_generado."");
																			
						$sql_consulta_iguales = mysql_query("select * from articulos_compra_servicio where idarticulos_servicios = ".$articulo_ingresar["idarticulos_servicios"]." and idorden_compra_servicio = ".$id_orden_compra."");
						$num_consulta_iguales = mysql_num_rows($sql_consulta_iguales);
							if($num_consulta_iguales > 1){
								$sql_cambiar_iguales = mysql_query("update articulos_compra_servicio set duplicado = 1 where idarticulos_servicios = ".$articulo_ingresar["idarticulos_servicios"]." and idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
								$sql_cambiar_iguales = mysql_query("update orden_compra_servicio set duplicados = 1 where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());			
							}
					}   // FIN DEL WHILE DE INGRESAR LOS ARTICULOS DE LA SOLICITUD DE COTIZACION
				
				
				//AGREGO LOS DATOS DE CONTRATACION EN LA ORDEN DE COMPRA SERVICIO
				
				$sql_solicitud=mysql_query("select * from solicitud_cotizacion where idsolicitud_cotizacion = '".$id_solicitud."'");
				$field_solicitud=mysql_fetch_array($sql_solicitud);
				
				$sql_proveedor_solicitud = mysql_query("select * from proveedores_solicitud_cotizacion 
																			where idsolicitud_cotizacion = '".$id_solicitud."'
																			and ganador = 'y'");
				$field_proveedor = mysql_fetch_array($sql_proveedor_solicitud);
				
				$sql_modo_contratacion = mysql_query("select * from modalidad_contratacion 
																	where idmodalidad_contratacion = '".$field_proveedor["tipo_procedimiento"]."'");
				$field_modalidad = mysql_fetch_array($sql_modo_contratacion);
				$sql = mysql_query("update orden_compra_servicio set modo_comunicacion = '".$field_solicitud["modo_comunicacion"]."',
															tipo_actividad = '".$field_solicitud["tipo_actividad"]."',
															modo_contratacion = '".$field_modalidad["descripcion"]."'
															where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
				
				
			}else{  // SI  LA SOLICITUD DE COTIZACION YA ESTABA SELECCIONADA PROCEDE A ELIMINARLA
				
				// PROCEDE A ELIMINAR LOS MATERIALES PORQUE YA EXISTEN Y LOS ESTOY DESELECCIONANDO
				$sql = mysql_query("delete from relacion_compra_solicitud_cotizacion 
											where idorden_compra = '".$id_orden_compra."' and idsolicitud_cotizacion = '".$id_solicitud."'");
					
					
				$sql = mysql_query("select * from relacion_compra_solicitud_cotizacion where idorden_compra = '".$id_orden_compra."'");
				$num = mysql_num_rows($sql);
				// SI YA NO EXISTEN MAS SOLICITUDES MARCADAS EN LA ORDEN DE COMPRA PROCEDE A BORRA TODOS LOS REGISTRSO DE ESA ORDEN
				if($num == 0){ 
					$sql = mysql_query("delete from articulos_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
					$sql = mysql_query("delete from partidas_orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");	
					$sql = mysql_query("delete from relacion_impuestos_ordenes_compras where idorden_compra_servicio = '".$id_orden_compra."'");
					// ACTUALIZO LOS TOTALES EN LA TABLA ORDEN_COMPRA_SERVICIO
					$sql_actualiza_totales = mysql_query("update orden_compra_servicio set 	sub_total = 0,
																							sub_total_original = 0,
																							impuesto = 0,
																							exento = 0,
																							exento_original = 0,
																							total = 0
																					where idorden_compra_servicio=".$id_orden_compra." ")or die (mysql_error());
				// SI AUN EXISTEN SOLICITUDES MARCADAS ELIMINA LOS REGISTRSO DE LA QUE SE ESTA DESMARCANDO
				}else{
					// BUSCO TODOS LOS ARTICULOS DE ESA SOLICITUD DE COTIZACION PARA ELIMINARLOS
					$sql_articulos_compra = mysql_query("select * from articulos_compra_servicio 
																		where idorden_compra_servicio = '".$id_orden_compra."' 
																		and idsolicitud_cotizacion = '".$id_solicitud."'");
					
					
					
					registra_transaccion("Ingresar Materiales ya Existentes en Orden de Compra (".$id_orden_compra.")",$login,$fh,$pc,'orden_compra_servicio');
					
					
					while($bus_articulos_compra = mysql_fetch_array($sql_articulos_compra)){
						$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_articulos_compra["idarticulos_servicios"]."'");
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
						// ACTUALIZO LOS TOTALES EN LA TABLA ORDEN_COMPRA_SERVICIO
						$sql_actualiza_totales = mysql_query("update orden_compra_servicio set 
		sub_total = sub_total - '".$bus_articulos_compra["total"]."',
		sub_total_original = sub_total_original - '".$bus_articulos_compra["total"]."',
		impuesto = impuesto - '".$bus_articulos_compra["impuesto"]."',
		exento = exento - '".$bus_articulos_compra["exento"]."',
		exento_original = exento_original - '".$bus_articulos_compra["exento"]."',
		total = total - '".$bus_articulos_compra["impuesto"]."' - '".$bus_articulos_compra["total"]."' - '".$bus_articulos_compra["exento"]."'
		where idorden_compra_servicio=".$id_orden_compra." ")or die (mysql_error());
						
						// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
						//$sql = mysql_query("select * from articulos_compra_servicio where idarticulos_compra_servicio = ".$id_articulo_compra."");
						//$bus = mysql_fetch_array($sql);
						//if($bus["estado"] == "aprobado" || $bus["estado"] == "sin disponibilidad"){
						$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
								$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
					if ($contribuyente_ordinario=="si"){
						if($destino_partida == 1){// EL IMPUESTO TIENE PARTIDA
							//actualizo el total de impuesto restandole el impuesto de ese producto
							$sql_total_impuestos = mysql_query("update relacion_impuestos_ordenes_compras set 
														base_calculo = base_calculo-'".$bus_articulos_compra["total"]."',
														base_calculo_original = base_calculo_original - '".$bus_articulos_compra["total"]."',
														total=total-".$impuesto_por_producto." 
														where idorden_compra_servicio = ".$id_orden_compra." 
														and idimpuestos = ".$id_impuestos."");
							// valido que el impuesto tenga partida
							$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where 
																			idorden_compra_servicio = ".$id_orden_compra." 
																			and idimpuestos = ".$id_impuestos."
																			and estado <> 'rechazado'");												
							$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
							$existe_partida = mysql_num_rows($sql_total_impuestos);
							if ($existe_partida > 0) {
								// busco la partida del impuesto en la tabla maestro_presupuesto 
							
							$sql_ordinal_impuesto= mysql_query("select * from ordinal where codigo='0000'");
							$bus_ordinal_impuesto= mysql_fetch_array($sql_ordinal_impuesto);
							
							
										// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************

			
			$sql_maestro_impuestos = consultarMaestro($anio, $bus_orden["idcategoria_programatica"], $id_clasificador_impuestos, $fuente_financiamiento, $tipo_presupuesto, $bus_ordinal_impuesto["idordinal"], $cofinanciamiento);	
							
								
								/*$sql_maestro_impuestos = mysql_query("select * from maestro_presupuesto 
																				where anio = ".$anio." 
																						and idcategoria_programatica = ".$bus_compra_servicio["idcategoria_programatica"]." 
																						and idclasificador_presupuestario = ".$id_clasificador_impuestos."");*/
								
								while($bus_maestro = mysql_fetch_array($sql_maestro_impuestos)){
								
								$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
								
								
								//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
								$total_impuesto_imputable = $bus_total_impuestos["total"];
								// valido que el total de impuesto no sea mayor que el disponible para cambiar el estado				
								if($total_impuesto_imputable > $disponible){
									$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
																				monto = '".$total_impuesto_imputable."',
																				monto_original = '".$total_impuesto_imputable."' 
																				where 
																				idorden_compra_servicio = ".$id_orden_compra."
																				and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'");
										
								}else{
									$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
															monto = '".$total_impuesto_imputable."', 
															monto_original = '".$total_impuesto_imputable."' 
															where 
															idorden_compra_servicio = ".$id_orden_compra."
															and idcategoria_programatica = ".$bus_compra_servicio["idcategoria_programatica"]." 
															and idclasificador_presupuestario = ".$id_clasificador_impuestos."");
								}
								$partida_impuestos = $id_clasificador_impuestos;
							}
							}
						}else{
							$partida_impuestos = 0;
						}
						
					} 
						// cierro la validacion del impuesto descontandolo a la partida
		
						$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_compra_servicio.exento) as exentos from articulos_compra_servicio, articulos_servicios where
													articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
													and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
													and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." ");
													
						$bus_imputable = mysql_fetch_array($sql_imputable);
						$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"];
					if ($contribuyente_ordinario == "si"){	
						if($destino_partida == 0){ // valido si el impuesto tiene partida propia y se lo sumo al total imputable 
							$sql_total_impuesto = mysql_query("select SUM(impuesto) as totales_impuesto from articulos_compra_servicio, articulos_servicios where
													articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
													and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
													and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." and idpartida_impuesto = 0");
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

			
			$sql_maestro_impuestos = consultarMaestro($anio, $bus_orden["idcategoria_programatica"], $id_clasificador_impuestos, $fuente_financiamiento, $tipo_presupuesto, $idordinal, $cofinanciamiento);								
							
														
						while($bus_maestro = mysql_fetch_array($sql_maestro)){
						
						
						$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
						
						
						
						
						
						
						
						//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
						
						if($total_imputable > $disponible){ // actualizo la tabla de partidas 
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
																	monto = '".$total_imputable."',
																	monto_original = '".$total_imputable."' 
																	where 
																	idorden_compra_servicio = ".$id_orden_compra."
																	and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
							$estado = "sin disponibilidad";
						}else{
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
																		monto = '".$total_imputable."',
																		monto_original = '".$total_imputable."' 
																		where 
																		idorden_compra_servicio = ".$id_orden_compra."
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'");
								$estado = "aprobado";
						}
						
						}
						// borro el articulo que reste a las partidas
						$sql = mysql_query("delete from articulos_compra_servicio where 
																idarticulos_compra_servicio = '".$bus_articulos_compra["idarticulos_compra_servicio"]."'");
						
						
						$sql_consulta_iguales = mysql_query("select * from articulos_compra_servicio where idarticulos_servicios = ".$bus_articulos_compra["idarticulos_servicios"]." and idorden_compra_servicio = ".$id_orden_compra."");
						$num_consulta_iguales = mysql_num_rows($sql_consulta_iguales);
							if($num_consulta_iguales == 1){
								$sql_cambiar_iguales = mysql_query("update articulos_compra_servicio set duplicado = 0 where idarticulos_servicios = ".$bus_articulos_compra["idarticulos_servicios"]." and idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
								$sql_cambiar_iguales = mysql_query("update orden_compra_servicio set duplicados = 0 where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());			
							}		
										
										// ********************************************* ELIMINAR PARTIDAS *************************************************
					}// CIERRE DLE WHILE DE ELIMINAR
					//borro todas las partidas que esten en cero en la orden de compra
					$sql = mysql_query("delete from partidas_orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."
																									and monto <= 0");

				} // CIERRE DEL IF PARA SABER SI ERA LA UTLIMA SOLICITUD SELECCIONADA Y BORRAR TODO
							
			} // CIERRE DE LA ELIMINACION DE LA SOLICITUD QUE SE DESMARCO
		
		}else if($accion == "eliminar"){ // ACCION PARA ELIMINAR UN ARTICULO DE LA ORDEN DE COMPRA
		
			// si la accion es eliminar se hacen varias consultas para eliminar los articulos de la tabla articulos_compra_servicio, ademas se elimina la relacion del 
			// articulo con la solicitud y se crean variables para luego verificar si ya no hay mas articulos por una solicitud para que la solicitud se deseleccione de 
			// la lista de solicitudes del proveedor
			
		$sql_material_eliminar = mysql_query("select * from articulos_compra_servicio where idarticulos_compra_servicio = '".$id_material."'");
		$bus_material_eliminar = mysql_fetch_array($sql_material_eliminar);
		
			$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_material_eliminar["idarticulos_servicios"]."'");
			$bus_ordinal = mysql_fetch_array($sql_ordinal);
			$idordinal = $bus_ordinal["idordinal"];
			
			$id_solicitud_cotizacion= $bus_material_eliminar["idsolicitud_cotizacion"];
			// elimino el articulo seleccionado
			$sql = mysql_query("delete from articulos_compra_servicio where idarticulos_compra_servicio = ".$id_material."")or die(mysql_error());
			
			$sql_consulta_iguales = mysql_query("select * from articulos_compra_servicio where idarticulos_servicios = ".$bus_material_eliminar["idarticulos_servicios"]." and idorden_compra_servicio = ".$id_orden_compra."");
						$num_consulta_iguales = mysql_num_rows($sql_consulta_iguales);
							if($num_consulta_iguales == 1){
								$sql_cambiar_iguales = mysql_query("update articulos_compra_servicio set 
																duplicado = 0 
																where idarticulos_servicios = ".$bus_material_eliminar["idarticulos_servicios"]." 
																and idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
								$sql_cambiar_iguales = mysql_query("update orden_compra_servicio set 
																			duplicados = 0 
																			where 
																			idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());			
							}		
			
		$sql_en_solicitudes = mysql_query("select * from articulos_compra_servicio where idsolicitud_cotizacion = ".$id_solicitud_cotizacion." 
																					and idorden_compra_servicio = ".$id_orden_compra."");
			$num_en_solicitudes = mysql_num_rows($sql_en_solicitudes);
			
			$sql_todos_articulos = mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
			$num_todos_articulos = mysql_num_rows($sql_todos_articulos);
			if($num_todos_articulos == 0){
				// si no existen mas articulos en la orden de compra elimino los registros de esa orden en el resto de las tablas		
				$sql = mysql_query("delete from relacion_compra_solicitud_cotizacion where idorden_compra = ".$id_orden_compra."");
				$sql = mysql_query("delete from relacion_impuestos_ordenes_compras where idorden_compra_servicio = ".$id_orden_compra."");
				$sql = mysql_query("delete from partidas_orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
				// ACTUALIZO LOS TOTALES EN LA TABLA ORDEN_COMPRA_SERVICIO
				$sql_actualiza_totales = mysql_query("update orden_compra_servicio set 	sub_total = 0,
																						sub_total_original = 0,
																						impuesto = 0,
																						exento = 0,
																						exento_original = 0,
																						total = 0
																where idorden_compra_servicio=".$id_orden_compra." ")or die (mysql_error());
				$eliminoSolicitud = $id_solicitud_cotizacion;
			}else{
				if($num_en_solicitudes == 0){
					// si ya no existen mas articulos relacionadoa a esa la solicitud, procedo a eliminarla
					$sql = mysql_query("delete from relacion_compra_solicitud_cotizacion where 
																						idsolicitud_cotizacion = ".$id_solicitud_cotizacion." 
																						and idorden_compra = ".$id_orden_compra."");
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
				// ACTUALIZO LOS TOTALES EN LA TABLA ORDEN_COMPRA_SERVICIO
				$sql_actualiza_totales = mysql_query("update orden_compra_servicio set 
															sub_total = sub_total - '".$bus_material_eliminar["total"]."',
															sub_total_original = sub_total_original - '".$bus_material_eliminar["total"]."',
															impuesto = impuesto - '".$bus_material_eliminar["impuesto"]."',
															exento = exento - '".$bus_material_eliminar["exento"]."',
															exento_original = exento_original - '".$bus_material_eliminar["exento"]."',
		total = total - '".$bus_material_eliminar["impuesto"]."' - '".$bus_material_eliminar["total"]."' - '".$bus_material_eliminar["exento"]."'
															where idorden_compra_servicio=".$id_orden_compra." ")or die (mysql_error());
																			
				// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
				$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
				$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
				
			
				if($destino_partida == 1){// EL IMPUESTO TIENE PARTIDA
					if ($contribuyente_ordinario=="si"){
					//echo $impuesto_por_producto;
					$sql_total_impuestos = mysql_query("update relacion_impuestos_ordenes_compras set 
															base_calculo = base_calculo - '".$bus_material_eliminar["total"]."',
															base_calculo_original = base_calculo_original - '".$bus_material_eliminar["total"]."',
															total = total - ".$impuesto_por_producto." 
															where idorden_compra_servicio = ".$id_orden_compra." 
															and idimpuestos = ".$id_impuestos."")or die(mysql_error());
					// valido que el impuesto tenga partida
					$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where idorden_compra_servicio = ".$id_orden_compra." 
																	and idimpuestos = ".$id_impuestos."
																	and estado <> 'rechazado'");												
					$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
					$existe_partida = mysql_num_rows($sql_total_impuestos);
					
					if ($existe_partida > 0) {
						// consulta maestro con el clasificador de impuesto
						
					$sql_ordinal_impuesto= mysql_query("select * from ordinal where codigo='0000'");
					$bus_ordinal_impuesto= mysql_fetch_array($sql_ordinal_impuesto);

						
						
						
									// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************

			
			$sql_maestro_impuestos = consultarMaestro($anio, $id_categoria_programatica, $id_clasificador_impuestos, $fuente_financiamiento, $tipo_presupuesto, $bus_ordinal_impuesto["idordinal"], $cofinanciamiento);		
						
							
							
						while($bus_maestro = mysql_fetch_array($sql_maestro_impuestos)){
						
						
						$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
						
						
						//$disponible = $bus_maestro["monto_actual"] - $bus_maestro["total_compromisos"];
						$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras 
																							where idorden_compra_servicio = ".$id_orden_compra." 
																							and idimpuestos = ".$id_impuestos."");
																		
						$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
						$total_impuesto_imputable = $bus_total_impuestos["total"]-impuesto_por_producto;
						//echo $total_impuesto_imputable;				
						if($total_impuesto_imputable > $disponible){
							// si el impuesto imputable es mayor que el disponible 
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
																				monto = '".$total_impuesto_imputable."',
																				monto_original = '".$total_impuesto_imputable."' 
																				where 
																				idorden_compra_servicio = ".$id_orden_compra."
																				and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");				
						}else{
							// si existe disponibilidad para esa partida
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set 
																			estado = 'disponible', 
																			monto = '".$total_impuesto_imputable."',
																			monto_original = '".$total_impuesto_imputable."' 
																			where 
																			idorden_compra_servicio = ".$id_orden_compra."
																			and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
						}
						$partida_impuestos = $id_clasificador_impuestos;
					}
				}
				}
				}else{
					$partida_impuestos = 0;
				}


				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_compra_servicio.exento) as exentos from articulos_compra_servicio, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." ");
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"];
				
				if($destino_partida == 0 and $contribuyente_ordinario == "si"){
					$sql_total_impuesto = mysql_query("select SUM(impuesto) as totales_impuesto from articulos_compra_servicio, articulos_servicios where
												articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
												and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
												and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." and idpartida_impuesto = 0");
					$bus_total_impuesto = mysql_fetch_array($sql_total_impuesto);
					$total_impuesto = $bus_total_impuesto["totales_impuesto"];
					$total_imputable = $total_imputable + $total_impuesto;	
				}
				
					$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
																			and idcategoria_programatica = ".$id_categoria_programatica." 
																			and idclasificador_presupuestario = ".$id_clasificador_presupuestario."
																			and idfuente_financiamiento = '".$fuente_financiamiento."'
																			and idtipo_presupuesto = '".$tipo_presupuesto."'
																			and idordinal = '".$idordinal."'");
				$bus_maestro = mysql_fetch_array($sql_maestro);
				
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				
				
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				
				if($total_imputable > $disponible){ // si el total imputable es mayor al disponible
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
																			monto = '".$total_imputable."' ,
																			monto_original = '".$total_imputable."' 
																			where 
																			idorden_compra_servicio = ".$id_orden_compra."
																			and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
				}else{	// si el total imputable es menor o igual al disponible
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
																			monto = '".$total_imputable."' ,
																			monto_original = '".$total_imputable."' 
																			where 
																			idorden_compra_servicio = ".$id_orden_compra."
																			and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
				}
					
							
				$sql = mysql_query("delete from partidas_orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."
																							and monto <= 0");	
				// ********************************************* ELIMINAR PARTIDAS ************************************************
				
			}// CIERRE SI NO ES ARTICULO UNICO
		}
		
	}

	
				  
	$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die("");
	$bus_orden = mysql_fetch_array($sql_orden);
	$sql = mysql_query("select * from articulos_compra_servicio,  unidad_medida, articulos_servicios
									 where 
									 	articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." and
									  	articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios and 
									  	articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida")or die("aqui oc2 ".mysql_error());
	
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
			<td class="Browse" width="5%"><div align="center">Duplicados</div></td>
			<?
			}
			//echo "select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."";
			?>
            
            <td class="Browse" width="10%"><div align="center">C&oacute;digo</div></td>
            <td class="Browse" width="40%"><div align="center">Descripci&oacute;n</div></td>
            <td class="Browse" width="5%"><div align="center">UND</div></td>
            <td class="Browse" width="10%"><div align="center">Cantidad</div></td>
            <td class="Browse" width="10%"><div align="center">Precio Unitario</div></td>
            <td class="Browse" width="10%"><div align="center">Total</div></td>
			<?
            if($bus_orden["estado"] == "elaboracion"){
            ?>
            <td class="Browse" colspan="2" width="5%"><div align="center">Acciones</div></td>
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
            <td class='Browse' align='left' style="font-size:9px"><?=$bus["codigo"]?></td>
            <td class='Browse' align='left' style="font-size:9px"><?=$bus[30]?></td>
            <td class='Browse' align='left'><?=$bus["abreviado"]?></td>
            <td class='Browse' align='right'>
			<?
            if($bus_orden["estado"] == "elaboracion"){
            ?>
            <input align="right" style="text-align:right" name="cantidad<?=$bus["idarticulos_compra_servicio"]?>" 
            												type="text" 
                                                            id="cantidad<?=$bus["idarticulos_compra_servicio"]?>" 
                                                            size="10"
                                                            value="<?=$bus["cantidad"]?>">
            <?
            }else{
			echo number_format($bus["cantidad"],2,',','.');
			}
			?>			</td>
            <td class="Browse" align='right'>
			<?
            if($bus_orden["estado"] == "elaboracion"){
            ?>
            <input align="right" style="text-align:right" name="precio<?=$bus["idarticulos_compra_servicio"]?>" 
            												type="hidden" 
                                                            id="precio<?=$bus["idarticulos_compra_servicio"]?>" 
                                                            size="10"
                                                            value="<?=$bus["precio_unitario"]?>">
            <input align="right" style="text-align:right" name="mostrarPrecio<?=$bus["idarticulos_compra_servicio"]?>" 
            												type="text" 
                                                            id="mostrarPrecio<?=$bus["idarticulos_compra_servicio"]?>" 
                                                            size="10"
                                                            onclick="this.select()"
                                                            value="<?=number_format($bus["precio_unitario"],2,',','.')?>"
                                                            onblur="formatoNumero(this.name, 'precio<?=$bus["idarticulos_compra_servicio"]?>')">
                                                            <input type="hidden" name="eliminoSolicitud" id="eliminoSolicitud" value="<?=$eliminoSolicitud?>">
            <?
            }else{
			echo number_format($bus["precio_unitario"],2,',','.');
			}
			?>            </td>
            <td class="Browse" align="right">
				<? if($bus["total"] == "" and $bus["9"] == ""){
                	echo "0,00";
                }else{
					$total = $bus["total"] + $bus["9"];
               		echo number_format($total,2,',','.');
                }
                ?>            </td>
				<?
                if($bus_orden["estado"] == "elaboracion"){
				?>
            <td class='Browse' align="center">
<? /*
******************************************************************************************************************************
CUANDO ACTUALIZA PRECIO NO ESTA ENVIANDO EL RESTO DE LOS DATOS DE PRESUPUESTO: AÑO, TIPO_PRESUPUESTO, ORDINAL
FUENTE_FINANCIAMIENTO

*****************************************************************************************************************************
*/ ?><a href="javascript:;" onclick=""><a href="javascript:;" onclick=""><img src="imagenes/refrescar.png" onclick="
                                actualizarPrecioCantidad(<?=$bus["idorden_compra_servicio"]?>, 
                                document.getElementById('precio<?=$bus["idarticulos_compra_servicio"]?>').value,
                                document.getElementById('cantidad<?=$bus["idarticulos_compra_servicio"]?>').value, 
                                <?=$bus["idarticulos_servicios"]?>, 
                                <?=$bus["idarticulos_compra_servicio"]?>, 
                                document.getElementById('id_categoria_programatica').value,
                                document.getElementById('anio').value,
                                document.getElementById('fuente_financiamiento').value,
                                document.getElementById('tipo_presupuesto').value,
                                document.getElementById('id_ordinal').value,
                                document.getElementById('contribuyente_ordinario').value)" 
                                title="Actualizar Precio y Cantidad" /></a></a></td>  
<td class='Browse' align="center">
                    <a href="javascript:;" onClick="eliminarMateriales(<?=$bus["idorden_compra_servicio"]?>, <?=$bus["idarticulos_compra_servicio"]?>, <?=$bus["idsolicitud_cotizacion"]?>, document.getElementById('id_categoria_programatica').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value, document.getElementById('contribuyente_ordinario').value)">
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
//********************************************* RECALCULAR EL COMPROMISO ********************************************************************
//*******************************************************************************************************************************************
if($ejecutar == "recalcular"){
	
	$sql_articulos_compra_servicio = mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
	$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
	$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
	$limpiar_partidas = mysql_query("update partidas_orden_compra_servicio set monto = 0 where idorden_compra_servicio = '".$id_orden_compra."'");
	$sql2=mysql_query("update relacion_impuestos_ordenes_compras set 
												base_calculo = 0,
												base_calculo_original = 0,
												total= 0 
											where idorden_compra_servicio = '".$id_orden_compra."'")or die("3333333 ".mysql_error());
											
	$tipo_carga_orden = $bus_compra_servicio["tipo_carga_orden"];
	
	while($bus_articulos_compra_servicio = mysql_fetch_array($sql_articulos_compra_servicio)){
		$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_articulos_compra_servicio["idarticulos_servicios"]."'");
		$bus_ordinal = mysql_fetch_array($sql_ordinal);
		$idordinal = $bus_ordinal["idordinal"];
		
		//*************************************************************************************
	
		$sql_impuestos = mysql_query("select impuestos.destino_partida as destinoPartida, 
												impuestos.idimpuestos as idImpuestos, 
												impuestos.porcentaje as porcentajeImpuesto, 
												impuestos.idclasificador_presupuestario as clasificadorImpuestos, 
												articulos_servicios.idclasificador_presupuestario as clasificadorArticulos,
												articulos_servicios.exento as exento
									from impuestos, articulos_servicios 
										where articulos_servicios.idarticulos_servicios = ".$bus_articulos_compra_servicio["idarticulos_servicios"]." 
											and articulos_servicios.idimpuestos = impuestos.idimpuestos")or die(mysql_error());
		$bus_impuestos = mysql_fetch_array($sql_impuestos);
		
		$id_clasificador_presupuestario = $bus_impuestos["clasificadorArticulos"];
		$id_clasificador_impuestos = $bus_impuestos["clasificadorImpuestos"];
		$id_impuestos = $bus_impuestos["idImpuestos"];
		$destino_partida = $bus_impuestos["destinoPartida"];
		
		$total = $bus_articulos_compra_servicio["cantidad"] * $bus_articulos_compra_servicio["precio_unitario"];
			
		//VALIDO QUE EL ARTICULO TENGA ASOCIADO UN IMPUESTO Y EL CONTRIBUYENTE SEA ORDINARIO PARA AFECTARLO	
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
		//ACTUALIZO LOS MONTOS DE ESE ARTICULO										
		$sql2 = mysql_query("update articulos_compra_servicio set porcentaje_impuesto = '".$porcentaje_impuesto."',
																	 impuesto = '".$impuesto_por_producto."',
																	  total = '".$total."', 
																	  exento = '".$exento."',
																	  precio_unitario = '".$bus_articulos_compra_servicio["precio_unitario"]."', 
																	  cantidad = ".$bus_articulos_compra_servicio["cantidad"].",
																	  idpartida_impuesto = ".$id_clasificador_impuestos."
																	 where 
																	  idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")
																	  or die("error update articulos".mysql_error());
		

			if($destino_partida == 1 and $contribuyente_ordinario == "si"){
//*************************************************************************************************************					
					$sql_ordinal_impuesto = mysql_query("select * from ordinal where codigo='0000'");
					$bus_ordinal_impuesto= mysql_fetch_array($sql_ordinal_impuesto);
					
				
					// *********************************************************************************************************
					// *********************************************************************************************************
					// *********************************************************************************************************
					// **************************************** COFINANCIAMIENTO ***********************************************
					// *********************************************************************************************************
					// *********************************************************************************************************
					// *********************************************************************************************************
			
					$sql_maestro = consultarMaestro($anio, $bus_compra_servicio["idcategoria_programatica"], $id_clasificador_impuestos, $idfuente_financiamiento, $idtipo_presupuesto, $bus_ordinal_impuesto["idordinal"], $cofinanciamiento);
				
					$existen_requisiciones = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."")or die(mysql_error());	
					$num_existen_requisiones = mysql_num_rows($existen_requisiciones);																	

					while($bus_maestro = mysql_fetch_array($sql_maestro)){
					
						if ($num_existen_requisiones >0){
							$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
						}else{
							$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
						}
						
						$sql2=mysql_query("update relacion_impuestos_ordenes_compras set 
												base_calculo = base_calculo + '".$total."',
												base_calculo_original = base_calculo_original + '".$total."',
												total=total+".$impuesto_por_producto." 
											where idorden_compra_servicio = '".$id_orden_compra."'
												and idimpuestos = ".$id_impuestos."")or die("3333333 ".mysql_error());
						
						$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras 
																where idorden_compra_servicio = ".$id_orden_compra." 
																	and idimpuestos = ".$id_impuestos."")or die(mysql_error());
																	
						$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
						$total_impuesto_imputable = $bus_total_impuestos["total"];
						
						if($total_impuesto_imputable > $disponible and $tipo_carga_orden != "requisicion"){
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
									monto = monto + '".$impuesto_por_producto."',
									monto_original = monto_original + '".$impuesto_por_producto."' 
									where idorden_compra_servicio = ".$id_orden_compra."
									and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("error partida impuesto".mysql_error());
							$estado_impuesto = 'sobregiro';
						}else{
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
									monto = monto + '".$impuesto_por_producto."',
									monto_original = monto_original + '".$impuesto_por_producto."' 
									where idorden_compra_servicio = ".$id_orden_compra."
									and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")or die("error partida impuesto".mysql_error());
							$estado_impuesto = 'disponible';
						}
						$partida_impuestos = $id_clasificador_impuestos;
					}
			}else{
				$partida_impuestos = 0;
			}

				
			$sql2 = mysql_query("update articulos_compra_servicio set idpartida_impuesto = ".$partida_impuestos."  
											where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")or die(mysql_error());
				
						
			$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_compra_servicio.exento) as exentos from articulos_compra_servicio, 			
										articulos_servicios 
									where articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
										and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
										and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." ")or die(mysql_error());
										
			$bus_imputable = mysql_fetch_array($sql_imputable);
			$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"];
			
			if ($destino_partida == 0 and $contribuyente_ordinario == "si"){
				$sql_total_impuesto = mysql_query("select SUM(impuesto) as totales_impuesto from articulos_compra_servicio, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." and idpartida_impuesto = 0")or die(mysql_error());
				$bus_total_impuesto = mysql_fetch_array($sql_total_impuesto);
				$total_impuesto = $bus_total_impuesto["totales_impuesto"];
			}				
			$total_imputable = $total_imputable+$total_impuesto;			
				
				
			// *********************************************************************************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			
			$sql_maestro = consultarMaestro($anio, $bus_compra_servicio["idcategoria_programatica"], $id_clasificador_presupuestario, $idfuente_financiamiento, $idtipo_presupuesto, $idordinal, $cofinanciamiento);
		
				//echo "ID ORDEN_COMPRA:".$id_orden_compra;
				
				
				
				$existen_requisiciones = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."");	
				$num_existen_requisiones = mysql_num_rows($existen_requisiciones);																	
				
				
				while($bus_maestro = mysql_fetch_array($sql_maestro)){
				
				
					if ($num_existen_requisiones >0){
						$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
					}else{
						$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
					}
					
					//$disponible = $bus_maestro["monto_actual"] - $bus_maestro["total_compromisos"];
					
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
						$total_imputable_nuevo =  $total_imputable;
					}
				
					if($total_imputable_nuevo > $disponible and $tipo_carga_orden != "requisicion"){
						$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
																monto = '".$total_imputable_nuevo."',
																monto_original = '".$total_imputable_nuevo."' 
																where idorden_compra_servicio = ".$id_orden_compra."
																and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")or die(mysql_error());
						$estado = "sin disponibilidad";
					}else{
						$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
																	monto = '".$total_imputable_nuevo."',
																	monto_original = '".$total_imputable_nuevo."' 
																	where idorden_compra_servicio = ".$id_orden_compra."
																	and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")or die(mysql_error());
					
						$estado = "aprobado";
					}
				}
				
			if ($estado_impuesto == 'sobregiro'){
				$estado = "sin disponibilidad";
			}
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA		
	
			$sql2 = mysql_query("update articulos_compra_servicio set estado = '".$estado."' 
													where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")or die(mysql_error());
			
	
	//*****************************************************************************************
	}// CIERRE DEL WHILE DE CONSULTA DE LOS ARTICULOS DE LA ORDEN



	$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
	$bus_orden = mysql_fetch_array($sql_orden);
	
	if($bus_orden["estado"] == "elaboracion" and $bus_orden["descuento"] == 0){
			$sql = mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
				
				while($bus = mysql_fetch_array($sql)){
					$exento += $bus["exento"];
					$sub_total += $bus["total"];
					$total_impuesto += $bus["impuesto"];
					$total_general += ($bus["total"] + $bus["impuesto"] + $bus["exento"]);	
				}
			$actualiza_totales = mysql_query("update orden_compra_servicio set sub_total = '".$sub_total."',
																	sub_total_original = '".$sub_total."',
																	impuesto = '".$total_impuesto."',
																	exento = '".$exento."',
																	exento_original = '".$exento."',
																	total = '".$total_general."'
																	where idorden_compra_servicio=".$id_orden_compra." ")or die (mysql_error());																					
	}




}






//*******************************************************************************************************************************************
//********************************************* INGRESAR DATOS BASICOS DE LA ORDEN DE COMPRA ************************************************
//*******************************************************************************************************************************************
if($ejecutar == "agregarDatosBasicos"){
if($_SESSION["modulo"] == "3"){
	$sql_dependencia = mysql_query("select * from configuracion_compras");
}else if($_SESSION["modulo"] == "1"){
	$sql_dependencia = mysql_query("select * from configuracion_rrhh");
}else if($_SESSION["modulo"] == "4"){
	$sql_dependencia = mysql_query("select * from configuracion_administracion");
}else if($_SESSION["modulo"] == "5"){
	$sql_dependencia = mysql_query("select * from configuracion_contabilidad");
}else if($_SESSION["modulo"] == "6"){
	$sql_dependencia = mysql_query("select * from configuracion_tributos");
}else if($_SESSION["modulo"] == "7"){
	$sql_dependencia = mysql_query("select * from configuracion_tesoreria");
}else if($_SESSION["modulo"] == "8"){
	$sql_dependencia = mysql_query("select * from configuracion_bienes");
}else if($_SESSION["modulo"] == "2"){
	$sql_dependencia = mysql_query("select * from configuracion_presupuesto");
}else if($_SESSION["modulo"] == "12"){
	$sql_dependencia = mysql_query("select * from configuracion_despacho");
}else if($_SESSION["modulo"] == "13"){
	$sql_dependencia = mysql_query("select * from configuracion_nomina");
}else if($_SESSION["modulo"] == "14"){
	$sql_dependencia = mysql_query("select * from configuracion_secretaria");
}else if($_SESSION["modulo"] == "16"){
	$sql_dependencia = mysql_query("select * from configuracion_caja_chica");
}else if($_SESSION["modulo"] == "17"){
	$sql_dependencia = mysql_query("select * from configuracion_recaudacion");
}else if($_SESSION["modulo"] == "19"){
	$sql_dependencia = mysql_query("select * from configuracion_obras");
}

$bus_dependencia = mysql_fetch_array($sql_dependencia);
$iddependencia = $bus_dependencia["iddependencia"];

	$sql = mysql_query("insert into orden_compra_servicio (tipo,
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
															tipo_carga_orden,
															iddependencia,
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
																					'".$ordenado_por."',
																					'".$cedula_ordenado."',
																					'".$numero_requisicion."',
																					'".$fecha_requisicion."',
																					'elaboracion',
																					'a',
																					'".$login."',
																					'".date("Y-m-d H:i:s")."',
																					'".$nro_factura."',
																					'".$fecha_factura."',
																					'".$nro_control."',
																					'".$tipo_carga_orden."',
																					'".$iddependencia."',
																					'".$cofinanciamiento."')");
if($sql){
	$iddocumento = mysql_insert_id();
	echo mysql_insert_id();
	
	$sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$tipo_orden."'");
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
																			'".$fuente_financiamiento."',
																			'".$justificacion."',
																			'compromiso',
																			'".$iddocumento."',
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
	registra_transaccion("Ingresar datos Basicos de Orden Compra (".$iddocumento.")",$login,$fh,$pc,'orden_compra_servicios');
}else{
	registra_transaccion("Ingresar datos Basicos de Orden Compra ERROR",$login,$fh,$pc,'orden_compra_servicios');
	echo "fallo";
}
	

}


//*******************************************************************************************************************************************
//*********************************** LISTA TODAS LAS SOLICITUDES SELECCIONADAS EN UNA ORDEN DE COMPRA ***************************************
//*******************************************************************************************************************************************
if($ejecutar == "listarSolicitudesSeleccionadas"){
	$sql = mysql_query("select * from relacion_compra_solicitud_cotizacion where idorden_compra = ".$id_orden_compra."");
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








if($ejecutar == "listarRequisicionesSeleccionadas"){
	$sql = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."");
	$num = mysql_num_rows($sql);
	if($num > 0){	
		?>																		
	<table width="75%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <td class="Browse"><div align="center">Nro Requisicion</div></td>
            <td class="Browse"><div align="center">Fecha</div></td>
            <td class="Browse"><div align="center">Monto</div></td>
          </tr>
          </thead>
          <? 
          while($bus = mysql_fetch_array($sql)){
		  	$sql2 = mysql_query("select * from requisicion where idrequisicion = ".$bus["idrequisicion"]."");
			$bus2 = mysql_fetch_array($sql2);
          ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class='Browse' align='center'><?=$bus2["numero_requisicion"]?></td>
            <td class='Browse' align='left'><?=$bus2["fecha_orden"]?></td>
            <td class='Browse' align='right'><?=number_format($bus2["total"],2,",",".")?></td>
      </tr>
          <?
          }
          ?>
        </table>
    <?
		}else{
		echo "<center>No hay Requisiciones Seleccionadas</center>";
		}	
}





//*******************************************************************************************************************************************
//********************************************* ACTUALIZAR PRECIO CANTIDAD DE ARTICULOS ORDEN COMPRAS ***************************************
//*******************************************************************************************************************************************

if($ejecutar == "actualizarPrecioCantidad"){

			$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id_articulo."'");
			$bus_ordinal = mysql_fetch_array($sql_ordinal);
			$idordinal = $bus_ordinal["idordinal"];
			
			
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
			$sql_consulta_precio_viejo = mysql_query("select * from articulos_compra_servicio where 
													idarticulos_compra_servicio = ".$id_articulo_compra."")or die("2: ".mysql_error());
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
			$sql2 = mysql_query("update articulos_compra_servicio set porcentaje_impuesto = '".$porcentaje_impuesto."',
																	 impuesto = '".$impuesto_por_producto."',
																	  total = '".$total."', 
																	  precio_unitario = '".$precio."', 
																	  cantidad = '".$cantidad."',
																	  exento = '".$exento."'
																	  where 
																	  idarticulos_compra_servicio = ".$id_articulo_compra."")or die("3: ".mysql_error());
		
			
			// ACTUALIZO LOS TOTALES EN LA TABLA ORDEN_COMPRA_SERVICIO
			$total_anterior = $total_viejo + $impuesto_viejo + $exento;
			$total_nuevo = $total + $impuesto_por_producto + $exento;
			$sql_actualiza_totales = mysql_query("update orden_compra_servicio set 
															sub_total = sub_total - '".$total_viejo."' + '".$total."',
															sub_total_original = sub_total_original - '".$total_viejo."' + '".$total."',
															impuesto = impuesto - '".$impuesto_viejo."' + '".$impuesto_por_producto."',
															exento = exento - '".$exento_viejo."' + '".$exento."',
															exento_original = exento_original - '".$exento_viejo."' + '".$exento."',
															total = total - '".$total_anterior."' + '".$total_nuevo."'
																			where idorden_compra_servicio=".$id_orden_compra." ")or die("4: ".mysql_error());
			
			
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
			
			$sql = mysql_query("select * from articulos_compra_servicio where idarticulos_compra_servicio = ".$id_articulo_compra."")or die("5: ".mysql_error());
			$bus = mysql_fetch_array($sql);
			if($bus["estado"] == "aprobado" || $bus["estado"] == "sin disponibilidad"){ // en cualquiera de stos estados el articulo tiene partida en el maestro

					$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die("6: ".mysql_error());
					$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
					//echo "ID: ".$bus_compra_servicio["idcategoria_programatica"]." ";
				if($destino_partida == 1 and $contribuyente_ordinario == "si"){
					// actualizo el total del impuesto sumando el nuevo impuesto y restandole el anterior *********************************************************************************************************************************************************
					
					$sql_total_impuestos = mysql_query("update relacion_impuestos_ordenes_compras set 
														base_calculo =  base_calculo + '".$total_viejo."' + '".$total."',
														base_calculo_original = base_calculo_original + '".$total_viejo."' + '".$total."',
														total = total + (".$impuesto_por_producto."-".$impuesto_viejo.") 
														where idorden_compra_servicio = ".$id_orden_compra." 
														and idimpuestos = ".$id_impuestos."")or die("7: ".mysql_error());
					// consulta maestro con el clasificador de impuesto
					
					$sql_ordinal_impuesto= mysql_query("select * from ordinal where codigo='0000'");
					$bus_ordinal_impuesto = mysql_fetch_array($sql_ordinal_impuesto);
					
					
					
			// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************

			
			$sql_maestro_impuestos = consultarMaestro($anio, $bus_compra_servicio["idcategoria_programatica"], $id_clasificador_impuestos, $idfuente_financiamiento, $idtipo_presupuesto, $bus_ordinal_impuesto["idordinal"], $cofinanciamiento);							
							
																								
																								
					while($bus_maestro = mysql_fetch_array($sql_maestro_impuestos)){
					
					$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
					
					//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
					$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where idorden_compra_servicio = ".$id_orden_compra." 
																	and idimpuestos = ".$id_impuestos."")or die("9: ".mysql_error());
																	
					$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
					$total_impuesto_imputable = $bus_total_impuestos["total"];
					$total_impuesto_imputable2 = $impuesto_por_producto - $impuesto_viejo;
								
					
					
					
					
					if($total_impuesto_imputable > $disponible){ // comparo el impuesto imputable con el disponible en la partida para verificar su estado
						$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
																			monto = '".$total_impuesto_imputable."',
																			monto_original = '".$total_impuesto_imputable."' 
																			where 
																			idorden_compra_servicio = ".$id_orden_compra."
																			and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("10: ".mysql_error());
						
					}else{

						$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
																			monto = '".$total_impuesto_imputable."',
																			monto_original = '".$total_impuesto_imputable."' 
																			where 
																			idorden_compra_servicio = ".$id_orden_compra."
																			and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("16: ".mysql_error());
					}
					
					$partida_impuestos = $id_clasificador_impuestos;
					}
				}else{
					$partida_impuestos = 0;
				}

				/*
				$sql2 = mysql_query("update articulos_compra_servicio set idpartida_impuesto = ".$partida_impuestos."  
																		where idarticulos_compra_servicio = ".$id_articulo_compra."");
				
				*/		
				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_compra_servicio.exento) as exentos from articulos_compra_servicio, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." ")or die("12: ".mysql_error());
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"];
				//echo $bus_imputable["totales"];
				//echo $bus_imputable["exentos"];
				if ($destino_partida == 0 and $contribuyente_ordinario=="si"){
					$sql_total_impuesto = mysql_query("select SUM(impuesto) as totales_impuesto from articulos_compra_servicio, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." and idpartida_impuesto = 0")or die("13: ".mysql_error());
					$bus_total_impuesto = mysql_fetch_array($sql_total_impuesto);
					$total_impuesto = $bus_total_impuesto["totales_impuesto"];
					$total_imputable = $total_imputable + $total_impuesto;
				}

				
				
									

			// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************

			$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
			$bus_orden = mysql_fetch_array($sql_orden);
			
			$sql_maestro = consultarMaestro($anio, $bus_orden["idcategoria_programatica"], $id_clasificador_presupuestario, $idfuente_financiamiento, $idtipo_presupuesto, $idordinal, $cofinanciamiento);
							
				while($bus_maestro = mysql_fetch_array($sql_maestro)){
				//echo "ID REGISTRO: ".$bus_maestro["idRegistro"];
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
				
				
				
				if($total_imputable > $disponible){
				//echo "AQUIIIIIIIIII ".$total_imputable;
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
																		monto = '".$total_imputable_nuevo."',
																		monto_original = '".$total_imputable_nuevo."' 
																		where 
																		idorden_compra_servicio = ".$id_orden_compra."
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("15: ".mysql_error());
					$estado = "sin disponibilidad";
				}else{
				

				
				//echo "ALLAAAAAAA ".$total_imputable;
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
																			monto = '".$total_imputable_nuevo."',
																			monto_original = '".$total_imputable_nuevo."' 
																			where 
																			idorden_compra_servicio = ".$id_orden_compra."
																			and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("16: ".mysql_error());
				
					$estado = "aprobado";
				}
				
				}
			}else{
				$estado = "rechazado";
			}
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA		
			
			$sql2 = mysql_query("update articulos_compra_servicio set estado = '".$estado."',
																idpartida_impuesto = ".$partida_impuestos." 
																where idarticulos_compra_servicio = ".$id_articulo_compra."")or die("17: ".mysql_error());
			
		if($sql2){
				registra_transaccion("Actualizar Precio Cantidad de Orden de Compra (".$id_articulo_compra.")",$login,$fh,$pc,'orden_compra_servicios');

				echo "exito";
		}else{
				registra_transaccion("Actualizar Precio Cantidd de Orden de Compra ERROR (".$id_articulo_compra.")",$login,$fh,$pc,'orden_compra_servicios');

				echo $sql2." MYSQL ERROR: ".mysql_error();
		}
}


//*******************************************************************************************************************************************
//********************************************* ACTUALIZAR DATOS BASICOS DE LAS ORDENES DE COMPRA ***************************************
//*******************************************************************************************************************************************
if($ejecutar == "procesarSNC"){
	
	$sql = mysql_query("update orden_compra_servicio set modo_comunicacion = '".$modo_comunicacion."',
															tipo_actividad = '".$tipo_actividad."',
															modo_contratacion = '".$tipo_procedimiento."',
															fecha_inicio ='".$fecha_inicio."',
															fecha_cierre ='".$fecha_cierre."',
															decreto_4998 ='".$decreto_4998."',
															decreto_4910 ='".$decreto_4910."'
															where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
}




//*******************************************************************************************************************************************
//********************************************* ACTUALIZAR DATOS BASICOS DE LAS ORDENES DE COMPRA ***************************************
//*******************************************************************************************************************************************
if($ejecutar == "actualizarDatosBasicos"){
	if($accion == "actualizar"){
		$sql = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
		$bus_consulta = mysql_fetch_array($sql);
			
			//VALIDO SI CAMBIO DE CATEGORIA PROGRAMATICA PARA HACER EL CAMBIO DE LAS 
			//PARTIDAS PRESUPUESTARIAS A LA NUEVA CATEGORIA PROGRAMATICA
			
			$idcategoria_programatica_actual = $bus_consulta["idcategoria_programatica"];
			if ($idcategoria_programatica_actual != $id_categoria_programatica){
				$sql_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
				while($consulta = mysql_fetch_array($sql_partidas)){
					$sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro='".$consulta["idmaestro_presupuesto"]."'");
					$registro_maestro = mysql_fetch_array($sql_maestro);
					$sql_nuevo_maestro = mysql_query("select * from maestro_presupuesto WHERE
														idcategoria_programatica = '".$id_categoria_programatica."'
														and idclasificador_presupuestario = '".$registro_maestro["idclasificador_presupuestario"]."'
														and anio = '".$registro_maestro["anio"]."'
														and idtipo_presupuesto = '".$registro_maestro["idtipo_presupuesto"]."'
														and idfuente_financiamiento = '".$registro_maestro["idfuente_financiamiento"]."'
														and idordinal = '".$registro_maestro["idordinal"]."'
														");
					$existe_registro =mysql_num_rows($sql_nuevo_maestro);
					//VALIDO SI EXISTE LA PARTIDA EN LA NUEVA CATEGORIA PROGRAMATICA PARA ACTUALIZAR EL idmaestro_presupuesto EN 
					//LA TABLA partidas_orden_compra_servicio
					if ($existe_registro > 0){
						$registro_nuevo_maestro = mysql_fetch_array($sql_nuevo_maestro);
						$idmaestro_presupuesto_nuevo = $registro_nuevo_maestro["idRegistro"];
						$disponible = consultarDisponibilidad($idmaestro_presupuesto_nuevo);
						
						if($disponible < $consulta["monto"]){
							$estado = 'sobregiro';
						}else{
							$estado = 'disponible';
						}
						
						$actualizar_partida_orden_compra = mysql_query("update partidas_orden_compra_servicio set idmaestro_presupuesto = '".$idmaestro_presupuesto_nuevo."',
																								 estado = '".$estado."'
																	where idpartidas_orden_compra_servicio = '".$consulta["idpartidas_orden_compra_servicio"]."'");
					}else{
						//SI NO EXISTE LA PARTIDA EN LA NUEVA CATEGORIA PROGRAMATICA LA ELIMINO DE LA TABLA partidas_orden_pago
						$actualizar_partida_orden_compra = mysql_query("delete from partidas_orden_compra_servicio WHERE 
																			idpartidas_orden_compra_servicio = '".$consulta["idpartidas_orden_compra_servicio"]."'");
					}
				}
			}

			//VALIDO SI CAMBIO DE FUENTE DE FINANCIAMIENTO PARA HACER EL CAMBIO DE LAS 
			//PARTIDAS PRESUPUESTARIAS A LA NUEVA FUENTE DE FINANCIAMIENTO
			
			$idfuente_financiamiento_actual = $bus_consulta["idfuente_financiamiento"];
			if ($idfuente_financiamiento_actual != $fuente_financiamiento){
				$sql_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
				while($consulta = mysql_fetch_array($sql_partidas)){
					$sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro='".$consulta["idmaestro_presupuesto"]."'");
					$registro_maestro = mysql_fetch_array($sql_maestro);
					$sql_nuevo_maestro = mysql_query("select * from maestro_presupuesto WHERE
														idcategoria_programatica = '".$registro_maestro["idcategoria_programatica"]."'
														and idclasificador_presupuestario = '".$registro_maestro["idclasificador_presupuestario"]."'
														and anio = '".$registro_maestro["anio"]."'
														and idtipo_presupuesto = '".$registro_maestro["idtipo_presupuesto"]."'
														and idfuente_financiamiento = '".$fuente_financiamiento."'
														and idordinal = '".$registro_maestro["idordinal"]."'
														");
					$existe_registro =mysql_num_rows($sql_nuevo_maestro);
					//VALIDO SI EXISTE LA PARTIDA EN LA NUEVA FUENTE DE FINANCIAMIENTO PARA ACTUALIZAR EL idmaestro_presupuesto EN 
					//LA TABLA partidas_orden_compra_servicio
					if ($existe_registro > 0){
						$registro_nuevo_maestro = mysql_fetch_array($sql_nuevo_maestro);
						$idmaestro_presupuesto_nuevo = $registro_nuevo_maestro["idRegistro"];
						$disponible = consultarDisponibilidad($idmaestro_presupuesto_nuevo);
						
						if($disponible < $consulta["monto"]){
							$estado = 'sobregiro';
						}else{
							$estado = 'disponible';
						}
						
						$actualizar_partida_orden_compra = mysql_query("update partidas_orden_compra_servicio set idmaestro_presupuesto = '".$idmaestro_presupuesto_nuevo."',
																								 estado = '".$estado."'
																	where idpartidas_orden_compra_servicio = '".$consulta["idpartidas_orden_compra_servicio"]."'");
					}else{
						//SI NO EXISTE LA PARTIDA EN LA NUEVA FUENTE DE FINANCIAMIENTO LA ELIMINO DE LA TABLA partidas_orden_pago
						$actualizar_partida_orden_compra = mysql_query("delete from partidas_orden_compra_servicio WHERE 
																			idpartidas_orden_compra_servicio = '".$consulta["idpartidas_orden_compra_servicio"]."'");
					}
				}
			}




		$sql = mysql_query("update orden_compra_servicio set tipo = '".$tipo_orden."',
															idbeneficiarios = '".$id_beneficiarios."',
															idcategoria_programatica = '".$id_categoria_programatica."',
															anio = '".$anio."',
															idfuente_financiamiento = '".$fuente_financiamiento."',
															idtipo_presupuesto = '".$tipo_presupuesto."',
															justificacion = '".$justificacion."',
															observaciones = '".$observaciones."',
															ordenado_por = '".$ordenado_por."',
															cedula_ordenado = '".$cedula_ordenado."',
															numero_requisicion = '".$numero_requisicion."',
															fecha_requisicion = '".$fecha_requisicion."',
															usuario = '".$login."',
															fechayhora = '".date("Y-m-d H:i:s")."',
															nro_factura = '".$nro_factura."',
															 fecha_factura = '".$fecha_factura."',
															 nro_control = '".$nro_control."',
															 cofinanciamiento = '".$cofinanciamiento."',
															 tipo_carga_orden = '".$tipo_carga_orden."' where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
															 
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
	registra_transaccion("Actualizar Datos Basicos de Orden Compra (".$id_orden_compra.")",$login,$fh,$pc,'orden_compra_servicios');

	}
$sql = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
$bus_consulta = mysql_fetch_array($sql);															

$fecha_anulacion='';
switch($bus_consulta["estado"]){
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
		  $sql_conformar_documento = mysql_query("select * from conformar_documentos where iddocumento = '".$bus_consulta["idorden_compra_servicio"]."' and tipo = '".$bus_tipo_documento["idtipos_documentos"]."'")or die(mysql_error());
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
		  $fecha_anulacion = '<strong>'.' ... Fecha de Anulaci&oacute;n: '.$bus_consulta["fecha_anulacion"].'</strong>';
		  break;
	  case "pagado":
	  	$sql_relacion_pago = mysql_query("select * from relacion_pago_compromisos where idorden_compra_servicio = '".$bus_consulta["idorden_compra_servicio"]."'")or die(mysql_error());
		while ($bus_relacion_pago = mysql_fetch_array($sql_relacion_pago)){
			$sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_relacion_pago["idorden_pago"]."'");
			$bus_orden_pago = mysql_fetch_array($sql_orden_pago);
			$sql_cheque = mysql_query("select * from pagos_financieros where idorden_pago = '".$bus_orden_pago["idorden_pago"]."'");
			$bus_cheque = mysql_fetch_array($sql_cheque);
			$mostrar_estado = $mostrar_estado." Pagado : ".$bus_orden_pago["numero_orden"]." : ".$bus_orden_pago["fecha_orden"]." : ".$bus_cheque["numero_cheque"]." : ".$bus_cheque["fecha_cheque"];
		}
		break;
	case "parcial":
	  	$sql_relacion_pago = mysql_query("select * from relacion_pago_compromisos where idorden_compra_servicio = '".$bus_consulta["idorden_compra_servicio"]."'")or die(mysql_error());
		$pagos='';
		while ($bus_relacion_pago = mysql_fetch_array($sql_relacion_pago)){
			$sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_relacion_pago["idorden_pago"]."'");
			$bus_orden_pago = mysql_fetch_array($sql_orden_pago);
			if ($bus_orden_pago["estado"] == "procesado" or $bus_orden_pago["estado"] == "pagada"){
				$sql_cheque = mysql_query("select * from pagos_financieros where idorden_pago = '".$bus_orden_pago["idorden_pago"]."'");
				$bus_cheque = mysql_fetch_array($sql_cheque);
				
				$pagos = $pagos. "(".$bus_orden_pago["numero_orden"]." : ".$bus_orden_pago["fecha_orden"]." : ".$bus_cheque["numero_cheque"]." : ".$bus_cheque["fecha_cheque"]. ") / ";
			}
		}
	  	$mostrar_estado = "Parcial : ".$pagos;
		  break;
		case "ordenado":
	  	$sql_relacion_pago = mysql_query("select * from relacion_pago_compromisos where idorden_compra_servicio = '".$bus_consulta["idorden_compra_servicio"]."'")or die(mysql_error());
		$pagos='';
		while ($bus_relacion_pago = mysql_fetch_array($sql_relacion_pago)){
			$sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_relacion_pago["idorden_pago"]."'");
			$bus_orden_pago = mysql_fetch_array($sql_orden_pago);
			if ($bus_orden_pago["estado"] == "procesado" or $bus_orden_pago["estado"] == "pagada"){
				$sql_cheque = mysql_query("select * from pagos_financieros where idorden_pago = '".$bus_orden_pago["idorden_pago"]."'");
				$bus_cheque = mysql_fetch_array($sql_cheque);
				
				$pagos = $pagos. "(".$bus_orden_pago["numero_orden"]." : ".$bus_orden_pago["fecha_orden"]." : ".$bus_cheque["numero_cheque"]." : ".$bus_cheque["fecha_cheque"]. ") / ";
			}
		}
	  	$mostrar_estado = "Ordenado : ".$pagos;
		  break;
	  }


	$sql_categoria_programatica = mysql_query("select * from categoria_programatica where idcategoria_programatica = ".$bus_consulta["idcategoria_programatica"]."");
	$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
	$sql_unidad_ejecutora = mysql_query("select * from unidad_ejecutora where idunidad_ejecutora = ".$bus_categoria_programatica["idunidad_ejecutora"]."");
	$bus_unidad_ejecutora = mysql_fetch_array($sql_unidad_ejecutora);

	$categoria_programatica = $bus_categoria_programatica["codigo"].' '.$bus_unidad_ejecutora["denominacion"];


	$sql_proveedor = mysql_query("select * from beneficiarios where idbeneficiarios = ".$bus_consulta["idbeneficiarios"]."");
	$bus_proveedor = mysql_fetch_array($sql_proveedor);
	
	$beneficiario = $bus_proveedor["nombre"];
	$contribuyente = $bus_proveedor["contribuyente_ordinario"];

	echo $bus_consulta["numero_orden"]						."|.|".
		$bus_consulta["fecha_orden"]						."|.|".
		$bus_consulta["fecha_elaboracion"]					."|.|".
		$bus_consulta["estado"]								."|.|".
		'<STRONG>'.$mostrar_estado.'</strong>'				."|.|".
		$fecha_anulacion									."|.|".
		$bus_consulta["tipo"]								."|.|".
		$bus_consulta["idcategoria_programatica"]			."|.|".
		$categoria_programatica								."|.|".
		$bus_consulta["idfuente_financiamiento"]			."|.|".
		$bus_consulta["cofinanciamiento"]					."|.|".
		$bus_consulta["idtipo_presupuesto"]					."|.|".
		$bus_consulta["anio"]								."|.|".
		$bus_consulta["justificacion"]						."|.|".
		$bus_consulta["observaciones"]						."|.|".
		$bus_consulta["ordenado_por"]						."|.|".
		$bus_consulta["cedula_ordenado"]					."|.|".
		$bus_consulta["numero_requisicion"]					."|.|".
		$bus_consulta["fecha_requisicion"]					."|.|".
		$bus_consulta["idbeneficiarios"]					."|.|".
		$beneficiario										."|.|".
		$contribuyente										."|.|".
		$bus_consulta["nro_factura"]						."|.|".
		$bus_consulta["nro_control"]						."|.|".
		$bus_consulta["fecha_factura"]						."|.|".
		$bus_consulta["modo_comunicacion"]					."|.|".
		$bus_consulta["tipo_actividad"]						."|.|".
		$bus_consulta["modo_contratacion"]					."|.|".
		$bus_consulta["fecha_inicio"]						."|.|".
		$bus_consulta["fecha_cierre"]						."|.|".
		$bus_consulta["decreto_4998"]						."|.|".
		$bus_consulta["decreto_4910"]						."|.|";
																
}


//*******************************************************************************************************************************************
//********************************************* ACTUALIZAR LISTA DE TOTALES DE LAS ORDENES DE COMPRA ***************************************
//*******************************************************************************************************************************************
if($ejecutar == "actualizarListaDeTotales"){
$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
$bus_orden = mysql_fetch_array($sql_orden);

if($bus_orden["estado"] == "elaboracion" and $bus_orden["descuento"] == 0){
		$sql = mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
			
			while($bus = mysql_fetch_array($sql)){
				$exento += $bus["exento"];
				$sub_total += $bus["total"];
				$total_impuesto += $bus["impuesto"];
				$total_general += ($bus["total"] + $bus["impuesto"] + $bus["exento"]);	
			}
		$actualiza_totales = mysql_query("update orden_compra_servicio set sub_total = '".$sub_total."',
																sub_total_original = '".$sub_total."',
																impuesto = '".$total_impuesto."',
																exento = '".$exento."',
																exento_original = '".$exento."',
																total = '".$total_general."'
																where idorden_compra_servicio=".$id_orden_compra." ")or die (mysql_error());																					
}

if($bus_relacion["estado"] == "sin disponibilidad"){
	$color = "#FFFF00";
}else if($bus_relacion["estado"] == "rechazado"){
	$color = "#FF0000";
}else{
$color = "";
}


$exento = $bus_orden["exento"];
$sub_total = $bus_orden["sub_total"];
$total_impuesto = $bus_orden["impuesto"];
$total_general = $bus_orden["total"];
						
		?>
        <b>Exento:</b> <?=number_format($exento,2,',','.')?> |
        <b>Sub Total:</b> <?=number_format($sub_total,2,',','.')?> | 
        <span style="background-color:<?=$color?>"><b>Impuestos:</b> <?=number_format($total_impuesto,2,',','.')?></span> |
        <b>Total Bs:</b> <?=number_format($total_general,2,',','.')?>
        
        <?
}




//*******************************************************************************************************************************************
//********************************************* ACTUALIZAR LISTA DE TOTALES DE LAS PARTIDAS ***************************************
//*******************************************************************************************************************************************
if($ejecutar == "actualizarTotalesPartidas"){
		
		$sql = mysql_query("select sum(monto) as monto_suma from partidas_orden_compra_servicio 
											where idorden_compra_servicio = ".$id_orden_compra."")or die("");
			//while(
			$bus = mysql_fetch_array($sql);
				$monto += $bus["monto_suma"];
			//}
		
		$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_compra."
														and tipo_movimiento = 'compromiso'");
		if (mysql_num_rows($sql_validar_asiento) > 0){
			
			$bus_asiento_contable = mysql_fetch_array($sql_validar_asiento);
	
			$sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'
														order by afecta");	
														
			while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)){
				$actualizar_cuentas = mysql_query("update cuentas_asiento_contable set monto = '".$monto."'
																where idcuentas_asiento_contable = '".$bus_cuentas_contables["idcuentas_asiento_contable"]."'");
			}
		}
		?>
        <?="<strong>Total Bsf: </strong>".number_format($monto,2,',','.')?>
        <?
}



//*******************************************************************************************************************************************
//********************************************* LISTA DE PARTIDAS ASOCIADAS A LOS MATERIALES SELECCIONADOS **********************************
//*******************************************************************************************************************************************
if($ejecutar == "mostrarPartidas"){
$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
$bus_orden = mysql_fetch_array($sql_orden);
//and idclasificador_presupuestario = ".$bus_orden["idclasificador_presupuestario"]." 

$sql_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
																		
$num_partidas = mysql_num_rows($sql_partidas);
if($num_partidas != 0){
	?>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <td class="Browse" colspan="4"><div align="center">Partida</div></td>
            <td width="21%" class="Browse"><div align="center">Fuente de Financiamiento</div></td>
            <td width="41%" class="Browse"><div align="center">Descripci&oacute;n</div></td>
            <?
            if($_SESSION["mos_dis"] == 1){
			?>
            <td width="12%" class="Browse"><div align="center">Disponible</div></td>
            <?
			}
			?>
            <td width="12%" class="Browse"><div align="center">Monto a Comprometer</div></td>
            
    </tr>
          </thead>
          <? 
          while($bus_partidas = mysql_fetch_array($sql_partidas)){
		  
		  $sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = ".$bus_partidas["idmaestro_presupuesto"]."");
		  $bus_maestro = mysql_fetch_array($sql_maestro);
		  //echo $bus_partidas["idmaestro_presupuesto"];
		  
          	if($bus_partidas["estado"] == "sobregiro"){
		  ?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
			}else if($bus_partidas["estado"] == "disponible"){
			?>
			
            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			<?
			}
			
			
          $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_maestro["idclasificador_presupuestario"]."'")or die(mysql_error());
		  $bus_clasificador = mysql_fetch_array($sql_clasificador);
		  ?>
            <td width="3%" align='center' class='Browse'><?=$bus_clasificador["partida"]?></td>
            <td width="3%" align='center' class='Browse'><?=$bus_clasificador["generica"]?></td>
            <td width="3%" align='center' class='Browse'><?=$bus_clasificador["especifica"]?></td>
            <td width="5%" align='center' class='Browse'><?=$bus_clasificador["sub_especifica"]?></td>
            
			<?
            $sql_fuente = mysql_query("select * from fuente_financiamiento where idfuente_financiamiento = '".$bus_maestro["idfuente_financiamiento"]."'");
		  $bus_fuente = mysql_fetch_array($sql_fuente);
			?>
             <td class='Browse' align='left'>&nbsp;<?=$bus_fuente["denominacion"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["denominacion"]?></td>
            	<?
                $disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				?>
    	     
             <?
            if($_SESSION["mos_dis"] == 1){
			?>
             <td class='Browse' align="right"><?=number_format($disponible,2,',','.')?></td>
	         <?
			}
			 ?>
             <td class='Browse' align='right'><?=number_format($bus_partidas["monto"],2,',','.')?></td>
          
          <?
          }
		  
          ?>
         
      </tr>
        </table>																	
<?
    }else{
	echo "No hay Partidas Asociadas";
    }																	
}


//*******************************************************************************************************************************************
//********************************************* LISTA DE CUENTAS CONTABLES  *****************************************************************
//*******************************************************************************************************************************************
if($ejecutar == "mostrarCuentasContables"){

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
$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_compra."
														and tipo_movimiento = 'compromiso'");
if (mysql_num_rows($sql_validar_asiento) > 0){																												
while ($bus_asiento_contable = mysql_fetch_array($sql_validar_asiento)){

	$sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'
														order by afecta")or die("aqui cuenta ".mysql_error());																
	$num_cuentas_contables = mysql_num_rows($sql_cuentas_contables);
	if($num_cuentas_contables != 0){
		
		if ($bus_asiento_contable["estado"] <> 'anulado'){
			?>
			
			  <? 
			  while($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)){
				?>
				<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<?
				$idcampo = "id".$bus_cuentas_contables["tabla"];
				$sql_cuentas = mysql_query("select * from ".$bus_cuentas_contables["tabla"]." 
																	where ".$idcampo." = '".$bus_cuentas_contables["idcuenta"]."'")or die(mysql_error());
				$bus_cuenta = mysql_fetch_array($sql_cuentas);
				
				if ($bus_cuentas_contables["afecta"] == 'debe'){
				?>
					<td width="10%" align="left" class='Browse'><?=$bus_cuenta["codigo"]?></td>
					<td width="62%" align="left" class='Browse'><?=$bus_cuenta["denominacion"]?></td>
					<td width="12%" align="right" class='Browse'><?=number_format($bus_cuentas_contables["monto"],2,',','.')?></td>
					<td width="12%" align="right" class='Browse'>&nbsp;</td>
				<?
				}else{
				?>
					<td width="10%" align="left" class='Browse'><?=$bus_cuenta["codigo"]?></td>
					<td width="62%" align="left" class='Browse'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$bus_cuenta["denominacion"]?></td>
					<td width="12%" align="right" class='Browse'>&nbsp;</td>
					<td width="12%" align="right" class='Browse'><?=number_format($bus_cuentas_contables["monto"],2,',','.')?></td>
				<?
				}
				?>
			   </tr>
				<?
			  }
			  
		}else{
			//MUESTRALAS CUENTAS QUE REVERSAN EL ASIENTO POR LA ANULACION
			?>
			<tr bgcolor="#FFCC33" onMouseOver="setRowColor(this, 0, 'over', '#FFCC33', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFCC33', '#EAFFEA', '#FFFFAA')">
				<td align="left" class='Browse' colspan="4"><strong>Fecha de Reverso: <?=$bus_asiento_contable["fecha_contable"]?></strong></td>
            </tr>
			
			<?
			while($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)){
				?>
				<tr bgcolor="#FFFF99" onMouseOver="setRowColor(this, 0, 'over', '#FFFF99', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF99', '#EAFFEA', '#FFFFAA')">
				<?
				$idcampo = "id".$bus_cuentas_contables["tabla"];
				$sql_cuentas = mysql_query("select * from ".$bus_cuentas_contables["tabla"]." 
																	where ".$idcampo." = '".$bus_cuentas_contables["idcuenta"]."'")or die(mysql_error());
				$bus_cuenta = mysql_fetch_array($sql_cuentas);
				
				if ($bus_cuentas_contables["afecta"] == 'debe'){
				?>
					<td width="10%" align="left" class='Browse'><?=$bus_cuenta["codigo"]?></td>
					<td width="62%" align="left" class='Browse'><?=$bus_cuenta["denominacion"]?></td>
					<td width="12%" align="right" class='Browse'><?=number_format($bus_cuentas_contables["monto"],2,',','.')?></td>
					<td width="12%" align="right" class='Browse'>&nbsp;</td>
				<?
				}else{
				?>
					<td width="10%" align="left" class='Browse'><?=$bus_cuenta["codigo"]?></td>
					<td width="62%" align="left" class='Browse'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$bus_cuenta["denominacion"]?></td>
					<td width="12%" align="right" class='Browse'>&nbsp;</td>
					<td width="12%" align="right" class='Browse'><?=number_format($bus_cuentas_contables["monto"],2,',','.')?></td>
				<?
				}
				?>
			   </tr>
				<?
			  }
			
			
			
																			
		}
    }else{
		echo "No se han Registrado Movimientos Contables para este documento";
    }
}
}
?>
</table>   
<?
}





// *****************************************************************************************************************************************
// ************************************************* PROCESAR ORDEN DE COMPRA Y SERVICIO ***************************************************
// *****************************************************************************************************************************************

if($ejecutar == "procesarOrden"){

	$sql_articulos_compra_servicio = mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
	$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
	$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
	$sql2=mysql_query("update relacion_impuestos_ordenes_compras set 
												base_calculo = 0,
												base_calculo_original = 0,
												total= 0 
											where idorden_compra_servicio = '".$id_orden_compra."'")or die("3333333 ".mysql_error());
	$tipo_carga_orden = $bus_compra_servicio["tipo_carga_orden"];
	
	while($bus_articulos_compra_servicio = mysql_fetch_array($sql_articulos_compra_servicio)){
	
		$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_articulos_compra_servicio["idarticulos_servicios"]."'");
		$bus_ordinal = mysql_fetch_array($sql_ordinal);
		$idordinal = $bus_ordinal["idordinal"];
		
		//*************************************************************************************
	
			$sql_impuestos = mysql_query("select impuestos.destino_partida as destinoPartida, 
										impuestos.idimpuestos as idImpuestos, 
										impuestos.porcentaje as porcentajeImpuesto, 
										impuestos.idclasificador_presupuestario as clasificadorImpuestos, 
										articulos_servicios.idclasificador_presupuestario as clasificadorArticulos,
										articulos_servicios.exento as exento
									from impuestos, articulos_servicios 
									where articulos_servicios.idarticulos_servicios = ".$bus_articulos_compra_servicio["idarticulos_servicios"]." 
										and articulos_servicios.idimpuestos = impuestos.idimpuestos")or die(mysql_error());
			$bus_impuestos = mysql_fetch_array($sql_impuestos);
			
			$id_clasificador_presupuestario = $bus_impuestos["clasificadorArticulos"];
			$id_clasificador_impuestos = $bus_impuestos["clasificadorImpuestos"];
			$id_impuestos = $bus_impuestos["idImpuestos"];
			$destino_partida = $bus_impuestos["destinoPartida"];
			
			//echo "Porcentaje: ".$bus[3];
			
			$total = $bus_articulos_compra_servicio["cantidad"] * $bus_articulos_compra_servicio["precio_unitario"];

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
													
			$sql2 = mysql_query("update articulos_compra_servicio set porcentaje_impuesto = '".$porcentaje_impuesto."',
																	 impuesto = '".$impuesto_por_producto."',
																	  total = '".$total."', 
																	  exento = '".$exento."',
																	  precio_unitario = '".$bus_articulos_compra_servicio["precio_unitario"]."', 
																	  cantidad = ".$bus_articulos_compra_servicio["cantidad"]."
														where 
														  idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")
														  or die("error update articulos".mysql_error());
			
			
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
			$sql = mysql_query("select * from articulos_compra_servicio 
														where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]." 
															")or die("error todos los articulos".mysql_error());
			$bus = mysql_fetch_array($sql);
			//if($bus["tipo_carga_orden"] != "requisicion"){
			if($bus["estado"] == "aprobado" || $bus["estado"] == "sin disponibilidad"){
			
				if($destino_partida == 1 and $contribuyente_ordinario == "si"){				
					$sql_ordinal_impuesto = mysql_query("select * from ordinal where codigo='0000'");
					$bus_ordinal_impuesto= mysql_fetch_array($sql_ordinal_impuesto);
				
					
					// *********************************************************************************************************
					// *********************************************************************************************************
					// *********************************************************************************************************
					// **************************************** COFINANCIAMIENTO ***********************************************
					// *********************************************************************************************************
					// *********************************************************************************************************
					// *********************************************************************************************************
			
					$sql_maestro = consultarMaestro($anio, $bus_compra_servicio["idcategoria_programatica"], $id_clasificador_impuestos, $idfuente_financiamiento, $idtipo_presupuesto, $bus_ordinal_impuesto["idordinal"], $cofinanciamiento);
				
					$existen_requisiciones = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."")or die(mysql_error());	
					$num_existen_requisiones = mysql_num_rows($existen_requisiciones);																	

					while($bus_maestro = mysql_fetch_array($sql_maestro)){
					
						if ($num_existen_requisiones >0){
							$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
						}else{
							$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
						}
						
						$sql2=mysql_query("update relacion_impuestos_ordenes_compras set 
												base_calculo = base_calculo + '".$total."',
												base_calculo_original = base_calculo_original + '".$total."',
												total=total+".$impuesto_por_producto." 
											where idorden_compra_servicio = '".$id_orden_compra."'
												and idimpuestos = ".$id_impuestos."")or die("3333333 ".mysql_error());
												
						//$disponible = $bus_maestro["monto_actual"] - $bus_maestro["total_compromisos"];
						$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras 
																	where idorden_compra_servicio = ".$id_orden_compra." 
																		and idimpuestos = ".$id_impuestos."")or die(mysql_error());
																		
						$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
						$total_impuesto_imputable = $bus_total_impuestos["total"];				

						if($total_impuesto_imputable > $disponible and $tipo_carga_orden != "requisicion"){
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
									monto = '".$total_impuesto_imputable."',
									monto_original = '".$total_impuesto_imputable."' 
									where idorden_compra_servicio = ".$id_orden_compra."
									and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("error partida impuesto".mysql_error());
							
						}else{
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
									monto = '".$total_impuesto_imputable."',
									monto_original = '".$total_impuesto_imputable."' 
									where idorden_compra_servicio = ".$id_orden_compra."
									and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")or die("error partida impuesto".mysql_error());
						}
					$partida_impuestos = $id_clasificador_impuestos;
					}
				}else{
					$partida_impuestos = 0;
				}

				
				$sql2 = mysql_query("update articulos_compra_servicio set idpartida_impuesto = ".$partida_impuestos."  
																	where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")or die(mysql_error());
				
						
				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_compra_servicio.exento) as exentos from articulos_compra_servicio, articulos_servicios 
										where articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." ")or die(mysql_error());
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"];
				if ($destino_partida == 0 and $contribuyente_ordinario == "si"){
					$sql_total_impuesto = mysql_query("select SUM(impuesto) as totales_impuesto from articulos_compra_servicio, articulos_servicios where
												articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
												and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
												and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." and idpartida_impuesto = 0")or die(mysql_error());
					$bus_total_impuesto = mysql_fetch_array($sql_total_impuesto);
					$total_impuesto = $bus_total_impuesto["totales_impuesto"];
				}				
				$total_imputable = $total_imputable+$total_impuesto;
//*********************************************************************************				
				
				
				
				
				
				// *********************************************************************************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			
			
			$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
			$bus_orden = mysql_fetch_array($sql_orden);
			
			$sql_maestro = consultarMaestro($anio, $bus_orden["idcategoria_programatica"], $id_clasificador_presupuestario, $idfuente_financiamiento, $idtipo_presupuesto, $idordinal, $cofinanciamiento);
		
				//echo "ID ORDEN_COMPRA:".$id_orden_compra;
				
				$existen_requisiciones = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."");	
				$num_existen_requisiones = mysql_num_rows($existen_requisiciones);																	
				
				
				while($bus_maestro = mysql_fetch_array($sql_maestro)){
				
				
				if ($num_existen_requisiones >0){
					$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				}else{
					$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				}
				
				//$disponible = $bus_maestro["monto_actual"] - $bus_maestro["total_compromisos"];
				
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
					$total_imputable_nuevo =  $total_imputable;
				}
					
				
				
				
					if($total_imputable_nuevo > $disponible and $tipo_carga_orden != "requisicion"){
						$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
																monto = '".$total_imputable_nuevo."',
																monto_original = '".$total_imputable_nuevo."' 
																where idorden_compra_servicio = ".$id_orden_compra."
																and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")or die(mysql_error());
						$estado = "sin disponibilidad";
					}else{
						$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
																	monto = '".$total_imputable_nuevo."',
																	monto_original = '".$total_imputable_nuevo."' 
																	where idorden_compra_servicio = ".$id_orden_compra."
																	and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")or die(mysql_error());
					
						$estado = "aprobado";
					}
					}
				
			}else{
				$estado = "rechazado";
			}
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA		
				
				
				
			$sql2 = mysql_query("update articulos_compra_servicio set estado = '".$estado."' 
													where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")or die(mysql_error());
			
	
	//*****************************************************************************************
	}// CIERRE DEL WHILE DE CONSULTA DE LOS ARTICULOS DE LA ORDEN





	$sql_articulos = mysql_query("select * from articulos_compra_servicio 
												where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
	$num_articulos = mysql_num_rows($sql_articulos);
	
	if($num_articulos != 0){
		$sql_orden_duplicados = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
		$bus_orden_duplicados = mysql_fetch_array($sql_orden_duplicados);
			if($bus_orden_duplicados["duplicados"] == 0){
				$sql_articulos = mysql_query("select * from articulos_compra_servicio 
														where idorden_compra_servicio = ".$id_orden_compra." 
														and (estado = 'rechazado' or estado = 'sin disponibilidad')")or die(mysql_error());
		$num_articulos = mysql_num_rows($sql_articulos);
		
		if($num_articulos == 0){
			$sql_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where 
														idorden_compra_servicio = ".$id_orden_compra." 
														and (estado = 'rechazado' or estado = 'sin disponibilidad')")or die(mysql_error());
			$num_impuestos = mysql_num_rows($sql_impuestos);
			
			if($num_impuestos == 0){
				$sql_actualizar_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
				while($bus_actualizar_partidas = mysql_fetch_array($sql_actualizar_partidas)){
					$sql_maestro = mysql_query("update maestro_presupuesto set 
															total_compromisos = total_compromisos + ".$bus_actualizar_partidas["monto"]."
															where idRegistro = ".$bus_actualizar_partidas["idmaestro_presupuesto"]."")or die(mysql_error());
					$sql_consulta_ordinal = mysql_query("select * from ordinal where codigo = '0000'")or die("ERROR CONSULTANDO EL ORDINAL NO APLICA".mysql_error());
					$bus_consulta_ordinal = mysql_fetch_array($sql_consulta_ordinal);
					
					$sql_consultar_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '".$bus_actualizar_partidas["idmaestro_presupuesto"]."' and idordinal != '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO EL MAESTRO 1:".mysql_error());
					$num_consulta_maestro = mysql_num_rows($sql_consultar_maestro);
					if($num_consultar_maestro != 0){
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
															where idRegistro = ".$bus_sub_epe["idmaestro_presupuesto"]."")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 2: ".mysql_error());
							
						}
						
						$sql_clasificador = mysql_query("select * clasificador_presupuestario where idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."' and sub_especifica != '00'")or die("ERROR CONSULTANDO EL CLASIFICADOR ".mysql_error());
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
															total_compromisos = total_compromisos + ".$bus_actualizar_partidas["monto"]."
															where idRegistro = ".$bus_id_maestro["idmaestro_presupuesto"]."")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 3: ".mysql_error());
							
						}
						
					}
				}
					
				$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
				$bus_orden = mysql_fetch_array($sql_orden);
				$tipo_orden = $bus_orden["tipo"];
			
				$sql_configuracion = mysql_query("select * from configuracion");
				$bus_configuracion = mysql_fetch_array($sql_configuracion);
				$anio_fiscal = $bus_configuracion["anio_fiscal"];
				
				
				$sql_nro_orden = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$tipo_orden."");
				$bus_nro_orden = mysql_fetch_array($sql_nro_orden);
				
				$codigo_orden = $bus_nro_orden["siglas"]."-".$anio_fiscal."-".$bus_nro_orden["nro_contador"];

				$sql_existe_numero = mysql_query("select * from orden_compra_servicio where numero_orden = '".$codigo_orden."'")or die("cero".mysql_error());
				$bus_existe = mysql_num_rows($sql_existe_numero);
				
				while ($bus_existe > 0){
					
					$sql_actualizar_numero = mysql_query("update tipos_documentos set nro_contador = nro_contador + 1 where idtipos_documentos = ".$tipo_orden."")or die("uno".mysql_error());
				
					$sql_nro_orden = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$tipo_orden."");
					$bus_nro_orden = mysql_fetch_array($sql_nro_orden);
					
					$codigo_orden = $bus_nro_orden["siglas"]."-".$anio_fiscal."-".$bus_nro_orden["nro_contador"];
					
					$sql_existe_numero = mysql_query("select * from orden_compra_servicio where numero_orden = '".$codigo_orden."'")or die("tres".mysql_error());
					$bus_existe = mysql_num_rows($sql_existe_numero);
					
				}
				
				
				
				// ACA SE GENERA EL NUMERO DE CONTROL DE LA ORDEN DE COMPRA


				$codigo_referencia = 90000000000+$bus_nro_orden["nro_contador"];
				
				$sql_actualizar_orden = mysql_query("update orden_compra_servicio set estado = 'procesado', 
																						numero_orden = '".$codigo_orden."',
																						fecha_orden = '".$fecha_validada."',
																						codigo_referencia = '".$codigo_referencia."'
																					where idorden_compra_servicio = ".$id_orden_compra."")or die("error".mysql_error());
				
				
				
				
				
			//	echo "select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."";
				$sql_relacion_compra_requisicion = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."")or die("ERROR EN EL SELECT ".mysql_error());
				
				while ($bus_relacion_compra_requisicion = mysql_fetch_array($sql_relacion_compra_requisicion))
				{
				//echo "update requisicion set estado = 'ordenado' where idrequisicion = ".$bus_relacion_compra_requisicion["idrequisicion"]."";
					$sql_actualizar_requisicion = mysql_query("update requisicion set estado = 'ordenado' where idrequisicion = ".$bus_relacion_compra_requisicion["idrequisicion"]."")or die("ERROR EN EL UPDATE".mysql_error());
					
					$partidas_requisicion = mysql_query("select * from partidas_requisiciones where idrequisicion = ".$bus_relacion_compra_requisicion["idrequisicion"]."");
					while ($bus_partidas_requision = mysql_fetch_array($partidas_requisicion)){
						$sql_actualizar_partidas_requisicion = mysql_query("update maestro_presupuesto set pre_compromiso = pre_compromiso - ".$bus_partidas_requision["monto"]." where idRegistro = ".$bus_partidas_requision["idmaestro_presupuesto"]."")or die("ERROR EN EL UPDATE".mysql_error());
					
					}
					
				}			
				
				
				
				$sql_relacion_compra_solicitud = mysql_query("select * from relacion_compra_solicitud_cotizacion where idorden_compra = ".$id_orden_compra."");
				
				while ($bus_relacion_compra_solicitud = mysql_fetch_array($sql_relacion_compra_solicitud))
				{
					$sql_actualizar_solicitud = mysql_query("update solicitud_cotizacion set estado = 'ordenado', 
																						nro_orden = '".$codigo_orden."' 
																					where idsolicitud_cotizacion = ".$bus_relacion_compra_solicitud["idsolicitud_cotizacion"]."");
				}
				
				$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_compra."
														and tipo_movimiento = 'compromiso'");
				if (mysql_num_rows($sql_validar_asiento) > 0){	
					$sql_asiento_contable = mysql_query("update asiento_contable set estado = 'procesado',
																			fecha_contable = '".$fecha_validada."'
											 where iddocumento = ".$id_orden_compra."
											 		and tipo_movimiento = 'compromiso'")or die("error".mysql_error());
				}
				
				/*$sql_relacion_compra_requisicion = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."");
				
				while ($bus_relacion_compra_requisicion = mysql_fetch_array($sql_relacion_compra_requisicion))
				{
					$sql_actualizar_requisicion = mysql_query("update requisicion set estado = 'ordenado', 
																						nro_orden = '".$codigo_orden."' 
																					where idrequisicion = ".$bus_relacion_compra_requisicion["idrequision"]."");
				}*/	
					
					
					
					
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
													fecha_ultima_compra = '".$fecha_validada."' 
													where idarticulos_servicios = '".$bus_select_articulos_compra["idarticulos_servicios"]."'");
				}
				
					
					
					
					
					
					
				if($sql_actualizar_orden){
					$sql_actualizar_numero = mysql_query("update tipos_documentos set nro_contador = nro_contador + 1 where idtipos_documentos = ".$tipo_orden."")or die("uno".mysql_error());
					echo "exito";
					registra_transaccion("Procesar Orden de Compra y Servicio (".$codigo_orden.")",$login,$fh,$pc,'orden_compra_servicios');
				}else{
					echo "fallo";
				}
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
	
	$sql_configuracion = mysql_query("select fecha_cierre from configuracion");
	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	
	if(date("Y-m-d") > $bus_configuracion["fecha_cierre"]){
		$fecha_anulacion = $bus_configuracion["fecha_cierre"];
	}else{
		$fecha_anulacion = $fecha_anulacion_compromiso;
	}
	

	$sql_orden = mysql_query("update orden_compra_servicio 
											set estado = 'anulado',
											fecha_anulacion = '".$fecha_anulacion."'
											where 
											idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
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
															where idRegistro = ".$bus_sub_epe["idmaestro_presupuesto"]."")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 2: ".mysql_error());
							
						}
						
						$sql_clasificador = mysql_query("select * clasificador_presupuestario where idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."' and sub_especifica != '00'")or die("ERROR CONSULTANDO EL CLASIFICADOR ".mysql_error());
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
		
		$partidas_requisicion = mysql_query("select * from partidas_requisiciones where idrequisicion = ".$bus_relacion_requisicion["idrequisicion"]."");
			while ($bus_partidas_requision = mysql_fetch_array($partidas_requisicion)){
				$sql_actualizar_partidas_requisicion = mysql_query("update maestro_presupuesto set pre_compromiso = pre_compromiso + ".$bus_partidas_requision["monto"]."
											 where idRegistro = ".$bus_partidas_requision["idmaestro_presupuesto"]."")or die("ERROR EN EL UPDATE".mysql_error());
			
			}

	}
	
	$sql_relacion_solicitud = mysql_query("select * from relacion_compra_solicitud_cotizacion where idorden_compra = ".$id_orden_compra."");
	while($bus_relacion_solicitud = mysql_fetch_array($sql_relacion_solicitud)){
		$sql_insert_relacion_compras = mysql_query("update solicitud_cotizacion set estado = 'procesado' where idsolicitud_cotizacion = '".$bus_relacion_solicitud["idsolicitud_cotizacion"]."'");

	}
	
	$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_compra."
														and tipo_movimiento = 'compromiso'");
	if (mysql_num_rows($sql_validar_asiento) > 0){
		$sql_actualizar =mysql_query("update asiento_contable set reversado = 'si'
														where iddocumento = ".$id_orden_compra."
															and tipo_movimiento = 'compromiso'");
		
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
	}else{
		echo "claveIncorrecta";
	}
}


// *****************************************************************************************************************************************
// ************************************************* DUPLICAR ORDEN DE COMPRA Y SERVICIO ***************************************************
// *****************************************************************************************************************************************


if($ejecutar == "duplicarOrden"){
	$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
	$bus_orden = mysql_fetch_array($sql_orden);// DUPLICACION DE LOS DATOS BASICOS DE LA ORDEN DE COMPRA
	$sql_insert_orden = mysql_query("insert into orden_compra_servicio(tipo,
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
																		ordenado_por,
																		cedula_ordenado,
																		numero_requisicion,
																		fecha_requisicion,
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
																		cofinanciamiento,
																		tipo_carga_orden)value(
																						'".$bus_orden["tipo"]."',
																						'".date("Y-m-d")."',
																						'".$bus_orden["proceso"]."',
																						'".$bus_orden["numero_documento"]."',
																						'".$bus_orden["idbeneficiarios"]."',
																						'".$bus_orden["idcategoria_programatica"]."',
																						'".$bus_orden["anio"]."',
																						'".$bus_orden["idfuente_financiamiento"]."',
																						'".$bus_orden["idtipo_presupuesto"]."',
																						'".$bus_orden["justificacion"]."',
																						'".$bus_orden["observaciones"]."',
																						'".$bus_orden["ordenado_por"]."',
																						'".$bus_orden["cedula_ordenado"]."',
																						'".$bus_orden["numero_requisicion"]."',
																						'".$bus_orden["fecha_requisicion"]."',
																						'".$bus_orden["nro_items"]."',
																						'".$bus_orden["sub_total"]."',
																						'".$bus_orden["sub_total_original"]."',
																						'".$bus_orden["impuesto"]."',
																						'".$bus_orden["total"]."',
																						'".$bus_orden["exento"]."',
																						'".$bus_orden["exento_original"]."',
																						'elaboracion',
																						'".$bus_orden["idrazones_devolucion"]."',
																						'".$bus_orden["numero_remision"]."',
																						'".$bus_orden["fecha_remision"]."',
																						'".$bus_orden["recibido_por"]."',
																						'".$bus_orden["cedula_recibido"]."',
																						'".$bus_orden["fecha_recibido"]."',
																						'0',
																						'".$bus_orden["status"]."',
																						'".$login."',
																						'".date("Y-m-d H:i:s")."',
																						'".$bus_orden["duplicados"]."',
																						'".$bus_orden["nro_factura"]."',
																						'".$bus_orden["fecha_factura"]."',
																						'".$bus_orden["nro_control"]."',
																						'".$bus_orden["cofinanciamiento"]."',
																						'".$bus_orden["tipo_carga_orden"]."')")or die(mysql_error());
	$nueva_orden_compra = mysql_insert_id();
	$sql_articulos = mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
	while($bus_articulos = mysql_fetch_array($sql_articulos)){// DUPLICACION DE LOS ARTICULOS
		$sql_insert_articulos = mysql_query("insert into articulos_compra_servicio(idorden_compra_servicio,
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


	$sql_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
	while($bus_partidas = mysql_fetch_array($sql_partidas)){// DUPLICAR LAS PARTIDAS
		$sql_insert_partidas = mysql_query("insert into partidas_orden_compra_servicio(idorden_compra_servicio,
																						idmaestro_presupuesto,
																						monto,
																						estado,
																						status,
																						usuario,
																						fechayhora)values('".$nueva_orden_compra."',
																									'".$bus_partidas["idmaestro_presupuesto"]."',
																									'".$bus_partidas["monto"]."',
																									'".$bus_partidas["estado"]."',
																									'".$bus_partidas["status"]."',
																									'".$login."',
																									'".date("Y-m-d H:i:S")."')");
	
	}

	$sql_relacion_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where idorden_compra_servicio = ".$id_orden_compra."");
	while($bus_relacion_impuestos = mysql_fetch_array($sql_relacion_impuestos)){
		$sql_insert_relacion_impuestos = mysql_query("insert into relacion_impuestos_ordenes_compras(idorden_compra_servicio,
																								idimpuestos,
																								porcentaje,
																								total,
																								estado)values(
																								'".$nueva_orden_compra."',
																								'".$bus_relacion_impuestos["idimpuestos"]."',
																								'".$bus_relacion_impuestos["porcentaje"]."',
																								'".$bus_relacion_impuestos["total"]."',
																								'".$bus_relacion_impuestos["estado"]."')");
		
	}
	
	$sql_relacion_compra = mysql_query("select * from relacion_compra_solicitud_cotizacion where idorden_compra = ".$id_orden_compra."");
	while($bus_relacion_compra = mysql_fetch_array($sql_relacion_compra)){
		$sql_insert_relacion_compras = mysql_query("insert into relacion_compra_solicitud_servicio(idorden_compra_solicitud,
																									idsolicitud_cotizacion)values(
																									'".$nueva_orden_compra."',
																									'".$bus_relacion_compra["idsolicitud_cotizacion"]."')");

	}
	
	
	$sql_relacion_requisicion = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."");
	while($bus_relacion_requisicion = mysql_fetch_array($sql_relacion_requisicion)){
		$sql_insert_relacion_compras = mysql_query("insert into relacion_compra_requisicion(idorden_compra_solicitud,
																					idrequisicion)values(
																					'".$nueva_orden_compra."',
																					'".$bus_relacion_requisicion["idrequisicion"]."')");

	}
	
	$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_compra."
														and tipo_movimiento = 'compromiso'");
	if (mysql_num_rows($sql_validar_asiento) > 0){	
		$sql_asiento_contable = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_compra." and tipo_movimiento='compromiso'")or die("aqui asiento ".mysql_error());
		$bus_asiento_contable = mysql_fetch_array($sql_asiento_contable)or die("aqui asiento ".mysql_error());
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
																			'".$bus_asiento_contable["idfuente_financiamiento"]."',
																			'".$bus_asiento_contable["detalle"]."',
																			'compromiso',
																			'".$nueva_orden_compra."',
																			'elaboracion',
																			'a',
																			'".$login."',
																			'".date("Y-m-d H:i:s")."',
																			'2')")or die("aqui insert ".mysql_error());
		
		if($sql_contable){
			$idasiento_contable = mysql_insert_id();
			$sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'");
			
			while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)){
				$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																tabla,
																idcuenta,
																afecta,
																monto
																	)values(
																			'".$idasiento_contable."',
																			'".$bus_cuentas_contables["tabla"]."',
																			'".$bus_cuentas_contables["idcuenta"]."',
																			'".$bus_cuentas_contables["afecta"]."',
																			'".$bus_cuentas_contables["monto"]."')");
			}
		
		}
	}
	registra_transaccion("Duplicar Orden Compra (Id Anterior:".$id_orden_compra.", Id Nuevo:".$nueva_orden_compra.")",$login,$fh,$pc,'orden_compra_servicios');
	echo $nueva_orden_compra;
}


// **************************************************************************************************************************************************
// **********************************************         INGRESAR PARTIDA INDIVIDUAL        ********************************************************
// **************************************************************************************************************************************************



if($ejecutar == "ingresarMaterialIndividual"){
	$sql = mysql_query("select * from articulos_compra_servicio where idarticulos_servicios = ".$id_material." and idorden_compra_servicio = ".$id_orden_compra."");
	$num = mysql_num_rows($sql);
	// SI EL ARTICULO NO EXISTE ENTRE LOS ARTICULOS CARGADOS
	if($num == 0){

		$total_articulo_individual = $cantidad * $precio_unitario;
		$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
		$bus_orden = mysql_fetch_array($sql_orden);
		
		
		$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id_material."'");
		$bus_ordinal = mysql_fetch_array($sql_ordinal);
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
		
		$sql = mysql_query("insert into articulos_compra_servicio (idorden_compra_servicio,
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
																	'".$id_orden_compra."',
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
		$actualiza_totales = mysql_query("update orden_compra_servicio set 	
											sub_total = sub_total + '".$total_articulo_individual."',
											sub_total_original = sub_total_original + '".$total_articulo_individual."',
											impuesto = impuesto + '".$total_impuesto_individual."',
											exento = exento + '".$exento."',
											exento_original = exento_original + '".$exento."',
											total = total + '".$total_articulo_individual."' + '".$total_impuesto_individual."' + '".$exento."'
																					where idorden_compra_servicio=".$id_orden_compra." ")or die ("11111111 ".mysql_error());
		
		if ($destino_partida<>0 and $contribuyente_ordinario=="si"){ // SI EL IMPUESTO TIENE PARTIDA PROPIA
			$sql_existe_partida=mysql_query("select * from relacion_impuestos_ordenes_compras 
																	where idorden_compra_servicio=".$id_orden_compra." 
																		and idimpuestos=".$id_impuestos."")or die("222222 ".mysql_error());
			$num=mysql_num_rows($sql_existe_partida); // VERIFICO SI ESE IMPUESTO YA FUE INGRESADO A LA TABLA DE RELACION DE IMPUESTOS CON ORDEN DE COMPRA
			if ($num==0) {
				$sql2 = mysql_query("insert into relacion_impuestos_ordenes_compras (idorden_compra_servicio,
																					idimpuestos,
																					base_calculo,
																					base_calculo_original,
																					porcentaje,
																					total)
																			value(
																					".$id_orden_compra.",
																					".$id_impuestos.",
																					'".$total_articulo_individual."',
																					'".$total_articulo_individual."',
																					".$porcentaje_impuesto.",
																					'".$total_impuesto_individual."'
																					)")or die("ERRORRRRRRRRRRRR ".mysql_error());
			}else {
				// SI YA EXISTE EN LA TABLA LE SUMO EL IMPUESTO DEL NUEVO ARTICULO AL TOTAL
				$sql2=mysql_query("update relacion_impuestos_ordenes_compras set 
				base_calculo = base_calculo + '".$total_articulo_individual."',
				base_calculo_original = base_calculo_original + '".$total_articulo_individual."',
				total=total+".$total_impuesto_individual." where idorden_compra_servicio = '".$id_orden_compra."'")or die("3333333 ".mysql_error());
			}
			
			// VALIDO LA PARTIDA DEL IMPUESTO EXISTA EN EL MAESTRO DE PRESUPUESTO 
			

			$sql_ordinal_impuesto= mysql_query("select * from ordinal where codigo='0000'");
			$bus_ordinal_impuesto= mysql_fetch_array($sql_ordinal_impuesto);

			
			
			// *********************************************************************************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			
			$sql_maestro_impuestos = consultarMaestro($anio, $bus_orden["idcategoria_programatica"], $id_clasificador_impuestos, $fuente_financiamiento, $tipo_presupuesto, $bus_ordinal_impuesto["idordinal"], $cofinanciamiento);
			while($bus_maestro = mysql_fetch_array($sql_maestro_impuestos)){
			$num_maestro_impuesto = mysql_num_rows($sql_maestro_impuestos);
			if($num_maestro_impuesto > 0){ // valido que exista una partida para el impuesto
				// obtengo el disponible de la partida para compararlo con el total de impuesto y saber si existe disponibilidad
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				
				
				$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where 
																		idorden_compra_servicio = ".$id_orden_compra." 
																	and idimpuestos = ".$id_impuestos."");
				$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
				$total_impuesto_imputable = $bus_total_impuestos["total"];	
						
				if($total_impuesto_imputable > $disponible){
					$estado_partida="sobregiro"; // si no tiene disponibilidad cambio el estado para colorearlo de AMARILLO
					$sql_update_estado_relacion_impuestos = mysql_query("update relacion_impuestos_ordenes_compras set estado = 'sin disponibilidad' 
																			where idorden_compra_servicio = ".$id_orden_compra."");
				}else{
					$estado_partida="disponible"; // si existe disponibilidad coloco el estado como DISPONIBLE para que aparezca en color normal
					$sql_update_estado_relacion_impuestos = mysql_query("update relacion_impuestos_ordenes_compras set estado = 'disponible' 
																			where idorden_compra_servicio = ".$id_orden_compra."");
				}
				// BUSCO LA PARTIDA DEL IMPUESTO EN LAS PARTIDAS DE LA ORDEN DE COMPRA 
				$sql_partidas_orden_compra=mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio=".$id_orden_compra." 
																		and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."") 
																	or die("AQUI ESTA EL ERROR:".mysql_error());
				$num=mysql_num_rows($sql_partidas_orden_compra);
				if ($num==0){ // SI NO EXISTE LA PARTIDA EN LA TABLA DE PARTIDAS DE LA ORDEN DE COMPRA LA AGREGO
					$ingresar_partida=mysql_query("insert into partidas_orden_compra_servicio (idorden_compra_servicio, 
																								idmaestro_presupuesto,
																								monto,
																								monto_original,
																								estado,
																								status,
																								usuario,
																								fechayhora) 
																							values (".$id_orden_compra.",
																									".$bus_maestro["idRegistro"].",
																									".$total_impuesto_individual.",
																									".$total_impuesto_individual.",
																									'".$estado_partida."',
																									'a',
																									'".$login."',
																									'".date("Y-m-d H:i:s")."')")
																								or die("4444444 ".mysql_error());
				}else{ // SI YA EXISTE LA PARTIDA, LE ACTUALIZO EL ESTADO Y EL TOTAL DE IMPUESTO IMPUTADO A ESA PARTIDA
					$actualiza_partida=mysql_query("update partidas_orden_compra_servicio set 
																		monto = monto + ".$total_impuesto_individual." ,
																		monto_original = monto_original + ".$total_impuesto_individual." ,
																		estado='".$estado_partida."' 
																		where idorden_compra_servicio=".$id_orden_compra." 
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")
																		or die ("555 ".mysql_error());
				}	
			}else{ // SI NO EXISTE PARTIDA PARA EL IMPUESTO LO COLOCA COMO RECHAZADO PARA COLOREARLO DE ROJO
				$sql_update_estado_relacion_impuestos = mysql_query("update relacion_impuestos_ordenes_compras set estado = 'rechazado' 
																	where idorden_compra_servicio = ".$id_orden_compra."");
			} // CIERRO LA VALIDACION PARA SABER SI TIENE PARTIDA EN EL MAESTRO DE PRESUPUESTO
			
			}
		} // CIERRO LA VALIDACION DE SI EL IMPUESTO TIENE PARTIDA PROPIA
		
		
		$sql_articulos = mysql_query("select * from articulos_servicios where idarticulos_servicios = ".$id_material."");
		$bus_articulos = mysql_fetch_array($sql_articulos);
		// 	se realiza la consulta en la tabla maestro para verificar si hay partidas para este articulo, de lo contrario el articulo se coloca en un estado de rechazado para que
		//	en la tabla aparesca la fila en rojo y muestre al usuario que no hay partidas para ese articulo
		
		
		
		// *********************************************************************************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			
			$sql_maestro = consultarMaestro($anio, $bus_orden["idcategoria_programatica"], $id_clasificador_presupuestario, $fuente_financiamiento, $tipo_presupuesto, $idordinal, $cofinanciamiento);
		
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
				
				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_compra_servicio.exento) as exentos from articulos_compra_servicio, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra."");
				// SUMO EL TOTAL DE TODOS LOS ARTICULOS QUE ESTAN IMPUTANDO ESA PARTIDA PARA COMPARARLO CON EL DISPONIBLE EN EL MAESTRO DE PRESUPUESTO
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"]; 
				
				if ($destino_partida == 0 and $contribuyente_ordinario=="si")	{// valido que el impuesto se sume a la partida o si tiene partida propia
					$sql_impuesto_imputable = mysql_query("select SUM(impuesto) as totales_impuestos from articulos_compra_servicio, 
																				articulos_servicios 
																			where
										articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
										and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
										and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." 
										and idpartida_impuesto = 0");
					$bus_impuesto_imputable = mysql_fetch_array($sql_imputable);
					$total_impuesto_imputable = $bus_impuesto_imputable["totales_impuestos"];
					$total_imputable = $total_imputable + $total_impuesto_imputable;
					$total_articulo_individual = $total_articulo_individual + $total_impuesto_individual;
				}
				if($total_imputable > $disponible){ // si el total a imputar es mayor al disponible en la partida
					$estado = "sin disponibilidad";
					$estado_partida = "sobregiro";
				}else{
					//si nada de esto sucede se coloca el estado en aprobado y el material se muestra normalmente
					$estado = "aprobado";
					$estado_partida = "disponible";
				}
				/*echo "select * from partidas_orden_compra_servicio where idorden_compra_servicio=".$id_orden_compra." 
																		and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."";*/
				
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
				
				
				
				
					
				$sql_partidas_orden_compra=mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio=".$id_orden_compra." 
																		and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."") 
																	or die("66666 ".mysql_error());
				$num=mysql_num_rows($sql_partidas_orden_compra);
				
				if ($num==0){ // SI NO EXISTE LA PARTIDA LA INGRESO
					$ingresar_partida=mysql_query("insert into partidas_orden_compra_servicio (idorden_compra_servicio, 
																								idmaestro_presupuesto,
																								monto,
																								monto_original,
																								estado,
																								status,
																								usuario,
																								fechayhora) 
																							values (".$id_orden_compra.",
																									".$bus_maestro["idRegistro"].",
																									".$total_imputable_nuevo.",
																									".$total_imputable_nuevo.",
																									'".$estado_partida."',
																									'a',
																									'".$login."',
																									'".date("Y-m-d H:i:s")."')")
																								or die("ERROR GUARDANDO PARTIDAS:". mysql_error());
				}else{ // DE LO CONTRARIO LA ACTUALIZO
					$actualiza_partida=mysql_query("update partidas_orden_compra_servicio set 
																		monto = ".$total_imputable_nuevo.",
																		monto_original = ".$total_imputable_nuevo.",
																		estado='".$estado_partida."' 
																		where idorden_compra_servicio=".$id_orden_compra." 
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")
																		or die ($total_item."ERROR MODIFICANDO PARTIDAS: ".mysql_error());
				}														
	
			}
			}
			// actualizo el estado del material ingresado				
			$sql_update_articulos_compras = mysql_query("update articulos_compra_servicio set estado = '".$estado."' 
																where idarticulos_compra_servicio = ".$id_ultimo_generado."");



		
		if($sql){
		registra_transaccion("Ingresar Material Individual en Orden de Compra (".$id_ultimo_generado.")",$login,$fh,$pc,'orden_compra_servicios');
			echo "exito";
		}else{
			echo "fallo";
		}
}else{
echo "existe";
}
}





if($ejecutar == "consultarTipoCarga"){
$sql_orden_compra = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
$bus_orden_compra = mysql_fetch_array($sql_orden_compra);
echo $bus_orden_compra["tipo_carga_orden"];
}
//if($ejecutar == "")



if($ejecutar == "actualizarTipoCargaOrden"){
	$sql_actualizar_ordne = mysql_query("update orden_compra_servicio set tipo_carga_orden = '".$tipo_carga_orden."' where idorden_compra_servicio = '".$id_orden_compra."'");
}

?>