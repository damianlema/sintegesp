<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);


if($ejecutar == "consultarConceptos"){
	$sql_consulta = mysql_query("select * 
										from 
										relacion_concepto_trabajador 
										where 
										idtipo_nomina = '".$idtipo_nomina."' 
										group by idconcepto, tabla")or die(mysql_error());
	?>
	<select name="constante_concepto" id="constante_concepto">
	<option value="0">.:: Seleccione ::.</option>
	<?
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
		$sql_concepto = mysql_query("select * from ".$bus_consulta["tabla"]." where id".$bus_consulta["tabla"]." = '".$bus_consulta["idconcepto"]."'")or die(mysql_error());
		$bus_concepto = mysql_fetch_array($sql_concepto);
		?>
		<option value="<?=$bus_consulta["tabla"]?>-<?=$bus_consulta["idconcepto"]?>">
		<?
        if($bus_consulta["tabla"] == "constantes_nomina"){
			echo "Constante: -";
		}else{
			echo "Concepto : -";
		}
		?>
		<?=$bus_concepto["descripcion"]?>
        </option>
		<?
	}
	?>
	</select>
	<?
}
?>