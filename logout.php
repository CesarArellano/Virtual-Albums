<?php
    include_once 'config.php'; // Se incluye el archivo de conexón a la BD
    $conexion = Conectar(); // Se conecta  a la base de datos.
    $_SESSION = array(); // Crear un arreglo con todas las variables session activas.
    session_destroy(); // Destruye las variables de sesión.
    Desconectar($conexion); // Desconecta de la BD
    header("location: index.php"); // Redirige a index.php
?>
