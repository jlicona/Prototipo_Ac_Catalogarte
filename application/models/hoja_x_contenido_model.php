<?php

class Hoja_x_contenido_model extends CI_Model {

	public $NOMBRE_TABLA = "hoja_x_contenido";

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function getTodos() {
		return $this->db->get($this->NOMBRE_TABLA)->result();
	}

	/*public function get($id) {
		return $this->db->get_where($this->NOMBRE_TABLA, array("id" => $id))->row();
	}*/

	public function getConHoja($idHoja) {
		$this->db->order_by("posicion asc");
		return $this->db->get_where($this->NOMBRE_TABLA, array("id_hoja" => $idHoja))->result();
	}
	
	public function borrarConHoja($idHoja){
		$this->db->delete($this->NOMBRE_TABLA, array("id_hoja" => $idHoja));
		return $this->db->affected_rows();
	}
	
	public function agregar($idHoja, $posicion, $idContenido, $texto, $referencia){
		$datos = array("id_hoja" => $idHoja, "posicion" => $posicion, 
			"id_contenido" => $idContenido, "texto" => $texto, "referencia" => $referencia);
		$this->db->insert($this->NOMBRE_TABLA, $datos);
		return $this->db->insert_id();
	}

}
