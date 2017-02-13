<?php
if($_POST["ingresar"]){
$_GET["accion"] = 85;
}

$existen_registros=0; // switch para validar si hay datos a cargar en la grilla 0 existen 1 no existen



if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from grupos 
												where status='a'"
													);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$campo_busqueda=$_POST["tipobusqueda"];
	$sql="select * from grupos where status='a'";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql);
		}else{
			if ($campo_busqueda=="c"){
				$registros_grilla=mysql_query($sql." and idgrupo like '%$texto_buscar%'");
			}
			if ($campo_busqueda=="d"){
				$registros_grilla=mysql_query($sql." and denominacion like '%$texto_buscar%'");
			}
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}


	//$conexion_db=conectarse();
if($_GET["c"]){
	$sql=mysql_query("select * from grupos 
										where idgrupo like '".$_GET['c']."'"
											,$conexion_db);
	$registro_actualizar=mysql_fetch_assoc($sql);
}

?>


<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		if (document.frmgrupos.idgrupo.value.length==0){
			alert("Debe escribir un C&oacute;digo para el Grupo.")
			document.frmgrupos.idgrupo.focus()
			return false;
		}
		if (document.frmgrupos.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para el Grupo.")
			document.frmgrupos.denominacion.focus()
			return false;
		}	
	} 
// end hiding from old browsers -->
</SCRIPT>
	
	<br>
	<h4 align=center>Grupos de Cargos</h4>
	<h2 class="sqlmVersion"></h2>
		<form name="frmgrupos"  method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()" action="principal.php?modulo=1&accion=<?=$_GET["accion"]?>">	
    <input type="hidden" value="<?php echo $registro_actualizar["idgrupo"];?>" name="codigoOculto">
    <br>
	<table border="0" align="center" cellpadding="0" cellspacing=0>
			<tr>
            	<td align='right' class='viewPropTitle'>C&oacute;digo :</td>
				<td class='viewProp'><input type="text" name="idgrupo" value="<?php echo $registro_actualizar["idgrupo"];?>" maxlength="5" size="5" <?php if ($_GET["accion"]==87) echo"disabled"?>> 
                
          &nbsp;<a href="principal.php?accion=10&modulo=1"><img src="imagenes/nuevo.png" border="0" title="Nuevo Grupo de Cargos"></a>
          
          

          <a href="#" onclick="document.getElementById('divTipoOrden').style.display='block';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onclick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='block';">X</a></div>
          <table id="tableImprimir">
          	<tr>
            	<td>Ordenar Por: </td>
                <td>
                	<select name="ordenarPor" id="ordenarPor">
                    	<option value="idgrupo">C&oacute;digo</option>
                        <option value="denominacion">Denominaci&oacute;n</option>
                    </select>                    
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=grupos&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
                </td>
            </tr>
          </table>
          <iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>          
          </div>
          
          
          
            </td>
			</tr>
			<tr>
            	<td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class='viewProp'><input type="text" name="denominacion" value="<?php echo $registro_actualizar["denominacion"];?>" maxlength="80" size="50" <?php if ($_GET["accion"]==87) echo"disabled"?>></td>
			</tr>
	</table>
	
	<table align= center cellspacing=0>
		<tr>
        	<td>
			<?php
					
					if($_GET["accion"] != 86 and $_GET["accion"] != 87 and in_array(85, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 86 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 87 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}

        	        //botones(85,86,87,$modo,"0",$nivel);
				
            ?>
                
                <input type="reset" value="Reiniciar" class="button">
                
            </td>
        </tr>
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
	
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="60%">
				<tr>
					<td align="right">
						<form name="grilla" action="" method="POST">
						<table class="Browse" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<td align="center" class="Browse" width="10%">C&oacute;digo</td>
									<td align="center" class="Browse" width="81%">Grupo de Cargo</td>
									<td align="center" class="Browse" width="9%" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
									{ ?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
									echo "<td align='center' class='Browse' width='10%'>".$llenar_grilla["idgrupo"]."</td>";
									echo "<td align='left' class='Browse' width='81%'>".$llenar_grilla["denominacion"]."</td>";
									$c=$llenar_grilla["idgrupo"];
									if(in_array(86, $privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?accion=86&modulo=1&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
										}
									if(in_array(87, $privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?accion=87&modulo=1&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
									}
									echo "</tr>";
									}
							?>
						</table>
						</form>
					</td>
				</tr>
			</table>
		</div>
<script> document.frmgrupos.idgrupo.focus() </script>


<?php
if($_POST){

$codigo_grupo=$_POST["idgrupo"];
$denominacion=$_POST["denominacion"];
$codigoOculto = $_POST["codigoOculto"];

	if ($_GET["accion"] == 85 and in_array(85, $privilegios) == true){  //modo para agregar un registro
		$busca_existe_registro=mysql_query("select * from grupos where idgrupo like '".$codigo_grupo."'  and status='a'");
		
		if (mysql_num_rows($busca_existe_registro)>0){
			mensaje("Disculpe el Registro que inserto ya Existe, Vuelva a Intentarlo");
			redirecciona("principal.php?accion=10&modulo=1");
			//header("location:error_rrhh.php?err=0");  // envia mensaje de error que el registro ya existe
		}else{
			mysql_query("insert into grupos 
										(idgrupo,denominacion,usuario,fechayhora,status) 
									values ('$codigo_grupo','$denominacion','$login','$fh','a')"
										);
			registra_transaccion('Ingresar Grupos de Cargos ('.$denominacion.')',$login,$fh,$pc,'grupos');
			mensaje("El Registro se Inserto con Exito");
			redirecciona("principal.php?accion=10&modulo=1");
		}
	}
	if($_GET["accion"] == 86 and in_array(86, $privilegios) == true and !$_POST["buscar"]){

				mysql_query("update grupos set  denominacion='".strtoupper($denominacion)."',
												usuario='".$login."', 
												fechayhora='".$fh."' 
													where idgrupo like '$codigo_grupo' 
														and status='a'");
				registra_transaccion('Modificar Grupos de Cargos ('.$denominacion.')',$login,$fh,$pc,'grupos');
				mensaje("El registro se Modifico con Exito");
				redirecciona("principal.php?accion=10&modulo=1");
	}
	
	if($_GET["accion"] == 87 and in_array(87, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from grupos where idgrupo = '$codigoOculto'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from grupos where idgrupo = '$codigoOculto'");
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Grupos de cargos (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'grupos');
				mensaje("Disculpe el regsitro que intenta eliminar tiene relacioncon otro regsitro dentro del sistema, por ello no puede ser eliminado");
				redirecciona("principal.php?accion=10&modulo=1");			
			
			}else{
				registra_transaccion('Eliminar Grupos de cargos ('.$bus["denominacion"].')',$login,$fh,$pc,'grupos');
				mensaje("El registro se Elimino con Exito");
				redirecciona("principal.php?accion=10&modulo=1");			
			}

	}
	
}
?>
