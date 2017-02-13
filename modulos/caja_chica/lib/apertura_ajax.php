<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);



if($ejecutar == "ingresarDatosBasicos"){
	$sql_consultar = mysql_query("select * 
							 			FROM
									orden_compra_servicio oc,
									tipos_documentos td
										WHERE 
									oc.idcategoria_programatica = '".$idcategoria_programatica."'
									and idtipo_caja_chica = '".$tipo_caja_chica."'
									and YEAR(oc.fecha_orden) = '".$_SESSION["anio_fiscal"]."'
									and td.idtipos_documentos = oc.tipo
									and td.modulo like '%-16-%'
									and td.compromete = 'no'
									and td.causa = 'no'
									and td.paga = 'no'
									and oc.estado = 'procesado'");
	$num_consultar = mysql_num_rows($sql_consultar);
	
	if($num_consultar == 0){


	$sql_ingresar = mysql_query("insert into orden_compra_servicio (tipo,
																	fecha_orden,
																	fecha_elaboracion,
																	idtipo_caja_chica,
																	idcategoria_programatica,
																	idbeneficiarios,
																	justificacion,
																	ordenado_por,
																	cedula_ordenado,
																	sub_total,
																	total,
																	estado,
																	idtipo_presupuesto,
																	idfuente_financiamiento,
																	anio)VALUES('".$idtipos_documentos."',
																				'".date("Y-m-d")."',
																				'".date("Y-m-d")."',
																				'".$tipo_caja_chica."',
																				'".$idcategoria_programatica."',
																				'".$idbeneficiarios."',
																				'".$justificacion."',
																				'".$responsable."',
																				'".$ci_responsable."',
																				'".$monto."',
																				'".$monto."',
																				'elaboracion',
																				'".$tipo_presupuesto."',
																				'".$fuente_financiamiento."',
																				'".$anio."')");
	
	echo mysql_insert_id();

	}else{
		echo "categoria_existe";	
	}
}






if($ejecutar == "modificarDatosBasicos"){
	$sql_consultar = mysql_query("select * 
							 			FROM
									orden_compra_servicio oc,
									tipos_documentos td
										WHERE 
									oc.idcategoria_programatica = '".$idcategoria_programatica."'
									and idtipo_caja_chica = '".$tipo_caja_chica."'
									and YEAR(oc.fecha_orden) = '".$_SESSION["anio_fiscal"]."'
									and td.idtipos_documentos = oc.tipo
									and td.modulo like '%-16-%'
									and td.compromete = 'no'
									and td.causa = 'no'
									and td.paga = 'no'
									and oc.estado = 'procesado'");
	$num_consultar = mysql_num_rows($sql_consultar);
	
	if($num_consultar == 0){


	$sql_ingresar = mysql_query("update orden_compra_servicio set 
																	idtipo_caja_chica = '".$tipo_caja_chica."',
																	idcategoria_programatica = '".$idcategoria_programatica."',
																	idbeneficiarios = '".$idbeneficiarios."',
																	justificacion = '".$justificacion."',
																	ordenado_por = '".$responsable."',
																	cedula_ordenado = '".$ci_responsable."',
																	sub_total = '".$monto."',
																	total = '".$monto."'
																	idtipo_presupuesto = '".$tipo_presupuesto."',
																	idfuente_financiamiento = '".$fuente_financiamiento."',
																	anio = '".$anio."'
																		where 
																	idorden_compra_servicio = '".$idorden_compra_servicio."'");
	}else{
		echo "categoria_existe";	
	}
}










if($ejecutar == "procesarApertura"){
	$sql_consultar = mysql_query("select * 
							 			FROM
									orden_compra_servicio oc,
									tipos_documentos td
										WHERE 
									oc.idcategoria_programatica = '".$idcategoria_programatica."'
									and idtipo_caja_chica = '".$tipo_caja_chica."'
									and YEAR(oc.fecha_orden) = '".$_SESSION["anio_fiscal"]."'
									and td.idtipos_documentos = oc.tipo
									and td.modulo like '%-16-%'
									and td.compromete = 'no'
									and td.causa = 'no'
									and td.paga = 'no'
									and oc.estado = 'procesado'");
	$num_consultar = mysql_num_rows($sql_consultar);
	
	if($num_consultar == 0){			
				
				$tipo_orden = $idtipos_documentos;
				$anio_fiscal = $_SESSION["anio_fiscal"];
				
				
				$sql_nro_orden = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$tipo_orden."");
				$bus_nro_orden = mysql_fetch_array($sql_nro_orden);
				
				$id_a_actualizar = $tipo_orden;
				$codigo_orden = $bus_nro_orden["siglas"]."-".$anio_fiscal."-".$bus_nro_orden["nro_contador"];
				$nro_orden_compra = $bus_nro_orden["nro_contador"];


					$sql_existe_numero = mysql_query("select * from orden_compra_servicio where numero_orden = '".$codigo_orden."'")or die("cero".mysql_error());
					$bus_existe = mysql_num_rows($sql_existe_numero);
					
					while ($bus_existe > 0){
						$sql_actualizar_numero = mysql_query("update tipos_documentos set nro_contador = nro_contador + 1 where idtipos_documentos = ".$id_a_actualizar."")or die("uno".mysql_error());
						$sql_nro_orden = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$tipo_orden."");
						$bus_nro_orden = mysql_fetch_array($sql_nro_orden);
						$id_a_actualizar = $tipo_orden;
						$codigo_orden = $bus_nro_orden["siglas"]."-".$anio_fiscal."-".$bus_nro_orden["nro_contador"];
						$nro_orden_pago = $bus_nro_orden["nro_contador"];
						$sql_existe_numero = mysql_query("select * from orden_compra_servicio where numero_orden = '".$codigo_orden."'")or die("cero".mysql_error());
						$bus_existe = mysql_num_rows($sql_existe_numero);
					}
				
				
				
				// ACA SE GENERA EL NUMERO DE CONTROL DE LA ORDEN DE COMPRA


				$codigo_referencia = 90000000000+$nro_orden_compra;
				
				$sql_actualizar_orden = mysql_query("update orden_compra_servicio set estado = 'procesado', 
																						numero_orden = '".$codigo_orden."',
																						fecha_orden = '".$fecha_validada."',
																						codigo_referencia = '".$codigo_referencia."'
																					where idorden_compra_servicio = '".$id_orden_compra."'")or die("error".mysql_error());
	
	
		echo $codigo_orden;
	}else{
		echo "categoria_existe";
	}
}




if($ejecutar == "calcularMonto"){
	$sql_tipo_caja_chica = mysql_query("select * from tipo_caja_chica where idtipo_caja_chica = '".$idtipo_caja_chica."'");
	$bus_tipo_caja_chica = mysql_fetch_array($sql_tipo_caja_chica);
	
	$monto = $_SESSION["costo_ut"]*$bus_tipo_caja_chica["unidades_tributarias_aprobadas"];
	echo number_format($monto,2,",",".")."|.|".$monto;
}





if($ejecutar == "anularApertura"){
	//echo "update orden_compra_servicio set estado = 'anulado' where idorden_compra_servicio = '".$idorden_compra_servicio."'";
$sql_anular = mysql_query("update orden_compra_servicio set estado = 'anulado' where idorden_compra_servicio = '".$idorden_compra_servicio."'")or die(mysql_error());
}



if($ejecutar == "consultarApertura"){
	$sql_consultar = mysql_query("select
								 oc.idtipo_caja_chica,
								 cp.codigo,
								 ue.denominacion as nombre_categoria,
								 oc.total,
								 be.nombre as nombre_beneficiarios,
								 be.idbeneficiarios,
								 oc.justificacion,
								 oc.ordenado_por,
								 oc.cedula_ordenado,
								 oc.numero_orden,
								 oc.fecha_orden,
								 oc.fecha_elaboracion,
								 oc.estado,
								 oc.idfuente_financiamiento,
								 oc.idtipo_presupuesto,
								 oc.anio,
								  oc.tipo
								 	from 
								 orden_compra_servicio oc,
								 beneficiarios be,
								 categoria_programatica cp,
								 unidad_ejecutora ue
								 	where 
								oc.idorden_compra_servicio = '".$idorden_compra_servicio."'
								and be.idbeneficiarios = oc.idbeneficiarios
								and cp.idcategoria_programatica = oc.idcategoria_programatica
								and ue.idunidad_ejecutora = cp.idunidad_ejecutora")or die(mysql_error());
	$bus_consultar = mysql_fetch_array($sql_consultar);
	
	echo $bus_consultar["idtipo_caja_chica"]."|.|".
		"(".$bus_consultar["codigo"].")".$bus_consultar["nombre_categoria"]."|.|".
		$bus_consultar["idcategoria_programatica"]."|.|".
		number_format($bus_consultar["total"],2,",",".")."|.|".
		$bus_consultar["total"]."|.|".
		$bus_consultar["nombre_beneficiarios"]."|.|".
		$bus_consultar["idbeneficiarios"]."|.|".
		$bus_consultar["justificacion"]."|.|".
		$bus_consultar["ordenado_por"]."|.|".
		$bus_consultar["cedula_ordenado"]."|.|".
		$bus_consultar["numero_orden"]."|.|".
		$bus_consultar["fecha_orden"]."|.|".
		$bus_consultar["fecha_elaboracion"]."|.|".
		$bus_consultar["estado"]."|.|".
		$bus_consultar["idfuente_financiamiento"]."|.|".
		$bus_consultar["idtipo_presupuesto"]."|.|".
		$bus_consultar["anio"]."|.|".
		$bus_consultar["tipo"];	
}
?>