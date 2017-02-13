<?php
if($_POST["ingresar"]){
	$_GET["accion"] = 158;
}

$idsub_programa=$_GET["c"];
$codigoprograma=$_GET["p"];
$codigosector=$_GET["s"];

$sectores=mysql_query("select * from sector 
											where status='a'"
												,$conexion_db);
												
$sql_programas=mysql_query("select * from programa 
											where status='a'"
												,$conexion_db);
												
$entro_sector=false;

if ($_POST["sectores"]<>0 and $_GET["accion"] == 37){
	$entro_sector=true;
	$sector_seleccionado=$_POST["sectores"];
	$sql_programas=mysql_query("select * from programa 
											where status='a' and idsector=".$sector_seleccionado." order by codigo"
												,$conexion_db);
}										
											
if ($buscar_registros==0){
	$registros_grilla=mysql_query("select 	sector.denominacion as denosector, 
											sector.codigo as codigosector,
											programa.denominacion as denoprograma, 
											programa.codigo as codigoprograma,
											sub_programa.denominacion,
											sub_programa.idsub_programa,
											sub_programa.codigo
											from sector,programa,sub_programa 
												where sub_programa.status='a'
												and sector.idsector = programa.idsector
												and programa.idprograma = sub_programa.idprograma
											order by codigosector, codigoprograma, codigo"
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
											sub_programa.denominacion,
											sub_programa.idsub_programa,
											sub_programa.codigo
											from sector,programa,sub_programa 
												where sub_programa.status='a'
												and sector.idsector = programa.idsector
												and programa.idprograma = sub_programa.idprograma";
	
	
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			if ($campo_busqueda=="c"){
				$registros_grilla=mysql_query($sql." and sub_programa.codigo like '$texto_buscar%' order by codigosector, codigoprograma, codigo",$conexion_db);
			}
			if ($campo_busqueda=="d"){
				$registros_grilla=mysql_query($sql." and sub_programa.denominacion like '$texto_buscar%' order by codigosector, codigoprograma, codigo",$conexion_db);
			}
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}

if ($_GET["accion"] == 159 || $_GET["accion"] == 160){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$conexion_db=conectarse();
	$sql=mysql_query("select sector.denominacion as denosector, 
											sector.codigo as codigosector,
											sector.idsector as idsector,
											programa.denominacion as denoprograma, 
											programa.codigo as codigoprograma,
											programa.idprograma as idprograma,
											sub_programa.denominacion,
											sub_programa.codigo,
											sub_programa.idsub_programa
												from 
											sector,programa,sub_programa
												where 
											sub_programa.status='a'
											and sub_programa.idsub_programa = '".$_GET["c"]."'
											and sector.idsector = programa.idsector
											and programa.idprograma = sub_programa.idprograma
											order by codigosector, codigoprograma, codigo",$conexion_db);
	$regsub_programa=mysql_fetch_assoc($sql);
	
	$sql_programas=mysql_query("select * from programa 
											where status='a' and idsector=".$regsub_programa["idsector"]." order by codigo"
												,$conexion_db);
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
		if (document.frmsub_programa.codigo.value.length==0){
			alert("Debe escribir un C&oacute;digo para el Sub_Programa")
			document.frmsub_programa.codigo.focus()
			return false;
		}
		if (document.frmsub_programa.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para el Sub_Programa")
			document.frmsub_programa.denominacion.focus()
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
	<h4 align=center>Sub-Programas</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<? 
	//echo $idsub_programa;
	?>
	<form name="frmsub_programa" action="principal.php?modulo=2&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">
    <input type="hidden" name="idsub_programa" id="idsub_programa" <?php echo 'value="'.$idsub_programa.'"';?>>	
		<table align=center cellpadding=2 cellspacing=0 width="60%">
			<tr>
			<td align='right' class='viewPropTitle'>Sector:</td>
			<td class='viewProp'>
				<select name="sectores" onChange="this.form.submit()" style="width:60%">
					<option>&nbsp;</option>
					<?php
						while($rowsector = mysql_fetch_array($sectores)) 
							{ 
								?>
									<option <?php echo "value='".$rowsector["idSector"]."'"; 
													if ($entro_sector and $rowsector["idSector"]==$sector_seleccionado)
														{echo " selected";} 
													if ($_GET["accion"] == 159 || $_GET["accion"] == 160) 
														{if ($rowsector["codigo"]==$codigosector)
															{echo " selected";}
														}?>
                                                   ><?php echo $rowsector["codigo"]." ".$rowsector["denominacion"];?></option>
					<?php
							}
					?>
				</select>
                
                &nbsp;<a href="principal.php?modulo=2&accion=37"><img src="imagenes/nuevo.png" border="0" title="Nuevo Sub Programa"></a>			 
                
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
                        <option value="sub_programa.denominacion">Denominaci&oacute;n</option>
                    </select>               
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/presupuesto/reportes.php?nombre=sprograma&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
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
				<select name="programas" style="width:60%">
					<option>&nbsp;</option>
					<?php
						while($rowprograma = mysql_fetch_array($sql_programas)) 
							{ 
								?>
									<option <?php echo "value='".$rowprograma["idprograma"]."'"; 
													if ($_GET["accion"] == 159 || $_GET["accion"] == 160) {
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
				<td align='right' class='viewPropTitle'>C&oacute;digo:</td>
				<td class=''><input type="text" id="codigo" name="codigo" maxlength="2" size="2" onKeyPress="javascript:return solonumeros(event)" <?php if (isset($_POST["codigo"])){ echo "value=".$_POST["codigo"];} else {echo "value=".$regsub_programa["codigo"];}?>>
				</td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Denominaci&oacute;n:</td>
				<td class=''><input type="text" name="denominacion" maxlength="255" size="60" id="denominacion" <?php echo 'value="'.$regsub_programa['denominacion'].'"'; if ($_GET["accion"] == 160) echo "disabled";?>></td>
			</tr>
		</table>
		<table align=center cellpadding=2 cellspacing=0>
			<tr><td>
					<?php

					if($_GET["accion"] != 159 and $_GET["accion"] != 160 and in_array(158, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 159 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 160 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
			?>				<input type="reset" value="Reiniciar" class="button">
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
									<td width="27%" align="center" class="Browse" >Sector</td>
								  <td width="27%" align="center" class="Browse" >Programa</td>
								  <td width="5%" align="center" class="Browse" >C&oacute;digo</td>
								  <td width="30%" align="center" class="Browse" >Sub-Programa</td>
								  <td align="center" class="Browse" colspan="2" >Acci&oacute;n</td>
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
									echo "<td align='center' class='Browse'>".$llenar_grilla["codigo"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["denominacion"]."</td>";
									$c=$llenar_grilla["idsub_programa"];
									$p=$llenar_grilla["codigoprograma"];
									$s=$llenar_grilla["codigosector"];
									if(in_array(159, $privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=159&c=$c&p=$p&s=$s' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									}
									if(in_array(160, $privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=160&c=$c&p=$p&s=$s' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmsub_programa.sector.focus() </script>
</body>
</html>

<?php
if($_POST){
	
	$sectores=$_POST["sectores"];
	$programas=$_POST["programas"];
	$codigo=$_POST["codigo"];
	$denominacion=strtoupper($_POST["denominacion"]);
	$idsub_programa=$_POST["idsub_programa"];
	$busca_existe_registro=mysql_query("select * from sub_programa where codigo like '".$_POST['codigo']."'  and status='a' and idprograma='".$programas."'",$conexion_db);
	
if($_GET["accion"] == 158 and in_array(158, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
		
		?>
				<script>
			mostrarMensajes("error", "Disculpe el regsitro que ingreso ya Exite, Vuelva a intentarlo");
			setTimeout("window.location.href='principal.php?modulo=2&accion=37'",5000);
			</script>

		<?
	}else{
		mysql_query("insert into sub_programa
									(codigo,denominacion,idprograma,usuario,fechayhora,status) 
							values ('$codigo','$denominacion','$programas','$login','$fh','a')"
									,$conexion_db);
			registra_transaccion('Ingresar Sub Programas ('.$denominacion.')',$login,$fh,$pc,'sub_programa',$conexion_db);
			
			?>
				<script>
			mostrarMensajes("exito", "El regsitro se Ingreso con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=37'",5000);
			</script>

		<?
			

		}
	}
	if ($_GET["accion"] == 159 and in_array(159, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("update sub_programa set 
										denominacion='".$denominacion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	idsub_programa='".$idsub_programa."'");
			registra_transaccion('Modificar Sub Programas ('.$denominacion.')',$login,$fh,$pc,'sub_programa',$conexion_db);
			?>
				<script>
			mostrarMensajes("exito", "El regsitro se Modifico con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=37'",5000);
			</script>

		<?
			

	}
	if ($_GET["accion"] == 160 and in_array(160, $privilegios) == true and !$_POST["buscar"]){
			$sql_eliminar = mysql_query("delete from sub_programa where idsub_programa='".$idsub_programa."'");	
			
			if(!$sql_eliminar){
				registra_transaccion('Eliminar Sub Programa (ERROR) ('.$bus["denominacion"].')',$login,$fh,$pc,'sub_programa',$conexion_db);
				?>
				<script>
			mostrarMensajes("error", "Disculpe no se pudo eliminar el dato seleccionado, Probablemente este dato este siendo usado por otra tabla");
			setTimeout("window.location.href='principal.php?modulo=3&accion=37'",5000);
			</script>

		<?
			}else{
				registra_transaccion('Eliminar Sub Programa ('.$bus["denominacion"].')',$login,$fh,$pc,'sub_programa',$conexion_db);
				
				?>
				<script>
			mostrarMensajes("exito", "El registro se Elimino con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=37'",5000);
			</script>

		<?

			}

	}
}
?>
