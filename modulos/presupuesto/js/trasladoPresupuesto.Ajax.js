/*****************************************************************************
* @Funciones javascript para controlar los eventos de la vista trasladoPresupuesto.view.php
* @versión: 1.0
* @fecha creación: 30/07/2016
* @autor: Hector Damian Lema
******************************************************************************
* @fecha modificacion
* @autor
* @descripcion
******************************************************************************/


//Al cargar el documento se ejecutan las siguientes funciones
//
$(document).ready(function() {

	//Formato de los datatime
	//
	$(function () {
        $('#datetimepicker1').datetimepicker({
        	viewMode: 'days',
            format: 'DD-MM-YYYY',
            locale: 'es'
        })
    });
    $(function () {
        $('#datetimepicker2').datetimepicker({
        	viewMode: 'days',
            format: 'DD-MM-YYYY',
            locale: 'es'
        });
    });


    //Validaciones de los campos del formulario
    //
	$('#formCabecera').bootstrapValidator({
		icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },

		 fields: {
			 numeroSolicitud: {
				 validators: {
					 notEmpty: {
						 message: 'El Numero de Solicitud es requerido'
					 }
				 }
			 },

			 datetimepicker1: {
				 validators: {
					 notEmpty: {
						message: 'La Fecha de Solicitud es requerida'
					 },
				 }
			 },

			  concepto: {
				 validators: {
					 notEmpty: {
						 message: 'El Concepto es requerido'
					 }
				 }
			 }
		}
	});



	//Al cargar el documento se llena la lista de la ventana modal de Traslados de Presupuestos para consultarlos
	//
	$.ajax({
		type: "POST",
		url: "modulos/presupuesto/controlador/trasladoPresupuesto.controller.php",
		data: 'ejecutar=listar_traslados_presupuestarios',
		success: function(response) {

			$("#cuerpoModalTrasladosPresupuestario").html(response);
			TablaPaginada('modal_traslados_presupuestarios');

		}
	});

});


//Al hacer click en el boton Continuar se valida y registra la cabecera del traslado
//
	$("#btnContinuar").click(function() {

		//Obtenemos el valor de los campos
		var numero_solicitud  = $("input#numeroSolicitud").val();

		//Validamos el campo Numero de Solicitud, simplemente miramos que no esté vacío
		if (numero_solicitud == "") {

			$("#_MENSAJE_DANGER_").css("display", "block");
			$("#_MENSAJE_DANGER_").html('<strong>¡ERROR!</strong>    Debe ingresar un Número de solicitud....');
			$("#_MENSAJE_DANGER_").slideUp(2000).delay(2500);

			$("input#numeroSolicitud").focus();
			return false;
		}

		var fecha_solicitud   = $('input#datetimepicker1').val();
		var numero_resolucion = $('input#numeroResolucion').val();
		var fecha_resolucion  = $('input#datetimepicker2').val();
		var concepto          = $('#concepto').val();

		if (concepto == "") {

			$("#_MENSAJE_DANGER_").css("display", "block");
			$("#_MENSAJE_DANGER_").html('<strong>¡ERROR!</strong>    Debe ingresar un Concepto o Justificación de la solicitud....');
			$("#_MENSAJE_DANGER_").slideUp(2000).delay(2500);

			$("input#concepto").focus();
			return false;
		}

		//Construimos la variable que se guardará en el data del Ajax para pasar al archivo php que procesará los datos
		var dataString = 	'numero_solicitud=' + numero_solicitud +
							'&fecha_solicitud=' + fecha_solicitud +
							'&numero_resolucion=' + numero_resolucion +
							'&fecha_resolucion=' + fecha_resolucion +
							'&concepto=' + concepto +
							'&ejecutar=ingresar_datos_basicos';

		$.ajax({
			type: "POST",
			url: "modulos/presupuesto/controlador/trasladoPresupuesto.controller.php",
			data: dataString,
			success: function(response) {

				respuesta = response.split("|.|");

				if(respuesta[0] == 1){

					$("#idTrasladoPresupuestario").val(respuesta[1]);

					$("#_MENSAJE_SUCCESS_").css("display", "block");
					$("#_MENSAJE_SUCCESS_").html('<strong>¡Continue!</strong>    Registro de datos exitoso, continue con la carga de las partidas....');
					$("#_MENSAJE_SUCCESS_").delay(4500).slideUp(2000);

					mostrarDiv();
					actualizarBotones();

				}else if(respuesta[0] == 2){

					$("#_MENSAJE_DANGER_").css("display", "block");
					$("#_MENSAJE_DANGER_").html('<strong>¡ERROR!</strong>    El Número de solicitud ya se encuentra registrado, por favor verifique el Número de Solicitud ingresado....');
					$("#_MENSAJE_DANGER_").delay(4500).slideUp(2000);

				}else if(respuesta[0] == 3){

					$("#_MENSAJE_WARNING_").css("display", "block");
					$("#_MENSAJE_WARNING_").html('<strong>¡Alerta!</strong>    Por favor complete los  campos requeridos del formulario....');
					$("#_MENSAJE_WARNING_").delay(4500).slideUp(2000);

				}

			}
		});
		return false;

	});


