<?php
/**
 *
 *     "lista_cargos.php" Listado de Trabajadores para seleccionarlo
 *    Version: 1.0.1
 *    Fecha Ultima Modificacion: 28/10/2008
 *    Autor: Hector Lema
 *
 */
ob_start();
session_start();
include_once "../../../conf/conex.php";

$conexion_db        = conectarse();
$existen_registros  = 1;
$buscar_registros   = $_REQUEST["busca"];
$m                  = $_REQUEST["m"];
$guardo             = $_REQUEST["g"];
$cuerpo             = $_REQUEST["cuerpo"];
$juntos             = $_REQUEST["j"];
$idcreditoadicional = $_REQUEST["i"];
$filtra             = $_REQUEST["llama"];

$sql_configuracion = mysql_query("select * from configuracion
											where status='a'"
    , $conexion_db);
$registro_configuracion       = mysql_fetch_assoc($sql_configuracion);
$anio_fijo                    = $registro_configuracion["anio_fiscal"];
$idtipo_presupuesto_fijo      = $registro_configuracion["idtipo_presupuesto"];
$idfuente_financiamiento_fijo = $registro_configuracion["idfuente_financiamiento"];

$sqltp = "select * from tipo_presupuesto where status='a'";

$sql_tipo_presupuesto = mysql_query($sqltp, $conexion_db);

$sql_fuente_financiamiento = mysql_query("select * from fuente_financiamiento
												where status='a'"
    , $conexion_db);

$sql_categoria_programatica = mysql_query("select * from categoria_programatica
												where status='a'
												order by codigo"
    , $conexion_db);

/*$sql="select tipo_presupuesto.denominacion as denotipo_presupuesto,
clasificador_presupuestario.codigo_cuenta as codigopartida,
clasificador_presupuestario.denominacion as denopartida,
ordinal.codigo as codigoordinal,
ordinal.denominacion as denoordinal,
categoria_programatica.codigo as codigocategoria,
fuente_financiamiento.denominacion as denofuente_financiamiento,
maestro_presupuesto.monto_actual as monto_actual,
maestro_presupuesto.anio as anio_maestro,
maestro_presupuesto.idRegistro as idRegistro_maestro,
maestro_presupuesto.monto_actual-maestro_presupuesto.total_compromisos disponible
from
maestro_presupuesto,
tipo_presupuesto,
clasificador_presupuestario,
categoria_programatica,
fuente_financiamiento,
ordinal
where
maestro_presupuesto.status='a'
and maestro_presupuesto.idtipo_presupuesto = tipo_presupuesto.idtipo_presupuesto
and maestro_presupuesto.idclasificador_presupuestario = clasificador_presupuestario.idclasificador_presupuestario
and maestro_presupuesto.idordinal=ordinal.idordinal
and maestro_presupuesto.idfuente_financiamiento=fuente_financiamiento.idfuente_financiamiento
and maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica ";
if ($filtra=="rectificacion"){
$sql = $sql."and categoria_programatica.transferencia = 1";
}
if ($filtra=="recibe"){
$sql = $sql."and categoria_programatica.transferencia <> 1";
}
if (isset($_POST["mostrar"])){

$anio=$_POST["anio"];
$tipo_presupuesto=$_POST["tipo_presupuesto"];
$fuente_financiamiento=$_POST["fuente_financiamiento"];
$categoria_programatica=$_POST["categoria_programatica"];

if ($_POST["anio"]<>""){
$sql=$sql." and maestro_presupuesto.anio='".$anio."'";
}

if ($_POST["tipo_presupuesto"]<>""){
$sql=$sql." and maestro_presupuesto.idtipo_presupuesto like '%".$tipo_presupuesto."%'";
}

if ($_POST["fuente_financiamiento"]<>""){
$sql=$sql." and maestro_presupuesto.idfuente_financiamiento like '%".$fuente_financiamiento."%'";
}

if ($_POST["categoria_programatica"]<>""){
$sql=$sql." and maestro_presupuesto.idcategoria_programatica like '%".$categoria_programatica."%'";
}

} else {
$sql=$sql." and maestro_presupuesto.anio=".$anio_fijo;
}*/

