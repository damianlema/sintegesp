<?php
/**
*
*	 "mension.php" Mantenimiento de denominacion de Grupo Sanguineo
*	Version: 1.0.1
*	Fecha Ultima Modificacion: 25/02/2007
*	Autor: Hector Lema
*
*/
ob_start();
session_start();
include_once("../../conf/conex.php");
include_once("../../lib/registra.php");
include("../../lib/botones.php");
$conexion_db=conectarse();
$modo=$_GET["modo"]; //modo de acceso a la pagina: 0 agregar 1 modificar 2 eliminar
$registro_nuevo=$_SESSION["nuevo"]; // valida si se mando a buscar algun texto 0 muestra todos los registros 1 - 2 filtra segun lo buscado
$nivelacce=$_SESSION["nivel_acceso"];
$buscar_registros=$_GET["busca"];
$existen_registros=0; // switch para validar si hay datos a cargar en la grilla 0 existen 1 no existen

if ($registro_nuevo=="0"){
$sql_usuarios=mysql_query("select * from usuarios 
										where cedula=".$_SESSION['cedula_usuario']
											,$conexion_db);
$registro_usuario=mysql_fetch_assoc($sql_usuarios);
$login=$registro_usuario["login"];
}else{
$login="";
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from usuarios 
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
	$sql="select * from usuarios where status='a'";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			if ($campo_busqueda=="c"){
				$registros_grilla=mysql_query($sql." and cedula like '$texto_buscar%'",$conexion_db);
			}
			if ($campo_busqueda=="a"){
				$registros_grilla=mysql_query($sql." and apellidos like '$texto_buscar%'",$conexion_db);
			}
			if ($campo_busqueda=="n"){
				$registros_grilla=mysql_query($sql." and nombres like '$texto_buscar%'",$conexion_db);
			}
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}
$bandera=false;
//if (isset($_POST["validar_cedula"])){
	if ($_POST["cedula"]<>"")
	{
		$cedula_validar=$_POST["cedula"];
		$sql_validar_cedula=mysql_query("select * from usuarios 
														where cedula=".$cedula_validar." and status='a'"
															,$conexion_db);
		if (mysql_num_rows($sql_validar_cedula)>0)
			{
				header("location:error.php?err=18&modo=0&busca=0");
			}else{
				$sql_validar_cedula=mysql_query("select apellidos,nombres from trabajador 
														where cedula=".$cedula_validar." and status='a'"
															,$conexion_db);
				if (mysql_num_rows($sql_validar_cedula)>0)
				{
					$registro_trabajador=mysql_fetch_assoc($sql_validar_cedula);
					$apellidos_trabajador=$registro_trabajador["apellidos"];
					$nombres_trabajador=$registro_trabajador["nombres"];
					$bandera=true;
				}
			}
	}
//}

if ($modo==1 || $modo==2){
	$result=mysql_query("select * from usuarios where cedula like '".$_GET['c']."'",$conexion_db);
	$row=mysql_fetch_assoc($result);
}

if (!isset($_POST["ejecutar_accion"])){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<!-- <META HTTP-EQUIV="Refresh" CONTENT="600; URL=../../lib/principal/cerrar.php"> -->
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
				<td align='center'><a HREF='../../acceso.php'>Volver a Acceso</a></td>
			<?php } ?>
		</tr>
	</table>
	<br>
	<h4 align=center>Usuarios</h4>
	<h2 class="sqlmVersion"></h2>
	<?php
	if ($modo==0){ 
	?>
		<form name="usuario" action="usuarios.php" method="POST" onsubmit="return valida_envia()">	
	<?php
	}
	if ($modo==1){
	?>
		<form name="usuario" action="include/modifica.php" method="POST">	
	<?php
	}
	if ($modo==2){
	?>
		<form name="usuario" action="include/elimina.php" method="POST">	
	<?php
	}
	?>	
	
	<table align=center cellpadding=2 cellspacing=0>

	<?php
		if ($modo==0){ //entro en modo para agregar
		?>
			<tr><td align='right' class='viewPropTitle'>C&eacute;dula:</td>
				<td class=''><input type="text" name="cedula" maxlength="12" size="12" <?php if (isset($_POST["cedula"])){ echo "value=".$_POST["cedula"]; }?>>
				<button name="validar_cedula" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="this.form.submit()"><img src='imagenes/validar03.gif'>
			</button>
		
				</td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Apellidos:</td>
				<td class=''><input type="text" name="apellidos" maxlength="45" size="45" <?php if ($bandera){ echo "value=".$apellidos_trabajador; }?>></td>
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
				</select> 
			</td></tr>
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
					</table>
				</td>
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
				</select> 
			</td></tr>
			
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
					</table>
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		<?php
		}
	?>
		
	</table>
	
	<table align=center cellpadding=2 cellspacing=0>
		<tr><td>
			<?php
				botones("ejecutar_accion","accion3","accion3",$modo,"0",$nivel);
/*
			
				if ($modo==0){
				?>
					<input align=center name="ejecutar_accion" type="submit" value="Guardar">
			<?php
				}
				if ($modo==1){
				?>
					<input align=center class="" name="accion3" type="submit" value="Modificar">
			<?php
			}
				if ($modo==2){
				?>
					<input align=center class="" name="accion3" type="submit" value="Eliminar">
			<?php
			}
	*/		?>
			<a HREF='usuarios.php?modo=0&nuevo=<?php echo $registro_nuevo; ?>&nivel=<?php echo $nivelacce; ?>&busca=0'>Cancelar</a>
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
}else{
	$modo=$_GET["modo"];
	
	if ($modo==0){  //modo para agregar un registro
		$fh=date("Y-m-d H:i:s");
		$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$cedula=md5($_POST["cedula"]);
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
		if(isset($_POST["area"])){
			$area=$_POST["area"];
			$areas="";
			foreach($area as $ar){
				$areas=$areas.$ar;
			}
		}else{ $areas="";}
		if ($nivelacceso=="A") $areas="12345678";
		if ($nivelacceso=="") $nivelacceso="0";
		$vali=mysql_query("select * from usuarios where cedula like '".$_POST['cedula']."' and status='a'",$conexion_db);
		
		if (mysql_num_rows($vali)>0){
			header("location:error.php?err=18&modo=0&busca=0");
		}else{
			$valilogin=mysql_query("select * from usuarios where login like '".$_POST['newlogin']."' and status='a'",$conexion_db);
			if (mysql_num_rows($valilogin)>0){
				header("location:error.php?err=19&modo=0&busca=0");
			}else{
				$result=mysql_query("insert into usuarios 			
														(cedula,apellidos,nombres,login,clave,nivel,status,fechayhora,
														preguntasecreta,respuestasecreta,estado,usuario,area)
												values 	
														('$cedula','$apellidos','$nombres','$newlogin','$clave1','$nivelacceso','a',$fh,
														'$pregunta','$respuesta','i','$login','$areas')",
												$conexion_db);
												
				registra_transaccion('a',$login,$fh,$pc,'usuarios',$conexion_db);
				if ($registro_nuevo=="0"){
					header("location:usuarios.php?modo=0&busca=0");}
				else{
					header("location:acceso.php");}
			}
		}
	}
}
mysql_close($conexion_db);
ob_flush();
?>
