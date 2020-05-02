<?php
  include '../config.php';
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $conexion = Conectar();
  $idAlbum = $_POST['idAlbum'];
  $nombreAlbum = $_POST['nombreAlbum'];
  $tipoAlbum = $_POST['tipoAlbum'];
  $temaAlbum = $_POST['temaAlbum'];
  $consulta = mysqli_query($conexion,"SELECT idTema FROM Temas WHERE nombreTema = '$temaAlbum'");
  $row = mysqli_fetch_assoc($consulta);
  $idTemaAlbum = $row['idTema'];
  $consulta2 = mysqli_query($conexion,"UPDATE Albumes SET titulo = '$nombreAlbum',tipoAlbum= '$tipoAlbum',idTema = $idTemaAlbum WHERE idAlbum = $idAlbum");
  echo json_encode(array('mensaje' => "Se creó el álbum con éxito",'alerta' => "success"));

  mysqli_free_result($consulta);
  Desconectar($conexion);
?>
