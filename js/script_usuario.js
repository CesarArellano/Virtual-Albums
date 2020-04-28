$(document).ready(function(e)
{
  $("#buscar").change(function()
  {
    $("#buscar").val('');
  });
  $(document).on('keyup', '#buscar', function(){
      console.log("Buscando...");
  });
  $('ul.tabs').tabs();
});
