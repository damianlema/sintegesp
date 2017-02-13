<html>
    <head>
        <link href="modulos/bienes/css/estilos_bienes.css" rel="stylesheet" type="text/css" />
        <script src="modulos/bienes/js/movimientos_ajax.js"></script>
</head>
<body>




<h4 align=center>
Movimientos de Bienes</h4>
<h2 class="sqlmVersion"></h2>

<input type="hidden" id="idmovimiento" name="idmovimiento">



<table align="center">
<tr>
<td><img src="imagenes/search0.png" alt="Buscar" title="Buscar" onClick="window.open('lib/listas/listarMovimientosMuebles.php','','width=900; height=600, scrollbars=yes,resizabled=no')" style="cursor:pointer"></td>
<td><img src="imagenes/nuevo.png" alt="Nuevo" title="Nuevo" onClick="window.location.href = 'principal.php?accion=1101&modulo=8'" style="cursor:pointer"></td>
<td >
        <div align="center" id="celdaImprimir" style="display:none">
          <img src="imagenes/imprimir.png" title="Imprimir Movimiento"  onClick="document.getElementById('pdf').src='lib/reportes/bienes/reportes.php?nombre=movimiento&idmovimiento='+document.getElementById('idmovimiento').value; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block';" style="cursor:pointer" />
        </div>
      </td>
</tr>
</table>

<div id="divImprimir" style="display:none; position:absolute; z-index:10; background-color:#CCCCCC; border:1px solid;">
<table align="center">
	<tr><td align="right"><a href="javascript:;" onClick="document.getElementById('divImprimir').style.display='none';">X</a></td></tr>
   	<tr><td><iframe name="pdf" id="pdf" style="display:none" height="600" width="750"></iframe></td></tr>
</table>
</div>

<? // CABEZERA DEL MOVIMIENTO ?>

<div id="divAjusteTituloDatos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:95%; margin-left:-47%;margin-top:5px">
 <table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>Datos del Movimiento</strong></td>
    </tr>
</table>
</div>
<div id="tablaDatosBasicos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:190px; width:95%; margin-left:-47%; margin-top:25px; overflow:auto"> 

<table width="100%" border="0" align="center">
  <tr>
    <td width="20%"align="right" class='viewPropTitleNew'>Nro. de Movimiento:</td>
    <td width="15%" style="border:1px solid #999; background-color:#FFF" id="celda_nro_movimiento"><strong>&nbsp;Aun no generado</strong></td>
    <td width="15%" align="right" class='viewPropTitleNew'>Fecha del Movimiento:</td>
    <td width="53%" ><label>
      <input type="text" name="fecha_movimiento" id="fecha_movimiento" size="12" value="<?=date("Y-m-d")?>">
    </label>
    <img src="imagenes/jscalendar0.gif" name="f_trigger_fecha_movimiento" width="16" height="16" id="f_trigger_fecha_movimiento" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                    Calendar.setup({
                                    inputField    : "fecha_movimiento",
                                    button        : "f_trigger_fecha_movimiento",
                                    align         : "Tr",
                                    ifFormat      : "%Y-%m-%d"
                                    });
                                </script> 
    </td>
    
  </tr>
  <tr>
    <td align="right" class='viewPropTitleNew'>Afecta:</td>
    <td><label>
      <select name="afecta" id="afecta">
      	<option value="0">.:: Seleccione ::.</option>
      	<option value="incorporacion" onClick="consultarTipoMovimiento('incorporacion')">Incorporaci&oacute;n</option>
        <option value="desincorporacion" onClick="consultarTipoMovimiento('desincorporacion')">Desincorporaci&oacute;n</option>
        <option value="ambos" onClick="consultarTipoMovimiento('ambos'), document.getElementById('accion_tipo_movimiento').value = 'ambos'">Ambos</option>
      </select>
    </label></td>
    <td align="right" class='viewPropTitleNew'>Tipo</td>
    <td><label>
      <select name="tipo" id="tipo">
        <option value="mueble">Mueble</option>
      </select>
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitleNew'>Tipo de Movimiento:<input type="hidden" name="accion_tipo_movimiento" id="accion_tipo_movimiento"></td>
    <td colspan="3" id="celda_tipo_movimiento"><label>
      <select name="tipo_movimiento" id="tipo_movimiento">
     	<option value="0">.:: Seleccione el tipo de Afectaci&oacute;n</option>
      </select>
    </label>
    
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitleNew'>Nro. de Documento:</td>
    <td><label>
      <input type="text" name="nro_documento" id="nro_documento">
    </label></td>
    <td align="right" class='viewPropTitleNew'>Fecha del Documento:</td>
    <td><label>
      <input type="text" name="fecha_documento" id="fecha_documento" size="12">
    </label>
    <img src="imagenes/jscalendar0.gif" name="f_trigger_fecha_documento" width="16" height="16" id="f_trigger_fecha_documento" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                    Calendar.setup({
                                    inputField    : "fecha_documento",
                                    button        : "f_trigger_fecha_documento",
                                    align         : "Tr",
                                    ifFormat      : "%Y-%m-%d"
                                    });
                                </script> 
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitleNew'>Concepto del Movimiento:</td>
    <td colspan="3"><label>
      <textarea name="justificacion_movimiento" cols="135" rows="3" id="justificacion_movimiento"></textarea>
    </label></td>
  </tr>
  
</table>
<table align="center"> 
            <tr>
            	<td><input type="button" name="boton_siguiente" id="boton_siguiente" value="Siguiente" class="button" onClick="ingresarDatosBasicos()"></td>
            	<td><input type="button" name="boton_anular" id="boton_anular" value="Anular" class="button" style="display:none" onBlur="anularMovimiento()"></td>
            	<td><input type="button" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" style="display:none" onClick="procesarModificar()"></td>
            	<td><input type="button" name="boton_procesar" id="boton_procesar" value="Procesar" class="button" style="display:none" onClick="procesarMovimiento()"></td>
            </tr>
     	</table>
</div>

<?

//********************************************************************************************************************************************************************
//  INICIO INCORPORACION BIENES NUEVOS
//********************************************************************************************************************************************************************
?>

<div id="bienes_nuevos" style="display:none; position:absolute; left:50%; width:95%; margin-left:-47%; height:100px !important; min-height:100px; margin-top:220px; overflow:auto">




