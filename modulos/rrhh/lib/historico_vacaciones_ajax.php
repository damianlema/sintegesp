<?
include("../../../conf/conex.php");
$conexion_db = Conectarse();
extract($_POST);
session_start();

function obtener_dias_entre_fechas($fechad, $fechah){ // DEVUELVE DIFERENCIAS EN DIAS PERO CONTANDO LOS FINES DESEMANA
	
	list($ano1, $mes1, $dia1)= SPLIT('[/.-]', $fechad);
	
	list($ano2, $mes2, $dia2)= SPLIT('[/.-]', $fechah);
	
	//calculo timestam de las dos fechas 
	$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
	$timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2); 
	
	//resto a una fecha la otra 
	$segundos_diferencia = $timestamp1 - $timestamp2; 
	//echo $segundos_diferencia; 
	
	//convierto segundos en días 
	$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
	
	//obtengo el valor absoulto de los días (quito el posible signo negativo) 
	$dias_diferencia = abs($dias_diferencia); 
	
	//quito los decimales a los días de diferencia 
	$dias_diferencia = floor($dias_diferencia); 
	
	return $dias_diferencia; 
}

function getDiaSemana($fecha) {
	$fecha=str_replace("/","-",$fecha);
	list($dia,$mes,$anio)=explode("-",$fecha);
	$dia = (((mktime ( 0, 0, 0, $mes, $dia, $anio) - mktime ( 0, 0, 0, 7, 17, $anio))/(60*60*24))+700000) % 7;
	//$dia--; if ($dia == 0) $dia = 7;
	return $dia;
}

if($ejecutar == "obten_disfrute_completo"){
	//echo "obten_disfrute_completo";
	if($tiempo_disfrute == ""){
		$tiempo_disfrute = 0;
	}
	
	if($tiempo_disfrutado==""){
		$tiempo_disfrutado = 0;
	}
	
	$resultado = $tiempo_disfrute-$tiempo_disfrutado;
	echo $resultado;
}

if($ejecutar == "cant_feriados"){
	//echo "cant_feriados";
	$total_dias = $dias_disfrute+$catidad_feriados;
	echo $total_dias;
}

if($ejecutar == "llenaroculto"){
	echo $valor;
}

if($ejecutar=="validarPeriodo"){
	//echo "validarPeriodos";
	//echo $periodo;
	$cantidad = strlen($periodo);
	
	if($cantidad == 9){
		$guion = substr($periodo,4,1);
		if($guion == "-"){
			$sms = 1;
			}else{
				$sms = 2;
				}
		}else{
			$sms = 0;
			}
echo $sms;
}

if($ejecutar == "reinicioAjustado"){
	//echo "reinicioAjustado";
	if($reinicio_ajustado == $fecha_reincorporacion){
		$sms = 0;
		}else{
			$sms = 1;
			}
	echo $sms;
}

if($ejecutar == "validarFechas"){
	
	if($fecha_inicio == ""){
		$fecha_inicio = $fecha_culminacion;
		}
		
	if($fecha_culminacion == ""){
		$fecha_culminacion = $fecha_inicio;
		}
	
   list($a, $m, $d)=SPLIT('[/.-]', $fecha_inicio); $fdesde = "$d-$m-$a";
   list($a, $m, $d)=SPLIT('[/.-]', $fecha_culminacion); $fhasta = "$d-$m-$a";
   
   list($dd, $mm, $ad)=SPLIT('[/.-]', $fdesde); $dd = (int) $md; $md = (int) $dd; $ad = (int) $ad;
	$dias_completos = obtener_dias_entre_fechas($fecha_inicio, $fecha_culminacion);
	$dia_semana = getDiaSemana($fdesde);
	$dias_permiso = 0;
	
	for ($i=0; $i<=$dias_completos; $i++) {
		if ($dia_semana == 8) $dia_semana = 1;
		if ($dia_semana >= 2 && $dia_semana <= 6) $dias_permiso++;
		$dia_semana++;
	}
	echo $dias_permiso;
}