$texto_buscar   = $_POST["textoabuscar"];
$campo_busqueda = $_POST["tipobusqueda"];
$sql            = "select tipo_presupuesto.denominacion as denotipo_presupuesto,
						clasificador_presupuestario.codigo_cuenta as codigopartida,
						clasificador_presupuestario.denominacion as denopartida,
						ordinal.codigo as codigoordinal,
						ordinal.denominacion as denoordinal,
						categoria_programatica.codigo as codigocategoria,
						fuente_financiamiento.denominacion as denofuente_financiamiento,
						maestro_presupuesto.monto_actual as monto_actual,
						maestro_presupuesto.anio as anio_maestro,
						maestro_presupuesto.idRegistro as idRegistro_maestro,
						maestro_presupuesto.monto_original as monto_original,
												maestro_presupuesto.monto_actual-maestro_presupuesto.total_compromisos-maestro_presupuesto.pre_compromiso-maestro_presupuesto.reservado_disminuir disponible
							from
								maestro_presupuesto,
								tipo_presupuesto,
								clasificador_presupuestario,
								categoria_programatica,
								fuente_financiamiento,
								ordinal
							where
								maestro_presupuesto.status='a'
								and maestro_presupuesto.idtipo_presupuesto = tipo_presupuesto.idtipo_presupuesto
								and maestro_presupuesto.idclasificador_presupuestario = clasificador_presupuestario.idclasificador_presupuestario
								and maestro_presupuesto.idordinal=ordinal.idordinal
								and maestro_presupuesto.idfuente_financiamiento=fuente_financiamiento.idfuente_financiamiento
								and maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica ";

if ($filtra == "rectificacion") {
    $sql = $sql . "and categoria_programatica.transferencia = 1";
}
if ($filtra == "recibe") {
    $sql = $sql . "and categoria_programatica.transferencia <> 1";
}

$anio                   = $_REQUEST["anio"];
$tipo_presupuesto       = $_REQUEST["idtipo_presupuesto"];
$fuente_financiamiento  = $_REQUEST["idfuente_financiamiento"];
$categoria_programatica = $_REQUEST["categoria_programatica"];
$idordinal              = $_REQUEST["idordinal"];

if ($_REQUEST["anio"] != "") {
    $sql = $sql . " and maestro_presupuesto.anio='" . $anio . "'";
}

if ($_REQUEST["idtipo_presupuesto"] != "") {
    $sql = $sql . " and maestro_presupuesto.idtipo_presupuesto = '" . $tipo_presupuesto . "'";
}

if ($_REQUEST["idfuente_financiamiento"] != "") {
    $sql = $sql . " and maestro_presupuesto.idfuente_financiamiento = '" . $fuente_financiamiento . "'";
}

if ($_REQUEST["categoria_programatica"] != "") {
    $sql = $sql . " and maestro_presupuesto.idcategoria_programatica = '" . $categoria_programatica . "'";
}

if ($_REQUEST["idordinal"] != "") {
    $sql = $sql . " and maestro_presupuesto.idcategoria_programatica = '" . $categoria_programatica . "'";
}

if ($texto_buscar != "") {
    if ($texto_buscar != "*") {
        if ($campo_busqueda == "c") {
            $sql = $sql . " and clasificador_presupuestario.codigo_cuenta like '%" . $texto_buscar . "%'";
        }
        if ($campo_busqueda == "d") {
            $sql = $sql . " and clasificador_presupuestario.denominacion like '%" . $texto_buscar . "%'";
        }
    }
}

$sql .= " order by maestro_presupuesto.anio, tipo_presupuesto.denominacion, fuente_financiamiento.denominacion, categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta, ordinal.codigo";

