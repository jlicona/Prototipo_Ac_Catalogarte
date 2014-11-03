<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
/**
 * Variables recibidas del controlador:
 * @param array $articulos Arreglo de registros de BD con articulos ej: foreach(){$registro->campo}
 * @param \Hoja_model $hoja_model Modelo de hojas de artículos
 */
?>
	<?php if (isset($resultados)) { ?>    
	<div class="caja_informacion">
	<?= $resultados; ?>    
	</div>    
	<?php }//fin if error  ?>



<?php echo form_open_multipart('admin/articulos'); 
if(count($articulos)<=0){
?>
<div class="caja_informacion">
	No existen resultados
</div>
<?php }else{ ?>

<!-- INICIO TABLA -->
<center>
<br>
<table class="tabla_forma">
<tr><td width="20%" valign="bottom"> 
	<!-- TITULO -->
	<center><p class="catalogo_titulo">ADMINISTRAR ARTICULOS</p></center>
</td><td width="80%"> 
	
	<!-- INSTRUCCIONES -->
	<br>
	<p class="txt_instrucciones">A continuacion se listan sus Articulos y otros Articulos Publicos.  Seleccione la accion que desea realizar desde la columna Acciones de cada Articulo.</p>
	<br>
	<p class="txt_instrucciones">Mostrando sus articulos:</p>
	<br>

</td></tr>
<tr><td colspan="2">

	<!-- CONTENIDO -->
	<table class="tabla">
	<tr>
		<th>NOMBRE</th>
		<th>AUTOR</th>
		<th>REFERENCIA</th>
		<th>HOJAS</th>
		<th>ACCIONES</th>
	</tr>
	<?php	foreach($articulos as $articulo){ ?>
		<tr>
			<td><?=$articulo->nombre?></td>
			<td><?=$articulo->autor?></td>
			<td><?=$articulo->referencia?></td>
			<td><center>
				<?=$hoja_model->getTotalConArticulo($articulo->id)?> 				
				<?=anchor('admin/hojas/por_articulo/'.$articulo->id, '<img src="' . base_url() . 'img/iconos/2.EDITAR.png" title="Editar">', 'class="lnk_icono"')?>
				</center>
			</td>
			<td>
				<center>
				<a class="lnk_icono" href="#"><img src="<?=base_url()?>img/iconos/1.VER.png" title="Ver"></a>
				<?=anchor('admin/articulos/editar/'.$articulo->id, '<img src="' . base_url() . 'img/iconos/2.EDITAR.png" title="Editar">', 'class="lnk_icono"')?>
				<a class="lnk_icono" href="#"><img src="<?=base_url()?>img/iconos/6.PUBLICAR.png" title="Publicar"></a>
				<a class="lnk_icono" href="#"><img src="<?=base_url()?>img/iconos/7.OCULTAR.png" title="Ocultar"></a>
				<a class="lnk_icono" href="#"><img src="<?=base_url()?>img/iconos/8.CATALOGO.png" title="Catalogo"></a>
				<a class="lnk_icono" href="#"><img src="<?=base_url()?>img/iconos/5.ELIMINAR.png" title="Eliminar"></a>
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

<?php }//fin si hay resultados ?>

</form>

<script>

	$(document).ready(function () {

	});
</script>