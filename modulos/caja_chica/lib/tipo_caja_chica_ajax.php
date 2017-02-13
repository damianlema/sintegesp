<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);



if($ejecutar == "ingresarTipoCajaChica"){
	$sql_ingresar = mysql_query("insert into tipo_caja_chica(denominacion,
															 unidades_tributarias_aprobadas,
															 resolucion_nro,
															 fecha_resolucion,
															 gaceta_nro,
															 fecha_gaceta,
															 minimo_reponer,
															 maximo_reponer,
															 ut_maxima_factura)
																VALUES
															('".$denominacion."',
															 '".$ut_aprobadas."',
															 '".$resolucion_nro."',
															 '".$fecha_resolucion."',
															 '".$gaceta_nro."',
															 '".$fecha_gaceta."',
															 '".$minimo_reponer."',
															 '".$maximo_reponer."',
															 '".$ut_maximas_factura."')");
}






if($ejecutar == "modificarTipoCajaChica"){
	$sql_modificar = mysql_query("update tipo_caja_chica set denominacion = '".$denominacion."',
															 unidades_tributarias_aprobadas = '".$ut_aprobadas."',
															 resolucion_nro = '".$resolucion_nro."',
															 fecha_resolucion =  '".$fecha_resolucion."',
															 gaceta_nro =  '".$gaceta_nro."',
															 fecha_gaceta = '".$fecha_gaceta."',
															 minimo_reponer = '".$minimo_reponer."',
															 maximo_reponer =  '".$maximo_reponer."',
															 ut_maxima_factura = '".$ut_maximas_factura."'
																WHERE 
															idtipo_caja_chica = '".$idtipo_caja_chica."'")or die(mysql_error());	
}



if($ejecutar == "eliminarTipoCajaChica"){

	$sql_consulta = mysql_query("select * from orden_compra_servicio where idtipo_caja_chica = '".$idtipo_caja_chica."'");
	$num_consulta = mysql_num_rows($sql_consulta);
	
	if($num_consulta > 0){
		echo "utilizado";	
	}else{
		$sql_eliminar = mysql_query("delete from tipo_caja_chica where idtipo_caja_chica = '".$idtipo_caja_chica."'");
	}
}










if($ejecutar == "consultarTiposCajaChica"){
	$sql_consultar = mysql_query("select * from tipo_caja_chica");
	?>
	<table border="0" class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
        	<thead>
            <tr>
            	<td width="26%" class="Browse" align="center">Denominacion</td>
                <td width="29%" class="Browse" align="center">UT. Aprobadas</td>
                <td width="15%" class="Browse" align="center">Resolucion Nro</td>
                <td width="19%" class="Browse" align="center">Gaceta Nro</td>
                <td width="11%" class="Browse" align="center" colspan="2">Acci&oacute;n</td>
            </tr>
            </thead>
            <? while($bus_consultar = mysql_fetch_array($sql_consultar)){?>
            	<tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="left" class="Browse"><?=$bus_consultar["denominacion"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["unidades_tributarias_aprobadas"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["resolucion_nro"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["gaceta_nro"]?></td>
                <td class="Browse" align="center">
                	<img src="imagenes/modificar.png" style="cursor:pointer" alt='Modificar' title='Modificar' 
                    onclick="seleccionarDatos('<?=$bus_consultar["idtipo_caja_chica"]?>',
                    							'<?=$bus_consultar["denominacion"]?>',
                                                '<?=$bus_consultar["unidades_tributarias_aprobadas"]?>',
                                                '<?=$bus_consultar["resolucion_nro"]?>',
                                                '<?=$bus_consultar["fecha_resolucion"]?>',
                                                '<?=$bus_consultar["gaceta_nro"]?>',
                                                '<?=$bus_consultar["fecha_gaceta"]?>',
                                                '<?=$bus_consultar["minimo_reponer"]?>',
                                                '<?=$bus_consultar["maximo_reponer"]?>',
                                       	         '<?=$bus_consultar["ut_maxima_factura"]?>')">
                </td>
                <td class="Browse" align="center">
                	<img src="imagenes/delete.png" style="cursor:pointer" alt='Eliminar' title='Eliminar' onclick="eliminarTipoCajaChica('<?=$bus_consultar["idtipo_caja_chica"]?>')"></td>
          </tr>
            <? } ?>
        </table>
	<?
}
?>

