<?
session_start();
include "../../../conf/conex.php";
include "../../../funciones/funciones.php";
$root_server = $_SERVER['DOCUMENT_ROOT'].$_SESSION["directorio_root"];
require($root_server."/conf/class.Conexion.php");
$conexion = Conectarse();
extract($_POST);
extract($_SESSION);

function diferenciaEntreFechas($fechaInicio, $fechaActual)
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
        echo "La fecha de inicio ha de ser anterior a la fecha Actual";
    } else {
        if ($mesInicio <= $mesActual) {
            $anios = $anioActual - $anioInicio;
            if ($diaInicio <= $diaActual) {
                $meses = $mesActual - $mesInicio;
                $dies  = $diaActual - $diaInicio;
            } else {
                if ($mesActual == $mesInicio) {
                    $anios = $anios - 1;
                }
                $meses = ($mesActual - $mesInicio - 1 + 12) % 12;
                $dies  = $b - ($diaInicio - $diaActual);
            }
        } else {
            $anios = $anioActual - $anioInicio - 1;
            if ($diaInicio > $diaActual) {
                $meses = $mesActual - $mesInicio - 1 + 12;
                $dies  = $b - ($diaInicio - $diaActual);
            } else {
                $meses = $mesActual - $mesInicio + 12;
                $dies  = $diaActual - $diaInicio;
            }
        }
        return $anios . "|.|" . $meses . "|.|" . $dies;
    }

}

//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************* DATOS BASICOS ********************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

if ($ejecutar == "consultarFicha") {
    $sql_nomenclatura = mysql_query("select * from nomenclatura_fichas where idnomenclatura_fichas ='" . $nomenclatura_ficha . "'");
    $bus_nomenclatura = mysql_fetch_array($sql_nomenclatura);

    $numero_con_ceros = str_pad($bus_nomenclatura["numero"], 4, "0", STR_PAD_LEFT);
    echo $numero_con_ceros;
}

