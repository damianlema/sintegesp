<script src="modulos/rrhh/js/motivos_cuentas_ajax.js"></script>


<br>
<h4 align=center>Cuentas Bancarias para Pagar</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
    <input name="idmotivos_cuentas" type="hidden" id="idmotivos_cuentas"/>
<table width="40%" border="0" align="center">
  <tr>
    <td width="28%" align="right" class="viewPropTitle">Denominacion</td>
    <td width="72%"><label>
      <input name="denominacion" type="text" id="denominacion" size="28" maxlength="20">
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><table border="0">
      <tr>
        <td><label>
          <input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onclick="ingresarCunetaBancaria()">
        </label></td>
        <td><label>
          <input type="submit" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" style="display:none" onclick="modificarCunetaBancaria()">
        </label></td>
        <td><label>
          <input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" class="button" style="display:none" onclick="eliminarCuentaBancaria()">
        </label></td>
      </tr>
    </table></td>
  </tr>
</table>
<br />
<br />

<div id="lista_cuentas_bancarias">
<?
	$sql_consultar = mysql_query("select 
								 *
								 	from 
								 motivos_cuentas");
	
	?>
	<table border="0" class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
        	<thead>
            <tr>
            	<td width="26%" class="Browse" align="center">Denominacion</td>
                <td width="11%" class="Browse" align="center" colspan="2">Acci&oacute;n</td>
            </tr>
            </thead>
            <? while($bus_consultar = mysql_fetch_array($sql_consultar)){?>
            	<tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="left" class="Browse"><?=$bus_consultar["denominacion"]?></td>
                
                
                
                <td class="Browse" align="center">
                 <img src="imagenes/modificar.png" style="cursor:pointer" alt='Modificar' title='Modificar' onclick="seleccionar('<?=$bus_consultar["denominacion"]?>','<?=$bus_consultar["idmotivos_cuentas"]?>')">                </td>
                 <td class="Browse" align="center">
                 	<img src="imagenes/delete.png" style="cursor:pointer" alt='Eliminar' title='Eliminar' onclick="seleccionar('<?=$bus_consultar["denominacion"]?>','<?=$bus_consultar["idmotivos_cuentas"]?>')">
                 </td>
          </tr>
            <? } ?>
        </table>

</div>