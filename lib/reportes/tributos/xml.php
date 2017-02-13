<?php
require('../../../conf/conex.php'); 
Conectarse();
extract($_GET);
extract($_POST);
$ahora=date("d-m-Y H:i:s");
$nombre_archivo = strtr($nombre_archivo, " ", "_");
//---------------
$LF = 0x0A;
$CR = 0x0D;
$nl = sprintf("%c%c",$CR,$LF);  
//---------------

//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Generar Archivo I.S.L.R...
	case "generar_archivo_islr":
		$dias_mes['01']=31; $dias_mes['03']=31; $dias_mes['04']=30; $dias_mes['05']=31; $dias_mes['06']=30;
		$dias_mes['07']=31; $dias_mes['08']=31; $dias_mes['09']=30; $dias_mes['10']=31; $dias_mes['11']=30; $dias_mes['12']=31;
		if ($anio%4==0) $dias_mes['02']=29; else $dias_mes['02']=28; 
		$dias=$dias_mes[$mes];
		$periodo=$anio.$mes;
		$desde=$anio."-".$mes."-01";
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
		
		$idtipo = 'ISLR';
		$sql="(SELECT 
					r.fecha_retencion,
					r.fecha_aplicacion_retencion, 
					r.numero_documento, 
					r.numero_factura, 
					r.numero_control, 
					r.fecha_factura, 
					r.total, 
					r.exento, 
					r.base, 
					r.impuesto, 
					r.total_retenido, 
					rl.periodo, 
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.porcentaje_impuesto, 
					rl.monto_retenido, 
					o.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante, 
					rl.porcentaje_aplicado, 
					rl.base_calculo, 
					rl.codigo_concepto 
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
					r.fecha_retencion,
					r.fecha_aplicacion_retencion,
					r.numero_documento, 
					r.numero_factura, 
					r.numero_control, 
					r.fecha_factura, 
					r.total, 
					r.exento, 
					r.base, 
					r.impuesto, 
					r.total_retenido, 
					rl.periodo, 
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.alicuota as porcentaje_impuesto, 
					rl.monto_retenido, 
					r.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante, 
					rl.porcentaje as porcentaje_aplicado, 
					rl.base_calculo, 
					rl.codigo_islr as codigo_concepto 
				FROM 
					retenciones r 
					INNER JOIN beneficiarios b ON (r.idbeneficiarios=b.idbeneficiarios) 
					INNER JOIN relacion_retenciones_externas rl ON (r.idretenciones=rl.idretencion) 
					INNER JOIN tipo_retencion tr ON (rl.idtipo_retencion=tr.idtipo_retencion)
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (r.fecha_aplicacion_retencion>='".$desde."' AND r.fecha_aplicacion_retencion<='".$hasta."'))
				
				ORDER BY numero_retencion";
		
				
		$query=mysql_query($sql) or die ($sql.mysql_error()); 
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_aplicacion_retencion']); $lafecha="$d/$m/$a";
			$base_calculo=number_format($field['base_calculo'], '2', '.', '');
			$porcentaje_aplicado=number_format($field['porcentaje_aplicado'], '2', '.', '');
			//$factura=(int) $field['numero_factura']; if ($factura==0) $factura=$field['numero_factura']; 
			//$control=(int) $field['numero_control']; if ($control==0) $control=$field['numero_control'];
			$factura=$field['numero_factura']; if ($factura==0) $factura=$field['numero_factura']; 
			$control=$field['numero_control']; if ($control==0) $control=$field['numero_control'];
			$rifr=$field['rif'];
			$concepto=$field['codigo_concepto'];
			
			$cuerpo.='
			<DetalleRetencion>
				<RifRetenido>'.$rifr.'</RifRetenido>
				<NumeroFactura>'.$factura.'</NumeroFactura>
				<NumeroControl>'.$control.'</NumeroControl>
				<FechaOperacion>'.$lafecha.'</FechaOperacion>
				<CodigoConcepto>'.$concepto.'</CodigoConcepto>
				<MontoOperacion>'.$base_calculo.'</MontoOperacion>
				<PorcentajeRetencion>'.$porcentaje_aplicado.'</PorcentajeRetencion>
			</DetalleRetencion>';
		}
		
		$buffer='<?xml version="1.0" encoding="utf-8" ?>'.$nl;
		$buffer.='<RelacionRetencionesISLR RifAgente="'.$rifc.'" Periodo="'.$periodo.'">';
		$buffer.=$cuerpo.$nl;
		$buffer.='</RelacionRetencionesISLR>';
		break;
}

$name_file=$nombre_archivo.".xml";
$file=fopen("../../../seniat/".$name_file, "w+");
fwrite($file, $buffer);
fclose($file);
require ("../../../lib/crearZip.php");
$zipfile = new zipfile();
$zipfile->add_file(implode("",file("../../../seniat/".$name_file)), $nombre_archivo.".xml");

header("Content-type: application/octet-stream");
header("Content-disposition: attachment; filename=".$nombre_archivo.".zip");
echo $zipfile->file();


//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
?>
<script language="javascript">
	location.href = "generar_filtro.php?nombre=<?=$_GET['nombre']?>";
	alert("El archivo se genero exitosamente");
</script>