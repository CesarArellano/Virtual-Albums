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
  <nav class="nav-extended light-blue accent-4">
    <div class="nav-wrapper">
      <a href="#" class="brand-logo">Virtual Albums</a>
    </div>
    <div class="nav-content">
      <ul class="tabs tabs-transparent">
        <li class="tab"><a class="active" href="#test1">Inicio</a></li>
        <li class="tab"><a href="#test2">Test 2</a></li>
        <li class="tab"><a href="#test3">Test 3</a></li>
      </ul>
    </div>
  </nav>
  <div id="test1" class="col s12">
    <h1 class="center">Virtual Albums</h1>
  </div>
  <div id="test2" class="col s12">Test 2</div>
  <div id="test3" class="col s12">Test 3</div>

  <center>
    <button class="btn waves-effect waves-light light-blue accent-4 center" onclick=" location.href='../logout.php'" name="action" id="action">Cerrar Sesión
      <i class="material-icons right">send</i>
    </button>
  </center>
</body>
</html>
