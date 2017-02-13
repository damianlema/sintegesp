<?
include("../../../conf/conex.php");
conectarse();

extract($_POST);


if($ejecutar == "ingresarUbicacion"){
	$sql_ingresar = mysql_query("insert into ubicacion (codigo, 
															denominacion, 
															usuario, 
															fechayhora, 
															status)VALUES('".$codigo."',
																			'".$denominacion."',
																			'".$login."',
																			'".$fh."',
																			'a')")or die(mysql_error());
}


if($ejecutar == "listarUbicacion"){
		$sql_consulta = mysql_query("select * from ubicacion")or die(mysql_error());
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
   	  <thead>
        <tr>
            <td width="10%" align="center" class="Browse">C&oacute;digo</td>
          <td width="50%" align="center" class="Browse">Denominaci&oacute;n</td>
          <td align="center" class="Browse" colspan="2">Acciones</td>
        </tr>
        </thead>
        <?
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class='Browse'><?=$bus_consulta["codigo"]?></td>
          	<td class='Browse'><?=$bus_consulta["denominacion"]?></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarModificar('<?=$bus_consulta["idubicacion"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["denominacion"]?>')"></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminar('<?=$bus_consulta["idubicacion"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["denominacion"]?>')"></td>
      </tr>
        <?
        }
		?>
        </table>
<?
}


if($ejecutar == "modificarUbicacion"){
	$sql_modificar = mysql_query("update ubicacion set codigo = '".$codigo."',
															denominacion = '".$denominacion."'
															where idubicacion = '".$idubicacion."'");
}



if($ejecutar == "eliminarUbicacion"){
	$sql_modificar = mysql_query("delete from ubicacion where idubicacion = '".$idubicacion."'");
}




?>