//Al hacer click en el boton actulizar se entra a validar y actualizar datos de la cabecera del traslado
//
	$("#btnActualizar").click(function() {
		//console.log("actualizar ");
		//
		var idtraslado_presupuestario = $('input#idTrasladoPresupuestario').val();
		var fecha_solicitud           = $('input#datetimepicker1').val();
		var numero_resolucion         = $('input#numeroResolucion').val();
		var fecha_resolucion          = $('input#datetimepicker2').val();
		var concepto                  = $('#concepto').val();

		if (concepto == "") {

			$("#_MENSAJE_DANGER_").css("display", "block");
			$("#_MENSAJE_DANGER_").html('<strong>¡ERROR!</strong>    Debe ingresar un Concepto o Justificación de la solicitud....');
			$("#_MENSAJE_DANGER_").slideUp(2000).delay(2500);

			$("input#concepto").focus();
			return false;
		}

		//Construimos la variable que se guardará en el data del Ajax para pasar al archivo php que procesará los datos
		var dataString = 	'idtraslado_presupuestario=' + idtraslado_presupuestario +
							'&fecha_solicitud=' + fecha_solicitud +
							'&numero_resolucion=' + numero_resolucion +
							'&fecha_resolucion=' + fecha_resolucion +
							'&concepto=' + concepto +
							'&ejecutar=actualizar_datos_basicos';

		$.ajax({
			type: "POST",
			url: "modulos/presupuesto/controlador/trasladoPresupuesto.controller.php",
			data: dataString,
			success: function(response) {

				respuesta = response.split("|.|");

				if(respuesta[0] == 1){

					$("#_MENSAJE_SUCCESS_").css("display", "block");
					$("#_MENSAJE_SUCCESS_").html('<strong>¡Continue!</strong>    Actualización de datos exitoso, continue con la carga de las partidas....');
					$("#_MENSAJE_SUCCESS_").delay(4500).slideUp(2000);

				}else if(respuesta[0] == 2){

					$("#_MENSAJE_DANGER_").css("display", "block");
					$("#_MENSAJE_DANGER_").html('<strong>ERROR!</strong>   A ocurrido un error en la actualizacion. (Código del error: '+respuesta[1]+')');
					$("#_MENSAJE_DANGER_").delay(4500).slideUp(2000);

				}else if(respuesta[0] == 3){

					$("#_MENSAJE_WARNING_").css("display", "block");
					$("#_MENSAJE_WARNING_").html('<strong>¡Alerta!</strong>    Por favor complete los  campos requeridos del formulario....');
					$("#_MENSAJE_WARNING_").delay(4500).slideUp(2000);

				}

			}
		});
		return false;

	});


