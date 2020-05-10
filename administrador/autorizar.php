<?php
	include_once '../config.php';
  $conexion = Conectar();
	//Se obtiene el id de la foto a autorizar
	$idFoto = $_GET['foto'];
	$idFoto = intval($idFoto);
	// Se obtiene información del álbum y el usuario propietario de la mism
	$consulta = mysqli_query($conexion,"SELECT idUsuario, idAlbum, rutaFoto, titulo FROM Fotos INNER JOIN Albumes USING(idAlbum) INNER JOIN Usuarios USING(idUsuario) WHERE idFoto = $idFoto");
	$row = mysqli_fetch_assoc($consulta);
	$idUsuario = $row['idUsuario'];
	$idAlbum = $row['idAlbum'];
	$nombreFoto = $row['rutaFoto'];
	$nombreAlbum = $row['titulo'];
	// Mensaje de notificación para los usuarios suscritos
	$mensaje = "La fotografía ".$nombreFoto." del álbum: ".$nombreAlbum." ha sido publicada en el sistema";
	// Se manda a llamar un procedimiento que autoriza y notifica a los usuarios que se autorizó la foto
	$autorizar = mysqli_query($conexion, "CALL autorizarFoto($idFoto, $idUsuario, $idAlbum, '$mensaje')");

	if($autorizar) // Si todo sale bien, manda mensaje éxitoso
	{
		header('location: notificacion.php?mensaje=Operación éxitosa,se le notificará a los usuarios suscritos.&p=index&a=success');
	}
	else // Si no manda mensaje de error.
	{
    header('location: notificacion.php?mensaje=Ha ocurrido un error&p=index&a=error');
	}

  mysqli_free_result($consulta);
	mysqli_free_result($autorizar);
	Desconectar($conexion);
?>
