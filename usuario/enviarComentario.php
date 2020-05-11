<?php
  include_once "../config.php";
  $conexion = Conectar();
  // Obtiene datos por método POST
  $idUsuario = $_SESSION['idUsuario'];
  $idFoto = $_POST['idFoto'];
  $pagina = intval($_POST['tipo']);
  // Banderas que verifican que se envió.
  $existePuntuacion = 0;
  $existeComentario = 0;

  if(isset($_POST['rating'])) // Verifico si hay un puntuación
  {
    $puntuacion = $_POST['rating'];
    $existePuntuacion = 1;
  }
  if(isset($_POST['comentario']) && $_POST['comentario'] != '') // Verica que haya un comentario
  {
    $comentario = $_POST['comentario'];
    $existeComentario = 1;
  }
  if($pagina == 2) // Si está en verAlbumes, se obtiene el álbum para poder redirigirlo al mismo.
    $album = $_POST['idAlbum'];

  if($existeComentario == 1 || $existePuntuacion == 1) // Si existe comentario y/o notificacion
  {
    // Se obtiene el nombre completo del usuario que desea hacer el comentario
    $consultaNombre = mysqli_query($conexion,"SELECT nombreUsuario, apPaternoUsuario, apMaternoUsuario FROM Usuarios WHERE idUsuario = $idUsuario");
    $row = mysqli_fetch_assoc($consultaNombre);
    $nombreUsuario = $row['nombreUsuario']." ". $row['apPaternoUsuario']." ". $row['apMaternoUsuario'];
    mysqli_free_result($consultaNombre);
    // Se obtiene el idAlbum,titulo del álbum y nombre de la foto
    $consultaAlbumes = mysqli_query($conexion, "SELECT idAlbum,titulo,rutaFoto FROM Usuarios INNER JOIN Albumes USING(idUsuario) INNER JOIN Fotos USING(idAlbum) WHERE idFoto = $idFoto");
    $rowInformacionAlbum = mysqli_fetch_assoc($consultaAlbumes);
    $nombreFoto = $rowInformacionAlbum['rutaFoto'];
    $nombreAlbum = $rowInformacionAlbum['titulo'];
    $idAlbum = intval($rowInformacionAlbum['idAlbum']);
    //Obtiene los usuarios suscritos al álbum
    $obtener_usuarios = mysqli_query($conexion, "SELECT idUsuario FROM Suscripciones WHERE idAlbum = $idAlbum AND idUsuario != $idUsuario");
    // Verifica si el usuario previamente puntuó la foto.
    $consultaPuntuacion = mysqli_query($conexion,"SELECT idPuntuacionComentario FROM PuntuacionesComentarios WHERE idUsuario = $idUsuario AND idFoto = $idFoto AND puntuacion IS NOT NULL");
    $numeroFilas = mysqli_num_rows($consultaPuntuacion);
    if($existeComentario == 0) // Si solo se puntuó
    {
      if($numeroFilas == 1) // Si ya había una puntuación sólo la actualiza
      {
        $row = mysqli_fetch_assoc($consultaPuntuacion);
        $idPuntuacionComentario = $row['idPuntuacionComentario'];
        //Actualización de la puntuación
        $actualizarPuntuacion = mysqli_query($conexion, "UPDATE PuntuacionesComentarios SET puntuacion = $puntuacion WHERE idPuntuacionComentario = $idPuntuacionComentario");
        mysqli_free_result($actualizarPuntuacion);
      }
      else // Si no, inserta la puntuación en la BD.
      {
        //Inserta los datos en la base
        $insertarPuntuacion = mysqli_query($conexion, "INSERT INTO PuntuacionesComentarios(idUsuario,idFoto,puntuacion) VALUES($idUsuario,$idFoto,$puntuacion)");
        mysqli_free_result($insertarPuntuacion);
        $mensaje = "El usuario ".$nombreUsuario." calificó con ".$puntuacion." la foto ".$nombreFoto." del álbum ".$nombreAlbum;
        // Se notifica a los usuarios suscritos al álbum
        $notificacion_uno = mysqli_query($conexion,"INSERT INTO Notificaciones (idAlbum,contenido) VALUES($idAlbum,'$mensaje')");
        $idNotificacionPuntuacion = mysqli_insert_id($conexion); // Obtiene el id de la notificación que se insertó
        while($row = mysqli_fetch_assoc($obtener_usuarios)) // Obtiene y envía notificaciones a usuarios
        {
          $idUsuarios = $row['idUsuario'];
          //Por defecto las notificaciones se envían como no leídas
  				$notificacion_suscritos = mysqli_query($conexion,"INSERT INTO NotificacionesLeidas (idNotificacion,idUsuario,estado) VALUES($idNotificacionPuntuacion,$idUsuarios,'No Leída')");
  			}
      }
    }
    elseif($existePuntuacion == 0) // Si sólo hay comentario
    {
      // Se registra el comentario
      $insertarComentario = mysqli_query($conexion, "INSERT INTO PuntuacionesComentarios(idUsuario,idFoto,comentario,fechaComentario) VALUES($idUsuario,$idFoto,'$comentario',NOW())");
      mysqli_free_result($insertarComentario);
      $mensaje = "El usuario ".$nombreUsuario." comentó en la foto ".$nombreFoto." del álbum ".$nombreAlbum;
      //Se notifica al usuario
      $notificacion_uno = mysqli_query($conexion,"INSERT INTO Notificaciones (idAlbum,contenido) VALUES($idAlbum,'$mensaje')");
      $idNotificacionComentario = mysqli_insert_id($conexion);
      while($row = mysqli_fetch_assoc($obtener_usuarios)) // Se envía al usuario
      {
        $idUsuarios = $row['idUsuario'];
        //Notificación de todos los usuarios que estén suscritos
        $notificacion_suscritos = mysqli_query($conexion,"INSERT INTO NotificacionesLeidas (idNotificacion,idUsuario,estado) VALUES($idNotificacionComentario,$idUsuarios,'No Leída')");
      }
    }
    else // Si hay comentario y puntuación
    {
      if($numeroFilas == 1)  // Si hay puntuación previa actualiza
      {
        $row = mysqli_fetch_assoc($consultaPuntuacion);
        $idPuntuacionComentario = $row['idPuntuacionComentario'];
        //Actualización de una puntuación anterior
        $actualizarPuntuacion = mysqli_query($conexion, "UPDATE PuntuacionesComentarios SET puntuacion = $puntuacion WHERE idPuntuacionComentario = $idPuntuacionComentario");
        mysqli_free_result($actualizarPuntuacion);
      }
      else // Si no inserta en la BD
      {
        $insertarPuntuacion = mysqli_query($conexion, "INSERT INTO PuntuacionesComentarios(idUsuario,idFoto,puntuacion) VALUES($idUsuario,$idFoto,$puntuacion)");
        mysqli_free_result($insertarPuntuacion);
        $mensaje = "El usuario ".$nombreUsuario." calificó con ".$puntuacion." la foto ".$nombreFoto." del álbum ".$nombreAlbum;
        // Crea notificación de la puntuación
        $notificacion_uno = mysqli_query($conexion,"INSERT INTO Notificaciones (idAlbum,contenido) VALUES($idAlbum,'$mensaje')");
        $idNotificacionPuntuacion = mysqli_insert_id($conexion);
      }
       //Inserta el comentario
      $insertarComentario = mysqli_query($conexion, "INSERT INTO PuntuacionesComentarios(idUsuario,idFoto,comentario,fechaComentario) VALUES($idUsuario,$idFoto,'$comentario',NOW())");
      $mensaje = "El usuario ".$nombreUsuario." comentó en la foto ".$nombreFoto." del álbum ".$nombreAlbum;
      // Ahora crea la notificación del comentario
      $notificacion_dos = mysqli_query($conexion,"INSERT INTO Notificaciones (idAlbum,contenido) VALUES($idAlbum,'$mensaje')");
      $idNotificacionComentario = mysqli_insert_id($conexion);
      // Procede a obtener y notificar a los usuarios que tengan privilegios.
      while($row = mysqli_fetch_assoc($obtener_usuarios))
      {
        $idUsuarios = $row['idUsuario'];
        $notificacion_suscritos_puntuacion = mysqli_query($conexion,"INSERT INTO NotificacionesLeidas (idNotificacion,idUsuario,estado) VALUES($idNotificacionPuntuacion,$idUsuarios,'No Leída')");
        $notificacion_suscritos_comentario = mysqli_query($conexion,"INSERT INTO NotificacionesLeidas (idNotificacion,idUsuario,estado) VALUES($idNotificacionComentario,$idUsuarios,'No Leída')");
      }
      mysqli_free_result($insertarComentario);
    }
    if($pagina == 1) // Si está en el index.php
    {
      header('location: notificacion.php?mensaje=Se envío la valoración exitosamente&p=index&a=success');
    }
    else // Si está en verAlbumes.php
    {
      header('location: notificacion.php?mensaje=Se envío la valoración exitosamente&p=albumes&a=success&al='.$album);
    }

    mysqli_free_result($consultaPuntuacion);
  }
  else // Mensajes de error por no haber ingresado nada y dar click en submit
  {
    if($pagina == 1) // Si está en el index.php
    {
      header('location: notificacion.php?mensaje=Error, esta foto no ha sido puntuada, ni comentada&p=index&a=error');
    }
    else // Si está en verAlbumes.php
    {
      header('location: notificacion.php?mensaje=Error, esta foto no ha sido puntuada, ni comentada&p=albumes&a=error&al='.$album);
    }
  }
  Desconectar($conexion);
?>
