<?php
if($_POST["ingresar"]){
$_GET["accion"] = 167;
}


$buscar_registros=$_GET["busca"];
$idtrabajador_seleccionado=$_GET["id"]; //variable pasada para los datos del trabajador hasta que se escriba otra cedula
$idtrabajador_seleccion2=$_GET["t"]; //variable pasada por la seleccion en la grilla para modificar o eliminar un familiar
$entro_buscar=false;
$existen_registros=1; // switch para validar si hay datos a cargar en la grilla 0 existen 1 no existen


if ($idtrabajador_seleccionado<>"" || $idtrabajador_seleccion2<>""){
	if($idtrabajador_seleccionado<>""){$idtrabajador_buscar=$idtrabajador_seleccionado;} else {$idtrabajador_buscar=$idtrabajador_seleccion2;}
	$sql_validar_id=mysql_query("select * from trabajador 
														where idtrabajador=".$idtrabajador_buscar
															,$conexion_db);
	$regtrabajador=mysql_fetch_assoc($sql_validar_id);
	$c=$regtrabajador["cedula"];
	$id_trabajador=$regtrabajador["idtrabajador"];
	$registros_grilla_carga=mysql_query("select * from carga_familiar where status='a' and idtrabajador=".$id_trabajador,$conexion_db);
	if (mysql_num_rows($registros_grilla_carga)>0)
		{
		$existen_registros=0;
	}

}

$nacionalidad=mysql_query("select * from nacionalidad 
									where status='a'"
										,$conexion_db); // registros para llenar el combo nacionalidad

$parentezco=mysql_query("select * from parentezco 
									where status='a'"
										,$conexion_db); // registros para llenar el combo nacionalidad

//if (isset($_POST["validar_cedula"])){
	if ($_REQUEST["cedula_buscar"]<>"")
	{
		$cedula_validar=$_REQUEST["cedula_buscar"];
		$sql_validar_cedula=mysql_query("select * from trabajador 
														where cedula=".$cedula_validar." and status='a'"
															,$conexion_db);
		if (mysql_num_rows($sql_validar_cedula)>0)
			{
				//header("location:error_rrhh.php?err=9&modo=0&busca=0");
				$regtrabajador=mysql_fetch_assoc($sql_validar_cedula);
				$c=$regtrabajador["cedula"];
				$id_trabajador=$regtrabajador["idtrabajador"];
				$registros_grilla_carga=mysql_query("select * from carga_familiar where status='a' and idtrabajador=".$id_trabajador,$conexion_db); 
				if (mysql_num_rows($registros_grilla_carga)>0)
					{
						$existen_registros=0;
					}
			}
	}
if ($_GET["accion"] == 168 || $_GET["accion"] == 169){
	$result=mysql_query("select * from carga_familiar where idcarga_familiar = '".$_GET['c']."'",$conexion_db);
	$row=mysql_fetch_assoc($result);
	$idcarga_familiar=$_GET['c'];
}
?>
	<script src="modulos/rrhh/js/valida.js" type="text/javascript" language="javascript"></script>
	<script type="text/javascript" src="modulos/rrhh/js/funciones.js"></script>
	
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")

<!--- oculta el script para navegadores antiguos
var miPopUp
var w=0

function abreVentana(){
	miPopup=window.open("lib/listas/lista_trabajador.php?frm=carga_familiar","carga_familiar","width=600,height=400,scrollbars=yes")
	miPopup.focus()
}

