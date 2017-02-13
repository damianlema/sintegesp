<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);


switch($ejecutar){
	case "mostrarConceptos":
		$sql_conceptos = mysql_query("select 
											c.idconcepto_tributario,
											c.codigo,
											c.denominacion,
											csc.tipo_cobro as tipo_base_aforo,
											csc.tipo_calculo as tipo_calculo_aforo,
											csc.valor as valor_aforo,
											c.valor_aforo as alicuota,
											csc.total_pagar,
											csc.idconceptos_solicitud_calculo,
											csc.fraccion_pago
												from 
											concepto_tributario c,
											conceptos_solicitud_calculo csc
										 		where 
											csc.idsolicitud_calculo = '".$idsolicitud_calculo."'
											and c.idconcepto_tributario = csc.idconcepto")or die(mysql_error());
	
	$sql_solicitud_calculo = mysql_query("select * from solicitud_calculo where idsolicitud_calculo = '".$idsolicitud_calculo."'");
	$bus_solicitud_calculo = mysql_fetch_array($sql_solicitud_calculo);
	?>
	
    <table align="center">
        <tr>
        	<td colspan="4" align="center"><strong>Agregar Nuevo Concepto</strong></td>
        </tr>
        <tr>
        	<td>
            <?
                if($bus_solicitud_calculo["estado"] == 'elaboracion'){
					?>
            <img src="imagenes/search0.png" onClick="window.open('lib/listas/listar_conceptos_tributarios.php','','width=900, height=600')" style="cursor:pointer; display:block" id="boton_buscar_conceptos">
            <?
            }
			?>
          </td>
            <td>
            <input type="hidden" id="idconcepto_tributario_agregar" name="idconcepto_tributario_agregar">
            <input type="text" id="codigo_concepto" name="codigo_concepto" readonly></td>
            <td><input type="text" id="denominacion_concepto" name="denominacion_concepto" size="90" readonly></td>
            <td>
            <?
                if($bus_solicitud_calculo["estado"] == 'elaboracion'){
					?>
            <img src="imagenes/validar.png" onClick="ingresarConcepto()" style="cursor:pointer; display:block" id="boton_agregar_conceptos">
            <?
            }
			?>
          </td>
        </tr>
    </table>
    <br>
    <h2 class="sqlmVersion"></h2>
    <br>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
        <thead>
          <tr>
            <td width="31%" class="Browse"><div align="center">Concepto</div></td>
            <td width="14%" class="Browse"><div align="center">Tipo Base</div></td>
            <td width="10%" class="Browse"><div align="center">Monto Variable</div></td>
            <td width="10%" class="Browse"><div align="center">Alicuota</div></td>
            <td width="9%" class="Browse"><div align="center">Total</div></td>
            <td width="12%" class="Browse"><div align="center">Fraccion de Pago</div></td>
            <td width="14%" class="Browse"><div align="center">Eliminar</div></td>
          </tr>
          </thead>
		<?
		while($bus_conceptos = mysql_fetch_array($sql_conceptos)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td class='Browse' align='left' style="padding:3px">&nbsp;(<?=$bus_conceptos["codigo"]?>)&nbsp;<?=$bus_conceptos["denominacion"]?></td>
                <td align='center' bgcolor="#e7dfce" class='Browse' style="padding:3px">
				<?
                if($bus_conceptos["tipo_base_aforo"] == "unidad_tributaria"){
					echo "Unidad Tributaria";
				}else if($bus_conceptos["tipo_base_aforo"] == "monto_variable"){
					echo "Monto Variable";
				}else{
					echo "Capital Social";
				}
				?></td>
              <td class='Browse' align='right' style="padding:3px">
			  <?
              if($bus_conceptos["tipo_base_aforo"] == "monto_variable" and $bus_solicitud_calculo["estado"] == 'elaboracion'){
			  	?>
				<input type="text" name="monto_variable_<?=$bus_conceptos["idconcepto_tributario"]?>" id="monto_variable_<?=$bus_conceptos["idconcepto_tributario"]?>" size="15" style="text-align:right" onBlur="actualizarMontoVariable(this.id, '<?=$bus_conceptos["idconcepto_tributario"]?>', this.value)" value="<?=$bus_conceptos["valor_aforo"]?>">
				<?
			  }
			  
			  
			  if($bus_solicitud_calculo["estado"] != "elaboracion"){
			  echo number_format($bus_conceptos["valor_aforo"],2,",",".");
			 }
			  
			  $total_general += $bus_conceptos["total_pagar"];
			  ?>
              &nbsp;
              </td>
              <td class='Browse' align='right' style="padding:3px">
			  <?
              
			  	if($bus_conceptos["tipo_calculo_aforo"] == "porcentual"){
					$simbolo = "%";
				}
				
				echo number_format($bus_conceptos["alicuota"],2,",",".")." ".$simbolo;
			  ?></td>
                <td class='Browse' align='right' style="padding:3px"><strong><?=number_format($bus_conceptos["total_pagar"],2,",",".")?></strong></td>
                <td align='center' bgcolor="#e7dfce" class='Browse' style="padding:3px">
                &nbsp;
                 <?
                if($bus_solicitud_calculo["estado"] == 'elaboracion'){
					?>
                 
                  <select name="fraccion_pago_<?=$bus_conceptos["idconcepto_tributario"]?>" id="fraccion_pago_<?=$bus_conceptos["idconcepto_tributario"]?>">
                	<option <? if($bus_conceptos["fraccion_pago"] == "fecha_actual"){echo "selected";}?> value="fecha_actual" onclick="actualizarFechasdePago('<?=$bus_conceptos["idconcepto_tributario"]?>', 'fecha_actual')">Fecha Actual</option>
                    <option <? if($bus_conceptos["fraccion_pago"] == "mensual"){echo "selected";}?> value="mensual" onclick="actualizarFechasdePago('<?=$bus_conceptos["idconcepto_tributario"]?>', 'mensual')">Mensual</option>
                    <option <? if($bus_conceptos["fraccion_pago"] == "trimestral"){echo "selected";}?> value="trimestral" onclick="actualizarFechasdePago('<?=$bus_conceptos["idconcepto_tributario"]?>', 'trimestral')">Trimestral</option>
                    <option <? if($bus_conceptos["fraccion_pago"] == "anual"){echo "selected";}?> value="anual" onclick="actualizarFechasdePago('<?=$bus_conceptos["idconcepto_tributario"]?>', 'anual')">Anual</option>
                </select>
                <?
                }else{
					switch($bus_conceptos["fraccion_pago"]){
						case "fecha_actual":
							echo "Fecha Actual";
						break;
						case "mensual":
							echo "Mensual";
						break;
						case "trimestral":
							echo "Trimestral";
						break;
						case "anual":
							echo "Anual";
						break;
					}
				}
				?>
                </td>
                <td class='Browse' align="center">
                &nbsp;
				<?
                if($bus_solicitud_calculo["estado"] == 'elaboracion'){
					?>
					<img src="imagenes/delete.png" onclick="eliminarConcepto('<?=$bus_conceptos["idconceptos_solicitud_calculo"]?>')" style="cursor:pointer">
					<?
				}
				?>
                
                
              </td>
      </tr>
			<?
			$total = 0;
			$simbolo = "";
		}
		?>
        <tr>
        <td align="right" colspan="3"><strong>TOTAL A PAGAR</strong></td>
        <td align="right"><strong style="color:#FF0000"><?=number_format($total_general,2,",",".")?></strong></td>
        <td>&nbsp;</td>
        </tr>
		</table>
	<?
	break;
	
	
	case "consultarContribuyente":
		$sql_consulta = mysql_query("select * from contribuyente where idcontribuyente ='".$idcontribuyente."'")or die(mysql_error());
		$bus_consulta = mysql_fetch_array($sql_consulta);
		
		echo "<strong>Nombre o Razon Social:</strong> ".$bus_consulta["razon_social"]."&nbsp;
			<strong>RIF:</strong> ".$bus_consulta["rif"];
		
		
	break;
	
	
	case "ingresarDatosBasicos":
		$sql_ingresar = mysql_query("insert into solicitud_calculo(idcontribuyente,
																	idtipo_solicitud,
																	fecha_solicitud,
																	anio,
																	desde,
																	hasta,
																	vence,
																	descripcion,
																	estado)VALUES('".$idcontribuyente."',
																						'".$tipo_solicitud."',
																						'".$fecha_solicitud."',
																						'".$anio."',
																						'".$desde."',
																						'".$hasta."',
																						'".$vence."',
																						'".$descripcion."',
																						'elaboracion')")or die(mysql_error());
		$idsolicitud_calculo = mysql_insert_id();
		echo $idsolicitud_calculo;
		
		
		$sql = "(select 
					c.idconcepto_tributario,
					c.codigo,
					c.denominacion,
					c.tipo_base_aforo,
					c.tipo_calculo_aforo,
					c.valor_aforo,
					c.divisor_aforo,
					c.monto_variable,
					c.idtabla_constantes_recaudacion
						from 
					concepto_tributario c,
					asociar_tipo_solicitud_concepto ac
						where 
					ac.idtipo_solicitud = '".$tipo_solicitud."'
					and c.idconcepto_tributario = ac.idconcepto_tributario)";
					
					
		$sql_actividades_comerciales = mysql_query("select idactividad_comercial from actividades_contribuyente where idcontribuyente = '".$idcontribuyente."'");
		while($bus_actividades_comerciales = mysql_fetch_array($sql_actividades_comerciales)){
			$sql .= " UNION (select 
								c.idconcepto_tributario,
								c.codigo,
								c.denominacion,
								c.tipo_base_aforo,
								c.tipo_calculo_aforo,
								c.valor_aforo,
								c.divisor_aforo,
								c.monto_variable,
								c.idtabla_constantes_recaudacion
									from 
								concepto_tributario c,
								asociar_concepto_actividad_comercial ac
									where 
								ac.idactividad_comercial = '".$bus_actividades_comerciales["idactividad_comercial"]."'
								and c.idconcepto_tributario = ac.idconcepto_tributario)";
		}
		
		
		$sql_conceptos = mysql_query($sql)or die(mysql_error());
											
		while($bus_conceptos = mysql_fetch_array($sql_conceptos)){
		
			
			
			$sql_consulta_tipo_solicitud = mysql_query("select * from 
																asociar_tipo_solicitud_concepto 
																where 
																idconcepto_tributario = '".$bus_conceptos["idconcepto_tributario"]."' 
																and idtipo_solicitud = '".$tipo_solicitud."'");
																
			$num_consulta_tipo_solicitud = mysql_num_rows($sql_consulta_tipo_solicitud);
			
		
			$sql_configuracion_recaudacion = mysql_query("select * from configuracion_recaudacion");
			$bus_configuracion_recaudacion = mysql_fetch_array($sql_configuracion_recaudacion);
			
		
			if($num_consulta_tipo_solicitud > 0){
		
			
			if($bus_conceptos["idtabla_constantes_recaudacion"] != 0){
			
			
				$sql_tabla_constante = mysql_query("select * from 
															tabla_constantes_recaudacion 
															where 
															idtabla_constantes = '".$bus_conceptos["idtabla_constantes_recaudacion"]."'")or die(mysql_error());
				$bus_tabla_constante = mysql_fetch_array($sql_tabla_constante);
				
				if($bus_tabla_constante["unidad"] == "dias"){
					
					$dias_diferencia = diasDiferencia($desde, $hasta);
					
					$sql_consulta = mysql_query("select  valor
													from rango_tabla_constantes_recaudacion
													where 
													'".$dias_diferencia."' >= desde
													and '".$dias_diferencia."' <= hasta");								
					$bus_consulta = mysql_fetch_array($sql_consulta);
					//echo "&nbsp;&nbsp;VALOR: ".$bus_consulta["valor"];
					$total_pagar = $bus_consulta["valor"]*$bus_configuracion_recaudacion["costo_unidad_tributaria"];
					
				}
				
				$tipo_base_aforo = "unidad_tributaria";
				$tipo_calculo_aforo = "fijo";
				$valor_aforo =  $bus_consulta["valor"];
				
			}else{
				
				if($bus_conceptos["tipo_base_aforo"] == "unidad_tributaria"){
					if($bus_conceptos["tipo_calculo_aforo"] == "fijo"){
						$total_pagar = $bus_conceptos["valor_aforo"]*$bus_configuracion_recaudacion["costo_unidad_tributaria"];
					}else{
						$total_pagar = ($bus_conceptos["valor_aforo"]/$bus_conceptos["divisor_aforo"])*$bus_configuracion_recaudacion["costo_unidad_tributaria"];
					}
				}else if($bus_conceptos["tipo_base_aforo"] == "monto_variable"){
						if($bus_conceptos["tipo_calculo_aforo"] == "fijo"){
							$total_pagar = $bus_conceptos["monto_variable"];
						}else{
							$total_pagar = (($bus_conceptos["monto_variable"]*$bus_conceptos["valor_aforo"])/$bus_conceptos["divisor_aforo"]);
						}
						
				}else{
					$sql_contribuyente = mysql_query("select * from solicitud_calculo 
																	where 
																	idsolicitud_calculo = '".$idsolicitud_calculo."'");
					$bus_contribuyente = mysql_fetch_array($sql_contribuyente);
					$sql_registro = mysql_query("select * from 
															registro_mercantil_contribuyente 
															where 
															idcontribuyente = '".$bus_contribuyente["idcontribuyente"]."'
															order by idregistro_mercantil_contribuyente DESC limit 0,1");
					$bus_registro = mysql_fetch_array($sql_registro);
					if($bus_consulta["tipo_calculo_aforo"] == "fijo"){
						$total_pagar = $bus_consulta["valor_aforo"];
					}else{
						$total_pagar = ($bus_consulta["valor_aforo"]/$bus_registro["capital_social"])*$bus_configuracion_recaudacion["costo_unidad_tributaria"];
					}
				}
				
				$tipo_base_aforo = $bus_conceptos["tipo_base_aforo"];
				$tipo_calculo_aforo = $bus_conceptos["tipo_calculo_aforo"];
				if($bus_conceptos["tipo_base_aforo"] == "monto_variable"){
					$valor_aforo = $bus_conceptos["monto_variable"];
				}else{
					$valor_aforo =  $bus_conceptos["valor_aforo"];
				}
				
				
			}
				
		}else{ // SI VIENE POR ACTIVIDAD ECONOMINA Y NO POR SOLICITUD
			
			$sql_actividades_comerciales = mysql_query("select idactividad_comercial 
																from 
																actividades_contribuyente 
																where 
																idcontribuyente = '".$idcontribuyente."'");
			while($bus_actividades_comerciales = mysql_fetch_array($sql_actividades_comerciales)){
				$sql_consulta = mysql_query("select * from 
														asociar_concepto_actividad_comercial 
														where 
														idconcepto_tributario = '".$bus_conceptos["idconcepto_tributario"]."' 
														and idactividad_comercial = '".$bus_actividades_comerciales["idactividad_comercial"]."'");
				$num_consulta = mysql_num_rows($sql_consulta);
				if($num_consulta > 0){
					$bus_consulta = mysql_fetch_array($sql_consulta);
					$tipo_base_aforo = $bus_consulta["tipo_valor"];
					$tipo_calculo_aforo = "fijo";
					$valor_aforo = $bus_consulta["valor"];
					if($tipo_base_aforo == "unidad_tributaria"){
						$total_pagar = $bus_consulta["valor"]*$bus_configuracion_recaudacion["costo_unidad_tributaria"];
					}else{
						$total_pagar = $bus_consulta["valor"];
					}
				}
				
				
			}
			
			
		}
			$sql_ingresar_conceptos = mysql_query("insert into conceptos_solicitud_calculo(idsolicitud_calculo,
																				idconcepto,
																				tipo_cobro,
																				tipo_calculo,
																				total_pagar,
																				valor,
																				fraccion_pago)
																				VALUES('".$idsolicitud_calculo."',
																						'".$bus_conceptos["idconcepto_tributario"]."',
																						'".$tipo_base_aforo."',
																						'".$tipo_calculo_aforo."',
																						'".$total_pagar."',
																						'".$valor_aforo."',
																						'fecha_actual')");
		}
	
	break;
	
	
	case "actualizarMontoVariable":
		$sql_concepto = mysql_query("select * from concepto_tributario where idconcepto_tributario = '".$idconcepto."'");
		$bus_concepto = mysql_fetch_array($sql_concepto);
		
		//echo $bus_concepto["tipo_calculo_aforo"];
		
		if($bus_concepto["tipo_calculo_aforo"] == "fijo"){
			$total_pagar = $valor;
		}else{
			$total_pagar =  (($valor*$bus_concepto["valor_aforo"])/$bus_concepto["divisor_aforo"]);
		}
		
			
		
		
		$sql_actualizar = mysql_query("update conceptos_solicitud_calculo 
													set total_pagar = '".$total_pagar."',
													valor = '".$valor."' 
														where 
													idsolicitud_calculo = '".$idsolicitud_calculo."' 
													and idconcepto = '".$idconcepto."'")or die(mysql_error());
	break;
	
	case "ingresarConcepto":
		$sql_conceptos = mysql_query("select 
											c.idconcepto_tributario,
											c.codigo,
											c.denominacion,
											c.tipo_base_aforo,
											c.tipo_calculo_aforo,
											c.valor_aforo,
											c.divisor_aforo,
											c.idtabla_constantes_recaudacion
								 				from 
											concepto_tributario c 
												where 
											idconcepto_tributario = '".$idconcepto."'");
		$bus_conceptos = mysql_fetch_array($sql_conceptos);

		$sql_configuracion_recaudacion = mysql_query("select * from configuracion_recaudacion");
		$bus_configuracion_recaudacion = mysql_fetch_array($sql_configuracion_recaudacion);
			
			
			
		
			
			
			$sql_consulta_tipo_solicitud = mysql_query("select * from 
																asociar_tipo_solicitud_concepto 
																where 
																idconcepto_tributario = '".$bus_conceptos["idconcepto_tributario"]."' 
																and idtipo_solicitud = '".$tipo_solicitud."'");
																
			$num_consulta_tipo_solicitud = mysql_num_rows($sql_consulta_tipo_solicitud);
			
		
			$sql_configuracion_recaudacion = mysql_query("select * from configuracion_recaudacion");
			$bus_configuracion_recaudacion = mysql_fetch_array($sql_configuracion_recaudacion);
			
		
			if($num_consulta_tipo_solicitud > 0){
			
		
			
			if($bus_conceptos["idtabla_constantes_recaudacion"] != 0){
			
			
				$sql_tabla_constante = mysql_query("select * from 
															tabla_constantes_recaudacion 
															where 
															idtabla_constantes = '".$bus_conceptos["idtabla_constantes_recaudacion"]."'")or die(mysql_error());
				$bus_tabla_constante = mysql_fetch_array($sql_tabla_constante);
				
				if($bus_tabla_constante["unidad"] == "dias"){
					
					$dias_diferencia = diasDiferencia($desde, $hasta);
					
					$sql_consulta = mysql_query("select  valor
													from rango_tabla_constantes_recaudacion
													where 
													'".$dias_diferencia."' >= desde
													and '".$dias_diferencia."' <= hasta");								
					$bus_consulta = mysql_fetch_array($sql_consulta);
					//echo "&nbsp;&nbsp;VALOR: ".$bus_consulta["valor"];
					$total_pagar = $bus_consulta["valor"]*$bus_configuracion_recaudacion["costo_unidad_tributaria"];
					
				}
				
				$tipo_base_aforo = "unidad_tributaria";
				$tipo_calculo_aforo = "fijo";
				$valor_aforo =  $bus_consulta["valor"];
				
			}else{
				
				if($bus_conceptos["tipo_base_aforo"] == "unidad_tributaria"){
					if($bus_conceptos["tipo_calculo_aforo"] == "fijo"){
						$total_pagar = $bus_conceptos["valor_aforo"]*$bus_configuracion_recaudacion["costo_unidad_tributaria"];
					}else{
						$total_pagar = ($bus_conceptos["valor_aforo"]/$bus_conceptos["divisor_aforo"])*$bus_configuracion_recaudacion["costo_unidad_tributaria"];
					}
				}else if($bus_conceptos["tipo_base_aforo"] == "monto_variable"){
						$total_pagar = 0;
				}else{
					$sql_contribuyente = mysql_query("select * from solicitud_calculo 
																	where 
																	idsolicitud_calculo = '".$idsolicitud_calculo."'");
					$bus_contribuyente = mysql_fetch_array($sql_contribuyente);
					$sql_registro = mysql_query("select * from 
															registro_mercantil_contribuyente 
															where 
															idcontribuyente = '".$bus_contribuyente["idcontribuyente"]."'
															order by idregistro_mercantil_contribuyente DESC limit 0,1");
					$bus_registro = mysql_fetch_array($sql_registro);
					if($bus_consulta["tipo_calculo_aforo"] == "fijo"){
						$total_pagar = $bus_consulta["valor_aforo"];
					}else{
						$total_pagar = ($bus_consulta["valor_aforo"]/$bus_registro["capital_social"])*$bus_configuracion_recaudacion["costo_unidad_tributaria"];
					}
				}
				
				$tipo_base_aforo = $bus_conceptos["tipo_base_aforo"];
				$tipo_calculo_aforo = $bus_consultos["tipo_calculo_aforo"];
				$valor_aforo =  $bus_conceptos["valor_aforo"];
				
			}
				
		}else{ // SI VIENE POR ACTIVIDAD ECONOMINA Y NO POR SOLICITUD
			
			echo $idcontribuyente;
			
			$sql_actividades_comerciales = mysql_query("select idactividad_comercial 
																from 
																actividades_contribuyente 
																where 
																idcontribuyente = '".$idcontribuyente."'");
			while($bus_actividades_comerciales = mysql_fetch_array($sql_actividades_comerciales)){
				$sql_consulta = mysql_query("select * from 
														asociar_concepto_actividad_comercial 
														where 
														idconcepto_tributario = '".$bus_conceptos["idconcepto_tributario"]."' 
														and idactividad_comercial = '".$bus_actividades_comerciales["idactividad_comercial"]."'");
				$num_consulta = mysql_num_rows($sql_consulta);
				if($num_consulta > 0){
					$bus_consulta = mysql_fetch_array($sql_consulta);
					$tipo_base_aforo = $bus_consulta["tipo_valor"];
					$tipo_calculo_aforo = "fijo";
					$valor_aforo = $bus_consulta["valor"];
					if($tipo_base_aforo == "unidad_tributaria"){
						$total_pagar = $bus_consulta["valor"]*$bus_configuracion_recaudacion["costo_unidad_tributaria"];
					}else{
						$total_pagar = $bus_consulta["valor"];
					}
				}
				
				
			}
			
			
		}
			$sql_ingresar_conceptos = mysql_query("insert into conceptos_solicitud_calculo(idsolicitud_calculo,
																				idconcepto,
																				tipo_cobro,
																				tipo_calculo,
																				total_pagar,
																				valor,
																				fraccion_pago)
																				VALUES('".$idsolicitud_calculo."',
																						'".$bus_conceptos["idconcepto_tributario"]."',
																						'".$tipo_base_aforo."',
																						'".$tipo_calculo_aforo."',
																						'".$total_pagar."',
																						'".$valor_aforo."',
																						'fecha_actual')");
		
		
		
		
		
		
	break;
	
	case "eliminarConcepto":
		$sql_eliminar = mysql_query("delete from conceptos_solicitud_calculo where idconceptos_solicitud_calculo = '".$idconceptos_solicitud_calculo."'");
	break;
	
	
	
	case "procesarSolicitud":
	//echo "select numero_solicitud_calculo from configuracion_recaudacion";
		$sql_consultar_numero = mysql_query("select numero_solicitud_calculo from configuracion_recaudacion");
		$bus_consultar_numero = mysql_fetch_array($sql_consultar_numero)or die(mysql_error());
		
		$sql_actualizar = mysql_query("update solicitud_calculo 
													set 
													numero_solicitud = 'SC-".$_SESSION["anio_fiscal"]."-".$bus_consultar_numero["numero_solicitud_calculo"]."',
													estado = 'procesado'
													where 
													idsolicitud_calculo = '".$idsolicitud_calculo."'");
		
		
		$sql_actualizar_numero = mysql_query("update 
												configuracion_recaudacion 
													set 
												numero_solicitud_calculo = numero_solicitud_calculo + 1");
		echo "SC-".$_SESSION["anio_fiscal"]."-".$bus_consultar_numero["numero_solicitud_calculo"];
	break;
	
	
	case "anularSolicitud":
		$sql_anular = mysql_query("update solicitud_calculo
											set estado = 'anulado'
											where
											idsolicitud_calculo = '".$idsolicitud_calculo."'");
	break;
	
	
	
	case "consultarSolicitud":
		$sql_consultar = mysql_query("select * from solicitud_calculo where idsolicitud_calculo = '".$idsolicitud_calculo."'");
		$bus_consultar = mysql_fetch_array($sql_consultar);
		
		$sql_contribuyente = mysql_query("select * from contribuyente where idcontribuyente = '".$bus_consultar["idcontribuyente"]."'");
		$bus_contribuyente = mysql_fetch_array($sql_contribuyente);
		
		echo $bus_consultar["numero_solicitud"]."|.|".
			 $bus_consultar["estado"]."|.|".
			 $bus_consultar["fecha_solicitud"]."|.|".
			 "<strong>Nombre o Razon Social:</strong> ".$bus_contribuyente["razon_social"]."&nbsp;
			<strong>RIF:</strong> ".$bus_contribuyente["rif"]."|.|".
			 $bus_consultar["idtipo_solicitud"]."|.|".
			 $bus_consultar["fecha_solicitud"]."|.|".
			 $bus_consultar["anio"]."|.|".
			 $bus_consultar["desde"]."|.|".
			 $bus_consultar["hasta"]."|.|".
			 $bus_consultar["vence"]."|.|".
			 $bus_consultar["descripcion"]."|.|".
			 $bus_consultar["idcontribuyente"]."|.|";
		
		
	break;
	
	
	
	
	case "actualizarFechasdePago":
		$sql_actualizar = mysql_query("update conceptos_solicitud_calculo 
												set fraccion_pago = '".$fraccion_pago."' 
													where 
												idsolicitud_calculo = '".$idsolicitud_calculo."'
												and idconcepto = '".$idconcepto_tributario."'")or die(mysql_error());
	break;
	
	case "mostrarRangoFechas":
		
		$sql_consultar_solicitud = mysql_query("select * from solicitud_calculo where idsolicitud_calculo = '".$idsolicitud_calculo."'");
		$bus_consultar_solicitud = mysql_fetch_array($sql_consultar_solicitud);
		
		if($bus_consultar_solicitud["estado"] == 'elaboracion'){
		
		$sql_consulta_conceptos = mysql_query("select * from conceptos_solicitud_calculo where idsolicitud_calculo = '".$idsolicitud_calculo."'");
		while($bus_consulta_conceptos = mysql_fetch_array($sql_consulta_conceptos)){
			
			
			
			$sql_limpiar = mysql_query("delete from rango_fecha_vencimiento_conceptos where idconcepto_solicitud_calculo = '".$bus_consulta_conceptos["idconceptos_solicitud_calculo"]."'");
			$fecha_vencimiento = $fecha_solicitud;
		
			if($bus_consulta_conceptos["fraccion_pago"] == "fecha_actual"){	
				$sql_insert = mysql_query("insert into rango_fecha_vencimiento_conceptos
													(idconcepto_solicitud_calculo,
													fecha_vencimiento,
													estado)
														VALUES
													('".$bus_consulta_conceptos["idconceptos_solicitud_calculo"]."',
													'".$fecha_vencimiento."',
													'pendiente')");
																		
			}else if($bus_consulta_conceptos["fraccion_pago"] == "mensual"){
			
				for($i=0;$i<12;$i++){
					$cantidad = 30;
					
					$fecha_vencimiento = date("Y-m-d", strtotime("$fecha_vencimiento + $cantidad days"));
					$sql_insert = mysql_query("insert into rango_fecha_vencimiento_conceptos
													(idconcepto_solicitud_calculo,
													fecha_vencimiento,
													estado)
														VALUES
													('".$bus_consulta_conceptos["idconceptos_solicitud_calculo"]."',
													'".$fecha_vencimiento."',
													'pendiente')")or die(mysql_error());
				}
			}else if($bus_consulta_conceptos["fraccion_pago"] == "trimestral"){
				for($i=0;$i<4;$i++){
					$cantidad = 90;
					$fecha_vencimiento = date("Y-m-d", strtotime("$fecha_vencimiento + $cantidad days"));
					$sql_insert = mysql_query("insert into rango_fecha_vencimiento_conceptos
													(idconcepto_solicitud_calculo,
													fecha_vencimiento,
													estado)
														VALUES
													('".$bus_consulta_conceptos["idconceptos_solicitud_calculo"]."',
													'".$fecha_vencimiento."',
													'pendiente')");
				}
			}else if($bus_consulta_conceptos["fraccion_pago"] == "anual"){
					$cantidad = 365;
					$fecha_vencimiento = date("Y-m-d", strtotime("$fecha_vencimiento + $cantidad days"));
					$sql_insert = mysql_query("insert into rango_fecha_vencimiento_conceptos
													(idconcepto_solicitud_calculo,
													fecha_vencimiento,
													estado)
														VALUES
													('".$bus_consulta_conceptos["idconceptos_solicitud_calculo"]."',
													'".$fecha_vencimiento."',
													'pendiente')");
			}
			
			
			// AQUI HACER EL QUERY PARA MOSTRARLOS
		}
		
		}
		
		
		
		
		
		
		// LIMPIAR LOS DATOS PRIMERO ANTES DE GUARDARLOS 
		?>
		 <form name="formulario_deudas" id="formulario_deudas" action="">
		<table width="100%" align="center" cellpadding="4" cellspacing="4">
		<?
		$sql_consultar_solicitud = mysql_query("select
									 csc.idconcepto,
									 rfv.fecha_vencimiento,
									 rfv.idrango_fecha_vencimiento_conceptos as idfraccion,
									 csc.idconceptos_solicitud_calculo as idcon_sol,
									 csc.total_pagar as monto_total_pagar,
									 (csc.total_pagar / (select count(*)
													from
												rango_fecha_vencimiento_conceptos rr
													where
												rr.idconcepto_solicitud_calculo = csc.idconceptos_solicitud_calculo)) as fraccion_pagar
										from
									conceptos_solicitud_calculo csc,
									rango_fecha_vencimiento_conceptos rfv
										where
									csc.idsolicitud_calculo = '".$idsolicitud_calculo."'
									and rfv.idconcepto_solicitud_calculo =  csc.idconceptos_solicitud_calculo
									and rfv.estado = 'pendiente'
									order by rfv.fecha_vencimiento");
		
	
	
		//$bus_consultar_solicitudes = mysql_fetch_array($sql_consultar_solicitud);
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
				?>
				
                <tr>
				<td>&nbsp;</td>
				</tr>
                	<tr>
                    <td style="border-bottom:#999999 solid 1px">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr style="background-color:#CCCCCC">
                        <td width="27%" align="center"><strong>Concepto</strong></td>
                        <td width="22%" align="center"><strong>Cuota a Pagar</strong></td>
                        <td width="51%" align="center"><strong>Fecha Tope</strong></td>
                        <td width="1%" align="center"><strong>Sel.</strong></td>
                        <tr>
				<?
			$i=1;
			while($bus_conceptos_solicitud_calculo = mysql_fetch_array($sql_consultar_solicitud)){
				
				
				
				
				$sql_concepto_tributario = mysql_query("select * from concepto_tributario where idconcepto_tributario = '".$bus_conceptos_solicitud_calculo["idconcepto"]."'");
				$bus_concepto_tributario = mysql_fetch_array($sql_concepto_tributario);
					
					if($bus_conceptos_solicitud_calculo["fecha_vencimiento"] != $fecha){
						
						if($i != 1){
							?>
							<tr style="background-color:#FFFFCC; font-weight:bold;">
                            <td style="border-bottom:#FFFFFF solid 5px"><input type="hidden" value="<?=$conceptos?>" id="conceptos_<?=($i-1)?>_<?=$bus_consultar_solicitudes["idsolicitud_calculo"]?>"></td>
                            <td align="right" style="color:#000; border-bottom:#FFFFFF solid 5px"><?=number_format($suma_pago,2,",",".")?></td>
                            <td style="border-bottom:#FFFFFF solid 5px"><input type="hidden" value="<?=$montos_fraccion?>" id="montos_<?=($i-1)?>_<?=$bus_consultar_solicitudes["idsolicitud_calculo"]?>"></td>
                            <td style="border-bottom:#FFFFFF solid 5px"><input type="hidden" value="<?=$moras?>" id="mora_<?=($i-1)?>_<?=$bus_consultar_solicitudes["idsolicitud_calculo"]?>"></td>
                            </tr>
							<?
							$suma_pago=0;
							$conceptos="";
							$montos_fraccion = "";
							$total_mora = "";
						}
						
						?>
						<tr style="background-color:#666666; font-weight:bold">
                        <td style="color:#FFFFFF;" colspan="2">PAGO <?=$i?></td>
                        <td></td>
                        <td></td>
                        </tr>
						<?
						$fecha = $bus_conceptos_solicitud_calculo["fecha_vencimiento"];
						$i++;
						$suma_pago += $bus_conceptos_solicitud_calculo["fraccion_pagar"];
						
					}else{
						$suma_pago += $bus_conceptos_solicitud_calculo["fraccion_pagar"];
					}
					
					
					
					?>
                    <tr>
                    <td>(<?=$bus_concepto_tributario["codigo"]?>) <?=$bus_concepto_tributario["denominacion"]?></td>
                    <td align="right"><?=number_format($bus_conceptos_solicitud_calculo["fraccion_pagar"],2,",",".")?></td>
                    <td>&nbsp;&nbsp;&nbsp;
					<?
                    if($bus_conceptos_solicitud_calculo["fecha_vencimiento"] == date("Y-m-d")){
						echo "<strong>Este Rubro debe ser cancelado el dia de hoy</strong>";
					}else{
						$partes = explode("-",$bus_conceptos_solicitud_calculo["fecha_vencimiento"]);
						echo "Debe Cancelarse antes del ".$partes[2]." de ".meses($partes[1])." de ".$partes[0];
					}
					?></td>
                    <td></td>
                    </tr>
                     <?
                     if($total_mora != 0){
					 ?>
                        <tr>
                            <td style="color:#990000; text-align:right; font-weight:bold">Total Mora Acumulada:&nbsp;</td>
                            <td style="color:#990000; text-align:right; font-weight:bold"><?=number_format($total_mora,2,",",".")?></td>
                            <td></td>
                            <td></td>
                        </tr>
					<?
					}
					
					$sql_update = mysql_query("update rango_fecha_vencimiento_conceptos set monto_total = '".round($bus_conceptos_solicitud_calculo["fraccion_pagar"],2)."' where idrango_fecha_vencimiento_conceptos = '".$bus_conceptos_solicitud_calculo["idfraccion"]."'");
					
				$conceptos .= $bus_conceptos_solicitud_calculo["idfraccion"].", ";
				$montos_fraccion .= round($bus_conceptos_solicitud_calculo["fraccion_pagar"],2).", "; 
				$moras .= $total_mora.", ";
				$suma_pago += $total_mora;
				$total_mora = "";
			}
			?>
            
							<tr style="background-color:#FFFFCC; font-weight:bold; border-bottom:#FFFFFF solid 1px">
                            <td><input type="hidden" value="<?=$conceptos?>" id="conceptos_<?=($i-1)?>_<?=$bus_consultar_solicitudes["idsolicitud_calculo"]?>"></td>
                            <td align="right" style="color:#000"><?=number_format($suma_pago,2,",",".")?></td>
                            <td><input type="hidden" value="<?=$montos_fraccion?>" id="montos_<?=($i-1)?>_<?=$bus_consultar_solicitudes["idsolicitud_calculo"]?>"></td>
                            <td><input type="hidden" value="<?=$moras?>" id="mora_<?=($i-1)?>_<?=$bus_consultar_solicitudes["idsolicitud_calculo"]?>"></td>
                            </tr>
							
            	</table>
                <br>
				
            </td>
			</tr>
			<?
			$fecha = "";
			$i=1;
			$suma_pago = 0;
			$conceptos = "";
			$montos_fraccion = "";
			$total_mora = "";
		
		?>
		</table>
        </form>
        <?
		
		
		
		
		
		
		
		
		/*
		?>
		<table border="0" align="center" cellpadding="0" cellspacing="0" class="Browse" width="70%">
        <thead>
        <tr>
        <td class="Browse">Concepto</td>
        <td class="Browse">Fecha de Proximo Pago</td>
		<tr>
        </thead>
		<?
		$sql_consulta_conceptos = mysql_query("select ct.codigo,
													  ct.denominacion,
													  rfvc.fecha_vencimiento
														 from 
													conceptos_solicitud_calculo csc,
													concepto_tributario ct,
													rango_fecha_vencimiento_conceptos rfvc
															where 
													csc.idsolicitud_calculo = '".$idsolicitud_calculo."'
													and ct.idconcepto_tributario = csc.idconcepto
													and rfvc.idconcepto_solicitud_calculo = csc.idconceptos_solicitud_calculo
													group by rfvc.fecha_vencimiento, ct.idconcepto_tributario")or die(mysql_error());
		while($bus_consulta_conceptos = mysql_fetch_array($sql_consulta_conceptos)){
			?>
			  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
					<td class='Browse'>(<?=$bus_consulta_conceptos["codigo"]?>)&nbsp;<?=$bus_consulta_conceptos["denominacion"]?></td>
					<td class='Browse'>&nbsp;
                    <?
                    if($bus_consulta_conceptos["fecha_vencimiento"] == date("Y-m-d")){
						echo "<strong>Este Rubro debe ser cancelado el dia de hoy</strong>";
					}else{
						$partes = explode("-",$bus_consulta_conceptos["fecha_vencimiento"]);
						echo "Debe Cancelarse antes del ".$partes[2]." de ".meses($partes[1])." de ".$partes[0];
					}
					?>
                    </td>
			  </tr>
			<?
		}
		?>
		</table>
        <br />
<br />

		<?
		
		*/
	break;
}
?>