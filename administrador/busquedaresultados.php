<?php
  include_once '../config.php';
  $conexion = Conectar();
  $Salida = "";
  $query = "SELECT idUsuario, nombreUsuario, apPaternoUsuario, apMaternoUsuario, escolaridad, direccion, nacimiento, correo, tipoUsuario, COUNT(idAlbum) AS 'CuantosAlbumes' FROM Usuarios LEFT JOIN Albumes USING(idUsuario) GROUP BY idUsuario";
  if (isset($_POST['Consulta']))
  {
    $texto = mysqli_real_escape_string($conexion, $_POST['Consulta']);
    $query = "SELECT idUsuario, nombreUsuario, apPaternoUsuario, apMaternoUsuario, escolaridad, direccion, nacimiento, correo, tipoUsuario, COUNT(idAlbum) AS 'CuantosAlbumes' FROM Usuarios LEFT JOIN Albumes USING(idUsuario) WHERE NombreUsuario LIKE '%".$texto."%' OR tipoUsuario LIKE '%".$texto."%' GROUP BY idUsuario";
  }
  $consulta = mysqli_query($conexion,$query);
  $numerofilas = mysqli_num_rows($consulta);
  if ($numerofilas > 0)
  {
    $Salida.= "<table class='responsive-table highlight centered'>
                    <thead>
                     <th>Nombre</th>
                     <th>Apellido Paterno</th>
                     <th>Apellido Materno</th>
                     <th>Escolaridad</th>
                     <th>Direccion</th>
                     <th>Nacimiento</th>
                     <th>Correo</th>
                     <th>Tipo de usuario</th>
                     <th>No. Albumes</th>
                     <th>Álbumes</th>
                     <th>Modificar</th>
                    </thead>
                    <tbody>";
                    while ($row = mysqli_fetch_assoc($consulta))
                    {
                        $Salida.= "<tr>
                          <td>".$row['nombreUsuario']."</td>
                          <td>".$row['apPaternoUsuario']."</td>
                          <td>".$row['apMaternoUsuario']."</td>
                          <td>".$row['escolaridad']."</td>
                          <td>".$row['direccion']."</td>
                          <td>".$row['nacimiento']."</td>
                          <td>".$row['correo']."</td>
                          <td>".$row['tipoUsuario']."</td>
                          <td>".$row['CuantosAlbumes']."</td>
                          <td><a href='albumes.php?id=".$row['idUsuario']."'target='_blank'>Ver más</a></td>
                          <td><a href='modificar.php?id=".$row['idUsuario']."'target='_blank'>Modificar información</a></td></tr>";
                    }
                    $Salida.= "</tbody></table>";
 }
 else
   $Salida.= "<h5>No se encontraron resultados</h5>";
 echo $Salida;
 mysqli_free_result($consulta);
 Desconectar($conexion);
?>
