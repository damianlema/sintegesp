<?php
include("../conf/conex.php");
conectarse();
extract($_GET);

$sql_select = mysql_query("select * from ".$tabla."");
	echo "<select name='".$nombre."' id='".$nombre."' style='width:250px'>";
		echo "<option value='0'>&nbsp;</option>";
		while($bus_select = mysql_fetch_array($sql_select)){
			if($idcreado == $bus_select[0]){$selected= "selected='selected'";}else{$selected = "";}
			echo "<option ".$selected." value='".$bus_select[0]."'>".$bus_select[1]."</option>";
		}
	echo "</select>";
?>