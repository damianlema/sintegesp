<?
include("../../../conf/conex.php");
conectarse();

extract($_POST);


if($ejecutar == "ingresarAlmacen"){
	
	$sql_validar_existe = mysql_query("select * from almacen 
									  					where codigo = '".$codigo."'
														and denominacion = '".$denominacion."'")or die("valida existe almacen ".mysql_error());
	$num_valida_existe = mysql_num_rows($sql_validar_existe);
	if ($num_valida_existe == 0){
		if ($preseleccion == 1){
			$sql_cambia_seleccion = mysql_query("update almacen set defecto = '0'
															where defecto = '1'")or die("cambia preseleccion ".mysql_error());
		}
		$sql_ingresar = mysql_query("insert into almacen (codigo,
														  denominacion, 
														  defecto,
														  responsable,
														  ci_responsable,
														  telefono,
														  email,
														  usuario, 
														  fechayhora, 
														  status)VALUES('".$codigo."',
														  				'".$denominacion."',
																	  	'".$preseleccion."',
																		'".$responsable."',
																		'".$ci_responsable."',
																		'".$telefono."',
																		'".$email."',
																		'".$login."',
																		'".$fh."',
																			'a')")or die(mysql_error());
		echo "exito";
		
	}else{
		echo "existe";	
	}
}


if($ejecutar == "listarAlmacen"){
		$sql_consulta = mysql_query("select * from almacen")or die(mysql_error());
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
   	  <thead>
       <tr>
            <td width="10%" align="center" class="Browse">C&oacute;digo</td>	
          <td width="70%" align="center" class="Browse">Denominaci&oacute;n</td>
          <td width="10%" align="center" class="Browse">Presel.</td>
          <td align="center" class="Browse" colspan="2">Acciones</td>&nbsp;
        </tr>
        </thead>
        <?
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class='Browse'><?=$bus_consulta["codigo"]?></td>
          	<td class='Browse'><?=$bus_consulta["denominacion"]?></td>
            <td class='Browse' align="center"><?php if ($bus_consulta["defecto"] == '1'){  echo "<img src='imagenes/accept.gif'>";} else {echo "&nbsp;";} ?></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarModificar('<?=$bus_consulta["idalmacen"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["defecto"]?>', '<?=$bus_consulta["responsable"]?>', '<?=$bus_consulta["ci_responsable"]?>', '<?=$bus_consulta["telefono"]?>', '<?=$bus_consulta["email"]?>', '<?=$bus_consulta["ubicacion"]?>')"></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminar('<?=$bus_consulta["idalmacen"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["defecto"]?>', '<?=$bus_consulta["responsable"]?>', '<?=$bus_consulta["ci_responsable"]?>', '<?=$bus_consulta["telefono"]?>', '<?=$bus_consulta["email"]?>', '<?=$bus_consulta["ubicacion"]?>')"></td>
      </tr>
        <?
        }
		?>
        </table>
<?
}


if($ejecutar == "modificarAlmacen"){
	if ($preseleccion == 1){
			$sql_cambia_seleccion = mysql_query("update almacen set defecto = '0'
															where defecto = '1'")or die("cambia preseleccion ".mysql_error());
	}
	$sql_modificar = mysql_query("update almacen set 		denominacion = '".$denominacion."',
															ubicacion = '".$ubicacion."',
								 							defecto = '".$preseleccion."',
															responsable = '".$responsable."',
															ci_responsable = '".$ci_responsable."',
															telefono = '".$telefono."',
															email = '".$email."'
															where idalmacen = '".$idalmacen."'")or die(mysql_error());
}



if($ejecutar == "eliminarAlmacen"){
	$sql_validar = mysql_query("select * from distribucion_almacen where idalmacen = '".$idalmacen."'");
	$sql_existe = mysql_num_rows($sql_validar);
	if ($sql_existe == 0){
		$sql_modificar = mysql_query("delete from almacen where idalmacen = '".$idalmacen."'");
		echo "exito";
	}else{
		echo "existe";	
	}
	
	
	
}




?>