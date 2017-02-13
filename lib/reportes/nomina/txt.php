<?php
session_start();
header('Content-Type: text/html; charset=iso-8859-1');
set_time_limit(-1);
require '../../../conf/conex.php';
Conectarse();
extract($_GET);
extract($_POST);
$ahora          = date("d-m-Y H:i:s");
$nombre_archivo = strtr($nombre_archivo, " ", "_");
header("Content-Disposition: filename=\"" . $nombre_archivo . ".txt\";");
$texto   = "";
$archivo = fopen($nombre_archivo . ".txt", "w+");
//---------------
$LF = 0x0A;
$CR = 0x0D;
$nl = sprintf("%c%c", $CR, $LF);
//---------------

//    ----------------------------------------
//    CUERPO DE LOS REPORTES
//    ----------------------------------------
switch ($nombre) {
    //    Generar diskette banco...
    case "generar_diskette_banco_venezuela":
        list($ano, $mes, $dia) = split("[-]", $fecha);
        $a                     = substr($ano, 2, 2);
        $fecha_nomina          = "$dia/$mes/$a";
        $texto                 = "";
        $sum_total             = 0;

        //    imprimo cada una de las nominas
        $detalles = split(";", $nominas);
        $con      = 0;
        foreach ($detalles as $detalle) {
            $con++;
            list($nomina, $periodo) = split("[|]", $detalle);
            //    Obtengo el banco y la cuenta
            $sql = "SELECT
						b.denominacion AS banco,
						cb.numero_cuenta,
						cb.idtipo_cuenta
					FROM
						cuentas_bancarias cb
						INNER JOIN banco b ON (cb.idbanco = b.idbanco)
					WHERE cb.idcuentas_bancarias = '" . $cuenta . "'";
            $query_banco = mysql_query($sql) or die($sql . mysql_error());
            if (mysql_num_rows($query_banco) != 0) {
                $field_banco = mysql_fetch_array($query_banco);
            }

            //    ---------------------------------------
            //    Obtengo el nombre de la institucion
            $sql               = "SELECT * FROM configuracion LIMIT 0, 1";
            $query_institucion = mysql_query($sql) or die($sql . mysql_error());
            if (mysql_num_rows($query_institucion) != 0) {
                $field_institucion = mysql_fetch_array($query_institucion);
            }

            //    ---------------------------------------
            //    Obtengo el id de la nomina generada....
            $sql = "SELECT idgenerar_nomina
					FROM generar_nomina
					WHERE
						idtipo_nomina = '" . $nomina . "' AND
						idperiodo = '" . $periodo . "' AND
						(estado = 'procesado' OR estado = 'Pre Nomina')";
            $query_nomina = mysql_query($sql) or die($sql . mysql_error());
            if (mysql_num_rows($query_nomina) != 0) {
                $field_nomina = mysql_fetch_array($query_nomina);
            }

            //    ---------------------------------------
            //    Obtengo los datos de la cabecera....
            $sql = "SELECT
						tn.titulo_nomina,
						rpn.desde,
						rpn.hasta,
						tn.motivo_cuenta
					FROM
						tipo_nomina tn
						INNER JOIN periodos_nomina pn ON (tn.idtipo_nomina = pn.idtipo_nomina)
						INNER JOIN generar_nomina gn ON (gn.idtipo_nomina = tn.idtipo_nomina AND idgenerar_nomina = '" . $field_nomina['idgenerar_nomina'] . "')
						INNER JOIN rango_periodo_nomina rpn ON (gn.idperiodo = rpn.idrango_periodo_nomina AND rpn.idrango_periodo_nomina = '" . $periodo . "')
					WHERE tn.idtipo_nomina = '" . $nomina . "'
					GROUP BY tn.idtipo_nomina";
            $query_titulo = mysql_query($sql) or die($sql . mysql_error());
            if (mysql_num_rows($query_titulo) != 0) {
                $field_titulo = mysql_fetch_array($query_titulo);
            }

            list($a, $m, $d) = SPLIT('[/.-]', $field_titulo['desde']);
            $desde           = $d . "/" . $m . "/" . $a;
            list($a, $m, $d) = SPLIT('[/.-]', $field_titulo['hasta']);
            $hasta           = $d . "/" . $m . "/" . $a;
            $periodo         = "Del $desde al $hasta";
            //    ---------------------------------------
            if ($flagunidad == "S" && $unidad != "0") {
                $filtro_unidad = " AND t.idunidad_funcional = '" . $unidad . "'";
            }

            if ($flagcentro == "S" && $centro != "0") {
                $filtro_centro = " AND t.centro_costo = '" . $centro . "'";
            }

            //    ---------------------------------------
            if ($con == 1) {
                $institucion        = substr(utf8_decode(trim($field_institucion['nombre_institucion'])), 0, 40);
                $nombre_institucion = (string) $institucion . str_repeat(" ", 40 - strlen($institucion));
                $numero_cuenta      = $field_banco['numero_cuenta'];
            }
            //    ---------------------------------------
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
						cp.codigo AS codcentro,
						cbt.nro_cuenta,
						cbt.tipo As tipo_cuenta
					FROM
						relacion_generar_nomina rgn
						LEFT JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)
						LEFT JOIN movimientos_personal mp ON (t.idtrabajador = mp.idtrabajador)
						LEFT JOIN cargos c ON (mp.idcargo = c.idcargo)
						LEFT JOIN niveles_organizacionales no ON (mp.idubicacion_funcional = no.idniveles_organizacionales)
						LEFT JOIN niveles_organizacionales no2 ON (mp.centro_costo = no2.idniveles_organizacionales)
						LEFT JOIN categoria_programatica cp ON (no2.idcategoria_programatica = cp.idcategoria_programatica)
						LEFT JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
						INNER JOIN cuentas_bancarias_trabajador cbt ON (t.idtrabajador = cbt.idtrabajador AND cbt.motivo = '" . $field_titulo['motivo_cuenta'] . "')
					WHERE
						rgn.idgenerar_nomina = '" . $field_nomina['idgenerar_nomina'] . "'  AND
						mp.idmovimientos_personal = (SELECT MAX(idmovimientos_personal)
													 FROM movimientos_personal
													 WHERE
														idtrabajador = t.idtrabajador AND
														fecha_movimiento <= '" . $field_titulo['hasta'] . "')
						$filtro_unidad
						$filtro_centro
					GROUP BY rgn.idtrabajador
					ORDER BY apellidos, nombres, length(cedula), cedula";

            $query_trabajador = mysql_query($sql) or die($sql . mysql_error());
            while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
                $sql = "SELECT
							SUM(rgn.total) AS total_conceptos,
							(SELECT SUM(rgn.total)
							 FROM
								relacion_generar_nomina rgn
								INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
							 WHERE
								rgn.total > 0 AND
								rgn.idgenerar_nomina = '" . $field_nomina['idgenerar_nomina'] . "' AND
								rgn.idtrabajador = '" . $field_trabajador['idtrabajador'] . "' AND
								rgn.tabla = 'constantes_nomina') AS total_constantes
						FROM
							relacion_generar_nomina rgn
							INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
						WHERE
							rgn.total > 0 AND
							rgn.idgenerar_nomina = '" . $field_nomina['idgenerar_nomina'] . "' AND
							rgn.idtrabajador = '" . $field_trabajador['idtrabajador'] . "' AND
							rgn.tabla = 'conceptos_nomina'
						GROUP BY rgn.idtrabajador";

                $query_asignaciones = mysql_query($sql) or die($sql . mysql_error());
                $field_asignaciones = mysql_fetch_array($query_asignaciones);

                $sql_concepto = "SELECT
							SUM(rgn.total) AS total_conceptos
						FROM
							relacion_generar_nomina rgn
							LEFT JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'Deduccion')
						WHERE
							total > 0 AND
							rgn.idtrabajador = '" . $field_trabajador['idtrabajador'] . "' AND
							rgn.idgenerar_nomina = '" . $field_nomina['idgenerar_nomina'] . "' AND
							rgn.tabla = 'conceptos_nomina'
						GROUP BY rgn.idtrabajador";
                $sql_constante = "SELECT SUM(rgn.total) AS total_constantes
							 FROM
								relacion_generar_nomina rgn
								INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'Deduccion')
							 WHERE
								total > 0 AND
								rgn.idtrabajador = '" . $field_trabajador['idtrabajador'] . "' AND
								rgn.idgenerar_nomina = '" . $field_nomina['idgenerar_nomina'] . "' AND
								rgn.tabla = 'constantes_nomina'
						GROUP BY rgn.idtrabajador";
                //if ($field_trabajador['idtrabajador']==17){ echo $sql;}
                $query_deducciones_concepto = mysql_query($sql_concepto) or die($sql_concepto . mysql_error());
                $field_deducciones_concepto = mysql_fetch_array($query_deducciones_concepto);

                $query_deducciones_constante = mysql_query($sql_constante) or die($sql_constante . mysql_error());
                $field_deducciones_constante = mysql_fetch_array($query_deducciones_constante);

                $total_asignaciones = $field_asignaciones['total_conceptos'] + $field_asignaciones['total_constantes'];
                $total_deducciones  = $field_deducciones_concepto['total_conceptos'] + $field_deducciones_constante['total_constantes'];
                $total              = $total_asignaciones - $total_deducciones;
                $sum_total += $total;

                if ($total > 0) {
                    if ($field_trabajador['tipo_cuenta'] == "Corriente") {
                        $tipo_cuenta = "0";
                        $relleno_1   = "0770";} elseif ($field_trabajador['tipo_cuenta'] == "Ahorro") {
                        $tipo_cuenta = "1";
                        $relleno_1   = "1770";}
                    $nro_cuenta = $field_trabajador['nro_cuenta'];
                    //--
                    $monto_total = number_format($total, 2, '', '');
                    $monto       = (string) str_repeat("0", 11 - strlen($monto_total)) . $monto_total;
                    //--
                    $nom    = substr(utf8_decode(trim($field_trabajador['nombres']) . " " . trim($field_trabajador['apellidos'])), 0, 40);
                    $nombre = (string) $nom . str_repeat(" ", 40 - strlen($nom));
                    //--
                    $cedula = (string) str_repeat("0", 10 - strlen($field_trabajador['cedula'])) . $field_trabajador['cedula'];
                    //--
                    $relleno_2 = "003291";
                    //--
                    $texto .= $tipo_cuenta . $nro_cuenta . $monto . $relleno_1 . $nombre . $cedula . $relleno_2 . "$nl";
                }
            }
        }
        $total_general = number_format($sum_total, 2, '', '');
        $total         = (string) str_repeat("0", 13 - strlen($total_general)) . $total_general;
        $buffer        = "H" . $nombre_institucion . $numero_cuenta . "01" . $fecha_nomina . $total . "03291 $nl" . $texto;
        fwrite($archivo, $buffer);
        fclose($archivo);
        break;
}
