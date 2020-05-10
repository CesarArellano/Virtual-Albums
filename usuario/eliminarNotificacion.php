<?php
  include "../config.php";
  require_once "HTML/Template/ITX.php";
  header('Content-type: application/json; charset=utf-8');
  $conexion = Conectar();
  $idNotificacionLeida = $_POST['idNotificacionLeida']; // Obtiene el id de la notificación del usuario.
	$idNotificacionLeida = intval($idNotificacionLeida); // Convierte a entero.
  // Eliminar la notificación de la BD.
  $eliminar = mysqli_query($conexion,"DELETE FROM NotificacionesLeidas WHERE idNotificacionLeida = $idNotificacionLeida");
  if($eliminar)
  {
    echo json_encode(array('mensaje' => "¡Se eliminó la notificación éxitosamente!", 'alerta' => "success"));
  }
  else
  {
    echo json_encode(array('mensaje' => "No se pudo eliminar la notificación", 'alerta' => "error"));
  }
  Desconectar($conexion);
?>
