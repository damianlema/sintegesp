<script src="modulos/almacen/js/movimiento_materiales_ajax.js"></script>


<br>
<h4 align=center>Movimiento de Materiales</h4>
<h2 class="sqlmVersion"></h2>

<input type="hidden" id="idmovimiento" name="idmovimiento">

<table align="center">
    <tr>
    	<td><img src="imagenes/search0.png" alt="Buscar" title="Buscar" onclick="window.open('lib/listas/listar_movimientos_materia.php','','width=900; height=600, scrollbars=yes,resizabled=no')" style="cursor:pointer"></td>
    	<td><img src="imagenes/nuevo.png" alt="Nuevo" title="Nuevo" onclick="window.location.href = 'principal.php?accion=1090&modulo=19'" style="cursor:pointer"></td>
    </tr>
</table>

<div id="divAjusteTituloDatos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:950px; margin-left:-475px">
<table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>Datos del Movimiento</strong></td>
    </tr>
</table>
</div>
<div id="divDatosBasicos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:auto; width:950px; margin-top:20px; margin-left:-475px; overflow:auto">

<br />
<table width="100%" border="0" align="center">
  <tr>
    <td width="20%" align="right" >N&uacute;mero:</td>
    <td width="22%" id="celda_nro_movimiento"><input type="text" name="numero_documento" id="numero_documento" style="width:150px; height:20px" readonly="readonly" /></td>
    <td width="6%" align="right" >Fecha:</td>
    <td width="32%"><input type="text" name="fecha_documento" id="fecha_documento" style="width:130px; height:20px" readonly="readonly" /></td>
    <td width="6%" align="right" >Estado:</td>
    <td width="22%"><input type="text" name="estado" id="estado" style="width:130px; height:20px" readonly="readonly" value="En Elaboraci&oacute;n"/></td>
  </tr>
  <? /*
   <tr>
    <td align="right" >Tipo de Documento:</td>
    <td colspan="5" >
    	 <?
		$sql_tipos_documentos = mysql_query("select * from tipos_documentos 
														where 
														compromete = 'no' and 
														causa = 'no' and 
														paga = 'no' and 
														modulo like '%-".$_SESSION["modulo"]."-%'");
		?>
		<select name="idtipos_documentos" id="idtipos_documentos">
        	<option value="0">.:: Seleccione ::.</option>
			<?
            while($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)){
            ?>
                <option value="<?=$bus_tipos_documentos["idtipos_documentos"]?>"><?=$bus_tipos_documentos["descripcion"]?></option>
            <?
            }
            ?>
		</select>     
    </td>
  </tr> */ ?>
  <tr>
    <td align="right" >Afecta:</td>
    <td><label>
      <select name="afecta" id="afecta">
      	<option value="0">.:: Seleccione ::.</option>
      	<option value="entrada" onClick="consultarTipoConceptoMovimiento('entrada'); consultarDocumentoTransaccion('entrada')">Entrada</option>
        <option value="salida" onClick="consultarTipoConceptoMovimiento('salida'); consultarDocumentoTransaccion('salida'); consultarMostrar('salida')">Salida</option>
        <option value="inicial" onClick="consultarTipoConceptoMovimiento('inicial'); consultarDocumentoTransaccion('inicial')">Ajuste</option>
        <option value="traslado" onClick="consultarTipoConceptoMovimiento('traslado'); consultarDocumentoTransaccion('traslado')">Traslado</option>
      </select>
    </label></td>
    <td align="right">Almacen</td>
    <td colspan="3">
		<?
      $sql_almacen = mysql_query("select * from almacen");
      ?>
      <select name="almacen" id="almacen">
          <option value="0">.:: Seleccione ::.</option>
          <?
          while($bus_almacen = mysql_fetch_array($sql_almacen)){
            ?>
            <option value="<?=$bus_almacen["idalmacen"]?>" <?php if ($bus_almacen["defecto"] == 1) echo "selected"?> onClick="seleccionarUbicacion('<?=$bus_almacen["idalmacen"]?>')"><?=$bus_almacen["codigo"]." - ".$bus_almacen["denominacion"]?></option>
            <?
          }
          ?>
      </select>
    </td>
  </tr>
  <tr>
    <td align="right" >Tipo de Concepto:<input type="hidden" name="accion_tipo_concepto" id="accion_tipo_concepto"></td>
    <td colspan="5" id="celda_tipo_concepto"><label>
      <select name="tipo_concepto_movimiento" id="tipo_concepto_movimiento">
     	<option value="0">.:: Seleccione el tipo de Afectaci&oacute;n</option>
      </select>
    </label>
    
    </td>
  </tr>
  <tr><td colspan="6">
  <div id="divDestino" style="display:none">
      <table width="100%" border="0" align="center">
      <tr >
        <td align="right" width="18%" >Unidad Destino:<input type="hidden" name="idniveles_organizacionales" id="idniveles_organizacionales"></td>
        <td colspan="3"><input type="text" name="unidad_destino" id="unidad_destino" style="width:650px; height:20px"/>
          <img src="imagenes/search0.png" alt="Buscar OC" title="Buscar Estructura Organizativa" onclick="window.open('lib/listas/listar_estructura_organizativa.php','','resisable=no, scrollbars=yes, width = 900, height= 600')" style="cursor:pointer"/>
        </td>
      </tr>
  	  </table>
   </div>
  <div id="divProveedor" style="display:none">
      <table width="100%" border="0" align="center">
      <tr >
        <td align="right" width="20%" >Proveedor:<input type="hidden" name="idbeneficiario" id="idbeneficiario"></td>
        <td colspan="2"><input type="text" name="proveedor" id="proveedor" style="width:650px; height:20px"/></td>
        <td width="15%" align="left"><img src="imagenes/search0.png" alt="Buscar OC" title="Buscar Proveedores" onclick="window.open('modulos/compromisos/lib/listar_beneficiarios.php?destino=almacen','listar proveedores','resizable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer" id="botonBuscarProveedor"/>
        </td>
      </tr>
  	  </table>
   </div>
   <div id="divOrdenCompra" style="display:none">
      <table width="100%" border="0" align="center">
      <tr >
        <td align="right" width="17%" >Compra:<input type="hidden" name="idorden_compra_servicio" id="idorden_compra_servicio"></td>
        <td width="18%" ><input type="text" name="orden_compra" id="orden_compra" style="width:150px; height:20px" readonly="readonly" /></td>
        <td align="right" width="6%" >Fecha:</td>
        <td width="54%" ><input type="text" name="fecha_orden_compra" id="fecha_orden_compra" style="width:100px; height:20px" readonly="readonly" />
          <img src="imagenes/search0.png" alt="Buscar Ordenes de Compra" title="Buscar Ordenes de Compra" style="cursor:pointer" onclick="window.open('lib/listas/listar_ordenes_compra.php?destino=almacen','buscar orden compra servicio','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">
        </td>
      </tr>
      </table>
  </div>
  <div id="divDocumentoTransaccion" style="display:none">
      <table width="100%" border="0" align="center">
      <tr >
        <td align="right" width="18%" >Documento:</td>
        <td width="20%" id="celda_documento_transaccion" >
        </td>
        <td align="right" width="7%" >N&uacute;mero:</td>
        <td width="10%" ><input type="text" name="numero_documento_transaccion" id="numero_documento_transaccion" style="width:100px; height:20px"/></td>
        <td align="right" width="7%" >Fecha:</td>
        <td width="38%" ><input type="text" name="fecha_documento_transaccion" id="fecha_documento_transaccion" style="width:100px; height:20px"/></td>
      </tr>
      </table>
  </div>
  </td>
  </tr>
  <tr>
    <td align="right" >Justificaci&oacute;n:</td>
    <td colspan="5"><label>
      <textarea name="justificacion_movimiento" cols="110" rows="3" id="justificacion_movimiento"></textarea>
    </label></td>
  </tr>
  
  <td align="right" >Elaborado por:</td>
      <td ><?
      ?>
        <select name="ordenado_por" id="ordenado_por">
          <?
		  $sql_configuracion_almacen = mysql_query("select * from configuracion_almacen")or die(mysql_error());
		  $bus_configuracion_almacen = mysql_fetch_array($sql_configuracion_almacen);
		  ?>
          <option id="<?=$bus_configuracion_almacen["primero_almacen"]?>" o
                        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_almacen["ci_primero_almacen"]?>'">
          <?=$bus_configuracion_almacen["primero_almacen"]?>
          </option>
          <? if($bus_configuracion_almacen["segundo_almacen"] <> ''){ ?> 
          		<option id="<?=$bus_configuracion_almacen["segundo_almacen"]?>"
                        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_almacen["ci_segundo_almacen"]?>'">
            <?=$bus_configuracion_almacen["segundo_almacen"]?>
          </option> <? } 
		  	if($bus_configuracion_almacen["tercero_almacen"] <> ''){  ?>
          <option id="<?=$bus_configuracion_almacen["tercero_almacen"]?>"
               	onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_almacen["ci_tercero_almacen"]?>'">
            <?=$bus_configuracion_almacen["tercero_almacen"]?>
          </option>
          <? } ?>
              
        </select></td>
      <td align="right"> C.I.:</td>
      <td colspan="3" ><input type="text" name="cedula_ordenado" id="cedula_ordenado" value="<?=$bus_configuracion_almacen["ci_primero_almacen"]?>"/></td>
  	 
  
  <tr>
    <td colspan="6" align="center">
    
    	<table>
            <tr>
            <td><input type="button" name="boton_siguiente" id="boton_siguiente" value="Siguiente" class="button" onClick="ingresarDatosBasicos()"></td>
            <td><input type="button" name="boton_actualizar" id="boton_actualizar" value="Actualizar" class="button" style="display:none" onClick="actualizarDatosBasicos()"></td>
            <td><input type="button" name="boton_anular" id="boton_anular" value="Anular" class="button" style="display:none" onblur="anularMovimiento()"></td>
            <td><input type="button" name="boton_procesar" id="boton_procesar" value="Procesar" class="button" style="display:none" onclick="procesarMovimiento()"></td>
            </tr>     
        </table>
    
    </td>
  </tr>
 
