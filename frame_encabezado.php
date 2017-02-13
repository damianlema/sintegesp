<?
session_start();
include("conf/conex.php");
Conectarse();

if($_SESSION){
$buscar=mysql_query("select * from usuarios 
								where cedula= '".$_SESSION['cedula_usuario']."'")or die(mysql_error());
$registro_usuario=mysql_fetch_array($buscar);
}
?>
<script src="js/function.js" type="text/javascript" language="javascript"></script>

<script>
function consultarMensajes(){
	setInterval("consultarTotalMensajes()",10000);
}


function consultarTotalMensajes(){
	var ajax = nuevoAjax();
	ajax.open("POST", "lib/consultarMensajes.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("cuadro_mensajes").innerHTML = "Cargando...";
		}
		if (ajax.readyState==4){
			document.getElementById("div_mensajes").innerHTML = ajax.responseText;
		} 
	}
	ajax.send(null);
}
</script>
<script LANGUAGE="JavaScript1.1">
document.oncontextmenu=new Function("return false")
</script> 
<html>
<head>
       
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">
<style type="text/css">
<!--
.Estilo5 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; text-decoration: none;}
.Estilo7 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; font-weight: bold; }
.Estilo8 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; text-decoration: none; font-weight:bold}
.Estilo8:hover {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; text-decoration: none; font-weight:normal}
-->
</style>
	<body background="imagenes/encabeza.jpg" style="background-repeat:no-repeat" bgcolor="#000000" onLoad="consultarTotalMensajes(), consultarMensajes()">
    <br>
<?
if($_SESSION){
?>
    <table align='right' >
    	
		<tr>
		  <td align='right'><span class="Estilo7">Usuario:</span></td>
		  <td align='right' class="Estilo5"><?=$registro_usuario["nombres"]." ".$registro_usuario["apellidos"]?></td>
		  <td align='center' style="border-left:#FFFFFF solid 1px">
          	<a HREF='#' class="Estilo8" onClick="cerrarSession('botonCerrar')">&nbsp;&nbsp;Cerrar Sesi&oacute;n</a>
          </td>
		  <td align='center' style="border-left:#FFFFFF solid 1px">
          	<a HREF='#' onClick="window.open('modulos/inicio/cambiar_contrasenia.php','','resizable = no, scrollbars = yes, width = 400, height = 200')" style="color:#FFFFFF;" class="Estilo8">
            	&nbsp;&nbsp;
            Cambiar Contrase&ntilde;a</a>
          </td>
        	<td align='center' style="border-left:#FFFFFF solid 1px">
            	<span class="Estilo5" id="div_mensajes"></span>
            </td>
            <td align='center' style="border-left:#FFFFFF solid 1px">
            	<span class="Estilo5">
&nbsp;
&nbsp;
<?
                    $sql_configuracion = mysql_query("select anio_fiscal from configuracion");
                    $bus_configuracion = mysql_fetch_array($sql_configuracion);	
                    ?>
A&ntilde;o Fiscal:
<?=$bus_configuracion["anio_fiscal"]?>
                    </span>
            </td>
   			 <td align='center' style="border-left:#FFFFFF solid 1px">
			 <span class="Estilo5">&nbsp;&nbsp;Versi&oacute;n 3.51</span>
             
             </td>
	</tr></table>
 <?
 }
 ?>  
</body>
</html>