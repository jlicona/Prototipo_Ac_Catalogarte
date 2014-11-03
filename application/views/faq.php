<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
/**
 * Variables recibidas del controlador:
 */
?>

<div align="center">
	<div style="width: 600px" align="justify">
		<p class="txt_Titulo_Seccion">PREGUNTAS FRECUENTES</p>
		<p class="txt_Enfasis">P: ¿Cómo puedo acceder a la sección de administración de la página?</p>
		<p class="txt_Contenido">R: Escribiendo en su dirección del navegador <a class="txt_Link" title="Administración" href="http://catalogarte.net/index.php/admin/acceso">catalogarte.net/index.php/admin/acceso</a>. Esta sección solo será accesible para el administrador del sistema y los editores de cada centro de trabajo, con sus respectivas credenciales.  La emisión de cuentas de usuario será responsabilidad del administrador del sistema.</p>
		<br>
		<p class="txt_Enfasis">P: ¿Puedo entrar con dispositivos móviles a la página?</p>
		<p class="txt_Contenido">R: Mientras el dispositivo que uses tenga <font class="txt_Enfasis_Contenido">acceso a Internet y un navegador actualizado</font>, podrás acceder a este sitio.  Actualmente, la página está pensada para su navegación a través de una computadora, sin embargo está diseñada con la flexibilidad para, eventualmente, adaptar su apariencia a la resolución del dispositivo que la visite.</p>
		<br>
		<p class="txt_Enfasis">P: ¿Qué no hace este prototipo que sí hará la versión final?</p>
		<p class="txt_Contenido">R: + <font class="txt_Enfasis_Contenido">Difundir</font> automáticamente información en las <font class="txt_Enfasis_Contenido">redes sociales</font> sobre las exposiciones.  
			<br>+ Generar miniaturas de las imágenes de las obras para eficientar rendimiento y permitir <font class="txt_Enfasis_Contenido">subir archivos de audio y video</font> para exposiciones multimedia. 
			<br>+ Generación automática de <font class="txt_Enfasis_Contenido">palabras clave</font>, basadas en los contenidos de la exposición, para enlazar literatura afín y sugerir la adquisición de libros desde la <font class="txt_Enfasis_Contenido">tienda EDUCAL</font>. 
			<br>+ Contar con las secciones:
			<br><font class="txt_Enfasis_Contenido">Búsqueda</font>[Filtrados de las exposiciones mostradas en la matriz de hexágonos por muy diversos criterios de las Exposiciones, Autores u Obras, soportados en la Base de Datos de la plataforma];
			<br><font class="txt_Enfasis_Contenido">Descarga PDF</font>[Para descargar los catálogos de las exposiciones en dicho formato];
			<br><font class="txt_Enfasis_Contenido">Protección de Derechos</font>[Para las obras que así lo especifiquen desde la Ficha Técnica (campo Derechos Abiertos), se agregará un mecanismo para la protección de los derechos de autor, ya sea agregando una marca de agua o sello con este fin, o implementando el mecanismo que el INBA determine
			<br><font class="txt_Enfasis_Contenido">Calendario</font> [para relacionar temporalmente las exposiciones por inaugurarse].
			<br><font class="txt_Enfasis_Contenido">Sedes</font>[para mostrar información sobre las sedes y su correspondiente sección de administración].
			<br><font class="txt_Enfasis_Contenido">Noticias</font>[para difundir anuncios y su correspondiente sección de administración].
			<br><font class="txt_Enfasis_Contenido">Sólo para los administradores del INBA</font> las secciones:
			<br><font class="txt_Enfasis_Contenido">Usuarios</font>[para administrar a los usuarios que alimentan la plataforma y sus permisos].
			<br><font class="txt_Enfasis_Contenido">Reportes</font>[para ofrecer información hasta ahora desconocida para el INBA que ayude a mejorar sus procesos operativos y logísticos].
			<br>+ <font class="txt_Enfasis_Contenido">Responsividad</font> a la resolución del dispositivo que navega y cuidado de la experiencia de usuarios de <font class="txt_Enfasis_Contenido">tabletas y teléfonos inteligentes</font>;
			<br>+ Total funcionalidad para los navegadores <font class="txt_Enfasis_Contenido">Chrome, Firefox, Internet Explorer y Safari.</font> versión escritorio y móvil.</p>
		<p class="txt_Enfasis">P: ¿Qué elementos componen la matriz de Exposiciones?</p>
		<img title="Elementos Matriz Exposiciones" src="<?=base_url()?>img/temp/FAQ-Matriz.png" width="600" height="561">
		<p class="txt_Enfasis">P: ¿Qué elementos tiene la ficha de una exposición?</p>
		<img title="Elementos Ficha Exposicion" src="<?=base_url()?>img/temp/FAQ-Ficha.png" width="600" height="561">
		<p class="txt_Enfasis">P: ¿Cómo se ordenan las exposiciones en la matriz?</p>
		<img title="Ordenamiento Matriz Exposiciones" src="<?=base_url()?>img/temp/GridLogica.png" width="600" height="730">
		<p class="txt_Enfasis">P: ¿En qué tecnologías se basa esta solución?</p>
		<p class="txt_Contenido">R: El servidor hace uso del framework de código abierto <font class="txt_Enfasis_Contenido"><i>CodeIgniter</i></font> para el desarrollo de aplicaciones web basadas en <font class="txt_Enfasis_Contenido"><i>PHP</i></font>.  La base de datos es <font class="txt_Enfasis_Contenido"><i>MySQL</i></font>.  Se usan técnicas de <font class="txt_Enfasis_Contenido"><i>Javascript</i> asíncrono</font> con <font class="txt_Enfasis_Contenido"><i>Json</i></font>, la librería <font class="txt_Enfasis_Contenido"><i>Jquery</i></font> y la librería de código abierto <font class="txt_Enfasis_Contenido"><i>Snap.svg</i></font> de Adobe para la matriz de exposiciones.  Otras tecnologías empleadas en el resto de la página son <font class="txt_Enfasis_Contenido"><i>Javascript</i></font> regular y <font class="txt_Enfasis_Contenido"><i>CSS</i></font>.</p>
		<p class="txt_Enfasis">P: ¿Dónde puedo probar este Prototipo?</p>
		<p class="txt_Contenido">R: El prototipo actual puede ser visto correctamente en <font class="txt_Enfasis_Contenido"><i>IOS Yosemite</i></font>, con las versiones más nuevas de los navegadores <font class="txt_Enfasis_Contenido"><i>Safari y Chrome</i></font>.  También funciona para <font class="txt_Enfasis_Contenido"><i>Windows Vista, Windows 7 y Windows 8</i></font> en la última versión de los navegadores <font class="txt_Enfasis_Contenido"><i>Chrome y Firefox</i></font>.  La resolución <font class="txt_Enfasis_Contenido"><i>mínima</i></font> sugerida para navegar es de <font class="txt_Enfasis_Contenido"><i>1280 x 720 px</i></font>.</p>		

		<p class="txt_Enfasis">P: ¿Cuales son las restricciones de este Prototipo para crear una nueva Exposición?</p>
		<p class="txt_Contenido">R: 
		<br>1. Se detectó un problema al guardar la ficha técnica entre los sistemas operativos <font class="txt_Enfasis_Contenido"><i>IOS y Windows</i></font>.  Aquí están cada una de las versiones para su descarga: 
		<a class="txt_Link" title="Descargar Ficha Técnica IOS" href="http://catalogarte.net/archivos/ios/ficha_tecnica.xls">Versión IOS  </a> y 
		<a class="txt_Link" title="Descargar Ficha Técnica Windows" href="http://catalogarte.net/archivos/windows/ficha_tecnica.xls">Versión Windows </a>
		<br>2. Sólo se pueden subir fotografías en formato <font class="txt_Enfasis_Contenido"><i>PNG, JPG o GIF</i></font>, no se soportan Audios ni Videos.
		<br>3. Los archivos deberán estar nombrados exclusivamente con <font class="txt_Enfasis_Contenido">caractéres alfanuméricos exlusivamente</font> (Sin símbolos, espacios, puntos, comas, acentos, eñes u otros caracteres latinos). 
		<br>4. El archivo ZIP final no deberá superar los <font class="txt_Enfasis_Contenido">8MB</font>.  Esta es una limitación del servidor contratado para los fines del Prototipo.
		<br>Todas estas restricciones serán <font class="txt_Enfasis_Contenido">corregidas</font> en la <font class="txt_Enfasis_Contenido">versión final</font> de la plataforma.</p>
	</div>
</div>

<script type="text/javascript">
</script>

<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>