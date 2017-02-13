<?php
if ($_POST["ingresar"]) {
    $_GET["accion"] = 197;
}

if ($buscar_registros == 0) {
    $registros_grilla = mysql_query("select * from  tipo_movimiento_personal
												where status='a'"
        , $conexion_db);
    if (mysql_num_rows($registros_grilla) <= 0) {
        $existen_registros = 1;
    }
}

if (isset($_POST["buscar"])) {
    $texto_buscar = $_POST["textoabuscar"];
    $sql          = "select * from  tipo_movimiento_personal where status='a'";
    if ($texto_buscar != "") {
        if ($texto_buscar == "*") {
            $registros_grilla = mysql_query($sql, $conexion_db);
        } else {
            $registros_grilla = mysql_query($sql . " and denominacion like '$texto_buscar%'", $conexion_db);
        }
    }
    if (mysql_num_rows($registros_grilla) <= 0) {
        $existen_registros = 1;
    }
}

if ($_GET["accion"] == 198 || $_GET["accion"] == 199) {
    // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
    $sql = mysql_query("select * from  tipo_movimiento_personal
										where idtipo_movimiento like '" . $_GET['c'] . "'"
        , $conexion_db);
    $registro_actualizar = mysql_fetch_assoc($sql);
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		if (document.frmtipomovimiento.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para el Tipo de Movimiento.")
			document.frmtipomovimiento.denominacion.focus()
			return false;
		}
	}
// end hiding from old browsers -->
</SCRIPT>

	<body>
	<br>
	<h4 align=center>Tipo de Movimiento</h4>
	<h2 class="sqlmVersion"></h2>

		<form name="frmtipomovimiento" action="principal.php?modulo=1&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">

	<table align=center cellpadding=2 cellspacing=0>
			<input type="hidden" name="tabla" value="9">  <?// campo oculto para enviar por el POST el numero de tabla a modificar?>
			<input type="hidden" name="user" value="<?php echo $registro_usuario["login"]; // envia el usuario que esta haciendo la modificacion         ?>">
			<input type="hidden" name="id_tipomovimiento" value="<?php echo $registro_actualizar["idtipo_movimiento"]; //envia el id del banco a modificar          ?>">
			<tr>
			  <td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
			  <td class='viewProp'><input type="text" name="denominacion" value="<?php echo $registro_actualizar["denominacion"]; ?>" maxlength="40" size="40" <?php if ($_GET["accion"] == 199) {
    echo "disabled";
}
?>>
&nbsp;<a href="principal.php?modulo=1&accion=28"><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Movimiento"></a></td>
	  </tr>
		<tr>

		  	<td align="right" class='viewPropTitle'>Relaci&oacute;n Laboral: </td>
		    <td><label>
		      <input type="radio" name="relacion_laboral" id="relacion_laboral"  <?if ($registro_actualizar["relacion_laboral"] == 'egreso') {echo "checked";}?> value="egreso"/>
		    </label>
			Egreso&nbsp;
			<label>
			<input type="radio" name="relacion_laboral" id="relacion_laboral" <?if ($registro_actualizar["relacion_laboral"] == 'reingreso') {echo "checked";}?> value="reingreso"/>
			</label>
			&nbsp;Reingreso</td>
	  	</tr>
			<tr>
			  <td align='right' class='viewPropTitle'>Sin goce de Sueldo?:</td>
			  <td class='viewProp'><label>
			    <input type="checkbox" name="goce_sueldo" id="goce_sueldo" <?if ($registro_actualizar["goce_sueldo"] == 'si') {echo "checked";}?> value="si">
			  </label></td>
	  </tr>
			<tr>
			  <td align='right' valign="top" class='viewPropTitle'>Afecta:</td>
				<td class='viewProp'><input type="checkbox" name="afecta_cargo" id="afecta_cargo" <?if ($registro_actualizar["afecta_cargo"] == 'si') {echo "checked";}?> value="si">
				  Cargo
				  <label></label>
			    <br>
			    <input type="checkbox" name="afecta_ubicacion" id="afecta_ubicacion" <?if ($registro_actualizar["afecta_ubicacion"] == 'si') {echo "checked";}?> value="si">
			    Ubicacion
			    <label></label>
			    <br>
			    <label>
			    <input type="checkbox" name="afecta_tiempo" id="afecta_tiempo" <?if ($registro_actualizar["afecta_tiempo"] == 'si') {echo "checked";}?> value="si">
			    </label>
			    Tiempo
                <br>
			    <label>
			    <input type="checkbox" name="afecta_centro_costo" id="afecta_centro_costo" <?if ($registro_actualizar["afecta_centro_costo"] == 'si') {echo "checked";}?> value="si">
			    </label>
		      Centro de Costo<br>
              <label>
			    <input type="checkbox" name="afecta_ficha" id="afecta_ficha" <?if ($registro_actualizar["afecta_ficha"] == 'si') {echo "checked";}?> value="si">
			    </label>
		      Nomenclatura Ficha<br></td>
			</tr>
	</table>
	</table>
	<table align=center cellpadding=2 cellspacing=0>
		<tr><td>
