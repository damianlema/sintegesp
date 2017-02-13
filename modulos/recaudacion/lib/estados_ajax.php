<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);

switch($ejecutar){
	case "ingresarEstado":
	$sql_ingresar = mysql_query("insert into estado(idpais, denominacion, usuario, status, fechayhora)VALUES('1', '".$descripcion."',
																						'".$login."', 'a', '".$fh."')");
	break;
	case "listarEstado":
		$sql_consulta = mysql_query("select * from estado")or die(mysql_error());
		?>
		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
        <thead>
          <tr>
            <td width="19%" class="Browse"><div align="center">Pais</div></td>
            <td width="63%" class="Browse"><div align="center">Descripcion</div></td>
            <td class="Browse" colspan="2"><div align="center">Acciones</div></td>
          </tr>
          </thead>
		<?
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td class='Browse' align='left'>&nbsp;VENEZUELA</td>
                <td class='Browse' align='left'>&nbsp;<?=$bus_consulta["denominacion"]?></td>
                <td width="11%" align='center' class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarDatos('<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["idestado"]?>')"></td>
              <td width="7%" align='center' class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarDatos('<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["idestado"]?>')"></td>
          </tr>
			<?
		}
		?>
		</table>
		<?
	break;
	case "modificarEstado":
		$sql_modificar = mysql_query("update estado set denominacion = '".$descripcion."'
																		where idestado = '".$idestado."'")or die(mysql_error());																		
	break;
	case "eliminarEstado":
		$sql_consultar = mysql_query("select * from municipios where idestado = '".$idestado."'");
		$num_consultar = mysql_num_rows($sql_consultar);
		if($num_consultar == 0){	
			$sql_eliminar = mysql_query("delete from estado where idestado ='".$idestado."'");
		}else{
			echo "usado";
		}
		
	break;
}
?>