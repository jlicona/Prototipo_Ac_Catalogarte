<?php
class Exposicion_x_autor_model extends CI_Model {

	public $NOMBRE_TABLA = "exposicion_x_autor";
        
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function getTodos(){
		return $this->db->get($this->NOMBRE_TABLA )->result();
	}
	
	public function getConExposicion($idExpo){
            $this->db->order_by("orden ASC");
            return $this->db->get_where($this->NOMBRE_TABLA, "id_exposicion=$idExpo")->result();
	}
        
        public function getConAutor($idAutor){
            $this->db->order_by("orden ASC");
            return $this->db->get_where($this->NOMBRE_TABLA, "id_autor=$idExpo")->result();
	}
        
        /**
         * Agrega una relación entre exposición y autor
         * @param int $idExpo
         * @param int $idAutor
         * @param int $orden
         */
        public function agregar($idExpo, $idAutor, $orden){
            $datos = array(
                "id_exposicion" => $idExpo,
                "id_autor" => $idAutor,
                "orden" => $orden
            );
            $this->db->insert($this->NOMBRE_TABLA, $datos);
        }

}