$(document).ready(function(e)
{
  $(".button-collapse").sideNav();
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
  $("#form-users").on('submit', function(e)
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
        url: 'registrar.php',
        data: new FormData(this), // Inicializa el objeto con la información de la forma.
        dataType : 'json', // Indicamos formato de respuesta
        contentType: false, // desactivamos esta opción, ya que la  codificación se específico en la forma.
        cache: false, // No almacena caché.
        processData:false, // No procesa nada con un determinado tipo de codificación, ya que contentType es false.
        success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
        {
          switch (data.pagina) //Evaluamos a qué página se redirigirá.
          {
            case 'index':
              pagina = 'inicio.php';
              break;
            case 'registro':
              pagina = 0;
              break;
          }

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
            if(pagina != 0)
              location.href = pagina;
          });
          $(document).click(function()
          {
            if(pagina != 0)
              location.href = pagina;
          });
          $(document).keyup(function(e)
          {
            if (e.which == 27)
            {
              if(pagina != 0)
                location.href = pagina;
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
  $("#form-login").on('submit', function(e)
  {
    let pagina, titulo; // Declaramos variables
    e.preventDefault();
    $.ajax(
    {
      type: 'POST',
      url: 'validar_login.php',
      data: new FormData(this), // Inicializa el objeto con la información de la forma.
      dataType : 'json', // Indicamos formato de respuesta
      cache: false,
      processData: false,
      contentType: false,
      success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
      {
        switch (data.tipoUsuario) //Evaluamos qué tipo de usuario es para saber a donde redirigirlo
        {
          case 'administrador':
            pagina = 'administrador/inicio.php';
            break;
          case 'usuario':
            pagina = 'usuario/inicio.php';
            break;
          case 'index':
            pagina = 0;
            break;
        }

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
          if(pagina != 0)
          {
            location.href = pagina;
          }
        });
        $(document).click(function()
        {
          if(pagina != 0)
          {
            location.href = pagina;
          }

        });
        $(document).keyup(function(e)
        {
          if (e.which == 27)
          {
            if(pagina != 0)
            {
              location.href = pagina;
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
          html: "Error en el servidor",
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Ok!'
        });
      }
    });
  });
});
