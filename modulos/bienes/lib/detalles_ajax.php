<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();


if($ejecutar == "ingresarDetalle"){
	$sql_ingresar_detalles = mysql_query("insert into detalle_catalogo_bienes(idsecciones_catalogo_bienes,
																			codigo,
																			denominacion,
																			status,
																			usuario,
																			fechayhora)values(
																				'".$secciones."',
																				'".$codigo."',
																				'".$denominacion."',
																				'a',
																				'".$login."',
																				'".$fh."')")or die(mysql_error());
																				
	if($sql_ingresar_detalles){
		echo "El Detalle se Ingreso con Exito";
	}else{
		echo "Disculpe el Detalle no se pudo ingresar con exito, por favor intente mas tarde";
	}
}







if($ejecutar == "consultarDetalles"){
$sql = "select * from detalle_catalogo_bienes ";
if($campo_buscar != ""){
	$sql.= "where denominacion like '%".$campo_buscar."%'";
}
$sql_consulta = mysql_query($sql);
?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
			  				<thead>
								<tr>
									<td width="12%" align="center" class="Browse">C&oacute;digo</td>
								  <td width="76%" align="center" class="Browse">Denominaci&oacute;n</td>
                                    <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                <td class='Browse'><?=$bus_consulta["codigo"]?></td>
                                <td class='Browse' align="left"><?=utf8_decode($bus_consulta["denominacion"])?></td>
                              <td width="6%" align="center" class='Browse'>
                                <?
                                $partes_codigo = explode("-", $bus_consulta["codigo"]);
								$codigoHidden = $partes_codigo[0]."-".$partes_codigo[1]."-".$partes_codigo[2];
								$codigo = $partes_codigo[3];
								?>
                                
                                	<img src="imagenes/modificar.png" 
                                    	onclick="mostrarEditar('<?=$bus_consulta["iddetalle_catalogo_bienes"]?>',
                                        						'<?=$bus_consulta["idsecciones_catalogo_bienes"]?>',
                                        						'<?=$codigoHidden?>',
                                                                '<?=$codigo?>',
                                        						'<?=$bus_consulta["denominacion"]?>')" 
                                        style="cursor:pointer">                                </td>
                                <td width="6%" align="center" class='Browse'>
                                	<img src="imagenes/delete.png" 
                                    style="cursor:pointer"
                                    onclick="mostrarEliminar('<?=$bus_consulta["iddetalle_catalogo_bienes"]?>',
                                    							'<?=$bus_consulta["idsecciones_catalogo_bienes"]?>',
                                        						'<?=$codigoHidden?>',
                                                                '<?=$codigo?>',
                                        						'<?=$bus_consulta["denominacion"]?>')">                                </td>
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



if($ejecutar == "modificarDetalle"){
	$sql_editar_subgrupo = mysql_query("update detalle_catalogo_bienes set 
																	idsecciones_catalogo_bienes = '".$secciones."',
																	codigo = '".$codigo."',
																	denominacion = '".$denominacion."'
																	where iddetalle_catalogo_bienes = '".$id_detalles."'")or die(mysql_error());
	if($sql_editar_subgrupo){
		echo "El Detalle se Edito con Exito";
	}else{
		echo "Disculpe el Detalle no se pudo editar, por favor intente mas tarde";
	}
}




if($ejecutar == "eliminarDetalles"){
	$sql_eliminar_subgrupo = mysql_query("delete from detalle_catalogo_bienes where iddetalle_catalogo_bienes = '".$id_detalles."'");
	if($sql_eliminar_subgrupo){
		echo "El Detalle se Elimino con Exito";
	}else{
		echo "Disculpe el Detalle no se pudo eliminar, por favbor intente de nuevo mas tarde";
	}
}
?>