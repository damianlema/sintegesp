<?
include("conf/conex.php");
Conectarse();
$i=1;
$sql_consulta = mysql_query("SELECT * FROM orden_compra_servicio WHERE tipo =204");

while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$sql_beneficiario = mysql_query("SELECT * FROM beneficiarios WHERE idbeneficiarios ='".$bus_consulta["idbeneficiarios"]."'");
	$bus_beneficiario = mysql_fetch_array($sql_beneficiario);
	echo " Cedula RIF ".$bus_beneficiario["rif"]." ".$bus_beneficiario["nombre"]." ";
	$letra = substr($bus_beneficiario["rif"],0,1);
	echo $letra;
	if ( $letra == "V"){
		$rif = substr($bus_beneficiario["rif"],2,8);
		echo " CEDULA ".$rif."  ";
		$sql_trabajador = mysql_query("SELECT * FROM trabajador WHERE cedula ='".$rif."'");
	}else{
		$sql_trabajador = mysql_query("SELECT * FROM trabajador WHERE cedula ='".$bus_beneficiario["rif"]."'");
	}
	$bus_trabajador = mysql_fetch_array($sql_trabajador);

	echo " Trabajador ".$bus_trabajador["apellidos"]." ".$bus_trabajador["nombres"]." ";
	$sql_centro = mysql_query("SELECT * FROM niveles_organizacionales WHERE idniveles_organizacionales ='".$bus_trabajador["centro_costo"]."'");
	$bus_centro = mysql_fetch_array($sql_centro);
	
	$idcategoria = $bus_centro["idcategoria_programatica"];
	echo " Categoria Correcta ".$idcategoria."</br>";
	$partidas_certificacion = mysql_query("SELECT * FROM partidas_orden_compra_servicio WHERE idorden_compra_servicio ='".$bus_consulta["idorden_compra_servicio"]."'");
	while($bus_partida = mysql_fetch_array($partidas_certificacion)){
		$sql_maestro = mysql_query("SELECT * FROM maestro_presupuesto WHERE idRegistro ='".$bus_partida["idmaestro_presupuesto"]."'");
		$bus_maestro = mysql_fetch_array($sql_maestro);
		if($bus_maestro["idcategoria_programatica"] != $idcategoria){
			echo " Categoria Orden ".$bus_maestro["idcategoria_programatica"]."</br>";
			$sql_maestro_correcto = mysql_query("SELECT * FROM maestro_presupuesto WHERE idclasificador_presupuestario ='".$bus_maestro["idclasificador_presupuestario"]."'
																			and idcategoria_programatica ='".$idcategoria."'
																			and idfuente_financiamiento ='".$bus_maestro["idfuente_financiamiento"]."'");
			$bus_maestro_correcto = mysql_fetch_array($sql_maestro_correcto);
			$idpartida_correcta = $bus_maestro_correcto["idRegistro"];
			
			$sql_update = mysql_query("update partidas_orden_compra_servicio set idmaestro_presupuesto = '".$idpartida_correcta."' 
														where idpartidas_orden_compra_servicio = '".$bus_partida["idpartidas_orden_compra_servicio"]."'");
			
			$sql_update = mysql_query("update articulos_compra_servicio set idcategoria_programatica = '".$idcategoria."' 
														where idorden_compra_servicio = '".$bus_consulta["idorden_compra_servicio"]."'");

			$sql_rop = mysql_query("select * from relacion_pago_compromisos where idorden_compra_servicio = '".$bus_consulta["idorden_compra_servicio"]."'");
			$bus_rop = mysql_fetch_array($sql_rop);

			$sql_update_op = mysql_query("update partidas_orden_pago set idmaestro_presupuesto = '".$idpartida_correcta."' 
														where idorden_pago = '".$bus_rop["idorden_pago"]."'
														and idmaestro_presupuesto = '".$bus_partida["idmaestro_presupuesto"]."'");
			
		}
	}
	echo "<BR>";
}

?>