<script src="modulos/recaudacion/js/requisitos_ajax.js"></script>
 <body onLoad="listarRequisitos()">
    <br>
<h4 align=center>Requisitos</h4>
	<h2 class="sqlmVersion"></h2>
 <br>
 <br>
 
 
 <input type="hidden" id="idrequisitos" name="idrequisitos">

 <table width="50%" border="0" align="center">
  <tr>
    <td width="32%" align="right" class='viewPropTitle'>Descripcion</td>
    <td width="68%"><label>
      <input name="descripcion" type="text" id="descripcion" size="60">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Vencimiento</td>
    <td><label>
      <input type="checkbox" name="vencimiento" id="vencimiento">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Bloquea proceso</td>
    <td><label>
      <input type="checkbox" name="bloquea_proceso" id="bloquea_proceso">
    </label></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><label>
      <table>
          <tr>
              <td><input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" onClick="ingresarRequisitos()" class="button"></td>
              <td><input type="submit" name="boton_modificar" id="boton_modificar" value="Modificar" style="display:none" onClick="modificarRequisitos()" class="button"></td>
              <td><input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" style="display:none" onClick="eliminarRequisitos()" class="button"></td>
          </tr>
      </table>
      
      
      
      
      
      
    </label></td>
   </tr>
</table>

<br>
<br>
<div id="listaRequisitos"></div>
</body>