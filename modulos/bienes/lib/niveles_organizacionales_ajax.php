<?
session_start();
include("../../../conf/conex.php");
extract($_POST);
Conectarse();


if($ejecutar == "cargarSubNiveles"){
	?>
    &nbsp;
    <select name="sub_nivel" id="sub_nivel" onChange="cargaCodigoSubNivel(this.value)">
	    <option value="0">NINGUNO</option>
		<?
	    $sql_sub_nivel = mysql_query("select * from niveles_organizacionales where modulo = '".$_SESSION["modulo"]."' order by codigo");
		while($bus_sub_nivel = mysql_fetch_array($sql_sub_nivel)){
			?>
			<option value="<?=$bus_sub_nivel["idniveles_organizacionales"].".|.".$bus_sub_nivel["codigo"]?>">
					(<?=$bus_sub_nivel["codigo"]?>) <?=$bus_sub_nivel["denominacion"]?>
			</option>
			<?
		}
		?>
    </select>
	<?
}



if($ejecutar == "ingresarNivelesOrganizacionales"){
if($sub_nivel != 0){
	$sql_sub = mysql_query("select * from niveles_organizacionales where idniveles_organizacionales = '".$sub_nivel."'");
	$bus_sub = mysql_fetch_array($sql_sub);
	$codigo = $bus_sub["codigo"]."-".$codigo;
}
	$sql_consulta = mysql_query("select * from niveles_organizacionales where organizacion = '".$organizacion."'
																		and codigo = '".$codigo."'
																		and sub_nivel = '".$sub_nivel."'
																		and modulo = '".$_SESSION["modulo"]."'");
	$num_consulta = mysql_num_rows($sql_consulta);
	if($num_consulta > 0){
		echo "existe";
	}else{
		
		$sql_ingresar = mysql_query("insert into niveles_organizacionales (organizacion,
																sub_nivel,
																codigo,
																denominacion,
																responsable,
																ci_responsable,
																telefono,
																email,
																status,
																usuario,
																fechayhora,
																modulo)VALUES('".$organizacion."',
																					'".$sub_nivel."',
																					'".$codigo."',
																					'".$denominacion."',
																					'".$responsable."',
																					'".$ci_responsable."',
																					'".$telefono."',
																					'".$email."',
																					'a',
																					'".$login."',
																					'".$fh."',
																					'".$_SESSION["modulo"]."')")or die(mysql_error());
	}
}




if($ejecutar == "modificarNivelesOrganizacionales"){
	$sql_actualizar = mysql_query("update niveles_organizacionales set organizacion = '".$organizacion."',
															sub_nivel = '".$sub_nivel."',
															denominacion = '".$denominacion."',
															responsable = '".$responsable."',
															ci_responsable = '".$ci_responsable."',
															telefono = '".$telefono."',
															email = '".$email."'
															where idniveles_organizacionales = '".$idniveles_organizacionales."'")or die(mysql_error());
}



if($ejecutar == "eliminarNivalesOrganizacionales"){
	$sql_validar = mysql_query("select * from niveles_organizacionales where sub_nivel = '".$idniveles_organizacionales."'");
	$sql_existe = mysql_num_rows($sql_validar);
	if ($sql_existe == 0){
		$sql_eliminar = mysql_query("delete from niveles_organizacionales where idniveles_organizacionales = '".$idniveles_organizacionales."'")or die(mysql_error());
		}else{
			echo "existe";
		}
}



if($ejecutar == "listarNivelesOrganizacionales"){
$sql_consulta = mysql_query("select niveles_organizacionales.organizacion,
									niveles_organizacionales.codigo,
									niveles_organizacionales.denominacion,
									niveles_organizacionales.responsable,
									niveles_organizacionales.ci_responsable,
									niveles_organizacionales.sub_nivel,
									niveles_organizacionales.telefono,
									niveles_organizacionales.email,
									niveles_organizacionales.idniveles_organizacionales,
									organizacion.denominacion as denominacion_organizacion,
									organizacion.codigo as codigo_organizacion 
										from 
									niveles_organizacionales, organizacion
										where 
									organizacion.idorganizacion = niveles_organizacionales.organizacion
									and niveles_organizacionales.modulo = '".$_SESSION["modulo"]."'
										order by niveles_organizacionales.codigo")or die(mysql_error());
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="70%" align="center">
   	  <thead>
        <tr>
          <td width="39%" align="center" class="Browse">Organizaci&oacute;n</td>
          <td width="15%" align="center" class="Browse">C&oacute;digo</td>
          <td width="50%" align="center" class="Browse">Denominaci&oacute;n</td>
          <td align="center" class="Browse" colspan="2">Acciones</td>
        </tr>
        </thead>
        <?
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class='Browse'><?=$bus_consulta["denominacion_organizacion"]?></td>
            <td class='Browse'><?=$bus_consulta["codigo_organizacion"]?>-<?=$bus_consulta["codigo"]?></td>
          	<td class='Browse'><?=$bus_consulta["denominacion"]?></td>
            <td width="1%" align="center" class='Browse'>
            	<img src="imagenes/modificar.png" 
                	style="cursor:pointer" 
                    onClick="seleccionarModificar('<?=$bus_consulta["organizacion"]?>','<?=$bus_consulta["codigo_organizacion"]?>-<?=$bus_consulta["codigo"]?>','<?=$bus_consulta["denominacion"]?>','<?=$bus_consulta["responsable"]?>','<?=$bus_consulta["ci_responsable"]?>', '<?=$bus_consulta["sub_nivel"]?>', '<?=$bus_consulta["telefono"]?>', '<?=$bus_consulta["email"]?>', '<?=$bus_consulta["idniveles_organizacionales"]?>')">
            </td>
            <td width="1%" align="center" class='Browse'>
            	<img src="imagenes/delete.png" 
                	style="cursor:pointer" 
                    onClick="seleccionarEliminar('<?=$bus_consulta["organizacion"]?>','<?=$bus_consulta["codigo_organizacion"]?>-<?=$bus_consulta["codigo"]?>','<?=$bus_consulta["denominacion"]?>','<?=$bus_consulta["responsable"]?>','<?=$bus_consulta["ci_responsable"]?>', '<?=$bus_consulta["sub_nivel"]?>', '<?=$bus_consulta["telefono"]?>', '<?=$bus_consulta["email"]?>', '<?=$bus_consulta["idniveles_organizacionales"]?>')">
            </td>
      </tr>
        <?
        }
		?>
        </table>
<?
}
?>