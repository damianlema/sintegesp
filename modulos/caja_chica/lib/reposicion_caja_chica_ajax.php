<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);





if($ejecutar =="ingresarDatosBasicos"){
	$sql_ingresar= mysql_query("insert into orden_compra_servicio(tipo,
																  fecha_elaboracion,
																  idbeneficiarios,
																  idcategoria_programatica,
																  anio,
																  idfuente_financiamiento,
																  idtipo_presupuesto,
																  idordinal,
																  justificacion,
																  ubicacion,
																  status,
																  usuario,
																  fechayhora,
																  estado,
																  idtipo_caja_chica)VALUES('".$idtipos_documentos."',
																							'".date("Y-m-d")."',
																							'".$id_beneficiarios."',
																							'".$idcategoria_programatica."',
																							'".$anio."',
																							'".$fuente_financiamiento."',
																							'".$tipo_presupuesto."',
																							'".$idordinal."',
																							'".$justificacion."',
																							'0',
																							'a',
																							'".$login."',
																							'".$fh."',
																							'elaboracion',
																							'".$idtipo_caja_chica."')")or die(mysql_error());
	
	$idrendicion = mysql_insert_id();
	echo $idrendicion;
}





if($ejecutar == "consultarPestanas"){
	$sql_rendicion = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio= '".$idrendicion."'")or die(mysql_error());
	$bus_rendicion = mysql_fetch_array($sql_rendicion);
	$sql_consultar = mysql_query("select * from facturas_rendicion_caja_chica where idorden_compra_servicio = '".$idrendicion."'")or die(mysql_error());
	?>
	<table style="margin-left:3px; margin-right:3px; margin-top:3px" cellpadding="5">
        <tr>
       <?
       while($bus_consultar = mysql_fetch_array($sql_consultar)){
	   $id = "id".$bus_consultar["idfactura_rendicion_caja_chica"];
	   ?>
       
        <td id="id<?=$bus_consultar["idfactura_rendicion_caja_chica"]?>" align="center" bgcolor="<? if($idpestana == $id){echo "#EAEAEA";}else{echo "#FFFFCC";}?>" onMouseOver="cambiarColor(this.id, '#EAEAEA')" onMouseOut="cambiarColor(this.id, '#FFFFCC')" style="cursor:pointer" onclick="document.getElementById('pestana_seleccionada').value = this.id, seleccionarFactura('<?=$bus_consultar["idfactura_rendicion_caja_chica"]?>', '<?=$bus_rendicion["estado"]?>')">
			<?
            if($bus_consultar["nro_factura"] == ""){
				echo "VACIO";	
			}else{
				echo $bus_consultar["nro_factura"];	
			}
			?>
        </td>
        <?
	   }
		if($bus_rendicion["estado"] == "elaboracion"){
        
		?>
        <td style="border:#CCC solid 1px; cursor:pointer" onclick="crearNuevaFactura()">Nuevo +</td>
        <td style="border:#CCC solid 1px; cursor:pointer" onclick="eliminarFactura()">Eliminar -</td>
        <?
		}
		?>
        </tr>
    </table
	><?
}








