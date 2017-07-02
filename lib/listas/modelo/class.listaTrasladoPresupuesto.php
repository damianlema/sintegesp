<?php
/*****************************************************************************
* @Consulta para cargar lista de traslados presupuestarios
* @versión: 1.0
* @fecha creación:
* @autor:
******************************************************************************
* @fecha modificacion
* @autor
* @descripcion
******************************************************************************/

/**
*
*/
class ListaTrasladoPresupuestario
{

	public function listarTrasladosPresupuestarios()
	{
		$db = new Conexion();
		$sql = $db->query("SELECT * FROM traslados_presupuestarios ORDER BY nro_solicitud");
		echo "entro a buscar datos";

	}
}


?>