<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Exposiciones extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper('rutinas');
		validarSesionIniciada();

		$this->load->helper(array('form', //Formularios
			'url' //Ancor/base_url()
		));

		$this->load->model('sede_model');
		$this->load->model('condicion_model');
		$this->load->model('exposicion_model');
	}

	public function index() {
		$this->session->set_userdata('id_menu_seleccionado', 0);
		
		$idCondicion = NULL;
		$orden = "fecha_fin desc";
		$exposiciones = $this->exposicion_model->getTodos($idCondicion, $orden);
		
		$datos["exposiciones"] = $exposiciones;
		$datos["condicion_model"] = $this->condicion_model;
		$datos["sede_model"] = $this->sede_model;
		
		$this->load->view('includes/cabecera');
		$this->load->view('admin/exposiciones', $datos);
		$this->load->view('includes/pie');
	}
	
	/**
	 * Publica la exposición
	 * @param type $idExpo
	 */
	public function publicar($idExpo){
		/* RESERVADO VALIDACIÓN DE SI EL USUARIO TIENE DERECHO A PUBLICAR ESTA EXPO*/
		/* RESERVADO VALIDACIÓN DE SI EXPO ES PUBLICABLE*/
		$this->exposicion_model->publicar($idExpo);
		$this->index();
	}
	
	/**
	 * Publica la exposición
	 * @param type $idExpo
	 */
	public function ocultar($idExpo){
		/* RESERVADO VALIDACIÓN DE SI EL USUARIO TIENE DERECHO A OCULTAR ESTA EXPO*/
		$this->exposicion_model->ocultar($idExpo);
		$this->index();
	}
	
	/**
	 * (En VIEW exposiciones_vincular_articulos) Ventana modal que permite vincular articulos
	 * a la exposición indicada.
	 * Verifica si existe variable post $id_articulo que indica que hubo un post
	 * @param int $idExpo Exposición a la que se vincularán artículos
	 */
	public function vincular_articulos($idExpo){
		/* RESERVADO VALIDACIÓN DE SI idExpo es correcto */
		/* RESERVADO VALIDACIÓN DE SI EL USUARIO TIENE DERECHO A VINCULAR ESTA EXPO */
		$this->load->model('articulo_model');
		
		$idArticulo = $this->input->get_post('id_articulo');
		if(!empty($idArticulo)){ //Si hay solicitud de agregar
			$this->articulo_model->vincularEnExposicion($idArticulo, $idExpo);
		}
		
		$articulosVinculables = $this->articulo_model->getVinculables($idExpo);
		$finalVinculables = array();
		$finalVinculables[0]= "-- Seleccione artículo --";
		foreach($articulosVinculables as $articulo)
			$finalVinculables[$articulo->id] = $articulo->nombre . " ($articulo->autor)";
		$articulosVinculados = $this->articulo_model->getVinculados($idExpo);
		
		$datos["idExposicion"] = $idExpo;
		$datos["articulosVinculables"] = $finalVinculables;
		$datos["articulosVinculados"] = $articulosVinculados;
		
		$this->load->view('admin/exposiciones_vincular_articulos', $datos);
	}

}

/* End of file exposiciones.php */
/* Location: ./application/controllers/admin/exposiciones.php */