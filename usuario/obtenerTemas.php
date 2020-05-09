<?php
  include '../config.php'; // Incluimos la conexion
  $conexion = Conectar();
  $consulta = mysqli_query($conexion,"SELECT * FROM Temas"); // Seleccionamos todo de la tabla de secciones
  $elementos = array(); // Establecemos un array
  while ($row = mysqli_fetch_assoc($consulta))
  {
    array_push($elementos, $row['nombreTema']); // Llenamos el array con las secciones
  }
  echo json_encode($elementos); // Imprimimos el array con codificación de JSON
  Desconectar($conexion);
?>
