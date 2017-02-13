<?php
if($_POST["ingresar"]){
$_GET["accion"] = 137;
}

$idcategoriaprogramatica=$_GET["cp"];
$codigounidadejecutora=$_GET["u"];
$codigoactividad=$_GET["a"];
$codigoproyecto=$_GET["y"];
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
											
$sql_proyectos=mysql_query("select * from proyecto
											where status='a'"
												,$conexion_db);

$sql_actividad=mysql_query("select * from actividad
											where status='a'"
												,$conexion_db);

$sql_unidad_ejecutora=mysql_query("select * from unidad_ejecutora
												where status='a' order by codigo"
													,$conexion_db);
											
$entro_sector=false;
$entro_programa=false;
$entro_subprograma=false;
$entro_proyecto=false;
$entro_actividad=false;

if ($_POST["sectores"]<>0 and $_GET["accion"] == 41){
	$entro_sector=true;
	$sector_seleccionado=$_POST["sectores"];
	$sql_programas=mysql_query("select * from programa 
											where status='a' and idsector=".$sector_seleccionado." order by codigo"
												,$conexion_db);
											
	$sql_sectores=mysql_query("select * from sector
											where status='a' and idsector=".$sector_seleccionado." order by codigo"
												,$conexion_db);
	$regsector=mysql_fetch_assoc($sql_sectores);
	$codigosector=$regsector["codigo"];
}				

if ($_POST["programas"]<>0 and $_GET["accion"] == 41){
	$entro_programa=true;
	$programa_seleccionado=$_POST["programas"];
	$sql_sub_programas=mysql_query("select * from sub_programa 
											where status='a' and idprograma=".$programa_seleccionado." order by codigo"
												,$conexion_db);
											
	$sql_programas2=mysql_query("select * from programa 
											where status='a' and idprograma=".$programa_seleccionado." order by codigo"
												,$conexion_db);
	$regprograma=mysql_fetch_assoc($sql_programas2);
	$codigoprograma=$regprograma["codigo"];										
	
}

if ($_POST["subprogramas"]<>0 and $_GET["accion"] == 41){
	$entro_subprograma=true;
	$subprograma_seleccionado=$_POST["subprogramas"];
	$sql_proyectos=mysql_query("select * from proyecto 
											where status='a' and idsub_programa=".$subprograma_seleccionado." order by codigo"
												,$conexion_db);
											
	$sql_sub_programas2=mysql_query("select * from sub_programa 
											where status='a' and idsub_programa=".$subprograma_seleccionado." order by codigo"
												,$conexion_db);
	$regsubprograma=mysql_fetch_assoc($sql_sub_programas2);
	$codigosubprograma=$regsubprograma["codigo"];											
}

if ($_POST["proyectos"]<>0 and $_GET["accion"] == 41){
	$entro_proyecto=true;
	$proyecto_seleccionado=$_POST["proyectos"];
	$sql_actividad=mysql_query("select * from actividad 
											where status='a' and idproyecto=".$proyecto_seleccionado." order by codigo"
												,$conexion_db);
											
	$sql_proyectos2=mysql_query("select * from proyecto
											where status='a' and idproyecto=".$proyecto_seleccionado." order by codigo"
												,$conexion_db);
											
	$regproyecto=mysql_fetch_assoc($sql_proyectos2);
	$codigoproyecto=$regproyecto["codigo"];											
}

if ($_POST["actividad"]<>0 and $_GET["accion"] == 41){
	$entro_actividad=true;
	$actividad_seleccionado=$_POST["actividad"];
	$sql_actividad2=mysql_query("select * from actividad 
											where status='a' and idActividad=".$actividad_seleccionado." order by codigo"
												,$conexion_db);
											
	$regactividad=mysql_fetch_assoc($sql_actividad2);
	$codigoactividad=$regactividad["codigo"];											
}

if ($_POST["unidad_ejecutora"]<>0 and $_GET["accion"] == 41){
	$entro_unidadejecutora=true;
	$unidadejecutora_seleccionado=$_POST["unidad_ejecutora"];
	$sql_unidadejecutora2=mysql_query("select * from unidad_ejecutora 
											where status='a' and idunidad_ejecutora=".$unidadejecutora_seleccionado." order by codigo"
												,$conexion_db);
											
	$regunidadejecutora=mysql_fetch_assoc($sql_unidadejecutora2);
	$codigounidadejecutora=$regunidadejecutora["codigo"];											
}

