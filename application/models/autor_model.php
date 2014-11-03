<?php
class Autor_model extends CI_Model {

    public $NOMBRE_TABLA = "autor";
	
    public function __construct(){
		parent::__construct();
        $this->load->database();    
    }
	
    public function getEnExposicion($idExpo){
        return $this->db->query("SELECT a.id, a.nombre FROM autor a JOIN exposicion_x_autor ea ON a.id=ea.id_autor WHERE ea.id_exposicion=$idExpo ORDER BY ea.orden ASC")->result();
    }
	
    public function get($id){
        return $this->db->get_where($this->NOMBRE_TABLA, array("id"=>$id) )->row();
    }
    
    /**
     * Agrega un nuevo autor
     * @param string $nombre Nombre del autor
     * @return int Id del registro creado
     */
    public function agregar($nombre){
        $this->db->insert($this->NOMBRE_TABLA, array("nombre" => $nombre) );
        return $this->db->insert_id();
    }
    
}