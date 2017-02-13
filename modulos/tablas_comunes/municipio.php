<?php

if($_POST["ingresar"]){
$_GET["accion"] = 212;
}

$ls=array(
"pais"=>"pais",
"estados"=>"estado"
);
$_SESSION['listadoSelect']=serialize($ls);



if ($buscar_registros==0){
	$registros_grilla=mysql_query("select 	pais.denominacion as denopais, 
											estado.denominacion as denoedo, 
											municipios.denominacion,
											municipios.idmunicipios
											from pais,estado,municipios 
												where municipios.status='a'
												and pais.idpais = estado.idpais
												and estado.idestado = municipios.idestado"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select pais.denominacion as denopais, 
					estado.denominacion as denoedo, 
					municipios.denominacion,
					municipios.idmunicipios
					from pais,estado,municipios 
							where municipios.status='a'
							and pais.idpais = estado.idpais
							and estado.idestado = municipios.idestado";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			$registros_grilla=mysql_query($sql." and municipios.denominacion like '$texto_buscar%'",$conexion_db);
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

if ($_GET["accion"] == 213 || $_GET["accion"] == 214){
	$sql=mysql_query("select * from municipios 
									where idmunicipios like '".$_GET['c']."'"
										,$conexion_db);
	$registro_actualizar=mysql_fetch_assoc($sql);
	
	$paiss=mysql_query("select * from pais 
									where status='a'"
										,$conexion_db);
	
	$estado= mysql_query("select 	estado.idpais as idpais, 
									estado.idestado as idestado, 
									estado.denominacion as denominacion
									from estado,pais
									where estado.status='a'
									and estado.idpais = estado.idpais"
										,$conexion_db);
	$registro_estado=mysql_fetch_assoc($estado);
	$pais_seleccionado=$registro_estado["idpais"];
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
var listadoSelects=new Array();
listadoSelects[0]="pais";
listadoSelects[1]="estados";
//listadoSelects[2]="select3";
function valida_envia(){
    
	//valido el grupo
    if (document.frmmunicipio.pais.selectedIndex==0){
       alert("Debe seleccionar un pais para el Estado")
       document.frmmunicipio.pais.focus()
       return false;
    }
	
	if (document.frmmunicipio.estado.selectedIndex==0){
       alert("Debe seleccionar un Estado ")
       document.frmmunicipio.estado.focus()
       return false;
    }
	
	if (document.frmmunicipio.denominacion.value.length==0){
		alert("Tiene que escribir una Denominaci&oacute;n para el Municipio")
		document.frmmunicipio.denominacion.focus()
		return false;
	}

} 
</SCRIPT>
	<body>
	<br>
	<h4 align=center>Municipios</h4>
	<h2 class="sqlmVersion"></h2>
		<form name="frmmunicipio" action="municipio.php" method="POST" onSubmit="return valida_envia()">	
	
	<table align=center cellpadding=2 cellspacing=0>
	<?php
		if ($_GET["accion"] == 212 || $_GET["accion"] == 58){ //entro en modo para agregar
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
                &nbsp;<a href="principal.php?modulo=9&accion=58"><img src="imagenes/nuevo.png" border="0" title="Nuevo Municipio"></a>
                 
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
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" maxlength="45" size="30"></td>
			</tr>
		<?php
		}
		if ($_GET["accion"] == 213 || $_GET["accion"] == 214){ //entro en modo para modificar o eliminar
		?>
			<input type="hidden" name="tabla" value="2">
			<input type="hidden" name="user" value="<?php echo $registro_usuario["login"];?>">
			<input type="hidden" name="id_municipios" value="<?php echo $registro_actualizar["idmunicipios"];?>">
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
                
                 &nbsp;<a href="principal.php?modulo=9&accion=58"><img src="imagenes/nuevo.png" border="0" title="Nuevo Municipio"></a>
                 
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
									<option value="<?php echo $rowedo["idestado"];?>"<?php if ($registro_actualizar["idestado"]==$rowedo["idestado"]){echo "selected";}?>><?php echo $rowedo["denominacion"];?></option>
					<?php
							}
					?>
				</select> 
			</td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" value="<?php echo $registro_actualizar["denominacion"];?>" maxlength="45" size="30" <?php if ($_GET["accion"] == 214) echo"disabled"?>></td>
			</tr>
		<?php
		}
	?>
		
	</table>
	</table>
	<table align=center cellpadding=2 cellspacing=0>
		<tr><td>
<?php

					if($_GET["accion"] != 213 and $_GET["accion"] != 214 and in_array(212, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 213 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 214 and in_array($_GET["accion"], $privilegios) == true){
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
									echo "<td align='left' class='Browse'>".$llenar_grilla["denominacion"]."</td>";
									$c=$llenar_grilla["idmunicipios"];
									if(in_array(213, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=9&accion=213&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(214, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=9&accion=214&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmmunicipio.pais.focus() </script>
</body>
</html>
<?php
if($_POST){
		$codigo_municipio=$_POST["id_municipios"];
		$codigo=$_POST["idestado"];
		$denominacion=strtoupper($_POST["denominacion"]);
		$idpais=$_POST["pais"];
		$idestado=$_POST["estados"];
	if($_GET["accion"] == 212 and in_array(212, $privilegios) == true){
		$busca_existe_registro=mysql_query("select * from municipios 
														where denominacion = '".$denominacion."'
														and idpais = '".$idpais."'
														and idestado = '".$idestado."'
														and status = 'a'"
															,$conexion_db);
		if (mysql_num_rows($busca_existe_registro)>0){
			mensaje("Disculpe el regsitro que ingreso ya existe, Vuelva a Intentarlo");
			redirecciona("principal.php?modulo=9&accion=58");
		}else{
			mysql_query("insert into municipios 
									(idestado,denominacion,usuario,fechayhora,status) 
								values ('$idestado','$denominacion','$login','$fh','a')"
									,$conexion_db);
			registra_transaccion('Ingresar Municipio ('.$denominacion.')',$login,$fh,$pc,'municipios',$conexion_db);
			mensaje("El regsirto se Ingreso con Exito");
			redirecciona("principal.php?modulo=9&accion=58");

		}
	}
	
	if($_GET["accion"] == 213 and in_array(213, $privilegios) == true and !$_POST["buscar"]){

			mysql_query("update municipios set 	denominacion='".strtoupper($denominacion)."', 
											usuario='".$login."', 
											fechayhora='".$fh."' 
												where idmunicipios like '$codigo_municipio'
													and status='a'"
													,$conexion_db);	
			registra_transaccion('Modificar Municipios ('.$denominacion.')',$login,$fh,$pc,'municipio',$conexion_db);
			mensaje("El regsirto se Modifico con Exito");
			redirecciona("principal.php?modulo=9&accion=58");
	}
	
	if($_GET["accion"] == 214 and in_array(214, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from municipios where idmunicipios = '$codigo_municipio'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from municipios where idmunicipios = '$codigo_municipio'"
													,$conexion_db);	
			if(!$sql_eliminar){
			registra_transaccion('Eliminar Municipios (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'municipio',$conexion_db);
			mensaje("Disculpe el registro que intenta eliminar esta relacionado con otro registro en el sistema, por ello no puede ser eliminado");
			redirecciona("principal.php?modulo=9&accion=58");	
			
			}else{
			registra_transaccion('Eliminar Municipios ('.$bus["denominacion"].')',$login,$fh,$pc,'municipio',$conexion_db);
			mensaje("El regsirto se Elimino con Exito");
			redirecciona("principal.php?modulo=9&accion=58");	
			
			}
	}
}
?>
