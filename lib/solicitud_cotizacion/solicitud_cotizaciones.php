<script src="js/solicitud_cotizacion_ajax.js" type="text/javascript" language="javascript"></script>	
<link href="css/estilos_solicitud.css" rel="stylesheet" type="text/css">


<?
if($_SESSION["modulo"] == 3){
	$accion=242;
}
if($_SESSION["modulo"] == 4){
	$accion=275;
}
/* ?>


<h4 align=center>Consulta de Precios</h4>

<h2 class="sqlmVersion"></h2>
*/ ?>
<table width="6%" border="0" align="center" cellpadding="0" cellspacing="2">
    <tr>
      <td align="right">
        <div align="center">
            <img src="imagenes/search0.png" title="Buscar Consulta de Precios" style="cursor:pointer" onclick="window.open('lib/listas/listar_solicitud_cotizaciones.php?destino=solicitudes','buscar solicitud de cotizacion','resisable = no, scrollbars = yes, width=900, height = 500')" />
        </div>
      </td>
      <td align="right">
        <div align="center">
          	<img src="imagenes/nuevo.png" title="Ingresar nueva Consulta de Precios"  style="cursor:pointer" onclick="window.location.href='principal.php?modulo=<?=$_SESSION["modulo"]?>&accion=<?=$accion?>'"/> 
        </div>
      </td>
      <td align="right">
        <div align="center" id="celdaImprimir" style="display:none">
        	 <img src="imagenes/imprimir.png" title="Imprimir Solicitud de Cotizacion" onClick="document.getElementById('divOpciones').style.display='block';" style="cursor:pointer" />
             
        </div>
      </td>
    </tr>
</table>  

<div id="divOpciones" style="display:none; position:absolute; z-index:10; background-color:#CCCCCC; border:1px solid; left:35%; width:25%;">
<table align="center" width="100%">
    <tr><td align="right"><a href="#" onClick="document.getElementById('divOpciones').style.display='none';">X</a></td></tr>
    <tr><td align="center"><strong>Seleccione el Tipo de Reporte</strong></td></tr>
    <tr><td><input type="radio" name="tipo2" id="consulta" checked="checked" /> Consulta de Precios</td></tr>
    <tr><td><input type="radio" name="tipo2" id="acta" /> Acta</td></tr>
    <tr><td><input type="button" value="Aceptar" onclick="pdf.location.href='lib/reportes/compras_servicios/reportes.php?nombre=scotizacion&id_solicitud='+document.getElementById('id_solicitud').value+'&consulta='+document.getElementById('consulta').checked+'&acta='+document.getElementById('acta').checked; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block'; document.getElementById('divOpciones').style.display='none';" /></td></tr>
</table>
</div>

<div id="divImprimir" style="display:none; position:absolute; z-index:11; background-color:#CCCCCC; border:1px solid;">
<table align="center">
    <tr><td align="right"><a href="#" onClick="document.getElementById('divImprimir').style.display='none';">X</a></td></tr>
    <tr><td><iframe name="pdf" id="pdf" style="display:none" height="600" width="750"></iframe></td></tr>
</table>
</div>            


<div id="divAjusteTituloDatos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:90%; margin-left:-560px; margin-top:5px">
<table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:11px"><strong>Consulta de Precios</strong></td>
    </tr>
</table>
</div>
<input type="hidden" id="id_solicitud" name="id_solicitud" />
<input type="hidden" id="estadoOculto" name="estadoOculto" />

