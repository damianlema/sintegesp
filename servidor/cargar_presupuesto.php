<br>
<h4 align=center>Cargar Presupuesto</h4>
<h2 class="sqlmVersion"></h2>
<br>



<?

if($_POST){
extract($_POST);
$nombre = $_FILES['archivo_excel']['name'];
$nombre_temp = $_FILES['archivo_excel']['tmp_name'];
if (move_uploaded_file($nombre_temp, "servidor/archivos/".$nombre)){
require_once 'Excel/reader.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read('servidor/archivos/'.$nombre);
error_reporting(E_ALL ^ E_NOTICE);
	for($i=1;$i<($cantidad_filas+1);$i++){
	echo $nombre;
				$sql_categoria_programatica = mysql_query("select * from categoria_programatica where codigo = '".$data->sheets[0]['cells'][$i][1]."'");
				$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
				$num_categoria_programatica = mysql_num_rows($sql_categoria_programatica);
				
				if($num_categoria_programatica > 0){
				
				
				$sql_clasificador_presupuestario = mysql_query("select * from clasificador_presupuestario where codigo_cuenta = '".$data->sheets[0]['cells'][$i][2]."'")or die(mysql_error());
				$bus_clasificador_presupuestario = mysql_fetch_array($sql_clasificador_presupuestario);
				$num_clasificador_presupuestario = mysql_num_rows($sql_clasificador_presupuestario);
				
				if($num_clasificador_presupuestario > 0){
				
				//if(strlen($data->sheets[0]['cells'][$i][3],0,1) == 5){
					//$parte1_ordinal = substr($data->sheets[0]['cells'][$i][3],0,1);
					//$parte2_ordinal = substr($data->sheets[0]['cells'][$i][3],2,4);
					//$ordinal = $parte1_ordinal.$parte2_ordinal;
				//}else{
					$ordinal = $data->sheets[0]['cells'][$i][3];
					//echo $ordinal;
				//}	
					
				$sql_ordinal = mysql_query("select * from ordinal where codigo = '".$ordinal."'");
				$bus_ordinal = mysql_fetch_array($sql_ordinal);
				$num_ordinal = mysql_num_rows($sql_ordinal);
				
				if ($num_ordinal > 0){
					$sql_configuracion = mysql_query("select * from configuracion");
					$bus_configuracion = mysql_fetch_array($sql_configuracion);
					
														
														
					$sql_maestro = mysql_query("insert into maestro_presupuesto(
													idcategoria_programatica,
													anio,
													idtipo_presupuesto,
													idfuente_financiamiento,
													idclasificador_presupuestario,
													idordinal,
													monto_original,
													monto_actual,
													status,
													usuario,
													fechayhora)VALUES(
													'".$bus_categoria_programatica["idcategoria_programatica"]."',
													'".$_SESSION["anio_fiscal"]."',
													'".$tipo_presupuesto."',
													'".$fuente_financiamiento."',
													'".$bus_clasificador_presupuestario["idclasificador_presupuestario"]."',
													'".$bus_ordinal["idordinal"]."',
													'".$data->sheets[0]['cells'][$i][4]."',
													'".$data->sheets[0]['cells'][$i][4]."',
													'a',
													'".$login."',
													'".$fh."')")or die(mysql_error());
				
					if($sql_maestro){
						echo "Linea Numero $i -> Se proceso la categoria programatica nro: ".$data->sheets[0]['cells'][$i][1]."<br />";
					}else{
						echo "Problemas : ".mysql_error()."<br />";
					}
				}else{
					echo "<strong style='color:#FF0000'>El Ordinal nro: ".$data->sheets[0]['cells'][$i][3].", no se encontro<br /></strong>";
				}
		}else{
			echo "<strong style='color:#FF0000'>La Partida nro: ".$data->sheets[0]['cells'][$i][2].", no se encontro<br /></strong>";
		}
		}else{
			echo "<strong style='color:#FF0000'>La categoria programatica nro: ".$data->sheets[0]['cells'][$i][1].", no se encontro<br /></strong>";
		}
		
	}
}else{
	echo $nombre;
   echo "DISCULPE EL ARCHIVO NO SE PUDO GUARDAR POR FAVOR INTENTE DE NUEVO";
} 

}else{
?>

<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
  <table width="60%" border="0" align="center">
    <tr>
      <td>Fuente de Financiamiento</td>
      <td>
      <?
      $sql_fuente_financiamiento = mysql_query("select * from fuente_financiamiento");
	  ?>
	  <select name="fuente_financiamiento" id="fuente_financiamiento">
	  <?
	  while($bus_fuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)){
	  	?>
		<option value="<?=$bus_fuente_financiamiento["idfuente_financiamiento"]?>"><?=$bus_fuente_financiamiento["denominacion"]?></option>
		<?	
	  }
	  ?>
	  </select>
      </td>
    </tr>
    <tr>
      <td>Tipo de Presupuesto</td>
      <td>
      
      <?
      $sql_tipo_presupuesto = mysql_query("select * from tipo_presupuesto");
	  ?>
	  <select name="tipo_presupuesto" id="tipo_presupuesto">
	  <?
	  while($bus_tipo_presupuesto = mysql_fetch_array($sql_tipo_presupuesto)){
	  	?>
		<option value="<?=$bus_tipo_presupuesto["idtipo_presupuesto"]?>"><?=$bus_tipo_presupuesto["denominacion"]?></option>
		<?	
	  }
	  ?>
	  </select>
      
      </td>
    </tr>
    <tr>
      <td>Archivo de Excel</td>
      <td><input name="archivo_excel" type="file" id="archivo_excel" size="50" /></td>
    </tr>
    <tr>
      <td>Cantidad Filas</td>
      <td><label>
        <input name="cantidad_filas" type="text" id="cantidad_filas" size="6" />
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="center"><label>
        <input type="submit" name="cargar_archivo" id="cargar_archivo" value="Cargar Archivo" class="button" />
      </label></td>
    </tr>
  </table>
  <br />
  <br />
  <center><img src="imagenes/explicacion_presupuesto.jpg" width="775" height="446" /></center><br />
</form>
<?
}
?>