if ($buscar_registros==0){
	$registros_grilla=mysql_query("select 	sector.denominacion as denosector, 
											sector.codigo as codigosector,
											programa.denominacion as denoprograma, 
											programa.codigo as codigoprograma,
											sub_programa.denominacion as denosubprograma,
											sub_programa.codigo as codigosubprograma,
											proyecto.denominacion as denoproyecto,
											proyecto.codigo as codigoproyecto,
											actividad.denominacion as denoactividad,
											actividad.codigo as codigoactividad,
											unidad_ejecutora.denominacion as denounidadejecutora,
											unidad_ejecutora.codigo as codigounidadejecutora,
											unidad_ejecutora.responsable as responsableunidadejecutora,
											categoria_programatica.codigo,
											categoria_programatica.idcategoria_programatica,
											categoria_programatica.anio
											from sector,programa,sub_programa,proyecto,actividad,unidad_ejecutora,categoria_programatica
												where categoria_programatica.status='a'
												and sector.idsector = programa.idsector
												and programa.idprograma = sub_programa.idprograma
												and sub_programa.idsub_programa = proyecto.idsub_programa
												and proyecto.idproyecto = actividad.idproyecto
												and actividad.idactividad = categoria_programatica.idActividad
												and unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora
											order by codigo"
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
											proyecto.denominacion as denoproyecto,
											proyecto.codigo as codigoproyecto,
											actividad.denominacion as denoactividad,
											actividad.codigo as codigoactividad,
											unidad_ejecutora.denominacion as denounidadejecutora,
											unidad_ejecutora.codigo as codigounidadejecutora,
											unidad_ejecutora.responsable as responsableunidadejecutora,
											categoria_programatica.codigo,
											categoria_programatica.idcategoria_programatica,
											categoria_programatica.anio
											from sector,programa,sub_programa,proyecto,actividad,unidad_ejecutora,categoria_programatica
												where categoria_programatica.status='a'
												and sector.idsector = programa.idsector
												and programa.idprograma = sub_programa.idprograma
												and sub_programa.idsub_programa = proyecto.idsub_programa
												and proyecto.idproyecto = actividad.idproyecto
												and actividad.idactividad = categoria_programatica.idActividad
												and unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora";
											
	
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			if ($campo_busqueda=="c"){
				$registros_grilla=mysql_query($sql." and categoria_programatica.codigo like '$texto_buscar%' order by codigo",$conexion_db);
			}
			if ($campo_busqueda=="d"){
				$registros_grilla=mysql_query($sql." and unidad_ejecutora.denominacion like '$texto_buscar%' order by codigo",$conexion_db);
			}
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}

if ($_GET["accion"] == 138 || $_GET["accion"] == 139 and isset($_GET["cp"])){   // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
	$sql=mysql_query("select 				sector.denominacion as denosector, 
											sector.codigo as codigosector,
											programa.denominacion as denoprograma, 
											programa.codigo as codigoprograma,
											sub_programa.denominacion as denosubprograma,
											sub_programa.codigo as codigosubprograma,
											proyecto.denominacion as denoproyecto,
											proyecto.codigo as codigoproyecto,
											actividad.denominacion as denoactividad,
											actividad.codigo as codigoactividad,
											unidad_ejecutora.denominacion as denounidadejecutora,
											unidad_ejecutora.codigo as codigounidadejecutora,
											unidad_ejecutora.responsable as responsableunidadejecutora,
											categoria_programatica.codigo,
											categoria_programatica.idcategoria_programatica,
											categoria_programatica.anio,
											categoria_programatica.transferencia
											from sector,programa,sub_programa,proyecto,actividad,unidad_ejecutora,categoria_programatica
												where categoria_programatica.status='a'
												and categoria_programatica.idcategoria_programatica = $idcategoriaprogramatica
												and sector.idsector = programa.idsector
												and programa.idprograma = sub_programa.idprograma
												and sub_programa.idsub_programa = proyecto.idsub_programa
												and proyecto.idproyecto = actividad.idproyecto
												and actividad.idactividad = categoria_programatica.idActividad
												and unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora
											order by codigo"
													,$conexion_db);
	$regcategoria_programatica=mysql_fetch_assoc($sql);

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<!-- <META HTTP-EQUIV="Refresh" CONTENT="600; URL=lib/cerrar.php"> -->
    <script src="modulos/presupuesto/js/usuarios_categorias_ajax.js" type="text/javascript" language="javascript"></script>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		if (document.frmcategoria_programatica.anio.value.length==0){
			alert("Debe escribir un a&ntilde;o para la Categoria Programatica")
			document.frmcategoria_programatica.anio.focus()
			return false;
		}
		if (document.frmcategoria_programatica.sectores.value.length==0){
			alert("Debe seleccionar un Sector para la Categoria Programatica")
			document.frmcategoria_programatica.sectores.focus()
			return false;
		}
		if (document.frmcategoria_programatica.programas.value.length==0){
			alert("Debe seleccionar un Programa para la Categoria Programatica")
			document.frmcategoria_programatica.programas.focus()
			return false;
		}	
		if (document.frmcategoria_programatica.subprogramas.value.length==0){
			alert("Debe seleccionar un Sub-Programa para la Categoria Programatica")
			document.frmcategoria_programatica.subprogramas.focus()
			return false;
		}
		if (document.frmcategoria_programatica.proyectos.value.length==0){
			alert("Debe seleccionar un Proyecto para la Categoria Programatica")
			document.frmcategoria_programatica.proyectos.focus()
			return false;
		}
		if (document.frmcategoria_programatica.actividad.value.length==0){
			alert("Debe seleccionar una Actividad para la Categoria Programatica")
			document.frmcategoria_programatica.actividad.focus()
			return false;
		}
		if (document.frmcategoria_programatica.unidad_ejecutora.value.length==0){
			alert("Debe seleccionar una Unidad Ejecutora para la Categoria Programatica")
			document.frmcategoria_programatica.sectores.focus()
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
	<h4 align=center>Categor&iacute;as Program&aacute;ticas</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<?php
	//echo "pa".$_POST["actividad"];
	//echo "m".$modo;
	//echo "a".$entro_actividad;
	//echo "p".$entro_proyecto;
	/*echo $regactividad["denoactividad"];
	echo $regactividad["codigoproyecto"];
	echo $regactividad["denoproyecto"];
	*/?>
	<form name="frmcategoria_programatica" action="principal.php?modulo=2&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data"  onSubmit="return valida_envia()">	
	<input type="hidden" name="modoactual" id="modoactual" <?php echo 'value="'.$modo.'"';?>>
    <input type="hidden" name="idcategoriaprogramatica" id="idcategoriaprogramatica" <?php echo 'value="'.$idcategoriaprogramatica.'"';?>>
		<table align=center cellpadding=2 cellspacing=0 width="60%">
			<tr>
				<td align='right' class='viewPropTitle'>C&oacute;digo:</td>
				<td class=''>
				<input type="label" id="codigo" name="codigo" maxlength="40" size="40" <?php //if (isset($_POST["anio"])){ echo 'value="'.$_POST["anio"];} 
																			if (isset($_POST["sectores"])){ echo 'value="'.$codigosector; }
																			if (isset($_POST["programas"])){ echo $codigoprograma; }
																			if (isset($_POST["subprogramas"])){ echo $codigosubprograma; }
																			if (isset($_POST["proyectos"])){ echo $codigoproyecto; }
																			if (isset($_POST["actividad"])){ echo $codigoactividad; }
																			echo '"';
																			if ($_GET["accion"] == 138 || $_GET["accion"] == 139) {echo 'value="'.$regcategoria_programatica["codigo"].'"';}?>>
                                                                            
                                                                            
                                         &nbsp;<a href="principal.php?modulo=2&accion=41"><img src="imagenes/nuevo.png" border="0" title="Nuevo Categoria Programatica"></a>
                                         
                                         
          <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
          <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='block';">X</a></div>
          <table id="tableImprimir">
          	<tr>
            	<td>Ordenar Por: </td>
                <td>
                	<select name="ordenarPor" id="ordenarPor">
                    	<option value="categoria_programatica.codigo">C&oacute;digo</option>
                        <option value="unidad_ejecutora.denominacion">Denominaci&oacute;n</option>
                        <option value="unidad_ejecutora.responsable">Responsable</option>
                    </select>               
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/presupuesto/reportes.php?nombre=catprog&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
                </td>
            </tr>
          </table>
          <iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>          
          </div>
                                         
                                         
                                         				</td>
			</tr>
			<tr>
			<td align='right' class='viewPropTitle'>Sector:</td>
			<td class='viewProp'>
				<select name="sectores" onChange="this.form.submit()" style="width:60%">
					<option>&nbsp;</option>
					<?php
						while($rowsector = mysql_fetch_array($sectores)) 
							{ 
								?>
									<option <?php echo "value=".$rowsector["idSector"]; 
													if ($entro_sector and $rowsector["idSector"]==$sector_seleccionado)
														{echo " selected";} 
													if ($_GET["accion"] == 138 || $_GET["accion"] == 139) 
														{if ($rowsector["codigo"]==$codigosector)
															{echo " selected";}
														}?>><?php echo $rowsector["codigo"]." ".$rowsector["denominacion"];?></option>
					<?php
							}
					?>
				</select>			</td>
			</tr>
			
			<tr>
			<td align='right' class='viewPropTitle'>Programa:</td>
			<td class='viewProp'>
				<select name="programas" onChange="this.form.submit()" style="width:60%">
					<option>&nbsp;</option>
					<?php
						while($rowpro = mysql_fetch_array($sql_programas)) 
							{ 
								?>
									<option <?php echo 'value="'.$rowpro["idprograma"].'"'; 
													if ($entro_programa and $rowpro["idprograma"]==$programa_seleccionado)
														{echo '" selected>'; echo $rowpro["codigo"].' '.$rowpro["denominacion"];} 
													else{if ($_GET["accion"] != 138 and $_GET["accion"] != 139) {?>><? echo $rowpro["codigo"].' '.$rowpro["denominacion"];}}
													if ($_GET["accion"] == 138 || $_GET["accion"] == 139) 
														{echo '" selected>'; echo $regcategoria_programatica["codigoprograma"].' '.$regcategoria_programatica["denoprograma"];}?>									</option>
					<?php
							}
					?>
				</select>			</td>
			</tr>
			
			<tr>
			<td align='right' class='viewPropTitle'>Sub-Programa:</td>
			<td class='viewProp'>
				<select name="subprogramas" onChange="this.form.submit()" style="width:60%">
					<option>&nbsp;</option>
					<?php
					if ($_GET["accion"] == 138 || $_GET["accion"] == 139) 
							{echo "<option value=".$regcategoria_programatica["codigosubprograma"]." selected>".$regcategoria_programatica["codigosubprograma"]." ".$regcategoria_programatica["denosubprograma"]."</option>";}
						while($rowsubpro = mysql_fetch_array($sql_sub_programas)) 
							{ 
								?>
									<option <?php echo "value=".$rowsubpro["idsub_programa"]; 
													if ($entro_subprograma and $rowsubpro["idsub_programa"]==$subprograma_seleccionado)
														{echo " selected>"; echo $rowsubpro["codigo"]." ".$rowsubpro["denominacion"];} 
													else{if ($_GET["accion"] != 138 and $_GET["accion"] != 139) {?>> <? echo $rowsubpro["codigo"]." ".$rowsubpro["denominacion"];}}
													if ($_GET["accion"] == 138 || $_GET["accion"] == 139) 
														{echo " selected>"; echo $regcategoria_programatica["codigosubprograma"]." ".$regcategoria_programatica["denosubprograma"];}?>									</option>
					<?php
							}
					?>
				</select>			</td>
			</tr>
			
			<tr>
			<td align='right' class='viewPropTitle'>Proyecto:</td>
			<td class='viewProp'>
				<select name="proyectos" onChange="this.form.submit()" style="width:60%">
					<option>&nbsp;</option>
					<?php
					if ($_GET["accion"] == 138 || $_GET["accion"] == 139) 
							{echo '<option value="'.$regcategoria_programatica["codigoproyecto"].'" selected>'.$regcategoria_programatica["codigoproyecto"].' '.$regcategoria_programatica["denoproyecto"]."</option>";}
						while($rowproyecto = mysql_fetch_array($sql_proyectos)) 
							{ 
								?>
									<option <?php echo 'value="'.$rowproyecto["idproyecto"].'"'; 
													if ($entro_proyecto and $rowproyecto["idproyecto"]==$proyecto_seleccionado)
														{echo ' selected>'; echo $rowproyecto["codigo"].' '.$rowproyecto["denominacion"];} 
													else{if ($_GET["accion"] != 138 and $_GET["accion"] != 139) {?>><? echo $rowproyecto["codigo"]." ".$rowproyecto["denominacion"];}}
													if ($_GET["accion"] == 138 || $_GET["accion"] == 139) 
														{echo '" selected>'; echo $regcategoria_programatica["codigoproyecto"]." ".$regcategoria_programatica["denoproyecto"];}?>									</option>
					<?php
							}
					?>
				</select>			</td>
			</tr>
			
			<tr>
			<td align='right' class='viewPropTitle'>Actividad:</td>
			<td class='viewProp'>
				<select name="actividad" onChange="this.form.submit()" style="width:60%">
					<option>&nbsp;</option>
					<?php
						if ($_GET["accion"] == 138 || $_GET["accion"] == 139) 
							{echo '<option value="'.$regcategoria_programatica["idActividad"].'" selected>'.$regcategoria_programatica["codigoactividad"]." ".$regcategoria_programatica["denoactividad"]."</option>";}
							
						while($rowactividad = mysql_fetch_array($sql_actividad)) 
							{
								?>
									<option <?php echo 'value="'.$rowactividad["idActividad"].'"'; 
													if ($entro_actividad and $rowactividad["idActividad"]==$actividad_seleccionado)
														{echo ' selected>'; echo $rowactividad["codigo"].' '.$rowactividad["denominacion"];} 
													else{if ($_GET["accion"] != 138 and $_GET["accion"] != 139) {?>><? echo $rowactividad["codigo"]." ".$rowactividad["denominacion"];}}
													if ($_GET["accion"] == 138 || $_GET["accion"] == 139) 
														{echo '" selected>'; echo $regcategoria_programatica["codigoactividad"]." ".$regcategoria_programatica["denoactividad"];}?>									</option>
					<?php
							}
					?>
				</select>			</td>
			</tr>
			
			<tr>
			<td align='right' class='viewPropTitle'>Unidad Ejecutora:</td>
			<td class='viewProp'>
				<select name="unidad_ejecutora" onChange="this.form.submit()" style="width:60%">
					<option>&nbsp;</option>
					<?php
						if ($_GET["accion"] == 138 || $_GET["accion"] == 139) 
							{echo '<option value="'.$regcategoria_programatica["idunidad_ejecutora"].'" selected>'.$regcategoria_programatica["codigounidadejecutora"]." ".$regcategoria_programatica["denounidadejecutora"]."</option>";}
							
						while($rowunidadejecutora = mysql_fetch_array($sql_unidad_ejecutora)) 
							{
								?>
									<option <?php echo 'value="'.$rowunidadejecutora["idunidad_ejecutora"].'"'; 
													if ($entro_unidadejecutora and $rowunidadejecutora["idunidad_ejecutora"]==$unidadejecutora_seleccionado)
														{echo ' selected>'; echo $rowunidadejecutora["codigo"].' '.$rowunidadejecutora["denominacion"];} 
													else{if ($_GET["accion"] != 138 and $_GET["accion"] != 139) {?>><? echo $rowunidadejecutora["codigo"]." ".$rowunidadejecutora["denominacion"];}}
													if ($_GET["accion"] == 138 || $_GET["accion"] == 139) 
														{echo '" selected>'; echo $regcategoria_programatica["codigounidadejecutora"]." ".$regcategoria_programatica["denounidadejecutora"];}?>									</option>
									
					<?php
							}
					?>
				</select>			</td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Responsable:</td>
				<td class=''><input type="text" name="responsable" maxlength="255" size="65" id="responsable" <?php if (isset($_POST["unidad_ejecutora"])){ echo 'value="'.$regunidadejecutora["responsable"].'"';} echo 'value="'.$regcategoria_programatica['responsableunidadejecutora'].'"'; echo "disabled";?>></td>
			</tr>
			<tr>
			  <td align='right' class='viewPropTitle'><p>Rectificaci&oacute;n:</p>
		      </td>
			  <td class=''><label>
			    <input type="checkbox" name="transferencia" id="transferencia" <?php if ($regcategoria_programatica['transferencia']=="1"){ echo " checked";}?>>
			  </label></td>
		  </tr>
		</table>
  <table align=center cellpadding=2 cellspacing=0>
			<tr><td>

				<?php

					if($_GET["accion"] != 138 and $_GET["accion"] != 139 and in_array(137, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 138 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 139 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
			?>

				<input type="reset" value="Limpiar" class="button">
			</td></tr>
		</table>
	</form>
	<br>
    <? if($_GET["accion"] == 139 and in_array($_GET["accion"], $privilegios) == true){ ?>
		<div id="usuarios_autorizados" style="display:block">
    <? }else{ ?>
    	<div id="usuarios_autorizados" style="display:none">
    <? } ?>

    <table align="center" width="50%">
    	<tr>
    		<td align="center" class="viewPropTitle" width="45%"><strong>Usuarios</strong></td>
            <td align="center" class="viewPropTitle" width="5%">&nbsp;</td>
            <td align="center" class="viewPropTitle" width="45%"><strong>Usuarios Asignados</strong></td>
    	</tr>
        <tr>
        	<td align="center">
            	<? 	$sql_usuarios = mysql_query("select usuarios.cedula, usuarios.apellidos, usuarios.nombres from usuarios
														where usuarios.status = 'a'
															and usuarios.cedula Not in (select cedula from usuarios_categoria where idcategoria_programatica = '$idcategoriaprogramatica')
															and usuarios.cedula In (select id_usuario from privilegios_modulo where id_modulo = '2')
																				 order by apellidos, nombres")or die(mysql_error()); 
				?>
					<select name="usuarios_activos" id="usuarios_activos" multiple size="6">
                    	<? while ($bus_usuarios = mysql_fetch_array($sql_usuarios)){ ?>
	                    	<option value="<?=$bus_usuarios["cedula"];?>"><? echo $bus_usuarios["apellidos"]." ".$bus_usuarios["nombres"];?></option>
                    	<? } ?>
                    </select>
            </td>
            <td align="center" >
            	<img style="display:block; cursor:pointer"
                                        src="imagenes/fast_forward.png" 
                                        title="Asignar Usuario a Categoria" 
                                        id="botonPasarUsuario" 
                                        name="botonPasarUsuario" 
                                        onclick="pasarUsuario()"/>
                  <br>
                  <img style="display:block; cursor:pointer"
                                        src="imagenes/rewind.png" 
                                        title="Quitar Usuario de Categoria" 
                                        id="botonRegresarUsuario" 
                                        name="botonRegresarUsuario"
                                        onclick="regresarUsuario()"/>
            </td>
            <td align="center">
	            <? 	$sql_usuarios_asignados = mysql_query("select * from usuarios_categoria, usuarios, privilegios_modulo 
																				where usuarios_categoria.cedula = usuarios.cedula
																				and usuarios_categoria.idcategoria_programatica = '".$idcategoriaprogramatica."'
																				and privilegios_modulo.id_usuario = usuarios.cedula
																				and privilegios_modulo.id_modulo = '2'
																				 order by apellidos, nombres"); ?>
            	<select name="usuarios_asignados" id="usuarios_asignados" multiple size="6">
                	<? while ($bus_usuarios_asignados = mysql_fetch_array($sql_usuarios_asignados)){ ?>
	                    	<option value="<?=$bus_usuarios_asignados["cedula"];?>"><? echo $bus_usuarios_asignados["apellidos"]." ".$bus_usuarios_asignados["nombres"];?></option>
                    	<? } ?>
                </select>
            </td>
        </tr>
    </table>
    
    
    
</div>


<br>
<br>
	<h2 align="center">Categor&iacute;as Program&aacute;ticas</h2>
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
			<table class="Main" cellpadding="0" cellspacing="0" width="95%">
				<tr>
					<td>
						<form name="grilla" action="" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="95%">
							<thead>
								<tr>
									<td align="center" class="Browse">C&oacute;digo</td>
									<td align="center" class="Browse">Denominaci&oacute;n</td>
									<td align="center" class="Browse">Unidad Ejecutora</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de programas 
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
									{ ?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
									echo "<td align='center' class='Browse'>".$llenar_grilla["codigo"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["denosubprograma"]." / ".$llenar_grilla["denoactividad"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["denounidadejecutora"]."</td>";
									$cp=$llenar_grilla["idcategoria_programatica"];
									$u=$llenar_grilla["codigounidadejecutora"];
									$a=$llenar_grilla["codigoactividad"];
									$y=$llenar_grilla["codigoproyecto"];
									$c=$llenar_grilla["codigosubprograma"];
									$p=$llenar_grilla["codigoprograma"];
									$s=$llenar_grilla["codigosector"];
									//echo "<td align='center' class='Browse' width='3%'><a href='categoria_programatica.php?modo=1&c=$c&p=$p&s=$s&y=$y&a=$a&u=$u&cp=$cp' class='Browse'><img src='../../css/theme/green/pics/edit.png' border='0' alt='Modificar' title='Modificar'></a></td>";
									if(in_array(139, $privilegios) == true){
										echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=139&c=$c&p=$p&s=$s&y=$y&a=$a&u=$u&cp=$cp' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
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
<script> document.frmcategoria_programatica.sectores.focus() </script>
</body>
</html>

<?php
if($_POST){
	
	$pos_sectores=$_POST["sectores"];
	$pos_programas=$_POST["programas"];
	$pos_subprogramas=$_POST["subprogramas"];
	$pos_proyectos=$_POST["proyectos"];
	$pos_actividad=$_POST["actividad"];
	$pos_unidadejecutora=$_POST["unidad_ejecutora"];
	$codigo=$_POST["codigo"];
	if ($_POST["transferencia"])
		$transferencia=1;
	else
		$transferencia=0;
	
	$busca_existe_registro=mysql_query("select * from categoria_programatica where codigo like '".$_POST['codigo']."'  and status='a'",$conexion_db);
if($_GET["accion"] == 137 and in_array(137, $privilegios) == true){
	if (mysql_num_rows($busca_existe_registro)>0){
		?>
		<script>
			mostrarMensajes("error", "Disculpe El registro que intenta guardar ya existe, vuelva a intentarlo");
			setTimeout("window.location.href='principal.php?modulo=2&accion=41'",5000);
			</script>
		<?
	}else{
		mysql_query("insert into categoria_programatica
									(codigo,idsector,idprograma,idsub_programa,idproyecto,idActividad,idunidad_ejecutora,transferencia,usuario,fechayhora,status) 
							values ('$codigo','$pos_sectores','$pos_programas','$pos_subprogramas','$pos_proyectos','$pos_actividad','$pos_unidadejecutora','$transferencia','$login','$fh','a')"
									,$conexion_db);
			registra_transaccion('Ingresar Categoria Programatica ('.$codigo.')',$login,$fh,$pc,'categoria_programatica',$conexion_db);
			?>
		<script>
			mostrarMensajes("exito", "El registro se Ingreso con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=41'",5000);
			</script>
		<?
		}
	}
	/*if (isset($_POST["accionm"])){
			mysql_query("update actividad set 
										denominacion='".$denominacion."',
										fechayhora='".$fh."',
										usuario='".$login."'
										where 	codigo = '$codigo' and idproyecto='".$pos_proyectos."' and status = 'a'",$conexion_db);
			registra_transaccion('m',$login,$fh,$pc,'actividad',$conexion_db);
			header("location:categoria_programatica.php?modo=0&busca=0"); 
	}*/
	if ($_GET["accion"] == 139 and in_array(139, $privilegios) == true and !$_POST["buscar"]){
			$sql = mysql_query("select * from categoria_programatica where codigo = '$codigo' and status = 'a'");
			$bus = mysql_fetch_array($sql);
			
			
			$sql_consultar = mysql_query("select * from orden_compra_servicio where idcategoria_programatica = '".$bus["idcategoria_programatica"]."'");
			$num_consultar = mysql_fetch_array($sql_consultar);
			
			$sql_consultar_op = mysql_query("select * from orden_pago where idcategoria_programatica = '".$bus["idcategoria_programatica"]."'");
			$num_consultar_op = mysql_fetch_array($sql_consultar_op);
			
			
			$sql_eliminar = mysql_query("delete from categoria_programatica where codigo = '$codigo' and status = 'a'");
			
			if($sql_eliminar){
				if($num_consultar == 0 and $num_consultar_op == 0){
					registra_transaccion('Eliminar Categroia Programatica ('.$bus["codigo"].')',$login,$fh,$pc,'categoria_programatica',$conexion_db);
					
					?>
		<script>
			mostrarMensajes("exito", "El registro se Elimino con Exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=41'",5000);
			</script>
		<?
				}else{
					registra_transaccion('Eliminar Categroia Programatica (ERROR) ('.$bus["codigo"].')',$login,$fh,$pc,'categoria_programatica',$conexion_db);
					
					?>
		<script> 
			mostrarMensajes("error", "Disculpe el regsitro no se puede eliminar, lo mas seguro es que este registro este utilizandose desde otra tabla");
			setTimeout("window.location.href='principal.php?modulo=2&accion=41'",5000);
			</script>
		<?
				}
			}
			
	}
}
?>
