<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);




if($ejecutar == "ingresarConcepto"){
	$sql_insertar = mysql_query("insert into tipo_conceptos_nomina(codigo,
																descripcion,
																afecta)VALUES('".$codigo."',
																			'".$descripcion."',
																			'".$afecta."')")or die("Error insertando la nueva concepto ".mysql_error());
	
	
	registra_transaccion("Se inserto un nuevco tipo de concepto de nomina (".mysql_insert_id().")",$login,$fh,$pc,'tipo_conceptos_nomina');
}



if($ejecutar == "modificarConcepto"){
	echo "....".$idconcepto_nomina;
	$sql_insertar = mysql_query("update tipo_conceptos_nomina set codigo = '".$codigo."',
																descripcion = '".$descripcion."',
																afecta = '".$afecta."'
																	where idconceptos_nomina = '".$idconcepto_nomina."'")or die("Error Modificando la nueva concepto ".mysql_error());

	registra_transaccion("Se modifico el tipo de concepto de nomina (".$idconcepto_nomina.")",$login,$fh,$pc,'tipo_conceptos_nomina');
}





if($ejecutar == "actualizarLista"){
	?>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="60%">
<thead>
          <tr>
          	<td width="16%" align="center" class="Browse">Codigo</td>
            <td width="56%" align="center" class="Browse">Descripcion</td>
            <td width="56%" align="center" class="Browse">Afecta</td>
            <td align="center" class="Browse" colspan="2">Accion</td>          
          </tr>
          </thead>    
          <?
          $sql_consulta = mysql_query("select * from tipo_conceptos_nomina");
		  while($bus_consulta = mysql_fetch_array($sql_consulta)){
		  ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class='Browse' align='left'><?=$bus_consulta["codigo"]?></td>
            <td class='Browse' align='left'><?=$bus_consulta["descripcion"]?></td>
            <td class='Browse' align='left'><?=$bus_consulta["afecta"]?></td>
            <td width="5%" align='center' class='Browse'>
            	<img src="imagenes/modificar.png" onClick="mostrarModificar('<?=$bus_consulta["idconceptos_nomina"]?>',
                															'<?=$bus_consulta["codigo"]?>',
                                                                            '<?=$bus_consulta["descripcion"]?>',
                                                                            '<?=$bus_consulta["afecta"]?>')" style="cursor:pointer">
            </td>
            <td width="6%" align='center' class='Browse'>
            	<img src="imagenes/delete.png" onClick="mostrarEliminar('<?=$bus_consulta["idconceptos_nomina"]?>',
                															'<?=$bus_consulta["codigo"]?>',
                                                                            '<?=$bus_consulta["descripcion"]?>',
                                                                            '<?=$bus_consulta["afecta"]?>')" style="cursor:pointer">
            </td>
  </tr>
         <?
         }
		 ?>
         </table>
	<?
}





if($ejecutar == "eliminarConcepto"){
	$sql_consulta = mysql_query("select * from conceptos_nomina where tipo_concepto = '".$idconcepto_nomina."'");
	$num_consulta = mysql_num_rows($sql_consulta);
	
	if($num_consulta > 0){
		echo "relacionado";	
	}else{
		$sql_eliminar = mysql_query("delete from tipo_conceptos_nomina where idconceptos_nomina = '".$idconcepto_nomina."'")or die(mysql_error());
		registra_transaccion("Se Elimino el tipo de concepto de nomina (".$idtipo_conceptos_nomina.")",$login,$fh,$pc,'tipo_conceptos_nomina');
	}
}

?>