</table>
</div>
<div id="divGrupoAjuste" style="display:none">
<div id="divAjusteTitulo" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; margin-top:250px; width:950px; margin-left:-475px">
<table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" onClick="abrirCerrarAjuste()" style="cursor:pointer; color:#FFF"><strong>Material a ajustar</strong></td>
    </tr>
</table>
</div>

<div id="divAjuste" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:165px; margin-top:270px; width:950px; margin-left:-475px; overflow:auto">

<table width="95%" border="0" align="center">
    <input type="hidden" name="idinventario_materia" id="idinventario_materia" value="" />
    <input type="hidden" name="serializado" id="serializado" value="" />
    <input type="hidden" name="caduca" id="caduca" value="" />
    <tr>
    	<td colspan="4" align="center">
        <img src='imagenes/search0.png' alt="Buscar Materiales" onClick="abreVentanaMateriales()" title="Buscar Materiales" style="cursor:pointer">
        <img src="imagenes/nuevo.png" width="16" height="16" onclick="limpiarMateriaAjuste()" title="Limpiar y seleccionar otro material" style="cursor:pointer"/>
        </td>
    </tr>
    <tr>
    	<td align="left" width="10%" style="font:Verdana, Geneva, sans-serif; font-size:11px">C&oacute;digo</td>
        <td align="left" width="55%" style="font:Verdana, Geneva, sans-serif; font-size:11px">Descripci&oacute;n</td>
        <td colspan="2" align="left" width="29%" style="font:Verdana, Geneva, sans-serif; font-size:9px" id="celda_cantidad_actual">El ajuste se hara en el Inventario Inicial</td>
    </tr>
    <tr><input type="hidden" name="cantidad_actual_sin_formato" id="cantidad_actual_sin_formato" value="" />
    	<td valign="top"><input type="text" name="codigo_materia" id="codigo_materia" style="width:130px; height:20px" readonly="readonly" /> </td>
        <td rowspan="3" valign="top"><textarea name="descripcion_materia" cols="80" rows="3" id="descripcion_materia" autocomplete="OFF"  style="width:550px; height:80px" readonly="readonly"></textarea></td>
        <td align="left" width="30%" style="font:Verdana, Geneva, sans-serif; font-size:11px">Actual</td>
         <td align="right"><input type="text" name="cantidad_actual" id="cantidad_actual" style="width:150px; height:20px; text-align:right" readonly="readonly" /> </td>
    </tr>
    <tr>
    	<td align="left" width="10%" style="font:Verdana, Geneva, sans-serif; font-size:11px">Unidad de Medida</td>
        <td align="left" width="30%" style="font:Verdana, Geneva, sans-serif; font-size:11px">Ajuste</td>
   		<input type="hidden" name="idcantidad_ajuste" id="idcantidad_ajuste" value="" />
    	<td align="right"><input type="text" name="cantidad_ajuste" id="cantidad_ajuste" onBlur="formatoNumero(this.name); llenar_ajustada()" style="width:150px; height:20px; text-align:right" /> </td>
    </tr>
    <tr>
    	<td valign="top"><input type="text" name="unidad_materia" id="unidad_materia" style="width:130px; height:20px" readonly="readonly" /> </td>
        <td align="left" width="30%" style="font:Verdana, Geneva, sans-serif; font-size:11px">Ajustada</td>
        <input type="hidden" name="idcantidad_ajustada" id="idcantidad_ajustada" value="0" />
        <td align="right"><input type="text" name="cantidad_ajustada" id="cantidad_ajustada" onBlur="formatoNumero(this.name)" style="width:150px; height:20px; text-align:right" readonly="readonly" /></td>
    </tr>
    <tr>
      <td colspan="4" align="center"><input type="button" name="boton_ajuste" id="boton_ajuste" value="Registrar Ajuste" class="button" onClick="ingresarMaterialAjuste()" style="display:block"><input type="button" name="boton_cierre_ajuste" id="boton_cierre_ajuste" value="Finalizar Ajuste del Material" class="button" onClick="finalizarAjusteMateria()" style="display:none"></td>
    </tr>  
    <tr><td colspan="4">
    
    <div id="barra_seriales" style="display:none">    
        <table align="center" width="95%" style="color:#FFF; background-color:#09F;font-weight:bold">
            <tr>
                <td style="color:#FFF" onClick="abrirCerrarSeriales()" style="cursor:pointer" >Seriales
                </td>
            </tr>
        </table>
      	<input name="idrelacion_serial_materia" type="hidden" id="idrelacion_serial_materia">
        <div id="mostrar_seriales" style="display:block">
        <div id="cargador_serial" style="display:block"> 
        <table align="center" width="50%">
            <tr>
                <td align="right">Serial</td>
                <td id="serial" ><input type="text" id="serialMateria" name="serialMateria" size="16" >&nbsp;<img src="imagenes/validar.png" style="cursor:pointer" onClick="ingresarSerial();" title="Ingresar Serial" id="imagen_carga_serial">    
                </td>
                <td align="center" id="van_serial">&nbsp;</td>
            </tr>
        </table>
        <br>
        </div>
        <div id="lista_seriales">
            &nbsp;
        </div>
	</div>
    </div>
    </td></tr>
 	 
    <tr><td colspan="4">
    
    <div id="barra_fecha_vencimiento" style="display:none">       
        <table align="center" width="95%" style="color:#FFF; background-color:#09F;font-weight:bold">
            <tr>
                <td style="color:#FFF" onClick="abrirCerrarFechaVencimiento()" style="cursor:pointer" >Fechas de Vencimiento</td>
            </tr>
        </table>
        <input name="idrelacion_vencimiento_materia" type="hidden" id="idrelacion_vencimiento_materia">
        <div id="mostrar_fecha_vencimiento" style="display:block">
        <div id="cargador_fecha_vencimiento" style="display:none">
        <table align="center" width="70%">
            <tr>
             	<td align="right" class='viewPropTitle'>Lote</td>
                <td id="celda_lote">
                  	<input type="text" id="lote" name="lote" size="12" >
                </td>
                <td align="right" class='viewPropTitle'>Fecha de Vencimiento</td>
                <td id="fecha_vencimiento">
                  	<input name="fecha_vencimiento_materia" type="text" id="fecha_vencimiento_materia" size="13" maxlength="10">
					<img src="imagenes/jscalendar0.gif" name="f_trigger_cfv" width="16" height="16" id="f_trigger_cfv" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
				  <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_vencimiento_materia",
							button        : "f_trigger_cfv",
							align         : "Tr",
							ifFormat    	: "%Y-%m-%d"
							});
						</script>		
                 </td>
               
                <td align="right" class='viewPropTitle'>Cantidad</td>
                <td id="cantidad_fecha">
                  	<input type="text" id="cantidad_fecha_vencimiento" name="cantidad_fecha_vencimiento" size="12" >
               
              		&nbsp;<img src='imagenes/validar.png' onClick="ingresarFVencimiento();" style="cursor:pointer" title="Ingresar Lote" id="imagen_carga_fecha_vencimiento">
                </td>
                <td align="center" id="van_fecha">&nbsp;</td>
            </tr>
        </table>
	    <br>
        </div>
        <div id="lista_fecha_vencimiento">
            <table class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
              <thead>
                <tr>
                  <td width="20%" align="center" class="Browse">Lote</td>	
                  <td width="20%" align="center" class="Browse">Fecha Vencimiento</td>
                  <td width="20%" align="center" class="Browse">Cantidad del Lote</td>
                  <td width="20%" align="center" class="Browse">Disponibles</td>
                  <td width="20%" align="center" class="Browse">Acci&oacute;n</td>
                </tr>
             </thead>
           </table>
        </div>
    </div>
    </div>
    
    </td>
    </tr>
    
    
</table>
</div>
</div> 


<div id="divMateriaAjustada" style="display:none">
<div id="divAjustadosTitulo" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; margin-top:475px; width:950px; margin-left:-475px">
<table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" onClick="abrirCerrarMateriaAjuste()" style="cursor:pointer; color:#FFF"><strong>Materiales en Movimiento</strong></td>
    </tr>
</table>
</div>

<div id="divListaMateriaAjustada" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:300px; margin-top:495px; width:950px; margin-left:-475px; overflow:auto">
<div id="lista_materiales">
<table class="Browse" cellpadding="0" cellspacing="0" width="95%" border="0" align="center">
  <thead>
    <tr>
      <td width="10%" align="center" class="Browse">C&oacute;digo</td>	
      <td width="75%" align="center" class="Browse">Descripci&oacute;n</td>
      <td width="10%" align="center" class="Browse">Cantidad Ajustada</td>
      <td width="5%" align="center" class="Browse">Acci&oacute;n</td>
    </tr>
 </thead>         
    
</table>
</div>
</div>
</div> 