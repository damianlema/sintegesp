<script src="modulos/caja_chica/js/tipo_caja_chica_ajax.js"></script>

    <br>
<h4 align=center>Tipo de Caja Chica</h4>
<h2 class="sqlmVersion"></h2>
<br />
<center>
</center>
<br />


<div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
<table align="center">
	<tr><td align="right"><a href="#" onClick="document.getElementById('divImprimir').style.display='none';">X</a></td></tr>
   	<tr><td><iframe name="pdf" id="pdf" style="display:none" height="600" width="750"></iframe></td></tr>
</table>
</div>

<div align="center"><img src="imagenes/nuevo.png" title="Nuevo tipo de Caja Chica" onclick="window.location.href='principal.php?modulo=<?=$_GET["modulo"]?>&accion=<?=$_GET["accion"]?>'" style="cursor:pointer" />&nbsp;
    <img src="imagenes/imprimir.png" title="Imprimir Tipos de Caja Chica"  onClick="pdf.location.href='lib/reportes/caja_chica/reportes.php?modulo=<?=$_SESSION["modulo"]?>&nombre=tipos_caja_chica'; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block';" style="cursor:pointer" /></div>
<br />


<input type="hidden" name="idtipo_caja_chica" id="idtipo_caja_chica">

<table width="60%" border="0" align="center">
  <tr>
    <td align="right" class='viewPropTitle'>Denominacion:</td>
    <td><label>
      <input name="denominacion" type="text" id="denominacion" size="40">
    </label></td>
    <td align="right" class='viewPropTitle'>UT. Aprobadas:</td>
    <td><label>
      <input name="ut_aprobadas" type="text" id="ut_aprobadas" onblur="document.getElementById('maximo_reponer').value= this.value" size="5">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Resolucion Nro:</td>
    <td><label>
      <input type="text" name="resolucion_nro" id="resolucion_nro">
    </label></td>
    <td align="right" class='viewPropTitle'>Fecha de Resolucion:</td>
    <td>
      <input name="fecha_resolucion" type="text" id="fecha_resolucion" size="12">
    <img src="imagenes/jscalendar0.gif" name="f_trigger_a" width="16" height="16" id="f_trigger_a" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "fecha_resolucion",
                                button        : "f_trigger_a",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script>
    
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Gaceta Nro:</td>
    <td><label>
      <input type="text" name="gaceta_nro" id="gaceta_nro">
    </label></td>
    <td align="right" class='viewPropTitle'>Fecha de Gaceta:</td>
    <td>
      <input name="fecha_gaceta" type="text" id="fecha_gaceta" size="12">
    <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "fecha_gaceta",
                                button        : "f_trigger_c",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script>
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Minimo a Reponer:</td>
    <td><label>
      <input name="minimo_reponer" type="text" id="minimo_reponer" size="5">
    </label></td>
    <td align="right" class='viewPropTitle'>Maximo a Reponer:</td>
    <td><label>
      <input name="maximo_reponer" type="text" id="maximo_reponer" size="5">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>UT Maximas por Facturas:</td>
    <td><label>
      <input name="ut_maximas_factura" type="text" id="ut_maximas_factura" size="5">
    </label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center"><table border="0">
      <tr>
        <td>
          <input type="button" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onClick="ingresarTipoCajaChica()">
        </td>
        <td>
          <input type="button" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" onClick="modificarTipoCajaChica()" style="display:none">
        </td>
        <td>
          <input type="button" name="boton_eliminar" id="boton_eliminar" value="Eliminar" class="button" onClick="eliminarTipoCajaChica()" style="display:none">
        </td>
      </tr>
    </table></td>
  </tr>
</table>



<br>
<br>


<div id="lista_tipo_caja_chica">
<?
$sql_consultar = mysql_query("select * from tipo_caja_chica"); 
?>
<table border="0" class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
        	<thead>
            <tr>
            	<td width="26%" class="Browse" align="center">Denominacion</td>
                <td width="29%" class="Browse" align="center">UT. Aprobadas</td>
                <td width="15%" class="Browse" align="center">Resolucion Nro</td>
                <td width="19%" class="Browse" align="center">Gaceta Nro</td>
                <td width="11%" class="Browse" align="center" colspan="2">Acci&oacute;n</td>
            </tr>
            </thead>
            <? while($bus_consultar = mysql_fetch_array($sql_consultar)){?>
            	<tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="left" class="Browse"><?=$bus_consultar["denominacion"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["unidades_tributarias_aprobadas"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["resolucion_nro"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["gaceta_nro"]?></td>
                <td class="Browse" align="center">
                	<img src="imagenes/modificar.png" style="cursor:pointer" alt='Modificar' title='Modificar' 
                    onclick="seleccionarDatos('<?=$bus_consultar["idtipo_caja_chica"]?>',
                    							'<?=$bus_consultar["denominacion"]?>',
                                                '<?=$bus_consultar["unidades_tributarias_aprobadas"]?>',
                                                '<?=$bus_consultar["resolucion_nro"]?>',
                                                '<?=$bus_consultar["fecha_resolucion"]?>',
                                                '<?=$bus_consultar["gaceta_nro"]?>',
                                                '<?=$bus_consultar["fecha_gaceta"]?>',
                                                '<?=$bus_consultar["minimo_reponer"]?>',
                                                '<?=$bus_consultar["maximo_reponer"]?>',
                                       	         '<?=$bus_consultar["ut_maxima_factura"]?>')">
                </td>
                <td class="Browse" align="center">
                	<img src="imagenes/delete.png" style="cursor:pointer" alt='Eliminar' title='Eliminar' onclick="eliminarTipoCajaChica('<?=$bus_consultar["idtipo_caja_chica"]?>')"></td>
          </tr>
            <? } ?>
        </table>

</div>


