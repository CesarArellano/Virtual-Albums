<?php
	include_once '../config.php';
  $conexion = Conectar();
	$idFoto = $_GET['foto'];
	$idFoto = intval($idFoto);

	$autorizar = mysqli_query($conexion,"UPDATE Fotos SET autorizada = '1' WHERE idFoto = $idFoto");
	if($autorizar)
	{
      $consulta = mysqli_query($conexion,"SELECT idUsuario,idAlbum,rutaFoto,titulo FROM Fotos INNER JOIN Albumes USING(idAlbum) INNER JOIN Usuarios USING(idUsuario) WHERE idFoto = $idFoto");
      $row = mysqli_fetch_assoc($consulta);
      $idUsuario = $row['idUsuario'];
      $idAlbum = $row['idAlbum'];
      $nombreFoto = $row['rutaFoto'];
			$nombreAlbum = $row['titulo'];
      $mensaje = "La fotografía ".$nombreFoto." del álbum: ".$nombreAlbum." ha sido publicada en el sistema";
      $notificacion_uno = mysqli_query($conexion,"INSERT INTO Notificaciones (idAlbum,contenido) VALUES($idAlbum,'$mensaje')");
      $idNotificacion = mysqli_insert_id($conexion);
      $obtener_usuarios = mysqli_query($conexion, "SELECT idUsuario FROM Suscripciones WHERE idAlbum = $idAlbum");

      while($row = mysqli_fetch_assoc($obtener_usuarios))
      {
        $idUsuarios = $row['idUsuario'];
				$notificacion_suscritos = mysqli_query($conexion,"INSERT INTO NotificacionesLeidas (idNotificacion,idUsuario,estado) VALUES($idNotificacion,$idUsuarios,'No Leída')");
			}
			header('location: notificacion.php?mensaje=Operación éxitosa,se le notificará a los usuarios suscritos.&p=index&a=success');
	}
	else
	{
    header('location: notificacion.php?mensaje=Ha ocurrido un error&p=index&a=error');
	}
  mysqli_free_result($row);
	Desconectar($conexion);
?>
