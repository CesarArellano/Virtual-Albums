<?php
  include_once "../config.php";
  $conexion = Conectar();
  $idUsuario = $_SESSION['idUsuario'];
  $idFoto = $_POST['idFoto'];
  $pagina = intval($_POST['tipo']);
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
  if($pagina == 2)
    $album = $_POST['idAlbum'];

  if($existeComentario == 1 || $existePuntuacion == 1)
  {
    $consultaNombre = mysqli_query($conexion,"SELECT nombreUsuario, apPaternoUsuario, apMaternoUsuario FROM Usuarios WHERE idUsuario = $idUsuario");
    $row = mysqli_fetch_assoc($consultaNombre);
    $nombreUsuario = $row['nombreUsuario']." ". $row['apPaternoUsuario']." ". $row['apMaternoUsuario'];
    $consultaAlbumes = mysqli_query($conexion, "SELECT idAlbum,titulo,rutaFoto FROM Usuarios INNER JOIN Albumes USING(idUsuario) INNER JOIN Fotos USING(idAlbum) WHERE idFoto = $idFoto");
    $rowInformacionAlbum = mysqli_fetch_assoc($consultaAlbumes);
    $nombreFoto = $rowInformacionAlbum['rutaFoto'];
    $nombreAlbum = $rowInformacionAlbum['titulo'];
    $idAlbum = intval($rowInformacionAlbum['idAlbum']);
    $obtener_usuarios = mysqli_query($conexion, "SELECT idUsuario FROM Suscripciones WHERE idAlbum = $idAlbum AND idUsuario != $idUsuario");
    $consultaPuntuacion = mysqli_query($conexion,"SELECT idPuntuacionComentario FROM PuntuacionesComentarios WHERE idUsuario = $idUsuario AND idFoto = $idFoto AND puntuacion IS NOT NULL");
    $numeroFilas = mysqli_num_rows($consultaPuntuacion);
    if($existeComentario == 0)
    {
      if($numeroFilas == 1)
      {
        $row = mysqli_fetch_assoc($consultaPuntuacion);
        $idPuntuacionComentario = $row['idPuntuacionComentario'];
        $actualizarPuntuacion = mysqli_query($conexion, "UPDATE PuntuacionesComentarios SET puntuacion = $puntuacion WHERE idPuntuacionComentario = $idPuntuacionComentario");
        mysqli_free_result($actualizarPuntuacion);
      }
      else
      {
        $insertarPuntuacion = mysqli_query($conexion, "INSERT INTO PuntuacionesComentarios(idUsuario,idFoto,puntuacion) VALUES($idUsuario,$idFoto,$puntuacion)");
        mysqli_free_result($insertarPuntuacion);
        $mensaje = "El usuario ".$nombreUsuario." calificó con ".$puntuacion." la foto ".$nombreFoto." del álbum ".$nombreAlbum;
        $notificacion_uno = mysqli_query($conexion,"INSERT INTO Notificaciones (idAlbum,contenido) VALUES($idAlbum,'$mensaje')");
        $idNotificacionPuntuacion = mysqli_insert_id($conexion);
        while($row = mysqli_fetch_assoc($obtener_usuarios))
        {
          $idUsuarios = $row['idUsuario'];
  				$notificacion_suscritos = mysqli_query($conexion,"INSERT INTO NotificacionesLeidas (idNotificacion,idUsuario,estado) VALUES($idNotificacionPuntuacion,$idUsuarios,'No Leída')");
  			}
      }
    }
    elseif($existePuntuacion == 0)
    {
      $insertarComentario = mysqli_query($conexion, "INSERT INTO PuntuacionesComentarios(idUsuario,idFoto,comentario,fechaComentario) VALUES($idUsuario,$idFoto,'$comentario',NOW())");
      mysqli_free_result($insertarComentario);
      $mensaje = "El usuario ".$nombreUsuario." comentó en la foto ".$nombreFoto." del álbum ".$nombreAlbum;
      $notificacion_uno = mysqli_query($conexion,"INSERT INTO Notificaciones (idAlbum,contenido) VALUES($idAlbum,'$mensaje')");
      $idNotificacionComentario = mysqli_insert_id($conexion);
      while($row = mysqli_fetch_assoc($obtener_usuarios))
      {
        $idUsuarios = $row['idUsuario'];
        $notificacion_suscritos = mysqli_query($conexion,"INSERT INTO NotificacionesLeidas (idNotificacion,idUsuario,estado) VALUES($idNotificacionComentario,$idUsuarios,'No Leída')");
      }
    }
    else
    {
      if($numeroFilas == 1)
      {
        $row = mysqli_fetch_assoc($consultaPuntuacion);
        $idPuntuacionComentario = $row['idPuntuacionComentario'];
        $actualizarPuntuacion = mysqli_query($conexion, "UPDATE PuntuacionesComentarios SET puntuacion = $puntuacion WHERE idPuntuacionComentario = $idPuntuacionComentario");
        mysqli_free_result($actualizarPuntuacion);
      }
      else
      {
        $insertarPuntuacion = mysqli_query($conexion, "INSERT INTO PuntuacionesComentarios(idUsuario,idFoto,puntuacion) VALUES($idUsuario,$idFoto,$puntuacion)");
        mysqli_free_result($insertarPuntuacion);
        $mensaje = "El usuario ".$nombreUsuario." calificó con ".$puntuacion." la foto ".$nombreFoto." del álbum ".$nombreAlbum;
        $notificacion_uno = mysqli_query($conexion,"INSERT INTO Notificaciones (idAlbum,contenido) VALUES($idAlbum,'$mensaje')");
        $idNotificacionPuntuacion = mysqli_insert_id($conexion);
      }
      $insertarComentario = mysqli_query($conexion, "INSERT INTO PuntuacionesComentarios(idUsuario,idFoto,comentario,fechaComentario) VALUES($idUsuario,$idFoto,'$comentario',NOW())");
      $mensaje = "El usuario ".$nombreUsuario." comentó en la foto ".$nombreFoto." del álbum ".$nombreAlbum;
      $notificacion_dos = mysqli_query($conexion,"INSERT INTO Notificaciones (idAlbum,contenido) VALUES($idAlbum,'$mensaje')");
      $idNotificacionComentario = mysqli_insert_id($conexion);

      while($row = mysqli_fetch_assoc($obtener_usuarios))
      {
        $idUsuarios = $row['idUsuario'];
        $notificacion_suscritos_puntuacion = mysqli_query($conexion,"INSERT INTO NotificacionesLeidas (idNotificacion,idUsuario,estado) VALUES($idNotificacionPuntuacion,$idUsuarios,'No Leída')");
        $notificacion_suscritos_comentario = mysqli_query($conexion,"INSERT INTO NotificacionesLeidas (idNotificacion,idUsuario,estado) VALUES($idNotificacionComentario,$idUsuarios,'No Leída')");
      }
      mysqli_free_result($insertarComentario);
    }
    if($pagina == 1)
    {
      header('location: notificacion.php?mensaje=Se envío la valoración exitosamente&p=index&a=success');
    }
    else
    {
      header('location: notificacion.php?mensaje=Se envío la valoración exitosamente&p=albumes&a=success&al='.$album);
    }

    mysqli_free_result($consultaPuntuacion);
  }
  else
  {
    if($pagina == 1)
    {
      header('location: notificacion.php?mensaje=Error, esta foto no ha sido puntuada, ni comentada&p=index&a=error');
    }
    else
    {
      header('location: notificacion.php?mensaje=Error, esta foto no ha sido puntuada, ni comentada&p=albumes&a=error&al='.$album);
    }
  }
  Desconectar($conexion);
?>
