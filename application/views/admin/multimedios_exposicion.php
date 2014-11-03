<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
/**
 * Variables recibidas del controlador:
 * @param bool $soloImprimirDetalles Indica que solo se imprime la tabla de detalles
 * @param object $exposicion Registro en base de datos de una exposición ej. {$exposicion->campo}
 * @param array $imagenesBaseExpo Lista de imagenes de exposición
 * @param array $autores Lista de autores donde cada uno tiene listas de obras y cada obra listas de multimedios
 */
?>

<?php

if(!$soloImprimirDetalles) echo '<br><center>' . anchor('admin/exposiciones/', 'Regresar', 'class="boton_matriz"'). '<br></center>' ;
//echo anchor('admin/exposiciones/', 'Regresar');
?>
<br>
<table class="tabla" id="detalles_multimedios" style="width: 600px">
	<thead>
		<tr>
			<th>Exposición</th>
			<th style="width:150px">Multimedio</th>
		</tr>
	</thead>
	<?php foreach($imagenesBaseExpo as $base){ ?>
	<tr>
		<td><?=$base->titulo?></td>
		<td>
			<?php
			$cambiarAgregar = "Agregar";
			$icono = "14.AGREGAR.png";
			$claseLink = "nuevo_multimedio_expo";
			$valorEscondido = $base->tipo;
			$multimedio = $base->multimedio;
			if(!empty($multimedio) ){ //si tiene multimedio asignado
				$cambiarAgregar = "Cambiar";
				$icono = "2.EDITAR.png";
				$claseLink = "actualizar_multimedio";
				$valorEscondido = $multimedio->id;
				$path = !empty($multimedio->path) ? base_url().$multimedio->path.$multimedio->nombre_archivo : "";
				echo '<img class="thumbnail" '.($path==''?'':'src="'.$path.'"').' />';
			}
			?>
			<a href="javascript:return;" class="<?=$claseLink?> lnk_icono" >
				<img src="<?=base_url()?>img/iconos/<?=$icono?>" title="<?=$cambiarAgregar?> Multimedio" />
			</a>
			<input type="hidden" value="<?=$valorEscondido?>" />
		</td>
	</tr>
	<?php } //fin ciclo bases expo ?>
	
	<?php foreach($autores as $autor){ ?>
	<tr><th>Autor: <?=$autor->nombre;?></th><th>Multimedio</th></tr>
		<?php foreach($autor->obras as $obra){ ?>
		<tr>
			<td>Obra:<?=$obra->titulo;?></td>
			<td>
				<a href="javascript:return;" class="nuevo_multimedio_obra lnk_icono" >
					<img src="<?=base_url()?>img/iconos/14.AGREGAR.png" title="Agregar Multimedio a esta obra" />
				</a>
				<input type="hidden" value="<?=$obra->id?>" />
			</td>
		</tr>
			<?php foreach($obra->multimedios as $multimedio){ ?>
		<tr><td></td>
			<td>
				<?php
				$id = $multimedio->id;
				$path = !empty($multimedio->path) ? base_url().$multimedio->path.$multimedio->nombre_archivo : "";
				?>
				<img class="thumbnail" <?=$path==''?'':'src="'.$path.'"'?> />
				<a href="javascript:return;" class="actualizar_multimedio lnk_icono" >
					<img src="<?=base_url()?>img/iconos/2.EDITAR.png" title="Cambiar Multimedio" />
				</a>
				<input type="hidden" value="<?=$id?>" />
			</td></tr>
			<?php } //fin ciclo multimedios ?>
		</tr>
		<?php }//fin ciclo obras ?>
	<?php } //fin ciclo autores ?>
</table>

<?php if($soloImprimirDetalles){return;} 

//echo anchor('admin/exposiciones/', 'Regresar');
echo '<br><center>' . anchor('admin/exposiciones/', 'Regresar', 'class="boton_matriz"'). '<br></center>' ;

?>

<div class="fondo_modal" id="modal_multimedio">
    <div class="popup_modal" id="popup_multimedio">
		<?php echo form_open_multipart('admin/multimedios/subir/'.$exposicion->id,'id="frmMultimedio"') ?>
		<p class="catalogo_titulo">Carga de multimedio</p>
		<br>
        <p class="txt_instrucciones" id="desc_actualizar">Seleccione un archivo que actualizará al multimedio seleccionado</p>
		<p class="txt_instrucciones" id="desc_nuevo">Seleccione un archivo para el nuevo multimedio</p>
		<br>
        <input type="file" name="userfile" id="userfile" size="20" />
		<input type="hidden" name="es_nuevo" id="es_nuevo" valuie="" />
		<input type="hidden" name="tipo_nuevo" id="tipo_nuevo" valuie="" /><!--obra, principal1,2,3,4,5, portada, contraportada-->
		<input type="hidden" name="id_obra" id="id_obra" valuie="" />

		<input type="hidden" name="id_multimedio_existente" id="id_multimedio_existente" valuie="" />
		
		<div class="botones">
			<input type="button" id="cerrar" value="Cancelar" />
			<input type="button" id="guardar" value="Guardar" />			
		</div>		
		</form>
    </div>