if ($ejecutar == "ingresarTrabajador") {
    $sql_cedula = mysql_query("select * from trabajador where cedula='" . $cedula . "'");
    $num_cedula = mysql_num_Rows($sql_cedula);

    if ($num_cedula > 0) {
        echo "cedula_repetida";
    } else {

        /*$can = strlen($nro_ficha);
        $result = 6-$can;
        for($i=1; $i<=$result;$i++){
        $ceros .= "0";
        }
        $nro_ficha = $ceros.$nro_ficha;
         */

        $sql_nomenclatura = mysql_query("select * from nomenclatura_fichas where idnomenclatura_fichas ='" . $nomenclatura_ficha . "'");
        $bus_nomenclatura = mysql_fetch_array($sql_nomenclatura);

        $numero_con_ceros = str_pad($bus_nomenclatura["numero"], 4, "0", STR_PAD_LEFT);
        $nro_ficha        = $bus_nomenclatura["descripcion"] . $numero_con_ceros;

        $result = mysql_query("insert into trabajador
                                                    (apellidos,
                                                    nombres,
                                                    idnacionalidad,
                                                    cedula,
                                                    rif,
                                                    nro_pasaporte,
                                                    sexo,
                                                    fecha_nacimiento,
                                                    lugar_nacimiento,
                                                    idedo_civil,
                                                    direccion,
                                                    telefono_habitacion,
                                                    telefono_movil,
                                                    correo_electronico,
                                                    status,
                                                    fechayhora,
                                                    usuario,
                                                    estacion,
                                                    nro_ficha,
                                                    fecha_ingreso,
                                                    fecha_inicio_continuidad,
                                                    idcargo,
                                                    idunidad_funcional,
                                                    centro_costo,
                                                    activo_nomina)
                                            values
                                                    ('$apellidos',
                                                    '$nombres',
                                                    '$idnacionalidad',
                                                    '$cedula',
                                                    '$rif',
                                                    '$nro_pasaporte',
                                                    '$sexo',
                                                    '$fecha_nacimiento',
                                                    '$lugar_nacimiento',
                                                    '$idedo_civil',
                                                    '$direccion',
                                                    '$telefono_habitacion',
                                                    '$telefono_movil',
                                                    '$correo_electronico',
                                                    'a',
                                                    '$fh',
                                                    '$login',
                                                    '$pc',
                                                    '$nro_ficha',
                                                    '$fecha_ingreso',
                                                    '$fecha_continuidad',
                                                    '$idcargo',
                                                    '$idubicacion_funcional',
                                                    '$idcentro_costo',
                                                    'si')") or die(mysql_error());

        echo mysql_insert_id() . "|.|exito";

        if ($result) {

            $sql_movimientos = mysql_query("insert into movimientos_personal(idtrabajador,
                                                                            fecha_movimiento,
                                                                            idtipo_movimiento,
                                                                            justificacion,
                                                                            fecha_ingreso,
                                                                            idcargo,
                                                                            idubicacion_funcional,
                                                                            centro_costo)values('" . mysql_insert_id() . "',
                                                                                                     '" . $fecha_ingreso . "',
                                                                                                     '0',
                                                                                                     'INGRESO',
                                                                                                     '" . $fecha_ingreso . "',
                                                                                                     '" . $idcargo . "',
                                                                                                     '" . $idubicacion_funcional . "',
                                                                                                     '" . $idcentro_costo . "')") or die("AQUI:" . mysql_error());

            $sql_consulta_usuarios = mysql_query("select * from usuarios where cedula = '" . $cedula . "'");
            $num_consulta_usuarios = mysql_num_rows($sql_consulta_usuarios);

            if ($num_consulta_usuarios == 0) {
                $p       = substr($nombres, 0, 1);
                $espacio = strpos($apellidos, ' ');
                $ape     = substr($apellidos, 0, $espacio);

                $user = $p . $ape;
                //echo $user;
                $c                    = $user . ".1234";
                $sql_ingresar_usuario = mysql_query("insert into usuarios(
                                                                    apellidos,
                                                                    nombres,
                                                                    cedula,
                                                                    login,
                                                                    clave,
                                                                    status,
                                                                    fechayhora,
                                                                    estacion)VALUES(
                                                                                    '" . $apellidos . "',
                                                                                    '" . $nombres . "',
                                                                                    '" . $cedula . "',
                                                                                    '" . $user . "',
                                                                                    '" . $c . "',
                                                                                    'a',
                                                                                    '" . $fh . "',
                                                                                    '" . $pc . "')") or die("error creando el usuario" . mysql_error());
            }

            $sql_consulta = mysql_query("select * from beneficiarios where rif = '" . $cedula . "' || rif = '" . $cedula . "'");
            $num_consulta = mysql_num_rows($sql_consulta);
            if ($num_consulta == 0) {
                $sql_ingresar_beneficiario = mysql_query("insert into beneficiarios(
                                                                                    nombre,
                                                                                    rif,
                                                                                    idtipo_beneficiario,
                                                                                    idtipo_sociedad,
                                                                                    idestado_beneficiario,
                                                                                    idtipos_persona,
                                                                                    idtipo_empresa,
                                                                                    contribuyente_ordinario,
                                                                                    status,
                                                                                    usuario,
                                                                                    fechayhora,
                                                                                    idestado)VALUES(
                                                                                                    '" . $apellidos . " " . $nombres . "',
                                                                                                    '" . $cedula . "',
                                                                                                    '126',
                                                                                                    '17',
                                                                                                    '12',
                                                                                                    '12',
                                                                                                    '9',
                                                                                                    'si',
                                                                                                    'a',
                                                                                                    '" . $login . "',
                                                                                                    '" . $fh . "',
                                                                                                    '1')");
            }
        }

        $sql_nomenclatura = mysql_query("UPDATE nomenclatura_fichas set numero=numero+1
                                            where
                                            idnomenclatura_fichas ='" . $bus_nomenclatura["idnomenclatura_fichas"] . "'");

    }

}

if ($ejecutar == "modificarTrabajador") {

    $result = mysql_query("update trabajador set    apellidos               = '$apellidos',
                                            nombres                 = '$nombres',
                                            idnacionalidad          = '$idnacionalidad',
                                            rif                     = '$rif',
                                            nro_pasaporte           = '$nro_pasaporte',
                                            sexo                    = '$sexo',
                                            fecha_nacimiento        = '$fecha_nacimiento',
                                            lugar_nacimiento        = '$lugar_nacimiento',
                                            idedo_civil             = '$idedo_civil',
                                            direccion               = '$direccion',
                                            telefono_habitacion     = '$telefono_habitacion',
                                            telefono_movil          = '$telefono_movil',
                                            correo_electronico      = '$correo_electronico'
                                            where idtrabajador      = '" . $idtrabajador . "'") or die(mysql_error());
    echo "exito";

}

if ($ejecutar == "eliminarTrabajador") {
    $sql_eliminar = mysql_query("update trabajador set status = 'e' where idtrabajador = '" . $idtrabajador . "'");
}

if ($ejecutar == "consultarId") {
    $sql_consulta = mysql_query("select * from trabajador where cedula = '" . $cedula . "'");
    $num_consulta = mysql_num_rows($sql_consulta);

    if ($num_consulta == 0) {
        echo "no_existe";
    } else {
        $bus_consulta = mysql_fetch_array($sql_consulta);
        echo $bus_consulta["idtrabajador"];
    }
}

if ($ejecutar == "consultarTrabajador") {
    $sql_consulta = mysql_query("select * from trabajador where idtrabajador = '" . $idtrabajador . "'");
    $bus_consulta = mysql_fetch_array($sql_consulta);

    $cambiar_fecha_ingreso = 'no';
    if (in_array(1203, $privilegios) == true) {
        $cambiar_fecha_ingreso = 'si';
    }

    if ($bus_consulta["status"] == "e") {
        $sql_movimiento = mysql_query("select * from movimientos_personal where idtrabajador = '" . $idtrabajador . "'
                                                                            and idtipo_movimiento = '2'");
        $bus_movimiento = mysql_fetch_array($sql_movimiento);
    }

    $sql_foto = mysql_query("select * from registro_fotografico_trabajador where idtrabajador = '" . $idtrabajador . "' and principal = 1");
    $bus_foto = mysql_fetch_array($sql_foto);

    echo $bus_consulta["idtrabajador"] . "|.|" .
    number_format($bus_consulta["cedula"], 0, ",", ".") . "|.|" .
        $bus_consulta["apellidos"] . "|.|" .
        $bus_consulta["nombres"] . "|.|" .
        $bus_consulta["idnacionalidad"] . "|.|" .
        $bus_consulta["rif"] . "|.|" .
        $bus_consulta["nro_pasaporte"] . "|.|" .
        $bus_consulta["sexo"] . "|.|" .
        $bus_consulta["fecha_nacimiento"] . "|.|" .
        $bus_consulta["lugar_nacimiento"] . "|.|" .
        $bus_consulta["idedo_civil"] . "|.|" .
        $bus_consulta["idgrupo_sanguineo"] . "|.|" .
        $bus_consulta["flag_donante"] . "|.|" .
        $bus_consulta["peso"] . "|.|" .
        $bus_consulta["talla"] . "|.|" .
        $bus_consulta["direccion"] . "|.|" .
        $bus_consulta["telefono_habitacion"] . "|.|" .
        $bus_consulta["telefono_movil"] . "|.|" .
        $bus_consulta["correo_electronico"] . "|.|" .
        $bus_consulta["flag_vehiculo"] . "|.|" .
        $bus_consulta["flag_licencia"] . "|.|" .
        $bus_consulta["nombre_emergencia"] . "|.|" .
        $bus_consulta["telefono_emergencia"] . "|.|" .
        $bus_consulta["direccion_emergencia"] . "|.|" .
        $bus_consulta["talla_camisa"] . "|.|" .
        $bus_consulta["talla_pantalon"] . "|.|" .
        $bus_consulta["talla_zapatos"] . "|.|" .
        $bus_consulta["otras_actividades"] . "|.|" .
        $bus_consulta["idcargo"] . "|.|" .
        $bus_consulta["idunidad_funcional"] . "|.|" .
        $bus_consulta["centro_costo"] . "|.|" .
        $bus_consulta["fecha_ingreso"] . "|.|" .
        $bus_consulta["idtipo_nomina"] . "|.|" .
        $bus_consulta["nro_ficha"] . "|.|" .
        $bus_consulta["status"] . "|.|" .
        $bus_movimiento["justificacion"] . "|.|" .
        $bus_consulta["activo_nomina"] . "|.|" .
        $bus_consulta["vacaciones"] . "|.|" .
        $bus_consulta["fecha_inicio_continuidad"] . "|.|" .
        $bus_foto["nombre_imagen"] . "|.|" .
        $bus_consulta["numero_registro_ivss"] . "|.|" .
        $bus_consulta["fecha_registro_ivss"] . "|.|" .
        $bus_consulta["ocupacion_oficio_ivss"] . "|.|" .
        $bus_consulta["otro_ivss"] . "|.|" .
        $cambiar_fecha_ingreso;
}

if ($ejecutar == "cargarImagen") {
    $tipo = substr($_FILES['foto_registroFotografico']['type'], 0, 5);
    $dir  = '../imagenes/';
    if ($tipo == 'image') {
        $nombre_imagen = $_FILES['foto_registroFotografico']['name'];
        while (file_exists($dir . $nombre_imagen)) {
            $partes_img    = explode(".", $nombre_imagen);
            $nombre_imagen = $partes_img[0] . rand(0, 1000000) . "." . $partes_img[1];
        }
        if (!copy($_FILES['foto_registroFotografico']['tmp_name'], $dir . $nombre_imagen)) {
            ?>
                <script>
                parent.document.getElementById('mostrarImagen').innerHTML = "<table><tr><td style='color:#990000; font-weight:bold'>* Disculpe la imagen no se pudo ingresar</td></tr></table>";
                </script>
                <?
        } else {
            $ruta = 'modulos/rrhh/imagenes/' . $nombre_imagen;
        }

        ?>

            <script>
            parent.document.getElementById('nombre_imagen').value = '<?=$nombre_imagen?>';
            parent.document.getElementById('mostrarImagen').innerHTML = "<img src='modulos/rrhh/imagenes/<?=$nombre_imagen?>' width = '100' height='120'>";
            parent.document.getElementById('foto').value = '';
            </script>
            <?

    } else {
        ?>
            <script>
            parent.document.getElementById('mostrarImagen').innerHTML = "<table><tr><td style='color:#990000; font-weight:bold'>* Disculpe el archivo que intenta subir NO es una Imagen</td></tr></table>";
            </script>

            <?
    }

}

//******************************************************************************************************************************
//******************************************************************************************************************************
//************************************************ REGISTRO FOTOGRAFICO ********************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

if ($ejecutar == "subirRegistroFotografico") {
    $sql_ingresar = mysql_query("insert into registro_fotografico_trabajador (idtrabajador,
                                                                                nombre_imagen,
                                                                                principal,
                                                                                descripcion)VALUES('" . $idtrabajador . "',
                                                                                                '" . $nombre_imagen . "',
                                                                                                '0',
                                                                                                '" . $descripcion . "')");
}

if ($ejecutar == "consultar_registroFotografico") {
    ?>
    <table align="center">
    <tr>
    <?
    $i            = 0;
    $sql_consulta = mysql_query("select * from registro_fotografico_trabajador where idtrabajador = '" . $idtrabajador . "'");
    while ($bus_consulta = mysql_fetch_array($sql_consulta)) {
        ?>
        <td>

            <table cellpadding="5" cellspacing="5">
                <tr>
                    <td align="right"><strong onclick="eliminar_registroFotografico('<?=$bus_consulta["idregistro_fotografico_trabajador"]?>')" style="cursor:pointer">X</strong></td>
                </tr>
                <tr>
                    <td align="center"><img src="modulos/rrhh/imagenes/<?=$bus_consulta["nombre_imagen"]?>" width="100" height="100"></td>
                </tr>
                 <tr>
                    <td align="center"><?=$bus_consulta["descripcion"]?></td>
                </tr>
                <tr>
                    <td align="center"><input type="radio" name="imagen_principal" id="imagen_principal" value="<?=$bus_consulta["idregistro_fotografico_trabajador"]?>" style="cursor:pointer" onclick="principal_registroFotografico('<?=$bus_consulta["idregistro_fotografico_trabajador"]?>')" <?if ($bus_consulta["principal"] == 1) {echo "checked";}?>></td>
                </tr>
            </table>

        </td>
        <?
        if ($i == 6) {
            ?>
            </tr>
            <tr>
            <?
            $i = 0;
        } else {
            $i++;
        }

    }
    ?>
    </tr>
    </table>
    <?
}

if ($ejecutar == "eliminar_registroFotografico") {
    $sql_consulta = mysql_query("select * from registro_fotografico_trabajador where idregistro_fotografico_trabajador = '" . $idregistro_fotografico . "'");
    $bus_consulta = mysql_fetch_array($sql_consulta);

    $sql_eliminar = mysql_query("delete from registro_fotografico_trabajador where idregistro_fotografico_trabajador = '" . $idregistro_fotografico . "'");

    if ($sql_eliminar) {
        unlink("../imagenes/" . $bus_consulta["nombre_imagen"]);
    }

}

if ($ejecutar == "principal_registroFotografico") {
    $sql_actualizar = mysql_query("update registro_fotografico_trabajador set principal = '0' where idtrabajador = '" . $idtrabajador . "'");
    $sql_actualizar = mysql_query("update registro_fotografico_trabajador set principal = '1' where idregistro_fotografico_trabajador = '" . $idregistro_fotografico_trabajador . "'");

}

//******************************************************************************************************************************
//******************************************************************************************************************************
//************************************************* DATOS DE EMPLEO *****************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

if ($ejecutar == "modificarDatosEmpleo") {

    /*
    idcargo = '".$idcargo."',
    idunidad_funcional = '".$ubicacion_funcional."',
    centro_costo = '".$centro_costo."',
    fecha_ingreso = '".$fecha_ingreso."',

     */

    $sql_actualizar = mysql_query("update trabajador set fecha_ingreso = '" . $fecha_ingreso . "',
                                                    fecha_inicio_continuidad = '" . $fecha_inicio_continuidad . "',
                                                    vacaciones = '" . $vacaciones . "',
                                                    activo_nomina = '" . $activo_nomina . "'
                                                    where
                                                    idtrabajador = '" . $idtrabajador . "'") or die(mysql_error());

    $sql_validar_existe = mysql_query("select * from movimientos_personal where idtrabajador = '" . $idtrabajador . "'
                                                                                and idtipo_movimiento = '0'");
    $sql_existe_ingreso = mysql_num_rows($sql_validar_existe);
    if ($sql_existe_ingreso == 0) {
        $sql_movimientos = mysql_query("insert into movimientos_personal(idtrabajador,
                                                                            fecha_movimiento,
                                                                            idtipo_movimiento,
                                                                            justificacion,
                                                                            fecha_ingreso,
                                                                            idcargo,
                                                                            idubicacion_funcional,
                                                                            centro_costo)values('" . $idtrabajador . "',
                                                                                                     '" . $fecha_ingreso . "',
                                                                                                     '0',
                                                                                                     'INGRESO',
                                                                                                     '" . $fecha_ingreso . "',
                                                                                                     '" . $idcargo . "',
                                                                                                     '" . $ubicacion_funcional . "',
                                                                                                     '" . $centro_costo . "')") or die("AQUI:" . mysql_error());
    }
}

//******************************************************************************************************************************
//******************************************************************************************************************************
// *************************************************** CARGA FAMILIAR **********************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

if ($ejecutar == "ingresarCargaFamiliar") {
    $result = mysql_query("insert into carga_familiar
                                                    (idtrabajador,
                                                    idparentezco,
                                                    cedula,
                                                    apellidos,
                                                    nombres,
                                                    fecha_nacimiento,
                                                    flag_constancia,
                                                    usuario,
                                                    status,
                                                    fechayhora,
                                                    sexo,
                                                    direccion,
                                                    telefono,
                                                    ocupacion,
                                                    idnacionalidad,
                                                    estacion,
                                                    foto)
                                            values
                                                    ('" . $idtrabajador . "',
                                                    '" . $idparentezco . "',
                                                    '" . $cedula . "',
                                                    '" . $apellido . "',
                                                    '" . $nombre . "',
                                                    '" . $fecha_nacimiento . "',
                                                    '" . $constancia . "',
                                                    '" . $login . "',
                                                    'a',
                                                    '" . $fh . "',
                                                    '" . $sexo . "',
                                                    '" . $direccion . "',
                                                    '" . $telefono . "',
                                                    '" . $ocupacion . "',
                                                    '" . $idnacionalidad . "',
                                                    '" . $pc . "',
                                                    '" . $nombre_foto . "')");
}

if ($ejecutar == "modificarCargaFamiliar") {

    if ($nombre_foto != '') {
        mysql_query("update carga_familiar set
                                                idparentezco= '" . $idparentezco . "',
                                                    cedula = '" . $cedula . "',
                                                    apellidos = '" . $apellido . "',
                                                    nombres = '" . $nombre . "',
                                                    fecha_nacimiento = '" . $fecha_nacimiento . "',
                                                    flag_constancia = '" . $constancia . "',
                                                    sexo = '" . $sexo . "',
                                                    direccion = '" . $direccion . "',
                                                    telefono = '" . $telefono . "',
                                                    ocupacion = '" . $ocupacion . "',
                                                    idnacionalidad = '" . $idnacionalidad . "',
                                                    foto = '" . $nombre_foto . "'
                                                    where idcarga_familiar = '" . $idcarga_familiar . "'") or die(mysql_error());
    } else {

        mysql_query("update carga_familiar set
                                                idparentezco= '" . $idparentezco . "',
                                                    cedula = '" . $cedula . "',
                                                    apellidos = '" . $apellido . "',
                                                    nombres = '" . $nombre . "',
                                                    fecha_nacimiento = '" . $fecha_nacimiento . "',
                                                    flag_constancia = '" . $constancia . "',
                                                    sexo = '" . $sexo . "',
                                                    direccion = '" . $direccion . "',
                                                    telefono = '" . $telefono . "',
                                                    ocupacion = '" . $ocupacion . "',
                                                    idnacionalidad = '" . $idnacionalidad . "'
                                                    where idcarga_familiar = '" . $idcarga_familiar . "'") or die(mysql_error());
    }

}

if ($ejecutar == "eliminarCargaFamiliar") {
    $sql = mysql_query("delete from carga_familiar where idcarga_familiar = '$idcarga_familiar'");
}

if ($ejecutar == "consultarCargaFamiliar") {

    $registros_grilla_carga = mysql_query("select * from carga_familiar where idtrabajador = '" . $idtrabajador . "'");
    ?>

    <table class="Main" cellpadding="0" cellspacing="0" width="80%">
                <tr>
                    <td>
                        <form name="grilla" action="" method="POST">
                        <table class="Browse" cellpadding="0" cellspacing="0" width="80%" align=center>
                            <thead>
                                <tr>
                                    <!--<td class="Browse">&nbsp;</td>-->
                                    <td align="center" class="Browse">Imagen</td>
                                    <td align="center" class="Browse">C&eacute;dula</td>
                                    <td align="center" class="Browse">Apellidos</td>
                                    <td align="center" class="Browse">Nombres</td>
                                    <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
                                </tr>
                            </thead>

                            <?php
    if ($existen_registros == 0) {
        while ($llenar_grilla = mysql_fetch_array($registros_grilla_carga)) {
            ?>
                                <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                <td align='center' class='Browse' width='20%'>
                                <a href="javascript:;" onClick="document.getElementById('imagen_<?=$llenar_grilla["idcarga_familiar"]?>').style.display= 'block'">Ver Imagen</a>
                                <div style=" position:absolute; background-color:#CCCCCC; display:none; width:600px; height:400px; border:#666666 solid 1px" id="imagen_<?=$llenar_grilla["idcarga_familiar"]?>">
                                <div align="right" style="cursor:pointer"><strong onClick="document.getElementById('imagen_<?=$llenar_grilla["idcarga_familiar"]?>').style.display= 'none'">Cerrar</strong></div>
                                <img src="modulos/rrhh/imagenes/carga_familiar/<?=$llenar_grilla["foto"]?>">
                                </div>
                                </td>
                                <td align='right' class='Browse' width='20%'>

                                <?
            if ($llenar_grilla["cedula"] != "") {
                echo $llenar_grilla["cedula"];
            } else {
                echo "<center><strong>Sin Nro. de Cedula</strong></center>";
            }
            ?></td>
                                <?
            echo "<td align='left' class='Browse'>" . $llenar_grilla["apellidos"] . "</td>";
            echo "<td align='left' class='Browse'>" . $llenar_grilla["nombres"] . "</td>";
            $c = $llenar_grilla["idcarga_familiar"];
            $t = $llenar_grilla["idtrabajador"];
            if (in_array(168, $privilegios) == true) {
                ?>
                                    <td align='center' class='Browse' width='7%'>
                                    <img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar' onclick="seleccionarCargaFamiliar('<?=$llenar_grilla["idcarga_familiar"]?>', '<?=$llenar_grilla["apellidos"]?>', '<?=$llenar_grilla["nombres"]?>', '<?=$llenar_grilla["idnacionalidad"]?>', '<?=$llenar_grilla["cedula"]?>', '<?=$llenar_grilla["fecha_nacimiento"]?>', '<?=$llenar_grilla["sexo"]?>', '<?=$llenar_grilla["idparentezco"]?>', '<?=$llenar_grilla["flag_constancia"]?>', '<?=$llenar_grilla["direccion"]?>', '<?=$llenar_grilla["telefono"]?>', '<?=$llenar_grilla["ocupacion"]?>')" style="cursor:pointer">                                    </td>
                                    <?
            }
            if (in_array(169, $privilegios) == true) {
                ?>
                                    <td align='center' class='Browse' width='7%'>
                                            <img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar' onclick="eliminarCargaFamiliar('<?=$llenar_grilla["idcarga_familiar"]?>')" style="cursor:pointer">
                                    </td>
                                    <?
            }
            echo "</tr>";
        }
    }
    ?>
                        </table>
                        </form>
                    </td>
                </tr>
            </table>

<?
}

if ($ejecutar == "cargarFotoCargaFamiliar") {

    $tipo = substr($_FILES['foto_cargaFamiliar']['type'], 0, 5);
    $dir  = '../imagenes/carga_familiar/';
    if ($tipo == 'image') {
        $nombre_imagen = $_FILES['foto_cargaFamiliar']['name'];
        while (file_exists($dir . $nombre_imagen)) {
            $partes_img    = explode(".", $nombre_imagen);
            $nombre_imagen = $partes_img[0] . rand(0, 1000000) . "." . $partes_img[1];
        }
        if (!copy($_FILES['foto_cargaFamiliar']['tmp_name'], $dir . $nombre_imagen)) {
            ?>
                <script>
                parent.mostrarMensajes("error", "Disculpe la imagen no se pudo ingresar");
                parent.document.getElementById('foto_cargaFamiliar').value = '';
                </script>
                <?
        } else {
            $ruta = 'modulos/rrhh/imagenes/carga_familiar/' . $nombre_imagen;
        }

        ?>

            <script>
            parent.document.getElementById('nombre_foto_cargaFamiliar').value = '<?=$nombre_imagen?>';

            </script>
            <?

    } else {
        ?>
            <script>
            parent.mostrarMensajes("error", "Disculpe el archivo que intenta subir NO es una Imagen");
            parent.document.getElementById('foto_cargaFamiliar').value = '';
            </script>

            <?
    }

}

//******************************************************************************************************************************
//******************************************************************************************************************************
// *************************************************** INSTRUCCION  ACADEMICA **************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

if ($ejecutar == "ingresarInstruccionAcademica") {
    $sql_ingresar = mysql_query("insert into instruccion_academica (idtrabajador,
                                                                    idnivel_estudio,
                                                                    idprofesion,
                                                                    idmension,
                                                                    institucion,
                                                                    anio_egreso,
                                                                    observaciones,
                                                                    flag_constancia,
                                                                    flag_actual,
                                                                    usuario,
                                                                    status,
                                                                    fechayhora,
                                                                    foto)VALUES('" . $idtrabajador . "',
                                                                                '" . $idnivel_estudios . "',
                                                                                '" . $idprofesion . "',
                                                                                '" . $idmension . "',
                                                                                '" . $institucion . "',
                                                                                '" . $anio_egreso . "',
                                                                                '" . $observaciones . "',
                                                                                '" . $constancia . "',
                                                                                '" . $profesion_actual . "',
                                                                                '" . $login . "',
                                                                                'a',
                                                                                '" . $fh . "',
                                                                                '" . $nombre_foto . "')");
    if ($sql_ingresar) {
        echo "exito";
    } else {
        echo mysql_error();
    }
}

if ($ejecutar == "modificarInstruccionAcademica") {

    if ($nombre_foto == "") {

        $sql_modificar = mysql_query("update instruccion_academica set idnivel_estudio = '" . $idnivel_estudios . "',
                                                                    idprofesion = '" . $idprofesion . "',
                                                                    idmension = '" . $idmension . "',
                                                                    institucion = '" . $institucion . "',
                                                                    anio_egreso = '" . $anio_egreso . "',
                                                                    observaciones = '" . $observaciones . "',
                                                                    flag_constancia = '" . $constancia . "',
                                                                    flag_actual = '" . $profesion_actual . "'
                                                                        where
                                                                    idinstruccion_academica = '" . $idinstruccion_academica . "'");
    } else {

        $sql_consulta = mysql_query("select * from instruccion_academica where idinstruccion_academica = '" . $idinstruccion_academica . "'");
        $bus_consulta = mysql_fetch_array($sql_consulta);

        $sql_modificar = mysql_query("update instruccion_academica set idnivel_estudio = '" . $idnivel_estudios . "',
                                                                        idprofesion = '" . $idprofesion . "',
                                                                        idmension = '" . $idmension . "',
                                                                        institucion = '" . $institucion . "',
                                                                        anio_egreso = '" . $anio_egreso . "',
                                                                        observaciones = '" . $observaciones . "',
                                                                        flag_constancia = '" . $constancia . "',
                                                                        flag_actual = '" . $profesion_actual . "',
                                                                        foto =  '" . $nombre_foto . "'
                                                                            where
                                                                        idinstruccion_academica = '" . $idinstruccion_academica . "'");

    }

    if ($sql_modificar) {
        if ($bus_consulta["foto"] != "") {
            unlink("../imagenes/estudios/" . $bus_consulta["foto"]);
        }
        echo "exito";
    } else {
        echo "fallo";
    }
}

if ($ejecutar == "eliminarInstruccionAcademica") {
    $sql_consulta = mysql_query("select * from instruccion_academica where idinstruccion_academica = '" . $idinstruccion_academica . "'");
    $bus_consulta = mysql_fetch_array($sql_consulta);

    $sql_eliminar = mysql_query("delete from instruccion_academica where idinstruccion_academica = '" . $idinstruccion_academica . "'");

    if ($sql_eliminar) {
        if ($bus_consulta["foto"] != "") {
            unlink("../imagenes/estudios/" . $bus_consulta["foto"]);
        }
        echo "exito";
    } else {
        echo mysql_error();
    }
}

if ($ejecutar == "consultarInstruccionAcademica") {

    $registros_grilla_carga = mysql_query("select nivel_estudio.denominacion as denominacion_nivel,
                                            profesion.denominacion as denominacion_profesion,
                                            mension.denominacion as denominacion_mension,
                                            nivel_estudio.idnivel_estudio,
                                            profesion.idprofesion,
                                            mension.idmension,
                                            instruccion_academica.idtrabajador,
                                            instruccion_academica.institucion,
                                            instruccion_academica.anio_egreso,
                                            instruccion_academica.observaciones,
                                            instruccion_academica.flag_constancia,
                                            instruccion_academica.flag_actual,
                                            instruccion_academica.idinstruccion_academica,
                                            instruccion_academica.foto
                                        from nivel_estudio,
                                            profesion,
                                            mension,
                                            instruccion_academica
                                        where
                                            instruccion_academica.status='a'
                                            and nivel_estudio.idnivel_estudio = instruccion_academica.idnivel_estudio
                                            and profesion.idprofesion = instruccion_academica.idprofesion
                                            and mension.idmension = instruccion_academica.idmension
                                            and idtrabajador= " . $idtrabajador) or die("AQUI" . mysql_error());
    if (mysql_num_rows($registros_grilla_carga) > 0) {
        $existen_registros = 0;
    }

    ?>

            <table class="Main" cellpadding="0" cellspacing="0" width="80%">
                <tr>
                    <td>
                        <form name="grilla" action="instruccion_academica.php" method="POST">
                        <table class="Browse" cellpadding="0" cellspacing="0" width="80%" align=center>
                            <thead>
                                <tr>
                                    <!--<td class="Browse">&nbsp;</td>-->
                                    <td align="center" class="Browse">Imagen</td>
                                    <td align="center" class="Browse">Nivel</td>
                                    <td align="center" class="Browse">Profesi&oacute;n</td>
                                    <td align="center" class="Browse">Mensi&oacute;n</td>
                                    <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
                                </tr>
                            </thead>

                            <?php
    if ($existen_registros == 0) {
        while ($llenar_grilla = mysql_fetch_array($registros_grilla_carga)) {
            ?>
                                <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                <td align='center' class='Browse' width='20%'>
                                <a href="javascript:;" onClick="document.getElementById('imagen_<?=$llenar_grilla["idinstruccion_academica"]?>').style.display= 'block'">Ver Imagen</a>
                                <div style=" position:absolute; background-color:#CCCCCC; display:none; width:600px; height:400px; border:#666666 solid 1px;" id="imagen_<?=$llenar_grilla["idinstruccion_academica"]?>">
                                <div align="right" style="cursor:pointer"><strong onClick="document.getElementById('imagen_<?=$llenar_grilla["idinstruccion_academica"]?>').style.display= 'none'">Cerrar</strong></div>
                                <img src="modulos/rrhh/imagenes/estudios/<?=$llenar_grilla["foto"]?>">
                                </div>
                                </td>
                                <?php
    echo "<td align='left' class='Browse' width='20%'>" . $llenar_grilla["denominacion_nivel"] . "</td>";

            echo "<td align='left' class='Browse'>" . $llenar_grilla["denominacion_profesion"] . "</td>";
            echo "<td align='left' class='Browse'>" . $llenar_grilla["denominacion_mension"] . "</td>";
            $c = $llenar_grilla["idinstruccion_academica"];
            $t = $llenar_grilla["idtrabajador"];
            ?>
                                <td align='center' class='Browse' width='7%'>
                                    <img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar' onclick="seleccionar_instruccionAcademica('<?=$llenar_grilla["idnivel_estudio"]?>', '<?=$llenar_grilla["idprofesion"]?>', <?=$llenar_grilla["idmension"]?>, '<?=$llenar_grilla["institucion"]?>', '<?=$llenar_grilla["anio_egreso"]?>', '<?=$llenar_grilla["observaciones"]?>', '<?=$llenar_grilla["flag_constancia"]?>', '<?=$llenar_grilla["flag_actual"]?>', '<?=$llenar_grilla["idinstruccion_academica"]?>')" style="cursor:pointer">
                                </td>
                                <td align='center' class='Browse' width='7%'>
                                    <img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar' style="cursor:pointer" onclick="eliminarInstruccionAcademica('<?=$llenar_grilla["idinstruccion_academica"]?>')">
                                </td>
                                <?
            echo "</tr>";
        }
    }
    ?>
                        </table>
                        </form>
                    </td>
                </tr>
            </table>
 <?
}

if ($ejecutar == "cargarFotoInstruccionAcademica") {

    $tipo = substr($_FILES['foto_instruccionAcademica']['type'], 0, 5);
    $dir  = '../imagenes/estudios/';
    if ($tipo == 'image') {
        $nombre_imagen = $_FILES['foto_instruccionAcademica']['name'];
        while (file_exists($dir . $nombre_imagen)) {
            $partes_img    = explode(".", $nombre_imagen);
            $nombre_imagen = $partes_img[0] . rand(0, 1000000) . "." . $partes_img[1];
        }
        if (!copy($_FILES['foto_instruccionAcademica']['tmp_name'], $dir . $nombre_imagen)) {
            ?>
                <script>
                parent.mostrarMensajes("error", "Disculpe la imagen no se pudo ingresar");
                parent.document.getElementById('foto_instruccionAcademica').value = '';
                </script>
                <?
        } else {
            $ruta = 'modulos/rrhh/imagenes/estudios/' . $nombre_imagen;
        }

        ?>

            <script>
            parent.document.getElementById('nombre_foto_instruccionAcademica').value = '<?=$nombre_imagen?>';

            </script>
            <?

    } else {
        ?>
            <script>
            parent.mostrarMensajes("error", "Disculpe el archivo que intenta subir NO es una Imagen");
            parent.document.getElementById('foto_instruccionAcademica').value = '';
            </script>

            <?
    }
}

// *************************************************************************************************************************
// *************************************************************************************************************************
// ********************************************** EXPERIENCIA LABORAL *********************************************
// *************************************************************************************************************************
// *************************************************************************************************************************

if ($ejecutar == "cargarFotoExperienciaLaboral") {
    $tipo = substr($_FILES['foto_experienciaLaboral']['type'], 0, 5);
    $dir  = '../imagenes/experiencia_laboral/';
    if ($tipo == 'image') {
        $nombre_imagen = $_FILES['foto_experienciaLaboral']['name'];
        while (file_exists($dir . $nombre_imagen)) {
            $partes_img    = explode(".", $nombre_imagen);
            $nombre_imagen = $partes_img[0] . rand(0, 1000000) . "." . $partes_img[1];
        }
        if (!copy($_FILES['foto_experienciaLaboral']['tmp_name'], $dir . $nombre_imagen)) {
            ?>
                <script>
                parent.mostrarMensajes("error", "Disculpe la imagen no se pudo ingresar");
                parent.document.getElementById('foto_experienciaLaboral').value = '';
                </script>
                <?
        } else {
            $ruta = 'modulos/rrhh/imagenes/experiencia_laboral/' . $nombre_imagen;
        }

        ?>

            <script>
            parent.document.getElementById('nombre_foto_experienciaLaboral').value = '<?=$nombre_imagen?>';

            </script>
            <?

    } else {
        ?>
            <script>
            parent.mostrarMensajes("error", "Disculpe el archivo que intenta subir NO es una Imagen");
            parent.document.getElementById('foto_experienciaLaboral').value = '';
            </script>

            <?
    }
}

if ($ejecutar == "ingresarExperienciaLaboral") {
    $sql_ingresar = mysql_query("insert into experiencia_laboral(idtrabajador,
                                                                empresa,
                                                                desde,
                                                                hasta,
                                                                tiempo_servicio,
                                                                motivo_salida,
                                                                ultimo_cargo,
                                                                direccion_empresa,
                                                                telefono_empresa,
                                                                observaciones,
                                                                usuario,
                                                                status,
                                                                fechayhora,
                                                                foto)
                                                                    VALUES(
                                                                '" . $idtrabajador . "',
                                                                '" . $empresa . "',
                                                                '" . $desde_exp . "',
                                                                '" . $hasta_exp . "',
                                                                '" . $tiempo_srv . "',
                                                                '" . $motivo . "',
                                                                '" . $ultimo_cargo . "',
                                                                '" . $direccion_empresa . "',
                                                                '" . $telefono . "',
                                                                '" . $observaciones . "',
                                                                '" . $login . "',
                                                                'a',
                                                                '" . $fh . "',
                                                                '" . $nombre_foto . "')") or die(mysql_error());

    if ($sql_ingresar) {
        echo "exito";
    } else {
        echo mysql_error();
    }

}

if ($ejecutar == "modificarExperienciaLaboral") {

    if ($nombre_foto != '') {
        $str_modificar = mysql_query("  UPDATE experiencia_laboral set
                                        empresa='" . $empresa . "',
                                        desde='" . $desde_exp . "',
                                        hasta='" . $hasta_exp . "',
                                        tiempo_servicio='" . $tiempo_srv . "',
                                        motivo_salida='" . $motivo . "',
                                        ultimo_cargo='" . $ultimo_cargo . "',
                                        direccion_empresa='" . $direccion_empresa . "',
                                        telefono_empresa='" . $telefono . "',
                                        observaciones='" . $observaciones . "',
                                        foto = '" . $nombre_foto . "'
                                            where
                                        secuencia='" . $idexperiencia_laboral . "'; ") or die(mysql_error());
    } else {

        $str_modificar = mysql_query("  UPDATE experiencia_laboral set
                                        empresa='" . $empresa . "',
                                        desde='" . $desde_exp . "',
                                        hasta='" . $hasta_exp . "',
                                        tiempo_servicio='" . $tiempo_srv . "',
                                        motivo_salida='" . $motivo . "',
                                        ultimo_cargo='" . $ultimo_cargo . "',
                                        direccion_empresa='" . $direccion_empresa . "',
                                        telefono_empresa='" . $telefono . "',
                                        observaciones='" . $observaciones . "'
                                            where
                                        secuencia='" . $idexperiencia_laboral . "'; ") or die(mysql_error());

    }

    if ($str_modificar) {
        echo "exito";
    } else {
        echo "fallo";
    }
}

if ($ejecutar == "eliminarExperienciaLaboral") {
    $sql_consulta = mysql_query("select * from experiencia_laboral where idexperiencia_laboral = '" . $idexperiencia_laboral . "'");
    $bus_consulta = mysql_fetch_array($sql_consulta);

    $sql_eliminar = mysql_query("delete from experiencia_laboral where idexperiencia_laboral = '" . $idexperiencia_laboral . "'");

    if ($sql_eliminar) {
        if ($bus_consulta["foto"] == "") {
            unlink("../imagenes/experiencia_laboral/" . $bus_consulta["foto"]);
        }
    } else {
        echo mysql_error();
    }
}

if ($ejecutar == "consultarExperienciaLaboral") {

    $sql_consulta = mysql_query("select * from experiencia_laboral where idtrabajador = '" . $idtrabajador . "'");
    ?>
    <br>
    <h2 class="sqlmVersion"></h2>
    <br>
    <div align="center">

    <table class="Browse" cellpadding="0" cellspacing="0" width="80%" align=center>
        <thead>
            <tr>
                <td align="center" class="Browse">Imagen</td>
                <td align="center" class="Browse">Nombre de la Empresa</td>
                <td align="center" class="Browse">&Uacute;ltimo Cargo</td>
                <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
            </tr>
        </thead>
            <?

    while ($bus_consulta = mysql_fetch_array($sql_consulta)) {
        ?>
            <tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td align='center' class='Browse' width='20%'>
            <a href="javascript:;" onClick="document.getElementById('imagen_<?=$bus_consulta["secuencia"]?>').style.display= 'block'">Ver Imagen</a>
            <div style=" position:absolute; background-color:#CCCCCC; display:none; width:600px; height:400px; border:#666666 solid 1px;" id="imagen_<?=$bus_consulta["secuencia"]?>">
            <div align="right" style="cursor:pointer"><strong onClick="document.getElementById('imagen_<?=$bus_consulta["secuencia"]?>').style.display= 'none'">Cerrar</strong></div>
            <?
        if (file_exists("../imagenes/experiencia_laboral/" . $bus_consulta["foto"]) and $bus_consulta["foto"] != '') {
            ?>
                <img src="modulos/rrhh/imagenes/experiencia_laboral/<?=$bus_consulta["foto"]?>">
                <?
        } else {
            ?>
                <center><strong style="font-size:18px">SIN IMAGEN</strong></center>
                <?
        }
        ?>

            </div>
            </td>
                <td align="left" class="Browse"><?=$bus_consulta["empresa"]?></td>
                <td align="left" class="Browse"><?=$bus_consulta["ultimo_cargo"]?></td>
                <td align="center" class="Browse"><img src="imagenes/modificar.png" style="cursor:pointer" alt='Modificar' title='Modificar' onclick="seleccionar_experienciaLaboral('<?=$bus_consulta["secuencia"]?>','<?=$bus_consulta["empresa"]?>', '<?=$bus_consulta["desde"]?>', '<?=$bus_consulta["hasta"]?>', '<?=$bus_consulta["tiempo_servicio"]?>', '<?=$bus_consulta["motivo_salida"]?>', '<?=$bus_consulta["ultimo_cargo"]?>', '<?=$bus_consulta["direccion_empresa"]?>', '<?=$bus_consulta["telefono_empresa"]?>', '<?=$bus_consulta["observaciones"]?>')"></td>
                <td align="center" class="Browse"><img src="imagenes/delete.png" style="cursor:pointer" alt='Eliminar' title='Eliminar' onclick="eliminarExperienciaLaboral('<?=$bus_consulta["secuencia"]?>')"></td>
      </tr>
            <?
    }
    ?>
    </table>
    <?
}

//***************************************************************************************************************************
//***************************************************************************************************************************
//***************************************************** MOVIMIENTOS ********************************************************
//***************************************************************************************************************************
//***************************************************************************************************************************

if ($ejecutar == "consultarFichaMovimientos") {

    $sql_nomenclatura = mysql_query("select * from nomenclatura_fichas where descripcion ='" . $nomenclatura_ficha . "'") or die(mysql_error());
    $bus_nomenclatura = mysql_fetch_array($sql_nomenclatura);

    $numero_con_ceros = str_pad($bus_nomenclatura["numero"], 4, "0", STR_PAD_LEFT);
    echo $numero_con_ceros;
}

if ($ejecutar == "buscarMovimientosTrabajador") {
    $sql_consulta = mysql_query("select * FROM
                                        movimientos_personal mp
                                            WHERE
                                        mp.idtrabajador = '" . $idtrabajador . "'
                                        order by fecha_movimiento");
    ?>
    <div align="center" style="font-weight:bold; font-size:12px">LISTA DE MOVIMIENTOS REALIZADOS PARA EL TRABAJADOR</div>
    <br>
    <br>

    <table align="center" class="Main" cellpadding="0" cellspacing="0" width="90%">
        <thead>
            <tr>
                <td class="Browse" align="center">Tipo Movimiento</td>
                <td class="Browse" align="center">Fecha</td>
                <td class="Browse" align="center">Justificacion</td>

                <td class="Browse" align="center" colspan="2">Acciones</td>
            </tr>
        </thead>
     <?
    while ($bus_consulta = mysql_fetch_array($sql_consulta)) {
        ?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class='Browse'>
            <?
        $sql_tipo_movimiento = mysql_query("select * from tipo_movimiento_personal where idtipo_movimiento = '" . $bus_consulta["idtipo_movimiento"] . "'");
        $bus_tipo_movimiento = mysql_fetch_array($sql_tipo_movimiento);
        if ($bus_consulta["idtipo_movimiento"] == 0) {
            echo "INGRESO";
        } else if ($bus_consulta["idtipo_movimiento"] == 1000000) {
            echo "OTROS MOVIMIENTOS";
        } else {
            echo $bus_tipo_movimiento["denominacion"];
        }
        ?></td>
            <td class='Browse'><?=$bus_consulta["fecha_movimiento"]?></td>
            <td class='Browse'><?=$bus_consulta["justificacion"]?></td>


            <td class='Browse' align="center">
            &nbsp;
            <?
        if ($bus_consulta["idtipo_movimiento"] != 0 and $bus_consulta["idtipo_movimiento"] != 1000000) {
            ?>
            <img src="imagenes/modificar.png" style="cursor:pointer" onclick="seleccionarModificar('<?=$bus_consulta["idmovimientos_personal"]?>','<?=$bus_consulta["fecha_movimiento"]?>', '<?=$bus_consulta["idtipo_movimiento"]?>', '<?=$bus_consulta["justificacion"]?>', '<?=$bus_consulta["fecha_egreso"]?>', '<?=$bus_consulta["causal"]?>', '<?=$bus_consulta["idubicacion_nueva"]?>', '<?=$bus_consulta["fecha_reingreso"]?>', '<?=$bus_consulta["desde"]?>', '<?=$bus_consulta["hasta"]?>', '<?=$bus_consulta["idnuevo_cargo"]?>', '<?=$bus_tipo_movimiento["relacion_laboral"]?>', '<?=$bus_tipo_movimiento["goce_sueldo"]?>', '<?=$bus_tipo_movimiento["afecta_cargo"]?>', '<?=$bus_tipo_movimiento["afecta_ubicacion"]?>', '<?=$bus_tipo_movimiento["afecta_tiempo"]?>', '<?=$bus_consulta["centro_costo"]?>', '<?=$bus_tipo_movimiento["afecta_centro_costo"]?>', '<?=$bus_tipo_movimiento["afecta_ficha"]?>')">
            <?
        }
        ?>
            </td>
            <td class='Browse' align="center" style="height:15px;">
            &nbsp;
            <?
        if ($bus_consulta["idtipo_movimiento"] != 0 and $bus_consulta["idtipo_movimiento"] != 1000000) {
            ?>
                <img src="imagenes/delete.png" style="cursor:pointer" onclick="eliminarMovimiento('<?=$bus_consulta["idmovimientos_personal"]?>')">
                <?
        }
        ?>
            </td>
        </tr>
        <?
    }
    ?>
    </table>
    <?
}

if ($ejecutar == "ingresarMovimiento") {

    if (strlen($ficha) < 6) {
        //echo "aqui";
        $sql_consulta_ficha = mysql_query("select * from trabajador where idtrabajador = '" . $idtrabajador . "'") or die(mysql_error());
        $bus_consulta_ficha = mysql_fetch_array($sql_consulta_ficha);
        $ficha              = $bus_consulta_ficha["nro_ficha"];
        $entro              = "si";
    }
    $sql_consulta_trabajador = mysql_query("select * from trabajador where idtrabajador = '" . $idtrabajador . "'") or die(mysql_error());
    $bus_consulta_trabajador = mysql_fetch_array($sql_consulta_trabajador);

    $sql_ingreso = "insert into movimientos_personal(           idtrabajador,
                                                                      fecha_movimiento,
                                                                      idtipo_movimiento,
                                                                      justificacion,
                                                                      causal,
                                                                      usuario,
                                                                      status,
                                                                      fechayhora,
                                                                      fecha_ingreso,
                                                                      fecha_reingreso,
                                                                      desde,
                                                                      hasta,
                                                                      idtipo_nomina,
                                                                      nro_ficha,
                                                                      fecha_egreso,
                                                                      idcargo,
                                                                      idubicacion_funcional,
                                                                      centro_costo
                                                                      )VALUES(
                                                                        '" . $idtrabajador . "',
                                                                        '" . $fecha_movimiento . "',
                                                                        '" . $tipo_movimiento . "',
                                                                        '" . $justificacion . "',
                                                                        '" . $causal . "',
                                                                        '" . $login . "',
                                                                        'a',
                                                                        '" . $fh . "',
                                                                        '" . $fecha_ingreso . "',
                                                                        '" . $fecha_reingreso . "',
                                                                        '" . $desde . "',
                                                                        '" . $hasta . "',
                                                                        '" . $idtipo_nomina . "',
                                                                        '" . $ficha . "',
                                                                        '" . $fecha_egreso . "'";

    if ($idnuevo_cargo != '0') {
        $sql_ingreso = $sql_ingreso . ",'" . $idnuevo_cargo . "'";
    } else {
        $sql_ingreso = $sql_ingreso . ",'" . $bus_consulta_trabajador["idcargo"] . "'";
    }

    if ($idubicacion_nueva != '0') {
        $sql_ingreso = $sql_ingreso . ",'" . $idubicacion_nueva . "'";
    } else {
        $sql_ingreso = $sql_ingreso . ",'" . $bus_consulta_trabajador["idunidad_funcional"] . "'";
    }

    if ($centro_costo != '0') {
        $sql_ingreso = $sql_ingreso . ",'" . $centro_costo . "'";
    } else {
        $sql_ingreso = $sql_ingreso . ",'" . $bus_consulta_trabajador["centro_costo"] . "'";
    }

    //echo $sql_ingreso;
    $sql_ingreso  = $sql_ingreso . ")";
    $sql_ingresar = mysql_query($sql_ingreso) or die(mysql_error());

    $idmovimiento_ingresado = mysql_insert_id();

    $sql_actualizar_trabajador = "update trabajador set ";

    if ($idnuevo_cargo != '0') {
        $sql_actualizar_trabajador = $sql_actualizar_trabajador . "idcargo = '" . $idnuevo_cargo . "', ";
    }

    if ($idubicacion_nueva != '0') {
        $sql_actualizar_trabajador = $sql_actualizar_trabajador . "idunidad_funcional = '" . $idubicacion_nueva . "', ";
    }

    if ($centro_costo != '0') {
        $sql_actualizar_trabajador = $sql_actualizar_trabajador . "centro_costo = '" . $centro_costo . "', ";
    }

    $sql_actualizar_trabajador = $sql_actualizar_trabajador . "nro_ficha = '" . $ficha . "'
                                                            where idtrabajador = '" . $idtrabajador . "'";

    $sql_actualizar = mysql_query($sql_actualizar_trabajador) or die("ACTU TRAB" . mysql_error());

    if ($entro != 'si') {
        $sql_nomenclatura = mysql_query("update nomenclatura_fichas set numero=numero+1 where descripcion = '" . substr($ficha, 0, 2) . "'");
    }

    $sql_consulta_tipo_movimiento = mysql_query("select * from tipo_movimiento_personal where idtipo_movimiento = '" . $tipo_movimiento . "'");
    $bus_consulta_tipo_movimiento = mysql_fetch_array($sql_consulta_tipo_movimiento);

    if ($bus_consulta_tipo_movimiento["relacion_laboral"] == 'egreso') {

        $sql_consulta          = mysql_query("delete from relacion_tipo_nomina_trabajador where idtrabajador = '" . $idtrabajador . "'");
        $sql_eliminar_concepto = mysql_query("delete from relacion_concepto_trabajador where idtrabajador = '" . $idtrabajador . "'");
        $sql_update            = mysql_query("update trabajador set status = 'e' where idtrabajador = '" . $idtrabajador . "'");
    }

    if ($bus_consulta_tipo_movimiento["relacion_laboral"] == 'reingreso') {

        $sql_update = mysql_query("update trabajador set status = 'a' where idtrabajador = '" . $idtrabajador . "'");
    }

    registra_transaccion("Se ingreso el movimiento de personal con el ID (" . $idmovimiento_ingresado . ", " . $justificacion . ") al trabajador : " . $idtrabajador . ")
                            ", $login, $fh, $pc, 'movimientos_personal');
}

if ($ejecutar == "modificarMovimiento") {

    if ($ficha == "") {
        $sql_consulta_ficha = mysql_query("select * from movimientos_personal where idtrabajador = '" . $idtrabajador . "' limit 0,1 order by desc");
        $bus_consulta_ficha = mysql_fetch_array($sql_consulta_ficha);
        $ficha              = $bus_consulta_ficha["ficha"];

    }

    $sql_ingresar = mysql_query("update movimientos_personal set idtrabajador = '" . $idtrabajador . "',
                                                                      fecha_movimiento='" . $fecha_movimiento . "',
                                                                      idtipo_movimiento='" . $tipo_movimiento . "',
                                                                      justificacion='" . $justificacion . "',
                                                                      fecha_ingreso='" . $fecha_ingreso . "',
                                                                      idcargo='" . $idnuevo_cargo . "',
                                                                      idubicacion_funcional='" . $idubicacion_nueva . "',
                                                                      fecha_egreso='" . $fecha_egreso . "',
                                                                      causal='" . $causal . "',
                                                                      centro_costo='" . $centro_costo . "',
                                                                      fecha_reingreso='" . $fecha_reingreso . "',
                                                                      desde='" . $desde . "',
                                                                      hasta='" . $hasta . "',
                                                                      idtipo_nomina='" . $idtipo_nomina . "',
                                                                      nro_ficha='" . $ficha . "'
                                                                        WHERE idmovimientos_personal = '" . $idmovimiento . "'") or die(mysql_error());

    $sql_consulta = mysql_query("select * from movimientos_personal where idmovimientos_personal = '" . $idmovimiento . "'");
    $bus_consulta = mysql_fetch_array($sql_consulta);

    $sql_actualizar = mysql_query("update trabajador set idcargo = '" . $idnuevo_cargo . "',
                                                            idunidad_funcional = '" . $idubicacion_nueva . "',
                                                            idcargo = '" . $idnuevo_cargo . "',
                                                            centro_costo = '" . $centro_costo . "',
                                                            nro_ficha = '" . $ficha . "'
                                                            where idtrabajador = '" . $idtrabajador . "'") or die(mysql_error());

    registra_transaccion("Se Modifico el movimiento de personal con el ID (" . $idmovimiento . ")", $login, $fh, $pc, 'movimientos_personal');
}

if ($ejecutar == "eliminarMovimiento") {
    $sql_consulta = mysql_query("select * from movimientos_personal where idmovimientos_personal = '" . $idmovimiento . "'") or die("error en la consulta " . mysql_error());
    $bus_consulta = mysql_fetch_array($sql_consulta);

    $sql_eliminar = mysql_query("delete from movimientos_personal where idmovimientos_personal = '" . $idmovimiento . "'");

    $sql_consultar_movimiento = mysql_query("select * from movimientos_personal where idtrabajador= '" . $bus_consulta["idtrabajador"] . "'
                                                                                and idtipo_nomina != '0' order by idmovimientos_personal desc limit 0,1") or die("error al consultar el ultimo " . mysql_error());
    $bus_consultar_movimiento = mysql_fetch_array($sql_consultar_movimiento);
    $sql_actualizar           = mysql_query("update trabajador set idtipo_nomina = '" . $bus_consultar_movimiento["idtipo_nomina"] . "'
                                                                where idtrabajador = '" . $bus_consultar_movimiento["idtrabajador"] . "'") or die("error al actualizar: " . mysql_error());
    registra_transaccion("Se Elimino el movimiento de personal con el ID (" . $idmovimiento . ")", $login, $fh, $pc, 'movimientos_personal');

}

// ************************************************************************************************************************
// ************************************************************************************************************************
// ********************************************** HISTORICO DE PERMISOS ***************************************************
// ************************************************************************************************************************
// ************************************************************************************************************************

if ($ejecutar == "validarFechas") {
    //echo "entro en total dias";
    $fechaInicio = $desde;
    $fechaActual = $hasta;

    $diaActual  = substr($fechaActual, 8, 10);
    $mesActual  = substr($fechaActual, 5, 7);
    $anioActual = substr($fechaActual, 0, 4);

    $diaInicio  = substr($fechaInicio, 8, 10);
    $mesInicio  = substr($fechaInicio, 5, 7);
    $anioInicio = substr($fechaInicio, 0, 4);

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
        $x = "La fecha de inicio ha de ser anterior a la fecha Actual";
    }
    //---------------------------------------------
    if ($x == "La fecha de inicio ha de ser anterior a la fecha Actual") {
        echo $x;
    } else {

        list($ad, $md, $dd) = SPLIT('[/.-]', $desde);
        list($ah, $mh, $dh) = SPLIT('[/.-]', $hasta);

        //    Calculo timestam de las dos fechas
        $timestampd = mktime(0, 0, 0, $md, $dd, $ad);
        $timestamph = mktime(0, 0, 0, $mh, $dh, $ah);

        //    Resto a una fecha la otra
        $segundos_diferencia = $timestampd - $timestamph;

        //convierto segundos en d�as
        $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);

        //obtengo el valor absoulto de los d�as (quito el posible signo negativo)
        $dias_diferencia = abs($dias_diferencia);

        //quito los decimales a los d�as de diferencia
        $dias_diferencia = floor($dias_diferencia);

        echo $dias_diferencia;
    }
}

if ($ejecutar == "validarHoras") {
    if ($hr_inicio != "") {
        //echo "Entro en hr_inicio";
        $hora = substr($hr_inicio, 0, 2);

        if ($hora > 12) {
            $hr_flag = 0;
        } else {
            $hr_flag = 1;
        }

        $dos_puntos = substr($hr_inicio, 2, 1);

        if ($dos_puntos == ":") {
            $dos_flag = 1;
        } else {
            $dos_flag = 0;
        }

        $minutos = substr($hr_inicio, 3, 2);

        if ($minutos > 59) {
            $min_flag = 0;
        } else {
            $min_flag = 1;
        }

        $am_pm = substr($hr_inicio, 6, 2);

        if ($am_pm == "pm") {
            $am_pm_flag = 1;
        } else {
            if ($am_pm == "am") {
                $am_pm_flag = 1;
            } else {
                $am_pm_flag = 0;
            }
        }

        if ($hr_flag == 1 && $dos_flag == 1 && $min_flag == 1 && $am_pm_flag == 1) {
            $valida_all = 1;
            echo $valida_all;
        } else {
            $valida_all = 0;
            echo $valida_all;
        }
    }
}
if ($ejecutar == "total_Horas") {

    //desfragmentamos la hora de incio en varios objetos
    $hora_inicio       = substr($hr_inicio, 0, 2);
    $dos_puntos_inicio = substr($hr_inicio, 2, 1);
    $minutos_inicio    = substr($hr_inicio, 3, 2);
    $am_pm_inicio      = substr($hr_inicio, 6, 2);

    //desfragmentamos la hora de culminacion en varios objetos
    $hora_culminacion       = substr($hr_culminacion, 0, 2);
    $dos_puntos_culminacion = substr($hr_culminacion, 2, 1);
    $minutos_culminacion    = substr($hr_culminacion, 3, 2);
    $am_pm_culminacion      = substr($hr_culminacion, 6, 2);

    //calculamos el tiempo restante del dia de inicio

    if ($am_pm_inicio == "am") {
        $residuo_horas_inicio = 24 - $hora_inicio;

        if ($minutos_inicio > 0) {
            $residuo_minutos_inicio = 60 - $minutos_inicio;
            $residuo_horas_inicio   = $residuo_horas_inicio - 1;
        }
    }
    if ($am_pm_inicio == "pm") {

        $residuo_horas_inicio = 12 - $hora_inicio;
        if ($minutos_inicio > 0) {
            $residuo_minutos_inicio = 60 - $minutos_inicio;
            $residuo_horas_inicio   = $residuo_horas_inicio - 1;
        }
    }
    //echo $residuo_horas_inicio;

    //calculamos el tiempo restante del ultimo dia

    if ($am_pm_culminacion == "am") {
        //echo "am";
        $residuo_horas_culminacion   = $hora_culminacion;
        $residuo_minutos_culminacion = $minutos_culminacion;
    }
    if ($am_pm_culminacion == "pm") {
        //echo "pm";
        $residuo_horas_culminacion   = 12 + $hora_culminacion;
        $residuo_minutos_culminacion = $minutos_culminacion;
    }

    //echo $hora_culminacion;
    //echo $residuo_horas_culminacion;
    //-----------------------------------------
    $total_horas   = $residuo_horas_inicio + $residuo_horas_culminacion;
    $total_minutos = $residuo_minutos_inicio + $residuo_minutos_culminacion;
    $total_dias    = $total_dias - 1;
    $horas_por_dia = $total_dias * 24;
    $total_horas   = $total_horas + $horas_por_dia;

    if ($total_minutos > 59) {
        $total_horas   = $total_horas + 1;
        $total_minutos = $total_minutos - 60;

        if ($total_minutos > 59) {
            $total_horas   = $total_horas + 1;
            $total_minutos = $total_minutos - 60;
        }
    }

    if ($total_minutos > 0) {
        if ($total_horas <= 0) {
            echo "Selecione horas validas";
        } else {
            echo "Horas de permiso: " . $total_horas . " - Minutos de permiso: " . $total_minutos;
        }
    } else {
        if ($total_horas <= 0) {
            echo "Selecione horas validas";
        } else {
            echo "Horas de permiso: " . $total_horas;
        }
    }
    //echo "Horas restantes: ".$total_horas." - minutos restantes: ".$total_minutos;
    //echo "Restan del primer dia: ".$residuo_horas_inicio." horas con: ".$residuo_minutos_inicio." minutos ";
    //echo "Restan del ultimo dia: ".$residuo_horas_culminacion." horas con: ".$residuo_minutos_culminacion." minutos";
}

if ($ejecutar == "checkbox") {
    echo $valor;
}

if ($ejecutar == "consultarHistoricoPermisos") {
    $str_select_permisos      = "select * from historico_permisos where idtrabajador = '" . $idtrabajador . "'";
    $result_select_trabajador = mysql_query($str_select_permisos) or die(mysql_error());

    ?>
        <table border="0" class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
            <thead>
            <tr>
                <td width="26%" class="Browse" align="center">Fecha de Inicio</td>
                <td width="29%" class="Browse" align="center">Fecha de Culminaci&oacute;n</td>
                <td width="15%" class="Browse" align="center">Justificado</td>
                <td width="19%" class="Browse" align="center">Remunerado</td>
                <td width="11%" class="Browse" align="center">Acci&oacute;n</td>
            </tr>
            </thead>
            <?while ($array_select = mysql_fetch_array($result_select_trabajador)) {?>
                <tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="left" class="Browse"><div align="center">
                  <?list($fecha_inicio, $hora) = split('[ ]', $array_select["fecha_inicio"]);?>
                  <?=$fecha_inicio?>
                </div></td>
                <td align="left" class="Browse"><div align="center">
                  <?list($fecha_culminacion, $hora) = split('[ ]', $array_select["fecha_culminacion"]);?>
                  <?=$fecha_culminacion?>
                </div></td>
                <?if ($array_select["justificado"] == 1) {$justificado = "Si";} else { $justificado = "No";}?>
                <td align="left" class="Browse"><div align="center">
                  <?=$justificado?>
                </div></td>
                <?if ($array_select["remunerado"] == 1) {$remunerado = "Si";} else { $remunerado = "No";}?>
                <td align="left" class="Browse"><div align="center">
                  <?=$remunerado?>
                </div></td>
                <td class="Browse" align="center">

                  <div align="center"><a href="javascript:;" onclick="seleccionarModificar('<?=$array_select["fecha_inicio"]?>', '<?=$array_select["hora_inicio"]?>', '<?=$array_select["fecha_culminacion"]?>', '<?=$array_select["hora_culminacion"]?>', '<?=$array_select["fecha_solicitud"]?>', '<?=$array_select["tiempo_total"]?>', '<?=$array_select["descuenta_bono_alimentacion"]?>', '<?=$array_select["motivo"]?>', '<?=$array_select["justificado"]?>', '<?=$array_select["remunerado"]?>', '<?=$array_select["aprobado_por"]?>', '<?=$array_select["ci_aprobado"]?>')"><img
                 src="imagenes/modificar.png" style="cursor:pointer" alt='Modificar' title='Modificar'></a>                    </div></td>
          </tr>
            <?}?>
        </table>
<?
}

if ($ejecutar == "modificarHistorico") {

    $str_actualizar = "update historico_permisos set
                        fecha_solicitud='" . $fecha_solicitud . "',
                        fecha_inicio ='" . $fecha_inicio . "',
                        hora_inicio = '" . $hr_inicio . "',
                        fecha_culminacion = '" . $fecha_culminacion . "',
                        hora_culminacion = '" . $hr_culminacion . "',
                        tiempo_total = '" . $tiempo_horas . "',
                        descuenta_bono_alimentacion = '" . $desc_bono . "',
                        motivo = '" . $motivo . "',
                        justificado = '" . $justificado1 . "',
                        remunerado = '" . $remunerado1 . "',
                        aprobado_por = '" . $aprobado_por . "',
                        ci_aprobado = '" . $ci_aprobado . "',
                        usuario = '" . $login . "',
                        fechayhora = '" . $fh . "'
                        where idhistorico_permisos = '" . $idhistorico . "'";
    $result_actualizar = mysql_query($str_actualizar);
}

if ($ejecutar == "ingresarHistorico") {

    $sql_ingresar = "insert into historico_permisos(
                        idtrabajador,
                        fecha_inicio,
                        hora_inicio,
                        fecha_culminacion,
                        hora_culminacion,
                        fecha_solicitud,
                        tiempo_total,
                        descuenta_bono_alimentacion,
                        motivo,
                        justificado,
                        remunerado,
                        aprobado_por,
                        ci_aprobado,
                        usuario,
                        fechayhora) values (
                        '" . $idtrabajador . "',
                        '" . $fecha_inicio . "',
                        '" . $hr_inicio . "',
                        '" . $fecha_culminacion . "',
                        '" . $hr_culminacion . "',
                        '" . $fecha_solicitud . "',
                        '" . $tiempo_horas . "',
                        '" . $desc_bono . "',
                        '" . $motivo . "',
                        '" . $justificado1 . "',
                        '" . $remunerado1 . "',
                        '" . $aprobado_por . "',
                        '" . $ci_aprobado . "',
                        '" . $login . "',
                        '" . $fh . "');";

    $result_ingresar = mysql_query($sql_ingresar);

    if ($result_ingresar) {
        echo "exito";
    } else {
        echo mysql_error();
    }
} //    fin de ingresar

// **************************************************************************************************************************
// **************************************************************************************************************************
// ****************************************************** RECONOCIMIENTOS ***************************************************
// **************************************************************************************************************************
// **************************************************************************************************************************

if ($ejecutar == "ingresarReconocimientos") {
    $sql_ingresar = mysql_query("insert into reconocimientos (idtrabajador,
                                                            idtipo_reconocimientos,
                                                            motivo,
                                                            fecha,
                                                            numero_documento,
                                                            fecha_entrega,
                                                            constancia_anexa,
                                                            nombre_imagen,
                                                            observaciones,
                                                            usuario,
                                                            fechayhora)VALUES('" . $idtrabajador . "',
                                                                                '" . $tipo . "',
                                                                                '" . $motivo . "',
                                                                                '" . $fecha . "',
                                                                                '" . $numero_documentos . "',
                                                                                '" . $fecha_entrega . "',
                                                                                '" . $constancia_anexa . "',
                                                                                '" . $nombre_imagen . "',
                                                                                '" . $observaciones . "',
                                                                                '" . $login . "',
                                                                                '" . $fh . "')");
    if ($sql_ingresar) {
        echo "exito";
    } else {
        echo mysql_error();
    }
}

if ($ejecutar == "modificarReconocimientos") {
    $sql_consulta = mysql_query("SELECT *
                                    FROM
                                reconocimientos
                                    WHERE
                                idreconocimientos = '" . $idreconocimientos . "'");
    $bus_consulta = mysql_fetch_array($sql_consulta);

    if ($bus_consulta["nombre_imagen"] != '') {
        unlink("../imagenes/reconocimientos/" . $bus_consulta["nombre_imagen"]);
    }

    $sql_modificar = mysql_query("UPDATE reconocimientos SET idtipo_reconocimientos = '" . $tipo . "',
                                                            motivo                  = '" . $motivo . "',
                                                            fecha                   = '" . $fecha . "',
                                                            numero_documento        = '" . $numero_documentos . "',
                                                            fecha_entrega           = '" . $fecha_entrega . "',
                                                            constancia_anexa        = '" . $constancia_anexa . "',
                                                            nombre_imagen           = '" . $nombre_imagen . "',
                                                            observaciones           = '" . $observaciones . "'
                                                                WHERE
                                                            idreconocimientos       = '" . $idreconocimientos . "'");
    if ($sql_modificar) {
        echo "exito";
    } else {
        echo mysql_error();
    }
}

if ($ejecutar == "eliminarReconocimiento") {
    $sql_consulta = mysql_query("select * from reconocimientos where idreconocimientos = '" . $idreconocimientos . "'") or die(mysql_error());
    $bus_consulta = mysql_fetch_array($sql_consulta);

    if ($bus_consulta["nombre_imagen"] != '') {
        unlink("../imagenes/reconocimientos/" . $bus_consulta["nombre_imagen"]);
    }

    $sql_eliminar = mysql_query("delete from reconocimientos where idreconocimientos = '" . $idreconocimientos . "'") or die(mysql_error());
}

if ($ejecutar == "consultarReconocimientos") {
    $sql_consulta = mysql_query("select * from reconocimientos where idtrabajador = '" . $idtrabajador . "'") or die(mysql_error());

    ?>
    <table border="0" class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
            <thead>
            <tr>
                <td width="19%" class="Browse" align="center">Imagen</td>
                <td width="26%" class="Browse" align="center">Tipo</td>
                <td width="29%" class="Browse" align="center">Fecha de Entrega</td>
                <td width="15%" class="Browse" align="center">Motivo</td>
                <td width="11%" class="Browse" align="center" colspan="2">Acci&oacute;n</td>
            </tr>
            </thead>
            <?
    while ($bus_consulta = mysql_fetch_array($sql_consulta)) {
        ?>
            <tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align='center' class='Browse' width='20%'>
            <a href="javascript:;" onClick="document.getElementById('imagen_<?=$bus_consulta["idreconocimientos"]?>').style.display= 'block'">Ver Imagen</a>
            <div style=" position:absolute; background-color:#CCCCCC; display:none; width:600px; height:400px; border:#666666 solid 1px;" id="imagen_<?=$bus_consulta["idreconocimientos"]?>">
            <div align="right" style="cursor:pointer"><strong onClick="document.getElementById('imagen_<?=$bus_consulta["idreconocimientos"]?>').style.display= 'none'">Cerrar</strong></div>
            <?
        if (file_exists("../imagenes/reconocimientos/" . $bus_consulta["nombre_imagen"]) and $bus_consulta["nombre_imagen"] != '') {
            ?>
                <img src="modulos/rrhh/imagenes/reconocimientos/<?=$bus_consulta["nombre_imagen"]?>">
                <?
        } else {
            ?>
                <center><strong style="font-size:18px">SIN IMAGEN</strong></center>
                <?
        }
        ?>

            </div>
            </td>
                <td align="left" class="Browse">
                <?
        $sql_reconocimientos = mysql_query("select * from tipos_reconocimientos where idtipo_reconocimientos = '" . $bus_consulta["idtipo_reconocimientos"] . "'");
        $bus_reconocimientos = mysql_fetch_array($sql_reconocimientos);

        echo "&nbsp;" . $bus_reconocimientos["descripcion"]?></td>
                <td align="left" class="Browse"><?=$bus_consulta["fecha_entrega"]?></td>
                <td align="left" class="Browse"><?=$bus_consulta["motivo"]?></td>
                <td align="center" class="Browse"><img src="imagenes/modificar.png" style="cursor:pointer" onclick="seleccionarReconocimientos('<?=$bus_consulta["idreconocimientos"]?>', '<?=$bus_consulta["tipo"]?>', '<?=$bus_consulta["motivo"]?>', '<?=$bus_consulta["fecha"]?>', '<?=$bus_consulta["numero_documento"]?>', '<?=$bus_consulta["fecha_entrega"]?>', '<?=$bus_consulta["constancia_anexa"]?>', '<?=$bus_consulta["nombre_imagen"]?>', '<?=$bus_consulta["observaciones"]?>')"></td>
                <td align="center" class="Browse"><img src="imagenes/delete.png" style="cursor:pointer" onclick="eliminarReconocimiento('<?=$bus_consulta["idreconocimientos"]?>')"></td>
            </tr>
            <?
    }
    ?>
            </table>


    <?
}

if ($ejecutar == "cargarFotoReconocimientos") {

    $tipo = substr($_FILES['foto_reconocimientos']['type'], 0, 5);
    $dir  = '../imagenes/reconocimientos/';
    if ($tipo == 'image') {
        $nombre_imagen = $_FILES['foto_reconocimientos']['name'];
        while (file_exists($dir . $nombre_imagen)) {
            $partes_img    = explode(".", $nombre_imagen);
            $nombre_imagen = $partes_img[0] . rand(0, 1000000) . "." . $partes_img[1];
        }
        if (!copy($_FILES['foto_reconocimientos']['tmp_name'], $dir . $nombre_imagen)) {
            ?>
                <script>
                parent.document.getElementById('mostrarImagen').innerHTML = "<table><tr><td style='color:#990000; font-weight:bold'>* Disculpe la imagen no se pudo ingresar</td></tr></table>";
                </script>
                <?
        } else {
            $ruta = 'modulos/rrhh/imagenes/reconocimientos/' . $nombre_imagen;
        }

        ?>

            <script>
            parent.document.getElementById('nombre_imagen_reconocimientos').value = '<?=$nombre_imagen?>';
            </script>
            <?

    } else {
        ?>
            <script>
            parent.mostrarMensajes("error", "Disculpe el archivo que intenta subir NO es una Imagen");
            </script>

            <?
    }

}

// **************************************************************************************************************************
// **************************************************************************************************************************
// ****************************************************** SANCIONES ***************************************************
// **************************************************************************************************************************
// **************************************************************************************************************************

if ($ejecutar == "ingresarsanciones") {
    $sql_ingresar = mysql_query("insert into sanciones (idtrabajador,
                                                            idtipo_sanciones,
                                                            motivo,
                                                            fecha,
                                                            numero_documento,
                                                            fecha_entrega,
                                                            constancia_anexa,
                                                            nombre_imagen,
                                                            observaciones,
                                                            usuario,
                                                            fechayhora)VALUES('" . $idtrabajador . "',
                                                                                '" . $tipo . "',
                                                                                '" . $motivo . "',
                                                                                '" . $fecha . "',
                                                                                '" . $numero_documentos . "',
                                                                                '" . $fecha_entrega . "',
                                                                                '" . $constancia_anexa . "',
                                                                                '" . $nombre_imagen . "',
                                                                                '" . $observaciones . "',
                                                                                '" . $login . "',
                                                                                '" . $fh . "')");
    if ($sql_ingresar) {
        echo "exito";
    } else {
        echo mysql_error();
    }
}

if ($ejecutar == "modificarsanciones") {
    $sql_consulta = mysql_query("SELECT *
                                    FROM
                                sanciones
                                    WHERE
                                idsanciones = '" . $idsanciones . "'");
    $bus_consulta = mysql_fetch_array($sql_consulta);

    if ($bus_consulta["nombre_imagen"] != '') {
        unlink("../imagenes/sanciones/" . $bus_consulta["nombre_imagen"]);
    }

    $sql_modificar = mysql_query("UPDATE sanciones SET idtipo_sanciones         = '" . $tipo . "',
                                                            motivo              = '" . $motivo . "',
                                                            fecha               = '" . $fecha . "',
                                                            numero_documento    = '" . $numero_documentos . "',
                                                            fecha_entrega       = '" . $fecha_entrega . "',
                                                            constancia_anexa    = '" . $constancia_anexa . "',
                                                            nombre_imagen       = '" . $nombre_imagen . "',
                                                            observaciones       = '" . $observaciones . "'
                                                                WHERE
                                                            idsanciones         = '" . $idsanciones . "'");
    if ($sql_modificar) {
        echo "exito";
    } else {
        echo mysql_error();
    }
}

if ($ejecutar == "eliminarsancion") {
    $sql_consulta = mysql_query("select * from sanciones where idsanciones = '" . $idsanciones . "'") or die(mysql_error());
    $bus_consulta = mysql_fetch_array($sql_consulta);

    if ($bus_consulta["nombre_imagen"] != '') {
        unlink("../imagenes/sanciones/" . $bus_consulta["nombre_imagen"]);
    }

    $sql_eliminar = mysql_query("delete from sanciones where idsanciones = '" . $idsanciones . "'") or die(mysql_error());
}

if ($ejecutar == "consultarsanciones") {
    $sql_consulta = mysql_query("select * from sanciones where idtrabajador = '" . $idtrabajador . "'");
    ?>
    <table border="0" class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
            <thead>
            <tr>
                <td width="19%" class="Browse" align="center">Imagen</td>
                <td width="26%" class="Browse" align="center">Tipo</td>
                <td width="29%" class="Browse" align="center">Fecha de Entrega</td>
                <td width="15%" class="Browse" align="center">Motivo</td>
                <td width="11%" class="Browse" align="center" colspan="2">Acci&oacute;n</td>
            </tr>
            </thead>
            <?
    while ($bus_consulta = mysql_fetch_array($sql_consulta)) {
        ?>
            <tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align='center' class='Browse' width='20%'>
            <a href="javascript:;" onClick="document.getElementById('imagen_sanciones_<?=$bus_consulta["idsanciones"]?>').style.display= 'block'">Ver Imagen</a>
            <div style=" position:absolute; background-color:#CCCCCC; display:none; width:600px; height:400px; border:#666666 solid 1px;" id="imagen_sanciones_<?=$bus_consulta["idsanciones"]?>">
            <div align="right" style="cursor:pointer"><strong onClick="document.getElementById('imagen_sanciones_<?=$bus_consulta["idsanciones"]?>').style.display= 'none'">Cerrar</strong></div>
            <?
        if (file_exists("../imagenes/sanciones/" . $bus_consulta["nombre_imagen"]) and $bus_consulta["nombre_imagen"] != '') {
            ?>
                <img src="modulos/rrhh/imagenes/sanciones/<?=$bus_consulta["nombre_imagen"]?>">
                <?
        } else {
            ?>
                <center><strong style="font-size:18px">SIN IMAGEN</strong></center>
                <?
        }
        ?>

            </div>
            </td>
                <td align="left" class="Browse"><?
        $sql_sanciones = mysql_query("select * from tipo_sanciones where idtipo_sanciones = '" . $bus_consulta["idtipo_sanciones"] . "'") or die(mysql_error());
        $bus_sanciones = mysql_fetch_array($sql_sanciones);
        echo "&nbsp;" . $bus_sanciones["descripcion"]?></td>
                <td align="left" class="Browse"><?=$bus_consulta["fecha_entrega"]?></td>
                <td align="left" class="Browse"><?=$bus_consulta["motivo"]?></td>
                <td align="center" class="Browse"><img src="imagenes/modificar.png" style="cursor:pointer" onclick="seleccionarsanciones('<?=$bus_consulta["idsanciones"]?>', '<?=$bus_consulta["tipo"]?>', '<?=$bus_consulta["motivo"]?>', '<?=$bus_consulta["fecha"]?>', '<?=$bus_consulta["numero_documento"]?>', '<?=$bus_consulta["fecha_entrega"]?>', '<?=$bus_consulta["constancia_anexa"]?>', '<?=$bus_consulta["nombre_imagen"]?>', '<?=$bus_consulta["observaciones"]?>')"></td>
                <td align="center" class="Browse"><img src="imagenes/delete.png" style="cursor:pointer" onclick="eliminarsancion('<?=$bus_consulta["idsanciones"]?>')"></td>
            </tr>
            <?
    }
    ?>
            </table>


    <?
}

if ($ejecutar == "cargarFotosanciones") {

    $tipo = substr($_FILES['foto_sanciones']['type'], 0, 5);
    $dir  = '../imagenes/sanciones/';
    if ($tipo == 'image') {
        $nombre_imagen = $_FILES['foto_sanciones']['name'];
        while (file_exists($dir . $nombre_imagen)) {
            $partes_img    = explode(".", $nombre_imagen);
            $nombre_imagen = $partes_img[0] . rand(0, 1000000) . "." . $partes_img[1];
        }
        if (!copy($_FILES['foto_sanciones']['tmp_name'], $dir . $nombre_imagen)) {
            ?>
                <script>
                parent.document.getElementById('mostrarImagen').innerHTML = "<table><tr><td style='color:#990000; font-weight:bold'>* Disculpe la imagen no se pudo ingresar</td></tr></table>";
                </script>
                <?
        } else {
            $ruta = 'modulos/rrhh/imagenes/sanciones/' . $nombre_imagen;
        }

        ?>

            <script>
            parent.document.getElementById('nombre_imagen_sanciones').value = '<?=$nombre_imagen?>';
            </script>
            <?

    } else {
        ?>
            <script>
            parent.mostrarMensajes("error", "Disculpe el archivo que intenta subir NO es una Imagen");
            </script>

            <?
    }

}

// **************************************************************************************************************************
// **************************************************************************************************************************
// ****************************************************** DECLARACION JURADA ***************************************************
// **************************************************************************************************************************
// **************************************************************************************************************************

if ($ejecutar == "ingresardeclaracion_jurada") {
    $sql_ingresar = mysql_query("insert into declaracion_jurada (idtrabajador,
                                                            tipo,
                                                            fecha_declaracion,
                                                            fecha_entrega,
                                                            constancia_anexa,
                                                            nombre_imagen,
                                                            observaciones,
                                                            usuario,
                                                            fechayhora)VALUES('" . $idtrabajador . "',
                                                                                '" . $tipo . "',
                                                                                '" . $fecha_declaracion . "',
                                                                                '" . $fecha_entrega . "',
                                                                                '" . $constancia_anexa . "',
                                                                                '" . $nombre_imagen . "',
                                                                                '" . $observaciones . "',
                                                                                '" . $login . "',
                                                                                '" . $fh . "')");
    if ($sql_ingresar) {
        echo "exito";
    } else {
        echo mysql_error();
    }
}

if ($ejecutar == "modificardeclaracion_jurada") {
    $sql_consulta = mysql_query("SELECT *
                                    FROM
                                declaracion_jurada
                                    WHERE
                                iddeclaracion = '" . $iddeclaracion_jurada . "'");
    $bus_consulta = mysql_fetch_array($sql_consulta);

    if ($bus_consulta["nombre_imagen"] != '') {
        unlink("../imagenes/declaracion_jurada/" . $bus_consulta["nombre_imagen"]);
    }

    $sql_modificar = mysql_query("UPDATE declaracion_jurada SET tipo            = '" . $tipo . "',
                                                            fecha_declaracion   = '" . $fecha_declaracion . "',
                                                            fecha_entrega       = '" . $fecha_entrega . "',
                                                            constancia_anexa    = '" . $constancia_anexa . "',
                                                            nombre_imagen       = '" . $nombre_imagen . "',
                                                            observaciones       = '" . $observaciones . "'
                                                                WHERE
                                                            iddeclaracion       = '" . $iddeclaracion_jurada . "'");
    if ($sql_modificar) {
        echo "exito";
    } else {
        echo mysql_error();
    }
}

if ($ejecutar == "eliminarDeclaracion_jurada") {
    $sql_consulta = mysql_query("select * from declaracion_jurada where iddeclaracion = '" . $iddeclaracion_jurada . "'") or die(mysql_error());
    $bus_consulta = mysql_fetch_array($sql_consulta);

    if ($bus_consulta["nombre_imagen"] != '') {
        unlink("../imagenes/declaracion_jurada/" . $bus_consulta["nombre_imagen"]);
    }

    $sql_eliminar = mysql_query("delete from declaracion_jurada where iddeclaracion = '" . $iddeclaracion_jurada . "'") or die(mysql_error());
}

if ($ejecutar == "consultardeclaracion_jurada") {
    $sql_consulta = mysql_query("select * from declaracion_jurada where idtrabajador = '" . $idtrabajador . "'");
    ?>
    <table border="0" class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
            <thead>
            <tr>
                <td width="19%" class="Browse" align="center">Imagen</td>
                <td width="26%" class="Browse" align="center">Tipo</td>
                <td width="29%" class="Browse" align="center">Fecha de Entrega</td>
                <td width="15%" class="Browse" align="center">Fecha de Declaracion</td>
                <td width="11%" class="Browse" align="center" colspan="2">Acci&oacute;n</td>
            </tr>
            </thead>
            <?
    while ($bus_consulta = mysql_fetch_array($sql_consulta)) {
        ?>
            <tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align='center' class='Browse' width='20%'>
            <a href="javascript:;" onClick="document.getElementById('imagen_declaracion_jurada_<?=$bus_consulta["iddeclaracion"]?>').style.display= 'block'">Ver Imagen</a>
            <div style=" position:absolute; background-color:#CCCCCC; display:none; width:600px; height:400px; border:#666666 solid 1px;" id="imagen_declaracion_jurada_<?=$bus_consulta["iddeclaracion"]?>">
            <div align="right" style="cursor:pointer"><strong onClick="document.getElementById('imagen_declaracion_jurada_<?=$bus_consulta["iddeclaracion"]?>').style.display= 'none'">Cerrar</strong></div>
            <?
        if (file_exists("../imagenes/declaracion_jurada/" . $bus_consulta["nombre_imagen"]) and $bus_consulta["nombre_imagen"] != '') {
            ?>
                <img src="modulos/rrhh/imagenes/declaracion_jurada/<?=$bus_consulta["nombre_imagen"]?>">
                <?
        } else {
            ?>
                <center><strong style="font-size:18px">SIN IMAGEN</strong></center>
                <?
        }
        ?>

            </div>
            </td>
                <td align="left" class="Browse"><?=$bus_consulta["tipo"]?></td>
                <td align="left" class="Browse"><?=$bus_consulta["fecha_entrega"]?></td>
                <td align="left" class="Browse"><?=$bus_consulta["fecha_declaracion"]?></td>
                <td align="center" class="Browse"><img src="imagenes/modificar.png" style="cursor:pointer" onclick="seleccionardeclaracion_jurada('<?=$bus_consulta["iddeclaracion"]?>', '<?=$bus_consulta["tipo"]?>', '<?=$bus_consulta["fecha_declaracion"]?>', '<?=$bus_consulta["fecha_entrega"]?>', '<?=$bus_consulta["constancia_anexa"]?>', '<?=$bus_consulta["nombre_imagen"]?>', '<?=$bus_consulta["observaciones"]?>')"></td>
                <td align="center" class="Browse"><img src="imagenes/delete.png" style="cursor:pointer" onclick="eliminarsancion('<?=$bus_consulta["iddeclaracion_jurada"]?>')"></td>
            </tr>
            <?
    }
    ?>
            </table>


    <?
}

if ($ejecutar == "cargarFotodeclaracion_jurada") {

    $tipo = substr($_FILES['foto_declaracion_jurada']['type'], 0, 5);
    $dir  = '../imagenes/declaracion_jurada/';
    if ($tipo == 'image') {
        $nombre_imagen = $_FILES['foto_declaracion_jurada']['name'];
        while (file_exists($dir . $nombre_imagen)) {
            $partes_img    = explode(".", $nombre_imagen);
            $nombre_imagen = $partes_img[0] . rand(0, 1000000) . "." . $partes_img[1];
        }
        if (!copy($_FILES['foto_declaracion_jurada']['tmp_name'], $dir . $nombre_imagen)) {
            ?>
                <script>
                parent.document.getElementById('mostrarImagen').innerHTML = "<table><tr><td style='color:#990000; font-weight:bold'>* Disculpe la imagen no se pudo ingresar</td></tr></table>";
                </script>
                <?
        } else {
            $ruta = 'modulos/rrhh/imagenes/declaracion_jurada/' . $nombre_imagen;
        }

        ?>

            <script>
            parent.document.getElementById('nombre_imagen_declaracion_jurada').value = '<?=$nombre_imagen?>';
            </script>
            <?

    } else {
        ?>
            <script>
            parent.mostrarMensajes("error", "Disculpe el archivo que intenta subir NO es una Imagen");
            </script>

            <?
    }

}

// **************************************************************************************************************************
// **************************************************************************************************************************
// ****************************************************** CURSOS REALIZADOS *************************************************
// **************************************************************************************************************************
// **************************************************************************************************************************

if ($ejecutar == "ingresarCursos") {
    $sql_ingresar = mysql_query("insert into cursos_otros_estudios (idtrabajador,
                                                            denominacion,
                                                            detalle_contenido,
                                                            duracion,
                                                            dias_horas,
                                                            desde,
                                                            hasta,
                                                            institucion,
                                                            telefonos,
                                                            realizado_por,
                                                            constancia_anexa,
                                                            nombre_imagen,
                                                            usuario,
                                                            fechayhora)VALUES('" . $idtrabajador . "',
                                                                                '" . $denominacion . "',
                                                                                '" . $detalle_contenido . "',
                                                                                '" . $duracion . "',
                                                                                '" . $tipo_duracion . "',
                                                                                '" . $desde . "',
                                                                                '" . $hasta . "',
                                                                                '" . $institucion . "',
                                                                                '" . $telefonos . "',
                                                                                '" . $realizado_por . "',
                                                                                '" . $constancia_anexa . "',
                                                                                '" . $nombre_imagen . "',
                                                                                '" . $login . "',
                                                                                '" . $fh . "')");
    if ($sql_ingresar) {
        echo "exito";
    } else {
        echo mysql_error();
    }
}

if ($ejecutar == "modificarCursos") {
    $sql_consulta = mysql_query("SELECT *
                                    FROM
                                cursos_otros_estudios
                                    WHERE
                                idcurso = '" . $idcursos . "'");
    $bus_consulta = mysql_fetch_array($sql_consulta);

    if ($bus_consulta["nombre_imagen"] != '') {
        unlink("../imagenes/cursos/" . $bus_consulta["nombre_imagen"]);
    }

    $sql_modificar = mysql_query("UPDATE cursos_otros_estudios SET
                                                            denominacion            = '" . $denominacion . "',
                                                            detalle_contenido       = '" . $detalle_contenido . "',
                                                            duracion                = '" . $duracion . "',
                                                            dias_horas              = '" . $dias_horas . "',
                                                            desde                   = '" . $desde . "',
                                                            hasta                   = '" . $hasta . "',
                                                            institucion             = '" . $institucion . "',
                                                            telefonos               = '" . $telefonos . "',
                                                            realizado_por           = '" . $realizado_por . "',
                                                            constancia_anexa        = '" . $constancia_anexa . "',
                                                            nombre_imagen           = '" . $nombre_imagen . "',
                                                            dias_horas              = '" . $tipo_duracion . "'
                                                                WHERE
                                                            idcurso                 = '" . $idcursos . "'") or die(mysql_error());
    if ($sql_modificar) {
        echo "exito";
    } else {
        echo mysql_error();
    }
}

if ($ejecutar == "eliminarCursos") {
    $sql_consulta = mysql_query("select * from cursos_otros_estudios where idcurso = '" . $idcursos . "'") or die(mysql_error());
    $bus_consulta = mysql_fetch_array($sql_consulta);

    if ($bus_consulta["nombre_imagen"] != '') {
        unlink("../imagenes/cursos/" . $bus_consulta["nombre_imagen"]);
    }

    $sql_eliminar = mysql_query("delete from cursos_otros_estudios where idcurso = '" . $idcursos . "'") or die(mysql_error());
}

if ($ejecutar == "consultarCursos") {
    $sql_consulta = mysql_query("select * from cursos_otros_estudios where idtrabajador = '" . $idtrabajador . "'");
    ?>
    <table border="0" class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
            <thead>
            <tr>
                <td width="19%" class="Browse" align="center">Imagen</td>
                <td width="26%" class="Browse" align="center">Denominacion</td>
                <td width="29%" class="Browse" align="center">Desde</td>
                <td width="15%" class="Browse" align="center">Hasta</td>
                <td width="11%" class="Browse" align="center" colspan="2">Acci&oacute;n</td>
            </tr>
            </thead>
            <?
    while ($bus_consulta = mysql_fetch_array($sql_consulta)) {
        ?>
            <tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align='center' class='Browse' width='20%'>
            <a href="javascript:;" onClick="document.getElementById('imagen_cursos_<?=$bus_consulta["idcurso"]?>').style.display= 'block'">Ver Imagen</a>
            <div style=" position:absolute; background-color:#CCCCCC; display:none; width:600px; height:400px; border:#666666 solid 1px;" id="imagen_cursos_<?=$bus_consulta["idcurso"]?>">
            <div align="right" style="cursor:pointer"><strong onClick="document.getElementById('imagen_cursos_<?=$bus_consulta["idcurso"]?>').style.display= 'none'">Cerrar</strong></div>
            <?
        if (file_exists("../imagenes/cursos/" . $bus_consulta["nombre_imagen"]) and $bus_consulta["nombre_imagen"] != '') {
            ?>
                <img src="modulos/rrhh/imagenes/cursos/<?=$bus_consulta["nombre_imagen"]?>">
                <?
        } else {
            ?>
                <center><strong style="font-size:18px">SIN IMAGEN</strong></center>
                <?
        }
        ?>

            </div>
            </td>
                <td align="left" class="Browse"><?=$bus_consulta["denominacion"]?></td>
                <td align="left" class="Browse"><?=$bus_consulta["desde"]?></td>
                <td align="left" class="Browse"><?=$bus_consulta["hasta"]?></td>
                <td align="center" class="Browse"><img src="imagenes/modificar.png" style="cursor:pointer" onclick="seleccionarCursos('<?=$bus_consulta["idcurso"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["detalle_contenido"]?>', '<?=$bus_consulta["desde"]?>', '<?=$bus_consulta["hasta"]?>', '<?=$bus_consulta["duracion"]?>', '<?=$bus_consulta["institucion"]?>', '<?=$bus_consulta["telefonos"]?>', '<?=$bus_consulta["realizado_por"]?>', '<?=$bus_consulta["constancia_anexa"]?>' , '<?=$bus_consulta["dias_horas"]?>')"></td>
                <td align="center" class="Browse"><img src="imagenes/delete.png" style="cursor:pointer" onclick="eliminarCursos('<?=$bus_consulta["idcurso"]?>')"></td>
            </tr>
            <?
    }
    ?>
            </table>


    <?
}

if ($ejecutar == "cargarFotoCursos") {

    $tipo = substr($_FILES['foto_cursos']['type'], 0, 5);
    $dir  = '../imagenes/cursos/';
    if ($tipo == 'image') {
        $nombre_imagen = $_FILES['foto_cursos']['name'];
        while (file_exists($dir . $nombre_imagen)) {
            $partes_img    = explode(".", $nombre_imagen);
            $nombre_imagen = $partes_img[0] . rand(0, 1000000) . "." . $partes_img[1];
        }
        if (!copy($_FILES['foto_cursos']['tmp_name'], $dir . $nombre_imagen)) {
            ?>
                <script>
            parent.mostrarMensajes("error", "Disculpe la imagen no se pudo ingresar");
            </script>
                <?
        } else {
            $ruta = 'modulos/rrhh/imagenes/cursos/' . $nombre_imagen;
        }

        ?>

            <script>
            parent.document.getElementById('nombre_imagen_cursos').value = '<?=$nombre_imagen?>';
            </script>
            <?

    } else {
        ?>
            <script>
            parent.mostrarMensajes("error", "Disculpe el archivo que intenta subir NO es una Imagen..");
            </script>

            <?
    }

}

// ***************************************************************************************************************************
// ***************************************************************************************************************************
// ************************************************* HISTORICO DE VACACAIONES ************************************************
// ***************************************************************************************************************************
// ***************************************************************************************************************************

function obtener_dias_entre_fechas($fechad, $fechah)
{
    // DEVUELVE DIFERENCIAS EN DIAS PERO CONTANDO LOS FINES DESEMANA
    list($ano1, $mes1, $dia1) = SPLIT('[/.-]', $fechad);
    list($ano2, $mes2, $dia2) = SPLIT('[/.-]', $fechah);
    //calculo timestam de las dos fechas
    $timestamp1 = mktime(0, 0, 0, $mes1, $dia1, $ano1);
    $timestamp2 = mktime(4, 12, 0, $mes2, $dia2, $ano2);
    //resto a una fecha la otra
    $segundos_diferencia = $timestamp1 - $timestamp2;
    //echo $segundos_diferencia;
    //convierto segundos en d�as
    $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
    //obtengo el valor absoulto de los d�as (quito el posible signo negativo)
    $dias_diferencia = abs($dias_diferencia);
    //quito los decimales a los d�as de diferencia
    $dias_diferencia = floor($dias_diferencia);
    return $dias_diferencia;
}

function getDiaSemana($fecha)
{
    $fecha                  = str_replace("/", "-", $fecha);
    list($dia, $mes, $anio) = explode("-", $fecha);
    $dia                    = (((mktime(0, 0, 0, $mes, $dia, $anio) - mktime(0, 0, 0, 7, 17, $anio)) / (60 * 60 * 24)) + 700000) % 7;
    //$dia--; if ($dia == 0) $dia = 7;
    return $dia;
}

if ($ejecutar == "obten_disfrute_completo_vacaciones") {
    //echo "obten_disfrute_completo";
    if ($tiempo_disfrute == "") {
        $tiempo_disfrute = 0;
    }

    if ($tiempo_disfrutado == "") {
        $tiempo_disfrutado = 0;
    }

    $resultado = $tiempo_disfrute - $tiempo_disfrutado;
    echo $resultado;
}

if ($ejecutar == "cant_feriados_vacaciones") {
    //echo "cant_feriados";
    $total_dias = $dias_disfrute + $catidad_feriados;
    echo $total_dias;
}

if ($ejecutar == "llenaroculto_vacaciones") {
    echo $valor;
}

if ($ejecutar == "validarPeriodo_vacaciones") {
    $cantidad = strlen($periodo);
    if ($cantidad == 9) {
        $guion = substr($periodo, 4, 1);
        if ($guion == "-") {
            $sms = 1;
        } else {
            $sms = 2;
        }
    } else {
        $sms = 0;
    }
    echo $sms;
}

if ($ejecutar == "reinicioAjustado_vacaciones") {
    //echo "reinicioAjustado";
    if ($reinicio_ajustado == $fecha_reincorporacion && $fecha_reincorporacion != '') {
        $sms = 0;
    } else {
        $sms = 1;
    }
    echo $sms;
}

if ($ejecutar == "validarFechas_vacaciones") {

    if ($fecha_inicio == "") {
        $fecha_inicio = $fecha_culminacion;
    }

    if ($fecha_culminacion == "") {
        $fecha_culminacion = $fecha_inicio;
    }

    list($a, $m, $d) = SPLIT('[/.-]', $fecha_inicio);
    $fdesde          = "$d-$m-$a";
    list($a, $m, $d) = SPLIT('[/.-]', $fecha_culminacion);
    $fhasta          = "$d-$m-$a";

    list($dd, $mm, $ad) = SPLIT('[/.-]', $fdesde);
    $dd                 = (int) $md;
    $md                 = (int) $dd;
    $ad                 = (int) $ad;
    $dias_completos     = obtener_dias_entre_fechas($fecha_inicio, $fecha_culminacion);
    $dia_semana         = getDiaSemana($fdesde);
    $dias_permiso       = 0;

    for ($i = 0; $i <= $dias_completos; $i++) {
        if ($dia_semana == 8) {
            $dia_semana = 1;
        }

        if ($dia_semana >= 2 && $dia_semana <= 6) {
            $dias_permiso++;
        }

        $dia_semana++;
    }
    echo $dias_permiso;
}

if ($ejecutar == "llenarFormulario_vacaciones") {

    $sql   = "SELECT * FROM historico_vacaciones WHERE idhistorico_vacaciones = '" . $id_historico . "'; ";
    $query = mysql_query($sql);
    $array = mysql_fetch_array($query);
    ?>
        <table border="0">
            <tr>
                <td><input type="hidden" value="<?=$array['idhistorico_vacaciones']?>" id="idhistorico_vacaciones_encontrado_vacaciones"/></td>
                <td><input type="hidden" value="<?=$array['idtrabajador']?>" id="idtrabajador_encontrado_vacaciones"/></td>

                <td><input type="hidden" value="<?=$array['periodo']?>" id="periodo_encontrado_vacaciones"/></td>
                <td><input type="hidden" value="<?=$array['numero_memorandum']?>" id="numero_memorandum_encontrado_vacaciones"/></td>

                <td><input type="hidden" value="<?=$array['fecha_memorandum']?>" id="fecha_memorandum_encontrado_vacaciones"/></td>
                <td><input type="hidden" value="<?=$array['fecha_inicio_vacacion']?>" id="fecha_inicio_vacacion_encontrado_vacaciones"/></td>

                <td><input type="hidden" value="<?=$array['fecha_culminacion_vacacion']?>" id="fecha_culminacion_vacacion_encontrado_vacaciones"/></td>
                <td><input type="hidden" value="<?=$array['tiempo_disfrute']?>" id="tiempo_disfrute_encontrado_vacaciones"/></td>

                <td><input type="hidden" value="<?=$array['fecha_inicio_disfrute']?>" id="fecha_inicio_disfrute_encontrado_vacaciones"/></td>
                <td><input type="hidden" value="<?=$array['fecha_reincorporacion']?>" id="fecha_reincorporacion_encontrado_vacaciones"/></td>

                <td><input type="hidden" value="<?=$array['fecha_reincorporacion_ajustada']?>" id="fecha_reincorporacion_ajustada_encontrado_vacaciones"/></td>
                <td><input type="hidden" value="<?=$array['dias_pendiente_disfrute']?>" id="dias_pendiente_disfrute_encontrado_vacaciones"/></td>

                <td><input type="hidden" value="<?=$array['dias_bono']?>" id="dias_bono_encontrado_vacaciones"/></td>
                <td><input type="hidden" value="<?=$array['monto_bono']?>" id="monto_bonos_encontrado_vacaciones"/></td>

                <td><input type="hidden" value="<?=$array['numero_orden_pago']?>" id="numero_orden_pago_encontrado_vacaciones"/></td>
                <td><input type="hidden" value="<?=$array['fecha_cancelacion']?>" id="fecha_cancelacion_encontrado_vacaciones"/></td>

                <td><input type="hidden" value="<?=$array['elaborado_por']?>" id="elaborado_por_encontrado_vacaciones"/></td>
                <td><input type="hidden" value="<?=$array['ci_elaborado_por']?>" id="ci_elaborado_por_encontrado_vacaciones"/></td>

                <td><input type="hidden" value="<?=$array['aprobada_por']?>" id="aprobada_por_encontrado_vacaciones"/></td>
                <td><input type="hidden" value="<?=$array['ci_aprobado']?>" id="ci_aprobado_encontrado_vacaciones"/></td>

                <td><input type="hidden" value="<?=$array['cantidad_feriados']?>" id="cantidad_feriadostabla_vacaciones"/></td>
                <td><input type="hidden" value="<?=$array['oculto_dias']?>" id="oculto_dias_encontrado_vacaciones"/></td>
                <td><input type="hidden" value="<?=$array['oculto_disfrutados']?>" id="oculto_disfrutados_encontrado_vacaciones"/></td>
            </tr>
        </table>
    <?
}

if ($ejecutar == "consultarVacacionesPendientes") {
    $str_sql = mysql_query("SELECT SUM(dias_pendiente_disfrute) as total FROM historico_vacaciones WHERE idtrabajador = '" . $idtrabajador . "'") or die(mysql_error());
    $bus_sql = mysql_fetch_array($str_sql);
    if ($bus_sql["total"] == '') {
        echo "0";
    } else {
        echo $bus_sql["total"];
    }
}

if ($ejecutar == "llenargrilla_vacaciones") {
    $str_sql = "SELECT * FROM historico_vacaciones WHERE idtrabajador = '" . $idtrabajador . "'";
    $result  = mysql_query($str_sql);

    if (!$result) {
        echo "Error en llenar grilla";
    } else {
        ?>
            <table width="943" border="0" align="center" cellpadding="0" cellspacing="0">
              <thead>
              <tr>
                <td class="Browse" align="center">N&uacute;mero Memorandum</td>
                <td class="Browse" align="center">Fecha Inicio Vacaci&oacute;n</td>
                <td class="Browse" align="center">Fecha Culminaci&oacute;n Vacaci&oacute;n</td>
                <td class="Browse" align="center">N&uacute;mero Orden Pago</td>
                <td class="Browse" align="center" colspan="2">Acci&oacute;n</td>
              </tr>
              </thead>
              <?while ($array = mysql_fetch_array($result)) {?>
              <tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td class="Browse" align="center"><?=$array['numero_memorandum']?></td>
                <td class="Browse" align="center"><?=$array['fecha_inicio_vacacion']?></td>
                <td class="Browse" align="center"><?=$array['fecha_culminacion_vacacion']?></td>
                <td class="Browse" align="center"><?=$array['numero_orden_pago']?></td>
                <td class="Browse" align="center"><img src="imagenes/modificar.png" style="cursor:pointer" title='Modificar' onclick="llenarFormulario_vacaciones(<?=$array['idhistorico_vacaciones']?>)"></td>
                <td class="Browse" align="center"><img src="imagenes/delete.png" style="cursor:pointer" title='Eliminar' onclick="eliminar_vacaciones('<?=$array['idhistorico_vacaciones']?>')"></td>
              </tr>
              <?}?>
            </table>
    <?}

}

if ($ejecutar == "accion_vacaciones") {
    //echo "accion";
    //echo $accion;
    if ($accion == "ingresar_vacaciones") {
        if ($idtrabajador == "") {
            $sms = 0;
        } else {
            /*if(
            $idtrabajador                         == ""
            ||$periodo                         == ""
            ||$numero_memorandum                 == ""
            ||$fecha_memorandum                 == ""
            ||$fecha_inicio_vacacion             == ""
            ||$fecha_culminacion_vacacion         == ""
            ||$tiempo_disfrute                 == ""
            ||$fecha_inicio_disfrute             == ""
            ||$fecha_reincorporacion             == ""
            ||$fecha_reincorporacion_ajustada     == ""
            ||$tiempo_pendiente_disfrute         == ""
            ||$dias_bonificacion                 == ""
            ||$monto_bono_vacacional             == ""
            ||$numero_orden_pago                 == ""
            ||$fecha_orden_pago                 == ""
            ||$elaborado_por                     == ""
            ||$ci_elaborado                     == ""
            ||$aprobado_por                     == ""
            ||$ci_aprobado                     == ""
            ||$cantidad_feriados                 == ""
            ||$oculto_dias                     == ""
            ||$oculto_disfrutados                 == ""){

            $sms = 1;

            }else{*/

            $sql_consulta = mysql_query("select * from historico_vacaciones
                                    where
                                    fecha_inicio_vacacion >= '" . $fecha_inicio_vacacion . "'
                                    and fecha_culminacion_vacacion <= '" . $fecha_culminacion_vacacion . "'");
            $num_consulta = mysql_num_rows($sql_consulta);

            if ($num_consulta > 0) {
                $sms = 5;
            } else {
                $str_sql = "INSERT INTO historico_vacaciones (
                                                          idtrabajador,
                                                          periodo,
                                                          numero_memorandum,
                                                          fecha_memorandum,
                                                          fecha_inicio_vacacion,
                                                          fecha_culminacion_vacacion,

                                                          cantidad_feriados,
                                                          tiempo_disfrute,
                                                          fecha_inicio_disfrute,
                                                          fecha_reincorporacion,
                                                          fecha_reincorporacion_ajustada,
                                                          dias_pendiente_disfrute,
                                                          dias_bono,

                                                          monto_bono,
                                                          numero_orden_pago,
                                                          fecha_cancelacion,
                                                          elaborado_por,
                                                          ci_elaborado_por,
                                                          aprobada_por,
                                                          ci_aprobado,
                                                          usuario,
                                                          fechayhora,

                                                          oculto_disfrutados,
                                                          oculto_dias
                                                          )VALUES(
                                                                      '" . $idtrabajador . "',
                                                                      '" . $periodo . "',
                                                                      '" . $numero_memorandum . "',
                                                                      '" . $fecha_memorandum . "',
                                                                      '" . $fecha_inicio_vacacion . "',
                                                                      '" . $fecha_culminacion_vacacion . "',
                                                                      '" . $cantidad_feriados . "',
                                                                      '" . $tiempo_disfrute . "',
                                                                      '" . $fecha_inicio_disfrute . "',
                                                                      '" . $fecha_reincorporacion . "',
                                                                      '" . $fecha_reincorporacion_ajustada . "',
                                                                      '" . $tiempo_pendiente_disfrute . "',
                                                                      '" . $dias_bonificacion . "',
                                                                      '" . $monto_bono_vacacional . "',
                                                                      '" . $numero_orden_pago . "',
                                                                      '" . $fecha_orden_pago . "',
                                                                      '" . $elaborado_por . "',
                                                                      '" . $ci_elaborado . "',
                                                                      '" . $aprobado_por . "',
                                                                      '" . $ci_aprobado . "',
                                                                      '" . $login . "',
                                                                      '" . $fh . "',

                                                                      '" . $oculto_dias . "',
                                                                      '" . $oculto_disfrutados . "'
                                                                      )";
                $result = mysql_query($str_sql);
                if (!$result) {
                    $sms = mysql_error();
                } else {
                    $sms = 2;
                }

            }

        }
        //}
    }
    //fin de accion guardar
    if ($accion == "modificar_vacaciones") {
        //echo "Modificar aqui";
        if (
            $idtrabajador == ""
            || $periodo == ""
            || $numero_memorandum == ""
            || $fecha_memorandum == ""
            || $fecha_inicio_vacacion == ""
            || $fecha_culminacion_vacacion == ""
            || $tiempo_disfrute == ""
            || $fecha_inicio_disfrute == ""
            || $fecha_reincorporacion == ""
            || $fecha_reincorporacion_ajustada == ""
            || $tiempo_pendiente_disfrute == ""
            || $dias_bonificacion == ""
            || $monto_bono_vacacional == ""
            || $numero_orden_pago == ""
            || $fecha_orden_pago == ""
            || $elaborado_por == ""
            || $ci_elaborado == ""
            || $aprobado_por == ""
            || $ci_aprobado == ""
            || $cantidad_feriados == ""
            || $oculto_dias == ""
            || $oculto_disfrutados == "") {

            $sms = 1;

        } else {
            $str = "UPDATE
                                                            historico_vacaciones SET
                                                    idtrabajador                    = '" . $idtrabajador . "',
                                                    periodo                         = '" . $periodo . "',
                                                    numero_memorandum               = '" . $numero_memorandum . "',
                                                    fecha_memorandum                = '" . $fecha_memorandum . "',
                                                    fecha_inicio_vacacion           = '" . $fecha_inicio_vacacion . "',
                                                    fecha_culminacion_vacacion      = '" . $fecha_culminacion_vacacion . "',
                                                    tiempo_disfrute                 = '" . $tiempo_disfrute . "',
                                                    fecha_inicio_disfrute           = '" . $fecha_inicio_disfrute . "',
                                                    fecha_reincorporacion           = '" . $fecha_reincorporacion . "',
                                                    fecha_reincorporacion_ajustada  = '" . $fecha_reincorporacion_ajustada . "',
                                                    dias_pendiente_disfrute         = '" . $tiempo_pendiente_disfrute . "',
                                                    dias_bono                       = '" . $dias_bonificacion . "',
                                                    monto_bono                      = '" . $monto_bono_vacacional . "',
                                                    cantidad_feriados               = '" . $cantidad_feriados . "',
                                                    numero_orden_pago               = '" . $numero_orden_pago . "',
                                                    fecha_cancelacion               = '" . $fecha_orden_pago . "',
                                                    elaborado_por                   = '" . $elaborado_por . "',
                                                    ci_elaborado_por                = '" . $ci_elaborado . "',
                                                    aprobada_por                    = '" . $aprobado_por . "',
                                                    ci_aprobado                     = '" . $ci_aprobado . "',
                                                    usuario                         = '" . $login . "',
                                                    fechayhora                      = '" . $fh . "',
                                                    oculto_dias                     = '" . $oculto_dias . "',
                                                    oculto_disfrutados              = '" . $oculto_disfrutados . "'
                                                        WHERE
                                                    idhistorico_vacaciones          = '" . $idhistorico_vacaciones . "';";
            $query = mysql_query($str);
            if (!$query) {
                $sms = mysql_error();
            } else {
                $sms = 2;
            }
        }
    } //fin de modificar
    echo $sms;
}

if ($ejecutar == "eliminar_vacaciones") {
    $sql_eliminar = mysql_query("delete from historico_vacaciones where idhistorico_vacaciones = '" . $idhistorico_vacaciones . "'");
}

// ***************************************************************************************************************************
// ***************************************************************************************************************************
// ************************************************* CUENTAS BANCARIAS ************************************************
// ***************************************************************************************************************************
// ***************************************************************************************************************************

if ($ejecutar == "consultarAsociados_cuentas_bancarias") {
    $sql_consultar = mysql_query("select
                                 t.nombres,
                                 t.apellidos,
                                 cb.nro_cuenta,
                                 cb.tipo,
                                 mc.denominacion as motivo,
                                 mc.idmotivos_cuentas,
                                 b.denominacion,
                                 b.idbanco,
                                 t.idtrabajador,
                                 cb.idcuentas_bancarias_trabajador
                                    from
                                 trabajador t,
                                 banco b,
                                 cuentas_bancarias_trabajador cb,
                                 motivos_cuentas mc
                                    where
                                 cb.idtrabajador = '" . $idtrabajador . "'
                                 and t.idtrabajador = cb.idtrabajador
                                 and b.idbanco = cb.banco
                                 and mc.idmotivos_cuentas = cb.motivo");

    ?>
    <table border="0" class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
            <thead>
            <tr>
                <td width="26%" class="Browse" align="center">Trabajador</td>
                <td width="29%" class="Browse" align="center">Nro. de Cuenta</td>
                <td width="15%" class="Browse" align="center">Tipo de Cuneta</td>
                <td width="19%" class="Browse" align="center">Motivo de la Cuenta</td>
                <td width="11%" class="Browse" align="center">Banco</td>
                <td width="11%" class="Browse" align="center" colspan="2">Acci&oacute;n</td>
            </tr>
            </thead>
            <?while ($bus_consultar = mysql_fetch_array($sql_consultar)) {?>
                <tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="left" class="Browse"><?=$bus_consultar["nombres"] . " " . $bus_consultar["apellidos"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["nro_cuenta"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["tipo"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["motivo"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["denominacion"]?></td>



                <td class="Browse" align="center">
                 <img src="imagenes/modificar.png" style="cursor:pointer" alt='Modificar' title='Modificar' onclick="seleccionar_cuentas_bancarias('<?=$bus_consultar["nombres"] . " " . $bus_consultar["apellidos"]?>','<?=$bus_consultar["nro_cuenta"]?>','<?=$bus_consultar["tipo"]?>', '<?=$bus_consultar["idmotivos_cuentas"]?>', '<?=$bus_consultar["idbanco"]?>', '<?=$bus_consultar["idcuentas_bancarias_trabajador"]?>')">                </td>
                 <td class="Browse" align="center">
                    <img src="imagenes/delete.png" style="cursor:pointer" alt='Eliminar' title='Eliminar' onclick="seleccionar_cuentas_bancarias('<?=$bus_consultar["nombres"] . " " . $bus_consultar["apellidos"]?>','<?=$bus_consultar["nro_cuenta"]?>','<?=$bus_consultar["tipo"]?>', '<?=$bus_consultar["idmotivos_cuentas"]?>', '<?=$bus_consultar["idbanco"]?>', '<?=$bus_consultar["idcuentas_bancarias_trabajador"]?>')">
                 </td>
          </tr>
            <?}?>
        </table>
    <?

}

if ($ejecutar == "ingresarCunetaBancaria") {
    $validar_cuenta    = mysql_query("select * from cuentas_bancarias_trabajador where nro_cuenta = '" . $numero_cuenta . "'");
    $existen_registros = mysql_num_rows($validar_cuenta);

    if ($existen_registros > 0) {
        echo 'existe';
    } else {
        $sql_ingresar = mysql_query("insert into cuentas_bancarias_trabajador (idtrabajador,
                                                                           nro_cuenta,
                                                                           tipo,
                                                                           motivo,
                                                                           banco)VALUES('" . $idtrabajador . "',
                                                                                        '" . $numero_cuenta . "',
                                                                                        '" . $tipo_cuenta . "',
                                                                                        '" . $motivo_cuenta . "',
                                                                                        '" . $banco . "')");
        echo 'exito';

    }

}

if ($ejecutar == "modificarCunetaBancaria") {
    $sql_ingresar = mysql_query("update cuentas_bancarias_trabajador set nro_cuenta             = '" . $numero_cuenta . "',
                                                                   tipo                             = '" . $tipo_cuenta . "',
                                                                   motivo                           = '" . $motivo_cuenta . "',
                                                                   banco                            = '" . $banco . "'
                                                                    where
                                                                   idcuentas_bancarias_trabajador   = '" . $idcuenta_bancaria . "'");
}

if ($ejecutar == "eliminarCuentaBancaria") {
    $sql_eliminar = mysql_query("delete from
                                        cuentas_bancarias_trabajador
                                            where
                                        idcuentas_bancarias_trabajador = '" . $idcuenta_bancaria . "'");
}

if ($ejecutar == "ingresarInformacionGeneral") {
    $sql_actualizar = mysql_query("update trabajador set
                                                        idgrupo_sanguineo           = '" . $idgrupo_sanguineo . "',
                                                        flag_donante                = '" . $flag_donante . "',
                                                        peso                        = '" . $peso . "',
                                                        talla                       = '" . $talla . "',
                                                        flag_vehiculo               = '" . $flag_vehiculo . "',
                                                        flag_licencia               = '" . $flag_licencia . "',
                                                        nombre_emergencia           = '" . $nombre_emergencia . "',
                                                        telefono_emergencia         = '" . $telefono_emergencia . "',
                                                        direccion_emergencia        = '" . $direccion_emergencia . "',
                                                        talla_camisa                = '" . $talla_camisa . "',
                                                        talla_pantalon              = '" . $talla_pantalon . "',
                                                        talla_zapatos               = '" . $talla_zapatos . "',
                                                        otras_actividades           = '" . $otras_actividades . "'
                                                        where idtrabajador          = '" . $idtrabajador . "'") or die(mysql_error());
    if ($sql_actualizar) {
        echo "exito";
    }

}

if ($ejecutar == "ingresarInformacionivss") {
    $sql_actualizar = mysql_query("update trabajador set
                                                        numero_registro_ivss        = '" . $numero_registro_ivss . "',
                                                        fecha_registro_ivss         = '" . $fecha_registro_ivss . "',
                                                        ocupacion_oficio_ivss       = '" . $ocupacion_oficio_ivss . "',
                                                        otro_ivss                   = '" . $otro_ivss . "'
                                                        where idtrabajador          = '" . $idtrabajador . "'") or die(mysql_error());
    if ($sql_actualizar) {
        echo "exito";
    }

}

if ($ejecutar == "mostrarPestanas") {
    ?>
    <ul>
        <li>
            <a href="javascript:;" onClick="mostrarPestana('div_datosBasicos'), document.getElementById('tabla_botones').style.display = 'block'">
                <span>Datos Basicos</span>
            </a>
        </li>
        <?
    //if(in_array(167, $privilegios) == true){
    ?>
        <li style="display:block" id="li_informacionGeneral">
            <a href="javascript:;" onClick="mostrarPestana('div_informacionGeneral')">
                <span>Informacion General</span>
            </a>
        </li>
        <?
    //}
    //if(in_array(167, $privilegios) == true){
    ?>
        <li style="display:block" id="li_datosEmpleo">
            <a href="javascript:;" onClick="mostrarPestana('div_datosEmpleo')">
                <span>Datos del Empleo</span>
            </a>
        </li>
        <?
    //}
    if (in_array(1207, $privilegios) == true) {
        ?>
        <li style="display:block" id="li_registroFotografico">
            <a href="javascript:;" onClick="mostrarPestana('div_registroFotografico')">
                <span>Registro Fotografico</span>
            </a>
        </li>
        <?
    }

    if (in_array(17, $privilegios) == true) {
        ?>

        <li style="display:block" id="li_cargaFamiliar">
            <a href="javascript:;" onClick="mostrarPestana('div_cargaFamiliar')">
                <span>Carga Familiar</span>
            </a>
        </li>
        <?
    }
    if (in_array(18, $privilegios) == true) {
        ?>
        <li style="display:block" id="li_estudiosRealizados">
            <a href="javascript:;" onClick="mostrarPestana('div_estudiosRealizados')">
                <span>Estudios Realizados</span>
            </a>
        </li>
        <?
    }
    if (in_array(1208, $privilegios) == true) {
        ?>
        <li style="display:block" id="li_cursosRealizados">
            <a href="javascript:;" onClick="mostrarPestana('div_cursosRealizados')">
                <span>Cursos Realizados</span>
            </a>
        </li>
        <?
    }
    if (in_array(19, $privilegios) == true) {
        ?>
        <li style="display:block" id="li_experienciaLaboral">
            <a href="javascript:;" onClick="mostrarPestana('div_experienciaLaboral')">
                <span>Experiencia Laboral</span>
            </a>
        </li>
        <?
    }
    if (in_array(20, $privilegios) == true) {
        ?>
        <li style="display:block" id="li_movimientos">
            <a href="javascript:;" onClick="mostrarPestana('div_movimientos')">
                <span>Movimientos</span>
            </a>
        </li>
        <?
    }
    if (in_array(21, $privilegios) == true) {
        ?>
        <li style="display:block" id="li_permisos">
            <a href="javascript:;" onClick="mostrarPestana('div_permisos')">
                <span>Permisos</span>
            </a>
        </li>
        <?
    }
    if (in_array(22, $privilegios) == true) {
        ?>
        <li style="display:block" id="li_vacaciones">
            <a href="javascript:;" onClick="mostrarPestana('div_vacaciones')">
                <span>Vacaciones</span>
            </a>
        </li>
        <?
    }

    if (in_array(23, $privilegios) == true) {
        ?>
        <li style="display:block" id="li_utilidades">
            <a href="javascript:;" onClick="mostrarPestana('div_utilidades')">
                <span>Utilidades/Bonos</span>
            </a>
        </li>
        <?
    }
    if (in_array(24, $privilegios) == true) {
        ?>
        <li style="display:block" id="li_prestacionesSociales">
            <a href="javascript:;" onClick="mostrarPestana('div_prestacionesSociales')">
                <span>Prestaciones Sociales</span>
            </a>
        </li>
        <?
    }
    if (in_array(1206, $privilegios) == true) {
        ?>
        <li style="display:block" id="li_declaracionJurada">
            <a href="javascript:;" onClick="mostrarPestana('div_declaracionJurada')">
                <span>Declaracion Jurada</span>
            </a>
        </li>
        <?
    }
    if (in_array(1205, $privilegios) == true) {
        ?>
        <li style="display:block" id="li_sanciones">
            <a href="javascript:;" onClick="mostrarPestana('div_sanciones')">
                <span>Sanciones</span>
            </a>
        </li>
        <?
    }
    if (in_array(1204, $privilegios) == true) {
        ?>
        <li style="display:block" id="li_reconocimientos">
            <a href="javascript:;" onClick="mostrarPestana('div_reconocimientos')">
                <span>Reconocimientos</span>
            </a>
        </li>
        <?
    }
    if (in_array(949, $privilegios) == true) {
        ?>
        <li style="display:block" id="li_cuentas_bancarias">
            <a href="javascript:;" onClick="mostrarPestana('div_cuentas_bancarias')">
                <span>Cuentas Bancarias</span>
            </a>
        </li>
        <?
    }
    if (in_array(1200, $privilegios) == true) {
        ?>
        <li style="display:block" id="li_ivss">
            <a href="javascript:;" onClick="mostrarPestana('div_ivss')">
                <span>I.V.S.S.</span>
            </a>
        </li>
        <?
    }
    ?>

    </ul>
    <?
}

if ($ejecutar == "cargarPeriodosCesantes") {
    $sql_ingresar = mysql_query("insert into periodos_cedentes_trabajadores(
                                            idtrabajador,
                                            desde,
                                            hasta,
                                            tiempo)VALUES(
                                                        '" . $idtrabajador . "',
                                                        '" . $desde . "',
                                                        '" . $hasta . "',
                                                        '" . $tiempo . "')") or die(mysql_error());
}

if ($ejecutar == "listarPeriodosCesantes") {
    $sql_consulta = mysql_query("select * from periodos_cedentes_trabajadores where idtrabajador = '" . $idtrabajador . "'");

    ?>

    <table border="0" class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
            <thead>
            <tr>
                <td width="20%" class="Browse" align="center">Desde</td>
                <td width="21%" class="Browse" align="center">Hasta</td>
                <td width="47%" class="Browse" align="center">Tiempo</td>
                <td class="Browse" align="center" colspan="2">Acci&oacute;n</td>
            </tr>
            </thead>
            <?while ($bus_consultar = mysql_fetch_array($sql_consulta)) {?>
                <tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="left" class="Browse"><?=$bus_consultar["desde"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["hasta"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["tiempo"]?></td>
                <td width="6%" align="center" class="Browse">
                 <img src="imagenes/modificar.png" style="cursor:pointer" alt='Modificar' title='Modificar' onclick="seleccionarPeriodosCedentes('<?=$bus_consultar["desde"]?>','<?=$bus_consultar["hasta"]?>','<?=$bus_consultar["tiempo"]?>', '<?=$bus_consultar["idperiodos_cedentes_trabajadores"]?>')">
                 </td>
                 <td width="6%" align="center" class="Browse">
                    <img src="imagenes/delete.png" style="cursor:pointer" alt='Eliminar' title='Eliminar' onclick="eliminarPeriodosCedentes('<?=$bus_consultar["idperiodos_cedentes_trabajadores"]?>')">
                 </td>
          </tr>
            <?}?>
        </table>


    <?
}

if ($ejecutar == "eliminarPeriodosCedentes") {
    $sql_eliminar = mysql_query("delete from periodos_cedentes_trabajadores where idperiodos_cedentes_trabajadores = '" . $idperiodos_cedentes_trabajadores . "'");
}

if ($ejecutar == "modificarPeriodosCedentes") {
    $sql_modificar = mysql_query("update periodos_cedentes_trabajadores set
                                                    desde ='" . $desde . "',
                                                    hasta = '" . $hasta . "',
                                                    tiempo = '" . $tiempo . "'
                                                    where
                                                    idperiodos_cedentes_trabajadores = '" . $idperiodos_cedentes_trabajadores . "'");
}

if ($ejecutar == "calcularTiempoPeriodosCedentes") {
    $resultado                  = diferenciaEntreFechas($desde, $hasta, 'todos');
    list($anios, $meses, $dias) = explode("|.|", $resultado);
    if ($anios != 0) {
        $mostrar .= $anios . " Anos ";
    }
    if ($meses != 0) {
        if ($anios != 0 and $dias == 0) {
            $mostrar .= "y " . $meses . " Meses ";
        } else {
            $mostrar .= $meses . " Meses ";
        }

    }
    if ($dias != 0) {
        if (($meses != 0 and $anios != 0) || ($anios != 0 and $meses == 0) || ($anios == 0 and $meses != 0)) {
            $mostrar .= "y " . $dias . " Dias ";
        } else {
            $mostrar .= $dias . " Dias ";
        }

    }
    echo $mostrar;
}

if ($ejecutar == "ingresarPrestaciones") {
    $sql_consulta = mysql_query("select * from tabla_prestaciones where anio = '" . $anio . "'
                                                                    and mes = '" . $mes . "'
                                                                    and idtrabajador = '" . $idtrabajador . "'");
    $num_consulta = mysql_num_rows($sql_consulta);
    if ($num_consulta == 0) {
        $sql_ingresar = mysql_query("insert into tabla_prestaciones
                                                            (anio,
                                                             mes,
                                                             sueldo,
                                                             usuario,
                                                             idtrabajador,
                                                             fechayhora,
                                                             pc)VALUES('" . $anio . "',
                                                                        '" . $mes . "',
                                                                        '" . $sueldo . "',
                                                                        '" . $login . "',
                                                                        '" . $idtrabajador . "',
                                                                        '" . $fh . "',
                                                                        '" . $pc . "')");
    } else {
        echo "existe";
    }
}

if ($ejecutar == "consultarPrestaciones") {

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

    ?>
        <table border="0" class="Browse" cellpadding="0" cellspacing="0" width="100%" align="center">
            <thead>
            <tr>
                <td width="2%" class="Browse" align="center" style="font-size: 8">A&ntilde;o</td>
                <td width="2%" class="Browse" align="center" style="font-size: 8">Mes</td>
                <td width="8%" class="Browse" align="center" style="font-size: 8"
                    title="Ingrese lo devengado por sueldo o salario en el mes"
                    >Sueldo del Mes</td>
                <td width="8%" class="Browse" align="center" style="font-size: 8"
                    title="Ingrese la sumatoria de los conceptos adicionales que son parte de la remuneración mensual"
                    >Otros Complemento</td>
                <td width="2%" class="Browse" align="center" style="font-size: 8"
                    title="Ingrese los días pagados por Bono Vacacional en ese periodo. De esta forma se cálcula la alícuota del Bono Vacacional y se suma a la Remuneración Mensual. En caso de fijar un valor de días de Bono Vacacional (DBV), esta alícuota sera prioritaria aunque indique el valor del Bono Vacacional pagado en ese periodo.&#013&#013La alícuota del bono vacacional es calculada de la siguiente forma: &#013&#013AlicuotaBV = (((Sueldo + Otros Complementos) / 30) x DBV / 360) x 30&#013"
                    >DBV</td>
                <td width="8%" class="Browse" align="center" style="font-size: 8"
                    title="Ingrese el valor del Bono Vacacional pagado en ese periodo.&#013&#013Si el valor de DBV (dias bono vacacional) es mayor a cero (0), no se tomará en cuenta el monto que ingrese en esta celda, sino el monto del cálculo de la Alícuota del Bono Vacacional"
                    >Bono Vacacional</td>
                <td width="2%" class="Browse" align="center" style="font-size: 8"
                    title="Ingrese los días pagados por Bono de Fin de Año o Aguinaldo en ese periodo. De esta forma se cálcula la alícuota del Bono de Fin de Año y se suma a la Remuneración Mensual. En caso de fijar un valor de días de Bono de Fin de Año (DBFA), esta alícuota sera prioritaria aunque indique el valor del Bono de Fin de Año pagado en ese periodo.&#013&#013La alícuota del bono de fin de año es calculada de la siguiente forma: &#013&#013AlicuotaBFA = ((((Sueldo + Otros Complementos) / 30) + AlicuotaBV / 30) x DBFA / 360) x 30&#013"
                    >DBFA</td>
                <td width="8%" class="Browse" align="center" style="font-size: 8"
                    title="Ingrese el valor del Bono de Fin de Año pagado en ese periodo.&#013&#013Si el valor de DBFA (dias bono de fin de año) es mayor a cero (0), no se tomará en cuenta el monto que ingrese en esta celda, sino el monto del cálculo de la Alícuota del Bono de Fin de Año"
                    >Bono Fin de A&ntilde;o</td>
                <td width="8%" class="Browse" align="center" style="font-size: 8">Remuner. Mensual</td>
                <td width="1%" class="Browse" align="center">A</td>
                <td width="1%" class="Browse" align="center">M</td>
                <td width="1%" class="Browse" align="center">Ley</td>
                <td width="1%" class="Browse" align="center">DP</td>
                <td width="1%" class="Browse" align="center">DA</td>
                <td width="6%" class="Browse" align="center" style="font-size: 8">Prest del Mes</td>
                <td width="10%" class="Browse" align="center" style="font-size: 8">Prest Acumula</td>
                <td width="8%" class="Browse" align="center" style="font-size: 8">Tasa de Interes</td>
                <td width="5%" class="Browse" align="center" style="font-size: 8">Interes Prest.</td>
                <td width="10%" class="Browse" align="center" style="font-size: 8">Interes Acumulado</td>
                <td width="12%" class="Browse" align="center" style="font-size: 8">Prest + Int Acumulad</td>
                <td class="Browse" align="center" colspan='2' style="font-size: 8">Acci&oacute;n</td>
            </tr>
            </thead>
         <?

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

    /*$sql_configuracion = mysql_query("select * from configuracion_rrhh");
    $bus_configuracion = mysql_fetch_array($sql_configuracion);

    $mes_inicio_prentaciones = $bus_configuracion["meses_inicio_pago_prestaciones"];
    $dias_prestaciones = $bus_configuracion["dias_prestaciones_mes"];*/

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

    $sql_consulta = mysql_query("select * from tabla_prestaciones
                                        where idtrabajador = '" . $idtrabajador . "' order by anio, mes");
    list($anioIngreso, $mesIngreso, $diaIngreso) = explode("-", $fecha_ingreso);

    //BUCLE PARA IR REVISANDO CADA AÑO Y MES DE LA TABLA DE PRESTACIONES
    while ($bus_consulta = mysql_fetch_array($sql_consulta)) {

        $dias_prestaciones = 0;
        $dias_adicionales  = 0;

        $resultado_fecha = diferenciaEntreDosFechas($fecha_ingreso, $bus_consulta["anio"] . "-" . $bus_consulta["mes"] . "-01");
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
                        //echo $dias_prestaciones;
                        $cuenta_meses = 0;
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
        //FIN WHILE LEYES APLICADAS

        if ($bus_consulta["anio"] != $anio_totalizar and $anio_totalizar > 0 and $anioRegistro >= 0 and $mesRegistro >= 0) {

            ?>
                <tr  bordercolor="#000000" bgcolor='#A9D0F5'>
                    <td align="right" class="Browse" colspan='14'>TOTALES DEL AÑO: <?=$anio_totalizar?></td>
                    <td align="right" class="Browse"><?=number_format($prestaciones_anuales, 2, ",", ".")?></td>
                    <td align="right" class="Browse" style="color:#F00"><?=number_format($adelantos_prestaciones_anuales, 2, ",", ".")?></td>
                    <td align="right" class="Browse">&nbsp;</td>
                    <td align="right" class="Browse"><?=number_format($intereses_anuales, 2, ",", ".")?></td>
                    <td align="right" class="Browse" style="color:#F00"><?=number_format($adelantos_intereses_anuales, 2, ",", ".")?></td>
                    <td align="right" class="Browse">&nbsp;

                    <?php //=number_format(($prestaciones_anuales + $intereses_anuales - $adelantos_prestaciones_anuales - $adelantos_intereses_anuales), 2, ",", ".")?></td>
                    <td width="4%" align="center" class="Browse">&nbsp;</td>
                </tr>

               <?
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

            //$prestacion_interes_acumulado = $prestacion_interes_acumulado - ($adelanto_interes + $adelanto_prestaciones);

        } else {
            $k++;
            $bandera++;
        }

        ?>
            <tr  bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="center" class="Browse" style="font-size: 9"><?=$bus_consulta["anio"]?></td>
                <td align="left" class="Browse" style="font-size: 9"><?="(" . $bus_consulta["mes"] . ")&nbsp;" . $meses[$bus_consulta["mes"]]?></td>
                <td align="right" class="Browse">
                    <input  type="text"
                            size="12"
                            id="sueldo_prestaciones_modificar_<?=$bus_consulta["idtabla_prestaciones"]?>"
                            value="<?=number_format($bus_consulta["sueldo"], 2, ",", ".")?>"
                            onclick="this.select()"
                            style="text-align:right; font-size: 9;"
                            onblur="guardarValorSueldo('sueldo_prestaciones_modificar_<?=$bus_consulta["idtabla_prestaciones"]?>', '<?=$bus_consulta["idtabla_prestaciones"]?>', this.value)"
                        title="Ingrese lo devengado por sueldo o salario en el mes"
                    >
                </td>
                <td align="right" class="Browse">
                    <input  type="text"
                            size="12"
                            id="complementos_prestaciones_modificar_<?=$bus_consulta["idtabla_prestaciones"]?>"
                            value="<?=number_format($bus_consulta["otros_complementos"], 2, ",", ".")?>"
                            onclick="this.select()"
                            style="text-align:right"
                            onblur="guardarValorOtros('complementos_prestaciones_modificar_<?=$bus_consulta["idtabla_prestaciones"]?>', '<?=$bus_consulta["idtabla_prestaciones"]?>', this.value)"
                        title="Ingrese la sumatoria de los conceptos adicionales que son parte de la remuneración mensual"
                    >
                </td>
                <td align="right" class="Browse">
                    <input  type="text"
                            size="6"
                            id="dias_bono_vacacional_prestaciones_modificar_<?=$bus_consulta["idtabla_prestaciones"]?>"
                            value="<?=number_format($bus_consulta["dias_bono_vacacional"], 2, ",", ".")?>"
                            onclick="this.select()"
                            style="text-align:right"
                            onblur="guardarValorDiasBonoVacacional('dias_bono_vacacional_prestaciones_modificar_<?=$bus_consulta["idtabla_prestaciones"]?>', '<?=$bus_consulta["idtabla_prestaciones"]?>', this.value)"
                        title="Ingrese los días pagados por Bono Vacacional en ese periodo. De esta forma se cálcula la alícuota del Bono Vacacional y se suma a la Remuneración Mensual. En caso de fijar un valor de días de Bono Vacacional (DBV), esta alícuota sera prioritaria aunque indique el valor del Bono Vacacional pagado en ese periodo.&#013&#013La alícuota del bono vacacional es calculada de la siguiente forma: &#013&#013AlicuotaBV = (((Sueldo + Otros Complementos) / 30) x DBV / 360) x 30&#013"
                    >
                </td>
                <td align="right" class="Browse">
                    <input  type="text"
                            size="12"
                            id="bono_vacacional_prestaciones_modificar_<?=$bus_consulta["idtabla_prestaciones"]?>"
                            value="<?=number_format($bus_consulta["bono_vacacional"], 2, ",", ".")?>"
                            onclick="this.select()"
                            style="text-align:right"
                            onblur="guardarValorBonoVacacional('bono_vacacional_prestaciones_modificar_<?=$bus_consulta["idtabla_prestaciones"]?>', '<?=$bus_consulta["idtabla_prestaciones"]?>', this.value)"
                        title="Ingrese el valor del Bono Vacacional pagado en ese periodo.&#013&#013Si el valor de DBV (dias bono vacacional) es mayor a cero (0), no se tomará en cuenta el monto que ingrese en esta celda, sino el monto del cálculo de la Alícuota del Bono Vacacional"
                    >
                </td>
                <td align="right" class="Browse">
                    <input  type="text"
                            size="6"
                            id="dias_bono_fin_anio_prestaciones_modificar_<?=$bus_consulta["idtabla_prestaciones"]?>"
                            value="<?=number_format($bus_consulta["dias_bono_fin_anio"], 2, ",", ".")?>"
                            onclick="this.select()"
                            style="text-align:right"
                            onblur="guardarValorDiasBonoFinAnio('dias_bono_fin_anio_prestaciones_modificar_<?=$bus_consulta["idtabla_prestaciones"]?>', '<?=$bus_consulta["idtabla_prestaciones"]?>', this.value)"
                        title="Ingrese los días pagados por Bono de Fin de Año o Aguinaldo en ese periodo. De esta forma se cálcula la alícuota del Bono de Fin de Año y se suma a la Remuneración Mensual. En caso de fijar un valor de días de Bono de Fin de Año (DBFA), esta alícuota sera prioritaria aunque indique el valor del Bono de Fin de Año pagado en ese periodo.&#013&#013La alícuota del bono de fin de año es calculada de la siguiente forma: &#013&#013AlicuotaBFA = ((((Sueldo + Otros Complementos) / 30) + AlicuotaBV / 30) x DBFA / 360) x 30&#013"
                    >
                </td>
                <td align="right" class="Browse">
                    <input  type="text"
                            size="12"
                            id="bono_fin_anio_prestaciones_modificar_<?=$bus_consulta["idtabla_prestaciones"]?>"
                            value="<?=number_format($bus_consulta["bono_fin_anio"], 2, ",", ".")?>"
                            onclick="this.select()"
                            style="text-align:right"
                            onblur="guardarValorBonoFinAnio('bono_fin_anio_prestaciones_modificar_<?=$bus_consulta["idtabla_prestaciones"]?>', '<?=$bus_consulta["idtabla_prestaciones"]?>', this.value)"
                    title="Ingrese el valor del Bono de Fin de Año pagado en ese periodo.&#013&#013Si el valor de DBFA (dias bono de fin de año) es mayor a cero (0), no se tomará en cuenta el monto que ingrese en esta celda, sino el monto del cálculo de la Alícuota del Bono de Fin de Año"
                    >
                </td>
                <td align="right" class="Browse"><?=number_format($ingreso_mensual, 2, ",", ".")?></td>
                <td align="center" class="Browse"><?if ($anioRegistro == '') {echo '0';} else {echo $anioRegistro;}?></td>
                <td align="center" class="Browse"><?if ($mesRegistro == '') {echo '0';} else {echo $mesRegistro;}?></td>
                <td align="left" class="Browse" style="font-size: 7"><?=$ley?></td>
                <td align="center" class="Browse"><?if ($mostrar == true) {echo number_format($dias_prestaciones, 0);} else {echo "0";}?></td>
                 <td align="center" class="Browse"><?if ($mostrar == true) {echo $dias_adicionales;} else {echo "0";}?></td>
                <td align="right" class="Browse"><?=number_format($prestaciones_del_mes, 2, ",", ".")?></td>
                <td align="right" class="Browse"><?=number_format($prestaciones_acumuladas, 2, ",", ".")?></td>
                <td align="right" class="Browse"><?=number_format($bus_tasas["interes"], 2, ",", ".")?></td>
                <td align="right" class="Browse"><?=number_format($interes_prestaciones, 2, ",", ".")?></td>
                <td align="right" class="Browse"><?=number_format($interes_acumulado, 2, ",", ".")?></td>
                <td align="right" class="Browse"><?=number_format($prestacion_interes_acumulado, 2, ",", ".")?></td>
                <td width="4%" align="center" class="Browse" colspan="2">
                <?
                if ($num_adelanto == 0) {
                    ?>
                        <img src="imagenes/add.png" style="cursor:pointer" onclick="document.getElementById('tr_adelanto_<?=$bus_consulta["idtabla_prestaciones"]?>').style.visibility = 'visible'">
                        <?
                } else {?> &nbsp; <?}
                ?>

                        </td>
                    </tr>
                    <?

                if ($num_adelanto == 0) {

            ?>
            <tr bordercolor="#000000" bgcolor='#FFFFCC' onMouseOver="setRowColor(this, 0, 'over', '#FFFFCC', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFFCC', '#EAFFEA', '#FFFFAA')" id="tr_adelanto_<?=$bus_consulta["idtabla_prestaciones"]?>" style="visibility:collapse">
                <td align="center" class="Browse" colspan="15" style="font-size:14px; font-weight:bold">ADELANTO</td>
                <td align="right" class="Browse"><input type="text" style="text-align:right" id="adelanto_prestaciones_<?=$bus_consulta["idtabla_prestaciones"]?>" size="12"></td>
                <td align="right" class="Browse" colspan='2'>&nbsp;</td>
                <td align="right" class="Browse"><input type="text" style="text-align:right" id="adelanto_interes_<?=$bus_consulta["idtabla_prestaciones"]?>" size="12"></td>
                <td width="3%" align="center" colspan='2' class="Browse"><input type="button" id="boton_actualizar_adelanto_<?=$bus_consulta["idtabla_prestaciones"]?>" name="boton_actualizar_adelanto" value="Ingresar" class="button" onclick="ingresarAdelanto('<?=$bus_consulta["idtabla_prestaciones"]?>', document.getElementById('adelanto_prestaciones_<?=$bus_consulta["idtabla_prestaciones"]?>').value, document.getElementById('adelanto_interes_<?=$bus_consulta["idtabla_prestaciones"]?>').value)"></td>
            </tr>
            <?
        } else {
            ?>
                <tr bordercolor="#000000" bgcolor='#FFFFCC' onMouseOver="setRowColor(this, 0, 'over', '#FFFFCC', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFFCC', '#EAFFEA', '#FFFFAA')" id="tr_adelanto_<?=$bus_consulta["idtabla_prestaciones"]?>" style="font-weight:bold">
                    <td align="center" class="Browse" colspan="15" style="font-size:14px; font-weight:bold">ADELANTO</td>
                    <td align="right" class="Browse" style="color:#F00"><?=number_format($bus_adelanto["monto_prestaciones"], 2, ",", ".")?></td>
                    <td align="right" class="Browse" colspan='2'>&nbsp;</td>
                    <td align="right" class="Browse" style="color:#F00"><?=number_format($bus_adelanto["monto_interes"], 2, ",", ".")?></td>
                    <td align="right" class="Browse" style="color:#F00">&nbsp;</td>
                    <?//number_format(($bus_adelanto["monto_prestaciones"]+$bus_adelanto["monto_interes"]),2,",",".")?>
                    <td width="3%" align="center" class="Browse" colspan="2"><img src="imagenes/delete.png" style="cursor:pointer" onclick="eliminarAdelanto('<?=$bus_adelanto["idtabla_adelantos"]?>')"></td>
                </tr>
            <?
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
    }
    ?>
         </table>
         <?
}

if ($ejecutar == "ingresarAdelanto") {
    $sql_ingresar = mysql_query("insert into tabla_adelantos(idtabla_prestaciones,
                                                             monto_prestaciones,
                                                             monto_interes)VALUES('" . $idtabla_prestaciones . "',
                                                                                '" . $adelanto_prestaciones . "',
                                                                                '" . $adelanto_interes . "')");
}

if ($ejecutar == "eliminarAdelanto") {
    $sql_eliminar = mysql_query("delete from tabla_adelantos where idtabla_adelantos = '" . $idtabla_adelanto . "'");
}

if ($ejecutar == "consultarSelectAniosPrestaciones") {
    $i         = $anio;
    $mesActual = (int) $mes;
    for ($i; $i <= date("Y"); $i++) {
        $m = 1;
        for ($m; $m <= 12; $m++) {
            if ($m < $mesActual and $i <= $anio) {

            } else {
                if ($i == date("Y") and $m > date("m")) {

                } else {
                    if ($m < 10) {$mesIngresar = '0' . $m;} else { $mesIngresar = $m;}
                    $sql_consulta = mysql_query("select * from tabla_prestaciones where anio = '" . $i . "'
                                                                                and mes = '" . $mesIngresar . "'
                                                                                and idtrabajador = '" . $idtrabajador . "'");
                    $num_consulta = mysql_num_rows($sql_consulta);
                    if ($num_consulta == 0) {

                        $sql_ingresar = mysql_query("insert into tabla_prestaciones
                                                                            (anio,
                                                                             mes,
                                                                             usuario,
                                                                             idtrabajador,
                                                                             fechayhora,
                                                                             pc)VALUES('" . $i . "',
                                                                                        '" . $mesIngresar . "',
                                                                                        '" . $login . "',
                                                                                        '" . $idtrabajador . "',
                                                                                        '" . $fh . "',
                                                                                        '" . $pc . "')");

                    }
                }
            }
        }
    }
}

if ($ejecutar == "eliminarPrestaciones") {
    $sql_eliminar = mysql_query("delete from tabla_prestaciones where idtabla_prestaciones = '" . $idtabla_prestaciones . "'");
}

if ($ejecutar == "actualizarSueldoPrestaciones") {
    $sql_actualizar = mysql_query("update tabla_prestaciones set sueldo = '" . $sueldo . "',
                                                                otros_complementos = '" . $otros . "',
                                                                bono_vacacional = '" . $bono_vacacional . "',
                                                                bono_fin_anio = '" . $bono_fin_anio . "'
                                        where idtabla_prestaciones = '" . $idtabla_prestaciones . "'");
}

if ($ejecutar == "guardarValorSueldo") {
    $sql_actualizar = mysql_query("update tabla_prestaciones set sueldo = '" . $sueldo . "'
                                        where idtabla_prestaciones = '" . $idtabla_prestaciones . "'");
}

if ($ejecutar == "guardarValorOtros") {
    $sql_actualizar = mysql_query("update tabla_prestaciones set otros_complementos = '" . $otros . "'
                                        where idtabla_prestaciones = '" . $idtabla_prestaciones . "'");
}

if ($ejecutar == "guardarValorDiasBonoVacacional") {
    $sql_tabla_prestaciones = mysql_query("SELECT * FROM tabla_prestaciones WHERE idtabla_prestaciones = '" . $idtabla_prestaciones . "'");
    $reg_tp                 = mysql_fetch_array($sql_tabla_prestaciones);
    $sueldo             = $reg_tp["sueldo"];
    $otros_complementos = $reg_tp["otros_complementos"];
    $dias_fin_anio      = $reg_tp['dias_bono_fin_anio'];
    $bono_fin_anio      = $reg_tp["bono_fin_anio"];
    $dias_vacacional    = $dias_bv;

    $alicuota_bv = 0;
    if($dias_vacacional > 0){
        $mensual_bono_vacacional = ((($sueldo + $otros_complementos) / 30) * $dias_vacacional / 360) * 30;
        $alicuota_bv = $mensual_bono_vacacional;
    }else{
        $mensual_bono_vacacional = 0;
    }
    if($dias_fin_anio > 0){
        $alicuota_bfa = (((($sueldo + $otros_complementos) / 30) + ($alicuota_bv / 30)) * $dias_fin_anio / 360) * 30;
    }else{
        $alicuota_bfa = 0;
    }

    $sql_actualizar = mysql_query("UPDATE tabla_prestaciones
                                        SET
                                            dias_bono_vacacional = '" . $dias_bv . "',
                                            bono_vacacional = '".$mensual_bono_vacacional."',
                                            bono_fin_anio = '".$alicuota_bfa."'
                                        WHERE
                                            idtabla_prestaciones = '" . $idtabla_prestaciones . "'");
}

if ($ejecutar == "guardarValorBonoVacacional") {
    $sql_actualizar = mysql_query("update tabla_prestaciones set bono_vacacional = '" . $bono_vacacional . "'
                                        where idtabla_prestaciones = '" . $idtabla_prestaciones . "'");
}

if ($ejecutar == "guardarValorDiasBonoFinAnio") {
    $sql_tabla_prestaciones = mysql_query("SELECT * FROM tabla_prestaciones WHERE idtabla_prestaciones = '" . $idtabla_prestaciones . "'");
    $reg_tp                 = mysql_fetch_array($sql_tabla_prestaciones);
    $sueldo             = $reg_tp["sueldo"];
    $otros_complementos = $reg_tp["otros_complementos"];
    $dias_vacacional    = $reg_tp['dias_bono_vacacional'];
    $bono_fin_anio      = $reg_tp["bono_fin_anio"];
    $dias_fin_anio      = $dias_bfa;

    $alicuota_bv = 0;
    if($dias_vacacional > 0){
        $mensual_bono_vacacional = ((($sueldo + $otros_complementos) / 30) * $dias_vacacional / 360) * 30;
        $alicuota_bv = $mensual_bono_vacacional;
    }else{
        $mensual_bono_vacacional = 0;
    }
    if($dias_fin_anio > 0){
        $alicuota_bfa = (((($sueldo + $otros_complementos) / 30) + ($alicuota_bv / 30)) * $dias_fin_anio / 360) * 30;
    }else{
        $alicuota_bfa = 0;
    }
    $sql_actualizar = mysql_query("UPDATE tabla_prestaciones
                                        SET
                                            dias_bono_fin_anio = '" . $dias_bfa . "',
                                            bono_vacacional = '".$mensual_bono_vacacional."',
                                            bono_fin_anio = '".$alicuota_bfa."'
                                        WHERE idtabla_prestaciones = '" . $idtabla_prestaciones . "'");
}

if ($ejecutar == "guardarValorBonoFinAnio") {
    $sql_actualizar = mysql_query("update tabla_prestaciones set bono_fin_anio = '" . $bono_fin_anio . "'
                                        where idtabla_prestaciones = '" . $idtabla_prestaciones . "'");
}

// ********************************************** VACACIONES VENCIDAS ****************************************************

if ($ejecutar == "ingresarVacacionesVencidas") {
    $sql_consulta = mysql_query("select * from tabla_vacaciones where tipo = '" . $tipo . "'
                                                                    and periodo = '" . $periodo . "'
                                                                    and idtrabajador = '" . $idtrabajador . "'");
    $num_consulta = mysql_num_rows($sql_consulta);
    if ($num_consulta == 0) {
        $sql_ingresar = mysql_query("insert into tabla_vacaciones
                                                            (tipo,
                                                             periodo,
                                                             dias,
                                                             sueldo,
                                                             usuario,
                                                             idtrabajador,
                                                             fechayhora,
                                                             pc)VALUES('" . $tipo . "',
                                                                        '" . $periodo . "',
                                                                        '" . $dias . "',
                                                                        '" . $sueldo . "',
                                                                        '" . $login . "',
                                                                        '" . $idtrabajador . "',
                                                                        '" . $fh . "',
                                                                        '" . $pc . "')");
    } else {
        echo "existe";
    }
}

if ($ejecutar == "consultarVacaciones") {
    $sql_consulta = mysql_query("select * from tabla_vacaciones where idtrabajador = '" . $idtrabajador . "' order by periodo");

    ?>
        <table border="0" class="Browse" cellpadding="0" cellspacing="0" width="98%" align="center">
            <thead>
            <tr>
                <td width="18%" class="Browse" align="center">Tipo</td>
                <td width="27%" class="Browse" align="center">Periodo</td>
                <td width="9%" class="Browse" align="center">Dias</td>
                <td width="24%" class="Browse" align="center">Sueldo del Mes</td>
                <td width="17%" class="Browse" align="center">Total Bono</td>
                <td class="Browse" align="center">Acci&oacute;n</td>
            </tr>
            </thead>
         <?
    while ($bus_consulta = mysql_fetch_array($sql_consulta)) {
        $bono = ($bus_consulta["sueldo"] / 30) * $bus_consulta["dias"];
        ?>
            <tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="left" class="Browse"><?=ucwords($bus_consulta["tipo"])?></td>
                <td align="center" class="Browse"><?=$bus_consulta["periodo"]?></td>
                <td align="left" class="Browse"><?=$bus_consulta["dias"]?></td>
                 <td align="right" class="Browse"><?=number_format($bus_consulta["sueldo"], 2, ",", ".")?></td>
                  <td align="right" class="Browse"><?=number_format($bono, 2, ",", ".")?></td>
                <td width="5%" align="center" class="Browse"><img src="imagenes/delete.png" style="cursor:pointer" onclick="eliminarVacaciones('<?=$bus_consulta["idtabla_vacaciones"]?>')"></td>
            </tr>
            <?
    }
    ?>
         </table>
         <?
}

if ($ejecutar == "consultarSelectAniosVacaciones") {
    if ($anio < 1997) {
        $i = 1997;
    } else {
        $i = $anio;
    }
    ?>
    <select id="periodo_vacaciones" name="periodo_vacaciones">
    <option value="0">.:: Seleccione ::.</option>
    <?
    for ($i; $i < date("Y"); $i++) {
        ?>
            <option><?=$i . "-" . ($i + 1)?></option>
        <?
    }
    ?>
    </select>
    <?
}

if ($ejecutar == "eliminarVacaciones") {
    $sql_eliminar = mysql_query("delete from tabla_vacaciones where idtabla_vacaciones = '" . $idvacaciones . "'");
}

// ********************************************** AGUINALDOS ****************************************************

if ($ejecutar == "ingresarAguinaldos") {
    $sql_consulta = mysql_query("select * from tabla_aguinaldos where tipo = '" . $tipo . "'
                                                                    and periodo = '" . $periodo . "'
                                                                    and idtrabajador = '" . $idtrabajador . "'");
    $num_consulta = mysql_num_rows($sql_consulta);
    if ($num_consulta == 0) {
        $sql_ingresar = mysql_query("insert into tabla_aguinaldos
                                                            (tipo,
                                                             periodo,
                                                             dias,
                                                             sueldo,
                                                             usuario,
                                                             idtrabajador,
                                                             fechayhora,
                                                             pc)VALUES('" . $tipo . "',
                                                                        '" . $periodo . "',
                                                                        '" . $dias . "',
                                                                        '" . $sueldo . "',
                                                                        '" . $login . "',
                                                                        '" . $idtrabajador . "',
                                                                        '" . $fh . "',
                                                                        '" . $pc . "')");
    } else {
        echo "existe";
    }
}

if ($ejecutar == "consultarAguinaldos") {
    $sql_consulta = mysql_query("select * from tabla_aguinaldos where idtrabajador = '" . $idtrabajador . "' order by periodo");

    ?>
        <table border="0" class="Browse" cellpadding="0" cellspacing="0" width="98%" align="center">
            <thead>
            <tr>
                <td width="18%" class="Browse" align="center">Tipo</td>
                <td width="27%" class="Browse" align="center">Periodo</td>
                <td width="9%" class="Browse" align="center">Dias</td>
                <td width="24%" class="Browse" align="center">Sueldo del Mes</td>
                <td width="17%" class="Browse" align="center">Total Bono</td>
                <td class="Browse" align="center">Acci&oacute;n</td>
            </tr>
            </thead>
         <?
    while ($bus_consulta = mysql_fetch_array($sql_consulta)) {
        $bono = ($bus_consulta["sueldo"] / 30) * $bus_consulta["dias"];
        ?>
            <tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="left" class="Browse"><?=ucwords($bus_consulta["tipo"])?></td>
                <td align="center" class="Browse"><?=$bus_consulta["periodo"]?></td>
                <td align="left" class="Browse"><?=$bus_consulta["dias"]?></td>
                 <td align="right" class="Browse"><?=number_format($bus_consulta["sueldo"], 2, ",", ".")?></td>
                  <td align="right" class="Browse"><?=number_format($bono, 2, ",", ".")?></td>
                <td width="5%" align="center" class="Browse"><img src="imagenes/delete.png" style="cursor:pointer" onclick="eliminarAguinaldos('<?=$bus_consulta["idtabla_aguinaldos"]?>')"></td>
            </tr>
            <?
    }
    ?>
         </table>
         <?
}

if ($ejecutar == "consultarSelectAniosAguinaldos") {
    if ($anio < 1997) {
        $i = 1997;
    } else {
        $i = $anio;
    }
    ?>
    <select id="periodo_aguinaldos" name="periodo_aguinaldos">
    <option value="0">.:: Seleccione ::.</option>
    <?
    for ($i; $i < date("Y"); $i++) {
        ?>
            <option><?=$i . "-" . ($i + 1)?></option>
        <?
    }
    ?>
    </select>
    <?
}

if ($ejecutar == "eliminarAguinaldos") {
    $sql_eliminar = mysql_query("delete from tabla_aguinaldos where idtabla_aguinaldos = '" . $idaguinaldos . "'");
}

// ********************************************** DEDUCCIONES ****************************************************

if ($ejecutar == "ingresarDeducciones") {
    $sql_consulta = mysql_query("select * from tabla_deducciones where tipo = '" . $tipo . "'
                                                                    and periodo = '" . $periodo . "'
                                                                    and idtrabajador = '" . $idtrabajador . "'");
    $num_consulta = mysql_num_rows($sql_consulta);
    if ($num_consulta == 0) {
        $sql_ingresar = mysql_query("insert into tabla_deducciones
                                                            (tipo,
                                                             periodo,
                                                             monto,
                                                             usuario,
                                                             idtrabajador,
                                                             fechayhora,
                                                             pc)VALUES('" . $tipo . "',
                                                                        '" . $periodo . "',
                                                                        '" . $monto . "',
                                                                        '" . $login . "',
                                                                        '" . $idtrabajador . "',
                                                                        '" . $fh . "',
                                                                        '" . $pc . "')");
    } else {
        echo "existe";
    }
}

if ($ejecutar == "consultarDeducciones") {
    $sql_consulta = mysql_query("select * from tabla_deducciones where idtrabajador = '" . $idtrabajador . "' order by periodo");

    ?>
        <table border="0" class="Browse" cellpadding="0" cellspacing="0" width="98%" align="center">
            <thead>
            <tr>
                <td width="19%" class="Browse" align="center">Tipo</td>
                <td width="26%" class="Browse" align="center">Periodo</td>
                <td width="27%" class="Browse" align="center">Monto</td>
                <td class="Browse" align="center">Acci&oacute;n</td>
            </tr>
            </thead>
         <?
    while ($bus_consulta = mysql_fetch_array($sql_consulta)) {
        ?>
            <tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="left" class="Browse"><?=ucwords($bus_consulta["tipo"])?></td>
                <td align="center" class="Browse"><?=$bus_consulta["periodo"]?></td>
                 <td align="right" class="Browse"><?=number_format($bus_consulta["monto"], 2, ",", ".")?></td>
                <td width="5%" align="center" class="Browse"><img src="imagenes/delete.png" style="cursor:pointer" onclick="eliminarDeducciones('<?=$bus_consulta["idtabla_deducciones"]?>')"></td>
            </tr>
            <?
    }
    ?>
         </table>
         <?
}

if ($ejecutar == "consultarSelectAniosDeducciones") {
    if ($anio < 1997) {
        $i = 1997;
    } else {
        $i = $anio;
    }
    ?>
    <select id="periodo_deducciones" name="periodo_deducciones">
    <option value="0">.:: Seleccione ::.</option>
    <?
    for ($i; $i < date("Y"); $i++) {
        ?>
            <option><?=$i . "-" . ($i + 1)?></option>
        <?
    }
    ?>
    </select>
    <?
}

if ($ejecutar == "eliminarDeducciones") {
    $sql_eliminar = mysql_query("delete from tabla_deducciones where idtabla_deducciones = '" . $iddeducciones . "'");
}

if ($ejecutar == "consultarTotalesGeneralesPrestaciones") {

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

    //list($anioIngreso, $mesIngreso, $diaIngreso) = explode("-", $fecha_ingreso);

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

    /*$sql_configuracion = mysql_query("select * from configuracion_rrhh");
    $bus_configuracion = mysql_fetch_array($sql_configuracion);

    $mes_inicio_prentaciones = $bus_configuracion["meses_inicio_pago_prestaciones"];
    $dias_prestaciones = $bus_configuracion["dias_prestaciones_mes"];*/

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

        $resultado_fecha = diferenciaEntreDosFechas($fecha_ingreso, $bus_consulta["anio"] . "-" . $bus_consulta["mes"] . "-01");
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

            //$mes_inicio_prentaciones = $bus_leyes["mes_inicial_abono"];
            //ECHO " AÑO desde: ".$anio_desde." AÑO TABLA: ".$bus_consulta["anio"].'<BR>';
            //ECHO " MES desde: ".$mes_desde." MES TABLA: ".$bus_consulta["mes"].'<BR>';

            //echo $cuenta_meses."   ";
            //ECHO " MES REGISTRO ".$mesRegistro. " MES INICIA ABONO ".$bus_leyes["mes_inicial_abono"].'<BR>';

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
    }

    $total_interes_prestaciones = $prestacion_interes_acumulado - $prestaciones_acumuladas;

    /*$sql_vacaciones = mysql_query("select * from tabla_vacaciones where idtrabajador = '" . $idtrabajador . "'");
    while ($bus_vacaciones = mysql_fetch_array($sql_vacaciones)) {
        $total_vacaciones += ($bus_vacaciones["sueldo"] / 30) * $bus_vacaciones["dias"];
    }

    $sql_aguinaldos = mysql_query("select * from tabla_aguinaldos where idtrabajador = '" . $idtrabajador . "'");
    while ($bus_aguinaldos = mysql_fetch_array($sql_aguinaldos)) {
        $total_aguinaldos += ($bus_aguinaldos["sueldo"] / 30) * $bus_aguinaldos["dias"];
    }

    $sql_deducciones = mysql_query("select * from tabla_deducciones where idtrabajador = '" . $idtrabajador . "'");
    while ($bus_deducciones = mysql_fetch_array($sql_deducciones)) {
        $total_deducciones += $bus_deducciones["monto"];
    }

    $total_a_pagar = ($prestacion_interes_acumulado + $total_vacaciones + $total_aguinaldos) - $total_deducciones;
    */
    $total_a_pagar = $prestacion_interes_acumulado;
    echo number_format($prestaciones_acumuladas, 2, ",", ".") . "|.|" .
    number_format($total_interes_prestaciones, 2, ",", ".") . "|.|" .
    number_format($prestacion_interes_acumulado, 2, ",", ".") . "|.|" .
    //number_format($total_vacaciones, 2, ",", ".") . "|.|" .
    //number_format($total_aguinaldos, 2, ",", ".") . "|.|" .
    //number_format($total_deducciones, 2, ",", ".") . "|.|" .
    number_format($total_a_pagar, 2, ",", ".");

}

error_reporting(E_ALL);
ini_set('display_errors', '1');
if ($ejecutar == "importarPrestaciones") {
    $nombreArchivo = $archivo_importar;
    switch ($tipo_importar) {

        case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            require_once 'PHPExcel/Classes/PHPExcel.php';
            require_once('PHPExcel/Classes/PHPExcel/Reader/Excel2007.php');

            $db = new conexion();
            $stmt = $db->stmt_init();

            $objReader = new PHPExcel_Reader_Excel2007();
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($nombreArchivo);
            $filas=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
            $cedula = mysql_real_escape_string(htmlspecialchars($objPHPExcel->getActiveSheet()->getCell('A2')));
            $sql_trabajador = mysql_query("select * from trabajador where   idtrabajador = '".$idtrabajador."'
                                                                            and cedula = '" . $cedula . "'");
            $bus_trabajador = mysql_fetch_array($sql_trabajador);
            $num_trabajador = mysql_num_rows($sql_trabajador);

            if ($num_trabajador > 0) {

                for ($i = 2; $i <= ($filas); $i++) {

                    $idtrabajador = $bus_trabajador["idtrabajador"];
                    $anio = mysql_real_escape_string(htmlspecialchars($objPHPExcel->getActiveSheet()->getCell('B'.$i)));
                    $mes  = mysql_real_escape_string(htmlspecialchars($objPHPExcel->getActiveSheet()->getCell('C'.$i)));

                    $sql_cuenta_trabajador = mysql_query("select * from tabla_prestaciones
                                     where idtrabajador = '" . $bus_trabajador["idtrabajador"] . "'
                                     and anio = '" . $anio . "'
                                     and mes = '" . $mes . "'") or die(mysql_error());

                    $bus_cuenta_trabajador = mysql_fetch_array($sql_cuenta_trabajador);
                    $num_cuenta_trabajador = mysql_num_rows($sql_cuenta_trabajador);

                    $sueldo                = mysql_real_escape_string(htmlspecialchars($objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue()));
                    $otros_complementos    = mysql_real_escape_string(htmlspecialchars($objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue()));
                    $dias_vacacional       = mysql_real_escape_string(htmlspecialchars($objPHPExcel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue()));
                    $bono_vacacional       = mysql_real_escape_string(htmlspecialchars($objPHPExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue()));
                    $dias_fin_anio         = mysql_real_escape_string(htmlspecialchars($objPHPExcel->getActiveSheet()->getCell('H'.$i)->getCalculatedValue()));
                    $bono_fin_anio         = mysql_real_escape_string(htmlspecialchars($objPHPExcel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue()));
                    $adelanto_prestaciones = mysql_real_escape_string(htmlspecialchars($objPHPExcel->getActiveSheet()->getCell('J'.$i)->getCalculatedValue()));
                    $adelanto_intereses    = mysql_real_escape_string(htmlspecialchars($objPHPExcel->getActiveSheet()->getCell('K'.$i)->getCalculatedValue()));

                    if ($num_cuenta_trabajador <= 0) {
                        $alicuota_bv = 0;
                        if($dias_vacacional > 0){
                            $mensual_bono_vacacional = ((($sueldo + $otros_complementos) / 30) * $dias_vacacional / 360) * 30;
                            $alicuota_bv = $mensual_bono_vacacional;
                        }else{
                            $mensual_bono_vacacional = $bono_vacacional;
                        }
                        if($dias_fin_anio > 0){
                            $alicuota_bfa = (((($sueldo + $otros_complementos) / 30) + ($alicuota_bv / 30)) * $dias_fin_anio / 360) * 30;
                        }else{
                            $alicuota_bfa = $bono_fin_anio;
                        }
                        $sql_cuenta = mysql_query("insert into tabla_prestaciones(
                                                                idtrabajador,
                                                                anio,
                                                                mes,
                                                                sueldo,
                                                                dias_bono_vacacional,
                                                                bono_vacacional,
                                                                dias_bono_fin_anio,
                                                                bono_fin_anio,
                                                                otros_complementos)VALUES(
                                                                '" . $bus_trabajador["idtrabajador"] . "',
                                                                '" . $anio . "',
                                                                '" . $mes . "',
                                                                '" . $sueldo . "',
                                                                '" . $dias_vacacional . "',
                                                                '" . $mensual_bono_vacacional . "',
                                                                '" . $dias_fin_anio . "',
                                                                '" . $alicuota_bfa . "',
                                                                '" . $otros_complementos . "')") or die(mysql_error());
                        $idtabla_prestaciones = mysql_insert_id();
                    } else {

                        $alicuota_bv = 0;
                        if($dias_vacacional > 0){
                            $mensual_bono_vacacional = ((($sueldo + $otros_complementos) / 30) * $dias_vacacional / 360) * 30;
                            $alicuota_bv = $mensual_bono_vacacional;
                        }else{
                            $mensual_bono_vacacional = $bono_vacacional;
                        }
                        if($dias_fin_anio > 0){
                            $alicuota_bfa = (((($sueldo + $otros_complementos) / 30) + ($alicuota_bv / 30)) * $dias_fin_anio / 360) * 30;
                        }else{
                            $alicuota_bfa = $bono_fin_anio;
                        }
                        $idtabla_prestaciones = $bus_cuenta_trabajador["idtabla_prestaciones"];
                        $sql_actualiza        = mysql_query("update tabla_prestaciones set
                                                                            sueldo = '" . $sueldo . "',
                                                                            otros_complementos = '" . $otros_complementos . "',
                                                                            bono_vacacional = '" . $mensual_bono_vacacional . "',
                                                                            bono_fin_anio = '" . $alicuota_bfa . "',
                                                                            dias_bono_vacacional = '" . $dias_vacacional . "',
                                                                            dias_bono_fin_anio = '" . $dias_fin_anio . "'
                                                    where idtabla_prestaciones = '" . $idtabla_prestaciones . "'") or die(mysql_error());
                    }
                    //Adelantos de prestaciones e intereses
                    if ($adelanto_prestaciones > 0 or $adelanto_intereses > 0) {
                        $sql_adelanto_trabajador = mysql_query("select * from tabla_adelantos
                                                 where idtabla_prestaciones = '" . $idtabla_prestaciones . "'") or die(mysql_error());
                        $bus_adelanto_trabajador = mysql_fetch_array($sql_adelanto_trabajador);
                        $num_adelanto_trabajador = mysql_num_rows($sql_adelanto_trabajador);

                        if ($num_adelanto_trabajador <= 0) {
                            $insertar_adelanto = mysql_query("insert into tabla_adelantos(idtabla_prestaciones,
                                                                                        monto_prestaciones,
                                                                                        monto_interes)VALUES(
                                                                                        '" . $idtabla_prestaciones . "',
                                                                                        '" . $adelanto_prestaciones . "',
                                                                                        '" . $adelanto_intereses . "')") or die(mysql_error());
                        } else {
                            $actualizar_adelanto = mysql_query("update tabla_adelantos set
                                                                    monto_prestaciones = '" . $adelanto_prestaciones . "',
                                                                    monto_interes = '" . $adelanto_intereses . "'
                                                                    where idtabla_prestaciones = '" . $idtabla_prestaciones . "'");
                        }

                    }
                }
            }else{
                echo 'error_cedula';
            }
            break;

        case 'application/vnd.ms-excel':
            require_once 'Excel/reader.php';
            $data = new Spreadsheet_Excel_Reader();
            $data->setOutputEncoding('CP1251');
            $data->read($nombreArchivo);
            $filas=$data->sheets[0]['numRows'];
            $cedula = mysql_real_escape_string(htmlspecialchars($data->sheets[0]['cells'][2][1]));
            $sql_trabajador = mysql_query("select * from trabajador where   idtrabajador = '".$idtrabajador."'
                                                                            and cedula = '" . $cedula . "'");
            $bus_trabajador = mysql_fetch_array($sql_trabajador);
            $num_trabajador = mysql_num_rows($sql_trabajador);

            if ($num_trabajador > 0) {
                for ($i = 2; $i <= ($filas); $i++) {
                    $anio = mysql_real_escape_string(htmlspecialchars($data->sheets[0]['cells'][$i][2]));
                    $mes  = mysql_real_escape_string(htmlspecialchars($data->sheets[0]['cells'][$i][3]));
                    $sql_cuenta_trabajador = mysql_query("select * from tabla_prestaciones
                                     where idtrabajador = '" . $bus_trabajador["idtrabajador"] . "'
                                     and anio = '" . $anio . "'
                                     and mes = '" . $mes . "'") or die(mysql_error());
                    $bus_cuenta_trabajador = mysql_fetch_array($sql_cuenta_trabajador);
                    $num_cuenta_trabajador = mysql_num_rows($sql_cuenta_trabajador);

                    $sueldo                = mysql_real_escape_string(htmlspecialchars($data->sheets[0]['cells'][$i][4]));
                    $otros_complementos    = mysql_real_escape_string(htmlspecialchars($data->sheets[0]['cells'][$i][5]));
                    $dias_vacacional       = mysql_real_escape_string(htmlspecialchars($data->sheets[0]['cells'][$i][6]));
                    $bono_vacacional       = mysql_real_escape_string(htmlspecialchars($data->sheets[0]['cells'][$i][7]));
                    $dias_fin_anio         = mysql_real_escape_string(htmlspecialchars($data->sheets[0]['cells'][$i][8]));
                    $bono_fin_anio         = mysql_real_escape_string(htmlspecialchars($data->sheets[0]['cells'][$i][9]));
                    $adelanto_prestaciones = mysql_real_escape_string(htmlspecialchars($data->sheets[0]['cells'][$i][10]));
                    $adelanto_intereses    = mysql_real_escape_string(htmlspecialchars($data->sheets[0]['cells'][$i][11]));

                    if ($num_cuenta_trabajador <= 0) {
                        $alicuota_bv = 0;
                        if($dias_vacacional > 0){
                            $mensual_bono_vacacional = ((($sueldo + $otros_complementos) / 30) * $dias_vacacional / 360) * 30;
                            $alicuota_bv = $mensual_bono_vacacional;
                        }else{
                            $mensual_bono_vacacional = $bono_vacacional;
                        }
                        if($dias_fin_anio > 0){
                            $alicuota_bfa = (((($sueldo + $otros_complementos) / 30) + ($alicuota_bv / 30)) * $dias_fin_anio / 360) * 30;
                        }else{
                            $alicuota_bfa = $bono_fin_anio;
                        }

                        $sql_cuenta = mysql_query("insert into tabla_prestaciones(
                                                                idtrabajador,
                                                                anio,
                                                                mes,
                                                                sueldo,
                                                                dias_bono_vacacional,
                                                                bono_vacacional,
                                                                dias_bono_fin_anio,
                                                                bono_fin_anio,
                                                                otros_complementos)VALUES(
                                                                '" . $bus_trabajador["idtrabajador"] . "',
                                                                '" . $anio . "',
                                                                '" . $mes . "',
                                                                '" . $sueldo . "',
                                                                '" . $dias_vacacional . "',
                                                                '" . $mensual_bono_vacacional . "',
                                                                '" . $dias_fin_anio . "',
                                                                '" . $alicuota_bfa . "',
                                                                '" . $otros_complementos . "')") or die(mysql_error());
                        $idtabla_prestaciones = mysql_insert_id();
                    } else {

                        $alicuota_bv = 0;
                        if($dias_vacacional > 0){
                            $mensual_bono_vacacional = ((($sueldo + $otros_complementos) / 30) * $dias_vacacional / 360) * 30;
                            $alicuota_bv = $mensual_bono_vacacional;
                        }else{
                            $mensual_bono_vacacional = $bono_vacacional;
                        }
                        if($dias_fin_anio > 0){
                            $alicuota_bfa = (((($sueldo + $otros_complementos) / 30) + ($alicuota_bv / 30)) * $dias_fin_anio / 360) * 30;
                        }else{
                            $alicuota_bfa = $bono_fin_anio;
                        }

                        $idtabla_prestaciones = $bus_cuenta_trabajador["idtabla_prestaciones"];
                        $sql_actualiza        = mysql_query("update tabla_prestaciones set
                                                                            sueldo = '" . $sueldo . "',
                                                                            otros_complementos = '" . $otros_complementos . "',
                                                                            bono_vacacional = '" . $mensual_bono_vacacional . "',
                                                                            bono_fin_anio = '" . $alicuota_bfa . "',
                                                                            dias_bono_vacacional = '" . $dias_vacacional . "',
                                                                            dias_bono_fin_anio = '" . $dias_fin_anio . "'
                                                    where idtabla_prestaciones = '" . $idtabla_prestaciones . "'") or die(mysql_error());
                    }
                    //Adelantos de prestaciones e intereses
                    if ($adelanto_prestaciones > 0 or $adelanto_intereses > 0) {
                        $sql_adelanto_trabajador = mysql_query("select * from tabla_adelantos
                                                 where idtabla_prestaciones = '" . $idtabla_prestaciones . "'") or die(mysql_error());
                        $bus_adelanto_trabajador = mysql_fetch_array($sql_adelanto_trabajador);
                        $num_adelanto_trabajador = mysql_num_rows($sql_adelanto_trabajador);

                        if ($num_adelanto_trabajador <= 0) {
                            $insertar_adelanto = mysql_query("insert into tabla_adelantos(idtabla_prestaciones,
                                                                                        monto_prestaciones,
                                                                                        monto_interes)VALUES(
                                                                                        '" . $idtabla_prestaciones . "',
                                                                                        '" . $adelanto_prestaciones . "',
                                                                                        '" . $adelanto_intereses . "')") or die(mysql_error());
                        } else {
                            $actualizar_adelanto = mysql_query("update tabla_adelantos set
                                                                    monto_prestaciones = '" . $adelanto_prestaciones . "',
                                                                    monto_interes = '" . $adelanto_intereses . "'
                                                                    where idtabla_prestaciones = '" . $idtabla_prestaciones . "'");
                        }

                    }

                }
            }else{
                echo 'error_cedula';
            }
            break;

    }
    unlink($nombreArchivo);
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', '1');

}


if ($ejecutar == "cargarImportar") {
    $tipo = $_FILES['archivo_importar_prestaciones']['type'];
    $dir  = '../importar/';
    if ($tipo == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' or $tipo == 'application/vnd.ms-excel') {
        $nombre_importar = $_FILES['archivo_importar_prestaciones']['name'];
        while (file_exists($dir . $nombre_importar)) {
            $partes_importar = explode(".", $nombre_importar);
            $nombre_importar = $partes_importar[0] . rand(0, 1000000) . "." . $partes_importar[1];
        }
        if (!copy($_FILES['archivo_importar_prestaciones']['tmp_name'], $dir . $nombre_importar)) {
            ?>
                <?
        } else {
            $ruta = '../importar/' . $nombre_importar;
        }

        ?>

            <script>
            parent.document.getElementById('nombre_importar').value = '<?=$ruta?>';
            parent.document.getElementById('tipo_importar').value = '<?=$tipo?>';
            parent.document.getElementById('error_tipo').style.display='none';
            </script>
            <?

    } else {
        ?>
            <script>
            parent.document.getElementById('error_tipo').style.display='block';
            parent.document.getElementById('error_tipo').innerHTML = "<table><tr><td style='color:#990000; font-weight:bold'>* Disculpe el archivo que intenta subir NO es de tipo Excel 1997/2003 (.xls)</td></tr></table>";
            </script>

            <?
    }

}


if ($ejecutar == "importarPrestacionesAnios") {
    $bd_anio_importar = 'gestion_'.$anio_importar;
    $bd_anio_trabajo = 'gestion_'.$_SESSION['anio_fiscal'];

    //ACTUALIZAR PRESTACIONES

    $sql_tabla_prestaciones = mysql_query("SELECT * FROM ".$bd_anio_importar.".tabla_prestaciones
                                                WHERE idtrabajador = '".$idtrabajador."'
                                                AND anio <= '".$anio_importar."'")or die(mysql_error().'1');
    while ($reg_tabla_importar = mysql_fetch_array($sql_tabla_prestaciones)){
        $dias_vacacional = $reg_tabla_importar['dias_bono_vacacional'];
        $dias_fin_anio = $reg_tabla_importar['dias_bono_fin_anio'];
        $alicuota_bv = 0;
        if($dias_vacacional > 0){
            $mensual_bono_vacacional = ((($sueldo + $otros_complementos) / 30) * $dias_vacacional / 360) * 30;
            $alicuota_bv = $mensual_bono_vacacional;
        }else{
            $mensual_bono_vacacional = $reg_tabla_importar['bono_vacacional'];
        }
        if($dias_fin_anio > 0){
            $alicuota_bfa = (((($sueldo + $otros_complementos) / 30) + ($alicuota_bv / 30)) * $dias_fin_anio / 360) * 30;
        }else{
            $alicuota_bfa = $reg_tabla_importar['bono_fin_anio'];
        }

        $sql_update = mysql_query("UPDATE ".$bd_anio_trabajo.".tabla_prestaciones SET
                                    ".$bd_anio_trabajo.".tabla_prestaciones.sueldo = '".$reg_tabla_importar['sueldo']."',
                                    ".$bd_anio_trabajo.".tabla_prestaciones.otros_complementos = '".$reg_tabla_importar['otros_complementos']."',
                                    ".$bd_anio_trabajo.".tabla_prestaciones.bono_vacacional = '".$mensual_bono_vacacional."',
                                    ".$bd_anio_trabajo.".tabla_prestaciones.bono_fin_anio = '".$alicuota_bfa."',
                                    ".$bd_anio_trabajo.".tabla_prestaciones.dias_bono_vacacional = '".$dias_vacacional."',
                                    ".$bd_anio_trabajo.".tabla_prestaciones.dias_bono_fin_anio = '".$dias_fin_anio."'
        where ".$bd_anio_trabajo.".tabla_prestaciones.idtrabajador = '".$reg_tabla_importar['idtrabajador']."'
                AND ".$bd_anio_trabajo.".tabla_prestaciones.anio = '".$reg_tabla_importar['anio']."'
                AND ".$bd_anio_trabajo.".tabla_prestaciones.mes = '".$reg_tabla_importar['mes']."'
        ")or die(mysql_error().'2');
    }

    //ACTUALIZANDO ADELANTOS

    $sql_adelantos = mysql_query("SELECT * FROM ".$bd_anio_importar.".tabla_adelantos,
                                            ".$bd_anio_importar.".tabla_prestaciones
                                    WHERE
                                        ".$bd_anio_importar.".tabla_adelantos.idtabla_prestaciones =
                                        ".$bd_anio_importar.".tabla_prestaciones.idtabla_prestaciones
                                    AND ".$bd_anio_importar.".tabla_prestaciones.idtrabajador = '".$idtrabajador."'")or die(mysql_error().'3');
    while($bus_adelantos = mysql_fetch_array($sql_adelantos)){

        $adelanto_prestaciones = $bus_adelantos["monto_prestaciones"];
        $adelanto_intereses    = $bus_adelantos["monto_interes"];

        $sql_datos_2017 = mysql_query("select * from ".$bd_anio_trabajo.".tabla_prestaciones
                                        where
                                        idtrabajador = '".$bus_adelantos["idtrabajador"]."'
                                        and anio = '".$bus_adelantos["anio"]."'
                                        and mes = '".$bus_adelantos["mes"]."'")or die(mysql_error());
        $bus_datos_2017 = mysql_fetch_array($sql_datos_2017);

        $idtabla_prestaciones_2017 = $bus_datos_2017["idtabla_prestaciones"];

        $sql_adelanto_trabajador = mysql_query("select * from ".$bd_anio_trabajo.".tabla_adelantos
                                     where idtabla_prestaciones = '" . $idtabla_prestaciones_2017 . "'") or die(mysql_error());

        $bus_adelanto_trabajador = mysql_fetch_array($sql_adelanto_trabajador);
        $num_adelanto_trabajador = mysql_num_rows($sql_adelanto_trabajador);

        if ($num_adelanto_trabajador <= 0) {
            $insertar_adelanto = mysql_query("insert into ".$bd_anio_trabajo.".tabla_adelantos(idtabla_prestaciones,
                                                                        monto_prestaciones,
                                                                        monto_interes)VALUES(
                                                                        '" . $idtabla_prestaciones_2017 . "',
                                                                        '" . $adelanto_prestaciones . "',
                                                                        '" . $adelanto_intereses . "')") or die(mysql_error());
        } else {
            $actualizar_adelanto = mysql_query("update ".$bd_anio_trabajo.".tabla_adelantos set
                                                    monto_prestaciones = '" . $adelanto_prestaciones . "',
                                                    monto_interes = '" . $adelanto_intereses . "'
                                                    where idtabla_prestaciones = '" . $idtabla_prestaciones_2017 . "'");
        }
    }
}


if ($ejecutar == "cargarSelectNominas") {

 echo '<option>..: Seleccione Tipo Nomina :..</option>';

    $sql_nominas = mysql_query("SELECT * FROM tipo_nomina, relacion_tipo_nomina_trabajador
                                WHERE tipo_nomina.idtipo_nomina = relacion_tipo_nomina_trabajador.idtipo_nomina
                                AND relacion_tipo_nomina_trabajador.idtrabajador = '".$idtrabajador."'
                                AND relacion_tipo_nomina_trabajador.activa = '1'")or die(mysql_error());
    while($reg_nominas = mysql_fetch_array($sql_nominas)){
      ?>
      <option value="<?=$reg_nominas['idtipo_nomina']?>" onclick="cargarSelectPeriodos();"><?=substr($reg_nominas["titulo_nomina"],0,37)?></option>
      <?php
    }

}

if ($ejecutar == "cargarSelectPeriodos") {

 echo '<option>..: Seleccione Periodo :..</option>';
    $sql_consultar_periodo = mysql_query("select * from
                                                    periodos_nomina pn,
                                                    rango_periodo_nomina rpn
                                                    where
                                                    pn.idtipo_nomina = '".$idtipo_nomina."'
                                                    and pn.periodo_activo = 'si'
                                                    and rpn.idperiodo_nomina = pn.idperiodos_nomina")or die(mysql_error());

    while($reg_consultar_periodo = mysql_fetch_array($sql_consultar_periodo)){
        $desde = explode(" ", $reg_consultar_periodo["desde"]);
        $hasta = explode(" ", $reg_consultar_periodo["hasta"]);
        $num_generar_nomina = 0;
        $sql_generar_nomina = mysql_query("select * from generar_nomina where idperiodo = '".$reg_consultar_periodo["idrango_periodo_nomina"]."' and estado = 'procesado' and idtipo_nomina = '".$idtipo_nomina."'")or die(mysql_error());

        $num_generar_nomina = mysql_num_rows($sql_generar_nomina);
        if($num_generar_nomina != 0){
          ?>
          <option value="<?=$reg_consultar_periodo['idrango_periodo_nomina'].'|.|'.$desde[0]?>" ><?=$desde[0]." - ".$hasta[0]?></option>
          <?php
        }
    }

}


if ($ejecutar == "importarPrestacionesNomina") {
    list($anioPrestaciones, $mesPrestaciones, $diaPrestaciones) = explode("-", $desde);
    $sql_generar_nomina = mysql_query("SELECT * FROM generar_nomina WHERE idtipo_nomina = '".$idtipo_nomina."'
                                                                    AND idperiodo = '".$idperiodo_nomina."'
                                                                    AND estado = 'procesado'");
    $reg_generar_nomina = mysql_fetch_array($sql_generar_nomina);

    $sql_conceptos_trabajador = mysql_query("SELECT * FROM relacion_generar_nomina, conceptos_nomina
                                            WHERE conceptos_nomina.idconceptos_nomina = relacion_generar_nomina.idconcepto
                                            AND relacion_generar_nomina.idgenerar_nomina = '".$reg_generar_nomina["idgenerar_nomina"]."'
                                            AND relacion_generar_nomina.idtrabajador = '".$idtrabajador."'
                                            AND relacion_generar_nomina.tabla = 'conceptos_nomina'
                                            AND conceptos_nomina.aplica_prestaciones='si'");
    while($reg_conceptos = mysql_fetch_array($sql_conceptos_trabajador)){
        $columna_prestaciones = $reg_conceptos["columna_prestaciones"];
        switch ($columna_prestaciones) {
            case 'sueldo':
                $sql_actualizar = mysql_query("UPDATE tabla_prestaciones
                                                SET sueldo = sueldo + '" . $reg_conceptos["total"] . "'
                                                WHERE idtrabajador = '".$idtrabajador."'
                                                    AND anio = '".$anioPrestaciones."'
                                                    AND mes = '".$mesPrestaciones."'");
                break;
            case 'oc':
                $sql_actualizar = mysql_query("UPDATE tabla_prestaciones
                                                SET otros_complementos = otros_complementos + '" . $reg_conceptos["total"] . "'
                                                WHERE idtrabajador = '".$idtrabajador."'
                                                    AND anio = '".$anioPrestaciones."'
                                                    AND mes = '".$mesPrestaciones."'");
                break;
            case 'bv':
                $sql_actualizar = mysql_query("UPDATE tabla_prestaciones
                                                SET bono_vacacional = bono_vacacional + '" . $reg_conceptos["total"] . "'
                                                WHERE idtrabajador = '".$idtrabajador."'
                                                    AND anio = '".$anioPrestaciones."'
                                                    AND mes = '".$mesPrestaciones."'");
                break;
            case 'bfa':
                $sql_actualizar = mysql_query("UPDATE tabla_prestaciones
                                                SET bono_fin_anio = bono_fin_anio + '" . $reg_conceptos["total"] . "'
                                                WHERE idtrabajador = '".$idtrabajador."'
                                                    AND anio = '".$anioPrestaciones."'
                                                    AND mes = '".$mesPrestaciones."'");
                break;

        }

    }
}

?>
