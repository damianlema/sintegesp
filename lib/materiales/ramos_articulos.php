<?php

if($_POST["ingresar"]){
$_GET["accion"] = 231;
}
if($_POST["ingresar2"]){
$_GET["accion"] = 325;
}
if($_POST["ingresar3"]){
$_GET["accion"] = 557;
}
if($_POST["ingresar4"]){
$_GET["accion"] = 650;
}
if($_POST["ingresar5"]){
$_GET["accion"] = 721;
}
if($_POST["ingresar6"]){
$_GET["accion"] = 841;
}
if($_POST["ingresar7"]){
$_GET["accion"] = 931;
}
if($_POST["ingresar9"]){
$_GET["accion"] = 1078;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from ramos_articulos 
												where status='a' order by descripcion"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from ramos_articulos where status='a'";
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

if (($_GET["accion"] == 232 || $_GET["accion"] == 233) || ($_GET["accion"] == 326 || $_GET["accion"] == 327) || ($_GET["accion"] == 558 || $_GET["accion"] == 559) || ($_GET["accion"] == 651 || $_GET["accion"] == 652) || ($_GET["accion"] == 722 || $_GET["accion"] == 723) || ($_GET["accion"] == 842 || $_GET["accion"] == 843) || ($_GET["accion"] == 932 || $_GET["accion"] == 933) || ($_GET["accion"] == 1079 || $_GET["accion"] == 1080)){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from ramos_articulos 
										where idramo_articulo like '".$_GET['c']."'"
											,$conexion_db);
	$regramos_articulos=mysql_fetch_assoc($sql);
	
}

?>

