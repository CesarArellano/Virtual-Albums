<?php
  include '../config.php';
  header('Content-type: application/json; charset=utf-8');
  error_reporting(E_ALL);
  ini_set('display_errors', 1); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $conexion = Conectar();
  $idAlbum= $_POST['album'];
  $directorio = "images/albumes/"; //Definimos el directorio para el usuario donde se van a guardar las imágenes
  $consulta = mysqli_query($conexion, "SELECT rutaFoto FROM Fotos WHERE idAlbum = $idAlbum");
  while($row = mysqli_fetch_assoc($consulta))
  {
    $rutaFoto = $directorio.$row['rutaFoto'];
    unlink($rutaFoto);
  }

  $consulta2 = mysqli_query($conexion,"DELETE FROM Albumes WHERE idAlbum = $idAlbum");
  if ($consulta2)
  {
    echo json_encode(array('mensaje' => "¡Se ha eliminado el álbum con éxito!", 'alerta' => "success"));
  }
  else
  {
    echo json_encode(array('mensaje' => "No se pudo eliminar el álbum", 'alerta' => "error"));
  }
  mysqli_free_result($consulta);
  Desconectar($conexion);
?>
