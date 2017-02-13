<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();


if($ejecutar == "ingresarGrupos"){
	$sql_ingresar_grupos = mysql_query("insert into grupo_catalogo_bienes(codigo,
																			denominacion,
																			status,
																			usuario,
																			fechayhora)values(
																				'".$codigo."',
																				'".$denominacion."',
																				'a',
																				'".$login."',
																				'".$fh."')");
																				
	if($sql_ingresar_grupos){
		echo "exito";
	}else{
		echo "fallo";
	}
}







if($ejecutar == "listarGrupos"){
$sql_consulta = mysql_query("select * from grupo_catalogo_bienes");
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
                                <td class='Browse' align="left"><?=$bus_consulta["denominacion"]?></td>
                                <td width="6%" align="center" class='Browse'>
                                	<img src="imagenes/modificar.png" 
                                    	onclick="mostrarEditar('<?=$bus_consulta["idgrupo_catalogo_bienes"]?>',
                                        						'<?=$bus_consulta["codigo"]?>',
                                        						'<?=$bus_consulta["denominacion"]?>')" 
                                        style="cursor:pointer">                                </td>
                          <td width="6%" align="center" class='Browse'>
                                	<img src="imagenes/delete.png" 
                                    style="cursor:pointer"
                                    onclick="mostrarEliminar('<?=$bus_consulta["idgrupo_catalogo_bienes"]?>',
                                        						'<?=$bus_consulta["codigo"]?>',
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



if($ejecutar == "modificarGrupos"){
	$sql_editar_grupo = mysql_query("update grupo_catalogo_bienes set codigo = '".$codigo."',
																	denominacion = '".$denominacion."'
																	where idgrupo_catalogo_bienes = '".$id_grupo."'");
	if($sql_editar_grupo){
		echo "exito";
	}else{
		echo "fallo";
	}
}




if($ejecutar == "eliminarGrupos"){
	$sql_eliminar_grupo = mysql_query("delete from grupo_catalogo_bienes where idgrupo_catalogo_bienes = '".$id_grupo."'");
	if($sql_eliminar_grupo){
		echo "exito";
	}else{
		echo "fallo";
	}
}
?>