<?php
  include "../config.php";
  require_once "HTML/Template/ITX.php";
  header('Content-type: application/json; charset=utf-8');
  $conexion = Conectar();
  $idNotificacionLeida = $_POST['idNotificacionLeida'];
	$idNotificacionLeida = intval($idNotificacionLeida);
  $autorizar = mysqli_query($conexion,"UPDATE NotificacionesLeidas SET estado = 'Leída' WHERE idNotificacionLeida = $idNotificacionLeida");
  if($autorizar)
  {
    echo json_encode(array('mensaje' => "¡Se cambió el estado de la notificación!", 'alerta' => "success"));
  }
  else
  {
    echo json_encode(array('mensaje' => "No se pudo cambiar el estado de la notificación", 'alerta' => "error"));
  }
  Desconectar($conexion);
?>
