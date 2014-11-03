<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
/**
 * Variables recibidas del controlador:
 * @param array $exposiciones Arreglo de registros con exposiciones ej: foreach(){$registro->campo}
 */
?>

<div style="background:'777'; font-family:'Raleway'; font-weight: 100; font-size: 20px;" >	
	<center>
	
	
	<table>
		<tr><td valign="top">
			<br><input class="boton_matriz" style="width:100%;" type="button" onclick="switchHoneyComb('disciplina');" value="DISCIPLINA"> 
			<input class="boton_matriz" style="width:100%; margin-top:15px;" type="button" onclick="switchHoneyComb('condicion');" value="CONDICIÓN"> 
			<input class="boton_matriz" style="width:100%; margin-top:15px;" type="button" onclick="switchHoneyComb('sede');" value="SEDE"> 
			<input class="boton_matriz" style="width:100%; margin-top:15px;" width="100%" type="button" onclick="switchHoneyComb('año');" value="AÑO">
			<input class="boton_matriz" style="width:100%; margin-top:15px; background-color: #F8C01B;" type="button" onclick="alert('Abrirá un panel de búsqueda por: autor (nombre, año de nacimiento, año de defuncion, red social), obra (nombre, Título, Año de elaboración, Técnica, Dimensiones), palabra clave, título exposicion, nombre de artículos, corriente artística');" value="BUSCAR...">
			<br><br><img title="Leyenda" src="<?=base_url()?>img/temp/GridLeyenda.png" width="200" height="510">
		</td><td>
			<svg width="1100" height="955" id="svgs">
			</svg>
		</td></tr>
	</table>
	</center>
</div>

<script type="text/javascript" src="<?=base_url()?>js/hex/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/hex/snap.svg-min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/hex/Hexagono.js"></script>
<!-- <script type="text/javascript" src="<?=base_url()?>js/hex/data.js"></script> -->
<script type="text/javascript" src="<?=base_url()?>js/hex/app.js"></script>
<script type="text/javascript">
	//Levantar el DATAJS y guardar en variable 	var datos = Mi servicio
	//Para ahora si poder cargar resetSVG
	var datos = [];
	$.ajax({
		url: "<?=base_url()?>index.php/servicios/exposiciones",
		type: "GET",
		data: "",  
		success: function(response){ 
		datos = response;
		cargarListas();
		resetSVG();
		ordenar("sede");
		render();
	},
		error: function( jqXHR ){  console.log(jqXHR); },
		complete: function( jqXHR ){  /* algo */ }
	});
</script>

<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>