<?php
if($_POST["ingresar"]){
$_GET["accion"] = 176;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from edo_civil 
												where status='a'"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from edo_civil where status='a'";
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

if ($_GET["accion"] == 177 || $_GET["accion"] == 178){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from edo_civil 
										where idedo_civil like '".$_GET['c']."'"
											,$conexion_db);
	$registro_actualizar=mysql_fetch_assoc($sql);
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		if (document.frmedocivil.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para el Estado Civil.")
			document.frmedocivil.denominacion.focus()
			return false;
		}	
	} 

function actselect(){
	window.close()
}

// end hiding from old browsers -->
</SCRIPT>

	<body>
	<br>
	<h4 align=center>Estado Civil</h4>
	<h2 class="sqlmVersion"></h2>
		<form name="frmedocivil" action="principal.php?modulo=1&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
	<table align=center cellpadding=2 cellspacing=0>
	<?php
		if ($_GET["accion"] == 176 || $_GET["accion"] == 25){ //entro en modo para agregar
		?>
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
			  <td class='viewProp'><input type="text" name="denominacion" maxlength="30" size="30">
                
                &nbsp;<a href="principal.php?modulo=1&accion=25"><img src="imagenes/nuevo.png" border="0" title="Nuevo Estado Civil"></a>
                
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=edocivil';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none';">X</a></div>          
          <iframe name="pdf" id="pdf" style="display:block" height="500" width="500"></iframe>          
          </div>
                
                </td>
			</tr>
		<?php
		}
		if ($_GET["accion"] == 177 || $_GET["accion"] == 178){ //entro en modo para modificar o eliminar
		?>
			<input type="hidden" name="tabla" value="6">  <?// campo oculto para enviar por el POST el numero de tabla a modificar?>
			<input type="hidden" name="user" value="<?php echo $registro_usuario["login"];    // envia el usuario que esta haciendo la modificacion?>">
			<input type="hidden" name="id_edocivil" value="<?php echo $registro_actualizar["idedo_civil"]; //envia el id del grupo a modificar ?>">
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" value="<?php echo $registro_actualizar["denominacion"];?>" maxlength="30" size="30" <?php if ($_GET["accion"] == 178) echo"disabled"?>>
                &nbsp;<a href="principal.php?modulo=1&accion=25"><img src="imagenes/nuevo.png" border="0" title="Nuevo Estado Civil"></a>
                </td>
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
					if($_GET["accion"] != 177 and $_GET["accion"] != 178 and in_array(176, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 177 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 178 and in_array($_GET["accion"], $privilegios) == true){
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
			<td>
				<input align=center class="button" name="buscar" type="submit" value="Buscar">
				<?php if($emergente<>""){ ?>
					<input type='button' value='SALIR' alt='' onClick="actselect()">
				<?php } ?>
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
									$c=$llenar_grilla["idedo_civil"];
									if(in_array(177, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=177&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(178, $privilegios)==true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=!&accion=178&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmedocivil.idgrupo.focus() </script>
</body>
</html>

<?php
if($_POST){
		$idedocivil=$_POST["id_edocivil"];
		$denominacion=strtoupper($_POST["denominacion"]);
if($_GET["accion"] == 176 and in_array(176, $privilegios) == true){	
		$busca_existe_registro=mysql_query("select * from edo_civil 
															where denominacion like '".$denominacion."'  
															and status='a'"
																	,$conexion_db);
		
		if (mysql_num_rows($busca_existe_registro)>0){
			mensaje("Disculpe el regsitro que ingreso ya existe, vuelva a intentarlo");
			redirecciona("principal.php?mmodulo=1&accion=25");
		}else{
			mysql_query("insert into edo_civil 
										(denominacion,usuario,fechayhora,status) 
									values ('$denominacion','$login','$fh','a')"
										,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Estado Civil del Trabajador ('.$denominacion.')',$login,$fh,$pc,'edo_civil',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("edo_civil", "lib/consultar_tablas_select.php", "edo_civil", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				mensaje("El registro se Inserto con Exito");
				redirecciona("principal.php?mmodulo=1&accion=25");
			}
			


		}
	}
	
	if($_GET["accion"] == 177 and in_array(177, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update edo_civil set 	denominacion='".strtoupper($denominacion)."',
												usuario='".$login."', 
												fechayhora='".$fh."' 
													where idedo_civil like '$idedocivil' 
														and status='a'"
															,$conexion_db);
			registra_transaccion('Modificar Estado Civil del Trabajador ('.$denominacion.')',$login,$fh,$pc,'edocivil',$conexion_db);
			mensaje("El registro se Modifico con Exito");
			redirecciona("principal.php?mmodulo=1&accion=25");
	}
	if($_GET["accion"] == 178 and in_array(178, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from edo_civil where idedo_civil = '$idedocivil'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from edo_civil where idedo_civil = '$idedocivil'"
													,$conexion_db);
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Estado Civil del Trabajador (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'edo_civil',$conexion_db);
				mensaje("Disculpe el registro que intenta eliminar esta relaciondo con otro registro dentro del sistema, por ello no puede ser eliminado");
				redirecciona("principal.php?mmodulo=1&accion=25");
			
			}else{
				registra_transaccion('Eliminar Estado Civil del Trabajador ('.$bus["denominacion"].')',$login,$fh,$pc,'edo_civil',$conexion_db);
				mensaje("El registro se Elimino con Exito");
				redirecciona("principal.php?mmodulo=1&accion=25");
			
			}
	}
	
	
}
?>
