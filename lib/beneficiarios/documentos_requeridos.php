<?php

if($_POST["ingresar"]){
$_GET["accion"] = 313;
}
if($_POST["ingresar2"]){
$_GET["accion"] = 92;
}
if($_POST["ingresar3"]){
$_GET["accion"] = 449;
}
if($_POST["ingresar4"]){
$_GET["accion"] = 516;
}
if($_POST["ingresar5"]){
$_GET["accion"] = 638;
}
if($_POST["ingresar6"]){
$_GET["accion"] = 709;
}
if($_POST["ingresar7"]){
$_GET["accion"] = 829;
}
if($_POST["ingresar8"]){
$_GET["accion"] = 919;
}
if($_POST["ingresar9"]){
$_GET["accion"] = 1062;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from documentos_requeridos 
												where status='a' order by descripcion"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from documentos_requeridos where status='a'";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			$registros_grilla=mysql_query($sql." and descripcion like '$texto_buscar%' order by descripcion",$conexion_db);
			
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}

if ($_GET["c"]){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from documentos_requeridos 
										where iddocumentos_requeridos like '".$_GET['c']."'"
											,$conexion_db);
	$regdocumentos_requeridos=mysql_fetch_assoc($sql);
	
}

?>

<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
function valida_envia(){
		if (document.frmdocumentos_requeridos.descripcion.value.length==0){
			mostrarMensajes("error", "Debe escribir una Descripcion para el Tipo de Presupuesto");
			document.frmdocumentos_requeridos.descripcion.focus();
			return false;
		}	
	} 

function solonumeros(e){
var key;
if(window.event)
	{key=e.keyCode;}
else if(e.which)
	{key=e.which;}
if (key < 48 || key > 57)
	{return false;}
return true;
}
// end hiding from old browsers -->
</SCRIPT>


<body>
	<br>
<h4 align=center>Documentos Requeridos</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<form name="frmdocumentos_requeridos" action="" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
  <input type="hidden" id="codigo" name="codigo" maxlength="9" size="9" <?php echo 'value="'.$regdocumentos_requeridos['iddocumentos_requeridos'].'"';?>>
		<table align=center cellpadding=2 cellspacing=0 width="31%">
<tr>
				<td align='right' class='viewPropTitle'>Descripcion:</td>
				<td class=''><input type="text" name="descripcion" maxlength="255" size="45" id="descripcion" <?php echo 'value="'.$regdocumentos_requeridos['descripcion'].'"';?>>
                
                
                <?
                if($_SESSION["modulo"] == 4){
				?>
                &nbsp; <a href='principal.php?modulo=4&accion=312' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Requerido"></a>
                <?
                }
				if($_SESSION["modulo"] == 3){
				?>
                &nbsp; <a href='principal.php?modulo=3&accion=75' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Requerido"></a>
                <?
                }
				if($_SESSION["modulo"] == 1){
				?>
                &nbsp; <a href='principal.php?modulo=1&accion=448' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Requerido"></a>
                <?
                }
				if($_SESSION["modulo"] == 2){
				?>
                &nbsp; <a href='principal.php?modulo=2&accion=515' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Requerido"></a>
                <?
                }
				if($_SESSION["modulo"] == 12){
				?>
                &nbsp; <a href='principal.php?modulo=12&accion=637' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Requerido"></a>
                <?
                }
				if($_SESSION["modulo"] == 13){
				?>
                &nbsp; <a href='principal.php?modulo=13&accion=707' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Requerido"></a>
                <?
                }
				if($_SESSION["modulo"] == 14){
				?>
                &nbsp; <a href='principal.php?modulo=14&accion=828' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Requerido"></a>
                <?
                }
				if($_SESSION["modulo"] == 16){
				?>
                &nbsp; <a href='principal.php?modulo=16&accion=918' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Requerido"></a>
                <?
                }
				if($_SESSION["modulo"] == 19){
				?>
                &nbsp; <a href='principal.php?modulo=19&accion=1040' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Requerido"></a>
                <?
                }
				?>
                
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=docreq';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none';">X</a></div>          
          <iframe name="pdf" id="pdf" style="display:block" height="500" width="500"></iframe>          
          </div>
                
                
                </td>
			</tr>
		</table>
