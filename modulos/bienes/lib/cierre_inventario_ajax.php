<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);

switch($ejecutar){
	case "ingresar_cierreInventario":
		$sql_actualizar = mysql_query("update niveles_organizacionales 
											SET 
												inventario_cerrado = 'si' 
											WHERE 
												idniveles_organizacionales = '".$idnivel_organizacion."'")or die(mysql_error());
	break;
	
	
	case "cargarNivelesOrganizacionales":
		$sql_niveles_organizacionales = mysql_query("select * from 
																niveles_organizacionales
																where 
																organizacion = '".$idorganizacion."'
																and inventario_cerrado != 'si'
																and modulo = '8'")or die(mysql_error());
		?>
		<select id="idniveles_organizacionales" name="idniveles_organizacionales">
		<?
			while($bus_niveles_organizacionales = mysql_fetch_array($sql_niveles_organizacionales)){
			?>
				<option value="<?=$bus_niveles_organizacionales["idniveles_organizacionales"]?>">
                	<?=$bus_niveles_organizacionales["denominacion"]?>
                </option>
			<?
			}
		?>
		</select>
		<?
	
	break;
}
?>