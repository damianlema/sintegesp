<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);

switch($ejecutar){
	case "ingresarRequisitos":
	$sql_ingresar = mysql_query("insert into requisitos(denominacion, vencimiento, bloquea_proceso)VALUES('".$descripcion."', '".$vencimiento."', '".$bloquea_procesos."')");
	break;
	case "listarRequisitos":
		$sql_consulta = mysql_query("select * from requisitos");
		?>
		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
        <thead>
          <tr>
            <td width="69%" class="Browse"><div align="center">Descripcion</div></td>
            <td width="69%" class="Browse"><div align="center">Vencimiento</div></td>
            <td width="69%" class="Browse"><div align="center">Bloquea Proceso</div></td>
            <td class="Browse" colspan="2"><div align="center">Acciones</div></td>
          </tr>
          </thead>
		<?
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td class='Browse' align='left'>&nbsp;<?=$bus_consulta["denominacion"]?></td>
                <td class='Browse' align='left'>&nbsp;<?=$bus_consulta["vencimiento"]?></td>
                <td class='Browse' align='left'>&nbsp;<?=$bus_consulta["bloquea_proceso"]?></td>
                <td width="1%" align='center' class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarDatos('<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["idrequisitos"]?>', '<?=$bus_consulta["vencimiento"]?>', '<?=$bus_consulta["bloquea_proceso"]?>')"></td>
              <td width="1%" align='center' class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarDatos('<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["idrequisitos"]?>', '<?=$bus_consulta["vencimiento"]?>', '<?=$bus_consulta["bloquea_proceso"]?>')"></td>
          </tr>
			<?
		}
		?>
		</table>
		<?
	break;
	case "modificarRequisitos":
		$sql_modificar = mysql_query("update requisitos set denominacion = '".$descripcion."',
															vencimiento = '".$vencimiento."',
															bloquea_proceso = '".$bloquea_proceso."'
																		where idrequisitos = '".$idrequisitos."'")or die(mysql_error());																		
	break;
	case "eliminarRequisitos":
		$sql_consultar = mysql_query("select * from asociar_actividad_requisitos where idrequisitos = '".$idrequisitos."'");
		$num_consultar = mysql_num_rows($sql_consultar);
		if($num_consultar == 0){	
			$sql_eliminar = mysql_query("delete from requisitos where idrequisitos ='".$idrequisitos."'");
		}else{
			echo "usado";
		}
		
	break;
}
?>