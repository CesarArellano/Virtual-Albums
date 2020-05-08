<?php
  include 'config.php';
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $conexion = Conectar();
  $nombreRegistro = $_POST['nombreRegistro']; //Guardamos todas las variables que nos llegaron por medio de POST
  $apPaternoRegistro = $_POST['apPaternoRegistro'];
  $apMaternoRegistro = $_POST['apMaternoRegistro'];
  $correoRegistro = $_POST['correoRegistro'];
  $passwordRegistro = $_POST['passwordRegistro'];
  $rutaFotoPerfilRegistro = $_POST['rutaFotoPerfilRegistro'];
  $tipoUsuarioRegistro = $_POST['tipoUsuarioRegistro'];
  $escolaridadRegistro = $_POST['escolaridadRegistro'];
  $direccionRegistro = $_POST['direccionRegistro'];
  $fechaNacimientoRegistro = $_POST['fechaNacimientoRegistro'];

  if($tipoUsuarioRegistro == 1)
  {
    $directorio = "administrador/images/perfil/"; //Definimos el directorio para el administrador donde se van a guardar las imágenes
  }
  if($tipoUsuarioRegistro == 2) // Datos a extraer solo usuario.
  {
    $directorio = "usuario/images/perfil/"; //Definimos el directorio para el usuario donde se van a guardar las imágenes
  }

  $query = mysqli_query($conexion,"SELECT * FROM Usuarios WHERE correo = '$correoRegistro'"); //Revisamos si el correo ya existe en la base de datos

  $numerofilas = mysqli_num_rows($query);
  if ($numerofilas == 1 ) //Si ya existe no permitimos el registro
  {
    echo json_encode(array('mensaje' => "Lo sentimos, usted ya se encuentra registrado", 'pagina' => "registro",'alerta' => "error"));
  }
  else
  {
    if ($rutaFotoPerfilRegistro != '')
    {
      $segundos = time(); // Devuelve el momento actual medido como el número de segundos desde la época unix (1 de Enero de 1970 00:00:00 GMT).
      $nombreArchivo = $_FILES['rutaFotoPerfilRegistro']['name']; //Extraemos el nombre completo del archivo subido
      $extension = ".".pathinfo($nombreArchivo, PATHINFO_EXTENSION); //Extraemos la extensión del archivo subido, le agregamos el punto
      $nombreArchivo = pathinfo($nombreArchivo, PATHINFO_FILENAME); //Extraemos el nombre del archivo subido
      $nombreArchivo = str_replace(' ', '-', $nombreArchivo);
      $nombreArchivo = $nombreArchivo.$segundos.$extension; //Añadimos el tiempo actual en segundos al nombre del archivo y su extension (para evitar repetición)
      $directoriocompleto = $directorio.$nombreArchivo;
      if (move_uploaded_file($_FILES['rutaFotoPerfilRegistro']["tmp_name"], $directoriocompleto)) //Sube la imágen, si se sube correctamente ejecuta el query con foto de perfil, si no, lo ejecuta sin foto de perfil
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
      $query = mysqli_query($conexion,"INSERT INTO Usuarios (nombreUsuario, apPaternoUsuario, apMaternoUsuario, escolaridad, direccion, nacimiento, correo, password, tipoUsuario) VALUES('$nombreRegistro', '$apPaternoRegistro', '$apMaternoRegistro', '$escolaridadRegistro', '$direccionRegistro', '$fechaNacimientoRegistro', '$correoRegistro', '$passwordRegistro', $tipoUsuarioRegistro)");
    }
    if ($query)
    {
      echo json_encode(array('mensaje' => "¡Te has registrado con éxito!", 'pagina' => "index",'alerta' => "success"));
    }
    else
    {
      echo json_encode(array('mensaje' => "Error, no se pudo procesar su información, intente de nuevo.", 'pagina' => "registro",'alerta' => "error"));
    }
  }
  Desconectar($conexion);
?>
