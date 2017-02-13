<?php
include("../../../conf/conex.php");
Conectarse();
extract($_POST);

if($rif != ""){
$sql = mysql_query("select * from beneficiarios where rif = '".$rif."'");
$num = mysql_num_rows($sql);
	if($num == 0){
		echo "no";
	}else{
		echo "si";
	}
}else{
echo "vacio";
}
?>