<?
session_start();
include("../../../conf/conex.php");
Conectarse();


function listar_foros($padre, $titulo) {
	global $foros;
	foreach($foros[$padre] as $foro => $datos) {			
		if(isset($foros[$foro])) {
			$nuevo_titulo = ($titulo == '') ? $datos['denominacion'] : "$titulo - {$datos['denominacion']} -";
			listar_foros($foro, $nuevo_titulo);
		}else{
		?>
		<option value="<?=$datos['idniveles_organizacionales']?>">
			<?=$titulo ." - ". $datos['denominacion']?>
		</option>
		<?
		}
	}
}



if($ejecutar == "seleccionarNivelOrganizacionalMueble"){
	  $foros = array();
	  $result = mysql_query("SELECT idniveles_organizacionales, 
									denominacion, 
									sub_nivel 
										FROM 
									niveles_organizacionales 
										where 
									organizacion = '".$idorganizacion."'
									and modulo = '".$_SESSION["modulo"]."'") or die(mysql_error());
	  while($row = mysql_fetch_assoc($result)) {
		  $foro = $row['idniveles_organizacionales'];
		  $padre = $row['sub_nivel'];
		  if(!isset($foros[$padre]))
			  $foros[$padre] = array();
		  $foros[$padre][$foro] = $row;
	  }
	
		?>
		<select id="nivel_organizacional_mueble" name="nivel_organizacional_mueble">
		<?
			listar_foros(0, '');
		?>
		</select>
		<?
		return;
}





if($ejecutar == "seleccionarNivelOrganizacionalDestinoMueble"){
	$foros = array();
	  $result = mysql_query("SELECT idniveles_organizacionales, 
									denominacion, 
									sub_nivel 
										FROM 
									niveles_organizacionales 
										where 
									organizacion = '".$idorganizacion."'
									and modulo = '".$_SESSION["modulo"]."'") or die(mysql_error());
	  while($row = mysql_fetch_assoc($result)) {
		  $foro = $row['idniveles_organizacionales'];
		  $padre = $row['sub_nivel'];
		  if(!isset($foros[$padre]))
			  $foros[$padre] = array();
		  $foros[$padre][$foro] = $row;
	  }
	
		?>
		<select id="nivel_organizacional_destino_mueble" name="nivel_organizacional_destino_mueble">
		<?
			listar_foros(0, '');
		?>
		</select>
		<?
		return;
}




if($ejecutar == "ingresarMovimiento"){
	$sql_ingresar = mysql_query("insert into movimientos_bienes_individuales (tipo,
											  codigo_bien,
											  idcatalogo_bienes,
											  especificaciones,
											  idorganizacion_actual,
											  idnivel_organizacional_actual,
											  nro_orden,
											  fecha_orden,
											  idtipo_movimiento,
											  idorganizacion_destino,
											  idnivel_organizacional_destino,
											  fecha_movimiento,
											  fecha_regreso,
											  retorno_automatico,
											  justificacion_movimiento,
											  status,
											  usuario,
											  fechayhora,
											  idbien,
											  tipo_bien,
											  estado)VALUES('".$tipo."',
											  					'".$codigo_bien."',
																'".$idcatalogo_bienes."',
																'".$especificaciones."',
																'".$idorganizacion_actual."',
																'".$idnivel_organizacional_actual."',
																'".$nro_orden."',
																'".$fecha_orden."',
																'".$idtipo_movimiento."',
																'".$idorganizacion_destino."',
																'".$idnivel_organizacional_destino."',
																'".$fecha_movimiento."',
																'".$fecha_regreso."',
																'".$retorno_automatico."',
																'".$justificacion_movimiento."',
																'a',
																'".$login."',
																'".$fh."',
																'".$idbien."',
																'".$tipo_bien."',
																'procesado')")or die(mysql_error());
	echo mysql_insert_id();
	if($tipo == "mueble"){
		$sql_actualizar = mysql_query("update muebles set idorganizacion = '".$idorganizacion_destino."',
															idnivel_organizacion = '".$idnivel_organizacional_destino."'
															where idmuebles = '".$idbien."'")or die(mysql_error());
	}else{
		$sql_actualizar = mysql_query("update ".$tipo_bien."s set organizacion = '".$idorganizacion_destino."'
															where id".$tipo_bien."s = '".$idbien."'")or die(mysql_error());
	
	}
}



if($ejecutar == "modificarMovimiento"){
		$sql_actualizar = mysql_query("update movimientos_bienes_individuales set tipo = '".$tipo."',
											  codigo_bien = '".$codigo_bien."',
											  idcatalogo_bienes = '".$idcatalogo_bienes."',
											  especificaciones = '".$especificaciones."',
											  idorganizacion_actual = '".$idorganizacion_actual."',
											  idnivel_organizacional_actual = '".$idnivel_organizacional_actual."',
											  nro_orden = '".$nro_orden."',
											  fecha_orden = '".$fecha_orden."',
											  idtipo_movimiento = '".$idtipo_movimiento."',
											  idorganizacion_destino = '".$idorganizacion_destino."',
											  idnivel_organizacional_destino = '".$idnivel_organizacional_destino."',
											  fecha_movimiento = '".$fecha_movimiento."',
											  fecha_regreso = '".$fecha_regreso."',
											  retorno_automatico = '".$retorno_automatico."',
											  justificacion_movimiento = '".$justificacion_movimiento."',
											  idbien = '".$idbien."',
											  tipo_bien = '".$tipo_bien."'
											  	where
											  idmovimientos_bienes_individuales = '".$idmovimiento."'")or die(mysql_error());
	if($tipo == "mueble"){
		$sql_actualizar = mysql_query("update muebles set idorganizacion = '".$idorganizacion_destino."',
															idnivel_organizacion = '".$idnivel_organizacional_destino."'
															where idmuebles = '".$idbien."'");
	}else{
		$sql_actualizar = mysql_query("update ".$tipo_bien."s set organizacion = '".$idorganizacion_destino."'
															where ".$tipo_bien."s = '".$idbien."'");
	
	}
	
}





if($ejecutar == "consultarMovimiento"){
	// consulta de movimientos
	$sql_consultar = mysql_query("select * from movimientos_bienes_individuales where idmovimientos_bienes_individuales = '".$idmovimiento."'");
	$bus_consultar = mysql_fetch_array($sql_consultar);
	// consulta del catalogo de bienes
	$sql_catalogo = mysql_query("select * from detalle_catalogo_bienes where iddetalle_catalogo_bienes = '".$bus_consultar["idcatalogo_bienes"]."'");
	$bus_catalogo = mysql_fetch_array($sql_catalogo);

	
	echo $bus_consultar["tipo"]."|.|".
	$bus_consultar["codigo_bien"]."|.|".
	$bus_catalogo["iddetalle_catalogo_bienes"]."|.|".
	"(".$bus_catalogo["codigo"].")".$bus_catalogo["denominacion"]."|.|".
	$bus_consultar["especificaciones"]."|.|".
	$bus_consultar["idorganizacion_actual"]."|.|".
	$bus_consultar["idnivel_organizacional_actual"]."|.|".
	$bus_consultar["nro_orden"]."|.|".
	$bus_consultar["fecha_orden"]."|.|".
	$bus_consultar["idtipo_movimiento"]."|.|".
	$bus_consultar["idorganizacion_destino"]."|.|".
	$bus_consultar["idnivel_organizacional_destino"]."|.|".
	$bus_consultar["fecha_movimiento"]."|.|".
	$bus_consultar["fecha_regreso"]."|.|".
	$bus_consultar["retorno_automatico"]."|.|".
	$bus_consultar["justificacion_movimiento"]."|.|".
	$bus_consultar["idbien"]."|.|".
	$bus_consultar["tipo_bien"]."|.|".
	$bus_consultar["estado"];
}




if($ejecutar == "eliminarMovimiento"){
	$sql_anular = mysql_query("update movimientos_bienes_individuales set estado = 'anulado' where idmovimientos_bines_individuales = '".$idmovimiento."'");
	$sql_consultar = mysql_query("select * from movimientos_bienes_individuales where idmovimientos_bienes_individuales = '".$idmovimiento."'");
	$bus_consultar = mysql_fetch_array($sql_consultar);
	
	if($bus_consultar["tipo"] == "mueble"){
		$sql_actualizar = mysql_query("update muebles set idorganizacion = '".$bus_consultar["idorganizacion_actual"]."',
															idnivel_organizacion = '".$bus_consultar["idnivel_organizacional_actual"]."'
															where idmuebles = '".$bus_consultar["idbien"]."'");
	}else{
		$sql_actualizar = mysql_query("update ".$bus_consultar["tipo_bien"]."s set organizacion = '".$bus_consultar["idorganizacion_actual"]."'
															where id".$bus_consultar["tipo_bien"]."s = '".$bus_consultar["idbien"]."'");
	
	}
}

?>