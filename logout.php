<?php
    include_once 'config.php';
    $conexion = Conectar();
    $token =  $_SESSION['token'];
    $strQuery = "DELETE FROM Sesiones WHERE token = '$token'";
  	$query = mysqli_query($conexion,$strQuery);
    $_SESSION = array();
    session_destroy();
    header("location: index.php");
?>
