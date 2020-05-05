<?php
  include '../config.php';
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $conexion = Conectar();
  $idUsuarioCrearAlbum = $_POST['idUsuario'];
  $nombreCrearAlbum = $_POST['nombreCrearAlbum'];
  $tipoCrearAlbum = $_POST['tipoCrearAlbum'];
  $temaCrearAlbum = $_POST['temaCrearAlbum'];
  $consulta = mysqli_query($conexion,"SELECT idTema FROM Temas WHERE nombreTema = '$temaCrearAlbum'");
  $row = mysqli_fetch_assoc($consulta);
  $idTemaCrearAlbum = $row['idTema'];
  $consulta = mysqli_query($conexion,"INSERT INTO Albumes(idTema,idUsuario,titulo,tipoAlbum,visitas,fechaAlbum) VALUES($idTemaCrearAlbum,$idUsuarioCrearAlbum,'$nombreCrearAlbum','$tipoCrearAlbum',0,CURDATE())");
  $idAlbumCrearAlbum = mysqli_insert_id($conexion);
  $consulta = mysqli_query($conexion, "INSERT INTO Suscripciones(idUsuario,idAlbum) VALUES($idUsuarioCrearAlbum,$idAlbumCrearAlbum)");
  echo json_encode(array('mensaje' => "Se creó el álbum con éxito",'alerta' => "success"));
  mysqli_free_result($consulta);
  Desconectar($conexion);
?>
