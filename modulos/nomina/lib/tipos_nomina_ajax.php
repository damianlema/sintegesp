<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);




if($ejecutar == "registrarDatosGenerales"){
	$sql_insert = mysql_query("insert into tipo_nomina(titulo_nomina,
														activa,
														usuario,
														estacion,
														fechayhora,
														idtipo_documento,
														motivo_cuenta)VALUES('".$titulo_nomina."',
																			'".$activa."',
																			'".$login."',
																			'".$pc."',
																			'".$fh."',
																			'".$idtipo_documento."',
																			'".$motivo_cuenta."')")or die(mysql_error());
echo mysql_insert_id();
}




if($ejecutar == "guardarTipoFraccion"){
	echo $idtipo_nomina;
	$sql_actualizar = mysql_query("update tipo_nomina set tipo_fraccion = '".$valor."' where idtipo_nomina = '".$idtipo_nomina."'")or die(mysql_error());
}




if($ejecutar == "guardarNumeroFraccion"){
	$sql_actualizar = mysql_query("update tipo_nomina set numero_fracciones = '".$valor."' where idtipo_nomina = '".$idtipo_nomina."'")or die(mysql_error());
}





/*
if($ejecutar == "ingresarFraccion"){
	$sql_eliminar = mysql_query("delete from rango_fraccion_nomina where idtipo_nomina = '".$idtipo_nomina."'");
	$sql_ingresar = mysql_query("insert into rango_fraccion_nomina(idtipo_nimina,
																	numero)values('".$idtipo_nomina."',
																					'".$numero."')");
}

*/



if($ejecutar == "consultarFracciones"){

		if($consultar != 'si'){
			$sql_consulta = mysql_query("select * from tipo_nomina where idtipo_nomina = '".$idtipo_nomina."'")or die(mysql_error());
			$bus_consulta = mysql_fetch_array($sql_consulta);
			
			//echo $bus_consulta["numero_fracciones"];
			$sql_eliminar_rango = mysql_query("delete from rango_fraccion_nomina where idtipo_nomina = '".$idtipo_nomina."'")or die(mysql_error());
			for($k=0;$k<$bus_consulta["numero_fracciones"];$k++){
				$sql_ingresar = mysql_query("insert into rango_fraccion_nomina(idtipo_nomina, 
																						numero, 
																						valor)VALUES('".$idtipo_nomina."',
																									'".($k+1)."',
																									'')")or die(mysql_error());
		}
	}
	
	?>
    <table align="center" width="100%">
	<?
	$sql_consulta_rango = mysql_query("select * from rango_fraccion_nomina where idtipo_nomina = '".$idtipo_nomina."'");
	$i=1;
	while($bus_consulta_rango = mysql_fetch_array($sql_consulta_rango)){
		
		?>
		<tr>
        	<td align="center" width="33%" bgcolor="#FFFFCC"><?=$bus_consulta_rango["numero"]?></td>
            <td align="center" width="33%" bgcolor="#FFFFCC">
            	<table>
                <tr>
                <td>
            <input type="text" name="fraccion<?=$bus_consulta_rango["idrango_fraccion_nomina"]?>" id="fraccion<?=$bus_consulta_rango["idrango_fraccion_nomina"]?>" size="15" onblur="guardarValorFraccion(this.value, '<?=$bus_consulta_rango["idrango_fraccion_nomina"]?>', 'div_imagen_fraccion<?=$bus_consulta_rango["idrango_fraccion_nomina"]?>')" style="text-align:right" onclick="this.select()" value="<?=$bus_consulta_rango["valor"]?>">
            	</td>
                <td>
            		<div id="div_imagen_fraccion<?=$bus_consulta_rango["idrango_fraccion_nomina"]?>">&nbsp;&nbsp;&nbsp;</div>
            	</td>
                </tr>
                </table>
                </td>
            <td align="center" width="33%" bgcolor="#FFFFCC">&nbsp;</td>
        </tr>
		<?
        $i++;
	}
	?>
	</table>
	<?
}












if($ejecutar == "guardarValorFraccion"){
	$sql_actualizar = mysql_query("update rango_fraccion_nomina set valor = '".$valor."' where idrango_fraccion_nomina = '".$idrango_fraccion_nomina."'")or die(mysql_error());
	if($sql_actualizar and is_numeric($valor)){
		echo "exito";
	}else{
		echo "fallo";
	}
}





