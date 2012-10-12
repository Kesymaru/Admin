<?php

/*
	Conecta la base de datos, procesa los datos y los muestra

*/

$host      =    "localhost";
$user      =    "laqueusr";
$pass      =    "Trans123@";
$tablename =    "laquesiguedb";

$conecta = mysql_connect($host,$user,$pass);

mysql_select_db($tablename, $conecta);

if(isset($_GET['func'])){
	switch($_GET['func']) {
	  case '1':
	    album();
	    break;
	  default:
	    // Do nothing?
	}
}

//para el home. muestra los albums ordenados por la cantidad de votos desendientemente
function album(){
	$contador = 0;
	$sql = 'SELECT * FROM votos ORDER BY votos DESC';
	$resultado = mysql_query($sql);
	
	while($row = mysql_fetch_array($resultado)){
		
		if($row['status'] == 1){
			$contador++;
			artista($row['cancion']);
			contador($contador, $row['cancion']);
		}
  	}
  	if($contador == 0){
  		echo '<div class="error">
  			<h1>No hay mas canciones en la lista.</h1>
  		</div>';
  	}
}

//solo para el administrador para que pueda ver incluso las deshabilitadas
function adminalbum(){
	$contador = 0;
	$sql = 'SELECT * FROM votos ORDER BY votos DESC';
	$resultado = mysql_query($sql);
	
	while($row = mysql_fetch_array($resultado)){
		$contador++;
		artista($row['cancion']);
		contador($contador, $row['cancion']);
  	}
  	echo '<SCRIPT TYPE="text/javascript">alert(\'Admin\');</SCRIPT>';
  	if($contador == 0){
  		echo '<div class="error">
  			<h1>No hay mas canciones en la lista.</h1>
  		</div>';
  	}
}

//muestra un album con el id de la cancion
function artista($idArtista){

  	$sql = 'SELECT * FROM musica WHERE id ='.$idArtista;
	$resultado = mysql_query($sql);

	while($row = mysql_fetch_array($resultado)){
		showArtista($row);
  	}
}

//realiza el voto. actualiza la tabla de la base de datos de voto
function voto($idCancion){

	$votos = votos($idCancion);
	$votos = $votos + 1;
	//actualiza votos
	$sql = 'UPDATE votos SET votos ='.$votos.' WHERE cancion ='.$idCancion;

	mysql_query($sql);
	//echo '<SCRIPT TYPE="text/javascript">alert(\'El voto se ha echo.\');</SCRIPT>';
	$link = '';
	echo '<SCRIPT TYPE="text/javascript">window.location = \"index.php\";</SCRIPT>';
}

//muestra todos la lista artistas agrupando los artistas para hacer una lista
function artistas(){
	$sql = 'SELECT * FROM musica GROUP BY artista';
	$resultado = mysql_query($sql);

	while($row = mysql_fetch_array($resultado)){
		
		echo '<h3 onClick="redireccionar( \'?artista='.$row['artista'].' \' )">'.$row['artista'].'</h3>';
	}
}

//devuelve votos de la cancion
function votos($idCancion){
	$sql = 'SELECT * FROM votos WHERE cancion = '.$idCancion;
	$resultado = mysql_query($sql);
	$resultado = mysql_fetch_array($resultado);
	
	if(isset($resultado['votos'])){
		return $resultado['votos'];
	}else{
		return '0';
	}
}

//muestra las canciones de un solo artista
function soloArtista($nombre){
	//echo '<SCRIPT TYPE="text/javascript">alert (\'Mostrando canciones de '.$_GET['artista'].'\');</SCRIPT>';
	$error = true;
	$sql = "SELECT * FROM musica WHERE artista LIKE '%$nombre%' LIMIT 0, 30";
	$resultado = mysql_query($sql);

	while($row = mysql_fetch_array($resultado)){
		showArtista($row);
		$error = false;
	}
	//mensaje de error
	if($error){
		echo '<div class="error">
			
				<h1>No se encontraron resultados para <br>'.$nombre.'</h1>

		</div>';
	}
}