if($ejecutar == "eliminarFactura"){	
	$sql_eliminar = mysql_query("delete from facturas_rendicion_caja_chica where idfactura_rendicion_caja_chica = '".$idfactura."'")or die("EL ERROR ES AQUI: ".mysql_error());
	
	$sql_articulos = mysql_query("select * from articulos_rendicion_caja_chica where idfactura_rendicion_caja_chica = '".$idfactura."' and idorden_compra_servicio = '".$idrendicion."'")or die("AHORA ES AQUI: ".mysql_error());
	
	while($bus_articulos = mysql_fetch_array($sql_articulos)){
		
	$idmaterial = $bus_articulos["idarticulos_rendicion_caja_chica"];	
	$fuente_financiamiento = "2";
	$tipo_presupuesto = "1";
	$idordinal = "6";
	$anio = $_SESSION["anio_fiscal"];

//echo "AQUI:".$idmaterial;
	 // ACCION PARA ELIMINAR UN ARTICULO DE LA ORDEN DE COMPRA
		
			// si la accion es eliminar se hacen varias consultas para eliminar los articulos de la tabla articulos_rendicion_caja_chica, ademas se elimina la relacion del 
			// articulo con la solicitud y se crean variables para luego verificar si ya no hay mas articulos por una solicitud para que la solicitud se deseleccione de 
			// la lista de solicitudes del proveedor
			
		$sql_material_eliminar = mysql_query("select * from articulos_rendicion_caja_chica where idarticulos_rendicion_caja_chica = '".$idmaterial."'")or die("AQUI");
		$bus_material_eliminar = mysql_fetch_array($sql_material_eliminar);
		
			$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_material_eliminar["idarticulos_servicios"]."'")or die("ERROR EN EL ORDINAL");
			$bus_ordinal = mysql_fetch_array($sql_ordinal);
			$idordinal = $bus_ordinal["idordinal"];
			
			$id_solicitud_cotizacion= $bus_material_eliminar["idsolicitud_cotizacion"];
			// elimino el articulo seleccionado
			//echo $idmaterial;
			$sql = mysql_query("delete from articulos_rendicion_caja_chica where idarticulos_rendicion_caja_chica = ".$idmaterial."")or die("AQUI".mysql_error());
			
			$sql_consulta_iguales = mysql_query("select * from articulos_rendicion_caja_chica where idarticulos_servicios = ".$bus_material_eliminar["idarticulos_servicios"]." and idorden_compra_servicio = ".$idrendicion."");
						$num_consulta_iguales = mysql_num_rows($sql_consulta_iguales);
							if($num_consulta_iguales == 1){
								$sql_cambiar_iguales = mysql_query("update articulos_rendicion_caja_chica set 
																duplicado = 0 
																where idarticulos_servicios = ".$bus_material_eliminar["idarticulos_servicios"]." 
																and idorden_compra_servicio = ".$idrendicion."")or die(mysql_error());
								$sql_cambiar_iguales = mysql_query("update orden_compra_servicio set 
																			duplicados = 0 
																			where 
																			idorden_compra_servicio = ".$idrendicion."")or die(mysql_error());			
							}		
			
		$sql_en_solicitudes = mysql_query("select * from articulos_rendicion_caja_chica where idsolicitud_cotizacion = ".$id_solicitud_cotizacion." 
																					and idorden_compra_servicio = ".$idrendicion."");
			$num_en_solicitudes = mysql_num_rows($sql_en_solicitudes);
			
			$sql_todos_articulos = mysql_query("select * from articulos_rendicion_caja_chica where idorden_compra_servicio = ".$idrendicion."");
			$num_todos_articulos = mysql_num_rows($sql_todos_articulos);
			if($num_todos_articulos == 0){
				// si no existen mas articulos en la orden de compra elimino los registros de esa orden en el resto de las tablas		
				$sql = mysql_query("delete from relacion_compra_solicitud_cotizacion where idorden_compra = ".$idrendicion."");
				$sql = mysql_query("delete from relacion_impuestos_ordenes_compras where idorden_compra_servicio = ".$idrendicion."");
				$sql = mysql_query("delete from partidas_orden_compra_servicio where idorden_compra_servicio = ".$idrendicion."");
				// ACTUALIZO LOS TOTALES EN LA TABLA ORDEN_COMPRA_SERVICIO
				$sql_actualiza_totales = mysql_query("update orden_compra_servicio set 	sub_total = 0,
																						sub_total_original = 0,
																						impuesto = 0,
																						exento = 0,
																						exento_original = 0,
																						total = 0
																where idorden_compra_servicio=".$idrendicion." ")or die (mysql_error());
				$eliminoSolicitud = $id_solicitud_cotizacion;
			}else{
				if($num_en_solicitudes == 0){
					// si ya no existen mas articulos relacionadoa a esa la solicitud, procedo a eliminarla
					$sql = mysql_query("delete from relacion_compra_solicitud_cotizacion where 
																						idsolicitud_cotizacion = ".$id_solicitud_cotizacion." 
																						and idorden_compra = ".$idrendicion."");
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
															where idorden_compra_servicio=".$idrendicion." ")or die (mysql_error());
																			
				// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
				$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$idrendicion."");
				$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
				
			
				if($destino_partida == 1){// EL IMPUESTO TIENE PARTIDA
					if ($contribuyente_ordinario=="si"){
					//echo $impuesto_por_producto;
					$sql_total_impuestos = mysql_query("update relacion_impuestos_ordenes_compras set 
															base_calculo = base_calculo - '".$bus_material_eliminar["total"]."',
															base_calculo_original = base_calculo_original - '".$bus_material_eliminar["total"]."',
															total = total - ".$impuesto_por_producto." 
															where idorden_compra_servicio = ".$idrendicion." 
															and idimpuestos = ".$id_impuestos."")or die(mysql_error());
					// valido que el impuesto tenga partida
					$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where idorden_compra_servicio = ".$idrendicion." 
																	and idimpuestos = ".$id_impuestos."
																	and estado <> 'rechazado'");												
					$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
					$existe_partida = mysql_num_rows($sql_total_impuestos);
					
					if ($existe_partida > 0) {
						// consulta maestro con el clasificador de impuesto
						
					$sql_ordinal_impuesto= mysql_query("select * from ordinal where codigo='0000'");
					$bus_ordinal_impuesto= mysql_fetch_array($sql_ordinal_impuesto);

					$sql_maestro_impuestos = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
																			and idcategoria_programatica = ".$idcategoria_programatica." 
																			and idclasificador_presupuestario = ".$id_clasificador_impuestos."
																			and idfuente_financiamiento = '".$fuente_financiamiento."'
																			and idtipo_presupuesto = '".$tipo_presupuesto."'
																			and idordinal = '".$bus_ordinal_impuesto["idordinal"]."'")or die(mysql_error());
						$bus_maestro = mysql_fetch_array($sql_maestro_impuestos);
						
						
						$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
						
						
						//$disponible = $bus_maestro["monto_actual"] - $bus_maestro["total_compromisos"];
						$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras 
																							where idorden_compra_servicio = ".$idrendicion." 
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
																				idorden_compra_servicio = ".$idrendicion."
																				and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");				
						}else{
							// si existe disponibilidad para esa partida
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set 
																			estado = 'disponible', 
																			monto = '".$total_impuesto_imputable."',
																			monto_original = '".$total_impuesto_imputable."' 
																			where 
																			idorden_compra_servicio = ".$idrendicion."
																			and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
						}
						$partida_impuestos = $id_clasificador_impuestos;
					}
				}
				}else{
					$partida_impuestos = 0;
				}


				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_rendicion_caja_chica.exento) as exentos from articulos_rendicion_caja_chica, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_rendicion_caja_chica.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_rendicion_caja_chica.idorden_compra_servicio = ".$idrendicion." ");
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"];
				
				if($destino_partida == 0 and $contribuyente_ordinario == "si"){
					$sql_total_impuesto = mysql_query("select SUM(impuesto) as totales_impuesto from articulos_rendicion_caja_chica, articulos_servicios where
												articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
												and articulos_rendicion_caja_chica.idarticulos_servicios = articulos_servicios.idarticulos_servicios
												and articulos_rendicion_caja_chica.idorden_compra_servicio = ".$idrendicion." and idpartida_impuesto = 0");
					$bus_total_impuesto = mysql_fetch_array($sql_total_impuesto);
					$total_impuesto = $bus_total_impuesto["totales_impuesto"];
					$total_imputable = $total_imputable + $total_impuesto;	
				}
				
				
				
					$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = '".$anio."' 
																			and idcategoria_programatica = '".$idcategoria_programatica."' 
																			and idclasificador_presupuestario = '".$id_clasificador_presupuestario."'
																			and idfuente_financiamiento = '".$fuente_financiamiento."'
																			and idtipo_presupuesto = '".$tipo_presupuesto."'
																			and idordinal = '".$idordinal."'")or die(mysql_error());
				$bus_maestro = mysql_fetch_array($sql_maestro);
				
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				
				
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				
				if($total_imputable > $disponible){ // si el total imputable es mayor al disponible
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
																			monto = '".$total_imputable."' ,
																			monto_original = '".$total_imputable."' 
																			where 
																			idorden_compra_servicio = ".$idrendicion."
																			and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
				}else{	// si el total imputable es menor o igual al disponible
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
																			monto = '".$total_imputable."' ,
																			monto_original = '".$total_imputable."' 
																			where 
																			idorden_compra_servicio = ".$idrendicion."
																			and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
				}
					
							
				$sql = mysql_query("delete from partidas_orden_compra_servicio where idorden_compra_servicio = ".$idrendicion."
																							and monto <= 0");	
				// ********************************************* ELIMINAR PARTIDAS ************************************************
				
			}// CIERRE SI NO ES ARTICULO UNICO
		


		
	}


//$sql_consulta = mysql_query("delete from facturas_rendicion_caja_chica where idfactura_rendicion_caja_chica = '".$idfactura."'")

}






if($ejecutar == "crearNuevaFactura"){
	$sql_ingresar = mysql_query("insert into facturas_rendicion_caja_chica(idorden_compra_servicio)VALUES('".$idrendicion."')");
	$idfactura = mysql_insert_id();
	echo $idfactura;
}







if($ejecutar == "guardarFactura"){
	$sql_Actualizar = mysql_query("update facturas_rendicion_caja_chica set nro_factura = '".$nro_factura."',
								  											fecha_factura = '".$fecha_factura."',
																			nro_control = '".$nro_control."',
																			idbeneficiarios = '".$idbeneficiarios."'
																			where idfactura_rendicion_caja_chica = '".$idfactura."'")or die(mysql_error());	
}








if($ejecutar == "actualizarMontosMaximos"){
	$sql_tipo_caja = mysql_query("select * from tipo_caja_chica where idtipo_caja_chica = '".$idtipo_caja_chica."'");
	$bus_tipo_caja = mysql_fetch_array($sql_tipo_caja);
	
	$mr = $bus_tipo_caja["maximo_reponer"];
	$mrf = $bus_tipo_caja["ut_maxima_factura"];
	
	$costo = $_SESSION["costo_ut"];	
	
	
	$maximo_reponer = $mr*$costo;
	$maximo_factura = $mrf*$costo;
	
	echo number_format($maximo_reponer, 2,",",".")."|.|".number_format($maximo_factura,2,",",".");
}










if($ejecutar == "ingresarMaterial"){
	
	
	
	
	
	
	//$fuente_financiamiento = "2";
	//$tipo_presupuesto = "1";
	$idordinal = "6";
	//$anio = $_SESSION["anio_fiscal"];




	$sql = mysql_query("select * from articulos_rendicion_caja_chica where idarticulos_servicios = ".$idmaterial." and idorden_compra_servicio = ".$idrendicion." and idfactura_rendicion_caja_chica = '".$idfactura."'");
	$num = mysql_num_rows($sql);
	// SI EL ARTICULO NO EXISTE ENTRE LOS ARTICULOS CARGADOS
	if($num == 0){

		
	$sql_tipo_caja_chica=mysql_query("select * from tipo_caja_chica where idtipo_caja_chica = '".$tipo_caja_chica."'");
	$bus_tipo_caja_chica = mysql_fetch_array($sql_tipo_caja_chica);
	
	
	
	$ut_aprobradas = $bus_tipo_caja_chica["unidades_tributarias_aprobadas"];
	$min_ut = $bus_tipo_caja_chica["minimo_reponer"];
	$max_ut = $bus_tipo_caja_chica["maximo_reponer"];
	$ut_factura = $bus_tipo_caja_chica["ut_maxima_factura"];
		
	
	$sql_suma = mysql_query("select SUM(cantidad * precio_unitario) as suma 
								   		from 
								   articulos_rendicion_caja_chica 
								   		where 
								   idorden_compra_servicio = ".$idrendicion." 
								   and idfactura_rendicion_caja_chica = '".$idfactura."'");
	
	$bus_suma = mysql_fetch_array($sql_suma);
	$suma_actual = $cantidad * $precio_unitario;
	$suma_total = $suma_actual+$bus_suma["suma"];
	
	
	$ut = $ut_factura*$_SESSION["costo_ut"];
	//echo "AQUI: ".$ut;
	if($suma_total <= $ut){
		
		


		$total_articulo_individual = $cantidad * $precio_unitario;
		$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$idrendicion."");
		$bus_orden = mysql_fetch_array($sql_orden);
		
		
		$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$idmaterial."'");
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
									 articulos_servicios.idarticulos_servicios = ".$idmaterial." 
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
		
		$sql = mysql_query("insert into articulos_rendicion_caja_chica (idorden_compra_servicio,
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
																	idpartida_impuesto,
																	idfactura_rendicion_caja_chica)values(
																	'".$idrendicion."',
																	'".$idmaterial."',
																	'".$cantidad."',
																	'".$precio_unitario."',
																	'".$porcentaje_impuesto."',
																	'".$total_impuesto_individual."',
																	'".$total_articulo_individual."',
																	'".$exento."',																	
																	'a',
																	'".$login."',
																	'".date("Y-m-d H:i:s")."',
																	'".$id_partida_impuesto."',
																	'".$idfactura."'
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
																					where idorden_compra_servicio=".$idrendicion." ")or die ("11111111 ".mysql_error());
		
		if ($destino_partida<>0 and $contribuyente_ordinario=="si"){ // SI EL IMPUESTO TIENE PARTIDA PROPIA
			$sql_existe_partida=mysql_query("select * from relacion_impuestos_ordenes_compras 
																	where idorden_compra_servicio=".$idrendicion." 
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
																					".$idrendicion.",
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
			total=total+".$total_impuesto_individual." where idorden_compra_servicio = '".$idrendicion."'")or die("3333333 ".mysql_error());
			}
			
			// VALIDO LA PARTIDA DEL IMPUESTO EXISTA EN EL MAESTRO DE PRESUPUESTO 
			
									// consulta maestro con el clasificador de impuesto

			$sql_ordinal_impuesto= mysql_query("select * from ordinal where codigo='0000'");
			$bus_ordinal_impuesto= mysql_fetch_array($sql_ordinal_impuesto);
			$sql_maestro_impuestos = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
														and idcategoria_programatica = ".$idcategoria_programatica." 
														and idclasificador_presupuestario = ".$id_clasificador_impuestos."
														and idfuente_financiamiento = '".$fuente_financiamiento."'
														and idtipo_presupuesto = '".$tipo_presupuesto."'
														and idordinal = '".$bus_ordinal_impuesto["idordinal"]."'")or die("ES AQUI EL ERRORRRRRRRRRRRRRRRR". mysql_error());
	
			
			$bus_maestro = mysql_fetch_array($sql_maestro_impuestos);
			$num_maestro_impuesto = mysql_num_rows($sql_maestro_impuestos);
			if($num_maestro_impuesto > 0){ // valido que exista una partida para el impuesto
				// obtengo el disponible de la partida para compararlo con el total de impuesto y saber si existe disponibilidad
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				
				
				$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where 
																		idorden_compra_servicio = ".$idrendicion." 
																	and idimpuestos = ".$id_impuestos."");
				$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
				$total_impuesto_imputable = $bus_total_impuestos["total"];	
						
				if($total_impuesto_imputable > $disponible){
					$estado_partida="sobregiro"; // si no tiene disponibilidad cambio el estado para colorearlo de AMARILLO
					$sql_update_estado_relacion_impuestos = mysql_query("update relacion_impuestos_ordenes_compras set estado = 'sin disponibilidad' 
																			where idorden_compra_servicio = ".$idrendicion."");
				}else{
					$estado_partida="disponible"; // si existe disponibilidad coloco el estado como DISPONIBLE para que aparezca en color normal
					$sql_update_estado_relacion_impuestos = mysql_query("update relacion_impuestos_ordenes_compras set estado = 'disponible' 
																			where idorden_compra_servicio = ".$idrendicion."");
				}
				// BUSCO LA PARTIDA DEL IMPUESTO EN LAS PARTIDAS DE LA ORDEN DE COMPRA 
				$sql_partidas_orden_compra=mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio=".$idrendicion." 
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
																							values (".$idrendicion.",
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
																		where idorden_compra_servicio=".$idrendicion." 
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")
																		or die ("555 ".mysql_error());
				}	
			}else{ // SI NO EXISTE PARTIDA PARA EL IMPUESTO LO COLOCA COMO RECHAZADO PARA COLOREARLO DE ROJO
				$sql_update_estado_relacion_impuestos = mysql_query("update relacion_impuestos_ordenes_compras set estado = 'rechazado' 
																	where idorden_compra_servicio = ".$idrendicion."");
			} // CIERRO LA VALIDACION PARA SABER SI TIENE PARTIDA EN EL MAESTRO DE PRESUPUESTO
			
			
		} // CIERRO LA VALIDACION DE SI EL IMPUESTO TIENE PARTIDA PROPIA
		
		
		$sql_articulos = mysql_query("select * from articulos_servicios where idarticulos_servicios = ".$idmaterial."");
		$bus_articulos = mysql_fetch_array($sql_articulos);
		// 	se realiza la consulta en la tabla maestro para verificar si hay partidas para este articulo, de lo contrario el articulo se coloca en un estado de rechazado para que
		//	en la tabla aparesca la fila en rojo y muestre al usuario que no hay partidas para ese articulo
		/*echo "select * from maestro_presupuesto where anio = ".$anio." 
														and idcategoria_programatica = ".$idcategoria_programatica." 
														and idclasificador_presupuestario = ".$id_clasificador_presupuestario."
														and idfuente_financiamiento = '".$fuente_financiamiento."'
														and idtipo_presupuesto = '".$tipo_presupuesto."'
														and idordinal = '".$idordinal."'";*/
		$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
														and idcategoria_programatica = ".$idcategoria_programatica." 
														and idclasificador_presupuestario = ".$id_clasificador_presupuestario."
														and idfuente_financiamiento = '".$fuente_financiamiento."'
														and idtipo_presupuesto = '".$tipo_presupuesto."'
														and idordinal = '".$idordinal."'"
																		)or die($anio."ERROR SQL MAESTRO: ".mysql_error());

		$num_maestro = mysql_num_rows($sql_maestro);
			
			if($num_maestro == 0){ // VALIDO QUE EXISTA UNA PARTIDA EN EL MAESTRO DE PRESUPUESTO PARA ESE ARTICULO
				$estado = "rechazado";  // si no tiene partida en el maestro de presupuesto le coloca RECHAZADO para pintarlo de color ROJO
			}else{
				$bus_maestro = mysql_fetch_array($sql_maestro);
				
				
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				
				// de lo contrario verifica si en la partida que esta asignada este articulo hay disponibilidad de dinero para comprar la cantidad que se esta pidiendo, si para 
				// esta partida no hay disponibilidad se coloca el estado en SIN DISPONIBILIDAD para que al mostrar el articulo en color en AMARILLO para indicar que no 
				// hay presupuesto para este articulo
				
				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_rendicion_caja_chica.exento) as exentos from articulos_rendicion_caja_chica, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_rendicion_caja_chica.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_rendicion_caja_chica.idorden_compra_servicio = ".$idrendicion."");
				// SUMO EL TOTAL DE TODOS LOS ARTICULOS QUE ESTAN IMPUTANDO ESA PARTIDA PARA COMPARARLO CON EL DISPONIBLE EN EL MAESTRO DE PRESUPUESTO
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"]; 
				
				if ($destino_partida == 0 and $contribuyente_ordinario=="si")	{// valido que el impuesto se sume a la partida o si tiene partida propia
					$sql_impuesto_imputable = mysql_query("select SUM(impuesto) as totales_impuestos from articulos_rendicion_caja_chica, 
																				articulos_servicios 
																			where
										articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
										and articulos_rendicion_caja_chica.idarticulos_servicios = articulos_servicios.idarticulos_servicios
										and articulos_rendicion_caja_chica.idorden_compra_servicio = ".$idrendicion." 
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
				/*echo "select * from partidas_orden_compra_servicio where idorden_compra_servicio=".$idrendicion." 
																		and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."";*/
					
				$sql_partidas_orden_compra=mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio=".$idrendicion." 
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
																							values (".$idrendicion.",
																									".$bus_maestro["idRegistro"].",
																									".$total_imputable.",
																									".$total_imputable.",
																									'".$estado_partida."',
																									'a',
																									'".$login."',
																									'".date("Y-m-d H:i:s")."')")
																								or die("ERROR GUARDANDO PARTIDAS:". mysql_error());
				}else{ // DE LO CONTRARIO LA ACTUALIZO
					$actualiza_partida=mysql_query("update partidas_orden_compra_servicio set 
																		monto = ".$total_imputable.",
																		monto_original = ".$total_imputable.",
																		estado='".$estado_partida."' 
																		where idorden_compra_servicio=".$idrendicion." 
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")
																		or die ($total_item."ERROR MODIFICANDO PARTIDAS: ".mysql_error());
				}														
	
			}
			// actualizo el estado del material ingresado				
			$sql_update_articulos_compras = mysql_query("update articulos_rendicion_caja_chica set estado = '".$estado."' 
																where idarticulos_rendicion_caja_chica = ".$id_ultimo_generado."");



		
		if($sql){
		registra_transaccion("Ingresar Material Individual en Orden de Compra (".$id_ultimo_generado.")",$login,$fh,$pc,'orden_compra_servicios');
			echo "exito";
		}else{
			echo "fallo";
		}
		
	}else{
		echo "sobrepasa_ut";	
	}
}else{
echo "existe";
}

}







if($ejecutar == "actualizarListaPartidas"){
	
$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$idrendicion."'");
$bus_orden = mysql_fetch_array($sql_orden);
//and idclasificador_presupuestario = ".$bus_orden["idclasificador_presupuestario"]." 

$sql_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = ".$idrendicion."");
																		
$num_partidas = mysql_num_rows($sql_partidas);
if($num_partidas != 0){
	?>
    <table width="85%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
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
            <td class='Browse' align='left'><?=$bus_clasificador["partida"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["generica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["sub_especifica"]?></td>
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








if($ejecutar == "actualizarListaMateriales"){
	$sql = mysql_query("select * from articulos_rendicion_caja_chica,  unidad_medida, articulos_servicios
									 where 
									 	articulos_rendicion_caja_chica.idorden_compra_servicio = ".$idrendicion."
										and articulos_rendicion_caja_chica.idfactura_rendicion_caja_chica = ".$idfactura."
										and articulos_servicios.idarticulos_servicios = articulos_rendicion_caja_chica.idarticulos_servicios and 
									  	articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida");
	
	$num = mysql_num_rows($sql);
	
	
	$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$idrendicion."'");
	$bus_orden = mysql_fetch_array($sql_orden);
	if($num != 0){
	?>
	<table width="85%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <?
            if($bus_orden["duplicados"] == 1){
			?>
			<td class="Browse"><div align="center">Duplicados</div></td>
			<?
			}
			?>
            <td class="Browse"><div align="center">Codigo</div></td>
            <td class="Browse"><div align="center">Descripcion</div></td>
            <td class="Browse"><div align="center">UND</div></td>
            <td class="Browse"><div align="center">Cantidad</div></td>
            <td class="Browse"><div align="center">Precio Unitario</div></td>
            <td class="Browse"><div align="center">Total</div></td>
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
            <td class='Browse' align='left'><?=$bus["codigo"]?></td>
            <td class='Browse' align='left'><?=$bus[30]?></td>
            <td class='Browse' align='left'><?=$bus["abreviado"]?></td>
            <td class='Browse' align='center'>
			<?
            if($bus_orden["estado"] == "elaboracion"){
            ?>
            <input align="right" style="text-align:right" name="cantidad<?=$bus["idarticulos_rendicion_caja_chica"]?>" 
            												type="text" 
                                                            id="cantidad<?=$bus["idarticulos_rendicion_caja_chica"]?>" 
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
            <input align="right" style="text-align:right" name="precio<?=$bus["idarticulos_rendicion_caja_chica"]?>" 
            												type="hidden" 
                                                            id="precio<?=$bus["idarticulos_rendicion_caja_chica"]?>" 
                                                            size="10"
                                                            value="<?=$bus["precio_unitario"]?>">
            <input align="right" style="text-align:right" name="mostrarPrecio<?=$bus["idarticulos_rendicion_caja_chica"]?>" 
            												type="text" 
                                                            id="mostrarPrecio<?=$bus["idarticulos_rendicion_caja_chica"]?>" 
                                                            size="10"
                                                            onclick="this.select()"
                                                            value="<?=number_format($bus["precio_unitario"],2,',','.')?>"
                                                            onblur="formatoNumero(this.name, 'precio<?=$bus["idarticulos_rendicion_caja_chica"]?>')">
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
                                actualizarPrecioCantidad(document.getElementById('precio<?=$bus["idarticulos_rendicion_caja_chica"]?>').value,
                                document.getElementById('cantidad<?=$bus["idarticulos_rendicion_caja_chica"]?>').value, 
                                '<?=$bus["idarticulos_servicios"]?>', 
                                '<?=$bus["idarticulos_rendicion_caja_chica"]?>')" 
                                title="Actualizar Precio y Cantidad" /></a></a></td>  
<td class='Browse' align="center">
                    <a href="javascript:;" onClick="eliminarMateriales('<?=$bus["idarticulos_rendicion_caja_chica"]?>')">
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
		echo "Sin materiales Asociados";	
	}
}












if($ejecutar == "seleccionarFactura"){
	$sql_consulta = mysql_query("select facturas_rendicion_caja_chica.nro_factura,
										facturas_rendicion_caja_chica.fecha_factura,
										facturas_rendicion_caja_chica.nro_control,
										beneficiarios.nombre,
										beneficiarios.idbeneficiarios,
										beneficiarios.contribuyente_ordinario
											from 
										facturas_rendicion_caja_chica, 
										beneficiarios 
											where 
										facturas_rendicion_caja_chica.idfactura_rendicion_caja_chica = '".$idfactura."'
										and facturas_rendicion_caja_chica.idbeneficiarios= beneficiarios.idbeneficiarios");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	
	echo $bus_consulta["nro_factura"]."|.|".
	$bus_consulta["fecha_factura"]."|.|".
	$bus_consulta["nro_control"]."|.|".
	$bus_consulta["idbeneficiarios"]."|.|".
	$bus_consulta["nombre"]."|.|".
	$bus_consulta["contribuyente_ordinario"];
}









if($ejecutar == "actualizarPrecioCantidad"){
	
	//$fuente_financiamiento = "2";
	//$tipo_presupuesto = "1";
	$idordinal = "6";
	//$anio = $_SESSION["anio_fiscal"];
	
	
	
	
	
	
	$sql_tipo_caja_chica=mysql_query("select * from tipo_caja_chica where idtipo_caja_chica = '".$tipo_caja_chica."'");
	$bus_tipo_caja_chica = mysql_fetch_array($sql_tipo_caja_chica);
	
	
	
	$ut_aprobradas = $bus_tipo_caja_chica["unidades_tributarias_aprobadas"];
	$min_ut = $bus_tipo_caja_chica["minimo_reponer"];
	$max_ut = $bus_tipo_caja_chica["maximo_reponer"];
	$ut_factura = $bus_tipo_caja_chica["ut_maxima_factura"];
		
	
	$sql_suma = mysql_query("select SUM(cantidad * precio_unitario) as suma 
								   		from 
								   articulos_rendicion_caja_chica 
								   		where 
								   idorden_compra_servicio = ".$idrendicion." 
								   and idfactura_rendicion_caja_chica = '".$idfactura."'
								   and idarticulos_rendicion_caja_chica != '".$idmaterial_compra."'");
	
	$bus_suma = mysql_fetch_array($sql_suma);
	$suma_actual = $cantidad * $precio;
	$suma_total = $suma_actual+$bus_suma["suma"];
	
	
	$ut = $ut_factura*$_SESSION["costo_ut"];
	//echo "AQUI: ".$ut;
	if($suma_total <= $ut){
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

			$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$idmaterial."'");
			$bus_ordinal = mysql_fetch_array($sql_ordinal);
			$idordinal = $bus_ordinal["idordinal"];
			
			
			$sql = mysql_query("select 	impuestos.destino_partida as destinoPartida, 
										impuestos.idimpuestos as idImpuestos, 
										impuestos.porcentaje as porcentajeImpuesto, 
										impuestos.idclasificador_presupuestario as clasificadorImpuestos, 
										articulos_servicios.idclasificador_presupuestario as clasificadorArticulos ,
										articulos_servicios.exento as exento
									from impuestos, articulos_servicios 
									where articulos_servicios.idarticulos_servicios = ".$idmaterial." 
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
			$sql_consulta_precio_viejo = mysql_query("select * from articulos_rendicion_caja_chica where 
													idarticulos_rendicion_caja_chica = ".$idmaterial_compra."")or die("2: ".mysql_error());
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
			$sql2 = mysql_query("update articulos_rendicion_caja_chica set porcentaje_impuesto = '".$porcentaje_impuesto."',
																	 impuesto = '".$impuesto_por_producto."',
																	  total = '".$total."', 
																	  precio_unitario = '".$precio."', 
																	  cantidad = '".$cantidad."',
																	  exento = '".$exento."'
																	  where 
																	  idarticulos_rendicion_caja_chica = ".$idmaterial_compra."")or die("3: ".mysql_error());
		
			
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
																			where idorden_compra_servicio=".$idrendicion." ")or die("4: ".mysql_error());
			
			
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
			
			$sql = mysql_query("select * from articulos_rendicion_caja_chica where idarticulos_rendicion_caja_chica = ".$idmaterial_compra."")or die("5: ".mysql_error());
			$bus = mysql_fetch_array($sql);
			if($bus["estado"] == "aprobado" || $bus["estado"] == "sin disponibilidad"){ // en cualquiera de stos estados el articulo tiene partida en el maestro
					$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$idrendicion."")or die("6: ".mysql_error());
					$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
					//echo "ID: ".$bus_compra_servicio["idcategoria_programatica"]." ";
				if($destino_partida == 1 and $contribuyente_ordinario == "si"){
					// actualizo el total del impuesto sumando el nuevo impuesto y restandole el anterior *********************************************************************************************************************************************************
					
					$sql_total_impuestos = mysql_query("update relacion_impuestos_ordenes_compras set 
														base_calculo =  base_calculo + '".$total_viejo."' + '".$total."',
														base_calculo_original = base_calculo_original + '".$total_viejo."' + '".$total."',
														total = total + (".$impuesto_por_producto."-".$impuesto_viejo.") 
														where idorden_compra_servicio = ".$idrendicion." 
														and idimpuestos = ".$id_impuestos."")or die("7: ".mysql_error());
					// consulta maestro con el clasificador de impuesto
					
					$sql_ordinal_impuesto= mysql_query("select * from ordinal where codigo='0000'");
					$bus_ordinal_impuesto = mysql_fetch_array($sql_ordinal_impuesto);
					$sql_maestro_impuestos = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
												and idcategoria_programatica = ".$idcategoria_programatica." 
												and idclasificador_presupuestario = ".$id_clasificador_impuestos."
												and idfuente_financiamiento = '".$fuente_financiamiento."'
												and idtipo_presupuesto = '".$tipo_presupuesto."'
												and idordinal = '".$bus_ordinal_impuesto["idordinal"]."'")or die("8: ".mysql_error());
																								
																								
																								
					$bus_maestro = mysql_fetch_array($sql_maestro_impuestos);
					
					
					
					$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
					
					//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
					$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where idorden_compra_servicio = ".$idrendicion." 
																	and idimpuestos = ".$id_impuestos."")or die("9: ".mysql_error());
																	
					$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
					$total_impuesto_imputable = $bus_total_impuestos["total"];
					$total_impuesto_imputable2 = $impuesto_por_producto - $impuesto_viejo;
								
					if($total_impuesto_imputable > $disponible){ // comparo el impuesto imputable con el disponible en la partida para verificar su estado
						$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
																			monto = '".$total_impuesto_imputable."',
																			monto_original = '".$total_impuesto_imputable."' 
																			where 
																			idorden_compra_servicio = ".$idrendicion."
																			and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("10: ".mysql_error());
						
					}else{

						$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
																			monto = '".$total_impuesto_imputable."',
																			monto_original = '".$total_impuesto_imputable."' 
																			where 
																			idorden_compra_servicio = ".$idrendicion."
																			and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("16: ".mysql_error());
					}
					
					$partida_impuestos = $id_clasificador_impuestos;
				}else{
					$partida_impuestos = 0;
				}

				/*
				$sql2 = mysql_query("update articulos_rendicion_caja_chica set idpartida_impuesto = ".$partida_impuestos."  
																		where idarticulos_rendicion_caja_chica = ".$idmaterial_compra."");
				
				*/		
				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_rendicion_caja_chica.exento) as exentos from articulos_rendicion_caja_chica, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_rendicion_caja_chica.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_rendicion_caja_chica.idorden_compra_servicio = ".$idrendicion." ")or die("12: ".mysql_error());
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"];
				//echo $bus_imputable["totales"];
				//echo $bus_imputable["exentos"];
				if ($destino_partida == 0 and $contribuyente_ordinario=="si"){
					$sql_total_impuesto = mysql_query("select SUM(impuesto) as totales_impuesto from articulos_rendicion_caja_chica, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_rendicion_caja_chica.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_rendicion_caja_chica.idorden_compra_servicio = ".$idrendicion." and idpartida_impuesto = 0")or die("13: ".mysql_error());
					$bus_total_impuesto = mysql_fetch_array($sql_total_impuesto);
					$total_impuesto = $bus_total_impuesto["totales_impuesto"];
					$total_imputable = $total_imputable + $total_impuesto;
				}

				$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
												and idcategoria_programatica = ".$idcategoria_programatica." 
												and idclasificador_presupuestario = ".$id_clasificador_presupuestario."
												and idfuente_financiamiento = '".$fuente_financiamiento."'
												and idtipo_presupuesto = '".$tipo_presupuesto."'
												and idordinal = '".$idordinal."'")or die("8: ".mysql_error());

				
				$bus_maestro = mysql_fetch_array($sql_maestro);
				
				
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				
				
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				
				if($total_imputable > $disponible){
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
																		monto = '".$total_imputable."',
																		monto_original = '".$total_imputable."' 
																		where 
																		idorden_compra_servicio = ".$idrendicion."
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("15: ".mysql_error());
					$estado = "sin disponibilidad";
				}else{
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
																			monto = '".$total_imputable."',
																			monto_original = '".$total_imputable."' 
																			where 
																			idorden_compra_servicio = ".$idrendicion."
																			and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("16: ".mysql_error());
				
					$estado = "aprobado";
				}
			}else{
				$estado = "rechazado";
			}
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA		
			
			$sql2 = mysql_query("update articulos_rendicion_caja_chica set estado = '".$estado."',
																idpartida_impuesto = ".$partida_impuestos." 
																where idarticulos_rendicion_caja_chica = ".$idmaterial_compra."")or die("17: ".mysql_error());
			
		if($sql2){
				registra_transaccion("Actualizar Precio Cantidad de Orden de Compra (".$idmaterial_compra.")",$login,$fh,$pc,'orden_compra_servicios');

				echo "exito";
		}else{
				registra_transaccion("Actualizar Precio Cantidd de Orden de Compra ERROR (".$idmaterial_compra.")",$login,$fh,$pc,'orden_compra_servicios');

				echo $sql2." MYSQL ERROR: ".mysql_error();
		}
	
	
	}else{
		echo "sobrepasa_ut";	
	}
	
}













if($ejecutar == "eliminarMateriales"){
	//$fuente_financiamiento = "2";
	//$tipo_presupuesto = "1";
	$idordinal = "6";
	//$anio = $_SESSION["anio_fiscal"];

//echo "AQUI:".$idmaterial;
	 // ACCION PARA ELIMINAR UN ARTICULO DE LA ORDEN DE COMPRA
		
			// si la accion es eliminar se hacen varias consultas para eliminar los articulos de la tabla articulos_rendicion_caja_chica, ademas se elimina la relacion del 
			// articulo con la solicitud y se crean variables para luego verificar si ya no hay mas articulos por una solicitud para que la solicitud se deseleccione de 
			// la lista de solicitudes del proveedor
			
		$sql_material_eliminar = mysql_query("select * from articulos_rendicion_caja_chica where idarticulos_rendicion_caja_chica = '".$idmaterial."'");
		$bus_material_eliminar = mysql_fetch_array($sql_material_eliminar);
		
			$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_material_eliminar["idarticulos_servicios"]."'");
			$bus_ordinal = mysql_fetch_array($sql_ordinal);
			$idordinal = $bus_ordinal["idordinal"];
			
			$id_solicitud_cotizacion= $bus_material_eliminar["idsolicitud_cotizacion"];
			// elimino el articulo seleccionado
			$sql = mysql_query("delete from articulos_rendicion_caja_chica where idarticulos_rendicion_caja_chica = ".$idmaterial."")or die(mysql_error());
			
			$sql_consulta_iguales = mysql_query("select * from articulos_rendicion_caja_chica where idarticulos_servicios = ".$bus_material_eliminar["idarticulos_servicios"]." and idorden_compra_servicio = ".$idrendicion."");
						$num_consulta_iguales = mysql_num_rows($sql_consulta_iguales);
							if($num_consulta_iguales == 1){
								$sql_cambiar_iguales = mysql_query("update articulos_rendicion_caja_chica set 
																duplicado = 0 
																where idarticulos_servicios = ".$bus_material_eliminar["idarticulos_servicios"]." 
																and idorden_compra_servicio = ".$idrendicion."")or die(mysql_error());
								$sql_cambiar_iguales = mysql_query("update orden_compra_servicio set 
																			duplicados = 0 
																			where 
																			idorden_compra_servicio = ".$idrendicion."")or die(mysql_error());			
							}		
			
		$sql_en_solicitudes = mysql_query("select * from articulos_rendicion_caja_chica where idsolicitud_cotizacion = ".$id_solicitud_cotizacion." 
																					and idorden_compra_servicio = ".$idrendicion."");
			$num_en_solicitudes = mysql_num_rows($sql_en_solicitudes);
			
			$sql_todos_articulos = mysql_query("select * from articulos_rendicion_caja_chica where idorden_compra_servicio = ".$idrendicion."");
			$num_todos_articulos = mysql_num_rows($sql_todos_articulos);
			if($num_todos_articulos == 0){
				// si no existen mas articulos en la orden de compra elimino los registros de esa orden en el resto de las tablas		
				$sql = mysql_query("delete from relacion_compra_solicitud_cotizacion where idorden_compra = ".$idrendicion."");
				$sql = mysql_query("delete from relacion_impuestos_ordenes_compras where idorden_compra_servicio = ".$idrendicion."");
				$sql = mysql_query("delete from partidas_orden_compra_servicio where idorden_compra_servicio = ".$idrendicion."");
				// ACTUALIZO LOS TOTALES EN LA TABLA ORDEN_COMPRA_SERVICIO
				$sql_actualiza_totales = mysql_query("update orden_compra_servicio set 	sub_total = 0,
																						sub_total_original = 0,
																						impuesto = 0,
																						exento = 0,
																						exento_original = 0,
																						total = 0
																where idorden_compra_servicio=".$idrendicion." ")or die (mysql_error());
				$eliminoSolicitud = $id_solicitud_cotizacion;
			}else{
				if($num_en_solicitudes == 0){
					// si ya no existen mas articulos relacionadoa a esa la solicitud, procedo a eliminarla
					$sql = mysql_query("delete from relacion_compra_solicitud_cotizacion where 
																						idsolicitud_cotizacion = ".$id_solicitud_cotizacion." 
																						and idorden_compra = ".$idrendicion."");
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
															where idorden_compra_servicio=".$idrendicion." ")or die (mysql_error());
																			
				// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
				$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$idrendicion."");
				$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
				
			
				if($destino_partida == 1){// EL IMPUESTO TIENE PARTIDA
					if ($contribuyente_ordinario=="si"){
					//echo $impuesto_por_producto;
					$sql_total_impuestos = mysql_query("update relacion_impuestos_ordenes_compras set 
															base_calculo = base_calculo - '".$bus_material_eliminar["total"]."',
															base_calculo_original = base_calculo_original - '".$bus_material_eliminar["total"]."',
															total = total - ".$impuesto_por_producto." 
															where idorden_compra_servicio = ".$idrendicion." 
															and idimpuestos = ".$id_impuestos."")or die(mysql_error());
					// valido que el impuesto tenga partida
					$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where idorden_compra_servicio = ".$idrendicion." 
																	and idimpuestos = ".$id_impuestos."
																	and estado <> 'rechazado'");												
					$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
					$existe_partida = mysql_num_rows($sql_total_impuestos);
					
					if ($existe_partida > 0) {
						// consulta maestro con el clasificador de impuesto
						
					$sql_ordinal_impuesto= mysql_query("select * from ordinal where codigo='0000'");
					$bus_ordinal_impuesto= mysql_fetch_array($sql_ordinal_impuesto);

					$sql_maestro_impuestos = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
																			and idcategoria_programatica = ".$idcategoria_programatica." 
																			and idclasificador_presupuestario = ".$id_clasificador_impuestos."
																			and idfuente_financiamiento = '".$fuente_financiamiento."'
																			and idtipo_presupuesto = '".$tipo_presupuesto."'
																			and idordinal = '".$bus_ordinal_impuesto["idordinal"]."'")or die(mysql_error());
						$bus_maestro = mysql_fetch_array($sql_maestro_impuestos);
						
						
						$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
						
						
						//$disponible = $bus_maestro["monto_actual"] - $bus_maestro["total_compromisos"];
						$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras 
																							where idorden_compra_servicio = ".$idrendicion." 
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
																				idorden_compra_servicio = ".$idrendicion."
																				and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");				
						}else{
							// si existe disponibilidad para esa partida
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set 
																			estado = 'disponible', 
																			monto = '".$total_impuesto_imputable."',
																			monto_original = '".$total_impuesto_imputable."' 
																			where 
																			idorden_compra_servicio = ".$idrendicion."
																			and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
						}
						$partida_impuestos = $id_clasificador_impuestos;
					}
				}
				}else{
					$partida_impuestos = 0;
				}


				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_rendicion_caja_chica.exento) as exentos from articulos_rendicion_caja_chica, articulos_servicios where
											articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_rendicion_caja_chica.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_rendicion_caja_chica.idorden_compra_servicio = ".$idrendicion." ");
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"];
				
				if($destino_partida == 0 and $contribuyente_ordinario == "si"){
					$sql_total_impuesto = mysql_query("select SUM(impuesto) as totales_impuesto from articulos_rendicion_caja_chica, articulos_servicios where
												articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
												and articulos_rendicion_caja_chica.idarticulos_servicios = articulos_servicios.idarticulos_servicios
												and articulos_rendicion_caja_chica.idorden_compra_servicio = ".$idrendicion." and idpartida_impuesto = 0");
					$bus_total_impuesto = mysql_fetch_array($sql_total_impuesto);
					$total_impuesto = $bus_total_impuesto["totales_impuesto"];
					$total_imputable = $total_imputable + $total_impuesto;	
				}
				
					$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
																			and idcategoria_programatica = ".$idcategoria_programatica." 
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
																			idorden_compra_servicio = ".$idrendicion."
																			and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
				}else{	// si el total imputable es menor o igual al disponible
					$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
																			monto = '".$total_imputable."' ,
																			monto_original = '".$total_imputable."' 
																			where 
																			idorden_compra_servicio = ".$idrendicion."
																			and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."");
				}
					
							
				$sql = mysql_query("delete from partidas_orden_compra_servicio where idorden_compra_servicio = ".$idrendicion."
																							and monto <= 0");	
				// ********************************************* ELIMINAR PARTIDAS ************************************************
				
			}// CIERRE SI NO ES ARTICULO UNICO
		

}













if($ejecutar == "actualizarTotalesGenerales"){
	$sql_consulta= mysql_query("select * from orden_compra_servicio where idorden_compra_servicio= '".$idrendicion."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	echo number_format($bus_consulta["exento"],2,",",".")."|.|".
	number_format($bus_consulta["sub_total"],2,",",".")."|.|".
	number_format($bus_consulta["impuesto"],2,",",".")."|.|".
	number_format($bus_consulta["total"],2,",",".");
}



if($ejecutar == "actualizarTotalesFactura"){
	$sql_consulta= mysql_query("select SUM(total) as subtotal, SUM(impuesto) as impuestos, SUM(exento) as exentos 
										from 
										articulos_rendicion_caja_chica where 
							   			idorden_compra_servicio= '".$idrendicion."'
										and idfactura_rendicion_caja_chica= '".$idfactura."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	echo number_format($bus_consulta["exentos"],2,",",".")."|.|".
	number_format($bus_consulta["subtotal"],2,",",".")."|.|".
	number_format($bus_consulta["impuestos"],2,",",".")."|.|".
	number_format($bus_consulta["exentos"]+$bus_consulta["subtotal"]+$bus_consulta["impuestos"],2,",",".");
}
















// **********************************************************************************************************************************************
// **********************************************************************************************************************************************
// **********************************************************************************************************************************************
// ********************************************************* PROCESAR ORDEN *********************************************************************
// **********************************************************************************************************************************************
// **********************************************************************************************************************************************
// **********************************************************************************************************************************************
// **********************************************************************************************************************************************











if($ejecutar == "procesarOrden"){
	

	$sql_articulos_compra_servicio = mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = ".$idrendicion."")or die(mysql_error());
	$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$idrendicion."")or die(mysql_error());
	$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
	
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

/***************************************************************************************************************************
			$sql2 = mysql_query("update articulos_compra_servicio set total = '".$total."' 
																	 where 
																	  idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")or die("error update articulos".mysql_error());
																	  
			$sql_total = mysql_query("select * from articulos_compra_servicio where 
																	  idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")or die("error update articulos".mysql_error());
			$bus_total=mysql_fetch_array($sql_total);
			$total= $bus_total["total"];
			
******************************************************************************************************************************/


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
																	  idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")or die("error update articulos".mysql_error());
		
		
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
			$sql = mysql_query("select * from articulos_compra_servicio 
														where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]." 
															")or die("error todos los articulos".mysql_error());
			$bus = mysql_fetch_array($sql);
			//if($bus["tipo_carga_orden"] != "requisicion"){
			if($bus["estado"] == "aprobado" || $bus["estado"] == "sin disponibilidad"){
			
				if($destino_partida == 1 and $contribuyente_ordinario == "si"){
//*************************************************************************************************************					
					$sql_ordinal_impuesto = mysql_query("select * from ordinal where codigo='0000'");
					$bus_ordinal_impuesto= mysql_fetch_array($sql_ordinal_impuesto);
					
					
					
					$sql_maestro_impuestos = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
												and idcategoria_programatica = ".$idcategoria_programatica." 
												and idclasificador_presupuestario = ".$id_clasificador_impuestos."
												and idfuente_financiamiento = '".$idfuente_financiamiento."'
												and idtipo_presupuesto = '".$idtipo_presupuesto."'
												and idordinal = '".$bus_ordinal_impuesto["idordinal"]."'")or die(mysql_error());
																							
					
					$existen_requisiciones = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$idrendicion."");	
					$num_existen_requisiones = mysql_num_rows($existen_requisiciones);																	
					$bus_maestro = mysql_fetch_array($sql_maestro_impuestos);
					if ($num_existen_requisiones >0){
						$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
					}else{
						$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
					}
					
					//$disponible = $bus_maestro["monto_actual"] - $bus_maestro["total_compromisos"];
					$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras 
																where idorden_compra_servicio = ".$idrendicion." 
																	and idimpuestos = ".$id_impuestos."")or die(mysql_error());
																	
					$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);
					$total_impuesto_imputable = $bus_total_impuestos["total"];				

						if($total_impuesto_imputable > $disponible and $tipo_carga_orden != "requisicion"){
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
									monto = '".$total_impuesto_imputable."',
									monto_original = '".$total_impuesto_imputable."' 
									where idorden_compra_servicio = ".$idrendicion."
									and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("error partida impuesto".mysql_error());
							
						}else{
							$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
									monto = '".$total_impuesto_imputable."',
									monto_original = '".$total_impuesto_imputable."' 
									where idorden_compra_servicio = ".$idrendicion."
									and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")or die("error partida impuesto".mysql_error());
						}
					$partida_impuestos = $id_clasificador_impuestos;
				}else{
					$partida_impuestos = 0;
				}

				
				$sql2 = mysql_query("update articulos_compra_servicio set idpartida_impuesto = ".$partida_impuestos."  
																	where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")or die(mysql_error());
				
						
				$sql_imputable = mysql_query("select SUM(total) as totales, SUM(articulos_compra_servicio.exento) as exentos from articulos_compra_servicio, articulos_servicios 
										where articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
											and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_compra_servicio.idorden_compra_servicio = ".$idrendicion." ")or die(mysql_error());
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"] + $bus_imputable["exentos"];
				if ($destino_partida == 0 and $contribuyente_ordinario == "si"){
					$sql_total_impuesto = mysql_query("select SUM(impuesto) as totales_impuesto from articulos_compra_servicio, articulos_servicios where
												articulos_servicios.idclasificador_presupuestario = ".$id_clasificador_presupuestario."
												and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
												and articulos_compra_servicio.idorden_compra_servicio = ".$idrendicion." and idpartida_impuesto = 0")or die(mysql_error());
					$bus_total_impuesto = mysql_fetch_array($sql_total_impuesto);
					$total_impuesto = $bus_total_impuesto["totales_impuesto"];
				}				
				$total_imputable = $total_imputable+$total_impuesto;
//*********************************************************************************				
				
				
				$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = ".$anio." 
												and idcategoria_programatica = ".$idcategoria_programatica." 
												and idclasificador_presupuestario = ".$id_clasificador_presupuestario."
												and idfuente_financiamiento = '".$idfuente_financiamiento."'
												and idtipo_presupuesto = '".$idtipo_presupuesto."'
												and idordinal = '".$idordinal."'")or die(mysql_error());
				
				//echo "ID ORDEN_COMPRA:".$idrendicion;
				
				$existen_requisiciones = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$idrendicion."");	
				$num_existen_requisiones = mysql_num_rows($existen_requisiciones);																	
				$bus_maestro = mysql_fetch_array($sql_maestro);
				if ($num_existen_requisiones >0){
					$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				}else{
					$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				}
				
				//$disponible = $bus_maestro["monto_actual"] - $bus_maestro["total_compromisos"];
				
					if($total_imputable > $disponible and $tipo_carga_orden != "requisicion"){
						$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
																monto = '".$total_imputable."',
																monto_original = '".$total_imputable."' 
																where idorden_compra_servicio = ".$idrendicion."
																and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")or die(mysql_error());
						$estado = "sin disponibilidad";
					}else{
						$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
																	monto = '".$total_imputable."',
																	monto_original = '".$total_imputable."' 
																	where idorden_compra_servicio = ".$idrendicion."
																	and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")or die(mysql_error());
					
						$estado = "aprobado";
					}
				
			}else{
				$estado = "rechazado";
			}
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA		
				
				
				
			$sql2 = mysql_query("update articulos_compra_servicio set estado = '".$estado."' 
													where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")or die(mysql_error());
			
	
	//*****************************************************************************************
	}// CIERRE DEL WHILE DE CONSULTA DE LOS ARTICULOS DE LA ORDEN





	$sql_articulos = mysql_query("select * from articulos_rendicion_caja_chica 
												where idorden_compra_servicio = ".$idrendicion."")or die(mysql_error());
	$num_articulos = mysql_num_rows($sql_articulos);
	
	if($num_articulos != 0){
		$sql_orden_duplicados = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$idrendicion."");
		$bus_orden_duplicados = mysql_fetch_array($sql_orden_duplicados);
			if($bus_orden_duplicados["duplicados"] == 0){
				$sql_articulos = mysql_query("select * from articulos_compra_servicio 
														where idorden_compra_servicio = ".$idrendicion." 
														and (estado = 'rechazado' or estado = 'sin disponibilidad')")or die(mysql_error());
		$num_articulos = mysql_num_rows($sql_articulos);
		
		if($num_articulos == 0){
			$sql_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where 
														idorden_compra_servicio = ".$idrendicion." 
														and (estado = 'rechazado' or estado = 'sin disponibilidad')")or die(mysql_error());
			$num_impuestos = mysql_num_rows($sql_impuestos);
			
			if($num_impuestos == 0){
				$sql_actualizar_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = ".$idrendicion."")or die(mysql_error());
				while($bus_actualizar_partidas = mysql_fetch_array($sql_actualizar_partidas)){
					$sql_maestro = mysql_query("update maestro_presupuesto set 
															total_compromisos = total_compromisos + ".$bus_actualizar_partidas["monto"]."
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
					
				$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$idrendicion."")or die(mysql_error());
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
																					where idorden_compra_servicio = ".$idrendicion."")or die("error".mysql_error());
				
				
				
				
				
			//	echo "select * from relacion_compra_requisicion where idorden_compra = ".$idrendicion."";
				$sql_relacion_compra_requisicion = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$idrendicion."")or die("ERROR EN EL SELECT ".mysql_error());
				
				while ($bus_relacion_compra_requisicion = mysql_fetch_array($sql_relacion_compra_requisicion))
				{
				//echo "update requisicion set estado = 'ordenado' where idrequisicion = ".$bus_relacion_compra_requisicion["idrequisicion"]."";
					$sql_actualizar_requisicion = mysql_query("update requisicion set estado = 'ordenado' where idrequisicion = ".$bus_relacion_compra_requisicion["idrequisicion"]."")or die("ERROR EN EL UPDATE".mysql_error());
					
					$partidas_requisicion = mysql_query("select * from partidas_requisiciones where idrequisicion = ".$bus_relacion_compra_requisicion["idrequisicion"]."");
					while ($bus_partidas_requision = mysql_fetch_array($partidas_requisicion)){
						$sql_actualizar_partidas_requisicion = mysql_query("update maestro_presupuesto set pre_compromiso = pre_compromiso - ".$bus_partidas_requision["monto"]." where idRegistro = ".$bus_partidas_requision["idmaestro_presupuesto"]."")or die("ERROR EN EL UPDATE".mysql_error());
					
					}
					
				}			
				
				
				
				$sql_relacion_compra_solicitud = mysql_query("select * from relacion_compra_solicitud_cotizacion where idorden_compra = ".$idrendicion."");
				
				while ($bus_relacion_compra_solicitud = mysql_fetch_array($sql_relacion_compra_solicitud))
				{
					$sql_actualizar_solicitud = mysql_query("update solicitud_cotizacion set estado = 'ordenado', 
																						nro_orden = '".$codigo_orden."' 
																					where idsolicitud_cotizacion = ".$bus_relacion_compra_solicitud["idsolicitud_cotizacion"]."");
				}
				
				/*$sql_relacion_compra_requisicion = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$idrendicion."");
				
				while ($bus_relacion_compra_requisicion = mysql_fetch_array($sql_relacion_compra_requisicion))
				{
					$sql_actualizar_requisicion = mysql_query("update requisicion set estado = 'ordenado', 
																						nro_orden = '".$codigo_orden."' 
																					where idrequisicion = ".$bus_relacion_compra_requisicion["idrequision"]."");
				}*/	
					
					
					
					
				// ACTUALIZAR EL ULTIMO COSTO DE LOS PRODUCTOS
				$sql_select_articulos_compra = mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = '".$idrendicion."'");
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
					echo "exito|.|".$codigo_orden;
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

// AQUI VA LA CONSULTA DE LOS MATERIALES YA INGRESADOS





















if($ejecutar == "anularOrden"){
	
/*$sql = mysql_query("select * from usuarios where login = '".$login."' and clave = '".md5($clave)."'");
$num = mysql_num_rows($sql);
if($num > 0){*/

	$sql_orden = mysql_query("update orden_compra_servicio 
							 			set 
										estado = 'anulado' 
										where 
										idorden_compra_servicio = ".$idrendicion."")or die(mysql_error());
	$sql_actualizar_partidas = mysql_query("select * from partidas_orden_compra_servicio 
										   where idorden_compra_servicio = ".$idrendicion."")or die(mysql_error());
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
							idcategoria_programatica= '".$idcategoria_programatica."'
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
						
						$sql_clasificador = mysql_query("select * clasificador_presupuestario 
														where idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."' 
														and sub_especifica != '00'")or die("ERROR CONSULTANDO EL CLASIFICADOR ".mysql_error());
						$num_clasificador = mysql_num_rows($sql_clasificador);
						if($num_clasificador > 0){
							$bus_clasificador = mysql_fetch_array($sql_clasificador);
							$sql_consulta_clasificador = mysql_query("select * from 
							clasificador_presupuestario 
							where partida = '".$bus_clasificador["partida"]."'
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
	
	$sql_relacion_requisicion = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$idrendicion."");
	while($bus_relacion_requisicion = mysql_fetch_array($sql_relacion_requisicion)){
		$sql_insert_relacion_compras = mysql_query("update requisicion set estado = 'procesado' where idrequisicion = '".$bus_relacion_requisicion["idrequisicion"]."'");
		
		$partidas_requisicion = mysql_query("select * from partidas_requisiciones where idrequisicion = ".$bus_relacion_requisicion["idrequisicion"]."");
			while ($bus_partidas_requision = mysql_fetch_array($partidas_requisicion)){
				$sql_actualizar_partidas_requisicion = mysql_query("update maestro_presupuesto set pre_compromiso = pre_compromiso + ".$bus_partidas_requision["monto"]."
											 where idRegistro = ".$bus_partidas_requision["idmaestro_presupuesto"]."")or die("ERROR EN EL UPDATE".mysql_error());
			
			}

	}
	
	$sql_relacion_solicitud = mysql_query("select * from relacion_compra_solicitud_cotizacion where idorden_compra = ".$idrendicion."");
	while($bus_relacion_solicitud = mysql_fetch_array($sql_relacion_solicitud)){
		$sql_insert_relacion_compras = mysql_query("update solicitud_cotizacion set estado = 'procesado' where idsolicitud_cotizacion = '".$bus_relacion_solicitud["idsolicitud_cotizacion"]."'");

	}
	
	
	if($sql_orden){
		echo "exito";
		registra_transaccion("Anular orden Compra (".$idrendicion.")",$login,$fh,$pc,'orden_compra_servicios');
	}else{
		registra_transaccion("Anular Orden Compra ERROR (".$idrendicion.")",$login,$fh,$pc,'orden_compra_servicios');
		echo "fallo";
	}
	/*}else{
		echo "claveIncorrecta";
	}*/
	
}









if($ejecutar == "consultarOrden"){
	//echo $idrendicion;
	$sql_consultar = mysql_query("select 
								 	uj.denominacion as denominacion_categoria,
									oc.idcategoria_programatica,
									oc.justificacion,
									b.nombre as nombre_beneficiario,
									b.idbeneficiarios,
									oc.idtipo_caja_chica,
									oc.estado,
									oc.fecha_orden,
									oc.fecha_elaboracion,
									oc.numero_orden,
									oc.idfuente_financiamiento,
									oc.idtipo_presupuesto,
									oc.anio
										from 
									orden_compra_servicio oc,
									unidad_ejecutora uj,
									beneficiarios b,
									categoria_programatica ct
										where 
									oc.idorden_compra_servicio = '".$idrendicion."'
									and ct.idcategoria_programatica = oc.idcategoria_programatica
									and uj.idunidad_ejecutora = ct.idunidad_ejecutora
									and b.idbeneficiarios = oc.idbeneficiarios")or die(mysql_error());
	$bus_consultar = mysql_fetch_array($sql_consultar);
	
	
	echo $bus_consultar["denominacion_categoria"]."|.|".
	$bus_consultar["idcategoria_programatica"]."|.|".
	$bus_consultar["justificacion"]."|.|".
	$bus_consultar["nombre_beneficiario"]."|.|".
	$bus_consultar["idbeneficiarios"]."|.|".
	$bus_consultar["idtipo_caja_chica"]."|.|".
	$bus_consultar["estado"]."|.|".
	$bus_consultar["numero_orden"]."|.|".
	$bus_consultar["idfuente_financiamiento"]."|.|".
	$bus_consultar["idtipo_presupuesto"]."|.|".
	$bus_consultar["anio"]."|.|".
	$bus_consultar["tipo"]."|.|".
	$bus_consultar["fecha_orden"]."|.|".
	$bus_consultar["fecha_elaboracion"]."|.|";
}

?>