<input type="hidden" name="idmovimientos_bienes_nuevos" id="idmovimientos_bienes_nuevos">

<table width="100%">
<tr>
<td>
<div id="tabsF">
  <ul>
    <li>
    	<a href="javascript:;" onClick="document.getElementById('divDatosPrincipales').style.display = 'block', document.getElementById('divDatosUbicacion').style.display = 'none', document.getElementById('divDatosFotos').style.display = 'none'"><span>Detalles Incorporaci&oacute;n</span></a>
    </li>
    <li style="visibility:hidden" id="li_bienes_adquisicion_bienes_nuevos">
        <a href="javascript:;" onClick="document.getElementById('divDatosPrincipales').style.display = 'none', document.getElementById('divDatosUbicacion').style.display = 'block', document.getElementById('divDatosFotos').style.display = 'none'"><span>Bienes Incorporaci&oacute;n</span>
        </a>
    </li>
    <li style="visibility:hidden" id="li_registro_fotografico_bienes_nuevos">
        <a href="javascript:;" onClick="document.getElementById('divDatosPrincipales').style.display = 'none', document.getElementById('divDatosUbicacion').style.display = 'none', document.getElementById('divDatosFotos').style.display = 'block'"><span>Registro Fotogr&aacute;fico</span>
        </a>
    </li>
  </ul>
</div>
</td>
</tr>
</table>

</div>


<div id="divDatosPrincipales" style="display:none; width:70%; height:370px; overflow:auto; "> 
<div id="dbienes_nuevos_detalle_adquisicion" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:900px; width:95%; margin-left:-47%; margin-top:265px; overflow:auto"> 



	<table width="100%" align="center" id="bienes_nuevos_detalle_adquisicion" style="display:block">
    	<tr>
    	  <td align="right" class='viewPropTitleNew'>Nro. Documento:</td>
    	  <td>
    	  <table>
          	<tr>
            <td>
            <input type="text" name="nro_documento_bienes_nuevos" id="nro_documento_bienes_nuevos">
            </td>
            <td><img src="imagenes/search0.png" alt="Buscar OC" title="Buscar OC" onClick="window.open('lib/listas/listarCompromisos.php','','resisable=no, scrollbars=yes, width = 900, height= 600')"></td>
            </tr>
          </table>
    	  
          </td>
    	  <td align="right" class='viewPropTitleNew'>Fecha Documento:</td>
    	  <td><label>
    	    <input type="text" name="fecha_documento_bienes_nuevos" id="fecha_documento_bienes_nuevos" size="12" readonly>
    	  </label>
          <img src="imagenes/jscalendar0.gif" name="f_trigger_fecha_documento_bienes_nuevos" width="16" height="16" id="f_trigger_fecha_documento_bienes_nuevos" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                    Calendar.setup({
                                    inputField    : "fecha_documento_bienes_nuevos",
                                    button        : "f_trigger_fecha_documento_bienes_nuevos",
                                    align         : "Tr",
                                    ifFormat      : "%Y-%m-%d"
                                    });
                                </script> 
          
          </td>
  	  </tr>
    	<tr>
    	  <td align="right" class='viewPropTitleNew'>Proveedor / Donador:</td>
    	  <td colspan="3"><label>
    	    <input name="proveedores_bienes_nuevos" type="text" id="proveedores_bienes_nuevos" size="120">
    	  </label></td>
   	  </tr>
    	<tr>
    	  <td align="right" class='viewPropTitleNew'>Nro. Factura:</td>
    	  <td><label>
    	    <input type="text" name="nro_factura_bienes_nuevos" id="nro_factura_bienes_nuevos">
    	  </label></td>
    	  <td align="right" class='viewPropTitleNew'>Fecha Factura:</td>
    	  <td><label>
    	    <input type="text" name="fecha_factura_bienes_nuevos" id="fecha_factura_bienes_nuevos" size="12" readonly>
    	  </label>
          <img src="imagenes/jscalendar0.gif" name="f_trigger_fecha_factura_bienes_nuevos" width="16" height="16" id="f_trigger_fecha_factura_bienes_nuevos" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                    Calendar.setup({
                                    inputField    : "fecha_factura_bienes_nuevos",
                                    button        : "f_trigger_fecha_factura_bienes_nuevos",
                                    align         : "Tr",
                                    ifFormat      : "%Y-%m-%d"
                                    });
                                </script> 

          </td>
  	  </tr>
    	<tr>
    	  <td colspan="4" align="center"><input type="button" id="boton_ingresar_cabecera_bienes_nuevos" name="boton_ingresar_cabecera_bienes_nuevos" value="Guardar" onClick="guardarInformacionPrincipalBienesNuevos()" class="button"></td>
        </tr>
  </table>
</div>
</div>

<div id="mensaje_error" style="display:none; position:absolute; z-index:100; left:50%; height:26px; width:385px; margin-left:-210px; margin-top:300px; overflow:auto">&nbsp;</div>