<table align=center cellpadding=2 cellspacing=0>
			<tr><td>
            			    <?php
    if($_REQUEST["pop"]){
	echo "<input type='hidden' value='true' name='pop' id='pop'>";
	}
				if($_GET["modulo"] == 4){
					if($_GET["accion"] != 314 and $_GET["accion"] != 315 and in_array(313, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 314 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 315 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				if($_GET["modulo"] == 3){
					if($_GET["accion"] != 93 and $_GET["accion"] != 95 and in_array(92, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar2' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 93 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 95 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}

				if($_GET["modulo"] == 1){
					if($_GET["accion"] != 450 and $_GET["accion"] != 451 and in_array(449, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar3' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 450 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 451 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				if($_GET["modulo"] == 2){
					if($_GET["accion"] != 517 and $_GET["accion"] != 518 and in_array(516, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar4' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 517 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 518 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				
				if($_GET["modulo"] == 12){
					if($_GET["accion"] != 639 and $_GET["accion"] != 640 and in_array(638, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar5' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 639 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 640 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				
				if($_GET["modulo"] == 13){
					if($_GET["accion"] != 709 and $_GET["accion"] != 710 and in_array(708, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar6' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 709 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 710 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				
				if($_GET["modulo"] == 14){
					if($_GET["accion"] != 830 and $_GET["accion"] != 831 and in_array(829, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar7' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 830 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 831 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				
				
				
				if($_GET["modulo"] == 16){
					if($_GET["accion"] != 920 and $_GET["accion"] != 921 and in_array(919, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar7' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 920 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 921 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				
				if($_GET["modulo"] == 19){
					if($_GET["accion"] != 1063 and $_GET["accion"] != 1064 and in_array(1062, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar7' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 1063 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 1064 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				
        	        //botones(85,86,87,$modo,"0",$nivel);
				
            ?>
				<input type="reset" value="Reiniciar" class="button">
			</td></tr>
		</table>
</form>
	<br>


<form name="buscar" action="" method="POST">
	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Buscar:</td>
			<td class='viewProp'><input type="text" name="textoabuscar" maxlength="60" size="30"></td>
			<td>
				<input align=center class="button" name="buscar" type="submit" value="Buscar">
				</a>
			</td>
		</tr>
	</table>
</form>





	<br>
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="60%">
				<tr>
					<td align="center">
					  <form name="grilla" action="documentos_requeridos.php" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="50%">
							<thead>
								<tr>
									<td align="center" class="Browse">Descripcion</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
									{ ?>
						  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
									echo "<td align='left' class='Browse'>".$llenar_grilla["descripcion"]."</td>";
									$c=$llenar_grilla["iddocumentos_requeridos"];
										
										
									if($_SESSION["modulo"] == 4){	
										if(in_array(314,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=314&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(315,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=315&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}
									
									
									if($_SESSION["modulo"] == 3){	
										if(in_array(93,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=93&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(95,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=95&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}
									
									
									if($_SESSION["modulo"] == 1){	
										if(in_array(450,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=1&accion=450&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(451,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=1&accion=451&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}
									
									if($_SESSION["modulo"] == 2){	
										if(in_array(517,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=517&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(518,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=518&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}
									
									
									if($_SESSION["modulo"] == 12){	
										if(in_array(639,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=639&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(640,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=640&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}
									
									
									if($_SESSION["modulo"] == 13){	
										if(in_array(709,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=13&accion=709&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(710,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=13&accion=710&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}
									
									if($_SESSION["modulo"] == 14){	
										if(in_array(830,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=830&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(831,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=831&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}
									
									
									
									if($_SESSION["modulo"] == 16){	
										if(in_array(920,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=920&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(921,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=921&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}
										
									if($_SESSION["modulo"] == 19){	
										if(in_array(1063,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1063&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(1064,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1064&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}		
									echo "</tr>";
									}
							}?>
						</table>
                       
					  </form>
					</td>
				</tr>
			</table>
             
</div>
<script> document.frmdocumentos_requeridos.descripcion.focus() </script>
</body>
</html>

<?php
	if($_POST){
		$codigo=$_POST["codigo"];
		$descripcion=strtoupper($_POST["descripcion"]);
		$busca_existe_registro=mysql_query("select * from documentos_requeridos where descripcion = '".$_POST['descripcion']."'  and status='a'",$conexion_db);
		
		if($_SESSION["modulo"] == 4){
		
		if($_GET["accion"] == 313 and in_array(313, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
			<script>
            mostrarMensajes("error", "Disculpe ya este documento existe");
            setTimeout("window.location.href='principal.php?modulo=4&accion=312'",5000);
            </script>
            <?
			}else{	
				
					mysql_query("insert into documentos_requeridos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
				$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Documento Requerido ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("documento", "lib/consultar_tablas_select.php", "documentos_requeridos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
			?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=4&accion=312'",5000);
            </script>
            <?		
			}
					


				}
			}
			
		if($_GET["accion"] == 314 and in_array(314, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update documentos_requeridos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	iddocumentos_requeridos = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Documentos Requeridos ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Actualizo con Exito");
            setTimeout("window.location.href='principal.php?modulo=4&accion=312'",5000);
            </script>
            <?
		}
		
		if($_GET["accion"] == 315 and in_array(315, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						
						?>
						<script>
                        mostrarMensajes("error", "Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
                        setTimeout("window.location.href='principal.php?modulo=4&accion=312'",5000);
                        </script>
                        <?
						
						registra_transaccion('Eliminar Documentos Requeridos (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
					}else{
						?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Eliminado con Exito");
            setTimeout("window.location.href='principal.php?modulo=4&accion=312'",5000);
            </script>
            <?
						registra_transaccion('Eliminar Documentos Requeridos ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
					}

		}
	}
	
	
			
	
	
		if($_SESSION["modulo"] == 3){
		
		if($_GET["accion"] == 92 and in_array(92, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
			<script>
            mostrarMensajes("error", "Disculpe ya este documento existe");
            setTimeout("window.location.href='principal.php?modulo=3&accion=75'",5000);
            </script>
            <?
			}else{	
				
					mysql_query("insert into documentos_requeridos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
				$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Documento Requerido ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("documento", "lib/consultar_tablas_select.php", "documentos_requeridos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					?>
			<script>
            mostrarMensajes("exito", "El Documento Requerido se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=3&accion=75'",5000);
            </script>
            <?
			}
							

				}
			}
		if($_GET["accion"] == 93 and in_array(93, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update documentos_requeridos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	iddocumentos_requeridos = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Documentos Requeridos ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Actualizo con Exito");
            setTimeout("window.location.href='principal.php?modulo=3&accion=75'",5000);
            </script>
            <?
				
		}
		
		if($_GET["accion"] == 95 and in_array(95, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Documentos Requeridos (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=3&accion=75'",5000);
            </script>
            <?
					}else{
						registra_transaccion('Eliminar Documentos Requeridos ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Eliminado con Exito");
            setTimeout("window.location.href='principal.php?modulo=3&accion=75'",5000);
            </script>
            <?
	
					}
		}
	}
	
	
	
	
	
	
	if($_SESSION["modulo"] == 1){
		
		if($_GET["accion"] == 449 and in_array(449, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
			<script>
            mostrarMensajes("error", "Disculpe ya este documento existe");
            setTimeout("window.location.href='principal.php?modulo=1&accion=448'",5000);
            </script>
            <?
			}else{	
				
					mysql_query("insert into documentos_requeridos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Documento Requerido ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("documento", "lib/listas/consultar_tablas_select.php", "documentos_requeridos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					
					?>
			<script>
            mostrarMensajes("exito", "El Documento Requerido se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=1&accion=448'",5000);
            </script>
            <?
					
			}
					

			}
		}
		
		
		if($_GET["accion"] == 450 and in_array(450, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update documentos_requeridos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	iddocumentos_requeridos = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Documentos Requeridos ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Actualizo con Exito");
            setTimeout("window.location.href='principal.php?modulo=1&accion=448'",5000);
            </script>
            <?
			
		}
		
		
		if($_GET["accion"] == 451 and in_array(451, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Documentos Requeridos (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=1&accion=448'",5000);
            </script>
            <?
						
					}else{
						registra_transaccion('Eliminar Documentos Requeridos ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Eliminado con Exito");
            setTimeout("window.location.href='principal.php?modulo=1&accion=448'",5000);
            </script>
            <?
						
					}
					
		}
	}








if($_SESSION["modulo"] == 2){
		
		if($_GET["accion"] == 516 and in_array(516, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
			<script>
            mostrarMensajes("error", "Disculpe ya este documento existe");
            setTimeout("window.location.href='principal.php?modulo=2&accion=515'",5000);
            </script>
            <?
				
			}else{	
				
					mysql_query("insert into documentos_requeridos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Documento Requerido ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("documento", "lib/listas/consultar_tablas_select.php", "documentos_requeridos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					
					
					?>
			<script>
            mostrarMensajes("exito", "El Documento Requerido se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=2&accion=515'",5000);
            </script>
            <?
					
			}
	
					

			}
		}
		
		
		if($_GET["accion"] == 517 and in_array(517, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update documentos_requeridos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	iddocumentos_requeridos = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Documentos Requeridos ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Actualizo con Exito");
            setTimeout("window.location.href='principal.php?modulo=2&accion=515'",5000);
            </script>
            <?
		}
		
		
		if($_GET["accion"] == 518 and in_array(518, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Documentos Requeridos (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=2&accion=515'",5000);
            </script>
            <?
					}else{
						registra_transaccion('Eliminar Documentos Requeridos ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
			<script>
            mostrarMensajes("error", "El Documento Reuqerido se Eliminado con Exito");
            setTimeout("window.location.href='principal.php?modulo=2&accion=515'",5000);
            </script>
            <?

					}
					
		}
	}













if($_SESSION["modulo"] == 13){
		
		if($_GET["accion"] == 708 and in_array(708, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
			<script>
            mostrarMensajes("error", "Disculpe ya este documento existe");
            setTimeout("window.location.href='principal.php?modulo=13&accion=707'",5000);
            </script>
            <?
			}else{	
				
					mysql_query("insert into documentos_requeridos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Documento Requerido ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("documento", "lib/listas/consultar_tablas_select.php", "documentos_requeridos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					
			?>
			<script>
            mostrarMensajes("exito", "El Documento Requerido se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=13&accion=707'",5000);
            </script>
            <?
					
			}
					

			}
		}
		
		
		if($_GET["accion"] == 709 and in_array(709, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update documentos_requeridos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	iddocumentos_requeridos = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Documentos Requeridos ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Actualizo con Exito");
            setTimeout("window.location.href='principal.php?modulo=13&accion=707'",5000);
            </script>
            <?
		}
		
		
		if($_GET["accion"] == 710 and in_array(710, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Documentos Requeridos (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
			<script>
            mostrarMensajes("errot", "Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=13&accion=707'",5000);
            </script>
            <?
					}else{
						registra_transaccion('Eliminar Documentos Requeridos ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						
						?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Eliminado con Exito");
            setTimeout("window.location.href='principal.php?modulo=13&accion=707'",5000);
            </script>
            <?
					}
		}
	}







if($_SESSION["modulo"] == 12){
		
		if($_GET["accion"] == 638 and in_array(638, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
			<script>
            mostrarMensajes("error", "Disculpe ya este documento existe");
            setTimeout("window.location.href='principal.php?modulo=12&accion=637'",5000);
            </script>
            <?
			}else{	
				
					mysql_query("insert into documentos_requeridos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Documento Requerido ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("documento", "lib/listas/consultar_tablas_select.php", "documentos_requeridos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El Documento Requerido se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=12&accion=637'",5000);
            </script>
            <?
					
			}
					

			}
		}
		
		
		if($_GET["accion"] == 639 and in_array(639, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update documentos_requeridos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	iddocumentos_requeridos = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Documentos Requeridos ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Actualizo con Exito");
            setTimeout("window.location.href='principal.php?modulo=12&accion=637'",5000);
            </script>
            <?
		}
		
		
		if($_GET["accion"] == 640 and in_array(640, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Documentos Requeridos (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=12&accion=637'",5000);
            </script>
            <?
					}else{
						registra_transaccion('Eliminar Documentos Requeridos ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Eliminado con Exito");
            setTimeout("window.location.href='principal.php?modulo=12&accion=637'",5000);
            </script>
            <?
					}
		}
	}







if($_SESSION["modulo"] == 14){
		
		if($_GET["accion"] == 829 and in_array(829, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
			<script>
            mostrarMensajes("error", "Disculpe ya este documento existe");
            setTimeout("window.location.href='principal.php?modulo=14&accion=828'",5000);
            </script>
            <?
			}else{	
				
					mysql_query("insert into documentos_requeridos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Documento Requerido ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("documento", "lib/listas/consultar_tablas_select.php", "documentos_requeridos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					
					?>
			<script>
            mostrarMensajes("exito", "El Documento Requerido se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=14&accion=828'",5000);
            </script>
            <?
					

					
			}
					

			}
		}
		
		
		if($_GET["accion"] == 830 and in_array(830, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update documentos_requeridos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	iddocumentos_requeridos = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Documentos Requeridos ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Actualizo con Exito");
            setTimeout("window.location.href='principal.php?modulo=14&accion=828'",5000);
            </script>
            <?
		}
		
		
		if($_GET["accion"] == 831 and in_array(831, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Documentos Requeridos (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=14&accion=828'",5000);
            </script>
            <?
					}else{
						registra_transaccion('Eliminar Documentos Requeridos ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
					?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Eliminado con Exito");
            setTimeout("window.location.href='principal.php?modulo=14&accion=828'",5000);
            </script>
            <?	
						
					}
					
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 16){
		
		if($_GET["accion"] == 919 and in_array(919, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
			<script>
            mostrarMensajes("error", "Disculpe ya este documento existe");
            setTimeout("window.location.href='principal.php?modulo=16&accion=918'",5000);
            </script>
            <?

			}else{	
				
					mysql_query("insert into documentos_requeridos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Documento Requerido ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("documento", "lib/listas/consultar_tablas_select.php", "documentos_requeridos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					?>
			<script>
            mostrarMensajes("exito", "El Documento Requerido se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=16&accion=918'",5000);
            </script>
            <?
					
			}
					

			}
		}
		
		
		if($_GET["accion"] == 920 and in_array(920, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update documentos_requeridos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	iddocumentos_requeridos = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Documentos Requeridos ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Actualizo con Exito");
            setTimeout("window.location.href='principal.php?modulo=16&accion=918'",5000);
            </script>
            <?
		}
		
		
		if($_GET["accion"] == 921 and in_array(921, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Documentos Requeridos (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=16&accion=918'",5000);
            </script>
            <?
					}else{
						registra_transaccion('Eliminar Documentos Requeridos ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Eliminado con Exito");
            setTimeout("window.location.href='principal.php?modulo=16&accion=918'",5000);
            </script>
            <?
					}
					
		}
	}




if($_SESSION["modulo"] == 19){
		
		if($_GET["accion"] == 1062 and in_array(1062, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
			<script>
            mostrarMensajes("error", "Disculpe ya este documento existe");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1040'",5000);
            </script>
            <?

			}else{	
				
					mysql_query("insert into documentos_requeridos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Documento Requerido ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("documento", "lib/listas/consultar_tablas_select.php", "documentos_requeridos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					?>
			<script>
            mostrarMensajes("exito", "El Documento Requerido se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1040'",5000);
            </script>
            <?
					
			}
					

			}
		}
		
		
		if($_GET["accion"] == 1063 and in_array(1063, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update documentos_requeridos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	iddocumentos_requeridos = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Documentos Requeridos ('.$descripcion.')',$login,$fh,$pc,'documentos_requeridos',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Actualizo con Exito");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1040'",5000);
            </script>
            <?
		}
		
		
		if($_GET["accion"] == 1064 and in_array(1064, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from documentos_requeridos where iddocumentos_requeridos = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Documentos Requeridos (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1040'",5000);
            </script>
            <?
					}else{
						registra_transaccion('Eliminar Documentos Requeridos ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
			<script>
            mostrarMensajes("exito", "El Documento Reuqerido se Eliminado con Exito");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1040'",5000);
            </script>
            <?
					}
					
		}
	}



}


?>