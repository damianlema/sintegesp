<br>
<h4 align=center>Cargar Cuentas</h4>
<h2 class="sqlmVersion"></h2>
<br>



<?
 if (!($link=mysql_connect("localhost","root","gestion2009"))){
   		echo "Error conectando al Servidor: ".mysql_error(); 
      	exit(); 
   }

   if (!mysql_select_db("gestion_alcaldia_tucupita_mayo2014",$link)) {

      echo "Error conectando a la base de datos."; 
      exit(); 
   } 
$tipo_cuenta = 2;
$tipo = 'Corriente';
$banco = 2;

$nombre = 'ALCATUCU.xls';
require_once 'Excel/reader.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read($nombre);
error_reporting(E_ALL ^ E_NOTICE);

	for($i=1;$i<(1225);$i++){
		if ($data->sheets[0]['cells'][$i][3] == 'entregado'){
			$sql_trabajador = mysql_query("select * from trabajador where cedula = '".$data->sheets[0]['cells'][$i][2]."'");
			$bus_trabajador = mysql_fetch_array($sql_trabajador);
			$num_trabajador = mysql_num_rows($sql_trabajador);
					
			if($num_trabajador > 0){
								
				$sql_cuenta_trabajador = mysql_query("select * from cuentas_bancarias_trabajador where nro_cuenta = '".$data->sheets[0]['cells'][$i][1]."'")or die(mysql_error());
				$bus_cuenta_trabajador = mysql_fetch_array($sql_cuenta_trabajador);
				$num_cuenta_trabajador = mysql_num_rows($sql_cuenta_trabajador);
				
				if($num_cuenta_trabajador <= 0){
																	
					$sql_cuenta = mysql_query("insert into cuentas_bancarias_trabajador(
													idtrabajador,
													nro_cuenta,
													tipo,
													motivo,
													banco)VALUES(
													'".$bus_trabajador["idtrabajador"]."',
													'".$data->sheets[0]['cells'][$i][1]."',
													'Corriente',
													'2',
													'2')")or die(mysql_error());
				
					if($sql_cuenta){
						echo "Linea Numero $i -> Se proceso la CUENTA nro: ".$data->sheets[0]['cells'][$i][1]." del trabajador: ".$data->sheets[0]['cells'][$i][2]." <br />";
					}else{
						echo "Problemas : ".mysql_error()."<br />";
					}
				}else{
					echo "<strong style='color:#FF0000'>La CUENTA nro: ".$data->sheets[0]['cells'][$i][1].", YA EXISTE<br /></strong>";
				}
		}else{
			echo "<strong style='color:#FF0000'>EL TRABAJADOR nro: ".$data->sheets[0]['cells'][$i][2].", no se encontro<br /></strong>";
		}
		
	}
}


?>