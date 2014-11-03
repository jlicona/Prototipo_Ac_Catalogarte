<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
/**
 * Variables recibidas del controlador:
 * @param $hojas Arreglo de registros de BD con hojas del artículo ej: foreach(){$registro->campo}
 * @param $articulo Registro de la BD del artículo actual ej: $registro->campo
 * @param \Plantilla_model $plantilla_model Fuente de datos de Plantillas
 */
?>
	<?php if (!empty($resultados)) { ?>    
	<div class="caja_informacion">
	<?= $resultados; ?>    
	</div>    
	<?php }//fin if error  ?>

<?php
	echo form_open('admin/hojas/submit'); 	
	$total_paginas = Count($hojas);
?>

<!-- INICIO TABLA -->
<center>
<br>
<table class="tabla_forma">
<tr><td width="20%" valign="bottom"> 
	<!-- TITULO -->
	<center><p class="catalogo_titulo">ADMINISTRAR PAGINA</p></center>
</td><td width="80%"> 
	
	<!-- INSTRUCCIONES -->
	<br>
	<p class="txt_instrucciones">A continuacion se listan las hojas del Artículo <b><?=$articulo->nombre?></b>.  Seleccione la accion que desea realizar desde la columna Acciones de cada Hoja o agregue una Hoja Nueva con el boton para dicho fin.</p>
	<br>
	<p class="txt_instrucciones">Mostrando las hojas del Articulo actual:</p>
	<br>

</td></tr>
<tr><td colspan="2">

	<!-- CONTENIDO -->
	<table class="tabla">
		<tr>
			<th># PAGINA</th>
			<th>PLANTILLA</th>
			<th>ACCIONES</th>
		</tr>
	<?php	foreach($hojas as $hoja){ ?>
		<tr>
			<td><?=$hoja->orden?></td>
			<td><?=$plantilla_model->get($hoja->id_plantilla)->nombre?></td>
			<td>
				<center>
				<a class="lnk_icono" href="#"><img src="<?=base_url()?>img/iconos/1.VER.png" title="Ver"></a>
				<?php
					echo anchor('admin/hojas/editar/'.$hoja->id, '<img src="' . base_url() . 'img/iconos/2.EDITAR.png" title="Editar">', 'class="lnk_icono"');
					if ($hoja->orden != 1) echo anchor('admin/hojas/subir/'.$hoja->id, '<img src="' . base_url() . 'img/iconos/3.SUBIR.png" title="Subir">', 'class="lnk_icono"'); 
					else echo '<a class="lnk_icono"><img src="' . base_url() . 'img/iconos/invisible.png"></a>';
					if ($hoja->orden != $total_paginas) echo anchor('admin/hojas/bajar/'.$hoja->id, '<img src="' . base_url() . 'img/iconos/4.BAJAR.png" title="Bajar">', 'class="lnk_icono"'); 
					else echo '<a class="lnk_icono"><img src="' . base_url() . 'img/iconos/invisible.png"></a>';
				?>
				<a class="lnk_icono" href="#"><img src="<?=base_url()?>img/iconos/5.ELIMINAR.png" title="Eliminar"></a>			
				</center>
			</td>
		</tr>
	<?php	}  ?>
		<tr>
			<td></td>
			<td></td>
			<td>
			<center>
			<?=anchor('admin/hojas/nuevo/'.$articulo->id, '<img src="' . base_url() . 'img/iconos/14.AGREGAR.png" title="Agregar Hoja">AGREGAR HOJA', 'class="lnk_icono"')?>
			</center>
			</td>
		</tr>
	</table>
	<br>
	<center><p>
		<input id="id_articulo" name="id_articulo" type="hidden" value="<?=$articulo->id?>"> 
		<input id="btnRegresar" name="btnRegresar" type="button" value="Regresar" onclick="goBack()"> 
	</p></center>
	<br>
</table>
	<br>
	<br>
</td></tr>
</table>
<br>
</center>
<!-- FIN TABLA -->

</form>

<script>

	function goBack() {
		window.location.href = "<?=site_url()."/admin/articulos"?>";
	}

	$(document).ready(function () {

	});
</script>