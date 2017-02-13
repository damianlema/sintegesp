<?
session_start();

include("../../conf/conex.php");
Conectarse();

extract($_POST);


if($ejecutar == "listarDesagregados"){
?>
  <table class="Main" cellpadding="0" cellspacing="0" width="40%" align="center">
				<tr>
					<td>
						<form name="grilla" action="lista_cargos.php" method="POST">
		 				 <table class="Browse" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<!--<td class="Browse">&nbsp;</td>-->
									<td width="81%" align="center" class="Browse">Unidad Desagregadora</td>
								  <td align="center" class="Browse" >Acci&oacute;n</td>
								</tr>
							</thead>
							
							<? 
								$sql_consultar = mysql_query("select * from desagrega_unidad_medida, unidad_medida 
															 						where 
																					desagrega_unidad_medida.idunidad_medida = unidad_medida.idunidad_medida
																					and
																					desagrega_unidad_medida.idunidad_medida = '".$idunidad_medida."'
																					order by descripcion ASC");
								while($bus_consultar = mysql_fetch_array($sql_consultar)){
									
									$sql_descripcion = mysql_query("select * from unidad_medida 
															 						where 
																					idunidad_medida = '".$bus_consultar["idunidad_medida_desagregada"]."'");
									$bus_descripcion = mysql_fetch_array($sql_descripcion);
								?>
								<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
									<td align='left' class='Browse'><?=$bus_descripcion["descripcion"]?></td>
                                    <td width="8%" align='center' class='Browse'>
                                  		<img src="imagenes/delete.png" style="cursor:pointer" onClick="eliminarDesagregado()">
                                  </td>
                          </tr>
								<?
								}
							?>
						</table>
						</form>
					</td>
				</tr>
  </table>
<?	
}


if($ejecutar == "actualizarSelectDesagregar"){ 
	
	$sql_unidades = mysql_query("Select unidad_medida.idunidad_medida,
														unidad_medida.descripcion,
														unidad_medida.abreviado
													from unidad_medida 
														where not exists 
														(select * from desagrega_unidad_medida 
															 where 
														desagrega_unidad_medida.idunidad_medida_desagregada = unidad_medida.idunidad_medida
														and desagrega_unidad_medida.idunidad_medida = '".$idunidad_medida."')");
?>
  		<select name="unidades_medida" id="unidades_medida">
			<? while($bus_select = mysql_fetch_array($sql_unidades)){
                echo "<option value='".$bus_select['idunidad_medida']."'>(".$bus_select['abreviado'].") ".$bus_select['descripcion']."</option>";
                }
            ?>
        </select>
<?	
}





if($ejecutar == "asociarUnidadDesagrega"){
	echo $idunidad_medida;
	echo $idunidad_desagrega;
	$sql_validar = mysql_query("select * from desagrega_unidad_medida
							   				where
											idunidad_medida = '".$idunidad_medida."'
											and idunidad_medida_desagregada= '".$idunidad_desagrega."'");
	$bus_validar = mysql_num_rows($sql_validar);
	if($bus_validar > 0){
		echo "existe";
	}else{
		$sql_ingresar = mysql_query("insert into desagrega_unidad_medida(idunidad_medida, idunidad_medida_desagregada)
																	VALUES
																  ('".$idunidad_medida."', '".$idunidad_desagrega."')")or die(mysql_error());
	}
}



if($ejecutar == "eliminarUnidadDesagregada"){
	
	$sql_eliminar = mysql_query("delete from desagrega_unidad_medida where iddesagrega_unidad_medida = '".$iddesagrega_unidad_medida."'");
	
}
?>