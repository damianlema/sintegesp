<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();

extract($_POST);




	$sql_orden_compra= mysql_query("select * 
								   			from 
											orden_compra_servicio,
											tipos_documentos
												where 
											orden_compra_servicio.estado != 'elaboracion' 
											and orden_compra_servicio.estado != 'anulado'
											and orden_compra_servicio.tipo = tipos_documentos.idtipos_documentos
											and (tipos_documentos.modulo like '%-13-%'
											or tipos_documentos.modulo like '%-1-%')
											and tipos_documentos.multi_categoria = 'si'
											and orden_compra_servicio.numero_orden = '".$numero_orden."'")or die(mysql_error());
	$bus_orden_compra = mysql_fetch_array($sql_orden_compra);
		
		$sql_eliminar_partidas = mysql_query("delete from partidas_orden_compra_servicio where idorden_compra_servicio = '".$bus_orden_compra["idorden_compra_servicio"]."'")or die(mysql_error());
		
		$sql_articulos_orden = mysql_query("select * from 
										   			articulos_compra_servicio 
														where 
													idorden_compra_servicio = '".$bus_orden_compra["idorden_compra_servicio"]."'");
		while($bus_articulos_orden = mysql_fetch_array($sql_articulos_orden)){
				
				
				$sql_articulos_servicios = mysql_query("select * from 
													   		articulos_servicios 
																where 
															idarticulos_servicios = '".$bus_articulos_orden["idarticulos_servicios"]."'");
				
				$bus_articulos_servicios = mysql_fetch_array($sql_articulos_servicios);
				
				
				$idcategoria_programatica =$bus_articulos_orden["idcategoria_programatica"];
				$idclasificador_presupuestario = $bus_articulos_servicios["idclasificador_presupuestario"];
				$idtipo_presupuesto = $bus_orden_compra["idtipo_presupuesto"];
				$anio = $bus_orden_compra["anio"];
				$idfuente_financiamiento = $bus_orden_compra["idfuente_financiamiento"];
				$idordinal = $bus_articulos_servicios["idordinal"];
					
				
		if($bus_articulos_servicios["tipo_concepto"] == 1){	
					$sql_maestro_presupuesto = mysql_query("select * from 
															maestro_presupuesto
																where
															idcategoria_programatica = '".$idcategoria_programatica."'
															and idclasificador_presupuestario = '".$idclasificador_presupuestario."'
															and idtipo_presupuesto = '".$idtipo_presupuesto."'
															and idfuente_financiamiento = '".$idfuente_financiamiento."'
															and anio = '".$anio."'
															and idordinal = '".$idordinal."'")or die("ERROR CONSULTANDO EL MAESTRO: ".mysql_error());

		//$bus_maestro = mysql_fetch_array($sql_maestro_presupuesto);
		$num_maestro = mysql_num_rows($sql_maestro_presupuesto);
			
			if($num_maestro == 0){ 
				$estado = "rechazado"; 
			}else{
				$bus_maestro = mysql_fetch_array($sql_maestro_presupuesto);
				$sql_imputable = mysql_query("select 
											 SUM(total) as totales, 
											SUM(articulos_compra_servicio.exento) as exentos 
												from 
											articulos_compra_servicio, 
											articulos_servicios 
												where
											articulos_servicios.idclasificador_presupuestario = ".$idclasificador_presupuestario."
											and articulos_compra_servicio.idcategoria_programatica = '".$idcategoria_programatica."'
											and articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
											and articulos_compra_servicio.idorden_compra_servicio = ".$bus_orden_compra["idorden_compra_servicio"]."");
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["totales"]; 
				$estado = "aprobado";
				$estado_partida = "disponible";

				$sql_partidas_orden_compra=mysql_query("select * from partidas_orden_compra_servicio where 
																		idorden_compra_servicio='".$bus_orden_compra["idorden_compra_servicio"]."' 
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'") 
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
																							values ('".$bus_orden_compra["idorden_compra_servicio"]."',
																									'".$bus_maestro["idRegistro"]."',
																									'".$total_imputable."',
																									'".$total_imputable."',
																									'".$estado_partida."',
																									'a',
																									'jbello',
																									'".date("Y-m-d H:i:s")."')")
																								or die("ERROR GUARDANDO PARTIDAS:". mysql_error());
				}else{ // DE LO CONTRARIO LA ACTUALIZO
					$actualiza_partida=mysql_query("update partidas_orden_compra_servicio set 
																		monto = ".$total_imputable.",
																		monto_original = ".$total_imputable.",
																		estado='".$estado_partida."' 
																		where idorden_compra_servicio=".$bus_orden_compra["idorden_compra_servicio"]." 
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")
																		or die ($total_item."ERROR MODIFICANDO PARTIDAS: ".mysql_error());
				}														
	
			}
		}else{
			$estado = "disponible";
		}

			$sql_update_articulos_compras = mysql_query("update articulos_compra_servicio set estado = '".$estado."' 
													where idarticulos_compra_servicio = ".$bus_articulos_orden["idarticulos_compra_servicio"]."");

				
				
				
				
				
				
				


				
				
		}
		echo "Listo: ".$bus_orden_compra["numero_orden"]."<br>";


?>