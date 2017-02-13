<?php
if($_POST["ingresar"]){
	$_GET["accion"] = 161;
}
$codigo=$_GET["c"];

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from tipo_presupuesto 
												where status='a' order by denominacion"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from tipo_presupuesto where status='a'";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			$registros_grilla=mysql_query($sql." and denominacion like '$texto_buscar%' order by denominacion",$conexion_db);
			
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}

if ($_GET["accion"] == 162 || $_GET["accion"] == 163){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from tipo_presupuesto 
										where idtipo_presupuesto like '".$_GET['c']."'"
											,$conexion_db);
	$regtipo_presupuesto=mysql_fetch_assoc($sql);
	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<!-- <META HTTP-EQUIV="Refresh" CONTENT="600; URL=lib/cerrar.php"> -->
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		
		if (document.frmtipo_presupuesto.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para el Tipo de Presupuesto")
			document.frmtipo_presupuesto.denominacion.focus()
			return false;
		}	
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
	<h4 align=center>Tipos de Presupuestos</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<form name="frmtipo_presupuesto" action="principal.php?modulo=2&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
	<input type="hidden" id="codigo" name="codigo" maxlength="9" size="9" <?php echo 'value="'.$regtipo_presupuesto['idtipo_presupuesto'].'"';?>>
		<table align=center cellpadding=2 cellspacing=0 width="50%">
			<tr>
				<td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class=''><input type="text" name="denominacion" maxlength="255" size="45" id="denominacion" <?php echo 'value="'.$regtipo_presupuesto['denominacion'].'"'; if ($_GET["accion"] == 163) echo "disabled";?>>
                &nbsp;<a href="principal.php?modulo=2&accion=44"><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de PResupuesto"></a>
                
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/reportes/presupuesto/reportes.php?nombre=tipopre';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
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

					if($_GET["accion"] != 162 and $_GET["accion"] != 163 and in_array(161, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 162 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 163 and in_array($_GET["accion"], $privilegios) == true){
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
					<td>
						<form name="grilla" action="" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="50%">
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
									$c=$llenar_grilla["idtipo_presupuesto"];
									if(in_array(162, $privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=162&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(163, $privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=163&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmtipo_presupuesto.denominacion.focus() </script>
</body>
</html>

<?php
if($_POST){

	$codigo=$_POST["codigo"];
	$denominacion=strtoupper($_POST["denominacion"]);
	$busca_existe_registro=mysql_query("select * from tipo_presupuesto where denominacion = '".$_POST['denominacion']."'  and status='a'",$conexion_db);
if($_GET["accion"] == 161 and in_array(161, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
	mensaje("Disculpe el registro que inserto ya Existe, vuelva a intentarlo");
	redirecciona("principal.php?modulo=2&accion=44");
	}else{
		mysql_query("insert into tipo_presupuesto
									(denominacion,usuario,fechayhora,status) 
							values ('$denominacion','$login','$fh','a')"
									,$conexion_db);
			registra_transaccion('Ingresar Tipo de Presupuesto ('.$denominacion.')',$login,$fh,$pc,'tipo_presupuesto',$conexion_db);
			
			?>
				<script>
			mostrarMensajes("error", "El registro se Inserto con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=44'",5000);
			</script>

		<?

		}
	}
	if ($_GET["accion"] == 162 and in_array(162, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update tipo_presupuesto set 
										denominacion='".$denominacion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idtipo_presupuesto = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Tipo de presupuesto ('.$denominacion.')',$login,$fh,$pc,'tipo_presupuesto',$conexion_db);
			
			?>
				<script>
			mostrarMensajes("exito", "El regsitro se Modifico con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=44'",5000);
			</script>

		<?
			

	}
	if ($_GET["accion"] == 163 and in_array(163, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from tipo_presupuesto where idtipo_presupuesto = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from tipo_presupuesto where idtipo_presupuesto = '$codigo'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Tipos de Presupuesto (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'sector',$conexion_db);
				
				?>
				<script>
			mostrarMensajes("error", "Disculpe el regsitro que intenta eliminar esta relacionado con otro regsitro, por ello no puede ser eliminado");
			setTimeout("window.location.href='principal.php?modulo=2&accion=44'",5000);
			</script>

		<?
			
			}else{
				registra_transaccion('Eliminar Tipos de Presupuesto ('.$bus["denominacion"].')',$login,$fh,$pc,'sector',$conexion_db);
				
				
				?>
				<script>
			mostrarMensajes("exito", "El regsitro se Elimino con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=44'",5000);
			</script>

		<?
				
			
			}

	}
}
?>
