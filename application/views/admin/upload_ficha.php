
<?php if (!empty($resultados)) { ?>    
<div class="caja_informacion">
<?=$resultados;?>    
</div>    
<?php }//fin if error ?>


<?php echo form_open_multipart('admin/upload_ficha/subir');?>
<!-- INICIO TABLA -->
<center>
<br>
<table class="tabla_forma">
<tr><td width="20%" valign="bottom"> 
	<!-- TITULO -->
	<center><p class="catalogo_titulo">Subir Nueva Exposicion</p></center>
</td><td width="80%"> 
	
	<!-- INSTRUCCIONES -->
	<br>
	<p class="txt_instrucciones">Genere el archivo ZIP que contiene la Ficha Tecnica (Archivo en Excel) y el conjunto de Multimedios que representan sus obras.</p>
	<br>
	<br>
</td></tr>
<tr><td colspan="2">

	<!-- CONTENIDO -->
	<table class="tabla">
		<tr><th>ARCHIVO ZIP</th><td><center><input type="file" name="userfile" size="20" /></center></td></tr>		
	</table>
	<br />
	<br />
	<center>
	<p>
	<input id="btnCancelar" onclick="goBack()" name="btnCancelar" type="button" value="Cancelar"> 
	<input type="submit" value="Subir ficha técnica"  />
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

    $(document).ready(function(){

    });

</script>