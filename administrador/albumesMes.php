<?php
  include_once "../config.php";
  $conexion = Conectar();
  $mes = $_POST['mesAlbum'];
  $anio = $_POST['anioAlbum'];
  //INICIO LÓGICA ÁLBUMES MÁS VISTOS EN UN MES
  $consultaAlbumesMejorPuntuados = mysqli_query($conexion, "SELECT idAlbum, titulo, tipoAlbum, fechaAlbum, COUNT(idVisita) FROM Albumes LEFT JOIN Visitas USING (idAlbum) WHERE MONTHNAME(fechaVisita) = '$mes' AND YEAR(fechaVisita) = $anio GROUP BY idAlbum ORDER BY COUNT(idVisita) DESC"); //Query que busca los álbumes mejor puntuados
  $numeroFilasAlbumesMejorPuntuados = mysqli_num_rows($consultaAlbumesMejorPuntuados);
  //Creamos la tabla y le ponemos el encabezado
  $contenidoAnalisis.=
  "<table class='responsive-table highlight centered' style='position:relative;top:-10px;'>
    <thead>
      <th>Título</th>
      <th>Fecha del álbum</th>
      <th>Privacidad del álbum</th>
      <th>Visitas en el mes</th>
    </thead>
    <tbody>";

  while ($row = mysqli_fetch_assoc($consultaAlbumesMejorPuntuados)) //Rellenamos la tabla con los datos que devolvió el query
  {
      $contenidoAnalisis.= "<tr>
      <td>".$row['titulo']."</td>
      <td>".$row['fechaAlbum']."</td>
      <td>".$row['tipoAlbum']."</td>
      <td>".$row['COUNT(idVisita)']."</td>
      <td><a href='verAlbumes.php?id=".$row['idAlbum']."'>Ver el álbum</a></td>
      </tr>";
  }

  $contenidoAnalisis.= "</tbody></table>"; //Cerramos la tabla

  if($numeroFilasAlbumesMejorPuntuados == 0) //Mensaje si no hay coincidencias
    $contenidoAnalisis.= "<p>No se encontraron álbumes</p>";
  echo $contenidoAnalisis;
  mysqli_free_result($consultaAlbumesMejorPuntuados);
  //FIN LÓGICA ÁLBUMES MÁS VISTOS EN UN MÉS
  Desconectar($conexion);
?>
