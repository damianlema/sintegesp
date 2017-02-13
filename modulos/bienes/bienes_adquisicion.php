<?
session_start();
if($_GET["registrar"] != ''){
	$sql_actualizar = mysql_query("update articulos_compra_servicio set registrado = 'si',
																		fecha_registro = '".date("Y-m-d H:i:s")."',
																		usuario_registro = '".$login."'
																		where idarticulos_compra_servicio = '".$_GET["registrar"]."'");
?>
<script>window.location.href='principal.php?modulo=8&accion=864';</script>
<?
}
?>

    <br>
<h4 align=center>
Bienes en Adquisicion
<br>
<br>
</h4>
<h2 class="sqlmVersion"></h2>
<br>
<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
            <td width="8%" align="center" class="Browse" style="font-size:9px">Nro. Compra</td>
            <td width="8%" align="center" class="Browse" style="font-size:9px">Fecha</td>
            <td width="73%" align="center" class="Browse" style="font-size:9px">Descripcion del Bien</td>
            <td width="8%" align="center" class="Browse" style="font-size:9px">Monto</td>
            <td width="3%" align="center" class="Browse" style="font-size:9px">Accion</td>
          </tr>
          </thead>
          <?
		  $sql_consultar = mysql_query("SELECT orden_compra_servicio.numero_orden as nro_compra,
					orden_compra_servicio.fecha_orden as fecha,
					articulos_compra_servicio.precio_unitario as precio_unitario,
					articulos_compra_servicio.cantidad as cantidad,
					articulos_compra_servicio.idarticulos_compra_servicio as idarticulo,
					articulos_servicios.descripcion as descripcion
						FROM 
					articulos_servicios,
					orden_compra_servicio,
					articulos_compra_servicio,
					clasificador_presupuestario
						WHERE
					articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
					and articulos_servicios.activo = 'a'
					and articulos_servicios.idclasificador_presupuestario = clasificador_presupuestario.idclasificador_presupuestario
					and clasificador_presupuestario.partida = '404'
					and orden_compra_servicio.idorden_compra_servicio = articulos_compra_servicio.idorden_compra_servicio
					and articulos_compra_servicio.registrado != 'si'
					and (orden_compra_servicio.estado = 'pagado' or orden_compra_servicio.estado = 'procesado')")or die(mysql_error());
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="8%" style="font-size:10px"><?=$bus_consultar["nro_compra"]?></td>
                    <td class='Browse' align='left' width="8%" style="font-size:10px"><?=$bus_consultar["fecha"]?></td>
                    <td class='Browse' align='left' width="73%" style="font-size:10px"><?=$bus_consultar["descripcion"]?></td>
                    <td class='Browse' align="right" width="8%" style="font-size:10px"><?=number_format($bus_consultar["precio_unitario"]*$bus_consultar["cantidad"],2,",",".")?></td>
                    <td width="3%" align="center" valign="middle" class='Browse'>
                      <input type="button" name="boton_registrar" id="boton_registrar" onClick="window.location.href='principal.php?modulo=8&accion=864&registrar=<?=$bus_consultar["idarticulo"]?>'" value="Registrar" class="button">
                    </td>
  </tr>
          <?
          }
          ?>
  </table>