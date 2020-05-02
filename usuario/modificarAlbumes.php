<?php
  include "../config.php";
  require_once "HTML/Template/ITX.php";
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  $conexion = Conectar();
	$template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("modificarAlbumes.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums | Modificar Álbumes");
  if(isset($_SESSION['idUsuario']))
  {
    $idAlbum = intval($_GET['id']);
    $consultaInformacionAlbum = mysqli_query($conexion,"SELECT titulo FROM Albumes WHERE idAlbum = $idAlbum");
    $rowInformacionAlbum = mysqli_fetch_assoc($consultaInformacionAlbum);
  }
  else
    header('location: ../inicio.php');

  $template->setVariable("TITULO_ALBUM", $rowInformacionAlbum['titulo']);
  $template->setVariable("IDALBUM",$idAlbum);
  $template->show();
  Desconectar($conexion);
?>
