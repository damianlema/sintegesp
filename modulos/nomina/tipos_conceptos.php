<script src="modulos/nomina/js/tipos_conceptos_nomina_ajax.js"></script>
	<br>
	<h4 align=center>Tipos de Afectaci&oacute;n</h4><br>

	<h2 class="sqlmVersion"></h2>
	<br>
    <br>
    <div align="center">
    	<img src="imagenes/nuevo.png" style="cursor:pointer" onclick="window.location.href='principal.php?accion=878&modeulo=13'"> &nbsp;
        <img src="imagenes/imprimir.png" style="cursor:pointer" title="Imprimir" onclick="document.getElementById('divImprimir').style.display='block'; pdf.location.href='lib/reportes/nomina/reportes.php?nombre=nomina_tipos_conceptos_lista'; document.getElementById('pdf').style.display='block';">
        
        <div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; width:50%; left:25%">
            <div align="right">
                <a href="#" onClick="document.getElementById('divImprimir').style.display='none'; document.getElementById('pdf').style.display='none';">X</a>
            </div>
            <iframe name="pdf" id="pdf" style="display:none; width:99%; height:550px;"></iframe>   
        </div>
	</div>
    <br />
<br />

    

<input type="hidden" id="idconcepto_nomina" name="idconcepto_nomina">
<table width="40%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>Codigo</td>
    <td><label>
      <input type="text" name="codigo" id="codigo">
    </label></td>
  </tr>
  <tr>
    <td>Denominacion</td>
    <td><label>
      <input name="descripcion" type="text" id="descripcion" size="50">
    </label></td>
  </tr>
  <tr>
    <td>Tipo de Afectaci&oacute;n</td>
    <td>
      <select name="afecta" id="afecta">
        <option value="Asignacion">Asignacion</option>
        <option value="Deduccion">Deduccion</option>
        <option value="Neutro">Neutro</option>
        <option value="Aporte">Aporte</option>
      </select>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
    
    
    <table border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
		<td><input type="button" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onClick="ingresarConcepto()"></td>
        <td><input type="button" name="boton_modificar" id="boton_modificar" value="Modificar" style="display:none" class="button" onClick="modificarConcepto()"></td>
        <td><input type="button" name="boton_eliminar" id="boton_eliminar" value="Eliminar" style="display:none" class="button" onClick="eliminarConcepto()"></td>
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
          	<td width="16%" align="center" class="Browse">Codigo</td>
            <td width="56%" align="center" class="Browse">Descripcion</td>
            <td width="56%" align="center" class="Browse">Afecta</td>
            <td align="center" class="Browse" colspan="2">Accion</td>          
          </tr>
          </thead>    
          <?
          $sql_consulta = mysql_query("select * from tipo_conceptos_nomina");
		  while($bus_consulta = mysql_fetch_array($sql_consulta)){
		  ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class='Browse' align='left'><?=$bus_consulta["codigo"]?></td>
            <td class='Browse' align='left'><?=$bus_consulta["descripcion"]?></td>
            <td class='Browse' align='left'><?=$bus_consulta["afecta"]?></td>
            <td width="5%" align='center' class='Browse'>
            	<img src="imagenes/modificar.png" onClick="mostrarModificar('<?=$bus_consulta["idconceptos_nomina"]?>',
                															'<?=$bus_consulta["codigo"]?>',
                                                                            '<?=$bus_consulta["descripcion"]?>',
                                                                            '<?=$bus_consulta["afecta"]?>')" style="cursor:pointer">
            </td>
            <td width="6%" align='center' class='Browse'>
            	<img src="imagenes/delete.png" onClick="mostrarEliminar('<?=$bus_consulta["idconceptos_nomina"]?>',
                															'<?=$bus_consulta["codigo"]?>',
                                                                            '<?=$bus_consulta["descripcion"]?>',
                                                                            '<?=$bus_consulta["afecta"]?>')" style="cursor:pointer">
            </td>
  </tr>
         <?
         }
		 ?>
         </table>


</div>
