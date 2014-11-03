<?php

function pasarMayusculas($cadena) { 
	$cadena = strtoupper($cadena); 
	$cadena = str_replace("á", "Á", $cadena); 
	$cadena = str_replace("é", "É", $cadena); 
	$cadena = str_replace("í", "Í", $cadena); 
	$cadena = str_replace("ó", "Ó", $cadena); 
	$cadena = str_replace("ú", "Ú", $cadena); 
	return ($cadena); 
}

function adjustBrightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Format the hex color string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Get decimal values
    $r = hexdec(substr($hex,0,2));
    $g = hexdec(substr($hex,2,2));
    $b = hexdec(substr($hex,4,2));

    // Adjust number of steps and keep it inside 0 to 255
    $r = max(0,min(255,$r + $steps));
    $g = max(0,min(255,$g + $steps));  
    $b = max(0,min(255,$b + $steps));

    $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
    $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
    $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

    return '#'.$r_hex.$g_hex.$b_hex;
}


if (!defined('BASEPATH'))exit('No direct script access allowed');
/**
 * Variables recibidas del controlador:
 * @param $catalogo Registro con la exposicion que se visita ej: $registro->campo
 */

/**
 * 2550 x 3300 pixeles HOJA CARTA
 * catalogo_portada
 * catalogo_legal
 * catalogo_indice
 * catalogo_notas
 * catalogo_obra_par
 * catalogo_obra_impar
 * catalogo_contraportada
 * index.php/catalogo/ver/1
 */

// Guarda referencias para el índice de items
$indiceHojas = array();
$nItem = 0.0; //Será redondeado hacia abajo por incrementos de 0.5 para generar referencias a índices

