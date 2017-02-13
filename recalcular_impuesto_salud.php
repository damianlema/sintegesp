<?
set_time_limit(-1);
mysql_connect("localhost", "root", "1234");
mysql_select_db("gestion_respaldo_salud");



$sql_orden = mysql_query("select * from orden_compra_servicio where estado != 'elaboracion'");
while($bus_orden = mysql_fetch_array($sql_orden)){
$id_orden_compra = $bus_orden["idorden_compra_servicio"];
$tipo_presupuesto = $bus_orden["idtipo_presupuesto"];
$fuente_financiamiento =  $bus_orden["idfuente_financiamiento"];
$anio = $bus_orden["anio"];	
	$sql_beneficiario = mysql_query("select * from beneficiarios where idbeneficiarios = '".$bus_orden["idbeneficiarios"]."'");
	$bus_beneficiario = mysql_fetch_array($sql_beneficiario);
	
	$contribuyente_ordinario=$bus_beneficiario["contribuyente_ordinario"];
	
	
		$sql_articulos = mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
		while($bus_articulos = mysql_fetch_array($sql_articulos)){
		$cantidad = $bus_articulos["cantidad"];
		$precio_unitario = $bus_articulos["precio_unitario"];
		
		
		$id_material = $bus_articulos["idarticulos_servicios"];
		$total_articulo_individual = $cantidad * $precio_unitario;
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

		
		$id_ultimo_generado = $bus_articulos["idarticulos_compra_servicio"]; 
		
		
		
		if ($destino_partida<>0 and $contribuyente_ordinario=="si"){ // SI EL IMPUESTO TIENE PARTIDA PROPIA
			
				$sql2=mysql_query("update relacion_impuestos_ordenes_compras set 
										base_calculo = '".$total_articulo_individual."',
										base_calculo_original = '".$total_articulo_individual."',
										total= ".$total_impuesto_individual." 
										where idorden_compra_servicio = '".$id_orden_compra."'")or die("3333333 ".mysql_error());
			}
			
			// VALIDO LA PARTIDA DEL IMPUESTO EXISTA EN EL MAESTRO DE PRESUPUESTO 
			
									// consulta maestro con el clasificador de impuesto

			$sql_ordinal_impuesto= mysql_query("select * from ordinal where codigo='0000'");
			$bus_ordinal_impuesto= mysql_fetch_array($sql_ordinal_impuesto);
			
			
			
						
			$sql_maestro = mysql_query("SELECT * FROM 
												maestro_presupuesto 
											WHERE 
												anio 							= '".$anio."' 
											AND idcategoria_programatica 		= '".$bus_orden["idcategoria_programatica"]."'  
											AND idclasificador_presupuestario 	= '".$id_clasificador_impuestos."' 
											AND idfuente_financiamiento 		= '".$fuente_financiamiento."' 
											AND idtipo_presupuesto 				= '".$tipo_presupuesto."' 
											AND idordinal 						= '".$bus_ordinal_impuesto["idordinal"]."'");
			
			
			while($bus_maestro = mysql_fetch_array($sql_maestro)){
			$num_maestro_impuesto = mysql_num_rows($sql_maestro);

				
				$sql_total_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where 
																		idorden_compra_servicio = ".$id_orden_compra." 
																	and idimpuestos = ".$id_impuestos."");
				$bus_total_impuestos = mysql_fetch_array($sql_total_impuestos);	
						

					$actualiza_partida=mysql_query("update partidas_orden_compra_servicio set 
																		monto = ".$total_impuesto_individual." ,
																		monto_original = ".$total_impuesto_individual." ,
																		estado='disponible' 
																		where idorden_compra_servicio=".$id_orden_compra." 
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")
																		or die ("555 ".mysql_error());
					

			
			}
		}
		echo "Lista la orden Nro: ".$bus_orden["numero_orden"]." --- Total individual de Impuesto: ".$total_impuesto_individual."<br>";
}
?>