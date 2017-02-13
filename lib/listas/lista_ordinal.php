<?php
/**
*
*	 "lista_ordinal.php" Listado de Ordinales presupuestarios
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

$registros_grilla=mysql_query("select * from ordinal where status='a' order by codigo",$conexion_db); 

if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$campo_busqueda=$_POST["tipobusqueda"];
	$sql="select * from ordinal where status='a'";
	if ($texto_buscar<>""){
		if ($texto_buscar=="*"){
			$registros_grilla=mysql_query($sql,$conexion_db);
		}else{
			if ($campo_busqueda=="c"){
				$registros_grilla=mysql_query($sql." and codigo like '$texto_buscar%' order by codigo",$conexion_db);
			}
			if ($campo_busqueda=="d"){
				$registros_grilla=mysql_query($sql." and denominacion like '$texto_buscar%' order by denominacion",$conexion_db);
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
function ponOrdinal(idordinal,c,d){
	m=document.buscar.modoactual.value
	opener.document.forms[0].idordinal.value=idordinal
	opener.document.forms[0].ordinal.value=c
	opener.document.forms[0].denoordinal.value=d
	opener.document.forms[0].modoactual.value=m
	opener.document.forms[0].emergente.value="true"
	window.close()
}
</SCRIPT>
</head>
	
	<body>
	<br>
	<h4 align=center>Listado de Ordinales</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	
	<?php //echo $m;?>
	
	<form name="buscar" action="lista_ordinal.php" method="POST">
	<input type="hidden"  id="destino" name="destino" value="<?=$_REQUEST["destino"]?>">
    <input type="hidden" name="modoactual" id="modoactual" <?php echo 'value="'.$m.'"';?>>
	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Buscar:</td>
			<td class='viewProp'><input type="text" name="textoabuscar" maxlength="60" size="30"></td>
			<td align='right' class='viewPropTitle'>Por:</td>
			<td class='viewProp'>
				<select name="tipobusqueda">
					<option VALUE="p">C&oacute;digo</option>
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
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<form name="grilla" action="lista_ordinal.php" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="80%">
							<thead>
								<tr>
									<td align="center" class="Browse">C&oacute;digo</td>
									<td align="center" class="Browse">Denominaci&oacute;n</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php  //  llena la grilla con los registros de la tabla de grupos 
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla)) 
									{
									$i=$llenar_grilla["idordinal"]; 
									 $c=$llenar_grilla["codigo"]; 
									 $d=$llenar_grilla["denominacion"]; 
									 ?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" 
                                        onClick="
                                        <?
                                        if($_REQUEST["destino"] == "maestro_presupuesto"){
										?>
                                        	ponOrdinal('<?=$i?>','<?=$c?>','<?=$d?>')
                                        <?
                                        }
										if($_REQUEST["destino"] == "orden_compra"){
										?>
                                        	opener.document.getElementById('descripcion_ordinal').value = '(<?=$llenar_grilla["codigo"]?>) <?=$llenar_grilla["denominacion"]?>', opener.document.getElementById('id_ordinal').value = '<?=$llenar_grilla["idordinal"]?>', window.close()
										<?
                                        }
										if($_REQUEST["destino"] == "rendicion_cuentas"){
										?>
                                        opener.document.getElementById('descripcion_ordinal').value = '(<?=$llenar_grilla["codigo"]?>) <?=$llenar_grilla["denominacion"]?>', opener.document.getElementById('idordinal').value = '<?=$llenar_grilla["idordinal"]?>', window.close()
										<?
                                        }
										if($_REQUEST["destino"] == "materiales"){
										?>
                                        opener.document.getElementById('nombre_ordinal').value = '(<?=$llenar_grilla["codigo"]?>) <?=$llenar_grilla["denominacion"]?>', opener.document.getElementById('idordinal').value = '<?=$llenar_grilla["idordinal"]?>', window.close()
										<?
                                        }
										?>
                                        ">
									<td align='center' class='Browse' width='6%'><?=$llenar_grilla["codigo"]?></td>
									<td align='left' class='Browse'><?=$llenar_grilla["denominacion"]?></td>
									<? 
									if($_REQUEST["destino"] == "maestro_presupuesto"){
										?>
										<td align='center' class='Browse' width='7%'> 
                                    		<a href='#' onClick="ponOrdinal('<?=$i?>','<?=$c?>','<?=$d?>')"><img src='../../imagenes/validar.png'></a></td>
										</tr>
										<?
									}else if($_REQUEST["destino"] == "orden_compra"){
										?>
										<td align='center' class='Browse' width='7%'> 
                                    		<a href='#' onClick="opener.document.getElementById('descripcion_ordinal').value = '(<?=$llenar_grilla["codigo"]?>) <?=$llenar_grilla["denominacion"]?>', opener.document.getElementById('id_ordinal').value = '<?=$llenar_grilla["idordinal"]?>', window.close()"><img src='../../imagenes/validar.png'></a></td>
										</tr>
										<?
									}else if($_REQUEST["destino"] == "rendicion_cuentas"){
										?>
										<td align='center' class='Browse' width='7%'> 
                                    		<a href='#' onClick="opener.document.getElementById('descripcion_ordinal').value = '(<?=$llenar_grilla["codigo"]?>) <?=$llenar_grilla["denominacion"]?>', opener.document.getElementById('idordinal').value = '<?=$llenar_grilla["idordinal"]?>', window.close()"><img src='../../imagenes/validar.png'></a></td>
										</tr>
										<?
									}else if($_REQUEST["destino"] == "materiales"){
										?>
										<td align='center' class='Browse' width='7%'> 
                                    		<a href='#' onClick="opener.document.getElementById('nombre_ordinal').value = '(<?=$llenar_grilla["codigo"]?>) <?=$llenar_grilla["denominacion"]?>', opener.document.getElementById('idordinal').value = '<?=$llenar_grilla["idordinal"]?>', window.close()"><img src='../../imagenes/validar.png'></a></td>
										</tr>
										<?
									}
									
									
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