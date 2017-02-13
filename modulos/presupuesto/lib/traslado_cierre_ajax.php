<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();



if($ejecutar == "ingresarTraslado"){
	$sql_ingresar = mysql_query("insert into traslados_presupuestarios (nro_solicitud,
																		fecha_solicitud,
																		nro_resolucion,
																		fecha_resolucion,
																		fecha_ingreso,
																		justificacion,
																		anio,
																		estado,
																		ubicacion,
																		status,
																		usuario,
																		fechayhora)VALUES('".$nro_solicitud."',
																							'".$fecha_solicitud."',
																							'".$nro_resolucion."',
																							'".$fecha_resolucion."',
																							'".date("Y-m-d")."',
																							'".$justificacion."',
																							'".$anio."',
																							'elaboracion',
																							'".$ubicacion."',
																							'a',
																							'".$login."',
																							'".$fh."')")or die(mysql_error());
																							
$idtraslado_presupuestario = mysql_insert_id();
?>
<table class="Browse" align=center cellpadding="0" cellspacing="0" width="80%">
					  	<thead>
								<tr>
									<td align="center" class="Browse">A&ntilde;o</td>
									<td align="center" class="Browse">Categor&iacute;a Program&aacute;tica</td>
									<td align="center" class="Browse">Partida Presupuestaria</td>
									<td align="center" class="Browse">Monto Disminuir</td>
								</tr>
							</thead>
<?

$sql_maestro = mysql_query("select * from maestro_presupuesto");
	while($bus_maestro = mysql_fetch_array($sql_maestro)){
		
		/*$disponible_actual = $bus_maestro["monto_actual"] ;
		$disponible_resta = $bus_maestro["total_compromisos"] + $bus_maestro["pre_compromiso"] + $bus_maestro["reservado_disminuir"];
		$disponible = bcsub ( $disponible_actual, $disponible_resta , 2);
		*/
		
		$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
		if($disponible > 0){
			mysql_query("insert into partidas_cedentes_traslado(idtraslados_presupuestarios,
																idmaestro_presupuesto,
																monto_debitar,
																status,
																usuario,
																fechayhora)VALUES('".$idtraslado_presupuestario."',
																					'".$bus_maestro["idRegistro"]."',
																					'".$disponible."',
																					'a',
																					'".$login."',
																					'".$fh."')");		
			mysql_query("update traslados_presupuestarios set
									total_debito=(total_debito)+'".$disponible."'
									where idtraslados_presupuestarios = '".$idtraslado_presupuestario."'")or die(mysql_error());
					
			mysql_query("update maestro_presupuesto set
							reservado_disminuir = (reservado_disminuir) + '".$disponible."'
							where idregistro = '".$bus_maestro["idRegistro"]."'")or die(mysql_error());
			
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td align='center' class='Browse' width='7%'><?=$anio?></td>
            <td align='center' class='Browse' width='18%'>
			<?
            $sql_categoria = mysql_query("select codigo from categoria_programatica where idcategoria_programatica = '".$bus_maestro["idcategoria_programatica"]."'");
			$bus_categoria = mysql_fetch_array($sql_categoria);
			echo $bus_categoria["codigo"];
			?>            </td>
            <td align='left' class='Browse' width='61%'>
            <?
            $sql_clasificador = mysql_query("select codigo_cuenta, denominacion 
											from 
												clasificador_presupuestario 
											where
												idclasificador_presupuestario = '".$bus_maestro["idclasificador_presupuestario"]."'");
			$bus_clasificador = mysql_fetch_array($sql_clasificador);
			echo "(".$bus_clasificador["codigo_cuenta"].") ".$bus_clasificador["denominacion"];
			?>            </td>
            <td align='right' class='Browse' width='14%'><?=number_format($disponible,2,",",".")?></td>
  </tr>
			<?
		}
	}
	
?>
</table>
<?

}



?>