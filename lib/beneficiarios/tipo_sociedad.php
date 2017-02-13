<?php
if($_POST["ingresar"]){
$_GET["accion"] = 305;
}
if($_POST["ingresar2"]){
$_GET["accion"] = 125;
}
if($_POST["ingresar3"]){
$_GET["accion"] = 465;
}
if($_POST["ingresar4"]){
$_GET["accion"] = 532;
}
if($_POST["ingresar5"]){
$_GET["accion"] = 630;
}
if($_POST["ingresar6"]){
$_GET["accion"] = 700;
}

if($_POST["ingresar7"]){
$_GET["accion"] = 821;
}

if($_POST["ingresar8"]){
$_GET["accion"] = 911;
}

if($_POST["ingresar8"]){
$_GET["accion"] = 982;
}

if($_POST["ingresar9"]){
$_GET["accion"] = 1056;
}
//if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from tipo_sociedad 
												where status='a' order by descripcion"
													,$conexion_db);


if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from tipo_sociedad where status='a'";
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
		$sql=mysql_query("select * from tipo_sociedad 
											where idtipo_sociedad like '".$_GET['c']."'"
												,$conexion_db);
		$regtipo_sociedad=mysql_fetch_assoc($sql);
		
	}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		
		if (document.frmtipo_sociedad.descripcion.value.length==0){
			mostrarMensajes("error", "Debe escribir una descripcion para el tipo de Presupuesto");
			document.frmtipo_sociedad.descripcion.focus()
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
	<h4 align=center>Tipos de Sociedad</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<form id="frmtipo_sociedad" action="principal.php?accion=<?=$_GET["accion"]?>&modulo=<?=$_SESSION["modulo"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
  <input type="hidden" id="codigo" name="codigo" maxlength="9" size="9" <?php echo 'value="'.$regtipo_sociedad['idtipo_sociedad'].'"';?>>
		<table align=center cellpadding=2 cellspacing=0 width="39%">
<tr>
				<td align='right' class='viewPropTitle'>Descripcion:</td>
				<td class=''><input type="text" name="descripcion" maxlength="255" size="45" id="descripcion" <?php echo 'value="'.$regtipo_sociedad['descripcion'].'"';?> onKeyUp="validarVacios('descripcion', this.value, 'frmtipo_sociedad')" onBlur="validarVacios('descripcion', this.value, 'frmtipo_sociedad')" autocomplete="OFF" style="padding:0px 20px 0px 0px;">
			    
                <?
                if($_SESSION["modulo"] == 4){
				?>
                &nbsp;<a href='principal.php?modulo=4&accion=304' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Sociedad"></a>
                <?
                }
				if($_SESSION["modulo"] == 3){
				?>
                &nbsp;<a href='principal.php?modulo=3&accion=73' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Sociedad"></a>
                <?
                }
				if($_SESSION["modulo"] == 1){
				?>
                &nbsp;<a href='principal.php?modulo=1&accion=464' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Sociedad"></a>
                <?
                }
				if($_SESSION["modulo"] == 2){
				?>
                &nbsp;<a href='principal.php?modulo=2&accion=531' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Sociedad"></a>
                <?
                }
				if($_SESSION["modulo"] == 12){
				?>
                &nbsp;<a href='principal.php?modulo=12&accion=629' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Sociedad"></a>
                <?
                }
				
				if($_SESSION["modulo"] == 13){
				?>
                &nbsp;<a href='principal.php?modulo=13&accion=699' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Sociedad"></a>
                <?
                }
				
				if($_SESSION["modulo"] == 14){
				?>
                &nbsp;<a href='principal.php?modulo=14&accion=820' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Sociedad"></a>
                <?
                }
				
				
				
				if($_SESSION["modulo"] == 16){
				?>
                &nbsp;<a href='principal.php?modulo=16&accion=910' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Sociedad"></a>
                <?
                }
				
				if($_SESSION["modulo"] == 17){
				?>
                &nbsp;<a href='principal.php?modulo=17&accion=982' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Sociedad"></a>
                <?
                }
				
				if($_SESSION["modulo"] == 19){
				?>
                &nbsp;<a href='principal.php?modulo=19&accion=1038' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Sociedad"></a>
                <?
                }

				?>
                
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='block';">X</a></div>
          <table id="tableImprimir">
          	<tr>
            	<td>Ordenar Por: </td>
                <td>
                	<select name="ordenarPor" id="ordenarPor">
                    	<option value="descripcion">Descripci&oacute;n</option>
                        <option value="siglas">Siglas</option>
                    </select>               
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=tiposociedad&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
                </td>
            </tr>
          </table>
          <iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>          
          </div>
                
                </td>
			</tr>
            <tr>
				<td align='right' class='viewPropTitle'>Siglas:</td>
				<td class=''><input type="text" name="siglas" maxlength="6" size="6" id="siglas" <?php echo 'value="'.$regtipo_sociedad['siglas'].'"';?>></td>
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
					if($_GET["accion"] != 306 and $_GET["accion"] != 307 and in_array(305, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 306 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 307 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				if($_SESSION["modulo"] == 3){	
					if($_GET["accion"] != 126 and $_GET["accion"] != 127 and in_array(125, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar2' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 126 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 127 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				if($_SESSION["modulo"] == 1){	
					if($_GET["accion"] != 466 and $_GET["accion"] != 467 and in_array(465, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar3' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 466 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 467 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				if($_SESSION["modulo"] == 2){	
					if($_GET["accion"] != 533 and $_GET["accion"] != 534 and in_array(532, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar4' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 533 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 534 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				if($_SESSION["modulo"] == 12){	
					if($_GET["accion"] != 631 and $_GET["accion"] != 632 and in_array(630, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar5' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 631 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 632 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				
				if($_SESSION["modulo"] == 13){	
					if($_GET["accion"] != 701 and $_GET["accion"] != 702 and in_array(700, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar6' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 701 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 702 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				
				if($_SESSION["modulo"] == 14){	
					if($_GET["accion"] != 822 and $_GET["accion"] != 823 and in_array(821, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar7' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 822 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 823 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				
				
				
				if($_SESSION["modulo"] == 16){	
					if($_GET["accion"] != 912 and $_GET["accion"] != 913 and in_array(911, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar8' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 912 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 913 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				if($_SESSION["modulo"] == 17){	
					if($_GET["accion"] != 998 and $_GET["accion"] != 999 and in_array(1000, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar9' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 999 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 1000 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
            <?
					}
				}
				
				if($_SESSION["modulo"] == 19){	
					if($_GET["accion"] != 1057 and $_GET["accion"] != 1058 and in_array(1056, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar9' type='submit' value='Ingresar'>
						<?
					}
				
					if($_GET["accion"] == 1057 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 1058 and in_array($_GET["accion"], $privilegios) == true){
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
                                    <td align="center" class="Browse">Siglas</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
									{ ?>
						  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								
									<td align='left' class='Browse'><?=$llenar_grilla["descripcion"]?></td>
									<td align='left' class='Browse'><?=$llenar_grilla["siglas"]?></td>
									<?php
                                    $c=$llenar_grilla["idtipo_sociedad"];
									if($_SESSION["modulo"] == 4){
										if(in_array(306, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=4&accion=306&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                                </a>
                                            </td>
                                            <?
										}
										if(in_array(307, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=4&accion=307&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                                </a>
                                            </td>
                                            <?
										}
									}
									if($_SESSION["modulo"] == 3){
										if(in_array(126, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=3&accion=126&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                                </a>
                                            </td>
                                            <?
										}
										if(in_array(127, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=3&accion=127&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                                </a>
                                            </td>
                                            <?
										}
									}
									if($_SESSION["modulo"] == 1){
										if(in_array(466, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=1&accion=466&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                                </a>
                                            </td>
                                            <?
										}
										if(in_array(467, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=1&accion=467&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                                </a>
                                            </td>
                                            <?	
										}
									}
									
									if($_SESSION["modulo"] == 2){
										if(in_array(533, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=2&accion=533&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                                </a>
                                            </td>
                                            <?
										}
										if(in_array(534, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=2&accion=534&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                                </a>
                                            </td>
                                           	<?
										}
									}
									
									
									
									if($_SESSION["modulo"] == 12){
										if(in_array(631, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=12&accion=631&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                                </a>
                                            </td>
                                            <?
										}
										if(in_array(632, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=12&accion=632&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                                </a>
                                            </td>
                                           	<?
										}
									}
									
									
									if($_SESSION["modulo"] == 13){
										if(in_array(701, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=13&accion=701&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                                </a>
                                            </td>
                                            <?
										}
										if(in_array(702, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=13&accion=702&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                                </a>
                                            </td>
                                           	<?
										}
									}
									
									
									
									if($_SESSION["modulo"] == 14){
										if(in_array(822, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=14&accion=822&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                                </a>
                                            </td>
                                            <?
										}
										if(in_array(823, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=14&accion=823&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                                </a>
                                            </td>
                                           	<?
										}
									}
									
									
									
									
									
									
									
									
									if($_SESSION["modulo"] == 16){
										if(in_array(912, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=16&accion=912&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                                </a>
                                            </td>
                                            <?
										}
										if(in_array(913, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=16&accion=913&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                                </a>
                                            </td>
                                           	<?
										}
									}
									
									
									if($_SESSION["modulo"] == 17){
										if(in_array(999, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=16&accion=912&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                                </a>
                                            </td>
                                            <?
										}
										if(in_array(1000, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=16&accion=913&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'>
                                                </a>
                                            </td>
                                           	<?
										}
									}
									
									if($_SESSION["modulo"] == 19){
										if(in_array(1057, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=19&accion=1057&c=<?=$c?>' class='Browse'>
                                                	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'>
                                                </a>
                                            </td>
                                            <?
										}
										if(in_array(1058, $privilegios) == true){
											?>
                                            <td align='center' class='Browse' width='3%'>
                                            	<a href='principal.php?modulo=19&accion=1058&c=<?=$c?>' class='Browse'>
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
	$siglas = $_POST["siglas"];
	$busca_existe_registro=mysql_query("select * from tipo_sociedad where descripcion = '".$_POST['descripcion']."'  and status='a'",$conexion_db);

	
	if($_SESSION["modulo"] == 4){
	
	if($_GET["accion"] == 305 and in_array(305,$privilegios) == true){	
	
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
			<script>
            mostrarMensajes("error", "Disculpe el Registro que esta ingresando existe, vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=4&accion=304'",5000);
            </script>
			<?
	}else{
		
			mysql_query("insert into tipo_sociedad
									(descripcion,usuario,fechayhora,status, siglas) 
							values ('$descripcion','$login','$fh','a','$siglas')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_sociedad", "lib/consultar_tablas_select.php", "tipo_sociedad", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=4&accion=304'",5000);
            </script>
			<?

			}	


		}
	}
	if ($_GET["accion"] == 306 and in_array(306, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_sociedad set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										siglas = '".$siglas."'
										where 	idtipo_sociedad = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			?>
			<script>
            mostrarMensajes("exito", "El regsitro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=4&accion=304'",5000);
            </script>
			<?
		}
	if ($_GET["accion"] == 307 and in_array(307, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_sociedad where idtipo_sociedad = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipo_sociedad where idtipo_sociedad = '$codigo'",$conexion_db);
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipo de Sociedad (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=4&accion=304'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Tipo de Sociedad ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "El regsitro se Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=4&accion=304'",5000);
            </script>
			<?
			}	
			
	}
	}
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 3){
	
	if($_GET["accion"] == 125 and in_array(125,$privilegios) == true){	
	
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
			<script>
            mostrarMensajes("error", "Disculpe el Registro que esta ingresando existe, vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=3&accion=73'",5000);
            </script>
			<?

	}else{
		
			mysql_query("insert into tipo_sociedad
									(descripcion,usuario,fechayhora,status, siglas) 
							values ('$descripcion','$login','$fh','a','$siglas')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_sociedad", "lib/consultar_tablas_select.php", "tipo_sociedad", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=3&accion=73'",5000);
            </script>
			<?
			}	


		}
	}
	if ($_GET["accion"] == 126 and in_array(126, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_sociedad set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										siglas = '".$siglas."'
										where 	idtipo_sociedad = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			?>
			<script>
            mostrarMensajes("exito", "El regsitro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=3&accion=73'",5000);
            </script>
			<?
	}
	if ($_GET["accion"] == 127 and in_array(127, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_sociedad where idtipo_sociedad = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipo_sociedad where idtipo_sociedad = '$codigo'",$conexion_db);
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipo de Sociedad (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=3&accion=73'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Tipo de Sociedad ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "El regsitro se Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=3&accion=73'",5000);
            </script>
			<?
			}	
	}
	}
	
	
	
	
	
	
	
		if($_SESSION["modulo"] == 1){
	
	if($_GET["accion"] == 465 and in_array(465,$privilegios) == true){	
	
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
			<script>
            mostrarMensajes("error", "Disculpe el Registro que esta ingresando existe, vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=1&accion=464'",5000);
            </script>
			<?
	}else{
		
			mysql_query("insert into tipo_sociedad
									(descripcion,usuario,fechayhora,status, siglas) 
							values ('$descripcion','$login','$fh','a','$siglas')");
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_sociedad", "lib/listas/consultar_tablas_select.php", "tipo_sociedad", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=1&accion=464'",5000);
            </script>
			<?
			}	
		}

	}
	if ($_GET["accion"] == 466 and in_array(466, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_sociedad set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										siglas = '".$siglas."'
										where 	idtipo_sociedad = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			?>
			<script>
            mostrarMensajes("exito", "El regsitro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=1&accion=464'",5000);
            </script>
			<?
	}
	if ($_GET["accion"] == 467 and in_array(467, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_sociedad where idtipo_sociedad = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipo_sociedad where idtipo_sociedad = '$codigo'",$conexion_db);
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipo de Sociedad (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=1&accion=464'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Tipo de Sociedad ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "El regsitro se Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=1&accion=464'",5000);
            </script>
			<?
			}	
	}
	}
	
	
	
	if($_SESSION["modulo"] == 2){
	
	if($_GET["accion"] == 532 and in_array(532,$privilegios) == true){	
	
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
			<script>
            mostrarMensajes("error", "Disculpe el Registro que esta ingresando existe, vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=2&accion=531'",5000);
            </script>
			<?
	}else{
		
			mysql_query("insert into tipo_sociedad
									(descripcion,usuario,fechayhora,status, siglas) 
							values ('$descripcion','$login','$fh','a','$siglas')");
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_sociedad", "lib/listas/consultar_tablas_select.php", "tipo_sociedad", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=2&accion=531'",5000);
            </script>
			<?
			}	
		}
	}
	if ($_GET["accion"] == 533 and in_array(533, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_sociedad set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										siglas = '".$siglas."'
										where 	idtipo_sociedad = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			?>
			<script>
            mostrarMensajes("exito", "El regsitro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=2&accion=531'",5000);
            </script>
			<?
		}
	if ($_GET["accion"] == 534 and in_array(534, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_sociedad where idtipo_sociedad = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipo_sociedad where idtipo_sociedad = '$codigo'",$conexion_db);
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipo de Sociedad (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=2&accion=531'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Tipo de Sociedad ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "El regsitro se Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=2&accion=531'",5000);
            </script>
			<?
			}	
	}
	}
	
	






if($_SESSION["modulo"] == 12){
	
	if($_GET["accion"] == 630 and in_array(630,$privilegios) == true){	
	
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
			<script>
            mostrarMensajes("error", "Disculpe el Registro que esta ingresando existe, vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=12&accion=629'",5000);
            </script>
			<?
	}else{
		
			mysql_query("insert into tipo_sociedad
									(descripcion,usuario,fechayhora,status, siglas) 
							values ('$descripcion','$login','$fh','a','$siglas')");
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_sociedad", "lib/listas/consultar_tablas_select.php", "tipo_sociedad", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=12&accion=629'",5000);
            </script>
			<?
			}	
		}
	}
	if ($_GET["accion"] == 631 and in_array(631, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_sociedad set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										siglas = '".$siglas."'
										where 	idtipo_sociedad = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			?>
			<script>
            mostrarMensajes("exito", "El regsitro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=12&accion=629'",5000);
            </script>
			<?
	}
	if ($_GET["accion"] == 632 and in_array(632, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_sociedad where idtipo_sociedad = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipo_sociedad where idtipo_sociedad = '$codigo'",$conexion_db);
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipo de Sociedad (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=12&accion=629'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Tipo de Sociedad ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "El regsitro se Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=12&accion=629'",5000);
            </script>
			<?
			}	
	}
	}











if($_SESSION["modulo"] == 13){
	
	if($_GET["accion"] == 700 and in_array(700,$privilegios) == true){	
	
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
			<script>
            mostrarMensajes("error", "Disculpe el Registro que esta ingresando existe, vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=13&accion=699'",5000);
            </script>
			<?
	}else{
		
			mysql_query("insert into tipo_sociedad
									(descripcion,usuario,fechayhora,status, siglas) 
							values ('$descripcion','$login','$fh','a','$siglas')");
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_sociedad", "lib/listas/consultar_tablas_select.php", "tipo_sociedad", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=13&accion=699'",5000);
            </script>
			<?
			}	
		}
	}
	if ($_GET["accion"] == 701 and in_array(701, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_sociedad set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										siglas = '".$siglas."'
										where 	idtipo_sociedad = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			?>
			<script>
            mostrarMensajes("exito", "El regsitro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=13&accion=699'",5000);
            </script>
			<?
	}
	if ($_GET["accion"] == 702 and in_array(702, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_sociedad where idtipo_sociedad = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipo_sociedad where idtipo_sociedad = '$codigo'",$conexion_db);
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipo de Sociedad (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=13&accion=699'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Tipo de Sociedad ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "El regsitro se Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=13&accion=699'",5000);
            </script>
			<?
			}	
	}
	}
	
	
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 14){
	
	if($_GET["accion"] == 821 and in_array(821,$privilegios) == true){	
	
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
			<script>
            mostrarMensajes("error", "Disculpe el Registro que esta ingresando existe, vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=14&accion=820'",5000);
            </script>
			<?
	}else{
		
			mysql_query("insert into tipo_sociedad
									(descripcion,usuario,fechayhora,status, siglas) 
							values ('$descripcion','$login','$fh','a','$siglas')");
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_sociedad", "lib/listas/consultar_tablas_select.php", "tipo_sociedad", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=14&accion=820'",5000);
            </script>
			<?
			}	
		}
	}
	if ($_GET["accion"] == 822 and in_array(822, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_sociedad set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										siglas = '".$siglas."'
										where 	idtipo_sociedad = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			?>
			<script>
            mostrarMensajes("exito", "El regsitro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=14&accion=820'",5000);
            </script>
			<?
	}
	if ($_GET["accion"] == 823 and in_array(823, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_sociedad where idtipo_sociedad = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipo_sociedad where idtipo_sociedad = '$codigo'",$conexion_db);
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipo de Sociedad (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=14&accion=820'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Tipo de Sociedad ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "El regsitro se Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=14&accion=820'",5000);
            </script>
			<?
			}	
			
		redirecciona("principal.php?modulo=14&accion=820");
	}
	}













if($_SESSION["modulo"] == 16){
	
	if($_GET["accion"] == 911 and in_array(911,$privilegios) == true){	
	
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
			<script>
            mostrarMensajes("error", "Disculpe el Registro que esta ingresando existe, vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=16&accion=910'",5000);
            </script>
			<?
	}else{
		
			mysql_query("insert into tipo_sociedad
									(descripcion,usuario,fechayhora,status, siglas) 
							values ('$descripcion','$login','$fh','a','$siglas')");
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_sociedad", "lib/listas/consultar_tablas_select.php", "tipo_sociedad", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=16&accion=910'",5000);
            </script>
			<?
			}	
		}
	}
	if ($_GET["accion"] == 912 and in_array(912, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_sociedad set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										siglas = '".$siglas."'
										where 	idtipo_sociedad = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			?>
			<script>
            mostrarMensajes("exito", "El regsitro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=16&accion=910'",5000);
            </script>
			<?
	}
	if ($_GET["accion"] == 913 and in_array(913, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_sociedad where idtipo_sociedad = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipo_sociedad where idtipo_sociedad = '$codigo'",$conexion_db);
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipo de Sociedad (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=16&accion=910'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Tipo de Sociedad ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "El regsitro se Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=16&accion=910'",5000);
            </script>
			<?
			}	
	}
	}


if($_SESSION["modulo"] == 17){
	
	if($_GET["accion"] == 998 and in_array(998,$privilegios) == true){	
	
	if (mysql_num_rows($busca_existe_registro)>0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe el Registro que esta ingresando existe, vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=17&accion=982'",5000);
            </script>
			<?
	}else{
		
			mysql_query("insert into tipo_sociedad
									(descripcion,usuario,fechayhora,status, siglas) 
							values ('$descripcion','$login','$fh','a','$siglas')");
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_sociedad", "lib/listas/consultar_tablas_select.php", "tipo_sociedad", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=17&accion=982'",5000);
            </script>
			<?
			}	
		}
	}
	if ($_GET["accion"] == 999 and in_array(999, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_sociedad set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										siglas = '".$siglas."'
										where 	idtipo_sociedad = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			?>
			<script>
            mostrarMensajes("exito", "El regsitro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=17&accion=982'",5000);
            </script>
			<?
	}
	if ($_GET["accion"] == 1000 and in_array(1000, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_sociedad where idtipo_sociedad = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipo_sociedad where idtipo_sociedad = '$codigo'",$conexion_db);
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipo de Sociedad (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=17&accion=982'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Tipo de Sociedad ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "El regsitro se Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=17&accion=982'",5000);
            </script>
			<?
			}	
	}
	}


if($_SESSION["modulo"] == 19){
	
	if($_GET["accion"] == 1056 and in_array(1056,$privilegios) == true){	
	
	if (mysql_num_rows($busca_existe_registro)>0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe el Registro que esta ingresando existe, vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1038'",5000);
            </script>
			<?
	}else{
		
			mysql_query("insert into tipo_sociedad
									(descripcion,usuario,fechayhora,status, siglas) 
							values ('$descripcion','$login','$fh','a','$siglas')");
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idtipo_sociedad", "lib/listas/consultar_tablas_select.php", "tipo_sociedad", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				?>
			<script>
            mostrarMensajes("exito", "El registro se Ingreso con Exito");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1038'",5000);
            </script>
			<?
			}	
		}
	}
	if ($_GET["accion"] == 1057 and in_array(1057, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_sociedad set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										siglas = '".$siglas."'
										where 	idtipo_sociedad = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de Sociedad ('.$descripcion.')',$login,$fh,$pc,'tipo_sociedad',$conexion_db);
			?>
			<script>
            mostrarMensajes("exito", "El regsitro se modifico con Exito");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1038'",5000);
            </script>
			<?
	}
	if ($_GET["accion"] == 1058 and in_array(1058, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_sociedad where idtipo_sociedad = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipo_sociedad where idtipo_sociedad = '$codigo'",$conexion_db);
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipo de Sociedad (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1038'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Tipo de Sociedad ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "El regsitro se Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1038'",5000);
            </script>
			<?
			}	
	}
	}

	
	
	
}
?>