if($ejecutar == "guardarJornada"){
	$sql_consultar = mysql_query("select * from jornada_tipo_nomina where idtipo_nomina = '".$idtipo_nomina."'");
	$num_consulta = mysql_num_rows($sql_consultar);
	if($num_consulta == 0){
		$sql_insertar = mysql_query("insert into jornada_tipo_nomina (idtipo_nomina, ".$dia.")VALUES('".$idtipo_nomina."', '".$jornada."')")or die("INSERTANDO ".mysql_error());
	}else{
		$sql_actualizar = mysql_query("update jornada_tipo_nomina set ".$dia." = '".$jornada."' where idtipo_nomina = '".$idtipo_nomina."'")or die("ACTUALIZANDO: ".mysql_error());
	}
	
}





if($ejecutar=="consultarAnio"){
	$sql_anio = mysql_query("select * from periodos_nomina where anio = '".$anio."' and idtipo_nomina = '".$idtipo_nomina."'");
	$num_anio = mysql_num_rows($sql_anio);
	echo $num_anio;
}





if($ejecutar == "guardarPeriodo"){
	if($periodo_activo == "si"){
		$sql_con_activas = mysql_query("select * from periodos_nomina where idtipo_nomina = '".$idtipo_nomina."' 
									   									and periodo_activo = 'si' 
																		and idperiodos_nomina != '".$idperiodo_nomina."'");
		$num_con_activas = mysql_num_rows($sql_con_activas);
			if($num_con_activas == 0){
				$periodo_activo = "si";	
			}else{
				$periodo_activo = "no";	
			}
	}
	
	$sql_anio = mysql_query("select * from periodos_nomina where anio = '".$anio."' and idtipo_nomina = '".$idtipo_nomina."'");
	$num_anio = mysql_num_rows($sql_anio);
	if($idperiodo_nomina == 0 && $num_anio == 0){
		$sql_ingresar = mysql_query("insert into periodos_nomina(idtipo_nomina,
															 descripcion_periodo,
															 fecha_inicio,
															 anio,
															 numero_periodos,
															 dia_semana_comienza,
															 periodo_activo,
															 cierre_mes)VALUES('".$idtipo_nomina."',
															 						'".$descripcion_periodo."',
																					'".$fecha_inicio."',
																					'".$anio."',
																					'".$periodo."',
																					'".$dia_semana_comienza."',
																					'".$periodo_activo."',
																					'".$cierre_mes."')")or die(mysql_error());
	
		echo "exito|.|".mysql_insert_id();	
	}else{
		$sql_consulta_periodo = mysql_query("select * from 
														generar_nomina gn,
														periodos_nomina pn,
														rango_periodo_nomina rpn
															where 
														gn.idperiodo = rpn.idrango_periodo_nomina
														and rpn.idperiodo_nomina = pn.idperiodos_nomina
														and pn.idperiodos_nomina = '".$idperiodo_nomina."'")or die(mysql_error());
		$num_consulta_periodo = mysql_num_rows($sql_consulta_periodo);
		
		if($num_consulta_periodo == 0){
		$sql_eliminar = mysql_query("delete from rango_periodo_nomina where idperiodo_nomina  = '".$idperiodo_nomina."'");
		
		
		$sql_actualizar = mysql_query("update periodos_nomina set idtipo_nomina = '".$idtipo_nomina."',
															 descripcion_periodo = '".$descripcion_periodo."',
															 fecha_inicio = '".$fecha_inicio."',
															 anio = '".$anio."',
															 numero_periodos = '".$periodo."',
															 dia_semana_comienza='".$dia_semana_comienza."',
															 periodo_activo = '".$periodo_activo."',
															 cierre_mes = '".$cierre_mes."'
															 where idperiodos_nomina = '".$idperiodo_nomina."'")or die(mysql_error());
			echo "exito|.|".$idperiodo_nomina;
		}else{
			echo "existe_generar|.|".$idperiodo_nomina;	
		}
	}
	
	/*if(is_numeric($periodo)){
		$sql_actualizar = mysql_query("update tipo_nomina set numero_periodos = '".$periodo."', descripcion_periodo = '".$descripcion_periodo."' where idtipo_nomina ='".$idtipo_nomina."'")or die(mysql_error());
	}else{
		echo "fallo";
	}*/
}







if($ejecutar == "consultarPestanas"){
	$sql_consultar = mysql_query("select * from periodos_nomina where idtipo_nomina = '".$idtipo_nomina."'");
	?>
	<table style="margin-left:3px; margin-right:3px; margin-top:3px" cellpadding="5">
        <tr>
  	      <?
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
			$id = "id".$bus_consultar["idperiodos_nomina"];
			?>
			<td id="id<?=$bus_consultar["idperiodos_nomina"]?>" align="center" bgcolor="<? if($idpestana == $id){echo "#EAEAEA";}else{echo "#FFFFCC";}?>" onMouseOver="cambiarColor(this.id, '#EAEAEA')" onMouseOut="cambiarColor(this.id, '#FFFFCC')" style="cursor:pointer" onclick="document.getElementById('pestana_seleccionada').value = this.id, seleccionarPestana('<?=$bus_consultar["idperiodos_nomina"]?>')"><?=$bus_consultar["anio"]?></td>
			<?  
			}
		  ?>
        </tr>
    </table>
	<?
}






if($ejecutar=="seleccionarPestana"){
	$sql_consulta= mysql_query("select * from periodos_nomina where idperiodos_nomina ='".$idperiodos_nomina."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	
	echo $bus_consulta["descripcion_periodo"]."|.|".
	$bus_consulta["fecha_inicio"]."|.|".
	$bus_consulta["anio"]."|.|".
	$bus_consulta["numero_periodos"]."|.|".
	$bus_consulta["dia_semana_comienza"]."|.|".
	$bus_consulta["periodo_activo"]."|.|".
	$bus_consulta["cierre_mes"];
}




if($ejecutar == "calcularPeriodo"){
	
	
	//$sql_eliminar = mysql_query("delete from rango_periodo_nomina where idtipo_nomina = '".$idtipo_nomina."'");



	function suma_fechas($fecha,$ndias, $sumar){
		if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
			list($año,$mes,$dia)=split("/", $fecha);
		if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
			list($año,$mes,$dia)=split("-",$fecha);
			if($mes == 11 and $sumar == "si" and ($dia == "02" || $dia == "03" || $dia == "04" || $dia == "05" || $dia == "06" || $dia == "07")){
				$ndias += 1;
			}
			$nueva = mktime(0,0,0, $mes,$dia,$año) + $ndias * 24 * 60 * 60;
			$nuevafecha=date("Y-m-d",$nueva);
			return ($nuevafecha);  
	}
	
	function ultimoDiaMes($mes,$año){
      for ($dia=28;$dia<=31;$dia++)
         if(checkdate($mes,$dia,$año)) $fecha="$dia";
      return $fecha;
    }
	
	function guardarRango($idperiodo, $numero, $desde, $hasta, $sugiere_pago){
		$sql_ingresar = mysql_query("insert into rango_periodo_nomina(idperiodo_nomina,
																	numero,
																	desde,
																	hasta,
																	sugiere_pago,
																	semana)VALUES('".$idperiodo."',
																						'".$numero."',
																						'".$desde."',
																						'".$hasta."',
																						'".$sugiere_pago."',
																						'".$numero."')");
	}
	
	
	
	function getDiaSemana($fecha) {
		$fecha=str_replace("/","-",$fecha);
		list($anio,$mes,$dia)=explode("-",$fecha);
		$dia = (((mktime ( 0, 0, 0, $mes, $dia, $anio) - mktime ( 0, 0, 0, 7, 17, $anio))/(60*60*24))+700000) % 7;
		if ($dia == 0) $dia = 7;
		return $dia;
	}
	
	$fecha_inicial = $fecha_inicio;
	if($periodo == 52){
			$dia_fecha_inicial = 1;
			$MiTimeStamp = mktime(0,0,0,01,$dia_fecha_inicial,$anio);
			$dia_semana = date("l",$MiTimeStamp);
			while($dia_semana != 'Monday'){
				$dia_fecha_inicial++;
				$MiTimeStamp = mktime(0,0,0,01,$dia_fecha_inicial,$anio);
				$dia_semana = date("l",$MiTimeStamp);
			}
		if($dia_fecha_inicial < 10){
			$dia_fecha_inicial = "0".$dia_fecha_inicial;
		}
		$fecha_inicial = $fecha_inicio;
		
		$listo = 0;
		while($listo < $periodo){
			
			
			
			//echo "FECHA FIN: ".$fecha_inicial;
			//echo $fecha_inicial." <br /> ";
			$a_fecha_inicial = explode("-",$fecha_inicial);
			if($a_fecha_inicial[2] < 10){
				$a_fecha_inicial[2] = "0".$a_fecha_inicial[2];
			}
			
			
			//echo $dia_semana_comienza;
			
			
			$fecha_inicial = $a_fecha_inicial[0]."-".$a_fecha_inicial[1]."-".$a_fecha_inicial[2];
			
			$fecha_fin = suma_fechas($fecha_inicial, 6, 'si');
			
			$dia_numero_semana = getDiaSemana($a_fecha_inicial[0]."-".$a_fecha_inicial[1]."-".$a_fecha_inicial[2]);
			
			$MiTimeStamp = mktime(0,0,0,$a_fecha_inicial[1],$a_fecha_inicial[2],$a_fecha_inicial[0]);
			$dia_semana = date("l",$MiTimeStamp);
			
			if($dia_semana == "Saturday"){
				$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-1);
			}else if($dia_semana == "Sunday"){
				$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-2);
			}else{
				$sugiere_pago = $fecha_fin;
			}
			
			
			
			if(!$entro){
				if($dia_numero_semana < $dia_semana_comienza){
					
					$dias = $dia_semana_comienza - $dia_numero_semana;
					$fecha_fin = suma_fechas($fecha_inicio, ($dias-1), 'no');
					$entro = true;
				}else if($dia_numero_semana > $dia_semana_comienza){
					//echo "AQUI";
					$dias = 7-($dia_numero_semana - $dia_semana_comienza );
					//echo $dias;
					$fecha_fin = suma_fechas($fecha_inicio, ($dias-1), 'no');
					$entro = true;
				}
			}
			
			
			
			$partes_fecha_fin = explode("-", $fecha_fin);
			if($partes_fecha_fin[1] == 12 && $partes_fecha_fin[2] > (31-7)){
				guardarRango($idperiodo, ($listo+1), $fecha_inicial, $fecha_fin, $sugiere_pago);
				$fecha_inicial = suma_fechas($fecha_fin, 1, 'no');
				$fecha_fin = $partes_fecha_fin[0]."-".$partes_fecha_fin[1]."-31";
				
				$MiTimeStamp = mktime(0,0,0,$partes_fecha_fin[1],$partes_fecha_fin[2],$partes_fecha_fin[0]);
				$dia_semana = date("l",$MiTimeStamp);
				
				if($dia_semana == "Saturday"){
					$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-1);
				}else if($dia_semana == "Sunday"){
					$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-2);
				}else{
					$sugiere_pago = $fecha_fin;
				}
				
				guardarRango($idperiodo, ($listo+2), $fecha_inicial, $fecha_fin, $sugiere_pago);
				$listo = $periodo;
			}else{
				guardarRango($idperiodo, ($listo+1), $fecha_inicial, $fecha_fin, $sugiere_pago);
				$p_fechas = explode("-", $fecha_fin);
				$MiTimeStamp = mktime(0,0,0,$p_fechas[1],$p_fechas[2],$p_fechas[0]);
				$dia_semana = date("l",$MiTimeStamp);
				
				if($dia_semana == "Saturday"){
					$fecha_inicial = suma_fechas($fecha_fin, 2, 'no');
				}else if($dia_semana == "Friday"){
					$fecha_inicial = suma_fechas($fecha_fin, 3, 'no');
				}else{
					$fecha_inicial = suma_fechas($fecha_fin, 1, 'no');
				}
			}
			
			
			
			$listo++;
			
						//unset($p_fechas);
			
			
			//echo "FECHA INICIAL: ".$fecha_fin."<br />";
			//echo $anio."-12-28";
			
		}
	}else if($periodo == 24){
		echo $cierre_mes;
		$listo = 0;
		while($listo < $periodo){
			$explo_fecha_inicio = explode("-", $fecha_inicial);
			
			if(($listo+1) % 2 == 0){
				//echo "Alla";
				if($explo_fecha_inicio[1] == "02"){
					$ultimo_dia = ultimoDiaMes($explo_fecha_inicio[1],$explo_fecha_inicio[0]);
					$fecha_fin = $explo_fecha_inicio[0]."-".$explo_fecha_inicio[1]."-".$ultimo_dia;
					
					list($año_fin,$mes_fin,$dia_fin)=split("-",$fecha_fin);
			
					$MiTimeStamp = mktime(0,0,0,$mes_fin,$dia_fin,$año_fin);
					$dia_semana = date("l",$MiTimeStamp);
					
					if($dia_semana == "Saturday"){
						$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-1);
					}else if($dia_semana == "Sunday"){
						$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-2);
					}else{
						$sugiere_pago = $fecha_fin;
					}
					
					$listo++;
					
					guardarRango($idperiodo, ($listo), $fecha_inicial, $fecha_fin, $sugiere_pago);
					$fecha_inicial = $explo_fecha_inicio[0]."-".($explo_fecha_inicio[1]+1)."-01";
				}else{
					$ultimo_dia = ultimoDiaMes($explo_fecha_inicio[1],$explo_fecha_inicio[0]);
					if($ultimo_dia == 30){
						$fecha_fin = $explo_fecha_inicio[0]."-".$explo_fecha_inicio[1]."-30";	
					}else{
						$fecha_fin = $explo_fecha_inicio[0]."-".$explo_fecha_inicio[1]."-".$cierre_mes;	
					}
					
					
					list($año_fin,$mes_fin,$dia_fin)=split("-",$fecha_fin);
			
					$MiTimeStamp = mktime(0,0,0,$mes_fin,$dia_fin,$año_fin);
					$dia_semana = date("l",$MiTimeStamp);
					
					if($dia_semana == "Saturday"){
						$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-1);
					}else if($dia_semana == "Sunday"){
						$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-2);
					}else{
						$sugiere_pago = $fecha_fin;
					}
					
					$listo++;
					
					guardarRango($idperiodo, ($listo), $fecha_inicial, $fecha_fin, $sugiere_pago);
					$fecha_inicial = $explo_fecha_inicio[0]."-".($explo_fecha_inicio[1]+1)."-01";
					
					
		
					if($fecha_fin == $anio."-12-".$cierre_mes){
						$listo = $periodo;
					}
				}		
			}else{
				//echo "AQUI";
				$fecha_fin = $explo_fecha_inicio[0]."-".$explo_fecha_inicio[1]."-15";
				
				list($año_fin,$mes_fin,$dia_fin)=split("-",$fecha_fin);
			
					$MiTimeStamp = mktime(0,0,0,$mes_fin,$dia_fin,$año_fin);
					$dia_semana = date("l",$MiTimeStamp);
					
					if($dia_semana == "Saturday"){
						$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-1);
					}else if($dia_semana == "Sunday"){
						$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-2);
					}else{
						$sugiere_pago = $fecha_fin;
					}
					
					$listo++;
		
					if($fecha_fin == $anio."-12-".$cierre_mes){
						$listo = $periodo;
					}
					
					guardarRango($idperiodo, ($listo), $fecha_inicial, $fecha_fin, $sugiere_pago);
					$fecha_inicial = $explo_fecha_inicio[0]."-".$explo_fecha_inicio[1]."-16";
			}
			
			
			
			
			
		}
	}else if($periodo == 12){
		$listo = 0;
		while($listo < $periodo){
			list($año,$mes,$dia)=split("-",$fecha_inicial);
			$ultimo_dia = ultimoDiaMes($mes,$año);
			
			
			
			if($mes == 02){
				$fecha_inicial = $año."-".$mes."-01";
				$fecha_fin = $año."-".$mes."-".$ultimo_dia;	
				list($año_fin,$mes_fin,$dia_fin)=split("-",$fecha_fin);
			
				$MiTimeStamp = mktime(0,0,0,$mes_fin,$dia_fin,$año_fin);
				$dia_semana = date("l",$MiTimeStamp);
				
				if($dia_semana == "Saturday"){
					$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-1);
				}else if($dia_semana == "Sunday"){
					$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-2);
				}else{
					$sugiere_pago = $fecha_fin;
				}
				
				guardarRango($idperiodo, ($listo+1), $fecha_inicial, $fecha_fin, $sugiere_pago);
				$fecha_inicial = $año_fin."-".($mes_fin+1)."-01";
				$listo++;
				
				if($fecha_fin == $anio."-12-".$cierre_mes){
					$listo = $periodo;
				}
			}else{
				$fecha_inicial = $año."-".$mes."-01";
				//echo $ultimo_dia;
				if($ultimo_dia == 30){
					$fecha_fin = $año."-".$mes."-30";	
				}else{
					//echo $cierre_mes;
					$fecha_fin = $año."-".$mes."-".$cierre_mes;	
				}
				
				//$fecha_fin = $año."-".$mes."-30";	
				list($año_fin,$mes_fin,$dia_fin)=split("-",$fecha_fin);
			
				$MiTimeStamp = mktime(0,0,0,$mes_fin,$dia_fin,$año_fin);
				$dia_semana = date("l",$MiTimeStamp);
				
				if($dia_semana == "Saturday"){
					$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-1);
				}else if($dia_semana == "Sunday"){
					$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-2);
				}else{
					$sugiere_pago = $fecha_fin;
				}
				
				guardarRango($idperiodo, ($listo+1), $fecha_inicial, $fecha_fin, $sugiere_pago);
				$fecha_inicial = $año_fin."-".($mes_fin+1)."-01";
				$listo++;
				
				if($fecha_fin == $anio."-12-".$cierre_mes){
					$listo = $periodo;
				}
			}
			

			
			
		}
	}
	/*else{
		$listo = 0;
		while($listo < $periodo){
			$dias_por_periodo = 365 / $periodo;
			$fecha_fin = suma_fechas($fecha_inicial, $dias_por_periodo,'no');
			
			if($periodo != 365){
				list($año_fin,$mes_fin,$dia_fin)=split("-",$fecha_fin);
				
				if($dia_fin == 16){
					$dia_fin = 15;
					$fecha_fin = $año_fin."-".$mes_fin."-".$dia_fin;
				}
				if($dia == 15){
					$ultimo_dia = ultimoDiaMes($mes,$año);
					$fecha_fin = $año."-".$mes."-".$ultimo_dia;
				}
				list($año_fin,$mes_fin,$dia_fin)=split("-",$fecha_fin);
				
				$MiTimeStamp = mktime(0,0,0,$mes_fin,$dia_fin,$año_fin);
				$dia_semana = date("l",$MiTimeStamp);
				//echo $dia_semana;
				
				if($dia_semana == "Saturday"){
						$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-1);	
				}else if($dia_semana == "Sunday"){
						$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-2);
				}else{
					$sugiere_pago = $fecha_fin;
				}
			} 
			
			
			guardarRango($idperiodo, ($listo+1), $fecha_inicial, $fecha_fin, $sugiere_pago);
			$fecha_inicial = $fecha_fin;
			$listo++;
			
			if($fecha_fin == $anio."-12-31" || $fecha_fin == $anio."-12-30"){
				$listo = $periodo;
			}
		}
	}*/
}





