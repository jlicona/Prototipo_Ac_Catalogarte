<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$CI_instancia = & get_instance();
$CI_instancia->load->library('session');


if( !function_exists('__limpiarSesion')){
	function __limpiarSesion(){
		//La variable no existe así que asignamos una en blanco
		$datosSesion = array(
			"id_usuario" => NULL,
			"nombre" => "[Sin sesión]",
			"esta_autenticado" => false,
			"es_administrador" => false,
			"id_menu_seleccionado" => 0
		);
		$CI_instancia = & get_instance();
		$CI_instancia->session->set_userdata($datosSesion);
	}
}

if($CI_instancia->session->userdata('id_usuario') === FALSE){
	__limpiarSesion();
}

if( !function_exists('iniciarSesion')){
	/**
	 * Inicia sesión del usuario especificado
	 * @param array $usuario Registro en base de datos de usuario
	 */
	function iniciarSesion($usuario, $temporalId, $temporalNombre, $temporalEsAdmin){
		$CI_instancia = & get_instance();
		$datosSesion = array(
			"id_usuario" => $temporalId, /* $usuario->id */
			"nombre" => $temporalNombre, /* $usuario->nombre */
			"esta_autenticado" => true,
			"es_administrador" => $temporalEsAdmin, /* $usuario->rol == "#"#"#*/
			"id_menu_seleccionado" => 0
		);
		$CI_instancia->session->set_userdata($datosSesion);
		$CI_instancia->load->helper('url');
		redirect('admin/exposiciones');
	}
}

if( !function_exists('cerrarSesion')){
	/**
	 * Cierra sesión del usuario
	 */
	function cerrarSesion(){
		__limpiarSesion();
		redirect('admin/acceso');
	}
}


if( !function_exists('validarSesionIniciada')){
	function validarSesionIniciada(){
		$CI_instancia = & get_instance();
		if( !$CI_instancia->session->userdata('esta_autenticado') ){
			$CI_instancia->load->helper('url');
			redirect('admin/acceso');
		}
	}
}

if( !function_exists('validarSesionAdmin')){
	function validarSesionAdmin(){
		validarSesionIniciada();
		$CI_instancia = & get_instance();
		if( !$CI_instancia->session->userdata('es_administrador') ){
			$CI_instancia->load->helper('url');
			redirect('admin/acceso');
		}
	}
}

if( !function_exists('validarSesionEditor')){
	function validarSesionEditor(){
		validarSesionIniciada();
		$CI_instancia = & get_instance();
		if( $CI_instancia->session->userdata('es_administrador') ){
			$CI_instancia->load->helper('url');
			redirect('admin/acceso');
		}
	}
}


/* End of file rutinas.php */
/* Location: ./application/helpers/rutinas.php */