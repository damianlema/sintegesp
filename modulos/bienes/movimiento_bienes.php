<script src="modulos/bienes/js/movimiento_bienes_ajax.js"></script>
    <br>
<h4 align=center>
Movimientos Individuales</h4>
<h2 class="sqlmVersion"></h2>
<br>
<input type="hidden" id="idmovimiento" name="idmovimiento">
<br />
<center>
<img src="imagenes/nuevo.png" onclick="window.location.href='principal.php?modulo=8&accion=852'" style="cursor:pointer">
&nbsp;
<img src="imagenes/search0.png" onclick="window.open('modulos/bienes/lib/listar_movimientos_individuales.php','','resizable=no, width=900, height=600, scrollbars=yes')" style="cursor:pointer">
</center>
<br />
<table width="80%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td align="right" class='viewPropTitle'>Tipo</td>
    <td>
      <select name="tipo" id="tipo">
      	<option value="0">.:: Seleccione ::.</option>
        <option value="inmueble" onclick="mostrarInmueble()">Inmueble</option>
        <option value="mueble" onclick="mostrarMueble()">Mueble</option>
      </select>    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>C&oacute;digo del Bien</td>
    <td colspan="3">
    
    <table>
    	<tr>
        	<td>
            <input type="hidden" name="tipo_bien" id="tipo_bien">
            <input type="hidden" name="idbien" id="idbien">
            <input name="codigo_bien" type="text" id="codigo_bien" disabled="disabled">
            </td>
            <td>
            	<img src="imagenes/search0.png" style="cursor:pointer" onclick="" id="imagen_seleccionar_codigo_bien">            </td>
        </tr>
    </table>    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>C&oacute;digo de Catalogo</td>
    <td colspan="3">
      
      <table>
      <tr>
      <td>
          <input type="hidden" name="idcatalogo_bienes" id="idcatalogo_bienes">
          <input name="catalogo_bienes" type="text" id="catalogo_bienes" size="100">      </td>
      <td>&nbsp;</td>
      </tr>
      </table>    </td>
  </tr>
  <tr>
    <td valign="top" align="right" class='viewPropTitle'>Especificaciones</td>
    <td colspan="3">
      <textarea name="especificaciones" cols="100" rows="3" id="especificaciones"></textarea>    </td>
  </tr>
  <tr>
    <td colspan="4" align="left" class='viewPropTitle'><strong>Ubicaci&oacute;n Actual</strong></td>
  </tr>
  <tr>
    <td colspan="4">
    
    
    <table width="100%" border="0" cellspacing="0" cellpadding="4" id="tabla_ubicacion_actual_inmueble" style="display:none">
      <tr>
        <td align="right" class='viewPropTitle'>Organizacion</td>
        <td>
        <select name="organizacion_inmueble" id="organizacion_inmueble" style="width:100%">
        	<option value="0">.:: Seleccione ::.</option>
         	<?
            $sql_organizacion = mysql_query("select * from organizacion");
			while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
				?>
				<option value="<?=$bus_organizacion["idorganizacion"]?>"><?=$bus_organizacion["denominacion"]?></option>
				<?
			}
			?>
         </select>
         
        
        
        </td>
        </tr>
    </table>
      
      
      <table width="100%" border="0" cellspacing="0" cellpadding="4" id="tabla_ubicacion_actual_mueble" style="display:none">
        <tr>
          <td align="right" class='viewPropTitle'>Organizacion</td>
          <td>
            
           <select name="organizacion_mueble" id="organizacion_mueble">
        	<option value="0">.:: Seleccione ::.</option>
         	<?
            $sql_organizacion = mysql_query("select * from organizacion");
			while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
				?>
				<option value="<?=$bus_organizacion["idorganizacion"]?>" onclick="seleccionarNivelOrganizacionalMueble('<?=$bus_organizacion["idorganizacion"]?>')"><?=$bus_organizacion["denominacion"]?></option>
				<?
			}
			?>
         </select>
          
          
          
          </td>
        </tr>
        <tr>
          <td align="right" class='viewPropTitle'>Nivel Organizacional</td>
          <td id="celda_nivel_organizacional_mueble">
            <select name="nivel_organizacional_mueble" id="nivel_organizacional_mueble">
        	<option value="0">.:: Seleccione la Organizacion::.</option>
         	</select>       
          </td>
        </tr>
      </table>      
      
      
      
      </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class='viewPropTitle' colspan="4"><strong>Movimiento</strong></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Nro. de Orden</td>
    <td>
      <input type="text" name="nro_orden" id="nro_orden">    </td>
    <td align="right" class='viewPropTitle'>Fecha de la Orden</td>
    <td><table>
      <tr>
        <td><input name="fecha_orden" type="text" id="fecha_orden" size="12" />        </td>
        <td><img src="imagenes/jscalendar0.gif" name="fecha_f" width="16" height="16" id="fecha_f" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
            <script type="text/javascript">
                                    Calendar.setup({
                                    inputField    : "fecha_orden",
                                    button        : "fecha_f",
                                    align         : "Tr",
                                    ifFormat      : "%Y-%m-%d"
                                    });
                                </script>        </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Tipo Movimiento</td>
    <td colspan="3"><label>
      <select name="idtipo_movimiento" id="idtipo_movimiento" style="width:60%">
      <?
      $sql_tipo_movimiento = mysql_query("select * from tipo_movimiento_bienes where afecta = 1");
	  while($bus_tipo_movimiento = mysql_fetch_array($sql_tipo_movimiento)){
	  	?>
			<option value="<?=$bus_tipo_movimiento["idtipo_movimiento_bienes"]?>"><?=$bus_tipo_movimiento["denominacion"]?></option>
		<?
	  }
	  ?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Nueva Ubicaci&oacute;n</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">
    
    <table width="80%" border="0" cellspacing="0" cellpadding="4" style="display:none" id="tabla_organizacion_destino_inmueble">
      <tr>
        <td align="right" class='viewPropTitle'>Organizacion</td>
        <td>
          <select name="organizacion_destino_inmueble" id="organizacion_destino_inmueble" style="width:100%">
        	<option value="0">.:: Seleccione ::.</option>
         	<?
            $sql_organizacion = mysql_query("select * from organizacion");
			while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
				?>
				<option value="<?=$bus_organizacion["idorganizacion"]?>"><?=$bus_organizacion["denominacion"]?></option>
				<?
			}
			?>
         </select>        </td>
      </tr>
    </table>
      
      
      
      
      
      <table width="100%" border="0" cellspacing="0" cellpadding="4" id="tabla_organizacion_destino_mueble" style="display:none">
        <tr>
          <td align="right" class='viewPropTitle'>Organizacion</td>
          <td>
            
           <select name="organizacion_destino_mueble" id="organizacion_destino_mueble">
        	<option value="0">.:: Seleccione ::.</option>
         	<?
            $sql_organizacion = mysql_query("select * from organizacion");
			while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
				?>
				<option value="<?=$bus_organizacion["idorganizacion"]?>" onclick="seleccionarNivelOrganizacionalDestinoMueble('<?=$bus_organizacion["idorganizacion"]?>')"><?=$bus_organizacion["denominacion"]?></option>
				<?
			}
			?>
         </select>
          
          
          
          </td>
        </tr>
        <tr>
          <td align="right" class='viewPropTitle'>Nivel Organizacional</td>
          <td id="celda_nivel_organizacional_destino_mueble">
            <select name="nivel_organizacional_destino_mueble" id="nivel_organizacional_destino_mueble">
        	<option value="0">.:: Seleccione la Organizacion::.</option>
         	</select>       
          </td>
        </tr>
      </table>      
      
      
        
      
      
      
      
      
      
      
      
      </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Fecha del Movimiento</td>
    <td>
    
    <table>
      <tr>
        <td><input name="fecha_movimiento" type="text" id="fecha_movimiento" size="12" />        </td>
        <td><img src="imagenes/jscalendar0.gif" name="fecha_m" width="16" height="16" id="fecha_m" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
            <script type="text/javascript">
                                    Calendar.setup({
                                    inputField    : "fecha_movimiento",
                                    button        : "fecha_m",
                                    align         : "Tr",
                                    ifFormat      : "%Y-%m-%d"
                                    });
                                </script>        </td>
      </tr>
    </table>    </td>
    <td align="right" class='viewPropTitle'>Fecha de Regreso</td>
    <td>
    
    <table>
      <tr>
        <td><input name="fecha_regreso" type="text" id="fecha_regreso" size="12" />        </td>
        <td><img src="imagenes/jscalendar0.gif" name="fecha_r" width="16" height="16" id="fecha_r" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
            <script type="text/javascript">
                                    Calendar.setup({
                                    inputField    : "fecha_regreso",
                                    button        : "fecha_r",
                                    align         : "Tr",
                                    ifFormat      : "%Y-%m-%d"
                                    });
                                </script>        </td>
      </tr>
    </table>    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Retorno Autom&aacute;tico</td>
    <td><label>
      <input type="checkbox" name="retorno_automatico" id="retorno_automatico" />
    </label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Justificaci&oacute;n del Movimiento</td>
    <td colspan="3"><label>
      <textarea name="justificacion_movimiento" cols="100" rows="3" id="justificacion_movimiento"></textarea>
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><table border="0" align="center" cellpadding="4" cellspacing="0">
      <tr>
        <td>
          <input type="button" name="boton_procesar" id="boton_procesar" value="Procesar Movimiento" class="button" onclick="ingresarMovimiento()"/>        </td>
        <td>
          <input type="button" name="boton_modificar" id="boton_modificar" value="Modificar Movimiento" style="display:none" class="button" onclick="modificarMovimiento()"/>        </td>
        <td>
          <input type="button" name="boton_anular" id="boton_anular" value="Anular Movimiento" style="display:none" class="button" onclick="eliminarMovimiento()"/>        </td>
      </tr>
    </table></td>
  </tr>
</table>
