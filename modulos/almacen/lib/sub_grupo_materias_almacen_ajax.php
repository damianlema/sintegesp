<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();


if($ejecutar == "ingresarSubGrupos"){
	
	$sql = mysql_query("select * from subgrupo_materias_almacen where
					idgrupo_materias_almacen = '".$grupo."' and
					codigo = '".$codigo."'");
	$bus_existe = mysql_num_rows($sql);
	if ($bus_existe == 0){
	$sql_ingresar_subgrupos = mysql_query("insert into subgrupo_materias_almacen(idgrupo_materias_almacen,
																			codigo,
																			denominacion,
																			status,
																			usuario,
																			fechayhora)values(
																				'".$grupo."',
																				'".$codigo."',
																				'".$denominacion."',
																				'a',
																				'".$login."',
																				'".$fh."')")or die(mysql_error());
																				
		if($sql_ingresar_subgrupos){
			echo "exito";
		}else{
			echo "fallo";
		}
	}else{
		echo "existe";	
	}
	
}







if($ejecutar == "consultarSubGrupos"){
$sql = "select * from subgrupo_materias_almacen ";
if($campo_buscar != ""){
	$sql.= "where denominacion like '%".$campo_buscar."%'";
}
//echo "PRUEBA: ".$campo_buscar;
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
                                <td class='Browse' align="left"><?=$bus_consulta["denominacion"]?></td>
                                <td width="6%" align="center" class='Browse'>
                                <?
                                $partes_codigo = explode("-", $bus_consulta["codigo"]);
								$codigoHidden = $partes_codigo[0];
								$codigo = $partes_codigo[1];
								?>
                                
                                	<img src="imagenes/modificar.png" 
                                    	onclick="mostrarEditar('<?=$bus_consulta["idsubgrupo_materias_almacen"]?>',
                                        						'<?=$bus_consulta["idgrupo_materias_almacen"]?>',
                                        						'<?=$codigoHidden?>',
                                                                '<?=$codigo?>',
                                        						'<?=$bus_consulta["denominacion"]?>')" 
                                        style="cursor:pointer">                                </td>
                                <td width="6%" align="center" class='Browse'>
                                	<img src="imagenes/delete.png" 
                                    style="cursor:pointer"
                                    onclick="mostrarEliminar('<?=$bus_consulta["idsubgrupo_materias_almacen"]?>',
                                    							'<?=$bus_consulta["idgrupo_materias_almacen"]?>',
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



if($ejecutar == "modificarSubGrupos"){
	$sql_editar_subgrupo = mysql_query("update subgrupo_materias_almacen set 
																	idgrupo_materias_almacen = '".$grupo."',
																	codigo = '".$codigo."',
																	denominacion = '".$denominacion."'
																	where idsubgrupo_materias_almacen = '".$id_subgrupo."'")or die(mysql_error());
	if($sql_editar_subgrupo){
		echo "exito";
	}else{
		echo "fallo";
	}
}




if($ejecutar == "eliminarSubGrupos"){
	$sql_eliminar_subgrupo = mysql_query("delete from subgrupo_materias_almacen where idsubgrupo_materias_almacen = '".$id_subgrupo."'");
	if($sql_eliminar_subgrupo){
		echo "exito";
	}else{
		echo "fallo";
	}
}
?>