<?
$conceptos[0] = array("hola","1");
$conceptos[1] = array("prueba","2");


if(in_array(array("hola","1"), $conceptos)){
	echo "Se Consiguio";	
}else{
	echo "No se Consiguio";	
}



echo $conceptos[0][0];


?>

<br />
<br />
<br />
<br />
<?
$a = "EM0001";
echo substr($a,0,2);
?>