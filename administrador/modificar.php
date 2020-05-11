<?php
  include_once("../config.php");
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();
  if (!isset($_SESSION['tipoUsuario']))
		header('location: ../index.php');
  $idUsuario = htmlentities($_GET['id']); // Se obtiene el id del usuario a modificar su información
  $consulta = mysqli_query($conexion,"SELECT tipoUsuario FROM Usuarios WHERE idUsuario = $idUsuario"); //Busca que tipo de usuario es el que se encuentra usando el sistema
  $row = mysqli_fetch_assoc($consulta);
  $tipoUsuarioRegistro = $row['tipoUsuario']; //Obtiene tipo de usuario de acuerdo al id recibido
  $template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("modificar.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums");
  $template->setVariable("IDUSUARIO", $idUsuario);
  $template->setVariable("TIPOUSUARIO", $tipoUsuarioRegistro);
  $template->show();

  Desconectar($conexion);
?>
