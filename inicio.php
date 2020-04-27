<?php
  @session_start();
  if (isset($_SESSION['tipoUsuario']) AND $_SESSION['tipoUsuario'] == 1)
  {
    header('location: administrador/inicio.php');
  }
  if (isset($_SESSION['tipoUsuario']) AND $_SESSION['tipoUsuario'] == 2)
  {
    header('location: usuario/inicio.php');
  }
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="shortcut icon" href="images/icon.png">
    <!-- Materialize CSS -->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.3.2/sweetalert2.css">
    <link rel="stylesheet" href="css/styles.css">
    <!-- Archivos JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.3.2/sweetalert2.js" charset="utf-8"></script>
    <script src="js/script.js"></script>

    <title>Virtual Albums | Inicio de Sesión</title>
  </head>
  <body>
    <main>
      <center>
        <br>
        <img class="responsive-img" src="images/icon.png" width="150px" height="150px" alt=""/>
      </center>
      <h1 class="center white-text">Virtual Albums</h1>
      <div class="container">
        <div class="valign-wrapper row login-box">
          <div class="card hoverable col s12 opacidad" id="formulario">
            <form method="POST" id="form-login">
              <div class="card-content">
                <span class="card-title center-align">Inicio de sesión</span>
                <div class="row">
                  <div class="input-field col s12">
                    <i class="material-icons prefix">email</i>
                    <input name="correo" type="email" class="validate" pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}">
                    <label for="email" data-error="Incorrecto" data-success="Correcto">Ingresa tu correo electrónico</label>
                  </div>

                  <div class="input-field col s12">
                    <i class="material-icons prefix">lock</i>
                    <input name="pass" type="password" class="validate" maxlength="32" required>
                    <label for="password">Ingrese su contraseña</label>
                  </div>
                </div>
              </div>
              <div class="card-action center-align">
                <button class="btn-flat grey-text waves-effect" type="reset" id="reset"> Limpiar Campos </button>
                <button class="btn waves-effect waves-light light-blue accent-4" type="submit" name="action" id="action">Entrar
                  <i class="material-icons right">send</i>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
    <footer class="page-footer black opacidad">
      <div class="container">
        <div class="row">
          <div class="col l4 m4 s12 center">
            <h5 class="white-text">Desarrollado por:</h5>
            <ul>
                <li><a class="grey-text text-lighten-3">César Mauricio Arellano Velásquez</a></li>
                <li><a class="grey-text text-lighten-3">Raúl González Portillo</a></li>
            </ul>
          </div>
          <center>
            <div class="col l4 m4 s12">
              <img class="responsive-img" src="images/LogoRAM.png" width="210px" height="200px" />
            </div>
          </center>
          <div class="col l4 m4 s12 center">
            <h5>¿No tienes cuenta?</h5>
            <button class="btn waves-effect waves-light cyan darken-2" onclick="location.href='registro_usuarios.php'">¡Regístrate!
              <i class="material-icons right">send</i>
          </button>
          </div>
        </div>
      </div>
      <div class="footer-copyright">
        <div class="container">
          © 2020 Virtual Albums
        </div>
      </div>
    </footer>
  </body>
</html>
