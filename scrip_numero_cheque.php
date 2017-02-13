<? 
if (!($link=mysql_connect("localhost","root","gestion2009"))){
   		echo "Error conectando al Servidor: ".mysql_error(); 
}

if (!mysql_select_db("gestion_gobernacion_072012",$link)) {

      echo "Error conectando a la base de datos."; 
} 
   mysql_query("SET NAMES 'utf8'");
   
$sql = mysql_query("SELECT * from pagos_financieros");

while ($bus = mysql_fetch_array($sql)){
	$numero_cheque = substr($bus["numero_cheque"],0,8);
	echo $numero_cheque."<br>";
	$actualizar = mysql_query("update pagos_financieros set numero_cheque = '".$numero_cheque."'
									where idpagos_financieros = '".$bus["idpagos_financieros"]."'")or die(mysql_error());

}

?>