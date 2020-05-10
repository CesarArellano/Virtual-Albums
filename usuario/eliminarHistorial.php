<?php
  include "../config.php";
  header('Content-type: application/json; charset=utf-8');
  $conexion = Conectar();
  $idUsuario = intval($_POST['idUsuario']); // Se obtiene el id del usuario
  //Elimina todo el historial de búsqueda del usuario
  $eliminarHistorial = mysqli_query($conexion,"DELETE FROM HistorialBusqueda WHERE idUsuario = $idUsuario");
  if($eliminarHistorial)
  {
    echo json_encode(array('mensaje' => "¡Se borró el historial con éxito!", 'alerta' => "success"));
  }
  else
  {
    echo json_encode(array('mensaje' => "No se pudo eliminar el historial", 'alerta' => "error"));
  }
  Desconectar($conexion);
?>
