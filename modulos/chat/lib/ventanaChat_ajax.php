<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);



switch($ejecutar){
	case "registrarComentario":
		$sql_ingresar = mysql_query("INSERT INTO conversaciones_chat(quien_escribe,
																	usuario_apertura,
																	usuario_recepcion,
																	mensaje,
																	fechayhora,
																	pc_apertura,
																	mensaje_nuevo)VALUES('".$_SESSION["login"]."',
																						'".$ua."',
																						'".$ur."',
																						'".$comentario."',
																						'".$_SESSION["fh"]."',
																						'".$_SESSION["pc"]."',
																						'si')")or die(mysql_error());
	break;
	
	
	case "consultarMensajes":
		?>
        
		<table width="100%" cellpadding="2" cellspacing="2" style="font-size:11px">
		<?
		$sql_consulta = mysql_query("select * from conversaciones_chat where 
										(usuario_apertura = '".$ua."' and usuario_recepcion = '".$ur."') 
										or 
										(usuario_apertura = '".$ur."' and usuario_recepcion = '".$ua."')");
		$i= 0;
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			
			?>
            <tr>
                <td>
                <?
                 if($bus_consulta["quien_escribe"] == $_SESSION["login"]){
				 	$remitente = "Yo";
				 }else{
				 	$sql_usuario = mysql_query("select * from usuarios where login = '".$bus_consulta["quien_escribe"]."'");
					$bus_usuario = mysql_fetch_array($sql_usuario);
					$remitente =  $bus_usuario["nombres"]." ".$bus_usuario["apellidos"];
				 }
				
				$fecha = explode(" ", $bus_consulta["fechayhora"]);
				
				if($fecha[0] == date("Y-m-d")){
					$fecha_mostrar = "";
				}else{
					list($a, $m, $d) = explode("-", $fecha[0]);
					$fecha_mostrar = $a."/".$m."/".$d." a las ";
				}
				$hora = explode(":", $fecha[1]);
				?>
                
                <strong <? if($bus_consulta["quien_escribe"] == $_SESSION["login"]){ echo "style='color:0066CC'"; }else{ echo "style='color:#990000'";}?>>
				<?=$remitente?> <span style="font-weight:normal ;font-family:Arial, Helvetica, sans-serif; font-size:9px; color:#999"><?=$fecha_mostrar.$hora[0].":".$hora[1]?></span> : 
                </strong>
                
				<?=$bus_consulta["mensaje"]?>
                
                <?
                $sql_update = mysql_query("update conversaciones_chat set mensaje_nuevo = 'no' 
													where 
													idconversaciones_chat = '".$bus_consulta["idconversaciones_chat"]."' 
													and (usuario_recepcion = '".$_SESSION["login"]."'
														or usuario_apertura = '".$_SESSION["login"]."')
													and quien_escribe != '".$_SESSION["login"]."'");
				?>
                <td>
			</tr>
			<?
			
		}
		?>
		</table>
        <label id="fin"></label>
		<?
		
		break;
		
		
		
		
		
		case "revisarNuevasConversaciones":
			$sql_consulta = mysql_query("select * from conversaciones_chat 
													where 
													quien_escribe != '".$_SESSION["login"]."'
													and 
													(usuario_recepcion = '".$_SESSION["login"]."'
													OR usuario_apertura = '".$_SESSION["login"]."')
													and mensaje_nuevo = 'si'")or die(mysql_error());
			$num_consulta = mysql_num_rows($sql_consulta);
			if($num_consulta > 0){
				$bus_consulta = mysql_fetch_array($sql_consulta);
				echo $bus_consulta["quien_escribe"];
			}else{
				echo 0;
			}
		break;
		
		
		
		
		case "revisarNuevosConectados":
		?>
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
		<?
		break;

}
?>	