if($ejecutar == "llenarFormulario"){
	
	//echo "llenarFormulario";
	//echo $id_historico;
	$sql = "select * from historico_vacaciones where idhistorico_vacaciones = '".$id_historico."'; ";
	$query = mysql_query($sql,$conexion_db);
	$array = mysql_fetch_array($query);
	?>
    	<table border="0">
        	<tr>
            	<td><input type="hidden" value="<?=$array['idhistorico_vacaciones']?>" id="idhistorico_vacaciones_encontrado"/></td>
                <td><input type="hidden" value="<?=$array['idtrabajador']?>" id="idtrabajador_encontrado"/></td>
                
                <td><input type="hidden" value="<?=$array['periodo']?>" id="periodo_encontrado"/></td>
                <td><input type="hidden" value="<?=$array['numero_memorandum']?>" id="numero_memorandum_encontrado"/></td>
                
                <td><input type="hidden" value="<?=$array['fecha_memorandum']?>" id="fecha_memorandum_encontrado"/></td>
                <td><input type="hidden" value="<?=$array['fecha_inicio_vacacion']?>" id="fecha_inicio_vacacion_encontrado"/></td>
                
                <td><input type="hidden" value="<?=$array['fecha_culminacion_vacacion']?>" id="fecha_culminacion_vacacion_encontrado"/></td>                
                <td><input type="hidden" value="<?=$array['tiempo_disfrute']?>" id="tiempo_disfrute_encontrado"/></td>
                
                <td><input type="hidden" value="<?=$array['fecha_inicio_disfrute']?>" id="fecha_inicio_disfrute_encontrado"/></td>
                <td><input type="hidden" value="<?=$array['fecha_reincorporacion']?>" id="fecha_reincorporacion_encontrado"/></td>
                
                <td><input type="hidden" value="<?=$array['fecha_reincorporacion_ajustada']?>" id="fecha_reincorporacion_ajustada_encontrado"/></td>
                <td><input type="hidden" value="<?=$array['dias_pendiente_disfrute']?>" id="dias_pendiente_disfrute_encontrado"/></td>
                
                <td><input type="hidden" value="<?=$array['dias_bono']?>" id="dias_bono_encontrado"/></td>
                <td><input type="hidden" value="<?=$array['monto_bono']?>" id="monto_bonos_encontrado"/></td>
                
                <td><input type="hidden" value="<?=$array['numero_orden_pago']?>" id="numero_orden_pago_encontrado"/></td>
                <td><input type="hidden" value="<?=$array['fecha_cancelacion']?>" id="fecha_cancelacion_encontrado"/></td>
                
                <td><input type="hidden" value="<?=$array['elaborado_por']?>" id="elaborado_por_encontrado"/></td>
                <td><input type="hidden" value="<?=$array['ci_elaborado_por']?>" id="ci_elaborado_por_encontrado"/></td>
                
                <td><input type="hidden" value="<?=$array['aprobada_por']?>" id="aprobada_por_encontrado"/></td>
                <td><input type="hidden" value="<?=$array['ci_aprobado']?>" id="ci_aprobado_encontrado"/></td>
                
                <td><input type="hidden" value="<?=$array['cantidad_feriados']?>" id="cantidad_feriadostabla"/></td>
                <td><input type="hidden" value="<?=$array['oculto_dias']?>" id="oculto_dias_encontrado"/></td>
                <td><input type="hidden" value="<?=$array['oculto_disfrutados']?>" id="oculto_disfrutados_encontrado"/></td>
            </tr>
        </table>
    <?
}

if($ejecutar == "llenargrilla"){
	//echo "llenarGrilla";
	//echo $id_trabajador;
	
	$str_sql = "select * from historico_vacaciones where idtrabajador ='".$id_trabajador."'";
	$result = mysql_query($str_sql,$conexion_db);
	
	if(!$result){
		echo "Error en llenar grilla";
	}else{
		?>
            <table width="943" border="0" align="center" cellpadding="0" cellspacing="0">
              <thead>
              <tr>
                <td class="Browse" align="center">N&uacute;mero Memorandum</td>
                <td class="Browse" align="center">Fecha Inicio Vacaci&oacute;n</td>
                <td class="Browse" align="center">Fecha Culminaci&oacute;n Vacaci&oacute;n</td>
                <td class="Browse" align="center">N&uacute;mero Orden Pago</td>
                <td class="Browse" align="center">Acci&oacute;n</td>
              </tr>
              </thead>
              <? while($array = mysql_fetch_array($result)){?>
              <tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td class="Browse" align="center"><?=$array['numero_memorandum']?></td>
                <td class="Browse" align="center"><?=$array['fecha_inicio_vacacion']?></td>
                <td class="Browse" align="center"><?=$array['fecha_culminacion_vacacion']?></td>
                <td class="Browse" align="center"><?=$array['numero_orden_pago']?></td>
                <td class="Browse" align="center"><img src="imagenes/modificar.png" style="cursor:pointer" title='Modificar' onclick="llenarFormulario(<?=$array['idhistorico_vacaciones']?>)"></td>
              </tr>
              <? }?>
            </table>
	<?	}
	
}

