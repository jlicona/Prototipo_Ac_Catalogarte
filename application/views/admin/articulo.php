<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
/**
 * Variables recibidas del controlador:
 * @param $articulo Registro de la BD del artículo actual extraido como $registro->campo.  Si el $articulo->id = 0, significa que es un articulo nuevo.
 * @param $resultados indica errores
 */
?>
	<?php if (isset($resultados)) { ?>    
	<div class="caja_informacion">
	<?= $resultados; ?>    
	</div>    
	<?php }//fin if error  ?>

<?php echo form_open('admin/articulos/guardar');?>


<!-- INICIO TABLA -->
<center>
<br>
<table class="tabla_forma">
<tr><td width="20%" valign="bottom"> 
	<!-- TITULO -->
	<center><p class="catalogo_titulo">ARTICULO</p></center>
</td><td width="80%"> 	
	<!-- INSTRUCCIONES -->
	<br>
	<p class="txt_instrucciones">Agrege o modifique los campos que se solicitan a continuacion para agregar un Nuevo Articulo.  Podra alterar el contenido al mismo desde la vista Articulos -> Hojas.</p>
	<br>
	<p class="txt_instrucciones">Nombre: Titulo del articulo. Maximo 100 Caracteres</p>
	<p class="txt_instrucciones">Autor: Nombre del Autor de este articulo. Maximo 100 Caracteres</p>
	<p class="txt_instrucciones">Referencia: Alguna informacion extra del articulo, como fecha de publicacion, editorial, edicion, ISBN u otros detalles del Articulo. Maximo 300 Caracteres</p>
	<br>
</td></tr>
<tr><td colspan="2">
	<!-- CONTENIDO -->
	<table class="tabla">
		<tr><th>NOMBRE*</th><td><center><input type="text" id="nombre" name="nombre" maxlength="100" value="<?=$articulo->nombre?>" size="50"></center></td></tr>
		<tr><th>AUTOR</th><td><center><input type="text" id="autor" name="autor" maxlength="100" value="<?=$articulo->autor?>" size="50"></center></td></tr>
		<tr><th>REFERENCIA</th><td><center><input type="text" id="referencia" name="referencia" maxlength="200" rows="3" value="<?=$articulo->referencia?>" size="50"></center></td></tr>
	</table>
	<br>
	<center>
	<p>
	<input id="id_articulo" name="id_articulo" type="hidden" value="<?=$articulo->id?>"> 
	<input id="btnCancelar" onclick="goBack()" name="btnCancelar" type="button" value="Cancelar"> 
	<input id="btnGuardar" type="submit" value="Guardar">
	</p>
	</center>
	<br>
	<br>
</td></tr>
</table>
</center>
<!-- FIN TABLA -->

</form>

<script>

	function goBack() {
		window.history.back()
	}

	$(document).ready(function () {	
		$('#btnGuardar').on('click', function(e){
			if($('#nombre').val().trim().length <= 0){
				alert('Debe capturar el por lo menos el nombre del Articulo.');
				//$('.caja_informacion').html('Debe capturar el por lo menos el nombre del Articulo.');
				e.preventDefault();
				return;
			}
		});
		
	});

</script>