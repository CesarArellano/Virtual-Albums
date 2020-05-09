<?php
  include "../config.php";
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();
	$template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("verAlbumes.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums | Ver Álbumes");
  $idAlbum = intval($_GET['id']);
  $notificar = intval($_GET['tipo']);
  $tablaFotosAlbum = "";

  if(isset($_SESSION['idUsuario']))
  {
    $idUsuario = intval($_SESSION['idUsuario']);
    $tipoUsuario = intval($_SESSION['tipoUsuario']);
    $consultaExisteAlbum = mysqli_query($conexion,"SELECT titulo FROM Albumes WHERE idAlbum = $idAlbum");
    $existe = mysqli_num_rows($consultaExisteAlbum);
    if($existe > 0)
    {
      $rowAlbum = mysqli_fetch_assoc($consultaExisteAlbum);
      if($notificar == 1)
      {
        $mensaje = "Buscó el álbum: ".$rowAlbum['titulo'];
        $historialBusqueda = mysqli_query($conexion,"INSERT INTO HistorialBusqueda (idUsuario,busqueda) VALUES($idUsuario,'$mensaje')");
      }
      $queryFotos = "SELECT idFoto FROM Fotos WHERE idAlbum = $idAlbum AND autorizada = 1 ORDER BY idFoto DESC";
      $consultaInsertarVisitas = mysqli_query($conexion,"INSERT INTO Visitas(fechaVisita,idAlbum) VALUES(CURDATE(),$idAlbum)");
      $consultaObtenerVisitas = mysqli_query($conexion,"SELECT COUNT(Visitas.idAlbum) as 'visitas' FROM Albumes LEFT JOIN Visitas USING (idAlbum) WHERE idAlbum = $idAlbum");
      $visitasPorAlbum = mysqli_fetch_assoc($consultaObtenerVisitas);
      $consultaPuntuacionFoto = mysqli_query($conexion,"SELECT ROUND(AVG(puntuacion),2) as 'puntuacionPromedio' FROM Fotos LEFT JOIN PuntuacionesComentarios USING (idFoto) WHERE idFoto IN ($queryFotos) GROUP BY idFoto ORDER BY idFoto DESC");
      $consultaFotos = mysqli_query($conexion,"SELECT * FROM Fotos WHERE idAlbum = $idAlbum AND autorizada = 1 ORDER BY idFoto DESC");
      $numerofilas = mysqli_num_rows($consultaFotos);
      if ($numerofilas > 0)
      {
        $i = 1;
        $tablaFotosAlbum = "<div class='row'>";
        while($rowFotos = mysqli_fetch_assoc($consultaFotos) AND $row2 = mysqli_fetch_assoc($consultaPuntuacionFoto))
        {
          $idFoto = intval($rowFotos['idFoto']);
          $consultaPuntuacion = mysqli_query($conexion,"SELECT puntuacion FROM PuntuacionesComentarios LEFT JOIN Usuarios USING(idUsuario) WHERE idUsuario = $idUsuario AND idFoto = $idFoto AND puntuacion IS NOT NULL");
          $numeroFilasPuntuacion = mysqli_num_rows($consultaPuntuacion);
          if($numeroFilasPuntuacion == 1)
          {
            $rowPuntuacion = mysqli_fetch_assoc($consultaPuntuacion);
          }
          if($row2['puntuacionPromedio'] == NULL)
          {
            $rating = 0;
          }
          else
          {
            $rating = $row2['puntuacionPromedio'];
          }
          $tablaFotosAlbum .= "<div class='col l6 m6 s12'><div class='card hoverable'><div class='card-image'><img class='materialboxed ajusteImagen' src='images/albumes/".$rowFotos['rutaFoto']."'></div>
          <div class='card-content'><p class='center-align' style='position:relative;top:-12px; font-size:15px'>Rating: ".$rating."</p>
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
            $consultaComentarios = mysqli_query($conexion,"SELECT nombreUsuario,apPaternoUsuario,apMaternoUsuario,comentario, fechaComentario, foto,tipoUsuario FROM PuntuacionesComentarios LEFT JOIN Usuarios USING(idUsuario) WHERE idFoto = $idFoto AND puntuacion IS NULL ORDER BY idFoto DESC");
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
                $tablaFotosAlbum .= "<p style='opacity: 0.8; font-size:15px;position:relative;top:-22px;'>".$row['fechaComentario']."</p><p class='comentarios' style='top:-26px; position:relative;'>".$row['comentario']."</p>";
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
  }
  else
  {
    $consultaObtenerVisitas = mysqli_query($conexion,"SELECT titulo FROM Albumes WHERE idAlbum = $idAlbum");
    $existe = mysqli_num_rows($consultaObtenerVisitas);
    if($existe > 0)
    {
      $rowAlbum = mysqli_fetch_assoc($consultaObtenerVisitas);
      $consultaObtenerVisitas = mysqli_query($conexion,"SELECT COUNT(Visitas.idAlbum) as 'visitas' FROM Albumes LEFT JOIN Visitas USING (idAlbum) WHERE idAlbum = $idAlbum");
      $visitasPorAlbum = mysqli_fetch_assoc($consultaObtenerVisitas);
      $consultaInsertarVisitas = mysqli_query($conexion,"INSERT INTO Visitas(fechaVisita,idAlbum) VALUES(CURDATE(),$idAlbum)");
      $consultaFotos = mysqli_query($conexion,"SELECT * FROM Fotos WHERE idAlbum = $idAlbum AND autorizada = 1 ORDER BY idFoto DESC");
      $numerofilas = mysqli_num_rows($consultaFotos);
      if ($numerofilas > 0)
      {
        $tablaFotosAlbum = "<div class='row'>";
        while($rowFotos = mysqli_fetch_assoc($consultaFotos))
        {
          $idFoto = intval($rowFotos['idFoto']);
          $tablaFotosAlbum .= "<div class='col l6 m6 s12'><div class='card hoverable'><div class='card-image'><img class='materialboxed ajusteImagen' src='images/albumes/".$rowFotos['rutaFoto']."'></div>
          <div class='card-content'> <h5 class='center-align'>Inicia sesión para ver comentarios</h5>";
          $tablaFotosAlbum .= "</div></div></div>";
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
  }
  $consultaInformacionAlbum = mysqli_query($conexion,"SELECT nombreUsuario,apPaternoUsuario, apMaternoUsuario, titulo FROM Albumes INNER JOIN Usuarios USING(idUsuario) WHERE idAlbum = $idAlbum");
  $rowInformacionAlbum = mysqli_fetch_assoc($consultaInformacionAlbum);
  $template->setVariable("TITULO_ALBUM", "<h2>Álbum: ".$rowInformacionAlbum['titulo']."</h2><h4>Visitas: ".$visitasPorAlbum['visitas']."</h4><h4>Autor: ".$rowInformacionAlbum['nombreUsuario']." ".$rowInformacionAlbum['apPaternoUsuario']." ".$rowInformacionAlbum['apMaternoUsuario']);
  $template->setVariable("FOTOS",$tablaFotosAlbum);
  $template->show();
  mysqli_free_result($consultaInformacionAlbum);

  Desconectar($conexion);
?>
