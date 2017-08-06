/*****************************************************************************
* @
* @versión: 1.0
* @fecha creación:
* @autor:
******************************************************************************
* @fecha modificacion
* @autor
* @descripcion
******************************************************************************/

$(document).ready(function() {

	//cargo el select de tipos de presupuesto
	var dataString = 'ejecutar_lista=llena_tipo_presupuesto';
	$.ajax({
		type: "POST",
		url: "../../../lib/listas/controlador/listaPresupuesto.Controller.php",
		data: dataString,
		success: function(response) {

			$("#tipo_presupuesto").html(response);

		},
		error: function(xhr, status){
			alert("error tipo presupuesto");
		}
	});

	//cargo el select de fuentes de financiamiento
	var dataString = 'ejecutar_lista=llena_fuente_financiamiento';
	$.ajax({
		type: "POST",
		url: "../../../lib/listas/controlador/listaPresupuesto.Controller.php",
		data: dataString,
		success: function(response) {

			$("#fuente_financiamiento").html(response);

		},
		error: function(xhr, status){
			alert("error fuente de financiamiento");
		}
	});

	//cargo el select de categorias programaticas
	var dataString = 'ejecutar_lista=llena_categoria_programatica';
	$.ajax({
		type: "POST",
		url: "../../../lib/listas/controlador/listaPresupuesto.Controller.php",
		data: dataString,
		success: function(response) {

			$("#categoria_programatica").html(response);

		},
		error: function(xhr, status){
			alert("error categoria programatica");
		}
	});
});


//Al precionar Enter o hacer click en el boton Buscar se carga la lista de presupuesto
//
	$("#btnBuscarListaPpto").click(function() {

		buscarPpto();

	});

	$("#texto_busqueda").keypress(function(e) {

		if(e.which == 13){
			buscarPpto();
		}

	});

	function buscarPpto()
	{
		//Obtenemos el valor de los campos
		var tipo_presupuesto       = $("#tipo_presupuesto").val();
		var fuente_financiamiento  = $("#fuente_financiamiento").val();
		var categoria_programatica = $("#categoria_programatica").val();
		var texto_busqueda         = $("input#texto_busqueda").val();
		var destino                = $("input#destino").val();

		//Construimos la variable que se guardará en el data del Ajax para pasar al archivo php que procesará los datos
		var dataStringLista = 	'tipo_presupuesto=' + tipo_presupuesto +
								'&fuente_financiamiento=' + fuente_financiamiento +
								'&categoria_programatica=' + categoria_programatica +
								'&texto_busqueda=' + texto_busqueda +
								'&destino=' + destino +
								'&ejecutar_lista=buscar_lista_presupuesto';


		$.ajax({
			type: "POST",
			url: "../../../lib/listas/controlador/listaPresupuesto.Controller.php",
			data: dataStringLista,
			error: function(xhr, status){
				alert(xhr);
				alert(status);
			},
			success: function(response) {

				if(response == 'vacio'){

					$("#_MENSAJE_WARNING_").css("display", "block");
					$("#_MENSAJE_WARNING_").html('<strong>¡Alerta!</strong>    No se encontraron registros con los criterios de busqueda....');
					$("#_MENSAJE_WARNING_").delay(4500).slideUp(2000);

				}else{
					$("#divResultado").css("display","block");
					$("#mostrarResultados").html(response);
					TablaPaginada('lista_presupuesto');
				}

			}
		});
	}