// Guarda cada hoja
$listaHojas = array('<div class="contenedor_invisible"></div>');

	//print_r ($catalogo);
	//Portada
	$outputHoja = '<div class="catalogo_disciplina_izq">'.pasarMayusculas($catalogo['disciplina']).'</div>'.
				  '<div class="catalogo_titulo_portada">'.$catalogo['nombre'].'</div>'.
				  '<div class="catalogo_descripcion"><div class="catalogo_contenido_txt">'.nl2br($catalogo['descripcion']).'</div></div>'.
				  '<div class="catalogo_imagen_portada"><img class="catalogo_imagen_portada_pic" src="'.base_url().$catalogo['carpeta_multimedios'].$catalogo['archivo_portada']['nombre'].'"></div>'.
				  '<div class="catalogo_pagina_izq">001</div>'.
				  '<div class="catalogo_fondo_izq" style="background-color:'.$catalogo['color'].'"></div><br>';
	$outputHoja = '<div class="contenedor_hoja">'.$outputHoja.'</div>';
	$listaHojas[] = $outputHoja;
	$indiceHojas[] = array("nombre"=>'Portada', "item" => (int)floor($nItem+=0.5));

	//Hoja Legal
	$outputHoja = '<div class="catalogo_disciplina_der">'.pasarMayusculas($catalogo['disciplina']).'</div>'.
				  '<div class="catalogo_titulo_seccion_der"><div class="catalogo_titulo_seccion_txt">HOJA LEGAL</div></div>'.
				  '<div class="catalogo_contenido_legal"><div class="catalogo_contenido_txt">'.
				  "Queda prohibida la reproducción total o parcial de esta publicación periódica, por cualquier medio o procedimiento, sin para ello contar con la autorización previa, expresa y por escrito del editor.  Toda forma de utilización no autorizada será perseguida conforme a lo establecido en la ley federal del derecho de autor.<br><br>Derechos Reservados Conforme a la ley.<br><br>(c) www.catalogArte.net (México 2014).</div></div>".
				  '<div class="catalogo_pagina_der">002</div>'.
				  '<div class="catalogo_fondo_der" style="background-color:'.$catalogo['color'].'"></div>';
	$outputHoja = '<div class="contenedor_hoja">'.$outputHoja.'</div>';
	$listaHojas[] = $outputHoja;
	$indiceHojas[] = array("nombre"=>'Hoja Legal', "item" => (int)floor($nItem+=0.5));

	//Hoja Indice
	$contenidoArticulos = '';
	$contenidoAutores = '';
	$paginaActual = 5;
	foreach ($catalogo['articulos'] as $articulo) {
		$contenidoArticulos .= '<br>' . sprintf('%03d', $paginaActual) . ' ' . $articulo['nombre'];
		//foreach ($articulo['hojas'] as $hoja) {
		$paginaActual += count($articulo['hojas']);
		//}
	}
	foreach ($catalogo['autores'] as $autor) {		
		$contenidoAutores .= "<br>" . sprintf('%03d', $paginaActual) . " " . $autor['nombre'];
		$paginaActual += count($autor['obras']);
	}
	$outputHoja = '<div class="catalogo_disciplina_izq">'.pasarMayusculas($catalogo['disciplina']).'</div>'.
				  '<div class="catalogo_titulo_seccion_izq"><div class="catalogo_titulo_seccion_txt">INDICE</div></div>'.
				  '<div class="catalogo_contenido_indice"><div class="catalogo_contenido_txt">001 PORTADA'.
				  '<br>002 HOJA LEGAL'.
				  '<br>003 INDICE'.
				  '<br>004 NOTAS'.
				  '<br>'.
				  '<br> ARTICULOS'.
				  '<br>'.
				  $contenidoArticulos.
				  '<br>'.				
				  '<br> AUTORES'.
				  '<br>'.
				  $contenidoAutores.
				  '<br><br>'. sprintf('%03d', $paginaActual) .' CONTRAPORTADA</div></div>'.
				  '<div class="catalogo_pagina_izq">003</div>'.
				  '<div class="catalogo_fondo_izq" style="background-color:'.$catalogo['color'].'"></div>';
	$outputHoja = '<div class="contenedor_hoja">'.$outputHoja.'</div>';
	$listaHojas[] = $outputHoja;
	$indiceHojas[] = array("nombre"=>'Indice', "item" => (int)floor($nItem+=0.5));

	//Hoja Notas
	$outputHoja = '<div class="catalogo_disciplina_der">'.pasarMayusculas($catalogo['disciplina']).'</div>'.
				  '<div class="catalogo_titulo_seccion_der"><div class="catalogo_titulo_seccion_txt">NOTAS</div></div>'.
				  '<div class="catalogo_contenido_notas"><div class="catalogo_contenido_txt">'.nl2br($catalogo['nota']).'</div></div>'.
				  '<div class="catalogo_pagina_der">004</div>'.
				  '<div class="catalogo_fondo_der" style="background-color:'.$catalogo['color'].'"></div>';
	$outputHoja = '<div class="contenedor_hoja">'.$outputHoja.'</div>';
	$listaHojas[] = $outputHoja;
	$indiceHojas[] = array("nombre"=>'Notas', "item" => (int)floor($nItem+=0.5));

	
	//Hoja Articulos	
	$catalogo_articulo = '';
	$paginaActual = 5;
	foreach ($catalogo['articulos'] as $articulo) {
		$indiceHojas[] = array("nombre"=>$articulo['nombre'], "item" => (int)floor($nItem));
		$nHoja = 1;
		foreach ($articulo['hojas'] as $hoja) {
				
			if ($paginaActual%2==0) {$aux = '_der'; $pos = 'top:750px;'; $aux2 = 'margin-left:539px;width:100%;'; }
			else { $aux = '_izq';					$pos = 'top:750px;'; $aux2 = 'margin-left:57px;width:100%;'; }

			$outputHoja = '<div class="contenedor_hoja">'.
					'<div class="catalogo_disciplina' . $aux . '">' . pasarMayusculas($catalogo['disciplina']) . '</div>'.
					
					'<div class="catalogo_pagina'.$aux.'">'.sprintf('%03d', $paginaActual).'</div>'.
					'<div width="544" class="catalogo_fondo'.$aux.'" style="background-color:'.$catalogo['color'].'">'.
					$hoja.
					'</div>'.
					'<div style="position: absolute;' . $pos . '"> <div style="float:left;' . $aux2 . '" class="catalogo_texto_90_grados">' . $articulo['nombre'] . '</div></div>'.
				'</div>';
			$listaHojas[] = $outputHoja;
			$indiceHojas[] = array("nombre"=>'ESCONDER', "item" => (int)floor($nItem+=0.5));
			$paginaActual += 1;
		}
	}
	

	//Hojas Obras
	$outputHoja = '';	
	foreach ($catalogo['autores'] as $autor) {		
		foreach ($autor['obras'] as $obra) {
			
			$esDerecha = ($paginaActual%2==0);
			if ($esDerecha) $aux = '_der';
			else $aux = '_izq';
			
			$multimedio = $obra['multimedios'][0];
			$outputHoja = '<div class="contenedor_hoja">'.
						'<div class="catalogo_disciplina'.$aux.'">'.pasarMayusculas($catalogo['disciplina']).'</div>'.
						'<div class="catalogo_titulo_seccion'.$aux.'"><div class="catalogo_titulo_seccion_txt">'.$obra['titulo'].'</div></div>'.
						'<div class="catalogo_imagen_obra'.$aux.'"><img class="catalogo_imagen_obra_pic" src="'.base_url().$catalogo['carpeta_multimedios'].$multimedio['nombre'].'"></div>'.
						'<div class="catalogo_obra_contenido'.$aux.'"><div class="catalogo_contenido_txt">'.$obra['comentario'].'</div></div>'.
						'<div class="catalogo_obra_ficha'.$aux.'"><div class="catalogo_contenido_ligero_txt">Autor: '.$autor['nombre'].
						'<br>Fecha de Elaboración: '.$obra['elaboracion'].
						'<br>Técnica: '.$obra['tecnica'].
						'<br>Dimensiones: '.$obra['dimensiones'].
						'</div></div>'.
						'<div class="catalogo_pagina'.$aux.'">'.sprintf('%03d', $paginaActual).'</div>'.
						'<div class="catalogo_fondo'.$aux.'" style="background-color:'.$catalogo['color'].'"></div>'.
						'</div>';
			$paginaActual += 1;
			$listaHojas[] = $outputHoja;
			$indiceHojas[] = array("nombre"=>$obra['titulo'], "item" => (int)floor($nItem+=0.5));
		}
	}
	//$catalogo_obras = $outputHoja;

	//Hoja Contraportada
	$outputHoja = '<div class="catalogo_titulo_contraportada">'.$catalogo['nombre'].'</div>'.
				  '<div class="catalogo_contenido_contraportada"><div class="catalogo_contenido_txt"><b>Sede: </b>'. $catalogo['sede'].
				  '<br><b>Desde: </b>'. $catalogo['inicio'].
				  '<br><b>Hasta: </b>'. $catalogo['fin'].'</div></div>'.
				  '<div class="catalogo_imagen_contraportada"><img class="catalogo_imagen_contraportada_pic" src="'.base_url().$catalogo['carpeta_multimedios'].$catalogo['archivo_contraportada']['nombre'].'"></div>'.
				  '<div class="catalogo_fondo_cen" style="background-color:'.$catalogo['color'].'"></div>';
	$outputHoja = '<div class="contenedor_hoja">'.$outputHoja.'</div>';
	$listaHojas[] = $outputHoja;
	$indiceHojas[] = array("nombre"=>'Contraportada', "item" => (int)floor($nItem+=0.5));

	if( count($listaHojas) % 2 != 0)
		$listaHojas[] = '<div class="contenedor_hoja"></div>';
	
