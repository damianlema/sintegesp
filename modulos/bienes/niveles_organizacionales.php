
<script src="modulos/bienes/js/niveles_organizacionales_ajax.js"></script>
	<br>
	<h4 align=center>Niveles Organizacionales</h4>
	<h2 class="sqlmVersion"></h2>
    <br>
<center><img src="imagenes/nuevo.png" style="cursor:pointer" onclick="window.location.href='principal.php?modulo=8&accion=765'"></center>
<br />
<input type="hidden" id="idorganizacion" name="idorganizacion">
<input type="hidden" id="idniveles_organizacionales" name="idniveles_organizacionales">
<table width="60%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td width="24%" align='right' class='viewPropTitle'>Organizaci&oacute;n:</td>
    <td width="76%">
      &nbsp;
      <select name="organizacion" id="organizacion" onChange="cargarSubNiveles(this.value)">
        <option value="0" onclick="document.getElementById('codigoOrganizacion').innerHTML = ''">.:: Seleccione ::.</option>
    	  <?
        $sql_organizacion = mysql_query("select * from organizacion");
    		while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
    			$sql_tiene_sub = mysql_query("select * from niveles_organizacionales where organizacion = '".$bus_organizacion["idorganizacion"]."'")or die(mysql_error());
    			$num_tiene_sub = mysql_num_rows($sql_tiene_sub);
    			?>
    			<option value="<?=$bus_organizacion["idorganizacion"].".|.".$num_tiene_sub.".|.".$bus_organizacion["codigo"]?>">
                  <?=$bus_organizacion["denominacion"]?>
          </option>
    			<?
		    }
	  ?>
      </select>    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Sub-Nivel:</td>
    <td id="celda_sub_nivel">
    &nbsp;
      <select name="sub_nivel" id="sub_nivel" disabled="disabled">
        <option value="0">NINGUNO</option>
      	<?
          $sql_sub_nivel = mysql_query("select * from niveles_organizacionales where modulo = '".$_SESSION["modulo"]."'");
      	while($bus_sub_nivel = mysql_fetch_array($sql_sub_nivel)){
      		?>
      		<option value="<?=$bus_sub_nivel["idniveles_organizacionales"]?>" onclick="document.getElementById('codigoSubNivel').innerHTML = '<?=substr($bus_sub_nivel["codigo"], 3, strlen($bus_sub_nivel["codigo"]))?>'"><?=$bus_sub_nivel["denominacion"]?></option>
      		<?
      	}
      	?>
      </select>
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>C&oacute;digo:</td>
    <td>
    	<table border="0" style="">
        <tr>
        <td id="codigoOrganizacion" style="font-weight:bold"></td>
        <td id="codigoSubNivel" style="font-weight:bold"></td>
      	<td><input name="codigo" type="text" id="codigo" maxlength="4"></td>
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
    <td align='right' class='viewPropTitle'>Email:</td>
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
          <input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" onClick="ingresarNivelesOrganizacionales()" class="button">        </td>
        <td>
          <input type="submit" name="boton_modificar" id="boton_modificar" value="Modificar" onClick="modificarNivelesOrganizacionales()" style="display:none" class="button">        </td>
        <td>
          <input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" onClick="eliminarNivelesOrganizacionales()" style="display:none" class="button">        </td>
      </tr>
    </table></td>
  </tr>
</table>

<br />
<br />
<br />

<div id="listaNivelesOrganizacionales">
<?
$sql_consulta = mysql_query("select niveles_organizacionales.organizacion,
									niveles_organizacionales.codigo,
									niveles_organizacionales.denominacion,
									niveles_organizacionales.responsable,
									niveles_organizacionales.ci_responsable,
									niveles_organizacionales.sub_nivel,
									niveles_organizacionales.telefono,
									niveles_organizacionales.email,
									niveles_organizacionales.idniveles_organizacionales,
									organizacion.denominacion as denominacion_organizacion,
									organizacion.codigo as codigo_organizacion 
										from 
									niveles_organizacionales, organizacion
										where 
									organizacion.idorganizacion = niveles_organizacionales.organizacion
									and niveles_organizacionales.modulo= '".$_SESSION["modulo"]."'
										order by niveles_organizacionales.organizacion, niveles_organizacionales.codigo ")or die(mysql_error());
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="70%" align="center">
   	  <thead>
        <tr>
          <td width="39%" align="center" class="Browse">Organizaci&oacute;n</td>
          <td width="15%" align="center" class="Browse">C&oacute;digo</td>
          <td width="50%" align="center" class="Browse">Denominaci&oacute;n</td>
          <td align="center" class="Browse" colspan="2">Acciones</td>
        </tr>
        </thead>
        <?
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
          $sql_tiene_sub = mysql_query("select * from niveles_organizacionales where organizacion = '".$bus_consulta["organizacion"]."'")or die(mysql_error());
          $num_tiene_sub = mysql_num_rows($sql_tiene_sub);
		    ?>   
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
          	<td class='Browse'><?=$bus_consulta["denominacion_organizacion"]?></td>
            <td class='Browse'><?=$bus_consulta["codigo_organizacion"]?>-<?=$bus_consulta["codigo"]?></td>
          	<td class='Browse'><?=$bus_consulta["denominacion"]?></td>
            <td width="1%" align="center" class='Browse'>
            	<img src="imagenes/modificar.png" 
                	style="cursor:pointer" 
                    onClick="seleccionarModificar('<?=$bus_consulta["organizacion"]?>',
                                                  '<?=$bus_consulta["codigo_organizacion"]?>-<?=$bus_consulta["codigo"]?>',
                                                  '<?=$bus_consulta["denominacion"]?>',
                                                  '<?=$bus_consulta["responsable"]?>',
                                                  '<?=$bus_consulta["ci_responsable"]?>',
                                                  '<?=$bus_consulta["sub_nivel"]?>',
                                                  '<?=$bus_consulta["telefono"]?>',
                                                  '<?=$bus_consulta["email"]?>',
                                                  '<?=$bus_consulta["idniveles_organizacionales"]?>',
                                                  '<?=$bus_consulta["codigo_organizacion"]?>',
                                                  '<?=$num_tiene_sub?>')">
            </td>
            <td width="1%" align="center" class='Browse'>
            	<img src="imagenes/delete.png" 
                	style="cursor:pointer" 
                    onClick="seleccionarEliminar('<?=$bus_consulta["organizacion"]?>',
                                                  '<?=$bus_consulta["codigo_organizacion"]?>-<?=$bus_consulta["codigo"]?>',
                                                  '<?=$bus_consulta["denominacion"]?>',
                                                  '<?=$bus_consulta["responsable"]?>',
                                                  '<?=$bus_consulta["ci_responsable"]?>',
                                                  '<?=$bus_consulta["sub_nivel"]?>',
                                                  '<?=$bus_consulta["telefono"]?>',
                                                  '<?=$bus_consulta["email"]?>',
                                                  '<?=$bus_consulta["idniveles_organizacionales"]?>',
                                                  '<?=$bus_consulta["codigo_organizacion"]?>',
                                                  '<?=$num_tiene_sub?>')">
            </td>
          </tr>
        <?
        }
		?>
        </table>
</div>
