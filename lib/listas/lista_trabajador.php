<?php
/**
 *
 *     "lista_trabajador.php" Listado de Trabajadores para seleccionarlo
 *    Version: 1.0.1
 *    Fecha Ultima Modificacion: 28/10/2008
 *    Autor: Hector Lema
 *
 */
ob_start();
session_start();
include_once "../../conf/conex.php";
$conexion_db       = conectarse();
$existen_registros = 0;
$buscar_registros  = $_GET["busca"];
$formulario        = $_REQUEST["frm"];

if ($_GET["tipo_nomina"]) {

    $registros_grilla = mysql_query("select * from trabajador, relacion_tipo_nomina_trabajador where trabajador.status='a' and relacion_tipo_nomina_trabajador.idtrabajador = trabajador.idtrabajador and relacion_tipo_nomina_trabajador.idtipo_nomina = '" . $_GET["tipo_nomina"] . "' order by cedula");
} else {
    $registros_grilla  = mysql_query("select * from trabajador order by cedula");
    $existen_registros = 1;
}

if (isset($_POST["buscar"])) {

    $texto_buscar   = $_POST["textoabuscar"];
    $campo_busqueda = $_POST["tipobusqueda"];
    /*if($_REQUEST["tipo_nomina"]){
    $sql="select * from trabajador, relacion_tipo_nomina_trabajador where trabajador.status='a'
    and relacion_tipo_nomina_trabajador.idtrabajador = trabajador.idtrabajador
    and relacion_tipo_nomina_trabajador.idtipo_nomina = '".$_REQUEST["tipo_nomina"]."'
    AND
    (trabajador.nro_ficha like '%$texto_buscar%' OR
    trabajador.cedula like '%$texto_buscar%' OR
    trabajador.apellidos like '%$texto_buscar%' OR
    trabajador.nombres like '%$texto_buscar%') order by cedula";
    $registros_grilla = mysql_query($sql);
    }else{ */
    //status='a' AND
    $sql = "select * from trabajador where
						(nro_ficha like '%$texto_buscar%' OR
						 cedula like '%$texto_buscar%' OR
						 apellidos like '%$texto_buscar%' OR
						 nombres like '%$texto_buscar%') order by cedula" or die(mysql_error());
    $registros_grilla  = mysql_query($sql);
    $existen_registros = 0;
    //}

    if (mysql_num_rows($registros_grilla) <= 0) {
        $existen_registros = 1;
    }
}

//if (mysql_num_rows($registros_trabajador)<=0)
//        {
//            $existen_registros=1;
//        }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>

<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")

function ponCedula(ced,id){
	//alert(id);
	//alert(forms[0]);
	opener.document.forms[0].cedula_buscar.value=ced;
	//opener.document.getElementById('id_obrero').value = id;
	opener.document.forms[0].submit();
	window.close();
}

function ponCedulaExperiencia(nombre, id, cedula, apellido){
	//alert(ced);
	//alert(forms[0]);
	opener.document.getElementById('nombre_trabajador').value=nombre;
	opener.document.getElementById('idtrabajador').value= id;
	opener.document.getElementById('cedula_trabajador').value= cedula;
	opener.document.getElementById('apellido_trabajador').value= apellido;
	opener.buscarExperiencias();
	//opener.document.forms[0].submit()
	window.close();
}

function ponCedulaHistorico(nombre, cedula, apellido,id){
	//alert(ced);
	//alert(forms[0]);
	opener.document.getElementById('nombre_trabajador').value=nombre;
	opener.document.getElementById('cedula_trabajador').value= cedula;
	opener.document.getElementById('apellido_trabajador').value= apellido;
	opener.document.getElementById('idtrabajador').value= id;
	opener.llenarGrilla(id);
	//opener.buscarPermisos();
	//opener.document.forms[0].submit();
	window.close();
}

function ponCedulahHistoricos_vacaciones(nombre, id, cedula, apellido){
	opener.document.getElementById('id_trabajador').value = id;
	opener.document.getElementById('cedula_trabajador').value = cedula;
	opener.document.getElementById('nombre_trabajador').value = nombre;
	opener.document.getElementById('apellido_trabajador').value = apellido;
	opener.llenarGrilla(id);
	window.close();
}
function ponCedulaMovimientos(idtrabajador, nombre, cedula, apellido , idubicacion, idcargo, idcentro_costo, fecha_ingreso, nro_ficha){


	opener.document.getElementById('nueva_ubicacion_funcional_movimientos').value = idubicacion;
	opener.document.getElementById('nuevo_cargo_movimientos').value = idcargo;
	opener.document.getElementById('centro_costo_movimientos').value = idcentro_costo;
	opener.document.getElementById('fecha_ingreso_movimientos').value = fecha_ingreso;

	opener.document.getElementById('ficha_actual_movimientos').innerHTML = nro_ficha;

	window.close();
}


