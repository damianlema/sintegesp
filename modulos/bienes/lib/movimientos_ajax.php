<?
session_start();
include("../../../conf/conex.php");
Conectarse();
extract($_POST);
extract($_GET);


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

function listar_foros($padre, $titulo, $tab, $idcampo) {
	global $foros;
	
	foreach($foros[$padre] as $foro => $datos) {			
		if(isset($foros[$foro])) {
			
			//$nuevo_titulo = ($titulo == '') ? $datos['denominacion'] : "$titulo - {$datos['denominacion']} -";
			?>
			<option value="<?=$datos['idniveles_organizacionales']?>" <? if($idcampo == "nivel_organizacional_existente_incorporacion"){echo "onclick='mostrarBienesActualesExistentesIncorporacion(".$datos["idniveles_organizacionales"].")'";} if($idcampo == "nivel_organizacional_existente_desincorporacion"){echo "onclick='mostrarBienesActualesExistentesDesIncorporacion(".$datos["idniveles_organizacionales"].")'";} if($idcampo == "nivel_organizacional_existente_traslados"){echo "onclick='mostrarBienesActualesExistentesTraslado(".$datos["idniveles_organizacionales"].")'";}?>>
				<?=$x.' .- '.$datos['denominacion']?>
			</option>
			<?
			//$x = $tab."&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";
			listar_foros($foro, $nuevo_titulo, $x, $idcampo);
			
		}else{
			$x = $tab."&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";
			?>
			<option value="<?=$datos['idniveles_organizacionales']?>" <? if($idcampo == "nivel_organizacional_existente_incorporacion"){echo "onclick='mostrarBienesActualesExistentesIncorporacion(".$datos["idniveles_organizacionales"].")'";} if($idcampo == "nivel_organizacional_existente_desincorporacion"){echo "onclick='mostrarBienesActualesExistentesDesIncorporacion(".$datos["idniveles_organizacionales"].")'";} if($idcampo == "nivel_organizacional_existente_traslados"){echo "onclick='mostrarBienesActualesExistentesTraslado(".$datos["idniveles_organizacionales"].")'";}?>>
				<?=$x.' .- '.$datos['denominacion']?>
			</option>
			<?
			
		}
	}
}



