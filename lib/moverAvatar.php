<?php
//Session activa
session_start();

//Funcion de renombre de archivo
rename("../imagenes/tmp/".$_POST['nombre'], "../imagenes/tmp/".md5($_SESSION['cedula_usuario']).".png");

//Funcion de copiar archivo a otro directorio
copy("../imagenes/tmp/".md5($_SESSION['cedula_usuario']).".png", "../imagenes/avatar/".md5($_SESSION['cedula_usuario']).".png");

//Funcion de eliminar archivo
unlink("../imagenes/tmp/".md5($_SESSION['cedula_usuario']).".png");

?>