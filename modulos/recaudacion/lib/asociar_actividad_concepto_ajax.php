<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);

switch($ejecutar){
	case "ingresarRequisitos":
	$sql_consulta = mysql_query("select * from asociar_concepto_actividad_comercial 
											where 
											idactividad_comercial = '".$idactividad_comercial."' 
											and idconcepto_tributario = '".$idrequisitos."'");
	$num_consulta = mysql_num_rows($sql_consulta);
	if($num_consulta == 0){
	$sql_ingresar = mysql_query("insert into asociar_concepto_actividad_comercial(idconcepto_tributario, 
														idactividad_comercial,
														valor,
														tipo_valor
														)VALUES('".$idrequisitos."', 
																			'".$idactividad_comercial."',
																			'".$valor."',
																			'".$tipo_valor."')")or die(mysql_error());
	}else{
		echo "existe";
	}
	break;
	case "listarRequisitos":
		$sql_consulta = mysql_query("select re.denominacion,
											arc.valor,
											arc.tipo_valor,
											arc.idasociar_concepto_actividad_comercial
											from 
												concepto_tributario re,
												asociar_concepto_actividad_comercial arc
											where 
												arc.idactividad_comercial = '".$idactividad_comercial."'
												and re.idconcepto_tributario = arc.idconcepto_tributario")or die(mysql_error());
		?>
		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
        <thead>
          <tr>
            <td width="76%" class="Browse"><div align="center">Descripcion</div></td>
            <td width="12%" class="Browse"><div align="center">Valor</div></td>
            <td class="Browse" colspan="2"><div align="center">Eliminar</div></td>
          </tr>
          </thead>
		<?
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td class='Browse' align='left'>&nbsp;<?=$bus_consulta["denominacion"]?></td>
                <td class='Browse' align='right'>&nbsp;<?=$bus_consulta["valor"]?>&nbsp;
				<?
                if($bus_consulta["tipo_valor"] == "unidad_tributaria"){
					echo "UT";
				}else{
					echo "BS";
				}
				?></td>
              <td width="12%" align='center' class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="eliminarRequisitos('<?=$bus_consulta["idasociar_concepto_actividad_comercial"]?>', '<?=$idactividad_comercial?>')"></td>
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
			$sql_eliminar = mysql_query("delete from asociar_concepto_actividad_comercial where idasociar_concepto_actividad_comercial='".$idasociar_requisitos_actividad_comercial."'")or die(mysql_error());
		//}else{
		//	echo "usado";
		//}
		
	break;
}
?>