<div id="divDatosUbicacion" style="display:none; width:70%; height:370px; overflow:auto; "> 
<div id="dbienes_nuevos_ubicacion_organizacional" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:900px; width:95%; margin-left:-47%; margin-top:265px; overflow:auto">  
  
    <table width="100%" border="0" align="center" id="bienes_nuevos_ubicacion_organizacional" style="display:block">
      <tr>
        <td colspan="6" class='viewPropTitleNew'><strong>Ubicaci&oacute;n Organizacional</strong></td>
      </tr>
      <tr>
        <td width="17%" align="right" class='viewPropTitleNew'>Organizaci&oacute;n:</td>
        <td colspan="5"><label>
          <select name="organizacion_bienes_nuevos" id="organizacion_bienes_nuevos" style="width:600px">
          <option value="0">.:: Seleccione ::.</option>
		  <?
                $sql_organizacion = mysql_query("select * from organizacion");
				while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
					?>
					<option value="<?=$bus_organizacion["idorganizacion"]?>" onClick="consultarNivelOrganizacional('<?=$bus_organizacion["idorganizacion"]?>', 'nivel_organizacional_bienes_nuevos', 'div_nivel_organizacional_bienes_nuevos')"><?=$bus_organizacion["denominacion"]?></option>
					<?
				}
				?>
          </select>
        </label></td>
      </tr>
      <tr>
        <td align="right" class='viewPropTitleNew'>Nivel Organizacional:</td>
        <td colspan="5" id="div_nivel_organizacional_bienes_nuevos"><label>
          <select name="nivel_organizacional_bienes_nuevos" id="nivel_organizacional_bienes_nuevos" style="width:600px">
          <option value="0">.:: Seleccione la Organizacion ::.</option>
          </select>
        </label></td>
      </tr>
      <tr>
        <td colspan="6" class='viewPropTitleNew'><strong>Detalles del Bien Mueble</strong></td>
      </tr>
      
      <tr>
        <td align="right" class='viewPropTitleNew'>C&oacute;digo del Catalogo:</td>
        <td colspan="5">
        	<input type="hidden" id="idcodigo_catalogo_bienes_nuevos" name="idcodigo_catalogo_bienes_nuevos">
          	<input name="codigo_catalogo_bienes_nuevos" type="text" id="codigo_catalogo_bienes_nuevos" size="100" readonly>
          	<img src="imagenes/search0.png" style="cursor:pointer" id="imagen_seleccionar_codigo_bien_nuevo" onClick="window.open('modulos/bienes/lib/listar_detalles.php?destino=movimientos','','resizable=no, scrollbars=yes, width=900, height=600')">
        </td>
      </tr>
      
      
      <tr>
        <td align="right" class='viewPropTitleNew'>Cantidad del Bien:</td>
        <td colspan="5">
        <input type="text" name="cantidad_bien" id="cantidad_bien" value='1' style="text-align:center" size="5">
        <? /*
        <input type="text" name="codigo_bien_bienes_nuevos" id="codigo_bien_bienes_nuevos" size="35" onBlur="validarCodigoBien(this.value)">
      	
      	<input type="hidden" id="idcodigo_bien_bienes_nuevos" name="idcodigo_bien_bienes_nuevos" maxlength="25" size="25">
      
      	<img id='idgenerar_codigo' src='imagenes/refrescar.png' title="Generar C&oacute;digo" 
        	onClick="generar_codigo()" style="cursor:pointer" style="display:block"> 
		*/ ?>
        </td>
      </tr>
      <tr>
        <td align="right" class='viewPropTitleNew'>Tipo:</td>
        <td id="celda_tipo_detalle">
            <select name="tipo_bienes_nuevos" id="tipo_bienes_nuevos" >
              <option value="0">.:: Seleccione el c&oacute;digo de catalogo::.</option>
        	</select>
        </td>
        <td align="right" class='viewPropTitleNew'>Ubicaci&oacute;n:</td>
        <td ><label>
           <select name="ubicacion_bienes_nuevos" id="ubicacion_bienes_nuevos">
           <?
           $sql_ubicacion = mysql_query("select * from ubicacion");
		   while($bus_ubicacion = mysql_fetch_array($sql_ubicacion)){
		  	?>
			<option value="<?=$bus_ubicacion["idubicacion"]?>">(<?=$bus_ubicacion["codigo"]?>)&nbsp;<?=$bus_ubicacion["denominacion"]?></option>
			<?
		   }
		   ?>
           </select>
           </label>
        </td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" class='viewPropTitleNew'>Especificaciones:</td>
        <td colspan="5"><label>
          <textarea name="especificaciones_bienes_nuevos" cols="140" rows="2" id="especificaciones_bienes_nuevos"></textarea>
        </label></td>
      </tr>
      <tr>
        <td align="right" class='viewPropTitleNew'>Marca:</td>
        <td><label>
          <input type="text" name="marca_bienes_nuevos" id="marca_bienes_nuevos" size="30">
        </label></td>
        <td align="right" class='viewPropTitleNew'>Modelo:</td>
        <td><label>
          <input type="text" name="modelos_bienes_nuevos" id="modelos_bienes_nuevos" size="30">
        </label></td>
        <td colspan="2">&nbsp;</td>
      </tr>
     
      <tr>
        <td align="right" class='viewPropTitleNew'>Serial(es):</td>
        <td colspan="5"><label>
          <input type="text" name="tipo_bienes_nuevos" id="seriales_bienes_nuevos" size="140">
        </label></td>
      </tr>
      <tr>
        <td align="right" class='viewPropTitleNew'>Accesorios:</td>
        <td colspan="5"><label>
        	<textarea name="accesorios_bienes_nuevos" cols="140" rows="2" id="accesorios_bienes_nuevos"></textarea>
        </label></td>
      </tr>
      <tr>
        <td  align="right" class='viewPropTitleNew'>Costo Bs:</td>
        <td><label>
          <input type="text" name="costo_bienes_nuevos" id="costo_bienes_nuevos" onKeyUp="calcularDepreciacionAnual(), calcularDepreciacionAcumulada()" onBlur="document.getElementById('costo_ajustado_bienes_nuevos').value = parseFloat(document.getElementById('mejoras_bienes_nuevos').value)+parseFloat(this.value)" value="0" size="20" onClick="this.select()" style="text-align:right">          
        </label></td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" class='viewPropTitleNew'>Mejoras Bs:</td>
        <td>
          <input type="text" name="mejoras_bienes_nuevos" id="mejoras_bienes_nuevos" style="text-align:right" onKeyUp="calcularDepreciacionAnual(), calcularDepreciacionAcumulada()" onBlur="document.getElementById('costo_ajustado_bienes_nuevos').value = parseFloat(document.getElementById('costo_bienes_nuevos').value)+parseFloat(this.value)" value="0" size="20">
        </td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr> 
        <td align="right" class='viewPropTitleNew'>Costo Ajustado Bs:</td>
        <td><label>
          <input type="text" name="costo_ajustado_bienes_nuevos" id="costo_ajustado_bienes_nuevos" style="text-align:right" readonly size="20">
        </label></td>
        <td colspan="4">&nbsp;</td>
      </tr> 
      <tr>
        <td align="right" class='viewPropTitleNew'>Valor Residual Bs:</td>
        <td><label>
          <input type="text" name="valor_residual_bienes_nuevos" id="valor_residual_bienes_nuevos" value="0" size="20" onKeyUp="calcularDepreciacionAnual(), calcularDepreciacionAcumulada()" onClick="this.select()" style="text-align:right">
        </label></td>
        <td colspan="4">&nbsp;</td>
      </tr> 
      <tr>
        <td align="right" class='viewPropTitleNew'>Vida Util (Años):</td>
        <td><label>
          <input type="text" name="vida_util_bienes_nuevos" id="vida_util_bienes_nuevos" value="0" size="5" onKeyUp="calcularDepreciacionAnual(), calcularDepreciacionAcumulada()" onClick="this.select()" style="text-align:right">
        </label></td>
      	<td colspan="4">&nbsp;</td>
      </tr> 
      <tr>    
        <td align="right" class='viewPropTitleNew'>Depreciaci&oacute;n Anual Bs:</td>
        <td><label>
          <input type="text" name="depreciacion_anual_bienes_nuevos" id="depreciacion_anual_bienes_nuevos" style="text-align:right" readonly>
        </label></td>
        <td colspan="4">&nbsp;</td>
      </tr> 
      <tr>
        <td align="right" class='viewPropTitleNew'>Depreciaci&oacute;n Acumulada Bs:</td>
        <td><label>
          <input type="text" name="depreciacion_acumulada_bienes_nuevos" id="depreciacion_acumulada_bienes_nuevos" style="text-align:right" readonly>
        </label></td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" class='viewPropTitleNew'>Asegurado</td>
        <td><input name="asegurado_bienes_nuevos" type="checkbox" id="asegurado_bienes_nuevos" value="si" onClick="validarSeleccionado(this.id)" style="cursor:pointer">    	</td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
    	<td colspan="6">

            <table width="100%" border="0" cellspacing="0" cellpadding="4" id="tabla_aseguradora" style="display:none">
              <tr>
                <td width="21%" align="right" class='viewPropTitleNew'>Aseguradora:</td>
                <td colspan="3">
                  <input name="aseguradora" type="text" id="aseguradora" size="80"></td>
              </tr>
              <tr>
                <td align="right" class='viewPropTitleNew'>Nro. Poliza:</td>
                <td width="18%">
                  <input type="text" name="nro_poliza" id="nro_poliza">        </td>
                <td width="17%" align="right" class='viewPropTitleNew'>Fecha de Vencimiento:</td>
                <td width="44%">
                  
                  
               <table>
                  <tr>
                      <td>
                      <input name="fecha_vencimiento" type="text" id="fecha_vencimiento" size="12">              </td>
                      <td>
                      <img src="imagenes/jscalendar0.gif" name="fecha_v" width="16" height="16" id="fecha_v" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                        <script type="text/javascript">
                                            Calendar.setup({
                                            inputField    : "fecha_vencimiento",
                                            button        : "fecha_v",
                                            align         : "Tr",
                                            ifFormat      : "%Y-%m-%d"
                                            });
                                        </script>             </td>
                 </tr>
              </table>       </td>
              </tr>
              <tr>
                <td align="right" class='viewPropTitleNew'>Monto Poliza:</td>
                <td>
                  <input name="monto_poliza" type="text" id="monto_poliza" size="20" style="text-align:right" value="0">        </td>
        
                <td align="right" class='viewPropTitleNew'>Monto Asegurado:</td>
                <td>
                  <input name="monto_asegurado" type="text" id="monto_asegurado" size="20" style="text-align:right" value="0">        </td>
              </tr>
            </table> 
    	</td>
  	  </tr>
      <tr>
        <td colspan="6" align="center">
        <input type="button" id="boton_ingresar_bienes_nuevos" name="boton_ingresar_bienes_nuevos" value="Ingresar" class="button" onClick="ingresarBienesNuevos()">
        <input type="button" id="boton_modificar_bienes_nuevos" name="boton_modificar_bienes_nuevos" value="Modificar" class="button" style="display:none" onClick="modificarBienesNuevos()">
        </td>
      </tr>
      <tr>
          <td colspan="6">
              <div id="lista_bienes_nuevos"></div>    
          </td>
      </tr>
    </table>
 </div>   
 </div>   
  <div id="divDatosFotos" style="display:none; width:70%; height:370px; overflow:auto; ">
  <div id="tabla_registro_fotografico_bienes_nuevos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:900px; width:95%; margin-left:-47%; margin-top:265px; overflow:auto">   
    </div>
    <br>
    <div id="listaFotosBienesNuevos" style="display:none; position:absolute; z-index:101; margin-left:150px; margin-top:300px; overflow:auto"></div>
    <? /*
    <div id="listaFotosBienesNuevos" style="display:none; z-index:110" margin-top:300px;></div>
	*/
	?>
  </div>

