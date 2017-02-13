<script src="modulos/recaudacion/js/tipo_solicitud_ajax.js"></script>
 <body onLoad="listarTipoSolicitud()">
    <br>
<h4 align=center>Tipo Solicitud</h4>
	<h2 class="sqlmVersion"></h2>
 <br>
 <br>
 
 
 <input type="hidden" id="idtipo_solicitud" name="idtipo_solicitud">

 <table width="40%" border="0" align="center">
  <tr>
    <td width="32%" align="right" class='viewPropTitle'>Descripcion</td>
    <td width="68%"><label>
      <input name="descripcion" type="text" id="descripcion" size="60">
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
              <td><input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" onClick="ingresarTipoSolicitud()" class="button"></td>
              <td><input type="submit" name="boton_modificar" id="boton_modificar" value="Modificar" style="display:none" onClick="modificarTipoSolicitud()" class="button"></td>
              <td><input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" style="display:none" onClick="eliminarTipoSolicitud()" class="button"></td>
          </tr>
      </table>
      
      
      
      
      
      
    </label></td>
   </tr>
</table>

<br>
<br>
<div id="listaTipoSolicitud"></div>
</body>