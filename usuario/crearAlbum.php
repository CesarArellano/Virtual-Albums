<?php
  include '../config.php';
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $conexion = Conectar();
  // Se obtienen los datos de la forma por métood POST
  $idUsuarioCrearAlbum = $_POST['idUsuario'];
  $nombreCrearAlbum = $_POST['nombreCrearAlbum'];
  $tipoCrearAlbum = $_POST['tipoCrearAlbum'];
  $temaCrearAlbum = $_POST['temaCrearAlbum'];
  // Se obtiene el id del tema.
  $consulta = mysqli_query($conexion,"SELECT idTema FROM Temas WHERE nombreTema = '$temaCrearAlbum'");
  $row = mysqli_fetch_assoc($consulta);
  $idTemaCrearAlbum = $row['idTema'];
  // Se registra el álbum
  $consulta = mysqli_query($conexion,"INSERT INTO Albumes(idTema,idUsuario,titulo,tipoAlbum,fechaAlbum) VALUES($idTemaCrearAlbum,$idUsuarioCrearAlbum,'$nombreCrearAlbum','$tipoCrearAlbum',CURDATE())");
  $idAlbumCrearAlbum = mysqli_insert_id($conexion); // Obtiene idAlbum que se insertó.
  // Lo suscribe a su propio álbum.
  $consulta = mysqli_query($conexion, "INSERT INTO Suscripciones(idUsuario,idAlbum) VALUES($idUsuarioCrearAlbum,$idAlbumCrearAlbum)");
  echo json_encode(array('mensaje' => "Se creó el álbum con éxito",'alerta' => "success"));
  mysqli_free_result($consulta);
  Desconectar($conexion);
?>
