<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);




if($ejecutar == "ingresarConstante"){
	if($valor == ""){
		$valor = 0;	
	}
	$sql_insertar = mysql_query("insert into constantes_nomina(codigo,
																abreviatura,
																descripcion,
																unidad,
																tipo,
																equivalencia,
																maximo,
																valor,
																idclasificador_presupuestario,
																idordinal,
																mostrar,
																afecta,
																posicion)VALUES('".$codigo."',
																			'".$abreviatura."',
																			'".$descripcion."',
																			'".$unidad."',
																			'".$tipo."',
																			'".$equivalencia."',
																			'".$maximo."',
																			'".$valor."',
																			'".$id_clasificador."',
																			'".$idordinal."',
																			'".$mostrar."',
																			'".$afecta."',
																			'".$posicion."')")or die("Error insertando la nueva constante ".mysql_error());
	
	
	
	
	registra_transaccion("Se inserto una nueva constante de nomina (".mysql_insert_id().")",$login,$fh,$pc,'constantes_nomina');
}



if($ejecutar == "modificarConstante"){
	if($valor == ""){
		$valor = 0;	
	}
	$sql_insertar = mysql_query("update constantes_nomina set codigo = '".$codigo."',
																abreviatura = '".$abreviatura."',
																descripcion = '".$descripcion."',
																unidad = '".$unidad."',
																tipo = '".$tipo."',
																equivalencia = '".$equivalencia."',
																maximo = '".$maximo."',
																valor = '".$valor."',
																mostrar = '".$mostrar."',
																afecta = '".$afecta."',
																idclasificador_presupuestario = '".$id_clasificador."',
																idordinal = '".$idordinal."',
																posicion = '".$posicion."'
																	where idconstantes_nomina = '".$idconstante_nomina."'")or die("Error Modificando la nueva constante ".mysql_error());
	
	
/*if($tipo == "valor_fijo"){
		$sql_consulta_trabajadores = mysql_query("select * from 
															relacion_concepto_trabajador rct
															where 
															rct.tabla = 'constantes_nomina'
															and rct.idconcepto = '".$idconstante_nomina."'");
		while($bus_consulta_trabajadores = mysql_fetch_array($sql_consulta_trabajadores)){
		
		$sql_trabajador = mysql_query("select * from trabajador where 
											idtrabajador = '".$bus_consulta_trabajadores["idtrabajador"]."'");
		$bus_trabajador = mysql_fetch_array($sql_trabajador);
		$sql_ingresar = mysql_query("insert into movimientos_personal(
											  idtrabajador,
											  fecha_movimiento,
											  idtipo_movimiento,
											  justificacion,
											  fecha_ingreso,
											  causal,
											  idcargo,
											  idubicacion_funcional,
											  usuario,
											  status,
											  fechayhora,
											  centro_costo,
											  idtipo_nomina)VALUES(
												'".$bus_consulta_trabajadores["idtrabajador"]."',
												'".date("Y-m-d")."',
												'1000000',
												'MODIFICACION DE LA CONSTANTE ASOCIADA (".$descripcion." AL VALOR : ".$valor.")',
												'".$bus_trabajador["fecha_ingreso"]."',
												'".$causal."',
												'".$bus_trabajador["idcargo"]."',
												'".$bus_trabajador["idunidad_funcional"]."',
												'".$login."',
												'a',
												'".$fh."',
												'".$bus_trabajador["centro_costo"]."',
												'".$bus_trabajador["idtipo_nomina"]."')")or die(mysql_error());		
		}													
	}
	*/
	
	
	
	
	
	registra_transaccion("Se modifico constante de nomina (".$idconstante_nomina.")",$login,$fh,$pc,'constantes_nomina');
}





if($ejecutar == "actualizarLista"){
	?>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="60%">
    <thead>
      <tr>
        <td width="13%" align="center" class="Browse">Clasificador Presupuestario</td>
        <td width="8%" align="center" class="Browse">Ordinal</td>
        <td width="15%" align="center" class="Browse">Codigo</td>
        <td width="11%" align="center" class="Browse">Abreviatura</td>
        <td width="42%" align="center" class="Browse">Descripcion</td>
        <td align="center" class="Browse" colspan="3">Accion</td>
      </tr>
    </thead>
    <?
          $sql_consulta = mysql_query("select * from constantes_nomina");
		  while($bus_consulta = mysql_fetch_array($sql_consulta)){
		  ?>
    <tr bgcolor='#e7dfce' onmouseover="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onmouseout="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
      <?
          $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_consulta["idclasificador_presupuestario"]."'");
		  $bus_clasificador = mysql_fetch_array($sql_clasificador);
		  $sql_ordinal = mysql_query("select * from ordinal where idordinal = '".$bus_consulta["idordinal"]."'");
		  $bus_ordinal = mysql_fetch_array($sql_ordinal);
		  ?>
      <td class='Browse' align='left'><?=$bus_clasificador["codigo_cuenta"]?>
        &nbsp;</td>
      <td class='Browse' align='left'><?=$bus_ordinal["codigo"]?>
        &nbsp;</td>
      <td class='Browse' align='left'><?=$bus_consulta["codigo"]?>
        &nbsp;</td>
      <td class='Browse' align='left'><?=$bus_consulta["abreviatura"]?>
        &nbsp;</td>
      <td class='Browse' align='left'><?=$bus_consulta["descripcion"]?>
        &nbsp;</td>
      <td width="5%" align='center' class='Browse'><?
            $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_consulta["idclasificador_presupuestario"]."'");
			$bus_clasificador = mysql_fetch_array($sql_clasificador);
			
			$sql_ordinal = mysql_query("select * from ordinal where idordinal = '".$bus_consulta["idordinal"]."'");
			$bus_ordinal = mysql_fetch_array($sql_ordinal);
			
			
			$sql_consulta_asociacion =mysql_query("select * from relacion_concepto_trabajador where idconcepto = '".$bus_consulta["idconstantes_nomina"]."' and tabla = 'constantes_nomina'");
			$num_consulta_asociacion = mysql_num_rows($sql_consulta_asociacion);
			if($num_consulta_asociacion > 0){
				$asociado = 'si';	
			}else{
				$asociado = 'no';	
			}
			
			?>
        <img src="imagenes/modificar.png" onclick="mostrarModificar('<?=$bus_consulta["idconstantes_nomina"]?>',
                															'<?=$bus_consulta["codigo"]?>',
                                                                            '<?=$bus_consulta["abreviatura"]?>',
                                                                            '<?=$bus_consulta["descripcion"]?>',
                                                                            '<?=$bus_consulta["unidad"]?>',
                                                                            '<?=$bus_consulta["tipo"]?>',
                                                                            '<?=$bus_consulta["equivalencia"]?>',
                                                                            '<?=$bus_consulta["maximo"]?>',
                                                                            '<?=$bus_consulta["valor"]?>',
                                                                            '<?=$bus_consulta["idclasificador_presupuestario"]?>',
                                                                            '<?=$bus_consulta["idordinal"]?>',
                                                                            '<?=$bus_clasificador["denominacion"]?>',
                                                                            '<?=$bus_ordinal["denominacion"]?>',
                                                                            '<?=$bus_consulta["mostrar"]?>',
                                                                            '<?=$asociado?>',
                                                                            '<?=$bus_consulta["afecta"]?>',
                                                                            '<?=$bus_consulta["posicion"]?>')" style="cursor:pointer" /></td>
      <td width="6%" align='center' class='Browse'><img src="imagenes/delete.png" onclick="mostrarEliminar('<?=$bus_consulta["idconstantes_nomina"]?>',
                															'<?=$bus_consulta["codigo"]?>',
                                                                            '<?=$bus_consulta["abreviatura"]?>',
                                                                            '<?=$bus_consulta["descripcion"]?>',
                                                                            '<?=$bus_consulta["unidad"]?>',
                                                                            '<?=$bus_consulta["tipo"]?>',
                                                                            '<?=$bus_consulta["equivalencia"]?>',
                                                                            '<?=$bus_consulta["maximo"]?>',
                                                                            '<?=$bus_consulta["valor"]?>',
                                                                            '<?=$bus_consulta["idclasificador_presupuestario"]?>',
                                                                            '<?=$bus_consulta["idordinal"]?>',
                                                                            '<?=$bus_clasificador["denominacion"]?>',
                                                                            '<?=$bus_ordinal["denominacion"]?>',
                                                                            '<?=$bus_consulta["mostrar"]?>',
                                                                            '<?=$asociado?>',
                                                                            '<?=$bus_consulta["afecta"]?>',
                                                                            '<?=$bus_consulta["posicion"]?>')" style="cursor:pointer" /></td>
    
    <td width="10%" align='center' class='Browse'>
        
        <?
        if($bus_consulta["tipo"] == "valor_fijo"){
		?>
        
        <img src="imagenes/refrescar.png" onclick="document.getElementById('div_proceso_lote').style.display='block', document.getElementById('idconstante_procesar_lote').value = '<?=$bus_consulta["idconstantes_nomina"]?>'" alt="Procesos en Lote" style="cursor:pointer"/>
        <?
		}else{
			?>&nbsp;<?	
		}
		?>
        </td>
    
    </tr>
    <?
         }
		 ?>
  </table>
	<?
}





