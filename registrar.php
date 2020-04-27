<?php
  include 'config.php';
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  $conexion = Conectar();
  $nombreRegistro = $_POST['nombreRegistro'];
  $apPaternoRegistro = $_POST['apPaternoRegistro'];
  $apMaternoRegistro = $_POST['apMaternoRegistro'];
  $escolaridadRegistro = $_POST['escolaridadRegistro'];
  $direccionRegistro = $_POST['direccionRegistro'];
  $fechaNacimientoRegistro = $_POST['fechaNacimientoRegistro'];
  $correoRegistro = $_POST['correoRegistro'];
  $passwordRegistro = $_POST['passwordRegistro'];
  $tipoUsuarioRegistro = $_POST['tipoUsuarioRegistro'];
  $rutaFotoPerfilRegistro = $_POST['rutaFotoPerfilRegistro'];

  if ($rutaFotoPerfilRegistro != '')
  {
    $directorio = 'usuario/images/';
    $nombreArchivo = $_FILES['rutaFotoPerfilRegistro']['name'];
    $directoriocompleto = $directorio.$nombreArchivo;
    if (move_uploaded_file($_FILES['rutaFotoPerfilRegistro']["tmp_name"], $directoriocompleto))
    {
      $query = mysqli_query($conexion,"INSERT INTO Usuarios (nombreUsuario, apPaternoUsuario, apMaternoUsuario, escolaridad, direccion, nacimiento, foto, correo, password, tipoUsuario) VALUES('$nombreRegistro', '$apPaternoRegistro', '$apMaternoRegistro', '$escolaridadRegistro', '$direccionRegistro', '$fechaNacimientoRegistro','$rutaFotoPerfilRegistro', '$correoRegistro', '$passwordRegistro', $tipoUsuarioRegistro)");
    }
    else
    {
      printf("Errormessage: %s\n", mysqli_error($conexion));
      echo "Ha ocurrido un error): subir imagen";
    }
  }
  else
  {
    $query = mysqli_query($conexion,"INSERT INTO Usuarios (nombreUsuario, apPaternoUsuario, apMaternoUsuario, escolaridad, direccion, nacimiento, foto, correo, password, tipoUsuario) VALUES('$nombreRegistro', '$apPaternoRegistro', '$apMaternoRegistro', '$escolaridadRegistro', '$direccionRegistro', '$fechaNacimientoRegistro',NULL, '$correoRegistro', '$passwordRegistro', $tipoUsuarioRegistro)");
  }
  if ($query)
  {
    echo "<br><h2 class='center-align'>¡Te has registrado con éxito! <br> Ahora puedes iniciar sesión</h2>";
  }
  else
  {
    printf("Errormessage: %s\n", mysqli_error($conexion));
    echo "Ha ocurrido un error): query";
  }
  Desconectar($conexion);
?>
