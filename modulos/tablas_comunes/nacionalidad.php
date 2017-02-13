<?php
if($_POST["ingresar"]){
$_GET["accion"] = 215;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from nacionalidad 
												where status='a'"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from nacionalidad where status='a'";
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

if ($_GET["accion"] == 216 || $_GET["accion"] == 217){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from nacionalidad 
										where idnacionalidad like '".$_GET['c']."'"
											,$conexion_db);
	$registro_actualizar=mysql_fetch_assoc($sql);
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		if (document.frmnacionalidad.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para la Nacionalidad.")
			document.frmnacionalidad.denominacion.focus()
			return false;
		}	
	} 
// end hiding from old browsers -->
</SCRIPT>
	<body>
	<br>
	<h4 align=center>Nacionalidad</h4>
	<h2 class="sqlmVersion"></h2>

		<form name="frmnacionalidad" action="principal.php?modulo=9&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
	
	<table align=center cellpadding=2 cellspacing=0>
	<?php
		if ($_GET["accion"] == 215 || $_GET["accion"] == 60){ //entro en modo para agregar
		?>
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" maxlength="30" size="30">
                &nbsp;<a href="principal.php?modulo=9&accion=60"><img src="imagenes/nuevo.png" border="0" title="Nueva Nacionalidad"></a>
                </td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Marca:</td>
				<td class='viewProp'><input type="text" name="indicador" maxlength="1" size="1"></td>
			</tr>
		<?php
		}
		if ($_GET["accion"] == 216 || $_GET["accion"] == 217){ //entro en modo para modificar o eliminar
		?>
			<input type="hidden" name="tabla" value="7">  <?// campo oculto para enviar por el POST el numero de tabla a modificar?>
			<input type="hidden" name="user" value="<?php echo $registro_usuario["login"];    // envia el usuario que esta haciendo la modificacion?>">
			<input type="hidden" name="id_nacionalidad" value="<?php echo $registro_actualizar["idnacionalidad"]; //envia el id del parentezco a modificar ?>">
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" value="<?php echo $registro_actualizar["denominacion"];?>" maxlength="30" size="30" <?php if ($_GET["accion"] == 217) echo"disabled"?>>
                
                &nbsp;<a href="principal.php?modulo=9&accion=60"><img src="imagenes/nuevo.png" border="0" title="Nueva Nacionalidad"></a>
                </td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Marca:</td>
				<td class='viewProp'><input type="text" name="denominacion" value="<?php echo $registro_actualizar["indicador"];?>" maxlength="1" size="1" <?php if ($_GET["accion"] == 217) echo"disabled"?>></td>
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
					if($_GET["accion"] != 216 and $_GET["accion"] != 217 and in_array(215, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 216 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 217 and in_array($_GET["accion"], $privilegios) == true){
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
						<table class="Browse" cellpadding="0" cellspacing="0" width="30%" align="center">
							<thead>
								<tr>
									<td align="center" class="Browse">Denominaci&oacute;n</td>
									<td align="center" class="Browse" width="20%">Marca</td>
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
									echo "<td align='center' class='Browse'>".$llenar_grilla["indicador"]."</td>";
									$c=$llenar_grilla["idnacionalidad"];
									if(in_array(216, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=9&accion=216&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(217, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=9&accion=217&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmnacionalidad.idgrupo.focus() </script>
</body>
</html>

<?php
if($_POST){
		$denominacion=strtoupper($_POST["denominacion"]);
		$indicador=strtoupper($_POST["indicador"]);
		$idnacionalidad=$_POST["id_nacionalidad"];	
	if($_GET["accion"] == 215 and in_array(215, $privilegios) == true){
		$busca_existe_registro=mysql_query("select * from nacionalidad 
															where denominacion like '".$denominacion."'  
															and status='a'"
																	,$conexion_db);
		
		if (mysql_num_rows($busca_existe_registro)>0){
			mensaje("Disculpe el registro que ingreso ya existe, Vuelvalo a Intentar");
			redirecciona("principal.php?modulo=9&accion=60");
		}else{
			mysql_query("insert into nacionalidad 
										(denominacion,usuario,fechayhora,status,indicador) 
									values ('$denominacion','$login','$fh','a','$indicador')"
										,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Nacionalidad ('.$denominacion.')',$login,$fh,$pc,'pais',$conexion_db);
			
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("nacionalidad", "lib/consultar_tablas_select.php", "nacionalidad", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				mensaje("El registro se Inserto con Exito");
				redirecciona("principal.php?modulo=9&accion=60");
			}
			

		}
	}
	
	if($_GET["accion"] == 216 and in_array(216, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update nacionalidad set 	denominacion='".strtoupper($denominacion)."',
													indicador='".strtoupper($indicador)."',
													usuario='".$login."', 
													fechayhora='".$fh."' 
														where idnacionalidad like '$idnacionalidad' 
															and status='a'"
																,$conexion_db);
			registra_transaccion('Modificar Nacionalidad ('.$denominacion.')',$login,$fh,$pc,'nacionalidad',$conexion_db);
			mensaje("El registro se Modifico con Exito");
			redirecciona("principal.php?modulo=9&accion=60");
	}
	if($_GET["accion"] == 217 and in_array(217, $privilegios) == true and !$_POST["buscar"]){ 
			$sql = mysql_query("select * from nacionalidad where idnacionalidad = '$idnacionalidad'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from nacionalidad where idnacionalidad = '$idnacionalidad'"
															,$conexion_db);
			if(!$sql_eliminar){
			registra_transaccion('Eliminar Nacionalidad (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'nacionalidad',$conexion_db);
			mensaje("Disculpe le registro que intenta eliminar esta relacionado con otro registro dentro del sistema, por ello no puede ser eliminado");
			redirecciona("principal.php?modulo=9&accion=60");
			
			}else{
			registra_transaccion('Eliminar Nacionalidad ('.$bus["denominacion"].')',$login,$fh,$pc,'nacionalidad',$conexion_db);
			mensaje("El registro se Elimino con Exito");
			redirecciona("principal.php?modulo=9&accion=60");
			
			}
	}
}
?>
