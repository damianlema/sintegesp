<?php
session_start();
/*****************************************************************************
 * @CONTROL DE ACCESO DE USUARIOS
 * @versión: 1.0
 * @fecha creación: 16/05/2008
 * @autor: Hector Lema
 ******************************************************************************
 * @fecha modificacion
 * @autor
 * @descripcion
 ******************************************************************************/
$_SESSION["nuevo"] = 1;
//$_SESSION["nivel_acceso"]=1;

include "conf/conex.php"; // conectar a la base de datos
Conectarse();
//include("conf/class.Conexion.php"); // conectar a la base de datos

include_once "funciones/funciones.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>

	    <link rel="shortcut icon" href="imagenes/icono_sistema.ico" type="image/x-icon" />
		<title>.:: SINTEGESP ::. Sistema Integrado de Gesti&oacute;n P&uacute;blica</title>

		<link href="css/theme/green/main.css" rel="stylesheet" type="text/css"> <!carga la hoja de estilo>

		<script language="javascript" type="text/javascript">
			document.oncontextmenu=new Function("return false")
			function valida_envia(){
				//script para validar que el usuario introdujo datos en el formulario
				if (document.acceso.login.value.length==0){
					alert("Debe escribir un Nombre de Usuario.")
					document.acceso.login.focus()
					return false;
				}
				if (document.acceso.clave.value.length==0){
					alert("Debe escribir una Clave de Acceso.")
					document.acceso.clave.focus()
					return false;
				}
			}
		</script>
	    <style type="text/css">
			<!--
			.Estilo4 {
				color: #FF0000;
				font-weight: bold;
			}
			.Estilo5 {
				color: #FFFFFF;
				font-weight: bold;
			}
			.Estilo6 {font-size: 10px}
			.Estilo7 {
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 10px;
			}
			body {
				background-color: #EAEAEA;
			}
			.Estilo8 {
				font-family: verdana, arial, helvetica, geneva, sans-serif;
				font-size: 10px;
			}
	    </style>
	    <meta charset="utf-8"">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximun-scale=1" />

	</head>



<?php