<?

//********************************************************************************************************************************************************************
//  FIN INCORPORACION BIENES NUEVOS
//********************************************************************************************************************************************************************
?>





<?

//********************************************************************************************************************************************************************
//  INICIO INCORPORACION BIENES EXISTENTES
//********************************************************************************************************************************************************************
?>


<div id="existentes_incorporacion" style="display:none; position:absolute; left:50%; width:95%; margin-left:-47%; height:100px !important; min-height:100px; margin-top:220px; overflow:auto">


<input type="hidden" name="idmovimientos_bienes_nuevos" id="idmovimientos_bienes_nuevos">

<table width="100%">
<tr>
<td>
<div id="tabsF">
  <ul>
    <li>
    	<a href="javascript:;" onClick="document.getElementById('tabla_detalles_movimientos_existente_incorporacion').style.display = 'block', document.getElementById('tabla_registro_fotografico_existentes_incorporacion1').style.display = 'none'">
                            <span>Detalle del Movimiento</span>
                        </a>
    </li>
    <li style="visibility:hidden" id="li_registro_fotografico_bienes_incorporacion">
         <a href="javascript:;" onClick="document.getElementById('tabla_detalles_movimientos_existente_incorporacion').style.display = 'none', document.getElementById('tabla_registro_fotografico_existentes_incorporacion1').style.display = 'block'">
                            <span>Registro Fotogr&aacute;fico</span>
                        </a>
    </li>
  </ul>
</div>
</td>
</tr>
</table>

</div>


<div id="tabla_detalles_movimientos_existente_incorporacion" style="display:none; width:70%; height:370px; overflow:auto; "> 
<div id="dbienes_existentes_detalle_adquisicion" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:900px; width:95%; margin-left:-47%; margin-top:265px; overflow:auto">

