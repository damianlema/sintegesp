<script src="modulos/recaudacion/js/actividades_comerciales_ajax.js"></script>
 <body onLoad="listarActividades()">
    <br>
<h4 align=center>Actividades Comerciales</h4>
	<h2 class="sqlmVersion"></h2>
 <br>
 <br>
 
 
 <input type="hidden" id="idactividades_comerciales" name="idactividades_comerciales">

 <table width="40%" border="0" align="center">
  <tr>
    <td width="32%" align="right" class='viewPropTitle'>Descripcion</td>
    <td width="68%"><label>
      <input name="descripcion" type="text" id="descripcion" size="60">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Alicuota</td>
    <td><label>
      <input name="alicuota" type="text" id="alicuota" size="10" style="text-align:right">
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><label>
      <table>
          <tr>
              <td><input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" onClick="ingresarActividad()" class="button"></td>
              <td><input type="submit" name="boton_modificar" id="boton_modificar" value="Modificar" style="display:none" onClick="modificarActividad()" class="button"></td>
              <td><input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" style="display:none" onClick="eliminarActividad()" class="button"></td>
          </tr>
      </table>
      
      
      
      
      
      
    </label></td>
   </tr>
</table>

<br>
<br>
<div id="listaActividades"></div>
</body>