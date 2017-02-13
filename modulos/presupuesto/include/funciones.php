<?php 
include_once("../../../conf/conex.php");
include_once("../../../lib/registra.php");
include("../../../lib/botones.php");
$conexion_db=conectarse();

// *********************************************************************************************************************************
// funcion para llenar la grilla con las partidas del credito adicional
function llena_grilla($id){

	$sql_partidas_credito_adicional=mysql_query("select clasificador_presupuestario.codigo_cuenta as codigopartida,
														clasificador_presupuestario.partida as partida,
														clasificador_presupuestario.generica as generica,
														clasificador_presupuestario.especifica as especifica,
														clasificador_presupuestario.sub_especifica as sub_especifica,
														clasificador_presupuestario.denominacion as denopartida,
														categoria_programatica.codigo as codigocategoria,
														unidad_ejecutora.denominacion as denocategoriaprogramatica,
														maestro_presupuesto.idregistro as idmaestro_presupuesto,
														partidas_credito_adicional.idcredito_adicional,
														partidas_credito_adicional.idmaestro_presupuesto,
														partidas_credito_adicional.idpartida_credito_adicional,
														partidas_credito_adicional.monto_acreditar
														from partidas_credito_adicional,clasificador_presupuestario,categoria_programatica, maestro_presupuesto, unidad_ejecutora
													where partidas_credito_adicional.status='a'
															and partidas_credito_adicional.idcredito_adicional=".$id."
															and maestro_presupuesto.idRegistro=partidas_credito_adicional.idmaestro_presupuesto
															and clasificador_presupuestario.idclasificador_presupuestario=maestro_presupuesto.idclasificador_presupuestario
															and categoria_programatica.idcategoria_programatica=maestro_presupuesto.idcategoria_programatica
															and unidad_ejecutora.idunidad_ejecutora=categoria_programatica.idunidad_ejecutora"
															,$conexion_db);
														
	if (mysql_num_rows($sql_partidas_credito_adicional)>0)
		{
			$existen_partidas=true;
		}
	$entro="si";
}	
?>