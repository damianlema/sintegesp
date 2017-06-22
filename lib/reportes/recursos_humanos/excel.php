<?php
extract($_GET);
extract($_POST);
$nombre_archivo = strtr($nombre_archivo, " ", "_");
$nombre_archivo = $nombre_archivo . ".xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: filename=\"" . $nombre_archivo . "\";");
session_start();
set_time_limit(-1);
ini_set("memory_limit", "200M");
require '../../../conf/conex.php';
Conectarse();
//    ----------------------------------------------------
$titulo     = "background-color:#999999; font-size:10px; font-weight:bold;";
$esp        = "font-size:10px;";
$total      = "font-size:10px; font-weight:bold;";
$cat        = "font-size:10px; font-weight:bold;";
$sql_config = mysql_query("select * from configuracion, estado where estado.idestado=configuracion.estado");
$config     = mysql_fetch_array($sql_config);
$ahora      = date("d-m-Y H:i:s");
//    ----------------------------------------------------
$nom_mes['01'] = "Enero";
$nom_mes['02'] = "Febrero";
$nom_mes['03'] = "Marzo";
$nom_mes['04'] = "Abril";
$nom_mes['05'] = "Mayo";
$nom_mes['06'] = "Junio";
$nom_mes['07'] = "Julio";
$nom_mes['08'] = "Agosto";
$nom_mes['09'] = "Septiembre";
$nom_mes['10'] = "Octubre";
$nom_mes['11'] = "Noviembre";
$nom_mes['12'] = "Diciembre";
//    ----------------------------------------------------
$dias_mes['01'] = 31;
$dias_mes['03'] = 31;
$dias_mes['04'] = 30;
$dias_mes['05'] = 31;
$dias_mes['06'] = 30;
$dias_mes['07'] = 31;
$dias_mes['08'] = 31;
$dias_mes['09'] = 30;
$dias_mes['10'] = 31;
$dias_mes['11'] = 30;
$dias_mes['12'] = 31;

//    ----------------------------------------------------
$titulo = "background-color:#999999; font-size:10px; font-weight:bold;";
$cat    = "background-color:RGB(225, 255, 255); font-size:10px; font-weight:bold;";
$par    = "font-size:10px; font-weight:bold;";
$gen    = "font-size:10px; text-decoration:underline;";
$esp    = "font-size:10px;";
$total  = "font-size:14px; font-weight:bold;";
$tr1    = "background-color:#999999; font-size:12px;";
$tr2    = "font-size:12px; color:#000000; font-weight:bold;";
$tr3    = "background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
$tr4    = "background-color:#999950; font-size:12px; color:#000000; font-weight:bold;";
$tr5    = "font-size:12px;";
$tr6    = "background-color:#999999; font-size:12px; border:1px solid #000000;";
$tr7    = "font-size:12px; border:1px solid #000000;";
?>

<?php

function diferenciaEntreDosFechas($fechaInicio, $fechaActual)
{
    list($anioInicio, $mesInicio, $diaInicio) = explode("-", $fechaInicio);
    list($anioActual, $mesActual, $diaActual) = explode("-", $fechaActual);

    $b   = 0;
    $mes = $mesInicio - 1;
    if ($mes == 2) {
        if (($anioActual % 4 == 0 && $anioActual % 100 != 0) || $anioActual % 400 == 0) {
            $b = 29;
        } else {
            $b = 28;
        }
    } else if ($mes <= 7) {
        if ($mes == 0) {
            $b = 31;
        } else if ($mes % 2 == 0) {
            $b = 30;
        } else {
            $b = 31;
        }
    } else if ($mes > 7) {
        if ($mes % 2 == 0) {
            $b = 31;
        } else {
            $b = 30;
        }
    }
    if (($anioInicio > $anioActual) || ($anioInicio == $anioActual && $mesInicio > $mesActual) ||
        ($anioInicio == $anioActual && $mesInicio == $mesActual && $diaInicio > $diaActual)) {
        //echo "A�o incio ".$anioInicio." A�o Actual ".$anioActual." Mes Inicio ".$mesInicio." Mes actual ".$mesActual." Dia Inicio ".$diaInicio." Dia Actual ".$diaActual."La fecha de inicio ha de ser anterior a la fecha Actual";
    } else {
        //echo "A�o incio ".$anioInicio." A�o Actual ".$anioActual." Mes Inicio ".$mesInicio." Mes actual ".$mesActual." Dia Inicio ".$diaInicio." Dia Actual ".$diaActual."La fecha de inicio ha de ser anterior a la fecha Actual <br>";
        if ($mesInicio <= $mesActual) {
            $anios = $anioActual - $anioInicio;
            if ($diaInicio <= $diaActual) {
                $meses = $mesActual - $mesInicio;
                $dies  = $diaActual - $diaInicio;
            } else {
                if ($mesActual == $mesInicio) {
                    $anios = $anios;
                }
                $meses = ($mesActual - $mesInicio + 12) % 12;
                $dies  = $b - ($diaInicio - $diaActual);
            }
        } else {
            $anios = $anioActual - $anioInicio - 1;
            if ($diaInicio > $diaActual) {
                $meses = $mesActual - $mesInicio + 12;
                $dies  = $b - ($diaInicio - $diaActual);
            } else {
                $meses = $mesActual - $mesInicio + 12;
                $dies  = $diaActual - $diaInicio;
            }
        }
        return $anios . "|.|" . $meses . "|.|" . $dies;
    }
}

