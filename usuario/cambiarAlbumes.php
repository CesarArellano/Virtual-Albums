<?php
  include '../config.php';
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $conexion = Conectar();
  // Se obtiene los datos del álbum a cambiar.
  $idAlbum = $_POST['idAlbum'];
  $nombreAlbum = $_POST['nombreAlbum'];
  $tipoAlbum = $_POST['tipoAlbum'];
  $temaAlbum = $_POST['temaAlbum'];

  $consulta = mysqli_query($conexion,"SELECT idTema FROM Temas WHERE nombreTema = '$temaAlbum'"); // Se obtiene el id del tema del álbum
  $row = mysqli_fetch_assoc($consulta);
  $idTemaAlbum = $row['idTema'];
  //Actualiza la información del álbum
  $consulta2 = mysqli_query($conexion,"UPDATE Albumes SET titulo = '$nombreAlbum',tipoAlbum= '$tipoAlbum',idTema = $idTemaAlbum WHERE idAlbum = $idAlbum");
  echo json_encode(array('mensaje' => "Se creó el álbum con éxito",'alerta' => "success"));

  mysqli_free_result($consulta); // Liberación de memoria
  mysqli_free_result($consulta2);
  Desconectar($conexion);
?>
