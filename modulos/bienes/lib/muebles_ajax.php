<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);
/*
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
*/
function listar_foros($padre, $titulo, $tab) {
	global $foros;
	
	foreach($foros[$padre] as $foro => $datos) {			
		if(isset($foros[$foro])) {
			//$nuevo_titulo = ($titulo == '') ? $datos['denominacion'] : "$titulo - {$datos['denominacion']} -";
			?>
			<option value="<?=$datos['idniveles_organizacionales']?>">
				<?=$x.' .- '.$datos['denominacion']?>
			</option>
			<?
			listar_foros($foro, $nuevo_titulo, $x);
		}else{
			$x = $tab."&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";
			?>
			<option value="<?=$datos['idniveles_organizacionales']?>">
				<?=$x.' .- '.$datos['denominacion']?>
			</option>
			<?
		}
	}
}

function calcularAnio($fechaInicio){ 
	$fechaActual = date("d/m/Y");  
	$diaActual = substr($fechaActual, 0, 2);  
	$mesActual = substr($fechaActual, 3, 5);  
	$anioActual = substr($fechaActual, 6, 10);  
	$diaInicio = substr($fechaInicio, 0, 2);  
	$mesInicio = substr($fechaInicio, 3, 5);  
	$anioInicio = substr($fechaInicio, 6, 10);  
	$b = 0;  
$mes = $mesInicio-1;  
	if($mes==2){  
		if(($anioActual%4==0 && $anioActual%100!=0) || $anioActual%400==0){  
			$b = 29;  
		}else{  
			$b = 28;  
		}  
	}else if($mes<=7){  
		if($mes==0){  
			$b = 31;  
		}else if($mes%2==0){  
			$b = 30;  
		}else{  
			$b = 31;  
		}  
	}else if($mes>7){  
		if($mes%2==0){  
			$b = 31;  
		}else{  
			$b = 30;  
		}  
	}  
	if(($anioInicio>$anioActual) || ($anioInicio==$anioActual && $mesInicio>$mesActual) ||   
		($anioInicio==$anioActual && $mesInicio == $mesActual && $diaInicio>$diaActual)){  
			return "fecha_mala";  
	}else{  
		if($mesInicio <= $mesActual){  
			$anios = $anioActual - $anioInicio;  
			if($diaInicio <= $diaActual){  
				$meses = $mesActual - $mesInicio;  
				$dies = $diaActual - $diaInicio;  
			}else{  
				if($mesActual == $mesInicio){  
					$anios = $anios - 1;  
				}  
				$meses = ($mesActual - $mesInicio - 1 + 12) % 12;  
				$dies = $b-($diaInicio-$diaActual);  
			}  
		}else{  
			$anios = $anioActual - $anioInicio - 1;  
			if($diaInicio > $diaActual){  
				$meses = $mesActual - $mesInicio -1 +12;  
				$dies = $b - ($diaInicio-$diaActual);  
			}else{  
				$meses = $mesActual - $mesInicio + 12;  
				$dies = $diaActual - $diaInicio;  
			}  
		}  

	return $anios;
	}
}




if($ejecutar == "calcularDepreciacionAcumulada"){
	$partes = explode("-", $fecha_compra);
	$fecha_compra = $partes[2]."/".$partes[1]."/".$partes[0];
	$anios = calcularAnio($fecha_compra);
	$depreciacion_acumulada = $depreciacion_anual * $anios;
	echo $depreciacion_acumulada;
}




