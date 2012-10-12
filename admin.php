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

//para actualizar con ajax
if(isset($_GET['func'])){
	switch($_GET['func']) {
	  case '1':
	    album();
	    break;

	  case '2':
	  	if(isset($_GET['id'])){
	  		//se elimina de la lista eliminar es igual a esconder
	  		eliminar($_GET['id']);
	  	}
	  	break;

	  case '3':
	  	resetList();
	  	break;

	  case '4':
	  	desList();
	  	break;

	  case '5':
	  	resetVotos();
	  	break;

	  default:
	    // nada
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

//muestra un album con el id de la cancion
function artista($idArtista){

  	$sql = 'SELECT * FROM musica WHERE id ='.$idArtista;
	$resultado = mysql_query($sql);

	while($row = mysql_fetch_array($resultado)){
		showArtista($row);
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

/*
	Para eliminar de la listas
*/

//saca de la lista la cancion
function eliminar($id){
	$status = 0;
	//actualiza votos
	$sql = 'UPDATE votos SET status ='.$status.' WHERE cancion ='.$id;

	mysql_query($sql);
	
	//actualiza lista
	echo '<SCRIPT TYPE="text/javascript">actualiza();</SCRIPT>';
}

//resetea la lista de canciones, todas a visibles
function resetList(){

	$sql = 'SELECT * FROM votos';
	$resultado = mysql_query($sql);
	
	while($row = mysql_fetch_array($resultado)){
		visible( $row['cancion'] );
  	}

}

function visible($id){

	//status para visible
	$sql = 'UPDATE votos SET status = 1 WHERE cancion ='.$id;
	mysql_query($sql);

}

//deshabilita la lista de canciones, todas a escondidas
function desList(){
	$sql = 'SELECT * FROM votos';
	$resultado = mysql_query($sql);
	
	while($row = mysql_fetch_array($resultado)){
		oculta( $row['cancion'] );
  	}
}

function oculta($id){
	//status para ocultos o tocados
	$sql = 'UPDATE votos SET status = 0 WHERE cancion ='.$id;
	mysql_query($sql);
}

//pone en cero todo los votos
function resetVotos(){
	$sql = 'SELECT * FROM votos';
	$resultado = mysql_query($sql);
	
	while($row = mysql_fetch_array($resultado)){
		votosCero( $row['cancion'] );
  	}
}

//a cero todos los votos de todas la canciones
function votosCero($id){
	//status para ocultos o tocados
	$sql = 'UPDATE votos SET votos = 0 WHERE cancion ='.$id;
	mysql_query($sql);
}

?>