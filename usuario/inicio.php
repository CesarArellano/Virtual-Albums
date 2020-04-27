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
  <div class="container">
    <h1 class="center">Bienvenido al sistema (Usuario)</h1>
    <center>
      <p>Foto de perfil</p>
      <img class="responsive-img" src="images/<?php echo $_SESSION['foto']?>" width="100px" height="100px" alt=""/>
      <br>
      <button class="btn waves-effect waves-light light-blue accent-4 center" onclick=" location.href='../logout.php'" name="action" id="action">Cerrar Sesión
        <i class="material-icons right">send</i>
      </button>
    </center>
  </div>
</body>
</html>
