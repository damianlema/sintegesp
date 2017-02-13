<?
session_start();
include("../../../conf/conex.php");
extract($_POST);
Conectarse();


if($ejecutar == "cargarSubNiveles"){
	?>
    &nbsp;
    <select name="sub_nivel" id="sub_nivel">
    <option value="0">NINGUNO</option>
	<?
    $sql_sub_nivel = mysql_query("select * from distribucion_almacen where idalmacen = '".$idalmacen."'order by codigo");
	while($bus_sub_nivel = mysql_fetch_array($sql_sub_nivel)){
		?>
		<option onclick="document.getElementById('codigoSubNivel').innerHTML = '<?=$bus_sub_nivel["codigo"]?>-'" value="<?=$bus_sub_nivel["iddistribucion_almacen"]?>">(<?=$bus_sub_nivel["codigo"]?>) <?=$bus_sub_nivel["denominacion"]?></option>
		<?
	}
	?>
    </select>
	<?
}



if($ejecutar == "ingresarDistribucionAlmacen"){
if($sub_nivel != 0){
	$sql_sub = mysql_query("select * from distribucion_almacen where iddistribucion_almacen = '".$sub_nivel."'");
	$bus_sub = mysql_fetch_array($sql_sub);
	$codigo = $bus_sub["codigo"]."-".$codigo;
}
	$sql_consulta = mysql_query("select * from distribucion_almacen where idalmacen = '".$idalmacen."'
																		and codigo = '".$codigo."'
																		and sub_nivel = '".$sub_nivel."'");
	$num_consulta = mysql_num_rows($sql_consulta);
	if($num_consulta > 0){
		echo "existe";
	}else{
		
		$sql_ingresar = mysql_query("insert into distribucion_almacen (idalmacen,
																sub_nivel,
																codigo,
																denominacion,
																responsable,
																ci_responsable,
																telefono,
																email,
																largo,
																alto,
																ancho,
																capacidad,
																status,
																usuario,
																fechayhora
																)VALUES('".$idalmacen."',
																					'".$sub_nivel."',
																					'".$codigo."',
																					'".$denominacion."',
																					'".$responsable."',
																					'".$ci_responsable."',
																					'".$telefono."',
																					'".$email."',
																					'".$largo."',
																					'".$alto."',
																					'".$ancho."',
																					'".$capacidad."',
																					'a',
																					'".$login."',
																					'".$fh."')")or die("ERROR INGRESANDO".mysql_error());
		echo "exito";
	}
}




if($ejecutar == "modificarDistribucionAlmacen"){
	$sql_actualizar = mysql_query("update distribucion_almacen set denominacion = '".$denominacion."',
															responsable = '".$responsable."',
															ci_responsable = '".$ci_responsable."',
															telefono = '".$telefono."',
															email = '".$email."',
															largo = '".$largo."',
															alto = '".$alto."',
															ancho = '".$ancho."',
															capacidad = '".$capacidad."'
															where iddistribucion_almacen = '".$iddistribucion_almacen."'")or die(mysql_error());
}



if($ejecutar == "eliminarDistribucionAlmacen"){
	$sql_validar = mysql_query("select * from distribucion_almacen where sub_nivel = '".$iddistribucion_almacen."'");
	$sql_existe = mysql_num_rows($sql_validar);
	if ($sql_existe == 0){
		$sql_validar_inventario = mysql_query("select * from inventario_materia where iddistribucion_almacen = '".$iddistribucion_almacen."'");
		$sql_existe_inventario = mysql_num_rows($sql_validar_inventario);
		if ($sql_existe_inventario == 0){	
			$sql_eliminar = mysql_query("delete from distribucion_almacen where iddistribucion_almacen = '".$iddistribucion_almacen."'")or die(mysql_error());
			echo "exito";
		}else{
			echo "inventario";
		}
	}else{
		echo "existe";
	}
}



if($ejecutar == "listarDistribucionAlmacen"){
$sql_consulta = mysql_query("select distribucion_almacen.idalmacen,
									distribucion_almacen.codigo,
									distribucion_almacen.denominacion,
									distribucion_almacen.responsable,
									distribucion_almacen.ci_responsable,
									distribucion_almacen.sub_nivel,
									distribucion_almacen.telefono,
									distribucion_almacen.email,
									distribucion_almacen.largo,
									distribucion_almacen.alto,
									distribucion_almacen.ancho,
									distribucion_almacen.capacidad,
									distribucion_almacen.iddistribucion_almacen,
									almacen.denominacion as denominacion_almacen,
									almacen.codigo as codigo_almacen 
										from 
									distribucion_almacen, almacen
										where 
									almacen.idalmacen = distribucion_almacen.idalmacen
										order by almacen.codigo, distribucion_almacen.codigo ")or die(mysql_error());
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="70%" align="center">
   	  <thead>
        <tr>
          <tr>
          <td width="15%" align="center" class="Browse">C&oacute;digo</td>
          <td width="75%" align="center" class="Browse">Denominaci&oacute;n</td>
          <td align="center" class="Browse" colspan="2">Acciones</td>
        </tr>
        </thead>
        <?
		$cambio_almacen='';
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
        <?php 
		if ($cambio_almacen <> $bus_consulta["denominacion_almacen"]){
			$cambio_almacen = $bus_consulta["denominacion_almacen"];
			echo "<tr>";
			echo "<td align='left' colspan='4' bgcolor='#0099CC'><b>&nbsp;".$bus_consulta["codigo_almacen"]." - ".$bus_consulta["denominacion_almacen"]."</b></td>";
			echo "</tr>"; ?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        
            <td class='Browse'><?=$bus_consulta["codigo_almacen"]?>-<?=$bus_consulta["codigo"]?></td>
          	<td class='Browse'><?=$bus_consulta["denominacion"]?></td>
            <td width="1%" align="center" class='Browse'>
            	<img src="imagenes/modificar.png" 
                	style="cursor:pointer" 
                    onClick="seleccionarModificar('<?=$bus_consulta["idalmacen"]?>','<?=$bus_consulta["codigo_almacen"]?>-<?=$bus_consulta["codigo"]?>','<?=$bus_consulta["denominacion"]?>','<?=$bus_consulta["responsable"]?>','<?=$bus_consulta["ci_responsable"]?>', '<?=$bus_consulta["sub_nivel"]?>', '<?=$bus_consulta["telefono"]?>', '<?=$bus_consulta["email"]?>', '<?=$bus_consulta["largo"]?>', '<?=$bus_consulta["alto"]?>', '<?=$bus_consulta["ancho"]?>', '<?=$bus_consulta["capacidad"]?>', '<?=$bus_consulta["iddistribucion_almacen"]?>')">
            </td>
            <td width="1%" align="center" class='Browse'>
            	<img src="imagenes/delete.png" 
                	style="cursor:pointer" 
                    onClick="seleccionarEliminar('<?=$bus_consulta["idalmacen"]?>','<?=$bus_consulta["codigo_almacen"]?>-<?=$bus_consulta["codigo"]?>','<?=$bus_consulta["denominacion"]?>','<?=$bus_consulta["responsable"]?>','<?=$bus_consulta["ci_responsable"]?>', '<?=$bus_consulta["sub_nivel"]?>', '<?=$bus_consulta["telefono"]?>', '<?=$bus_consulta["email"]?>', '<?=$bus_consulta["largo"]?>', '<?=$bus_consulta["alto"]?>', '<?=$bus_consulta["ancho"]?>', '<?=$bus_consulta["capacidad"]?>', '<?=$bus_consulta["iddistribucion_almacen"]?>')">
            </td>
      		</tr>
      <?
		}else{
		?>
        
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        
            <td class='Browse'><?=$bus_consulta["codigo_almacen"]?>-<?=$bus_consulta["codigo"]?></td>
          	<td class='Browse'><?=$bus_consulta["denominacion"]?></td>
            <td width="1%" align="center" class='Browse'>
            	<img src="imagenes/modificar.png" 
                	style="cursor:pointer" 
                    onClick="seleccionarModificar('<?=$bus_consulta["idalmacen"]?>','<?=$bus_consulta["codigo_almacen"]?>-<?=$bus_consulta["codigo"]?>','<?=$bus_consulta["denominacion"]?>','<?=$bus_consulta["responsable"]?>','<?=$bus_consulta["ci_responsable"]?>', '<?=$bus_consulta["sub_nivel"]?>', '<?=$bus_consulta["telefono"]?>', '<?=$bus_consulta["email"]?>', '<?=$bus_consulta["largo"]?>', '<?=$bus_consulta["alto"]?>', '<?=$bus_consulta["ancho"]?>', '<?=$bus_consulta["capacidad"]?>', '<?=$bus_consulta["iddistribucion_almacen"]?>')">
            </td>
            <td width="1%" align="center" class='Browse'>
            	<img src="imagenes/delete.png" 
                	style="cursor:pointer" 
                    onClick="seleccionarEliminar('<?=$bus_consulta["idalmacen"]?>','<?=$bus_consulta["codigo_almacen"]?>-<?=$bus_consulta["codigo"]?>','<?=$bus_consulta["denominacion"]?>','<?=$bus_consulta["responsable"]?>','<?=$bus_consulta["ci_responsable"]?>', '<?=$bus_consulta["sub_nivel"]?>', '<?=$bus_consulta["telefono"]?>', '<?=$bus_consulta["email"]?>', '<?=$bus_consulta["largo"]?>', '<?=$bus_consulta["alto"]?>', '<?=$bus_consulta["ancho"]?>', '<?=$bus_consulta["capacidad"]?>', '<?=$bus_consulta["iddistribucion_almacen"]?>')">
            </td>
      </tr>
        <?
        }
        }
		?>
        </table>
<?
}
?>