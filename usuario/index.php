<?php
  include "../config.php";
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();
	$template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("principal.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums | Usuario");
  $boton = "";
  if(isset($_SESSION['idUsuario']))
  {
    $idUsuario = intval($_SESSION['idUsuario']);
    $tipoUsuario = intval($_SESSION['tipoUsuario']);
    if($tipoUsuario == 1)
    {
      $boton = "<button class='btn waves-effect waves-light indigo darken-1 right' id='regresarAdmin'>Regresar admin<i class='material-icons left'>arrow_back</i></button>";
      $queryBusqueda = "SELECT idAlbum,titulo FROM Albumes";
      $queryFeed = "SELECT idFoto, idAlbum, titulo, nombreTema, rutaFoto,nombreUsuario,apPaternoUsuario,apMaternoUsuario,foto FROM Albumes INNER JOIN Temas USING(idTema) INNER JOIN Fotos USING(idAlbum) INNER JOIN Usuarios USING(idUsuario) WHERE idUsuario != $idUsuario AND autorizada = 1 ORDER BY idFoto DESC";
      $queryPuntuacion = "SELECT idFoto FROM Albumes INNER JOIN Temas USING(idTema) INNER JOIN Fotos USING(idAlbum) INNER JOIN Usuarios USING(idUsuario) WHERE idUsuario != $idUsuario AND autorizada = 1 ORDER BY idFoto DESC";
    }
    else
    {
      $queryBusqueda = "SELECT DISTINCT idAlbum,titulo FROM Albumes LEFT JOIN Usuarios USING(idUsuario) LEFT JOIN Suscripciones USING(idAlbum) WHERE tipoAlbum = 'Publico' OR (tipoAlbum = 'Privado' AND Suscripciones.idUsuario = $idUsuario)";
      $queryFeed = "SELECT idFoto, Albumes.idAlbum AS 'idAlbum', titulo, nombreTema, rutaFoto, nombreUsuario, apPaternoUsuario, apMaternoUsuario, foto FROM Albumes INNER JOIN Temas USING(idTema) LEFT JOIN Fotos USING(idAlbum) LEFT JOIN Usuarios USING(idUsuario) LEFT JOIN Suscripciones USING(idAlbum) WHERE ((tipoAlbum = 'Publico') OR (tipoAlbum = 'Privado' AND Suscripciones.idUsuario = $idUsuario)) AND (Albumes.idUsuario != $idUsuario AND autorizada = 1) GROUP BY idFoto ORDER BY idFoto DESC";
      $queryPuntuacion = "SELECT idFoto FROM Albumes INNER JOIN Temas USING(idTema) LEFT JOIN Fotos USING(idAlbum) LEFT JOIN Usuarios USING(idUsuario) LEFT JOIN Suscripciones USING(idAlbum) WHERE ((tipoAlbum = 'Publico') OR (tipoAlbum = 'Privado' AND Suscripciones.idUsuario = $idUsuario)) AND (Albumes.idUsuario != $idUsuario AND autorizada = 1) GROUP BY idFoto ORDER BY idFoto DESC";
    }
    //Sección búsqueda de álbumes
    $consultaTituloAlbumes = mysqli_query($conexion,$queryBusqueda);
    $numerofilas = mysqli_num_rows($consultaTituloAlbumes);
    $tituloAlbumes = "";
    if ($numerofilas > 0)
    {
      while ($row = mysqli_fetch_array($consultaTituloAlbumes))
      {
        $tituloAlbumes .= "<tr><td><a href='verAlbumes.php?id=".$row['idAlbum']."&tipo=1'>".$row['titulo']."</a></td></tr>";
      }
      mysqli_free_result($consultaTituloAlbumes);
    }
    else
    {
      $tituloAlbumes .= "<tr>No hay resultados</tr>";
    }
    $template->setVariable("TITULOS_ALBUMES", $tituloAlbumes);

    //Sección Inicio
    $consultaPuntuacionFoto = mysqli_query($conexion,"SELECT ROUND(AVG(puntuacion),2) as 'puntuacionPromedio' FROM Fotos LEFT JOIN PuntuacionesComentarios USING (idFoto) WHERE idFoto IN ($queryPuntuacion) GROUP BY idFoto ORDER BY idFoto DESC");
    $consultaFeed = mysqli_query($conexion,$queryFeed);
    $numerofilas = mysqli_num_rows($consultaFeed);
    if ($numerofilas > 0)
    {
      $i = 0;
      $contenidoUsuario = "<div class='row'>";

        while ($row = mysqli_fetch_assoc($consultaFeed) AND $row2 = mysqli_fetch_assoc($consultaPuntuacionFoto))
        {
          $idFoto = intval($row['idFoto']);
          $consultaPuntuacion = mysqli_query($conexion,"SELECT puntuacion FROM PuntuacionesComentarios LEFT JOIN Usuarios USING(idUsuario) WHERE idUsuario = $idUsuario AND idFoto = $idFoto AND puntuacion IS NOT NULL");
          $numeroFilasPuntuacion = mysqli_num_rows($consultaPuntuacion);
          if($numeroFilasPuntuacion == 1)
          {
            $rowPuntuacion = mysqli_fetch_assoc($consultaPuntuacion);
          }
          if($row['foto'] == NULL)
          {
            $fotoPerfil = "../images/avatar.png";
          }
          else
          {
            $fotoPerfil = "images/perfil/".$row['foto'];
          }
          if($row2['puntuacionPromedio'] == NULL)
          {
            $rating = 0;
          }
          else
          {
            $rating = $row2['puntuacionPromedio'];
          }
          $nombreUsuario =  $row['nombreUsuario']." ".$row['apPaternoUsuario']." ".$row['apMaternoUsuario'];
          $fotosAlbum = "images/albumes/".$row['rutaFoto'];
          $contenidoUsuario.= "<div class='col l6 m12 s12'>
          <div class='card hoverable'>
            <br>
            <img src='".$fotoPerfil."' height='50px' width='50px' class='left circle' style='position: relative; left: 10px; top:-10px;'>
            <p class='tituloNombreUsuario'>".$nombreUsuario."</p>
            <hr style='border: 0.5px solid gray;'>
            <div class='card-image' style='top:-7px;'>
              <img class='materialboxed ajusteImagen' src='".$fotosAlbum."'>
              <span class='card-title' style='background-color:black; opacity:0.8; font-size:18px'>Álbum: ".$row['titulo']."<br>Tema: ".$row['nombreTema']."</span>
              <a class='btn-floating halfway-fab waves-effect waves-light red' href='verAlbumes.php?id=".$row['idAlbum']."&tipo=0'><i class='material-icons'>remove_red_eye</i></a>
            </div>
            <div class='card-content'><p class='center-align' style='position:relative;top:-20px;'>Rating: ".$rating."</p>
              <form action='enviarComentario.php' method='POST'>
                <div class='star-rating center-align'>";
                if($numeroFilasPuntuacion == 1 AND $rowPuntuacion['puntuacion'] == 5.0)
                  $contenidoUsuario .= "<input id='star-".$i."' type='radio' name='rating' value='5' checked>";
                else
                  $contenidoUsuario .= "<input id='star-".$i."' type='radio' name='rating' value='5'>";

                $contenidoUsuario .= "<label for='star-".$i."' title='5 stars'>
                        <i class='active fa fa-star' aria-hidden='true'>★</i>
                    </label>";
                $i++;
                if($numeroFilasPuntuacion == 1 AND $rowPuntuacion['puntuacion'] == 4.0)
                  $contenidoUsuario .= "<input id='star-".$i."' type='radio' name='rating' value='4' checked>";
                else
                  $contenidoUsuario .= "<input id='star-".$i."' type='radio' name='rating' value='4'>";

                $contenidoUsuario .= "<label for='star-".$i."' title='4 stars'>
                        <i class='active fa fa-star' aria-hidden='true'>★</i>
                    </label>";
                $i++;

                if($numeroFilasPuntuacion == 1 AND $rowPuntuacion['puntuacion'] == 3.0)
                  $contenidoUsuario .= "<input id='star-".$i."' type='radio' name='rating' value='3' checked>";
                else
                  $contenidoUsuario .= "<input id='star-".$i."' type='radio' name='rating' value='3'>";

                $contenidoUsuario .= "<label for='star-".$i."' title='3 stars'>
                  <i class='active fa fa-star' aria-hidden='true'>★</i>
                  </label>";
                $i++;

                if($numeroFilasPuntuacion == 1 AND $rowPuntuacion['puntuacion'] == 2.0)
                  $contenidoUsuario .= "<input id='star-".$i."' type='radio' name='rating' value='2' checked>";
                else
                  $contenidoUsuario .= "<input id='star-".$i."' type='radio' name='rating' value='2'>";

                $contenidoUsuario.="<label for='star-".$i."' title='2 stars'>
                        <i class='active fa fa-star' aria-hidden='true'>★</i>
                    </label>";
                $i++;

                if($numeroFilasPuntuacion == 1 AND $rowPuntuacion['puntuacion'] == 1.0)
                  $contenidoUsuario .= "<input id='star-".$i."' type='radio' name='rating' value='1' checked>";
                else
                  $contenidoUsuario .= "<input id='star-".$i."' type='radio' name='rating' value='1'>";

                $contenidoUsuario.="<label for='star-".$i."' title='1 star'>
                        <i class='active fa fa-star' >★</i>
                    </label>
                  </div>";
                $i++;
                  $contenidoUsuario.="
                  <div class='row'>
                    <div class='input-field col s12'>
                      <textarea id='textarea".$i."' class='materialize-textarea' name='comentario' style='overflow:scroll;'></textarea>
                      <label for='textarea".$i."'>Haz un comentario</label>
                    </div>
                  </div>
                  <input type='hidden' name='idFoto' value='".$idFoto."'>
                  <input type='hidden' name='idAlbum' value='".$idAlbum."'>
                  <input type='hidden' name='tipo' value='1'>
                  <center><button class='btn waves-effect waves-light indigo darken-3' type='submit' style='top: -25px;'>Enviar<i class='material-icons right'>send</i></button></center>
                  </form>";
                $consultaComentarios = mysqli_query($conexion,"SELECT nombreUsuario,apPaternoUsuario,apMaternoUsuario,comentario,foto,tipoUsuario FROM PuntuacionesComentarios LEFT JOIN Usuarios USING(idUsuario) WHERE idFoto = $idFoto AND puntuacion IS NULL ORDER BY idFoto DESC");
                $numeroFilasComentarios = mysqli_num_rows($consultaComentarios);
                $contenidoUsuario .= "<h5>Comentarios</h5><div class='cajaComentarios'>";
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
                    $contenidoUsuario .= "<img class='ajusteImagenComentarios' src='".$fotoPerfil."'><p class='tituloNombreUsuario2'>".$row['nombreUsuario']." ".$row['apPaternoUsuario']." ".$row['apMaternoUsuario']."</p>";
                    $contenidoUsuario .= "<p class='comentarios' style='top:-15px;'>".$row['comentario']."</p>";
                  }
                }
                else
                {
                  $contenidoUsuario .= "<h5 class='center-align' style='position:relative; top:60px;'>No hay comentarios</h5>";
                }

                $contenidoUsuario .= "</div></div></div></div>";
      }
      $contenidoUsuario.= "</div>";
    }
    else
      $contenidoUsuario.= "<h4 class='center-align'>No hay contenido para ver.</h4>";
    //Sección Perfil
    $consultaPerfil = mysqli_query($conexion,"SELECT * FROM Usuarios  WHERE idUsuario = $idUsuario");
    $datosPerfil = mysqli_fetch_assoc($consultaPerfil);
    if($datosPerfil['foto'] == NULL)
    {
      $foto = "../images/avatar.png";
    }
    elseif ($datosPerfil['tipoUsuario'] == 1)
    {
      $foto = "../administrador/images/perfil/".$datosPerfil['foto'];
    }
    else
    {
      $foto = "images/perfil/".$datosPerfil['foto'];
    }
    //Sección Álbumes
    $consultaObtenerVisitas = mysqli_query($conexion,"SELECT COUNT(Visitas.idAlbum) as 'visitas' FROM Albumes LEFT JOIN Visitas USING (idAlbum) WHERE idUsuario = $idUsuario GROUP BY idAlbum ORDER BY idAlbum DESC");
    $consultaAlbumes = mysqli_query($conexion,"SELECT idAlbum, titulo, fechaAlbum, tipoAlbum, nombreTema, COUNT(idfoto) AS 'cuantasFotos' FROM Albumes LEFT JOIN Temas USING(idTema) LEFT JOIN Fotos USING(idAlbum) WHERE idUsuario = $idUsuario GROUP BY idAlbum ORDER BY idAlbum DESC");
    $numerofilas = mysqli_num_rows($consultaAlbumes);
    if ($numerofilas > 0)
    {
      $tablaAlbumesUsuario = "<table class='responsive-table highlight centered'>
        <thead>
          <th>Título</th>
          <th>Visitas</th>
          <th>Fecha álbum</th>
          <th>Privacidad del álbum</th>
          <th>Tema del álbum</th>
          <th>Fotos del álbum<th>
          <th></th>
        </thead>
        <tbody>";
        while ($row = mysqli_fetch_assoc($consultaAlbumes) AND $row2 = mysqli_fetch_assoc($consultaObtenerVisitas))
        {
          $tablaAlbumesUsuario.= "<tr>
          <td>".$row['titulo']."</td>
          <td>".$row2['visitas']."</td>
          <td>".$row['fechaAlbum']."</td>
          <td>".$row['tipoAlbum']."</td>
          <td>".$row['nombreTema']."</td>
          <td>".$row['cuantasFotos']."</td>
          <td><a href='verFotos.php?id=".$row['idAlbum']."'>Ver el álbum</a></td>
          <td><a href='modificarAlbumes.php?id=".$row['idAlbum']."'>Modificar información</a></td>
          <td><a href='#' onclick='eliminarAlbum(".$row['idAlbum'].")'>Eliminar</a></td>
          <td><a href='#' onclick='compartirAlbum(".$row['idAlbum'].",".$idUsuario.")'>Compartir</a></td>
          </tr>";
      }
      $tablaAlbumesUsuario.= "</tbody></table>";
    }
    else
    {
      $tablaAlbumesUsuario.= "<h4 class='center-align'>No ha creado álbumes</h4>";
    }
    //Suscripciones
    $consultaSuscripciones = mysqli_query($conexion,"SELECT nombreUsuario,apPaternoUsuario, apMaternoUsuario,idAlbum, titulo, fechaAlbum, tipoAlbum FROM Albumes a LEFT JOIN Usuarios USING(idUsuario) INNER JOIN Suscripciones s USING(idAlbum) WHERE s.idUsuario = $idUsuario AND a.idUsuario != $idUsuario GROUP BY idAlbum");
    $numerofilas = mysqli_num_rows($consultaSuscripciones);
    if ($numerofilas > 0)
    {
      $tablaSuscripcionesUsuario = "<table class='responsive-table highlight centered'>
        <thead>
          <th>Autor álbum</th>
          <th>Título álbum</th>
          <th>Privacidad del álbum</th>
          <th>Fecha álbum</th>
          <th></th>
          <th></th>
        </thead>
        <tbody>";
        while ($row = mysqli_fetch_assoc($consultaSuscripciones))
        {
          $tablaSuscripcionesUsuario.= "<tr>
          <td>".$row['nombreUsuario']." ".$row['apPaternoUsuario']." ".$row['apMaternoUsuario']."</td>
          <td>".$row['titulo']."</td>
          <td>".$row['tipoAlbum']."</td>
          <td>".$row['fechaAlbum']."</td>
          <td><a href='verAlbumes.php?id=".$row['idAlbum']."&tipo=0'>Ver el álbum</a></td>
          <td><a href='#' onclick='eliminarSuscripcion(".$row['idAlbum'].")'>Eliminar suscripción</a></td>
          </tr>";
      }
      $tablaSuscripcionesUsuario.= "</tbody></table>";
    }
    else
      $tablaSuscripcionesUsuario.= "<h4 class='center-align'>Aún no está suscrito a ningún álbum</h4>";
    //Sección Notificaciones
    $consultaNotificaciones = mysqli_query($conexion,"SELECT idNotificacionLeida, contenido,estado FROM Notificaciones INNER JOIN NotificacionesLeidas USING(idNotificacion) WHERE idUsuario = $idUsuario ORDER BY idNotificacion DESC");
    $numerofilas = mysqli_num_rows($consultaNotificaciones);
    if ($numerofilas > 0)
    {
      $tablaNotificacionesUsuario = "<table class='responsive-table highlight centered'>
        <thead>
          <th>Notificación</th>
          <th></th>
          <th><th>
          <th></th>
        </thead>
        <tbody>";
        while ($row = mysqli_fetch_assoc($consultaNotificaciones))
        {
          $tablaNotificacionesUsuario.= "<tr>
            <td>".$row['contenido']."</td>";
            if($row['estado'] == "Leída")
            {
              $tablaNotificacionesUsuario.="<td><h2 class='blue-text'></h2></td>
                <td><p>Leída</p></td>";
            }
            else
            {
              $tablaNotificacionesUsuario.="<td><h2 class='blue-text'>•</h2></td>
                <td><a href='#' onclick='cambiarNotificacion(".$row['idNotificacionLeida'].")'>Marcar como leída</a></td>";
            }
          $tablaNotificacionesUsuario.= "<td><a href='#' onclick='eliminarNotificacion(".$row['idNotificacionLeida'].")'>Eliminar notificación</a></td></tr>";

      }
      $tablaNotificacionesUsuario.= "</tbody></table>";
    }
    else
      $tablaNotificacionesUsuario.= "<h4 class='center-align'>No tiene notificaciones</h4>";

    $consultaHistorial = mysqli_query($conexion,"SELECT busqueda FROM HistorialBusqueda WHERE idUsuario = $idUsuario");
    $numerofilas = mysqli_num_rows($consultaHistorial);
    $historialBusqueda = "";
    if($numerofilas > 0)
    {
      while($datosHistorial = mysqli_fetch_assoc($consultaHistorial))
      {
        $historialBusqueda .= "<div class='row'>
          <div class='col s12'>
            <div class='card-panel blue darken-1s hoverable'>
              <h5 class='white-text center-align'>".$datosHistorial['busqueda']."</h5>
            </div>
          </div>
        </div>";
      }
      $historialBusqueda .="<center><button class='btn waves-effect waves-light red' onclick='borrarHistorial(".$idUsuario.")' style='margin: 10px'>Borrar Historial
        <i class='material-icons right'>close</i></button><button class='btn waves-effect waves-light green' id='mostrarPerfil' style='margin: 10px'>Perfil
          <i class='material-icons right'>arrow_back</i>
        </button></center>";
      mysqli_free_result($consultaHistorial);
    }
    else
    {
      $historialBusqueda .= "<h4 class='center-align'>No tiene historial de búsqueda</h4><center><button class='btn waves-effect waves-light green' id='mostrarPerfil' style='margin: 10px'>Perfil
        <i class='material-icons right'>arrow_back</i>
      </button></center>";
    }

    $template->addBlockfile("TABS_DE_SELECCION", "TABS", "tabs.html");
    $template->touchBlock('TABS');
    $template->addBlockfile("CONTENIDO_ALBUMES", "ALBUMES", "albumes.html");
    $template->setCurrentBlock('ALBUMES');
    $template->setVariable("IDUSUARIO",$idUsuario);
    $template->setVariable("CONTENIDO_ALBUM",$tablaAlbumesUsuario);
    $template->setVariable("CONTENIDO_SUSCRIPCIONES",$tablaSuscripcionesUsuario);
    $template->parseCurrentBlock("ALBUMES");
    $template->addBlockfile("CONTENIDO_NOTIFICACIONES", "NOTIFICACIONES", "notificaciones.html");
    $template->setCurrentBlock('NOTIFICACIONES');
    $template->setVariable("VER_NOTIFICACIONES", $tablaNotificacionesUsuario);
    $template->parseCurrentBlock("NOTIFICACIONES");
    $template->addBlockfile("CONTENIDO_PERFIL", "PERFIL", "perfil.html");
    $template->setCurrentBlock('PERFIL');
    $template->setVariable("FOTO", $foto);
    $template->setVariable("NOMBRECOMPLETO", $datosPerfil['nombreUsuario']." ".$datosPerfil['apPaternoUsuario']." ".$datosPerfil['apMaternoUsuario']);
    $template->setVariable("ESCOLARIDAD", $datosPerfil['escolaridad']);
    $template->setVariable("DIRECCION", $datosPerfil['direccion']);
    $template->setVariable("NACIMIENTO", $datosPerfil['nacimiento']);
    $template->setVariable("CORREO", $datosPerfil['correo']);
    $template->setVariable("IDUSUARIO",$idUsuario);
    $template->setVariable("IDUSUARIO_BORRAR",$idUsuario);
    $template->setVariable("HISTORIAL_BUSQUEDA",$historialBusqueda);
    $template->parseCurrentBlock("PERFIL");

    mysqli_free_result($consultaPerfil);
    mysqli_free_result($consultaAlbumes);
    mysqli_free_result($consultaNotificaciones);
    mysqli_free_result($consultaFeed);

  }
  else
  {
    $template->setVariable("TABS_DE_SELECCION", "");
    $template->setVariable("CONTENIDO_ALBUMES", "");
    $template->setVariable("CONTENIDO_NOTIFICACIONES", "");
    $template->setVariable("CONTENIDO_PERFIL", "");
    //Sección búsqueda de álbumes
    $queryBusqueda = "SELECT idAlbum,titulo FROM Albumes WHERE tipoAlbum = 'Público'";
    $consultaTituloAlbumes = mysqli_query($conexion,$queryBusqueda);
    $numerofilas = mysqli_num_rows($consultaTituloAlbumes);
    $tituloAlbumes = "";
    if ($numerofilas > 0)
    {
      while ($row = mysqli_fetch_array($consultaTituloAlbumes))
      {
        $tituloAlbumes .= "<tr><td><a href='verAlbumes.php?id=".$row['idAlbum']."&tipo=1'>".$row['titulo']."</a></td></tr>";
      }
      mysqli_free_result($consultaTituloAlbumes);
    }
    else
    {
      $tituloAlbumes .= "<tr>No hay resultados</tr>";
    }
    $template->setVariable("TITULOS_ALBUMES", $tituloAlbumes);

    //Sección Inicio
    $consultaFeed = mysqli_query($conexion,"SELECT idAlbum,titulo,nombreTema, rutaFoto,nombreUsuario,apPaternoUsuario,apMaternoUsuario,foto FROM Albumes INNER JOIN Temas USING(idTema) INNER JOIN Fotos USING(idAlbum) INNER JOIN Usuarios USING(idUsuario) WHERE autorizada = 1 AND tipoAlbum = 'Público' ORDER BY idFoto DESC");
    $numerofilas = mysqli_num_rows($consultaFeed);
    if ($numerofilas > 0)
    {
      $contenidoUsuario = "<div class='row'>";
        while ($row = mysqli_fetch_assoc($consultaFeed))
        {
          if($row['foto'] == NULL)
          {
            $fotoPerfil = "../images/avatar.png";
          }
          elseif ($row['tipoUsuario'] == 1)
          {
            $fotoPerfil = "../administrador/images/perfil/".$row['foto'];
          }
          else
          {
            $fotoPerfil = "images/perfil/".$row['foto'];
          }
          $nombreUsuario =  $row['nombreUsuario']." ".$row['apPaternoUsuario']." ".$row['apMaternoUsuario'];
          $fotosAlbum = "images/albumes/".$row['rutaFoto'];
          $contenidoUsuario.= "<div class='col l6 m12 s12'>
          <div class='card hoverable'>
          <br>
          <img src='".$fotoPerfil."' height='50px' width='50px' class='left circle' style='position: relative; left: 10px; top:-10px;'>
          <p class='title' style='font-size: 15px; position: relative; left: 20px; top:-10px;'>".$nombreUsuario."</p>
          <hr>
            <div class='card-image' style='top:-7px;'>
              <img class='materialboxed ajusteImagen' src='".$fotosAlbum."'>
              <span class='card-title' style='background-color:black; opacity:0.8; font-size:18px'>Álbum: ".$row['titulo']."<br>Tema: ".$row['nombreTema']."</span>
              <a class='btn-floating halfway-fab waves-effect waves-light red' href='verAlbumes.php?id=".$row['idAlbum']."'><i class='material-icons'>remove_red_eye</i></a>
            </div>
            <div class='card-content'>
              <h5 class='center-align'>Inicia sesión para ver comentarios</h5>
            </div>
          </div>
          </div>";
      }
      $contenidoUsuario.= "</div><center><button class='btn waves-effect waves-light indigo darken-1 right-align' style='margin: 10px' onclick='regresarLogin()'>Salir
        <i class='material-icons right'>arrow_back</i>
        </button></center>";
    }
    else
      $contenidoUsuario.= "<h4 class='center-align'>No hay contenido para ver.</h4>";
  }

  $template->addBlockfile("CONTENIDO_INICIO", "INICIO", "inicio.html");
  $template->setCurrentBlock('INICIO');
  $template->setVariable("BOTON",$boton);
  $template->setVariable("CONTENIDO_FEED",$contenidoUsuario);
  $template->parseCurrentBlock('INICIO');
  $template->show();
  Desconectar($conexion);
?>
