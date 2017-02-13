<script src="js/remitir_documentos_ajax.js" type="text/javascript" language="javascript"></script>	

    <br>
<h4 align=center>Remisi&oacute;n de Documentos</h4>
	<h2 class="sqlmVersion"></h2>
<input type="hidden" id="campo_tipo_documento" name="campo_tipo_documento">

<div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
<table align="center">
	<tr><td align="right"><a href="#" onClick="document.getElementById('divImprimir').style.display='none';">X</a></td></tr>
   	<tr><td><iframe name="pdf" id="pdf" style="display:none" height="600" width="750"></iframe></td></tr>
</table>
</div>

  <!-- TABLA DE DATOS BASICOS-->
<br>
<div id="tablaDatosBasicos">
  
  <table width="73%" border="0" align="center" cellpadding="0" cellspacing="2">
  
  <tr>
  <td colspan="7" align="right"><div align="center"><img src="imagenes/search0.png" title="Buscar Remisiones" style="cursor:pointer" onclick="window.open('lib/listas/listar_remision_documentos.php','buscar remisiones','resisable = no, scrollbars = yes, width=900, height = 500')" />&nbsp; <img src="imagenes/nuevo.png" title="Ingresar nueva Remisi&oacute;n" onclick="window.location.href='principal.php?modulo=<?=$modulo?>&accion=<?=$_GET["accion"]?>'" style="cursor:pointer" />
&nbsp;
  <img src="imagenes/imprimir.png" title="Imprimir Remisión" style="cursor:pointer" /> 
  
  </div></td>
  </tr>
  
<tr>
        <td width="163" align="right" class='viewPropTitle'>N&uacute;mero de Remisi&oacute;n:</td>
      <td width="133"><strong>Aun No Generado</strong>
        &nbsp;</td>
      <td width="140" class='viewPropTitle'><div align="right">Fecha de Envio:</div></td>
        
      <td width="108">&nbsp;</td>
      <td width="210" class='viewPropTitle'><div align="right">Nro. Documentos enviados:</div></td>
      <td width="77" id="divCantidad">&nbsp;</td>
</tr>
    <tr>
      <td align="right" class='viewPropTitle'>Fecha de Elaboraci&oacute;n:</td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Para:</td>
      <td colspan="6">
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
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Asunto:
      <td height="4" colspan="7">
      <input name="asunto" type="text" id="asunto" value="En el texto" size="100" />
      <input type="hidden" name="id_remision" id="id_remision">
      <input type="hidden" name="estado" id="estado" value="">
      <input type="hidden" name="tabla" id="tabla" value="">
      </td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Justificaci&oacute;n:</td>
      <td colspan="6"><textarea name="justificacion" cols="100" rows="3" id="justificacion"></textarea></td>
    </tr>
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
                            onclick="ingresarDatosBasicos(document.getElementById('dependencias').value, document.getElementById('asunto').value, document.getElementById('justificacion').value, document.getElementById('id_remision').value)" 
                            class="button">             			
               </td>
        	</tr>
       </table>
        <br />        </td>
    </tr>
  </table>
