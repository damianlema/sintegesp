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

	//$anio." , ".$idcategoria_programatica." , ".$idclasificador_presupuestario." , ".$idfuente_financiamiento." , ".$idtipo_presupuesto." , ".$idordinal." , ".$cofinanciamiento;

	return $sql_maestro;
}










//*******************************************************************************************************************************************
//********************************************* INGRESAR MATERIALES A LA ORDEN DE COMPRA ****************************************************
//*******************************************************************************************************************************************

if($ejecutar == "materiales"){
//si la accion a ejecutar es ditinta a consultar, entonces procede a agregar o eliminar de acuerdo a la opcion que venga
	if($accion != "consultar"){
	// si la accion que viene es ingresar desde cotizacion se procede a consultar los datos de la solicitud para ingresar los materiales asociados
	
		if($accion == "eliminar"){ // ACCION PARA ELIMINAR UN ARTICULO DE LA ORDEN DE COMPRA
		
			// si la accion es eliminar se hacen varias consultas para eliminar los articulos de la tabla articulos_compra_servicio, ademas se elimina la relacion del 
			// articulo con la solicitud y se crean variables para luego verificar si ya no hay mas articulos por una solicitud para que la solicitud se deseleccione de 
			// la lista de solicitudes del proveedor
			
			$sql_material_eliminar = mysql_query("select * from articulos_compra_servicio where idarticulos_compra_servicio = '".$id_material."'");
			$bus_material_eliminar = mysql_fetch_array($sql_material_eliminar);
			
			
			
			$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_material_eliminar["idarticulos_servicios"]."'");
			$bus_ordinal = mysql_fetch_array($sql_ordinal);
			$idordinal = $bus_ordinal["idordinal"];
			
			
			$sql_articulos_servicios = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_material_eliminar["idarticulos_servicios"]."'");
			$bus_articulos_servicios = mysql_fetch_array($sql_articulos_servicios);
			
			$id_categoria_programatica = $bus_material_eliminar["idcategoria_programatica"];
			$id_clasificador_presupuestario = $bus_articulos_servicios["idclasificador_presupuestario"];
			
			
			$sql = mysql_query("delete from articulos_compra_servicio where idarticulos_compra_servicio = ".$id_material." and idorden_compra_servicio = '".$id_orden_compra."' and idcategoria_programatica = '".$id_categoria_programatica."'")or die(mysql_error());

			$sql_todos_articulos = mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
			$num_todos_articulos = mysql_num_rows($sql_todos_articulos);
			if($num_todos_articulos == 0){
				// si no existen mas articulos en la orden de compra elimino los registros de esa orden en el resto de las tablas		
				$sql = mysql_query("delete from partidas_orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
				// ACTUALIZO LOS TOTALES EN LA TABLA ORDEN_COMPRA_SERVICIO
				$sql_actualiza_totales = mysql_query("update orden_compra_servicio set 	sub_total = 0,
																						impuesto = 0,
																						exento = 0,
																						total = 0
																where idorden_compra_servicio=".$id_orden_compra." ")or die (mysql_error());
			}else{
				//echo $impuesto_por_producto;
				// ACTUALIZO LOS TOTALES EN LA TABLA ORDEN_COMPRA_SERVICIO
				$sql_actualiza_totales = mysql_query("update orden_compra_servicio set 
																sub_total = sub_total - '".$bus_material_eliminar["total"]."',
																impuesto = impuesto - '".$bus_material_eliminar["impuesto"]."',
																exento = exento - '".$bus_material_eliminar["exento"]."',
total = total - '".$bus_material_eliminar["impuesto"]."' - '".$bus_material_eliminar["total"]."' - '".$bus_material_eliminar["exento"]."'
														where idorden_compra_servicio=".$id_orden_compra." ")or die (mysql_error());
																			
				// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
				$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
				$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
		
				
				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_compra_servicio.exento) as exentos 
									from articulos_compra_servicio, articulos_servicios 
								where
									articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
									and articulos_compra_servicio.idcategoria_programatica = '".$id_categoria_programatica."'
									and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
									and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." ");
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"];
				
				
				
				// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************

			
			$sql_maestro = colsultarMaestro($anio, $id_categoria_programatica, $id_clasificador_presupuestario, $fuente_financiamiento, $tipo_presupuesto, $idordinal, $cofinanciamiento);	
				
				while($bus_maestro = mysql_fetch_array($sql_maestro)){
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				//$disponible_actual = $bus_maestro["monto_actual"] ;
				//$disponible_resta = $bus_maestro["total_compromisos"] + $bus_maestro["pre_compromiso"] + $bus_maestro["reservado_disminuir"];
				//$disponible = bcsub ( $disponible_actual, $disponible_resta , 2);
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
				
				
				
				if($total_imputable_nuevo > $disponible){ // si el total imputable es mayor al disponible
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
																	monto = '".$total_imputable_nuevo."' 
																where 
																	idorden_compra_servicio = ".$id_orden_compra."
																	and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
				}else{	// si el total imputable es menor o igual al disponible
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
																	monto = '".$total_imputable_nuevo."' 
																where 
																	idorden_compra_servicio = ".$id_orden_compra."
																	and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
				}
					
							
				$sql = mysql_query("delete from partidas_orden_compra_servicio 
																		where 
																		idorden_compra_servicio = ".$id_orden_compra."
																		and monto <= 0");	
				// ********************************************* ELIMINAR PARTIDAS ************************************************
				
			}	
			}// CIERRE SI NO ES ARTICULO UNICO
		}
	}

									  
	$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
	$bus_orden = mysql_fetch_array($sql_orden);
	$sql_articulos_orden_compra_servicio = mysql_query("select * from articulos_compra_servicio,  
																		unidad_medida, 
																		articulos_servicios, 
																		categoria_programatica
									 where 
									 	articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." 
									  	and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios 
										and articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida
										and categoria_programatica.idcategoria_programatica = articulos_compra_servicio.idcategoria_programatica 
										order by categoria_programatica.codigo,articulos_compra_servicio.idarticulos_compra_servicio");
	
	$num = mysql_num_rows($sql_articulos_orden_compra_servicio);
	if($num != 0){
		// si existen articulos en la orden los muestra
	?>
    
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
            <td class="Browse"><div align="center">Descripcion</div></td>
            <td class="Browse"><div align="center">Cantidad</div></td>
            <td class="Browse"><div align="center">Precio Unitario</div></td>
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
			//var_dump($bus_articulos_orden_compra_servicio);
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
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
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
			<?
            if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 1){
				echo "Asignaci&oacute;n";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 2){
				echo "Deducci&oacute;n";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 4){
				echo "Aporte";
			}
			?></td>
            <td class='Browse' align='left'><?=$bus_articulos_orden_compra_servicio[30]?>
              <div align="right"></div></td>
            <td class='Browse' align='right'>
			  <div align="right">
			    <?
            if($bus_orden["estado"] == "elaboracion"){
            ?>
			    <input align="right" style="text-align:right" name="cantidad<?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>" 
            												type="text" 
                                                            id="cantidad<?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>" 
                                                            size="10"
                                                            value="<?=$bus_articulos_orden_compra_servicio["cantidad"]?>" />
			    <?
            }else{
			echo number_format($bus_articulos_orden_compra_servicio["cantidad"],2,',','.');
			}
			?>			
		        </div></td>
            <td class="Browse" align='right'>
			  <div align="right">
			    <?
            if($bus_orden["estado"] == "elaboracion"){
            ?>
			    <input align="right" style="text-align:right" name="precio<?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>" 
            												type="hidden" 
                                                            id="precio<?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>" 
                                                            size="10"
                                                            value="<?=$bus_articulos_orden_compra_servicio["precio_unitario"]?>">
			    <input align="right" style="text-align:right" name="mostrarPrecio<?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>" 
            												type="text" 
                                                            id="mostrarPrecio<?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>" 
                                                            size="13"
                                                            onclick="this.select()"
                                                            value="<?=number_format($bus_articulos_orden_compra_servicio["precio_unitario"],2,',','.')?>"
                                                            onblur="formatoNumero(this.name, 'precio<?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>')">
			    <input type="hidden" name="eliminoSolicitud" id="eliminoSolicitud" value="<?=$eliminoSolicitud?>">
                <?
            }else{
			echo number_format($bus_articulos_orden_compra_servicio["precio_unitario"],2,',','.');
			}
			?>            
		      </div></td>
          <td class="Browse" align="right">
				<? if($bus_articulos_orden_compra_servicio["total"] == 0){
                	echo number_format($bus_articulos_orden_compra_servicio[9],2,',','.');
                }else{
					echo number_format($bus_articulos_orden_compra_servicio["total"],2,',','.');
                }
                ?>             </td>
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
                                actualizarPrecioCantidad(<?=$bus_articulos_orden_compra_servicio["idorden_compra_servicio"]?>, 
                                document.getElementById('precio<?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>').value,
                                document.getElementById('cantidad<?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>').value, 
                                <?=$bus_articulos_orden_compra_servicio["idarticulos_servicios"]?>, 
                                <?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>, 
                                document.getElementById('anio').value,
                                document.getElementById('fuente_financiamiento').value,
                                document.getElementById('tipo_presupuesto').value,
                                document.getElementById('id_ordinal').value,
                                document.getElementById('contribuyente_ordinario').value)" 
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
//$idcategoria_programatica=4;
//echo "tipo ".$tipo_orden." id_beneficiarios ".$id_beneficiarios." categoria ".$idcategoria_programatica." año ".$anio." fuente ".$fuente_financiamiento." tipo ".$tipo_presupuesto." ordi ".$ordinal;
	
	
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
}

$bus_dependencia = mysql_fetch_array($sql_dependencia);
$iddependencia = $bus_dependencia["iddependencia"];
	$sql = mysql_query("insert into orden_compra_servicio (tipo,
															fecha_elaboracion,
															idbeneficiarios,
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
															iddependencia,
															cofinanciamiento)values(
																					'".$tipo_orden."',
																					'".$fecha_validada."',
																					'".$id_beneficiarios."',
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
																					'".$iddependencia."',
																					'".$cofinanciamiento."')")or die("ERROR INSERTANDO LA ORDEN DE COMPRA  ".mysql_error());
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
	
	
	
	
	registra_transaccion("Ingresar datos Basicos de Certificacion RRHH (".$iddocumento.")",$login,$fh,$pc,'orden_compra_servicios');
}else{
	registra_transaccion("Ingresar datos Basicos de Certificacion RRHH ERROR",$login,$fh,$pc,'orden_compra_servicios');
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
            <td class='Browse' align='right'><?=$bus2["total"]?></td>
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
            <td class='Browse' align='right'><?=$bus2["total"]?></td>
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

				
				$sql_articulos_servicios = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id_articulo."'")or die(mysql_error());
				$bus_articulos_servicios = mysql_fetch_array($sql_articulos_servicios);
				
				$idordinal = $bus_articulos_servicios["idordinal"];
				
				
				$sql_articulos_compra_servicio = mysql_query("select * from articulos_compra_servicio where idarticulos_compra_servicio = '".$id_articulo_compra."'");
				$bus_articulos_compra_servicio = mysql_fetch_array($sql_articulos_compra_servicio);
				
				$id_categoria_programatica = $bus_articulos_compra_servicio["idcategoria_programatica"];
				$id_clasificador_presupuestario = $bus_articulos_servicios["idclasificador_presupuestario"];
			//echo $id_clasificador_presupuestario;
			
			$total_articulo_individual = $cantidad * $precio;
