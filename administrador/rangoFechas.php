<?php
  include_once "../config.php";
  $conexion = Conectar();
  $fecha1 = $_POST['fecha1']; // Obtiene fecha 1 por método POST
  $fecha2 = $_POST['fecha2']; // Obtiene fecha 2 por método POST

  $consultaAlbumes = mysqli_query($conexion, "SELECT idAlbum,titulo,nombretema, u.idUsuario AS 'idPropietario', nombreUsuario, fechaAlbum, AVG(puntuacion) AS 'promedioPuntuacion' FROM Albumes a LEFT JOIN Usuarios u USING(idUsuario) LEFT JOIN Fotos USING(idAlbum) LEFT JOIN Temas t ON a.idTema = t.idTema LEFT JOIN PuntuacionesComentarios USING(idFoto) WHERE fechaAlbum BETWEEN '$fecha1' AND '$fecha2' GROUP BY idAlbum"); //Query que busca los álbumes mejor puntuados
  $numeroFilasEncontradas = mysqli_num_rows($consultaAlbumes);
  //Creamos la tabla y le ponemos el encabezado
  $contenidoBusqueda.=
  "<table class='responsive-table centered'>
    <thead>
     <th>Título Album</th>
     <th>Tema</th>
     <th>ID Propietario</th>
     <th>Propietario</th>
     <th>Fecha de publicación</th>
     <th>Puntuación</th>
     <th>Información</th>
    </thead>
    <tbody>";
    while ($row = mysqli_fetch_assoc($consultaAlbumes)) // Rellena tabla en dado caso que hayan resultados
    {
      if($row['promedioPuntuacion'] == NULL)
      {
        $puntuacion = 0;
      }
      else
      {
        $puntuacion = $row['promedioPuntuacion'];
      }
        $contenidoBusqueda.= "<tr>
          <td>".$row['titulo']."</td>
          <td>".$row['nombretema']."</td>
          <td>".$row['idPropietario']."</td>
          <td>".$row['nombreUsuario']."</td>
          <td>".$row['fechaAlbum']."</td>
          <td>".$puntuacion."</td>
          <td><a href='verAlbumes.php?id=".$row['idAlbum']."'>Ver más</a></td>
        </tr>";
    }
    $contenidoBusqueda.= "</tbody></table>";

  $contenidoBusqueda.= "</tbody></table>"; //Cerramos la tabla

  if($numeroFilasEncontradas == 0) //Mensaje si no hay coincidencias
    $contenidoBusqueda.= "<p>No se encontraron álbumes</p>";
  echo $contenidoBusqueda;
  mysqli_free_result($consultaAlbumes);
  //FIN LÓGICA ÁLBUMES MÁS VISTOS EN UN MÉS
  Desconectar($conexion);
?>