?>
<!--
# CATALOGARTE : Prototipo para la difusion de Exposiciones
# Copyright (c) 2014 IMPORTARE
# Auteur - Author - Autor: Axel Sanchez < axel20000@gmail.com >
# Auteur - Author - Autor: Hugo Gutierrez < akira.redwolf@gmail.com >
# Auteur - Author - Autor: Jaime Licona < liconita@gmail.com >
# Auteur - Author - Autor: Luis Sol < luisol.04@gmail.com >
# 
# Este programa es software libre; Ud. puede redistribuirlo y/o modificarlo
# bajo los terminos de la Licencia Publica General GNU tal como publico 
# la Fundacion del Software Libre; en su version 3 de la Licencia, o
# (según su voluntad) cualesquiera otras posteriores.
#
# Este programa se distribuye con el animo de que sea útil, pero SIN
# GARANTIA de NINGUN TIPO; incluso sin la garantia implicita de USO MERCANTIL
# o UTILIDAD PARA UN USO ESPECIFICO. Vease la Licencia Publica General GNU 
# para mas detalles.
#
# Deberia haber recibido una copia de la Licencia Publica General GNU con este
# programa, si no ha sido así, consulte <http://www.gnu.org/licenses/>
# -->
<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title><?=$catalogo['nombre']?></title>
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="author" content="Catalogarte" />
		
		<link href='http://fonts.googleapis.com/css?family=Raleway:300,500,100,800)' rel='stylesheet' type='text/css'>
		
		<link rel='shortcut icon' href="<?=base_url()?>img/iconos/favicon.png" type='image/png'>
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/estilos.css" >
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/bookblock/jquery.jscrollpane.custom.css" />
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/bookblock/bookblock.css" />
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/bookblock/custom.css" />
		<script src="<?=base_url()?>js/bookblock/modernizr.custom.79639.js"></script>
		
	</head>
	<body>
		<div id="container" class="container">	

			<div class="menu-panel" >
				<h3>Navegación</h3>
				<ul id="menu-toc" class="menu-toc">
					<?php 
					$esPrimerItem = true;
					foreach($indiceHojas as $indiceItem){
						echo '<li id-item="'.$indiceItem['item'].'" '.($esPrimerItem ? 'class="menu-toc-current "' : ' ') . ($indiceItem['nombre']=='ESCONDER'? 'style="display:none;"' : '') . '><a href="#">'.$indiceItem['nombre'].'</a></li>';
						$esPrimerItem = false;
					}//fin ciclo indice ?>
				</ul>
			</div>

			
			<div class="bb-custom-wrapper">
				<div id="bb-bookblock" class="bb-bookblock">
					
					<?php for($nHoja = 0, $nItem=0; $nHoja<count($listaHojas); $nHoja+=2, $nItem++){ ?>
					
					<div class="bb-item" id="item<?=$nItem;?>">
						<div class="content">
							
							<div class="scroller">
								<div class="micontenido" style="margin: auto; width: 1232px">
									<div class="mipagina" style="float:left; width:616px; height:796px; ">
										<?=$listaHojas[$nHoja];?>
									</div>

									<div style="float:left; width:616px; height:796px; ">
										<?=$listaHojas[$nHoja+1];?>
									</div>
								</div>
								
							</div>
							
						</div>
					</div>
					
					<?php } //fin ciclo hojas ?>
					
					
				</div>
				
				<nav>
					<span id="bb-nav-prev">&larr;</span>
					<span id="bb-nav-next">&rarr;</span>
				</nav>

				<span id="tblcontents" class="menu-button">Table of Contents</span>

				<img class="banner_catalogo" src="<?=  base_url()?>img/banner_catalogo.png" />
			</div>
				
		</div><!-- /container -->
		
		<style>
			.menu-panel{
				overflow: auto;
				background:<?=$catalogo['color']?>;
			}
			
			.menu-toc li a{
				background:<?=$catalogo['color']?>;
				border-bottom: 1px solid <?=  adjustBrightness($catalogo['color'], -50)?>;
			}
			.menu-toc li a:hover, .menu-toc li.menu-toc-current a{
				background: <?=  adjustBrightness($catalogo['color'], -50)?>;
			}
			
			.bb-custom-wrapper nav span, .menu-button {
				background:<?=$catalogo['color']?>;
			}
			
			.banner_catalogo{
				position: absolute;
				left: 130px;
				top: 0px;
				z-index: 1000;
				width: 600px;
			}
		</style>
		
		<script src="<?=base_url()?>js/jquery-1.8.3.min.js"></script>
