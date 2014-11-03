<?php

class Multimedio_model extends CI_Model {

	public $NOMBRE_TABLA = "multimedio";

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
	
	public function getUrl($idMultimedio) {
		$multimedio = $this->db->get_where($this->NOMBRE_TABLA, array("id" => $idMultimedio))->row();
		return base_url() . $multimedio->path . $multimedio->nombre_archivo;
	}

	/**
	 * Registra un nuevo multimedio a la base de datos
	 * @param string $nombre Nombre del archivo ej: archivo.jpg
	 * @param string $path Path de la carpeta del archivo ej: c:/carpeta/sub/
	 * @param string $tipo Tipo del archivo ej: img
	 * @return int Id del registro creado
	 */
	public function agregar($nombre, $path = '', $tipo = 'img') {
		$datos = array("nombre_archivo" => $nombre,
			"path" => $path, "tipo_archivo" => $tipo);
		$this->db->insert($this->NOMBRE_TABLA, $datos);
		return $this->db->insert_id();
	}
	
	/**
	 * Actualiza el multimedio con los datos proporcionados
	 * @param int $idMultimedio Id del multimedio a actualizar
	 * @param array $datos Datos a actualizar
	 */
	public function actualizar($idMultimedio, $datos) {
		$this->db->update($this->NOMBRE_TABLA, $datos, array("id" => $idMultimedio));
		return $this->db->affected_rows();
	}

}
