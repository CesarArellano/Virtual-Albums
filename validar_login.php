<?php
  include_once "config.php";
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  $correo = $_POST["correo"];
  $password = $_POST["pass"];

	if(count(explode("'",$correo))>1 || count(explode('"',$correo))>1) // Se sanitiza las variable correo
  {
    //Se manda json de error.
		echo json_encode(array('mensaje' => "Error con los datos proporcionados", 'tipoUsuario' => "index",'alerta' => "error"));
	}
	if(count(explode("'",$password))>1 || count(explode('"',$password))>1) // Se sanitiza las variable password
  {
    //Se manda json de error.
		echo json_encode(array('mensaje' => "Error con los datos proporcionados", 'tipoUsuario' => "index",'alerta' => "error"));
	}

	$conexion = Conectar();
	$strQuery = "SELECT idUsuario, tipoUsuario, nombreUsuario,foto FROM Usuarios WHERE correo = '$correo' AND password = '$password'";
	$query = mysqli_query($conexion,$strQuery);

  // La contraseña está mal o el usuario no existe
	if(($row = mysqli_fetch_assoc($query)) == NULL)
  {
		Desconectar($conexion); // Desconectamos de la BD.
    //Se manda json de error.
    echo json_encode(array('mensaje' => "Error, correo o contraseña incorrectos", 'tipoUsuario' => "index",'alerta' => "error"));
	}
	else // El usuario si existe
  {
    $idUsuario = $row['idUsuario'];
    $idTipoUsuario = $row['tipoUsuario'];
    mysqli_query($conexion, "DELETE FROM Sesiones WHERE idUsuario = $idUsuario");
    do
    {
			$token = sha1(microtime(true));
			$query = "SELECT token FROM Sesiones WHERE token = '$token'";
		}while((mysqli_num_rows(mysqli_query($conexion,$query))) > 0);

		$query = "INSERT INTO Sesiones (idUsuario,token,timestamp) VALUES ($idUsuario,'$token',now())";
		mysqli_query($conexion,$query);
    $_SESSION['idUsuario'] = $idUsuario;
    $_SESSION['tipoUsuario'] = $idTipoUsuario;
    $_SESSION['token'] = $token;
    $_SESSION['foto'] = $row['foto'];
		Desconectar($conexion);
		switch($idTipoUsuario)
    {
			case 1: // Administrador, se manda json para redigir y mostrar alerta determinada.
				echo json_encode(array('mensaje' => "¡Buen trabajo!", 'tipoUsuario' => "administrador",'alerta' => "success"));
        break;
			case 2: // Usuario, se manda json para redigir y mostrar alerta determinada.
				echo json_encode(array('mensaje' => "¡Buen trabajo!", 'tipoUsuario' => "usuario",'alerta' => "success"));
				break;
		}
  }
?>
