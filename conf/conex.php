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

    if (!mysql_select_db("gestion_gobernacion_2017", $link)) {

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
