<?php

if($_POST["ingresar"]){
$_GET["accion"] = 239;
}
if($_POST["ingresar2"]){
$_GET["accion"] = 329;
}
if($_POST["ingresar3"]){
$_GET["accion"] = 553;
}
if($_POST["ingresar4"]){
$_GET["accion"] = 654;
}
if($_POST["ingresar5"]){
$_GET["accion"] = 845;
}
if($_POST["ingresar8"]){
$_GET["accion"] = 934;
}
if($_POST["ingresar9"]){
$_GET["accion"] = 1075;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from impuestos 
												where status='a' order by descripcion"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from impuestos where status='a'";
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

if (($_GET["accion"] == 330 || $_GET["accion"] == 331) || ($_GET["accion"] == 240 || $_GET["accion"] == 241) || ($_GET["accion"] == 554 || $_GET["accion"] == 555) || ($_GET["accion"] == 655 || $_GET["accion"] == 656) || ($_GET["accion"] == 846 || $_GET["accion"] == 847) || ($_GET["accion"] == 936 || $_GET["accion"] == 937) || ($_GET["accion"] == 1076 || $_GET["accion"] == 1077)){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from impuestos 
										where idimpuestos like '".$_GET['c']."'"
											,$conexion_db);
	$regimpuestos=mysql_fetch_assoc($sql);
	
}

?>

