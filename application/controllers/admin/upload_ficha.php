<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Ver http://www.innovativephp.com/uploading-and-extracting-zip-files-with-codeigniter-and-php-ziparchive/
 */
class Upload_ficha extends CI_Controller {

	/**
	 *
	 * @var string Nombre del archivo de ficha técnica
	 */
	public $ARCHIVO_FICHA_TECNICA = "ficha_tecnica.xls";
	//Variables de estructura de una ficha técnica
	public $HOJA_EXPO = "Expo";
	public $HOJA_AUTOR = "Autor"; //Prefijo para Autor1, Autor2, AutorN

	/**
	 *
	 * @var string Carpeta en servidor donde se suben los ZIP de usuarios
	 */
	public $CARPETA_UPLOAD;

	/**
	 * Declarada en constructor y usada como plantilla para la carpeta de multimedios
	 * de una exposición con la forma $CARPETA_EXPOSICIONES . {idExpo} . '/'
	 * @var string Carpeta en servidor donde se guardan las exposiciones
	 */
	public $CARPETA_EXPOSICIONES;

	public function __construct() {
		parent::__construct();
		$this->load->helper('rutinas');
		validarSesionIniciada();
		
		$this->session->set_userdata('id_menu_seleccionado', 0);
		
		$this->CARPETA_UPLOAD = APPPATH . "../archivos/uploads/fichas/";
		$this->CARPETA_EXPOSICIONES = APPPATH . "../archivos/exposiciones/expo";

		$this->load->helper(array('form', //Formularios
			'url', //Ancor/base_url()
			'file' //delete_files()
		));
		$this->load->library('FichaTecnica');

		$this->load->model('sede_model');
		$this->load->model('disciplina_model');
		$this->load->model('condicion_model');
		$this->load->model('disponibilidad_model');

		$this->load->model('autor_model');
		$this->load->model('exposicion_model');
		$this->load->model('exposicion_x_autor_model');
		$this->load->model('multimedio_model');
		$this->load->model('obra_model');
		$this->load->model('obra_x_multimedio_model');
	}

	public function index($resultados = '') {
		$this->load->view('includes/cabecera');
		$this->load->view('admin/upload_ficha', array("resultados" => $resultados));
		$this->load->view('includes/pie');
	}

	/**
	 * Procesa la carga en servidor de un archivo ZIP del usuario
	 * @return type
	 */
	public function subir() {
		$carpetaExtraccion = $this->CARPETA_UPLOAD . "/" . time();
		if (!mkdir($carpetaExtraccion)) {
			$this->index("No se puede crear carpeta para extraer archivos ZIP");
			return;
		}

		$config['upload_path'] = $this->CARPETA_UPLOAD;
		$config['allowed_types'] = 'zip';
		$config['max_size'] = '100000'; //KB
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload()) {
			$this->index($this->upload->display_errors());
			rmdir($carpetaExtraccion);
			return;
		}
		$infoArchivo = array('upload_data' => $this->upload->data());
		$archivoZip = $infoArchivo['upload_data']['full_path'];
		$zip = new ZipArchive;
		chmod($archivoZip, 0777);
		if ($zip->open($archivoZip) === FALSE) {
			$this->index("No fue posible abrir el archivo ZIP recibido");
			rmdir($carpetaExtraccion);
			return;
		}
		$zip->extractTo($carpetaExtraccion);
		$zip->close();

