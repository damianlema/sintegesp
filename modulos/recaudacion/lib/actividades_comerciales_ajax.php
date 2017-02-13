<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);

switch($ejecutar){
	case "ingresarActividad":
	$sql_ingresar = mysql_query("insert into actividades_comerciales(denominacion,
																		alicuota)VALUES('".$descripcion."',
																						'".$alicuota."')");
	break;
	case "listarActividades":
		$sql_consulta = mysql_query("select * from actividades_comerciales");
		?>
		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
        <thead>
          <tr>
            <td width="69%" class="Browse"><div align="center">Descripcion</div></td>
            <td width="20%" class="Browse"><div align="center">Alicuota</div></td>
            <td class="Browse" colspan="2"><div align="center">Acciones</div></td>
          </tr>
          </thead>
		<?
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td class='Browse' align='left'>&nbsp;<?=$bus_consulta["denominacion"]?></td>
                <td class='Browse' align='right'>&nbsp;<?=number_format($bus_consulta["alicuota"],2,",",".")?></td>
                <td width="1%" align='center' class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onclick="seleccionarDatos('<?=$bus_consulta["denominacion"]?>','<?=$bus_consulta["alicuota"]?>', '<?=$bus_consulta["idactividades_comerciales"]?>')"></td>
              <td width="1%" align='center' class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onclick="seleccionarDatos('<?=$bus_consulta["denominacion"]?>','<?=$bus_consulta["alicuota"]?>', '<?=$bus_consulta["idactividades_comerciales"]?>')"></td>
          </tr>
			<?
		}
		?>
		</table>
		<?
	break;
	case "modificarActividad":
		$sql_modificar = mysql_query("update actividades_comerciales set denominacion = '".$descripcion."',
																		alicuota = '".$alicuota."'
																		where idactividades_comerciales = '".$idactividades_comerciales."'")or die(mysql_error());																		
	break;
	case "eliminarActividad":
		$sql_consultar = mysql_query("select * from actividades_contribuyente where idactividades_comerciales = '".$idactividades_comerciales."'");
		$num_consultar = mysql_num_rows($sql_consultar);
		if($num_consultar == 0){	
			$sql_eliminar = mysql_query("delete from actividades_comerciales where idactividades_comerciales ='".$idactividades_comerciales."'");
		}else{
			echo "usado";
		}
		
	break;
}
?>