//    ----------------------------------------
//    CUERPO DE LOS REPORTES
//    ----------------------------------------
switch ($nombre) {
    //    EXPORTAR PRESTACIONES SOCIALES
    case "exportarPrestaciones":

        //    Obtengo LOS DATOS DEL TRABAJADOR....
        $sql              = "SELECT * FROM trabajador WHERE idtrabajador = '" . $idtrabajador . "'";
        $query_trabajador = mysql_query($sql) or die($sql . mysql_error());
        if (mysql_num_rows($query_trabajador) != 0) {
            $field_trabajador = mysql_fetch_array($query_trabajador);
        }

        //    ---------------------------------------
        //    Obtengo los datos de la cabecera....
        $sql         = "SELECT * from cargos where idcargo = '" . $field_trabajador['idcargo'] . "'";
        $query_cargo = mysql_query($sql) or die($sql . mysql_error());
        if (mysql_num_rows($query_cargo) != 0) {
            $field_cargo = mysql_fetch_array($query_cargo);
        }

        list($a, $m, $d)       = SPLIT('[/.-]', $field_trabajador['fecha_ingreso']);
        $fecha_ingreso_mostrar = $d . "/" . $m . "/" . $a;
        list($a, $m, $d)       = SPLIT('[/.-]', $field_trabajador['fecha_nacimiento']);
        $fecha_nacimiento      = $d . "/" . $m . "/" . $a;

        ?>

        <table>
            <tr>
                <td colspan='2' align='right'>CEDULA DE IDENTIDAD: </td>
                <td colspan='2' style="font-size:12px; font-weight:bold" align='left'><?=number_format($field_trabajador['cedula'], 0, ",", ".")?></td>
			</tr>
			<tr>
                <td colspan='2' align='right'>APELLIDOS: </td>
                <td colspan='2' style="font-size:12px; font-weight:bold" align='left'><?=$field_trabajador['apellidos']?></td>
			</tr>
			<tr>
                <td colspan='2' align='right'>NOMBRES: </td>
                <td colspan='2' style="font-size:12px; font-weight:bold" align='left'><?=$field_trabajador['nombres']?></td>
			</tr>
			<tr>
                <td colspan='2' align='right'>FECHA DE NACIMIENTO: </td>
                <td colspan='2' style="font-size:12px; font-weight:bold" align='left'><?=$fecha_nacimiento?></td>
			</tr>
			<tr>
                <td colspan='2' align='right'>CARGO: </td>
                <td colspan='2' style="font-size:12px; font-weight:bold" align='left'><?=$field_cargo['denominacion']?></td>
			</tr>
			<tr>
                <td colspan='2' align='right'>FECHA DE INGRESO: </td>
                <td colspan='2' style="font-size:12px; font-weight:bold" align='left'><?=$fecha_ingreso_mostrar?></td>
			</tr>
		</table>
		<table>
			<tr>
                <td></td>
                <td></td>
			</tr>
            <tr>
                <td colspan='17' align='center' style="font-size:16px; font-weight:bold">HOJA DE CALCULO DE PRESTACIONES</td>
			</tr>
			<tr>
                <td></td>
                <td></td>
			</tr>
		</table>
		<table border='1'>

				<tr>
					<td width='40' align='center'  style="font-size:10px; font-weight:bold">A&Ntilde;O</td>
					<td width='60' align='center'  style="font-size:10px; font-weight:bold">MES</td>
					<td width='120' align='center'  style="font-size:10px; font-weight:bold">SUELDO DEL MES</td>
					<td width='120' align='center'  style="font-size:10px; font-weight:bold">OTROS COMPLEMENTOS</td>
                    <td width='120' align='center'  style="font-size:10px; font-weight:bold">DIAS BONO VACACIONA</td>
					<td width='120' align='center'  style="font-size:10px; font-weight:bold">BONO VACACIONA</td>
                    <td width='120' align='center'  style="font-size:10px; font-weight:bold">DIAS BONO FIN A&Ntilde;O</td>
					<td width='120' align='center'  style="font-size:10px; font-weight:bold">BONO FIN A&Ntilde;O</td>
					<td width='120' align='center'  style="font-size:10px; font-weight:bold">INGRESOS DEL MES</td>
					<td width='60' align='center'  style="font-size:10px; font-weight:bold">A&Ntilde;OS</td>
					<td width='80' align='center'  style="font-size:10px; font-weight:bold">MESES</td>
					<td width='80' align='center'  style="font-size:10px; font-weight:bold">LEY</td>
					<td width='100' align='center'  style="font-size:10px; font-weight:bold">DIAS PRESTACIONES</td>
					<td width='100' align='center'  style="font-size:10px; font-weight:bold">DIAS ADICIONALES</td>
					<td width='100' align='center'  style="font-size:10px; font-weight:bold">TOTAL DIAS</td>
					<td width='120' align='center'  style="font-size:10px; font-weight:bold">PRESTACIONES DEL MES</td>
					<td width='120' align='center'  style="font-size:10px; font-weight:bold">PRESTACIONES ACUMULADAS</td>
					<td width='80' align='center'  style="font-size:10px; font-weight:bold">TASA INTERES</td>
					<td width='120' align='center'  style="font-size:10px; font-weight:bold">INTERESES PRESTACIONES</td>
					<td width='120' align='center'  style="font-size:10px; font-weight:bold">INTERESES ACUMULADOS</td>
					<td width='120' align='center'  style="font-size:10px; font-weight:bold">PRESTACIONES + INTERESES</td>
				</tr>
			</table>

		<table>
		<?

        //$sql_consulta = mysql_query("select * from tabla_prestaciones where idtrabajador = '".$idtrabajador."' order by anio, mes");

        $meses['01'] = "Ene";
        $meses['02'] = "Feb";
        $meses['03'] = "Mar";
        $meses['04'] = "Abr";
        $meses['05'] = "May";
        $meses['06'] = "Jun";
        $meses['07'] = "Jul";
        $meses['08'] = "Ago";
        $meses['09'] = "Sep";
        $meses[10]   = "Oct";
        $meses[11]   = "Nov";
        $meses[12]   = "Dic";

        list($anioIngreso, $mesIngreso, $diaIngreso) = explode("-", $field_trabajador['fecha_ingreso']);
        $fecha_ingreso                               = $field_trabajador['fecha_ingreso'];

        /*$sql_configuracion = mysql_query("select * from configuracion_rrhh");
        $bus_configuracion = mysql_fetch_array($sql_configuracion);

        $mes_inicio_prentaciones = $bus_configuracion["meses_inicio_pago_prestaciones"];
        $dias_prestaciones = $bus_configuracion["dias_prestaciones_mes"];*/
        $k                                           = 0;
        $bandera                                     = -1;
        $cont_meses                                  = 0;
        $cont_anios                                  = 0;
        $mostrar                                     = false;
        $cuenta_meses                                = -1;
        $anio_totalizar                              = 0;
        $prestaciones_anuales                        = 0;
        $intereses_anuales                           = 0;
        $adelantos_prestaciones_anuales              = 0;
        $adelantos_intereses_anuales                 = 0;
        $fila                                        = 11;
        $sql_consulta                                = mysql_query("select * from tabla_prestaciones where idtrabajador = '" . $idtrabajador . "' order by anio, mes");
        list($anioIngreso, $mesIngreso, $diaIngreso) = explode("-", $fecha_ingreso);

        while ($bus_consulta = mysql_fetch_array($sql_consulta)) {
            $dias_prestaciones = 0;
            $dias_adicionales  = 0;

            $resultado_fecha  = diferenciaEntreDosFechas($fecha_ingreso, $bus_consulta["anio"] . "-" . $bus_consulta["mes"] . "-01");
            list($anioRegistro, $mesRegistro, $diaRegistro) = explode("|.|", $resultado_fecha);
            //CONTADOR DE LOS MESES QUE VAN TRANSCURRIENDO, LO UTILIZO PARA CONTROLAR SI LA APLICACION
            //DE LA LEY ES MENSUAL, TRIMESTRAL O ANUAL
            $cuenta_meses = $cuenta_meses + 1;

            $sql = "select * from leyes_prestaciones where anio_desde <= '" . $bus_consulta["anio"] . "'
         												and anio_hasta >= '" . $bus_consulta["anio"] . "'
         												";
            $sql_leyes = mysql_query($sql);

            //RECORRO LA TABLA DE LEYES PARA SABER CUAL APLICA AL AÑO Y MES DEL BUCLE DE LA TABLA DE PRESTACIONES
            while ($bus_leyes = mysql_fetch_array($sql_leyes)) {

                $anio_desde = $bus_leyes["anio_desde"];
                $mes_desde  = $bus_leyes["mes_desde"];
                $anio_hasta = $bus_leyes["anio_hasta"];
                $mes_hasta  = $bus_leyes["mes_hasta"];
                $capitaliza_intereses = $bus_leyes["capitaliza_intereses"];

                //SI EL AÑO DE LA TABLA PRESTACIONES ESTA ENTRE LOS DOS RANGOS A ESTABLECIDOS EN LA LEY
                if ($anio_desde < $bus_consulta["anio"] and $anio_hasta > $bus_consulta["anio"]) {

                    $ley = $bus_leyes["siglas"];
                    /*
                    SI EL AÑO DE INGRESO ES MAYOR
                     */
                    if (($anioIngreso >= $anio_desde
                        and ($mesRegistro > (int) $bus_leyes["mes_inicial_abono"]
                            and $anioRegistro == 0)) or ($anioRegistro > 0)) {

                        if ($bus_leyes["tipo_abono"] == 'mensual' and $cuenta_meses == 1) {
                            $dias_prestaciones = $bus_leyes["valor_abono"];
                            $cuenta_meses      = 0;
                        }
                        if ($bus_leyes["tipo_abono"] == 'trimestral' and $cuenta_meses == 3) {
                            $dias_prestaciones = $bus_leyes["valor_abono"];
                            $cuenta_meses      = 0;

                        }
                        if ($bus_leyes["tipo_abono"] == 'anual' and $bus_leyes["calcula"] == 'final' and $anio_hasta == $bus_consulta["anio"] and $mes_hasta == $bus_consulta["mes"]) {
                            $dias_prestaciones = $anioRegistro * $bus_leyes["valor_abono"];
                            $cuenta_meses      = 0;

                        }
                        if ($bus_leyes["tipo_abono_adicional"] == 'anual' and $mesRegistro == 0) {
                            $dias_adicionales = $anioRegistro * $bus_leyes["valor_abono_adicional"];
                            if ($dias_adicionales > $bus_leyes["valor_tope_adicional"]) {
                                $dias_adicionales = $bus_leyes["valor_tope_adicional"];
                            }
                        }

                    }
                    if (($anioIngreso > $anio_desde
                        and $anioRegistro == 0) or ($anioRegistro > 0)) {

                        if ($bus_leyes["tipo_abono"] == 'mensual'
                            and ($mesRegistro > (int) $bus_leyes["mes_inicial_abono"]
                                and $anioRegistro == 0)) {
                            $dias_prestaciones = $bus_leyes["valor_abono"];
                            $cuenta_meses      = 0;
                        }
                        if ($bus_leyes["tipo_abono"] == 'trimestral' and $cuenta_meses == 3) {
                            $dias_prestaciones = $bus_leyes["valor_abono"];
                            $cuenta_meses      = 0;

                        }
                        if ($bus_leyes["tipo_abono"] == 'anual' and $bus_leyes["calcula"] == 'final' and $anio_hasta == $bus_consulta["anio"] and $mes_hasta == $bus_consulta["mes"]) {
                            $dias_prestaciones = $anioRegistro * $bus_leyes["valor_abono"];
                            $cuenta_meses      = 0;

                        }
                        if ($bus_leyes["tipo_abono_adicional"] == 'anual' and $mesRegistro == 0) {
                            $dias_adicionales = $anioRegistro * $bus_leyes["valor_abono_adicional"];
                            if ($dias_adicionales > $bus_leyes["valor_tope_adicional"]) {
                                $dias_adicionales = $bus_leyes["valor_tope_adicional"];
                            }
                        }
                    }
                }

                if ($anio_hasta == $bus_consulta["anio"] and $bus_consulta["mes"] <= $mes_hasta) {
                    //ECHO " AÑO HASTA: ".$anio_hasta." AÑO TABLA: ".$bus_consulta["anio"].'<BR>';
                    //ECHO " MES HASTA: ".$mes_hasta." MES TABLA: ".$bus_consulta["mes"].'<BR>';
                    $ley = $bus_leyes["siglas"];
                    if (($anioIngreso >= $anio_desde and ($mesRegistro > (int) $bus_leyes["mes_inicial_abono"] and $anioRegistro == 0)) or ($anioRegistro > 0)) {
                        if ($bus_leyes["tipo_abono"] == 'mensual' and $cuenta_meses == 1) {
                            $dias_prestaciones = $bus_leyes["valor_abono"];
                            $cuenta_meses      = 0;
                        }
                        if ($bus_leyes["tipo_abono"] == 'trimestral' and $cuenta_meses == 3) {
                            $dias_prestaciones = $bus_leyes["valor_abono"];
                            $cuenta_meses      = 0;
                        }
                        if ($bus_leyes["tipo_abono"] == 'anual' and $bus_leyes["calcula"] == 'final' and $anio_hasta == $bus_consulta["anio"] and $mes_hasta == $bus_consulta["mes"]) {
                            $dias_prestaciones = $anioRegistro * $bus_leyes["valor_abono"];
                            $cuenta_meses      = 0;

                        }
                        if ($bus_leyes["tipo_abono_adicional"] == 'anual' and $mesRegistro == 0) {
                            $dias_adicionales = $anioRegistro * $bus_leyes["valor_abono_adicional"];
                            if ($dias_adicionales > $bus_leyes["valor_tope_adicional"]) {
                                $dias_adicionales = $bus_leyes["valor_tope_adicional"];
                            }
                        }
                    }
                }

                if ($anio_desde == $bus_consulta["anio"] and $mes_desde <= $bus_consulta["mes"]) {
                    //ECHO " AÑO desde: ".$anio_desde." AÑO TABLA: ".$bus_consulta["anio"].'<BR>';
                    //ECHO " MES desde: ".$mes_desde." MES TABLA: ".$bus_consulta["mes"].'<BR>';
                    //ECHO " MES REGISTRO ".$mesRegistro. " MES INICIA ABONO ".$bus_leyes["mes_inicial_abono"].'<BR>';
                    $ley = $bus_leyes["siglas"];
                    if (($anioIngreso >= $anio_desde and ($mesRegistro > (int) $bus_leyes["mes_inicial_abono"] and $anioRegistro == 0)) or ($anioRegistro > 0)) {
                        if ($bus_leyes["tipo_abono"] == 'mensual' and $cuenta_meses == 1) {
                            $dias_prestaciones = $bus_leyes["valor_abono"];
                            $cuenta_meses      = 0;
                        }
                        if ($bus_leyes["tipo_abono"] == 'trimestral' and $cuenta_meses == 3) {
                            $dias_prestaciones = $bus_leyes["valor_abono"];
                            $cuenta_meses      = 0;
                        }
                        if ($bus_leyes["tipo_abono"] == 'anual' and $bus_leyes["calcula"] == 'final' and $anio_hasta == $bus_consulta["anio"] and $mes_hasta == $bus_consulta["mes"]) {
                            $dias_prestaciones = $anioRegistro * $bus_leyes["valor_abono"];
                            $cuenta_meses      = 0;

                        }
                        if ($bus_leyes["tipo_abono_adicional"] == 'anual' and $mesRegistro == 0) {
                            $dias_adicionales = $anioRegistro * $bus_leyes["valor_abono_adicional"];
                            if ($dias_adicionales > $bus_leyes["valor_tope_adicional"]) {
                                $dias_adicionales = $bus_leyes["valor_tope_adicional"];
                            }
                        }
                    }
                }
            }
            $restar = 1;

            if ($bus_consulta["anio"] != $anio_totalizar and $anio_totalizar > 0 and $anioRegistro >= 0 and $mesRegistro >= 0) {

                ?>
				<tr  bordercolor="#000000" bgcolor='#A9D0F5'>
	                <td align="right" class="Browse" colspan='15'>TOTALES DEL AÑO: <?=$anio_totalizar?></td>
	                <td align="right" class="Browse"><?=number_format($prestaciones_anuales, 2, ",", ".")?></td>
					<?
                if ($entroAdelantoPrestaciones > 0 and $entroAdelanto > 0) {
                    $restarP += 1;
                    $prestaciones_adelantadas = number_format($adelantos_prestaciones_anuales, 2, ",", "");
                    ?>
                			<td align="right" class="Browse"><?="+m" . ($fila - $restarP - 1) . "-m" . ($fila - ($restarP))?></td>
                		<?
                } else {

                    $restarP += 1;
                    if ($mesAdelanto == '12') {
                        $restarP += 1;
                    }

                    ?>
                			<td align="right" class="Browse"><?="+m" . ($fila - $restarP)?></td>
                		<?
                }

                /*   <td align="right" class="Browse"><?=number_format(($prestaciones_anuales-$adelantos_prestaciones_anuales),2,",",".")?></td> */
                ?>
	                <td align="right" class="Browse">&nbsp;</td>
	                <td align="right" class="Browse"><?=number_format($intereses_anuales, 2, ",", ".")?></td>
	                <?
                if ($entroAdelantoIntereses > 0 and $entroAdelanto > 0) {
                    $restarI += 1;
                    $intereses_adelantados = number_format($adelantos_intereses_anuales, 2, ",", "");
                    ?>
	                    <td align="right" class="Browse"><?="+p" . ($fila - $restarI - 1) . "-p" . ($fila - $restarI)?></td>
                		<?

                } else {
                    $restarI += 1;
                    ?>
                			<td align="right" class="Browse"><?="+p" . ($fila - $restarI)?></td>

                		<?
                }
                if ($restarP > 0) {
                    $entroAdelantoPrestaciones = 0;
                    $restarP                   = 0;
                }
                if ($restarI > 0) {
                    $entroAdelantoIntereses = 0;
                    $restarI                = 0;
                }

                /*
                <td align="right" class="Browse" style="color:#F00"><?=number_format($adelantos_intereses_anuales,2,",",".")?></td>
                <td align="right" class="Browse"><?=number_format(($interes_acumulado),2,",",".")?></td>

                <td align="right" class="Browse"><?=number_format(($prestaciones_anuales+$intereses_anuales-$adelantos_prestaciones_anuales-$adelantos_intereses_anuales),2,",",".")?></td>
                 */?>

	                <td align="right" class="Browse">&nbsp;</td>
                    <?php //"+p" . ($fila) . "+s" . ($fila)?>

	            </tr>

	           <?
                $fila += 1;
                $entroTotales                   = 1;
                $prestaciones_anuales           = 0;
                $intereses_anuales              = 0;
                $adelantos_prestaciones_anuales = 0;
                $adelantos_intereses_anuales    = 0;
            }

            if ($dias_prestaciones > 0) {
                $cuenta_meses = 0;
            }

            if ($cuenta_meses > 3) {
                $cuenta_meses = 0;
            }

            $mostrar = true;
            if ($bandera == $mes_inicio_prentaciones) {
                $mostrar      = true;
                $sql_tasas    = mysql_query("select * from tabla_intereses where mes = '" . $bus_consulta["mes"] . "' and anio = '" . $bus_consulta["anio"] . "'");
                $bus_tasas    = mysql_fetch_array($sql_tasas);
                $sql_adelanto = mysql_query("select * from tabla_adelantos
												where
													idtabla_prestaciones = '" . $bus_consulta["idtabla_prestaciones"] . "'");
                $num_adelanto = mysql_num_rows($sql_adelanto);
                $bus_adelanto = mysql_fetch_array($sql_adelanto);

                //ALICUOTA BONO VACACIONAL
                $alicuota_bv = 0;
                if ($bus_consulta["dias_bono_vacacional"] > 0){
                    $mensual_bono_vacacional = ((($bus_consulta["sueldo"] + $bus_consulta["otros_complementos"]) / 30) *
                                                    $bus_consulta["dias_bono_vacacional"] / 360) * 30;
                    $alicuota_bv = $mensual_bono_vacacional;
                }else{
                    $mensual_bono_vacacional = $bus_consulta["bono_vacacional"];
                }

                //ALICUOTA AGUINALDO
                if ($bus_consulta["dias_bono_fin_anio"] > 0){
                    $mensual_bono_fin_anio = (((($bus_consulta["sueldo"] + $bus_consulta["otros_complementos"]) / 30)
                                                    + ($alicuota_bv / 30))
                                                    * $bus_consulta["dias_bono_fin_anio"] / 360) * 30;
                }else{
                    $mensual_bono_fin_anio = $bus_consulta["bono_fin_anio"];
                }

                $ingreso_mensual = $bus_consulta["sueldo"] + $bus_consulta["otros_complementos"]
                                    + $mensual_bono_vacacional + $mensual_bono_fin_anio;


                $prestaciones_del_mes = (($ingreso_mensual / 30) * ($dias_prestaciones + $dias_adicionales));
                $prestaciones_acumuladas += $prestaciones_del_mes;

                if ($capitaliza_intereses == 'Si'){
                    //CALCULO CAPITALIZANDO LOS INTERESES
                    if($prestacion_interes_acumulado > 0){
                        $interes_prestaciones = ((($prestacion_interes_acumulado + $prestaciones_acumuladas) * $bus_tasas["interes"]) / 100) / 12;
                        $interes_prestaciones_del_mes = ((($prestacion_interes_acumulado + $prestaciones_acumuladas) * $bus_tasas["interes"]) / 100) / 12;
                        $interes_acumulado += ((($prestacion_interes_acumulado + $prestaciones_acumuladas) * $bus_tasas["interes"]) / 100) / 12;
                    }else{
                        $interes_prestaciones = (($prestaciones_acumuladas * $bus_tasas["interes"]) / 100) / 12;
                        $interes_prestaciones_del_mes = (($prestaciones_acumuladas * $bus_tasas["interes"]) / 100) / 12;
                        $interes_acumulado += (($prestaciones_acumuladas * $bus_tasas["interes"]) / 100) / 12;
                    }
                }else{
                    //CALCULO SIN CAPITALIZAR LOS INTERESES
                    $interes_prestaciones = (($prestaciones_acumuladas * $bus_tasas["interes"]) / 100) / 12;
                    $interes_acumulado += (($prestaciones_acumuladas * $bus_tasas["interes"]) / 100) / 12;
                }

                $prestacion_interes_acumulado = ($prestaciones_acumuladas + $interes_acumulado);

            } else {
                $k++;
                $bandera++;
            }

            ?>
			<tr  >
                <td align="center" class="Browse"><?=$bus_consulta["anio"]?></td>
                <td align="left" class="Browse"><?="(" . $bus_consulta["mes"] . ")&nbsp;" . $meses[$bus_consulta["mes"]]?></td>
                <td align="right" class="Browse"><?=number_format($bus_consulta["sueldo"], 2, ",", ".")?></td>
                <td align="right" class="Browse"><?=number_format($bus_consulta["otros_complementos"], 2, ",", ".")?></td>
                <td align="right" class="Browse"><?=number_format($bus_consulta["dias_bono_vacacional"], 2, ",", ".")?></td>
                <td align="right" class="Browse"><?=number_format($bus_consulta["bono_vacacional"], 2, ",", ".")?></td>
                <td align="right" class="Browse"><?=number_format($bus_consulta["dias_bono_fin_anio"], 2, ",", ".")?></td>
                <td align="right" class="Browse"><?=number_format($bus_consulta["bono_fin_anio"], 2, ",", ".")?></td>
                <td align="right" class="Browse"><?=number_format($ingreso_mensual, 2, ",", ".")?></td>
                <td align="center" class="Browse"><?if ($anioRegistro == '') {echo '0';} else {echo $anioRegistro;}?></td>
                <td align="center" class="Browse"><?if ($mesRegistro == '') {echo '0';} else {echo $mesRegistro;}?></td>
                <td align="left" class="Browse"><?=$ley?></td>
                <td align="center" class="Browse"><?if ($mostrar == true) {echo $dias_prestaciones;} else {echo "0";}?></td>
                <td align="center" class="Browse"><?if ($mostrar == true) {echo $dias_adicionales;} else {echo "0";}?></td>
                <td align="center" class="Browse"><?if ($mostrar == true) {echo $dias_prestaciones + $dias_adicionales;} else {echo "0";}?></td>
                <td align="right" class="Browse"><?="+i" . $fila . "/30*o" . $fila?></td>
                <?/*
            <td align="right" class="Browse"><?=number_format($prestaciones_del_mes,2,",",".")?></td>

            <td align="right" class="Browse"><?=number_format($prestaciones_acumuladas,2,",",".")?></td>
             */
            if ($fila == 11) {
                ?>
                	<td align="right" class="Browse"><?=0?></td>
                	<?
            } else {
                $restar    = 1;
                $imprimiot = 0;
                $imprimioa = 0;
                if ($entroTotales == 0 and $entroAdelanto == 0) {
                    $imprimiot = 1;
                    $imprimioa = 1;
                    ?>
                			<td align="right" class="Browse"><?="+q" . ($fila - $restar) . "+p" . $fila?></td>
                		<?
                }
                if ($entroTotales > 0 and $entroAdelanto > 0) {
                    $restar += 2;
                    $imprimiot = 1;
                    $imprimioa = 1;
                    ?>
                			<td align="right" class="Browse"><?="+q" . ($fila - $restar) . "+p" . $fila . "-q" . ($fila - ($restar - 1))?></td>
                		<?
                }
                if ($imprimiot == 0 and $entroTotales > 0) {
                    $restar += 1;
                    //$entroTotales=0;
                    ?>
                			<td align="right" class="Browse"><?="+q" . ($fila - $restar) . "+p" . $fila?></td>
                		<?
                }
                if ($imprimioa == 0 and $entroAdelanto > 0) {
                    $restar += 1;
                    //$entroAdelanto=0;
                    ?>
                			<td align="right" class="Browse"><?="+q" . ($fila - $restar) . "+p" . $fila . "-q" . ($fila - ($restar - 1))?></td>
                		<?
                }

            }
            ?>
                <td align="right" class="Browse"><?=number_format($bus_tasas["interes"], 2, ",", ".")?> %</td>
            <?php
            if ($capitaliza_intereses == 'Si'){
                if ($fila == 11) {
                ?>
                    <td align="right" class="Browse"><?=0?></td>
                    <?
                } else {
                    if ($entroTotales == 0 and $entroAdelanto == 0) {
                        $imprimiot = 1;
                        $imprimioa = 1;
                        ?>
                            <td align="right" class="Browse"><?="+(q" . $fila . "+t". ($fila - $restar) .")*r" . $fila . "/12"?></td>
                            <?
                    }
                    if ($entroTotales > 0 and $entroAdelanto > 0) {
                        $restar += 2;
                        $imprimiot = 1;
                        $imprimioa = 1;
                        ?>
                            <td align="right" class="Browse"><?="+(q" . ($fila) . "+t". ($fila - $restar + 2) ."-t". ($fila - $restar + 3) .")*r" . $fila . "/12"?></td>
                            <?
                    }
                    if ($imprimiot == 0 and $entroTotales > 0) {
                        $restar += 1;
                        //$entroTotales=0;
                        ?>
                            <td align="right" class="Browse"><?="+(q" . $fila . "+t". ($fila - $restar + 1) .")*r" . $fila . "/12"?></td>
                            <?
                    }
                    if ($imprimioa == 0 and $entroAdelanto > 0) {
                        $restar += 1;
                        //$entroAdelanto=0;
                        ?>
                            <td align="right" class="Browse"><?="+(q" . ($fila) . "+t". ($fila - $restar + 1) ."-t". ($fila - $restar + 2) .")*r" . $fila . "/12"?></td>
                            <?
                    }
                }
            }else{
            ?>
                <td align="right" class="Browse"><?="+q" . $fila . "*r" . $fila . "/12"?></td>
            <?php
            }
            if ($fila == 11) {
                ?>
                	<td align="right" class="Browse"><?=0?></td>
                	<?
            } else {
                $restar = 1;
                if ($entroTotales == 0 and $entroAdelanto == 0) {
                    ?>
                			<td align="right" class="Browse"><?="+t" . ($fila - $restar) . "+s" . $fila?></td>
                		<?
                }
                if ($entroTotales > 0 and $entroAdelanto > 0) {
                    $restar += 2;
                    $entroTotales  = 0;
                    $entroAdelanto = 0;
                    ?>
                			<td align="right" class="Browse"><?="+t" . ($fila - $restar) . "+s" . $fila . "-t" . ($fila - ($restar - 1))?></td>
                		<?
                }
                if ($entroTotales > 0) {
                    $restar += 1;
                    $entroTotales = 0;
                    ?>
                			<td align="right" class="Browse"><?="+t" . ($fila - $restar) . "+s" . $fila?></td>
                		<?
                }
                if ($entroAdelanto > 0) {
                    $restar += 1;
                    $entroAdelanto = 0;
                    ?>
                			<td align="right" class="Browse"><?="+t" . ($fila - $restar) . "+s" . $fila . "-t" . ($fila - ($restar - 1))?></td>
                		<?
                }

            }
            $entroTotales  = 0;
            $entroAdelanto = 0;
            /*

            <td align="right" class="Browse"><?=number_format($interes_prestaciones,2,",",".")?></td>
            <td align="right" class="Browse"><?=number_format($interes_acumulado,2,",",".")?></td>
             */?>

                <td align="right" class="Browse"><?="+q" . $fila . "+t" . $fila?></td>

            </tr>

            <?

            if ($num_adelanto != 0) {

                ?>
                <tr bordercolor="#000000" bgcolor='#FFFFCC' onMouseOver="setRowColor(this, 0, 'over', '#FFFFCC', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFFCC', '#EAFFEA', '#FFFFAA')" id="tr_adelanto_<?=$bus_consulta["idtabla_prestaciones"]?>" style="font-weight:bold">
                    <td align="right" class="Browse" colspan="16" style="font-size:14px; font-weight:bold">ADELANTO</td>
                    <td align="right" class="Browse" style="color:#F00"><?=number_format($bus_adelanto["monto_prestaciones"], 2, ",", ".")?></td>
                    <td align="right" class="Browse" colspan='2'>&nbsp;</td>
                    <td align="right" class="Browse" style="color:#F00"><?=number_format($bus_adelanto["monto_interes"], 2, ",", ".")?></td>
                    <td align="right" class="Browse" style="color:#F00">&nbsp;</td>

                    <?php //number_format(($bus_adelanto["monto_prestaciones"] + $bus_adelanto["monto_interes"]), 2, ",", ".")?>

                </tr>
				<?
                $fila += 1;
                $entroAdelanto = 1;
                if ($bus_consulta["mes"] == '12') {
                    $mesAdelanto = 12;
                } else {
                    $mesAdelanto = 0;
                }
                if ($bus_adelanto["monto_prestaciones"] > 0) {
                    $entroAdelantoPrestaciones = 1;
                }
                if ($bus_adelanto["monto_interes"] > 0) {
                    $entroAdelantoIntereses = 1;
                }

            }
            $cont_meses++;
            $interes_acumulado       = $interes_acumulado - $bus_adelanto["monto_interes"];
            $prestaciones_acumuladas = $prestaciones_acumuladas - $bus_adelanto["monto_prestaciones"];
            $adelanto_interes        = $bus_adelanto["monto_interes"];
            $adelanto_prestaciones   = $bus_adelanto["monto_prestaciones"];
            if ($cont_meses == 11) {
                $cont_anios++;
            }
            $anio_totalizar = $bus_consulta["anio"];
            $prestaciones_anuales += $prestaciones_del_mes;
            $intereses_anuales += $interes_prestaciones;
            $adelantos_prestaciones_anuales += $bus_adelanto["monto_prestaciones"];
            $adelantos_intereses_anuales += $bus_adelanto["monto_interes"];
            $fila += 1;
        }
        ?>
		 </table>
		 <?

        break;

//    Relacion de Prestaciones e Intereses...
    case "lista_trabajadores_prestaciones":

        $campos = explode("|", $checks);
        $lapso  = '  ' . $nom_mes[$mes_prestaciones] . ' ' . $anio_prestaciones;
        echo "<table>";
        echo "<tr><td colspan='5' style='$total'>REPUBLICA BOLIVARIANA DE VENEZUELA</td></tr>";
        echo "<tr><td colspan='5' style='$total'>" . $config["nombre_institucion"] . "</td></tr>";
        echo "<tr><td colspan='5' style='$total'>ESTADO " . $config["denominacion"] . "</td></tr>";
        echo "<tr><td colspan='5' style='$total'>" . $config["rif"] . "</td></tr>";
        echo "<tr><td colspan='5' style='$total'></td></tr>";
        echo "<tr><td colspan='5' align='center' style='$total'>RELACION PRESTACIONES SOCIALES</td></tr>";
        echo "<tr><td colspan='5' style='$total'></td></tr>";

        ?>

        <table>
			<tr>
                <td colspan='3' align='right'><?=utf8_decode("HASTA EL MES / AÑO:")?></td>
                <td colspan='2' style="font-size:14px; font-weight:bold" align='left'>&nbsp;&nbsp;<?=$lapso?></td>
			</tr>
		</table>
		<table border='1'>
			<tr>
				<td width='40' align='center' style="font-size:10px; font-weight:bold">No.</td>
				<td width='60' align='center' style="font-size:10px; font-weight:bold">Cedula de Identidad</td>
				<td width='320' align='center' style="font-size:10px; font-weight:bold">Apellidos y Nombres</td>
				<td width='120' align='center' style="font-size:10px; font-weight:bold">Fecha de Ingreso</td>
				<?
        if ($campos[0] == 1) {
            ?>
				<td width='120' align='center'  style="font-size:10px; font-weight:bold">Prestaciones Acumuladas</td>
				<?}
        if ($campos[1] == 1) {
            ?>
				<td width='120' align='center'  style="font-size:10px; font-weight:bold">Intereses Aacumulados</td>
				<?}
        if ($campos[2] == 1) {
            ?>
				<td width='120' align='center'  style="font-size:10px; font-weight:bold">Prestaciones + Intereses</td>
				<?}
        ?>
			</tr>
		</table>

		<table>
			<?

        $whe_estado = '';
        if ($estado == 'activos') {
            $whe_estado = " and t.activo_nomina='si'";
        }

        if ($estado == 'egresados') {
            $whe_estado = " and t.activo_nomina='no'";
        }

        //    -------------------------
        if ($nomina != "") {
            $where = "WHERE rtnt.idtipo_nomina = '" . $nomina . "'" . $whe_estado;
        }

        $orderby = "titulo_nomina, '" . $ordenar . "'";
        //    -------------------------

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

        $query = mysql_query($sql) or die($sql . mysql_error());
        $i     = 1;
        while ($field = mysql_fetch_array($query)) {

            $idtrabajador  = $field["idtrabajador"];
            $fecha_ingreso = $field["fecha_ingreso"];

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
            $meses[10]   = "Oct";
            $meses[11]   = "Nov";
            $meses[12]   = "Dic";

            list($anioIngreso, $mesIngreso, $diaIngreso) = explode("-", $fecha_ingreso);
            $fecha_ingreso_mostrar                       = $diaIngreso . '/' . $mesIngreso . '/' . $anioIngreso;

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

            $sql_consulta = mysql_query("select * from tabla_prestaciones where idtrabajador = '" . $idtrabajador . "' order by anio, mes");

            list($anioIngreso, $mesIngreso, $diaIngreso) = explode("-", $fecha_ingreso);

            //BUCLE PARA IR REVISANDO CADA AÑO Y MES DE LA TABLA DE PRESTACIONES
            while ($bus_consulta = mysql_fetch_array($sql_consulta)) {
                $dias_prestaciones = 0;
                $dias_adicionales  = 0;
                $entra             = 'no';


                $resultado_fecha  = diferenciaEntreDosFechas($fecha_ingreso, $bus_consulta["anio"] . "-" . $bus_consulta["mes"] . "-01");
                list($anioRegistro, $mesRegistro, $diaRegistro) = explode("|.|", $resultado_fecha);
                //CONTADOR DE LOS MESES QUE VAN TRANSCURRIENDO, LO UTILIZO PARA CONTROLAR SI LA APLICACION
                //DE LA LEY ES MENSUAL, TRIMESTRAL O ANUAL
                $cuenta_meses = $cuenta_meses + 1;

                $sql = "select * from leyes_prestaciones where anio_desde <= '" . $bus_consulta["anio"] . "'
				     												and anio_hasta >= '" . $bus_consulta["anio"] . "'
				     												";
                $sql_leyes = mysql_query($sql);

                //RECORRO LA TABLA DE LEYES PARA SABER CUAL APLICA AL AÑO Y MES DEL BUCLE DE LA TABLA DE PRESTACIONES
                while ($bus_leyes = mysql_fetch_array($sql_leyes)) {

                    $anio_desde = $bus_leyes["anio_desde"];
                    $mes_desde  = $bus_leyes["mes_desde"];
                    $anio_hasta = $bus_leyes["anio_hasta"];
                    $mes_hasta  = $bus_leyes["mes_hasta"];
                    $capitaliza_intereses = $bus_leyes["capitaliza_intereses"];


                    //SI EL AÑO DE LA TABLA PRESTACIONES ESTA ENTRE LOS DOS RANGOS A ESTABLECIDOS EN LA LEY
                    if ($anio_desde < $bus_consulta["anio"] and $anio_hasta > $bus_consulta["anio"]) {

                        $ley = $bus_leyes["siglas"];
                        //SI EL AÑO DE INGRESO ES MAYOR

                        if (($anioIngreso >= $anio_desde
                            and ($mesRegistro > (int) $bus_leyes["mes_inicial_abono"]
                                and $anioRegistro == 0)) or ($anioRegistro > 0)) {

                            if ($bus_leyes["tipo_abono"] == 'mensual' and $cuenta_meses == 1) {
                                $dias_prestaciones = $bus_leyes["valor_abono"];
                                $cuenta_meses      = 0;
                            }
                            if ($bus_leyes["tipo_abono"] == 'trimestral' and $cuenta_meses == 3) {
                                $dias_prestaciones = $bus_leyes["valor_abono"];
                                $cuenta_meses      = 0;

                            }
                            if ($bus_leyes["tipo_abono"] == 'anual' and $bus_leyes["calcula"] == 'final' and $anio_hasta == $bus_consulta["anio"] and $mes_hasta == $bus_consulta["mes"]) {
                                $dias_prestaciones = $anioRegistro * $bus_leyes["valor_abono"];
                                $cuenta_meses      = 0;

                            }
                            if ($bus_leyes["tipo_abono_adicional"] == 'anual' and $mesRegistro == 0) {
                                $dias_adicionales = $anioRegistro * $bus_leyes["valor_abono_adicional"];
                                if ($dias_adicionales > $bus_leyes["valor_tope_adicional"]) {
                                    $dias_adicionales = $bus_leyes["valor_tope_adicional"];
                                }
                            }

                        }
                        if (($anioIngreso > $anio_desde
                            and $anioRegistro == 0) or ($anioRegistro > 0)) {

                            if ($bus_leyes["tipo_abono"] == 'mensual'
                                and ($mesRegistro > (int) $bus_leyes["mes_inicial_abono"]
                                    and $anioRegistro == 0)) {
                                $dias_prestaciones = $bus_leyes["valor_abono"];
                                $cuenta_meses      = 0;
                            }
                            if ($bus_leyes["tipo_abono"] == 'trimestral' and $cuenta_meses == 3) {
                                $dias_prestaciones = $bus_leyes["valor_abono"];
                                $cuenta_meses      = 0;

                            }
                            if ($bus_leyes["tipo_abono"] == 'anual' and $bus_leyes["calcula"] == 'final' and $anio_hasta == $bus_consulta["anio"] and $mes_hasta == $bus_consulta["mes"]) {
                                $dias_prestaciones = $anioRegistro * $bus_leyes["valor_abono"];
                                $cuenta_meses      = 0;

                            }
                            if ($bus_leyes["tipo_abono_adicional"] == 'anual' and $mesRegistro == 0) {
                                $dias_adicionales = $anioRegistro * $bus_leyes["valor_abono_adicional"];
                                if ($dias_adicionales > $bus_leyes["valor_tope_adicional"]) {
                                    $dias_adicionales = $bus_leyes["valor_tope_adicional"];
                                }
                            }
                        }
                    }

                    if ($anio_hasta == $bus_consulta["anio"] and $bus_consulta["mes"] <= $mes_hasta) {
                        //ECHO " AÑO HASTA: ".$anio_hasta." AÑO TABLA: ".$bus_consulta["anio"].'<BR>';
                        //ECHO " MES HASTA: ".$mes_hasta." MES TABLA: ".$bus_consulta["mes"].'<BR>';
                        $ley = $bus_leyes["siglas"];
                        if (($anioIngreso >= $anio_desde and ($mesRegistro > (int) $bus_leyes["mes_inicial_abono"] and $anioRegistro == 0)) or ($anioRegistro > 0)) {
                            if ($bus_leyes["tipo_abono"] == 'mensual' and $cuenta_meses == 1) {
                                $dias_prestaciones = $bus_leyes["valor_abono"];
                                $cuenta_meses      = 0;
                            }
                            if ($bus_leyes["tipo_abono"] == 'trimestral' and $cuenta_meses == 3) {
                                $dias_prestaciones = $bus_leyes["valor_abono"];
                                $cuenta_meses      = 0;
                            }
                            if ($bus_leyes["tipo_abono"] == 'anual' and $bus_leyes["calcula"] == 'final' and $anio_hasta == $bus_consulta["anio"] and $mes_hasta == $bus_consulta["mes"]) {
                                $dias_prestaciones = $anioRegistro * $bus_leyes["valor_abono"];
                                $cuenta_meses      = 0;

                            }
                            if ($bus_leyes["tipo_abono_adicional"] == 'anual' and $mesRegistro == 0) {
                                $dias_adicionales = $anioRegistro * $bus_leyes["valor_abono_adicional"];
                                if ($dias_adicionales > $bus_leyes["valor_tope_adicional"]) {
                                    $dias_adicionales = $bus_leyes["valor_tope_adicional"];
                                }
                            }
                        }
                    }

                    if ($anio_desde == $bus_consulta["anio"] and $mes_desde <= $bus_consulta["mes"]) {

                        $ley = $bus_leyes["siglas"];
                        if (($anioIngreso >= $anio_desde and ($mesRegistro > (int) $bus_leyes["mes_inicial_abono"] and $anioRegistro == 0)) or ($anioRegistro > 0)) {
                            if ($bus_leyes["tipo_abono"] == 'mensual' and $cuenta_meses == 1) {
                                $dias_prestaciones = $bus_leyes["valor_abono"];
                                $cuenta_meses      = 0;
                            }
                            if ($bus_leyes["tipo_abono"] == 'trimestral' and $cuenta_meses == 3) {
                                $dias_prestaciones = $bus_leyes["valor_abono"];
                                $cuenta_meses      = 0;
                            }
                            if ($bus_leyes["tipo_abono"] == 'anual' and $bus_leyes["calcula"] == 'final' and $anio_hasta == $bus_consulta["anio"] and $mes_hasta == $bus_consulta["mes"]) {
                                $dias_prestaciones = $anioRegistro * $bus_leyes["valor_abono"];
                                $cuenta_meses      = 0;

                            }
                            if ($bus_leyes["tipo_abono_adicional"] == 'anual' and $mesRegistro == 0) {
                                $dias_adicionales = $anioRegistro * $bus_leyes["valor_abono_adicional"];
                                if ($dias_adicionales > $bus_leyes["valor_tope_adicional"]) {
                                    $dias_adicionales = $bus_leyes["valor_tope_adicional"];
                                }
                            }
                        }
                    }
                }
                //FIN WHILE LEYES APLICADAS

                if ($dias_prestaciones > 0) {
                    $cuenta_meses = 0;
                }

                if ($cuenta_meses > 3) {
                    $cuenta_meses = 0;
                }

                $mostrar = true;
                if ($bandera == $mes_inicio_prentaciones) {

                    $mostrar      = true;
                    $sql_tasas    = mysql_query("select * from tabla_intereses where mes = '" . $bus_consulta["mes"] . "' and anio = '" . $bus_consulta["anio"] . "'");
                    $bus_tasas    = mysql_fetch_array($sql_tasas);
                    $sql_adelanto = mysql_query("select * from tabla_adelantos
                                                        where
                                                            idtabla_prestaciones = '" . $bus_consulta["idtabla_prestaciones"] . "'");
                    $num_adelanto = mysql_num_rows($sql_adelanto);
                    $bus_adelanto = mysql_fetch_array($sql_adelanto);


                    //ALICUOTA BONO VACACIONAL
                    $alicuota_bv = 0;
                    if ($bus_consulta["dias_bono_vacacional"] > 0){
                        $mensual_bono_vacacional = ((($bus_consulta["sueldo"] + $bus_consulta["otros_complementos"]) / 30) *
                                                        $bus_consulta["dias_bono_vacacional"] / 360) * 30;
                        $alicuota_bv = $mensual_bono_vacacional;
                    }else{
                        $mensual_bono_vacacional = $bus_consulta["bono_vacacional"];
                    }

                    //ALICUOTA AGUINALDO
                    if ($bus_consulta["dias_bono_fin_anio"] > 0){
                        $mensual_bono_fin_anio = (((($bus_consulta["sueldo"] + $bus_consulta["otros_complementos"]) / 30)
                                                        + ($alicuota_bv / 30))
                                                        * $bus_consulta["dias_bono_fin_anio"] / 360) * 30;
                    }else{
                        $mensual_bono_fin_anio = $bus_consulta["bono_fin_anio"];
                    }

                    $ingreso_mensual = $bus_consulta["sueldo"] + $bus_consulta["otros_complementos"]
                                        + $mensual_bono_vacacional + $mensual_bono_fin_anio;


                    $prestaciones_del_mes = (($ingreso_mensual / 30) * ($dias_prestaciones + $dias_adicionales));
                    $prestaciones_acumuladas += $prestaciones_del_mes;

                    if ($capitaliza_intereses == 'Si'){
                        //CALCULO CAPITALIZANDO LOS INTERESES
                        if($prestacion_interes_acumulado > 0){
                            $interes_prestaciones = ((($prestaciones_acumuladas + $interes_acumulado) * $bus_tasas["interes"]) / 100) / 12;
                            $interes_prestaciones_del_mes = ((($prestaciones_acumuladas + $interes_acumulado) * $bus_tasas["interes"]) / 100) / 12;
                            $interes_acumulado += ((($prestaciones_acumuladas + $interes_acumulado) * $bus_tasas["interes"]) / 100) / 12;
                        }else{
                            $interes_prestaciones = (($prestaciones_acumuladas * $bus_tasas["interes"]) / 100) / 12;
                            $interes_prestaciones_del_mes = (($prestaciones_acumuladas * $bus_tasas["interes"]) / 100) / 12;
                            $interes_acumulado += (($prestaciones_acumuladas * $bus_tasas["interes"]) / 100) / 12;
                        }
                    }else{
                        //CALCULO SIN CAPITALIZAR LOS INTERESES
                        $interes_prestaciones = (($prestaciones_acumuladas * $bus_tasas["interes"]) / 100) / 12;
                        $interes_acumulado += (($prestaciones_acumuladas * $bus_tasas["interes"]) / 100) / 12;
                    }
                    $prestacion_interes_acumulado = ($prestaciones_acumuladas + $interes_acumulado);

                } else {
                    $k++;
                    $bandera++;
                }

                $cont_meses++;
                $interes_acumulado       = $interes_acumulado - $bus_adelanto["monto_interes"];
                $prestaciones_acumuladas = $prestaciones_acumuladas - $bus_adelanto["monto_prestaciones"];
                $adelanto_interes        = $bus_adelanto["monto_interes"];
                $adelanto_prestaciones   = $bus_adelanto["monto_prestaciones"];
                if ($cont_meses == 11) {
                    $cont_anios++;
                }
                $anio_totalizar = $bus_consulta["anio"];
                $prestaciones_anuales += $prestaciones_del_mes;
                $intereses_anuales += $interes_prestaciones;
                $adelantos_prestaciones_anuales += $bus_adelanto["monto_prestaciones"];
                $adelantos_intereses_anuales += $bus_adelanto["monto_interes"];

                if ($bus_consulta["anio"] == $anio_prestaciones && $bus_consulta["mes"] == $mes_prestaciones) {
                    break;
                }
            }

            $total_interes_prestaciones = $interes_acumulado + $prestaciones_acumuladas;

            //IMPRIMO LOS RESULTADOS
            ?>
				<tr>
					<td><?=$i?></td>
					<td><?=number_format($field['cedula'], 0, '', '.')?></td>
					<td><?=utf8_decode($field['apellidos'] . ', ' . $field['nombres'])?></td>
					<td><?=$fecha_ingreso_mostrar?></td>
					<?
            if ($campos[0] == 1) {
                ?>
					<td><?=number_format($prestaciones_acumuladas, 2, ',', '.')?></td>
					<?}
            if ($campos[1] == 1) {
                ?>
					<td><?=number_format($interes_acumulado, 2, ',', '.')?></td>
					<?}
            if ($campos[2] == 1) {
                ?>
					<td><?=number_format($total_interes_prestaciones, 2, ',', '.')?></td>
					<?}
            ?>
				</tr>
				<?
            $i++;
            $suma_prestaciones          = $suma_prestaciones + $prestaciones_acumuladas;
            $suma_intereses             = $suma_intereses + $interes_acumulado;
            $suma_sumatoria             = $suma_sumatoria + $total_interes_prestaciones;
            $prestaciones_acumuladas    = 0;
            $interes_acumulado          = 0;
            $total_interes_prestaciones = 0;
        }

        ?>
			<tr>
				<td colspan='4' style="background-color:#999999; font-size:13px; font-weight:bold;"><?="TOTAL DE TRABAJADORES: " . ($i - 1)?></td>
				<?
        if ($campos[0] == 1) {
            ?>
				<td style="background-color:#999999; font-size:13px; font-weight:bold;"><?=number_format($suma_prestaciones, 2, ',', '.')?></td>
				<?}
        if ($campos[1] == 1) {
            ?>
				<td style="background-color:#999999; font-size:13px; font-weight:bold;"><?=number_format($suma_intereses, 2, ',', '.')?></td>
				<?}
        if ($campos[2] == 1) {
            ?>
				<td style="background-color:#999999; font-size:13px; font-weight:bold;"><?=number_format($suma_sumatoria, 2, ',', '.')?></td>
				<?}
        ?>
			</tr>
		</table>
		<?

        break;
}
?>