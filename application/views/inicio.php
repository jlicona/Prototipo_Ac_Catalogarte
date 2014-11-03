<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
/**
 * Variables recibidas del controlador:
 * @param array $exposicionesRecientes Arreglo de registros con exposiciones ej: foreach(){$registro->campo}
 * @param array $exposicionesPorVenir Arreglo de registros con exposiciones ej: foreach(){$registro->campo}
 */

foreach($exposicionesRecientes as &$exposicion){
	//Agrega identificador en html de cada exposición
	$exposicion["id_html"] = "slide".$exposicion["id"];
	unset($exposicion["nota"]);
}
foreach($exposicionesPorVenir as &$exposicion){
	//Generamos id en html y regeneramos solo con datos que usará javascript (archivo menos pesado)
	$id = $exposicion["id"];
	$nombre = $exposicion["nombre"];
	$limite = 50;
	if(strlen($nombre)>$limite)
		$nombre = substr ($nombre, 0, $limite) . " ...";
	$archivoPortada = $exposicion["archivo_portada"];
	$carpeta = $exposicion["carpeta_multimedios"];
	$idHtml = "slidePV".$exposicion["id"];
	$exposicion = array("id" => $id, "id_html" => $idHtml, "nombre" => $nombre, "archivo_portada" =>$archivoPortada, "carpeta_multimedios"=>$carpeta);
	//$exposicion["id_html"] = $idHtml;
}
?>
<div id="portSupe">
	
	<div id="contenedorExpo">
		<div id="carrusel_expo">
			<!--<img class="expoFoto" id="slide16" alt="expoFoto 2" src="<?=base_url()?>img/temp/porvenir.png" >
			<img class="expoFoto" id="slide15" alt="expoFoto" src="<?=base_url()?>img/temp/portada.jpg">
			<img class="expoFoto" id="slide3" alt="expoFoto" src="<?=base_url()?>img/temp/slide3.png">-->
		</div>
		
		<div id="contenidoExpo">
			<p class="txt_Titulo_Expo">EXPOSICIÓN</p>
			<p class="txt_Sede_Expo">...</p>
			<p class="txt_Nombre_Expo">...</p>
			<p class="txt_Contenido_Expo">....</p>
		</div>
	</div>

	<div id="contenedor_Port">
		<div class="portDesc">
			&nbsp;
			<br><font class="txt_Titulo_Seccion">CATALOGARTE</font>
			<p class="txt_Contenido">El Instituto Nacional de Bellas Artes y Literatura tiene como encomienda difundir al p&uacute;blico, nacional y extranjero, el arte y a quienes le dan vida.  Con este portal, se pone a disposici&oacute;n del p&uacute;blico las exposiciones que exhibe el circuito de museos del INBA, buscando de esta manera acercarlos a los artistas y las obras que pueblan sus recintos. </p>
		</div>
		<div class="portSede">
			&nbsp;
			<br><font class="txt_Titulo_Seccion">SEDES</font>
			<p class="txt_Contenido">Estos son los espacios que componen al circuito de Museos del INBA.</p>
			<br>
			<center>
			<table>
				<tr><td>
					<a href="#"><img src="<?=base_url()?>img/sedes/GJMV.jpg" width="90" height="88"></a>
				</td><td>
					<a href="#"><img src="<?=base_url()?>img/sedes/MCEDR.jpg" width="97" height="117"></a>
				</td></tr>
				<tr><td>
					<a href="#"><img src="<?=base_url()?>img/sedes/MUNAE.jpg" width="109" height="117"></a>
				</td><td>
					<a href="#"><img src="<?=base_url()?>img/sedes/Tamayo.jpg" width="117" height="94"></a>
				</td></tr>
			</table>
			<img src="<?=base_url()?>img/separador3.png" width="9" height="9" alt="Separador">&nbsp;&nbsp;
			<img src="<?=base_url()?>img/separador3.png" width="9" height="9" alt="Separador">&nbsp;&nbsp;
			<img src="<?=base_url()?>img/separador.png" width="9" height="9" alt="Separador">&nbsp;&nbsp;
			<img src="<?=base_url()?>img/separador3.png" width="9" height="9" alt="Separador">&nbsp;&nbsp;
			</center>
		</div>
	</div>
</div>

<div id="portFond">
	<div class="portPVen">
		&nbsp;
		<br>
		<font class="txt_Subtitulo">YA VIENE</font>
		<p class="txt_Enfasis" id="PVnombre">Un titulo mas o menos corto <img src="<?=base_url()?>img/separador.png" width="9" height="9" alt="Separador"></p>
		<br>
		<div id="PVcontenedor">
			<div id="PVcarrusel">
				<!--<img src="<?=base_url()?>img/temp/porvenir.png" width="340" height="121">-->
			</div>
		</div>
    </div>	
	<div class="portNoti">
		&nbsp;
		<br><font class="txt_Subtitulo">NO TE PIERDAS</font>
		<p class="txt_Enfasis">Concurso de Literatura <img src="<?=base_url()?>img/separador.png" width="9" height="9" alt="Separador"> </p>
 		<p class="txt_Contenido">¿Eres Artista pl&aacute;stico y escritor? &iexcl;Participa! Revisa las bases aqu&iacute;: <a class="txt_Link" href="http://basesConcurso.catalogarte.net"> http://basesConcurso.catalogarte.net</a></p>
		<p class="txt_Enfasis">¡La exposición fue un éxito! <img src="<?=base_url()?>img/separador.png" width="9" height="9" alt="Separador"></p>
		<p class="txt_Contenido">Gracias por la participacilón de todos en esta entrega.  Esperamos que sigamos asi. <a class="txt_Link" href="http://www.link.com">http://www.link.com</a></p>

	</div>
	<div class="portRSoc">
		&nbsp;
		<br><font class="txt_Subtitulo">REDES SOCIALES</font>
		<p class="txt_Enfasis">@twitterDeAlguien <img src="<?=base_url()?>img/separador.png" width="9" height="9" alt="Separador"></p>
		<p class="txt_Contenido">Amigos, no dejen de ver la sobrecogedora expo fotogr&aacute;fica &quot;Sobre la Bestia&quot;, de Isain Esponda en el @GJMV.  Aqui el link: <a class="txt_Link" href="http://catalogarte.bellasartes.gob.mx/expo.php?id=23">http://catalogarte.bellasartes.gob.mx/expo.php?id=23</a></p>
	</div>