//			echo "TOTAL ARTICULO INDIVIDUAL: ".$total_articulo_individual;
			
			//echo "TIPO CONCEPTO: ".$bus_articulos_servicios["tipo_concepto"]."<br />";
			if($bus_articulos_servicios["tipo_concepto"] == 1){
				$monto_total = $total_articulo_individual;
				$exento = 0; 
			}else if($bus_articulos_servicios["tipo_concepto"] == 4){
				$monto_total = $total_articulo_individual;
				$exento = 0;
				$tipo_aporte ='S';
			}else if($bus_articulos_servicios["tipo_concepto"] == 2){
				$monto_total = 0;
				$exento = $total_articulo_individual;
			}
			
			// busco el precio y la cantidad anteriores para restarsela a los totales
			$sql_orden_compra_viejo = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
			$bus_orden_compra_viejo = mysql_fetch_array($sql_orden_compra_viejo);
		
			$exento_viejo = $bus_orden_compra_viejo["exento"];
			$sub_total_viejo = $bus_orden_compra_viejo["sub_total"];
			
			
			
			// actualizo la tabla de articulos de la orden de compra con la nueva cantidad y el nuevo precio										
			$sql2 = mysql_query("update articulos_compra_servicio set total = '".$monto_total."', 
																	  precio_unitario = '".$precio."', 
																	  cantidad = '".$cantidad."',
																	  exento = '".$exento."'
																	  where 
																	  idarticulos_compra_servicio = ".$id_articulo_compra."")or die("3: ".mysql_error());
		
			
			// ACTUALIZO LOS TOTALES EN LA TABLA ORDEN_COMPRA_SERVICIO
			$total_anterior = $sub_total_viejo - $exento_viejo;
			$total_nuevo = $monto_total - $exento;
			$sql_actualiza_totales = mysql_query("update orden_compra_servicio set 
														sub_total = sub_total - '".$sub_total_viejo."' + '".$monto_total."',
														exento = exento - '".$exento_viejo."' + '".$exento."',
														total = total - '".$total_anterior."' + '".$total_nuevo."'
														where idorden_compra_servicio=".$id_orden_compra." ")or die("4: ".mysql_error());
			
			
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
			
			$sql = mysql_query("select * from articulos_compra_servicio where 
													idarticulos_compra_servicio = ".$id_articulo_compra."")or die("5: ".mysql_error());
			$bus = mysql_fetch_array($sql);
			if($bus["estado"] == "aprobado" || $bus["estado"] == "sin disponibilidad"){ // en cualquiera de stos estados el articulo tiene partida en el maestro
					$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where 
														idorden_compra_servicio = ".$id_orden_compra."")or die("6: ".mysql_error());
					$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
					//echo "ID: ".$bus_compra_servicio["idcategoria_programatica"]." ";

					$partida_impuestos = 0;


	
				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_compra_servicio.exento) as exentos from articulos_compra_servicio, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_compra_servicio.idcategoria_programatica = '".$id_categoria_programatica."'
											and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." ")or die("12: ".mysql_error());
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"];
				//echo $bus_imputable["totales"];
				//echo $bus_imputable["exentos"];


	if($bus_articulos_servicios["tipo_concepto"] == 1 or $bus_articulos_servicios["tipo_concepto"] == 4){

	
	
			// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************

			
			$sql_maestro = colsultarMaestro($anio, $id_categoria_programatica, $id_clasificador_presupuestario, $idfuente_financiamiento, $idtipo_presupuesto, $idordinal, $cofinanciamiento);	
				

				
				while($bus_maestro = mysql_fetch_array($sql_maestro)){
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
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
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
																		monto = '".$total_imputable_nuevo."' 
																		where 
																		idorden_compra_servicio = ".$id_orden_compra."
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("15: ".mysql_error());
					$estado = "sin disponibilidad";
				}else{
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
																			monto = '".$total_imputable_nuevo."' 
																			where 
																			idorden_compra_servicio = ".$id_orden_compra."
																			and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("16: ".mysql_error());
				
					$estado = "aprobado";
				}
				
				}
				
			}else{
				$estado = "aprobado";
			}
		}else{
			$estado = "aprobado";
		}
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA		
			
			$sql2 = mysql_query("update articulos_compra_servicio set estado = '".$estado."' 
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
if($ejecutar == "actualizarDatosBasicos"){
	if($accion == "actualizar"){
	//echo "BENEFICIARIO: ".$id_beneficiarios;
		$sql = mysql_query("update orden_compra_servicio set tipo = '".$tipo_orden."',
															idbeneficiarios = '".$id_beneficiarios."',
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
															cofinanciamiento = '".$cofinanciamiento."'
														where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());


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


														
	registra_transaccion("Actualizar Datos Basicos de Certificacion RRHH (".$id_orden_compra.")",$login,$fh,$pc,'orden_compra_servicios');

	}
//echo "select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."<br />";
$sql = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
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
		$bus_relacion_pago = mysql_fetch_array($sql_relacion_pago);
		$sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_relacion_pago["idorden_pago"]."'");
		$bus_orden_pago = mysql_fetch_array($sql_orden_pago);
		$sql_cheque = mysql_query("select * from pagos_financieros where idorden_pago = '".$bus_orden_pago["idorden_pago"]."'");
		$bus_cheque = mysql_fetch_array($sql_cheque);
	  	$mostrar_estado = "Pagado : ".$bus_orden_pago["numero_orden"]." : ".$bus_orden_pago["fecha_orden"]." : ".$bus_cheque["numero_cheque"]." : ".$bus_cheque["fecha_cheque"];
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
		$id_orden_compra									."|.|";
																

															
}


