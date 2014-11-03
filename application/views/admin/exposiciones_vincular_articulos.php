<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
/**
 * Variables recibidas del controlador:
 * @param int $idExposicion Identificador de la exposición
 * @param array $articulosVinculables Arreglo de registros de BD con exposiciones ej: foreach(){$registro->campo}
 * @param array $articulosVinculados Arreglo de registros de BD con exposiciones ej: foreach(){$registro->campo}
 */

echo form_dropdown('cmbArticulo', $articulosVinculables, "0", 'id="cmbArticulo"');
?>
<input type="button" id="btnVincular" value="Vincular" /> <input type="button" id="btnCerrar" value="Cerrar" />

<div style="margin-top: 30px">
<?php if( count($articulosVinculados) <=0){ ?>
	<p>No hay artículos vinculados</p>
<?php }else{ ?>

<p>Artículos vinculados:</p>
<ul>
	<?php foreach($articulosVinculados as $articulo){ ?>
	<li><?="$articulo->nombre ($articulo->autor)"?></li>
	<?php }//fin ciclo ?>
</ul>
<?php } ?>
</div>

<script>

	$(document).ready(function () {
		$("#btnCerrar").click(function(){
			$("#modal_vinculos").fadeOut(500);
		});
		
		$("#btnVincular").click(function(){
			if( $("#cmbArticulo").val() == 0 ){
				alert('Debe seleccionar un artículo');
				return;
			}
			var parametros = { id_articulo : $("#cmbArticulo").val() };
			var url = "<?=site_url()?>/admin/exposiciones/vincular_articulos/"+<?=$idExposicion?>;
			var xhr = $.post(url, parametros);
			xhr.done( function(datos) {
				$("#popup_vinculos").html(datos);
			});
			xhr.fail(function(jqXHR, textStatus, errorThrown) { 
				alert('No fue posible comunicarse con el servidor:'+jqXHR.responseText);
			});
		});
	});
</script>