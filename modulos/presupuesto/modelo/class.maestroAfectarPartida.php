<?php
/*******************************************************************************************************************
 * @Clase maestra para afectar partidas`presupuestarias
 * @version: 1.0
 * @Fecha creacion:
 * @Autor:
 ********************************************************************************************************************
 * @Fecha Modificacion:
 * @Autor:
 * @Descripcion:
 ********************************************************************************************************************/

/**
 *
 */
class MaestroAfectarPartida
{

    public function mostrarPartidaAfectar()
    {
        //consultar la partida para mostrar los datos en el formulario para ser afectada

        $db                   = new Conexion();
        $idMaestroPresupuesto = $_POST["idmaestro_presupuesto"];

        $sql = "  SELECT 	maestro_presupuesto.idRegistro AS IdPresupuesto,
        					maestro_presupuesto.idcategoria_programatica AS IdCategoria,
        					maestro_presupuesto.idclasificador_presupuestario AS IdPartida,
					        categoria_programatica.codigo AS CodCategoria,
					        fuente_financiamiento.denominacion AS FuenteFinanciamiento,
					        clasificador_presupuestario.partida AS Par,
					        clasificador_presupuestario.generica AS Gen,
					        clasificador_presupuestario.especifica AS Esp,
					        clasificador_presupuestario.sub_especifica AS Sesp,
					        clasificador_presupuestario.denominacion AS NomPartida,
					        ordinal.codigo AS codordinal,
        					ordinal.denominacion AS nomordinal
					FROM
						maestro_presupuesto, categoria_programatica, fuente_financiamiento, clasificador_presupuestario, ordinal
					WHERE
						maestro_presupuesto.idRegistro = '" . $idMaestroPresupuesto . "'
						AND categoria_programatica.idcategoria_programatica = maestro_presupuesto.idcategoria_programatica
						AND fuente_financiamiento.idfuente_financiamiento = maestro_presupuesto.idfuente_financiamiento
						AND clasificador_presupuestario.idclasificador_presupuestario = maestro_presupuesto.idclasificador_presupuestario
						AND ordinal.idordinal = maestro_presupuesto.idordinal
				";

        $consulta = $db->query($sql);
        $registro = $db->recorrer($consulta);

        //cargar todos los registros para mostrarlos con js en el formulario
        //
        echo $idMaestroPresupuesto . "|.|" .
            $registro["FuenteFinanciamiento"] . "|.|" .
            $registro["CodCategoria"] . "|.|" .
            $registro["Par"] . "|.|" .
            $registro["Gen"] . "|.|" .
            $registro["Esp"] . "|.|" .
            $registro["Sesp"] . "|.|" .
            $registro["NomPartida"] . "|.|" .
            $registro["codordinal"] . "|.|" .
            $registro["nomordinal"];

    }
}