$registros_grilla = mysql_query($sql, $conexion_db);
if (mysql_num_rows($registros_grilla) <= 0) {$existen_registros = 1;} else { $existen_registros = 0;}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<link href="../../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../../js/function.js" type="text/javascript" language="javascript"></script>

<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
function ponPresupuesto(idPresupuesto){
	m=document.mostrar.modoactual.value
	//cuerpo=document.mostrar.cuerpo.value
	opener.document.forms[0].idmaestropresupuesto.value=idPresupuesto
	opener.document.forms[0].emergente.value="true"
	opener.document.forms[0].modoactual.value=m
	opener.document.forms[0].guardo.value=true
	//opener.document.forms[0].cuerpo.value=cuerpo
	opener.document.forms[0].juntos.value=document.mostrar.juntos.value
	opener.document.forms[0].submit()
	window.close()
}
</SCRIPT>
</head>

	<body>
	<br>
	<h2 align=center>Listado de Presupuestos</h2>
	<h2 class="sqlmVersion"></h2>
	<br>
	<?php
//echo $idcreditoadicional;
//echo $guardo;
/*echo " a&ntilde;o".$anio.".";
echo "tp".$tipo_presupuesto.".";
echo "ff".$fuente_financiamiento.".";
echo "cp".$categoria_programatica.".";
 */
?>
	<br>
	<h2 class="sqlmVersion"></h2>
	<br>

<form name="buscar" action="listar_presupuesto.php" method="POST">

	<input type="hidden" id="categoria_programatica" name="categoria_programatica" value="<?=$_REQUEST["categoria_programatica"]?>">
    <input type="hidden" id="anio" name="anio" value="<?=$_REQUEST["anio"]?>">
    <input type="hidden" id="idfuente_financiamiento" name="idfuente_financiamiento" value="<?=$_REQUEST["idfuente_financiamiento"]?>">
    <input type="hidden" id="idtipo_presupuesto" name="idtipo_presupuesto" value="<?=$_REQUEST["idtipo_presupuesto"]?>">
    <input type="hidden" id="idordinal" name="idordinal" value="<?=$_REQUEST["idordinal"]?>">
    <input type="hidden" id="origen" name="origen" value="<?=$_REQUEST["origen"]?>">
    <input type="hidden" id="multi_categoria" name="multi_categoria" value="<?=$_REQUEST["multi_categoria"]?>">



    <input type="hidden" name="modoactual" id="modoactual" <?php echo 'value="' . $m . '"'; ?>>
	<input type="hidden" name="guardo" id="guardo" <?php echo 'value="' . $guardo . '"'; ?>>
	<input type="hidden" name="nro" id="nro" <?php echo 'value="' . $idCredito . '"'; ?>>
	<input type="hidden" name="juntos" id="juntos" <?php echo 'value="' . $juntos . '"'; ?>>
	<input type="hidden" name="idcreditos_adicionales" id="idcreditos_adicionales" <?php echo 'value="' . $idcreditoadicional . '"'; ?>>
	<input type="hidden" name="cuerpo" id="cuerpo" <?php echo 'value="' . $cuerpo . '"'; ?>>
	<h4 align=center>Rango a Mostrar</h4>

	<table align=center cellpadding=2 cellspacing=0 width="98%">
  <tr>
    <td width="13%" align='right' class='viewPropTitle'>A&ntilde;o:</td>
    <td width="15%" class='viewProp' style="width:7%"><span class="viewProp" style="width:7%"><span class="viewProp" style="width:7%">
    <select name="anio" style="width:90%" id="anio" <?if ($_REQUEST["origen"] == 'pagos') {echo "disabled";}?>>
      <option value="">&nbsp;</option>
      <option value="2008" <?php if ($_REQUEST["anio"] == '2008') {echo "selected";}?>>2008</option>
      <option value="2009" <?php if ($_REQUEST["anio"] == '2009') {echo "selected";}?>>2009</option>
      <option value="2010" <?php if ($_REQUEST["anio"] == '2010') {echo "selected";}?>>2010</option>
      <option value="2011" <?php if ($_REQUEST["anio"] == '2011') {echo "selected";}?>>2011</option>
      <option value="2012" <?php if ($_REQUEST["anio"] == '2012') {echo "selected";}?>>2012</option>
      <option value="2013" <?php if ($_REQUEST["anio"] == '2013') {echo "selected";}?>>2013</option>
      <option value="2014" <?php if ($_REQUEST["anio"] == '2014') {echo "selected";}?>>2014</option>
      <option value="2015" <?php if ($_REQUEST["anio"] == '2015') {echo "selected";}?>>2015</option>
      <option value="2016" <?php if ($_REQUEST["anio"] == '2016') {echo "selected";}?>>2016</option>
      <option value="2017" <?php if ($_REQUEST["anio"] == '2017') {echo "selected";}?>>2017</option>
      <option value="2018" <?php if ($_REQUEST["anio"] == '2018') {echo "selected";}?>>2018</option>
      <option value="2019" <?php if ($_REQUEST["anio"] == '2019') {echo "selected";}?>>2019</option>
      <option value="2020" <?php if ($_REQUEST["anio"] == '2020') {echo "selected";}?>>2020</option>
      <option value="2021" <?php if ($_REQUEST["anio"] == '2021') {echo "selected";}?>>2021</option>
      <option value="2022" <?php if ($_REQUEST["anio"] == '2022') {echo "selected";}?>>2022</option>
      <option value="2023" <?php if ($_REQUEST["anio"] == '2023') {echo "selected";}?>>2023</option>
      <option value="2024" <?php if ($_REQUEST["anio"] == '2024') {echo "selected";}?>>2024</option>
      <option value="2025" <?php if ($_REQUEST["anio"] == '2025') {echo "selected";}?>>2025</option>
    </select>
    </span></span></td>
    <td width="16%" align='right' class='viewPropTitle' style="width:15%"><span class="viewPropTitle" style="width:15%">Tipo de Presupuesto:</span></td>
    <td width="16%" class='viewProp' style="width:15%"><span class="viewProp" style="width:15%">
      <select name="idtipo_presupuesto" style="width:98%" id="idtipo_presupuesto" <?if ($_REQUEST["origen"] == 'pagos') {echo "disabled";}?>>
        <option value="">&nbsp;</option>
        <?php
