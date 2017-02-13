<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);

switch($ejecutar){
	case "ingresarCofinanciamiento":
		$sql_ingresar = mysql_query("insert into cofinanciamiento (denominacion)VALUES('".$denominacion."')");
		echo mysql_insert_id();
	break;
	
	
	case "incluirFinancimiento":
		
		$sql_consultar = mysql_query("select SUM(porcentaje) as total from fuentes_cofinanciamiento 
												where 
												idcofinanciamiento = '".$idcofinanciamiento."'")or die(mysql_error());
		
		$bus_consultar = mysql_fetch_array($sql_consultar);
		$total = $bus_consultar["total"]+$porcentaje;
			
		if($total <= 100){
	
			$sql_consultar = mysql_query("select * from fuentes_cofinanciamiento 
													where 
													idfuente_financiamiento = '".$idfuente_financiamiento."' 
													and idcofinanciamiento = '".$idcofinanciamiento."'")or die(mysql_error());
			$num_consulta = mysql_num_rows($sql_consultar);
			if($num_consulta > 0){
				echo "existe";
			}else{
				$sql_ingresar = mysql_query("insert into fuentes_cofinanciamiento(idfuente_financiamiento,
																			porcentaje,
																			idcofinanciamiento)VALUES('".$idfuente_financiamiento."',
																									'".$porcentaje."',
																									'".$idcofinanciamiento."')");
				echo (100-$total);
			}
		}else{
			echo "mayor";
		}
	break;
	
	case "listarFuentes":
		$sql_consulta = mysql_query("select * from fuentes_cofinanciamiento where idcofinanciamiento = '".$idcofinanciamiento."'");
		?>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="100%">
			  <thead>
			  <tr>
				<td width="64%" align="center" class="Browse">Fuente Financiamiento</td>
                <td width="27%" align="center" class="Browse">Porcentaje</td>
				<td align="center" class="Browse">Eliminar</td>
			  </tr>
			  </thead>
		<?
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
		$sql_fuentes = mysql_query("select * from fuente_financiamiento where idfuente_financiamiento = '".$bus_consulta["idfuente_financiamiento"]."'");
		$bus_fuentes = mysql_fetch_array($sql_fuentes);
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            	<td class='Browse' align="left">&nbsp;<?=$bus_fuentes["denominacion"]?></td>
                <td class='Browse' align="right"><?=$bus_consulta["porcentaje"]?> %</td>
                <td width="5%" align="center" class='Browse'><img src="imagenes/delete.png"  style="cursor:pointer" border="0" onclick="eliminarFinanciamiento('<?=$bus_consulta["idfuentes_cofinanciamiento"]?>')"></td>    
		  </tr>
			<?
		}
		?>
		</table>
		<?
	break;
	
	
	
	
	
	case "eliminarFinanciamiento":
	
		$sql_eliminar = mysql_query("delete from fuentes_cofinanciamiento where idfuentes_cofinanciamiento = '".$idfuentes_cofinanciamiento."'");
	
	break;
	

}
?>