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

$sql_config = mysql_query("select * from configuracion, estado where estado.idestado=configuracion.estado");
$config= mysql_fetch_array($sql_config);

?>

<?php
//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Relacion de Retenciones...
	case "relacion_retenciones":
		$tr1="background-color:#999999; font-size:12px;";
		$tr2="font-size:12px; color:#000000; font-weight:bold;";
		$tr3="background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
		$tr4="font-size:12px; color:#000000; font-weight:bold;";
		$tr5="font-size:12px; color:#000000;";
		//----------------------------------------------------
		if ($_GET["desde"]!="" && $_GET["hasta"]!="") {
			list($a, $m, $d)=SPLIT( '[/.-]', $_GET["desde"]); $desde=$d."/".$m."/".$a;	
			list($a, $m, $d)=SPLIT( '[/.-]', $_GET["hasta"]); $hasta=$d."/".$m."/".$a;
			$periodo="DEL $desde AL $hasta";
			$filtro_periodo=" AND (retenciones.fecha_retencion>='".$_GET['desde']."' AND retenciones.fecha_retencion<='".$_GET['hasta']."')";
		} else $periodo="";
		
		$sql="SELECT descripcion FROM tipo_retencion WHERE nombre_comprobante='".$_GET["idtipo_retencion"]."' GROUP BY nombre_comprobante";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$tipo_retencion=$field["descripcion"];
		//----------------------------------------------------
		echo "
		<table border='1'>
			<tr><td>Tipo de Retenci&oacute;n:</td><td style='$tr4' colspan='7'>$tipo_retencion</td></tr>";
			if ($periodo!="") echo "<tr><td>Periodo:</td><td style='$tr4' colspan='7'>$periodo</td></tr>";
			echo "
			<tr>
				<th width='150' style='$tr1'>Estado</th>
				<th width='100' style='$tr1'>Nro. Comprobante</th>
				<th width='100' style='$tr1'>Nro. Orden</th>
				<th width='100' style='$tr1'>F. Orden</th>
				<th width='350' style='$tr1'>Beneficiario</th>
				<th width='100' style='$tr1'>Nro. Factura</th>
				<th width='100' style='$tr1'>Nro. Control</th>
				<th width='100' style='$tr1'>F. Factura</th>
				<th width='200' style='$tr1'>% Total Retenido</th>
			</tr>";
		//----------------------------------------------------
		$sql="(SELECT 
					retenciones.idretenciones,
					retenciones.fecha_retencion, 
					retenciones.numero_factura, 
					retenciones.numero_control, 
					retenciones.fecha_factura, 
					retenciones.estado, 
					orden_compra_servicio.numero_orden, 
					orden_compra_servicio.fecha_orden, 
					beneficiarios.nombre AS Beneficiario, 
					relacion_retenciones.monto_retenido,  
					relacion_retenciones.numero_retencion, 
					tipo_retencion.nombre_comprobante 
				FROM 
					retenciones 
					INNER JOIN orden_compra_servicio ON (retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio) 
					INNER JOIN beneficiarios ON (orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios) 
					INNER JOIN relacion_retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones) 
					INNER JOIN tipo_retencion ON (relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion) 
				WHERE 
					(tipo_retencion.nombre_comprobante='".$_GET["idtipo_retencion"]."') $filtro_periodo)
					
				UNION 
				
				(SELECT 
					relacion_retenciones_externas.idrelacion_retenciones_externas as idretenciones,
					relacion_retenciones_externas.fecha_factura as fecha_retencion,
					relacion_retenciones_externas.numero_factura, 
					relacion_retenciones_externas.numero_control, 
					relacion_retenciones_externas.fecha_factura,
					'externa' AS estado, 
					relacion_retenciones_externas.numero_orden, 
					relacion_retenciones_externas.fecha_factura as fecha_orden, 
					beneficiarios.nombre AS Beneficiario, 
					relacion_retenciones_externas.monto_retenido, 
					relacion_retenciones_externas.numero_retencion, 
					tipo_retencion.nombre_comprobante 
				FROM 
					retenciones  
					INNER JOIN beneficiarios ON (retenciones.idbeneficiarios=beneficiarios.idbeneficiarios) 
					INNER JOIN relacion_retenciones_externas ON (retenciones.idretenciones=relacion_retenciones_externas.idretencion) 
					INNER JOIN tipo_retencion ON (relacion_retenciones_externas.idtipo_retencion=tipo_retencion.idtipo_retencion) 
				WHERE 
					(tipo_retencion.nombre_comprobante='".$_GET["idtipo_retencion"]."') $filtro_periodo) 
					
				ORDER BY idretenciones, fecha_orden";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			list($anio, $mes, $dia)=SPLIT( '[/.-]', $field["fecha_retencion"]);
			if ($field['numero_retencion']==0){ $nro_comprobante="";
			}else{
				// $nro_comprobante=(string) $anio.$mes.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				if ((8-strlen($field['numero_retencion'])) > 0){
					$nro_comprobante=(string) $anio.$mes.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				}else{
					$nro_comprobante=(string) $anio.$mes.$field['numero_retencion'];
				}
			}
			$retenido=number_format($field["monto_retenido"], 2, ',', '');
			$suma_retenido+=$field["monto_retenido"];
			list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_orden"]); $forden=$d."/".$m."/".$a;	
			list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_factura"]); $ffactura=$d."/".$m."/".$a;
			$estado=strtoupper($field["estado"]);
			//
			echo "
			<tr>
				<td style='$tr5'>".$estado."</td>
				<td style='$tr5' align='center'>=TEXTO(".$nro_comprobante."; \"0\")</td>
				<td style='$tr5'>".$field['numero_orden']."</td>
				<td style='$tr5' align='center'>".$forden."</td>
				<td style='$tr5'>".utf8_decode($field["Beneficiario"])."</td>
				<td style='$tr5'>".$field["numero_factura"]."</td>
				<td style='$tr5'>".$field["numero_control"]."</td>
				<td style='$tr5' align='center'>".$ffactura."</td>
				<td style='$tr5' align='right'>=DECIMAL(".$retenido."; 2)</td>
			</tr>";
		}
		$suma_retenido=number_format($suma_retenido, 2, ',', '');
		echo "
			<tr>
				<td colspan='7' width='1000' align='right' style='$tr2'>Total</th>
				<td width='200' style='$tr2' align='right'>=DECIMAL(".$suma_retenido."; 2)</th>
			</tr>
		</table>";
		break;
	
	//	Relacion por Beneficiarios...
	case "retenciones_beneficiario":
		$tr1="background-color:#999999; font-size:12px;";
		$tr2="font-size:12px; color:#000000; font-weight:bold;";
		$tr3="background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
		$tr4="font-size:12px; color:#000000; font-weight:bold;";
		$tr5="font-size:12px; color:#000000;";
		//----------------------------------------------------
		if ($_GET["desde"]!="" && $_GET["hasta"]!="") {
			list($a, $m, $d)=SPLIT( '[/.-]', $_GET["desde"]); $desde=$d."/".$m."/".$a;	
			list($a, $m, $d)=SPLIT( '[/.-]', $_GET["hasta"]); $hasta=$d."/".$m."/".$a;
			$periodo="DEL $desde AL $hasta";
			$filtro_periodo=" AND (retenciones.fecha_retencion>='".$_GET["desde"]."' AND retenciones.fecha_retencion<='".$_GET["hasta"]."')";
		} else $periodo="";
		if ($estado!="0") $filtro_estado=" AND retenciones.estado='".$estado."'";
		$sql="SELECT descripcion FROM tipo_retencion WHERE nombre_comprobante='".$_GET["idtipo_retencion"]."' GROUP BY nombre_comprobante";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$tipo_retencion=$field["descripcion"];
		
		$sql="SELECT nombre FROM beneficiarios WHERE idbeneficiarios='".$_GET["idbeneficiario"]."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$beneficiario=$field["nombre"];
		//----------------------------------------------------
		echo "
		<table border='1'>
			<tr><td colspan='2'>Beneficiario:</td><td style='$tr4' colspan='5'>$beneficiario</td></tr>
			<tr><td colspan='2'>Tipo de Retenci&oacute;n:</td><td style='$tr4' colspan='5'>$tipo_retencion</td></tr>";
			if ($periodo!="") echo "<tr><td colspan='2'>Periodo:</td><td style='$tr4' colspan='5'>$periodo</td></tr>";
			echo "
			<tr>
				<th width='100' style='$tr1'>Nro. Orden</th>
				<th width='100' style='$tr1'>F. Orden</th>
				<th width='100' style='$tr1'>Nro. Factura</th>
				<th width='100' style='$tr1'>Nro. Control</th>
				<th width='100' style='$tr1'>F. Factura</th>
				<th width='150' style='$tr1'>Estado</th>
				<th width='200' style='$tr1'>% Total Retenido</th>
			</tr>";
		//----------------------------------------------------
		$sql="(SELECT 
					retenciones.idretenciones,
					retenciones.fecha_retencion,
					retenciones.fecha_aplicacion_retencion,
					retenciones.numero_factura, 
					retenciones.numero_control, 
					retenciones.fecha_factura, 
					retenciones.estado, 
					orden_compra_servicio.numero_orden, 
					orden_compra_servicio.fecha_orden, 
					beneficiarios.nombre AS Beneficiario, 
					relacion_retenciones.monto_retenido, 
					tipo_retencion.nombre_comprobante 
				FROM 
					retenciones 
					INNER JOIN orden_compra_servicio ON (retenciones.iddocumento=orden_compra_servicio.idorden_compra_servicio) 
					INNER JOIN beneficiarios ON (orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios) 
					INNER JOIN relacion_retenciones ON (retenciones.idretenciones=relacion_retenciones.idretenciones) 
					INNER JOIN tipo_retencion ON (relacion_retenciones.idtipo_retencion=tipo_retencion.idtipo_retencion) 
				WHERE 
					(tipo_retencion.nombre_comprobante='".$_GET["idtipo_retencion"]."') AND (beneficiarios.idbeneficiarios='".$_GET["idbeneficiario"]."') $filtro_periodo $filtro_estado)
					
				UNION 
				
				(SELECT 
					relacion_retenciones_externas.idrelacion_retenciones_externas as idretenciones,
					relacion_retenciones_externas.fecha_factura as fecha_retencion,
					relacion_retenciones_externas.fecha_factura as fecha_aplicacion_retencion,
					relacion_retenciones_externas.numero_factura, 
					relacion_retenciones_externas.numero_control, 
					relacion_retenciones_externas.fecha_factura, 
					'EXTERNA' AS estado, 
					relacion_retenciones_externas.numero_orden, 
					relacion_retenciones_externas.fecha_factura AS fecha_orden, 
					beneficiarios.nombre AS Beneficiario, 
					relacion_retenciones_externas.monto_retenido, 
					tipo_retencion.nombre_comprobante 
				FROM 
					retenciones  
					INNER JOIN beneficiarios ON (retenciones.idbeneficiarios=beneficiarios.idbeneficiarios) 
					INNER JOIN relacion_retenciones_externas ON (retenciones.idretenciones=relacion_retenciones_externas.idretencion) 
					INNER JOIN tipo_retencion ON (relacion_retenciones_externas.idtipo_retencion=tipo_retencion.idtipo_retencion) 
				WHERE 
					(tipo_retencion.nombre_comprobante='".$_GET["idtipo_retencion"]."') AND (beneficiarios.idbeneficiarios='".$_GET["idbeneficiario"]."') $filtro_periodo $filtro_estado) 
					
				ORDER BY idretenciones, fecha_retencion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$retenido=number_format($field["monto_retenido"], 2, ',', '.');
			$suma_retenido+=$field["monto_retenido"];
			list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_orden"]); $forden=$d."/".$m."/".$a;	
			list($a, $m, $d)=SPLIT( '[/.-]', $field["fecha_factura"]); $ffactura=$d."/".$m."/".$a;
			$estado=strtoupper($field["estado"]);
			//
			echo "
			<tr>
				<td style='$tr5'>".$field['numero_orden']."</td>
				<td style='$tr5' align='center'>".$forden."</td>
				<td style='$tr5'>".$field["numero_factura"]."</td>
				<td style='$tr5'>".$field["numero_control"]."</td>
				<td style='$tr5' align='center'>".$ffactura."</td>			
				<td style='$tr5'>".$estado."</td>
				<td style='$tr5' align='right'>=DECIMAL(".$retenido."; 2)</td>
			</tr>";
		}
		$suma_retenido=number_format($suma_retenido, 2, ',', '');
		echo "
			<tr>
				<td colspan='6' align='right' style='$tr2'>Total</th>
				<td style='$tr2' align='right'>=DECIMAL(".$suma_retenido."; 2)</th>
			</tr>
		</table>";
		break;
	
	//	Retenciones Aplicadas...
	case "retenciones_aplicadas":
		$tr1="background-color:#999999; font-size:12px;";
		$tr2="font-size:12px; color:#000000; font-weight:bold;";
		$tr3="background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
		$tr4="font-size:12px; color:#000000; font-weight:bold;";
		$tr5="font-size:12px; color:#000000;";
		//----------------------------------------------------
		if ($quincena=="1") $dias=15;
		else {
			$dias_mes['01']=31; $dias_mes['03']=31; $dias_mes['04']=30; $dias_mes['05']=31; $dias_mes['06']=30;
			$dias_mes['07']=31; $dias_mes['08']=31; $dias_mes['09']=30; $dias_mes['10']=31; $dias_mes['11']=30; $dias_mes['12']=31;
			if ($anio%4==0) $dias_mes['02']=29; else $dias_mes['02']=28; 
			$dias=$dias_mes[$mes];
		}
		$periodo=$anio.$mes;
		if ($quincena=="1"){
			$desde=$anio."-".$mes."-01";
		}else if($quincena=="2"){
			$desde=$anio."-".$mes."-16";
		}else if($quincena=="3"){
			$desde=$anio."-".$mes."-01";
		}
		$hasta=$anio."-".$mes."-".$dias;
		//----------------------------------------------------
		$sql="SELECT descripcion FROM tipo_retencion WHERE nombre_comprobante='".$idtipo."' GROUP BY nombre_comprobante";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) { $field=mysql_fetch_array($query); $tipo_retencion=$field['descripcion']; }
		list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fdesde=$d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fhasta=$d."/".$m."/".$a;
		//----------------------------------------------------
		echo "<table>";
		echo "<tr><td colspan='7' style='$cat'>REPUBLICA BOLIVARIANA DE VENEZUELA</td></tr>";
		echo "<tr><td colspan='7' style='$cat'>".$config["nombre_institucion"]."</td></tr>";
		echo "<tr><td colspan='7' style='$cat'>".$config["denominacion"]."</td></tr>";
		echo "<tr><td colspan='7' style='$cat'>".$config["rif"]."</td></tr>";
		echo "
			<tr><td colspan='6'></td></tr>
			<tr><td style='$tr4' colspan='6' align='center'>RELACION DE RETENCIONES APLICADAS</td></tr>
			<tr><td></td><td style='$tr4' colspan='5'></td></tr>
			<tr><td>Periodo:</td><td style='$tr4' colspan='5' align='left'>$periodo</td></tr>
			<tr><td>Fecha:</td><td style='$tr4' colspan='5'>Del $fdesde Al $fhasta</td></tr>
			<tr><td>Tipo de Retención:</td><td style='$tr4' colspan='5'>$tipo_retencion</td></tr>
			<tr><td></td><td style='$tr4' colspan='5'></td></tr>
		</table>";
			echo "
			<table border='1'>	
			<tr>
				<th width='150' style='$tr1'>Nro. Comprobante</th>
				<th width='150' style='$tr1'>Nro. Orden de Pago</th>
				<th width='150' style='$tr1'>R.I.F</th>
				<th width='350' style='$tr1'>Beneficiario</th>
				<th width='100' style='$tr1'>Fecha</th>
				<th width='200' style='$tr1'>Monto</th>
			</tr>

		</table>";
		//----------------------------------------------------
		$sql="(SELECT 
					r.fecha_aplicacion_retencion as fecha_aplicacion_retencion, 
					r.numero_documento, 
					op.numero_orden,
					rl.periodo, 
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.monto_retenido, 
					rl.periodo, 
					o.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					r.estado,
					r.fecha_retencion,
					ropr.idorden_pago,
					r.tipo_retencion,
					rl.numero_retencion_referencia
					
				FROM 
					retenciones r 
					INNER JOIN relacion_retenciones rl ON (r.idretenciones=rl.idretenciones AND rl.generar_comprobante = 'si') 
					INNER JOIN relacion_orden_pago_retencion ropr ON (r.idretenciones = ropr.idretencion)
					INNER JOIN orden_pago op ON (ropr.idorden_pago = op.idorden_pago)
					INNER JOIN tipo_retencion tr ON (rl.idtipo_retencion=tr.idtipo_retencion) 
					INNER JOIN orden_compra_servicio o ON (r.numero_documento=o.numero_orden) 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (r.fecha_aplicacion_retencion>='".$desde."' AND r.fecha_aplicacion_retencion<='".$hasta."')
					$filtro_estado_r)
					
				UNION 
				
				(SELECT 
					r.fecha_aplicacion_retencion as fecha_aplicacion_retencion, 
					rl.numero_orden as numero_documento, 
					rl.numero_orden, 
					rl.periodo, 
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.monto_retenido, 
					rl.periodo, 
					r.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					r.estado,
					r.fecha_retencion,
					rl.numero_orden,
					r.tipo_retencion,
					rl.numero_retencion_referencia
				FROM 
					retenciones r 
					INNER JOIN beneficiarios b ON (r.idbeneficiarios=b.idbeneficiarios)
					INNER JOIN relacion_retenciones_externas rl ON (r.idretenciones=rl.idretencion) 
					INNER JOIN tipo_retencion tr ON (rl.idtipo_retencion=tr.idtipo_retencion)
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (r.fecha_aplicacion_retencion>='".$desde."' AND r.fecha_aplicacion_retencion<='".$hasta."'))
					
				UNION
				
				(SELECT 
					r.fecha_aplicacion_retencion as fecha_aplicacion_retencion, 
					r.numero_documento, 
					op.numero_orden,
					cr.periodo, 
					cr.idtipo_retencion, 
					cr.numero_retencion, 
					rl.monto_retenido, 
					cr.periodo, 
					o.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					cr.estado,
					cr.fecha_retencion,
					ropr.idorden_pago,
					r.tipo_retencion,
					rl.numero_retencion_referencia
				FROM 
					retenciones r 
					INNER JOIN comprobantes_retenciones cr ON (r.idretenciones=cr.idretenciones AND cr.estado <> 'procesado')
					INNER JOIN relacion_orden_pago_retencion ropr ON (r.idretenciones = ropr.idretencion)
					INNER JOIN orden_pago op ON (ropr.idorden_pago = op.idorden_pago)
					INNER JOIN tipo_retencion tr ON (cr.idtipo_retencion=tr.idtipo_retencion) 
					INNER JOIN relacion_retenciones rl ON (cr.idretenciones = rl.idretenciones AND cr.idtipo_retencion = rl.idtipo_retencion AND rl.generar_comprobante = 'si')
					INNER JOIN orden_compra_servicio o ON (r.numero_documento=o.numero_orden) 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (cr.fecha_retencion>='".$desde."' AND cr.fecha_retencion<='".$hasta."')
					$filtro_estado_cr)
				
				ORDER BY numero_retencion_referencia";
			   
		echo "
		<table border='1'>";

		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=1; $i<=$rows; $i++) {
			$field=mysql_fetch_array($query);
			//----------------------------------------------------	
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_retencion']); $fecha_retencion=$d."/".$m."/".$a;
			//$nro_comprobante=(string) $anio.$mes.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
			if ((8-strlen($field['numero_retencion'])) > 0){
				$nro_comprobante=(string) $a.$m.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
			}else{
				$nro_comprobante=(string) $a.$m.$field['numero_retencion'];
			}
			$monto_retenido=$field['monto_retenido'];
			$suma_retenido+=$field['monto_retenido'];
			//----------------------------------------------------
			echo "
			<tr>
				<td style='$tr5' align='center'>=TEXTO(".$nro_comprobante."; \"00000000000000\")</td>
				<td style='$tr5' align='center'>".$field['numero_orden']."</td>
				<td style='$tr5' align='center'>".$field['rif']."</td>
				<td style='$tr5'>".utf8_decode($field["nombre"])."</td>
				<td style='$tr5' align='center'>".$fecha_retencion."</td>			
				<td style='$tr5' align='right'>".number_format($monto_retenido, 2, ',', '.')."</td>
			</tr>";
		}
		//$suma_retenido=number_format($suma_retenido, 2, ',', '');
		echo "
			<tr>
				<td colspan='5' align='right' style='$tr2'>Total</th>
				<td style='$tr2' align='right'>".number_format($suma_retenido, 2, ',', '.')."</th>
			</tr>
		</table>";
		break;
		
	//	Generar Archivo I.V.A...
	case "generar_archivo_iva":
		$tr1="background-color:#999999; font-size:12px;";
		$tr2="font-size:12px; color:#000000; font-weight:bold;";
		$tr3="background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
		$tr4="font-size:12px; color:#000000; font-weight:bold;";
		$tr5="font-size:12px; color:#000000;";
		//----------------------------------------------------
		if ($quincena=="1") $dias=15;
		else {
			$dias_mes['01']=31; $dias_mes['03']=31; $dias_mes['04']=30; $dias_mes['05']=31; $dias_mes['06']=30;
			$dias_mes['07']=31; $dias_mes['08']=31; $dias_mes['09']=30; $dias_mes['10']=31; $dias_mes['11']=30; $dias_mes['12']=31;
			if ($anio%4==0) $dias_mes['02']=29; else $dias_mes['02']=28; 
			$dias=$dias_mes[$mes];
		}
		$periodo=$anio.$mes;
		if ($quincena=="1") $desde=$anio."-".$mes."-01"; else  $desde=$anio."-".$mes."-16";
		if ($quincena=="3") $desde=$anio."-".$mes."-01";
		$hasta=$anio."-".$mes."-".$dias;
		//----------------------------------------------------
		$query=mysql_query("SELECT rif FROM configuracion");
		while($field=mysql_fetch_array($query)) $rifc=$field['rif'];
		$rifc=ereg_replace("-", "", $rifc);
				
		$tipo_operacion="C";
		$tipo_documento="01";
		$monto=0;
		$nro_doc_afectado=0;
		$nro_expediente=0;
		//	------------------------------
		echo "<table border='1'>";
		//	------------------------------
		$idtipo = 'IVA';
		$sql="(SELECT 
					r.fecha_aplicacion_retencion as fecha_retencion, 
					r.numero_documento, 
					r.numero_factura,
					r.numero_control,
					r.fecha_factura,
					r.total, 
					r.exento, 
					r.base, 
					r.impuesto, 
					rl.periodo, 
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.monto_retenido, 
					rl.porcentaje_impuesto, 
					rl.periodo, 
					o.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					r.estado,
					r.fecha_retencion
				FROM 
					retenciones r 
					INNER JOIN relacion_retenciones rl ON (r.idretenciones=rl.idretenciones AND rl.generar_comprobante = 'si') 
					INNER JOIN tipo_retencion tr ON (rl.idtipo_retencion=tr.idtipo_retencion) 
					INNER JOIN orden_compra_servicio o ON (r.numero_documento=o.numero_orden) 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (r.fecha_aplicacion_retencion>='".$desde."' AND r.fecha_aplicacion_retencion<='".$hasta."')
					$filtro_estado_r)
					
				UNION 
				
				(SELECT 
					r.fecha_aplicacion_retencion as fecha_retencion, 
					r.numero_documento,
					rl.numero_factura,
					rl.numero_control,
					rl.fecha_factura,
					rl.total, 
					rl.exento, 
					rl.sub_total as base, 
					rl.impuesto, 
					rl.periodo, 
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.monto_retenido, 
					rl.alicuota as porcentaje_impuesto, 
					rl.periodo, 
					r.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					r.estado,
					r.fecha_retencion
				FROM 
					retenciones r 
					INNER JOIN beneficiarios b ON (r.idbeneficiarios=b.idbeneficiarios) 
					INNER JOIN relacion_retenciones_externas rl ON (r.idretenciones=rl.idretencion) 
					INNER JOIN tipo_retencion tr ON (rl.idtipo_retencion=tr.idtipo_retencion)
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (r.fecha_aplicacion_retencion>='".$desde."' AND r.fecha_aplicacion_retencion<='".$hasta."'))
					
				UNION
				
				(SELECT 
					r.fecha_aplicacion_retencion as fecha_retencion, 
					r.numero_documento,
					r.numero_factura,
					r.numero_control,
					r.fecha_factura,
					r.total, 
					r.exento, 
					r.base, 
					r.impuesto, 
					cr.periodo, 
					cr.idtipo_retencion, 
					cr.numero_retencion, 
					rl.monto_retenido, 
					rl.porcentaje_impuesto, 
					cr.periodo, 
					o.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					cr.estado,
					cr.fecha_retencion
				FROM 
					retenciones r 
					INNER JOIN comprobantes_retenciones cr ON (r.idretenciones=cr.idretenciones AND cr.estado <> 'procesado') 
					INNER JOIN tipo_retencion tr ON (cr.idtipo_retencion=tr.idtipo_retencion) 
					INNER JOIN relacion_retenciones rl ON (cr.idretenciones = rl.idretenciones AND cr.idtipo_retencion = rl.idtipo_retencion AND rl.generar_comprobante = 'si')
					INNER JOIN orden_compra_servicio o ON (r.numero_documento=o.numero_orden) 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (cr.fecha_retencion>='".$desde."' AND cr.fecha_retencion<='".$hasta."')
					$filtro_estado_cr)
				
				ORDER BY numero_retencion";
			   
			   
		$query=mysql_query($sql) or die ($sql.mysql_error()); 
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			$monto=$field['total'];
			$base=$field['base'];
			$monto_iva=$field['monto_retenido'];
			$rifv=ereg_replace("-", "", $field['rif']);
			$factura=(int) $field['numero_factura'];
			$nro_factura=$field['numero_factura'];
			$control=(int) $field['numero_control'];
			$nro_control=$field['numero_control'];
			$exento=$field['exento'];
			$alicuota=$field['porcentaje_impuesto'];
			$monto=number_format($monto, '2', '.', '');
			$base=number_format($base, '2', '.', '');
			$monto_iva=number_format($monto_iva, '2', '.', '');
			$exento=number_format($exento, '2', '.', '');
			$alicuota=number_format($alicuota, '2', '.', '');
			$periodo=$field['periodo'];
			//$nro_comprobante=(string) $periodo.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
			if ((8-strlen($field['numero_retencion'])) > 0){
				$nro_comprobante=(string) $periodo.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
			}else{
				$nro_comprobante=(string) $periodo.$field['numero_retencion'];
			}
			//	---------------------------
			$len_nro_factura=strlen($nro_factura);
			$ceros_nro_factura=(string) str_repeat("0", $len_nro_factura);
			$len_nro_control=strlen($nro_control);
			$ceros_nro_control=(string) str_repeat("0", $len_nro_control);
			
			echo "
			<tr>
				<td style='$tr5'>".$rifc."</td>
				<td style='$tr5'>".$periodo."</td>
				<td style='$tr5' width='100'>=TEXTO(\"".$field['fecha_factura']."\"; \"yyyy-mm-dd\")</td>
				<td style='$tr5'>".$tipo_operacion."</td>
				<td style='$tr5' width='25'>=TEXTO(".$tipo_documento."; \"00\")</td>
				<td style='$tr5'>".$rifv."</td>
				<td style='$tr5' width='100'>=TEXTO(".$nro_factura."; \"$ceros_nro_factura\")</td>
				<td style='$tr5' width='100'>=TEXTO(".$nro_control."; \"$ceros_nro_control\")</td>
				<td style='$tr5'>".$monto."</td>
				<td style='$tr5'>".$base."</td>
				<td style='$tr5'>".$monto_iva."</td>
				<td style='$tr5'>".$nro_doc_afectado."</td>
				<td style='$tr5' width='150'>=TEXTO(".$nro_comprobante."; \"00000000000000\")</td>
				<td style='$tr5'>".$exento."</td>
				<td style='$tr5'>".$alicuota."</td>
				<td style='$tr5'>".$nro_expediente."</td>
			</tr>";
		}
		break;


	//	Generar Archivo I.V.A...
	case "libro_compras_uno":
		$tr1="background-color:#999999; font-size:12px;";
		$tr2="font-size:12px; color:#000000; font-weight:bold;";
		$tr3="background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
		$tr4="font-size:12px; color:#000000; font-weight:bold;";
		$tr5="font-size:12px; color:#000000;";
		//----------------------------------------------------
		$query=mysql_query("SELECT nombre_institucion, domicilio_legal, ciudad, estado, rif FROM configuracion") or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$nombre_agente=$field['nombre_institucion'];
			$rif_agente=$field['rif'];
			$queryEstado=mysql_query("SELECT denominacion FROM estado where idestado = '".$field['estado']."'") or die ($sql.mysql_error());
			$fieldEstado=mysql_fetch_array($queryEstado);
			$direccion_fiscal=$field['domicilio_legal']." ".$field['ciudad']." Estado ".$fieldEstado['denominacion'];
		}
		//----------------------------------------------------
		$dias_mes['01']=31; $dias_mes['03']=31; $dias_mes['04']=30; $dias_mes['05']=31; $dias_mes['06']=30;
		$dias_mes['07']=31; $dias_mes['08']=31; $dias_mes['09']=30; $dias_mes['10']=31; $dias_mes['11']=30; $dias_mes['12']=31;
		if ($anio%4==0) $dias_mes['02']=29; else $dias_mes['02']=28; 
		$dias=$dias_mes[$mes];
		if ($estado!="todos") {
			 $filtro_estado_r=" AND r.estado='".$estado."'";
		}
		$periodo=$anio.$mes;
		$desde=$anio."-".$mes."-01"; 
		$hasta=$anio."-".$mes."-".$dias;
		//----------------------------------------------------
		$query=mysql_query("SELECT rif FROM configuracion");
		while($field=mysql_fetch_array($query)) $rifc=$field['rif'];
		$rifc=ereg_replace("-", "", $rifc);
		
		$idtipo = '1x1000';
		$sql="(SELECT 
					r.fecha_aplicacion_retencion as fecha_retencion,
					r.idretenciones, 
					op.numero_orden,
					r.numero_documento, 
					r.numero_factura,
					r.numero_control,
					r.fecha_factura,
					r.fecha_aplicacion_retencion,
					r.total, 
					r.exento, 
					r.base, 
					r.impuesto, 
					rl.periodo, 
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.monto_retenido, 
					rl.porcentaje_impuesto, 
					rl.periodo,
					rl.fecha_enteramiento,
					rl.fecha_deposito,
					rl.numero_deposito,
					rl.fecha_transferencia,
					o.idbeneficiarios,
					o.total as total_contrato,
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					r.estado,
					r.fecha_retencion
				FROM 
					retenciones r 
					INNER JOIN relacion_retenciones rl ON (r.idretenciones=rl.idretenciones AND rl.generar_comprobante = 'si') 
					INNER JOIN relacion_orden_pago_retencion ropr ON (r.idretenciones = ropr.idretencion)
					INNER JOIN orden_pago op ON (ropr.idorden_pago = op.idorden_pago)
					INNER JOIN tipo_retencion tr ON (rl.idtipo_retencion=tr.idtipo_retencion) 
					INNER JOIN orden_compra_servicio o ON (r.numero_documento=o.numero_orden) 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (r.fecha_aplicacion_retencion>='".$desde."' AND r.fecha_aplicacion_retencion<='".$hasta."')
					$filtro_estado_r)
					
				UNION 
				
				(SELECT 
				 	r.fecha_aplicacion_retencion as fecha_retencion, 
					r.idretenciones,
					rl.numero_orden as numero_documento,
					rl.numero_orden,
					rl.numero_factura,
					rl.numero_control,
					rl.fecha_factura,
					r.fecha_aplicacion_retencion,
					rl.total, 
					rl.exento, 
					rl.sub_total as base, 
					rl.impuesto, 
					rl.periodo, 
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.monto_retenido, 
					rl.alicuota as porcentaje_impuesto, 
					rl.periodo,
					rl.fecha_enteramiento,
					rl.fecha_deposito,
					rl.numero_deposito,
					rl.fecha_transferencia,
					r.idbeneficiarios, 
					rl.monto_contrato as total_contrato,
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					r.estado,
					r.fecha_retencion
				FROM 
					retenciones r 
					INNER JOIN beneficiarios b ON (r.idbeneficiarios=b.idbeneficiarios) 
					INNER JOIN relacion_retenciones_externas rl ON (r.idretenciones=rl.idretencion) 
					INNER JOIN tipo_retencion tr ON (rl.idtipo_retencion=tr.idtipo_retencion)
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (r.fecha_aplicacion_retencion>='".$desde."' AND r.fecha_aplicacion_retencion<='".$hasta."')
					$filtro_estado_r)
					
				UNION
				
				(SELECT 
					r.fecha_aplicacion_retencion as fecha_retencion, 
					r.idretenciones,
					op.numero_orden,
					r.numero_documento,
					r.numero_factura,
					r.numero_control,
					r.fecha_factura,
					r.fecha_aplicacion_retencion,
					r.total, 
					r.exento, 
					r.base, 
					r.impuesto, 
					cr.periodo, 
					cr.idtipo_retencion, 
					cr.numero_retencion, 
					rl.monto_retenido, 
					rl.porcentaje_impuesto, 
					cr.periodo, 
					o.idbeneficiarios, 
					o.total as total_contrato,
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					cr.estado,
					cr.fecha_retencion
				FROM 
					retenciones r 
					INNER JOIN comprobantes_retenciones cr ON (r.idretenciones=cr.idretenciones AND cr.estado <> 'procesado') 
					INNER JOIN relacion_orden_pago_retencion ropr ON (r.idretenciones = ropr.idretencion)
					INNER JOIN orden_pago op ON (ropr.idorden_pago = op.idorden_pago)
					INNER JOIN tipo_retencion tr ON (cr.idtipo_retencion=tr.idtipo_retencion) 
					INNER JOIN relacion_retenciones rl ON (cr.idretenciones = rl.idretenciones AND cr.idtipo_retencion = rl.idtipo_retencion AND rl.generar_comprobante = 'si')
					INNER JOIN orden_compra_servicio o ON (r.numero_documento=o.numero_orden) 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (cr.fecha_retencion>='".$desde."' AND r.fecha_retencion<='".$hasta."')
					$filtro_estado_r)

				ORDER BY numero_retencion";


		echo "
		<table border='1'>
			<tr><td colspan='2'>NOMBRE O RAZON SOCIAL:</td><td style='$tr4' colspan='4' align='left'>".utf8_decode($nombre_agente)."</td></tr>
			<tr><td colspan='2'>R.I.F.::</td><td style='$tr4' colspan='4'>".utf8_decode($rif_agente)."</td></tr>
			<tr><td colspan='2'>Periodo:</td><td style='$tr4' colspan='4' align='left'>$periodo</td></tr>
			<tr><td colspan='2'>Fecha:</td><td style='$tr4' colspan='4'>Del ".$fdesde." Al ".$fhasta."</td></tr>";
			echo "
			<tr>
				<td width='35' style='$tr1'>Nro. Oper</td>
				<td width='350' style='$tr1'>Nombre del Contribuyente</td>
				<td width='100' style='$tr1'>R.I.F</td>
				<td width='350' style='$tr1'>Concepto de Operación</td>
				<td width='150' style='$tr1'>Monto Total del Contrato</td>
				<td width='100' style='$tr1'>Tipo de Operación</td>
				<td width='100' style='$tr1'>Fecha de Retención</td>
				<td width='100' style='$tr1'>Fecha de Entera</td>
				<td width='100' style='$tr1'>Nro. Deposito</td>
				<td width='100' style='$tr1'>Nro. Orden Pago</td>
				<td width='150' style='$tr1'>Monto de la Orden de Pago</td>
				<td width='150' style='$tr1'>Monto de la Operación</td>
				<td width='150' style='$tr1'>Base Imponible</td>
				<td width='150' style='$tr1'>Impuesto a Retener</td>
				<td width='150' style='$tr1'>Deducciones o Monto a Enterar</td>
			</tr>";
		//----------------------------------------------------



		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		$linea=1;
		for ($i=1; $i<=$rows; $i++) {
			$field=mysql_fetch_array($query);
			$queryidop = mysql_query("select * from relacion_orden_pago_retencion where idretencion = '".$field["idretenciones"]."'");
			$campos = mysql_fetch_array($queryidop);
			$sql_concepto_externo = mysql_query("select * from relacion_retenciones_externas where idretencion = '".$field["idretenciones"]."'");
			if ($sql_concepto_externo){
				$bus_concepto = mysql_fetch_array($sql_concepto_externo);
				$concepto = $bus_concepto["concepto_orden"];
			}else{
				$concepto = '';
			}
			$sqlop = mysql_query("select * from orden_pago where idorden_pago = '".$campos["idorden_pago"]."'"); 
			$busop = mysql_fetch_array($sqlop);
			if ($concepto == ''){
				$concepto = $busop['justificacion'];
			}
			if ($busop["anticipo"]=='si'){
				$operacion = 'Anticipo';
			}
			if (($busop["anticipo"]=='no' or $busop["anticipo"]=='') && $busop["forma_pago"]=='parcial'){
				$operacion = utf8_decode('Valuación');
			}
			if (($busop["anticipo"]=='no' or $busop["anticipo"]=='') && $busop["forma_pago"]=='total'){
				$operacion = utf8_decode('Pago');
			}
			//----------------------------------------------------	
			//if ($i==1) $contador=$field['numero_retencion'];
			//if ($contador>$field['numero_retencion']) $contador=$field['numero_retencion'];
			//----------------------------------------------------	
			//list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_factura']); $fecha_factura=$d."/".$m."/".$a;
			
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_aplicacion_retencion']); $fecha_factura=$d."/".$m."/".$a;
			
			if ((8-strlen($field['numero_retencion'])) > 0){
				$nro_comprobante=(string) $a.$m.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
			}else{
				$nro_comprobante=(string) $a.$m.$field['numero_retencion'];
			}
			$total=number_format($field['total'], 2, ',', '.');
			$total_contrato=number_format($field['total_contrato'], 2, ',', '.');
			$exento=number_format($field['exento'], 2, ',', '.');
			$alicuota=number_format($field['porcentaje_impuesto'], 2, ',', '.');
			$impuesto=number_format($field['impuesto'], 2, ',', '.');
			$monto_retenido=number_format($field['monto_retenido'], 2, ',', '.');
			$base=number_format($field['base'], 2, ',', '.');
			$suma_retenido+=$field['monto_retenido'];
			$estado = strtoupper($field['estado']);
			
			//----------------------------------------------------

			echo "
			<tr>
				<td style='$tr5'>".$linea."</td>
				<td style='$tr5'>".utf8_decode($field['nombre'])."</td>
				<td style='$tr5'>".$field['rif']."</td>
				<td style='$tr5'>".utf8_decode($concepto)."</td>
				<td style='$tr5'>".$total_contrato."</td>
				<td style='$tr5'>".$operacion."</td>
				<td style='$tr5'>".$fecha_factura."</td>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>".$field['numero_orden']."</td>
				<td style='$tr5'>".$total."</td>
				<td style='$tr5'>".$total."</td>
				<td style='$tr5'>".$base."</td>
				<td style='$tr5'>".$monto_retenido."</td>
				<td style='$tr5'>".$monto_retenido."</td>
			</tr>";

			$linea++;
			$contador++;
		}
		$suma_retenido=number_format($suma_retenido, 2, ',', '.');
		
		echo "
			<tr>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>&nbsp;</td>
				<td style='$tr5'>".$suma_retenido."</td>
			</tr>";



		break;
}
?>