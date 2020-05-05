<?php
  include_once("../config.php");
  require_once "HTML/Template/ITX.php";
  $conexion = Conectar();

  if (!isset($_SESSION['tipoUsuario']))
		header('location: ../index.php');

  $idUsuario = $_SESSION['idUsuario'];

  $query = mysqli_query($conexion,"SELECT * FROM Usuarios WHERE idUsuario = $idUsuario");
  $row = mysqli_fetch_assoc($query);
  if($row['foto'] == NULL)
    $rutaImagen = "../images/avatar.png";
  else
    $rutaImagen = "images/perfil/".$row['foto'];
  $template = new HTML_Template_ITX('./templates');
  $template->loadTemplatefile("principal.html", true, true);
  $template->setVariable("TITULO", "Virtual Albums | Administración");
  $template->setVariable("IMAGEN",$rutaImagen);
  $template->setVariable("USUARIO",$row['nombreUsuario']);
  $template->addBlockfile("CONTENIDO_INICIO", "INICIO", "inicio.html");
  $template->touchBlock('INICIO');
  $template->addBlockfile("CONTENIDO_ADMIN", "ADMIN", "admin.html");
  $template->touchBlock('ADMIN');
  $template->addBlockfile("CONTENIDO_REGISTRO", "REGISTRO", "registro.html");
  $template->touchBlock('REGISTRO');
  $template->addBlockfile("CONTENIDO_BUSQUEDA", "BUSQUEDA", "busqueda.html");
  $template->touchBlock('BUSQUEDA');
  $template->addBlockfile("CONTENIDO_VISUALIZACION", "VISUALIZACION", "visualizacion.html");
  $template->touchBlock('VISUALIZACION');
  $template->addBlockfile("CONTENIDO_ANALISIS", "ANALISIS", "analisis.html");
  $template->touchBlock('ANALISIS');
  $template->show();

  Desconectar($conexion);
?>
