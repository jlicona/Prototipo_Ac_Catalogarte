<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Articulos extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper('rutinas');
		validarSesionEditor();

		$this->session->set_userdata('id_menu_seleccionado', 1);
		
		$this->load->helper(array('form', //Formularios
			'url' //Ancor/base_url()
		));

		$this->load->model('articulo_model');
		$this->load->model('hoja_model');
	}

	public function index() {
		//$orden = "fecha_fin desc";
		//$idUsuario = ...
		$articulos = $this->articulo_model->getTodos();
		
		$datos["articulos"] = $articulos;
		$datos["hoja_model"] = $this->hoja_model;
		
		$this->load->view('includes/cabecera');
		$this->load->view('admin/articulos', $datos);
		$this->load->view('includes/pie');
	}
	
	public function editar($idArticulo) {
		$articulo = $this->articulo_model->get($idArticulo);
		if(empty($articulo)){
			$this->nuevo();
			return;
		}else{
			$datos["articulo"] = $articulo;
			$this->displayArticulo($datos);
		}
	}
	
	public function nuevo(){
		$datos["articulo"] = (object)array("id" => 0, "nombre" => "", 
				"autor" => "", "referencia" => "");
		$this->displayArticulo($datos);
	}

	public function guardar(){
		$id = $this->input->get_post('id_articulo');
		$nombre = $this->input->get_post('nombre');
		$autor = $this->input->get_post('autor');
		$referencia = $this->input->get_post('referencia');
		$publico = 1;
		$idUsuario = 1;
		
		$articulo = (object)array("id" => $id, "nombre" => $nombre, 
			"autor" => $autor, "referencia" => $referencia, "publico" => 1, 
			"id_usuario" => $idUsuario);
		
		$error = "";
		if(empty(trim($nombre))){
			$error = "Debe agregar un nombre";
		}
		if(empty($error)){//todo va bien
			if($id == 0){
				$idNuevo = $this->articulo_model->agregar($nombre, $autor, $referencia, $publico, $idUsuario);
				if($idNuevo <= 0)
					$error = "No fue posible crear el artículo";
			}else{
				$this->articulo_model->actualizar($id, $articulo);
			}
		}
		if(!empty($error)){		
			$datos["articulo"] = $articulo;
			$datos["resultados"] = $error;
		
			$this->displayArticulo($datos);
		}else{
			redirect('admin/articulos');
		}
		
	}
	
	private function displayArticulo($datos){
		$this->load->view('includes/cabecera');
		$this->load->view('admin/articulo', $datos);
		$this->load->view('includes/pie');
	}
}

/* End of file articulo.php */
/* Location: ./application/controllers/admin/articulo.php */