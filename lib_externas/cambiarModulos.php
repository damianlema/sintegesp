<?
include("conf/conex.php");
Conectarse();

$sql_tipos_documentos = mysql_query("select * from tipos_documentos");
	while($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)){
		if($bus_tipos_documentos["multi_categoria"] == "si" and $bus_tipos_documentos["causa"] == 'no'){
			$update = mysql_query("update tipos_documentos 
												set modulo = '-1--13-' 
												where idtipos_documentos  = '".$bus_tipos_documentos["idtipos_documentos"]."'"); 
		}else{
			$update = mysql_query("update tipos_documentos 
												set modulo = '-".$bus_tipos_documentos["modulo"]."-' 
												where idtipos_documentos  = '".$bus_tipos_documentos["idtipos_documentos"]."'"); 
		}
		echo "Se Actualizo el Tipo de Documento: ".$bus_tipos_documentos["descripcion"]."<br>";
	}

?>