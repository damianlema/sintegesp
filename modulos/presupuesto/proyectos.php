<?php
if($_POST["ingresar"]){
	$_GET["accion"] = 152;
}
$idproyecto=$_GET["y"];
$codigosub_programa=$_GET["c"];
$codigoprograma=$_GET["p"];
$codigosector=$_GET["s"];

$sectores=mysql_query("select * from sector 
											where status='a'"
												,$conexion_db);
												
$sql_programas=mysql_query("select * from programa 
											where status='a'"
												,$conexion_db);

$sql_sub_programas=mysql_query("select * from sub_gprograma 
											where status='a'"
												,$conexion_db);
											
$entro_sector=false;
$entro_programa=false;

if ($_POST["sectores"]<>0 and $_GET["accion"] == 38){
	$entro_sector=true;
	$sector_seleccionado=$_POST["sectores"];
	$sql_programas=mysql_query("select * from programa 
											where status='a' and idsector=".$sector_seleccionado." order by codigo"
												,$conexion_db);
}				

if ($_POST["programas"]<>0 and $_GET["accion"] == 38){
	$entro_programa=true;
	$programa_seleccionado=$_POST["programas"];
	$sql_sub_programas=mysql_query("select * from sub_programa 
											where status='a' and idprograma=".$programa_seleccionado." order by codigo"
												,$conexion_db);
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select 	sector.denominacion as denosector, 
											sector.codigo as codigosector,
											programa.denominacion as denoprograma, 
											programa.codigo as codigoprograma,
											sub_programa.denominacion as denosubprograma,
											sub_programa.codigo as codigosubprograma,
											proyecto.denominacion,
											proyecto.idproyecto,
											proyecto.codigo
											from sector,programa,sub_programa,proyecto
												where proyecto.status='a'
												and sector.idsector = programa.idsector
												and programa.idprograma = sub_programa.idprograma
												and sub_programa.idsub_programa = proyecto.idsub_programa
											order by codigosector, codigoprograma, codigosubprograma, codigo"
													,$conexion_db);

	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
	}

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$campo_busqueda=$_POST["tipobusqueda"];
	$sql="select 	sector.denominacion as denosector, 
											sector.codigo as codigosector,
											programa.denominacion as denoprograma, 
											programa.codigo as codigoprograma,
											sub_programa.denominacion as denosubprograma,
											sub_programa.codigo as codigosubprograma,
											proyecto.denominacion,
											proyecto.idproyecto,
											proyecto.codigo
											from sector,programa,sub_programa ,proyecto
												where proyecto.status='a'
												and sector.idsector = programa.idsector
												and programa.idprograma = sub_programa.idprograma
												and sub_programa.idsub_programa = proyecto.idsub_programa";
	
	
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			if ($campo_busqueda=="c"){
				$registros_grilla=mysql_query($sql." and proyecto.codigo like '$texto_buscar%' order by codigosector, codigoprograma, codigosubprograma, codigo",$conexion_db);
			}
			if ($campo_busqueda=="d"){
				$registros_grilla=mysql_query($sql." and proyecto.denominacion like '$texto_buscar%' order by codigosector, codigoprograma, codigosubprograma, codigo",$conexion_db);
			}
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}

