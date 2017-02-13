<?php
extract($_POST);
include("../../../conf/conex.php");
$conexion_db = Conectarse();
if($ejecutar == "validarFechas"){
	//echo "entro en total dias";
	$fechaInicio = $desde;
   $fechaActual = $hasta;
   
   $diaActual = substr($fechaActual, 8, 10);  
   $mesActual = substr($fechaActual, 5, 7);  
   $anioActual = substr($fechaActual, 0, 4);
     
   $diaInicio = substr($fechaInicio, 8, 10);  
   $mesInicio = substr($fechaInicio, 5, 7);  
   $anioInicio = substr($fechaInicio, 0, 4);  
   
	$b = 0;
	$mes = $mesInicio-1;

	if($mes==2){
		if(($anioActual%4==0 && $anioActual%100!=0) || $anioActual%400==0){
			$b = 29;
		}else{
			$b = 28;
		}
	}else if($mes<=7){
		if($mes==0){
		$b = 31;
		}else if($mes%2==0){
	  		$b = 30;
	  	}else{
	  		$b = 31;
	  	}
	 }else if($mes>7){
	 	if($mes%2==0){
	  		$b = 31;
	  	}else{
	  		$b = 30;
	  	}
	  }
	  
	  if(($anioInicio>$anioActual) || ($anioInicio==$anioActual && $mesInicio>$mesActual) || 
	  ($anioInicio==$anioActual && $mesInicio == $mesActual && $diaInicio>$diaActual)){
	  	$x = "La fecha de inicio ha de ser anterior a la fecha Actual";
	  }
	//---------------------------------------------
	if($x == "La fecha de inicio ha de ser anterior a la fecha Actual"){
		echo $x;
	}else{
	
		list($ad, $md, $dd)=SPLIT( '[/.-]', $desde);
		list($ah, $mh, $dh)=SPLIT( '[/.-]', $hasta);
		
		//	Calculo timestam de las dos fechas
		$timestampd = mktime(0, 0, 0, $md, $dd, $ad);
		$timestamph = mktime(0, 0, 0, $mh, $dh, $ah);
		
		//	Resto a una fecha la otra
		$segundos_diferencia = $timestampd - $timestamph;
		
		//convierto segundos en días
		$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
		
		//obtengo el valor absoulto de los días (quito el posible signo negativo)
		$dias_diferencia = abs($dias_diferencia);
		
		//quito los decimales a los días de diferencia
		$dias_diferencia = floor($dias_diferencia); 
		
		echo $dias_diferencia;
	}
}

