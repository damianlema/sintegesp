<?php
ini_set('max_execution_time', 360000);
?>

<br>
<h4 align=center>Actualizar Prestaciones</h4>
<h2 class="sqlmVersion"></h2>
<br>



<?
if (!($link = mysql_connect("localhost", "root", ""))) {
    echo "Error conectando al Servidor: " . mysql_error();
    exit();
}

if (!mysql_select_db("gestion_mariguitar_2017abr2017", $link)) {

    echo "Error conectando a la base de datos.";
    exit();
}
$i = 1;

$bd_2017 = "gestion_mariguitar_2017abr2017";
$bd_2016 = "gestion_mariguitar_2016abr2017";

//LIMPIO LAS TABLAS DE PRESTACIONES DE VALORES BASURA


echo "ACTUALIZACION DE SUELDOS, COMPLEMENTOS Y BONOS, CULMINADA CON EXITO";


$sql_trabajadores = mysql_query("select * from ".$bd_2017.".trabajador")or die(mysql_error());

while($trabajadores = mysql_fetch_array($sql_trabajadores)){
    echo $trabajadores["idtrabajador"]."<br>";
    $sql_tabla_prestaciones = mysql_query("select * from ".$bd_2016.".tabla_prestaciones where idtrabajador = '".$trabajadores["idtrabajador"]."'")or die(mysql_error());
    //$sql_tabla_prestaciones = mysql_query("select * from ".$bd_2016.".tabla_prestaciones where idtrabajador = 324");
    while ($tabla_2016 = mysql_fetch_array($sql_tabla_prestaciones)){
        //echo $tabla_2016['anio']." ".$tabla_2016['mes']."<br>";
        $sql_update = mysql_query("UPDATE ".$bd_2017.".tabla_prestaciones SET
                                    ".$bd_2017.".tabla_prestaciones.sueldo = '".$tabla_2016['sueldo']."',
                                    ".$bd_2017.".tabla_prestaciones.otros_complementos = '".$tabla_2016['otros_complementos']."',
                                    ".$bd_2017.".tabla_prestaciones.bono_vacacional = '".$tabla_2016['bono_vacacional']."',
                                    ".$bd_2017.".tabla_prestaciones.bono_fin_anio = '".$tabla_2016['bono_fin_anio']."'
        where ".$bd_2017.".tabla_prestaciones.idtrabajador = '".$tabla_2016['idtrabajador']."'
                AND ".$bd_2017.".tabla_prestaciones.anio = '".$tabla_2016['anio']."'
                AND ".$bd_2017.".tabla_prestaciones.mes = '".$tabla_2016['mes']."'
        ")or die(mysql_error());
    }
}



/*$actualizar = mysql_query("

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
*/

?>