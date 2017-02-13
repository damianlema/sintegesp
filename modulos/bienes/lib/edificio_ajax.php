<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
extract($_POST);
Conectarse();

if($ejecutar == "ingresarEdificio"){
 //var_dump($_POST);
 //echo "ID: ".$idtipo_movimiento;
 
 $entro = 0;
	while ($entro == 0){
		$validar_codigo_bien = mysql_query("select * from muebles where codigo_bien = '".$codigo_bien."'");
		if(mysql_num_rows($validar_codigo_bien) > 0){
			$sql_inventario_materia = mysql_query("select * from relacion_contadores")or die(mysql_error());
			$bus_inventario_materia = mysql_fetch_array($sql_inventario_materia);
			$codigo_bien = str_pad($bus_inventario_materia["contador_bienes_inmuebles"]+1, 13, "0", STR_PAD_LEFT);
			$actualiza = mysql_query("update relacion_contadores set contador_bienes_inmuebles = contador_bienes_inmuebles+1");
		}else{
			$sql_inventario_materia = mysql_query("select * from relacion_contadores")or die(mysql_error());
			$bus_inventario_materia = mysql_fetch_array($sql_inventario_materia);
			$codigo_bien_automatico = $bus_inventario_materia["contador_bienes_inmuebles"];
			$codigo_bien = str_pad($bus_inventario_materia["contador_bienes_inmuebles"], 13, "0", STR_PAD_LEFT);
			$entro = 1;
		}
	}
 
 
 $sql_insertar_datos = mysql_query("insert into edificios(
								idtipo_movimiento,
								  iddetalle_catalogo_bienes,
								  estado_municipio_propietario,
								  denominacion_inmueble,
								  clasificacion_funcional_inmueble,
								  ubicacion_geografica_estado,
								  ubicacion_geografica_municipio,
								  ubicacion_geografica_direccion,
								  area_terreno,
								  area_construccion,
								  numero_pisos,
								  area_total_construccion,
								  area_anexidades,
								  tipo_estructura,
								  pisos,
								  paredes,
								  techos,
								  puertas_ventanas,
								  servicios,
								  otras_anexidades,
								  linderos,
								  estado_legal,
								  valor_contabilidad_fecha,
								  valor_contabilidad_monto,
								  mejoras_fecha,
								  mejoras_valor,
								  mejoras_fecha2,
								  mejoras_valor2,
								  mejoras_fecha3,
								  mejoras_valor3,
								  mejoras_fecha4,
								  mejoras_valor4,
								  mejoras_fecha5,
								  mejoras_valor5,
								  avaluo_provicional,
								  planos_esquemas_fotocopias,
								  preparado_por,
								  lugar,
								  fecha,
								  cargo,
								  status,
								  usuario,
								  fechayhora,
								  organizacion,
								  codigo_bien)values(
													'".$idtipo_movimiento."',
													  '".$iddetalle_catalogo_bienes."',
													  '".$estado_municipio_propietario."',
													  '".$denominacion_inmueble."',
													  '".$clasificacion_funcional_inmueble."',
													  '".$ubicacion_geografica_estado."',
													  '".$ubicacion_geografica_municipio."',
													  '".$ubicacion_geografica_direccion."',
													  '".$area_terreno."',
													  '".$area_construccion."',
													  '".$numero_pisos."',
													  '".$area_total_construccion."',
													  '".$area_anexidades."',
													  '".$tipo_estructura."',
													  '".$pisos."',
													  '".$paredes."',
													  '".$techos."',
													  '".$puertas_ventanas."',
													  '".$servicios."',
													  '".$otras_anexidades."',
													  '".$linderos."',
													  '".$estado_legal."',
													  '".$valor_contabilidad_fecha."',
													  '".$valor_contabilidad_monto."',
													  '".$mejoras_fecha."',
													  '".$mejoras_valor."',
													  '".$mejoras_fecha2."',
													  '".$mejoras_valor2."',
													  '".$mejoras_fecha3."',
													  '".$mejoras_valor3."',
													  '".$mejoras_fecha4."',
													  '".$mejoras_valor4."',
													  '".$mejoras_fecha5."',
													  '".$mejoras_valor5."',
													  '".$avaluo_provicional."',
													  '".$planos_esquemas_fotocopias."',
													  '".$preparado_por."',
													  '".$lugar."',
													  '".$fecha."',
													  '".$cargo."',
													  'a',
													  '".$login."',
													  '".$fh."',
													  '".$organizacion."',
													  '".$codigo_bien."')"); 
	if($sql_insertar_datos){
		echo "Los datos fueron Registrados con Exito";
		if ($codigo_bien_automatico <> ""){
			
			$contador = $codigo_bien_automatico + 1;
			
			$sql_cambia_contador = mysql_query("update relacion_contadores set contador_bienes_muebles = '".$contador."'")or die(mysql_error());
			
		}	
	}else{
		echo "Discupe los datos no se pudieron guardar con exito, por favor intentelo mas tarde";
	}
	
	
}



