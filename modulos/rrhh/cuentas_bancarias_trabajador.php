<script src="modulos/rrhh/js/cuentas_bancarias_trabajador_ajax.js"></script>


<br>
	<h4 align=center>Cuentas Bancarias</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
    <input name="idcuenta_bancaria" type="hidden" id="idcuenta_bancaria"/>
<table width="62%" border="0" align="center">
  <tr>
    <td width="28%" align="right" class="viewPropTitle">Trabajador</td>
    <td width="72%"><input name="id_trabajador" type="hidden" id="id_trabajador"/>
      <label>
        <input name="nombre_trabajador" type="text" id="nombre_trabajador" size="60" readonly="readonly"/>
    <img src="imagenes/search0.png" width="16" height="16" onclick="window.open('lib/listas/lista_trabajador.php?frm=conceptos_trabajador','','resizable=no, scrollbars=yes, width =900, height = 600')" style="cursor:pointer"/></label></td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">Banco</td>
    <td>
    	<?
      $sql_bancos = mysql_query("select * from banco");
	  ?>
      <select name="banco" id="banco">
          <option value="0">.:: Seleccione ::.</option>
          <?
          while($bus_bancos = mysql_fetch_array($sql_bancos)){
            ?>
            <option value="<?=$bus_bancos["idbanco"]?>"><?=$bus_bancos["denominacion"]?></option>
            <?
          }
          ?>
      </select>
    </td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">Nº de Cuenta</td>
    <td><label>
      <input name="numero_cuenta" type="text" id="numero_cuenta" size="28" maxlength="20">
    </label></td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle"><p>Tipo de Cuenta</p></td>
    <td><label>
      <select name="tipo_cuenta" id="tipo_cuenta">
        <option value="Corriente">Corriente</option>
        <option value="Ahorro">Ahorro</option>
      </select>
    </label></td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">Cuentas Bancarias para Pagar</td>
    <td><label>
      <select name="motivo_cuenta" id="motivo_cuenta">
        <?
        $sql_motivos_cuentas = mysql_query("select * from motivos_cuentas");
		while($bus_motivos_cuentas = mysql_fetch_array($sql_motivos_cuentas)){
		?><option value="<?=$bus_motivos_cuentas["idmotivos_cuentas"]?>"><?=$bus_motivos_cuentas["denominacion"]?></option><?	
		}
		?>
      </select>
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

</div>