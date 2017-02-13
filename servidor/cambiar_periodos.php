<?
session_start();
set_time_limit(-1);
//include ("../ClassSQLimporter.php");
include("../conf/conex.php");
Conectarse();
$anio_a_cambiar='2016';

			$sql_actualizar_tipos_Documentos = mysql_query("update tipos_documentos 
														   set nro_contador = 1 ");

			$sql_rango_periodos_nomina = mysql_query("select * from rango_periodo_nomina");
			while($bus_consulta = mysql_fetch_array($sql_rango_periodos_nomina)){
				list($anioDesde, $mesDesde, $diaDesde) = explode("-", $bus_consulta["desde"]);
				$nuevoDesde = $anio_a_cambiar.'-'.$mesDesde.'-'.$diaDesde;
				list($anioHasta, $mesHasta, $diaHasta) = explode("-", $bus_consulta["hasta"]);
				$nuevoHasta = $anio_a_cambiar.'-'.$mesHasta.'-'.$diaHasta;
				list($anioSugiere, $mesSugiere, $diaSugiere) = explode("-", $bus_consulta["sugiere_pago"]);
				$nuevoSugiere = $anio_a_cambiar.'-'.$mesSugiere.'-'.$diaSugiere;

				$sql_actualizar_rango = mysql_query("update `rango_periodo_nomina` 
																set 
																desde = '".$nuevoDesde."',
																hasta = '".$nuevoHasta."',
																sugiere_pago = '".$nuevoSugiere."'
														where
															idrango_periodo_nomina = '".$bus_consulta["idrango_periodo_nomina"]."'")
														or die(" actualiza rango ".mysql_error());
			}
			

			$sql_periodos_nomina = mysql_query("select * from periodos_nomina");
			while($bus_consulta = mysql_fetch_array($sql_periodos_nomina)){
				list($anioDesde, $mesDesde, $diaDesde) = explode("-", $bus_consulta["fecha_inicio"]);
				$nuevoDesde = $anio_a_cambiar.'-'.$mesDesde.'-'.$diaDesde;
				
				$sql_actualizar = mysql_query("update `periodos_nomina` 
																set 
																fecha_inicio = '".$nuevoDesde."',
																anio = '".$anio_a_cambiar."'
														where
															idperiodos_nomina = '".$bus_consulta["idperiodos_nomina"]."'")
														or die(" actualiza periodo ".mysql_error());
			}
			
			
		

// HAGO UNA COPIA DE LA CARPETA GESTION

?>