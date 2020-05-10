<?php
  include_once '../config.php';
  $conexion = Conectar();
  $Salida = "";
  //Devuelve todos los álbumes que hay en el sistema
  $query = "SELECT idAlbum,titulo,nombretema, u.idUsuario AS 'idPropietario', nombreUsuario, fechaAlbum, AVG(puntuacion) AS 'promedioPuntuacion' FROM Albumes a LEFT JOIN Usuarios u USING(idUsuario) LEFT JOIN Fotos USING(idAlbum) LEFT JOIN Temas t ON a.idTema = t.idTema  LEFT JOIN PuntuacionesComentarios USING(idFoto) GROUP BY idAlbum";
  if (isset($_POST['Consulta'])) // Si se hizo una busqueda en el input text
  {
    $texto = mysqli_real_escape_string($conexion, $_POST['Consulta']); // Sanitiza variable de texto

    $query = "SELECT idAlbum,titulo,nombretema, u.idUsuario AS 'idPropietario', nombreUsuario, fechaAlbum, AVG(puntuacion) AS 'promedioPuntuacion' FROM Albumes a LEFT JOIN Usuarios u USING(idUsuario) LEFT JOIN Fotos USING(idAlbum) LEFT JOIN Temas t ON a.idTema = t.idTema  LEFT JOIN PuntuacionesComentarios USING(idFoto) WHERE nombreUsuario LIKE '%".$texto."%' OR titulo LIKE '%".$texto."%' OR nombreTema LIKE '%".$texto."%' GROUP BY idAlbum";
  }
  $consulta = mysqli_query($conexion,$query);
  $numerofilas = mysqli_num_rows($consulta);
  if ($numerofilas > 0) // Si encontró resultados, muestra álbumes en una tabla
  {
    $Salida.= "<table class='responsive-table centered'>
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
                    while ($row = mysqli_fetch_assoc($consulta))
                    {
                      if($row['promedioPuntuacion'] == NULL) // Si no hay puntuación
                      {
                        $puntuacion = 0; // Setear en 0 la puntuación
                      }
                      else
                      {
                        $puntuacion = $row['promedioPuntuacion'];
                      }
                        $Salida.= "<tr>
                          <td>".$row['titulo']."</td>
                          <td>".$row['nombretema']."</td>
                          <td>".$row['idPropietario']."</td>
                          <td>".$row['nombreUsuario']."</td>
                          <td>".$row['fechaAlbum']."</td>
                          <td>".$puntuacion."</td>
                          <td><a href='verAlbumes.php?id=".$row['idAlbum']."'>Ver más</a></td>
                        </tr>";
                    }
                    $Salida.= "</tbody></table>";
 }
 else // Si no hay álbumes con el texto que escribió el admin
   $Salida.= "<h5>No se encontraron resultados</h5>";
 echo $Salida;
 mysqli_free_result($consulta);
 Desconectar($conexion);
?>
