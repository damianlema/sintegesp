<?php
$nombre_archivo = strtr($nombre_archivo, " ", "_"); $nombre_archivo=$nombre_archivo.".xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: filename=\"".$nombre_archivo."\";");
session_start();
set_time_limit(-1);
ini_set("memory_limit", "200M");
extract($_GET);
extract($_POST);
require('../../../conf/conex.php');
Conectarse();
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
$titulo="background-color:#999999; font-size:14px; font-weight:bold;";
$cat="background-color:RGB(225, 255, 255); font-size:10px; font-weight:bold;";
$par="font-size:10px; font-weight:bold;";
$gen="font-size:10px; text-decoration:underline;";
$esp="font-size:10px;";
$total="font-size:11px; font-weight:bold;";
?>

<?php
//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {

	//	Resumen Anual Trabajador
	case "nomina_resumen_anual_trabajador":
	

		if ($idtrabajador != "") $filtro = " AND t.idtrabajador = '".$idtrabajador."'";
		if ($nomina != "0") $filtro .= " AND rtnt.idtipo_nomina = '".$nomina."'";
		if ($centro != "0") $filtro_centro .= " AND t.centro_costo = '".$centro."'";
		//	---------------------------------------
		//	Obtengo los datos de los trabajadores que pertenecen a esta nomina generada...
		$sql = "SELECT
					rgn.idtrabajador,
					t.cedula,
					t.apellidos,
					t.nombres,
					t.fecha_ingreso,
					c.denominacion AS nomcargo,
					c.grado,
					c.paso,
					ue.denominacion AS nomcentro_costo,
					tn.titulo_nomina,
					cp.idcategoria_programatica,
					cp.codigo AS codcentro_costo,
					ue.idunidad_ejecutora,
					no.denominacion AS nomunidad,
					no.codigo AS codunidad
				FROM
					relacion_generar_nomina rgn
					INNER JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					LEFT JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador AND mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal) FROM movimientos_personal WHERE idtrabajador = t.idtrabajador))
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					INNER JOIN generar_nomina gn ON (rgn.idgenerar_nomina = gn.idgenerar_nomina)
					INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina AND rpn.hasta LIKE '".$anio_fiscal."%')
					INNER JOIN relacion_tipo_nomina_trabajador rtnt ON (t.idtrabajador = rtnt.idtrabajador)
					INNER JOIN tipo_nomina tn ON (rtnt.idtipo_nomina = tn.idtipo_nomina)
					LEFT JOIN niveles_organizacionales norg ON (t.centro_costo = norg.idniveles_organizacionales $filtro_centro)
					LEFT JOIN categoria_programatica cp ON (norg.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
					LEFT JOIN niveles_organizacionales no ON (t.idunidad_funcional = no.idniveles_organizacionales)
				WHERE 1 $filtro
				GROUP BY rgn.idtrabajador";
		$query_trabajador = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
			list($a, $m, $d)=SPLIT( '[/.-]', $field_trabajador['fecha_ingreso']); $fingreso = $d."/".$m."/".$a;
			$cedula = number_format($field_trabajador['cedula'], 0, '', '.');
			$apellidos = utf8_decode($field_trabajador['nombres'].' '.$field_trabajador['apellidos']);
			?>
			 <table>
			 	<tr>
	                <th align="left" colspan = "2">Republica Bolivariana de Venezuela</th>
	            </tr>
	             <tr>
	                <th align="left" colspan = "2">Estado Sucre</th>
	            </tr>
	            <tr>
	                <th align="left" colspan = "2">Alcaldia del Municipio Bolivar</th>
	            </tr>
			 	<tr>
	                <td align="right" ></td>
	                <th align="left" colspan = "3"></th>
	            </tr>
	            <tr>
	                <td align="center" colspan = "4" style="background-color:#999999; font-size:16px; font-weight:bold;">RESUMEN ANUAL POR TRABAJADOR</td>
	            </tr>
	             <tr>
	                <td align="center" colspan = "4" style="background-color:#999999; font-size:16px; font-weight:bold;">A&Ntilde;O FISCAL <?=$anio_fiscal?></td>
	            </tr>
	            <tr>
	                <td align="right" ></td>
	                <th align="left" colspan = "3"></th>
	            </tr>
	            <tr>
	                <td align="right">Cedula de Identidad:</td>
	                <th align="left" colspan = "3"><?=$cedula?></th>
	            </tr>
	            <tr>
	                <td align="right">Apellidos y Nombres:</td>
	                <th align="left" colspan = "3"><?=$apellidos?></th>
	            </tr>
	            <tr>
	                <td align="right">Fecha de Ingreso:</td>
	                <th align="left" colspan = "3"><?=$fingreso?></th>
	            </tr>
	            <tr>
	                <td align="right">Cargo:</td>
	                <th align="left" colspan = "3"><?=utf8_decode($field_trabajador['nomcargo'])?></th>
	            </tr>
	            <tr>
	                <td align="right">Unidad Funcional:</td>
	                <th align="left" colspan = "3"><?=utf8_decode($field_trabajador['codunidad'].' '.$field_trabajador['nomunidad'])?></th>
	            </tr>
	            <tr>
	                <td align="right">Centro de Costo:</td>
	                <th align="left" colspan = "3"><?=utf8_decode($field_trabajador['codcentro_costo'].' '.$field_trabajador['nomcentro_costo'])?></th>
	            </tr>
	            <tr>
	                <td align="right" ></td>
	                <th align="left" colspan = "3"></th>
	            </tr>
	            <tr>
	                <td align="right" ></td>
	                <th align="left" colspan = "3"></th>
	            </tr>
	            <tr>
	                <th align="center" colspan = "2" border="1" style="background-color:#E6E6E6; font-size:14px; font-weight:bold;">ASIGNACIONES</th>
	                <th align="center" colspan = "2" border="1" style="background-color:#E6E6E6; font-size:14px; font-weight:bold;"></th>
	            </tr>

        <?
				
			$sql = "(SELECT
						rgn.tabla,
						SUM(rgn.total) As total,
						cn.descripcion,
						rgn.idconcepto
					FROM
						relacion_generar_nomina rgn
						INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN generar_nomina gn ON (rgn.idgenerar_nomina = gn.idgenerar_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
					WHERE
						rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' 
						AND rgn.tabla = 'conceptos_nomina' 
						AND gn.estado <> 'Anulado'
						
					GROUP BY idconcepto)
			
					UNION
					
					(SELECT
						rgn.tabla,
						rgn.total,
						cn.descripcion,
						rgn.idconcepto
					FROM
						relacion_generar_nomina rgn
						INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						INNER JOIN generar_nomina gn ON (rgn.idgenerar_nomina = gn.idgenerar_nomina)
						INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina AND rpn.hasta LIKE '".$anio_fiscal."%')
					WHERE
						rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
						rgn.tabla = 'constantes_nomina' AND
						cn.mostrar = 'si'
						AND gn.estado <> 'Anulado'
					GROUP BY idconcepto)
					";
			$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
			$mostrar=1;

			//	Imprimo las deducciones del trabajador			
			$sql = "SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion,
						rgn.idconcepto
					FROM
						relacion_generar_nomina rgn
						LEFT JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
						INNER JOIN generar_nomina gn ON (rgn.idgenerar_nomina = gn.idgenerar_nomina)
						INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina AND rpn.hasta LIKE '".$anio_fiscal."-%')
						
					WHERE
						rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' 
						AND	rgn.tabla = 'conceptos_nomina'
						
					GROUP BY idconcepto";
			$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
			$sondeducciones = mysql_num_rows($query_deducciones);			
			$mostrard=1; $cuenta_deducciones = 1;
			$field_deducciones = mysql_fetch_array($query_deducciones);


			while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
				if ($nomina != "0"){
					$sql_validar = mysql_query("select * from relacion_concepto_trabajador 
						where idtrabajador = '".$field_trabajador['idtrabajador']."'
								and idtipo_nomina = '".$nomina."'
								and idconcepto = '".$field_asignaciones["idconcepto"]."'")or die(mysql_error());
					$existe = mysql_num_rows($sql_validar);
					if ($existe>=1) {$mostrar=1;}else{$mostrar=0;}
				}
				if ($mostrar == 1){
					?>
					<tr>
		                <td align="left" colspan="2"><?=utf8_decode($field_asignaciones['descripcion'])?></th>
	                	<td align="right"><?=number_format($field_asignaciones['total'], 2, ',', '.')?></td>
	                </tr>
	            	<?
					
					$total_asignaciones+=$field_asignaciones['total'];
				}
			}
			
			?>
				<tr>
	                <th align="left" colspan="2" style="background-color:#BDBDBD; font-size:14px; font-weight:bold;">TOTAL ASIGNACIONES</th>
	                <th align="center" border="1" style="background-color:#BDBDBD; font-size:14px; font-weight:bold;"></th>
                	<th align="right" style="background-color:#BDBDBD; font-size:16px; font-weight:bold;"><?=number_format($total_asignaciones, 2, ',', '.')?></td>
                </tr>
            	
                <tr>
	                <th align="center" colspan = "2" border="1"></th>
	                <th align="center" colspan = "2" border="1"></th>
	            </tr>

			 	<tr>
	                <th align="center" colspan = "2" border="1" style="background-color:#E6E6E6; font-size:14px; font-weight:bold;">DEDUCCIONES</th>
	                <th align="center" colspan = "2" border="1" style="background-color:#E6E6E6; font-size:14px; font-weight:bold;"></th>
	            </tr>

	           <?





			//	Imprimo las deducciones del trabajador			
			$sql = "SELECT
						rgn.tabla,
						SUM(rgn.total) AS total,
						cn.descripcion,
						rgn.idconcepto
					FROM
						relacion_generar_nomina rgn
						LEFT JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
						INNER JOIN generar_nomina gn ON (rgn.idgenerar_nomina = gn.idgenerar_nomina)
						INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina AND rpn.hasta LIKE '".$anio_fiscal."-%')
						
					WHERE
						rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' 
						AND	rgn.tabla = 'conceptos_nomina'
						
					GROUP BY idconcepto";
			$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
			$mostrar=1;
			while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
				if ($nomina != "0"){
					$sql_validar = mysql_query("select * from relacion_concepto_trabajador 
						where idtrabajador = '".$field_trabajador['idtrabajador']."'
								and idtipo_nomina = '".$nomina."'
								and idconcepto = '".$field_deducciones["idconcepto"]."'")or die(mysql_error());
					$existe = mysql_num_rows($sql_validar);
					if ($existe>=1) {$mostrar=1;}else{$mostrar=0;}
				}
				if ($mostrar == 1){
					
					?>
					<tr>
		                <td align="left" colspan="2"><?=utf8_decode($field_deducciones['descripcion'])?></th>
	                	<td align="right"><?=number_format($field_deducciones['total'], 2, ',', '.')?></td>
	                </tr>
	            	<?

					$total_deducciones+=$field_deducciones['total'];
				}
			}
			$total_neto = $total_asignaciones - $total_deducciones;
			?>
				<tr>
	                <th align="left" colspan="2" style="background-color:#BDBDBD; font-size:14px; font-weight:bold;">TOTAL DEDUCCIONES</th>
	                 <th align="center" border="1" style="background-color:#BDBDBD; font-size:14px; font-weight:bold;"></th>
                	<th align="right" style="background-color:#BDBDBD; font-size:16px; font-weight:bold;"><?=number_format($total_deducciones, 2, ',', '.')?></td>
                </tr>
            	
                <tr>
	                <th align="center" colspan = "2" border="1"></th>
	                <th align="center" colspan = "2" border="1"></th>
	            </tr>

			 	<tr>
	                <th align="center" colspan = "2" border="1" style="background-color:#BDBDBD; font-size:16px; font-weight:bold;">TOTAL NETO</th>
	                 <th align="center" border="1" style="background-color:#BDBDBD; font-size:14px; font-weight:bold;"></th>
	                <th align="right" style="background-color:#BDBDBD; font-size:16px; font-weight:bold;"><?=number_format($total_neto, 2, ',', '.')?></td>
	            </tr>

	           <?

			
			
			?>
			</table>
			<?
			$total_asignaciones = 0;
			$total_deducciones = 0;
		}
	break;

























	//	Resumen Detallado de Conceptos
	case "nomina_resumen_detallado_conceptos":
		//	Obtengo el id de la nomina generada....
		$sql = "SELECT idgenerar_nomina FROM generar_nomina WHERE idtipo_nomina = '".$nomina."' AND idperiodo = '".$periodo."' AND estado = 'procesado'";
		$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);
		//	---------------------------------------
		//	Obtengo los datos de la cabecera....
		$query_nivel = ''; $titulo_nivel = '';
		if ($flagunidad == "S" && $unidad != "0") {
			$filtro_cab = "SELECT idniveles_organizacionales as id, codigo, denominacion FROM niveles_organizacionales WHERE idniveles_organizacionales = '".$unidad."'";
			$query_nivel = mysql_query($filtro_cab) or die ($filtro_cab.mysql_error());
		}
		if ($flagcentro == "S" && $centro != "0") { 
			$filtro_cab = "SELECT no.idcategoria_programatica as id, cp.codigo, ue.denominacion
																		FROM
																			categoria_programatica cp
																				INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
																				INNER JOIN niveles_organizacionales no ON (cp.idcategoria_programatica = no.idcategoria_programatica)
																	WHERE cp.idcategoria_programatica = '".$centro."'";
			$query_nivel = mysql_query($filtro_cab) or die ($filtro_cab.mysql_error());
		}
		//$query_nivel = mysql_query($filtro_cab) or die ($filtro_cab.mysql_error());
		if($query_nivel != ''){
			if (mysql_num_rows($query_nivel) != 0) {
				$field_nivel = mysql_fetch_array($query_nivel);
				$titulo_nivel = $field_nivel['codigo']." ".$field_nivel['denominacion'];
			}
		}
		$sql = "SELECT 
					tn.titulo_nomina,
					rpn.desde,
					rpn.hasta
				FROM 
					tipo_nomina tn
					INNER JOIN periodos_nomina pn ON (tn.idtipo_nomina = pn.idtipo_nomina)
					INNER JOIN generar_nomina gn ON (gn.idtipo_nomina = tn.idtipo_nomina AND idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."')
					INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina AND rpn.idrango_periodo_nomina = '".$periodo."')
				WHERE 
					tn.idtipo_nomina = '".$nomina."'";
		$query_titulo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_titulo) != 0) $field_titulo = mysql_fetch_array($query_titulo);
		list($a, $m, $d)=SPLIT( '[/.-]', $field_titulo['desde']); $desde = $d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $field_titulo['hasta']); $hasta = $d."/".$m."/".$a;
		$periodo = "Del $desde al $hasta";
		?>
		 <table>
            <tr>
                <th align="center" colspan = "6">RESUMEN DETALLADO CONCEPTOS</th>
            </tr>
             <tr>
                <td align="right" colspan = "2"></td>
                <th align="left" colspan = "3"></th>
            </tr>
            <tr>
                <td align="right" colspan = "2">TIPO DE NOMINA:</td>
                <th align="left" colspan = "3"><?=$field_titulo["titulo_nomina"]?></th>
            </tr>
            <tr>
                <td align="right" colspan = "2">PERIODO:</td>
                <th align="left" colspan = "3"><?=$periodo?></th>
            </tr>
            <tr>
                <td align="right" colspan = "2">FILTRO:</td>
                <th align="left" colspan = "3"><?=$titulo_nivel?></th>
            </tr>
            <tr>
                <td align="right" colspan = "2"></td>
                <th align="left" colspan = "3"></th>
            </tr>
            <tr>
                <td align="right" colspan = "2"></td>
                <th align="left" colspan = "3"></th>
            </tr>
        </table>


        <?

		//	---------------------------------------
		if ($flagunidad == "S" && $unidad != "0") $filtro_unidad = " AND t.idunidad_funcional = '".$unidad."'";
		if ($flagcentro == "S" && $centro != "0") $filtro_centro = " AND no2.idcategoria_programatica = '".$centro."'";
		//	---------------------------------------
		?>
        <table border="1">
            <tr>
                <th>CEDULA</th>
                <th>NOMBRE</th>
                <?
                $sql = "(SELECT
							rgn.tabla,
							cn.idconceptos_nomina,
							cn.descripcion AS nomconcepto
						FROM
							relacion_generar_nomina rgn
							INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
						WHERE
							rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
							rgn.tabla = 'conceptos_nomina'
						GROUP BY rgn.idconcepto)
						
						UNION
						
						(SELECT
							rgn.tabla,
							cn.idconstantes_nomina AS idconceptos_nomina,
							cn.descripcion AS nomconcepto
						FROM
							relacion_generar_nomina rgn
							INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
						WHERE
							rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
							rgn.tabla = 'constantes_nomina' AND
							cn.mostrar = 'si'
						GROUP BY rgn.idconcepto)";
                $query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
                $rows_asignaciones = mysql_num_rows($query_asignaciones);
                while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
                    $q .= ", (SELECT 
                                    total 
                                FROM 
                                    relacion_generar_nomina
                                WHERE
                                    idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
									idtrabajador = rgn.idtrabajador AND
									idconcepto = '".$field_asignaciones['idconceptos_nomina']."' AND
									tabla = '".$field_asignaciones['tabla']."'
                                ) AS 'ASIGNACION'";
                    ?><th><?=$field_asignaciones['nomconcepto']?></th><?
                }
                ?>
                <th>TOTAL ASIGNACIONES</th>
                
                
                <?
                $sql = "SELECT
							rgn.tabla,
							cn.idconceptos_nomina,
							cn.descripcion AS nomconcepto
						FROM
							relacion_generar_nomina rgn
							INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
						WHERE
							rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
							rgn.tabla = 'conceptos_nomina'
						GROUP BY rgn.idconcepto";
                $query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
                $rows_deducciones = mysql_num_rows($query_deducciones);
                while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
                    $q .= ", (SELECT 
                                    total 
                                FROM 
                                    relacion_generar_nomina
                                WHERE
                                    idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
									idtrabajador = rgn.idtrabajador AND
									idconcepto = '".$field_deducciones['idconceptos_nomina']."' AND
									tabla = '".$field_deducciones['tabla']."'
                                ) AS 'DEDUCCION'";
                    ?><th><?=$field_deducciones['nomconcepto']?></th><?
                }
                ?>
                <th>TOTAL DEDUCCIONES</th>
                <th>TOTAL NETO</th>
            </tr>
            
            
            <?
			

			$sql = "SELECT
					rgn.idtrabajador,
					t.cedula,
					CONCAT(t.nombres, ' ', t.apellidos) AS nomtrabajador
					$q	
				FROM
					relacion_generar_nomina rgn
					INNER JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					INNER JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
					LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
					LEFT JOIN niveles_organizacionales no ON (mp.idubicacion_funcional = no.idniveles_organizacionales)
					LEFT JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
					LEFT JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
					LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
				WHERE
					rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."'  AND 
					mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal) 
												 FROM movimientos_personal 
												 WHERE 
												 	idtrabajador = t.idtrabajador AND
													fecha_movimiento <= '".$field_titulo['hasta']."')
					$filtro_unidad
					$filtro_centro
				GROUP BY rgn.idtrabajador
				ORDER BY length(cedula), cedula";







			$query_empleados = mysql_query($sql) or die ($sql.mysql_error());
			$rows_empleados = mysql_num_rows($query_empleados);
			while ($field_empleados = mysql_fetch_array($query_empleados)) {
				?>
				<tr>
					<td><?=number_format($field_empleados['cedula'], 0 , '', '.')?></td>
					<td><?=$field_empleados['nomtrabajador']?></td>
					<?
					$total = 0;
					for ($i=3; $i<=($rows_asignaciones+2); $i++) {
						$sum_total[$i] += $field_empleados[$i];
						$total_asignaciones += $field_empleados[$i];
						$monto = $field_empleados[$i];
						?><td align="right"><?=number_format($monto, 2, ',', '.')?></td><?
					}
					
					?><th align="right"><?=number_format($total_asignaciones, 2, ',', '.')?></th>
					
					<?
					$total = 0;
					for ($k=$i; $k<=$rows_deducciones+$i-1; $k++) {
						$sum_total[$k] += $field_empleados[$k];
						$total_deducciones += $field_empleados[$k];
						$monto = $field_empleados[$k];
						?><td align="right"><?=number_format($monto, 2, ',', '.')?></td><?
					}
					//$total_deducciones = number_format($total_deducciones, 2, ',', '');
					$total_neto = ($total_asignaciones - $total_deducciones);
					?>
					<th align="right"><?=number_format($total_deducciones, 2, ',', '.')?></th>				
					<th align="right"><?=number_format($total_neto, 2, ',', '.')?></th>
				</tr>
				<?
				$total_asignaciones=0;
				$total_deducciones=0;
			}
			?>
			
			
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<?
				$total = 0;
				for ($i=3; $i<=$rows_asignaciones+2; $i++) {
					$sum_total_asignaciones += $sum_total[$i];
					$total = $sum_total[$i];
					?><th align="right"><?=number_format($total, 2, ',', '.')?></th><?
				}
				//$asignaciones = number_format($sum_total_asignaciones, 2, ',', '');
				?><th align="right"><?=number_format($sum_total_asignaciones, 2, ',', '.')?></th>
				
				<?
				$total = 0;
				for ($k=$i; $k<=$rows_deducciones+$i-1; $k++) {
					$sum_total_deducciones += $sum_total[$k];
					$total = $sum_total[$k];
					?><th align="right"><?=number_format($total, 2, ',', '.')?></th><?
				}
				//$deducciones = number_format($sum_total_deducciones, 2, ',', '');
				$sum_total_neto = ($sum_total_asignaciones - $sum_total_deducciones);
				?>
				<th align="right"><?=number_format($sum_total_deducciones, 2, ',', '.')?></th>				
				<th align="right"><?=number_format($sum_total_neto, 2, ',', '.')?></th>
			</tr>
		</table>
		<?
		break;
		
		
		
		
		
	//	Payroll...
	case "payroll_trabajadores":
		//	---------------------------------------
		//	Obtengo el id de la nomina generada....
		$sql = "SELECT idgenerar_nomina FROM generar_nomina WHERE idtipo_nomina = '".$nomina."' AND idperiodo = '".$periodo."' AND (estado = 'procesado' OR estado = 'Pre Nomina')";
		$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);
		//	---------------------------------------
		//	Obtengo los datos de la cabecera....
		$sql = "SELECT 
					tn.titulo_nomina,
					rpn.desde,
					rpn.hasta
				FROM 
					tipo_nomina tn
					INNER JOIN periodos_nomina pn ON (tn.idtipo_nomina = pn.idtipo_nomina)
					INNER JOIN generar_nomina gn ON (gn.idtipo_nomina = tn.idtipo_nomina AND idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."')
					INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina AND rpn.idrango_periodo_nomina = '".$periodo."')
				WHERE 
					tn.idtipo_nomina = '".$nomina."'
				GROUP BY tn.idtipo_nomina";
		$query_titulo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_titulo) != 0) $field_titulo = mysql_fetch_array($query_titulo);
		list($a, $m, $d)=SPLIT( '[/.-]', $field_titulo['desde']); $desde = $d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $field_titulo['hasta']); $hasta = $d."/".$m."/".$a;
		$periodo = "Del $desde al $hasta";
		//	---------------------------------------
		if ($flagunidad == "S" && $unidad != "0") $filtro_unidad = " AND t.idunidad_funcional = '".$unidad."'";
		if ($flagcentro == "S" && $centro != "0") $filtro_centro = " AND t.centro_costo = '".$centro."'";
		if ($idtrabajador != "") $filtro_trabajador = " AND rgn.idtrabajador = '".$idtrabajador."'";
		//	---------------------------------------
		//	Obtengo los datos de los trabajadores que pertenecen a esta nomina generada...
		$sql = "SELECT
					rgn.idtrabajador,
					t.cedula,
					t.apellidos,
					t.nombres,
					t.fecha_ingreso,
					t.nro_ficha,
					c.denominacion AS nomcargo,
					c.grado,
					c.paso,
					t.centro_costo,
					t.idunidad_funcional,
					no.denominacion AS nomunidad,
					no.codigo AS codunidad,
					ue.denominacion AS nomcentro,
					cp.codigo AS codcentro
				FROM
					relacion_generar_nomina rgn
					INNER JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
					INNER JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
					INNER JOIN cargos c ON (mp.idcargo = c.idcargo)
					INNER JOIN niveles_organizacionales no ON (mp.idubicacion_funcional = no.idniveles_organizacionales)
					INNER JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
					INNER JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
					INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
				WHERE
					rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."'  AND 
					mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal) 
												 FROM movimientos_personal 
												 WHERE 
												 	idtrabajador = t.idtrabajador AND
													fecha_movimiento <= '".$field_titulo['hasta']."')
					$filtro_unidad
					$filtro_centro
					$filtro_trabajador
				GROUP BY rgn.idtrabajador
				ORDER BY codcentro, length(cedula), cedula";
		$query_trabajador = mysql_query($sql) or die ($sql.mysql_error()); $i=0;
		$nro_trabajadores = mysql_num_rows($query_trabajador); $sw=0;
		while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
			list($a, $m, $d)=SPLIT( '[/.-]', $field_trabajador['fecha_ingreso']); $fingreso = $d."/".$m."/".$a;
			
			$sql = mysql_query("SELECT 
						cn.descripcion,
						rct.valor
					FROM
						constantes_nomina cn
						INNER JOIN relacion_concepto_trabajador rct ON (cn.idconstantes_nomina = rct.idconcepto AND rct.tabla = 'constantes_nomina')
						INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'Neutro')
						INNER JOIN movimientos_personal mp ON (mp.idtrabajador = rct.idtrabajador)
					WHERE 
						cn.mostrar = 'si' AND
						rct.valor > 0 AND
						mp.idtrabajador = '".$field_trabajador['idtrabajador']."'  AND 
						mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal) 
													 FROM movimientos_personal 
													 WHERE 
														idtrabajador = mp.idtrabajador AND
														fecha_movimiento <= '".$field_titulo['hasta']."')
					LIMIT 0,1");
			$bus_consulta_sueldo = mysql_fetch_array($sql);
			?>
            
            <!--	IMPRIMO LOS DATOS DEL TRABAJADOR	-->
            <table style="font-size:13px;">
            <tr><td colspan="2" align="center" style="height:20px;"><strong>Alcaldia del Municipio Tucupita</strong></td></tr>
            <tr><td colspan="2" align="center" style="height:20px;"><strong><?=$field_titulo['titulo_nomina']?></strong></td></tr>
            <tr><td colspan="2" align="center" style="height:20px;"><strong><?=$periodo?></strong></td></tr>
            <tr>
                <td colspan="2" style="height:20px;">
					<?=utf8_decode('Cedula: '.number_format($field_trabajador['cedula'], 0, '', '.'))?>
                     &nbsp; &nbsp;
                	<?=utf8_decode('Nro Ficha: '.$field_trabajador['nro_ficha'])?>
                     &nbsp; &nbsp;
                	<?=utf8_decode("F. Ingreso: ".$fingreso)?>
                     &nbsp; &nbsp;
                	<?=utf8_decode('Nombres: '.$field_trabajador['nombres'].' '.$field_trabajador['apellidos'])?>
				</td>                    
            </tr>
            <tr>
                <td colspan="2" style="height:20px;">
					<?=utf8_decode('Cargo: '.substr($field_trabajador['nomcargo'], 0, 70))?>
				</td>
            </tr>
            <tr>
                <td colspan="2" style="height:20px;">
					<?=utf8_decode('Centro de Costo: '.$field_trabajador['codcentro'].' '.$field_trabajador['nomcentro'])?>
				</td>
            </tr>
            <tr>
                <td colspan="2" style="height:20px;">
                	<?=utf8_decode('Unidad Funcional: '.$field_trabajador['codunidad'].' '.substr($field_trabajador['nomunidad'], 0, 70))?>
				</td>
            </tr>
            <tr>
                <td colspan="2" style="height:20px;">
                	<?=utf8_decode($bus_consulta_sueldo['descripcion'].': '.number_format($bus_consulta_sueldo['valor'],2,",","."))?>
				</td>
            </tr>
            <tr style="border-bottom:#000 solid;">
                <td style="height:20px;">ASIGNACIONES</td>
                <td style="height:20px;">DEDUCCIONES</td>
            </tr>
            <tr>
                <td width="1500">
                    <!--	IMPRIMO LAS ASIGNACIONES	-->
                    <table style="font-size:13px;">
                    <?
					$total_asignaciones = 0;
					//	Imprimo las asignaciones del trabajador			
					$sql = "(SELECT
								rgn.tabla,
								rgn.total,
								rgn.cantidad,
								cn.descripcion
							FROM
								relacion_generar_nomina rgn
								INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
							WHERE
								rgn.total > 0 AND
								rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
								rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
								rgn.tabla = 'conceptos_nomina')
					
							UNION
							
							(SELECT
								rgn.tabla,
								rgn.total,
								rgn.cantidad,
								cn.descripcion
							FROM
								relacion_generar_nomina rgn
								INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
							WHERE
								rgn.total > 0 AND
								rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
								rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
								rgn.tabla = 'constantes_nomina')";
					$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
					$rows_asignaciones = mysql_num_rows($query_asignaciones);
					while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {//for($k=1;$k<100;$k++){
						if ($field_asignaciones['cantidad'] > 0) $cantidad = "(".$field_asignaciones['cantidad'].")";
						else $cantidad = "";
						?>
                         <tr>
                            <td style="height:20px;"><?=$k?> <?=utf8_decode($field_asignaciones['descripcion'])?></td>
                            <td align="right" style="height:20px;">&nbsp;<?=number_format($field_asignaciones['total'], 2, ',', '.')?></td>
                        </tr>
                        <?
						$total_asignaciones+=$field_asignaciones['total'];//}
					} $cantidad = "";
					?>
                    </table>
                </td>
               <td>
                    <!--	IMPRIMO LAS DEDUCCIONES	-->
                    <table style="font-size:13px;">
                    <?
					$total_deducciones = 0;
					//	Imprimo las deducciones del trabajador	
					$sql = "(SELECT
								rgn.tabla,
								rgn.total,
								rgn.cantidad,
								cn.descripcion
							FROM
								relacion_generar_nomina rgn
								LEFT JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'deduccion')
							WHERE
								total > 0 AND
								rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
								rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
								rgn.tabla = 'conceptos_nomina'
							GROUP BY rgn.idconcepto)
					
							UNION
					
							(SELECT
								rgn.tabla,
								rgn.total,
								rgn.cantidad,
								cn.descripcion
							FROM
								relacion_generar_nomina rgn
								INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'deduccion')
							WHERE
								total > 0 AND
								rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
								rgn.idgenerar_nomina = '".$field_nomina['idgenerar_nomina']."' AND
								rgn.tabla = 'constantes_nomina')";
					$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
					$rows_deducciones = mysql_num_rows($query_deducciones);
					while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
						if ($field_deducciones['cantidad'] > 0) $cantidad = "(".$field_deducciones['cantidad'].")";
						else $cantidad = "";
						?>
                         <tr>
                            <td style="height:20px;"><?=utf8_decode($field_deducciones['descripcion'])?></td>
                            <td align="right" style="height:20px;">&nbsp;<?=number_format($field_deducciones['total'], 2, ',', '.')?></td>
                        </tr>
                        <?
						$total_deducciones+=$field_deducciones['total'];
					} $cantidad = "";
					?>
                    </table>
                </td>
            </tr>
            
            
            
            <tr style="border-top:#000 solid;">
                <td>
                    <table style="font-size:13px;">
                         <tr>
                            <td style="height:20px;"><?=utf8_decode('TOTAL ASIGNACIONES')?></td>
                            <td align="right" style="height:20px;">&nbsp;<?=number_format($total_asignaciones, 2, ',', '.')?></td>
                        </tr>
                    </table>
                </td>
               <td>
                    <table style="font-size:13px;">
                         <tr>
                            <td style="height:20px;"><?=utf8_decode('TOTAL DEDUCCIONES')?></td>
                            <td align="right" style="height:20px;">&nbsp;<?=number_format($total_deducciones, 2, ',', '.')?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            
            
            <tr style="border-top:#000 solid;">
                <td>
                    <table>
                         <tr>
                            <td style="height:20px;"></td>
                            <td align="right" style="height:20px;"></td>
                        </tr>
                    </table>
                </td>
               <td>
                    <table style="font-size:13px;">
                         <tr>
                            <td style="height:20px;"><?=utf8_decode('TOTAL NETO')?> <? $total_neto = $total_asignaciones-$total_deducciones; ?></td>
                            <td align="right" style="height:20px;">&nbsp;<?=number_format($total_neto, 2, ',', '.')?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <?
			if ($rows_asignaciones > $rows_deducciones) $rows = $rows_asignaciones; else $rows = $rows_deducciones;
			
			$restante = 23 - (10 + $rows);
			
			for ($i=1; $i<=$restante; $i++) {
				?><tr><td colspan="2" style="height:20px;"></td></tr><?
			}
			?>
            
            
            </table>
		<?
		}
		
		break;
}
?>