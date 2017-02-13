<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);




if($ejecutar == "ingresarHojaTiempo"){
	$sql_insertar = mysql_query("insert into tipo_hoja_tiempo(descripcion, unidad)VALUES('".$descripcion."','".$unidad."')")or die("Error insertando la nueva concepto ".mysql_error());
	
	
	registra_transaccion("Se inserto un nuevco tipo de Hoja de Tiempo(".mysql_insert_id().")",$login,$fh,$pc,'tipo_hoja_tiempo');
}



if($ejecutar == "modificarHojaTiempo"){
	echo "....".$idconcepto_nomina;
	$sql_insertar = mysql_query("update tipo_hoja_tiempo 
								set descripcion = '".$descripcion."',
								unidad = '".$unidad."'
								where idtipo_hoja_tiempo = '".$idtipo_hoja_tiempo."'")or die("Error Modificando la nueva concepto ".mysql_error());

	registra_transaccion("Se modifico el Tipo de Hoja de Tiempo (".$idtipo_hoja_tiempo.")",$login,$fh,$pc,'tipo_hoja_tiempo');
}





if($ejecutar == "actualizarLista"){
	?>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="60%">
<thead>
          <tr>
            <td width="56%" align="center" class="Browse">Descripcion</td>
            <td width="56%" align="center" class="Browse">Unidad</td>
            <td align="center" class="Browse" colspan="2">Accion</td>          
          </tr>
          </thead>    
          <?
          $sql_consulta = mysql_query("select * from tipo_hoja_tiempo");
		  while($bus_consulta = mysql_fetch_array($sql_consulta)){
		  ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class='Browse' align='left'><?=$bus_consulta["descripcion"]?>&nbsp;</td>
            <td class='Browse' align='left'><?
            if($bus_consulta["unidad"] =="Anos"){
				echo "A&ntilde;os";
			}else{
				echo $bus_consulta["unidad"];	
			}?>&nbsp;</td>
            <td width="5%" align='center' class='Browse'>
            	<img src="imagenes/modificar.png" onClick="mostrarModificar('<?=$bus_consulta["idtipo_hoja_tiempo"]?>',
                															'<?=$bus_consulta["descripcion"]?>',
                                                                            '<?=$bus_consulta["unidad"]?>')" style="cursor:pointer">
            </td>
            <td width="6%" align='center' class='Browse'>
            	<img src="imagenes/delete.png" onClick="mostrarEliminar('<?=$bus_consulta["idtipo_hoja_tiempo"]?>',
                															'<?=$bus_consulta["descripcion"]?>',
                                                                            '<?=$bus_consulta["unidad"]?>')" style="cursor:pointer">
            </td>
  </tr>
         <?
         }
		 ?>
         </table>
	<?
}





if($ejecutar == "eliminarHojaTiempo"){
	$sql_consultar_conceptos= mysql_query("select * from relacion_formula_conceptos_nomina where valor_oculto = 'THT_".$idtipo_hoja_tiempo."'");
		$num_consultar_conceptos = mysql_num_rows($sql_consultar_conceptos);
		if($num_consultar_conceptos > 0){
			echo "asociada_concepto";		
		}else{
			$sql_eliminar = mysql_query("delete from tipo_hoja_tiempo where idtipo_hoja_tiempo = '".$idtipo_hoja_tiempo."'")or die(mysql_error());
			registra_transaccion("Se Elimino el Tipo de Hoja de Tiempo (".$idtipo_hoja_tiempo.")",$login,$fh,$pc,'tipo_hoja_tiempo');
		}
}

?>