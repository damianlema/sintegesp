<?php
include("../../../conf/conex.php");
Conectarse();
if($_GET["rif"] != ""){
$sql = mysql_query("select * from beneficiarios where rif = '".$_GET["rif"]."'");
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