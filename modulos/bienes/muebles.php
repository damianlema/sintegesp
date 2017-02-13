<script src="modulos/bienes/js/muebles_ajax.js" type="text/javascript" language="javascript"></script>
<link href="modulos/bienes/css/estilos_bienes.css" rel="stylesheet" type="text/css" />


<h4 align=center>
Muebles
<br>
</h4>
<h2 class="sqlmVersion"></h2>
<input type="hidden" id="idmueble" name="idmueble">
<table width="6%" border="0" align="center" cellpadding="0" cellspacing="2">
    <tr>
      <td align="right">
		<div align="center">
		<img src="imagenes/search0.png" style="cursor:pointer" onClick="window.open('lib/listas/listar_muebles.php','','resizable=no, scrollbars=yes, width=1100, height=600')">
        </div>
	  </td> 
      <td align="right">
      	<div align="center">
		<img src="imagenes/nuevo.png" style="cursor:pointer" onClick="window.location.href = 'principal.php?accion=767&modulo=8'">
		</div>
	  </td>
    </tr>
</table>
<table align="center" width="75%" >
    <tr>
      <td width="240" align='center' bgcolor="#3399FF" style="font-weight:bold; font-size: 16px;" ><strong>C&oacute;digo del bien:</strong></td>
      <td width="270" align='center' bgcolor="#ccc" id="codigo_bien_mostrar" style="font-weight:bold; font-size: 16px; border:#666666 solid 1px">
      <strong>&nbsp;</strong></td>
      <td width="270" align='center' bgcolor="#ccc" id="estado_bien_mostrar" style="font-weight:bold; font-size: 16px; border:#666666 solid 1px">
      <strong>&nbsp;</strong></td>
    </tr>
</table>
<div id="tabsF">
  <ul>
    <li><a href="javascript:;" onClick="mostrarContenido('divDatosPrincipales')"><span>Datos del bien</span></a></li>
    <li><a href="javascript:;" id="idDivCosto" style="display:none" onClick="mostrarContenido('divCosto')"><span>Costo</span></a></li>
    <li><a href="javascript:;" id="idDivSeguro" style="display:none" onClick="mostrarContenido('divSeguro')"><span>Seguro</span></a></li>
    <li><a href="javascript:;" id="idDivFotos" style="display:none" onClick="mostrarContenido('divFotos')"><span>Registro Fotogr&aacute;fico</span></a></li>
    <li><a href="javascript:;" id="idDivMovimiento" style="display:none" onClick="mostrarContenido('divMovimiento')"><span>Movimientos</span></a></li>
  </ul>
</div>
<br />

<? // DIV DATOS BASICOS DEL BIEN?>

<div id="divDatosPrincipales" style="width:70%; height:380px; overflow:auto; "> 
<div id="divAjusteTituloDatos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:95%; margin-left:-47%;margin-top:3px">
 <table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>Detalles del Bien Mueble</strong></td>
    </tr>
