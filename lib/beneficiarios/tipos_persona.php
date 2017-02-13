<?php
if($_POST["ingresar"]){
$_GET["accion"] = 301;
}
if($_POST["ingresar2"]){
$_GET["accion"] = 128;
}
if($_POST["ingresar3"]){
$_GET["accion"] = 461;
}
if($_POST["ingresar4"]){
$_GET["accion"] = 528;
}
if($_POST["ingresar5"]){
$_GET["accion"] = 626;
}

if($_POST["ingresar6"]){
$_GET["accion"] = 696;
}

if($_POST["ingresar7"]){
$_GET["accion"] = 817;
}

if($_POST["ingresar8"]){
$_GET["accion"] = 907;
}

if($_POST["ingresar9"]){
$_GET["accion"] = 984;
}

if($_POST["ingresar10"]){
$_GET["accion"] = 1053;
}
if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from tipos_persona 
												where status='a' order by descripcion"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from tipos_persona where status='a'";
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
	$sql=mysql_query("select * from tipos_persona 
										where idtipos_persona like '".$_GET['c']."'"
											,$conexion_db);
	$regtipos_persona=mysql_fetch_assoc($sql);
	
}


?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		
		if (document.frmtipos_persona.descripcion.value.length==0){
			mostrarMensajes("error", "debe escribir una descripcion para tl tipo de presupuesto");
			document.frmtipos_persona.descripcion.focus();
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
	<h4 align=center>Tipos de Persona</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<form id="frmtipos_persona" action="" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
	<input type="hidden" id="codigo" name="codigo" maxlength="9" size="9" <?php echo 'value="'.$regtipos_persona['idtipos_persona'].'"';?>>
		<table align=center cellpadding=2 cellspacing=0 width="40%">
<tr>
				<td align='right' class='viewPropTitle'>Descripcion:</td>
				<td class=''><input type="text" name="descripcion" maxlength="255" size="45" id="descripcion" <?php echo 'value="'.$regtipos_persona['descripcion'].'"';?> onKeyUp="validarVacios('descripcion', this.value, 'frmtipos_persona')" onBlur="validarVacios('descripcion', this.value, 'frmtipos_persona')" autocomplete="OFF" style="padding:0px 20px 0px 0px;">
			    
                <?
                if($_SESSION["modulo"] == 4){
				?>
                    &nbsp;<a href='principal.php?modulo=4&accion=300' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Persona"></a>
                <?
                }
                if($_SESSION["modulo"] == 3){
				?>
                    &nbsp;<a href='principal.php?modulo=3&accion=72' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Persona"></a>
                <?
                }
				 if($_SESSION["modulo"] == 1){
				?>
                    &nbsp;<a href='principal.php?modulo=1&accion=460' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Persona"></a>
                <?
                }
				if($_SESSION["modulo"] == 2){
				?>
                    &nbsp;<a href='principal.php?modulo=2&accion=527' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Persona"></a>
                <?
                }
				if($_SESSION["modulo"] == 12){
				?>
                    &nbsp;<a href='principal.php?modulo=12&accion=625' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Persona"></a>
                <?
                }
				if($_SESSION["modulo"] == 13){
				?>
                    &nbsp;<a href='principal.php?modulo=13&accion=695' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Persona"></a>
                <?
                }
				
				if($_SESSION["modulo"] == 14){
				?>
                    &nbsp;<a href='principal.php?modulo=14&accion=816' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Persona"></a>
                <?
                }
				
				if($_SESSION["modulo"] == 16){
				?>
                    &nbsp;<a href='principal.php?modulo=16&accion=906' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Persona"></a>
                <?
                }
				if($_SESSION["modulo"] == 17){
				?>
                    &nbsp;<a href='principal.php?modulo=17&accion=981' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Persona"></a>
                <?
                }
				if($_SESSION["modulo"] == 19){
				?>
                    &nbsp;<a href='principal.php?modulo=19&accion=1037' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Persona"></a>
                <?
                }
				?>
                
                
                
                
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=tipopersona';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
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
	?>
			<?php
				if($_SESSION["modulo"] == 4){
					if($_GET["accion"] != 302 and $_GET["accion"] != 303 and in_array(301, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 302 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 303 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				if($_SESSION["modulo"] == 3){
					if($_GET["accion"] != 129 and $_GET["accion"] != 130 and in_array(128, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar3' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 129 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 130 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				if($_SESSION["modulo"] == 1){
					if($_GET["accion"] != 462 and $_GET["accion"] != 463 and in_array(461, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar3' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 462 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 463 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				if($_SESSION["modulo"] == 2){
					if($_GET["accion"] != 529 and $_GET["accion"] != 530 and in_array(528, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar4' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 529 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 530 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				if($_SESSION["modulo"] == 12){
					if($_GET["accion"] != 627 and $_GET["accion"] != 628 and in_array(626, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar5' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 627 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 628 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				
				if($_SESSION["modulo"] == 12){
					if($_GET["accion"] != 697 and $_GET["accion"] != 698 and in_array(696, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar6' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 697 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 698 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				
				if($_SESSION["modulo"] == 14){
					if($_GET["accion"] != 818 and $_GET["accion"] != 819 and in_array(817, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar7' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 818 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 819 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				
				
				if($_SESSION["modulo"] == 16){
					if($_GET["accion"] != 908 and $_GET["accion"] != 909 and in_array(907, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar8' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 908 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 909 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				
				
				
				if($_SESSION["modulo"] == 17){
					if($_GET["accion"] != 986 and $_GET["accion"] != 985 and in_array(984, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar9' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 985 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 986 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				if($_SESSION["modulo"] == 19){
					if($_GET["accion"] != 1054 and $_GET["accion"] != 1055 and in_array(1053, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar10' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 1054 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 1055 and in_array($_GET["accion"], $privilegios) == true){
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
								
									<td align='left' class='Browse'><?=$llenar_grilla["descripcion"]?></td>
								<?php
                                $c=$llenar_grilla["idtipos_persona"];	
								if($_SESSION["modulo"] == 4){
									if(in_array(302, $privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=4&accion=302&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                            </a>
                                        </td>
                                        <?
									}
									if(in_array(303,$privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=4&accion=303&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                            </a>
                                        </td>
                                        <?
									}
								}	
								
								
								
								
								if($_SESSION["modulo"] == 3){
									if(in_array(129, $privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=3&accion=129&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                            </a>
                                        </td>
                                        <?
									}
									if(in_array(130,$privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=3&accion=130&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                            </a>
                                       </td>
                                       <?
									}
								}	
									
								
								if($_SESSION["modulo"] == 1){
									if(in_array(462, $privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=1&accion=462&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                            </a>
                                       </td>
                                       <?
									}
									if(in_array(463,$privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=1&accion=463&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                            </a>
                                        </td>
                                        <?	
									}
								}
								
								
								
								if($_SESSION["modulo"] == 2){
									if(in_array(529, $privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=2&accion=529&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                            </a>
                                        </td>
                                        <?
									}
									if(in_array(530,$privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=2&accion=530&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                            </a>
                                      	</td>
                                        <?
									}
								}
								
								
								
								
								if($_SESSION["modulo"] == 12){
									if(in_array(627, $privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=12&accion=627&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                            </a>
                                        </td>
                                        <?
									}
									if(in_array(628,$privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=12&accion=628&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                            </a>
                                      	</td>
                                        <?
									}
								}
								
								
								
								
								
								if($_SESSION["modulo"] == 13){
									if(in_array(697, $privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=13&accion=697&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                            </a>
                                        </td>
                                        <?
									}
									if(in_array(698,$privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=13&accion=698&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                            </a>
                                      	</td>
                                        <?
									}
								}
								
								
								
								
								if($_SESSION["modulo"] == 14){
									if(in_array(818, $privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=14&accion=818&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                            </a>
                                        </td>
                                        <?
									}
									if(in_array(819,$privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=14&accion=819&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                            </a>
                                      	</td>
                                        <?
									}
								}
								
								
								
								
								
								if($_SESSION["modulo"] == 16){
									if(in_array(908, $privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=16&accion=908&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                            </a>
                                        </td>
                                        <?
									}
									if(in_array(909,$privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=16&accion=909&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                            </a>
                                      	</td>
                                        <?
									}
								}
								
								
								
								
								
								
								if($_SESSION["modulo"] == 17){
									if(in_array(985, $privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=17&accion=985&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                            </a>
                                        </td>
                                        <?
									}
									if(in_array(986,$privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=17&accion=986&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                            </a>
                                      	</td>
                                        <?
									}
								}
								
								if($_SESSION["modulo"] == 19){
									if(in_array(1054, $privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=19&accion=1054&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                            </a>
                                        </td>
                                        <?
									}
									if(in_array(1055,$privilegios) == true){
										?>
                                        <td align='center' class='Browse' width='3%'>
                                        	<a href='principal.php?modulo=19&accion=1055&c=<?=$c?>' class='Browse'>
                                            	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                            </a>
                                      	</td>
                                        <?
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
	$busca_existe_registro=mysql_query("select * from tipos_persona where descripcion = '".$_POST['descripcion']."'  and status='a'",$conexion_db);
	
	if($_SESSION["modulo"] == 4){
	if($_GET["accion"] == 301 and in_array(301, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, Intentelo de nuevo");
        setTimeout("window.location.href='principal.php?modulo=4&accion=300'",5000);
        </script>
        <?
	}else{
		
			mysql_query("insert into tipos_persona
									(descripcion,usuario,fechayhora,status) 
							values ('$descripcion','$login','$fh','a')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipos_persona", "lib/consultar_tablas_select.php", "tipos_persona", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Ingreso con Exito");
        setTimeout("window.location.href='principal.php?modulo=4&accion=300'",5000);
        </script>
        <?
			}
			
		}
	}
	if ($_GET["accion"] == 302 and in_array(302, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipos_persona set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idtipos_persona = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=4&accion=300'",5000);
        </script>
        <?
	}
	if ($_GET["accion"] == 303 and in_array(303, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipos_persona where idtipos_persona = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipos_persona where idtipos_persona = '$codigo'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipos de Personas (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=4&accion=300'",5000);
        </script>
        <?

			}else{
				registra_transaccion('Eliminar Tipos de Personas ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Elimino con Exito");
        setTimeout("window.location.href='principal.php?modulo=4&accion=300'",5000);
        </script>
        <?
			}				
			

	}
	
	}
	
	
	
	
	
	
	
	
	
		if($_SESSION["modulo"] == 3){
	if($_GET["accion"] == 128 and in_array(128, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, Intentelo de nuevo");
        setTimeout("window.location.href='principal.php?modulo=3&accion=72'",5000);
        </script>
        <?
	}else{
		
			mysql_query("insert into tipos_persona
									(descripcion,usuario,fechayhora,status) 
							values ('$descripcion','$login','$fh','a')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipos_persona", "lib/consultar_tablas_select.php", "tipos_persona", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Ingreso con Exito");
        setTimeout("window.location.href='principal.php?modulo=3&accion=72'",5000);
        </script>
        <?
			}
		}
	}
	if ($_GET["accion"] == 129 and in_array(129, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipos_persona set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idtipos_persona = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=3&accion=72'",5000);
        </script>
        <?
	}
	if ($_GET["accion"] == 130 and in_array(130, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipos_persona where idtipos_persona = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipos_persona where idtipos_persona = '$codigo'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipos de Personas (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=3&accion=72'",5000);
        </script>
        <?

			}else{
				registra_transaccion('Eliminar Tipos de Personas ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Elimino con Exito");
        setTimeout("window.location.href='principal.php?modulo=3&accion=72'",5000);
        </script>
        <?
			}				
	}
	
	}
	
	
	
	
	
	if($_SESSION["modulo"] == 1){
	if($_GET["accion"] == 461 and in_array(461, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
		
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, Intentelo de nuevo");
        setTimeout("window.location.href='principal.php?modulo=1&accion=460'",5000);
        </script>
        <?
	}else{
		
			mysql_query("insert into tipos_persona
									(descripcion,usuario,fechayhora,status) 
							values ('$descripcion','$login','$fh','a')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipos_persona", "lib/consultar_tablas_select.php", "tipos_persona", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Ingreso con Exito");
        setTimeout("window.location.href='principal.php?modulo=1&accion=460'",5000);
        </script>
        <?
			}
		}
	}
	if ($_GET["accion"] == 462 and in_array(462, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipos_persona set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idtipos_persona = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=1&accion=460'",5000);
        </script>
        <?
	}
	if ($_GET["accion"] == 463 and in_array(463, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipos_persona where idtipos_persona = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipos_persona where idtipos_persona = '$codigo'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipos de Personas (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=1&accion=460'",5000);
        </script>
        <?
			}else{
				registra_transaccion('Eliminar Tipos de Personas ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Elimino con Exito");
        setTimeout("window.location.href='principal.php?modulo=1&accion=460'",5000);
        </script>
        <?
			}				
	}
	
	}
	
	
	
	
	
	if($_SESSION["modulo"] == 2){
	if($_GET["accion"] == 528 and in_array(528, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, Intentelo de nuevo");
        setTimeout("window.location.href='principal.php?modulo=2&accion=527'",5000);
        </script>
        <?
	}else{
		
			mysql_query("insert into tipos_persona
									(descripcion,usuario,fechayhora,status) 
							values ('$descripcion','$login','$fh','a')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipos_persona", "lib/consultar_tablas_select.php", "tipos_persona", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Ingreso con Exito");
        setTimeout("window.location.href='principal.php?modulo=2&accion=527'",5000);
        </script>
        <?
			}
		}
	}
	if ($_GET["accion"] == 529 and in_array(529, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipos_persona set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idtipos_persona = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=2&accion=527'",5000);
        </script>
        <?

	}
	if ($_GET["accion"] == 530 and in_array(530, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipos_persona where idtipos_persona = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipos_persona where idtipos_persona = '$codigo'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipos de Personas (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=2&accion=527'",5000);
        </script>
        <?

			}else{
				registra_transaccion('Eliminar Tipos de Personas ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
			
			?>
		<script>
        mostrarMensajes("exito", "El regsitro se Elimino con Exito");
        setTimeout("window.location.href='principal.php?modulo=2&accion=527'",5000);
        </script>
        <?	
			}				
	}
	
	}
	
	
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 12){
	if($_GET["accion"] == 626 and in_array(626, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, Intentelo de nuevo");
        setTimeout("window.location.href='principal.php?modulo=12&accion=625'",5000);
        </script>
        <?
	}else{
		
			mysql_query("insert into tipos_persona
									(descripcion,usuario,fechayhora,status) 
							values ('$descripcion','$login','$fh','a')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipos_persona", "lib/consultar_tablas_select.php", "tipos_persona", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Ingreso con Exito");
        setTimeout("window.location.href='principal.php?modulo=12&accion=625'",5000);
        </script>
        <?
			}
		}
	}
	if ($_GET["accion"] == 627 and in_array(627, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipos_persona set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idtipos_persona = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=12&accion=625'",5000);
        </script>
        <?	
	}
	if ($_GET["accion"] == 628 and in_array(628, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipos_persona where idtipos_persona = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipos_persona where idtipos_persona = '$codigo'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipos de Personas (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=12&accion=625'",5000);
        </script>
        <?

			}else{
				registra_transaccion('Eliminar Tipos de Personas ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Elimino con Exito");
        setTimeout("window.location.href='principal.php?modulo=12&accion=625'",5000);
        </script>
        <?
			}				
	}
	
	}
	
	
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 13){
	if($_GET["accion"] == 696 and in_array(696, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, Intentelo de nuevo");
        setTimeout("window.location.href='principal.php?modulo=13&accion=695'",5000);
        </script>
        <?
	}else{
		
			mysql_query("insert into tipos_persona
									(descripcion,usuario,fechayhora,status) 
							values ('$descripcion','$login','$fh','a')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipos_persona", "lib/consultar_tablas_select.php", "tipos_persona", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Ingreso con Exito");
        setTimeout("window.location.href='principal.php?modulo=13&accion=695'",5000);
        </script>
        <?
			}
		}
	}
	if ($_GET["accion"] == 697 and in_array(697, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipos_persona set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idtipos_persona = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=13&accion=695'",5000);
        </script>
        <?
	}
	if ($_GET["accion"] == 698 and in_array(698, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipos_persona where idtipos_persona = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipos_persona where idtipos_persona = '$codigo'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipos de Personas (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=13&accion=695'",5000);
        </script>
        <?

			}else{
				registra_transaccion('Eliminar Tipos de Personas ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				
				?>
				<script>
                mostrarMensajes("exito", "El regsitro se Elimino con Exito");
                setTimeout("window.location.href='principal.php?modulo=13&accion=695'",5000);
                </script>
                <?
			}				
			
	}
	
	}
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 14){
	if($_GET["accion"] == 817 and in_array(817, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
		
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, Intentelo de nuevo");
        setTimeout("window.location.href='principal.php?modulo=14&accion=816'",5000);
        </script>
        <?
	}else{
		
			mysql_query("insert into tipos_persona
									(descripcion,usuario,fechayhora,status) 
							values ('$descripcion','$login','$fh','a')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipos_persona", "lib/consultar_tablas_select.php", "tipos_persona", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Ingreso con Exito");
        setTimeout("window.location.href='principal.php?modulo=14&accion=816'",5000);
        </script>
        <?
			}
		}
	}
	if ($_GET["accion"] == 818 and in_array(818, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipos_persona set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idtipos_persona = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=14&accion=816'",5000);
        </script>
        <?

	}
	if ($_GET["accion"] == 819 and in_array(819, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipos_persona where idtipos_persona = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipos_persona where idtipos_persona = '$codigo'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipos de Personas (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=14&accion=816'",5000);
        </script>
        <?

			}else{
				registra_transaccion('Eliminar Tipos de Personas ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Elimino con Exito");
        setTimeout("window.location.href='principal.php?modulo=14&accion=816'",5000);
        </script>
        <?
			}				
	}
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 16){
	if($_GET["accion"] == 907 and in_array(907, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, Intentelo de nuevo");
        setTimeout("window.location.href='principal.php?modulo=16&accion=906'",5000);
        </script>
        <?
	}else{
		
			mysql_query("insert into tipos_persona
									(descripcion,usuario,fechayhora,status) 
							values ('$descripcion','$login','$fh','a')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipos_persona", "lib/consultar_tablas_select.php", "tipos_persona", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Ingreso con Exito");
        setTimeout("window.location.href='principal.php?modulo=16&accion=906'",5000);
        </script>
        <?
			}
		}
	}
	if ($_GET["accion"] == 908 and in_array(908, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipos_persona set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idtipos_persona = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=16&accion=906'",5000);
        </script>
        <?

	}
	if ($_GET["accion"] == 909 and in_array(909, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipos_persona where idtipos_persona = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipos_persona where idtipos_persona = '$codigo'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipos de Personas (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=16&accion=906'",5000);
        </script>
        <?

			}else{
				registra_transaccion('Eliminar Tipos de Personas ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Elimino con Exito");
        setTimeout("window.location.href='principal.php?modulo=16&accion=906'",5000);
        </script>
        <?
			}				
	}
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 17){
	if($_GET["accion"] == 984 and in_array(984, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, Intentelo de nuevo");
        setTimeout("window.location.href='principal.php?modulo=17&accion=981'",5000);
        </script>
        <?
	}else{
		
			mysql_query("insert into tipos_persona
									(descripcion,usuario,fechayhora,status) 
							values ('$descripcion','$login','$fh','a')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("tipo_persona", "lib/consultar_tablas_select.php", "tipos_persona", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
		<script>
        mostrarMensajes("extio", "El regsitro se Ingreso con Exito");
        setTimeout("window.location.href='principal.php?modulo=17&accion=981'",5000);
        </script>
        <?
			}
			
		}
	}
	if ($_GET["accion"] == 985 and in_array(985, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipos_persona set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idtipos_persona = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=17&accion=981'",5000);
        </script>
        <?
	}
	if ($_GET["accion"] == 986 and in_array(986, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipos_persona where idtipos_persona = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipos_persona where idtipos_persona = '$codigo'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipos de Personas (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=17&accion=981'",5000);
        </script>
        <?

			}else{
				
				registra_transaccion('Eliminar Tipos de Personas ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Elimino con Exito");
        setTimeout("window.location.href='principal.php?modulo=17&accion=981'",5000);
        </script>
        <?
			}				
	}
	
	}
	
	
	
	
	if($_SESSION["modulo"] == 19){
	if($_GET["accion"] == 1053 and in_array(1053, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, Intentelo de nuevo");
        setTimeout("window.location.href='principal.php?modulo=19&accion=1037'",5000);
        </script>
        <?
	}else{
		
			mysql_query("insert into tipos_persona
									(descripcion,usuario,fechayhora,status) 
							values ('$descripcion','$login','$fh','a')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("tipo_persona", "lib/consultar_tablas_select.php", "tipos_persona", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
		<script>
        mostrarMensajes("extio", "El regsitro se Ingreso con Exito");
        setTimeout("window.location.href='principal.php?modulo=19&accion=1037'",5000);
        </script>
        <?
			}
			
		}
	}
	if ($_GET["accion"] == 1054 and in_array(1054, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipos_persona set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idtipos_persona = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipos de Personas ('.$descripcion.')',$login,$fh,$pc,'tipos_persona',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=19&accion=1037'",5000);
        </script>
        <?
	}
	if ($_GET["accion"] == 1055 and in_array(1055, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipos_persona where idtipos_persona = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipos_persona where idtipos_persona = '$codigo'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipos de Personas (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=19&accion=1037'",5000);
        </script>
        <?

			}else{
				
				registra_transaccion('Eliminar Tipos de Personas ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Elimino con Exito");
        setTimeout("window.location.href='principal.php?modulo=19&accion=1037'",5000);
        </script>
        <?
			}				
	}
	
	}
	
	
	
	
	
	
}
?>