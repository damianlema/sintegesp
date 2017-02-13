<script src="modulos/rrhh/js/nomenclatura_ficha_ajax.js"></script>
 <body onLoad="listarNomenclatura()">
    <br>
<h4 align=center>Nomenclatura para las Fichas</h4>
	<h2 class="sqlmVersion"></h2>
 <br>
 <br>
 
 
 <input type="hidden" id="idnomenclatura_fichas" name="idnomenclatura_fichas">

 <table width="40%" border="0" align="center">
  <tr>
    <td width="32%" align="right" class='viewPropTitle'>Descripcion</td>
    <td width="68%"><label>
      <input name="descripcion" type="text" id="descripcion" size="4" maxlength="2">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Numero</td>
    <td><label>
      <input name="numero" type="text" id="numero" size="10" style="text-align:right">
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
              <td><input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" onClick="ingresarNomenclatura()" class="button"></td>
              <td><input type="submit" name="boton_modificar" id="boton_modificar" value="Modificar" style="display:none" onClick="modificarNomenclatura()" class="button"></td>
              <td><input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" style="display:none" onClick="eliminarNomenclatura()" class="button"></td>
          </tr>
      </table>
      
      
      
      
      
      
    </label></td>
   </tr>
</table>

<br>
<br>
<div id="listaNomenclatura"></div>
</body>