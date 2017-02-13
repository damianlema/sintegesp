<?
if($_POST){
	$sql_consulta_anulado = mysql_query("select * from orden_pago where numero_orden = '".$_POST["nro_orden_anulada"]."' limit 0,1");
	$bus_consulta_anulado = mysql_fetch_array($sql_consulta_anulado);
	$num_consulta_anulado = mysql_num_rows($sql_consulta_anulado);
	$sql_consulta_actual = mysql_query("select * from orden_pago where numero_orden = '".$_POST["nro_orden_actual"]."' limit 0,1");
	$bus_consulta_actual = mysql_fetch_array($sql_consulta_actual);
	$num_consulta_actual = mysql_num_rows($sql_consulta_actual);
	
	if($num_consulta_actual > 0){
		if($num_consulta_anulado > 0){
			$sql_ingresar_anulado = mysql_query("update orden_pago 
													set numero_orden = '".$bus_consulta_actual["numero_orden"]."',
													fecha_orden = '".$bus_consulta_actual["fecha_orden"]."',
													fecha_elaboracion = '".$bus_consulta_actual["fecha_elaboracion"]."'
													where idorden_pago = '".$bus_consulta_anulado["idorden_pago"]."'")or die("ERROR CAMBIANDO ANULADO:".mysql_error());
													
													
			$sql_ingresar_actual = mysql_query("update orden_pago 
													set numero_orden = '".$bus_consulta_anulado["numero_orden"]."',
													fecha_orden = '".$bus_consulta_anulado["fecha_orden"]."',
													fecha_elaboracion = '".$bus_consulta_anulado["fecha_elaboracion"]."'
													where idorden_pago = '".$bus_consulta_actual["idorden_pago"]."'")or die("ERROR CAMBIANDO ACTUAL:".mysql_error());
			?>
			<script>
				mostrarMensajes("exito", "Se actualizo los datos con exito");
			</script>
			<?
		}else{
			?>
			<script>
				mostrarMensajes("error", "Disculpe el numero actual no existe");
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
	<h4 align=center>Cambiar Numeros de Orden de Pago</h4>
	<h2 class="sqlmVersion"></h2>
	<br>

<form name="form1" method="post" action="">
  <table width="60%" border="0" align="center" cellpadding="4" cellspacing="0">
    <tr>
      <td align="right" class='viewPropTitle'>Nro. Orden Anulada:</td>
      <td>
        <input type="text" name="nro_orden_anulada" id="nro_orden_anulada">
        <br />
        Ejem: OP-2010-1
      </td>
      <td align="right" class='viewPropTitle'>Nro. Orden Actual:</td>
      <td>
        <input type="text" name="nro_orden_actual" id="nro_orden_actual">
        <br />
      Ejem: OP-2010-1      </td>
      <td>
        <input type="submit" name="boton_enviar" id="boton_enviar" value="Enviar">
      </td>
    </tr>
  </table>
</form>