<? /*
<form name="formulario_existentes_incorporacion" id="formulario_existentes_incorporacion">
*/ ?>
		
    <table width="100%" align="center" id="tabla_detalles_movimientos_existente_incorporacion">
    <tr>
      <td>
        <table width="100%">
        	<tr>
        	  <td colspan="2" class='viewPropTitleNew'><strong>Ubicaci&oacute;n Organizacional</strong></td>
       	  </tr>
          <tr>
        	  <td width="17%" align="right" class='viewPropTitleNew'>Organizacion</td>
        	  <td width="83%"><label>
        	    <select name="organizacion_existentes_incorporacion" id="organizacion_existentes_incorporacion"  style="width:600px">
                <option>.:: Seleccione ::.</option>
				<?
                $sql_organizacion = mysql_query("select * from organizacion");
				while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
					?>
					<option value="<?=$bus_organizacion["idorganizacion"]?>" onClick="consultarNivelOrganizacional('<?=$bus_organizacion["idorganizacion"]?>', 'nivel_organizacional_existente_incorporacion', 'div_nivel_organizacional_existente_incorporacion')"><?=$bus_organizacion["denominacion"]?></option>
					<?
				}
				?>
      	      </select>
        	  </label></td>
      	  </tr>
        	<tr>
        	  <td align="right" class='viewPropTitleNew'>Nivel Organizacional</td>
        	  <td id="div_nivel_organizacional_existente_incorporacion">
        	   <select name="nivel_organizacional_existente_incorporacion" id="nivel_organizacional_existente_incorporacion"  style="width:600px">
      	      	<option value="0">.:: Seleccionar Organizacion ::.</option>
              </select>
        	  </td>
      	  </tr>
  </table>
  
  		
		<div id="tabla_bienes_existente_incorporacion" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:100px; width:1000px; margin-left:-500px; margin-top:5px; overflow:auto"></div>
 

      
  		<div id="datos_bienes_existente_incorporacion" style="display:block; position:absolute; left:50%; height:700px; width:1000px; margin-left:-500px; margin-top:115px; overflow:auto">	
        
    <table>
        <tr>
        	<td align="right" class='viewPropTitleNew'>C&oacute;digo del Bien:</td>
            <td colspan="5">
            <input type="hidden" name="idcodigo_bien_existente_incorporacion" id="idcodigo_bien_existente_incorporacion">
            <input type="text" name="codigo_bien_existente_incorporacion" id="codigo_bien_existente_incorporacion" readonly disabled="disabled">
            
            </td>
            
        </tr>
        <tr>
          <td align="right" class='viewPropTitleNew'>C&oacute;digo del Catalogo:</td>
          <td colspan="5">
          <input name="idcodigo_catalogo_existente_incorporacion" type="hidden" id="idcodigo_catalogo_existente_incorporacion" size="90" />
          <input name="codigo_catalogo_existente_incorporacion" type="text" id="codigo_catalogo_existente_incorporacion" size="90" readonly disabled="disabled"/>
          </td>
        </tr>
        <tr>
        	<td align="right" class='viewPropTitleNew'>Especificaciones:</td>
        	<td colspan="5"><textarea name="especificaciones_existente_incorporacion" cols="130" rows="3" id="especificaciones_existente_incorporacion" disabled="disabled"></textarea></td>
        </tr>    
        <tr>
        	<td align="right" class='viewPropTitleNew'>Mejoras Bs.:</td>
            <td colspan="5"><input type="text" name="mejoras_existente_incorporacion" id="mejoras_existente_incorporacion" disabled="disabled" align="right" value="0,00" onClick="this.select()"></td>
        </tr>
        <tr>
        	<td align="right" class='viewPropTitleNew'>Descripci&oacute;n Movimiento:</td>
        	<td colspan="5"><textarea name="descripcion_existente_incorporacion" id="descripcion_existente_incorporacion" cols="130" rows="3" disabled="disabled"></textarea></td>
        </tr>
        
        
        <tr>
            <td align="center" colspan="6">
            <input type="button" name="boton_ingresar_existentes_incorporacion" id="boton_ingresar_existentes_incorporacion" value="Ingresar" class="button" onClick="ingresarExistentesIncorporacion()" disabled="disabled">
            <input type="button" name="boton_modificar_existentes_incorporacion" id="boton_modifciar_existentes_incorporacion" value="Modificar" class="button" onClick="modificarExistentesIncorporacion()" style="display:none">
            </td>
  		</tr>
        <tr>
          <td colspan="6">
              <br />
              <div id="lista_existente_incorporacion"></div>
              <br>
          </td>
      </tr>
    </table>    
        
  </div>
  
  </td>
  </tr>
  
  
  </table>
 <? /* </form> <div id="listaFotosExistentesIncorporacion"  style="display:none;; width:60%; height:300px; background-color:#EAEAEA; border:#000000 solid 1px"></div> */ ?>
 </div> 
 </div>
  
 <div id="tabla_registro_fotografico_existentes_incorporacion1" style="display:none; width:70%; height:370px; overflow:auto; "> 
 <div id="tabla_registro_fotografico_existentes_incorporacion" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:900px; width:95%; margin-left:-47%; margin-top:265px; overflow:auto"></div>
 
 <div id="listaFotosExistentesIncorporacion" style="display:none; position:absolute; z-index:101; margin-left:150px; margin-top:300px; overflow:auto"></div>
 
 </div>



