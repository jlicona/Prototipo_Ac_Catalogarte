<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
/**
 * Variables recibidas del controlador:
 * @param array $plantillas Arreglo de plantillas disponibles
 * @param int $plantillaSeleccionada id de la plantilla a usar
 * 
 * @param object $hoja Registro de una hoja en base de datos
 * @param string $contextoJson Contexto en Json de la estructura de la hoja
 */

if($plantillaSeleccionada == NULL){ //Si no seleccionó plantilla
?>
<div id="seccion_plantilla">
	
	<?=form_open('','method="POST" id="frmPlantilla" ');?>
	<!-- INICIO TABLA -->
	<center>
	<br>
	<table class="tabla_forma">
	<tr><td width="20%" valign="bottom"> 
		<!-- TITULO -->
		<center><p class="catalogo_titulo">SELECCIONAR PLANTILLA HOJA ARTÍCULO</p></center>
	</td><td width="80%"> 
		
		<!-- INSTRUCCIONES -->
		<br>
		<p class="txt_instrucciones">Seleccione la plantilla de la nueva página que agregará a su artículo. Esta selección es permanente para esta hoja al guardarla.</p>
		<br>
		<br>
	</td></tr>
	<tr><td colspan="2" align="center">

		<!-- CONTENIDO -->
		<table class="tabla">
			<?php foreach($plantillas as $plantilla){ ?>
				<tr><td  align="center">
				<div class="opcion_plantilla">
					<input type="radio" name="rgPlantillas" value="<?=$plantilla->id?>" title="<?=$plantilla->nombre?>"  />
					<?=$plantilla->nombre?>
				</div>
				</td><tr>
			<?php } //fin ciclo plantillas ?>
		</table>
		<br><br>
		<?php 
			//echo anchor('admin/hojas/por_articulo/'.$hoja->id_articulo, 'Regresar');
			echo anchor('admin/hojas/por_articulo/'.$hoja->id_articulo, 'Regresar', 'class="boton_matriz"');
		?>
		<input type="button" id="btnSeleccionarPlantilla" value="Seleccionar"/>
		<br><br>
		</form>
	
	<script>
		$(document).ready(function(){
			$("#btnSeleccionarPlantilla").click(function(){
				var idPlantilla = $('input[name=rgPlantillas]:checked').val();
				if(idPlantilla === undefined){
					alert("Debe elegir una plantilla");
					return;
				}
				$("#frmPlantilla").submit();
			});
			
		});
	</script>
	</table>
	</center>
	<!-- FIN TABLA -->

</div>
<?php }else{ //hay plantilla seleccionada?>

<br>
<div id="seccion_pagina">
	<div id="elementos_visuales">
        <p class="txt_Enfasis_Contenido">Elementos</p>
        <div id="elementoTitulo" class="herramienta ui-widget-content">
			<p class="titulo_hoja">Título</p>
        </div>
         
        <div id="elementoParrafo" class="herramienta ui-widget-content">
			<p class="parrafo_hoja">Párrafo</p>
        </div>
		
		<div id="elementoCita" class="herramienta ui-widget-content">
			<p class="cita_hoja">Cita</p>
        </div>
		
		<div id="elementoImagen" class="herramienta ui-widget-content">
			<p style="text-align: center">
				<img src="<?=base_url()?>img/iconos/10.MULTIMEDIOS.png" alt="Imagen" width="30px" height="30px" />
			</p>
        </div>
		
		<p>
			<?=form_open("admin/hojas/guardar", 'method="POST" id="frmGuardar"');?>
			<?php echo anchor('admin/hojas/por_articulo/'.$hoja->id_articulo, 'Regresar', 'class="boton_matriz"'); ?>				  
			<br><br>
			<input type="button" value="Guardar Hoja" id="guardar" />
			<input type="hidden" name="parametros" id="parametros" value="" />
			</form>
		</p>
	</div>
    
	<div id="contenido_plantilla">

        <div id="pagina" style="clear:both;">
			<?php
			//TEORÍA: TODLO LO CONTENIDO EN ESTE DIV DINÁMICO Y ALGUNOS ESTÍLOS/JAVASCRIPT DEBEN SER RECIBIDOS POR JSON
			//PARAMETROS DUMMY
			$NUMERO_COLUMNAS = $plantillaSeleccionada; //Esto se deberá cambiar por un mapeo real
			
			$FORMATO_COLUMNAS = array(
				1 => array(
					(object)["ancho"=>520, "alto"=>50]
				),
				2 => array(
					(object)["ancho"=>250, "alto"=>50],
					(object)["ancho"=>250, "alto"=>50]
				),
				3 => array(
					(object)["ancho"=>166, "alto"=>50],
					(object)["ancho"=>166, "alto"=>50],
					(object)["ancho"=>166, "alto"=>50]
				)
			);
			$columnas = $FORMATO_COLUMNAS[$NUMERO_COLUMNAS];
			$numCol = 0;
			$numEspacio = 0;
			foreach($columnas as $columna){ //ciclo columnas
			?>
            <div id="columna<?=$numCol++?>" style="margin-left: <?=$NUMERO_COLUMNAS == 3 ? 7 : 10?>px; margin-right: <?=$NUMERO_COLUMNAS == 3 ? 7 : 10?>px;" class="columna_plantilla">
				<?php for($i=0; $i<14; $i++, $numEspacio++){ ?>
                <div id="espacio<?=$numEspacio?>" style="width:<?=$columna->ancho?>px; height:<?=$columna->alto?>px;" class="espacio_drop" id-contenido="<?=$numEspacio?>">
                </div>
				<?php } ?>
            </div>
			<?php } //fin ciclo columnas ?>
        
        </div><!-- pagina -->
        <div id="editor_html" class="editor"></div>
		<div id="editor_img" class="editor">
			<input type="file" name="userfile" id="userfile" size="40" />
			<a href="javascript:return;" id="btnActualizarImagen">Actualizar Imágen</a>
			<p>
				<a class="lnk_icono" href="javascript:return;" id="btnBorrarImagen" ><img src="<?=base_url()?>img/iconos/5.ELIMINAR.png" title="Borrar imagen" /></a>
			</p>
		</div>
		<div id="dummy" style="background-color: yellow; position: absolute;left:-1000px; top:800px; width:225px;"></div>
	</div><!-- contenido_plantilla -->
	
</div><!-- seccion_pagina -->

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/jquery-ui.css" >
<script src="<?=base_url()?>js/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/raptor-front-end.min.css" >
<script src="<?=base_url()?>js/raptor.min.js"></script>
<style type="text/css">
	#seccion_pagina{
		width: 900px;
		background-color: green;
	}
	/* Herramientas */
	#elementos_visuales{
		float:left;
		width: 150px;
		padding: 5px;
		border-style: solid 1px;
		border-color: #666666;

	}
	
	/* Estructura de la plantilla */
	#contenido_plantilla{
		float:left;
		/*width: 540px;
		height: 740px;*/
	}
	#pagina{
		clear: both;
		/* width: 540px //Ancho sale por el margen y ancho de columna_plantilla */
		height: 740px;
		background-color: #F8C01B;
	}
	/*.columna_plantilla{ // su ancho se asigna por programación
		float:left; 
		// Margin Left y Right se asignan por programación
		margin-top: 10px;
	}*/

	/*jqueryUI arrastrables*/
	.herramienta { 
		float: left;
		width: 100px;
		padding: 0.5em;
		margin: 10px 10px 10px 0;
		cursor: pointer;
	}
	/*.titulo_hoja{
		font-family: 'Raleway';
		font-weight: 500;
		font-size: 12px;
		text-align: left;
		background-color: white;
		padding: 10px;
	}
	.parrafo_hoja{
		font-family: 'Raleway';
		font-weight: 300;
		font-size: 10px;
		text-align: justify;
		background-color: white;
		padding: 10px;
	}
	.cita_hoja {
		font-family: 'Raleway';
		font-weight: 300;
		font-size: 8px;
		text-align: justify;
		background-color: white;
		padding: 10px;
	}
	.imagen_hoja { margin: auto; text-align: center; padding: 10px; background-color: white;}
	.imagen_hoja img{max-width: 100%; max-height: 680px;}
	
	.espacio_drop { //ancho y alto son asignados en servidor
		padding: 0px; 
		margin-left:0px; 
		margin-right:0px; 
		//background-color: #9bbb59;
		overflow: hidden;
		cursor: pointer;
		//border-top: 1px solid #e78f08;
	}*/
	.espacio_drop:hover , .espacio_drop_hover{
		background-color: #C28A00;
	}
	
	
	#editor_html , #editor_img{ 
		width: 150px; 
		padding: 0.5em; 
		position:absolute; left: 0; top:0;
		background-color: #F8C01B;
		border: #E68F8F double 3px !important;
	}
	#editor_img{width: auto;}
