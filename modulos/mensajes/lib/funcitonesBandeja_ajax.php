<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);


switch($ejecutar){
	case "consultarRecibidos":
		?>
        <form name="formulario_listaCorreos" id="formulario_listaCorreos">
        <input type="hidden" name="accion" id="accion" value="papelera">
        <input type="hidden" name="ubicacion" id="ubicacion" value="recibidos">
		<table width="100%" align="center" cellpadding="0" cellspacing="0"1 style="font-family:Arial, Helvetica, sans-serif; font-size:12px">
		<?
		$sql_mensajes = mysql_query("select 
												mc.idmensajes_correo,
												mc.estado,
												mc.asunto,
												us.nombres,
												us.apellidos,
												mc.idusuario_envia,
												mc.fechayhora
											FROM 
											mensajes_correo mc
											INNER JOIN lista_usuarios_mensajes_correo lu ON 
															(lu.idmensajes_correo = mc.idmensajes_correo and idusuario = '".$_SESSION["login"]."')
											INNER JOIN usuarios us ON (lu.idusuario = us.login)
											WHERE
											(mc.estado = 'recibido' or mc.estado = 'leido')
											order by mc.idmensajes_correo DESC");
		while($bus_mensajes = mysql_fetch_array($sql_mensajes)){
			?>
			<tr <? if($bus_mensajes["estado"] == "recibido"){ echo "style='background-color:#FFFF99; cursor:pointer'"; $color = '#FFFF99';}else{ echo "style='background-color:#FFF; cursor:pointer'";$color = '#FFF';}?> onMouseOver="this.style.backgroundColor='#99CCCC'" onMouseOut="this.style.backgroundColor='<?=$color?>'">
           	  <td width="2%" style="border-bottom:#666 solid 1px"><input type="checkbox" name="eliminar_mensaje[]" id="eliminar_mensaje[]" value="<?=$bus_mensajes["idmensajes_correo"]?>"></td>
              
                <td width="24%" style="border-right:#666666 solid 1px; border-bottom:#666666 solid 1px" onClick="mostrarMensaje('<?=$bus_mensajes["idmensajes_correo"]?>')">
				<?
                $sql_usuario_envia = mysql_query("select * from usuarios where login = '".$bus_mensajes["idusuario_envia"]."'");
				$bus_usuario_envia = mysql_fetch_array($sql_usuario_envia);
				?>
				<?="&nbsp;&nbsp;".$bus_usuario_envia["apellidos"]." ".$bus_usuario_envia["nombres"]?></td>
                <td width="64%" style="border-bottom:#666 solid 1px" onClick="mostrarMensaje('<?=$bus_mensajes["idmensajes_correo"]?>')">&nbsp;&nbsp;<?=$bus_mensajes["asunto"]?></td>
                <td width="10%" style="border-bottom:#666 solid 1px" onClick="mostrarMensaje('<?=$bus_mensajes["idmensajes_correo"]?>')">
				<?
                $fecha = explode(" ", $bus_mensajes["fechayhora"]);
				list($a,$m,$d) = explode("-", $fecha[0]);
				if($fecha[0] == date("Y-m-d")){
					list($h, $m, $s) = explode(":", $fecha[1]);
					if($h > 12){
						$h = $h -12;
						$horario = "PM";
					}else{
						$horario = "AM";
					}
					$texto_mostrar = $h.":".$m." ".$horario;
				}else{
					$texto_mostrar = $d."/".$m."/".$a;
				}
				
				echo $texto_mostrar;
				?></td>
          </tr>
			<?
		}
		?>
		</table>
        </form>
		<?
	break;
	
	
	
	case "mostrarEnviar":
		?>
		<table align="center" width="100%" style="background-color:#EAEAEA">
        	<tr>
            <td>
            	<table width="100%" cellpadding="0" cellspacing="2" style="font-family:Arial, Helvetica, sans-serif; font-size:12px">
   	  <tr>
                        <td width="2%"><img src="img/personas.png" width="14" height="14"></td>
          <td width="12%">&nbsp;Para:&nbsp;</td>
          <td width="86%">
          			<select name="campo_para" id="campo_para">
					<option value="todos">.:: Todos ::.</option>
					<?
                    $sql_usuarios = mysql_query("select * from usuarios where login != '".$_SESSION["login"]."' order by apellidos");
					while($bus_usuarios = mysql_fetch_array($sql_usuarios)){
						?>
						<option value="<?=$bus_usuarios["login"]?>"><?=$bus_usuarios["apellidos"]." ".$bus_usuarios["nombres"]?></option>
						<?
					}
					?>
                    </select>
          			</td>
                  </tr>
                    <tr>
                    	<td><img src="img/asunto.png" width="14" height="14"></td>
                        <td>&nbsp;Asunto:&nbsp;</td>
                        <td><input type="text" name="campo_asunto" id="campo_asunto" size="60"></td>
                    </tr>
                    <tr>
                    	<td valign="top"><img src="img/adjunto.png" width="14" height="14"></td>
                    	<td valign="top">&nbsp;Archivo Adjunto:&nbsp;</td>
                    	<td>
                        <div id="lista_adjuntos_listos"></div>
                        <div>
                        <form method="post" id="formAdjunto" enctype="multipart/form-data" action="lib/funcitonesBandeja_ajax.php" target="iframeUpload">
                        <input type="hidden" id="ejecutar" name="ejecutar" value="ingresar_adjunto">
                        <input type="hidden" id="adjuntos" name="adjuntos" value="">
                        <input type="file" name="archivo_adjunto" id="archivo_adjunto" size="40" onchange="document.getElementById('formAdjunto').submit()">
                        </div>
                        <iframe name="iframeUpload" style="display:none"></iframe>
                        </form>
                        </td>
                    </tr>
                 </table>
            </td>
            </tr>
            <tr>
            	<td>
                <textarea style="width:100%" id="campo_mensaje" name="campo_mensaje" rows="15"></textarea>
                </td>
            </tr>
            <tr>
            	<td>
                	
                    <table cellpadding="0" cellspacing="0">
                    	<tr>
                            <td>
                            <input type="button" name="boton_enviar_mensaje" id="boton_enviar_mensaje" value="Enviar" class="button" onClick="enviarMensaje()">
                            </td>
                        	<td>
                            <input type="button" name="boton_descartar_mensaje" id="boton_descartar_mensaje" value="Descartar" class="button">
                            </td>
                        </tr>
                    </table>
                
                </td>
            </tr>
        </table>
		<?
	break;
	
	
	case "ingresar_adjunto":
	if($_FILES['archivo_adjunto']["name"] != ''){
	$tipo = substr($_FILES['archivo_adjunto']['type'], 0, 5);
		$dir = '../adjuntos/';
		$nombre_imagen = $_FILES['archivo_adjunto']['name'];
			while(file_exists($dir.$nombre_imagen)){
				$partes_img = explode(".",$nombre_imagen);
				$nombre_imagen = $partes_img[0].rand(0,1000000).".".$partes_img[1];
			}
			if (!copy($_FILES['archivo_adjunto']['tmp_name'], $dir.$nombre_imagen)){
				echo "<script>parent.mostrarMensajes('error', 'Disculpe el archivo adjunto no pudo ser cargado, por favor intente de nuevo');</script>";
			}else{
				$sql_ingresar = mysql_query("insert into adjuntos_temporales(idsession,
																			 nombre_archivo,
																				fechayhora,
																				usuario,
																				pc)VALUES('".session_id()."',
																							'".$nombre_imagen."',
																									'".$fh."',
																									'".$login."',
																									'".$pc."')")or die(mysql_error());
				echo "<script>parent.mostrarMensajes('exito', 'El archivo adjunto se subio con exito');</script>";
				echo "<script>parent.document.getElementById('adjuntos').value += '".mysql_insert_id().",';</script>";
				echo "<script>parent.consultarAdjuntos();</script>";
			}
		
	}
	break;
	
	
	case "consultarAdjuntos":
	?>

	<?
		$mostrar = "<table style='font-family:Arial, Helvetica, sans-serif; font-size:12px'>";
		$sql_consulta_temporales = mysql_query("select * from adjuntos_temporales where idsession = '".session_id()."'")or die(mysql_error());
		while($bus_consulta_temporales = mysql_fetch_array($sql_consulta_temporales)){
			$mostrar.= "
			<tr>
							<td style='padding-right:15px'>".$bus_consulta_temporales["nombre_archivo"]."</td>
							<td style='border:#666 solid 1px;padding:3px; cursor:pointer' onclick='eliminarAdjunto(".$bus_consulta_temporales["idadjuntos_temporales"].")'>X</td>
						</tr>
            ";
		}
		$mostrar .= "<table>";
		echo $mostrar;
	
	break;
	
	
	case "eliminarAdjunto":
		$sql_consulta = mysql_query("select * from adjuntos_temporales where idadjuntos_temporales = '".$idadjuntos_temporales."'");
		$bus_consulta = mysql_fetch_array($sql_consulta);
		unlink("../adjuntos/".$bus_consulta["nombre_archivo"]);
		$sql_eliminar = mysql_query("delete from adjuntos_temporales where idadjuntos_temporales = '".$idadjuntos_temporales."'");
	break;
	
	
	case "enviarMensaje":
		if($para == "todos"){
		$sql_usuarios = mysql_query("select * from usuarios where status = 'a' and login != '".$_SESSION["login"]."'");
			while($bus_usuarios = mysql_fetch_array($sql_usuarios)){
				
				$sql_enviar_mensaje = mysql_query("insert into mensajes_correo(idusuario_envia,
																		asunto,
																		mensaje,
																		fechayhora,
																		pc,
																		estado)VALUES('".$_SESSION["login"]."',
																					'".$asunto."',
																					'".$mensaje."',
																					'".$_SESSION["fh"]."',
																					'".$_SESSION["pc"]."',
																					'recibido')")or die("1".mysql_error());
				$idmensaje = mysql_insert_id();
		
			
				$sql_ingresar_destinatarios = mysql_query("insert into lista_usuarios_mensajes_correo(idmensajes_correo,
																				idusuario)VALUES('".$idmensaje."',
																								'".$bus_usuarios["login"]."')")or die("2".mysql_error());
				
				
			}
			if($adjuntos != ''){
				$adjuntos = explode(",",$adjuntos);
				foreach($adjuntos as $adjun){
					if($adjun != ''){
					$sql_consultar = mysql_query("select * from adjuntos_temporales where idadjuntos_temporales = '".$adjun."'");
					$bus_consultar = mysql_fetch_array($sql_consultar);
					
					$sql_ingresar = mysql_query("insert into adjuntos_correo(idcorreo,
																		 nombre_archivo,
																		 usuario,
																		 fechayhora,
																		 pc)values('".$idmensaje."',
																					'".$bus_consultar["nombre_archivo"]."',
																					'".$login."',
																					'".$fh."',
																					'".$pc."')");
					$sql_eliminar = mysql_query("delete from adjuntos_temporales where idadjuntos_temporales = '".$adjun."'");
					}
				}					
			}
		}else{
			$sql_enviar_mensaje = mysql_query("insert into mensajes_correo(idusuario_envia,
																		asunto,
																		mensaje,
																		fechayhora,
																		pc,
																		estado)VALUES('".$_SESSION["login"]."',
																					'".$asunto."',
																					'".$mensaje."',
																					'".$_SESSION["fh"]."',
																					'".$_SESSION["pc"]."',
																					'recibido')")or die("1".mysql_error());
				$idmensaje = mysql_insert_id();
		
			
				$sql_ingresar_destinatarios = mysql_query("insert into lista_usuarios_mensajes_correo(idmensajes_correo,
																				idusuario)VALUES('".$idmensaje."',
																							'".$para."')")or die("2".mysql_error());
				
				
				
				if($adjuntos != ''){
					$adjuntos = explode(",",$adjuntos);
					foreach($adjuntos as $adjun){
						
						$sql_consultar = mysql_query("select * from adjuntos_temporales where idadjuntos_temporales = '".$adjun."'");
						$bus_consultar = mysql_fetch_array($sql_consultar);
						
						$sql_ingresar = mysql_query("insert into adjuntos_correo(idcorreo,
																		 nombre_archivo,
																		 usuario,
																		 fechayhora,
																		 pc)values('".$idmensaje."',
																					'".$bus_consultar["nombre_archivo"]."',
																					'".$login."',
																					'".$fh."',
																					'".$pc."')");
						$sql_eliminar = mysql_query("delete from adjuntos_temporales where idadjuntos_temporales = '".$adjun."'");
					}
					
				}
				
				
				
				
				
				
		}
		
	break;
	
	
	
	case "consultarEnviados":
		?>
        <form name="formulario_listaCorreos" id="formulario_listaCorreos">
        <input type="hidden" name="accion" id="accion" value="papelera">
        <input type="hidden" name="ubicacion" id="ubicacion" value="enviados">
		<table width="100%" align="center" cellpadding="0" cellspacing="0"1 style="font-family:Arial, Helvetica, sans-serif; font-size:12px">
		<?
		$sql_mensajes = mysql_query("select 
												mc.idmensajes_correo,
												mc.estado,
												mc.asunto,
												us.nombres,
												us.apellidos,
												lu.idusuario as enviado_a,
												mc.idusuario_envia,
												mc.fechayhora
											FROM 
											mensajes_correo mc
											
											INNER JOIN lista_usuarios_mensajes_correo lu ON 
															(lu.idmensajes_correo = mc.idmensajes_correo)
											INNER JOIN usuarios us ON (lu.idusuario = us.login)
											
											WHERE
												mc.idusuario_envia = '".$_SESSION["login"]."'
												
											order by mc.idmensajes_correo DESC")or die(mysql_error());
											
											//INNER JOIN lista_usuarios_mensajes_correo lu ON 
											//				(lu.idmensajes_correo = mc.idmensajes_correo and idusuario = '".$_SESSION["login"]."')
											//INNER JOIN usuarios us ON (lu.idusuario = us.login)
		while($bus_mensajes = mysql_fetch_array($sql_mensajes)){
			?>
			<tr style='background-color:#FFF; cursor:pointer' onMouseOver="this.style.backgroundColor='#99CCCC'" onMouseOut="this.style.backgroundColor='#FFF'">
           	  <td width="2%" style="border-bottom:#666 solid 1px"><input type="checkbox" name="eliminar_mensaje[]" id="eliminar_mensaje[]" value="<?=$bus_mensajes["idmensajes_correo"]?>"></td>
                <td width="24%" style="border-right:#666666 solid 1px; border-bottom:#666666 solid 1px" onClick="mostrarMensaje('<?=$bus_mensajes["idmensajes_correo"]?>')">
				<?
                $sql_usuario_envia = mysql_query("select * from usuarios where login = '".$bus_mensajes["enviado_a"]."'");
				$bus_usuario_envia = mysql_fetch_array($sql_usuario_envia);
				?>
				<?="&nbsp;&nbsp;".$bus_usuario_envia["apellidos"]." ".$bus_usuario_envia["nombres"]?></td>
                <td width="64%" style="border-bottom:#666 solid 1px" onClick="mostrarMensaje('<?=$bus_mensajes["idmensajes_correo"]?>')">&nbsp;&nbsp;<?=$bus_mensajes["asunto"]?></td>
                <td width="10%" style="border-bottom:#666 solid 1px" onClick="mostrarMensaje('<?=$bus_mensajes["idmensajes_correo"]?>')">
				<?
                $fecha = explode(" ", $bus_mensajes["fechayhora"]);
				list($a,$m,$d) = explode("-", $fecha[0]);
				if($fecha[0] == date("Y-m-d")){
					list($h, $m, $s) = explode(":", $fecha[1]);
					if($h > 12){
						$h = $h -12;
						$horario = "PM";
					}else{
						$horario = "AM";
					}
					$texto_mostrar = $h.":".$m." ".$horario;
				}else{
					$texto_mostrar = $d."/".$m."/".$a;
				}
				
				echo $texto_mostrar;
				?></td>
          </tr>
			<?
		}
		?>
		</table>
        </form>
		<?
	break;
	
	
	
	
	
	case "consultarPapelera":
		?>
        <form name="formulario_listaCorreos" id="formulario_listaCorreos">
        <input type="hidden" name="accion" id="accion" value="eliminar">
        <input type="hidden" name="ubicacion" id="ubicacion" value="papelera">
		<table width="100%" align="center" cellpadding="0" cellspacing="0"1 style="font-family:Arial, Helvetica, sans-serif; font-size:12px">
		<?
		$sql_mensajes = mysql_query("select 
												mc.idmensajes_correo,
												mc.estado,
												mc.asunto,
												us.nombres,
												us.apellidos,
												mc.idusuario_envia,
												mc.fechayhora
											FROM 
											mensajes_correo mc
											INNER JOIN lista_usuarios_mensajes_correo lu ON 
															(lu.idmensajes_correo = mc.idmensajes_correo and idusuario = '".$_SESSION["login"]."')
											INNER JOIN usuarios us ON (lu.idusuario = us.login)
											WHERE
											mc.estado = 'papelera'
											order by mc.idmensajes_correo DESC");
		while($bus_mensajes = mysql_fetch_array($sql_mensajes)){
			?>
			<tr style='background-color:#FFF; cursor:pointer' onMouseOver="this.style.backgroundColor='#99CCCC'" onMouseOut="this.style.backgroundColor='#FFF'">
           	  <td width="2%" style="border-bottom:#666 solid 1px"><input type="checkbox" name="eliminar_mensaje[]" id="eliminar_mensaje[]" value="<?=$bus_mensajes["idmensajes_correo"]?>"></td>
                <td width="24%" style="border-right:#666666 solid 1px; border-bottom:#666666 solid 1px" onClick="mostrarMensaje('<?=$bus_mensajes["idmensajes_correo"]?>')">
				<?
                $sql_usuario_envia = mysql_query("select * from usuarios where login = '".$bus_mensajes["idusuario_envia"]."'");
				$bus_usuario_envia = mysql_fetch_array($sql_usuario_envia);
				?>
				<?="&nbsp;&nbsp;".$bus_usuario_envia["apellidos"]." ".$bus_usuario_envia["nombres"]?></td>
                <td width="64%" style="border-bottom:#666 solid 1px" onClick="mostrarMensaje('<?=$bus_mensajes["idmensajes_correo"]?>')">&nbsp;&nbsp;<?=$bus_mensajes["asunto"]?></td>
                <td width="10%" style="border-bottom:#666 solid 1px" onClick="mostrarMensaje('<?=$bus_mensajes["idmensajes_correo"]?>')">
				<?
                $fecha = explode(" ", $bus_mensajes["fechayhora"]);
				list($a,$m,$d) = explode("-", $fecha[0]);
				if($fecha[0] == date("Y-m-d")){
					list($h, $m, $s) = explode(":", $fecha[1]);
					if($h > 12){
						$h = $h -12;
						$horario = "PM";
					}else{
						$horario = "AM";
					}
					$texto_mostrar = $h.":".$m." ".$horario;
				}else{
					$texto_mostrar = $d."/".$m."/".$a;
				}
				
				echo $texto_mostrar;
				?></td>
          </tr>
			<?
		}
		?>
		</table>
        </form>
		<?
	break;
	
	case "mostrarMensaje":
		$sql_mensaje = mysql_query("select * from mensajes_correo where idmensajes_correo = '".$idmensajes_correo."'");
		$bus_mensaje = mysql_fetch_array($sql_mensaje);
		
		$sql_usuario_envia = mysql_query("select * from usuarios where login = '".$bus_mensaje["idusuario_envia"]."'");
		$bus_usuario_envia = mysql_fetch_array($sql_usuario_envia);
	?>
	<table align="center" width="100%" cellpadding="2" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px">
        <tr>
        <td colspan="2">
       	 
         <?
         if($ubicacion != 'enviados' and $ubicacion != 'papelera'){
		 ?>
          <table cellpadding="0" cellspacing="0">
            <tr>
            <td>
        	<input type="button" name="boton_eliminar_mensaje" id="boton_eliminar_mensaje" value="Eliminar" class="button" onClick=" eliminarUnicoMensaje('<?=$idmensajes_correo?>')">
        	</td>
            <td>
            <input type="button" name="boton_reenviar_mensaje" id="boton_reenviar_mensaje" value="Reenviar" class="button">
            </td>
            </table>
        <?
        }
		?>
        
        </td>
      </tr>
        <tr style="background-color:#EAEAEA">
       		<td width="11%">
           	  <table cellpadding="2" cellspacing="0">
                <tr>
                <td><img src="img/contacto.png" width="14" height="14"></td>
                <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px">De: </td>
                </tr>
                </table>
                
            </td>
        	<td width="89%"><?=$bus_usuario_envia["apellidos"]." ".$bus_usuario_envia["nombres"]?></td>
      </tr>
        <tr style="background-color:#EAEAEA">
        <?
        $sql_usuario_para = mysql_query("select * from lista_usuarios_mensajes_correo where idmensajes_correo = '".$idmensajes_correo."'");
		
		?>
        	<td>
            	<table cellpadding="2" cellspacing="0">
                <tr>
                <td><img src="img/personas.png" width="14" height="14"></td>
                <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px">Para: </td>
                </tr>
                </table>
            
            </td>
        	<td>
            <?
            while($bus_usuario_para = mysql_fetch_array($sql_usuario_para)){
				$sql_usuario = mysql_query("select * from usuarios where login = '".$bus_usuario_para["idusuario"]."'");
				$bus_usuario = mysql_fetch_array($sql_usuario);
				echo $bus_usuario["apellidos"]." ".$bus_usuario["nombres"];
			}
			?>
            </td>
        </tr>
        <tr style="background-color:#EAEAEA">
        	<td>
            <table cellpadding="2" cellspacing="0">
                <tr>
                <td><img src="img/fecha.png" width="14" height="14"></td>
                <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px">Fecha: </td>
                </tr>
                </table>
            
            </td>
            <td>
            <?
            $fecha = explode(" ", $bus_mensaje["fechayhora"]);
			list($a,$m,$d) = explode("-", $fecha[0]);
			list($h, $mi, $s) = explode(":", $fecha[1]);
			if($h > 12){
				$h = $h - 12;
				$tipo_hora = "PM";
			}else{
				$tipo_hora = "AM";
			}
			echo $d."/".$m."/".$a."&nbsp; A las ".$h.":".$mi." ".$tipo_hora;
			?>
            </td>
        </tr>
        <tr style="background-color:#EAEAEA;">
        	<td style="border-bottom:#666666 solid 1px">
            
            <table cellpadding="2" cellspacing="0">
                <tr>
                <td><img src="img/asunto.png" width="14" height="14"></td>
                <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px">Asunto: </td>
                </tr>
                </table>
            
            </td>
            <td style="border-bottom:#666666 solid 1px"><?=$bus_mensaje["asunto"]?></td>
        </tr>
        
         <tr style="background-color:#EAEAEA;">
        	<td style="border-bottom:#666666 solid 1px">
            
            <table cellpadding="2" cellspacing="0">
                <tr>
                <td><img src="img/adjunto.png" width="14" height="14"></td>
                <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px">Adjuntos: </td>
                </tr>
                </table>
            
            </td>
            <td style="border-bottom:#666666 solid 1px">
            
            <?
			$sql_consulta_temporales = mysql_query("select * from adjuntos_correo where idcorreo = '".$idmensajes_correo."'")or die(mysql_error());
			while($bus_consulta_temporales = mysql_fetch_array($sql_consulta_temporales)){
				?>
                <div style='padding-right:15px'><a href="adjuntos/<?=$bus_consulta_temporales["nombre_archivo"]?>" target="_blank"><?=$bus_consulta_temporales["nombre_archivo"]?></a></div>

			<? } ?>
            
            </td>
        </tr>
        
        
        
        
        
        <tr>
        	<td colspan="2" style="padding:5px">
            <div style="width:100%; height:260px; overflow:auto"><?=$bus_mensaje["mensaje"]?></div>
            </td>
        </tr>
        </table>
	<?
	if($ubicacion != 'enviados' and $ubicacion != 'papelera'){
	$sql_actualizar = mysql_query("update mensajes_correo
												set estado = 'leido' 
												where idmensajes_correo = '".$idmensajes_correo."'");
	}
	break;
	
	
	
	
	
	case "enviarPapelera":
	
		$datos = explode(",", $valores);
		
		foreach($datos as $a){
			$sql_actualizar = mysql_query("update mensajes_correo
												set estado = 'papelera'
												where idmensajes_correo = '".$a."'");
		}
	
	break;	
	
	
	case "suprimirDefinitivamente":
		$datos = explode(",", $valores);
		
		foreach($datos as $a){
			$sql_actualizar = mysql_query("delete from mensajes_correo
												where idmensajes_correo = '".$a."'");
			$sql_consulta = mysql_query("select * from adjuntos_correo where idcorreo = '".$a."'");
			$bus_consulta = mysql_fetch_array($sql_consulta);
			unlink("../adjuntos/".$bus_consulta["nombre_archivo"]);
			$sql_adjuntos = mysql_query("delete from adjuntos_correo where idcorreo = '".$a."'");
		}
	break;
	
	
	
	
	
	
	
	
	case "buscarMensajes":
		?>
        <form name="formulario_listaCorreos" id="formulario_listaCorreos">
        <input type="hidden" name="accion" id="accion" value="papelera">
        <input type="hidden" name="ubicacion" id="ubicacion" value="recibidos">
		<table width="100%" align="center" cellpadding="0" cellspacing="0"1 style="font-family:Arial, Helvetica, sans-serif; font-size:12px">
		<?
		$sql_mensajes = mysql_query("select 
												mc.idmensajes_correo,
												mc.estado,
												mc.asunto,
												us.nombres,
												us.apellidos,
												mc.idusuario_envia,
												mc.fechayhora
											FROM 
											mensajes_correo mc
											INNER JOIN lista_usuarios_mensajes_correo lu ON 
															(lu.idmensajes_correo = mc.idmensajes_correo 
															and idusuario = '".$_SESSION["login"]."')
											INNER JOIN usuarios us ON (lu.idusuario = us.login)
											WHERE
											us.nombres like '%".$campoBuscar."%'
											or us.apellidos like '%".$campoBuscar."%'
											or mc.asunto like '%".$campoBuscar."%'
											or mc.mensaje like '%".$campoBuscar."%'
											order by mc.idmensajes_correo DESC");
		while($bus_mensajes = mysql_fetch_array($sql_mensajes)){
			?>
			<tr <? if($bus_mensajes["estado"] == "recibido"){ echo "style='background-color:#FFFF99; cursor:pointer'"; $color = '#FFFF99';}else{ echo "style='background-color:#FFF; cursor:pointer'";$color = '#FFF';}?> onMouseOver="this.style.backgroundColor='#99CCCC'" onMouseOut="this.style.backgroundColor='<?=$color?>'">
           	  <td width="2%" style="border-bottom:#666 solid 1px"><input type="checkbox" name="eliminar_mensaje[]" id="eliminar_mensaje[]" value="<?=$bus_mensajes["idmensajes_correo"]?>"></td>
              
                <td width="24%" style="border-right:#666666 solid 1px; border-bottom:#666666 solid 1px" onClick="mostrarMensaje('<?=$bus_mensajes["idmensajes_correo"]?>')">
				<?
                $sql_usuario_envia = mysql_query("select * from usuarios where login = '".$bus_mensajes["idusuario_envia"]."'");
				$bus_usuario_envia = mysql_fetch_array($sql_usuario_envia);
				?>
				<?="&nbsp;&nbsp;".$bus_usuario_envia["apellidos"]." ".$bus_usuario_envia["nombres"]?></td>
                <td width="64%" style="border-bottom:#666 solid 1px" onClick="mostrarMensaje('<?=$bus_mensajes["idmensajes_correo"]?>')"><? if($bus_mensajes["estado"]=="papelera"){?>&nbsp; <img src="img/papelera.png" width="14" height="16" title="Mensaje en la Papelera" alt="Mensaje en la Papelera"><? }?>&nbsp;&nbsp;<?=$bus_mensajes["asunto"]?></td>
                <td width="10%" style="border-bottom:#666 solid 1px" onClick="mostrarMensaje('<?=$bus_mensajes["idmensajes_correo"]?>')">
				<?
                $fecha = explode(" ", $bus_mensajes["fechayhora"]);
				list($a,$m,$d) = explode("-", $fecha[0]);
				if($fecha[0] == date("Y-m-d")){
					list($h, $m, $s) = explode(":", $fecha[1]);
					if($h > 12){
						$h = $h -12;
						$horario = "PM";
					}else{
						$horario = "AM";
					}
					$texto_mostrar = $h.":".$m." ".$horario;
				}else{
					$texto_mostrar = $d."/".$m."/".$a;
				}
				
				echo $texto_mostrar;
				?></td>
          </tr>
			<?
		}
		?>
		</table>
        </form>
		<?
	break;
	
	
	
}






?>