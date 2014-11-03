<?php

class Plantilla_model extends CI_Model {

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
	
	/**
	 * Agrega nueva plantilla
	 * @param type $nombre
	 * @return int Id del nuevo registro
	 */
	public function agregar($nombre){
		$datos = array("nombre" => $nombre);
		$this->db->insert($this->NOMBRE_TABLA, $datos);
		return $this->db->insert_id();
	}
	
	public function actualizar($idPlantilla, $datos){
		return $this->db->update($this->NOMBRE_TABLA, $datos, array("id" => $idPlantilla));
	}

}
