<?php
if($_POST["ingresar"]){
$_GET["accion"] = 200;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from banco 
												where status='a'"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from banco where status='a'";
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

if ($_GET["accion"] == 201 || $_GET["accion"] == 202){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from banco 
										where idbanco like '".$_GET['c']."'"
											,$conexion_db);
	$registro_actualizar=mysql_fetch_assoc($sql);
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		if (document.frmbanco.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para el Banco.")
			document.frmbanco.denominacion.focus()
			return false;
		}	
	} 
// end hiding from old browsers -->
</SCRIPT>
	<body>
	<br>
	<h4 align=center>Bancos</h4>
	<h2 class="sqlmVersion"></h2>
		<form name="frmbanco" action="principal.php?modulo=9&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
	<table width="50%" align=center cellpadding=2 cellspacing=0>
	  
	<?php
		if ($_GET["accion"] == 200 || $_GET["accion"] == 54){ //entro en modo para agregar
		?>
   <tr>
         <td colspan="6" align='right' ><div align="center"><span class="viewProp"><a href="principal.php?modulo=9&accion=54"><img src="imagenes/nuevo.png" border="0" title="Nuevo Banco"></a></span></div></td>
       </tr>
       <tr>
	    <td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
	    <td colspan="5" class='viewProp'><input type="text" name="denominacion" maxlength="45" size="45"></td>
      </tr>
	  <tr>
	    <td align='right' class='viewPropTitle'>Gerente</td>
	    <td class='viewProp'><input type="text" name="gerente" maxlength="60" size="30"></td>
	    <td class='viewPropTitle'>C.I.No.</td>
	    <td class='viewProp'><input type="text" name="ci_gerente" maxlength="12" size="12"></td>
	    <td class='viewPropTitle'>Cargo:</td>
	    <td class='viewProp'><input type="text" name="cargo_gerente" maxlength="200" size="80"></td>
      </tr>
	  <tr>
        <td align='right' class='viewPropTitle'>Fideicomiso</td>
	    <td class='viewProp'><input type="text" name="fideicomiso" maxlength="60" size="30"></td>
	    <td class='viewPropTitle'>C.I.No.</td>
	    <td class='viewProp'><input type="text" name="ci_fideicomiso" maxlength="12" size="12"></td>
	    <td class='viewPropTitle'>Cargo:</td>
	    <td class='viewProp'><input type="text" name="cargo_fideicomiso" maxlength="200" size="80"></td>
      </tr>
	 <tr>
        <td align='right' class='viewPropTitle'>Atenci&oacute;n:</td>
	    <td class='viewProp' colspan="3"><input type="text" name="atencion" maxlength="100" size="100"></td>
      </tr>
		<?php
		}
		if ($_GET["accion"] == 201 || $_GET["accion"] == 202){ //entro en modo para modificar o eliminar
		?>
			<input type="hidden" name="tabla" value="4">  <?// campo oculto para enviar por el POST el numero de tabla a modificar?>
			<input type="hidden" name="user" value="<?php echo $registro_usuario["login"];    // envia el usuario que esta haciendo la modificacion?>">
			<input type="hidden" name="id_banco" value="<?php echo $registro_actualizar["idbanco"]; //envia el id del banco a modificar ?>">
            
             <tr>
	    <td colspan="6" align='right' ><div align="center"><span class="viewProp"><a href="principal.php?modulo=9&accion=54"><img src="imagenes/nuevo.png" border="0" title="Nuevo Banco"></a></span></div></td>
			</tr>
	  <tr>
	    <td align='right' class='viewPropTitle'>Denominaci&oacute;n</td>
	    <td colspan="5" class='viewProp'><input type="text" name="denominacion" value="<?php echo $registro_actualizar["denominacion"];?>" maxlength="45" size="45" <?php if ($_GET["accion"] == 202) echo"disabled"?>></td>
      </tr>
	  <tr>
        <td align='right' class='viewPropTitle'>Gerente</td>
	    <td class='viewProp'><input type="text" name="gerente" value="<?php echo $registro_actualizar["gerente"];?>" maxlength="60" size="30"></td>
	    <td class='viewPropTitle'>C.I.No.</td>
	    <td class='viewProp'><input type="text" name="ci_gerente" value="<?php echo $registro_actualizar["ci_gerente"];?>" maxlength="12" size="12"></td>
	    <td class='viewPropTitle'>Cargo:</td>
	    <td class='viewProp'><input type="text" name="cargo_gerente" value="<?php echo $registro_actualizar["cargo_gerente"];?>" maxlength="200" size="80"></td>
      </tr>
	  <tr>
        <td align='right' class='viewPropTitle'>Fideicomiso</td>
	    <td class='viewProp'><input type="text" name="fideicomiso" value="<?php echo $registro_actualizar["fideicomiso"];?>" maxlength="60" size="30"></td>
	    <td class='viewPropTitle'>C.I.No.</td>
	    <td class='viewProp'><input type="text" name="ci_fideicomiso" value="<?php echo $registro_actualizar["ci_fideicomiso"];?>" maxlength="12" size="12"></td>
	    <td class='viewPropTitle'>Cargo:</td>
	    <td class='viewProp'><input type="text" name="cargo_fideicomiso" value="<?php echo $registro_actualizar["cargo_fideicomiso"];?>" maxlength="200" size="80"></td>
      </tr>
       <tr>
        <td align='right' class='viewPropTitle'>Atenci&oacute;n:</td>
	    <td class='viewProp' colspan="3"><input type="text" name="atencion" value="<?php echo $registro_actualizar["atencion"];?>"maxlength="100" size="100"></td>
      </tr>   
		<?php
		}
	?>	
	</table>
	</table>
	<table align=center cellpadding=2 cellspacing=0>
		<tr><td>