</style>

<script>

	function goBack() {
		window.history.back();
	}

	$(document).ready( function(){
		var jsonHoja = <?=$contextoJson?>; //arreglo de contenidos
		var construyendo = false;
		//$("#dummy").hide();
		
		function construir(){
			construyendo = true;
			//Asignacion de contenidos
			for(var c = 0; c< jsonHoja.length; c++){
				var contenido = jsonHoja[c];
				var idDestino = "#espacio"+contenido.posicion;
				var destino = $(idDestino);
				var idTipoHerramienta = "";
				switch(parseInt(contenido.id_contenido)){
					case 1:
						idTipoHerramienta = "elementoTitulo";
						break;
					case 2:
						idTipoHerramienta = "elementoParrafo";
						break;
					case 3:
						idTipoHerramienta = "elementoCita";
						break;
					case 4:
						idTipoHerramienta = "elementoImagen";
						break;
					default:
						idTipoHerramienta = "elementoParrafo";
						alert("Id de herramienta desconocida "+contenido.id_contenido);
				}
				crearContenido(destino, idTipoHerramienta, $("#"+idTipoHerramienta).html());
				if(contenido.referencia){ //asignar imagen
					$(destino).find("div").attr("id-multimedio", contenido.referencia);
					$(destino).find("img").attr("src", contenido.url)
							.load( {contenedor: destino}, function(e){
								abrirEditor( $(e.data.contenedor) );
								cambioContenido();//Por delay esto ojalá ocurra después de acabar asignación de los demas contenidos
								$(".editor").hide();
							});
				}else{ //asignar texto
					$(destino).html(contenido.texto);
				}
			}
			//Actualización de espacios
			for(var c=0; c<jsonHoja.length; c++){
				var contenido = jsonHoja[c];
				var idDestino = "#espacio"+contenido.posicion;
				abrirEditor( $(idDestino) );
				cambioContenido();
			}
			//alert("cargado");
			$(".editor").hide();
			construyendo = false;
		}//fin construir


		$("#guardar").click(function (){
			//RECORRER espacios
			var lista = [];
			var n = 0;
			$(".espacio_drop").each(function(index, el){
				if( tieneContenido(el) ){
					var idContenido, texto = null, referencia = null;
					if($(el).has(".titulo_hoja").length >0 ){
						idContenido = 1;
						texto = $(el).html();
					}else if( $(el).has(".parrafo_hoja").length >0 ){
						idContenido = 2;
						texto = $(el).html();
					}else if( $(el).has(".cita_hoja").length >0 ){
						idContenido = 3;
						texto = $(el).html();
					}else if( $(el).has(".imagen_hoja").length >0){
						idContenido = 4;
						referencia = $(el).find("div").attr("id-multimedio");
					}
					var obj = {
						posicion : $(el).attr("id-contenido"),
						id_contenido : idContenido,
						texto : texto,
						referencia : referencia
					};
					lista[n++] = obj;
				}
			});
			var parametros = {hoja: <?=json_encode($hoja, JSON_UNESCAPED_UNICODE)?>, estructura: lista};
			parametros.hoja.html = $("#pagina").prop("outerHTML");
			var json = JSON.stringify(parametros);
			$("#parametros").val(json);
			$("#frmGuardar").submit();
		});
		
		
		$( ".herramienta, .espacio_drop" ).draggable({ 
			revert: "invalid",
			helper: "clone",
			cursor: "crosshair",
			start: function(event, ui){
				$(".editor").hide();/*fadeOut(200);*/
				$(ui.helper).css({opacity:0.5});
			}
		});
		
		$( ".espacio_drop" ).droppable({
			activeClass: "ui-state-default",
			hoverClass: "espacio_drop_hover",//"ui-state-hover",
			tolerance: "pointer",
			drop: function( event, ui ) {
				if( $(ui.draggable).hasClass("espacio_drop") ){ //Si el contenido viene de otro contenido existente
					if( !tieneContenido(ui.draggable) ) return;
					//Intercambio entre espacios
					var tipoParaThis = $(ui.draggable).attr("tipo-contenido");
					var tipoParaDrag = $(this).attr("tipo-contenido");
					$(this).attr("tipo-contenido", tipoParaThis);
					$(ui.draggable).attr("tipo-contenido", tipoParaDrag);

					var htmlParaThis = $(ui.draggable).html();
					var htmlParaDrag = $(this).html();
					$(this).html(htmlParaThis);
					$(ui.draggable).html(htmlParaDrag);

					abrirEditor(ui.draggable);
					cambioContenido();
					abrirEditor(this);
					cambioContenido();
				}else{
					var idTipoHerramienta = $(ui.draggable).attr("id");
					var contenidoHtml = $(ui.draggable).html();
					crearContenido(this, idTipoHerramienta, contenidoHtml);
				}
				
				//$( this ).html($(ui.draggable)
				  /*.addClass( "ui-state-highlight" )
				  .find( "p" )
					.html( "Dropped!" );*/
			  }
		});
		$(".espacio_drop").click(function(){
			if( !tieneContenido(this)){//$(this).text().trim() === ""){
				$(".editor").hide();
				return;
			}
			abrirEditor(this);
			cambioContenido();
		});


		$(".editor").hide();

		var altoOriginalContenido = [];
		var anchoOriginalContenido = [];
		$(".espacio_drop").each(function(index, el){
			anchoOriginalContenido[parseInt($(el).attr("id-contenido"))] = $(el).width();
			altoOriginalContenido[parseInt($(el).attr("id-contenido"))] = $(el).height();
		});
		
		var espacioDestino = null;
		
		function abrirEditor(destino){
			$(".editor").hide();
			var idEditor = $(destino).attr("tipo-contenido") === "Imagen" ? "#editor_img" : "#editor_html";
			espacioDestino = destino;
			var pos = $(destino).offset();
			var posPag = $("#pagina").offset();
			$(idEditor).css({"top":pos.top-30, "left":posPag.left + $("#pagina").width()+30});
			if(idEditor === "#editor_html")
				$(idEditor).html($(destino).html());
			$(idEditor).show();$(idEditor).focus();//fadeIn(200, function(){$(this).focus();});
			//alert("alto es:"+$(this).height()+"\nInterno:"+$(this).prop("scrollHeight")+"\nDummy:"+$("#dummy").height());
			$(".raptor-ui").hide();//para quitar barra inútil que aparece siempre
		}
		
		function crearContenido(uiDestino, idTipoHerramienta, contenidoHtml){
			if(idTipoHerramienta == "elementoTitulo" || idTipoHerramienta == "elementoParrafo"
					|| idTipoHerramienta == "elementoCita"){
				
				$(uiDestino).attr("tipo-contenido", "Texto");
				$(uiDestino).html(contenidoHtml);
				if(!construyendo){
					abrirEditor(uiDestino);
					cambioContenido();
				}
			}else if ( idTipoHerramienta.indexOf("Imagen") >= 0 ){
				var html = '<div id-multimedio="0" class="imagen_hoja"><img src="<?=base_url()?>img/iconos/10.MULTIMEDIOS.png" /></div>';	
				$(uiDestino).attr("tipo-contenido", "Imagen");
				$(uiDestino).html(html);
				if(!construyendo){
					abrirEditor(uiDestino);
					cambioContenido();
				}
			}else{
				$(uiDestino).html("esto no deberia verse");
			}
		}//fin crearContenido

		function cambioContenido(){
			if(espacioDestino === null)
				return;
			if($(espacioDestino).attr("tipo-contenido") === "Texto")
				$(espacioDestino).html($("#editor_html").html());
			var altoActual = $(espacioDestino).height();
			$("#dummy").width($(espacioDestino).width());
			$("#dummy").html($(espacioDestino).html());
			var altoReal = $("#dummy").prop("scrollHeight");
					/*parseInt($("#dummy").css("padding-top")) 
							+ parseInt($("#dummy").css("padding-bottom"));*/
			//Recalculamos cuánto debe ocupar
			var idContenido = parseInt($(espacioDestino).attr("id-contenido"));
			var nuevoAlto = altoOriginalContenido[idContenido];
			var i;
			
			for(i = idContenido+1; idContenido < altoOriginalContenido.length && nuevoAlto < altoReal; i++){
				//Son de la misma columna?
				if( $("#espacio"+i).parent().attr("id") !== $(espacioDestino).parent().attr("id"))
					break;
				//if(estaOcupado(i))break;
				if( tieneContenido("#espacio"+i) )// $("#espacio"+i).text().trim() !== "")
					break;
				nuevoAlto += altoOriginalContenido[i];
				$("#espacio"+i).hide();
			}
			if(nuevoAlto < altoActual){ //Hay que hacer visibles algunos espacios
				while(i < altoOriginalContenido.length && !$("#espacio"+i).is(":visible")){
					//alert("mostrando "+i);
					$("#espacio"+i).show();
					i++;
				}
			}
			$(espacioDestino).height(nuevoAlto);
			//alert("debe crecer"+altoReal);
		}
		
		function tieneContenido(elemento){
			return ! ($(elemento).text().trim() === "" && $(elemento).has(".imagen_hoja").length ===0);
			/*if( $(elemento).has(".imagen_hoja").length >0 )
				return true;
			if( $(elemento).text().trim() !== "" )
				return true;
			$(elemento).html(""); //Por si acaso lo vaciamos (caso textos vacios con estílo
			return false;*/
		}
		

		$("#btnActualizarImagen").click(function(){
			if($("#editor_img input").val()==''){
				alert('Debe seleccionar un archivo');
				return;
			}
			var idMultimedio = $(espacioDestino).find("div").attr("id-multimedio");
			var formulario = new FormData();//$("#frmMultimedio")[0]);
			formulario.append('userfile', $("#editor_img input")[0].files[0]);
			formulario.append('id_multimedio', idMultimedio );
			/*formulario.append('es_nuevo', $("#es_nuevo").val());
			formulario.append('tipo_nuevo', $("#tipo_nuevo").val());
			formulario.append('id_obra', $("#id_obra").val());
			formulario.append('id_multimedio_existente', $("#id_multimedio_existente").val());*/
			/*$.each($("#userfile")[0].files, function(i,archivo){formulario.append('userfile-'+i,archivo);});*/			
			var parametros = {
				url: "<?=site_url()?>/admin/hojas/recibir_multimedio", //$("#frmMultimedio").attr('action'),
				data: formulario,
				cache: false,
				contentType: false,
				processData: false,
				type: 'POST'
			};
			var xhr = $.ajax(parametros);
			//var xhr = $.post($("#frmMultimedio").attr('action'), $("#frmMultimedio").serialize());
			xhr.done( function(salidaJson) {
				if(salidaJson.error !== ''){
					alert("Se recibió el error: "+salidaJson.error);
				}else{
					$(espacioDestino).find("img").attr("src", salidaJson.url)
							.load(function(){
								cambioContenido();
							});
					$(espacioDestino).find("div").attr("id-multimedio", salidaJson.id_multimedio);
					$("#editor_img input").val("");
				}
			});
			xhr.fail(function(jqXHR, textStatus, errorThrown) { 
				alert('No fue posible comunicarse con el servidor:'+jqXHR.responseText);
			});
		});
		
		$("#btnBorrarImagen").click(function(){
			$(espacioDestino).html("");
			cambioContenido();
			$("#editor_img").hide();
		});


		$("#editor_html").raptor({
			autoEnable: true,
			enableUi: false,
			unloadWarning: false,
			classes: 'raptor-editing-inline',
			plugins: {
				paste: false,
				/*textBold: true,
				textItalic: true,
				textUnderline: true,
				textStrike: true,
				textBlockQuote: true,
				textSizeDecrease: true,
				textSizeIncrease: true,
				dock: {
					docked: false,
					dockToElement: false
				},*/
				unsavedEditWarning: false
			},
			bind: {
				change: function(){
					cambioContenido();
				}
			}
		});
		
		construir();
	});
</script>

<?php }//fin si hay plantilla seleccionada ?>