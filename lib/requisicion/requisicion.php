<script src="js/requisicion_ajax.js" type="text/javascript" language="javascript"></script>
<?
/*
<h4 align=center>Requisiciones</h4>
<h2 class="sqlmVersion"></h2>
*/

$sql_configuracion=mysql_query("select * from configuracion 
											where status='a'"
												,$conexion_db);
$registro_configuracion=mysql_fetch_assoc($sql_configuracion);

$anio_fijo=$registro_configuracion["anio_fiscal"];
$idtipo_presupuesto_fijo=$registro_configuracion["idtipo_presupuesto"];
$idfuente_financiamiento_fijo=$registro_configuracion["idfuente_financiamiento"];
include "../../../funciones/funciones.php";
?>
<input type="hidden" name="tipo_carga_orden" id="tipo_carga_orden">
<input type="hidden" id="id_requisicion" name="id_requisicion">
<input type="hidden" id="idestado" name="idestado">

<table width="6%" border="0" align="center" cellpadding="0" cellspacing="2">
        <tr>
          <td align="right">
          	<div align="center">
				<img src="imagenes/search0.png" title="Buscar Requisiciones" style="cursor:pointer" onClick="abreVentana('lib/listas/listar_requisiciones.php?destino=ordenes');return false" /> 
			 </div>
          </td> 
          <td align="right">
          	<div align="center">
            	<img src="imagenes/nuevo.png" title="Ingresar nueva Requisi&oacute;n" onClick="window.location.href='principal.php?modulo=<?=$_GET["modulo"]?>&accion=<?=$_GET["accion"]?>'" style="cursor:pointer" />
            </div>
          </td> 
          <td align="right">
          	<div align="center" id="celdaRecalcular" style="display:none">
            	<img src="imagenes/refrescar.png" title="Recalcular Requisi&oacute;n" onClick="recalcular()" style="cursor:pointer" />   
			</div>
          </td> 
          <td align="right">
          	<div align="center" id="celdaImprimir" style="display:none">
            	<img src="imagenes/imprimir.png" title="Imprimir Requisi&oacute;n"  onClick="pdf.location.href='lib/reportes/compras_servicios/reportes.php?nombre=requisicion&id_requisicion='+document.getElementById('id_requisicion').value; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block';" style="cursor:pointer" />
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
    
    
<!-- TABLA DE DATOS BASICOS-->
<div id="divAjusteTituloDatos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:90%; margin-left:-560px;margin-top:5px">
<table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>Datos de la Requisici&oacute;n</strong></td>
    </tr>
</table>
</div> 



