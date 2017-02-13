<?php
/**
*
*	 "lista_cargos.php" Listado de Trabajadores para seleccionarlo
*	Version: 1.0.1
*	Fecha Ultima Modificacion: 28/10/2008
*	Autor: Hector Lema
*
*/
ob_start();
session_start();
include_once("../../conf/conex.php");

$conection_db=conectarse();
$existen_registros=0;
$buscar_registros=$_GET["busca"];
$m=$_GET["m"];

if($_REQUEST["destino"] == "reposicion"){
	$registros_grilla=mysql_query("select 		sector.denominacion as denosector, 
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
											categoria_programatica.idcategoria_programatica
											from 
											sector,
											programa,
											sub_programa,
											proyecto,
											actividad,
											unidad_ejecutora,
											categoria_programatica,
											orden_compra_servicio,
											tipos_documentos
												where categoria_programatica.status='a'
												and orden_compra_servicio.idcategoria_programatica = categoria_programatica.idcategoria_programatica
												and orden_compra_servicio.estado = 'procesado'
												and orden_compra_servicio.idtipo_caja_chica = '".$_REQUEST["tcc"]."'
												and tipos_documentos.idtipos_documentos = orden_compra_servicio.tipo
												and tipos_documentos.compromete = 'no'
												and tipos_documentos.causa = 'no'
												and tipos_documentos.paga = 'no'
												and sector.idsector = programa.idsector
												and programa.idprograma = sub_programa.idprograma
												and sub_programa.idsub_programa = proyecto.idsub_programa
												and proyecto.idproyecto = actividad.idproyecto
												and actividad.idactividad = categoria_programatica.idActividad
												and unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora
												group by orden_compra_servicio.idcategoria_programatica
											order by codigo")or die(mysql_error());
	
}else{
	$registros_grilla=mysql_query("select 		sector.denominacion as denosector, 
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
											categoria_programatica.idcategoria_programatica
											from sector,programa,sub_programa,proyecto,actividad,unidad_ejecutora,categoria_programatica
												where categoria_programatica.status='a'
												and sector.idsector = programa.idsector
												and programa.idprograma = sub_programa.idprograma
												and sub_programa.idsub_programa = proyecto.idsub_programa
												and proyecto.idproyecto = actividad.idproyecto
												and actividad.idactividad = categoria_programatica.idActividad
												and unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora
											order by codigo");
	
}




if (isset($_POST["buscar"])){

if($_REQUEST["destino"] == "reposicion"){
$texto_buscar=$_POST["textoabuscar"];
$registros_grilla=mysql_query("select 		sector.denominacion as denosector, 
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
											categoria_programatica.idcategoria_programatica
											from 
											sector,
											programa,
											sub_programa,
											proyecto,
											actividad,
											unidad_ejecutora,
											categoria_programatica,
											orden_compra_servicio,
											tipos_documentos
												where 
												categoria_programatica.status='a'
												and orden_compra_servicio.idcategoria_programatica = categoria_programatica.idcategoria_programatica
												and orden_compra_servicio.estado = 'procesado'
												and tipos_documentos.idtipos_documentos = orden_compra_servicio.tipo
												and tipos_documentos.compromete = 'no'
												and tipos_documentos.causa = 'no'
												and tipos_documentos.paga = 'no'
												and sector.idsector = programa.idsector
												and programa.idprograma = sub_programa.idprograma
												and sub_programa.idsub_programa = proyecto.idsub_programa
												and proyecto.idproyecto = actividad.idproyecto
												and actividad.idactividad = categoria_programatica.idActividad
												and unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora
												and (categoria_programatica.codigo like '$texto_buscar%'
												or unidad_ejecutora.denominacion like '$texto_buscar%')
												group by orden_compra_servicio.idcategoria_programatica
											order by codigo")or die(mysql_error());
	
}else{


$texto_buscar=$_POST["textoabuscar"];
	$campo_busqueda=$_POST["tipobusqueda"];
	$sql="select sector.denominacion as denosector, 
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
				$registros_grilla=mysql_query($sql." and categoria_programatica.codigo like '$texto_buscar%' order by codigo");
			}
			if ($campo_busqueda=="d"){
				$registros_grilla=mysql_query($sql." and unidad_ejecutora.denominacion like '$texto_buscar%' order by codigo");
			}
		}
	}
	if (mysql_num_rows($registros_grilla)<=0)
		{
			$existen_registros=1;
		}
}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>

<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
function ponCategoria(idCategoria,c,d){
	m=document.buscar.modoactual.value
	opener.document.forms[0].idcategoria_programatica.value=idCategoria
	opener.document.forms[0].codcategoria_programatica.value=c
	opener.document.forms[0].denocategoria_programatica.value=d
	opener.document.forms[0].modoactual.value=m
	opener.document.forms[0].emergente.value="true"
	window.close()
}
</SCRIPT>
</head>
	
	<body>
	<br>
	<h4 align=center>Listado de Categorias Programaticas</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	
	<?php //echo $m;?>
	
	<form name="buscar" action="lista_categorias_programaticas.php" method="POST">
	<input type="hidden" name="modoactual" id="modoactual" <?php echo 'value="'.$m.'"';?>>
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
                <input type="hidden" name="destino" id="destino" value="<?=$_REQUEST["destino"]?>">
                <input type="hidden" name="tcc" id="tcc" value="<?=$_REQUEST["tcc"]?>">
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
			<table class="Main" cellpadding="0" cellspacing="0" width="95%">
				<tr>
					<td>
						<form name="grilla" action="lista_categorias_programaticas.php" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="95%">
							<thead>
								<tr>
									<td align="center" class="Browse">C&oacute;digo</td>
									<td width="45%" align="center" class="Browse">Denominaci&oacute;n</td>
								  <td width="42%" align="center" class="Browse">Responsable</td>
								  <td width="6%" colspan="2" align="center" class="Browse">Acci&oacute;n</td>
							  </tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de programas 
							if ($existen_registros==0){
							$i=0;
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
									{ 
									$cp=$llenar_grilla["idcategoria_programatica"];
									$c=$llenar_grilla["codigo"];
									$d=$llenar_grilla["denounidadejecutora"];
									?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" 
                                        onClick="
                                        <?
                                        if($_REQUEST["destino"] == ""){
										?>
                                        ponCategoria('<?=$cp?>','<?=$c?>','<?=$d?>')
										<?
                                        }
										if($_REQUEST["destino"] == "orden_compra"){
										?>
                                        opener.document.getElementById('nombre_categoria').value='(<?=$llenar_grilla["codigo"]?>) <?=$llenar_grilla["denounidadejecutora"]?>', 
                                        opener.document.getElementById('id_categoria_programatica').value= '<?=$llenar_grilla["idcategoria_programatica"]?>', window.close()
                                        <?
                                        }
										if($_REQUEST["destino"] == "rendicion_cuentas"){
										?>
                                        opener.document.getElementById('categoria_programatica').value='(<?=$llenar_grilla["codigo"]?>) <?=$llenar_grilla["denounidadejecutora"]?>', opener.document.getElementById('id_categoria_programatica').value= '<?=$llenar_grilla["idcategoria_programatica"]?>', window.close()
										<?
                                        }
										?>
                                        ">
								<?php
									echo "<td align='center' class='Browse'>".$llenar_grilla["codigo"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["denounidadejecutora"]."</td>";
									echo "<td align='left' class='Browse'>"."&nbsp;".$llenar_grilla["responsableunidadejecutora"]."</td>";
									
									if($_REQUEST["destino"] == ""){ ?>
										<td align='center' class='Browse' width='7%'> <a href='#' onClick="ponCategoria('<?=$cp?>','<?=$c?>','<?=$d?>')"><img src='../../imagenes/validar.png'></a></td>
									<? }
									if($_REQUEST["destino"] == "orden_compra"){
										?>
										<td align='center' class='Browse' width='7%'>
                                         
                                        <a href='#' onClick="opener.document.getElementById('nombre_categoria').value='(<?=$llenar_grilla["codigo"]?>) <?=$llenar_grilla["denounidadejecutora"]?>', opener.document.getElementById('id_categoria_programatica').value= '<?=$llenar_grilla["idcategoria_programatica"]?>', window.close()"><img src='../../imagenes/validar.png'></a></td>
                                        <?
									}
									if($_REQUEST["destino"] == "reposicion"){
										?>
										<td align='center' class='Browse' width='7%'> 
                                        <a href='#' onClick="opener.document.getElementById('nombre_categoria').value='(<?=$llenar_grilla["codigo"]?>) <?=$llenar_grilla["denounidadejecutora"]?>', opener.document.getElementById('id_categoria_programatica').value= '<?=$llenar_grilla["idcategoria_programatica"]?>', window.close()"><img src='../../imagenes/validar.png'></a></td>
                                        <?
									}
									if($_REQUEST["destino"] == "rendicion_cuentas"){
										?>
										<td align='center' class='Browse' width='7%'> 
                                        <a href='#' onClick="opener.document.getElementById('categoria_programatica').value='(<?=$llenar_grilla["codigo"]?>) <?=$llenar_grilla["denounidadejecutora"]?>', opener.document.getElementById('id_categoria_programatica').value= '<?=$llenar_grilla["idcategoria_programatica"]?>', window.close()"><img src='../../imagenes/validar.png'></a></td>
                                        <?
									}
									if($_REQUEST["destino"] == "generar_nomina"){
										?>
										<td align='center' class='Browse' width='7%'>
                                         
                                        <a href='#' onClick="opener.document.getElementById('nombre_categoria').value='(<?=$llenar_grilla["codigo"]?>) <?=$llenar_grilla["denounidadejecutora"]?>', opener.document.getElementById('idcentro_costo_fijo').value= '<?=$llenar_grilla["idcategoria_programatica"]?>', window.close()"><img src='../../imagenes/validar.png'></a></td>
                                        <?
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
</body>
</html>