</div>

<script>

    $(document).ready(function(){
		//Variables para texto de exposición
		var colapsado = true;
		var cssColapsado = {"height" : "160px", "top" : "440px" };
		var cssFull = {"height" : "100%", "top" : "0" };
		$("#contenidoExpo").click(function(){
			if(!colapsado){
				$("#contenidoExpo").animate(cssColapsado, 1000);
				colapsado = true;
			}else{
				$("#contenidoExpo").animate(cssFull, 1000);
				colapsado = false;
			}
		});


		//Variables para carrusel de exposición
		/*var slides = [{"id_html":"#slide1", "nombre":"título 1"}, 
			{"id_html":"#slide2", "nombre":"Super título 2"},
			{"id_html":"#slide3", "nombre":"Impresionantisimo título 3"}];*/
		var slides = <?= json_encode($exposicionesRecientes, JSON_UNESCAPED_UNICODE)?>;
		for(var i=0; i<slides.length ; i++){
			var atributos = {"src" : <?='"'.base_url().'"'?> + slides[i].carpeta_multimedios + slides[i].archivo_portada.nombre,
				"alt" : slides[i].archivo_portada.nombre,
				"id" : slides[i].id_html
			};
			$("#carrusel_expo").append($('<img>').attr(atributos).addClass('expoFoto'));
		}
		var posicionSlide = 0;
		var alto = $("#"+slides[0].id_html).height();
		//Posición inicial de slides
		for(var i=0 ; i<slides.length ; i++){
			$("#"+slides[i].id_html).css( {"top" : alto*i} );
		}
		$(".expoFoto").click(function(){
			//Detener timer
			clearInterval(timerCarrusel);
			moverSlides();
		});
		
		var timerCarrusel = setInterval(function(){moverSlides();}, 6000);
		function moverSlides(){
			posicionSlide++;
			if(posicionSlide >= slides.length){
				posicionSlide = 0;
			}
			$("#carrusel_expo").animate({"top" : -alto*posicionSlide}, 1000);
			//$(".txt_Titulo_Expo").fadeOut(200, function(){
			$("#contenidoExpo").children().fadeOut(200, function(){
				asignarTextoCarrusel();
				$(this).fadeIn(800);
			});
		}
		function asignarTextoCarrusel(){
			$(".txt_Sede_Expo").html(slides[posicionSlide].sede);
			$(".txt_Nombre_Expo").html(slides[posicionSlide].nombre);
			$(".txt_Contenido_Expo").html(slides[posicionSlide].descripcion);
		}
		asignarTextoCarrusel();


		//Variables para carrusel de exposiciones por venir
		var slidesPV = <?= json_encode($exposicionesPorVenir, JSON_UNESCAPED_UNICODE)?>;
		for(var i=0; i<slidesPV.length ; i++){
			var atributos = {"src" : <?='"'.base_url().'"'?> + slidesPV[i].carpeta_multimedios + slidesPV[i].archivo_portada.nombre,
				"alt" : slidesPV[i].archivo_portada.nombre,
				"id" : slidesPV[i].id_html
			};
			$("#PVcarrusel").append($('<img>').attr(atributos).addClass('expoFotoPV'));
		}
		var posicionSlidePV = 0;
		var altoPV = $("#"+slidesPV[0].id_html).height();
		//Posición inicial de slides
		for(var i=0 ; i<slidesPV.length ; i++){
			$("#"+slidesPV[i].id_html).css( {"top" : altoPV*i} );
		}
		$(".expoFotoPV").click(function(){
			//Detener timer
			clearInterval(timerCarruselPV);
			moverSlidesPV();
		});
		
		var timerCarruselPV = setInterval(function(){moverSlidesPV();}, 6000);
		function moverSlidesPV(){
			posicionSlidePV++;
			if(posicionSlidePV >= slidesPV.length){
				posicionSlidePV = 0;
			}
			$("#PVcarrusel").animate({"top" : -altoPV*posicionSlidePV}, 1000);
			$("#PVnombre").fadeOut(200, function(){
				asignarTextoCarruselPV();
				$(this).fadeIn(800);
			});
		}
		function asignarTextoCarruselPV(){
			$("#PVnombre").html(slidesPV[posicionSlidePV].nombre)
				.append($("<img>").attr({"width":"9px","height":"9px","src":"<?=base_url()?>img/separador.png"}));
		}
		asignarTextoCarruselPV();
    });
</script>


<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>