if($ejecutar == "accion"){
	//echo "accion";
	//echo $accion;
	if($accion == "Guardar"){
		if($id_trabajador == ""){
			$sms = 0;
			}else{
				if(
				   $id_trabajador == "" 
					 ||$periodo == ""
					 ||$numero_memorandum == ""
					 ||$fecha_memorandum == ""
					 ||$fecha_inicio_vacacion == ""
					 ||$fecha_culminacion_vacacion == ""
					 ||$tiempo_disfrute == ""
					 ||$fecha_inicio_disfrute == ""
					 ||$fecha_reincorporacion == ""
					 ||$fecha_reincorporacion_ajustada == ""
					 ||$tiempo_pendiente_disfrute == ""
					 ||$dias_bonificacion == ""
					 ||$monto_bono_vacacional == ""
					 ||$numero_orden_pago == ""
					 ||$fecha_orden_pago == ""
					 ||$elaborado_por == ""
					 ||$ci_elaborado == ""
					 ||$aprobado_por == ""
					 ||$ci_aprobado == ""
					 ||$cantidad_feriados == ""
					 ||$oculto_dias == ""
					 ||$oculto_disfrutados == ""){
						
						$sms = 1;
						
					}else{
						
							$str_sql = "insert into historico_vacaciones (idtrabajador,
														  periodo,
														  numero_memorandum,
														  fecha_memorandum,
														  fecha_inicio_vacacion,
														  fecha_culminacion_vacacion,
														  
														  cantidad_feriados,
														  tiempo_disfrute,
														  fecha_inicio_disfrute,
														  fecha_reincorporacion,
														  fecha_reincorporacion_ajustada,
														  dias_pendiente_disfrute,
														  dias_bono,
														  
														  monto_bono,
														  numero_orden_pago,
														  fecha_cancelacion,
														  elaborado_por,
														  ci_elaborado_por,
														  aprobada_por,
														  ci_aprobado,
														  usuario,
														  fechayhora,
														  
														  oculto_disfrutados,
														  oculto_dias
														  )values(
														  
														  '".$id_trabajador."',
														  '".$periodo."',
														  '".$numero_memorandum."',
														  '".$fecha_memorandum."',
														  '".$fecha_inicio_vacacion."',
														  '".$fecha_culminacion_vacacion."',
														  '".$cantidad_feriados."',
														  '".$tiempo_disfrute."',
														  '".$fecha_inicio_disfrute."',
														  '".$fecha_reincorporacion."',
														  '".$fecha_reincorporacion_ajustada."',
														  '".$tiempo_pendiente_disfrute."',
														  '".$dias_bonificacion."',
														  
														  '".$monto_bono_vacacional."',
														  '".$numero_orden_pago."',
														  '".$fecha_orden_pago."',
														  '".$elaborado_por."',
														  '".$ci_elaborado."',
														  '".$aprobado_por."',
														  '".$ci_aprobado."',
														  '".$login."',
														  '".$fh."',
														  
														  '".$oculto_dias."',
														  '".$oculto_disfrutados."'
														  )";
							$result = mysql_query($str_sql,$conexion_db);
							if(!$result){
								$sms = mysql_error();
								echo $error;
								}else{
									$sms = 2;
									}
						
						}
					}
				}//fin de accion guardar
	if($accion == "Modificar"){
		//echo "Modificar aqui";
		if(
				   $id_trabajador == "" 
					 ||$periodo == ""
					 ||$numero_memorandum == ""
					 ||$fecha_memorandum == ""
					 ||$fecha_inicio_vacacion == ""
					 ||$fecha_culminacion_vacacion == ""
					 ||$tiempo_disfrute == ""
					 ||$fecha_inicio_disfrute == ""
					 ||$fecha_reincorporacion == ""
					 ||$fecha_reincorporacion_ajustada == ""
					 ||$tiempo_pendiente_disfrute == ""
					 ||$dias_bonificacion == ""
					 ||$monto_bono_vacacional == ""
					 ||$numero_orden_pago == ""
					 ||$fecha_orden_pago == ""
					 ||$elaborado_por == ""
					 ||$ci_elaborado == ""
					 ||$aprobado_por == ""
					 ||$ci_aprobado == ""
					 ||$cantidad_feriados == ""
					 ||$oculto_dias == ""
					 ||$oculto_disfrutados == ""){
						
						$sms = 1;
						
											}else{
												$str = "update historico_vacaciones set 
													idtrabajador = '".$id_trabajador."',
													periodo = '".$periodo."',
													numero_memorandum = '".$numero_memorandum."',
													fecha_memorandum = '".$fecha_memorandum."',
													fecha_inicio_vacacion = '".$fecha_inicio_vacacion."',
													fecha_culminacion_vacacion = '".$fecha_culminacion_vacacion."',
													tiempo_disfrute = '".$tiempo_disfrute."',
													fecha_inicio_disfrute = '".$fecha_inicio_disfrute."',
													fecha_reincorporacion = '".$fecha_reincorporacion."',
													fecha_reincorporacion_ajustada = '".$fecha_reincorporacion_ajustada."',		  
													dias_pendiente_disfrute = '".$tiempo_pendiente_disfrute."',
													dias_bono = '".$dias_bonificacion."',
													
													monto_bono = '".$monto_bono_vacacional."',
													cantidad_feriados = '".$cantidad_feriados."',
													
													numero_orden_pago = '".$numero_orden_pago."',
													fecha_cancelacion = '".$fecha_orden_pago."',
													elaborado_por = '".$elaborado_por."',
													ci_elaborado_por = '".$ci_elaborado."',
													aprobada_por = '".$aprobado_por."',
													ci_aprobado = '".$ci_aprobado."',
													usuario = '".$login."',
													fechayhora = '".$fh."',
													oculto_dias = '".$oculto_dias."',
													oculto_disfrutados = '".$oculto_disfrutados."'
													where idhistorico_vacaciones = '".$idhistorico_vacaciones."';";
												$query = mysql_query($str,$conexion_db);
												if(!$query){
												$sms = mysql_error();
												echo $error;
												//echo $str;
												}else{
													$sms = 2;
													}
		}
	}//fin de modificar
echo $sms;
}
?>