//Funcion para cargar los datos seleccionados en el modal y mostrarlos en el formulario de traslados
//
function consultarTrasladoPresupuestario(idtraslado_presupuestario)
{
	//console.log(idtraslado_presupuestario);
	var dataString = 	'idtraslado_presupuestario=' + idtraslado_presupuestario +
						'&ejecutar=consultar_traslado_presupuestario';
	$.ajax({
		type: "POST",
		url: "modulos/presupuesto/controlador/trasladoPresupuesto.controller.php",
		data: dataString,
		success: function(response) {

			respuesta = response.split("|.|");
			fecha = respuesta[2].split("-");
			fecha_solicitud = fecha[2]+"-"+fecha[1]+"-"+fecha[0];
			fecha = respuesta[4].split("-");
			fecha_resolucion = fecha[2]+"-"+fecha[1]+"-"+fecha[0];

			$('input#idTrasladoPresupuestario').val(idtraslado_presupuestario);
			$("input#numeroSolicitud").val(respuesta[1]);
			$('input#datetimepicker1').val(fecha_solicitud);
			$('input#numeroResolucion').val(respuesta[3]);
			$('input#datetimepicker2').val(fecha_resolucion);
			$('#concepto').val(respuesta[5]);
			$('input#estado').val(respuesta[6]);
			$('input#disminucionesBs').val(formatNumber.new(respuesta[7]));
			$('input#aumentosBs').val(formatNumber.new(respuesta[8]));
			mostrarDiv();
			mostrarPartidasDisminuidas(idtraslado_presupuestario);
			mostrarPartidasAumentadas(idtraslado_presupuestario);
			actualizarBotones();
		}
	});

}


//Funcion para cargar el datatable de las partidas disminuidas
//
function mostrarPartidasDisminuidas(idtraslado_presupuestario)
{

	var dataString = 	'idtraslado_presupuestario=' + idtraslado_presupuestario +
						'&ejecutar=mostrar_partidas_disminuidas';
	$.ajax({
		type: "POST",
		url: "modulos/presupuesto/controlador/trasladoPresupuesto.controller.php",
		data: dataString,
		success: function(response) {

			$("#cuerpoPartidasDisminuidas").html(response);
			TablaPaginada('tabla_disminuir');

		}
	});
}


//Funcion para cargar el datatable de las partidas aumentadas
//
function mostrarPartidasAumentadas(idtraslado_presupuestario)
{

	var dataString = 	'idtraslado_presupuestario=' + idtraslado_presupuestario +
						'&ejecutar=mostrar_partidas_aumentadas';
	$.ajax({
		type: "POST",
		url: "modulos/presupuesto/controlador/trasladoPresupuesto.controller.php",
		data: dataString,
		success: function(response) {

			$("#cuerpoPartidasAumentadas").html(response);
			TablaPaginada('tabla_aumentar');

		}
	});
}

//Funcion para mostrar y ocultar div en el formulario
//
function mostrarDiv()
{
	$("#divTablas").css("display","block");
}

//Funcion para actualizar los botones segun el estado del traslado
//
function actualizarBotones()
{
	var estado = $('input#estado').val();

	switch(estado){

		case 'En elaboración':
			$("#btnContinuar").css("display","none");
			$("#btnActualizar").css("display","block");
			$("#btnProcesar").css("display","block");
			$("#btnAnular").css("display","none");
			$("#btnDuplicar").css("display","none");
			$("#btnEliminar").css("display","block");
			$("#btnBuscarPartidaDisminuir").css("display","block");
			$("#btnCargarPartidaDisminuir").css("display","block");
			$("#btnBuscarPartidaAumentar").css("display","block");
			$("#btnCargarPartidaAumentar").css("display","block");
			$("#numeroSolicitud").prop('disabled',true);
		break;

		case 'Procesado':
			$("#btnContinuar").css("display","none");
			$("#btnActualizar").css("display","block");
			$("#btnProcesar").css("display","none");
			$("#btnAnular").css("display","block");
			$("#btnDuplicar").css("display","block");
			$("#btnEliminar").css("display","none");
			$("#btnBuscarPartidaDisminuir").css("display","none");
			$("#btnCargarPartidaDisminuir").css("display","none");
			$("#btnBuscarPartidaAumentar").css("display","none");
			$("#btnCargarPartidaAumentar").css("display","none");
			$("#numeroSolicitud").prop('disabled',true);
		break;

		case 'Anulado':
			$("#btnContinuar").css("display","none");
			$("#btnActualizar").css("display","none");
			$("#btnProcesar").css("display","none");
			$("#btnAnular").css("display","none");
			$("#btnDuplicar").css("display","block");
			$("#btnEliminar").css("display","block");
			$("#btnBuscarPartidaDisminuir").css("display","none");
			$("#btnCargarPartidaDisminuir").css("display","none");
			$("#btnBuscarPartidaAumentar").css("display","none");
			$("#btnCargarPartidaAumentar").css("display","none");
			$("#numeroSolicitud").prop('disabled',true);
		break;

	}

}




