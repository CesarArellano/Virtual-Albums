<?php
  include_once '../config.php'; // Incluye archivo de configuración de la BD
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $conexion = Conectar(); // Función para conectar a la BD.
  //Guardamos todas las variables que nos llegaron por medio de POST
  $idUsuario = $_POST['idUsuario'];
  $nombreRegistro = $_POST['nombreRegistro'];
  $apPaternoRegistro = $_POST['apPaternoRegistro'];
  $apMaternoRegistro = $_POST['apMaternoRegistro'];
  $passwordRegistro = $_POST['passwordRegistro'];
  $rutaFotoPerfilRegistro = $_POST['rutaFotoPerfilRegistro'];
  $escolaridadRegistro = $_POST['escolaridadRegistro'];
  $direccionRegistro = $_POST['direccionRegistro'];
  $fechaNacimientoRegistro = $_POST['fechaNacimientoRegistro'];
  if($_SESSION['tipoUsuario'] == 1) // Si es admin
    $directorio = "../administrador/images/perfil/";
  else
    $directorio = "images/perfil/"; //Definimos el directorio para el usuario donde se van a guardar las imágenes

  if ($rutaFotoPerfilRegistro != '') // Si quiere subir foto de perfil
  {
    $segundos = time(); // Devuelve el momento actual medido como el número de segundos desde la época unix (1 de Enero de 1970 00:00:00 GMT).
    $nombreArchivo = $_FILES["rutaFotoPerfilRegistro"]["name"]; //Extraemos el nombre completo del archivo subido
    $extension = ".".pathinfo($nombreArchivo, PATHINFO_EXTENSION); //Extraemos la extensión del archivo subido, le agregamos el punto
    $nombreArchivo = pathinfo($nombreArchivo, PATHINFO_FILENAME); //Extraemos el nombre del archivo subido
    $nombreArchivo = str_replace(' ', '-', $nombreArchivo); // Reemplaza los espacios del nombre de la imagen por guiones
    $nombreArchivo = $nombreArchivo.$segundos.$extension; //Añadimos el tiempo actual en segundos al nombre del archivo y su extension (para evitar repetición)
    $directoriocompleto = $directorio.$nombreArchivo; // Directorio a guardar las fotos de perfil.
    if (move_uploaded_file($_FILES['rutaFotoPerfilRegistro']["tmp_name"], $directoriocompleto)) //Sube la imágen, si se sube correctamente ejecuta el query con foto de perfil, si no, lo ejecuta sin foto de perfil
    {
      //Actualiza el registro en la base de datos (con foto)
      $query = mysqli_query($conexion,"UPDATE Usuarios SET nombreUsuario = '$nombreRegistro', apPaternoUsuario = '$apPaternoRegistro', apMaternoUsuario = '$apMaternoRegistro', escolaridad = '$escolaridadRegistro', direccion = '$direccionRegistro', nacimiento = '$fechaNacimientoRegistro',foto = '$nombreArchivo', password = '$passwordRegistro' WHERE idUsuario = $idUsuario");
    }
    else
    {
      echo json_encode(array('mensaje' => "Error, no se pudo subir imagen, intente de nuevo", 'pagina' => "modificar",'alerta' => "error"));
    }
  }
  else // Si no quiere subir foto de perfil
  {
    //Actualiza el registro en la base de datos (sin foto)
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
  mysqli_free_result($query); // Libera memoria del query
  Desconectar($conexion);
?>