//*******************************************************************************************************************************************
//********************************************* ACTUALIZAR LISTA DE TOTALES DE LAS ORDENES DE COMPRA ***************************************
//*******************************************************************************************************************************************
if($ejecutar == "actualizarListaDeTotales"){

	/*$sql = mysql_query("select * from articulos_compra_servicio as acs, articulos_servicios as aser
							where acs.idorden_compra_servicio = ".$id_orden_compra."
							and acs.idarticulos_servicios = aser.idarticulos_servicios
							and aser.tipo_concepto=4")or die(mysql_error());
	if (isset($sql)){ 
		$existe_aporte = mysql_num_rows($sql)or die(mysql_error());
	}else{
		$existe_aporte = 0;
	}

*/
	

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

	$sql_suma_aportes = mysql_query("select SUM(articulos_compra_servicio.total) as total_aportes
											from 
											articulos_servicios, 
											articulos_compra_servicio 
											where 
											articulos_compra_servicio.idorden_compra_servicio = '".$id_orden_compra."'
											and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios
											and articulos_servicios.tipo_concepto = 4")or die(mysql_error());
	$bus_suma_aportes = mysql_fetch_array($sql_suma_aportes);

	$sub_total_aportes = $bus_suma_aportes["total_aportes"];
	$sub_total = $bus_suma_asignaciones["total_asignaciones"];
	$exento = $bus_suma_deducciones["total_deducciones"];
	$total_general = $bus_suma_aportes["total_aportes"] +$bus_suma_asignaciones["total_asignaciones"] - $bus_suma_deducciones["total_deducciones"];

	$sub_total_actualizar = $bus_suma_aportes["total_aportes"] +$bus_suma_asignaciones["total_asignaciones"];
	$sql_actualizar_compra_servicio = mysql_query("update orden_compra_servicio 
														set exento = '".$bus_suma_deducciones["total_deducciones"]."',
														sub_total = '".$sub_total_actualizar."',
														total = '".$total_general."'
														where 
													idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR EN LA ACTUALIZACION DE LOS TOTALES ".mysql_error());
																		

	if($sub_total_aportes > 0){ ?>
		<b>Aportes:</b> <?=number_format($sub_total_aportes,2,',','.')?> | 
	<?
	}else{ ?>
		<b>Asignaciones:</b> <?=number_format($sub_total,2,',','.')?> | 
	<?
	}					
	?>

    
	<b>Deducciones:</b> <?=number_format($exento,2,',','.')?> |
    <b>Total Bs:</b> <?=number_format($total_general,2,',','.')?>
    <?
}








if($ejecutar == "actualizarDescuento"){
	$sql_orden_compra = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
	$bus_orden_compra = mysql_fetch_array($sql_orden_compra);
	$monto_total_orden = $bus_orden_compra["sub_total"];
	$porcentaje_descuento = ($descuento*100)/$monto_total_orden;
	$sql_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
		while($bus_partidas = mysql_fetch_array($sql_partidas)){
			$monto_total_actualizar = ($bus_partidas["monto"]*$porcentaje_descuento)/100;
			$sql_actualizar_partida = mysql_query("update partidas_orden_compra_servicio set monto = '".$monto_total_actualizar."'
										where idpartidas_orden_compra_servicio = '".$bus_partidas["idpartidas_orden_compra_servicio"]."'");
		}
	$exento = $bus_orden_compra["exento"];
	$sub_total = $bus_orden_compra["sub_total"]-$descuento;
	// FALTA ACTUALIZAR EL IMPUESTO
	$sql_actualizar_orden = mysql_query("update orden_compra_servicio 
																set exento = '".$exento."',
																sub_total = '".$sub_total."'
																descuento = '".$descuento."'
																where idorden_compra_servicio = '".$id_orden_compra."'");								
}












//*******************************************************************************************************************************************
//********************************************* ACTUALIZAR LISTA DE TOTALES DE LAS PARTIDAS ***************************************
//*******************************************************************************************************************************************
if($ejecutar == "actualizarTotalesPartidas"){
		$sql = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
			
			while($bus = mysql_fetch_array($sql)){
				$monto += $bus["monto"];
			}
		
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
            <td class="Browse"><div align="center">Categoria</div></td>
            <td class="Browse" colspan="4"><div align="center">Partida</div></td>
            <td class="Browse"><div align="center">Descripci&oacute;n</div></td>
            <?
            if($_SESSION["mos_dis"] == 1){
			?>
            <td class="Browse"><div align="center">Disponible</div></td>
            <?
			}
			?>
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
			
			
          $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = ".$bus_maestro["idclasificador_presupuestario"]."");
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
			<?
            if($_SESSION["mos_dis"] == 1){
			?>
    	    <td class='Browse' align="right"><?=number_format(consultarDisponibilidad($bus_maestro["idRegistro"]),2,',','.')?></td>
	        <?
			}
			?>
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

if($ejecutar == "recalcular"){

	$sql_articulos_compra_servicio = mysql_query("select * from articulos_compra_servicio 
						where idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR EN LA CONSULTA A LOS ARTICULOS: ".mysql_error());
	$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die("SELECT DE L ORDEN DE COMPRA: ".mysql_error());
	$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
	
	while($bus_articulos_compra_servicio = mysql_fetch_array($sql_articulos_compra_servicio)){
		$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_articulos_compra_servicio["idarticulos_servicios"]."'");
		$bus_ordinal = mysql_fetch_array($sql_ordinal);
		$idordinal = $bus_ordinal["idordinal"];
			
	
		//*************************************************************************************
		$sql_articulos_servicios = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_articulos_compra_servicio["idarticulos_servicios"]."'");
		$bus_articulos_servicios =  mysql_fetch_array($sql_articulos_servicios);
		
		$id_clasificador_presupuestario = $bus_articulos_servicios["idclasificador_presupuestario"];
		$id_categoria_programatica = $bus_articulos_compra_servicio["idcategoria_programatica"];
		
		$total_articulo_individual = $bus_articulos_compra_servicio["cantidad"] * $bus_articulos_compra_servicio["precio_unitario"];
		
		if($bus_articulos_servicios["tipo_concepto"] == 1 || $bus_articulos_servicios["tipo_concepto"] == 4){
			$monto_total = $total_articulo_individual;
			$exento = 0; 
		}else{
			$monto_total = 0;
			$exento = $total_articulo_individual;
		}
	
												
		$sql2 = mysql_query("update articulos_compra_servicio set total = '".$monto_total."', 
																	  exento = '".$exento."',
																	  precio_unitario = '".$bus_articulos_compra_servicio["precio_unitario"]."', 
																	  cantidad = ".$bus_articulos_compra_servicio["cantidad"]."
																	  where 
																	  idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")or die("error update articulos".mysql_error());
		
		
		// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
		$sql = mysql_query("select * from articulos_compra_servicio 
													where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]." 
														")or die("error todos los articulos".mysql_error());
		$bus = mysql_fetch_array($sql);
			
		if($bus_articulos_servicios["tipo_concepto"] == 1 || $bus_articulos_servicios["tipo_concepto"] == 4){	
			
						
				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_compra_servicio.exento) as exentos from articulos_compra_servicio, articulos_servicios 
					where articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
					and articulos_compra_servicio.idcategoria_programatica = '".$id_categoria_programatica."'
					and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
					and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." ")or die("ERROR HACIENOD LAS SUMAS DE LOS TOTALES: ".mysql_error());
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"];				
				$total_imputable = $total_imputable+$total_impuesto;
				
			// *********************************************************************************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			
			//$sql_maestro = colsultarMaestro($anio, $id_categoria_programatica, $id_clasificador_presupuestario, $idfuente_financiamiento, $idtipo_presupuesto, $idordinal, $cofinanciamiento);
				
			
			$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
												and idcategoria_programatica = ".$id_categoria_programatica." 
												and idclasificador_presupuestario = ".$id_clasificador_presupuestario."
												and idfuente_financiamiento = '".$idfuente_financiamiento."'
												and idtipo_presupuesto = '".$idtipo_presupuesto."'
												and idordinal = '".$idordinal."'")or die("ERROR SELECCIONANDO EL MAESTRO PARA LOS IMPUESTOS: ".mysql_error());
			$existe_partida = mysql_num_rows($sql_maestro);
			if($existe_partida <= 0){
				$estado = 'rechazado';
			}else{
			
				while($bus_maestro = mysql_fetch_array($sql_maestro)){
				
				//echo "*";
				//$disponible = $bus_maestro["monto_actual"] - $bus_maestro["total_compromisos"];
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				//echo "DISOPONIBLE: ".$disponible." ";
				//echo "ID MAESTRO PRESUPUESTO: ".$bus_maestro["idRegistro"]." ";
				
				
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
				
				
				
				if($total_imputable_nuevo > $disponible){
														
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
														monto = '".$total_imputable_nuevo."' 
														where idorden_compra_servicio = ".$id_orden_compra."
														and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("ERROR ACTUALIZANDO LAS PARTIDAS CON SOBREGIRO: ".mysql_error());
					$estado = "sin disponibilidad";
				}else{
				//echo "XXXXXXXXXXXXX ";
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
									monto = '".$total_imputable_nuevo."' 
									where idorden_compra_servicio = ".$id_orden_compra."
									and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")or die("ERROR ACTUALIZANDO LAS PARTIDAS DISPONIBLES: ".mysql_error());
				
					$estado = "aprobado";
				}
			}
			
		}
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA		
				
			$sql2 = mysql_query("update articulos_compra_servicio set estado = '".$estado."' 
													where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")or die("ERRO ACTUALIZANDO EL ESTADO DE LOS ARTICULOS: ".mysql_error());
			
	
	//*****************************************************************************************
	}// CIERRE DEL WHILE DE CONSULTA DE LOS ARTICULOS DE LA ORDEN
	
}
}









// *****************************************************************************************************************************************
// ************************************************* PROCESAR ORDEN DE COMPRA Y SERVICIO ***************************************************
// *****************************************************************************************************************************************

if($ejecutar == "procesarOrden"){

	$sql_articulos_compra_servicio = mysql_query("select * from articulos_compra_servicio 
						where idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR EN LA CONSULTA A LOS ARTICULOS: ".mysql_error());
	$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die("SELECT DE L ORDEN DE COMPRA: ".mysql_error());
	$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
	
	while($bus_articulos_compra_servicio = mysql_fetch_array($sql_articulos_compra_servicio)){
		$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_articulos_compra_servicio["idarticulos_servicios"]."'");
		$bus_ordinal = mysql_fetch_array($sql_ordinal);
		$idordinal = $bus_ordinal["idordinal"];
			
	
		//*************************************************************************************
		$sql_articulos_servicios = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_articulos_compra_servicio["idarticulos_servicios"]."'");
		$bus_articulos_servicios =  mysql_fetch_array($sql_articulos_servicios);
		
		$id_clasificador_presupuestario = $bus_articulos_servicios["idclasificador_presupuestario"];
		$id_categoria_programatica = $bus_articulos_compra_servicio["idcategoria_programatica"];
		
		$total_articulo_individual = $bus_articulos_compra_servicio["cantidad"] * $bus_articulos_compra_servicio["precio_unitario"];
		
		if($bus_articulos_servicios["tipo_concepto"] == 1){
			$monto_total = $total_articulo_individual;
			$exento = 0; 
		}else{
			$monto_total = 0;
			$exento = $total_articulo_individual;
		}
	
												
		$sql2 = mysql_query("update articulos_compra_servicio set total = '".$monto_total."', 
																	  exento = '".$exento."',
																	  precio_unitario = '".$bus_articulos_compra_servicio["precio_unitario"]."', 
																	  cantidad = ".$bus_articulos_compra_servicio["cantidad"]."
																	  where 
																	  idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")or die("error update articulos".mysql_error());
		
		
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
			$sql = mysql_query("select * from articulos_compra_servicio 
														where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]." 
															")or die("error todos los articulos".mysql_error());
			$bus = mysql_fetch_array($sql);
			
		if($bus_articulos_servicios["tipo_concepto"] == 1){	
			
			if($bus["estado"] == "aprobado" || $bus["estado"] == "sin disponibilidad"){
				
						
				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_compra_servicio.exento) as exentos from articulos_compra_servicio, articulos_servicios 
					where articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
					and articulos_compra_servicio.idcategoria_programatica = '".$id_categoria_programatica."'
					and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
					and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra." ")or die("ERROR HACIENOD LAS SUMAS DE LOS TOTALES: ".mysql_error());
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"];				
				$total_imputable = $total_imputable+$total_impuesto;
				
			// *********************************************************************************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			
			$sql_maestro = colsultarMaestro($anio, $id_categoria_programatica, $id_clasificador_presupuestario, $idfuente_financiamiento, $idtipo_presupuesto, $idordinal, $cofinanciamiento);
				
				
				
				
				$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
												and idcategoria_programatica = ".$id_categoria_programatica." 
												and idclasificador_presupuestario = ".$id_clasificador_presupuestario."
												and idfuente_financiamiento = '".$idfuente_financiamiento."'
												and idtipo_presupuesto = '".$idtipo_presupuesto."'
												and idordinal = '".$idordinal."'")or die("ERROR SELECCIONANDO EL MAESTRO PARA LOS IMPUESTOS: ".mysql_error());
				
				while($bus_maestro = mysql_fetch_array($sql_maestro)){
				
				//echo "*";
				//$disponible = $bus_maestro["monto_actual"] - $bus_maestro["total_compromisos"];
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				//echo "DISOPONIBLE: ".$disponible." ";
				//echo "ID MAESTRO PRESUPUESTO: ".$bus_maestro["idRegistro"]." ";
				
				
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
				
				
				
				if($total_imputable_nuevo > $disponible){
														
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
														monto = '".$total_imputable_nuevo."' 
														where idorden_compra_servicio = ".$id_orden_compra."
														and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("ERROR ACTUALIZANDO LAS PARTIDAS CON SOBREGIRO: ".mysql_error());
					$estado = "sin disponibilidad";
				}else{
				//echo "XXXXXXXXXXXXX ";
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
									monto = '".$total_imputable_nuevo."' 
									where idorden_compra_servicio = ".$id_orden_compra."
									and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")or die("ERROR ACTUALIZANDO LAS PARTIDAS DISPONIBLES: ".mysql_error());
				
					$estado = "aprobado";
				}
				}
			}else{
				$estado = "rechazado";

			}
		}else{
			$estado = "aprobado";
		}
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA		
				
				
				
			$sql2 = mysql_query("update articulos_compra_servicio set estado = '".$estado."' 
													where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")or die("ERRO ACTUALIZANDO EL ESTADO DE LOS ARTICULOS: ".mysql_error());
			
	
	//*****************************************************************************************
	}// CIERRE DEL WHILE DE CONSULTA DE LOS ARTICULOS DE LA ORDEN





	$sql_articulos = mysql_query("select * from articulos_compra_servicio 
												where idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR SELECCIONANDO LOS ARTICULOS: ".mysql_error());
	$num_articulos = mysql_num_rows($sql_articulos);
	
	if($num_articulos != 0){
		$sql_orden_duplicados = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
		$bus_orden_duplicados = mysql_fetch_array($sql_orden_duplicados);
			if($bus_orden_duplicados["duplicados"] == 0){
				$sql_articulos = mysql_query("select * from articulos_compra_servicio 
														where idorden_compra_servicio = ".$id_orden_compra." 
														and (estado = 'rechazado' or estado = 'sin disponibilidad')")or die("ERROR SELECCIONANDO ARTICULOS COMPRA SERVICIOS: ".mysql_error());
		$num_articulos = mysql_num_rows($sql_articulos);
		
		if($num_articulos == 0){
			$sql_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where 
														idorden_compra_servicio = ".$id_orden_compra." 
														and (estado = 'rechazado' or estado = 'sin disponibilidad')")or die("ERROR SELECCIONANDO LA RELACION DE IMPUESTOS ORDENES COMPRAS: ".mysql_error());
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
						and idordinal = '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO EL MAESTRO 2:".mysql_error());
							$bus_id_maestro = mysql_fetch_array($sql_id_maestro);
							
							$sql_maestro = mysql_query("update maestro_presupuesto set 
															total_compromisos = total_compromisos + ".$bus_actualizar_partidas["monto"]."
															where idRegistro = ".$bus_id_maestro["idmaestro_presupuesto"]."")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 3: ".mysql_error());
							
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
																						fecha_orden = '".$fecha_validada."',
																						codigo_referencia = '".$codigo_referencia."'
																					where idorden_compra_servicio = ".$id_orden_compra."")or die("error".mysql_error());
				
				
				
				
				
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
															fecha_contable = '".$fecha_validada."'
											 			where iddocumento = ".$id_orden_compra."
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
					echo "exito";
					registra_transaccion("Procesar Orden de Compra y Servicio (".$codigo_orden.", ID: ".$id_orden_compra.")",$login,$fh,$pc,'orden_compra_servicios');
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
																		impuesto,
																		total,
																		exento,
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
																						'".$bus_orden["impuesto"]."',
																						'".$bus_orden["total"]."',
																						'".$bus_orden["exento"]."',
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
																						'".$bus_orden["tipo_carga_orden"]."')")or die(mysql_error());
	$nueva_orden_compra = mysql_insert_id();
	$sql_articulos = mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
	while($bus_articulos = mysql_fetch_array($sql_articulos)){// DUPLICACION DE LOS ARTICULOS
		$sql_insert_articulos = mysql_query("insert into articulos_compra_servicio(idorden_compra_servicio,
																					idarticulos_servicios,
																					idcategoria_programatica,
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
																											'".$bus_articulos["idcategoria_programatica"]."',
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
		$sql_insert_relacion_impuestos = mysql_query("insert into relacion_impuestos_compra_servicio(idorden_compra_servicio,
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
	
	/*echo "material ".$id_material;
	echo "IDorden ".$id_orden_compra;
	echo "Categoria ".$id_categoria_programatica;*/
	
	$sql = mysql_query("select * from articulos_compra_servicio where 
													idarticulos_servicios = ".$id_material." 
													and idorden_compra_servicio = ".$id_orden_compra." 
													and idcategoria_programatica = '".$id_categoria_programatica."'");
	
	$sql_articulos_servicios = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id_material."'");
	
	$bus_articulos_servicios = mysql_fetch_array($sql_articulos_servicios);
	$idordinal = $bus_articulos_servicios["idordinal"];
	
	
	$id_clasificador_presupuestario = $bus_articulos_servicios["idclasificador_presupuestario"];
	$num = mysql_num_rows($sql);
	
	// SI EL ARTICULO NO EXISTE ENTRE LOS ARTICULOS CARGADOS
	if($num == 0){

		$total_articulo_individual = $cantidad * $precio_unitario;
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
																	'".$id_orden_compra."',
																	'".$id_material."',
																	'".$id_categoria_programatica."',
																	'".$cantidad."',
																	'".$precio_unitario."',
																	'".$porcentaje_impuesto."',
																	'".$total_impuesto_individual."',
																	'".$monto_total."',
																	'".$exento."',																	
																	'a',
																	'".$login."',
																	'".date("Y-m-d H:i:s")."',
																	'".$id_partida_impuesto."'
																	)")or die("AQUIIIIIIII ".mysql_error());

		$id_ultimo_generado = mysql_insert_id(); 	// OBTENGO EL ULTIMO ID INGRESADO EN LA TABLA DE ARTICULOS PARA ACTUALIZARLE EL ESTADO DESPUES DE ANALIZAR LA
													//DISPONIBILIDAD DE LAS PARTIDAS
		$total_general =  $monto_total - $exento;
		$actualiza_totales = mysql_query("update orden_compra_servicio set 	
											sub_total = sub_total + '".$monto_total."',
											exento = exento + '".$exento."',
											total = total + '".$total_general."'
											where idorden_compra_servicio=".$id_orden_compra." ")or die ("11111111 ".mysql_error());
		
	
		$sql_articulos = mysql_query("select * from articulos_servicios where idarticulos_servicios = ".$id_material."");
		$bus_articulos = mysql_fetch_array($sql_articulos);
		// 	se realiza la consulta en la tabla maestro para verificar si hay partidas para este articulo, de lo contrario el articulo se coloca en un estado de rechazado para que
		//	en la tabla aparesca la fila en rojo y muestre al usuario que no hay partidas para ese articulo
	if($bus_articulos_servicios["tipo_concepto"] == 1){	
		
		
		
									// *********************************************************************************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			// **************************************** COFINANCIAMIENTO ***********************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			// *********************************************************************************************************
			
			$sql_maestro = colsultarMaestro($anio, $id_categoria_programatica, $id_clasificador_presupuestario, $fuente_financiamiento, $tipo_presupuesto, $idordinal, $cofinanciamiento);
		

		$num_maestro = mysql_num_rows($sql_maestro);
			
			if($num_maestro == 0){ // VALIDO QUE EXISTA UNA PARTIDA EN EL MAESTRO DE PRESUPUESTO PARA ESE ARTICULO
				$estado = "rechazado";  // si no tiene partida en el maestro de presupuesto le coloca RECHAZADO para pintarlo de color ROJO
			}else{
				while($bus_maestro = mysql_fetch_array($sql_maestro)){
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				//echo $bus_maestro["idRegistro"];
				// de lo contrario verifica si en la partida que esta asignada este articulo hay disponibilidad de dinero para comprar la cantidad que se esta pidiendo, si para 
				// esta partida no hay disponibilidad se coloca el estado en SIN DISPONIBILIDAD para que al mostrar el articulo en color en AMARILLO para indicar que no 
				// hay presupuesto para este articulo
				
				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_compra_servicio.exento) as exentos from articulos_compra_servicio, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_compra_servicio.idcategoria_programatica = '".$id_categoria_programatica."'
											and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_compra_servicio.idorden_compra_servicio = ".$id_orden_compra."");
				// SUMO EL TOTAL DE TODOS LOS ARTICULOS QUE ESTAN IMPUTANDO ESA PARTIDA PARA COMPARARLO CON EL DISPONIBLE EN EL MAESTRO DE PRESUPUESTO
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"]; 
				
				
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
				
				
				
				
				if($total_imputable_nuevo > $disponible){ // si el total a imputar es mayor al disponible en la partida
					$estado = "sin disponibilidad";
					$estado_partida = "sobregiro";
				}else{
					//si nada de esto sucede se coloca el estado en aprobado y el material se muestra normalmente
					$estado = "aprobado";
					$estado_partida = "disponible";
				}
				
				
				
				
				
				
				/*echo "select * from partidas_orden_compra_servicio where idorden_compra_servicio=".$id_orden_compra." 
				
				
				
																		and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."";*/
					
				$sql_partidas_orden_compra=mysql_query("select * from partidas_orden_compra_servicio where 
																		idorden_compra_servicio=".$id_orden_compra." 
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
		}else{ // SI ES DEDUCCION ******************************************************************
			$estado = "disponible";
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
$sql_orden_compra = mysql_Query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
$bus_orden_compra = mysql_fetch_array($sql_orden_compra);
echo $bus_orden_compra["tipo_carga_orden"];
}
//if($ejecutar == "")



if($ejecutar == "actualizarTipoCargaOrden"){
	$sql_Actualizar_ordne = mysql_query("update orden_compra_servicio set tipo_carga_orden = '".$tipo_carga_orden."' where idorden_compra_servicio = '".$id_orden_compra."'");
}







if($ejecutar == "consultarEstado"){
	$sql_consulta = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'")or die(mysql_error());
	$bus_consulta = mysql_fetch_array($sql_consulta);
	
	echo $bus_consulta["estado"];
}

?>