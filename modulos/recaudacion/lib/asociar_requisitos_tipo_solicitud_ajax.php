<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);

switch($ejecutar){
	case "ingresarRequisitos":
	$sql_consulta = mysql_query("select * from asociar_requisitos_tipo_solicitud 
											where 
											idtipo_solicitud = '".$idtipo_solicitud."' 
											and idrequisitos = '".$idrequisitos."'");
	$num_consulta = mysql_num_rows($sql_consulta);
	if($num_consulta == 0){
	$sql_ingresar = mysql_query("insert into asociar_requisitos_tipo_solicitud(idrequisitos, 
														idtipo_solicitud 
														)VALUES('".$idrequisitos."', 
																			'".$idtipo_solicitud."')")or die(mysql_error());
	}else{
		echo "existe";
	}
	break;
	case "listarRequisitos":
		$sql_consulta = mysql_query("select re.denominacion,
											arc.idasociar_requisitos_tipo_solicitud
											from 
												requisitos re,
												asociar_requisitos_tipo_solicitud arc
											where 
												arc.idtipo_solicitud = '".$idtipo_solicitud."'
												and re.idrequisitos = arc.idrequisitos")or die(mysql_error());
		?>
		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
        <thead>
          <tr>
            <td width="43%" class="Browse"><div align="center">Descripcion</div></td>
            <td class="Browse" colspan="2"><div align="center">Eliminar</div></td>
          </tr>
          </thead>
		<?
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td class='Browse' align='left'>&nbsp;<?=$bus_consulta["denominacion"]?></td>
              <td width="11%" align='center' class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="eliminarRequisitos('<?=$bus_consulta["idasociar_requisitos_tipo_solicitud"]?>', '<?=$idtipo_solicitud?>')"></td>
          </tr>
			<?
		}
		?>
		</table>
		<?
	break;

	case "eliminarRequisitos":
		//$sql_consultar = mysql_query("select * from contribuyente where carrera = '".$idcarrera."'");
		//$num_consultar = mysql_num_rows($sql_consultar);
		//if($num_consultar == 0){	
			$sql_eliminar = mysql_query("delete from asociar_requisitos_tipo_solicitud where idasociar_requisitos_tipo_solicitud='".$idasociar_requisitos_tipo_solicitud."'")or die(mysql_error());
		//}else{
		//	echo "usado";
		//}
		
	break;
}
?>