//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************* AUTOGENERAR CODIGO ***************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************




if($ejecutar == "generar_codigo"){
	$sql_inventario_materia = mysql_query("select * from relacion_contadores")or die(mysql_error());
	$bus_inventario_materia = mysql_fetch_array($sql_inventario_materia);
	if ($bus_inventario_materia["contador_bienes_inmuebles"] > 0){
		$numero = $bus_inventario_materia["contador_bienes_inmuebles"];
		$codigo_con_ceros = str_pad($bus_inventario_materia["contador_bienes_inmuebles"], 13, "0", STR_PAD_LEFT);
	}else{
		$numero = 1;
		$codigo_con_ceros = str_pad($numero, 13, "0", STR_PAD_LEFT);
	}
	echo $numero."|.|".$codigo_con_ceros; 
}




if($ejecutar == "editarEdificio"){
	 $sql_editar_edificio = mysql_query("update edificios set
								idtipo_movimiento = '".$idtipo_movimiento."',
								  iddetalle_catalogo_bienes = '".$iddetalle_catalogo_bienes."',
								  estado_municipio_propietario = '".$estado_municipio_propietario."',
								  denominacion_inmueble = '".$denominacion_inmueble."',
								  clasificacion_funcional_inmueble = '".$clasificacion_funcional_inmueble."',
								  ubicacion_geografica_estado = '".$ubicacion_geografica_estado."',
								  ubicacion_geografica_municipio = '".$ubicacion_geografica_municipio."',
								  ubicacion_geografica_direccion = '".$ubicacion_geografica_direccion."',
								  area_terreno = '".$area_terreno."',
								  area_construccion = '".$area_construccion."',
								  numero_pisos = '".$numero_pisos."',
								  area_total_construccion =  '".$area_total_construccion."',
								  area_anexidades =  '".$area_anexidades."',
								  tipo_estructura =  '".$tipo_estructura."',
								  pisos = '".$pisos."',
								  paredes = '".$paredes."',
								  techos = '".$techos."',
								  puertas_ventanas = '".$puertas_ventanas."',
								  servicios = '".$servicios."',
								  otras_anexidades = '".$otras_anexidades."',
								  linderos = '".$linderos."',
								  estado_legal = '".$estado_legal."',
								  valor_contabilidad_fecha = '".$valor_contabilidad_fecha."',
								  valor_contabilidad_monto = '".$valor_contabilidad_monto."',
								  mejoras_fecha = '".$mejoras_fecha."',
								  mejoras_valor = '".$mejoras_valor."',
								  mejoras_fecha2 = '".$mejoras_fecha2."',
								  mejoras_valor2 = '".$mejoras_valor2."',
								  mejoras_fecha3 = '".$mejoras_fecha3."',
								  mejoras_valor3 = '".$mejoras_valor3."',
								  mejoras_fecha4 = '".$mejoras_fecha4."',
								  mejoras_valor4 = '".$mejoras_valor4."',
								  mejoras_fecha5 = '".$mejoras_fecha5."',
								  mejoras_valor5 = '".$mejoras_valor5."',
								  avaluo_provicional = '".$avaluo_provicional."',
								  planos_esquemas_fotocopias =  '".$planos_esquemas_fotocopias."',
								  preparado_por = '".$preparado_por."',
								  lugar = '".$lugar."',
								  fecha = '".$fecha."',
								  cargo = '".$cargo."',
								  organizacion = '".$organizacion."',
								   codigo_bien = '".$codigo_bien."'
								  where idedificios = '".$idedificio."'"); 
	if($sql_editar_edificio){
		echo "El Edificio se Actualizo con Exito";
	}else{
		echo "Disculpe no se pudieron actualizar los datos, por favor intente mas tarde";
	}
}





if($ejecutar == "eliminarEdificio"){
	$sql_eliminar = mysql_query("delete from edificios where idedificios = '".$idedificio."'");
	if($sql_eliminar){
		echo "El Edificio se Elimino con Exito";
	}else{
		echo "Disculpe el edificio no se pudo eliminar, por favor intente de nuevo mas tarde";
	}
}



if($ejecutar == "cambiarMunicipios"){
	?>
	<select id="ubicacion_geografica_municipio" name="ubicacion_geografica_municipio">
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
	$sql_validar = mysql_query("select * from edificios where codigo_bien = '".$valor."'")or die(mysql_error());
	$num_validar = mysql_num_rows($sql_validar);
	if($num_validar > 0){
		echo "existe";
	}
}
?>