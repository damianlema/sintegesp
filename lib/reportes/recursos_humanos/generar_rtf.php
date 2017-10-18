<?php
session_start();
set_time_limit(-1);
ini_set("memory_limit", "200M");
extract($_GET);
extract($_POST);
require '../../../conf/conex.php';
require '../../mc_table4.php';
Conectarse();
$ahora = date("d-m-Y H:i:s");
//------------------------
$dia          = date("d");
$mes          = date("m");
$annio        = date("Y");
$nommes['01'] = "Enero";
$nommes['02'] = "Febrero";
$nommes['03'] = "Marzo";
$nommes['04'] = "Abril";
$nommes['05'] = "Mayo";
$nommes['06'] = "Junio";
$nommes['07'] = "Julio";
$nommes['08'] = "Agosto";
$nommes['09'] = "Septiembre";
$nommes['10'] = "Octubre";
$nommes['11'] = "Noviembre";
$nommes['12'] = "Diciembre";
$nombremes    = $nommes[$mes];

//------------------------
$pdf        = new PDF_MC_Table4('P', 'mm', 'Letter');
$dia_letras = $pdf->ValorEnLetras($dia, "");
//    ------------------------
$sql   = "SELECT * FROM configuracion_rrhh";
$query = mysql_query($sql) or die($sql . mysql_error());
$rows  = mysql_num_rows($query);
if ($rows != 0) {
    $field = mysql_fetch_array($query);
}

//    ------------------------
$primero = $field['primero_rrhh'];
$ci      = $field['ci_primero_rrhh'];
$cargo   = $field['cargo_primero_rrhh'];
$cargo2  = $field['cargo_primero_rrhh'];
//    ------------------------

//    ------------------------
$sqlt   = "SELECT * FROM trabajador where idtrabajador='" . $idtrabajador . "'";
$queryt = mysql_query($sqlt) or die($sqlt . mysql_error());
$rows   = mysql_num_rows($queryt);
if ($rows != 0) {
    $fieldt = mysql_fetch_array($queryt);
} else {
    $nombreyapellido = $sqlt;
}

//    ------------------------
if ($fieldt["sexo"] == 'F') {
    $genero = 'la ciudadana';
    //$tipo_genero='ciudadana';
} else {
    $genero = 'el ciudadano';
    //$tipo_genero='ciudadano';
}
$nombreyapellido = strtoupper(utf8_decode($fieldt['nombres'])) . " " . strtoupper(utf8_decode($fieldt['apellidos']));
$citrabajador    = number_format($fieldt['cedula'], 0, ',', '.');

list($a, $m, $d) = SPLIT('[/.-]', $fieldt["fecha_ingreso"]);
$fechai          = $d . "/" . $m . "/" . $a;
//    ------------------------
$sqln   = "SELECT * FROM nacionalidad where idnacionalidad='" . $fieldt['idnacionalidad'] . "'";
$queryn = mysql_query($sqln) or die($sqln . mysql_error());
$rows   = mysql_num_rows($queryn);
if ($rows != 0) {
    $fieldn = mysql_fetch_array($queryn);
}

$nacionalidad = $fieldn['indicador'] . "- " . $citrabajador;
//    ------------------------

$sqlc   = "SELECT * FROM cargos where idcargo='" . $fieldt['idcargo'] . "'";
$queryc = mysql_query($sqlc) or die($sqlc . mysql_error());
$fieldc = mysql_fetch_array($queryc);

$cargotrabajador = $fieldc["denominacion"];

$sqlf   = "SELECT * FROM niveles_organizacionales where idniveles_organizacionales='" . $fieldt['idunidad_funcional'] . "'";
$queryf = mysql_query($sqlf) or die($sqlf . mysql_error());
$fieldf = mysql_fetch_array($queryf);

$centrotrabajador = $fieldf["denominacion"];
$centro_costo     = $fieldt["centro_costo"];

$sqlfc   = "SELECT * FROM niveles_organizacionales where idniveles_organizacionales='" . $centro_costo . "'";
$queryfc = mysql_query($sqlfc) or die($sqlfc . mysql_error());
$fieldfc = mysql_fetch_array($queryfc);

$centro_costof = $fieldfc["idcategoria_programatica"];

$sqlcc = "SELECT unidad_ejecutora.denominacion FROM categoria_programatica,unidad_ejecutora
    where categoria_programatica.idcategoria_programatica='" . $centro_costof . "'
    and unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora";
$querycc = mysql_query($sqlcc) or die($sqlcc . mysql_error());
$fieldcc = mysql_fetch_array($querycc);

$cobra = $fieldcc["denominacion"];

if ($idconcepto != '') {
    $sqlconcepto   = "SELECT * from relacion_concepto_trabajador where idconcepto='" . $idconcepto . "' and tabla='" . $tabla . "' and idtrabajador='" . $idtrabajador . "'";
    $queryconcepto = mysql_query($sqlconcepto) or die($sqlconcepto . mysql_error());
    $rowsconcepto  = mysql_num_rows($queryconcepto);
    if ($rowsconcepto != 0) {
        $fieldconcepto         = mysql_fetch_array($queryconcepto);
        $monto_concepto_letras = $pdf->ValorEnLetras($fieldconcepto["valor"], "");
        $monto_concepto        = number_format($fieldconcepto['valor'], 2, ',', '.');
    }
}
$archivo = fopen("doc/FORMATO01.rtf", "r");
/*if ($concepto=='') $archivo=fopen("doc/FORMATO01.rtf", "r");
else $archivo=fopen("doc/FORMATO02.rtf", "r");
else $archivo=fopen("doc/FORMATO03.rtf", "r");*/
//    --------------------
if ($archivo) {
    while (!feof($archivo)) {
        $texto .= fgets($archivo, 255);
    }

    $texto = ereg_replace("_dia_", "{ $dia}", $texto);
    $texto = ereg_replace("_mes_", "{ $nombremes}", $texto);
    $texto = ereg_replace("_annio_", "{ $annio}", $texto);
    $texto = ereg_replace("xxxx", "{ $cargo2}", $texto);
    $texto = ereg_replace("_genero_", "{ $genero}", $texto);

    //$texto=ereg_replace("_tttttt_", "{ $tipo_genero}", $texto);
    $texto = ereg_replace("_trabajador_", "{\\b $nombreyapellido}", $texto);
    $texto = ereg_replace("_nacionalidad_", "{\\b $nacionalidad}", $texto);
    $texto = ereg_replace("cargotrabajador", "{\\b $cargotrabajador}", $texto);
    $texto = ereg_replace("centroooooo", "{\\b $cobra}", $texto);
    $texto = ereg_replace("_asignada_", "{\\b $centrotrabajador}", $texto);
    $texto = ereg_replace("_fechai_", "{\\b $fechai}", $texto);

    $texto = ereg_replace("_valorletra_", "{\\b $monto_concepto_letras}", $texto);

    $texto = ereg_replace("_valor_", "{\\b $monto_concepto}", $texto);

    $texto = ereg_replace("_primero_", "{\\b $primero}", $texto);
    $texto = ereg_replace("_ci_", "{\\b $ci}", $texto);
    $texto = ereg_replace("_cargo_", "{\\b $cargo}", $texto);

    $texto = ereg_replace("_notapie_", "{\\b $nota_pie}", $texto);

    header('Content-type: application/msword');
    header('Content-Disposition: inline; filename=constancia.rtf');
    $output = "{\\rtf1";
    $output .= $texto;
    $output .= "}";
    echo $output;
}
