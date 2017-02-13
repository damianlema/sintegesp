<?php
$nombre_archivo = strtr($nombre_archivo, " ", "_");
$nombre_archivo=$nombre_archivo.".xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: filename=\"".$nombre_archivo."\";");
session_start();
set_time_limit(-1);
require('../../../conf/conex.php');
Conectarse();
extract($_GET);
extract($_POST);
$LF = 0x0A;
$CR = 0x0D;
$nl = sprintf("%c%c",$CR,$LF);  
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
?>

<?php
//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Sumario de Contrataciones...
	case "infogen":
		//$tr1="background-color:RGB(225, 255, 255); font-size:12px; font-weight:bold;";
		//$tr5="font-size:12px;";
		//$span="color:#FF0000; font-size:8px; text-align:right;";
		
		if ($trimestre==1) { $desde=$anio."-01-01"; $hasta=$anio."-03-31"; $tri="I Trimestre"; }
		if ($trimestre==2) { $desde=$anio."-04-01"; $hasta=$anio."-06-30"; $tri="II Trimestre"; }
		if ($trimestre==3) { $desde=$anio."-07-01"; $hasta=$anio."-09-30"; $tri="III Trimestre"; }
		if ($trimestre==4) { $desde=$anio."-10-01"; $hasta=$anio."-12-31"; $tri="IV Trimestre"; }
		if($trimestre == 1)$between="BETWEEN '".$anio."-01-01' AND '".$anio."-03-31'";
		if($trimestre == 2)$between="BETWEEN '".$anio."-04-01' AND '".$anio."-06-30'";
		if($trimestre == 3)$between="BETWEEN '".$anio."-07-01' AND '".$anio."-09-30'";
		if($trimestre == 4)$between="BETWEEN '".$anio."-10-01' AND '".$anio."-12-31'";
		
		
		echo "
		<table border='1'>
			<tr>
				<th width='80' style='font-size:10'>CODIGO</th>
				<th width='60' style='font-size:10'>PERIODO</th>
				<th width='65' style='font-size:10'>TRIMESTRE</th>
				<th width='90' style='font-size:10'>FECHA_INICIO</th>
				<th width='90' style='font-size:10'>FECHA_CIERRE</th>
				<th width='70' style='font-size:10'>ACTIVIDAD</th>
				<th width='300' style='font-size:10'>MODO_CONTRATACION</th>
				<th width='115' style='font-size:10'>LUGAR_EJECUCION</th>
				<th width='120' style='font-size:10'>MODO_COMUNICACION</th>
				<th width='1000' style='font-size:10'>OBJETO</th>
				<th width='90' style='font-size:10'>DECRETO_4998</th>
				<th width='90' style='font-size:10'>DECRETO_4910</th>
			</tr>";
		//	---------------------------------------------------------
		
		
		$sql="SELECT * from 
						orden_compra_servicio oc,
						tipos_documentos td 
							where 
						oc.estado = 'pagado' 
						and oc.tipo = td.idtipos_documentos
						and td.modulo like '%-3-%'
						and oc.fecha_orden ".$between."";
						
		$query=mysql_query($sql) or die ($sql.mysql_error());
		
		while ($row=mysql_fetch_array($query)) {
			
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
			$objeto= utf8_decode($row["justificacion"]);
			
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
			//echo $bus_consulta_precios["tipo_procedimiento"];
			
			$decreto_4998= $row["decreto_4998"];
			$decreto_4910= $row["decreto_4910"];
			
			
			
			echo "
			<tr>
				<td style='font-size:10'>".$codigo."</td>
				<td style='font-size:10'>".$periodo."</td>
				<td style='font-size:10'>".$trimestre."</td>
				<td style='font-size:10'>".$fecha_inicio."</td>
				<td style='font-size:10'>".$fecha_cierre."</td>
				<td style='font-size:10'>".$actividad."</td>
				<td style='font-size:10'>".$modo_contratacion."</td>
				<td style='font-size:10'>".$lugar_ejecucion."</td>
				<td style='font-size:10'>".$modo_comunicacion."</td>
				<td style='font-size:10'>".$objeto."</td>
				<td style='font-size:10'>".$decreto_4998."</td>
				<td style='font-size:10'>".$decreto_4910."</td>
			</tr>";
		}
		//$suma_total=number_format($suma_total, 2, ',', '');
		echo "</table>";
		break;
		
	//	Sumario de Empresas...
	case "detalleproc":
		//$tr1="background-color:RGB(225, 255, 255); font-size:12px; font-weight:bold;";
		//$tr5="font-size:12px;";
		//$span="color:#FF0000; font-size:8px; text-align:right;";
		if ($trimestre==1) { $desde=$anio."-01-01"; $hasta=$anio."-03-31"; $tri="I Trimestre"; }
		if ($trimestre==2) { $desde=$anio."-04-01"; $hasta=$anio."-06-30"; $tri="II Trimestre"; }
		if ($trimestre==3) { $desde=$anio."-07-01"; $hasta=$anio."-09-30"; $tri="III Trimestre"; }
		if ($trimestre==4) { $desde=$anio."-10-01"; $hasta=$anio."-12-31"; $tri="IV Trimestre"; }
		
		
		$sql_configuracion = mysql_query("select * from configuracion");
		$bus_configuracion = mysql_fetch_array($sql_configuracion);
		
		$sql_estados = mysql_query("select * from estado where idestado = '".$bus_configuracion["estado"]."'")or die(mysql_error());
		$bus_estados = mysql_fetch_array($sql_estados); 
		
		$lugar_ejecucion= $bus_estados["denominacion"];
		
		
		if($trimestre == 1)$between="BETWEEN '".$anio."-01-01' AND '".$anio."-03-31'";
		if($trimestre == 2)$between="BETWEEN '".$anio."-04-01' AND '".$anio."-06-30'";
		if($trimestre == 3)$between="BETWEEN '".$anio."-07-01' AND '".$anio."-09-30'";
		if($trimestre == 4)$between="BETWEEN '".$anio."-10-01' AND '".$anio."-12-31'";
		
		
		echo "
		<table border='1'>
			<tr>
				<th width='80' style='font-size:10'>CODIGO</th>
				<th width='60' style='font-size:10'>PERIODO</th>
				<th width='80' style='font-size:10'>TRIMESTRE</th>
				<th width='80' style='font-size:10'>CODIGO_CCCE</th>
				<th width='80' style='font-size:10'>CANTIDAD</th>
				<th width='90' style='font-size:10'>UNIDAD_MEDIDA</th>
				<th width='115' style='font-size:10'>LUGAR_EJECUCION</th>
				<th width='100' style='font-size:10'>MONTO</th>
				<th width='600' style='font-size:10'>DETALLE</th>
			</tr>";
		//	---------------------------------------------------------
		//	---------------------------------------------------------
		//	Consulto e imprimo el cuerpo.....
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
				$cantidad = number_format(($row["cantidad"]*1),2, ',', '.');
				$unidad_medida = utf8_decode($bus_detalle["descripcion_uni"]);
				$monto = number_format((($row["cantidad"]*$row["precio_unitario"])+$row["impuesto"]),2, ',', '.');
				$detalle = utf8_decode($bus_detalle["descripcion"]);
				echo "
				<tr>
					<td style='font-size:10'>".$codigo."</td>
					<td style='font-size:10'>".$periodo."</td>
					<td style='font-size:10'>".$trimestre."</td>
					<td style='font-size:10'>".$codigo_ccce."</td>
					<td style='font-size:10'>".$cantidad."</td>
					<td style='font-size:10'>".$unidad_medida."</td>
					<td style='font-size:10'>".$lugar_ejecucion."</td>
					<td style='font-size:10'>".$monto."</td>
					<td style='font-size:10'>".$detalle."</td>
				</tr>";
				$total=0;
			}
		}
		
		echo "</table>";
		break;

	//	Sumario de Empresas...
	case "contratistas":
		//$tr1="background-color:RGB(225, 255, 255); font-size:12px; font-weight:bold;";
		//$tr5="font-size:12px;";
		//$span="color:#FF0000; font-size:8px; text-align:right;";
		if ($trimestre==1) { $desde=$anio."-01-01"; $hasta=$anio."-03-31"; $tri="I Trimestre"; }
		if ($trimestre==2) { $desde=$anio."-04-01"; $hasta=$anio."-06-30"; $tri="II Trimestre"; }
		if ($trimestre==3) { $desde=$anio."-07-01"; $hasta=$anio."-09-30"; $tri="III Trimestre"; }
		if ($trimestre==4) { $desde=$anio."-10-01"; $hasta=$anio."-12-31"; $tri="IV Trimestre"; }
		
		
		$sql_configuracion = mysql_query("select * from configuracion");
		$bus_configuracion = mysql_fetch_array($sql_configuracion);
		
		$sql_estados = mysql_query("select * from estado where idestado = '".$bus_configuracion["estado"]."'")or die(mysql_error());
		$bus_estados = mysql_fetch_array($sql_estados); 
		
		$lugar_ejecucion= $bus_estados["denominacion"];
		
		
		if($trimestre == 1)$between="BETWEEN '".$anio."-01-01' AND '".$anio."-03-31'";
		if($trimestre == 2)$between="BETWEEN '".$anio."-04-01' AND '".$anio."-06-30'";
		if($trimestre == 3)$between="BETWEEN '".$anio."-07-01' AND '".$anio."-09-30'";
		if($trimestre == 4)$between="BETWEEN '".$anio."-10-01' AND '".$anio."-12-31'";
		
		
		echo "
		<table border='1'>
			<tr>
				<th width='60' style='font-size:10'>PERIODO</th>
				<th width='80' style='font-size:10'>TRIMESTRE</th>
				<th width='80' style='font-size:10'>CODIGO</th>
				<th width='80' style='font-size:10'>RIF</th>
				<th width='300' style='font-size:10'>NOMBRE</th>
				<th width='40' style='font-size:10'>TIPO</th>
				<th width='115' style='font-size:10'>ESTADO</th>
				<th width='100' style='font-size:10'>CONDICION</th>
				<th width='80' style='font-size:10'>RUBRO</th>
				<th width='600' style='font-size:10'>DETALLE_RUBRO</th>
				<th width='100' style='font-size:10'>MONTO</th>
				<th width='100' style='font-size:10'>POR_VAN</th>
				<th width='100' style='font-size:10'>POR_ANTICIPO</th>
				<th width='100' style='font-size:10'>CONTRATO</th>
				<th width='100' style='font-size:10'>FECHA_INICIO</th>
				<th width='100' style='font-size:10'>FECHA_FIN</th>
				<th width='100' style='font-size:10'>GRS</th>
			</tr>";
		//	---------------------------------------------------------
		//	---------------------------------------------------------
		//	Consulto e imprimo el cuerpo.....
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
				echo "
				<tr>
					<td style='font-size:10'>".$periodo."</td>
					<td style='font-size:10'>".$trimestre."</td>
					<td style='font-size:10'>".$codigo."</td>
					<td style='font-size:10'>".$rif."</td>
					<td style='font-size:10'>".$nombre."</td>
					<td style='font-size:10'>".$tipo_empresa."</td>
					<td style='font-size:10'>".$estado."</td>
					<td style='font-size:10'>".$condicion."</td>
					<td style='font-size:10'>".$rubro."</td>
					<td style='font-size:10'>".$rubro_detalle."</td>
					<td style='font-size:10'>".$monto."</td>
					<td style='font-size:10'>".$por_van."</td>
					<td style='font-size:10'>".$por_anticipo."</td>
					<td style='font-size:10'>".$contrato."</td>
					<td style='font-size:10'>".$fecha_inicio."</td>
					<td style='font-size:10'>".$fecha_cierre."</td>
					<td style='font-size:10'>".$grs."</td>
				</tr>";
				$total=0;
			}
		}
		
		echo "</table>";
		break;









	
	//	Evaluacion de Desempeño...
	case "filtro_sumarios_desempenio":
		$tr1="background-color:RGB(225, 255, 255); font-size:12px; font-weight:bold;";
		$tr5="font-size:12px;";
		$span="color:#FF0000; font-size:8px; text-align:right;";
		if ($trimestre==1) { $desde=$anio."-01-01"; $hasta=$anio."-03-31"; $tri="I Trimestre"; }
		if ($trimestre==2) { $desde=$anio."-04-01"; $hasta=$anio."-06-30"; $tri="II Trimestre"; }
		if ($trimestre==3) { $desde=$anio."-07-01"; $hasta=$anio."-09-30"; $tri="III Trimestre"; }
		if ($trimestre==4) { $desde=$anio."-10-01"; $hasta=$anio."-12-31"; $tri="IV Trimestre"; }
		//	---------------------------------------------------------
		//	Consulto e imprimo la cabecera.....
		echo "
		<table border='1'>
			<tr>
				<td width='500' style='$tr1' colspan='2'>ORGANO O ENTE RESPONSABLE</td>
				<td width='500' style='$tr5' colspan='3'>MINISTERIO DE SALUD</td>
			</tr>
			<tr>
				<td width='500' style='$tr1' colspan='2'>ORGANO O ENTE EJECUTOR</td>
				<td width='500' style='$tr5' colspan='3'></td>
			</tr>
			<tr>
				<td width='500' style='$tr1' colspan='2'>UBICACION</td>
				<td width='500' style='$tr5' colspan='3'></td>
			</tr>
			<tr>
				<td width='500' style='$tr1' colspan='2'>FUNCIONARIO RESPONSABLE</td>
				<td width='500' style='$tr5' colspan='3'></td>
			</tr>
			<tr>
				<td width='500' style='$tr1' colspan='2'>C.I. / CARGO></td>
				<td width='500' style='$tr5' colspan='3'></td>
			</tr>
			<tr>
				<td width='500' style='$tr1' colspan='2'>CORREO ELECTRONICO</td>
				<td width='500' style='$tr5' colspan='3'></td>
			</tr>
			<tr>
				<td width='500' style='$tr1' colspan='2'>TELEFONO</td>
				<td width='500' style='$tr5' colspan='3'></td>
			</tr>
			<tr>
				<td width='500' style='$tr1' colspan='2'>FECHA</td>
				<td width='500' style='$tr5' colspan='3'></td>
			</tr>
		</table>";
		echo "
		<table border='1'>
			<tr>
				<th width='350' style='$tr1'>NOMBRE DE LA EMPRESA CONTRATADA</th>
				<th width='200' style='$tr1'>RIF DE LA EMPRESA COMTRATADA</th>
				<th width='200' style='$tr1'>NRO. DE CONTRATO / ORDEN DE COMPRA</th>
				<th width='400' style='$tr1'>NOMBRE U OBJETO DEL PROCEDIMIENTO</th>
				<th width='200' style='$tr1'>FECHA DE INICIO DE CONTRATO</th>
				<th width='200' style='$tr1'>FECHA DE CULMINACION SEGUN CONTRATO</th>
				<th width='200' style='$tr1'>NUMERO DE PRORROGAS</th>
				<th width='200' style='$tr1'>FECHA DE CULMINACION DEFINITIVA</th>
				<th width='350' style='$tr1'>MONTO DEL CONTRATO BS</th>
				<th width='200' style='$tr1'>NRO. DE PROCEDIMIENTO ORDEN DE COMPRA O SERVICIO</th>
				<th width='200' style='$tr1'>TIPO DE PROCDIMIENTO</th>
				<th width='200' style='$tr1'>TIPO DE EMPRESA</th>
				<th width='200' style='$tr1'>TIEMPO DE ENTREGA</th>
				<th width='200' style='$tr1'>TECNICO / CALIDAD</th>
				<th width='200' style='$tr1'>GERENCIA ADMINISTRATIVA</th>
				<th width='200' style='$tr1'>SHA</th>
				<th width='200' style='$tr1'>LABORAL</th>
				<th width='200' style='$tr1'>IMPACTO AMBIENTAL</th>
				<th width='200' style='$tr1'>COMPROMISO DE RESPONSABILIDAD SOCIAL</th>
				<th width='200' style='$tr1'>% VAN</th>
				<th width='200' style='$tr1'>OBSERVACIONES</th>
			</tr>";
		//	---------------------------------------------------------
		$filtro="(ocs.estado='procesado' OR ocs.estado='pagado') AND ocs.anio='".$anio."'";
		if ($idbeneficiario!="") $filtro.=" AND ocs.idbeneficiarios='".$idbeneficiario."'";
		//	Consulto e imprimo el cuerpo.....
		$sql="SELECT
						ocs.numero_orden,
						ocs.justificacion,
						ocs.fecha_orden,
						ocs.total,
						ocs.numero_orden,
						b.nombre AS Beneficiario,
						b.rif
				FROM
						orden_compra_servicio ocs
						INNER JOIN beneficiarios b ON (ocs.idbeneficiarios=b.idbeneficiarios)
				WHERE
						$filtro
				ORDER BY ocs.codigo_referencia";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$total=number_format($field['total'], 2, ',', '');
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;	
			echo "
			<tr>
				<td>".utf8_decode($field['Beneficiario'])."</td>
				<td>".$field['rif']."</td>
				<td>".$field['numero_orden']."</td>
				<td>".utf8_decode($field['justificacion'])."</td>
				<td>".$fecha."</td>
				<td></td>
				<td>0</td>
				<td></td>
				<td>=DECIMAL(".$total."; 2)</td>
				<td>".$field['numero_orden']."</td>
				<td>ADJUDICACION DIRECTA</td>
				<td>PYMIS</td>
				<td>BUENO</td>
				<td>BUENO</td>
				<td>BUENO</td>
				<td>BUENO</td>
				<td>BUENO</td>
				<td>BUENO</td>
				<td>BUENO</td>
				<td>12%</td>
				<td>S/N OBSERVACIONES</td>
			</tr>";
		}
		echo "</table>";
		break;
		
	//	Orden de Compra/Servicio...
	case "filtro_orden_compra_servicio":
		echo "<table>";
		//---------------------------------------------------
		$filtro=""; $dbeneficiario=0; $dcategoria=0; $dperiodo=1; $dtipo=0; $darticulo=0; $destado=0; $head=0;
		//	------------------
		if ($_GET['idbeneficiario']!="") { $filtro=" AND (beneficiarios.idbeneficiarios='".$_GET['idbeneficiario']."') "; $dbeneficiario=1; $head=2; }
		if ($_GET['idcategoria']!=0) { 
			$filtro.=" AND (categoria_programatica.idcategoria_programatica='".$_GET['idcategoria']."') "; $dcategoria=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['idarticulo']!="") { 
			$filtro.=" AND (articulos_servicios.idarticulos_servicios='".$_GET['idarticulo']."') "; $darticulo=1;
			if ($dbeneficiario==1) $head=2; else $head=9;
		}
		if ($_GET['desde']!="" && $_GET['hasta']!="") {
			list($a, $m, $d)=SPLIT( '[/.-]', $_GET['desde']); $fecha_desde=$a."-".$m."-".$d;
			list($a, $m, $d)=SPLIT( '[/.-]', $_GET['hasta']); $fecha_hasta=$a."-".$m."-".$d;
			$filtro.=" AND (orden_compra_servicio.fecha_orden>='".$fecha_desde."' AND orden_compra_servicio.fecha_orden<='".$fecha_hasta."') "; $dperiodo=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['idtipo']!="0") { 
			$filtro.=" AND (tipos_documentos.idtipos_documentos='".$_GET['idtipo']."') "; $dtipo=1;
			if ($dbeneficiario==1) $head=4; else $head=3;
		}
		if ($_GET['idestado']!="0") { 
			$filtro.=" AND (orden_compra_servicio.estado='".$_GET['idestado']."') "; $destado=1;
			if ($head==0) $head=5;
			elseif ($head==2) $head=6;
			elseif ($head==3) $head=7;
			elseif ($head==4) $head=8;
		}
		if ($filtro=="") $head=1;
		////////////
		if ($head==9)
			$sql="SELECT orden_compra_servicio.nro_factura, 
						 orden_compra_servicio.idorden_compra_servicio,
						 orden_compra_servicio.codigo_referencia, 
						 orden_compra_servicio.numero_orden, 
						 orden_compra_servicio.fecha_orden, 
						 orden_compra_servicio.estado, 
						 beneficiarios.nombre, 
						 tipos_documentos.descripcion, 
						 categoria_programatica.idcategoria_programatica, 
						 categoria_programatica.codigo, 
						 unidad_ejecutora.denominacion, 
						 articulos_compra_servicio.idarticulos_servicios, 
						 articulos_servicios.descripcion As articulo, 
						 orden_compra_servicio.estado, 
						 articulos_compra_servicio.precio_unitario as total
					FROM 
						 orden_compra_servicio, 
						 tipos_documentos, 
						 beneficiarios, 
						 categoria_programatica, 
						 unidad_ejecutora, 
						 articulos_compra_servicio, 
						 articulos_servicios 
					WHERE 
						 (orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios) AND 
						 (orden_compra_servicio.tipo=tipos_documentos.idtipos_documentos) AND 
						 (orden_compra_servicio.idcategoria_programatica=categoria_programatica.idcategoria_programatica AND
						 categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND 
						 (tipos_documentos.modulo like '%-3-%') AND 
						 (orden_compra_servicio.idorden_compra_servicio=articulos_compra_servicio.idorden_compra_servicio AND
						 articulos_compra_servicio.idarticulos_servicios=articulos_servicios.idarticulos_servicios) $filtro  
					ORDER BY orden_compra_servicio.codigo_referencia";
		elseif ($_GET['idarticulo']!="")
			$sql="SELECT orden_compra_servicio.nro_factura, 
						 orden_compra_servicio.codigo_referencia, 
						 orden_compra_servicio.idorden_compra_servicio,
						 orden_compra_servicio.numero_orden, 
						 orden_compra_servicio.fecha_orden, 
						 orden_compra_servicio.estado, 
						 beneficiarios.nombre, 
						 tipos_documentos.descripcion, 
						 categoria_programatica.idcategoria_programatica, 
						 categoria_programatica.codigo, 
						 unidad_ejecutora.denominacion, 
						 articulos_compra_servicio.idarticulos_servicios, 
						 articulos_servicios.descripcion As articulo, 
						 orden_compra_servicio.estado, 
						 orden_compra_servicio.total 
					FROM 
						 orden_compra_servicio, 
						 tipos_documentos, 
						 beneficiarios, 
						 categoria_programatica, 
						 unidad_ejecutora, 
						 articulos_compra_servicio, 
						 articulos_servicios 
					WHERE 
						 (orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios) AND 
						 (orden_compra_servicio.tipo=tipos_documentos.idtipos_documentos) AND 
						 (orden_compra_servicio.idcategoria_programatica=categoria_programatica.idcategoria_programatica AND
						 categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND 
						 (tipos_documentos.modulo like '%-3-%') AND 
						 (orden_compra_servicio.idorden_compra_servicio=articulos_compra_servicio.idorden_compra_servicio AND
						 articulos_compra_servicio.idarticulos_servicios=articulos_servicios.idarticulos_servicios) $filtro  
					ORDER BY orden_compra_servicio.codigo_referencia";
		else
			$sql="SELECT orden_compra_servicio.nro_factura, 
						 orden_compra_servicio.idorden_compra_servicio, 
						 orden_compra_servicio.codigo_referencia, 
						 orden_compra_servicio.numero_orden, 
						 orden_compra_servicio.fecha_orden, 
						 orden_compra_servicio.estado, 
						 beneficiarios.nombre, 
						 tipos_documentos.descripcion, 
						 categoria_programatica.idcategoria_programatica,
						 categoria_programatica.codigo, 
						 unidad_ejecutora.denominacion, 
						 orden_compra_servicio.estado, 
						 orden_compra_servicio.total 
					FROM 
						 orden_compra_servicio, 
						 tipos_documentos, 
						 beneficiarios, 
						 categoria_programatica, 
						 unidad_ejecutora 
					WHERE 
						 (orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios) AND 
						 (orden_compra_servicio.tipo=tipos_documentos.idtipos_documentos) AND
						 (orden_compra_servicio.idcategoria_programatica=categoria_programatica.idcategoria_programatica AND
						 categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND 
						 (tipos_documentos.modulo like '%-3-%') $filtro 
					ORDER BY orden_compra_servicio.codigo_referencia";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//---------------------------------------------------
		if ($head==1) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				
				$total=number_format($field['total'], 2, ',', ''); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				
				//
				$sql = "SELECT
							d.denominacion AS dependencia
						FROM 
							dependencias d
							INNER JOIN relacion_documentos_remision rdr ON (rdr.iddependencia_origen = d.iddependencia)
						WHERE
							rdr.tabla = 'orden_compra_servicio' AND 
							rdr.id_documento = '".$field['idorden_compra_servicio']."' AND
							rdr.idrelacion_documentos_remision = (SELECT MAX(idrelacion_documentos_remision) FROM relacion_documentos_remision WHERE tabla = 'orden_compra_servicio' AND id_documento = '".$field['idorden_compra_servicio']."')";
				$query_ubicacion_actual = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_ubicacion_actual) != 0) $field_ubicacion_actual = mysql_fetch_array($query_ubicacion_actual);
				//
				
				if ($grupo != $field['idcategoria_programatica']) {
					if ($i != 0) {
						echo "<tr><td align='right' colspan='8' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
						$sum_sub_total = 0;
					}
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['codigo'].' - '.$field['denominacion']);
					
					echo "<tr><td colspan='8'>&nbsp;</td></tr>";
					echo "<tr><td colspan='8' style='$cat'>".$categoria."</td></tr>";
					if ($desde!="" && $hasta!="") {
						list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fecha_desde=$d."/".$m."/".$a;
						list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fecha_hasta=$d."/".$m."/".$a;
						echo "<tr><td colspan='8' style='$cat'>".$fecha_desde." a ".$fecha_hasta."</td></tr>";
					}
					echo "
					<tr>
						<td align='center' style='$titulo'>Nro. O/C</td>
						<td align='center' style='$titulo'>Fecha O/C</td>
						<td align='center' style='$titulo'>Nro. Factura</td>
						<td align='center' style='$titulo'>Estado</td>
						<td align='center' style='$titulo'>Tipo</td>
						<td align='center' style='$titulo'>Proveedor</td>
						<td align='center' style='$titulo'>Ubicacion Actual</td>
						<td align='center' style='$titulo'>Total</td>
					</tr>";
				}
				
				echo "
				<tr>
					<td align='center' style='$esp'>".$field['numero_orden']."</td>
					<td align='center' style='$esp'>".$fecha."</td>
					<td align='center' style='$esp'>".$field['nro_factura']."</td>
					<td align='center' style='$esp'>".$estado."</td>
					<td style='$esp'>".utf8_decode($field['descripcion'])."</td>
					<td style='$esp'>".utf8_decode($field['nombre'])."</td>
					<td style='$esp'>".utf8_decode($field_ubicacion_actual['dependencia'])."</td>
					<td align='right' style='$esp'>=DECIMAL(".$total."; 2)</td>
				</tr>";
			}
			echo "<tr><td align='right' colspan='8' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
		}
		//----------------------------------------------------
		elseif ($head==2) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				
				$total=number_format($field['total'], 2, ',', ''); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				
				//
				$sql = "SELECT
							d.denominacion AS dependencia
						FROM 
							dependencias d
							INNER JOIN relacion_documentos_remision rdr ON (rdr.iddependencia_origen = d.iddependencia)
						WHERE
							rdr.tabla = 'orden_compra_servicio' AND 
							rdr.id_documento = '".$field['idorden_compra_servicio']."' AND
							rdr.idrelacion_documentos_remision = (SELECT MAX(idrelacion_documentos_remision) FROM relacion_documentos_remision WHERE tabla = 'orden_compra_servicio' AND id_documento = '".$field['idorden_compra_servicio']."')";
				$query_ubicacion_actual = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_ubicacion_actual) != 0) $field_ubicacion_actual = mysql_fetch_array($query_ubicacion_actual);
				//
				
				if ($grupo != $field['idcategoria_programatica']) {
					if ($i != 0) {
						echo "<tr><td align='right' colspan='7' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
						$sum_sub_total = 0;
					}
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['codigo'].' - '.$field['denominacion']);
					
					echo "<tr><td colspan='7'>&nbsp;</td></tr>";
					echo "<tr><td colspan='7' style='$cat'>".$categoria."</td></tr>";
					echo "<tr><td colspan='7' style='$cat'>".utf8_decode($field['nombre'])."</td></tr>";
					if ($desde!="" && $hasta!="") {
						list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fecha_desde=$d."/".$m."/".$a;
						list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fecha_hasta=$d."/".$m."/".$a;
						echo "<tr><td colspan='7' style='$cat'>".$fecha_desde." a ".$fecha_hasta."</td></tr>";
					}
					echo "
					<tr>
						<td align='center' style='$titulo'>Nro. O/C</td>
						<td align='center' style='$titulo'>Fecha O/C</td>
						<td align='center' style='$titulo'>Nro. Factura</td>
						<td align='center' style='$titulo'>Estado</td>
						<td align='center' style='$titulo'>Tipo</td>
						<td align='center' style='$titulo'>Ubicacion Actual</td>
						<td align='center' style='$titulo'>Total</td>
					</tr>";
				}
				
				echo "
				<tr>
					<td align='center' style='$esp'>".$field['numero_orden']."</td>
					<td align='center' style='$esp'>".$fecha."</td>
					<td align='center' style='$esp'>".$field['nro_factura']."</td>
					<td align='center' style='$esp'>".$estado."</td>
					<td style='$esp'>".utf8_decode($field['descripcion'])."</td>
					<td style='$esp'>".utf8_decode($field_ubicacion_actual['dependencia'])."</td>
					<td align='right' style='$esp'>=DECIMAL(".$total."; 2)</td>
				</tr>";
			}
			echo "<tr><td align='right' colspan='7' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
		}
		//----------------------------------------------------
		elseif ($head==3) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				
				$total=number_format($field['total'], 2, ',', ''); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				
				//
				$sql = "SELECT
							d.denominacion AS dependencia
						FROM 
							dependencias d
							INNER JOIN relacion_documentos_remision rdr ON (rdr.iddependencia_origen = d.iddependencia)
						WHERE
							rdr.tabla = 'orden_compra_servicio' AND 
							rdr.id_documento = '".$field['idorden_compra_servicio']."' AND
							rdr.idrelacion_documentos_remision = (SELECT MAX(idrelacion_documentos_remision) FROM relacion_documentos_remision WHERE tabla = 'orden_compra_servicio' AND id_documento = '".$field['idorden_compra_servicio']."')";
				$query_ubicacion_actual = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_ubicacion_actual) != 0) $field_ubicacion_actual = mysql_fetch_array($query_ubicacion_actual);
				//
				
				if ($grupo != $field['idcategoria_programatica']) {
					if ($i != 0) {
						echo "<tr><td align='right' colspan='7' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
						$sum_sub_total = 0;
					}
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['codigo'].' - '.$field['denominacion']);
					
					echo "<tr><td colspan='7'>&nbsp;</td></tr>";
					echo "<tr><td colspan='7' style='$cat'>".$categoria."</td></tr>";
					echo "<tr><td colspan='7' style='$cat'>".utf8_decode($field['descripcion'])."</td></tr>";
					if ($desde!="" && $hasta!="") {
						list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fecha_desde=$d."/".$m."/".$a;
						list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fecha_hasta=$d."/".$m."/".$a;
						echo "<tr><td colspan='7' style='$cat'>".$fecha_desde." a ".$fecha_hasta."</td></tr>";
					}				
					echo "
					<tr>
						<td align='center' style='$titulo'>Nro. O/C</td>
						<td align='center' style='$titulo'>Fecha O/C</td>
						<td align='center' style='$titulo'>Nro. Factura</td>
						<td align='center' style='$titulo'>Estado</td>
						<td align='center' style='$titulo'>Proveedor</td>
						<td align='center' style='$titulo'>Ubicacion Actual</td>
						<td align='center' style='$titulo'>Total</td>
					</tr>";
				}
				
				echo "
				<tr>
					<td align='center' style='$esp'>".$field['numero_orden']."</td>
					<td align='center' style='$esp'>".$fecha."</td>
					<td align='center' style='$esp'>".$field['nro_factura']."</td>
					<td align='center' style='$esp'>".$estado."</td>
					<td style='$esp'>".utf8_decode($field['nombre'])."</td>
					<td style='$esp'>".utf8_decode($field_ubicacion_actual['dependencia'])."</td>
					<td align='right' style='$esp'>=DECIMAL(".$total."; 2)</td>
				</tr>";
			}
			echo "<tr><td align='right' colspan='7' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
		}
		//----------------------------------------------------
		elseif ($head==4) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				
				$total=number_format($field['total'], 2, ',', ''); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				
				//
				$sql = "SELECT
							d.denominacion AS dependencia
						FROM 
							dependencias d
							INNER JOIN relacion_documentos_remision rdr ON (rdr.iddependencia_origen = d.iddependencia)
						WHERE
							rdr.tabla = 'orden_compra_servicio' AND 
							rdr.id_documento = '".$field['idorden_compra_servicio']."' AND
							rdr.idrelacion_documentos_remision = (SELECT MAX(idrelacion_documentos_remision) FROM relacion_documentos_remision WHERE tabla = 'orden_compra_servicio' AND id_documento = '".$field['idorden_compra_servicio']."')";
				$query_ubicacion_actual = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_ubicacion_actual) != 0) $field_ubicacion_actual = mysql_fetch_array($query_ubicacion_actual);
				//
				
				if ($grupo != $field['idcategoria_programatica']) {
					if ($i != 0) {
						echo "<tr><td align='right' colspan='6' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
						$sum_sub_total = 0;
					}
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['codigo'].' - '.$field['denominacion']);
					
					echo "<tr><td colspan='6'>&nbsp;</td></tr>";
					echo "<tr><td colspan='6' style='$cat'>".$categoria."</td></tr>";
					echo "<tr><td colspan='6' style='$cat'>".utf8_decode($field['nombre'])."</td></tr>";
					echo "<tr><td colspan='6' style='$cat'>".utf8_decode($field['descripcion'])."</td></tr>";
					if ($desde!="" && $hasta!="") {
						list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fecha_desde=$d."/".$m."/".$a;
						list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fecha_hasta=$d."/".$m."/".$a;
						echo "<tr><td colspan='6' style='$cat'>".$fecha_desde." a ".$fecha_hasta."</td></tr>";
					}
					echo "
					<tr>
						<td align='center' style='$titulo'>Nro. O/C</td>
						<td align='center' style='$titulo'>Fecha O/C</td>
						<td align='center' style='$titulo'>Nro. Factura</td>
						<td align='center' style='$titulo'>Estado</td>
						<td align='center' style='$titulo'>Ubicacion Actual</td>
						<td align='center' style='$titulo'>Total</td>
					</tr>";
				}
				
				echo "
				<tr>
					<td align='center' style='$esp'>".$field['numero_orden']."</td>
					<td align='center' style='$esp'>".$fecha."</td>
					<td align='center' style='$esp'>".$field['nro_factura']."</td>
					<td align='center' style='$esp'>".$estado."</td>
					<td style='$esp'>".utf8_decode($field_ubicacion_actual['dependencia'])."</td>
					<td align='right' style='$esp'>=DECIMAL(".$total."; 2)</td>
				</tr>";
			}
			echo "<tr><td align='right' colspan='6' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
		}
		//----------------------------------------------------
		elseif ($head==5) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				
				$total=number_format($field['total'], 2, ',', ''); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				
				//
				$sql = "SELECT
							d.denominacion AS dependencia
						FROM 
							dependencias d
							INNER JOIN relacion_documentos_remision rdr ON (rdr.iddependencia_origen = d.iddependencia)
						WHERE
							rdr.tabla = 'orden_compra_servicio' AND 
							rdr.id_documento = '".$field['idorden_compra_servicio']."' AND
							rdr.idrelacion_documentos_remision = (SELECT MAX(idrelacion_documentos_remision) FROM relacion_documentos_remision WHERE tabla = 'orden_compra_servicio' AND id_documento = '".$field['idorden_compra_servicio']."')";
				$query_ubicacion_actual = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_ubicacion_actual) != 0) $field_ubicacion_actual = mysql_fetch_array($query_ubicacion_actual);
				//
				
				if ($grupo != $field['idcategoria_programatica']) {
					if ($i != 0) {
						echo "<tr><td align='right' colspan='6' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
						$sum_sub_total = 0;
					}
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['codigo'].' - '.$field['denominacion']);
					
					echo "<tr><td colspan='6'>&nbsp;</td></tr>";
					echo "<tr><td colspan='6' style='$cat'>".$categoria."</td></tr>";
					echo "<tr><td colspan='6' style='$cat'>".utf8_decode($field['descripcion'])."</td></tr>";
					echo "<tr><td colspan='6' style='$cat'>".$estado."</td></tr>";
					if ($desde!="" && $hasta!="") {
						list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fecha_desde=$d."/".$m."/".$a;
						list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fecha_hasta=$d."/".$m."/".$a;
						echo "<tr><td colspan='6' style='$cat'>".$fecha_desde." a ".$fecha_hasta."</td></tr>";
					}
					echo "
					<tr>
						<td align='center' style='$titulo'>Nro. O/C</td>
						<td align='center' style='$titulo'>Fecha O/C</td>
						<td align='center' style='$titulo'>Nro. Factura</td>
						<td align='center' style='$titulo'>Proveedor</td>
						<td align='center' style='$titulo'>Ubicacion Actual</td>
						<td align='center' style='$titulo'>Total</td>
					</tr>";
				}
				
				echo "
				<tr>
					<td align='center' style='$esp'>".$field['numero_orden']."</td>
					<td align='center' style='$esp'>".$fecha."</td>
					<td align='center' style='$esp'>".$field['nro_factura']."</td>
					<td style='$esp'>".utf8_decode($field['nombre'])."</td>
					<td style='$esp'>".utf8_decode($field_ubicacion_actual['dependencia'])."</td>
					<td align='right' style='$esp'>=DECIMAL(".$total."; 2)</td>
				</tr>";
			}
			echo "<tr><td align='right' colspan='6' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
		}
		//----------------------------------------------------
		elseif ($head==6) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				
				$total=number_format($field['total'], 2, ',', ''); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				
				//
				$sql = "SELECT
							d.denominacion AS dependencia
						FROM 
							dependencias d
							INNER JOIN relacion_documentos_remision rdr ON (rdr.iddependencia_origen = d.iddependencia)
						WHERE
							rdr.tabla = 'orden_compra_servicio' AND 
							rdr.id_documento = '".$field['idorden_compra_servicio']."' AND
							rdr.idrelacion_documentos_remision = (SELECT MAX(idrelacion_documentos_remision) FROM relacion_documentos_remision WHERE tabla = 'orden_compra_servicio' AND id_documento = '".$field['idorden_compra_servicio']."')";
				$query_ubicacion_actual = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_ubicacion_actual) != 0) $field_ubicacion_actual = mysql_fetch_array($query_ubicacion_actual);
				//
				
				if ($grupo != $field['idcategoria_programatica']) {
					if ($i != 0) {
						echo "<tr><td align='right' colspan='6' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
						$sum_sub_total = 0;
					}
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['codigo'].' - '.$field['denominacion']);
					
					echo "<tr><td colspan='6'>&nbsp;</td></tr>";
					echo "<tr><td colspan='6' style='$cat'>".$categoria."</td></tr>";
					echo "<tr><td colspan='6' style='$cat'>".utf8_decode($field['nombre'])."</td></tr>";
					echo "<tr><td colspan='6' style='$cat'>".$estado."</td></tr>";
					if ($desde!="" && $hasta!="") {
						list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fecha_desde=$d."/".$m."/".$a;
						list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fecha_hasta=$d."/".$m."/".$a;
						echo "<tr><td colspan='6' style='$cat'>".$fecha_desde." a ".$fecha_hasta."</td></tr>";
					}
					echo "
					<tr>
						<td align='center' style='$titulo'>Nro. O/C</td>
						<td align='center' style='$titulo'>Fecha O/C</td>
						<td align='center' style='$titulo'>Nro. Factura</td>
						<td align='center' style='$titulo'>Tipo</td>
						<td align='center' style='$titulo'>Ubicacion Actual</td>
						<td align='center' style='$titulo'>Total</td>
					</tr>";
				}
				
				echo "
				<tr>
					<td align='center' style='$esp'>".$field['numero_orden']."</td>
					<td align='center' style='$esp'>".$fecha."</td>
					<td align='center' style='$esp'>".$field['nro_factura']."</td>
					<td style='$esp'>".utf8_decode($field['descripcion'])."</td>
					<td style='$esp'>".utf8_decode($field_ubicacion_actual['dependencia'])."</td>
					<td align='right' style='$esp'>=DECIMAL(".$total."; 2)</td>
				</tr>";
			}
			echo "<tr><td align='right' colspan='6' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
		}
		//----------------------------------------------------
		elseif ($head==7) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				
				$total=number_format($field['total'], 2, ',', ''); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				
				//
				$sql = "SELECT
							d.denominacion AS dependencia
						FROM 
							dependencias d
							INNER JOIN relacion_documentos_remision rdr ON (rdr.iddependencia_origen = d.iddependencia)
						WHERE
							rdr.tabla = 'orden_compra_servicio' AND 
							rdr.id_documento = '".$field['idorden_compra_servicio']."' AND
							rdr.idrelacion_documentos_remision = (SELECT MAX(idrelacion_documentos_remision) FROM relacion_documentos_remision WHERE tabla = 'orden_compra_servicio' AND id_documento = '".$field['idorden_compra_servicio']."')";
				$query_ubicacion_actual = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_ubicacion_actual) != 0) $field_ubicacion_actual = mysql_fetch_array($query_ubicacion_actual);
				//
				
				if ($grupo != $field['idcategoria_programatica']) {
					if ($i != 0) {
						echo "<tr><td align='right' colspan='6' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
						$sum_sub_total = 0;
					}
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['codigo'].' - '.$field['denominacion']);
					
					echo "<tr><td colspan='6'>&nbsp;</td></tr>";
					echo "<tr><td colspan='6' style='$cat'>".$categoria."</td></tr>";
					echo "<tr><td colspan='6' style='$cat'>".utf8_decode($field['descripcion'])."</td></tr>";
					echo "<tr><td colspan='6' style='$cat'>".$estado."</td></tr>";
					if ($desde!="" && $hasta!="") {
						list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fecha_desde=$d."/".$m."/".$a;
						list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fecha_hasta=$d."/".$m."/".$a;
						echo "<tr><td colspan='6' style='$cat'>".$fecha_desde." a ".$fecha_hasta."</td></tr>";
					}
					echo "
					<tr>
						<td align='center' style='$titulo'>Nro. O/C</td>
						<td align='center' style='$titulo'>Fecha O/C</td>
						<td align='center' style='$titulo'>Nro. Factura</td>
						<td align='center' style='$titulo'>Proveedor</td>
						<td align='center' style='$titulo'>Ubicacion Actual</td>
						<td align='center' style='$titulo'>Total</td>
					</tr>";
				}
				
				echo "
				<tr>
					<td align='center' style='$esp'>".$field['numero_orden']."</td>
					<td align='center' style='$esp'>".$fecha."</td>
					<td align='center' style='$esp'>".$field['nro_factura']."</td>
					<td style='$esp'>".utf8_decode($field['nombre'])."</td>
					<td style='$esp'>".utf8_decode($field_ubicacion_actual['dependencia'])."</td>
					<td align='right' style='$esp'>=DECIMAL(".$total."; 2)</td>
				</tr>";
			}
			echo "<tr><td align='right' colspan='6' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
		}
		//----------------------------------------------------
		elseif ($head==8) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				
				$total=number_format($field['total'], 2, ',', ''); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				
				//
				$sql = "SELECT
							d.denominacion AS dependencia
						FROM 
							dependencias d
							INNER JOIN relacion_documentos_remision rdr ON (rdr.iddependencia_origen = d.iddependencia)
						WHERE
							rdr.tabla = 'orden_compra_servicio' AND 
							rdr.id_documento = '".$field['idorden_compra_servicio']."' AND
							rdr.idrelacion_documentos_remision = (SELECT MAX(idrelacion_documentos_remision) FROM relacion_documentos_remision WHERE tabla = 'orden_compra_servicio' AND id_documento = '".$field['idorden_compra_servicio']."')";
				$query_ubicacion_actual = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_ubicacion_actual) != 0) $field_ubicacion_actual = mysql_fetch_array($query_ubicacion_actual);
				//
				
				if ($grupo != $field['idcategoria_programatica']) {
					if ($i != 0) {
						echo "<tr><td align='right' colspan='5' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
						$sum_sub_total = 0;
					}
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['codigo'].' - '.$field['denominacion']);
					
					echo "<tr><td colspan='5'>&nbsp;</td></tr>";
					echo "<tr><td colspan='5' style='$cat'>".$categoria."</td></tr>";
					echo "<tr><td colspan='5' style='$cat'>".utf8_decode($field['nombre'])."</td></tr>";
					echo "<tr><td colspan='5' style='$cat'>".utf8_decode($field['descripcion'])."</td></tr>";
					echo "<tr><td colspan='5' style='$cat'>".$estado."</td></tr>";
					if ($desde!="" && $hasta!="") {
						list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fecha_desde=$d."/".$m."/".$a;
						list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fecha_hasta=$d."/".$m."/".$a;
						echo "<tr><td colspan='5' style='$cat'>".$fecha_desde." a ".$fecha_hasta."</td></tr>";
					}
					echo "
					<tr>
						<td align='center' style='$titulo'>Nro. O/C</td>
						<td align='center' style='$titulo'>Fecha O/C</td>
						<td align='center' style='$titulo'>Nro. Factura</td>
						<td align='center' style='$titulo'>Ubicacion Actual</td>
						<td align='center' style='$titulo'>Total</td>
					</tr>";
				}
				
				echo "
				<tr>
					<td align='center' style='$esp'>".$field['numero_orden']."</td>
					<td align='center' style='$esp'>".$fecha."</td>
					<td align='center' style='$esp'>".$field['nro_factura']."</td>
					<td style='$esp'>".utf8_decode($field_ubicacion_actual['dependencia'])."</td>
					<td align='right' style='$esp'>=DECIMAL(".$total."; 2)</td>
				</tr>";
			}
			echo "<tr><td align='right' colspan='5' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
		}
		//----------------------------------------------------
		elseif ($head==9) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				
				$total=number_format($field['total'], 2, ',', ''); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				
				//
				$sql = "SELECT
							d.denominacion AS dependencia
						FROM 
							dependencias d
							INNER JOIN relacion_documentos_remision rdr ON (rdr.iddependencia_origen = d.iddependencia)
						WHERE
							rdr.tabla = 'orden_compra_servicio' AND 
							rdr.id_documento = '".$field['idorden_compra_servicio']."' AND
							rdr.idrelacion_documentos_remision = (SELECT MAX(idrelacion_documentos_remision) FROM relacion_documentos_remision WHERE tabla = 'orden_compra_servicio' AND id_documento = '".$field['idorden_compra_servicio']."')";
				$query_ubicacion_actual = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_ubicacion_actual) != 0) $field_ubicacion_actual = mysql_fetch_array($query_ubicacion_actual);
				//
				
				if ($grupo != $field['idcategoria_programatica']) {
					if ($i != 0) {
						echo "<tr><td align='right' colspan='8' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
						$sum_sub_total = 0;
					}
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['codigo'].' - '.$field['denominacion']);
					
					echo "<tr><td colspan='8'>&nbsp;</td></tr>";
					echo "<tr><td colspan='8' style='$cat'>".$categoria."</td></tr>";
					if ($desde!="" && $hasta!="") {
						list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fecha_desde=$d."/".$m."/".$a;
						list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fecha_hasta=$d."/".$m."/".$a;
						echo "<tr><td colspan='8' style='$cat'>".$fecha_desde." a ".$fecha_hasta."</td></tr>";
					}
					echo "
					<tr>
						<td align='center' style='$titulo'>Nro. O/C</td>
						<td align='center' style='$titulo'>Fecha O/C</td>
						<td align='center' style='$titulo'>Nro. Factura</td>
						<td align='center' style='$titulo'>Estado</td>
						<td align='center' style='$titulo'>Tipo</td>
						<td align='center' style='$titulo'>Proveedor</td>
						<td align='center' style='$titulo'>Ubicacion Actual</td>
						<td align='center' style='$titulo'>Precio U.</td>
					</tr>";
				}
				
				echo "
				<tr>
					<td align='center' style='$esp'>".$field['numero_orden']."</td>
					<td align='center' style='$esp'>".$fecha."</td>
					<td align='center' style='$esp'>".$field['nro_factura']."</td>
					<td align='center' style='$esp'>".$estado."</td>
					<td style='$esp'>".utf8_decode($field['descripcion'])."</td>
					<td style='$esp'>".utf8_decode($field['nombre'])."</td>
					<td style='$esp'>".utf8_decode($field_ubicacion_actual['dependencia'])."</td>
					<td align='right' style='$esp'>=DECIMAL(".$total."; 2)</td>
				</tr>";
			}
			echo "<tr><td align='right' colspan='8' style='$total'>=DECIMAL(".number_format($sum_sub_total, 2, ',', '')."; 2)</td></tr>";
		}
		
		echo "</table>";
		break;
	/*	
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
		
		
		if($trimestre == 1)$trimestre="I";
		else if($trimestre == 2)$trimestre='II';
		else if($trimestre == 3)$trimestre = 'III';
		else if($trimestre == 4)$trimestre = 'IV';
		
		$codigo = $numero_orden[0]."-".$numero_orden[2];
		$periodo = $numero_orden[1];
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
		echo $csv;
		break;
		
	case "detalleproc":
		// DETALLEPRODUC  *****************************************************************************************
		$csv_end = "$nl";  
		$csv_sep = "|";  
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
		
		
		if($trimestre == 1)$trimestre="I";
		else if($trimestre == 2)$trimestre='II';
		else if($trimestre == 3)$trimestre = 'III';
		else if($trimestre == 4)$trimestre = 'IV';
		
		$numero_orden = explode("-",$bus_ordenes['numero_orden']);
		
		$periodo = $numero_orden[1];
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
		echo $csv; 
		break;
		
	case "contratistas":
		$csv_end = "$nl";  
		$csv_sep = "|";  
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
		
		if($trimestre == 1)$trimestre="I";
		else if($trimestre == 2)$trimestre='II';
		else if($trimestre == 3)$trimestre = 'III';
		else if($trimestre == 4)$trimestre = 'IV';
		
		
		$periodo = $numero_orden[0]."-".$numero_orden[2];
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
		echo $csv;
		break;
		*/
}
?>