<!--		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>-->
		<script src="<?=base_url()?>js/bookblock/jquery.mousewheel.js"></script>
		<script src="<?=base_url()?>js/bookblock/jquery.jscrollpane.min.js"></script>
		<script src="<?=base_url()?>js/bookblock/jquerypp.custom.js"></script>
		<script src="<?=base_url()?>js/bookblock/jquery.bookblock.js"></script>
		<script src="<?=base_url()?>js/bookblock/page.js"></script>
		<script>
			$(function() {

				Page.init();

			});
			
			
			var waitForFinalEvent = (function () {
				var timers = {};
				return function (callback, ms, uniqueId) {
				  if (!uniqueId) {
					uniqueId = "Don't call this twice without a uniqueId";
				  }
				  if (timers[uniqueId]) {
					clearTimeout (timers[uniqueId]);
				  }
				  timers[uniqueId] = setTimeout(callback, ms);
				};
			})();
			
			
			function actualizarDimensiones(){
				var radio = $("body").width() / 1366; //1232;
				var altoLimite = 796; //$("body").height();
				if( radio * altoLimite > ($("body").height()-50) ) //> altoLimite )
					radio = ($("body").height()-50) / altoLimite;
				var padLeft = ($("body").width() - (radio*1232)) / 2;
					//alert('radio '+radio+ ", body:"+$("body").width());
				var css = {
					'transform': 'scale('+radio+','+radio+')', 
					'transform-origin': 'left top'};
				$(".micontenido").css(css);
				$(".scroller").css({"padding-left" : padLeft});
				//alert($('body').width());
			}
			
			$(document).ready(function(){
				
				actualizarDimensiones();
				
				$(window).resize(function(){
					waitForFinalEvent(function(){
						actualizarDimensiones();
					}, 500, "some unique string");
				});
			});
		</script>
	</body>
</html>


<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>