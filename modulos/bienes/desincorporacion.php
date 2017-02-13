<script src="modulos/bienes/js/desincorporacion_ajax.js"></script>

    <br>
<h4 align=center>Desincorporacion de Bienes</h4>
<h2 class="sqlmVersion"></h2>
    <br>
    <br />
	<center><img src="imagenes/search0.png" onclick="window.open('lib/listas/listar_desincorporacion.php', '', 'width=900, height=600, scrollbars = yes')" style="cursor:pointer"></center>
 <br>
<input type="hidden" id="iddesincorporacion" name="iddesincorporacion">
    <table width="70%" border="0" align="center">
      <tr>
        <td align="right" class='viewPropTitle'>Numero de Planilla</td>
        <td id="numero_planilla" style="font-weight:bold">Aun no Generado</td>
      </tr>
      <tr>
        <td align="right" class='viewPropTitle'>Fecha de Proceso</td>
        <td id="fecha_proceso"><?=date("Y-m-d")?></td>
      </tr>
      <tr>
        <td align="right" class='viewPropTitle'>Estado</td>
        <td id="estado_planilla" style="font-weight:bold">Elaboracion</td>
      </tr>
      <tr>
        <td width="20%" align="right" class='viewPropTitle'>Justificacion</td>
        <td width="80%"><label>
          <textarea name="justificacion" cols="80" rows="3" id="justificacion"></textarea>
        </label></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center">
        
        <table border="0">
          <tr>
            <td>
              <input type="submit" name="boton_siguiente" id="boton_siguiente" value="Siguiente" class="button" onClick="ingresarDatosBasicos()">            </td>
            <td>
              <input type="submit" name="boton_procesar" id="boton_procesar" value="Procesar" class="button" style="display:none" onClick="procesarDesincorporacion()">            </td>
            <td>
              <input type="submit" name="boton_anular" id="boton_anular" value="Anular" class="button" style="display:none" onClick="anularOrden()">            </td>
          </tr>
        </table>        </td>
      </tr>
    </table>
    <br>
    <br>
    
    <table align="center" style="display:none" id="datos_extra" width="800" border="0">
    <tr>
    <td align="center" width="800">
    
    <table width="40%" border="0" align="center" id="tabla_campos_muebles">
      <tr>
        <td width="3%" align="right"><img src="imagenes/search0.png" width="16" height="16" onClick="window.open('lib/listas/listar_muebles.php?destino=desincorporacion','listar_muebles','width=900, height=600, scrollbars=yes')" style="cursor:pointer"></td>
        <td width="93%">Mueble</td>
        <td width="93%"><label>
          <input name="codigo_mueble" type="text" id="codigo_mueble" size="15" readonly>
        </label></td>
        <td width="93%"><input name="nombre_mueble" type="text" id="nombre_mueble" size="80" readonly>
            <input type="hidden" name="idmueble" id="idmueble">        </td>
        <td width="1%" align="left">&nbsp;</td>
        <td width="3%" align="left"><img src="imagenes/validar.png" width="16" height="16" onClick="ingresarMueble()"></td>
      </tr>
    </table>
    
    </td>
    </tr>
    <tr>
    <td id="lista_seleccionados" align="center" width="800"></td>
    </tr>
</table>
