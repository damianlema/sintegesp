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



$(document).ready(function() {

	$("#btnContinuar").click(function() {


//console.log("aa "+concepto);

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

					$("#divTablas").css("display","block");
					$("#btnContinuar").css("display","none");
					$("#btnActualizar").css("display","block");
					$("#numeroSolicitud").prop('disabled',true);

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

});


