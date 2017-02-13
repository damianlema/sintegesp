<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);

switch($ejecutar){
	case "ingresarCarrera":
	$sql_ingresar = mysql_query("insert into carrera(idcalle, 
														denominacion, 
														usuario, 
														status, 
														fechayhora)VALUES('".$idcalle."', 
																			'".$descripcion."',
																			'".$login."', 
																			'a', 
																			'".$fh."')")or die(mysql_error());
	echo mysql_insert_id();
	break;
	case "listarCarrera":
		$sql_consulta = mysql_query("select es.denominacion as denominacion_estado,
											mu.denominacion as denominacion_municipio,
											mu.idcarrera,
											mu.idcalle 
											from 
											carrera mu,
											calle es
											where es.idcalle = mu.idcalle")or die(mysql_error());
		?>
		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
        <thead>
          <tr>
            <td width="36%" class="Browse"><div align="center">Calle</div></td>
            <td width="43%" class="Browse"><div align="center">Descripcion</div></td>
            <td class="Browse" colspan="2"><div align="center">Acciones</div></td>
          </tr>
          </thead>
		<?
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td class='Browse' align='left'>&nbsp;<?=$bus_consulta["denominacion_estado"]?></td>
                <td class='Browse' align='left'>&nbsp;<?=$bus_consulta["denominacion_municipio"]?></td>
                <td width="10%" align='center' class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarDatos('<?=$bus_consulta["denominacion_municipio"]?>', '<?=$bus_consulta["idcarrera"]?>', '<?=$bus_consulta["idcalle"]?>')"></td>
              <td width="11%" align='center' class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarDatos('<?=$bus_consulta["denominacion_municipio"]?>', '<?=$bus_consulta["idcarrera"]?>', '<?=$bus_consulta["idcalle"]?>')"></td>
          </tr>
			<?
		}
		?>
		</table>
		<?
	break;
	case "modificarCarrera":
		$sql_modificar = mysql_query("update carrera set denominacion = '".$descripcion."',
															idcalle = '".$idcalle."'
																		where idcarrera = '".$idcarrera."'")or die(mysql_error());																		
	break;
	case "eliminarCarrera":
		$sql_consultar = mysql_query("select * from contribuyente where carrera = '".$idcarrera."'");
		$num_consultar = mysql_num_rows($sql_consultar);
		if($num_consultar == 0){	
			$sql_eliminar = mysql_query("delete from carrera where idcarrera ='".$idcarrera."'");
		}else{
			echo "usado";
		}
		
	break;
}
?>