<br>
<h4 align=center>Cargar Prestaciones</h4>
<h2 class="sqlmVersion"></h2>
<br>



<?
if (!($link = mysql_connect("localhost", "root", ""))) {
    echo "Error conectando al Servidor: " . mysql_error();
    exit();
}

if (!mysql_select_db("gestion_procuraduria_2016_06122016", $link)) {

    echo "Error conectando a la base de datos.";
    exit();
}

$tipo_cuenta = 2;
$tipo        = 'Corriente';
$banco       = 2;

$nombre = 'carga_prestaciones.xls';
require_once 'Excel/reader.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read($nombre);
error_reporting(E_ALL ^ E_NOTICE);

for ($i = 1; $i < (3010); $i++) {

    $sql_trabajador = mysql_query("select * from trabajador where cedula = '" . $data->sheets[0]['cells'][$i][1] . "'");
    $bus_trabajador = mysql_fetch_array($sql_trabajador);
    $num_trabajador = mysql_num_rows($sql_trabajador);

    if ($num_trabajador > 0) {
        //echo " idtrabajador ".$bus_trabajador["idtrabajador"];
        //echo " anio ".$data->sheets[0]['cells'][$i][2];
        //echo " mes ".$data->sheets[0]['cells'][$i][3]."<br>";
        $sql_cuenta_trabajador = mysql_query("select * from tabla_prestaciones
									 where idtrabajador = '" . $bus_trabajador["idtrabajador"] . "'
									 and anio = '" . $data->sheets[0]['cells'][$i][2] . "'
									 and mes = '" . $data->sheets[0]['cells'][$i][3] . "'") or die(mysql_error());
        $bus_cuenta_trabajador = mysql_fetch_array($sql_cuenta_trabajador);
        $num_cuenta_trabajador = mysql_num_rows($sql_cuenta_trabajador);
        /*if ($data->sheets[0]['cells'][$i][2] <= 2007) {
        $sueldo                = $data->sheets[0]['cells'][$i][4] / 1000;
        $bono_vacacional       = $data->sheets[0]['cells'][$i][5] / 1000;
        $bono_fin_anio         = $data->sheets[0]['cells'][$i][6] / 1000;
        $otros_complementos    = $data->sheets[0]['cells'][$i][7] / 1000;
        $adelanto_prestaciones = $data->sheets[0]['cells'][$i][8] / 1000;
        $adelanto_intereses    = $data->sheets[0]['cells'][$i][9] / 1000;
        $dias_vacacional       = $data->sheets[0]['cells'][$i][10];
        $dias_prestacion       = $data->sheets[0]['cells'][$i][11];
        } else {
         */
        $sueldo                = $data->sheets[0]['cells'][$i][4];
        $bono_vacacional       = $data->sheets[0]['cells'][$i][5];
        $bono_fin_anio         = $data->sheets[0]['cells'][$i][6];
        $otros_complementos    = $data->sheets[0]['cells'][$i][7];
        $adelanto_prestaciones = $data->sheets[0]['cells'][$i][8];
        $adelanto_intereses    = $data->sheets[0]['cells'][$i][9];
        $dias_vacacional       = $data->sheets[0]['cells'][$i][10];
        $dias_prestacion       = $data->sheets[0]['cells'][$i][11];
        //}

        echo " existe " . $num_cuenta_trabajador;
        if ($num_cuenta_trabajador <= 0) {

            $alicuota_bv = ((($sueldo + $otros_complementos) / 30) * $dias_vacacional / 360) * 30;
            $alicuota_pr = (((($sueldo + $otros_complementos) / 30) + ($alicuota_bv / 30)) * $dias_prestacion / 360) * 30;

            $sql_cuenta = mysql_query("insert into tabla_prestaciones(
													idtrabajador,
													anio,
													mes,
													sueldo,
													bono_vacacional,
													bono_fin_anio,
													otros_complementos)VALUES(
													'" . $bus_trabajador["idtrabajador"] . "',
													'" . $data->sheets[0]['cells'][$i][2] . "',
													'" . $data->sheets[0]['cells'][$i][3] . "',
													'" . $sueldo . "',
													'" . $alicuota_bv . "',
													'" . $alicuota_pr . "',
													'" . $otros_complementos . "')") or die(mysql_error());

            if ($sql_cuenta) {
                $idtabla_prestaciones = mysql_insert_id();
                echo "Linea Numero $i -> Se PROCESO el año nro: " . $data->sheets[0]['cells'][$i][2] . " mes: " . $data->sheets[0]['cells'][$i][3] . " <br />";
            } else {
                echo "Problemas : " . mysql_error() . "<br />";
            }
        } else {

            $alicuota_bv = ((($sueldo + $otros_complementos) / 30) * $dias_vacacional / 360) * 30;
            $alicuota_pr = (((($sueldo + $otros_complementos) / 30) + ($alicuota_bv / 30)) * $dias_prestacion / 360) * 30;

            $idtabla_prestaciones = $bus_cuenta_trabajador["idtabla_prestaciones"];
            $sql_actualiza        = mysql_query("update tabla_prestaciones set sueldo = '" . $sueldo . "',
														 		otros_complementos = '" . $otros_complementos . "',
														 		bono_vacacional = '" . $alicuota_bv . "',
														 		bono_fin_anio = '" . $alicuota_pr . "'
										where idtabla_prestaciones = '" . $idtabla_prestaciones . "'") or die;
            echo "<strong style='color:#FF0000'>Linea Numero $i -> Se ACTUALIZO el año nro: " . $data->sheets[0]['cells'][$i][2] . " mes: " . $data->sheets[0]['cells'][$i][3] . " valor: " . $sueldo . " idtabla_prestaciones " . $idtabla_prestaciones . " </strong><br />";
        }

        if ($data->sheets[0]['cells'][$i][8] > 0 or $data->sheets[0]['cells'][$i][9] > 0) {
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

    } else {
        echo "<strong style='color:#FF0000'>EL TRABAJADOR nro: " . $data->sheets[0]['cells'][$i][1] . ", no se encontro<br /></strong>";
    }

}

?>