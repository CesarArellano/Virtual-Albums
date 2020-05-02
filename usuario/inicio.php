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
          </tr>";
      }
      $tablaAlbumesUsuario.= "</tbody></table>";
    }
    else
      $tablaAlbumesUsuario.= "<p>Aún no ha creado ningún álbum.</p>";

    $template->addBlockfile("TABS_DE_SELECCION", "TABS", "tabs.html");
    $template->touchBlock('TABS');
    //$template->setCurrentBlock('TABS');
    //$template->parseCurrentBlock("TABS");
    $template->addBlockfile("CONTENIDO_ALBUMES", "ALBUMES", "albumes.html");
    $template->setCurrentBlock('ALBUMES');
    $template->setVariable("IDUSUARIO",$idUsuario);
    $template->setVariable("CONTENIDO_ALBUM",$tablaAlbumesUsuario);
    $template->parseCurrentBlock("ALBUMES");
    $template->addBlockfile("CONTENIDO_NOTIFICACIONES", "NOTIFICACIONES", "notificaciones.html");
    $template->touchBlock('NOTIFICACIONES');
    //$template->setCurrentBlock('NOTIFICACIONES');
    //$template->parseCurrentBlock("NOTIFICACIONES");
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

  }
  else
  {
    $template->setVariable("TABS_DE_SELECCION", "");
    $template->setVariable("CONTENIDO_ALBUMES", "");
    $template->setVariable("CONTENIDO_NOTIFICACIONES", "");
    $template->setVariable("CONTENIDO_PERFIL", "");
  }

  $template->addBlockfile("CONTENIDO_INICIO", "INICIO", "inicio.html");
  $template->touchBlock('INICIO');
  $template->show();
  Desconectar($conexion);
?>
