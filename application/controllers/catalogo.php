<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Catalogo extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/servicios
	 * 	- or -  
	 * 		http://example.com/index.php/servicios/index
	 * 	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index() {
		//$this->load->view('welcome_message');
	}

	public function __construct() {
		parent::__construct();
		$this->load->helper('rutinas');
		$this->session->set_userdata('id_menu_seleccionado', 0);
		
		$this->load->helper(array('form', //Formularios
			'url' //Ancor/base_url()
		));
		$this->load->model("exposicion_model");
	}

	/**
	 * Devuelve Json con la información de la exposición $idExpo
	 */
	public function ver($idExpo = 0) {
		$datos["catalogo"] = $this->exposicion_model->getDetallesExposicion($idExpo);
		
		//$this->load->view('includes/cabecera');
		$this->load->view('catalogo', $datos);
		//$this->load->view('includes/pie');
	}

}

/* End of file catalogo.php */
/* Location: ./application/controllers/catalogo.php */