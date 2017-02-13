<?
session_start();

include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
?>

<?
extract($_POST);

if($ejecutar == "registrarTiposMovimientos"){
	$sql_insert_movimientos = mysql_query("insert into tipo_movimiento_bancario (denominacion,
																				siglas,
																				afecta,
																				usuario,
																				status,
																				fechayhora,
																				idcuenta_debe,
																				tabla_debe,
																				idcuenta_haber,
																				tabla_haber,
																				excluir_contabilidad)values(
																									'".$denominacion."',
																									'".$siglas."',
																									'".$afecta."',
																									'".$login."',
																									'a',
																									'".$fh."',
																									'".$idcuenta_deudora."',
																									'".$tabla_deudora."',
																									'".$idcuenta_acreedora."',
																									'".$tabla_acreedora."',
																									'".$excluir_contabilidad."')");
																									
	if($sql_insert_movimientos){
		echo "exito";
		registra_transaccion("Registro Tipo de Movimiento Bancario (".$denominacion.")",$login,$fh,$pc,'tipo_movimiento_bancario');
	}else{
		registra_transaccion("Registro Tipo de Movimiento Bancario ERROR (".$denominacion.")",$login,$fh,$pc,'tipo_movimiento_bancario');
		echo "fallo";
	}
}








if($ejecutar == "consultarTiposMovimientos"){
    $sql_tipos_movimientos = mysql_query("select * from tipo_movimiento_bancario");
	$num_tipos_movimientos = mysql_num_rows($sql_tipos_movimientos);
	if($num_tipos_movimientos > 0){
	?>
    <table class="Browse" cellpadding="0" cellspacing="0" width="30%" align="center">
        <thead>
            <tr>
                <td align="center" class="Browse">Denominacion</td>
                <td align="center" class="Browse">Siglas</td>
                <td align="center" class="Browse">Afecta</td>
                <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
            </tr>
        </thead>
        <?
		while($bus_tipos_movimientos = mysql_fetch_array($sql_tipos_movimientos)){
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td align='left' class='Browse'><?=$bus_tipos_movimientos["denominacion"]?></td>
                <td align='left' class='Browse'><?=$bus_tipos_movimientos["siglas"]?></td>
                <td align='left' class='Browse'>
					<?
                    if($bus_tipos_movimientos["afecta"] == "d"){
                        echo "Debita";
                    }else{
                        echo "Acredita";
                    }
                    ?>
                </td>
				<td align='center' class='Browse' width='7%'>
						<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar' onClick="mostrarParaModificar('<?=$bus_tipos_movimientos["idtipo_movimiento_bancario"]?>', '<?=$bus_tipos_movimientos["denominacion"]?>', '<?=$bus_tipos_movimientos["siglas"]?>', '<?=$bus_tipos_movimientos["afecta"]?>', '<?=$bus_tipos_movimientos["idcuenta_debe"]?>', '<?=$bus_tipos_movimientos["tabla_debe"]?>', '<?=$bus_tipos_movimientos["idcuenta_haber"]?>', '<?=$bus_tipos_movimientos["tabla_haber"]?>', '<?=$bus_tipos_movimientos["excluir_contabilidad"]?>')" style="cursor:pointer">
                </td>
				<td align='center' class='Browse' width='7%'>
                    	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar' onclick="eliminarTipoMovimiento('<?=$bus_tipos_movimientos["idtipo_movimiento_bancario"]?>')" style="cursor:pointer">
               	</td>
         </tr>
         <?
         }
		 ?>
    </table>
    <?
    }
	registra_transaccion("Consultar Lista de Tipo de Movimiento Bancario",$login,$fh,$pc,'tipo_movimiento_bancario');
}








if($ejecutar == "eliminarTipoMovimiento"){
	
	$sql_validar=mysql_query("select * from ingresos_egresos_financieros where idtipo_movimiento = '".$idtipo_movimiento_bancario."'");
	if (mysql_num_rows($sql_validar) <= 0){
	
		$sql_eliminar_tipo_movimiento = mysql_query("delete from tipo_movimiento_bancario where idtipo_movimiento_bancario = '".$idtipo_movimiento_bancario."'")or die(mysql_error());
		if($sql_eliminar_tipo_movimiento){
			echo "exito";
			registra_transaccion("Eliminar Tipo de Movimiento Bancario (".$idtipo_movimiento_bancario.")",$login,$fh,$pc,'tipo_movimiento_bancario');
		}else{
			registra_transaccion("Registrar Tipo de Movimiento Bancario ERROR(".$idtipo_movimiento_bancario.")",$login,$fh,$pc,'tipo_movimiento_bancario');
			echo "fallo";
		}
	}else{
		echo "fallo";
	}
}







if($ejecutar == "modificarTiposMovimientos"){
	$sql_update_tipo_movimiento = mysql_query("update tipo_movimiento_bancario set 
																			denominacion = '".$denominacion."',
																			siglas = '".$siglas."',
																			afecta = '".$afecta."',
																			tabla_debe = '".$tabla_deudora."',
																			idcuenta_debe = '".$idcuenta_deudora."',
																			tabla_haber = '".$tabla_acreedora."',
																			idcuenta_haber = '".$idcuenta_acreedora."',
																			excluir_contabilidad = '".$excluir_contabilidad."'
																			where idtipo_movimiento_bancario = '".$idtipo_movimiento_bancario."'");
	if($sql_update_tipo_movimiento){
		echo "exito";
		registra_transaccion("Modificar Tipo de Movimiento Bancario (".$denominacion."-".$afecta.")",$login,$fh,$pc,'tipo_movimiento_bancario');
	}else{
		registra_transaccion("Modificar Tipo de Movimiento Bancario ERROR (".$denominacion."-".$afecta.")",$login,$fh,$pc,'tipo_movimiento_bancario');
		echo "fallo";
	}
}
?>