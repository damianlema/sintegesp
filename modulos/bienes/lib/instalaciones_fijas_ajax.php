<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();

extract($_POST);

if($ejecutar == "ingresarDatos"){
	
	$sql_consultar = mysql_query("select * from instalaciones_fijas 
													where idinmueble = '".$idinmueble."' and tipo_inmueble = '".$tipo_inmueble."'");
	$num_consultar = mysql_num_rows($sql_consultar);
		if($num_consultar == 0){
			$sql_insertar_datos_basicos = mysql_query("insert into instalaciones_fijas(idinmueble,
																					tipo_inmueble)values('".$idinmueble."',
																											'".$tipo_inmueble."')")or die(mysql_error());
		$id_instalaciones = mysql_insert_id();																									
		}else{
			$bus_consultar = mysql_fetch_array($sql_consultar);
			$id_instalaciones = $bus_consultar["idinstalaciones_fijas"];																									
		}

		

			$sql_insertar_datos = mysql_query("insert into relacion_instalaciones_fijas(idinstalaciones_fijas,
																					descripcion,
																					valor,
																					fecha)VALUES('".$id_instalaciones."',
																								'".$descripcion."',
																								'".$valor."',
																								'".$fecha."')")or die(mysql_error());
	if($sql_insertar_datos){
		echo "exito";
	}else{
		echo "fallo";
	}	
}



if($ejecutar == "modificarDatos"){

	$sql_modificar_datos = mysql_query("update relacion_instalaciones_fijas set descripcion = '".$descripcion."',
																				valor = '".$valor."',
																				fecha = '".$fecha."'
																				where idrelacion_instalaciones_fijas = '".$idrelacion."'");
	
	if($sql_modificar_datos){
		echo "exito";
	}else{
		echo "fallo";
	}
}








if($ejecutar == "consultarDatos"){
		
		?>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="60%">
          <thead>
          <tr>
            <td width="63%" align="center" class="Browse" style="font-size:9px">Descripcion</td>
            <td width="14%" align="center" class="Browse" style="font-size:9px">Valor</td>
            <td width="13%" align="center" class="Browse" style="font-size:9px">Fecha</td>
            <td align="center" class="Browse" style="font-size:9px" colspan="2">Acciones</td>
          </tr>
          </thead>
          <?
		 // echo "select * form instalaciones_fijas where idinmueble = '".$idinmueble."'";
		  $sql_con = mysql_query("select * from instalaciones_fijas where idinmueble = '".$idinmueble."' and tipo_inmueble = '".$tipo_inmueble."'");
		  while($bus_con = mysql_fetch_array($sql_con)){
		  	$sql_consultar = mysql_query("select * from relacion_instalaciones_fijas where 
																idinstalaciones_fijas = '".$bus_con["idinstalaciones_fijas"]."'");
			  while($bus_consultar = mysql_fetch_array($sql_consultar)){
			  ?>
					  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
						<td class='Browse' align='left' width="63%" style="font-size:10px"><?=$bus_consultar["descripcion"]?></td>
                        <td class='Browse' align='right' width="14%" style="font-size:10px"><?=number_format($bus_consultar["valor"],2,",",".")?></td>
                        <td class='Browse' align='center' width="13%" style="font-size:10px"><?=$bus_consultar["fecha"]?></td>
					  <td width="5%" align="center" valign="middle" class='Browse'>
							<img src="imagenes/modificar.png"
                            	style="cursor:pointer"
                                onClick="document.getElementById('descripcion').value='<?=$bus_consultar["descripcion"]?>',
                                		document.getElementById('valor_mostrado').value='<?=number_format($bus_consultar["valor"],2,",",".")?>',
                                        document.getElementById('valor').value='<?=$bus_consultar["valor"]?>',
                                        document.getElementById('fecha').value='<?=$bus_consultar["fecha"]?>',
                                        document.getElementById('idrelacion').value='<?=$bus_consultar["idrelacion_instalaciones_fijas"]?>',
                                        document.getElementById('botonGuardar').style.display = 'none',
                                        document.getElementById('botonModificar').style.display = 'block'">						
                      </td>
                      <td width="5%" align="center" valign="middle" class='Browse'>
							<img src="imagenes/delete.png"
                            	style="cursor:pointer"
                                onClick="eliminarInstalacion('<?=$bus_consultar["idrelacion_instalaciones_fijas"]?>')">
                      </td>
		  </tr>
			  <?
			  }
		  }
          ?>
        </table>
		<?
}







if($ejecutar == "eliminarInstalacion"){
	$sql_eliminar_datos = mysql_query("delete from relacion_instalaciones_fijas where idrelacion_instalaciones_fijas = '".$idrelacion."'");
	
	
	if($sql_eliminar_datos){
		echo "exito";
	}else{
		echo "fallo";
	}
}
?>