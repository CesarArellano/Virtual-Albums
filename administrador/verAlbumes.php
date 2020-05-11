<?php
  include "../config.php";
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();
	$template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("verAlbumes.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums | Ver Álbumes");
  $idAlbum = intval($_GET['id']);
  $tablaFotosAlbum = "";

  if(isset($_SESSION['idUsuario'])) // Si está logeado el usuario desplegará información
  {
    $consultaExisteAlbum = mysqli_query($conexion,"SELECT titulo FROM Albumes WHERE idAlbum = $idAlbum");
    $existe = mysqli_num_rows($consultaExisteAlbum);
    if($existe > 0)
    {
      $queryFotos = "SELECT idFoto FROM Fotos WHERE idAlbum = $idAlbum AND autorizada = 1 ORDER BY idFoto DESC"; //Obtiene todas las fotos que han sido autorizadas y las ordena por ID
      $consultaObtenerVisitas = mysqli_query($conexion,"SELECT COUNT(Visitas.idAlbum) as 'visitas' FROM Albumes LEFT JOIN Visitas USING (idAlbum) WHERE idAlbum = $idAlbum"); //Obtiene las visitas de determinadoálbum
      $visitasPorAlbum = mysqli_fetch_assoc($consultaObtenerVisitas);
      $consultaPuntuacionFoto = mysqli_query($conexion,"SELECT ROUND(AVG(puntuacion),2) as 'puntuacionPromedio' FROM Fotos LEFT JOIN PuntuacionesComentarios USING (idFoto) WHERE idFoto IN ($queryFotos) GROUP BY idFoto ORDER BY idFoto DESC"); //Calcula la puntuación promedio de determinada foto
      $consultaFotos = mysqli_query($conexion,"SELECT * FROM Fotos WHERE idAlbum = $idAlbum AND autorizada = 1 ORDER BY idFoto DESC"); // Obtiene información de cada foto que esté autorizada
      $numerofilas = mysqli_num_rows($consultaFotos);
      if ($numerofilas > 0)
      {
        $i = 1; // Generará inputs únicos para poder ver correctamente la puntuación de la foto
        $tablaFotosAlbum = "<div class='row'>";
        while($rowFotos = mysqli_fetch_assoc($consultaFotos) AND $row2 = mysqli_fetch_assoc($consultaPuntuacionFoto))
        {
          $idFoto = intval($rowFotos['idFoto']);
          if($row2['puntuacionPromedio'] == NULL)
            $rating = 0; // si la foto no tiene rating, se desplegará un 0
          else
          {
            $rating = $row2['puntuacionPromedio'];
            $estrellas = intval($rating); // Esta variable ayuda a desplegar la puntuación en estrellas que tiene la foto
          }
          $tablaFotosAlbum .= "<div class='col l6 m6 s12'><div class='card hoverable'><div class='card-image'><img class='materialboxed ajusteImagen' src='../usuario/images/albumes/".$rowFotos['rutaFoto']."'></div>
          <div class='card-content'><p class='center-align' style='position:relative;top:-12px; font-size:15px'>Rating: ".$rating."</p>
            <form><div class='star-rating center-align'>";
          if($estrellas == 5)
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='5' onclick='deshabilitar(this.form)'checked>";
          else
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='5' onclick='deshabilitar(this.form)'>";
          $tablaFotosAlbum .= "<label for='star-".$i."' title='5 stars'>
                  <i class='active fa fa-star' aria-hidden='true'>★</i>
              </label>";
          $i++;
          if($estrellas == 4)
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='4' onclick='deshabilitar(this.form)' checked>";
          else
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='4'>";
          $tablaFotosAlbum .= "<label for='star-".$i."' title='4 stars'>
                  <i class='active fa fa-star' aria-hidden='true'>★</i>
              </label>";
          $i++;
          if($estrellas == 3)
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='3' onclick='deshabilitar(this.form)' checked>";

          else
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='3' onclick='deshabilitar(this.form)'>";
          $tablaFotosAlbum .= "<label for='star-".$i."' title='3 stars'>
            <i class='active fa fa-star' aria-hidden='true'>★</i>
            </label>";
          $i++;

          if($estrellas == 2)
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='2' onclick='deshabilitar(this.form)' checked>";
          else
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='2' onclick='deshabilitar(this.form)'>";

          $tablaFotosAlbum.="<label for='star-".$i."' title='2 stars'>
                  <i class='active fa fa-star' aria-hidden='true'>★</i>
              </label>";
          $i++;

          if($estrellas == 1)
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='1' onclick='deshabilitar(this.form)' checked>";
          else
            $tablaFotosAlbum .= "<input id='star-".$i."' type='radio' name='rating' value='1' onclick='deshabilitar(this.form)'>";

          $tablaFotosAlbum.="<label for='star-".$i."' title='1 star'>
                  <i class='active fa fa-star' >★</i>
              </label>
            </div></form>";
          $i++;
          // Obtenemos información suficiente para rellenar la caja de comentarios
          $consultaComentarios = mysqli_query($conexion,"SELECT nombreUsuario,apPaternoUsuario,apMaternoUsuario,comentario,foto,tipoUsuario FROM PuntuacionesComentarios LEFT JOIN Usuarios USING(idUsuario) WHERE idFoto = $idFoto AND puntuacion IS NULL");
          $numeroFilasComentarios = mysqli_num_rows($consultaComentarios);
          $tablaFotosAlbum .= "<h5>Comentarios</h5><div class='cajaComentarios'>"; // caja de comentarios, se muestran los comentarios, con el usuario, su foto y fecha del comentario
          if($numeroFilasComentarios > 0)
          {
            while($row = mysqli_fetch_assoc($consultaComentarios))
            {
              if($row['foto'] == NULL) // Si no tiene foto de perfil le ponemos una por default
                $fotoPerfil = "../images/avatar.png";
              else
              {
                if($row['tipoUsuario'] == 1) // Si es admin, se busca en un directorio
                  $fotoPerfil = "images/perfil/".$row['foto'];
                else // Si es usuario se busca en otro directorio
                  $fotoPerfil = "../usuario/images/perfil/".$row['foto'];
              }
              $tablaFotosAlbum .= "<img class='ajusteImagenComentarios' src='".$fotoPerfil."'><p class='tituloNombreUsuario2'>".$row['nombreUsuario']." ".$row['apPaternoUsuario']." ".$row['apMaternoUsuario']."</p>";
              $tablaFotosAlbum .= "<p class='comentarios'>".$row['comentario']."</p>";
            }
          }
          else
            $tablaFotosAlbum .= "<h5 class='center-align' style='position:relative; top:60px;'>No hay comentarios</h5>";

          $tablaFotosAlbum .= "</div></div></div></div>";
        }
        $tablaFotosAlbum .= "</div>";
        mysqli_free_result($consultaObtenerVisitas);
        mysqli_free_result($consultaFotos);
      }
      else // Si álbum todavía no tiene fotos despliega el siguiente mensaje
        $tablaFotosAlbum.= "<br><br><h4 class='center-align'>Aún no ha subido ninguna foto al álbum.</h4>";
    }
    else // mensaje si no existe el álbum
        $tablaFotosAlbum .= "<br><h4 class='center-align'>No existe el álbum</h4>";
  }
  else // Si no está logeado se procederá a regresarlo a la página de inicio
    header('location: ../index.php');
  //Obtiene el título del álbum,visitas y el propietario del mismo.
  $consultaInformacionAlbum = mysqli_query($conexion,"SELECT nombreUsuario,apPaternoUsuario, apMaternoUsuario, titulo FROM Albumes INNER JOIN Usuarios USING(idUsuario) WHERE idAlbum = $idAlbum");
  $rowInformacionAlbum = mysqli_fetch_assoc($consultaInformacionAlbum);
  $template->setVariable("TITULO_ALBUM", "<h2>Álbum: ".$rowInformacionAlbum['titulo']."</h2><h4>Visitas: ".$visitasPorAlbum['visitas']."</h4><h4>Autor: ".$rowInformacionAlbum['nombreUsuario']." ".$rowInformacionAlbum['apPaternoUsuario']." ".$rowInformacionAlbum['apMaternoUsuario']);
  $template->setVariable("FOTOS",$tablaFotosAlbum);
  $template->show();
  mysqli_free_result($consultaInformacionAlbum);

  Desconectar($conexion);
?>
