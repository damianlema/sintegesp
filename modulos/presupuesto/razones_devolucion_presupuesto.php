<?php
if($_POST["ingresar"]){
$_GET["accion"] = 261;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from razones_devolucion 
												where status='a' order by descripcion"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from razones_devolucion where status='a'";
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

if ($_GET["accion"] == 262 || $_GET["accion"] == 263){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from razones_devolucion 
										where idrazones_devolucion like '".$_GET['c']."'"
											,$conexion_db);
	$regrazones_devolucion=mysql_fetch_assoc($sql);
	
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		
		if (document.frmrazones_devolucion.descripcion.value.length==0){
			alert("Debe escribir una Descripcion para Razon de Devoluci&oacute;n")
			document.frmrazones_devolucion.descripcion.focus()
			return false;
		}	
	} 


// end hiding from old browsers -->
</SCRIPT>

</head>
	<body>
	<br>
	<h4 align=center>Razones de Devoluci&oacute;n de Documentos</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<form name="frmrazones_devolucion" action="principal.php?modulo=2&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
	<input type="hidden" id="id" name="id" maxlength="9" size="9" <?php echo 'value="'.$regrazones_devolucion['idrazones_devolucion'].'"';?>>
		<table align=center cellpadding=2 cellspacing=0 width="40%">
<tr>
				<td align='right' class='viewPropTitle'>Descripcion:</td>
				<td class=''><input type="text" name="descripcion" maxlength="255" size="45" id="descripcion" <?php echo 'value="'.$regrazones_devolucion['descripcion'].'"';?>>
			    &nbsp;<a href='principal.php?modulo=2&accion=260' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nueva Raz&oacute;n de Devoluci&oacute;n"></a>
                
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/<?=$_SESSION["rutaReportes"]?>/generar_reporte.php?nombre=tipoempresa';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
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
	?>
			<?php
					if($_GET["accion"] != 262 and $_GET["accion"] != 263 and in_array(261, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 262 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 263 and in_array($_GET["accion"], $privilegios) == true){
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
				</a>
			</td>
		</tr>
	</table>
	</form>
	<br>
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="60%">
				<tr>
					<td align="center">
						<form name="grilla" action="" method="POST">
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
									$c=$llenar_grilla["idrazones_devolucion"];
									if(in_array(262,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=262&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(263,$privilegios) ==  true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=263&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmrazones_devolucion.descripcion.focus() </script>
</body>
</html>

<?php
if($_POST){
	$id=$_POST["id"];
	$descripcion=strtoupper($_POST["descripcion"]);
	$busca_existe_registro=mysql_query("select * from razones_devolucion where descripcion = '".$_POST['descripcion']."'  and status='a'",$conexion_db);
	
	if($_GET["accion"] == 261 and in_array(261, $privilegios) == true){
	
	if (mysql_num_rows($busca_existe_registro)>0){
				?>
				<script>
			mostrarMensajes("error", "Disculpe el Regsitro que ingreso ya existe, vuelvalo a intentar");
			setTimeout("window.location.href='principal.php?modulo=2&accion=260'",5000);
			</script>

		<?

	}else{
		
			mysql_query("insert into razones_devolucion
									(descripcion,usuario,fechayhora,status) 
							values ('$descripcion','$login','$fh','a')"
									,$conexion_db);					
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Razones Devolucion ('.$descripcion.')',$login,$fh,$pc,'razones_devolucion',$conexion_db);
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("idrazones_devolucion", "lib/consultar_tablas_select.php", "razones_devolucion", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
		//mensaje("El registro se Ingreso con Exito");
		redirecciona("principal.php?modulo=2&accion=260");

			}						

		}
	}
	if ($_GET["accion"] == 262 and in_array(262,$privilegios) == true and !$_POST["buscar"]){
			mysql_query("update razones_devolucion set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idrazones_devolucion = '$id' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Razones Devolucion ('.$descripcion.')',$login,$fh,$pc,'razones_devolucion',$conexion_db);
			
			?>
				<script>
			mostrarMensajes("exito", "El registro se modifico con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=260'",5000);
			</script>

		<?
	}
	if ($_GET["accion"] == 263 and in_array(263,$privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from razones_devolucion where idrazones_devolucion = '$id'");
			$BUS = mysql_fetch_array($sql);
			$sql_elimianr = mysql_query("delete from razones_devolucion where idrazones_devolucion = '$id'",$conexion_db);	
			if(!$sql_elimianr){
				registra_transaccion('Eliminar Razones Devolucion (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'razones_devolucion',$conexion_db);
				?>
				<script>
			mostrarMensajes("error", "isculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabl");
			setTimeout("window.location.href='principal.php?modulo=2&accion=260'",5000);
			</script>

		<?
			}else{
				registra_transaccion('Eliminar Razones Devolucion ('.$bus["descripcion"].')',$login,$fh,$pc,'razones_devolucion',$conexion_db);
				
				?>
				<script>
			mostrarMensajes("exito", "El regsitro se Elimino con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=260'",5000);
			</script>

		<?
			}
			
	}
}
?>