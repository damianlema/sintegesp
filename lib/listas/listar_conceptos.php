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

if($_GET["eliminar"]){
	$sql_eliminar = mysql_query("delete from conceptos_nomina where idconceptos_nomina ='".$_GET["eliminar"]."'");
	$sql_eliminar_formula= mysql_query("delete from relacion_formula_conceptos_nomina where idconcepto_nomina = '".$_GET["eliminar"]."'");
}


if($_POST){
	$registros_grilla=mysql_query("select co.codigo, 
								  			co.descripcion,
											co.idconceptos_nomina,
											tc.descripcion as descripcion_tipo_concepto,
											co.tipo_concepto,
											co.idclasificador_presupuestario,
											co.idordinal,
											co.desagregar_concepto,
											co.factor_desagregacion,
											co.posicion,
											co.aplica_prestaciones,
											co.columna_prestaciones
												from 
											conceptos_nomina co,
											tipo_conceptos_nomina tc
												where 
												co.tipo_concepto = '".$_POST["tipo_concepto"]."'
												and tc.idconceptos_nomina = co.tipo_concepto
												order by co.codigo",$conexion_db)or die(mysql_error()); 
}else{
	$registros_grilla=mysql_query("select co.codigo, 
								  			co.descripcion,
											co.idconceptos_nomina,
											tc.descripcion as descripcion_tipo_concepto,
											co.tipo_concepto,
											co.idclasificador_presupuestario,
											co.idordinal,
											co.desagregar_concepto,
											factor_desagregacion,
											co.posicion,
											co.aplica_prestaciones,
											co.columna_prestaciones
												from 
											conceptos_nomina co,
											tipo_conceptos_nomina tc
												where 
												tc.idconceptos_nomina = co.tipo_concepto
												order by co.codigo",$conexion_db)or die(mysql_error());  	
}




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
	
    <script>
		function eliminarConcepto(id){
			if(confirm("Seguro desea Eliminar este Concepto?")){
				window.location.href = 'listar_conceptos.php?eliminar='+id+'';
			}	
		}
	</script>

</head>
	
	<body>
	<br>
	<h4 align=center>Listado de Conceptos</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	
	<?php //echo $m;?>
	
	<form name="buscar" action="" method="POST">
	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Buscar:</td>
			<td class=''>
            <?
            $sql_tipo_concepto = mysql_query("select * from tipo_conceptos_nomina");
			
			?>
            <select name="tipo_concepto" id="tipo_concepto">
            	<?
                while($bus_tipo_concepto = mysql_fetch_array($sql_tipo_concepto)){
				?>
                <option <? if($_POST["tipo_concepto"] == $bus_tipo_concepto["idconceptos_nomina"]){echo "selected";}?> value="<?=$bus_tipo_concepto["idconceptos_nomina"]?>">(<?=$bus_tipo_concepto["codigo"]?>)&nbsp;<?=$bus_tipo_concepto["descripcion"]?></option>
                <?
				}
				?>
            </select>
            </td>
			<td>
				<input align=center name="buscar" type="submit" value="Buscar" class="button">
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
                                    <td align="center" class="Browse">Tip de Concepto</td>
									<td align="center" class="Browse" colspan="2">Selecci&oacute;n</td>
								</tr>
							</thead>
							
							<?php
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla)){
								$sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario ='".$llenar_grilla["idclasificador_presupuestario"]."'");
								$bus_clasificador = mysql_fetch_array($sql_clasificador);
								$sql_ordinal = mysql_query("select * from ordinal where idordinal ='".$llenar_grilla["idordinal"]."'");
								$bus_ordinal = mysql_fetch_array($sql_ordinal);
								?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" >
										<td align='left' class='Browse' width='20%' onClick="opener.consultarConcepto('<?=$llenar_grilla["idconceptos_nomina"]?>', '<?=$llenar_grilla["codigo"]?>', '<?=$llenar_grilla["descripcion"]?>', '<?=$llenar_grilla["tipo_concepto"]?>', '<?=$llenar_grilla["idclasificador_presupuestario"]?>', '<?=$llenar_grilla["idordinal"]?>', '<?=$bus_clasificador["partida"]?>', '<?=$bus_clasificador["generica"]?>', '<?=$bus_clasificador["especifica"]?>', '<?=$bus_clasificador["sub_especifica"]?>', '<?=$bus_clasificador["denominacion"]?>', '<?=$bus_ordinal["codigo"]?>', '<?=$bus_ordinal["denominacion"]?>',  '<?=$llenar_grilla["posicion"]?>', '<?=$llenar_grilla["aplica_prestaciones"]?>', '<?=$llenar_grilla["columna_prestaciones"]?>'), window.close()"><?=$llenar_grilla["codigo"]?></td>
										<td align='left' class='Browse' onClick="opener.consultarConcepto('<?=$llenar_grilla["idconceptos_nomina"]?>', '<?=$llenar_grilla["codigo"]?>', '<?=$llenar_grilla["descripcion"]?>', '<?=$llenar_grilla["tipo_concepto"]?>', '<?=$llenar_grilla["idclasificador_presupuestario"]?>', '<?=$llenar_grilla["idordinal"]?>', '<?=$bus_clasificador["partida"]?>', '<?=$bus_clasificador["generica"]?>', '<?=$bus_clasificador["especifica"]?>', '<?=$bus_clasificador["sub_especifica"]?>', '<?=$bus_clasificador["denominacion"]?>', '<?=$bus_ordinal["codigo"]?>', '<?=$bus_ordinal["denominacion"]?>',  '<?=$llenar_grilla["posicion"]?>', '<?=$llenar_grilla["aplica_prestaciones"]?>', '<?=$llenar_grilla["columna_prestaciones"]?>'), window.close()"><?=$llenar_grilla["descripcion"]?></td>
                                        <td align='left' class='Browse' onClick="opener.consultarConcepto('<?=$llenar_grilla["idconceptos_nomina"]?>', '<?=$llenar_grilla["codigo"]?>', '<?=$llenar_grilla["descripcion"]?>', '<?=$llenar_grilla["tipo_concepto"]?>', '<?=$llenar_grilla["idclasificador_presupuestario"]?>', '<?=$llenar_grilla["idordinal"]?>', '<?=$bus_clasificador["partida"]?>', '<?=$bus_clasificador["generica"]?>', '<?=$bus_clasificador["especifica"]?>', '<?=$bus_clasificador["sub_especifica"]?>', '<?=$bus_clasificador["denominacion"]?>', '<?=$bus_ordinal["codigo"]?>', '<?=$bus_ordinal["denominacion"]?>',  '<?=$llenar_grilla["posicion"]?>', '<?=$llenar_grilla["aplica_prestaciones"]?>', '<?=$llenar_grilla["columna_prestaciones"]?>'), window.close()"><?=$llenar_grilla["descripcion_tipo_concepto"]?></td>
										<td align='center' class='Browse' width='7%'> 
                                        <a href='javascript:;' onClick="opener.consultarConcepto('<?=$llenar_grilla["idconceptos_nomina"]?>', '<?=$llenar_grilla["codigo"]?>', '<?=$llenar_grilla["descripcion"]?>', '<?=$llenar_grilla["tipo_concepto"]?>', '<?=$llenar_grilla["idclasificador_presupuestario"]?>', '<?=$llenar_grilla["idordinal"]?>', '<?=$bus_clasificador["partida"]?>', '<?=$bus_clasificador["generica"]?>', '<?=$bus_clasificador["especifica"]?>', '<?=$bus_clasificador["sub_especifica"]?>', '<?=$bus_clasificador["denominacion"]?>', '<?=$bus_ordinal["codigo"]?>', '<?=$bus_ordinal["denominacion"]?>',  '<?=$llenar_grilla["posicion"]?>', '<?=$llenar_grilla["aplica_prestaciones"]?>', '<?=$llenar_grilla["columna_prestaciones"]?>'), window.close()">
                                        	<img src='../../imagenes/validar.png'>
                                        </a>
                                        </td>
                                        <td align='center' class='Browse' width='7%'> 
                                        	<img src='../../imagenes/delete.png' onClick="eliminarConcepto('<?=$llenar_grilla["idconceptos_nomina"]?>')">
                                        </td>
						  </tr>
								<?
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