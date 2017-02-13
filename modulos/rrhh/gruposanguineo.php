<?php
if($_POST["ingresar"]){
$_GET["accion"] = 179;
}


if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from grupo_sanguineo 
												where status='a'"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from grupo_sanguineo where status='a'";
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

if ($_GET["accion"] == 180 || $_GET["accion"] == 181){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from grupo_sanguineo 
										where idgrupo_sanguineo like '".$_GET['c']."'"
											,$conexion_db);
	$registro_actualizar=mysql_fetch_assoc($sql);
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		if (document.frmgruposanguineo.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para el Grupo Sanguineo.")
			document.frmgruposanguineo.denominacion.focus()
			return false;
		}	
	} 
// end hiding from old browsers -->
</SCRIPT>
	<body>
	<br>
	<h4 align=center>Grupos Sanguineos</h4>
	<h2 class="sqlmVersion"></h2>
		<form name="frmgruposanguineo" action="principal.php?modulos=1&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
	<table align=center cellpadding=2 cellspacing=0>
	<?php
		if ($_GET["accion"] == 179 || $_GET["accion"] == 27){ //entro en modo para agregar
		?>
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" maxlength="4" size="4">
                 &nbsp;<a href="principal.php?modulo=1&accion=27"><img src="imagenes/nuevo.png" border="0" title="Nuevo Grupo Sanguineo"></a> 
                 
                 
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=gsang';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none';">X</a></div>
          
          <iframe name="pdf" id="pdf" style="display:block" height="500" width="500"></iframe>          
          </div>
                 
                </td>
			</tr>
		<?php
		}
		if ($_GET["accion"] == 180 || $_GET["accion"] == 181){ //entro en modo para modificar o eliminar
		?>
			<input type="hidden" name="tabla" value="8">  <?// campo oculto para enviar por el POST el numero de tabla a modificar?>
			<input type="hidden" name="user" value="<?php echo $registro_usuario["login"];    // envia el usuario que esta haciendo la modificacion?>">
			<input type="hidden" name="id_gruposanguineo" value="<?php echo $registro_actualizar["idgrupo_sanguineo"]; //envia el id del banco a modificar ?>">
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" value="<?php echo $registro_actualizar["denominacion"];?>" maxlength="4" size="4" <?php if ($_GET["accion"] == 181) echo"disabled"?>>
                 &nbsp;<a href="principal.php?modulo=1&accion=27"><img src="imagenes/nuevo.png" border="0" title="Nuevo Grupo Sanguineo"></a>
                 
                
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
					if($_GET["accion"] != 180 and $_GET["accion"] != 181 and in_array(179, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 180 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 181 and in_array($_GET["accion"], $privilegios) == true){
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
									$c=$llenar_grilla["idgrupo_sanguineo"];
									if(in_array(180, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=180&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(181, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=181&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmgruposanguineo.denominacion.focus() </script>
</body>
</html>

<?php
if($_POST){

		$denominacion=strtoupper($_POST["denominacion"]);
		$codigo_grupo_sanguineo=$_POST["id_gruposanguineo"];
		if($_GET["accion"] == 179 and in_array(179, $privilegios) == true){
			$busca_existe_registro=mysql_query("select * from grupo_sanguineo 
															where denominacion like '".$denominacion."'  
															and status='a'"
																	,$conexion_db);

			if (mysql_num_rows($busca_existe_registro)>0){
				mensaje("Disculpe el Registro que ingreso ya Exite, Vuelva a Intentarlo");
				redirecciona("principal.php?accion=27&modulo=1");
			}else{
				mysql_query("insert into grupo_sanguineo 
											(denominacion,usuario,fechayhora,status) 
										values ('$denominacion','$login','$fh','a')"
											,$conexion_db);
											
				$idcreado = mysql_insert_id();
				registra_transaccion('Ingresar Grupos Sanguineos ('.$denominacion.')',$login,$fh,$pc,'grupo_sanguineo',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("gruposanguineo", "lib/consultar_tablas_select.php", "grupo_sanguineo", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
				mensaje("El registro se Ingreso con Exito");
				redirecciona("principal.php?accion=27&modulo=1");
			}
			

			}
		}
		
		if($_GET["accion"] == 180 and in_array(180, $privilegios) == true and !$_POST["buscar"]){
			$codigo_grupo_sanguineo=$_POST["id_gruposanguineo"];
			$denominacion=$_POST["denominacion"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']); 
			mysql_query("update grupo_sanguineo set 	denominacion='".strtoupper($denominacion)."', 
											usuario='".$login."', 
											fechayhora='".$fh."' 
												where idgrupo_sanguineo like '$codigo_grupo_sanguineo'"
													,$conexion_db);	
				registra_transaccion('Modificar Grupo Sanguineo ('.$denominacion.')',$login,$fh,$pc,'grupo_sanguineo',$conexion_db);
				mensaje("El registro se Modifico con Exito");
				redirecciona("principal.php?accion=27&modulo=1");
		}
		if($_GET["accion"] == 181 and in_array(181, $privilegios) == true and !$_POST["buscar"]){
			
			$sql = mysql_query("select * from grupo_sanguineo where idgrupo_sanguineo = '$codigo_grupo_sanguineo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from grupo_sanguineo where idgrupo_sanguineo = '$codigo_grupo_sanguineo'"
													,$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Grupo Sanguineo (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'grupo_sanguineo',$conexion_db);
				mensaje("Disculpe el regsitro que intenta eliminar tiene relacioncon otro regsitro dentro del sistema, por ello no puede ser eliminado");
				redirecciona("principal.php?accion=27&modulo=1");
			
			}else{
				registra_transaccion('Eliminar Grupo Sanguineo ('.$bus["denominacion"].')',$login,$fh,$pc,'grupo_sanguineo',$conexion_db);
				mensaje("El registro se Elimino con Exito");
				redirecciona("principal.php?accion=27&modulo=1");
			
			}			
		}
}
?>
