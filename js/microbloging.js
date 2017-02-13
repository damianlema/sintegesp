
//Variables globales
var i=0;
var cant=0;

//DOM jquery
$(document).ready(
	function()
	{
		// $(this).mouseover(
			// function()
			// {
				// cargarMensajesRecientes(cantidad);
			// }
		// );
		//Llama a la funcion que muestra los mensajes en la base de datos
		cantidad = 3;
		cargarMensajesRecientes(cantidad);

		//Click en dejar mensaje
		$(".dejarMensaje").click(
			function()
			{
				if(i==0)
				{
					//Muestra el area de texto
					$(".areaTexto").show();
					i++;
				}
				else
				{
					//Oculta el area de texto
					$(".areaTexto").hide();
					i=0;
				}

		});

		//Mouse over/out en mensaje
		$(".mensaje").live("mouseover",
			function()
			{
				//Muestra las opciones del mensaje
				$("#opcion_"+$(this).attr("idMensaje")).show();
			}
		).live("mouseout",
			function()
			{
				//Oculta las opciones del mensaje
				$("#opcion_"+$(this).attr("idMensaje")).hide();
			});

		//Click en las opciones del mensaje
		$(".opciones").live("click",
			function()
			{
				//Si la opcion es del tipo 1
				if($(this).attr("opcion")==1)
				{
					//Llama a la funcion eliminar mensjae
					eliminarMensaje($(this).attr("idMensaje"));
				}
				else
				{
					//Llama a la funcion reenviar mensaje
					reenviarMensaje($(this).attr("idMensaje"));
				}
			});

		//Setea las opciones para el text counter
		var options = {
				'maxCharacterSize': 140,
				'originalStyle': 'originalDisplayInfo',
				'warningStyle': 'warningDisplayInfo',
				'warningNumber': 40,
				'displayFormat': '#left '
				};
				$('#texto').textareaCount(options);

		//Click en publicar
		$("#publicar").click(
			function()
			{
				//Si no esta vacio
				if($(".texto").val()!='')
				{
					//Llama a la funcion guardar mensaje
					guardarMensaje($(".texto").val());
				}
			});

		//Click en anteriores
		$(".anteriores").click(
			function()
			{
				//Aumenta la cantidad de mensajes
				cantidad = cantidad + 5;
				//Llama a la funcion de mostrar mensajes
				cargarMensajesRecientes(cantidad);
			}
		);
		//Click en cambiar avatar
		$(".cambiarAvatar").click(
			function()
			{
				$("#nombreAvatar").val("");
				$("#archivoAvatar").val("");
				$("#avatar").show();
			});

		//Click en cancelar cambio de avatar
		$("#cancelarCambioAvatar").click(
			function()
			{
				$("#archivoAvatar").val("");
				$("#nombreAvatar").val("");
				$("#imagenAvatar").attr("src",$("#nombreAvatarActual").val());
				$("#avatar").hide();
			});

		//Click en guardar cambios
		$("#guardarCambio").click(
			function()
			{
				//Si no se selecciono una imagen
				if($("#nombreAvatar").val()=='')
				{
					$("#avatar").hide();
				}
				else
				{
					//Llama a funcion moverAvatar
					moverAvatar($("#nombreAvatar").val());
					$("#avatar").hide();
					//Llama a funcion de mostrar mensajes
					cargarMensajesRecientes(cantidad);
				}

			});

		//Cambio en el campo file de seleccion de avatar
		$("#archivoAvatar").change(
				function()
				{
					var nombre = $("#archivoAvatar").val();
					var extension = new String;
					extension = (nombre.substring(nombre.lastIndexOf("."))).toLowerCase();
					if((extension=='.jpg')||(extension=='.png'))
					{

						$("#upload_target").remove();
						//Inserta el iframe necesario para la carga de la imagen
						$("#iframe").html("<iframe id='upload_target' name='upload_target' src='#' style='width:10px;height:10px;border:0px solid #fff;'></iframe>");

						$("#formAvatar").submit();
						var SoloNombre = new String;
						soloNombre = (nombre.substring(nombre.lastIndexOf("\\")));

						var texto = soloNombre.split("\\");
						if(texto[0]=='')
						{
							var nombre = texto[1];
						}
						else
						{
							var nombre = texto[0];
						}

						$("#nombreAvatar").val(nombre);
						//Llama a la funcion starUpload para cargar imagen
						startUpload();
					}
			});
});

//Funcion para inicio de carga
function startUpload()
	{
      	return true;
	}

//Funcion para finalizar la carga
function stopUpload(success)
	{
      var result = '';
      //Si la carga es exitosa
      if (success == 1)
      {
      	//Cambia imagen de previsualizar
      	$("#imagenAvatar").attr("src","../../imagenes/tmp/"+$("#nombreAvatar").val());
      }
      return true;
	}

//Funcion para mover y renombrar imagen
function moverAvatar(nombre)
{
	//Variable con la data
	var data = 'nombre='+nombre;

	//Envio por post
	$.post('../moverAvatar.php', data,
	function(envio)
	{
	}, 'JSON').error(function()
		{

		});
		return false;
}

