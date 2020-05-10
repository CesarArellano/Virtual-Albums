<?php
  include "../config.php";
  require_once "HTML/Template/ITX.php";
  header('Content-type: application/json; charset=utf-8');
  $conexion = Conectar();
  $idNotificacionLeida = $_POST['idNotificacionLeida']; //Obtiene id por método POST
	$idNotificacionLeida = intval($idNotificacionLeida); // Convierte a entero
  // Cambia el estado notificación por leída.
  $autorizar = mysqli_query($conexion,"UPDATE NotificacionesLeidas SET estado = 'Leída' WHERE idNotificacionLeida = $idNotificacionLeida");
  if($autorizar) // Se actualizó correctamente manda mensaje éxitoso
  {
    echo json_encode(array('mensaje' => "¡Se cambió el estado de la notificación!", 'alerta' => "success"));
  }
  else
  {
    echo json_encode(array('mensaje' => "No se pudo cambiar el estado de la notificación", 'alerta' => "error"));
  }
  Desconectar($conexion);
?>
