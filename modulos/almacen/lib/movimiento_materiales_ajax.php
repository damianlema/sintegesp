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





switch($ejecutar){

	case "consultarTipoConceptoMovimiento":
		?>
		<select name="tipo_concepto_movimiento" id="tipo_concepto_movimiento">
		
		<?
		if($tipo != "salida"){
			if($tipo == "entrada"){
				$i = 1;
			}else{
				$i = 3;
			}
			if($tipo == "traslado"){
				$i = 4;
			}
			?> <option value="0">.:: Seleccione ::.</option> <?
			$sql_consulta = mysql_query("select * from tipo_movimiento_almacen where afecta = '".$i."'");
			while($bus_consulta = mysql_fetch_array($sql_consulta)){
				?>
				<option value="<?=$bus_consulta["id_tipo_movimiento_almacen"]?>" 
				onClick="document.getElementById('accion_tipo_concepto').value = '<?=$bus_consulta["documento_origen"]?>', consultarMostrar('<?=$tipo?>')"><?=$bus_consulta["codigo"]." - ".$bus_consulta["descripcion"]?></option>
				<?
			}
		}else{
			?>
        	<option value="0">.:: Los conceptos de Salida los Selecciona al Momento de Registrar los Materiales ::.</option>
		</select>
		<?
		}
	break;
	
	case "consultarDocumentoTransaccion":
		if($tipo == "entrada"){ ?>
			<select name="documento_transaccion" id="documento_transaccion">
                <option value="0">.:: Seleccione ::.</option>
                <option value="factura" >Factura</option>
                <option value="presupuesto" >Presupuesto/Cotizaci&oacute;n</option>
                <option value="notaEntrega" >Nota de Entrega</option>
                <option value="actaDonacion" >Acta de Donaci&oacute;n</option>
            </select>
            
		<? }
		if($tipo == "salida"){ ?>
			<select name="documento_transaccion" id="documento_transaccion">
                <option value="0">.:: Seleccione ::.</option>
                <option value="requisicion" >Requisici&oacute;n</option>
                <option value="memorandum" >Memorandum</option>
                <option value="ordeInterna" >Orden Interna</option>
            </select>
		<? } 
		if($tipo == "inicial"){ ?>
			<select name="documento_transaccion" id="documento_transaccion">
                <option value="0">.:: Seleccione ::.</option>
                <option value="ajuste" >Acta de Ajuste</option>
                <option value="memorandum" >Memorandum</option>
                <option value="ordeInterna" >Orden Interna</option>
            </select>
		<? }
	break;
	
	case "ingresarDatosBasicos":
		$sql_ingresar = mysql_query("insert into movimiento_materia_almacen
															( afecta,
															  idtipo_movimiento_almacen,
															  idalmacen,
															  justificacion,
															  idniveles_organizacionales,
															  idproveedor,
															  nombre_proveedor,
															  idorden_compra_servicio,
															  tipo_documento_transaccion,
															  numero_documento_transaccion,
															  fecha_documento_transaccion,
															  elaborado_por,
															  cedula_elaborado_por,
															  estado,
															  fechayhora,
															  estacion,
															  usuario)VALUES('".$afecta."',
																					'".$tipo_concepto_movimiento."',
																					'".$almacen."',
																					'".$justificacion."',
																					'".$idniveles_organizacionales."',
																					'".$idbeneficiario."',
																					'".$proveedor."',
																					'".$idorden_compra_servicio."',
																					'".$documento_transaccion."',
																					'".$numero_documento_transaccion."',
																					'".$fecha_documento_transaccion."',
																					'".$ordenado_por."',
																					'".$cedula_ordenado."',
																					'elaboracion',
																					'".$fh."',
																					'".$pc."',
																					'".$login."')")or die (mysql_error());
		if($sql_ingresar){
			echo "exito|.|".mysql_insert_id();
		}else{
			echo "fallo";
		}
	break;
	
	case "actualizarDatosBasicos":
		$sql_actualizar = mysql_query("update movimiento_materia_almacen set  justificacion = '".$justificacion."',
															  idniveles_organizacionales = '".$idniveles_organizacionales."',
															  idproveedor = '".$idbeneficiario."',
															  nombre_proveedor = '".$proveedor."',
															  idorden_compra_servicio = '".$idorden_compra_servicio."',
															  tipo_documento_transaccion = '".$documento_transaccion."',
															  numero_documento_transaccion = '".$numero_documento_transaccion."',
															  fecha_documento_transaccion = '".$fecha_documento_transaccion."',
															  elaborado_por = '".$ordenado_por."',
															  cedula_elaborado_por = '".$cedula_ordenado."'
										where idmovimiento_materia_almacen = '".$idmovimiento."'")or die (mysql_error());
		
			echo "exito";		
	break;
	
	
	
 case "ingresarMaterialAjuste":
	$validar_cantidad = mysql_query("select * from relacion_materia_movimiento
								  				where idinventario_materia = '".$idinventario_materia."'
												and idmovimiento_materia_almacen = '".$idmovimiento."'")or die("aqui error ".mysql_error());
	if (mysql_num_rows($validar_cantidad)){
			$existe_cantidad = mysql_num_rows($validar_cantidad)or die(mysql_error());
		}else{
			$existe_cantidad = 0;
		}
	if ($existe_cantidad == 0){
			$ingresa_materia_movimiento = mysql_query("insert into relacion_materia_movimiento
																			(idinventario_materia,
																			 idmovimiento_materia_almacen,
																			 cantidad_movimiento)
																			VALUES
																			('".$idinventario_materia."',
																			 '".$idmovimiento."',
																			 '".$cantidad_ajuste."')")or die("error ingresar ajustar".mysql_error());
			//OJO ESTO LO DEBE HACER CUANDO SE PROCESE EL MOVIMIENTO
			/*$result=mysql_query("update inventario_materia set 	existencia_actual	= existencia_actual + '".$cantidad_ajuste."',
																inventario_inicial	=  inventario_inicial + '".$cantidad_ajuste."'
											where idinventario_materia		= '".$idinventario_materia."'")or die(mysql_error());	*/
			echo "exito";
	}else{
			echo "existe";	
	}	
 break;
	
case "listaMaterialesMovimiento":
  $sql_listaMateria = mysql_query("select 	inventario_materia.idinventario_materia, 
								 			inventario_materia.codigo, 
											inventario_materia.descripcion,
											relacion_materia_movimiento.cantidad_movimiento,
											relacion_materia_movimiento.idrelacion_materia_movimiento
										from inventario_materia, relacion_materia_movimiento 
										where inventario_materia.idinventario_materia = relacion_materia_movimiento.idinventario_materia and
											relacion_materia_movimiento.idmovimiento_materia_almacen = '".$idmovimiento."'")or die("error ".mysql_error());

   ?>
     <table class="Browse" cellpadding="0" cellspacing="0" width="95%" border="0" align="center">
      <thead>
        <tr>
          <td width="10%" align="center" class="Browse">C&oacute;digo</td>	
          <td width="75%" align="center" class="Browse">Descripci&oacute;n</td>
          <td width="10%" align="center" class="Browse">Cantidad Ajustada</td>
          <td width="5%" align="center" class="Browse">Acci&oacute;n</td>
        </tr>
     </thead>         
    

      <?
      while($bus_listaMateria = mysql_fetch_array($sql_listaMateria)){ ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			<td align="left" class='Browse' style="font-weight:normal" width="10%"><?=$bus_listaMateria["codigo"]?></td>
            <td align="left" class='Browse' style="font-weight:normal" width="75%"><?=$bus_listaMateria["descripcion"]?></td>
            <td align="right" class='Browse' style="font-weight:normal" width="10%"><?=number_format($bus_listaMateria["cantidad_movimiento"],2,",",".")?></td>
        	<td align="center" class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="modificarMateriaMovimiento('<?=$bus_listaMateria["idrelacion_materia_movimiento"]?>')">&nbsp;<img src="imagenes/delete.png" style="cursor:pointer" onClick="eliminarMateriaMovimiento('<?=$bus_listaMateria["idrelacion_materia_movimiento"]?>')"></td>
	      </tr>
	<? } ?>
      
     </table>
     <?
 break;  



 case "ingresarSerial":
	$validar_cantidad = mysql_query("select * from relacion_serial_movimiento_materia 
								  				where idmovimiento_materia_almacen = '".$idmovimiento."' and
													idinventario_materia = '".$idinventario_materia."'")or die("aqui error ".mysql_error());
	if (mysql_num_rows($validar_cantidad)){
			$existe_cantidad = mysql_num_rows($validar_cantidad)or die(mysql_error());
		}else{
			$existe_cantidad = 0;
		}
	if ($existe_cantidad < $cantidadmovimiento){
		$validar_existe = mysql_query("select * from relacion_serial_movimiento_materia 
								  				where idmovimiento_materia_almacen = '".$idmovimiento."' and
													idinventario_materia = '".$idinventario_materia."'
													and serial = '".$serial."'")or die("aqui error ".mysql_error());
		$existe_serial = mysql_num_rows($validar_existe);
		if ($existe_serial == 0){
			$validar_existe1 = mysql_query("select * from relacion_serial_materia 
								  				where idinventario_materia = '".$idinventario_materia."'
													and serial = '".$serial."'")or die("aqui error ".mysql_error());
			$existe_serial1 = mysql_num_rows($validar_existe1);
			if ($existe_serial1 == 0){
				$ingresa_serial = mysql_query("insert into relacion_serial_movimiento_materia
																			(idinventario_materia,
																			 idmovimiento_materia_almacen,
																			 serial,
																			 estado,
																			 factor)
																			VALUES
																			('".$idinventario_materia."',
																			 '".$idmovimiento."',
																			 '".$serial."',
																			 'elaboracion',
																			 'aumenta')")or die("error serial".mysql_error());
				echo "exito";
			}else{
				echo "existe";
			}
		}else{
			echo "existe";	
		}
	}else{
		echo "limite";	
	}
 break;

 case "consultarSeriales":
	  $sql_seriales = mysql_query("select * from relacion_serial_movimiento_materia 
								  			where idmovimiento_materia_almacen = '".$idmovimiento."' and
													idinventario_materia = '".$idinventario_materia."'")or die("aqui es1".mysql_error());
	  $existen_seriales = mysql_num_rows($sql_seriales)or die(mysql_error());
	  if ($existen_seriales > 0){
		  $columnas = 0;
		  ?>
		  <input name="van_seriales" type="hidden" id="van_seriales" value="<?=$existen_seriales?>">
		  <table align="center" class="Entrada">
		  <tr>
		  <?
		  while($bus_seriales = mysql_fetch_array($sql_seriales)){
			  if ($bus_seriales["estado"] == 'elaboracion'){
				?>
				<td align="center" class="Entrada" width="10%"><?=$bus_seriales["serial"]?></td>
				<td width="3%"><img src="imagenes/delete.png" style="cursor:pointer" onClick="EliminarSerialMovimiento('<?=$bus_seriales["idrelacion_serial_movimiento_materia"]?>')"></td>
				<?
			  }else{ ?>
				  <td align="center" class="Entrada" width="10%" style="background:#FC3"><?=$bus_seriales["serial"]?></td>
				  <td width="3%">&nbsp;</td>
			  <? } 
			$columnas = $columnas + 1;
			if ($columnas == 7){
				echo "</tr>";
				echo "<tr>";
				$columnas = 0;
			}
		  }
		  ?>
		  </tr>
		  </table>
		  <?
	  }
 break;

 case "EliminarSerialMovimiento":
	
	$ingresa_serial = mysql_query("delete from relacion_serial_movimiento_materia
											where idrelacion_serial_movimiento_materia = '".$idrelacion_serial_movimiento_materia."'")or die("error serial".mysql_error());
	
	echo "exito";

 break;	

 case "mostrarSerialesAjustar":
 
	  $sql_seriales = mysql_query("select * from relacion_serial_materia
								  			where idinventario_materia = '".$idinventario_materia."'")or die("aqui es1".mysql_error());
	  $existen_seriales = mysql_num_rows($sql_seriales)or die(mysql_error());
 	
	  $sql_serialesEliminadosMovimiento = mysql_query("select * from relacion_serial_movimiento_materia 
													  where idmovimiento_materia_almacen = '".$idmovimiento."' and
															idinventario_materia = '".$idinventario_materia."'")or die("van aqui ".mysql_error());
	  if (mysql_num_rows($sql_serialesEliminadosMovimiento)){
	  	$seriales_eliminados = mysql_num_rows($sql_serialesEliminadosMovimiento)or die("errorrrr ".mysql_error());
	  }else{
		$seriales_eliminados = 0;
	  }
	  
	  $quedan_seriales = $existen_seriales - $seriales_eliminados;
	
	  if ($quedan_seriales > $cantidadajustada){
		  $columnas = 0;
		  ?>
          <table align="center" >
          <tr><td>Cantidad de seriales existentes: <input name="van_seriales" id="van_seriales" type="text" id="van_seriales" value="<?=$quedan_seriales?>" readonly="readonly">
          </td></tr></table>
		  <table align="center" class="Entrada">
		  <tr>
		  <?
		  while($bus_seriales = mysql_fetch_array($sql_seriales)){
			 $sql_serialeYaEliminado = mysql_query("select * from relacion_serial_movimiento_materia 
													  	where idmovimiento_materia_almacen = '".$idmovimiento."' and
																idinventario_materia = '".$idinventario_materia."' and
																serial = '".$bus_seriales["serial"]."'");
			 if (mysql_num_rows($sql_serialeYaEliminado)){
	 		 	$serial_YaEliminado = mysql_num_rows($sql_serialeYaEliminado)or die("error aqui".mysql_error());
			 }else{
			 	$serial_YaEliminado = 0;
			 }
			 if ($serial_YaEliminado <= 0){
			 	  if ($bus_seriales["estado"] == 'Disponible'){
					?>
					<td align="center" class="Entrada" width="10%"><?=$bus_seriales["serial"]?></td>
					<td width="3%"><img src="imagenes/delete.png" style="cursor:pointer" onClick="EliminarSerial('<?=$bus_seriales["idrelacion_serial_materia"]?>')"></td>
					<?
				  }else{ ?>
					  <td align="center" class="Entrada" width="10%" style="background:#FC3"><?=$bus_seriales["serial"]?></td>
					  <td width="3%">&nbsp;</td>
				  <? } 
  				  $columnas = $columnas + 1;
				  if ($columnas == 7){
					echo "</tr>";
					echo "<tr>";
					$columnas = 0;
				  }
			 }else{
				?>
                <td align="center" class="Entrada" width="10%"><?=$bus_seriales["serial"]?></td>
                <td width="3%"><img src="imagenes/advertencia.png" style="cursor:pointer" title="Reingresar este serial eliminado por error" onClick="ReingresarSerial('<?=$bus_seriales["serial"]?>')"></td>
                <?
				 $columnas = $columnas + 1;
				  if ($columnas == 7){
					echo "</tr>";
					echo "<tr>";
					$columnas = 0;
				  }
			 }
		  }
		  ?>
	  	  </tr>
	  	  </table>
	 	  <?
	 }else{ 
	 	?>
          <table align="center" class="Entrada">
		  <tr> <?
		while($bus_seriales = mysql_fetch_array($sql_seriales)){
			 $sql_serialeYaEliminado = mysql_query("select * from relacion_serial_movimiento_materia 
													  	where idmovimiento_materia_almacen = '".$idmovimiento."' and
																idinventario_materia = '".$idinventario_materia."' and
																serial = '".$bus_seriales["serial"]."'");
			 if (mysql_num_rows($sql_serialeYaEliminado)){
	 		 	$serial_YaEliminado = mysql_num_rows($sql_serialeYaEliminado)or die("error aqui".mysql_error());
			 }else{
			 	$serial_YaEliminado = 0;
			 }
			 if ($serial_YaEliminado <= 0){
			 	  if ($bus_seriales["estado"] == 'Disponible'){
					?>
					<td align="center" class="Entrada" width="10%"><?=$bus_seriales["serial"]?></td>
					<td width="3%">&nbsp;</td>
					<?
				  }else{ ?>
					  <td align="center" class="Entrada" width="10%" style="background:#FC3"><?=$bus_seriales["serial"]?></td>
					  <td width="3%">&nbsp;</td>
				  <? } 
  				  $columnas = $columnas + 1;
				  if ($columnas == 7){
					echo "</tr>";
					echo "<tr>";
					$columnas = 0;
				  }
			 }else{
				?>
                <td align="center" class="Entrada" width="10%"><?=$bus_seriales["serial"]?></td>
                <td width="3%"><img src="imagenes/advertencia.png" style="cursor:pointer" title="Reingresar este serial eliminado por error" onClick="ReingresarSerial('<?=$bus_seriales["serial"]?>')"></td>
                <?
				 $columnas = $columnas + 1;
				  if ($columnas == 7){
					echo "</tr>";
					echo "<tr>";
					$columnas = 0;
				  }
			 }
		  }
		  ?>
	  	  </tr>
	  	  </table>
	 	  <?
  	 }
 break;
	
	
 case "EliminarSerial":
	
	$sql_seriales = mysql_query("select * from relacion_serial_materia
								  			where idrelacion_serial_materia = '".$idrelacion_serial_materia."'")or die("aqui es1".mysql_error());
	$sql_serial = mysql_fetch_array($sql_seriales);
	$ingresa_serial = mysql_query("insert into relacion_serial_movimiento_materia
																			(idinventario_materia,
																			 idmovimiento_materia_almacen,
																			 serial,
																			 estado,
																			 factor)
																			VALUES
																			('".$idinventario_materia."',
																			 '".$idmovimiento."',
																			 '".$sql_serial["serial"]."',
																			 'elaboracion',
																			 'disminuye')")or die("error serial".mysql_error());
	
	echo "exito";

 break;	

case "ReingresarSerial":

	$ingresa_serial = mysql_query("delete from relacion_serial_movimiento_materia
											where idmovimiento_materia_almacen = '".$idmovimiento."'
											and idinventario_materia = '".$idinventario_materia."'
											and serial = '".$serial."'")or die("error serial".mysql_error());
	
	echo "exito";

 break;	

 case "ingresarFVencimiento":
 	//VALIDO LA CANTIDAD DE FECHAS INGRESADAS EN ESTE MOVIMIENTO
	$validar_cantidad = mysql_query("select SUM(cantidad) as cantidad from relacion_vencimiento_movimiento_materia 
								  				where idmovimiento_materia_almacen = '".$idmovimiento."'
												and idinventario_materia = '".$idinventario_materia."'
												and factor <> 'ajusta'")or die("aqui error ".mysql_error());
	$existe_cantidad = mysql_fetch_array($validar_cantidad);
	$existen_cantidad = $existe_cantidad["cantidad"];
	
	//VALIDO LOS AJUSTES DE FECHA EN ESTE MOVIMIENTO
	$validar_cantidad_ajustada = mysql_query("select * from relacion_vencimiento_movimiento_materia 
								  				where idmovimiento_materia_almacen = '".$idmovimiento."'
												and idinventario_materia = '".$idinventario_materia."'
												and factor = 'ajusta'")or die("aqui error ".mysql_error());
	$existencia_ajustada = 0;
	while ($existe_cantidad_ajustada = mysql_fetch_array($validar_cantidad_ajustada)){
		$validar_cantidad_individual = mysql_query("select * from relacion_vencimiento_materia 
								  				where idrelacion_vencimiento_materia = '".$existe_cantidad_ajustada["idrelacion_vencimiento_materia"]."'
												")or die("aqui error ".mysql_error());
		$existe_cantidad_individual = mysql_fetch_array($validar_cantidad_individual);
		
		$cantidad_individual = $existe_cantidad_individual["cantidad"];
		if ($existe_cantidad_ajustada["cantidad"] < 0){
			$existencia_lote_ajustado = $cantidad_individual + $existe_cantidad_ajustada["cantidad"];
		}else{
			$existencia_lote_ajustado = $existe_cantidad_ajustada["cantidad"] - $cantidad_individual; 
		}
		$existencia_ajustada = $existencia_ajustada + $existencia_lote_ajustado;
	}	
	
	if (($existencia_ajustada+$existen_cantidad+$cantidad) < $cantidad_movimiento){
		$validar_existe = mysql_query("select * from relacion_vencimiento_movimiento_materia 
								  				where idmovimiento_materia_almacen = '".$idmovimiento."'
												and idinventario_materia = '".$idinventario_materia."'
												and lote = '".$lote."'")or die("aqui error ".mysql_error());
		if (mysql_num_rows($validar_existe)){
			$existe_lote = mysql_num_rows($validar_existe);
			$idrelacion_movimiento = mysql_fetch_array($validar_existe);
		}else{
			$existe_lote = 0;
		}
		if ($existe_lote == 0){
			$ingresa_lote = mysql_query("insert into relacion_vencimiento_movimiento_materia
																			(idmovimiento_materia_almacen,
																			 idinventario_materia,
																			 lote,
																			 cantidad,
																			 fecha_vencimiento,
																			 disponibles,
																			 factor)
																			VALUES
																			('".$idmovimiento."',
																			 '".$idinventario_materia."',
																			 '".$lote."',
																			 '".$cantidad."',
																			 '".$fecha_vencimiento."',
																			 '".$cantidad."',
																			 'aumenta')")or die("error ingresando fecha vencimiento".mysql_error());
			echo "exito";
		}else{
			$result=mysql_query("update relacion_vencimiento_movimiento_materia set 	cantidad	= '".$cantidad."'
									where 
									idrelacion_vencimiento_movimiento_materia	= '".$idrelacion_movimiento["idrelacion_vencimiento_movimiento_materia"]."'")or die(" error actualizando fvencimiento ".mysql_error());
			echo "exito";
		}
	}else{
		echo "limite";	
	}
 break;


 case "consultarFVencimiento":
  //VALIDO LA CANTIDAD DE FECHAS INGRESADAS EN ESTE MOVIMIENTO
	$validar_cantidad = mysql_query("select SUM(cantidad) as cantidad from relacion_vencimiento_movimiento_materia 
								  				where idmovimiento_materia_almacen = '".$idmovimiento."'
												and idinventario_materia = '".$idinventario_materia."'
												and factor <> 'ajusta'")or die("aqui error ".mysql_error());
	$existe_cantidad = mysql_fetch_array($validar_cantidad);
	$existen_cantidad = $existe_cantidad["cantidad"];
	
	//VALIDO LOS AJUSTES DE FECHA EN ESTE MOVIMIENTO
	$validar_cantidad_ajustada = mysql_query("select * from relacion_vencimiento_movimiento_materia 
								  				where idmovimiento_materia_almacen = '".$idmovimiento."'
												and idinventario_materia = '".$idinventario_materia."'
												and factor = 'ajusta'")or die("aqui error ".mysql_error());
	$existencia_ajustada = 0;
	while ($existe_cantidad_ajustada = mysql_fetch_array($validar_cantidad_ajustada)){
		$validar_cantidad_individual = mysql_query("select * from relacion_vencimiento_materia 
								  				where idrelacion_vencimiento_materia = '".$existe_cantidad_ajustada["idrelacion_vencimiento_materia"]."'
												")or die("aqui error ".mysql_error());
		$existe_cantidad_individual = mysql_fetch_array($validar_cantidad_individual);
		
		$cantidad_individual = $existe_cantidad_individual["cantidad"];
		if ($existe_cantidad_ajustada["cantidad"] < 0){
			$existencia_lote_ajustado = $cantidad_individual + $existe_cantidad_ajustada["cantidad"];
		}else{
			$existencia_lote_ajustado = $existe_cantidad_ajustada["cantidad"] - $cantidad_individual; 
		}
		$existencia_ajustada = $existencia_ajustada + $existencia_lote_ajustado;
	}	
	
	$van_vencimiento = $existencia_ajustada+$existen_cantidad
 	  ?>
      <input name="van_fechas" type="hidden" id="van_fechas" value="<?=$existen_vencimiento+$existen_movimiento_vencimiento?>">
     <table class="Browse" cellpadding="0" cellspacing="0" width="60%" align="center">
      <thead>
        <tr>
          <td width="20%" align="center" class="Browse">Lote</td>	
          <td width="20%" align="center" class="Browse">Fecha Vencimiento</td>
          <td width="20%" align="center" class="Browse">Cantidad del Lote</td>
          <td width="10%" align="center" class="Browse">Disponibles</td>
          <td width="20%" align="center" class="Browse">Cantidad Ajustada</td>
          <td width="10%" align="center" class="Browse">Acci&oacute;n</td>
        </tr>
     </thead>
      <?
	 
      while($bus_vencimiento = mysql_fetch_array($sql_vencimiento)){ 
      		$sql_vencimiento_movimiento = mysql_query("select * from relacion_vencimiento_movimiento_materia 
													   	where idmovimiento_materia_almacen = '".$idmovimiento."'
														and idrelacion_vencimiento_materia = '".$bus_vencimiento["idrelacion_vencimiento_materia"]."'
														")or die("error ".mysql_error());
			 if (mysql_num_rows($sql_vencimiento_movimiento)){
				$valida_existe = mysql_num_rows($sql_vencimiento_movimiento);
				$cantidad_existe = mysql_fetch_array($sql_vencimiento_movimiento);
			 }else{
				$valida_existe = 0;
				$cantidad_existe = 0;
			 } ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			<td align="left" class='Browse' style="font-weight:normal" width="20%"><?=$bus_vencimiento["lote"]?></td>
            <td align="center" class='Browse' style="font-weight:normal" width="20%"><?=$bus_vencimiento["fecha_vencimiento"]?></td>
            <td align="right" class='Browse' style="font-weight:normal" width="20%"><?=number_format($bus_vencimiento["cantidad"],2,",",".")?></td>
            <td align="right" class='Browse' style="font-weight:normal" width="20%"><?=number_format($bus_vencimiento["disponibles"],2,",",".")?></td>
            <td align="right" class='Browse' style="font-weight:normal" width="20%"><input type="text" name="cantidad_ajuste_lote<?=$bus_vencimiento["idrelacion_vencimiento_materia"]?>" id="cantidad_ajuste_lote<?=$bus_vencimiento["idrelacion_vencimiento_materia"]?>" value="<?=$cantidad_existe["cantidad"]?>" style="text-align:right" size="10" /></td>
			<td align="center" class='Browse'><img src="imagenes/refrescar.png" alt="Ajustar Lote" title="Ajustar Lote" style="cursor:pointer" onClick="ajustarLoteVencimiento('<?=$bus_vencimiento["idrelacion_vencimiento_materia"]?>')"></td>
	      </tr>
	<? } ?>
      <?
	   $sql_vencimiento_movimiento = mysql_query("select * from relacion_vencimiento_movimiento_materia where idmovimiento_materia_almacen = '".$idmovimiento."' and factor <> 'ajusta'")or die("error ".mysql_error());
      while($bus_vencimiento_movimiento = mysql_fetch_array($sql_vencimiento_movimiento)){ ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			<td align="left" class='Browse' style="font-weight:normal" width="20%"><?=$bus_vencimiento_movimiento["lote"]?></td>
            <td align="center" class='Browse' style="font-weight:normal" width="20%"><?=$bus_vencimiento_movimiento["fecha_vencimiento"]?></td>
            <td align="right" class='Browse' style="font-weight:normal" width="20%"><?=number_format($bus_vencimiento_movimiento["cantidad"],2,",",".")?></td>
            <td align="right" class='Browse' style="font-weight:normal" width="20%"><?=number_format($bus_vencimiento_movimiento["disponibles"],2,",",".")?></td>
            <td width="20%" align="center" class="Browse">&nbsp;</td>
        	<td align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="eliminarLoteVencimientoMovimiento('<?=$bus_vencimiento_movimiento["idrelacion_vencimiento_movimiento_materia"]?>')"></td>
	      </tr>
	<? } ?>
     </table>
     <?
 break;  

case "eliminarLoteVencimientoMovimiento":
	
	$eliminar_FVmovimiento = mysql_query("delete from relacion_vencimiento_movimiento_materia
											where idrelacion_vencimiento_movimiento_materia = '".$idrelacion_vencimiento_movimiento_materia."'")or die("error FVencimiento".mysql_error());
	
	echo "exito";

 break;		
 
 
 
 case "mostrarFechaVencimientoAjustar":
	  $sql_vencimiento = mysql_query("select SUM(cantidad) as cantidad from relacion_vencimiento_materia where idinventario_materia = '".$idinventario_materia."'")or die("error ".mysql_error());
	  $bus_vencimiento = mysql_fetch_array($sql_vencimiento)or die(mysql_error());
	  $existen_vencimiento = $bus_vencimiento["cantidad"];
	  $sql_vencimiento = mysql_query("select * from relacion_vencimiento_materia where idinventario_materia = '".$idinventario_materia."'")or die("error ".mysql_error());
		  ?>
          <table align="center" >
          <tr><td>Cantidad de materiales con fecha de vencimiento existentes: <input name="van_fechas" id="van_fechas" type="text" value="<?=$existen_vencimiento?>" readonly="readonly">
          </td></tr></table>		 
		 <table class="Browse" cellpadding="0" cellspacing="0" width="60%" align="center">
		  <thead>
			<tr>
			  <td width="15%" align="center" class="Browse">Lote</td>	
			  <td width="15%" align="center" class="Browse">Fecha Vencimiento</td>
			  <td width="20%" align="center" class="Browse">Cantidad del Lote</td>
			  <td width="20%" align="center" class="Browse">Disponibles</td>
              <td width="20%" align="center" class="Browse">Ajuste</td>
			  <td width="10%" align="center" class="Browse">Acci&oacute;n</td>
			</tr>
		 </thead>
		  <?
		  while($bus_vencimiento = mysql_fetch_array($sql_vencimiento)){ ?>
			  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td align="left" class='Browse' style="font-weight:normal" width="15%"><?=$bus_vencimiento["lote"]?></td>
				<td align="center" class='Browse' style="font-weight:normal" width="15%"><?=$bus_vencimiento["fecha_vencimiento"]?></td>
				<td align="right" class='Browse' style="font-weight:normal" width="20%"><?=number_format($bus_vencimiento["cantidad"],2,",",".")?></td>
				<td align="right" class='Browse' style="font-weight:normal" width="20%"><?=number_format($bus_vencimiento["disponibles"],2,",",".")?></td>
                <td align="right" class='Browse' style="font-weight:normal" width="20%"><input type="text" name="cantidad_ajuste_lote<?=$bus_vencimiento["idrelacion_vencimiento_materia"]?>" id="cantidad_ajuste_lote<?=$bus_vencimiento["idrelacion_vencimiento_materia"]?>" style="text-align:right" size="10" /></td>
				<td align="center" class='Browse'><img src="imagenes/refrescar.png" alt="Ajustar Lote" title="Ajustar Lote" style="cursor:pointer" onClick="ajustarLoteVencimiento('<?=$bus_vencimiento["idrelacion_vencimiento_materia"]?>')"></td>
			  </tr>
		<? } ?>
		  
		 </table>
		 <?
  break;


case "ajustarLoteVencimiento":
	$validar_cantidad_total = mysql_query("select SUM(disponibles) as cantidad from relacion_vencimiento_materia 
								  				where idinventario_materia = '".$idinventario_materia."'")or die("aqui error ".mysql_error());
	$existe_cantidad_total = mysql_fetch_array($validar_cantidad_total);
	$existen_cantidad = $existe_cantidad["cantidad"];
	
	if (($existen_cantidad + $cantidad_ajuste_lote) < 0){
		echo "superior"; // VALIDA QUE NO SE DISMINUYA UN VALOR MAYOR AL DISPONIBLE
	}else{

		$validar_cantidad = mysql_query("select cantidad, disponibles from relacion_vencimiento_materia 
													where idrelacion_vencimiento_materia = '".$idrelacion_vencimiento_materia."'")or die("aqui error ".mysql_error());
		$existe_cantidad = mysql_fetch_array($validar_cantidad);
		$existen_cantidad = $existe_cantidad["cantidad"];
		
		if (($existen_cantidad + $cantidad_ajuste_lote) < $cantidadajustada){ // VALIDA QUE EL AJUSTE NO SUPERE A LA CANTIDAD QUE SE VA A AJUSTAR
		
			 $sql_vencimiento_movimiento = mysql_query("select * from relacion_vencimiento_movimiento_materia 
													   	where idmovimiento_materia_almacen = '".$idmovimiento."'
														and idrelacion_vencimiento_materia = '".$idrelacion_vencimiento_materia."'
														")or die("error ".mysql_error());
			 if (mysql_num_rows($sql_vencimiento_movimiento)){
				$valida_existe = mysql_num_rows($sql_vencimiento_movimiento);
			 }else{
				$valida_existe = 0;
			 }
			 if ($valida_existe == 0){ 
				 $actualiza_lote = mysql_query("insert into relacion_vencimiento_movimiento_materia
																			(idmovimiento_materia_almacen,
																			 idrelacion_vencimiento_materia,
																			 idinventario_materia,
																			 cantidad,
																			 factor)
																			VALUES
																			('".$idmovimiento."',
																			 '".$idrelacion_vencimiento_materia."',
																			 '".$idinventario_materia."',
																			 '".$cantidad_ajuste_lote."',
																			 'ajusta')")or die("error ingresando fecha vencimiento".mysql_error());
			 }else{
				 $id_existe = mysql_fetch_array($sql_vencimiento_movimiento);
				 $result=mysql_query("update relacion_vencimiento_movimiento_materia set 	cantidad	= '".$cantidad_ajuste_lote."'
										where 
						idrelacion_vencimiento_movimiento_materia	= '".$id_existe["idrelacion_vencimiento_movimiento_materia"]."'")or die(mysql_error());
			 }
			echo "exito";
		}else{
			echo "mayor";	// VALIDA QUE EL AJUSTE NO SUPERE A LA CANTIDAD QUE SE VA A AJUSTAR
		}
	}
 break;



 case "finalizarAjusteMateria":
	
	if ($serializado == 1 and $fecha_vencimiento == 1){
		$sql_seriales = mysql_query("select * from relacion_serial_materia where idinventario_materia = '".$idinventario_materia."'")or die("aqui es1".mysql_error());
		if (mysql_num_rows($sql_seriales)){
			$existen_seriales = mysql_num_rows($sql_seriales)or die(mysql_error());
		}else{
			$existen_seriales = 0;
		}
		$sql_serialesEliminadosMovimiento = mysql_query("select * from relacion_serial_movimiento_materia 
													  where idmovimiento_materia_almacen = '".$idmovimiento."' and
															idinventario_materia = '".$idinventario_materia."'")or die("van aqui ".mysql_error());
	    if (mysql_num_rows($sql_serialesEliminadosMovimiento)){
		  $seriales_movimiento = mysql_num_rows($sql_serialesEliminadosMovimiento)or die("errorrrr ".mysql_error());
	    }else{
		  $seriales_movimiento = 0;
	    }
	  	
		if ($cantidad_ajuste < 0){
	    	$seriales_ajustados = $existen_seriales - $seriales_movimiento;
		}else{
			$seriales_ajustados = $existen_seriales + $seriales_movimiento;	
		}
		
		  
		if ($existen_seriales < $seriales_ajustados){
				echo "faltan_seriales";
		}else{
			$validar_cantidad = mysql_query("select SUM(cantidad) as cantidad from relacion_vencimiento_materia 
												where idinventario_materia = '".$idinventario_materia."'")or die("aqui error ".mysql_error());
			$existe_cantidad = mysql_fetch_array($validar_cantidad);
			$existen_fechas = number_format($existe_cantidad["cantidad"],2,",",".");			
			if ($existen_fechas < $cantidad_inicial){
				echo "faltan_fechas";
			}else{
				echo "exito";
			}
		}
	}
	
	
	if ($serializado == 1 and $fecha_vencimiento == 0){

		$sql_seriales = mysql_query("select * from relacion_serial_materia where idinventario_materia = '".$idinventario_materia."'")or die("aqui es1".mysql_error());
		if (mysql_num_rows($sql_seriales)){
			$existen_seriales = mysql_num_rows($sql_seriales)or die(mysql_error());
		}else{
			$existen_seriales = 0;
		}
		
		$existen_seriales_mas_ajuste = $existen_seriales + $cantidad_ajuste;
		
		$sql_serialesEliminadosMovimiento = mysql_query("select * from relacion_serial_movimiento_materia 
													  where idmovimiento_materia_almacen = '".$idmovimiento."' and
															idinventario_materia = '".$idinventario_materia."'")or die("van aqui ".mysql_error());
	    if (mysql_num_rows($sql_serialesEliminadosMovimiento)){
		  $seriales_movimiento = mysql_num_rows($sql_serialesEliminadosMovimiento)or die("errorrrr ".mysql_error());
	    }else{
		  $seriales_movimiento = 0;
	    }
	  	
		if ($cantidad_ajuste < 0){
	    	$seriales_ajustados = $existen_seriales - $seriales_movimiento;
		}else{
			$seriales_ajustados = $existen_seriales + $seriales_movimiento;	
		}
		
		echo "Cantidad de seriales existentes ".$existen_seriales;
		echo " seriales en este movimiento ".$seriales_movimiento;
		echo " seriales ajustados ".$seriales_ajustados;
		echo " seriales totales ajustados ".$existen_seriales_mas_ajuste;
		
		if ($existen_seriales_mas_ajuste < $seriales_ajustados){
			echo "faltan_seriales";
		}else{
			echo "exito";
		}
	}
	
	if ($fecha_vencimiento == 1 and $serializado == 0){
		$validar_cantidad = mysql_query("select SUM(cantidad) as cantidad from relacion_vencimiento_materia 
												where idinventario_materia = '".$idinventario_materia."'")or die("aqui error ".mysql_error());
		$existe_cantidad = mysql_fetch_array($validar_cantidad);
		$existen_fechas = number_format($existe_cantidad["cantidad"],2,",",".");
		if ($existen_fechas < $cantidad_inicial){
			echo "faltan_fechas";
		}else{
			echo "exito";
		}
	}
 break;
}

?>