</table>
</div>
<div id="tablaDatosBasicos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:360px; width:95%; margin-left:-47%; margin-top:23px; overflow:auto"> 
 <table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
 
 <tr>
    <td align="right" class='viewPropTitleNew'>Tipo de Movimiento</td>
    <td colspan="3">
      <select name="idtipo_movimiento" id="idtipo_movimiento" style="width:600px">
      <?
      $sql_tipo_movimiento = mysql_query("select * from tipo_movimiento_bienes where afecta = 1 and uso = 'inicial'");
	  while($bus_tipo_movimiento = mysql_fetch_array($sql_tipo_movimiento)){
	  	?>
			<option value="<?=$bus_tipo_movimiento["idtipo_movimiento_bienes"]?>">(<?=$bus_tipo_movimiento["codigo"]?>)&nbsp;<?=$bus_tipo_movimiento["denominacion"]?></option>
		<?
	  }
	  ?>
      </select>    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitleNew'>Organización</td>
    <td colspan="3">
      <select name="idorganizacion" id="idorganizacion" style="width:600px" onChange="seleccionarNivel(this.value)">
        <option value="0">.:: Seleccione ::.</option>
    	  <?
          $sql_organizacion = mysql_query("select * from organizacion");
    	  while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
    	  	?>
    			<option value="<?=$bus_organizacion["idorganizacion"]?>" 
                 ><?=$bus_organizacion["denominacion"]?>
          </option>
    		<?
        //
    	  }
    	  ?>
      </select>    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitleNew'>Nivel Organizacional</td>
    <td colspan="3" id="celda_nivel_organizacion">
      <select name="idnivel_organizacion" id="idnivel_organizacion" style="width:600px">
      <option value="0">.:: Seleccione la Organizacion ::.</option>
      </select>    </td>
  </tr>
  
 
 
 
  <tr>
    <td width="11%" align="right" class='viewPropTitleNew'>C&oacute;digo de Catalogo:</td>
    <td colspan="3">
     	
        <input type="hidden" name="idcatalogo_bienes" id="idcatalogo_bienes">
        <input name="catalogo_bienes" type="text" id="catalogo_bienes" size="120">                
  		<img id='buscar_catalogo' src='imagenes/search0.png' title="Buscar Catalogo" onClick="window.open('modulos/bienes/lib/listar_detalles.php','','resizable = no, scrollbars=yes, width=900, height=600')" style="cursor:pointer" style="display:block"> 
                 
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitleNew'>Tipo:</td>
    <td colspan="3" id="celda_tipo_detalle">
      <select name="idtipo" id="idtipo" style="width:50%">
		<option value="0">.:: Seleccione el C&oacute;digo de Catalogo ::.</option>
      </select>    </td>
  </tr>
  <tr>

    <td align="right" class='viewPropTitleNew'>C&oacute;digo del Bien:</td>
    <td colspan="3">
      <input type="text" name="codigo_bien" id="codigo_bien" size="35" onBlur="validarCodigoBien(this.value)">
      <input type="text" name="cantidad_bien" id="cantidad_bien" value='1' style="text-align:center" size="5">
      <input type="hidden" id="codigo_bien_automatico" name="codigo_bien_automatico" maxlength="25" size="25">
      
      <img id='idgenerar_codigo' src='imagenes/refrescar.png' title="Generar C&oacute;digo" onClick="generar_codigo()" style="cursor:pointer" style="display:block"> 
      
      
    </td>
  </tr>
  <tr>

    <td align="right" class='viewPropTitleNew'>C&oacute;digo Anterior del Bien:</td>
    <td colspan="3">
      <input type="text" name="codigo_anterior_bien" id="codigo_anterior_bien" size="50">
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitleNew'>Ubicacion física:</td>
    <td colspan="3">
      <select name="idubicacion" id="idubicacion">
      	<?
        $sql_ubicacion = mysql_query("select * from ubicacion");
		while($bus_ubicacion = mysql_fetch_array($sql_ubicacion)){
			?>
      <option value="<?=$bus_ubicacion["idubicacion"]?>">(
        <?=$bus_ubicacion["codigo"]?>
        )&nbsp;
        <?=$bus_ubicacion["denominacion"]?>
        </option>
      <?
		}
		?>
    </select></td>
  </tr>
  
  <tr>
    <td align="right" class='viewPropTitleNew'>Especificaciones:</td>
    <td colspan="3"><textarea name="especificaciones" cols="130" rows="2" id="especificaciones"></textarea></td>
  </tr>
  
  <tr>
    <td width="17%" align="right" class='viewPropTitleNew'>Marca:</td>
    <td width="25%" ><input name="marca" type="text" id="marca" size="40">    </td>
    <td width="8%" align="right" class='viewPropTitleNew'>Modelo:</td>
    <td width="50%" ><input name="modelo" type="text" id="modelo" size="25">    </td>
  </tr>
  
  
  <tr>
    <td align="right" class='viewPropTitleNew'>Serial(es):</td>
    <td colspan="3">
      <input name="serial" type="text" id="serial" size="140">    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitleNew'>Accesorios:</td>
    <td colspan="3"><textarea name="accesorios" cols="130" rows="2" id="accesorios"></textarea></td>
  </tr>
  </table>
 </div>
 </div>





<? // DATOS DE UBICACION DEL BIEN ?>