<div id="divDatosBasicos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:28%; width:90%; margin-left:-560px; margin-top:25px; overflow:auto">	
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="15%" align="right" class='viewPropTitleNew'>Nro. de Consulta:</td>
          <td width="10%" colspan="2" style="border:1px solid #999; background-color:#FFF" id="celdaNroOrden"><strong>&nbsp;Aun no generado</strong></td>
          <td width="15%" class='viewPropTitleNew' align="right">Fecha de Consulta:</td>
          <td width="10%"><input type="text" name="fecha_consulta" id="fecha_consulta" style="width:150px; height:20px" readonly="readonly" /></td>
          <td width="15%" class='viewPropTitleNew' align="right">Estado:</td>
          <td width="23%" id="celdaEstado"><strong>En Elaboraci&oacute;n</strong></td>
        </tr>
        <tr>
          <td class='viewPropTitleNew' align="right">Tipo de Consulta:</td>
          <td colspan="4">
          
                <?
				$sql_tipos_documentos = mysql_query("select * from tipos_documentos where modulo like '%-".$_SESSION["modulo"]."-%'");
				?>
				<select name="tipo" id="tipo" >
				<?
				while($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)){
				?>
					<option <? if($bus["tipo"] == $bus_tipos_documentos["idtipos_documentos"]){echo "selected";} ?> value="<?=$bus_tipos_documentos["idtipos_documentos"]?>"><?=$bus_tipos_documentos["descripcion"]?></option>
				<?
				}
				?>
          </select>   
         </td>
          <td class='viewPropTitleNew' align="right">Cantidad de Items:</td>
          <td> 
          <strong>
          	<div id="divCantidadItems"></div>
          </strong></td>
        </tr>
        <tr>
          <td class='viewPropTitleNew' align="right">Concepto:</td>
          <td colspan="6"><textarea name="justificacion" cols="115" rows="5" id="justificacion"></textarea></td>
          <td>&nbsp;&nbsp;<a href="#" onClick="abrirCerrarObservaciones()" id="textoContraerObservaciones"><img border="0" src="imagenes/comments.png" title="Observaciones" style="text-decoration:none"></a></td>
        </tr>
        <tr>
        	<td colspan="7">
                <div id="divObservaciones" style="display:none">
                 <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="20%" align="right" class='viewPropTitleNew'>Observaciones:</td>
                        <td width="80%" colspan="6"><textarea name="observaciones" cols="135" rows="2" id="observaciones"></textarea></td>
                    </tr>
                 </table>
                </div>
             </td>
        </tr>     
        <tr>
           <td align="right" class='viewPropTitleNew'>Modo de Comunicaci&oacute;n:</td>
           <td><label>
             <select name="modo_comunicacion" id="modo_comunicacion">
             	<option value="invitacion">Invitaci&oacute;n</option>
               	<option value="prensa">Prensa</option>
             </select>
           </label>
           </td>
           <td align="right" class='viewPropTitleNew'>Actividad:</td>
           <td><label>
             <select name="tipo_actividad" id="tipo_actividad">
               <option value="bienes">Bienes</option>
               <option value="servicios">Servicios</option>
               <option value="obras">Obras</option>
             </select>
           </label>
           </td>
        </tr>
        <tr>
           <td align="right" class='viewPropTitleNew'>Ordenado Por: </td>
           <td colspan="4"><input type="text" name="ordenado_por" id="ordenado_por" size="40">&nbsp;C.I: <input type="text" size="12" name="cedula_ordenado" id="cedula_ordenado">
           </td>
        </tr>
       
        <tr>
          <td colspan="7" align="center">
          	<table align="center" id="tablaBotones" border="0" width="100">
           		<tr>
                	<td id="celdaSiguiente" style="display:block">
              			<input type="button" name="botonIngresar" id="botonIngresar" value="Siguiente &gt;" style="display:block" class="button" onClick="registrarSolicitudCotizacion(document.getElementById('tipo').value, document.getElementById('justificacion').value, document.getElementById('observaciones').value, document.getElementById('ordenado_por').value, document.getElementById('cedula_ordenado').value, document.getElementById('modo_comunicacion').value, document.getElementById('tipo_actividad').value), this.style.display = 'none'">
         			</td>
            	</tr>
             	<tr>
             		<td>
                        <input type="button" id="botonEspera" name="botonEspera" value="En Espera" class="button"  style="display:none" onClick="actualizarInformacionBasica(document.getElementById('id_solicitud').value, document.getElementById('tipo').value, document.getElementById('justificacion').value, document.getElementById('observaciones').value, document.getElementById('ordenado_por').value, document.getElementById('cedula_ordenado').value, document.getElementById('modo_comunicacion').value, document.getElementById('tipo_actividad').value)">                    
                    </td>
                    <td id="celdaProcesar">
                        <?
                        if($_SESSION["modulo"] == 3 and in_array(383, $privilegios) == true){
                        ?>
                       <input type="button" id="botonProcesar" name="botonProcesar" value="Procesar" class="button" style="display:none" onClick="procesar(document.getElementById('id_solicitud').value, document.getElementById('tipo').value, document.getElementById('justificacion').value, document.getElementById('observaciones').value, document.getElementById('ordenado_por').value, document.getElementById('cedula_ordenado').value, document.getElementById('modo_comunicacion').value, document.getElementById('tipo_actividad').value)">
                       <?
                       }if($_SESSION["modulo"] == 4 and in_array(394, $privilegios) == true){
                        ?>
                       <input type="button" id="botonProcesar" name="botonProcesar" value="Procesar" class="button" style="display:none" onClick="procesar(document.getElementById('id_solicitud').value, document.getElementById('tipo').value, document.getElementById('justificacion').value, document.getElementById('observaciones').value, document.getElementById('ordenado_por').value, document.getElementById('cedula_ordenado').value, document.getElementById('modo_comunicacion').value, document.getElementById('tipo_actividad').value)">
                       <?
                       }
                       ?>                    
                   </td>
               	   <td id="celdaGanador">
						<?
                        if($_SESSION["modulo"] == 3 and in_array(384, $privilegios) == true){
                        ?>
                            <input type="button" id="botonGanador" name="botonGanador" value="Ganador" class="button" style="display:none" onClick="mostrarSelectGanador(document.getElementById('id_solicitud').value)">
                        <?
                        }else if($_SESSION["modulo"] == 4 and in_array(395, $privilegios) == true){
                        ?>
                            <input type="button" id="botonGanador" name="botonGanador" value="Ganador" class="button" style="display:none" onClick="mostrarSelectGanador(document.getElementById('id_solicitud').value)">
                        <?
                        }
                        ?>
                	</td>
                    <td id="celdaFinalizar">
                    	<?
                    	if($_SESSION["modulo"] == 3 and in_array(385, $privilegios) == true){
							?>
							<input type="button" id="botoFinalizar" name="botoFinalizar" value="Finalizar" class="button" style="display:none" onClick="finalizarSolicitud(document.getElementById('id_solicitud').value)">
						<?
                        }else if($_SESSION["modulo"] == 4 and in_array(398, $privilegios) == true){
							?>
							<input type="button" id="botoFinalizar" name="botoFinalizar" value="Finalizar" class="button" style="display:none" onClick="finalizarSolicitud(document.getElementById('id_solicitud').value)">
			   			<?
						}
						?>
                    </td>
                    <td id="celdaAnular">
						<?
                        if($_SESSION["modulo"] == 3 and in_array(386, $privilegios) == true){
                        ?>
                        	<input type="button" id="botonAnular" name="botonAnular" value="Anular" class="button" style="display:none"  onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'">
                        <?
                        }else if($_SESSION["modulo"] == 4 and in_array(396, $privilegios) == true){
                        ?>
                        	<input type="button" id="botonAnular" name="botonAnular" value="Anular" class="button" style="display:none" onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'">
                        <?
                        }
			  			?>
                    	
                    </td>
                    <td id="celdaDuplicar">
                    	<?
                    	if($bus["estado"] != "espera"){ 
							if($_SESSION["modulo"] == 3 and in_array(387, $privilegios) == true){ 
								?>
								<input type="button" id="botonDuplicar" name="botonDuplicar" value="Duplicar" class="button" style="display:none" onclick="duplicarSolicitud(document.getElementById('id_solicitud').value)" />
							<? 
							}else if($_SESSION["modulo"] == 4 and in_array(397, $privilegios) == true){ 
								?>
								<input type="button" id="botonDuplicar" name="botonDuplicar" value="Duplicar" class="button" style="display:none" onclick="duplicarSolicitud(document.getElementById('id_solicitud').value)" />
							<? 
							}
						}?>
                    </td>
               </tr>
             </table>
        	</td>
        </tr>
  </table>
