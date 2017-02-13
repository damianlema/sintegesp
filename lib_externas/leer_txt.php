<?
include("conf/conex.php");
Conectarse();

$lines = file('paises.txt');

foreach ($lines as $line_num => $line) {
    $datos = explode(",", $line);
	if($datos[1] != "VENEZUELA"){
		mysql_query("insert into pais(denominacion, usuario, status, fechayhora)VALUES('".utf8_encode(trim($datos[1]))."', 'jbello', 'a', '".date("Y-m-d H:i:s")."')");
	}
	echo "Se ingreso el Pais: ".$datos[1]."<br>";
}
?>
 