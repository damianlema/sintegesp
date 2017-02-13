<?php
if($_POST["ingresar"]){
$_GET["accion"] = 170;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from cargos 
												where status='a'"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$campo_busqueda=$_POST["tipobusqueda"];
	$sql="select * from cargos where status='a'";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			if ($campo_busqueda=="c"){
				$registros_grilla=mysql_query($sql." and idcargo like '$texto_buscar%'",$conexion_db);
			}
			if ($campo_busqueda=="d"){
				$registros_grilla=mysql_query($sql." and denominacion like '$texto_buscar%'",$conexion_db);
			}
			if ($campo_busqueda=="g"){
				$registros_grilla=mysql_query($sql." and grado like '$texto_buscar%'",$conexion_db);
			}
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}
//selecciona las series de cargos a cargar en el combobox
$series=mysql_query("select * from series 
									where status='a'"
										,$conexion_db);

if ($_GET["accion"]==171 || $_GET["accion"]==172){
	$sql=mysql_query("select * from cargos 
									where idcargo like '".$_GET['c']."'"
										,$conexion_db);
	$registro_actualizar=mysql_fetch_assoc($sql);
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos

function valida_envia(){
    
	//valido el grupo
    
	if (document.frmcargo.serie.selectedIndex==0){
       alert("Debe seleccionar una Serie para el Cargo.")
       document.frmcargo.serie.focus()
       return false;
    }
	//valido el codigo
	if (document.frmcargo.codigo.value.length==0){
		alert("Tiene que escribir un Codigo para el Cargo")
		document.frmcargo.codigo.focus()
		return false;
	}
	
	if (document.frmcargo.denominacion.value.length==0){
		alert("Tiene que escribir una Denominaci&oacute;n para el Cargo")
		document.frmcargo.denominacion.focus()
		return false;
	}
	
	if (document.frmcargo.grado.value.length==0){
		alert("Tiene que escribir un Grado para el Cargo")
		document.frmcargo.grado.focus()
		return false;
	}

    //document.serie.submit();
} 

// end hiding from old browsers -->
</SCRIPT>

	<body>
	<br>
	<h4 align=center>Cargos</h4>
	<h2 class="sqlmVersion"></h2>
		<form name="frmcargo" action="principal.php?modulo=1&accion=<?=$_GET["accion"]?>" method="POST" onSubmit="return valida_envia()">	
	<table align=center cellpadding=2 cellspacing=0>
	<?php
		if ($_GET["accion"]==170 || $_GET["accion"] == 12){ //entro en modo para agregar
		?>
			<tr>
			<td align='right' class='viewPropTitle'>Series:</td>
			<td class='viewProp'>
				<select name=serie>
					<option>&nbsp;</option>
					<?php
						while($regseries = mysql_fetch_array($series)) 
							{ 
								?>
									<option value="<?php echo $regseries["idserie"];?>"><?php echo $regseries["idserie"]." ".$regseries["denominacion"];?></option>
					<?php
							}
					?>
				</select>
                
                 &nbsp;<a href="principal.php?modulo=1&accion=12"><img src="imagenes/nuevo.png" border="0" title="Nuevo Cargo"></a>
                 
                 
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='block';">X</a></div>
          <table id="tableImprimir">
          	<tr>
            	<td>Ordenar Por: </td>
                <td>
                	<select name="ordenarPor" id="ordenarPor">
                    	<option value="cargos.idcargo">C&oacute;digo</option>
                        <option value="cargos.denominacion">Denominaci&oacute;n</option>
                        <option value="cargos.grado">Grado</option>
                        <option value="series.denominacion">Serie</option>
                    </select>                   
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=cargos&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
                </td>
            </tr>
          </table>
          <iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>          
          </div>
                 
                  
			</td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>C&oacute;digo :</td>
				<td class='viewProp'><input type="text" name="codigo" maxlength="5" size="5"></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" maxlength="80" size="50"></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Grado:</td>
				<td class='viewProp'><input type="text" name="grado" maxlength="2" size="2"></td>
			</tr>	
			<tr><td align='right' class='viewPropTitle'>Paso:</td>
				<td class='viewProp'><input type="text" name="paso" maxlength="2" size="2"></td>
			</tr>
			
		<?php
		}
		if ($_GET["accion"]==171 || $_GET["accion"]==172){ //entro en modo para modificar o eliminar
		?>
			<input type="hidden" name="tabla" value="2">
			<input type="hidden" name="user" value="<?php echo $registro_usuario["login"];?>">
			<input type="hidden" name="idcargo" value="<?php echo $registro_actualizar["idcargo"];?>">
			<tr>
			<td align='right' class='viewPropTitle'>Series:</td>
			<td class='viewProp'>
				<select name=serie>
					<option>&nbsp;</option>
					<?php
						while($regseries = mysql_fetch_array($series)) 
							{ 
								?>
									<option value="<?php echo $regseries["idserie"];?>"<?php if ($registro_actualizar["idserie"]==$regseries["idserie"]){echo "selected";}?>><?php echo $regseries["idserie"]." ".$regseries["denominacion"];?></option>
					<?php
							}
					?>
				</select>
                
                &nbsp;<a href="principal.php?modulo=1&accion=12"><img src="imagenes/nuevo.png" border="0" title="Nuevo Cargo"></a> 
			</td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>C&oacute;digo :</td>
				<td class='viewProp'><input type="text" name="codigo" value="<?php echo $registro_actualizar["idcargo"];?>" maxlength="5" size="5" disabled></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" value="<?php echo $registro_actualizar["denominacion"];?>" maxlength="80" size="50" <?php if ($_GET["accion"]==172) echo"disabled"?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Grado:</td>
				<td class='viewProp'><input type="text" name="grado" value="<?php echo $registro_actualizar["grado"];?>" maxlength="2" size="2"<?php if ($_GET["accion"]==172) echo"disabled"?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Paso:</td>
				<td class='viewProp'><input type="text" name="paso" value="<?php echo $registro_actualizar["paso"];?>" maxlength="2" size="2"<?php if ($_GET["accion"]==172) echo"disabled"?>></td>
			</tr>
		<?php
		}
	?>
		
	</table>
	<table align=center cellpadding=2 cellspacing=0>
		<tr><td>
<?php

					if($_GET["accion"] != 171 and $_GET["accion"] != 172 and in_array(170, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 171 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 172 and in_array($_GET["accion"], $privilegios) == true){
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
					<option VALUE="g">Grado</option>
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
						<form name="grilla" action="cargos.php" method="POST">
						<table class="Browse" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<!--<td class="Browse">&nbsp;</td>-->
									<td align="center" class="Browse">C&oacute;digo</td>
									<td align="center" class="Browse">Denominaci&oacute;n</td>
									<td align="center" class="Browse">Grado</td>
									<td align="center" class="Browse">Paso</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							
							<?php
							//$resultg=mysql_query("select * from clasificador",$link);
							if ($mensaje==0){
								while($llenar_grilla = mysql_fetch_array($registros_grilla)) 
								{ 
								?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
									echo "<td align='center' class='Browse'>".$llenar_grilla["idcargo"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["denominacion"]."</td>";
									echo "<td align='center' class='Browse'>".$llenar_grilla["grado"]."</td>";
									echo "<td align='center' class='Browse'>".$llenar_grilla["paso"]."</td>";
									$c=$llenar_grilla["idcargo"];
									if(in_array(171, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=171&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(172, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=172&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
		<script> document.frmcargo.serie.focus() </script>
</body>
</html>

<?php
if($_POST){
		$codigo=$_POST["codigo"];
		$idcargo=$_POST["idcargo"];
		$login=$_POST["user"];
		$denominacion=strtoupper($_POST["denominacion"]);
		$grado=$_POST["grado"];
		$paso=$_POST["paso"];
		$idserie=$_POST["serie"];

	if($_GET["accion"] == 170 and in_array(170, $privilegios) == true){							
		$busca_existe_registro=mysql_query("select * from cargos 
											where idcargo like '".$codigo."'  
											and status='a'"
												,$conexion_db);
		if (mysql_num_rows($busca_existe_registro)>0){
			mensaje("Disuclpe el Regsitro que Inserto ya Existe, Vuelva a Intentarlo");
			redirecciona("principal.php?modulo=1&accion=12");
		}else{
			$result=mysql_query("insert into cargos 
												(idcargo,denominacion,usuario,fechayhora,status,idserie,grado,paso) 
											values ('$codigo','$denominacion','$login','$fh','a','$idserie','$grado',$paso)"
												,$conexion_db);
			registra_transaccion('Insertar Cargos ('.$denominacion.')',$login,$fh,$pc,'cargos',$conexion_db);
			mensaje("El registro se Inserto con Exito");
			redirecciona("principal.php?modulo=1&accion=12");
		}
	}
		if ($_GET["accion"] == 171 and in_array(171, $privilegios) == true and !$_POST["buscar"]){ // modificando un registro de la tabla CARGOS

			mysql_query("update cargos set denominacion='".strtoupper($denominacion)."', 
										usuario='".$login."', 
										fechayhora='".$fh."', 
										idserie='".$idserie."', 
										grado='".$grado."',
										paso='".$paso."'
											where idcargo = '$idcargo' 
												and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Cargos ('.$denominacion.')',$login,$fh,$pc,'cargos',$conexion_db);
			mensaje("El registro se Modifico con Exito");
			redirecciona("principal.php?modulo=1&accion=12");
		}
		if ($_GET["accion"] == 172 and in_array(172, $privilegios) == true and !$_POST["buscar"]){ // eliminando un registro de la tabla CARGOS
			$sql = mysql_query("select * from cargos where idcargo = '$idcargo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from cargos where idcargo = '$idcargo'"
													,$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Cargos (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'cargos',$conexion_db);
				mensaje("Disculpe el regsitro que intenta eliminar tiene relacioncon otro regsitro dentro del sistema, por ello no puede ser eliminado");
				redirecciona("principal.php?modulo=1&accion=12");
			
			}else{
				registra_transaccion('Eliminar Cargos ('.$bus["denominacion"].')',$login,$fh,$pc,'cargos',$conexion_db);
				mensaje("El registro se Elimino con Exito");
				redirecciona("principal.php?modulo=1&accion=12");

			}
		}
}
?>
