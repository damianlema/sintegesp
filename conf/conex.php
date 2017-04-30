<?php
/*****************************************************************************
 * @
 * @versión: 1.0
 * @fecha creación:
 * @autor:
 ******************************************************************************
 * @fecha modificacion
 * @autor
 * @descripcion
 ******************************************************************************/
session_start();

set_time_limit(-1);
function Conectarse()
{
    if (!($link = mysql_connect("localhost", "root", ""))) {
        echo "Error conectando al Servidor: " . mysql_error();
        exit();
    }

<<<<<<< HEAD
    if (!mysql_select_db("gestion_mapire_2017_itrimestre", $link)) {
=======
    if (!mysql_select_db("gestion_mariguitar_2017abr2017", $link)) {
>>>>>>> 6c1073ead21b30b8524dfe128d0b87ab1e83f603

        echo "Error conectando a la base de datos.";
        exit();
    }
    mysql_query("SET NAMES 'utf8'");
    return $link;
}

function desconectar()
{
    mysql_close();
}

extract($_SESSION);

