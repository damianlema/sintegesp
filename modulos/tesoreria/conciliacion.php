<script src="modulos/tesoreria/js/conciliacion_ajax.js"></script>
    <br>
<h4 align=center>Conciliar Pagos</h4>
	<h2 class="sqlmVersion"></h2>
 <br>


<table align="center" cellpadding="0" cellspacing="0">
	<tr>
    	<td align="center" class='viewPropTitle'>Banco: 
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
          </select></td>
      <td>&nbsp;</td>
        <td align="center" class='viewPropTitle'>Cuenta</td>
        <td id="celdaCuentaBancaria">
        <select name="cuenta" id="cuenta">
          <option value="0">.:: Seleccione un Banco ::.</option>
        </select>
        </td>
         <td align="center" class='viewPropTitle'>Estado</td>
          <td>
          <select name="estado" id="estado">
          	<option value="0">.:: Seleccione ::.</option>
            <option value="transito">Transito</option>
            <option value="conciliado">Conciliado</option>
          </select>
          </td>
        
        <td><input type="button" name="botonBuscar" id="botonBuscar" value="Buscar" class="button" onClick="buscarPagos()"></td>
    </tr>
</table>
<br />



    <div id="divMensaje" align="center" style="color:#FF0000; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold"></div>
    
<br />    
<div id="listaOrdenes">   
    
</div>