<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		
		if (document.frmimpuestos.descripcion.value.length==0){
			alert("Debe escribir una Descripcion para el Tipo de Presupuesto")
			document.frmimpuestos.descripcion.focus()
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
<h4 align=center>Impuestos</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<form id="frmimpuestos" action="principal.php?modulo=<?=$_SESSION["modulo"]?>&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data">	
  <input type="hidden" id="codigo" name="codigo" maxlength="9" size="9" <?php echo 'value="'.$regimpuestos['idimpuestos'].'"';?>>
  <table align=center cellpadding=2 cellspacing=0 width="60%">
<tr>
				<td align='right' class='viewPropTitle'>Descripcion:</td>
				<td class=''><input type="text" name="descripcion" maxlength="255" size="45" id="descripcion" <?php echo 'value="'.$regimpuestos['descripcion'].'"';?> onKeyUp="validarVacios('descripcion', this.value, 'frmimpuestos')" onBlur="validarVacios('descripcion', this.value, 'frmimpuestos')" autocomplete="OFF" style="padding:0px 20px 0px 0px;">
                
                <?
                if($_SESSION["modulo"] == 2){
					$accion_nuevo = 552;	
				}else if($_SESSION["modulo"] == 3){
					$accion_nuevo = 238;
				}else if($_SESSION["modulo"] == 4){
					$accion_nuevo = 328;
				}else if($_SESSION["modulo"] == 12){
					$accion_nuevo = 653;
				}else if($_SESSION["modulo"] == 14){
					$accion_nuevo = 828;
				}else if($_SESSION["modulo"] == 16){
					$accion_nuevo = 934;
				}else if($_SESSION["modulo"] == 19){
					$accion_nuevo = 1068;
				}
				
				?>
                
                &nbsp; <a href='principal.php?modulo=<?=$_SESSION["modulo"]?>&accion=<?=$accion_nuevo?>' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Requerido"></a>
                
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
                         <option value="porcentaje">Porcentaje</option>
                    </select>               
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/compras_servicios/reportes.php?nombre=impuestos&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
                </td>
            </tr>
          </table>
          <iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>          
          </div>
                
                                </td>
			</tr>
            
            <tr>
				<td align='right' class='viewPropTitle'>Siglas:</td>
				<td class=''><input name="siglas" type="text" id="siglas" value="<?=$regimpuestos["siglas"]?>" size="10" onKeyUp="validarVacios('siglas', this.value, 'frmimpuestos')" onBlur="validarVacios('siglas', this.value, 'frmimpuestos')" autocomplete="OFF" style="padding:0px 20px 0px 0px;"></td>
			</tr>
            
             <tr>
               <td align='right' class='viewPropTitle'>Porcentaje:</td>
               <td class=''><input name="porcentaje" type="text" id="porcentaje" value="<?=$regimpuestos["porcentaje"]?>" size="6" onKeyUp="validarVacios('porcentaje', this.value, 'frmimpuestos')" onBlur="validarVacios('porcentaje', this.value, 'frmimpuestos')" autocomplete="OFF" style="padding:0px 20px 0px 0px;">
                 <strong>%</strong></td>
             </tr>
             <tr>
				<td align='right' class='viewPropTitle'>Partida Propia:</td>
				<td class=''>
                <table align="left">
                	<tr>
                    	<td><input type="checkbox" name="partida_propia" id="partida_propia" onClick="aparecerClasificador()" value="1" <? if($regimpuestos["destino_partida"] == 1){echo "checked";}?>></td>
                        <td>
                        <div id="divClasificador" 
                        	style="<? if($regimpuestos["destino_partida"] and $regimpuestos["destino_partida"] == 1){echo "display:block";}else{echo "display:none";}?> ">
                         <?
						 if($regimpuestos["destino_partida"] == 1){
							 $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = ".$regimpuestos["idclasificador_presupuestario"]."")or die(mysql_error());
							 $bus_clasificador = mysql_fetch_array($sql_clasificador);
						 }
						 ?>
                        Clasificador Presupuestario: <input type="text" 
                        									size="50" 
                                                            name="nombre_clasificador" 
                                                            id="nombre_clasificador"
                                                            <? if($regimpuestos["destino_partida"] == 1){echo "value='(".$bus_clasificador["codigo_cuenta"].") ".$bus_clasificador["denominacion"]."'";}?>>

                        <input type="hidden" id="id_clasificador_presupuestario" 
                        					name="id_clasificador_presupuestario"
                                            <? if($regimpuestos["destino_partida"] == 1){echo "value='".$regimpuestos["idclasificador_presupuestario"]."'";}?>>
                        </div>
                        </td>
                        <td align="left"><div style="<? if($regimpuestos["destino_partida"] and $regimpuestos["destino_partida"] == 1){echo "display:block";}else{echo "display:none";}?>" id="imageSelecCategoria"><img style="display:block"
                                                src="imagenes/search0.png" 
                                                title="Buscar Categoria Programatica" 
                                                id="buscarCategoriaProgramatica" 
                                                name="buscarCategoriaProgramatica"
                                                onclick="window.open('lib/listas/listar_clasificador_presupuestario.php?destino=impuestos','partida propia','resizable = no, scrollbars=yes, width=900, height = 500')"></div></td>
                    </tr>
                  </table>    
            </td>
    </tr>
  </table>
<table align=center cellpadding=2 cellspacing=0>
			<tr><td>
            			    <?php
    if($_REQUEST["pop"]){
	echo "<input type='hidden' value='true' name='pop' id='pop'>";
	}
				if($_SESSION["modulo"] == 3){
					if($_GET["accion"] != 240 and $_GET["accion"] != 241 and in_array(239, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 240 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 241 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				
				
				if($_SESSION["modulo"] == 4){
					if($_GET["accion"] != 330 and $_GET["accion"] != 331 and in_array(329, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar2' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 330 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 331 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				
				
				
				if($_SESSION["modulo"] == 2){
					if($_GET["accion"] != 554 and $_GET["accion"] != 555 and in_array(553, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar3' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 554 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 555 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				
				if($_SESSION["modulo"] == 12){
					if($_GET["accion"] != 655 and $_GET["accion"] != 656 and in_array(654, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar4' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 655 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 656 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				
				
				if($_SESSION["modulo"] == 14){
					if($_GET["accion"] != 846 and $_GET["accion"] != 847 and in_array(845, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar5' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 846 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 847 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				
				
				if($_SESSION["modulo"] == 16){
					if($_GET["accion"] != 936 and $_GET["accion"] != 937 and in_array(935, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar6' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 936 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 937 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				if($_SESSION["modulo"] == 19){
					if($_GET["accion"] != 1076 and $_GET["accion"] != 1077 and in_array(1075, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar9' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 1076 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 1077 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
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
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td align="center">
					  <form name="grilla" action="impuestos.php" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="50%">
							<thead>
								<tr >
								  <td align="center" class="Browse" width="70%">Descripcion</td>
                                    <td align="center" class="Browse">Siglas</td>
                                    <td align="center" class="Browse">Porcentaje</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
									{ ?>
						  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
									echo "<td align='left' class='Browse'>&nbsp;".$llenar_grilla["descripcion"]."</td>";
									echo "<td align='left' class='Browse'>&nbsp;".$llenar_grilla["siglas"]."</td>";
									echo "<td align='right' class='Browse'>&nbsp;".$llenar_grilla["porcentaje"]."</td>";
									$c=$llenar_grilla["idimpuestos"];
										
									if($_SESSION["modulo"] == 3){	
										if(in_array(240,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=240&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(241,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=241&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}	
									
									
									if($_SESSION["modulo"] == 4){	
										if(in_array(330,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=330&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(331,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=331&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}	
									
									
									
									if($_SESSION["modulo"] == 2){	
										if(in_array(554,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=554&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(555,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=555&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}
									
									
									
									
									if($_SESSION["modulo"] == 12){	
										if(in_array(655,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=655&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(656,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=656&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}
									
									
									
									if($_SESSION["modulo"] == 14){	
										if(in_array(846,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=846&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(847,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=847&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}	
									
									
									
									
									
									
									
									if($_SESSION["modulo"] == 16){	
										if(in_array(936,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=936&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(937,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=937&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}
									
									if($_SESSION["modulo"] == 19){	
										if(in_array(1076,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1076&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(1077,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1077&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
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
<script> document.getElementById('descripcion').focus() </script>
</body>
</html>

<?php
	if($_POST){
		$siglas = $_POST["siglas"];
		$porcentaje = $_POST["porcentaje"];
		$codigo=$_POST["codigo"];
		$descripcion=strtoupper($_POST["descripcion"]);
		if($_POST["partida_propia"]){
			$partida_propia = 1;
			$id_clasificador = $_POST["id_clasificador_presupuestario"];
		}else{
			$partida_propia = 0;
			$id_clasificador = 0;
		}
		
		
		$busca_existe_registro=mysql_query("select * from impuestos where descripcion = '".$_POST['descripcion']."'  and status='a'",$conexion_db);
		
		
		
		if($_SESSION["modulo"] == 4){
		if($_GET["accion"] == 329 and in_array(329, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe ya este Impuesto existe");
				redirecciona("principal.php?modulo=4&accion=328");
			}else{	
				
					mysql_query("insert into impuestos
											(descripcion,
											 siglas, 
											 porcentaje, 
											 destino_partida, 
											 idclasificador_presupuestario, 
											 usuario,
											 fechayhora,
											 status)values('$descripcion',
											 				'$siglas', 
															'$porcentaje', 
															'".$partida_propia."', 
															'".$id_clasificador."', 
															'$login',
															'$fh',
															'a')",$conexion_db)or die(mysql_error());

				$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Documento Requerido ('.$descripcion.')',$login,$fh,$pc,'impuestos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("impuesto", "lib/consultar_tablas_select.php", "impuestos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					mensaje("El Impuesto se Ingreso con Exito");
					redirecciona("principal.php?modulo=4&accion=328");
			}
					
					

				}
			}
		if($_GET["accion"] == 330 and in_array(330, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update impuestos set 
											descripcion='".$descripcion."',
											siglas='".$siglas."',
											porcentaje='".$porcentaje."',
											destino_partida = '".$partida_propia."',
											idclasificador_presupuestario = '".$id_clasificador."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idimpuestos = '$codigo' and status = 'a'",$conexion_db)or die(mysql_error());
				
				registra_transaccion('Modificar Documentos Requeridos ('.$descripcion.')',$login,$fh,$pc,'impuestos',$conexion_db);
				mensaje("El Impuesto se Actualizo con Exito");
				redirecciona("principal.php?modulo=4&accion=328");
		}
		if($_GET["accion"] == 331 and in_array(331, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from impuestos where idimpuestos = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from impuestos where idimpuestos = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Documentos Requeridos (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
						redirecciona("principal.php?modulo=4&accion=328");
					}else{
						registra_transaccion('Eliminar Documentos Requeridos ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("El Impuesto se Eliminado con Exito");
						redirecciona("principal.php?modulo=4&accion=328");
					}
				
		}
		
		}
		
		
		
		
		
		
		
		
		
		
		
		if($_SESSION["modulo"] == 3){
		if($_GET["accion"] == 239 and in_array(239, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe ya este Impuesto existe");
				redirecciona("principal.php?modulo=3&accion=238");
			}else{	
				
					mysql_query("insert into impuestos
											(descripcion,
											 siglas, 
											 porcentaje, 
											 destino_partida, 
											 idclasificador_presupuestario, 
											 usuario,
											 fechayhora,
											 status)values('$descripcion',
											 				'$siglas', 
															'$porcentaje', 
															'".$partida_propia."', 
															'".$id_clasificador."', 
															'$login',
															'$fh',
															'a')",$conexion_db)or die(mysql_error());

				$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Documento Requerido ('.$descripcion.')',$login,$fh,$pc,'impuestos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("impuesto", "lib/consultar_tablas_select.php", "impuestos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					mensaje("El Impuesto se Ingreso con Exito");
					redirecciona("principal.php?modulo=4&accion=328");
			}
					
					

				}
			}
		if($_GET["accion"] == 240 and in_array(240, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update impuestos set 
											descripcion='".$descripcion."',
											siglas='".$siglas."',
											porcentaje='".$porcentaje."',
											destino_partida = '".$partida_propia."',
											idclasificador_presupuestario = '".$id_clasificador."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idimpuestos = '$codigo' and status = 'a'",$conexion_db)or die(mysql_error());
				
				registra_transaccion('Modificar Documentos Requeridos ('.$descripcion.')',$login,$fh,$pc,'impuestos',$conexion_db);
				mensaje("El Impuesto se Actualizo con Exito");
				redirecciona("principal.php?modulo=3&accion=238");
		}
		if($_GET["accion"] == 241 and in_array(241, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from impuestos where idimpuestos = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from impuestos where idimpuestos = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Documentos Requeridos (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
						redirecciona("principal.php?modulo=3&accion=238");
					}else{
						registra_transaccion('Eliminar Documentos Requeridos ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("El Impuesto se Eliminado con Exito");
						redirecciona("principal.php?modulo=3&accion=238");
					}
				
		}
		
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		if($_SESSION["modulo"] == 12){
		if($_GET["accion"] == 654 and in_array(654, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe ya este Impuesto existe");
				redirecciona("principal.php?modulo=12&accion=653");
			}else{	
				
					mysql_query("insert into impuestos
											(descripcion,
											 siglas, 
											 porcentaje, 
											 destino_partida, 
											 idclasificador_presupuestario, 
											 usuario,
											 fechayhora,
											 status)values('$descripcion',
											 				'$siglas', 
															'$porcentaje', 
															'".$partida_propia."', 
															'".$id_clasificador."', 
															'$login',
															'$fh',
															'a')",$conexion_db)or die(mysql_error());

				$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Documento Requerido ('.$descripcion.')',$login,$fh,$pc,'impuestos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("impuesto", "lib/consultar_tablas_select.php", "impuestos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					mensaje("El Impuesto se Ingreso con Exito");
					redirecciona("principal.php?modulo=12&accion=653");
			}
					
					

				}
			}
		if($_GET["accion"] == 655 and in_array(656, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update impuestos set 
											descripcion='".$descripcion."',
											siglas='".$siglas."',
											porcentaje='".$porcentaje."',
											destino_partida = '".$partida_propia."',
											idclasificador_presupuestario = '".$id_clasificador."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idimpuestos = '$codigo' and status = 'a'",$conexion_db)or die(mysql_error());
				
				registra_transaccion('Modificar Documentos Requeridos ('.$descripcion.')',$login,$fh,$pc,'impuestos',$conexion_db);
				mensaje("El Impuesto se Actualizo con Exito");
				redirecciona("principal.php?modulo=12&accion=653");
		}
		
		if($_GET["accion"] == 656 and in_array(656, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from impuestos where idimpuestos = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from impuestos where idimpuestos = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Documentos Requeridos (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
						redirecciona("principal.php?modulo=12&accion=653");
					}else{
						registra_transaccion('Eliminar Documentos Requeridos ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("El Impuesto se Eliminado con Exito");
						redirecciona("principal.php?modulo=12&accion=653");
					}
				
		}
		
		}
		
		
		
		
		
		
		
		
		
		
		
		if($_SESSION["modulo"] == 14){
		if($_GET["accion"] == 845 and in_array(845, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe ya este Impuesto existe");
				redirecciona("principal.php?modulo=14&accion=844");
			}else{	
				
					mysql_query("insert into impuestos
											(descripcion,
											 siglas, 
											 porcentaje, 
											 destino_partida, 
											 idclasificador_presupuestario, 
											 usuario,
											 fechayhora,
											 status)values('$descripcion',
											 				'$siglas', 
															'$porcentaje', 
															'".$partida_propia."', 
															'".$id_clasificador."', 
															'$login',
															'$fh',
															'a')",$conexion_db)or die(mysql_error());

				$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Documento Requerido ('.$descripcion.')',$login,$fh,$pc,'impuestos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("impuesto", "lib/consultar_tablas_select.php", "impuestos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					mensaje("El Impuesto se Ingreso con Exito");
					redirecciona("principal.php?modulo=14&accion=844");
			}
					
					

				}
			}
		if($_GET["accion"] == 846 and in_array(846, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update impuestos set 
											descripcion='".$descripcion."',
											siglas='".$siglas."',
											porcentaje='".$porcentaje."',
											destino_partida = '".$partida_propia."',
											idclasificador_presupuestario = '".$id_clasificador."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idimpuestos = '$codigo' and status = 'a'",$conexion_db)or die(mysql_error());
				
				registra_transaccion('Modificar Documentos Requeridos ('.$descripcion.')',$login,$fh,$pc,'impuestos',$conexion_db);
				mensaje("El Impuesto se Actualizo con Exito");
				redirecciona("principal.php?modulo=14&accion=844");
		}
		
		if($_GET["accion"] == 847 and in_array(847, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from impuestos where idimpuestos = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from impuestos where idimpuestos = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Documentos Requeridos (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
						redirecciona("principal.php?modulo=14&accion=844");
					}else{
						registra_transaccion('Eliminar Documentos Requeridos ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("El Impuesto se Eliminado con Exito");
						redirecciona("principal.php?modulo=14&accion=844");
					}
				
		}
		
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		if($_SESSION["modulo"] == 16){
		if($_GET["accion"] == 935 and in_array(935, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe ya este Impuesto existe");
				redirecciona("principal.php?modulo=16&accion=934");
			}else{	
				
					mysql_query("insert into impuestos
											(descripcion,
											 siglas, 
											 porcentaje, 
											 destino_partida, 
											 idclasificador_presupuestario, 
											 usuario,
											 fechayhora,
											 status)values('$descripcion',
											 				'$siglas', 
															'$porcentaje', 
															'".$partida_propia."', 
															'".$id_clasificador."', 
															'$login',
															'$fh',
															'a')",$conexion_db)or die(mysql_error());

				$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Documento Requerido ('.$descripcion.')',$login,$fh,$pc,'impuestos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("impuesto", "lib/consultar_tablas_select.php", "impuestos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					mensaje("El Impuesto se Ingreso con Exito");
					redirecciona("principal.php?modulo=16&accion=934");
			}
					
					

				}
			}
		if($_GET["accion"] == 936 and in_array(936, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update impuestos set 
											descripcion='".$descripcion."',
											siglas='".$siglas."',
											porcentaje='".$porcentaje."',
											destino_partida = '".$partida_propia."',
											idclasificador_presupuestario = '".$id_clasificador."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idimpuestos = '$codigo' and status = 'a'",$conexion_db)or die(mysql_error());
				
				registra_transaccion('Modificar Documentos Requeridos ('.$descripcion.')',$login,$fh,$pc,'impuestos',$conexion_db);
				mensaje("El Impuesto se Actualizo con Exito");
				redirecciona("principal.php?modulo=16&accion=934");
		}
		
		if($_GET["accion"] == 937 and in_array(937, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from impuestos where idimpuestos = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from impuestos where idimpuestos = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Documentos Requeridos (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
						redirecciona("principal.php?modulo=16&accion=934");
					}else{
						registra_transaccion('Eliminar Documentos Requeridos ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("El Impuesto se Eliminado con Exito");
						redirecciona("principal.php?modulo=16&accion=934");
					}
				
		}
		
		}
		
		
		
		if($_SESSION["modulo"] == 19){
		if($_GET["accion"] == 1075 and in_array(1075, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe ya este Impuesto existe");
				redirecciona("principal.php?modulo=16&accion=934");
			}else{	
				
					mysql_query("insert into impuestos
											(descripcion,
											 siglas, 
											 porcentaje, 
											 destino_partida, 
											 idclasificador_presupuestario, 
											 usuario,
											 fechayhora,
											 status)values('$descripcion',
											 				'$siglas', 
															'$porcentaje', 
															'".$partida_propia."', 
															'".$id_clasificador."', 
															'$login',
															'$fh',
															'a')",$conexion_db)or die(mysql_error());

				$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Documento Requerido ('.$descripcion.')',$login,$fh,$pc,'impuestos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("impuesto", "lib/consultar_tablas_select.php", "impuestos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					mensaje("El Impuesto se Ingreso con Exito");
					redirecciona("principal.php?modulo=19&accion=1068");
			}
					
					

				}
			}
		if($_GET["accion"] == 1076 and in_array(1076, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update impuestos set 
											descripcion='".$descripcion."',
											siglas='".$siglas."',
											porcentaje='".$porcentaje."',
											destino_partida = '".$partida_propia."',
											idclasificador_presupuestario = '".$id_clasificador."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idimpuestos = '$codigo' and status = 'a'",$conexion_db)or die(mysql_error());
				
				registra_transaccion('Modificar Documentos Requeridos ('.$descripcion.')',$login,$fh,$pc,'impuestos',$conexion_db);
				mensaje("El Impuesto se Actualizo con Exito");
				redirecciona("principal.php?modulo=19&accion=1068");
		}
		
		if($_GET["accion"] == 1077 and in_array(1077, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from impuestos where idimpuestos = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from impuestos where idimpuestos = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Documentos Requeridos (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
						redirecciona("principal.php?modulo=19&accion=1068");
					}else{
						registra_transaccion('Eliminar Documentos Requeridos ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("El Impuesto se Eliminado con Exito");
						redirecciona("principal.php?modulo=19&accion=1068");
					}
				
		}
		
		}
		
		
	}


?>