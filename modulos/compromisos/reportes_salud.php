<?php
/*
$ls=array(
"actividad"=>"actividad",
"familia"=>"familia",
"grupo"=>"grupo"
);
$_SESSION['listadoSelect']=serialize($ls);
*/
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>
<table style="width:100%; height:100%">
	<tr>
    	<td width="20%" valign="top"><script type="text/javascript" src="js/treeMenu/salud/compras_servicios.js"></script></td>
       	<td width="80%" valign="top" align="right">
        	<a id="aCerrar" style="font-weight:bold; font-size:18px; text-decoration:none; color:#000000; display:none" title="Cerrar Ventana" href="#" onClick="document.getElementById('frmReporte').style.display='none'; document.getElementById('frmReporte').src=''; document.getElementById('aCerrar').style.display='none';">X</a>
            <iframe src="" name="frmReporte" id="frmReporte" width="95%" height="90%" scrolling="auto" frameborder="1" style="border-color:#333333; display:none;"></iframe>
        </td>
    </tr>
</table>
</body>
</html>



<?php
/*
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
<!--
.Select1 {width: 225}
-->
</style>
<SCRIPT language=JavaScript>
var listadoSelects=new Array();
listadoSelects[0]="actividad";
listadoSelects[1]="familia";
listadoSelects[2]="grupo";
</script>
</head>

<body>
<table align="center">
  <tr>
    <td width="400" valign='top'>
    	<table width="225" style="border:1px solid; border-color:#999999;">
        	<label><b>Reportes</b></label>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="lib/generar_reporte.php?nombre=tipobene" target="frmReporte">Tipos de Beneficiario</a>            		
            	</td>
            </tr>
            
			<tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="lib/generar_reporte.php?nombre=edobene" target="frmReporte">Estado de Beneficiario</a>            		
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="lib/generar_reporte.php?nombre=tipopersona" target="frmReporte">Tipos de Persona</a>            		
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="#" onclick="document.getElementById('divTipoOrdenTsoc').style.display='block'; document.getElementById('divTipoOrdenSNC').style.display='none'; document.getElementById('divTipoOrdenUnim').style.display='none'; document.getElementById('divTipoOrdenImp').style.display='none'; document.getElementById('divTipoOrdenSncact').style.display='none'; document.getElementById('divTipoOrdenSncfam').style.display='none'; document.getElementById('divTipoOrdenSncgru').style.display='none'; document.getElementById('divTipoOrdenSncdet').style.display='none'; document.getElementById('divTipoOrdenBenef').style.display='none'; document.getElementById('divTipoOrdenDbenef').style.display='none';">Tipo de Sociedad</a>
            		<div id="divTipoOrdenTsoc" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            		<table>
            			<tr>
                			<td>Ordenar Por: </td>
                			<td>
                            	<select name="ordenarPor1" id="ordenarPor1">
                                    <option value="descripcion">Descripci&oacute;n</option>
                                    <option value="siglas">Siglas</option>
                                </select>
                                <a href="#" onclick="document.getElementById('divTipoOrdenTsoc').style.display='none'">X</a>
                            </td>
                        </tr>
                		<tr>
                			<td colspan="2">
                            	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="frmReporte.location.href='lib/generar_reporte.php?nombre=tiposociedad&orden='+document.getElementById('ordenarPor1').value; document.getElementById('divTipoOrdenTsoc').style.display='none';">
                            </td>
                		</tr>
            		</table>
            		</div>
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="lib/generar_reporte.php?nombre=tipoempresa" target="frmReporte">Tipos de Empresas</a>            		
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="lib/generar_reporte.php?nombre=docreq" target="frmReporte">Documentos Requeridos</a>            		
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="#" onclick="document.getElementById('divTipoOrdenUnim').style.display='block'; document.getElementById('divTipoOrdenSNC').style.display='none'; document.getElementById('divTipoOrdenTsoc').style.display='none'; document.getElementById('divTipoOrdenImp').style.display='none'; document.getElementById('divTipoOrdenSncact').style.display='none'; document.getElementById('divTipoOrdenSncfam').style.display='none'; document.getElementById('divTipoOrdenSncgru').style.display='none'; document.getElementById('divTipoOrdenSncdet').style.display='none'; document.getElementById('divTipoOrdenBenef').style.display='none'; document.getElementById('divTipoOrdenDbenef').style.display='none';">Unidades de Medida</a>
            		<div id="divTipoOrdenUnim" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            		<table>
            			<tr>
                			<td>Ordenar Por: </td>
                			<td>
                            	<select name="ordenarPor2" id="ordenarPor2">
                                    <option value="descripcion">Descripci&oacute;n</option>
                                    <option value="abreviado">Abreviado</option>
                                </select>
                                <a href="#" onclick="document.getElementById('divTipoOrdenUnim').style.display='none'">X</a>
                            </td>
                        </tr>
                		<tr>
                			<td colspan="2">
                            	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="frmReporte.location.href='lib/generar_reporte.php?nombre=unidadm&orden='+document.getElementById('ordenarPor2').value; document.getElementById('divTipoOrdenUnim').style.display='none';">
                            </td>
                		</tr>
            		</table>
            		</div>
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="lib/generar_reporte.php?nombre=ramos" target="frmReporte">Ramos de Materiales</a>            		
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="#" onclick="document.getElementById('divTipoOrdenImp').style.display='block'; document.getElementById('divTipoOrdenSNC').style.display='none'; document.getElementById('divTipoOrdenTsoc').style.display='none'; document.getElementById('divTipoOrdenUnim').style.display='none'; document.getElementById('divTipoOrdenSncact').style.display='none'; document.getElementById('divTipoOrdenSncfam').style.display='none'; document.getElementById('divTipoOrdenSncgru').style.display='none'; document.getElementById('divTipoOrdenSncdet').style.display='none'; document.getElementById('divTipoOrdenBenef').style.display='none'; document.getElementById('divTipoOrdenDbenef').style.display='none';">Impuestos</a>
            		<div id="divTipoOrdenImp" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            		<table>
            			<tr>
                			<td>Ordenar Por: </td>
                			<td>
                            	<select name="ordenarPor3" id="ordenarPor3">
                                    <option value="descripcion">Descripci&oacute;n</option>
                                    <option value="siglas">Siglas</option>
                                    <option value="porcentaje">Porcentaje</option>
                                </select>
                                <a href="#" onclick="document.getElementById('divTipoOrdenImp').style.display='none'">X</a>
                            </td>
                        </tr>
                		<tr>
                			<td colspan="2">
                            	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="frmReporte.location.href='lib/generar_reporte.php?nombre=impuestos&orden='+document.getElementById('ordenarPor3').value; document.getElementById('divTipoOrdenImp').style.display='none';">
                            </td>
                		</tr>
            		</table>
            		</div>
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="#" onclick="document.getElementById('divTipoOrdenSncact').style.display='block'; document.getElementById('divTipoOrdenSNC').style.display='none'; document.getElementById('divTipoOrdenTsoc').style.display='none'; document.getElementById('divTipoOrdenImp').style.display='none'; document.getElementById('divTipoOrdenUnim').style.display='none'; document.getElementById('divTipoOrdenSncfam').style.display='none'; document.getElementById('divTipoOrdenSncgru').style.display='none'; document.getElementById('divTipoOrdenSncdet').style.display='none'; document.getElementById('divTipoOrdenBenef').style.display='none'; document.getElementById('divTipoOrdenDbenef').style.display='none';">SNC Actividades</a>
            		<div id="divTipoOrdenSncact" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            		<table>
            			<tr>
                			<td>Ordenar Por: </td>
                			<td>
                            	<select name="ordenarPor4" id="ordenarPor4">
                                    <option value="descripcion">Descripci&oacute;n</option>
                                    <option value="sigla">Sigla</option>
                                </select>
                                <a href="#" onclick="document.getElementById('divTipoOrdenSncact').style.display='none'">X</a>
                            </td>
                        </tr>
                		<tr>
                			<td colspan="2">
                            	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="frmReporte.location.href='lib/generar_reporte.php?nombre=sncactividad&orden='+document.getElementById('ordenarPor4').value; document.getElementById('divTipoOrdenSncact').style.display='none';">
                            </td>
                		</tr>
            		</table>
            		</div>
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="#" onclick="document.getElementById('divTipoOrdenSncfam').style.display='block'; document.getElementById('divTipoOrdenSNC').style.display='none'; document.getElementById('divTipoOrdenTsoc').style.display='none'; document.getElementById('divTipoOrdenImp').style.display='none'; document.getElementById('divTipoOrdenUnim').style.display='none'; document.getElementById('divTipoOrdenSncact').style.display='none'; document.getElementById('divTipoOrdenSncgru').style.display='none'; document.getElementById('divTipoOrdenSncdet').style.display='none'; document.getElementById('divTipoOrdenBenef').style.display='none'; document.getElementById('divTipoOrdenDbenef').style.display='none';">SNC Familia Actividad</a>
            		<div id="divTipoOrdenSncfam" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            		<table>
            			<tr>
                			<td>Ordenar Por: </td>
                			<td>
                            	<select name="ordenarPor5" id="ordenarPor5">
                                    <option value="snc_actividades.descripcion">Actividad</option>
                                    <option value="snc_familia_actividad.descripcion">Familia</option>
                                    <option value="snc_familia_actividad.codigo">Codigo</option>
                                </select>
                                <a href="#" onclick="document.getElementById('divTipoOrdenSncfam').style.display='none'">X</a>
                            </td>
                        </tr>
                		<tr>
                			<td colspan="2">
                            	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="frmReporte.location.href='lib/generar_reporte.php?nombre=sncfamilia&orden='+document.getElementById('ordenarPor5').value; document.getElementById('divTipoOrdenSncfam').style.display='none';">
                            </td>
                		</tr>
            		</table>
            		</div>
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="#" onclick="document.getElementById('divTipoOrdenSncgru').style.display='block'; document.getElementById('divTipoOrdenSNC').style.display='none'; document.getElementById('divTipoOrdenTsoc').style.display='none'; document.getElementById('divTipoOrdenImp').style.display='none'; document.getElementById('divTipoOrdenUnim').style.display='none'; document.getElementById('divTipoOrdenSncact').style.display='none'; document.getElementById('divTipoOrdenSncfam').style.display='none'; document.getElementById('divTipoOrdenSncdet').style.display='none'; document.getElementById('divTipoOrdenBenef').style.display='none'; document.getElementById('divTipoOrdenDbenef').style.display='none';">SNC Grupo Actividad</a>
            		<div id="divTipoOrdenSncgru" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            		<table>
            			<tr>
                			<td>Ordenar Por: </td>
                			<td>
                            	<select name="ordenarPor6" id="ordenarPor6">
                                    <option value="codigo">Codigo</option>
                                    <option value="descripcion">Grupo</option>
                                </select>
                                <a href="#" onclick="document.getElementById('divTipoOrdenSncgru').style.display='none'">X</a>
                            </td>
                        </tr>
                		<tr>
                			<td colspan="2">
                            	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="frmReporte.location.href='lib/generar_reporte.php?nombre=sncgrupo&orden='+document.getElementById('ordenarPor6').value; document.getElementById('divTipoOrdenSncgru').style.display='none';">
                            </td>
                		</tr>
            		</table>
            		</div>
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="#" onclick="document.getElementById('divTipoOrdenSncdet').style.display='block'; document.getElementById('divTipoOrdenSNC').style.display='none'; document.getElementById('divTipoOrdenTsoc').style.display='none'; document.getElementById('divTipoOrdenImp').style.display='none'; document.getElementById('divTipoOrdenUnim').style.display='none'; document.getElementById('divTipoOrdenSncact').style.display='none'; document.getElementById('divTipoOrdenSncfam').style.display='none'; document.getElementById('divTipoOrdenSncgru').style.display='none'; document.getElementById('divTipoOrdenBenef').style.display='none'; document.getElementById('divTipoOrdenDbenef').style.display='none';">SNC Detalle Actividad</a>
            		<div id="divTipoOrdenSncdet" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            		<table>
            			<tr>
                			<td>Ordenar Por: </td>
                			<td>
                            	<select name="ordenarPor7" id="ordenarPor7">
                                    <option value="snc_grupo_actividad.descripcion">Grupo Actividad</option>
                                    <option value="snc_detalle_grupo.descripcion">Detalle</option>
                                    <option value="snc_detalle_grupo.codigo">Codigo</option>
                                </select>
                                <a href="#" onclick="document.getElementById('divTipoOrdenSncdet').style.display='none'">X</a>
                            </td>
                        </tr>
                		<tr>
                			<td colspan="2">
                            	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="frmReporte.location.href='lib/generar_reporte.php?nombre=sncdetalle&orden='+document.getElementById('ordenarPor7').value; document.getElementById('divTipoOrdenSncdet').style.display='none';">
                            </td>
                		</tr>
            		</table>
            		</div>
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="#" onclick="document.getElementById('divTipoOrdenSNC').style.display='block'; document.getElementById('divTipoOrdenTsoc').style.display='none'; document.getElementById('divTipoOrdenUnim').style.display='none'; document.getElementById('divTipoOrdenImp').style.display='none'; document.getElementById('divTipoOrdenSncact').style.display='none'; document.getElementById('divTipoOrdenSncfam').style.display='none'; document.getElementById('divTipoOrdenSncgru').style.display='none'; document.getElementById('divTipoOrdenSncdet').style.display='none'; document.getElementById('divTipoOrdenBenef').style.display='none'; document.getElementById('divTipoOrdenDbenef').style.display='none';">SNC</a>
            		<div id="divTipoOrdenSNC" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            		<table>
                    	<tr><td align="right" colspan="2"><a href="#" onclick="document.getElementById('divTipoOrdenSNC').style.display='none'">X</a></td></tr>
            			<tr>
                			<td>Actividades: </td>
                			<td>
                            	<select name="actividad" id="actividad" onChange="cargaContenido(this.id)" class="Select1">
                                    <option value="0">Elige</option>
                                    <?php
                                    $sql="SELECT idsnc_actividades, descripcion FROM snc_actividades ORDER BY idsnc_actividades";
                                    $query=mysql_query($sql) or die ($sql.mysql_error());
                                    $rows=mysql_num_rows($query);
                                    for ($i=0; $i<$rows; $i++) {
                                    	$field=mysql_fetch_array($query);
                                        echo "<option value='".$field[0]."'>".$field[1]."</option>";
                                    }
                                    ?>
                                </select>                                
                            </td>
                        </tr>
                        <tr>
                			<td>Familia de la Actividad: </td>
                			<td>
                            	<select name="familia" id="familia" disabled class="Select1">
                                    <option value="0">Selecciona Opci&oacute;n...</option>                                    
                                </select>
                            </td>
                        </tr>
                        <tr>
                			<td>Grupo de la Actividad: </td>
                			<td>
                            	<select name="grupo" id="grupo" disabled class="Select1">
                                    <option value="0">Selecciona Opci&oacute;n...</option>
                                </select>
                            </td>
                        </tr>
                		<tr>
                			<td colspan="2">
                            	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="frmReporte.location.href='lib/generar_reporte.php?nombre=snc&act='+document.getElementById('actividad').value+'&fam='+document.getElementById('familia').value+'&gru='+document.getElementById('grupo').value; document.getElementById('divTipoOrdenSNC').style.display='none';">
                            </td>
                		</tr>
            		</table>
            		</div>
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="#" onclick="document.getElementById('divTipoOrdenBenef').style.display='block'; document.getElementById('divTipoOrdenTsoc').style.display='none'; document.getElementById('divTipoOrdenUnim').style.display='none'; document.getElementById('divTipoOrdenImp').style.display='none'; document.getElementById('divTipoOrdenSncact').style.display='none'; document.getElementById('divTipoOrdenSncfam').style.display='none'; document.getElementById('divTipoOrdenSncgru').style.display='none'; document.getElementById('divTipoOrdenSncdet').style.display='none'; document.getElementById('divTipoOrdenSNC').style.display='none'; document.getElementById('divTipoOrdenDbenef').style.display='none';">Beneficiarios</a>
            		<div id="divTipoOrdenBenef" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            		<table>
                    	<tr><td align="right" colspan="2"><a href="#" onclick="document.getElementById('divTipoOrdenBenef').style.display='none'">X</a></td></tr>
            			<tr>
                			<td>Tipo de Beneficiario: </td>
                			<td>
                            	<select name="tbene" id="tbene" class="Select1">
                                    <option value="0">Elige</option>
                                    <?php
                                    $sql="SELECT idtipo_beneficiario, descripcion FROM tipo_beneficiario ORDER BY idtipo_beneficiario";
                                    $query=mysql_query($sql) or die ($sql.mysql_error());
                                    $rows=mysql_num_rows($query);
                                    for ($i=0; $i<$rows; $i++) {
                                    	$field=mysql_fetch_array($query);
                                        echo "<option value='".$field[0]."'>".$field[1]."</option>";
                                    }
                                    ?>
                                </select>                                
                            </td>
                        </tr>
            			<tr>
                			<td>Estado del Beneficiario: </td>
                			<td>
                            	<select name="ebene" id="ebene" class="Select1">
                                    <option value="0">Elige</option>
                                    <?php
                                    $sql="SELECT idestado_beneficiario, descripcion FROM estado_beneficiario ORDER BY idestado_beneficiario";
                                    $query=mysql_query($sql) or die ($sql.mysql_error());
                                    $rows=mysql_num_rows($query);
                                    for ($i=0; $i<$rows; $i++) {
                                    	$field=mysql_fetch_array($query);
                                        echo "<option value='".$field[0]."'>".$field[1]."</option>";
                                    }
                                    ?>
                                </select>                                
                            </td>
                        </tr>
            			<tr>
                			<td>Tipo de Persona: </td>
                			<td>
                            	<select name="tpersona" id="tpersona" class="Select1">
                                    <option value="0">Elige</option>
                                    <?php
                                    $sql="SELECT idtipos_persona, descripcion FROM tipos_persona ORDER BY idtipos_persona";
                                    $query=mysql_query($sql) or die ($sql.mysql_error());
                                    $rows=mysql_num_rows($query);
                                    for ($i=0; $i<$rows; $i++) {
                                    	$field=mysql_fetch_array($query);
                                        echo "<option value='".$field[0]."'>".$field[1]."</option>";
                                    }
                                    ?>
                                </select>                                
                            </td>
                        </tr>
            			<tr>
                			<td>Tipo de Sociedad: </td>
                			<td>
                            	<select name="tsoc" id="tsoc" class="Select1">
                                    <option value="0">Elige</option>
                                    <?php
                                    $sql="SELECT idtipo_sociedad, descripcion FROM tipo_sociedad ORDER BY idtipo_sociedad";
                                    $query=mysql_query($sql) or die ($sql.mysql_error());
                                    $rows=mysql_num_rows($query);
                                    for ($i=0; $i<$rows; $i++) {
                                    	$field=mysql_fetch_array($query);
                                        echo "<option value='".$field[0]."'>".$field[1]."</option>";
                                    }
                                    ?>
                                </select>                                
                            </td>
                        </tr>
            			<tr>
                			<td>Tipo de Empresa: </td>
                			<td>
                            	<select name="temp" id="temp" class="Select1">
                                    <option value="0">Elige</option>
                                    <?php
                                    $sql="SELECT idtipo_empresa, descripcion FROM tipo_empresa ORDER BY idtipo_empresa";
                                    $query=mysql_query($sql) or die ($sql.mysql_error());
                                    $rows=mysql_num_rows($query);
                                    for ($i=0; $i<$rows; $i++) {
                                    	$field=mysql_fetch_array($query);
                                        echo "<option value='".$field[0]."'>".$field[1]."</option>";
                                    }
                                    ?>
                                </select>                                
                            </td>
                        </tr>
                		<tr>
                			<td colspan="2">
                            	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="frmReporte.location.href='lib/generar_reporte.php?nombre=beneficiario&tbene='+document.getElementById('tbene').value+'&ebene='+document.getElementById('ebene').value+'&tpersona='+document.getElementById('tpersona').value+'&tsoc='+document.getElementById('tsoc').value+'&temp='+document.getElementById('temp').value; document.getElementById('divTipoOrdenBenef').style.display='none';">
                            </td>
                		</tr>
            		</table>
            		</div>
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="#" onclick="document.getElementById('divTipoOrdenDbenef').style.display='block'; document.getElementById('divTipoOrdenTsoc').style.display='none'; document.getElementById('divTipoOrdenUnim').style.display='none'; document.getElementById('divTipoOrdenImp').style.display='none'; document.getElementById('divTipoOrdenSncact').style.display='none'; document.getElementById('divTipoOrdenSncfam').style.display='none'; document.getElementById('divTipoOrdenSncgru').style.display='none'; document.getElementById('divTipoOrdenSncdet').style.display='none'; document.getElementById('divTipoOrdenSNC').style.display='none'; document.getElementById('divTipoOrdenBenef').style.display='none';">Documentos Entregados</a>
            		<div id="divTipoOrdenDbenef" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            		<table>
                    	<tr><td align="right" colspan="2"><a href="#" onclick="document.getElementById('divTipoOrdenDbenef').style.display='none'">X</a></td></tr>
            			<tr>
                			<td>Tipo de Beneficiario: </td>
                			<td>
                            	<select name="dtbene" id="dtbene" class="Select1">
                                    <option value="0">Elige</option>
                                    <?php
                                    $sql="SELECT idtipo_beneficiario, descripcion FROM tipo_beneficiario ORDER BY idtipo_beneficiario";
                                    $query=mysql_query($sql) or die ($sql.mysql_error());
                                    $rows=mysql_num_rows($query);
                                    for ($i=0; $i<$rows; $i++) {
                                    	$field=mysql_fetch_array($query);
                                        echo "<option value='".$field[0]."'>".$field[1]."</option>";
                                    }
                                    ?>
                                </select>                                
                            </td>
                        </tr>
            			<tr>
                			<td>Estado del Beneficiario: </td>
                			<td>
                            	<select name="debene" id="debene" class="Select1">
                                    <option value="0">Elige</option>
                                    <?php
                                    $sql="SELECT idestado_beneficiario, descripcion FROM estado_beneficiario ORDER BY idestado_beneficiario";
                                    $query=mysql_query($sql) or die ($sql.mysql_error());
                                    $rows=mysql_num_rows($query);
                                    for ($i=0; $i<$rows; $i++) {
                                    	$field=mysql_fetch_array($query);
                                        echo "<option value='".$field[0]."'>".$field[1]."</option>";
                                    }
                                    ?>
                                </select>                                
                            </td>
                        </tr>
            			<tr>
                			<td>Tipo de Persona: </td>
                			<td>
                            	<select name="dtpersona" id="dtpersona" class="Select1">
                                    <option value="0">Elige</option>
                                    <?php
                                    $sql="SELECT idtipos_persona, descripcion FROM tipos_persona ORDER BY idtipos_persona";
                                    $query=mysql_query($sql) or die ($sql.mysql_error());
                                    $rows=mysql_num_rows($query);
                                    for ($i=0; $i<$rows; $i++) {
                                    	$field=mysql_fetch_array($query);
                                        echo "<option value='".$field[0]."'>".$field[1]."</option>";
                                    }
                                    ?>
                                </select>                                
                            </td>
                        </tr>
            			<tr>
                			<td>Tipo de Sociedad: </td>
                			<td>
                            	<select name="dtsoc" id="dtsoc" class="Select1">
                                    <option value="0">Elige</option>
                                    <?php
                                    $sql="SELECT idtipo_sociedad, descripcion FROM tipo_sociedad ORDER BY idtipo_sociedad";
                                    $query=mysql_query($sql) or die ($sql.mysql_error());
                                    $rows=mysql_num_rows($query);
                                    for ($i=0; $i<$rows; $i++) {
                                    	$field=mysql_fetch_array($query);
                                        echo "<option value='".$field[0]."'>".$field[1]."</option>";
                                    }
                                    ?>
                                </select>                                
                            </td>
                        </tr>
            			<tr>
                			<td>Tipo de Empresa: </td>
                			<td>
                            	<select name="dtemp" id="dtemp" class="Select1">
                                    <option value="0">Elige</option>
                                    <?php
                                    $sql="SELECT idtipo_empresa, descripcion FROM tipo_empresa ORDER BY idtipo_empresa";
                                    $query=mysql_query($sql) or die ($sql.mysql_error());
                                    $rows=mysql_num_rows($query);
                                    for ($i=0; $i<$rows; $i++) {
                                    	$field=mysql_fetch_array($query);
                                        echo "<option value='".$field[0]."'>".$field[1]."</option>";
                                    }
                                    ?>
                                </select>                                
                            </td>
                        </tr>
                		<tr>
                			<td colspan="2">
                            	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="frmReporte.location.href='lib/generar_reporte.php?nombre=dbeneficiario&tbene='+document.getElementById('dtbene').value+'&ebene='+document.getElementById('debene').value+'&tpersona='+document.getElementById('dtpersona').value+'&tsoc='+document.getElementById('dtsoc').value+'&temp='+document.getElementById('dtemp').value; document.getElementById('divTipoOrdenDbenef').style.display='none';">
                            </td>
                		</tr>
            		</table>
            		</div>
            	</td>
            </tr>
            
                       
        </table>
   	</td>
    <td>
    	<iframe src="lib/msj_reporte.php" name="frmReporte" id="frmReporte" width="700" height="575" style="border:1px solid; border-color:#999999;">
        </iframe>
    </td>
  </tr>
</table>
</body>
</html>




<?php
/*
<?php 
	require "lib/kooltreeview/kooltreeview.php";
	$treeview = new KoolTreeView("treeview");
	$treeview->imageFolder="lib/kooltreeview/icons";
	$treeview->styleFolder="lib/kooltreeview/styles/default";

	$root = $treeview->getRootNode();
	$root->text = "<span style='background-color:#FF0000; color:#FFFFFF; font-weight:bold'>&nbsp;Reportes&nbsp;</span>";
	$root->expand=true;
	$root->image="woman2S.gif";
	
	//	Nivel Administracion de Beneficiarios
	$node=$treeview->Add("root","AdminBene","Administracion de Beneficiarios",false,"Folder_o.gif","");
	$node=$treeview->Add("AdminBene", "P17", "Beneficiarios", false, "pdf.gif", "");	
		$node->addData("tipo", "filtro");
		$node->addData("nombre", "beneficiario");
	$node=$treeview->Add("AdminBene", "P11", "Tipo de Beneficiario", false, "pdf.gif", "");	
		$node->addData("tipo", "directo");
		$node->addData("nombre", "tipobene");
	$node=$treeview->Add("AdminBene", "P12", "Estado de Beneficiario", false, "pdf.gif", "");	
		$node->addData("tipo", "directo");
		$node->addData("nombre", "edobene");
	$node=$treeview->Add("AdminBene", "P13", "Tipos de Persona", false, "pdf.gif", "");	
		$node->addData("tipo", "directo");
		$node->addData("nombre", "tipopersona");
	$node=$treeview->Add("AdminBene", "P14", "Tipos de Sociedad", false, "pdf.gif", "");	
		$node->addData("tipo", "filtro");
		$node->addData("nombre", "tiposociedad");
	$node=$treeview->Add("AdminBene", "P15", "Tipos de Empresa", false, "pdf.gif", "");	
		$node->addData("tipo", "filtro");
		$node->addData("nombre", "tipoempresa");
	$node=$treeview->Add("AdminBene", "P16", "Documentos Requeridos", false, "pdf.gif", "");	
		$node->addData("tipo", "filtro");
		$node->addData("nombre", "docreq");
	$node=$treeview->Add("AdminBene", "P18", "Documentos Entregados", false, "pdf.gif", "");	
		$node->addData("tipo", "filtro");
		$node->addData("nombre", "dbeneficiario");
	
	//	Nivel Administracion de Materiales
	$node=$treeview->Add("root","AdminMate","Administracion de Materiales",false,"Folder_o.gif","");
	$node=$treeview->Add("AdminMate", "P21", "Unidades de Medida", false, "pdf.gif", "");	
		$node->addData("tipo", "filtro");
		$node->addData("nombre", "unidadm");
	$node=$treeview->Add("AdminMate", "P22", "Ramos de Materiales", false, "pdf.gif", "");	
		$node->addData("tipo", "directo");
		$node->addData("nombre", "ramos");
	$node=$treeview->Add("AdminMate", "P23", "Impuestos", false, "pdf.gif", "");	
		$node->addData("tipo", "filtro");
		$node->addData("nombre", "impuestos");
	$node=$treeview->Add("AdminMate", "P24", "SNC Actividades", false, "pdf.gif", "");	
		$node->addData("tipo", "filtro");
		$node->addData("nombre", "sncactividad");
	$node=$treeview->Add("AdminMate", "P25", "SNC Familia Actividad", false, "pdf.gif", "");	
		$node->addData("tipo", "filtro");
		$node->addData("nombre", "sncfamilia");
	$node=$treeview->Add("AdminMate", "P26", "SNC Grupo Actividad", false, "pdf.gif", "");	
		$node->addData("tipo", "filtro");
		$node->addData("nombre", "sncgrupo");
	$node=$treeview->Add("AdminMate", "P27", "SNC Detalle Actividad", false, "pdf.gif", "");	
		$node->addData("tipo", "filtro");
		$node->addData("nombre", "sncdetalle");
	$node=$treeview->Add("AdminMate", "P28", "SNC", false, "pdf.gif", "");	
		$node->addData("tipo", "filtro");
		$node->addData("nombre", "snc");
	
	//	Nivel Solicitud de Cotizaciones
	$node=$treeview->Add("root","Cotizaciones","Solicitud de Cotizaciones", false,"Folder_o.gif","");
	$node=$treeview->Add("Cotizaciones", "P41", "Solicitud de Cotizaciones", false, "pdf.gif", "");	
		$node->addData("tipo", "filtro");
		$node->addData("nombre", "filtro_solicitud_cotizacion_com");
	
	//	Orden de Compra/Servicio
	$node=$treeview->Add("root","orden_compra_servicio","Orden de Compra/Servicio", false,"Folder_o.gif","");
	$node=$treeview->Add("orden_compra_servicio", "P31", "Orden de Compra Servicio", false, "pdf.gif", "");	
		$node->addData("tipo", "filtro");
		$node->addData("nombre", "filtro_orden_compra_servicio");
		
	$treeview->showLines = true;
?>
<html>
 <head>
 	<title>How to make KoolTreeView state-persisted</title>
	<META http-equiv=Content-Type content="text/html; charset=utf-8">
	<link rel="stylesheet" href="style.css"/>
 </head>
 <body class="documentBody">
 	<form id="form1" method="post">
 	<div class="indent indent_right bottomspacing"><br/></div>
	<style type="text/css" rel="stylesheet">
	 #img
	 {
		border : gray 1px solid ;		

		padding: 0;
		margin:5px;
		height : 120px;
		width : 160px;
	 }
	#name, #price, #auth
	 {
		padding: 3px;
		margin:5px;		
	 }
	  #columnleft
	 {
	  float:left;
	  width:200px;
	  height:180px;	 
		margin : 5px;
		padding : 10px;
	 }
	  #columnright
	 {
	  float:left;
	  width:200px;
	  height:180px;	  
	  margin : 5px;
	  padding : 10px;
	 }	
	</style>
	<!-- Shows  -->
    
    <table style="width:100%; height:100%">
    	<tr>
        	<td width="20%" valign="top"><?php echo $treeview->Render();?></td>
       	  	<td width="80%" valign="top">
            	<a id="aCerrar" style="font-weight:bold; font-size:18px; text-decoration:none; color:#000000; display:none" title="Cerrar Ventana" href="#" onClick="document.getElementById('frmReporte').style.display='none'; document.getElementById('frmReporte').src=''; document.getElementById('aCerrar').style.display='none';">X</a>
            	<iframe src="" name="frmReporte" id="frmReporte" width="95%" height="90%" scrolling="auto" frameborder="1" style="border-color:#333333; display:none;"></iframe>
          	</td>
      </tr>
    </table>
	
	<script type="text/javascript">
    function nuevoAjax() { 
        var xmlhttp=false;
        try{
            xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch(e){
            try{
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch(E){
                if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
            }
        }
        return xmlhttp; 
    }    
    
    function llamarReporte(nombre) {
        var ajax=nuevoAjax();
        ajax.open("POST", "lib/generar_reporte.php?nombre="+nombre, true);	
        ajax.onreadystatechange=function() { 
            if(ajax.readyState == 1){
                document.getElementById("divCargando").style.display = "block";
                }
            if (ajax.readyState==4){
                document.getElementById("aCerrar").style.display = "block";	
                document.getElementById("frmReporte").style.display = "block";			
                frmReporte.location.href = "lib/generar_reporte.php?nombre="+nombre;
                document.getElementById("divCargando").style.display = "none";
            } 
        }
        ajax.send(null);
    }
    
    function llamarFiltro(nombre) {
        var ajax=nuevoAjax();
        ajax.open("GET", "lib/generar_filtro.php?nombre="+nombre, true);	
        ajax.onreadystatechange=function() { 
            if(ajax.readyState == 1){
                document.getElementById("divCargando").style.display = "block";
                }
            if (ajax.readyState==4){
                document.getElementById("aCerrar").style.display = "block";	
                document.getElementById("frmReporte").style.display = "block";
                frmReporte.location.href = "lib/generar_filtro.php?nombre="+nombre;
                document.getElementById("divCargando").style.display = "none";
            } 
        }
        ajax.send(null);
    }
        
    function nodeSelect_handle(sender,arg) {	
    	var treenode = treeview.getNode(arg.NodeId);
        var nombre = treenode.getData("nombre");	
        var tipo = treenode.getData("tipo");
            
        if (tipo=="directo") llamarReporte(nombre); 
        else if (tipo=="filtro") llamarFiltro(nombre);
    }
    treeview.registerEvent("OnSelect",nodeSelect_handle);
    </script>

	<br />
	<br />
	<br />
	<br />
			
	<!-- Links -->
	<div class="sectionHeader"></div>
	<div class="bottomspacing"></div>	
	
 	</form>
 </body>
</html>
*/
?>