<?php

class Articulo_model extends CI_Model {

	public $NOMBRE_TABLA = "articulo";

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
	
	public function getEnExposicion($idExpo){
		return $this->db->get_where("exposicion_x_articulo", array("id_exposicion" => $idExpo) )->result();
	}
	
	/**
	 * Regresa los artículos que son vinculables a la exposición
	 * @param int $idExpo Exposición a la que se le busca artículos vinculables
	 * @return array
	 */
	public function getVinculables($idExpo){
		$idExpo = $this->db->escape($idExpo);
		return $this->db->query("select * from articulo where id NOT IN (select id_articulo from exposicion_x_articulo WHERE id_exposicion=$idExpo)")->result();
	}
	
	/**
	 * Regresa los artículos que ya están vinculados a exposición.
	 * Es similar que getEnExposicion() pero regresa más campos
	 * @param int $idExpo Exposición a la que se le busca artículos NO vinculables
	 * @return array
	 */
	public function getVinculados($idExpo){
		$idExpo = $this->db->escape($idExpo);
		return $this->db->query("select * from articulo where id IN (select id_articulo from exposicion_x_articulo WHERE id_exposicion=$idExpo)")->result();
	}
	
	/**
	 * Vincula un artículo y una exposición
	 * @param int $idArticulo Artículo a vincular
	 * @param int $idExpo Exposición a vincular
	 * @return int Id del nuevo registro
	 */
	public function vincularEnExposicion($idArticulo, $idExpo){
		$this->db->insert("exposicion_x_articulo", array('id_articulo'=>$idArticulo, 'id_exposicion'=>$idExpo) );
		return $this->db->insert_id();
	}
	
	/**
	 * Agrega un nuevo artículo
	 * @param type $nombre
	 * @param type $autor
	 * @param type $referencia
	 * @param type $publico
	 * @param type $idUsuario
	 * @return int Id del nuevo registro
	 */
	public function agregar($nombre, $autor, $referencia, $publico, $idUsuario){
		$datos = array("nombre" => $nombre,
			"autor" => $autor, "referencia" => $referencia,
			"publico" => $publico, "id_usuario" => $idUsuario);
		$this->db->insert($this->NOMBRE_TABLA, $datos);
		return $this->db->insert_id();
	}
	
	public function actualizar($idArticulo, $datos){
		return $this->db->update($this->NOMBRE_TABLA, $datos, array("id" => $idArticulo));
	}

}
