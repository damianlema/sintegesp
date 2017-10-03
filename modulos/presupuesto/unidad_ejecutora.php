<?php
if ($_POST["ingresar"]) {
    $_GET["accion"] = 164;
}

$emergente = $_POST["emergente"];

$m = $_POST["modoactual"];
if ($m != "") {
    $modo = $m;
}

if ($buscar_registros == 0) {
    $registros_grilla = mysql_query("select * from unidad_ejecutora
												where status='a' order by codigo"
        , $conexion_db);
    if (mysql_num_rows($registros_grilla) <= 0) {
        $existen_registros = 1;
    }
}

if (isset($_POST["buscar"])) {
    $texto_buscar   = $_POST["textoabuscar"];
    $campo_busqueda = $_POST["tipobusqueda"];
    $sql            = "select * from unidad_ejecutora where status='a'";
    if ($texto_buscar != "") {
        if ($texto_buscar == "*") {
            $registros_grilla = mysql_query($sql, $conexion_db);
        } else {
            if ($campo_busqueda == "c") {
                $registros_grilla = mysql_query($sql . " and codigo like '$texto_buscar%' order by codigo", $conexion_db);
            }
            if ($campo_busqueda == "d") {
                $registros_grilla = mysql_query($sql . " and denominacion like '$texto_buscar%' order by denominacion", $conexion_db);
            }
        }
    }
    if (mysql_num_rows($registros_grilla) <= 0) {
        $existen_registros = 1;
    }
}

if ($_POST["idcargo"] != "") {
    $sql_validar_cargo = mysql_query("select * from cargos
														where idcargo=" . $_POST["idcargo"] . " and status='a'"
        , $conexion_db);
    if (mysql_num_rows($sql_validar_cargo) > 0) {
        $regcargos = mysql_fetch_assoc($sql_validar_cargo);
    }
}

if ($_GET["accion"] == 165 and isset($_GET["c"])) {
    // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario

    $sql = mysql_query("select * from unidad_ejecutora
										where codigo like '" . $_GET['c'] . "'"
        , $conexion_db);
    $regunidad_ejecutora = mysql_fetch_assoc($sql);

    /*if (!$emergente)
$idcargo=$regunidad_ejecutora["idCargo"];
else
$idcargo=$_POST["idcargo"];

$sql_validar_cargo=mysql_query("select * from cargos
where idcargo=".$idcargo." and status='a'"
,$conexion_db);
$regcargos=mysql_fetch_assoc($sql_validar_cargo);*/
}

if ($_GET["accion"] == 166 and isset($_GET["c"])) {
    // si entra para eliminar busca el registro para cargar los datos en el formulario

    $sql = mysql_query("select * from unidad_ejecutora
										where codigo like '" . $_GET['c'] . "'"
        , $conexion_db);
    $regunidad_ejecutora = mysql_fetch_assoc($sql);
    /*$idcargo=$regunidad_ejecutora["idCargo"];
$sql_validar_cargo=mysql_query("select * from cargos
where idcargo=".$idcargo." and status='a'"
,$conexion_db);
$regcargos=mysql_fetch_assoc($sql_validar_cargo);*/
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<!-- <META HTTP-EQUIV="Refresh" CONTENT="600; URL=lib/cerrar.php"> -->
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		if (document.frmunidad_ejecutora.codigo.value.length==0){
			alert("Debe escribir un C&oacute;digo para el Sector")
			document.frmunidad_ejecutora.codigo.focus()
			return false;
		}
		if (document.frmunidad_ejecutora.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para el Sector")
			document.frmunidad_ejecutora.denominacion.focus()
			return false;
		}
	}

function abreVentana(){
	m=document.frmunidad_ejecutora.modoactual.value;
	miPopup=window.open("lib/listas/lista_cargos.php?m="+m,"cargos","width=600,height=400,scrollbars=yes")
	miPopup.focus()
}

