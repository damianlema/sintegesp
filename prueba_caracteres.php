<?
$nro_ficha= 123;
$can = strlen($nro_ficha);
	$result = 6-$can;
	for($i=1; $i<=$result;$i++){
		//echo "aqui";
		$ceros .= "0";
	}		
	$nro_ficha = $ceros.$nro_ficha;
	
	echo $nro_ficha;
?>