<?php
  include '../config.php';
  header('Content-type: application/json; charset=utf-8');
  error_reporting(E_ALL);
  ini_set('display_errors', 1); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $conexion = Conectar();
  $idAlbum = intval($_POST['idAlbum']);
  $idUsuario = intval($_POST['idUsuario']);
  $correoUsuario =  $_POST['correoUsuario'];
  $consulta = mysqli_query($conexion, "SELECT idUsuario FROM Usuarios WHERE correo = '$correoUsuario'");
  $numerofilas = mysqli_num_rows($consulta);
  if($numerofilas > 0)
  {
    $row = mysqli_fetch_assoc($consulta);
    $idUsuarioSuscripcion = intval($row['idUsuario']);
    if($idUsuario != $idUsuarioSuscripcion)
    {
      $estaSuscrito =  mysqli_query($conexion,"SELECT idUsuario FROM Suscripciones WHERE idUsuario = $idUsuarioSuscripcion AND idAlbum = $idAlbum");
      $numerofilas = mysqli_num_rows($estaSuscrito);
      if($numerofilas == 0)
      {
        $agregarSuscripcion = mysqli_query($conexion,"INSERT INTO Suscripciones (idUsuario,idAlbum) VALUES($idUsuarioSuscripcion,$idAlbum)");
        $obtenerUsuario = mysqli_query($conexion,"SELECT nombreUsuario,apPaternoUsuario,apMaternoUsuario FROM Usuarios WHERE idUsuario = $idUsuario");
        $row2 = mysqli_fetch_assoc($obtenerUsuario);
        $nombreUsuario = $row2['nombreUsuario']." ".$row2['apPaternoUsuario']." ".$row2['apMaternoUsuario'];
        $obtenerAlbum= mysqli_query($conexion,"SELECT titulo FROM Albumes WHERE idAlbum = $idAlbum");
        $row3 = mysqli_fetch_assoc($obtenerAlbum);
        $mensaje = "El usuario ".$nombreUsuario." le compartió su álbum: ".$row3['titulo'];
        $notificacion_uno = mysqli_query($conexion,"INSERT INTO Notificaciones (idAlbum,contenido) VALUES($idAlbum,'$mensaje')");
        $idNotificacion = mysqli_insert_id($conexion);
        $notificacion_suscritos = mysqli_query($conexion,"INSERT INTO NotificacionesLeidas (idNotificacion,idUsuario,estado) VALUES($idNotificacion,$idUsuarioSuscripcion,'No Leída')");
        mysqli_free_result($obtenerUsuario);
        mysqli_free_result($obtenerAlbum);
        echo json_encode(array('mensaje' => "Se compartió el álbum con éxito", 'alerta' => "success"));
      }
      else
      {
        echo json_encode(array('mensaje' => "El usuario a compartir el álbum ya se encuentra suscrito a su álbum", 'alerta' => "error"));
      }

    }
    else
    {
      echo json_encode(array('mensaje' => "Usted ya se encuentra suscrito a su álbum", 'alerta' => "error"));
    }
    mysqli_free_result($consulta);
  }
  else
  {
    echo json_encode(array('mensaje' => "No existe el usuario", 'alerta' => "error"));
  }


  Desconectar($conexion);
?>
