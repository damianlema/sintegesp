<?php
if($_POST["ingresar"]){
$_GET["accion"] = 321;
}
if($_POST["ingresar2"]){
$_GET["accion"] = 131;
}
if($_POST["ingresar3"]){
$_GET["accion"] = 561;
}
if($_POST["ingresar4"]){
$_GET["accion"] = 646;
}
if($_POST["ingresar5"]){
$_GET["accion"] = 717;
}

if($_POST["ingresar6"]){
$_GET["accion"] = 837;
}


if($_POST["ingresar7"]){
$_GET["accion"] = 927;
}

if($_POST["ingresar8"]){
$_GET["accion"] = 1045;
}

if($_POST["ingresar9"]){
$_GET["accion"] = 1072;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from unidad_medida 
												where status='a' order by tipo_unidad, descripcion"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from unidad_medida where status='a'";
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

if (($_GET["accion"] == 322 || $_GET["accion"] == 323) || ($_GET["accion"] == 132 || $_GET["accion"] == 133) || ($_GET["accion"] == 562 || $_GET["accion"] == 563) || ($_GET["accion"] == 647 || $_GET["accion"] == 648) || ($_GET["accion"] == 718 || $_GET["accion"] == 719) || ($_GET["accion"] == 838 || $_GET["accion"] == 839) || ($_GET["accion"] == 928 || $_GET["accion"] == 929 || $_GET["accion"] == 1046 || $_GET["accion"] == 1047 || $_GET["accion"] == 1073 || $_GET["accion"] == 1074)){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from unidad_medida 
										where idunidad_medida like '".$_GET['c']."'"
											,$conexion_db);
	$regunidad_medida=mysql_fetch_assoc($sql);
	
}
?>
<script src="js/unidad_medida_ajax.js"></script>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		
		if (document.frmunidad_medida.descripcion.value.length==0){
			alert("Debe escribir una Descripcion para el Tipo de Presupuesto")
			document.frmunidad_medida.descripcion.focus()
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
	<h4 align=center>Unidades de Medida</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<form id="frmunidad_medida" action="principal.php?modulo=<?=$_SESSION["modulo"]?>&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data">	
  <input type="hidden" id="id" name="id" maxlength="9" size="9" <?php echo 'value="'.$regunidad_medida['idunidad_medida'].'"';?>>
  
  	<table align=center cellpadding=2 cellspacing=0 width="40%">
        	<tr><td align="center">
            
            
  			 <?
                if($_SESSION["modulo"] == 2){
					$accion_nuevo = 560;	
				}else if($_SESSION["modulo"] == 3){
					$accion_nuevo = 77;
				}else if($_SESSION["modulo"] == 4){
					$accion_nuevo = 320;
				}else if($_SESSION["modulo"] == 12){
					$accion_nuevo = 645;
				}else if($_SESSION["modulo"] == 14){
					$accion_nuevo = 836;
				}else if($_SESSION["modulo"] == 16){
					$accion_nuevo = 926;
				}else if($_SESSION["modulo"] == 20){
					$accion_nuevo = 1045;
				}else if($_SESSION["modulo"] == 19){
					$accion_nuevo = 1066;
				}
				?>
    &nbsp;<a href='principal.php?modulo=<?=$_SESSION["modulo"]?>&accion=<?=$accion_nuevo?>' class='Browse' onClick="document.getElementById('div_desagrega').style.display='none';"><img src="imagenes/nuevo.png" border="0" title="Nueva Unidad de Medida"></a>
                
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='block';">X</a></div>
          <table id="tableImprimir">
          	<tr>
            	<td>Ordenar Por: </td>
                <td>
                	<select name="ordenarPor" id="ordenarPor">
                    	<option value="descripcion">Descripci&oacute;n</option>
                        <option value="abreviado">Abreviado</option>
                    </select>               
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/compras_servicios/reportes.php?nombre=unidadm&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
                </td>
            </tr>
          </table>
          <iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>          
          </div>
  		</td></tr></table>
		<table align=center cellpadding=2 cellspacing=0 width="40%">
        	<tr>
				<td align='right' class='viewPropTitle'>Descripcion:</td>
				<td class=''><input type="text" name="descripcion" maxlength="255" size="45" id="descripcion" <?php echo 'value="'.$regunidad_medida['descripcion'].'"';?> onKeyUp="validarVacios('descripcion', this.value, 'frmunidad_medida')" onBlur="validarVacios('descripcion', this.value, 'frmunidad_medida')" autocomplete="OFF" style="padding:0px 20px 0px 0px;">
                </td>
			</tr>
            <tr>
				<td align='right' class='viewPropTitle'>Siglas:</td>
				<td class=''><input type="text" name="abreviado" maxlength="3" size="3" id="abreviado" <?php echo 'value="'.$regunidad_medida['abreviado'].'"';?> onKeyUp="validarVacios('abreviado', this.value, 'frmunidad_medida')" onBlur="validarVacios('abreviado', this.value, 'frmunidad_medida')" autocomplete="OFF" style="padding:0px 20px 0px 0px;"></td>
			</tr>
            
             <tr>
				<td align='right' class='viewPropTitle'>Tipo de Unidad:</td>
				<td class=''>
                	<select name="tipo_unidad" id="tipo_unidad">
                    	<option value="Cuenta" <? if ($regunidad_medida['abreviado'] == "Cuenta") echo " selected"; ?>>Cuenta</option>
                        <option value="Longitud" <? if ($regunidad_medida['abreviado'] == "Longitud") echo " selected"; ?>>Longitud</option>
                        <option value="Superficie" <? if ($regunidad_medida['abreviado'] == "Superficie") echo " selected"; ?>>Superficie</option>
                        <option value="Volumen" <? if ($regunidad_medida['abreviado'] == "Volumen") echo " selected"; ?>>Volumen</option>
                        <option value="Capacidad" <? if ($regunidad_medida['abreviado'] == "Capacidad") echo " selected"; ?>>Capacidad</option>
                        <option value="Peso" <? if ($regunidad_medida['abreviado'] == "Peso") echo " selected"; ?>>Peso</option>
                    </select>      
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
					if($_GET["accion"] != 322 and $_GET["accion"] != 323 and in_array(321, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 322 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 323 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				
				if($_SESSION["modulo"] == 3){	
					if($_GET["accion"] != 132 and $_GET["accion"] != 133 and in_array(131, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar2' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 132 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 133 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				
				if($_SESSION["modulo"] == 2){	
					if($_GET["accion"] != 562 and $_GET["accion"] != 563 and in_array(561, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar3' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 562 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 563 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				
				
				if($_SESSION["modulo"] == 12){	
					if($_GET["accion"] != 647 and $_GET["accion"] != 648 and in_array(646, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar4' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 647 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 648 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				if($_SESSION["modulo"] == 1){	
					if($_GET["accion"] != 718 and $_GET["accion"] != 719 and in_array(717, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar5' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 718 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 719 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				
				
				if($_SESSION["modulo"] == 14){	
					if($_GET["accion"] != 838 and $_GET["accion"] != 839 and in_array(837, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar6' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 838 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 839 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				
				
				if($_SESSION["modulo"] == 16){	
					if($_GET["accion"] != 928 and $_GET["accion"] != 929 and in_array(927, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar7' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 928 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 929 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				if($_SESSION["modulo"] == 20){	//almacen
					if($_GET["accion"] != 1046 and $_GET["accion"] != 1047 and in_array(1045, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar8' type='submit' value='Ingresar' onClick='document.getElementById('div_desagrega').style.display='block';'>";
					}
				
					if($_GET["accion"] == 1046 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 1047 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
				
				if($_SESSION["modulo"] == 19){	
					if($_GET["accion"] != 1073 and $_GET["accion"] != 1074 and in_array(1072, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar9' type='submit' value='Ingresar' onClick='document.getElementById('div_desagrega').style.display='block';'>";
					}
				
					if($_GET["accion"] == 1073 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 1074 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				}
			?>
				
			</td></tr>
		</table>
</form>
	<br>
    <? if ($_GET['c']){?>
	 	<div id="div_desagrega" style="display:block">
    <? }else{ ?>
    	<div id="div_desagrega" style="display:none">
    <? } ?>
            <table align=center cellpadding=2 cellspacing=0 width="50%">
            <tr><td colspan="2" align="center" bgcolor="#0099FF"><b>UNIDADES DE DESGLOCE</b></td></tr>
		    <tr>
            	<?php
					$sql_unidades = mysql_query("Select unidad_medida.idunidad_medida,
														unidad_medida.descripcion,
														unidad_medida.abreviado
													from unidad_medida 
														where not exists 
														(select * from desagrega_unidad_medida 
															 where 
														desagrega_unidad_medida.idunidad_medida_desagregada = unidad_medida.idunidad_medida
														and desagrega_unidad_medida.idunidad_medida = '".$regunidad_medida["idunidad_medida"]."')");
					
					
										/*		
												
												select unidad_medida.idunidad_medida,
														unidad_medida.descripcion,
														unidad_medida.abreviado
													from unidad_medida 
													LEFT OUTER JOIN desagrega_unidad_medida
													ON desagrega_unidad_medida.idunidad_medida = unidad_medida.idunidad_medida*/
					
					
					
					?>
            	<td align='right' class='viewPropTitle'>Unidad de Desagregaci&oacute;n</td>
                <td>
                	<select name="unidades_medida" id="unidades_medida">
                    	<? while($bus_select = mysql_fetch_array($sql_unidades)){
							echo "<option value='".$bus_select['idunidad_medida']."'>(".$bus_select['abreviado'].") ".$bus_select['descripcion']."</option>";
							}
						?>
                    </select>
                    <input type="submit" name="boton_asociar" id="boton_asociar" value="Asociar" class="button" onClick="asociarUnidadDesagrega()"/>
        		</td>
        	</tr>
        	</table>
            <br>
            <div id="listaUnidadesDesagregan" style="display:block">
  <table class="Main" cellpadding="0" cellspacing="0" width="40%" align="center">
				<tr>
					<td>
						<form name="grilla" action="lista_cargos.php" method="POST">
		 				 <table class="Browse" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<!--<td class="Browse">&nbsp;</td>-->
									<td width="81%" align="center" class="Browse">Unidad Desagregadora</td>
								  <td align="center" class="Browse" >Acci&oacute;n</td>
								</tr>
							</thead>
							
							<? 
								$sql_consultar = mysql_query("select * from desagrega_unidad_medida, unidad_medida 
															 						where 
																					desagrega_unidad_medida.idunidad_medida = unidad_medida.idunidad_medida
																					and
																					desagrega_unidad_medida.idunidad_medida = '".$_GET["c"]."'
																					order by descripcion ASC");
								while($bus_consultar = mysql_fetch_array($sql_consultar)){
									
									$sql_descripcion = mysql_query("select * from unidad_medida 
															 						where 
																					idunidad_medida = '".$bus_consultar["idunidad_medida_desagregada"]."'");
									$bus_descripcion = mysql_fetch_array($sql_descripcion);
								?>
								<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
									<td align='left' class='Browse'><?=$bus_descripcion["descripcion"]?></td>
                                    <td width="8%" align='center' class='Browse'>
                                  		<img src="imagenes/delete.png" style="cursor:pointer" onClick="eliminarDesagregado('<?=$bus_consultar["iddesagrega_unidad_medida"]?>')">
                                  </td>
                          </tr>
								<?
								}
							?>
						</table>
						</form>
					</td>
				</tr>
  </table>
            
</div>
            
         </div>

<br><br><br><br><br><br><br><br>

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
			<table class="Main" cellpadding="0" cellspacing="0" width="40%">
				<tr>
					<td align="center">
					  <form name="grilla" action="" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
                                	<td align="center" class="Browse" width="20%">Sigla</td>
									<td align="center" class="Browse">Descripci&oacute;n</td>
									<td align="center" class="Browse" colspan="2"><font size="-1">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
							if ($existen_registros==0){
								$tipo_unidad='';
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
									{ ?>
						  			<tr>
								<?php
									if ($llenar_grilla["tipo_unidad"] <> $tipo_unidad){
										echo "<td align='left' colspan='4' bgcolor='#0099CC'><b>&nbsp;".$llenar_grilla["tipo_unidad"]."</b></td>";
										echo "</tr>";
										$tipo_unidad = $llenar_grilla["tipo_unidad"];
										?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                        <?
										echo "<td align='center' class='Browse'>&nbsp;".$llenar_grilla["abreviado"]."</font></td>";
										echo "<td align='left' class='Browse'>&nbsp;".$llenar_grilla["descripcion"]."</font></td>";
										$c=$llenar_grilla["idunidad_medida"];
									
										if($_SESSION["modulo"] == 4){
											if(in_array(322,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=322&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(323, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=323&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
									
										if($_SESSION["modulo"] == 3){
											if(in_array(132,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=132&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(133, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=133&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
									
									
									
										if($_SESSION["modulo"] == 2){
											if(in_array(562,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=562&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(563, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=563&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
									
									
									
										if($_SESSION["modulo"] == 12){
											if(in_array(647,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=647&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(648, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=648&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
									
										if($_SESSION["modulo"] == 1){
											if(in_array(718,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=1&accion=718&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(719, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=1&accion=719&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
									
									
									
										if($_SESSION["modulo"] == 14){
											if(in_array(838,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=838&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(839, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=839&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
									
								
										if($_SESSION["modulo"] == 16){
											if(in_array(928,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=928&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(929, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=929&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
										
										if($_SESSION["modulo"] == 20){
											if(in_array(1046,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=20&accion=1046&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(1047, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=20&accion=1047&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
										
										if($_SESSION["modulo"] == 19){
											if(in_array(1073,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1073&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(1074, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1074&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
										
										echo "</tr>";
									}else{ ?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                        <?
										echo "<td align='center' class='Browse'>&nbsp;".$llenar_grilla["abreviado"]."</font></td>";
										echo "<td align='left' class='Browse'>&nbsp;".$llenar_grilla["descripcion"]."</font></td>";
										$c=$llenar_grilla["idunidad_medida"];
									
										if($_SESSION["modulo"] == 4){
											if(in_array(322,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=322&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(323, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=323&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
									
										if($_SESSION["modulo"] == 3){
											if(in_array(132,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=132&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(133, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=133&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
									
									
									
										if($_SESSION["modulo"] == 2){
											if(in_array(562,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=562&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(563, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=563&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
									
									
									
										if($_SESSION["modulo"] == 12){
											if(in_array(647,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=647&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(648, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=12&accion=648&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
									
										if($_SESSION["modulo"] == 1){
											if(in_array(718,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=1&accion=718&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(719, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=1&accion=719&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
									
									
									
										if($_SESSION["modulo"] == 14){
											if(in_array(838,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=838&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(839, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=14&accion=839&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
									
								
										if($_SESSION["modulo"] == 16){
											if(in_array(928,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=928&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(929, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=16&accion=929&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
										
										if($_SESSION["modulo"] == 20){
											if(in_array(1046,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=20&accion=1046&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(1047, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=20&accion=1047&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
										
										if($_SESSION["modulo"] == 19){
											if(in_array(1073,$privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1073&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
											}
											if(in_array(1074, $privilegios) == true){
												echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=19&accion=1074&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
											}
										}
										echo "</tr>";
									}
									
									
									}
							}?>
						</table>
                        
					  </form>
					</td>
				</tr>
			</table>
</div>

</body>
</html>

<?php
if($_POST){
	$id=$_POST["id"];
	$descripcion=strtoupper($_POST["descripcion"]);
	$abreviado = $_POST["abreviado"];
	$tipo_unidad = $_POST["tipo_unidad"];
	$busca_existe_registro=mysql_query("select * from unidad_medida where descripcion = '".$_POST['descripcion']."'  and status='a'",$conexion_db);
	
	if($_SESSION["modulo"] == 4){
		if($_GET["accion"] == 321 and in_array(321, $privilegios) == true) {
	
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe el Registro que ingreso ya existe, vuelva a intentarlo");
				redirecciona("principal.php?modulo=4&accion=320");
			}else{
		
				mysql_query("insert into unidad_medida
										(descripcion,usuario,fechayhora,status, abreviado, tipo_unidad) 
								values ('$descripcion','$login','$fh','a','$abreviado', '$tipo_unidad')"
										,$conexion_db);
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
				
				if($_POST["pop"]){
					?>
					<script>
						actualizaSelect("unidad_medida", "lib/consultar_tablas_select.php", "unidad_medida", <?php echo $idcreado?>);
					</script>
					<?php
				}else{
						mensaje("El regsitro se Ingreso con Exito");
						redirecciona("principal.php?modulo=4&accion=320");
				}
			}
		}
		if ($_GET["accion"] == 322 and in_array(322, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update unidad_medida set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										abreviado = '".$abreviado."',
										tipo_unidad = '".$tipo_unidad."'
										where 	idunidad_medida = '$id' and status = 'a'",$conexion_db)or die(mysql_error());
			registra_transaccion('Modificar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
			mensaje("El registro se Modifico con Exito");
			redirecciona("principal.php?modulo=4&accion=320");

		}
		if ($_GET["accion"] == 323 and in_array(323,$privilegios)== true and !$_POST["buscar"]){
			$sql = mysql_query("select * from unidad_medida where idunidad_medida = '$id'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from unidad_medida where idunidad_medida = '$id'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Unidad de Medida (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				redirecciona("principal.php?modulo=4&accion=320");

			}else{
				registra_transaccion('Eliminar Unidad de Medida ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("El regsitro se Elimino con Exito");
				redirecciona("principal.php?modulo=4&accion=320");
			}				
		}
	}
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 3){
	if($_GET["accion"] == 131 and in_array(131, $privilegios) == true) {
	
	if (mysql_num_rows($busca_existe_registro)>0){
		mensaje("Disculpe el Registro que ingreso ya existe, vuelva a intentarlo");
		redirecciona("principal.php?modulo=3&accion=77");
	}else{
		
			mysql_query("insert into unidad_medida
									(descripcion,usuario,fechayhora,status, abreviado, tipo_unidad) 
							values ('$descripcion','$login','$fh','a','$abreviado','$tipo_unidad')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("unidad_medida", "lib/consultar_tablas_select.php", "unidad_medida", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					mensaje("El registro se Ingreso con Exito");
					redirecciona("principal.php?modulo=3&accion=77");
			}
			
			

		}
	}
	if ($_GET["accion"] == 132 and in_array(132, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update unidad_medida set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										abreviado = '".$abreviado."',
										tipo_unidad = '".$tipo_unidad."'
										where 	idunidad_medida = '$id' and status = 'a'",$conexion_db)or die(mysql_error());
			registra_transaccion('Modificar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
			mensaje("El regsitro se Modifico con Exito");
			redirecciona("principal.php?modulo=3&accion=77");

	}
	if ($_GET["accion"] == 133 and in_array(133,$privilegios)== true and !$_POST["buscar"]){
			$sql = mysql_query("select * from unidad_medida where idunidad_medida = '$id'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from unidad_medida where idunidad_medida = '$id'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Unidad de Medida (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				redirecciona("principal.php?modulo=3&accion=77");

			}else{
				registra_transaccion('Eliminar Unidad de Medida ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("El regsitro se Elimino con Exito");
				redirecciona("principal.php?modulo=3&accion=77");
			}				
			

	}
	}
	
		
	if($_SESSION["modulo"] == 2){
	if($_GET["accion"] == 561 and in_array(561, $privilegios) == true) {
	
	if (mysql_num_rows($busca_existe_registro)>0){
		mensaje("Disculpe el Registro que ingreso ya existe, vuelva a intentarlo");
		redirecciona("principal.php?modulo=2&accion=560");
	}else{
		
			mysql_query("insert into unidad_medida
									(descripcion,usuario,fechayhora,status, abreviado, tipo_unidad) 
							values ('$descripcion','$login','$fh','a','$abreviado','$tipo_unidad')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("unidad_medida", "lib/consultar_tablas_select.php", "unidad_medida", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					mensaje("El regsitro se Ingreso con Exito");
					redirecciona("principal.php?modulo=2&accion=560");
			}
			
			

		}
	}
	if ($_GET["accion"] == 562 and in_array(562, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update unidad_medida set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										abreviado = '".$abreviado."',
										tipo_unidad = '".$tipo_unidad."'
										where 	idunidad_medida = '$id' and status = 'a'",$conexion_db)or die(mysql_error());
			registra_transaccion('Modificar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
			mensaje("El regsitro se Modifico con Exito");
			redirecciona("principal.php?modulo=2&accion=560");

	}
	if ($_GET["accion"] == 563 and in_array(563,$privilegios)== true and !$_POST["buscar"]){
			$sql = mysql_query("select * from unidad_medida where idunidad_medida = '$id'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from unidad_medida where idunidad_medida = '$id'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Unidad de Medida (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				redirecciona("principal.php?modulo=2&accion=560");

			}else{
				registra_transaccion('Eliminar Unidad de Medida ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("El regsitro se Elimino con Exito");
				redirecciona("principal.php?modulo=2&accion=560");
			}				
			

	}
	}
	
	
	if($_SESSION["modulo"] == 12){
	if($_GET["accion"] == 646 and in_array(646, $privilegios) == true) {
	
	if (mysql_num_rows($busca_existe_registro)>0){
		mensaje("Disculpe el Registro que ingreso ya existe, vuelva a intentarlo");
		redirecciona("principal.php?modulo=12&accion=645");
	}else{
		
			mysql_query("insert into unidad_medida
									(descripcion,usuario,fechayhora,status, abreviado, tipo_unidad) 
							values ('$descripcion','$login','$fh','a','$abreviado', '$tipo_unidad')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("unidad_medida", "lib/consultar_tablas_select.php", "unidad_medida", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
					mensaje("El regsitro se Ingreso con Exito");
					redirecciona("principal.php?modulo=12&accion=645");
			}
			
			

		}
	}
	
	
	if ($_GET["accion"] == 647 and in_array(647, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update unidad_medida set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										abreviado = '".$abreviado."',
										tipo_unidad = '".$tipo_unidad."'
										where 	idunidad_medida = '$id' and status = 'a'",$conexion_db)or die(mysql_error());
			registra_transaccion('Modificar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
			mensaje("El regsitro se Modifico con Exito");
			redirecciona("principal.php?modulo=12&accion=645");

	}
	
	
	if ($_GET["accion"] == 648 and in_array(648,$privilegios)== true and !$_POST["buscar"]){
			$sql = mysql_query("select * from unidad_medida where idunidad_medida = '$id'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from unidad_medida where idunidad_medida = '$id'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Unidad de Medida (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				redirecciona("principal.php?modulo=12&accion=645");

			}else{
				registra_transaccion('Eliminar Unidad de Medida ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("El regsitro se Elimino con Exito");
				redirecciona("principal.php?modulo=12&accion=645");
			}				
			

	}
	}
	
	
	
	if($_SESSION["modulo"] == 1){
		if($_GET["accion"] == 717 and in_array(717, $privilegios) == true) {
	
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe el Registro que ingreso ya existe, vuelva a intentarlo");
				redirecciona("principal.php?modulo=1&accion=716");
			}else{
		
				mysql_query("insert into unidad_medida
										(descripcion,usuario,fechayhora,status, abreviado, tipo_unidad) 
								values ('$descripcion','$login','$fh','a','$abreviado', '$tipo_unidad')"
										,$conexion_db);
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
				
				if($_POST["pop"]){
					?>
					<script>
						actualizaSelect("unidad_medida", "lib/consultar_tablas_select.php", "unidad_medida", <?php echo $idcreado?>);
					</script>
					<?php
				}else{
						mensaje("El regsitro se Ingreso con Exito");
						redirecciona("principal.php?modulo=1&accion=716");
				}
			}
		}
	
		if ($_GET["accion"] == 718 and in_array(718, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update unidad_medida set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										abreviado = '".$abreviado."',
										tipo_unidad = '".$tipo_unidad."'
										where 	idunidad_medida = '$id' and status = 'a'",$conexion_db)or die(mysql_error());
			registra_transaccion('Modificar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
			mensaje("El regsitro se Modifico con Exito");
			redirecciona("principal.php?modulo=1&accion=716");

		}
	
	
		if ($_GET["accion"] == 719 and in_array(719,$privilegios)== true and !$_POST["buscar"]){
			$sql = mysql_query("select * from unidad_medida where idunidad_medida = '$id'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from unidad_medida where idunidad_medida = '$id'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Unidad de Medida (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				redirecciona("principal.php?modulo=1&accion=716");

			}else{
				registra_transaccion('Eliminar Unidad de Medida ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("El regsitro se Elimino con Exito");
				redirecciona("principal.php?modulo=1&accion=716");
			}				
		}
	}




if($_SESSION["modulo"] == 14){
		if($_GET["accion"] == 837 and in_array(837, $privilegios) == true) {
	
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe el Registro que ingreso ya existe, vuelva a intentarlo");
				redirecciona("principal.php?modulo=14&accion=836");
			}else{
		
				mysql_query("insert into unidad_medida
										(descripcion,usuario,fechayhora,status, abreviado, tipo_unidad) 
								values ('$descripcion','$login','$fh','a','$abreviado', '$tipo_unidad')"
										,$conexion_db);
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
				
				if($_POST["pop"]){
					?>
					<script>
						actualizaSelect("unidad_medida", "lib/consultar_tablas_select.php", "unidad_medida", <?php echo $idcreado?>);
					</script>
					<?php
				}else{
						mensaje("El regsitro se Ingreso con Exito");
						redirecciona("principal.php?modulo=14&accion=836");
				}
			}
		}
	
		if ($_GET["accion"] == 838 and in_array(838, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update unidad_medida set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										abreviado = '".$abreviado."',
										tipo_unidad = '".$tipo_unidad."'
										where 	idunidad_medida = '$id' and status = 'a'",$conexion_db)or die(mysql_error());
			registra_transaccion('Modificar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
			mensaje("El regsitro se Modifico con Exito");
			redirecciona("principal.php?modulo=14&accion=836");

		}
	
	
		if ($_GET["accion"] == 839 and in_array(839,$privilegios)== true and !$_POST["buscar"]){
			$sql = mysql_query("select * from unidad_medida where idunidad_medida = '$id'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from unidad_medida where idunidad_medida = '$id'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Unidad de Medida (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				redirecciona("principal.php?modulo=14&accion=836");

			}else{
				registra_transaccion('Eliminar Unidad de Medida ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("El regsitro se Elimino con Exito");
				redirecciona("principal.php?modulo=14&accion=836");
			}				
		}
	}
	
	
	
	
	
	
	
	
	if($_SESSION["modulo"] == 16){
		if($_GET["accion"] == 927 and in_array(927, $privilegios) == true) {
	
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe el Registro que ingreso ya existe, vuelva a intentarlo");
				redirecciona("principal.php?modulo=16&accion=926");
			}else{
		
				mysql_query("insert into unidad_medida
										(descripcion,usuario,fechayhora,status, abreviado, tipo_unidad) 
								values ('$descripcion','$login','$fh','a','$abreviado', '$tipo_unidad')"
										,$conexion_db);
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
				
				if($_POST["pop"]){
					?>
					<script>
						actualizaSelect("unidad_medida", "lib/consultar_tablas_select.php", "unidad_medida", <?php echo $idcreado?>);
					</script>
					<?php
				}else{
						mensaje("El regsitro se Ingreso con Exito");
						redirecciona("principal.php?modulo=16&accion=926");
				}
			}
		}
	
		if ($_GET["accion"] == 928 and in_array(928, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update unidad_medida set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										abreviado = '".$abreviado."',
										tipo_unidad = '".$tipo_unidad."'
										where 	idunidad_medida = '$id' and status = 'a'",$conexion_db)or die(mysql_error());
			registra_transaccion('Modificar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
			mensaje("El regsitro se Modifico con Exito");
			redirecciona("principal.php?modulo=16&accion=926");

		}
	
	
		if ($_GET["accion"] == 929 and in_array(929,$privilegios)== true and !$_POST["buscar"]){
			$sql = mysql_query("select * from unidad_medida where idunidad_medida = '$id'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from unidad_medida where idunidad_medida = '$id'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Unidad de Medida (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				redirecciona("principal.php?modulo=16&accion=926");

			}else{
				registra_transaccion('Eliminar Unidad de Medida ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("El regsitro se Elimino con Exito");
				redirecciona("principal.php?modulo=16&accion=926");
			}				
		}
	}

	
	if($_SESSION["modulo"] == 20){
		if($_GET["accion"] == 1045 and in_array(1045, $privilegios) == true) {
	
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe el Registro que ingreso ya existe, vuelva a intentarlo");
				redirecciona("principal.php?modulo=20&accion=1045");
			}else{
		
				mysql_query("insert into unidad_medida
										(descripcion,usuario,fechayhora,status, abreviado, tipo_unidad) 
								values ('$descripcion','$login','$fh','a','$abreviado', '$tipo_unidad')"
										,$conexion_db);
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
				
				if($_POST["pop"]){
					?>
					<script>
						actualizaSelect("unidad_medida", "lib/consultar_tablas_select.php", "unidad_medida", <?php echo $idcreado?>);
					</script>
					<?php
				}else{
						mensaje("El regsitro se Ingreso con Exito");
						redirecciona("principal.php?modulo=20&accion=1045");
				}
			}
		}
	
		if ($_GET["accion"] == 1046 and in_array(1046, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update unidad_medida set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										abreviado = '".$abreviado."',
										tipo_unidad = '".$tipo_unidad."'
										where 	idunidad_medida = '$id' and status = 'a'",$conexion_db)or die(mysql_error());
			registra_transaccion('Modificar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
			mensaje("El regsitro se Modifico con Exito");
			redirecciona("principal.php?modulo=20&accion=1045");

		}
	
	
		if ($_GET["accion"] == 1047 and in_array(1047,$privilegios)== true and !$_POST["buscar"]){
			$sql = mysql_query("select * from unidad_medida where idunidad_medida = '$id'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from unidad_medida where idunidad_medida = '$id'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Unidad de Medida (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				redirecciona("principal.php?modulo=20&accion=926");

			}else{
				registra_transaccion('Eliminar Unidad de Medida ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("El regsitro se Elimino con Exito");
				redirecciona("principal.php?modulo=20&accion=1045");
			}				
		}
	}

	
	
	if($_SESSION["modulo"] == 19){
		if($_GET["accion"] == 1072 and in_array(1072, $privilegios) == true) {
	
			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe el Registro que ingreso ya existe, vuelva a intentarlo");
				redirecciona("principal.php?modulo=19&accion=1066");
			}else{
		
				mysql_query("insert into unidad_medida
										(descripcion,usuario,fechayhora,status, abreviado, tipo_unidad) 
								values ('$descripcion','$login','$fh','a','$abreviado', '$tipo_unidad')"
										,$conexion_db);
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
				
				if($_POST["pop"]){
					?>
					<script>
						actualizaSelect("unidad_medida", "lib/consultar_tablas_select.php", "unidad_medida", <?php echo $idcreado?>);
					</script>
					<?php
				}else{
						mensaje("El regsitro se Ingreso con Exito");
						redirecciona("principal.php?modulo=19&accion=1066");
				}
			}
		}
	
		if ($_GET["accion"] == 1073 and in_array(1073, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update unidad_medida set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										abreviado = '".$abreviado."',
										tipo_unidad = '".$tipo_unidad."'
										where 	idunidad_medida = '$id' and status = 'a'",$conexion_db)or die(mysql_error());
			registra_transaccion('Modificar Unidad de Medida ('.$descripcion.')',$login,$fh,$pc,'unidad_medida',$conexion_db);
			mensaje("El regsitro se Modifico con Exito");
			redirecciona("principal.php?modulo=19&accion=1066");

		}
	
	
		if ($_GET["accion"] == 1074 and in_array(1074,$privilegios)== true and !$_POST["buscar"]){
			$sql = mysql_query("select * from unidad_medida where idunidad_medida = '$id'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from unidad_medida where idunidad_medida = '$id'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Unidad de Medida (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				redirecciona("principal.php?modulo=19&accion=1066");

			}else{
				registra_transaccion('Eliminar Unidad de Medida ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("El regsitro se Elimino con Exito");
				redirecciona("principal.php?modulo=19&accion=1066");
			}				
		}
	}
	
	
	
	
}
?>