<?php

					if($_GET["accion"] != 201 and $_GET["accion"] != 202 and in_array(200, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 201 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 202 and in_array($_GET["accion"], $privilegios) == true){
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
									$c=$llenar_grilla["idbanco"];
									if(in_array(201, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=9&accion=201&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(202, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=9&accion=202&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmbanco.denominacion.focus() </script>
</body>
</html>

<?php
if($_POST){

		$denominacion=strtoupper($_POST["denominacion"]);
		$gerente=strtoupper($_POST["gerente"]);
		$ci_gerente=strtoupper($_POST["ci_gerente"]);
		$cargo_gerente=strtoupper($_POST["cargo_gerente"]);
		$fideicomiso=strtoupper($_POST["fideicomiso"]);	
		$ci_fideicomiso=strtoupper($_POST["ci_fideicomiso"]);	
		$cargo_fideicomiso=strtoupper($_POST["cargo_fideicomiso"]);		
		$atencion=strtoupper($_POST["atencion"]);	
		$codigo_banco=$_POST["id_banco"];
	if($_GET["accion"] == 200 and in_array(200, $privilegios) == true){
		$busca_existe_registro=mysql_query("select * from banco 
															where denominacion like '".$denominacion."'  
															and status='a'"
																	,$conexion_db);
		
		if (mysql_num_rows($busca_existe_registro)>0){
			mensaje("Disculpe el registro que ingreso ya Existe, Vuelva a Intentarlo");
			redirecciona("principal.php?modulo=9&accion=54");
		}else{
			mysql_query("insert into banco 
										(denominacion,gerente,ci_gerente,cargo_gerente,fideicomiso,ci_fideicomiso,cargo_fideicomiso,atencion,usuario,fechayhora,status) 
									values ('$denominacion','$gerente','$ci_gerente','$cargo_gerente','$fideicomiso','$ci_fideicomiso','$cargo_fideicomiso','$atencion','$login','$fh','a')"
										,$conexion_db);
										
			registra_transaccion('Ingresar Bancos ('.$denominacion.')',$login,$fh,$pc,'banco',$conexion_db);
			mensaje("El regsitro se Ingreso con Exito");
			redirecciona("principal.php?modulo=9&accion=54");

		}
	}
	
	if($_GET["accion"] == 201 and in_array(201, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update banco set 	denominacion='".strtoupper($denominacion)."', 
											gerente='".$gerente."',
											ci_gerente='".$ci_gerente."',
											cargo_gerente='".$cargo_gerente."',
											fideicomiso='".$fideicomiso."',
											ci_fideicomiso='".$ci_fideicomiso."',
											cargo_fideicomiso='".$cargo_fideicomiso."',
											atencion='".$atencion."',
											usuario='".$login."', 
											fechayhora='".$fh."' 
												where idbanco like '$codigo_banco'"
													,$conexion_db);	
			registra_transaccion('Modificar Bancos ('.$denominacion.')',$login,$fh,$pc,'banco',$conexion_db);
			mensaje("El regsitro se Modificar con Exito");
			redirecciona("principal.php?modulo=9&accion=54");
	}
	
	if($_GET["accion"] == 202 and in_array(202, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from banco where idbanco = '$codigo_banco'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from banco where idbanco = '$codigo_banco'"
													,$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Banco (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'banco',$conexion_db);
				mensaje("Disculpe el registro que intenta eliminar esta relacionado con otro regsitro dentro del sistema, por ello no puede ser eliminado");
				redirecciona("principal.php?modulo=9&accion=54");
			
			}else{
				registra_transaccion('Eliminar Banco ('.$bus["denominacion"].')',$login,$fh,$pc,'banco',$conexion_db);
				mensaje("El registro se Elimino con Exito");
				redirecciona("principal.php?modulo=9&accion=54");
			
			}
	}
}
?>
