<?php
  require_once "HTML/Template/ITX.php";
  session_start();
  // Redirige al usuario si no está logueado.
	$template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("principal.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums");
  //$template->addBlockfile("CONTENIDO", "INICIO", "login.html");
  //$template->touchBlock('INICIO');
  $template->show();
?>
