<?php
session_start();
include_once("../../conf/conex.php");
$conexion_db=conectarse();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
</head>
	
	<body>
	<br>
	<h4 align=center>Listado de Movimientos</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	
    
    
    <form name="buscar" action="" method="POST">
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
    
    
	<?
    if($_POST){
		//$registros_grilla = mysql_query("select * from movimientos_bienes");
		$registros_grilla = mysql_query("select * from movimientos_bienes where 
																		nro_movimiento like '%".$_POST["textoabuscar"]."%'
																		|| justificacion like '%".$_POST["textoabuscar"]."%' ");
	/*else{
		$registros_grilla = mysql_query("select * from movimientos_bienes");
	}*/
	
	
	?>
	
	
	
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="95%">
				<tr>
					<td>
						
						<table class="Browse" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<!--<td class="Browse">&nbsp;</td>-->
									<td align="center" class="Browse">Nro. Movimiento</td>
									<td width="16%" align="center" class="Browse">Fecha Movimiento</td>
                                    <td width="15%" align="center" class="Browse">Tipo Afectacion</td>
                                    <td width="43%" align="center" class="Browse">Justificacion</td>
								  <td align="center" class="Browse" colspan="2">Selecci&oacute;n</td>
								</tr>
							</thead>
							
							<?php
							
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
								{
								$c=$llenar_grilla["idcargo"]; 
								
								
								
								if($llenar_grilla["afecta"] == "incorporacion"){
									$i = 1;
								}else{
									$i = 2;
								}
								$sql_consulta = mysql_query("select * from tipo_movimiento_bienes where idtipo_movimiento_bienes = '".$llenar_grilla["idtipo_movimiento"]."'");
								$bus_consulta = mysql_fetch_array($sql_consulta);
									
									if($bus_consulta["origen_bien"] == "nuevo"){
										$accion_tipo_movimiento = 'nuevo';
									}else if($bus_consulta["origen_bien"] == "existente" and $i == 1){
										$accion_tipo_movimiento = 'existente_incorporacion';
									}else if($bus_consulta["origen_bien"] == "existente" and $i == 2){
										$accion_tipo_movimiento = 'existente_desincorporacion';
									}else{
										$accion_tipo_movimiento = 'ambos';
									}

								?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.document.getElementById('accion_tipo_movimiento').value = '<?=$accion_tipo_movimiento?>', opener.consultarMovimientos('<?=$llenar_grilla["idmovimientos_bienes"]?>', '<?=$llenar_grilla["estado"]?>'), window.close()">
									
                                    <td align='left' class='Browse' width='17%'>
                                    <?
                                    if($llenar_grilla["nro_movimiento"] == ""){
										echo "En Elaboracion";
									}else{
										echo "<strong>".$llenar_grilla["nro_movimiento"]."</strong>";
									}
									?>
                                    &nbsp;</td>
									<td align='center' class='Browse'><?=$llenar_grilla["fecha_movimiento"]?></td>
                                    <td align='left' class='Browse'><?=$llenar_grilla["afecta"]?></td>
                                    <td align='left' class='Browse'>&nbsp;<?=$llenar_grilla["justificacion"]?></td>
									<td align='center' class='Browse' width='9%'><img src='../../imagenes/validar.png'></td>
						  </tr>
                                <?
								}
							
							?>
						</table>
						
					</td>
				</tr>
			</table>
            
		</div>
        <?
								
		}
		?>
	
</body>
</html>