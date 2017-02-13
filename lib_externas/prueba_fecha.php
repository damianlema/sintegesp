<? 
	function suma_fechas($fecha,$ndias){
		if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
			list($año,$mes,$dia)=split("/", $fecha);
		if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
			list($año,$mes,$dia)=split("-",$fecha);
			$nueva = mktime(0,0,0, $mes,$dia,$año) + $ndias * 24 * 60 * 60;
			$nuevafecha=date("Y-m-d",$nueva);
			return ($nuevafecha);  
	}
	
	function ultimoDiaMes($mes,$año){
      for ($dia=28;$dia<=31;$dia++)
         if(checkdate($mes,$dia,$año)) $fecha="$dia";
      return $fecha;
    }


$fecha_inicial = "2010-01-01";
$periodo = 2;
$listo = 0;
		while($listo < $periodo){
			$dias_por_periodo = 365 / $periodo;
			$fecha_fin = suma_fechas($fecha_inicial, $dias_por_periodo);
			
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
				echo $dia_semana;
				
				if($dia_semana == "Saturday"){
					$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-1);
				}else if($dia_semana == "Sunday"){
					$sugiere_pago = $año_fin."-".$mes_fin."-".($dia_fin-2);
				}else{
					$sugiere_pago = $fecha_fin;
				}
			}
			
				
			echo ($listo+1)." ---- La fecha Inicial es: ".$fecha_inicial." Y la Fecha Final es: ".$fecha_fin." FECHA QUE SUGIERE : ".$sugiere_pago."<br />";
			$fecha_inicial = $fecha_fin;
			$listo++;
		}








?> 
