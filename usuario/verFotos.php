<?php
  include "../config.php";
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();
	$template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("verFotos.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums | Ver Fotos");
  if(isset($_SESSION['idUsuario']))
  {
    $tablaFotosUsuario = "";
    $idAlbum = intval($_GET['id']);
    $consultaFotos = mysqli_query($conexion,"SELECT * FROM Fotos WHERE idAlbum = $idAlbum");
    $numerofilas = mysqli_num_rows($consultaFotos);
    if ($numerofilas > 0)
    {
      $tablaFotosUsuario = "<div class='row'>";
      while($rowFotos = mysqli_fetch_assoc($consultaFotos))
      {
        $tablaFotosUsuario .= "<div class='col l3 s6'><div class='card hoverable'><div class='card-image'><img class='materialboxed crop' src='images/albumes/".$rowFotos['rutaFoto']."'><span class='card-title'>Foto</span><div class='card-action'><center><button class='btn waves-effect waves-light red' onclick='eliminarFoto(".$rowFotos['idFoto'].")'>Eliminar foto<i class='material-icons right'>clear</i></button></center>
        </div></div></div></div></td>";
      }

      $tablaFotosUsuario .= "</div>";
    }
    else
      $tablaFotosUsuario.= "<p>Aún no ha subido ninguna foto al álbum.</p>";

    $consultaInformacionAlbum = mysqli_query($conexion,"SELECT titulo FROM Albumes WHERE idAlbum = $idAlbum");
    $rowInformacionAlbum = mysqli_fetch_assoc($consultaInformacionAlbum);
    $template->setVariable("TITULO_ALBUM", $rowInformacionAlbum['titulo']);
    $template->setVariable("FOTOS",$tablaFotosUsuario);
    $template->setVariable("IDALBUM",$idAlbum);
    $template->show();
    mysqli_free_result($consultaFotos);
    mysqli_free_result($consultaInformacionAlbum);
  }
  else
  {
    header('location: ../index.php');
  }
  Desconectar($conexion);
?>