<div id="divMovimiento" style="display:none; width:70%; height:380px; overflow:auto; ">

<div id="divAjusteTituloDatos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:95%; margin-left:-47%;margin-top:3px">
<table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>Movimientos del bien</strong></td>
    </tr>
</table>
</div>

<div id="tablaDatosUbicacion" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:355px; width:95%; margin-left:-47%; margin-top:23px; overflow:auto">

	<div id="lista_Movimientos"></div>
		
</div>
</div>


<? // DATOS DE COMPRA DEL BIEN ?>


<div id="divCosto" style="display:none; width:70%; height:380px; overflow:auto; ">

<div id="divAjusteTituloDatos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:95%; margin-left:-47%;margin-top:3px">
<table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>Detalles de Adquisici&oacute;n</strong></td>
    </tr>
</table>
</div>

<div id="tablaDatosCosto" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:355px; width:95%; margin-left:-47%; margin-top:23px; overflow:auto">

 <br>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
  
  <tr>
    <td width="20%" align="right" class='viewPropTitleNew'>No. Documento de Compra</td>
    <td width="17%">
    <input type="text" name="nro_documento_compra" id="nro_documento_compra">    </td>
    <td width="13%" align="right" class='viewPropTitleNew'>Fecha de Compra</td>
    <td width="50%"><table>
      <tr>
        <td><input name="fecha_compra" type="text" id="fecha_compra" size="12" onChange="calcularDepreciacionAcumulada()" />        </td>
        <td><img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                    Calendar.setup({
                                    inputField    : "fecha_compra",
                                    button        : "f_trigger_c",
                                    align         : "Tr",
                                    ifFormat      : "%Y-%m-%d"
                                    });
                                </script>        </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="20%" align="right" class='viewPropTitleNew'>Proveedor</td>
    <td colspan="3"><input name="proveedor" type="text" id="proveedor" size="80" /></td>
  </tr>
  <tr>
    <td width="20%" align="right" class='viewPropTitleNew'>Nro. Factura</td>
    <td width="17%"><input type="text" name="nro_factura" id="nro_factura" /></td>
    <td width="13%" align="right" class='viewPropTitleNew'>Fecha Factura</td>
    <td width="50%"><table>
      <tr>
        <td><input name="fecha_factura" type="text" id="fecha_factura" size="12" />        </td>
        <td><img src="imagenes/jscalendar0.gif" name="fecha_f" width="16" height="16" id="fecha_f" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                    Calendar.setup({
                                    inputField    : "fecha_factura",
                                    button        : "fecha_f",
                                    align         : "Tr",
                                    ifFormat      : "%Y-%m-%d"
                                    });
                                </script>        </td>
      </tr>
    </table></td>
  </tr>
  </table>
  <table width="80%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td width="20%" align="right" class='viewPropTitleNew'><p>Costo:</p>    </td>
    <td width="17%"><input name="costo" type="text" id="costo" style="text-align:right" value="0" onKeyUp="calcularCostoAjustado(), calcularDepreciacionAnual(), calcularDepreciacionAcumulada()" size="20" onClick="this.select()" /></td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td width="20%" align="right" class='viewPropTitleNew'><p>Mejoras:</p>    </td>
    <td width="17%"><input name="mejoras" type="text" id="mejoras" style="text-align:right" value="0" onKeyUp="calcularCostoAjustado(), calcularDepreciacionAnual(), calcularDepreciacionAcumulada()" size="20" onClick="this.select()" /></td>
  </tr>
  <tr>
    <td width="20%" align="right" class='viewPropTitleNew'><p>Costo Ajustado:</p>    </td>
    <td width="17%"><input name="costo_ajustado" type="text" id="costo_ajustado" style="text-align:right" disabled="disabled" value="0" size="20" onClick="this.select()" /></td>
  </tr>
  <tr>
    <td width="20%" align="right" class='viewPropTitleNew'>Valor Residual:</td>
    <td width="17%"><input name="valor_residual" type="text" id="valor_residual" style="text-align:right" value="0" size="20" onKeyUp="calcularDepreciacionAnual(), calcularDepreciacionAcumulada()" /></td>
  </tr>
  <tr>
    <td width="20%" align="right" class='viewPropTitleNew'>Vida Util (Años):</td>
    <td width="17%"><input name="vida_util" type="text" id="vida_util" value="0" size="5" onKeyUp="calcularDepreciacionAnual(), calcularDepreciacionAcumulada()" onClick="this.select()" /></td>
  </tr>
  <tr>
    <td width="20%" align="right" class='viewPropTitleNew'>Depreciación Anual:</td>
    <td width="17%">
    <input name="depreciacion_anual" type="text" id="depreciacion_anual" style="text-align:right" value="0" size="20" disabled>    </td>
  </tr>
  <tr>
    <td width="20%" align="right" class='viewPropTitleNew'>Depreciación Acumulada:</td>
    <td width="17%">
    <input name="depreciacion_acumulada" type="text" id="depreciacion_acumulada" style="text-align:right" value="0" size="20" disabled>    </td>
  </tr>
  </table>
  </div>
  </div>
  
  