<table align="center" width="100%" id="existentes_incorporacion_ubicacion_destino" style="display:none">
  
        	<tr>
        	  <td colspan="2" class='viewPropTitle'><strong>Ubicacion Actual</strong></td>
       	  </tr>
        	<tr>
        	  <td width="17%" align="right" class='viewPropTitle'>Organizacion</td>
        	  <td width="83%"><label>
        	    <select name="organizacion_existentes_incorporacion" id="organizacion_existentes_incorporacion">
                <option>.:: Seleccione ::.</option>
				<?
                $sql_organizacion = mysql_query("select * from organizacion");
				while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
					?>
					<option value="<?=$bus_organizacion["idorganizacion"]?>" onClick="consultarNivelOrganizacional('<?=$bus_organizacion["idorganizacion"]?>', 'nivel_organizacional_existente_incorporacion', 'div_nivel_organizacional_existente_incorporacion')"><?=$bus_organizacion["denominacion"]?></option>
					<?
				}
				?>
      	      </select>
        	  </label></td>
      	  </tr>
        	<tr>
        	  <td align="right" class='viewPropTitle'>Nivel Organizacional</td>
        	  <td id="div_nivel_organizacional_existente_incorporacion">
        	   <select name="nivel_organizacional_existente_incorporacion" id="nivel_organizacional_existente_incorporacion">
      	      	<option value="0">.:: Seleccionar Organizacion ::.</option>
              </select>
        	  </td>
      	  </tr>
       
          <tr>
        	<td align="right" class='viewPropTitleNew'>Retorno Automatico:</td>
        	<td colspan="2"><input type="checkbox" name="retorno_existente_incorporacion" id="retorno_existente_incorporacion" disabled="disabled"></td>
            <td align="right" class='viewPropTitleNew'>Fecha de Retorno</td>
            <td><input type="text" size="12" name="fecha_retorno_existente_incorporacion" id="fecha_retorno_existente_incorporacion" disabled="disabled">
            <img src="imagenes/jscalendar0.gif" name="f_trigger_fecha_retorno_existente_incorporacion" width="16" height="16" id="f_trigger_fecha_retorno_existente_incorporacion" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                    Calendar.setup({
                                    inputField    : "fecha_retorno_existente_incorporacion",
                                    button        : "f_trigger_fecha_retorno_existente_incorporacion",
                                    align         : "Tr",
                                    ifFormat      : "%Y-%m-%d"
                                    });
                                </script>            </td>
        </tr>
  </table>




<?

//********************************************************************************************************************************************************************
//  FIN INCORPORACION BIENES EXISTENTES
//********************************************************************************************************************************************************************
?>






<?

//********************************************************************************************************************************************************************
//  INICIO DESINCORPORACION BIENES EXISTENTES
//********************************************************************************************************************************************************************
?>


<div id="existentes_desincorporacion" style="display:none; position:absolute; left:50%; width:95%; margin-left:-47%; height:100px !important; min-height:100px; margin-top:220px; overflow:auto">


<input type="hidden" name="idmovimientos_bienes_nuevos" id="idmovimientos_bienes_nuevos">

<table width="100%">
<tr>
<td>
<div id="tabsF">
  <ul>
    <li>
    	<a href="javascript:;" onClick="document.getElementById('tabla_detalles_movimientos_existente_desincorporacion').style.display = 'block', document.getElementById('tabla_registro_fotografico_existentes_desincorporacion1').style.display = 'none'">
                            <span>Detalle del Movimiento</span>
                        </a>
    </li>
    <li style="visibility:hidden" id="li_registro_fotografico_bienes_desincorporacion">
         <a href="javascript:;" onClick="document.getElementById('tabla_detalles_movimientos_existente_desincorporacion').style.display = 'none', document.getElementById('tabla_registro_fotografico_existentes_desincorporacion1').style.display = 'block'">
                            <span>Registro Fotogr&aacute;fico</span>
                        </a>
    </li>
  </ul>
</div>
</td>
</tr>
</table>

</div>


<div id="tabla_detalles_movimientos_existente_desincorporacion" style="display:none; width:70%; height:370px; overflow:auto; "> 
<div id="dbienes_dexistentes_detalle_adquisicion" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:900px; width:95%; margin-left:-47%; margin-top:265px; overflow:auto">