<div id="tablaDatosBasicos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:235px; width:90%; margin-left:-560px; margin-top:25px; overflow:auto">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
	<tr>
        <td width="15%" align="right" class='viewPropTitleNew'>N&uacute;mero:</td>
        <td width="10%" colspan="2" style="border:1px solid #999; background-color:#FFF" id="celdaNroOrden"><strong>Aun No Generado</strong></td>
        <td width="15%" align="right" class='viewPropTitleNew'>Fecha:</td>
        <td width="10%" align="left" ><input type="text" name="fecha_orden" id="fecha_orden" style="width:150px; height:20px" readonly="readonly" /></td>
        <td width="15%" align="right" class='viewPropTitleNew'>Fecha de Elaboraci&oacute;n:</td>
        <td width="5%" id="celdaFechaElaboracion" class='viewPropTitleNew'><strong><?=date("d-m-Y")?></strong></td>
	</tr>
    <tr>
    	<td align="right" class='viewPropTitleNew'>Estado:</td>
      	<td colspan="6" style="border:1px solid #999; background-color:#FFF" id="celdaEstado"><strong>&nbsp;En Elaboraci&oacute;n</strong></td>
    </tr>
    <tr>
      	<td align="right" class='viewPropTitleNew'>Tipo</td>
        <td colspan="6">
			<?
            $sql_tipos_documentos = mysql_query("select * from tipos_documentos where modulo like '%-".$_SESSION["modulo"]."-%' and compromete = 'si' and causa = 'no' and paga = 'no'");
            ?>
            <select name="tipo_orden" id="tipo_orden">
				<?
                while($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)){
                ?>
                    <option value="<?=$bus_tipos_documentos["idtipos_documentos"]?>"><?=$bus_tipos_documentos["descripcion"]?></option>
                <?
                }
            ?>
            </select>     
         </td>
    </tr>    
    
    <tr>
          <td align="right" class='viewPropTitleNew'>Proveedor:</td>
          <td colspan="4"><input name="nombre_proveedor" type="text" id="nombre_proveedor" size="120"  readonly="readonly"/>
                  <input type="hidden" name="id_beneficiarios" id="id_beneficiarios" />
                  <input type="hidden" name="contribuyente_ordinario" id="contribuyente_ordinario" />          
          </td>
          <td colspan="2"><img style="display:block"
                                        src="imagenes/search0.png" 
                                        title="Buscar Nuevo Proveedor" 
                                        id="buscarProveedor" 
                                        name="buscarProveedor" 
                                        onclick="window.open('modulos/compromisos/lib/listar_beneficiarios.php?destino=requisicion','listar proveedores','resizable = no, scrollbars = yes, width=900, height = 500')" /> 
          </td>
    </tr>
    
    <tr>
          <td align="right" class='viewPropTitleNew'>Categor&iacute;a Program&aacute;tica:</td>
          <td colspan="4"><input type="text" name="nombre_categoria" id="nombre_categoria" size="120" readonly="readonly"/>
                        <input type="hidden" name="id_categoria_programatica" id="id_categoria_programatica" />           </td>
          <td align="left"><img style="display:block"
                                                    src="imagenes/search0.png" 
                                                    title="Buscar Categoria Programatica" 
                                                    id="buscarCategoriaProgramatica" 
                                                    name="buscarCategoriaProgramatica"
                                                    onclick="window.open('lib/listas/lista_categorias_programaticas.php?destino=orden_compra','listar categorias programaticas','resizable = no, scrollbars=yes, width=900, height = 500')" 
                                                     />
           </td>
           <td align="left">
            	<a href="#" onClick="abrirCerrarDatosExtra()" id="textoContraerDatosExtra" style="font-size:10px">Origen Presupuestario</a>            
           </td>
    </tr>
    <input type="hidden" name="id_ordinal" id="id_ordinal" value="" />   
  	<tr>
  		<td colspan="7">  
            <table width="100%" id="datosExtra" style="display:none">  
                <tr>
                    <td width="21%" align="right" class='viewPropTitleNew'>Fuente de Financiamiento:</td>
                    <td colspan="2">
                    	<select name="fuente_financiamiento" id="fuente_financiamiento">
                            <option>.:: Seleccione ::.</option>
                            <?php
							$sql_fuente_financiamiento=mysql_query("select * from fuente_financiamiento where status='a'",$conexion_db);
							while($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)){?>
                              	<option onclick="document.getElementById('cofinanciamiento').value = 'no'" <?php echo 'value="'.$rowfuente_financiamiento["idfuente_financiamiento"].'"'; if ($rowfuente_financiamiento["idfuente_financiamiento"]==$idfuente_financiamiento_fijo) {echo ' selected';}?>> <?php echo $rowfuente_financiamiento["denominacion"];?> 
                                </option>
                      		<?php
                            }
                            $sql_cofinanciamiento = mysql_query("select * from cofinanciamiento");
                            while($bus_cofinanciamiento = mysql_fetch_array($sql_cofinanciamiento)){?>
                                 <option onclick="document.getElementById('cofinanciamiento').value = 'si'" value="<?=$bus_cofinanciamiento["idcofinanciamiento"]?>"><?=$bus_cofinanciamiento["denominacion"]?></option>
                                    <?
                            }?>
                    	</select>
                    	<input type="hidden" id="cofinanciamiento" name="cofinanciamiento" value="">
                    </td>
                  	<td align="right" class='viewPropTitleNew'>Tipo de Presupuesto:</td>
                  	<td align="right" >
                    	<select name="tipo_presupuesto" id="tipo_presupuesto">
                    		<option>.:: Seleccione ::.</option>
                    			<?php
                                $sql_tipo_presupuesto=mysql_query("select * from tipo_presupuesto 
                                                        where status='a'"
                                                            ,$conexion_db);
                                    while($rowtipo_presupuesto = mysql_fetch_array($sql_tipo_presupuesto)){?>
                    		<option <?php echo 'value="'.$rowtipo_presupuesto["idtipo_presupuesto"].'"';if ($rowtipo_presupuesto["idtipo_presupuesto"]==$idtipo_presupuesto_fijo){echo ' selected';}?>> <?php echo $rowtipo_presupuesto["denominacion"];?> 
                            </option>
                   			<?php }?>
                      	</select>
                    </td>
                    <td  align="right" class='viewPropTitleNew'>A&ntilde;o:</td>
                    <td ><select name="anio" id="anio" disabled="disabled">
                        <?