<?php

if ($_GET["accion"] != 198 and $_GET["accion"] != 199 and in_array(197, $privilegios) == true) {
    echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
}

if ($_GET["accion"] == 198 and in_array($_GET["accion"], $privilegios) == true) {
    echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
}

if ($_GET["accion"] == 199 and in_array($_GET["accion"], $privilegios) == true) {
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
			<td class='viewProp'><input type="text" name="textoabuscar" maxlength="30" size="30"></td>
			<td>
				<input align=center class="button" name="buscar" type="submit" value="Buscar">
				</a>
			</td>
		</tr>
	</table>
	</form>

	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<form name="grilla" action="" method="POST">
						<table class="Browse" cellpadding="0" cellspacing="0" width="40%" align="center">
							<thead>
								<tr>
									<td align="center" class="Browse">Denominaci&oacute;n</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php //  llena la grilla con los registros de la tabla de grupos
if ($existen_registros == 0) {
    while ($llenar_grilla = mysql_fetch_array($registros_grilla)) {
        ?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
echo "<td align='left' class='Browse'>" . $llenar_grilla["denominacion"] . "</td>";
        $c = $llenar_grilla["idtipo_movimiento"];
        if (in_array(198, $privilegios) == true) {
            echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=198&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
        }
        if (in_array(199, $privilegios) == true) {
            echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=199&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
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
<script> document.frmtipomovimiento.denominacion.focus() </script>
</body>
</html>

<?php
if ($_POST) {

    $denominacion           = strtoupper($_POST["denominacion"]);
    $codigo_tipo_movimiento = $_POST["id_tipomovimiento"];
    $relacion_laboral       = $_POST["relacion_laboral"];
    /*if ($_POST["relacion_laboral"] == 'egreso') {
    $relacion_laboral == "egreso";
    }
    if ($_POST["relacion_laboral"] == 'reingreso') {
    $relacion_laboral == "reingreso";
    }*/
    if ($_POST["goce_sueldo"] == 'si') {
        $goce_sueldo == "si";
    } else {
        $goce_sueldo == "no";
    }
    if ($_POST["afecta_cargo"] == 'si') {
        $afecta_cargo == "si";
    } else {
        $afecta_cargo == "no";
    }
    if ($_POST["afecta_ubicacion"] == 'si') {
        $afecta_ubicacion == "si";
    } else {
        $afecta_ubicacion == "no";
    }
    if ($_POST["afecta_tiempo"] == 'si') {
        $afecta_tiempo == "si";
    } else {
        $afecta_tiempo == "no";
    }

    if ($_POST["afecta_centro_costo"] == 'si') {
        $afecta_centro_costo == "si";
    } else {
        $afecta_centro_costo == "no";
    }

    if ($_POST["afecta_ficha"] == 'si') {
        $afecta_ficha == "si";
    } else {
        $afecta_ficha == "no";
    }

    if ($_GET["accion"] == 197 and in_array(194, $privilegios) == true) {
        $busca_existe_registro = mysql_query("select * from  tipo_movimiento_personal
															where denominacion = '" . $denominacion . "'
															and status='a'"
            , $conexion_db);

        if (mysql_num_rows($busca_existe_registro) > 0) {
            mensaje("Disculpe el regsitro que insertpo ya existe, vuelvalo a intentar");
            redirecciona("principal.php?modulo=1&accion=28");
        } else {
            mysql_query("insert into tipo_movimiento_personal
										(denominacion,
										relacion_laboral,
										goce_sueldo,
										afecta_cargo,
										afecta_ubicacion,
										afecta_tiempo,
										afecta_centro_costo,
										afecta_ficha,
										usuario,
										fechayhora,
										status)
									values (
										'$denominacion',
										'$relacion_laboral',
										'$goce_sueldo',
										'$afecta_cargo',
										'$afecta_ubicacion',
										'$afecta_tiempo',
										'$afecta_centro_costo',
										'$afecta_ficha',
										'$login',
										'$fh',
										'a')"
                , $conexion_db);

            registra_transaccion('Ingresar Tipos de Movimientos (' . $denominacion . ')', $login, $fh, $pc, ' tipo_movimiento_personal', $conexion_db);
            mensaje("EL registro se Ingreso con Exito");
            redirecciona("principal.php?modulo=1&accion=28");

        }
    }

    if ($_GET["accion"] == 198 and in_array(198, $privilegios) == true and !$_POST["buscar"]) {
        mysql_query("update tipo_movimiento_personal set denominacion='" . strtoupper($denominacion) . "',
											relacion_laboral='" . $relacion_laboral . "',
											goce_sueldo='" . $goce_sueldo . "',
											afecta_cargo='" . $afecta_cargo . "',
											afecta_ubicacion='" . $afecta_ubicacion . "',
											afecta_tiempo='" . $afecta_tiempo . "',
											afecta_centro_costo='" . $afecta_centro_costo . "',
											afecta_ficha='" . $afecta_ficha . "',
											usuario='" . $login . "',
											fechayhora='" . $fh . "'
												where idtipo_movimiento like '$codigo_tipo_movimiento'"
            , $conexion_db) or die(mysql_error());
        registra_transaccion('Modificar Tipo de Movimientos (' . $denominacion . ')', $login, $fh, $pc, 'tipo_movimiento', $conexion_db);
        mensaje("EL registro se Modifico con Exito");
        redirecciona("principal.php?modulo=1&accion=28");
    }
    if ($_GET["accion"] == 199 and in_array(199, $privilegios) == true and !$_POST["buscar"]) {
        $sql_consulta = mysql_query("select * from movimientos_personal where idtipo_movimiento = '" . $codigo_tipo_movimiento . "'");
        $num_consulta = mysql_num_rows($sql_consulta);

        if ($num_consulta == 0) {

            $sql          = mysql_query("select * from tipo_movimiento_personal where idtipo_movimiento = '$codigo_tipo_movimiento'");
            $bus          = mysql_fetch_array($sql);
            $sql_eliminar = mysql_query("delete from tipo_movimiento_personal where idtipo_movimiento = '$codigo_tipo_movimiento'"
                , $conexion_db);
            if (!$sql_eliminar) {
                registra_transaccion('Eliminar Tipos de Movimientos (ERROR) (' . $bus["denominacion"] . ')', $login, $fh, $pc, 'tipo_movimiento', $conexion_db);
                mensaje("Disculpe el registro que intenta eliminar esta relacionado con otro regsitro dentro del sistema, por ello no puede ser eliminado");
                redirecciona("principal.php?modulo=1&accion=28");

            } else {
                registra_transaccion('Eliminar Tipos de Movimientos (' . $bus["denominacion"] . ')', $login, $fh, $pc, 'tipo_movimiento', $conexion_db);
                mensaje("EL registro se Elimino con Exito");
                redirecciona("principal.php?modulo=1&accion=28");

            }

        } else {
            mensaje("Disculpe el Tipo de movimiento que selecciono no puede ser eliminado debido a que el mismo esta siendo usado por algunos movimientos de personal");
            redirecciona("principal.php?modulo=1&accion=28");
        }
    }
}
?>
