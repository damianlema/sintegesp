<?php
ini_set('max_execution_time', 3600);
?>

<br>
<h4 align=center>Actualizar Prestaciones</h4>
<h2 class="sqlmVersion"></h2>
<br>



<?
if (!($link = mysql_connect("localhost", "root", "gestion2009"))) {
    echo "Error conectando al Servidor: " . mysql_error();
    exit();
}

if (!mysql_select_db("gestion_2017", $link)) {

    echo "Error conectando a la base de datos.";
    exit();
}
$i = 1;

$bd_2017 = "gestion_2017";
$bd_2016 = "gestion_2016";

//LIMPIO LAS TABLAS DE PRESTACIONES DE VALORES BASURA
$borrar_trabajador_cero = mysql_query("delete from ".$bd_2017.".tabla_prestaciones where idtrabajador=0");
$borrar_trabajador_cero2016 = mysql_query("delete from ".$bd_2016.".tabla_prestaciones where idtrabajador=0");

$actualizar_cedula = mysql_query("UPDATE trabajador SET cedula = '5706885' WHERE idtrabajador = 138");

echo "ACTUALIZACION DE SUELDOS, COMPLEMENTOS Y BONOS, CULMINADA CON EXITO";

$actualizar = mysql_query("

    UPDATE ".$bd_2017.".tabla_prestaciones
    INNER JOIN ".$bd_2016.".tabla_prestaciones
        on (".$bd_2017.".tabla_prestaciones.idtrabajador = ".$bd_2016.".tabla_prestaciones.idtrabajador
            and ".$bd_2017.".tabla_prestaciones.anio = ".$bd_2016.".tabla_prestaciones.anio
            and ".$bd_2017.".tabla_prestaciones.mes = ".$bd_2016.".tabla_prestaciones.mes)
    SET ".$bd_2017.".tabla_prestaciones.sueldo = ".$bd_2016.".tabla_prestaciones.sueldo,
        ".$bd_2017.".tabla_prestaciones.otros_complementos = ".$bd_2016.".tabla_prestaciones.otros_complementos,
        ".$bd_2017.".tabla_prestaciones.bono_vacacional = ".$bd_2016.".tabla_prestaciones.bono_vacacional,
        ".$bd_2017.".tabla_prestaciones.bono_fin_anio = ".$bd_2016.".tabla_prestaciones.bono_fin_anio
    where
        ".$bd_2017.".tabla_prestaciones.sueldo = 0
    ")or die(mysql_error());


?>