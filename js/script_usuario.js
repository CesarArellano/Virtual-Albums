$(document).ready(function(e)
{
  $('ul.tabs').tabs(); // Habilita las tabs.
  $("#verHistorialBusqueda").hide(); //Oculta el historial de búsqueda
  $("#modificarperfil").hide(); //Oculta modificar perfil
  $("#crearAlbum").hide(); // Oculta la forma del álbum
  $("#formSubirFoto").hide(); // Oculta la forma de subir foto.
  $("select").material_select(); // Inicializa el select
  $("select[required]").css({display: "block", height: 0, padding: 0, width: 0, position: 'absolute'}); // Muestra en pantalla un mensaje de que el campo del select está vacío.
  $('.datepicker').pickadate( // Inicializa el calendario datepicker de materialize
  {

		monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
		weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
		weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
    weekdaysLetter: [ 'D', 'L', 'M', 'M', 'J', 'V', 'S' ],
		selectMonths: true, // Permite seleccionar meses con un menú desplegable.
		selectYears: 100, // Permite seleccionar años con un menú desplegable.
		today: false,
		clear: 'Limpiar',
		close: 'Ok',
		labelMonthNext: 'Siguiente mes',
		labelMonthPrev: 'Mes anterior',
		labelMonthSelect: 'Selecciona un mes',
		labelYearSelect: 'Selecciona un año',
    min:new Date(1900,0,1), // Fecha mínima de nacimiento (formato del constructor: "año,mes[Rango de meses: 0-11 ],día").
    max: new Date(), // Fecha máxima de nacimiento.
    format: 'yyyy-mm-dd',
    firstDay: true
	});

  $('.datepicker').on('mousedown',function(e)
  {
    e.preventDefault(); // Arreglo falla datepicker materialize
  });
  // Mandamos una petición asíncrona mediante método POST al servidor, para obtener temas de los álbumes y ponerlos en el select
		$.post('obtenerTemas.php',function(data)
		{
				for (var i = 0; i < data.length ; i++)
				{
					 $('#temaCrearAlbum').append($('<option>',
			        {
			            value: data[i],
			            text : data[i]
			        }));
					 $('#temaCrearAlbum').material_select();
				}
		}, 'json');
  // Dependiendo a que botón se dé click ocultará o mostrar determinados elementos
  $("#mostrar_modificar").click(function()
  {
    $("#modificarperfil").show();
    $("#verHistorialBusqueda").hide();
    $("#perfil").hide();
  });
  $("#mostrar_informacion").click(function()
  {
    $("#perfil").show();
    $("#modificarperfil").hide();
    $("#verHistorialBusqueda").hide();
  });
  $("#mostrarPerfil").click(function()
  {
    $("#perfil").show();
    $("#modificarperfil").hide();
    $("#verHistorialBusqueda").hide();
  });
  $("#mostrar_historial").click(function()
  {
    $("#verHistorialBusqueda").show();
    $("#perfil").hide();
    $("#modificarperfil").hide();
  });
  $("#mostrarCrearAlbum").click(function()
  {
    $("#crearAlbum").show();
    $("#albumesUsuario").hide();
  });
  $("#mostrarAlbumesUsuario").click(function()
  {
    $("#albumesUsuario").show();
    $("#crearAlbum").hide();
  });
  $("#mostrarSubirFotos").click(function()
  {
    $("#formSubirFoto").show();
    $("#mostrarFotos").hide();
  });
  $("#botonMostrarFotos").click(function()
  {
    $("#mostrarFotos").show();
    $("#formSubirFoto").hide();
  });
  //Regresa al admin cuando está en el modo de visualización
  $("#regresarAdmin").click(function()
  {
    location.href="../administrador/index.php";
  });
  $("#formularioCrearAlbum").on('submit', function(e)
  {
    e.preventDefault();
    $.ajax(
    {
      type: 'POST',
      url: 'crearAlbum.php',
      data: new FormData(this), // Inicializa el objeto con la información de la forma.
      dataType : 'json', // Indicamos formato de respuesta
      contentType: false, // desactivamos esta opción, ya que la  codificación se específico en la forma.
      cache: false, // No almacena caché.
      processData: false, // No procesa nada con un determinado tipo de codificación, ya que contentType es false.
      success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
      {
        swal( // Se inicializa sweetalert2
        {
          title: "Bien hecho!",
          type: data.alerta,
          html: data.mensaje,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Ok!'
        }).then(function ()
        {
            location.reload();
            location.href = 'index.php#tabAlbumes';
        });
        $(document).click(function()
        {
            location.reload();
            location.href = 'index.php#tabAlbumes';
        });
        $(document).keyup(function(e)
        {
          if (e.which == 27)
          {
            location.reload();
            location.href = 'index.php#tabAlbumes';
          }
        });
      },
      error : function(xhr, status) // Si hubo error, despliega mensaje.
      {
        swal( // Se inicializa sweetalert2
        {
          title: "Ups...",
          type: "error",
          html: "Error del servidor, intente de nuevo",
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Ok!'
        });
      }
    });
  });
  //Cuando se quiere actualizar información del usuario se dispara el evento para modificar los datos.
  $("#form-update").on('submit', function(e)
  {
    e.preventDefault();
    let nombreFoto, flag = 0;
    nombreFoto = $("#rutaFotoPerfilRegistro").val();
    if(nombreFoto != '')
    {
      if (!(/\.(jpg|jpeg|png|gif|bmp|tiff|raw|JPG|PNG)$/i).test(nombreFoto)) // si el archivo no tiene estas extensiones
      {
        flag = 1;
      }

    }
    if(flag == 1) // Si el archivo no es una imagen despliega error.
    {
      console.log("Entró");
      swal( // Se inicializa sweetalert2
      {
        title: "Upss...",
        type: "error",
        html: "Error, formato de imagen inválido, intente con otra imagen",
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Ok!'
      });
    }
    else
    {
      $.ajax(
      {
        type: 'POST',
        url: 'actualizar.php',
        data: new FormData(this), // Inicializa el objeto con la información de la forma.
        dataType : 'json', // Indicamos formato de respuesta
        contentType: false, // desactivamos esta opción, ya que la  codificación se específico en la forma.
        cache: false, // No almacena caché.
        processData:false, // No procesa nada con un determinado tipo de codificación, ya que contentType es false.
        success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
        {
          if (data.alerta == "error") // título de acuerdo al tipo de alerta
              titulo = "Ups...";
          else
              titulo = "Bien hecho!";
          swal( // Se inicializa sweetalert2
          {
            title: titulo,
            type: data.alerta,
            html: data.mensaje,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok!'
          }).then(function ()
          {
            if (data.pagina == "index")
            {
              location.reload();
              location.href = 'index.php#tabPerfil';
            }

          });
          $(document).click(function()
          {
            if (data.pagina == "index" )
            {
              location.reload();
              location.href = 'index.php#tabPerfil';
            }
          });
          $(document).keyup(function(e)
          {
            if (e.which == 27)
            {
              if (data.pagina == "index" )
              {
                location.reload();
                location.href = 'index.php#tabPerfil';
              }
            }
          });
        },
        error : function(xhr, status) // Si hubo error, despliega mensaje.
        {
          swal( // Se inicializa sweetalert2
          {
            title: "Ups...",
            type: "error",
            html: "Error del servidor, intente de nuevo",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok!'
          });
        }
      });
    }
  });
  $("#formularioSubirFotos").on('submit', function(e)
  {
    e.preventDefault();
    let nombreFoto, flag = 0;
    nombreFoto = $("#rutaSubirFoto").val();
    if(nombreFoto != '')
    {
      if (!(/\.(jpg|jpeg|png|gif|bmp|tiff|raw|JPG|PNG)$/i).test(nombreFoto)) // si el archivo no tiene estas extensiones
      {
        flag = 1;
      }
    }
    if(flag == 1) // Si el archivo no es una imagen despliega error.
    {
      swal( // Se inicializa sweetalert2
      {
        title: "Upss...",
        type: "error",
        html: "Error, formato de imagen inválido, intente con otra imagen",
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Ok!'
      });
    }
    else
    {
      $.ajax(
      {
        type: 'POST',
        url: 'subirFoto.php',
        data: new FormData(this), // Inicializa el objeto con la información de la forma.
        dataType : 'json', // Indicamos formato de respuesta
        contentType: false, // desactivamos esta opción, ya que la  codificación se específico en la forma.
        cache: false, // No almacena caché.
        processData:false, // No procesa nada con un determinado tipo de codificación, ya que contentType es false.
        success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
        {
          if (data.alerta == "error") // título de acuerdo al tipo de alerta
              titulo = "Ups...";
          else
              titulo = "Bien hecho!";
          swal( // Se inicializa sweetalert2
          {
            title: titulo,
            type: data.alerta,
            html: data.mensaje,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok!'
          }).then(function ()
          {
              if(data.pagina == "albumes")
                location.reload();
          });
          $(document).click(function()
          {
              if(data.pagina == "albumes")
                location.reload();
          });
          $(document).keyup(function(e)
          {
            if (e.which == 27)
            {
              if(data.pagina == "albumes")
                location.reload();
            }
          });
        },
        error : function(xhr, status,error) // Si hubo error, despliega mensaje.
        {
          swal( // Se inicializa sweetalert2
          {
            title: "Ups...",
            type: "error",
            html: "Error del servidor, intente de nuevo",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok!'
          });
        }
      });
    }

  });
  $("#formularioModificarAlbum").on('submit', function(e)
  {
    e.preventDefault();
    $.ajax(
    {
      type: 'POST',
      url: 'cambiarAlbumes.php',
      data: new FormData(this), // Inicializa el objeto con la información de la forma.
      dataType : 'json', // Indicamos formato de respuesta
      contentType: false, // desactivamos esta opción, ya que la  codificación se específico en la forma.
      cache: false, // No almacena caché.
      processData: false, // No procesa nada con un determinado tipo de codificación, ya que contentType es false.
      success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
      {
        swal( // Se inicializa sweetalert2
        {
          title: "Bien hecho!",
          type: data.alerta,
          html: data.mensaje,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Ok!'
        }).then(function ()
        {
          location.href = "index.php#tabAlbumes";
        });
        $(document).click(function()
        {
          location.href = "index.php#tabAlbumes";
        });
        $(document).keyup(function(e)
        {
          if (e.which == 27)
          {
            location.href = "index.php#tabAlbumes";
          }
        });
      },
      error : function(xhr, status) // Si hubo error, despliega mensaje.
      {
        swal( // Se inicializa sweetalert2
        {
          title: "Ups...",
          type: "error",
          html: "Error del servidor, intente de nuevo",
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Ok!'
        });
      }
    });
  });
  //Cuando se sale del elemento de búsqueda, deja vacío el input y ejecuta animación de deslizar hacia arriba
  $("#inputBusqueda").change(function(){
    $("#inputBusqueda").val(" ");
    $("#search").slideUp(700);
  });
  var consulta = $("#searchTable").DataTable(); // Ejecuta el método DataTable() para realizar la búsqueda de los álbumes

  $("#inputBusqueda").keyup(function(){
    consulta.search($(this).val()).draw(); // dibuja un recuadro en blanco con el texto los álbumes según lo escrito en el input de búsqueda

    if ($("#inputBusqueda").val() == ""){ // Si no hay nada en el input, no muestra ningun álbum
      $("search").css({
        "height": "auto",
        "background": "none",
      })

      $("#search").hide(); //Oculta el input search

    } else {
      $("#search").fadeIn("fast"); // efectos de búsqueda
    }
  });
});

