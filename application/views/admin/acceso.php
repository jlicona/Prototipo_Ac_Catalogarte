<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
/**
 * Variables recibidas del controlador:
 * @param $articulo Registro de la BD del artículo actual extraido como $registro->campo.  Si el $articulo->id = 0, significa que es un articulo nuevo.
 * @param $resultados indica errores
 */
?>
	<?php if (!empty($resultados)) { ?>    
	<div class="caja_informacion">
	<?= $resultados; ?>    
	</div>    
	<?php }//fin if error  ?>

<?php echo form_open('admin/acceso/ingresar');?>
<!-- 	
		A donde sea que esta madre vaya, debera prender estas tres dos banderas:

			$estaAutenticado = false;
			$esAdministrador = false;
		
		Adicionalmente, como se agregara el $idMenuSeleccionado para cada pagina. ?
		
-->

<!-- INICIO TABLA -->
<center>
<br>
<table class="tabla_forma">
<tr><td width="20%" valign="bottom"> 
	<!-- TITULO -->
	<center><p class="catalogo_titulo">ACCESO</p></center>
</td><td width="80%"> 
	
	<!-- INSTRUCCIONES -->
	<br>
	<p class="txt_instrucciones">Introduzca sus credenciales para ingresar a la sección de Adminsitración de CATALOGARTE.</p>
	<br>
	<br>
</td></tr>
<tr><td colspan="2">

	<!-- CONTENIDO -->
	<table class="tabla">
		<tr><th>NOMBRE DE USUARIO</th><td><center><input type="text" id="nombre" name="nombre" maxlength="100" value="" size="20"></center></td></tr>
		<tr><th>CONTRASEÑA</th><td><center><input type="password" id="contrasena" name="contrasena" maxlength="100" value="" size="20"></center></td></tr>
	</table>
	<br>
	<center>
	<p>
	<input id="btnIngresar" type="submit" value="Ingresar">
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

	$(document).ready(function () {	
		$('#btnIngresar').on('click', function(e){
			if ( ($('#nombre').val().trim().length <= 0) || ($('#contrasena').val().trim().length <= 0) ) {
				alert('Debe capturar sus credenciales para acceder al Sistema.');
				//$('.caja_informacion').html('Debe capturar el por lo menos el nombre del Articulo.');
				e.preventDefault();
				return;
			}
		});
		
	});
</script>