switch($ejecutar){

	case "consultarNivelOrganizacional":
	
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
		
		if ($idcampo == 'nivel_organizacional_traslados_destino'){
		?>
			<select id="<?=$idcampo?>" name="<?=$idcampo?>" style="width:600px;" onchange="desbloquear_destino();" >
				<option value="0">Elige</option>
				<? if (mysql_num_rows($result) != 0) listar_foros(0, '', $tab, $idcampo); ?>
			</select>
        <?
		}else{
		?>
            <select id="<?=$idcampo?>" name="<?=$idcampo?>" style="width:600px;" >
                <option value="0">Elige</option>
                <? if (mysql_num_rows($result) != 0) listar_foros(0, '', $tab, $idcampo); ?>
            </select>
		<?
		}
	
		/*
	return;
		$sql_nivel = mysql_query("select * from niveles_organizacionales where organizacion = '".$idorganizacion."' and modulo = '8'")or die(mysql_error());
		?>
		<select name="<?=$idcampo?>" id="<?=$idcampo?>" style="width:600px">
		<option value="0">.:: Seleccione ::.</option>
		<?
		while($bus_nivel = mysql_fetch_array($sql_nivel)){
			?>
			<option <? if($idcampo == "nivel_organizacional_existente_incorporacion"){echo "onclick='mostrarBienesActualesExistentesIncorporacion(".$bus_nivel["idniveles_organizacionales"].")'";}?> value="<?=$bus_nivel["idniveles_organizacionales"]?>"><?=$bus_nivel["denominacion"]?></option>
			<?
		}
		?>
		</select>
		<?*/
	break;
	
	
 	case $ejecutar == "validarCodigoBien":
		$sql_validar = mysql_query("select * from muebles where codigo_bien = '".$valor."'")or die(mysql_error());
		$num_validar = mysql_num_rows($sql_validar);
		if($num_validar > 0){
			echo "existe";
		}
	break;






	case "consultarTipoMovimiento":
		?>
		<select name="tipo_movimiento" id="tipo_movimiento">
		
		<?
		if($tipo == "incorporacion"){
			$i = 1;
			$sql_consulta = mysql_query("select * from tipo_movimiento_bienes where afecta = '".$i."' 
																						and uso = 'movimientos'
																						and cambia_ubicacion = 'no'");
		}else if($tipo == "desincorporacion"){
			$i = 2;
			$sql_consulta = mysql_query("select * from tipo_movimiento_bienes where afecta = '".$i."' 
																						and uso = 'movimientos'
																						and cambia_ubicacion = 'no'");
		}else{
			$i = 3;
			$sql_consulta = mysql_query("select * from tipo_movimiento_bienes where afecta = '".$i."' 
																						and uso = 'movimientos'");
		}
		
		if($i == 3){
			$accion_tipo_movimiento = 'ambos'; ?>
            
			<option value="0" onClick="document.getElementById('accion_tipo_movimiento').value = '<?=$accion_tipo_movimiento?>'">.:: Los movimientos seran seleccionados en un proceso posterior ::.</option>
            <?
		}else{?>
			<option value="0">.:: Seleccione ::.</option>
            <?
		}
		
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			if($bus_consulta["origen_bien"] == "nuevo"){
				$accion_tipo_movimiento = 'nuevo';
			}else if($bus_consulta["origen_bien"] == "existente" and $i == 1){
				$accion_tipo_movimiento = 'existente_incorporacion';
			}else if($bus_consulta["origen_bien"] == "existente" and $i == 2){
				$accion_tipo_movimiento = 'existente_desincorporacion';
			}else if($i == 3){
				$accion_tipo_movimiento = 'ambos';
			}
			?>
			<option value="<?=$bus_consulta["idtipo_movimiento_bienes"]?>" onClick="document.getElementById('accion_tipo_movimiento').value = '<?=$accion_tipo_movimiento?>' <? if($bus_consulta["cambia_ubicacion"]=='si'){ echo ", document.getElementById('existentes_incorporacion_ubicacion_destino').style.display='block'";}?>"><?=$bus_consulta["denominacion"]?></option>
			<?
		}
		?>
		</select>
		<?
	break;
	
//********************************************************************************************************************************************************
//MOVIMIENTOS DE INCORPORACION DE BIENES NUEVOS
//********************************************************************************************************************************************************		
	
	case "ingresarDatosBasicos":
		$sql_ingresar = mysql_query("insert into movimientos_bienes
															(nro_movimiento, 
															  fecha_movimiento,
															  afecta,
															  tipo,
															  idtipo_movimiento,
															  nro_documento,
															  fecha_documento,
															  justificacion,
															  estado,
															  fechayhora,
															  pc,
															  usuario)VALUES('".$nro_movimiento."',
															  						'".$fecha_movimiento."',
																					'".$afecta."',
																					'".$tipo."',
																					'".$tipo_movimiento."',
																					'".$nro_documento."',
																					'".$fecha_documento."',
																					'".$justificacion."',
																					'elaboracion',
																					'".$fh."',
																					'".$pc."',
																					'".$login."')");
		if($sql_ingresar){
			echo "exito|.|".mysql_insert_id();
		}else{
			echo "fallo";
		}
	break;
	
	case "procesarModificar":
		$sql_modificar = mysql_query("update movimientos_bienes	set
									 			fecha_movimiento = '".$fecha_movimiento."',
												nro_documento = '".$nro_documento."',
												fecha_documento = '".$fecha_documento."',
												justificacion = '".$justificacion."'
													where 
												idmovimientos_bienes = '".$idmovimientos_bienes."'")or die(mysql_error());
	break;
	
	case "guardarInformacionPrincipalBienesNuevos":
		$sql_modificar = mysql_query("update movimientos_bienes	set
									 			nro_documento_bien_nuevo = '".$nro_documento_bienes_nuevos."',
												fecha_documento_bien_nuevo = '".$fecha_documento_bienes_nuevos."',
												proveedor_bien_nuevo = '".$proveedores_bienes_nuevos."',
												nro_factura_bien_nuevo = '".$nro_factura_bienes_nuevos."',
												fecha_factura_bien_nuevo = '".$fecha_factura_bienes_nuevos."'
													where 
												idmovimientos_bienes = '".$idmovimientos_bienes."'")or die(mysql_error());
	
	break;
	
	
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
	echo $cantidad.$numero."|.|".$codigo_con_ceros;
}

	
	
	
	case "ingresarBienesNuevos":
		for($i=0; $i<=$cantidad-1; $i++){
			
		
	
			$sql_ingresar = mysql_query("insert into movimientos_bienes_nuevos
												(idmovimientos_bienes,
												  idorganizacion,
												  idnivel_organizacional,
												  codigo_catalogo,
												  idubicacion,
												  especificaciones,
												  marca,
												  modelo,
												  tipo,
												  seriales,
												  accesorios,
												  costo,
												  valor_residual,
												  vida_util,
												  mejoras,
												  costo_ajustado,
												  depresiacion_anual,
												  depresiacion_acumulada,
												  asegurado,
												  aseguradora,
												  nro_poliza,
												  fecha_vencimiento,
												  monto_poliza,
												  monto_asegurado)
												VALUES
												('".$idmovimiento_bienes."',
												'".$idorganizacion."',
												'".$nivel_organizacional."',
												'".$codigo_catalogo."',
												'".$idubicacion."',
												'".$especificaciones."',
												'".$marca."',
												'".$modelo."',
												'".$tipo."',
												'".$serial."',
												'".$accesorios."',
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
												'".$monto_asegurado."')");
												
			
			if($sql_ingresar){
				if($i==0){
					echo "exito";
				}
				//$codigo_bien_automatico = $bus_inventario_materia["contador_bienes_muebles"]+1;
				//$actualiza = mysql_query("update relacion_contadores set contador_bienes_muebles = contador_bienes_muebles+1");
			}else{
				//$sql_eliminar_mueble = mysql_query("delete from muebles where idmuebles = '".$idinsertado."'");
				echo mysql_error();
			}
		
		}
			
	break;
	
	
	case "listarBienesNuevos":
			
			$sql_movimientos = mysql_query("select * from movimientos_bienes where idmovimientos_bienes = '".$idmovimiento."'");
			$bus_movimientos = mysql_fetch_array($sql_movimientos);
			$estado = $bus_movimientos["estado"];
		
			$query = "select detalle_catalogo_bienes.codigo as codigo_catalogo,
						detalle_catalogo_bienes.denominacion as denominacion_catalogo,
						detalle_catalogo_bienes.iddetalle_catalogo_bienes as iddetalle_catalogo_bienes,
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
						
						
		$sql_consulta = mysql_query($query)or die(mysql_error());
		?>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="95%">
          <thead>
          <tr>
            <td width="10%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Bien</td>
            <td align="center" class="Browse" style="font-size:9px">C&oacute;digo del Catalogo / Especificaciones</td>
           <?
           if($estado == "elaboracion"){
		   ?>
            <td width="10%" align="center" class="Browse" style="font-size:9px" colspan="2">Acciones</td>
            <?
            }
			?>
          </tr>
          </thead>
          
         <?
         while($bus_consulta = mysql_fetch_array($sql_consulta)){
		 ?> 
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td align="" valign="middle" class='Browse'>&nbsp;<?=$bus_consulta["idcodigo_bien"]?></td>
            <td align="" valign="middle" class='Browse'>(<?=$bus_consulta["codigo_catalogo"]?>)&nbsp;<?=$bus_consulta["especificaciones"]?></td>
            
            
            <?
            if($estado == "elaboracion"){
			?>
            
            <td width="3%" align="center" valign="middle" class='Browse'>
            <img src="imagenes/modificar.png" style="cursor:pointer" onclick="seleccionarModificarBienesNuevos('<?=$bus_movimiento["nro_documento_bien_nuevo"]?>', '<?=$bus_movimiento["fecha_documento_bien_nuevo"]?>', '<?=$bus_movimiento["proveedor_bien_nuevo"]?>', '<?=$bus_movimiento["nro_factura_bien_nuevo"]?>', '<?=$bus_movimiento["fecha_factura_bien_nuevo"]?>', '<?=$bus_consulta["idorganizacion"]?>', '<?=$bus_consulta["idnivel_organizacional"]?>', '<?=$bus_consulta["codigo_catalogo"]?>', '<?=$bus_consulta["idubicacion"]?>', '<?=$bus_consulta["especificaciones"]?>', '<?=$bus_consulta["marca"]?>', '<?=$bus_consulta["modelo"]?>', '<?=$bus_consulta["tipo"]?>', '<?=$bus_consulta["serial"]?>', '<?=$bus_consulta["accesorios"]?>', '<?=$bus_consulta["costo"]?>', '<?=$bus_consulta["valor_residual"]?>', '<?=$bus_consulta["vida_util"]?>','<?=$bus_consulta["mejoras"]?>', '<?=$bus_consulta["costo_ajustado"]?>', '<?=$bus_consulta["depreciacion_anual"]?>', '<?=$bus_consulta["depreciacion_acumulada"]?>', '<?=$bus_consulta["asegurado"]?>', '<?=$bus_consulta["idmovimientos_bienes_nuevos"]?>', '<?=$bus_consulta["idcodigo_catalogo"]?>', '<?=$bus_consulta["idcodigo_bien"]?>', '<?=$bus_consulta["aseguradora"]?>', '<?=$bus_consulta["nro_poliza"]?>', '<?=$bus_consulta["fecha_vencimiento"]?>', '<?=$bus_consulta["monto_poliza"]?>', '<?=$bus_consulta["monto_asegurado"]?>')">
            </td>
            <td width="3%" align="center" valign="middle" class='Browse'><img src="imagenes/delete.png" onclick="eliminarBienesNuevos('<?=$bus_consulta["idmovimientos_bienes_nuevos"]?>')" style="cursor:pointer"></td>
                 
               <?
               }
			   ?>  
                    
          </tr>
          
          <?
          }
		  ?>
          </table>
		<?
	
	
	break;
	
	
	case "eliminarBienesNuevos":
		$sql_eliminar = mysql_query("delete from movimientos_bienes_nuevos where idmovimientos_bienes_nuevos = '".$idmovimiento."'");
		$sql_eliminar_fotos = mysql_query("delete from registro_fotografico_bienes_nuevos where idmovimientos_bienes_nuevos = '".$idmovimiento."'");
		
	break;
	
	case "modificarBienesNuevos":
	
	//nro_adquisicion = '".$nro_documento."',fecha_documento = '".$fecha_documento."',idbeneficiario = '".$idbenficiario."',nro_factura = '".$nro_factura."',fecha_factura = '".$fecha_factura."',
		
		$sql_actualizar = mysql_query("update movimientos_bienes_nuevos set
												  idorganizacion = '".$idorganizacion."',
												  idnivel_organizacional = '".$nivel_organizacional."',
												  codigo_catalogo = '".$codigo_catalogo."',
												  codigo_bien = '".$codigo_bien."',
												  idubicacion = '".$idubicacion."',
												  especificaciones = '".$especificaciones."',
												  marca = '".$marca."',
												  modelo = '".$modelo."',
												  tipo = '".$tipo."',
												  seriales = '".$serial."',
												  accesorios = '".$accesorios."',
												  costo = '".$costo."',
												  valor_residual = '".$valor_residual."',
												  vida_util = '".$vida_util."',
												  mejoras = '".$mejoras."',
												  costo_ajustado = '".$costo_ajustado."',
												  depresiacion_anual = '".$depreciacion_anual."',
												  depresiacion_acumulada = '".$depreciacion_acumulada."',
												  asegurado = '".$asegurado."',
												  aseguradora = '".$aseguradora."',
												  nro_poliza = '".$nro_poliza."',
												  fecha_vencimiento = '".$fecha_vencimiento."',
												  monto_poliza = '".$monto_poliza."',
												  monto_asegurado = '".$monto_asegurado."'
												  where
												  idmovimientos_bienes_nuevos = '".$idmovimientos_bienes_nuevos."'")or die(mysql_error());
	
	break;
	
	
	
	case "listarRegistroFotograficoBienesNuevos":
		$sql_movimientos = mysql_query("select * from movimientos_bienes where idmovimientos_bienes = '".$idmovimiento."'")	;
		$bus_movimientos = mysql_fetch_array($sql_movimientos);
		$estado = $bus_movimientos["estado"];
		$query = "select 
						detalle_catalogo_bienes.codigo as codigo_catalogo,
						detalle_catalogo_bienes.denominacion as denominacion_catalogo,
						detalle_catalogo_bienes.iddetalle_catalogo_bienes as iddetalle_catalogo_bienes,
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
						movimientos_bienes_nuevos.asegurado
	   						from 
						movimientos_bienes_nuevos,
						detalle_catalogo_bienes
							where
						movimientos_bienes_nuevos.idmovimientos_bienes = '".$idmovimiento."'
						and detalle_catalogo_bienes.iddetalle_catalogo_bienes = movimientos_bienes_nuevos.codigo_catalogo";
		
		
		$sql_consulta = mysql_query($query)or die(mysql_error());
		$num = mysql_num_rows($sql_consulta);
		
		?>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="80%">
          <thead>
          <tr>
            <td width="9%" align="center" class="Browse" style="font-size:9px">Codigo del Bien</td>
            <td width="19%" align="center" class="Browse" style="font-size:9px">Codigo del Catalogo</td>
            <td align="center" class="Browse" style="font-size:9px" <? if($estado == "elaboracion"){?>colspan="2"<? }?>>Acciones</td>
          </tr>
          </thead>
          
         <?
         while($bus_consulta = mysql_fetch_array($sql_consulta)){
		 ?> 
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td align="" valign="middle" class='Browse'>&nbsp;<?=$bus_consulta["idcodigo_bien"]?></td>
            <td align="" valign="middle" class='Browse'>(<?=$bus_consulta["codigo_catalogo"]?>)&nbsp;<?=$bus_consulta["denominacion_catalogo"]?></td>
            <?
            if($estado == "elaboracion"){
			?>
            <td width="4%" align="center" valign="middle" class='Browse'><a href="javascript:;" onclick="document.getElementById('agregar_foto_bienes_nuevos').style.display = 'block', document.getElementById('idmovimientos_bienes_nuevos_fotos').value = '<?=$bus_consulta["idmovimientos_bienes_nuevos"]?>'">Agregar</a></td>
            <?
            }
			?>
            <td width="4%" align="center" valign="middle" class='Browse'><a href="javascript:;" onclick="consultarRegistroFotograficoBienesNuevos('<?=$bus_consulta["idmovimientos_bienes_nuevos"]?>', '<?=$estado?>')">Ver</a></td>
                    
          </tr>
          
          <?
          }
		  ?>
          </table>
          <div id="agregar_foto_bienes_nuevos" style="display:none; width:300px; height:400px">	
                <br /><div align="right"><a href="javascript:;" onclick="document.getElementById('agregar_foto_bienes_nuevos').style.display = 'none'"><strong>Cerrar (X)</strong></a></div>
                <div id="mostrarImagen" align="center"></div>
                <table align="center" style="background-color:#EAEAEA; border:#000000 solid 1px">
                    <tr>
                        <td align='right'>Foto:</td>
                        <td>
                        <form method="post" id="formImagen_bienes_nuevos" name="formImagen_bienes_nuevos" enctype="multipart/form-data" action="modulos/bienes/lib/movimientos_ajax.php" target="iframeUpload">
                          <input type="hidden" name="idmovimientos_bienes_nuevos_fotos" id="idmovimientos_bienes_nuevos_fotos">
                          <input type="file" name="foto_registroFotografico_bienes_nuevos" id="foto_registroFotografico_bienes_nuevos" size="20" align="left" onChange="document.getElementById('formImagen_bienes_nuevos').submit()">
                          <!--<input type="submit" name="boton_subir" id="boton_subir" value="Subir">-->
                          <input type="hidden" id="ejecutar" name="ejecutar" value="cargarRegistroFotograficoBienesNuevos">
                        </form>
                        <iframe id="iframeUpload" name="iframeUpload" style="display:none"></iframe>
                        <input type="hidden" id="nombre_imagen_bienes_nuevos" name="nombre_imagen_bienes_nuevos">
                        </td>
                    </tr>
                    <tr>
                    <td>Descripcion: </td>
                    <td><textarea name="descripcion_foto_bienes_nuevos" id="descripcion_foto_bienes_nuevos" rows="3" cols="30"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><input type="button" id="boton_registroFotografico_bienes_nuevos" name="boton_registroFotografico_bienes_nuevos" value="Subir Imagen" class="button" onClick="subirRegistroFotograficoBienesNuevos()"></td>
                    </tr>
                </table>	
            </div>
		<?
	break;
	
	case "subirRegistroFotograficoBienesNuevos":
		$sql_ingresar = mysql_query("insert into registro_fotografico_bienes_nuevos
													(idmovimientos_bienes_nuevos,
													imagen,
													descripcion)VALUES
													('".$idmovimientos_bienes_nuevos_fotos."',
													'".$nombre_imagen_bienes_nuevos."',
													'".$descripcion_foto_bienes_nuevos."')");
	break;
	
	
	
	
	
	
	case "consultarRegistroFotograficoBienesNuevos":

		$sql_consulta = mysql_query("select * from registro_fotografico_bienes_nuevos where idmovimientos_bienes_nuevos = '".$idmovimientos_bienes_nuevos."'")or die(mysql_error());
		?>
        <br />
        <div style="text-align:right"><a href="javascript:;" onclick="document.getElementById('listaFotosBienesNuevos').style.display = 'none'"><strong>Cerrar (X)&nbsp;&nbsp;&nbsp;</strong></a></div>
		<table align="center">
        <tr>
		<?
		$i=0;
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
			
            <td>
            	<?
                if($estado == "elaboracion"){
				?>
                <div align="right"><a href="javascript:;" onclick="eliminarRegistroFotograficoBienesNuevos('<?=$bus_consulta["idregistro_fotografico_bienes_nuevos"]?>', '<?=$idmovimientos_bienes_nuevos?>')">Eliminar</a></div>
                <?
                }
				?>
                <table width="100%">
                <tr>
                	<td style="border:#000000 solid 1px" align="center"><img src="modulos/bienes/imagenes/<?=$bus_consulta["imagen"]?>" width="150" height="150"></td>
                </tr>
                <tr>
                	<td><?=$bus_consulta["descripcion"]?></td>
                </tr>
                </table>
            </td>
			<?
            if($i == 4){
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
		
	break;
	
	
	
	
	
	case "cargarRegistroFotograficoBienesNuevos":
			$tipo = substr($_FILES['foto_registroFotografico_bienes_nuevos']['type'], 0, 5);
		$dir = '../imagenes/';
		if ($tipo == 'image') {
		$nombre_imagen = $_FILES['foto_registroFotografico_bienes_nuevos']['name'];
			while(file_exists($dir.$nombre_imagen)){
				$partes_img = explode(".",$nombre_imagen);
				$nombre_imagen = $partes_img[0].rand(0,1000000).".".$partes_img[1];
			}
			if (!copy($_FILES['foto_registroFotografico_bienes_nuevos']['tmp_name'], $dir.$nombre_imagen)){
				?>
                <script>
				parent.mostrarMensajes("error", "Disculpe la imagen no se pudo ingresar");
				</script>
                <?
			}else{

				$ruta = 'modulos/bienes/imagenes/'.$nombre_imagen;
			}
			
				?>
                
			<script>
            parent.document.getElementById('nombre_imagen_bienes_nuevos').value = '<?=$nombre_imagen?>';
			parent.document.getElementById('mostrarImagen').innerHTML = "<img src='modulos/bienes/imagenes/<?=$nombre_imagen?>' width = '100' height='120'>";
            </script>
            <?
			
		}else{
			?>
			<script>
			parent.mostrarMensajes("error", "Disculpe el archivo que intenta subir NO es una Imagen");
			</script>
			
			<?
		}
		
	break;
	
	
	
	case "eliminarRegistroFotograficoBienesNuevos":
		$sql_eliminar = mysql_query("delete from registro_fotografico_bienes_nuevos where idregistro_fotografico_bienes_nuevos = '".$idregistro_fotografico_bienes_nuevos."'");
	break;
	
	
	
	
	
	
//********************************************************************************************************************************************************
//MOVIMIENTOS DE INCORPORACION DE BIENES EXISTENTES
//********************************************************************************************************************************************************		
	
	
	
	
	
	case "ingresarExistentesIncorporacion":
		 
		  $sql_ingresar = mysql_query("insert into movimientos_existentes_incorporacion(idmovimientos_bienes,
																						  idorganizacion,
																						  idnivel_organizacional,
																						  codigo_bien,
																						  codigo_catalogo,
																						  denominacion,
																						  especificaciones,
																						  mejoras,
																						  descripcion,
																						  retorno_automatico,
																						  fecha_retorno
		  																				)VALUES(
																						'".$idmovimientos."',
																						  '".$idorganizacion."',
																						  '".$idnivel_organizacional."',
																						  '".$codigo_bien."',
																						  '".$codigo_catalogo."',
																						  '".$denominacion."',
																						  '".$especificaciones."',
																						  '".$mejoras."',
																						  '".$descripcion."',
																						  '".$retorno_automatico."',
																						  '".$fecha_retorno."')");
			/*
			if($idorganizacion_destino != 0){
				$sql_actualizar = mysql_query("update muebles set idorganizacion = '".$idorganizacion_destino."',
																	idnivel_organizacion = '".$idnivel_organizacional_destino."'
																	where idmuebles = '".$codigo_bien."'")or die(mysql_error());
			}
			*/
			
			if($sql_ingresar){
				echo "exito";
			}else{
				echo "fallo";
			}
																						
		  
	
	break;
	
	
	case "mostrarBienesActualesExistentesIncorporacion":
			 
			$sql_tipo = mysql_query("select * from tipo_movimiento_bienes where idtipo_movimiento_bienes = '".$idmovimiento."'");
			$bus_tipo = mysql_fetch_array($sql_tipo);
			 
			  $query = "select muebles.idmuebles as idmuebles,
	  					muebles.idmuebles as idcodigo_bien,
						muebles.codigo_bien,
						muebles.estado,
	  					muebles.especificaciones as especificaciones_mueble,
						muebles.idorganizacion as idorganizacion,
						muebles.idnivel_organizacion as idnivel_organizacion,
						organizacion.denominacion as organizacion,
						niveles_organizacionales.codigo as codigo_nivel,
						niveles_organizacionales.denominacion as nivel_organizacional,
						detalle_catalogo_bienes.codigo as codigo_catalogo,
						detalle_catalogo_bienes.denominacion as denominacion_catalogo,
						detalle_catalogo_bienes.iddetalle_catalogo_bienes as idcodigo_catalogo
	   						from 
						muebles,
						organizacion,
						niveles_organizacionales,
						detalle_catalogo_bienes
							where
						organizacion.idorganizacion = muebles.idorganizacion
						and niveles_organizacionales.idniveles_organizacionales = muebles.idnivel_organizacion
						and muebles.idcatalogo_bienes = detalle_catalogo_bienes.iddetalle_catalogo_bienes
						and muebles.idnivel_organizacion = '".$idnivel_organizacional."'
						and (detalle_catalogo_bienes.denominacion like '%".$buscar."%'
						or muebles.codigo_bien like '%".$buscar."%')";

		
		$sql_consulta = mysql_query($query)or die(mysql_error());
		?>
        <table style="margin-left:50px">
        <tr>
        <td><input name="campo_buscar_bienes" type="text" id="campo_buscar_bienes" value="<?=$buscar?>" size="49"></td>
        <td><input type="button" id="boton_buscar_bienes" name="boton_buscar_bienes" class="button" value="Buscar" onclick="mostrarBienesActualesExistentesIncorporacion(document.getElementById('nivel_organizacional_existente_incorporacion').value)"></td>
        </tr>
        </table>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="95%">
          <thead>
          <tr>
            <td width="15%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Bien</td>
            <td width="75%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Catalogo</td>
            <td width="10%" align="center" class="Browse" style="font-size:9px">Seleccione</td>
          </tr>
          </thead>
          
         <?
         while($bus_consulta = mysql_fetch_array($sql_consulta)){
			 
			 if ($bus_tipo["estado_bien"] != 'activo' and $bus_tipo["estado_bien"] != 'desincorporado'){
				 if ($bus_consulta["estado"] == 'activo'){
			 ?> 
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			 <? }else{ ?>
					<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
			 <? } ?>
                        <td align="" valign="middle" class='Browse'><?=$bus_consulta["codigo_bien"]?></td>
                        <td align="" valign="middle" class='Browse'>(<?=$bus_consulta["codigo_catalogo"]?>)&nbsp;<?=$bus_consulta["denominacion_catalogo"]?></td>
                        
                        <td width="11%" align="center" valign="middle" class='Browse'>
                        <img src="imagenes/validar.png" style="cursor:pointer" onclick="cargarBienExistenteIncorporacion('<?=$bus_consulta["idcodigo_bien"]?>', '<?=$bus_consulta["codigo_bien"]?>', '<?=$bus_consulta["idcodigo_catalogo"]?>', '<?="(".$bus_consulta["codigo_catalogo"].") ".$bus_consulta["denominacion_catalogo"]?>', '<?=$bus_consulta["especificaciones_mueble"]?>', '<?=$bus_consulta["idorganizacion"]?>', '<?=$bus_consulta["idnivel_organizacion"]?>')">            </td>
			  	</tr>
          <? } 
		  	if ($bus_tipo["estado_bien"] == 'activo'){
		  		if ($bus_consulta["estado"] == 'activo'){
			 ?> 
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			
                        <td align="" valign="middle" class='Browse'><?=$bus_consulta["codigo_bien"]?></td>
                        <td align="" valign="middle" class='Browse'>(<?=$bus_consulta["codigo_catalogo"]?>)&nbsp;<?=$bus_consulta["denominacion_catalogo"]?></td>
                        
                        <td width="11%" align="center" valign="middle" class='Browse'>
                        <img src="imagenes/validar.png" style="cursor:pointer" onclick="cargarBienExistenteIncorporacion('<?=$bus_consulta["idcodigo_bien"]?>', '<?=$bus_consulta["codigo_bien"]?>', '<?=$bus_consulta["idcodigo_catalogo"]?>', '<?="(".$bus_consulta["codigo_catalogo"].") ".$bus_consulta["denominacion_catalogo"]?>', '<?=$bus_consulta["especificaciones_mueble"]?>', '<?=$bus_consulta["idorganizacion"]?>', '<?=$bus_consulta["idnivel_organizacion"]?>')">            </td>
			  	</tr>
                <? } ?>
          <? }
		  if ($bus_tipo["estado_bien"] == 'desincorporado'){
		   		if ($bus_consulta["estado"] == 'desincorporado'){
			 ?> 
					<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
			
                        <td align="" valign="middle" class='Browse'><?=$bus_consulta["codigo_bien"]?></td>
                        <td align="" valign="middle" class='Browse'>(<?=$bus_consulta["codigo_catalogo"]?>)&nbsp;<?=$bus_consulta["denominacion_catalogo"]?></td>
                        
                        <td width="11%" align="center" valign="middle" class='Browse'>
                        <img src="imagenes/validar.png" style="cursor:pointer" onclick="cargarBienExistenteIncorporacion('<?=$bus_consulta["idcodigo_bien"]?>', '<?=$bus_consulta["codigo_bien"]?>', '<?=$bus_consulta["idcodigo_catalogo"]?>', '<?="(".$bus_consulta["codigo_catalogo"].") ".$bus_consulta["denominacion_catalogo"]?>', '<?=$bus_consulta["especificaciones_mueble"]?>', '<?=$bus_consulta["idorganizacion"]?>', '<?=$bus_consulta["idnivel_organizacion"]?>')">            </td>
			  	</tr>
               <? } ?>
          <? } 
          }
		  ?>
          </table>
		<?
	
	break;
	
	
	
	
	case "listaMovimientosExistentesIncorporacion":
	$sql_movimientos = mysql_query("select * from movimientos_bienes where idmovimientos_bienes = '".$idmovimiento."'");
			 $bus_movimientos = mysql_fetch_array($sql_movimientos);
			 $estado = $bus_movimientos["estado"];
		$query = "select muebles.idmuebles as idmuebles,
	  					muebles.codigo_bien as codigo_bien,
						detalle_catalogo_bienes.codigo as codigo_catalogo,
						detalle_catalogo_bienes.denominacion as denominacion_catalogo,
						detalle_catalogo_bienes.iddetalle_catalogo_bienes as iddetalle_catalogo_bienes,
						movimientos_existentes_incorporacion.idmovimientos_existentes_incorporacion,
						movimientos_existentes_incorporacion.idorganizacion,
						movimientos_existentes_incorporacion.idnivel_organizacional,
						movimientos_existentes_incorporacion.idorganizacion_destino,
						movimientos_existentes_incorporacion.idnivel_organizacional_destino,
						movimientos_existentes_incorporacion.codigo_bien as idcodigo_bien,
						movimientos_existentes_incorporacion.codigo_catalogo as idcodigo_catalogo,
						movimientos_existentes_incorporacion.denominacion,
						movimientos_existentes_incorporacion.mejoras,
						movimientos_existentes_incorporacion.descripcion,
						movimientos_existentes_incorporacion.retorno_automatico,
						movimientos_existentes_incorporacion.fecha_retorno,
						movimientos_existentes_incorporacion.especificaciones,
						organizacion.denominacion as organizacion,
						niveles_organizacionales.denominacion as nivel_organizacional
	   						from 
						movimientos_existentes_incorporacion,
						muebles,
						detalle_catalogo_bienes,
						organizacion,
						niveles_organizacionales
							where
						movimientos_existentes_incorporacion.idmovimientos_bienes = '".$idmovimiento."'	
						and muebles.idmuebles = movimientos_existentes_incorporacion.codigo_bien
						and detalle_catalogo_bienes.iddetalle_catalogo_bienes = movimientos_existentes_incorporacion.codigo_catalogo
						and organizacion.idorganizacion = movimientos_existentes_incorporacion.idorganizacion
						and niveles_organizacionales.idniveles_organizacionales = movimientos_existentes_incorporacion.idnivel_organizacional";
		
		
		$sql_consulta = mysql_query($query)or die(mysql_error());
		?>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="100%">
          <thead>
          <tr>
            <td width="9%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Bien</td>
            <td width="19%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Catalogo</td>
            <td width="26%" align="center" class="Browse" style="font-size:9px">Organizaci&oacute;n</td>
            <td width="26%" align="center" class="Browse" style="font-size:9px">Nivel Organizacional</td>
            <td width="12%" align="center" class="Browse" style="font-size:9px">Mejoras</td>
            <?
            if($estado == "elaboracion"){
			?>
            <td align="center" class="Browse" style="font-size:9px">Acciones</td>
            <?
            }
			?>
          </tr>
          </thead>
          
         <?
         while($bus_consulta = mysql_fetch_array($sql_consulta)){
		 ?> 
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td align="" valign="middle" class='Browse'><?=$bus_consulta["codigo_bien"]?></td>
            <td align="" valign="middle" class='Browse'>(<?=$bus_consulta["codigo_catalogo"]?>)&nbsp;<?=$bus_consulta["denominacion_catalogo"]?></td>
            <td align="" valign="middle" class='Browse'><?=$bus_consulta["organizacion"]?></td>
            <td align="" valign="middle" class='Browse'><?=$bus_consulta["nivel_organizacional"]?></td>
            <td align="right" valign="middle" class='Browse'><?=number_format($bus_consulta["mejoras"],2,',','.')?></td>
            <!--  <td width="4%" align="center" valign="middle" class='Browse'>
           <img src="imagenes/modificar.png" style="cursor:pointer" onclick="seleccionarModificarExistentesIncorporacion('<?="( ".$bus_consulta["codigo_catalogo"]." ) ".$bus_consulta["denominacion_catalogo"]?>', '<?=$bus_consulta["idcodigo_catalogo"]?>', '<?=$bus_consulta["codigo_bien"]?>', '<?=$bus_consulta["idcodigo_bien"]?>', '<?=$bus_consulta["idorganizacion"]?>', '<?=$bus_consulta["idnivel_organizacional"]?>', '<?=$bus_consulta["idorganizacion_destino"]?>', '<?=$bus_consulta["idnivel_organizacional_destino"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["mejoras"]?>', '<?=$bus_consulta["descripcion"]?>', '<?=$bus_consulta["retorno_automatico"]?>', '<?=$bus_consulta["especificaciones"]?>', '<?=$bus_consulta["fehca_retorno"]?>')">            </td> -->
            <?
            if($estado  == "elaboracion"){
			?>
            <td width="4%" align="center" valign="middle" class='Browse'><img src="imagenes/delete.png" onclick="eliminarMovimientosExistentesIncorporacion('<?=$bus_consulta["idmovimientos_existentes_incorporacion"]?>')" style="cursor:pointer"></td>
            <?
            }
			?>
                    
          </tr>
          
          <?
          }
		  ?>
          </table>
		<?
	break;
	
	
	case "eliminarMovimientosExistentesIncorporacion":
		$sql_eliminar = mysql_query("delete from movimientos_existentes_incorporacion where idmovimientos_existentes_incorporacion = '".$idmovimiento."'")or die(mysql_error());
		$sql_eliminar = mysql_query("delete from registro_fotografico_existentes_incorporacion where idmovimientos_existentes_incorporacion = '".$idmovimiento."'")or die(mysql_error());
	break;
	
	
	
	
	
	
	
	case "listarRegistroFotograficoExistentesIncorporacion":
		
		$sql_movimientos = mysql_query("select * from movimientos_bienes where idmovimientos_bienes = '".$idmovimiento."'");
		$bus_movimientos = mysql_fetch_array($sql_movimientos);
		
		$estado = $bus_movimientos["estado"];
		
		
		$query = "select muebles.idmuebles as idmuebles,
	  					muebles.codigo_bien as codigo_bien,
						detalle_catalogo_bienes.codigo as codigo_catalogo,
						detalle_catalogo_bienes.denominacion as denominacion_catalogo,
						detalle_catalogo_bienes.iddetalle_catalogo_bienes as iddetalle_catalogo_bienes,
						movimientos_existentes_incorporacion.idmovimientos_existentes_incorporacion,
						movimientos_existentes_incorporacion.idorganizacion,
						movimientos_existentes_incorporacion.idnivel_organizacional,
						movimientos_existentes_incorporacion.idorganizacion_destino,
						movimientos_existentes_incorporacion.idnivel_organizacional_destino,
						movimientos_existentes_incorporacion.codigo_bien as idcodigo_bien,
						movimientos_existentes_incorporacion.codigo_catalogo as idcodigo_catalogo,
						movimientos_existentes_incorporacion.denominacion,
						movimientos_existentes_incorporacion.mejoras,
						movimientos_existentes_incorporacion.descripcion,
						movimientos_existentes_incorporacion.retorno_automatico,
						movimientos_existentes_incorporacion.fecha_retorno,
						movimientos_existentes_incorporacion.especificaciones,
						organizacion.denominacion as organizacion,
						niveles_organizacionales.denominacion as nivel_organizacional
	   						from 
						movimientos_existentes_incorporacion,
						muebles,
						detalle_catalogo_bienes,
						organizacion,
						niveles_organizacionales
							where
						movimientos_existentes_incorporacion.idmovimientos_bienes = '".$idmovimiento."'	
						and muebles.idmuebles = movimientos_existentes_incorporacion.codigo_bien
						and detalle_catalogo_bienes.iddetalle_catalogo_bienes = movimientos_existentes_incorporacion.codigo_catalogo
						and organizacion.idorganizacion = movimientos_existentes_incorporacion.idorganizacion
						and niveles_organizacionales.idniveles_organizacionales = movimientos_existentes_incorporacion.idnivel_organizacional";
		
		
		$sql_consulta = mysql_query($query)or die(mysql_error());
		?>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="90%">
          <thead>
          <tr>
            <td width="9%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Bien</td>
            <td width="69%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Catalogo</td>
            <td align="center" class="Browse" style="font-size:9px" <? if($estado == "elaboracion"){?>colspan="2"<? }?>>Acciones</td>
          </tr>
          </thead>
          
         <?
         while($bus_consulta = mysql_fetch_array($sql_consulta)){
		 ?> 
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td align="" valign="middle" class='Browse'><?=$bus_consulta["codigo_bien"]?></td>
            <td align="" valign="middle" class='Browse'>(<?=$bus_consulta["codigo_catalogo"]?>)&nbsp;<?=$bus_consulta["denominacion_catalogo"]?></td>
            <?
            if($estado == "elaboracion"){
			?>
            <td width="4%" align="center" valign="middle" class='Browse'><a href="javascript:;" onclick="document.getElementById('agregar_foto_incorporacion').style.display = 'block', document.getElementById('idmovimientos_existentes_incorporacion_fotos').value = '<?=$bus_consulta["idmovimientos_existentes_incorporacion"]?>'">Agregar</a></td>
            <?
            }
			?>
            <td width="4%" align="center" valign="middle" class='Browse'><a href="javascript:;" onclick="consultarRegistroFotografico('<?=$bus_consulta["idmovimientos_existentes_incorporacion"]?>', '<?=$estado?>')">Ver</a></td>
                    
          </tr>
          
          <?
          }
		  ?>
          </table>
          
          <div id="agregar_foto_incorporacion" style="display:none; width:300px; height:400px">	
                <br /><div align="right"><a href="javascript:;" onclick="document.getElementById('agregar_foto_incorporacion').style.display = 'none'"><strong>Cerrar (X)</strong></a></div>
                <div id="mostrarImagenIncorporacionExistente" align="center"></div>
                <table align="center" style="background-color:#EAEAEA; border:#000000 solid 1px">
                    <tr>
                        <td align='right'>Foto:</td>
                        <td>
                        <form method="post" id="formImagen_existentes_incorporacion" name="formImagen_existentes_incorporacion" enctype="multipart/form-data" action="modulos/bienes/lib/movimientos_ajax.php" target="iframeUpload">
                          <input type="hidden" name="idmovimientos_existentes_incorporacion_fotos" id="idmovimientos_existentes_incorporacion_fotos">
                          <input type="file" name="foto_registroFotografico_existentes_incorporacion" id="foto_registroFotografico_existentes_incorporacion" size="20" align="left" onChange="document.getElementById('formImagen_existentes_incorporacion').submit()">
                          <!--<input type="submit" name="boton_subir" id="boton_subir" value="Subir">-->
                          <input type="hidden" id="ejecutar" name="ejecutar" value="cargarRegistroFotograficoExistentesIncorporacion">
                        </form>
                        <iframe id="iframeUpload" name="iframeUpload" style="display:none"></iframe>
                        <input type="hidden" id="nombre_imagen_existentes_incorporacion" name="nombre_imagen_existentes_incorporacion">
                        </td>
                    </tr>
                    <tr>
                    <td>Descripcion: </td>
                    <td><textarea name="descripcion_foto_existentes_incorporacion" id="descripcion_foto_existentes_incorporacion" rows="3" cols="30"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><input type="button" id="boton_registroFotografico_existentes_incorporacion" name="boton_registroFotografico_existentes_incorporacion" value="Subir Imagen" class="button" onClick="subirRegistroFotograficoExistentesIncorporacion()"></td>
                    </tr>
                </table>
                
            </div>
        
		<?
	break;
	
	
	case "subirRegistroFotograficoExistentesIncorporacion":
		$sql_ingresar = mysql_query("insert into registro_fotografico_existentes_incorporacion
													(idmovimientos_existentes_incorporacion,
													imagen,
													descripcion)VALUES
													('".$idmovimientos_existentes_incorporacion_fotos."',
													'".$nombre_imagen_existentes_incorporacion."',
													'".$descripcion_foto_existentes_incorporacion."')");
	break;
	
	
	
	
	
	
	case "consultarRegistroFotografico":


		$sql_consulta = mysql_query("select * from registro_fotografico_existentes_incorporacion where idmovimientos_existentes_incorporacion = '".$idmovimientos_existentes_incorporacion."'")or die(mysql_error());
		?>
        <br />
        <div style="text-align:right"><a href="javascript:;" onclick="document.getElementById('listaFotosExistentesIncorporacion').style.display = 'none'"><strong>Cerrar (X)&nbsp;&nbsp;&nbsp;</strong></a></div>
		<table align="center">
        <tr>
		<?
		$i=0;
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
			
            <td>
            	<?
                if($estado == "elaboracion"){
				?>
                <div align="right"><a href="javascript:;" onclick="eliminarRegistroFotograficoExistentesIncorporacion('<?=$bus_consulta["idregistro_fotografico_existentes_incorporacion"]?>', '<?=$idmovimientos_existentes_incorporacion?>')">Eliminar</a></div>
                <?
                }
				?>
                <table width="100%">
                    <tr>
                        <td style="border:#000000 solid 1px" align="center"><img src="modulos/bienes/imagenes/<?=$bus_consulta["imagen"]?>" width="150" height="150"></td>
                    </tr>
                    <tr>
                        <td><?=$bus_consulta["descripcion"]?></td>
                    </tr>
                </table>
                
            </td>
		<?
		if($i == 4){
			?>
			</tr>
            <tr>
			<?
			$i = 0;
		}else{
			$i++;
		}
		}
		
	break;
	
	
	
	
	
	case "cargarRegistroFotograficoExistentesIncorporacion":
	
		$tipo = substr($_FILES['foto_registroFotografico_existentes_incorporacion']['type'], 0, 5);
		$dir = '../imagenes/';
		if ($tipo == 'image') {
		$nombre_imagen = $_FILES['foto_registroFotografico_existentes_incorporacion']['name'];
			while(file_exists($dir.$nombre_imagen)){
				$partes_img = explode(".",$nombre_imagen);
				$nombre_imagen = $partes_img[0].rand(0,1000000).".".$partes_img[1];
			}
			if (!copy($_FILES['foto_registroFotografico_existentes_incorporacion']['tmp_name'], $dir.$nombre_imagen)){
				?>
                <script>
				parent.mostrarMensajes("error", "Disculpe la imagen no se pudo ingresar");
				</script>
                <?
			}else{

				$ruta = 'modulos/bienes/imagenes/'.$nombre_imagen;
			}
			
				?>
                
			<script>
            parent.document.getElementById('nombre_imagen_existentes_incorporacion').value = '<?=$nombre_imagen?>';
			parent.document.getElementById('mostrarImagenIncorporacionExistente').innerHTML = "<img src='modulos/bienes/imagenes/<?=$nombre_imagen?>' width = '100' height='120'>";
            </script>
            <?
			
		}else{
			?>
			<script>
			parent.mostrarMensajes("error", "Disculpe el archivo que intenta subir NO es una Imagen");
			</script>
			
			<?
		}
	
	
	
	
		
	break;
	
	
	case "eliminarRegistroFotograficoExistentesIncorporacion":
		$sql_eliminar = mysql_query("delete from registro_fotografico_existentes_incorporacion where idregistro_fotografico_existentes_incorporacion = '".$idregistro_fotografico_existentes_incorporacion."'");
	break;
	
	
	
	
	
	
	
	
	
	
	
//********************************************************************************************************************************************************
//MOVIMIENTOS DE DESINCORPORACION DE BIENES EXISTENTES
//********************************************************************************************************************************************************		
	
	
	
	
	
	case "ingresarExistentesDesIncorporacion":
		 
		  $sql_ingresar = mysql_query("insert into movimientos_existentes_desincorporacion(idmovimientos_bienes,
																						  codigo_bien,
																						  codigo_catalogo,
																						  denominacion,
																						  especificaciones,
																						  motivos,
																						  idorganizacion,
																						  idnivel_organizacional,
																						  idorganizacion_destino,
																						  idnivel_organizacional_destino
		  																				)VALUES(
																						'".$idmovimientos."',
																						  '".$codigo_bien."',
																						  '".$codigo_catalogo."',
																						  '".$denominacion."',
																						  '".$especificaciones."',
																						  '".$descripcion."',
																						  '".$idorganizacion."',
																						  '".$idnivel_organizacional."',
																						  '".$idorganizacion_destino."',
																						  '".$idnivel_organizacional_destino."')");
			
			
			if($sql_ingresar){
				echo "exito";
			}else{
				echo "fallo";
			}
																						
		  
	
	break;
	
	
	case "mostrarBienesActualesExistentesDesIncorporacion":
			 
			$sql_tipo = mysql_query("select * from tipo_movimiento_bienes where idtipo_movimiento_bienes = '".$idmovimiento."'");
			$bus_tipo = mysql_fetch_array($sql_tipo);
			 
			  $query = "select muebles.idmuebles as idmuebles,
	  					muebles.idmuebles as idcodigo_bien,
						muebles.codigo_bien,
						muebles.estado,
	  					muebles.especificaciones as especificaciones_mueble,
						muebles.idorganizacion as idorganizacion,
						muebles.idnivel_organizacion as idnivel_organizacion,
						organizacion.denominacion as organizacion,
						niveles_organizacionales.codigo as codigo_nivel,
						niveles_organizacionales.denominacion as nivel_organizacional,
						detalle_catalogo_bienes.codigo as codigo_catalogo,
						detalle_catalogo_bienes.denominacion as denominacion_catalogo,
						detalle_catalogo_bienes.iddetalle_catalogo_bienes as idcodigo_catalogo
	   						from 
						muebles,
						organizacion,
						niveles_organizacionales,
						detalle_catalogo_bienes
							where
						organizacion.idorganizacion = muebles.idorganizacion
						and niveles_organizacionales.idniveles_organizacionales = muebles.idnivel_organizacion
						and muebles.idcatalogo_bienes = detalle_catalogo_bienes.iddetalle_catalogo_bienes
						and muebles.idnivel_organizacion = '".$idnivel_organizacional."'
						and (detalle_catalogo_bienes.denominacion like '%".$buscar."%'
						or muebles.codigo_bien like '%".$buscar."%')";

		
		$sql_consulta = mysql_query($query)or die(mysql_error());
		?>
        <table style="margin-left:50px">
        <tr>
        <td><input name="campo_buscar_bienes" type="text" id="campo_buscar_bienes" value="<?=$buscar?>" size="49"></td>
        <td><input type="button" id="boton_buscar_bienes" name="boton_buscar_bienes" class="button" value="Buscar" onclick="mostrarBienesActualesExistentesDesIncorporacion(document.getElementById('nivel_organizacional_existente_desincorporacion').value)"></td>
        </tr>
        </table>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="95%">
          <thead>
          <tr>
            <td width="15%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Bien</td>
            <td width="75%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Catalogo</td>
            <td width="10%" align="center" class="Browse" style="font-size:9px">Seleccione</td>
          </tr>
          </thead>
          
         <?
         while($bus_consulta = mysql_fetch_array($sql_consulta)){
             $sql_valido = mysql_query("select * from movimientos_existentes_desincorporacion
                                                    where idmovimientos_bienes = '".$idmovimiento_desincorporacion."'
                                                        and codigo_bien = '".$bus_consulta["idcodigo_bien"]."'");
             //$bus_valido = mysql_fetch_array($sql_valido);
             if(mysql_num_rows($sql_valido)<=0) {
                 if ($bus_tipo["estado_bien"] != 'activo' and $bus_tipo["estado_bien"] != 'desincorporado') {
                     if ($bus_consulta["estado"] == 'activo') {
                         ?>
                         <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                     <? } else { ?>
                         <tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
                     <? } ?>
                     <td align="" valign="middle" class='Browse'><?= $bus_consulta["idcodigo_bien"] ?></td>
                     <td align="" valign="middle" class='Browse'>(<?= $bus_consulta["codigo_catalogo"] ?>
                         )&nbsp;<?= $bus_consulta["denominacion_catalogo"] ?></td>

                     <td width="11%" align="center" valign="middle" class='Browse'>
                         <img src="imagenes/validar.png" style="cursor:pointer"
                              onclick="cargarBienExistenteDesIncorporacion('<?= $bus_consulta["idcodigo_bien"] ?>', '<?= $bus_consulta["codigo_bien"] ?>', '<?= $bus_consulta["idcodigo_catalogo"] ?>', '<?= "(" . $bus_consulta["codigo_catalogo"] . ") " . $bus_consulta["denominacion_catalogo"] ?>', '<?= $bus_consulta["especificaciones_mueble"] ?>', '<?= $bus_consulta["idorganizacion"] ?>', '<?= $bus_consulta["idnivel_organizacion"] ?>')">
                     </td>
                     </tr>
                 <?
                 }
                 if ($bus_tipo["estado_bien"] == 'activo') {
                     if ($bus_consulta["estado"] == 'activo') {
                         ?>
                         <tr bgcolor='#e7dfce'
                             onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')"
                             onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">

                             <td align="" valign="middle" class='Browse'><?= $bus_consulta["codigo_bien"] ?></td>
                             <td align="" valign="middle" class='Browse'>(<?= $bus_consulta["codigo_catalogo"] ?>
                                 )&nbsp;<?= $bus_consulta["denominacion_catalogo"] ?></td>

                             <td width="11%" align="center" valign="middle" class='Browse'>
                                 <img src="imagenes/validar.png" style="cursor:pointer"
                                      onclick="cargarBienExistenteDesIncorporacion('<?= $bus_consulta["idcodigo_bien"] ?>', '<?= $bus_consulta["codigo_bien"] ?>', '<?= $bus_consulta["idcodigo_catalogo"] ?>', '<?= "(" . $bus_consulta["codigo_catalogo"] . ") " . $bus_consulta["denominacion_catalogo"] ?>', '<?= $bus_consulta["especificaciones_mueble"] ?>', '<?= $bus_consulta["idorganizacion"] ?>', '<?= $bus_consulta["idnivel_organizacion"] ?>')">
                             </td>
                         </tr>
                     <? } ?>
                 <?
                 }
                 if ($bus_tipo["estado_bien"] == 'desincorporado') {
                     if ($bus_consulta["estado"] == 'desincorporado') {
                         ?>
                         <tr bgcolor='#FF0000'
                             onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')"
                             onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">

                             <td align="" valign="middle" class='Browse'><?= $bus_consulta["codigo_bien"] ?></td>
                             <td align="" valign="middle" class='Browse'>(<?= $bus_consulta["codigo_catalogo"] ?>
                                 )&nbsp;<?= $bus_consulta["denominacion_catalogo"] ?></td>

                             <td width="11%" align="center" valign="middle" class='Browse'>
                                 <img src="imagenes/validar.png" style="cursor:pointer"
                                      onclick="cargarBienExistenteDesIncorporacion('<?= $bus_consulta["idcodigo_bien"] ?>', '<?= $bus_consulta["codigo_bien"] ?>', '<?= $bus_consulta["idcodigo_catalogo"] ?>', '<?= "(" . $bus_consulta["codigo_catalogo"] . ") " . $bus_consulta["denominacion_catalogo"] ?>', '<?= $bus_consulta["especificaciones_mueble"] ?>', '<?= $bus_consulta["idorganizacion"] ?>', '<?= $bus_consulta["idnivel_organizacion"] ?>')">
                             </td>
                         </tr>
                     <? } ?>
                 <?
                 }
             }
          }
		  ?>
          </table>
		<?
	
	break;
	
	
	
	
	
	case "listaMovimientosExistentesDesIncorporacion":
	$sql_movimientos = mysql_query("select * from movimientos_bienes where idmovimientos_bienes = '".$idmovimiento."'");
			 $bus_movimientos = mysql_fetch_array($sql_movimientos);
			 $estado = $bus_movimientos["estado"];
		$query = "select muebles.idmuebles as idmuebles,
	  					muebles.codigo_bien as codigo_bien,
						muebles.estado,
	  					muebles.especificaciones as especificaciones_mueble,
						movimientos_existentes_desincorporacion.idorganizacion as idorganizacion,
						movimientos_existentes_desincorporacion.idnivel_organizacional as idnivel_organizacion,
						movimientos_existentes_desincorporacion.idorganizacion_destino as idorganizacion_destino,
						movimientos_existentes_desincorporacion.idnivel_organizacional_destino as idnivel_organizacion_destino,
						detalle_catalogo_bienes.codigo as codigo_catalogo,
						detalle_catalogo_bienes.denominacion as denominacion_catalogo,
						detalle_catalogo_bienes.iddetalle_catalogo_bienes as iddetalle_catalogo_bienes,
						movimientos_existentes_desincorporacion.idmovimientos_existentes_desincorporacion,
						movimientos_existentes_desincorporacion.codigo_bien as idcodigo_bien,
						movimientos_existentes_desincorporacion.codigo_catalogo as idcodigo_catalogo,
						movimientos_existentes_desincorporacion.denominacion,
						movimientos_existentes_desincorporacion.motivos,
						movimientos_existentes_desincorporacion.especificaciones,
						organizacion.denominacion as organizacion,
						niveles_organizacionales.denominacion as nivel_organizacional
	   						from 
						movimientos_existentes_desincorporacion,
						muebles,
						detalle_catalogo_bienes,
						organizacion,
						niveles_organizacionales
							where
						movimientos_existentes_desincorporacion.idmovimientos_bienes = '".$idmovimiento."'	
						and muebles.idmuebles = movimientos_existentes_desincorporacion.codigo_bien
						and detalle_catalogo_bienes.iddetalle_catalogo_bienes = movimientos_existentes_desincorporacion.codigo_catalogo
						and organizacion.idorganizacion = movimientos_existentes_desincorporacion.idorganizacion
						and niveles_organizacionales.idniveles_organizacionales = movimientos_existentes_desincorporacion.idnivel_organizacional";
		
		
		$sql_consulta = mysql_query($query)or die(mysql_error());
		?>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="100%">
          <thead>
          <tr>
            <td width="9%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Bien</td>
            <td width="19%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Catalogo</td>
            <td width="26%" align="center" class="Browse" style="font-size:9px">Organizaci&oacute;n</td>
            <td width="26%" align="center" class="Browse" style="font-size:9px">Nivel Organizacional</td>
            <td width="12%" align="center" class="Browse" style="font-size:9px">Mejoras</td>
            <?
            if($estado == "elaboracion"){
			?>
            <td align="center" class="Browse" style="font-size:9px">Acciones</td>
            <?
            }
			?>
          </tr>
          </thead>
          
         <?
         while($bus_consulta = mysql_fetch_array($sql_consulta)){
		 ?> 
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td align="" valign="middle" class='Browse'><?=$bus_consulta["codigo_bien"]?></td>
            <td align="" valign="middle" class='Browse'>(<?=$bus_consulta["codigo_catalogo"]?>)&nbsp;<?=$bus_consulta["denominacion_catalogo"]?></td>
            <td align="" valign="middle" class='Browse'><?=$bus_consulta["organizacion"]?></td>
            <td align="" valign="middle" class='Browse'><?=$bus_consulta["nivel_organizacional"]?></td>
            <td align="right" valign="middle" class='Browse'><?=number_format($bus_consulta["mejoras"],2,',','.')?></td>
            <!--  <td width="4%" align="center" valign="middle" class='Browse'>
           <img src="imagenes/modificar.png" style="cursor:pointer" onclick="seleccionarModificarExistentesDesIncorporacion('<?="( ".$bus_consulta["codigo_catalogo"]." ) ".$bus_consulta["denominacion_catalogo"]?>', '<?=$bus_consulta["idcodigo_catalogo"]?>', '<?=$bus_consulta["codigo_bien"]?>', '<?=$bus_consulta["idcodigo_bien"]?>', '<?=$bus_consulta["idorganizacion"]?>', '<?=$bus_consulta["idnivel_organizacional"]?>', '<?=$bus_consulta["idorganizacion_destino"]?>', '<?=$bus_consulta["idnivel_organizacional_destino"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["motivos"]?>', '<?=$bus_consulta["especificaciones"]?>')">            </td> -->
            <?
            if($estado  == "elaboracion"){
			?>
            <td width="4%" align="center" valign="middle" class='Browse'><img src="imagenes/delete.png" onclick="eliminarMovimientosExistentesDesIncorporacion('<?=$bus_consulta["idmovimientos_existentes_desincorporacion"]?>')" style="cursor:pointer"></td>
            <?
            }
			?>
                    
          </tr>
          
          <?
          }
		  ?>
          </table>
		<?
	break;
	
	
	case "eliminarMovimientosExistentesDesIncorporacion":
		$sql_eliminar = mysql_query("delete from movimientos_existentes_desincorporacion where idmovimientos_existentes_desincorporacion = '".$idmovimiento."'")or die(mysql_error());
		$sql_eliminar = mysql_query("delete from registro_fotografico_existentes_desincorporacion where idmovimientos_existentes_desincorporacion = '".$idmovimiento."'")or die(mysql_error());
	break;
	
	
	
	
	
	
	
	case "listarRegistroFotograficoExistentesDesIncorporacion":
		
		$sql_movimientos = mysql_query("select * from movimientos_bienes where idmovimientos_bienes = '".$idmovimiento."'");
		$bus_movimientos = mysql_fetch_array($sql_movimientos);
		
		$estado = $bus_movimientos["estado"];
		
		
		$query = "select muebles.idmuebles as idmuebles,
	  					muebles.codigo_bien as codigo_bien,
						movimientos_existentes_desincorporacion.idorganizacion as idorganizacion,
						movimientos_existentes_desincorporacion.idnivel_organizacional as idnivel_organizacion,
						detalle_catalogo_bienes.codigo as codigo_catalogo,
						detalle_catalogo_bienes.denominacion as denominacion_catalogo,
						detalle_catalogo_bienes.iddetalle_catalogo_bienes as iddetalle_catalogo_bienes,
						movimientos_existentes_desincorporacion.idmovimientos_existentes_desincorporacion,
						movimientos_existentes_desincorporacion.codigo_bien as idcodigo_bien,
						movimientos_existentes_desincorporacion.codigo_catalogo as idcodigo_catalogo,
						movimientos_existentes_desincorporacion.denominacion,
						movimientos_existentes_desincorporacion.motivos,
						movimientos_existentes_desincorporacion.especificaciones,
						organizacion.denominacion as organizacion,
						niveles_organizacionales.denominacion as nivel_organizacional
	   						from 
						movimientos_existentes_desincorporacion,
						muebles,
						detalle_catalogo_bienes,
						organizacion,
						niveles_organizacionales
							where
						movimientos_existentes_desincorporacion.idmovimientos_bienes = '".$idmovimiento."'	
						and muebles.idmuebles = movimientos_existentes_desincorporacion.codigo_bien
						and detalle_catalogo_bienes.iddetalle_catalogo_bienes = movimientos_existentes_desincorporacion.codigo_catalogo
						and organizacion.idorganizacion = movimientos_existentes_desincorporacion.idorganizacion
						and niveles_organizacionales.idniveles_organizacionales = movimientos_existentes_desincorporacion.idnivel_organizacional";
		
		
		$sql_consulta = mysql_query($query)or die(mysql_error());
		?>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="90%">
          <thead>
          <tr>
            <td width="9%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Bien</td>
            <td width="69%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Catalogo</td>
            <td align="center" class="Browse" style="font-size:9px" <? if($estado == "elaboracion"){?>colspan="2"<? }?>>Acciones</td>
          </tr>
          </thead>
          
         <?
         while($bus_consulta = mysql_fetch_array($sql_consulta)){
		 ?> 
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td align="" valign="middle" class='Browse'><?=$bus_consulta["codigo_bien"]?></td>
            <td align="" valign="middle" class='Browse'>(<?=$bus_consulta["codigo_catalogo"]?>)&nbsp;<?=$bus_consulta["denominacion_catalogo"]?></td>
            <?
            if($estado == "elaboracion"){
			?>
            <td width="4%" align="center" valign="middle" class='Browse'><a href="javascript:;" onclick="document.getElementById('agregar_foto_desincorporacion').style.display = 'block', document.getElementById('idmovimientos_existentes_desincorporacion_fotos').value = '<?=$bus_consulta["idmovimientos_existentes_desincorporacion"]?>'">Agregar</a></td>
            <?
            }
			?>
            <td width="4%" align="center" valign="middle" class='Browse'><a href="javascript:;" onclick="consultarRegistroFotograficoExistentesDesIncorporacion('<?=$bus_consulta["idmovimientos_existentes_desincorporacion"]?>', '<?=$estado?>')">Ver</a></td>
                    
          </tr>
          
          <?
          }
		  ?>
          </table>
          
          <div id="agregar_foto_desincorporacion" style="display:none; width:300px; height:400px">	
                <br /><div align="right"><a href="javascript:;" onclick="document.getElementById('agregar_foto_desincorporacion').style.display = 'none'"><strong>Cerrar (X)</strong></a></div>
                <div id="mostrarImagenDesIncorporacionExistente" align="center"></div>
                <table align="center" style="background-color:#EAEAEA; border:#000000 solid 1px">
                    <tr>
                        <td align='right'>Foto:</td>
                        <td>
                        <form method="post" id="formImagen_existentes_desincorporacion" name="formImagen_existentes_desincorporacion" enctype="multipart/form-data" action="modulos/bienes/lib/movimientos_ajax.php" target="iframeUpload">
                          <input type="hidden" name="idmovimientos_existentes_desincorporacion_fotos" id="idmovimientos_existentes_desincorporacion_fotos">
                          <input type="file" name="foto_registroFotografico_existentes_desincorporacion" id="foto_registroFotografico_existentes_desincorporacion" size="20" align="left" onChange="document.getElementById('formImagen_existentes_desincorporacion').submit()">
                          <!--<input type="submit" name="boton_subir" id="boton_subir" value="Subir">-->
                          <input type="hidden" id="ejecutar" name="ejecutar" value="cargarRegistroFotograficoExistentesDesIncorporacion">
                        </form>
                        <iframe id="iframeUpload" name="iframeUpload" style="display:none"></iframe>
                        <input type="hidden" id="nombre_imagen_existentes_desincorporacion" name="nombre_imagen_existentes_desincorporacion">
                        </td>
                    </tr>
                    <tr>
                    <td>Descripcion: </td>
                    <td><textarea name="descripcion_foto_existentes_desincorporacion" id="descripcion_foto_existentes_desincorporacion" rows="3" cols="30"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><input type="button" id="boton_registroFotografico_existentes_desincorporacion" name="boton_registroFotografico_existentes_desincorporacion" value="Subir Imagen" class="button" onClick="subirRegistroFotograficoExistentesDesIncorporacion()"></td>
                    </tr>
                </table>
                
            </div>
        
		<?
	break;
	
	
	case "subirRegistroFotograficoExistentesDesIncorporacion":
		$sql_ingresar = mysql_query("insert into registro_fotografico_existentes_desincorporacion
													(idmovimientos_existentes_desincorporacion,
													imagen,
													descripcion)VALUES
													('".$idmovimientos_existentes_desincorporacion_fotos."',
													'".$nombre_imagen_existentes_desincorporacion."',
													'".$descripcion_foto_existentes_desincorporacion."')");
	break;
	
	
	
	
	case "consultarRegistroFotograficoExistentesDesIncorporacion":


		$sql_consulta = mysql_query("select * from registro_fotografico_existentes_desincorporacion where idmovimientos_existentes_desincorporacion = '".$idmovimientos_existentes_desincorporacion."'")or die(mysql_error());
		?>
        <br />
        <div style="text-align:right"><a href="javascript:;" onclick="document.getElementById('listaFotosExistentesDesIncorporacion').style.display = 'none'"><strong>Cerrar (X)&nbsp;&nbsp;&nbsp;</strong></a></div>
		<table align="center">
        <tr>
		<?
		$i=0;
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
			
            <td>
            	<?
                if($estado == "elaboracion"){
				?>
                <div align="right"><a href="javascript:;" onclick="eliminarRegistroFotograficoExistentesDesIncorporacion('<?=$bus_consulta["idregistro_fotografico_existentes_desincorporacion"]?>', '<?=$idmovimientos_existentes_desincorporacion?>')">Eliminar</a></div>
                <?
                }
				?>
                <table width="100%">
                    <tr>
                        <td style="border:#000000 solid 1px" align="center"><img src="modulos/bienes/imagenes/<?=$bus_consulta["imagen"]?>" width="150" height="150"></td>
                    </tr>
                    <tr>
                        <td><?=$bus_consulta["descripcion"]?></td>
                    </tr>
                </table>
                
            </td>
		<?
		if($i == 4){
			?>
			</tr>
            <tr>
			<?
			$i = 0;
		}else{
			$i++;
		}
		}
		
	break;
	
	
	
	
	
	
	case "cargarRegistroFotograficoExistentesDesIncorporacion":
	
		$tipo = substr($_FILES['foto_registroFotografico_existentes_desincorporacion']['type'], 0, 5);
		$dir = '../imagenes/';
		if ($tipo == 'image') {
		$nombre_imagen = $_FILES['foto_registroFotografico_existentes_desincorporacion']['name'];
			while(file_exists($dir.$nombre_imagen)){
				$partes_img = explode(".",$nombre_imagen);
				$nombre_imagen = $partes_img[0].rand(0,1000000).".".$partes_img[1];
			}
			if (!copy($_FILES['foto_registroFotografico_existentes_desincorporacion']['tmp_name'], $dir.$nombre_imagen)){
				?>
                <script>
				parent.mostrarMensajes("error", "Disculpe la imagen no se pudo ingresar");
				</script>
                <?
			}else{

				$ruta = 'modulos/bienes/imagenes/'.$nombre_imagen;
			}
			
				?>
                
			<script>
            parent.document.getElementById('nombre_imagen_existentes_desincorporacion').value = '<?=$nombre_imagen?>';
			parent.document.getElementById('mostrarImagenDesIncorporacionExistente').innerHTML = "<img src='modulos/bienes/imagenes/<?=$nombre_imagen?>' width = '100' height='120'>";
            </script>
            <?
			
		}else{
			?>
			<script>
			parent.mostrarMensajes("error", "Disculpe el archivo que intenta subir NO es una Imagen");
			</script>
			
			<?
		}
	
	
	
	
		
	break;
	
	
	case "eliminarRegistroFotograficoExistentesDesIncorporacion":
		$sql_eliminar = mysql_query("delete from registro_fotografico_existentes_desincorporacion where idregistro_fotografico_existentes_desincorporacion = '".$idregistro_fotografico_existentes_desincorporacion."'");
	break;
	
	
	


//***************************************************************************************************************************************************************
// MOVIMIENTO DE TRASLADOS DE BIENES
//***************************************************************************************************************************************************************





case "mostrarBienesActualesExistentesTraslado":
			 
			$sql_tipo = mysql_query("select * from tipo_movimiento_bienes where idtipo_movimiento_bienes = '".$idtipo_movimiento."'");
			$bus_tipo = mysql_fetch_array($sql_tipo);
			 
			  $query = "select muebles.idmuebles as idmuebles,
	  					muebles.idmuebles as idcodigo_bien,
						muebles.codigo_bien,
						muebles.estado,
	  					muebles.especificaciones as especificaciones_mueble,
						muebles.idorganizacion as idorganizacion,
						muebles.idnivel_organizacion as idnivel_organizacion,
						organizacion.denominacion as organizacion,
						niveles_organizacionales.codigo as codigo_nivel,
						niveles_organizacionales.denominacion as nivel_organizacional,
						detalle_catalogo_bienes.codigo as codigo_catalogo,
						detalle_catalogo_bienes.denominacion as denominacion_catalogo,
						detalle_catalogo_bienes.iddetalle_catalogo_bienes as idcodigo_catalogo
	   						from 
						muebles,
						organizacion,
						niveles_organizacionales,
						detalle_catalogo_bienes
							where
						organizacion.idorganizacion = muebles.idorganizacion
						and niveles_organizacionales.idniveles_organizacionales = muebles.idnivel_organizacion
						and muebles.idcatalogo_bienes = detalle_catalogo_bienes.iddetalle_catalogo_bienes
						and muebles.idnivel_organizacion = '".$idnivel_organizacional."'
						and (detalle_catalogo_bienes.denominacion like '%".$buscar."%'
						or muebles.codigo_bien like '%".$buscar."%')";

		
		$sql_consulta = mysql_query($query)or die(mysql_error());
		?>
        <table style="margin-left:50px">
        <tr>
        <td><input name="campo_buscar_bienes" type="text" id="campo_buscar_bienes" value="<?=$buscar?>" size="49"></td>
        <td><input type="button" id="boton_buscar_bienes" name="boton_buscar_bienes" class="button" value="Buscar" onclick="mostrarBienesActualesExistentesTraslado(document.getElementById('nivel_organizacional_existente_traslados').value)"></td>
        </tr>
        </table>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="95%">
          <thead>
          <tr>
            <td width="15%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Bien</td>
            <td width="75%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Catalogo</td>
            <td width="10%" align="center" class="Browse" style="font-size:9px">Seleccione</td>
          </tr>
          </thead>
          
         <?
         while($bus_consulta = mysql_fetch_array($sql_consulta)){
			 //valido que el bien no se encuentre seleccionado para ser trasladado
			 $sql_valida_seleccionado = mysql_query("select * from movimientos_existentes_incorporacion 
			 															where idmovimientos_bienes = '".$idmovimiento."'
																		and codigo_bien = '".$bus_consulta["idcodigo_bien"]."'");
			if ($sql_valida_seleccionado){
				$num_registros = mysql_num_rows($sql_valida_seleccionado);
			}else{
				$num_registros = 0;
			}
			if ($num_registros == 0){
				
			 if ($bus_tipo["estado_bien"] != 'activo' and $bus_tipo["estado_bien"] != 'desincorporado'){
				 if ($bus_consulta["estado"] == 'activo'){
			 ?> 
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			 <? }else{ ?>
					<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
			 <? } ?>
                        <td align="" valign="middle" class='Browse'><?=$bus_consulta["codigo_bien"]?></td>
                        <td align="" valign="middle" class='Browse'>(<?=$bus_consulta["codigo_catalogo"]?>)&nbsp;<?=$bus_consulta["denominacion_catalogo"]?></td>
                        
                        <td width="11%" align="center" valign="middle" class='Browse'>
                        <img src="imagenes/validar.png" style="cursor:pointer" onclick="cargarBienExistenteTrasladoDestino('<?=$bus_consulta["idcodigo_bien"]?>', '<?=$bus_consulta["codigo_bien"]?>', '<?=$bus_consulta["idcodigo_catalogo"]?>', '<?="(".$bus_consulta["codigo_catalogo"].") ".$bus_consulta["denominacion_catalogo"]?>', '<?=$bus_consulta["especificaciones_mueble"]?>', '<?=$bus_consulta["idorganizacion"]?>', '<?=$bus_consulta["idnivel_organizacion"]?>')">            </td>
			  	</tr>
          <? } 
		  	if ($bus_tipo["estado_bien"] == 'activo'){
		  		if ($bus_consulta["estado"] == 'activo'){
			 ?> 
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			
                        <td align="" valign="middle" class='Browse'><?=$bus_consulta["codigo_bien"]?></td>
                        <td align="" valign="middle" class='Browse'>(<?=$bus_consulta["codigo_catalogo"]?>)&nbsp;<?=$bus_consulta["denominacion_catalogo"]?></td>
                        
                        <td width="11%" align="center" valign="middle" class='Browse'>
                        <img src="imagenes/validar.png" style="cursor:pointer" onclick="cargarBienExistenteTrasladoDestino('<?=$bus_consulta["idcodigo_bien"]?>', '<?=$bus_consulta["codigo_bien"]?>', '<?=$bus_consulta["idcodigo_catalogo"]?>', '<?="(".$bus_consulta["codigo_catalogo"].") ".$bus_consulta["denominacion_catalogo"]?>', '<?=$bus_consulta["especificaciones_mueble"]?>', '<?=$bus_consulta["idorganizacion"]?>', '<?=$bus_consulta["idnivel_organizacion"]?>')">            </td>
			  	</tr>
                <? } ?>
          <? }
		  if ($bus_tipo["estado_bien"] == 'desincorporado'){
		   		if ($bus_consulta["estado"] == 'desincorporado'){
			 ?> 
					<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
			
                        <td align="" valign="middle" class='Browse'><?=$bus_consulta["codigo_bien"]?></td>
                        <td align="" valign="middle" class='Browse'>(<?=$bus_consulta["codigo_catalogo"]?>)&nbsp;<?=$bus_consulta["denominacion_catalogo"]?></td>
                        
                        <td width="11%" align="center" valign="middle" class='Browse'>
                        <img src="imagenes/validar.png" style="cursor:pointer" onclick="cargarBienExistenteTrasladoDestino('<?=$bus_consulta["idcodigo_bien"]?>', '<?=$bus_consulta["codigo_bien"]?>', '<?=$bus_consulta["idcodigo_catalogo"]?>', '<?="(".$bus_consulta["codigo_catalogo"].") ".$bus_consulta["denominacion_catalogo"]?>', '<?=$bus_consulta["especificaciones_mueble"]?>', '<?=$bus_consulta["idorganizacion"]?>', '<?=$bus_consulta["idnivel_organizacion"]?>')">            </td>
			  	</tr>
               <? } ?>
          <? }
		  	}
          }
		  ?>
          </table>
		<?
	
	break;


	case "cargarBienesSeleccionadosTraslado":
		 
		  $sql_ingresar = mysql_query("insert into movimientos_existentes_desincorporacion(idmovimientos_bienes,
		  																					idtipo_movimiento,
																						  codigo_bien,
																						  codigo_catalogo,
																						  denominacion,
																						  especificaciones,
																						  motivos,
																						  idorganizacion,
																						  idnivel_organizacional,
																						  idorganizacion_destino,
																						  idnivel_organizacional_destino
		  																				)VALUES(
																						'".$idmovimiento."',
																						  '".$idtipo_movimiento_origen."',
																						  '".$idcodigo_bien."',
																						  '".$idcodigo_catalogo."',
																						  '".$denominacion."',
																						  '".$especificaciones."',
																						  '".$descripcion."',
																						  '".$idorganizacion_origen."',
																						  '".$idnivel_organizacional_origen."',
																						  '".$idorganizacion_destino."',
																						  '".$idnivel_organizacional_destino."')");
																						  

			$sql_ingresar = mysql_query("insert into movimientos_existentes_incorporacion(idmovimientos_bienes,
																							idtipo_movimiento,
																						  idorganizacion,
																						  idnivel_organizacional,
																						  codigo_bien,
																						  codigo_catalogo,
																						  denominacion,
																						  especificaciones,
																						  descripcion
		  																				)VALUES(
																						'".$idmovimiento."',
																						  '".$idtipo_movimiento_destino."',
																						  '".$idorganizacion_destino."',
																						  '".$idnivel_organizacional_destino."',
																						  '".$idcodigo_bien."',
																						  '".$idcodigo_catalogo."',
																						  '".$denominacion."',
																						  '".$especificaciones."',
																						  '".$descripcion."')");			
			
			if($sql_ingresar){
				echo "exito";
			}else{
				echo "fallo";
			}
																						
		  
	
	break;



	case "mostrarBienesDestinoTraslado":
			 
			 
			  $query = "select muebles.idmuebles as idmuebles,
					  muebles.idmuebles as idcodigo_bien,
					  muebles.codigo_bien as codigo_del_bien, 
					  muebles.estado,
					  detalle_catalogo_bienes.codigo as codigo_catalogo,
					  detalle_catalogo_bienes.denominacion as denominacion_catalogo,
					  detalle_catalogo_bienes.iddetalle_catalogo_bienes as idcodigo_catalogo,
					  movimientos_existentes_incorporacion.idmovimientos_bienes,
					  movimientos_existentes_incorporacion.idmovimientos_existentes_incorporacion,
					  movimientos_existentes_incorporacion.codigo_bien
	   						from 
						muebles,
						detalle_catalogo_bienes,
						movimientos_existentes_incorporacion
							where
						movimientos_existentes_incorporacion.idmovimientos_bienes = '".$idmovimiento."'
						and muebles.idmuebles = movimientos_existentes_incorporacion.codigo_bien
						and muebles.idcatalogo_bienes = detalle_catalogo_bienes.iddetalle_catalogo_bienes
						";

		//echo $query();
		$sql_consulta = mysql_query($query)or die(mysql_error());
		?>
       
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="95%">
          <thead>
          <tr>
            <td width="15%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Bien</td>
            <td width="75%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Catalogo</td>
            <td width="10%" align="center" class="Browse" style="font-size:9px">Seleccione</td>
          </tr>
          </thead>
          
         <?
         while($bus_consulta = mysql_fetch_array($sql_consulta)){
			 				
			 
				 if ($bus_consulta["estado"] == 'activo'){
			 ?> 
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			 <? }else{ ?>
					<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
			 <? } ?>
                        <td align="" valign="middle" class='Browse'><?=$bus_consulta["codigo_del_bien"]?></td>
                        <td align="" valign="middle" class='Browse'>(<?=$bus_consulta["codigo_catalogo"]?>)&nbsp;<?=$bus_consulta["denominacion_catalogo"]?></td>
                        
                        <td width="11%" align="center" valign="middle" class='Browse'>
                        <img src="imagenes/delete.png" style="cursor:pointer" onclick="EliminarBienExistenteTrasladoDestino('<?=$bus_consulta["idmovimientos_existentes_incorporacion"]?>')">            </td>
			  	</tr>
         	<? 
          }
		  ?>
          </table>
		<?
	
	break;


case "EliminarBienExistenteTrasladoDestino":
	$sql_validar = mysql_query("select * from movimientos_existentes_incorporacion where idmovimientos_existentes_incorporacion = '".$idmovimiento."'");
	$bus_validar = mysql_fetch_array($sql_validar);
	
	$sql_eliminar = mysql_query("delete from movimientos_existentes_desincorporacion where idmovimientos_bienes = '".$bus_validar["idmovimientos_bienes"]."'
																					and codigo_bien = '".$bus_validar["codigo_bien"]."'")or die(mysql_error());
	
	
	$sql_eliminar = mysql_query("delete from movimientos_existentes_incorporacion 
									where idmovimientos_existentes_incorporacion = '".$idmovimiento."'")or die(mysql_error());
	
	// falta eliminar imagenes																				
	$sql_eliminar = mysql_query("delete from registro_fotografico_existentes_incorporacion where idmovimientos_existentes_incorporacion = '".$idmovimiento."'")or die(mysql_error());
	break;

	




	case "listarRegistroFotograficoTraslado":
		
		$sql_movimientos = mysql_query("select * from movimientos_bienes where idmovimientos_bienes = '".$idmovimiento."'");
		$bus_movimientos = mysql_fetch_array($sql_movimientos);
		
		$estado = $bus_movimientos["estado"];
		
		
		$query = "select muebles.idmuebles as idmuebles,
	  					muebles.codigo_bien as codigo_bien,
						movimientos_existentes_incorporacion.idorganizacion as idorganizacion,
						movimientos_existentes_incorporacion.idnivel_organizacional as idnivel_organizacion,
						detalle_catalogo_bienes.codigo as codigo_catalogo,
						detalle_catalogo_bienes.denominacion as denominacion_catalogo,
						detalle_catalogo_bienes.iddetalle_catalogo_bienes as iddetalle_catalogo_bienes,
						movimientos_existentes_incorporacion.idmovimientos_existentes_incorporacion,
						movimientos_existentes_incorporacion.codigo_bien as idcodigo_bien,
						movimientos_existentes_incorporacion.codigo_catalogo as idcodigo_catalogo,
						movimientos_existentes_incorporacion.denominacion,						
						movimientos_existentes_incorporacion.especificaciones,
						organizacion.denominacion as organizacion,
						niveles_organizacionales.denominacion as nivel_organizacional
	   						from 
						movimientos_existentes_incorporacion,
						muebles,
						detalle_catalogo_bienes,
						organizacion,
						niveles_organizacionales
							where
						movimientos_existentes_incorporacion.idmovimientos_bienes = '".$idmovimiento."'	
						and muebles.idmuebles = movimientos_existentes_incorporacion.codigo_bien
						and detalle_catalogo_bienes.iddetalle_catalogo_bienes = movimientos_existentes_incorporacion.codigo_catalogo
						and organizacion.idorganizacion = movimientos_existentes_incorporacion.idorganizacion
						and niveles_organizacionales.idniveles_organizacionales = movimientos_existentes_incorporacion.idnivel_organizacional";
		
		//echo $query;
		
		$sql_consulta = mysql_query($query)or die(mysql_error());
		?>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="90%">
          <thead>
          <tr>
            <td width="9%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Bien</td>
            <td width="69%" align="center" class="Browse" style="font-size:9px">C&oacute;digo del Catalogo</td>
            <td align="center" class="Browse" style="font-size:9px" <? if($estado == "elaboracion"){?>colspan="2"<? }?>>Acciones</td>
          </tr>
          </thead>
          
         <?
         while($bus_consulta = mysql_fetch_array($sql_consulta)){
		 ?> 
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td align="" valign="middle" class='Browse'><?=$bus_consulta["codigo_bien"]?></td>
            <td align="" valign="middle" class='Browse'>(<?=$bus_consulta["codigo_catalogo"]?>)&nbsp;<?=$bus_consulta["denominacion_catalogo"]?></td>
            <?
            if($estado == "elaboracion"){
			?>
            <td width="4%" align="center" valign="middle" class='Browse'><a href="javascript:;" onclick="document.getElementById('agregar_foto_traslado').style.display = 'block', document.getElementById('idmovimientos_existentes_traslado_fotos').value = '<?=$bus_consulta["idmovimientos_existentes_incorporacion"]?>'">Agregar</a></td>
            <?
            }
			?>
            <td width="4%" align="center" valign="middle" class='Browse'><a href="javascript:;" onclick="consultarRegistroFotograficoTraslado('<?=$bus_consulta["idmovimientos_existentes_incorporacion"]?>', '<?=$estado?>')">Ver</a></td>
                    
          </tr>
          
          <?
          }
		  ?>
          </table>
          
          <div id="agregar_foto_traslado" style="display:none; width:300px; height:400px">	
                <br /><div align="right"><a href="javascript:;" onclick="document.getElementById('agregar_foto_traslado').style.display = 'none'"><strong>Cerrar (X)</strong></a></div>
                <div id="mostrarImagenTraslado" align="center"></div>
                <table align="center" style="background-color:#EAEAEA; border:#000000 solid 1px">
                    <tr>
                        <td align='right'>Foto:</td>
                        <td>
                        <form method="post" id="formImagen_existentes_traslado" name="formImagen_existentes_traslado" enctype="multipart/form-data" action="modulos/bienes/lib/movimientos_ajax.php" target="iframeUpload">
                          <input type="hidden" name="idmovimientos_existentes_traslado_fotos" id="idmovimientos_existentes_traslado_fotos">
                          <input type="file" name="foto_registroFotografico_existentes_traslado" id="foto_registroFotografico_existentes_traslado" size="20" align="left" onChange="document.getElementById('formImagen_existentes_traslado').submit()">
                          <!--<input type="submit" name="boton_subir" id="boton_subir" value="Subir">-->
                          <input type="hidden" id="ejecutar" name="ejecutar" value="cargarRegistroFotograficoTraslado">
                        </form>
                        <iframe id="iframeUpload" name="iframeUpload" style="display:none"></iframe>
                        <input type="hidden" id="nombre_imagen_existentes_traslado" name="nombre_imagen_existentes_traslado">
                        </td>
                    </tr>
                    <tr>
                    <td>Descripcion: </td>
                    <td><textarea name="descripcion_foto_existentes_traslado" id="descripcion_foto_existentes_traslado" rows="3" cols="30"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><input type="button" id="boton_registroFotografico_existentes_traslado" name="boton_registroFotografico_existentes_traslado" value="Subir Imagen" class="button" onClick="subirRegistroFotograficoTraslado()"></td>
                    </tr>
                </table>
                
            </div>
        
		<?
	break;
	
	
	case "subirRegistroFotograficoTraslado":
		$sql_ingresar = mysql_query("insert into registro_fotografico_existentes_incorporacion
													(idmovimientos_existentes_incorporacion,
													imagen,
													descripcion)VALUES
													('".$idmovimientos_existentes_traslado_fotos."',
													'".$nombre_imagen_existentes_traslado."',
													'".$descripcion_foto_existentes_traslado."')");
	break;
	
	
	
	
	case "consultarRegistroFotograficoTraslado":


		$sql_consulta = mysql_query("select * from registro_fotografico_existentes_incorporacion where idmovimientos_existentes_incorporacion = '".$idmovimientos_existentes_incorporacion."'")or die(mysql_error());
		?>
        <br />
        <div style="text-align:right"><a href="javascript:;" onclick="document.getElementById('listaFotosExistentesTraslados').style.display = 'none'"><strong>Cerrar (X)&nbsp;&nbsp;&nbsp;</strong></a></div>
		<table align="center">
        <tr>
		<?
		$i=0;
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
			
            <td>
            	<?
                if($estado == "elaboracion"){
				?>
                <div align="right"><a href="javascript:;" onclick="eliminarRegistroFotograficoTraslado('<?=$bus_consulta["idregistro_fotografico_existentes_incorporacion"]?>', '<?=$idmovimientos_existentes_incorporacion?>')">Eliminar</a></div>
                <?
                }
				?>
                <table width="100%">
                    <tr>
                        <td style="border:#000000 solid 1px" align="center"><img src="modulos/bienes/imagenes/<?=$bus_consulta["imagen"]?>" width="150" height="150"></td>
                    </tr>
                    <tr>
                        <td><?=$bus_consulta["descripcion"]?></td>
                    </tr>
                </table>
                
            </td>
		<?
		if($i == 4){
			?>
			</tr>
            <tr>
			<?
			$i = 0;
		}else{
			$i++;
		}
		}
		
	break;
	
	
	
	
	
	
	case "cargarRegistroFotograficoTraslado":
	
		$tipo = substr($_FILES['foto_registroFotografico_existentes_traslado']['type'], 0, 5);
		$dir = '../imagenes/';
		if ($tipo == 'image') {
		$nombre_imagen = $_FILES['foto_registroFotografico_existentes_traslado']['name'];
			while(file_exists($dir.$nombre_imagen)){
				$partes_img = explode(".",$nombre_imagen);
				$nombre_imagen = $partes_img[0].rand(0,1000000).".".$partes_img[1];
			}
			if (!copy($_FILES['foto_registroFotografico_existentes_traslado']['tmp_name'], $dir.$nombre_imagen)){
				?>
                <script>
				parent.mostrarMensajes("error", "Disculpe la imagen no se pudo ingresar");
				</script>
                <?
			}else{

				$ruta = 'modulos/bienes/imagenes/'.$nombre_imagen;
			}
			
				?>
                
			<script>
            parent.document.getElementById('nombre_imagen_existentes_traslado').value = '<?=$nombre_imagen?>';
			parent.document.getElementById('mostrarImagenTraslado').innerHTML = "<img src='modulos/bienes/imagenes/<?=$nombre_imagen?>' width = '100' height='120'>";
            </script>
            <?
			
		}else{
			?>
			<script>
			parent.mostrarMensajes("error", "Disculpe el archivo que intenta subir NO es una Imagen");
			</script>
			
			<?
		}
	
	
	
	
		
	break;
	
	
	case "eliminarRegistroFotograficoTraslado":
		$sql_eliminar = mysql_query("delete from registro_fotografico_existentes_incorporacion where idregistro_fotografico_existentes_incorporacion = '".$idregistro_fotografico_existentes_incorporacion."'");
	break;



	
	
	
	
//***************************************************************************************************************************************************************
// PROCESAR MOVIMIENTOS
//***************************************************************************************************************************************************************
	
	
	
	case "procesarMovimiento":
	
		//******************************************************************************************************************************
		// PROCESAR MOVIMIENTO BIENES NUEVOS
		//******************************************************************************************************************************
		
		if ($accion_tipo_movimiento == 'nuevo'){

            $sql_configuracion_bienes = mysql_query("select * from configuracion_bienes");
            $bus_configuracion_bienes = mysql_fetch_array($sql_configuracion_bienes);
            $nro_movimiento = $bus_configuracion_bienes["nro_movimiento"];



            $sql_actualizar = mysql_query("update movimientos_bienes
			    								set nro_movimiento = 'MOV-".$_SESSION["anio_fiscal"]."-".$nro_movimiento."',
			    								estado = 'procesado'
			    								where idmovimientos_bienes = '".$idmovimiento."'");

            $sql_actualizar = mysql_query("update configuracion_bienes set nro_movimiento = nro_movimiento+1");

            echo "MOV-".$_SESSION["anio_fiscal"]."-".$nro_movimiento;

            $sql_movimiento = mysql_query("select * from movimientos_bienes where idmovimientos_bienes = '".$idmovimiento."'");
            $bus_movimiento = mysql_fetch_array($sql_movimiento);
            $idtipo_movimiento = $bus_movimiento["idtipo_movimiento"];
            $nro_documento = $bus_movimiento["nro_documento_bien_nuevo"];
            $idbenficiario = $bus_movimiento["proveedor_bien_nuevo"];
            $nro_factura = $bus_movimiento["nro_factura_bien_nuevo"];
            $fecha_factura = $bus_movimiento["fecha_factura_bien_nuevo"];
            $fecha_compra = $bus_movimiento["fecha_documento_bien_nuevo"];

			$sql_lista_bienes = mysql_query("select * from movimientos_bienes_nuevos 
																where idmovimientos_bienes = '".$idmovimiento."'") or die("aquiii1".mysql_error());
				
			while ($bus_bienes = mysql_fetch_array($sql_lista_bienes)){

			   // ASIGNO EL CODIGO DEL BIEN
                $sql_inventario_materia = mysql_query("select * from relacion_contadores")or die(mysql_error());
                $bus_inventario_materia = mysql_fetch_array($sql_inventario_materia);
                if ($bus_inventario_materia["contador_bienes_muebles"] > 0){
                    $numero = $bus_inventario_materia["contador_bienes_muebles"];
                    $codigo_con_ceros = str_pad($bus_inventario_materia["contador_bienes_muebles"], 6, "0", STR_PAD_LEFT);
                }else{
                    $numero = 1;
                    $codigo_con_ceros = str_pad($numero, 6, "0", STR_PAD_LEFT);
                }


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
                        $actualiza = mysql_query("update relacion_contadores set contador_bienes_muebles = contador_bienes_muebles+1");
                        $entro = 1;
                    }
                }

			   $sql_ingresar_mueble = mysql_query("insert into muebles
			    										(idorganizacion,
			    										idnivel_organizacion,
			    										idtipo_movimiento,
			    										idcatalogo_bienes,
			    										codigo_bien,
			    										especificaciones,
			    										marca,
			    										modelo,
			    										idtipo,
			    										serial,
			    										accesorios,
			    										numero_documento_compra,
			    										proveedor,
			    										nro_factura,
			    										fecha_factura,
			    										fecha_compra,
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
			    										fechayhora)
			    											VALUES(
			    											'".$bus_bienes["idorganizacion"]."',
			    											'".$bus_bienes["nivel_organizacional"]."',
			    											'".$idtipo_movimiento."',
			    											'".$bus_bienes["codigo_catalogo"]."',
			    											'".$codigo_bien."',
			    											'".$bus_bienes["especificaciones"]."',
			    											'".$bus_bienes["marca"]."',
			    											'".$bus_bienes["modelo"]."',
			    											'".$bus_bienes["tipo"]."',
			    											'".$bus_bienes["seriales"]."',
			    											'".$bus_bienes["accesorios"]."',
			    											'".$nro_documento."',
			    											'".$idbenficiario."',
			    											'".$nro_factura."',
			    											'".$fecha_factura."',
			    											'".$fecha_compra."',
			    											'".$bus_bienes["costo"]."',
			    											'".$bus_bienes["valor_residual"]."',
			    											'".$bus_bienes["vida_util"]."',
			    											'".$bus_bienes["mejoras"]."',
			    											'".$bus_bienes["costo_ajustado"]."',
			    											'".$bus_bienes["depreciacion_anual"]."',
			    											'".$bus_bienes["depreciacion_acumulada"]."',
			    											'".$bus_bienes["asegurado"]."',
			    											'".$bus_bienes["aseguradora"]."',
			    											'".$bus_bienes["nro_poliza"]."',
			    											'".$bus_bienes["fecha_vencimiento"]."',
			    											'".$bus_bienes["monto_poliza"]."',
			    											'".$bus_bienes["monto_asegurado"]."',
			    											'registrado',
			    											'a',
			    											'".$login."',
			    											'".$fh."')");

                    $idmueble = mysql_insert_id();

                    $sql_actualizar = mysql_query("update movimientos_bienes_nuevos
			    								set codigo_bien = '".$codigo_bien."'
			    								where idmovimientos_bienes_nuevos='".$bus_bienes["idmovimientos_bienes_nuevos"]."'");



                    $sql_registro_fotografico = mysql_query("select * from registro_fotografico_bienes_nuevos
                                                                            where idmovimientos_bienes_nuevos='".$bus_bienes["idmovimientos_bienes_nuevos"]."' ");

                    while($bus_fotos = mysql_fetch_array($sql_registro_fotografico)){
                        $sql_inserta_imagen = mysql_query("insert into registro_fotografico_bienes_muebles
                                                                        (idmuebles,
			    															nombre_imagen,
			    															principal,
			    															descripcion)VALUES('".$idmueble."',
			    																				'".$bus_fotos["imagen"]."',
			    																				'0',
			    																				'".$bus_fotos["descripcion"]."')");

                }
            }
		}
		
		//******************************************************************************************************************************
		// PROCESAR MOVIMIENTO EXISTENTE INCORPORACION
		//******************************************************************************************************************************
		
		if ($accion_tipo_movimiento == 'existente_incorporacion'){
			$sql_configuracion_bienes = mysql_query("select * from configuracion_bienes");
			$bus_configuracion_bienes = mysql_fetch_array($sql_configuracion_bienes);
			$nro_movimiento = $bus_configuracion_bienes["nro_movimiento"];
			
			
			
			$sql_actualizar = mysql_query("update movimientos_bienes 
											set nro_movimiento = 'MOV-".$_SESSION["anio_fiscal"]."-".$nro_movimiento."',
											estado = 'procesado' 
											where idmovimientos_bienes = '".$idmovimiento."'");
		
			$sql_actualizar = mysql_query("update configuracion_bienes set nro_movimiento = nro_movimiento+1");
			
			$sql_movimiento = mysql_query("select * from movimientos_bienes where idmovimientos_bienes = '".$idmovimiento."'");
			$field_movimiento = mysql_fetch_array($sql_movimiento);
		
			echo "MOV-".$_SESSION["anio_fiscal"]."-".$nro_movimiento;
			
			
			$sql_bienes_movimiento = mysql_query("select * from movimientos_existentes_incorporacion where idmovimientos_bienes = '".$idmovimiento."'") or die("aquiii2".mysql_error());
				
			while ($bus_bienes_existentes = mysql_fetch_array($sql_bienes_movimiento)){
				$especificaciones = $bus_bienes_existentes["especificaciones"]." / ".$field_movimiento["fecha_movimiento"]." - ".$bus_bienes_existentes["descripcion"];
				
				$sql_valida_depreciacion = mysql_query("select * from muebles where idmuebles = '".$bus_bienes_existentes["codigo_bien"]."'");
				$field_valida_depreciacion = mysql_fetch_array($sql_valida_depreciacion);
				$depreciacion_anual = ($field_valida_depreciacion["costo_ajustado"] - $field_valida_depreciacion["valor_residual"]) / $field_valida_depreciacion["vida_util"];
				$partes = explode("-", $field_valida_depreciacion["fecha_compra"]);
				$fecha_compra = $partes[2]."/".$partes[1]."/".$partes[0];
				$anios = calcularAnio($fecha_compra);
				$depreciacion_acumulada = $depreciacion_anual * $anios;
				
				$sql_actualiza = mysql_query("update muebles set
														mejoras = mejoras + '".$bus_bienes_existentes["mejoras"]."',
														costo_ajustado = costo_ajustado + '".$bus_bienes_existentes["mejoras"]."',
														depreciacion_anual = '".$depreciacion_anual."',
														depreciacion_acumulada = '".$depreciacion_acumulada."',
														especificaciones = '".$especificaciones."',
														estado = 'activo'
														where idmuebles = '".$bus_bienes_existentes["codigo_bien"]."'")or die("actualiza ".mysql_error());
			
				$sql_registro_fotografico = mysql_query("select * from registro_fotografico_existentes_incorporacion
												where idmovimientos_existentes_incorporacion='".$bus_bienes_existentes["idmovimientos_existentes_incorporacion"]."' ");
						
				while($bus_fotos = mysql_fetch_array($sql_registro_fotografico)){
					$sql_inserta_imagen = mysql_query("insert into registro_fotografico_bienes_muebles
																(idmuebles,
																nombre_imagen,
																principal,
																descripcion)VALUES('".$bus_bienes_existentes["codigo_bien"]."',
																					'".$bus_fotos["imagen"]."',
																					'0',
																					'".$bus_fotos["descripcion"]."')");
					
				}
			}
		}
		
		//******************************************************************************************************************************
		// PROCESAR MOVIMIENTO EXISTENTE DESINCORPORACION
		//******************************************************************************************************************************
		
		
		if ($accion_tipo_movimiento == 'existente_desincorporacion'){
			$sql_configuracion_bienes = mysql_query("select * from configuracion_bienes");
			$bus_configuracion_bienes = mysql_fetch_array($sql_configuracion_bienes);
			$nro_movimiento = $bus_configuracion_bienes["nro_movimiento"];
			
			
			
			$sql_actualizar = mysql_query("update movimientos_bienes 
											set nro_movimiento = 'MOV-".$_SESSION["anio_fiscal"]."-".$nro_movimiento."',
											estado = 'procesado' 
											where idmovimientos_bienes = '".$idmovimiento."'");
		
			$sql_actualizar = mysql_query("update configuracion_bienes set nro_movimiento = nro_movimiento+1");
			
			$sql_movimiento = mysql_query("select * from movimientos_bienes where idmovimientos_bienes = '".$idmovimiento."'");
			$field_movimiento = mysql_fetch_array($sql_movimiento);
		
			echo "MOV-".$_SESSION["anio_fiscal"]."-".$nro_movimiento;
			
			
			$sql_bienes_movimiento = mysql_query("select * from movimientos_existentes_desincorporacion where idmovimientos_bienes = '".$idmovimiento."'") or die("aquiii2".mysql_error());
				
			while ($bus_bienes_existentes = mysql_fetch_array($sql_bienes_movimiento)){
				$especificaciones = $bus_bienes_existentes["especificaciones"]." / ".$field_movimiento["fecha_movimiento"]." - ".$bus_bienes_existentes["descripcion"];
				
				if ($bus_bienes_existentes["idorganizacion_destino"] != 0 and $bus_bienes_existentes["idnivel_organizacional_destino"] != 0){
					$idorganizacion = $bus_bienes_existentes["idorganizacion_destino"];
					$idnivel_organizacional = $bus_bienes_existentes["idnivel_organizacional_destino"];
				}else{
					$idorganizacion = $bus_bienes_existentes["idorganizacion"];
					$idnivel_organizacional = $bus_bienes_existentes["idnivel_organizacional"];
				}
				$sql_actualiza = mysql_query("update muebles set
														especificaciones = '".$especificaciones."',
														estado = 'desincorporado',
														idorganizacion = '".$idorganizacion."',
														idnivel_organizacion = '".$idnivel_organizacional."'
														where idmuebles = '".$bus_bienes_existentes["codigo_bien"]."'")or die("actualiza ".mysql_error());
			
				$sql_registro_fotografico = mysql_query("select * from registro_fotografico_existentes_desincorporacion
												where idmovimientos_existentes_desincorporacion='".$bus_bienes_existentes["idmovimientos_existentes_desincorporacion"]."' ");
						
				while($bus_fotos = mysql_fetch_array($sql_registro_fotografico)){
					$sql_inserta_imagen = mysql_query("insert into registro_fotografico_bienes_muebles
																(idmuebles,
																nombre_imagen,
																principal,
																descripcion)VALUES('".$bus_bienes_existentes["codigo_bien"]."',
																					'".$bus_fotos["imagen"]."',
																					'0',
																					'".$bus_fotos["descripcion"]."')");
					
				}
			}
		}
		
	
	
	//******************************************************************************************************************************
	// PROCESAR MOVIMIENTO TRASLADOS
	//******************************************************************************************************************************
		
		
		if ($accion_tipo_movimiento == 'ambos'){
			$sql_configuracion_bienes = mysql_query("select * from configuracion_bienes");
			$bus_configuracion_bienes = mysql_fetch_array($sql_configuracion_bienes);
			$nro_movimiento = $bus_configuracion_bienes["nro_movimiento"];
			
			
			
			$sql_actualizar = mysql_query("update movimientos_bienes 
											set nro_movimiento = 'MOV-".$_SESSION["anio_fiscal"]."-".$nro_movimiento."',
											estado = 'procesado' 
											where idmovimientos_bienes = '".$idmovimiento."'");
		
			$sql_actualizar = mysql_query("update configuracion_bienes set nro_movimiento = nro_movimiento+1");
			
			$sql_movimiento = mysql_query("select * from movimientos_bienes where idmovimientos_bienes = '".$idmovimiento."'");
			$field_movimiento = mysql_fetch_array($sql_movimiento);
		
			echo "MOV-".$_SESSION["anio_fiscal"]."-".$nro_movimiento;
			
			//REGISTRO EL MOVIMIENTO DE DESINCOPRORACION PARA TRASLADAR
			/*
			$sql_bienes_movimiento = mysql_query("select * from movimientos_existentes_desincorporacion where idmovimientos_bienes = '".$idmovimiento."'") or die("aquiii2".mysql_error());
				
			while ($bus_bienes_existentes = mysql_fetch_array($sql_bienes_movimiento)){
				$sql_tipo_movimiento = mysql_query("select * from tipo_movimiento_bienes 
																where idtipo_movimiento_bienes = '".$bus_bienes_existentes["idtipo_movimiento"]."'");
				$bus_tipo = mysql_fetch_array($sql_tipo_movimiento);
				$sql_bien_mueble = mysql_query("select * from muebles where idmuebles = '".$bus_bienes_existentes["codigo_bien"]."'");
				$bus_bien_mueble = mysql_fetch_array($sql_bien_mueble);
				$especificaciones = $bus_bien_mueble["especificaciones"]." / ".$field_movimiento["fecha_movimiento"]." - ".$bus_tipo["codigo"]." ".$bus_tipo["denominacion"];
				$sql_actualiza = mysql_query("update muebles set
														especificaciones = '".$especificaciones."'
														where idmuebles = '".$bus_bienes_existentes["codigo_bien"]."'")or die("actualiza desincorporacion traslado ".mysql_error());
				
			}
			*/
			
			//REGISTRO EL MOVIMIENTO DE INCOPRORACION PARA TRASLADAR
			
			$sql_bienes_movimiento = mysql_query("select * from movimientos_existentes_incorporacion where idmovimientos_bienes = '".$idmovimiento."'") or die("aquiii2".mysql_error());
				
			while ($bus_bienes_existentes = mysql_fetch_array($sql_bienes_movimiento)){
				/*$sql_tipo_movimiento = mysql_query("select * from tipo_movimiento_bienes 
																where idtipo_movimiento_bienes = '".$bus_bienes_existentes["idtipo_movimiento"]."'");
				$bus_tipo = mysql_fetch_array($sql_tipo_movimiento);
				$sql_bien_mueble = mysql_query("select * from muebles where idmuebles = '".$bus_bienes_existentes["codigo_bien"]."'");
				$bus_bien_mueble = mysql_fetch_array($sql_bien_mueble);	
															
				$especificaciones = $bus_bien_mueble["especificaciones"]." / ".$field_movimiento["fecha_movimiento"]." - ".$bus_tipo["codigo"]." ".$bus_tipo["denominacion"];
				
				
				especificaciones = '".$especificaciones."',
				*/
				$sql_actualiza = mysql_query("update muebles set
														idorganizacion = '".$bus_bienes_existentes["idorganizacion"]."',
														idnivel_organizacion = '".$bus_bienes_existentes["idnivel_organizacional"]."',
														estado = 'activo'
														where idmuebles = '".$bus_bienes_existentes["codigo_bien"]."'")or die("actualiza ".mysql_error());
			
				$sql_registro_fotografico = mysql_query("select * from registro_fotografico_existentes_incorporacion
												where idmovimientos_existentes_incorporacion='".$bus_bienes_existentes["idmovimientos_existentes_incorporacion"]."' ");
						
				while($bus_fotos = mysql_fetch_array($sql_registro_fotografico)){
					$sql_inserta_imagen = mysql_query("insert into registro_fotografico_bienes_muebles
																(idmuebles,
																nombre_imagen,
																principal,
																descripcion)VALUES('".$bus_bienes_existentes["codigo_bien"]."',
																					'".$bus_fotos["imagen"]."',
																					'0',
																					'".$bus_fotos["descripcion"]."')");
					
				}
			}
				
		}
	
	
	
	break;
	
	
	
	
	case "consultarDatosBasicos":
	
		$sql_consulta = mysql_query("select * from movimientos_bienes where idmovimientos_bienes = '".$idmovimiento."'");
		$bus_consulta = mysql_fetch_array($sql_consulta);
		
		echo $bus_consulta["nro_movimiento"]."|.|".
			$bus_consulta["fecha_movimiento"]."|.|".
			$bus_consulta["afecta"]."|.|".
			$bus_consulta["tipo"]."|.|".
			$bus_consulta["idtipo_movimiento"]."|.|".
			$bus_consulta["nro_documento"]."|.|".
			$bus_consulta["fecha_documento"]."|.|".
			$bus_consulta["justificacion"]."|.|".
			$bus_consulta["estado"]."|.|".
			$bus_consulta["nro_documento_bien_nuevo"]."|.|".
			$bus_consulta["fecha_documento_bien_nuevo"]."|.|".
			$bus_consulta["proveedor_bien_nuevo"]."|.|".
			$bus_consulta["nro_factura_bien_nuevo"]."|.|".
			$bus_consulta["fecha_factura_bien_nuevo"];
	break;
	
	
	
	case "anularMovimiento":	
		$sql_anular = mysql_query("update movimientos_bienes set estado = 'anulado' where idmovimientos_bienes = '".$idmovimiento."'");
	break;
	
	
	
	case "consultarTipoDetalle":
	
		$sql_tipo_detalle = mysql_query("select * from tipo_detalle where iddetalle = '".$iddetalle."'");
		?>
		<select name="tipo_bienes_nuevos" id="tipo_bienes_nuevos">
        <option value="0">.:: Seleccione ::.</option>
		<?
		
		while($bus_tipo_detalle = mysql_fetch_array($sql_tipo_detalle)){
			?>
			<option value="<?=$bus_tipo_detalle["idtipo_detalle"]?>"><?=$bus_tipo_detalle["tipo"]?></option>
			<?	
		}
		?>
		</select>
		<?
	
	break;
	
	
	
	
	case "procesarRetornoBien":
	
		$sql_procesar_retorno = mysql_query("update movimientos_existentes_incorporacion
														set retorno_automatico = 'no',
														fecha_retorno = '',
														usuario_retorno = '".$login."',
														fecha_retornado = '".$fh."'
														where
														idmovimientos_existentes_incorporacion = '".$id."'");
	
	break;
	
	
	case "reprocesarFechaRetorno":
		$sql_procesar_retorno = mysql_query("update movimientos_existentes_incorporacion
													set fecha_retorno = '".$nueva_fecha."',
													usuario_cambio_fecha = '".$login."',
													fecha_cambio_fecha = '".$fh."'
													where
													idmovimientos_existentes_incorporacion = '".$id."'")or die(mysql_error());
	
	break;
	
	
	
	
	
	
	
	
	
	
	
	
	
	case "calcularDepreciacionAcumulada":
		$partes = explode("-", $fecha_compra);
		$fecha_compra = $partes[2]."/".$partes[1]."/".$partes[0];
		$anios = calcularAnio($fecha_compra);
		$depreciacion_acumulada = $depreciacion_anual * $anios;
		echo $depreciacion_acumulada;
	break;
	
	
	
	
	
	
	
	
	
	
	
	
}

?>