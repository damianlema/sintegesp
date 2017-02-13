<?
session_start();
set_time_limit(-1);
include "../ClassSQLimporter.php";

// HAGO UNA COPIA DE LA CARPETA GESTION

function full_copy($source, $target)
{
    if (is_dir($source)) {
        @mkdir($target);
        $d = dir($source);
        while (false !== ($entry = $d->read())) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            $Entry = $source . '/' . $entry;
            if (is_dir($Entry)) {
                full_copy($Entry, $target . '/' . $entry);
                continue;
            }
            copy($Entry, $target . '/' . $entry);
        }
        $d->close();
    } else {
        copy($source, $target);
    }
}

$c  = mysql_connect("localhost", "root", "gestion2009");
$db = mysql_select_db("gestion_configuracion_general", $c);

$sql = mysql_query("select * from anios where anio = '" . $anio_a_cambiar . "'");
$num = mysql_num_rows($sql);

if ($num == 0) {
    $source = '../../../gestion_' . $_SESSION["anio_fiscal"] . '/'; //CARPETA DE ORIGEN DE LOS ARCHIVOS SCRIPT A COPIAR AL NUEÑO AÑO
    //$source ='../../../gestion_contabilidad/';
    $destination = '../../../gestion_' . $anio_a_cambiar . "/";
    if (file_exists($destination)) {
        echo "<br /><br /><br />Disculpe la carpeta del año seleccionada ya esta creada, por favor eliminela del servidor y vuelva a intentarlo";
    } else {
        full_copy($source, $destination);

        $host       = "localhost";
        $dbUser     = "root";
        $dbPassword = "gestion2009";
        $sqlFile    = "../script_" . $anio_a_cambiar . ".sql"; //ARCHIVO CON LA ESTRUCTURA DE LA BASE DE DATOS A CREAR
        $sqlFile1   = "../gestion_respaldo.sql"; // ARCHIVO CON LA ESTRUCTURA DE LA BASE DE DATOS DE RESPALDO DE TRANSACCIONES
        //$sqlFile = "../../../gestion/doc/script.sql";

        $newImport1 = new sqlImport($host, $dbUser, $dbPassword, $sqlFile1, "gestion_respaldo_" . $anio_a_cambiar);
        $newImport1->import();

        $newImport = new sqlImport($host, $dbUser, $dbPassword, $sqlFile, "gestion_" . $anio_a_cambiar);
        $newImport->import();

        //------------------ Show Messages !!! ---------------------------
        $import = $newImport->ShowErr();
        if ($import[0] == true) {

            //SE CREA EL ARCHIVO CONF
            //chmod($destination,777);
            $nombre_temp = "conex.php";
            $archivo     = $destination . "conf/" . $nombre_temp;
            $gestor      = fopen($archivo, "w");
            fwrite($gestor,
                "<?php
session_start();
set_time_limit(-1);
function Conectarse(){
   if (!(\$link=mysql_connect(\"localhost\",\"root\",\"gestion2009\"))){
        echo \"Error conectando a la base de datos.\";
        exit();
   }
   if (!mysql_select_db(\"gestion_" . $anio_a_cambiar . "\",\$link)) {
      echo \"Error conectando a la base de datos.\";
      exit();
   }
   mysql_query(\"SET NAMES 'utf8'\");
   return \$link;
}
function desconectar(){
    mysql_close();
}
?>"
            );
            fclose($gestor);

            // SE CREA EL ARCHIVO CONF

            $sql_insert = mysql_query("insert into gestion_configuracion_general.anios(anio,
                                                         ruta)VALUES('" . $anio_a_cambiar . "', 'http://localhost/gestion_" . $anio_a_cambiar . "/')") or die(mysql_error());

            // AQUI VAN LAS TABLAS QUE HAY QUE DUPLICAR

            $arreglo_tablas = array(
                "accion",
                "actividad",
                "actividades_comerciales",
                "actividades_contribuyente",
                "adjuntos_correo",
                "adjuntos_mensajes_correo",
                "agrupacion_modulos",
                "almacen",
                "articulos_servicios",
                "asociar_concepto_actividad_comercial",
                "asociar_requisitos_actividad_comercial",
                "asociar_requisitos_tipo_solicitud",
                "asocia_tipo_solicitud_concepto",
                "autorizar_generar_comprobante",
                "banco",
                "beneficiarios",
                "calle",
                "carga_familiar",
                "cargos",
                "carrera",
                "categoria_programatica",
                "cheques_cuentas_bancarias",
                "ciudad",
                "clasificador_presupuestario",
                "cofinanciamiento",
                "conceptos_desagregados",
                "conceptos_nomina",
                "concepto_tributario",
                "condicion_almacenaje_materia",
                "condicion_conservacion_materia",
                "configuracion",
                "configuracion_administracion",
                "configuracion_almacen",
                "configuracion_bienes",
                "configuracion_caja_chica",
                "configuracion_cheques",
                "configuracion_compras",
                "configuracion_contabilidad",
                "configuracion_despacho",
                "configuracion_logos",
                "configuracion_nomina",
                "configuracion_presupuesto",
                "configuracion_recaudacion",
                "configuracion_reportes",
                "configuracion_rrhh",
                "configuracion_secretaria",
                "configuracion_tesoreria",
                "configuracion_tributos",
                "configuracion_obras",
                "constantes_nomina",
                "contribuyente",
                "cuentas_bancarias",
                "cuentas_bancarias_trabajador",
                "cuenta_cuentas_contables",
                "cursos",
                "cursos_otros_estudios",
                "declaracion_jurada",
                "dependencias",
                "desagregacion_cuentas_contables",
                "desagrega_unidad_medida",
                "desincorporacion_bienes",
                "detalle_catalogo_bienes",
                "detalle_materias_almacen",
                "dias_feriados",
                "distribucion_almacen",
                "documentos_requeridos",
                "documento_entregado_beneficiario",
                "edificios",
                "edo_civil",
                "entes_gubernamentales",
                "estado",
                "estado_beneficiario",
                "experiencia_laboral",
                "forma_materia",
                "fuentes_cofinanciamiento",
                "fuente_financiamiento",
                "grupos",
                "grupo_catalogo_bienes",
                "grupo_cuentas_contables",
                "grupo_materias_almacen",
                "grupo_sanguineo",
                "historico_disfrute_vacaciones",
                "historico_movimiento_personal",
                "historico_permisos",
                "historico_vacaciones",
                "imagenes_contribuyente",
                "impuestos",
                "instalaciones_fijas",
                "instruccion_academica",
                "inventario_materia",
                "jornada_tipo_nomina",
                "leyes_prestaciones",
                "lista_usuarios_mensajes_correo",
                "marca_materia",
                "mensajes_correo",
                "mension",
                "microbloging",
                "modalidad_contratacion",
                "modulo",
                "moras_conceptos_tributarios",
                "motivos_cuentas",
                "movimientos_bienes",
                "movimientos_bienes_individuales",
                "movimientos_bienes_nuevos",
                "movimientos_existentes_desincorporacion",
                "movimientos_existentes_incorporacion",
                "movimientos_lotes",
                "movimientos_personal",
                "movimiento_materia_almacen",
                "muebles",
                "muebles_desincorporacion",
                "municipios",
                "nacionalidad",
                "niveles_organizacionales",
                "nivel_estudio",
                "nombres_firmas",
                "nomenclatura_fichas",
                "ordinal",
                "organizacion",
                "pais",
                "parentezco",
                "parroquia",
                "periodos_cedentes_trabajadores",
                "periodos_nomina",
                "privilegios_acciones",
                "privilegios_modulo",
                "profesion",
                "programa",
                "proyecto",
                "ramos_articulos",
                "rango_fecha_vencimiento_conceptos",
                "rango_tabla_constantes",
                "rango_tabla_constantes_recaudacion",
                "rango_periodo_nomina",
                "razones_devolucion",
                "reconocimientos",
                "registro_fotografico_bienes_muebles",
                "registro_fotografico_bienes_nuevos",
                "registro_fotografico_existentes_desincorporacion",
                "registro_fotografico_existentes_incorporacion",
                "registro_fotografico_trabajador",
                "registro_mercantil_contribuyente",
                "relacion_accesorios_materia",
                "relacion_compra_materia",
                "relacion_conceptos_periodos",
                "relacion_concepto_trabajador",
                "relacion_desagrega_unidad_materia",
                "relacion_equivalencia_materia",
                "relacion_formula_conceptos_nomina",
                "relacion_imagen_materia",
                "relacion_instalaciones_fijas",
                "relacion_materia_articulos_servicios",
                "relacion_reemplazo_materia",
                "relacion_requisitos_contribuyente",
                "relacion_serial_materia",
                "relacion_tipo_nomina_trabajador",
                "relacion_vencimiento_materia",
                "requisitos",
                "rubro_cuentas_contables",
                "sanciones",
                "secciones_catalogo_bienes",
                "seccion_materias_almacen",
                "sector",
                "sectores",
                "series",
                "socios_contribuyente",
                "subcuenta_primer_cuentas_contables",
                "subcuenta_segundo_cuentas_contables",
                "subgrupo_catalogo_bienes",
                "subgrupo_cuentas_contables",
                "sub_programa",
                "tabla_adelantos",
                "tabla_aguinaldos",
                "tabla_constantes",
                "tabla_constantes_recaudacion",
                "tabla_deducciones",
                "tabla_intereses",
                "tabla_prestaciones",
                "tabla_vacaciones",
                "terrenos",
                "tipos_documentos",
                "tipos_persona",
                "tipos_reconocimientos",
                "tipo_beneficiario",
                "tipo_caja_chica",
                "tipo_conceptos_nomina",
                "tipo_cuenta_bancaria",
                "tipo_detalle_almacen",
                "tipo_detalle",
                "tipo_empresa",
                "tipo_hoja_tiempo",
                "tipo_movimiento_bancario",
                "tipo_movimiento_personal",
                "tipo_nomina",
                "tipo_presupuesto",
                "tipo_retencion",
                "tipo_sanciones",
                "tipo_sociedad",
                "tipo_solicitud",
                "trabajador",
                "ubicacion",
                "unidad_ejecutora",
                "unidad_medida",
                "unidad_tributaria",
                "urbanizacion",
                "usuarios",
                "usuarios_categoria",
                "usuarios_fuente_financiamiento",
                "vacaciones",
                "volumen_materia");

            $sql_consulta = mysql_query("show tables from gestion_" . $_SESSION["anio_fiscal"] . "") or die(mysql_error());
            while ($bus_consulta = mysql_fetch_array($sql_consulta)) {
                if (in_array($bus_consulta[0], $arreglo_tablas)) {
                    $sql_ejecutar = mysql_query("TRUNCATE TABLE `gestion_" . $anio_a_cambiar . "`.`" . $bus_consulta[0] . "`")
                    or die($bus_consulta[0] . ": " . mysql_error());

                    $sql_ejecutar = mysql_query("insert into
                                                    `gestion_" . $anio_a_cambiar . "`.`" . $bus_consulta[0] . "`
                                                    select *
                                                        from
                                                    `gestion_" . $_SESSION["anio_fiscal"] . "`.`" . $bus_consulta[0] . "`")
                    or die($bus_consulta[0] . ": " . mysql_error());
                }
            }

            $sql_actualizar_configuracion = mysql_query("update `gestion_" . $anio_a_cambiar . "`.`configuracion`
                                                                set
                                                                anio_fiscal = '" . $anio_a_cambiar . "',
                                                                fecha_cierre = '" . $fecha_cierre . "'");

            $sql_actualizar_tipos_Documentos = mysql_query("update `gestion_" . $anio_a_cambiar . "`.tipos_documentos
                                                           set nro_contador = 1 ");

            $sql_rango_periodos_nomina = mysql_query("select * from `gestion_" . $anio_a_cambiar . "`.rango_periodo_nomina");
            while ($bus_consulta = mysql_fetch_array($sql_rango_periodos_nomina)) {
                list($anioDesde, $mesDesde, $diaDesde)       = explode("-", $bus_consulta["desde"]);
                $nuevoDesde                                  = $anio_a_cambiar . '-' . $mesDesde . '-' . $diaDesde;
                list($anioHasta, $mesHasta, $diaHasta)       = explode("-", $bus_consulta["hasta"]);
                $nuevoHasta                                  = $anio_a_cambiar . '-' . $mesHasta . '-' . $diaHasta;
                list($anioSugiere, $mesSugiere, $diaSugiere) = explode("-", $bus_consulta["sugiere_pago"]);
                $nuevoSugiere                                = $anio_a_cambiar . '-' . $mesSugiere . '-' . $diaSugiere;

                $sql_actualizar_rango = mysql_query("update `gestion_" . $anio_a_cambiar . "`.`rango_periodo_nomina`
                                                                set
                                                                desde = '" . $nuevoDesde . "',
                                                                hasta = '" . $nuevoHasta . "',
                                                                sugiere_pago = '" . $nuevoSugiere . "'
                                                        where
                                                            idrango_periodo_nomina = '" . $bus_consulta["idrango_periodo_nomina"] . "'")
                or die(" actualiza rango " . mysql_error());
            }

            $sql_periodos_nomina = mysql_query("select * from `gestion_" . $anio_a_cambiar . "`.periodos_nomina");
            while ($bus_consulta = mysql_fetch_array($sql_periodos_nomina)) {
                list($anioDesde, $mesDesde, $diaDesde) = explode("-", $bus_consulta["fecha_inicio"]);
                $nuevoDesde                            = $anio_a_cambiar . '-' . $mesDesde . '-' . $diaDesde;

                $sql_actualizar = mysql_query("update `gestion_" . $anio_a_cambiar . "`.`periodos_nomina`
                                                                set
                                                                fecha_inicio = '" . $nuevoDesde . "',
                                                                anio = '" . $anio_a_cambiar . "'
                                                        where
                                                            idperiodos_nomina = '" . $bus_consulta["idperiodos_nomina"] . "'")
                or die(" actualiza periodo " . mysql_error());
            }
            echo "<br /><br /><br />El nuevo año del sistema se ha generado con exito";

        } else {
            echo "<br /><br /><br />Disculpe no se pudo crear la base de datos por las siguientes razones...<br>\r\n";
            foreach ($import[1] as $index => $value) {
                echo $import[1][$index] . ": " . $import[2][$index] . "<br>\r\n<br /><br />";
            }
        }
    }
} else {
    echo "<br /><br /><br />Disculpe el Año que intenta generar ya existe, por favor verifique";
}

// HAGO UNA COPIA DE LA CARPETA GESTION
