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
	//	Presupuesto Original...
	case "preori":
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//---------------------------------------------
		$texto .= "Fuente de Financiamiento:\t".$nom_fuente_financiamiento."$nl";
		$texto .= "Tipo de Presupuesto:\t".$nom_tipo_presupuesto."$nl";
		$texto .= utf8_decode("Año Fiscal:\t".$anio_fiscal."$nl");
		$sum_total=0;
		if ($idcategoria_programatica=='0') $filtro=""; else $filtro=" AND (maestro_presupuesto.idcategoria_programatica='".$idcategoria_programatica."')";
		//---------------------------------------------
		$sql="(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria, 
					  maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
					  categoria_programatica.codigo AS CodCategoria, 
					  unidad_ejecutora.denominacion AS Unidad, 
					  clasificador_presupuestario.partida AS Par,
					  clasificador_presupuestario.generica AS Gen, 
					  clasificador_presupuestario.especifica AS Esp, 
					  clasificador_presupuestario.sub_especifica AS Sesp,
					  clasificador_presupuestario.denominacion AS NomPartida, 
					  maestro_presupuesto.idRegistro AS IdPresupuesto, 
					  maestro_presupuesto.monto_original AS MontoOriginal, 
					  'especifica' AS Tipo, 
					  ordinal.codigo AS codordinal,
					  ordinal.denominacion AS nomordinal 
				FROM 
					  maestro_presupuesto, 
					  categoria_programatica, 
					  unidad_ejecutora, 
					  clasificador_presupuestario, 
					  ordinal
				WHERE 
					  ((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					  (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					  (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
					  (maestro_presupuesto.idordinal=ordinal.idordinal) AND 
					  (maestro_presupuesto.anio='".$anio_fiscal."' AND 
					   maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
					   maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
				
				UNION
				
				(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria, 
						maestro_presupuesto.idclasificador_presupuestario AS idPartida,
						categoria_programatica.codigo AS CodCategoria, 
						unidad_ejecutora.denominacion AS Unidad, 
						clasificador_presupuestario.partida AS Par,
						(SELECT clasificador_presupuestario.generica 
						 FROM clasificador_presupuestario
						 WHERE (clasificador_presupuestario.idclasificador_presupuestario=idPartida)) AS Gen, 
						'00' AS Esp, 
						'00' AS Sesp,
						(SELECT clasificador_presupuestario.denominacion 
						 FROM clasificador_presupuestario
						 WHERE 
							(clasificador_presupuestario.partida=Par AND 
							 clasificador_presupuestario.generica=Gen AND 
							 clasificador_presupuestario.especifica='00' AND 
							 clasificador_presupuestario.sub_especifica='00')) AS NomPartida, 
						maestro_presupuesto.idRegistro AS IdPresupuesto, 
						SUM(maestro_presupuesto.monto_original) AS MontoOriginal, 
						'generica' AS Tipo, 
					  '0000' AS codordinal,
					  '' AS nomordinal 
				FROM 
					maestro_presupuesto, 
					categoria_programatica, 
					unidad_ejecutora, 
					clasificador_presupuestario 
				WHERE 
					((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
					(clasificador_presupuestario.sub_especifica='00') AND 
					(maestro_presupuesto.anio='".$anio_fiscal."' AND 
					maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
					maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
				
				UNION
				
				(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria, 
						maestro_presupuesto.idclasificador_presupuestario AS idPartida,
						categoria_programatica.codigo AS CodCategoria, 
						unidad_ejecutora.denominacion AS Unidad, 
						clasificador_presupuestario.partida AS Par,
						'00' AS Gen, 
						'00' AS Esp, 
						'00' AS Sesp,
						(SELECT clasificador_presupuestario.denominacion 
						 FROM clasificador_presupuestario
						 WHERE 
							(clasificador_presupuestario.partida=Par AND 
							 clasificador_presupuestario.generica='00' AND 
							 clasificador_presupuestario.especifica='00' AND 
							 clasificador_presupuestario.sub_especifica='00')) AS NomPartida, 
						maestro_presupuesto.idRegistro AS IdPresupuesto, 
						SUM(maestro_presupuesto.monto_original) AS MontoOriginal, 
						'partida' AS Tipo, 
						'0000' AS codordinal,
						'' AS nomordinal 
				FROM 
					maestro_presupuesto, 
					categoria_programatica, 
					unidad_ejecutora, 
					clasificador_presupuestario 
				WHERE 
					((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
					(clasificador_presupuestario.sub_especifica='00') AND 
					(maestro_presupuesto.anio='".$anio_fiscal."' AND 
					maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
					maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
				ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"]!=$IdCategoria) {
				$IdCategoria = $field['IdCategoria'];
				$texto .= utf8_decode($field["CodCategoria"]." - ".$field["Unidad"])."$nl";
			}
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$monto_original=number_format($field["MontoOriginal"], 2, ',', '.');
			if ($field["Tipo"]=="partida") {
				$sum_total+=$field["MontoOriginal"];
				$texto .= $clasificador."\t".utf8_decode($field["NomPartida"])."\t".$monto_original."$nl";
			}
			else if ($field["Tipo"]=="generica") {
				$texto .= $clasificador."\t".utf8_decode($field["NomPartida"])."\t".$monto_original."$nl";
			}
			else if ($field["Tipo"]=="especifica") {
				if ($field['codordinal'] == '0000')
					$texto .= $clasificador."\t".utf8_decode($field["NomPartida"])."\t".$monto_original."$nl";
				else
					$texto .= $clasificador."\t".utf8_decode($field['codordinal'].' '.$field["nomordinal"])."\t".$monto_original."$nl";
			}
		}
		//---------------------------------------------
		//	IMPRIMO EL TOTAL
		$sum_total=number_format($sum_total, 2, ',', '.');
		$texto .= "\t\t".$sum_total."$nl";
		fwrite($archivo, $texto);
		fclose($archivo);
		break;
		
	//	Resumen por Categorias...
	case "preres":
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//---------------------------------------------
		$texto .= "Fuente de Financiamiento:\t".$nom_fuente_financiamiento."$nl";
		$texto .= "Tipo de Presupuesto:\t".$nom_tipo_presupuesto."$nl";
		$texto .= utf8_decode("Año Fiscal:\t".$anio_fiscal."$nl");
		if ($idcategoria_programatica=='0') $filtro=""; else $filtro=" AND (maestro_presupuesto.idcategoria_programatica='".$idcategoria_programatica."')";
		//------------------------------------------------
		$sql="(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria, 
					  maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
					  categoria_programatica.codigo AS CodCategoria, 
					  unidad_ejecutora.denominacion AS Unidad, 
					  clasificador_presupuestario.partida AS Par,
					  clasificador_presupuestario.generica AS Gen, 
					  clasificador_presupuestario.especifica AS Esp, 
					  clasificador_presupuestario.sub_especifica AS Sesp,
					  clasificador_presupuestario.denominacion AS NomPartida, 
					  maestro_presupuesto.idRegistro AS IdPresupuesto,
					  SUM(maestro_presupuesto.monto_original) AS Formulado, 
					  SUM(maestro_presupuesto.monto_actual) AS Actual, 
					  SUM(maestro_presupuesto.total_causados) AS Causado, 
					  SUM(maestro_presupuesto.total_pagados) AS Pagado, 
					  SUM(maestro_presupuesto.total_compromisos) AS Compromiso,
					  SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso, 
					  SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir,
							SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
							SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion,
					  'especifica' AS Tipo, 
					  ordinal.codigo AS codordinal, 
					  ordinal.denominacion AS nomordinal
				FROM 
					  maestro_presupuesto, 
					  categoria_programatica, 
					  unidad_ejecutora, 
					  clasificador_presupuestario, 
					  ordinal
				WHERE 
					  ((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					  (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					  (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
					  (maestro_presupuesto.idordinal=ordinal.idordinal) AND 
					  (maestro_presupuesto.anio='".$anio_fiscal."' AND 
					  maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
					  maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
				GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
				
				UNION
				
				(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria, 
						maestro_presupuesto.idclasificador_presupuestario AS idPartida,
						categoria_programatica.codigo AS CodCategoria, 
						unidad_ejecutora.denominacion AS Unidad, 
						clasificador_presupuestario.partida AS Par,
						(SELECT clasificador_presupuestario.generica 
						 FROM clasificador_presupuestario
						 WHERE (clasificador_presupuestario.idclasificador_presupuestario=idPartida)) AS Gen, 
						'00' AS Esp, 
						'00' AS Sesp,
						(SELECT clasificador_presupuestario.denominacion 
						 FROM clasificador_presupuestario 
						 WHERE 
							(clasificador_presupuestario.partida=Par AND
							 clasificador_presupuestario.generica=Gen AND 
							 clasificador_presupuestario.especifica='00' AND 
							 clasificador_presupuestario.sub_especifica='00')) AS NomPartida, 
						maestro_presupuesto.idRegistro AS IdPresupuesto,
						SUM(maestro_presupuesto.monto_original) AS Formulado, 
						SUM(maestro_presupuesto.monto_actual) AS Actual, 
						SUM(maestro_presupuesto.total_causados) AS Causado, 
						SUM(maestro_presupuesto.total_pagados) AS Pagado, 
						SUM(maestro_presupuesto.total_compromisos) AS Compromiso, 
						SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso, 
						SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir,
							SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
							SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion,
						'generica' AS Tipo,
						'0000' AS codordinal, 
						'' AS nomordinal
					FROM 
						maestro_presupuesto, 
						categoria_programatica, 
						unidad_ejecutora, 
						clasificador_presupuestario
					WHERE 
						((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
						(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
						(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
						(clasificador_presupuestario.sub_especifica='00') AND 
						(maestro_presupuesto.anio='".$anio_fiscal."' AND 
						maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
						maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
					GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
					
					UNION
					
					(SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria, 
							maestro_presupuesto.idclasificador_presupuestario AS idPartida,
							categoria_programatica.codigo AS CodCategoria, 
							unidad_ejecutora.denominacion AS Unidad, 
							clasificador_presupuestario.partida AS Par,
							'00' AS Gen, 
							'00' AS Esp, 
							'00' AS Sesp,
							(SELECT clasificador_presupuestario.denominacion 
							 FROM clasificador_presupuestario
							 WHERE 
								(clasificador_presupuestario.partida=Par AND 
								clasificador_presupuestario.generica='00' AND 
								clasificador_presupuestario.especifica='00' AND 
								clasificador_presupuestario.sub_especifica='00')) AS NomPartida, 
							maestro_presupuesto.idRegistro AS IdPresupuesto,
							SUM(maestro_presupuesto.monto_original) AS Formulado, 
							SUM(maestro_presupuesto.monto_actual) AS Actual, 
							SUM(maestro_presupuesto.total_causados) AS Causado, 
							SUM(maestro_presupuesto.total_pagados) AS Pagado, 
							SUM(maestro_presupuesto.total_compromisos) AS Compromiso,
							SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso,
							SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir,
							SUM(maestro_presupuesto.total_aumento) AS TotalAumento,
							SUM(maestro_presupuesto.total_disminucion) AS TotalDisminucion,
							'partida' AS Tipo,
							'0000' AS codordinal, 
							'' AS nomordinal
						FROM 
							maestro_presupuesto, 
							categoria_programatica, 
							unidad_ejecutora, 
							clasificador_presupuestario
						WHERE 
							((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
							(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
							(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
							(clasificador_presupuestario.sub_especifica='00') AND 
							(maestro_presupuesto.anio='".$anio_fiscal."' AND 
							maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
							maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
						GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp), (codordinal))
						ORDER BY CodCategoria, Par, Gen, Esp, Sesp, codordinal";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while ($field=mysql_fetch_array($query)) {
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$formulado=number_format($field["Formulado"], 2, ',', '.');
			$monto_actual = $field['Formulado'] + $field['TotalAumento'] - $field['TotalDisminucion'];
			$actual=number_format($monto_actual, 2, ',', '.');
			$compromiso=number_format($field["Compromiso"], 2, ',', '.');
			$precompromiso=number_format($field["PreCompromiso"], 2, ',', '.');
			$causado=number_format($field["Causado"], 2, ',', '.');
			$pagado=number_format($field["Pagado"], 2, ',', '.');
			$disponible=number_format(($monto_actual-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"]), 2, ',', '.');
			if ($field["Compromiso"]==0 or $field["Actual"]==0) $pcompromiso="0"; else $pcompromiso=(float) (($field["Compromiso"]*100)/$monto_actual); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
			if ($field["Causado"]==0 or $monto_actual==0) $pcausado="0"; else $pcausado=(float) (($field["Causado"]*100)/$monto_actual); $pcausado=number_format($pcausado, 2, ',', '.');
			if ($field["Pagado"]==0 or $monto_actual==0) $ppagado="0"; else $ppagado=(float) (($field["Pagado"]*100)/$monto_actual); $ppagado=number_format($ppagado, 2, ',', '.');
			if ($field["Compromiso"]==0 or $monto_actual==0) $pdisponible="0"; else 
			if (($monto_actual-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"])==0) $pdisponible="0"; else $pdisponible=(float) ((($monto_actual-$field["Compromiso"])*100)/$monto_actual); 
			$pdisponible=number_format($pdisponible, 2, ',', '.');
			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"]!=$IdCategoria) {
				$IdCategoria = $field['IdCategoria'];
				$texto .= utf8_decode($field["CodCategoria"]." - ".$field["Unidad"])."$nl";
			}
			if ($field["Tipo"]=="partida") {
				$sum_formulado+=$field["Formulado"];
				$sum_actual+=$monto_actual;
				$sum_compromiso+=$field["Compromiso"];
				$sum_precompromiso+=$field["PreCompromiso"];
				$sum_causado+=$field["Causado"];
				$sum_pagado+=$field["Pagado"];
				$sum_disponible+=($field["Actual"]-$field["PreCompromiso"]-$field["Compromiso"]-$field["ReservadoDisminuir"]);
				$texto .= $clasificador."\t".utf8_decode($field["NomPartida"])."\t".$formulado."\t".$actual."\t".$precompromiso."\t".$compromiso."\t".$pcompromiso."%\t".$causado."\t".$pcausado."%\t".$pagado."\t".$ppagado."%\t".$disponible."\t".$pdisponible."%$nl";
			}
			else if ($field["Tipo"]=="generica") {
				$texto .= $clasificador."\t".utf8_decode($field["NomPartida"])."\t".$formulado."\t".$actual."\t".$precompromiso."\t".$compromiso."\t".$pcompromiso."%\t".$causado."\t".$pcausado."%\t".$pagado."\t".$ppagado."%\t".$disponible."\t".$pdisponible."%$nl";
			}
			else if ($field["Tipo"]=="especifica") {
				if ($field['codordinal'] == "0000")
					$texto .= $clasificador."\t".utf8_decode($field["NomPartida"])."\t".$formulado."\t".$actual."\t".$precompromiso."\t".$compromiso."\t".$pcompromiso."%\t".$causado."\t".$pcausado."%\t".$pagado."\t".$ppagado."%\t".$disponible."\t".$pdisponible."%$nl";
				else 
					$texto .= $clasificador."\t".utf8_decode($field['codordinal'].' '.$field["nomordinal"])."\t".$formulado."\t".$actual."\t".$precompromiso."\t".$compromiso."\t".$pcompromiso."%\t".$causado."\t".$pcausado."%\t".$pagado."\t".$ppagado."%\t".$disponible."\t".$pdisponible."%$nl";
			}
			$formulado=""; $actual=""; $precompromiso=""; $compromiso=""; $pcompromiso=""; $causado=""; $pcausado=""; $pagado=""; $ppagado=""; $disponible=""; $pdisponible="";
		}
		//------------------------------------------------
		if ($sum_compromiso == 0 or $sum_actual == 0) $tpcompromiso = 0; else $tpcompromiso=(float) (($sum_compromiso*100)/$sum_actual); $tpcompromiso=number_format($tpcompromiso, 2, ',', '.');
		if ($sum_causado == 0 or $sum_actual) $tpcausado = 0; else $tpcausado=(float) (($sum_causado*100)/$sum_actual); $tpcausado=number_format($tpcausado, 2, ',', '.');
		if ($sum_pagado == 0 or $sum_actual == 0) $tppagado = 0; else $tppagado=(float) (($sum_pagado*100)/$sum_actual); $tppagado=number_format($tppagado, 2, ',', '.');
		if ($sum_disponible == 0 or $sum_actual == 0) $tpdisponible = 0; else $tpdisponible=(float) (($sum_disponible*100)/$sum_actual); $tpdisponible=number_format($tpdisponible, 2, ',', '.');
		$sum_formulado=number_format($sum_formulado, 2, ',', '.'); 
		$sum_actual=number_format($sum_actual, 2, ',', '.');
		$sum_precompromiso=number_format($sum_precompromiso, 2, ',', '.');
		$sum_compromiso=number_format($sum_compromiso, 2, ',', '.'); 
		$sum_causado=number_format($sum_causado, 2, ',', '.'); 
		$sum_pagado=number_format($sum_pagado, 2, ',', '.'); 
		$sum_disponible=number_format($sum_disponible, 2, ',', '.'); 
		//	IMPRIMO LOS TOTALES
		$texto .= "\t\t".$sum_formulado."\t".$sum_actual."\t".$sum_precompromiso."\t".$sum_compromiso."\t".$tpcompromiso."%\t".$sum_causado."\t".$tpcausado."%\t".$sum_pagado."\t".$tppagado."%\t".$sum_disponible."\t".$tpdisponible."%$nl";
		fwrite($archivo, $texto);
		fclose($archivo);
		break;
		
	//	Resumen Consolidado...
	case "consolidado":
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//---------------------------------------------
		$texto .= "Fuente de Financiamiento:\t".$nom_fuente_financiamiento."$nl";
		$texto .= "Tipo de Presupuesto:\t".$nom_tipo_presupuesto."$nl";
		$texto .= utf8_decode("Año Fiscal:\t".$anio_fiscal."$nl");
		$l=0;
		if ($idcategoria_programatica=='0') $filtro=""; else $filtro=" AND (maestro_presupuesto.idcategoria_programatica='".$idcategoria_programatica."')";
		//	CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO	O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
		$sql="SELECT maestro_presupuesto.idclasificador_presupuestario AS idPartida, 
					 clasificador_presupuestario.partida AS Par, 
					 '00' AS Gen, 
					 '00' AS Esp, 
					 '00' AS Sesp,
					 (SELECT clasificador_presupuestario.denominacion 
					  FROM clasificador_presupuestario
					  WHERE 
						(clasificador_presupuestario.partida=Par AND 
						 clasificador_presupuestario.generica='00' AND 
						 clasificador_presupuestario.especifica='00' AND 
						 clasificador_presupuestario.sub_especifica='00')) AS NomPartida, 
					 maestro_presupuesto.idRegistro AS IdPresupuesto,
					 SUM(maestro_presupuesto.monto_original) AS Formulado, 
					 SUM(maestro_presupuesto.monto_actual) AS Actual,
					 SUM(maestro_presupuesto.total_causados) AS Causado, 
					 SUM(maestro_presupuesto.total_pagados) AS Pagado, 
					 SUM(maestro_presupuesto.total_compromisos) AS Compromiso,
					 SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso,
					 SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir
				FROM maestro_presupuesto, clasificador_presupuestario
				WHERE 
					(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
					(clasificador_presupuestario.sub_especifica='00') AND 
					(maestro_presupuesto.anio='".$anio_fiscal."' AND 
					maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
					maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro
				GROUP BY (Par), (Gen), (Esp), (Sesp)
				ORDER BY Par, Gen, Esp, Sesp";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		while ($field=mysql_fetch_array($query)) {
			$l++;
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$formulado=number_format($field["Formulado"], 2, ',', '.');
			$actual=number_format($field["Actual"], 2, ',', '.');
			$compromiso=number_format($field["Compromiso"], 2, ',', '.');
			$precompromiso=number_format($field["PreCompromiso"], 2, ',', '.');
			$causado=number_format($field["Causado"], 2, ',', '.');
			$pagado=number_format($field["Pagado"], 2, ',', '.');
			$disponible=number_format(($field["Actual"]-$field["Compromiso"]-$field["PreCompromiso"]-$field["ReservadoDisminuir"]), 2, ',', '.');
			if ($field["Compromiso"]==0 || $field["Actual"]==0) $pcompromiso="0"; else $pcompromiso=(float) (($field["Compromiso"]*100)/$field["Actual"]); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
			if ($field["Causado"]==0 || $field["Actual"]==0) $pcausado="0"; else $pcausado=(float) (($field["Causado"]*100)/$field["Actual"]); $pcausado=number_format($pcausado, 2, ',', '.');
			if ($field["Pagado"]==0 || $field["Actual"]==0) $ppagado="0"; else $ppagado=(float) (($field["Pagado"]*100)/$field["Actual"]); $ppagado=number_format($ppagado, 2, ',', '.');
			if (($field["Actual"]-$field["Compromiso"]-$field["PreCompromiso"]-$field["ReservadoDisminuir"])==0 || $field["Actual"]==0) $pdisponible="0"; else $pdisponible=(float) ((($field["Actual"]-$field["Compromiso"]-$field["PreCompromiso"]-$field["ReservadoDisminuir"])*100)/$field["Actual"]); 
			$pdisponible=number_format($pdisponible, 2, ',', '.');
			$sum_formulado+=$field["Formulado"];
			$sum_actual+=$field["Actual"];
			$sum_compromiso+=$field["Compromiso"];
			$sum_precompromiso+=$field["PreCompromiso"];
			$sum_causado+=$field["Causado"];
			$sum_pagado+=$field["Pagado"];
			$sum_disponible+=($field["Actual"]-$field["Compromiso"]-$field["PreCompromiso"]-$field["ReservadoDisminuir"]);
			$texto .= $clasificador."\t".utf8_decode($field["NomPartida"])."\t".$formulado."\t".$actual."\t".$precompromiso."\t".$compromiso."\t".$pcompromiso."%\t".$causado."\t".$pcausado."%\t".$pagado."\t".$ppagado."%\t".$disponible."\t".$pdisponible."%$nl";	
		}
		//------------------------------------------------
		$tpcompromiso=(float) (($sum_compromiso*100)/$sum_actual); $tpcompromiso=number_format($tpcompromiso, 2, ',', '.');
		$tpcausado=(float) (($sum_causado*100)/$sum_actual); $tpcausado=number_format($tpcausado, 2, ',', '.');
		$tppagado=(float) (($sum_pagado*100)/$sum_actual); $tppagado=number_format($tppagado, 2, ',', '.');
		$tpdisponible=(float) (($sum_disponible*100)/$sum_actual); $tpdisponible=number_format($tpdisponible, 2, ',', '.');	
		$sum_formulado=number_format($sum_formulado, 2, ',', '.'); 
		$sum_actual=number_format($sum_actual, 2, ',', '.');
		$sum_compromiso=number_format($sum_compromiso, 2, ',', '.'); 
		$sum_precompromiso=number_format($sum_precompromiso, 2, ',', '.'); 
		$sum_causado=number_format($sum_causado, 2, ',', '.'); 
		$sum_pagado=number_format($sum_pagado, 2, ',', '.'); 
		$sum_disponible=number_format($sum_disponible, 2, ',', '.'); 
		//	IMPRIMO LOS TOTALES
		$texto .= "\t\t".$sum_formulado."\t".$sum_actual."\t".$sum_precompromiso."\t".$sum_compromiso."\t".$tpcompromiso."%\t".$sum_causado."\t".$tpcausado."%\t".$sum_pagado."\t".$tppagado."%\t".$sum_disponible."\t".$tpdisponible."%$nl";
		fwrite($archivo, $texto);
		fclose($archivo);
		break;
		
	//	Consolidado por Categorias...
	case "porsector":
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//---------------------------------------------
		$texto .= "Fuente de Financiamiento:\t".$nom_fuente_financiamiento."$nl";
		$texto .= "Tipo de Presupuesto:\t".$nom_tipo_presupuesto."$nl";
		$texto .= utf8_decode("Año Fiscal:\t".$anio_fiscal."$nl");
		if ($idcategoria_programatica=='0') $filtro=""; else $filtro=" AND (maestro_presupuesto.idcategoria_programatica='".$idcategoria_programatica."')";
		////////////
		//	CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO	O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
		$sql="SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria, 
					 maestro_presupuesto.idclasificador_presupuestario AS idPartida,
					 categoria_programatica.codigo AS CodCategoria, 
					 unidad_ejecutora.denominacion AS Unidad, 
					 clasificador_presupuestario.partida AS Par,
					 '00' AS Gen, 
					 '00' AS Esp, 
					 '00' AS Sesp,
					 (SELECT clasificador_presupuestario.denominacion 
					  FROM clasificador_presupuestario
					  WHERE 
						   (clasificador_presupuestario.partida=Par AND 
						   clasificador_presupuestario.generica='00' AND 
						   clasificador_presupuestario.especifica='00' AND 
						   clasificador_presupuestario.sub_especifica='00')) AS NomPartida, 
					 maestro_presupuesto.idRegistro AS IdPresupuesto,
					 SUM(maestro_presupuesto.monto_original) AS Formulado, 
					 SUM(maestro_presupuesto.monto_actual) AS Actual,
					 SUM(maestro_presupuesto.total_causados) AS Causado, 
					 SUM(maestro_presupuesto.total_pagados) AS Pagado, 
					 SUM(maestro_presupuesto.total_compromisos) AS Compromiso, 
					 SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso, 
					 SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir
				FROM 
					maestro_presupuesto, 
					categoria_programatica, 
					unidad_ejecutora, 
					clasificador_presupuestario
				WHERE 
					((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					(categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
					(maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
					(clasificador_presupuestario.sub_especifica='00') AND 
					(maestro_presupuesto.anio='".$anio_fiscal."' AND 
					maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
					maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
					GROUP BY (CodCategoria), (Par), (Gen), (Esp), (Sesp)
					ORDER BY CodCategoria, Par, Gen, Esp, Sesp";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//	SI CAMBIA DE CATEGORIA LA IMPRIMO
			if ($field["IdCategoria"]!=$IdCategoria) {
				$IdCategoria=$field["IdCategoria"];
				$l=1;
				if ($i!=0) {
					//	IMPRIMO LOS TOTALES
					$sum_formulado=number_format($sum_formulados, 2, ',', '.'); 
					$sum_actual=number_format($sum_actuals, 2, ',', '.');
					$sum_compromiso=number_format($sum_compromisos, 2, ',', '.'); 
					$sum_precompromiso=number_format($sum_precompromisos, 2, ',', '.'); 
					if ($sum_compromiso=="0,00") $tpcompromiso="0,00";  else { $tpcompromiso=(float) (($sum_compromisos*100)/$sum_actuals); $tpcompromiso=number_format($tpcompromiso, 2, ',', '.'); }
					$sum_causado=number_format($sum_causados, 2, ',', '.'); 
					if ($sum_causado=="0,00") $tpcausado="0,00"; else { $tpcausado=(float) (($sum_causados*100)/$sum_actuals); $tpcausado=number_format($tpcausado, 2, ',', '.'); }
					$sum_pagado=number_format($sum_pagados, 2, ',', '.'); 
					if ($sum_pagado=="0,00") $tppagado="0,00"; else { $tppagado=(float) (($sum_pagados*100)/$sum_actuals); $tppagado=number_format($tppagado, 2, ',', '.'); }
					$sum_disponible=number_format($sum_disponibles, 2, ',', '.'); 
					if ($sum_disponible=="0,00") $tpdisponible="0,00"; else { $tpdisponible=(float) (($sum_disponibles*100)/$sum_actuals); $tpdisponible=number_format($tpdisponible, 2, ',', '.'); }
					$texto .= "\t\t".$sum_formulado."\t".$sum_actual."\t".$sum_precompromiso."\t".$sum_compromiso."\t".$tpcompromiso."%\t".$sum_causado."\t".$tpcausado."%\t".$sum_pagado."\t".$tppagado."%\t".$sum_disponible."\t".$tpdisponible."%$nl";
				}
				//
				$sum_formulados=0; $sum_actuals=0; $sum_compromisos=0; $sum_precompromisos=0; $sum_causados=0; $sum_pagados=0; $sum_disponibles=0;
				//	
				$texto .= utf8_decode($field["CodCategoria"]." - ".$field["Unidad"])."$nl";
			}
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$formulado=number_format($field["Formulado"], 2, ',', '.');
			$actual=number_format($field["Actual"], 2, ',', '.');
			$compromiso=number_format($field["Compromiso"], 2, ',', '.');
			$precompromiso=number_format($field["PreCompromiso"], 2, ',', '.');
			$causado=number_format($field["Causado"], 2, ',', '.');
			$pagado=number_format($field["Pagado"], 2, ',', '.');
			$disponible=number_format(($field["Actual"]-$field["Compromiso"]-$field["PreCompromiso"]-$field["ReservadoDisminuir"]), 2, ',', '.');
			if ($field["Compromiso"]==0 || $field["Actual"]==0) $pcompromiso="0"; else $pcompromiso=(float) (($field["Compromiso"]*100)/$field["Actual"]); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
			if ($field["Causado"]==0 || $field["Actual"]==0) $pcausado="0"; else $pcausado=(float) (($field["Causado"]*100)/$field["Actual"]); $pcausado=number_format($pcausado, 2, ',', '.');
			if ($field["Pagado"]==0 || $field["Actual"]==0) $ppagado="0"; else $ppagado=(float) (($field["Pagado"]*100)/$field["Actual"]); $ppagado=number_format($ppagado, 2, ',', '.');
			if (($field["Actual"]-$field["Compromiso"]-$field["PreCompromiso"]-$field["ReservadoDisminuir"])==0 || $field["Actual"]==0) $pdisponible="0"; else $pdisponible=(float) ((($field["Actual"]-$field["Compromiso"]-$field["PreCompromiso"]-$field["ReservadoDisminuir"])*100)/$field["Actual"]); 
			$pdisponible=number_format($pdisponible, 2, ',', '.');
			$sum_formulados+=$field["Formulado"];
			$sum_actuals+=$field["Actual"];
			$sum_compromisos+=$field["Compromiso"];
			$sum_precompromisos+=$field["PreCompromiso"];
			$sum_causados+=$field["Causado"];
			$sum_pagados+=$field["Pagado"];
			$sum_disponibles+=($field["Actual"]-$field["Compromiso"]);
			$texto .= $clasificador."\t".utf8_decode($field["NomPartida"])."\t".$formulado."\t".$actual."\t".$precompromiso."\t".$compromiso."\t".$pcompromiso."%\t".$causado."\t".$pcausado."%\t".$pagado."\t".$ppagado."%\t".$disponible."\t".$pdisponible."%$nl";
		}
		//	IMPRIMO LOS TOTALES
		$sum_formulado=number_format($sum_formulados, 2, ',', '.'); 
		$sum_actual=number_format($sum_actuals, 2, ',', '.');
		$sum_compromiso=number_format($sum_compromisos, 2, ',', '.'); 
		$sum_precompromiso=number_format($sum_precompromisos, 2, ',', '.'); 
		if ($sum_compromiso=="0,00") $tpcompromiso="0,00";  else { $tpcompromiso=(float) (($sum_compromisos*100)/$sum_actuals); $tpcompromiso=number_format($tpcompromiso, 2, ',', '.'); }
		$sum_causado=number_format($sum_causados, 2, ',', '.'); 
		if ($sum_causado=="0,00") $tpcausado="0,00"; else { $tpcausado=(float) (($sum_causados*100)/$sum_actuals); $tpcausado=number_format($tpcausado, 2, ',', '.'); }
		$sum_pagado=number_format($sum_pagados, 2, ',', '.'); 
		if ($sum_pagado=="0,00") $tppagado="0,00"; else { $tppagado=(float) (($sum_pagados*100)/$sum_actuals); $tppagado=number_format($tppagado, 2, ',', '.'); }
		$sum_disponible=number_format($sum_disponibles, 2, ',', '.'); 
		if ($sum_disponible=="0,00") $tpdisponible="0,00"; else { $tpdisponible=(float) (($sum_disponibles*100)/$sum_actuals); $tpdisponible=number_format($tpdisponible, 2, ',', '.'); }
		$texto .= "\t\t".$sum_formulado."\t".$sum_actual."\t".$sum_precompromiso."\t".$sum_compromiso."\t".$tpcompromiso."%\t".$sum_causado."\t".$tpcausado."%\t".$sum_pagado."\t".$tppagado."%\t".$sum_disponible."\t".$tpdisponible."%$nl";
		fwrite($archivo, $texto);
		fclose($archivo);
		break;
		
	//	Consolidado por Sector...
	case "consolidado_sector":
		$sql="SELECT denominacion As TipoPresupuesto, (SELECT denominacion FROM fuente_financiamiento WHERE idfuente_financiamiento='".$financiamiento."') AS FuenteFinanciamiento FROM tipo_presupuesto WHERE idtipo_presupuesto='".$tipo_presupuesto."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		$nom_fuente_financiamiento=$field['FuenteFinanciamiento'];
		$nom_tipo_presupuesto=$field['TipoPresupuesto'];
		//---------------------------------------------
		$texto .= "Fuente de Financiamiento:\t".$nom_fuente_financiamiento."$nl";
		$texto .= "Tipo de Presupuesto:\t".$nom_tipo_presupuesto."$nl";
		$texto .= utf8_decode("Año Fiscal:\t".$anio_fiscal."$nl");
		if ($_GET["idsector"]!=0) $filtro="AND (sector.idsector='".$_GET["idsector"]."')";
		////////////
		//	CONSUTO PARA OBTENER TODOS LOS REGISTROS DE MAESTRO PRESUPUESTO	O LAS QUE HAYA EL USUARIO SELECCIONADO DEL FILTRO
		$sql="SELECT maestro_presupuesto.idcategoria_programatica AS IdCategoria, 
					 maestro_presupuesto.idclasificador_presupuestario AS idPartida,
					 categoria_programatica.codigo AS CodCategoria, 
					 unidad_ejecutora.denominacion AS Unidad, 
					 clasificador_presupuestario.partida AS Par,
					 '00' AS Gen, 
					 '00' AS Esp, 
					 '00' AS Sesp, 
					 categoria_programatica.idsector AS IdSector, 
					 sector.codigo AS CodSector, 
					 sector.denominacion AS Sector,
					 (SELECT clasificador_presupuestario.denominacion 
						FROM clasificador_presupuestario
						WHERE 
						(clasificador_presupuestario.partida=Par AND 
						clasificador_presupuestario.generica='00' AND 
						clasificador_presupuestario.especifica='00' AND 
						clasificador_presupuestario.sub_especifica='00')) AS NomPartida, 
					 maestro_presupuesto.idRegistro AS IdPresupuesto,
					 SUM(maestro_presupuesto.monto_original) AS Formulado, 
					 SUM(maestro_presupuesto.monto_actual) AS Actual,
					 SUM(maestro_presupuesto.total_causados) AS Causado, 
					 SUM(maestro_presupuesto.total_pagados) AS Pagado, 
					 SUM(maestro_presupuesto.total_compromisos) AS Compromiso, 
					 SUM(maestro_presupuesto.pre_compromiso) AS PreCompromiso, 
					 SUM(maestro_presupuesto.reservado_disminuir) AS ReservadoDisminuir
				FROM 
					 maestro_presupuesto, 
					 categoria_programatica, 
					 unidad_ejecutora, 
					 clasificador_presupuestario, 
					 sector
				WHERE 
					 ((maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica) AND
					 (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND 
					 (categoria_programatica.idsector=sector.idsector) AND 
					 (maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario) AND 
					 (maestro_presupuesto.anio='".$anio_fiscal."' AND 
					 maestro_presupuesto.idfuente_financiamiento='".$financiamiento."' AND 
					 maestro_presupuesto.idtipo_presupuesto='".$tipo_presupuesto."') $filtro)
				GROUP BY (IdSector), (Par), (Gen), (Esp), (Sesp)
				ORDER BY IdSector, Par, Gen, Esp, Sesp";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//	SI CAMBIA DE SECTOR LA IMPRIMO
			if ($field["IdSector"]!=$IdSector) {
				$IdSector=$field["IdSector"];
				$l=1;
				if ($i!=0) {
					//	IMPRIMO LOS TOTALES
					$sum_formulado=number_format($sum_formulados, 2, ',', '.'); 
					$sum_actual=number_format($sum_actuals, 2, ',', '.');
					$sum_compromiso=number_format($sum_compromisos, 2, ',', '.'); 
					$sum_precompromiso=number_format($sum_precompromisos, 2, ',', '.'); 
					if ($sum_compromiso=="0,00") $tpcompromiso="0,00";  else { $tpcompromiso=(float) (($sum_compromisos*100)/$sum_actuals); $tpcompromiso=number_format($tpcompromiso, 2, ',', '.'); }
					$sum_causado=number_format($sum_causados, 2, ',', '.'); 
					if ($sum_causado=="0,00") $tpcausado="0,00"; else { $tpcausado=(float) (($sum_causados*100)/$sum_actuals); $tpcausado=number_format($tpcausado, 2, ',', '.'); }
					$sum_pagado=number_format($sum_pagado, 2, ',', '.'); 
					if ($sum_pagado=="0,00") $tppagado="0,00"; else { $tppagado=(float) (($sum_pagados*100)/$sum_actuals); $tppagado=number_format($tppagado, 2, ',', '.'); }
					$sum_disponible=number_format($sum_disponibles, 2, ',', '.'); 
					if ($sum_disponible=="0,00") $tpdisponible="0,00"; else { $tpdisponible=(float) (($sum_disponibles*100)/$sum_actuals); $tpdisponible=number_format($tpdisponible, 2, ',', '.'); }
					$texto .= "\t\t".$sum_formulado."\t".$sum_actual."\t".$sum_precompromiso."\t".$sum_compromiso."\t".$tpcompromiso."%\t".$sum_causado."\t".$tpcausado."%\t".$sum_pagado."\t".$tppagado."%\t".$sum_disponible."\t".$tpdisponible."%$nl";
				}
				$sum_formulados=0; $sum_actuals=0; $sum_compromisos=0; $sum_precompromisos=0; $sum_causados=0; $sum_pagados=0; $sum_disponibles=0;
				$texto .= utf8_decode($field["CodSector"]." - ".$field["Sector"])."$nl";
			}
			$clasificador=$field["Par"].".".$field["Gen"].".".$field["Esp"].".".$field["Sesp"];
			$formulado=number_format($field["Formulado"], 2, ',', '.');
			$actual=number_format($field["Actual"], 2, ',', '.');
			$compromiso=number_format($field["Compromiso"], 2, ',', '.');
			$precompromiso=number_format($field["PreCompromiso"], 2, ',', '.');
			$causado=number_format($field["Causado"], 2, ',', '.');
			$pagado=number_format($field["Pagado"], 2, ',', '.');
			$disponible=number_format(($field["Actual"]-$field["Compromiso"]-$field["PreCompromiso"]-$field["ReservadoDisminuir"]), 2, ',', '.');
			if ($field["Compromiso"]==0 || $field["Actual"]==0) $pcompromiso="0"; else $pcompromiso=(float) (($field["Compromiso"]*100)/$field["Actual"]); $pcompromiso=number_format($pcompromiso, 2, ',', '.');
			if ($field["Causado"]==0 || $field["Actual"]==0) $pcausado="0"; else $pcausado=(float) (($field["Causado"]*100)/$field["Actual"]); $pcausado=number_format($pcausado, 2, ',', '.');
			if ($field["Pagado"]==0 || $field["Actual"]==0) $ppagado="0"; else $ppagado=(float) (($field["Pagado"]*100)/$field["Actual"]); $ppagado=number_format($ppagado, 2, ',', '.');
			if (($field["Actual"]-$field["Compromiso"]-$field["PreCompromiso"]-$field["ReservadoDisminuir"])==0 || $field["Actual"]==0) $pdisponible="0"; else $pdisponible=(float) ((($field["Actual"]-$field["Compromiso"]-$field["PreCompromiso"]-$field["ReservadoDisminuir"])*100)/$field["Actual"]); 
			$pdisponible=number_format($pdisponible, 2, ',', '.');
			$sum_formulados+=$field["Formulado"];
			$sum_actuals+=$field["Actual"];
			$sum_compromisos+=$field["Compromiso"];
			$sum_precompromisos+=$field["PreCompromiso"];
			$sum_causados+=$field["Causado"];
			$sum_pagados+=$field["Pagado"];
			$sum_disponibles+=($field["Actual"]-$field["Compromiso"]);
			$texto .= $clasificador."\t".utf8_decode($field["NomPartida"])."\t".$formulado."\t".$actual."\t".$precompromiso."\t".$compromiso."\t".$pcompromiso."%\t".$causado."\t".$pcausado."%\t".$pagado."\t".$ppagado."%\t".$disponible."\t".$pdisponible."%$nl";
		}
		//	IMPRIMO LOS TOTALES
		$sum_formulado=number_format($sum_formulados, 2, ',', '.'); 
		$sum_actual=number_format($sum_actuals, 2, ',', '.');
		$sum_compromiso=number_format($sum_compromisos, 2, ',', '.'); 
		$sum_precompromiso=number_format($sum_precompromisos, 2, ',', '.'); 
		if ($sum_compromiso=="0,00") $tpcompromiso="0,00";  else { $tpcompromiso=(float) (($sum_compromisos*100)/$sum_actuals); $tpcompromiso=number_format($tpcompromiso, 2, ',', '.'); }
		$sum_causado=number_format($sum_causados, 2, ',', '.'); 
		if ($sum_causado=="0,00") $tpcausado="0,00"; else { $tpcausado=(float) (($sum_causados*100)/$sum_actuals); $tpcausado=number_format($tpcausado, 2, ',', '.'); }
		$sum_pagado=number_format($sum_pagados, 2, ',', '.'); 
		if ($sum_pagado=="0,00") $tppagado="0,00"; else { $tppagado=(float) (($sum_pagados*100)/$sum_actuals); $tppagado=number_format($tppagado, 2, ',', '.'); }
		$sum_disponible=number_format($sum_disponibles, 2, ',', '.'); 
		if ($sum_disponible=="0,00") $tpdisponible="0,00"; else { $tpdisponible=(float) (($sum_disponibles*100)/$sum_actuals); $tpdisponible=number_format($tpdisponible, 2, ',', '.'); }
		$texto .= "\t\t".$sum_formulado."\t".$sum_actual."\t".$sum_precompromiso."\t".$sum_compromiso."\t".$tpcompromiso."%\t".$sum_causado."\t".$tpcausado."%\t".$sum_pagado."\t".$tppagado."%\t".$sum_disponible."\t".$tpdisponible."%$nl";
		fwrite($archivo, $texto);
		fclose($archivo);
		break;
}
?>