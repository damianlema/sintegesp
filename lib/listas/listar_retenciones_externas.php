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
	<h4 align=center>Listado de Retenciones Externas</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	
	<?php //echo $m;?>
	
	<form name="buscar" action="" method="POST">
	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Texto a buscar:</td>
			<td class='viewProp'>
            	<input type="text" name="texto_busqueda" maxlength="60" size="30" value="<?=$_POST["texto_busqueda"]?>">
            </td>

			<td>
				<input align=center class="button" name="buscar" type="submit" value="Buscar">
			</td>
		</tr>
	</table>
	</form>
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="90%">
		  <tr>
					<td>
						<form name="grilla" action="lista_ordinal.php" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="100%">
					  <thead>
								<tr>
									<td width="12%" align="center" class="Browse">Nro. Retencion</td>
                                  <td width="37%" align="center" class="Browse">Ente Gubernamental</td>
                                  <td width="34%" align="center" class="Browse">Beneficiario</td>
                                  <td width="13%" align="center" class="Browse">Fecha Retencion</td>
                                  <td width="13%" align="center" class="Browse">Eatado</td>
								  <td width="4%" align="center" class="Browse">Acci&oacute;n</td>
						</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
							if($_POST["buscar"]){
									$sql="select retenciones.nro_retencion as nro_retencion,
											 retenciones.idretenciones as idretenciones,
											 relacion_retenciones_externas.numero_factura as nro_factura,
											 entes_gubernamentales.nombre as nombre_ente,
											 beneficiarios.nombre as nombre_bneficiario,
											 retenciones.fecha_retencion as fecha_retencion,
											 retenciones.estado as estado
											 	from 
											 retenciones, 
											 relacion_retenciones_externas,
											 entes_gubernamentales,
											 beneficiarios 
											 	where 
											 retenciones.tipo_retencion = '1'
											 and relacion_retenciones_externas.idretencion = retenciones.idretenciones
											 and (retenciones.nro_retencion like '%".$_POST["texto_busqueda"]."%'
											 or relacion_retenciones_externas.numero_factura like '%".$_POST["texto_busqueda"]."%'
											 or beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%'
											 or entes_gubernamentales.nombre like '%".$_POST["texto_busqueda"]."%')
											 and entes_gubernamentales.identes_gubernamentales = retenciones.idente_gubernamental
											 and beneficiarios.idbeneficiarios = retenciones.idbeneficiarios
											 group by retenciones.idretenciones order by retenciones.idretenciones ASC";
								$sql_consulta = mysql_query($sql)or die(mysql_error());
									
							
							
								
								while($bus_consulta = mysql_fetch_array($sql_consulta)){
								 ?>
								<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.consultarRetencion('<?=$bus_consulta["idretenciones"]?>'), window.close()">
									<td align='left' class='Browse'>&nbsp;<?=$bus_consulta["nro_retencion"]?></td>
                                    <td align='left' class='Browse'>&nbsp;<?=$bus_consulta["nombre_ente"]?></td>
                                    <td align='left' class='Browse'>&nbsp;<?=$bus_consulta["nombre_bneficiario"]?></td>
                                    <td align='left' class='Browse'>&nbsp;<?=$bus_consulta["fecha_retencion"]?></td>
                                    <td align='left' class='Browse'>&nbsp;<?=$bus_consulta["estado"]?></td>
                                    <td align="center" class='Browse'><img src="../../imagenes/validar.png" style="cursor:pointer" onClick="opener.consultarRetencion('<?=$bus_consulta["idretenciones"]?>'), window.close()"></td>
                          </tr>
								<?
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