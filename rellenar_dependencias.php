<?
include("conf/conex.php");
Conectarse();

$sql_orden_compra = mysql_query("select * from orden_compra_servicio where estado != 'elaboracion'");
while($bus_orden_compra = mysql_fetch_array($sql_orden_compra)){

$sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$bus_orden_compra["tipo"]."'");
$bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);

	if(ereg("-3-", $bus_tipo_documento["modulo"]) == 1){
	$sql_dependencia = mysql_query("select * from configuracion_compras");
	}else if(ereg("-1-", $bus_tipo_documento["modulo"]) == 1){
		$sql_dependencia = mysql_query("select * from configuracion_rrhh");
	}else if(ereg("-4-", $bus_tipo_documento["modulo"]) == 1){
		$sql_dependencia = mysql_query("select * from configuracion_administracion");
	}else if(ereg("-5-", $bus_tipo_documento["modulo"]) == 1){
		$sql_dependencia = mysql_query("select * from configuracion_contabilidad");
	}else if(ereg("-6-", $bus_tipo_documento["modulo"]) == 1){
		$sql_dependencia = mysql_query("select * from configuracion_tributos");
	}else if(ereg("-7-", $bus_tipo_documento["modulo"]) == 1){
		$sql_dependencia = mysql_query("select * from configuracion_tesoreria");
	}else if(ereg("-8-", $bus_tipo_documento["modulo"]) == 1){
		$sql_dependencia = mysql_query("select * from configuracion_bienes");
	}else if(ereg("-2-", $bus_tipo_documento["modulo"]) == 1){
		$sql_dependencia = mysql_query("select * from configuracion_presupuesto");
	}else if(ereg("-12-", $bus_tipo_documento["modulo"]) == 1){
		$sql_dependencia = mysql_query("select * from configuracion_despacho");
	}else if(ereg("-13-", $bus_tipo_documento["modulo"]) == 1){
		$sql_dependencia = mysql_query("select * from configuracion_nomina");
	}else if(ereg("-14-", $bus_tipo_documento["modulo"]) == 1){
		$sql_dependencia = mysql_query("select * from configuracion_secretaria");
	}else if(ereg("-16-", $bus_tipo_documento["modulo"]) == 1){
		$sql_dependencia = mysql_query("select * from configuracion_caja_chica");
	}else if(ereg("-17-", $bus_tipo_documento["modulo"]) == 1){
		$sql_dependencia = mysql_query("select * from configuracion_recaudacion");
	}
	
	$bus_dependencia = mysql_fetch_array($sql_dependencia);
	$iddependencia = $bus_dependencia["iddependencia"];


	$sql_actualizar = mysql_query("update orden_compra_servicio set iddependencia ='".$iddependencia."' where idorden_compra_servicio = '".$bus_orden_compra["idorden_compra_servicio"]."'");

	echo "EL ID DE LA DEPENDENCIA ES :".$iddependencia.", PARA EL NUMERO DE COMPRA: ".$bus_orden_compra["numero_orden"]."<br>";


}






?>