</div>
  

  <table align="center" cellpadding="0" cellspacing="0" id="tablaSeleccionarDocumentos" style="display:none; width:800px">
 	 <tr>
     <td width="800" class="viewPropTitle">
                <table align="left">
                <tr>
                <td>
                <strong>PROCESO:
                	<? if($modulo == 1){ ?>
                    <select name="origenDocumentos" id="origenDocumentos">
                          <option value="0">.:: Seleccione ::.</option>
                          <option onclick="consultarDocumentos('compromete')" value="compromete">Compromisos</option>
                    </select>
                <? }
				    if($modulo == 2){ ?>
                    <select name="origenDocumentos" id="origenDocumentos">
                          <option value="0">.:: Seleccione ::.</option>
                          <option onclick="consultarDocumentos('presupuesto')" value="presupuesto">Presupuesto</option>
                          <option onclick="consultarDocumentos('compromete')" value="compromete">Compromisos</option>
                          <option onclick="consultarDocumentos('causa')" value="causa">Causados</option>
                          <option onclick="consultarDocumentos('paga')" value="paga">Pagados</option>
                    </select>
                <? } 
               if($modulo == 3 || $modulo == 16 || $modulo == 19){ ?>
                    <select name="origenDocumentos" id="origenDocumentos">
                          <option value="0">.:: Seleccione ::.</option>
                          <option onclick="consultarDocumentos('compromete')" value="compromete">Compromisos</option>
                    </select>
                <? } 
				if($modulo == 4){ ?>
                    <select name="origenDocumentos" id="origenDocumentos">
                          <option value="0">.:: Seleccione ::.</option>
                          <option onclick="consultarDocumentos('compromete')" value="compromete">Compromisos</option>
                          <option onclick="consultarDocumentos('causa')" value="causa">Causados</option>
                    </select>
                <? }
				if($modulo == 6){ ?>
                    <select name="origenDocumentos" id="origenDocumentos">
                          <option value="0">.:: Seleccione ::.</option>
                          <option onclick="consultarDocumentos('compromete')" value="compromete">Compromisos</option>
                          <option onclick="consultarDocumentos('causa')" value="causa">Causados</option>
                    </select>
                <? } 
				if($modulo == 7){ ?>
                    <select name="origenDocumentos" id="origenDocumentos">
                          <option value="0">.:: Seleccione ::.</option>
                          <option onclick="consultarDocumentos('compromete')" value="compromete">Compromisos</option>
                          <option onclick="consultarDocumentos('causa')" value="causa">Causados</option>
                    </select>
                <? }
				if($modulo == 5){ ?>
                    <select name="origenDocumentos" id="origenDocumentos">
                          <option value="0">.:: Seleccione ::.</option>
                          <option onclick="consultarDocumentos('compromete')" value="compromete">Compromisos</option>
                          <option onclick="consultarDocumentos('causa')" value="causa">Causados</option>
                    </select>
                <? }
				if($modulo == 12){ ?>
                    <select name="origenDocumentos" id="origenDocumentos">
                          <option value="0">.:: Seleccione ::.</option>
                          <option onclick="consultarDocumentos('compromete')" value="compromete">Compromisos</option>
                          <option onclick="consultarDocumentos('causa')" value="causa">Causados</option>
                    </select>
                <? }
				
				if($modulo == 13){ ?>
                    <select name="origenDocumentos" id="origenDocumentos">
                          <option value="0">.:: Seleccione ::.</option>
                          <option onclick="consultarDocumentos('compromete')" value="compromete">Compromisos</option>
                    </select>
                <? } if($modulo == 14){ ?>
                    <select name="origenDocumentos" id="origenDocumentos">
                          <option value="0">.:: Seleccione ::.</option>
                          <option onclick="consultarDocumentos('compromete')" value="compromete">Compromisos</option>
                    </select>
                <? } ?>
                
      			</strong>
                </td>
                <td id="subOrigenDocumentos">
                </td>
                <td>
                	<form method="post" onsubmit="return buscarOrdenEspecifica()">
                    <table>
                		<tr>
                    		<td><div id="texto_buscar" style="display:none">Nro.&nbsp;Orden.</div> </td>
    						<td><input type="text" name="campoBusqueda" id="campoBusqueda" style="display:none" size="12"></td>            
	                		<td><input type="submit" name="boton_buscar" id="boton_buscar" class="button" style="display:none" value="Buscar"></td>
                        </tr>
                    </table>
                    </form> 
                </td>
                </tr>
                </table>
      </td>
     <td align="right" class='viewPropTitle'>
          <a href="#" onclick="abrirCerrarListaDocumentos()" id="textoContraerListaDocumentos"> 
             <img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar" /> 
          </a> 
     </td>
   	</tr>
   	<tr>
      <td width="800" colspan="2">
          <table width="800" align="center" id="tablaListaDocumentos" style=" display:block" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center" valign="top" class='viewPropTitle' id="listaDocumentos" width="800px">
              <!-- LISTA DE DOCUMENTOS -->
            
              </td>
            </tr>
          </table>
      </td>
   </tr>
</table>


<br>
<!-- DOCUMENTOS A ENVIAR-->
<table width="800" align="center" style="display:none" id="tablaDocumentosEnviar">
        <tr>
          <td width="300" align="left" class='viewPropTitle'><strong>DOCUMENTOS A ENVIAR</strong></td>
          <td width="500" align="right" class='viewPropTitle'>
         	 <a href="#" onClick="abrirCerrarDocumentosEnviar()" id="textoContraerDocumentosEnviar">
          		<img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar">            
             </a>          
          </td>
 		</tr>
        <tr>
          <td colspan="2" align="right">&nbsp;</td>
  		</tr>
        <tr>
          <td colspan="2" align="center">
          	<div id="divListaSeleccionadosDocumentos" style="display:block"></div> 
          </td>
        </tr>
</table>

