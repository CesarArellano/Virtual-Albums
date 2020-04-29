<?php
  include '../config.php';
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $conexion = Conectar();
  $idUsuario = $_POST['idUsuario']; //Guardamos todas las variables que nos llegaron por medio de POST
  $nombreRegistro = $_POST['nombreRegistro'];
  $apPaternoRegistro = $_POST['apPaternoRegistro'];
  $apMaternoRegistro = $_POST['apMaternoRegistro'];
  $passwordRegistro = $_POST['passwordRegistro'];
  $rutaFotoPerfilRegistro = $_POST['rutaFotoPerfilRegistro'];
  $tipoUsuarioRegistro = $_POST['tipoUsuario'];

  if($tipoUsuarioRegistro == '1')
  {
    $directorio = "images/perfil/"; //Definimos el directorio para el usuario donde se van a guardar las imágenes
  }
  else
  {
    $escolaridadRegistro = $_POST['escolaridadRegistro'];
    $direccionRegistro = $_POST['direccionRegistro'];
    $fechaNacimientoRegistro = $_POST['fechaNacimientoRegistro'];
    $directorio = "../usuario/images/perfil/"; //Definimos el directorio para el usuario donde se van a guardar las imágenes
  }

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
      if($tipoUsuarioRegistro == '1')
        $query = mysqli_query($conexion,"UPDATE Usuarios SET nombreUsuario = '$nombreRegistro', apPaternoUsuario = '$apPaternoRegistro', apMaternoUsuario = '$apMaternoRegistro', foto = '$nombreArchivo', password = '$passwordRegistro' WHERE idUsuario = $idUsuario");
      else
        $query = mysqli_query($conexion,"UPDATE Usuarios SET nombreUsuario = '$nombreRegistro', apPaternoUsuario = '$apPaternoRegistro', apMaternoUsuario = '$apMaternoRegistro', escolaridad = '$escolaridadRegistro', direccion = '$direccionRegistro', nacimiento = '$fechaNacimientoRegistro',foto = '$nombreArchivo', password = '$passwordRegistro' WHERE idUsuario = $idUsuario");
    }
    else
    {
      echo json_encode(array('mensaje' => "Error, no se pudo subir imagen, intente de nuevo", 'pagina' => "modificar",'alerta' => "error"));
    }
  }
  else
  {
    if ($tipoUsuarioRegistro == '1')
      $query = mysqli_query($conexion,"UPDATE Usuarios SET nombreUsuario = '$nombreRegistro', apPaternoUsuario = '$apPaternoRegistro', apMaternoUsuario = '$apMaternoRegistro', password = '$passwordRegistro' WHERE idUsuario = $idUsuario");
    else
      $query = mysqli_query($conexion,"UPDATE Usuarios SET nombreUsuario = '$nombreRegistro', apPaternoUsuario = '$apPaternoRegistro', apMaternoUsuario = '$apMaternoRegistro', escolaridad = '$escolaridadRegistro', direccion = '$direccionRegistro', nacimiento = '$fechaNacimientoRegistro', password = '$passwordRegistro' WHERE idUsuario = $idUsuario");
  }
  if ($query)
  {
    echo json_encode(array('mensaje' => "¡Se ha actualizado con éxito la información!", 'pagina' => "index",'alerta' => "success"));
  }
  else
  {
    echo json_encode(array('mensaje' => "Error, no se pudo procesar su información, intente de nuevo", 'pagina' => "modificar",'alerta' => "error"));
  }
  Desconectar($conexion);
?>
