<script src="modulos/tesoreria/js/relacion_generar_comprobantes_ajax.js" type="text/javascript" language="javascript"></script>	

    <br>
<h4 align=center>Relaci&oacute;n de Ordenes apara Generar Comprobante</h4>
	<h2 class="sqlmVersion"></h2>


<div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
<table align="center">
	<tr><td align="right"><a href="#" onClick="document.getElementById('divImprimir').style.display='none';">X</a></td></tr>
   	<tr><td><iframe name="pdf" id="pdf" style="display:none" height="600" width="750"></iframe></td></tr>
</table>
</div>

  <!-- TABLA DE DATOS BASICOS-->
<br>
<div id="tablaDatosBasicos">
  
  <table width="6%" border="0" align="center" cellpadding="0" cellspacing="2">
  
  <tr>
  <td align="right"><img src="imagenes/search0.png" title="Buscar Remisiones" style="cursor:pointer" onclick="window.open('lib/listas/listar_relacion_generar_comprobante.php','Buscar Relaciones','resisable = no, scrollbars = yes, width=900, height = 500')" />&nbsp; <img src="imagenes/nuevo.png" title="Ingresar nueva Relaci&oacute;n" onclick="window.location.href='principal.php?modulo=<?=$modulo?>&accion=<?=$_GET["accion"]?>'" style="cursor:pointer" /></td>
  
<td id='celdaImprimir' style="display:none" align="left">
  &nbsp;<img src="imagenes/imprimir.png" title="Imprimir Relaci&oacute;n" onClick="pdf.location.href='lib/reportes/tesoreria/reportes.php?nombre=remitirdoc&id_remision='+document.getElementById('id_remision').value; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block';" style="cursor:pointer" /> 
  
</td>
  </tr>
 </table>
 <br />
 
