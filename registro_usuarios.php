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

    <title>Virtual Albums | Registro</title>
  </head>
  <body>
    <main>
      <center>
        <br>
        <img class="responsive-img" src="images/icon.png" width="150px" height="150px" alt=""/>
      </center>
      <h1 class="center white-text">Virtual Albums</h1>
      <div class="container">
        <div class="row">
          <center>
            <div id="respuesta" class="flow-text white-text valign center-block"></div>
          </center>
          <div class="card hoverable col s12 opacidad">
            <form method="POST" enctype="multipart/form-data" id="form-users">
              <div class="card-content">
                <span class="card-title center-align">Registro</span>
                <div class="row">
                  <div class="input-field col l4 s12">
                    <i class="material-icons prefix">account_circle</i>
                    <input id="nombreRegistro" name="nombreRegistro" type="text" class="validate" pattern="[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,80}"  maxlength="80" required>
                    <label for="nombreRegistro">Ingresa tu(s) nombre(s)</label>
                  </div>
                  <div class="input-field col l4 s12">
                    <input id="apPaternoRegistro" name="apPaternoRegistro" type="text" class="validate" pattern="[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,80}" maxlength="80" required>
                    <label for="apPaternoRegistro">Ingresa tu apellido paterno</label>
                  </div>
                  <div class="input-field col l4 s12">
                    <input id="apMaternoRegistro" name="apMaternoRegistro" type="text" class="validate" pattern="[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,80}" maxlength="80" required>
                    <label for="apMaternoRegistro">Ingresa tu apellido materno</label>
                  </div>
                  <div class="input-field col l4 s12">
                    <i class="material-icons prefix">school</i>
                    <select id="escolaridadRegistro" name="escolaridadRegistro" required>
                      <option value="" disabled selected>Selecciona la sección</option>
                      <option value="Sin estudios">Sin estudios</option>
                      <option value="Básica">Básica</option>
                      <option value="Media">Media</option>
                      <option value="Media Superior">Media Superior</option>
                      <option value="Superior">Superior</option>
                    </select>
                    <label for="escolaridadRegistro">Escolaridad</label>
                  </div>
                  <div class="input-field col l8 s12">
                    <i class="material-icons prefix">home</i>
                    <input id="direccionRegistro" name="direccionRegistro" type="text" class="validate" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚ., ]{2,300}" maxlength="300" required>
                    <label for="direccionRegistro">Ingresa tu dirección</label>
                  </div>
                  <div class="input-field col l6 s12">
                    <i class="material-icons prefix">insert_invitation</i>
                    <input id=fechaNacimientoRegistro name="fechaNacimientoRegistro" type="text" class="datepicker" data-date-format="yyyy-mm-dd" required>
                    <label for="fechaNacimientoRegistro">Ingresa tu fecha de nacimiento</label>
                  </div>
                  <div class="file-field input-field col l6 s12">
                    <div class="btn waves-effect waves-light light-blue accent-4">
                      <i class="material-icons prefix" id="Icono_Imagen">image</i>
                      <span id="texto-boton-imagen">Foto perfil</span>
                      <input type="file" name="rutaFotoPerfilRegistro" accept="image/*">
                    </div>
                    <div class="file-path-wrapper">
                      <input class="file-path validate" type="text" name="rutaFotoPerfilRegistro" placeholder="Opcional">
                    </div>
                  </div>
                  <div class="input-field col s12">
                    <i class="material-icons prefix">email</i>
                    <input id="correoRegistro" name="correoRegistro" type="text" class="validate" pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}" maxlength="150" required>
                    <label for="correoRegistro" data-error="Incorrecto" data-success="Correcto">Ingresa tu correo electrónico</label>
                  </div>

                  <div class="input-field col s12">
                    <i class="material-icons prefix">lock</i>
                    <input id="passwordRegistro" name="passwordRegistro" type="password" class="validate" minlength="8" maxlength="32" required>
                    <label for="passwordRegistro">Ingrese su contraseña</label>
                  </div>
                  <input type="hidden" id="tipoUsuarioRegistro" name="tipoUsuarioRegistro" value="2">
                </div>
              </div>
              <div class="card-action center-align">
                <button class="btn-flat grey-text waves-effect" type="reset" id="reset"> Limpiar Campos </button>
                <button class="btn waves-effect waves-light light-blue accent-4" type="submit" name="action" id="action">Registrar
                  <i class="material-icons right">send</i>
                </button>
              </div>
              <div class="center">
                <b><p id="validacion" class="center"></p></b>
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
            <h5>¿Ya tienes cuenta?</h5>
            <button class="btn waves-effect waves-light cyan darken-2" onclick="location.href='inicio.php'">¡Iniciar Sesión!
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
