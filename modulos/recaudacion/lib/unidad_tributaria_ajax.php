<?
include("../../../conf/conex.php");
Conectarse();
extract($_POST);


switch($ejecutar){
	case "ingresar_unidad":
		$sql_ingresar = mysql_query("insert into unidad_tributaria (anio,
																		desde,
																		hasta,
																		costo,
																		gaceta,
																		fecha_gaceta)VALUES('".$anio."',
																					'".$desde."',
																					'".$hasta."',
																					'".$costo."',
																					'".$gaceta."',
																					'".$fecha_gaceta."')")or die(mysql_error());
	break;
	case "listarUnidades":
	$sql_consulta = mysql_query("select * from unidad_tributaria");
	?>
	<table align="center" cellpadding="0" cellspacing="0" border="0" width="40%">
<thead>
              <tr>
                <td align="center" class="Browse">A&ntilde;o</td>
                <td align="center" class="Browse">Desde</td>
                <td align="center" class="Browse">Hasta</td>
                <td align="center" class="Browse">Costo</td>
                <td colspan="2" align="center" class="Browse">Acciones</td>
      </tr>
              </thead>
	<?
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
	?>
	<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                        <td class='Browse' align='center'><?=$bus_consulta["anio"]?></td>
                        <td class='Browse' align='center'><?=$bus_consulta["desde"]?></td>
                        <td class='Browse' align='center'><?=$bus_consulta["hasta"]?></td>
                        <td class='Browse' align='right'><?=$bus_consulta["costo"]?></td>
                        <td class='Browse' align='center'><img src="imagenes/modificar.png" onclick="seleccionarUnidad('<?=$bus_consulta["idunidad_tributaria"]?>', '<?=$bus_consulta["anio"]?>', '<?=$bus_consulta["desde"]?>', '<?=$bus_consulta["hasta"]?>', '<?=$bus_consulta["costo"]?>', '<?=$bus_consulta["gaceta"]?>', '<?=$bus_consulta["fecha_gaceta"]?>'), document.getElementById('boton_ingresar').style.display='none', document.getElementById('boton_modificar').style.display='block', document.getElementById('boton_eliminar').style.display='none'" style="cursor:pointer"></td>
                        <td class='Browse' align='center'><img src="imagenes/delete.png" onclick="seleccionarUnidad('<?=$bus_consulta["idunidad_tributaria"]?>', '<?=$bus_consulta["anio"]?>', '<?=$bus_consulta["desde"]?>', '<?=$bus_consulta["hasta"]?>', '<?=$bus_consulta["costo"]?>', '<?=$bus_consulta["gaceta"]?>', '<?=$bus_consulta["fecha_gaceta"]?>'), document.getElementById('boton_ingresar').style.display='none', document.getElementById('boton_modificar').style.display='none', document.getElementById('boton_eliminar').style.display='block'" style="cursor:pointer"></td>
     </tr>
	<?
	}
	?>
	</table>
<?
	break;
	case "modificarUnidad":
	$sql_actualizar = mysql_query("update unidad_tributaria set anio = '".$anio."',
																	desde = '".$desde."',
																	hasta = '".$hasta."',
																	costo = '".$costo."',
																	gaceta = '".$gaceta."',
																	fecha_gaceta = '".$fecha_gaceta."'
																	where idunidad_tributaria = '".$idunidad_tributaria."'");
	break;
	case "eliminarUnidad":
	$sql_eliminar = mysql_query("delete from unidad_tributaria where idunidad_tributaria = '".$idunidad_tributaria."'");
	break;
}
?>