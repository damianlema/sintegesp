<?php
if($_POST["ingresar"]){
$_GET["accion"] = 182;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from mension 
												where status='a'"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from mension where status='a'";
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

if ($_GET["accion"] == 183 || $_GET["accion"] == 184){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from mension 
										where idmension like '".$_GET['c']."'"
											,$conexion_db);
	$registro_actualizar=mysql_fetch_assoc($sql);
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		if (document.frmmension.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para la Mensi&oacute;n")
			document.frmmension.denominacion.focus()
			return false;
		}	
	} 
// end hiding from old browsers -->
</SCRIPT>
	<body>
	<br>
	<h4 align=center>Mensiones</h4>
	<h2 class="sqlmVersion"></h2>
		<form name="frmmension" action="principal.php?modulo=1&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
	<table align=center cellpadding=2 cellspacing=0>
	<?php
		if ($_GET["accion"] == 182 || $_GET["accion"] == 15){ //entro en modo para agregar
		?>
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" maxlength="40" size="40">
                &nbsp;<a href="principal.php?modulo=1&accion=15"><img src="imagenes/nuevo.png" border="0" title="Nuevo Mension"></a>
                
                
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=mensiones';"><img src="imagenes/imprimir.png" border="0" title="Imprimir Cargos"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none';">X</a></div>
          
          <iframe name="pdf" id="pdf" style="display:block" height="500" width="500"></iframe>          
          </div>               
                
                </td>
			</tr>
		<?php
		}
		if ($_GET["accion"] == 183 || $_GET["accion"] == 184){ //entro en modo para modificar o eliminar
		?>
			<input type="hidden" name="tabla" value="5">  <?// campo oculto para enviar por el POST el numero de tabla a modificar?>
			<input type="hidden" name="user" value="<?php echo $registro_usuario["login"];    // envia el usuario que esta haciendo la modificacion?>">
			<input type="hidden" name="id_mension" value="<?php echo $registro_actualizar["idmension"]; //envia el id del banco a modificar ?>">
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
			<td class='viewProp'><input type="text" name="denominacion" value="<?php echo $registro_actualizar["denominacion"];?>" maxlength="40" size="40" <?php if ($_GET["accion"] == 184) echo"disabled"?>>
            &nbsp;<a href="principal.php?modulo=1&accion=15"><img src="imagenes/nuevo.png" border="0" title="Nuevo Mension"></a>
            
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

					if($_GET["accion"] != 183 and $_GET["accion"] != 184 and in_array(182, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 183 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 184 and in_array($_GET["accion"], $privilegios) == true){
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
									$c=$llenar_grilla["idmension"];
									if(in_array(182, $privilegios)==true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=183&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(184, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=184&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmmension.denominacion.focus() </script>
</body>
</html>

<?php
if($_POST){

	
			$codigo_mension=$_POST["id_mension"];
		$denominacion=strtoupper($_POST["denominacion"]);
if($_GET["accion"] == 182 and in_array(182, $privilegios) == true){
		$busca_existe_registro=mysql_query("select * from mension 
															where denominacion like '".$denominacion."'  
															and status='a'"
																	,$conexion_db);
		
		if (mysql_num_rows($busca_existe_registro)>0){
			mensaje("Disculpe el registro ya exite, Vuelva a Intentarlo");
			redirecciona("principal.php?modulo=1&accion=15");
		}else{
			mysql_query("insert into mension 
										(denominacion,usuario,fechayhora,status) 
									values ('$denominacion','$login','$fh','a')"
										,$conexion_db);
										
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Mension ('.$denominacion.')',$login,$fh,$pc,'mension',$conexion_db);
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idmension", "lib/consultar_tablas_select.php", "mension", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				mensaje("El regsitro se Inserto con Exito");
			redirecciona("principal.php?modulo=1&accion=15");
			}
			
			

		}
	}
	if($_GET["accion"] == 183 and in_array(183, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update mension set 	denominacion='".strtoupper($denominacion)."', 
											usuario='".$login."', 
											fechayhora='".$fh."' 
												where idmension like '$codigo_mension'"
													,$conexion_db);	
			registra_transaccion('Modificar Mension ('.$denominacion.')',$login,$fh,$pc,'mension',$conexion_db);
			mensaje("El regsitro se Modificar con Exito");
			redirecciona("principal.php?modulo=1&accion=15");
	}
	
	if($_GET["accion"] == 184 and in_array(184, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from mension where idmension = '$codigo_mension'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from mension where idmension = '$codigo_mension'"
													,$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Mension (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'mension',$conexion_db);
				mensaje("Disculpe el regsitro que intenta eliminar tiene relacioncon otro regsitro dentro del sistema, por ello no puede ser eliminado");
				redirecciona("principal.php?modulo=1&accion=15");
			
			}else{
				registra_transaccion('Eliminar Mension ('.$bus["denominacion"].')',$login,$fh,$pc,'mension',$conexion_db);
				mensaje("El regsitro se Elimino con Exito");
				redirecciona("principal.php?modulo=1&accion=15");			
			}
	}
}
?>