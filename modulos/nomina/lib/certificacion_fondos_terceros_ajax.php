<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
include("funciones_conceptos.php");
Conectarse();
extract($_POST);


if($ejecutar == 'seleccionarPeriodo'){
	$sql_consultar_periodo = mysql_query("select * from 
										 			periodos_nomina pn,
													rango_periodo_nomina rpn
										 			where 
													pn.idtipo_nomina = '".$idtipo_nomina."'
													and pn.periodo_activo = 'si'
													and rpn.idperiodo_nomina = pn.idperiodos_nomina")or die(mysql_error());
	?>
	<select name="idperiodo" id="idperiodo">
    	<option value="0">.:: Seleccione ::.</option>
	<?
		while($bus_consultar_periodo= mysql_fetch_array($sql_consultar_periodo)){
			?>
			<option value="<?=$bus_consultar_periodo["idrango_periodo_nomina"]?>">
				<?
                $desde = explode(" ", $bus_consultar_periodo["desde"]);
                $hasta = explode(" ", $bus_consultar_periodo["hasta"]);
                echo $desde[0]." - ".$hasta[0];
                ?>
            </option>
			<?	
		}
	?>
	</select>
	<?
}










if($ejecutar == "crearCertificacion"){
		$sql_cargar_certificacion = mysql_query("insert into orden_compra_servicio(
													   tipo,
													   fecha_elaboracion,
													   idbeneficiarios,
													   idcategoria_programatica,
													   anio,
													   idfuente_financiamiento,
													   idtipo_presupuesto,
													   idordinal,
													   justificacion,
													   estado,
													   ubicacion,
													   status,
													   usuario,
													   fechayhora,
													   idtipo_nomina,
													   idperiodo,
													   idconcepto)
												VALUES
														('".$idtipo_documento."',
														'".date("Y-m-d")."',
														'".$idbeneficiario."',
														'".$idcategoria_programatica."',
														'".$_SESSION["anio_fiscal"]."',
														'2',
														'1',
														'0',
														'".$justificacion."',
														'elaboracion',
														'0',
														'a',
														'".$login."',
														'".$fh."',
														'".$idtipo_nomina."',
														'".$idperiodo."',
														'".$idconceptos."')")or die("ERROR CREANDO LA ORDEN DE COMPRA: ".mysql_error());
														
		$idorden_compra = mysql_insert_id();
		
		
		$sql_consultar_nomina = mysql_query("select * from generar_nomina where idtipo_nomina = '".$idtipo_nomina."'
																				and idperiodo = '".$idperiodo."'
																				and estado = 'procesado'")or die(mysql_error());
																				
																				
		$bus_consultar_nomina = mysql_fetch_array($sql_consultar_nomina);



		$sql_consultar_relacion_nomina = mysql_query("select *
														 	from relacion_generar_nomina 
														where idgenerar_nomina = '".$bus_consultar_nomina["idgenerar_nomina"]."'
														and idconcepto = '".$idconcepto."'
														group by idtrabajador");
											
		while($bus_consultar_relacion_nomina = mysql_fetch_array($sql_consultar_relacion_nomina)){
			if($bus_consultar_relacion_nomina["tabla"] == "conceptos_nomina"){
				
				$sql_conceptos_nomina = mysql_query("select * from conceptos_nomina 
									where idconceptos_nomina = '".$bus_consultar_relacion_nomina["idconcepto"]."'");
				$bus_conceptos_nomina = mysql_fetch_array($sql_conceptos_nomina);
				
			}else if($bus_consultar_relacion_nomina["tabla"] == "constantes_nomina"){
				
				$sql_conceptos_nomina = mysql_query("select * from constantes_nomina where idconstantes_nomina = '".$bus_consultar_relacion_nomina["idconcepto"]."'");
				$bus_conceptos_nomina = mysql_fetch_array($sql_conceptos_nomina);
				
			}
			
			
			$idclasificador_presupuestario = $bus_conceptos_nomina["idclasificador_presupuestario"];
				$idordinal = $bus_conceptos_nomina["idordinal"];
			
			
			
			
				    /*$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica 
												from 
											trabajador tr,
											niveles_organizacionales no
												where 
											tr.idtrabajador = '".$bus_consultar_relacion_nomina["idtrabajador"]."'
											and no.idniveles_organizacionales = tr.centro_costo")or die("eeeeee".mysql_error());
					$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
			$idcategoria_programatica = $bus_categoria_programatica["idcategoria_programatica"];
			*/
			
			
			
			$sql_articulos_servicios = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_conceptos_nomina["idarticulos_servicios"]."'");
			$bus_articulos_servicios = mysql_fetch_array($sql_articulos_servicios);
			
		
			
			
			$sql_busqueda = mysql_query("select * from articulos_compra_servicio 
									where 
									idarticulos_servicios = '".$bus_conceptos_nomina["idarticulos_servicios"]."'
									and idcategoria_programatica = '".$idcategoria_programatica."'
									and idorden_compra_servicio = '".$idorden_compra."'")or die("ertyuui".mysql_error());
				
					
			$num_busqueda = mysql_num_rows($sql_busqueda);
			//echo $nummmm."....";
			
			
			
			if($num_busqueda > 0){
			
				$bus_busqueda = mysql_fetch_array($sql_busqueda);
				
				actualizarPrecioCantidad($bus_conceptos_nomina["idarticulos_servicios"], $idorden_compra, $idcategoria_programatica, 1, $bus_consultar_relacion_nomina["total"], 2, 1,$idclasificador_presupuestario,$idordinal, $bus_busqueda["idarticulos_compra_servicio"], $bus_consultar_relacion_nomina["idtrabajador"]);
				
			}else{
			//echo "AQUI \n";
				$resultado = ingresarMaterial($bus_conceptos_nomina["idarticulos_servicios"], $idorden_compra, $idcategoria_programatica, 1, $bus_consultar_relacion_nomina["total"], 2, 1,$idclasificador_presupuestario, $idordinal);
				echo $resultado;	
			}
		}
		
		echo $idorden_compra;
		/////////////////////////////////////////////////////////////
}








if($ejecutar == "consultarPartidas"){

	
$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$idorden_compra."'");
$bus_orden = mysql_fetch_array($sql_orden);
//and idclasificador_presupuestario = ".$bus_orden["idclasificador_presupuestario"]." 

$sql_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = '".$idorden_compra."' order by idmaestro_presupuesto");
																		
$num_partidas = mysql_num_rows($sql_partidas);
if($num_partidas != 0){
	?>
        <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <td class="Browse"><div align="center">Categoria</div></td>
            <td class="Browse" colspan="4"><div align="center">Partida</div></td>
            <td class="Browse"><div align="center">Descripci&oacute;n</div></td>
            <td class="Browse"><div align="center">Disponible</div></td>
            <td class="Browse"><div align="center">Monto a Comprometer</div></td>
          </tr>
          </thead>
          <? 
          while($bus_partidas = mysql_fetch_array($sql_partidas)){
		  
		  $sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = ".$bus_partidas["idmaestro_presupuesto"]."");
		  $bus_maestro = mysql_fetch_array($sql_maestro);
		  
		  
		  
		  
          	if($bus_partidas["estado"] == "sobregiro"){
		  ?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
			}else if($bus_partidas["estado"] == "disponible"){
			?>
			
            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			<?
			}
			
			
          $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_maestro["idclasificador_presupuestario"]."'");
		  $bus_clasificador = mysql_fetch_array($sql_clasificador);
		  ?>
            
            
            
            <td class='Browse' align='left'>
			<?
            $sql_categoria_programatica = mysql_query("select * from categoria_programatica where idcategoria_programatica = '".$bus_maestro["idcategoria_programatica"]."'");
			$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
			echo $bus_categoria_programatica["codigo"];
			?>
            </td>
            <td class='Browse' align='left'><?=$bus_clasificador["partida"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["generica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["sub_especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["denominacion"]?></td>

    	      <td class='Browse' align="right"><?=number_format(consultarDisponibilidad($bus_maestro["idRegistro"]),2,',','.')?></td>
	          <td class='Browse' align='right'><?=number_format($bus_partidas["monto"],2,',','.')?></td>
          </tr>
          <?
          }
          ?>
        </table>																	
<?
    }else{
	echo "No hay Partidas Asociadas";
    }																		

}






if($ejecutar == "consultarConceptos"){
	
	
	
	$sql_articulos_orden_compra_servicio = mysql_query("select * from articulos_compra_servicio,  
																		unidad_medida, 
																		articulos_servicios, 
																		categoria_programatica
									 where 
									 	articulos_compra_servicio.idorden_compra_servicio = '".$idorden_compra."'
									  	and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios 
										and articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida
										and categoria_programatica.idcategoria_programatica = articulos_compra_servicio.idcategoria_programatica 
										order by articulos_servicios.tipo_concepto, 
										categoria_programatica.codigo,
										articulos_compra_servicio.idarticulos_compra_servicio")or die(mysql_error());
	
	$num = mysql_num_rows($sql_articulos_orden_compra_servicio);
	
	
	
	
	
	?>
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <?
            if($bus_orden["duplicados"] == 1){
			?>
			<td class="Browse"><div align="center">Duplicados</div></td>
			<?
			}
			?>
            <td class="Browse"><div align="center">Categoria</div></td>
            <td class="Browse"><div align="center">Tipo</div></td>
            <td class="Browse"><div align="center">Descripcion</div></td>
            <td class="Browse"><div align="center">Monto</div></td>
			<?
            if($bus_orden["estado"] == "elaboracion"){
            ?>
            <td class="Browse" colspan="2"><div align="center">Acciones</div></td>
			<?
            }
            ?>
          </tr>
          </thead>
          <? 
		  
          while($bus_articulos_orden_compra_servicio = mysql_fetch_array($sql_articulos_orden_compra_servicio)){
			
            if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 1){
				$color = "#9CF";
				$tipo_concepto =  "Asignacion";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 2){
				$tipo_concepto =  "Deduccion";
				$color = "#FFC";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 4){
				$tipo_concepto =  "Aporte Patronal";
			}else{
				$tipo_concepto = "Neutro";	
			}	
          	if($bus_articulos_orden_compra_servicio["estado"] == "rechazado"){
			?>
			<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
			<?
			}else if($bus_articulos_orden_compra_servicio["estado"] == "sin disponibilidad"){
			?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
			}else{
			?>
			<tr style="background-color:<?=$color?>" onMouseOver="setRowColor(this, 0, 'over', '<?=$color?>', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '<?=$color?>', '#EAFFEA', '#FFFFAA')">
			<?
			
			}
		  ?>
          <?
          if($bus_orden["duplicados"] == 1){
			  if($bus_articulos_orden_compra_servicio["duplicado"] == 1){
			  ?>
				<td class='Browse' align='center'><img src="imagenes/advertencia.png" title="Articulo Duplicado"></td>
			   <?
			   }else{
			   	?>
				<td class='Browse' align='center'>&nbsp;</td>
			   <?
			   }
		   }
		   ?>
           <td class='Browse' align='left'><?=$bus_articulos_orden_compra_servicio["codigo"]?></td>
            <td class='Browse' align='left'>
			<?=$tipo_concepto?>
            </td>
             <td class='Browse' align='left'><?=$bus_articulos_orden_compra_servicio[30]?> 
              <div align="right"></div></td>
            
            
          <td class="Browse" align="right">
				<? 
				echo number_format($bus_articulos_orden_compra_servicio["precio_unitario"],2,',','.');
                ?>             </td>
				<?
                if($bus_orden["estado"] == "elaboracion"){
				?>
            <td class='Browse' align="center">
			<a href="javascript:;" onclick=""><a href="javascript:;" onclick=""><img src="imagenes/refrescar.png" onclick=" 
                                actualizarPrecioCantidad(<?=$bus_articulos_orden_compra_servicio["idorden_compra_servicio"]?>, 
                                document.getElementById('precio<?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>').value,
                                document.getElementById('cantidad<?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>').value, 
                                <?=$bus_articulos_orden_compra_servicio["idarticulos_servicios"]?>, 
                                <?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>, 
                                document.getElementById('anio').value,
                                document.getElementById('fuente_financiamiento').value,
                                document.getElementById('tipo_presupuesto').value,
                                document.getElementById('id_ordinal').value,
                                document.getElementById('contribuyente_ordinario').value)" 
                                title="Actualizar Precio y Cantidad" /></a></td>  
<td class='Browse' align="center">
                    <a href="javascript:;" onClick="eliminarMateriales(<?=$bus_articulos_orden_compra_servicio["idorden_compra_servicio"]?>, <?=$bus_articulos_orden_compra_servicio["idarticulos_compra_servicio"]?>, <?=$bus_articulos_orden_compra_servicio["idsolicitud_cotizacion"]?>, document.getElementById('id_categoria_programatica').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value, document.getElementById('contribuyente_ordinario').value)">
           			<img src="imagenes/delete.png" title="Eliminar Materiales">           		</a>            </td>
              <?
                }
				?>
          </tr>
          <?
          }
		  
		  
          ?>
        </table>
        <?	
}

























function ingresarMaterial($id_material, $id_orden_compra, $id_categoria_programatica, $cantidad, $precio_unitario, $fuente_financiamiento, $tipo_presupuesto,$id_clasificador_presupuestario, $idordinal){
	
	//echo "ENTRO EN LA FUNCION";
	$sql = mysql_query("select * from articulos_compra_servicio where 
													idarticulos_servicios = ".$id_material." 
													and idorden_compra_servicio = ".$id_orden_compra." 
													and idcategoria_programatica = '".$id_categoria_programatica."'")or die("TTTTTT".mysql_error());
	
	$sql_articulos_servicios = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id_material."'");
	
	$bus_articulos_servicios = mysql_fetch_array($sql_articulos_servicios);
	//$idordinal = $bus_articulos_servicios["idordinal"];
	
	
	//$id_clasificador_presupuestario = $bus_articulos_servicios["idclasificador_presupuestario"];
	$num = mysql_num_rows($sql);
	
	// SI EL ARTICULO NO EXISTE ENTRE LOS ARTICULOS CARGADOS
	if($num == 0){
	//echo "NO EXISTE";

		if($cantidad != 0){
			$total_articulo_individual = $cantidad * $precio_unitario;
		}else{
			$total_articulo_individual = $precio_unitario;
		}
		$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
		$bus_orden = mysql_fetch_array($sql_orden);
		
		//BUSCO EL IMPUESTO QUE SE LE APLICA AL ARTICULO PARA SABER SI TIENE PARTIDA PROPIA O SE VA A CARGAR A LA PARTIDA DEL ARTICULO
	

		// AGREGO EL ARTICULO DE LA SOLICITUD DE COTIZACION A LA ORDEN DE COMPRA
		

			$monto_total = $total_articulo_individual;
			$exento = 0; 

		
		//echo "MONTO TOTAL: ".$monto_total;
		//echo "EXENTO: ".$exento;
		
		
		if($total_articulo_individual != 0){
		//echo "AQUI";
		
		$sql = mysql_query("insert into articulos_compra_servicio (idorden_compra_servicio,
																	idarticulos_servicios,
																	idcategoria_programatica,
																	cantidad,
																	precio_unitario,
																	porcentaje_impuesto,
																	impuesto,
																	total,
																	exento,
																	status,
																	usuario,
																	fechayhora,
																	idpartida_impuesto)values(
																	'".$id_orden_compra."',
																	'".$id_material."',
																	'".$id_categoria_programatica."',
																	'".$cantidad."',
																	'".$precio_unitario."',
																	'".$porcentaje_impuesto."',
																	'".$total_impuesto_individual."',
																	'".$monto_total."',
																	'".$exento."',																	
																	'a',
																	'".$login."',
																	'".date("Y-m-d H:i:s")."',
																	'".$id_partida_impuesto."'
																	)")or die("AQUIIIIIIII ".mysql_error());

		$id_ultimo_generado = mysql_insert_id(); 
		
		
			// OBTENGO EL ULTIMO ID INGRESADO EN LA TABLA DE ARTICULOS PARA ACTUALIZARLE EL ESTADO DESPUES DE ANALIZAR LA
													//DISPONIBILIDAD DE LAS PARTIDAS
		$total_general =  $monto_total - $exento;
		$actualiza_totales = mysql_query("update orden_compra_servicio set 	
											sub_total = sub_total + '".$monto_total."',
											exento = exento + '".$exento."',
											total = total + '".$total_general."'
											where idorden_compra_servicio=".$id_orden_compra." ")or die ("11111111 ".mysql_error());
		}
		
		
	
		$sql_articulos = mysql_query("select * from articulos_servicios where idarticulos_servicios = ".$id_material."");
		$bus_articulos = mysql_fetch_array($sql_articulos);
		// 	se realiza la consulta en la tabla maestro para verificar si hay partidas para este articulo, de lo contrario el articulo se coloca en un estado de rechazado para que
		//	en la tabla aparesca la fila en rojo y muestre al usuario que no hay partidas para ese articulo
	if($bus_articulos_servicios["tipo_concepto"] == 4){	
		
		//echo "AQUI";
		
		
		$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = '".$_SESSION["anio_fiscal"]."' 
														and idcategoria_programatica = '".$id_categoria_programatica."' 
														and idclasificador_presupuestario = '".$id_clasificador_presupuestario."'
														and idfuente_financiamiento = '".$fuente_financiamiento."'
														and idtipo_presupuesto = '".$tipo_presupuesto."'
														and idordinal = '".$idordinal."'"
																		)or die($anio."ERROR SQL MAESTRO: ".mysql_error());

		
		$num_maestro = mysql_num_rows($sql_maestro);
			
			if($num_maestro == 0){ // VALIDO QUE EXISTA UNA PARTIDA EN EL MAESTRO DE PRESUPUESTO PARA ESE ARTICULO
				$estado = "rechazado";  // si no tiene partida en el maestro de presupuesto le coloca RECHAZADO para pintarlo de color ROJO
			}else{
				$bus_maestro = mysql_fetch_array($sql_maestro);
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				//echo $bus_maestro["idRegistro"];
				// de lo contrario verifica si en la partida que esta asignada este articulo hay disponibilidad de dinero para comprar la cantidad que se esta pidiendo, si para 
				// esta partida no hay disponibilidad se coloca el estado en SIN DISPONIBILIDAD para que al mostrar el articulo en color en AMARILLO para indicar que no 
				// hay presupuesto para este articulo
				
				$sql_imputable = mysql_query("select (precio_unitario) as total from articulos_compra_servicio where idarticulos_compra_servicio = '".$id_ultimo_generado."'");
				// SUMO EL TOTAL DE TODOS LOS ARTICULOS QUE ESTAN IMPUTANDO ESA PARTIDA PARA COMPARARLO CON EL DISPONIBLE EN EL MAESTRO DE PRESUPUESTO
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["total"]; 
				
				if($total_imputable > $disponible){ // si el total a imputar es mayor al disponible en la partida
					//echo "ESTA SOBREGIRADOOOOOOOOO";
					$estado = "sin disponibilidad";
					$estado_partida = "sobregiro";
				}else{
					//si nada de esto sucede se coloca el estado en aprobado y el material se muestra normalmente
					$estado = "aprobado";
					$estado_partida = "disponible";
				}
				/*echo "select * from partidas_orden_compra_servicio where idorden_compra_servicio=".$id_orden_compra." 
																		and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."";*/
					
				$sql_partidas_orden_compra=mysql_query("select * from partidas_orden_compra_servicio where 
																		idorden_compra_servicio='".$id_orden_compra."' 
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'") 
																	or die("66666 ".mysql_error());
				$num=mysql_num_rows($sql_partidas_orden_compra);
				
				if ($num==0){ // SI NO EXISTE LA PARTIDA LA INGRESO
					
					//echo "INGRESANDO PARTIDA ...... ";
					if($total_imputable != 0){
					//echo "ENTRO AQUI";
					$ingresar_partida=mysql_query("insert into partidas_orden_compra_servicio (idorden_compra_servicio, 
																								idmaestro_presupuesto,
																								monto,
																								monto_original,
																								estado,
																								status,
																								usuario,
																								fechayhora) 
																							values (".$id_orden_compra.",
																									".$bus_maestro["idRegistro"].",
																									".$precio_unitario.",
																									".$precio_unitario.",
																									'".$estado_partida."',
																									'a',
																									'".$login."',
																									'".date("Y-m-d H:i:s")."')")
																								or die("ERROR GUARDANDO PARTIDAS:". mysql_error());
					}
				}else{ // DE LO CONTRARIO LA ACTUALIZO
					//echo "AQUI";
					$actualiza_partida=mysql_query("update partidas_orden_compra_servicio set 
																		monto = monto + '".$precio_unitario."',
																		monto_original = monto_original + '".$precio_unitario."',
																		estado='".$estado_partida."' 
																		where idorden_compra_servicio='".$id_orden_compra."' 
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")
																		or die ($total_item."ERROR MODIFICANDO PARTIDAS: ".mysql_error());
				}														
	
			}
		}else{ // SI ES DEDUCCION ******************************************************************
			$estado = "disponible";
		}
			// actualizo el estado del material ingresado				
			$sql_update_articulos_compras = mysql_query("update articulos_compra_servicio set estado = '".$estado."' 
																where idarticulos_compra_servicio = ".$id_ultimo_generado."");



		
		if($sql){
		registra_transaccion("Ingresar Material Individual en Orden de Compra (".$id_ultimo_generado.")",$login,$fh,$pc,'orden_compra_servicios');
			//echo "exito";
		}else{
			//echo "fallo";
		}
}else{
//echo "existe";
}
	
}










function actualizarPrecioCantidad($id_articulo, $id_orden_compra, $id_categoria_programatica, $cantidad, $precio, $fuente_financiamiento, $tipo_presupuesto,$id_clasificador_presupuestario, $idordinal, $id_articulo_compra, $idtrabajador){

				//echo $precio."........";
				
				
				$sql_actualizar = mysql_query("update articulos_compra_servicio set
											  				total = total + ".$precio.",
															precio_unitario = precio_unitario + ".$precio."
											  				where 
															idarticulos_compra_servicio = '".$id_articulo_compra."'
															and idorden_compra_servicio = '".$id_orden_compra."'")or die("ALLA".mysql_error());
				
				
				
				
				
				$sql_articulos_servicios = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id_articulo."'")or die("XXXX".mysql_error());
				$bus_articulos_servicios = mysql_fetch_array($sql_articulos_servicios);
				
				//$idordinal = $bus_articulos_servicios["idordinal"];
				
				
				$sql_articulos_compra_servicio = mysql_query("select * from articulos_compra_servicio where idarticulos_compra_servicio = '".$id_articulo_compra."'")or die("FFFF".mysql_error());
				$bus_articulos_compra_servicio = mysql_fetch_array($sql_articulos_compra_servicio);
				
				$id_categoria_programatica = $id_categoria_programatica;
				//$id_clasificador_presupuestario = $bus_articulos_servicios["idclasificador_presupuestario"];
			//echo $id_clasificador_presupuestario;
			
			if($cantidad != 0){
				$total_articulo_individual = $cantidad * $precio;	
			}else{
				$total_articulo_individual = $precio;	
			}
			
//			echo "TOTAL ARTICULO INDIVIDUAL: ".$total_articulo_individual;
			
			//echo "TIPO CONCEPTO: ".$bus_articulos_servicios["tipo_concepto"]."<br />";

				$monto_total = $total_articulo_individual;
				$exento = 0; 

			
			// busco el precio y la cantidad anteriores para restarsela a los totales
			$sql_orden_compra_viejo = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'")or die("TTTTT".mysql_error());
			$bus_orden_compra_viejo = mysql_fetch_array($sql_orden_compra_viejo);
		
			$exento_viejo = $bus_orden_compra_viejo["exento"];
			$sub_total_viejo = $bus_orden_compra_viejo["sub_total"];
			
			
			
			// actualizo la tabla de articulos de la orden de compra con la nueva cantidad y el nuevo precio										

			
		
			
			// ACTUALIZO LOS TOTALES EN LA TABLA ORDEN_COMPRA_SERVICIO
			$total_anterior = $sub_total_viejo - $exento_viejo;
			$total_nuevo = $monto_total - $exento;
			$sql_actualiza_totales = mysql_query("update orden_compra_servicio set 
														sub_total = sub_total  + '".$monto_total."',
														exento = exento  + '".$exento."',
														total = total + '".$total_nuevo."'
														where idorden_compra_servicio=".$id_orden_compra." ")or die("4: ".mysql_error());
			
			
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
			
			
			$sql = mysql_query("select * from articulos_compra_servicio where 
													idarticulos_compra_servicio = '".$id_articulo_compra."'")or die("5: ".mysql_error());
			$bus = mysql_fetch_array($sql);
			
			if($bus["estado"] == "aprobado" || $bus["estado"] == "sin disponibilidad"){ // en cualquiera de stos estados el articulo tiene partida en el maestro
					$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where 
														idorden_compra_servicio = '".$id_orden_compra."'")or die("6: ".mysql_error());
					$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
					//echo "ID: ".$bus_compra_servicio["idcategoria_programatica"]." ";

					$partida_impuestos = 0;


	
				$sql_imputable = mysql_query("select precio_unitario from articulos_compra_servicio where idarticulos_compra_servicio = '".$id_articulo_compra."'")or die("12: ".mysql_error());
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$suma_partida = $bus_imputable["precio_unitario"];
				//echo "TOTAL: ".$total_imputable. "ID ";
				//echo $bus_imputable["totales"];
				//echo $bus_imputable["exentos"];


	if($bus_articulos_servicios["tipo_concepto"] == 4){

	
				$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = ".$_SESSION["anio_fiscal"]." 
												and idcategoria_programatica = '".$id_categoria_programatica."' 
												and idclasificador_presupuestario = '".$id_clasificador_presupuestario."'
												and idfuente_financiamiento = '".$fuente_financiamiento."'
												and idtipo_presupuesto = '".$tipo_presupuesto."'
												and idordinal = '".$idordinal."'")or die("8: ".mysql_error());

				
				$bus_maestro = mysql_fetch_array($sql_maestro);
				
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				$sql_consultar_existe = mysql_query("select * from partidas_orden_compra_servicio where idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."' and idorden_compra_servicio = '".$id_orden_compra."'");
				$num_consultar_existe = mysql_num_rows($sql_consultar_existe);
				
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				
				
				if($num_consultar_existe > 0){
					if($suma_partida > $disponible){
						$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
																monto = monto + '".$precio."',
																monto_original = monto_original + '".$precio."' 
																where 
																idorden_compra_servicio = '".$id_orden_compra."'
																and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("15: ".mysql_error());
						$estado = "sin disponibilidad";
					}else{
						//echo "PRUEBA: ".$bus_maestro["idRegistro"]." ------ ";
						$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
														monto = monto + '".$precio."',
														monto_original = monto_original + '".$precio."' 
														where 
														idorden_compra_servicio = ".$id_orden_compra."
														and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("16: ".mysql_error());
					
					
						$estado = "aprobado";
					}
				}else{
					if($suma_partida > $disponible){
						$es = "sobregiro";	
						$estado = "sin disponibilidad";
					}else{
						$es = "disponible";
						$estado = "aprobado";
					}	

						if($total_articulo_individual != 0){
						//echo "entro aqui";
							$sql_partida = mysql_query("insert into partidas_orden_compra_servicio 
																			(estado, 
																				monto, 
																				monto_original,
																				idmaestro_presupuesto,
																				idorden_compra_servicio
																				)VALUES(
																			'".$es."', 
																			'".$precio."',
																			'".$precio."',
																			'".$bus_maestro["idRegistro"]."',
																			'".$id_orden_compra."')")or die("15: ".mysql_error());		
						}

				}
				
			}else{
				//echo "ALLA";
				$estado = "aprobado";
			}
		}else{
			$estado = "aprobado";
		}
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA		
			
			$sql2 = mysql_query("update articulos_compra_servicio set estado = '".$estado."' 
																where idarticulos_compra_servicio = '".$id_articulo_compra."'")or die("17: ".mysql_error());
			
		if($sql2){
				registra_transaccion("Actualizar Precio Cantidad de Orden de Compra (".$id_articulo_compra.")",$login,$fh,$pc,'orden_compra_servicios');

				//echo "exito";
		}else{
				registra_transaccion("Actualizar Precio Cantidd de Orden de Compra ERROR (".$id_articulo_compra.")",$login,$fh,$pc,'orden_compra_servicios');

				//echo $sql2." MYSQL ERROR: ".mysql_error();
		}
	
}









if($ejecutar=="procesarCertificacion"){
	$result = procesarCertificacion($idorden_compra);
	echo $result;
}






function procesarCertificacion($id_orden_compra){

	$idfuente_financiamiento = 2;
	$idtipo_presupuesto = 1;


	$sql_articulos_compra_servicio = mysql_query("select * from articulos_compra_servicio 
						where idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR EN LA CONSULTA A LOS ARTICULOS: ".mysql_error());
	$sql_compra_servicio = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die("SELECT DE L ORDEN DE COMPRA: ".mysql_error());
	$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
	
	while($bus_articulos_compra_servicio = mysql_fetch_array($sql_articulos_compra_servicio)){
		$sql_ordinal = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_articulos_compra_servicio["idarticulos_servicios"]."'");
		$bus_ordinal = mysql_fetch_array($sql_ordinal);
		$idordinal = $bus_ordinal["idordinal"];
			
	
	//*************************************************************************************
	$sql_articulos_servicios = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_articulos_compra_servicio["idarticulos_servicios"]."'");
	$bus_articulos_servicios =  mysql_fetch_array($sql_articulos_servicios);
	
	$id_clasificador_presupuestario = $bus_articulos_servicios["idclasificador_presupuestario"];
	$id_categoria_programatica = $bus_articulos_compra_servicio["idcategoria_programatica"];
	
	$total_articulo_individual = $bus_articulos_compra_servicio["cantidad"] * $bus_articulos_compra_servicio["precio_unitario"];
		

			$monto_total = $total_articulo_individual;
			$exento = 0; 

	
	
	
	
	
													
			$sql2 = mysql_query("update articulos_compra_servicio set total = '".$monto_total."', 
																	  exento = '".$exento."',
																	  precio_unitario = '".$bus_articulos_compra_servicio["precio_unitario"]."', 
																	  cantidad = ".$bus_articulos_compra_servicio["cantidad"]."
																	  where 
																	  idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")or die("error update articulos".mysql_error());
		
		
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
			$sql = mysql_query("select * from articulos_compra_servicio 
														where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]." 
															")or die("error todos los articulos".mysql_error());
			$bus = mysql_fetch_array($sql);
			
		if($bus_articulos_servicios["tipo_concepto"] == 4){	
			//echo "ESTADO: ".$bus["estado"];
			if($bus["estado"] == "aprobado" || $bus["estado"] == "sin disponibilidad"){
				
						
				//echo "TOTAL: ".$total_articulo_individual;
				
				$total_imputable = $total_articulo_individual;				
				$total_imputable = $total_imputable+$total_impuesto;
//*********************************************************************************				
				
				
				$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = '".$_SESSION["anio_fiscal"]."' 
												and idcategoria_programatica = '".$id_categoria_programatica."' 
												and idclasificador_presupuestario = '".$id_clasificador_presupuestario."'
												and idfuente_financiamiento = '".$idfuente_financiamiento."'
												and idtipo_presupuesto = '".$idtipo_presupuesto."'
												and idordinal = '".$idordinal."'")or die("ERROR SELECCIONANDO EL MAESTRO PARA LOS IMPUESTOS: ".mysql_error());
				
				$bus_maestro = mysql_fetch_array($sql_maestro);
				
				//echo "*";
				//$disponible = $bus_maestro["monto_actual"] - $bus_maestro["total_compromisos"];
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				//echo "DISOPONIBLE: ".$disponible." ";
				//echo "ID MAESTRO PRESUPUESTO: ".$bus_maestro["idRegistro"]." ";
				//echo "Disponible: ".$bus_maestro["idRegistro"];
				if($total_imputable > $disponible){
				//echo "update partidas_orden_compra_servicio set estado = 'sobregiro', 
				//										monto = '".$total_imputable."' 
				//										where idorden_compra_servicio = ".$id_orden_compra."
				//										and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'";
														
					//$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'sobregiro', 
						//								monto = monto + '".$total_imputable."' 
							//							where idorden_compra_servicio = ".$id_orden_compra."
								//						and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("ERROR ACTUALIZANDO LAS PARTIDAS CON SOBREGIRO: ".mysql_error());
					$estado = "sin disponibilidad";
				}else{
				//echo "XXXXXXXXXXXXX ";
					/*$sql_partida = mysql_query("update partidas_orden_compra_servicio set estado = 'disponible', 
									monto = monto + '".$total_imputable."' 
									where idorden_compra_servicio = ".$id_orden_compra."
									and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."")or die("ERROR ACTUALIZANDO LAS PARTIDAS DISPONIBLES: ".mysql_error());*/
				
					$estado = "aprobado";
				}
			}else{
				$estado = "rechazado";

			}
		}else{
			$estado = "aprobado";
		}
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA		
				
				
				
			$sql2 = mysql_query("update articulos_compra_servicio set estado = '".$estado."' 
													where idarticulos_compra_servicio = ".$bus_articulos_compra_servicio["idarticulos_compra_servicio"]."")or die("ERRO ACTUALIZANDO EL ESTADO DE LOS ARTICULOS: ".mysql_error());
			
	
	//*****************************************************************************************
	}// CIERRE DEL WHILE DE CONSULTA DE LOS ARTICULOS DE LA ORDEN





	$sql_articulos = mysql_query("select * from articulos_compra_servicio 
												where idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR SELECCIONANDO LOS ARTICULOS: ".mysql_error());
	$num_articulos = mysql_num_rows($sql_articulos);
	
	if($num_articulos != 0){
		$sql_orden_duplicados = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."");
		$bus_orden_duplicados = mysql_fetch_array($sql_orden_duplicados);
			if($bus_orden_duplicados["duplicados"] == 0){
				$sql_articulos = mysql_query("select * from articulos_compra_servicio 
														where idorden_compra_servicio = ".$id_orden_compra." 
														and (estado = 'rechazado' or estado = 'sin disponibilidad')")or die("ERROR SELECCIONANDO ARTICULOS COMPRA SERVICIOS: ".mysql_error());
		$num_articulos = mysql_num_rows($sql_articulos);
		
		if($num_articulos == 0){
			$sql_impuestos = mysql_query("select * from relacion_impuestos_ordenes_compras where 
														idorden_compra_servicio = ".$id_orden_compra." 
														and (estado = 'rechazado' or estado = 'sin disponibilidad')")or die("ERROR SELECCIONANDO LA RELACION DE IMPUESTOS ORDENES COMPRAS: ".mysql_error());
			$num_impuestos = mysql_num_rows($sql_impuestos);
			
			if($num_impuestos == 0){
				$sql_actualizar_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR SELECCIONANDO LAS PARTIDAS DE LA ORDEN DE COMPRAS: ".mysql_error());
				while($bus_actualizar_partidas = mysql_fetch_array($sql_actualizar_partidas)){
				
					$sql_maestro = mysql_query("update maestro_presupuesto set 
															total_compromisos = total_compromisos + ".$bus_actualizar_partidas["monto"]."
															where idRegistro = ".$bus_actualizar_partidas["idmaestro_presupuesto"]."")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO: ".mysql_error());
															
					$sql_consulta_ordinal = mysql_query("select * from ordinal where codigo = '0000'")or die("ERROR CONSULTANDO EL ORDINAL NO APLICA".mysql_error());
					$bus_consulta_ordinal = mysql_fetch_array($sql_consulta_ordinal);
					
					$sql_consultar_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '".$bus_actualizar_partidas["idmaestro_presupuesto"]."' and idordinal != '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO EL MAESTRO 1:".mysql_error());
					$num_consulta_maestro = mysql_num_rows($sql_consultar_maestro);
					if($num_consulta_maestro != 0){
						$bus_consultar_maestro= mysql_fetch_array($sql_consultar_maestro);
						$sql_sub_espe = mysql_query("select * from maestro_presupuesto where 
							idcategoria_programatica= '".$bus_consultar_maestro["idcategoria_programatica"]."'
						and idtipo_presupuesto = '".$bus_consultar_maestro["idtipo_presupuesto"]."'
						and idfuente_financiamiento = '".$bus_consultar_maestro["idfuente_financiamiento"]."'
						and idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."'
						and idordinal = '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO SUB ESPECIFICA".mysql_error());    
						$num_sub_espe =mysql_num_rows($sql_sub_espe);
						if($num_sub_espe != 0){
							$bus_sub_epe = mysql_fetch_array($sql_sub_espe);
							$sql_maestro = mysql_query("update maestro_presupuesto set 
															total_compromisos = total_compromisos + ".$bus_actualizar_partidas["monto"]."
															where idRegistro = '".$bus_sub_epe["idmaestro_presupuesto"]."'")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 2: ".mysql_error());
							
						}
						
						$sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."' and sub_especifica != '00'")or die("ERROR CONSULTANDO EL CLASIFICADOR ".mysql_error());
						$num_clasificador = mysql_num_rows($sql_clasificador);
						if($num_clasificador > 0){
							$bus_clasificador = mysql_fetch_array($sql_clasificador);
							$sql_consulta_clasificador = mysql_query("select * from clasificador_presupuestario where partida = '".$bus_clasificador["partida"]."'
							and generica = '".$bus_clasificador["generica"]."'
							and especifica ='".$bus_clasificador["especifica"]."'
							and sub_especifica= '00'")or die("ERROR CONSULTANDO EL CLASIFICADOR 2:".mysql_error());
							$bus_consulta_clasificador= mysql_fetch_array($sql_consulta_clasificador);
							$sql_id_maestro= mysql_query("select * from maestro_presupuesto where 
							idcategoria_programatica= '".$bus_consultar_maestro["idcategoria_programatica"]."'
						and idtipo_presupuesto = '".$bus_consultar_maestro["idtipo_presupuesto"]."'
						and idfuente_financiamiento = '".$bus_consultar_maestro["idfuente_financiamiento"]."'
						and idclasificador_presupuestario = '".$bus_consulta_clasificador["idclasificador_presupuestario"]."'
						and idordinal = '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO EL MAESTRO 2:".mysql_error());
							$bus_id_maestro = mysql_fetch_array($sql_id_maestro);
							
							$sql_maestro = mysql_query("update maestro_presupuesto set 
															total_compromisos = total_compromisos + ".$bus_actualizar_partidas["monto"]."
															where idRegistro = ".$bus_id_maestro["idmaestro_presupuesto"]."")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 3: ".mysql_error());
							
						}
						
					}
					
					
					
					
					
					
					
					
				}
					
				$sql_orden = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die("ERROR SELECCIONANDO LA ORDEN DE COMPRA: ".mysql_error());
				$bus_orden = mysql_fetch_array($sql_orden);
				$tipo_orden = $bus_orden["tipo"];
			
				$sql_configuracion = mysql_query("select * from configuracion");
				$bus_configuracion = mysql_fetch_array($sql_configuracion);
				$anio_fiscal = $bus_configuracion["anio_fiscal"];
				
				
				$sql_nro_orden = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$tipo_orden."");
				$bus_nro_orden = mysql_fetch_array($sql_nro_orden);
				
				
				if($bus_nro_orden["documento_asociado"] != 0){
						$sql_documento_asociado = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$bus_nro_orden["documento_asociado"]."");
						$bus_documento_asociado = mysql_fetch_array($sql_documento_asociado);
						$id_a_actualizar = $bus_documento_asociado["idtipos_documentos"];
						$codigo_orden = $bus_documento_asociado["siglas"]."-".$anio_fiscal."-".$bus_documento_asociado["nro_contador"];
						$nro_orden_compra = $bus_documento_asociado["nro_contador"];
					}else{
						$id_a_actualizar = $tipo_orden;
						$codigo_orden = $bus_nro_orden["siglas"]."-".$anio_fiscal."-".$bus_nro_orden["nro_contador"];
						$nro_orden_compra = $bus_nro_orden["nro_contador"];
					}

					$sql_existe_numero = mysql_query("select * from orden_compra_servicio where numero_orden = '".$codigo_orden."'")or die("cero".mysql_error());
					$bus_existe = mysql_num_rows($sql_existe_numero);
					
					while ($bus_existe > 0){
						$sql_actualizar_numero = mysql_query("update tipos_documentos set nro_contador = nro_contador + 1 where idtipos_documentos = ".$id_a_actualizar."")or die("uno".mysql_error());
						

		
						$sql_nro_orden = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$tipo_orden."");
						$bus_nro_orden = mysql_fetch_array($sql_nro_orden);
							if($bus_nro_orden["documento_asociado"] != 0){
								$sql_documento_asociado = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$bus_nro_orden["documento_asociado"]."");
								$bus_documento_asociado = mysql_fetch_array($sql_documento_asociado);
								$id_a_actualizar = $bus_documento_asociado["idtipos_documentos"];
								$codigo_orden = $bus_documento_asociado["siglas"]."-".$anio_fiscal."-".$bus_documento_asociado["nro_contador"];
								$nro_orden_pago = $bus_documento_asociado["nro_contador"];
							}else{
								$id_a_actualizar = $tipo_orden;
								$codigo_orden = $bus_nro_orden["siglas"]."-".$anio_fiscal."-".$bus_nro_orden["nro_contador"];
								$nro_orden_pago = $bus_nro_orden["nro_contador"];
							}
						
						
						$sql_existe_numero = mysql_query("select * from orden_compra_servicio where numero_orden = '".$codigo_orden."'")or die("cero".mysql_error());
						$bus_existe = mysql_num_rows($sql_existe_numero);
					}
				
				
				
				// ACA SE GENERA EL NUMERO DE CONTROL DE LA ORDEN DE COMPRA


				$codigo_referencia = 90000000000+$nro_orden_compra;
				
				$sql_actualizar_orden = mysql_query("update orden_compra_servicio set estado = 'procesado', 
																						numero_orden = '".$codigo_orden."',
																						fecha_orden = '".date("Y-m-d")."',
																						codigo_referencia = '".$codigo_referencia."'
																					where idorden_compra_servicio = ".$id_orden_compra."")or die("error".mysql_error());
				
				
				
				
				
			//	echo "select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."";
				$sql_relacion_compra_requisicion = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."")or die("ERROR EN EL SELECT ".mysql_error());
				
				while ($bus_relacion_compra_requisicion = mysql_fetch_array($sql_relacion_compra_requisicion))
				{
				//echo "update requisicion set estado = 'ordenado' where idrequisicion = ".$bus_relacion_compra_requisicion["idrequisicion"]."";
					$sql_actualizar_requisicion = mysql_query("update requisicion set estado = 'ordenado' where idrequisicion = ".$bus_relacion_compra_requisicion["idrequisicion"]."")or die("ERROR EN EL UPDATE".mysql_error());
				}			
				
				
				
				$sql_relacion_compra_solicitud = mysql_query("select * from relacion_compra_solicitud_cotizacion where idorden_compra = ".$id_orden_compra."");
				
				while ($bus_relacion_compra_solicitud = mysql_fetch_array($sql_relacion_compra_solicitud))
				{
					$sql_actualizar_solicitud = mysql_query("update solicitud_cotizacion set estado = 'ordenado', 
																						nro_orden = '".$codigo_orden."' 
																					where idsolicitud_cotizacion = ".$bus_relacion_compra_solicitud["idsolicitud_cotizacion"]."");
				}
				
				$sql_relacion_compra_requisicion = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."");
				
				while ($bus_relacion_compra_requisicion = mysql_fetch_array($sql_relacion_compra_requisicion))
				{
					$sql_actualizar_requisicion = mysql_query("update requisicion set estado = 'ordenado', 
																						nro_orden = '".$codigo_orden."' 
																					where idrequisicion = ".$bus_relacion_compra_requisicion["idrequision"]."");
				}	
					
					
					
					
				// ACTUALIZAR EL ULTIMO COSTO DE LOS PRODUCTOS
				$sql_select_articulos_compra = mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
				while($bus_select_articulos_compra = mysql_fetch_array($sql_select_articulos_compra)){
					$sql_select_ultimo_costo = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$bus_select_articulos_compra["idarticulos_servicios"]."'");
					$bus_select_ultimo_costo = mysql_fetch_array($sql_select_ultimo_costo);
					$costo_actual = $bus_select_articulos_compra["precio_unitario"];
					$ultimo_costo = $bus_select_ultimo_costo["ultimo_costo"];
						if($bus_select_ultimo_costo["ultimo_costo"] == 0 || $bus_select_ultimo_costo["ultimo_costo"] == ""){
							$costo_promedio = $costo_actual;	
						}else{
							$costo_promedio = ($costo_actual+$ultimo_costo)/2;
						}
					
					$sql_actualizar_articulo = mysql_query("update articulos_servicios set ultimo_costo = '".$costo_actual."',
													costo_promedio = '".$costo_promedio."',
													fecha_ultima_compra = '".date("Y-m-d")."' 
													where idarticulos_servicios = '".$bus_select_articulos_compra["idarticulos_servicios"]."'");
				}
				
					
					
					
					
					
					
				if($sql_actualizar_orden){
					$sql_actualizar_numero = mysql_query("update tipos_documentos set nro_contador = nro_contador + 1 where idtipos_documentos = ".$tipo_orden."")or die("uno".mysql_error());
					return "exito|.|".$codigo_orden;
					registra_transaccion("Procesar Orden de Compra y Servicio (".$codigo_orden.", ID: ".$id_orden_compra.")",$login,$fh,$pc,'orden_compra_servicios');
				}else{
					return "fallo";
				}
			}else{
				return "falloImpuestos";
			}
		}else{
			return "falloMateriales";
		}
	  }else{
		return "duplicados";
	}
	}else{
		return "sinMateriales";
	}
	

}










if($ejecutar == "consultarCertificacion"){
	//echo $idorden_compra;

	$sql_consultar = mysql_query("select oc.tipo,
									oc.idtipo_nomina,
									oc.idperiodo,
									oc.idcategoria_programatica,
									oc.idconcepto,
									oc.idbeneficiarios,
									oc.justificacion,
									oc.numero_orden,
									oc.estado,
									cp.codigo,
									ue.denominacion,
									be.nombre,
									cn.descripcion
									  from
									orden_compra_servicio oc,
									categoria_programatica cp,
									unidad_ejecutora ue,
									beneficiarios be,
									conceptos_nomina cn
									  where
									oc.idorden_compra_servicio = '".$idorden_compra."' and
									oc.idcategoria_programatica = cp.idcategoria_programatica and
									oc.idbeneficiarios = be.idbeneficiarios and
									cp.idunidad_ejecutora = ue.idunidad_ejecutora")or die(mysql_error());
	$bus_consultar = mysql_fetch_array($sql_consultar);
	
	echo $bus_consultar["tipo"]."|.|".
	$bus_consultar["idtipo_nomina"]."|.|".
	$bus_consultar["idperiodo"]."|.|".
	$bus_consultar["denominacion"]."|.|".
	$bus_consultar["idcategoria_programatica"]."|.|".
	$bus_consultar["descripcion"]."|.|".
	$bus_consultar["idconcepto"]."|.|".
	$bus_consultar["nombre"]."|.|".
	$bus_consultar["idbeneficiarios"]."|.|".
	$bus_consultar["justificacion"]."|.|".
	
	$bus_consultar["numero_orden"]."|.|".
	$bus_consultar["estado"]."|.|".
	$bus_consultar["codigo"]."|.|";
	

}






if($ejecutar == "consultarTotal"){
	$sql_consulta = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio ='".$idorden_compra."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	echo number_format($bus_consulta["total"],2,",",".");
}




if($ejecutar == "anularCertificacion"){
	anularCertificacion($idorden_compra);
}



function anularCertificacion($id_orden_compra){

	
	$sql_orden = mysql_query("update orden_compra_servicio set estado = 'anulado' where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
	$sql_actualizar_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = ".$id_orden_compra."")or die(mysql_error());
		while($bus_actualizar_partidas = mysql_fetch_array($sql_actualizar_partidas)){
			$sql_maestro = mysql_query("update maestro_presupuesto set 
												total_compromisos = total_compromisos-".$bus_actualizar_partidas["monto"]."
												where idRegistro = ".$bus_actualizar_partidas["idmaestro_presupuesto"]."")or die(mysql_error());
					
					
					$sql_consulta_ordinal = mysql_query("select * from ordinal where codigo = '0000'")or die("ERROR CONSULTANDO EL ORDINAL NO APLICA".mysql_error());
					$bus_consulta_ordinal = mysql_fetch_array($sql_consulta_ordinal);
					
					$sql_consultar_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '".$bus_actualizar_partidas["idmaestro_presupuesto"]."' and idordinal != '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO EL MAESTRO 1:".mysql_error());
					$num_consulta_maestro = mysql_num_rows($sql_consultar_maestro);
					if($num_consulta_maestro != 0){
						$bus_consultar_maestro= mysql_fetch_array($sql_consultar_maestro);
						$sql_sub_espe = mysql_query("select * from maestro_presupuesto where 
							idcategoria_programatica= '".$bus_consultar_maestro["idcategoria_programatica"]."'
						and idtipo_presupuesto = '".$bus_consultar_maestro["idtipo_presupuesto"]."'
						and idfuente_financiamiento = '".$bus_consultar_maestro["idfuente_financiamiento"]."'
						and idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."'
						and idordinal = '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO SUB ESPECIFICA".mysql_error());    
						$num_sub_espe =mysql_num_rows($sql_sub_espe);
						if($num_sub_espe != 0){
							$bus_sub_epe = mysql_fetch_array($sql_sub_espe);
							$sql_maestro = mysql_query("update maestro_presupuesto set 
															total_compromisos = total_compromisos - ".$bus_actualizar_partidas["monto"]."
															where idRegistro = '".$bus_sub_epe["idmaestro_presupuesto"]."'")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 2: ".mysql_error());
							
						}
						
						$sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."' and sub_especifica != '00'")or die("ERROR CONSULTANDO EL CLASIFICADOR ".mysql_error());
						$num_clasificador = mysql_num_rows($sql_clasificador);
						if($num_clasificador > 0){
							$bus_clasificador = mysql_fetch_array($sql_clasificador);
							$sql_consulta_clasificador = mysql_query("select * from clasificador_presupuestario where partida = '".$bus_clasificador["partida"]."'
							and generica = '".$bus_clasificador["generica"]."'
							and especifica ='".$bus_clasificador["especifica"]."'
							and sub_especifica= '00'")or die("ERROR CONSULTANDO EL CLASIFICADOR 2:".mysql_error());
							$bus_consulta_clasificador= mysql_fetch_array($sql_consulta_clasificador);
							$sql_id_maestro= mysql_query("select * from maestro_presupuesto where 
							idcategoria_programatica= '".$bus_consultar_maestro["idcategoria_programatica"]."'
						and idtipo_presupuesto = '".$bus_consultar_maestro["idtipo_presupuesto"]."'
						and idfuente_financiamiento = '".$bus_consultar_maestro["idfuente_financiamiento"]."'
						and idclasificador_presupuestario = '".$bus_consulta_clasificador["idclasificador_presupuestario"]."'
						and idordinal = '".$bus_consulta_ordinal["idordinal"]."'")or die("ERROR CONSULTANDO EL MAESTRO 2:".mysql_error());
							$bus_id_maestro = mysql_fetch_array($sql_id_maestro);
							

							$sql_maestro = mysql_query("update maestro_presupuesto set 
															total_compromisos = total_compromisos - ".$bus_actualizar_partidas["monto"]."
															where idRegistro = ".$bus_id_maestro["idmaestro_presupuesto"]."")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO 3: ".mysql_error());
							
						}
						
					}
			
		}
	
	$sql_relacion_requisicion = mysql_query("select * from relacion_compra_requisicion where idorden_compra = ".$id_orden_compra."");
	while($bus_relacion_requisicion = mysql_fetch_array($sql_relacion_requisicion)){
		$sql_insert_relacion_compras = mysql_query("update requisicion set estado = 'procesado' where idrequisicion = '".$bus_relacion_requisicion["idrequisicion"]."'");

	}
	
	$sql_relacion_solicitud = mysql_query("select * from relacion_compra_solicitud_cotizacion where idorden_compra = ".$id_orden_compra."");
	while($bus_relacion_solicitud = mysql_fetch_array($sql_relacion_solicitud)){
		$sql_insert_relacion_compras = mysql_query("update solicitud_cotizacion set estado = 'procesado' where idsolicitud_cotizacion = '".$bus_relacion_solicitud["idsolicitud_cotizacion"]."'");

	}
	
	
	if($sql_orden){
		echo "exito";
		registra_transaccion("Anular Certificacion de Fondos a Terceros(".$id_orden_compra.")",$login,$fh,$pc,'orden_compra_servicios');
	}else{
		registra_transaccion("Anular Certificacion de Fondos a Terceros ERROR (".$id_orden_compra.")",$login,$fh,$pc,'orden_compra_servicios');
		echo "fallo";
	}


	
	

}

?>