function buscar($busca){
	$encontro = false;
	$resultado = 'No se encontro ningun resultado con la busqueda '.$busca;

	for ($i = 0; $i <= 2; $i++){
		if($i == 0){
			$sql = mysql_query("SELECT * FROM musica WHERE artista LIKE '%$busca%' LIMIT 0, 30 ");
		}
		if($i == 1){
			$sql = mysql_query("SELECT * FROM musica WHERE cancion LIKE '%$busca%' LIMIT 0, 30 ");
		}
		if($i == 2){
			$sql = mysql_query("SELECT * FROM musica WHERE album LIKE '%$busca%' LIMIT 0, 30 ");
		}

		while ($row = mysql_fetch_array($sql)){
			if($row['artista'] == $busca || $row['cancion'] == $busca || $row['album'] == $busca){
				$encontro = true;
				showArtista($row);
				}
			}
			if($encontro == true){
				echo '<div class="limpiar" onClick="redireccionar(\'\')">Limpiar</div>';
				break;
			}
	}
	if(!$encontro){
		echo '<div class="error">
			
				<h1>No se encontraron resultados para <br>'.$busca.'</h1>

		</div>';
	}

	//muestra el boton para clear
	//echo '<SCRIPT TYPE="text/javascript">$(".clearMenu").css(\'display\',\'inline-block\');</SCRIPT>';
	//echo '<SCRIPT TYPE="text/javascript">alert (\'Resultado '.$resultado.' \');</SCRIPT>';
}


//muestra formatedo el artista, requiere el arreglo de la consulta de la tabla de la base de datos
function showArtista($row){
		echo '<div class="album" id="album'.$row['id'].'" onClick="eliminar('.$row['id'].')">
				<div class="cover">';
		//cover
		if ($row['cover'] == 'default'){
			echo '<img class="imageCover" src="images/album.png" title="'.$row['artista'].'">';
		}else{
			echo '<img class="imageCover" src="images/cover/'.$row['cover'].'" title="'.$row['artista'].'">';
		}

		echo '</div>
				<div class="info">
					<div class="contador" id="contador'.$row['id'].'">
						<span></span>
					</div>
					<div class="infosong">
						<img src="images/disco.png">';
		//artista
		echo '<h2>'.$row['cancion'].'</h2>';
		//album
		echo '<h3>'.$row['artista'].'</h3>';

		echo '</div>
					<div class="infovotos">
						<img src="images/like.png">';
		//id para el jquery
		echo '<img src="images/masDesactivo.png"  class="mas" id="boton'.$row['id'].'">';
		//votos
		echo '<p id="votos'.$row['id'].'">'.votos($row['id']).' votos</p>
				</div>
				</div>
			</div>';
}

function contador($numero, $id){
	echo '<SCRIPT TYPE="text/javascript">
			$("#contador'.$id.'").html("<h1>'.$numero.'</h1>");
			$("#contador'.$id.'").css("display","inline-block");
		</SCRIPT>';
}


/* para el admin */

//imprime formulario para logue
function loginForm(){
	echo '
	<html>

	<head>
	<title>Laquesigue Admin</title>
	<meta charset="utf-8" />
	<meta id="extViewportMeta" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	
	<link rel="stylesheet" href="css/style.css" TYPE="text/css" MEDIA=screen>	
	
	</head>
	<body>

	
	<form action="index.php" method="post" class="formulario">
		<h1>Admin</h1>
		<h3>Entrar como administrador</h3><br/>
		<input type="text" name="admin" required="required" placeholder="Username">
		<br/>
		<input type="password" name="password" required="required" placeholder="Password">
		<br>
		<input type="submit" value="Entrar">
		<hr class="hrFull">
		<p>
			Esta seccion es exclusiva para el administrador. <br/>
			Si estas buscando la app esta es la direccion <br/>
			<a href="http://www.laquesigue.com/"> laquesique.com</a>
		</p>
	</form>

	</body>
	</html>
	';
}

//determina si el admin esta logueado
function admin(){
	//echo '<h1>Adminlogueado</h1>';
	
	if(isset($_SESSION['admin']) && isset($_SESSION['status']) && $_SESSION['admin'] != false && $_SESSION['status']){
		return true;
	}else{
		return false;
	}
}

function loguear($admin, $password){
	$password = encripta($password);

	$sql = 'SELECT * FROM admin';
	$resultado = mysql_query($sql);

	while($row = mysql_fetch_array($resultado) ){
		//echo '<SCRIPT TYPE="text/javascript">alert (\'PassData: '.$row['password'].' Pass:'.$password.'\');</SCRIPT>';
		
		if( $row['password'] == $password && $row['admin'] == $admin){
				$_SESSION['admin']   = true;
				$_SESSION['status']  = true;
				$_SESSION['usuario'] = $row['admin'];
				$_SESSION['id']      = $row['id'];
		}
  	}

}

//encrita o desencrita password
function encripta($text){
	//quita / y etiquetas html
	$text = stripcslashes($text);
	$text = strip_tags($text);
	$text = md5 ($text); 
	$text = crc32($text);
	$text = crypt($text, "xtemp"); 
	$text = sha1("xtemp".$text);
	return $text;
}

function logout(){
	//remoeve todas la variables de la session
	session_unset();

	//destruye la session
	session_destroy();

	//echo '<SCRIPT TYPE="text/javascript">rediereccionar(\'\');</SCRIPT>';
	//header('location: http://localhost/testing/admin/index.php');
}


?>