<div id="divSeguro" style="display:none; width:70%; height:380px; overflow:auto; ">

<div id="divAjusteTituloDatos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:95%; margin-left:-47%;margin-top:3px">
<table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>Detalles del Seguro</strong></td>
    </tr>
</table>
</div>

<div id="tablaDatosSeguro" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:355px; width:95%; margin-left:-47%; margin-top:23px; overflow:auto">  
  
  <br>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
  
  <tr>
    <td width="20%" align="right" class='viewPropTitleNew'>Asegurado ?</td>
    <td width="17%">
    <input name="asegurado" type="checkbox" id="asegurado" value="si" onClick="validarSeleccionado(this.id)" style="cursor:pointer">    </td>
    <td width="18%">&nbsp;</td>
    <td width="45%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">

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
        <td width="17%" align="right" class='viewPropTitleNew'>Fecha de Vencimiento</td>
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
  </table>
  </div>
  </div>


<div id="divFotos" style="display:none; width:70%; height:380px; overflow:auto; ">

<div id="divAjusteTituloDatos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:95%; margin-left:-47%;margin-top:3px">
<table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>Registro Fotogr&aacute;fico</strong></td>
    </tr>
</table>
</div>

<div id="tablaDatosFotos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:355px; width:95%; margin-left:-47%; margin-top:23px; overflow:auto">  

<div id="mostrarImagen" align="center"></div>
        <table align="center">
            <tr>
            	<td align='right' class='viewPropTitleNew'>Foto:</td>
            	<td>
                <form method="post" id="formImagen" name="formImagen" enctype="multipart/form-data" action="modulos/bienes/lib/muebles_ajax.php" target="iframeUpload">
                  <input type="file" name="foto_registroFotografico" id="foto_registroFotografico" size="20" align="left" onChange="document.getElementById('formImagen').submit()">
                  <!--<input type="submit" name="boton_subir" id="boton_subir" value="Subir">-->
                  <input type="hidden" id="ejecutar" name="ejecutar" value="cargarImagen">
              </form>
              <iframe id="iframeUpload" name="iframeUpload" style="display:none"></iframe>
              <input type="hidden" id="nombre_imagen" name="nombre_imagen">
                </td>
            </tr>
            <tr>
            <td>Descripcion: </td>
            <td><textarea name="descripcion_foto" id="descripcion_foto" rows="3" cols="30"></textarea></td>
            </tr>
            <tr>
            	<td colspan="2" align="center"><input type="button" id="boton_registroFotografico" name="boton_registroFotografico" value="Subir Imagen" class="button" onClick="subirRegistroFotografico()"></td>
            </tr>
        </table>
    <br>
	<br>

	<div id="lista_registroFotografico"></div> 

</div>
</div>
  
  <table border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    
        <td><input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" style="display:block" onClick="ingresarMueble()"></td>
        <td><input type="submit" name="boton_modificar" id="boton_modificar" value="Actualizar" class="button" style="display:none" onClick="modificarMueble()"></td>
        <td><input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar"class="button" style="display:none" onClick="eliminarMueble()"></td>
      </tr>
    
  
</table>
