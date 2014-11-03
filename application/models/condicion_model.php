<?php

class Condicion_model extends CI_Model {

	public $NOMBRE_TABLA = "condicion";

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

	public function getConNombre($nombre) {
		return $this->db->get_where($this->NOMBRE_TABLA, array("nombre" => $nombre))->row();
	}

}
