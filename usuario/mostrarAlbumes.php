<?php
  include_once '../config.php';
  $conexion = Conectar();
  $consultaTituloAlbumes = mysqli_query($conexion,"SELECT idAlbum,titulo FROM Albumes  WHERE tipoAlbum = 'Público'");
  $numerofilas = mysqli_num_rows($consultaTituloAlbumes);
  $tituloAlbumes = "";
  if ($numerofilas > 0)
  {
    while ($row = mysqli_fetch_array($consultaTituloAlbumes))
    {
      $tituloAlbumes .= "<tr><td><a href='verAlbumes.php?id=".$row['idAlbum']."&tipo=1'>".$row['titulo']."</a></td></tr>";
    }
    $tituloAlbumes .=  "<tr><td><a href='#'>Hola</a></td></tr>";
    mysqli_free_result($consultaTituloAlbumes);
  }
  else
  {
    $tituloAlbumes .= "<tr>No hay resultados</tr>";
  }
  echo $tituloAlbumes;
  Desconectar($conexion);
?>
