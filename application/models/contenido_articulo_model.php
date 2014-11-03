<?php

class Contenido_articulo_model extends CI_Model {

	public $NOMBRE_TABLA = "contenido_articulo";

	//Mapeo de tipos a ID en base de datos para su mejor uso en VIEWS
	public $TITULO = 1;
	public $PARRAFO = 2;
	public $CITA = 3;
	public $PAISAJE = 4;
	public $RETRATO = 5;
	
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
