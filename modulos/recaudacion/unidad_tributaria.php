<script src="modulos/recaudacion/js/unidad_tributaria_ajax.js"></script>

<body onLoad="listarUnidades()">
<input type="hidden" id="idunidad_tributaria" name="idunidad_tributaria" value="">
    <br>
<h4 align=center>Unidad Tributaria</h4>
	<h2 class="sqlmVersion"></h2>
 <br>
 <br>
<table width="30%" border="0" align="center">
  <tr>
    <td width="36%" align="right" class='viewPropTitle'>Año</td>
    <td width="64%"><label>
      <select name="anio" id="anio">
      	<?
		for($i=1994;$i<2100;$i++){
			?>
			<option value="<?=$i?>" <?php if($i == date('Y')) {echo ' selected';}?>><?=$i?></option>
			<?
		}
		?>
        
      </select>
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Desde</td>
    <td>
      <input name="desde" type="text" id="desde" size="12" readonly="readonly">
      <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "desde",
                                button        : "f_trigger_c",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script>
    </td>
  </tr>
  <tr>
    <td align="right"class='viewPropTitle'>Hasta</td>
    <td><input name="hasta" type="text" id="hasta" size="12" readonly="readonly">
      <img src="imagenes/jscalendar0.gif" name="f_trigger_h" width="16" height="16" id="f_trigger_h" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "hasta",
                                button        : "f_trigger_h",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Costo</td>
    <td><label>
      <input name="costo" type="text" id="costo" size="10">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Nro. Gaceta</td>
    <td><label>
      <input name="gaceta" type="text" id="gaceta" size="10">
    </label></td>
  </tr>
   <tr>
    <td align="right" class='viewPropTitle'>Fecha Gaceta</td>
    <td><label>
      <input name="fecha_gaceta" type="text" id="fecha_gaceta" size="12">
       <img src="imagenes/jscalendar0.gif" name="f_trigger_h" width="16" height="16" id="f_trigger_h" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "fecha_gaceta",
                                button        : "f_trigger_g",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script>
    </label></td>
  </tr>
  <tr>
    <td colspan="2"><table border="0" align="center">
      <tr>
        <td><label>
          <input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onClick="ingresar_unidad()">
        </label></td>
        <td><label>
          <input type="submit" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" style="display:none" onClick="modificarUnidad()">
        </label></td>
        <td><label>
          <input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" class="button" style="display:none" onClick="eliminarUnidad()">
        </label></td>
      </tr>
    </table></td>
  </tr>
</table>
<br>
<br>
<div id="listaUnidades"></div>
</body>