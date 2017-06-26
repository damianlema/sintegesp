	<?php
	session_start();
	include("../../conf/conex.php");
	Conectarse();
	extract($_POST);
	extract($_GET);

	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Documento sin t&iacute;tulo</title>



	<script type="text/javascript" src="../../js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="../../js/jquery.textareaCounter.plugin.js"></script>
	<script type="text/javascript" src="../../js/microbloging.js"></script>


	<script type="text/javascript" src="../../js/function.js"></script>
	<script type="text/javascript" src="../../js/calendar/calendar.js"></script>
	<script type="text/javascript" src="../../js/calendar/calendar-setup.js"></script>
	<script type="text/javascript" src="../../js/calendar/lang/calendar-es.js"></script>

	<script>
	function procesarRetornoBien(id){
		if(confirm("Seguro desea procesar este retorno del bien?")){
			var ajax			= nuevoAjax();
			ajax.open("POST", "../../modulos/bienes/lib/movimientos_ajax.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() {
				if(ajax.readyState == 1){}
				if (ajax.readyState==4){
					window.location.href = 'fondo_main.php';
				}
			}
			ajax.send("id="+id+"&ejecutar=procesarRetornoBien");
		}
	}



	function reprocesarFechaRetorno(id, nueva_fecha){
		if(confirm("Seguro desea modificar la fecha de retorno de este bien?")){
			var ajax = nuevoAjax();
			ajax.open("POST", "../../modulos/bienes/lib/movimientos_ajax.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() {
				if(ajax.readyState == 1){}
				if (ajax.readyState==4){
				alert(ajax.responseText);
				}
			}
			ajax.send("id="+id+"&nueva_fecha="+nueva_fecha+"&ejecutar=reprocesarFechaRetorno");
		}
		//window.location.href = 'fondo_main.php';
	}
	</script>


	<style type="text/css"> @import url("../../css/theme/calendar-win2k-cold-1.css");</style>


	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">

	</head>

	<body>

	<table align="center" width="90%">
	<tr>
	    <td width="50%">
	    <br />
	    <img src="../../imagenes/sintegesp.png" width="38%" align="center" >
	    <br />
	    <br />
	    </td>
	    <td width="50%" align="right">
	    <?
			$sql_configuracion = mysql_query("select * from configuracion_logos");
			$bus_configuracion = mysql_fetch_array($sql_configuracion);
		?>
	    <img width="20%" align="center" src="../../imagenes/<?=$bus_configuracion["logo"]?>" >    </td>
	</tr>
	<tr>
	<td colspan="2">
	<style>
	.div{
		background-color:#EAEAEA;
		border:#333333 solid 1px;
		overflow:auto;
		font-family:Arial, Helvetica, sans-serif;
		font-size:14px;
		padding:10px;
	}

	.div:hover{
		background-color:#FFFFEE;

	}


	</style>
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
					$bus_mensajes = @mysql_fetch_array($sql_mensajes);
					$sql_usuario = mysql_query("select * from usuarios where login = '".$_SESSION["login"]."'");
					$bus_usuario = mysql_fetch_array($sql_usuario);

					if(date("H") > 12){
						$mensaje = "Buenas Tardes";
					}else{
						$mensaje = "Buenos Dias";
					}

					if($bus_mensajes["cantidad"] != 0){
						?>
						<div class="div"><?=$mensaje?> Sr(a). <?=$bus_usuario["nombres"]." ".$bus_usuario["apellidos"]?>, Tienes <a href="../../modulos/mensajes/verBandeja.php" style="text-decoration:none"><strong style="font-size:18px;">(<?=$bus_mensajes["cantidad"]?>)</strong></a> Correos sin Leer!</div>
						<?
					}else{
						?>
						<div class="div"><?=$mensaje?> Sr(a). <?=$bus_usuario["nombres"]." ".$bus_usuario["apellidos"]?>, <strong>NO</strong> tienes Correos sin leer</div>
						<?
					}

	?>
	</td>
	</tr>
	<tr>
		<td rowspan="2">
	    	<div style="height:435px" class="div">
			    <?
			    // INICIO DEL MICROBLOGING
			    ?>
	    		<div class="dejarMensaje" style="width: 55px; top: -72px; font-size: 12px; cursor:pointer;">
	    			<img src="../../imagenes/Comment_edit.png" title="Deja tu mensaje">
	    		</div>
	    		<span class="areaTexto" style="display: none;" >
					<textarea id='texto' class="texto" cols='55' rows='3'></textarea>

					<button id='publicar' style="margin-left: 286px;">Publicar</button>
				</span>
	    		<div class="cambiarAvatar" style="position: relative; left: 445px; width: 35px; top: -42px; font-size: 12px; cursor:pointer;">
	    			<img src="../../imagenes/client.png" title="Cambia tu Imagen">
	    		</div>

				<span class="notificacion" ></span>

				<div class="mensajes"></div>
				<div class="anteriores" style="display: none; left: 350px; font-size: 12px; width: 137px; cursor:pointer; position: relative">Mensajes Anteriores</div>
	    	</div>

	    	<div id="avatar" align="center" style="display:none; height: 125px; position: absolute; width: 385px; top: 245px; left: 115px;" class="div">
	    		<form id="formAvatar" action="../cargarAvatar.php" method="post" enctype="multipart/form-data" target="upload_target" >
				    <input type="hidden" id="nombreAvatar" name="nombreAvatar" />
				    <?php
				    if(file_exists("../../imagenes/avatar/".md5($_SESSION['cedula_usuario']).".png"))
						{
							$imagen = "../../imagenes/avatar/".md5($_SESSION['cedula_usuario']).".png";
						}
						else
						{
							$imagen = "../../imagenes/avatar/userDefault.png";
						}
				    ?>
				    <input type="hidden" id="nombreAvatarActual" name="nombreAvatarActual" value="<?php echo $imagen; ?>" />
				    <img id="imagenAvatar"  src="<?php echo $imagen; ?>" style="width: 60px; height: 50px; -webkit-border-radius: 5px; border-radius: 5px;" />
				    <br/>
			    	<input name="archivoAvatar" id="archivoAvatar" type="file" />
			    	<div id="iframe"></div>
			    </form>
			    <button id="guardarCambio">Guardar Cambios</button>
			    <button id="cancelarCambioAvatar">Cancelar</button>
	    	</div>
	    </td>


		<td>
		    <div style="height:205px" class="div">
			    <?
				//
				//	MUESTRA LOS CUMPLEAÑEROS DEL DIA
				//
			    list($a, $m, $d) = explode("-", date("Y-m-d"));
			    $sql_consulta = mysql_query("select * from trabajador where fecha_nacimiento like '%".$m."-".$d."%'")or die(mysql_error());
				$num_consulta = mysql_num_rows($sql_consulta);
				if($num_consulta > 0){
				?>

			    <table style="font-family:Arial, Helvetica, sans-serif; font-size:12px; width:100%;">
				    <tr>
					    <td style="font-size:18px; color:#333">
					    	<strong><?=$d."/".$m."/".$a?>&nbsp;&nbsp;|&nbsp;&nbsp;Felicidades a:</strong><br /><br />
					    </td>
				    </tr>
				    <?
					while($bus_consulta = mysql_fetch_array($sql_consulta)){
						list($a1, $m1, $d1) = explode("-", $bus_consulta["fecha_nacimiento"]);
						?>
						<tr>
					        <td><?=$bus_consulta["nombres"]." ".$bus_consulta["apellidos"]?></td>
					        <td>Que hoy cumple <strong><?=($a-$a1)?> Años</strong></td>
				        </tr>
						<?
					}
					?>
			    </table>

			    <?
				}
				?>
		    </div>
	    </td>
	</tr>

	    <td>
	    <div style="height:205px" class="div">
	    <?
		//
		//	MUESTRA LOS QUE INGRESARON A TRABAJAR ESE DIA
		//
	    list($a, $m, $d) = explode("-", date("Y-m-d"));
	    $sql_consulta = mysql_query("select * from trabajador where fecha_ingreso like '%".$m."-".$d."%'")or die(mysql_error());
		$num_consulta = mysql_num_rows($sql_consulta);
		if($num_consulta > 0){
		?>

	    <table style="padding:5px">
	    <tr>
	    <td style="font-size:12px; color:#333">
	    	<strong style="font-size:14px">Un día como hoy ingresaron en nuestra Instituci&oacute;n:</strong><br /> <br />
	    <?
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			list($a1, $m1, $d1) = explode("-", $bus_consulta["fecha_nacimiento"]);
			?>
			<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px"><?=$bus_consulta["apellidos"]." ".$bus_consulta["nombres"]."<br />"?></span>
			<?
		}
		?>
	    <br />
	    <strong style="font-size:14px">Felicidades a todos y gracias por ser parte de este fabuloso equipo de trabajo!</strong>
	    </td>
	    </tr>
	    </table>

	    <?
		}
		?>
	    </div>
	    </td>
	</tr>
	</table>


	<?
	    if($_SESSION["modulo"] == 8 and in_array(1026,$privilegios) == true){

			?>

	        <br />
	<h2 align="center"><strong>Movimientos en retorno</strong></h2>
	<br />


			<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="90%">
			<thead>
	        <tr>
	        <td width="8%" align="center" class="Browse">Nro Movimiento</td>
	        <td width="12%" align="center" class="Browse">Fecha del Movimiento</td>
	        <td width="11%" align="center" class="Browse">Codigo del Bien</td>
	        <td width="46%" align="center" class="Browse">Codigo del Catalogo</td>
	        <td width="12%" align="center" class="Browse">Fecha de retorno</td>
	        <td width="11%" align="center" class="Browse">Procesar</td>
	   		 </tr>
	         </thead>
			<?
			$sql_movimientos = mysql_query("select mb.nro_movimiento,
										   			mb.fecha_movimiento,
													mu.codigo_bien,
													dcb.codigo,
													dcb.denominacion,
													mei.fecha_retorno,
													mei.idmovimientos_existentes_incorporacion as id
														from
										   			movimientos_bienes mb,
										   			movimientos_existentes_incorporacion mei,
													muebles mu,
													detalle_catalogo_bienes dcb
														where
													mei.retorno_automatico = 'si'
													and mu.idmuebles = mei.codigo_bien
													and dcb.iddetalle_catalogo_bienes = mei.codigo_catalogo
													and mb.estado = 'procesado'")or die(mysql_error());
			while($bus_movimientos = mysql_fetch_array($sql_movimientos)){
				?>
				<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
	            	<td class='Browse'><?=$bus_movimientos["nro_movimiento"]?></td>
	                <td class='Browse'><?=$bus_movimientos["fecha_movimiento"]?></td>
	                <td class='Browse'><?=$bus_movimientos["codigo_bien"]?></td>
	                <td class='Browse'>(<?=$bus_movimientos["codigo"]?>)&nbsp;<?=$bus_movimientos["denominacion"]?></td>
	                <td class='Browse'><input type="text" size="12" id="fecha_retorno_<?=$bus_movimientos["id"]?>" name="fecha_retorno_<?=$bus_movimientos["id"]?>" value="<?=$bus_movimientos["fecha_retorno"]?>" onchange="reprocesarFechaRetorno('<?=$bus_movimientos["id"]?>', this.value)">
	                <img src="../../imagenes/jscalendar0.gif" name="img_fecha_retorno_<?=$bus_movimientos["id"]?>" width="16" height="16" id="img_fecha_retorno_<?=$bus_movimientos["id"]?>" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
	            <script type="text/javascript">
	                                    Calendar.setup({
	                                    inputField    : "fecha_retorno_<?=$bus_movimientos["id"]?>",
	                                    button        : "img_fecha_retorno_<?=$bus_movimientos["id"]?>",
	                                    align         : "Tr",
	                                    ifFormat      : "%Y-%m-%d"
	                                    });
	                                </script> </td>
	                 <td><input type="button" name="boton_procesar_bien_<?=$bus_movimientos["id"]?>" id="boton_procesar_bien_<?=$bus_movimientos["id"]?>" value="Procesar Retorno" class="button" onclick="procesarRetornoBien('<?=$bus_movimientos["id"]?>')"></td>
	            </tr>
				<?
			}
			?>
			</table>
			<?
		}
		?>





	</body>
	</html>
