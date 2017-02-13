<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
extract($_POST);
Conectarse();

if($ejecutar == "ingresarTerreno"){

$sql_ingresar_terrenos = mysql_query("insert into terrenos(idtipo_movimiento,
														  iddetalle_catalogo_bienes,
														  estado_municipio,
														  denominacion_inmueble,
														  clasificacion_agricultura,
														  clasificacion_ganaderia,
														  clasificacion_mixto_agropecuario,
														  clasificacion_otros,
														  ubicacion_municipio,
														  ubicacion_territorio,
														  area_total_terreno_hectarias,
														  area_total_terreno_metros,
														  area_construccion_metros,
														  tipografia_plana,
														  tipografia_semiplana,
														  tipografia_pendiente,
														  tipografia_muypendiente,
														  cultivos_permanentes,
														  cultivos_deforestados,
														  otros_bosques,
														  otros_tierras_incultas,
														  otros_noaprovechables,
														  potreros_naturales,
														  potreros_cultivados,
														  recursos_cursos,
														  recursos_manantiales,
														  recursos_canales,
														  recursos_embalses,
														  recursos_pozos,
														  recursos_acuaductos,
														  recursos_otros,
														  cercas_longitud,
														  cercas_estantes,
														  cercas_material,
														  vias_interiores,
														  otras_bienhechurias,
														  linceros,
														  estudio_legal,
														  contabilidad_fecha,
														  contabilidad_valor,
														  adicionales_fecha,
														  adicionales_valor,
														  adicionales_fecha2,
														  adicionales_valor2,
														  adicionales_fecha3,
														  adicionales_valor3,
														  adicionales_fecha4,
														  adicionales_valor4,
														  adicionales_fecha5,
														  adicionales_valor5,
														  avaluo_hectarias,
														  avaluo_bs,
														  avaluo_hectarias2,
														  avaluo_bs2,
														  avaluo_hectarias3,
														  avaluo_bs3,
														  planos_esquemas_fotografias,
														  preparado_por,
														  lugar,
														  fecha,
														  cargo,
														  status,
														  usuario,
														  fechayhora,
														  codigo_bien,
														  organizacion)values('".$idtipo_movimiento."',
																			  '".$iddetalle_catalogo_bienes."',
																			  '".$estado_municipio."',
																			  '".$denominacion_inmueble."',
																			  '".$clasificacion_agricultura."',
																			  '".$clasificacion_ganaderia."',
																			  '".$clasificacion_mixto_agropecuario."',
																			  '".$clasificacion_otros."',
																			  '".$ubicacion_municipio."',
																			  '".$ubicacion_territorio."',
																			  '".$area_total_terreno_hectarias."',
																			  '".$area_total_terreno_metros."',
																			  '".$area_construccion_metros."',
																			  '".$tipografia_plana."',
																			  '".$tipografia_semiplana."',
																			  '".$tipografia_pendiente."',
																			  '".$tipografia_muypendiente."',
																			  '".$cultivos_permanentes."',
																			  '".$cultivos_deforestados."',
																			  '".$otros_bosques."',
																			  '".$otros_tierras_incultas."',
																			  '".$otros_noaprovechables."',
																			  '".$potreros_naturales."',
																			  '".$potreros_cultivados."',
																			  '".$recursos_cursos."',
																			  '".$recursos_manantiales."',
																			  '".$recursos_canales."',
																			  '".$recursos_embalses."',
																			  '".$recursos_pozos."',
																			  '".$recursos_acuaductos."',
																			  '".$recursos_otros."',
																			  '".$cercas_longitud."',
																			  '".$cercas_estantes."',
																			  '".$cercas_material."',
																			  '".$vias_interiores."',
																			  '".$otras_bienhechurias."',
																			  '".$linceros."',
																			  '".$estudio_legal."',
																			  '".$contabilidad_fecha."',
																			  '".$contabilidad_valor."',
																			  '".$adicionales_fecha."',
																			  '".$adicionales_valor."',
																			  '".$adicionales_fecha2."',
																			  '".$adicionales_valor2."',
																			  '".$adicionales_fecha3."',
																			  '".$adicionales_valor3."',
																			  '".$adicionales_fecha4."',
																			  '".$adicionales_valor4."',
																			  '".$adicionales_fecha5."',
																			  '".$adicionales_valor5."',
																			  '".$avaluo_hectarias."',
																			  '".$avaluo_bs."',
																			  '".$avaluo_hectarias2."',
																			  '".$avaluo_bs2."',
																			  '".$avaluo_hectarias3."',
																			  '".$avaluo_bs3."',
																			  '".$planos_esquemas_fotografias."',
																			  '".$preparado_por."',
																			  '".$lugar."',
																			  '".$fecha."',
																			  '".$cargo."',
																			  'a',
																			  '".$login."',
																			  '".$fh."',
																			  '".$codigo_bien."',
																			  '".$organizacion."')")or die(mysql_error());
	
	if($sql_ingresar_terrenos){
		echo "exito";
	}else{
		echo "fallo";
	}
}








