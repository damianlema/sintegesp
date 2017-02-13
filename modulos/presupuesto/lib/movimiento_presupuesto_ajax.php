<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);


if($ejecutar == "recalcularTotalRectificacion"){
	$sql_rectificacion = mysql_query("select sum(monto_acreditar) as total_receptora from partidas_receptoras_rectificacion
																					where idrectificacion_presupuesto = '".$id."'")or die(mysql_error());
	
	$bus_rectificacion = mysql_fetch_array($sql_rectificacion);
	$monto_total = $bus_rectificacion["total_receptora"];
	
	$sql_actualiza_rectificacion = mysql_query("update rectificacion_presupuesto set total_credito = '".$monto_total."' 
																				where idrectificacion_presupuesto = '".$id."'");

	echo number_format($monto_total,2,",",".");

}


if($ejecutar == "recalcularTotalCredito"){
	$sql_credito = mysql_query("select sum(monto_acreditar) as total_credito from partidas_credito_adicional
																					where idcredito_adicional = '".$id."'")or die(mysql_error());
	
	$bus_credito = mysql_fetch_array($sql_credito);
	$monto_total = $bus_credito["total_credito"];
	
	$sql_actualiza_credito = mysql_query("update creditos_adicionales set total_credito = '".$monto_total."' 
																				where idcreditos_adicionales = '".$id."'");

	echo number_format($monto_total,2,",",".");

}


if($ejecutar == "recalcularTotalDisminucion"){
	$sql_disminucion = mysql_query("select sum(monto_debitar) as total_debito from partidas_disminucion_presupuesto
																					where iddisminucion_presupuesto = '".$id."'")or die(mysql_error());
	
	$bus_disminucion = mysql_fetch_array($sql_disminucion);
	$monto_total = $bus_disminucion["total_debito"];
	
	$sql_actualiza_disminucion = mysql_query("update disminucion_presupuesto set total_disminucion = '".$monto_total."' 
																				where iddisminucion_presupuesto = '".$id."'");

	echo number_format($monto_total,2,",",".");

}

if($ejecutar == "recalcularTotalTrasladoI"){
	$sql_aumento = mysql_query("select sum(monto_acreditar) as total_credito from partidas_receptoras_traslado
																					where idtraslados_presupuestarios = '".$id."'")or die(mysql_error());
	
	$bus_aumento = mysql_fetch_array($sql_aumento);
	$monto_total = $bus_aumento["total_credito"];
	
	$sql_actualiza_credito = mysql_query("update traslados_presupuestarios set total_credito = '".$monto_total."' 
																				where idtraslados_presupuestarios = '".$id."'");

	echo number_format($monto_total,2,",",".");

}

if($ejecutar == "recalcularTotalTrasladoD"){
	$sql_disminucion = mysql_query("select sum(monto_debitar) as total_debito from partidas_cedentes_traslado
																					where idtraslados_presupuestarios = '".$id."'")or die(mysql_error());
	
	$bus_disminucion = mysql_fetch_array($sql_disminucion);
	$monto_total = $bus_disminucion["total_debito"];
	
	$sql_actualiza_disminucion = mysql_query("update traslados_presupuestarios set total_debito = '".$monto_total."' 
																				where idtraslados_presupuestarios = '".$id."'");

	echo number_format($monto_total,2,",",".");

}

if($ejecutar == "recalcularPartidaTrasladoCedente"){
//echo "update partidas_cedentes_traslado set monto_debitar =  '".$monto."' 
//																					where idtraslados_presupuestarios = '".$id_traslado."' 
//																					and idmaestro_presupuesto = '".$id_partida."'";
	$sql_disminucion = mysql_query("update partidas_cedentes_traslado set monto_debitar =  '".$monto."' 
																					where idtraslados_presupuestarios = '".$id_traslado."' 
																					and idmaestro_presupuesto = '".$id_partida."'")or die(mysql_error("ERROR AJUSTANDO CEDENTE TRASLADO"));

}

if($ejecutar == "recalcularPartidaTrasladoReceptora"){
//echo "update partidas_receptoras_traslado set monto_acreditar =  '".$monto."' 
//																					where idtraslados_presupuestarios = '".$id_traslado."' 
//																					and idmaestro_presupuesto = '".$id_partida."'";
	$sql_aumento = mysql_query("update partidas_receptoras_traslado set monto_acreditar =  '".$monto."' 
																					where idtraslados_presupuestarios = '".$id_traslado."' 
																					and idmaestro_presupuesto = '".$id_partida."'")or die(mysql_error("ERROR AJUSTANDO RECEPTORA TRASLADO"));

}


if($ejecutar == "recalcularPartidaCredito"){
//echo "update partidas_credito_adicional set monto_acreditar =  '".$monto."' 
//																					where idcredito_adicional = '".$id_credito."' 
//																					and idmaestro_presupuesto = '".$id_partida."'"
	$sql_credito = mysql_query("update partidas_credito_adicional set monto_acreditar =  '".$monto."' 
																					where idcredito_adicional = '".$id_credito."' 
																					and idmaestro_presupuesto = '".$id_partida."'")or die(mysql_error("ERROR AJUSTANDO credito adicional"));

}

if($ejecutar == "recalcularPartidaDisminucion"){
/*echo "update partidas_disminucion_presupuesto set monto_debitar =  '".$monto."' 
																					where iddisminucion_presupuesto = '".$id_disminucion."' 
																					and idmaestro_presupuesto = '".$id_partida."'";
*/
	$sql_credito = mysql_query("update partidas_disminucion_presupuesto set monto_debitar =  '".$monto."' 
																					where iddisminucion_presupuesto = '".$id_disminucion."' 
																					and idmaestro_presupuesto = '".$id_partida."'")or die(mysql_error("ERROR AJUSTANDO disminucion de presupuesto"));

}


if($ejecutar == "recalcularPartidaRectificada"){
/*echo "update partidas_receptoras_rectificacion set monto_debitar =  '".$monto."' 
																					where idrectificacion_presupuesto = '".$id_rectificacion."' 
																					and idmaestro_presupuesto = '".$id_partida."'";
*/																					
	$sql_credito = mysql_query("update partidas_receptoras_rectificacion set monto_acreditar =  '".$monto."' 
																					where idrectificacion_presupuesto = '".$id_rectificacion."' 
																					and idmaestro_presupuesto = '".$id_partida."'")or die(mysql_error("ERROR AJUSTANDO rectificacion de presupuesto"));

}


?>