<?php
  include_once("../config.php");
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();
  if (!isset($_SESSION['tipoUsuario']))
		header('location: ../index.php');
  $idUsuario = htmlentities($_GET['id']);
  $vacio = 1;
  $vacio2 = 1;

  $template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("albumes.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums");
  $template->addBlockfile("TABLA", "ALBUMES", "tabla.html"); // De la página principal.html se coloca en contenido el bloque de código HTML de tabla.html y se coloca un ID llamado ALBUMES
  $template->setCurrentBlock("ALBUMES"); // Nos ubicamos en el bloque HTML ALBUMES

  $consultaUsuario = mysqli_query($conexion,"SELECT tipoUsuario FROM Usuarios WHERE idUsuario = $idUsuario");
  $fila = mysqli_fetch_assoc($consultaUsuario);
  $tipoUsuario = $fila['tipoUsuario'];
  mysqli_free_result($consultaUsuario);
  // Ejecutamos el query
  $result = mysqli_query($conexion, "SELECT idAlbum, titulo, tipoAlbum, fechaAlbum, COUNT(Visitas.idAlbum) as 'visitas' FROM Albumes LEFT JOIN Visitas USING(idAlbum) WHERE idUsuario = $idUsuario GROUP BY idAlbum") or die("Could not execute query");

  while($line = mysqli_fetch_assoc($result))
  {
    $vacio = 0;

    // Fijamos el bloque con la informacion de cada álbum
    $template->setCurrentBlock("ALBUM"); // Nos ubicamos en el segmento de código donde están las columnas de la tabla
    // Desplegamos la informacion de cada presidentes y se rellena con la información obtenida de la BD.
    $template->setVariable("TITULOALBUM", $line['titulo']);
    $template->setVariable("TIPOALBUM", $line['tipoAlbum']);
    $template->setVariable("VISITASALBUM", $line['visitas']);
    $template->setVariable("FECHAALBUM", $line['fechaAlbum']);

    $result2 = mysqli_query($conexion, "SELECT count(idFoto) as cantidadFotos FROM Fotos WHERE idAlbum = $line[idAlbum]") or die("Could not execute query");
    $line2 = mysqli_fetch_assoc($result2);
    $template->setVariable("CANTIDADFOTOSALBUM", $line2['cantidadFotos']);
    mysqli_free_result($result2);

    $result3 = mysqli_query($conexion, "SELECT count(idUsuario) as cantidadComentarios FROM PuntuacionesComentarios LEFT JOIN Fotos USING(idFoto) WHERE comentario IS NOT NULL AND idUsuario = $idUsuario AND idAlbum = $line[idAlbum]") or die("Could not execute query");
    $line3 = mysqli_fetch_assoc($result3);
    $template->setVariable("CANTIDADCOMENTARIOSALBUM", $line3['cantidadComentarios']);
    mysqli_free_result($result3);

    $template->parseCurrentBlock("ALBUM"); //Se sale del bloque de código HTML ALBUM

  }// while
  if($vacio == 1)
  {
    $template->setVariable("MENSAJEVACIO", "Este usuario no es propietario de ningún album");
  }
  else
    $template->setVariable("MENSAJEVACIO", "");

  $template->parseCurrentBlock("ALBUMES"); //Se sale del bloque de código HTML ALBUMES
  // Liberamos memoria
  mysqli_free_result($result);
  if($tipoUsuario == 1)
  {
    $template->setVariable("TABLA2", "SUSCRIPCIONES", "");
  }
  else
  {
    $template->addBlockfile("TABLA2", "SUSCRIPCIONES", "tabla2.html"); // De la página principal.html se coloca en contenido el bloque de código HTML de tabla.html y se coloca un ID llamado ALBUMES
    $template->setCurrentBlock("SUSCRIPCIONES"); // Nos ubicamos en el bloque HTML SUSCRIPCIONES

    // Ejecutamos el query
    $result4 = mysqli_query($conexion, "SELECT idAlbum, titulo, tipoAlbum, fechaAlbum, COUNT(Visitas.idAlbum) as 'visitas' FROM Albumes a LEFT JOIN Suscripciones s USING (idAlbum) LEFT JOIN Visitas USING(idAlbum) WHERE s.idUsuario = $idUsuario AND a.idUsuario != $idUsuario GROUP BY idAlbum") or die("Could not execute query");

    while($line4 = mysqli_fetch_assoc($result4))
    {
      $vacio2 = 0;

      // Fijamos el bloque con la informacion de cada álbum
      $template->setCurrentBlock("SUSCRIPCION"); // Nos ubicamos en el segmento de código donde están las columnas de la tabla
      // Desplegamos la informacion de cada presidentes y se rellena con la información obtenida de la BD.
      $template->setVariable("TITULOSUSCRIPCION", $line4['titulo']);
      $template->setVariable("TIPOSUSCRIPCION", $line4['tipoAlbum']);
      $template->setVariable("VISITASSUSCRIPCION", $line4['visitas']);
      $template->setVariable("FECHASUSCRIPCION", $line4['fechaAlbum']);

      $result5 = mysqli_query($conexion, "SELECT count(idFoto) as cantidadFotos FROM Fotos WHERE idAlbum = $line4[idAlbum]") or die("Could not execute query");
      $line5 = mysqli_fetch_assoc($result5);
      $template->setVariable("CANTIDADFOTOSSUSCRIPCION", $line5['cantidadFotos']);
      mysqli_free_result($result5);

      $result6 = mysqli_query($conexion, "SELECT count(idUsuario) as cantidadComentarios FROM PuntuacionesComentarios LEFT JOIN Fotos USING(idFoto) WHERE comentario IS NOT NULL AND idUsuario = $idUsuario AND idAlbum = $line4[idAlbum]") or die("Could not execute query");
      $line6 = mysqli_fetch_assoc($result6);
      $template->setVariable("CANTIDADCOMENTARIOSSUSCRIPCION", $line6['cantidadComentarios']);
      mysqli_free_result($result6);

      $template->parseCurrentBlock("SUSCRIPCION"); //Se sale del bloque de código HTML ALBUM

     }// while

     $template->parseCurrentBlock("SUSCRIPCIONES"); //Se sale del bloque de código HTML SUSCRIPCIONES

     if($vacio2 == 1)
      $template->setVariable("MENSAJEVACIO2", "Este usuario no es suscriptor de ningún album");
    else
      $template->setVariable("MENSAJEVACIO2", "");

    // Liberamos memoria
    mysqli_free_result($result4);
  }
  $template->show();

  Desconectar($conexion);
?>
