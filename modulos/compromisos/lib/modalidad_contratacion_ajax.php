<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);




if($ejecutar == "ingresarHojaTiempo"){
	$sql_insertar = mysql_query("insert into modalidad_contratacion(descripcion, codigo)VALUES('".$descripcion."', '".$codigo."')")or die("Error insertando la nueva concepto ".mysql_error());
	
	
	registra_transaccion("Se inserto una nueva Modalidad de Contratacion(".mysql_insert_id().")",$login,$fh,$pc,'modalidad_contratacion');
}



if($ejecutar == "modificarHojaTiempo"){
	//echo "....".$idconcepto_nomina;
	$sql_insertar = mysql_query("update modalidad_contratacion 
								set descripcion = '".$descripcion."',
								codigo = '".$codigo."'
								where idmodalidad_contratacion = '".$idmodalidad_contratacion."'")or die("Error Modificando la nueva concepto ".mysql_error());

	registra_transaccion("Se modifico la Modalidad de Contratacion (".$idmodalidad_contratacion.")",$login,$fh,$pc,'modalidad_contratacion');
}





if($ejecutar == "actualizarLista"){
	?>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="60%">
<thead>
          <tr>
            <td width="10%" align="center" class="Browse">Codigo</td>
            <td width="78%" align="center" class="Browse">Descripcion</td>
            <td align="center" class="Browse" colspan="2">Accion</td>          
          </tr>
          </thead>    
          <?
          $sql_consulta = mysql_query("select * from modalidad_contratacion");
		  while($bus_consulta = mysql_fetch_array($sql_consulta)){
		  ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class='Browse' align='center'><?=$bus_consulta["codigo"]?>&nbsp;</td>
            <td class='Browse' align='left'><?=$bus_consulta["descripcion"]?>&nbsp;</td>
            <td width="5%" align='center' class='Browse'>
            	<img src="imagenes/modificar.png" onClick="mostrarModificar('<?=$bus_consulta["idmodalidad_contratacion"]?>',
                															'<?=$bus_consulta["descripcion"]?>',
                                                                            '<?=$bus_consulta["codigo"]?>')" style="cursor:pointer">            </td>
          <td width="7%" align='center' class='Browse'>
            	<img src="imagenes/delete.png" onClick="mostrarEliminar('<?=$bus_consulta["idmodalidad_contratacion"]?>',
                															'<?=$bus_consulta["descripcion"]?>',
                                                                            '<?=$bus_consulta["codigo"]?>')" style="cursor:pointer">            </td>
  </tr>
         <?
         }
		 ?>
         </table>
	<?
}





if($ejecutar == "eliminarHojaTiempo"){
	$sql_consultar_conceptos= mysql_query("select * from proveedores_solicitud_cotizacion where tipo_procedimiento = '".$idmodalidad_contratacion."'");
		$num_consultar_conceptos = mysql_num_rows($sql_consultar_conceptos);
		if($num_consultar_conceptos > 0){
			echo "asociada_concepto";		
		}else{
			$sql_eliminar = mysql_query("delete from modalidad_contratacion where idmodalidad_contratacion = '".$idmodalidad_contratacion."'")or die(mysql_error());
			registra_transaccion("Se Elimino La Modalidad de Contratacion(".$idmodalidad_contratacion.")",$login,$fh,$pc,'modalidad_contratacion');
		}
}

?>