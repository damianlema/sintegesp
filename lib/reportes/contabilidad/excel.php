<?php
$nombre_archivo = strtr($nombre_archivo, " ", "_"); $nombre_archivo=$nombre_archivo.".xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: filename=\"".$nombre_archivo."\";");
session_start();
set_time_limit(-1);
extract($_GET);
extract($_POST);
ini_set("memory_limit", "200M");
require('../../../conf/conex.php');
require('../firmas.php');
Conectarse();

$ahora=date("d-m-Y H:i:s");
//	----------------------------------------------------
$nom_mes['01']="Enero";
$nom_mes['02']="Febrero";
$nom_mes['03']="Marzo";
$nom_mes['04']="Abril";
$nom_mes['05']="Mayo";
$nom_mes['06']="Junio";
$nom_mes['07']="Julio";
$nom_mes['08']="Agosto";
$nom_mes['09']="Septiembre";
$nom_mes['10']="Octubre";
$nom_mes['11']="Noviembre";
$nom_mes['12']="Diciembre";
//	----------------------------------------------------
$dias_mes['01']=31;
$dias_mes['03']=31;
$dias_mes['04']=30;
$dias_mes['05']=31;
$dias_mes['06']=30;
$dias_mes['07']=31;
$dias_mes['08']=31;
$dias_mes['09']=30;
$dias_mes['10']=31;
$dias_mes['11']=30;
$dias_mes['12']=31;

$tr1="background-color:#999999; font-size:12px;";
$tr2="font-size:12px; color:#000000; font-weight:bold;";
$tr3="background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
$tr4="background-color:#999950; font-size:12px; color:#000000; font-weight:bold;";
$tr5="font-size:12px;";
$tr6="background-color:#999999; font-size:12px; border:1px solid #000000;";
$tr7="font-size:12px; border:1px solid #000000;";

