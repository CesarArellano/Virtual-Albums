<?php
  include_once "../config.php";
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $conexion = Conectar();
  $rutaFotoAlbum = $_POST['rutaSubirFoto'];
  $idAlbum = intval($_POST['idAlbum']);
  $directorio = "images/albumes/"; //Definimos el directorio para el usuario donde se van a guardar las imágenes
  $segundos = time(); // Devuelve el momento actual medido como el número de segundos desde la época unix (1 de Enero de 1970 00:00:00 GMT).
  $nombreArchivo = $_FILES["rutaSubirFoto"]["name"]; //Extraemos el nombre completo del archivo subido
  $extension = ".".pathinfo($nombreArchivo, PATHINFO_EXTENSION); //Extraemos la extensión del archivo subido, le agregamos el punto
  $nombreArchivo = pathinfo($nombreArchivo, PATHINFO_FILENAME); //Extraemos el nombre del archivo subido
  $nombreArchivo = str_replace(' ', '-', $nombreArchivo);
  $nombreArchivo = $nombreArchivo.$segundos.$extension; //Añadimos el tiempo actual en segundos al nombre del archivo y su extension (para evitar repetición)
  $directoriocompleto = $directorio.$nombreArchivo;
  if (move_uploaded_file($_FILES['rutaSubirFoto']["tmp_name"], $directoriocompleto)) //Sube la imágen, si se sube correctamente ejecuta el query con foto de perfil, si no, lo ejecuta sin foto de perfil
  {
      $subirFoto = mysqli_query($conexion,"INSERT INTO Fotos (idAlbum,fechaFoto,rutaFoto,autorizada) VALUES($idAlbum,CURDATE(),'$nombreArchivo',0)");
  }
  else
  {
    echo json_encode(array('mensaje' => "Error, no se pudo subir imagen, intente de nuevo", 'pagina' => "fotos",'alerta' => "error"));
  }
  if ($subirFoto)
  {
    echo json_encode(array('mensaje' => "¡Se ha subido la foto al álbum con éxito!", 'pagina' => "albumes",'alerta' => "success"));
  }
  else
  {
    echo json_encode(array('mensaje' => "Error, no se pudo procesar su foto, intente de nuevo", 'pagina' => "fotos",'alerta' => "error"));
  }
  Desconectar($conexion);
?>
