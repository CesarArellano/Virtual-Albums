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
    echo $idUsuario.$idFoto.$pagina;
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
      }

      mysqli_free_result($insertarPuntuacion);
    }
    elseif($existePuntuacion == 0)
    {
      $insertarComentario = mysqli_query($conexion, "INSERT INTO PuntuacionesComentarios(idUsuario,idFoto,comentario) VALUES($idUsuario,$idFoto,'$comentario')");
      mysqli_free_result($insertarComentario);
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
      }
      $insertarComentario = mysqli_query($conexion, "INSERT INTO PuntuacionesComentarios(idUsuario,idFoto,comentario) VALUES($idUsuario,$idFoto,'$comentario')");
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
