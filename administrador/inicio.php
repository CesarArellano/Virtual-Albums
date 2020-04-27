<?php
  session_start();
	if (!isset($_SESSION['token']))
		header('location: ../inicio.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Administrador</title>
	<meta charset="utf-8">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="../js/materialize.min.js"></script>
    <script type="text/javascript" src="../js/script.js"></script>
</head>
<body>
  <nav>
    <div class="nav-wrapper light-blue accent-4">
      <a href="#!" class="brand-logo">Virtual Albums</a>
      <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
      <ul class="right hide-on-med-and-down">
        <li><a href="sass.html">Inicio</a></li>
        <li><a href="badges.html">Perfil</a></li>
        <li><a href="collapsible.html">Álbumes</a></li>
        <li><a href="mobile.html">Fotos</a></li>
      </ul>
      <ul class="side-nav" id="mobile-demo">
        <li><a href="sass.html">Inicio</a></li>
        <li><a href="badges.html">Perfil</a></li>
        <li><a href="collapsible.html">Álbumes</a></li>
        <li><a href="mobile.html">Fotos</a></li>
      </ul>
    </div>
  </nav>
  <h1 class="center">Bienvenido al sistema (Administrador)</h1>
  <center>
    <button class="btn waves-effect waves-light light-blue accent-4 center" onclick=" location.href='../logout.php'" name="action" id="action">Cerrar Sesión
      <i class="material-icons right">send</i>
    </button>
  </center>
</body>
</html>
