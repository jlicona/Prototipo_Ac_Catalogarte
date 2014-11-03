<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Multimedios extends CI_Controller {

	public $CARPETA_UPLOAD = "archivos/exposiciones/expo";
	
	public $PORTADA = "archivo_portada";
	public $CONTRAPORTADA = "archivo_contraportada";
	public $PRINCIPAL1 = "archivo_pieza_principal_1";
	public $PRINCIPAL2 = "archivo_pieza_principal_2";
	public $PRINCIPAL3 = "archivo_pieza_principal_3";
	public $PRINCIPAL4 = "archivo_pieza_principal_4";
	public $PRINCIPAL5 = "archivo_pieza_principal_5";
	public $OBRA = "obra";
	
	public $OPCIONES_NUEVO_MULTIMEDIO; //Concentra a los anteriores
	
	public function __construct() {
		parent::__construct();
		$this->load->helper('rutinas');
		validarSesionIniciada();
	
		$this->session->set_userdata('id_menu_seleccionado', 0);
		
		$this->load->helper(array('form', //Formularios
			'url' //Ancor/base_url()
		));
		$this->load->model('exposicion_model');
		$this->load->model('multimedio_model');
		$this->load->model('autor_model');
		$this->load->model('obra_model');
		$this->load->model('obra_x_multimedio_model');
		
		$this->OPCIONES_NUEVO_MULTIMEDIO = array($this->PORTADA, $this->CONTRAPORTADA, 
			$this->PRINCIPAL1, $this->PRINCIPAL2, $this->PRINCIPAL3, $this->PRINCIPAL4, 
			$this->PRINCIPAL5, $this->OBRA);
	}

	public function index() {}
	
	/**
	 * 
	 * @param type $idExpo Exposición a usar
	 * @param type $soloImprimirDetalles Cuando la llamada es del navegador será false, cuando sea con Ajax en subir() será verdadero
	 */
	public function en_exposicion($idExpo, $soloImprimirDetalles = FALSE){
		$exposicion = $this->exposicion_model->get($idExpo);
		if(empty($exposicion)){
			echo "La exposición no existe";
			return;
		}
		$imagenesBaseExpo = array(
			(object)array("tipo" => $this->PORTADA, "titulo" => "Portada", 
				"multimedio" => $this->getMultimedio($exposicion->archivo_portada)),
			(object)array("tipo" => $this->CONTRAPORTADA, "titulo" => "Contraportada", 
				"multimedio" => $this->getMultimedio($exposicion->archivo_contraportada)),
			(object)array("tipo" => $this->PRINCIPAL1, "titulo" => "Pieza principal #1", 
				"multimedio" => $this->getMultimedio($exposicion->archivo_pieza_principal_1)),
			(object)array("tipo" => $this->PRINCIPAL2, "titulo" => "Pieza principal #2", 
				"multimedio" => $this->getMultimedio($exposicion->archivo_pieza_principal_2)),
			(object)array("tipo" => $this->PRINCIPAL3, "titulo" => "Pieza principal #3", 
				"multimedio" => $this->getMultimedio($exposicion->archivo_pieza_principal_3)),
			(object)array("tipo" => $this->PRINCIPAL4, "titulo" => "Pieza principal #4", 
				"multimedio" => $this->getMultimedio($exposicion->archivo_pieza_principal_4)),
			(object)array("tipo" => $this->PRINCIPAL5, "titulo" => "Pieza principal #5", 
				"multimedio" => $this->getMultimedio($exposicion->archivo_pieza_principal_5)),
		);
		
		$autores = $this->autor_model->getEnExposicion($idExpo);
		foreach($autores as $autor){
			$autor->obras = $this->obra_model->getConAutor($autor->id);
			foreach($autor->obras as $obra){
				$obraMultimedios = $this->obra_x_multimedio_model->getConObra($obra->id);
				$multimedios = array();
				foreach($obraMultimedios as $obraMulti)
					$multimedios[] = $this->multimedio_model->get($obraMulti->id_multimedio);
				$obra->multimedios = $multimedios;
			}
		}
		
		$datos["exposicion"] = $exposicion;
		$datos["imagenesBaseExpo"] = $imagenesBaseExpo;
		$datos["autores"] = $autores;
		$datos["soloImprimirDetalles"] = $soloImprimirDetalles;

		//echo '<pre>'.print_r($datos, TRUE).'</pre>';
		//return;
		
		if(!$soloImprimirDetalles)$this->load->view('includes/cabecera');
		$this->load->view('admin/multimedios_exposicion', $datos);
		if(!$soloImprimirDetalles)$this->load->view('includes/pie');
	}
	
	public function subir($idExpo){
		//Permitimos respuesta favorable al navegador para ajax en servidor
		header("Access-Control-Allow-Origin: *");
		
		if(!is_numeric($idExpo)){
			$this->msgError("Esta exposición es incorrecta");
			return;
		}
		$exposicion = $this->exposicion_model->get($idExpo);
		if(empty($exposicion)){
			$this->msgError("Esta exposición no existe");
			return;
		}
		$this->CARPETA_UPLOAD .= $idExpo . '/';
		$nombreArchivo = $this->recibirArchivo($this->CARPETA_UPLOAD);
		if($nombreArchivo === FALSE){
			$this->msgError('No fue posible recibir el archivo');
			return;
		}
		
		if($this->input->get_post('es_nuevo')=='1'){
			//Verificamos tipo de elemento nuevo
			$tipo = $this->input->get_post('tipo_nuevo');
			if(!in_array($tipo, $this->OPCIONES_NUEVO_MULTIMEDIO)){
				$this->msgError("No se reconoce tipo de opción nueva");
				unlink($this->CARPETA_UPLOAD.$nombreArchivo);
				return;
			}
			$idNuevo = $this->multimedio_model->agregar($nombreArchivo, $this->CARPETA_UPLOAD);
			if($tipo == $this->OBRA){
				$idObra = $this->input->get_post('id_obra');
				$this->obra_x_multimedio_model->agregar($idNuevo, $idObra);
			}else{
				$datos = array($tipo => $idNuevo);
				$this->exposicion_model->actualizar($idExpo, $datos);
			}
		}else{
			//Actualizamos multimedio
			$idMultimedio = $this->input->get_post('id_multimedio_existente');
			$datos = array("nombre_archivo" => $nombreArchivo);
			$this->multimedio_model->actualizar($idMultimedio, $datos);
		}
		$this->en_exposicion($idExpo, TRUE);
	}
	
	private function recibirArchivo($carpeta){
		$config['upload_path'] = $carpeta;
		$config['allowed_types'] = '*';
		$config['max_size'] = '100000'; //KB
		//$config['file_name'] = "nombre controlado";
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload()) {
			$this->msgError($this->upload->display_errors());
			return FALSE;
		}
		$infoArchivo = $this->upload->data();
		//$conEspeciales = preg_replace('/[^(\x20-\x7F)]*/','', $infoArchivo['file_name']);
		//rename($carpeta.$infoArchivo['file_name'], $carpeta.$conEspeciales);
		//print_r($infoArchivo);
		return $infoArchivo['file_name'];
	}
	
	private function getMultimedio($id){
		if(empty($id))
			return NULL;
		return $this->multimedio_model->get($id);
	}
	
	private function msgError($msg){
		echo $msg;
	}
	
}

/* End of file multimedios.php */
/* Location: ./application/controllers/admin/multimedios.php */