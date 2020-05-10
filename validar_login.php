<?php
  include_once "config.php";
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar, codificado en utf-8
  // Se obtienen datos de una forma por método POST
  $correo = $_POST["correo"];
  $password = $_POST["pass"];

	if(count(explode("'",$correo))>1 || count(explode('"',$correo))>1) // Se sanitiza la variable correo
  {    //Se manda json de error.
		echo json_encode(array('mensaje' => "Error con los datos proporcionados", 'tipoUsuario' => "index",'alerta' => "error"));
	}

	if(count(explode("'",$password))>1 || count(explode('"',$password))>1) // Se sanitiza la variable password
  {
    //Se manda json de error.
		echo json_encode(array('mensaje' => "Error con los datos proporcionados", 'tipoUsuario' => "index",'alerta' => "error"));
	}
	$conexion = Conectar(); //Función que conecta a la BD y abre la sesión.
	$strQuery = "SELECT idUsuario, tipoUsuario FROM Usuarios WHERE correo = '$correo' AND password = '$password'";
	$consulta = mysqli_query($conexion,$strQuery); // Se ejecuta el query

  // La contraseña está mal o el usuario no existe
	if(($row = mysqli_fetch_assoc($consulta)) == NULL)
  {
		Desconectar($conexion); // Desconectamos de la BD.
    //Se manda json de error.
    echo json_encode(array('mensaje' => "Error, correo o contraseña incorrectos", 'tipoUsuario' => "index",'alerta' => "error"));
	}
	else // El usuario si existe
  {
    $idUsuario = $row['idUsuario']; // Se obtiene el id del usuario
    $idTipoUsuario = $row['tipoUsuario']; // Se obtiene el tipo de usuario
    $_SESSION['tipoUsuario'] = $idTipoUsuario; // Se guarda el tipo de usuario en un variable de sesión esto para poder utilizarla durante la utilización del sistema.
    $_SESSION['idUsuario'] = $idUsuario; // Se guarda el id del usuario.
    mysqli_free_result($consulta); // Libera memoria de la consulta
		Desconectar($conexion); // Desconecta de la BD.
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
