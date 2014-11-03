<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Acceso extends CI_Controller {

	
	public function __construct() {
		parent::__construct();
		$this->load->helper('rutinas');
		
		
		$this->load->helper(array('form', //Formularios
			'url' //Ancor/base_url()
		));		
	}

	public function index($resultados = '') {
		$this->session->set_userdata('id_menu_seleccionado', 0);
		$datos['resultados'] = $resultados;
		
		$this->load->view('includes/cabecera');
		$this->load->view('admin/acceso', $datos);
		$this->load->view('includes/pie');
	}
	
	
	public function ingresar(){
		/**
		 * Validación dummy de usuario.
		 * Administrador: { nombre_usuario : Administrador, password : admin }
		 * Editor: { nombre_usuario : Editor, password : editor }
		 */
		$login = $this->input->get_post('nombre');
		$password = $this->input->get_post('contrasena');
		if($login == "Administrador" && $password == "admin"){
			iniciarSesion(NULL, 1, "Administrador", true);
		}else if( $login == "Editor" && $password == "editor"){
			iniciarSesion(NULL, 2, "Editor", false);
		}else{
			$this->index('Datos de inicio incorrectos');
		}
	}
	
	public function salir(){
		cerrarSesion();
	}
	
}

/* End of file multimedios.php */
/* Location: ./application/controllers/admin/multimedios.php */