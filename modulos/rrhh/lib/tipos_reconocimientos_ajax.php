<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);




switch($ejecutar){
	case "ingresarTiposSanciones":
			$sql_ingresar = mysql_query("insert into tipos_reconocimientos 
																(denominacion,
																usuario,
																fechayhora) 
															values (
																'$descripcion',
																'".$_SESSION["login"]."',
																'".$_SESSION["fh"]."')");
																
			if($sql_ingresar){
				echo "exito|.|".mysql_insert_id();
			}else{
				echo "error|.|".mysql_error();
			}
	break;
	
	
	case "modificarTiposSanciones":
	
			$sql_modificar = mysql_query("update tipos_reconocimientos set denominacion = '".$descripcion."'
																where idtipo_reconocimientos = '".$idtipo_reconocimientos."'");
																
			if($sql_modificar){
				echo "exito|.|";
			}else{
				echo "error|.|".mysql_error();
			}
	
	break;
	
	case "consultarSanciones":
	$sql_consulta = mysql_query("select * from tipos_reconocimientos")or die(mysql_error());
	?>
	<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<table class="Browse" cellpadding="0" cellspacing="0" width="40%" align="center">
							<thead>
								<tr>
									<td align="center" class="Browse">Descripci&oacute;n</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
							$existen_registros = mysql_num_rows($sql_consulta);
						
								while($llenar_grilla= mysql_fetch_array($sql_consulta)) 
									{ ?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
									echo "<td align='left' class='Browse'>&nbsp;".$llenar_grilla["denominacion"]."</td>";
									$c=$llenar_grilla["idtipo_reconocimientos"];
									if(in_array(198,$privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='7%'>
                                        <img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar' onClick="seleccionarModificar('<?=$llenar_grilla["idtipo_reconocimientos"]?>', '<?=$llenar_grilla["denominacion"]?>')" style="cursor:pointer">
                                        </td>
									<?
                                    }
									if(in_array(199, $privilegios) == true){
									?>
                                    <td align='center' class='Browse' width='7%'>
                                    	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar' onClick="eliminarSanciones('<?=$llenar_grilla["idtipo_reconocimientos"]?>')" style="cursor:pointer">
                                    </td>	
									<?
                                    }
									?>
									</tr>
							<?
							}
                            ?>
						</table>
					</td>
				</tr>
			</table>
	<?
	
	break;
	
	
	case "eliminarSanciones":
		$sql_eliminar = mysql_query("delete from tipo_reconocimientos where idtipo_reconocimientos = '".$idtipo_reconocimientos."'");
		if($sql_eliminar){
			echo "exito";
		}else{
			echo mysql_error();
		}
	break;
}
?>