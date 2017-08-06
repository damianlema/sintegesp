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

			alert(response);

			//$("#cuerpoPartidasDisminuidas").html(response);
			//TablaPaginada('tabla_disminuir');

		}
	});

}