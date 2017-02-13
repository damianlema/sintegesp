<?php
/**
*
*	 "lista_traslados_presupuestarios.php" Listado de Rectificaci&oacute;n Presupuestaria
*	Version: 1.0.1
*	Fecha Ultima Modificacion: 27/02/2009	
*	Autor: Hector Lema
*
*/
ob_start();
session_start();
include_once("../../conf/conex.php");

$conexion_db=conectarse();
$existen_registros=0;
$buscar_registros=$_GET["busca"];
$m=$_GET["m"];
$juntos=$_GET["j"];
$guardo=$_GET["g"];

if($_GET["eliminar"]){
	$sql_eliminar = mysql_query("delete from rectificacion_presupuesto where idrectificacion_presupuesto = '".$_GET["eliminar"]."'");
	$sql_eliminar = mysql_query("delete from partidas_rectificadoras where idrectificacion_presupuesto = '".$_GET["eliminar"]."'");
	$sql_eliminar = mysql_query("delete from partidas_receptoras_rectificacion where idrectificacion_presupuesto = '".$_GET["eliminar"]."'");
}

$registros_grilla=mysql_query("select * from rectificacion_presupuesto where status='a' order by nro_solicitud",$conexion_db); 

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$campo_busqueda=$_POST["tipobusqueda"];
	$sql="select * from rectificacion_presupuesto where status='a'";
			
			if ($campo_busqueda=="E"){
				$registros_grilla=mysql_query($sql." and estado =  'En elaboracion' order by nro_solicitud",$conexion_db);
			}
			if ($campo_busqueda=="P"){
				$registros_grilla=mysql_query($sql." and estado =  'Procesado' order by nro_solicitud",$conexion_db);
			}
			if ($campo_busqueda=="A"){
				$registros_grilla=mysql_query($sql." and estado =  'Anulado' order by nro_solicitud",$conexion_db);
			}
			
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			if ($campo_busqueda=="n"){
				$registros_grilla=mysql_query($sql." and nro_solicitud like '$texto_buscar%' order by nro_solicitud",$conexion_db);
			}
			if ($campo_busqueda=="r"){
				$registros_grilla=mysql_query($sql." and nro_resolucion like '$texto_buscar%' order by nro_resolucion",$conexion_db);
			}
			if ($campo_busqueda=="j"){
				$registros_grilla=mysql_query($sql." and justificacion like '$texto_buscar%' order by justificacion",$conexion_db);
			}
		}
	}
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

<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
function ponRectificacion(idRectificacion){
	m=document.buscar.modoactual.value
	opener.document.forms[0].idrectificacion_emergente.value=idRectificacion
	opener.document.forms[0].idrectificacion_presupuesto.value=idRectificacion
	opener.document.forms[0].modoactual.value=1
	opener.document.forms[0].juntos.value=document.buscar.juntos.value
	opener.document.forms[0].guardo.value=true
	opener.document.forms[0].emergente.value="true"
	opener.document.forms[0].submit()
	window.close()
}
</SCRIPT>
</head>
	
	<body>
	<br>
	<h4 align=center>Listado de Rectificaciones Presupuestarias</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	
	<?php //echo $m;?>
	
	<form name="buscar" action="lista_rectificacion_presupuesto.php" method="POST">
	<input type="hidden" name="modoactual" id="modoactual" <?php echo 'value="'.$m.'"';?>>
	<input type="hidden" name="guardo" id="guardo" <?php echo 'value="'.$guardo.'"';?>>
	<input type="hidden" name="juntos" id="juntos" <?php echo 'value="'.$juntos.'"';?>>
	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Buscar:</td>
			<td class='viewProp'><input type="text" name="textoabuscar" maxlength="60" size="30"></td>
			<td align='right' class='viewPropTitle'>Por:</td>
			<td class='viewProp'>
				<select name="tipobusqueda">
					<option VALUE="n">Nro. Solicitud</option>
					<option VALUE="r">Nro. Resoluci&oacute;n</option>
					<option VALUE="j">Justificaci&oacute;n</option>
                    <option VALUE="E">En elaboraci&oacute;n</option>
                    <option VALUE="P">Procesados</option>
                    <option VALUE="A">Anulados</option>
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
						<form name="grilla" action="lista_creditos_adicionales.php" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="95%">
							<thead>
								<tr>
									<td align="center" class="Browse">Nro. Solicitud</td>
									<td align="center" class="Browse">Nro. Resoluci&oacute;n</td>
									<td align="center" class="Browse">Fecha Resoluci&oacute;n</td>
									<td align="center" class="Browse">Justificaci&oacute;n</td>
                                    <td align="center" class="Browse">Estado</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
									{ 
									$c=$llenar_grilla["idrectificacion_presupuesto"];
									?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="ponRectificacion("<?=$c?>")">
								<?php
									echo "<td align='left' class='Browse' width='6%'>".$llenar_grilla["nro_solicitud"]."</td>";
									echo "<td align='left' class='Browse' width='6%'>"."&nbsp;".$llenar_grilla["nro_resolucion"]."</td>";
									echo "<td align='center' class='Browse' width='6%'>".$llenar_grilla["fecha_resolucion"]."</td>";
									echo "<td align='left' class='Browse'>".utf8_decode($llenar_grilla["justificacion"])."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["estado"]."</td>";
									
									echo "<td align='center' class='Browse' width='7%'> <a href='#' onclick='ponRectificacion(".$c.")'><img src='../../imagenes/validar.png'></a></td>";
									if ($llenar_grilla["estado"] == 'Anulado'){ ?>
										<td align='center' class='Browse' width='7%'>
                                    	<a href="?eliminar=<?=$llenar_grilla["idrectificacion_presupuesto"]?>">
										<img src="../../imagenes/delete.png">
										</a>
                                        </td>
									<? } else {
										echo "<td align='center' class='Browse' width='7%'>&nbsp;";
										echo "</td>";
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