<?php
if($_POST["ingresar"]){
$_GET["accion"] = 338;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from snc_familia_actividad 
												where status='a' order by descripcion"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from snc_familia_actividad where status='a'";
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

if ($_GET["accion"] == 339 || $_GET["accion"] == 340){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from snc_familia_actividad 
										where idsnc_familia_actividad like '".$_GET['c']."'"
											,$conexion_db);
	$regsnc_familia_actividad=mysql_fetch_assoc($sql);
	
}

?>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		
		if (document.frmsnc_familia_actividad.descripcion.value.length==0){
			alert("Debe escribir una Descripcion para el Tipo de Presupuesto")
			document.frmsnc_familia_actividad.descripcion.focus()
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
	<h4 align=center>SNC Familia Actividad</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<form name="frmsnc_familia_actividad" action="principal.php?modulos=4&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
  <input type="hidden" id="id" name="id" maxlength="9" size="9" <?php echo 'value="'.$regsnc_familia_actividad['idsnc_familia_actividad'].'"';?>>
		<table align=center cellpadding=2 cellspacing=0 width="50%">
<tr>
			  <td align='right' class='viewPropTitle'>Actividades:</td>
			  <td class=''>
              <?php
              $sql = mysql_query("select * from snc_actividades");
			  ?>
              <select name="actividad" id="actividad" <? if($_GET["accion"] == 339){echo " disabled";}?>>
              <?php
              while($bus = mysql_fetch_array($sql)){
			  ?>
              <option <?php if(isset($_GET["acti"]) and $_GET["acti"] == $bus["idsnc_actividades"]){ echo " selected='selected'";}?> value="<?php echo $bus["idsnc_actividades"]?>"><?php echo $bus["codigo"]." ".$bus["descripcion"]?></option>
			  <?php
			  }
			  ?>
              </select>
&nbsp;<a href='principal.php?modulo=4&accion=337' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo SNC Familia Actividad"></a>

			
            
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='block';">X</a></div>
          <table id="tableImprimir">
          	<tr>
            	<td>Ordenar Por: </td>
                <td>
                	<select name="ordenarPor" id="ordenarPor">
                    	 <option value="snc_actividades.descripcion">Actividad</option>
                         <option value="snc_familia_actividad.descripcion">Familia</option>
                         <option value="snc_familia_actividad.codigo">Codigo</option>
                    </select>               
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/compras_servicios/reportes.php?nombre=sncfamilia&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
                </td>
            </tr>
          </table>
          <iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>          
          </div>
            
            
              </td>
		  </tr>
			<tr>
				<td align='right' class='viewPropTitle'>Descripci&oacute;n:</td>
				<td class=''><input type="text" name="descripcion" maxlength="255" size="45" id="descripcion" <?php echo 'value="'.$regsnc_familia_actividad['descripcion'].'"';?>></td>
			</tr>
            <tr>
				<td align='right' class='viewPropTitle'>C&oacute;digo:</td>
				<td class=''><input type="text" name="codigo" maxlength="2" size="4" id="codigo" <?php echo 'value="'.$regsnc_familia_actividad['codigo'].'"'; if($_GET["accion"] == 114){echo " disabled";}?>></td>
		  </tr>
		</table>
<table align=center cellpadding=2 cellspacing=0>
			<tr><td>
			<?php
					if($_GET["accion"] != 339 and $_GET["accion"] != 340 and in_array(338, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 339 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 340 and in_array($_GET["accion"], $privilegios) == true){
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
					<td align="center">
					  <form name="grilla" action="" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="50%">
							<thead>
								<tr>
									<td align="center" class="Browse">Actividad</td>
                                    <td align="center" class="Browse">Familia</td>
                                    <td align="center" class="Browse">C&oacute;digo</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
									{ ?>
						  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
								$sql = mysql_query("select * from snc_actividades where idsnc_actividades = ".$llenar_grilla["idsnc_actividades"]."");
								$bus = mysql_fetch_array($sql);
									echo "<td align='left' class='Browse'>".$bus["descripcion"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["descripcion"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["codigo"]."</td>";
									$c=$llenar_grilla["idsnc_familia_actividad"];
									if(in_array(339, $privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=339&c=$c&acti=".$bus["idsnc_actividades"]."' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(340, $privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=4&accion=340&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmsnc_familia_actividad.descripcion.focus() </script>
</body>
</html>

<?php
if($_POST){

	$id=$_POST["id"];
	$descripcion=strtoupper($_POST["descripcion"]);
	$codigo = $_POST["codigo"];
	$actividad = $_POST["actividad"];
	$sql_actividad=mysql_query("select * from snc_actividades where idsnc_actividades='".$actividad."'",$conexion_db);
	$buscar_actividad = mysql_fetch_array($sql_actividad);
	$sigla=$buscar_actividad["sigla"];
	$codigo=$sigla.$codigo;
	
	$busca_existe_registro=mysql_query("select * from snc_familia_actividad where descripcion = '".$_POST['descripcion']."'  and idsnc_actividades= ".$_POST["actividad"]." and status='a'",$conexion_db);
	if($_GET["accion"] == 338 and in_array(338,$privilegios) == true){
	
	if (mysql_num_rows($busca_existe_registro)>0){
		mensaje("Disculpe el registro que ingreso ya existe");
		redirecciona("principal.php?modulo=4&accion=337");
	}else{
		
			mysql_query("insert into snc_familia_actividad
									(descripcion,usuario,fechayhora,status, codigo, idsnc_actividades) 
							values ('$descripcion','$login','$fh','a','$codigo', $actividad)"
									,$conexion_db);
			registra_transaccion('Ingresar Familia Actividad ('.$descripcion.')',$login,$fh,$pc,'snc_familia_actividad',$conexion_db);
			mensaje("El registro fue Insertado con Exito");
			redirecciona("principal.php?modulo=4&accion=337");
		}
	}
	if ($_GET["accion"]==339 and in_array(339, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update snc_familia_actividad set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idsnc_familia_actividad = '$id' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Familia Actividad ('.$descripcion.')',$login,$fh,$pc,'snc_familia_actividad',$conexion_db);
			mensaje("El registro fue Modificado con Exito");
			redirecciona("principal.php?modulo=4&accion=337");
	}
	
	if ($_GET["accion"] == 340 and in_array(340, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from snc_familia_actividad where idsnc_familia_actividad = '$id'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from snc_familia_actividad where idsnc_familia_actividad = '$id'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Familia Actividad (ERROR) ('.$_bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				redirecciona("principal.php?modulo=4&accion=337");
			}else{
				registra_transaccion('Eliminar Familia Actividad ('.$_bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("El registro fue Eliminado con Exito");
				redirecciona("principal.php?modulo=4&accion=337");
			}
			
	}
}
?>