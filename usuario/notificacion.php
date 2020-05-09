<?php
  include_once("../config.php");
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();
  if (!isset($_SESSION['tipoUsuario']))
		header('location: ../index.php');
  $mensaje = htmlentities($_GET['mensaje']);
  $alerta = htmlentities($_GET['a']);
  $pagina = htmlentities($_GET['p']);

  if($pagina == 'index')
    $pagina = "index.php";
  else
  {
    $idAlbum = htmlentities($_GET['al']);
    $pagina = "verAlbumes.php?id=".$idAlbum."&tipo=0";
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
