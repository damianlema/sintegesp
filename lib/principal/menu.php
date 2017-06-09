<?php
session_start();
?>

<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
<?
include ("../../conf/conex.php");
$conexion_db=conectarse();
$buscar=mysql_query("select * from usuarios
								where cedula=".$_SESSION['cedula_usuario']
								,$conexion_db);
$registro_usuario=mysql_fetch_assoc($buscar);
$ced=$registro_usuario["cedula"];
$_SESSION["nivel_acceso"]=$registro_usuario["nivel"];
$_SESSION["login"]=$registro_usuario["login"];
$fila=0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
        <head>
        <title></title>
		<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">
        <style type="text/css">
<!--
.Estilo4 {
	color: #FF0000;
	font-weight: bold;
}
body {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        margin: 0;
        font-size: 70%;
        font-weight: bold;
        /*font-color: #FFFFFF;*/
        background: url(../../imagenes/verti.jpg);
        }

ul {
        list-style: none;
        margin: 0;
        padding: 0;
        }

img {
    border: none;
}

/*- Menu 11--------------------------- */

#menu11 {
        width: 150px;
        margin: 10px;
        border-style: solid solid none solid;
        border-color: #889944;
        /*border-size: 1px;*/
        border-width: 1px;
        }

#menu11 li a {
        height: 32px;
          /*voice-family: "\"}\"";
          voice-family: inherit;*/
          height: 24px;
        text-decoration: none;
        }

#menu11 li a:link, #menu11 li a:visited {
        color: #242424;
        display: block;
        background:  url(../../imagenes/menu11.gif);
        padding: 8px 0 0 15px;
        }

#menu11 li a:hover, #menu11 li #current {
        color: #242424;
        background:  url(imagenes/menu11.gif) 0 -32px;
        padding: 8px 0 0 15px;
        }
.Estilo6 {
	font-size: 10;
	color: #FFFFFF;
}

.linkNombres{
text-decoration:none;
cursor:pointer;
}

.linkNombres:hover{
color:#0066FF;
text-decoration:underline;
}

-->
        </style>
        <script>
		function nuevoAjax(){
				var xmlhttp=false;
				try{
					xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
				}catch(e){
					try{
						xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
					}catch(E){
						if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
					}
				}
				return xmlhttp;
			}

		ClosingVar =true
		window.onbeforeunload = ExitCheck;
		function ExitCheck(){
		///control de cerrar la ventana///
		var num = document.getElementById('num_validacion_cerrar').value;
		 if(ClosingVar == true){
			document.getElementById('num_validacion_cerrar').value = parseInt(document.getElementById('num_validacion_cerrar').value) + 1;
			if(parseInt(document.getElementById('num_validacion_cerrar').value) == 2){
				ExitCheck = false;
				//alert(ajax.responseText);
				var ajax=nuevoAjax();
					ajax.open("POST", "cerrar.php", true);
					ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
					ajax.send(null);
			}else{
			//alert("AQUi");
			setTimeout("document.getElementById('num_validacion_cerrar').value = num;", 5000);
			}
		  }
		}

		function verificarChat(){
			setInterval("revisarNuevasConversaciones('<?=$_SESSION["login"]?>')",10000);
			setInterval("revisarNuevosConectados()",5000);
		}

		function revisarNuevosConectados(){
				var ajax		= nuevoAjax();
				ajax.open("POST", "../../modulos/chat/lib/ventanaChat_ajax.php", true);
				ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
				ajax.onreadystatechange=function() {
					if(ajax.readyState == 1){}
					if (ajax.readyState==4){
					//alert(ajax.responseText);
							document.getElementById('contenido_lista_contactos').innerHTML = ajax.responseText;
					}
				}
				ajax.send("ejecutar=revisarNuevosConectados");
		}

		function revisarNuevasConversaciones(login){
				var ajax		= nuevoAjax();
				ajax.open("POST", "../../modulos/chat/lib/ventanaChat_ajax.php", true);
				ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
				ajax.onreadystatechange=function() {
					if(ajax.readyState == 1){}
					if (ajax.readyState==4){
					//alert(ajax.responseText);
							if(ajax.responseText != 0){
								popUP = window.open('../../modulos/chat/ventanaChat.php?ua='+ajax.responseText+'&ur=<?=$_SESSION["login"]?>', 'venta_chat_'+ajax.responseText+"", 'width=350, height=350, scrollbars=no, resizable=no,location=no,menubar=no');
							}
					}
				}
				ajax.send("lo="+login+"&ejecutar=revisarNuevasConversaciones");
		}

		</script>

        </head>

        <body onLoad="verificarChat()">
            <input type="hidden" name="num_validacion_cerrar" id="num_validacion_cerrar" value="1">
				<h2 class="sqlmVersion"></h2>
				<br>
                <div id="contactos_msn" style="background-color:#FFFFFF; padding:2px; width:158px; height:95%; border:#333333 solid 3px; position:absolute; display:none; font-size:10px; font-weight:normal; font-family:Arial, Helvetica, sans-serif">
                    <div align="center" style="background-color:#666666; color:#FFFFFF"><strong style="font-weight:bold; font-size:14px">CHAT ONLINE</strong></div>
                    <div align="right">
                        <a href="javascript:;" onClick="document.getElementById('contactos_msn').style.display = 'none'" style="text-decoration:none"><strong>Cerrar (X)</strong></a>
                    </div>
	                <br />
                    <div style="overflow:auto; width:100%; height:93%" id="contenido_lista_contactos">
				<ul>
                <?
                $sql_contactos = mysql_query("select * from usuarios where status = 'a' and estado = 'a' and login != '".$_SESSION["login"]."'");
				$num_contactos = mysql_num_rows($sql_contactos);
				if($num_contactos > 0){
				while($bus_contactos = mysql_fetch_array($sql_contactos)){
					?>

                    <li style="padding:3px; cursor:pointer" id="nombre_contacto_<?=$bus_contactos["cedula"]?>" onMouseOver="this.style.color='#0066FF', this.style.textDecoration='underline'; this.style.backgroundColor= '#EAEAEA'" onMouseOut="this.style.color='#000', this.style.textDecoration='none', this.style.backgroundColor= '#FFF'" onClick="window.open('../../modulos/chat/ventanaChat.php?ua=<?=$login?>&ur=<?=$bus_contactos["login"]?>', 'venta_chat_<?=$bus_contactos["login"]?>', 'width=350, height=350, scrollbars=no, resizable=no')">
                    -<?=substr($bus_contactos["apellidos"]." ".$bus_contactos["nombres"],0,20)?>
                    </li>

					<?
				}
				}else{
					echo "<strong><center>NO HAY USUARIOS CONECTADOS</center></strong>";
				}
				?>
                </ul>
                	</div>
                </div>
                <div id="menu11">
                        <ul>
