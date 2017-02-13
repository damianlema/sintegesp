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
		<option onclick="buscarMuebles('<?=$datos["idniveles_organizacionales"]?>')" value="<?=$datos['idniveles_organizacionales']?>">
			<?=$titulo ." - ". $datos['denominacion']?>
		</option>
		<?
		}
	}
}


function listar_foros2($padre, $titulo) {
	global $foros;
	foreach($foros[$padre] as $foro => $datos) {			
		if(isset($foros[$foro])) {
			$nuevo_titulo = ($titulo == '') ? $datos['denominacion'] : "$titulo - {$datos['denominacion']} -";
			listar_foros2($foro, $nuevo_titulo);
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
        <select id="nivel_organizacional_mueble" name="nivel_organizacional_mueble">
        <?
			listar_foros2(0, '');
		?>
        </select>
		<?
		return;
}








if($ejecutar == "mostrarInmueble"){
	if($tipo == "inmueble"){
	$sql_terreno = mysql_query("select terrenos.codigo_bien as codigo_bien,
											terrenos.denominacion_inmueble as denominacion_inmueble,
											organizacion.denominacion as organizacion,
											terrenos.idterrenos  as idterrenos,
											detalle_catalogo_bienes.codigo as codigo_catalogo,
											detalle_catalogo_bienes.denominacion as denominacion
											 	from
											terrenos, organizacion, detalle_catalogo_bienes
											 	where 
											terrenos.organizacion = organizacion.idorganizacion
											and terrenos.organizacion = '".$organizacion."'
											and detalle_catalogo_bienes.iddetalle_catalogo_bienes = terrenos.iddetalle_catalogo_bienes
											GROUP BY terrenos.idterrenos
                      						order by organizacion.denominacion")or die(mysql_error());
	$num_terreno = mysql_num_rows($sql_terreno);
	$sql_edificio = mysql_query("select edificios.codigo_bien as codigo_bien,
											edificios.denominacion_inmueble as denominacion_inmueble,
											organizacion.denominacion as organizacion,
											edificios.idedificios as idedificios,
											detalle_catalogo_bienes.codigo as codigo_catalogo,
											detalle_catalogo_bienes.denominacion as denominacion
											 	from 
											edificios, organizacion , detalle_catalogo_bienes
											 	where 
											edificios.organizacion = organizacion.idorganizacion
											and detalle_catalogo_bienes.iddetalle_catalogo_bienes = edificios.iddetalle_catalogo_bienes
											and edificios.organizacion = '".$organizacion."'");
	$num_edificio = mysql_num_rows($sql_edificio);
	if($num_edificio == 0 && $num_terreno == 0){
		echo "<center><strong>Esta Organizacion no posee Inmuebles Asociados</strong></center>";
	}else{
	?>
    <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="900">
  <thead>
        <tr>
        	<td width="6%" align="center" class="Browse">Tipo</td>
          <td width="10%" align="center" class="Browse">Codigo del Bien</td>
          <td width="12%" align="center" class="Browse">Codigo del Catalogo</td>
          <td width="24%" align="center" class="Browse">Denominacion del Catalogo</td>
          <td width="21%" align="center" class="Browse">Denominacion</td>
          <td width="19%" align="center" class="Browse">Organizacion</td>
        <?
              if($estado == 'elaboracion'){
			  ?>
            <td width="8%" align="center" class="Browse">Seleccionar</td>
            <?
            }
			?>
      </tr>
    </thead>
        <?
		
		while($bus_terreno = mysql_fetch_array($sql_terreno)){
			

			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td class='Browse' align='left'>Terreno</td>
				<td class='Browse' align='left'>&nbsp;<?=$bus_terreno["codigo_bien"]?></td>
                <td class='Browse' align='left'>&nbsp;<?=$bus_terreno["codigo_catalogo"]?></td>
                <td class='Browse' align='left'>&nbsp;<?=$bus_terreno["denominacion"]?></td>
			  <td class='Browse' align='left'>&nbsp;<?=$bus_terreno["denominacion_inmueble"]?></td>
			  <td class='Browse' align='left'>&nbsp;<?=$bus_terreno["organizacion"]?></td>
			  <?
              if($estado == 'elaboracion'){
			  ?>
              <td class='Browse' align='center'><img src="imagenes/validar.png" style="cursor:pointer" onclick="seleccionarBien('<?=$bus_terreno["idterrenos"]?>', '<?=$bus_terreno["codigo_bien"]?>', 'terreno', '<?=$idmovimiento?>')"></td>
              <?
              }
			  ?>
			</tr>
			<?
			}
		while($bus_edificio = mysql_fetch_array($sql_edificio)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td class='Browse' align='left'>Edificio</td>
				<td class='Browse' align='left'>&nbsp;<?=$bus_edificio["codigo_bien"]?></td>
                <td class='Browse' align='left'>&nbsp;<?=$bus_edificio["codigo_catalogo"]?></td>
                <td class='Browse' align='left'>&nbsp;<?=$bus_edificio["denominacion"]?></td>
			  <td class='Browse' align='left'>&nbsp;<?=$bus_edificio["denominacion_inmueble"]?></td>
			  <td class='Browse' align='left'>&nbsp;<?=$bus_edificio["organizacion"]?></td>
			  <?
              if($estado == 'elaboracion'){
			  ?>
              <td class='Browse' align='center'><img src="imagenes/validar.png" style="cursor:pointer" onclick="seleccionarBien('<?=$bus_edificio["idedificios"]?>', '<?=$bus_edificio["codigo_bien"]?>', 'edificio', '<?=$idmovimiento?>')"></td>
              <?
              }
			  ?>
			</tr>
			<?
        }
		?>
    </table>
<?
		}
	}else{
	        $sql_mueble = mysql_query("select muebles.codigo_bien as codigo_bien,
										  muebles.especificaciones as especificaciones,
										  organizacion.denominacion as organizacion,
										  niveles_organizacionales.denominacion as nivel_organizacional,
										  muebles.idmuebles as idmuebles,
										  detalle_catalogo_bienes.codigo as codigo_catalogo,
										  detalle_catalogo_bienes.denominacion as denominacion
										  	from 
										  muebles, organizacion, niveles_organizacionales, detalle_catalogo_bienes
										  	where
										  organizacion.idorganizacion = muebles.idorganizacion
										  and detalle_catalogo_bienes.iddetalle_catalogo_bienes = muebles.idcatalogo_bienes
										  and niveles_organizacionales.idniveles_organizacionales = muebles.idnivel_organizacion
										  and muebles.idnivel_organizacion = '".$idnivel_organizacional."'")or die("XXX".mysql_error());
		$num_mueble = mysql_num_rows($sql_mueble);
		if($num_mueble == 0){
			echo "<center><strong>Este Nivel Organizacional no posee Muebles Asociados</strong></center>";
		}else{
	?>
	<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="900">
  <thead>
        <tr>
            <td width="6%" align="center" class="Browse">Codigo del Bien</td>
            <td width="11%" align="center" class="Browse">Codigo del Catalogo</td>
          <td width="22%" align="center" class="Browse">Denominacion Catalogo</td>
          <td width="19%" align="center" class="Browse">Especificaciones</td>
          <td width="18%" align="center" class="Browse">Organizacion</td>
          <td width="16%" align="center" class="Browse">Nivel Organizacional</td>
      <?
              if($estado == 'elaboracion'){
			  ?>
            <td width="8%" align="center" class="Browse">Seleccionar</td>
            <?
            }
			?>
      </tr>
    </thead>
        <?
		while($bus_mueble = mysql_fetch_array($sql_mueble)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td class='Browse' align='left'>&nbsp;<?=$bus_mueble["codigo_bien"]?></td>
                <td class='Browse' align='left'>&nbsp;<?=$bus_mueble["codigo_catalogo"]?></td>
                <td class='Browse' align='left'>&nbsp;<?=$bus_mueble["denominacion"]?></td>
			  <td class='Browse' align='left'>&nbsp;<?=$bus_mueble["especificaciones"]?></td>
				<td class='Browse' align='left'>&nbsp;<?=$bus_mueble["organizacion"]?></td>
				<td class='Browse' align='left'>&nbsp;<?=$bus_mueble["nivel_organizacional"]?></td>
				<?
              if($estado == 'elaboracion'){
			  ?>
                <td class='Browse' align='center'><img src="imagenes/validar.png" style="cursor:pointer" onclick="seleccionarBien('<?=$bus_mueble["idmuebles"]?>', '<?=$bus_mueble["codigo_bien"]?>', 'mueble', '<?=$idmovimiento?>')" ></td>
                <?
                }
				?>
		  </tr>
			<?
        }
		?>
    </table>
<?
		}
	}
}





if($ejecutar == "guardarDatosBasicos"){
//echo "prueba";
	$sql_ingresar = mysql_query("insert into movimientos_lotes (tipo,
																	nro_orden,
																	fecha_orden,
																	tipo_movimiento,
																	fecha_movimiento,
																	justificacion_movimiento,
																	status,
																	usuario,
																	fechayhora
																		)VALUES(
																	'".$tipo."',
																	'".$nro_orden."',
																	'".$fecha_orden."',
																	'".$tipo_movimiento."',
																	'".$fecha_movimiento."',
																	'".$justificacion_movimiento."',
																	'a',
																	'".$login."',
																	'".$fh."')")or die(mysql_error());
echo mysql_insert_id();
}




if($ejecutar == "seleccionarBien"){
	$sql_ingresar = mysql_query("insert into bienes_seleccionados_lotes(idbien,
																		codigo_bien,
																		tipo_bien,
																		idmovimiento,
																		idorganizacion_actual,
																		idnivel_organizacional_actual,
																		idorganizacion_destino,
																		idnivel_organizacional_destino)VALUES(
																						'".$idbien."',
																						'".$codigo_bien."',
																						'".$tipo_bien."',
																						'".$idmovimiento."',
																						'".$idorganizacion_actual."',
																						'".$idnivel_organizacional_actual."',
																						'".$idorganizacion_destino."',
																						'".$idnivel_organizacional_destino."')")
																						or die(mysql_error());
}



if($ejecutar == "listarBienesSeleccionados"){
	$sql_seleccionados = mysql_query("select * from bienes_seleccionados_lotes where idmovimiento = '".$idmovimiento."'");
	?>
	<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="900">
    <thead>
        <tr>
            <td width="6%" align="center" class="Browse">Tipo</td>
          <td width="6%" align="center" class="Browse">Cod. Bien</td>
          <td width="6%" align="center" class="Browse">Codigo Cat.</td>
          <td width="10%" align="center" class="Browse">Denominacion Cat.</td>
          <td width="12%" align="center" class="Browse">Especificaciones</td>
            <td width="13%" align="center" class="Browse">Organizacion Ori.</td>
            <?
            if($tipo == "mueble"){
			?>
            <td width="15%" align="center" class="Browse">Nivel O. Origen</td>
            <?
            }
			?>
            <td width="14%" align="center" class="Browse">Organizacion Des.</td>
            <?
            if($tipo == "mueble"){
			?>
            <td width="13%" align="center" class="Browse">Nivel O. Destino</td>
			<?
            }
              if($estado == 'elaboracion'){
			  ?>
            <td width="5%" align="center" class="Browse">Regresar</td>
          <?
            }
			?>
        </tr>
    </thead>
        <?
		while($bus_seleccionados = mysql_fetch_array($sql_seleccionados)){
		if($bus_seleccionados["tipo_bien"] == 'terreno'){
			$tipo_bien = "Terreno";
			$sql_datos = mysql_query("select terrenos.codigo_bien as codigo_bien,
											terrenos.denominacion_inmueble as especificaciones,
											detalle_catalogo_bienes.codigo as codigo_catalogo,
										 	detalle_catalogo_bienes.denominacion as denominacion
												from 
											terrenos, detalle_catalogo_bienes
												where
											terrenos.idterrenos = '".$bus_seleccionados["idbien"]."'
											and detalle_catalogo_bienes.iddetalle_catalogo_bienes = terrenos.iddetalle_catalogo_bienes");
			
		}else if($bus_seleccionados["tipo_bien"] == "edificio"){
			$tipo_bien = "Edificio";
			$sql_datos = mysql_query("select edificios.codigo_bien as codigo_bien,
											edificios.denominacion_inmueble as especificaciones,
											detalle_catalogo_bienes.codigo as codigo_catalogo,
											detalle_catalogo_bienes.denominacion as denominacion
												from 
											edificios, detalle_catalogo_bienes
												where
											edificios.idedificios = '".$bus_seleccionados["idbien"]."'
											and detalle_catalogo_bienes.iddetalle_catalogo_bienes = edificios.iddetalle_catalogo_bienes");
		}else if($bus_seleccionados["tipo_bien"] == "mueble"){
			$tipo_bien = "Mueble";
			$sql_datos = mysql_query("select muebles.codigo_bien as codigo_bien,
											muebles.especificaciones as especificaciones,
											detalle_catalogo_bienes.codigo as codigo_catalogo,
											detalle_catalogo_bienes.denominacion as denominacion
												from 
											muebles, detalle_catalogo_bienes
												where
											muebles.idmuebles = '".$bus_seleccionados["idbien"]."'
											and detalle_catalogo_bienes.iddetalle_catalogo_bienes = muebles.idcatalogo_bienes")or die(mysql_error());
		}
		$bus_datos = mysql_fetch_array($sql_datos);
		
		$sql_organizacion_actual = mysql_query("select * from organizacion where idorganizacion = '".$bus_seleccionados["idorganizacion_actual"]."'");
		$bus_organizacion_actual = mysql_fetch_array($sql_organizacion_actual);
		
		$sql_nivel_organizacional_actual = mysql_query("select * from niveles_organizacionales where idniveles_organizacionales = '".$bus_seleccionados["idnivel_organizacional_actual"]."'");
		$bus_nivel_organizacional_actual = mysql_fetch_array($sql_nivel_organizacional_actual);
		
		
		
		
		$sql_organizacion_destino = mysql_query("select * from organizacion where idorganizacion = '".$bus_seleccionados["idorganizacion_destino"]."'");
		$bus_organizacion_destino = mysql_fetch_array($sql_organizacion_destino);
		
		$sql_nivel_organizacional_destino = mysql_query("select * from niveles_organizacionales where idniveles_organizacionales = '".$bus_seleccionados["idnivel_organizacional_destino"]."'");
		$bus_nivel_organizacional_destino = mysql_fetch_array($sql_nivel_organizacional_destino);
		
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class='Browse' align='left'>&nbsp;<?=$tipo_bien?></td>
            <td class='Browse' align='left'>&nbsp;<?=$bus_datos["codigo_bien"]?></td>
            <td class='Browse' align='left'>&nbsp;<?=$bus_datos["codigo_catalogo"]?></td>
            <td class='Browse' align='left'>&nbsp;<?=$bus_datos["denominacion"]?></td>
          <td class='Browse' align='left'>&nbsp;<?=$bus_datos["especificaciones"]?></td>
            <td class='Browse' align='left'>&nbsp;<?=$bus_organizacion_actual["denominacion"]?></td>
            <?
            if($tipo == "mueble"){
			?>
            <td class='Browse' align='left'>&nbsp;<?=$bus_nivel_organizacional_actual["denominacion"]?></td>
            <?
            }
			?>
            <td class='Browse' align='left'>&nbsp;<?=$bus_organizacion_destino["denominacion"]?></td>
            <?
            if($tipo == "mueble"){
			?>
            <td class='Browse' align='left'>&nbsp;<?=$bus_nivel_organizacional_destino["denominacion"]?></td>
            <?
			}
              if($estado == 'elaboracion'){
			  ?>
            <td class='Browse' align='center'><img src="imagenes/delete.png" style="cursor:pointer" onclick="eliminarSeleccionado('<?=$bus_seleccionados["idbienes_seleccionados_lotes"]?>')"></td>
            <?
            }
			?>
      </tr>
        <?
        }
		?>
    </table>
	<?
}





if($ejecutar == "eliminarSeleccionado"){
	$sql_eliminar = mysql_query("delete from bienes_seleccionados_lotes where idbienes_seleccionados_lotes = '".$idbienes_seleccionados."'");
}





if($ejecutar == "procesarMovmientos"){
	$sql_consulta = mysql_query("select * from bienes_seleccionados_lotes where idmovimiento = '".$idmovimiento."'");
	$num_consulta = mysql_num_rows($sql_consulta);
	if($num_consulta > 0){
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			if($bus_consulta["tipo_bien"] == "mueble"){
				$sql_actualizar = mysql_query("update muebles set idorganizacion = '".$bus_consulta["idorganizacion_destino"]."',
																idnivel_organizacion = '".$bus_consulta["idnivel_organizacional_destino"]."'
																where idmuebles = '".$bus_consulta["idbien"]."'");
			}else{
				$sql_actualizar = mysql_query("update ".$bus_consulta["tipo_bien"]."s set 
																	organizacion = '".$bus_consulta["idorganizacion_destino"]."' 
																	where id".$bus_consulta["tipo_bien"]."s = '".$bus_consulta["idbien"]."'");
			}
		}
	
		$num_movimiento_lote = 1;
			while($num_movimiento_lote > 0){
				$sql_actualizar = mysql_query("update configuracion_bienes set nro_movimiento_lote = nro_movimiento_lote + 1");
				$sql_consulta = mysql_query("select * from configuracion_bienes");
				$bus_consulta = mysql_fetch_array($sql_consulta);			
				$nro_movimiento = "MOV-".date("Y")."-".$bus_consulta["nro_movimiento_lote"];
				$sql_movimientos_lotes = mysql_query("select * from movimientos_lotes where nro_movimiento = '".$nro_movimiento."'");
				$num_movimiento_lote = mysql_num_rows($sql_movimientos_lotes);
			}
		
		
		$sql_procesar = mysql_query("update movimientos_lotes 
															set estado = 'procesado',
															nro_movimiento = '".$nro_movimiento."' 
															where 
															idmovimientos_lotes = '".$idmovimiento."'");
															
		echo $nro_movimiento;
	}else{
		echo "noTieneBienes";
	}
}




if($ejecutar == "consultarMovimiento"){
	$sql_consultar = mysql_query("select * from movimientos_lotes where idmovimientos_lotes = '".$idmovimiento."'");
	$bus_consultar = mysql_fetch_array($sql_consultar);
		
	echo $bus_consultar["nro_movimiento"]."|.|".
		$bus_consultar["tipo"]."|.|".
		$bus_consultar["nro_orden"]."|.|".
		$bus_consultar["fecha_orden"]."|.|".
		$bus_consultar["tipo_movimiento"]."|.|".
		$bus_consultar["fecha_movimiento"]."|.|".
		$bus_consultar["fecha_regreso"]."|.|".
		$bus_consultar["regreso_automatico"]."|.|".
		$bus_consultar["justificacion_movimiento"]."|.|".
		$bus_consultar["estado"];
}





if($ejecutar == "modificarMovimiento"){
	$sql_actualizar = mysql_query("update movimientos_lotes set tipo = '".$tipo."',
																	nro_orden = '".$nro_orden."',
																	fecha_orden = '".$fecha_orden."',
																	tipo_movimiento = '".$tipo_movimiento."',
																	fecha_movimiento = '".$fecha_movimiento."',
																	justificacion_movimiento = '".$justificacion_movimiento."'
																	where idmovimientos_lotes = '".$idmovimiento."'")or die(mysql_error());
}




if($ejecutar == "anularMovimiento"){
	$sql_anular = mysql_query("update movimientos_lotes set estado = 'anulado' where idmovimientos_lotes = '".$idmovimiento."'");
	$sql_anular_bienes = mysql_query("update bienes_seleccionados_lotes set estado = 'anulado' where idmovimiento = '".$idmovimiento."'");
	
	$sql_consulta = mysql_query("select * from bienes_seleccionados_lotes where idmovimiento = '".$idmovimiento."'");
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
			if($bus_consulta["tipo_bien"] == "mueble"){
				$sql_actualizar = mysql_query("update muebles set idorganizacion = '".$bus_consulta["idorganizacion_actual"]."',
																idnivel_organizacion = '".$bus_consulta["idnivel_organizacional_actual"]."'
																where idmuebles = '".$bus_consulta["idbien"]."'");
			}else{
				$sql_actualizar = mysql_query("update ".$bus_consulta["tipo_bien"]."s set 
																	organizacion = '".$bus_consulta["idorganizacion_actual"]."' 
																	where id".$bus_consulta["tipo_bien"]."s = '".$bus_consulta["idbien"]."'");
			}
		}
}
?>