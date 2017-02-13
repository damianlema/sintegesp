<?
include("../../../conf/conex.php");
conectarse();

extract($_POST);


if($ejecutar == "ingresarTipo"){
	$sql_ingresar = mysql_query("insert into tipo_detalle (iddetalle, 
															tipo, 
															usuario, 
															fechayhora, 
															status)VALUES('".$iddetalle."',
																			'".$tipo."',
																			'".$login."',
																			'".$fh."',
																			'a')");
}


if($ejecutar == "listarTipo"){
		$sql_consulta = mysql_query("select detalle_catalogo_bienes.codigo,
										detalle_catalogo_bienes.denominacion,
										tipo_detalle.tipo,
										tipo_detalle.idtipo_detalle,
										tipo_detalle.iddetalle 
											from 
										detalle_catalogo_bienes,
										tipo_detalle
											where
										detalle_catalogo_bienes.iddetalle_catalogo_bienes = tipo_detalle.iddetalle")or die(mysql_error());
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="70%" align="center">
    	<thead>
        <tr>
            <td width="39%" align="center" class="Browse">Detalle</td>
          <td width="50%" align="center" class="Browse">Tipo de Detalle</td>
          <td align="center" class="Browse" colspan="2">Acciones</td>
        </tr>
        </thead>
        <?
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class='Browse'>(<?=$bus_consulta["codigo"]?>)&nbsp;<?=$bus_consulta["denominacion"]?></td>
          	<td class='Browse'><?=$bus_consulta["tipo"]?></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarModificar('<?=$bus_consulta["idtipo_detalle"]?>', '<?=$bus_consulta["iddetalle"]?>', '<?=$bus_consulta["tipo"]?>')"></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminar('<?=$bus_consulta["idtipo_detalle"]?>', '<?=$bus_consulta["iddetalle"]?>', '<?=$bus_consulta["tipo"]?>')"></td>
      </tr>
        <?
        }
		?>
        </table>
	<?
}


if($ejecutar == "modificarTipo"){
	$sql_modificar = mysql_query("update tipo_detalle set iddetalle = '".$iddetalle."',
															tipo = '".$tipo."'
															where idtipo_detalle = '".$idtipo_detalle."'")or die(mysql_error());
	
}



if($ejecutar == "eliminarTipo"){
	$sql_modificar = mysql_query("delete from tipo_detalle where idtipo_detalle = '".$idtipo_detalle."'");
}




?>