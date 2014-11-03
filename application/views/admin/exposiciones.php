<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
/**
 * Variables recibidas del controlador:
 * @param array $exposiciones Arreglo de registros de BD con exposiciones ej: foreach(){$registro->campo}
 * @param \Condicion_model $condicion_model Fuente de datos de condiciones
 * @param \Sede_model $sede_model Fuente de datos de sedes
 */
?>
	<?php if (!empty($resultados)) { ?>    
	<div class="caja_informacion">
	<?= $resultados; ?>    
	</div>    
	<?php }//fin if error  ?>



<?php echo form_open_multipart('admin/exposiciones/submit'); 
if(count($exposiciones)<=0){
?>
<div class="caja_informacion">
	No existen resultados
</div>
<?php }else{ ?>
<!-- INICIO TABLA -->
<center>
<br>
<table class="tabla_forma">
<tr><td width="30%" valign="bottom"> 
	<!-- TITULO -->
	<center><p class="catalogo_titulo">ADMINISTRAR EXPOSICIONES</p></center>
</td><td width="70%"> 
	
	<!-- INSTRUCCIONES -->
	<br>
	<p class="txt_instrucciones">A continuacion se listan las exposiciones disponibles.  Desde la columna Acciones, puede editar las exposiciones y sus catalogos.</p>
		<br>
	<p class="txt_instrucciones">
		Mostrando sus exposiciones de la sede:
		<select name ="cmbSedes" id="cmbSedes">
			<option id="0">Todas</option>
			<?php	foreach($sede_model->getTodos() as $sede){ ?>
				<option id="<?=$sede->id?>"><?=$sede->nombre?></option>
			<?php	} ?>
		</select>
	</p>
	<br>
</td></tr>
<tr><td colspan="2">

	<!-- CONTENIDO -->
	<table class="tabla">
		<tr>
			<th></th>
			<th>EXPOSICION</th>
			<th>DESDE</th>
			<th>HASTA</th>
			<th>CONDICION</th>
			<th>ACCIONES</th>
		</tr>
	<?php	foreach($exposiciones as $exposicion){ 
		$condicion = $condicion_model->get($exposicion->id_condicion);
	?>
		<tr>
			<td>
				<div style="width: 20px; height: 20px; background-color: <?=$condicion->borde?>"></div>
			</td>
			<td><?=$exposicion->nombre?></td>
			<td><?=$exposicion->fecha_inicio?></td>
			<td><?=$exposicion->fecha_fin?></td>
			<td><?=$condicion->nombre?></td>
			<td style="width: 250px">
				<center>
				<a class="lnk_icono" href="#" onclick='window.open ("<?=base_url()?>index.php/catalogo/ver/" + <?=$exposicion->id?>, "_blank")' ><img src="<?=base_url()?>img/iconos/1.VER.png" title="Ver Catalogo"></a>
				<a class="lnk_icono" href="#"><img src="<?=base_url()?>img/iconos/9.DESCARGAR.png" title="Descargar Ficha"></a>
				<?php 
				if($exposicion->visible){
					echo anchor('admin/exposiciones/ocultar/'.$exposicion->id, '<img src="' . base_url() . 'img/iconos/7.OCULTAR.png" title="Ocultar exposición">', 'class="lnk_icono"');
				}else{
					echo anchor('admin/exposiciones/publicar/'.$exposicion->id, '<img src="' . base_url() . 'img/iconos/6.PUBLICAR.png" title="Publicar exposición">', 'class="lnk_icono"');
				}?>
				<?=anchor('admin/multimedios/en_exposicion/'.$exposicion->id, '<img src="' . base_url() . 'img/iconos/10.MULTIMEDIOS.png" title="Administrar Multimedios">', 'class="lnk_icono"')?>
				<a class="lnk_icono" href="#"><img src="<?=base_url()?>img/iconos/11.CAMBIAR-FECHA.png" title="Cambiar Fechas"></a>
				<a class="lnk_icono" href="#"><img src="<?=base_url()?>img/iconos/12.CONDICION.png" title="Cambiar Condicion"></a>
				<a class="lnk_icono" href="javascript:return;"><img class="vincular" id-expo="<?=$exposicion->id?>" src="<?=base_url()?>img/iconos/15.VINCULAR.png" title="Vincular con Articulos"></a>
				</center>
			</td>
		</tr>
	<?php	}//fin ciclo ?>
	</table>
	<br>
	<br>
</td></tr>
</table>
<br>
</center>
<!-- FIN TABLA -->

<div class="fondo_modal" id="modal_vinculos">
    <div class="popup_modal" id="popup_vinculos" style="overflow: auto;">
    </div>
</div>

<?php }//fin si hay resultados ?>

</form>

<script>

	$(document).ready(function () {
		$("#modal_vinculos").hide();
		
		$(".vincular").click(function(){
			var left = ($(window).width()/2)-($("#popup_vinculos").width()/2);
			var top = $(window).height()/2-($("#popup_vinculos").height()/2);
			$("#popup_vinculos").css({"left":left, "top":top});
			
			$("#modal_vinculos").width($(document).width()).height($(document).height()).fadeIn(500);
			
			var xhr = $.get("<?=site_url()?>/admin/exposiciones/vincular_articulos/"+$(this).attr("id-expo"));
			xhr.done( function(datos) {
				$("#popup_vinculos").html(datos);
			});
			xhr.fail(function(jqXHR, textStatus, errorThrown) { 
				alert('No fue posible comunicarse con el servidor:'+jqXHR.responseText);
			});
		});
	});
</script>