		try {
			$fichaTecnica = $this->leer_excel($carpetaExtraccion . "/");
			$this->crearExposicion($fichaTecnica, $carpetaExtraccion . "/");

			$datos = array("ficha_tecnica" => $fichaTecnica);
			$resultado = $this->load->view('admin/upload_ficha_resultados', $datos, TRUE);
			$this->index($resultado);
		} catch (Exception $e) {
			$this->index("El siguiente error ocurrió:<br>" . $e->getMessage());
		}
		//Borramos archivos que ya no necesitamos
		unlink($archivoZip);
		delete_files($carpetaExtraccion, TRUE);
		rmdir($carpetaExtraccion);
	}

	/**
	 * Procesa archivo de excel con información de una ficha técnica de exposición
	 * @param string $carpeta Carpeta que contiene el archivo xls a procesar
	 * @return \FichaTecnica
	 * @throws Exception Al no leer el archivo de excel
	 */
	private function leer_excel($carpeta) {
		$pathFicha = $carpeta . $this->ARCHIVO_FICHA_TECNICA;
		if (!file_exists($pathFicha))
			throw new Exception("No se encontró dentro del ZIP el archivo requerido: " . $this->ARCHIVO_FICHA_TECNICA);

		$this->load->library('excel');
		//$doc = $this->excel;

		$objPHPExcel = PHPExcel_IOFactory::load($pathFicha); //Leemos archivo
		$nombresHojas = $objPHPExcel->getSheetNames();
		if (!in_array($this->HOJA_EXPO, $nombresHojas))
			throw new Exception("Documento de Excel no contiene hoja '" . $this->HOJA_EXPO . "'");

		$sheetExpo = $objPHPExcel->getSheetByName($this->HOJA_EXPO)->toArray(null, true, true, true);
		//echo "<pre>" . print_r($sheetExpo, TRUE) . "</pre>";

		$ficha = new FichaTecnica();

		$sede = $this->getExcel($sheetExpo, 2, "C", TRUE, "Sede");
		$filaSede = $this->sede_model->getConNombre($sede);
		if (empty($filaSede))
			throw new Exception("No se identifica la sede '$sede'");
		$ficha->idSede = $filaSede->id;

		$disciplina = $this->getExcel($sheetExpo, 3, "C", TRUE, "Disciplina");
		$filaDisciplina = $this->disciplina_model->getConNombre($disciplina);
		if (empty($filaDisciplina))
			throw new Exception("No se identifica la disciplina '$disciplina'");
		$ficha->idDisciplina = $filaDisciplina->id;

		$condicion = $this->getExcel($sheetExpo, 4, "C", TRUE, "Condición");
		$filaCondicion = $this->condicion_model->getConNombre($condicion);
		if (empty($filaCondicion))
			throw new Exception("No se identifica la condición '$condicion'");
		$ficha->idCondicion = $filaCondicion->id;

		$ficha->nombreExpo = $this->getExcel($sheetExpo, 5, "C", TRUE, "Nombre");
		$ficha->descExpo = $this->getExcel($sheetExpo, 6, "C", TRUE, "Descripción Corta");
		$ficha->notaExpo = $this->getExcel($sheetExpo, 7, "C", TRUE, "Nota");

		$disponibilidad = $this->getExcel($sheetExpo, 12, "C", TRUE, "Catálogo Disponible");
		$filaDisponibilidad = $this->disponibilidad_model->getConNombre($disponibilidad);
		if (empty($filaDisponibilidad))
			throw new Exception("No se identifica la disponibilidad '$disponibilidad'");
		$ficha->idDisponibilidad = $filaDisponibilidad->id;

		$ficha->fechaInicioExpo = $this->getExcel($sheetExpo, 18, "C", TRUE, "Inicio Exposición");
		if(!empty($ficha->fechaInicioExpo)){
			$fecha = $objPHPExcel->getSheetByName($this->HOJA_EXPO)->getCell("C18")->getValue();
			$ficha->fechaInicioExpo = PHPExcel_Style_NumberFormat::toFormattedString($fecha, 'YYYY-MM-DD');
		}
		$ficha->fechaFinExpo = $this->getExcel($sheetExpo, 19, "C");
		if(!empty($ficha->fechaFinExpo)){
			$fecha = $objPHPExcel->getSheetByName($this->HOJA_EXPO)->getCell("C19")->getValue();
			$ficha->fechaFinExpo = PHPExcel_Style_NumberFormat::toFormattedString($fecha, 'YYYY-MM-DD');
		}
		$ficha->totalAutores = (int) $this->getExcel($sheetExpo, 23, "C", TRUE, "Total Autores");
		//Archivos generales de exposición
		$ficha->portada = $this->getExcel($sheetExpo, 26, "C");
		$ficha->contraportada = $this->getExcel($sheetExpo, 27, "C");
		$ficha->principal1 = $this->getExcel($sheetExpo, 28, "C");
		$ficha->principal2 = $this->getExcel($sheetExpo, 29, "C");
		$ficha->principal3 = $this->getExcel($sheetExpo, 30, "C");
		$ficha->principal4 = $this->getExcel($sheetExpo, 31, "C");
		$ficha->principal5 = $this->getExcel($sheetExpo, 32, "C");

		$multimedios = array();
		$this->agregarSiNoExiste($ficha->portada, $multimedios);
		$this->agregarSiNoExiste($ficha->contraportada, $multimedios);
		$this->agregarSiNoExiste($ficha->principal1, $multimedios);
		$this->agregarSiNoExiste($ficha->principal2, $multimedios);
		$this->agregarSiNoExiste($ficha->principal3, $multimedios);
		$this->agregarSiNoExiste($ficha->principal4, $multimedios);
		$this->agregarSiNoExiste($ficha->principal5, $multimedios);

		//Recorremos autores declarados
		for ($i = 1; $i <= $ficha->totalAutores; $i++) {
			$hojaAutor = $this->HOJA_AUTOR . $i;
			if (!in_array($hojaAutor, $nombresHojas))
				throw new Exception("Documento de Excel no contiene hoja '" . $hojaAutor . "'");
			//Genera sheet en arreglo de solo números con índice 0 para filas y columnas
			$sheetAutor = $objPHPExcel->getSheetByName($hojaAutor)->toArray(null, true, true, false);

			$autor = new FichaAutor();
			$autor->nombre = $this->getExcel($sheetAutor, 1, 2, TRUE, "Nombre", $hojaAutor);

			$totalObras = (int) $this->getExcel($sheetAutor, 5, 2, TRUE, "Número de Obras", $hojaAutor);
			//Recorremos obras
			for ($col = 2; $col < 2 + $totalObras; $col++) {
				$obra = new FichaObra();
				$obra->titulo = $this->getExcel($sheetAutor, 9, $col, TRUE, "Título", $hojaAutor);
				$obra->elaboracion = $this->getExcel($sheetAutor, 10, $col);
				$obra->tecnica = $this->getExcel($sheetAutor, 11, $col);
				$obra->dimensiones = $this->getExcel($sheetAutor, 12, $col);
				$obra->comentarios = $this->getExcel($sheetAutor, 13, $col);

				$totalArchivos = (int) $this->getExcel($sheetAutor, 15, $col, TRUE, "Número de Archivos", $hojaAutor);
				//Recorremos archivos
				for ($fila = 16; $fila < 16 + $totalArchivos; $fila++) {
					$archivoObra = $this->getExcel($sheetAutor, $fila, $col, TRUE, "Archivo #" . ($fila - 15), $hojaAutor);
					$this->agregarSiNoExiste($archivoObra, $multimedios);
					$obra->archivos[] = $archivoObra;
				}//fin ciclo archivos
				$autor->obras[] = $obra;
			}//fin ciclo obras
			$ficha->autores[] = $autor;
		}//fin ciclo autores
		//print_r($multimedios);

		$this->load->helper('directory');
		$archivosReales = directory_map($carpeta, 1);
		//if(count($archivosReales)>0)echo "Encodeo de archivos es:".mb_detect_encoding($archivosReales[0]);
		//Sacamos lista de $archivosReales(-ficha.xls) NO EXISTENTES EN $multimedios (sobrantes)
		$archivosSobrantes = array();
		foreach ($archivosReales as $archivo) {
			if ($archivo == $this->ARCHIVO_FICHA_TECNICA)
				continue;
			if (!in_array($archivo, $multimedios))
				$archivosSobrantes[] = $archivo;
		}
		//Sacamos lista de $multimedios NO EXISTENTES EN $archivosReales (faltantes)
		$archivosFaltantes = array();
		foreach ($multimedios as $multimedio) {
			if (!in_array($multimedio, $archivosReales))
				$archivosFaltantes[] = $multimedio;
		}

		$ficha->multimedios = $multimedios;
		$ficha->archivosSobrantes = $archivosSobrantes;
		$ficha->archivosFaltantes = $archivosFaltantes;
		//echo "<pre>" . print_r($ficha, TRUE) . "</pre>";

		/* if(count($archivosFaltantes)>0){
		  $error = "Los siguientes archivos no fueron incluídos en el ZIP:<ul>";
		  foreach($archivosFaltantes as $archivo)
		  $error.= "<li>$archivo</li>";
		  $error.= "</ul>";
		  throw new Exception($error);
		  } */

		return $ficha;
	}

	/**
	 * Agrega $valor en $arreglo solo si $valor no está contenido ya en $arreglo
	 * @param string $valor
	 * @param array $arreglo
	 */
	private function agregarSiNoExiste($valor, &$arreglo) {
		if ($valor != '' && !in_array($valor, $arreglo))
			$arreglo[] = $valor;
	}

	/**
	 * Valida la existencia de una celda en el sheet de excel especificado
	 * @param array $sheet Arreglo de un sheet de Excel
	 * @param int $fila
	 * @param mixed $columna string ó entero que indica la columna a usar
	 * @param boolean $errorSiNoExiste Provoca excepción al ser TRUE al no existir la celda
	 * @param string $nombreValorEsperado Nombre del valor esperado para crear mensaje de error si no existe la celda definida por fila y columna
	 * @throws Exception Cuando la celda definida por $fila y $columna no existe
	 * @return mixed Contenido de la celda
	 */
	private function getExcel($sheet, $fila, $columna, $errorSiNoExiste = FALSE, $nombreValorEsperado = '', $nombreSheet = 'Expo') {
		if (!isset($sheet[$fila][$columna])) {
			if ($errorSiNoExiste)
				throw new Exception("No se encuentra el valor $nombreValorEsperado en la fila $fila, columna $columna de la hoja $nombreSheet");
			return '';
		}
		return $sheet[$fila][$columna];
	}

	/**
	 * Crea una nueva exposición con la información recibida de la ficha técnica
	 * @param \FichaTecnica $ficha
	 * @param string $carpetaArchivosOrigen Carpeta donde están los archivos a usar
	 * @throws Exception Al no crear la exposición
	 */
	private function crearExposicion($ficha, $carpetaArchivosOrigen) {
		$idExpo = $this->exposicion_model->agregar($ficha);
		if (empty($idExpo))
			throw new Exception("No fue posible insertar exposición");
		//Creamos carpeta de multimedios para esta exposición
		$carpetaMultimedios = $this->CARPETA_EXPOSICIONES . $idExpo . "/";
		if (!mkdir($carpetaMultimedios))
			throw new Exception("No fue posible crear carpeta de multimedios para la exposición");

		//Alta de multimedios
		$mapa_nombre_x_id_multimedio = array();
		foreach ($ficha->multimedios as $multimedio) {
			if (in_array($multimedio, $ficha->archivosSobrantes))
				continue; //No se toman en cuenta los sobrantes
			$path = $carpetaMultimedios;
			if (in_array($multimedio, $ficha->archivosFaltantes)) {
				$path = ""; //para identificar en BD que el archivo falta
			} else {
				//Movemos archivo a su nueva casa
				rename($carpetaArchivosOrigen . $multimedio, $carpetaMultimedios . $multimedio);
			}
			$id = $this->multimedio_model->agregar($multimedio, $path);
			$mapa_nombre_x_id_multimedio[$multimedio] = $id;
		}

		$updatesExpo = array("carpeta_multimedios" => $carpetaMultimedios); //Los updates a hacer
		if (!empty($ficha->portada))
			$updatesExpo["archivo_portada"] = $mapa_nombre_x_id_multimedio[$ficha->portada];
		if (!empty($ficha->contraportada))
			$updatesExpo["archivo_contraportada"] = $mapa_nombre_x_id_multimedio[$ficha->contraportada];
		if (!empty($ficha->principal1))
			$updatesExpo["archivo_pieza_principal_1"] = $mapa_nombre_x_id_multimedio[$ficha->principal1];
		if (!empty($ficha->principal2))
			$updatesExpo["archivo_pieza_principal_2"] = $mapa_nombre_x_id_multimedio[$ficha->principal2];
		if (!empty($ficha->principal3))
			$updatesExpo["archivo_pieza_principal_3"] = $mapa_nombre_x_id_multimedio[$ficha->principal3];
		if (!empty($ficha->principal4))
			$updatesExpo["archivo_pieza_principal_4"] = $mapa_nombre_x_id_multimedio[$ficha->principal4];
		if (!empty($ficha->principal5))
			$updatesExpo["archivo_pieza_principal_5"] = $mapa_nombre_x_id_multimedio[$ficha->principal5];
		/* INICIA PARCHE PARA TENER THUMBNAIL EN LA EXPOSICIÓN */
		$archivoInvisible = "thumb.png";
		copy("img/iconos/invisible.png", $carpetaMultimedios.$archivoInvisible);
		$idInvisible = $this->multimedio_model->agregar($archivoInvisible, $carpetaMultimedios);
		$updatesExpo["archivo_thumbnail"] = $idInvisible;
		/* FIN PARCHE*/
		//if (!empty($updatesExpo))
			$this->exposicion_model->actualizar($idExpo, $updatesExpo);

		//Autores en exposición
		$ordenAutor = 1;
		foreach ($ficha->autores as $autor) {
			$idAutor = $this->autor_model->agregar($autor->nombre);
			$this->exposicion_x_autor_model->agregar($idExpo, $idAutor, $ordenAutor);
			$ordenAutor++;
			//Obras del autor
			$ordenObra = 1;
			foreach ($autor->obras as $obra) {
				$idObra = $this->obra_model->agregar($idAutor, $obra->titulo, $obra->elaboracion, $obra->tecnica, $obra->dimensiones, $obra->comentarios, count($obra->archivos), $ordenObra);
				$ordenObra++;
				//Multimedios de obra
				$ordenMultimedio = 1;
				foreach ($obra->archivos as $archivo) {
					$idMultimedio = $mapa_nombre_x_id_multimedio[$archivo];
					$this->obra_x_multimedio_model->agregar($idMultimedio, $idObra, $ordenMultimedio);
					$ordenMultimedio++;
				}//fin ciclo archivos
			}//fin ciclo obras
		}//fin ciclo autores
	}

//fin crearExposicion
}

/* End of file upload_ficha.php */
/* Location: ./application/controllers/admin/upload_ficha.php */