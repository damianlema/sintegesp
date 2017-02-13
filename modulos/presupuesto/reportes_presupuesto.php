<?
session_start();
include("../../conf/conex.php");
conectarse();
?>
<table align="center">
  <tr>
    <td width="400" valign='top'>
    	<table width="225" style="border:1px solid; border-color:#999999;">
        	<label><b>Ejecuci&oacute;n Presupuestaria</b></label>
            
            
			<tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="#" onclick="document.getElementById('divPreOri').style.display='block'; document.getElementById('divPreRes').style.display='none'; document.getElementById('divPreCon').style.display='none'; document.getElementById('divPreSector').style.display='none';">Presupuesto Original</a>
            		<div id="divPreOri" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            		<table>
            			<tr>
                			<td>Mostrar: </td>
                			<td>
                            	<select name="mostrar1" id="mostrar1" style="width:225px;">
                                	<option value="0">[Todas]</option>
                                    <?
								    $sql=mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die ($sql.mysql_error());
                    				while($field=mysql_fetch_array($sql)) {
										echo "<option value='".$field[0]."'>".$field[1]." ".$field[2]."</option>";
									}
                                    ?>
                                </select>
                                <a href="#" onclick="document.getElementById('divPreOri').style.display='none'">X</a>
                            </td>
                        </tr>
                		<tr>
                			<td colspan="2">
                            	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="frmReporte.location.href='../../lib/generar_reporte.php?nombre=preori&idcategoria_programatica='+document.getElementById('mostrar1').value; document.getElementById('divPreOri').style.display='none';">
                            </td>
                		</tr>
            		</table>
            		</div>
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="#" onclick="document.getElementById('divPreRes').style.display='block'; document.getElementById('divPreOri').style.display='none'; document.getElementById('divPreCon').style.display='none'; document.getElementById('divPreSector').style.display='none';">Resumen por Categor&iacute;as</a>
            		<div id="divPreRes" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            		<table>
            			<tr>
                			<td>Mostrar: </td>
                			<td>
                            	<select name="mostrar2" id="mostrar2" style="width:225px;">
                                	<option value="0">[Todas]</option>
                                    <?
								    $sql=mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die ($sql.mysql_error());
                    				while($field=mysql_fetch_array($sql)) {
										echo "<option value='".$field[0]."'>".$field[1]." ".$field[2]."</option>";
									}
                                    ?>
                                </select>
                                <a href="#" onclick="document.getElementById('divPreRes').style.display='none'">X</a>
                            </td>
                        </tr>
                		<tr>
                			<td colspan="2">
                            	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="frmReporte.location.href='../../lib/generar_reporte.php?nombre=preres&idcategoria_programatica='+document.getElementById('mostrar2').value; document.getElementById('divPreRes').style.display='none';">
                            </td>
                		</tr>
            		</table>
            		</div>
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="#" onclick="document.getElementById('divPreCon').style.display='block'; document.getElementById('divPreOri').style.display='none'; document.getElementById('divPreRes').style.display='none'; document.getElementById('divPreSector').style.display='none';">Resumen Consolidado</a>
            		<div id="divPreCon" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            		<table>
            			<tr>
                			<td>Mostrar: </td>
                			<td>
                            	<select name="mostrar3" id="mostrar3" style="width:225px;">
                                	<option value="0">[Todas]</option>
                                    <?
								    $sql=mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die ($sql.mysql_error());
                    				while($field=mysql_fetch_array($sql)) {
										echo "<option value='".$field[0]."'>".$field[1]." ".$field[2]."</option>";
									}
                                    ?>
                                </select>
                                <a href="#" onclick="document.getElementById('divPreCon').style.display='none'">X</a>
                            </td>
                        </tr>
                		<tr>
                			<td colspan="2">
                            	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="frmReporte.location.href='../../lib/generar_reporte.php?nombre=consolidado&idcategoria_programatica='+document.getElementById('mostrar3').value; document.getElementById('divPreCon').style.display='none';">
                            </td>
                		</tr>
            		</table>
            		</div>
            	</td>
            </tr>
            
            <tr>
            	<td style="border-bottom:1px solid; border-color:#999999;">
            		<a href="#" onclick="document.getElementById('divPreSector').style.display='block'; document.getElementById('divPreOri').style.display='none'; document.getElementById('divPreRes').style.display='none'; document.getElementById('divPreCon').style.display='none';">Resumen Por Sector</a>
            		<div id="divPreSector" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            		<table>
            			<tr>
                			<td>Mostrar: </td>
                			<td>
                            	<select name="mostrar4" id="mostrar4" style="width:225px;">
                                	<option value="0">[Todas]</option>
                                    <?
								    $sql=mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die ($sql.mysql_error());
                    				while($field=mysql_fetch_array($sql)) {
										echo "<option value='".$field[0]."'>".$field[1]." ".$field[2]."</option>";
									}
                                    ?>
                                </select>
                                <a href="#" onclick="document.getElementById('divPreSector').style.display='none'">X</a>
                            </td>
                        </tr>
                		<tr>
                			<td colspan="2">
                            	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="frmReporte.location.href='../../lib/generar_reporte.php?nombre=porsector&idcategoria_programatica='+document.getElementById('mostrar4').value; document.getElementById('divPreSector').style.display='none';">
                            </td>
                		</tr>
            		</table>
            		</div>
            	</td>
            </tr>
            
        </table>
   	</td>
    <td>
    	<iframe src="../../lib/msj_reporte.php" name="frmReporte" id="frmReporte" width="700" height="575" style="border:1px solid; border-color:#999999;">
        </iframe>
    </td>
  </tr>
</table>