</div>

<div class="fondo_modal" id="modal_lightbox">
    <div class="popup_modal" id="popup_lightbox">
    </div>
</div>

<script>
	
	$(document).ready(function () {
		
		function habilitarEventosTabla(){
			$(".actualizar_multimedio").click(function(){
				$("#desc_nuevo").hide();
				$("#desc_actualizar").show();
				$("#es_nuevo").val(0);
				$("#id_multimedio_existente").val($(this).next().val());
				abrirModal();
			});
			$(".nuevo_multimedio_obra").click(function(){
				$("#desc_nuevo").show();
				$("#desc_actualizar").hide();
				$("#es_nuevo").val(1);
				$("#tipo_nuevo").val('obra');
				$("#id_obra").val($(this).next().val());
				abrirModal();
			});
			$(".nuevo_multimedio_expo").click(function(){
				$("#desc_nuevo").show();
				$("#desc_actualizar").hide();
				$("#es_nuevo").val(1);
				$("#tipo_nuevo").val($(this).next().val());
				abrirModal();
			});
			
			$(".thumbnail").click(function(){
				var img = $("<img/>");
				$(img).attr("src", $(this).attr('src')).load(function(){
					$("#popup_lightbox").empty();
					$("#popup_lightbox").append($(this));
					var ancho = $(this).width();
					if(ancho == 0)
						ancho = this.width;
					var alto = $(this).height();
					if(alto == 0)
						alto = this.height;
					var relacion = ancho/alto;
					if(ancho>$(window).width()){
						ancho = $(window).width();
						alto = ancho * relacion;
					}
					if(alto>$(window).height()){
						alto = $(window).height();
						ancho = alto * relacion;
					}
					//alert("ancho "+ancho+" alto "+alto);
					$(this).css({"width":ancho, "height":alto});
					var left = ($(window).width()/2)-(ancho/2);
					var top = $(window).height()/2-(alto/2);
					$("#popup_lightbox").css({"left":left, "top":top, "width":ancho+"px", "height":alto});
					$("#modal_lightbox").width($(document).width()).height($(document).height()).fadeIn(500);
				});
			});
		}//fin habilitar
		
		$("#modal_multimedio").hide();
		$("#modal_lightbox").hide();
		habilitarEventosTabla();
		
		function abrirModal(){
			$("#userfile").val('');
			var left = ($(window).width()/2)-($("#popup_multimedio").width()/2);
			var top = $(window).height()/2-($("#popup_multimedio").height()/2);
			$("#popup_multimedio").css({"left":left, "top":top});
			
			$("#modal_multimedio").width($(document).width()).height($(document).height()).fadeIn(500);
		}
		
		function cerrarModal(){
			$("#modal_multimedio").fadeOut(500);
		}
		$("#cerrar").click(cerrarModal);
		
		$("#guardar").click(function(){
			if($("#userfile").val()==''){
				alert('Debe seleccionar un archivo')
				return;
			}
			
			var formulario = new FormData($("#frmMultimedio")[0]);
			formulario.append('userfile', $("#userfile")[0].files[0]);
			/*formulario.append('es_nuevo', $("#es_nuevo").val());
			formulario.append('tipo_nuevo', $("#tipo_nuevo").val());
			formulario.append('id_obra', $("#id_obra").val());
			formulario.append('id_multimedio_existente', $("#id_multimedio_existente").val());*/
			/*$.each($("#userfile")[0].files, function(i,archivo){formulario.append('userfile-'+i,archivo);});*/			
			var parametros = {
				url: $("#frmMultimedio").attr('action'),
				data: formulario,
				cache: false,
				contentType: false,
				processData: false,
				type: 'POST'
			};
			var xhr = $.ajax(parametros);
			//var xhr = $.post($("#frmMultimedio").attr('action'), $("#frmMultimedio").serialize());
			xhr.done( function(datos) {
				$("#detalles_multimedios").html(datos);
				habilitarEventosTabla();
				cerrarModal();
			});
			xhr.fail(function(jqXHR, textStatus, errorThrown) { 
				alert('No fue posible comunicarse con el servidor:'+jqXHR.responseText);
			});
		});
		
		$("#modal_lightbox").click(function(){$(this).fadeOut(500);});

	});
</script>