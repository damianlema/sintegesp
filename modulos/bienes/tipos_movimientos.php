<script src="modulos/bienes/js/tipos_movimientos_ajax.js"></script>
    <br>
<h4 align=center>
Tipo Movimiento</h4>
<h2 class="sqlmVersion"></h2>
<br>

<input name="id_tipo_movimiento" type="hidden" id="id_tipo_movimiento">
<center><a href="principal.php?accion=<?=$_GET["accion"]?>&amp;modulo=<?=$_GET["modulo"]?>">
	<img src="imagenes/nuevo.png" border="0" title="Nuevo Grupo">
</a></center>
<table width="80%" border="0" align="center">
  <tr>
    <td align="right" class='viewPropTitle'><span class="Browse">C&oacute;digo</span></td>
    <td><label>
      <input name="codigo" type="text" id="codigo" size="3" maxlength="3" autocomplete="OFF">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'><span class="Browse">Denominaci&oacute;n</span></td>
    <td><label>
      <input name="denominacion" type="text" id="denominacion" size="100">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Afecta</td>
    <td><input type="radio" name="afecta" id="afecta_incorporacion" value="1" onclick="document.getElementById('origen_bien_1').checked = false"/>
Incorporaci&oacute;n
<input type="radio" name="afecta" id="afecta_desincorporacion" value="2" onclick="document.getElementById('origen_bien_1').checked = true"/>
Desincorporaci&oacute;n </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Tipo:</td>
    <td>
      <input type="checkbox" name="tipo_inmueble" id="tipo_inmueble" />
    Inmueble 
    <input type="checkbox" name="tipo_mueble" id="tipo_mueble" />
    Mueble
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Origen del Bien:</td>
    <td><table width="200">
      <tr>
        <td><input type="radio" name="origen_bien" value="nuevo" id="origen_bien_0" onclick="document.getElementById('celda_estado').style.display='none'"/></td>
        <td><label> Nuevo</label></td>
        <td><input type="radio" name="origen_bien" value="existente" id="origen_bien_1" onclick="document.getElementById('celda_estado').style.display='block'" /></td>
        <td>Existente</td>
      </tr>
      
    </table>    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Estado del Bien:</td>
    <td>
    	<table width="200" id="celda_estado" style="display:none">
          <tr>
            <td><input type="radio" name="estado_bien" value="activo" id="estado_bien_0" /></td>
            <td><label> Activo</label></td>
            <td><input type="radio" name="estado_bien" value="desincorporado" id="estado_bien_1" /></td>
            <td>Desincorporado</td>
          </tr>
    	</table>    
    </td>
  </tr>
   <tr>
   <td align="right" class='viewPropTitle'>Uso:</td>
    <td ><table width="250">
      <tr>
        <td><input type="radio" name="momento_afectado" value="inicial" id="momento_afectado_0" /></td>
        <td><label>Conteo Inicial</label></td>
        <td><input type="radio" name="momento_afectado" value="movimientos" id="momento_afectado_1" /></td>
        <td>Movimientos</td>
      </tr>
      
    </table>    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Describir Motivo:</td>
    <td><label>
      <input type="checkbox" name="describir_motivo" id="describir_motivo" />
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Memoria Fotografica</td>
    <td><label>
      <input type="checkbox" name="memoria_fotografica" id="memoria_fotografica" />
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Cambia Ubicaci&oacute;n:</td>
    <td><label>
      <input type="checkbox" name="cambia_ubicacion" id="cambia_ubicacion" />
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Formato Impresi&oacute;n:</td>
    <td>
      <select name="formato" id="formato" style="cursor:pointer">
        <option value="0">.:: Seleccione ::.</option>
        <option value="bm1">BM-1</option>
		<option value="bm2i">BM-2-Inc</option>
        <option value="bm2d">BM-2-Des</option>
        <option value="bm3">BM-3</option>
        </select> 
