<?
include("conf/conex.php");
Conectarse();
$n=1;
$sql_orden_compra = mysql_query("select * from orden_compra_servicio where estado != 'elaboracion' and estado != 'anulado'");
	while($bus_orden_compra =mysql_fetch_array($sql_orden_compra)){
		$sql_articulos= mysql_query("select SUM((cantidad*precio_unitario)+impuesto) as total, SUM(total) as total_articulos, SUM(exento) as exento_articulos, SUM(impuesto) as impuestos_articulos from articulos_compra_servicio where idorden_compra_servicio='".$bus_orden_compra["idorden_compra_servicio"]."'");
		$bus_articulos= mysql_fetch_array($sql_articulos);
		$total = $bus_articulos["total"];
		$total = round($total,2);
		if($total != $bus_orden_compra["total"]){
			$swql_actualizar = mysql_query("update orden_compra_servicio set exento = '".$bus_articulos["exento_articulos"]."', 
																sub_total = '".$bus_articulos["total_articulos"]."',
																impuesto = '".$bus_articulos["impuestos_articulos"]."',
																total = '".($bus_articulos["total_articulos"]+$bus_articulos["impuestos_articulos"]+$bus_articulos["exento_articulos"])."'
																where idorden_compra_servicio = '".$bus_orden_compra["idorden_compra_servicio"]."'")or die(mysql_error());
			echo $n." - ".$bus_orden_compra["numero_orden"]." - Total Articulos: ".$total." |	Total Orden: ".$bus_orden_compra["total"]."<br>";
		$n++;
		}
		
	}
?>