<? /*
<form name="formulario_existentes_incorporacion" id="formulario_existentes_incorporacion">
*/ ?>
		
    <table width="100%" align="center" id="tabla_detalles_movimientos_existente_desincorporacion">
    <tr>
      <td>
        <table width="100%">
        	<tr>
        	  <td colspan="2" class='viewPropTitleNew'><strong>Ubicaci&oacute;n Organizacional</strong></td>
       	  </tr>
          <tr>
        	  <td width="17%" align="right" class='viewPropTitleNew'>Organizacion</td>
        	  <td width="83%"><label>
        	    <select name="organizacion_existentes_desincorporacion" id="organizacion_existentes_desincorporacion"  style="width:600px">
                <option>.:: Seleccione ::.</option>
				<?
                $sql_organizacion = mysql_query("select * from organizacion");
				while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
					?>
					<option value="<?=$bus_organizacion["idorganizacion"]?>" onClick="consultarNivelOrganizacional('<?=$bus_organizacion["idorganizacion"]?>', 'nivel_organizacional_existente_desincorporacion', 'div_nivel_organizacional_existente_desincorporacion')"><?=$bus_organizacion["denominacion"]?></option>
					<?
				}
				?>
      	      </select>
        	  </label></td>
      	  </tr>
        	<tr>
        	  <td align="right" class='viewPropTitleNew'>Nivel Organizacional</td>
        	  <td id="div_nivel_organizacional_existente_desincorporacion">
        	   <select name="nivel_organizacional_existente_desincorporacion" id="nivel_organizacional_existente_desincorporacion"  style="width:600px">
      	      	<option value="0">.:: Seleccionar Organizacion ::.</option>
              </select>
        	  </td>
      	  </tr>
  </table>
  
  		
		<div id="tabla_bienes_existente_desincorporacion" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:100px; width:1000px; margin-left:-500px; margin-top:5px; overflow:auto"></div>
 

      
  		<div id="datos_bienes_existente_desincorporacion" style="display:block; position:absolute; left:50%; height:700px; width:1000px; margin-left:-500px; margin-top:115px; overflow:auto">	
        
    <table>
        <tr>
        	<td align="right" class='viewPropTitleNew'>C&oacute;digo del Bien:</td>
            <td colspan="5">
            <input type="hidden" name="idcodigo_bien_existente_desincorporacion" id="idcodigo_bien_existente_desincorporacion">
            <input type="text" name="codigo_bien_existente_desincorporacion" id="codigo_bien_existente_desincorporacion" readonly disabled="disabled">
            
            </td>
            
        </tr>
        <tr>
          <td align="right" class='viewPropTitleNew'>C&oacute;digo del Cat&aacute;logo:</td>
          <td colspan="5">
          <input name="idcodigo_catalogo_existente_desincorporacion" type="hidden" id="idcodigo_catalogo_existente_desincorporacion" size="90" />
          <input name="codigo_catalogo_existente_desincorporacion" type="text" id="codigo_catalogo_existente_desincorporacion" size="90" readonly disabled="disabled"/>
          </td>
        </tr>
        <tr>
        	<td align="right" class='viewPropTitleNew'>Especificaciones:</td>
        	<td colspan="5"><textarea name="especificaciones_existente_desincorporacion" cols="130" rows="3" id="especificaciones_existente_desincorporacion" disabled="disabled"></textarea></td>
        </tr>    
        
        <tr>
        	<td align="right" class='viewPropTitleNew'>Descripci&oacute;n Movimiento:</td>
        	<td colspan="5"><textarea name="descripcion_existente_desincorporacion" id="descripcion_existente_desincorporacion" cols="130" rows="3" disabled="disabled"></textarea></td>
        </tr>
        <tr>
            <td align="center" colspan="6">
            <table width="100%">
        	<tr>
        	  <td colspan="2" class='viewPropTitleNew'><strong>Reubicaci&oacute;n Organizacional (seleccione si va a ser trasladado a otro lugar de resguardo, de lo contrario permanecer&aacute; en su ubicaci&oacute;n actual)</strong></td>
       	  </tr>
          <tr>
        	  <td width="17%" align="right" class='viewPropTitleNew'>Organizaci&oacute;n</td>
        	  <td width="83%"><label>
        	    <select name="organizacion_existentes_desincorporacion_destino" id="organizacion_existentes_desincorporacion_destino"  style="width:600px">
                <option value="0">.:: Seleccione ::.</option>
				<?
                $sql_organizacion = mysql_query("select * from organizacion");
				while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
					?>
					<option value="<?=$bus_organizacion["idorganizacion"]?>" onClick="consultarNivelOrganizacional('<?=$bus_organizacion["idorganizacion"]?>', 'nivel_organizacional_existente_desincorporacion_destino', 'div_nivel_organizacional_existente_desincorporacion_destino')"><?=$bus_organizacion["denominacion"]?></option>
					<?
				}
				?>
      	      </select>
        	  </label></td>
      	  </tr>
        	<tr>
        	  <td align="right" class='viewPropTitleNew'>Nivel Organizacional</td>
        	  <td id="div_nivel_organizacional_existente_desincorporacion_destino">
        	   <select name="nivel_organizacional_existente_desincorporacion_destino" id="nivel_organizacional_existente_desincorporacion_destino"  style="width:600px">
      	      	<option value="0">.:: Seleccionar Organizaci&oacute;n ::.</option>
              </select>
        	  </td>
      	  </tr>
  		</table>
        </td>
        </tr>
        <tr>
            <td align="center" colspan="6">
            <input type="button" name="boton_ingresar_existentes_desincorporacion" id="boton_ingresar_existentes_desincorporacion" value="Ingresar" class="button" onClick="ingresarExistentesDesIncorporacion()" disabled="disabled">
            <input type="button" name="boton_modificar_existentes_desincorporacion" id="boton_modifciar_existentes_desincorporacion" value="Modificar" class="button" onClick="modificarExistentesDesIncorporacion()" style="display:none">
            </td>
  		</tr>
        <tr>
          <td colspan="6">
              <br />
              <div id="lista_existente_desincorporacion"></div>
              <br>
          </td>
      </tr>
    </table>    
    
  </div>
  
  </td>
  </tr>
  
  
  </table>
 <? /* </form> <div id="listaFotosExistentesIncorporacion"  style="display:none;; width:60%; height:300px; background-color:#EAEAEA; border:#000000 solid 1px"></div> */ ?>
 </div> 
 </div>
  
 <div id="tabla_registro_fotografico_existentes_desincorporacion1" style="display:none; width:70%; height:370px; overflow:auto; "> 
 <div id="tabla_registro_fotografico_existentes_desincorporacion" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:900px; width:95%; margin-left:-47%; margin-top:265px; overflow:auto"></div>
 
 <div id="listaFotosExistentesDesIncorporacion" style="display:none; position:absolute; z-index:105; margin-left:150px; margin-top:300px; overflow:auto"></div>
 
 </div>


<?

//********************************************************************************************************************************************************************
//  FIN DESINCORPORACION BIENES EXISTENTES
//********************************************************************************************************************************************************************
?>

<?

//********************************************************************************************************************************************************************
//  INICIO TRASLADO DE BIENES
//********************************************************************************************************************************************************************
?>


<div id="traslados" style="display:none; position:absolute; left:50%; width:95%; margin-left:-47%; height:100px !important; min-height:100px; margin-top:220px; overflow:auto">


<input type="hidden" name="idmovimientos_bienes_nuevos" id="idmovimientos_bienes_nuevos">

<table width="100%">
<tr>
<td>
<div id="tabsF">
  <ul>
    <li>
    	<a href="javascript:;" onClick="document.getElementById('tabla_detalles_traslados').style.display = 'block', document.getElementById('tabla_registro_fotografico_traslados1').style.display = 'none'">
                            <span>Detalle del Movimiento</span>
                        </a>
    </li>
    <li style="visibility:hidden" id="li_registro_fotografico_bienes_traslado">
         <a href="javascript:;" onClick="document.getElementById('tabla_detalles_traslados').style.display = 'none', document.getElementById('tabla_registro_fotografico_traslados1').style.display = 'block'">
                            <span>Registro Fotogr&aacute;fico</span>
                        </a>
    </li>
  </ul>
</div>
</td>
</tr>
</table>

</div>


<div id="tabla_detalles_traslados" style="display:none; width:70%; height:370px; overflow:auto; ">

<div id="divAjusteTituloUno" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:95%; margin-left:-47%;margin-top:265px">
<table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>Destino de los bienes</strong></td>
    </tr>
</table>
</div> 


<div id="dbienes_traslados" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:100px; width:95%; margin-left:-47%; margin-top:285px; overflow:auto">

