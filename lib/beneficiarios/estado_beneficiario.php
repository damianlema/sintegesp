<?php
if($_POST["ingresar"]){
$_GET["accion"] = 297;
}
if($_POST["ingresar2"]){
$_GET["accion"] = 96;
}
if($_POST["ingresar3"]){
$_GET["accion"] = 457;
}
if($_POST["ingresar4"]){
$_GET["accion"] = 524;
}
if($_POST["ingresar5"]){
$_GET["accion"] = 622;
}
if($_POST["ingresar6"]){
$_GET["accion"] = 692;
}
if($_POST["ingresar7"]){
$_GET["accion"] = 813;
}
if($_POST["ingresar8"]){
$_GET["accion"] = 903;
}
if($_POST["ingresar9"]){
$_GET["accion"] = 1050;
}
if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from estado_beneficiario 
												where status='a' order by descripcion"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from estado_beneficiario where status='a'";
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
	$sql=mysql_query("select * from estado_beneficiario 
										where idestado_beneficiario like '".$_GET['c']."'"
											,$conexion_db);
	$regestado_beneficiario=mysql_fetch_assoc($sql);
	
}

?>


<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		
		if (document.frmestado_beneficiario.descripcion.value.length==0){
			mostrarMensajes("error", "Debe escribir una Descripcion para el Tipo de Presupuesto");
			document.frmestado_beneficiario.descripcion.focus()
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
	<h4 align=center>Estado de Beneficiario</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<form id="frmestado_beneficiario" action="" method="POST" enctype="multipart/form-data">	
	<input type="hidden" id="codigo" name="codigo" maxlength="9" size="9" <?php echo 'value="'.$regestado_beneficiario['idestado_beneficiario'].'"';?>>
		<table align=center cellpadding=2 cellspacing=0 width="32%">
<tr>
				<td align='right' class='viewPropTitle'>Descripcion:</td>
				<td class=''><input type="text" name="descripcion" maxlength="255" size="45" id="descripcion" <?php echo 'value="'.$regestado_beneficiario['descripcion'].'"';?>  onKeyUp="validarVacios('descripcion', this.value, 'frmestado_beneficiario')" onBlur="validarVacios('descripcion', this.value, 'frmestado_beneficiario')" autocomplete="OFF" style="padding:0px 20px 0px 0px;">
                
                <?
                if($_SESSION["modulo"] == 4){
				?>
                &nbsp;<a href='principal.php?modulo=4&accion=296' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Estado de Beneficiario"></a>
                <?
                }
				if($_SESSION["modulo"] == 3){
				?>
                &nbsp;<a href='principal.php?modulo=3&accion=71' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Estado de Beneficiario"></a>
                <?
                }
				if($_SESSION["modulo"] == 1){
				?>
                &nbsp;<a href='principal.php?modulo=1&accion=456' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Estado de Beneficiario"></a>
                <?
                }
				if($_SESSION["modulo"] == 2){
				?>
                &nbsp;<a href='principal.php?modulo=2&accion=523' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Estado de Beneficiario"></a>
                <?
                }
				if($_SESSION["modulo"] == 12){
				?>
                &nbsp;<a href='principal.php?modulo=12&accion=621' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Estado de Beneficiario"></a>
                <?
                }
				
				if($_SESSION["modulo"] == 13){
				?>
                &nbsp;<a href='principal.php?modulo=13&accion=691' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Estado de Beneficiario"></a>
                <?
                }
				
				if($_SESSION["modulo"] == 14){
				?>
                &nbsp;<a href='principal.php?modulo=14&accion=812' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Estado de Beneficiario"></a>
                <?
                }
				
				if($_SESSION["modulo"] == 16){
				?>
                &nbsp;<a href='principal.php?modulo=16&accion=902' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Estado de Beneficiario"></a>
                <?
                }
				if($_SESSION["modulo"] == 19){
				?>
                &nbsp;<a href='principal.php?modulo=19&accion=1036' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Estado de Beneficiario"></a>
                <?
                }
				?>
                
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=edobene';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
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

				if($_SESSION["modulo"] == 4){	
					if($_GET["accion"] != 298 and $_GET["accion"] != 299 and in_array(297, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar' id="ingresar" type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 298 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 299 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				if($_SESSION["modulo"] == 3){	
					if($_GET["accion"] != 97 and $_GET["accion"] != 98 and in_array(96, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar2' id="ingresar" type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 97 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 98 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				if($_SESSION["modulo"] == 1){	
					if($_GET["accion"] != 458 and $_GET["accion"] != 459 and in_array(457, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar3' id="ingresar" type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 458 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 459 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				if($_SESSION["modulo"] == 2){	
					if($_GET["accion"] != 525 and $_GET["accion"] != 526 and in_array(524, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar4' id="ingresar" type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 525 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 526 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				if($_SESSION["modulo"] == 12){	
					if($_GET["accion"] != 623 and $_GET["accion"] != 624 and in_array(622, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar5' id="ingresar" type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 623 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 624 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				if($_SESSION["modulo"] == 13){	
					if($_GET["accion"] != 693 and $_GET["accion"] != 694 and in_array(692, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar6' id="ingresar" type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 693 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 694 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				if($_SESSION["modulo"] == 14){	
					if($_GET["accion"] != 814 and $_GET["accion"] != 815 and in_array(813, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar7' id="ingresar" type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 814 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 815 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				
				
				if($_SESSION["modulo"] == 16){	
					if($_GET["accion"] != 904 and $_GET["accion"] != 905 and in_array(903, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar8' id="ingresar" type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 904 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 905 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				if($_SESSION["modulo"] == 19){	
					if($_GET["accion"] != 1051 and $_GET["accion"] != 1052 and in_array(1050, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar9' id="ingresar" type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 1051 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
						<?
					}
				
					if($_GET["accion"] == 1052 and in_array($_GET["accion"], $privilegios) == true){
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
						<form name="grilla" action="estado_beneficiario.php" method="POST">
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
									$c=$llenar_grilla["idestado_beneficiario"];
									
								if($_SESSION["modulo"] == 4){
									if(in_array(298, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=298&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(299, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=299&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								
								if($_SESSION["modulo"] == 3){
									if(in_array(97, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=97&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(98, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=98&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								if($_SESSION["modulo"] == 1){
									if(in_array(458, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=1&accion=458&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(459, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=1&accion=459&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								
								if($_SESSION["modulo"] == 2){
									if(in_array(525, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=525&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(526, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=526&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								
								if($_SESSION["modulo"] == 12){
									if(in_array(623, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=623&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(624, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=624&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								
								if($_SESSION["modulo"] == 13){
									if(in_array(693, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=13&accion=693&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(694, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=13&accion=694&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								if($_SESSION["modulo"] == 14){
									if(in_array(814, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=814&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(815, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=815&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								
								
								
								if($_SESSION["modulo"] == 16){
									if(in_array(904, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=904&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(905, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=905&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
								
								if($_SESSION["modulo"] == 19){
									if(in_array(1051, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1051&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(1052, $privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1052&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
								}
									
									echo "</tr>";
									}
							}
							?>
						</table>
                        
						</form>
					</td>
				</tr>
			</table>
            

		</div>
            <script>document.getElementById('descripcion').focus()</script>
</body>
</html>

<script src="../../js/actualiza_select.js" type="text/javascript" language="javascript"></script>
<?php
	$codigo=$_POST["codigo"];
	$descripcion=strtoupper($_POST["descripcion"]);
	$busca_existe_registro=mysql_query("select * from estado_beneficiario where descripcion = '".$_POST['descripcion']."'  and status='a'",$conexion_db);
	
if($_POST){
	if($_SESSION["modulo"] == 4){
		if($_GET["accion"] == 297 and in_array(297,$privilegios) == true){
		if (mysql_num_rows($busca_existe_registro)>0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe este registro ya existe");
            setTimeout("window.location.href='principal.php?modulo=4&accion=296'",5000);
            </script>
			<?

		}else{	
			mysql_query("insert into estado_beneficiario
									(descripcion,usuario,fechayhora,status, bloquea) 
							values ('$descripcion','$login','$fh','a', 'n')"
									,$conexion_db);
			// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
			$idcreado = mysql_insert_id();
			registra_transaccion('ingresar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idestado_beneficiario", "lib/consultar_tablas_select.php", "estado_beneficiario", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
			
			?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=4&accion=296'",5000);
            </script>
			<?
			}
		}
	}

		if($_GET["accion"] == 298 and in_array(298, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update estado_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."',
											bloquea = 'n'
											where 	idestado_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El registro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=4&accion=296'",5000);
            </script>
			<?
		}
		if ($_GET["accion"] == 299 and in_array(299, $privilegios) == true and !$_POST["buscar"]){
				$sql = mysql_query("select * from estado_beneficiario where idestado_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from estado_beneficiario where idestado_beneficiario = '$codigo'",$conexion_db);	
					if(!$sql_eliminar){
						registra_transaccion('Eliminar estado de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
						<script>
                        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
                        setTimeout("window.location.href='principal.php?modulo=4&accion=296'",5000);
                        </script>
                        <?
					}else{
						registra_transaccion('Eliminar estado de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
						<script>
                        mostrarMensajes("exito", "El registro se Elimino con exito");
                        setTimeout("window.location.href='principal.php?modulo=4&accion=296'",5000);
                        </script>
                        <?
						}
				
		}
	}
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 3){
		if($_GET["accion"] == 96 and in_array(96,$privilegios) == true){
		if (mysql_num_rows($busca_existe_registro)>0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe este registro ya existe");
            setTimeout("window.location.href='principal.php?modulo=3&accion=71'",5000);
            </script>
			<?
		}else{	
			mysql_query("insert into estado_beneficiario
									(descripcion,usuario,fechayhora,status, bloquea) 
							values ('$descripcion','$login','$fh','a', 'n')"
									,$conexion_db);
			// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
			$idcreado = mysql_insert_id();
			registra_transaccion('ingresar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idestado_beneficiario", "lib/consultar_tablas_select.php", "estado_beneficiario", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=3&accion=71'",5000);
            </script>
			<?
			}
		}
	}

		if($_GET["accion"] == 97 and in_array(97, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update estado_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."',
											bloquea = 'n'
											where 	idestado_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El registro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=3&accion=71'",5000);
            </script>
			<?
		}
		
		
		if ($_GET["accion"] == 98 and in_array(98, $privilegios) == true and !$_POST["buscar"]){
				$sql = mysql_query("select * from estado_beneficiario where idestado_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from estado_beneficiario where idestado_beneficiario = '$codigo'",$conexion_db);	
					if(!$sql_eliminar){
						registra_transaccion('Eliminar estado de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=3&accion=71'",5000);
            </script>
			<?
					}else{
						registra_transaccion('Eliminar estado de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
			<script>
            mostrarMensajes("exito", "El registro se Elimino con exito");
            setTimeout("window.location.href='principal.php?modulo=3&accion=71'",5000);
            </script>
			<?
						
					}
				
		}
	}
	
	
	
	
	
	
	
	
	
	
	
		if($_SESSION["modulo"] == 1){
		if($_GET["accion"] == 457 and in_array(457,$privilegios) == true){
		if (mysql_num_rows($busca_existe_registro)>0){
			
			?>
			<script>
            mostrarMensajes("error", "Disculpe este registro ya existe");
            setTimeout("window.location.href='principal.php?modulo=1&accion=456'",5000);
            </script>
			<?
		
		}else{	
			mysql_query("insert into estado_beneficiario
									(descripcion,usuario,fechayhora,status, bloquea) 
							values ('$descripcion','$login','$fh','a', 'n')"
									,$conexion_db);
			// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
			$idcreado = mysql_insert_id();
			registra_transaccion('ingresar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idestado_beneficiario", "lib/consultar_tablas_select.php", "estado_beneficiario", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=1&accion=456'",5000);
            </script>
			<?

			}

		}
	}

		if($_GET["accion"] == 458 and in_array(458, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update estado_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."',
											bloquea = 'n'
											where 	idestado_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
					?>
			<script>
            mostrarMensajes("exito", "El registro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=1&accion=456'",5000);
            </script>
			<?
		}
		
		
		if ($_GET["accion"] == 459 and in_array(459, $privilegios) == true and !$_POST["buscar"]){
				$sql = mysql_query("select * from estado_beneficiario where idestado_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from estado_beneficiario where idestado_beneficiario = '$codigo'",$conexion_db);	
					if(!$sql_eliminar){
						registra_transaccion('Eliminar estado de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
							?>
						<script>
                        mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
                        setTimeout("window.location.href='principal.php?modulo=1&accion=456'",5000);
                        </script>
                        <?
						
					}else{
						registra_transaccion('Eliminar estado de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("");
						
							?>
			<script>
            mostrarMensajes("exito", "El registro se Elimino con exito");
            setTimeout("window.location.href='principal.php?modulo=1&accion=456'",5000);
            </script>
			<?
					}
		}
	}
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 2){
		if($_GET["accion"] == 524 and in_array(524,$privilegios) == true){
		if (mysql_num_rows($busca_existe_registro)>0){
				?>
			<script>
            mostrarMensajes("error", "Disculpe este registro ya existe");
            setTimeout("window.location.href='principal.php?modulo=2&accion=523'",5000);
            </script>
			<?
		}else{	
			mysql_query("insert into estado_beneficiario
									(descripcion,usuario,fechayhora,status, bloquea) 
							values ('$descripcion','$login','$fh','a', 'n')"
									,$conexion_db);
			// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
			$idcreado = mysql_insert_id();
			registra_transaccion('ingresar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idestado_beneficiario", "lib/consultar_tablas_select.php", "estado_beneficiario", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=2&accion=523'",5000);
            </script>
			<?
			}
		}
	}

		if($_GET["accion"] == 525 and in_array(525, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update estado_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."',
											bloquea = 'n'
											where 	idestado_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El registro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=2&accion=523'",5000);
            </script>
			<?
		}
		
		
		if ($_GET["accion"] == 526 and in_array(526, $privilegios) == true and !$_POST["buscar"]){
				$sql = mysql_query("select * from estado_beneficiario where idestado_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from estado_beneficiario where idestado_beneficiario = '$codigo'",$conexion_db);	
					if(!$sql_eliminar){
						registra_transaccion('Eliminar estado de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=2&accion=523'",5000);
            </script>
			<?	
					}else{
						registra_transaccion('Eliminar estado de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
			<script>
            mostrarMensajes("exito", "El registro se Elimino con exito");
            setTimeout("window.location.href='principal.php?modulo=2&accion=523'",5000);
            </script>
			<?
					}
				redirecciona("principal.php?modulo=2&accion=523");
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 12){
		if($_GET["accion"] == 622 and in_array(622,$privilegios) == true){
		if (mysql_num_rows($busca_existe_registro)>0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe este registro ya existe");
            setTimeout("window.location.href='principal.php?modulo=12&accion=621'",5000);
            </script>
			<?
		}else{	
			mysql_query("insert into estado_beneficiario
									(descripcion,usuario,fechayhora,status, bloquea) 
							values ('$descripcion','$login','$fh','a', 'n')"
									,$conexion_db);
			// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
			$idcreado = mysql_insert_id();
			registra_transaccion('ingresar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idestado_beneficiario", "lib/consultar_tablas_select.php", "estado_beneficiario", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
			?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=12&accion=621'",5000);
            </script>
			<?
			}
		}
	}

		if($_GET["accion"] == 623 and in_array(623, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update estado_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."',
											bloquea = 'n'
											where 	idestado_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El registro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=12&accion=621'",5000);
            </script>
			<?
				
		}
		
		
		if ($_GET["accion"] == 624 and in_array(624, $privilegios) == true and !$_POST["buscar"]){
				$sql = mysql_query("select * from estado_beneficiario where idestado_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from estado_beneficiario where idestado_beneficiario = '$codigo'",$conexion_db);	
					if(!$sql_eliminar){
						registra_transaccion('Eliminar estado de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=12&accion=621'",5000);
            </script>
			<?
						
					}else{
						registra_transaccion('Eliminar estado de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
			<script>
            mostrarMensajes("exito", "El registro se Elimino con exito");
            setTimeout("window.location.href='principal.php?modulo=12&accion=621'",5000);
            </script>
			<?

					}
		}
	}
	
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 13){
		if($_GET["accion"] == 692 and in_array(692,$privilegios) == true){
		if (mysql_num_rows($busca_existe_registro)>0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe este registro ya existe");
            setTimeout("window.location.href='principal.php?modulo=13&accion=691'",5000);
            </script>
			<?
		}else{	
			mysql_query("insert into estado_beneficiario
									(descripcion,usuario,fechayhora,status, bloquea) 
							values ('$descripcion','$login','$fh','a', 'n')"
									,$conexion_db);
			// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
			$idcreado = mysql_insert_id();
			registra_transaccion('ingresar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idestado_beneficiario", "lib/consultar_tablas_select.php", "estado_beneficiario", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=13&accion=691'",5000);
            </script>
			<?

			}

		}
	}

		if($_GET["accion"] == 693 and in_array(693, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update estado_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."',
											bloquea = 'n'
											where 	idestado_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "El registro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=13&accion=691'",5000);
            </script>
			<?
		}
		
		
		if ($_GET["accion"] == 694 and in_array(694, $privilegios) == true and !$_POST["buscar"]){
				$sql = mysql_query("select * from estado_beneficiario where idestado_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from estado_beneficiario where idestado_beneficiario = '$codigo'",$conexion_db);	
					if(!$sql_eliminar){
						registra_transaccion('Eliminar estado de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=13&accion=691'",5000);
            </script>
			<?
					}else{
						registra_transaccion('Eliminar estado de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
			<script>
            mostrarMensajes("exito", "El registro se Elimino con exito");
            setTimeout("window.location.href='principal.php?modulo=13&accion=691'",5000);
            </script>
			<?
					}
		}
	}
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 14){
		if($_GET["accion"] == 813 and in_array(813,$privilegios) == true){
		if (mysql_num_rows($busca_existe_registro)>0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe el Registro ya existe");
            setTimeout("window.location.href='principal.php?modulo=14&accion=812'",5000);
            </script>
			<?
		}else{	
			mysql_query("insert into estado_beneficiario
									(descripcion,usuario,fechayhora,status, bloquea) 
							values ('$descripcion','$login','$fh','a', 'n')"
									,$conexion_db);
			// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
			$idcreado = mysql_insert_id();
			registra_transaccion('ingresar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idestado_beneficiario", "lib/consultar_tablas_select.php", "estado_beneficiario", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=14&accion=812'",5000);
            </script>
			<?

			}

		}
	}

		if($_GET["accion"] == 814 and in_array(814, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update estado_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."',
											bloquea = 'n'
											where 	idestado_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El registro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=14&accion=812'",5000);
            </script>
			<?
		}
		
		
		if ($_GET["accion"] == 815 and in_array(815, $privilegios) == true and !$_POST["buscar"]){
				$sql = mysql_query("select * from estado_beneficiario where idestado_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from estado_beneficiario where idestado_beneficiario = '$codigo'",$conexion_db);	
					if(!$sql_eliminar){
						registra_transaccion('Eliminar estado de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=14&accion=812'",5000);
            </script>
			<?
						
					}else{
						registra_transaccion('Eliminar estado de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
						?>
			<script>
            mostrarMensajes("exito", "El registro se Elimino con exito");
            setTimeout("window.location.href='principal.php?modulo=14&accion=812'",5000);
            </script>
			<?
					}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 16){
		if($_GET["accion"] == 903 and in_array(903,$privilegios) == true){
		if (mysql_num_rows($busca_existe_registro)>0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe este registro ya existe");
            setTimeout("window.location.href='principal.php?modulo=16&accion=902'",5000);
            </script>
			<?
		}else{	
			mysql_query("insert into estado_beneficiario
									(descripcion,usuario,fechayhora,status, bloquea) 
							values ('$descripcion','$login','$fh','a', 'n')"
									,$conexion_db);
			// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
			$idcreado = mysql_insert_id();
			registra_transaccion('ingresar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idestado_beneficiario", "lib/consultar_tablas_select.php", "estado_beneficiario", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exit");
            setTimeout("window.location.href='principal.php?modulo=16&accion=902'",5000);
            </script>
			<?

			}

		}
	}

		if($_GET["accion"] == 904 and in_array(904, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update estado_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."',
											bloquea = 'n'
											where 	idestado_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El registro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=16&accion=902'",5000);
            </script>
			<?
		}
		
		
		if ($_GET["accion"] == 905 and in_array(905, $privilegios) == true and !$_POST["buscar"]){
				$sql = mysql_query("select * from estado_beneficiario where idestado_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from estado_beneficiario where idestado_beneficiario = '$codigo'",$conexion_db);	
					if(!$sql_eliminar){
						registra_transaccion('Eliminar estado de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=16&accion=902'",5000);
            </script>
			<?
						
					}else{
						registra_transaccion('Eliminar estado de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
			?>
			<script>
            mostrarMensajes("exito", "El registro se Elimino con exito");
            setTimeout("window.location.href='principal.php?modulo=16&accion=902'",5000);
            </script>
			<?
						
					}

		}
	}
	
	
	
	if($_SESSION["modulo"] == 19){
		if($_GET["accion"] == 1050 and in_array(1050,$privilegios) == true){
		if (mysql_num_rows($busca_existe_registro)>0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe este registro ya existe");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1036'",5000);
            </script>
			<?
		}else{	
			mysql_query("insert into estado_beneficiario
									(descripcion,usuario,fechayhora,status, bloquea) 
							values ('$descripcion','$login','$fh','a', 'n')"
									,$conexion_db);
			// este idcreado es el que se captura para realizar la seleccion automatica en la actualizacion del select por medio del ajax
			$idcreado = mysql_insert_id();
			registra_transaccion('ingresar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idestado_beneficiario", "lib/consultar_tablas_select.php", "estado_beneficiario", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exit");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1036'",5000);
            </script>
			<?

			}

		}
	}

		if($_GET["accion"] == 1051 and in_array(1051, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update estado_beneficiario set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."',
											bloquea = 'n'
											where 	idestado_beneficiario = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar estado de beneficiario ('.$descripcion.')',$login,$fh,$pc,'estado_beneficiario',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "El registro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1036'",5000);
            </script>
			<?
		}
		
		
		if ($_GET["accion"] == 1052 and in_array(1052, $privilegios) == true and !$_POST["buscar"]){
				$sql = mysql_query("select * from estado_beneficiario where idestado_beneficiario = '$codigo'");
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from estado_beneficiario where idestado_beneficiario = '$codigo'",$conexion_db);	
					if(!$sql_eliminar){
						registra_transaccion('Eliminar estado de Beneficiario (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1036'",5000);
            </script>
			<?
						
					}else{
						registra_transaccion('Eliminar estado de Beneficiario ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						
			?>
			<script>
            mostrarMensajes("exito", "El registro se Elimino con exito");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1036'",5000);
            </script>
			<?
						
					}

		}
	}
	
}

?>