anio_fiscal();
?>
                      </select>
                    </td>   
				</tr>
 			</table>    
 		</td>
    </tr>
     
    <tr>
      <td align="right" class='viewPropTitleNew'>Concepto:</td>
      <td colspan="6"><textarea name="justificacion" cols="135" rows="3" id="justificacion"></textarea>
      &nbsp;<a href="#" onClick="abrirCerrarObservaciones()" id="textoContraerObservaciones"><img border="0" src="imagenes/comments.png" title="Observaciones" style="text-decoration:none"></a></td>
    </tr>
    
    <tr>
    	<td colspan="7">
    		<table id="divObservaciones" style="display:none" width="100%">
                <tr>
                    <td align="right" class='viewPropTitleNew'>Observaciones:</td>
                    <td width="85%" colspan="7"><textarea name="observaciones" cols="135" rows="2" id="observaciones"></textarea></td>
                </tr>
            </table>    	
        </td>
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
                            onclick="ingresarDatosBasicos(document.getElementById('tipo_orden').value, document.getElementById('id_categoria_programatica').value, document.getElementById('justificacion').value, document.getElementById('observaciones').value, document.getElementById('id_beneficiarios').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value)" 
                            class="button">       			
                </td>
                <td id="celdaBotonElaboracion">
           		<input type="button" 
                    name="botonEnElaboracion" 
                    id="botonEnElaboracion" 
                    value="En Elaboracion"
                    style="display:none"
                    onclick="actualizarDatosBasicos('actualizar')"
                    class="button">
           		</td>
                <td>		
					<?
                    if($_SESSION["modulo"] == 4 and in_array(388, $privilegios) == true){
                    ?>
                    <input type="button" 
                            name="botonProcesar" 
                            id="botonProcesar" 
                            value="Procesar"
                            style="display:none"
                            onclick="procesarOrden(document.getElementById('id_requisicion').value)"
                            class="button">
                    <?
                    }else if($_SESSION["modulo"] == 3 and in_array(399, $privilegios) == true){
                    ?>
                    <input type="button" 
                            name="botonProcesar" 
                            id="botonProcesar" 
                            value="Procesar"
                            style="display:none"
                            onclick="procesarOrden(document.getElementById('id_requisicion').value)"
                            class="button">
                    <?
                    }else if($_SESSION["modulo"] == 1 and in_array(435, $privilegios) == true){
                    ?>
                    <input type="button" 
                            name="botonProcesar" 
                            id="botonProcesar" 
                            value="Procesar"
                            style="display:none"
                            onclick="procesarOrden(document.getElementById('id_requisicion').value)"
                            class="button">
                    <?
                    }
                    ?>
                </td>
                <td>
					<?
                    if($_SESSION["modulo"] == 4 and in_array(389, $privilegios) == true){
                    ?>
                    <input type="button" 
                            name="botonAnular" 
                            id="botonAnular" 
                            value="Anular"
                            style="display:none"
                            onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'"
                            class="button">
                     <?
                     }else if($_SESSION["modulo"] == 3 and in_array(400, $privilegios) == true){
                    ?>
                    <input type="button" 
                            name="botonAnular" 
                            id="botonAnular" 
                            value="Anular"
                            style="display:none"
                            onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'"
                            class="button">
                     <?
                     }else if($_SESSION["modulo"] == 1 and in_array(436, $privilegios) == true){
                    ?>
                    <input type="button" 
                            name="botonAnular" 
                            id="botonAnular" 
                            value="Anular"
                            style="display:none"
                            onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'"
                            class="button">
                     <?
                     }
                     ?>
                </td>
                <td>		
					<?
                    if($_SESSION["modulo"] == 4 and in_array(390, $privilegios) == true){
                    ?>
                    <input type="button" 
                            name="botonDuplicar" 
                            id="botonDuplicar" 
                            value="Duplicar"
                            style="display:none"
                            onclick="duplicarOrden(document.getElementById('id_requisicion').value)"
                            class="button">
                    <?
                    }else if($_SESSION["modulo"] == 3 and in_array(401, $privilegios) == true){
                    ?>
                    <input type="button" 
                            name="botonDuplicar" 
                            id="botonDuplicar" 
                            value="Duplicar"
                            style="display:none"
                            onclick="duplicarOrden(document.getElementById('id_requisicion').value)"
                            class="button">
                    <?
                    }else if($_SESSION["modulo"] == 1 and in_array(437, $privilegios) == true){
                    ?>
                    <input type="button" 
                            name="botonDuplicar" 
                            id="botonDuplicar" 
                            value="Duplicar"
                            style="display:none"
                            onclick="duplicarOrden(document.getElementById('id_requisicion').value)"
                            class="button">
                    <?
                    }
                    ?>
                </td>
        	</tr>
       </table>
      </td>
    </tr>
  </table>
