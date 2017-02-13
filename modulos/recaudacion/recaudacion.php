<script src="modulos/recaudacion/js/recaudacion_ajax.js"></script>
<style type="text/css">
<!--
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>

<h4 align=center>Recaudaci&oacute;n</h4>
	<h2 class="sqlmVersion"></h2>

 <div align="center">
 <img src="imagenes/search0.png" style="cursor:pointer" onClick="window.open('lib/listas/listar_recaudacion.php','','width=900, height=600, scrollbars = yes')">&nbsp;
 <img src="imagenes/nuevo.png" style="cursor:pointer" onClick="window.location.href='principal.php?modulo=<?=$_GET["modulo"]?>&accion=<?=$_GET["accion"]?>'">
 &nbsp;
<img id="btImprimir" src="imagenes/imprimir.png" style="cursor:pointer; visibility:hidden;" onClick="document.getElementById('divPDF').style.display='block'; iPDF.location.href='lib/reportes/recaudacion/reportes.php?nombre=recaudacion&idcontribuyente='+document.getElementById('idcontribuyente').value+'&idpagos_recaudacion='+document.getElementById('idpagos_recaudacion').value;">
 </div>

<div id="divPDF" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
<table align="center">
	<tr>
    	<td>
            <div align="right"><a href="#" onClick="document.getElementById('divPDF').style.display='none'; document.getElementById('iPDF').src='';">X</a></div>
            <iframe name="iPDF" id="iPDF" height="600px" width="710px"></iframe>
        </td>
    </tr>
</table>
</div>


