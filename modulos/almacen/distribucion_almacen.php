
<script src="modulos/almacen/js/distribucion_almacen_ajax.js"></script>
	<br>
	<h4 align=center>Distribuci&oacute;n del Almacen</h4>
	<h2 class="sqlmVersion"></h2>
    <br>
<center><a href="principal.php?accion=<?=$_GET["accion"]?>&amp;modulo=<?=$_GET["modulo"]?>">
	<img src="imagenes/nuevo.png" border="0" title="Nuevo Grupo">
</a></center>
<br />

<input type="hidden" id="iddistribucion_almacen" name="iddistribucion_almacen">
<table width="60%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td width="24%" align='right' class='viewPropTitle'>Almacen:</td>
    <td width="76%">
      &nbsp;
      <select name="almacen" id="almacen">
      <option value="0" onclick="document.getElementById('codigoDistribucion').innerHTML = ''">.:: Seleccione ::.</option>
	  <?
      	$sql_organizacion = mysql_query("select * from almacen");
		while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
			$sql_tiene_sub = mysql_query("select * from distribucion_almacen where idalmacen = '".$bus_organizacion["idalmacen"]."'")or die(mysql_error());
			$num_tiene_sub = mysql_num_rows($sql_tiene_sub);
			?>
			<option value="<?=$bus_organizacion["idalmacen"]?>" onclick="cargarSubNiveles('<?=$bus_organizacion["idalmacen"]?>', '<?=$num_tiene_sub?>'), document.getElementById('codigoDistribucion').innerHTML ='<?=$bus_organizacion["codigo"]?>-'"><?=$bus_organizacion["codigo"]." - ".$bus_organizacion["denominacion"]?></option>
			<?
		}
	  ?>
      </select>    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Area Distributiva:</td>
    <td id="celda_sub_nivel">
    &nbsp;
    <select name="sub_nivel" id="sub_nivel" disabled="disabled">
    <option value="0">NINGUNO</option>
	<?
    $sql_sub_nivel = mysql_query("select * from distribucion_almacen ");
	while($bus_sub_nivel = mysql_fetch_array($sql_sub_nivel)){
		?>
		<option value="<?=$bus_sub_nivel["iddistribucion_almacen"]?>" onclick="document.getElementById('codigoSubNivel').innerHTML = '<?=substr($bus_sub_nivel["codigo"], 3, strlen($bus_sub_nivel["codigo"]))?>'"><?=$bus_sub_nivel["denominacion"]?></option>
		<?
	}
	?>
    </select>    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>C&oacute;digo:</td>
    <td>
    	<table border="0" style="">
        <tr>
        <td id="codigoDistribucion" style="font-weight:bold"></td>
        <td id="codigoSubNivel" style="font-weight:bold"></td>
      	<td><input name="codigo" type="text" id="codigo" maxlength="2"></td>
        </tr>
        </table>   </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
    <td>
      &nbsp;
    <input name="denominacion" type="text" id="denominacion" size="80">    </td>
  </tr>
   <tr>
    <td align='right' class='viewPropTitle'>Largo:</td>
    <td>
      &nbsp;
    <input name="largo" type="text" id="largo" size="10">cm    </td>
  </tr>
   <tr>
    <td align='right' class='viewPropTitle'>Alto:</td>
    <td>
      &nbsp;
    <input name="alto" type="text" id="alto" size="10">cm    </td>
  </tr>
   <tr>
    <td align='right' class='viewPropTitle'>Ancho:</td>
    <td>
      &nbsp;
    <input name="ancho" type="text" id="ancho" size="10">cm    </td>
  </tr>
   <tr>
    <td align='right' class='viewPropTitle'>Capacidad:</td>
    <td>
      &nbsp;
    <input name="capacidad" type="text" id="capacidad" size="10">    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Responsable:</td>
    <td>&nbsp; 
    	 <input name="responsable" type="text" id="responsable" size="60"></td>
  </tr>
   <tr>
    <td align='right' class='viewPropTitle'>C.I. Responsable:</td>
    <td>&nbsp; 
    	 <input name="ci_responsable" type="text" id="ci_responsable" size="15"></td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Tel&eacute;fono / Extenci&oacute;n:</td>
    <td>
      &nbsp;
    <input type="text" name="telefono" id="telefono">    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>e-mail:</td>
    <td>&nbsp;
    <input name="email" type="text" id="email" size="45"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><table border="0" align="center" cellpadding="4" cellspacing="0">
      <tr>
        <td>
          <input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" onClick="ingresarDistribucionAlmacen()" class="button">        </td>
        <td>
          <input type="submit" name="boton_modificar" id="boton_modificar" value="Modificar" onClick="modificarDistribucionAlmacen()" style="display:none" class="button">        </td>
        <td>
          <input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" onClick="eliminarDistribucionAlmacen()" style="display:none" class="button">        </td>
      </tr>
    </table></td>
  </tr>
</table>

<br />
<br />
<br />

<div id="listaDistribucionAlmacen">
<?
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
</div>
