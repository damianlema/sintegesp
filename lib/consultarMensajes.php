<?
session_start();
include("../conf/conex.php");
Conectarse();
$conexion = Conectarse();
extract($_POST);

?>
&nbsp;
                &nbsp;
                <a href='modulos/mensajes/verBandeja.php' target = 'main' class="Estilo8">
                <?
                $sql_mensajes = mysql_query("select 
												COUNT(mc.idusuario_envia) as cantidad
											FROM 
											mensajes_correo mc
											INNER JOIN lista_usuarios_mensajes_correo lu ON 
												(lu.idmensajes_correo = mc.idmensajes_correo and idusuario = '".$_SESSION["login"]."')
											INNER JOIN usuarios us ON (lu.idusuario = us.login)
											WHERE
											(mc.estado = 'recibido')");
				$bus_mensajes = mysql_fetch_array($sql_mensajes);
											
				?>
                Correo <? if($bus_mensajes["cantidad"] > 0){echo "<strong style='color:#FF0000'>(".$bus_mensajes["cantidad"].")</strong>";}?></a>