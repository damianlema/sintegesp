<?php
ob_start();
session_start();
include_once("../../conf/conex.php");
include("../../funciones/funciones.php");
$conexion_db=conectarse();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>

	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>

<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos

function valida_envia(){
   
	//valido los campos del usuario
    if (document.usuario.cedula.value.length==0){
       alert("Debe escribir un numero de Cedula.")
       document.usuario.cedula.focus()
       return false;
    }
	
	if (document.usuario.apellidos.value.length==0){
       alert("Debe escribir un Apellido(s) para el Usuario")
       document.usuario.apellidos.focus()
       return false;
    }
	
	if (document.usuario.nombres.value.length==0){
       alert("Debe escribir un Nombre(s) para el Usuario.")
       document.usuario.nombres.focus()
       return false;
    }
	
	if (document.usuario.newlogin.value.length==0){
		alert("Tiene que escribir un Nombre de Usuario o Login que identifique al Usuario")
		document.usuario.newlogin.focus()
		return false;
	}
	
	if (document.usuario.clave.value.length==0){
		alert("Debe escribir una Clave para el ingreso del Usuario al Sistema")
		document.usuario.clave.focus()
		return false;
	}

	if (document.usuario.clave2.value.length==0){
		alert("Debe confirmar la Clave para el ingreso del Usuario al Sistema")
		document.usuario.clave2.focus()
		return false;
	}

	if (document.usuario.clave2.value!=document.usuario.clave.value){
		alert("Las Claves ingresadas son diferentes, por favor verifique")
		document.usuario.clave.focus()
		return false;
	}	
	
	if (document.usuario.pregunta.value.length==0){
		alert("Tiene que escribir una Pregunta en caso de que olvide su clave")
		document.usuario.pregunta.focus()
		return false;
	}
	
	if (document.usuario.respuesta.value.length==0){
		alert("Tiene que escribir una Respuesta a la Pregunta Secreta en caso de que olvide su clave")
		document.usuario.respuesta.focus()
		return false;
	}
    //document.usuario.submit();
} 

