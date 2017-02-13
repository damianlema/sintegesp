<?php
session_start();
set_time_limit(-1);
ini_set("memory_limit", "200M");
extract($_GET);
extract($_POST);
//	----------------------------------------
$MAXLINEA=30;
$MAXLINEALEG=15;
//	----------------------------------------
require('../../../conf/conex.php');
require('../../mc_table.php');
require('../../mc_table2.php');
require('../../mc_table3.php');
require('../../mc_table4.php');
require('../../mc_table5.php');
require('../../mc_table6.php');
require('../../mc_table7.php');
require('../../mc_table8.php');
require('../firmas.php');
require('head.php');
//	----------------------------------------
Conectarse();
$ahora=date("d-m-Y H:i:s");
//	----------------------------------------
function diferenciaEntreDosFechas($fechaInicio, $fechaActual){
	list($anioInicio, $mesInicio, $diaInicio) = explode("-", $fechaInicio);
	list($anioActual, $mesActual, $diaActual) = explode("-", $fechaActual);

	$b = 0;
	$mes = $mesInicio-1;
	if($mes==2){
		if(($anioActual%4==0 && $anioActual%100!=0) || $anioActual%400==0){
			$b = 29;
		}else{
			$b = 28;
		}
	}
	else if($mes<=7){
			if($mes==0){
				$b = 31;
			}
			else if($mes%2==0){
				$b = 30;
			}
		else{
			$b = 31;
		}
	}
	else if($mes>7){
		if($mes%2==0){
			$b = 31;
		}
		else{
			$b = 30;
		}
	}
	if(($anioInicio>$anioActual) || ($anioInicio==$anioActual && $mesInicio>$mesActual) ||
		($anioInicio==$anioActual && $mesInicio == $mesActual && $diaInicio>$diaActual)){
			//echo "A�o incio ".$anioInicio." A�o Actual ".$anioActual." Mes Inicio ".$mesInicio." Mes actual ".$mesActual." Dia Inicio ".$diaInicio." Dia Actual ".$diaActual."La fecha de inicio ha de ser anterior a la fecha Actual";
	}else{
		//echo "A�o incio ".$anioInicio." A�o Actual ".$anioActual." Mes Inicio ".$mesInicio." Mes actual ".$mesActual." Dia Inicio ".$diaInicio." Dia Actual ".$diaActual."La fecha de inicio ha de ser anterior a la fecha Actual <br>";
		if($mesInicio <= $mesActual){
			$anios = $anioActual - $anioInicio;
			if($diaInicio <= $diaActual){
				$meses = $mesActual - $mesInicio;
				$dies = $diaActual - $diaInicio;
			}else{
				if($mesActual == $mesInicio){
					$anios = $anios ;
				}
				$meses = ($mesActual - $mesInicio + 12) % 12;
				$dies = $b-($diaInicio-$diaActual);
			}
		}else{
			$anios = $anioActual - $anioInicio -1 ;
			if($diaInicio > $diaActual){
				$meses = $mesActual - $mesInicio  +12;
				$dies = $b - ($diaInicio-$diaActual);
			}else{
				$meses = $mesActual - $mesInicio + 12;
				$dies = $diaActual - $diaInicio;
			}
		}
	return $anios."|.|".$meses."|.|".$dies;
	}
}
//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Grupos...
	case "grupos":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		grupos($pdf);
		////////////
		$sql="SELECT idgrupo, denominacion FROM grupos ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array($field[0], utf8_decode($field[1])));
			$linea=$pdf->GetY(); if ($linea>250) { grupos($pdf); $pdf->SetY(45); }
		}
		break;
		
	//	Series...
	case "series":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		series($pdf);
		////////////
		$sql = "SELECT
					grupos.denominacion,
					series.idserie,
					series.denominacion,
					series.idgrupo
				FROM
					series
					LEFT JOIN grupos ON (series.idgrupo = grupos.idgrupo)
				ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$grupo=$field[3].' - '.$field[0];
			$pdf->Row(array(utf8_decode($grupo), $field[1], utf8_decode($field[2])));
			$linea=$pdf->GetY(); if ($linea>250) { series($pdf); $pdf->SetY(45); }
		}
		break;
		
	//	Cargos...
	case "cargos":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		cargos($pdf);
		////////////
		$sql="SELECT series.denominacion, cargos.idcargo, cargos.denominacion, cargos.grado, cargos.idserie FROM cargos, series WHERE (cargos.idserie=series.idserie) ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$serie=$field[4].' - '.$field[0];
			$pdf->Row(array(utf8_decode($serie), $field[1], utf8_decode($field[2]), utf8_decode($field[3])));
			$linea=$pdf->GetY(); if ($linea>250) { cargos($pdf); $pdf->SetY(45); }
		}
		break;
		
	//	Nivel...
	case "nivel":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		nivel($pdf);
		////////////
		$sql="SELECT denominacion FROM nivel_estudio ORDER BY denominacion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0])));
			$linea=$pdf->GetY(); if ($linea>250) { nivel($pdf); $pdf->SetY(45); }
		}
		break;
		
	//	Profesion...
	case "profesion":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		profesion($pdf);
		////////////
		$sql="SELECT denominacion, abreviatura FROM profesion ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0]), $field[1]));
			$linea=$pdf->GetY(); if ($linea>250) { profesion($pdf); $pdf->SetY(45); }
		}
		break;
		
	//	Mensiones...
	case "mensiones":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		mensiones($pdf);
		////////////
		$sql="SELECT denominacion FROM mension ORDER BY denominacion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0])));
			$linea=$pdf->GetY(); if ($linea>250) { mensiones($pdf); $pdf->SetY(45); }
		}
		break;
		
	//	Estado Civil...
	case "edocivil":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		edocivil($pdf);
		////////////
		$sql="SELECT denominacion FROM edo_civil ORDER BY denominacion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0])));		
			$linea=$pdf->GetY(); if ($linea>250) { edocivil($pdf); $pdf->SetY(45); }
		}
		break;
		
	//	Parentesco...
	case "parentesco":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		parentesco($pdf);
		////////////
		$sql="SELECT denominacion FROM parentezco ORDER BY denominacion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0])));
			$linea=$pdf->GetY(); if ($linea>250) { parentesco($pdf); $pdf->SetY(45); }
		}
		break;
		
	//	Grupo Sanguineo...
	case "gsang":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		gsang($pdf);
		////////////
		$sql="SELECT denominacion FROM grupo_sanguineo ORDER BY denominacion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0])));
			$linea=$pdf->GetY(); if ($linea>250) { gsang($pdf); $pdf->SetY(45); }
		}
		break;
		
	//	Tipos de Movimiento...
	case "tmov":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		tmov($pdf);
		////////////
		$sql="SELECT denominacion FROM tipo_movimiento_personal ORDER BY denominacion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0])));
			$linea=$pdf->GetY(); if ($linea>250) { tmov($pdf); $pdf->SetY(45); }
		}
		break;
		
	//	Lista de Trabajadores...
	case "listatrab":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$whe_estado='';
		if($estado=='activos') $whe_estado = " where t.status='a'";
		if($estado=='egresados') $whe_estado = " where t.status='e'";
		//---------------------------------------------
		$campos = explode("|", $checks);
		//---------------------------------------------
		listatrab($pdf, $campos, $estado);
		////////////
		$sql = "SELECT
					t.cedula,
					t.nombres,
					t.apellidos,
					no.denominacion AS nomunidad_funcional,
					ue.denominacion AS nomcentro_costo
				FROM
					trabajador t
					INNER JOIN niveles_organizacionales no ON (t.idunidad_funcional = no.idniveles_organizacionales)
					INNER JOIN niveles_organizacionales cno ON (t.centro_costo = cno.idniveles_organizacionales)
					INNER JOIN categoria_programatica cp ON (cno.idcategoria_programatica = cp.idcategoria_programatica)
					INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
				".$whe_estado."
				 ORDER BY ".$ordenar;
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$i++;
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			if ($campos[2] && $campos[3]) {
				$pdf->Row(array(number_format($field['cedula'], 0, '', '.'), 
								utf8_decode($field['nombres']).', '.utf8_decode($field['apellidos']), 
								utf8_decode($field['nomcentro_costo']), 
								utf8_decode($field['nomunidad_funcional'])));
			}elseif (!$campos[2] && $campos[3]) {
				$pdf->Row(array(number_format($field['cedula'], 0, '', '.'), 
								utf8_decode($field['nombres']).', '.utf8_decode($field['apellidos']),
								utf8_decode($field['nomunidad_funcional'])));
			}elseif ($campos[2] && !$campos[3]) {
				$pdf->Row(array(number_format($field['cedula'], 0, '', '.'), 
								utf8_decode($field['nombres']).', '.utf8_decode($field['apellidos']), 
								utf8_decode($field['nomcentro_costo'])));
			}elseif (!$campos[2] && !$campos[3]) {
				$pdf->Row(array(number_format($field['cedula'], 0, '', '.'), 
								utf8_decode($field['nombres']).', '.utf8_decode($field['apellidos'])));
			}
			$linea = $pdf->GetY(); if ($linea>250) { listatrab($pdf, $campos, $estado); }
		}
		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(195, 5, 'Total de Trabajadores: '.$i, 0, 0, 'L');
		break;
		
	//	Tipos de Persona...
	case "tipopersona":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		tipopersona($pdf);
		////////////
		$sql="SELECT descripcion FROM tipos_persona ORDER BY descripcion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0])));
			$linea=$pdf->GetY(); if ($linea>250) { tipopersona($pdf); $pdf->SetY(45); }
		}
		break;
			
	//	Tipos de Sociedad...
	case "tiposociedad":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		tiposociedad($pdf);
		////////////
		$sql="SELECT descripcion, siglas FROM tipo_sociedad ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0]), utf8_decode($field[1])));
			$linea=$pdf->GetY(); if ($linea>250) { tiposociedad($pdf); $pdf->SetY(45); }
		}
		break;
			
	//	Tipos de Empresa...
	case "tipoempresa":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		tipoempresa($pdf);
		////////////
		$sql="SELECT descripcion FROM tipo_empresa ORDER BY descripcion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0])));
			$linea=$pdf->GetY(); if ($linea>250) { tipoempresa($pdf); $pdf->SetY(45); }
		}
		break;
		
	//	Tipos de Beneficiario...
	case "tipobene":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		tipobene($pdf);
		////////////
		$sql="SELECT descripcion FROM tipo_beneficiario ORDER BY descripcion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0])));
			$linea=$pdf->GetY(); if ($linea>250) { tipobene($pdf); $pdf->SetY(45); }
		}
		break;
		
	//	Estado de Beneficiario...
	case "edobene":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		edobene($pdf);
		////////////
		$sql="SELECT descripcion FROM estado_beneficiario ORDER BY descripcion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0])));
			$linea=$pdf->GetY(); if ($linea>250) { edobene($pdf); $pdf->SetY(45); }
		}
		break;
			
	//	Documentos Requeridos...
	case "docreq":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		docreq($pdf);
		////////////
		$sql="SELECT descripcion FROM documentos_requeridos ORDER BY descripcion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0])));
			$linea=$pdf->GetY(); if ($linea>250) { docreq($pdf); $pdf->SetY(45); }
		}
		break;
			
	//	(Modulo) Certificacion de Compromisos...
	case "certificacion_compromiso_rrhh":
		$pag=0;
		//	Obtengo los datos de la certificacion 
		$sql="SELECT o.numero_orden, 
					 o.fecha_orden, 
					 o.tipo, 
					 o.justificacion, 
					 o.numero_requisicion,
					 o.fecha_requisicion, 
					 o.exento, 
					 o.sub_total, 
					 o.total, 
					 b.nombre AS Beneficiario, 
					 t.descripcion AS TipoDocumento,
					 t.modulo,
					 t.forma_preimpresa,
					 t.fondos_terceros,
					 t.idtipos_documentos
			  FROM 
					orden_compra_servicio o 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
					INNER JOIN tipos_documentos t ON (o.tipo=t.idtipos_documentos) 
			  WHERE 
					o.idorden_compra_servicio='".$id_orden_compra."'";
		$query_datos=mysql_query($sql) or die ($sql.mysql_error());
		$rows_datos=mysql_num_rows($query_datos);
		if ($rows_datos!=0) $field_datos=mysql_fetch_array($query_datos);

		$sql_suma_aportes = mysql_query("select SUM(articulos_compra_servicio.total) as total_aportes
											from 
											articulos_servicios, 
											articulos_compra_servicio 
											where 
											articulos_compra_servicio.idorden_compra_servicio = '".$id_orden_compra."'
											and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios
											and articulos_servicios.tipo_concepto = 4")or die(mysql_error());
		$bus_suma_aportes = mysql_fetch_array($sql_suma_aportes);
		$sub_total_aportes = $bus_suma_aportes["total_aportes"];
		list($a, $m, $d)=SPLIT( '[/.-]', $field_datos['fecha_requisicion']); $fecha_requisicion="$d-$m-$a";
		list($a, $m, $d)=SPLIT( '[/.-]', $field_datos['fecha_orden']); $fecha_orden="$d-$m-$a";
		$numero_orden=$field_datos['numero_orden'];
		$tipo_documento=$field_datos['TipoDocumento'];
		$asignaciones=number_format($field_datos['sub_total'], 2, ',', '.');
		$deducciones=number_format($field_datos['exento'], 2, ',', '.');
		$neto=number_format($field_datos['total'], 2, ',', '.');
		//	------------------------------------------------------------
		//	IMPRIMO LA CERTIFICACION DE COMPROMISO MODULO=1 Y FORMA  PREIMPRESA=NO
		//	------------------------------------------------------------
		$field_datos['modulo'] = explode("-", $field_datos['modulo']);
		if ((in_array(1,$field_datos['modulo']) || in_array(13,$field_datos['modulo'])) && $field_datos['forma_preimpresa']=="no") {
			$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
			$pdf->Open();
			certificacion_compromiso_rrhh($pdf, $tipo_documento, $numero_orden, $fecha_orden, ++$pag, $field_datos['idtipos_documentos'], $modulo);
			//	---------------------------------
			$pdf->SetY(40);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(140, 5, 'BENEFICIARIO', 1, 0, 'L', 1); $pdf->Cell(35, 5, 'MEMORANDUM', 1, 0, 'C', 1); $pdf->Cell(25, 5, 'FECHA', 1, 1, 'C', 1);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(140, 5, substr(utf8_decode($field_datos['Beneficiario']), 0, 90), 1, 0, 'L', 1); $pdf->Cell(35, 5, $field_datos['numero_requisicion'], 1, 0, 'L', 1); $pdf->Cell(25, 5, $fecha_requisicion, 1, 1, 'C', 1);
			//	---------------------------------
			$pdf->Ln(1);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); 
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(200, 5, 'CONCEPTO DEL GASTO', 1, 1, 'C', 1);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);  
			$pdf->SetFont('Arial', '', 8);
			$pdf->MultiCell(200, 5, utf8_decode($field_datos['justificacion']), 0, 'L', 1); 
			$pdf->Rect(8, 51, 200, 35);
			//	---------------------------------
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			if ($field_datos['fondos_terceros'] == "si") {
				$pdf->SetFont('Arial', 'B', 8); 
				$pdf->SetXY(135, 100); $pdf->Cell(35, 5, 'NETO A PAGAR:', 0, 1, 'R', 1);
				$pdf->SetFont('Arial', 'B', 10); 
				$pdf->SetXY(170, 100); $pdf->Cell(35, 5, $neto, 0, 1, 'R', 1);
			} else {
				$pdf->SetFont('Arial', 'B', 8);
				if($sub_total_aportes > 0){
					$pdf->SetXY(135, 90); $pdf->Cell(35, 5, 'APORTE PATRONAL:', 0, 1, 'R', 1); 
				}else{
					$pdf->SetXY(135, 90); $pdf->Cell(35, 5, 'ASIGNACIONES:', 0, 1, 'R', 1); 
				}
				$pdf->SetXY(135, 95); $pdf->Cell(35, 5, 'DEDUCCIONES:', 0, 1, 'R', 1); 
				$pdf->SetXY(135, 100); $pdf->Cell(35, 5, 'NETO A PAGAR:', 0, 1, 'R', 1);
				$pdf->SetFont('Arial', 'B', 10); 
				$pdf->SetXY(170, 90); $pdf->Cell(35, 5, $asignaciones, 0, 1, 'R', 1); 
				$pdf->SetXY(170, 95); $pdf->Cell(35, 5, $deducciones, 0, 1, 'R', 1); 
				$pdf->SetXY(170, 100); $pdf->Cell(35, 5, $neto, 0, 1, 'R', 1);
			}
			//	---------------------------------
			$pdf->SetY(110);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); 
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Cell(200, 5, 'PARA EL USO DEL AREA ADMINISTRATIVA', 0, 1, 'C', 1);
			//	---------------------------------
			//	IMPRIMIR LAS PARTIDAS
			//	---------------------------------
			$pdf->Ln(1);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(25, 5, 'CAT. PROG.', 1, 0, 'C', 1);
			$pdf->Cell(25, 5, 'PARTIDA', 1, 0, 'C', 1);
			$pdf->Cell(110, 5, 'DESCRIPCION', 1, 0, 'C', 1);
			$pdf->Cell(40, 5, 'MONTO', 1, 0, 'C', 1);
			$pdf->Ln(7);
			//	
			$sql="SELECT partidas_orden_compra_servicio.idmaestro_presupuesto, 
						 partidas_orden_compra_servicio.monto, 
						 clasificador_presupuestario.denominacion, 
						 clasificador_presupuestario.partida, 
						 clasificador_presupuestario.generica, 
						 clasificador_presupuestario.especifica, 
						 clasificador_presupuestario.sub_especifica, 
						 maestro_presupuesto.idclasificador_presupuestario, 
						 categoria_programatica.codigo 
					FROM 
						partidas_orden_compra_servicio, 
						clasificador_presupuestario, 
						maestro_presupuesto, 
						categoria_programatica 
					WHERE 
						(partidas_orden_compra_servicio.idorden_compra_servicio='".$id_orden_compra."') AND 
						(partidas_orden_compra_servicio.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND 
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
						maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica
					ORDER BY 
						 categoria_programatica.codigo,
						 clasificador_presupuestario.partida, 
						 clasificador_presupuestario.generica, 
						 clasificador_presupuestario.especifica, 
						 clasificador_presupuestario.sub_especifica";
			$query_partidas=mysql_query($sql) or die ($sql.mysql_error());
			$rows_partidas=mysql_num_rows($query_partidas);
			if ($rows_partidas<22) {
				for ($i=1; $i<=$rows_partidas; $i++) {
					$field_partidas=mysql_fetch_array($query_partidas);
					$partida=$field_partidas['partida']." ".$field_partidas['generica']." ".$field_partidas['especifica']." ".$field_partidas['sub_especifica'];
					$monto=number_format($field_partidas['monto'], 2, ',', '.');
					$sum_monto+=$field_partidas['monto'];
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); 
					$pdf->SetFont('Arial', '', 8);
					$pdf->SetAligns(array('C', 'C', 'L', 'R'));
					$pdf->SetWidths(array(25, 25, 110, 40));
					$pdf->Row(array($field_partidas['codigo'], $partida, utf8_decode($field_partidas['denominacion']), $monto));
					if ($i==$rows_partidas) {
							$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0); 
							$pdf->Rect(10, $pdf->GetY()+2, 198, 0.1);
							$pdf->Ln(5);
							$van_monto=number_format($sum_monto, 2, ',', '.');
							$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); 
							$pdf->SetFont('Arial', '', 8);
							$pdf->SetAligns(array('C', 'C', 'R', 'R'));
							$pdf->SetWidths(array(25, 25, 110, 40));
							$pdf->Row(array('', '', 'TOTAL .............>', $van_monto));
							$y=$pdf->GetY(); 
					}
				}
			} else {
				anexo_certificacion_compromiso_rrhh($pdf, $tipo_documento, $numero_orden, $fecha_orden, ++$pag, $field_datos['idtipos_documentos'], $modulo );
				for ($i=1; $i<=$rows_partidas; $i++) {
					$field_partidas=mysql_fetch_array($query_partidas);
					$partida=$field_partidas['partida']." ".$field_partidas['generica']." ".$field_partidas['especifica']." ".$field_partidas['sub_especifica'];
					$monto=number_format($field_partidas['monto'], 2, ',', '.');
					$sum_monto+=$field_partidas['monto'];
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); 
					$pdf->SetFont('Arial', '', 8);
					$pdf->SetAligns(array('C', 'C', 'L', 'R'));
					$pdf->SetWidths(array(25, 25, 110, 40));
					$pdf->Row(array($field_partidas['codigo'], $partida, utf8_decode($field_partidas['denominacion']), $monto));
					$y=$pdf->GetY(); 
					if ($y>250 || $i==$rows_partidas) { 
						if ($i==$rows_partidas) {
							$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0); 
							$pdf->Rect(10, $pdf->GetY()+2, 198, 0.1);
							$pdf->Ln(5);
							$van_monto=number_format($sum_monto, 2, ',', '.');
							$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); 
							$pdf->SetFont('Arial', '', 8);
							$pdf->SetAligns(array('C', 'C', 'R', 'R'));
							$pdf->SetWidths(array(25, 25, 110, 40));
							$pdf->Row(array('', '', 'TOTAL .............>', $van_monto));
						} else {
							$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0); 
							$pdf->Rect(10, $pdf->GetY()+2, 198, 0.1);
							$pdf->Ln(5);
							$van_monto=number_format($sum_monto, 2, ',', '.');
							$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); 
							$pdf->SetFont('Arial', '', 8);
							$pdf->SetAligns(array('C', 'C', 'R', 'R'));
							$pdf->SetWidths(array(25, 25, 110, 40));
							$pdf->Row(array('', '', 'VAN .............>', $van_monto));
							anexo_certificacion_compromiso_rrhh($pdf, $tipo_documento, $numero_orden, $fecha_orden, ++$pag, $field_datos['idtipos_documentos'], $modulo );
						}
					}
				}
			}
			
			
		//AQUI VAN LAS CUENTAS CONTABLES DEL COMPROMISO
		
			if ($rows_partidas<16 && $y<180) {
				
				$sql_asiento_contable = mysql_query("select * from asiento_contable where iddocumento = '".$id_orden_compra."'
																					and tipo_movimiento = 'compromiso'")or die("UNO ".mysql_error());
				if (mysql_num_rows($sql_asiento_contable)>0){
					$bus_asiento_contable = mysql_fetch_array($sql_asiento_contable);
					$sql_cuentas_asiento = mysql_query("select * from cuentas_asiento_contable where idasiento_contable ='".$bus_asiento_contable["idasiento_contable"]."'")or die("dos".mysql_error());
					$sql_tabla_cuentas = $sql_cuentas_asiento;
					$num_cuentas_asiento = mysql_num_rows($sql_cuentas_asiento);
					
					if ($num_cuentas_asiento <=2){
						$y=$pdf->GetY()+2;
						$pdf->SetFont('Arial', 'B', 9);
						$pdf->SetXY(10, $y); $pdf->Cell(50, 5, utf8_decode('AFECTACION CONTABLE'), 0, 1, 'L');
						$y=$pdf->GetY();
						$pdf->SetY($y);	
						$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('Arial', 'B', 8);
						$pdf->Cell(25, 5, 'CUENTA', 1, 0, 'C', 1);
						$pdf->Cell(120, 5, 'DENOMINACION', 1, 0, 'C', 1);
						$pdf->Cell(30, 5, 'DEBE', 1, 0, 'C', 1);
						$pdf->Cell(30, 5, 'HABER', 1, 0, 'C', 1);
						$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('Arial', 'B', 9); 
						
						
						for ($i=1; $i<=$num_cuentas_asiento; $i++) {
							$bus_cuentas_contables = mysql_fetch_array($sql_tabla_cuentas);
							$y+=5;
							$idcampo = "id".$bus_cuentas_contables["tabla"];
							$sql_cuentas = mysql_query("select * from ".$bus_cuentas_contables["tabla"]." 
																				where ".$idcampo." = '".$bus_cuentas_contables["idcuenta"]."'")or die($idcampo." tres".mysql_error());
							$bus_cuenta = mysql_fetch_array($sql_cuentas);
							
							if($bus_cuentas_contables["afecta"] == 'debe'){
								$monto_debe  =number_format($bus_cuentas_contables["monto"], 2, ',', '.');
								$monto_haber = '';
								$denominacion = $bus_cuenta["denominacion"];
							}else{
								$monto_haber =number_format($bus_cuentas_contables["monto"], 2, ',', '.');
								$monto_debe = '';
								$denominacion = '         '.$bus_cuenta["denominacion"];
							}
							
							//$descripcion=SUBSTR($field[2], 0, 65); 
							$h=5; $l=4; $x1=15.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $bus_cuenta["codigo"], 0, 'L');
							$h=5; $l=4; $x1=40.00125; $w1=120; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, substr(utf8_decode($denominacion),0,50), 0, 'L');
							$h=5; $l=4; $x1=165.00125; $w1=30; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto_debe, 0, 'C');
							$h=5; $l=4; $x1=185.00125; $w1=30; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto_haber, 0, 'R');
						}
						
						
					}
				}
			}else{
				anexo_certificacion_compromiso_rrhh_contable($pdf, $tipo_documento, $numero_orden, $fecha_orden, ++$pag, $field_datos['idtipos_documentos'], $modulo );
				//if ($y<220) {
					
					$sql_asiento_contable = mysql_query("select * from asiento_contable where iddocumento = '".$id_orden_compra."'
																						and tipo_movimiento = 'compromiso'")or die("UNO ".mysql_error());
					if (mysql_num_rows($sql_asiento_contable)>0){
						$bus_asiento_contable = mysql_fetch_array($sql_asiento_contable);
						$sql_cuentas_asiento = mysql_query("select * from cuentas_asiento_contable where idasiento_contable ='".$bus_asiento_contable["idasiento_contable"]."'")or die("dos".mysql_error());
						$sql_tabla_cuentas = $sql_cuentas_asiento;
						$num_cuentas_asiento = mysql_num_rows($sql_cuentas_asiento);
						
						if ($num_cuentas_asiento <=2){
							$y=$pdf->GetY();
							$pdf->SetFont('Arial', 'B', 9);
							$pdf->SetXY(10, $y); $pdf->Cell(50, 5, utf8_decode('AFECTACION CONTABLE'), 0, 1, 'L');
							$y=$pdf->GetY();
							$pdf->SetY($y);	
							$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('Arial', 'B', 8);
							$pdf->Cell(25, 5, 'CUENTA', 1, 0, 'C', 1);
							$pdf->Cell(120, 5, 'DENOMINACION', 1, 0, 'C', 1);
							$pdf->Cell(30, 5, 'DEBE', 1, 0, 'C', 1);
							$pdf->Cell(30, 5, 'HABER', 1, 0, 'C', 1);
							$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('Arial', 'B', 9); 
							
							
							for ($i=1; $i<=$num_cuentas_asiento; $i++) {
								$bus_cuentas_contables = mysql_fetch_array($sql_tabla_cuentas);
								$y+=5;
								$idcampo = "id".$bus_cuentas_contables["tabla"];
								$sql_cuentas = mysql_query("select * from ".$bus_cuentas_contables["tabla"]." 
																					where ".$idcampo." = '".$bus_cuentas_contables["idcuenta"]."'")or die($idcampo." tres".mysql_error());
								$bus_cuenta = mysql_fetch_array($sql_cuentas);
								
								if($bus_cuentas_contables["afecta"] == 'debe'){
									$monto_debe  =number_format($bus_cuentas_contables["monto"], 2, ',', '.');
									$monto_haber = '';
									$denominacion = $bus_cuenta["denominacion"];
								}else{
									$monto_haber =number_format($bus_cuentas_contables["monto"], 2, ',', '.');
									$monto_debe = '';
									$denominacion = '         '.$bus_cuenta["denominacion"];
								}
								
								//$descripcion=SUBSTR($field[2], 0, 65); 
								$h=5; $l=4; $x1=15.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $bus_cuenta["codigo"], 0, 'L');
								$h=5; $l=4; $x1=40.00125; $w1=120; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, substr(utf8_decode($denominacion),0,50), 0, 'L');
								$h=5; $l=4; $x1=165.00125; $w1=30; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto_debe, 0, 'C');
								$h=5; $l=4; $x1=185.00125; $w1=30; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto_haber, 0, 'R');
							}
							
							
						}
					}
				//}
			}
			
			
			
		}
		//	------------------------------------------------------------
		//	IMPRIMO LA CERTIFICACION DE COMPROMISO MODULO=1 Y FORMA  PREIMPRESA=SI
		//	------------------------------------------------------------
		elseif (in_array(1,$field_datos['modulo']) && $field_datos['forma_preimpresa']=="si") {
			$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
			$pdf->SetTopMargin(5);
			$pdf->SetLeftMargin(5);
			$pdf->SetAutoPageBreak(1, 2.5);
			$pag=0;
			//-----------
			$sql="SELECT nombre_institucion FROM configuracion";
			$query=mysql_query($sql);
			$rows=mysql_num_rows($query);
			if ($rows!=0) $field=mysql_fetch_array($query);
			$despachar=$field['nombre_institucion'];
			//-----------
			//OBTENGO LOS DATOS DE CABECERA Y LOS ENVIO A LA FUNCION PARA QUE LAS IMPRIMA
			$sql="SELECT 
					 o.numero_orden, 
					 o.fecha_orden, 
					 o.tipo, 
					 o.justificacion, 
					 o.numero_requisicion,
					 o.fecha_requisicion, 
					 o.exento, 
					 o.sub_total, 
					 o.total, 
					 o.idbeneficiarios,
					 o.impuesto,
					 o.descuento,
					 o.exento_original,
					 o.sub_total_original,
					 b.nombre AS Beneficiario,
					 b.rif, 
					 t.descripcion AS TipoDocumento,
					 t.modulo,
					 t.forma_preimpresa
			  FROM 
					orden_compra_servicio o 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
					INNER JOIN tipos_documentos t ON (o.tipo=t.idtipos_documentos)
			  WHERE 
					o.idorden_compra_servicio='".$id_orden_compra."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				$id_categoria_programatica=$field['idcategoria_programatica'];
				$numero=$field['numero_orden'];
				$fecha=$field['fecha_orden'];
				$tipo=$field['TipoDocumento'];
				$nombre=$field['Beneficiario'];
				$rif=$field['rif'];
				$justificacion=$field['justificacion'];
				$nrequisicion=$field['numero_requisicion'];
				$frequisicion=$field['fecha_requisicion'];
				$impuesto=number_format($field['impuesto'], 2, ',', '.');
				$total_general=number_format($field['total'], 2, ',', '.');
				$descuento=$field['descuento'];
				$valor_descuento=number_format($field['descuento'], 2, ',', '.');
				if ($descuento>0) {
					$agregar_descuento=1;
					$exento=number_format($field['exento_original'], 2, ',', '.');
					$sub_total=number_format($field['sub_total_original'], 2, ',', '.');
				} else { 
					$exento=number_format($field['exento'], 2, ',', '.');
					$sub_total=number_format($field['sub_total'], 2, ',', '.');
					$agregar_descuento=0;
				}
				
				$sql="SELECT partidas_orden_compra_servicio.idorden_compra_servicio, 
							 maestro_presupuesto.anio, 
							 tipo_presupuesto.denominacion AS TipoPresupuesto, 
							 fuente_financiamiento.denominacion AS FuenteFinanciamiento 
						FROM 
							 partidas_orden_compra_servicio, 
							 maestro_presupuesto, 
							 tipo_presupuesto, 
							 fuente_financiamiento 
						WHERE 
							 (partidas_orden_compra_servicio.idorden_compra_servicio='".$_GET['id_orden_compra']."' AND
							 partidas_orden_compra_servicio.idmaestro_presupuesto=maestro_presupuesto.idRegistro AND 
							 maestro_presupuesto.idtipo_presupuesto=tipo_presupuesto.idtipo_presupuesto AND
							 maestro_presupuesto.idfuente_financiamiento=fuente_financiamiento.idfuente_financiamiento)";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if ($rows!=0) {
					$field=mysql_fetch_array($query);
					$anio=$field['anio'];
					$tpresupuesto=$field['TipoPresupuesto'];
					$ffinanciamiento=$field['FuenteFinanciamiento'];			
				}
			}
			//OBTENGO LOS DATOS DEL CUERPO Y LOS IMPRIMO
			$sql="SELECT articulos_compra_servicio.idarticulos_servicios, 
						 articulos_compra_servicio.cantidad, 
						 articulos_compra_servicio.precio_unitario, 
						 articulos_compra_servicio.total, 
						 articulos_compra_servicio.exento, 
						 articulos_servicios.idunidad_medida, 
						 articulos_servicios.descripcion, 
						 unidad_medida.abreviado 
					FROM 
						 articulos_compra_servicio, 
						 articulos_servicios, 
						 unidad_medida 
					WHERE 
						 (articulos_compra_servicio.idorden_compra_servicio='".$_GET['id_orden_compra']."') AND
						 (articulos_compra_servicio.idarticulos_servicios=articulos_servicios.idarticulos_servicios) AND 
						 (articulos_servicios.idunidad_medida=unidad_medida.idunidad_medida)";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $pag=1;
			certificacion_rrhh_viaticos($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo);
			$pdf->SetY(76); $y=$pdf->GetY();
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query); //for($f=0;$f<5;$f++){
				$total=(int) $field[3];
				$pu=number_format($field[2], 2, ',', '.');
				if ($total==0) $total=number_format($field[4], 2, ',', '.'); else $total=number_format($field[3], 2, ',', '.');
				$y+=5;
				$pdf->SetAligns(array('C', 'R', 'C', 'L', 'R', 'R'));
				$pdf->SetWidths(array(10, 15, 15, 95, 35, 35));
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 9);
				$pdf->Row(array($i, $field[1], $field[7], utf8_decode($field[6]), $pu, $total));
				if ($y>230) {
					$pag++;
					certificacion_rrhh_viaticos($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo);
					$y=76; $pdf->SetY(76);
				}
			}//}
			$y=$pdf->GetY()+5;
			//IMPRIMO LOS TOTALES Y OBTENGO EL TIPO DE IMPUESTO
			$sql="SELECT relacion_impuestos_ordenes_compras.idimpuestos, 
						 impuestos.siglas, 
						 impuestos.porcentaje, 
						 relacion_impuestos_ordenes_compras.total 
					FROM 
						 relacion_impuestos_ordenes_compras, 
						 impuestos 
					WHERE 
						 (relacion_impuestos_ordenes_compras.idorden_compra_servicio='".$_GET['id_orden_compra']."') AND 
						 (relacion_impuestos_ordenes_compras.idimpuestos=impuestos.idimpuestos)";
			$query_impuestos=mysql_query($sql) or die ($sql.mysql_error());
			$nro_impuestos=mysql_num_rows($query_impuestos);
		
			//OBTENGO LAS PARTIDAS Y LAS IMPRIMO
			$sql="SELECT partidas_orden_compra_servicio.idmaestro_presupuesto,
						 partidas_orden_compra_servicio.monto, 
						 clasificador_presupuestario.denominacion, 
						 clasificador_presupuestario.partida, 
						 clasificador_presupuestario.generica, 
						 clasificador_presupuestario.especifica, 
						 clasificador_presupuestario.sub_especifica, 
						 maestro_presupuesto.idclasificador_presupuestario,  
						 categoria_programatica.codigo 
					FROM 
						partidas_orden_compra_servicio, 
						clasificador_presupuestario, 
						maestro_presupuesto,
						categoria_programatica
					WHERE 
						(partidas_orden_compra_servicio.idorden_compra_servicio='".$_GET['id_orden_compra']."') AND
						(partidas_orden_compra_servicio.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						(maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica)";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows_partidas=mysql_num_rows($query);
			
			$ycuadro=$y+((3+$nro_impuestos+$agregar_descuento)*5);
			if ($ycuadro>=245) {
				$pag++;
				certificacion_rrhh_viaticos($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo);
				if ($rows_partidas>6) $y=240-((3+$nro_impuestos)*5); else $y=205-((3+$nro_impuestos)*5);
			}
			else if ($ycuadro<=180) $y=180;
			else $y=200-((3+$nro_impuestos)*5);
			//-----------
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(150, 150, 150); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Rect(5.00125, $y-3, 205, 0.1);
			$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y); $pdf->MultiCell($w, l, $S.'EXENTO: ', 0, 'R'); 	
			$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y); $pdf->MultiCell($w, l, $exento, 0, 'R');
			$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, 'SUB-TOTAL:', 0, 'R'); 	
			$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, $sub_total, 0, 'R');
			if ($agregar_descuento==1) {
				$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y+10); $pdf->MultiCell($w, l, 'DESCUENTO:', 0, 'R'); 	
				$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y+10); $pdf->MultiCell($w, l, $valor_descuento, 0, 'R');
				$y+=5;
			}
			for ($z=0; $z<$nro_impuestos; $z++) {
				$field_impuestos=mysql_fetch_array($query_impuestos) or die (mysql_error());
				$porcentaje=number_format($field_impuestos['porcentaje'], 2, ',', '.');
				$siglas=$field_impuestos['siglas']." (".$porcentaje." %):";
				$impuesto=number_format($field_impuestos['total'], 2, ',', '.');
				$y+=5;
				$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, $siglas, 0, 'R'); 	
				$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, $impuesto, 0, 'R');
			}
			$y=$pdf->GetY();
			$pdf->SetFont('Arial', 'B', 12);
			$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, 'TOTAL:', 0, 'R'); 	
			$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, $total_general, 0, 'R');
			
			$y=$pdf->GetY();
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->SetXY(5, $y-10); $pdf->Cell(50, 5, utf8_decode('AÑO:  '.$anio), 0, 1, 'L');
			$pdf->SetXY(5, $y-5); $pdf->Cell(100, 5, 'TIPO DE PRESUPUESTO:  '.$tpresupuesto, 0, 1, 'L'); 
			$pdf->SetXY(5, $y); $pdf->Cell(100, 5, 'FUENTE DE FINANCIAMIENTO:  '.$ffinanciamiento, 0, 1, 'L');
			//-----------
			$y=$pdf->GetY();
			if ($rows_partidas>6 || $y>205) {
				$pag++;
				anexoordencs($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $modulo);
				$y=73;
			} else {
				$y=$pdf->GetY();
				$pdf->SetY($y);	
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(25, 5, 'CAT.PROGR.', 1, 0, 'C', 1);
				$pdf->Cell(25, 5, 'PARTIDA', 1, 0, 'C', 1);
				$pdf->Cell(110, 5, 'DESCRIPCION', 1, 0, 'C', 1);
				$pdf->Cell(45, 5, 'MONTO', 1, 0, 'C', 1);
			}
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 9); 
			for ($i=1; $i<=$rows_partidas; $i++) {
				$field=mysql_fetch_array($query);
				$y+=5;
				$partida=$field[3]." ".$field[4]." ".$field[5]." ".$field[6];
				$monto=number_format($field[1], 2, ',', '.');
				$descripcion=SUBSTR($field[2], 0, 65); 
				$h=5; $l=4; $x1=10.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $field['codigo'], 0, 'C');
				$h=5; $l=4; $x1=35.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $partida, 0, 'C');
				$h=5; $l=4; $x1=60.00125; $w1=110; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, utf8_decode($descripcion), 0, 'L');
				$h=5; $l=4; $x1=170.00125; $w1=45; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto, 0, 'R');
			}
		}
		break;


	//	Trabajadores por tipo de nomina...
	case "trabajadores_por_tipo_de_nomina":
		$pdf=new PDF_MC_Table('L', 'mm', 'Letter');
		$pdf->Open();
		$whe_estado='';
		if($estado=='activos') $whe_estado = " and t.activo_nomina='si'";
		if($estado=='egresados') $whe_estado = " and t.activo_nomina='no'";
		//	-------------------------
		if ($nomina != "") $where = "WHERE rtnt.idtipo_nomina = '".$nomina."'".$whe_estado;
		$orderby = "titulo_nomina, '".$ordenar."'";
		//	-------------------------


		$sql = "SELECT
					rtnt.*,
					tn.titulo_nomina,
					t.nro_ficha,
					t.apellidos,
					t.nombres,
					t.cedula,
					t.fecha_ingreso,
					c.denominacion AS nomcargo,
					no.denominacion AS nomunidad_funcional,
					ue.denominacion AS nomcentro_costo,
					cp.codigo AS codigo_centro
				FROM
					relacion_tipo_nomina_trabajador rtnt
					INNER JOIN trabajador t ON (rtnt.idtrabajador = t.idtrabajador)
					INNER JOIN tipo_nomina tn ON (rtnt.idtipo_nomina = tn.idtipo_nomina)
					INNER JOIN niveles_organizacionales no ON (t.idunidad_funcional = no.idniveles_organizacionales)
					INNER JOIN niveles_organizacionales cno ON (t.centro_costo = cno.idniveles_organizacionales)
					INNER JOIN categoria_programatica cp ON (cno.idcategoria_programatica = cp.idcategoria_programatica)
					INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					LEFT JOIN cargos c On (t.idcargo = c.idcargo)
				$where
				ORDER BY $ordenar";
				
		$query = mysql_query($sql) or die ($sql.mysql_error()); $i=1;
		while ($field = mysql_fetch_array($query)) {
			if ($grupo != $field['idtipo_nomina']) {
				$grupo = $field['idtipo_nomina'];
				if ($i==1) trabajadores_por_tipo_de_nomina($pdf, $field['titulo_nomina'], $estado);
				else {
					$pdf->Ln(5);
					$pdf->SetFont('Arial', 'B', 10);
					$pdf->Cell(200, 4, utf8_decode($field['titulo_nomina']), 0, 1, 'C');
					$pdf->Ln(2);
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);	
					$pdf->SetAligns(array('C','C', 'R', 'L', 'C', 'L', 'L', 'L'));
					$pdf->SetWidths(array(8, 14, 17, 60, 20, 55, 36, 55));
					$pdf->Row(array('No.','Ficha', utf8_decode('Cédula'), 'Apellidos y Nombres', 'F. Ingreso', 'Cargo', 'Cuenta', 'Centro Costo'));
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Ln(4);
				}
			}
			$sql_cuenta = mysql_query("select * from cuentas_bancarias_trabajador where idtrabajador = '".$field['idtrabajador']."' and motivo = '2'")or die(mysql_error());
			$reg_cuenta = mysql_fetch_array($sql_cuenta);
			$pdf->Row(array($i, $field['nro_ficha'], 
							number_format($field['cedula'], 0, '', '.'),
							utf8_decode($field['apellidos'].', '.$field['nombres']), 
							formatoFecha($field['fecha_ingreso']), 
							utf8_decode($field['nomcargo']),
							utf8_decode($reg_cuenta['nro_cuenta']),
							utf8_decode($field['codigo_centro'].' - '.$field['nomcentro_costo'])));
							$pdf->Ln(2);
			$linea=$pdf->GetY(); if ($linea>200) trabajadores_por_tipo_de_nomina($pdf, $field['titulo_nomina'], $estado);
			$i++;
		}
		$pdf->Ln(5);
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(200, 4, "TOTAL DE TRABAJADORES: ".($i-1), 0, 1, 'L');
		break;


	//	Trabajadores por concepto /constante...
	case "trabajadores_por_concepto_constante":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$whe_estado='';
		if($estado=='activos') $whe_estado = " and t.activo_nomina='si'";
		if($estado=='egresados') $whe_estado = " and t.activo_nomina='no'";
		//	-------------------------
		if ($tabla != "") {
			if ($tabla == "conceptos_nomina") $id = "idconceptos_nomina"; else $id = "idconstantes_nomina";
			$filtro = "WHERE rct.tabla = '".$tabla."' AND idconcepto = '".$idconcepto."' AND rtnt.idtipo_nomina = '".$nomina_concepto."'".$whe_estado;
			$inner = "INNER JOIN $tabla cn ON (rct.idconcepto = cn.$id)";
			$orderby = "tabla, codconcepto, $ordenar";
			
			$sql = "SELECT
						rct.*,
						rtnt.idtipo_nomina,
						cn.codigo AS codconcepto,
						cn.descripcion AS nomconcepto,
						t.nro_ficha,
						t.apellidos,
						t.nombres,
						t.cedula,
						t.fecha_ingreso,
						t.sexo,
						c.denominacion AS nomcargo
					FROM
						relacion_concepto_trabajador rct
						$inner
						INNER JOIN trabajador t ON (rct.idtrabajador = t.idtrabajador)
						INNER JOIN relacion_tipo_nomina_trabajador rtnt ON (rtnt.idtrabajador = t.idtrabajador)
						LEFT JOIN cargos c On (t.idcargo = c.idcargo)
					$filtro
					ORDER BY $ordenar";
		} else {
			$orderby = "tabla, codconcepto, $ordenar";
			$sql = "(SELECT
						rct.*,
						cn.codigo AS codconcepto,
						cn.descripcion AS nomconcepto,	
						t.nro_ficha,	
						t.apellidos,
						t.nombres,
						t.cedula,
						t.fecha_ingreso,
						t.sexo,
						c.denominacion AS nomcargo
					FROM
						relacion_concepto_trabajador rct
						INNER JOIN conceptos_nomina cn ON (rct.idconcepto = cn.idconceptos_nomina)
						INNER JOIN trabajador t ON (rct.idtrabajador = t.idtrabajador)
						LEFT JOIN cargos c On (t.idcargo = c.idcargo)
					WHERE
						tabla = 'conceptos_nomina'
					)
			
					UNION
					
					(SELECT
						rct.*,
						cn.codigo AS codconcepto,
						cn.descripcion AS nomconcepto,
						t.nro_ficha,
						t.apellidos,
						t.nombres,
						t.cedula,
						t.fecha_ingreso,
						t.sexo,
						c.denominacion AS nomcargo
					FROM
						relacion_concepto_trabajador rct
						INNER JOIN constantes_nomina cn ON (rct.idconcepto = cn.idconstantes_nomina)
						INNER JOIN trabajador t ON (rct.idtrabajador = t.idtrabajador)
						LEFT JOIN cargos c On (t.idcargo = c.idcargo)
					WHERE
						tabla = 'constantes_nomina'
					)
					
					ORDER BY $ordenar";
		}
		$query = mysql_query($sql) or die ($sql.mysql_error()); $i=1;
		$masculino = 0; $femenino = 0;
		while ($field = mysql_fetch_array($query)) {
			$concepto = $field['codconcepto'].' - '.$field['nomconcepto'];
			
			
				if($field['valor'] <= $valor_evaluar){
					if ($grupo != $field['idconcepto'] || $grupo_tabla != $field['tabla']) {
						$grupo = $field['idconcepto'];
						$grupo_tabla = $field['tabla'];
						if ($i==1) trabajadores_por_concepto_constante($pdf, $concepto, $estado,$evalua_rango,$valor_evaluar,$aumento_aplicar,$aumento_hasta,$nomina_concepto);
						else {
							$pdf->Ln(5);
							$pdf->SetFont('Arial', 'B', 10);
							$pdf->Cell(200, 4, utf8_decode($concepto), 0, 1, 'C');
							$pdf->Ln(2);
							$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
							$pdf->SetFont('Arial', 'B', 8);	
							$pdf->SetAligns(array('C','C', 'R', 'L', 'R', 'R', 'R'));
							$pdf->SetWidths(array(8,20, 20, 92, 20, 20, 20));
							$pdf->Row(array('No.','Ficha', utf8_decode('Cédula'), 'Apellidos y Nombres', 'Sueldo Actual', 'Incremento', 'Nuevo Sueldo'));
							$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Ln(4);
						}
					}
				$pdf->SetFont('Arial', '', 10);
				if ($aumento_aplicar <> ''){
					$aumento=$field['valor']*$aumento_aplicar/100;
				}else{
					$aumento=$aumento_hasta-$field['valor'];
				}
				
				if($field['sexo']== 'M') $masculino = $masculino+1;
				else $femenino = $femenino + 1;
				
				$pdf->Row(array($i, $field['nro_ficha'], number_format($field['cedula'],0,",","."), utf8_decode($field['apellidos']).' '.utf8_decode($field['nombres']), number_format($field['valor'],2,",","."),number_format($aumento,2,",","."),number_format($field['valor']+$aumento,2,",",".")));
				$suma += $field['valor'];
				$suma_aumento += $aumento;
				$linea=$pdf->GetY(); if ($linea>250) trabajadores_por_concepto_constante($pdf, $concepto, $estado,$evalua_rango,$valor_evaluar,$aumento_aplicar,$aumento_hasta,$nomina_concepto);
				$i++;
				$pdf->Ln(3);
				}
		}
		$pdf->Ln(2);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		$y = $pdf->GetY();
		$pdf->Rect(5, $y, 205, 0.1);
		$pdf->SetXY(8, $y);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(35, 5, 'TOTAL INSIDENCIA MENSUAL AUMENTO:', 0, 0, 'L');
		$pdf->Cell(125, 5, number_format($suma, 2, ',', '.'), 0, 0, 'R');
		$pdf->Cell(20, 5, number_format($suma_aumento, 2, ',', '.'), 0, 0, 'R');
		$pdf->Cell(20, 5, number_format($suma+$suma_aumento, 2, ',', '.'), 0, 0, 'R');

		
		
		$pdf->SetXY(8, $y);	
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(30, 20, "TOTAL DE TRABAJADORES BENEFICIADOS: ".($i-1)."", 0, 1, 'L');

		$y = $pdf->GetY();
		$pdf->SetXY(8, $y);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(30, 5, "TOTAL DE TRABAJADORES MASCULINO: ".($masculino)."", 0, 1, 'L');
		$pdf->Ln(2);
		$y = $pdf->GetY();
		$pdf->SetXY(8, $y);	
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(30, 5, "TOTAL DE TRABAJADORES FEMENINO: ".($femenino)."", 0, 1, 'L');
		break;
		
	
	//	Trabajadores por unidad funcional...
	case "trabajadores_por_unidad_funcional":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$whe_estado='';
		if($estado=='activos') $whe_estado = " and t.activo_nomina='si'";
		if($estado=='egresados') $whe_estado = " and t.activo_nomina='no'";
		//	-------------------------
		if ($idunidad != "") { $where = "WHERE t.idunidad_funcional = '".$idunidad."'".$whe_estado; $orderby = $ordenar; }
		else { $orderby = "norg.codigo, $ordenar"; }
		//	-------------------------
		$sql = "SELECT
					norg.idniveles_organizacionales,
					norg.codigo AS codunidad,
					norg.denominacion AS nomunidad,
					t.nro_ficha,
					t.apellidos,
					t.nombres,
					t.cedula,
					t.fecha_ingreso,
					c.denominacion AS nomcargo
				FROM
					trabajador t
					INNER JOIN niveles_organizacionales norg ON (t.idunidad_funcional = norg.idniveles_organizacionales)
					LEFT JOIN cargos c On (t.idcargo = c.idcargo)
				$where
				ORDER BY $ordenar";
		$query = mysql_query($sql) or die ($sql.mysql_error()); $i=1;
		while ($field = mysql_fetch_array($query)) {
			if ($grupo != $field['idniveles_organizacionales']) {
				$grupo = $field['idniveles_organizacionales'];
				$unidad_funcional = $field['codunidad']."  ".$field['nomunidad'];
				if ($i==1) trabajadores_por_unidad_funcional($pdf, $unidad_funcional, $estado);
				else {
					$pdf->Ln(5);
					$pdf->SetFont('Arial', 'B', 10);
					$pdf->Cell(200, 4, utf8_decode($unidad_funcional), 0, 1, 'C');
					$pdf->Ln(2);
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);	
					$pdf->SetAligns(array('C', 'C', 'R', 'L', 'C', 'L'));
					$pdf->SetWidths(array(8, 20, 20, 62, 20, 70));
					$pdf->Row(array('No.','Ficha', utf8_decode('Cédula'), 'Apellidos y Nombres', 'F. Ingreso', 'Cargo'));
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 8);
					
				}
			}
			
			$pdf->Row(array($i, $field['nro_ficha'], 
							number_format($field['cedula'], 0, '', '.'),
							utf8_decode($field['apellidos'].', '.$field['nombres']), 
							formatoFecha($field['fecha_ingreso']), 
							utf8_decode($field['nomcargo'])));
			$linea=$pdf->GetY(); if ($linea>250) trabajadores_por_unidad_funcional($pdf, $unidad_funcional, $estado);
			$i++;
		}
		
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(300, 20, "TOTAL DE TRABAJADORES: ".($i-1)."", 0, 1, 'L');
		
		break;
		
	
	//	Trabajadores por centro de ostos...
	case "trabajadores_por_centro_costos":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$whe_estado='';
		if($estado=='activos') $whe_estado = " and t.activo_nomina='si'";
		if($estado=='egresados') $whe_estado = " and t.activo_nomina='no'";
		//	-------------------------
		if ($idcentro != "") { $where = "WHERE no.idcategoria_programatica = '".$idcentro."'".$whe_estado; $orderby = $ordenar; }
		else { $orderby = "cp.codigo, $ordenar"; }

		$ordenar = "nomcargo, t.nro_ficha";
		//	-------------------------
		$sql = "SELECT
					cp.idcategoria_programatica,
					cp.codigo AS codcentro,
					ue.denominacion AS nomcentro,
					t.nro_ficha,
					t.apellidos,
					t.nombres,
					t.cedula,
					t.fecha_ingreso,
					c.denominacion AS nomcargo,
					t.idtrabajador
				FROM
					trabajador t
					INNER JOIN niveles_organizacionales no ON (t.centro_costo = no.idniveles_organizacionales)
					INNER JOIN categoria_programatica cp ON (no.idcategoria_programatica = cp.idcategoria_programatica AND no.idcategoria_programatica <> '0')
					INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					LEFT JOIN cargos c On (t.idcargo = c.idcargo)
				$where
				ORDER BY $ordenar";
		$query = mysql_query($sql) or die ($sql.mysql_error()); $i=1;
		while ($field = mysql_fetch_array($query)) {
			if ($grupo != $field['idcategoria_programatica']) {
				$grupo = $field['idcategoria_programatica'];
				$centro_costo = $field['codcentro']."  ".$field['nomcentro'];
				if ($i==1) trabajadores_por_centro_costos($pdf, $centro_costo, $estado);
				else {
					$pdf->Ln(5);
					$pdf->SetFont('Arial', 'B', 10);
					$pdf->Cell(200, 4, utf8_decode($centro_costo), 0, 1, 'C');
					$pdf->Ln(2);
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);	
					$pdf->SetAligns(array('C','C', 'R', 'L', 'C', 'L'));
					$pdf->SetWidths(array(8, 20, 20, 62, 20, 70));
					$pdf->Row(array('No.','Ficha', utf8_decode('Cédula'), 'Apellidos y Nombres', 'F. Ingreso', 'Cargo'));
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Ln(4);
				}
			}
			$sql_sueldo = mysql_query("select * from relacion_concepto_trabajador 
														where idtrabajador = '".$field["idtrabajador"]."'
														and tabla='constantes_nomina' 
														and idconcepto = 1")or die (mysql_error());
			$reg_sueldo = mysql_fetch_array($sql_sueldo);
			$pdf->Row(array($i, $field['nro_ficha'], 
							number_format($field['cedula'], 0, '', '.'),
							utf8_decode($field['apellidos'].', '.$field['nombres']), 
							number_format($reg_sueldo['valor'], 2, ',', '.'),
							utf8_decode($field['nomcargo'])));

			/*$pdf->Row(array($i, $field['nro_ficha'], 
							number_format($field['cedula'], 0, '', '.'),
							utf8_decode($field['apellidos'].', '.$field['nombres']), 
							formatoFecha($field['fecha_ingreso']), 
							utf8_decode($field['nomcargo'])));
			*/
			$linea=$pdf->GetY(); if ($linea>250) trabajadores_por_centro_costos($pdf, $centro_costo, $estado);
			$i++;
		}
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(300, 20, "TOTAL DE TRABAJADORES: ".($i-1)."", 0, 1, 'L');
		
		
		break;
		
	
	//	Trabajadores carga familiar...
	case "trabajadores_carga_familiar":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		//	-------------------------
		if ($parentesco != "") $filtro_parentesco = "AND cf.idparentezco = '".$parentesco."'";
		//	-------------------------
		//	determino cual es el agrupador
		if ($rdonomina == "true") {
			if ($nomina != "") $where = "WHERE rtnt.idtipo_nomina = '".$nomina."'";
			$sql = "SELECT
						t.idtrabajador,
						t.nro_ficha,
						t.apellidos,
						t.nombres,
						t.cedula,
						t.fecha_ingreso,
						rtnt.idtipo_nomina AS idagrupador,
						'' AS codagrupador,
						tn.titulo_nomina AS nomagrupador
					FROM
						trabajador t
						INNER JOIN relacion_tipo_nomina_trabajador rtnt ON (t.idtrabajador = rtnt.idtrabajador)
						INNER JOIN tipo_nomina tn ON (rtnt.idtipo_nomina = tn.idtipo_nomina)
						INNER JOIN carga_familiar cf ON (t.idtrabajador = cf.idtrabajador $filtro_parentesco)
					$where
					GROUP BY idtrabajador
					ORDER BY nomagrupador, $ordenar";
		}
		elseif ($rdounidad == "true") {
			if ($idunidad != "") $where = "WHERE norg.idniveles_organizacionales = '".$idunidad."'";
			$sql = "SELECT
						t.idtrabajador,
						t.nro_ficha,
						t.apellidos,
						t.nombres,
						t.cedula,
						t.fecha_ingreso,
						norg.idniveles_organizacionales AS idagrupador,
						norg.codigo AS codagrupador,
						norg.denominacion AS nomagrupador
					FROM
						trabajador t
						INNER JOIN niveles_organizacionales norg ON (t.idunidad_funcional = norg.idniveles_organizacionales)
						INNER JOIN carga_familiar cf ON (t.idtrabajador = cf.idtrabajador $filtro_parentesco)
					$where
					GROUP BY idtrabajador
					ORDER BY nomagrupador, $ordenar";
		}
		elseif ($rdocentro == "true") {
			if ($idcentro != "") $where = "WHERE no.idcategoria_programatica = '".$idcentro."'";
			$sql = "SELECT
						t.idtrabajador,
						t.nro_ficha,
						t.apellidos,
						t.nombres,
						t.cedula,
						t.fecha_ingreso,
						cp.idcategoria_programatica AS idagrupador,
						cp.codigo AS codagrupador,
						ue.denominacion AS nomagrupador
					FROM
						trabajador t
						INNER JOIN niveles_organizacionales no ON (t.centro_costo = no.idniveles_organizacionales)
						INNER JOIN categoria_programatica cp ON (no.idcategoria_programatica = cp.idcategoria_programatica AND no.idcategoria_programatica <> '0')
						INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
						INNER JOIN carga_familiar cf ON (t.idtrabajador = cf.idtrabajador $filtro_parentesco)
					$where
					GROUP BY idtrabajador
					ORDER BY nomagrupador, $ordenar";
		}
		elseif ($rdotrabajador == "true") {
			if ($idtrabajador != "") $where = "WHERE t.idtrabajador = '".$idtrabajador."'";
			$sql = "SELECT
						t.idtrabajador,
						t.nro_ficha,
						t.apellidos,
						t.nombres,
						t.cedula,
						t.fecha_ingreso,
						'' AS idagrupador,
						'' AS codagrupador,
						'' AS nomagrupador
					FROM
						trabajador t
						INNER JOIN niveles_organizacionales no ON (t.centro_costo = no.idniveles_organizacionales)
						INNER JOIN categoria_programatica cp ON (no.idcategoria_programatica = cp.idcategoria_programatica AND no.idcategoria_programatica <> '0')
						INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
						INNER JOIN carga_familiar cf ON (t.idtrabajador = cf.idtrabajador $filtro_parentesco)
					$where
					GROUP BY idtrabajador
					ORDER BY nomagrupador, $ordenar";
		}
		//	-------------------------
		//	imprimo la lista de trabajadores con su carga familiar
		$query = mysql_query($sql) or die ($sql.mysql_error()); $i=1; $sw = false;
		while ($field = mysql_fetch_array($query)) {
			if ($i==1) trabajadores_carga_familiar($pdf, $agrupador);
			$linea=$pdf->GetY(); if ($linea>250) trabajadores_carga_familiar($pdf, $agrupador);
			
			//	imprimo el trabajador
			$pdf->SetFont('Arial', 'BI', 8);	
			$pdf->SetAligns(array('C', 'R', 'L', 'C'));
			$pdf->SetWidths(array(20, 20, 100, 20));
			$pdf->Row(array($field['nro_ficha'], 
							number_format($field['cedula'], 0, '', '.'),
							utf8_decode($field['nombres'].', '.$field['apellidos']), 
							formatoFecha($field['fecha_ingreso'])));
			
			$pdf->Ln(1);
			$linea=$pdf->GetY(); if ($linea>250) trabajadores_carga_familiar($pdf, $agrupador);
			
			//	imprimo la carga familiar			
			$sql = "SELECT
						cf.cedula,
						cf.nombres,
						cf.apellidos,
						cf.fecha_nacimiento,
						p.denominacion AS parentezco
					FROM
						carga_familiar cf
						LEFT JOIN parentezco p ON (cf.idparentezco = p.idparentezco)
					WHERE
						idtrabajador = '".$field['idtrabajador']."' $filtro_parentesco
					ORDER BY length(cedula), cedula";			
			$query_carga = mysql_query($sql) or die ($sql.mysql_error());			
			if ($grupo != $field['idagrupador'] && $rdotrabajador == "false") {
				$grupo = $field['idagrupador'];				
				$agrupador = trim($field['codagrupador']."  ".$field['nomagrupador']);
				if ($i!=1){
					$pdf->Ln(5);
					$pdf->SetFont('Arial', 'B', 10);
					$pdf->Cell(200, 4, utf8_decode($agrupador), 0, 1, 'L');
					$pdf->Ln(2);
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(245, 245, 245); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);	
					$pdf->SetAligns(array('C', 'R', 'L', 'C'));
					$pdf->SetWidths(array(20, 20, 100, 20));
					$pdf->Row(array('Ficha', utf8_decode('Cédula'), 'Nombres y Apellidos', 'F. Ingreso'));
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 8);
					
				}
			} elseif (!$sw && $rdotrabajador == "true") {
				trabajadores_carga_familiar($pdf, $agrupador);
				$sw = true;
			}
			while ($field_carga = mysql_fetch_array($query_carga)) {
				if ($field_carga['cedula'] == "") $cedula = "NT"; else $cedula = number_format($field_carga['cedula'], 0, '', '.');
				$edad = obtenerEdad($field_carga['fecha_nacimiento']);
				
				$pdf->SetFont('Arial', '', 8);	
				$pdf->SetAligns(array('R', 'L', 'C', 'L', 'L'));
				$pdf->SetWidths(array(20, 100, 20, 20, 15));
				$pdf->Cell(20, 4);				
				$pdf->Row(array($cedula, 
								utf8_decode($field_carga['nombres'].', '.$field_carga['apellidos']), 
								formatoFecha($field_carga['fecha_nacimiento']),
								$field_carga['parentezco'],
								utf8_decode($edad.' años')));
			}
			$pdf->Ln(10);			
			$i++;
		}
		
		break;
		
	
	//	Ficha del Trabajador...
	case "trabajadores_ficha":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$pdf->SetDrawColor(255, 255, 255);
		//	-------------------------		
		if ($rdonomina == "true" && $nomina != "") { $where = "AND rtnt.idtipo_nomina = '".$nomina."'"; $ordenar_rdo = "titulo_nomina"; }
		elseif ($rdounidad == "true" && $idunidad != "") { $where = "AND norg.idniveles_organizacionales = '".$idunidad."'"; $ordenar_rdo = "ubicacion_funcional"; }
		elseif ($rdocentro == "true" && $idcentro != "") { $where = "AND no.idcategoria_programatica = '".$idcentro."'"; $ordenar_rdo = "nom_centro_costo"; }
		elseif ($rdotrabajador == "true" && $idtrabajador != "") { $where = "AND t.idtrabajador = '".$idtrabajador."'"; $ordenar_rdo = "idtrabajador"; }
		else $ordenar_rdo = $ordenar;
		//	-------------------------
		//	consulto trabajadores filtrados
		$sql = "SELECT
					t.*,
					n.denominacion AS nacionalidad,
					ec.denominacion AS estado_civil,
					c.denominacion AS cargo,
					tn.titulo_nomina,
					norg.codigo AS codigo_ubicacion_funcional,
					norg.denominacion AS ubicacion_funcional,
					cp.codigo AS codigo_centro,
					ue.denominacion AS nom_centro_costo
				FROM
					trabajador t
					LEFT JOIN relacion_tipo_nomina_trabajador rtnt ON (t.idtrabajador = rtnt.idtrabajador)
					LEFT JOIN tipo_nomina tn ON (rtnt.idtipo_nomina = tn.idtipo_nomina)
					LEFT JOIN niveles_organizacionales norg ON (t.idunidad_funcional = norg.idniveles_organizacionales)
					LEFT JOIN niveles_organizacionales no ON (t.centro_costo = no.idniveles_organizacionales)
					LEFT JOIN categoria_programatica cp ON (no.idcategoria_programatica = cp.idcategoria_programatica AND no.idcategoria_programatica <> '0')
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					LEFT JOIN carga_familiar cf ON (t.idtrabajador = cf.idtrabajador $filtro_parentesco)
					LEFT JOIN nacionalidad n ON (t.idnacionalidad = n.idnacionalidad)
					LEFT JOIN edo_civil ec ON (t.idedo_civil = ec.idedo_civil)
					LEFT JOIN cargos c ON (t.idcargo = c.idcargo)
				WHERE 1 $where
				GROUP BY idtrabajador
				ORDER BY $ordenar_rdo, $ordenar";
		$query_datos_basicos = mysql_query($sql) or die ($sql.mysql_error()); $i=1; $sw = false;
		while ($field_datos_basicos = mysql_fetch_array($query_datos_basicos)) {
			trabajadores_ficha($pdf);			
			
			//	imprimo datos basicos
			if ($field_datos_basicos['sexo'] == "M") $sexo_trabajador = "Masculino"; else $sexo_trabajador = "Femenino";
			
			trabajadores_ficha_banda($pdf, "DATOS");
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, 'Nro. Ficha:', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(70, 5, $field_datos_basicos['nro_ficha'], 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, utf8_decode('Cédula:'), 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(70, 5, number_format($field_datos_basicos['cedula'], 0, '', '.'), 0, 1, 'L');
			$pdf->Ln(1);
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, 'Apellidos: ', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(70, 5, utf8_decode($field_datos_basicos['apellidos']), 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, 'Nombres: ', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(70, 5, utf8_decode($field_datos_basicos['nombres']), 0, 1, 'L');
			$pdf->Ln(1);
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, 'R.I.F.: ', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(70, 5, $field_datos_basicos['rif'], 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, 'Nro. Pasaporte: ', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(70, 5, $field_datos_basicos['nro_pasaporte'], 0, 1, 'L');
			$pdf->Ln(1);
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, 'Nacionalidad: ', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(70, 5, utf8_decode($field_datos_basicos['nacionalidad']), 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, 'F. Nacimiento: ', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(70, 5, formatoFecha($field_datos_basicos['fecha_nacimiento']), 0, 1, 'L');
			$pdf->Ln(1);
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, 'Lugar de Nac.: ', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(170, 5, utf8_decode($field_datos_basicos['lugar_nacimiento']), 0, 1, 'L');
			$pdf->Ln(1);
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, 'Sexo: ', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(70, 5, $sexo_trabajador, 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, 'Edo. Civil: ', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(70, 5, utf8_decode($field_datos_basicos['estado_civil']), 0, 1, 'L');
			$pdf->Ln(1);
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, utf8_decode('Dirección:'), 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->MultiCell(170, 5, utf8_decode($field_datos_basicos['direccion']), 0, 'L');
			$pdf->Ln(1);
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, utf8_decode('Tlf. Habitación: '), 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(70, 5, $field_datos_basicos['telefono_habitacion'], 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, utf8_decode('Tlf. Móvil:'), 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(70, 5, utf8_decode($field_datos_basicos['telefono_movil']), 0, 1, 'L');
			$pdf->Ln(1);
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, utf8_decode('e-Mail:'), 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(170, 5, utf8_decode($field_datos_basicos['correo_electronico']), 0, 1, 'L');
			$pdf->Ln(1);
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, utf8_decode('Contacto Emerg.: '), 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(70, 5, utf8_decode($field_datos_basicos['nombre_emergencia']), 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, utf8_decode('Tlf. Emergencia:'), 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(70, 5, utf8_decode($field_datos_basicos['telefono_emergencia']), 0, 1, 'L');
			$pdf->Ln(1);
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, 'Dir. Emergencia:', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(170, 5, utf8_decode($field_datos_basicos['direccion_emergencia']), 0, 1, 'L');
			$pdf->Ln(1);
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, 'F. Ingreso:', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(170, 5, formatoFecha($field_datos_basicos['fecha_ingreso']), 0, 1, 'L');
			$pdf->Ln(1);
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, 'Cargo:', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(170, 5, utf8_decode($field_datos_basicos['cargo']), 0, 1, 'L');
			$pdf->Ln(1);
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, 'Ubic. Funcional:', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(170, 5, utf8_decode($field_datos_basicos['ubicacion_funcional']), 0, 1, 'L');
			$pdf->Ln(1);
			$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(30, 5, 'Centro de Costo:', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 9);	$pdf->Cell(170, 5, utf8_decode($field_datos_basicos['nom_centro_costo']), 0, 1, 'L');
			$pdf->Ln(15);
			
			//	imprimo cuentas bancarias
			$sql = "SELECT
						cbt.nro_cuenta,
						cbt.tipo,
						mc.denominacion AS motivo,
						b.denominacion AS banco
					FROM
						cuentas_bancarias_trabajador cbt
						INNER JOIN motivos_cuentas mc ON (cbt.motivo = mc.idmotivos_cuentas)
						INNER JOIN banco b ON (cbt.banco = b.idbanco)
					WHERE
						cbt.idtrabajador = '".$field_datos_basicos['idtrabajador']."'";
			$query_cuentas = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_cuentas) != 0) trabajadores_ficha_banda($pdf, "CUENTAS");
			while ($field_cuentas = mysql_fetch_array($query_cuentas)) {
				$pdf->SetFont('Arial', '', 9);	
				$pdf->SetAligns(array('L', 'L', 'L', 'L'));
				$pdf->SetWidths(array(45, 85, 35, 35));
				$pdf->Row(array(utf8_decode($field_cuentas['nro_cuenta']),
								utf8_decode($field_cuentas['banco']), 
								utf8_decode($field_cuentas['tipo']), 
								utf8_decode($field_cuentas['motivo'])));
				$pdf->Ln(2);
				$linea = $pdf->GetY(); if ($linea>250) { trabajadores_ficha($pdf); trabajadores_ficha_banda($pdf, "CUENTAS"); }
			}
			if (mysql_num_rows($query_cuentas) != 0) $pdf->Ln(15);
			
			//	imprimo la carga familiar
			$sql = "SELECT
						cf.cedula,
						cf.nombres,
						cf.apellidos,
						cf.fecha_nacimiento,
						p.denominacion AS parentezco
					FROM
						carga_familiar cf
						LEFT JOIN parentezco p ON (cf.idparentezco = p.idparentezco)
					WHERE
						idtrabajador = '".$field_datos_basicos['idtrabajador']."' $filtro_parentesco
					ORDER BY length(cedula), cedula";			
			$query_carga = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_carga) != 0) trabajadores_ficha_banda($pdf, "CARGA");
			while ($field_carga = mysql_fetch_array($query_carga)) {
				if ($field_carga['cedula'] == "") $cedula = "NT"; else $cedula = number_format($field_carga['cedula'], 0, '', '.');
				
				$pdf->SetFont('Arial', '', 9);	
				$pdf->SetAligns(array('R', 'L', 'C', 'L'));
				$pdf->SetWidths(array(20, 120, 25, 35));
				$pdf->Row(array($cedula, 
								utf8_decode($field_carga['nombres'].', '.$field_carga['apellidos']), 
								formatoFecha($field_carga['fecha_nacimiento']),
								$field_carga['parentezco']));
				$pdf->Ln(2);
				$linea = $pdf->GetY(); if ($linea>250) { trabajadores_ficha($pdf); trabajadores_ficha_banda($pdf, "CARGA"); }
			}
			if (mysql_num_rows($query_carga) != 0) $pdf->Ln(15);
			
			//	imprimo instruccion academica
			$sql = "SELECT
						ia.institucion,
						ia.anio_egreso,
						ia.flag_constancia,
						ne.denominacion AS nivel,
						p.denominacion AS profesion,
						m.denominacion AS mension
					FROM
						instruccion_academica ia
						INNER JOIN nivel_estudio ne ON (ia.idnivel_estudio = ne.idnivel_estudio)
						INNER JOIN profesion p ON (ia.idprofesion = p.idprofesion)
						INNER JOIN mension m ON (ia.idmension = m.idmension)
					WHERE
						ia.idtrabajador = '".$field_datos_basicos['idtrabajador']."'";
			$query_estudios = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_estudios) != 0) trabajadores_ficha_banda($pdf, "ESTUDIOS");
			while ($field_estudios = mysql_fetch_array($query_estudios)) {
				if ($field_estudios['flag_constancia'] == 1) $flag_constancia = "Si"; else $flag_constancia = "No";
				
				$pdf->SetFont('Arial', '', 9);	
				$pdf->SetAligns(array('L', 'L', 'L', 'C', 'C'));
				$pdf->SetWidths(array(40, 65, 65, 20, 10));
				$pdf->Row(array(utf8_decode($field_estudios['nivel']),
								utf8_decode($field_estudios['profesion']),
								utf8_decode($field_estudios['mension']),
								utf8_decode($field_estudios['anio_egreso']),
								$flag_constancia));
				$pdf->Ln(2);
				$linea = $pdf->GetY(); if ($linea>250) { trabajadores_ficha($pdf); trabajadores_ficha_banda($pdf, "ESTUDIOS"); }
			}
			if (mysql_num_rows($query_estudios) != 0) $pdf->Ln(15);
			
			//	imprimo experiencia laboral
			$sql = "SELECT
						e.empresa,
						e.desde,
						e.hasta,
						e.ultimo_cargo
					FROM
						experiencia_laboral e
					WHERE
						e.idtrabajador = '".$field_datos_basicos['idtrabajador']."'";
			$query_experiencia = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_experiencia) != 0) trabajadores_ficha_banda($pdf, "EXPERIENCIA");
			while ($field_experiencia = mysql_fetch_array($query_experiencia)) {
				$pdf->SetFont('Arial', '', 9);	
				$pdf->SetAligns(array('L', 'C', 'C', 'L'));
				$pdf->SetWidths(array(60, 40, 40, 60));
				$pdf->Row(array(utf8_decode($field_experiencia['empresa']),
								formatoFecha($field_experiencia['desde']),
								formatoFecha($field_experiencia['hasta']),
								utf8_decode($field_experiencia['ultimo_cargo'])));
				$pdf->Ln(2);
				$linea = $pdf->GetY(); if ($linea>250) { trabajadores_ficha($pdf); trabajadores_ficha_banda($pdf, "EXPERIENCIA"); }
			}
			if (mysql_num_rows($query_experiencia) != 0) $pdf->Ln(15);
			
			//	imprimo movimientos
			$sql = "SELECT
						mp.fecha_movimiento,
						mp.justificacion,
						tmp.denominacion AS tipo
					FROM
						movimientos_personal mp
						INNER JOIN tipo_movimiento_personal tmp ON (mp.idtipo_movimiento = tmp.idtipo_movimiento)
					WHERE
						mp.idtrabajador = '".$field_datos_basicos['idtrabajador']."'";
			$query_movimiento = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_movimiento) != 0) trabajadores_ficha_banda($pdf, "MOVIMIENTO");
			while ($field_movimiento = mysql_fetch_array($query_movimiento)) {
				$pdf->SetFont('Arial', '', 9);	
				$pdf->SetAligns(array('C', 'L', 'L'));
				$pdf->SetWidths(array(30, 40, 130));
				$pdf->Row(array(formatoFecha($field_movimiento['fecha_movimiento']),
								utf8_decode($field_movimiento['tipo']),
								utf8_decode($field_movimiento['justificacion'])));
				$pdf->Ln(2);
				$linea = $pdf->GetY(); if ($linea>250) { trabajadores_ficha($pdf); trabajadores_ficha_banda($pdf, "MOVIMIENTO"); }
			}
			if (mysql_num_rows($query_movimiento) != 0) $pdf->Ln(15);
			
			//	imprimo permisos
			$sql = "SELECT
						hp.*
					FROM
						historico_permisos hp
					WHERE
						hp.idtrabajador = '".$field_datos_basicos['idtrabajador']."'";
			$query_permisos = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_movimiento) != 0) trabajadores_ficha_banda($pdf, "PERMISOS");
			while ($field_permisos = mysql_fetch_array($query_permisos)) {
				$desde = formatoFecha($field_permisos['fecha_inicio'])." ".$field_permisos['hora_inicio'];
				$hasta = formatoFecha($field_permisos['fecha_culminacion'])." ".$field_permisos['hora_culminacion'];
				if ($field_permisos['descuenta_bono_alimentacion'] == 1) $flag_bono = "Si"; else $flag_bono = "No";
				if ($field_permisos['remunerado'] == 1) $flag_remunerado = "Si"; else $flag_remunerado = "No";
				if ($field_permisos['justificado'] == 1) $flag_justificado = "Si"; else $flag_justificado = "No";
				
				$pdf->SetFont('Arial', '', 9);	
				$pdf->SetAligns(array('C', 'C', 'C', 'L', 'C', 'C', 'C'));
				$pdf->SetWidths(array(30, 30, 20, 75, 15, 15, 15));
				$pdf->Row(array($desde,
								$hasta,
								$field_permisos['tiempo_total'],
								utf8_decode($field_permisos['motivo']),
								$flag_bono,
								$flag_remunerado,
								$flag_justificado));
				$pdf->Ln(2);
				$linea = $pdf->GetY(); if ($linea>250) { trabajadores_ficha($pdf); trabajadores_ficha_banda($pdf, "PERMISOS"); }
			}
			if (mysql_num_rows($query_permisos) != 0) $pdf->Ln(15);
			
			$i++;
		}
		
		break;
		
	
	//	Certificacion de Rcursos Humanos...
	case "certificacion_compromiso_rrhh":			
		$pag=0;
		//	Obtengo los datos de la certificacion 
		$sql="SELECT o.numero_orden, 
					 o.fecha_orden, 
					 o.tipo, 
					 o.justificacion, 
					 o.numero_requisicion,
					 o.fecha_requisicion, 
					 o.exento, 
					 o.sub_total, 
					 o.total, 
					 b.nombre AS Beneficiario, 
					 t.descripcion AS TipoDocumento,
					 t.modulo,
					 t.forma_preimpresa
			  FROM 
					orden_compra_servicio o 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
					INNER JOIN tipos_documentos t ON (o.tipo=t.idtipos_documentos) 
			  WHERE 
					o.idorden_compra_servicio='".$id_orden_compra."'";
		$query_datos=mysql_query($sql) or die ($sql.mysql_error());
		$rows_datos=mysql_num_rows($query_datos);
		if ($rows_datos!=0) $field_datos=mysql_fetch_array($query_datos);
		list($a, $m, $d)=SPLIT( '[/.-]', $field_datos['fecha_requisicion']); $fecha_requisicion="$d-$m-$a";
		list($a, $m, $d)=SPLIT( '[/.-]', $field_datos['fecha_orden']); $fecha_orden="$d-$m-$a";
		$numero_orden=$field_datos['numero_orden'];
		$tipo_documento=$field_datos['TipoDocumento'];
		$asignaciones=number_format($field_datos['sub_total'], 2, ',', '.');
		$deducciones=number_format($field_datos['exento'], 2, ',', '.');
		$neto=number_format($field_datos['total'], 2, ',', '.');
		//	------------------------------------------------------------
		//	IMPRIMO LA CERTIFICACION DE COMPROMISO MODULO=1 Y FORMA  PREIMPRESA=NO
		//	------------------------------------------------------------
		$field_datos['modulo'] = explode("-", $field_datos['modulo']);
		if ((in_array(1,$field_datos['modulo']) || in_array(13,$field_datos['modulo'])) && $field_datos['forma_preimpresa']=="no") {
			$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
			$pdf->Open();
			certificacion_compromiso_rrhh($pdf, $tipo_documento, $numero_orden, $fecha_orden, ++$pag);
			//	---------------------------------
			$pdf->SetY(40);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(140, 5, 'BENEFICIARIO', 1, 0, 'L', 1); $pdf->Cell(35, 5, 'MEMORANDUM', 1, 0, 'C', 1); $pdf->Cell(25, 5, 'FECHA', 1, 1, 'C', 1);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(140, 5, substr(utf8_decode($field_datos['Beneficiario']), 0, 90), 1, 0, 'L', 1); $pdf->Cell(35, 5, $field_datos['numero_requisicion'], 1, 0, 'L', 1); $pdf->Cell(25, 5, $fecha_requisicion, 1, 1, 'C', 1);
			//	---------------------------------
			$pdf->Ln(1);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); 
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(200, 5, 'CONCEPTO DEL GASTO', 1, 1, 'C', 1);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);  
			$pdf->SetFont('Arial', '', 8);
			$pdf->MultiCell(200, 5, utf8_decode($field_datos['justificacion']), 0, 'L', 1); 
			$pdf->Rect(8, 51, 200, 35);
			//	---------------------------------
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8); 
			$pdf->SetXY(135, 90); $pdf->Cell(35, 5, 'ASIGNACIONES:', 0, 1, 'R', 1); 
			$pdf->SetXY(135, 95); $pdf->Cell(35, 5, 'DEDUCCIONES:', 0, 1, 'R', 1); 
			$pdf->SetXY(135, 100); $pdf->Cell(35, 5, 'NETO A PAGAR:', 0, 1, 'R', 1);
			$pdf->SetFont('Arial', 'B', 10); 
			$pdf->SetXY(170, 90); $pdf->Cell(35, 5, $asignaciones, 0, 1, 'R', 1); 
			$pdf->SetXY(170, 95); $pdf->Cell(35, 5, $deducciones, 0, 1, 'R', 1); 
			$pdf->SetXY(170, 100); $pdf->Cell(35, 5, $neto, 0, 1, 'R', 1);
			//	---------------------------------
			$pdf->SetY(110);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); 
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Cell(200, 5, 'PARA EL USO DEL AREA ADMINISTRATIVA', 0, 1, 'C', 1);
			//	---------------------------------
			//	IMPRIMIR LAS PARTIDAS
			//	---------------------------------
			$pdf->Ln(1);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(25, 5, 'CAT. PROG.', 1, 0, 'C', 1);
			$pdf->Cell(25, 5, 'PARTIDA', 1, 0, 'C', 1);
			$pdf->Cell(110, 5, 'DESCRIPCION', 1, 0, 'C', 1);
			$pdf->Cell(40, 5, 'MONTO', 1, 0, 'C', 1);
			$pdf->Ln(7);
			//	
			$sql="SELECT partidas_orden_compra_servicio.idmaestro_presupuesto, 
						 partidas_orden_compra_servicio.monto, 
						 clasificador_presupuestario.denominacion, 
						 clasificador_presupuestario.partida, 
						 clasificador_presupuestario.generica, 
						 clasificador_presupuestario.especifica, 
						 clasificador_presupuestario.sub_especifica, 
						 maestro_presupuesto.idclasificador_presupuestario, 
						 categoria_programatica.codigo 
					FROM 
						partidas_orden_compra_servicio, 
						clasificador_presupuestario, 
						maestro_presupuesto, 
						categoria_programatica 
					WHERE 
						(partidas_orden_compra_servicio.idorden_compra_servicio='".$id_orden_compra."') AND 
						(partidas_orden_compra_servicio.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND 
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
						maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica
					ORDER BY 
						 categoria_programatica.codigo,
						 clasificador_presupuestario.partida, 
						 clasificador_presupuestario.generica, 
						 clasificador_presupuestario.especifica, 
						 clasificador_presupuestario.sub_especifica";
			$query_partidas=mysql_query($sql) or die ($sql.mysql_error());
			$rows_partidas=mysql_num_rows($query_partidas);
			if ($rows_partidas<22) {
				for ($i=1; $i<=$rows_partidas; $i++) {
					$field_partidas=mysql_fetch_array($query_partidas);
					$partida=$field_partidas['partida']." ".$field_partidas['generica']." ".$field_partidas['especifica']." ".$field_partidas['sub_especifica'];
					$monto=number_format($field_partidas['monto'], 2, ',', '.');
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); 
					$pdf->SetFont('Arial', '', 8);
					$pdf->SetAligns(array('C', 'C', 'L', 'R'));
					$pdf->SetWidths(array(25, 25, 110, 40));
					$pdf->Row(array($field_partidas['codigo'], $partida, utf8_decode($field_partidas['denominacion']), $monto));
				}
			} else {
				anexo_certificacion_compromiso_rrhh($pdf, $tipo_documento, $numero_orden, $fecha_orden, ++$pag, $field_datos['idtipos_documentos'], $modulo );
				for ($i=1; $i<=$rows_partidas; $i++) {
					$field_partidas=mysql_fetch_array($query_partidas);
					$partida=$field_partidas['partida']." ".$field_partidas['generica']." ".$field_partidas['especifica']." ".$field_partidas['sub_especifica'];
					$monto=number_format($field_partidas['monto'], 2, ',', '.');
					$sum_monto+=$field_partidas['monto'];
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); 
					$pdf->SetFont('Arial', '', 8);
					$pdf->SetAligns(array('C', 'C', 'L', 'R'));
					$pdf->SetWidths(array(25, 25, 110, 40));
					$pdf->Row(array($field_partidas['codigo'], $partida, utf8_decode($field_partidas['denominacion']), $monto));
					$y=$pdf->GetY(); 
					if ($y>250 || $i==$rows_partidas) { 
						if ($i==$rows_partidas) {
							$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0); 
							$pdf->Rect(10, $pdf->GetY()+2, 198, 0.1);
							$pdf->Ln(5);
							$van_monto=number_format($sum_monto, 2, ',', '.');
							$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); 
							$pdf->SetFont('Arial', '', 8);
							$pdf->SetAligns(array('C', 'C', 'R', 'R'));
							$pdf->SetWidths(array(25, 25, 110, 40));
							$pdf->Row(array('', '', 'TOTAL .............>', $van_monto));
						} else {
							$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0); 
							$pdf->Rect(10, $pdf->GetY()+2, 198, 0.1);
							$pdf->Ln(5);
							$van_monto=number_format($sum_monto, 2, ',', '.');
							$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); 
							$pdf->SetFont('Arial', '', 8);
							$pdf->SetAligns(array('C', 'C', 'R', 'R'));
							$pdf->SetWidths(array(25, 25, 110, 40));
							$pdf->Row(array('', '', 'VAN .............>', $van_monto));
							anexo_certificacion_compromiso_rrhh($pdf, $tipo_documento, $numero_orden, $fecha_orden, ++$pag, $field_datos['idtipos_documentos'], $modulo );
						}
					}
				}
			}
		}
		//	------------------------------------------------------------
		//	IMPRIMO LA CERTIFICACION DE COMPROMISO MODULO=1 Y FORMA  PREIMPRESA=SI
		//	------------------------------------------------------------
		elseif (in_array(1,$field_datos['modulo']) && $field_datos['forma_preimpresa']=="si") {
			$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
			$pdf->SetTopMargin(5);
			$pdf->SetLeftMargin(5);
			$pdf->SetAutoPageBreak(1, 2.5);
			$pag=0;
			//-----------
			$sql="SELECT nombre_institucion FROM configuracion";
			$query=mysql_query($sql);
			$rows=mysql_num_rows($query);
			if ($rows!=0) $field=mysql_fetch_array($query);
			$despachar=$field['nombre_institucion'];
			//-----------
			//OBTENGO LOS DATOS DE CABECERA Y LOS ENVIO A LA FUNCION PARA QUE LAS IMPRIMA
			$sql="SELECT 
					 o.numero_orden, 
					 o.fecha_orden, 
					 o.tipo, 
					 o.justificacion, 
					 o.numero_requisicion,
					 o.fecha_requisicion, 
					 o.exento, 
					 o.sub_total, 
					 o.total, 
					 o.idbeneficiarios,
					 o.impuesto,
					 o.descuento,
					 o.exento_original,
					 o.sub_total_original,
					 b.nombre AS Beneficiario,
					 b.rif, 
					 t.descripcion AS TipoDocumento,
					 t.modulo,
					 t.forma_preimpresa
			  FROM 
					orden_compra_servicio o 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
					INNER JOIN tipos_documentos t ON (o.tipo=t.idtipos_documentos)
			  WHERE 
					o.idorden_compra_servicio='".$id_orden_compra."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				$id_categoria_programatica=$field['idcategoria_programatica'];
				$numero=$field['numero_orden'];
				$fecha=$field['fecha_orden'];
				$tipo=$field['TipoDocumento'];
				$nombre=$field['Beneficiario'];
				$rif=$field['rif'];
				$justificacion=$field['justificacion'];
				$nrequisicion=$field['numero_requisicion'];
				$frequisicion=$field['fecha_requisicion'];
				$impuesto=number_format($field['impuesto'], 2, ',', '.');
				$total_general=number_format($field['total'], 2, ',', '.');
				$descuento=$field['descuento'];
				$valor_descuento=number_format($field['descuento'], 2, ',', '.');
				if ($descuento>0) {
					$agregar_descuento=1;
					$exento=number_format($field['exento_original'], 2, ',', '.');
					$sub_total=number_format($field['sub_total_original'], 2, ',', '.');
				} else { 
					$exento=number_format($field['exento'], 2, ',', '.');
					$sub_total=number_format($field['sub_total'], 2, ',', '.');
					$agregar_descuento=0;
				}
				
				$sql="SELECT partidas_orden_compra_servicio.idorden_compra_servicio, 
							 maestro_presupuesto.anio, 
							 tipo_presupuesto.denominacion AS TipoPresupuesto, 
							 fuente_financiamiento.denominacion AS FuenteFinanciamiento 
						FROM 
							 partidas_orden_compra_servicio, 
							 maestro_presupuesto, 
							 tipo_presupuesto, 
							 fuente_financiamiento 
						WHERE 
							 (partidas_orden_compra_servicio.idorden_compra_servicio='".$_GET['id_orden_compra']."' AND
							 partidas_orden_compra_servicio.idmaestro_presupuesto=maestro_presupuesto.idRegistro AND 
							 maestro_presupuesto.idtipo_presupuesto=tipo_presupuesto.idtipo_presupuesto AND
							 maestro_presupuesto.idfuente_financiamiento=fuente_financiamiento.idfuente_financiamiento)";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if ($rows!=0) {
					$field=mysql_fetch_array($query);
					$anio=$field['anio'];
					$tpresupuesto=$field['TipoPresupuesto'];
					$ffinanciamiento=$field['FuenteFinanciamiento'];			
				}
			}
			//OBTENGO LOS DATOS DEL CUERPO Y LOS IMPRIMO
			$sql="SELECT articulos_compra_servicio.idarticulos_servicios, 
						 articulos_compra_servicio.cantidad, 
						 articulos_compra_servicio.precio_unitario, 
						 articulos_compra_servicio.total, 
						 articulos_compra_servicio.exento, 
						 articulos_servicios.idunidad_medida, 
						 articulos_servicios.descripcion, 
						 unidad_medida.abreviado 
					FROM 
						 articulos_compra_servicio, 
						 articulos_servicios, 
						 unidad_medida 
					WHERE 
						 (articulos_compra_servicio.idorden_compra_servicio='".$_GET['id_orden_compra']."') AND
						 (articulos_compra_servicio.idarticulos_servicios=articulos_servicios.idarticulos_servicios) AND 
						 (articulos_servicios.idunidad_medida=unidad_medida.idunidad_medida)";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $pag=1;
			certificacion_rrhh_viaticos($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo);
			$pdf->SetY(76); $y=$pdf->GetY();
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query); //for($f=0;$f<5;$f++){
				$total=(int) $field[3];
				$pu=number_format($field[2], 2, ',', '.');
				if ($total==0) $total=number_format($field[4], 2, ',', '.'); else $total=number_format($field[3], 2, ',', '.');
				$y+=5;
				$pdf->SetAligns(array('C', 'R', 'C', 'L', 'R', 'R'));
				$pdf->SetWidths(array(10, 15, 15, 95, 35, 35));
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 9);
				$pdf->Row(array($i, $field[1], $field[7], utf8_decode($field[6]), $pu, $total));
				if ($y>230) {
					$pag++;
					certificacion_rrhh_viaticos($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo);
					$y=76; $pdf->SetY(76);
				}
			}//}
			$y=$pdf->GetY()+5;
			//IMPRIMO LOS TOTALES Y OBTENGO EL TIPO DE IMPUESTO
			$sql="SELECT relacion_impuestos_ordenes_compras.idimpuestos, 
						 impuestos.siglas, 
						 impuestos.porcentaje, 
						 relacion_impuestos_ordenes_compras.total 
					FROM 
						 relacion_impuestos_ordenes_compras, 
						 impuestos 
					WHERE 
						 (relacion_impuestos_ordenes_compras.idorden_compra_servicio='".$_GET['id_orden_compra']."') AND 
						 (relacion_impuestos_ordenes_compras.idimpuestos=impuestos.idimpuestos)";
			$query_impuestos=mysql_query($sql) or die ($sql.mysql_error());
			$nro_impuestos=mysql_num_rows($query_impuestos);
		
			//OBTENGO LAS PARTIDAS Y LAS IMPRIMO
			$sql="SELECT partidas_orden_compra_servicio.idmaestro_presupuesto,
						 partidas_orden_compra_servicio.monto, 
						 clasificador_presupuestario.denominacion, 
						 clasificador_presupuestario.partida, 
						 clasificador_presupuestario.generica, 
						 clasificador_presupuestario.especifica, 
						 clasificador_presupuestario.sub_especifica, 
						 maestro_presupuesto.idclasificador_presupuestario,  
						 categoria_programatica.codigo 
					FROM 
						partidas_orden_compra_servicio, 
						clasificador_presupuestario, 
						maestro_presupuesto,
						categoria_programatica
					WHERE 
						(partidas_orden_compra_servicio.idorden_compra_servicio='".$_GET['id_orden_compra']."') AND
						(partidas_orden_compra_servicio.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND
						(maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica)";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows_partidas=mysql_num_rows($query);
			
			$ycuadro=$y+((3+$nro_impuestos+$agregar_descuento)*5);
			if ($ycuadro>=245) {
				$pag++;
				certificacion_rrhh_viaticos($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo);
				if ($rows_partidas>6) $y=240-((3+$nro_impuestos)*5); else $y=205-((3+$nro_impuestos)*5);
			}
			else if ($ycuadro<=180) $y=180;
			else $y=200-((3+$nro_impuestos)*5);
			//-----------
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(150, 150, 150); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Rect(5.00125, $y-3, 205, 0.1);
			$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y); $pdf->MultiCell($w, l, $S.'EXENTO: ', 0, 'R'); 	
			$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y); $pdf->MultiCell($w, l, $exento, 0, 'R');
			$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, 'SUB-TOTAL:', 0, 'R'); 	
			$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, $sub_total, 0, 'R');
			if ($agregar_descuento==1) {
				$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y+10); $pdf->MultiCell($w, l, 'DESCUENTO:', 0, 'R'); 	
				$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y+10); $pdf->MultiCell($w, l, $valor_descuento, 0, 'R');
				$y+=5;
			}
			for ($z=0; $z<$nro_impuestos; $z++) {
				$field_impuestos=mysql_fetch_array($query_impuestos) or die (mysql_error());
				$porcentaje=number_format($field_impuestos['porcentaje'], 2, ',', '.');
				$siglas=$field_impuestos['siglas']." (".$porcentaje." %):";
				$impuesto=number_format($field_impuestos['total'], 2, ',', '.');
				$y+=5;
				$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, $siglas, 0, 'R'); 	
				$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, $impuesto, 0, 'R');
			}
			$y=$pdf->GetY();
			$pdf->SetFont('Arial', 'B', 12);
			$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, 'TOTAL:', 0, 'R'); 	
			$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, $total_general, 0, 'R');
			
			$y=$pdf->GetY();
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->SetXY(5, $y-10); $pdf->Cell(50, 5, utf8_decode('AÑO:  '.$anio), 0, 1, 'L');
			$pdf->SetXY(5, $y-5); $pdf->Cell(100, 5, 'TIPO DE PRESUPUESTO:  '.$tpresupuesto, 0, 1, 'L'); 
			$pdf->SetXY(5, $y); $pdf->Cell(100, 5, 'FUENTE DE FINANCIAMIENTO:  '.$ffinanciamiento, 0, 1, 'L');
			//-----------
			$y=$pdf->GetY();
			if ($rows_partidas>6 || $y>205) {
				$pag++;
				anexoordencs($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $modulo);
				$y=73;
			} else {
				$y=$pdf->GetY();
				$pdf->SetY($y);	
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(25, 5, 'CAT.PROGR.', 1, 0, 'C', 1);
				$pdf->Cell(25, 5, 'PARTIDA', 1, 0, 'C', 1);
				$pdf->Cell(110, 5, 'DESCRIPCION', 1, 0, 'C', 1);
				$pdf->Cell(45, 5, 'MONTO', 1, 0, 'C', 1);
			}
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 9); 
			for ($i=1; $i<=$rows_partidas; $i++) {
				$field=mysql_fetch_array($query);
				$y+=5;
				$partida=$field[3]." ".$field[4]." ".$field[5]." ".$field[6];
				$monto=number_format($field[1], 2, ',', '.');
				$descripcion=SUBSTR($field[2], 0, 65); 
				$h=5; $l=4; $x1=10.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $field['codigo'], 0, 'C');
				$h=5; $l=4; $x1=35.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $partida, 0, 'C');
				$h=5; $l=4; $x1=60.00125; $w1=110; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, utf8_decode($descripcion), 0, 'L');
				$h=5; $l=4; $x1=170.00125; $w1=45; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto, 0, 'R');
			}
		}		
		break;
		
	//	Listado...
	case "trabajadores_listado":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		//---------------------------------------------
		$i = 0;
		if ($chkcuenta == "true") $joincuenta = "INNER"; else $joincuenta = "LEFT";
		//---------------------------------------------
		trabajadores_listado($pdf);
		//---------------------------------------------
		$sql = "SELECT
					t.cedula,
					t.nombres,
					t.apellidos
				FROM
					trabajador t
					$joincuenta JOIN cuentas_bancarias_trabajador cbt ON (t.idtrabajador = cbt.idtrabajador)
				ORDER BY ".$ordenar;
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$i++;
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(number_format($field['cedula'], 0, '', '.'), 
							utf8_decode($field['nombres']).', '.utf8_decode($field['apellidos'])));
			$linea = $pdf->GetY(); if ($linea>250) { listatrab($pdf, $campos); }
		}
		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(195, 5, 'Total de Trabajadores: '.$i, 0, 0, 'L');
		break;


    //	Listado...
    case "trabajadores_constancia":
        $pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
        $pdf->Open();
        //---------------------------------------------
        $i = 0;
        if ($chkcuenta == "true") $joincuenta = "INNER"; else $joincuenta = "LEFT";
        //---------------------------------------------
        trabajadores_constancia($pdf);
        //---------------------------------------------
        $sql = "SELECT
					t.cedula,
					t.nombres,
					t.apellidos
				FROM
					trabajador t
				WHERE
				    idtrabajador = '".$idtrabajador."'
				";
        $query = mysql_query($sql) or die ($sql.mysql_error());
        while ($field = mysql_fetch_array($query)) {
            $i++;
            $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Row(array(number_format($field['cedula'], 0, '', '.'),
                utf8_decode($field['nombres']).', '.utf8_decode($field['apellidos'])));

        }
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(195, 5, 'Total de Trabajadores: '.$i, 0, 0, 'L');
        break;

    //	Relacion de Prestaciones e Intereses...
	case "lista_trabajadores_prestaciones":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();

		$campos = explode("|", $checks);

		$whe_estado='';
		if($estado=='activos') $whe_estado = " and t.activo_nomina='si'";
		if($estado=='egresados') $whe_estado = " and t.activo_nomina='no'";
		//	-------------------------
		if ($nomina != "") $where = "WHERE rtnt.idtipo_nomina = '".$nomina."'".$whe_estado;
		$orderby = "titulo_nomina, '".$ordenar."'";
		//	-------------------------


		$sql = "SELECT
					rtnt.*,
					tn.titulo_nomina,
					t.nro_ficha,
					t.apellidos,
					t.nombres,
					t.cedula,
					t.fecha_ingreso,
					c.denominacion AS nomcargo,
					no.denominacion AS nomunidad_funcional,
					ue.denominacion AS nomcentro_costo,
					cp.codigo AS codigo_centro
				FROM
					relacion_tipo_nomina_trabajador rtnt
					INNER JOIN trabajador t ON (rtnt.idtrabajador = t.idtrabajador)
					INNER JOIN tipo_nomina tn ON (rtnt.idtipo_nomina = tn.idtipo_nomina)
					INNER JOIN niveles_organizacionales no ON (t.idunidad_funcional = no.idniveles_organizacionales)
					INNER JOIN niveles_organizacionales cno ON (t.centro_costo = cno.idniveles_organizacionales)
					INNER JOIN categoria_programatica cp ON (cno.idcategoria_programatica = cp.idcategoria_programatica)
					INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					LEFT JOIN cargos c On (t.idcargo = c.idcargo)
				$where
				ORDER BY $ordenar";

		$query = mysql_query($sql) or die ($sql.mysql_error()); $i=1;
		while ($field = mysql_fetch_array($query)) {

			$idtrabajador  = $field["idtrabajador"];
			$fecha_ingreso = $field["fecha_ingreso"];

			if ($grupo != $field['idtipo_nomina']) {
				$grupo = $field['idtipo_nomina'];
				if ($i==1) head_trabajadores_prestaciones($pdf, $field['titulo_nomina'], $estado, $campos,
							$mes_prestaciones, $anio_prestaciones);
				else {
					$pdf->Ln(5);
					$pdf->SetFont('Arial', 'B', 10);
					$pdf->Cell(200, 4, utf8_decode($field['titulo_nomina']), 0, 1, 'C');
					$pdf->Ln(2);
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 8);
					if($campos[0] == 1 && $campos[1] == 1 && $campos[2] == 1){
						$pdf->SetAligns(array('C','C', 'C', 'C', 'C', 'C', 'C'));
						$pdf->SetWidths(array(10, 20, 70, 20, 28, 28, 28));
						$pdf->Row(array('No.',utf8_decode('Cédula'), 'Apellidos y Nombres', 'F. Ingreso', 'Prestaciones Acumuladas', 'Intereses Acumulados', 'Prestaciones + Intereses'));
						$pdf->SetAligns(array('C','R', 'L', 'C', 'R', 'R', 'R'));
					}
					if($campos[0] == 1 && $campos[1] == 0 && $campos[2] == 0){
						$pdf->SetAligns(array('C','C', 'C', 'C', 'C'));
						$pdf->SetWidths(array(10, 20, 126, 20, 28));
						$pdf->Row(array('No.',utf8_decode('Cédula'), 'Apellidos y Nombres', 'F. Ingreso', 'Prestaciones Acumuladas'));
						$pdf->SetAligns(array('C','R', 'L', 'C', 'R'));
					}
					if($campos[0] == 1 && $campos[1] == 1 && $campos[2] == 0){
						$pdf->SetAligns(array('C','C', 'C', 'C', 'C', 'C'));
						$pdf->SetWidths(array(10, 20, 98, 20, 28, 28));
						$pdf->Row(array('No.',utf8_decode('Cédula'), 'Apellidos y Nombres', 'F. Ingreso', 'Prestaciones Acumuladas', 'Intereses Acumulados'));
						$pdf->SetAligns(array('C','R', 'L', 'C', 'R', 'R'));
					}
					if($campos[0] == 1 && $campos[1] == 0 && $campos[2] == 1){
						$pdf->SetAligns(array('C','C', 'C', 'C', 'C', 'C'));
						$pdf->SetWidths(array(10, 20, 98, 20, 28, 28));
						$pdf->Row(array('No.',utf8_decode('Cédula'), 'Apellidos y Nombres', 'F. Ingreso', 'Prestaciones Acumuladas', 'Prestaciones + Intereses'));
						$pdf->SetAligns(array('C','R', 'L', 'C', 'R', 'R'));
					}
					if($campos[0] == 0 && $campos[1] == 1 && $campos[2] == 1){
						$pdf->SetAligns(array('C','C', 'C', 'C', 'C', 'C'));
						$pdf->SetWidths(array(10, 20, 98, 20, 28, 28));
						$pdf->Row(array('No.',utf8_decode('Cédula'), 'Apellidos y Nombres', 'F. Ingreso', 'Intereses Acumulados', 'Prestaciones + Intereses'));
						$pdf->SetAligns(array('C','R', 'L', 'C', 'R', 'R'));
					}
					if($campos[0] == 0 && $campos[1] == 1 && $campos[2] == 0){
						$pdf->SetAligns(array('C','C', 'C', 'C', 'C'));
						$pdf->SetWidths(array(10, 20, 126, 20, 28));
						$pdf->Row(array('No.',utf8_decode('Cédula'), 'Apellidos y Nombres', 'F. Ingreso', 'Intereses Acumulados'));
						$pdf->SetAligns(array('C','R', 'L', 'C', 'R'));
					}
					if($campos[0] == 0 && $campos[1] == 0 && $campos[2] == 1){
						$pdf->SetAligns(array('C','C', 'C', 'C', 'C'));
						$pdf->SetWidths(array(10, 20, 126, 20, 28));
						$pdf->Row(array('No.',utf8_decode('Cédula'), 'Apellidos y Nombres', 'F. Ingreso', 'Prestaciones + Intereses'));
						$pdf->SetAligns(array('C','R', 'L', 'C', 'R'));
					}
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Ln(4);
				}
			}

			//CALCULO LAS PRESTACIONES DEL TRABAJADOR

			$meses['01'] = "Ene";
			$meses['02'] = "Feb";
			$meses['03'] = "Mar";
			$meses['04'] = "Abr";
			$meses['05'] = "May";
			$meses['06'] = "Jun";
			$meses['07'] = "Jul";
			$meses['08'] = "Ago";
			$meses['09'] = "Sep";
			$meses[10] = "Oct";
			$meses[11] = "Nov";
			$meses[12] = "Dic";

			list($anioIngreso, $mesIngreso, $diaIngreso) = explode("-", $fecha_ingreso);


			$k                              = 0;
			$bandera                        = -1;
			$cont_meses                     = 0;
			$cont_anios                     = 0;
			$mostrar                        = false;
			$cuenta_meses                   = -1;
			$anio_totalizar                 = 0;
			$prestaciones_anuales           = 0;
			$intereses_anuales              = 0;
			$adelantos_prestaciones_anuales = 0;
			$adelantos_intereses_anuales    = 0;

			$sql_consulta = mysql_query("select * from tabla_prestaciones where idtrabajador = '".$idtrabajador."' order by anio, mes");

			list($anioIngreso, $mesIngreso, $diaIngreso) = explode("-", $fecha_ingreso);

			//BUCLE PARA IR REVISANDO CADA AÑO Y MES DE LA TABLA DE PRESTACIONES
		    while($bus_consulta = mysql_fetch_array($sql_consulta)){
		     	$dias_prestaciones = 0;
		     	$dias_adicionales = 0;
		     	$entra = 'no';

		     	//VALIDO QUE SE EJECUTE HASTA EL AÑO Y MES SELECCIONADO
		     	/*if($bus_consulta["anio"] < $anio_prestaciones ){
		     		$entra = 'si';
		     	}elseif($bus_consulta["anio"] == $anio_prestaciones && $bus_consulta["mes"] <= $mes_prestaciones){
		     		$entra = 'si';
		     	}

		     	if($entra == 'si'){*/
			     	$resultado_fecha = diferenciaEntreDosFechas($fecha_ingreso, $bus_consulta["anio"]."-".$bus_consulta["mes"]."-01");
					list($anioRegistro, $mesRegistro, $diaRegistro) = explode("|.|", $resultado_fecha);
					//CONTADOR DE LOS MESES QUE VAN TRANSCURRIENDO, LO UTILIZO PARA CONTROLAR SI LA APLICACION
					//DE LA LEY ES MENSUAL, TRIMESTRAL O ANUAL
					$cuenta_meses = $cuenta_meses + 1;

			     	$sql = "select * from leyes_prestaciones where anio_desde <= '".$bus_consulta["anio"]."'
			     												and anio_hasta >= '".$bus_consulta["anio"]."'
			     												";
			     	$sql_leyes = mysql_query($sql);

			     	//RECORRO LA TABLA DE LEYES PARA SABER CUAL APLICA AL AÑO Y MES DEL BUCLE DE LA TABLA DE PRESTACIONES
			     	while($bus_leyes = mysql_fetch_array($sql_leyes)){

			         	$anio_desde = $bus_leyes["anio_desde"];
			         	$mes_desde = $bus_leyes["mes_desde"];
			         	$anio_hasta = $bus_leyes["anio_hasta"];
			         	$mes_hasta = $bus_leyes["mes_hasta"];

			         	//$mes_inicio_prentaciones = $bus_leyes["mes_inicial_abono"];
			         	//ECHO " AÑO desde: ".$anio_desde." AÑO TABLA: ".$bus_consulta["anio"].'<BR>';
			         	//ECHO " MES desde: ".$mes_desde." MES TABLA: ".$bus_consulta["mes"].'<BR>';

			         	//echo $cuenta_meses."   ";
			         	//ECHO " MES REGISTRO ".$mesRegistro. " MES INICIA ABONO ".$bus_leyes["mes_inicial_abono"].'<BR>';


			         	//SI EL AÑO DE LA TABLA PRESTACIONES ESTA ENTRE LOS DOS RANGOS A ESTABLECIDOS EN LA LEY
			         	if($anio_desde < $bus_consulta["anio"] and $anio_hasta > $bus_consulta["anio"]){

			         		$ley = $bus_leyes["siglas"];
			         		//SI EL AÑO DE INGRESO ES MAYOR

			         		if (($anioIngreso >= $anio_desde
			         				and ($mesRegistro > (int)$bus_leyes["mes_inicial_abono"]
			         					and $anioRegistro == 0)) or ($anioRegistro > 0)){

			         			if($bus_leyes["tipo_abono"] == 'mensual' and $cuenta_meses == 1){
			         				$dias_prestaciones = $bus_leyes["valor_abono"];
			         				$cuenta_meses = 0;
			         			}
			         			if($bus_leyes["tipo_abono"] == 'trimestral' and $cuenta_meses == 3){
			         				$dias_prestaciones = $bus_leyes["valor_abono"];
			         				$cuenta_meses = 0;

			         			}
			         			if($bus_leyes["tipo_abono"] == 'anual' and $bus_leyes["calcula"] == 'final' and $anio_hasta == $bus_consulta["anio"] and $mes_hasta == $bus_consulta["mes"]){
			         				$dias_prestaciones = $anioRegistro * $bus_leyes["valor_abono"];
			         				$cuenta_meses = 0;

			         			}
			         			if ($bus_leyes["tipo_abono_adicional"] == 'anual' and $mesRegistro == 0){
			     					$dias_adicionales = $anioRegistro * $bus_leyes["valor_abono_adicional"];
			     					if($dias_adicionales > $bus_leyes["valor_tope_adicional"]){
			     						$dias_adicionales = $bus_leyes["valor_tope_adicional"];
			     					}
			         			}

			         		}
			     			if (($anioIngreso > $anio_desde
			     				and $anioRegistro == 0) or ($anioRegistro > 0)){

			         			if($bus_leyes["tipo_abono"] == 'mensual'
			         				and ($mesRegistro > (int)$bus_leyes["mes_inicial_abono"]
			         					and $anioRegistro == 0)){
			         				$dias_prestaciones = $bus_leyes["valor_abono"];
			         				$cuenta_meses = 0;
			         			}
			         			if($bus_leyes["tipo_abono"] == 'trimestral' and $cuenta_meses == 3){
			         				$dias_prestaciones = $bus_leyes["valor_abono"];
			         				$cuenta_meses = 0;

			         			}
			         			if($bus_leyes["tipo_abono"] == 'anual' and $bus_leyes["calcula"] == 'final' and $anio_hasta == $bus_consulta["anio"] and $mes_hasta == $bus_consulta["mes"]){
			         				$dias_prestaciones = $anioRegistro * $bus_leyes["valor_abono"];
			         				$cuenta_meses = 0;

			         			}
			         			if ($bus_leyes["tipo_abono_adicional"] == 'anual' and $mesRegistro == 0){
			     					$dias_adicionales = $anioRegistro * $bus_leyes["valor_abono_adicional"];
			     					if($dias_adicionales > $bus_leyes["valor_tope_adicional"]){
			     						$dias_adicionales = $bus_leyes["valor_tope_adicional"];
			     					}
			         			}
			         		}
			         	}

			         	if($anio_hasta == $bus_consulta["anio"] and $bus_consulta["mes"] <= $mes_hasta){
			         		//ECHO " AÑO HASTA: ".$anio_hasta." AÑO TABLA: ".$bus_consulta["anio"].'<BR>';
			         		//ECHO " MES HASTA: ".$mes_hasta." MES TABLA: ".$bus_consulta["mes"].'<BR>';
			         		$ley = $bus_leyes["siglas"];
			         		if (($anioIngreso >= $anio_desde and ($mesRegistro > (int)$bus_leyes["mes_inicial_abono"] and $anioRegistro == 0)) or ($anioRegistro > 0)){
			         			if($bus_leyes["tipo_abono"] == 'mensual' and $cuenta_meses == 1){
			         				$dias_prestaciones = $bus_leyes["valor_abono"];
			         				$cuenta_meses = 0;
			         			}
			         			if($bus_leyes["tipo_abono"] == 'trimestral' and $cuenta_meses == 3){
			         				$dias_prestaciones = $bus_leyes["valor_abono"];
			         				$cuenta_meses = 0;
			         			}
			         			if($bus_leyes["tipo_abono"] == 'anual' and $bus_leyes["calcula"] == 'final' and $anio_hasta == $bus_consulta["anio"] and $mes_hasta == $bus_consulta["mes"]){
			         				$dias_prestaciones = $anioRegistro * $bus_leyes["valor_abono"];
			         				$cuenta_meses = 0;

			         			}
			         			if ($bus_leyes["tipo_abono_adicional"] == 'anual' and $mesRegistro == 0){
			     					$dias_adicionales = $anioRegistro * $bus_leyes["valor_abono_adicional"];
			     					if($dias_adicionales > $bus_leyes["valor_tope_adicional"]){
			     						$dias_adicionales = $bus_leyes["valor_tope_adicional"];
			     					}
			     				}
			         		}
			         	}

			         	if($anio_desde == $bus_consulta["anio"] and $mes_desde <= $bus_consulta["mes"]){
			         		//ECHO " AÑO desde: ".$anio_desde." AÑO TABLA: ".$bus_consulta["anio"].'<BR>';
			         		//ECHO " MES desde: ".$mes_desde." MES TABLA: ".$bus_consulta["mes"].'<BR>';
			         		//ECHO " MES REGISTRO ".$mesRegistro. " MES INICIA ABONO ".$bus_leyes["mes_inicial_abono"].'<BR>';
			         		$ley = $bus_leyes["siglas"];
			         		if (($anioIngreso >= $anio_desde and ($mesRegistro > (int)$bus_leyes["mes_inicial_abono"] and $anioRegistro == 0)) or ($anioRegistro > 0)){
			         			if($bus_leyes["tipo_abono"] == 'mensual' and $cuenta_meses == 1){
			         				$dias_prestaciones = $bus_leyes["valor_abono"];
			         				$cuenta_meses = 0;
			         			}
			         			if($bus_leyes["tipo_abono"] == 'trimestral' and $cuenta_meses == 3){
			         				$dias_prestaciones = $bus_leyes["valor_abono"];
			         				$cuenta_meses = 0;
			         			}
			         			if($bus_leyes["tipo_abono"] == 'anual' and $bus_leyes["calcula"] == 'final' and $anio_hasta == $bus_consulta["anio"] and $mes_hasta == $bus_consulta["mes"]){
			         				$dias_prestaciones = $anioRegistro * $bus_leyes["valor_abono"];
			         				$cuenta_meses = 0;

			         			}
			         			if ($bus_leyes["tipo_abono_adicional"] == 'anual' and $mesRegistro == 0){
			     					$dias_adicionales = $anioRegistro * $bus_leyes["valor_abono_adicional"];
			     					if($dias_adicionales > $bus_leyes["valor_tope_adicional"]){
			     						$dias_adicionales = $bus_leyes["valor_tope_adicional"];
			     					}
			     				}
			         		}
			         	}
			        }
			        //FIN WHILE LEYES APLICADAS


			      	if ($dias_prestaciones > 0){
						$cuenta_meses = 0;
					}

			        if($cuenta_meses > 3) $cuenta_meses = 0;

			     	$mostrar = true;
					if($bandera == $mes_inicio_prentaciones){

						$mostrar = true;
						$sql_tasas = mysql_query("select * from tabla_intereses where mes = '".$bus_consulta["mes"]."' and anio = '".$bus_consulta["anio"]."'");
						$bus_tasas = mysql_fetch_array($sql_tasas);
						$sql_adelanto = mysql_query("select * from tabla_adelantos
														where
															idtabla_prestaciones = '".$bus_consulta["idtabla_prestaciones"]."'");
						$num_adelanto = mysql_num_rows($sql_adelanto);
						$bus_adelanto = mysql_fetch_array($sql_adelanto);

			            $ingreso_mensual = $bus_consulta["sueldo"] + $bus_consulta["otros_complementos"] + $bus_consulta["bono_vacacional"] + $bus_consulta["bono_fin_anio"];

						$prestaciones_del_mes = (($ingreso_mensual/30)*($dias_prestaciones+$dias_adicionales));
						$prestaciones_acumuladas += $prestaciones_del_mes;

						$interes_prestaciones_del_mes = (($prestaciones_del_mes*$bus_tasas["interes"])/100)/12;

						$prestacion_interes_acumulado = ($prestaciones_del_mes+$prestacion_interes_acumulado+$interes_prestaciones);

						$prestacion_interes_acumulado = $prestacion_interes_acumulado - ($adelanto_interes + $adelanto_prestaciones);

						//$interes_prestaciones = ($prestacion_interes_acumulado*30*$bus_tasas["interes"])/36000;
						//$interes_acumulado += ($prestacion_interes_acumulado*30*$bus_tasas["interes"])/36000;


						//CALCULO SIN CAPITALIZAR LOS INTERESES
						$interes_prestaciones = (($prestaciones_acumuladas*$bus_tasas["interes"])/100)/12;
						$interes_acumulado += (($prestaciones_acumuladas*$bus_tasas["interes"])/100)/12;

					}else{
						$k++;
						$bandera++;
					}

					$cont_meses++;
					$interes_acumulado = $interes_acumulado - $bus_adelanto["monto_interes"];
					$prestaciones_acumuladas = $prestaciones_acumuladas - $bus_adelanto["monto_prestaciones"];
					$adelanto_interes = $bus_adelanto["monto_interes"];
					$adelanto_prestaciones = $bus_adelanto["monto_prestaciones"];
					if($cont_meses == 11){
						$cont_anios++;
					}
					$anio_totalizar = $bus_consulta["anio"];
					$prestaciones_anuales += $prestaciones_del_mes;
					$intereses_anuales += $interes_prestaciones;
					$adelantos_prestaciones_anuales += $bus_adelanto["monto_prestaciones"];
					$adelantos_intereses_anuales += $bus_adelanto["monto_interes"];

				if($bus_consulta["anio"] == $anio_prestaciones && $bus_consulta["mes"] == $mes_prestaciones){
					break;
				}
			}

			$total_interes_prestaciones = $interes_acumulado + $prestaciones_acumuladas;

			//IMPRIMO LOS RESULTADOS

			if($campos[0] == 1 && $campos[1] == 1 && $campos[2] == 1){
				$pdf->Row(array($i,
							number_format($field['cedula'], 0, '', '.'),
							utf8_decode($field['apellidos'].', '.$field['nombres']),
							formatoFecha($field['fecha_ingreso']),
							number_format($prestaciones_acumuladas, 2, ',', '.'),
							number_format($interes_acumulado, 2, ',', '.'),
							number_format($total_interes_prestaciones, 2, ',', '.')));
			}
			if($campos[0] == 1 && $campos[1] == 0 && $campos[2] == 0){
				$pdf->Row(array($i,
							number_format($field['cedula'], 0, '', '.'),
							utf8_decode($field['apellidos'].', '.$field['nombres']),
							formatoFecha($field['fecha_ingreso']),
							number_format($prestaciones_acumuladas, 2, ',', '.')));
			}
			if($campos[0] == 1 && $campos[1] == 1 && $campos[2] == 0){
				$pdf->Row(array($i,
							number_format($field['cedula'], 0, '', '.'),
							utf8_decode($field['apellidos'].', '.$field['nombres']),
							formatoFecha($field['fecha_ingreso']),
							number_format($prestaciones_acumuladas, 2, ',', '.'),
							number_format($interes_acumulado, 2, ',', '.')));
			}
			if($campos[0] == 1 && $campos[1] == 0 && $campos[2] == 1){
				$pdf->Row(array($i,
							number_format($field['cedula'], 0, '', '.'),
							utf8_decode($field['apellidos'].', '.$field['nombres']),
							formatoFecha($field['fecha_ingreso']),
							number_format($prestaciones_acumuladas, 2, ',', '.'),
							number_format($total_interes_prestaciones, 2, ',', '.')));
			}
			if($campos[0] == 0 && $campos[1] == 1 && $campos[2] == 1){
				$pdf->Row(array($i,
							number_format($field['cedula'], 0, '', '.'),
							utf8_decode($field['apellidos'].', '.$field['nombres']),
							formatoFecha($field['fecha_ingreso']),
							number_format($interes_acumulado, 2, ',', '.'),
							number_format($total_interes_prestaciones, 2, ',', '.')));
			}
			if($campos[0] == 0 && $campos[1] == 1 && $campos[2] == 0){
				$pdf->Row(array($i,
							number_format($field['cedula'], 0, '', '.'),
							utf8_decode($field['apellidos'].', '.$field['nombres']),
							formatoFecha($field['fecha_ingreso']),
							number_format($interes_acumulado, 2, ',', '.')));
			}
			if($campos[0] == 0 && $campos[1] == 0 && $campos[2] == 1){
				$pdf->Row(array($i,
							number_format($field['cedula'], 0, '', '.'),
							utf8_decode($field['apellidos'].', '.$field['nombres']),
							formatoFecha($field['fecha_ingreso']),
							number_format($total_interes_prestaciones, 2, ',', '.')));
			}

			$pdf->Ln(2);
			$linea=$pdf->GetY(); if ($linea>260) head_trabajadores_prestaciones($pdf, $field['titulo_nomina'], $estado, $campos,
									$mes_prestaciones, $anio_prestaciones);
			$i++;
			$suma_prestaciones = $suma_prestaciones + $prestaciones_acumuladas;
			$suma_intereses = $suma_intereses + $interes_acumulado;
			$suma_sumatoria = $suma_sumatoria + $total_interes_prestaciones;
			$prestaciones_acumuladas = 0;
			$interes_acumulado = 0;
			$total_interes_prestaciones = 0;
		}

		$y=$pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
		$h=0.1; $x=5; $w=208; $pdf->Rect($x, $y+2, $w, $h);
		$pdf->Ln(5);
		$pdf->SetFont('Arial', 'B', 8);
		if($campos[0] == 1 && $campos[1] == 1 && $campos[2] == 1){
			$pdf->Cell(100, 0, "TOTAL DE TRABAJADORES: ".($i-1), 0, 1, 'L');
			$pdf->Cell(120,0);
			$pdf->Cell(28, 0, number_format($suma_prestaciones, 2, ',', '.'), 0, 1, 'R');
			$pdf->Cell(148,0);
			$pdf->Cell(28, 0, number_format($suma_intereses, 2, ',', '.'), 0, 1, 'R');
			$pdf->Cell(176,0);
			$pdf->Cell(28, 0, number_format($suma_sumatoria, 2, ',', '.'), 0, 1, 'R');
		}
		if($campos[0] == 1 && $campos[1] == 0 && $campos[2] == 0){
			$pdf->Cell(100, 0, "TOTAL DE TRABAJADORES: ".($i-1), 0, 1, 'L');
			$pdf->Cell(176,0);
			$pdf->Cell(28, 0, number_format($suma_prestaciones, 2, ',', '.'), 0, 1, 'R');
		}
		if($campos[0] == 1 && $campos[1] == 1 && $campos[2] == 0){
			$pdf->Cell(100, 0, "TOTAL DE TRABAJADORES: ".($i-1), 0, 1, 'L');
			$pdf->Cell(148,0);
			$pdf->Cell(28, 0, number_format($suma_prestaciones, 2, ',', '.'), 0, 1, 'R');
			$pdf->Cell(176,0);
			$pdf->Cell(28, 0, number_format($suma_intereses, 2, ',', '.'), 0, 1, 'R');
		}
		if($campos[0] == 1 && $campos[1] == 0 && $campos[2] == 1){
			$pdf->Cell(100, 0, "TOTAL DE TRABAJADORES: ".($i-1), 0, 1, 'L');
			$pdf->Cell(148,0);
			$pdf->Cell(28, 0, number_format($suma_prestaciones, 2, ',', '.'), 0, 1, 'R');
			$pdf->Cell(176,0);
			$pdf->Cell(28, 0, number_format($suma_sumatoria, 2, ',', '.'), 0, 1, 'R');
		}
		if($campos[0] == 0 && $campos[1] == 1 && $campos[2] == 1){
			$pdf->Cell(100, 0, "TOTAL DE TRABAJADORES: ".($i-1), 0, 1, 'L');
			$pdf->Cell(148,0);
			$pdf->Cell(28, 0, number_format($suma_intereses, 2, ',', '.'), 0, 1, 'R');
			$pdf->Cell(176,0);
			$pdf->Cell(28, 0, number_format($suma_sumatoria, 2, ',', '.'), 0, 1, 'R');
		}
		if($campos[0] == 0 && $campos[1] == 1 && $campos[2] == 0){
			$pdf->Cell(100, 0, "TOTAL DE TRABAJADORES: ".($i-1), 0, 1, 'L');
			$pdf->Cell(176,0);
			$pdf->Cell(28, 0, number_format($suma_intereses, 2, ',', '.'), 0, 1, 'R');
		}
		if($campos[0] == 0 && $campos[1] == 0 && $campos[2] == 1){
			$pdf->Cell(100, 0, "TOTAL DE TRABAJADORES: ".($i-1), 0, 1, 'L');
			$pdf->Cell(176,0);
			$pdf->Cell(28, 0, number_format($suma_sumatoria, 2, ',', '.'), 0, 1, 'R');
		}

		break;
}
//	----------------------------------------
//	FIN CUERPO DE LOS REPORTES
//	----------------------------------------

$pdf->Output();
?>