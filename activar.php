<?
include("conf/conex.php");
Conectarse();
$sql_trabajador = mysql_query("select * from trabajador");

while($reg_trabajador = mysql_fetch_array($sql_trabajador)){

	if ($reg_trabajador["activo_nomina"] == 'no'){
		$actualiza = mysql_query("update relacion_tipo_nomina_trabajador set activa = '0' where idtrabajador = '".$reg_trabajador["idtrabajador"]."'");
	
	}

}

//aaaAA

?>