<?php

if($_POST["ingresar"]){
$_GET["accion"] = 334;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from snc_actividades 
												where status='a' order by descripcion"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from snc_actividades where status='a'";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			$registros_grilla=mysql_query($sql." and descripcion like '$texto_buscar%' order by descripcion",$conexion_db);
			
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}

if ($_GET["accion"] == 335 || $_GET["accion"] == 336){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from snc_actividades 
										where idsnc_actividades like '".$_GET['c']."'"
											,$conexion_db);
	$regsnc_actividades=mysql_fetch_assoc($sql);
	
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		
		if (document.frmsnc_actividades.descripcion.value.length==0){
			alert("Debe escribir una Descripcion para el Tipo de Presupuesto")
			document.frmsnc_actividades.descripcion.focus()
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

<body>
	<br>
	<h4 align=center>SNC Actividades</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<form id="frmsnc_actividades" action="principal.php?modulo=4&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
  <input type="hidden" id="codigo" name="codigo" maxlength="9" size="9" <?php if ($_GET["accion"]==100 || $_GET["accion"]==101) {echo 'value="'.$regsnc_actividades['idsnc_actividades'].'"';}?>>
		<table align=center cellpadding=2 cellspacing=0 width="40%">
<tr>
				<td align='right' class='viewPropTitle'>Descripci&oacute;n:</td>
				<td class=''><input type="text" name="descripcion" maxlength="255" size="45" id="descripcion" <?php echo 'value="'.$regsnc_actividades['descripcion'].'"'; ?> onKeyUp="validarVacios('descripcion', this.value, 'frmsnc_actividades')" onBlur="validarVacios('descripcion', this.value, 'frmsnc_actividades')" autocomplete="OFF" style="padding:0px 20px 0px 0px;">
                &nbsp;<a href='principal.php?modulo=4&accion=333' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo SNC Actividades"></a>
                
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='block';">X</a></div>
          <table id="tableImprimir">
          	<tr>
            	<td>Ordenar Por: </td>
                <td>
                	<select name="ordenarPor" id="ordenarPor">
                    	 <option value="descripcion">Descripci&oacute;n</option>
                         <option value="sigla">Sigla</option>
                    </select>               
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/compras_servicios/reportes.php?nombre=sncactividad&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
                </td>
            </tr>
          </table>
          <iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>          
          </div>
                
                
                </td>
			</tr>
            <tr>
				<td align='right' class='viewPropTitle'>sigla:</td>
				<td class=''><input type="text" name="sigla" maxlength="1" size="1" id="sigla" <?php echo 'value="'.$regsnc_actividades['sigla'].'"'; ?>></td>
			</tr>
		</table>
<table align=center cellpadding=2 cellspacing=0>
			<tr><td>
<?php
					
					if($_GET["accion"] != 335 and $_GET["accion"] != 336 and in_array(334, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 335 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 336 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}

        	        //botones(85,86,87,$modo,"0",$nivel);
				
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
					<td align="center">
					  <form name="grilla" action="snc_actividades.php" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="50%">
							<thead>
								<tr>
									<td align="center" class="Browse">Actividad</td>
                                    <td align="center" class="Browse">Sigla</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
									{ ?>
						  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
									echo "<td align='left' class='Browse'>".$llenar_grilla["descripcion"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["sigla"]."</td>";
									$c=$llenar_grilla["idsnc_actividades"];
										if(in_array(335, $privilegios) == true){
											echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=335&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
										if(in_array(336, $privilegios) == true){
									echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=336&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.getElementById('descripcion').focus() </script>
</body>
</html>

<?php

if ($_POST){
	$codigo=$_POST["codigo"];
	$descripcion=strtoupper($_POST["descripcion"]);
	$sigla = $_POST["sigla"];
	$busca_existe_registro=mysql_query("select * from snc_actividades where descripcion = '".$_POST['descripcion']."'  and status='a'",$conexion_db);

	if($_GET["accion"] == 334 and in_array(334, $privilegios) == true){
		if (mysql_num_rows($busca_existe_registro)>0){
			mensaje("Disculpe ya este registro existe");
			redirecciona("principal.php?modulo=4&accion=333");
		}else{
		
			mysql_query("insert into snc_actividades
									(descripcion,usuario,fechayhora,status, sigla) 
							values ('$descripcion','$login','$fh','a','$sigla')"
									,$conexion_db);
			registra_transaccion('Ingresar SNC Actividades ('.$descripcion.')',$login,$fh,$pc,'snc_actividades',$conexion_db);
			mensaje("Se inserto el registro con Exito");
			redirecciona("principal.php?modulo=4&accion=333");
		}
	}
	if ($_GET["accion"]==335 and in_array(335, $privilegios) ==  true and !$_POST["buscar"]){
			mysql_query("update snc_actividades set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."',
										sigla = '".$sigla."'
										where 	idsnc_actividades = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar SNC Actividades ('.$descripcion.')',$login,$fh,$pc,'snc_actividades',$conexion_db);
			mensaje("Se Modifico el registro con Exito");
			redirecciona("principal.php?modulo=4&accion=333");

	}
	if ($_GET["accion"] == 336 and in_array(336, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from snc_actividades where idsnc_actividades = '$codigo'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from snc_actividades where idsnc_actividades = '$codigo'",$conexion_db);	
			if(!$sql_eliminar){
					registra_transaccion('Eliminar SNC Actividades (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
					mensaje("Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
					redirecciona("principal.php?modulo=4&accion=333");

				}else{
					registra_transaccion('Eliminar SNC Actividades ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
					mensaje("Se Elimino el registro con Exito");
					redirecciona("principal.php?modulo=4&accion=333");				
				}


	}
}
?>