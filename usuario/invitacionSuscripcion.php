<?php
  include '../config.php';
  header('Content-type: application/json; charset=utf-8');  // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $conexion = Conectar();
  //Obtiene información por método POST
  $idAlbum = intval($_POST['idAlbum']);
  $idUsuario = intval($_POST['idUsuario']);
  $correoUsuario =  $_POST['correoUsuario'];
  // Verifica si existe el usuario a suscribir.
  $consulta = mysqli_query($conexion, "SELECT idUsuario FROM Usuarios WHERE correo = '$correoUsuario'");
  $numerofilas = mysqli_num_rows($consulta);
  if($numerofilas > 0) // Si existe el usuario
  {
    $row = mysqli_fetch_assoc($consulta);
    $idUsuarioSuscripcion = intval($row['idUsuario']);
    if($idUsuario != $idUsuarioSuscripcion) // Comprueba que no sea el mismo usuario dueño del álbum
    {
      $estaSuscrito =  mysqli_query($conexion,"SELECT idUsuario FROM Suscripciones WHERE idUsuario = $idUsuarioSuscripcion AND idAlbum = $idAlbum");
      $numerofilas = mysqli_num_rows($estaSuscrito);
      if($numerofilas == 0) // si no está suscrito
      {
        //Agrega suscripción
        $agregarSuscripcion = mysqli_query($conexion,"INSERT INTO Suscripciones (idUsuario,idAlbum) VALUES($idUsuarioSuscripcion,$idAlbum)");
        $obtenerUsuario = mysqli_query($conexion,"SELECT nombreUsuario,apPaternoUsuario,apMaternoUsuario FROM Usuarios WHERE idUsuario = $idUsuario");
        $row2 = mysqli_fetch_assoc($obtenerUsuario);
        $nombreUsuario = $row2['nombreUsuario']." ".$row2['apPaternoUsuario']." ".$row2['apMaternoUsuario'];
        $obtenerAlbum= mysqli_query($conexion,"SELECT titulo FROM Albumes WHERE idAlbum = $idAlbum");
        $row3 = mysqli_fetch_assoc($obtenerAlbum);
        // Genera mensaje de notificación
        $mensaje = "El usuario ".$nombreUsuario." le compartió su álbum: ".$row3['titulo'];
        //Crea notificación de la suscripción al usuario correspondiente
        $notificacion_uno = mysqli_query($conexion,"INSERT INTO Notificaciones (idAlbum,contenido) VALUES($idAlbum,'$mensaje')");
        $idNotificacion = mysqli_insert_id($conexion);
        //Envía notificación al usuario
        $notificacion_suscritos = mysqli_query($conexion,"INSERT INTO NotificacionesLeidas (idNotificacion,idUsuario,estado) VALUES($idNotificacion,$idUsuarioSuscripcion,'No Leída')");
        mysqli_free_result($obtenerUsuario);
        mysqli_free_result($obtenerAlbum);
        echo json_encode(array('mensaje' => "Se compartió el álbum con éxito", 'alerta' => "success"));
      }
      else // Mensaje de error si ya está suscrito
      {
        echo json_encode(array('mensaje' => "El usuario a compartir el álbum ya se encuentra suscrito a su álbum", 'alerta' => "error"));
      }

    }
    else // Si ya se encuentra suscrito al álbum, manda mensaje de error.
    {
      echo json_encode(array('mensaje' => "Usted ya se encuentra suscrito a su álbum", 'alerta' => "error"));
    }
    mysqli_free_result($consulta);
  }
  else // Mensaje de error, si no existe el usuario
  {
    echo json_encode(array('mensaje' => "No existe el usuario", 'alerta' => "error"));
  }
  Desconectar($conexion);
?>
