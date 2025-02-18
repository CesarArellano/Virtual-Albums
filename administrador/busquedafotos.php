<?php
  include_once '../config.php';
  $conexion = Conectar();
  $Salida = "";
  // Busca fotos no autorizadas
  $query = "SELECT idUsuario, nombreUsuario, apPaternoUsuario, apMaternoUsuario, titulo, idFoto, rutaFoto, autorizada FROM Fotos INNER JOIN Albumes USING(idAlbum) INNER JOIN Usuarios USING(idUsuario) WHERE autorizada = 0";
  if (isset($_POST['Consulta']))
  {
    $texto = mysqli_real_escape_string($conexion, $_POST['Consulta']); // Sanitiza varible de búsqueda
    $query = "SELECT idUsuario, nombreUsuario, apPaternoUsuario, apMaternoUsuario, titulo, idFoto, rutaFoto, autorizada FROM Fotos INNER JOIN Albumes USING(idAlbum) INNER JOIN Usuarios USING(idUsuario) WHERE autorizada = 0 GROUP BY idFoto HAVING nombreUsuario LIKE '%".$texto."%' OR apPaternoUsuario LIKE '%".$texto."%' OR apMaternoUsuario LIKE '%".$texto."%'";
  }
  $consulta = mysqli_query($conexion,$query); //Ejecuta el query
  $numerofilas = mysqli_num_rows($consulta);
  if ($numerofilas > 0) // Si encontró resultados, muestra fotos por autorizar en una tabla
  {
    $Salida.= "<table class='responsive-table centered'>
      <thead>
       <th>ID Usuario</th>
       <th>Nombre</th>
       <th>Apellido Paterno</th>
       <th>Apellido Materno</th>
       <th>Título Album</th>
       <th>ID Foto</th>
       <th>Vista previa</th>
       <th>Autorizar</th>
      </thead>
      <tbody>";
      while ($row = mysqli_fetch_assoc($consulta))
      {
        $Salida.= "<tr>
          <td>".$row['idUsuario']."</td>
          <td>".$row['nombreUsuario']."</td>
          <td>".$row['apPaternoUsuario']."</td>
          <td>".$row['apMaternoUsuario']."</td>
          <td>".$row['titulo']."</td>
          <td>".$row['idFoto']."</td>
          <td><img class='fotos' width='100' src='../usuario/images/albumes/".$row['rutaFoto']."'/></td>
          <td><a href='autorizar.php?foto=".$row['idFoto']."'>Autorizar</a></td>
        </tr>";
      }
      $Salida.= "</tbody></table>";
 }
 else // Si no hay fotos por fotos
   $Salida.= "<h5>No se encontraron resultados</h5>";
 echo $Salida;
 mysqli_free_result($consulta);
 Desconectar($conexion);
?>
