<?php
class Obra_model extends CI_Model {

	public $NOMBRE_TABLA = "obra";
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function getTodos(){
		return $this->db->get($this->NOMBRE_TABLA)->result();
	}
	
	public function get($id){
		return $this->db->get_where($this->NOMBRE_TABLA, array("id"=>$id) )->row();
	}
	
	public function getConAutor($idAutor){
		$this->db->order_by("orden asc");
		return $this->db->get_where($this->NOMBRE_TABLA, array("id_autor"=>$idAutor) )->result();
	}

        /**
         * Agrega una nueva obra
         * @param int $idAutor
         * @param string $elaboracion
         * @param string $tecnica
         * @param string $dimensiones
         * @param string $comentario
         * @param int $nMultimedios
         * @param int $orden
         * @return int Id del registro creado
         */
	public function agregar($idAutor, $titulo, $elaboracion, $tecnica, $dimensiones, $comentario, $nMultimedios, $orden){
		$datos = array("id_autor" => $idAutor, 
			"titulo" => $titulo,
			"numero_multimedios" => $nMultimedios, 
			"orden" => $orden);
		if(!empty($elaboracion))$datos["elaboracion"] = $elaboracion;
		if(!empty($tecnica))$datos["tecnica"] = $tecnica;
		if(!empty($dimensiones))$datos["dimensiones"] = $dimensiones;
		if(!empty($comentario))$datos["comentario"] = $comentario;
		$this->db->insert($this->NOMBRE_TABLA, $datos);
		return $this->db->insert_id();
	}
}