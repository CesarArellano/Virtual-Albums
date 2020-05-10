<?php
  include '../config.php'; // Incluimos la conexion
  header('Content-type: application/json; charset=utf-8'); // Codifica el json en utf-8
  $conexion = Conectar();
  $consulta = mysqli_query($conexion,"SELECT * FROM Temas"); // Seleccionamos todo de la tabla de álbumes
  $elementos = array(); // Establecemos un array
  while ($row = mysqli_fetch_assoc($consulta))
  {
    array_push($elementos, $row['nombreTema']); // Llenamos el array con los nombres de los temas de los álbumes
  }
  echo json_encode($elementos); // Mandamos el array con codificación JSON
  Desconectar($conexion);
?>
