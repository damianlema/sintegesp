<?
include("../../../conf/conex.php");
conectarse();

extract($_POST);


if($ejecutar == "ingresarMarca"){
	
	$sql_validar_existe = mysql_query("select * from marca_materia 
									  					where denominacion = '".$denominacion."'")or die("valida existe marca ".mysql_error());
	$num_valida_existe = mysql_num_rows($sql_validar_existe);
	if ($num_valida_existe == 0){
		if ($preseleccion == 1){
			$sql_cambia_seleccion = mysql_query("update marca_materia set seleccionada = '0'
															where seleccionada = '1'")or die("cambia preseleccion ".mysql_error());
		}
		$sql_ingresar = mysql_query("insert into marca_materia (denominacion, 
														  seleccionada,
														usuario, 
														fechayhora, 
														status)VALUES('".$denominacion."',
																	  '".$preseleccion."',
																		'".$login."',
																		'".$fh."',
																			'a')")or die(mysql_error());
		echo "exito";
		
	}else{
		echo "existe";	
	}
}


if($ejecutar == "listarMarca"){
		$sql_consulta = mysql_query("select * from marca_materia")or die(mysql_error());
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="30%" align="center">
   	  <thead>
       <tr>
          <td width="80%" align="center" class="Browse">Denominaci&oacute;n</td>
          <td width="10%" align="center" class="Browse">Presel.</td>
          <td align="center" class="Browse" colspan="2">Acciones</td>
        </tr>
        </thead>
        <?
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
          	<td class='Browse'><?=$bus_consulta["denominacion"]?></td>
            <td class='Browse' align="center"><?php if ($bus_consulta["seleccionada"] == '1'){  echo "<img src='imagenes/accept.gif'>";} else {echo "&nbsp;";} ?></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarModificar('<?=$bus_consulta["idmarca_materia"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["seleccionada"]?>')"></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminar('<?=$bus_consulta["idmarca_materia"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["seleccionada"]?>')"></td>
      </tr>
        <?
        }
		?>
        </table>
<?
}


if($ejecutar == "modificarMarca"){
	if ($preseleccion == 1){
			$sql_cambia_seleccion = mysql_query("update marca_materia set seleccionada = '0'
															where seleccionada = '1'")or die("cambia preseleccion ".mysql_error());
	}
	$sql_modificar = mysql_query("update marca_materia set denominacion = '".$denominacion."',
								 							 seleccionada = '".$preseleccion."'
															where idmarca_materia = '".$idmarca."'")or die(mysql_error());
}



if($ejecutar == "eliminarMarca"){
	
		$sql_modificar = mysql_query("delete from marca_materia where idmarca_materia = '".$idmarca."'");
	
	
	
}




?>