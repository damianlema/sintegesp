<?php  
include("conf/conex.php"); // Conexion a nuestra BD  
Conectarse();

// INFOGEN  *****************************************************************************************
$csv_end = "  
";  
$csv_sep = "|";  
$csv_file = "C:/infogen.csv";  
$csv="";  
$sql="SELECT * from 
				orden_compra_servicio oc,
				tipos_documentos td 
					where 
				oc.estado != 'elaboracion' 
				and oc.tipo = td.idtipos_documentos
				and td.modulo like '%-3-%'";  
$res=mysql_query($sql)or die(mysql_error());  


while($row=mysql_fetch_array($res)){  

$sql_configuracion = mysql_query("select * from configuracion");
$bus_configuracion = mysql_fetch_array($sql_configuracion);

$sql_estados = mysql_query("select codigo from estado where idestado = '".$bus_configuracion["estado"]."'");
$bus_estados = mysql_fetch_array($sql_estados); 


$numero_orden = explode("-",$row['numero_orden']);

$codigo = $numero_orden[0]."-".$numero_orden[2];
$periodo = $numero_orden[1];
$trimestre = $tri;
$fecha_inicio = $row["fecha_orden"];
$fecha_cierre = $row["fecha_orden"];
$actividad = "";
$modo_contratacion= "";
$lugar_ejecucion= $bus_estados["codigo"];
$modo_comunicacion = $row["modo_comunicacion"];
$objeto= $row["justificacion"];
$decreto_4998= "0";
$decreto_4910= "0";

	$csv.= $codigo.$csv_sep.
			$periodo.$csv_sep.
			$trimestre.$csv_sep.
			$fecha_inicio.$csv_sep.
			$fecha_cierre.$csv_sep.
			$actividad.$csv_sep.
			$modo_contratacion.$csv_sep.
			$lugar_ejecucion.$csv_sep.
			$modo_comunicacion.$csv_sep.
			$objeto.$csv_sep.
			$decreto_4998.$csv_sep.
			$decreto_4910.
			$csv_end;  
}  
//Generamos el csv de todos los datos  
if (!$handle = fopen($csv_file, "w")) {  
	echo "Cannot open file";  
	exit;  
}  
if (fwrite($handle, utf8_decode($csv)) === FALSE) {  
	echo "Cannot write to file";  
	exit;  
}  
fclose($handle);  


// INFOGEN  *****************************************************************************************








// DETALLEPRODUC  *****************************************************************************************
$csv_end = "  
";  
$csv_sep = "|";  
$csv_file = "C:/detalleproduc.csv";  
$csv="";  
$sql="SELECT * from 
				orden_compra_servicio oc,
				tipos_documentos td 
					where 
				oc.estado != 'elaboracion' 
				and oc.tipo = td.idtipos_documentos
				and td.modulo like '%-3-%'"; 
$res=mysql_query($sql);  


