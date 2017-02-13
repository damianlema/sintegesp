<?php
ob_start();
session_start();
include_once("../../conf/conex.php");
include_once("../../lib/registra.php");
$conexion_db=conectarse();
$busqueda=$_POST['busqueda'];
// DEBO PREPARAR LOS TEXTOS QUE VOY A BUSCAR si la cadena existe

if ($busqueda<>''){
		$cadbusca="SELECT * FROM usuarios WHERE cedula LIKE '$busqueda'";
	}
	
	$result=mysql_query($cadbusca, $conexion_db);
	if (mysql_num_rows($result)<=0)
		{
			$_SESSION['existen_registros']=0;
			include("plantilla.php");
		}else{
			$_SESSION['existen_registros']=1;
			while ($row = mysql_fetch_array($result)){
				echo "
					<table align=center cellpadding=2 cellspacing=0>
						<tr><td align='right' class='viewPropTitle'>Apellidos:</td>
							<td class=''><input type='text' name='apellidos' maxlength='45' size='45' id='apellidos' value='".$row['apellidos']."'></td>
							<td align='right' class='viewPropTitle'>Nombres:</td>
							<td class=''><input type='text' name='nombres' maxlength='45' size='45' id='nombres' value='".$row['nombres']."'></td>
						</tr>
					</table>
					";
			}
		}
?>
	