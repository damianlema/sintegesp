<?
/**/

extract($_POST);

if($ejecutar == "validarFechas"){
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
	  	echo "La fecha de inicio ha de ser anterior a la fecha Actual";
	  }else{
	  	if($mesInicio <= $mesActual){
	  		$anios = $anioActual - $anioInicio;
	  	if($diaInicio <= $diaActual){
	  		$meses = $mesActual - $mesInicio;
	  		$dies = $diaActual - $diaInicio;
	  	}else{
	  		if($mesActual == $mesInicio){
	  		$anios = $anios - 1;
	  	}
	  	$meses = ($mesActual - $mesInicio - 1 + 12) % 12;
	  	$dies = $b-($diaInicio-$diaActual);
	  }
	  }else{
	  	$anios = $anioActual - $anioInicio - 1;
	  		if($diaInicio > $diaActual){
	  			$meses = $mesActual - $mesInicio -1 +12;
	  			$dies = $b - ($diaInicio-$diaActual);
	  		}else{
	  			$meses = $mesActual - $mesInicio + 12;
	  			$dies = $diaActual - $diaInicio;
	  		}
	  }
	  if($anios == 0 && $meses == 0 && $dies == 0){
	   $tiempo_srv = "0 Días";
	  }
	  if($anios == 0 && $meses == 0 && $dies != 0){
	   $tiempo_srv = "".$dies." Días ";
	  }
	  if($anios == 0 && $meses != 0 && $dies == 0){
	   $tiempo_srv = "".$meses." Meses";
	  }
	  if($anios != 0 && $meses == 0 && $dies == 0){
	   $tiempo_srv = "".$anios." Años";
	  }
	  if($anios != 0 && $meses == 0 && $dies != 0){
	   $tiempo_srv = "".$anios." Años, ".$dies." Días";
	  }
	  if($anios != 0 && $meses != 0 && $dies == 0){
	   $tiempo_srv = "".$anios."Años, ".$meses." Meses";
	  }
	  if($anios != 0 && $meses != 0 && $dies != 0){
	   $tiempo_srv = "".$anios." Años, ".$meses." Meses, ".$dies." Días";
	  }
	  	echo $tiempo_srv;
	  }
  
}
  
  
  
  
  
  
  
if($ejecutar == "boton"){
 	$estado_falso = $estado;
	if($estado_falso == 'La fecha de inicio ha de ser anterior a la fecha Actual' || $estado_falso == ''){
		$btn_style = "anterior";
	}else{
		$btn_style = "";
	}
	echo $btn_style;
}

	/**/
?>