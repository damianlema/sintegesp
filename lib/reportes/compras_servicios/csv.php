<?php //die($trimestre);
$nombre_archivo = strtr($nombre_archivo, " ", "_");
$nombre_archivo=$nombre_archivo.".xls";
//header("Content-Type: application/vnd.ms-excel; charset=utf-8");
//header("Content-Disposition: filename=\"".$nombre_archivo."\";");
session_start();
set_time_limit(-1);
require('../../../conf/conex.php');
require("../../excel.php"); 
require("../../excel-ext.php"); 
Conectarse();
extract($_GET);
extract($_POST);
$LF = 0x0A;
$CR = 0x0D;
$nl = sprintf("%c%c",$CR,$LF);  


if($trimestre == 1)$between="BETWEEN '".$anio."-01-01' AND '".$anio."-03-31'";
if($trimestre == 2)$between="BETWEEN '".$anio."-04-01' AND '".$anio."-06-30'";
if($trimestre == 3)$between="BETWEEN '".$anio."-07-01' AND '".$anio."-09-30'";
if($trimestre == 4)$between="BETWEEN '".$anio."-10-01' AND '".$anio."-12-31'";


switch ($nombre) {
	case "infogen":
		// INFOGEN  *****************************************************************************************
		$csv_end = "$nl";  
		$csv_sep = "|";  
		//$csv_file = "C:/infogen.csv";  
		$csv="";  
		$sql="SELECT * from 
						orden_compra_servicio oc,
						tipos_documentos td 
							where 
						oc.estado = 'pagado' 
						and oc.tipo = td.idtipos_documentos
						and td.modulo like '%-3-%'
						and oc.fecha_orden ".$between."";  
		$res=mysql_query($sql)or die(mysql_error());  
		
		
		
		while($row=mysql_fetch_array($res)){  
			
			$sql_configuracion = mysql_query("select * from configuracion");
			$bus_configuracion = mysql_fetch_array($sql_configuracion);
			
			$sql_estados = mysql_query("select * from estado where idestado = '".$bus_configuracion["estado"]."'")or die(mysql_error());
			$bus_estados = mysql_fetch_array($sql_estados); 
			
			$lugar_ejecucion= $bus_estados["denominacion"];
			
			$numero_orden = explode("-",$row['numero_orden']);
			
			
			if($trimestre == 1)$trimestre="I";
			else if($trimestre == 2)$trimestre='II';
			else if($trimestre == 3)$trimestre = 'III';
			else if($trimestre == 4)$trimestre = 'IV';
			
			$codigo = $numero_orden[0]."-".$numero_orden[2];
			$periodo = $numero_orden[1];
			$objeto1= substr(utf8_decode($row["justificacion"]), 0, 255);
			$objeto2= " ".substr($row["justificacion"], 256, 500);
			$objeto= $objeto1.$objeto2;
			
			$fecha_i = explode("-",$row["fecha_inicio"]);
			$fecha_c = explode("-",$row["fecha_cierre"]);
			
			
			$fecha_inicio = $fecha_i[2]."/".$fecha_i[1]."/".$fecha_i[0];
			$fecha_cierre = $fecha_c[2]."/".$fecha_c[1]."/".$fecha_c[0];
			
			$sql_consulta_precios = mysql_query("select sc.tipo_actividad,
															sc.modo_comunicacion,
															sc.fecha_solicitud,
															psc.tipo_procedimiento
																from 
															relacion_compra_solicitud_cotizacion rcsc,
															solicitud_cotizacion sc,
															proveedores_solicitud_cotizacion psc
																where 
															rcsc.idorden_compra = '".$row["idorden_compra_servicio"]."'
															and rcsc.idsolicitud_cotizacion = sc.idsolicitud_cotizacion")or die(mysql_error());
				if (mysql_num_rows($sql_consulta_precios) > 0){
					$bus_consulta_precios = mysql_fetch_array($sql_consulta_precios);
					
					if($bus_consulta_precios["tipo_actividad"] == "bienes"){
						$actividad = "B";
					}else{
						$actividad = "S";
					}
					
					//$fecha_s = explode("-",$bus_consulta_precios["fecha_solicitud"]);
					//$fecha_inicio = $fecha_s[2]."/".$fecha_s[1]."/".$fecha_s[0];
					
					$sql_modalidad_contratacion = mysql_query("select * from modalidad_contratacion where idmodalidad_contratacion = '".$bus_consulta_precios["tipo_procedimiento"]."'") or die( mysql_error());
					$bus_modalidad_contratacion = mysql_fetch_array($sql_modalidad_contratacion);
					$modo_contratacion= $bus_modalidad_contratacion["descripcion"];
					$modo_comunicacion = $bus_consulta_precios["modo_comunicacion"];	
				}else{
					if($row["tipo_actividad"] == "bienes"){
						$actividad = "B";
					}else{
						$actividad = "S";
					}
					$modo_contratacion= $row["modo_contratacion"];
					$modo_comunicacion = strtoupper($row["modo_comunicacion"]);	
				}
			
			$decreto_4998= $row["decreto_4998"];
			$decreto_4910= $row["decreto_4910"];
			
				/*$csv.= $codigo.$csv_sep.
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
						$csv_end;  */
						
						
				
	  $data[] = array("CODIGO"       	   => $codigo, 
					  "PERIODO"      	   => $periodo, 
					  "TRIMESTRE"    	   => $trimestre, 
					  "FECHA_INICIO" 	   => $fecha_inicio, 
					  "FECHA_CIERRE" 	   => $fecha_cierre,
					  "ACTIVIDAD"    	   => $actividad,
					  "MODO_CONTRATACION"  => $modo_contratacion,
					  "LUGAR_EJECUCION"    => $lugar_ejecucion,
					  "MODO_COMUNICACION"  => $modo_comunicacion,
					  "OBJETO"  		   => $objeto1,
					  "DECRETO_4998"  	   => $decreto_4998,
					  "DECRETO_4910"       => $decreto_4910
					  );
		
		}  

		createExcel($nombre_archivo, $data);
		
		//Generamos el csv de todos los datos
		//echo $csv;
		break;
		
	case "detalleproc":
		// DETALLEPRODUC  *****************************************************************************************
		$csv_end = "$nl";  
		$csv_sep = "|";  
		$csv="";  
		
		
		$sql_configuracion = mysql_query("select * from configuracion");
		$bus_configuracion = mysql_fetch_array($sql_configuracion);
		
		$sql_estados = mysql_query("select * from estado where idestado = '".$bus_configuracion["estado"]."'")or die(mysql_error());
		$bus_estados = mysql_fetch_array($sql_estados); 
		
		$lugar_ejecucion= $bus_estados["denominacion"];
		
		
		$sql="SELECT * from 
						orden_compra_servicio oc,
						tipos_documentos td 
							where 
						oc.estado = 'pagado' 
						and oc.tipo = td.idtipos_documentos
						and td.modulo like '%-3-%'
						and oc.fecha_orden ".$between.""; 
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
		
		
		if($trimestre == 1)$trimestre="I";
		else if($trimestre == 2)$trimestre='II';
		else if($trimestre == 3)$trimestre = 'III';
		else if($trimestre == 4)$trimestre = 'IV';
		
		$numero_orden = explode("-",$bus_ordenes['numero_orden']);
		
		$periodo = $numero_orden[1];
		$codigo = $numero_orden[0]."-".$numero_orden[2];
		$codigo_ccce = substr($bus_detalle["codigo"],1,strlen($bus_detalle["codigo"]));
		$cantidad = number_format($row["cantidad"],2, ',', '.');
		$unidad_medida = utf8_decode($bus_detalle["descripcion_uni"]);
		$monto = number_format((($row["cantidad"]*$row["precio_unitario"])+$row["impuesto"]),2, ',', '.');
		$detalle = utf8_decode($bus_detalle["descripcion"]);
		
		
			/*$csv.= $periodo.$csv_sep.
					$trimestre.$csv_sep.
					$codigo.$csv_sep.
					$codigo_ccce.$csv_sep.
					$cantidad.$csv_sep.
					$unidad_medida.$csv_sep.
					$monto.$csv_sep.
					$detalle.
					$csv_end;  */
					
					
  $data[] = array("CODIGO"       	   => $codigo, 
				  "PERIODO"      	   => $periodo, 
				  "TRIMESTRE"    	   => $trimestre, 
				  "CODIGO_CCCE" 	   => $codigo_ccce,
				  "CANTIDAD"    	   => $cantidad,
				  "UNIDAD_MEDIDA"      => $unidad_medida,
				  "LUGAR_EJECUCION"    => $lugar_ejecucion,
				  "MONTO"   	       => $monto,
				  "DETALLE"  		   => $detalle
				  );
	
		  

		
		}
		}
		createExcel($nombre_archivo, $data);   
		break;
		
	case "contratistas":
		$csv_end = "$nl";  
		$csv_sep = "|";  
		$csv="";  
		
		$sql_o = mysql_query("SELECT * from 
						orden_compra_servicio oc,
						tipos_documentos td 
							where 
						oc.estado = 'pagado' 
						and oc.tipo = td.idtipos_documentos
						and td.modulo like '%-3-%'
						and oc.fecha_orden ".$between.""); 
						
		while($bus_o = mysql_fetch_array($sql_o)){
		
			$sql="SELECT
					sc.nro_orden,
					sncdg.codigo as rubro,
					sncdg.descripcion as detalle_rubro,
					be.nombre as nombre_beneficiario,
					be.rif,
					te.codigo as codigo_tipo_empresa,
					est.denominacion as codigo_estado,
					psc.ganador,
					((acs.cantidad*acs.precio_unitario)+acs.impuesto) as monto,
					ocs.fecha_inicio as fecha_inicio,
					ocs.fecha_cierre as fecha_cierre
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
					INNER JOIN relacion_compra_solicitud_cotizacion rcsc ON (sc.idsolicitud_cotizacion = rcsc.idsolicitud_cotizacion)
					INNER JOIN orden_compra_servicio ocs ON (ocs.idorden_compra_servicio = rcsc.idorden_compra)
						WHERE
					ocs.idorden_compra_servicio = '".$bus_o["idorden_compra_servicio"]."'
					GROUP BY nro_orden, be.idbeneficiarios, rubro"; 
			$res=mysql_query($sql)or die("ALLA".mysql_error());  
			$num_orden = mysql_num_rows($res);
			
			if($num_orden == 0){
			//echo "NUMEROOOOOO: ".$num_orden.". .................";
				$sql = "SELECT
					sc.numero_orden as nro_orden,
					sncdg.codigo as rubro,
					sncdg.descripcion as detalle_rubro,
					be.nombre as nombre_beneficiario,
					be.rif,
					te.codigo as codigo_tipo_empresa,
					est.denominacion as codigo_estado,
					((acs.cantidad*acs.precio_unitario)+acs.impuesto) as monto,
					sc.fecha_orden as fecha_solicitud,
					sc.fecha_inicio as fecha_inicio,
					sc.fecha_cierre as fecha_cierre
						FROM
					orden_compra_servicio sc
					INNER JOIN tipos_documentos td ON (sc.tipo = td.idtipos_documentos AND td.modulo like '%-3-%')
					INNER JOIN articulos_compra_servicio acs ON (acs.idorden_compra_servicio = sc.idorden_compra_servicio)
					INNER JOIN articulos_servicios arc ON (arc.idarticulos_servicios = acs.idarticulos_servicios)
					INNER JOIN snc_detalle_grupo sncdg ON (arc.idsnc_detalle_grupo = sncdg.idsnc_detalle_grupo)
					INNER JOIN snc_grupo_actividad sncga ON (sncdg.idsnc_grupo_actividad = sncga.idsnc_grupo_actividad)
					INNER JOIN beneficiarios be ON (sc.idbeneficiarios = be.idbeneficiarios)
					INNER JOIN tipo_empresa te ON (be.idtipo_empresa = te.idtipo_empresa)
					LEFT JOIN estado est ON (est.idestado = be.idestado)
						WHERE
					sc.idorden_compra_servicio = '".$bus_o["idorden_compra_servicio"]."'
					GROUP BY nro_orden, be.idbeneficiarios, rubro";
				$res=mysql_query($sql)or die("AQUI".mysql_error());  
			}
			
			
			
			while($bus_orden=mysql_fetch_array($res)){
			
			
				$numero_orden = explode("-",$bus_orden['nro_orden']);
				
				if($bus_orden["ganador"] == 'y'){
					$con = "Ganadora";
				}else{
					$con = "Participante";
				}
				
				if(!$bus_orden["ganador"]){
					$con = "Ganadora";
				}
				
				if($trimestre == 1)$trimestre="I";
				else if($trimestre == 2)$trimestre='II';
				else if($trimestre == 3)$trimestre = 'III';
				else if($trimestre == 4)$trimestre = 'IV';
				
				
				$codigo = $numero_orden[0]."-".$numero_orden[2];
				$periodo = $numero_orden[1];
				$rif = str_replace("-", "", $bus_orden["rif"]); //substr($bus_orden["rif"],0,1)."-".substr($bus_orden["rif"],2,(strlen($bus_orden["rif"])-1))."-".substr($bus_orden["rif"],(strlen($bus_orden["rif"])-1),strlen($bus_orden["rif"]));
				$nombre = utf8_decode($bus_orden["nombre_beneficiario"]);
				$tipo_empresa = $bus_orden["codigo_tipo_empresa"];
				$estado = $bus_orden["codigo_estado"];
				$condicion = $con;
				$rubro = $bus_orden["rubro"]; //substr($bus_orden["rubro"],1,strlen($bus_orden["rubro"]));
				$rubro_detalle = utf8_decode($bus_orden["detalle_rubro"]);
				$monto = number_format(($bus_orden["monto"]*1),2,",","");
				$por_van = '0';
				$por_anticipo = '0';
				$contrato = $numero_orden[0]."-".$numero_orden[2];
				
				
				$fecha_i = explode("-",$bus_orden["fecha_inicio"]);
				$fecha_c = explode("-",$bus_orden["fecha_cierre"]);
				
				
				$fecha_inicio = $fecha_i[2]."/".$fecha_i[1]."/".$fecha_i[0];
				$fecha_cierre = $fecha_c[2]."/".$fecha_c[1]."/".$fecha_c[0];
				
				$grs  = '0';
				
				
					/*$csv.= $periodo.$csv_sep.
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
							$csv_end;  */
							
							
		  $data[] = array("PERIODO"      	   => $periodo, 
						  "TRIMESTRE"    	   => $trimestre, 
						  "CODIGO"       	   => $codigo, 
						  "RIF" 	   	 	   => $rif, 
						  "NOMBRE" 	   		   => $nombre,
						  "TIPO_EMPRESA"       => $tipo_empresa,
						  "ESTADO"  		   => $estado,
						  "CONDICION"          => $condicion,
						  "RUBRO"              => $rubro,
						  "DETALLE RUBRO"      => $rubro_detalle,
						  "MONTO"  		       => $monto,
						  "POR_VAN"  	       => $por_van,
						  "POR_ANTICIPO"       => $por_anticipo,
						  "CONTRATO"           => $contrato,
						  "FECHA_INICIO"       => $fecha_inicio,
						  "FECHA_FIN"          => $fecha_cierre,
						  "GRS"                => $grs
						  );
					
				}
			} 
		createExcel($nombre_archivo, $data);
		//echo $csv;
		break;
}
?>