<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);



if($ejecutar == "registrarEgresos"){

	$sql_registrar_ingresos = mysql_query("insert into ingresos_egresos_financieros(idtipo_movimiento,
																				tipo,
																				numero_documento,
																				fecha,
																				idbanco,
																				idcuentas_bancarias,
																				monto,
																				emitido_por,
																				ci_emitido,
																				concepto,
																				status,
																				usuario,
																				fechayhora)values('".$idtipo_movimiento."',
																									'egreso',
																									'".$numero_documento."',
																									'".$fecha."',
																									'".$idbanco."',
																									'".$idcuentas_bancarias."',
																									'".$monto."',
																									'".$emitido_por."',
																									'".$ci_emisor."',
																									'".$concepto."',
																									'a',
																									'".$login."',
																									'".$fh."')")or die(mysql_error());
	
	if($sql_registrar_ingresos){
		echo "exito";
	}else{
		echo "fallo";
	}
}





if($ejecutar == "cargarCuentasBancarias"){
	$sql_consultar_cuentas = mysql_query("select * from cuentas_bancarias where idbanco = '".$banco."'");
	?>
	<select name="cuenta" id="cuenta">
    	<option value="0">.:: Seleccione ::.</option>
		<?
        while($bus_consultar_cuentas = mysql_fetch_array($sql_consultar_cuentas)){
		?>
			<option	value="<?=$bus_consultar_cuentas["idcuentas_bancarias"]?>"><?=$bus_consultar_cuentas["numero_cuenta"]?></option>
			<?
		}
		?>
    </select>  
	<?
}










if($ejecutar == "modificarEgresos"){


	$sql_registrar_ingresos = mysql_query("update ingresos_egresos_financieros set idtipo_movimiento = '".$idtipo_movimiento."',
																				numero_documento = '".$numero_documento."',
																				fecha = '".$fecha."',
																				idbanco = '".$idbanco."',
																				idcuentas_bancarias = '".$idcuentas_bancarias."',
																				monto = '".$monto."',
																				emitido_por = '".$emitido_por."',
																				ci_emitido = '".$ci_emisor."',
																				concepto ='".$concepto."' where idingresos_financieros = '".$id_egresos."'")or die(mysql_error());
	
	if($sql_registrar_ingresos){
		echo "exito";
	}else{
		echo "fallo";
	}
}






if($ejecutar == "eliminarEgresos"){
	$sql_eliminar_ingresos = mysql_query("delete from ingresos_egresos_financieros where idingresos_financieros = '".$id_egresos."'");
	if($sql_eliminar_ingresos){
		echo "exito";
	}else{
		echo "fallo";
	}
}



?>