while ($rowtipo_presupuesto = mysql_fetch_array($sql_tipo_presupuesto)) {
    ?>
        <option <?php echo 'value="' . $rowtipo_presupuesto["idtipo_presupuesto"] . '"';
    if ($_REQUEST["origen"] == 'pagos') {
        if ($rowtipo_presupuesto["idtipo_presupuesto"] == $_REQUEST["idtipo_presupuesto"]) {
            echo "selected";
        }
    } else if ($rowtipo_presupuesto["idtipo_presupuesto"] == $idtipo_presupuesto_fijo) {
        echo ' selected';
    }
    ?>
                                    > <?php echo $rowtipo_presupuesto["denominacion"]; ?> </option>
        <?php
}
?>
      </select>
    </span></td>
    <td width="19%" align='right' class='viewPropTitle' style="width:18%"><span class="viewPropTitle" style="width:18%">Fuente de Financiamiento:</span></td>
    <td width="21%" class='viewProp' style="width:20%"><span class="viewProp" style="width:20%">
      <select name="idfuente_financiamiento" id="idfuente_financiamiento" style="width:98%" <?if ($_REQUEST["origen"] == 'pagos') {echo "disabled";}?>>
        <option value="">&nbsp;</option>
        <?php
while ($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)) {
    ?>
        <option <?php echo 'value="' . $rowfuente_financiamiento["idfuente_financiamiento"] . '"';
    if ($_REQUEST["origen"] == 'pagos') {
        if ($rowfuente_financiamiento["idfuente_financiamiento"] == $_REQUEST["idfuente_financiamiento"]) {
            echo "selected";
        }
    } else if ($rowfuente_financiamiento["idfuente_financiamiento"] == $idfuente_financiamiento_fijo) {
        echo ' selected';
    }
    ?>> <?php echo $rowfuente_financiamiento["denominacion"]; ?> </option>
        <?php
}
?>
      </select>
    </span></td>
    </tr>
  <tr>
			<td align='right' class='viewPropTitle'><span class="viewPropTitle" style="width:11%"><span class="viewPropTitle" style="width:11%"><span class="viewPropTitle" style="width:11%">Categoria Pro.:</span></span></span></td>
			<td colspan="5" class='viewProp' style="width:7%"><span class="viewProp" style="width:13%">
			  <select name="categoria_programatica" id="categoria_programtica" <?if ($_REQUEST["origen"] == "pagos" and $_REQUEST["multi_categoria"] == "no") {echo "disabled";}?>>
                <option value="">&nbsp;</option>
                <?php
