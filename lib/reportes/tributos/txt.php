<?php
session_start(); 
header('Content-Type: text/html; charset=iso-8859-1');
set_time_limit(-1);
require('../../../conf/conex.php');
Conectarse();
extract($_GET);
extract($_POST);
$ahora=date("d-m-Y H:i:s");
$nombre_archivo = strtr($nombre_archivo, " ", "_");
header("Content-Disposition: filename=\"".$nombre_archivo.".txt\";");
$texto="";
$archivo=fopen($nombre_archivo.".txt", "w+");
//---------------
$LF = 0x0A;
$CR = 0x0D;
$nl = sprintf("%c%c",$CR,$LF);  
//---------------

//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Generar Archivo I.V.A...
	case "generar_archivo_iva":
		if ($quincena=="1") $dias=15;
		else if ($quincena == "2" or $quincena=="3"){
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
			if ($field["estado"] <> "anulado"){
				$monto=$field['total'];
				$base=$field['base'];
				$monto_iva=$field['monto_retenido'];
				//list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_factura']);
				//$fecha_factura=$d."/".$m."/".$a;
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
				$nro_comprobante=(string) $periodo.str_repeat("0", 8-strlen($field['numero_retencion'])).$field['numero_retencion'];
				
				
				$texto.=$rifc."\t".$periodo."\t".$field['fecha_factura']."\t".$tipo_operacion."\t".$tipo_documento."\t".$rifv."\t".$nro_factura."\t".$nro_control."\t".$monto."\t".$base."\t".$monto_iva."\t".$nro_doc_afectado."\t".$nro_comprobante."\t".$exento."\t".$alicuota."\t".$nro_expediente."$nl";
			}
		}
		fwrite($archivo, $texto);
		fclose($archivo);
		break;
}
?>