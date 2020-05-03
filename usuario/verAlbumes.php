<?php
  include "../config.php";
  require_once "HTML/Template/ITX.php";
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  $conexion = Conectar();
	$template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("verAlbumes.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums | Ver ÁLbumes");
  if(isset($_SESSION['idUsuario']))
  {
    $tablaFotosAlbum = "";
    $idAlbum = intval($_GET['id']);
    $consultaObtenerVisitas = mysqli_query($conexion,"SELECT visitas FROM Albumes WHERE idAlbum = $idAlbum");
    $existe = mysqli_num_rows($consultaObtenerVisitas);
    if($existe > 0)
    {
      $rowVisitaAlbum = mysqli_fetch_assoc($consultaObtenerVisitas);
      $visitasAlbum = intval($rowVisitaAlbum['visitas']);
      $visitasAlbum++;
      $consultaActualizarVisitas = mysqli_query($conexion,"UPDATE Albumes SET visitas = $visitasAlbum WHERE idAlbum = $idAlbum");
      $consultaFotos = mysqli_query($conexion,"SELECT * FROM Fotos WHERE idAlbum = $idAlbum");
      $numerofilas = mysqli_num_rows($consultaFotos);
      if ($numerofilas > 0)
      {
        $tablaFotosAlbum = "<div class='row'>";
        while($rowFotos = mysqli_fetch_assoc($consultaFotos))
        {
          $tablaFotosAlbum .= "<div class='col l3 m6 s12'><div class='card hoverable'><div class='card-image'><img class='materialboxed crop' src='images/albumes/".$rowFotos['rutaFoto']."'><span class='card-title'>Foto</span><div class='card-action'><center><button class='btn waves-effect waves-light indigo darken-3' onclick='enviarComentario(".$rowFotos['idFoto'].")'>Enviar comentario<i class='material-icons right'>send</i></button></center>
          </div></div></div></div></td>";
        }
        $tablaFotosAlbum .= "</div>";
        mysqli_free_result($consultaObtenerVisitas);
        mysqli_free_result($consultaFotos);
      }
      else
      {
        $tablaFotosAlbum.= "<br><h4 class='center-align'>Aún no ha subido ninguna foto al álbum.</h4>";
      }
    }
    else
    {
        $tablaFotosAlbum .= "<br><h4 class='center-align'>No existe el álbum</h4>";
    }
    $consultaInformacionAlbum = mysqli_query($conexion,"SELECT titulo FROM Albumes WHERE idAlbum = $idAlbum");
    $rowInformacionAlbum = mysqli_fetch_assoc($consultaInformacionAlbum);
    $template->setVariable("TITULO_ALBUM", $rowInformacionAlbum['titulo']);
    $template->setVariable("FOTOS",$tablaFotosAlbum);
    $template->show();
    mysqli_free_result($consultaInformacionAlbum);
  }
  else
    header('location: ../inicio.php');

  Desconectar($conexion);
?>
