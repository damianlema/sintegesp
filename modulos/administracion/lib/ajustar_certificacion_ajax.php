<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);


if($ejecutar == "actualizarDatos"){
	
	$sql_consultar_descuento = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
	$bus_consultar_descuento = mysql_fetch_array($sql_consultar_descuento);
	
	$descuento_viejo = $bus_consultar_descuento["descuento"];
	$sub_total_viejo = $bus_consultar_descuento["sub_total"];
	
	
	$total = $exento + $sub_total + $impuesto;
	// ACTUALIZAR LOS IMPUESTOS
	
	/*if($impuesto != 0){
		$contar_relacion_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras 
														where idorden_compra_servicio = '".$id_orden_compra."'")or die(mysql_error());
		$num_contar_relacion_impuestos = mysql_num_rows($contar_relacion_impuestos);
		while($bus_contar_relacion_impuestos = mysql_fetch_array($contar_relacion_impuestos)){
			$totales_impuestos += $bus_contar_relacion_impuestos["total"];
		}
		if($totales_impuestos > $impuesto){
			$restante_impuesto = $totales_impuestos - $impuesto;
			$operador = "-";
		}else{
			$restante_impuesto = $impuesto - $totales_impuestos;
			$operador = "+";
		}
		$division_por_impuesto = $restante_impuesto/$num_contar_relacion_impuestos;
		$sql_actualizar_relacion = mysql_query("select * from relacion_impuestos_ordenes_compras 
																					where idorden_compra_servicio = '".$id_orden_compra."'");	
		while($bus_actualizar_relacion = mysql_fetch_array($sql_actualizar_relacion)){
			$sql_actualizar_relacion_impuestos = mysql_query("update relacion_impuestos_ordenes_compras
							set total = total ".$operador." ".$division_por_impuesto."
							where idrelacion_impuestos_ordenes_compras = '".$bus_actualizar_relacion["idrelacion_impuestos_ordenes_compras"]."'");
			
			$sql_impuestos = mysql_query("select * from impuestos where idimpuestos = '".$bus_actualizar_relacion["idimpuestos"]."'")or die("ERROR AL CONSULTAR LOS IMPUESTOS ".mysql_error());
			$bus_impuestos = mysql_fetch_array($sql_impuestos);
			
			$clasificador_presupuestario = $bus_impuestos["idclasificador_presupuestario"];
			$fuente_financiamiento = $bus_consultar_descuento["idfuente_financiamiento"];
			$ordinal = $bus_consultar_descuento["idordinal"];
			$tipo_presupuesto = $bus_consultar_descuento["idtipo_presupuesto"];
			$categoria_programatica = $bus_consultar_descuento["idcategoria_programatica"];
			$anio = $bus_consultar_descuento["anio"];
			
			$sql_maestro = mysql_query("select * from maestro_presupuesto where 
																idclasificador_presupuestario = '".$clasificador_presupuestario."'
																and idfuente_financiamiento = '".$fuente_financiamiento."'
																and idordinal = '".$ordinal."'
																and idtipo_presupuesto = '".$tipo_presupuesto."'
																and idcategoria_programatica = '".$categoria_programatica."'
																and anio = '".$anio."'")or die("ERROR AL CONSULTAR EL MAESTRO ".mysql_error());
			$bus_maestro = mysql_fetch_array($sql_maestro);
			//echo "ID MAESTRO PRESUPUESTO: ".$bus_maestro["idRegistro"];
			$idmaestro_presupuesto = $bus_maestro["idRegistro"];
			$sql_actualizar_partidas = mysql_query("update partidas_orden_compra_servicio 
																	set monto = monto ".$operador." ".$division_por_impuesto."
																	where idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'
																	and idorden_compra_servicio = '".$id_orden_compra."'")or die("ERROR AL ACTUALIZAR LAS PARTIDAS ".mysql_error()); 
		}
	}*/
	
	
	
	
	
	
	// ACTUALIZAR LAS PARTIDAS
	
/*	if($sub_total != $sub_total_viejo){
		$contar_partidas = mysql_query("select * from partidas_orden_compra_servicio 
														where idorden_compra_servicio = '".$id_orden_compra."'")or die(mysql_error());
		$num_contar_partidas = mysql_num_rows($contar_partidas);
		while($bus_contar_partidas = mysql_fetch_array($contar_partidas)){
			$totales_partidas += $bus_contar_partidas["monto"];
		}
		if($totales_partidas > $sub_total){
			$restante_partidas = $totales_partidas - $sub_total;
			$operador = "-";
		}else{
			$restante_partidas = $sub_total - $totales_partidas;
			$operador = "+";
		}
		$division_por_partidas = $restante_partidas/$num_contar_partidas;
		$sql_actualizar_partidas = mysql_query("select * from partidas_orden_compra_servicio
																		where idorden_compra_servicio = '".$id_orden_compra."'
																		and idmaestro_presupuesto != '".$idmaestro_presupuesto."'");	
		while($bus_actualizar_partidas = mysql_fetch_array($sql_actualizar_partidas)){
			$sql_actualizar_partidas_orden = mysql_query("update partidas_orden_compra_servicio
							set monto = monto ".$operador." ".$division_por_partidas."
							where idpartidas_orden_compra_servicio = '".$bus_actualizar_partidas["idpartidas_orden_compra_servicio"]."'");
								
		}
	}
	*/
	
	//*************************************************************
	

	
	
	
	$sql_actualizar_datos = mysql_query("update orden_compra_servicio set justificacion = '".$justificacion."',
																			exento = '".$exento."',
																			sub_total = '".$sub_total."',
																			impuesto = '".$impuesto."',
																			total = '".$total."',
																			ordenado_por = '".$ordenado_por."',
																			cedula_ordenado = '".$cedula_ordenado."'
																			where idorden_compra_servicio = '".$id_orden_compra."'")or die("Error en el Primer Query: ".mysql_error());
	
	
	
	
																			
	/*if($descuento != $descuento_viejo){
	//echo $bus_consultar_descuento["descuento"];
	//echo "entro aca";
	//echo "select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'";
		$sql_orden_compra_servicio = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
		$bus_orden_compra_servicio = mysql_fetch_array($sql_orden_compra_servicio);
			/*if($bus_orden_compra_servicio["sub_total_original"] == 0){
				$monto_total_orden_compra_servicio = $bus_orden_compra_servicio["exento_original"];
			}else{
				$monto_total_orden_compra_servicio = $bus_orden_compra_servicio["sub_total_original"];
			}
		$porcentaje_descuento = ($descuento*100)/$monto_total_orden_compra_servicio;
		$monto_total_orden_compra_servicio;
		$sql_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
			while($bus_partidas = mysql_fetch_array($sql_partidas)){
				if($descuento > 0){
					$monto_actualizar_partida = ($bus_partidas["monto_original"]*$porcentaje_descuento)/100;
					$sql_actualizar_partida = mysql_query("update partidas_orden_compra_servicio 
												set monto = monto_original - '".$monto_actualizar_partida."'
												where idpartidas_orden_compra_servicio = '".$bus_partidas["idpartidas_orden_compra_servicio"]."'");
				}else{
				//echo "entro aca";
					$sql_actualizar_partida = mysql_query("update partidas_orden_compra_servicio 
												set monto = monto_original
												where idpartidas_orden_compra_servicio = '".$bus_partidas["idpartidas_orden_compra_servicio"]."'");
				}
			}
		*/
		/*$sql_sumar_impuestos = mysql_query("select SUM(base_calculo_original) as suma_total_calculo from relacion_impuestos_ordenes_compras where idorden_compra_servicio = '".$id_orden_compra."'");
		$bus_suma_impuestos = mysql_fetch_array($sql_sumar_impuestos);
		
		//echo $bus_suma_impuestos["suma_total_calculo"];
		$sql_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where idorden_compra_servicio = '".$id_orden_compra."'");
			while($bus_impuestos = mysql_fetch_array($sql_impuestos)){
				if($descuento > 0){
					//echo "entro aca";
					$porcentaje_descuento_impuesto = (($bus_impuestos["base_calculo_original"]*100)/($bus_suma_impuestos["suma_total_calculo"]+$bus_orden_compra_servicio["exento_original"]));
					$restar_base_calculo = ($porcentaje_descuento_impuesto*$descuento)/100;
					echo $restar_base_calculo;
					$base_caculo = $bus_impuestos["base_calculo_original"]-$restar_base_calculo;
					$monto_actualizar_impuestos = ($base_caculo*$bus_impuestos["porcentaje"])/100;
					$sql_actualizar_impuestos = mysql_query("update relacion_impuestos_ordenes_compras 
									set total = '".$monto_actualizar_impuestos."',
									base_calculo = '".$base_caculo."'
									where idrelacion_impuestos_ordenes_compras = '".$bus_impuestos["idrelacion_impuestos_ordenes_compras"]."'");
					$impuesto_total += $monto_actualizar_impuestos;	
				}else{
				//echo "entro aca";
					$monto_actualizar_impuestos = ($bus_impuestos["base_calculo_original"]*$bus_impuestos["porcentaje"])/100;
					$sql_actualizar_impuestos = mysql_query("update relacion_impuestos_ordenes_compras 
									set total = '".$monto_actualizar_impuestos."',
									base_calculo = base_calculo_original
									where idrelacion_impuestos_ordenes_compras = '".$bus_impuestos["idrelacion_impuestos_ordenes_compras"]."'");
					$impuesto_total += $monto_actualizar_impuestos;
				}
			}
		
		$suma_exento_sub_total = $bus_orden_compra_servicio["exento_original"]+$bus_orden_compra_servicio["sub_total_original"];
		
		$porcentaje_exento = ($bus_orden_compra_servicio["exento_original"]*100)/$suma_exento_sub_total;
		$porcentaje_sub_total = ($bus_orden_compra_servicio["sub_total_original"]*100)/$suma_exento_sub_total;
		
		
		$monto_descontar_exento = ($porcentaje_exento*$descuento)/100;
		$monto_descontar_sub_total = ($porcentaje_sub_total*$descuento)/100;
		
		$exento = $bus_orden_compra_servicio["exento_original"]-$monto_descontar_exento;
		$sub_total = $bus_orden_compra_servicio["sub_total_original"]-$monto_descontar_sub_total;
		$total = $exento + $sub_total + $impuesto_total;
		$sql_actualizar_orden_compra_servicio = mysql_query("update orden_compra_servicio 
																	set exento = '".$exento."',
																	sub_total = '".$sub_total."',
																	impuesto = '".$impuesto_total."',
																	total = '".$total."',
																	descuento = '".$descuento."'
																	where idorden_compra_servicio = '".$id_orden_compra."'");
	}	*/						
}










if($ejecutar == "buscarOrdenes"){
	?>
	
	<table align="center" class="Browse" cellpadding="0" cellspacing="0" >
        <thead>
            <tr>
                <!--<td class="Browse">&nbsp;</td>-->
                <td align="center" class="Browse">Nº</td>
                <td align="center" class="Browse">Numero de Orden</td>
                <td align="center" class="Browse">Beneficiario</td>
                <td align="center" class="Browse">Justificacion</td>
                <td align="center" class="Browse">Exento</td>
                <td align="center" class="Browse">Sub Total</td>
                <td align="center" class="Browse">Impuesto</td>
                <td align="center" class="Browse">Total</td>
                <td align="center" class="Browse">Actz.</td>
            </tr>
        </thead>
    <?
    $sql_orden_compra = mysql_query("select * from orden_compra_servicio, tipos_documentos 
												where orden_compra_servicio.numero_orden != '' 
												and orden_compra_servicio.numero_orden like '%".$numero_orden."%'
												and orden_compra_servicio.tipo = tipos_documentos.idtipos_documentos
												and tipos_documentos.modulo like '%-4-%' order by fecha_elaboracion ASC");
    $i = 0;
    while($bus_orden_compra = mysql_fetch_array($sql_orden_compra)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <?
            $sql_beneficiario = mysql_query("select * from beneficiarios where idbeneficiarios = '".$bus_orden_compra["idbeneficiarios"]."'");
			$bus_beneficiario = mysql_fetch_array($sql_beneficiario);
			?>
            <td align="center" class='Browse'><?=$i+1?></td>
            <td align="left" class='Browse'><?=$bus_orden_compra["numero_orden"]?></td>
            <td align="left" class='Browse'><?=$bus_beneficiario["nombre"]?></td>
            <td align="left" class='Browse'><?=$bus_orden_compra["justificacion"]?></td>
            <td align="right" class='Browse'><?=$bus_orden_compra["exento"]?></td>
			<td align="right" class='Browse'><?=$bus_orden_compra["sub_total"]?></td>
            <td align="right" class='Browse'><?=$bus_orden_compra["impuesto"]?></td>
            <td align="right" class='Browse'><?=$bus_orden_compra["total"]?></td>
            <td align="center" class='Browse'>
            <img src="imagenes/validar.png" 
                title="Actualizar Descuento" 
                style="cursor:pointer" 
                onclick="mostrarDatos('<?=$bus_orden_compra["idorden_compra_servicio"]?>',
                						'<?=$bus_orden_compra["numero_orden"]?>',
                                        '<?=str_replace("\n"," ",$bus_orden_compra["justificacion"])?>',
                                        '<?=$bus_orden_compra["exento"]?>',
                                        '<?=$bus_orden_compra["sub_total"]?>',
                                        '<?=$bus_orden_compra["impuesto"]?>',
                                        '<?=$bus_orden_compra["total"]?>',
                                        '<?=number_format($bus_orden_compra["exento"],2,",",".")?>',
                                        '<?=number_format($bus_orden_compra["sub_total"],2,",",".")?>',
                                        '<?=number_format($bus_orden_compra["impuesto"],2,",",".")?>',
                                        '<?=number_format($bus_orden_compra["total"],2,",",".")?>',
                                        '<?=$bus_orden_compra["ordenado_por"]?>',
                                        '<?=$bus_orden_compra["cedula_ordenado"]?>')">            </td>
      </tr>
        <?
		$i++;
    }
    
    ?>
    </table>
	
	<?
}








if($ejecutar == "listarPartidas"){
	
	$sql_articulos_compras =  mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'")or die(mysql_error());
	
	
?>
<strong style="padding-left:50px">ARTICULOS</strong>
 	<table width="85%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
		<thead>
          <tr>
            <td class="Browse"><div align="center">Nombre Articulo</div></td>
            <td width="19%" class="Browse"><div align="center">Monto</div></td>
            <td class="Browse"><div align="center">Acciones</div></td>
          </tr>
      </thead>
          <? 
          while($bus_articulos_compras = mysql_fetch_array($sql_articulos_compras)){
		  
		  $sql_articulo = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_articulos_compras["idarticulos_servicios"]."'");
		  $bus_articulo = mysql_fetch_array($sql_articulo);
		  ?>
           <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td width="74%" align='left' class='Browse'>(<?=$bus_articulo["codigo"]?>)&nbsp;<?=$bus_articulo["descripcion"]?></td>
            <td class='Browse' align='center'>
			<?
            if($bus_articulos_compras["exento"] != 0){
				?>
				<input type="text" 
                		name="valor_mostrado<?=$bus_articulos_compras["idarticulos_compra_servicio"]?>" 
                        id="valor_mostrado<?=$bus_articulos_compras["idarticulos_compra_servicio"]?>"
                        value="<?=number_format($bus_articulos_compras["exento"],2,',','.')?>"
                        style="text-align:right"
                        onblur="formatoNumero(this.name, 'valor<?=$bus_articulos_compras["idarticulos_compra_servicio"]?>')">
               <input type="hidden" 
                		name="valor<?=$bus_articulos_compras["idarticulos_compra_servicio"]?>" 
                        id="valor<?=$bus_articulos_compras["idarticulos_compra_servicio"]?>"
                        value="<?=$bus_articulos_compras["exento"]?>"
                        style="text-align:right">
				<?
			}else{
				?>
				<input type="text" 
                		name="valor_mostrado<?=$bus_articulos_compras["idarticulos_compra_servicio"]?>" 
                        id="valor_mostrado<?=$bus_articulos_compras["idarticulos_compra_servicio"]?>"
                        value="<?=number_format($bus_articulos_compras["total"],2,',','.')?>"
                        style="text-align:right"
                        onblur="formatoNumero(this.name, 'valor<?=$bus_partidas["idarticulos_compra_servicio"]?>')">
               <input type="hidden" 
                		name="valor<?=$bus_articulos_compras["idarticulos_compra_servicio"]?>" 
                        id="valor<?=$bus_articulos_compras["idarticulos_compra_servicio"]?>"
                        value="<?=$bus_articulos_compras["total"]?>"
                        style="text-align:right">
				<?
			}
			?>
           
			</td>
            <td width="7%" align='center' class='Browse'>
           	  <img src="imagenes/refrescar.png" 
                	border="0" 
                    style="cursor:pointer" 
                    title="Actualizar Monto"
                    onclick="actualizarArticulos('<?=$bus_articulos_compras["idarticulos_compra_servicio"]?>', document.getElementById('valor<?=$bus_articulos_compras["idarticulos_compra_servicio"]?>').value)"></td>
      </tr>
          <?
          }
          ?>
</table>

	<br />
<br />

	<?
	
	$sql_partidas =  mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'")or die(mysql_error());
?>
<strong style="padding-left:50px">PARTIDAS</strong>
 	<table width="85%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
		<thead>
          <tr>
            <td class="Browse" colspan="4"><div align="center">Partida</div></td>
            <td width="32%" class="Browse"><div align="center">Descripci&oacute;n</div></td>
            <td width="17%" class="Browse"><div align="center">Disponible</div></td>
            <td width="27%" class="Browse"><div align="center">Monto a Comprometer</div></td>
            <td class="Browse" colspan="2"><div align="center">Acciones</div></td>
          </tr>
      </thead>
          <? 
          while($bus_partidas = mysql_fetch_array($sql_partidas)){
		  
		  $sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = ".$bus_partidas["idmaestro_presupuesto"]."");
		  $bus_maestro = mysql_fetch_array($sql_maestro);
			
          $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = ".$bus_maestro["idclasificador_presupuestario"]."");
		  $bus_clasificador = mysql_fetch_array($sql_clasificador);
		  ?>
           <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td width="2%" align='left' class='Browse'><?=$bus_clasificador["partida"]?></td>
            <td width="2%" align='left' class='Browse'><?=$bus_clasificador["generica"]?></td>
            <td width="4%" align='left' class='Browse'><?=$bus_clasificador["especifica"]?></td>
            <td width="4%" align='left' class='Browse'><?=$bus_clasificador["sub_especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["denominacion"]?></td>
			<td class='Browse' align="center"><?=number_format($bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"],2,',','.')?></td>
			<td class='Browse' align='center'>
           	  <input type="text" 
                		name="monto_mostrado<?=$bus_partidas["idmaestro_presupuesto"]?>" 
                        id="monto_mostrado<?=$bus_partidas["idmaestro_presupuesto"]?>"
                        value="<?=number_format($bus_partidas["monto"],2,',','.')?>"
                        style="text-align:right"
                        onblur="formatoNumero(this.name, 'monto<?=$bus_partidas["idmaestro_presupuesto"]?>')">
               <input type="hidden" 
                		name="monto<?=$bus_partidas["idmaestro_presupuesto"]?>" 
                        id="monto<?=$bus_partidas["idmaestro_presupuesto"]?>"
                        value="<?=$bus_partidas["monto"]?>"
                        style="text-align:right">
			</td>
            <td width="6%" align='center' class='Browse'>
           	  <img src="imagenes/refrescar.png" 
                	border="0" 
                    style="cursor:pointer" 
                    title="Actualizar Monto"
                    onclick="actualizarPartidas('<?=$bus_partidas["idpartidas_orden_compra_servicio"]?>', document.getElementById('monto<?=$bus_partidas["idmaestro_presupuesto"]?>').value)"></td>
            <td width="6%" align='center' class='Browse'>
            <img src="imagenes/rewind.png" 
            	border="0" 
                style="cursor:pointer" 
                title="Volver a Montos Originales"
                onclick="document.getElementById('monto_mostrado<?=$bus_partidas["idmaestro_presupuesto"]?>').value = '<?=number_format($bus_partidas["monto_original"],2,',','.')?>',
                		document.getElementById('monto<?=$bus_partidas["idmaestro_presupuesto"]?>').value = '<?=$bus_partidas["monto_original"]?>'"></td>
      </tr>
          <?
          }
          ?>
</table>
        <?												
}











if($ejecutar == "actualizarPartidas"){
	$sql_partida = mysql_query("select * from partidas_orden_compra_servicio where idpartidas_orden_compra_servicio = '".$idpartida."'");
	$bus_partida = mysql_fetch_array($sql_partida);
	$sql_actualizar_partida = mysql_query("update partidas_orden_compra_servicio set monto = '".$monto_actualizar."'
																				where idpartidas_orden_compra_servicio = '".$idpartida."'")or die(mysql_error());
	
	$sql_actualizar_maestro = mysql_query("update maestro_presupuesto set 
			total_compromisos = (total_causados + '".$bus_partida["monto_original"]."')-'".$bus_partida["monto"]."'
			where idRegistro = '".$bus_partida["idmaestro_presupuesto"]."'");
																				
	if($sql_actualizar_partida){
		if($sql_actualizar_maestro){
			echo "exito";
		}
	}
}



if($ejecutar == "actualizarArticulos"){
	$sql_consulta = mysql_query("select * from articulos_compra_servicio where idarticulos_compra_servicio = '".$idarticulo."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	
	if($bus_consulta["exento"] != 0){
		$sql_actualizar = mysql_query("update articulos_compra_servicio set exento = '".$valor."'
									  									where idarticulos_compra_servicio = '".$idarticulo."'");
	}else{
		$sql_actualizar = mysql_query("update articulos_compra_servicio set total = '".$valor."'
									  									where idarticulos_compra_servicio = '".$idarticulo."'");	
	}
	if($sql_actualizar){
		echo "exito";	
	}
}






if($ejecutar == "volverMontosOriginales"){
	/*$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'")or die(mysql_error());
	$bus_orden = mysql_fetch_array($sql_orden);
	//var_dump($bus_orden);
	
	$exento = $bus_orden["exento_original"];
	$sub_total = $bus_orden["sub_total_original"];
	
	$sql_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where idorden_compra_servicio = '".$id_orden_compra."'");
		while($bus_impuestos = mysql_fetch_array($sql_impuestos)){
			$impuesto += (($bus_impuestos["base_calculo_original"]*$bus_impuestos["porcentaje"])/100); 
		}
	$total = $exento + $sub_total + $impuesto;
	echo $exento."|.|".$sub_total."|.|".$impuesto."|.|".$total;*/
}
?>