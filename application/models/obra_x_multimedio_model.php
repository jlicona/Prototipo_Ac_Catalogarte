<?php
class Obra_x_multimedio_model extends CI_Model {

	public $NOMBRE_TABLA = "obra_x_multimedio";
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function getTodos(){
		return $this->db->get($this->NOMBRE_TABLA)->result();
	}
	
	public function getConObra($idObra){
		$this->db->order_by("orden asc");
		return $this->db->get_where($this->NOMBRE_TABLA, array("id_obra"=>$idObra) )->result();
	}

	public function getOrdenMaximo($idObra){
		return (int)$this->db->query("select max(orden) 'maximo' from $this->NOMBRE_TABLA where id_obra=$idObra")->row()->maximo;
	}
	
	public function agregar($idMultimedio, $idObra, $orden = ''){
		if(empty($orden)){
			$orden = $this->getOrdenMaximo($idObra)+1;
		}
		$datos = array("id_multimedio" => $idMultimedio,
                    "id_obra" => $idObra, "orden" => $orden);
		return $this->db->insert($this->NOMBRE_TABLA, $datos);
	}        
}