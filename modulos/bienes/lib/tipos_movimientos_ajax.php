<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();


if($ejecutar == "ingresarTiposMovimientos"){
	$sql_ingresar_grupos = mysql_query("insert into tipo_movimiento_bienes(codigo,
																			denominacion,
																			afecta,
																			status,
																			usuario,
																			fechayhora,
																			tipo_mueble,
																			tipo_inmueble,
																			origen_bien,
																			estado_bien,
																			describir_motivo,
																			memoria_fotografica,
																			cambia_ubicacion,
																			uso,
																			formato)values(
																				'".$codigo."',
																				'".$denominacion."',
																				'".$afecta."',
																				'a',
																				'".$login."',
																				'".$fh."',
																				'".$tipo_mueble."',
																				'".$tipo_inmueble."',
																				'".$origen_bien."',
																				'".$estado_bien."',
																				'".$describir_motivo."',
																				'".$memoria_fotografica."',
																				'".$cambia_ubicacion."',
																				'".$momento_afectado."',
																				'".$formato."')");
																				
	if($sql_ingresar_grupos){
		echo "exito";
	}else{
		echo "fallo";
	}
}







if($ejecutar == "consultarTiposMovimientos"){
$sql_consulta = mysql_query("select * from tipo_movimiento_bienes order by codigo");
?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
<thead>
								<tr>
									<td width="13%" align="center" class="Browse">C&oacute;digo</td>
								  <td width="54%" align="center" class="Browse">Denominaci&oacute;n</td>
                                  <td width="20%" align="center" class="Browse">Afecta</td>
                                  <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                <td class='Browse'><?=$bus_consulta["codigo"]?></td>
                                <td class='Browse' align="left"><?=$bus_consulta["denominacion"]?></td>
                  <td class='Browse' align="center">
									<?
                                    if($bus_consulta["afecta"] == 1){
                                        echo "Incorporacion";
                                    }else if($bus_consulta["afecta"] == 2){
                                        echo "Desincorporacion";
                                    }
                                    ?>
                                </td>
                                <td width="7%" align="center" class='Browse'>
                                	<img src="imagenes/modificar.png" 
                                    	onclick="mostrarEditar('<?=$bus_consulta["idtipo_movimiento_bienes"]?>',
                                        						'<?=$bus_consulta["codigo"]?>',
                                        						'<?=$bus_consulta["denominacion"]?>',
                                                                '<?=$bus_consulta["afecta"]?>',
                                                                '<?=$bus_consulta["tipo_mueble"]?>', 
                                                                '<?=$bus_consulta["tipo_inmueble"]?>',
                                                                '<?=$bus_consulta["origen_bien"]?>',
                                                                '<?=$bus_consulta["estado_bien"]?>',
                                                                '<?=$bus_consulta["describir_motivo"]?>', 
                                                                '<?=$bus_consulta["memoria_fotografica"]?>',
                                                                '<?=$bus_consulta["cambia_ubicacion"]?>',
                                                                '<?=$bus_consulta["uso"]?>',
                                                                '<?=$bus_consulta["formato"]?>')" 
                                        style="cursor:pointer">                                </td>
                          <td width="6%" align="center" class='Browse'>
                                	<img src="imagenes/delete.png" 
                                    style="cursor:pointer"
                                    onclick="mostrarEliminar('<?=$bus_consulta["idtipo_movimiento_bienes"]?>',
                                        						'<?=$bus_consulta["codigo"]?>',
                                        						'<?=$bus_consulta["denominacion"]?>',
                                                                '<?=$bus_consulta["afecta"]?>',
                                                                '<?=$bus_consulta["tipo_mueble"]?>', 
                                                                '<?=$bus_consulta["tipo_inmueble"]?>',
                                                                '<?=$bus_consulta["origen_bien"]?>',
                                                                '<?=$bus_consulta["estado_bien"]?>',
                                                                '<?=$bus_consulta["describir_motivo"]?>', 
                                                                '<?=$bus_consulta["memoria_fotografica"]?>',
                                                                '<?=$bus_consulta["cambia_ubicacion"]?>',
                                                                '<?=$bus_consulta["uso"]?>',
                                                                '<?=$bus_consulta["formato"]?>')">                                </td>
                          </tr>
							<?
							}
							?>
							
								
						</table>
                        
					
      </td>
        </tr>
    </table>
<?
}



if($ejecutar == "modificarTiposMovimientos"){
	$sql_editar_grupo = mysql_query("update tipo_movimiento_bienes set codigo = '".$codigo."',
																	denominacion = '".$denominacion."',
																	afecta = '".$afecta."',
																	tipo_mueble = '".$tipo_mueble."',
																	tipo_inmueble = '".$tipo_inmueble."',
																	origen_bien = '".$origen_bien."',
																	estado_bien = '".$estado_bien."',
																	describir_motivo = '".$describir_motivo."',
																	memoria_fotografica = '".$memoria_fotografica."',
																	cambia_ubicacion = '".$cambia_ubicacion."',
																	uso = '".$momento_afectado."',
																	formato = '".$formato."'
																	where idtipo_movimiento_bienes = '".$idtipos_movimientos."'")or die(mysql_error());
	if($sql_editar_grupo){
		echo "exito";
	}else{
		echo "fallo";
	}
}




if($ejecutar == "eliminarTiposMovimientos"){
	$sql_eliminar_grupo = mysql_query("delete from tipo_movimiento_bienes where idtipo_movimiento_bienes = '".$idtipos_movimientos."'");
	if($sql_eliminar_grupo){
		echo "exito";
	}else{
		echo "fallo";
	}
}
?>