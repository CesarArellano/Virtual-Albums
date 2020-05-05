<?php
  include_once("../config.php");
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();
  if (!isset($_SESSION['tipoUsuario']))
		header('location: ../index.php');
  $idUsuario = htmlentities($_GET['id']);
  $consulta = mysqli_query($conexion,"SELECT tipoUsuario FROM Usuarios WHERE idUsuario = $idUsuario");
  $row = mysqli_fetch_assoc($consulta);
  $tipoUsuarioRegistro = $row['tipoUsuario'];
  $template = new HTML_Template_ITX('./templates');
  if($tipoUsuarioRegistro == 1)
  {
    $template->loadTemplatefile("modificar_administrador.html", true, true);
  }
  else
  {
    $template->loadTemplatefile("modificar.html", true, true);
  }

  $template->setVariable("TITULO", "Virtual Albums");
  $template->setVariable("IDUSUARIO", $idUsuario);
  $template->setVariable("TIPOUSUARIO", $tipoUsuarioRegistro);
  $template->show();

  Desconectar($conexion);
?>