function solonumeros(e){
var key;
if(window.event)
	{key=e.keyCode;}
else if(e.which)
	{key=e.which;}
if (key < 48 || key > 57)
	{return false;}
return true;
}
// end hiding from old browsers -->
</SCRIPT>
</head>
	<body>
	<br>
	<h4 align=center>Carga Familiar</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<form action='principal.php?accion=<?=$_GET["accion"]?>&modulo=1' method='POST' enctype="multipart/form-data" name='carga_familiar'>
  <table align=center cellpadding=2 cellspacing=0>
			<tr><td align='right' class='viewPropTitle'>C&eacute;dula:</td>
				<td class=''><input type="text" id="cedula_buscar" name="cedula_buscar" maxlength="12" size="12" onKeyPress="javascript:return solonumeros(event)" <?php if (isset($_REQUEST["cedula_buscar"])){ echo "value=".$_REQUEST["cedula_buscar"];} else { echo "value=".$regtrabajador["cedula"];}?>>
					<button name="validar_cedula" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="this.form.submit()">
                    <img src='imagenes/validar.png' >
					</button>
					<button name="listado" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="abreVentana()"><img src='imagenes/search0.png'>
					</button>
                    &nbsp;<a href="principal.php?modulo=1&accion=17"><img src="imagenes/nuevo.png" border="0" title="Nueva Carga Familiar">
				</td>
			</tr>
		</table>
	<div id="resultado">
		<br>
		<table align=center cellpadding=2 cellspacing=0 width="80%">
			<tr>
				<td align='right' class='viewPropTitle'>Apellidos:</td>
				<td class=''><input type="text" name="apellidos" maxlength="45" size="45" id="apellidos" disabled value="<?php echo $regtrabajador["apellidos"];?>"></td>
				<td align='right' class='viewPropTitle'>Nombres:</td>
				<td class=''><input type="text" name="nombres" maxlength="45" size="45" id="nombres" disabled value="<?php echo $regtrabajador["nombres"];?>"></td>
			</tr>
		</table>
	</div>
	<br>
	<h2 class="sqlmVersion"></h2>
	<br>
	<table align=center cellpadding=2 cellspacing=0 width="80%">
			<tr>
				<td align='right' class='viewPropTitle'>Apellidos:</td>
				<td class=''><input type="text" name="apellidos_familiar" maxlength="45" size="45" id="apellidos_familiar" value="<?php if ($_GET["accion"]==168 || $_GET["accion"]==169) {echo $row["apellidos"];} ?>" <?php if ($modo==2) echo " disabled";?>></td>
				<td align='right' class='viewPropTitle'>Nombres:</td>
				<td class=''><input type="text" name="nombres_familiar" maxlength="45" size="45" id="nombres_familiar" <?php if ($_GET["accion"]==168 || $_GET["accion"]==169) {echo 'value="'.$row['nombres'].'"';} if ($_GET["accion"]==169) echo "disabled";?>></td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Nacionalidad:</td>
				<td class='viewProp'>
				<label><select name="nacionalidad" id="nacionalidad" style="width:55%">
					<option>&nbsp;</option>
					<?php
						while($regnacionalidad = mysql_fetch_array($nacionalidad))
							{ ?>
								<option <?php echo "value=".$regnacionalidad["idnacionalidad"]; if ($_GET["accion"]==168 || $_GET["accion"]==169) {if ($regnacionalidad['idnacionalidad']==$row['idnacionalidad']) {echo " selected";}} ?> > <?php echo $regnacionalidad["indicador"]." ".$regnacionalidad["denominacion"]; echo "</option>";
							} ?>
				</select> </label>
				<img src='imagenes/add.png' onClick="window.open('principal.php?modulo=9&accion=60&pop=si','agregar nacionalidad','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">				</td>
				<td align='right' class='viewPropTitle'>C&eacute;dula:</td>
				<td class=''><input type="text" id="cedula_familiar" name="cedula_familiar" maxlength="12" size="12" onKeyPress="javascript:return solonumeros(event)" <?php if ($_GET["accion"]==168 || $_GET["accion"]==169) {echo 'value="'.$row['cedula'].'"';} if ($_GET["accion"]==169) echo "disabled";?>></td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Fecha de Nacimiento:</td>
				<td>
				<input name="fecha_nacimiento" type="text" id="fecha_nacimiento" <?php if ($_GET["accion"]==168 || $_GET["accion"]==169) {echo "value=".substr($row['fecha_nacimiento'],8,2)."/".substr($row['fecha_nacimiento'],5,2)."/".substr($row['fecha_nacimiento'],0,4);}  if ($_GET["accion"]==169) echo "disabled";?> size="13" maxlength="10">
					<img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
				  <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_nacimiento",
							button        : "f_trigger_c",
							align         : "Tr",
							ifFormat    	: "%d/%m/%Y"
							});
						</script>				</td>
				<td align='right' class='viewPropTitle'>Sexo:</td>
				<td class=''>
				<select name="sexo" style="width:15%">
					<option VALUE=""></option>
					<option VALUE="F" <?php if ($_GET["accion"]==168 || $_GET["accion"]==169){ if ($row['sexo']=="F") echo "selected";}  ?>>F</option>
					<option VALUE="M" <?php if ($_GET["accion"]==168 || $_GET["accion"]==169){ if ($row['sexo']=="M") echo "selected";}  ?>>M</option>
				</select> </td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Parentezco:</td>
				<td class='viewProp'>
				<label><select name="parentezco" id="parentezco" style="width:55%">
					<option>&nbsp;</option>
					<?php
						while($regparentezco = mysql_fetch_array($parentezco))
							{ ?>
								<option <?php echo "value=".$regparentezco["idparentezco"]; if ($_GET["accion"]==168 || $_GET["accion"]==169) {if ($regparentezco['idparentezco']==$row['idparentezco']) {echo " selected";}} ?> > <?php echo $regparentezco["denominacion"]; echo "</option>";
							} ?>
				</select> </label>
				<img src='imagenes/add.png' onClick="window.open('principal.php?modulo=1&accion=26&pop=si','agregar parentezco','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">				</td>
				<td align='right' class='viewPropTitle'>Constancia:</td>
				<td><input type ="checkbox" name ="constancia" <?php if ($_GET["accion"]==168 || $_GET["accion"]==169) {if ($regparentezco['flag_constancia']=="1") echo " checked";} if ($_GET["accion"]==169) echo "disabled";?>></td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Direcci&oacute;n:</td>
				<td class='' colspan="3"><textarea name="direccion_familiar" cols="125" id="direccion_familiar" 
				<? if ($_get["accion"]==169) 
					echo "disabled";?>
                 ><?=$row['direccion']?></textarea>				</td>
			</tr>
			<tr>
			  <td align='right' class='viewPropTitle'>Telef&oacute;no: </td>
			  <td class=''><input type="text" name="telefono_familiar" maxlength="25" size="30" id="telefono_familiar" <?php if ($_GET["accion"]==168 || $_GET["accion"]==169) {echo 'value="'.$row['telefono'].'"';} if ($_GET["accion"]==169) echo "disabled";?>></td>
			  <td align='right' class='viewPropTitle'>Ocupaci&oacute;n: </td>
			  <td class=''><input type="text" name="ocupacion_familiar" maxlength="55" size="55" id="ocupaci&oacute;n_familiar" <?php if ($_GET["accion"]==168 || $_GET["accion"]==169) {echo 'value="'.$row['ocupacion'].'"';} if ($_GET["accion"]==169) echo "disabled";?>></td>
	  </tr>
			<tr>
				<td align='right' class='viewPropTitle'>Foto:</td>
				<td class=''><label>
				  <input type="file" name="foto" id="foto">
				</label></td>
                <td align='right' class='viewPropTitle'>&nbsp;</td>
				<td class=''>&nbsp;</td>
			</tr>
			<input type="hidden" name="idcarga_familiar" maxlength="25" size="25" id="idcarga_familiar" <?php if ($_GET["accion"]==168 || $_GET["accion"]==169) {echo 'value="'.$row['idcarga_familiar'].'"';}?>>
	</table>
	<br><br>
	<table align=center cellpadding=2 cellspacing=0>
		<tr><td>
			<?php

					if($_GET["accion"] != 168 and $_GET["accion"] != 169 and in_array(167, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 168 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 169 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
			?>
			<input type="reset" value="Reiniciar" class="button">

		</td></tr>
	</table>
	<?php
	if ($c<>""){ ?>
		<script> document.carga_familiar.apellidos_familiar.focus() </script>
	<?php }else{?>
		<script> document.getElementById("cedula_buscar").focus() </script>
	<?php } ?>
	</form>
	<br><br>
	<h2 class="sqlmVersion"></h2>	
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<form name="grilla" action="" method="POST">
						<table class="Browse" cellpadding="0" cellspacing="0" width="80%" align=center>
							<thead>
								<tr>
									<!--<td class="Browse">&nbsp;</td>-->
                                    <td align="center" class="Browse">Imagen</td>
									<td align="center" class="Browse">C&eacute;dula</td>
									<td align="center" class="Browse">Apellidos</td>
									<td align="center" class="Browse">Nombres</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							
							<?php
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla_carga)) 
								{ 
								?>
								<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                <td align='center' class='Browse' width='20%'>
                                <a href="javascript:;" onClick="document.getElementById('imagen_<?=$llenar_grilla["idcarga_familiar"]?>').style.display= 'block'">Ver Imagen</a>
                                <div style=" position:absolute; background-color:#CCCCCC; display:none; width:600px; height:400px; border:#666666 solid 1px" id="imagen_<?=$llenar_grilla["idcarga_familiar"]?>">
                                <div align="right" style="cursor:pointer"><strong onClick="document.getElementById('imagen_<?=$llenar_grilla["idcarga_familiar"]?>').style.display= 'none'">Cerrar</strong></div>
                                <img src="modulos/rrhh/imagenes/carga_familiar/<?=$llenar_grilla["foto"]?>">
                                </div>
                                </td>
                                <td align='right' class='Browse' width='20%'>
								
								<?
                                if($llenar_grilla["cedula"]!= ""){
									echo $llenar_grilla["cedula"];	
								}else{
									echo "<center><strong>Sin Nro. de Cedula</strong></center>";	
								}
								?></td>
								<?
								echo "<td align='left' class='Browse'>".$llenar_grilla["apellidos"]."</td>";
								echo "<td align='left' class='Browse'>".$llenar_grilla["nombres"]."</td>";
								$c=$llenar_grilla["idcarga_familiar"];
								$t=$llenar_grilla["idtrabajador"];
								if(in_array(168, $privilegios) == true){
									echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=168&c=$c&t=$t&busca=0' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
								}
								if(in_array(169, $privilegios) == true){
									echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=169&&c=$c&t=$t&busca=0' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
	<br>
</body>
</html>
<?php
if($_POST){
extract($_POST);
	$idcarga_familiar=$_POST["idcarga_familiar"];
	$cedula=$_POST["cedula_buscar"];
	$apellidos=strtoupper($_POST["apellidos_familiar"]);
	$nombres=strtoupper($_POST["nombres_familiar"]);
	$idnacionalidad=$_POST["nacionalidad"];
	$idparentezco=$_POST["parentezco"];
	$cedula_familiar=$_POST["cedula_familiar"];
	$sexo=$_POST["sexo"];
	$fecha_nacimiento=substr($_POST["fecha_nacimiento"],6,4)."/".substr($_POST["fecha_nacimiento"],3,2)."/".substr($_POST["fecha_nacimiento"],0,2);
	$direccion=$_POST["direccion_familiar"];
	$telefono=$_POST["telefono_familiar"];
	$ocupacion=$_POST["ocupacion_familiar"];


	
if($_GET["accion"] == 167 and in_array(167, $privilegios) == true){
		$vali=mysql_query("select * from carga_familiar where cedula like '".$_POST['cedula_familiar']."' and idtrabajador='".$id_trabajador."' and apellidos='".$_POST['apellidos_familiar']."' and status='a'",$conexion_db);
		if (mysql_num_rows($vali)>0 and $_POST["cedula_familiar"] != ""){
			mensaje("Disculpe el registro que Inserto ya Existe, Vuelva a Intentarlo");
			redirecciona("principal.php?modulo=1&accion=17");
		}else{
		
		if($_FILES["foto"]["name"] != ""){	
			$tipo = substr($_FILES['foto']['type'], 0, 5);
			$dir = 'modulos/rrhh/imagenes/carga_familiar/';
			if ($tipo == 'image') {
			$nombre_imagen = $_FILES['foto']['name'];
			while(file_exists($dir.$nombre_imagen)){
				$partes_img = explode(".",$nombre_imagen);
				$nombre_imagen = $partes_img[0].rand(0,1000000).".".$partes_img[1];
			}
			if (copy($_FILES['foto']['tmp_name'], $dir.$nombre_imagen)){
			$pic = $dir.$nombre_imagen;	
				$result=mysql_query("insert into carga_familiar 
													(idtrabajador,
													idparentezco,
													cedula,
													apellidos,
													nombres,
													fecha_nacimiento,
													flag_constancia,
													usuario,
													status,
													fechayhora,
													sexo,
													direccion,
													telefono,
													ocupacion,
													idnacionalidad,
													estacion,
													foto) 
											values 
													($id_trabajador,
													$idparentezco,
													'$cedula_familiar',
													'$apellidos',
													'$nombres',
													'$fecha_nacimiento',
													'$flag_constancia',
													'$login',
													'a',
													'$fh',
													'$sexo',
													'$direccion',
													'$telefono',
													'$ocupacion',
													$idnacionalidad,
													'$pc',
													'$nombre_imagen')",
											$conexion_db)or die($pic." --- ".mysql_error());
				registra_transaccion('Ingresar Carga Familiar ('.$apellidos.' '.$nombres.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				mensaje("El registro se Inserto con exito");
				redirecciona("principal.php?modulo=1&accion=17&cedula_buscar=".$_REQUEST["cedula_buscar"]."");
			}else{
				mensaje("Disculpe no se pudo registrar la imagen, por lo tanto no se registraron los datos");
				redirecciona("principal.php?modulo=1&accion=17&cedula_buscar=".$_REQUEST["cedula_buscar"]."");
			}
			
		}else{
			mensaje("Disculpe el archivo que intenta cargar parece no ser una imagen valida, por favor intente de nuevo");
			redirecciona("principal.php?modulo=1&accion=17&cedula_buscar=".$_REQUEST["cedula_buscar"]."");
		}
		
			
			}else{
				
				$result=mysql_query("insert into carga_familiar 
													(idtrabajador,
													idparentezco,
													cedula,
													apellidos,
													nombres,
													fecha_nacimiento,
													flag_constancia,
													usuario,
													status,
													fechayhora,
													sexo,
													direccion,
													telefono,
													ocupacion,
													idnacionalidad,
													estacion) 
											values 
													($id_trabajador,
													$idparentezco,
													'$cedula_familiar',
													'$apellidos',
													'$nombres',
													'$fecha_nacimiento',
													'$flag_constancia',
													'$login',
													'a',
													'$fh',
													'$sexo',
													'$direccion',
													'$telefono',
													'$ocupacion',
													$idnacionalidad,
													'$pc')",
											$conexion_db);
				registra_transaccion('Ingresar Carga Familiar ('.$apellidos.' '.$nombres.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				mensaje("El regisrto se Inserto con exito");
				redirecciona("principal.php?modulo=1&accion=17&cedula_buscar=".$_REQUEST["cedula_buscar"]."");
				
			}
		}
	}
	if ($_GET["accion"] == 168 and in_array(168,$privilegios) == true and !$_POST["buscar"]){
			mysql_query("update carga_familiar set 
												apellidos='".$apellidos."',
												nombres='".$nombres."',
												idparentezco='".$idparentezco."',
												cedula='".$cedula_familiar."',
												fecha_nacimiento='".$fecha_nacimiento."',
												flag_constancia='".$flag_constancia."',
												usuario='".$login."',
												fechayhora='".$fh."',
												sexo='".$sexo."',
												direccion='".$direccion."',
												telefono='".$telefono."',
												ocupacion='".$ocupacion."',
												idnacionalidad='".$idnacionalidad."',
												estacion='".$pc."' 
										where idcarga_familiar = '$idcarga_familiar'"
												,$conexion_db);	
			registra_transaccion('Modificar Carga Familiar ('.$apellidos.' '.$nombres.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
			mensaje("El Registro se Modifico con Exito");
			redirecciona("principal.php?modulo=1&accion=17&cedula_buscar=".$_REQUEST["cedula_buscar"]."");
	}
	if ($_GET["accion"] == 169 and in_array(169, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from carga_familiar where idcarga_familiar = '$idcarga_familiar'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from carga_familiar where idcarga_familiar = '$idcarga_familiar'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Carga Familiar (ERROR) ('.$bus["apellidos"].' '.$bus["nombres"].')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				mensaje("Disculpe el registro que intenta eliminar esta relacionado con otro registro, por ello no puede ser eliminado");
				redirecciona("principal.php?modulo=1&accion=17&cedula_buscar=".$_REQUEST["cedula_buscar"]."");			
			}else{
				registra_transaccion('Eliminar Carga Familiar ('.$bus["apellidos"].' '.$bus["nombres"].')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				mensaje("El Registro se Elimino con Exito");
				redirecciona("principal.php?modulo=1&accion=17&cedula_buscar=".$_REQUEST["cedula_buscar"]."");			
			}

	}
}
?>
