<?php
  include "../config.php";
  require_once "HTML/Template/ITX.php";
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  $conexion = Conectar();
	$template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("verAlbumes.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums | Ver Álbumes");
  if(isset($_SESSION['idUsuario']))
  {
    $idUsuario = intval($_SESSION['idUsuario']);
    $tablaFotosAlbum = "";
    $idAlbum = intval($_GET['id']);
    $notificar = intval($_GET['tipo']);
    $consultaObtenerVisitas = mysqli_query($conexion,"SELECT visitas,titulo FROM Albumes WHERE idAlbum = $idAlbum");
    $existe = mysqli_num_rows($consultaObtenerVisitas);
    if($existe > 0)
    {
      $rowAlbum = mysqli_fetch_assoc($consultaObtenerVisitas);
      if($notificar == 1)
      {
        $mensaje = "Buscó el álbum: ".$rowAlbum['titulo'];
        $historialBusqueda = mysqli_query($conexion,"INSERT INTO HistorialBusqueda (idUsuario,busqueda) VALUES($idUsuario,'$mensaje')");
      }
      $visitasAlbum = intval($rowAlbum['visitas']);
      $visitasAlbum++;
      $consultaActualizarVisitas = mysqli_query($conexion,"UPDATE Albumes SET visitas = $visitasAlbum WHERE idAlbum = $idAlbum");
      $consultaFotos = mysqli_query($conexion,"SELECT * FROM Fotos WHERE idAlbum = $idAlbum");
      $numerofilas = mysqli_num_rows($consultaFotos);
      if ($numerofilas > 0)
      {
        $i = 1;
        $tablaFotosAlbum = "<div class='row'>";
        while($rowFotos = mysqli_fetch_assoc($consultaFotos))
        {
          $tablaFotosAlbum .= "<div class='col l6 m6 s12'><div class='card hoverable'><div class='card-image'><img class='materialboxed ajusteImagen' src='images/albumes/".$rowFotos['rutaFoto']."'></div>
          <div class='card-content'>
          <form action='enviarComentario.php' method='POST'>
            <div class='star-rating center-align'>
              <input id='star-".$i."' type='radio' name='rating' value='5'>
              <label for='star-".$i."' title='5 stars'>
                  <i class='active fa fa-star' aria-hidden='true'>★</i>
              </label>";
              $i++;
              $tablaFotosAlbum.="
              <input id='star-".$i."' type='radio' name='rating' value='4'>
              <label for='star-".$i."' title='4 stars'>
                  <i class='active fa fa-star' aria-hidden='true'>★</i>
              </label>";
              $i++;
              $tablaFotosAlbum.="
              <input id='star-".$i."' type='radio' name='rating' value='3'>
              <label for='star-".$i."' title='3 stars'>
                  <i class='active fa fa-star' aria-hidden='true'>★</i>
              </label>
              ";
              $i++;
              $tablaFotosAlbum.="
              <input id='star-".$i."' type='radio' name='rating' value='2'>
              <label for='star-".$i."' title='2 stars'>
                  <i class='active fa fa-star' aria-hidden='true'>★</i>
              </label>";
              $i++;
              $tablaFotosAlbum.="
              <input id='star-".$i."' type='radio' name='rating' value='1'>
              <label for='star-".$i."' title='1 star'>
                  <i class='active fa fa-star' >★</i>
              </label>
            </div>";
            $i++;
            $tablaFotosAlbum.="
            <div class='row'>
              <div class='input-field col s12'>
                <textarea id='textarea".$i."' class='materialize-textarea' name='comentario' style='overflow:scroll;' required></textarea>
                <label for='textarea".$i."'>Haz un comentario</label>
              </div>
            </div>
            <input type='hidden' name='idFoto' value='".$rowFotos['idFoto']."'>
            <center><button class='btn waves-effect waves-light indigo darken-3' type='submit' style='top: -10px'>Enviar comentario<i class='material-icons right'>send</i></button></center>
            </form>
          </div></div></div>";
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
