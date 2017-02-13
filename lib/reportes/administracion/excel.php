<?php
$nombre_archivo = strtr($nombre_archivo, " ", "_"); $nombre_archivo=$nombre_archivo.".xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: filename=\"".$nombre_archivo."\";");
session_start();
set_time_limit(-1);
require('../../../conf/conex.php');
Conectarse();
extract($_GET);
extract($_POST);
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

//	----------------------------------------------------
$titulo="background-color:#999999; font-size:10px; font-weight:bold;";
$esp="font-size:10px;";
$total="font-size:10px; font-weight:bold;";
$cat="font-size:10px; font-weight:bold;";
$sql_config = mysql_query("select * from configuracion, estado where estado.idestado=configuracion.estado");
$config= mysql_fetch_array($sql_config);
?>

<?php
//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Ordenes de Pago...
	case "filtro_orden_pago":

		$filtro=""; $filtro_inner=""; $dbeneficiario=0; $dcategoria=0; $dperiodo=1; $dtipo=0; $darticulo=0; $destado=0; $head=0; $dfuente=0;
		////////////
		$mostrarPartidas = $_GET["chkpar"];
		$mostrarConcepto = $_GET["chkconcepto"];
		$categoria='';
		if ($_GET['idbeneficiario']!="") { $filtro=" AND (beneficiarios.idbeneficiarios='".$_GET['idbeneficiario']."') "; $dbeneficiario=1; $head=2; }

		if ($_GET['idcategoria']!="") {
			$filtro_inner = "INNER JOIN partidas_orden_pago ON (orden_pago.idorden_pago = partidas_orden_pago.idorden_pago)
  								INNER JOIN maestro_presupuesto ON (partidas_orden_pago.idmaestro_presupuesto = maestro_presupuesto.idRegistro)";
			$filtro.=" AND (orden_pago.idcategoria_programatica = '".$_GET['idcategoria']."'
							or
							(tipos_documentos.multi_categoria ='si'
								AND partidas_orden_pago.idorden_pago = orden_pago.idorden_pago
								AND maestro_presupuesto.idRegistro=partidas_orden_pago.idmaestro_presupuesto
								AND maestro_presupuesto.idcategoria_programatica='".$_GET['idcategoria']."')) ";
			$dcategoria=1;
			$sql_categoria = mysql_query("select unidad_ejecutora.denominacion, categoria_programatica.codigo from unidad_ejecutora,categoria_programatica
													where categoria_programatica.idcategoria_programatica = '".$_GET['idcategoria']."'
													and unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora");
			$bus_categoria = mysql_fetch_array($sql_categoria);
			$categoria = $bus_categoria["codigo"]." - ".$bus_categoria["denominacion"];
			if ($dbeneficiario==1) $head=2; else $head=1;
		}

		if ($_GET['idfuente_financiamiento']!='0') {
			$filtro.=" AND (orden_pago.idfuente_financiamiento = '".$_GET['idfuente_financiamiento']."') "; $dcategoria=1;
			$sql_fuente = mysql_query("select fuente_financiamiento.denominacion from fuente_financiamiento
													where fuente_financiamiento.idfuente_financiamiento = '".$_GET['idfuente_financiamiento']."'
													");
			$bus_fuente = mysql_fetch_array($sql_fuente);
			$fuente = $bus_fuente["denominacion"];
			if ($dfuente==1) $head=2; else $head=1;
		}
		if ($_GET['desde']!="" && $_GET['hasta']!="") {
			list($a, $m, $d)=SPLIT( '[/.-]', $_GET['desde']); $fecha_desde=$a."-".$m."-".$d;
			list($a, $m, $d)=SPLIT( '[/.-]', $_GET['hasta']); $fecha_hasta=$a."-".$m."-".$d;
			$filtro.=" AND (orden_pago.fecha_orden>='".$fecha_desde."' AND orden_pago.fecha_orden<='".$fecha_hasta."') "; $dperiodo=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['idmodulo']!="0") { 
			$sql_modulo=mysql_query("select nombre_modulo from modulo where id_modulo = '".$_GET["idmodulo"]."'");
			$bus_modulo=mysql_fetch_array($sql_modulo);
			$nombrem = $bus_modulo["nombre_modulo"];
			if ($nombrem == 'Compras y Servicios'){
				$sql_dependencia=mysql_query("select dependencias.denominacion from dependencias, configuracion_compras where dependencias.iddependencia = configuracion_compras.iddependencia");
			$bus_dependencia=mysql_fetch_array($sql_dependencia);
			$nombremodulo = $bus_dependencia["denominacion"];
			}
		}else{
			$nombremodulo = "";
		}
		if ($_GET['idtipo']!="0") {
			$filtro.=" AND (orden_pago.tipo ='".$_GET['idtipo']."') "; $dtipo=1;
			if ($dbeneficiario==1) $head=4; else $head=3;
		}else{
			if ($dbeneficiario==1) $head=4; else $head=3;
		}
		if ($_GET['idestado']!="0") {
			if ($_GET['idestado'] != 'procesadapagada'){
				if ($_GET['idestado'] == 'procesado'){
					$filtro.=" AND (orden_pago.estado='procesado') "; $destado=1;
					$estado='PROCESADA';
				}
				if ($_GET['idestado'] == 'conformado'){
					$filtro.=" AND (orden_pago.estado='conformado') "; $destado=1;
					$estado='CONFORMADA';
				}
				if ($_GET['idestado'] == 'devuelto'){
					$filtro.=" AND (orden_pago.estado='devuelto') "; $destado=1;
					$estado='DEVUELTA';
				}
				if ($_GET['idestado'] == 'anulado'){
					$filtro.=" AND (orden_pago.estado='anulado') "; $destado=1;
					$estado='ANULADA';
				}
				if ($_GET['idestado'] == 'pagada'){
					$filtro.=" AND (orden_pago.estado='pagada') "; $destado=1;
					$estado='PAGADA';
				}
			}else{
				$filtro.=" AND (orden_pago.estado='procesado' or orden_pago.estado='pagada') "; $destado=1;
				$estado='PROCESADA / PAGADA';
			}
		}


		if ($_GET['chksinafectacion']!="si") {
			$filtro.=" AND (tipos_documentos.causa!='no' and tipos_documentos.paga='no') ";
		}

		if ($_GET['chkanticipo']!="si") {

			$sql="SELECT
					orden_pago.idorden_pago,
					  orden_pago.codigo_referencia,
					  orden_pago.tipo,
					  orden_pago.numero_orden,
					  orden_pago.fecha_orden,
					  orden_pago.idcategoria_programatica,
					  orden_pago.exento,
					  orden_pago.sub_total,
					  orden_pago.total,
					  orden_pago.total_retenido,
					  orden_pago.total_a_pagar,
					  orden_pago.justificacion,
					  orden_pago.estado,
					  beneficiarios.nombre,
					  pagos_financieros.numero_cheque,
					  tipos_documentos.descripcion

				FROM
					orden_pago
					  INNER JOIN beneficiarios ON (orden_pago.idbeneficiarios=beneficiarios.idbeneficiarios)
					  INNER JOIN tipos_documentos ON (orden_pago.tipo=tipos_documentos.idtipos_documentos)
					  INNER JOIN fuente_financiamiento ON (orden_pago.idfuente_financiamiento = fuente_financiamiento.idfuente_financiamiento)
					  INNER JOIN pagos_financieros ON (orden_pago.idorden_pago=pagos_financieros.idorden_pago)
					  $filtro_inner
				WHERE
					orden_pago.idorden_pago<>''
					$filtro
				GROUP BY orden_pago.idorden_pago ORDER BY orden_pago.codigo_referencia";

		}else{
			$filtro.=" AND (orden_pago.anticipo ='si') ";
			$sql="SELECT
					orden_pago.idorden_pago,
					  orden_pago.codigo_referencia,
					  orden_pago.tipo,
					  orden_pago.numero_orden,
					  orden_pago.fecha_orden,
					  orden_pago.idcategoria_programatica,
					  orden_pago.exento,
					  orden_pago.sub_total,
					  orden_pago.total,
					  orden_pago.total_retenido,
					  orden_pago.total_a_pagar,
					  orden_pago.justificacion,
					  orden_pago.estado,
					  beneficiarios.nombre,
					  pagos_financieros.numero_cheque,
					  tipos_documentos.descripcion,
					  orden_pago.anticipo
				FROM 
					orden_pago
					  INNER JOIN beneficiarios ON (orden_pago.idbeneficiarios=beneficiarios.idbeneficiarios)
					  INNER JOIN tipos_documentos ON (orden_pago.tipo=tipos_documentos.idtipos_documentos)
					  INNER JOIN fuente_financiamiento ON (orden_pago.idfuente_financiamiento = fuente_financiamiento.idfuente_financiamiento)
					  INNER JOIN pagos_financieros ON (orden_pago.idorden_pago=pagos_financieros.idorden_pago)
					  $filtro_inner
				WHERE
					orden_pago.idorden_pago<>''
					$filtro
				GROUP BY orden_pago.idorden_pago ORDER BY orden_pago.codigo_referencia";
		}

		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//echo $sql;
		//---------------------------------------------------
		$total_bruto = 0;
		$total_deducciones = 0;
		$total_monto = 0;
		$sum_bruto = 0;
		$sum_deducciones = 0;
		$sum_total = 0;
		$nro_ordenes = 0;
		$monto = 0;
		$bruto = (float) 0;
		$sum_partidas_orden = 0;
		$entro1 = 0;

		echo "<table>";
		echo "<tr><td colspan='7' style='$cat'>REPUBLICA BOLIVARIANA DE VENEZUELA</td></tr>";
		echo "<tr><td colspan='7' style='$cat'>".$config["nombre_institucion"]."</td></tr>";
		echo "<tr><td colspan='7' style='$cat'>".$config["denominacion"]."</td></tr>";
		echo "<tr><td colspan='7' style='$cat'>".$config["rif"]."</td></tr>";
		echo "<tr><td colspan='7' style='$cat'></td></tr>";
		echo "<tr><td colspan='7' align='center' style='$total'>RELACION DE ORDENES DE PAGO</td></tr>";
		echo "<tr><td colspan='7' style='$cat'></td></tr>";

		if ($desde!="" && $hasta!="") {
			list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fecha_desde=$d."/".$m."/".$a;
			list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fecha_hasta=$d."/".$m."/".$a;
			echo "<tr><td colspan='7' style='$cat'>DESDE: ".$fecha_desde."     HASTA: ".$fecha_hasta."</td></tr>";
		}
		if($fuente != ''){
			echo "<tr><td colspan='7' style='$cat'>FUENTE DE FINANCIAMIENTO: ".$fuente."</td></tr>";
		}
		if($categoria != ''){
			echo "<tr><td colspan='7' style='$cat'>CATEGORIA PROGRAMATICA: ".$categoria."</td></tr>";
		}
		echo "</table>";
		echo "<table border='1'>";
		if ($_GET["chkfinanciero"]!="si"){
			echo "
			<tr>
				<td align='center' width='100' style='$titulo'>Nro. Orden</td>
				<td align='center' width='100' style='$titulo'>Fecha O/P</td>
				<td align='center' colspan=2 width='160' style='$titulo'>Beneficiario Proveedor</td>";
			if($mostrarConcepto == 'si'){
					echo "
					<td align='center' width='200' style='$titulo'>Concepto</td>";
				}
			echo "
				<td align='center' width='200' style='$titulo'>Monto Bruto</td>
				<td align='center' width='200' style='$titulo'>Retenido/Deducido</td>
				<td align='center' width='200' style='$titulo'>Total Pagado</td>
			</tr>";
		}else{
			echo "
			<tr>
				<td align='center' width='100' style='$titulo'>Nro. Orden</td>
				<td align='center' width='100' style='$titulo'>Fecha O/P</td>
				<td align='center' colspan=2 width='160' style='$titulo'>Beneficiario Proveedor</td>";
			if($mostrarConcepto == 'si'){
					echo "
					<td align='center' width='200' style='$titulo'>Concepto</td>";
				}
			echo "
				<td align='center' width='100' style='$titulo'>Doc. Financiero</td>
				<td align='center' width='200' style='$titulo'>Monto Bruto</td>
				<td align='center' width='200' style='$titulo'>Retenido/Deducido</td>
				<td align='center' width='200' style='$titulo'>Total Pagado</td>
			</tr>";
		}
		echo "</table>";
		echo "<table>";
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);

				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;

				if ($field['estado'] != "anulado") {
					if ($field['exento']==0 and $field['sub_total']==0){
						$bruto = $field['total'];
						$deducciones = 0;
						$total = $field['total_a_pagar'];
						$sum_bruto += $field['total'];
						$sum_deducciones += 0;
						$sum_total += $field['total_a_pagar'];
					}elseif ($field['exento']==0 and $field['sub_total']!=0){
						$bruto = $field['total'];
						$deducciones = $field['total_retenido'];
						$total = $field['total_a_pagar'];
						$sum_bruto += $field['total'];
						$sum_deducciones += $field['total_retenido'];
						$sum_total += $field['total_a_pagar'];
					}elseif ($field['exento']!=0 and $field['sub_total']!=0 and $field['total_retenido']==0){
						$bruto = $field['sub_total'];
						$deducciones = $field['exento'];
						$total = $field['total_a_pagar'];
						$sum_bruto += $field['sub_total'];
						$sum_deducciones += $field['excento'];
						$sum_total += $field['total_a_pagar'];
					}elseif ($field['exento']!=0 and $field['sub_total']==0){
						$bruto = $field['exento'];
						$deducciones = 0;
						$total = $field['total_a_pagar'];
						$sum_bruto += $field['exento'];
						$sum_deducciones += 0;
						$sum_total += $field['total_a_pagar'];
					}elseif ($field['exento']!=0 and $field['sub_total']!=0 and $field['total_retenido']!=0){
						$bruto = $field['total'];
						$deducciones = $field['total_retenido'];
						$total = $field['total_a_pagar'];
						$sum_bruto += $field['total'];
						$sum_deducciones += $field['total_retenido'];
						$sum_total += $field['total_a_pagar'];

					}
				}

				if ($_GET["chkfinanciero"]!="si"){
					echo "
					<tr>
						<td style='$esp'>".$field['numero_orden']."</td>
						<td align='center' style='$esp'>".$fecha."</td>
						<td style='$esp' colspan=2>".utf8_decode($field['nombre'])."</td>";
					if ($mostrarConcepto == 'si'){
						echo "
						<td style='$esp'>".utf8_decode($field['justificacion'])."</td>
						"; }
					echo "
						<td align='right' style='$esp'>".number_format($bruto, 2, ',', '.')."</td>
						<td align='right' style='$esp'>".number_format($deducciones, 2, ',', '.')."</td>
						<td align='right' style='$esp'>".number_format($total, 2, ',', '.')."</td>
					</tr>";
				}else{
					echo "
					<tr>
						<td style='$esp'>".$field['numero_orden']."</td>
						<td align='center' style='$esp'>".$fecha."</td>
						<td style='$esp' colspan=2>".utf8_decode($field['nombre'])."</td>";
					if ($mostrarConcepto == 'si'){
						echo "
						<td style='$esp'>".utf8_decode($field['justificacion'])."</td>
						"; }
					echo "
						<td style='$esp' align='center'>".$field['numero_cheque']."</td>
						<td align='right' style='$esp'>".number_format($bruto, 2, ',', '.')."</td>
						<td align='right' style='$esp'>".number_format($deducciones, 2, ',', '.')."</td>
						<td align='right' style='$esp'>".number_format($total, 2, ',', '.')."</td>
					</tr>";
				}
				$nro_ordenes++;
				/*
				if ($mostrarConcepto == 'si'){
					echo "
					<tr>
						<td align='center' style='$esp'></td>
						<td align='center' style='$esp'>CONCEPTO:</td>
						<td style='$esp' colspan=2>".utf8_decode($field['justificacion'])."</td>
						<td align='right' style='$esp'></td>
						<td align='right' style='$esp'></td>
						<td align='right' style='$esp'></td>
					</tr>";
				}
				*/
				if ($mostrarPartidas == 'si'){

					if ($_GET['idcategoria'] == ''){
						$sql_par = "select * from partidas_orden_pago where idorden_pago = '".$field["idorden_pago"]."'";
					}else{
						$sql_par ="select * from partidas_orden_pago,maestro_presupuesto
															where partidas_orden_pago.idorden_pago = '".$field["idorden_pago"]."'
															and maestro_presupuesto.idRegistro = partidas_orden_pago.idmaestro_presupuesto
															and maestro_presupuesto.idcategoria_programatica = '".$_GET['idcategoria']."'";
					}
					$sql_partidas = mysql_query($sql_par);
					while ($bus_partidas = mysql_fetch_array($sql_partidas)){
						$sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '".$bus_partidas['idmaestro_presupuesto']."'");
						$bus_maestro = mysql_fetch_array($sql_maestro);
						$sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_maestro['idclasificador_presupuestario']."'");
						$bus_clasificador = mysql_fetch_array($sql_clasificador);

						$codigopartida = $bus_clasificador['partida']."-".$bus_clasificador['generica']."-".$bus_clasificador['especifica']."-".$bus_clasificador['sub_especifica'];
						$denominacionpartida = utf8_decode($bus_clasificador['denominacion']);

						echo "
							<tr>
								<td align='right' colspan='2' style='$esp'> ".$codigopartida."</td>
								<td style='$esp'>".$denominacionpartida."</td>
								<td align='right' style='$esp'>".number_format($bus_partidas['monto'], 2, ',', '.')."</td>
							</tr>";
						$monto += $bus_partidas['monto'];
						$sum_partidas_orden += $bus_partidas['monto'];
					}
					$bruto2 = floatval($bruto);
					$sum_partidas_orden2 = floatval($sum_partidas_orden);
					//$resultado = floatval(number_format($sum_partidas_orden2,2, ',', '.') - number_format($bruto2,2, ',', '.'));
					$resultado = $sum_partidas_orden2 - $bruto2;
					$resultado2 = number_format($resultado,2);
					//$resultado = $sum_partidas_orden2 - $bruto2;
					if($resultado != 0 and $resultado > 0){
						echo "
							<tr>
								<td align='center' colspan='2' style='$cat'>ERROR: </td>
								<td style='$cat' colspan='2'>Diferencia entre Total Partidas y Monto Bruto de la Orden</td>
								<td align='right' style='$cat'>".number_format(($sum_partidas_orden2-$bruto2), 2, ',', '.')."</td>

							</tr>";
					}
						//<td align='right' style='$esp'>".var_dump($bruto2)." ".var_dump($sum_partidas_orden)."</td>
				}
				$sum_partidas_orden=0;

			}
			echo "</table>";
			if ($_GET["chkfinanciero"]!="si"){
				echo "<table border='1'>";
				echo "
						<tr>
							<td align='right' colspan=4 style='$total'>TOTALES: </td>
							<td align='right' style='$total'>".number_format($sum_bruto, 2, ',', '.')."</td>
							<td align='right' style='$total'>".number_format($sum_deducciones, 2, ',', '.')."</td>
							<td align='right' style='$total'>".number_format($sum_total, 2, ',', '.')."</td>
						</tr>";
				echo "</table>";
				echo "<table>";
				echo "<tr><td colspan='7' style='$cat'></td></tr>";
				echo "<tr><td colspan='7' style='$cat'></td></tr>";
				echo "
				<tr>
					<td align='right' colspan='2' style='$total'>Numero de Ordenes: $nro_ordenes</td>
				</tr>";
				if ($mostrarPartidas == 'si'){
					echo "
					<tr>
						<td align='right' colspan='2' style='$total'>Monto total partidas: ".number_format($monto, 2, ',', '.')."</td>
					</tr>";
				}
				echo "</table>";
			}else{
				echo "<table border='1'>";
				echo "
						<tr>
							<td align='right' colspan=5 style='$total'>TOTALES: </td>
							<td align='right' style='$total'>".number_format($sum_bruto, 2, ',', '.')."</td>
							<td align='right' style='$total'>".number_format($sum_deducciones, 2, ',', '.')."</td>
							<td align='right' style='$total'>".number_format($sum_total, 2, ',', '.')."</td>
						</tr>";
				echo "</table>";
				echo "<table>";
				echo "<tr><td colspan='8' style='$cat'></td></tr>";
				echo "<tr><td colspan='8' style='$cat'></td></tr>";
				echo "
				<tr>
					<td align='right' colspan='2' style='$total'>Numero de Ordenes: $nro_ordenes</td>
				</tr>";
				if ($mostrarPartidas == 'si'){
					echo "
					<tr>
						<td align='right' colspan='2' style='$total'>Monto total partidas: ".number_format($monto, 2, ',', '.')."</td>
					</tr>";
				}
				echo "</table>";
			}
		break;
}
?>