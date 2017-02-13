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
require('head.php');
require('../firmas.php');
require('../../mc_table.php');
require('../../mc_table2.php');
require('../../mc_table3.php');
require('../../mc_table4.php');
require('../../mc_table5.php');
require('../../mc_table6.php');
require('../../mc_table7.php');
require('../../mc_table8.php');
require('../../mc_table9.php');
require('../../../conf/conex.php');
//	----------------------------------------
Conectarse();
$ahora=date("d-m-Y H:i:s");
//	----------------------------------------
//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Beneficiarios...
	case "beneficiario":
		$pdf=new PDF_MC_Table('L', 'mm', 'Letter');
		$pdf->Open();
		//
		if ($_GET['tbene']!="0") {
			$sql="SELECT descripcion FROM tipo_beneficiario WHERE idtipo_beneficiario='".$_GET['tbene']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) { $field=mysql_fetch_array($query); $tipo=$field[0]; }
		}
		//
		beneficiario($pdf, $tipo);
		////////////
		$filtro="";
		if ($_GET['tbene']=="0") $filtro="idtipo_beneficiario<>'' AND "; else $filtro="idtipo_beneficiario='".$_GET['tbene']."' AND ";
		if ($_GET['ebene']=="0") $filtro.="idestado_beneficiario<>'' AND "; else $filtro.="idestado_beneficiario='".$_GET['ebene']."' AND ";
		if ($_GET['tpersona']=="0") $filtro.="idtipos_persona<>'' AND "; else $filtro.="idtipos_persona='".$_GET['tpersona']."' AND ";
		if ($_GET['tsoc']=="0") $filtro.="idtipo_sociedad<>'' AND "; else $filtro.="idtipo_sociedad='".$_GET['tsoc']."' AND ";
		if ($_GET['temp']=="0") $filtro.="idtipo_empresa<>''"; else $filtro.="idtipo_empresa='".$_GET['temp']."'";	
		$sql="SELECT rif, nombre, representante_legal, telefonos, email, direccion FROM beneficiarios WHERE (".$filtro.")";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array($field['rif'], utf8_decode($field['nombre']), utf8_decode($field['representante_legal']), $field['telefonos'], utf8_decode($field['direccion'])));
			$linea=$pdf->GetY(); if ($linea>175) { beneficiario($pdf, $tipo); $pdf->SetY(50); }
		}
		break;
			
	//	Documentos Entregados...
	case "dbeneficiario":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		dbeneficiario($pdf);
		////////////
		$filtro="";
		if ($_GET['tbene']=="0") $filtro="idtipo_beneficiario<>'' AND "; else $filtro="idtipo_beneficiario='".$_GET['tbene']."' AND ";
		if ($_GET['ebene']=="0") $filtro.="idestado_beneficiario<>'' AND "; else $filtro.="idestado_beneficiario='".$_GET['ebene']."' AND ";
		if ($_GET['tpersona']=="0") $filtro.="idtipos_persona<>'' AND "; else $filtro.="idtipos_persona='".$_GET['tpersona']."' AND ";
		if ($_GET['tsoc']=="0") $filtro.="idtipo_sociedad<>'' AND "; else $filtro.="idtipo_sociedad='".$_GET['tsoc']."' AND ";
		if ($_GET['temp']=="0") $filtro.="idtipo_empresa<>''"; else $filtro.="idtipo_empresa='".$_GET['temp']."'";	
		$sql="SELECT idbeneficiarios, rif, nombre FROM beneficiarios WHERE (".$filtro.") ORDER BY rif";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);		
			//
			$sql1="SELECT documentos_requeridos.descripcion, documento_entregado_beneficiario.fecha_emision, documento_entregado_beneficiario.fecha_vencimiento FROM documentos_requeridos, documento_entregado_beneficiario WHERE (documentos_requeridos.iddocumentos_requeridos=documento_entregado_beneficiario.iddocumentos_requeridos) AND (documento_entregado_beneficiario.idbeneficiarios='".$field[0]."')";
			$query1=mysql_query($sql1) or die ($sql1.mysql_error());
			$rows1=mysql_num_rows($query1);
			if ($rows1!=0) {				
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(60, 10, 'Rif./Cedula: '.$field[1], 0, 0, 'L');
				$pdf->Cell(130, 10, 'Nombre: '.utf8_decode($field[2]), 0, 1, 'L');
				//			
				$pdf->SetFont('Arial', 'BU', 8);			
				$pdf->Cell(15, 8, '', 0, 0, 'L');			
				$pdf->Cell(70, 8, 'DOCUMENTO', 0, 0, 'L');
				$pdf->Cell(30, 8, 'FECHA DE EMISION', 0, 0, 'L');
				$pdf->Cell(30, 8, 'FECHA DE VENCIMIENTO', 0, 1, 'L');	
			}
			for ($j=0; $j<$rows1; $j++) {
				$field1=mysql_fetch_array($query1);
				//					
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'L', 'L', 'L'));
				$pdf->SetWidths(array(15, 70, 30, 30));
				$pdf->Row(array('', utf8_decode($field1[0]), $field1[1], $field1[2]));
				$linea=$pdf->GetY(); if ($linea>250) { dbeneficiario($pdf); $pdf->SetY(45); }
			}
		}
		break;
		
	//	Ficha de Beneficiarios...
	case "ficha_beneficiarios":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		ficha_beneficiarios($pdf);
		$fecha=date("Y-m-d");
		if ($idbeneficiario!="") $filtro="AND (beneficiarios.idbeneficiarios='".$idbeneficiario."')";
		////////////
		$sql="SELECT 
					beneficiarios.idbeneficiarios, 
					beneficiarios.nombre, 
					beneficiarios.nro_expediente, 
					beneficiarios.rif, 
					beneficiarios.email, 
					beneficiarios.telefonos, 
					beneficiarios.direccion, 
					beneficiarios.url, beneficiarios.objeto, 
					beneficiarios.datos_registro, 
					beneficiarios.representante_legal, 
					beneficiarios.cedula_representante, 
					beneficiarios.telefono_representante, 
					beneficiarios.persona_autorizada, 
					beneficiarios.cedula_persona_autorizada, 
					beneficiarios.telefono_persona_autorizada, 
					tipo_beneficiario.descripcion AS TipoBeneficiario, 
					tipo_sociedad.descripcion AS TipoSociedad, 
					estado_beneficiario.descripcion AS EdoBeneficiario, 
					tipos_persona.descripcion AS TipoPersona, 
					tipo_empresa.descripcion AS TipoEmpresa, 
					beneficiarios.pre_requisitos 
				FROM 
					beneficiarios, 
					tipo_beneficiario, 
					tipo_sociedad, 
					estado_beneficiario, 
					tipos_persona, 
					tipo_empresa 
				WHERE 
					(beneficiarios.idtipo_beneficiario=tipo_beneficiario.idtipo_beneficiario 
					AND beneficiarios.idtipo_sociedad=tipo_sociedad.idtipo_sociedad 
					AND beneficiarios.idestado_beneficiario=estado_beneficiario.idestado_beneficiario 
					AND beneficiarios.idtipos_persona=tipos_persona.idtipos_persona 
					AND beneficiarios.idtipo_empresa=tipo_empresa.idtipo_empresa) $filtro";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$datos=mysql_fetch_array($query);
			if ($datos['pre_requisitos']=="1") $pre="SI"; else $pre="NO";
			//	-----------------------------
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetY(30);
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(25, 6, 'Nro. Expediente: '.$x, 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(175, 6, $datos['nro_expediente'], 0, 1, 'L');
			
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(15, 6, 'Nombre: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(185, 6, utf8_decode($datos['nombre']), 0, 1, 'L');
			
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(15, 6, 'Rif./C.I.: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(65, 6, $datos['rif'], 0, 0, 'L');
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(15, 6, 'Email: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(105, 6, $datos['email'], 0, 1, 'L');
			
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(15, 6, 'Telefonos: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(190, 6, $datos['telefonos'], 0, 1, 'L');
			
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(15, 6, 'Dirección: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->MultiCell(190, 4, utf8_decode($datos['direccion']), 0, 1, 'L');
			
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(15, 6, 'URL: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(190, 6, $datos['url'], 0, 1, 'L');
			
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(15, 6, 'Objeto: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->MultiCell(190, 4, utf8_decode($datos['objeto']), 0, 1, 'L');
			
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(25, 6, 'Datos Registro: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(170, 6, utf8_decode($datos['datos_registro']), 0, 1, 'L');
			
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(35, 6, 'Representante Legal: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(60, 6, utf8_decode($datos['representante_legal']), 0, 0, 'L');
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(35, 6, 'C.I Representante Legal: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(60, 6, $datos['cedula_representante'], 0, 1, 'L');
			
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(35, 6, 'Persona Autorizada: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(60, 6, utf8_decode($datos['persona_autorizada']), 0, 0, 'L');
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(35, 6, 'C.I Persona Autorizada: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(60, 6, $datos['cedula_persona_autorizada'], 0, 1, 'L');
			
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(35, 6, 'Tipo de Beneficiario: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(60, 6, utf8_decode($datos['TipoBeneficiario']), 0, 0, 'L');
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(35, 6, 'Tipo de Sociedad: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(60, 6, utf8_decode($datos['TipoSociedad']), 0, 1, 'L');
			
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(35, 6, 'Estado de Beneficiario: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(60, 6, utf8_decode($datos['EdoBeneficiario']), 0, 0, 'L');
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(35, 6, 'Tipo de Persona: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(60, 6, utf8_decode($datos['TipoPersona']), 0, 1, 'L');
			
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(35, 6, 'Tipo de Empresa: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(60, 6,utf8_decode( $datos['TipoEmpresa']), 0, 0, 'L');
			$pdf->SetFont('Arial', '', 8); $pdf->Cell(35, 6, 'Pre-Requisitos: ', 0, 0, 'L');
			$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(60, 6, $pre, 0, 1, 'L');
			
			$pdf->SetFont('Arial', 'B', 12); $pdf->Cell(190, 15, 'Documentos Entregados ', 0, 1, 'C');
			
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->Cell(40, 5, 'DOCUMENTO', 1, 0, 'C', 1);
			$pdf->Cell(30, 5, 'NRO. COMPR.', 1, 0, 'C', 1);
			$pdf->Cell(25, 5, 'EMISION', 1, 0, 'C', 1);
			$pdf->Cell(25, 5, 'VENCIMIENTO', 1, 0, 'C', 1);
			$pdf->Cell(60, 5, 'VERIFICADO', 1, 0, 'C', 1);	
			$pdf->Cell(20, 5, 'ESTADO', 1, 1, 'C', 1);
			$pdf->Ln(2);
			//	-----------------------------
			$sql="SELECT 
						documentos_requeridos.descripcion, 
						documento_entregado_beneficiario.nro_comprobante, 
						documento_entregado_beneficiario.fecha_emision, 
						documento_entregado_beneficiario.fecha_vencimiento, 
						documento_entregado_beneficiario.verificador_por 
					FROM 
						documentos_requeridos, 
						documento_entregado_beneficiario 
					WHERE 
						(documentos_requeridos.iddocumentos_requeridos=documento_entregado_beneficiario.iddocumentos_requeridos) 
						AND (documento_entregado_beneficiario.idbeneficiarios='".$datos['idbeneficiarios']."')";
			$query1=mysql_query($sql) or die ($sql.mysql_error());
			$rows1=mysql_num_rows($query1);
			for ($j=0; $j<$rows1; $j++) {
				$documentos=mysql_fetch_array($query1);
				if ($documentos['fecha_vencimiento']=="0000-00-00") $status="VIGENTE"; 
				else {
					if ($fecha>$documentos['fecha_vencimiento']) $status="VENCIDO"; else $status="VIGENTE";
				}
				list($a, $m, $d)=SPLIT( '[/.-]', $documentos['fecha_vencimiento']); $vencimiento=$d."/".$m."/".$a;
				list($a, $m, $d)=SPLIT( '[/.-]', $documentos['fecha_emision']); $emision=$d."/".$m."/".$a;
				//					
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('L', 'C', 'C', 'C', 'L', 'C'));
				$pdf->SetWidths(array(40, 30, 25, 25, 60, 20));
				$pdf->Row(array(utf8_decode($documentos['descripcion']), $documentos['nro_comprobante'].$x, $emision, $vencimiento, $documentos['verificador_por'], $status));
				$linea=$pdf->GetY(); if ($linea>250) { ficha_beneficiarios($pdf); $pdf->SetY(30); }
			}
			if ($i<$rows-1) ficha_beneficiarios($pdf); $pdf->SetY(45);
		}
		break;
		
	//	Relacion Documentacion Vencida...
	case "relacion_documentacion_vencida":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		relacion_documentacion_vencida($pdf);
		$fecha=date("Y-m-d");
		if ($idbeneficiario!="") $filtro="WHERE (beneficiarios.idbeneficiarios='".$idbeneficiario."')";
		////////////
		$sql="SELECT idbeneficiarios, nombre, rif, telefonos FROM beneficiarios $filtro";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$datos=mysql_fetch_array($query);
			//	-----------------------------
			$sql="SELECT 
						documentos_requeridos.descripcion, 
						documento_entregado_beneficiario.nro_comprobante, 
						documento_entregado_beneficiario.fecha_emision, 
						documento_entregado_beneficiario.fecha_vencimiento, 
						documento_entregado_beneficiario.verificador_por 
					FROM 
						documentos_requeridos, 
						documento_entregado_beneficiario 
					WHERE 
						(documentos_requeridos.iddocumentos_requeridos=documento_entregado_beneficiario.iddocumentos_requeridos) 
						AND (documento_entregado_beneficiario.idbeneficiarios='".$datos['idbeneficiarios']."') 
						AND ( DATEDIFF(documento_entregado_beneficiario.fecha_vencimiento, NOW())<0 )";
			$query1=mysql_query($sql) or die ($sql.mysql_error());
			$rows1=mysql_num_rows($query1);
			if ($rows1!=0) {
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 8); $pdf->Cell(15, 6, 'Nombre: ', 0, 0, 'L');
				$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(185, 6, utf8_decode($datos['nombre']), 0, 1, 'L');
				
				$pdf->SetFont('Arial', '', 8); $pdf->Cell(15, 6, 'Rif./C.I.: ', 0, 0, 'L');
				$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(65, 6, $datos['rif'], 0, 0, 'L');		
				$pdf->SetFont('Arial', '', 8); $pdf->Cell(15, 6, 'Telefonos: ', 0, 0, 'L');
				$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(65, 6, $datos['telefonos'], 0, 1, 'L');
				//	-----------------------------
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 6);
				$pdf->Cell(50, 5, 'DOCUMENTO', 1, 0, 'C', 1);
				$pdf->Cell(30, 5, 'NRO. COMPR.', 1, 0, 'C', 1);
				$pdf->Cell(25, 5, 'EMISION', 1, 0, 'C', 1);
				$pdf->Cell(25, 5, 'VENCIMIENTO', 1, 0, 'C', 1);
				$pdf->Cell(70, 5, 'VERIFICADO', 1, 1, 'C', 1);
				$pdf->Ln(2);
				//	-----------------------------
				for ($j=0; $j<$rows1; $j++) {
					$documentos=mysql_fetch_array($query1);
					
					list($a, $m, $d)=SPLIT( '[/.-]', $documentos['fecha_vencimiento']); $vencimiento=$d."/".$m."/".$a;
					list($a, $m, $d)=SPLIT( '[/.-]', $documentos['fecha_emision']); $emision=$d."/".$m."/".$a;
					//					
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 6);
					$pdf->SetAligns(array('L', 'C', 'C', 'C', 'L'));
					$pdf->SetWidths(array(50, 30, 25, 25, 70));
					$pdf->Row(array(utf8_decode($documentos['descripcion']), $documentos['nro_comprobante'].$x, $emision, $vencimiento, $documentos['verificador_por']));
					$linea=$pdf->GetY(); if ($linea>220) { relacion_documentacion_vencida($pdf); $pdf->SetY(30); }
				}
				$pdf->Ln(10);
				$y=$pdf->GetY();
				$pdf->SetDrawColor(0, 0, 0);
				$pdf->Rect(12, $y, 198, 0.1);
			}
		}
		break;
		
	//	Unidades de Medida...
	case "unidadm":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		unidadm($pdf);
		////////////
		$sql="SELECT descripcion, abreviado FROM unidad_medida ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0]), utf8_decode($field[1])));
			$linea=$pdf->GetY(); if ($linea>250) { unidadm($pdf); $pdf->SetY(45); }
		}
		break;
		
	//	Ramos de Materiales...
	case "ramos":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		ramos($pdf);
		////////////
		$sql="SELECT descripcion FROM ramos_articulos ORDER BY descripcion";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0])));
			$linea=$pdf->GetY(); if ($linea>250) { ramos($pdf); $pdf->SetY(45); }
		}
		break;
		
	//	Impuestos...
	case "impuestos":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		impuestos($pdf);
		////////////
		$sql="SELECT descripcion, siglas, porcentaje FROM impuestos ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0]), utf8_decode($field[1]), $field[2]));
			$linea=$pdf->GetY(); if ($linea>250) { impuestos($pdf); $pdf->SetY(45); }
		}
		break;
		
	//	Catalogo de Materiales...
	case "catalogo_materiales":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		if ($_SESSION['modulo']=="1" || $_SESSION['modulo']=="13") $titulo="Catálogo de Conceptos";
		elseif ($_SESSION['modulo']=="2") $titulo="Catálogo de Servicios";
		elseif ($_SESSION['modulo']=="3") $titulo="Catálogo de Materiales";
		elseif ($_SESSION['modulo']=="4") $titulo="Catálogo de Materiales";
		$filtro="";
		//---------------------------------------------------
		if ($tipo=="todas" && $ramo=="todas") $head=1;
		else if ($tipo!="todas" && $ramo!="todas") { $head=2; $where="AND (a.tipo='".$tipo."' AND a.idramo_articulo='".$ramo."')"; }
		else if ($tipo!="todas") { $head=3; $where="AND (a.tipo='".$tipo."')"; }
		else if ($ramo!="todas") { $head=4; $where="AND (a.idramo_articulo='".$ramo."')"; }
		//---------------------------------------------------
		if ($_SESSION['modulo']=="1" || $_SESSION['modulo']=="13") {
			if ($_SESSION['modulo']=='1' || $_SESSION['modulo']=='13') $or="OR modulo like '%-13-%' OR modulo like '%-1-%'"; else $or="";
			$sql="SELECT
					a.codigo,
					a.descripcion AS Articulo,
					u.descripcion AS Unidad,
					r.descripcion AS Ramo,
					t.descripcion AS Tipo,
					a.tipo_concepto
				  FROM 
					articulos_servicios a
					INNER JOIN unidad_medida u ON (a.idunidad_medida=u.idunidad_medida)
					INNER JOIN ramos_articulos r ON (a.idramo_articulo=r.idramo_articulo)
					INNER JOIN tipos_documentos t ON (a.tipo=t.idtipos_documentos)
				  WHERE (t.modulo like '%-".$_SESSION['modulo']."-%' $or) $where
				  ORDER BY a.descripcion";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			//---------------------------------------------------
			if ($head==1 || $head==4) {
				for ($i=0; $i<$rows; $i++) {
					$field=mysql_fetch_array($query);
					if ($head==4) $ramo=$field['Ramo']; else $ramo="";
					if ($field['tipo_concepto']=="1") $afecta="Asignación"; else $afecta="Deducción";
					if ($i==0) catalogo_materiales2($pdf, $head, "", $ramo, $titulo);
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
					$pdf->SetAligns(array('L', 'L', 'L', 'L', 'L'));			
					$pdf->SetWidths(array(35, 80, 60, 20));
					$pdf->Row(array(utf8_decode($field['codigo']), utf8_decode($field['Articulo']), utf8_decode($field['Tipo']), utf8_decode($afecta)));
					$y=$pdf->GetY(); if ($y>250) catalogo_materiales2($pdf, $head, "", $ramo, $titulo);
				}
			}
			//----------------------------------------------------
			elseif ($head==2 || $head==3) {
				for ($i=0; $i<$rows; $i++) {
					$field=mysql_fetch_array($query);
					if ($head==2) $ramo=$field['Ramo']; else $ramo="";
					if ($field['tipo_concepto']=="1") $afecta="Asignación"; else $afecta="Deducción";
					if ($i==0) catalogo_materiales2($pdf, $head, $field['Tipo'], $field['Ramo'], $titulo);
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
					$pdf->SetAligns(array('L', 'L', 'L'));			
					$pdf->SetWidths(array(35, 90, 20));
					$pdf->Cell(25, 5); $pdf->Row(array(utf8_decode($field['codigo']), utf8_decode($field['Articulo']), utf8_decode($afecta)));
					$y=$pdf->GetY(); if ($y>250) catalogo_materiales2($pdf, $head, $field['Tipo'], $ramo, $titulo);
				}
			}
		} else {
			$sql="SELECT
					a.codigo,
					a.descripcion AS Articulo,
					u.descripcion AS Unidad,
					r.descripcion AS Ramo,
					t.descripcion AS Tipo
				  FROM 
					articulos_servicios a
					INNER JOIN unidad_medida u ON (a.idunidad_medida=u.idunidad_medida)
					INNER JOIN ramos_articulos r ON (a.idramo_articulo=r.idramo_articulo)
					INNER JOIN tipos_documentos t ON (a.tipo=t.idtipos_documentos)
				  WHERE (t.modulo like '%-".$_SESSION['modulo']."-%') $where
				  ORDER BY a.descripcion";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			//---------------------------------------------------
			if ($head==1) {
				for ($i=0; $i<$rows; $i++) {
					$field=mysql_fetch_array($query);
					if ($i==0) catalogo_materiales($pdf, $head, "", "", $titulo);
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
					$pdf->SetAligns(array('L', 'L', 'L', 'L', 'L'));			
					$pdf->SetWidths(array(35, 70, 20, 15, 55));
					$pdf->Row(array(utf8_decode($field['codigo']), utf8_decode($field['Articulo']), utf8_decode($field['Unidad']), utf8_decode($field['Tipo']), utf8_decode($field['Ramo'])));
					$y=$pdf->GetY(); if ($y>250) catalogo_materiales($pdf, $head, "", "", $titulo);
				}
			}
			//----------------------------------------------------
			elseif ($head==2) {
				for ($i=0; $i<$rows; $i++) {
					$field=mysql_fetch_array($query);
					if ($i==0) catalogo_materiales($pdf, $head, $field['Tipo'], $field['Ramo'], $titulo);
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
					$pdf->SetAligns(array('L', 'L', 'L'));			
					$pdf->SetWidths(array(35, 90, 20));
					$pdf->Cell(25, 5); $pdf->Row(array(utf8_decode($field['codigo']), utf8_decode($field['Articulo']), utf8_decode($field['Unidad'])));
					$y=$pdf->GetY(); if ($y>250) catalogo_materiales($pdf, $head, $field['Tipo'], $field['Ramo'], $titulo);
				}
			}
			//----------------------------------------------------
			elseif ($head==3) {
				for ($i=0; $i<$rows; $i++) {
					$field=mysql_fetch_array($query);
					if ($i==0) catalogo_materiales($pdf, $head,  $field['Tipo'], "", $titulo);
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
					$pdf->SetAligns(array('L', 'L', 'L', 'L'));			
					$pdf->SetWidths(array(35, 75, 20, 65));
					$pdf->Row(array(utf8_decode($field['codigo']), utf8_decode($field['Articulo']), utf8_decode($field['Unidad']), utf8_decode($field['Ramo'])));
					$y=$pdf->GetY(); if ($y>250) catalogo_materiales($pdf, $head,  $field['Tipo'], "", $titulo);
				}
			}
			//----------------------------------------------------
			elseif ($head==4) {
				for ($i=0; $i<$rows; $i++) {
					$field=mysql_fetch_array($query);
					if ($i==0) catalogo_materiales($pdf, $head, "", $field['Ramo'], $titulo);
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
					$pdf->SetAligns(array('L', 'L', 'L', 'L'));			
					$pdf->SetWidths(array(35, 95, 20, 15));
					$pdf->Cell(15, 5); $pdf->Row(array(utf8_decode($field['codigo']), utf8_decode($field['Articulo']), utf8_decode($field['Unidad']), utf8_decode($field['Tipo'])));
					$y=$pdf->GetY(); if ($y>250) catalogo_materiales($pdf, $head, "", $field['Ramo'], $titulo);
				}
			}
		}
		break;
		
	//	SNC Actividades...
	case "sncactividad":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		sncactividad($pdf);
		////////////
		$sql="SELECT descripcion, sigla FROM snc_actividades ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0]), utf8_decode($field[1])));
			$linea=$pdf->GetY(); if ($linea>250) { sncactividad($pdf); }
		}
		break;
		
	//	SNC Familia...
	case "sncfamilia":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		sncfamilia($pdf);
		////////////
		$sql="SELECT snc_familia_actividad.codigo, 
					 snc_familia_actividad.descripcion, 
					 snc_actividades.descripcion 
				FROM 
					 snc_familia_actividad, 
					 snc_actividades 
				WHERE 
					 (snc_familia_actividad.idsnc_actividades=snc_actividades.idsnc_actividades) 
				ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array($field[2], utf8_decode($field[1]), utf8_decode($field[0])));
			$linea=$pdf->GetY(); if ($linea>250) { sncfamilia($pdf); }
		}
		break;
		
	//	SNC Grupo...
	case "sncgrupo":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		sncgrupo($pdf);
		////////////
		$sql="SELECT codigo, descripcion FROM snc_grupo_actividad ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array($field[0], utf8_decode($field[1])));
			$linea=$pdf->GetY(); if ($linea>250) { sncgrupo($pdf); }
		}
		break;
		
	//	SNC Detalle...
	case "sncdetalle":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		sncdetalle($pdf);
		////////////
		$sql="SELECT snc_grupo_actividad.descripcion, 
					 snc_detalle_grupo.descripcion, 
					 snc_detalle_grupo.codigo 
				FROM 
					 snc_grupo_actividad, 
					 snc_detalle_grupo 
				WHERE 
					 (snc_grupo_actividad.idsnc_grupo_actividad=snc_detalle_grupo.idsnc_grupo_actividad) 
				ORDER BY ".$_GET["orden"]."";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0]), utf8_decode($field[1]), $field[2]));
			$linea=$pdf->GetY(); if ($linea>225) { sncdetalle($pdf); }
		}
		break;
		
	//	SNC...
	case "snc":
		$pdf=new PDF_MC_Table2('P', 'mm', 'Letter');
		$pdf->Open();
		snc($pdf);
		////////////
		if ($_GET['act']=="0") $sql="SELECT idsnc_actividades, descripcion FROM snc_actividades ORDER BY idsnc_actividades";
		else $sql="SELECT idsnc_actividades, descripcion FROM snc_actividades WHERE (idsnc_actividades='".$_GET['act']."') ORDER BY idsnc_actividades";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(100, 100, 100); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetHeight(array(4));
			$pdf->SetFont('Arial', '', 6);
			$pdf->Row(array(utf8_decode($field[1])));
			$linea=$pdf->GetY(); if ($linea>250) { snc($pdf); $pdf->SetY(45); }
			$pdf->Cell(190, 3, '', 0, 1);
			//--		
			if ($_GET['fam']=="0") $sql1="SELECT idsnc_familia_actividad, codigo, descripcion FROM snc_familia_actividad WHERE (idsnc_actividades='".$field[0]."') ORDER BY codigo";
			else $sql1="SELECT idsnc_familia_actividad, codigo, descripcion FROM snc_familia_actividad WHERE (idsnc_familia_actividad='".$_GET['fam']."') AND (idsnc_actividades='".$field[0]."') ORDER BY codigo";
			$query1=mysql_query($sql1) or die ($sql1.mysql_error());
			$rows1=mysql_num_rows($query1);
			for ($j=0; $j<$rows1; $j++) {
				$field1=mysql_fetch_array($query1);
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(225, 225, 225); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetHeight(array(4));
				$pdf->SetFont('Arial', '', 6);
				$familia="     ".$field1[1]."  ".$field1[2];
				$pdf->Row(array(utf8_decode($familia)));
				$linea=$pdf->GetY(); if ($linea>250) { snc($pdf); $pdf->SetY(45); }
				$pdf->Cell(190, 3, '', 0, 1);
				//--			
				if ($_GET['gru']=="0") $sql2="SELECT idsnc_grupo_actividad, codigo, descripcion FROM snc_grupo_actividad WHERE (idsnc_familia_actividad='".$field1[0]."') ORDER BY codigo";
				else $sql2="SELECT idsnc_grupo_actividad, codigo, descripcion FROM snc_grupo_actividad WHERE (idsnc_grupo_actividad='".$_GET['gru']."') AND (idsnc_familia_actividad='".$field1[0]."') ORDER BY codigo";
				$query2=mysql_query($sql2) or die ($sql2.mysql_error());
				$rows2=mysql_num_rows($query2);
				for ($k=0; $k<$rows2; $k++) {
					$field2=mysql_fetch_array($query2);
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetHeight(array(4));
					$pdf->SetFont('Arial', 'B', 6);
					$grupo="     "."     ".$field2[1]."  ".$field2[2];
					$pdf->Row(array(utf8_decode($grupo)));
					$linea=$pdf->GetY(); if ($linea>250) { snc($pdf); $pdf->SetY(45); }
					$pdf->Cell(190, 3, '', 0, 1);
					//--				
					$sql3="SELECT idsnc_detalle_grupo, codigo, descripcion FROM snc_detalle_grupo WHERE (idsnc_grupo_actividad='".$field2[0]."') ORDER BY codigo";
					$query3=mysql_query($sql3) or die ($sql3.mysql_error());
					$rows3=mysql_num_rows($query3);
					for ($l=0; $l<$rows3; $l++) {
						$field3=mysql_fetch_array($query3);
						$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
						$pdf->SetHeight(array(3));
						$pdf->SetFont('Arial', '', 6);
						$detalle="     "."     "."     "."     ".$field3[1]."  ".$field3[2];
						$pdf->Row(array(utf8_decode($detalle)));
						$linea=$pdf->GetY(); if ($linea>250) { snc($pdf); }
					}
					$pdf->Cell(190, 3, '', 0, 1);
				}
			}		
		}
		break;
		
	//	Orden de Compra/Servicio...
	case "filtro_orden_compra_servicio":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$filtro=""; $dbeneficiario=0; $dcategoria=0; $dperiodo=1; $dtipo=0; $darticulo=0; $destado=0; $head=0;
		////////////
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
						 orden_compra_servicio.justificacion, 
						 beneficiarios.nombre, 
						 tipos_documentos.descripcion, 
						 categoria_programatica.idcategoria_programatica, 
						 categoria_programatica.codigo, 
						 unidad_ejecutora.denominacion, 
						 articulos_compra_servicio.idarticulos_servicios, 
						 articulos_servicios.descripcion As articulo, 
						 orden_compra_servicio.estado, 
						 articulos_compra_servicio.precio_unitario
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
						 (tipos_documentos.modulo like '%-".$_SESSION['modulo']."-%') AND 
						 (orden_compra_servicio.idorden_compra_servicio=articulos_compra_servicio.idorden_compra_servicio AND
						 articulos_compra_servicio.idarticulos_servicios=articulos_servicios.idarticulos_servicios) $filtro  
					ORDER BY orden_compra_servicio.codigo_referencia, beneficiarios.nombre";
		elseif ($_GET['idarticulo']!="")
			$sql="SELECT orden_compra_servicio.nro_factura, 
						 orden_compra_servicio.codigo_referencia, 
						 orden_compra_servicio.idorden_compra_servicio,
						 orden_compra_servicio.numero_orden, 
						 orden_compra_servicio.fecha_orden, 
						 orden_compra_servicio.estado,
						 orden_compra_servicio.justificacion, 
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
						 (tipos_documentos.modulo like '%-".$_SESSION['modulo']."-%') AND 
						 (orden_compra_servicio.idorden_compra_servicio=articulos_compra_servicio.idorden_compra_servicio AND
						 articulos_compra_servicio.idarticulos_servicios=articulos_servicios.idarticulos_servicios) $filtro  
					ORDER BY orden_compra_servicio.codigo_referencia, beneficiarios.nombre";
		else
			$sql="SELECT orden_compra_servicio.nro_factura, 
						 orden_compra_servicio.idorden_compra_servicio, 
						 orden_compra_servicio.codigo_referencia, 
						 orden_compra_servicio.numero_orden, 
						 orden_compra_servicio.fecha_orden, 
						 orden_compra_servicio.estado,
						 orden_compra_servicio.justificacion, 
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
						 (tipos_documentos.modulo like '%-".$_SESSION['modulo']."-%') $filtro 
					ORDER BY orden_compra_servicio.codigo_referencia, beneficiarios.nombre";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//---------------------------------------------------
		if ($head==1) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);				
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
				$total=number_format($field['total'], 2, ',', '.'); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_orden_compra_servicio($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('L', 'C', 'L', 'C', 'L', 'L', 'L', 'R'));
				$pdf->SetWidths(array(20, 15, 15, 20, 35, 40, 35, 20));
				$pdf->Row(array($field['numero_orden'], $fecha, $field['nro_factura'], $estado, utf8_decode($field['descripcion']), utf8_decode($field['nombre']), utf8_decode($field['justificacion']), $total));
				$y=$pdf->GetY(); if ($y>230) filtro_orden_compra_servicio($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, ""); 
			}
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(200, 5, number_format($sum_sub_total, 2, ',', '.'), 0, 1, 'R');
		}
		//----------------------------------------------------
		elseif ($head==2) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);				
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
				$total=number_format($field['total'], 2, ',', '.'); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_orden_compra_servicio($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('L', 'C', 'L', 'C', 'L', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 20, 25, 25, 60, 25));
				$pdf->Row(array($field['numero_orden'], $fecha, $field['nro_factura'], $estado, utf8_decode($field['descripcion']), utf8_decode($field['justificacion']), $total));
				$y=$pdf->GetY(); if ($y>250) filtro_orden_compra_servicio($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
			}
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(200, 5, number_format($sum_sub_total, 2, ',', '.'), 0, 1, 'R');
		}
		//----------------------------------------------------
		elseif ($head==3) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
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
				$total=number_format($field['total'], 2, ',', '.'); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_orden_compra_servicio($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('L', 'C', 'L', 'C', 'L', 'L', 'R'));
				$pdf->SetWidths(array(20, 15, 15, 20, 55, 55, 20));
				$pdf->Row(array($field['numero_orden'], $fecha, $field['nro_factura'], $estado, utf8_decode($field['nombre']), utf8_decode($field['justificacion']), $total));
				$y=$pdf->GetY(); if ($y>250) filtro_orden_compra_servicio($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
			}
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(200, 5, number_format($sum_sub_total, 2, ',', '.'), 0, 1, 'R');
		}
		//----------------------------------------------------
		elseif ($head==4) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
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
				$total=number_format($field['total'], 2, ',', '.'); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_orden_compra_servicio($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('L', 'C', 'L', 'C', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 20, 25, 85, 25));
				$pdf->Row(array($field['numero_orden'], $fecha, $field['nro_factura'], $estado, utf8_decode($field_ubicacion_actual['dependencia']), $total));
				$y=$pdf->GetY(); if ($y>250) filtro_orden_compra_servicio($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
			}
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(200, 5, number_format($sum_sub_total, 2, ',', '.'), 0, 1, 'R');
		}
		//----------------------------------------------------
		elseif ($head==5) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
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
				$total=number_format($field['total'], 2, ',', '.'); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_orden_compra_servicio($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('L', 'C',  'L', 'L', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 20, 55, 55, 25));
				$pdf->Row(array($field['numero_orden'], $fecha, $field['nro_factura'], utf8_decode($field['nombre']), utf8_decode($field['justificacion']), $total));
				$y=$pdf->GetY(); if ($y>250) filtro_orden_compra_servicio($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
			}
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(200, 5, number_format($sum_sub_total, 2, ',', '.'), 0, 1, 'R');
		}
		//----------------------------------------------------
		elseif ($head==6) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
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
				$total=number_format($field['total'], 2, ',', '.'); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_orden_compra_servicio($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('L', 'C', 'L', 'L', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 20, 20, 90, 25));
				$pdf->Row(array($field['numero_orden'], $fecha, $field['nro_factura'], utf8_decode($field['descripcion']), utf8_decode($field['justificacion']), $total));
				$y=$pdf->GetY(); if ($y>250) filtro_orden_compra_servicio($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
			}
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(200, 5, number_format($sum_sub_total, 2, ',', '.'), 0, 1, 'R');
		}
		//----------------------------------------------------
		elseif ($head==7) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
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
				$total=number_format($field['total'], 2, ',', '.'); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_orden_compra_servicio($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('L', 'C', 'L', 'L', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 20, 55, 55, 25));
				$pdf->Row(array($field['numero_orden'], $fecha, $field['nro_factura'], utf8_decode($field['nombre']), utf8_decode($field['justificacion']), $total));
				$y=$pdf->GetY(); if ($y>250) filtro_orden_compra_servicio($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
			}
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(10, 5, number_format($rows, 2, ',', '.'), 0, 1, 'R');
			$pdf->Cell(200, 5, number_format($sum_sub_total, 2, ',', '.'), 0, 1, 'R');
		}
		//----------------------------------------------------
		elseif ($head==8) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
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
				$total=number_format($field['total'], 2, ',', '.'); $sum_total += $field['total']; $sum_sub_total += $field['total'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_orden_compra_servicio($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('L', 'C', 'L', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 20, 55, 25));
				$pdf->Row(array($field['numero_orden'], $fecha, $field['nro_factura'], utf8_decode($field['justificacion']), $total));
				$y=$pdf->GetY(); if ($y>250) filtro_orden_compra_servicio($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
			}
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(145, 5, number_format($sum_sub_total, 2, ',', '.'), 0, 1, 'R');
		}
		//----------------------------------------------------
		elseif ($head==9) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
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
				$precio_unitario=number_format($field['precio_unitario'], 2, ',', '.'); $sum_total += $field['precio_unitario']; $sum_sub_total += $field['precio_unitario'];
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_orden_compra_servicio($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 6);
				$pdf->SetAligns(array('L', 'C', 'L', 'C', 'L', 'L', 'L', 'R'));
				$pdf->SetWidths(array(20, 15, 20, 20, 20, 45, 45, 15));
				$pdf->Row(array($field['numero_orden'], $fecha, $field['nro_factura'], $estado, utf8_decode($field['descripcion']), utf8_decode($field['nombre']), utf8_decode($field['justificacion']), $precio_unitario));
				$y=$pdf->GetY(); if ($y>250) filtro_orden_compra_servicio($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
			}
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(200, 5, number_format($sum_sub_total, 2, ',', '.'), 0, 1, 'R');
		}
		break;
	
	//	Totales por Proveedor...
	case "totales_por_proveedor":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		totales_por_proveedor($pdf);
		////////////
		
		$sql="SELECT    beneficiarios.rif,
						beneficiarios.nombre,
          				sum(orden_compra_servicio.total) as total
					FROM
						 orden_compra_servicio,
						 tipos_documentos,
						 beneficiarios
					WHERE
						 (orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios) AND
						 (orden_compra_servicio.tipo=tipos_documentos.idtipos_documentos) AND
             			 (orden_compra_servicio.estado='pagado' or
						  orden_compra_servicio.estado='parcial' or
						  orden_compra_servicio.estado='ordenado') AND
						 (tipos_documentos.modulo like '%-".$_SESSION['modulo']."-%')
          GROUP BY beneficiarios.idbeneficiarios
          ORDER BY beneficiarios.nombre";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			$total=number_format($field['total'], 2, ',', '.'); 
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Row(array(utf8_decode($field[0]), utf8_decode($field[1]), $total));
			$linea=$pdf->GetY(); if ($linea>250) { totales_por_proveedor($pdf); $pdf->SetY(45); }
		}
		break;
	
	
	//	Solicitud de Cotizaciones...
	case "filtro_solicitud_cotizacion_com":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$filtro=""; $dbeneficiario=0; $dcategoria=0; $dperiodo=1; $dtipo=0; $darticulo=0; $destado=0; $head=0;
		////////////
		if ($_GET['idbeneficiario']!="") { $filtro=" AND (beneficiarios.idbeneficiarios='".$_GET['idbeneficiario']."') "; $dbeneficiario=1; $head=2; }
		if ($_GET['idarticulo']!="") { 
			$filtro.=" AND (articulos_solicitud_cotizacion.idarticulos_servicios='".$_GET['idarticulo']."') "; $darticulo=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['desde']!="" && $_GET['hasta']!="") {
			list($d, $m, $a)=SPLIT( '[/.-]', $_GET['desde']); $fecha_desde=$a."-".$m."-".$d;
			list($d, $m, $a)=SPLIT( '[/.-]', $_GET['hasta']); $fecha_hasta=$a."-".$m."-".$d;
			$filtro.=" AND (solicitud_cotizacion.fecha_solicitud>='".$fecha_desde."' AND solicitud_cotizacion.fecha_solicitud<='".$fecha_hasta."') "; $dperiodo=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['idtipo']!="0") { 
			$filtro.=" AND (tipos_documentos.idtipos_documentos='".$_GET['idtipo']."') "; $dtipo=1;
			if ($dbeneficiario==1) $head=4; else $head=3;
		}
		if ($_GET['idestado']!="0") { 
			$filtro.=" AND (solicitud_cotizacion.estado='".$_GET['idestado']."') "; $destado=1;
			if ($head==0) $head=5;
			elseif ($head==2) $head=6;
			elseif ($head==3) $head=7;
			elseif ($head==4) $head=8;
		}
		if ($filtro=="") $head=1;
		////////////	
		if ($_GET['idarticulo']!="")
			$sql="SELECT 
						solicitud_cotizacion.numero, 
						solicitud_cotizacion.fecha_solicitud, 
						solicitud_cotizacion.estado, 
						proveedores_solicitud_cotizacion.idbeneficiarios, 
						beneficiarios.nombre, 
						tipos_documentos.descripcion, 
						articulos_solicitud_cotizacion.idarticulos_servicios 
					FROM 
						solicitud_cotizacion, 
						proveedores_solicitud_cotizacion, 
						beneficiarios, 
						tipos_documentos, 
						articulos_solicitud_cotizacion 
					WHERE 
						(solicitud_cotizacion.idsolicitud_cotizacion=proveedores_solicitud_cotizacion.idsolicitud_cotizacion) 
						AND (proveedores_solicitud_cotizacion.idbeneficiarios=beneficiarios.idbeneficiarios) 
						AND (solicitud_cotizacion.tipo=tipos_documentos.idtipos_documentos) 
						AND (solicitud_cotizacion.idsolicitud_cotizacion=articulos_solicitud_cotizacion.idsolicitud_cotizacion) 
						AND (tipos_documentos.modulo like '%-3-%') $filtro 
					ORDER BY solicitud_cotizacion.numero";
		else
			$sql="SELECT 
						solicitud_cotizacion.numero, 
						solicitud_cotizacion.fecha_solicitud, 
						solicitud_cotizacion.estado,
						proveedores_solicitud_cotizacion.idbeneficiarios, 
						beneficiarios.nombre, 
						tipos_documentos.descripcion 
					FROM 
						solicitud_cotizacion, 
						proveedores_solicitud_cotizacion, 
						beneficiarios, 
						tipos_documentos 
					WHERE 
						(solicitud_cotizacion.idsolicitud_cotizacion=proveedores_solicitud_cotizacion.idsolicitud_cotizacion) 
						AND (proveedores_solicitud_cotizacion.idbeneficiarios=beneficiarios.idbeneficiarios) 
						AND (solicitud_cotizacion.tipo=tipos_documentos.idtipos_documentos) 
						AND (tipos_documentos.modulo like '%-3-%') $filtro 
					ORDER BY solicitud_cotizacion.numero";	
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//---------------------------------------------------
		if ($head==1) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, "", $_GET['desde'], $_GET['hasta'], "", $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'C', 'L', 'L'));
				$pdf->SetWidths(array(25, 20, 25, 45, 80));
				$pdf->Row(array($field['numero'], $fecha, $estado, utf8_decode($field['descripcion']), utf8_decode($field['nombre'])));
			}
		}
		//----------------------------------------------------
		elseif ($head==2) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, $beneficiario, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'C', 'L'));
				$pdf->SetWidths(array(25, 20, 25, 125));
				$pdf->Row(array($field['numero'], $fecha, $estado, utf8_decode($field['descripcion'])));
			}
		}
		//----------------------------------------------------
		elseif ($head==3) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, "", $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'C', 'L'));
				$pdf->SetWidths(array(25, 20, 25, 125));
				$pdf->Row(array($field['numero'], $fecha, $estado, utf8_decode($field['nombre'])));
			}
		}
		//----------------------------------------------------
		elseif ($head==4) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, $beneficiario, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'C'));
				$pdf->SetWidths(array(25, 20, 25));
				$pdf->Row(array($field['numero'], $fecha, $estado));
			}
		}
		//----------------------------------------------------
		elseif ($head==5) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, "", $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'L', 'L'));
				$pdf->SetWidths(array(25, 20, 45, 80));
				$pdf->Row(array($field['numero'], $fecha, utf8_decode($field['descripcion']), utf8_decode($field['nombre'])));
			}
		}
		//----------------------------------------------------
		elseif ($head==6) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, $beneficiario, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'L'));
				$pdf->SetWidths(array(25, 20, 125));
				$pdf->Row(array($field['numero'], $fecha, utf8_decode($field['descripcion'])));
			}
		}
		//----------------------------------------------------
		elseif ($head==7) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, "", $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'L'));
				$pdf->SetWidths(array(25, 20, 125));
				$pdf->Row(array($field['numero'], $fecha, utf8_decode($field['nombre'])));
			}
		}
		//----------------------------------------------------
		elseif ($head==8) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_solicitud']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_solicitud_cotizacion($pdf, $head, $beneficiario, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C'));
				$pdf->SetWidths(array(25, 20));
				$pdf->Row(array($field['numero'], $fecha));
			}
		}
		break;
		
	//	Requisicion de Compras/Servicios...
	case "filtro_requisicion":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
		$filtro=""; $dbeneficiario=0; $dcategoria=0; $dperiodo=1; $dtipo=0; $darticulo=0; $destado=0; $head=0;
		////////////
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
			$filtro.=" AND (requisicion.fecha_orden>='".$fecha_desde."' AND requisicion.fecha_orden<='".$fecha_hasta."') "; $dperiodo=1;
			if ($dbeneficiario==1) $head=2; else $head=1;
		}
		if ($_GET['idtipo']!="0") { 
			$filtro.=" AND (tipos_documentos.idtipos_documentos='".$_GET['idtipo']."') "; $dtipo=1;
			if ($dbeneficiario==1) $head=4; else $head=3;
		}
		if ($_GET['idestado']!="0") { 
			$filtro.=" AND (requisicion.estado='".$_GET['idestado']."') "; $destado=1;
			if ($head==0) $head=5;
			elseif ($head==2) $head=6;
			elseif ($head==3) $head=7;
			elseif ($head==4) $head=8;
		}
		if ($filtro=="") $head=1;
	
	
		////////////
		if ($head==9)
			$sql="SELECT requisicion.codigo_referencia, 
						 requisicion.numero_requisicion, 
						 requisicion.fecha_orden, 
						 requisicion.estado, 
						 beneficiarios.nombre, 
						 tipos_documentos.descripcion, 
						 categoria_programatica.idcategoria_programatica, 
						 categoria_programatica.codigo, 
						 unidad_ejecutora.denominacion, 
						 articulos_requisicion.idarticulos_servicios, 
						 articulos_servicios.descripcion As articulo, 
						 requisicion.estado, 
						 articulos_requisicion.precio_unitario 
					FROM 
						 requisicion, 
						 tipos_documentos, 
						 beneficiarios, 
						 categoria_programatica, 
						 unidad_ejecutora, 
						 articulos_requisicion, 
						 articulos_servicios 
					WHERE 
						 (requisicion.idbeneficiarios=beneficiarios.idbeneficiarios) AND 
						 (requisicion.tipo=tipos_documentos.idtipos_documentos) AND
						 (requisicion.idcategoria_programatica=categoria_programatica.idcategoria_programatica AND 
						 categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND 
						 (tipos_documentos.modulo like '%-3-%') AND 
						 (requisicion.idrequisicion=articulos_requisicion.idrequisicion AND 
						 articulos_requisicion.idarticulos_servicios=articulos_servicios.idarticulos_servicios) $filtro 
					ORDER BY requisicion.codigo_referencia";
		elseif ($_GET['idarticulo']!="")
			$sql="SELECT requisicion.codigo_referencia, 
						 requisicion.numero_requisicion, 
						 requisicion.fecha_orden, 
						 requisicion.estado, 
						 beneficiarios.nombre, 
						 tipos_documentos.descripcion, 
						 categoria_programatica.idcategoria_programatica, 
						 categoria_programatica.codigo, 
						 unidad_ejecutora.denominacion, 
						 articulos_requisicion.idarticulos_servicios, 
						 articulos_servicios.descripcion As articulo, 
						 requisicion.estado, 
						 requisicion.total 
					FROM 
						 requisicion, 
						 tipos_documentos, 
						 beneficiarios, 
						 categoria_programatica, 
						 unidad_ejecutora, 
						 articulos_requisicion, 
						 articulos_servicios 
					WHERE 
						 (requisicion.idbeneficiarios=beneficiarios.idbeneficiarios) AND 
						 (requisicion.tipo=tipos_documentos.idtipos_documentos) AND
						 (requisicion.idcategoria_programatica=categoria_programatica.idcategoria_programatica AND 
						 categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND 
						 (tipos_documentos.modulo like '%-3-%') AND 
						 (requisicion.idrequisicion=articulos_requisicion.idrequisicion AND 
						 articulos_requisicion.idarticulos_servicios=articulos_servicios.idarticulos_servicios) $filtro 
						 ORDER BY requisicion.codigo_referencia";
		else
			$sql="SELECT requisicion.codigo_referencia, 
						 requisicion.numero_requisicion, 
						 requisicion.fecha_orden, 
						 requisicion.estado, 
						 beneficiarios.nombre, 
						 tipos_documentos.descripcion, 
						 categoria_programatica.idcategoria_programatica, 
						 categoria_programatica.codigo, 
						 unidad_ejecutora.denominacion, 
						 requisicion.estado, 
						 requisicion.total 
					FROM 
						 requisicion, 
						 tipos_documentos, 
						 beneficiarios, 
						 categoria_programatica, 
						 unidad_ejecutora 
					WHERE 
						 (requisicion.idbeneficiarios=beneficiarios.idbeneficiarios) AND 
						 (requisicion.tipo=tipos_documentos.idtipos_documentos) AND
						 (requisicion.idcategoria_programatica=categoria_programatica.idcategoria_programatica AND 
						 categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND 
						 (tipos_documentos.modulo like '%-3-%') $filtro 
					ORDER BY requisicion.codigo_referencia";
					
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//---------------------------------------------------
		if ($head==1) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($grupo != $field['idcategoria_programatica']) {
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['denominacion']);
					$pdf->Ln(5);
					
					$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, utf8_decode('Categoría Programática: '), 0, 0, 'L');
					$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($field['denominacion']), 0, 1, 'L');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
					$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
					$pdf->Cell(40, 5, 'Tipo', 1, 0, 'C', 1);
					$pdf->Cell(60, 5, 'Proveedor', 1, 0, 'C', 1);
					$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
				}
				
				$total=number_format($field['total'], 2, ',', '.');
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_requisicion($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'C', 'L', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 25, 40, 60, 25));
				$pdf->Row(array($field['numero_requisicion'], $fecha, $estado, utf8_decode($field['descripcion']), utf8_decode($field['nombre']), $total));
				$y=$pdf->GetY(); if ($y>250) filtro_requisicion($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
			}
		}
		//----------------------------------------------------
		elseif ($head==2) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($grupo != $field['idcategoria_programatica']) {
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['denominacion']);
					$pdf->Ln(5);
					
					$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, utf8_decode('Categoría Programática: '), 0, 0, 'L');
					$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($field['denominacion']), 0, 1, 'L');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
					$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
					$pdf->Cell(40, 5, 'Tipo', 1, 0, 'C', 1);
					$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
				}
				
				$total=number_format($field['total'], 2, ',', '.');
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_requisicion($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'C', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 25, 40, 25));
				$pdf->Row(array($field['numero_requisicion'], $fecha, $estado, utf8_decode($field['descripcion']), $total));
				$y=$pdf->GetY(); if ($y>250) filtro_requisicion($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
			}
		}
		//----------------------------------------------------
		elseif ($head==3) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($grupo != $field['idcategoria_programatica']) {
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['denominacion']);
					$pdf->Ln(5);
					
					$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, utf8_decode('Categoría Programática: '), 0, 0, 'L');
					$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($field['denominacion']), 0, 1, 'L');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
					$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
					$pdf->Cell(100, 5, 'Proveedor', 1, 0, 'C', 1);
					$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
				}
				
				$total=number_format($field['total'], 2, ',', '.');
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_requisicion($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'C', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 25, 100, 25));
				$pdf->Row(array($field['numero_requisicion'], $fecha, $estado, utf8_decode($field['nombre']), $total));
				$y=$pdf->GetY(); if ($y>250) filtro_requisicion($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
			}
		}
		//----------------------------------------------------
		elseif ($head==4) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($grupo != $field['idcategoria_programatica']) {
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['denominacion']);
					$pdf->Ln(5);
					
					$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, utf8_decode('Categoría Programática: '), 0, 0, 'L');
					$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($field['denominacion']), 0, 1, 'L');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
					$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
					$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
				}
				
				$total=number_format($field['total'], 2, ',', '.');
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_requisicion($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'C', 'R'));
				$pdf->SetWidths(array(25, 20, 25, 25));
				$pdf->Row(array($field['numero_requisicion'], $fecha, $estado, $total));
				$y=$pdf->GetY(); if ($y>250) filtro_requisicion($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, "");
			}
		}
		//----------------------------------------------------
		elseif ($head==5) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($grupo != $field['idcategoria_programatica']) {
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['denominacion']);
					$pdf->Ln(5);
					
					$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, utf8_decode('Categoría Programática: '), 0, 0, 'L');
					$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($field['denominacion']), 0, 1, 'L');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
					$pdf->Cell(40, 5, 'Tipo', 1, 0, 'C', 1);
					$pdf->Cell(80, 5, 'Proveedor', 1, 0, 'C', 1);
					$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
				}
				
				$total=number_format($field['total'], 2, ',', '.');
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_requisicion($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'L', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 40, 80, 25));
				$pdf->Row(array($field['numero_requisicion'], $fecha, utf8_decode($field['descripcion']), utf8_decode($field['nombre']), $total));
				$y=$pdf->GetY(); if ($y>250) filtro_requisicion($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
			}
		}
		//----------------------------------------------------
		elseif ($head==6) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($grupo != $field['idcategoria_programatica']) {
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['denominacion']);
					$pdf->Ln(5);
					
					$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, utf8_decode('Categoría Programática: '), 0, 0, 'L');
					$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($field['denominacion']), 0, 1, 'L');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
					$pdf->Cell(60, 5, 'Tipo', 1, 1, 'C', 1);
					$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
				}
				
				$total=number_format($field['total'], 2, ',', '.');
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_requisicion($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 20, 25));
				$pdf->Row(array($field['numero_requisicion'], $fecha, utf8_decode($field['descripcion'], $total)));
				$y=$pdf->GetY(); if ($y>250) filtro_requisicion($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, $estado);
			}
		}
		//----------------------------------------------------
		elseif ($head==7) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($grupo != $field['idcategoria_programatica']) {
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['denominacion']);
					$pdf->Ln(5);
					
					$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, utf8_decode('Categoría Programática: '), 0, 0, 'L');
					$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($field['denominacion']), 0, 1, 'L');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
					$pdf->Cell(100, 5, 'Proveedor', 1, 0, 'C', 1);
					$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
				}
				
				$total=number_format($field['total'], 2, ',', '.');
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_requisicion($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 100, 25));
				$pdf->Row(array($field['numero_requisicion'], $fecha, utf8_decode($field['nombre'], $total)));
	
				$y=$pdf->GetY(); if ($y>250) filtro_requisicion($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
			}
		}
		//----------------------------------------------------
		elseif ($head==8) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($grupo != $field['idcategoria_programatica']) {
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['denominacion']);
					$pdf->Ln(5);
					
					$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, utf8_decode('Categoría Programática: '), 0, 0, 'L');
					$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($field['denominacion']), 0, 1, 'L');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
					$pdf->Cell(25, 5, 'Total', 1, 1, 'C', 1);
				}
				
				$total=number_format($field['total'], 2, ',', '.');
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dbeneficiario==1) $beneficiario=$field['nombre'];
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($dtipo==1) $tipo=$field['descripcion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_requisicion($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'R'));
				$pdf->SetWidths(array(25, 20, 25));
				$pdf->Row(array($field['numero_orden'], $fecha, $total));
				$y=$pdf->GetY(); if ($y>250) filtro_requisicion($pdf, $head, $beneficiario, $categoria, $_GET['desde'], $_GET['hasta'], $tipo, $articulo, $estado);
			}
		}
		//----------------------------------------------------
		elseif ($head==9) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($grupo != $field['idcategoria_programatica']) {
					$grupo = $field['idcategoria_programatica'];
					$categoria = utf8_decode($field['denominacion']);
					$pdf->Ln(5);
					
					$pdf->SetFont('Arial', '', 8); $pdf->Cell(33, 4, utf8_decode('Categoría Programática: '), 0, 0, 'L');
					$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(162, 4, utf8_decode($field['denominacion']), 0, 1, 'L');
					//Colores de los bordes, fondo, texto y tama&ntilde;o del texto
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(25, 5, 'Nro.', 1, 0, 'C', 1);
					$pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', 1);
					$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
					$pdf->Cell(40, 5, 'Tipo', 1, 0, 'C', 1);
					$pdf->Cell(60, 5, 'Proveedor', 1, 0, 'C', 1);
					$pdf->Cell(25, 5, 'Precio U.', 1, 1, 'C', 1);
				}
				
				$precio_unitario=number_format($field['precio_unitario'], 2, ',', '.');
				list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a;
				$estado=strtoupper($field['estado']);
				if ($dcategoria==1) $categoria=$field['denominacion'];
				if ($darticulo==1) $articulo=$field['articulo'];
				if ($i==0) filtro_requisicion($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('L', 'C', 'C', 'L', 'L', 'R'));
				$pdf->SetWidths(array(25, 20, 25, 20, 80, 25));
				$pdf->Row(array($field['numero_requisicion'], $fecha, $estado, utf8_decode($field['descripcion']), utf8_decode($field['nombre']), $precio_unitario));
				$y=$pdf->GetY(); if ($y>250) filtro_requisicion($pdf, $head, "", $categoria, $_GET['desde'], $_GET['hasta'], "", $articulo, "");
			}
		}
		break;
		
	//	(Modulo) Orden de Compra/Servicio...
	case "ordencs":
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
		$sql="SELECT orden_compra_servicio.numero_orden, 
					 orden_compra_servicio.fecha_orden, 
					 tipos_documentos.descripcion, 
					 orden_compra_servicio.idbeneficiarios, 
					 beneficiarios.nombre, 
					 beneficiarios.rif, 
					 categoria_programatica.idcategoria_programatica, 
					 unidad_ejecutora.denominacion, 
					 orden_compra_servicio.justificacion, 
					 orden_compra_servicio.numero_requisicion, 
					 orden_compra_servicio.fecha_requisicion, 
					 orden_compra_servicio.sub_total, 
					 orden_compra_servicio.impuesto, 
					 orden_compra_servicio.total, 
					 orden_compra_servicio.exento, 
					 categoria_programatica.codigo, 
					 orden_compra_servicio.descuento, 
					 orden_compra_servicio.exento_original, 
					 orden_compra_servicio.sub_total_original, 
					 tipos_documentos.idtipos_documentos,
					 tipos_documentos.reversa_compromiso
				FROM 
					 orden_compra_servicio, 
					 tipos_documentos, 
					 beneficiarios, 
					 categoria_programatica, 
					 unidad_ejecutora 
				WHERE 
					 (orden_compra_servicio.idorden_compra_servicio='".$_GET['id_orden_compra']."') AND 
					 (orden_compra_servicio.tipo=tipos_documentos.idtipos_documentos) AND 
					 (orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios) AND 
					 (orden_compra_servicio.idcategoria_programatica=categoria_programatica.idcategoria_programatica AND
					 categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)";
		$query=mysql_query($sql) or die ("aqui...".$sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {
			$field=mysql_fetch_array($query);
			$documento=$field['idtipos_documentos'];
			$id_categoria_programatica=$field[6];
			$categoria_programatica=$field[15];
			$numero=$field[0];
			$fecha=$field[1];
			//if ($field[2]=="Compras") $tipo="Compra"; else $tipo="Servicio";
			$tipo=$field[2];
			$reversa=$field[20];
			$nombre=$field[4];
			$rif=$field[5];
			$unidad=$field[7];
			$justificacion=$field[8];
			$nrequisicion=$field[9];
			$frequisicion=$field[10];
			$impuesto=number_format($field[12], 2, ',', '.');
			$total_general=number_format($field[13], 2, ',', '.');
			$descuento=$field['descuento'];
			$valor_descuento=number_format($field['descuento'], 2, ',', '.');
			if ($descuento>0) {
				$agregar_descuento=1;
				$exento=number_format($field['exento_original'], 2, ',', '.');
				$sub_total=number_format($field['sub_total_original'], 2, ',', '.');
			} else { 
				$exento=number_format($field[14], 2, ',', '.');
				$sub_total=number_format($field[11], 2, ',', '.');
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
			$query=mysql_query($sql) or die (" dos ".$sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				$anio=$field['anio'];
				$tpresupuesto=$field['TipoPresupuesto'];
				$ffinanciamiento=$field['FuenteFinanciamiento'];			
			}
		}
		
				
		//-----------
		//OBTENGO LOS DATOS DEL CUERPO Y LOS IMPRIMO
		if ($modulo != "16") {
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
			$query=mysql_query($sql) or die (" tres ".$sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $pag=1;
			ordencs($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo, $documento,$reversa);
			$pdf->SetY(76); $y=$pdf->GetY();
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query); //for($f=0;$f<25;$f++){
				$total=(float) $field[3];
				$pu=number_format($field[2], 2, ',', '.');
				if ($total==0) $total=number_format($field[4], 2, ',', '.'); else $total=number_format($field[3], 2, ',', '.');
				
				$pdf->SetAligns(array('C', 'R', 'C', 'L', 'R', 'R'));
				$pdf->SetWidths(array(10, 15, 15, 95, 35, 35));
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 9);
				$pdf->Row(array($i, $field[1], $field[7], utf8_decode($field[6]), $pu, $total));
				
				if(strlen($field[6]) < "50"){
					$y+=5;
				}else if(strlen($field[6]) > "50" and strlen($field[6]) < "100"){
					$y+=10;
				}else if(strlen($field[6]) > "100"){
					$y+=30;	
				}
				if ($y>205) {
					$pag++;
					ordencs($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo, $documento,$reversa);
					$y=76; $pdf->SetY(76);
				}
			}//}
		} else {
			$sql="xSELECT
						frcc.*,
						b.nombre AS nombeneficiario,
						(SELECT SUM(total) 
							FROM articulos_rendicion_caja_chica 
								WHERE idorden_compra_servicio = '".$id_orden_compra."' AND 
									idfactura_rendicion_caja_chica = frcc.idfactura_rendicion_caja_chica) AS base,
						(SELECT SUM(exento) 
							FROM articulos_rendicion_caja_chica 
								WHERE idorden_compra_servicio = '".$id_orden_compra."' AND 
									idfactura_rendicion_caja_chica = frcc.idfactura_rendicion_caja_chica) AS exento,
						(SELECT SUM(impuesto) 
							FROM articulos_rendicion_caja_chica 
								WHERE idorden_compra_servicio = '".$id_orden_compra."' AND 
									idfactura_rendicion_caja_chica = frcc.idfactura_rendicion_caja_chica) AS impuesto
					FROM
						facturas_rendicion_caja_chica frcc
						LEFT JOIN beneficiarios b ON (frcc.idbeneficiarios = b.idbeneficiarios)
					WHERE
						frcc.idorden_compra_servicio = '".$id_orden_compra."'";
			$query=mysql_query($sql) or die (" cuatro ".$sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $pag=1;
			ordencs($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo, $documento,$reversa);
			$pdf->SetY(76); $y=$pdf->GetY();
			for ($i=1; $i<=$rows; $i++) {
				$field=mysql_fetch_array($query); //for($f=0;$f<25;$f++){
				//$total=(float) $field[3];
				//$pu=number_format($field[2], 2, ',', '.');
				//if ($total==0) $total=number_format($field[4], 2, ',', '.'); else $total=number_format($field[3], 2, ',', '.');
				$total = $field['base'] + $field['exento'] + $field['impuesto'];
				
				$pdf->SetAligns(array('L', 'L', 'C', 'R', 'R', 'R', 'R'));
				$pdf->SetWidths(array(25, 60, 20, 25, 25, 25, 25));
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 9);
				$pdf->Row(array($field['nro_factura'], utf8_decode($field['nombeneficiario']), formatoFecha($field['fecha_factura']), number_format($field['base'], 2, ',', '.'), number_format($field['exento'], 2, ',', '.'), number_format($field['impuesto'], 2, ',', '.'), number_format($total, 2, ',', '.')));
				
				if(strlen($field['nombeneficiario']) < "50"){
					$y+=5;
				}else if(strlen($field['nombeneficiario']) > "50" and strlen($field['nombeneficiario']) < "100"){
					$y+=10;
				}else if(strlen($field['nombeneficiario']) > "100"){
					$y+=30;	
				}
				if ($y>205) {
					$pag++;
					ordencs($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo, $documento,$reversa);
					$y=76; $pdf->SetY(76);
				}
			}//}
			
		}
		
		
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
		$query_impuestos=mysql_query($sql) or die (" cinco ".$sql.mysql_error());
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
						 categoria_programatica.codigo,
						 partidas_orden_compra_servicio.monto_restado
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
		$query=mysql_query($sql) or die (" seis ".$sql.mysql_error());
		$rows_partidas=mysql_num_rows($query);
		
		$ycuadro=$y+((3+$nro_impuestos+$agregar_descuento)*5);
		if ($ycuadro>=220) {
			$pag++;
			ordencs($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo, $documento,$reversa);
			if ($rows_partidas>6) $y=215-((3+$nro_impuestos)*5); else $y=180-((3+$nro_impuestos)*5);
		}
		else if ($ycuadro<=155) $y=155;
		else $y=215-((3+$nro_impuestos)*5);
		//-----------
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(150, 150, 150); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Rect(5.00125, $y-6, 205, 0.1);
		$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y-3); $pdf->MultiCell($w, l, 'EXENTO: ', 0, 'R'); 	
		$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y-3); $pdf->MultiCell($w, l, $exento, 0, 'R');
		$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y+2); $pdf->MultiCell($w, l, 'SUB-TOTAL:', 0, 'R'); 	
		$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y+2); $pdf->MultiCell($w, l, $sub_total, 0, 'R');
		if ($agregar_descuento==1) {
			$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y+7); $pdf->MultiCell($w, l, 'DESCUENTO:', 0, 'R'); 	
			$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y+7); $pdf->MultiCell($w, l, $valor_descuento, 0, 'R');
			$y+=5;
		}
		for ($z=0; $z<$nro_impuestos; $z++) {
			$field_impuestos=mysql_fetch_array($query_impuestos) or die (mysql_error());
			$porcentaje=number_format($field_impuestos['porcentaje'], 2, ',', '.');
			$siglas=$field_impuestos['siglas']." (".$porcentaje." %):";
			//$impuesto=number_format($field_impuestos['total'], 2, ',', '.');
			$y+=2;
			$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, $siglas, 0, 'R'); 	
			$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, $impuesto, 0, 'R');
		}
		$y=$pdf->GetY();
		$pdf->SetFont('Arial', 'B', 12);
		$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, 'TOTAL:', 0, 'R'); 	
		$h=5; $l=4; $x=170.00125; $w=35; $pdf->SetXY($x+5, $y+5); $pdf->MultiCell($w, l, $total_general, 0, 'R');
		
		$y=$pdf->GetY();
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetXY(5, $y-13); $pdf->Cell(50, 5, utf8_decode('AÑO:  '.$anio), 0, 1, 'L');
		$pdf->SetXY(5, $y-8); $pdf->Cell(100, 5, 'TIPO DE PRESUPUESTO:  '.$tpresupuesto, 0, 1, 'L'); 
		$pdf->SetXY(5, $y-3); $pdf->Cell(100, 5, 'FUENTE DE FINANCIAMIENTO:  '.$ffinanciamiento, 0, 1, 'L');
		//-----------
		$y=$pdf->GetY();
		if ($rows_partidas>6 || $y>180) {
			$pag++;
			anexoordencs($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar, $modulo, $documento, $reversa);
			$y=45;
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
			if($field["monto_restado"] > 0){
				$monto=number_format($field["monto_restado"], 2, ',', '.');	
			}else{
				$monto=number_format($field["monto"], 2, ',', '.');	
			}
			
			$descripcion=SUBSTR($field[2], 0, 65); 
			$h=5; $l=4; $x1=10.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $categoria_programatica, 0, 'C');
			$h=5; $l=4; $x1=35.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $partida, 0, 'C');
			$h=5; $l=4; $x1=60.00125; $w1=110; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, substr(utf8_decode($descripcion),0,50), 0, 'L');
			$h=5; $l=4; $x1=170.00125; $w1=45; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto, 0, 'R');
		}
		
		/*
		AQUI VAN LAS CUENTAS CONTABLES DEL COMPROMISO
		*/
		if ($rows_partidas<4 && $y<180) {
			
			$sql_asiento_contable = mysql_query("select * from asiento_contable where iddocumento = '".$id_orden_compra."'
																				and tipo_movimiento = 'compromiso'")or die(" siete ".mysql_error());
			if (mysql_num_rows($sql_asiento_contable)>0){
				$bus_asiento_contable = mysql_fetch_array($sql_asiento_contable);
				$sql_cuentas_asiento = mysql_query("select * from cuentas_asiento_contable where idasiento_contable ='".$bus_asiento_contable["idasiento_contable"]."'");
				$num_cuentas_asiento = mysql_num_rows($sql_cuentas_asiento);
				
				if ($num_cuentas_asiento <=2){
					$y=$pdf->GetY()+8;
					$pdf->SetFont('Arial', 'B', 9);
					$pdf->SetXY(5, $y); $pdf->Cell(50, 5, utf8_decode('AFECTACION CONTABLE'), 0, 1, 'L');
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
						$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_asiento);
						$y+=5;
						$idcampo = "id".$bus_cuentas_contables["tabla"];
						$sql_cuentas = mysql_query("select * from ".$bus_cuentas_contables["tabla"]." 
																			where ".$idcampo." = '".$bus_cuentas_contables["idcuenta"]."'")or die(mysql_error());
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
						$h=5; $l=4; $x1=10.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $bus_cuenta["codigo"], 0, 'L');
						$h=5; $l=4; $x1=35.00125; $w1=120; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, substr(utf8_decode($denominacion),0,50), 0, 'L');
						$h=5; $l=4; $x1=165.00125; $w1=30; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto_debe, 0, 'C');
						$h=5; $l=4; $x1=185.00125; $w1=30; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto_haber, 0, 'R');
					}
					
					
				}
			}
		}
		break;
		
	//	(Modulo) Requisiciones...
	case "requisicion":
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
		$sql="SELECT requisicion.numero_requisicion, 
					 requisicion.fecha_orden, 
					 tipos_documentos.descripcion, 
					 requisicion.idbeneficiarios, 
					 beneficiarios.nombre, 
					 beneficiarios.rif, 
					 categoria_programatica.idcategoria_programatica, 
					 unidad_ejecutora.denominacion, 
					 requisicion.justificacion, 
					 requisicion.numero_requisicion, 
					 requisicion.fecha_orden, 
					 requisicion.sub_total, 
					 requisicion.impuesto, 
					 requisicion.total, 
					 requisicion.exento, 
					 categoria_programatica.codigo 
				FROM 
					 requisicion, 
					 tipos_documentos, 
					 beneficiarios, 
					 categoria_programatica, 
					 unidad_ejecutora 
				WHERE 
					 (requisicion.idrequisicion='".$_GET['id_requisicion']."') AND 
					 (requisicion.tipo=tipos_documentos.idtipos_documentos) AND 
					 (requisicion.idbeneficiarios=beneficiarios.idbeneficiarios) AND 
					 (requisicion.idcategoria_programatica=categoria_programatica.idcategoria_programatica AND 
					 categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora)";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {
			$field=mysql_fetch_array($query);
			$id_categoria_programatica=$field[6];
			$categoria_programatica=$field[15];
			$numero=$field[0];
			$fecha=$field[1];
			//if ($field[2]=="Compras") $tipo="Compra"; else $tipo="Servicio";
			$tipo=$field[2];
			$nombre=$field[4];
			$rif=$field[5];
			$unidad=$field[7];
			$justificacion=$field[8];
			$nrequisicion=$field[9];
			$frequisicion=$field[10];
			$impuesto=number_format($field[12], 2, ',', '.');
			$total_general=number_format($field[13], 2, ',', '.');
			$descuento=$field['descuento'];
			$valor_descuento=number_format($field['descuento'], 2, ',', '.');
			if ($descuento>0) {
				$agregar_descuento=1;
				$exento=number_format($field['exento_original'], 2, ',', '.');
				$sub_total=number_format($field['sub_total_original'], 2, ',', '.');
			} else { 
				$exento=number_format($field[14], 2, ',', '.');
				$sub_total=number_format($field[11], 2, ',', '.');
				$agregar_descuento=0;
			}
			
			$sql="SELECT partidas_requisiciones.idrequisicion, 
						 maestro_presupuesto.anio, 
						 tipo_presupuesto.denominacion AS TipoPresupuesto, 
						 fuente_financiamiento.denominacion AS FuenteFinanciamiento 
					FROM 
						 partidas_requisiciones, 
						 maestro_presupuesto, 
						 tipo_presupuesto, 
						 fuente_financiamiento 
					WHERE 
						 (partidas_requisiciones.idrequisicion='".$_GET['id_requisicion']."' AND 
						 partidas_requisiciones.idmaestro_presupuesto=maestro_presupuesto.idRegistro AND 
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
		//-----------
		//OBTENGO LOS DATOS DEL CUERPO Y LOS IMPRIMO
		$sql="SELECT articulos_requisicion.idarticulos_servicios, 
					 articulos_requisicion.cantidad, 
					 articulos_requisicion.precio_unitario, 
					 articulos_requisicion.total, 
					 articulos_requisicion.exento, 
					 articulos_servicios.idunidad_medida, 
					 articulos_servicios.descripcion, 
					 unidad_medida.abreviado 
				FROM 
					 articulos_requisicion, 
					 articulos_servicios, 
					 unidad_medida 
				WHERE 
					 (articulos_requisicion.idrequisicion='".$_GET['id_requisicion']."') AND 
					 (articulos_requisicion.idarticulos_servicios=articulos_servicios.idarticulos_servicios) AND 
					 (articulos_servicios.idunidad_medida=unidad_medida.idunidad_medida)";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $pag=1;
		requisicion($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar);
		$pdf->SetY(66); $y=$pdf->GetY();
		for ($i=1; $i<=$rows; $i++) {
			$field=mysql_fetch_array($query);// for($f=0;$f<10;$f++){
			$total=(int) $field[3];
			$pu=number_format($field[2], 2, ',', '.');
			if ($total==0) $total=number_format($field[4], 2, ',', '.'); else $total=number_format($field[3], 2, ',', '.');
			//$y+=5;
			$pdf->SetAligns(array('C', 'R', 'C', 'L', 'R', 'R'));
			$pdf->SetWidths(array(10, 15, 15, 95, 35, 35));
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 9);
			$pdf->Row(array($i, $field[1], $field[7], utf8_decode($field[6]), $pu, $total));
			
			if(strlen($field[6]) < "50"){
					$y+=5;
				}else if(strlen($field[6]) > "50" and strlen($field[6]) < "100"){
					$y+=10;
				}else if(strlen($field[6]) > "100"){
					$y+=30;	
				}
			if ($y>205) {
				$pag++;
				requisicion($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar);
				$y=66; $pdf->SetY(66);
			}
		}//}
		$y=$pdf->GetY()+5;
		//IMPRIMO LOS TOTALES Y OBTENGO EL TIPO DE IMPUESTO
		$sql="SELECT relacion_impuestos_requisiciones.idimpuestos, 
					 impuestos.siglas, 
					 impuestos.porcentaje, 
					 relacion_impuestos_requisiciones.total 
				FROM 
					 relacion_impuestos_requisiciones, 
					 impuestos 
				WHERE 
					 (relacion_impuestos_requisiciones.idrequisicion='".$_GET['id_requisicion']."') AND 
					 (relacion_impuestos_requisiciones.idimpuestos=impuestos.idimpuestos)";
		$query_impuestos=mysql_query($sql) or die ($sql.mysql_error());
		$nro_impuestos=mysql_num_rows($query_impuestos);
	
		//OBTENGO LAS PARTIDAS Y LAS IMPRIMO
		$sql="SELECT partidas_requisiciones.idmaestro_presupuesto, 
					 partidas_requisiciones.monto, 
					 clasificador_presupuestario.denominacion, 
					 clasificador_presupuestario.partida, 
					 clasificador_presupuestario.generica, 
					 clasificador_presupuestario.especifica, 
					 clasificador_presupuestario.sub_especifica, 
					 maestro_presupuesto.idclasificador_presupuestario 
				FROM 
					 partidas_requisiciones, 
					 clasificador_presupuestario, 
					 maestro_presupuesto 
				WHERE 
					 (partidas_requisiciones.idrequisicion='".$_GET['id_requisicion']."') AND 
					 (partidas_requisiciones.idmaestro_presupuesto=maestro_presupuesto.idRegistro) AND
					 (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario)";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows_partidas=mysql_num_rows($query);
		
		$ycuadro=$y+((3+$nro_impuestos+$agregar_descuento)*5);
		if ($ycuadro>=220) {
			$pag++;
			requisicion($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag, $despachar);
			if ($rows_partidas>6) $y=215-((3+$nro_impuestos)*5); else $y=180-((3+$nro_impuestos)*5);
		}
		else if ($ycuadro<=155) $y=155;
		else $y=215-((3+$nro_impuestos)*5);
		//-----------
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(150, 150, 150); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Rect(5.00125, $y-3, 205, 0.1);
		$h=5; $l=4; $x=135.00125; $w=35; $pdf->SetXY($x+5, $y); $pdf->MultiCell($w, l, 'EXENTO: ', 0, 'R'); 	
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
	//		$impuesto=number_format($field_impuestos['total'], 2, ',', '.');
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
		if ($rows_partidas>6 || $y>180) {
			$pag++;
			anexorequisicion($pdf, $pag, $numero, $fecha, $tipo, $nombre, $rif, $nrequisicion, $frequisicion, $unidad, $justificacion, $pag);
			$y=45;
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
			$h=5; $l=4; $x1=10.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $categoria_programatica, 0, 'C');
			$h=5; $l=4; $x1=35.00125; $w1=25; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $partida, 0, 'C');
			$h=5; $l=4; $x1=60.00125; $w1=110; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, utf8_decode($descripcion), 0, 'L');
			$h=5; $l=4; $x1=170.00125; $w1=45; $pdf->SetXY($x1-5, $y); $pdf->MultiCell($w1, $l, $monto, 0, 'R');
		}
		break;
		
	//	(Modulo) Remision de Documentos...
	case "remitirdoc":
		$pdf=new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		$pag=1;
		//	OBTENGO EL HEAD DEL DOCUMENTO
		$sql="SELECT numero_documento, 
					 fecha_envio, 
					 iddependencia_destino, 
					 iddependencia_origen, 
					 asunto, 
					 justificacion, 
					 numero_documentos_enviados 
				FROM 
					 remision_documentos 
				WHERE 
					 idremision_documentos='".$_GET['id_remision']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) { $field=mysql_fetch_array($query); $ndoc=$field[0]; $fdoc=$field[1]; } 
		remitirdoc($pdf, $ndoc, $fdoc, $field[2], $field[3], $field[4], $field[5], $field[6], $pag);
		$query=mysql_query("SELECT * FROM relacion_documentos_remision WHERE idremision_documentos='".$_GET['id_remision']."'") or die ($sql.mysql_error());	
		while($bus_consulta=mysql_fetch_array($query)) {
			$idtabla = "id".$bus_consulta["tabla"];
			$sql_documentos = mysql_query("select * from ".$bus_consulta["tabla"]." where ".$idtabla." = ".$bus_consulta["id_documento"]."");
			$bus_documentos = mysql_fetch_array($sql_documentos);
	
				if($bus_consulta["tabla"] == "traslados_presupuestarios" and $bus_consulta["idtipos_documentos"] == 'elaboracion'){
				}else if($bus_consulta["tabla"] == "traslados_presupuestarios" and $bus_consulta["idtipos_documentos"] == 'procesado'){
				}else if($bus_consulta["tabla"] == "creditos_adicionales" and $bus_consulta["idtipos_documentos"] == 'elaboracion'){
				}else if($bus_consulta["tabla"] == "creditos_adicionales" and $bus_consulta["idtipos_documentos"] == 'procesado'){
				}else if($bus_consulta["tabla"] == "disminucion_presupuesto" and $bus_consulta["idtipos_documentos"] == 'elaboracion'){
				}else if($bus_consulta["tabla"] == "disminucion_presupuesto" and $bus_consulta["idtipos_documentos"] == 'procesado'){
				}else if($bus_consulta["tabla"] == "rectificacion_presupuesto" and $bus_consulta["idtipos_documentos"] == 'elaboracion'){
				}else if($bus_consulta["tabla"] == "rectificacion_presupuesto" and $bus_consulta["idtipos_documentos"] == 'procesado'){
				}else if($bus_consulta["tabla"] == "orden_compra_servicio") {
					
					$numero_documento = $bus_documentos["numero_orden"];
					$fecha = $bus_documentos["fecha_elaboracion"];
					$sql_beneficiario = mysql_query("select * from beneficiarios 
							where idbeneficiarios = '".$bus_documentos["idbeneficiarios"]."'");
					$bus_beneficiario = mysql_fetch_array($sql_beneficiario);	
					$beneficiario = $bus_beneficiario["nombre"];
					$monto = $bus_documentos["total"];
					
				}else if($bus_consulta["tabla"] == "orden_pago"){
				
					$numero_documento = $bus_documentos["numero_orden"];
					$fecha = $bus_documentos["fecha_elaboracion"];
					$sql_beneficiario = mysql_query("select * from beneficiarios 
							where idbeneficiarios = '".$bus_documentos["idbeneficiarios"]."'");
					$bus_beneficiario = mysql_fetch_array($sql_beneficiario);	
					$beneficiario = $bus_beneficiario["nombre"];
					$monto = $bus_documentos["total"];
				}
					
					
			list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $fecha=$d."/".$m."/".$a;
			$monto=number_format($monto, 2, ',', '.');
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetAligns(array('C', 'C', 'L', 'R'));
			$pdf->SetWidths(array(25, 25, 120, 35));
			$pdf->Row(array($numero_documento, $fecha, utf8_decode($beneficiario), $monto));
			$linea=$pdf->GetY(); if ($linea>235) { $pag++; remitirdoccon($pdf, $ndoc, $fdoc, $pag); $pdf->SetY(42); 
		}
		}
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
		$pdf->Rect(20, 250, 55, 0.1);
		$pdf->Rect(145, 250, 55, 0.1);
		$pdf->SetY(251);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(80, 4, "Firma y Sello", 0, 0, 'C'); 
		$pdf->Cell(60, 5); $pdf->Cell(65, 4, "Firma y Sello", 0, 1, 'C'); 
		$pdf->Cell(140, 5); $pdf->Cell(65, 5, "Recibido Por:", 0, 1, 'L');
		$pdf->Cell(140, 5); $pdf->Cell(65, 5, "Fecha:", 0, 1, 'L');
		$pdf->Cell(140, 5); $pdf->Cell(65, 5, "Hora:", 0, 1, 'L');
		break;
		
	//	Ordenes de Compra por Financiamiento...
	case "ordenes_compra_por_financiamiento":
		$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		//	---------------------------------------
		ordenes_compra_por_financiamiento($pdf);
		//	---------------------------------------
		$filtro = "";
		if ($anio_fiscal != "0") $filtro .= " AND ocs.anio = '".$anio_fiscal."' ";
		if ($tipo_presupuesto != "0") $filtro .= " AND ocs.idtipo_presupuesto = '".$tipo_presupuesto."' ";
		if ($financiamiento != "0") $filtro .= " AND ocs.idfuente_financiamiento = '".$financiamiento."' "; 
		if ($tipo != "0") $filtro .= " AND ocs.tipo = '".$tipo."' "; 
		if ($estado != "0") $filtro .= " AND ocs.estado = '".$estado."' "; 
		if ($desde != "") $filtro .= " AND ocs.fecha_orden >= '".$desde."'";
		if ($hasta != "") $filtro .= " AND ocs.fecha_orden <= '".$hasta."'";
		//	---------------------------------------
		$sql = "SELECT
					ocs.numero_orden,
					ocs.fecha_orden,
					b.nombre AS beneficiario,
					ocs.total,
					ocs.estado,
					td.compromete,
					td.causa
				FROM
					orden_compra_servicio ocs
					LEFT JOIN beneficiarios b ON (ocs.idbeneficiarios = b.idbeneficiarios)
					LEFT JOIN tipos_documentos td ON (ocs.tipo = td.idtipos_documentos AND (td.compromete = 'si' OR td.compromete = 'no') AND td.causa = 'si')
				WHERE 1 $filtro";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_orden']); $fecha=$d."/".$m."/".$a; $sum_total += $field['total'];
			$pdf->Row(array($field['numero_orden'], $fecha, utf8_decode($field['beneficiario']), number_format($field['total'], 2, ',', '.')));
			$linea=$pdf->GetY(); if ($linea>235) { ordenes_compra_por_financiamiento($pdf); } 
		}
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(205, 10, number_format($sum_total, 2, ',', '.'), 0, 1, 'R');
		
		break;
		
	//	Remision de Documentos...
	case "recibido_y_remitido":
		$pdf=new PDF_MC_Table4('L', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		$pag=1;
		
		//	-------------
		$sql = "SELECT nombre_modulo FROM modulo WHERE id_modulo = '".$_SESSION["modulo"]."'";
		$query_modulo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_modulo) != 0) $field_modulo = mysql_fetch_array($query_modulo);
		//	-------------
		if ($desde != "") { list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fdesde = $d."/".$m."/".$a; }
		if ($hasta != "") { list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fhasta = $d."/".$m."/".$a; }
		//	-------------
		if ($tipo == "orden_compra_servicio") $tabla = "Orden de Copmpra/Servicio";
		if ($tipo == "orden_pago") $tabla = "Orden de Pago";
		//	-------------
		
		$filtro_documento = "AND rdr.tabla = '".$tipo."'";
		if ($desde != "") $filtro_desde = "AND rd.fecha_elaboracion >= '".$desde."'";
		if ($hasta != "") $filtro_hasta = "AND rd.fecha_elaboracion <= '".$hasta."'";
		if ($estado != "0") {
			if ($tipo = "orden_compra_servicio") {
				if ($estado == "pp") $filtro_estado = "AND ($tipo.estado = 'procesado' OR $tipo.estado = 'pagado')";
				elseif ($estado == "cd") $filtro_estado = "AND ($tipo.estado = 'conformado' OR $tipo.estado = 'devuelto')";
				elseif ($estado == "pr") $filtro_estado = "AND ($tipo.estado = 'procesado')";
				elseif ($estado == "pa") $filtro_estado = "AND ($tipo.estado = 'pagado')";
			}
			elseif ($tipo = "orden_pago") {
				if ($estado == "pp") $filtro_estado = "AND ($tipo.estado = 'procesado' OR $tipo.estado = 'pagado')";
				elseif ($estado == "cd") $filtro_estado = "AND ($tipo.estado = 'conformado' OR $tipo.estado = 'devuelto')";
				elseif ($estado == "pr") $filtro_estado = "AND ($tipo.estado = 'procesado')";
				elseif ($estado == "pa") $filtro_estado = "AND ($tipo.estado = 'pagado')";
			}
		}
		
		if ($_SESSION['modulo'] == 1) $tb = "configuracion_rrhh";
		elseif ($_SESSION['modulo'] == 2) $tb = "configuracion_presupuesto";
		elseif ($_SESSION['modulo'] == 3) $tb = "configuracion_compras";
		elseif ($_SESSION['modulo'] == 4) $tb = "configuracion_administracion";
		elseif ($_SESSION['modulo'] == 5) $tb = "configuracion_contabilidad";
		elseif ($_SESSION['modulo'] == 6) $tb = "configuracion_tributos";
		elseif ($_SESSION['modulo'] == 7) $tb = "configuracion_tesoreria";
		elseif ($_SESSION['modulo'] == 8) $tb = "configuracion_bienes";
		elseif ($_SESSION['modulo'] == 12) $tb = "configuracion_despacho";
		elseif ($_SESSION['modulo'] == 13) $tb = "configuracion_nomina";
		elseif ($_SESSION['modulo'] == 16) $tb = "configuracion_caja_chica";
		
		$sql = "SELECT iddependencia FROM $tb";
		$query_dependencia = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_dependencia) != 0) $field_dependencia = mysql_fetch_array($query_dependencia); 
		
		recibido_y_remitido($pdf, $field_modulo, $fdesde, $fhasta, $tabla, $pag);
		$id = "id".$tipo;
		$sql = "SELECT 
					rd.idremision_documentos,
					rd.numero_documento,
					rd.fecha_elaboracion AS fecha_remision,
					d.denominacion AS destino,
					rdr.idtipos_documentos,
					rdr.id_documento,
					$tipo.tipo,
					$tipo.numero_orden,
					$tipo.fecha_elaboracion AS fecha_documento,
					$tipo.total,
					$tipo.estado,
					b.nombre AS beneficiario,
					td.modulo
				FROM 
					remision_documentos rd
					INNER JOIN relacion_documentos_remision rdr ON (rd.idremision_documentos = rdr.idremision_documentos)
					INNER JOIN dependencias d ON (rd.iddependencia_origen = d.iddependencia)
					INNER JOIN $tipo ON (rdr.id_documento = $tipo.$id)
					INNER JOIN beneficiarios b ON ($tipo.idbeneficiarios = b.idbeneficiarios)
					INNER JOIN tipos_documentos td ON (rdr.idtipos_documentos = td.idtipos_documentos)
				WHERE 
					rd.iddependencia_origen = '".$field_dependencia['iddependencia']."'
					$filtro_documento 
					$filtro_desde 
					$filtro_hasta 
				GROUP BY rd.idremision_documentos
				ORDER BY fecha_documento, numero_orden";
		$query_documentos = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_documentos = mysql_fetch_array($query_documentos)) {
			list($a, $m, $d)=SPLIT( '[/.-]', $field_documentos['fecha_documento']); $fecha_documento = $d."/".$m."/".$a;
			list($a, $m, $d)=SPLIT( '[/.-]', $field_documentos['fecha_remision']); $fecha_remision = $d."/".$m."/".$a;
			$monto=number_format($field_documentos['total'], 2, ',', '.');
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 6);
			$pdf->SetAligns(array('L', 'C', 'L', 'C', 'L', 'C'));
			$pdf->SetWidths(array(30, 20, 85, 20, 85, 30));
			$pdf->Row(array($field_documentos['numero_orden'], $fecha_documento, utf8_decode($field_documentos['beneficiario']), $fecha_remision, utf8_decode($field_documentos['destino']), strtoupper($field_documentos['estado'])));
			$linea=$pdf->GetY(); if ($linea>200) { $pag++; recibido_y_remitido($pdf, $field_modulo, $fdesde, $fhasta, $tabla, $pag); }
		}
		break;
		
	//	Documentos Recibidos...
	case "documentos_recibidos":
		$pdf=new PDF_MC_Table4('L', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		$pag=1;
		
		//	-------------
		$sql = "SELECT nombre_modulo FROM modulo WHERE id_modulo = '".$_SESSION["modulo"]."'";
		$query_modulo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_modulo) != 0) $field_modulo = mysql_fetch_array($query_modulo);
		//	-------------
		if ($desde != "") { list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fdesde = $d."/".$m."/".$a; }
		if ($hasta != "") { list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fhasta = $d."/".$m."/".$a; }
		//	-------------
		if ($tipo == "orden_compra_servicio") $tabla = "Orden de Copmpra/Servicio";
		if ($tipo == "orden_pago") $tabla = "Orden de Pago";
		//	-------------
		
		$filtro_documento = "AND rdr.tabla = '".$tipo."'";
		if ($desde != "") $filtro_desde = "AND rd.fecha_recibido >= '".$desde."'";
		if ($hasta != "") $filtro_hasta = "AND rd.fecha_recibido <= '".$hasta."'";
		if ($estado != "0") {
			if ($tipo = "orden_compra_servicio") {
				if ($estado == "pp") $filtro_estado = "AND ($tipo.estado = 'procesado' OR $tipo.estado = 'pagado')";
				elseif ($estado == "cd") $filtro_estado = "AND ($tipo.estado = 'conformado' OR $tipo.estado = 'devuelto')";
				elseif ($estado == "pr") $filtro_estado = "AND ($tipo.estado = 'procesado')";
				elseif ($estado == "pa") $filtro_estado = "AND ($tipo.estado = 'pagado')";
			}
			elseif ($tipo = "orden_pago") {
				if ($estado == "pp") $filtro_estado = "AND ($tipo.estado = 'procesado' OR $tipo.estado = 'pagado')";
				elseif ($estado == "cd") $filtro_estado = "AND ($tipo.estado = 'conformado' OR $tipo.estado = 'devuelto')";
				elseif ($estado == "pr") $filtro_estado = "AND ($tipo.estado = 'procesado')";
				elseif ($estado == "pa") $filtro_estado = "AND ($tipo.estado = 'pagado')";
			}
		}
		
		if ($_SESSION['modulo'] == 1) $tb = "configuracion_rrhh";
		elseif ($_SESSION['modulo'] == 2) $tb = "configuracion_presupuesto";
		elseif ($_SESSION['modulo'] == 3) $tb = "configuracion_compras";
		elseif ($_SESSION['modulo'] == 4) $tb = "configuracion_administracion";
		elseif ($_SESSION['modulo'] == 5) $tb = "configuracion_contabilidad";
		elseif ($_SESSION['modulo'] == 6) $tb = "configuracion_tributos";
		elseif ($_SESSION['modulo'] == 7) $tb = "configuracion_tesoreria";
		elseif ($_SESSION['modulo'] == 8) $tb = "configuracion_bienes";
		elseif ($_SESSION['modulo'] == 12) $tb = "configuracion_despacho";
		elseif ($_SESSION['modulo'] == 13) $tb = "configuracion_nomina";
		elseif ($_SESSION['modulo'] == 16) $tb = "configuracion_caja_chica";
		
		$sql = "SELECT iddependencia FROM $tb";
		$query_dependencia = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_dependencia) != 0) $field_dependencia = mysql_fetch_array($query_dependencia); 
		
		
		documentos_recibidos($pdf, $field_modulo, $fdesde, $fhasta, $tabla, $pag);
		$id = "id".$tipo;
		$sql = "SELECT 
					rd.idrecibir_documentos,
					rd.fecha_recibido,
					d.denominacion AS origen,
					rdr.idtipos_documentos,
					rdr.id_documento,
					$tipo.tipo,
					$tipo.numero_orden,
					$tipo.fecha_elaboracion AS fecha_documento,
					$tipo.total,
					$tipo.estado,
					b.nombre AS beneficiario,
					td.modulo
				FROM 
					recibir_documentos rd
					INNER JOIN relacion_documentos_recibidos rdr ON (rd.idrecibir_documentos = rdr.idrecibir_documentos)
					INNER JOIN dependencias d ON (rd.iddependencia_recibe = d.iddependencia)
					INNER JOIN $tipo ON (rdr.id_documento = $tipo.$id)
					INNER JOIN beneficiarios b ON ($tipo.idbeneficiarios = b.idbeneficiarios)
					INNER JOIN tipos_documentos td ON (rdr.idtipos_documentos = td.idtipos_documentos)
				WHERE 
					rd.iddependencia_recibe = '".$field_dependencia['iddependencia']."'
					$filtro_documento 
					$filtro_desde 
					$filtro_hasta 
				GROUP BY rd.idrecibir_documentos
				ORDER BY fecha_documento, numero_orden";
		$query_documentos = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_documentos = mysql_fetch_array($query_documentos)) {
			list($a, $m, $d)=SPLIT( '[/.-]', $field_documentos['fecha_documento']); $fecha_documento = $d."/".$m."/".$a;
			list($a, $m, $d)=SPLIT( '[/.-]', $field_documentos['fecha_recibido']); $fecha_recibido = $d."/".$m."/".$a;
			$monto=number_format($field_documentos['total'], 2, ',', '.');
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 6);
			$pdf->SetAligns(array('L', 'C', 'L', 'C', 'L', 'C'));
			$pdf->SetWidths(array(30, 20, 85, 20, 85, 30));
			$pdf->Row(array($field_documentos['numero_orden'], $fecha_documento, utf8_decode($field_documentos['beneficiario']), $fecha_recibido, utf8_decode($field_documentos['origen']), strtoupper($field_documentos['estado'])));
			$linea=$pdf->GetY(); if ($linea>200) { $pag++; documentos_recibidos($pdf, $field_modulo, $fdesde, $fhasta, $tabla, $pag); }
		}
		break;
		
		
	//	Consulta de Precios...
	case "scotizacion":
		$pdf=new PDF_MC_Table9('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(10);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pdf->Open();
		//	---------------------------------------
		//	consulto el numero de la solicitud, la fecha y el tipo de documento para imprimirlo en el top left de la pagina
		$sql = "SELECT
					sc.numero,
					sc.fecha_solicitud,
					td.descripcion AS tipo_documento
				FROM
					solicitud_cotizacion sc
					LEFT JOIN tipos_documentos td ON (sc.tipo = td.idtipos_documentos)
				WHERE
					sc.idsolicitud_cotizacion = '".$id_solicitud."'";
		$query_head = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_head) != 0) $field_head = mysql_fetch_array($query_head);
		//	---------------------------------------
		//	consulto los datos de configuracion para obtener la informacion de la empresa
		$sql = "SELECT * FROM configuracion LIMIT 0, 1";
		$query_conf = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_conf) != 0) $conf = mysql_fetch_array($query_conf);
		//	---------------------------------------
		//	valido el tipo de reporte a imprimir
		if ($consulta == "true") {
			//	consulto los proveedores seleccionados
			$sql = "SELECT
						psc.idbeneficiarios,
						b.nombre,
						b.rif,
						b.telefonos
					FROM
						proveedores_solicitud_cotizacion psc
						LEFT JOIN beneficiarios b ON (psc.idbeneficiarios = b.idbeneficiarios)
					WHERE
						idsolicitud_cotizacion = '".$id_solicitud."'";
			$query_proveedor = mysql_query($sql) or die ($sql.mysql_error());
			while($proveedor = mysql_fetch_array($query_proveedor)) {
				$pag=1;
				//	---------------------------------------
				//	imprimo la cabecera, los datos del proveedor y el organismo
				scotizacion($pdf, $pag, '', $field_head);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				
				$pdf->SetAligns(array('L', 'L', 'L', 'L')); $pdf->SetWidths(array(20, 90, 20, 70));
				$pdf->Row(array('PARA:', utf8_decode($proveedor['nombre']), utf8_decode('ATENCIÓN:'), utf8_decode(''), ));
				$pdf->Row(array(utf8_decode('TELÉFONO:'), utf8_decode($conf['telefonos']), 'E-MAIL:', utf8_decode($conf['correo_electronico'])));
				$pdf->Row(array('DE:', utf8_decode($conf['nombre_institucion']), 'TEL/FAX:', utf8_decode($conf['fax'])));
				//	--------------------
				$pdf->Ln(3);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				
				$pdf->SetAligns(array('C', 'C')); $pdf->SetWidths(array(30, 170));
				$pdf->Row(array('CANTIDAD', utf8_decode('DESCRIPCIÓN')));
				//	---------------------------------------
				//	imprimo los articulos a consultar
				$sql = "SELECT
							ass.descripcion,
							sc.cantidad
						FROM
							articulos_servicios ass 
							LEFT JOIN articulos_solicitud_cotizacion sc ON (ass.idarticulos_servicios = sc.idarticulos_servicios)
						WHERE
							sc.idsolicitud_cotizacion = '".$id_solicitud."'";
				$query_articulos = mysql_query($sql) or die ($sql.mysql_error());
				while($field_articulos = mysql_fetch_array($query_articulos)) {
					$pdf->SetFont('Arial', '', 9);
		
					$pdf->SetAligns(array('C', 'L')); $pdf->SetWidths(array(30, 170));
					$pdf->Row(array($field_articulos['cantidad'], utf8_decode($field_articulos['descripcion'])));
					if ($pdf->GetY() > 225) { $pag++; scotizacion($pdf, $pag, '', $field_head); }
				}
				//	---------------------------------------
				$pdf->Ln(1);
				//	imprimo la nota abajo de los articulos
				$pdf->SetFont('Arial', 'B', 8); $pdf->SetDrawColor(255, 255, 255); $pdf->SetAligns(array('L')); $pdf->SetWidths(array(200));
				$pdf->Row(array(utf8_decode('NOTA: REALIZAR EL PRESUPUESTO CON LAS MISMAS ESPECIFICACIONES QUE SE COLOCAN EN LA PRESENTE SOLICITUD')));
				if ($pdf->GetY() > 225) { $pag++; scotizacion($pdf, $pag, '', $field_head); }
				//	---------------------------------------
				//	imprimo las observaciones
				$pdf->Ln(5);
				$pdf->SetFont('Arial', 'BU', 10);
				$pdf->Cell(200, 8, utf8_decode('OBSERVACIONES:'), 0, 1, 'L');
				if ($pdf->GetY() > 225) { $pag++; scotizacion($pdf, $pag, '', $field_head); }
				
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetAligns(array('L')); $pdf->SetWidths(array(200));
				
				$pdf->Ln(5);
				$pdf->SetFont('Arial', '', 10);
				$pdf->Row(array(utf8_decode('1.-	Presentar su cotización en Bolívares a nombre de:')));
				if ($pdf->GetY() > 225) { $pag++; scotizacion($pdf, $pag, '', $field_head); }
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->Row(array(utf8_decode('   	'.$conf['nombre_institucion'].'	 Rif.:'.$conf['rif'])));
				if ($pdf->GetY() > 225) { $pag++; scotizacion($pdf, $pag, '', $field_head);; }
				
				$pdf->Ln(5);
				$pdf->SetFont('Arial', '', 10);
				$pdf->Row(array(utf8_decode('2.-	Incluir en su cotización:')));
				if ($pdf->GetY() > 225) { $pag++; scotizacion($pdf, $pag, '', $field_head); }
				$pdf->Row(array(utf8_decode('   			-	 Validez de la Oferta.')));
				if ($pdf->GetY() > 225) { $pag++; scotizacion($pdf, $pag, '', $field_head); }
				$pdf->Row(array(utf8_decode('   			-	 Tiempo de Entrega.')));
				if ($pdf->GetY() > 225) { $pag++; scotizacion($pdf, $pag, '', $field_head); }
				$pdf->Row(array(utf8_decode('   			-	 Condiciones de Pago.')));
				if ($pdf->GetY() > 225) { $pag++; scotizacion($pdf, $pag, '', $field_head); }
				$pdf->Row(array(utf8_decode('   			-	 Si está o no incluido el I:V.A en los precios cotizados.')));
				if ($pdf->GetY() > 225) { $pag++; scotizacion($pdf, $pag, '', $field_head); }
				
				$pdf->Ln(5);
				$pdf->SetFont('Arial', '', 10);
				$pdf->Row(array(utf8_decode('3.-	Enviar dicha cotización en sobre cerrado debidamente sellado y firmado a la siguiente dirección:')));
				if ($pdf->GetY() > 225) { $pag++; scotizacion($pdf, $pag, '', $field_head); }
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->Row(array(utf8_decode('   	'.$conf['domicilio_legal'].'	 Rif.:'.$conf['rif'])));
				if ($pdf->GetY() > 225) { $pag++; scotizacion($pdf, $pag, '', $field_head); }
				
				$pdf->Ln(5);
				$pdf->SetFont('Arial', '', 10);
				$pdf->Row(array(utf8_decode('4.-	De igual manera se puede enviar dicha oferta via fax y/o correo electrónico:')));
				if ($pdf->GetY() > 225) { $pag++; scotizacion($pdf, $pag, '', $field_head); }
			}
		} else {
			//	consulto el formato del reporte de cotizaciones
			$sql = "SELECT formato 
					FROM proveedores_solicitud_cotizacion 
					WHERE idsolicitud_cotizacion = '".$id_solicitud."' 
					GROUP BY formato";
			$query_formato = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_formato) != 0) $field_formato = mysql_fetch_array($query_formato);
			//	---------------------------------------
			if ($field_formato['formato'] == 'Analisis de Cotizaciones') {
				scotizacion($pdf, $pag, $field_formato, $field_head);
				//	---------------------------------------
				//	consulto los proveedores seleccionados
				$sql = "SELECT
							idbeneficiarios,
							monto_total,
							descuento,
							garantia_servicio,
							calidad,
							gtos_flt,
							emp_local,
							nro_cotizacion,
							experiencia
						FROM proveedores_solicitud_cotizacion
						WHERE idsolicitud_cotizacion = '".$id_solicitud."'
						ORDER BY idproveedores_solicitud_cotizacion";
				$query_proveedor = mysql_query($sql) or die ($sql.mysql_error());	$j=1;
				while($proveedor = mysql_fetch_array($query_proveedor)) {
					$pag=1;
					if ($j<10) $nro="0$j";
					
					$pdf->SetFont('Arial', '', 10);
					$pdf->SetAligns(array('C', 'C', 'R', 'C', 'C', 'C', 'C', 'C', 'C'));
					$pdf->SetWidths(array(20, 20, 25, 25, 20, 20, 20, 20, 25));
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->Row(array($nro,
									$proveedor['nro_cotizacion'],
									number_format($proveedor['monto_total'], 2, ',', '.'),
									strtoupper($proveedor['descuento']),
									strtoupper($proveedor['garantia_servicio']),
									strtoupper($proveedor['calidad']),
									strtoupper($proveedor['gtos_flt']),
									strtoupper($proveedor['emp_local']),
									strtoupper($proveedor['experiencia'])));
					$j++;
				}
				
				//	---------------------------------------
				//	muestro los datos de los proveedores
				$sql = "SELECT
							psc.idbeneficiarios,
							psc.justificacion,
							psc.ganador,
							b.nombre
						FROM 
							proveedores_solicitud_cotizacion psc
							INNER JOIN beneficiarios b On (psc.idbeneficiarios = b.idbeneficiarios)
						WHERE psc.idsolicitud_cotizacion = '".$id_solicitud."'
						ORDER BY idproveedores_solicitud_cotizacion";
				$query_proveedor = mysql_query($sql) or die ($sql.mysql_error());	$j=1;
				while($proveedor = mysql_fetch_array($query_proveedor)) {
					$pag=1;
					
					$pdf->SetFont('Arial', '', 10);
					$pdf->SetAligns(array('L'));
					$pdf->SetWidths(array(195));
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->Row(array(utf8_decode('EMPRESA Nº ').$j.' : '.$proveedor['nombre']));
					
					if ($proveedor['ganador'] == "y") $justificacion = $proveedor['justificacion'];
					
					$j++;
				}
				
				$pdf->SetFont('Arial', '', 10);
				$pdf->SetAligns(array('L'));
				$pdf->SetWidths(array(195));
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->Row(array('RECOMENDACIONES : '.$justificacion));
			}
			//	---------------------------------------
			elseif ($field_formato['formato'] == 'Acto Motivado') {
				$pag=1;
				scotizacion($pdf, $pag, $field_formato, $field_head);
				//	---------------------------------------
				//	muestro los datos de los proveedores
				$sql = "SELECT
							psc.idbeneficiarios,
							psc.justificacion,
							psc.ganador,
							b.nombre,
							te.descripcion as descripcion_tipo_empresa
						FROM 
							proveedores_solicitud_cotizacion psc
							INNER JOIN beneficiarios b On (psc.idbeneficiarios = b.idbeneficiarios)
							INNER JOIN tipo_empresa te ON (b.idtipo_empresa = te.idtipo_empresa)
						WHERE 
							psc.idsolicitud_cotizacion = '".$id_solicitud."' AND
							psc.ganador = 'y'
						ORDER BY idproveedores_solicitud_cotizacion";
				$query_proveedor = mysql_query($sql) or die ($sql.mysql_error());	
				if (mysql_num_rows($query_proveedor) != 0) {
					$proveedor = mysql_fetch_array($query_proveedor);
					
					$pdf->SetFont('Arial', '', 12);
					$sql_configuracion = mysql_query("select c.nombre_institucion, es.denominacion 
															from 
															configuracion c,
															estado es
															where 
															c.estado = es.idestado")or die(mysql_error());
					$bus_configuracion = mysql_fetch_array($sql_configuracion);
					
					$pdf->MultiCell(195, 8, utf8_decode('La '.$bus_configuracion["nombre_institucion"].' en función de sus atribuciones y en la búsqueda de continuar brindando un servicio oportuno y de mayor calidad en todas sus dependencias, ha de utilizar una serie de materiales, bienes, así como tambien, servicios que faciliten la ejecución de sus funciones. En este orden de ideas, EL DESPACHO DEL ALCALDE, requiere la Adquisición de: '), 0, 'J');
					$pdf->Ln(8);
					$pdf->SetFont('Arial', 'B', 12);
					$pdf->SetAligns(array('C', 'C', 'L')); $pdf->SetWidths(array(15, 20, 165));
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->Row(array('#', 'Cant.', utf8_decode('Descripción')));
						
					//	---------------------------------------
					//	imprimo los articulos a consultar
					$sql = "SELECT
								ass.descripcion,
								sc.cantidad
							FROM
								articulos_servicios ass 
								LEFT JOIN articulos_solicitud_cotizacion sc ON (ass.idarticulos_servicios = sc.idarticulos_servicios)
							WHERE
								sc.idsolicitud_cotizacion = '".$id_solicitud."'";
					$query_articulos = mysql_query($sql) or die ($sql.mysql_error());	$j=0;
					while($field_articulos = mysql_fetch_array($query_articulos)) {	$j++;
						$pdf->SetFont('Arial', '', 12);
			
						$pdf->SetAligns(array('C', 'C', 'L')); $pdf->SetWidths(array(15, 20, 165));
						$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
						$pdf->Row(array($j, $field_articulos['cantidad'], utf8_decode($field_articulos['descripcion'])));
						if ($pdf->GetY() > 225) { $pag++; scotizacion($pdf, $pag, '', $field_head); }
					}
					$pdf->Ln(8);
					//	---------------------------------------
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 12);
					
					$pdf->MultiCell(195, 8, utf8_decode('En este sentido, se contrató la '.$proveedor["descripcion_tipo_empresa"].': '.$proveedor['nombre'].', por prestar un servicio confiable y eficiente, lo cual resulta de gran beneficio a esta Institución en este sentido, y de conformidad con el Artículo '.$proveedor['articulo'].' Numeral '.$proveedor['articulo'].' del Decreto con Rango, Valor y Fuerza de Ley de Contrataciones Públicas, la cual especifica lo siguiente: Se podrá proceder excepcionalmente a la Cotratación Directa, independiente del monto de la contratación siempre y cuando la máxima autoridad del órgano o ente contratante mediante acto motivado, justifique adecuadamente su procedencia, en el diguiente supuesto: 1- Si se trata de suministros de bienes, prestación de servicios o ejecución de obras requeridas para la cantidad del proceso productivo y pudiera resultar gravemenete afectado por el retardo de la apertura de un procedimiento de contratación. Por ende se otorga por Contratación Directa la contratación de la referida compra a la '.$proveedor["descripcion_tipo_empresa"].': '.$proveedor['nombre'].', por ofrecer un mejor servicio y en virtud de que el mismo no le presta ningún proveedor residente en este Estado '.$bus_configuracion["denominacion"].'.'), 0, 'J');
				}
			}
		}
		break;
}
//	----------------------------------------
//	FIN CUERPO DE LOS REPORTES
//	----------------------------------------

$pdf->Output();
?>