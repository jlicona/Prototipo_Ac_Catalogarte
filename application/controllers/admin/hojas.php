<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Hojas extends CI_Controller {

	public $CARPETA_UPLOAD = "archivos/articulos/";
	
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
		$this->load->model('hoja_x_contenido_model');
		$this->load->model('plantilla_model');
		$this->load->model('plantilla_articulo_model');
	}

	public function index($idArticulo) {
	}
	
	/**
	 * (En VIEW hojas) Lista hojas del artículo especificado
	 * @param int $idArticulo
	 */
	public function por_articulo($idArticulo){
		/* TODO VALIDAR SI USUARIO TIENE DERECHO A VER ESTAS HOJAS */
		$articulo = $this->articulo_model->get($idArticulo);
		$hojas = $this->hoja_model->getConArticulo($idArticulo);
		
		$datos["articulo"] = $articulo;
		$datos["hojas"] = $hojas;
		$datos["plantilla_model"] = $this->plantilla_model;
		
		$this->load->view('includes/cabecera');
		$this->load->view('admin/hojas', $datos);
		$this->load->view('includes/pie');
	}
	
	/**
	 * (En VIEW hojas) Sube la hoja en la lista de hojas
	 * @param int $idHoja
	 */
	public function subir($idHoja){
		$hojaActual = $this->hoja_model->subir($idHoja);
		$this->por_articulo($hojaActual->id_articulo);
	}
	
	/**
	 * (En VIEW hojas) Baja la hoja en la lista de hojas
	 * @param int $idHoja
	 */
	public function bajar($idHoja){
		$hojaActual = $this->hoja_model->bajar($idHoja);
		$this->por_articulo($hojaActual->id_articulo);
	}
	
	
	/**
	 * (En VIEW hoja_definicion) Define datos de hoja y artículo al que pertenece
	 * @param int $idHoja Identificador de la hoja
	 */
	public function editar($idHoja) {
		$hoja = $this->hoja_model->get($idHoja);
		if(empty($hoja)){
			redirect("admin/articulos");
			return;
		}
		$plantillas = array(); //En modo edición no hay plantillas

		$contenidos = $this->hoja_x_contenido_model->getConHoja($idHoja);
		$this->load->model('multimedio_model');
		foreach($contenidos as &$contenido){
			if(!empty($contenido->referencia))
				$contenido->url = $this->multimedio_model->getUrl($contenido->referencia);
		}
		$contextoJson = json_encode($contenidos, JSON_UNESCAPED_UNICODE);
		
		$datos["plantillas"] = $plantillas;
		$datos["plantillaSeleccionada"] = $hoja->id_plantilla;
		$datos["hoja"] = $hoja;
		$datos["contextoJson"] = $contextoJson;

		$this->displayHoja($datos);
	}
	
	/**
	 * (En VIEW hoja_definicion) Crea una hoja en blanco con los datos del artículo al que pertenece
	 * Valida la existencia de variable de post rgPlantillas para el paso de selección de plantillas
	 * @param int $idArticulo
	 */
	public function nuevo($idArticulo){
		//DATOS DUMMY
		$plantillas = $this->plantilla_articulo_model->getTodos();
		/*$plantillas = array(
			(object)["id"=>1, "nombre" => "Plantilla 1", "img" => base_url()."img/temp/porvenir.png"],
			(object)["id"=>2, "nombre" => "Plantilla 2", "img" => base_url()."img/temp/portada.jpg"]
		);*/
		
		$contextoJson = "[]"; /*array(
			(object)array("id_hoja" => 1, "posicion" => "0", "id_contenido" => "1", "texto" => '<p class="titulo_hoja">Título</p>', "referencia" =>null),
			(object)array("id_hoja" => 1, "posicion" => "2", "id_contenido" => "2", "texto" => '<p class="parrafo_hoja">El párrafo</p>', "referencia" =>null),
			(object)array("id_hoja" => 1, "posicion" => "4", "id_contenido" => "4", "texto" => null, "referencia" =>426),
			(object)array("id_hoja" => 1, "posicion" => "10", "id_contenido" => "3", "texto" => '<p class="cita_hoja">La cita</p>', "referencia" =>null)
		);
		$this->load->model('multimedio_model');
		foreach($contextoJson as &$contenido){
			if(!empty($contenido->referencia))
				$contenido->url = $this->multimedio_model->getUrl($contenido->referencia);
		}
		$contextoJson = json_encode($contextoJson, JSON_UNESCAPED_UNICODE);*/
		$idPlantilla = $this->input->get_post('rgPlantillas');
		if( empty($idPlantilla) )
			$idPlantilla = NULL;
		
		$datos["plantillas"] = $plantillas;
		$datos["plantillaSeleccionada"] = $idPlantilla;
		$datos["hoja"] = (object)array("id" => 0, "id_articulo" => $idArticulo, 
				"id_plantilla" => $idPlantilla, "html" =>"", "orden" => -1 /* Al ser nuevo no cuenta */);
		$datos["contextoJson"] = $contextoJson;
		$datos["modoEdicion"] = TRUE;
				
		$this->displayHoja($datos);
	}

	/**
	 * (En VIEW hoja_definicion) Guarda el registro de una hoja
	 */
	public function guardar(){
		$parametros = json_decode( $this->input->get_post('parametros') );
		$hoja = $parametros->hoja;
		$idHoja = null;
		if($hoja->id == "0"){
			$idHoja = $this->hoja_model->agregar($hoja->id_articulo, $hoja->id_plantilla, $hoja->html);
		}else{
			$idHoja = $hoja->id;
			$this->hoja_model->actualizar($idHoja, array("html" => $hoja->html));
			$this->hoja_x_contenido_model->borrarConHoja($idHoja);
		}
		//Generación de contenidos
		foreach($parametros->estructura as $contenido){
			$this->hoja_x_contenido_model->agregar($idHoja, 
					$contenido->posicion, $contenido->id_contenido, 
					$contenido->texto, $contenido->referencia );
		}

		redirect('admin/hojas/por_articulo/'.$hoja->id_articulo);
	}

	
	private function displayHoja($datos){
		$this->load->view('includes/cabecera');
		$this->load->view('admin/hoja_definicion', $datos);
		$this->load->view('includes/pie');
	}
	
	/**
	 * Recibe un archivo de multimedio mediante una llamada ajax post
	 * @param int $id_multimedio Variable de post. Valor 0 indica nuevo multimedio
	 */
	public function recibir_multimedio(){
		//Permitimos respuesta favorable al navegador para ajax en servidor
		header("Access-Control-Allow-Origin: *");
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		
		$idMultimedio = $this->input->get_post('id_multimedio');
		if(!is_numeric($idMultimedio)){
			$this->uploadResult("id multimedio inválido");
			return;
		}
		
		$nombreArchivo = $this->recibirArchivo($this->CARPETA_UPLOAD);
		if($nombreArchivo === FALSE){
			//$this->uploadResult('No fue posible recibir el archivo');
			return;
		}
		
		$this->load->model('multimedio_model');
		
		if($idMultimedio == '0'){
			$idMultimedio = $this->multimedio_model->agregar($nombreArchivo, $this->CARPETA_UPLOAD);
			if(empty($idMultimedio)){
				$this->uploadResult("No se pudo agregar multimedio en base de datos");
				unlink($this->CARPETA_UPLOAD.$nombreArchivo);
				return;
			}
		}else{
			//Actualizamos multimedio
			$datos = array("nombre_archivo" => $nombreArchivo);
			if($this->multimedio_model->actualizar($idMultimedio, $datos) <=0){
				$this->uploadResult("No se pudo actualizar multimedio en base de datos");
				unlink($this->CARPETA_UPLOAD.$nombreArchivo);
				return;
			}
		}
		$this->uploadResult("", $idMultimedio, base_url().$this->CARPETA_UPLOAD.$nombreArchivo);
	}
	
	/**
	 * Helper para recibir un archivo en servidor
	 * @param string $carpeta Carpeta donde se recibe el archivo
	 * @return mixed Nombre del archivo en string en éxito y FALSE en caso contrario
	 */
	private function recibirArchivo($carpeta){
		$config['upload_path'] = $carpeta;
		$config['allowed_types'] = '*';
		$config['max_size'] = '100000'; //KB
		//$config['file_name'] = "nombre controlado";
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload()) {
			$this->uploadResult($this->upload->display_errors());
			return FALSE;
		}
		$infoArchivo = $this->upload->data();
		//$conEspeciales = preg_replace('/[^(\x20-\x7F)]*/','', $infoArchivo['file_name']);
		//rename($carpeta.$infoArchivo['file_name'], $carpeta.$conEspeciales);
		//print_r($infoArchivo);
		return $infoArchivo['file_name'];
	}
	
	/**
	 * Genera mensaje en JSON con el reusltado de la función recibir_multimedio()
	 * @param string $msgError Tiene valor cuando se reporta un error
	 * @param int $idMultimedio Multimedio relacionado
	 * @param string $url Url del multimedio
	 */
	private function uploadResult($msgError, $idMultimedio = '', $url =''){
		$salida = array("error" => $msgError, "id_multimedio" => $idMultimedio, "url" => $url);
		$this->output->set_output(json_encode($salida, JSON_UNESCAPED_UNICODE));
	}
}

/* End of file hojas.php */
/* Location: ./application/controllers/admin/hojas.php */