<? /*
<form name="formulario_existentes_incorporacion" id="formulario_existentes_incorporacion">
*/ ?>
		
    <table width="100%" align="center" id="tabla_detalles_traslados">
    
        <tr>
            <td align="right" class='viewPropTitleNew'>Tipo de Movimiento Destino de los bienes:</td>
            <td colspan="3" id="celda_tipo_movimiento_destino">
            <? $sql_consulta = mysql_query("select * from tipo_movimiento_bienes where afecta = '1' 
                                                                                            and uso = 'movimientos'
                                                                                            and cambia_ubicacion = 'si'");
            
            
            ?>
              <select name="tipo_movimiento_destino" id="tipo_movimiento_destino" style="width:500px">
                <option value="0">.:: Seleccione el tipo de Movimiento de Incoporaci&oacute;n</option>
                <? 
                while($bus_consulta = mysql_fetch_array($sql_consulta)){				
                    ?>
                    <option value="<?=$bus_consulta["idtipo_movimiento_bienes"]?>"><?=$bus_consulta["denominacion"]?></option>
                    <?
                }
                ?>
              </select>
            </label>
            
            </td>
        </tr>
        <tr>
            <td colspan="6">
            <strong>Nueva ubicaci&oacute;n de los bienes:</strong></td>
       	</tr>
          <tr>
        	  <td align="right" class='viewPropTitleNew'>Organizaci&oacute;n:</td>
        	  <td colspan="5"><label>
        	    <select name="organizacion_traslados_destino" id="organizacion_traslados_destino"  style="width:600px">
                <option>.:: Seleccione ::.</option>
				<?
                $sql_organizacion = mysql_query("select * from organizacion");
				while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
					?>
					<option value="<?=$bus_organizacion["idorganizacion"]?>" onClick="consultarNivelOrganizacional('<?=$bus_organizacion["idorganizacion"]?>', 'nivel_organizacional_traslados_destino', 'div_nivel_organizacional_traslados_destino')"><?=$bus_organizacion["denominacion"]?></option>
					<?
				}
				?>
      	      </select>
        	  </label></td>
      	  </tr>
        	<tr>
        	  <td align="right" class='viewPropTitleNew'>Nivel Organizacional:</td>
        	  <td colspan="5" id="div_nivel_organizacional_traslados_destino">
        	   <select name="nivel_organizacional_traslados_destino" id="nivel_organizacional_traslados_destino"  style="width:600px">
      	      	<option value="0">.:: Seleccionar Organizaci&oacute;n ::.</option>
              </select>
        	  </td>
      	  </tr>
      </table>
     </div>
     
     <div id="divAjusteTituloDos" style="display:block; position:absolute; z-index:120; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:95%; margin-left:-47%;margin-top:380px">
    <table width="100%" border="0" align="center" style="background: #09F" >
        <tr>
          <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>Origen de los bienes</strong></td>
        </tr>
    </table>
    </div> 
      
    <div id="obienes_traslados" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:700px; width:95%; margin-left:-47%; margin-top:401px; overflow:auto">  
		
     <table width="100%" align="center" id="tabla_detalles_traslados_origen">
     <tr>
        <td align="right" class='viewPropTitleNew'>Tipo de Movimiento origen de los bienes:</td>
        <td colspan="5" id="celda_tipo_movimiento_origen">
        <? $sql_consulta = mysql_query("select * from tipo_movimiento_bienes where afecta = '2' 
																						and uso = 'movimientos'
																						and cambia_ubicacion = 'si'");
		
		
		?>
          <select name="tipo_movimiento_origen" id="tipo_movimiento_origen" style="width:500px" disabled="true">
            <option value="0">.:: Seleccione el tipo de Movimiento de Desincoporaci&oacute;n</option>
            <? 
			while($bus_consulta = mysql_fetch_array($sql_consulta)){				
				?>
				<option value="<?=$bus_consulta["idtipo_movimiento_bienes"]?>"><?=$bus_consulta["denominacion"]?></option>
				<?
			}
			?>
          </select>
        </label>
        
        </td>
  	</tr>
    <tr>
        <td colspan="6">
            <strong>Ubicaci&oacute;n actual de los bienes:</strong></td>
         </tr>   
          <tr>
        	  <td align="right" class='viewPropTitleNew'>Organizaci&oacute;n:</td>
        	  <td colspan="5"><label>
        	    <select name="organizacion_existentes_traslados" id="organizacion_existentes_traslados"  style="width:600px" disabled="true">
                <option>.:: Seleccione ::.</option>
				<?
                $sql_organizacion = mysql_query("select * from organizacion");
				while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
					?>
					<option value="<?=$bus_organizacion["idorganizacion"]?>" onClick="consultarNivelOrganizacional('<?=$bus_organizacion["idorganizacion"]?>', 'nivel_organizacional_existente_traslados', 'div_nivel_organizacional_existente_traslados')"><?=$bus_organizacion["denominacion"]?></option>
					<?
				}
				?>
      	      </select>
        	  </label></td>
      	  </tr>
        	<tr>
        	  <td align="right" class='viewPropTitleNew'>Nivel Organizacional:</td>
        	  <td colspan="5" id="div_nivel_organizacional_existente_traslados">
        	   <select name="nivel_organizacional_existente_traslados" id="nivel_organizacional_existente_traslados"  style="width:600px" disabled="true">
      	      	<option value="0">.:: Seleccionar Organizaci&oacute;n ::.</option>&nbsp;
              </select>
        	  </td>
      	  </tr>
  </table>
  
  		
		<div id="tabla_bienes_traslados_origen" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:200px; width:1000px; margin-left:-500px; margin-top:5px; overflow:auto"></div>
 
	
		<div id="tabla_bienes_traslados_destino" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:200px; width:1000px; margin-left:-500px; margin-top:225px; overflow:auto">
        
        
        </div>
      
  		
  
  
 <? /* </form> <div id="listaFotosExistentesIncorporacion"  style="display:none;; width:60%; height:300px; background-color:#EAEAEA; border:#000000 solid 1px"></div> */ ?>
 </div> 
 </div>
  
 <div id="tabla_registro_fotografico_traslados1" style="display:none; width:70%; height:370px; overflow:auto; "> 
 <div id="tabla_registro_fotografico_traslados" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:900px; width:95%; margin-left:-47%; margin-top:265px; overflow:auto"></div>
 
 <div id="listaFotosExistentesTraslados" style="display:none; position:absolute; z-index:105; margin-left:150px; margin-top:300px; overflow:auto"></div>
 
 </div>


<?

//********************************************************************************************************************************************************************
//  FIN TRASLADO DE BIENES
//********************************************************************************************************************************************************************
?>
