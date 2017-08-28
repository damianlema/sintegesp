function consultarPpto(idmaestro_presupuesto, destino)
{

	if(destino == 'disminucion_traslado')
	{
		afecta = 'disminuir';
	}

	var dataString = 	'idmaestro_presupuesto=' + idmaestro_presupuesto +
						'&destino=' + destino +
						'&afecta=' + afecta +
						'&ejecutar_afectar=mostrar_partida_afectar';
	$.ajax({
		type: "POST",
		url: "modulos/presupuesto/controlador/maestroAfectarPartida.controller.php",
		data: dataString,
		success: function(response) {

			respuesta = response.split("|.|");
			codigo_partida = respuesta[3]+"."+respuesta[4]+"."+respuesta[5]+"."+respuesta[6]+" - "+respuesta[8];

			$('input#idmaestro_presupuesto_'+afecta).val(respuesta[0]);
			$('input#fuente_financiamiento_'+afecta).val(respuesta[1]);
			$('input#categoria_programatica_'+afecta).val(respuesta[2]);
			$('input#codigo_partida_'+afecta).val(codigo_partida);
			$('input#nombre_partida_'+afecta).val(respuesta[7]);

		}
	});

}