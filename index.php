<?php
  session_start(); // Función es para activar la sesión.
  require_once "HTML/Template/ITX.php";
  // Redirige al usuario si está logueado.

  if (isset($_SESSION['tipoUsuario']) AND $_SESSION['tipoUsuario'] == 1)
    header('location: administrador/index.php');
  if (isset($_SESSION['tipoUsuario']) AND $_SESSION['tipoUsuario'] == 2)
    header('location: usuario/index.php');

  $template = new HTML_Template_ITX('./templates');
	$template->loadTemplatefile("principal.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums | Login");
  $template->addBlockfile("CONTENIDO", "INICIO", "login.html");
  $template->touchBlock('INICIO');
  $template->show();
?>
