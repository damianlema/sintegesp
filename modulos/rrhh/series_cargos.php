<?php
if($_POST["ingresar"]){
$_GET["accion"] = 194;
}
if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from series 
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
	$sql="select * from series where status='a'";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			if ($campo_busqueda=="c"){
				$registros_grilla=mysql_query($sql." and idserie like '$texto_buscar%'",$conexion_db);
			}
			if ($campo_busqueda=="d"){
				$registros_grilla=mysql_query($sql." and denominacion like '$texto_buscar%'",$conexion_db);
			}
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}
// selecciona los registros de grupos de cargos para desplegarlos en combobox 
$grupos=mysql_query("select * from grupos 
									where status='a'"
										,$conexion_db);

if ($_GET["accion"] == 195 || $_GET["accion"] == 196){
	$sql=mysql_query("select * from series 
									where idserie like '".$_GET['c']."'"
										,$conexion_db);
	$registro_actualizar=mysql_fetch_assoc($sql);
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos

function valida_envia(){
    
	//valido el grupo
    if (document.frmserie.grupo.selectedIndex==0){
       alert("Debe seleccionar un Grupo para la Serie.")
       document.frmserie.grupo.focus()
       return false;
    }
	
	//valido el codigo
	if (document.frmserie.idserie.value.length==0){
		alert("Tiene que escribir un Codigo para la Serie")
		document.frmserie.idserie.focus()
		return false;
	}
	
	if (document.frmserie.denominacion.value.length==0){
		alert("Tiene que escribir una Denominaci&oacute;n para la Serie")
		document.frmserie.denominacion.focus()
		return false;
	}

    //document.serie.submit();
} 
</SCRIPT>
	<body>
	<br>
	<h4 align=center>Series de Cargos</h4>
	<h2 class="sqlmVersion"></h2>

		<form name="frmserie" action="principal.php?modulo=!&accion=<?=$_GET["accion"]?>" method="POST" onSubmit="return valida_envia()">	
	
	<table align=center cellpadding=2 cellspacing=0>
	<?php
		if ($_GET["accion"] == 194 || $_GET["accion"] == 11){ //entro en modo para agregar
		?>
			<tr>
			<td align='right' class='viewPropTitle'>Grupo de la serie:</td>
			<td class='viewProp'>
				<select name=grupo>
					<option>&nbsp;</option>
					<?php
						while($rowgru = mysql_fetch_array($grupos)) 
							{ 
								?>
									<option value="<?php echo $rowgru["idgrupo"];?>"><?php echo $rowgru["idgrupo"]." ".$rowgru["denominacion"];?></option>
					<?php
							}
					?>
				</select>
                
                &nbsp;<a href="principal.php?modulo=1&accion=11"><img src="imagenes/nuevo.png" border="0" title="Nueva Series de Cargos"></a>
                
                
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='block';">X</a></div>
          <table id="tableImprimir">
          	<tr>
            	<td>Ordenar Por: </td>
                <td>
                	<select name="ordenarPor" id="ordenarPor">
                    	<option value="series.idserie">C&oacute;digo</option>
                        <option value="series.denominacion">Denominaci&oacute;n</option>
                        <option value="grupos.denominacion">Grupo</option>
                    </select>                    
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=series&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
                </td>
            </tr>
          </table>
          <iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>          
          </div>
                
                
                
                
                 
			</td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>C&oacute;digo :</td>
				<td class='viewProp'><input type="text" name="idserie" maxlength="5" size="5"></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" maxlength="80" size="50"></td>
			</tr>
		<?php
		}
		if ($_GET["accion"] == 195 || $_GET["accion"] == 196){ //entro en modo para modificar o eliminar
		?>
			<input type="hidden" name="tabla" value="1">
			<input type="hidden" name="user" value="<?php echo $registro_usuario["login"];?>">
			<input type="hidden" name="id_serie" value="<?php echo $registro_actualizar["idserie"];?>">
			<input type="hidden" name="id_grupo" value="<?php echo $registro_actualizar["idgrupo"];?>">
			<tr>
			<td align='right' class='viewPropTitle'>Grupo de la serie:</td>
			<td class='viewProp'>
				<select name=grupo>
					<option>&nbsp;</option>
					<?php
						while($rowgru = mysql_fetch_array($grupos)) 
							{ 
								?>
									<option value="<?php echo $rowgru["idgrupo"];?>"<?php if ($registro_actualizar["idgrupo"]==$rowgru["idgrupo"]){echo "selected";}?>><?php echo $rowgru["idgrupo"]." ".$rowgru["denominacion"];?></option>
					<?php
							}
					?>
				</select>
                
                 &nbsp;<a href="principal.php?modulo=1&accion=11"><img src="imagenes/nuevo.png" border="0" title="Nueva Series de Cargos"></a>  
			</td>
			</tr>
			
			<tr><td align='right' class='viewPropTitle'>C&oacute;digo :</td>
				<td class='viewProp'><input type="text" name="idserie" value="<?php echo $registro_actualizar["idserie"];?>" maxlength="5" size="5" disabled></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" value="<?php echo $registro_actualizar["denominacion"];?>" maxlength="80" size="50" <?php if ($_GET["accion"] == 196) echo"disabled"?>></td>
			</tr>
		<?php
		}
	?>
		
	</table>
	</table>
	<table align=center cellpadding=2 cellspacing=0>
		<tr><td>
<?php

					if($_GET["accion"] != 195 and $_GET["accion"] != 196 and in_array(194, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 195 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 196 and in_array($_GET["accion"], $privilegios) == true){
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
									echo "<td align='center' class='Browse'>".$llenar_grilla["idserie"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["denominacion"]."</td>";
									$c=$llenar_grilla["idserie"];
									if(in_array(195, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=195&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(196,  $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=196&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmserie.grupo.focus() </script>
</body>
</html>
<?php
if($_POST){
	
		$codigo=$_POST["idserie"];
		$denominacion=strtoupper($_POST["denominacion"]);
		$idgrupo=$_POST["grupo"];
		$codigo_serie=$_POST["id_serie"];
if($_GET["accion"] == 194 and in_array(194, $privilegios) == true){
		$busca_existe_registro=mysql_query("select * from series 
														where idserie like '".$codigo."'  
														and status='a'"
														,$conexion_db);
		if (mysql_num_rows($busca_existe_registro)>0){
			mensaje("Disculpe el registro que Inserto ya Existe, Vuelva a Interntarlo");
			redirecciona("principal.php?modulo=1&accion=11");
		}else{
			mysql_query("insert into series 
									(idserie,denominacion,usuario,fechayhora,status,idgrupo) 
								values ('$codigo','$denominacion','$login','$fh','a','$idgrupo')"
									,$conexion_db);
			registra_transaccion('Ingresar Series de Cargos ('.$denominacion.')',$login,$fh,$pc,'series',$conexion_db);
			mensaje("El regsitro se Inserto con Exito");
			redirecciona("principal.php?modulo=1&accion=11");

		}
	}
	
	if($_GET["accion"] == 195 and in_array(195, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update series set denominacion='".strtoupper($denominacion)."', 
											usuario='".$login."', 
											fechayhora='".$fh."', 
											idgrupo='".$idgrupo."' 
												where idserie like '$codigo_serie' 
													and status='a'",$conexion_db);	
			registra_transaccion('Modificar Series de Cargos ('.$denominacion.')',$login,$fh,$pc,'series',$conexion_db);													
			mensaje("El regsitro se Modifico con Exito");
			redirecciona("principal.php?modulo=1&accion=11");
	}
	if($_GET["accion"] == 196 and in_array(196, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from series where idserie = '$codigo_serie'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from series where idserie = '$codigo_serie'"
													,$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Series de Cargos (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'series',$conexion_db);
				mensaje("Disculpe el regsitro que intenta eliminar se encuentra relacionado con otro registro en el sistema, por ello no puede ser eliminado");
				redirecciona("principal.php?modulo=1&accion=11");
			}else{
				registra_transaccion('Eliminar Series de Cargos ('.$bus["denominacion"].')',$login,$fh,$pc,'series',$conexion_db);
				mensaje("El regsitro se Elimino con Exito");
				redirecciona("principal.php?modulo=1&accion=11");
			}
	}
	
}
?>
