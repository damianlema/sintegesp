<script src="modulos/utilidades/js/firmas_reportes_ajax.js"></script>
<table align="center" border="0">
	<th colspan="2">Configuracion Reportes</th>
    <tr>
    <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td align='right' class='viewPropTitle'>Tipo de Documento</td>
        <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; color:#990000">
        <?
        $sql_documentos= mysql_query("select * from tipos_documentos");
		?>
        <select name="reportes" id="reportes">
        	<option value="0">.:: Seleccione ::.</option>
            <?
            while($bus_documentos= mysql_fetch_array($sql_documentos)){
			?>
            <option value="<?=$bus_documentos["idtipos_documentos"]?>" onClick="document.getElementById('detalle_configuracion_reportes').innerHTML = '', mostrarTiposReportes(<?=$bus_documentos["idtipos_documentos"]?>)"><?=$bus_documentos["descripcion"]?></option>
            <?
            }
			?>
        </select>        </td>
    </tr>
    <tr>
        <td align='right' class='viewPropTitle'>Tipos de Reportes <input type="hidden" id="cantidad_firmas" name="cantidad_firmas"></td>
        <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; color:#990000" id="celdaTiposReportes"></td>
    </tr>
    <tr>
        <td id="detalle_configuracion_reportes" colspan="2"></td>
    </tr>
    <tr>    
        <td colspan="2" align="center">
          <input type="submit" 
          			name="botonCambiarReportes" 
                    id="botonCambiarReportes" 
                    value="Guardar Datos" 
                    onClick="procesarConfiguracion()"
                    class="button"/>        </td>
    </tr>
</table>
