<?php
if($_POST["ingresar"]){
$_GET["accion"] = 206;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from dependencias 
												where status='a'"
													,$conexion_db); // carga los registros de la tabla dependencias que esten activos: a activos e eliminados
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}
	
// FILTRA LOS DATOS SEGUN LOS PARAMETROS ESTABLECIDOS EN EL CAMPO BUSCAR
if (isset($_POST["buscar"])){ // si se hizo clic en el boton buscar
	$texto_buscar=$_POST["textoabuscar"];
	$campo_busqueda=$_POST["tipobusqueda"];
	$sql="select * from dependencias where status='a'";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			if ($campo_buscar=="c"){
				$registros_grilla=mysql_query($sql."and iddependencia like '$texto_buscar%'",$conexion_db);
			}
			if ($campo_buscar=="d"){
				$registros_grilla=mysql_query($sql."and denominacion like '$texto_buscar%'",$conexion_db);
			}
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
	{
		$existen_registros=1;
	}
}
// BUSCA EL REGISTRO A MODIFICAR O ELIMINAR
if ($_GET["accion"] == 207 || $_GET["accion"] == 208){
	$sql=mysql_query("select * from dependencias 
									where iddependencia like '".$_GET['c']."'"
										,$conexion_db);
	$registro_actualizar=mysql_fetch_assoc($sql);
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos

function valida_envia(){
    
	//valido el codigo
    if (document.frmdependencia.iddependencia.selectedIndex==0){
       alert("Debe colocar un codigo para la dependencia.")
       document.frmdependencia.iddependencia.focus()
       return false;
    }
	
	//valido la denominacion
	if (document.frmdependencia.denominacion.value.length==0){
		alert("Tiene que escribir una Denominacion para la dependencia")
		document.frmdependencia.denominacion.focus()
		return false;
	}

} 
</SCRIPT>
	<body>
	<br>
	<h4 align=center>Dependencias</h4>
	<h2 class="sqlmVersion"></h2>

		<form name="frmdependencia" action="principal.php?modulo=1&accion=<?=$_GET["accion"]?>" method="POST" onSubmit="return valida_envia()">	
	<table align=center cellpadding=2 cellspacing=0>
	<?php
		if ($_GET["accion"] == 61 || $_GET["accion"] == 206){ //entro en modo para agregar
		?>
			<tr><td align='right' class='viewPropTitle'>C&oacute;digo :</td>
				<td class='viewProp'><input type="text" name="iddependencia" maxlength="5" size="5">
                &nbsp;<a href="principal.php?modulo=9&accion=61"><img src="imagenes/nuevo.png" border="0" title="Nuevas Dependencias"></a>
                </td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" maxlength="80" size="50"></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Siglas:</td>
				<td class='viewProp'><input type="text" name="siglas" maxlength="10" size="10"></td>
			</tr>
		<?php
		}
		if ($_GET["accion"] == 207 || $_GET["accion"] == 208){ //entro en modo para modificar o eliminar
		?>
			<input type="hidden" name="tabla" value="6">
			<input type="hidden" name="user" value="<?php echo $registro_usuario["login"];?>">
			<input type="hidden" name="id_dependencia" value="<?php echo $registro_actualizar["iddependencia"];?>">
			<tr><td align='right' class='viewPropTitle'>C&oacute;digo:</td>
				<td class='viewProp'><input type="text" name="iddependencia" value="<?php echo $registro_actualizar["iddependencia"];?>" maxlength="5" size="5" disabled>
                 &nbsp;<a href="principal.php?modulo=9&accion=61"><img src="imagenes/nuevo.png" border="0" title="Nuevas Dependencias"></a>
                </td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" value="<?php echo $registro_actualizar["denominacion"];?>" maxlength="80" size="50" <?php if ($_GET["accion"] == 208) echo"disabled"?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Siglas:</td>
				<td class='viewProp'><input type="text" name="siglas" value="<?php echo $registro_actualizar["siglas"];?>" maxlength="10" size="10" <?php if ($_GET["accion"] == 208) echo"disabled"?>></td>
			</tr>
		<?php
		}
		?>
	</table>
	</table>
	<table align=center cellpadding=2 cellspacing=0>
		<tr><td>
<?php

					if($_GET["accion"] != 207 and $_GET["accion"] != 208 and in_array(206, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 207 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 208 and in_array($_GET["accion"], $privilegios) == true){
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
			<td class='viewProp'><input type="text" name="textoabuscar" maxlength="60" size="30"></td>
			<td align='right' class='viewPropTitle'>Por:</td>
			<td class='viewProp'>
				<select name="tipobusqueda">
					<option VALUE="c">C&oacute;digo</option>
					<option VALUE="d">Denominaci&oacute;n</option>
				</select> 
			</td>
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
						<table class="Browse" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<td align="center" class="Browse">C&oacute;digo</td>
									<td align="center" class="Browse">Denominaci&oacute;n</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							
							<?php
							if ($existen_registros==0){
							while($llenar_grilla = mysql_fetch_array($registros_grilla)) 
							{ 
								?>
								<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
								echo "<td align='center' class='Browse'>".$llenar_grilla["iddependencia"]."</td>";
								echo "<td align='left' class='Browse'>".$llenar_grilla["denominacion"]."</td>";
								$c=$llenar_grilla["iddependencia"];
								if(in_array(207, $privilegios) == true){
									echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=9&accion=207&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
								}
								if(in_array(208, $privilegios) == true){
									echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=9&accion=208&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmdependencia.iddependencia.focus() </script>
</body>
</html>

<?php
if($_POST){
		$iddependencia=$_POST["id_dependencia"];
		$codigo=$_POST["iddependencia"];
		$denominacion=strtoupper($_POST["denominacion"]);
		$siglas=strtoupper($_POST["siglas"]);

	if($_GET["accion"] == 206 and in_array(206, $privilegios) == true){
		$busca_existe_registro=mysql_query("select * from dependencias 
															where iddependencia like '".$codigo."'  
															and status='a'"
																,$conexion_db);
		
		if (mysql_num_rows($busca_existe_registro)>0){
			mensaje("Disculpe el regsitro que ingreso ya existe, Vuelva a Intentarlo");
			redirecciona("principal.php?modulo=9&accion=61");
		}else{
			$result=mysql_query("insert into dependencias 
												(iddependencia,denominacion,usuario,fechayhora,status,siglas) 
											values ('$codigo','$denominacion','$login','$fh','a','$siglas')"
												,$conexion_db);
			registra_transaccion('Ingresar Dependencias ('.$denominacion.')',$login,$fh,$pc,'dependencias',$conexion_db);
			mensaje("El registro se Ingreso con Exito");
			redirecciona("principal.php?modulo=9&accion=61");
		}
	}
	
	if($_GET["accion"] == 207 and in_array(207, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update dependencias set 	denominacion='".strtoupper($denominacion)."',
													siglas='".strtoupper($siglas)."',
													usuario='".$login."', 
													fechayhora='".$fh."' 
														where iddependencia like '$iddependencia' 
															and status='a'"
																,$conexion_db);
			registra_transaccion('Modificar Dependencias ('.$denominacion.')',$login,$fh,$pc,'dependencias',$conexion_db);
			mensaje("El registro se Modifico con Exito");
			redirecciona("principal.php?modulo=9&accion=61");
	}
	if($_GET["accion"] == 208 and in_array(208, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from dependencias where iddependencia = '$iddependencia'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from dependencias where iddependencia = '$iddependencia'"
															,$conexion_db);
			if(!$_sql_eliminar){
			registra_transaccion('Eliminar Dependencias (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'dependencias',$conexion_db);
			mensaje("Disculpe el registro que intenta eliminar esta relacionado con otro registro en el sistema, por ello no puede ser eliminado");
			redirecciona("principal.php?modulo=9&accion=61");
			
			}else{
			registra_transaccion('Eliminar Dependencias ('.$bus["denominacion"].')',$login,$fh,$pc,'dependencias',$conexion_db);
			mensaje("El registro se Elimino con Exito");
			redirecciona("principal.php?modulo=9&accion=61");

			}
	}
}
?>
