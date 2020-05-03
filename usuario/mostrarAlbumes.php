<?php
  include_once '../config.php';
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $conexion = Conectar();
  $tituloAlbumes = array();
  $query = "SELECT idAlbum,titulo FROM Albumes  WHERE tipoAlbum = 'Público'";
  if (isset($_POST['consulta']))
  {
    $texto = mysqli_real_escape_string($conexion, $_POST['consulta']);
    $query = "SELECT idAlbum,titulo FROM Albumes WHERE tipoAlbum = 'Público' GROUP BY idAlbum HAVING titulo LIKE '%".$texto."%' ";
  }
  $consulta = mysqli_query($conexion,$query);
  $numerofilas = mysqli_num_rows($consulta);
  if ($numerofilas > 0)
  {
    while ($row = mysqli_fetch_array($consulta))
    {
      $tituloAlbumes[$row['titulo']] = $row['idAlbum'];
    }
  }
  echo json_encode($tituloAlbumes);
  mysqli_free_result($consulta);
  Desconectar($conexion);
?>
