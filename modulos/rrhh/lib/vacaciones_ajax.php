<?
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);


switch($ejecutar){
	case "consultarVacaciones":
		$sql_vacaciones = mysql_query("select * from vacaciones where idtrabajador = '".$idtrabajador."'");
		
		
		?>
		<table align="center" class="Main" cellpadding="0" cellspacing="0" width="90%">
    	<thead>
        	<tr>
            	<td class="Browse" align="center">Fecha de Salida</td>
                <td class="Browse" align="center">Fecha de Reintegro</td>
                <td class="Browse" align="center">Comentarios</td>
                <td class="Browse" align="center" colspan="2">Acciones</td>
            </tr>
        </thead>
     <?
     while($bus_vacaciones = mysql_fetch_array($sql_vacaciones)){
	 	?>
		<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class='Browse'>
			<?
            list($a, $m ,$d) = explode("-",$bus_vacaciones["fecha_salida"]);
			echo $d."/".$m."/".$a;
			?></td>
            <td class='Browse'>
			<?
            list($a, $m ,$d) = explode("-",$bus_vacaciones["fecha_reintegro"]);
			echo $d."/".$m."/".$a;
			?></td>
            <td class='Browse'><?=$bus_vacaciones["comentarios"]?></td>
            <td class='Browse' align="center">
            &nbsp;
            <img src="imagenes/modificar.png" style="cursor:pointer" onclick="seleccionarModificar('<?=$bus_vacaciones["fecha_salida"]?>', '<?=$bus_vacaciones["fecha_reintegro"]?>', '<?=$bus_vacaciones["comentarios"]?>', '<?=$bus_vacaciones["idvacaciones"]?>')">
            </td>
            <td class='Browse' align="center" style="height:15px;"> 
				&nbsp;
				<?
                if(date("Y-m-d") <= $bus_vacaciones["fecha_salida"]){
				?>
				<img src="imagenes/delete.png" style="cursor:pointer" onclick="eliminarMovimiento('<?=$bus_vacaciones["idvacaciones"]?>')">
				<?	
				}
				?>
                
            </td>
        </tr>
		<?
	 }
	 ?>
    </table>
		<?
	break;
	
	
	
	case "ingresarVacaciones":
		$sql_ingresar = mysql_query("insert into vacaciones(fecha_salida,
															fecha_reintegro,
															comentarios,
															idtrabajador)VALUES('".$fecha_salida."',
																				'".$fecha_reintegro."',
																				'".$comentarios."',
																				'".$idtrabajador."')");
		if($sql_ingresar){
			registra_transaccion("Se ingreso las vacaciones al trabajador (".$idtrabajador.")",$login,$fh,$pc,"Vacaciones");
			echo "exito";
		}else{
			echo "fallo";
		}
	break;
	
	case "modificarVacaciones":
		$sql_modificar = mysql_query("update vacaciones set fecha_salida = '".$fecha_salida."',
															fecha_reintegro = '".$fecha_reintegro."',
															comentarios = '".$comentarios."'
																where 
															idvacaciones = '".$idvacaciones."'")or die(mysql_error());
		registra_transaccion("Se modifico las vacaciones al trabajador (".$idtrabajador.")",$login,$fh,$pc,"Vacaciones");
		if($sql_modificar){
			echo "exito";
		}else{
			echo "fallo";
		}
	
	break;
	
	
	
	case "eliminarMovimiento":
		$sql_eliminar = mysql_query("delete from vacaciones where idvacaciones = '".$idvacaciones."'");
		registra_transaccion("Se elimino las vacaciones al trabajador (".$idtrabajador.")",$login,$fh,$pc,"Vacaciones");
	break;
}
?>