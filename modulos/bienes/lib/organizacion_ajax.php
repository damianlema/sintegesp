<?
include("../../../conf/conex.php");
extract($_POST);
Conectarse();


if($ejecutar == "cambiarMunicipios"){
	?>
	<select id="municipio" name="municipio" style="cursor:pointer">
	<option value="0">.:: Seleccine el Municipio ::.</option>
	<?
	$sql_municipios = mysql_query("select * from municipios where idestado = '".$idestado."'");
	while($bus_municipios = mysql_fetch_array($sql_municipios)){
		?>
		<option value="<?=$bus_municipios["idmunicipios"]?>"><?=$bus_municipios["denominacion"]?></option>
		<?
	}
	?>
	</select>
	<?
}




if($ejecutar == "ingresarOrganizacion"){
	$sql_ingresar = mysql_query("insert into organizacion (codigo,
															denominacion,
															responsable,
															idestado,
															idmunicipio,
															direccion,
															telefonos,
															email,
															status,
															usuario,
															fechayhora)VALUES('".$codigo."',
																				'".$denominacion."',
																				'".$responsable."',
																				'".$estado."',
																				'".$municipio."',
																				'".$direccion."',
																				'".$telefonos."',
																				'".$email."',
																				'a',
																				'".$login."',
																				'".$fh."')");
}




if($ejecutar == "modificarOrganizacion"){
	$sql_actualizar = mysql_query("update organizacion set codigo = '".$codigo."',
															denominacion = '".$denominacion."',
															responsable = '".$responsable."',
															idestado = '".$estado."',
															idmunicipio = '".$municipio."',
															direccion = '".$direccion."',
															telefonos = '".$telefonos."',
															email = '".$email."'
															where idorganizacion = '".$idorganizacion."'")or die(mysql_error());
}



if($ejecutar == "eliminarOrganizacion"){
	$sql_eliminar = mysql_query("delete from organizacion where idorganizacion = '".$idorganizacion."'")or die(mysql_error());
}



if($ejecutar == "listarOrganizaciones"){
$sql_consulta = mysql_query("select organizacion.idorganizacion as idorganizacion,
										organizacion.codigo as codigo,
										organizacion.denominacion as denominacion,
										organizacion.responsable as responsable,
										organizacion.direccion as direccion,
										organizacion.telefonos as telefonos,
										organizacion.email,
										estado.idestado,
										municipios.idmunicipios as idmunicipios
										 from organizacion, estado, municipios
										 where 
										 organizacion.idestado = estado.idestado
										 and organizacion.idmunicipio = municipios.idmunicipios")or die(mysql_error());
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="70%" align="center">
    	<thead>
        <tr>
            <td width="39%" align="center" class="Browse">C&oacute;digo</td>
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
            <td width="1%" align="center" class='Browse'>
            	<img src="imagenes/modificar.png" 
                	style="cursor:pointer" 
                    onClick="seleccionarModificar('<?=$bus_consulta["idorganizacion"]?>','<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["responsable"]?>', '<?=$bus_consulta["idestado"]?>', '<?=$bus_consulta["idmunicipios"]?>', '<?=$bus_consulta["direccion"]?>', '<?=$bus_consulta["telefonos"]?>', '<?=$bus_consulta["email"]?>')">
            </td>
            <td width="1%" align="center" class='Browse'>
            	<img src="imagenes/delete.png" 
                	style="cursor:pointer" 
                    onClick="seleccionarEliminar('<?=$bus_consulta["idorganizacion"]?>','<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["responsable"]?>', '<?=$bus_consulta["idestado"]?>', '<?=$bus_consulta["idmunicipios"]?>', '<?=$bus_consulta["direccion"]?>', '<?=$bus_consulta["telefonos"]?>', '<?=$bus_consulta["email"]?>')">
            </td>
      </tr>
        <?
        }
		?>
        </table>
	<?
}
?>