function solonumeros(e){
var key;
if(window.event)
	{key=e.keyCode;}
else if(e.which)
	{key=e.which;}
if (key < 48 || key > 57)
	{return false;}
return true;
}
// end hiding from old browsers -->
</SCRIPT>
</head>
	<body>
	<br>
	<h4 align=center>Unidades Ejecutoras</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<form name="frmunidad_ejecutora" action="principal.php?modulo=2&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">
	<input type="hidden" name="modoactual" id="modoactual" <?php echo 'value="' . $_GET["accion"] . '"'; ?>>
	<input type="hidden" id="codigo2" name="codigo2" maxlength="4" size="4" <?php if (isset($_POST["codigo"])) {echo 'value="' . $_POST["codigo"] . '"';} else {echo 'value="' . $regunidad_ejecutora["codigo"] . '"';}?>>
		<table align=center cellpadding=2 cellspacing=0 width="60%">
			<tr>
				<td align='right' class='viewPropTitle'>C&oacute;digo:</td>
				<td class=''><input type="text" id="codigo" name="codigo" maxlength="4" size="4" onKeyPress="javascript:return solonumeros(event)" <?php if (isset($_POST["codigo"])) {echo 'value="' . $_POST["codigo"] . '"';} else {echo 'value="' . $regunidad_ejecutora["codigo"] . '"';}if ($_GET["accion"] == 166) {
    echo "disabled";
}
?>>

                 &nbsp;<a href="principal.php?modulo=2&accion=40"><img src="imagenes/nuevo.png" border="0" title="Nueva Actividad">


          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='block';">X</a></div>
          <table id="tableImprimir">
          	<tr>
            	<td>Ordenar Por: </td>
                <td>
                	<select name="ordenarPor" id="ordenarPor">
                    	<option value="denominacion">Denominaci&oacute;n</option>
                        <option value="responsable">Responsable</option>
                    </select>
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/presupuesto/reportes.php?nombre=unidade&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
                </td>
            </tr>
          </table>
          <iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>
          </div>

				</td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class=''><input type="text" name="denominacion" maxlength="255" size="65" id="denominacion" <?php if (isset($_POST["codigo"])) {echo 'value="' . $_POST["denominacion"] . '"';}if ($_GET["accion"] == 165 || $_GET["accion"] == 166) {echo 'value="' . $regunidad_ejecutora['denominacion'] . '"';}if ($_GET["accion"] == 166) {
    echo "disabled";
}
?>></td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Responsable:</td>
				<td class=''><input type="text" name="responsable" maxlength="255" size="65" id="responsable" <?php if (isset($_POST["codigo"])) {echo 'value="' . $_POST["responsable"] . '"';}if ($_GET["accion"] == 165 || $_GET["accion"] == 166) {echo 'value="' . $regunidad_ejecutora['responsable'] . '"';}if ($_GET["accion"] == 166) {
    echo "disabled";
}
?>></td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Cargo:</td>
				<td class=''><input type="text" name="cargos" id="cargos" maxlength="95" size="45"  <?php if (!$_POST["idcargo"]) {echo ' value="' . $regunidad_ejecutora["cargo"] . '"';} else {echo ' value="' . $regcargos["denominacion"] . '"';}if ($_GET["accion"] == 166) {
    echo "disabled";
}
?>>
				<?php if ($_GET["accion"] != 166) {?><button name="listado" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="abreVentana()"><img src='imagenes/search0.png'> <?php }?>
				</button>
				</td>
			</tr>
			<input type="hidden" name="idcargo" maxlength="5" size="5" id="idcargo" <?php echo 'value="' . $regcargos['idcargo'] . '"'; ?>>
			<input type="hidden" name="emergente" maxlength="5" size="5" id="emergente" <?php echo 'value="' . $_POST['emergente'] . '"'; ?>>
		</table>
		<table align=center cellpadding=2 cellspacing=0>
			<tr><td>
			<?php

if ($_GET["accion"] != 165 and $_GET["accion"] != 166 and in_array(164, $privilegios) == true) {
    echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
}

if ($_GET["accion"] == 165 and in_array($_GET["accion"], $privilegios) == true) {
    echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
}

if ($_GET["accion"] == 166 and in_array($_GET["accion"], $privilegios) == true) {
    echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
}
?>
				<input type="reset" value="Reiniciar" class="button">
			</td></tr>
		</table>
	</form>
	<br>

	<form name="buscar" action="" method="POST">
	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Buscar:</td>
			<td class='viewProp'><input type="text" name="textoabuscar" maxlength="60" size="30"></td>
			<td align='right' class='viewPropTitle'>Por:</td>
			<td class='viewProp'>
				<select name="tipobusqueda">
					<option VALUE="c">C&oacute;digo</option>
					<option VALUE="d">Denominaci&oacute;n</option>
				</select>
			</td>
			<td>
				<input align=center class="button" name="buscar" type="submit" value="Buscar">
				</a>
			</td>
		</tr>
	</table>
	</form>
	<br>
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<form name="grilla" action="" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="80%">
							<thead>
								<tr>
									<td align="center" class="Browse">C&oacute;digo</td>
									<td align="center" class="Browse">Denominaci&oacute;n</td>
									<td align="center" class="Browse">Responsable</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php //  llena la grilla con los registros de la tabla de grupos