<input id="idcontribuyente" name="idcontribuyente" type="hidden">
<input id="idpagos_recaudacion" name="idpagos_recaudacion" type="hidden">
 <table width="90%" align="center">
   <tr>
     <td align="right" class='viewPropTitle'>N&uacute;mero de la Solicitud</td>
     <td id="numero_orden" style="font-weight:bold">Aun no generado</td>
     <td align="right" class='viewPropTitle'>Estado</td>
     <td id="estado_orden" style="font-weight:bold">Elaboraci&oacute;n</td>
   </tr>
   
   <tr>
     <td width="24%" align="right" class='viewPropTitle'>
     
     
     <table>
     <tr>
     <td>
     Contribuyente&nbsp;     </td>
     <td>  <img src="imagenes/search0.png" onClick="window.open('lib/listas/listar_contribuyentes.php?url=solicitud_calculo','','width=900, height = 600, scrollbars=yes')" style="cursor:pointer" id="imagen_buscar_contribuyente">     </td>
     </tr>
     </table>     </td>
     
     <td colspan="3" id="datos_contribuyente">&nbsp;</td>
   </tr>
   <tr>
     <td align="right" class='viewPropTitle'>Total a Pagar:</td>
     <td align="center" style="font-weight:bold" id="total_a_pagar">&nbsp;</td>
     <td align="center">&nbsp;</td>
     <td align="center">&nbsp;</td>
   </tr>
   <tr>
     <td colspan="4" align="center"><table border="0">
       <tr>
         <td><label>
           <input type="submit" name="boton_siguiente" id="boton_siguiente" value="Siguiente" class="button" onClick="ingresarDatosBasicos()"/>
         </label></td>
         <td><label>
           <input type="submit" name="boton_procesar" id="boton_procesar" value="Procesar" style="display:none" class="button" onClick="abrirFormularioPago()"/>
         </label></td>
         <td><label>
           <input type="submit" name="boton_anular" id="boton_anular" value="Anular" style="display:none" class="button" onBlur="anularRecaudacion()"/>
         </label></td>
       </tr>
     </table></td>
   </tr>
   <tr>
     <td colspan="4" bgcolor="#EAEAEA" id="titulo"><strong>Pagos Pendientes</strong>
     
     <div id="planilla_datos_pago" style="background-color:#EAEAEA; position:absolute; width:800px; height:450px; display:none; left:130px; top:80px; border:#000000 solid 1px; overflow:auto; padding:15px">
     <div style="text-align:right"><a href="javascript:;" onClick="document.getElementById('planilla_datos_pago').style.display = 'none'">Cerrar</a></div>
     	<table width="100%">
          <tr>
            <td colspan="4" align="left" bgcolor="#999999"><span class="Estilo1">&nbsp;&nbsp;&nbsp;Datos de la Recaudaci&oacute;n</span></td>
          </tr>
              <tr>
                <td align="right" >&nbsp;</td>
                <td></td>
                <td align="right" >&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
        		<td align="right" class='viewPropTitle'>Nro de Planilla</td>
                <td id="numero_planilla"></td>
                <td align="right" class='viewPropTitle'>Total bs.</td>
                <td><input type="text" name="total" id="total" style="text-align:right" readonly="readonly"></td>
            </tr>
            <tr>
        		<td align="right" class='viewPropTitle'>Fecha de Pago</td>
                <td><input name="fecha_pago" type="text" id="fecha_pago" size="13" value="<?=date("Y-m-d")?>"></td>
                <td align="right" class='viewPropTitle'>Descuento</td>
                <td><input type="text" name="descuento" id="descuento" style="text-align:right" onBlur="actualizarTotales()" value="0"></td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" class='viewPropTitle'>Total Mora.</td>
                <td><input type="text" name="total_mora" id="total_mora" style="text-align:right" readonly="readonly"></td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" class='viewPropTitle'>Total a Cancelar.</td>
                <td><input type="text" name="total_pagar" id="total_pagar" style="text-align:right" readonly="readonly"></td>
            </tr>
            <tr>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" align="left" bgcolor="#999999"><span class="Estilo1">&nbsp;&nbsp;&nbsp;Forma de Pago</span></td>
            </tr>
            <tr>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
            	<td align="right" class='viewPropTitle'>Banco</td>
                <td>
                <select id="banco" name="banco">
				<option value="0">.:: Seleccione ::.</option>
				<?
                $sql_banco = mysql_query("select * from banco");
				while($bus_banco = mysql_fetch_array($sql_banco)){
					?>
					<option onclick="consultarCuentaBancaria(this.value, 1)" value="<?=$bus_banco["idbanco"]?>"><?=$bus_banco["denominacion"]?></option>
					<?
				}
				?>
                </select>                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
            	<td align="right" class='viewPropTitle'>Cuenta</td>
                <td id="div_nro_cuenta">
                  <select name="nro_cuenta" id="nro_cuenta">
                  </select>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
            	<td align="right" class='viewPropTitle'>Especie</td>
                <td>
                <select name="especie" id="especie">
	                <option onClick="document.getElementById('detalle_deposito').style.display = 'none'" value="efectivo">Efectivo</option>
                    <option onClick="document.getElementById('detalle_deposito').style.display = 'block'" value="debito">Debito</option>
                    <option onClick="document.getElementById('detalle_deposito').style.display = 'block'" value="credito">Credito</option>
                    <option onClick="document.getElementById('detalle_deposito').style.display = 'block'" value="cheque">Cheque</option>
                    <option onClick="document.getElementById('detalle_deposito').style.display = 'block'" value="deposito">Deposito</option>
                    <option onClick="document.getElementById('detalle_deposito').style.display = 'block'" value="transferencia">Transferencia</option>
                    <option onClick="document.getElementById('detalle_deposito').style.display = 'block'" value="otro">Otro</option>
                </select>                </td>
                <td colspan="2" rowspan="3">
                
                
                
                
                
                <table width="100%" border="0" id="detalle_deposito" style="display:none">
                  <tr>
                    <td align="right" class='viewPropTitle'>Banco del Emisor</td>
                    <td>
                    <select id="banco_pago" name="banco_pago">
				<?
                $sql_banco = mysql_query("select * from banco");
				while($bus_banco = mysql_fetch_array($sql_banco)){
					?>
					<option  value="<?=$bus_banco["idbanco"]?>"><?=$bus_banco["denominacion"]?></option>
					<?
				}
				?>
                </select>
                    </td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitle'>Nro Cuenta del Emisor</td>
                    <td id="div_nro_cuenta_pago">
                      <input name="nro_cuenta_pago" type="text" id="nro_cuenta_pago" maxlength="20" size="30">
                    </td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitle'>Nro. Documento</td>
                    <td><label>
                      <input type="text" name="nro_documento" id="nro_documento">
                    </label></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitle'>Nro. de Cheque / Tarjeta</td>
                    <td><label>
                      <input type="text" name="nro_cheque_tarjeta" id="nro_cheque_tarjeta">
                    </label></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitle'>Tipo de Tarjeta</td>
                    <td>
                    <select name="tipo_tarjeta" id="tipo_tarjeta">
                    	<option value="visa">Visa</option>
                        <option value="mastercard">Marter Card</option>
                        <option value="american_express">American Express</option>
                        <option value="dinners_club">Dinners Club</option>
                     </select>
                    </td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitle'>Fecha</td>
                    <td><label>
                      <input type="text" name="fecha" id="fecha">
                    </label></td>
                  </tr>
                </table></td>
            </tr>
     		<?
            $buscar=mysql_query("select * from usuarios 
								where cedula= '".$_SESSION['cedula_usuario']."'")or die(mysql_error());
			$registro_usuario=mysql_fetch_array($buscar);
			?>
            
            <tr>
            	<td align="right" class='viewPropTitle'>Recibido por:</td>
                <td><input name="recibido_por" type="text" id="recibido_por" value="<?=$registro_usuario["nombres"]." ".$registro_usuario["apellidos"]?>" size="40"></td>
            </tr>
            <tr>
                <td align="right" valign="top" class='viewPropTitle'>CI Recibido</td>
                <td valign="top"><input type="text" name="ci_recibido" id="ci_recibido" value="<?=$_SESSION['cedula_usuario']?>"></td>
            </tr>
            
            <tr>
            	<td colspan="4" align="center"><input type="button" class="button" id="boton_pagar" name="boton_pagar" value="Pagar" onClick="procesarPago()"></td>
          </tr>
            </table>
     </div>
     
     
     </td>
   </tr>
   <tr>
     <td colspan="4" id="celda_pagos_pendientes">&nbsp;</td>
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td width="27%">&nbsp;</td>
     <td width="21%">&nbsp;</td>
     <td width="28%">&nbsp;</td>
   </tr>
   <tr>
     <td colspan="4" id="celda_fechas">&nbsp;</td>
   </tr>
 </table>
 