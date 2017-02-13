<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();


if($ejecutar == "ingresarSecciones"){
	
	$sql_existe = mysql_query("select * from seccion_materias_almacen where
							  				idsubgrupo_materias_almacen = '".$subgrupo."' and 
											codigo = '".$codigo."'");
	
	$bus_existe = mysql_num_rows($sql_existe);
	
	if ($bus_existe == 0){
	
		$sql_ingresar_secciones = mysql_query("insert into seccion_materias_almacen(idsubgrupo_materias_almacen,
																			codigo,
																			denominacion,
																			status,
																			usuario,
																			fechayhora)values(
																				'".$subgrupo."',
																				'".$codigo."',
																				'".$denominacion."',
																				'a',
																				'".$login."',
																				'".$fh."')")or die(mysql_error());
																				
		if($sql_ingresar_secciones){
			echo "exito";
		}else{
			echo "fallo";
		}
	}else{
		echo "existe";
	}
}







if($ejecutar == "consultarSecciones"){
$sql = "select * from seccion_materias_almacen ";
if($campo_buscar != ""){
	$sql .= "where denominacion like '%".$campo_buscar."%'";
}
echo $campo_buscar;
$sql_consulta = mysql_query($sql);
?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
	  				  <thead>
								<tr>
									<td width="13%" align="center" class="Browse">C&oacute;digo</td>
								  <td width="75%" align="center" class="Browse">Denominaci&oacute;n</td>
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
								$codigoHidden = $partes_codigo[0]."-".$partes_codigo[1];
								$codigo = $partes_codigo[2];
								?>
                                
                                	<img src="imagenes/modificar.png" 
                                    	onclick="mostrarEditar('<?=$bus_consulta["idseccion_materias_almacen"]?>',
                                        						'<?=$bus_consulta["idsubgrupo_materias_almacen"]?>',
                                        						'<?=$codigoHidden?>',
                                                                '<?=$codigo?>',
                                        						'<?=$bus_consulta["denominacion"]?>')" 
                                        style="cursor:pointer">                                </td>
                                <td width="6%" align="center" class='Browse'>
                                	<img src="imagenes/delete.png" 
                                    style="cursor:pointer"
                                    onclick="mostrarEliminar('<?=$bus_consulta["idseccion_materias_almacen"]?>',
                                    							'<?=$bus_consulta["idsubgrupo_materias_almacen"]?>',
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



if($ejecutar == "modificarSecciones"){
	$sql_editar_subgrupo = mysql_query("update seccion_materias_almacen set 
																	idsubgrupo_materias_almacen = '".$subgrupo."',
																	codigo = '".$codigo."',
																	denominacion = '".$denominacion."'
																	where idseccion_materias_almacen = '".$id_secciones."'")or die(mysql_error());
	if($sql_editar_subgrupo){
		echo "exito";
	}else{
		echo "fallo";
	}
}




if($ejecutar == "eliminarSecciones"){
	$sql_eliminar_subgrupo = mysql_query("delete from seccion_materias_almacen where idseccion_materias_almacen = '".$id_secciones."'");
	if($sql_eliminar_subgrupo){
		echo "exito";
	}else{
		echo "fallo";
	}
}
?>