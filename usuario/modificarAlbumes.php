<?php
  include "../config.php";
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();
	$template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("modificarAlbumes.html", true, true); // carga template
  $template->setVariable("TITULO", "Virtual Albums | Modificar Álbumes"); // asigna título
  if(isset($_SESSION['idUsuario'])) // Si está logeado deja modificar la info del álbum
  {
    $idAlbum = intval($_GET['id']); // Obtiene idAlbum
    $consultaInformacionAlbum = mysqli_query($conexion,"SELECT titulo FROM Albumes WHERE idAlbum = $idAlbum"); //Obtiene título del álbum
    $rowInformacionAlbum = mysqli_fetch_assoc($consultaInformacionAlbum);
  }
  else // Si no está logeado no me permite acceder
    header('location: ../index.php');

  $template->setVariable("TITULO_ALBUM", $rowInformacionAlbum['titulo']); // Inserta en el contenedor el nombre del álbum
  $template->setVariable("IDALBUM",$idAlbum); // Pone ID del álbum en la forma.
  $template->show(); // Muestra en pantalla.
  Desconectar($conexion);
?>