<?php
$sql_modulos = mysql_query("select * from modulo where mostrar = 'si' and id_modulo != 18 order by orden");
while($bus_modulos = mysql_fetch_array($sql_modulos)){
	$sql_permisos = mysql_query("select * from privilegios_modulo where id_modulo = ".$bus_modulos["id_modulo"]." and id_usuario = ".$_SESSION["cedula_usuario"]."");
	$num_permisos = mysql_num_rows($sql_permisos);
		if($num_permisos > 0){
			echo "<li><a href='menuTop.php?modulo=".$bus_modulos["id_modulo"]."' target='menutab'>".$bus_modulos["nombre_modulo"]."</a></li>";
		}
}

if($_SESSION["nivel"] == 'A'){
	?>
    <li>
    	<a href='javascript:;' onClick="window.open('../../modulos/admin_sistema/index.php', 'ventana_administracion','statusbar=no')">
        	Admin. del Sistema
        </a>
   </li>
   <?
}

?>
                    </ul>
                </div>
                <?
                $sql_permisos = mysql_query("select * from privilegios_modulo where id_modulo = '18' and id_usuario = ".$_SESSION["cedula_usuario"]."");
	$num_permisos = mysql_num_rows($sql_permisos);
		if($num_permisos > 0){
				?>
                <div align="center" id="boton_mostrar_chat" style="border:#666666 solid 4px; cursor:pointer; padding:5px; text-decoration:none; background-color:#CCCCCC; color:#000000" onClick="document.getElementById('contactos_msn').style.display = 'block'" onMouseOver="this.style.backgroundColor='#0066CC', this.style.color='#FFF'" onMouseOut="this.style.backgroundColor='#CCC', this.style.color='#000'">MOSTRAR CHAT ONLINE</div>
                <?
                }
				?>
				<br />
				<table align='center'>
  <tr >
   <td align="center"><img src="../../imagenes/prueba_logo2.png" width="160" height="83" /><br />
    <br />
    <span class="Estilo6" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; color:#FFFFFF">Copyleft &copy; 2009 <br />
La red.com, c.a.<br />
<span style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px">RIF: J-31421362-8</span><br>

   	  <a href="http://www.sintegesp.com" target="_blank" class="Estilo4">www.sintegesp.com</a></span><br>
</td>
  </tr>
  </table>
</body>
</html>