if ($existen_registros == 0) {
    while ($llenar_grilla = mysql_fetch_array($registros_grilla)) {
        ?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
echo "<td align='center' class='Browse'>" . $llenar_grilla["codigo"] . "</td>";
        echo "<td align='left' class='Browse'>" . $llenar_grilla["denominacion"] . "</td>";
        echo "<td align='left' class='Browse'>" . $llenar_grilla["responsable"] . "</td>";
        $c = $llenar_grilla["codigo"];
        $m = 1;
        $e = 2;
        if (in_array(165, $privilegios) == true) {
            echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=165&c=$c&m=$m' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
        }
        if (in_array(166, $privilegios) == true) {
            echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=166&c=$c&e=$e' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
        }
        echo "</tr>";
    }
} ?>
						</table>
						</form>
					</td>
				</tr>
			</table>
		</div>
<script> document.frmunidad_ejecutora.codigo.focus() </script>
</body>
</html>

<?php
if ($_POST) {

    $codigo       = $_POST["codigo"];
    $denominacion = strtoupper($_POST["denominacion"]);
    $responsable  = strtoupper($_POST["responsable"]);
    $cargo        = strtoupper($_POST["cargos"]);

    $busca_existe_registro = mysql_query("select * from unidad_ejecutora where codigo like '" . $_POST['codigo'] . "'  and status='a'", $conexion_db);
    if ($_GET["accion"] == 164 and in_array(164, $privilegios) == true) {
        if (mysql_num_rows($busca_existe_registro) > 0) {

            ?>
			<script>
			mostrarMensajes("error", "Disculpe el registro que esta insertando ya Existe, Vuelva a Intentarlo");
			setTimeout("window.location.href='principal.php?modulo=2&accion=40'",5000);
			</script>

		<?

        } else {
            mysql_query("insert into unidad_ejecutora
									(codigo,denominacion,responsable,Cargo,usuario,fechayhora,status)
							values ('$codigo','$denominacion','$responsable','$cargo','$login','$fh','a')"
                , $conexion_db);
            registra_transaccion('Ingresar Unidad Ejecutora (' . $denominacion . ')', $login, $fh, $pc, 'unidad_ejecutora', $conexion_db);

            ?>
				<script>
			mostrarMensajes("exito", "El registro se Ingreso con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=40'",5000);
			</script>

		<?

        }
    }
    if ($_GET["accion"] == 165 and in_array(165, $privilegios) == true and !$_POST["buscar"]) {
        mysql_query("update unidad_ejecutora set
										denominacion='" . $denominacion . "',
										responsable='" . $responsable . "',
										cargo='" . $cargo . "',
										fechayhora='" . $fh . "',
										usuario='" . $login . "'
										where 	codigo = '$codigo' and status = 'a'", $conexion_db);
        registra_transaccion('Modificar Unidad Ejecutora (' . $denominacion . ')', $login, $fh, $pc, 'unidad_ejecutora', $conexion_db);

        ?>
				<script>
			mostrarMensajes("exito", "El registro se Modifico con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=40'",5000);
			</script>

		<?

    }
    if ($_GET["accion"] == 166 and in_array(166, $privilegios) == true and !$_POST["buscar"]) {
        $codigo       = $_POST["codigo2"];
        $sql          = mysql_query("select * from unidad_ejecutora where codigo = '$codigo' and status = 'a'");
        $bus          = mysql_fetch_array($sql);
        $sql_eliminar = mysql_query("delete from unidad_ejecutora where codigo = '$codigo' and status = 'a'", $conexion_db);
        if (!$sql_eliminar) {
            registra_transaccion('Eliminar Unidad Ejecutora (ERROR) (' . $bus["denominacion"] . ')', $login, $fh, $pc, 'unidad_ejecutora', $conexion_db);

            ?>
				<script>
			mostrarMensajes("error", "Disculpe el registro que intenta eliminar esta relacionado con otro registro, por ello no puede ser eliminado");
			setTimeout("window.location.href='principal.php?modulo=2&accion=40'",5000);
			</script>

		<?

        } else {
            registra_transaccion('Eliminar Unidad Ejecutora (' . $bus["denominacion"] . ')', $login, $fh, $pc, 'unidad_ejecutora', $conexion_db);

            ?>
				<script>
					mostrarMensajes("exito", "El registro se Elimino con Exito");
					setTimeout("window.location.href='principal.php?modulo=2&accion=40'",5000);
					</script>

				<?
        }
    }
}
?>
