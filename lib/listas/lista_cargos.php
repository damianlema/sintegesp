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
$conexion_db=conectarse();
$existen_registros=0;
$buscar_registros=$_GET["busca"];
$m=$_GET["m"];

$registros_grilla=mysql_query("select * from cargos where status='a' order by denominacion",$conexion_db); 

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql="select * from cargos where status='a'";
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
	$m=$_POST["modoactual"];
}


//if (mysql_num_rows($registros_trabajador)<=0)
//		{
//			$existen_registros=1;
//		}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>

<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
function ponCargo(idcargo){
	m=document.buscar.modoactual.value
	opener.document.forms[0].idcargo.value=idcargo
	opener.document.forms[0].modoactual.value=m
	opener.document.forms[0].emergente.value="true"
	opener.document.forms[0].submit()
	window.close()
}
</SCRIPT>
</head>
	
	<body>
	<br>
	<h4 align=center>Listado de Cargos</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	
	<?php //echo $m;?>
	
	<form name="buscar" action="lista_cargos.php" method="POST">
	<input type="hidden" name="modoactual" id="modoactual" <?php echo 'value="'.$m.'"';?>>
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
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<form name="grilla" action="lista_cargos.php" method="POST">
						<table class="Browse" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<!--<td class="Browse">&nbsp;</td>-->
									<td align="center" class="Browse">C&oacute;digo</td>
									<td align="center" class="Browse">Denominacion</td>
									<td align="center" class="Browse" colspan="2">Selecci&oacute;n</td>
								</tr>
							</thead>
							
							<?php
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
								{
								$c=$llenar_grilla["idcargo"]; 
								?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="ponCargo('<?=$c?>')">
								<?php
									echo "<td align='right' class='Browse' width='20%'>".$llenar_grilla["idcargo"]."</td>";
									echo "<td align='left' class='Browse'>".$llenar_grilla["denominacion"]."</td>";
									
									echo "<td align='center' class='Browse' width='7%'> <a href='#' onclick='ponCargo(".$c.")'><img src='../../imagenes/validar.png'></a></td>";
								echo "</tr>";
								}
							}
							//".$c.",".$formulario."
							?>
						</table>
						</form>
					</td>
				</tr>
			</table>
		</div>
	
</body>
</html>