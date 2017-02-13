<?php
if($_POST["ingresar"]){
$_GET["accion"] = 140;
}

$idclasificador_presupuestario=$_GET["c"];

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from clasificador_presupuestario
												where status='a' order by codigo_cuenta"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$campo_busqueda=$_POST["tipobusqueda"];
	$sql="select * from clasificador_presupuestario where status='a'";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			$registros_grilla=mysql_query($sql." and codigo_cuenta like '%$texto_buscar%' or denominacion like '%$texto_buscar%' order by codigo_cuenta",$conexion_db);
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}

if ($_GET["accion"] == 141 || $_GET["accion"] == 142 and $_GET["c"]){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	
	$sql=mysql_query("select * from clasificador_presupuestario 
										where idclasificador_presupuestario = ".$_GET['c']
											,$conexion_db);
	$regclasificador_presupuestario=mysql_fetch_assoc($sql);
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		if (document.frmclasificador_presupuestario.partida.value.length==0){
			alert("Debe escribir una Partida para la Cuenta")
			document.frmclasificador_presupuestario.partida.focus()
			return false;
		}
		if (document.frmclasificador_presupuestario.generica.value.length==0){
			alert("Debe escribir una Gen&eacute;rica para la Cuenta")
			document.frmclasificador_presupuestario.generica.focus()
			return false;
		}
		if (document.frmclasificador_presupuestario.especifica.value.length==0){
			alert("Debe escribir una Especifica para la Cuenta")
			document.frmclasificador_presupuestario.especifica.focus()
			return false;
		}
		if (document.frmclasificador_presupuestario.subespecifica.value.length==0){
			alert("Debe escribir una Sub-Especifica para la Cuenta")
			document.frmclasificador_presupuestario.subespecifica.focus()
			return false;
		}
		if (document.frmclasificador_presupuestario.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para la Ceunta")
			document.frmclasificador_presupuestario.denominacion.focus()
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
	<h4 align=center>Clasificador Presupuestario</h4>
	<h2 class="sqlmVersion"></h2>
	<br>

	<form name="frmclasificador_presupuestario" action="principal.php?modulo=2&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">
	<input type="hidden" id="codigo" name="codigo" maxlength="9" size="9" <?php echo 'value="'.$regclasificador_presupuestario['codigo_cuenta'].'"';?>>
		<table align=center cellpadding=2 cellspacing=0 width="60%">
			<tr>
				<td align='right' class='viewPropTitle'>Partida:</td>
				<td class=''><input type="text" id="partida" name="partida" tabindex="1" maxlength="3" size="3" onKeyPress="javascript:return solonumeros(event)" <?php echo 'value="'.$regclasificador_presupuestario["partida"].'"'; if ($_GET["accion"] == 142) echo "disabled";?>>
&nbsp;	<a href="principal.php?modulo=2&accion=42">	<img src="imagenes/nuevo.png" border="0" title="Nuevo Clasificador Presupuestario"></a>
			
            
            <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='block';">X</a></div>
          <table id="tableImprimir">
          	<tr>
            	<td>Ordenar Por: </td>
                <td>
                	<select name="ordenarPor" id="ordenarPor">
                    	<option value="partida">Partida</option>
                        <option value="generica">Gen&eacute;rica</option>
                        <option value="especifica">Espec&iacute;fica</option>
                        <option value="sub_especifica">Sub-Espec&iacute;fica</option>
                    </select>               
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/presupuesto/reportes.php?nombre=clapre&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
                </td>
            </tr>
          </table>
          <iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>          
          </div>



		</td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Gen&eacute;rica:</td>
				<td class=''><input type="text" id="generica" name="generica" tabindex="2" maxlength="2" size="2" onKeyPress="javascript:return solonumeros(event)" <?php echo 'value="'.$regclasificador_presupuestario["generica"].'"'; if ($_GET["accion"] == 142) echo "disabled";?>>
				</td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Especifica:</td>
				<td class=''><input type="text" id="especifica" name="especifica" tabindex="3" maxlength="2" size="2" onKeyPress="javascript:return solonumeros(event)" <?php echo 'value="'.$regclasificador_presupuestario["especifica"].'"'; if ($_GET["accion"] == 142) echo "disabled";?>>
				</td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Sub-Especifica:</td>
				<td class=''><input type="text" id="subespecifica" name="subespecifica" tabindex="4" maxlength="2" size="2" onKeyPress="javascript:return solonumeros(event)" <?php echo 'value="'.$regclasificador_presupuestario["sub_especifica"].'"'; if ($_GET["accion"] == 142) echo "disabled";?>>
				</td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class=''><input type="text" name="denominacion" tabindex="5" maxlength="255" size="65" id="denominacion" <?php echo 'value="'.$regclasificador_presupuestario['denominacion'].'"'; if ($_GET["accion"] == 142) echo "disabled";?>></td>
			</tr>
		</table>
		<table align=center cellpadding=2 cellspacing=0>
			<tr><td>
				<?php
				
							   if($_REQUEST["pop"]){
	echo "<input type='hidden' value='true' name='pop' id='pop'>";
	}

					if($_GET["accion"] != 141 and $_GET["accion"] != 142 and in_array(140, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 141 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 142 and in_array($_GET["accion"], $privilegios) == true){
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
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<form name="grilla" action="" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="80%">
							<thead>
								<tr>
									<td align="center" class="Browse">Partida</td>
									<td align="center" class="Browse">Gen&eacute;rica</td>
									<td align="center" class="Browse">Especifica</td>
									<td align="center" class="Browse">Sub-Especifica</td>
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
									echo "<td align='center' class='Browse' width='6%'>".$llenar_grilla["partida"]."</td>";
									echo "<td align='center' class='Browse' width='6%'>".$llenar_grilla["generica"]."</td>";
									echo "<td align='center' class='Browse' width='6%'>".$llenar_grilla["especifica"]."</td>";
									echo "<td align='center' class='Browse' width='6%'>".$llenar_grilla["sub_especifica"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["denominacion"]."</td>";
									$c=$llenar_grilla["idclasificador_presupuestario"];
									if(in_array(141, $privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=141&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(142, $privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=142&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmclasificador_presupuestario.partida.focus() </script>
</body>
</html>

<?php
if($_POST){
	
	if ($_POST["codigo"]==""){$codigo=$_POST["partida"].$_POST["generica"].$_POST["especifica"].$_POST["subespecifica"];}
	else {$codigo=$_POST["codigo"];}
	
	$partida=$_POST["partida"];
	$generica=$_POST["generica"];
	$especifica=$_POST["especifica"];
	$subespecifica=$_POST["subespecifica"];
	$denominacion=$_POST["denominacion"];


	$busca_existe_registro=mysql_query("select * from clasificador_presupuestario where codigo_cuenta like '".$_POST['codigo']."'  and status='a'",$conexion_db);
if($_GET["accion"] == 140 and in_array(140, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
			mostrarMensajes("error", "Disculpe el registro que esta ingresando ya Exite, Vuelva a Intentarlo");
			setTimeout("window.location.href='principal.php?modulo=2&accion=42'",5000);
			</script>
		<?
	}else{
		mysql_query("insert into clasificador_presupuestario
									(partida,generica,especifica,sub_especifica,denominacion,codigo_cuenta,usuario,fechayhora,status) 
							values ('$partida','$generica','$especifica','$subespecifica','$denominacion','$codigo','$login','$fh','a')"
									,$conexion_db);
			$idcreado = mysql_insert_id();
			registra_transaccion('Ingresar Clasificador Presupuestario ('.$denominacion.')',$login,$fh,$pc,'clasificador_presupuestario',$conexion_db);
			
			
			if($_POST["pop"]){
				?>
				<script>
               		actualizaSelect("clasificador", "lib/consultar_tablas_select.php", "clasificador_presupuestario", <?php echo $idcreado?>);
                </script>
				<?php
			}else{
			?>
		<script>
			mostrarMensajes("exito", "El regsitro se Inserto con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=42'",5000);
			</script>
		<?
			}
			
			
			
		}
	}
	if ($_GET["accion"] == 141 and in_array(141, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update clasificador_presupuestario set 
										denominacion='".$denominacion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	codigo_cuenta = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Clasificador Presupuestario ('.$denominacion.')',$login,$fh,$pc,'clasificador_presupuestario',$conexion_db);
			?>
		<script>
			mostrarMensajes("exito", "El registro se Modifico con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=42'",5000);
			</script>
		<?
	}
	if ($_GET["accion"] == 142 and in_array(142, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from clasificador_presupuestario where codigo_cuenta = '$codigo' and status = 'a'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from clasificador_presupuestario where codigo_cuenta = '$codigo' and status = 'a'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Clasificador Presupuestario (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'clasificador_presupuestario',$conexion_db);
				
				?>
		<script>
			mostrarMensajes("error", "Disculpe el registro no se pudo Eliminar, posiblemente sea porque este registro esta siendo usado en otra tabla");
			setTimeout("window.location.href='principal.php?modulo=2&accion=42'",5000);
			</script>
		<?
			}else{
				registra_transaccion('Eliminar Clasificador Presupuestario ('.$bus["denominacion"].')',$login,$fh,$pc,'clasificador_presupuestario',$conexion_db);
				?>
		<script>
			mostrarMensajes("exito", "El registro se Elimino con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=42'",5000);
			</script>
		<?
			}
			
	}
}
?>