<table width="75%" border="0" align="center" cellpadding="0" cellspacing="2"> 
<tr>
        <td width="163" align="right" class='viewPropTitle'>N&uacute;mero de Relaci&oacute;n:</td>
      <td width="133" id="divNumeroDocumento"><strong>Aun No Generado</strong>
        &nbsp;</td>
      <td width="140" class='viewPropTitle'><div align="right">Fecha:</div></td>
        
      <td width="108"><div id="divFechaEnvio" align="left">&nbsp;</div></td>
      <td width="210" class='viewPropTitle'><div align="right">Nro. Documentos relacionados:</div></td>
      <td width="77" id="divCantidad">&nbsp;</td>
	</tr>
    <tr>
      <td align="right" class='viewPropTitle'>Para:</td>
      <td colspan="3">
      <select name="dependencias" id="dependencias">
	  <option value="0">.:: Seleccione ::. </option>
	  <?
	  if ($modulo == 1){
		  $sql_configuracion = mysql_query("select * from configuracion_rrhh");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if ($modulo == 2){
		  $sql_configuracion = mysql_query("select * from configuracion_presupuesto");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 3){
		  $sql_configuracion = mysql_query("select * from configuracion_compras");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
  	  }else if($modulo == 4){
		  $sql_configuracion = mysql_query("select * from configuracion_administracion");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 6){
		  $sql_configuracion = mysql_query("select * from configuracion_tributos");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 7){
		  $sql_configuracion = mysql_query("select * from configuracion_tesoreria");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 5){
		  $sql_configuracion = mysql_query("select * from configuracion_contabilidad");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 12){
		  $sql_configuracion = mysql_query("select * from configuracion_despacho");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 13){
		  $sql_configuracion = mysql_query("select * from configuracion_nomina");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 14){
		  $sql_configuracion = mysql_query("select * from configuracion_secretaria");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 16){
		  $sql_configuracion = mysql_query("select * from configuracion_caja_chica");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 19){
		  $sql_configuracion = mysql_query("select * from configuracion_obras");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }
	  
      $sql_dependencias = mysql_query("select * from dependencias order by denominacion");
	  while($bus_dependencias = mysql_fetch_array($sql_dependencias)){
	  	if ($bus_dependencias["iddependencia"] <> $bus_configuracion["iddependencia"]){
	  	?>
	  		<option value="<?=$bus_dependencias["iddependencia"]?>"><?=$bus_dependencias["denominacion"]?></option>
	  	<? }
	  }
	  ?>
      </select>      
      </td>
      <td width="210" class='viewPropTitle'><div align="right">Estado:</div></td>
      <td width="77" id="divEstado"><strong>Elaboraci&oacute;n</strong></td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Asunto:</td>
      <td height="4" colspan="7">
      <input name="asunto" type="text" id="asunto" value="En el texto" size="100" />
      <input type="hidden" name="id_remision" id="id_remision">
      <input type="hidden" name="estado" id="estado" value="">
      <input type="hidden" name="tabla" id="tabla" value="">
      </td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Concepto:</td>
      <td colspan="6"><textarea name="justificacion" cols="100" rows="3" id="justificacion"></textarea></td>
    </tr>
    <tr>
      <td align='right' class='viewPropTitle'>Banco</td>
    	<td>
    	<select name="banco" id="banco">
        	<option value="0">.:: Seleccione ::.</option>
			<?
            $sql_consultar_cuentas = mysql_query("select * from cuentas_bancarias where idbanco = '".$bus_consultar_banco["idbanco"]."'");
			$sql_consultar_banco = mysql_query("select * from banco");
				while($bus_consultar_banco = mysql_fetch_array($sql_consultar_banco)){
				$sql_consultar_cuentas = mysql_query("select * from cuentas_bancarias where idbanco = '".$bus_consultar_banco["idbanco"]."'");
				$num_consultar_cuentas = mysql_num_rows($sql_consultar_cuentas);
					if($num_consultar_cuentas > 0){
						?>
						<option value="<?=$bus_consultar_banco["idbanco"]?>" onclick="cargarCuentasBancarias('<?=$bus_consultar_banco["idbanco"]?>')"><?=$bus_consultar_banco["denominacion"]?></option>
						<?
					}
				}
			?>
         </select>    </td>
    	<td align='right' class='viewPropTitle'>Cuenta</td>
    	<td colspan="3" id="celdaCuentaBancaria">
    	<select name="cuenta" id="cuenta" disabled>
        	<option value="0">.:: Seleccione un Banco ::.</option>
		</select>   </td>
    </tr>
    <? /*
    <tr>
        	<td align="right" class='viewPropTitle'>Tipo de Retenci&oacute;n</td>
            <td width="21%">
            <select name="idtipo" id="idtipo">
                    <option value="0"></option>
                    <?php
                        $sql="SELECT nombre_comprobante, descripcion FROM tipo_retencion GROUP BY nombre_comprobante ORDER BY descripcion";
                        $query=mysql_query($sql) or die ($sql.mysql_error());
                        while ($field=mysql_fetch_array($query)) echo "<option value='$field[0]'>$field[1]</option>";
                    ?>
                </select>
             </td>
        </tr>
	*/ ?>
    <tr>
      <td>&nbsp;</td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="7" id="vistaDeBotones">
      	
        <table align="center">
        	<tr>
        		<td>
                    <input type="button" 
                            name="botonSiguiente" 
                            id="botonSiguiente" 
                            value="Siguiente >" 
                            style="display:block" 
                            onclick="ingresarDatosBasicos(document.getElementById('dependencias').value, document.getElementById('asunto').value, document.getElementById('justificacion').value, document.getElementById('id_remision').value, document.getElementById('cuenta').value)" 
                            class="button">             			
               </td>
               <td>

				<input type="button" 
                            name="botonElaboracion" 
                            id="botonElaboracion" 
                            value="En Elaboraci&oacute;n" 
                            style="display:none" 
                            onclick="actualizarDatosBasicos(document.getElementById('id_remision').value, document.getElementById('dependencias').value, document.getElementById('asunto').value, document.getElementById('justificacion').value, document.getElementById('cuenta').value)" 
                            class="button">                	
                </td>
                <td>

				<input type="button" 
                            name="botonEnviar" 
                            id="botonEnviar" 
                            value="Enviar" 
                            style="display:none" 
                            onclick="remitirDocumentos(document.getElementById('id_remision').value)" 
                            class="button">                	
	             </td>
               <td>
               
				<input type="button" 
                            name="botonAnular" 
                            id="botonAnular" 
                            value="Anular" 
                            style="display:none" 
                            onclick="anularRemision(document.getElementById('id_remision').value)" 
                            class="button">                	
				</td>
        	</tr>
       </table>
        <br />        </td>
    </tr>
  </table>
</div>
  
<? /*
  <table width="1100px" align="center" cellpadding="0" cellspacing="0" id="tablaSeleccionarDocumentos" style="display:none">
 	
    <tr>
        <td><div id="texto_buscar" style="display:block">Nro.&nbsp;Orden.</div> </td>
        <td><input type="text" name="campoBusqueda" id="campoBusqueda" style="display:block" size="12"></td>            
        <td><input type="submit" name="boton_buscar" id="boton_buscar" class="button" style="display:block" value="Buscar"></td>
        
   	</tr>
   	</table>
 */ ?>
    
    <table width="1100px" align="center" style="display:none" id="tablaSeleccionarDocumentos">
    <tr style="background: #09F">
      <td width="1100px" align="left"><strong>DOCUMENTOS POR GENERAR COMPROBANTE</strong></td>
    </tr>
    <tr>
    <td>
    <div id="listaDocumentos"></div>
    </td>
  	</tr>
</table>


<br>
<!-- DOCUMENTOS A ENVIAR-->
<table width="1100px" align="center" style="display:none" id="tablaDocumentosEnviar">
    <tr style="background: #09F">
      <td width="1100px" align="left"><strong>DOCUMENTOS RELACIONADOS</strong></td>
    </tr>
    <tr>
    <td>
    <div id="divListaSeleccionadosDocumentos" style="display:block"></div> 
    </td>
    </tr>
</table>

