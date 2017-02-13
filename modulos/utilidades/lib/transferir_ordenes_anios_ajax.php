<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();

extract($_POST);


switch($ejecutar){
	case "consultarOrdenes":
		mysql_select_db("gestion_".$_SESSION["anio_fiscal"]);
		//echo "select * from orden_compra_servicio where tipo = '".$tipo_orden."'";
		$sql_consulta = mysql_query("select * from orden_compra_servicio where tipo = '".$tipo_orden."' and estado != 'elaboracion'");
		$num_consulta = mysql_num_rows($sql_consulta);
		$tabla = "orden_compra_servicio";
		if($num_consulta == 0){
		//echo "AQUI";
			$sql_consulta = mysql_query("select * from orden_pago where tipo = '".$tipo_orden."' and estado != 'elaboracion'");
			$tabla = "orden_pago";
		}
		?>
		<select name="numero_orden" id="numero_orden">
        <option value="0" onClick="document.getElementById('boton_procesar').style.display='none'">.:: Seleccione ::.</option>
        	<?
            while($bus_consulta = mysql_fetch_array($sql_consulta)){
				?>
				<option value="<?=$bus_consulta["id".$tabla]?>" onClick="mostrarDatos('<?=$bus_consulta["numero_orden"]?>', '<?=$bus_consulta["id".$tabla]?>')"><?=$bus_consulta["numero_orden"]?></option>
				<?
			}
			?>
            
        </select>
		<?
	break;
	
	
	case "procesarOrden":
	

	
		$sql_consulta = mysql_query("select * from gestion_".$anio_destino.".orden_compra_servicio where tipo = '".$tipo_orden."' and idorden_compra_servicio = '".$numero_orden."'")or die(mysql_error());
		$num_consulta = mysql_num_rows($sql_consulta);
		if($num_consulta == 0){
			
			// CONSULTO
			$sql_insertar = mysql_query("insert into 
											gestion_".$anio_destino.".orden_pago 
											select * 
												from 
											gestion_".$_SESSION["anio_fiscal"].".orden_pago
												where 
											tipo = '".$tipo_orden."' and 
											idorden_pago = '".$numero_orden."'");
			$id_insertado = mysql_insert_id();
			
			$sql_actualizar = mysql_query("update gestion_".$anio_destino.".orden_pago
												set 
												numero_orden = '',
												estado = 'elaboracion',
												 where idorden_pago = '".$id_insertado."'");
			
			
			$sql_insertar_partidas = mysql_query("insert into 
														gestion_".$anio_destino.".partidas_orden_pago
															select * from 
														gestion_".$_SESSION["anio_fiscal"].".partidas_orden_pago
															where 
														idorden_pago = '".$id_insertado."'");
														
														
			$sql_insertar_partidas = mysql_query("insert into 
														gestion_".$anio_destino.".relacion_pago_compromisos
															select * from 
														gestion_".$_SESSION["anio_fiscal"].".relacion_pago_compromisos
															where 
														idorden_pago = '".$id_insertado."'");
			
			
		}else{
		
			$sql_insertar = mysql_query("insert into 
											gestion_".$anio_destino.".orden_compra_servicio 
											select * 
												from 
											gestion_".$_SESSION["anio_fiscal"].".orden_compra_servicio
												where 
											tipo = '".$tipo_orden."' and 
											idorden_compra_servicio = '".$numero_orden."'")or die(mysql_error());
			$id_insertado = mysql_insert_id();
			
			$sql_actualizar = mysql_query("update gestion_".$anio_destino.".orden_compra_servicio
												set 
												numero_orden = '',
												estado = 'elaboracion',
												 where idorden_compra_servicio = '".$id_insertado."'");
			
			
			$sql_insertar_partidas = mysql_query("insert into 
														gestion_".$anio_destino.".partidas_orden_compra_servicio
															select * from 
														gestion_".$_SESSION["anio_fiscal"].".partidas_orden_compra_servicio
															where 
														idorden_compra_servicio = '".$id_insertado."'");
			
			$sql_insertar_partidas = mysql_query("insert into 
														gestion_".$anio_destino.".articulos_orden_compra_servicio
															select * from 
														gestion_".$_SESSION["anio_fiscal"].".articulos_orden_compra_servicio
															where 
														idorden_compra_servicio = '".$id_insertado."'");
		}
	break;
}
?>