<?php
if($_POST["ingresar"]){
$_GET["accion"] = 116;
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from snc_grupo_actividad 
												where status='a' order by codigo"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from snc_grupo_actividad where status='a'";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			$registros_grilla=mysql_query($sql." and descripcion like '$texto_buscar%' order by codigo",$conexion_db);
			
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}

if ($_GET["accion"] == 117 || $_GET["accion"] == 118){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from snc_grupo_actividad 
										where idsnc_grupo_actividad like '".$_GET['c']."'"
											,$conexion_db);
	$regsnc_grupo_actividad=mysql_fetch_assoc($sql);
	
}


?>

<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		
		if (document.frmsnc_grupo_actividad.descripcion.value.length==0){
			alert("Debe escribir una Descripcion para el Tipo de Presupuesto")
			document.frmsnc_grupo_actividad.descripcion.focus()
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
	<h4 align=center>Clases</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<form id="frmsnc_grupo_actividad" action="principal.php?modulo=3&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
  <input type="hidden" id="id" name="id" maxlength="9" size="9" <?php echo 'value="'.$regsnc_grupo_actividad['idsnc_grupo_actividad'].'"';?>>
		<table align=center cellpadding=2 cellspacing=0 width="70%">
<tr>
			  <td align='right' class='viewPropTitle'>Familias:</td>
			  <td class=''>
              <?php
              $sql = mysql_query("select * from snc_familia_actividad");
			  ?>
              <select name="familia_actividad" id="familia_actividad" <? if($_GET["accion"] == 117){echo " disabled";}?>>
              <?php
              while($bus = mysql_fetch_array($sql)){
			  ?>
			  <option <?php if(isset($_GET["id_familia"]) and $_GET["id_familia"] == $bus["idsnc_familia_actividad"]){ echo " selected='selected'";}?> value="<?php echo $bus["idsnc_familia_actividad"]?>"><?php echo $bus["codigo"]." ".$bus["descripcion"]?></option>
			  <?php
			  }
			  ?>
              </select>
&nbsp;<a href='principal.php?modulo=3&accion=81' class='Browse'><img src="imagenes/nuevo.png" border="0" title="Nuevo SNC Grupo Actividad"></a>


		  <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='block';">X</a></div>
          <table id="tableImprimir">
          	<tr>
            	<td>Ordenar Por: </td>
                <td>
                	<select name="ordenarPor" id="ordenarPor">
                    	 <option value="codigo">C&oacute;digo</option>
                         <option value="descripcion">Clases</option>
                    </select>               
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/compras_servicios/reportes.php?nombre=sncgrupo&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
                </td>
            </tr>
          </table>
          <iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>          
          </div>

              </td>
		  </tr>
          <tr>
				<td align='right' class='viewPropTitle'>C&oacute;digo:</td>
				<td class=''><input type="text" name="codigo" maxlength="2" size="5" id="codigo" <?php echo 'value="'.$regsnc_grupo_actividad['codigo'].'"'; if($_GET["accion"] == 117){echo " disabled";}?> onKeyUp="validarVacios('codigo', this.value, 'frmsnc_grupo_actividad')" onBlur="validarVacios('codigo', this.value, 'frmsnc_grupo_actividad')" autocomplete="OFF" style="padding:0px 20px 0px 0px;"></td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Descripci&oacute;n:</td>
				<td class=''><input type="text" name="descripcion" maxlength="255" size="130" id="descripcion" <?php echo 'value="'.$regsnc_grupo_actividad['descripcion'].'"';?> onKeyUp="validarVacios('descripcion', this.value, 'frmsnc_grupo_actividad')" onBlur="validarVacios('descripcion', this.value, 'frmsnc_grupo_actividad')" autocomplete="OFF" style="padding:0px 20px 0px 0px;"></td>
			</tr>
            
		</table>
<table align=center cellpadding=2 cellspacing=0>
			<tr><td>
			<?php
					if($_GET["accion"] != 117 and $_GET["accion"] != 118 and in_array(116, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 117 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 118 and in_array($_GET["accion"], $privilegios) == true){
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
			<table class="Main" cellpadding="0" cellspacing="0" width="90%">
				<tr>
					<td align="center">
					  <form name="grilla" action="" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
                                	<td align="center" class="Browse">C&oacute;digo</td>
									<td align="center" class="Browse">Clases</td>
                                   <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
									{ ?>
						  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
								$sql = mysql_query("select * from snc_familia_actividad where idsnc_familia_actividad = ".$llenar_grilla["idsnc_familia_actividad"]."");
								$bus = mysql_fetch_array($sql);
									echo "<td align='left' class='Browse'>".$llenar_grilla["codigo"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["descripcion"]."</td>";
									$c=$llenar_grilla["idsnc_grupo_actividad"];
									if(in_array(117,$privilegios)==true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=117&c=$c&id_familia=".$bus["idsnc_familia_actividad"]."' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(118,$privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=3&accion=118&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
if($_POST){
	$id=$_POST["id"];
	$descripcion=strtoupper($_POST["descripcion"]);
	$codigo = $_POST["codigo"];
	$familia_actividad = $_POST["familia_actividad"];
	$sql_familia_actividad=mysql_query("select * from snc_familia_actividad where idsnc_familia_actividad='".$familia_actividad."'",$conexion_db);
	$buscar_familia_actividad = mysql_fetch_array($sql_familia_actividad);
	$sigla=$buscar_familia_actividad["codigo"];
	$codigo=$sigla.$codigo;
	
	$busca_existe_registro=mysql_query("select * from snc_grupo_actividad where descripcion = '".$_POST['descripcion']."'  and status='a'",$conexion_db);
if($_GET["accion"] == 116 and in_array(116, $privilegios)==true){	
	if (mysql_num_rows($busca_existe_registro)>0){
		mensaje("Disculpe el registro que intenta ingresar ya existe");
		redirecciona("principal.php?modulo=3&accion=81");  // envia mensaje de error que el registro ya existe
	}else{
		
			mysql_query("insert into snc_grupo_actividad
									(descripcion,usuario,fechayhora,status, codigo, idsnc_familia_actividad) 
							values ('$descripcion','$login','$fh','a','$codigo', $familia_actividad)"
									,$conexion_db);
			registra_transaccion('Ingresar SNC Grupo Actividad ('.$descripcion.')',$login,$fh,$pc,'snc_grupo_actividad',$conexion_db);
			mensaje("El registro se inserto con Exito");
			redirecciona("principal.php?modulo=3&accion=81");
		}
	}
	if ($_GET["accion"] ==117 and in_array(117, $privilegios)==true and !$_POST["buscar"]){
			mysql_query("update snc_grupo_actividad set 
										descripcion='".$descripcion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idsnc_grupo_actividad = '$id' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar SNC Grupo Actividad ('.$descripcion.')',$login,$fh,$pc,'snc_grupo_actividad',$conexion_db);
			mensaje("El registro se Modifico con Exito");
			redirecciona("principal.php?modulo=3&accion=81");
	}
	if ($_GET["accion"] == 118 and in_array(118, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from snc_grupo_actividad where idsnc_grupo_actividad = '$id'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from snc_grupo_actividad where idsnc_grupo_actividad = '$id'",$conexion_db);
			if(!$sql_eliminar){
				registra_transaccion('Eliminar SNC Grupo Actividad (ERROR) ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
				redirecciona("principal.php?modulo=3&accion=81");
			}else{
				registra_transaccion('Eliminar SNC Grupo Actividad ('.$bus["descripcion"].')',$login,$fh,$pc,'sector',$conexion_db);
				mensaje("El registro se elimino con exito");
				redirecciona("principal.php?modulo=3&accion=81");
			}	
			
	}
}
?>