//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Libro Diario...
	case "contabilidad_libro_diario":
		
		//---------------------------------------------
		//---------------------------------------------
		$desde_inicial = $anio_fiscal.'-01-01';
		$mes_inicial = 1;
		if ($mes=='01'){
			$idesde = '01-01-'.$anio_fiscal; $ihasta = '31-01-'.$anio_fiscal;
			$desde = $anio_fiscal.'-01-01'; $hasta = $anio_fiscal.'-01-31'; $hasta_anterior = $anio_fiscal.'-01-31';
			$mes_final=1;
		}
		if ($mes=='02'){
		   $idesde = '01-02-'.$anio_fiscal; if ($anio_fiscal%4==0) $ihasta = '29-02-'.$anio_fiscal; else $ihasta = '28-02-'.$anio_fiscal;
		   $desde = $anio_fiscal.'-02-01'; if ($anio_fiscal%4==0) $hasta = $anio_fiscal.'-02-29'; else $hasta = $anio_fiscal.'-02-28';
			$hasta_anterior = $anio_fiscal.'-01-31';
			$mes_final=2; $mes_anterior=1;
		}
		if ($mes=='03'){
			$idesde = '01-03-'.$anio_fiscal; $ihasta = '31-03-'.$anio_fiscal;
			$desde = $anio_fiscal.'-03-01'; $hasta = $anio_fiscal.'-03-31';
				if ($anio_fiscal%4==0) $hasta_anterior = $anio_fiscal.'-02-29'; else $hasta_anterior = $anio_fiscal.'-02-28';
			$mes_final=3; $mes_anterior=2;
		}
		if ($mes=='04'){
			$idesde = '01-04-'.$anio_fiscal; $ihasta = '30-04-'.$anio_fiscal;
			$desde = $anio_fiscal.'-04-01'; $hasta = $anio_fiscal.'-04-30'; $hasta_anterior = $anio_fiscal.'-03-31';
			$mes_final=4; $mes_anterior=3;
		}
		if ($mes=='05'){
			$idesde = '01-05-'.$anio_fiscal; $ihasta = '31-05-'.$anio_fiscal;
			$desde = $anio_fiscal.'-05-01'; $hasta = $anio_fiscal.'-05-31'; $hasta_anterior = $anio_fiscal.'-04-30';
			$mes_final=5; $mes_anterior=4;
		}
		if ($mes=='06'){
			$idesde = '01-06-'.$anio_fiscal; $ihasta = '30-06-'.$anio_fiscal;
			$desde = $anio_fiscal.'-06-01'; $hasta = $anio_fiscal.'-06-30'; $hasta_anterior = $anio_fiscal.'-05-31';
			$mes_final=6; $mes_anterior=5;
		}
		if ($mes=='07'){
			$idesde = '01-07-'.$anio_fiscal; $ihasta = '31-07-'.$anio_fiscal;
			$desde = $anio_fiscal.'-07-01'; $hasta = $anio_fiscal.'-07-31'; $hasta_anterior = $anio_fiscal.'-06-30';
			$mes_final=7; $mes_anterior=6;
		}
		if ($mes=='08'){
			$idesde = '01-08-'.$anio_fiscal; $ihasta = '31-08-'.$anio_fiscal; 
			$desde = $anio_fiscal.'-08-01'; $hasta = $anio_fiscal.'-08-31'; $hasta_anterior = $anio_fiscal.'-07-31';
			$mes_final=8; $mes_anterior=7;
		}
		if ($mes=='09'){
			$idesde = '01-09-'.$anio_fiscal; $ihasta = '30-09-'.$anio_fiscal;
			$desde = $anio_fiscal.'-09-01'; $hasta = $anio_fiscal.'-09-30'; $hasta_anterior = $anio_fiscal.'-08-31';
			$mes_final=9; $mes_anterior=8;
		}
		if ($mes=='10'){
			$idesde = '01-10-'.$anio_fiscal; $ihasta = '31-10-'.$anio_fiscal;
			$desde = $anio_fiscal.'-10-01'; $hasta = $anio_fiscal.'-10-31'; $hasta_anterior = $anio_fiscal.'-09-30';
			$mes_final=10; $mes_anterior=9;
		}
		if ($mes=='11'){
			$idesde = '01-11-'.$anio_fiscal; $ihasta = '30-11-'.$anio_fiscal;
			$desde = $anio_fiscal.'-11-01'; $hasta = $anio_fiscal.'-11-30'; $hasta_anterior = $anio_fiscal.'-10-31';
			$mes_final=11; $mes_anterior=10;
		}
		if ($mes=='12'){
			$idesde = '01-12-'.$anio_fiscal; $ihasta = '31-12-'.$anio_fiscal;
			$desde = $anio_fiscal.'-12-01'; $hasta = $anio_fiscal.'-12-31'; $hasta_anterior = $anio_fiscal.'-11-30';
			$mes_final=12; $mes_anterior=11;
		}

		echo "
			<table>
				<tr><td colspan='7' style='$tr3'>LIBRO DIARIO</td></tr>
				<tr><td colspan='7'>Desde: ".$idesde." Hasta: ".$ihasta."</td></tr>
				<tr><td colspan='7'>A&ntilde;o Fiscal: ".$anio_fiscal."</td></tr>
				<tr><td colspan='7'>&nbsp;</td></tr>
				<tr><td colspan='7'>&nbsp;</td></tr>
			</table>
			<table border='1'>
				<tr>
					<td width='100' style='$tr1'>FECHA</td>
					<td width='1000' style='$tr1'>DESCRIPCION DEL ASIENTO</td>
					<td width='200' style='$tr1'>SUBDIVISION ESTADISTICA</td>
					<td width='200' style='$tr1'>DIVISION ESTADISTICA</td>
					<td width='200' style='$tr1'>SUBCUENTA</td>
					<td width='200' style='$tr1'>DEBE</td>
					<td width='200' style='$tr1'>HABER</td>
				</tr>
			</table>";

			//	--------------------
			$i = 0;
			$sql = "SELECT ac.*, 
							cac.*
					FROM 
							asiento_contable ac
							INNER JOIN cuentas_asiento_contable cac ON (ac.idasiento_contable = cac.idasiento_contable)
					WHERE 
							(ac.fecha_contable >= '".$desde."' AND ac.fecha_contable <= '".$hasta."')
							AND (ac.estado = 'procesado' or ac.estado = 'anulado')
					ORDER BY ac.fecha_contable, ac.prioridad, ac.idasiento_contable, cac.afecta, cac.tabla";
			//echo $sql;
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$sw=0;
			while ($field = mysql_fetch_array($query)) {
				if ($sw != $field["idasiento_contable"]){
					$i++;
					echo "
						<table border='1'>
							<tr>
								<td width='100' style='$tr4'>&nbsp;</td>
								<td width='1000' style='$tr4' align='center'>ASIENTO No. ".$i."</td>
								<td width='200' style='$tr4'>&nbsp;</td>
								<td width='200' style='$tr4'>&nbsp;</td>
								<td width='200' style='$tr4'>&nbsp;</td>
								<td width='200' style='$tr4'>&nbsp;</td>
								<td width='200' style='$tr4'>&nbsp;</td>
							</tr>
						</table>";
					list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_contable"]); $fecha=$d."/".$m."/".$a;
					echo "
						<table border='1'>
							<tr>
								<td width='100' style='$tr5'>".$fecha."</td>
								<td width='1000' style='$tr5'>".utf8_decode($field["detalle"])."</td>
								<td width='200' style='$tr5'>&nbsp;</td>
								<td width='200' style='$tr5'>&nbsp;</td>
								<td width='200' style='$tr5'>&nbsp;</td>
								<td width='200' style='$tr5'>&nbsp;</td>
								<td width='200' style='$tr5'>&nbsp;</td>
							</tr>
						</table>";
					$sw = $field["idasiento_contable"];
					$imprime_rubro = 'no';
				}
				$idcampo = "id".$field["tabla"];
				$sql_cuentas = mysql_query("select * from ".$field["tabla"]." 
																		where ".$idcampo." = '".$field["idcuenta"]."'")or die(" tablas ".mysql_error());
				$bus_cuenta = mysql_fetch_array($sql_cuentas);

				if($field["tabla"] == 'cuenta_cuentas_contables'){
					$sql_suma_rubro = mysql_query("SELECT SUM(monto) as monto, idasiento_contable, afecta, idcuenta, tabla
											  FROM cuentas_asiento_contable cac
												INNER JOIN cuenta_cuentas_contables ccc ON (cac.idcuenta = ccc.idcuenta_cuentas_contables)
												WHERE cac.idasiento_contable = '".$field["idasiento_contable"]."'
											  AND cac.tabla = 'cuenta_cuentas_contables'
											GROUP BY ccc.idrubro, cac.afecta");

					$bus_suma_rubro = mysql_fetch_array($sql_suma_rubro);

					$sql_rubro = mysql_query("select * from rubro_cuentas_contables
																		where idrubro_cuentas_contables = '".$bus_cuenta["idrubro"]."'")or die(" tablas ".mysql_error());
					$bus_rubro = mysql_fetch_array($sql_rubro);
					if($field["afecta"] == 'debe'){
						$monto_debe =  number_format($bus_suma_rubro["monto"], 2, ',', '.');
						$monto_haber = '';
					}else{
						$monto_haber =  number_format($bus_suma_rubro["monto"], 2, ',', '.');
						$monto_debe = '';
					}
					$monto_cuenta = number_format($field["monto"], 2, ',', '.');

					if($imprime_rubro == 'no'){
						echo "
						<table border='1'>
							<tr>
								<td width='100' style='$tr5'>&nbsp;</td>
								<td width='1000' style='$tr5'>".utf8_decode($bus_rubro["codigo"]).' - '.utf8_decode($bus_rubro["denominacion"])."</td>
								<td width='200' style='$tr5'>&nbsp;</td>
								<td width='200' style='$tr5'>&nbsp;</td>
								<td width='200' style='$tr5'>&nbsp;</td>
								<td width='200' style='$tr5' align='right'>".$monto_debe."</td>
								<td width='200' style='$tr5' align='right'>".$monto_haber."</td>
							</tr>
						</table>";						
						$imprime_rubro = 'si';
					}
					echo "
						<table border='1'>
							<tr>
								<td width='100' style='$tr5'>&nbsp;</td>
								<td width='1000' style='$tr5'>&nbsp;&nbsp;&nbsp;&nbsp;".utf8_decode($bus_cuenta["codigo"]).' - '.utf8_decode($bus_cuenta["denominacion"])."</td>
								<td width='200' style='$tr5'>&nbsp;</td>
								<td width='200' style='$tr5'>&nbsp;</td>
								<td width='200' style='$tr5' align='right'>".$monto_cuenta."</td>
								<td width='200' style='$tr5'>&nbsp;</td>
								<td width='200' style='$tr5'>&nbsp;</td>
							</tr>
						</table>";
				}
				if($field["tabla"] == 'rubro_cuentas_contables'){
					if($field["afecta"] == 'debe'){
						$monto_debe =  number_format($field["monto"], 2, ',', '.');
						$monto_haber = '';
					}else{
						$monto_haber =  number_format($field["monto"], 2, ',', '.');
						$monto_debe = '';
					}
					
					echo "
						<table border='1'>
							<tr>
								<td width='100' style='$tr5'>&nbsp;</td>
								<td width='1000' style='$tr5'>".utf8_decode($bus_cuenta["codigo"]).' - '.utf8_decode($bus_cuenta["denominacion"])."</td>
								<td width='200' style='$tr5'>&nbsp;</td>
								<td width='200' style='$tr5'>&nbsp;</td>
								<td width='200' style='$tr5'>&nbsp;</td>
								<td width='200' style='$tr5' align='right'>".$monto_debe."</td>
								<td width='200' style='$tr5' align='right'>".$monto_haber."</td>
							</tr>
						</table>";

				}
				
				//	Fin Imprimo Montos...
			}



		break;
}
?>