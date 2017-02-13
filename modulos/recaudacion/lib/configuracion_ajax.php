<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);

	$sql_configuracion = mysql_query("select * from configuracion_recaudacion");
	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	
	$sql_consulta = mysql_query("select * from ".$sel." where ".$nombreIdPadre." = '".$idpadre."'")or die(mysql_error());
	?>
	<select id="<?=$sel?>" name="<?=$sel?>" style="width:250px">
    <option value="0">.:: Seleccione ::.</option>
	<?
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
		
		?>
		<option <? if($bus_configuracion["idmunicipio"] == $bus_consulta["id".$sel]){echo "selected";}?> value="<?=$bus_consulta["id".$sel]?>"><?=$bus_consulta["denominacion"]?></option>
		<?
	}
	?>
	</select>
	<?
?>