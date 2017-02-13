<script src="modulos/tesoreria/js/pagos_entregados_ajax.js"></script>

	<br>
	<h4 align=center>Registrar Entregas de Pagos</h4>
	<h2 class="sqlmVersion"></h2>
    <br>

<input type="hidden" name="idpagos_financieros" id="idpagos_financieros">
<table border="0" align="center" cellpadding="0" cellspacing="4">
  <tr>
    <td align="right" class='viewPropTitle'>Orde de Pago</td>
    <td id="divOrdenPago">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Beneficiario</td>
    <td id="divBeneficiario">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Recibido Por</td>
    <td><label>
      <input name="recibido_por" type="text" id="recibido_por" size="40">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>CI Recibe</td>
    <td><label>
      <input name="ci_recibe" type="text" id="ci_recibe" size="15">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Fecha Recibe</td>
    <td><label>
      <input name="fecha_recibe" type="text" id="fecha_recibe" size="12" readonly>
    <img src="imagenes/jscalendar0.gif" name="imagen_fecha" width="16" height="16" id="imagen_fecha" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" border="0" />
      <script>
      Calendar.setup({
                        inputField    : 'fecha_recibe',
                        button        : 'imagen_fecha',
                        align         : 'Tr',
                        ifFormat      : '%Y-%m-%d'
                        });
    </script>
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><label>
      <input 
      	type="button" 
        name="actualizar" 
        id="actualizar" 
        value="Actualizar" 
        class="button"
        onClick="actualizarDatos(document.getElementById('idpagos_financieros').value)">
    </label></td>
  </tr>
</table>
<br />
<br>



<table align="center" cellpadding="0" cellspacing="0">
	<tr>
	  <td align="center" class='viewPropTitle'>Nro. Orden:</td>
	  <td><label>
	    <input type="text" name="nro_orden_busqueda" id="nro_orden_busqueda" />
	  </label></td>
	  <td align="center" class='viewPropTitle'>Nro. Cheque</td>
	  <td id="celdaCuentaBancaria2"><label>
	    <input type="text" name="nro_cheque_busqueda" id="nro_cheque_busqueda" />
	  </label></td>
	  <td align="center" class='viewPropTitle'>Beneficiario</td>
	  <td><label>
	    <input type="text" name="beneficiario_busqueda" id="beneficiario_busqueda" />
	  </label></td>
	  <td>&nbsp;</td>
  </tr>
	<tr>
    	<td align="center" class='viewPropTitle'>Banco:    	  </td>
      <td><span class="viewPropTitle">
        <select name="banco" id="banco">
          <option value="0">.:: Seleccione ::.</option>
          <?
            $sql_consultar_cuentas = mysql_Query("select * from cuentas_bancarias where idbanco = '".$bus_consultar_banco["idbanco"]."'");
			$sql_consultar_banco = mysql_query("select * from banco");
				while($bus_consultar_banco = mysql_fetch_array($sql_consultar_banco)){
				$sql_consultar_cuentas = mysql_query("select * from cuentas_bancarias where idbanco = '".$bus_consultar_banco["idbanco"]."'");
				$num_consultar_cuentas = mysql_num_rows($sql_consultar_cuentas);
					if($num_consultar_cuentas > 0){
						?>
          <option value="<?=$bus_consultar_banco["idbanco"]?>" onclick="cargarCuentasBancarias('<?=$bus_consultar_banco["idbanco"]?>')">
          <?=$bus_consultar_banco["denominacion"]?>
          </option>
          <?
					}
				}
			?>
        </select>
      </span></td>
        <td align="center" class='viewPropTitle'>Cuenta</td>
        <td id="celdaCuentaBancaria">
        <select name="cuenta" id="cuenta">
          <option value="0">.:: Seleccione un Banco ::.</option>
        </select>        </td>
         <td align="center" class='viewPropTitle'>&nbsp;</td>
          <td>&nbsp;</td>
        
        <td><input type="button" name="botonBuscar" id="botonBuscar" value="Buscar" class="button" onClick="listarPagosFinancieros()"></td>
    </tr>
</table>




<br />

<br>
<div id="lista_pagos_financieros">
  
</div>