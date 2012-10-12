
//jquery para admin

$(window).load(function() {
 	var ancho = $(".cover").width() * 0.80;
	$('.imageCover').css('width',ancho);
});


$(document).ready(function(){
	var menuStatus;
	var ancho = $(".cover").width() * 0.80;
	$('.imageCover').css('width',ancho);

	$('.pages').live("swipeleft", function(){
		if (menuStatus){
	
		$(".ui-page-active").animate({
			marginLeft: "0px",
		  }, 300, function(){menuStatus = false});
		  }
	});
	
	$('.pages').live("swiperight", function(){
		if (!menuStatus){	
		$(".ui-page-active").animate({
			marginLeft: "80%",
		  }, 300, function(){menuStatus = true});
		  }
	});
	
	$("#menu li a").click(function(){
		var p = $(this).parent();
		if($(p).hasClass('active')){
			$("#menu li").removeClass('active');
		} else {
			$("#menu li").removeClass('active');
			$(p).addClass('active');
		}
	});

	//responsitive para el cover
	$(window).resize(function() {
		var ancho = $(".cover").width() * 0.80;

    	$(window).width() < $('.imageCover').css('width',ancho);
	});

	//actualiza automaticamente los resultados
	jQuery(function($){
  		setInterval(function(){ 
	  		//alert('actualiza');
	  		$.get("admin.php",{'func':'1'},function(data){
	  			$(".sidebar").html(data);
	  			var ancho = $(".cover").width() * 0.80;
	  			$(window).width() < $('.imageCover').css('width',ancho);
			});
  		},5000); // 5000ms == 5 seconds
	});

});	

//para menu deslizable
function move(){
	var menuStatus;

		//alert( $('.showMenu').attr('id') );

		if( $('.showMenu').attr('id') == 'menu0' ){				
			$(".ui-page-active").animate({
				marginLeft: "75%",
		  	}, 300);

		  	$(".content").css('height','100%');
		  	$(".content").fadeIn(10);
		  	menuStatus = true; 
		  	$('.showMenu').attr('id', 'menu1');
		  	//alert(menuStatus);
		  } 
		  else {
			$(".ui-page-active").animate({
				marginLeft: "0px",
		  	}, 300);
			$(".content").css('height','0');
			$(".content").fadeOut(10);
			menuStatus = false; 
			$('.showMenu').attr('id', 'menu0');
			//alert(menuStatus);
		  }
}

function redireccionar(link){
	link = "index.php"+link;
	window.location = link;
}

//elimina de la lista la cancion seleccionada
function eliminar(id){
	
	//esconde automaticamente
	$("#album"+id).animate({
		height: 0,
		width: 0,
		fontSize: 0,
	}, "slow", function(){
		$("#album"+id).css('display','none');
	});
	notifica("Se ha deshabilitado<br>correctamente.")

	//elimina de la lista la cancion
	jQuery(function($){ 
	  	$.get("admin.php",{
	  		'func':'2',
	  		'id' : id
	  	},function(data){
	  		/*$(".sidebar").html(data);
	  		var ancho = $(".cover").width() * 0.80;
	  		$(window).width() < $('.imageCover').css('width',ancho);*/
		});
	});
}

//funciona para actualizar al instante el home del admin
function actualiza(){
	$.get("admin.php",{'func':'1'},function(data){
	  	$(".sidebar").html(data);
	  	var ancho = $(".cover").width() * 0.80;
	  	$(window).width() < $('.imageCover').css('width',ancho);
	});
}

//menu admin
function menu(id){

	if( $("#menu"+id).css('display') == 'none'){
		$("#menu"+id).css('display','table');
		$("#opcion"+id).css({
				'border-bottom-right-radius': '0px',
				'border-bottom-left-radius' : '0px',
		});
		$("#menu"+id).animate({
		    	opacity: 1
	  	}, 1500, 'linear');
	}else{
		$("#menu"+id).css({
				'display': 'none',
				'opacity': '0.2',
		});
		$("#opcion"+id).css('border-radius','20px');
	}

	if(id==2){
		$("#menu3").fadeOut(700);
		$("#opcion3").css('border-radius','20px');
	}else{
		$("#menu2").fadeOut(700);
		$("#opcion2").css('border-radius','20px');
	}
}

function resetList(){
	$.get("admin.php",{'func':'3'});
	actualiza();
}

function desList(){
	$.get("admin.php",{'func':'4'});
	actualiza();
}

function resetVotos(){
	$.get("admin.php",{'func':'5'});
	actualiza();
}

//usa noty (jquery plugin) para notificar 
function notifica(text) {
  	var n = noty({
  		text: text,
  		type: 'alert',
    	dismissQueue: true,
  		layout: 'topCenter',
  		closeWith: ['click'], // ['click', 'button', 'hover']
  	});
  	console.log('html: '+n.options.id);
  	
  	//tiempo para desaparecerlo solo 
  	setTimeout(function (){
		n.close();
	},3000);
}

//notifica errores
function notificaE(text) {
  	var n = noty({
  		text: text,
  		type: 'error',
    	dismissQueue: true,
  		layout: 'topCenter',
  		closeWith: ['click'], // ['click', 'button', 'hover']
  	});
  	console.log('html: '+n.options.id);
  	
  	//tiempo para desaparecerlo solo 
  	setTimeout(function (){
		n.close();
	},3000);
}

//notifica advertencias
function notificaW(text) {
  	var n = noty({
  		text: text,
  		type: 'warning',
    	dismissQueue: true,
  		layout: 'topCenter',
  		closeWith: ['click'], // ['click', 'button', 'hover']
  	});
  	console.log('html: '+n.options.id);
  	
  	//tiempo para desaparecerlo solo 
  	setTimeout(function (){
		n.close();
	},3000);
}