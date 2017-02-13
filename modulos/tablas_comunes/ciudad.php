<?php

if($_POST["ingresar"]){
$_GET["accion"] = 200;
}


$ls=array(
"pais"=>"pais",
"estados"=>"estado",
"municipios"=>"municipios"
);
$_SESSION['listadoSelect']=serialize($ls);



if ($buscar_registros==0){
	$registros_grilla=mysql_query("select 	pais.denominacion as denopais, 
											estado.denominacion as denoedo, 
											municipios.denominacion as denomuni,
											ciudad.idciudad,
											ciudad.denominacion
											from pais,estado,municipios,ciudad 
												where ciudad.status='a'
												and pais.idpais = estado.idpais
												and estado.idestado = municipios.idestado
												and municipios.idmunicipios=ciudad.idmunicipios"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select 	pais.denominacion as denopais, 
					estado.denominacion as denoedo, 
					municipios.denominacion as denomuni,
					ciudad.idciudad,
					ciudad.denominacion
					from pais,estado,municipios,ciudad 
						where ciudad.status='a'
						and pais.idpais = estado.idpais
						and estado.idestado = municipios.idestado
						and municipios.idmunicipios=ciudad.idmunicipios";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			$registros_grilla=mysql_query($sql." and ciudad.denominacion like '$texto_buscar%'",$conexion_db);
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}

//selecciona los registros de pais para desplegarlos en combobox 

$paiss=mysql_query("select * from pais 
									where status='a'"
										,$conexion_db);

if ($_GET["accion"] == 204 || $_GET["accion"] == 205){
	$sql=mysql_query("select * from ciudad 
									where idciudad like '".$_GET['c']."'"
										,$conexion_db);
    $registro_actualizar=mysql_fetch_assoc($sql);
	
	$municipio=mysql_query("select 	municipios.idestado as idestado, 
									municipios.idmunicipios as idmunicipios, 
									municipios.denominacion as denominacion
									from municipios,estado
									where municipios.status='a'
										and municipios.idmunicipios = '".$registro_actualizar['idmunicipios']."'"
										,$conexion_db);
	$registro_municipio=mysql_fetch_assoc($municipio);
	$municipio_seleccionado=$registro_actualizar['idmunicipios'];
	$estado_seleccionado=$registro_municipio["idestado"];
	
	$estado= mysql_query("select 	estado.idpais as idpais, 
									estado.idestado as idestado, 
									estado.denominacion as denominacion
									from estado,pais
									where estado.status='a'
									and estado.idestado = '".$registro_municipio['idestado']."'"
										,$conexion_db);
	$registro_estado=mysql_fetch_assoc($estado);
	$pais_seleccionado=$registro_estado["idpais"];
	
	$paiss=mysql_query("select * from pais 
									where status='a'"
										,$conexion_db);
}

?>	
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
var listadoSelects=new Array();
listadoSelects[0]="pais";
listadoSelects[1]="estados";
listadoSelects[2]="municipios";
function valida_envia(){
    
	//valido el grupo
    if (document.frmciudad.pais.selectedIndex==0){
       alert("Debe seleccionar un pais para el Estado")
       document.frmciudad.pais.focus()
       return false;
    }
	
	if (document.frmciudad.estados.selectedIndex==0){
       alert("Debe seleccionar un Estado ")
       document.frmciudad.estados.focus()
       return false;
    }
	
	if (document.frmciudad.municipios.selectedIndex==0){
       alert("Debe seleccionar un Estado ")
       document.frmciudad.municipios.focus()
       return false;
    }
	
	if (document.frmciudad.denominacion.value.length==0){
		alert("Tiene que escribir una Denominaci&oacute;n para el Municipio")
		document.frmciudad.denominacion.focus()
		return false;
	}

} 
</SCRIPT>
</head>
	<body>
	<br>
	<h4 align=center>Ciudad</h4>
	<h2 class="sqlmVersion"></h2>
		<form name="frmciudad" action="principal.php?modulo=1&accion=<?=$_GET["accion"]?>" method="POST" onSubmit="return valida_envia()">	
	<table align=center cellpadding=2 cellspacing=0>
	<?php
		if ($_GET["accion"] == 203 || $_GET["accion"] == 59){ //entro en modo para agregar
		?>
			<tr>
			<td align='right' class='viewPropTitle'>Pais:</td>
			<td class='viewProp'>
				<select name=pais id=pais onChange="cargaContenido(this.id)">
					<option>&nbsp;</option>
					<?php
						
							while($rowgru = mysql_fetch_array($paiss)) 
								{ 
								?>
									<option value="<?php echo $rowgru["idpais"];?>" ><?php echo $rowgru["denominacion"];?></option>
					<?php
								}
					?>
				</select>
                
                &nbsp;<a href="principal.php?modulo=9&accion=59"><img src="imagenes/nuevo.png" border="0" title="Nueva Ciudad"></a> 
			</td>
			</tr>
			<tr>
			<td align='right' class='viewPropTitle'>Estado:</td>
			<td class='viewProp'>
				<select disabled=disabled name=estados id=estados>
						<option value="0">Selecciona opci&oacute;n...</option>
				</select>
			</td>
			</tr>
			<tr>
			<td align='right' class='viewPropTitle'>Municipio:</td>
			<td class='viewProp'>
				<select disabled=disabled name=municipios id=municipios>
						<option value="0">Selecciona opci&oacute;n...</option>
				</select>
			</td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" maxlength="45" size="30"></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>C&oacute;digo Postal:</td>
				<td class='viewProp'><input type="text" name="codigopostal" maxlength="4" size="4"></td>
			</tr>
		<?php
		}
		if ($_GET["accion"] == 204 || $_GET["accion"] == 205){ //entro en modo para modificar o eliminar
		?>
			<input type="hidden" name="tabla" value="3">
			<input type="hidden" name="user" value="<?php echo $registro_usuario["login"];?>">
			<input type="hidden" name="id_ciudad" value="<?php echo $registro_actualizar["idciudad"];?>">
			<tr>
			<td align='right' class='viewPropTitle'>Pais:</td>
			<td class='viewProp'>
				<select name=pais disabled>
					<option>&nbsp;</option>
					<?php
						while($rowgru = mysql_fetch_array($paiss)) 
							{ 
								?>
									<option value="<?php echo $rowgru["idpais"];?>"<?php if ($pais_seleccionado==$rowgru["idpais"]){echo "selected";}?>><?php echo $rowgru["denominacion"];?></option>
					<?php
							}
					?>
				</select>
                
                 &nbsp;<a href="principal.php?modulo=9&accion=59"><img src="imagenes/nuevo.png" border="0" title="Nueva Ciudad"></a>  
			</td>
			</tr>
			<td align='right' class='viewPropTitle'>Estado:</td>
			<td class='viewProp'>
				<select name=estados disabled>
					<option>&nbsp;</option>
					<?php
						while($rowedo = mysql_fetch_array($estado)) 
							{ 
								?>
									<option value="<?php echo $rowedo["idestado"];?>"<?php if ($estado_seleccionado==$rowedo["idestado"]){echo "selected";}?>><?php echo $rowedo["denominacion"];?></option>
					<?php
							}
					?>
				</select> 
			</td>
			</tr>
			<tr>
			<td align='right' class='viewPropTitle'>Municipio:</td>
			<td class='viewProp'>
				<select name=municipio disabled>
					<option>&nbsp;</option>
					<?php
						while($rowmuni = mysql_fetch_array($municipio)) 
							{ 
								?>
									<option value="<?php echo $rowmuni["idmunicipios"];?>"<?php if ($municipio_seleccionado==$rowmuni["idmunicipios"]){echo "selected";}?>><?php echo $rowmuni["denominacion"];?></option>
					<?php
							}
					?>
				</select> 
			</td>
			</tr>
			
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" value="<?php echo $registro_actualizar["denominacion"];?>" maxlength="45" size="30" <?php if ($_GET["accion"] == 205) echo"disabled"?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>C&oacute;digo Postal:</td>
				<td class='viewProp'><input type="text" name="codigopostal" value="<?php echo $registro_actualizar["codigo_postal"];?>" maxlength="4" size="4" <?php if ($_GET["accion"] == 205) echo"disabled"?>></td>
			</tr>
		<?php
		}
	?>
		
	</table>
	
	</table>
	<table align=center cellpadding=2 cellspacing=0>
		<tr><td>
<?php

					if($_GET["accion"] != 204 and $_GET["accion"] != 205 and in_array(203, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 204 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 205 and in_array($_GET["accion"], $privilegios) == true){
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
			<td class='viewProp'><input type="text" name="textoabuscar" maxlength="30" size="20"></td>
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
						<table class="Browse" cellpadding="0" cellspacing="0" width="75%" align="center">
							<thead>
								<tr>
									<td align="center" class="Browse">Pais</td>
									<td align="center" class="Browse">Estado</td>
									<td align="center" class="Browse">Municipio</td>
									<td align="center" class="Browse">Ciudad</td>
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
									echo "<td align='left' class='Browse'>".$llenar_grilla["denopais"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["denoedo"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["denomuni"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["denominacion"]."</td>";
									$c=$llenar_grilla["idciudad"];
									if(in_array(204, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=9&accion=204&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(205, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=9&accion=205&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmciudad.pais.focus() </script>
</body>
</html>
<?php
if($_POST){
		$codigo_ciudad=$_POST["id_ciudad"];
		$denominacion=strtoupper($_POST["denominacion"]);
		$codigo_postal=strtoupper($_POST["codigopostal"]);
		$idmunicipio=$_POST["municipios"];
		$idpais=$_POST["pais"];
		$idestado=$_POST["estados"];
	
	if($_GET["accion"] == 203 and in_array(203, $privilegios) == true){
		$busca_existe_registro=mysql_query("select * from ciudad 
														where denominacion = '".$denominacion."'
														and idmunicipio = '".$idmunicipio."'
														and status = 'a'"
															,$conexion_db);
		if (mysql_num_rows($busca_existe_registro)>0){
			mensaje("Disculpe el regsitro que ingreso ya existe, Vuelva a Intentarlo");
			redirecciona("principal.php?modulo=9&accion=59");
		}else{
			mysql_query("insert into ciudad 
									(idmunicipios,denominacion,codigo_postal,usuario,fechayhora,status) 
								values ('$idmunicipio','$denominacion','$codigo_postal','$login','$fh','a')"
									,$conexion_db);
			registra_transaccion('Ingresar Ciudades ('.$denominacion.')',$login,$fh,$pc,'ciudad',$conexion_db);
			mensaje("El registro se Inserto con Exito");
			redirecciona("principal.php?modulo=9&accion=59");

		}
	}
	
	if($_GET["accion"] == 204 and in_array(204, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update ciudad set 	denominacion='".strtoupper($denominacion)."', 
											codigo_postal='".$codigopostal."',
											usuario='".$login."', 
											fechayhora='".$fh."' 
												where idciudad like '$codigo_ciudad'"
													,$conexion_db);	
			registra_transaccion('Modificar Ciudades ('.$denominacion.')',$login,$fh,$pc,'ciudad',$conexion_db);
			mensaje("El registro se Modifico con Exito");
			redirecciona("principal.php?modulo=9&accion=59");
	}
	if($_GET["accion"] == 205 and in_array(205, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from ciudad where idciudad = '$codigo_ciudad'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from ciudad where idciudad = '$codigo_ciudad'"
													,$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Ciudades (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'ciudad',$conexion_db);
				mensaje("Disculpe el registro que intenta eliminar esta relacionado con otro registro en el sistema, por ello no puede ser eliminado");
				redirecciona("principal.php?modulo=9&accion=59");
			
			}else{
				registra_transaccion('Eliminar Ciudades ('.$bus["denominacion"].')',$login,$fh,$pc,'ciudad',$conexion_db);
				mensaje("El registro se Eliminar con Exito");
				redirecciona("principal.php?modulo=9&accion=59");
			
			}
	}
}
?>