if($ejecutar == "editarTerreno"){

$sql_ingresar_terrenos = mysql_query("update terrenos set idtipo_movimiento = '".$idtipo_movimiento."',
														  iddetalle_catalogo_bienes = '".$iddetalle_catalogo_bienes."',
														  estado_municipio = '".$estado_municipio."',
														  denominacion_inmueble = '".$denominacion_inmueble."',
														  clasificacion_agricultura = '".$clasificacion_agricultura."',
														  clasificacion_ganaderia = '".$clasificacion_ganaderia."',
														  clasificacion_mixto_agropecuario = '".$clasificacion_mixto_agropecuario."',
														  clasificacion_otros = '".$clasificacion_otros."',
														  ubicacion_municipio = '".$ubicacion_municipio."',
														  ubicacion_territorio = '".$ubicacion_territorio."',
														  area_total_terreno_hectarias = '".$area_total_terreno_hectarias."',
														  area_total_terreno_metros = '".$area_total_terreno_metros."',
														  area_construccion_metros = '".$area_construccion_metros."',
														  tipografia_plana = '".$tipografia_plana."',
														  tipografia_semiplana = '".$tipografia_semiplana."',
														  tipografia_pendiente = '".$tipografia_pendiente."',
														  tipografia_muypendiente = '".$tipografia_muypendiente."',
														  cultivos_permanentes =  '".$cultivos_permanentes."',
														  cultivos_deforestados = '".$cultivos_deforestados."',
														  otros_bosques = '".$otros_bosques."',
														  otros_tierras_incultas = '".$otros_tierras_incultas."',
														  otros_noaprovechables = '".$otros_noaprovechables."',
														  potreros_naturales = '".$potreros_naturales."',
														  potreros_cultivados = '".$potreros_cultivados."',
														  recursos_cursos = '".$recursos_cursos."',
														  recursos_manantiales = '".$recursos_manantiales."',
														  recursos_canales = '".$recursos_canales."',
														  recursos_embalses = '".$recursos_embalses."',
														  recursos_pozos = '".$recursos_pozos."',
														  recursos_acuaductos = '".$recursos_acuaductos."',
														  recursos_otros = '".$recursos_otros."',
														  cercas_longitud = '".$cercas_longitud."',
														  cercas_estantes = '".$cercas_estantes."',
														  cercas_material = '".$cercas_material."',
														  vias_interiores = '".$vias_interiores."',
														  otras_bienhechurias = '".$otras_bienhechurias."',
														  linceros = '".$linceros."',
														  estudio_legal = '".$estudio_legal."',
														  contabilidad_fecha = '".$contabilidad_fecha."',
														  contabilidad_valor = '".$contabilidad_valor."',
														  adicionales_fecha = '".$adicionales_fecha."',
														  adicionales_valor = '".$adicionales_valor."',
														  adicionales_fecha2 = '".$adicionales_fecha2."',
														  adicionales_valor2 = '".$adicionales_valor2."',
														  adicionales_fecha3 = '".$adicionales_fecha3."',
														  adicionales_valor3 = '".$adicionales_valor3."',
														  adicionales_fecha4 = '".$adicionales_fecha4."',
														  adicionales_valor4 = '".$adicionales_valor4."',
														  adicionales_fecha5 = '".$adicionales_fecha5."',
														  adicionales_valor5 = '".$adicionales_valor5."',
														  avaluo_hectarias = '".$avaluo_hectarias."',
														  avaluo_bs = '".$avaluo_bs."',
														  avaluo_hectarias2 = '".$avaluo_hectarias2."',
														  avaluo_bs2 = '".$avaluo_bs2."',
														  avaluo_hectarias3 = '".$avaluo_hectarias3."',
														  avaluo_bs3 = '".$avaluo_bs3."',
														  planos_esquemas_fotografias = '".$planos_esquemas_fotografias."',
														  preparado_por = '".$preparado_por."',
														  lugar= '".$lugar."',
														  fecha = '".$fecha."',
														  cargo = '".$cargo."',
														  codigo_bien = '".$codigo_bien."',
														  organizacion = '".$organizacion."'
														  where idterrenos = '".$idterreno."'")or die(mysql_error());
	
	if($sql_ingresar_terrenos){
		echo "exito";
	}else{
		echo "fallo";
	}
}




if($ejecutar == "eliminarTerreno"){
	$sql_eliminar = mysql_query("delete from terrenos where idterrenos = '".$idterreno."'");
	if($sql_eliminar){
		echo "exito";
	}else{
		echo "fallo";
	}
}






if($ejecutar == "cambiarMunicipios"){
	?>
	<select id="ubicacion_municipio" name="ubicacion_municipio">
	<option value="0">.:: Seleccine el Municipio ::.</option>
	<?
	$sql_municipios = mysql_query("select * from municipios where idestado = '".$idestado."'");
	while($bus_municipios = mysql_fetch_array($sql_municipios)){
		?>
		<option value="<?=$bus_municipios["idmunicipios"]?>"><?=$bus_municipios["denominacion"]?></option>
		<?
	}
	?>
	</select>
	<?
}



if($ejecutar == "validarCodigoBien"){
	$sql_validar = mysql_query("select * from terrenos where codigo_bien = '".$valor."'")or die(mysql_error());
	$num_validar = mysql_num_rows($sql_validar);
	if($num_validar > 0){
		echo "existe";
	}
}

?>