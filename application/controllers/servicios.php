<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Servicios extends CI_Controller {

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
		$this->load->view('welcome_message');
	}

	public function __construct() {
		parent::__construct();
		$this->load->helper('rutinas');
		
		$this->load->model("exposicion_model");
		$this->load->library('session');
	}

	/**
	 * Devuelve Arreglo Json con las exposiciones existentes
	 */
	public function exposiciones() {
		//Permitimos respuesta favorable al navegador para ajax en servidor
		header("Access-Control-Allow-Origin: *");//$this->output->set_header("Access-Control-Allow-Origin: *");
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$idCondicion = NULL;
		$salida = $this->exposicion_model->getDetalles($idCondicion);
		$this->escribirJson($salida);
	}

	/**
	 * Devuelve Json con la información de la exposición $idExpo
	 */
	public function exposicion($idExpo = 0) {
		//Permitimos respuesta favorable al navegador para ajax en servidor
		header("Access-Control-Allow-Origin: *");//$this->output->set_header("Access-Control-Allow-Origin: *");
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$salida = $this->exposicion_model->getDetallesExposicion($idExpo);
		$this->escribirJson($salida);
	}

	private function escribirJson($jsonArray) {
		$this->output->set_output(json_encode($jsonArray, JSON_UNESCAPED_UNICODE));
	}

}

/* End of file servicios.php */
/* Location: ./application/controllers/servicios.php */