if($ejecutar == "seleccionarNivel"){
	
	$foros = array();
		$result = mysql_query("SELECT idniveles_organizacionales, 
										denominacion, 
										sub_nivel,
										codigo 
								FROM 
										niveles_organizacionales 
								WHERE 
										organizacion = '".$idorganizacion."'
										AND modulo = '8'") or die(mysql_error());
		while($row = mysql_fetch_assoc($result)) {
			$foro = $row['idniveles_organizacionales'];
			$padre = $row['sub_nivel'];
			if(!isset($foros[$padre]))
			$foros[$padre] = array();
			$foros[$padre][$foro] = $row;
		}
		
		$tab = "";
		?>
		<select id="<?=$idcampo?>" name="<?=$idcampo?>" style="width:600px;">
        	<option value="0">Elige</option>
			<? if (mysql_num_rows($result) != 0) listar_foros(0, '', $tab); ?>
		</select>
		<?
	
	
		/*
	
	  $foros = array();
      $result = mysql_query("SELECT idniveles_organizacionales, 
	  								denominacion, 
									sub_nivel 
										FROM 
									niveles_organizacionales 
										where 
									organizacion = '".$idorganizacion."'
									and modulo = '".$_SESSION["modulo"]."'
									and inventario_cerrado = ''") or die(mysql_error());
      while($row = mysql_fetch_assoc($result)) {
          $foro = $row['idniveles_organizacionales'];
          $padre = $row['sub_nivel'];
          if(!isset($foros[$padre]))
              $foros[$padre] = array();
          $foros[$padre][$foro] = $row;
      }

		?>
        <select id="idnivel_organizacion" name="idnivel_organizacion">
        <?
			listar_foros(0, '');
		?>
        </select>
		<?
		*/
		return;
}



if($ejecutar == "cargarTipo"){
	$sql_tipo = mysql_query("select * from tipo_detalle where iddetalle = '".$iddetalle."'");
	$num_tipo = mysql_num_rows($sql_tipo);
	?>
	<select name="idtipo" id="idtipo"  style="width:50%" <? if($num_tipo == 0){echo "disabled";}?>>
      	<option value="0">.:: Seleccione ::.</option>
		<?
		while($bus_tipo = mysql_fetch_array($sql_tipo)){
			?>
				<option value="<?=$bus_tipo["idtipo_detalle"]?>"><?=$bus_tipo["tipo"]?></option>
			<?
		}
		?>
      </select> 
	<?
}




//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************* AUTOGENERAR CODIGO ***************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************




if($ejecutar == "generar_codigo"){
	$sql_inventario_materia = mysql_query("select * from relacion_contadores")or die(mysql_error());
	$bus_inventario_materia = mysql_fetch_array($sql_inventario_materia);
	if ($cantidad == 1){
		if ($bus_inventario_materia["contador_bienes_muebles"] > 0){
			$numero = $bus_inventario_materia["contador_bienes_muebles"];
			$codigo_con_ceros = str_pad($bus_inventario_materia["contador_bienes_muebles"], 6, "0", STR_PAD_LEFT);
		}else{
			$numero = 1;
			$codigo_con_ceros = str_pad($numero, 6, "0", STR_PAD_LEFT);
		}
	}else{
		if ($bus_inventario_materia["contador_bienes_muebles"] > 0){
			$numero = $bus_inventario_materia["contador_bienes_muebles"];
			$codigo_con_ceros = "Desde: ".str_pad($bus_inventario_materia["contador_bienes_muebles"], 6, "0", STR_PAD_LEFT)."   Hasta: ".
			str_pad($bus_inventario_materia["contador_bienes_muebles"]+$cantidad-1, 6, "0", STR_PAD_LEFT);
		}else{
			$numero = '1'.'-'.'1'+$cantidad-1;
			$codigo_con_ceros = str_pad($numero, 6, "0", STR_PAD_LEFT);
		}
	}
	echo $numero."|.|".$codigo_con_ceros; 
}


if($ejecutar == "ingresarMueble"){
	for($i=0; $i<=$cantidad-1; $i++){
		$codigo_bien_automatico = $codigo_bien_automatico+$i;
		$codigo_bien = str_pad($codigo_bien_automatico, 6, "0", STR_PAD_LEFT);
		$entro = 0;
		while ($entro == 0){
			$validar_codigo_bien = mysql_query("select * from muebles where codigo_bien = '".$codigo_bien."'");
			if(mysql_num_rows($validar_codigo_bien) > 0){
				$sql_inventario_materia = mysql_query("select * from relacion_contadores")or die(mysql_error());
				$bus_inventario_materia = mysql_fetch_array($sql_inventario_materia);
				$codigo_bien = str_pad($bus_inventario_materia["contador_bienes_muebles"]+1, 6, "0", STR_PAD_LEFT);
				$codigo_bien_automatico = $bus_inventario_materia["contador_bienes_muebles"]+1;
				$actualiza = mysql_query("update relacion_contadores set contador_bienes_muebles = contador_bienes_muebles+1");
			}else{
				$sql_inventario_materia = mysql_query("select * from relacion_contadores")or die(mysql_error());
				$bus_inventario_materia = mysql_fetch_array($sql_inventario_materia);
				if ($bus_inventario_materia["contador_bienes_muebles"] > 0){
					$codigo_bien_automatico = $bus_inventario_materia["contador_bienes_muebles"];
					$codigo_bien = str_pad($bus_inventario_materia["contador_bienes_muebles"], 6, "0", STR_PAD_LEFT);
				}else{
					$codigo_bien_automatico = 1;
					$codigo_bien = str_pad($codigo_bien_automatico, 6, "0", STR_PAD_LEFT);
				}
				
				$entro = 1;
			}
		}
		$sql_ingresar = mysql_query("insert into muebles (idorganizacion,
														idnivel_organizacion,
														idtipo_movimiento,
														idcatalogo_bienes,
														codigo_bien,
														codigo_anterior_bien,
														idubicacion,
														especificaciones,
														marca,
														modelo,
														idtipo,
														serial,
														accesorios,
														numero_documento_compra,
														proveedor,
														fecha_compra,
														nro_factura,
														fecha_factura,
														costo,
														valor_residual,
														vida_util,
														mejoras,
														costo_ajustado,
														depreciacion_anual,
														depreciacion_acumulada,
														asegurado,
														aseguradora,
														nro_poliza,
														fecha_vencimiento,
														monto_poliza,
														monto_asegurado,
														estado,
														status,
														usuario,
														fechayhora)VALUES('".$idorganizacion."',
																			'".$idnivel_organizacion."',
																			'".$idtipo_movimiento."',
																			'".$idcatalogo_bienes."',
																			'".$codigo_bien."',
																			'".$codigo_anterior_bien."',
																			'".$idubicacion."',
																			'".$especificaciones."',
																			'".$marca."',
																			'".$modelo."',
																			'".$idtipo."',
																			'".$serial."',
																			'".$accesorios."',
																			'".$nro_documento_compra."',
																			'".$proveedor."',
																			'".$fecha_compra."',
																			'".$nro_factura."',
																			'".$fecha_factura."',
																			'".$costo."',
																			'".$valor_residual."',
																			'".$vida_util."',
																			'".$mejoras."',
																			'".$costo_ajustado."',
																			'".$depreciacion_anual."',
																			'".$depreciacion_acumulada."',
																			'".$asegurado."',
																			'".$aseguradora."',
																			'".$nro_poliza."',
																			'".$fecha_vencimiento."',
																			'".$monto_poliza."',
																			'".$monto_asegurado."',
																			'activo',
																			'a',
																			'".$login."',
																			'".$fh."')")or die(mysql_error());
		echo mysql_insert_id();
		
		if($sql_ingresar){
			if ($codigo_bien_automatico <> ""){
				
				$contador = $codigo_bien_automatico + 1;
				
				$sql_cambia_contador = mysql_query("update relacion_contadores 
															set contador_bienes_muebles = '".$contador."'")or die(mysql_error());
				
			}	
		}
	}
}





if($ejecutar == "modificarMueble"){
	$sql_modificar = mysql_query("update muebles set idorganizacion = '".$idorganizacion."',
														idnivel_organizacion = '".$idnivel_organizacion."',
														idtipo_movimiento = '".$idtipo_movimiento."',
														idcatalogo_bienes = '".$idcatalogo_bienes."',
														codigo_bien = '".$codigo_bien."',
														codigo_anterior_bien = '".$codigo_anterior_bien."',
														idubicacion = '".$idubicacion."',
														especificaciones = '".$especificaciones."',
														marca = '".$marca."',
														modelo = '".$modelo."',
														idtipo = '".$idtipo."',
														serial = '".$serial."',
														accesorios = '".$accesorios."',
														numero_documento_compra = '".$nro_documento_compra."',
														proveedor = '".$proveedor."',
														fecha_compra = '".$fecha_compra."',
														nro_factura = '".$nro_factura."',
														fecha_factura = '".$fecha_factura."',
														costo = '".$costo."',
														valor_residual = '".$valor_residual."',
														vida_util = '".$vida_util."',
														mejoras = '".$mejoras."',
														costo_ajustado = '".$costo_ajustado."',
														depreciacion_anual = '".$depreciacion_anual."',
														depreciacion_acumulada = '".$depreciacion_acumulada."',
														asegurado = '".$asegurado."',
														aseguradora = '".$aseguradora."',
														nro_poliza = '".$nro_poliza."',
														fecha_vencimiento = '".$fecha_vencimiento."',
														monto_poliza = '".$monto_poliza."',
														monto_asegurado = '".$monto_asegurado."'
														where idmuebles = '".$idmueble."'")or die(mysql_error());
}

if($ejecutar == "eliminarMueble"){
	
	$sql_eliminar = mysql_query("delete from muebles where idmuebles = '".$idmueble."'")or die(mysql_error());
}




if($ejecutar == "consultarMueble"){
	$sql_consulta = mysql_query("select * from muebles where idmuebles = '".$idmueble."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	$sql_catalogo = mysql_query("select * from detalle_catalogo_bienes where iddetalle_catalogo_bienes = '".$bus_consulta["idcatalogo_bienes"]."'")or die(mysql_error());
	$bus_catalogo = mysql_fetch_array($sql_catalogo);
	
	
	echo $bus_consulta["idmuebles"]."|.|".
		$bus_consulta["idorganizacion"]."|.|".
		$bus_consulta["idnivel_organizacion"]."|.|".
		$bus_consulta["idtipo_movimiento"]."|.|".
		$bus_consulta["idcatalogo_bienes"]."|.|".
		$bus_consulta["codigo_bien"]."|.|".
		$bus_consulta["idubicacion"]."|.|".
		$bus_consulta["especificaciones"]."|.|".
		$bus_consulta["marca"]."|.|".
		$bus_consulta["modelo"]."|.|".
		$bus_consulta["idtipo"]."|.|".
		$bus_consulta["serial"]."|.|".
		$bus_consulta["accesorios"]."|.|".
		$bus_consulta["numero_documento_compra"]."|.|".
		$bus_consulta["proveedor"]."|.|".
		$bus_consulta["fecha_compra"]."|.|".
		$bus_consulta["nro_factura"]."|.|".
		$bus_consulta["fecha_factura"]."|.|".
		$bus_consulta["costo"]."|.|".
		$bus_consulta["valor_residual"]."|.|".
		$bus_consulta["vida_util"]."|.|".
		$bus_consulta["depreciacion_anual"]."|.|".
		$bus_consulta["depreciacion_acumulada"]."|.|".
		$bus_consulta["asegurado"]."|.|".
		$bus_consulta["aseguradora"]."|.|".
		$bus_consulta["nro_poliza"]."|.|".
		$bus_consulta["fecha_vencimiento"]."|.|".
		$bus_consulta["monto_poliza"]."|.|".
		$bus_consulta["monto_asegurado"]."|.|".
		"(".$bus_catalogo["codigo"].") ".$bus_catalogo["denominacion"]."|.|".
		$bus_consulta["mejoras"]."|.|".
		$bus_consulta["costo_ajustado"]."|.|".
		$bus_consulta["codigo_anterior_bien"]."|.|".
		$bus_consulta["estado"];
}




if($ejecutar == "validarCodigoBien"){
	$sql_validar = mysql_query("select * from muebles where codigo_bien = '".$valor."'")or die(mysql_error());
	$num_validar = mysql_num_rows($sql_validar);
	if($num_validar > 0){
		
		echo "existe";
	}
}



if($ejecutar == "cargarImagen"){
	$tipo = substr($_FILES['foto_registroFotografico']['type'], 0, 5);
		$dir = '../imagenes/';
		if ($tipo == 'image') {
		$nombre_imagen = $_FILES['foto_registroFotografico']['name'];
			while(file_exists($dir.$nombre_imagen)){
				$partes_img = explode(".",$nombre_imagen);
				$nombre_imagen = $partes_img[0].rand(0,1000000).".".$partes_img[1];
			}
			if (!copy($_FILES['foto_registroFotografico']['tmp_name'], $dir.$nombre_imagen)){
				?>
                <script>
				parent.document.getElementById('mostrarImagen').innerHTML = "<table><tr><td style='color:#990000; font-weight:bold'>* Disculpe la imagen no se pudo ingresar</td></tr></table>";
				</script>
                <?
			}else{
				$ruta = 'modulos/bienes/imagenes/'.$nombre_imagen;
			}
			
				?>
                
			<script>
            parent.document.getElementById('nombre_imagen').value = '<?=$nombre_imagen?>';
            parent.document.getElementById('mostrarImagen').innerHTML = "<img src='modulos/bienes/imagenes/<?=$nombre_imagen?>' width = '100' height='120'>";
            parent.document.getElementById('foto').value = '';
            </script>
            <?
			
		}else{
			?>
			<script>
			parent.document.getElementById('mostrarImagen').innerHTML = "<table><tr><td style='color:#990000; font-weight:bold'>* Disculpe el archivo que intenta subir NO es una Imagen</td></tr></table>";
			</script>
			
			<?
		}
		
}


if($ejecutar == "subirRegistroFotografico"){
	$sql_ingresar = mysql_query("insert into registro_fotografico_bienes_muebles (idmuebles,
																				nombre_imagen,
																				principal,
																				descripcion)VALUES('".$idmueble."',
																								'".$nombre_imagen."',
																								'0',
																								'".$descripcion."')");
}

if($ejecutar == "consultar_Movimientos"){
	
					
			$query = "select * from muebles where idmuebles = '".$idmueble."'";
			
			
			/*
						movimientos_bienes.nro_movimiento,
						movimientos_bienes.fecha_movimiento,
						movimientos_bienes.idtipo_movimiento,
						movimientos_bienes_nuevos.idmovimientos_bienes_nuevos,
						movimientos_bienes_nuevos.nro_adquisicion,
						movimientos_bienes_nuevos.fecha_documento,
						movimientos_bienes_nuevos.idbeneficiario,
						movimientos_bienes_nuevos.nro_factura,
						movimientos_bienes_nuevos.fecha_factura,
						movimientos_bienes_nuevos.idorganizacion,
						movimientos_bienes_nuevos.idnivel_organizacional,
						movimientos_bienes_nuevos.codigo_catalogo as idcodigo_catalogo,
						movimientos_bienes_nuevos.codigo_bien as idcodigo_bien,
						movimientos_bienes_nuevos.idubicacion,
						movimientos_bienes_nuevos.especificaciones,
						movimientos_bienes_nuevos.marca,
						movimientos_bienes_nuevos.modelo,
						movimientos_bienes_nuevos.tipo,
						movimientos_bienes_nuevos.seriales,
						movimientos_bienes_nuevos.accesorios,
						movimientos_bienes_nuevos.costo,
						movimientos_bienes_nuevos.valor_residual,
						movimientos_bienes_nuevos.vida_util,
						movimientos_bienes_nuevos.mejoras,
						movimientos_bienes_nuevos.costo_ajustado,
						movimientos_bienes_nuevos.depresiacion_anual,
						movimientos_bienes_nuevos.depresiacion_acumulada,
						movimientos_bienes_nuevos.asegurado,
						movimientos_bienes_nuevos.aseguradora,
						movimientos_bienes_nuevos.nro_poliza,
						movimientos_bienes_nuevos.fecha_vencimiento,
						movimientos_bienes_nuevos.monto_poliza,
						movimientos_bienes_nuevos.monto_asegurado
	   						from
						movimientos_bienes_nuevos,
						detalle_catalogo_bienes
							where
						movimientos_bienes_nuevos.idmovimientos_bienes = '".$idmovimiento."'
						and detalle_catalogo_bienes.iddetalle_catalogo_bienes = movimientos_bienes_nuevos.codigo_catalogo";
		
						
						$sql_movimiento = mysql_query("select * from movimientos_bienes where idmovimientos_bienes = '".$idmovimiento."'");
						$bus_movimiento = mysql_fetch_array($sql_movimiento);
				*/		
						
		$sql_consulta = mysql_query($query)or die(mysql_error());
		?>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="95%">
          <thead>
          <tr>
          	<td width="15%" align="center" class="Browse" style="font-size:9px">Nro. Movimiento</td>
            <td width="10%" align="center" class="Browse" style="font-size:9px">Fecha</td>
            <td align="center" class="Browse" style="font-size:9px">Tipo de Movimiento</td>
          </tr>
          </thead>
          
         <?
         while($bus_consulta = mysql_fetch_array($sql_consulta)){
		 ?> 
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
          	<td align="" valign="middle" class='Browse'>REGISTRO INICIAL</td>
            <td align="" valign="middle" class='Browse'>&nbsp;</td>
            <? 	$sql_tipo = mysql_query("select * from tipo_movimiento_bienes where idtipo_movimiento_bienes = '".$bus_consulta["idtipo_movimiento"]."'"); 
				$bus_tipo = mysql_fetch_array($sql_tipo);
			?>
            <td align="" valign="middle" class='Browse'>(<?=$bus_tipo["codigo"]?>)&nbsp;<?=$bus_tipo["denominacion"]?></td>
           
          </tr>
          
          <?
          }
		  
		 
		  
		  $sql_movimientos = mysql_query("(select 	movimientos_bienes.nro_movimiento,
		  											movimientos_bienes.fecha_movimiento,
													movimientos_bienes.idtipo_movimiento,
													movimientos_bienes.idmovimientos_bienes,
													movimientos_bienes_nuevos.idmovimientos_bienes,
													movimientos_bienes.estado,
													movimientos_bienes_nuevos.idorganizacion,movimientos_bienes_nuevos.idnivel_organizacional
													from 
													movimientos_bienes,
													movimientos_bienes_nuevos
													where movimientos_bienes_nuevos.idmovimientos_bienes = movimientos_bienes.idmovimientos_bienes
													and movimientos_bienes_nuevos.codigo_bien = '".$codigo_bien."'
													and movimientos_bienes.estado = 'procesado')
											UNION
											(select movimientos_bienes.nro_movimiento,
		  											movimientos_bienes.fecha_movimiento,
													movimientos_bienes.idtipo_movimiento,
													movimientos_bienes.idmovimientos_bienes,
													movimientos_existentes_desincorporacion.idmovimientos_bienes,
													movimientos_bienes.estado,
													movimientos_existentes_desincorporacion.idorganizacion, movimientos_existentes_desincorporacion.idnivel_organizacional
													from 
													movimientos_bienes,
													movimientos_existentes_desincorporacion
													where movimientos_existentes_desincorporacion.idmovimientos_bienes = movimientos_bienes.idmovimientos_bienes
													and movimientos_existentes_desincorporacion.codigo_bien = '".$idmueble."'
													and movimientos_bienes.estado = 'procesado')
											UNION
											(select movimientos_bienes.nro_movimiento,
		  											movimientos_bienes.fecha_movimiento,
													movimientos_bienes.idtipo_movimiento,
													movimientos_bienes.idmovimientos_bienes,
													movimientos_existentes_incorporacion.idmovimientos_bienes,
													movimientos_bienes.estado,
													movimientos_existentes_incorporacion.idorganizacion, movimientos_existentes_incorporacion.idnivel_organizacional
													from 
													movimientos_bienes,
													movimientos_existentes_incorporacion
													where movimientos_existentes_incorporacion.idmovimientos_bienes = movimientos_bienes.idmovimientos_bienes
													and movimientos_existentes_incorporacion.codigo_bien = '".$idmueble."'
													and movimientos_bienes.estado = 'procesado')
											
											UNION
											(select movimientos_bienes.nro_movimiento,
		  											movimientos_bienes.fecha_movimiento,
													movimientos_existentes_desincorporacion.idtipo_movimiento,
													movimientos_bienes.idmovimientos_bienes,
													movimientos_existentes_desincorporacion.idmovimientos_bienes,
													movimientos_bienes.estado,
													movimientos_existentes_desincorporacion.idorganizacion, movimientos_existentes_desincorporacion.idnivel_organizacional
													from 
													movimientos_bienes,
													movimientos_existentes_desincorporacion
													where movimientos_existentes_desincorporacion.idmovimientos_bienes = movimientos_bienes.idmovimientos_bienes
													and movimientos_existentes_desincorporacion.codigo_bien = '".$idmueble."'
													and movimientos_bienes.estado = 'procesado')
											UNION
											(select movimientos_bienes.nro_movimiento,
		  											movimientos_bienes.fecha_movimiento,
													movimientos_existentes_incorporacion.idtipo_movimiento,
													movimientos_bienes.idmovimientos_bienes,
													movimientos_existentes_incorporacion.idmovimientos_bienes,
													movimientos_bienes.estado,
													movimientos_existentes_incorporacion.idorganizacion, movimientos_existentes_incorporacion.idnivel_organizacional
													from 
													movimientos_bienes,
													movimientos_existentes_incorporacion
													where movimientos_existentes_incorporacion.idmovimientos_bienes = movimientos_bienes.idmovimientos_bienes
													and movimientos_existentes_incorporacion.codigo_bien = '".$idmueble."'
													and movimientos_bienes.estado = 'procesado')
													
											ORDER BY fecha_movimiento
													");
		  
			          
           while($bus_movimientos = mysql_fetch_array($sql_movimientos)){
			   if ($bus_movimientos["idtipo_movimiento"] > 0){
			   	$sql_tipo = mysql_query("select * from tipo_movimiento_bienes where idtipo_movimiento_bienes = '".$bus_movimientos["idtipo_movimiento"]."'"); 
				$bus_tipo = mysql_fetch_array($sql_tipo);
			
			   	if ($bus_tipo["afecta"] == '1' and $bus_tipo["cambia_ubicacion"] == 'no'){
		 ?> 
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
          	<td align="" valign="middle" class='Browse'><?=$bus_movimientos["nro_movimiento"]?></td>
            <td align="" valign="middle" class='Browse'><?=$bus_movimientos["fecha_movimiento"]?></td>
            
            <td align="" valign="middle" class='Browse'>(<?=$bus_tipo["codigo"]?>)&nbsp;<?=$bus_tipo["denominacion"]?></td>
          		<? }
				if ($bus_tipo["afecta"] == '1' and $bus_tipo["cambia_ubicacion"] == 'si'){
					$sql_organizacion = mysql_query("select * from organizacion where idorganizacion = '".$bus_movimientos["idorganizacion"]."'")or die(mysql_error());
					$field_organizacion = mysql_fetch_array($sql_organizacion);
					$sql_nivel_organizacional = mysql_query("select * from niveles_organizacionales where idniveles_organizacionales = '".$bus_movimientos["idnivel_organizacional"]."'");
					$field_nivel_organizacional = mysql_fetch_array($sql_nivel_organizacional);
		 ?> 
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
          	<td align="" valign="middle" class='Browse'><?=$bus_movimientos["nro_movimiento"]?></td>
            <td align="" valign="middle" class='Browse'><?=$bus_movimientos["fecha_movimiento"]?></td>
            
            <td align="" valign="middle" class='Browse'>(<?=$bus_tipo["codigo"]?>)&nbsp;<?=$bus_tipo["denominacion"]?>&nbsp;-&nbsp;<?=$field_organizacion["denominacion"]?>&nbsp;-&nbsp;<?=$field_nivel_organizacional["denominacion"]?></td>
          		<? }
				if ($bus_tipo["afecta"] == '2' and $bus_tipo["cambia_ubicacion"] == 'no'){	?>
          <tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
          	<td align="" valign="middle" class='Browse'><?=$bus_movimientos["nro_movimiento"]?></td>
            <td align="" valign="middle" class='Browse'><?=$bus_movimientos["fecha_movimiento"]?></td>
            
            <td align="" valign="middle" class='Browse'>(<?=$bus_tipo["codigo"]?>)&nbsp;<?=$bus_tipo["denominacion"]?></td>
          		<? } 
				if ($bus_tipo["afecta"] == '2' and $bus_tipo["cambia_ubicacion"] == 'si'){	
					$sql_organizacion = mysql_query("select * from organizacion where idorganizacion = '".$bus_movimientos["idorganizacion"]."'")or die(mysql_error());
					$field_organizacion = mysql_fetch_array($sql_organizacion);
					$sql_nivel_organizacional = mysql_query("select * from niveles_organizacionales where idniveles_organizacionales = '".$bus_movimientos["idnivel_organizacional"]."'");
					$field_nivel_organizacional = mysql_fetch_array($sql_nivel_organizacional);
				?>
          <tr bgcolor='#FFCC00' onMouseOver="setRowColor(this, 0, 'over', '#FFCC00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFCC00', '#EAFFEA', '#FFFFAA')">
          	<td align="" valign="middle" class='Browse'><?=$bus_movimientos["nro_movimiento"]?></td>
            <td align="" valign="middle" class='Browse'><?=$bus_movimientos["fecha_movimiento"]?></td>
            
            <td align="" valign="middle" class='Browse'>(<?=$bus_tipo["codigo"]?>)&nbsp;<?=$bus_tipo["denominacion"]?>&nbsp;-&nbsp;<?=$field_organizacion["denominacion"]?>&nbsp;-&nbsp;<?=$field_nivel_organizacional["denominacion"]?></td>
          		<? } ?>
          	
           
          </tr>
          
          <?
          }
		  }
		  ?>
          </table>
          
<? 		
	} 

if($ejecutar == "consultar_registroFotografico"){
	?>
	<table align="center">
    <tr>
	<?
	$i=0;
	$sql_consulta = mysql_query("select * from registro_fotografico_bienes_muebles where idmuebles = '".$idmueble."'");
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
        <td>
        
        	<table cellpadding="5" cellspacing="5">
                <tr>
                	<td align="right"><strong onclick="eliminar_registroFotografico('<?=$bus_consulta["idregistro_fotografico_bienes_muebles"]?>')" style="cursor:pointer">X</strong></td>
                </tr>
                <tr>
                	<td align="center"><img src="modulos/bienes/imagenes/<?=$bus_consulta["nombre_imagen"]?>" width="100" height="100"></td>
                </tr>
                 <tr>
                	<td align="center"><?=$bus_consulta["descripcion"]?></td>
                </tr>
                <tr>
                	<td align="center"><input type="radio" name="imagen_principal" id="imagen_principal" value="<?=$bus_consulta["idregistro_fotografico_bienes_muebles"]?>" style="cursor:pointer" onclick="principal_registroFotografico('<?=$bus_consulta["idregistro_fotografico_bienes_muebles"]?>')" <? if($bus_consulta["principal"] == 1){echo "checked";}?>></td>
                </tr>
            </table>
        
        </td>
		<?
		if($i == 6){
			?>
			</tr>
            <tr>
			<?
			$i = 0;
		}else{
			$i++;
		}
		
	}
	?>
	</tr>
    </table>
	<?
}






if($ejecutar == "eliminar_registroFotografico"){
	$sql_consulta = mysql_query("select * from registro_fotografico_bienes_muebles where idregistro_fotografico_bienes_muebles = '".$idregistro_fotografico."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	
	
	$sql_eliminar = mysql_query("delete from registro_fotografico_bienes_muebles where idregistro_fotografico_bienes_muebles = '".$idregistro_fotografico."'");
	
	if($sql_eliminar){
		unlink("../imagenes/".$bus_consulta["nombre_imagen"]);
	}
	
}




if($ejecutar == "principal_registroFotografico"){
	$sql_actualizar = mysql_query("update registro_fotografico_bienes_muebles set principal = '0' where idmuebles = '".$idmueble."'");
	$sql_actualizar = mysql_query("update registro_fotografico_bienes_muebles set principal = '1' where idregistro_fotografico_bienes_muebles = '".$idregistro_fotografico_trabajador."'");
	
}




?>