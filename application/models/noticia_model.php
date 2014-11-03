<?php
class Condicion_model extends CI_Model {

	public $NOMBRE_TABLA = "noticia";
        public $LIMITE = 15;
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function getTodos(){
                $this->db->order_by("fecha_creacion DESC");
                $this->db->limit($this->LIMITE);
		return $this->db->get($this->NOMBRE_TABLA)->result();
	}
	
	public function get($id){
		return $this->db->get_where($this->NOMBRE_TABLA, array("id"=>$id) )->row();
	}
}