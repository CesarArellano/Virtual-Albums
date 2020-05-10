<?php
  include_once("../config.php");
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();
  if (!isset($_SESSION['tipoUsuario']))
		header('location: ../index.php');
  //Obtiene valores de respuesta del autorizar.php para mostrar notificacionón
  $mensaje = htmlentities($_GET['mensaje']);
  $p = htmlentities($_GET['p']);
  $alerta = htmlentities($_GET['a']);
  //Se verifica a que página se irá dependiendo del parámetro p
  switch ($p)
    {
      case 'index':
        $pagina = 'index.php';
        break;
    }

    if ($alerta == "error")
        $titulo = "Oppss..";
    else
        $titulo = "Bien hecho!";

  $template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("alerta.html", true, true);
  $template->setVariable("TITULO", $titulo);
  $template->setVariable("MENSAJE",$mensaje);
  $template->setVariable("ALERTA",$alerta);
  $template->setVariable("PAGINA",$pagina);
  $template->show();

  Desconectar($conexion);
?>