</div>


<div id="divPreguntarUsuario" style="display:none; position:absolute; z-index:11; background-color:#CCCCCC; border:#000000 solid 1px; margin-top:170px; margin-left:590px">
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
      <td colspan="2" align="center"><input type="button" name="validar" id="validar" class="button" value="Anular" onClick="anularOrden(document.getElementById('id_requisicion').value, document.getElementById('verificarClave').value)"></td>
    </tr>
</table>
</div>


 
  <!-- TABLA DE DATOS BASICOS-->
 
 <input type="hidden" name="solicitudes" id="solicitudes">
 
  
  <!-- TABLA DE PROVEEDORES-->

<div id="divTablaProveedores" style="display:block; position:absolute; left:50%; width:96%; margin-left:-600px; height:100px; height:auto !important; min-height:100px; margin-top:275px; overflow:auto">  


	<table align="center" cellpadding="0" cellspacing="0" id="tablaProveedor" style="display:none" width="100%">
    	<tr style="background: #09F">
      		<td width="25%" align="left"><strong>PROCESO:</strong>
                <select name="proceso" id="proceso">
                <option value="0">.:: Seleccione ::.</option>
                  <option onClick="document.getElementById('listaSolicitudesProveedor').style.display = 'none';
                                    document.getElementById('tipo_carga_orden').value = 'directo';
                                    actualizarTipoCargaOrden(document.getElementById('id_requisicion').value, 'directo')" 
                                                    value="directo">Directo</option>
                  <option onClick="consultarPedidosProveedores(document.getElementById('id_beneficiarios').value, document.getElementById('tipo_orden').value, document.getElementById('id_requisicion').value), 
                                    document.getElementById('tipo_carga_orden').value = 'cotizacion';
                                    actualizarTipoCargaOrden(document.getElementById('id_requisicion').value, 'cotizacion')"
                                                    value="cotizacion">Desde Consulta de Precios</option>
                </select>
      		</td>
      		<td align="right" width="90%">&nbsp;</td>
            <td align="right" width="5%">
                <a href="javascript:;" onClick="abrirCerrarProveedores()" id="textoContraerProveedores">
                <img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar">        </a>      
            </td>
        </tr>
    	<tr>
       		<td colspan="2" width="800">     
          		<table width="100%" align="center" id="formularioProveedores" style=" display:block" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="20%" align="left" valign="top" >
                      
        
                      <div id="listaSolicitudesProveedor" style="background-color:#CCCCCC; 
                                                                        border:#000000 1px solid; 
                                                                        display:none; 
                                                                        width:180px; 
                                                                        height:200px; 
                                                                        overflow:auto;
                                                                        cursor:pointer"></div>                      
                      </td>
                    <td width="80%" valign="top">
                      <div id="solicitudesSeleccionada" style="width:100%">
                      <center>No hay Solicitudes Seleccionadas</center>
                      </div>
                      
                     </td>
                    </tr>
     			</table>  
            </td>
   		</tr>
</table>
  <!-- PROVEEDORES-->

<br>

