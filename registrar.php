<?php
  include 'config.php';
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $formatos  = array('.jpg', '.png', '.jpeg', '.gif', '.bmp', '.tiff', '.raw');
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
      $directorio = 'usuario/images/';
      $nombreArchivo = $_FILES['rutaFotoPerfilRegistro']['name'];
      $directoriocompleto = $directorio.$nombreArchivo;
      $ext = substr($nombreArchivo, strrpos($nombreArchivo, '.'));
      if (in_array($ext, $formatos))
      {
        if (move_uploaded_file($_FILES['rutaFotoPerfilRegistro']["tmp_name"], $directoriocompleto))
        {
          $query = mysqli_query($conexion,"INSERT INTO Usuarios (nombreUsuario, apPaternoUsuario, apMaternoUsuario, escolaridad, direccion, nacimiento, foto, correo, password, tipoUsuario) VALUES('$nombreRegistro', '$apPaternoRegistro', '$apMaternoRegistro', '$escolaridadRegistro', '$direccionRegistro', '$fechaNacimientoRegistro','$rutaFotoPerfilRegistro', '$correoRegistro', '$passwordRegistro', $tipoUsuarioRegistro)");
        }
        else
        {
          echo json_encode(array('mensaje' => "Error, no se pudo procesar imagen, intente de nuevo", 'pagina' => "registro",'alerta' => "error"));
        }
      }
      else
      {
        echo json_encode(array('mensaje' => "Error, foto no admitida. Archivos permitidos (.jpg .jpeg .png .gif .bmp .tiff .raw), intente de nuevo", 'pagina' => "registro",'alerta' => "error"));
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
