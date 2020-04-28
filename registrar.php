<?php
  include 'config.php';
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar, codificado en utf-8
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

  $query = mysqli_query($conexion,"SELECT * FROM Usuarios WHERE correo = '$correoRegistro'");

  $numerofilas = mysqli_num_rows($query);
  if ($numerofilas == 1 )
  {
    echo json_encode(array('mensaje' => "Lo sentimos, usted ya se encuentra registrado", 'pagina' => "registro",'alerta' => "error"));
  }
  else
  {
    if ($rutaFotoPerfilRegistro != '')
    {
      $segundos = time(); // Devuelve el momento actual medido como el número de segundos desde la época unix (1 de Enero de 1970 00:00:00 GMT).
      $directorio = 'usuario/images/';
      $nombreArchivo = $_FILES['rutaFotoPerfilRegistro']['name'];
      $extension = ".".pathinfo($nombreArchivo, PATHINFO_EXTENSION);
      $nombreArchivo = pathinfo($nombreArchivo, PATHINFO_FILENAME);
      $nombreArchivo = $nombreArchivo.$segundos.$extension;
      $directoriocompleto = $directorio.$nombreArchivo;
      if (move_uploaded_file($_FILES['rutaFotoPerfilRegistro']["tmp_name"], $directoriocompleto)) //Sube la imágen, si se sube correctamente se inserta la información en la base de datos.
      {
        $query = mysqli_query($conexion,"INSERT INTO Usuarios (nombreUsuario, apPaternoUsuario, apMaternoUsuario, escolaridad, direccion, nacimiento, foto, correo, password, tipoUsuario) VALUES('$nombreRegistro', '$apPaternoRegistro', '$apMaternoRegistro', '$escolaridadRegistro', '$direccionRegistro', '$fechaNacimientoRegistro','$nombreArchivo', '$correoRegistro', '$passwordRegistro', $tipoUsuarioRegistro)");
      }
      else
      {
        echo json_encode(array('mensaje' => "Error, no se pudo subir imagen, intente de nuevo", 'pagina' => "registro",'alerta' => "error"));
      }
    }
    else
    {
      $query = mysqli_query($conexion,"INSERT INTO Usuarios (nombreUsuario, apPaternoUsuario, apMaternoUsuario, escolaridad, direccion, nacimiento, foto, correo, password, tipoUsuario) VALUES('$nombreRegistro', '$apPaternoRegistro', '$apMaternoRegistro', '$escolaridadRegistro', '$direccionRegistro', '$fechaNacimientoRegistro',NULL, '$correoRegistro', '$passwordRegistro', $tipoUsuarioRegistro)");
    }
    if ($query)
    {
      echo json_encode(array('mensaje' => "¡Te has registrado con éxito!", 'pagina' => "index",'alerta' => "success"));
    }
    else
    {
      echo json_encode(array('mensaje' => "Error, no se pudo procesar su información, intente de nuevo", 'pagina' => "registro",'alerta' => "error"));
    }
  }

  Desconectar($conexion);
?>
