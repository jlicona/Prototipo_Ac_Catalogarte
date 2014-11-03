<!--
# CATALOGARTE : Prototipo para la difusion de Exposiciones
# Copyright (c) 2014 IMPORTARE
# Auteur - Author - Autor: Axel Sanchez < axel20000@gmail.com >
# Auteur - Author - Autor: Hugo Gutierrez < akira.redwolf@gmail.com >
# Auteur - Author - Autor: Jaime Licona < liconita@gmail.com >
# Auteur - Author - Autor: Luis Sol < luisol.04@gmail.com >
# 
# Este programa es software libre; Ud. puede redistribuirlo y/o modificarlo
# bajo los terminos de la Licencia Publica General GNU tal como publico 
# la Fundacion del Software Libre; en su version 3 de la Licencia, o
# (según su voluntad) cualesquiera otras posteriores.
#
# Este programa se distribuye con el animo de que sea útil, pero SIN
# GARANTIA de NINGUN TIPO; incluso sin la garantia implicita de USO MERCANTIL
# o UTILIDAD PARA UN USO ESPECIFICO. Vease la Licencia Publica General GNU 
# para mas detalles.
#
# Deberia haber recibido una copia de la Licencia Publica General GNU con este
# programa, si no ha sido así, consulte <http://www.gnu.org/licenses/>
# -->

<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
/**
 * Variables recibidas del controlador:
 * @param string $titulo Título de la página (OPCIONAL)
 */
$CI_instancia = & get_instance(); //Instancia de CodeIgniter

