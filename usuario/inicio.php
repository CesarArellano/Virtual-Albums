<?php
  include "../config.php";
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();
	$template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("principal.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums | Usuario");
  if(isset($_SESSION['idUsuario']))
  {
    $idUsuario = intval($_SESSION['idUsuario']);
    //Sección Inicio
    $consultaFeed = mysqli_query($conexion,"SELECT idAlbum,titulo,nombreTema, rutaFoto,nombreUsuario,apPaternoUsuario,apMaternoUsuario,foto,visitas FROM Albumes INNER JOIN Temas USING(idTema) INNER JOIN Fotos USING(idAlbum) INNER JOIN Usuarios USING(idUsuario) WHERE autorizada = 1 AND tipoAlbum = 'Público' ORDER BY idFoto DESC");
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
          else
          {
            $fotoPerfil = "images/perfil/".$row['foto'];
          }
          $nombreUsuario =  $row['nombreUsuario']." ".$row['apPaternoUsuario']." ".$row['apMaternoUsuario'];
          $fotosAlbum = "images/albumes/".$row['rutaFoto'];
          $contenidoUsuario.= "<div class='col l6 m6 s12'>
          <div class='card hoverable'>
            <br>
            <img src='".$fotoPerfil."' height='50px' width='50px' class='left circle' style='position: relative; left: 10px; top:-10px;'>
            <p class='tituloNombreUsuario'>".$nombreUsuario."</p>
            <hr>
            <div class='card-image'>
              <img class='materialboxed ajusteImagen' src='".$fotosAlbum."'>
              <span class='card-title'>Álbum: ".$row['titulo']."</span>
              <a class='btn-floating halfway-fab waves-effect waves-light red' href='verAlbumes.php?id=".$row['idAlbum']."'><i class='material-icons'>remove_red_eye</i></a>
            </div>
            <div class='card-content'>
              <p>Tema: ".$row['nombreTema']."</p>
              <p>Visitas:".$row['visitas']."</p>
            </div>
          </div>
          </div>";
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
    else
    {
      $foto = "images/perfil/".$datosPerfil['foto'];
    }
    //Sección Álbumes
    $consultaAlbumes = mysqli_query($conexion,"SELECT idAlbum, titulo, visitas, fechaAlbum, tipoAlbum, nombreTema FROM Albumes LEFT JOIN Temas USING(idTema) WHERE idUsuario = $idUsuario");
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
          <th><th>
          <th><th>
        </thead>
        <tbody>";
        while ($row = mysqli_fetch_assoc($consultaAlbumes))
        {
          $tablaAlbumesUsuario.= "<tr>
            <td>".$row['titulo']."</td>
            <td>".$row['visitas']."</td>
            <td>".$row['fechaAlbum']."</td>
            <td>".$row['tipoAlbum']."</td>
            <td>".$row['nombreTema']."</td>
            <td><a href='verFotos.php?id=".$row['idAlbum']."'>Ver el álbum</a></td>
            <td><a href='modificarAlbumes.php?id=".$row['idAlbum']."'>Modificar información</a></td>
            <td><center><button class='btn waves-effect waves-light red' onclick='eliminarAlbum(".$row['idAlbum'].")'>Eliminar álbum<i class='material-icons right'>clear</i></button></center></td>
          </tr>";
      }
      $tablaAlbumesUsuario.= "</tbody></table>";
    }
    else
      $tablaAlbumesUsuario.= "<p>Aún no ha creado ningún álbum.</p>";
    //Sección Notificaciones
    $consultaNotificaciones = mysqli_query($conexion,"SELECT idNotificacion, contenido,estado FROM Notificaciones INNER JOIN NotificacionesLeidas USING(idNotificacion) WHERE idUsuario = $idUsuario");
    $numerofilas = mysqli_num_rows($consultaNotificaciones);
    if ($numerofilas > 0)
    {
      $tablaNotificacionesUsuario = "<table class='responsive-table highlight centered'>
        <thead>
          <th>Notificación</th>
          <th></th>
          <th><th>
        </thead>
        <tbody>";
        while ($row = mysqli_fetch_assoc($consultaNotificaciones))
        {
          $tablaNotificacionesUsuario.= "<tr>
            <td>".$row['contenido']."</td>
            <td>"."<h2 class='blue-text'>•</h2>"."</td>
            <td><a href='notificacionleida.php?id=".$row['idNotificacion']."'>Marcar como leída</a></td>
          </tr>";
      }
      $tablaNotificacionesUsuario.= "</tbody></table>";
    }
    else
      $tablaNotificacionesUsuario.= "<p>No tiene notificaciones.</p>";

    $template->addBlockfile("TABS_DE_SELECCION", "TABS", "tabs.html");
    $template->touchBlock('TABS');
    $template->addBlockfile("CONTENIDO_ALBUMES", "ALBUMES", "albumes.html");
    $template->setCurrentBlock('ALBUMES');
    $template->setVariable("IDUSUARIO",$idUsuario);
    $template->setVariable("CONTENIDO_ALBUM",$tablaAlbumesUsuario);
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

    $template->parseCurrentBlock("PERFIL");

    mysqli_free_result($consultaPerfil);
    mysqli_free_result($consultaAlbumes);
    mysqli_free_result($consultaNotificaciones);
    //mysqli_free_result($consultaFeed);

  }
  else
  {
    $template->setVariable("TABS_DE_SELECCION", "");
    $template->setVariable("CONTENIDO_ALBUMES", "");
    $template->setVariable("CONTENIDO_NOTIFICACIONES", "");
    $template->setVariable("CONTENIDO_PERFIL", "");
    //Sección Inicio
    $consultaFeed = mysqli_query($conexion,"SELECT idAlbum,titulo,nombreTema, rutaFoto,nombreUsuario,apPaternoUsuario,apMaternoUsuario,foto,visitas FROM Albumes INNER JOIN Temas USING(idTema) INNER JOIN Fotos USING(idAlbum) INNER JOIN Usuarios USING(idUsuario) WHERE autorizada = 1 AND tipoAlbum = 'Público' ORDER BY idFoto DESC");
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
          else
          {
            $fotoPerfil = "images/perfil/".$row['foto'];
          }
          $nombreUsuario =  $row['nombreUsuario']." ".$row['apPaternoUsuario']." ".$row['apMaternoUsuario'];
          $fotosAlbum = "images/albumes/".$row['rutaFoto'];
          $contenidoUsuario.= "<div class='col l6 m6 s12'>
          <div class='card hoverable'>
          <br>
          <img src='".$fotoPerfil."' height='50px' width='50px' class='left circle' style='position: relative; left: 10px; top:-10px;'>
          <p class='title' style='font-size: 15px; position: relative; left: 20px; top:-10px;'>".$nombreUsuario."</p>
          <hr>

            <div class='card-image'>
              <img class='materialboxed ajusteImagen' src='".$fotosAlbum."' style='padding: 10px 18px;'>
              <span class='card-title'>Álbum: ".$row['titulo']."</span>
              <a class='btn-floating halfway-fab waves-effect waves-light red' href='verAlbumes.php?id=".$row['idAlbum']."'><i class='material-icons'>remove_red_eye</i></a>
            </div>
            <div class='card-content'>
              <p>Tema: ".$row['nombreTema']."</p>
              <p>Visitas: ".$row['visitas']."</p>
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
  $template->setVariable("CONTENIDO_FEED",$contenidoUsuario);
  $template->parseCurrentBlock('INICIO');
  $template->show();
  Desconectar($conexion);
?>
