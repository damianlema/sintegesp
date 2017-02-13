<?php
if($_POST["ingresar"]){
$_GET["accion"] = 309;
}
if($_POST["ingresar2"]){
$_GET["accion"] = 122;
}
if($_POST["ingresar3"]){
$_GET["accion"] = 469;
}
if($_POST["ingresar4"]){
$_GET["accion"] = 536;
}
if($_POST["ingresar5"]){
$_GET["accion"] = 634;
}
if($_POST["ingresar6"]){
$_GET["accion"] = 704;
}
if($_POST["ingresar7"]){
$_GET["accion"] = 825;
}
if($_POST["ingresar8"]){
$_GET["accion"] = 915;
}

if($_POST["ingresar9"]){
$_GET["accion"] = 983;
}

if($_POST["ingresar10"]){
$_GET["accion"] = 1059;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from tipo_empresa 
												where status='a' order by codigo"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from tipo_empresa where status='a'";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			$registros_grilla=mysql_query($sql." and descripcion like '$texto_buscar%' order by codigo",$conexion_db);
			
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}

if ($_GET["c"]){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from tipo_empresa 
										where idtipo_empresa like '".$_GET['c']."'"
											,$conexion_db);
	$regtipo_empresa=mysql_fetch_assoc($sql);
	
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		
		if (document.frmtipo_empresa.descripcion.value.length==0){
			moistrarMensajes("error", "Disculpe debe escribir una descripcion para Tipo de Presupuesto");
			document.frmtipo_empresa.descripcion.focus();
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

</head>
	<body>
	<br>
	<h4 align=center>Tipos de Empresas</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<form id="frmtipo_empresa" action="" method="POST" enctype="multipart/form-data">	
	<input type="hidden" id="id" name="id" maxlength="9" size="9" <?php echo 'value="'.$regtipo_empresa['idtipo_empresa'].'"';?>>
		<table align=center cellpadding=2 cellspacing=0 width="40%">
<tr>
  <td align='right' class='viewPropTitle'>Codigo</td>
  <td class=''><label>
    <input type="text" name="codigo" id="codigo" value="<?=$regtipo_empresa['codigo']?>">
  </label></td>
</tr>
<tr>
				<td align='right' class='viewPropTitle'>Descripcion:</td>
				<td class=''><input type="text" name="descripcion" maxlength="255" size="45" id="descripcion" <?php echo 'value="'.$regtipo_empresa['descripcion'].'"';?> onKeyUp="validarVacios('descripcion', this.value, 'frmtipo_empresa')" onBlur="validarVacios('descripcion', this.value, 'frmtipo_empresa')" autocomplete="OFF" style="padding:0px 20px 0px 0px;">
                <?
			    if($_SESSION["modulo"] == 4){
				?>
                &nbsp;<a href='principal.php?modulo=4&accion=308' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Empresa"></a>
                <?
                }
				if($_SESSION["modulo"] == 3){
				?>
                &nbsp;<a href='principal.php?modulo=3&accion=74' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Empresa"></a>
                <?
                }
				if($_SESSION["modulo"] == 1){
				?>
                &nbsp;<a href='principal.php?modulo=1&accion=468' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Empresa"></a>
                <?
                }
				if($_SESSION["modulo"] == 2){
				?>
                &nbsp;<a href='principal.php?modulo=2&accion=535' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Empresa"></a>
                <?
                }
				if($_SESSION["modulo"] == 12){
				?>
                &nbsp;<a href='principal.php?modulo=12&accion=633' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Empresa"></a>
                <?
                }
				
				if($_SESSION["modulo"] == 13){
				?>
                &nbsp;<a href='principal.php?modulo=13&accion=703' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Empresa"></a>
                <?
                }
				
				if($_SESSION["modulo"] == 14){
				?>
                &nbsp;<a href='principal.php?modulo=14&accion=824' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Empresa"></a>
                <?
                }
				if($_SESSION["modulo"] == 16){
				?>
                &nbsp;<a href='principal.php?modulo=16&accion=914' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Empresa"></a>
                <?
                }
				if($_SESSION["modulo"] == 17){
				?>
                &nbsp;<a href='principal.php?modulo=17&accion=983' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Empresa"></a>
                <?
                }
				if($_SESSION["modulo"] == 19){
				?>
                &nbsp;<a href='principal.php?modulo=19&accion=1039' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Empresa"></a>
                <?
                }
				?>
                
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=tipoempresa';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none';">X</a></div>          
          <iframe name="pdf" id="pdf" style="display:block" height="500" width="500"></iframe>          
          </div>                </td>
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
					if($_GET["accion"] != 310 and $_GET["accion"] != 311 and in_array(309, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 310 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 311 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				if($_SESSION["modulo"] == 3){
					if($_GET["accion"] != 123 and $_GET["accion"] != 124 and in_array(122, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar2' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 123 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 124 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				if($_SESSION["modulo"] == 1){
					if($_GET["accion"] != 470 and $_GET["accion"] != 471 and in_array(469, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar3' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 470 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 471 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				
				if($_SESSION["modulo"] == 2){
					if($_GET["accion"] != 537 and $_GET["accion"] != 538 and in_array(536, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar4' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 537 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 538 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				
				if($_SESSION["modulo"] == 12){
					if($_GET["accion"] != 635 and $_GET["accion"] != 636 and in_array(634, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar5' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 635 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 636 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				
				
				
				
				if($_SESSION["modulo"] == 13){
					if($_GET["accion"] != 705 and $_GET["accion"] != 706 and in_array(704, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar6' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 705 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 706 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				
				
				if($_SESSION["modulo"] == 14){
					if($_GET["accion"] != 827 and $_GET["accion"] != 826 and in_array(825, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar7' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 826 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 827 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				
				if($_SESSION["modulo"] == 16){
					if($_GET["accion"] != 917 and $_GET["accion"] != 916 and in_array(915, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar8' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 916 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 917 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				
				
				if($_SESSION["modulo"] == 17){
					if($_GET["accion"] != 1003 and $_GET["accion"] != 1002 and in_array(1001, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar8' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 1002 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 1001 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				
				if($_SESSION["modulo"] == 19){
					if($_GET["accion"] != 1060 and $_GET["accion"] != 1061 and in_array(1059, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar10' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 1060 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 1061 and in_array($_GET["accion"], $privilegios) == true){
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
									<td align="center" class="Browse">C&oacute;digo</td>
                                    <td align="center" class="Browse">Descripci&oacute;n</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
									{ ?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
									echo "<td align='center' class='Browse'>&nbsp;".$llenar_grilla["codigo"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["descripcion"]."</td>";
									$c=$llenar_grilla["idtipo_empresa"];
									
								if($_SESSION["modulo"] == 4){
									if(in_array(310,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=310&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(311,$privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=311&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								if($_SESSION["modulo"] == 3){
									if(in_array(123,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=123&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(124,$privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=124&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								if($_SESSION["modulo"] == 1){
									if(in_array(470,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=1&accion=470&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(471,$privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=1&accion=471&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								
								if($_SESSION["modulo"] == 2){
									if(in_array(537,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=537&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(538,$privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=538&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								if($_SESSION["modulo"] == 12){
									if(in_array(635,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=635&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(636,$privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=636&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								if($_SESSION["modulo"] == 13){
									if(in_array(705,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=13&accion=705&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(706,$privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=13&accion=706&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								if($_SESSION["modulo"] == 14){
									if(in_array(826,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=826&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(827,$privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=827&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								
								if($_SESSION["modulo"] == 16){
									if(in_array(916,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=916&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(917,$privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=917&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								if($_SESSION["modulo"] == 17){
									if(in_array(1002,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=916&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(1003,$privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=917&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}	
									
								if($_SESSION["modulo"] == 19){
									if(in_array(1060,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1060&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(1061,$privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1061&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
             <script>document.getElementById('descripcion').focus()</script>
		</div>
<script> document.frmtipo_empresa.descripcion.focus() </script>
</body>
</html>

<?php
if($_POST){
	$id=$_POST["id"];
	$codigo = $_POST["codigo"];
	$descripcion=strtoupper($_POST["descripcion"]);
	$busca_existe_registro=mysql_query("select * from tipo_empresa where descripcion = '".$_POST['descripcion']."'  and status='a'",$conexion_db);
	
	
	if($_SESSION["modulo"] == 4){
	
	if($_GET["accion"] == 309 and in_array(309, $privilegios) == true){
	
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, vuelvalo a intentar");
        setTimeout("window.location.href='principal.php?modulo=4&accion=308'");
        </script>
        <?
	}else{
		
			mysql_query("insert into tipo_empresa
									(descripcion,usuario,fechayhora,status, codigo) 
							values ('$descripcion','$login','$fh','a', '".$codigo."')"
									,$conexion_db);					
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_empresa", "lib/consultar_tablas_select.php", "tipo_empresa", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
		
		?>
		<script>
        mostrarMensajes("exito", "El registro se Ingreso con Exito");
        setTimeout("window.location.href='principal.php?modulo=4&accion=308'");
        </script>
        <?

			}						

		}
	}
	if ($_GET["accion"] == 310 and in_array(310,$privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_empresa set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										codigo = '".$codigo."'
										where 	idtipo_empresa = '$id' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			?>
		<script>
        mostrarMensajes("exito", "El registro se modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=4&accion=308'");
        </script>
        <?
	}
	if ($_GET["accion"] == 311 and in_array(311,$privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_empresa where idtipo_empresa = '$id'");
			$BUS = mysql_fetch_array($sql);
			$sql_elimianr = mysql_query("delete from tipo_empresa where idtipo_empresa = '$id'",$conexion_db);	
			if(!$sql_elimianr){
				registra_transaccion('Eliminar Tipo de Empresa (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=4&accion=308'");
        </script>
        <?
			}else{
				registra_transaccion('Eliminar Tipo de Empresa ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				
				?>
		<script>
        mostrarMensajes("error", "El regsitro se Elimino con Exito");
        setTimeout("window.location.href='principal.php?modulo=4&accion=308'");
        </script>
        <?
			}
			
	}
	}
	
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 3){
	
	if($_GET["accion"] == 122 and in_array(122, $privilegios) == true){
	
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, vuelvalo a intentar");
        setTimeout("window.location.href='principal.php?modulo=3&accion=74'");
        </script>
        <?
	}else{
		
			mysql_query("insert into tipo_empresa
									(descripcion,usuario,fechayhora,status, codigo) 
							values ('$descripcion','$login','$fh','a', '".$codigo."')"
									,$conexion_db);					
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_empresa", "lib/consultar_tablas_select.php", "tipo_empresa", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
			?>
		<script>
        mostrarMensajes("exito", "El registro se Ingreso con Exito");
        setTimeout("window.location.href='principal.php?modulo=3&accion=74'");
        </script>
        <?

			}						


		}
	}
	if ($_GET["accion"] == 123 and in_array(123,$privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_empresa set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										codigo = '".$codigo."'
										where 	idtipo_empresa = '$id' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			
			?>
		<script>
        mostrarMensajes("exito", "El registro se modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=3&accion=74'");
        </script>
        <?
	}
	if ($_GET["accion"] == 124 and in_array(124,$privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_empresa where idtipo_empresa = '$id'");
			$BUS = mysql_fetch_array($sql);
			$sql_elimianr = mysql_query("delete from tipo_empresa where idtipo_empresa = '$id'",$conexion_db);	
			if(!$sql_elimianr){
				registra_transaccion('Eliminar Tipo de Empresa (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=3&accion=74'");
        </script>
        <?
			}else{
				registra_transaccion('Eliminar Tipo de Empresa ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Elimino con Exito");
        setTimeout("window.location.href='principal.php?modulo=3&accion=74'");
        </script>
        <?
			}
			
	}
	}
	
	
	
	
	
	
	
	
		if($_SESSION["modulo"] == 1){
	
	if($_GET["accion"] == 469 and in_array(469, $privilegios) == true){
	
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, vuelvalo a intentar");
        setTimeout("window.location.href='principal.php?modulo=1&accion=468'");
        </script>
        <?
	}else{
		
			mysql_query("insert into tipo_empresa
									(descripcion,usuario,fechayhora,status, codigo) 
							values ('$descripcion','$login','$fh','a', '".$codigo."')"
									,$conexion_db);					
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_empresa", "lib/consultar_tablas_select.php", "tipo_empresa", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
		<script>
        mostrarMensajes("exito", "El registro se Ingreso con Exito");
        setTimeout("window.location.href='principal.php?modulo=1&accion=468'");
        </script>
        <?
			}						

		}
	}
	if ($_GET["accion"] == 470 and in_array(470,$privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_empresa set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										codigo = '".$codigo."'
										where 	idtipo_empresa = '$id' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			?>
		<script>
        mostrarMensajes("exito", "El registro se modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=1&accion=468'");
        </script>
        <?
	}
	
	
	if ($_GET["accion"] == 471 and in_array(471,$privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_empresa where idtipo_empresa = '$id'");
			$BUS = mysql_fetch_array($sql);
			$sql_elimianr = mysql_query("delete from tipo_empresa where idtipo_empresa = '$id'",$conexion_db);	
			if(!$sql_elimianr){
				registra_transaccion('Eliminar Tipo de Empresa (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=1&accion=468'");
        </script>
        <?
			}else{
				registra_transaccion('Eliminar Tipo de Empresa ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "El regsitro se Elimino con Exito");
        setTimeout("window.location.href='principal.php?modulo=1&accion=468'");
        </script>
        <?

			}
		redirecciona("principal.php?modulo=1&accion=468");
			
	}
	}
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 2){
	
	if($_GET["accion"] == 536 and in_array(536, $privilegios) == true){
	
	if (mysql_num_rows($busca_existe_registro)>0){
		
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, vuelvalo a intentar");
        setTimeout("window.location.href='principal.php?modulo=2&accion=535'");
        </script>
        <?
		
	}else{
		
			mysql_query("insert into tipo_empresa
									(descripcion,usuario,fechayhora,status, codigo) 
							values ('$descripcion','$login','$fh','a', '".$codigo."')"
									,$conexion_db);					
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_empresa", "lib/consultar_tablas_select.php", "tipo_empresa", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
				<script>
                mostrarMensajes("exito", "El registro se Ingreso con Exito");
                setTimeout("window.location.href='principal.php?modulo=2&accion=535'");
                </script>
        <?
			}						


		}
	}
	if ($_GET["accion"] == 537 and in_array(537,$privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_empresa set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										codigo = '".$codigo."'
										where 	idtipo_empresa = '$id' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			
			?>
		<script>
        mostrarMensajes("exito", "El registro se modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=2&accion=535'");
        </script>
        <?
	}
	
	
	if ($_GET["accion"] == 538 and in_array(538,$privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_empresa where idtipo_empresa = '$id'");
			$BUS = mysql_fetch_array($sql);
			$sql_elimianr = mysql_query("delete from tipo_empresa where idtipo_empresa = '$id'",$conexion_db);	
			if(!$sql_elimianr){
				registra_transaccion('Eliminar Tipo de Empresa (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=2&accion=535'");
        </script>
        <?
			}else{
				registra_transaccion('Eliminar Tipo de Empresa ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "El regsitro se Elimino con Exito");
        setTimeout("window.location.href='principal.php?modulo=2&accion=535'");
        </script>
        <?
			}
			
	}
	}
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 12){
	
	if($_GET["accion"] == 634 and in_array(634, $privilegios) == true){
	
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, vuelvalo a intentar");
        setTimeout("window.location.href='principal.php?modulo=12&accion=633'");
        </script>
        <?
	}else{
		
			mysql_query("insert into tipo_empresa
									(descripcion,usuario,fechayhora,status, codigo) 
							values ('$descripcion','$login','$fh','a', '".$codigo."')"
									,$conexion_db);					
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_empresa", "lib/consultar_tablas_select.php", "tipo_empresa", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
		<script>
        mostrarMensajes("exito", "El registro se Ingreso con Exito");
        setTimeout("window.location.href='principal.php?modulo=12&accion=633'");
        </script>
        <?
			}						

		}
	}
	if ($_GET["accion"] == 635 and in_array(635,$privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_empresa set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										codigo = '".$codigo."'
										where 	idtipo_empresa = '$id' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			?>
		<script>
        mostrarMensajes("exito", "El registro se modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=12&accion=633'");
        </script>
        <?
	}
	
	
	if ($_GET["accion"] == 636 and in_array(636,$privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_empresa where idtipo_empresa = '$id'");
			$BUS = mysql_fetch_array($sql);
			$sql_elimianr = mysql_query("delete from tipo_empresa where idtipo_empresa = '$id'",$conexion_db);	
			if(!$sql_elimianr){
				registra_transaccion('Eliminar Tipo de Empresa (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=12&accion=633'");
        </script>
        <?
			}else{
				registra_transaccion('Eliminar Tipo de Empresa ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Elimino con Exito");
        setTimeout("window.location.href='principal.php?modulo=12&accion=633'");
        </script>
        <?
			}
			
	}
	}
	
	
	
	
	
	if($_SESSION["modulo"] == 13){
	
	if($_GET["accion"] == 704 and in_array(704, $privilegios) == true){
	
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
        mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, vuelvalo a intentar");
        setTimeout("window.location.href='principal.php?modulo=13&accion=703'");
        </script>
        <?
	}else{
		
			mysql_query("insert into tipo_empresa
									(descripcion,usuario,fechayhora,status, codigo) 
							values ('$descripcion','$login','$fh','a', '".$codigo."')"
									,$conexion_db);					
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_empresa", "lib/consultar_tablas_select.php", "tipo_empresa", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
				<script>
                mostrarMensajes("exito", "El registro se Ingreso con Exito");
                setTimeout("window.location.href='principal.php?modulo=13&accion=703'");
                </script>
                <?
			}						

		}
	}
	if ($_GET["accion"] == 705 and in_array(705,$privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_empresa set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										codigo = '".$codigo."'
										where 	idtipo_empresa = '$id' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			?>
		<script>
        mostrarMensajes("exito", "El registro se modifico con Exito");
        setTimeout("window.location.href='principal.php?modulo=13&accion=703'");
        </script>
        <?
		
	}
	
	
	if ($_GET["accion"] == 706 and in_array(706,$privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_empresa where idtipo_empresa = '$id'");
			$BUS = mysql_fetch_array($sql);
			$sql_elimianr = mysql_query("delete from tipo_empresa where idtipo_empresa = '$id'",$conexion_db);	
			if(!$sql_elimianr){
				registra_transaccion('Eliminar Tipo de Empresa (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
        setTimeout("window.location.href='principal.php?modulo=13&accion=703'");
        </script>
        <?
			}else{
				registra_transaccion('Eliminar Tipo de Empresa ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				
				?>
		<script>
        mostrarMensajes("exito", "El regsitro se Elimino con Exito");
        setTimeout("window.location.href='principal.php?modulo=13&accion=703'");
        </script>
        <?
			}
			
	}
	}
	
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 14){
	
	if($_GET["accion"] == 825 and in_array(825, $privilegios) == true){
	
	if (mysql_num_rows($busca_existe_registro)>0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, vuelvalo a intentar");
            setTimeout("window.location.href='principal.php?modulo=14&accion=824'",5000);
            </script>
			<?

	}else{
		
			mysql_query("insert into tipo_empresa
									(descripcion,usuario,fechayhora,status, codigo) 
							values ('$descripcion','$login','$fh','a', '".$codigo."')"
									,$conexion_db);					
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_empresa", "lib/consultar_tablas_select.php", "tipo_empresa", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=14&accion=824'",5000);
            </script>
			<?
			}						
		}
	}
	if ($_GET["accion"] == 826 and in_array(826,$privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_empresa set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										codigo = '".$codigo."'
										where 	idtipo_empresa = '$id' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			
			?>
			<script>
            mostrarMensajes("exito", "El registro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=14&accion=824'",5000);
            </script>
			<?
	}
	
	
	if ($_GET["accion"] == 827 and in_array(827,$privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_empresa where idtipo_empresa = '$id'");
			$BUS = mysql_fetch_array($sql);
			$sql_elimianr = mysql_query("delete from tipo_empresa where idtipo_empresa = '$id'",$conexion_db);	
			if(!$sql_elimianr){
				registra_transaccion('Eliminar Tipo de Empresa (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=14&accion=824'",5000);
            </script>
			<?
				
			}else{
				registra_transaccion('Eliminar Tipo de Empresa ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "El regsitro se Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=14&accion=824'",5000);
            </script>
			<?
			}
			
	}
	}











if($_SESSION["modulo"] == 16){
	
	if($_GET["accion"] == 915 and in_array(915, $privilegios) == true){
	
	if (mysql_num_rows($busca_existe_registro)>0){
		
		?>
			<script>
            mostrarMensajes("exito", "Disculpe el Regsitro que ingreso ya existe, vuelvalo a intentar");
            setTimeout("window.location.href='principal.php?modulo=16&accion=914'",5000);
            </script>
			<?

	}else{
		
			mysql_query("insert into tipo_empresa
									(descripcion,usuario,fechayhora,status, codigo) 
							values ('$descripcion','$login','$fh','a', '".$codigo."')"
									,$conexion_db);					
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_empresa", "lib/consultar_tablas_select.php", "tipo_empresa", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
				<script>
                mostrarMensajes("error", "El registro se Ingreso con Exito");
                setTimeout("window.location.href='principal.php?modulo=16&accion=914'",5000);
                </script>
                <?

			}						

		}
	}
	if ($_GET["accion"] == 916 and in_array(916,$privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_empresa set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										codigo = '".$codigo."'
										where 	idtipo_empresa = '$id' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			?>
			<script>
            mostrarMensajes("exito", "El registro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=16&accion=914'",5000);
            </script>
			<?
	}
	
	
	if ($_GET["accion"] == 917 and in_array(917,$privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_empresa where idtipo_empresa = '$id'");
			$bus = mysql_fetch_array($sql);
			$sql_elimianr = mysql_query("delete from tipo_empresa where idtipo_empresa = '$id'",$conexion_db);	
			if(!$sql_elimianr){
				registra_transaccion('Eliminar Tipo de Empresa (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
				<script>
                mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
                setTimeout("window.location.href='principal.php?modulo=16&accion=914'",5000);
                </script>
                <?
			}else{
				registra_transaccion('Eliminar Tipo de Empresa ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
				<script>
                mostrarMensajes("exito", "El regsitro se Elimino con Exito");
                setTimeout("window.location.href='principal.php?modulo=16&accion=914'",5000);
                </script>
                <?
			}
			
	}
	}


if($_SESSION["modulo"] == 17){
	
	if($_GET["accion"] == 1001 and in_array(1001, $privilegios) == true){
	
	if (mysql_num_rows($busca_existe_registro)>0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, vuelvalo a intentar");
            setTimeout("window.location.href='principal.php?modulo=16&accion=914'",5000);
            </script>
			<?
	}else{
		
			mysql_query("insert into tipo_empresa
									(descripcion,usuario,fechayhora,status, codigo) 
							values ('$descripcion','$login','$fh','a', '".$codigo."')"
									,$conexion_db);					
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_empresa", "lib/consultar_tablas_select.php", "tipo_empresa", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
				<script>
                mostrarMensajes("exito", "El registro se Ingreso con Exito");
                setTimeout("window.location.href='principal.php?modulo=16&accion=914'",5000);
                </script>
                <?
			}						

		}
	}
	if ($_GET["accion"] == 1002 and in_array(1002,$privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_empresa set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										codigo = '".$codigo."'
										where 	idtipo_empresa = '$id' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			?>
			<script>
            mostrarMensajes("exito", "El registro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=16&accion=914'",5000);
            </script>
			<?
			}
	
	
	if ($_GET["accion"] == 1003 and in_array(1003,$privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_empresa where idtipo_empresa = '$id'");
			$bus = mysql_fetch_array($sql);
			$sql_elimianr = mysql_query("delete from tipo_empresa where idtipo_empresa = '$id'",$conexion_db);	
			if(!$sql_elimianr){
				registra_transaccion('Eliminar Tipo de Empresa (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=16&accion=914'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Tipo de Empresa ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El regsitro se Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=16&accion=914'",5000);
            </script>
			<?
			}
			
	}
	}


if($_SESSION["modulo"] == 19){
	
	if($_GET["accion"] == 1059 and in_array(1059, $privilegios) == true){
	
	if (mysql_num_rows($busca_existe_registro)>0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, vuelvalo a intentar");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1039'",5000);
            </script>
			<?
	}else{
		
			mysql_query("insert into tipo_empresa
									(descripcion,usuario,fechayhora,status, codigo) 
							values ('$descripcion','$login','$fh','a', '".$codigo."')"
									,$conexion_db);					
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_empresa", "lib/consultar_tablas_select.php", "tipo_empresa", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
				<script>
                mostrarMensajes("exito", "El registro se Ingreso con Exito");
                setTimeout("window.location.href='principal.php?modulo=19&accion=1039'",5000);
                </script>
                <?
			}						

		}
	}
	if ($_GET["accion"] == 1060 and in_array(1060,$privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_empresa set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										codigo = '".$codigo."'
										where 	idtipo_empresa = '$id' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Empresa ('.$descripcion.')',$login,$fh,$pc,'tipo_empresa',$conexion_db);
			?>
			<script>
            mostrarMensajes("exito", "El registro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1039'",5000);
            </script>
			<?
			}
	
	
	if ($_GET["accion"] == 1061 and in_array(1061,$privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_empresa where idtipo_empresa = '$id'");
			$bus = mysql_fetch_array($sql);
			$sql_elimianr = mysql_query("delete from tipo_empresa where idtipo_empresa = '$id'",$conexion_db);	
			if(!$sql_elimianr){
				registra_transaccion('Eliminar Tipo de Empresa (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1039'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Tipo de Empresa ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El regsitro se Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1039'",5000);
            </script>
			<?
			}
			
	}
	}



}
?>