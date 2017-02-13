<?

include("conf/conex.php");
Conectarse();

echo "AÑOS: ".anioempresa(897);

function anioempresa($idtrabajador) { // SE ESTA UTILIZANDO
	//echo getFechaIngreso($idtrabajador)."<br />";
	$anios = getAnios(getFechaIngreso($idtrabajador));
	return $anios;
}


function getFechaIngreso($idtrabajador){
	$sql = "SELECT fecha_ingreso FROM trabajador WHERE idtrabajador = '".$idtrabajador."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0){ 
		$field = mysql_fetch_array($query);
		return $field["fecha_ingreso"];
	}else{
		return 0;	
	}
}


function getAnios($fecha) { // RETORNA LA EDAD DE UNA PERSONA EN AÑOS MES Y DIA
	$dia=date("d");
	$mes=date("m");
	$anno=date("Y");
	$dia_nac=substr($fecha, 8, 2);
	$mes_nac=substr($fecha, 5, 2);
	$anno_nac=substr($fecha, 0, 4);
		if($mes_nac>$mes){
			$calc_edad= $anno-$anno_nac-1;
		}else{
			if($mes==$mes_nac AND $dia_nac>$dia AND $anno == $anno_nac){
				$calc_edad= $anno-$anno_nac-1;
			}else{
				$calc_edad= $anno-$anno_nac;
			}
		}
	return $calc_edad;
}
?>