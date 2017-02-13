<?
if (!($link=mysql_connect("localhost","root","gestion2009"))){
   		echo "Error conectando al Servidor: ".mysql_error(); 
      	exit(); 
   }


   if (!mysql_select_db("gestion_snc_2013",$link)) {

      echo "Error conectando a la base de datos."; 
      exit(); 
   } 
   mysql_query("SET NAMES 'utf8'");

if($_POST){
extract($_POST);
$nombre = $_FILES['archivo_excel']['name'];
$nombre_temp = $_FILES['archivo_excel']['tmp_name'];
if (move_uploaded_file($nombre_temp, "../servidor/archivos/".$nombre)){

$cantidad_filas=19475;
require_once 'Excel/reader.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read('../servidor/archivos/'.$nombre);

error_reporting(E_ALL ^ E_NOTICE);
	for($i=1;$i<($cantidad_filas+1);$i++){
	
	echo "<strong style='color:#FF6'>Clase nro: ".$data->sheets[0]['cells'][$i][1]."<br /></strong>";
	
				$sql_familia = mysql_query("select * from snc_grupo_actividad where codigo = '".$data->sheets[0]['cells'][$i][1]."'");
				$bus_familia = mysql_fetch_array($sql_familia);
				$num_familia = mysql_num_rows($sql_familia);
				
				if($num_familia > 0){
				
				
					$sql_validar = mysql_query("select * from snc_detalle_grupo where codigo = '".$data->sheets[0]['cells'][$i][2]."'")or die(mysql_error());
					$bus_validar = mysql_fetch_array($sql_validar);
					$num_validar = mysql_num_rows($sql_validar);
				
				
				
					if($num_validar <= 0){
				
				
						$denominacion = $data->sheets[0]['cells'][$i][3];
						$codigo = $data->sheets[0]['cells'][$i][2];;
						$codigo_familia = $bus_familia["idsnc_grupo_actividad"];
						
						$sql_ingresar = mysql_query("insert into snc_detalle_grupo
									(descripcion,usuario,fechayhora,status, codigo, idsnc_grupo_actividad) 
							values ('$denominacion','','','a','$codigo', $codigo_familia)");
					
				
				
						if($sql_ingresar){
							echo "Linea Numero $i -> Se proceso el Producto nro: ".$data->sheets[0]['cells'][$i][1]."<br />";
						}else{
							echo "Problemas : ".mysql_error()."<br />";
						}
		
					}else{
						echo "<strong style='color:#FF0000'>El Producto nro: ".$data->sheets[0]['cells'][$i][2].", YA se encontro<br /></strong>";
					}
			}else{
				echo "<strong style='color:#FF0000'>La Clase nro: ".$data->sheets[0]['cells'][$i][1].", no se encontro<br /></strong>";
			}
		
	}
}else{
   echo "DISCULPE EL ARCHIVO NO SE PUDO GUARDAR POR FAVOR INTENTE DE NUEVO";
} 

}else{
?>

<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
  <table width="60%" border="0" align="center">
    
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
 
</form>
<?
}
?>