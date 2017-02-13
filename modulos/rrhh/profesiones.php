<?php
if($_POST["ingresar"]){
$_GET["accion"] = 191;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from profesion 
												where status='a'"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from profesion where status='a'";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			$registros_grilla=mysql_query($sql." and denominacion like '$texto_buscar%'",$conexion_db);
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}

if ($_GET["accion"] == 192 || $_GET["accion"] == 193){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from profesion 
										where idprofesion like '".$_GET['c']."'"
											,$conexion_db);
	$registro_actualizar=mysql_fetch_assoc($sql);
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		if (document.frmprofesion.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para el Nivel de Estudio.")
			document.frmprofesion.denominacion.focus()
			return false;
		}	
	} 
// end hiding from old browsers -->
</SCRIPT>

	<body>
	<br>
	<h4 align=center>Profesiones</h4>
	<h2 class="sqlmVersion"></h2>
		<form name="frmprofesion" action="principal.php?modulo=1&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
	<table align=center cellpadding=2 cellspacing=0>
	<?php
		if ($_GET["accion"] == 191 || $_GET["accion"] == 14){ //entro en modo para agregar
		?>
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
			  <td class='viewProp'><input type="text" name="denominacion" maxlength="30" size="30">
              &nbsp;<a href="principal.php?modulo=1&accion=14"><img src="imagenes/nuevo.png" border="0" title="Nuevas Profesiones"></a>
              
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='block';">X</a></div>
          <table id="tableImprimir">
          	<tr>
            	<td>Ordenar Por: </td>
                <td>
                	<select name="ordenarPor" id="ordenarPor">
                    	<option value="denominacion">Denominaci&oacute;n</option>
                        <option value="abreviatura">Abreviatura</option>
                    </select>                   
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=profesion&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
                </td>
            </tr>
          </table>
          <iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>          
          </div>
              
              </td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Abreviatura:</td>
				<td class='viewProp'><input type="text" name="abreviatura" maxlength="5" size="5"></td>
			</tr>
		<?php
		}
		if ($_GET["accion"] == 192 || $_GET["accion"] == 193){ //entro en modo para modificar o eliminar
		?>
			<input type="hidden" name="tabla" value="4">  <?// campo oculto para enviar por el POST el numero de tabla a modificar?>
			<input type="hidden" name="user" value="<?php echo $registro_usuario["login"];    // envia el usuario que esta haciendo la modificacion?>">
			<input type="hidden" name="id_profesion" value="<?php echo $registro_actualizar["idprofesion"]; //envia el id del banco a modificar ?>">
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" value="<?php echo $registro_actualizar["denominacion"];?>" maxlength="30" size="30" <?php if ($_GET["accion"] == 193) echo"disabled"?>>
                &nbsp;<a href="principal.php?modulo=1&accion=14"><img src="imagenes/nuevo.png" border="0" title="Nuevas Profesiones"></a>
                
                
                           
                
                </td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Abreviatura:</td>
				<td class='viewProp'><input type="text" name="abreviatura" value="<?php echo $registro_actualizar["abreviatura"];?>" maxlength="5" size="5" <?php if ($_GET["accion"] == 193) echo"disabled"?>></td>
			</tr>
		<?php
		}
	?>	
	</table>
	</table>
	<table align=center cellpadding=2 cellspacing=0>
		<tr><td>
<?php
    if($_REQUEST["pop"]){
	echo "<input type='hidden' value='true' name='pop' id='pop'>";
	}
					if($_GET["accion"] != 192 and $_GET["accion"] != 193 and in_array(191, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 192 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 193 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
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
			<td class='viewProp'><input type="text" name="textoabuscar" maxlength="30" size="30"></td>
			<td>
				<input align=center class="button" name="buscar" type="submit" value="Buscar">
				</a>
			</td>
		</tr>
	</table>
	</form>
	
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<form name="grilla" action="" method="POST">
						<table class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
							<thead>
								<tr>
									<td align="center" class="Browse">Denominaci&oacute;n</td>
									<td align="center" class="Browse" width='15%'>Abreviatura</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
									{ ?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
									echo "<td align='left' class='Browse'>".$llenar_grilla["denominacion"]."</td>";
									echo "<td align='left' class='Browse' width='15%'>".$llenar_grilla["abreviatura"]."</td>";
									$c=$llenar_grilla["idprofesion"];
									if(in_array(192, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=192&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(193, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=193&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmprofesion.denominacion.focus() </script>
</body>
</html>

<?php
if($_POST){

		$denominacion=strtoupper($_POST["denominacion"]);
		$abreviatura=$_POST["abreviatura"];
		$codigo_profesion=$_POST["id_profesion"];		
if($_GET["accion"] == 191 and in_array(191, $privilegios) == true){
		$busca_existe_registro=mysql_query("select * from profesion 
															where denominacion like '".$denominacion."'  
															and status='a'"
																	,$conexion_db);
		
		if (mysql_num_rows($busca_existe_registro)>0){
			mensaje("Disculpe el regsitro que ingreso ya existe, vuelva a intentarlo");
			redirecciona("principal.php?modulo=1&accion=14");
		}else{
			mysql_query("insert into profesion 
										(denominacion,abreviatura,usuario,fechayhora,status) 
									values ('$denominacion','$abreviatura','$login','$fh','a')"
										,$conexion_db);
			$idcreado = mysql_insert_id();							
			registra_transaccion('Ingresar Profesiones ('.$denominacion.')',$login,$fh,$pc,'profesion',$conexion_db);
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idprofesion", "lib/consultar_tablas_select.php", "profesion", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				mensaje("El Registro se Ingreso con Exito");
			redirecciona("principal.php?modulo=1&accion=14");
			}
			
			

		}
	}	
	if($_GET["accion"] == 192 and in_array(192, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update profesion set 	denominacion='".strtoupper($denominacion)."', 
											abreviatura='".$abreviatura."',
											usuario='".$login."', 
											fechayhora='".$fh."' 
												where idprofesion like '$codigo_profesion'"
													,$conexion_db);	
			registra_transaccion('Modificar Profesiones ('.$denominacion.')',$login,$fh,$pc,'profesion',$conexion_db);
			mensaje("El registro se Modifico con Exito");
			redirecciona("principal.php?modulo=1&accion=14");
	}
	if($_GET["accion"] == 193 and in_array(193, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from profesion where idprofesion = '$codigo_profesion'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from profesion where idprofesion = '$codigo_profesion'"
													,$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Profesiones (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'profesion',$conexion_db);
				mensaje("Disculpe el regsitro que intenta eliminar esta relacionado con otro registro dentro del sistema, por ello no puede ser eliminado");
				redirecciona("principal.php?modulo=1&accion=14");
			
			}else{
				registra_transaccion('Eliminar Profesiones ('.$bus["denominacion"].')',$login,$fh,$pc,'profesion',$conexion_db);
				mensaje("El registro se Elimino con Exito");
				redirecciona("principal.php?modulo=1&accion=14");
			
			}

	}
	
}
?>
