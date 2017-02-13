<?
include("../../../conf/conex.php");
conectarse();

extract($_POST);


if($ejecutar == "ingresarMovimiento"){
	
	$sql_validar_existe = mysql_query("select * from tipo_movimiento_almacen 
									  					where codigo = '".$codigo."'
														and descripcion = '".$denominacion."'")or die("valida existe tipo_movimiento ".mysql_error());
	$num_valida_existe = mysql_num_rows($sql_validar_existe);
	if ($num_valida_existe == 0){
		$sql_ingresar = mysql_query("insert into tipo_movimiento_almacen (codigo,
																		  descripcion, 
																		  afecta,
																		  activo,
																		  origen_materia,
																		  describir_motivo,
																		  memoria_fotografica,
																		  comprobante,
																		  numero_comprobante,
																		  documento_origen,
																		  usuario, 
																		  fechayhora, 
																		  status)VALUES('".$codigo."',
																							'".$denominacion."',
																							'".$afecta."',
																							'".$activo."',
																							'".$origen_materia."',
																							'".$describir_motivo."',
																							'".$memoria_fotografica."',
																							'".$comprobante."',
																							'".$numero_comprobante."',
																							'".$documento_origen."',
																							'".$login."',
																							'".$fh."',
																			'a')")or die(mysql_error());
		echo "exito";
		
	}else{
		echo "existe";	
	}
}


if($ejecutar == "listarTipoMovimiento"){
		$sql_consulta = mysql_query("select * from tipo_movimiento_almacen order by afecta, codigo")or die(mysql_error());
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
   	  <thead>
      <tr>
          <td width="10%" align="center" class="Browse">C&oacute;digo</td>	
          <td width="80%" align="center" class="Browse">Denominaci&oacute;n</td>
          <td align="center" class="Browse" colspan="2">Acciones</td>&nbsp;
        </tr>
        </thead>
        <?
		$afecta = 0;
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		if ($afecta <> $bus_consulta["afecta"]){
			$afecta = $bus_consulta["afecta"];
			if ($afecta == 1) $denominacion_afecta = "Entrada";
			if ($afecta == 2) $denominacion_afecta = "Salida";
			if ($afecta == 3) $denominacion_afecta = "Inventario Inicial";
			if ($afecta == 4) $denominacion_afecta = "Traslado de Materias";
			echo "<tr>";
			echo "<td align='left' colspan='4' bgcolor='#0099CC'><b>&nbsp;".$denominacion_afecta."</b></td>";
			echo "</tr>"; ?>
       		<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class='Browse'><?=$bus_consulta["codigo"]?></td>
          	<td class='Browse'><?=$bus_consulta["descripcion"]?></td>
			
            <td width="1%" align="center" class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarModificar('<?=$bus_consulta["id_tipo_movimiento_almacen"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["descripcion"]?>', '<?=$bus_consulta["afecta"]?>', '<?=$bus_consulta["activo"]?>', '<?=$bus_consulta["origen_materia"]?>', '<?=$bus_consulta["describir_motivo"]?>', '<?=$bus_consulta["memoria_fotografica"]?>', '<?=$bus_consulta["comprobante"]?>', '<?=$bus_consulta["numero_comprobante"]?>', '<?=$bus_consulta["documento_origen"]?>')"></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminar('<?=$bus_consulta["id_tipo_movimiento_almacen"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["descripcion"]?>', '<?=$bus_consulta["afecta"]?>', '<?=$bus_consulta["activo"]?>', '<?=$bus_consulta["origen_materia"]?>', '<?=$bus_consulta["describir_motivo"]?>', '<?=$bus_consulta["memoria_fotografica"]?>', '<?=$bus_consulta["comprobante"]?>', '<?=$bus_consulta["numero_comprobante"]?>', '<?=$bus_consulta["documento_origen"]?>')"></td>
      		</tr>
        <?
		}else{ ?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class='Browse'><?=$bus_consulta["codigo"]?></td>
          	<td class='Browse'><?=$bus_consulta["descripcion"]?></td>
			
            <td width="1%" align="center" class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarModificar('<?=$bus_consulta["id_tipo_movimiento_almacen"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["descripcion"]?>', '<?=$bus_consulta["afecta"]?>', '<?=$bus_consulta["activo"]?>', '<?=$bus_consulta["origen_materia"]?>', '<?=$bus_consulta["describir_motivo"]?>', '<?=$bus_consulta["memoria_fotografica"]?>', '<?=$bus_consulta["comprobante"]?>', '<?=$bus_consulta["numero_comprobante"]?>', '<?=$bus_consulta["documento_origen"]?>')"></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminar('<?=$bus_consulta["id_tipo_movimiento_almacen"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["descripcion"]?>', '<?=$bus_consulta["afecta"]?>', '<?=$bus_consulta["activo"]?>', '<?=$bus_consulta["origen_materia"]?>', '<?=$bus_consulta["describir_motivo"]?>', '<?=$bus_consulta["memoria_fotografica"]?>', '<?=$bus_consulta["comprobante"]?>', '<?=$bus_consulta["numero_comprobante"]?>', '<?=$bus_consulta["documento_origen"]?>')"></td>
     	 	</tr>
		<?
		}
        }
		?>
        </table>
<?
}


if($ejecutar == "modificarMovimiento"){
	echo $id_tipo_movimiento_almacen;
	$sql_modificar = mysql_query("update tipo_movimiento_almacen set 		
								 							descripcion = '".$denominacion."',
															afecta = '".$afecta."',
								 							activo = '".$activo."',
															origen_materia = '".$origen_materia."',
															describir_motivo = '".$describir_motivo."',
															memoria_fotografica = '".$memoria_fotografica."',
															comprobante = '".$comprobante."',
															numero_comprobante = '".$numero_comprobante."',
															documento_origen = '".$documento_origen."'
															where id_tipo_movimiento_almacen = '".$id_tipo_movimiento_almacen."'")or die(mysql_error());
}



if($ejecutar == "eliminarMovimiento"){
	$sql_validar = mysql_query("select * from inventario_materia where id_tipo_movimiento_almacen = '".$id_tipo_movimiento_almacen."'");
	$sql_existe = mysql_num_rows($sql_validar);
	if ($sql_existe == 0){
		$sql_modificar = mysql_query("delete from tipo_movimiento_almacen where id_tipo_movimiento_almacen = '".$id_tipo_movimiento_almacen."'");
		echo "exito";
	}else{
		echo "existe";	
	}
	
	
	
}




?>