if($ejecutar == "mostrarListaPeriodos"){
	$sql_consultar = mysql_query("select * from rango_periodo_nomina where idperiodo_nomina = '".$idperiodo_nomina."' order by idrango_periodo_nomina");
	?>
	<table align="center" width="100%">
	<?
	while($bus_consultar = mysql_fetch_array($sql_consultar)){
		$sql_periodo_nomina = mysql_query("select * from periodos_nomina where idperiodos_nomina = '".$idperiodo_nomina."'");
		$bus_periodo_nomina = mysql_fetch_array($sql_periodo_nomina);
		
		?>
		<tr>
        	
			<td width="24%" align="center" bgcolor="#FFFFCC">
            <?
            if($bus_periodo_nomina["numero_periodos"] == '52'){
			?>
            <input type="text" name="semana_<?=$bus_consultar["idrango_periodo_nomina"]?>" id="semana" value="<?=$bus_consultar["semana"]?>" size="3" onclick="select()" onblur="actualizarSemanas(this.name, this.value, '<?=$bus_consultar["idrango_periodo_nomina"]?>')">
			<?
			}
			?>
            </td>
            <td width="24%" align="center" bgcolor="#FFFFCC"><?=$bus_consultar["numero"]?></td>
            <td width="25%" align="center" bgcolor="#FFFFCC"><?=$bus_consultar["desde"]?></td>
            <td width="27%" align="center" bgcolor="#FFFFCC"><?=$bus_consultar["hasta"]?></td>
            <td width="24%" align="center" bgcolor="#FFFFCC"><?=$bus_consultar["sugiere_pago"]?></td>
            <td width="24%" align="center" bgcolor="#FFFFCC"><a onclick="abrirCerrarCuadros('<?=$bus_consultar["idrango_periodo_nomina"]?>')" href="javascript:;"><img src="imagenes/abrir.gif" border="0" id="imagenAbrir_<?=$bus_consultar["idrango_periodo_nomina"]?>"></a></td>
        </tr>
        <?
        	/*echo "select
										relacion_concepto_trabajador.idrelacion_concepto_trabajador,
										relacion_concepto_trabajador.tabla,
										relacion_concepto_trabajador.idconcepto 
											from
										periodos_nomina,
										tipo_nomina,
										relacion_concepto_trabajador
											where 
										periodos_nomina.idperiodos_nomina = '".$idperiodo_nomina."'
										and tipo_nomina.idtipo_nomina = periodos_nomina.idtipo_nomina
										and relacion_concepto_trabajador.idtipo_nomina = tipo_nomina.idtipo_nomina
										group by relacion_concepto_trabajador.tabla, relacion_concepto_trabajador.idconcepto";*/

        $sql_conceptos = mysql_query("select
										relacion_concepto_trabajador.idrelacion_concepto_trabajador,
										relacion_concepto_trabajador.tabla,
										relacion_concepto_trabajador.idconcepto 
											from
										periodos_nomina,
										tipo_nomina,
										relacion_concepto_trabajador
											where 
										periodos_nomina.idperiodos_nomina = '".$idperiodo_nomina."'
										and tipo_nomina.idtipo_nomina = periodos_nomina.idtipo_nomina
										and relacion_concepto_trabajador.idtipo_nomina = tipo_nomina.idtipo_nomina
										group by relacion_concepto_trabajador.tabla, relacion_concepto_trabajador.idconcepto")or die(mysql_error());
		?>
        <tr>
        	<td colspan="4">
        		<table style="display:none" id="div_<?=$bus_consultar["idrango_periodo_nomina"]?>">
                <tr>
                <?
                $i=0;
				
				while($bus_conceptos = mysql_fetch_array($sql_conceptos)){
				
				if($bus_conceptos["tabla"] == "constantes_nomina"){
					$cam = "afecta";
				}else{
					$cam = "tipo_concepto";
				}
				
				$sql_consulta = mysql_query("select * from ".$bus_conceptos["tabla"]." where id".$bus_conceptos["tabla"]." = '".$bus_conceptos["idconcepto"]."' and (".$cam." = '2' or ".$cam." = '3' or ".$cam." = '5')")or die(mysql_error());
		$bus_consulta = mysql_fetch_array($sql_consulta);
		if($bus_consulta["descripcion"] != ""){
				
				$sql_relacion = mysql_query("select * from relacion_conceptos_periodos 
													where idtipo_nomina = '".$idtipo_nomina."'
													and idconcepto = '".$bus_conceptos["idconcepto"]."'
													and idperiodo = '".$bus_consultar["idrango_periodo_nomina"]."'")or die(mysql_error());
				$num_relacion = mysql_num_rows($sql_relacion);
				$bus_relacion = mysql_fetch_array($sql_relacion);
				
				
				?>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:10px">
                <input type="checkbox" name="check_<?=$bus_consultar["idrango_periodo_nomina"]?>_<?=$bus_conceptos["idconcepto"]?>" onclick="activarDesactivarCampos('text_<?=$bus_consultar["idrango_periodo_nomina"]?>_<?=$bus_conceptos["idconcepto"]?>', '<?=$bus_consultar["idrango_periodo_nomina"]?>', '<?=$bus_conceptos["idconcepto"]?>')" <? if($num_relacion > 0){echo "checked";}?>>
                <?=$bus_consulta["descripcion"]?>
                </td>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                    <td>
                <input type="text" <? if($num_relacion == 0){echo "disabled='disabled'";}?> id="text_<?=$bus_consultar["idrango_periodo_nomina"]?>_<?=$bus_conceptos["idconcepto"]?>" size="10" maxlength="3" style="text-align:right" onblur="registrarConcepto('<?=$bus_consultar["idrango_periodo_nomina"]?>', '<?=$bus_conceptos["idconcepto"]?>', this.value)" onclick="select()" value="<?=$bus_relacion["valor"]?>">
                    </td>
                    <td style="font-size:9px; font-weight:bold">
                    &nbsp;%
                    </td>
                    </tr>
                    </table>
                </td>
				<?
				if($i == 1){
					?>
					</tr>
                    <tr>
					<?
					$i=0;
				}else{
					$i++;
				}
				}
			}
				?>
                </tr>
                </table>
        	</td>
        </tr>
		<?
		
	}
	?>
	</table>
	<?
}






if($ejecutar == "consultarTipoNomina"){
	//echo $idtipo_nomina;
	$sql_consulta = mysql_query("select * from tipo_nomina where idtipo_nomina = '".$idtipo_nomina."'")or die(mysql_error());
	$bus_consulta = mysql_fetch_array($sql_consulta);
	
	echo $bus_consulta["titulo_nomina"]."|.|".
		$bus_consulta["anio"]."|.|".
		$bus_consulta["fecha_inicio"]."|.|".
		$bus_consulta["activa"]."|.|".
		$bus_consulta["numero_periodos"]."|.|".
		$bus_consulta["descripcion_periodo"]."|.|".
		$bus_consulta["tipo_fraccion"]."|.|".
		$bus_consulta["numero_fracciones"]."|.|".
		$bus_consulta["idtipo_documento"]."|.|".
		$bus_consulta["motivo_cuenta"];
}




if($ejecutar == "consultarJornadas"){
	$sql_consulta = mysql_query("select * from jornada_tipo_nomina where idtipo_nomina = '".$idtipo_nomina."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	
	echo $bus_consulta["lunes"]."|.|".
			$bus_consulta["martes"]."|.|".
			$bus_consulta["miercoles"]."|.|".
			$bus_consulta["jueves"]."|.|".
			$bus_consulta["viernes"]."|.|".
			$bus_consulta["sabado"]."|.|".
			$bus_consulta["domingo"];
}




if($ejecutar == "actualizarDatosGenerales"){
	$sql_actualizar = mysql_query("update tipo_nomina set titulo_nomina = '".$titulo_nomina."',
															anio = '".$anio."',
															fecha_inicio = '".$fecha_inicio."',
															activa = '".$activa."',
															idtipo_documento = '".$idtipo_documento."',
															motivo_cuenta = '".$motivo_cuenta."'
															where idtipo_nomina = '".$idtipo_nomina."'")or die(mysql_error());
}





if($ejecutar == "eliminarTipoNomina"){
	$sql_consulta = mysql_query("select * from relacion_tipo_nomina_trabajador where idtipo_nomina = '".$idtipo_nomina."'");
	$num_consulta = mysql_num_rows($sql_consulta);
	if($num_consulta > 0){
		echo "trabajadores_asociados";
	}else{
		$sql_eliminar = mysql_query("delete from tipo_nomina where idtipo_nomina = '".$idtipo_nomina."'");
		$sql_eliminar = mysql_query("delete from rango_fraccion_nomina where idtipo_nomina = '".$idtipo_nomina."'");
		$sql_eliminar = mysql_query("delete from rango_periodo_nomina where idtipo_nomina = '".$idtipo_nomina."'");
		$sql_eliminar = mysql_query("delete from jornada_tipo_nomina where idtipo_nomina = '".$idtipo_nomina."'");
		
		if($sql_eliminar){
			echo "exito";
		}
	}
	
	
}





if($ejecutar == "registrarConcepto"){
	$sql_consulta = mysql_query("select * from relacion_conceptos_periodos 
														where idtipo_nomina = '".$idtipo_nomina."'
														and idconcepto = '".$idconcepto."'
														and idperiodo = '".$idperiodo."'");
	$num_consulta = mysql_num_rows($sql_consulta);													
	if($num_consulta == 0){
		$sql_ingresar = mysql_query("insert into relacion_conceptos_periodos(idtipo_nomina,
																			idconcepto,
																			idperiodo,
																			valor)VALUES('".$idtipo_nomina."',
																						'".$idconcepto."',
																						'".$idperiodo."',
																						'".$valor."')");
	}else{
		$sql_actualizar = mysql_query("update relacion_conceptos_periodos 
														set valor = '".$valor."'
														where
														idtipo_nomina = '".$idtipo_nomina."'
														and idconcepto = '".$idconcepto."'
														and idperiodo = '".$idperiodo."'")or die(mysql_error());
														
	}
}



if($ejecutar == "eliminarConcepto"){
	$sql_eliminar = mysql_query("delete from relacion_conceptos_periodos 
										where 
										idtipo_nomina = '".$idtipo_nomina."'
										and idconcepto = '".$idconcepto."'
										and idperiodo = '".$idperiodo."'")or die(mysql_error());
}




if($ejecutar == "actualizarSemanas"){
	$sql_actualizar = mysql_query("update rango_periodo_nomina set semana = '".$valor."' where idrango_periodo_nomina = '".$idrango_periodo_nomina."'")or die(mysql_error());
}
?>