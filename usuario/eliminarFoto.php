<?php
  include '../config.php';
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $conexion = Conectar();
  $idFoto = $_POST['foto'];
  $directorio = "images/albumes/"; //Definimos el directorio para el usuario donde se van a guardar las imágenes
  $consulta = mysqli_query($conexion, "SELECT rutaFoto FROM Fotos WHERE idFoto = $idFoto");
  $row = mysqli_fetch_assoc($consulta);
  $rutaFoto = $directorio.$row['rutaFoto'];
  $consulta2 = mysqli_query($conexion,"DELETE FROM Fotos WHERE idFoto = $idFoto");

  if ($consulta2)
  {
    unlink($rutaFoto);
    echo json_encode(array('mensaje' => "¡Se ha eliminado la foto con éxito!", 'alerta' => "success"));
  }
  else
  {
    echo json_encode(array('mensaje' => "No se pudo eliminar la foto", 'alerta' => "error"));
  }
  mysqli_free_result($consulta);
  Desconectar($conexion);
?>
