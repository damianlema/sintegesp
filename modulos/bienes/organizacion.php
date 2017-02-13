<script src="modulos/bienes/js/organizacion_ajax.js"></script>
	<br>
	<h4 align=center>Organización</h4>
    <h2 class="sqlmVersion"></h2>
	<br />
<center><img src="imagenes/nuevo.png" style="cursor:pointer" onclick="window.location.href='principal.php?modulo=8&accion=764'"></center>
<br />
<input type="hidden" id="idorganizacion" name="idorganizacion">
<table width="60%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td align='right' class='viewPropTitle'>Código:</td>
    <td>
      <input name="codigo" type="text" id="codigo" size="3" maxlength="3">    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Denominación:</td>
    <td colspan="3"><input name="denominacion" type="text" id="denominacion" size="100"></td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Responsable:</td>
    <td colspan="3">
      <input name="responsable" type="text" id="responsable" size="80">    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Estado:</td>
    <td>
      <select name="estado" id="estado" style="cursor:pointer">
        <option value="0">.:: Seleccione ::.</option>
		<?
        $sql_estados = mysql_query("select * from estado where idpais = '1'");
		while($bus_estados = mysql_fetch_array($sql_estados)){
			?>
			<option value="<?=$bus_estados["idestado"]?>" onClick="cambiarMunicipios(this.value)"><?=$bus_estados["denominacion"]?></option>
			<?
		}
		?>
        </select>    </td>
    <td align='right' class='viewPropTitle'>Municipio:</td>
    <td id="celda_municipio">
      <select id="municipio" name="municipio" style="cursor:pointer">
      	<option value="0">.:: Seleccione Primero el Estado ::.</option>
      </select >    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Dirección:</td>
    <td colspan="3"><textarea name="direccion" cols="90" rows="3" id="direccion"></textarea></td>
  </tr>
  
  <tr>
    <td align='right' class='viewPropTitle'>Teléfonos:</td>
    <td colspan="2">
      <input name="telefonos" type="text" id="telefonos" size="50">    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Email:</td>
    <td colspan="2">
      <input name="email" type="text" id="email" size="50">    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><table width="30%" border="0" align="center" cellpadding="4" cellspacing="0">
      <tr>
        <td>
          <input type="button" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onClick="ingresarOrganizacion()">        </td>
        <td>
          <input type="button" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" onClick="modificarOrganizacion()" style="display:none">        </td>
        <td>
          <input type="button" name="boton_eliminar" id="boton_eliminar" value="Eliminar" class="button" onClick="eliminarOrganizacion()" style="display:none">        </td>
      </tr>
    </table></td>
  </tr>
</table>
<br>
<br>
<br>
<div id="listaOrganizaciones">
<?
$sql_consulta = mysql_query("select organizacion.idorganizacion as idorganizacion,
										organizacion.codigo as codigo,
										organizacion.denominacion as denominacion,
										organizacion.responsable as responsable,
										organizacion.direccion as direccion,
										organizacion.telefonos as telefonos,
										organizacion.email,
										estado.idestado,
										municipios.idmunicipios as idmunicipios
										 from organizacion, estado, municipios
										 where 
										 organizacion.idestado = estado.idestado
										 and organizacion.idmunicipio = municipios.idmunicipios")or die(mysql_error());
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="70%" align="center">
    	<thead>
        <tr>
            <td width="15%" align="center" class="Browse">Código</td>
          <td width="50%" align="center" class="Browse">Denominación</td>
          <td align="center" class="Browse" colspan="2">Acciones</td>
        </tr>
        </thead>
        <?
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class='Browse'><?=$bus_consulta["codigo"]?></td>
          	<td class='Browse'><?=$bus_consulta["denominacion"]?></td>
            <td width="1%" align="center" class='Browse'>
            	<img src="imagenes/modificar.png" 
                	style="cursor:pointer" 
                    onClick="seleccionarModificar('<?=$bus_consulta["idorganizacion"]?>','<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["responsable"]?>', '<?=$bus_consulta["idestado"]?>', '<?=$bus_consulta["idmunicipios"]?>', '<?=$bus_consulta["direccion"]?>', '<?=$bus_consulta["telefonos"]?>', '<?=$bus_consulta["email"]?>')">
            </td>
            <td width="1%" align="center" class='Browse'>
            	<img src="imagenes/delete.png" 
                	style="cursor:pointer" 
                    onClick="seleccionarEliminar('<?=$bus_consulta["idorganizacion"]?>','<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["responsable"]?>', '<?=$bus_consulta["idestado"]?>', '<?=$bus_consulta["idmunicipios"]?>', '<?=$bus_consulta["direccion"]?>', '<?=$bus_consulta["telefonos"]?>', '<?=$bus_consulta["email"]?>')">
            </td>
      </tr>
        <?
        }
		?>
        </table>
</div>