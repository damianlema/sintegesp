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


echo "ACTUALIZANDO ADELANTOS<BR><BR>";


$sql_adelantos = mysql_query("select * from ".$bd_2016.".tabla_adelantos,
                                            ".$bd_2016.".tabla_prestaciones
                                    where
                                        ".$bd_2016.".tabla_adelantos.idtabla_prestaciones =
                                        ".$bd_2016.".tabla_prestaciones.idtabla_prestaciones")or die(mysql_error());
    while($bus_adelantos = mysql_fetch_array($sql_adelantos)){

        $adelanto_prestaciones = $bus_adelantos["monto_prestaciones"];
        $adelanto_intereses = $bus_adelantos["monto_interes"];

        $sql_datos_2017 = mysql_query("select * from ".$bd_2017.".tabla_prestaciones
                                        where
                                        idtrabajador = '".$bus_adelantos["idtrabajador"]."'
                                        and anio = '".$bus_adelantos["anio"]."'
                                        and mes = '".$bus_adelantos["mes"]."'")or die(mysql_error());
        $bus_datos_2017 = mysql_fetch_array($sql_datos_2017);

        $idtabla_prestaciones_2017 = $bus_datos_2017["idtabla_prestaciones"];

        $sql_adelanto_trabajador = mysql_query("select * from ".$bd_2017.".tabla_adelantos
                                     where idtabla_prestaciones = '" . $idtabla_prestaciones_2017 . "'") or die(mysql_error());

        $bus_adelanto_trabajador = mysql_fetch_array($sql_adelanto_trabajador);
        $num_adelanto_trabajador = mysql_num_rows($sql_adelanto_trabajador);

        if ($num_adelanto_trabajador <= 0) {
            $insertar_adelanto = mysql_query("insert into ".$bd_2017.".tabla_adelantos(idtabla_prestaciones,
                                                                        monto_prestaciones,
                                                                        monto_interes)VALUES(
                                                                        '" . $idtabla_prestaciones_2017 . "',
                                                                        '" . $adelanto_prestaciones . "',
                                                                        '" . $adelanto_intereses . "')") or die(mysql_error());
        } else {
            $actualizar_adelanto = mysql_query("update ".$bd_2017.".tabla_adelantos set
                                                    monto_prestaciones = '" . $adelanto_prestaciones . "',
                                                    monto_interes = '" . $adelanto_intereses . "'
                                                    where idtabla_prestaciones = '" . $idtabla_prestaciones_2017 . "'");
        }
        echo "Procesando registro ".$idtabla_prestaciones_2017."<br>";
    }
echo "<BR><BR>PROCESO FINALIZADO CON EXITO";
?>