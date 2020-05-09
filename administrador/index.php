<?php
  include_once("../config.php");
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();

  if (!isset($_SESSION['tipoUsuario']))
		header('location: ../index.php');

  $idUsuario = $_SESSION['idUsuario'];

  $query = mysqli_query($conexion,"SELECT * FROM Usuarios WHERE idUsuario = $idUsuario");
  $rowPerfil = mysqli_fetch_assoc($query);
  if($rowPerfil['foto'] == NULL)
    $rutaImagen = "../images/avatar.png";
  else
    $rutaImagen = "images/perfil/".$rowPerfil['foto'];

  $contenidoAnalisis = "<div class ='container'>"; //Preparamos un contenedor para el módulo de análisis de información

  //INICIO DE ÁLBUMES MÁS VISTOS
  $consultaAlbumesMasVistos = mysqli_query($conexion, "SELECT idAlbum, titulo, fechaAlbum, tipoAlbum, count(idAlbum) as 'visitas' FROM Albumes LEFT JOIN Visitas USING (idAlbum) GROUP BY idAlbum ORDER BY count(idAlbum) DESC"); //Query que busca los álbumes más visitados
  $numeroFilasAlbumesMasVistos = mysqli_num_rows($consultaAlbumesMasVistos);
  //Creamos la tabla y le ponemos el encabezado
  $contenidoAnalisis.=
  "
  <table class='responsive-table highlight centered'>
  <h4>Álbumes más vistos</h4>
    <thead>
      <th>Título</th>
      <th>Fecha del álbum</th>
      <th>Privacidad del álbum</th>
      <th>Visitas</th>
    </thead>
    <tbody>";

  while ($row = mysqli_fetch_assoc($consultaAlbumesMasVistos)) //Rellenamos la tabla con los datos que devolvió el query
  {
      $contenidoAnalisis.= "<tr>
      <td>".$row['titulo']."</td>
      <td>".$row['fechaAlbum']."</td>
      <td>".$row['tipoAlbum']."</td>
      <td>".$row['visitas']."</td>
      <td><a href='verAlbumes.php?id=".$row['idAlbum']."'>Ver el álbum</a></td>
      </tr>";
  }

  $contenidoAnalisis.= "</tbody></table>"; //Cerramos la tabla

  if($numeroFilasAlbumesMasVistos == 0) //Mensaje si no hay coincidencias
    $contenidoAnalisis.= "<p>No se encontraron álbumes</p>";

  mysqli_free_result($consultaAlbumesMasVistos);
  //FIN DE ÁLBUMES MÁS VISTOS

  //INICIO DE ÁLBUMES CON MÁS FOTOS
  $consultaAlbumesMasFotos = mysqli_query($conexion, "SELECT idAlbum, titulo, fechaAlbum, tipoAlbum, COUNT(idAlbum) AS 'Cantidad de fotos' FROM Albumes LEFT JOIN Fotos USING (idAlbum) GROUP BY idAlbum ORDER BY COUNT(idAlbum) DESC"); //Query que busca los álbumes con más fotos
  $numeroFilasAlbumesMasFotos = mysqli_num_rows($consultaAlbumesMasFotos);
  //Creamos la tabla y le ponemos el encabezado
  $contenidoAnalisis.=
  "
  <table class='responsive-table highlight centered'>
  <h4>Álbumes con más fotografías</h4>
    <thead>
      <th>Título</th>
      <th>Fecha del álbum</th>
      <th>Privacidad del álbum</th>
      <th>Cantidad de fotografías</th>
    </thead>
    <tbody>";

  while ($row = mysqli_fetch_assoc($consultaAlbumesMasFotos)) //Rellenamos la tabla con los datos que devolvió el query
  {
      $contenidoAnalisis.= "<tr>
      <td>".$row['titulo']."</td>
      <td>".$row['fechaAlbum']."</td>
      <td>".$row['tipoAlbum']."</td>
      <td>".$row['Cantidad de fotos']."</td>
      <td><a href='verAlbumes.php?id=".$row['idAlbum']."'>Ver el álbum</a></td>
      </tr>";
  }

  $contenidoAnalisis.= "</tbody></table>"; //Cerramos la tabla

  if($numeroFilasAlbumesMasFotos == 0) //Mensaje si no hay coincidencias
    $contenidoAnalisis.= "<p>No se encontraron álbumes</p>";

  mysqli_free_result($consultaAlbumesMasFotos);
  //FIN DE ÁLBUMES CON MÁS FOTOS

  //INICIO DE FOTOS CON MÁS COMENTARIOS
  $consultaFotosMasComentarios = mysqli_query($conexion, "SELECT nombreUsuario, apPaternoUsuario, apMaternoUsuario, rutaFoto, titulo, fechaFoto, COUNT(comentario) as 'Cuantos' FROM Usuarios LEFT JOIN Albumes USING (idUsuario) LEFT JOIN Fotos USING (idAlbum) LEFT JOIN PuntuacionesComentarios USING (idFoto) WHERE comentario IS NOT NULL GROUP BY (idFoto) ORDER BY COUNT(comentario) DESC"); //Query que búsca las fotos con más comentarios
  $numeroFilasFotosMasComentarios = mysqli_num_rows($consultaFotosMasComentarios);
  //Creamos la tabla y le ponemos el encabezado
  $contenidoAnalisis.=
  "
  <table class='responsive-table highlight centered'>
  <h4>Fotos con más comentarios</h4>
    <thead>
      <th>Autor</th>
      <th>Vista previa</th>
      <th>Álbum</th>
      <th>Fecha de la foto</th>
      <th>Cantidad de comentarios</th>
    </thead>
    <tbody>";

  while ($row = mysqli_fetch_assoc($consultaFotosMasComentarios)) //Rellenamos la tabla con los datos que devolvió el query
  {
      $contenidoAnalisis.= "<tr>
      <td>".$row['nombreUsuario']." ".$row['apPaternoUsuario']." ".$row['apMaternoUsuario']."</td>
      <td>".$row['rutaFoto']."</td>
      <td>".$row['titulo']."</td>
      <td>".$row['fechaFoto']."</td>
      <td>".$row['Cuantos']."</td>
      <td><a href='verAlbumes.php?id=".$row['idAlbum']."'>Ver el álbum</a></td>
      </tr>";
  }

  $contenidoAnalisis.= "</tbody></table>"; //Cerramos la tabla

  if($numeroFilasFotosMasComentarios == 0) //Mensaje si no hay coincidencias
    $contenidoAnalisis.= "<p>No se encontraron álbumes</p>";

  mysqli_free_result($consultaFotosMasComentarios);
  //FIN DE FOTOS CON MÁS COMENTARIOS

  //INICIO DE ÁLBUMES MEJOR PUNTUADOS
  $consultaAlbumesMejorPuntuados = mysqli_query($conexion, "SELECT titulo, fechaAlbum, tipoAlbum, AVG(puntuacion) AS 'Promedio de estrellas' FROM Albumes LEFT JOIN Fotos USING(idAlbum) LEFT JOIN PuntuacionesComentarios USING (idFoto) WHERE puntuacion IS NOT NULL GROUP BY idAlbum ORDER BY AVG(puntuacion) DESC"); //Query que busca los álbumes mejor puntuados
  $numeroFilasAlbumesMejorPuntuados = mysqli_num_rows($consultaAlbumesMejorPuntuados);
  //Creamos la tabla y le ponemos el encabezado
  $contenidoAnalisis.=
  "
  <table class='responsive-table highlight centered'>
  <h4>Álbumes mejor puntuados</h4>
    <thead>
      <th>Título</th>
      <th>Fecha del álbum</th>
      <th>Privacidad del álbum</th>
      <th>Promedio de estrellas</th>
    </thead>
    <tbody>";

  while ($row = mysqli_fetch_assoc($consultaAlbumesMejorPuntuados)) //Rellenamos la tabla con los datos que devolvió el query
  {
      $contenidoAnalisis.= "<tr>
      <td>".$row['titulo']."</td>
      <td>".$row['fechaAlbum']."</td>
      <td>".$row['tipoAlbum']."</td>
      <td>".$row['Promedio de estrellas']."</td>
      <td><a href='verAlbumes.php?id=".$row['idAlbum']."'>Ver el álbum</a></td>
      </tr>";
  }

  $contenidoAnalisis.= "</tbody></table>"; //Cerramos la tabla

  if($numeroFilasAlbumesMejorPuntuados == 0) //Mensaje si no hay coincidencias
    $contenidoAnalisis.= "<p>No se encontraron álbumes</p>";

  mysqli_free_result($consultaAlbumesMejorPuntuados);
  //FIN DE ÁLBUMES MEJOR PUNTUADOS

  $contenidoAnalisis.="</div>"; //Cierre del contenedor del módulo de análisis de información

  $template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("principal.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums | Administración");
  $template->setVariable("IMAGEN",$rutaImagen);
  $template->setVariable("USUARIO",$rowPerfil['nombreUsuario']);
  $template->addBlockfile("CONTENIDO_INICIO", "INICIO", "inicio.html");
  $template->touchBlock('INICIO');
  $template->addBlockfile("CONTENIDO_ADMIN", "ADMIN", "admin.html");
  $template->touchBlock('ADMIN');
  $template->addBlockfile("CONTENIDO_REGISTRO", "REGISTRO", "registro.html");
  $template->touchBlock("REGISTRO");
  $template->addBlockfile("CONTENIDO_BUSQUEDA", "BUSQUEDA", "busqueda.html");
  $template->touchBlock('BUSQUEDA');
  $template->addBlockfile("CONTENIDO_ANALISIS", "ANALISIS", "analisis.html");
  $template->setCurrentBlock('ANALISIS');
  $template->setVariable("CONTENIDO_ANALISIS", $contenidoAnalisis);
  $template->parseCurrentBlock('ANALISIS');
  $template->show();

  Desconectar($conexion);
?>
