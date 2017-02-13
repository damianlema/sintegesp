<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);

switch($ejecutar){
	case "ingresarTipoSolicitud":
	$sql_ingresar = mysql_query("insert into tipo_solicitud(descripcion)VALUES('".$descripcion."')");
	break;
	case "listarTipoSolicitud":
		$sql_consulta = mysql_query("select * from tipo_solicitud");
		?>
		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
        <thead>
          <tr>
            <td width="69%" class="Browse"><div align="center">Descripcion</div></td>
            <td class="Browse" colspan="2"><div align="center">Acciones</div></td>
          </tr>
          </thead>
		<?
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td class='Browse' align='left'>&nbsp;<?=$bus_consulta["descripcion"]?></td>
                <td width="1%" align='center' class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarDatos('<?=$bus_consulta["descripcion"]?>', '<?=$bus_consulta["idtipo_solicitud"]?>')"></td>
              <td width="1%" align='center' class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarDatos('<?=$bus_consulta["descripcion"]?>', '<?=$bus_consulta["idtipo_solicitud"]?>')"></td>
          </tr>
			<?
		}
		?>
		</table>
		<?
	break;
	case "modificarTipoSolicitud":
		$sql_modificar = mysql_query("update tipo_solicitud set descripcion = '".$descripcion."'
																		where idtipo_solicitud = '".$idtipo_solicitud."'")or die(mysql_error());																		
	break;
	case "eliminarTipoSolicitud":
		//$sql_consultar = mysql_query("select * from actividades_contribuyente where idtipo_solicitud = '".$idtipo_solicitud."'");
		//$num_consultar = mysql_num_rows($sql_consultar);
		//if($num_consultar == 0){	
			$sql_eliminar = mysql_query("delete from tipo_solicitud where idtipo_solicitud ='".$idtipo_solicitud."'");
		//}else{
			//echo "usado";
		//}
		
	break;
}
?>