</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="button" 
      		name="botonProcesar" 
            id="botonProcesar" 
            value="Ingresar"
            class="button"
            style="display:block"
            onClick="ingresarTiposMovimientos(document.getElementById('codigo').value,
            						document.getElementById('denominacion').value,
                                    'afecta_incorporacion',
                                    'afecta_desincorporacion')">
      <input type="button"
      		style="display:none" 
      		name="botonModificar" 
            id="botonModificar" 
            value="Modificar"
            class="button"
            onClick="modificarTiposMovimientos(document.getElementById('id_tipo_movimiento').value,
            						document.getElementById('codigo').value,
            						document.getElementById('denominacion').value,
                                    'afecta_incorporacion',
                                    'afecta_desincorporacion')">
      <input type="button"
      		style="display:none" 
      		name="botonEliminar" 
            id="botonEliminar" 
            value="Eliminar"
            class="button"
            onClick="eliminarTiposMovimientos(document.getElementById('id_tipo_movimiento').value)">    </td>
  </tr>
</table>



<br>
<br>

<div id="listaTiposMovimientos">
<?
$sql_consulta = mysql_query("select * from tipo_movimiento_bienes order by codigo");

?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
<thead>
								<tr>
									<td width="13%" align="center" class="Browse">C&oacute;digo</td>
								  <td width="54%" align="center" class="Browse">Denominaci&oacute;n</td>
                                  <td width="20%" align="center" class="Browse">Afecta</td>
                                  <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                <td class='Browse'><?=$bus_consulta["codigo"]?></td>
                                <td class='Browse' align="left"><?=$bus_consulta["denominacion"]?></td>
                  <td class='Browse' align="center">
									<?
                                    if($bus_consulta["afecta"] == 1){
                                        echo "Incorporacion";
                                    }else if($bus_consulta["afecta"] == 2){
                                        echo "Desincorporacion";
                                    }else if($bus_consulta["afecta"] == 3){
										echo "Traslados Incorpora";
									}else{
										echo "Traslados Desincorpora";
									}
                                    ?>
                                </td>
                               <td width="7%" align="center" class='Browse'>
                                	<img src="imagenes/modificar.png" 
                                    	onclick="mostrarEditar('<?=$bus_consulta["idtipo_movimiento_bienes"]?>',
                                        						'<?=$bus_consulta["codigo"]?>',
                                        						'<?=$bus_consulta["denominacion"]?>',
                                                                '<?=$bus_consulta["afecta"]?>',
                                                                '<?=$bus_consulta["tipo_mueble"]?>', 
                                                                '<?=$bus_consulta["tipo_inmueble"]?>',
                                                                '<?=$bus_consulta["origen_bien"]?>',
                                                                '<?=$bus_consulta["estado_bien"]?>',
                                                                '<?=$bus_consulta["describir_motivo"]?>', 
                                                                '<?=$bus_consulta["memoria_fotografica"]?>',
                                                                '<?=$bus_consulta["cambia_ubicacion"]?>',
                                                                '<?=$bus_consulta["uso"]?>',
                                                                '<?=$bus_consulta["formato"]?>')" 
                                        style="cursor:pointer">                                </td>
                          <td width="6%" align="center" class='Browse'>
                                	<img src="imagenes/delete.png" 
                                    style="cursor:pointer"
                                    onclick="mostrarEliminar('<?=$bus_consulta["idtipo_movimiento_bienes"]?>',
                                        						'<?=$bus_consulta["codigo"]?>',
                                        						'<?=$bus_consulta["denominacion"]?>',
                                                                '<?=$bus_consulta["afecta"]?>',
                                                                '<?=$bus_consulta["tipo_mueble"]?>', 
                                                                '<?=$bus_consulta["tipo_inmueble"]?>',
                                                                '<?=$bus_consulta["origen_bien"]?>',
                                                                '<?=$bus_consulta["estado_bien"]?>',
                                                                '<?=$bus_consulta["describir_motivo"]?>', 
                                                                '<?=$bus_consulta["memoria_fotografica"]?>',
                                                                '<?=$bus_consulta["cambia_ubicacion"]?>',
                                                                '<?=$bus_consulta["uso"]?>',
                                                                '<?=$bus_consulta["formato"]?>')">                                </td>
                          </tr>
							<?
							}
							?>
							
								
						</table>
                        
					
      </td>
        </tr>
    </table>
</div>



