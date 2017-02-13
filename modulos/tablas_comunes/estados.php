<?php
if($_POST["ingresar"]){
$_GET["accion"] = 209;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select 	pais.denominacion as denopais, 
											estado.codigo,
											estado.denominacion,
											estado.idestado
											from pais,estado 
												where estado.status='a'
												and pais.idpais = estado.idpais"
													,$conexion_db)or die(mysql_error());
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$campo_busqueda=$_POST["tipobusqueda"];
	$sql="select pais.denominacion as denopais, 
					estado.codigo,
					estado.denominacion,
					estado.idestado
					from pais,estado 
						where estado.status='a'
							and pais.idpais = estado.idpais";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			$registros_grilla=mysql_query($sql." and estado.denominacion like '$texto_buscar%'",$conexion_db);
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}
// selecciona los registros de pais para desplegarlos en combobox 
$paiss=mysql_query("select * from pais 
									where status='a'"
										,$conexion_db);

if ($_GET["accion"] == 210 || $_GET["accion"] == 211){
	$sql=mysql_query("select * from estado 
									where idestado like '".$_GET['c']."'"
										,$conexion_db);
	$registro_actualizar=mysql_fetch_assoc($sql);
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos

function valida_envia(){
    
	//valido el grupo
    if (document.frmestado.pais.selectedIndex==0){
       alert("Debe seleccionar un pais para la Serie.")
       document.frmestado.pais.focus()
       return false;
    }
	
	if (document.frmestado.denominacion.value.length==0){
		alert("Tiene que escribir una Denominaci&oacute;n para el estado")
		document.frmestado.denominacion.focus()
		return false;
	}

    //document.serie.submit();
} 
</SCRIPT>

	<body>
	<br>
	<h4 align=center>Estados</h4>
	<h2 class="sqlmVersion"></h2>
	
		<form name="frmestado" action="principal.php?modulos=9&accion=<?=$_GET["accion"]?>" method="POST" onSubmit="return valida_envia()">	
	
	<table align=center cellpadding=2 cellspacing=0>
	<?php
		if ($_GET["accion"] == 209 || $_GET["accion"] == 57){ //entro en modo para agregar
		?>
			<tr>
			<td align='right' class='viewPropTitle'>Pais:</td>
			<td class='viewProp'>
				<select name=pais>
					<option>&nbsp;</option>
					<?php
						while($rowgru = mysql_fetch_array($paiss)) 
							{ 
								?>
									<option value="<?php echo $rowgru["idpais"];?>"><?php echo $rowgru["idpais"]." ".$rowgru["denominacion"];?></option>
					<?php
							}
					?>
				</select>
                 &nbsp;<a href="principal.php?modulo=9&accion=57"><img src="imagenes/nuevo.png" border="0" title="Nuevo Estado"></a>
                 
			</td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" maxlength="30" size="30"></td>
			</tr>
            <tr>
            	<td align='right' class='viewPropTitle'>Codigo</td>
				<td class='viewProp'><input type="text" name="codigo" maxlength="30" size="30"></td>
			</tr>
		<?php
		}
		if ($_GET["accion"] == 210 || $_GET["accion"] == 211){ //entro en modo para modificar o eliminar
		?>
			<input type="hidden" name="tabla" value="1">
			<input type="hidden" name="user" value="<?php echo $registro_usuario["login"];?>">
			<input type="hidden" name="id_estado" value="<?php echo $registro_actualizar["idestado"];?>">
			<tr>
			<td align='right' class='viewPropTitle'>Pais:</td>
			<td class='viewProp'>
				<select name=pais>
					<option>&nbsp;</option>
					<?php
						while($rowgru = mysql_fetch_array($paiss)) 
							{ 
								?>
									<option value="<?php echo $rowgru["idpais"];?>"<?php if ($registro_actualizar["idpais"]==$rowgru["idpais"]){echo "selected";}?>><?php echo $rowgru["idpais"]." ".$rowgru["denominacion"];?></option>
					<?php
							}
					?>
				</select>
                
                &nbsp;<a href="principal.php?modulo=9&accion=57"><img src="imagenes/nuevo.png" border="0" title="Nuevo Estado"></a> 
			</td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" value="<?php echo $registro_actualizar["denominacion"];?>" maxlength="30" size="30" <?php if ($_GET["accion"] == 211) echo"disabled"?>></td>
			</tr>
            <tr>
              <td align='right' class='viewPropTitle'>Codigo:</td>
				<td class='viewProp'><input type="text" name="codigo" maxlength="30" size="30" value="<?=$registro_actualizar["codigo"]?>"></td>
			</tr>
		<?php
		}
	?>
		
	</table>
	</table>
	<table align=center cellpadding=2 cellspacing=0>
		<tr><td>
<?php

					if($_GET["accion"] != 210 and $_GET["accion"] != 211 and in_array(209, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 210 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 211 and in_array($_GET["accion"], $privilegios) == true){
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
	
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<form name="grilla" action="" method="POST">
						<table class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
							<thead>
								<tr>
									<td align="center" class="Browse">Codigo</td>
                                    <td align="center" class="Browse">Pais</td>
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
									echo "<td align='center' class='Browse'>&nbsp;".$llenar_grilla["codigo"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["denopais"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["denominacion"]."</td>";
									$c=$llenar_grilla["idestado"];
									if(in_array(210, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=9&accion=210&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(211, $privilegios) == true){
										echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=9&accion=211&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmestado.pais.focus() </script>
</body>
</html>
<?php
if($_POST){
		$codigo_estado=$_POST["id_estado"];
		$codigo=$_POST["idestado"];
		$denominacion=strtoupper($_POST["denominacion"]);
		$idpais=$_POST["pais"];
		$cod = $_POST["codigo"];
		if($_GET["accion"]== 209 and in_array(209, $privilegios) == true){
		$busca_existe_registro=mysql_query("select * from estado 
														where denominacion = '".$denominacion."'  
														and idpais = $idpais
														and status = 'a'"
															,$conexion_db);
		if (mysql_num_rows($busca_existe_registro)>0){
			mensaje("Disculpe el resgitro que ingreso ya existe, vuelvalo a intentar");
			redirecciona("principal.php?modulo=9&accion=57");
		}else{
			mysql_query("insert into estado 
									(idpais,denominacion,usuario,fechayhora,status, codigo) 
								values ('$idpais','$denominacion','$login','$fh','a', '".$cod."')"
									,$conexion_db);
			registra_transaccion('Ingresar Estados ('.$denominacion.')',$login,$fh,$pc,'estado',$conexion_db);
			mensaje("El registro se Ingreso con Exito");
			redirecciona("principal.php?modulo=9&accion=57");

		}
	}
	if($_GET["accion"] == 210 and in_array(210, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update estado 	set denominacion='".strtoupper($denominacion)."', 
										usuario='".$login."', 
										fechayhora='".$fh."', 
										idpais='".$idpais."',
										codigo='".$cod."' 
										where idestado like '$codigo_estado' 
											and status='a'",$conexion_db);	
			registra_transaccion('Modificar Estado ('.$denominacion.')',$login,$fh,$pc,'estado',$conexion_db);													
			mensaje("El registro se Modifico con Exito");
			redirecciona("principal.php?modulo=9&accion=57");
	}
	if($_GET["accion"] == 211 and in_array(211, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from estado where idestado = '$codigo_estado'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from estado where idestado = '$codigo_estado'"
													,$conexion_db);	
			if(!$sql_eliminar){
			registra_transaccion('Eliminar Estado (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'estado',$conexion_db);
			mensaje("Disculpe el registro que intenta eliminar esta relacionado con otro registro dentro del distema, por ello no puede ser eliminado");
			redirecciona("principal.php?modulo=9&accion=57");
			
			}else{
			registra_transaccion('Eliminar Estado ('.$bus["denominacion"].')',$login,$fh,$pc,'estado',$conexion_db);
			mensaje("El registro se Elimino con Exito");
			redirecciona("principal.php?modulo=9&accion=57");
			
			}
	}
}

?>
