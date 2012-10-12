<?php 

//index para admin

	session_start();
	require_once('music.php');
	error_reporting(E_ERROR);

//status 0 -> libre, 1 -> voto, 2-> artista, 3 -> busqueda
$status = 0;
$like = '';
$artista = '';
$search = '';

//envio datos
if (isset($_GET['like']) || isset($_GET['artista']) || isset($_GET['search']) ){
	
	if(isset($_GET['like'])){
		$like = $_GET['like'];
		$status = 1;
	}
	if(isset($_GET['artista'])){
		$artista = $_GET['artista'];
		$status = 2;
	}
	if(isset($_GET['search']) && $_GET['search'] != '' ){
		$search = $_GET['search'];
		$status = 3;
	}
}


//cierra seccion
if($_GET['logout'] == true){
	logout();
}

//datos logueo para admin
if(isset($_POST['password']) && isset($_POST['admin']) ){

	loguear($_POST['admin'], $_POST['password']);
}

//solo para admin, pregunta password
if(!isset($_SESSION['admin'])){
	loginForm();
}else if(admin()){
	//si esta logueado
?>

<!DOCTYPE html>
<html>

<head>
	<title>Laquesigue Admin</title>
	<meta charset="utf-8" />
	<meta id="extViewportMeta" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	
	<LINK rel="stylesheet" href="css/style.css" TYPE="text/css" MEDIA=screen>	
	<link rel="stylesheet" href="css/jquery.mobile-1.0rc2.min.css" />
	<link rel="stylesheet" href="css/main.css" />

	<script type="text/javascript" src="js/jquery-1.6.4.min.js"></script>
	<script type="text/javascript" src="js/jquery.mobile-1.0rc2.min.js"></script>
	
	<!-- notificaciones -->
	<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
	<script type="text/javascript" src="js/noty/layouts/topCenter.js"></script>
	<script type="text/javascript" src="js/noty/themes/default.js"></script>

	<script type="text/javascript" src="js/appAdmin.js"></script>
	

</head>

<body>

<?php

if( !isset($_SESSION['bienvenida']) ){
	echo '<SCRIPT TYPE="text/javascript">notifica(\'Bienvenido '.$_SESSION['usuario'].'\');</SCRIPT>';
	echo '<SCRIPT TYPE="text/javascript">notificaW(\'Recuerda que esta session<br/>es como Admin.\');</SCRIPT>';
	$_SESSION['bienvenida'] = true;
}

?>

<form action="index.php" method="get">

	<div data-role="page" class="page right" id="home">
		<div class="app">
			<!-- boton del menu -->
			<div class="menuicon" >
				<!-- id -> 1 activo, id -> 0 no activo -->
				<img src="images/appmenu.png" class="showMenu" onClick="move()" id="menu0">
			</div>
			<!-- logo -->
			<a href="http://77digital.com/" target="_black">
			<img class="logo" src="images/logo.png" ">
			</a>
			<div class="menulike">
				
			</div>
		</div>
		
		<div class="sidebar">
			
			<?php 
				//presenta los albums, resultados o canciones de un artista
				switch ($status) {
					case 0:
						album();
						$status = 0;
						break;
					
					case 1:
						voto($like);
						album();
						$status = 0;
						break;

					case 2:
						soloArtista($artista);
						echo '<div class="limpiar" onClick="redireccionar(\'\')">Limpiar</div>';
						$status = 0;
						break;

					case 3:
						$_GET['search'] = '';
						$status = 0;
						buscar($search); 
						break;
				}
			?>

		<!-- fin sidebar -->
		</div>

	<!-- fin page and home -->
	</div>

	<!-- menu -->
	<div class="left" id="menu">

		<div class="topbar">
			<div class="logout" onClick="redireccionar('?logout=true')">
				<!-- <img src="images/logout.png"> -->
				<button>Salir</button>
			</div>
			<?php echo '<h1>'.$_SESSION['usuario'].'</h1>'; ?>
			
		</div>

		<div class="content">

			<form action="index.php" method="post" >

			<div class="list">
				<div class="menuAdmin">

					<div class="opcion" id="opcion2" onClick="menu(2)">
						<img src="images/dropdown.png" id="dropdown">
						<h3>Lista de Canciones</h3>
					</div>

					<div id="menu2" class="options formulario"> 
					
						<hr><h3>Resetear Lista</h3><hr>
						<br/>
						<button onClick="resetList()">Reiniciar Lista</button>
						<button onClick="desList()">Deshabilitar Lista</button>
						<br/>
					
					</div>
				</div>

			</div>

			<div class="list">
				<div class="menuAdmin">

					<div class="opcion" id="opcion3" onClick="menu(3)">
						<img src="images/dropdown.png" id="dropdown">
						<h3>Votos Canciones</h3>
					</div>

					<div id="menu3" class="options formulario"> 
					
						<hr><h3>Resetear Votos</h3><hr>
						<br/>
						<button onClick="resetVotos()">Reiniciar Votos</button>
						<br/>
					
					</div>
				</div>

			</div>

			<!-- para cambiar datos usuario
			<div class="list" >
				<div class="menuAdmin">

					<div class="opcion" id="opcion4" onClick="menu(4)">
						<img src="images/dropdown.png" id="dropdown">
						<h3>Usuario y contaseña</h3>
					</div>

					<div id="menu4" class="options formulario"> 
						
						<hr><h3>Usuario</h3><hr>
						<input type="text" name="oldUsuario" placeholder="Usuario Actual" required="required">
						<input type="text" name="newUsuario" placeholder="Usuario Nuevo">
						<br/>
						<hr><h3>Contraseña</h3><hr>
						<input type="password" name="oldPassword" placeholder="Password Actual" required="required">
						<input type="password" name="newPassword" placeholder="Password Nuevo"  required="required">
						<br/>
						<input type="submit" value="Enviar" name="Envio">
						
					</div>
				</div>

			</div>
			-->
			</form>

		</div>

	</div>

<!-- fin formulario -->
</form>

<!-- twee -->
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
	</script> 

</body>

</html>

<?php

} //fin if
?>