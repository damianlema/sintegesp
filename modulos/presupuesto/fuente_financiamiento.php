<script src="modulos/presupuesto/js/usuarios_fuentes_financiamiento_ajax.js" type="text/javascript" language="javascript"></script>
<?php
if($_POST["ingresar"]){
	$_GET["accion"] = 143;
}


$codigo=$_GET["c"];

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select * from fuente_financiamiento 
												where status='a' order by denominacion"
													,$conexion_db);
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from fuente_financiamiento where status='a'";
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
$idfuente_financiamiento = $_GET['c'];

if ($_GET["accion"] == 144 || $_GET["accion"] == 145){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select * from fuente_financiamiento 
										where idfuente_financiamiento like '".$_GET['c']."'"
											,$conexion_db);
	$regfuente_financiamiento=mysql_fetch_assoc($sql);

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
		
		if (document.frmfuente_financiamiento.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para la Fuente de Financiamiento")
			document.frmfuente_financiamiento.denominacion.focus()
			return false;
		}	
	} 

// end hiding from old browsers -->
</SCRIPT>
</head>
	<body>
	<br>
	<h4 align=center>Fuentes de Financiamiento</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<form name="frmfuente_financiamiento" action="principal.php?modulo=2&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
	<input type="hidden" id="codigo" name="codigo" maxlength="9" size="9" <?php echo 'value="'.$regfuente_financiamiento['idfuente_financiamiento'].'"';?>>
    <?php //echo $idfuente_financiamiento; ?>
		<table align=center cellpadding=2 cellspacing=0 width="50%">
			<tr>
				<td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class=''><input type="text" name="denominacion" maxlength="255" size="45" id="denominacion" <?php echo 'value="'.$regfuente_financiamiento['denominacion'].'"'; if ($_GET["accion"]==145) echo "disabled";?>>
			    &nbsp;<a href="principal.php?modulo=2&accion=45"><img src="imagenes/nuevo.png" border="0" title="Nuevo Ordinal"></a>
                
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/reportes/presupuesto/reportes.php?nombre=fuentes';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
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

					if($_GET["accion"] != 144 and $_GET["accion"] != 145 and in_array(143, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 144 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 145 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
			?>
				<input type="reset" value="Reiniciar" class="button">
			</td></tr>
		</table>
	</form>
	<br>
    <div id="usuarios_autorizados" style="display:block">
    <table align="center" width="50%">
    	<tr>
    		<td align="center" class="viewPropTitle" width="45%"><strong>Usuarios</strong></td>
            <td align="center" class="viewPropTitle" width="5%">&nbsp;</td>
            <td align="center" class="viewPropTitle" width="45%"><strong>Usuarios Asignados</strong></td>
    	</tr>
        <tr>
        	<td align="center">
            	<? 
				/*echo "select usuarios.cedula, usuarios.apellidos, usuarios.nombres from usuarios
														where usuarios.status = 'a'
															and usuarios.cedula Not in 
														(select cedula from usuarios_fuente_financiamiento where idfuente_financiamiento = '".$idfuente_financiamiento."')
															and usuarios.cedula In (select id_usuario from privilegios_modulo where id_modulo like '%-".$modulo."-%')
																				 order by apellidos, nombres";*/
				
					$sql_usuarios = mysql_query("select usuarios.cedula, usuarios.apellidos, usuarios.nombres from usuarios
														where usuarios.status = 'a'
															and usuarios.cedula Not in 
														(select cedula from usuarios_fuente_financiamiento where idfuente_financiamiento = '".$idfuente_financiamiento."')
																				order by nombres, apellidos")or die(mysql_error()); 
				?>
					<select name="usuarios_activos" id="usuarios_activos" multiple size="6">
                    	<? while ($bus_usuarios = mysql_fetch_array($sql_usuarios)){ ?>
	                    	<option value="<?=$bus_usuarios["cedula"];?>"><? echo $bus_usuarios["nombres"]." ".$bus_usuarios["apellidos"];?></option>
                    	<? } ?>
                    </select>
            </td>
            <td align="center" >
            	<img style="display:block; cursor:pointer"
                                        src="imagenes/fast_forward.png" 
                                        title="Asignar Usuario a Fuente de Financiamiento" 
                                        id="botonPasarUsuario" 
                                        name="botonPasarUsuario" 
                                        onclick="pasarUsuario()"/>
                  <br>
                  <img style="display:block; cursor:pointer"
                                        src="imagenes/rewind.png" 
                                        title="Quitar Usuario de Fuente de Financiamiento" 
                                        id="botonRegresarUsuario" 
                                        name="botonRegresarUsuario"
                                        onclick="regresarUsuario()"/>
            </td>
            <td align="center">
	            <? 	
				 /*echo "select * from usuarios_fuente_financiamiento, usuarios, privilegios_modulo 
																				where usuarios_fuente_financiamiento.cedula = usuarios.cedula
																			and usuarios_fuente_financiamiento.idfuente_financiamiento = '".$idfuente_financiamiento."'
																				and privilegios_modulo.id_usuario = usuarios.cedula
																				 order by apellidos, nombres";*/
				
				$sql_usuarios_asignados = mysql_query("select * from usuarios_fuente_financiamiento, usuarios, privilegios_modulo 
																				where usuarios_fuente_financiamiento.cedula = usuarios.cedula
																			and usuarios_fuente_financiamiento.idfuente_financiamiento = '".$idfuente_financiamiento."'
																			group by usuarios_fuente_financiamiento.cedula
																				 order by nombres, apellidos"); ?>
            	<select name="usuarios_asignados" id="usuarios_asignados" multiple size="6">
                	<? while ($bus_usuarios_asignados = mysql_fetch_array($sql_usuarios_asignados)){ ?>
	                    	<option value="<?=$bus_usuarios_asignados["cedula"];?>"><? echo $bus_usuarios_asignados["nombres"]." ".$bus_usuarios_asignados["apellidos"];?></option>
                    	<? } ?>
                </select>
            </td>
        </tr>
    </table>
	</div>





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
									$c=$llenar_grilla["idfuente_financiamiento"];
									if(in_array(144, $privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?accion=144&modulo=2&c=$c' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(145, $privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?accion=145&modulo=2&c=$c' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmfuente_financiamiento.denominacion.focus() </script>
</body>
</html>

<?php
if($_POST){

	$codigo=$_POST["codigo"];
	$denominacion=strtoupper($_POST["denominacion"]);
	$busca_existe_registro=mysql_query("select * from fuente_financiamiento where denominacion = '".$_POST['denominacion']."'  and status='a'",$conexion_db);
if($_GET["accion"] == 143 and in_array(143, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
			mostrarMensajes("error", "Disculpe el registro que esta insertando ya existe, vuelva a intentarlo");
			setTimeout("window.location.href='principal.php?modulo=2&accion=45'",5000);
			</script>
		<?
		
	}else{
		mysql_query("insert into fuente_financiamiento
									(denominacion,usuario,fechayhora,status) 
							values ('$denominacion','$login','$fh','a')"
									,$conexion_db);
			registra_transaccion('Ingresar Fuente de Financiamiento ('.$denominacion.')',$login,$fh,$pc,'fuente_financiamiento',$conexion_db);
			?>
		<script>
			mostrarMensajes("exito", "El registro se Inserto con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=45'",5000);
			</script>
		<?
		}
	}
	if ($_GET["accion"] == 144 and in_array(144, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update fuente_financiamiento set 
										denominacion='".$denominacion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idfuente_financiamiento = '$codigo' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Fuente de Financiamiento ('.$denominacion.')',$login,$fh,$pc,'fuente_financiamiento',$conexion_db);
			
			?>
		<script>
			mostrarMensajes("exito", "El regsitro se Modficio con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=45'",5000);
			</script>
		<?
	}
	if ($_GET["accion"] == 145 and in_array(145, $privilegios) == true and !$_POST["buscar"]){
			$sql_eliminar = mysql_query("delete from fuente_financiamiento where idfuente_financiamiento = '$codigo'",$conexion_db);	
			if(!$_sql_eliminar){
				registra_transaccion('Eliminar Fuente de Financiamiento (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
			mostrarMensajes("error", "Disculpe el registro que intenta eliminar parece estar relacionado con otros registros por ello no puede ser eliminado");
			setTimeout("window.location.href='principal.php?modulo=2&accion=45'",5000);
			</script>
		<?
			
			}else{
				registra_transaccion('Eliminar Fuente de Financiamiento ('.$bus["denominacion"].')',$login,$fh,$pc,'sector',$conexion_db);
				?>
		<script>
			mostrarMensajes("exito", "El regsitro se Elimino con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=45'",5000);
			</script>
		<?
			}
	}
}
?>