while($bus_ordenes=mysql_fetch_array($res)){  
$sql_articulos = mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = '".$bus_ordenes["idorden_compra_servicio"]."'");
while($row = mysql_fetch_array($sql_articulos)){

$sql_detalle = mysql_query("select snc.codigo,
							 	   snc.descripcion,
								   um.descripcion as descripcion_uni
							 from 
								articulos_servicios ase,
								articulos_compra_servicio acs,
								snc_detalle_grupo snc,
								unidad_medida um
							where
								acs.idarticulos_compra_servicio = '".$row["idarticulos_compra_servicio"]."'
								AND acs.idarticulos_servicios = ase.idarticulos_servicios 
								AND ase.idsnc_detalle_grupo = snc.idsnc_detalle_grupo
								AND um.idunidad_medida = ase.idunidad_medida")or die(mysql_error());



$bus_detalle = mysql_fetch_array($sql_detalle);




$numero_orden = explode("-",$bus_ordenes['numero_orden']);

$periodo = $numero_orden[1];
$trimestre = $tri;
$codigo = $numero_orden[0]."-".$numero_orden[2];
$codigo_ccce = $bus_detalle["codigo"];
$cantidad = $row["cantidad"];
$unidad_medida = $bus_detalle["descripcion_uni"];
$monto = ($bus_ordenes["precio_unitario"]+$bus_ordenes["impuesto"]);
$detalle = $bus_detalle["descripcion"];


	$csv.= $periodo.$csv_sep.
			$trimestre.$csv_sep.
			$codigo.$csv_sep.
			$codigo_ccce.$csv_sep.
			$cantidad.$csv_sep.
			$unidad_medida.$csv_sep.
			$monto.$csv_sep.
			$detalle.
			$csv_end;  
}
}  
//Generamos el csv de todos los datos  
if (!$handle = fopen($csv_file, "w")) {  
	echo "Cannot open file";  
	exit;  
}  
if (fwrite($handle, utf8_decode($csv)) === FALSE) {  
	echo "Cannot write to file";  
	exit;  
}  
fclose($handle);  


// DETALLEPRODUC  *****************************************************************************************




// CONTRATISTAS  *****************************************************************************************
$csv_end = "  
";  
$csv_sep = "|";  
$csv_file = "C:/contratistas.csv";  
$csv="";  
$sql="SELECT
		sc.nro_orden,
		sncga.codigo as rubro,
		sncga.descripcion as detalle_rubro,
		be.nombre as nombre_beneficiario,
		be.rif,
		te.codigo as codigo_tipo_empresa,
		est.codigo as codigo_estado,
		psc.ganador,
		(acs.precio_unitario+acs.impuesto) as monto,
		sc.fecha_solicitud
			FROM
		solicitud_cotizacion sc
		INNER JOIN tipos_documentos td ON (sc.tipo = td.idtipos_documentos AND td.modulo like '%-3-%')
		INNER JOIN articulos_solicitud_cotizacion acs ON (acs.idsolicitud_cotizacion = sc.idsolicitud_cotizacion)
		INNER JOIN articulos_servicios arc ON (arc.idarticulos_servicios = acs.idarticulos_servicios)
		INNER JOIN snc_detalle_grupo sncdg ON (arc.idsnc_detalle_grupo = sncdg.idsnc_detalle_grupo)
		INNER JOIN snc_grupo_actividad sncga ON (sncdg.idsnc_grupo_actividad = sncga.idsnc_grupo_actividad)
		INNER JOIN proveedores_solicitud_cotizacion psc ON (sc.idsolicitud_cotizacion = psc.idsolicitud_cotizacion)
		INNER JOIN beneficiarios be ON (psc.idbeneficiarios = be.idbeneficiarios)
		INNER JOIN tipo_empresa te ON (be.idtipo_empresa = te.idtipo_empresa)
		LEFT JOIN estado est ON (est.idestado = be.idestado)
			WHERE
		sc.estado = 'ordenado'
		GROUP BY nro_orden, be.idbeneficiarios, rubro"; 
$res=mysql_query($sql)or die(mysql_error());  


while($bus_orden=mysql_fetch_array($res)){  
$numero_orden = explode("-",$bus_orden['nro_orden']);

if($bus_orden["ganador"] == 'y'){
	$con = "Ganador";
}else{
	$con = "Participante";
}


$periodo = $numero_orden[0]."-".$numero_orden[2];
$trimestre = $tri;
$codigo = $numero_orden[1];
$rif = $bus_orden["rif"];
$nombre = $bus_orden["nombre_beneficiario"];
$tipo_empresa = $bus_orden["codigo_tipo_empresa"];
$estado = $bus_orden["codigo_estado"];
$condicion = $con;
$rubro = $bus_orden["rubro"];
$rubro_detalle = $bus_orden["detalle_rubro"];
$monto = $bus_orden["monto"];
$por_van = '0';
$por_anticipo = '0';
$contrato = $numero_orden[0]."-".$numero_orden[2];
$fecha_inicio= $bus_orden["fecha_solicitud"];
$fecha_fin= $bus_orden["fecha_solicitud"];
$grs  = '0';


	$csv.= $periodo.$csv_sep.
			$trimestre.$csv_sep.
			$codigo.$csv_sep.
			$rif.$csv_sep.
			$nombre.$csv_sep.
			$tipo_empresa.$csv_sep.
			$estado.$csv_sep.
			$condicion.$csv_sep.
			$rubro.$csv_sep.
			$rubro_detalle.$csv_sep.
			$monto.$csv_sep.
			$por_van.$csv_sep.
			$por_anticipo.$csv_sep.
			$contrato.$csv_sep.
			$fecha_inicio.$csv_sep.
			$fecha_fin.$csv_sep.
			$grs.
			$csv_end;  

}  
//Generamos el csv de todos los datos  
if (!$handle = fopen($csv_file, "w")) {  
	echo "Cannot open file";  
	exit;  
}  
if (fwrite($handle, utf8_decode($csv)) === FALSE) {  
	echo "Cannot write to file";  
	exit;  
}  
fclose($handle);  


// CONTRATISTAS  *****************************************************************************************
?>  