function eliminarFoto(idFoto)
{
	let param = "foto="+idFoto;
	$.ajax(
	{
		type: 'POST',
		url: 'eliminarFoto.php',
		data: param, // Inicializa el objeto con la información de la forma.
		dataType : 'json', // Indicamos formato de respuesta
		success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
		{
			if(data.alerta == "error")
			{
				titulo = "Ups..."
			}
			else
			{
				titulo = "Bien hecho!"
			}
			swal( // Se inicializa sweetalert2
			{

				title: titulo,
				type: data.alerta,
				html: data.mensaje,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			}).then(function ()
			{
				if(data.alerta == "success")
					location.reload();
			});
			$(document).click(function()
			{
				if(data.alerta == "success")
					location.reload();
			});
			$(document).keyup(function(e)
			{
				if (e.which == 27)
				{
					if(data.alerta == "success")
						location.reload();
				}
			});
		},
		error : function(xhr, status,error) // Si hubo error, despliega mensaje.
		{
			swal( // Se inicializa sweetalert2
			{
				title: "Ups...",
				type: "error",
				html: "Error del servidor, intente de nuevo"+error,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			});
		}
	});
}

function eliminarAlbum(idAlbum)
{
  let param = "album="+idAlbum;
  $.ajax(
	{
		type: 'POST',
		url: 'eliminarAlbum.php',
		data: param, // Inicializa el objeto con la información de la forma.
		dataType : 'json', // Indicamos formato de respuesta
		success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
		{
			if(data.alerta == "error")
			{
				titulo = "Ups..."
			}
			else
			{
				titulo = "Bien hecho!"
			}
			swal( // Se inicializa sweetalert2
			{

				title: titulo,
				type: data.alerta,
				html: data.mensaje,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			}).then(function ()
			{
				if(data.alerta == "success")
        {
          location.reload();
          location.href = 'index.php#tabAlbumes';
        }
			});
			$(document).click(function()
			{
				if(data.alerta == "success")
        {
          location.reload();
          location.href = 'index.php#tabAlbumes';
        }
			});
			$(document).keyup(function(e)
			{
				if (e.which == 27)
				{
					if(data.alerta == "success")
          {
            location.reload();
            location.href = 'index.php#tabAlbumes';
          }
				}
			});
		},
		error : function(xhr, status,error) // Si hubo error, despliega mensaje.
		{
			swal( // Se inicializa sweetalert2
			{
				title: "Ups...",
				type: "error",
				html: "Error del servidor, intente de nuevo"+error,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			});
		}
	});
}
// Cambiar el estado de la notificación de no leída a leída
function cambiarNotificacion(idNotificacionLeida)
{
	let param = "idNotificacionLeida="+idNotificacionLeida;
	$.ajax(
	{
		type: 'POST',
		url: 'notificacionLeida.php',
		data: param, // Inicializa el objeto con la información de la forma.
		dataType : 'json', // Indicamos formato de respuesta
		success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
		{
			if(data.alerta == "error")
			{
				titulo = "Ups..."
			}
			else
			{
				titulo = "Bien hecho!"
			}
			swal( // Se inicializa sweetalert2
			{

				title: titulo,
				type: data.alerta,
				html: data.mensaje,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			}).then(function ()
			{
				if(data.alerta == "success")
        {
          location.reload();
          location.href = 'index.php#tabNotificaciones';
        }
			});
			$(document).click(function()
			{
				if(data.alerta == "success")
        {
          location.reload();
          location.href = 'index.php#tabNotificaciones';
        }
			});
			$(document).keyup(function(e)
			{
				if (e.which == 27)
				{
					if(data.alerta == "success")
          {
            location.reload();
            location.href = 'index.php#tabNotificaciones';
          }

				}
			});
		},
		error : function(xhr, status,error) // Si hubo error, despliega mensaje.
		{
			swal( // Se inicializa sweetalert2
			{
				title: "Ups...",
				type: "error",
				html: "Error del servidor, intente de nuevo"+error,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			});
		}
	});
}
function eliminarNotificacion(idNotificacionLeida)
{
	let param = "idNotificacionLeida="+idNotificacionLeida;
	$.ajax(
	{
		type: 'POST',
		url: 'eliminarNotificacion.php',
		data: param, // Inicializa el objeto con la información de la forma.
		dataType : 'json', // Indicamos formato de respuesta
		success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
		{
			if(data.alerta == "error")
			{
				titulo = "Ups..."
			}
			else
			{
				titulo = "Bien hecho!"
			}
			swal( // Se inicializa sweetalert2
			{

				title: titulo,
				type: data.alerta,
				html: data.mensaje,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			}).then(function ()
			{
				if(data.alerta == "success")
        {
          location.reload();
          location.href = 'index.php#tabNotificaciones';
        }
			});
			$(document).click(function()
			{
				if(data.alerta == "success")
        {
          location.reload();
          location.href = 'index.php#tabNotificaciones';
        }
			});
			$(document).keyup(function(e)
			{
				if (e.which == 27)
				{
					if(data.alerta == "success")
          {
            location.reload();
            location.href = 'index.php#tabNotificaciones';
          }

				}
			});
		},
		error : function(xhr, status,error) // Si hubo error, despliega mensaje.
		{
			swal( // Se inicializa sweetalert2
			{
				title: "Ups...",
				type: "error",
				html: "Error del servidor, intente de nuevo"+error,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			});
		}
	});
}
//Cuando un usuario quiere compartir / suscribir a otro usuario se ejecuta está función que procede a registrarlo como suscripción si cumple con ciertas normas básicas.
function compartirAlbum(idAlbum,idUsuario)
{

  swal({
  imageUrl:"../images/email.ico",
  imageWidth: 150,
  imageHeight: 150,
  confirmButtonColor: '#3085d6',
  confirmButtonText: '¡Compartir!',
  title: 'Ingresa correo electrónico',
  html:"<input id='correoCompartir' class='swal2-input'>", //prompt con un input para ingresar el usuario
  preConfirm: function () {
    return new Promise(function (resolve) {
      resolve([
        $('#correoCompartir').val() // Obtiene la cadena del correo
      ])
    })
  }
}).then(function (result) {
  let correoUsuario = result.toString(); // Asigna variable con el nombre del correo, ya que el formato de respuesta es un json lo convierte a cadena
  let param = "idAlbum="+idAlbum+"&idUsuario="+idUsuario+"&correoUsuario="+correoUsuario;
  $.ajax(
	{
		type: 'POST',
		url: 'invitacionSuscripcion.php',
		data: param, // Inicializa el objeto con la información de la forma.
		dataType : 'json', // Indicamos formato de respuesta
		success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
		{
			if(data.alerta == "error")
			{
				titulo = "Ups..."
			}
			else
			{
				titulo = "Bien hecho!"
			}
			swal( // Se inicializa sweetalert2
			{
				title: titulo,
				type: data.alerta,
				html: data.mensaje,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			})
		},
		error : function(xhr, status,error) // Si hubo error, despliega mensaje.
		{
			swal( // Se inicializa sweetalert2
			{
				title: "Ups...",
				type: "error",
				html: "Error del servidor, intente de nuevo"+error,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			});
		}
	});
}).catch(swal.noop)
}
// Cuando un visitante quiera regresar a la página principal, lo redigirá a registro_usuarios.html para invitarlo a que se registre
function regresarLogin()
{
  location.href = '../registro_usuarios.html';
}
//Borra el historial de busqueda del usuario logeado.
function borrarHistorial(idUsuario)
{
  let param = "idUsuario="+idUsuario;
  $.ajax(
	{
		type: 'POST',
		url: 'eliminarHistorial.php',
		data: param, // Inicializa el objeto con la información de la forma.
		dataType : 'json', // Indicamos formato de respuesta
		success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
		{
			if(data.alerta == "error")
			{
				titulo = "Ups..."
			}
			else
			{
				titulo = "Bien hecho!"
			}
			swal( // Se inicializa sweetalert2
			{

				title: titulo,
				type: data.alerta,
				html: data.mensaje,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			}).then(function ()
			{
				if(data.alerta == "success")
        {
          location.reload();
          location.href = 'index.php#tabPerfil';
        }
			});
			$(document).click(function()
			{
				if(data.alerta == "success")
        {
          location.reload();
          location.href = 'index.php#tabPerfil';
        }
			});
			$(document).keyup(function(e)
			{
				if (e.which == 27)
				{
					if(data.alerta == "success")
          {
            location.reload();
            location.href = 'index.php#tabPerfil';
          }
				}
			});
		},
		error : function(xhr, status,error) // Si hubo error, despliega mensaje.
		{
			swal( // Se inicializa sweetalert2
			{
				title: "Ups...",
				type: "error",
				html: "Error del servidor, intente de nuevo"+error,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			});
		}
	});
}
// Procede a eliminar suscripción
function eliminarSuscripcion(idAlbum)
{
  let param = "album="+idAlbum;
  $.ajax(
	{
		type: 'POST',
		url: 'eliminarSuscripcion.php',
		data: param, // Inicializa el objeto con la información de la forma.
		dataType : 'json', // Indicamos formato de respuesta
		success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
		{
			if(data.alerta == "error")
			{
				titulo = "Ups..."
			}
			else
			{
				titulo = "Bien hecho!"
			}
			swal( // Se inicializa sweetalert2
			{

				title: titulo,
				type: data.alerta,
				html: data.mensaje,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			}).then(function ()
			{
				if(data.alerta == "success")
        {
          location.reload();
          location.href = 'index.php#tabAlbumes';
        }
			});
			$(document).click(function()
			{
				if(data.alerta == "success")
        {
          location.reload();
          location.href = 'index.php#tabAlbumes';
        }
			});
			$(document).keyup(function(e)
			{
				if (e.which == 27)
				{
					if(data.alerta == "success")
          {
            location.reload();
            location.href = 'index.php#tabAlbumes';
          }
				}
			});
		},
		error : function(xhr, status,error) // Si hubo error, despliega mensaje.
		{
			swal( // Se inicializa sweetalert2
			{
				title: "Ups...",
				type: "error",
				html: "Error del servidor, intente de nuevo"+error,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			});
		}
	});
}
// Cuando esté en algún álbum de su propiedad, habrá un botón que lo redirigirá para ver su álbum completo
function verAlbum(idAlbum)
{
  location.href = "verAlbumes.php?id="+idAlbum+"tipo=0"; // Tipo 0 indica que no se registrará en su historial de búsqueda
}
