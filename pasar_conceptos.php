<?
include("conf/conex.php");
Conectarse();
		
	$sql_conceptos = mysql_query("SELECT *
FROM gestion_2013.articulos_servicios
WHERE tipo_concepto != ''");	


												
		while($bus_consultar = mysql_fetch_array($sql_conceptos)){
				$sql_ingresar = mysql_query("insert into gestion_2014articulos_servicios (idarticulos_servicios,
															codigo,
															tipo,
															descripcion,
															idunidad_medida,
															idramo_articulo,
															activo,
															idclasificador_presupuestario,
															idsnc_detalle_grupo,
															idimpuestos,
															exento,
															ultimo_costo,
															fecha_ultima_compra,
															tipo_concepto,
															status,
															usuario,
															fechayhora,
															idordinal)values('".$bus_consultar["idarticulos_servicios"]."',
																				'".$bus_consultar["codigo"]."',
																				'".$bus_consultar["tipo"]."',
																				'".$bus_consultar["descripcion"]."',
																				'".$bus_consultar["idunidad_medida"]."',
																				'".$bus_consultar["idramo_articulo"]."',
																				'".$bus_consultar["activo"]."',
																				'".$bus_consultar["id_clasificador_presupuestario"]."',
																				'".$bus_consultar["id_snc_detalle_grupo"]."',
																				'".$bus_consultar["idimpuesto"]."',
																				'".$bus_consultar["exento"]."',
																				'".$bus_consultar["ultimo_costo"]."',
																				'".$bus_consultar["fecha_ultima_compra"]."',
																				'".$bus_consultar["tipo_concepto"]."',
																				'a',
																				'".$bus_consultar["usuario"]."',
																				'".$bus_consultar["fechayhora"]."',
																				'".$bus_consultar["idordinal"]."'
																				)")or die(" error ingresando ".mysql_error());
			
		}

?>