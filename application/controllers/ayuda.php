<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ayuda extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper('rutinas');
		$this->session->set_userdata('id_menu_seleccionado', 0);
	}
	

	public function faq() {
		$this->load->view('includes/cabecera');
		$this->load->view('faq');
		$this->load->view('includes/pie');
	}
	
	public function acerca() {
		$this->load->view('includes/cabecera');
		$this->load->view('acerca');
		$this->load->view('includes/pie');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */