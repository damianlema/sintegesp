<?
session_start();

include("../../conf/conex.php");
Conectarse();

extract($_POST);


if($ejecutar == "listarEntes"){
?>
  <table class="Main" cellpadding="0" cellspacing="0" width="50%" align="center">
				<tr>
					<td>
						<form name="grilla" action="lista_cargos.php" method="POST">
		  <table class="Browse" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<!--<td class="Browse">&nbsp;</td>-->
									<td width="81%" align="center" class="Browse">Nombre</td>
								  <td align="center" class="Browse" colspan="2">Acciones</td>
								</tr>
							</thead>
							
							<? 
								$sql_consultar = mysql_query("select * from entes_gubernamentales order by nombre ASC");
								while($bus_consultar = mysql_fetch_array($sql_consultar)){
								?>
								<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
									<td align='left' class='Browse'><?=$bus_consultar["nombre"]?></td>
                                    <td width="11%" align='center' class='Browse'>
                                    	<img src="imagenes/modificar.png" style="cursor:pointer" onClick="mostrarModificar('<?=$bus_consultar["nombre"]?>', '<?=$bus_consultar["director"]?>','<?=$bus_consultar["cargod"]?>','<?=$bus_consultar["administrador"]?>','<?=$bus_consultar["cargoa"]?>', '<?=$bus_consultar["identes_gubernamentales"]?>')">
                                    </td>
                                  	<td width="8%" align='center' class='Browse'>
                                  		<img src="imagenes/delete.png" style="cursor:pointer" onClick="mostrarEliminar('<?=$bus_consultar["nombre"]?>', '<?=$bus_consultar["director"]?>','<?=$bus_consultar["cargod"]?>','<?=$bus_consultar["administrador"]?>','<?=$bus_consultar["cargoa"]?>', '<?=$bus_consultar["identes_gubernamentales"]?>')">
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


if($ejecutar == "ingresarEnte"){
	$sql_ingresar = mysql_query("insert into entes_gubernamentales(nombre,
																	director,
																	cargod,
																	administrador,
																	cargoa)
																	VALUES
																  ('".$nombre."',
																  '".$director."',
																  '".$cargod."',
																  '".$administrador."',
																  '".$cargoa."')")or die(mysql_error());
}


if($ejecutar == "modificarEnte"){
	$sql_modificar = mysql_query("update entes_gubernamentales set nombre = '".$nombre."',
																	director = '".$director."',
																	cargod = '".$cargod."',
																	administrador = '".$administrador."',
																	cargoa = '".$cargoa."' 
																	where identes_gubernamentales = '".$id_ente."'");
}

if($ejecutar == "eliminarEnte"){
	$sql_consultar = mysql_query("select * from retenciones where idente_gubernamental = '".$id_ente."'");
	$num_consultar = mysql_num_rows($sql_consultar);
	
	if($num_consultar > 0){
		echo "existe";
	}else{
		$sql_eliminar = mysql_query("delete from entes_gubernamentales where identes_gubernamentales = '".$id_ente."'");
	}
}
?>