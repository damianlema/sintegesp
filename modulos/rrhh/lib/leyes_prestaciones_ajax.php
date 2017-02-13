<?
session_start();

include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
?>

<?
extract($_POST);

if($ejecutar == "registrarLeyesPrestaciones"){
	$sql_insert_movimientos = mysql_query("insert into leyes_prestaciones (denominacion,
																				siglas,
																				calcula,
																				tipo_abono,
																				mes_inicial_abono,
																				valor_abono,
																				valor_abono_adicional,
																				tipo_abono_adicional,
																				valor_tope_adicional,
																				mes_desde,
																				anio_desde,
																				mes_hasta,
																				anio_hasta)values(
																									'".$denominacion."',
																									'".$siglas."',
																									'".$calcula."',
																									'".$tipo_abono."',
																									'".$mes_inicio_abono."',
																									'".$valor_abono."',
																									'".$valor_abono_adicional."',
																									'".$tipo_abono_adicional."',
																									'".$tope_abono_adicional."',
																									'".$mes_inicio_aplica."',
																									'".$anio_inicio_aplica."',
																									'".$mes_fin_aplica."',
																									'".$anio_fin_aplica."')");
																							
	if($sql_insert_movimientos){
		echo "exito";
		registra_transaccion("Registro Leyes Prestaciones (".$denominacion.")",$login,$fh,$pc,'leyes_prestaciones');
	}else{
		registra_transaccion("Registro Leyes Prestaciones ERROR (".$denominacion.")",$login,$fh,$pc,'leyes_prestaciones');
		echo "fallo";
	}
}








if($ejecutar == "consultarLeyesPrestaciones"){
    $sql_leyes_prestaciones = mysql_query("select * from leyes_prestaciones");
	$num_leyes_prestaciones = mysql_num_rows($sql_leyes_prestaciones);
	if($num_leyes_prestaciones > 0){
	?>
    <table class="Browse" cellpadding="0" cellspacing="0" width="30%" align="center">
        <thead>
            <tr>
            	<td align="center" class="Browse">Siglas</td>
                <td align="center" class="Browse">Denominacion</td>
                <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
            </tr>
        </thead>
        <?
		while($bus_leyes_prestaciones = mysql_fetch_array($sql_leyes_prestaciones)){
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td align='left' class='Browse'><?=$bus_leyes_prestaciones["siglas"]?></td>
                <td align='left' class='Browse'><?=$bus_leyes_prestaciones["denominacion"]?></td>
				<td align='center' class='Browse' width='7%'>
                    	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar' 
                    		onClick="mostrarParaModificar('<?=$bus_leyes_prestaciones["idleyes_prestaciones"]?>', 
                    										'<?=$bus_leyes_prestaciones["denominacion"]?>', 
                    										'<?=$bus_leyes_prestaciones["siglas"]?>',
                    										'<?=$bus_leyes_prestaciones["calcula"]?>',
                    										'<?=$bus_leyes_prestaciones["tipo_abono"]?>', 
                    										'<?=$bus_leyes_prestaciones["mes_inicial_abono"]?>',
                    										'<?=$bus_leyes_prestaciones["valor_abono"]?>', 
                    										'<?=$bus_leyes_prestaciones["valor_abono_adicional"]?>',
                    										'<?=$bus_leyes_prestaciones["tipo_abono_adicional"]?>', 
                    										'<?=$bus_leyes_prestaciones["valor_tope_adicional"]?>',
                    										'<?=$bus_leyes_prestaciones["mes_desde"]?>', 
                    										'<?=$bus_leyes_prestaciones["anio_desde"]?>',
                    										'<?=$bus_leyes_prestaciones["mes_hasta"]?>', 
                    										'<?=$bus_leyes_prestaciones["anio_hasta"]?>'
                    										)" style="cursor:pointer">
                </td>
				<td align='center' class='Browse' width='7%'>
                    	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar' onClick="eliminarLeyesPrestaciones('<?=$bus_leyes_prestaciones["idleyes_prestaciones"]?>')" style="cursor:pointer">
               	</td>
         </tr>
         <?
         }
		 ?>
    </table>
    <?
    }
	registra_transaccion("Consultar Lista de Leyes de Prestaciones",$login,$fh,$pc,'leyes_prestaciones');
}








if($ejecutar == "eliminarLeyesPrestaciones"){
	
	$sql_eliminar_leyes_prestaciones = mysql_query("delete from leyes_prestaciones where idleyes_prestaciones = '".$idleyes_prestaciones."'")or die(mysql_error());
	if($sql_eliminar_leyes_prestaciones){
			echo "exito";
			registra_transaccion("Eliminar Leyes Prestaciones (".$idleyes_prestaciones.")",$login,$fh,$pc,'leyes_prestaciones');
	}else{
			registra_transaccion("Eliminar Leyes Prestaciones ERROR(".$idleyes_prestaciones.")",$login,$fh,$pc,'leyes_prestaciones');
			echo "fallo";
	}
	
}







if($ejecutar == "modificarLeyesPrestaciones"){
	$sql_update_Leyes_prestaciones = mysql_query("update leyes_prestaciones set 
																			denominacion = '".$denominacion."',
																			siglas = '".$siglas."',
																			calcula = '".$calcula."',
																			tipo_abono = '".$tipo_abono."',
																			mes_inicial_abono = '".$mes_inicio_abono."',
																			valor_abono = '".$valor_abono."',
																			valor_abono_adicional = '".$valor_abono_adicional."',
																			tipo_abono_adicional = '".$tipo_abono_adicional."',
																			valor_tope_adicional = '".$tope_abono_adicional."',
																			mes_desde = '".$mes_inicio_aplica."',
																			anio_desde = '".$anio_inicio_aplica."',
																			mes_hasta = '".$mes_fin_aplica."',
																			anio_hasta = '".$anio_fin_aplica."'
																			where idleyes_prestaciones = '".$idleyes_prestaciones."'");
	if($sql_update_Leyes_prestaciones){
		echo "exito";
		registra_transaccion("Modificar Leyes Prestaciones (".$denominacion."-".$afecta.")",$login,$fh,$pc,'leyes_prestaciones');
	}else{
		registra_transaccion("Modificar Leyes Prestaciones ERROR (".$denominacion."-".$afecta.")",$login,$fh,$pc,'leyes_prestaciones');
		echo "fallo";
	}
}
?>