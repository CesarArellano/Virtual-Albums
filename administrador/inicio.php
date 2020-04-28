<?php
  session_start();
	if (!isset($_SESSION['token']))
		header('location: ../inicio.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Administrador</title>
	<meta charset="utf-8">
  <link rel="shortcut icon" href="../images/icon.png">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="../css/styles_administrador.css"  media="screen,projection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="../js/materialize.min.js"></script>
    <script type="text/javascript" src="../js/script.js"></script>
</head>
<body>
<main>
	<nav class="blue darken-3 ">
		<div class="nav-wrapper">
			<a class="brand-logo center">Inicio</a>
			<img src="../images/icon.png" class="brand-logo right" height="50px" id="nav-logo">
			<a href="#" id="activar" data-activates="Panel" class="button-collapse"><i class="material-icons">menu</i></a>
		</div>
	</nav>
	<div class="title">
	  	<center>
	  		<h2><b>Bienvenido al sistema</b></h2>
	  	</center>
	</div>
	<div class="container">
		<div class="row">
		  	<div class="col s12">
		  		<div class="card hoverable z-depth-2">
		  			<div class="card-content">
						<span class="card-title center"><h3><b>Instructivo</b></h3></span>
						<div class="flow-text">
							<ul class="collection with-header">
						        <li class="collection-header">
						        	<h6 align="justify"><b>Dentro de este apartado te ofrecemos una breve explicación de lo que puedes hacer en cada uno de los apartados del sistema.</b></h6>
						        </li>
						        <li class="collection-item">
						        	<h6 align="justify"><b>Administración: </b>Para el módulo del administrador, el sistema podrá
                          desplegar la información correspondiente a todos los
                          usuarios registrados y sus datos personales, así como el
                          tipo de usuario. Se deberá indicar también el número de
                          álbumes que tiene cada usuario, el número de fotos en
                          cada álbum y las fotos en las que haya realizado
                          comentarios. Además, se podrá hacer una búsqueda por
                          nombre de usuario o por tipo de usuario. También se
                          podrá modificar la información personal de los usuarios
                          registrados. A su vez, el módulo de administración
                          permitirá subir fotografías al sitio, usando diferentes
                          criterios para su clasificación. Una vez que una nueva
                          fotografía se haya subido y que ésta haya sido autorizada
                          por el administrador, se deberá mandar un mensaje a los
                          usuarios (que tengan privilegios para ello), avisándoles
                          que la nueva fotografía ha sido publicada en el sistema.
                    </h6>
						        </li>
						        <li class="collection-item">
						        	<h6 align="justify"><b>Registro de usuario:</b> Registrar administradores</h6>
						        </li>
						        <li class="collection-item">
						        	<h6 align="justify"><b>Búsqueda: </b>Para el módulo de búsqueda es necesario contar con un
                        mecanismo que permita encontrar álbumes con base en
                        diferentes criterios:
                        • Nombre del titular del álbum
                        • Título del álbum
                        • Tema del álbum
                        • Un rango de fechas
                        Para la búsqueda se podrá usar cualquiera de los criterios
                        anteriores por separado o una mezcla de cualquiera de
                        los mismos.
                        Dicho resultado mostrará el nombre del álbum, el dueño
                        del mismo, la fecha de publicación y la puntuación (que
                        podrá ir de una a cinco estrellas). Al dar clic en el álbum,
                        se desplegará una lista en donde se podrán ver las vistas
                        preliminares de las fotos que pertenecen a dicho álbum.
                        Así mismo, se podrán ver también los comentarios y la
                        fecha de éstos, escritos por diferentes usuarios de lasfotos de dicho álbum.
                      </h6>
                    </li>
						        <li class="collection-item">
						        	<h6 align="justify"><b>Visualización: </b>Aprobar álbumes.</h6>
						        </li>
						        <li class="collection-item">
						         	<h6 align="justify"><b>Análisis de información: </b>El módulo de análisis permitirá ver los álbumes mejor
                        calificados, así como los álbumes más visitados en
                        general y los álbumes más visitados en un mes en
                        particular. También tendrá la capacidad de desplegar el
                        nombre de los álbumes con más fotografías y las
                        fotografías que más comentarios tengan.
                      </h6>
						        </li>
					    	</ul>
						</div>
		  			</div>
		  		</div>
		  	</div>
		</div>
	</div>
	<ul class="side-nav fixed" id="Panel">
		<li>
			<div class="user-view">
				<div class="background">
			    	<img src="../images/background.png" class="responsive-img">
				</div>
				<a><img  src="../images/avatar.png" width="100px" height="100px"></a>
				<a><span class="white-text name"><font size=3>Usuario: <?php echo $_SESSION['User'] ?></font></span></a>
				<a><span class="white-text email"><font size=3>Tipo de cuenta: Administrador</font></span></a>
			</div>
		</li>
		<li><a href="#"><i class="material-icons">home</i>Inicio</a></li>
    <li><a href="#"><i class="material-icons">create</i>Administración</a></li>
		<li><a href="#"><i class="material-icons">person_add</i>Registro de usuario</a></li>
		<li><a href="#"><i class="material-icons">pageview</i>Búsqueda</a></li>
		<li><a href="#"><i class="material-icons">photo</i>Visualización</a></li>
		<li><a href="#"><i class="material-icons">insert_chart</i>Análisis de información</a></li>
		<li><a href="../logout.php"><i class="material-icons">exit_to_app</i>Salir del sistema</a></li>
		<li><div class="divider"></div></li>
		<center>
			<p><i class="material-icons">settings</i></p><p>ADMINISTRADOR</p>
		</center>
 	</ul>
</main>
</body>
</html>
