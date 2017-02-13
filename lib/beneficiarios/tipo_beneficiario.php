<?php

if($_POST["ingresar"]){
$_GET["accion"] = 293;
}
if($_POST["ingresar2"]){
$_GET["accion"] = 119;
}
if($_POST["ingresar3"]){
$_GET["accion"] = 453;
}
if($_POST["ingresar4"]){
$_GET["accion"] = 520;
}
if($_POST["ingresar5"]){
$_GET["accion"] = 618;
}
if($_POST["ingresar6"]){
$_GET["accion"] = 688;
}
if($_POST["ingresar7"]){
$_GET["accion"] = 809;
}
if($_POST["ingresar8"]){
$_GET["accion"] = 899;
}
if($_POST["ingresar9"]){
$_GET["accion"] = 1047;
}
if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from tipo_beneficiario 
												where status='a' order by descripcion"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from tipo_beneficiario where status='a'";
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
	$sql=mysql_query("select * from tipo_beneficiario 
										where idtipo_beneficiario like '".$_GET['c']."'"
											,$conexion_db);
	$regtipo_beneficiario=mysql_fetch_assoc($sql);
	
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		
		if (document.frmtipo_beneficiario.descripcion.value.length==0){
			mostrarMensajes("error", "Debe escribir una Descripcion para el Tipo de Presupuesto")
			document.frmtipo_beneficiario.descripcion.focus();
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
	<h4 align=center>Tipos de Beneficiario</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<form id="frmtipo_beneficiario" action="" method="POST" enctype="multipart/form-data">	
	<input type="hidden" id="codigo" name="codigo" maxlength="9" size="9" <?php echo 'value="'.$regtipo_beneficiario['idtipo_beneficiario'].'"';?>>
		<table align=center cellpadding=2 cellspacing=0 width="40%">
<tr>
				<td align='right' class='viewPropTitle'>Descripcion:</td>
				<td class=''><input type="text" name="descripcion" maxlength="255" size="45" id="descripcion" <?php echo 'value="'.$regtipo_beneficiario['descripcion'].'"';?> onKeyUp="validarVacios('descripcion', this.value, 'frmtipo_beneficiario')" onBlur="validarVacios('descripcion', this.value, 'frmtipo_beneficiario')" autocomplete="OFF" style="padding:0px 20px 0px 0px;">
			    
                <?
                if($_SESSION["modulo"] == 4){
				?>
                &nbsp;<a href='principal.php?modulo=4&accion=292' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Beneficiario"></a>
                <?
                }
				if($_SESSION["modulo"] == 3){
				?>
                &nbsp;<a href='principal.php?modulo=3&accion=70' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Beneficiario"></a>
                <?
                }
				if($_SESSION["modulo"] == 1){
				?>
                &nbsp;<a href='principal.php?modulo=1&accion=452' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Beneficiario"></a>
                <?
                }
				if($_SESSION["modulo"] == 2){
				?>
                &nbsp;<a href='principal.php?modulo=2&accion=519' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Beneficiario"></a>
                <?
                }
				
				if($_SESSION["modulo"] == 12){
				?>
                &nbsp;<a href='principal.php?modulo=12&accion=617' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Beneficiario"></a>
                <?
                }
				
				if($_SESSION["modulo"] == 13){
				?>
                &nbsp;<a href='principal.php?modulo=13&accion=687' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Beneficiario"></a>
                <?
                }
				
				if($_SESSION["modulo"] == 14){
				?>
                &nbsp;<a href='principal.php?modulo=14&accion=808' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Beneficiario"></a>
                <?
                }
				
				if($_SESSION["modulo"] == 16){
				?>
                &nbsp;<a href='principal.php?modulo=16&accion=898' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Beneficiario"></a>
                <?
                }
				if($_SESSION["modulo"] == 19){
				?>
                &nbsp;<a href='principal.php?modulo=19&accion=1035' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Beneficiario"></a>
                <?
                }
				?>
                
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=tipobene';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none';">X</a></div>          
          <iframe name="pdf" id="pdf" style="display:block" height="500" width="500"></iframe>          
          </div>
                
                
                </td>
			</tr>
		</table>
<table align=center cellpadding=2 cellspacing=0>
			<tr>
            <td>
			    <?php
    if($_REQUEST["pop"]){
	echo "<input type='hidden' value='true' name='pop' id='pop'>";
	}
	?>
			
			<?php
				if($_SESSION["modulo"] == 4){
					if($_GET["accion"] != 294 and $_GET["accion"] != 295 and in_array(293, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 294 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 295 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				if($_SESSION["modulo"] == 3){
					if($_GET["accion"] != 120 and $_GET["accion"] != 121 and in_array(119, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar2' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 120 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 121 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				if($_SESSION["modulo"] == 1){
					if($_GET["accion"] != 454 and $_GET["accion"] != 455 and in_array(453, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar3' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 454 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 455 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				if($_SESSION["modulo"] == 2){
					if($_GET["accion"] != 521 and $_GET["accion"] != 522 and in_array(520, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar4' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 521 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 522 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				
				if($_SESSION["modulo"] == 12){
					if($_GET["accion"] != 619 and $_GET["accion"] != 620 and in_array(618, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar5' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 619 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 620 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				if($_SESSION["modulo"] == 13){
					if($_GET["accion"] != 689 and $_GET["accion"] != 690 and in_array(688, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar6' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 689 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 690 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				
				if($_SESSION["modulo"] == 14){
					if($_GET["accion"] != 810 and $_GET["accion"] != 811 and in_array(809, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar7' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 810 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 811 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				
				
				
				if($_SESSION["modulo"] == 16){
					if($_GET["accion"] != 900 and $_GET["accion"] != 901 and in_array(898, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar8' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 900 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 901 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				if($_SESSION["modulo"] == 19){
					if($_GET["accion"] != 1048 and $_GET["accion"] != 1049 and in_array(1047, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar9' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 1048 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 1049 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
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
						<form name="grilla" action="" method="POST">
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
									$c=$llenar_grilla["idtipo_beneficiario"];
									
									
								if($_SESSION["modulo"] == 4){
									if(in_array(294,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=294&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(295,$privilegios)==true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=295&&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								if($_SESSION["modulo"] == 3){
									if(in_array(120,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=120&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(121,$privilegios)==true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=121&&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								
								
								
								if($_SESSION["modulo"] == 1){
									if(in_array(454,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=1&accion=454&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(455,$privilegios)==true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=1&accion=455&&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								
								if($_SESSION["modulo"] == 2){
									if(in_array(521,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=521&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(522,$privilegios)==true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=522&&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								
								
								if($_SESSION["modulo"] == 12){
									if(in_array(619,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=619&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(620,$privilegios)==true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=620&&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								
								if($_SESSION["modulo"] == 13){
									if(in_array(689,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=13&accion=689&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(690,$privilegios)==true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=13&accion=690&&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								if($_SESSION["modulo"] == 14){
									if(in_array(810,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=810&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(811,$privilegios)==true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=811&&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								
								
								if($_SESSION["modulo"] == 16){
									if(in_array(900,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=900&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(901,$privilegios)==true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=901&&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								if($_SESSION["modulo"] == 19){
									if(in_array(1048,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1048&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(1049,$privilegios)==true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1049&&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
 <script>document.getElementById('descripcion').focus()</script>
</body>
</html>

<?php
if($_POST){
	$codigo=$_POST["codigo"];
	$descripcion=strtoupper($_POST["descripcion"]);
	$busca_existe_registro=mysql_query("select * from tipo_beneficiario where descripcion = '".$_POST['descripcion']."'  and status='a'",$conexion_db);
	
	if($_SESSION["modulo"] == 4){
	
		if($_GET["accion"] == 293 and in_array(293, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
				<script>
				mostrarMensajes("error", "Disculpe el registro que intenta Ingresar ya existe, por favor vuelva a intentarlo");
				setTimeout("window.location.href='principal.php?modulo=4&accion=292'");
				</script>
				<?

			}else{
			
				mysql_query("insert into tipo_beneficiario
										(descripcion,usuario,fechayhora,status) 
								values ('$descripcion','$login','$fh','a')"
										,$conexion_db);
				// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Tipoo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				if($_POST["pop"]){
					?>
					<script>
						actualizaSelect("idtipo_beneficiario", "lib/consultar_tablas_select.php", "tipo_beneficiario", <?php echo $idcreado?>);
					</script>
					<?php
				}else{
					?>
				<script>
				mostrarMensajes("exito", "El registro se inserto con exito");
				setTimeout("window.location.href='principal.php?modulo=4&accion=292'");
				</script>
				<?

				}
				
			}
		}
		if ($_GET["accion"] == 294 and in_array(294,$privilegios) == true and !$_POST["buscar"]){
				mysql_query("update tipo_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idtipo_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Tipo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				?>
				<script>
				mostrarMensajes("exito", "El registro se Modifico con exito");
				setTimeout("window.location.href='principal.php?modulo=4&accion=292'");
				</script>
				<?
		}
		if ($_GET["accion"] == 295 and in_array(295,$privilegios)==true and !$_POST["buscar"]){
				$sql = mysql_query("select * from tipo_beneficiario where idtipo_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from tipo_beneficiario where idtipo_beneficiario = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Tipo de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
				<script>
				mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				setTimeout("window.location.href='principal.php?modulo=4&accion=292'");
				</script>
				<?
						
					}else{
						registra_transaccion('Eliminar Tipo de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
				<script>
				mostrarMensajes("exito", El registro se Elimino con exito"");
				setTimeout("window.location.href='principal.php?modulo=4&accion=292'");
				</script>
				<?					
			
			}	
		}
	}
	
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 3){
	
		if($_GET["accion"] == 119 and in_array(119, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				
				?>
				<script>
				mostrarMensajes("error", "Disculpe el registro que intenta Ingresar ya existe, por favor vuelva a intentarlo");
				setTimeout("window.location.href='principal.php?modulo=3&accion=70'");
				</script>
				<?
				
			}else{
			
				mysql_query("insert into tipo_beneficiario
										(descripcion,usuario,fechayhora,status) 
								values ('$descripcion','$login','$fh','a')"
										,$conexion_db);
				// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Tipoo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				if($_POST["pop"]){
					?>
					<script>
						actualizaSelect("idtipo_beneficiario", "lib/consultar_tablas_select.php", "tipo_beneficiario", <?php echo $idcreado?>);
					</script>
					<?php
				}else{
					?>
				<script>
				mostrarMensajes("exito", "El registro se inserto con exito");
				setTimeout("window.location.href='principal.php?modulo=3&accion=70'");
				</script>
				<?
				}

			}
		}
		if ($_GET["accion"] == 120 and in_array(120,$privilegios) == true and !$_POST["buscar"]){
				mysql_query("update tipo_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idtipo_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Tipo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				?>
				<script>
				mostrarMensajes("exito", "El registro se Modifico con exito");
				setTimeout("window.location.href='principal.php?modulo=3&accion=70'");
				</script>
				<?
		}
		if ($_GET["accion"] == 121 and in_array(121,$privilegios)==true and !$_POST["buscar"]){
				$sql = mysql_query("select * from tipo_beneficiario where idtipo_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from tipo_beneficiario where idtipo_beneficiario = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Tipo de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
				<script>
				mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				setTimeout("window.location.href='principal.php?modulo=3&accion=70'");
				</script>
				<?
					}else{
						registra_transaccion('Eliminar Tipo de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
				<script>
				mostrarMensajes("exito", "El registro se Elimino con exito");
				setTimeout("window.location.href='principal.php?modulo=3&accion=70'");
				</script>
				<?
					}	
		}
	}
	
	
	
	
	
	
		if($_SESSION["modulo"] == 1){
	
		if($_GET["accion"] == 453 and in_array(453, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
				<script>
				mostrarMensajes("error", "Disculpe el registro que intenta Ingresar ya existe, por favor vuelva a intentarlo");
				setTimeout("window.location.href='principal.php?modulo=1&accion=452'");
				</script>
				<?
			}else{
			
				mysql_query("insert into tipo_beneficiario
										(descripcion,usuario,fechayhora,status) 
								values ('$descripcion','$login','$fh','a')"
										,$conexion_db);
				// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Tipoo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				if($_POST["pop"]){
					?>
					<script>
						actualizaSelect("idtipo_beneficiario", "lib/consultar_tablas_select.php", "tipo_beneficiario", <?php echo $idcreado?>);
					</script>
					<?php
				}else{
					
					?>
				<script>
				mostrarMensajes("exito", "El registro se inserto con exito");
				setTimeout("window.location.href='principal.php?modulo=1&accion=452'");
				</script>
				<?
				}
			}
		}
		if ($_GET["accion"] == 454 and in_array(454,$privilegios) == true and !$_POST["buscar"]){
				mysql_query("update tipo_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idtipo_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Tipo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				?>
				<script>
				mostrarMensajes("exito", "El registro se Modifico con exito");
				setTimeout("window.location.href='principal.php?modulo=1&accion=452'");
				</script>
				<?

		}
		
		
		if ($_GET["accion"] == 455 and in_array(455,$privilegios)==true and !$_POST["buscar"]){
				$sql = mysql_query("select * from tipo_beneficiario where idtipo_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from tipo_beneficiario where idtipo_beneficiario = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Tipo de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
				<script>
				mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				setTimeout("window.location.href='principal.php?modulo=1&accion=452'");
				</script>
				<?
					}else{
						registra_transaccion('Eliminar Tipo de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
				<script>
				mostrarMensajes("exito", "El registro se Elimino con exito");
				setTimeout("window.location.href='principal.php?modulo=1&accion=452'");
				</script>
				<?
					}	
		}
	}
	
	
	
	
	
	
	if($_SESSION["modulo"] == 2){
	
		if($_GET["accion"] == 520 and in_array(520, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
				<script>
				mostrarMensajes("error", "Disculpe el registro que intenta Ingresar ya existe, por favor vuelva a intentarlo");
				setTimeout("window.location.href='principal.php?modulo=2&accion=590'");
				</script>
				<?
				
			}else{
			
				mysql_query("insert into tipo_beneficiario
										(descripcion,usuario,fechayhora,status) 
								values ('$descripcion','$login','$fh','a')"
										,$conexion_db);
				// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Tipoo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				if($_POST["pop"]){
					?>
					<script>
						actualizaSelect("idtipo_beneficiario", "lib/consultar_tablas_select.php", "tipo_beneficiario", <?php echo $idcreado?>);
					</script>
					<?php
				}else{
					?>
				<script>
				mostrarMensajes("exito", "El registro se inserto con exito");
				setTimeout("window.location.href='principal.php?modulo=2&accion=590'");
				</script>
				<?
					
				}

			}
		}
		if ($_GET["accion"] == 521 and in_array(521,$privilegios) == true and !$_POST["buscar"]){
				mysql_query("update tipo_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idtipo_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Tipo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				?>
				<script>
				mostrarMensajes("exito", "El registro se Modifico con exito");
				setTimeout("window.location.href='principal.php?modulo=2&accion=590'");
				</script>
				<?
		}
		
		
		if ($_GET["accion"] == 522 and in_array(522,$privilegios)==true and !$_POST["buscar"]){
				$sql = mysql_query("select * from tipo_beneficiario where idtipo_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from tipo_beneficiario where idtipo_beneficiario = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Tipo de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
				<script>
				mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				setTimeout("window.location.href='principal.php?modulo=2&accion=590'");
				</script>
				<?
					}else{
						registra_transaccion('Eliminar Tipo de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
				<script>
				mostrarMensajes("exito", "El registro se Elimino con exito");
				setTimeout("window.location.href='principal.php?modulo=2&accion=590'");
				</script>
				<?
					}	
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 12){
	
		if($_GET["accion"] == 618 and in_array(618, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				
				?>
				<script>
				mostrarMensajes("error", "Disculpe el registro que intenta Ingresar ya existe, por favor vuelva a intentarlo");
				setTimeout("window.location.href='principal.php?modulo=12&accion=617'");
				</script>
				<?
			}else{
			
				mysql_query("insert into tipo_beneficiario
										(descripcion,usuario,fechayhora,status) 
								values ('$descripcion','$login','$fh','a')"
										,$conexion_db);
				// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Tipoo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				if($_POST["pop"]){
					?>
					<script>
						actualizaSelect("idtipo_beneficiario", "lib/consultar_tablas_select.php", "tipo_beneficiario", <?php echo $idcreado?>);
					</script>
					<?php
				}else{
					
					?>
				<script>
				mostrarMensajes("exito", "El registro se inserto con exito");
				setTimeout("window.location.href='principal.php?modulo=12&accion=617'");
				</script>
				<?
				}

			}
		}
		if ($_GET["accion"] == 619 and in_array(619,$privilegios) == true and !$_POST["buscar"]){
				mysql_query("update tipo_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idtipo_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Tipo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				?>
				<script>
				mostrarMensajes("exito", "El registro se Modifico con exito");
				setTimeout("window.location.href='principal.php?modulo=12&accion=617'");
				</script>
				<?
						}
		
		
		if ($_GET["accion"] == 620 and in_array(620,$privilegios)==true and !$_POST["buscar"]){
				$sql = mysql_query("select * from tipo_beneficiario where idtipo_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from tipo_beneficiario where idtipo_beneficiario = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Tipo de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
				<script>
				mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				setTimeout("window.location.href='principal.php?modulo=12&accion=617'");
				</script>
				<?
					}else{
						registra_transaccion('Eliminar Tipo de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
				<script>
				mostrarMensajes("exito", "El registro se Elimino con exito");
				setTimeout("window.location.href='principal.php?modulo=12&accion=617'");
				</script>
				<?
					}	
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 13){
	
		if($_GET["accion"] == 688 and in_array(688, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
				<script>
				mostrarMensajes("error", "Disculpe el registro que intenta Ingresar ya existe, por favor vuelva a intentarlo");
				setTimeout("window.location.href='principal.php?modulo=13&accion=687'");
				</script>
				<?

			}else{
			
				mysql_query("insert into tipo_beneficiario
										(descripcion,usuario,fechayhora,status) 
								values ('$descripcion','$login','$fh','a')"
										,$conexion_db);
				// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Tipoo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				if($_POST["pop"]){
					?>
					<script>
						actualizaSelect("idtipo_beneficiario", "lib/consultar_tablas_select.php", "tipo_beneficiario", <?php echo $idcreado?>);
					</script>
					<?php
				}else{
					?>
				<script>
				mostrarMensajes("exito", "El registro se inserto con exito");
				setTimeout("window.location.href='principal.php?modulo=13&accion=687'");
				</script>
				<?
					
				}
			}
		}
		if ($_GET["accion"] == 689 and in_array(689,$privilegios) == true and !$_POST["buscar"]){
				mysql_query("update tipo_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idtipo_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Tipo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				?>
				<script>
				mostrarMensajes("exito", "El registro se Modifico con exito");
				setTimeout("window.location.href='principal.php?modulo=13&accion=687'");
				</script>
				<?
		}
		
		
		if ($_GET["accion"] == 690 and in_array(690,$privilegios)==true and !$_POST["buscar"]){
				$sql = mysql_query("select * from tipo_beneficiario where idtipo_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from tipo_beneficiario where idtipo_beneficiario = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Tipo de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
				<script>
				mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				setTimeout("window.location.href='principal.php?modulo=13&accion=687'");
				</script>
				<?
					}else{
						registra_transaccion('Eliminar Tipo de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
				<script>
				mostrarMensajes("error", "El registro se Elimino con exito");
				setTimeout("window.location.href='principal.php?modulo=13&accion=687'");
				</script>
				<?
					}	
		}
	}
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 14){
	
		if($_GET["accion"] == 809 and in_array(809, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
				<script>
				mostrarMensajes("error", "Disculpe el registro que intenta Ingresar ya existe, por favor vuelva a intentarlo");
				setTimeout("window.location.href='principal.php?modulo=14&accion=808'");
				</script>
				<?
				
			}else{
			
				mysql_query("insert into tipo_beneficiario
										(descripcion,usuario,fechayhora,status) 
								values ('$descripcion','$login','$fh','a')"
										,$conexion_db);
				// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Tipoo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				if($_POST["pop"]){
					?>
					<script>
						actualizaSelect("idtipo_beneficiario", "lib/consultar_tablas_select.php", "tipo_beneficiario", <?php echo $idcreado?>);
					</script>
					<?php
				}else{
					
					?>
				<script>
				mostrarMensajes("exito", "El registro se inserto con exito");
				setTimeout("window.location.href='principal.php?modulo=14&accion=808'");
				</script>
				<?
				}
			}
		}
		if ($_GET["accion"] == 810 and in_array(810,$privilegios) == true and !$_POST["buscar"]){
				mysql_query("update tipo_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idtipo_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Tipo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				?>
				<script>
				mostrarMensajes("exito", "El registro se Modifico con exito");
				setTimeout("window.location.href='principal.php?modulo=14&accion=808'");
				</script>
				<?
		}
		
		
		if ($_GET["accion"] == 811 and in_array(811,$privilegios)==true and !$_POST["buscar"]){
				$sql = mysql_query("select * from tipo_beneficiario where idtipo_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from tipo_beneficiario where idtipo_beneficiario = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Tipo de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
				<script>
				mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				setTimeout("window.location.href='principal.php?modulo=14&accion=808'");
				</script>
				<?
						

					}else{
						registra_transaccion('Eliminar Tipo de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
				<script>
				mostrarMensajes("exito", "El registro se Elimino con exito");
				setTimeout("window.location.href='principal.php?modulo=14&accion=808'");
				</script>
				<?

					}	

		}
	}
	
	
	
	
	if($_SESSION["modulo"] == 16){
	
		if($_GET["accion"] == 899 and in_array(899, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
				<script>
				mostrarMensajes("error", "Disculpe el registro que intenta Ingresar ya existe, por favor vuelva a intentarlo");
				setTimeout("window.location.href='principal.php?modulo=16&accion=898'");
				</script>
				<?
			}else{
			
				mysql_query("insert into tipo_beneficiario
										(descripcion,usuario,fechayhora,status) 
								values ('$descripcion','$login','$fh','a')"
										,$conexion_db);
				// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Tipoo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				if($_POST["pop"]){
					?>
					<script>
						actualizaSelect("idtipo_beneficiario", "lib/consultar_tablas_select.php", "tipo_beneficiario", <?php echo $idcreado?>);
					</script>
					<?php
				}else{
					
					?>
				<script>
				mostrarMensajes("exito", "El registro se inserto con exito");
				setTimeout("window.location.href='principal.php?modulo=16&accion=898'");
				</script>
				<?
					
				}
			}
		}
		if ($_GET["accion"] == 900 and in_array(900,$privilegios) == true and !$_POST["buscar"]){
				mysql_query("update tipo_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idtipo_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Tipo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				?>
				<script>
				mostrarMensajes("exito", "El registro se Modifico con exit");
				setTimeout("window.location.href='principal.php?modulo=16&accion=898'");
				</script>
				<?
		}
		
		
		if ($_GET["accion"] == 901 and in_array(901,$privilegios)==true and !$_POST["buscar"]){
				$sql = mysql_query("select * from tipo_beneficiario where idtipo_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from tipo_beneficiario where idtipo_beneficiario = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Tipo de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
				<script>
				mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				setTimeout("window.location.href='principal.php?modulo=16&accion=898'");
				</script>
				<?
						
					}else{
						registra_transaccion('Eliminar Tipo de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
				<script>
				mostrarMensajes("error", "El registro se Elimino con exito");
				setTimeout("window.location.href='principal.php?modulo=16&accion=898'");
				</script>
				<?
						
					}	
		}
	}
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 19){
	
		if($_GET["accion"] == 1047 and in_array(1047, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				?>
				<script>
				mostrarMensajes("error", "Disculpe el registro que intenta Ingresar ya existe, por favor vuelva a intentarlo");
				setTimeout("window.location.href='principal.php?modulo=19&accion=1035'");
				</script>
				<?
			}else{
			
				mysql_query("insert into tipo_beneficiario
										(descripcion,usuario,fechayhora,status) 
								values ('$descripcion','$login','$fh','a')"
										,$conexion_db);
				// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Tipoo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				if($_POST["pop"]){
					?>
					<script>
						actualizaSelect("idtipo_beneficiario", "lib/consultar_tablas_select.php", "tipo_beneficiario", <?php echo $idcreado?>);
					</script>
					<?php
				}else{
					
					?>
				<script>
				mostrarMensajes("exito", "El registro se inserto con exito");
				setTimeout("window.location.href='principal.php?modulo=119&accion=1035'");
				</script>
				<?
					
				}
			}
		}
		if ($_GET["accion"] == 1048 and in_array(1048,$privilegios) == true and !$_POST["buscar"]){
				mysql_query("update tipo_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idtipo_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Tipo de Beneficiario ('.$descripcion.')',$login,$fh,$pc,'tipo_beneficiario',$conexion_db);
				
				?>
				<script>
				mostrarMensajes("exito", "El registro se Modifico con exit");
				setTimeout("window.location.href='principal.php?modulo=19&accion=1035'");
				</script>
				<?
		}
		
		
		if ($_GET["accion"] == 1049 and in_array(1049,$privilegios)==true and !$_POST["buscar"]){
				$sql = mysql_query("select * from tipo_beneficiario where idtipo_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from tipo_beneficiario where idtipo_beneficiario = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Tipo de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
				<script>
				mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				setTimeout("window.location.href='principal.php?modulo=19&accion=1035'");
				</script>
				<?
						
					}else{
						registra_transaccion('Eliminar Tipo de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
				<script>
				mostrarMensajes("error", "El registro se Elimino con exito");
				setTimeout("window.location.href='principal.php?modulo=19&accion=1035'");
				</script>
				<?
						
					}	
		}
	}
	
	
	
	
	
	
	
	
}
?>