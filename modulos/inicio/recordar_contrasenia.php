<?php
include_once("../../conf/conex.php");
include_once("../../funciones/funciones.php");
$conexion_db=conectarse();
//if (isset($_POST["validar_cedula"])){
	if ($_POST["cedula"]<>""){
		$cedula_validar=$_POST["cedula"];
		$sql_validar_cedula=mysql_query("select * from usuarios 
														where cedula=".$cedula_validar." and status='a' and login <> 'administrador'"
															,$conexion_db);
		if (mysql_num_rows($sql_validar_cedula)<=0)
		{
			mensaje("No existe usuario con esa C&eacute;dula de Identidad");
			?><script>window.close()</script><?
			$existe=false;
		}else{
			$existe=true;
			$registro_usuario=mysql_fetch_assoc($sql_validar_cedula);
			$apellidos=$registro_usuario["apellidos"];
			$nombres=$registro_usuario["nombres"];
			$pregunta=$registro_usuario["preguntasecreta"];
			$respuesta=$registro_usuario["respuestasecreta"];
			$login=$registro_usuario["login"];
			$sql_actualizar = mysql_query("update usuarios set clave = ".md5("1234")." where cedula = '".$cedula_validar."'");
			$clave=1234;
			$mensaje = "* Su clave ha sido actualizada a 1234, ingrese al sistema con esta clave y vuelva a cambiarla";
		}
	}
//}

if (!isset($_POST["ejecutar_accion"])){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="js/function.js" type="text/javascript" language="javascript"></script>
	</head>
	
	<body>
	<table align=left cellpadding=2 cellspacing=0>
		<tr>
			<td align='center'><a HREF='#' onClick="window.close()">Volver a Acceso</a></td>
		</tr>
	</table>
	<br>
	<h4 align=center>Recordar Contrase&ntilde;a</h4>
	<h2 class="sqlmVersion"></h2>
	
	<form name="recordar_contrasenia" action="recordar_contrasenia.php" method="POST" onSubmit="return valida_envia()">	
	
	<table align=center cellpadding=2 cellspacing=0>

		<tr><td align='right' class='viewPropTitle'>C&eacute;dula:</td>
			<td class=''><input type="text" name="cedula" maxlength="12" size="12" <?php if (isset($_POST["cedula"])){ echo "value=".$_POST["cedula"]; }?>>
				<button name="validar_cedula" type="button" style="background-color:white;border-style:none;cursor:hand;" onClick="this.form.submit()"><img src='../../imagenes/validar.png'>
			</button>
		
				</td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Apellidos:</td>
				<td class=''><input type="text" name="apellidos" <?php if (isset($_POST["cedula"]) and $_POST["cedula"]<>"" and $existe) { echo 'value="'.$apellidos.'"'; }else{ echo "disabled";}?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Nombres:</td>
				<td class=''><input type="text" name="nombres" <?php if (isset($_POST["cedula"]) and $_POST["cedula"]<>"" and $existe) { echo 'value="'.$nombres.'"';}else{ echo "disabled";}?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Pregunta Secreta:</td>
				<td class=''><input type="text" name="pregunta" <?php if (isset($_POST["cedula"]) and $_POST["cedula"]<>"" and $existe) { echo 'value="'.$pregunta.'"'; }else{ echo "disabled";}?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Respuesta:</td>
				<td class=''><input type="text" name="respuesta" <?php if (isset($_POST["cedula"]) and $_POST["cedula"]<>"") { echo "enabled"; }else{ echo "disabled";}?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Nombre de Usuario:</td>
				<td class=''><input type="text" name="newlogin" maxlength="20" size="20" disabled></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Clave:</td>
				<td class=''><input type="password" name="clave" maxlength="20" size="20" disabled></td>
			</tr>
			
	</table>
	
	<table align=center cellpadding=2 cellspacing=0>
		<tr><td>
				<input align=center name="ejecutar_accion" type="submit" value="Comprobar" <?php if (isset($_POST["cedula"]) and $_POST["cedula"]<>"") { echo "enabled"; }else{ echo "disabled";}?>>
				<input type="button" value="Cancelar" onClick="window.close()">
		</td></tr>
	</table>
	</form>
	<br>

	
	<?php 
		if (!isset($_POST["cedula"]) or $_POST["cedula"]==""){
			echo "<script> document.recordar_contrasenia.cedula.focus() </script>";
		}else{
			echo "<script> document.recordar_contrasenia.respuesta.focus() </script>";
		}
	
	?>
</body>
</html>
<?php
}else{

	if ($_POST["respuesta"]<>$respuesta){
		mensaje("Disculpe la respuesta es incorrecta");
		?><script>window.close()</script><?
	}else{
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="js/function.js" type="text/javascript" language="javascript"></script>
	</head>
	
	<body>
	<br>
	<h4 align=center>Recordar Contrase&ntilde;a</h4>
	<h2 class="sqlmVersion"></h2>
	
	<form name="recordar_contrasenia" action="recordar_contrasenia.php" method="POST" onSubmit="return valida_envia()">	
	
	<table align=center cellpadding=2 cellspacing=0>

		<tr><td align='right' class='viewPropTitle'>C&eacute;dula:</td>
			<td class=''><input type="text" name="cedula" maxlength="12" size="12" <?php if (isset($_POST["cedula"])){ echo "value=".$_POST["cedula"]; }?> disabled>
		
				</td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Apellidos:</td>
				<td class=''><input type="text" name="apellidos"  <?php echo 'value="'.$apellidos.'"'; echo " disabled";?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Nombres:</td>
				<td class=''><input type="text" name="nombres"  <?php echo 'value="'.$nombres.'"'; echo " disabled";?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Pregunta Secreta:</td>
				<td class=''><input type="text" name="pregunta" <?php echo 'value="'.$pregunta.'"'; echo " disabled";?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Respuesta:</td>
				<td class=''><input type="text" name="respuesta"  <?php echo 'value="'.$respuesta.'"'; echo " disabled";?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Nombre de Usuario:</td>
				<td class=''><input type="text" name="newlogin"  disabled <?php echo 'value="'.$login.'"'; ?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Clave:</td>
				<td class=''><input type="text" name="clave"  disabled <?php echo "value=".$clave; ?>></td>
			</tr>
            <tr>
				<td class='' colspan="2" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; color:#990000"><?=$mensaje?></td>
			</tr>
			
	</table>
	<table align=center cellpadding=2 cellspacing=0>
		<tr><td>
				<a HREF='#' onClick="window.close()">Volver al acceso</a>
		</td></tr>
	</table>
	</form>
	</body>
</html>
	<?php
	}
}
mysql_close($conexion_db);
?>
