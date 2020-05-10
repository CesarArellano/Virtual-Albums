<?php
  include_once("../config.php");
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();
  if (!isset($_SESSION['tipoUsuario']))
		header('location: ../index.php');
  // Obtiene los parámetros por método GET.
  $mensaje = htmlentities($_GET['mensaje']);
  $alerta = htmlentities($_GET['a']);
  $pagina = htmlentities($_GET['p']);

  if($pagina == 'index') // Si se encuentra en el index.
    $pagina = "index.php";
  else // Si se encuentra en verAlbumes.php
  {
    $idAlbum = htmlentities($_GET['al']); // Obtener idAlbum para redigirlo.
    $pagina = "verAlbumes.php?id=".$idAlbum."&tipo=0";
  }

  if ($alerta == "error") // Tipo de alerta
    $titulo = "Oppss..";
  else
    $titulo = "Bien hecho!";

  $template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("alerta.html", true, true);
  $template->setVariable("TITULO", $titulo); // título del mensaje
  $template->setVariable("MENSAJE",$mensaje); // cuerpo del mensaje
  $template->setVariable("ALERTA",$alerta); // tipo alerta
  $template->setVariable("PAGINA",$pagina); // página a redigirlo
  $template->show(); // muestra el template

  Desconectar($conexion);
?>
