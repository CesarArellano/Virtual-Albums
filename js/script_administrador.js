$(document).ready(function(e)
{
  $(".button-collapse").sideNav();
  $('ul.tabs').tabs(
  {
    swipeable : true,
    responsiveThreshold : 1920
  });

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
  function Buscar_Datos(Consulta){
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
  $("#buscar").on('keyup', function(){
      var Valor = $(this).val();
      if (Valor != "")
          Buscar_Datos(Valor);
      else
          Buscar_Datos();
  });
  $(".tabs-content").css('height','1000px'); // Ajusta los divs de los tabs del módulo administración

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
});