while ($rowcategoria_programatica = mysql_fetch_array($sql_categoria_programatica)) {
    ?>
                <option <?php echo 'value="' . $rowcategoria_programatica["idcategoria_programatica"] . '"';
    if ($_REQUEST["origen"] == "pagos") {
        if ($rowcategoria_programatica["idcategoria_programatica"] == $_REQUEST["categoria_programatica"]) {
            echo "selected";
        }
    }
    $sql_unidad_ejecutora = mysql_query("select * from unidad_ejecutora where idunidad_ejecutora = '" . $rowcategoria_programatica["idunidad_ejecutora"] . "'");
    $bus_unidad_ejecutora = mysql_fetch_array($sql_unidad_ejecutora);
    ?>> <?php echo "(" . $rowcategoria_programatica["codigo"] . ")&nbsp;" . $bus_unidad_ejecutora["denominacion"]; ?> </option>
                <?php
}
?>
              </select>
			</span></td>
	  </tr>
	</table>
<br>
	<h2 class="sqlmVersion"></h2>
	<br>

	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Buscar:</td>
			<td class='viewProp'><input type="text" name="textoabuscar" maxlength="60" size="30"></td>
			<td align='right' class='viewPropTitle'>Por:</td>
			<td class='viewProp'>
				<select name="tipobusqueda">
				  <option value="c">C&oacute;digo Partida</option>
				  <option value="d">Denominaci&oacute;n</option>
			  </select>
			</td>
			<td>
				<input align=center class="button" name="buscar" type="submit" value="Buscar">
			</td>
		</tr>
	</table>
	</form>
	<br>
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="95%">
				<tr>
					<td>
						<form name="grilla" action="lista_presupuestos.php" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="95%">
							<thead>
								<tr>
									<td width="2%" align="center" class="Browse">A&ntilde;o</td>
								  <td width="2%" align="center" class="Browse">Tipo</td>
								  <td width="7%" align="center" class="Browse">Fuente Financiamiento</td>
								  <td width="7%" align="center" class="Browse">Categoria Programatica</td>
								  <td width="61%" align="center" class="Browse">Partida</td>
								  <td width="9%" align="center" class="Browse">Monto Actual</td>
								  <td width="9%" align="center" class="Browse">Disponible</td>
								  <td width="3%" colspan="2" align="center" class="Browse">Acci&oacute;n</td>
							  </tr>
							</thead>
							<?php //  llena la grilla con los registros de la tabla de programas
