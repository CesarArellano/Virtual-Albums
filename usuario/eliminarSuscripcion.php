<?php
  include '../config.php';
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $conexion = Conectar();
  //Obtiene los valores por método POST y se convierten a valores enteros.
  $idUsuario = intval($_SESSION['idUsuario']);
  $idAlbum= intval($_POST['album']);
  // Se procede a eliminar la suscripciones a álbumes ajenos
  $consulta = mysqli_query($conexion,"DELETE FROM Suscripciones WHERE idAlbum = $idAlbum AND idUsuario = $idUsuario");
  if ($consulta)
  {
    echo json_encode(array('mensaje' => "¡Se ha eliminado la suscripción con éxito!", 'alerta' => "success"));
  }
  else
  {
    echo json_encode(array('mensaje' => "No se pudo eliminar la suscripción,intente de nuevo", 'alerta' => "error"));
  }
  mysqli_free_result($consulta);
  Desconectar($conexion);
?>