if (!$_POST) {
    // Si no hay variables enviadas por el formulario !

    ?>

<body>

	<h2 class="sqlmVersion"></h2>
	<br><br><br><br><br><br><br><br><br><br><br>
	<table width="27%" height="244" align="center" cellpadding="0" cellspacing="0" class="home" style="border:#CCCCCC 4px solid; -moz-border-radius: 15px;">
		<tr>
			<td class="boxtitle Estilo5">
				<table width="100%">
					<tr>
						<td width="39%">&nbsp;</td>
						<td width="33%"><strong style="color:#FFFFFF">Bienvenido</strong></td>
						<td width="28%" align="right">
							<a href='#' class="Estilo8" onClick="window.top.close()" style="color:#FFFFFF"><img src="imagenes/delete_x.gif" border="0"></a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<form
					name     ="acceso"
					action   ="acceso.php"
					enctype  ="multipart/form-data"
					method   ="POST"
					onSubmit ="return valida_envia()"
					target   ="_parent">
					<table width="100%" height="100%"align="center" bgcolor="#FFFFFF">
						<tr>
							<td width="116" rowspan="2" align="right"><img src="imagenes/prueba_logo.png"></td>
							<td width="116" height="71" align="right" valign="bottom">Usuario:&nbsp;</td>
							<td width="120" valign="bottom"><input type="text" class="text" name="login" value="" size="20" autocomplete="OFF"></td>
						</tr>
						<tr>
							<td align="right" valign="top">Clave:&nbsp;</td>
							<td align="center" valign="top"><input type="password" class="text" name="clave" value="" size="20">
								<br>
								<br>
								<input type="submit" value="Iniciar Sesi&oacute;n" class="button"></td>
						</tr>
						<tr>
							<td colspan="3" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td height="24" align="left"><a href="#" class="Estilo6" onClick="window.open('modulos/inicio/usuarios.php','','resizable=no, scrollbars=yes, width=400, height=300')">Crear nuevo usuario </a></td>
							<td colspan="2" rowspan="2" align="left"><span class="Estilo6">Para Ingresar al Sistema Escriba el Nombre de Usuario y Clave que le Facilito el Administrador</span></td>
						</tr>
						<tr>
							<td align="left"><a href='#' class="Estilo7" onClick="window.open('modulos/inicio/recordar_contrasenia.php','','resizable = no, scrollbars=yes, width=400, height=300')">Recordar Contrase&ntilde;a </a></td>
						</tr>
					</table>
				</form>
			</td>
		</tr>
	</table>

	<div align="center">
	  <br><br><br>
        Copyleft &copy; 2009 La red.com, c.a.<br>
        <span style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px">RIF: J-31421362-8</span><br>
          	Direcci&oacute;n: Calle Pedernales Casa N&ordm; 12 Local La red.com <br>
          	Urb. Delfin Mendoza
  			Tucupita, Estado Delta Amacuro<br>
  			Hecho en Venezuela<br>
          	Tlf: (0287) 721-31-44<br>
      	  <a href="http://www.sintegesp.com" class="Estilo4">www.sintegesp.com</a><br>
    </div>
	<script> document.acceso.login.focus() </script>
</body>

<?php
} else {
    $pc          = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    $fh          = date("Y-m-d H:i:s");
    $login       = mysql_real_escape_string(htmlspecialchars($_POST["login"]));
    $clave       = mysql_real_escape_string(htmlspecialchars(md5($_POST["clave"])));
    $conexion_db = conectarse(); // llama a la funcion para conectar a la base de datos que esta en el archvio conex.php
    $valida      = mysql_query("select * from usuarios
										where login = '" . $login . "'
										and clave = '" . $clave . "'", $conexion_db)or die(mysql_error());

    if (mysql_num_rows($valida) <= 0) {

        registra_transaccion('Inicio de Session ERROR (' . $_POST["login"] . ')', $_POST['login'], $fh, $pc, 'inicio_sesion_fallido', $conexion_db);
        mensaje("Disculpe Acceso Incorrecto"); // envia el codigo de error al archivo manejador de errores error.php
        redirecciona("marcos.html?error=1");

    } else {

        $resultado         = mysql_fetch_assoc($valida);
        $sql_configuracion = mysql_query("select * from configuracion");
        $bus_configuracion = mysql_fetch_array($sql_configuracion);
        $cedula            = $resultado["cedula"];
        $nivel             = $resultado["nivel"];
        $login             = $resultado["login"];

        $_SESSION["directorio_root"] = '/gestion_git'; //.$bus_configuracion["anio_fiscal"];
        $_SESSION["nivel"]           = $resultado["nivel"];
        $_SESSION["login"]           = $login;
        $_SESSION["fh"]              = $fh;
        $_SESSION["pc"]              = $pc;
        $_SESSION["ID_SESION"]       = session_id();
        $_SESSION["cedula_usuario"]  = $cedula; //carga en el buffer de sesion la cedula del usuario para usarla en otros modulos
        $_SESSION["anio_fiscal"]     = $bus_configuracion["anio_fiscal"];
        $_SESSION["rutaReportes"]    = $bus_configuracion["ruta_reportes"];
        $_SESSION["version"]         = $bus_configuracion["version"];
        $_SESSION["mos_dis"]         = $bus_configuracion["disponibilidad"];
        $_SESSION["numero_entradas"]++;

        $privilegios     = array();
        $i               = 0;
        $sql_privilegios = mysql_query("select * from privilegios_acciones where id_usuario = '" . $_SESSION["cedula_usuario"] . "'") or die(mysql_error());
        while ($bus_privilegios = mysql_fetch_array($sql_privilegios)) {
            $privilegios[$i] = $bus_privilegios["id_accion"];
            $i++;
        }

        $_SESSION["privilegios"] = $privilegios;
        mysql_query("insert into historico_usuarios
								(estacion,fechayhora,usuario)
							values ('$pc','$fh','$login')",
            $conexion_db); //almacena en tabla de historico de connexiones los datos del usuario que inicio sesion
        mysql_query("update usuarios
								set estacion='" . $pc . "',
								estado = 'a'
									where cedula=$cedula", $conexion_db); // modifica el estatus del usuario para indicar que tiene sesion abierta
        mysql_query("delete from conversaciones_chat where
											usuario_recepcion = '" . $_SESSION["login"] . "'
											or usuario_apertura = '" . $_SESSION["login"] . "'");

        // RESPALDO DE TRANSACCIONES
        $sql_consulta       = mysql_query("select * from usuarios where cedula='" . $cedula . "'") or die(mysql_error());
        $bus_consulta       = mysql_fetch_array($sql_consulta);
        $anio_actual        = $_SESSION["anio_fiscal"];
        $mes_actual         = date("m");
        $arregloMeses["01"] = "enero";
        $arregloMeses["02"] = "febrero";
        $arregloMeses["03"] = "marzo";
        $arregloMeses["04"] = "abril";
        $arregloMeses["05"] = "mayo";
        $arregloMeses["06"] = "junio";
        $arregloMeses["07"] = "julio";
        $arregloMeses["08"] = "agosto";
        $arregloMeses["09"] = "septiembre";
        $arregloMeses["10"] = "octubre";
        $arregloMeses["11"] = "noviembre";
        $arregloMeses["12"] = "diciembre";

        /*

        $sql_transacciones = mysql_query("select * from gestion_".$anio_actual.".registro_transacciones where usuario = '".$bus_consulta["login"]."'")or die(mysql_error());
        $num_transacciones = mysql_num_rows($sql_transacciones);
        if($num_transacciones > 0){
        while($bus_transacciones = mysql_fetch_array($sql_transacciones)){
        $sql_respaldar = mysql_query("insert into gestion_respaldo_".$anio_actual.".".$arregloMeses[$mes_actual]."
        (tipo, tabla, usuario, fechayhora, estacion)
        values
        ('".$bus_transacciones["tipo"]."',
        '".$bus_transacciones["tabla"]."',
        '".$bus_transacciones["usuario"]."',
        '".$bus_transacciones["fechayhora"]."',
        '".$bus_transacciones["estacion"]."')");
        $sql_eliminar = mysql_query("delete from gestion_".$anio_actual.".registro_transacciones where
        idregistro_transacciones = ".$bus_transacciones["idregistro_transacciones"]."");
        }
        }

         */

        // RESPALDO DE TRANSACCIONES

        registra_transaccion('Inicio de Session (' . $login . ')', $login, $fh, $pc, 'inicio_sesion_exitoso', $conexion_db);

        //SE CREAN LOS FRAMES PARA DIVIDIR LA PANTALLA DONDE SE MOSTRARAN EL ENCABEZADO, EL MENU PRINCIPAL, EL SUB-MENU Y LA VENTANA DE PROCESOS
        echo "<frameset rows='58,*' cols='*' frameborder='NO' border='0' framespacing='0'>
		       <frame src='frame_encabezado.php' name='frame_encabezado' scrolling='NO' noresize>";
        echo "<frameset cols='185,*' frameborder='NO' border='0' framespacing='0'>";
        echo "<frame src='lib/principal/menu.php' name='menuFrame' noresize>";
        echo "<frameset rows='38,*' cols='*' frameborder='NO' border='0' framespacing='0'>";
        echo "<frame src='' name='menutab' noresize>";
        echo "<frame src='lib/principal/fondo_main.php' name='main' noresize>";
        echo "</frameset>";
        echo "</frameset>
		</frameset>";

    }
    mysql_close($conexion_db); // cierra la conexion a la base de datos
}
?>
</html>