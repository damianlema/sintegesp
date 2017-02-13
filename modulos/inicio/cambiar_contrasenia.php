<?php
session_start();
ob_start();
include_once("../../conf/conex.php");
include_once("../../funciones/funciones.php");
$conexion_db=conectarse();
$usuario = $_SESSION["login"];


		$sql_validar_cedula=mysql_query("select * from usuarios 
														where login='".$usuario."' and status='a' and login <> 'administrador'");
		

			$registro_usuario=mysql_fetch_assoc($sql_validar_cedula);
			$apellidos=$registro_usuario["apellidos"];
			$nombres=$registro_usuario["nombres"];
			$pregunta=$registro_usuario["preguntasecreta"];
			$respuesta=$registro_usuario["respuestasecreta"];
			$login=$registro_usuario["login"];
			$clave=$registro_usuario["clave"];

if ($usuario <> "administrador"){
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
    <script src="js/cambiar_contrasena_ajax.js" type="text/javascript" language="javascript"></script>
    <script src="../../js/validarCampos.js" type="text/javascript" language="javascript"></script>
	</head>
	
	<body>
	<h4 align=center>Cambiar Contrase&ntilde;a</h4>
	<h2 class="sqlmVersion"></h2>
	
	<form id="cambiar_contrasenia" action="" method="POST">	

<table align=center cellpadding=2 cellspacing=0>
			<tr><td align='right' class='viewPropTitle'>Clave Actual:</td>
				<td class=''><input type="password" name="clave" id="clave" maxlength="20" size="20"></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Nueva Clave:</td>
				<td class=''><input type="password" name="clavenew1" id="clavenew1" maxlength="20" size="20"></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Confirme Nueva Clave:</td>
				<td class=''><input type="password" name="clavenew2" id="clavenew2" maxlength="20" size="20"></td>
			</tr>
	</table>
 
<table align=center cellpadding=2 cellspacing=0>
		<tr><td>
				<input align="center" name="ejecutar_accion" type="button" value="Modificar" id="ejecutar_accion" onClick="cambiarClave(document.getElementById('clave').value, document.getElementById('clavenew1').value, document.getElementById('clavenew2').value)">
				<input type="button" value="Cerrar" onClick="window.close()">
		</td></tr>
	</table>
	</form>
	<br>
	<?
    }
	?>
</body>
</html>