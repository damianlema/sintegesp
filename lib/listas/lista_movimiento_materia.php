<?php
/**
*
*	 "lista_materia.php" 
*	Version: 1.0.1
*	Fecha Ultima Modificacion: 10/04/2012
*	Autor: Hector Lema
*
*/
ob_start();
session_start();
include_once("../../conf/conex.php");
$conexion_db=conectarse();
$existen_registros=0;
$buscar_registros=$_GET["busca"];
$formulario=$_REQUEST["frm"];

$registros_grilla = mysql_query("select inventario_materia.descripcion as descripcion,
										inventario_materia.codigo as codigo,
										inventario_materia.idinventario_materia,
										inventario_materia.existencia_actual,
										inventario_materia.inventario_inicial,
										inventario_materia.serializado,
										inventario_materia.idunidad_medida,
										inventario_materia.status,
										unidad_medida.idunidad_medida as idunidad,
										inventario_materia.caduca,
										unidad_medida.abreviado,
										unidad_medida.descripcion as descripcion_unidad
										from inventario_materia, unidad_medida 
										where 
											unidad_medida.idunidad_medida = inventario_materia.idunidad_medida 
											and inventario_materia.codigo = 'x' order by inventario_materia.codigo ") or die (mysql_error());
	
if (isset($_POST["buscar"])){
	
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select 	inventario_materia.descripcion as descripcion,
					inventario_materia.codigo as codigo,
					inventario_materia.idinventario_materia,
					inventario_materia.existencia_actual,
					inventario_materia.inventario_inicial,
					inventario_materia.serializado,
					inventario_materia.caduca,
					inventario_materia.idunidad_medida,
					inventario_materia.status,
					unidad_medida.idunidad_medida as idunidad,
					unidad_medida.abreviado,
					unidad_medida.descripcion as descripcion_unidad
					from inventario_materia, unidad_medida where 
						unidad_medida.idunidad_medida = inventario_materia.idunidad_medida
						and 
						(inventario_materia.codigo like '%$texto_buscar%' OR
						 inventario_materia.descripcion like '%$texto_buscar%') order by inventario_materia.codigo";
	$registros_grilla = mysql_query($sql) or die (mysql_error());
	
	if (mysql_num_rows($registros_grilla)<=0)
	{
		$existen_registros=1;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>

</head>
	<SCRIPT language=JavaScript>

document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
var miPopUp
var w=0

function obtenID(){
	id = opener.document.getElementById('id_materia').value;
	
	return id;
}
// end hiding from old browsers -->
</SCRIPT>
	<body>
	<br>
	<h4 align=center>Listado de Materias</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	
	<form name="buscar" action="lista_materia.php" method="POST">
    <input type="hidden" name="frm" id="frm" value="<?=$_REQUEST["frm"]?>">
    
    
	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Buscar:</td>
			<td class=''><input type="text" name="textoabuscar" maxlength="30" size="30"></td>
			<td>
				<input align=center name="buscar" type="submit" value="Buscar">
				</a>
			</td>
		</tr>
	</table>
	</form>
	
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="90%">
				<tr>
					<td>
						<form name="grilla" action="" method="POST">
						<table class="Browse" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<!--<td class="Browse">&nbsp;</td>-->
                                    <td align="center" class="Browse" width="20%">C&oacute;digo</td>
									<td align="left" class="Browse" width="70%">Descripci&oacute;n</td>
									<td align="center" class="Browse" width="10%">Selecci&oacute;n</td>
								</tr>
							</thead>
							
							<?php
							
							if($_REQUEST["frm"] == "datos_basicos"){
							
							if ($existen_registros==0){
								$i=1;
								while($llenar_grilla= mysql_fetch_array($registros_grilla)){
								 $codigo_materia=$llenar_grilla["codigo"];
								 $idinventario_materia = $llenar_grilla["idinventario_materia"];
								 if ($llenar_grilla["status"] == 'a'){
 								 ?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.consultarMateria('<?=$idinventario_materia?>'), window.close()">
                                    <?php
									}else{
									?>
                                    <tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.consultarMateria('<?=$idinventario_materia?>'), window.close()">
									<?php
									}
									echo "<td align='center' class='Browse' width='20%'>".$llenar_grilla["codigo"]."</td>";
									echo "<td align='left' class='Browse' width='70%'>".$llenar_grilla["descripcion"]."</td>";
									?>
									<td align='center' class='Browse' width='7%'><a href='#' onClick="opener.consultarMateria('<?=$idinventario_materia?>'), window.close()"><img src='../../imagenes/validar.png'></a></td>
                                    <?
								echo "</tr>";
								$i++;
								}
							}
							}
							?>
                            <?php
							
							if($_REQUEST["frm"] == "reemplazos"){
							
							if ($existen_registros==0){
								$i=1;
								while($llenar_grilla= mysql_fetch_array($registros_grilla)){
								 $codigo_materia=$llenar_grilla["codigo"];
								 $descripcion_materia=$llenar_grilla["descripcion"];
								 $idinventario_materia = $llenar_grilla["idinventario_materia"];
								 if ($llenar_grilla["status"] == 'a'){
 								 ?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.document.getElementById('descripcion_reemplazo').value = '<?=$codigo_materia?> <?=$descripcion_materia?>', opener.document.getElementById('idmateria_reemplazo').value = '<?=$idinventario_materia?>',  window.close()">
                                    <?php
									}else{
									?>
                                    <tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.document.getElementById('descripcion_reemplazo').value = '<?=$codigo_materia?> <?=$descripcion_materia?>', opener.document.getElementById('idmateria_reemplazo').value = '<?=$idinventario_materia?>',  window.close()">
									<?php
									}
									echo "<td align='center' class='Browse' width='20%'>".$llenar_grilla["codigo"]."</td>";
									echo "<td align='left' class='Browse' width='70%'>".$llenar_grilla["descripcion"]."</td>";
									?>
									<td align='center' class='Browse' width='7%'><a href='#' onClick="opener.document.getElementById('descripcion_reemplazo').value = '<?=$codigo_materia?> <?=$descripcion_materia?>', opener.document.getElementById('idmateria_reemplazo').value = '<?=$idinventario_materia?>',  window.close()"><img src='../../imagenes/validar.png'></a></td>
                                    <?
								echo "</tr>";
								$i++;
								}
							}
							}
							?>
                            <?php
							
							if($_REQUEST["frm"] == "equivalencias"){
							
							if ($existen_registros==0){
								$i=1;
								while($llenar_grilla= mysql_fetch_array($registros_grilla)){
								 $codigo_materia=$llenar_grilla["codigo"];
								 $descripcion_materia=$llenar_grilla["descripcion"];
								 $idinventario_materia = $llenar_grilla["idinventario_materia"];
								 if ($llenar_grilla["status"] == 'a'){
 								 ?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.document.getElementById('descripcion_equivalencia').value = '<?=$codigo_materia?> <?=$descripcion_materia?>', opener.document.getElementById('idmateria_equivalente').value = '<?=$idinventario_materia?>',opener.document.getElementById('describir_equivalencia').focus() ,  window.close()">
                                    <?php
									}else{
									?>
                                    <tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.document.getElementById('descripcion_equivalencia').value = '<?=$codigo_materia?> <?=$descripcion_materia?>', opener.document.getElementById('idmateria_equivalente').value = '<?=$idinventario_materia?>', opener.document.getElementById('describir_equivalencia').focus() , window.close()">
									<?php
									}
									echo "<td align='center' class='Browse' width='20%'>".$llenar_grilla["codigo"]."</td>";
									echo "<td align='left' class='Browse' width='70%'>".$llenar_grilla["descripcion"]."</td>";
									?>
									<td align='center' class='Browse' width='7%'><a href='#' onClick="opener.document.getElementById('descripcion_equivalencia').value = '<?=$codigo_materia?> <?=$descripcion_materia?>', opener.document.getElementById('idmateria_equivalente').value = '<?=$idinventario_materia?>', opener.document.getElementById('describir_equivalencia').focus() , window.close()"><img src='../../imagenes/validar.png'></a></td>
                                    <?
								echo "</tr>";
								$i++;
								}
							}
							}
							?>
                            <?php
							
							if($_REQUEST["frm"] == "accesorios"){
							
							if ($existen_registros==0){
								$i=1;
								while($llenar_grilla= mysql_fetch_array($registros_grilla)){
								 $codigo_materia=$llenar_grilla["codigo"];
								 $descripcion_materia=$llenar_grilla["descripcion"];
								 $idinventario_materia = $llenar_grilla["idinventario_materia"];
								 if ($llenar_grilla["status"] == 'a'){
 								 ?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.document.getElementById('descripcion_accesorio').value = '<?=$codigo_materia?> <?=$descripcion_materia?>', opener.document.getElementById('idmateria_accesorios').value = '<?=$idinventario_materia?>',opener.document.getElementById('describir_accesorio').focus() ,  window.close()">
                                    <?php
									}else{
									?>
                                    <tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.document.getElementById('descripcion_accesorio').value = '<?=$codigo_materia?> <?=$descripcion_materia?>', opener.document.getElementById('idmateria_accesorios').value = '<?=$idinventario_materia?>', opener.document.getElementById('describir_accesorio').focus() , window.close()">
									<?php
									}
									echo "<td align='center' class='Browse' width='20%'>".$llenar_grilla["codigo"]."</td>";
									echo "<td align='left' class='Browse' width='70%'>".$llenar_grilla["descripcion"]."</td>";
									?>
									<td align='center' class='Browse' width='7%'><a href='#' onClick="opener.document.getElementById('descripcion_accesorio').value = '<?=$codigo_materia?> <?=$descripcion_materia?>', opener.document.getElementById('idmateria_accesorios').value = '<?=$idinventario_materia?>',  opener.document.getElementById('describir_accesorio').focus() , window.close()"><img src='../../imagenes/validar.png'></a></td>
                                    <?
								echo "</tr>";
								$i++;
								}
							}
							}
							?>
							<?php
							
							if($_REQUEST["frm"] == "movimiento_materia_ajuste"){
							
							if ($existen_registros==0){
								$i=1;
								while($llenar_grilla= mysql_fetch_array($registros_grilla)){
								 $codigo_materia=$llenar_grilla["codigo"];
								 $descripcion_materia = $llenar_grilla["descripcion"];
								 $idinventario_materia = $llenar_grilla["idinventario_materia"];
								 $existencia_actual = number_format($llenar_grilla["existencia_actual"],2,",",".");
								 if ($llenar_grilla["status"] == 'a'){
 								 ?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.document.getElementById('codigo_materia').value = '<?=$codigo_materia?>', opener.document.getElementById('descripcion_materia').value = '<?=$descripcion_materia?>', opener.document.getElementById('idinventario_materia').value = '<?=$idinventario_materia?>', opener.document.getElementById('cantidad_actual_sin_formato').value = '<?=$llenar_grilla["inventario_inicial"]?>', opener.document.getElementById('cantidad_actual').value = '<?=$existencia_actual?>', opener.document.getElementById('serializado').value = '<?=$llenar_grilla["serializado"]?>', opener.document.getElementById('caduca').value = '<?=$llenar_grilla["caduca"]?>', opener.document.getElementById('unidad_materia').value = '<?=$llenar_grilla["abreviado"]."-".$llenar_grilla["descripcion_unidad"]?>', opener.document.getElementById('cantidad_ajuste').focus() ,  window.close()">
                                    <?php
									}else{
									?>
                                    <tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.document.getElementById('codigo_materia').value = '<?=$codigo_materia?>', opener.document.getElementById('descripcion_materia').value = '<?=$descripcion_materia?>', opener.document.getElementById('idinventario_materia').value = '<?=$idinventario_materia?>', opener.document.getElementById('cantidad_actual_sin_formato').value = '<?=$llenar_grilla["inventario_inicial"]?>', opener.document.getElementById('cantidad_actual').value = '<?=$existencia_actual?>', opener.document.getElementById('serializado').value = '<?=$llenar_grilla["serializado"]?>', opener.document.getElementById('caduca').value = '<?=$llenar_grilla["caduca"]?>', opener.document.getElementById('unidad_materia').value = '<?=$llenar_grilla["abreviado"]."-".$llenar_grilla["descripcion_unidad"]?>', opener.document.getElementById('cantidad_ajuste').focus() ,  window.close()">
									<?php
									}
									echo "<td align='center' class='Browse' width='20%'>".$llenar_grilla["codigo"]."</td>";
									echo "<td align='left' class='Browse' width='70%'>".$llenar_grilla["descripcion"]."</td>";
									?>
									<td align='center' class='Browse' width='7%'><a href='#' onClick="opener.document.getElementById('codigo_materia').value = '<?=$codigo_materia?>', opener.document.getElementById('descripcion_materia').value = '<?=$descripcion_materia?>', opener.document.getElementById('idinventario_materia').value = '<?=$idinventario_materia?>', opener.document.getElementById('cantidad_actual_sin_formato').value = '<?=$llenar_grilla["inventario_inicial"]?>', opener.document.getElementById('cantidad_actual').value = '<?=$existencia_actual?>', opener.document.getElementById('serializado').value = '<?=$llenar_grilla["serializado"]?>', opener.document.getElementById('caduca').value = '<?=$llenar_grilla["caduca"]?>', opener.document.getElementById('unidad_materia').value = '<?=$llenar_grilla["abreviado"]."-".$llenar_grilla["descripcion_unidad"]?>', opener.document.getElementById('cantidad_ajuste').focus() ,  window.close()"><img src='../../imagenes/validar.png'></a></td>
                                    <?
								echo "</tr>";
								$i++;
								}
							}
							}
							?>
							
						</table>
						</form>
					</td>
				</tr>
			</table>
		</div>
	
</body>
</html>