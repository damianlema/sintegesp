<?
include("conf/conex.php");
Conectarse();
		function edad($edad){
			list($anio,$mes,$dia) = explode("-",$edad);
			$anio_dif = date("Y") - $anio;
			$mes_dif = date("m") - $mes;
			$dia_dif = date("d") - $dia;
			if ($dia_dif < 0 || $mes_dif < 0)
			$anio_dif--;
			return $anio_dif;
		}
		



function numerode($idparentezco, $condicion, $edad, $idtrabajador){
	$numero = 0;
	if($condicion != "todos"){
		$sql_consultar= mysql_query("select cf.fecha_nacimiento 
												from 
													carga_familiar cf,
													parentezco pa
												where 
													cf.idtrabajador = '".$idtrabajador."'
													and cf.idparentezco = pa.idparentezco
													and pa.idparentezco = '".$idparentezco."'")or die(mysql_error());
												
		while($bus_consultar = mysql_fetch_array($sql_consultar)){
			switch($condicion){
				case ">":
					if(edad($bus_consultar["fecha_nacimiento"]) > $edad){
						$numero++;
					}
				break;
				case "<":
					if(edad($bus_consultar["fecha_nacimiento"]) < $edad){
						
						$numero++;
					}
				break;
				case ">=":
					if(edad($bus_consultar["fecha_nacimiento"]) >= $edad){
						$numero++;
					}
				break;
				case "<=":
					if(edad($bus_consultar["fecha_nacimiento"]) <= $edad){
						$numero++;
					}
				break;
				case "=":
					if(edad($bus_consultar["fecha_nacimiento"]) == $edad){
						$numero++;
					}
				break;
			}
		}
		$num_consultar = $numero;
	}else{
		$sql_consultar= mysql_query("select cf.idcarga_familiar 
												from 
													carga_familiar cf,
													parentezco pa
												where 
													cf.idtrabajador = '".$idtrabajador."'
													and cf.idparentezco = pa.idparentezco
													and pa.idparentezco = '".$idparentezco."'")or die(mysql_error());	
		$num_consultar= mysql_num_rows($sql_consultar);
	}

	return $num_consultar;
}


		
echo numerode(1, "<", 18, 189);

?>