<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);

if($ejecutar == "cambiar_numero_comprobante"){
	if ($idorden_pago <> '0'){
		
		
		$sql_relacion_orden_pago_retencion = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago = '".$idorden_pago."'");
		
		
		while ($bus_relacion = mysql_fetch_array($sql_relacion_orden_pago_retencion)){	
			//ACTUALIZO LOS DATOS EN LA RETENCION
			$sql_actualiza_retencion = mysql_query("update retenciones set fecha_aplicacion_retencion = '".$fecha_comprobante."',
																			numero_factura =  '".$numero_factura."',
																			numero_control = '".$numero_control."',
																			fecha_factura = '".$fecha_factura."'
																					where idretenciones = '".$bus_relacion["idretencion"]."'");
			
			$sql_relacion_compromiso_retencion = mysql_query("select * from retenciones where idretenciones = '".$bus_relacion["idretencion"]."'");
			$bus_retencion = mysql_fetch_array($sql_relacion_compromiso_retencion);
			//ACTUALIZO LOS DATOS DE LA FACTURA EN EL COMPROMISO
			$sql_actualiza_retencion = mysql_query("update orden_compra_servicio set nro_factura =  '".$numero_factura."',
																			nro_control = '".$numero_control."',
																			fecha_factura = '".$fecha_factura."'
																					where idorden_compra_servicio = '".$bus_retencion["iddocumento"]."'");
			
			$fecha = explode("-", $fecha_comprobante);
			//ACTUALIZO LOS DATOS EN LA RETENCION ESPECIFICA
			$sql_actualiza_relacion_retencion = mysql_query("update relacion_retenciones set periodo = '".$fecha[0].$fecha[1]."',
																					numero_retencion = '".$numero_comprobante."',
																					codigo_concepto = '".$codigo_concepto."' 
																					where idretenciones = '".$bus_relacion["idretencion"]."'
																					and idtipo_retencion = '".$idtipo_retencion."'");
			//ACTUALIZO LOS DATOS EN EL CONTROL DE COMPROBANTES
			$sql_actualiza_comprobante = mysql_query("update comprobantes_retenciones set numero_retencion = '".$numero_comprobante."' 
																					where idretenciones = '".$bus_relacion["idretencion"]."'
																					and idorden_pago = '".$idorden_pago."'
																					and idtipo_retencion = '".$idtipo_retencion."'");
			
		}
	}else{
		//echo 'entro';
		//ACTUALIZO DATOS DE FECHA DE RETENCIONES
		$sql_actualiza_retencion = mysql_query("update retenciones set fecha_aplicacion_retencion = '".$fecha_comprobante."' 
																					where idretenciones = '".$idretenciones."'")or die(mysql_error());
		$fecha = explode("-", $fecha_comprobante);
		//ACTUALIZO NUMERO DE COMPROBANTE Y PERIODO
		$sql_actualiza_relacion_retencion = mysql_query("update relacion_retenciones_externas set periodo = '".$fecha[0].$fecha[1]."',
																					numero_retencion = '".$numero_comprobante."',
																					codigo_islr = '".$codigo_concepto."'
																					where idretencion = '".$idretenciones."'
																					and idtipo_retencion = '".$idtipo_retencion."'");
		
		//ACTUALIZO NUMERO DE FACTURA Y FECHA
		$sql_actualiza_relacion_retencion = mysql_query("update relacion_retenciones_externas set numero_factura =  '".$numero_factura."',
																						numero_control = '".$numero_control."',
																						fecha_factura = '".$fecha_factura."',
																						numero_orden = '".$numero_orden_e."'
																					where idrelacion_retenciones_externas = '".$idrelacion_retenciones."'")or die(mysql_error());	

		//ACTUALIZO LOS DATOS EN EL CONTROL DE COMPROBANTES																						
		$sql_actualiza_comprobante = mysql_query("update comprobantes_retenciones set numero_retencion = '".$numero_comprobante."' 
																					where idretenciones = '".$idretenciones."'
																					and idtipo_retencion = '".$idtipo_retencion."'");																		
	}
	echo $numero_comprobante;
}


?>