// end hiding from old browsers -->
</SCRIPT>
</head>
	
	<body>
	<table align=left cellpadding=2 cellspacing=0>
		<tr>
			<?php if ($registro_nuevo<>"0"){ ?>
				<td align='center'><a HREF='#' onClick="window.close();">Volver a Acceso</a></td>
			<?php } ?>
		</tr>
	</table>
	<br>
	<h4 align=center>Usuarios</h4>
	<h2 class="sqlmVersion"></h2>
		<form name="usuario" action="usuarios.php" method="POST" onSubmit="return valida_envia()">	
	<table align=center cellpadding=2 cellspacing=0>

	<?php
		if ($modo==0){ //entro en modo para agregar
		?>
			<tr><td align='right' class='viewPropTitle'>C&eacute;dula:</td>
				<td class=''><input type="text" name="cedula" maxlength="12" size="12" <?php if (isset($_POST["cedula"])){ echo "value=".$_POST["cedula"]; }?>>
				</td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Apellidos:</td>
				<td class=''><input name="apellidos" type="text" id="apellidos" size="45" maxlength="45" <?php if ($bandera){ echo "value=".$apellidos_trabajador; }?>></td>
	  </tr>
			<tr><td align='right' class='viewPropTitle'>Nombres:</td>
				<td class=''><input type="text" name="nombres" maxlength="45" size="45" <?php if ($bandera){ echo "value=".$nombres_trabajador; }?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Nombre de Usuario:</td>
				<td class=''><input type="text" name="newlogin" maxlength="20" size="20"></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Ingrese su Clave:</td>
				<td class=''><input type="password" name="clave" maxlength="20" size="20"></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Reescriba la Clave:</td>
				<td class=''><input type="password" name="clave2" maxlength="20" size="20"></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Pregunta Secreta:</td>
				<td class=''><input type="text" name="pregunta" maxlength="45" size="45"></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Respuesta:</td>
				<td class=''><input type="text" name="respuesta" maxlength="45" size="45"></td>
			</tr>
		<?php if ($nivelacce=="A"){ ?>
			<tr>
			<td align='right' class='viewPropTitle'>Nivel de acceso:</td>
			<td class=''>
				<select name="nivelacceso" style="width:100%">
					<option VALUE="A">Administrador (Acceso sin restriciones)</option>
					<option VALUE="CMEO">Crear/Modificar/Eliminar(Anular)/Consultar</option>
					<option VALUE="CMO">Crear/Modificar/Consultar</option>
					<option VALUE="MC">Modificar/Consultar</option>
					<option VALUE="O">Consultar</option>
			</select>			</td></tr>
			<tr><td align='right' class='viewPropTitle'>Permisos:</td>
				<td colspan=3>
					<table>
					<tr>
						<td><input type ="checkbox" name ="area[]" value="1">RRHH</td>
						<td><input type ="checkbox" name ="area[]" value="2">Presupuesto</td>
						<td><input type ="checkbox" name ="area[]" value="3">Compromisos</td>
						<td><input type ="checkbox" name ="area[]" value="4">Finanzas</td>
					</tr>
						<td><input type ="checkbox" name ="area[]" value="5">Contabilidad</td>
						<td><input type ="checkbox" name ="area[]" value="6">Almacen</td>
						<td><input type ="checkbox" name ="area[]" value="7">Bienes</td>
						<td><input type ="checkbox" name ="area[]" value="8">Tablas Comunes</td>
					</table>				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			
		<?php
		}
		}
		if ($modo==1 || $modo==2){ //entro en modo para modificar o eliminar
		?>
			<input type="hidden" name="tabla" value="0">
			<input type="hidden" name="user" value="<?php echo $row["login"];?>">
			<input type="hidden" name="c" value="<?php echo $row["cedula"];?>">
			<input type="hidden" name="nuevo" value="<?php echo $registro_nuevo;?>">
			<input type="hidden" name="nivel" value="<?php echo $nivelacce;?>">
			<tr><td align='right' class='viewPropTitle'>C&eacute;dula:</td>
				<td class=''>
				<input type="text" name="cedula" maxlength="12" size="12" value="<?php echo $row["cedula"];?>" disabled></td>
			</tr>
			
			<tr><td align='right' class='viewPropTitle'>Apellidos:</td>
				<td class=''><input type="text" name="apellidos" maxlength="45" size="45" value="<?php echo $row["apellidos"];?>" <?php if ($modo==2){echo "disabled";}?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Nombres:</td>
				<td class=''><input type="text" name="nombres" maxlength="45" size="45" value="<?php echo $row["nombres"];?>" <?php if ($modo==2){echo "disabled";}?> ></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Nombre de Usuario:</td>
				<td class=''><input type="text" name="newlogin" maxlength="20" size="20" value="<?php echo $row["login"];?>" disabled></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Ingrese su Clave:</td>
				<td class=''><input type="password" name="clave" maxlength="20" size="20" value="<?php echo $row["clave"];?>" <?php if ($modo==2){echo "disabled";}?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Reescriba la Clave:</td>
				<td class=''><input type="password" name="clave2" maxlength="20" size="20" value="<?php echo $row["clave"];?>" <?php if ($modo==2){echo "disabled";}?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Pregunta Secreta:</td>
				<td class=''><input type="text" name="pregunta" maxlength="45" size="45" value="<?php echo $row["preguntasecreta"];?>" <?php if ($modo==2){echo "disabled";}?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Respuesta:</td>
				<td class=''><input type="text" name="respuesta" maxlength="45" size="45" value="<?php echo $row["respuestasecreta"];?>"
 <?php if ($modo==2){echo "disabled";}?>></td>
			</tr>
			<tr>
			<td align='right' class='viewPropTitle'>Nivel de Acceso:</td>
			<td class=''>
				<select name="nivelacceso" <?php if ($modo==2){echo "disabled";}?> style="width:100%">
					<option VALUE="A" <?php if ($row["nivel"]=="A"){echo "selected";}?>>Administrador (Acceso sin restriciones)</option>
					<option VALUE="CMEC" <?php if ($row["nivel"]=="CMEC"){echo "selected";}?>>Crear/Modificar/Eliminar/Consultar</option>
					<option VALUE="CMC" <?php if ($row["nivel"]=="CMC"){echo "selected";}?>>Crear/Modificar/Consultar</option>
					<option VALUE="MC" <?php if ($row["nivel"]=="MC"){echo "selected";}?>>Modificar/Consultar</option>
					<option VALUE="C" <?php if ($row["nivel"]=="C"){echo "selected";}?>>Consultar</option>
			</select>			</td></tr>
			
			<tr><td align='right' class='viewPropTitle'>Area de Acceso:</td>
				<td colspan=3>
					<table>
					<tr>
						<td><input type ="checkbox" name ="area[]" value="1" <?php if (ereg("1",$row["area"])){echo "checked";}?>>RRHH</td>
						<td><input type ="checkbox" name ="area[]" value="2" <?php if (ereg("2",$row["area"])){echo "checked";}?>>Presupuesto</td>
						<td><input type ="checkbox" name ="area[]" value="3" <?php if (ereg("3",$row["area"])){echo "checked";}?>>Compromisos</td>
						<td><input type ="checkbox" name ="area[]" value="4" <?php if (ereg("4",$row["area"])){echo "checked";}?>>Finanzas</td>
					</tr>
						<td><input type ="checkbox" name ="area[]" value="5" <?php if (ereg("5",$row["area"])){echo "checked";}?>>Contabilidad</td>
						<td><input type ="checkbox" name ="area[]" value="6" <?php if (ereg("6",$row["area"])){echo "checked";}?>>Almacen</td>
						<td><input type ="checkbox" name ="area[]" value="7" <?php if (ereg("7",$row["area"])){echo "checked";}?>>Bienes</td>
						<td><input type ="checkbox" name ="area[]" value="8" <?php if (ereg("8",$row["area"])){echo "checked";}?>>Tablas Comunes</td>
					</table>				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		<?php
		}
	?>
	</table>
	
  <table align=center cellpadding=2 cellspacing=0>
		<tr><td>
      <input align=center class='button' name='ingresar' type='submit' value='Ingresar'>
			<input align=center class='button' name='reiniciar' type='reset' value='Limpiar'>
		</td></tr>
	</table>
	</form>
	<br>

	<?php if ($registro_nuevo=="0"){ ?>
	<form name="buscar" action="usuarios.php" method="POST">
	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Buscar:</td>
			<td class=''><input type="text" name="textoabuscar" maxlength="30" size="30"></td>
			<td align='right' class='viewPropTitle'>Por:</td>
			<td class=''>
				<select name="tipobusqueda">
					<option VALUE="c">C&eacute;dula</option>
					<option VALUE="a">Apellidos</option>
					<option VALUE="n">Nombres</option>
				</select> 
			</td>
			<td>
				<input align=center name="buscar" type="submit" value="Buscar">
				</a>
			</td>
		</tr>
	</table>
	</form>
	
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<form name="grilla" action="usuarios.php" method="POST">
						<table class="Browse" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<!--<td class="Browse">&nbsp;</td>-->
									<td align="center" class="Browse">C&eacute;dula</td>
									<td align="center" class="Browse">Apellidos</td>
									<td align="center" class="Browse">Nombres</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							
							<?php
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
								{ 
								?>
								<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
								echo "<td align='right' class='Browse' width='20%'>".$llenar_grilla["cedula"]."</td>";
								echo "<td align='left' class='Browse'>".$llenar_grilla["apellidos"]."</td>";
								echo "<td align='left' class='Browse'>".$llenar_grilla["nombres"]."</td>";
								$c=$llenar_grilla["cedula"];
								echo "<td align='center' class='Browse' width='7%'><a href='usuarios.php?modo=1&c=$c&busca=0' class='Browse'><img src='./theme/green/pics/edit.png' border='0' alt='Modificar' title='Modificar'></a></td>";
								echo "<td align='center' class='Browse' width='7%'><a href='usuarios.php?modo=2&c=$c&busca=0' class='Browse'><img src='./theme/green/pics/deletecol.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
	<?php } 
		if (!isset($_POST["cedula"]) or $_POST["cedula"]==""){
			echo "<script> document.usuario.cedula.focus() </script>";
		}else{
			if ($bandera){
				echo "<script> document.usuario.newlogin.focus() </script>";
			}else{
				echo "<script> document.usuario.apellidos.focus() </script>";
			}
		}
	
	?>
</body>
</html>
<?php
if($_POST){
	$modo=$_GET["modo"];
	
		$fh=date("Y-m-d H:i:s");
		$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$cedula=$_POST["cedula"];
		$apellidos=strtoupper($_POST["apellidos"]);
		$nombres=strtoupper($_POST["nombres"]);
		$newlogin=$_POST["newlogin"];
		$clave1=$_POST["clave"];
		$clave2=$_POST["clave2"];
		$pregunta=$_POST["pregunta"];
		$respuesta=$_POST["respuesta"];
		if (isset($_POST["nivelacceso"])){
			$nivelacceso=$_POST["nivelacceso"];
		}else{ $nivelacceso="";}

		if ($nivelacceso=="A") $areas="12345678";
		if ($nivelacceso=="") $nivelacceso="0";
		$vali=mysql_query("select * from usuarios where cedula like '".$_POST['cedula']."' and status='a'",$conexion_db);
		
		if (mysql_num_rows($vali)>0){
			mensaje("Disculpe la cedula que ingreso ya existe");
		}else{
			$valilogin=mysql_query("select * from usuarios where login like '".$_POST['newlogin']."' and status='a'",$conexion_db);
			if (mysql_num_rows($valilogin)>0){
				mensaje("Disculpe el Usuario que Ingreso ya Existe");
			}else{
		
				$result=mysql_query("insert into usuarios 			
														(cedula,apellidos,nombres,login,clave,nivel,status,fechayhora,
														preguntasecreta,respuestasecreta,estado,usuario, estacion)
												values 	
														('$cedula','$apellidos','$nombres','$newlogin','".md5($clave1)."','$nivelacceso','a','$fh',
														'$pregunta','$respuesta','i','$newlogin', '$pc')",
												$conexion_db)or die(mysql_error());
												
				
				
				registra_transaccion('Agregar Usuario desde Inicio',$login,$fh,$pc,'usuarios',$conexion_db);
				if ($registro_nuevo!="0"){
					$sql = mysql_query("insert into privilegios_modulo(id_modulo,id_usuario)values(11,$cedula)");
					$sql = mysql_query("insert into privilegios_acciones(id_accion,id_usuario)values(64,$cedula),(65,$cedula)");
					mensaje("EL usuario se Ingreso con Exito, Ya puede Iniciar Session");
					?>
                    <script>window.close();</script>
                    <?

				}
			}
		}
	
}
mysql_close($conexion_db);
ob_flush();
?>
