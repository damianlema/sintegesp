<script src="modulos/nomina/js/tipo_hoja_tiempo_ajax.js"></script>
	<br>
	<h4 align=center>Tipos de Hoja de Tiempo</h4><br>

	<h2 class="sqlmVersion"></h2>
	<br>
    <br>
    <div align="center">
    	<img src="imagenes/nuevo.png" style="cursor:pointer" onclick="window.location.href='principal.php?accion=944&modeulo=13'"> &nbsp;
        <img src="imagenes/imprimir.png" style="cursor:pointer" title="Imprimir" onclick="document.getElementById('divImprimir').style.display='block'; pdf.location.href='lib/reportes/nomina/reportes.php?nombre=nomina_tipo_hoja_tiempo'; document.getElementById('pdf').style.display='block';">
        
        <div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; width:50%; left:25%">
            <div align="right">
                <a href="#" onClick="document.getElementById('divImprimir').style.display='none'; document.getElementById('pdf').style.display='none';">X</a>
            </div>
            <iframe name="pdf" id="pdf" style="display:none; width:99%; height:550px;"></iframe>   
        </div>
	</div>
    <br />
<br />


<input type="hidden" id="idtipo_hoja_tiempo" name="idconcepto_nomina">
<table width="40%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>Denominacion</td>
    <td><label>
      <input name="descripcion" type="text" id="descripcion" size="50">
    </label></td>
  </tr>
  <tr>
    <td>Unidad:</td>
    <td><select id="unidad" name="unidad">
      <option selected="selected" value="Dias">D&iacute;as</option>
      <option value="Meses">Meses</option>
      <option value="Anos">A&ntilde;os</option>
      <option value="Bolivares">Bolivares</option>
    </select></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
    
    
    <table border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
		<td><input type="button" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onClick="ingresarHojaTiempo()"></td>
        <td><input type="button" name="boton_modificar" id="boton_modificar" value="Modificar" style="display:none" class="button" onClick="modificarHojaTiempo()"></td>
        <td><input type="button" name="boton_eliminar" id="boton_eliminar" value="Eliminar" style="display:none" class="button" onClick="eliminarHojaTiempo()"></td>
      </tr>
    </table>
    
    
    </td>
  </tr>
</table>








<br>
<br>
<br>
<div id="lista_conceptos">
				<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="60%">
<thead>
          <tr>
            <td width="56%" align="center" class="Browse">Descripcion</td>
            <td width="56%" align="center" class="Browse">Unidad</td>
            <td align="center" class="Browse" colspan="2">Accion</td>          
          </tr>
          </thead>    
          <?
          $sql_consulta = mysql_query("select * from tipo_hoja_tiempo");
		  while($bus_consulta = mysql_fetch_array($sql_consulta)){
		  ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class='Browse' align='left'><?=$bus_consulta["descripcion"]?>&nbsp;</td>
            <td class='Browse' align='left'>
			<?
            if($bus_consulta["unidad"] =="Anos"){
				echo "A&ntilde;os";
			}else{
				echo $bus_consulta["unidad"];	
			}
			?>&nbsp;</td>
            <td width="5%" align='center' class='Browse'>
            	<img src="imagenes/modificar.png" onClick="mostrarModificar('<?=$bus_consulta["idtipo_hoja_tiempo"]?>',
                															'<?=$bus_consulta["descripcion"]?>',
                                                                            '<?=$bus_consulta["unidad"]?>')" style="cursor:pointer">
            </td>
            <td width="6%" align='center' class='Browse'>
            	<img src="imagenes/delete.png" onClick="mostrarEliminar('<?=$bus_consulta["idtipo_hoja_tiempo"]?>',
                															'<?=$bus_consulta["descripcion"]?>',
                                                                            '<?=$bus_consulta["unidad"]?>')" style="cursor:pointer">
            </td>
  </tr>
         <?
         }
		 ?>
         </table>


</div>