function ponCedulaVacaciones(idtrabajador, nombre, cedula, apellido ){
	opener.document.getElementById('id_trabajador').value = idtrabajador;
	opener.document.getElementById('cedula_trabajador').value = cedula;
	opener.document.getElementById('nombre_trabajador').value = nombre;
	opener.document.getElementById('apellido_trabajador').value = apellido;
	opener.consultarVacaciones(idtrabajador);
	window.close();
}

function ponrep_constancia(idtrabajador, nombre, cedula, apellido ){
	opener.document.getElementById('idtrabajadorc').value = idtrabajador;
	opener.document.getElementById('nomtrabajador').value = apellido+' '+nombre;

	window.close();
}

function ponrep_carga_familiar(idtrabajador, nombre, cedula, apellido ){
	opener.document.getElementById('idtrabajador').value = idtrabajador;
	opener.document.getElementById('nomtrabajador').value = apellido+' '+nombre;

	window.close();
}

</SCRIPT>
</head>

	<body>
	<br>
	<h4 align=center>Listado de Trabajadores</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<?php //echo $formulario;?>
	<form name="buscar" action="lista_trabajador.php" method="POST">
    <input type="hidden" name="frm" id="frm" value="<?=$_REQUEST["frm"]?>">
    <input type="hidden" name="tipo_nomina" id="tipo_nomina" value="<?=$_REQUEST["tipo_nomina"]?>">

	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Buscar:</td>
			<td class=''><input type="text" name="textoabuscar" maxlength="30" size="30"></td>
			<td>
				<input align=center name="buscar" type="submit" value="Buscar">
				</a>
			</td>
		</tr>
	</table>
	</form>

	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="90%">
				<tr>
					<td>
						<form name="grilla" action="../../modulos/rrhh/usuarios.php" method="POST">
						<table class="Browse" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<!--<td class="Browse">&nbsp;</td>-->
									<td align="center" class="Browse" width="5%">Nro</td>
                                    <td align="center" class="Browse" width="10%">Nro Ficha</td>
                                    <td align="center" class="Browse" width="12%">C&eacute;dula</td>
									<td align="center" class="Browse" width="33%">Apellidos</td>
									<td align="center" class="Browse" width="33%">Nombres</td>
                                    <td align="center" class="Browse" width="5%">Status</td>
									<td align="center" class="Browse" width="7%">Selecci&oacute;n</td>
								</tr>
							</thead>

							<?php

