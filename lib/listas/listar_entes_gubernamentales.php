<?php
ob_start();
session_start();
include_once("../../conf/conex.php");
$conexion_db=conectarse();
extract($_POST);


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
	<h4 align=center>Listado de Entes Gubernamentales</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	
	<?php //echo $m;?>
	
	<form name="buscar" action="" method="POST">
	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Buscar:</td>
			<td class='viewProp'><input type="text" name="nombre" maxlength="60" size="30" value="<?=$_POST["nombre"]?>"></td>
			<td>
				<input align=center class="button" name="buscar" type="submit" value="Buscar">
				</a>			</td>
		</tr>
	</table>
	</form>
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<form name="grilla" action="lista_ordinal.php" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="80%">
							<thead>
								<tr>
									<td width="89%" align="center" class="Browse">Nombre</td>
									<td width="11%" align="center" class="Browse">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
								$sql="select * from entes_gubernamentales where nombre != ''";
								if ($nombre){
									if ($nombre<>""){
											$sql .= " and nombre like '%".$nombre."%'";
									}
								}
								$sql_consulta = mysql_query($sql);
								while($bus_consulta = mysql_fetch_array($sql_consulta)){
								 ?>
								<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.document.getElementById('ente_gubernamental').value = '<?=$bus_consulta["nombre"]?>', opener.document.getElementById('id_ente_gubernamental').value = '<?=$bus_consulta["identes_gubernamentales"]?>', window.close()">
									<td align='left' class='Browse'><?=$bus_consulta["nombre"]?></td>
                                    <td align="center"><img src="../../imagenes/validar.png" style="cursor:pointer" onClick="opener.document.getElementById('ente_gubernamental').value = '<?=$bus_consulta["nombre"]?>', opener.document.getElementById('id_ente_gubernamental').value = '<?=$bus_consulta["identes_gubernamentales"]?>', window.close()"></td>
                          </tr>
								<?
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