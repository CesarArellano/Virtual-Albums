<?php
  include_once '../config.php';
  $conexion = Conectar();
  $Salida = "";
  $query = "SELECT idAlbum,titulo,nombretema, u.idUsuario AS 'idPropietario', nombreUsuario, fechaAlbum, puntuacion FROM Usuarios u INNER JOIN Albumes a USING(idUsuario) INNER JOIN Fotos USING(idAlbum) INNER JOIN Temas t ON a.idTema = t.idTema  INNER JOIN PuntuacionesComentarios USING(idFoto)";
  if (isset($_POST['Consulta']))
  {
    $texto = mysqli_real_escape_string($conexion, $_POST['Consulta']);
    $query = "SELECT idAlbum,titulo,nombretema, u.idUsuario AS 'idPropietario', nombreUsuario, fechaAlbum, puntuacion FROM Usuarios u INNER JOIN Albumes a USING(idUsuario) INNER JOIN Fotos USING(idAlbum) INNER JOIN Temas t ON a.idTema = t.idTema  INNER JOIN PuntuacionesComentarios USING(idFoto) WHERE nombreUsuario LIKE '%".$texto."%' OR titulo LIKE '%".$texto."%' OR nombreTema LIKE '%".$texto."%'";
  }
  $consulta = mysqli_query($conexion,$query);
  $numerofilas = mysqli_num_rows($consulta);
  if ($numerofilas > 0)
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
                        $Salida.= "<tr>
                          <td>".$row['titulo']."</td>
                          <td>".$row['nombretema']."</td>
                          <td>".$row['idPropietario']."</td>
                          <td>".$row['nombreUsuario']."</td>
                          <td>".$row['fechaAlbum']."</td>
                          <td>".$row['puntuacion']."</td>
                          <td><a href='vermas.php?foto=".$row['idAlbum']."'>Ver más</a></td>
                        </tr>";
                    }
                    $Salida.= "</tbody></table>";
 }
 else
   $Salida.= "No se encontraron resultados ):";
 echo $Salida;
 mysqli_free_result($row);
 Desconectar($conexion);
?>
