<script src="modulos/rrhh/js/vacaciones_ajax.js"></script>


<br>
	<h4 align=center>Vacaciones</h4>
    <h2 class="sqlmVersion"></h2>
    <br>
    
    
    
    
    <table width="60%" align="center">
      <tr>
        <td width="254" align='right' class='viewPropTitle'>Cedula</td>
        <td width="144"><input type="hidden" id="id_trabajador" name="id_trabajador" />
            <input type="hidden" id="idvacaciones" name="idvacaciones" />
            <input type="text" id="cedula_trabajador" name="cedula_trabajador" disabled="disabled"/>
            <img src="imagenes/search0.png" style="cursor:pointer" onclick="window.open('lib/listas/lista_trabajador.php?frm=vacaciones','','width=900, height=600, scrollbars=yes')" /></td>
        <td width="247">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="26%" align='right' class='viewPropTitle'>Nombre:</td>
              <td width="22%"><input type="text" name="nombre_trabajador" id="nombre_trabajador" disabled="disabled"/>
              </td>
              <td width="13%" align='right' class='viewPropTitle'>Apellido</td>
              <td width="39%"><input type="text" name="apellido_trabajador" id="apellido_trabajador" disabled="disabled"/>
              </td>
            </tr>
        </table></td>
      </tr>
    </table>
<br>
<h2 class="sqlmVersion"></h2>
<br>
<form method="post" action="" name="formularioVacaciones">
<table align="center" width="70%">
<tr>
    <td align='right' class='viewPropTitle'>Fecha de Salida</td>
    <td>
    <input type="text" size="12" name="fecha_salida" id="fecha_salida">
    <img src="imagenes/jscalendar0.gif" name="f_trigger_c" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
              <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "fecha_salida",
                                button        : "f_trigger_c",
                                align         : "Tr",
                                ifFormat    	: "%Y-%m-%d"
                                });
                            </script>  
    </td>
    <td align='right' class='viewPropTitle'>Fecha de Reintegro</td>
    <td>
    <input type="text" size="12" name="fecha_reintegro" id="fecha_reintegro">
    <img src="imagenes/jscalendar0.gif" name="f_trigger_r" id="f_trigger_r" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
              <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "fecha_reintegro",
                                button        : "f_trigger_r",
                                align         : "Tr",
                                ifFormat    	: "%Y-%m-%d"
                                });
                            </script>  
    </td>
</tr>
<tr>
	<td colspan="4" class='viewPropTitle'>Comentarios</td>
</tr>
<tr>
	<td colspan="4"><textarea name="comentarios" id="comentarios" cols="120" rows="5"></textarea></td>
</tr>
<tr>
	<td colspan="4" align="center">
    
    <table id="tabla_botones">
    	<tr>
        <td><input type="button" id="boton_ingresar" name="boton_ingresar" value="Ingresar" class="button" onClick="ingresarVacaciones()"></td>
        <td><input type="button" id="boton_modificar" name="boton_modificar" value="Modificar" class="button" style="display:none" onclick="modificarVacaciones()"></td>
        <td><input type="button" id="boton_eliminar" name="boton_eliminar" value="Eliminar" class="button" style="display:none"></td>
        </tr>
    </table>
    
    </td>
</tr>
</table>
</form>

<br>
<br>
<div id="listaVacaciones"></div>


