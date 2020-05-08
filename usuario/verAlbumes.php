<?php
  include "../config.php";
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();
	$template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("verAlbumes.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums | Ver Álbumes");
  if(isset($_SESSION['idUsuario']))
  {
    $idUsuario = intval($_SESSION['idUsuario']);
    $tipoUsuario = intval($_SESSION['tipoUsuario']);
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
      $consultaFotos = mysqli_query($conexion,"SELECT * FROM Fotos WHERE idAlbum = $idAlbum ORDER BY idFoto DESC");
      $numerofilas = mysqli_num_rows($consultaFotos);
      if ($numerofilas > 0)
      {
        $i = 1;
        $tablaFotosAlbum = "<div class='row'>";
        while($rowFotos = mysqli_fetch_assoc($consultaFotos))
        {
          $idFoto = intval($rowFotos['idFoto']);
          $consultaPuntuacion = mysqli_query($conexion,"SELECT puntuacion FROM PuntuacionesComentarios LEFT JOIN Usuarios USING(idUsuario) WHERE idUsuario = $idUsuario AND idFoto = $idFoto AND puntuacion IS NOT NULL");
          $numeroFilasPuntuacion = mysqli_num_rows($consultaPuntuacion);
          if($numeroFilasPuntuacion == 1)
          {
            $rowPuntuacion = mysqli_fetch_assoc($consultaPuntuacion);
          }
          $tablaFotosAlbum .= "<div class='col l6 m6 s12'><div class='card hoverable'><div class='card-image'><img class='materialboxed ajusteImagen' src='images/albumes/".$rowFotos['rutaFoto']."'></div>
          <div class='card-content'>
          <form action='enviarComentario.php' method='POST'>
            <div class='star-rating center-align'>";
          if($numeroFilasPuntuacion == 1 AND $rowPuntuacion['puntuacion'] == 5.0)
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='5' checked>";
          else
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='5'>";

          $tablaFotosAlbum .= "<label for='star-".$i."' title='5 stars'>
                  <i class='active fa fa-star' aria-hidden='true'>★</i>
              </label>";
          $i++;
          if($numeroFilasPuntuacion == 1 AND $rowPuntuacion['puntuacion'] == 4.0)
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='4' checked>";
          else
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='4'>";

          $tablaFotosAlbum .= "<label for='star-".$i."' title='4 stars'>
                  <i class='active fa fa-star' aria-hidden='true'>★</i>
              </label>";
          $i++;

          if($numeroFilasPuntuacion == 1 AND $rowPuntuacion['puntuacion'] == 3.0)
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='3' checked>";
          else
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='3'>";

          $tablaFotosAlbum .= "<label for='star-".$i."' title='3 stars'>
            <i class='active fa fa-star' aria-hidden='true'>★</i>
            </label>";
          $i++;

          if($numeroFilasPuntuacion == 1 AND $rowPuntuacion['puntuacion'] == 2.0)
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='2' checked>";
          else
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='2'>";

          $tablaFotosAlbum.="<label for='star-".$i."' title='2 stars'>
                  <i class='active fa fa-star' aria-hidden='true'>★</i>
              </label>";
          $i++;

          if($numeroFilasPuntuacion == 1 AND $rowPuntuacion['puntuacion'] == 1.0)
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='1' checked>";
          else
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='1'>";

          $tablaFotosAlbum.="<label for='star-".$i."' title='1 star'>
                  <i class='active fa fa-star' >★</i>
              </label>
            </div>";
          $i++;
            $tablaFotosAlbum.="
            <div class='row'>
              <div class='input-field col s12'>
                <textarea id='textarea".$i."' class='materialize-textarea' name='comentario' style='overflow:scroll;'></textarea>
                <label for='textarea".$i."'>Haz un comentario</label>
              </div>
            </div>
            <input type='hidden' name='idFoto' value='".$idFoto."'>
            <input type='hidden' name='idAlbum' value='".$idAlbum."'>
            <input type='hidden' name='tipo' value='2'>
            <center><button class='btn waves-effect waves-light indigo darken-3' type='submit' style='top: -25px;'>Enviar<i class='material-icons right'>send</i></button></center>
            </form>";
          $consultaComentarios = mysqli_query($conexion,"SELECT nombreUsuario,apPaternoUsuario,apMaternoUsuario,comentario,foto,tipoUsuario FROM PuntuacionesComentarios LEFT JOIN Usuarios USING(idUsuario) WHERE idFoto = $idFoto AND puntuacion IS NULL");
          $numeroFilasComentarios = mysqli_num_rows($consultaComentarios);
          $tablaFotosAlbum .= "<h5>Comentarios</h5><div class='cajaComentarios'>";
          if($numeroFilasComentarios > 0)
          {
            while($row = mysqli_fetch_assoc($consultaComentarios))
            {
              if($row['foto'] == NULL)
              {
                $fotoPerfil = "../images/avatar.png";
              }
              else
              {
                if($row['tipoUsuario'] == 1)
                  $fotoPerfil = "../administrador/images/perfil/".$row['foto'];
                else
                  $fotoPerfil = "images/perfil/".$row['foto'];
              }
              $tablaFotosAlbum .= "<img class='ajusteImagenComentarios' src='".$fotoPerfil."'><p class='tituloNombreUsuario2'>".$row['nombreUsuario']." ".$row['apPaternoUsuario']." ".$row['apMaternoUsuario']."</p>";
              $tablaFotosAlbum .= "<p class='comentarios'>".$row['comentario']."</p>";
            }
          }
          else
          {
            $tablaFotosAlbum .= "<h5 class='center-align' style='position:relative; top:60px;'>No hay comentarios</h5>";
          }

          $tablaFotosAlbum .= "</div></div></div></div>";
        }
        $tablaFotosAlbum .= "</div>";
        mysqli_free_result($consultaObtenerVisitas);
        mysqli_free_result($consultaFotos);
      }
      else
      {
        $tablaFotosAlbum.= "<br><br><h4 class='center-align'>Aún no ha subido ninguna foto al álbum.</h4>";
      }
    }
    else
    {
        $tablaFotosAlbum .= "<br><h4 class='center-align'>No existe el álbum</h4>";
    }
    $consultaInformacionAlbum = mysqli_query($conexion,"SELECT nombreUsuario,apPaternoUsuario, apMaternoUsuario, titulo FROM Albumes INNER JOIN Usuarios USING(idUsuario) WHERE idAlbum = $idAlbum");
    $rowInformacionAlbum = mysqli_fetch_assoc($consultaInformacionAlbum);
    $template->setVariable("TITULO_ALBUM", "<h2>Álbum: ".$rowInformacionAlbum['titulo']."</h2><h4>Autor: ".$rowInformacionAlbum['nombreUsuario']." ".$rowInformacionAlbum['apPaternoUsuario']." ".$rowInformacionAlbum['apMaternoUsuario'] );
    $template->setVariable("FOTOS",$tablaFotosAlbum);
    $template->show();
    mysqli_free_result($consultaInformacionAlbum);
  }
  else
    header('location: ../index.php');

  Desconectar($conexion);
?>
