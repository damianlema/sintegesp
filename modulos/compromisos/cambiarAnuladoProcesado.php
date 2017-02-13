<?
if($_POST){
	$sql_consulta_anulado = mysql_query("select * from orden_compra_servicio where numero_orden = '".$_POST["nro_orden_anulada"]."' limit 0,1");
	$bus_consulta_anulado = mysql_fetch_array($sql_consulta_anulado);
	$num_consulta_anulado = mysql_num_rows($sql_consulta_anulado);
	$sql_consulta_actual = mysql_query("select * from orden_compra_servicio where numero_orden = '".$_POST["nro_orden_actual"]."' limit 0,1");
	$bus_consulta_actual = mysql_fetch_array($sql_consulta_actual);
	$num_consulta_actual = mysql_num_rows($sql_consulta_actual);
	
	if($num_consulta_actual > 0){
		if($num_consulta_anulado > 0){
			$sql_ingresar_anulado = mysql_query("update orden_compra_servicio 
													set numero_orden = '".$bus_consulta_actual["numero_orden"]."',
													fecha_orden = '".$bus_consulta_actual["fecha_orden"]."',
													fecha_elaboracion = '".$bus_consulta_actual["fecha_elaboracion"]."'
													where idorden_compra_servicio = '".$bus_consulta_anulado["idorden_compra_servicio"]."'");
													
													
			$sql_ingresar_actual = mysql_query("update orden_compra_servicio 
													set numero_orden = '".$bus_consulta_anulado["numero_orden"]."',
													fecha_orden = '".$bus_consulta_anulado["fecha_orden"]."',
													fecha_elaboracion = '".$bus_consulta_anulado["fecha_elaboracion"]."'
													where idorden_compra_servicio = '".$bus_consulta_actual["idorden_compra_servicio"]."'");
			?>
			<script>
				alert("Se actualizo los datos con exito");
			</script>
			<?
		}else{
			?>
			<script>
				alert("Disculpe el Nro Actual no existe");
			</script>
			<?	
		}
	}else{
		?>
		<script>
			alert("Disculpe el Numero Anulado no Existe");
		</script>
		<?
	}
}
?>

    <br>
<h4 align=center>Cambiar Numero de OC Anulada por Procesada</h4>
<br /><br />
	<h2 class="sqlmVersion"></h2>
 <br>
 <br />
 
<form name="form1" method="post" action="">
  <table width="60%" border="0" align="center" cellpadding="4" cellspacing="0">
    <tr>
      <td align="right" class='viewPropTitle'>Nro. Orden Anulada:</td>
      <td>
        <input type="text" name="nro_orden_anulada" id="nro_orden_anulada">
        <br />
      Ejem: OC-2010-1      </td>
      <td align="right" class='viewPropTitle'>Nro. Orden Actual:</td>
      <td>
        <input type="text" name="nro_orden_actual" id="nro_orden_actual">
        <br />
      Ejem: OC-2010-1      </td>
      <td>
        <input type="submit" name="boton_enviar" id="boton_enviar" value="Enviar">
      </td>
    </tr>
  </table>
</form>