</div>
     
<div id="divPreguntarUsuario" style="display:none; position:absolute; z-index:12; background-color:#CCCCCC; border:#000000 solid 1px; margin-top:130px; margin-left:580px">
  <table align="center">
    <tr>
      <td align="right" colspan="2">
        <a href="#" onClick="document.getElementById('divPreguntarUsuario').style.display='none'" title="Cerrar">
          <strong>x</strong>                                </a>                            </td>
      </tr>
    <tr>
      <td><strong>Usuario:</strong> </td>
        <td><?=$login?></td>
      </tr>
    <tr>
      <td><strong>Clave:</strong> </td>
        <td><input type="password" name="verificarClave" id="verificarClave"></td>
      </tr>
    <tr>
      <td colspan="2" align="center"><input type="button" name="validar" id="validar" class="button" value="Anular" onClick="validarAnulacion(document.getElementById('id_solicitud').value, document.getElementById('verificarClave').value)"></td>
      </tr>
    </table>
</div>      
       
<div id="divTablaProveedores" style="display:none; position:absolute; left:50%; width:90%; margin-left:-560px; height:100px; height:auto !important; min-height:100px; margin-top:240px; overflow:auto">
     <table width="100%" id="tablaProveedor" style="display:block; " align="center">
        <tr style="background: #09F">
          <td align="left" width="25%"><strong>PROVEEDORES</strong></td>
          <td align="right" width="100%">&nbsp;</td>
          <td align="right" width="5%">
          		<a href="#" onClick="abrirCerrarProveedores()" id="textoContraerProveedores">
                <img border="0" src="imagenes/cerrar.gif" title="Cerrar" style="text-decoration:none">
          </td>
        </tr>
    </table>
    <table width="95%" id="formularioProveedores" style="display:block; " align="center">
        <tr>
          <td width="5%">&nbsp;</td>
          <td width="100%" align="center">Nombre:</td>
          <td width="15%" align="center">RIF:</td>
          <td width="5%">&nbsp;</td>
        </tr>
        <tr>
        	<td><div id="divBuscarProveedor"><img src="imagenes/search0.png" title="Buscar Nuevo Proveedor" id="buscarProveedor" name="buscarProveedor" onClick="window.open('modulos/administracion/lib/listar_beneficiarios.php?destino=cotizaciones','','resizable = no, scrollbars = yes, width =900, height=600')" style="cursor:pointer"></div></td>
          <td><input name="nombreBeneficiario" type="text" disabled id="nombreBeneficiario" size="125"></td>
	            <input type="hidden" id="idBeneficiario" name="idBeneficiario">              
          <td><input name="rifBeneficiario" type="text" disabled id="rifBeneficiario" size="20"></td>
          <td rowspan="2"><img src="imagenes/validar.png" title="Procesar Proveedor" id="botonProcesarProveedor" name="botonProcesarProveedor" onClick="procesarProveedor(document.getElementById('idBeneficiario').value, document.getElementById('id_solicitud').value)" style="cursor:pointer"></td>
        </tr>
    	</table>
  
      <div id="divContenidoProveedores" align="center" style="display:block; width:100%;">
      	No hay Proveedores Asociados
      </div>

      <table width="100%" id="tablaMateriales" style="display:block; " align="center">    
        <tr style="background: #09F">
          <td align="left" width="25%"><strong>SOLICITADO</strong></td>
          <td align="right" width="100%"><span id="totales"><strong>Exento:</strong> 0,00 | <strong>SubTotal:</strong> 0,00 | <strong>Impuesto:</strong> 0,00 | <strong>Total Bsf:</strong> 0,00</span ></td>
          <td align="right" width="5%"><a href="#" onClick="abrirCerrarMateriales()" id="textoContraerMateriales"><img border="0" src="imagenes/cerrar.gif" style="text-decoration:none;" title="Cerrar"></a></td>
         </tr>
      </table>
      <table width="95%" id="formularioMateriales" style="display:block; " align="center"> 
       
        	<tr>
              <td width="5%">&nbsp;</td>
              <td align="center" width="15%">C&oacute;digo:</td>
              <td align="center" width="65%">Descipci&oacute;n:</td>
              <td align="center" width="5%">Und:</td>
              <td align="center" width="15%">Cantidad:</td>
              <td width="5%">&nbsp;</td>
          </tr>
          <tr>
              <td><img src="imagenes/search0.png" title="Buscar Nuevo Material" id="buscarMaterial" name="buscarMaterial" onClick="window.open('lib/listas/listar_materiales.php?destino=solicituda','','resizable = no, scrollbars=yes, width = 900, height= 600')" style="cursor:pointer"></td>
              <td><input name="codigo_material" type="text" disabled id="codigo_material" size="20">
              <input type="hidden" id="id_material" name="id_material"></td>
              <td><input name="descripcion_material" type="text" disabled id="descripcion_material" size="95"></td>
              <td><input name="unidad_medida" type="text" disabled id="unidad_medida" size="10"></td>
              <td><input name="cantidad" type="text" id="cantidad" size="15" align="right"></td>
              <td><img src="imagenes/validar.png" title="Procesar Material" id="procesarMaterial" name="procesarMaterial" onClick="procesarMateriales(document.getElementById('id_solicitud').value, document.getElementById('id_material').value, document.getElementById('cantidad').value)" style="cursor:pointer"></td>
          </tr>
        </table>
    
        <div id="divContenidoMateriales" align="center" style="display:block; width:100%;">
          No hay Materiles Asociados
        </div>
    
</div>