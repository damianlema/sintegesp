<?php
/*******************************************************************************************************************
 * @Clase para gestionar metodos de consulta de traslados presupuestarios
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
class ListaTraslados
{

    public function listarTrasladosPresupuestarios()
    {
        $db  = new Conexion();
        $sql = $db->query("SELECT * FROM traslados_presupuestarios ORDER BY nro_solicitud");
        ?>
		<table data-page-length='5' align="center" id="modal_traslados_presupuestarios"
				class="table table-striped table=hover display" width="100%">
          	<thead>
                <tr class="info">
                	<th style="width: 10%; height: 5px; padding: 0px;">
                		<h6 align="left"><small><strong>Nro. Solicitud</strong></small></h6>
                	</th>
                  	<th style="width: 10%; height: 5px; padding: 0px;">
                  		<h6 align="left"><small><strong>Fecha Solicitud</strong></small></h6>
                  	</th>
                  	<th style="width: 67%; height: 5px; padding: 0px;">
                  		<h6 align="left"><small><strong>Concepto</strong></small></h6>
                  	</th>
                  	<th style="width: 7%; height: 5px; padding: 0px;">
                  		<h6 align="left"><small><strong>Estado</strong></small></h6>
                  	</th>
                  	<!--
                  	<th style="width: 4%; height: 5px; padding: 0px;">
                  		<h6 align="left"><small><strong>&nbsp;</strong></small></h6>
                  	</th>
                  	-->
                </tr>
          	</thead>
          	<tbody>
				<?php
while ($registros = $db->recorrer($sql)) {
            $fecha           = explode("-", $registros["fecha_solicitud"]);
            $fecha_solicitud = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];
            ?>
			                <tr style="cursor:pointer" data-toggle="modal" data-target="#miModalTraslado"
			                	onclick="consultarTrasladoPresupuestario(<?=$registros['idtraslados_presupuestarios']?>);">
			                  	<td style="width: 10%; height: 5px; padding: 0px;" align='left'>
			                  		<h6><?=$registros["nro_solicitud"]?></h6></td>
			                  	<td style="width: 10%; height: 5px; padding: 0px;" align='center'>
			                  		<h6><?=$fecha_solicitud?></h6></td>
			                  	<td style="width: 67%; height: 5px; padding: 0px;" align='left'>
			                  		<h6><?=$registros["justificacion"]?></h6></td>
			                  	<td style="width: 7%; height: 5px; padding: 0px;" align='left'>
			                  		<h6><?=$registros["estado"]?></h6></td>
			                </tr>
			            <?
        }
        ?>
        	</tbody>
		</table>
		<?php
}

    //Metodo para consultar los datos de la cabecera del traslado presupuestario
    //
    public function consultarTrasladosPresupuestarios()
    {
        $idtraslado_presupuestario = $_POST['idtraslado_presupuestario'];
        $db                        = new Conexion();
        $sql                       = $db->query("SELECT * FROM traslados_presupuestarios
									WHERE idtraslados_presupuestarios = '" . $idtraslado_presupuestario . "'
									ORDER BY nro_solicitud");
        $registro = $db->recorrer($sql);
        echo $registro["idtraslados_presupuestarios"] . "|.|" .
            $registro["nro_solicitud"] . "|.|" .
            $registro["fecha_solicitud"] . "|.|" .
            $registro["nro_resolucion"] . "|.|" .
            $registro["fecha_resolucion"] . "|.|" .
            $registro["justificacion"] . "|.|" .
            $registro["estado"] . "|.|" .
            $registro["total_debito"] . "|.|" .
            $registro["total_credito"];
    }

    //Metodo para cargar las partidas disminuidas en el traslado
    //
    public function mostrarPartidasDisminuidas()
    {
        $idtraslado_presupuestario = $_POST['idtraslado_presupuestario'];
        $db                        = new Conexion();
        $sql                       = $db->query("SELECT 	clasificador_presupuestario.codigo_cuenta as codigopartida,
									clasificador_presupuestario.partida as partida,
									clasificador_presupuestario.generica as generica,
									clasificador_presupuestario.especifica as especifica,
									clasificador_presupuestario.sub_especifica as sub_especifica,
									clasificador_presupuestario.denominacion as denopartida,
									categoria_programatica.codigo as codigocategoria,
									unidad_ejecutora.denominacion as denocategoriaprogramatica,
									maestro_presupuesto.idregistro as idmaestro_presupuesto,
									maestro_presupuesto.anio as anio,
									ordinal.codigo as codigoordinal,
									ordinal.denominacion as denoordinal,
									partidas_cedentes_traslado.idtraslados_presupuestarios,
									partidas_cedentes_traslado.idmaestro_presupuesto,
									partidas_cedentes_traslado.idpartida_cedentes_traslado,
									partidas_cedentes_traslado.monto_debitar,
									fuente_financiamiento.denominacion as denominacion_fuente
										FROM
									partidas_cedentes_traslado,
									clasificador_presupuestario,
									categoria_programatica,
									maestro_presupuesto,
									unidad_ejecutora,
									ordinal,
									fuente_financiamiento
										WHERE
										partidas_cedentes_traslado.status='a'
									and partidas_cedentes_traslado.idtraslados_presupuestarios=" . $idtraslado_presupuestario . "
									and maestro_presupuesto.idRegistro=partidas_cedentes_traslado.idmaestro_presupuesto
									and clasificador_presupuestario.idclasificador_presupuestario=maestro_presupuesto.idclasificador_presupuestario
									and categoria_programatica.idcategoria_programatica=maestro_presupuesto.idcategoria_programatica
									and unidad_ejecutora.idunidad_ejecutora=categoria_programatica.idunidad_ejecutora
									and ordinal.idordinal=maestro_presupuesto.idordinal
									and fuente_financiamiento.idfuente_financiamiento = maestro_presupuesto.idfuente_financiamiento
										ORDER BY
									categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC");

        ?>

		<table data-page-length='5' align="center" id="tabla_disminuir" class="table table-striped table=hover display" width="100%">
          	<thead>
            	<tr class="info">
            		<th style="width: 13%; height: 5px; padding: 0px;">
            			<h6 align="center"><small><strong>Fuente de Financiamiento</strong></small></h6>
            		</th>
            		<th style="width: 8%; height: 5px; padding: 0px;">
            			<h6 align="center"><small><strong>Categoria Programática</strong></small></h6>
            		</th>
              		<th style="width: 12%; height: 5px; padding: 0px;">
              			<h6 align="center"><small><strong>Partida Presupuestaria</strong></small></h6>
              		</th>
              		<th style="width: 40%; height: 5px; padding: 0px;">
              			<h6 align="center"><small><strong>Descripción</strong></small></h6>
              		</th>
              		<th style="width: 15%; height: 5px; padding: 0px;">
              			<h6 align="center"><small><strong>Monto Bs.</strong></small></h6>
              		</th>
              		<!--
              		<th style="width: 7%; height: 5px; padding: 0px;">
              			<h6 align="center"><small><strong></strong></small></h6>
              		</th>
              		-->
            	</tr>
          	</thead>
          	<tbody>
          		<?php
while ($registros = $db->recorrer($sql)) {

            ?>
	                <tr>
	                	<!--
	                	onclick="consultarTrasladoPresupuestario(<?=$registros['idpartida_cedentes_traslado']?>);"
	                	-->

	                  	<td style="width: 13%; height: 5px; padding: 0px;" align='left'>
	                  		<h6 style="font-size: 9;"><?=$registros["denominacion_fuente"]?></h6></td>
	                  	<td style="width: 8%; height: 5px; padding: 0px;" align='center'>
	                  		<h6 style="font-size: 9;"><?=$registros["codigocategoria"]?></h6></td>
	                  	<td style="width: 12%; height: 5px; padding: 0px;" align='center'>
	                  		<h6 style="font-size: 9;"><?=$registros["partida"] . "." . $registros["generica"] . "." . $registros["especifica"] . "." . $registros["sub_especifica"] . " (" . $registros["codigoordinal"] . ")"?></h6></td>
	                  	<td style="width: 40%; height: 5px; padding: 0px;" align='left'>
	                  		<h6><?=$registros["denopartida"]?></h6></td>
	                  	<td style="width: 15%; height: 5px; padding: 0px; text-align: right;" align='right'>
	                  		<h6><?=number_format($registros["monto_debitar"], 2, ",", ".")?></h6></td>
	                </tr>
	            	<?php
}
        ?>
          	</tbody>
        </table>

		<?php
}

    //Metodo para cargar las partidas aumentadas en el traslado
    //
    public function mostrarPartidasAumentadas()
    {
        $idtraslado_presupuestario = $_POST['idtraslado_presupuestario'];
        $db                        = new Conexion();
        $sql                       = $db->query("SELECT 	clasificador_presupuestario.codigo_cuenta as codigopartida,
									clasificador_presupuestario.partida as partida,
									clasificador_presupuestario.generica as generica,
									clasificador_presupuestario.especifica as especifica,
									clasificador_presupuestario.sub_especifica as sub_especifica,
									clasificador_presupuestario.denominacion as denopartida,
									categoria_programatica.codigo as codigocategoria,
									unidad_ejecutora.denominacion as denocategoriaprogramatica,
									maestro_presupuesto.idregistro as idmaestro_presupuesto,
									maestro_presupuesto.anio as anio,
									ordinal.codigo as codigoordinal,
									ordinal.denominacion as denoordinal,
									partidas_receptoras_traslado.idtraslados_presupuestarios,
									partidas_receptoras_traslado.idmaestro_presupuesto,
									partidas_receptoras_traslado.idpartida_receptoras_traslado,
									partidas_receptoras_traslado.monto_acreditar,
									fuente_financiamiento.denominacion as denominacion_fuente
										FROM
									partidas_receptoras_traslado,
									clasificador_presupuestario,
									categoria_programatica,
									maestro_presupuesto,
									unidad_ejecutora,
									ordinal,
									fuente_financiamiento
										WHERE
										partidas_receptoras_traslado.status='a'
									and partidas_receptoras_traslado.idtraslados_presupuestarios=" . $idtraslado_presupuestario . "
									and maestro_presupuesto.idRegistro=partidas_receptoras_traslado.idmaestro_presupuesto
									and clasificador_presupuestario.idclasificador_presupuestario=maestro_presupuesto.idclasificador_presupuestario
									and categoria_programatica.idcategoria_programatica=maestro_presupuesto.idcategoria_programatica
									and unidad_ejecutora.idunidad_ejecutora=categoria_programatica.idunidad_ejecutora
									and ordinal.idordinal=maestro_presupuesto.idordinal
									and fuente_financiamiento.idfuente_financiamiento = maestro_presupuesto.idfuente_financiamiento
										ORDER BY
									categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC");

        ?>

		<table data-page-length='5' align="center" id="tabla_aumentar" class="table table-striped table=hover display" width="100%">
          	<thead>
            	<tr class="info">
            		<th style="width: 13%; height: 5px; padding: 0px;">
            			<h6 align="center"><small><strong>Fuente de Financiamiento</strong></small></h6>
            		</th>
            		<th style="width: 8%; height: 5px; padding: 0px;">
            			<h6 align="center"><small><strong>Categoria Programática</strong></small></h6>
            		</th>
              		<th style="width: 12%; height: 5px; padding: 0px;">
              			<h6 align="center"><small><strong>Partida Presupuestaria</strong></small></h6>
              		</th>
              		<th style="width: 40%; height: 5px; padding: 0px;">
              			<h6 align="center"><small><strong>Descripción</strong></small></h6>
              		</th>
              		<th style="width: 15%; height: 5px; padding: 0px;">
              			<h6 align="center"><small><strong>Monto Bs.</strong></small></h6>
              		</th>
              		<!--
              		<th style="width: 7%; height: 5px; padding: 0px;">
              			<h6 align="center"><small><strong></strong></small></h6>
              		</th>
              		-->
            	</tr>
          	</thead>
          	<tbody>
          		<?php
while ($registros = $db->recorrer($sql)) {

            ?>
	                <tr>
	                	<!--
	                	onclick="consultarTrasladoPresupuestario(<?=$registros['idpartida_receptoras_traslado']?>);"
	                	-->

	                  	<td style="width: 13%; height: 5px; padding: 0px;" align='left'>
	                  		<h6 style="font-size: 9;"><?=$registros["denominacion_fuente"]?></h6></td>
	                  	<td style="width: 8%; height: 5px; padding: 0px;" align='center'>
	                  		<h6 style="font-size: 9;"><?=$registros["codigocategoria"]?></h6></td>
	                  	<td style="width: 12%; height: 5px; padding: 0px;" align='center'>
	                  		<h6 style="font-size: 9;"><?=$registros["partida"] . "." . $registros["generica"] . "." . $registros["especifica"] . "." . $registros["sub_especifica"] . " (" . $registros["codigoordinal"] . ")"?></h6></td>
	                  	<td style="width: 40%; height: 5px; padding: 0px;" align='left'>
	                  		<h6><?=$registros["denopartida"]?></h6></td>
	                  	<td style="width: 15%; height: 5px; padding: 0px; text-align: right;" align='right'>
	                  		<h6><?=number_format($registros["monto_acreditar"], 2, ",", ".")?></h6></td>
	                </tr>
	            	<?php
}
        ?>
          	</tbody>
        </table>

		<?php
}

}
?>