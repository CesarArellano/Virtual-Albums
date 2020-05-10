<?php
  include "../config.php";
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();
	$template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("verFotos.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums | Ver Fotos");
  if(isset($_SESSION['idUsuario'])) // Si logeado le muesra el contenido
  {
    $tablaFotosUsuario = "";
    $idAlbum = intval($_GET['id']); // Obtiene por método GET idAlbum
    $consultaFotos = mysqli_query($conexion,"SELECT * FROM Fotos WHERE idAlbum = $idAlbum"); // Obtiene info de todas las fotos del álbum
    $numerofilas = mysqli_num_rows($consultaFotos);
    if ($numerofilas > 0) // si hay fotos en el álbum las muestra en pantalla.
    {
      $tablaFotosUsuario = "<div class='row'>";
      while($rowFotos = mysqli_fetch_assoc($consultaFotos))
      {
        //Se aplican elementos de materialize para ver las fotos en cards.
        $tablaFotosUsuario .= "<div class='col l3 s6'><div class='card hoverable'><div class='card-image'><img class='materialboxed ajusteImagen2' src='images/albumes/".$rowFotos['rutaFoto']."'><span class='card-title'>Foto</span><div class='card-action'><center>
        <button class='btn waves-effect waves-light red' onclick='eliminarFoto(".$rowFotos['idFoto'].")' style='margin: 10px'>Eliminar foto<i class='material-icons right'>clear</i></button>
        <button class='btn waves-effect waves-light indigo darken-3' onclick='verAlbum(".$idAlbum.")' style='margin: 10px'>Ver más<i class='material-icons right'>apps</i></button>
        </center>
        </div></div></div></div></td>";
      }

      $tablaFotosUsuario .= "</div>";
    }
    else // Si no hay foto, muestra mensaje.
      $tablaFotosUsuario.= "<p>Aún no ha subido ninguna foto al álbum.</p>";

    // Obtiene el título del álbum
    $consultaInformacionAlbum = mysqli_query($conexion,"SELECT titulo FROM Albumes WHERE idAlbum = $idAlbum");
    $rowInformacionAlbum = mysqli_fetch_assoc($consultaInformacionAlbum);
    $template->setVariable("TITULO_ALBUM", $rowInformacionAlbum['titulo']);
    $template->setVariable("FOTOS",$tablaFotosUsuario);
    $template->setVariable("IDALBUM",$idAlbum);
    $template->show();
    mysqli_free_result($consultaFotos);
    mysqli_free_result($consultaInformacionAlbum);
  }
  else // Si no está logeado lo manda al login
  {
    header('location: ../index.php');
  }
  Desconectar($conexion);
?>