if(!isset($titulo))
	$titulo = "CATALOGARTE";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<link href='http://fonts.googleapis.com/css?family=Raleway:300,500,100,800)' rel='stylesheet' type='text/css'>
    <?php 
    $this->load->helper('url'); //Para poder usar base_url() más abajo
    ?>
    <meta charset="utf-8">
    <title><?=$titulo?></title>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/estilos.css" >
	<link rel='shortcut icon' href="<?=base_url()?>img/iconos/favicon.png" type='image/png'>	
    <script src="<?=base_url()?>js/jquery-1.8.3.min.js"></script>
    <!--[if lt IE 9]>
    <script>
        document.createElement("article");
        document.createElement("aside");
        document.createElement("footer");
        document.createElement("header");
        document.createElement("main");
        document.createElement("nav");
        document.createElement("section");
    </script>
    <![endif]-->

	<?php
		
		////////////////////////////////////////////////// MENU
		////////// CONTENIDO
		$menuGeneral = array(0 => array("nombre" => "INICIO", "url" => base_url()), 1 => array("nombre" => "EXPOSICIONES", "url" => base_url()."index.php/exposiciones/explorar"), 2 => array("nombre" => "SEDES", "url" => "#"),);
		$submenuGeneral = array( 0 => array( array("nombre" => "CALENDARIO", "url" => "#"), array("nombre" => "SOBRE CATALOGARTE", "url" => base_url()."index.php/ayuda/acerca"), array("nombre" => "FAQ", "url" => base_url()."index.php/ayuda/faq") ), 1 => array( array("nombre" => "EXPLORA", "url" => "index.php/exposiciones/explorar"), array("nombre" => "BUSCA", "url" => "index.php/exposiciones/buscar") ), 2 => array( ),);
		$menuEditor = array(0 => array("nombre" => "EXPOSICIONES", "url" => base_url()."index.php/admin/exposiciones/"), 1 => array("nombre" => "ARTICULOS", "url" => base_url()."index.php/admin/articulos/"), 2 => array("nombre" => "SALIR", "url" => base_url()."index.php/admin/acceso/salir"),);
		$submenuEditor = array(0 => array( array("nombre" => "LISTADO EXPOSICIONES", "url" => base_url()."index.php/admin/exposiciones/"), array("nombre" => "AGREGAR NUEVA EXPOSICION", "url" => base_url()."index.php/admin/upload_ficha/") ), 1 => array( array("nombre" => "LISTADO ARTICULOS", "url" => base_url()."index.php/admin/articulos/"), array("nombre" => "AGREGAR NUEVO ARTICULO", "url" => base_url()."index.php/admin/articulos/nuevo") ), 2 => array( ),);
		$menuAdministrador = array(0 => array("nombre" => "EXPOSICIONES","url" => base_url()."index.php/admin/exposiciones/"), 1 => array("nombre" => "SEDES", "url" => "#"), 2 => array("nombre" => "USUARIOS","url" => "#"),	3 => array("nombre" => "SALIR","url" => base_url()."index.php/admin/acceso/salir"),);
		$submenuAdministrador = array( 0 => array( array("nombre" => "LISTADO EXPOSICIONES", "url" => base_url()."index.php/admin/exposiciones/"), array("nombre" => "AGREGAR NUEVA EXPOSICION", "url" => base_url()."index.php/admin/upload_ficha/") ), 1 => array( array("nombre" => "LISTADO SEDES", "url" => "#"), array("nombre" => "AGREGAR NUEVA SEDE", "url" => "#") ), 2 => array( array("nombre" => "LISTADO USUARIOS", "url" => "#"), array("nombre" => "AGREGAR NUEVO USUARIO", "url" => "#"), array("nombre" => "PERMISOS", "url" => "#") ), 3 => array( ),);

		////////// CAMBIAR ESTAS VARIABLES POR ALGO QUE SE CARGA DESE SESION
		$estaAutenticado = $CI_instancia->session->userdata('esta_autenticado');
		$esAdministrador = $CI_instancia->session->userdata('es_administrador');
		$idMenuSeleccionado = $CI_instancia->session->userdata('id_menu_seleccionado');

		////////// LEVANTANDO LOS ARRELGOS CORRESPONDIENTES
		if ($estaAutenticado) { 
			if (!$esAdministrador) { $listadoMenu = $menuEditor; $listadoSubmenu = $submenuEditor[$idMenuSeleccionado]; } 
			else { $listadoMenu = $menuAdministrador; $listadoSubmenu = $submenuAdministrador[$idMenuSeleccionado]; } } 
		else { $listadoMenu = $menuGeneral; $listadoSubmenu = $submenuGeneral[$idMenuSeleccionado]; }

		////////// ESCRIBIENDO
		$outputMenu = ""; $outputSubmenu = "";
		foreach($listadoMenu as $menu) $outputMenu .= '<li><a class="txt_Menu" href="' . $menu["url"] . '">' . $menu["nombre"] . '</a></li>';
		foreach($listadoSubmenu as $submenu) $outputSubmenu .= '<li><a class="txt_Submenu" href="' . $submenu["url"] . '">' . $submenu["nombre"] . '</a></li>';
		//////////////////////////////////////////////////  FIN MENU

	?>
</head>
<body>

    <div id="contenedor_principal">
        <header class="cabecera">

			<a href="<?=base_url()?>"><img class="logo_sitio" src="<?=base_url()?>img/logo_sitio.png" alt="Catalogarte" title="INICIO" ></a>

			<div id="contenedor_menus">

				<div id="menu"> <ul> <?php echo $outputMenu; ?> </ul> </div>
                
                <div id="sub_menu"> <ul> <?php echo $outputSubmenu; ?> </ul> </div>
                
                <div id="otros_logos">
                    <ul>
                        <li><img src="<?=base_url()?>img/LOGO_INBA_CONACULTA_100.png" width="405" height="100" alt="Logo INBA CONACULTA" title="Logo INBA CONACULTA"></li>
						<li></li>
                        <li><img src="<?=base_url()?>img/LOGO_SEP_GF_100.png" width="327" height="100" alt="Logo SEP SEGOB" title="Logo SEP SEGOB"></li>
						<li></li>
                    </ul>
                </div>

			</div>

        </header>
        <main class="contenido">