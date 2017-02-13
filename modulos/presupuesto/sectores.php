<?php
if($_POST["ingresar"]){
	$_GET["accion"] = 155;
}


$codigo=$_GET["c"];

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from sector 
												where status='a' order by codigo"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$campo_busqueda=$_POST["tipobusqueda"];
	$sql="select * from sector where status='a'";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			if ($campo_busqueda=="c"){
				$registros_grilla=mysql_query($sql." and codigo like '$texto_buscar%' order by codigo",$conexion_db);
			}
			if ($campo_busqueda=="d"){
				$registros_grilla=mysql_query($sql." and denominacion like '$texto_buscar%' order by codigo",$conexion_db);
			}
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}

if ($_POST["codigo"]<>"" || $_GET["C"]<>"")
	{
		if ($_POST["codigo"]<>"") {$codigo_validar=$_POST["codigo"];} else {$codigo_validar=$_GET["c"];}
		$sql_validar_codigo=mysql_query("select * from sector
														where codigo=".$codigo_validar." and status='a'"
															,$conexion_db);
		if (mysql_num_rows($sql_validar_codigo)>0)
			{
				//header("location:error_rrhh.php?err=9&modo=0&busca=0");
				$regsector=mysql_fetch_assoc($sql_validar_codigo);
				$c=$regsector["codigo"];
				$modo=1;
			}
	}

if ($_GET["accion"] == 156 || $_GET["accion"] == 157){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$conexion_db=conectarse();
	$sql=mysql_query("select * from sector 
										where codigo like '".$_GET['c']."'"
											,$conexion_db);
	$regsector=mysql_fetch_assoc($sql);
	
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
		if (document.frmsector.codigo.value.length==0){
			alert("Debe escribir un C&oacute;digo para el Sector")
			document.frmsector.codigo.focus()
			return false;
		}
		if (document.frmsector.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para el Sector")
			document.frmsector.denominacion.focus()
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
	<h4 align=center>Sectores</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<form name="frmsector" action="principal.php?modulo=2&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
	
		<table align=center cellpadding=2 cellspacing=0 width="60%">
			<tr>
				<td align='right' class='viewPropTitle'>C&oacute;digo:</td>
				<td class=''><input type="text" id="codigo" name="codigo" maxlength="2" size="2" onKeyPress="javascript:return solonumeros(event)" <?php if (isset($_POST["codigo"])){ echo "value=".$_POST["codigo"];} else {echo "value=".$regsector["codigo"];}?>>
                &nbsp;<a href="principal.php?modulo=2&accion=35"><img src="imagenes/nuevo.png" border="0" title="Nuevo Sector"></a>
                
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/reportes/presupuesto/reportes/.php?nombre=sector';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none';">X</a></div>
          
          <iframe name="pdf" id="pdf" style="display:block" height="500" width="500"></iframe>          
          </div>
                			
				</td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class=''><input type="text" name="denominacion" maxlength="255" size="45" id="denominacion" <?php echo 'value="'.$regsector['denominacion'].'"'; if ($_GET["accion"] == 157) echo "disabled";?>></td>
			</tr>
		</table>
		<table align=center cellpadding=2 cellspacing=0>
			<tr><td>
					<?php

					if($_GET["accion"] != 156 and $_GET["accion"] != 157 and in_array(155, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 156 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 157 and in_array($_GET["accion"], $privilegios) == true){
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
	<br>
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<form name="grilla" action="sectores.php" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="60%">
							<thead>
								<tr>
									<td align="center" class="Browse">C&oacute;digo</td>
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
									echo "<td align='center' class='Browse'>".$llenar_grilla["codigo"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["denominacion"]."</td>";
									$c=$llenar_grilla["codigo"];
									if(in_array(157, $privilegios)){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=156&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(57, $privilegios)){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?accion=157&modulo=2&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmsector.codigo.focus() </script>
</body>
</html>

<?php
if($_POST){

	$codigo=$_POST["codigo"];
	$denominacion=strtoupper($_POST["denominacion"]);
	$busca_existe_registro=mysql_query("select * from sector where codigo like '".$_POST['codigo']."'  and status='a'",$conexion_db);
if($_GET["accion"] == 155 and in_array(155, $privilegios)){
	if (mysql_num_rows($busca_existe_registro)>0){
			?>
				<script>
			mostrarMensajes("error", "Disculpe el registro que inserto ya existe, Vuelva a intentarlo");
			setTimeout("window.location.href='principal.php?modulo=2&accion=35'",5000);
			</script>

		<?
			
	}else{
		mysql_query("insert into sector
									(codigo,denominacion,usuario,fechayhora,status) 
							values ('$codigo','$denominacion','$login','$fh','a')"
									,$conexion_db);
			registra_transaccion('Insertar Sectores ('.$denominacion.')',$login,$fh,$pc,'sector',$conexion_db);
			
			?>
				<script>
			mostrarMensajes("exito", "El regsitro se Inserto con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=35'",5000);
			</script>

		<?

		}
	}
	if ($_GET["accion"] == 156 and in_array(156,$privilegios) == true and !$_POST["buscar"]){
			mysql_query("update sector set 
										denominacion='".$denominacion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	codigo = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Sectores ('.$denominacion.')',$login,$fh,$pc,'sector',$conexion_db);
			
			?>
				<script>
			mostrarMensajes("error", "El regsitro se Modifico con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=35'",5000);
			</script>

		<?

	}
	if ($_GET["accion"] == 157 and in_array(157,$privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from sector where codigo = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from sector where codigo = '$codigo'",$conexion_db);
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Sectores (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'sector',$conexion_db);
				
				?>
				<script>
			mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
			setTimeout("window.location.href='principal.php?modulo=3&accion=35'",5000);
			</script>

		<?
			}else{
				registra_transaccion('Eliminar Sectores ('.$bus["denominacion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
				<script>
			mostrarMensajes("exito", "El regsitro se Elimino con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=35'",5000);
			</script>

		<?
				
					
			}	
	}
}
?>
