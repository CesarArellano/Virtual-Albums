<?php
  function Conectar() // Función para conectar a la BD.
  {
    $cfgServer['host']	= 'localhost';	// MySQL hostname
    $cfgServer['user']	= 'ic19cav';	// MySQL user
    $cfgServer['password']	= '208192';		// MySQL password
    $cfgServer['dbname']	= 'ic19cav';	// MySQL database name

    session_start(); //Abre sessión, mantiene variables SESSION activas
    $conexion = mysqli_connect($cfgServer['host'], $cfgServer['user'], $cfgServer['password']) or die('No se pudo conectar: ' . mysqli_error($conexion));
  	mysqli_select_db($conexion, $cfgServer['dbname']) or die("No se pudo seleccionar la base de datos");
  	mysqli_set_charset($conexion,"utf8"); // Permite la codificación en utf8.
    return $conexion;
  }
  function Desconectar($conexion) // Función para desconectar de la BD.
  {
    mysqli_close($conexion);
  }
?>