//Funcion para guardar mensaje
function guardarMensaje(mensaje)
{
	//Variable con la data
	var data = "mensaje="+mensaje;

	//Envio por post
	$.post('../crearMensajeAjax.php', data,
	function(envio)
	{
		if(envio.estado)
		{

			//Operaciones visuales
			$(".notificacion").html();
			$(".texto").val("");
			$(".areaTexto").fadeOut();

			//Llama a la funcion de mostrar mensajes
			cargarMensajesRecientes(cantidad);
			i=0;

		}
		else
		{
			//Notifica la existencia de un error
			$(".notificacion").html("Su mensaje no pudo ser enviado").show().fadeOut(2000);
		}
	}, 'JSON').error(function()
		{

		});
		return false;
}

//Funcion para cargar mensajes
function cargarMensajesRecientes(cantidad)
{

	//Variable con la cantidad de mensajes a presentar
	var data="cantidad="+cantidad;

	//Envio por post
	$.post('../mensajesAjax.php', data,
	function(envio)
	{
		if(envio.estado)
		{
			//Operaciones visuales
			$(".mensajes").html("");

			var datos = envio.data;
			var i;

			//Se imprimen los mensajes en pantalla
			for( i=0; i<envio.cantidad;i++)
			{
				$(".mensajes").append('<div class="mensaje" id="mensaje_'+datos[i].id_mensaje+'" idMensaje="'+datos[i].id_mensaje+'" style="height: 85px; width: 480px; border-bottom: 1px solid #CCC;"><div class="image" style="position: relative; top: 12px; width: 65px; height: 60px;"><img src="../../imagenes/avatar/'+datos[i].imagen+'" style="width: 60px; height: 50px; -webkit-border-radius: 5px; border-radius: 5px;"></div><div class="nombre" style="position: relative; top: -50px; left: 70px; width: 250px; "><span style="font-weight: bold; font-size: 12px;">'+datos[i].nombre+' '+datos[i].apellido+'</span><span class="usuario" style="font-size: 12px;  color: #9E9C9C;"> '+datos[i].usuario+'</span></div><div class="fecha" style="position: relative; top: -64px; left: 343px; font-size: 11px; width:145px; color:#9E9C9C;">Publicado el '+datos[i].fecha+'</div><div class="contenido" style="top: -60px; height:38px; position: relative; left: 70px; font-size: 11px; width: 380px;">'+datos[i].mensaje+'<span id="reenviante_'+datos[i].id_mensaje+'" style="font-weight: bold; "></span></div><div class="opciones" opcion="'+datos[i].opciones+'" idMensaje="'+datos[i].id_mensaje+'" id="opcion_'+datos[i].id_mensaje+'" style="top: -58px; position: relative; cursor:pointer; left: 430px; font-size: 10px; color:#A09D9D; width: 50px; display: none;" align="right" ></div></div>');				
				$(".mensaje_"+datos[i].id_mensaje).append("adicinal");

				//Si el mensaje es un reenvio
				if(datos[i].reenviante!='')
				{
					$("#reenviante_"+datos[i].id_mensaje).html(" (Reenviado por "+datos[i].reenviante+")");
				}

				//Si el mensaje pertenece al usuario
				if(datos[i].opciones==1)
				{
					$("#opcion_"+datos[i].id_mensaje).html("Borrar");
				}
				else
				{
					$("#opcion_"+datos[i].id_mensaje).html("Reenviar");
				}
			}
			//Si existen mas mensajes en la base de datos
			if(envio.msnAnterior=='si')
			{
				$(".anteriores").show();
			}
			else
			{
				$(".anteriores").hide();
			}

		}
		else
		{
			//Notificacion si no existen mensajes
			$(".mensajes").html(envio.msn);
		}
	}, 'JSON').error(function()
		{
			//Notificacion de error
			$(".mensajes").html("Problema al cargar mensajes");
		});
		return false;
}

//Funcion para reenviar mensaje
function reenviarMensaje(idMensaje)
{
	//Variable con el primary key del mensaje que sera reenviado
	var data = "idMensaje="+idMensaje;

	//Envio por post
	$.post('../reenviarMensajeAjax.php', data,
	function(envio)
	{
		if(envio.estado)
		{
			//LLama a la funcion de mostrar mensajes
			cargarMensajesRecientes(cantidad);
		}
	}, 'JSON').error(function()
		{

		});
		return false;
}

//Funcion de eliminar mensaje
function eliminarMensaje(idMensaje)
{
	//Variable con el primary key del mensaje que sera eliminado
	var data = "idMensaje="+idMensaje;

	//Envio por post
	$.post('../eliminarMensajeAjax.php', data,
	function(envio)
	{
		if(envio.estado)
		{

			//Llama a la funcion para mostrar mensajes
			cargarMensajesRecientes(cantidad);
		}
	}, 'JSON').error(function()
		{

		});
		return false;
}