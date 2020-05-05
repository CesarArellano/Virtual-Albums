$(document).ready(function(e)
{
  $(".button-collapse").sideNav();
  $('.materialboxed').materialbox();
  $('ul.tabs').tabs();
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
    max: new Date(2005,11,31), // Fecha máxima de nacimiento.
    format: 'yyyy-mm-dd',
    firstDay: true // Empieza en día Lunes y no en Domingo.
	});
  $('.datepicker').on('mousedown',function(e)
  {
    e.preventDefault(); // Arreglo falla datepicker materialize
  });
  // $(".tabs-content").css('height','1000px'); // Ajusta los divs de los tabs del módulo administración
  $("#admin").hide();
  $("#registro").hide();
  $("#busqueda").hide();
  $("#visualizacion").hide();
  $("#analisis").hide();

  $("#select_inicio").click(function()
  {
    $("#inicio_contenido").show();
    $("#admin").hide();
    $("#registro").hide();
    $("#busqueda").hide();
    $("#visualizacion").hide();
    $("#analisis").hide();
  });
  $("#select_admin").click(function()
  {
    $("#inicio_contenido").hide();
    $("#admin").show();
    $("#registro").hide();
    $("#busqueda").hide();
    $("#visualizacion").hide();
    $("#analisis").hide();
  });
  $("#select_registro").click(function()
  {
    $("#inicio_contenido").hide();
    $("#admin").hide();
    $("#registro").show();
    $("#busqueda").hide();
    $("#visualizacion").hide();
    $("#analisis").hide();
  });

  $("#select_busqueda").click(function()
  {
    $("#inicio_contenido").hide();
    $("#admin").hide();
    $("#registro").hide();
    $("#busqueda").show();
    $("#visualizacion").hide();
    $("#analisis").hide();
  });
  $("#select_visualizacion").click(function()
  {
    $("#inicio_contenido").hide();
    $("#admin").hide();
    $("#registro").hide();
    $("#busqueda").hide();
    $("#visualizacion").show();
    $("#analisis").hide();
  });
  $("#select_analisis").click(function()
  {
    $("#inicio_contenido").hide();
    $("#admin").hide();
    $("#registro").hide();
    $("#busqueda").hide();
    $("#visualizacion").hide();
    $("#analisis").show();
  });
  $(Buscar_Datos());
  $(Buscar_Fotos());
  $(Buscar_Albumes());
  function Buscar_Datos(Consulta)
  {
      $.ajax(
      {
          url: 'busquedaresultados.php',
          type: 'POST',
          dataType: 'html',
          data: {Consulta: Consulta},
          success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
          {
            $("#datos").html(data);
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
  function Buscar_Fotos(Consulta)
  {
      $.ajax(
      {
          url: 'busquedafotos.php',
          type: 'POST',
          dataType: 'html',
          data: {Consulta: Consulta},
          success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
          {
            $("#datosfotos").html(data);
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
  function Buscar_Albumes(Consulta)
  {
      $.ajax(
      {
          url: 'busquedaalbumes.php',
          type: 'POST',
          dataType: 'html',
          data: {Consulta: Consulta},
          success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
          {
            $("#datosalbumes").html(data);
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

  $("#buscar").on('keyup', function(){
      var Valor = $(this).val();
      if (Valor != "")
          Buscar_Datos(Valor);
      else
          Buscar_Datos();
  });
  $("#buscarfotos").on('keyup', function(){
      var Valor = $(this).val();
      if (Valor != "")
          Buscar_Fotos(Valor);
      else
          Buscar_Fotos();
  });
  $("#buscaralbumes").on('keyup', function(){
      var Valor = $(this).val();
      if (Valor != "")
          Buscar_Albumes(Valor);
      else
          Buscar_Albumes();
  });

  $("#form-admins").on('submit', function(e)
  {
    e.preventDefault();
    let nombreFoto, flag = 0;
    nombreFoto = $("#rutaFotoPerfilRegistro").val();
    if(nombreFoto != '')
    {
      if (!(/\.(jpg|jpeg|png|gif|bmp|tiff|raw|JPG|PNG)$/i).test(nombreFoto)) // si el archivo no tiene estas extensiones
          flag = 1;
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
        url: '../registrar.php',
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
            }

          });
          $(document).click(function()
          {
            if (data.pagina == "index" )
            {
              location.reload();
            }
          });
          $(document).keyup(function(e)
          {
            if (e.which == 27)
            {
              if (data.pagina == "index" )
              {
                location.reload();
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
  $("#form-update").on('submit', function(e)
  {
    e.preventDefault();
    let nombreFoto, flag = 0;
    nombreFoto = $("#rutaFotoPerfilRegistro").val();
    if(nombreFoto != '')
    {
      if (!(/\.(jpg|jpeg|png|gif|bmp|tiff|raw|JPG|PNG)$/i).test(nombreFoto)) // si el archivo no tiene estas extensiones
          flag = 1;
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
              location.href = "index.php";
            }

          });
          $(document).click(function()
          {
            if (data.pagina == "index" )
            {
              location.href = "index.php";
            }
          });
          $(document).keyup(function(e)
          {
            if (e.which == 27)
            {
              if (data.pagina == "index" )
              {
                location.href = "index.php";
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

});