if($ejecutar == "validarHoras"){
	if($hr_inicio != ""){
		//echo "Entro en hr_inicio";
		$hora = substr($hr_inicio,0,2);
		
		if($hora > 12){
			$hr_flag = 0;
		}else{
			$hr_flag = 1;
		}
		
		$dos_puntos = substr($hr_inicio,2,1);
		
		if($dos_puntos == ":"){
			$dos_flag = 1;
		}else{
			$dos_flag = 0;
		}
		
		$minutos = substr($hr_inicio,3,2);
		
		if($minutos > 59){
			$min_flag = 0;
		}else{
			$min_flag = 1;
		}
		
		$am_pm = substr($hr_inicio,6,2);
		
		if($am_pm == "pm"){
			$am_pm_flag = 1;
		}else{
			if($am_pm == "am"){
				$am_pm_flag =1;
			}else{
				$am_pm_flag = 0;
			}
		}
		
		if($hr_flag == 1 && $dos_flag == 1 && $min_flag == 1 && $am_pm_flag == 1){
			$valida_all = 1;
			echo $valida_all;
		}else{
			$valida_all = 0;
			echo $valida_all;
		}
	}
}
if($ejecutar == "total_Horas"){

	//desfragmentamos la hora de incio en varios objetos	
	$hora_inicio = substr($hr_inicio,0,2);
	$dos_puntos_inicio = substr($hr_inicio,2,1);
	$minutos_inicio = substr($hr_inicio,3,2);
	$am_pm_inicio = substr($hr_inicio,6,2);
	
	//desfragmentamos la hora de culminacion en varios objetos	
	$hora_culminacion = substr($hr_culminacion,0,2);
	$dos_puntos_culminacion = substr($hr_culminacion,2,1);
	$minutos_culminacion = substr($hr_culminacion,3,2);
	$am_pm_culminacion = substr($hr_culminacion,6,2);
	
	//calculamos el tiempo restante del dia de inicio
	
	if($am_pm_inicio == "am"){
		$residuo_horas_inicio = 24 - $hora_inicio;
		
		if($minutos_inicio > 0){
			$residuo_minutos_inicio = 60 - $minutos_inicio;
			$residuo_horas_inicio = $residuo_horas_inicio - 1;
		}
	}
	if($am_pm_inicio == "pm"){
		
		$residuo_horas_inicio = 12 - $hora_inicio;
		if($minutos_inicio > 0){
			$residuo_minutos_inicio = 60 - $minutos_inicio;
			$residuo_horas_inicio = $residuo_horas_inicio - 1;
		}
	}
	//echo $residuo_horas_inicio;
	
	//calculamos el tiempo restante del ultimo dia
	
	if($am_pm_culminacion == "am"){
		//echo "am";
		$residuo_horas_culminacion = $hora_culminacion;
		$residuo_minutos_culminacion = $minutos_culminacion;
	}
	if($am_pm_culminacion == "pm"){
		//echo "pm";
		$residuo_horas_culminacion = 12 + $hora_culminacion;
		$residuo_minutos_culminacion = $minutos_culminacion;
	}
	
	
	//echo $hora_culminacion;
	//echo $residuo_horas_culminacion;
	//-----------------------------------------
	$total_horas = $residuo_horas_inicio + $residuo_horas_culminacion;
	$total_minutos = $residuo_minutos_inicio + $residuo_minutos_culminacion;
	$total_dias = $total_dias - 1;
	$horas_por_dia = $total_dias * 24;
	$total_horas = $total_horas + $horas_por_dia;
	
	if($total_minutos > 59){
		$total_horas = $total_horas + 1;
		$total_minutos = $total_minutos - 60;
		
		if($total_minutos > 59){
			$total_horas = $total_horas + 1;
			$total_minutos = $total_minutos - 60;
		}
	}
	
	if($total_minutos > 0){
		if($total_horas <= 0){
			echo "Selecione horas validas";
		}else{
			echo "Horas Restantes: ".$total_horas." - Minutos Restantes: ".$total_minutos;
		}
	}else{
		if($total_horas <= 0){
			echo "Selecione horas validas";
		}else{
			echo "Horas Restantes: ".$total_horas;
		}
	}
	//echo "Horas restantes: ".$total_horas." - minutos restantes: ".$total_minutos;
	//echo "Restan del primer dia: ".$residuo_horas_inicio." horas con: ".$residuo_minutos_inicio." minutos ";
	//echo "Restan del ultimo dia: ".$residuo_horas_culminacion." horas con: ".$residuo_minutos_culminacion." minutos";
}

if($ejecutar == "checkbox"){
	echo $valor;
}

if($ejecutar == "llenargrilla"){
	$str_select_permisos = "select * from historico_permisos where idtrabajador = '".$idtrabajador."';";
	$result_select_trabajador = mysql_query($str_select_permisos,$conexion_db);
	
	?>
		<table border="0" class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
        	<thead>
            <tr>
            	<td width="26%" class="Browse" align="center">Fecha de Inicio</td>
                <td width="29%" class="Browse" align="center">Fecha de Culminaci&oacute;n</td>
                <td width="15%" class="Browse" align="center">Justificado</td>
                <td width="19%" class="Browse" align="center">Remunerado</td>
                <td width="11%" class="Browse" align="center">Acci&oacute;n</td>
            </tr>
            </thead>
            <? while($array_select = mysql_fetch_array($result_select_trabajador)){?>
            	<tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="left" class="Browse"><div align="center">
                  <? list($fecha_inicio, $hora) = split('[ ]', $array_select["fecha_inicio"]); ?>
				  <?=$fecha_inicio?>
                </div></td>
                <td align="left" class="Browse"><div align="center">
	              <? list($fecha_culminacion, $hora) = split('[ ]', $array_select["fecha_culminacion"]); ?>
                  <?=$fecha_culminacion?>
                </div></td>
                <? if($array_select["justificado"] == 1){ $justificado = "Si"; }else{ $justificado = "No";}?>
                <td align="left" class="Browse"><div align="center">
                  <?=$justificado?>
                </div></td>
                <? if($array_select["remunerado"] == 1){ $remunerado = "Si"; }else{ $remunerado = "No";}?>
                <td align="left" class="Browse"><div align="center">
                  <?=$remunerado?>
                </div></td>
                <td class="Browse" align="center">
                  <div align="center"><a href="principal.php?accion=21&modulo=1&idtrabajador=<?=$idtrabajador?>&accion_form=modificar&desc_bono=<?=$array_select["descuento_bono_alimentacion"]?>&justificado=<?=$array_select["justificado"]?>&remunerado=<?=$array_select["remunerado"]?>&idhistorico=<?=$array_select["idhistorico_permisos"]?>"><img
                 src="imagenes/modificar.png" style="cursor:pointer" alt='Modificar' title='Modificar'></a>                    </div></td>
          </tr>
            <? } ?>
        </table>
<? 
}
?>