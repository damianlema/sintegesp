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
	<h4 align=center>Listado de Tipos de Nomina</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	
	<?php //echo $m;?>
	
	<form name="buscar" action="" method="POST">
	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Buscar:</td>
			<td class='viewProp'><input type="text" name="textoabuscar" maxlength="60" size="30"></td>
			<td>
				<input align=center class="button" name="buscar" type="submit" value="Buscar">
				</a>			</td>
		</tr>
	</table>
	</form>
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="95%">
				<tr>
					<td>
						<form name="grilla" action="../clasificador_presupuestario.php" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
								  <td width="90%" align="center" class="Browse">Titulo Nomina</td>
								  <td width="10%" align="center" class="Browse">Acci&oacute;n</td>
							  </tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
							if ($existen_registros==0){
								$registros_grilla = mysql_query("select * from tipo_nomina where titulo_nomina like '%".$_POST["textobuscar"]."%'");
								while($llenar_grilla= mysql_fetch_array($registros_grilla)){ 
									?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.consultarTipoNomina('<?=$llenar_grilla["idtipo_nomina"]?>'), window.close()">
								<?php
									echo "<td align='left' class='Browse' width='6%'>".$llenar_grilla["titulo_nomina"]."&nbsp;</td>";
									?> 
                                    <td align='center' class='Browse' width='26%'>
                                    	<img src='../../imagenes/validar.png' onClick="opener.consultarTipoNomina('<?=$llenar_grilla["idtipo_nomina"]?>'), window.close()">                                    </td> 
									<?
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