<? 
if (!($link=mysql_connect("localhost","root","gestion2009"))){
   		echo "Error conectando al Servidor: ".mysql_error(); 
}

if (!mysql_select_db("gestion_2012",$link)) {

      echo "Error conectando a la base de datos."; 
} 
   mysql_query("SET NAMES 'utf8'");
   
$sql = mysql_query("SELECT relacion_retenciones.generar_comprobante, relacion_retenciones.idrelacion_retenciones
FROM relacion_retenciones
INNER JOIN retenciones ON ( retenciones.idretenciones = relacion_retenciones.idretenciones )
INNER JOIN orden_compra_servicio ON ( orden_compra_servicio.idorden_compra_servicio = retenciones.iddocumento )
WHERE orden_compra_servicio.idfuente_financiamiento = '7'
AND (
relacion_retenciones.idtipo_retencion = '8'
OR relacion_retenciones.idtipo_retencion = '11'
OR relacion_retenciones.idtipo_retencion = '13'
OR relacion_retenciones.idtipo_retencion = '14'
OR relacion_retenciones.idtipo_retencion = '15'
OR relacion_retenciones.idtipo_retencion = '16'
OR relacion_retenciones.idtipo_retencion = '20'
OR relacion_retenciones.idtipo_retencion = '24'
)");

while ($bus = mysql_fetch_array($sql)){
	$actualizar = mysql_query("update relacion_retenciones set generar_comprobante = 'no'
									where idrelacion_retenciones = '".$bus["idrelacion_retenciones"]."'")or die(mysql_error());

}

?>