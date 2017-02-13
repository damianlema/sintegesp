<script src="modulos/contabilidad/js/grupo_ajax.js"></script>

    <br>
<h4 align=center>
Grupo
</h4>
<h2 class="sqlmVersion"></h2>
<br>

<center>
<a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/<?=$_SESSION["rutaReportes"]?>/contabilidad.php?nombre=contabilidad_grupo';">
    <img src="imagenes/imprimir.png" border="0" title="Imprimir">
</a>
</center>
<table align="center" width="50%">
	<tr>
    	<td>
            <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
                <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none';">X</a></div>
                <iframe name="pdf" id="pdf" style="display:block" height="500" width="500"></iframe>          
            </div>
        </td>
	</tr>
</table>

<input name="idgrupo" type="hidden" id="idgrupo"/>
<table width="30%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td align="right" class='viewPropTitle'>Codigo</td>
    <td><label>
      <input name="codigo" type="text" id="codigo" size="2" maxlength="1">
    </label>
    
        
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Denominacion</td>
    <td><label>
      <input name="denominacion" type="text" id="denominacion" size="35">
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><table border="0" align="center" cellpadding="4" cellspacing="0">
      <tr>
        <td><label>
          <input type="button" name="boton_ingresar" id="boton_ingresar" value="Ingresar" style=" cursor:pointer" class="button" onclick="ingresarGrupo()">
        </label></td>
        <td><label>
          <input type="button" name="boton_modificar" id="boton_modificar" value="Modificar" style="cursor:pointer; display:none" class="button" onclick="editarGrupo()">
        </label></td>
        <td><label>
          <input type="button" name="boton_eliminar" id="boton_eliminar" value="Eliminar" style="cursor:pointer; display:none" class="button" onclick="eliminarGrupo()">
        </label></td>
      </tr>
    </table></td>
  </tr>
</table>

<br />
<br />
<form method="post" action="" onsubmit="return listarGrupos()">
<table align="center" id="tabla_buscar">
<tr>
<td align="right" class='viewPropTitle'>Denominacion</td>
<td><input type="text" id="campoBuscar" name="campoBuscar"></td>
<td><input type="submit" id="botonBuscar" name="botonBuscar" value="Buscar" class="button"></td>
</tr>
</table>
</form>

<br />
<div id="listaGrupos">
<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="30%">
          <thead>
          <tr>
            <td width="7%" align="center" class="Browse" style="font-size:9px">Codigo</td>
            <td width="67%" align="center" class="Browse" style="font-size:9px">Denominacion</td>
            <td width="6%" align="center" class="Browse" style="font-size:9px" colspan="2">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $sql_consultar = mysql_query("select * from grupo_cuentas_contables ORDER BY codigo");
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='center' width="7%" style="font-size:10px"><?=$bus_consultar["codigo"]?></td>
                    <td class='Browse' align='left' width="67%" style="font-size:10px"><?=$bus_consultar["denominacion"]?></td>
                    <td align="center" valign="middle" class='Browse'>
                        <img src="imagenes/modificar.png"
                            style="cursor:pointer"
                            onClick="seleccinarModificar('<?=$bus_consultar["codigo"]?>', '<?=$bus_consultar["denominacion"]?>', '<?=$bus_consultar["idgrupos_cuentas_contables"]?>')">
                    </td>
                    <td align="center" valign="middle" class='Browse'>
                        <img src="imagenes/delete.png"
                            style="cursor:pointer"
                            onClick="seleccinarEliminar('<?=$bus_consultar["codigo"]?>', '<?=$bus_consultar["denominacion"]?>', '<?=$bus_consultar["idgrupos_cuentas_contables"]?>')">                    </td>
                            
   		        </tr>
          <?
          }
          ?>
  </table>
</div>