if($ejecutar == "eliminarConstante"){
	//echo "PRUEBAAAAAAA ";
	$sql_consulta_asociacion =mysql_query("select * from relacion_concepto_trabajador where idconcepto = '".$idconstantes_nomina."' and tabla = 'constantes_nomina'");
	$num_consulta_asociacion = mysql_num_rows($sql_consulta_asociacion);
	//echo "AQUIIIIIIIIII:".$num_consulta_asociacion;
	if($num_consulta_asociacion > 0){
		echo "asociada_trabajador";	
	}else{
		
		$sql_consultar_conceptos= mysql_query("select * from relacion_formula_conceptos_nomina where valor_oculto = 'CN_".$idconstantes_nomina."'");
		$num_consultar_conceptos = mysql_num_rows($sql_consultar_conceptos);
		if($num_consultar_conceptos > 0){
			echo "asociada_concepto";		
		}else{
			$sql_eliminar = mysql_query("delete from constantes_nomina where idconstantes_nomina = '".$idconstantes_nomina."'");
			registra_transaccion("Se Elimino la constante de nomina (".$idconstantes_nomina.")",$login,$fh,$pc,'constantes_nomina');	
		}
	}
}






if($ejecutar == "procesoPorLote"){
	if($tipo_nomina == "todas"){
			$query_tipo_nomina = "";
		}else{
			$query_tipo_nomina = " and idtipo_nomina = '".$tipo_nomina."'";
		}
	

	$sql_consulta = mysql_query("select * from relacion_concepto_trabajador 
											where idconcepto = '".$idconstante."' 
											and tabla = 'constantes_nomina'
											and (valor >= ".$rango_entre." and valor <= ".$rango_hasta.")
											".$query_tipo_nomina."")or die(mysql_error());
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
		if($tipo == "porcentual"){
			$porcentaje = ($bus_consulta["valor"]*$valor)/100;
			$total = $bus_consulta["valor"]+$porcentaje;
		//echo $total.".....";
		}else{
			$total = $valor;	
		}
		
		$total = round($total,2);
		
		
		
		$sql_actualizar = mysql_query("update relacion_concepto_trabajador set valor = '".$total."' 
									  	where idrelacion_concepto_trabajador = '".$bus_consulta["idrelacion_concepto_trabajador"]."'");
		
		$sql_constantes = mysql_query("select * from constantes_nomina where idconstantes_nomina = '".$idconstante."'");
		$bus_constantes = mysql_fetch_array($sql_constantes);
		
		$sql_consulta_trabajador = mysql_query("select * from trabajador where idtrabajador = '".$bus_consulta["idtrabajador"]."'");
		$bus_consulta_trabajador = mysql_fetch_array($sql_consulta_trabajador);
										
		$sql_ingresar = mysql_query("insert into movimientos_personal(
											  idtrabajador,
											  fecha_movimiento,
											  idtipo_movimiento,
											  justificacion,
											  fecha_ingreso,
											  causal,
											  idcargo,
											  idubicacion_funcional,
											  usuario,
											  status,
											  fechayhora,
											  centro_costo,
											  idconstante,
											  valor)VALUES(
												'".$bus_consulta["idtrabajador"]."',
												'".date("Y-m-d")."',
												'1000000',
												'MODIFICACION DE LA CONSTANTE ASOCIADA (".$bus_constantes["descripcion"]." AL VALOR : ".$total.")',
												'".$bus_consulta_trabajador["fecha_ingreso"]."',
												'".$causal."',
												'".$bus_consulta_trabajador["idcargo"]."',
												'".$bus_consulta_trabajador["idunidad_funcional"]."',
												'".$login."',
												'a',
												'".$fh."',
												'".$bus_consulta_trabajador["centro_costo"]."',
												'".$idconstante."',
												'".$total."')")or die(mysql_error());		

		
		
	}
}

?>