<!-- MATERIALES-->
<table width="100%" align="center" style="display:none" id="tablaMaterialesPartidas">
        <tr >
        <td colspan="3">
            <table align="center" width="60%">
                <tr>
                    <td bgcolor="#e7dfce" width="4%"></td>
                        <td width="26%" align="left"><font size="1"><strong >Disponibilidad Presupuestaria</strong></font></td>
                    <td bgcolor="#FFFF00" width="4%"></td>
                        <td width="26%" align="left"><font size="1"><strong >Sin Disponibilidad Presupuestaria</strong></font></td>
                    <td bgcolor="#FF0000" width="4%"></td>
                        <td width="26%" align="left"><font size="1"><strong >Sin Partida Presupuestaria</strong></font></td>
                </tr>
            </table>
        </td>
        </tr>
        <tr style="background: #09F">
          <td align="left" width="25%" ><strong>MATERIALES</strong></td>
          <td align="right" width="80%" >
          	<span id="totales">
            <strong>Exento:</strong> 0,00 |
            <strong>Sub Total:</strong> 0,00 | 
            <strong>Impuesto:</strong> 0,00 | 
            <strong>Total Bsf:</strong> 0,00
            </span >
          </td>
          <td width="1%"
            <a href="javascript:;" onClick="abrirCerrarMateriales()" id="textoContraerMateriales">
            <img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar">
            </a>
          </td>
 		</tr>
        <tr>
          <td colspan="3" align="right">
            
            <form name="formularioMateriales" id="formularioMateriales" onsubmit="return ingresarMaterialIndividual(document.getElementById('id_requisicion').value, document.getElementById('id_material').value,document.getElementById('cantidad').value, document.getElementById('precio_unitario').value, document.getElementById('id_categoria_programatica').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value, document.getElementById('contribuyente_ordinario').value)">
            <table width="99%" border="0" align="center" cellpadding="0" cellspacing="0" id="formularioMateriales" style="display:block">
                <tr>
                      <td>&nbsp;</td>
                      <td align="center" width="15%">C&oacute;digo:</td>
                      <td align="center">Descripci&oacute;n:</td>
                      <td align="center">Und:</td>
                      <td align="center">Cantidad:</td>
                      <td align="center">PU:</td>
                      <td>&nbsp;</td>
               </tr>
               <tr>
               		<td>
                    <img src="imagenes/search0.png" 
                    			style="cursor:pointer"
                                title="Buscar Nuevo Material" 
                                id="buscarMaterial" 
                                name="buscarMaterial" 
                                onClick="window.open('lib/listas/listar_materiales.php?destino=requisicion',
                                					'',
                                                        'resizable = no, scrollbars=yes, width = 800, height= 400')">
                    
               		</td>
                    <td>
                    	<input name="codigo_material" type="text" disabled id="codigo_material" size="23" width="100%">
                        <input type="hidden" id="id_material" name="id_material">
                    </td>
                    <td>
                        <input name="descripcion_material" type="text" disabled id="descripcion_material" size="80" width="100%">
                    </td>
                    <td>
                        <input name="unidad_medida" type="text" disabled id="unidad_medida" size="10" width="100%">
                    </td>
                    <td>
                        <input name="cantidad" type="text" id="cantidad" size="20" width="100%">
                    </td>
                    <td>
                        <input name="precio_unitario" type="text" id="precio_unitario" size="22" width="100%">
                    </td>
                    <td>
                        <input type="image" src="imagenes/validar.png" 
                                                    title="Procesar Material" 
                                                    id="procesarMaterial" 
                                                    name="procesarMaterial">
                    </td>
               </tr>
            </table>
            </form>
          </td>
  		</tr>
        <tr>
          <td colspan="3" align="center">
          	<div id="divMateriales" style="display:block">
          		No hay Materiles Asociados
          	</div>
          </td>
        </tr>
   </td>
 </tr>
 </table> 
       <!-- PROVEEDORES-->
<!-- PARTIDAS-->
<br><br>
<table width="100%" align="center" style="display:none" id="tablaPartidas">
     <tr style="background: #09F">
      <td align="left" width="24%"><strong>AFECTACI&Oacute;N PRESUPUESTARIA</strong></td>
      <td align="right" width="80%">
        <span id="totalPartidas"><strong>Total Bsf: </strong>0,00</span>
      </td>
	  <td align="right" width="5%">
        <a href="javascript:;" onClick="abrirCerrarPartidas()" id="textoContraerPartidas">
            <img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar">
        </a>
      </td>
    </tr>
    <tr>
      <td colspan="3" align="center">
        <div id="divPartidas" style="display:block">
            No hay Partidas Asociadas
        </div>
      </td>
    </tr>
      <!-- PARTIDAS-->
</table>

</div>

