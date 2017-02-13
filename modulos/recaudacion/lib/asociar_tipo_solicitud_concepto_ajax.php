<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);

switch($ejecutar){
	case "ingresarRequisitos":
	$sql_consulta = mysql_query("select * from asociar_tipo_solicitud_concepto 
											where 
											idtipo_solicitud = '".$idtipo_solicitud."' 
											and idconcepto_tributario = '".$idrequisitos."'");
	$num_consulta = mysql_num_rows($sql_consulta);
	if($num_consulta == 0){
	$sql_ingresar = mysql_query("insert into asociar_tipo_solicitud_concepto(idconcepto_tributario, 
														idtipo_solicitud 
														)VALUES('".$idrequisitos."', 
																			'".$idtipo_solicitud."')")or die(mysql_error());
	}else{
		echo "existe";
	}
	break;
	case "listarRequisitos":
		$sql_consulta = mysql_query("select re.denominacion,
											arc.idasociar_tipo_solicitud_concepto
											from 
												concepto_tributario re,
												asociar_tipo_solicitud_concepto arc
											where 
												arc.idtipo_solicitud = '".$idtipo_solicitud."'
												and re.idconcepto_tributario = arc.idconcepto_tributario")or die(mysql_error());
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
              <td width="11%" align='center' class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="eliminarRequisitos('<?=$bus_consulta["idasociar_tipo_solicitud_concepto"]?>', '<?=$idtipo_solicitud?>')"></td>
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
			$sql_eliminar = mysql_query("delete from asociar_tipo_solicitud_concepto where idasociar_tipo_solicitud_concepto='".$idasociar_requisitos_tipo_solicitud."'")or die(mysql_error());
		//}else{
		//	echo "usado";
		//}
		
	break;
}
?>