if ($_GET["accion"] == 153 || $_GET["accion"] == 154 and isset($_GET["y"])){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$conexion_db=conectarse();
	
	$sql=mysql_query("select sector.denominacion as denosector, 
											sector.codigo as codigosector,
											sector.idsector as idsector,
											programa.denominacion as denoprograma, 
											programa.codigo as codigoprograma,
											programa.idprograma as idprograma,
											sub_programa.denominacion as denosubprograma,
											sub_programa.codigo as codigosubprograma,
											sub_programa.idsub_programa as idsubprograma,
											proyecto.denominacion,
											proyecto.idproyecto,
											proyecto.codigo
											from sector,programa,sub_programa ,proyecto
												where proyecto.status='a'
												and proyecto.idproyecto = $idproyecto
												and sector.idsector = programa.idsector
												and programa.idprograma = sub_programa.idprograma
												and sub_programa.idsub_programa = proyecto.idsub_programa
												order by codigosector, codigoprograma, codigosubprograma, codigo"
													,$conexion_db)or die(mysql_error());
	$regproyecto=mysql_fetch_assoc($sql);
	
	$sql_programas=mysql_query("select * from programa 
											where status='a' and idsector=".$regproyecto["idsector"]." order by codigo"
												,$conexion_db);

	$sql_sub_programas=mysql_query("select * from sub_gprograma 
											where status='a' and idprograma=".$regproyecto["idprograma"]." order by codigo"
												,$conexion_db);
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
		if (document.frmproyecto.codigo.value.length==0){
			alert("Debe escribir un C&oacute;digo para el Proyecto")
			document.frmproyecto.codigo.focus()
			return false;
		}
		if (document.frmproyecto.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para el Proyecto")
			document.frmproyecto.denominacion.focus()
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
	<h4 align=center>Proyectos</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	
	<form name="frmproyecto" action="principal.php?modulo=2&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">	
		<table align=center cellpadding=2 cellspacing=0 width="60%">
			<tr>
			<td align='right' class='viewPropTitle'>Sector:</td>
			<td class='viewProp'>
	            <input type="hidden" value="<?=$_GET["y"]?>" name="y">
                
				<select name="sectores" onChange="this.form.submit()" style="width:60%">
					<option>&nbsp;</option>
					<?php
						while($rowsector = mysql_fetch_array($sectores)) 
							{ 
								?>
									<option <?php echo "value=".$rowsector["idSector"]; 
													if ($entro_sector and $rowsector["idSector"]==$sector_seleccionado)
														{echo " selected";} 
													if ($_GET["accion"] == 153 || $_GET["accion"] == 154) 
														{if ($rowsector["codigo"]==$codigosector)
															{echo " selected";}
														}?>><?php echo $rowsector["codigo"]." ".$rowsector["denominacion"];?></option>
					<?php
							}
					?>
				</select>
&nbsp;<a href="principal.php?modulo=2&accion=38"><img src="imagenes/nuevo.png" border="0" title="Nuevo Proyecto"></a>

		  <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='block';">X</a></div>
          <table id="tableImprimir">
          	<tr>
            	<td>Ordenar Por: </td>
                <td>
                	<select name="ordenarPor" id="ordenarPor">
                    	<option value="sector.denominacion">Sector</option>
                        <option value="programa.denominacion">Programa</option>
                        <option value="sub_programa.denominacion">Sub-Programa</option>
                        <option value="proyecto.denominacion">Denominaci&oacute;n</option>
                    </select>               
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/presupuesto/reportes.php?nombre=proyecto&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
                </td>
            </tr>
          </table>
          <iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>          
          </div>


		</td>
			</tr>
			
			<tr>
			<td align='right' class='viewPropTitle'>Programa:</td>
			<td class='viewProp'>
				<select name="programas" onChange="this.form.submit()" style="width:60%">
					<option>&nbsp;</option>
					<?php
						while($rowprograma = mysql_fetch_array($sql_programas)) 
							{ 
								?>
									<option <?php echo "value='".$rowprograma["idprograma"]."'"; 
													if ($_POST["programas"]<>""){
														if ($rowprograma["idprograma"]==$_POST["programas"]){
															echo " selected";
														}
													}
													if ($_GET["accion"] == 153 || $_GET["accion"] == 154) {
														if ($rowprograma["codigo"]==$codigoprograma){
															echo " selected";
														}
													}
											?>
                                     		>
											<?php echo $rowprograma["codigo"]." ".$rowprograma["denominacion"];?></option>
                                   
					<?php
							}
					?>
				</select> 
			</td>
			</tr>
			
			<tr>
			<td align='right' class='viewPropTitle'>Sub-Programa:</td>
			<td class='viewProp'>
				<select name="subprogramas"  style="width:60%">
					<option>&nbsp;</option>
					<?php
						if ($_GET["accion"] == 153 || $_GET["accion"] == 154) 
							{echo "<option value=".$regproyecto["idsubprograma"]." selected>".$regproyecto["codigosubprograma"]." ".$regproyecto["denosubprograma"]."</option>";}
							
						while($rowsub_programa = mysql_fetch_array($sql_sub_programas)) 
							{
								?>
									<option <?php echo "value=".$rowsub_programa["idsub_programa"]?> > <?=$rowsub_programa["codigo"]." ".$rowsub_programa["denominacion"];?></option>
					<?php
							}
					?>
				</select> 
			</td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>C&oacute;digo:</td>
				<td class=''><input type="text" id="codigo" name="codigo" maxlength="2" size="2" onKeyPress="javascript:return solonumeros(event)" <?php if (isset($_POST["codigo"])){ echo "value=".$_POST["codigo"];} else {echo "value=".$regproyecto["codigo"];} if ($modo==1 || $modo==2) //echo " disabled";?>>
				</td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class=''><input type="text" name="denominacion" maxlength="255" size="60" id="denominacion" <?php echo 'value="'.$regproyecto['denominacion'].'"'; if ($_GET["accion"] == 154) echo "disabled";?>></td>
			</tr>
		</table>
		<table align=center cellpadding=2 cellspacing=0>
			<tr><td>
					<?php

					if($_GET["accion"] != 153 and $_GET["accion"] != 154 and in_array(152, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 153 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 154 and in_array($_GET["accion"], $privilegios) == true){
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
						<form name="grilla" action="" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<td width="20%" align="center" class="Browse">Sector</td>
								  <td width="20%" align="center" class="Browse">Programa</td>
								  <td width="20%" align="center" class="Browse">SubPrograma</td>
								  <td width="5%" align="center" class="Browse">C&oacute;digo</td>
								  <td width="25%" align="center" class="Browse">Proyecto</td>
								  <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de programas 
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
									{ ?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
									echo "<td align='left' class='Browse'>".$llenar_grilla["codigosector"]." ".$llenar_grilla["denosector"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["codigoprograma"]." ".$llenar_grilla["denoprograma"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["codigosubprograma"]." ".$llenar_grilla["denosubprograma"]."</td>";
									echo "<td align='center' class='Browse'>".$llenar_grilla["codigo"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["denominacion"]."</td>";
									$y=$llenar_grilla["idproyecto"];
									$c=$llenar_grilla["codigosubprograma"];
									$p=$llenar_grilla["codigoprograma"];
									$s=$llenar_grilla["codigosector"];
									if(in_array(153, $privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo2&accion=153&c=$c&p=$p&s=$s&y=$y' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(154, $privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo2&accion=154&c=$c&p=$p&s=$s&y=$y' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmproyecto.sector.focus() </script>
</body>
</html>

<?php
if($_POST){
	
	$pos_sectores=$_POST["sectores"];
	$pos_programas=$_POST["programas"];
	$pos_subprogramas=$_POST["subprogramas"];
	$codigo=$_POST["codigo"];
	$denominacion=strtoupper($_POST["denominacion"]);
	$busca_existe_registro=mysql_query("select * from proyecto where codigo like '".$_POST['codigo']."'  and status='a' and idsub_programa='".$pos_subprogramas."'",$conexion_db);
if($_GET["accion"] == 152 and in_array(152, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
				<script>
			mostrarMensajes("error", "Disculpe el registro que intenta Ingresar ya Existe, Vuelva a Intentarlo");
			setTimeout("window.location.href='principal.php?modulo=2&accion=38'",5000);
			</script>

		<?
	}else{
		mysql_query("insert into proyecto
									(codigo,denominacion,idsub_programa,usuario,fechayhora,status) 
							values ('$codigo','$denominacion','$pos_subprogramas','$login','$fh','a')"
									,$conexion_db);
			registra_transaccion('Ingresar Proyectos ('.$denominacion.')',$login,$fh,$pc,'proyecto',$conexion_db);
		
				?>
				<script>
			mostrarMensajes("exito", "El registro se Ingreso con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=38'",5000);
			</script>

		<?

		}
	}
	if ($_GET["accion"] == 153 and in_array(153, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update proyecto set 
										denominacion='".$denominacion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	codigo = '$codigo' and idsub_programa='".$pos_subprogramas."' and status = 'a'",$conexion_db);
			registra_transaccion('Modificar Proyecto ('.$denominacion.')',$login,$fh,$pc,'proyecto',$conexion_db);
		
				?>
				<script>
			mostrarMensajes("exito", "El regsitro se Modifico con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=38'",5000);
			</script>

		<?

	}
	if ($_GET["accion"] == 154 and in_array(154, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from proyecto where codigo = '$codigo' and status = 'a' and idsub_programa='".$pos_subprogramas."'");
			$bus = mysql_fetch_array($sql);
			$sql_eliminar = mysql_query("delete from proyecto where codigo = '$codigo' and status = 'a' and idsub_programa='".$pos_subprogramas."'",$conexion_db);	
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Proyectos (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'proyecto',$conexion_db);
				
						?>
				<script>
			mostrarMensajes("error", "Disculpe el regsitro que intenta eliminar parece estar relacionado con otra registro, por ello no puede ser eliminado");
			setTimeout("window.location.href='principal.php?modulo=2&accion=38'",5000);
			</script>

		<?

			
			}else{
				registra_transaccion('Eliminar Proyectos ('.$bus["denominacion"].')',$login,$fh,$pc,'proyecto',$conexion_db);
				
						?>
				<script>
			mostrarMensajes("exito", "El registro se Elimino con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=38'",5000);
			</script>

		<?
			}
			

	}
}
?>