if ($_REQUEST["frm"] == "datos_basicos") {
    //echo "AQUIIIIIIIIIIII";
    if ($existen_registros == 0) {
        $i = 1;
        while ($llenar_grilla = mysql_fetch_array($registros_grilla)) {
            $c             = $llenar_grilla["cedula"];
            $id_trabajador = $llenar_grilla["idtrabajador"];

            $idubicacion    = $llenar_grilla["idunidad_funcional"];
            $idcargo        = $llenar_grilla["idcargo"];
            $idcentro_costo = $llenar_grilla["centro_costo"];
            $fecha_ingreso  = $llenar_grilla["fecha_ingreso"];
            $nro_ficha      = $llenar_grilla["nro_ficha"];
            if ($llenar_grilla["status"] == 'a') {
                ?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.document.getElementById('cedula_buscar').value= '<?=$c?>' , opener.consultarTrabajador('<?=$id_trabajador?>'), window.close()">
								<?php
} else {
                ?>
									<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.document.getElementById('cedula_buscar').value= '<?=$c?>' , opener.consultarTrabajador('<?=$id_trabajador?>'), window.close()">
								<?php

            }
            echo "<td align='right' class='Browse' width='5%'>" . $i . "</td>";
            echo "<td align='center' class='Browse' width='10%'>" . $llenar_grilla["nro_ficha"] . "</td>";
            echo "<td align='right' class='Browse' width='12%'>" . $llenar_grilla["cedula"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["apellidos"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["nombres"] . "</td>";
            if ($llenar_grilla["status"] == 'a') {
                echo "<td align='left' class='Browse' width='5%'>" . 'ACTIVO' . "</td>";
            } else {
                echo "<td align='left' class='Browse' width='5%'>" . 'EGRESADO' . "</td>";
            }
            ?>
									<td align='center' class='Browse' width='7%'><a href='#' onClick="opener.document.getElementById('cedula_buscar').value= '<?=$c?>' , opener.consultarTrabajador('<?=$id_trabajador?>'), window.close()"><img src='../../imagenes/validar.png'></a></td>
                                    <?
            echo "</tr>";
            $i++;
        }
    }
} else if ($_REQUEST["frm"] == "carga_familiar") {
    //echo "AQUIIIIIIIIIIII";
    if ($existen_registros == 0) {
        $i = 1;
        while ($llenar_grilla = mysql_fetch_array($registros_grilla)) {
            $c             = $llenar_grilla["cedula"];
            $id_trabajador = $llenar_grilla["idtrabajador"];
            ?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="ponCedula('<?=$c?>','<?=$id_trabajador?>')">
								<?php
echo "<td align='right' class='Browse' width='5%'>" . $i . "</td>";
            echo "<td align='center' class='Browse' width='10%'>" . $llenar_grilla["nro_ficha"] . "</td>";
            echo "<td align='right' class='Browse' width='12%'>" . $llenar_grilla["cedula"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["apellidos"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["nombres"] . "</td>";
            ?>
									<td align='center' class='Browse' width='7%'><a href='#' onClick="ponCedula('<?=$c?>','<?=$id_trabajador?>')"><img src='../../imagenes/validar.png'></a></td>
                                    <?
            echo "</tr>";
            $i++;
        }
    }
} else if ($_REQUEST["frm"] == "historico_vacaciones") {
    if ($existen_registros == 0) {
        $i = 1;
        while ($llenar_grilla = mysql_fetch_array($registros_grilla)) {
            $nombre   = $llenar_grilla["nombres"];
            $id       = $llenar_grilla["idtrabajador"];
            $cedula   = $llenar_grilla["cedula"];
            $apellido = $llenar_grilla["apellidos"];
            ?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="ponCedulahHistoricos_vacaciones('<?php echo $nombre; ?>', '<?=$id?>', '<?=$cedula?>', '<?=$apellido?>')" >
								<?php
echo "<td align='right' class='Browse' width='5%'>" . $i . "</td>";
            echo "<td align='center' class='Browse' width='10%'>" . $llenar_grilla["nro_ficha"] . "</td>";
            echo "<td align='right' class='Browse' width='12%'>" . $llenar_grilla["cedula"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["apellidos"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["nombres"] . "</td>";

            ?>
                                    <td align='center' class='Browse' width='7%'>
                                    	<a href='#' onClick="ponCedulahHistoricos_vacaciones('<?php echo $nombre; ?>', '<?=$id?>', '<?=$cedula?>', '<?=$apellido?>')"><img src='../../imagenes/validar.png'></a></td>
                                    <?
            echo "</tr>";
            $i++;
        }
    }

} else if ($_REQUEST["frm"] == "experiencia_laboral") {
    if ($existen_registros == 0) {
        $i = 1;
        while ($llenar_grilla = mysql_fetch_array($registros_grilla)) {
            $nombre   = $llenar_grilla["nombres"];
            $id       = $llenar_grilla["idtrabajador"];
            $cedula   = $llenar_grilla["cedula"];
            $apellido = $llenar_grilla["apellidos"];
            ?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="ponCedulaExperiencia('<?php echo $nombre; ?>', '<?=$id?>', '<?=$cedula?>', '<?=$apellido?>')" >
								<?php
echo "<td align='right' class='Browse' width='5%'>" . $i . "</td>";
            echo "<td align='center' class='Browse' width='10%'>" . $llenar_grilla["nro_ficha"] . "</td>";
            echo "<td align='right' class='Browse' width='12%'>" . $llenar_grilla["cedula"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["apellidos"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["nombres"] . "</td>";

            ?>
                                    <td align='center' class='Browse' width='7%'>
                                    	<a href='#' onClick="ponCedulaExperiencia('<?php echo $nombre; ?>', '<?=$id?>', '<?=$cedula?>', '<?=$apellido?>')"><img src='../../imagenes/validar.png'></a></td>
                                    <?
            echo "</tr>";
            $i++;
        }
    }

} else if ($_REQUEST["frm"] == "historico_permisos") {
    if ($existen_registros == 0) {
        $i = 1;
        while ($llenar_grilla = mysql_fetch_array($registros_grilla)) {
            ?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
echo "<td align='right' class='Browse' width='5%'>" . $i . "</td>";
            echo "<td align='center' class='Browse' width='10%'>" . $llenar_grilla["nro_ficha"] . "</td>";
            echo "<td align='right' class='Browse' width='12%'>" . $llenar_grilla["cedula"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["apellidos"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["nombres"] . "</td>";
            $nombre   = $llenar_grilla["nombres"];
            $id       = $llenar_grilla["idtrabajador"];
            $cedula   = $llenar_grilla["cedula"];
            $apellido = $llenar_grilla["apellidos"];
            ?>
                                    <td align='center' class='Browse' width='7%'>
                                    	<a href='#' onClick="ponCedulaHistorico('<?php echo $nombre; ?>', '<?=$cedula?>', '<?=$apellido?>', '<?=$id?>')"><img src='../../imagenes/validar.png'></a></td>
                                    <?
            echo "</tr>";
            $i++;
        }
    }

} else if ($_REQUEST["frm"] == "movimientos_personal") {
    if ($existen_registros == 0) {
        $i = 1;
        while ($llenar_grilla = mysql_fetch_array($registros_grilla)) {
            $nombre   = $llenar_grilla["nombres"];
            $id       = $llenar_grilla["idtrabajador"];
            $cedula   = $llenar_grilla["cedula"];
            $apellido = $llenar_grilla["apellidos"];

            $idubicacion    = $llenar_grilla["idunidad_funcional"];
            $idcargo        = $llenar_grilla["idcargo"];
            $idcentro_costo = $llenar_grilla["centro_costo"];
            $fecha_ingreso  = $llenar_grilla["fecha_ingreso"];
            $nro_ficha      = $llenar_grilla["nro_ficha"];

            ?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="ponCedulaMovimientos('<?=$id?>', '<?=$nombre?>', '<?=$cedula?>', '<?=$apellido?>', '<?=$idubicacion?>', '<?=$idcargo?>', '<?=$idcentro_costo?>', '<?=$fecha_ingreso?>' , '<?=$nro_ficha?>')">
								<?php
echo "<td align='right' class='Browse' width='5%'>" . $i . "</td>";
            echo "<td align='center' class='Browse' width='10%'>" . $llenar_grilla["nro_ficha"] . "</td>";
            echo "<td align='right' class='Browse' width='12%'>" . $llenar_grilla["cedula"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["apellidos"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["nombres"] . "</td>";

            ?>
                                    <td align='center' class='Browse' width='7%'>
                                    	<a href='#' onClick="ponCedulaMovimientos('<?=$id?>', '<?=$nombre?>', '<?=$cedula?>', '<?=$apellido?>', '<?=$idubicacion?>', '<?=$idcargo?>', '<?=$idcentro_costo?>', '<?=$fecha_ingreso?>', '<?=$nro_ficha?>')"><img src='../../imagenes/validar.png'></a></td>
                                    <?
            echo "</tr>";
            $i++;
        }
    }

} else if ($_REQUEST["frm"] == "conceptos_trabajador") {
    if ($existen_registros == 0) {
        $i = 1;

        while ($llenar_grilla = mysql_fetch_array($registros_grilla)) {
            $nombre   = $llenar_grilla["nombres"];
            $id       = $llenar_grilla["idtrabajador"];
            $cedula   = $llenar_grilla["cedula"];
            $apellido = $llenar_grilla["apellidos"];
            ?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.document.getElementById('nombre_trabajador').value = '<?=$nombre?> <?=$apellido?>', opener.document.getElementById('id_trabajador').value = '<?=$id?>', opener.consultarAsociados(), window.close()">
								<?php
echo "<td align='right' class='Browse' width='5%'>" . $i . "</td>";
            echo "<td align='center' class='Browse' width='10%'>" . $llenar_grilla["nro_ficha"] . "</td>";
            echo "<td align='right' class='Browse' width='12%'>" . $llenar_grilla["cedula"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["apellidos"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["nombres"] . "</td>";

            ?>
                                    <td align='center' class='Browse' width='7%'>
                                    	<a href='#' onClick="opener.document.getElementById('nombre_trabajador').value = '<?=$nombre?> <?=$apellido?>', opener.document.getElementById('id_trabajador').value = '<?=$id?>', opener.consultarAsociados(), window.close()"><img src='../../imagenes/validar.png'></a></td>
                                    <?
            echo "</tr>";
            $i++;
        }
    }

} else if ($_REQUEST["frm"] == "vacaciones") {
    if ($existen_registros == 0) {
        $i = 1;
        while ($llenar_grilla = mysql_fetch_array($registros_grilla)) {
            $nombre   = $llenar_grilla["nombres"];
            $id       = $llenar_grilla["idtrabajador"];
            $cedula   = $llenar_grilla["cedula"];
            $apellido = $llenar_grilla["apellidos"];

            $idubicacion    = $llenar_grilla["idunidad_funcional"];
            $idcargo        = $llenar_grilla["idcargo"];
            $idcentro_costo = $llenar_grilla["centro_costo"];
            $fecha_ingreso  = $llenar_grilla["fecha_ingreso"];
            $nro_ficha      = $llenar_grilla["nro_ficha"];

            ?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="ponCedulaVacaciones('<?=$id?>', '<?=$nombre?>', '<?=$cedula?>', '<?=$apellido?>')">
								<?php
echo "<td align='right' class='Browse' width='5%'>" . $i . "</td>";
            echo "<td align='center' class='Browse' width='10%'>" . $llenar_grilla["nro_ficha"] . "</td>";
            echo "<td align='right' class='Browse' width='12%'>" . $llenar_grilla["cedula"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["apellidos"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["nombres"] . "</td>";

            ?>
                                    <td align='center' class='Browse' width='7%'>
                                    	<a href='#' onClick="ponCedulaVacaciones('<?=$id?>', '<?=$nombre?>', '<?=$cedula?>', '<?=$apellido?>')"><img src='../../imagenes/validar.png'></a></td>
                                    <?
            echo "</tr>";
            $i++;
        }
    }

} else if ($_REQUEST["frm"] == "rep_constancia") {
    if ($existen_registros == 0) {
        $i = 1;
        while ($llenar_grilla = mysql_fetch_array($registros_grilla)) {
            $nombre   = $llenar_grilla["nombres"];
            $id       = $llenar_grilla["idtrabajador"];
            $cedula   = $llenar_grilla["cedula"];
            $apellido = $llenar_grilla["apellidos"];

            $idubicacion    = $llenar_grilla["idunidad_funcional"];
            $idcargo        = $llenar_grilla["idcargo"];
            $idcentro_costo = $llenar_grilla["centro_costo"];
            $fecha_ingreso  = $llenar_grilla["fecha_ingreso"];
            $nro_ficha      = $llenar_grilla["nro_ficha"];

            ?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="ponrep_constancia('<?=$id?>', '<?=$nombre?>', '<?=$cedula?>', '<?=$apellido?>')">
								<?php
echo "<td align='right' class='Browse' width='5%'>" . $i . "</td>";
            echo "<td align='center' class='Browse' width='10%'>" . $llenar_grilla["nro_ficha"] . "</td>";
            echo "<td align='right' class='Browse' width='12%'>" . $llenar_grilla["cedula"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["apellidos"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["nombres"] . "</td>";

            ?>
                                    <td align='center' class='Browse' width='7%'>
                                    	<a href='#' onClick="ponrep_constancia('<?=$id?>', '<?=$nombre?>', '<?=$cedula?>', '<?=$apellido?>')"><img src='../../imagenes/validar.png'></a></td>
                                    <?
            echo "</tr>";
            $i++;
        }
    }
} else if ($_REQUEST["frm"] == "rep_carga_familiar") {
    if ($existen_registros == 0) {
        $i = 1;
        while ($llenar_grilla = mysql_fetch_array($registros_grilla)) {
            $nombre   = $llenar_grilla["nombres"];
            $id       = $llenar_grilla["idtrabajador"];
            $cedula   = $llenar_grilla["cedula"];
            $apellido = $llenar_grilla["apellidos"];

            $idubicacion    = $llenar_grilla["idunidad_funcional"];
            $idcargo        = $llenar_grilla["idcargo"];
            $idcentro_costo = $llenar_grilla["centro_costo"];
            $fecha_ingreso  = $llenar_grilla["fecha_ingreso"];
            $nro_ficha      = $llenar_grilla["nro_ficha"];

            ?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="ponrep_carga_familiar('<?=$id?>', '<?=$nombre?>', '<?=$cedula?>', '<?=$apellido?>')">
								<?php
echo "<td align='right' class='Browse' width='5%'>" . $i . "</td>";
            echo "<td align='center' class='Browse' width='10%'>" . $llenar_grilla["nro_ficha"] . "</td>";
            echo "<td align='right' class='Browse' width='12%'>" . $llenar_grilla["cedula"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["apellidos"] . "</td>";
            echo "<td align='left' class='Browse' width='33%'>" . $llenar_grilla["nombres"] . "</td>";

            ?>
                                    <td align='center' class='Browse' width='7%'>
                                    	<a href='#' onClick="ponrep_carga_familiar('<?=$id?>', '<?=$nombre?>', '<?=$cedula?>', '<?=$apellido?>')"><img src='../../imagenes/validar.png'></a></td>
                                    <?
            echo "</tr>";
            $i++;
        }
    }
}
?>
						</table>
						</form>
					</td>
				</tr>
			</table>
		</div>

</body>
</html>