<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		
		if (document.frmramos_articulos.descripcion.value.length==0){
			alert("Debe escribir una Descripcion para el Tipo de Presupuesto")
			document.frmramos_articulos.descripcion.focus()
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
<h4 align=center>Ramos de Materiales</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<form id="frmramos_articulos" action="principal.php?modulo=<?=$_SESSION["modulo"]?>&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
  <input type="hidden" id="codigo" name="codigo" maxlength="9" size="9" <?php echo 'value="'.$regramos_articulos['idramo_articulo'].'"';?>>
		<table align=center cellpadding=2 cellspacing=0 width="31%">
<tr>
				<td align='right' class='viewPropTitle'>Descripcion:</td>
				<td class=''>
                <table>
                <tr>
                <td>
                <input type="text" name="descripcion" maxlength="255" size="45" id="descripcion" <?php echo 'value="'.$regramos_articulos['descripcion'].'"';?> onKeyUp="validarVacios('descripcion', this.value, 'frmramos_articulos')" onBlur="validarVacios('descripcion', this.value, 'frmramos_articulos')" autocomplete="OFF" style="padding:0px 20px 0px 0px;">
                </td>
                <td>
				<?
                if($_SESSION["modulo"] == 2){
					$accion_nuevo = 556;	
				}else if($_SESSION["modulo"] == 3){
					$accion_nuevo = 230;
				}else if($_SESSION["modulo"] == 4){
					$accion_nuevo = 324;
				}else if($_SESSION["modulo"] == 12){
					$accion_nuevo = 649;
				}else if($_SESSION["modulo"] == 1){
					$accion_nuevo = 721;
				}else if($_SESSION["modulo"] == 14){
					$accion_nuevo = 841;
				}else if($_SESSION["modulo"] == 16){
					$accion_nuevo = 930;
				}else if($_SESSION["modulo"] == 19){
					$accion_nuevo = 1067;
				}
				
				
				?>
                
                
                <a href='principal.php?modulo=<?=$_SESSION["modulo"]?>&accion=<?=$accion_nuevo?>' class='Browse'>
                <img src="imagenes/nuevo.png" border="0" title="Nuevo Ramo de Materiales"></a>
                </td>
                <td>
                <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/reportes/compras_servicios/reportes.php?nombre=ramos';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
                </td>
                </tr>
                </table>
                
                
                
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
					if($_GET["accion"] != 326 and $_GET["accion"] != 327 and in_array(325, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar2' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 326 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 327 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				if($_SESSION["modulo"] == 3){
					if($_GET["accion"] != 232 and $_GET["accion"] != 233 and in_array(231, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 232 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 233 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				if($_SESSION["modulo"] == 2){
					if($_GET["accion"] != 558 and $_GET["accion"] != 559 and in_array(557, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar3' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 558 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 559 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				
				if($_SESSION["modulo"] == 12){
					if($_GET["accion"] != 651 and $_GET["accion"] != 652 and in_array(650, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar4' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 651 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 652 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				if($_SESSION["modulo"] == 1){
					if($_GET["accion"] != 722 and $_GET["accion"] != 723 and in_array(721, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar5' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 722 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 723 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				if($_SESSION["modulo"] == 14){
					if($_GET["accion"] != 842 and $_GET["accion"] != 842 and in_array(841, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar6' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 842 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 843 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				
				if($_SESSION["modulo"] == 16){
					if($_GET["accion"] != 932 and $_GET["accion"] != 932 and in_array(931, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar7' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 932 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 933 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				if($_SESSION["modulo"] == 19){
					if($_GET["accion"] != 1079 and $_GET["accion"] != 1080 and in_array(1078, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar7' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 1079 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 1080 and in_array($_GET["accion"], $privilegios) == true){
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
					  <form name="grilla" action="ramos_articulos.php" method="POST">
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
									$c=$llenar_grilla["idramo_articulo"];
									if($_SESSION["modulo"] == 4){	
										if(in_array(326,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=326&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(327,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=327&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}
									
									
									if($_SESSION["modulo"] == 3){	
										if(in_array(232,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=232&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(233,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=233&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}
									
									
									if($_SESSION["modulo"] == 2){	
										if(in_array(558,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=558&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(559,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=559&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}	
									
									
									if($_SESSION["modulo"] == 12){	
										if(in_array(651,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=651&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(652,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=652&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}	
									
									if($_SESSION["modulo"] == 1){	
										if(in_array(722,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=1&accion=722&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(723,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=1&accion=723&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}
									
									
									
									
									if($_SESSION["modulo"] == 14){	
										if(in_array(842,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=842&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(843,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=843&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}	
									
									
									
									if($_SESSION["modulo"] == 16){	
										if(in_array(932,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=932&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(933,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=933&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
										}
									}	
									
									if($_SESSION["modulo"] == 19){	
										if(in_array(1079,$privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1079&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(1080,$privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1080&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
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
<script> document.getElementById('descripcion').focus() </script>
</body>
</html>

<?php
	if($_POST){
		$codigo=$_POST["codigo"];
		$descripcion=strtoupper($_POST["descripcion"]);
		$busca_existe_registro=mysql_query("select * from ramos_articulos where descripcion = '".$_POST['descripcion']."'  and status='a'",$conexion_db);
		
		
		if($_SESSION["modulo"] == 4){
		if($_GET["accion"] == 325 and in_array(325, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe ya este Ramo existe");
				redirecciona("principal.php?modulo=4&accion=324");
			}else{	
				
					mysql_query("insert into ramos_articulos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
					$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("ramo", "lib/consultar_tablas_select.php", "ramos_articulos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					mensaje("El Ramo de Materiales se Ingreso con Exito");
					redirecciona("principal.php?modulo=4&accion=324");
			}
					
					

				}
			}
		if($_GET["accion"] == 327 and in_array(327, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update ramos_articulos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idramo_articulo = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
				mensaje("El Ramo de Materiales se Actualizo con Exito");
				redirecciona("principal.php?modulo=4&accion=324");
		}
		if($_GET["accion"] == 327 and in_array(327, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Ramo de Materiales (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
						redirecciona("principal.php?modulo=4&accion=324");
					}else{
						registra_transaccion('Eliminar Ramo de Materiales ('.$bus["descripcion"].')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
						mensaje("El Ramo de Materiales se Eliminado con Exito");
						redirecciona("principal.php?modulo=4&accion=324");
					}
				
		}
		}
		
		
		
		
		
		
		
		
		
		
		if($_SESSION["modulo"] == 3){
		//echo $_GET["accion"];
		if($_GET["accion"] == 231 and in_array(231, $privilegios) == true){
		//echo "ENTRO ACA";
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe ya este Ramo existe");
				redirecciona("principal.php?modulo=3&accion=230");
			}else{	
				
					mysql_query("insert into ramos_articulos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
				$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("ramo", "lib/consultar_tablas_select.php", "ramos_articulos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					mensaje("El Ramo de Materiales se Ingreso con Exito");
					redirecciona("principal.php?modulo=3&accion=230");
			}
					
					

				}
			}
		if($_GET["accion"] == 232 and in_array(232, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update ramos_articulos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idramo_articulo = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
				mensaje("El Ramo de Materiales se Actualizo con Exito");
				redirecciona("principal.php?modulo=3&accion=230");
		}
		if($_GET["accion"] == 233 and in_array(233, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Ramo de Materiales (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
						redirecciona("principal.php?modulo=3&accion=230");
					}else{
						registra_transaccion('Eliminar Ramo de Materiales ('.$bus["descripcion"].')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
						mensaje("El Ramo de Materiales se Eliminado con Exito");
						redirecciona("principal.php?modulo=3&accion=230");
					}
				
		}
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		if($_SESSION["modulo"] == 2){
		if($_GET["accion"] == 557 and in_array(557, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe ya este Ramo existe");
				redirecciona("principal.php?modulo=2&accion=556");
			}else{	
				
					mysql_query("insert into ramos_articulos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
				$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("ramo", "lib/consultar_tablas_select.php", "ramos_articulos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					mensaje("El Ramo de Materiales se Ingreso con Exito");
					redirecciona("principal.php?modulo=2&accion=556");
			}
					
					

				}
			}
		if($_GET["accion"] == 558 and in_array(558, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update ramos_articulos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idramo_articulo = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
				mensaje("El Ramo de Materiales se Actualizo con Exito");
				redirecciona("principal.php?modulo=2&accion=556");
		}
		if($_GET["accion"] == 559 and in_array(559, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Ramo de Materiales (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
						redirecciona("principal.php?modulo=2&accion=556");
					}else{
						registra_transaccion('Eliminar Ramo de Materiales ('.$bus["descripcion"].')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
						mensaje("El Ramo de Materiales se Eliminado con Exito");
						redirecciona("principal.php?modulo=2&accion=556");
					}
				
		}
		}
		
		
		
		
		
		
		
		
		
		
		
		if($_SESSION["modulo"] == 12){
		if($_GET["accion"] == 650 and in_array(650, $privilegios) == true){
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe ya este Ramo existe");
				redirecciona("principal.php?modulo=12&accion=649");
			}else{	
				
					mysql_query("insert into ramos_articulos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
				$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
					
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("ramo", "lib/consultar_tablas_select.php", "ramos_articulos", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					mensaje("El Ramo de Materiales se Ingreso con Exito");
					redirecciona("principal.php?modulo=12&accion=649");
			}
					
					

				}
			}
		if($_GET["accion"] == 651 and in_array(651, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update ramos_articulos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idramo_articulo = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
				mensaje("El Ramo de Materiales se Actualizo con Exito");
				redirecciona("principal.php?modulo=12&accion=649");
		}
		if($_GET["accion"] == 652 and in_array(652, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Ramo de Materiales (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
						redirecciona("principal.php?modulo=12&accion=649");
					}else{
						registra_transaccion('Eliminar Ramo de Materiales ('.$bus["descripcion"].')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
						mensaje("El Ramo de Materiales se Eliminado con Exito");
						redirecciona("principal.php?modulo=12&accion=649");
					}
				
		}
		}
		
		
		
		
		if($_SESSION["modulo"] == 1){
			if($_GET["accion"] == 721 and in_array(721, $privilegios) == true){
				if (mysql_num_rows($busca_existe_registro)>0){
					mensaje("Disculpe ya este Ramo existe");
					redirecciona("principal.php?modulo=1&accion=720");
				}else{	
					
					mysql_query("insert into ramos_articulos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
					$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
					
					if($_POST["pop"]){
						?>
						<script>
							actualizaSelect("ramo", "lib/consultar_tablas_select.php", "ramos_articulos", <?php echo $idcreado?>);
						</script>
						<?php
					}else{
						mensaje("El Ramo de Materiales se Ingreso con Exito");
						redirecciona("principal.php?modulo=1&accion=720");
					}
				}
			}
			if($_GET["accion"] == 722 and in_array(722, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update ramos_articulos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idramo_articulo = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
				mensaje("El Ramo de Materiales se Actualizo con Exito");
				redirecciona("principal.php?modulo=1&accion=720");
			}
			if($_GET["accion"] == 723 and in_array(723, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Ramo de Materiales (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
						redirecciona("principal.php?modulo=1&accion=720");
					}else{
						registra_transaccion('Eliminar Ramo de Materiales ('.$bus["descripcion"].')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
						mensaje("El Ramo de Materiales se Eliminado con Exito");
						redirecciona("principal.php?modulo=1&accion=720");
					}
				
			}
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		if($_SESSION["modulo"] == 14){
			if($_GET["accion"] == 841 and in_array(841, $privilegios) == true){
				if (mysql_num_rows($busca_existe_registro)>0){
					mensaje("Disculpe ya este Ramo existe");
					redirecciona("principal.php?modulo=14&accion=840");
				}else{	
					
					mysql_query("insert into ramos_articulos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
					$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
					
					if($_POST["pop"]){
						?>
						<script>
							actualizaSelect("ramo", "lib/consultar_tablas_select.php", "ramos_articulos", <?php echo $idcreado?>);
						</script>
						<?php
					}else{
						mensaje("El Ramo de Materiales se Ingreso con Exito");
						redirecciona("principal.php?modulo=14&accion=840");
					}
				}
			}
			if($_GET["accion"] == 842 and in_array(842, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update ramos_articulos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idramo_articulo = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
				mensaje("El Ramo de Materiales se Actualizo con Exito");
				redirecciona("principal.php?modulo=14&accion=840");
			}
			if($_GET["accion"] == 843 and in_array(843, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Ramo de Materiales (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
						redirecciona("principal.php?modulo=14&accion=840");
					}else{
						registra_transaccion('Eliminar Ramo de Materiales ('.$bus["descripcion"].')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
						mensaje("El Ramo de Materiales se Eliminado con Exito");
						redirecciona("principal.php?modulo=14&accion=840");
					}
				
			}
		}
		
		
		
		
		
		
		
		
		
		
		if($_SESSION["modulo"] == 16){
			if($_GET["accion"] == 931 and in_array(931, $privilegios) == true){
				if (mysql_num_rows($busca_existe_registro)>0){
					mensaje("Disculpe ya este Ramo existe");
					redirecciona("principal.php?modulo=16&accion=930");
				}else{	
					
					mysql_query("insert into ramos_articulos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
					$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
					
					if($_POST["pop"]){
						?>
						<script>
							actualizaSelect("ramo", "lib/consultar_tablas_select.php", "ramos_articulos", <?php echo $idcreado?>);
						</script>
						<?php
					}else{
						mensaje("El Ramo de Materiales se Ingreso con Exito");
						redirecciona("principal.php?modulo=16&accion=930");
					}
				}
			}
			if($_GET["accion"] == 932 and in_array(932, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update ramos_articulos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idramo_articulo = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
				mensaje("El Ramo de Materiales se Actualizo con Exito");
				redirecciona("principal.php?modulo=16&accion=930");
			}
			if($_GET["accion"] == 933 and in_array(933, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Ramo de Materiales (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
						redirecciona("principal.php?modulo=16&accion=930");
					}else{
						registra_transaccion('Eliminar Ramo de Materiales ('.$bus["descripcion"].')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
						mensaje("El Ramo de Materiales se Eliminado con Exito");
						redirecciona("principal.php?modulo=16&accion=930");
					}
				
			}
		}
		
		
		
		if($_SESSION["modulo"] == 19){
			if($_GET["accion"] == 1078 and in_array(1078, $privilegios) == true){
				if (mysql_num_rows($busca_existe_registro)>0){
					mensaje("Disculpe ya este Ramo existe");
					redirecciona("principal.php?modulo=19&accion=1067");
				}else{	
					
					mysql_query("insert into ramos_articulos
											(descripcion,usuario,fechayhora,status) 
									values ('$descripcion','$login','$fh','a')"
											,$conexion_db);
					$idcreado = mysql_insert_id();
					registra_transaccion('Ingresar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
					
					if($_POST["pop"]){
						?>
						<script>
							actualizaSelect("ramo", "lib/consultar_tablas_select.php", "ramos_articulos", <?php echo $idcreado?>);
						</script>
						<?php
					}else{
						mensaje("El Ramo de Materiales se Ingreso con Exito");
						redirecciona("principal.php?modulo=19&accion=1067");
					}
				}
			}
			if($_GET["accion"] == 1079 and in_array(1079, $privilegios) == true and !$_POST["buscar"]){
				mysql_query("update ramos_articulos set 
											descripcion='".$descripcion."',
											fechayhora='".$fh."',
											usuario='".$login."'
											where 	idramo_articulo = '$codigo' and status = 'a'",$conexion_db);
				registra_transaccion('Modificar Ramo de Materiales ('.$descripcion.')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
				mensaje("El Ramo de Materiales se Actualizo con Exito");
				redirecciona("principal.php?modulo=19&accion=1067");
			}
			if($_GET["accion"] == 1080 and in_array(1080, $privilegios) == true and !$_POST["buscar"]){
				$sql= mysql_query("select * from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
				$bus = mysql_fetch_array($sql);
				$sql_eliminar = mysql_query("delete from ramos_articulos where idramo_articulo = '$codigo'",$conexion_db);
					if(!$sql_eliminar){
						registra_transaccion('Eliminar Ramo de Materiales (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
						mensaje("Disculpe no se pudo eliminar el registro seleccionado, Probablemente este dato este siendo usado por otra tabla");
						redirecciona("principal.php?modulo=19&accion=1067");
					}else{
						registra_transaccion('Eliminar Ramo de Materiales ('.$bus["descripcion"].')',$login,$fh,$pc,'ramos_articulos',$conexion_db);
						mensaje("El Ramo de Materiales se Eliminado con Exito");
						redirecciona("principal.php?modulo=19&accion=1067");
					}
				
			}
		}
		
	}


?>