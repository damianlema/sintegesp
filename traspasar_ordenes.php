<?
mysql_connect("localhost", "root", "1234");
extract($_POST);
if($_POST){
	$sql_consulta_oc = mysql_query("INSERT INTO gestion_".$anio_destino.".orden_compra_servicio
									(SELECT * FROM gestion_".$anio_origen.".orden_compra_servicio 
									WHERE 
									orden_compra_servicio.numero_orden LIKE '%".$numero_orden."%')")or die(mysql_error());
	$sql_consulta = mysql_query("select * from gestion_".$anio_destino.".orden_compra_servicio order by idorden_compra_servicio desc limit 0,1");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	$idorden_compra = $bus_consulta["idorden_compra_servicio"];
	
	$sql_actualizar_oc = mysql_query("UPDATE 
											gestion_".$anio_destino.".orden_compra_servicio
										SET
											numero_orden = '',
											fecha_orden = '',
											fecha_elaboracion= '".date("Y-m-d")."',
											ubicacion = '0',
											estado = 'elaboracion',
											anio = '2011'
										WHERE
											idorden_compra_servicio = '".$idorden_compra."'")or die(mysql_error());
	
	$sql_ingresar_articulos = mysql_query("INSERT INTO gestion_".$anio_destino.".articulos_compra_servicio
											(SELECT * FROM gestion_".$anio_origen.".articulos_compra_servicio 
											WHERE 
											idorden_compra_servicio = '".$idorden_compra."')");
											
	$sql_consultar_partidas = mysql_query("SELECT * 
												FROM 
												gestion_".$anio_origen.".partidas_orden_compra_servicio 
												where 
												idorden_compra_servicio = '".$idorden_compra."'")or die(mysql_error());




	while($bus_consultar_partidas = mysql_fetch_array($sql_consultar_partidas)){

		$sql_maestro_origen = mysql_query("SELECT * FROM gestion_".$anio_origen.".maestro_presupuesto 
											where 
											idRegistro = '".$bus_consultar_partidas["idmaestro_presupuesto"]."'")or die("Error consultando maestro origen".mysql_error());
		$bus_maestro_origen = mysql_fetch_array($sql_maestro_origen);

		
		$sql_maestro_destino = mysql_query("SELECT * FROM 
											gestion_".$anio_destino.".maestro_presupuesto 
											WHERE 
											idcategoria_programatica = '".$bus_maestro_origen["idcategoria_programatica"]."'
											and idclasificador_presupuestario = '".$bus_maestro_origen["idclasificador_presupuestario"]."'
											and idfuente_financiamiento = 2
											and idtipo_presupuesto = 1")or die("Error consultando maestro destino".mysql_error());
		$bus_maestro_destino = mysql_fetch_array($sql_maestro_destino);									
		$sql_ingresar_partida = mysql_query("INSERT INTO gestion_".$anio_destino.".partidas_orden_compra_servicio(
														idorden_compra_servicio,
														idmaestro_presupuesto,
														monto,
														monto_original,
														estado,
														status,
														fechayhora,
														usuario)VALUES('".$idorden_compra."',
																		'".$bus_maestro_destino["idRegistro"]."',
																		'".$bus_consultar_partidas["monto"]."',
																		'".$bus_consultar_partidas["monto_original"]."',
																		'".$bus_consultar_partidas["estado"]."',
																		'".$bus_consultar_partidas["status"]."',
																		'".$bus_consultar_partidas["fechayhora"]."',
																		'".$bus_consultar_partidas["usuario"]."')")or die("Error insertando nuevo maestro".mysql_error());
	}
	
	?>
	<script>
		alert("Se cargo con exito la orden");
		window.location.href='traspasar_ordenes.php';
	</script>
	<?
}else{
?>
<form action="" method="post">
<table border="0" align="center">
  <tr>
    <td>Año Origen</td>
    <td><label>
      <input type="text" name="anio_destino" id="anio_destino">
    </label></td>
  </tr>
  <tr>
    <td>Año Destino</td>
    <td><label>
      <input type="text" name="anio_origen" id="anio_origen">
    </label></td>
  </tr>
  <tr>
    <td>Numero de Orden</td>
    <td><label>
      <input type="text" name="numero_orden" id="numero_orden">
    </label></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><label>
      <input type="submit" name="boton_procesar" id="boton_procesar" value="Enviar">
    </label></td>
  </tr>
</table>
</form>
<?
}
?>