<?php
session_start();
set_time_limit(-1);
ini_set("memory_limit", "200M");
require('../../../conf/conex.php');
require('../../mc_table4.php');
Conectarse();
$ahora=date("d-m-Y H:i:s");
//------------------------
$dia=date("d");
$mes=date("m");
$annio=date("Y");
$nommes['01']="Enero";
$nommes['02']="Febrero";
$nommes['03']="Marzo";
$nommes['04']="Abril";
$nommes['05']="Mayo";
$nommes['06']="Junio";
$nommes['07']="Julio";
$nommes['08']="Agosto";
$nommes['09']="Septiembre";
$nommes['10']="Octubre";
$nommes['11']="Noviembre";
$nommes['12']="Diciembre";
$nombremes=$nommes[$mes];
//------------------------
$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
//	------------------------
$sql="SELECT * FROM configuracion_tesoreria";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
//	------------------------
$primero=$field['primero_tesoreria'];
$ci=$field['ci_primero_tesoreria'];
$cargo=$field['cargo_primero_tesoreria'];
//	------------------------
$sql="SELECT pagos_financieros.idorden_pago, pagos_financieros.beneficiario, pagos_financieros.monto_cheque, orden_pago.numero_orden AS OrdenPago, orden_pago.justificacion, orden_pago.total_retenido, orden_pago.numero_proyecto, orden_pago.numero_contrato, tipos_documentos.descripcion AS TipoDocumento, cuentas_bancarias.idbanco, banco.denominacion AS Banco, banco.fideicomiso, banco.cargo_fideicomiso, banco.atencion FROM pagos_financieros, orden_pago, tipos_documentos, cuentas_bancarias, banco WHERE pagos_financieros.idpagos_financieros='".$id_emision_pago."' AND pagos_financieros.idorden_pago=orden_pago.idorden_pago AND pagos_financieros.idtipo_documento=tipos_documentos.idtipos_documentos AND pagos_financieros.idcuenta_bancaria=cuentas_bancarias.idcuentas_bancarias AND cuentas_bancarias.idbanco=banco.idbanco";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
//	--------------------
$monto_letras=$pdf->ValorEnLetras($field["monto_cheque"], "");
$monto=number_format($field['monto_cheque'], 2, ',', '.');
$retencion=number_format($field['total_retenido'], 2, ',', '.'); $retenido=(int) $field['total_retenido'];
$beneficiario=$field['beneficiario'];
$ordenpago=$field['OrdenPago'];
$justificacion=$field['justificacion'];
$porcentaje=$field['Porcentaje'];
$tipodocumento=$field['TipoDocumento'];
$idordenpago=$field['idorden_pago'];
$proyecto=$field['numero_proyecto'];
$contrato=$field['numero_contrato'];
$banco=$field['Banco'];
$fideicomiso=$field['fideicomiso'];
$cargo_fideicomiso=$field['cargo_fideicomiso'];
$atencion=$field['atencion'];
//	--------------------
$sql="SELECT relacion_pago_compromisos.idorden_compra_servicio, orden_compra_servicio.numero_orden, retenciones.idretenciones, relacion_retenciones.porcentaje_aplicado FROM relacion_pago_compromisos, orden_compra_servicio, retenciones, relacion_retenciones WHERE relacion_pago_compromisos.idorden_compra_servicio=orden_compra_servicio.idorden_compra_servicio AND relacion_pago_compromisos.idorden_pago='".$idordenpago."' AND retenciones.idretenciones=relacion_retenciones.idretenciones";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	$ordencompra=$field['numero_orden'];
	$porcentaje=number_format($field['porcentaje_aplicado'], 2, ',', '.');
	if ($retenido==0) $archivo=fopen("doc/FORMATO01.rtf", "r");
	else $archivo=fopen("doc/FORMATO02.rtf", "r");
} else $archivo=fopen("doc/FORMATO03.rtf", "r");
//	--------------------
if ($archivo) {
	while(!feof($archivo)) $texto.=fgets($archivo, 255);
	
	$texto=ereg_replace("jjj", "{\\b $justificacion}", $texto);
	$texto=ereg_replace("_dia_", "{ $dia}", $texto);
	$texto=ereg_replace("_mes_", "{ $nombremes}", $texto);
	$texto=ereg_replace("_annio_", "{ $annio}", $texto);
	$texto=ereg_replace("_ordenpago_", "{\\b $ordenpago}", $texto);
	$texto=ereg_replace("_ordencompra_", "{\\b $ordencompra}", $texto);
	$texto=ereg_replace("_beneficiario_", "{\\b $beneficiario}", $texto);
	$texto=ereg_replace("_montoletras_", "{\\b $monto_letras}", $texto);
	$texto=ereg_replace("_monto_", "{\\b $monto}", $texto);
	$texto=ereg_replace("_xxx_", "{\\b $retencion}", $texto);
	$texto=ereg_replace("_porcentaje_", "{\\b $porcentaje}", $texto);
	$texto=ereg_replace("_tipodocumento_", "{\\b $tipodocumento}", $texto);
	$texto=ereg_replace("_proyecto_", "{\\b $proyecto}", $texto);
	$texto=ereg_replace("_contrato_", "{\\b $contrato}", $texto);
	$texto=ereg_replace("_primero_", "{\\b $primero}", $texto);
	$texto=ereg_replace("_ci_", "{\\b $ci}", $texto);
	$texto=ereg_replace("_cargo_", "{\\b $cargo}", $texto);
	$texto=ereg_replace("fideicomiso", "{\\b $fideicomiso}", $texto);
	$texto=ereg_replace("fcargo", "{\\b $cargo_fideicomiso}", $texto);
	$texto=ereg_replace("_banco_", "{\\b $banco}", $texto);
	$texto=ereg_replace("-atencion-", "{\\b $atencion}", $texto);
	
	header('Content-type: application/msword');
	header('Content-Disposition: inline; filename=OFICIO.rtf');
	$output="{\\rtf1";
	$output.=$texto;
	$output.="}";
	echo $output;
}
?>