if ($existen_registros == 0) {
    while ($llenar_grilla = mysql_fetch_array($registros_grilla)) {

        // VALIDAR DISPONIBILIDADES **************************************************************************************
        // VALIDO SI ES ORDINAL
        // VALIDAR DISPONIBILIDADES **************************************************************************************
        // VALIDO SI ES ORDINAL
        $sql_es_ordinal = mysql_query("select * FROM
																maestro_presupuesto mp,
																ordinal o
																	WHERE
																o.idordinal = mp.idordinal
																and o.codigo = '0000'
																and mp.idRegistro = '" . $llenar_grilla["idRegistro_maestro"] . "'") or die(mysql_error());
        $num_es_ordinal = mysql_num_rows($sql_es_ordinal);

        if ($num_es_ordinal > 0) {
            $es_ordinal = 'no';
        } else {
            $es_ordinal = 'si';
        }
        //echo "ID: ".$llenar_grilla["idRegistro_maestro"]." ORDINAL: ".$es_ordinal."<br>";
        // VALIDO SI ES SUB ESPECIFICA
        $sql_es_sub = mysql_query("SELECT * FROM
																maestro_presupuesto mp,
																clasificador_presupuestario cp
																	WHERE
																cp.idclasificador_presupuestario = mp.idclasificador_presupuestario
																and cp.sub_especifica = '00'
																and mp.idRegistro = '" . $llenar_grilla["idRegistro_maestro"] . "'") or die(mysql_error());
        $num_es_sub = mysql_num_rows($sql_es_sub);

        if ($num_es_sub > 0) {
            $es_sub = 'no';
        } else {
            $es_sub = 'si';
        }
        //echo "ID: ".$llenar_grilla["idRegistro_maestro"]." SUB: ".$es_sub."<br>";

        $sql_ordinal = mysql_query("select * from ordinal where codigo = '0000'");
        $bus_ordinal = mysql_fetch_array($sql_ordinal);
        // SI ES ESPECIFICA
        if ($es_sub == 'no' and $es_ordinal == 'no') {
            //echo "AQUI";
            $sql_maestro = mysql_query("SELECT
												cp.partida,
												cp.generica,
												cp.especifica,
												cp.sub_especifica,
												mp.idRegistro,
												(mp.monto_actual - mp.pre_compromiso - mp.total_compromisos - mp.reservado_disminuir) as disponible,
												mp.monto_actual,
												mp.monto_original,
												mp.idcategoria_programatica,
												mp.idtipo_presupuesto,
												mp.idfuente_financiamiento,
												mp.idclasificador_presupuestario
													FROM
												maestro_presupuesto mp,
												clasificador_presupuestario cp
												WHERE
													mp.idRegistro = '" . $llenar_grilla["idRegistro_maestro"] . "'
													and cp.idclasificador_presupuestario = mp.idclasificador_presupuestario") or die(mysql_error());
            $bus_maestro = mysql_fetch_array($sql_maestro);

            $sql_clasificador_sub = mysql_query("SELECT * FROM
																			clasificador_presupuestario
																				WHERE
																			partida = '" . $bus_maestro["partida"] . "'
																			and generica = '" . $bus_maestro["generica"] . "'
																			and especifica = '" . $bus_maestro["especifica"] . "'
																			and sub_especifica != '00'");
            $num_clasificador_sub = mysql_num_rows($sql_clasificador_sub);

            if ($num_clasificador_sub > 0) {
                while ($bus_clasificador_sub = mysql_fetch_array($sql_clasificador_sub)) {
                    $sql_suma = mysql_query("SELECT
														monto_actual,
														monto_original
															FROM
														maestro_presupuesto mp
															WHERE
														idcategoria_programatica = '" . $bus_maestro["idcategoria_programatica"] . "'
														and idtipo_presupuesto = '" . $bus_maestro["idtipo_presupuesto"] . "'
														and idfuente_financiamiento = '" . $bus_maestro["idfuente_financiamiento"] . "'
														and idclasificador_presupuestario = '" . $bus_clasificador_sub["idclasificador_presupuestario"] . "'");
                    $bus_suma = mysql_fetch_array($sql_suma);
                    $disponible_especifica += $bus_suma["monto_original"];
                }
                //echo "AQUI ".$disponible_especifica;
            } else {
                //echo "aqui";
                $sql_maestro = mysql_query("SELECT
												(mp.monto_actual - mp.pre_compromiso - mp.total_compromisos - mp.reservado_disminuir) as disponible,
												mp.idcategoria_programatica,
												mp.idtipo_presupuesto,
												mp.idfuente_financiamiento,
												mp.idclasificador_presupuestario,
												mp.monto_actual
													FROM maestro_presupuesto mp
												WHERE
													idRegistro = '" . $llenar_grilla["idRegistro_maestro"] . "'");
                $bus_maestro = mysql_fetch_array($sql_maestro);

                $sql_ordinales = mysql_query("SELECT *
													FROM maestro_presupuesto
													WHERE
													idcategoria_programatica = '" . $bus_maestro["idcategoria_programatica"] . "'
													and idtipo_presupuesto = '" . $bus_maestro["idtipo_presupuesto"] . "'
													and idfuente_financiamiento = '" . $bus_maestro["idfuente_financiamiento"] . "'
													and idclasificador_presupuestario = '" . $bus_maestro["idclasificador_presupuestario"] . "'
													and idordinal != '" . $bus_ordinal["idordinal"] . "'");
                while ($bus_ordinales = mysql_fetch_array($sql_ordinales)) {
                    $sql_suma = mysql_query("SELECT
												(monto_actual - pre_compromiso - total_compromisos - reservado_disminuir) as disponible,
												monto_actual,
												monto_original
													FROM maestro_presupuesto
												WHERE
													idcategoria_programatica = '" . $bus_ordinales["idcategoria_programatica"] . "'
													and idtipo_presupuesto = '" . $bus_ordinales["idtipo_presupuesto"] . "'
													and idfuente_financiamiento = '" . $bus_ordinales["idfuente_financiamiento"] . "'
													and idclasificador_presupuestario = '" . $bus_ordinales["idclasificador_presupuestario"] . "'
													and idordinal = '" . $bus_ordinales["idordinal"] . "'");
                    $bus_suma = mysql_fetch_array($sql_suma);

                    $disponible_especifica += $bus_suma["monto_original"];
                }

            }

            //if($llenar_grilla["idRegistro_maestro"]==1215){
            //echo $disponible_especifica."<br>";
            //}
            //echo $disponible_especifica;
            if (($bus_maestro["monto_actual"] - $disponible_especifica) == 0 and $disponible_especifica = !0) {
                $mostrar_partida = 'no';
            } else {
                $mostrar_partida    = 'si';
                $disponible_mostrar = ($bus_maestro["disponible"] - $disponible_especifica);
            }

        }
        $disponible_especifica = 0;

        // SI ES SUB ESPECIFICA
        if ($es_sub == "si" and $es_ordinal == 'no') {
            $sql_maestro = mysql_query("SELECT
												(mp.monto_actual - mp.pre_compromiso - mp.total_compromisos - mp.reservado_disminuir) as disponible,
												mp.idcategoria_programatica,
												mp.idtipo_presupuesto,
												mp.idfuente_financiamiento,
												mp.idclasificador_presupuestario,
												mp.monto_actual,
												mp.monto_original
													FROM maestro_presupuesto mp
												WHERE
													idRegistro = '" . $llenar_grilla["idRegistro_maestro"] . "'");
            $bus_maestro = mysql_fetch_array($sql_maestro);

            $sql_ordinales = mysql_query("SELECT *
													FROM maestro_presupuesto
													WHERE
													idcategoria_programatica = '" . $bus_maestro["idcategoria_programatica"] . "'
													and idtipo_presupuesto = '" . $bus_maestro["idtipo_presupuesto"] . "'
													and idfuente_financiamiento = '" . $bus_maestro["idfuente_financiamiento"] . "'
													and idclasificador_presupuestario = '" . $bus_maestro["idclasificador_presupuestario"] . "'
													and idordinal != '" . $bus_ordinal["idordinal"] . "'");
            while ($bus_ordinales = mysql_fetch_array($sql_ordinales)) {
                $sql_suma = mysql_query("SELECT
											(monto_actual - pre_compromiso - total_compromisos - reservado_disminuir) as disponible,
											monto_actual,
											monto_original
										 		FROM maestro_presupuesto
											WHERE
												idcategoria_programatica = '" . $bus_ordinales["idcategoria_programatica"] . "'
												and idtipo_presupuesto = '" . $bus_ordinales["idtipo_presupuesto"] . "'
												and idfuente_financiamiento = '" . $bus_ordinales["idfuente_financiamiento"] . "'
												and idclasificador_presupuestario = '" . $bus_ordinales["idclasificador_presupuestario"] . "'
												and idordinal = '" . $bus_ordinales["idordinal"] . "'");
                $bus_suma = mysql_fetch_array($sql_suma);
                $disponible_especifica += $bus_suma["monto_original"];
            }
            if (($bus_maestro["disponible"] - $disponible_especifica) == 0 and $disponible_especifica = !0) {
                $mostrar_partida = 'no';
            } else {
                $mostrar_partida    = 'si';
                $disponible_mostrar = ($bus_maestro["disponible"] - $disponible_especifica);
            }
        }
        $disponible_especifica = 0;

        // SI ES ORDINAL

        if ($es_ordinal == 'si') {
            //echo "AQUI<br>";
            $mostrar_partida    = 'si';
            $disponible_mostrar = $llenar_grilla["disponible"];
        }

        if ($llenar_grilla["monto_original"] == 0) {
            $mostrar_partida = 'si';
        }

        //echo "MOSTRAR PARTIDA: ".$mostrar_partida."<br>";
        // VALIDAR DISPONIBILIDADES **************************************************************************************

        // VALIDAR DISPONIBILIDADES **************************************************************************************

        if ($mostrar_partida == 'si') {

            $cp = $llenar_grilla["idRegistro_maestro"];
            if ($llenar_grilla["codigoordinal"] != '0000') {
                $denominacion = $llenar_grilla["denoordinal"];
            } else {
                $denominacion = $llenar_grilla["denopartida"];
            }
            ?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.document.getElementById('id_partida').value='<?=$llenar_grilla["idRegistro_maestro"]?>', opener.document.getElementById('partida').value='<?=$llenar_grilla["codigopartida"]?>', opener.document.getElementById('denominacion_partida').value='<?=$denominacion?>', opener.document.getElementById('ordinal').value='<?=$llenar_grilla["codigoordinal"]?>', opener.document.getElementById('monto').focus(), window.close()">
								<?php
echo "<td align='center' class='Browse' width='5%'>" . $llenar_grilla["anio_maestro"] . "</td>";
            echo "<td align='center' class='Browse' width='10%'>" . utf8_decode($llenar_grilla["denotipo_presupuesto"]) . "</td>";
            echo "<td align='left' class='Browse'width='17%'>" . utf8_decode($llenar_grilla["denofuente_financiamiento"]) . "</td>";
            echo "<td align='center' class='Browse' width='8%'>" . $llenar_grilla["codigocategoria"] . "</td>";
            echo "<td align='left' class='Browse'>" . $llenar_grilla["codigopartida"] . " " . $llenar_grilla["codigoordinal"] . " ";
            echo utf8_decode($denominacion);
            echo "</td>";
            echo "<td align='right' class='Browse' width='10%'>" . number_format($llenar_grilla["monto_actual"], 2, ",", ".") . "</td>";
            echo "<td align='right' class='Browse' width='10%'>" . number_format($disponible_mostrar, 2, ",", ".") . "</td>";

            ?>
									<td align='center' class='Browse' width='5%'>
                                    <img src='../../../imagenes/validar.png' style="cursor:pointer" onClick="opener.document.getElementById('id_partida').value='<?=$llenar_grilla["idRegistro_maestro"]?>', opener.document.getElementById('partida').value='<?=$llenar_grilla["codigopartida"]?>', opener.document.getElementById('denominacion_partida').value='<?=$denominacion?>', opener.document.getElementById('ordinal').value='<?=$llenar_grilla["codigoordinal"]?>', opener.document.getElementById('monto').focus(), window.close()">
                                    </td>
									</tr>

                                    <?
        }
        $mostrar_partida    = '';
        $disponible_mostrar = 0;
    }
}?>
						</table>
						</form>
					</td>
				</tr>
			</table>
		</div>
</body>
</html>
