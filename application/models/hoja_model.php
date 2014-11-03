<?php

class Hoja_model extends CI_Model {

	public $NOMBRE_TABLA = "hoja_articulo";

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
	
	public function getConArticulo($idArticulo) {
		$this->db->order_by("orden", "asc");
		return $this->db->get_where($this->NOMBRE_TABLA, array("id_articulo" => $idArticulo))->result();
	}
	
	public function getTotalConArticulo($idArticulo) {
		return $this->db->query("select count(*) 'total' from $this->NOMBRE_TABLA where id_articulo = $idArticulo")->row()->total;
	}
	
	public function agregar($idArticulo, $idPlantilla, $html){
		$orden = $this->getTotalConArticulo($idArticulo) + 1;
		$datos = array("id_articulo" => $idArticulo, "id_plantilla" => $idPlantilla,
			"html" => $html, "orden" => $orden);
		$this->db->insert($this->NOMBRE_TABLA, $datos);
		return $this->db->insert_id();
	}

	public function actualizar($idHoja, $datos){
		return $this->db->update($this->NOMBRE_TABLA, $datos, array("id" => $idHoja));
	}
	
	/**
	 * Cambia el orden de $idHoja y sus hermanos
	 * @param int $idHoja
	 * @return object La hoja modificada
	 */
	public function subir($idHoja){
		$hojaActual = $this->get($idHoja);
		$hojas = $this->getConArticulo($hojaActual->id_articulo);
		$hojaAnterior = NULL;
		for($i=0 ; $i<count($hojas) ; $i++){
			if($hojas[$i]->id == $hojaActual->id){
				if($i-1 >= 0)
					$hojaAnterior = $hojas[$i-1];
				break;
			}
		}
		if(!empty($hojaAnterior)){
			$orden = $hojaAnterior->orden;
			$hojaAnterior->orden = $hojaActual->orden;
			$hojaActual->orden = $orden;
			$this->actualizar($hojaAnterior->id, $hojaAnterior);
			$this->actualizar($hojaActual->id, $hojaActual);
		}
		return $hojaActual;
	}
	
	/**
	 * Cambia el orden de $idHoja y sus hermanos
	 * @param int $idHoja
	 * @return object La hoja modificada
	 */
	public function bajar($idHoja){
		$hojaActual = $this->get($idHoja);
		$hojas = $this->getConArticulo($hojaActual->id_articulo);
		$hojaSiguiente = NULL;
		for($i=0 ; $i<count($hojas) ; $i++){
			if($hojas[$i]->id == $hojaActual->id){
				if($i+1 < count($hojas) )
					$hojaSiguiente = $hojas[$i+1];
				break;
			}
		}
		if(!empty($hojaSiguiente)){
			$orden = $hojaSiguiente->orden;
			$hojaSiguiente->orden = $hojaActual->orden;
			$hojaActual->orden = $orden;
			$this->actualizar($hojaSiguiente->id, $hojaSiguiente);
			$this->actualizar($hojaActual->id, $hojaActual);
		}
		return $hojaActual;
	}
	
}
