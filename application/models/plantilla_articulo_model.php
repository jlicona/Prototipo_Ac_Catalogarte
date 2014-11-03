<?php

class Plantilla_articulo_model extends CI_Model {

	public $NOMBRE_TABLA = "plantilla_articulo";

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function getTodos() {
		return $this->db->get($this->NOMBRE_TABLA)->result();
	}

	public function get($id) {
		return $this->db->get_where($this->NOMBRE_TABLA, array("id" => $id))->row();
	}
	
}
