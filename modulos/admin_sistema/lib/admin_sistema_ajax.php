<?
session_start();
set_time_limit(-1);
include("../../../conf/conex.php");
Conectarse();
extract($_POST);




if($ejecutar == "guardarDatosRespaldo"){
	$sql_actualizar = mysql_query("update configuracion set usuario_bd = '".$usuario_bd."',
								  							clave_bd = '".$clave_bd."',
															bd_conexion = '".$bd_conexion."'");	
	
	if($sql_actualizar){
		echo "exito";	
	}else{
		echo "No se pudieron actualizar los datos";
	}
}


if($ejecutar == "limpiarDatosDuplicados"){
	mysql_connect("localhost", "root", "1234");
	
	$sql_consultar_existe_bd = mysql_query("SHOW DATABASES LIKE '".$bd."'");
	$num_consultar_existe_bd = mysql_num_rows($sql_consultar_existe_bd);
	
	if($num_consultar_existe_bd > 0){
	mysql_select_db($bd);
		$sql_consultar_existe_tabla = mysql_query("SHOW TABLE STATUS LIKE '".$tabla_actual."'");
		$num_consultar_existe_tabla = mysql_num_rows($sql_consultar_existe_tabla);
		if($num_consultar_existe_tabla > 0){
			$sql_consultar_existe_campo = mysql_query("SELECT column_name FROM information_schema.COLUMNS WHERE table_name = '".$tabla_actual."' AND column_name='".$campo_agrupar."'");
			$num_consultar_existe_campo = mysql_num_rows($sql_consultar_existe_campo);
			if($num_consultar_existe_campo > 0){
				$sql_cambiar_nombre = mysql_query("ALTER TABLE ".$tabla_actual." RENAME ".$tabla_actual."_prueba");
				$sql_crear_clone_table = mysql_query("CREATE TABLE ".$tabla_actual." LIKE ".$tabla_actual."_prueba");
				$sql_insertar_datos = mysql_query("INSERT INTO ".$tabla_actual." SELECT * FROM ".$tabla_actual."_prueba GROUP BY ".$campo_agrupar."");
				$sql_eliminar_clone = mysql_query("drop table ".$tabla_actual."_prueba");
				
				if($sql_cambiar_nombre){
					if($sql_crear_clone_table){
						if($sql_insertar_datos){
							if($sql_eliminar_clone){
								echo "exito";
							}else{
								echo "Fallo Eliminando la Nueva Tabla";
							}
						}else{
							echo "Fallo Insertando los Datos";
						}
					}else{
						echo "Fallo Creando la Nueva Tabla";
					}
				}else{
					echo "Fallo Cambiando el Nombre de la Tabla";
				}
			}else{
				echo "Disculpe el Campo por donde desea agrupar no existe";
			}
		}else{
			echo "Disculpe la Tabla que Indico No Existe";
		}
	}else{
		echo "Disculpe la Base de Datos que Selecciono no Existe";
	}
}


/*
if($ejecutar == "procesarSql"){
	if($nombreBD == ""){
		$query = mysql_query($sql)or die(mysql_error());
		if($query){
			echo "exito";
		}
		
	}else{
		//$query = "USE ".$nombreBD.";\n";
		mysql_select_db($nombreBD);
		//$patron = "^(\r\n)+|^(\n)+|^(\r)+|^(\n\r)+";
		//$query = eregi_replace($patron, '', $query);
		//$query = utf8_decode($query);
		//$query = "insert into tabla (nombre)values(1);";
		echo "hola".$sql;
		$result = mysql_query(stripslashes($sql))or die(mysql_error());
		if($result){
			echo "exito";
		}
		
	}
mysql_close($conex);
}
*/



if($ejecutar == "crearNuevasAcciones"){
	$sql_ingresar_accion = mysql_query("insert into accion(nombre_accion,
															id_modulo,
															url,
															mostrar,
															accion_padre,
															posicion)VALUES(
																'".$nombre_accion."',
																'".$id_modulo."',
																'".$url."',
																'".$mostrar."',
																'".$accion_padre."',
																'".$posicion."')");
	if($sql_ingresar_accion){
		echo "exito|.|".mysql_insert_id();
	}else{
		echo "fallo|.|".mysql_error();
	}																			
}



if($ejecutar == "cambiar_ruta_reportes"){
	$sql_update = mysql_query("update configuracion set ruta_reportes = '".$ruta."'")or die(mysql_error());
	if($sql_update){
		echo "exito";
	}else{
		echo "fallo";
	}
}



if($ejecutar == "ingresar_modulos"){
	$sql_insertar = mysql_query("insert into modulo(nombre_modulo)values('".$nuevo_modulo."')");
	if($sql_insertar){
		echo "exito";
	}
}





if($ejecutar == "procesarConfiguracion"){
	$sql_configuracion_logo = mysql_query("update configuracion_logos set logo = '".$logo."',
																			segundo_logo = '".$segundo_logo."',
																			alto_primero = '".$alto_primero."',
																			ancho_primero = '".$ancho_primero."',
																			alto_segundo = '".$alto_segundo."',
																			ancho_segundo = '".$ancho_segundo."'");
}




if($ejecutar == "actualizar_configuracion"){
	$sql_actualizar = mysql_query("update configuracion set version = '".$tipo_sistema."'");
	if($sql_actualizar){
		echo "exito";	
	}
}




if($ejecutar == "mostrar_modulo"){
	//echo $id_modulo;
	$sql_consultar = mysql_query("select * from modulo where id_modulo = '".$id_modulo."'");
	$bus_consultar = mysql_fetch_array($sql_consultar);
	if($bus_consultar["mostrar"] == "si"){
		$mostrar = "no";	
	}else{
		$mostrar = "si";	
	}
	
	$sql_actualizar = mysql_query("update modulo set mostrar = '".$mostrar."' where id_modulo = '".$id_modulo."'");
	echo $mostrar;
}








if($ejecutar == "agrupar_modulos"){
	$sql_consulta = mysql_query("select * from agrupacion_modulos where modulo = '".$modulo_asociacion."'")or die(mysql_error());
	$num_consulta = mysql_num_rows($sql_consulta);
	
	if($num_consulta == 0){
	
		$sql_ingresar = mysql_query("insert into agrupacion_modulos (grupo,
																 modulo)VALUES('".$grupo."',
																 				'".$modulo_asociacion."')");
		if(!$sql_ingresar){
			echo "Disculpe no se pudo agregar este modulo al grupo seleccionado";
		}else{
			echo "exito";	
		}
	}else{
		echo "Disculpe este modulo ya este incluido en un Grupo";	
	}
}


if($ejecutar == "mostrar_modulos"){
	$sql_consulta = mysql_query("select * from agrupacion_modulos where grupo = '".$grupo."'")or die(mysql_error());
	?>
	
	<table align="center" class="Browse" cellpadding="0" cellspacing="0" width="50%">
        <thead>
            <tr>
                <!--<td class="Browse">&nbsp;</td>-->
                <td align="center" class="Browse">Grupo</td>
                <td align="center" class="Browse">Modulo</td>
                <td align="center" class="Browse">Eliminar.</td>
            </tr>
        </thead>
	<?
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
		<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        
        <td align="center" class='Browse'>Grupo #<?=$bus_consulta["grupo"]?></td>
            <td align="left" class='Browse'>
			<?
				$sql_modulos = mysql_query("select * from dependencias where iddependencia = '".$bus_consulta["modulo"]."'")or die(mysql_error());
				$bus_modulos = mysql_fetch_array($sql_modulos);
				echo $bus_modulos["denominacion"];
			?>
			</td>
            <td align="center" class='Browse'>
            <img src="../../imagenes/delete.png" 
                title="Actualizar Descuento" 
                style="cursor:pointer" 
                onclick="eliminarAgrupacion('<?=$bus_consulta["idagrupacion_modulos"]?>', '<?=$bus_consulta["grupo"]?>')">
            </td>
      </tr>
		
		<?	
	}
	?>
	</table>
	<?
	
}
	
	
	if($ejecutar == "eliminarAgrupacion"){
		
		$sql_eliminar = mysql_query("delete from agrupacion_modulos where idagrupacion_modulos = '".$idagrupacion."'");	
		if($sql_eliminar){
			echo "exito";	
		}else{
			echo mysql_error();	
		}
	}
	
	
	
	
	if($ejecutar == "mostrarConfiguracion"){
		$sql_configuracion = mysql_query("select * from configuracion_cheques where idbanco = '".$idbanco."'");
		$bus_configuracion = mysql_fetch_array($sql_configuracion);
		
		echo $bus_configuracion["alto_monto_numeros"]."|.|".
			$bus_configuracion["izquierda_monto_numeros"]."|.|".
			$bus_configuracion["alto_beneficiario"]."|.|".
			$bus_configuracion["izquierda_beneficiario"]."|.|".
			$bus_configuracion["alto_monto_letras"]."|.|".
			$bus_configuracion["izquierda_monto_letras"]."|.|".
			$bus_configuracion["alto_fecha"]."|.|".
			$bus_configuracion["izquierda_fecha"]."|.|".
			$bus_configuracion["alto_ano"]."|.|".
			$bus_configuracion["izquierda_ano"];
		
	}
	
	
	if($ejecutar == "actualizarConfiguracion"){
		$sql_consulta = mysql_query("select * from configuracion_cheques where idbanco = '".$idbanco."'");
		$num_consulta = mysql_num_rows($sql_consulta);
		if($num_consulta > 0){
			$sql_actualizar = mysql_query("update configuracion_cheques 
													set alto_monto_numeros = '".$alto_monto_numeros."',
													izquierda_monto_numeros = '".$izquierda_monto_numeros."',
													alto_beneficiario = '".$alto_beneficiario."',
													izquierda_beneficiario = '".$izquierda_beneficiario."',
													alto_monto_letras = '".$alto_monto_letras."',
													izquierda_monto_letras = '".$izquierda_monto_letras."',
													alto_fecha = '".$alto_fecha."',
													izquierda_fecha = '".$izquierda_fecha."',
													alto_ano ='".$alto_ano."',
													izquierda_ano = '".$izquierda_ano."'
													where idbanco = '".$idbanco."'")or die(mysql_error());
			if($sql_actualizar){
				echo "exito";
			}
		}else{
			$sql_ingresar = mysql_query("insert into configuracion_cheques 
													(idbanco,
													alto_monto_numeros,
													izquierda_monto_numeros,
													alto_beneficiario,
													izquierda_beneficiario,
													alto_monto_letras,
													izquierda_monto_letras,
													alto_fecha, 
													izquierda_fecha,
													alto_ano,
													izquierda_ano)VALUES('".$idbanco."',
																								'".$alto_monto_numeros."',
																								'".$izquierda_monto_numeros."',
																								'".$alto_beneficiario."',
																								'".$izquierda_beneficiario."',
																								'".$alto_monto_letras."',
																								'".$izquierda_monto_letras."',
																								'".$alto_fecha."',
																								'".$izquierda_fecha."',
																								'".$alto_ano."',
																								'".$izquierda_ano."')")or die(mysql_error());
			if($sql_ingresar){
				echo "exito";
			}
		}
	}

?>
