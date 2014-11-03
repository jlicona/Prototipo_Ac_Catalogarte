<?php

class Exposicion_model extends CI_Model {

	public $NOMBRE_TABLA = "exposicion";
	private $sql;

	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->sql = "SELECT *, year(fecha_inicio) 'ano', ( SELECT 1 + (-0.1*(TRUNCATE((DATEDIFF(CURDATE(), fecha_inicio) /365),0) ))   ) 'alfa' FROM " . $this->NOMBRE_TABLA;
	}

	public function getTodos($idCondicion = NULL, $orden = NULL) {
		$where = " where 1=1";
		if($idCondicion !== NULL)
			$where.= " and id_condicion=$idCondicion";
		$order_by = "";
		if($orden !== NULL)
			$order_by =" order by fecha_fin asc";
		$sql = $this->sql." $where $order_by";
		return $this->db->query($sql)->result();
	}

	public function getVisibles($idCondicion = NULL) {
		$where = " where visible=1";
		if($idCondicion !== NULL)
			$where.= " and id_condicion=$idCondicion";
		$order_by = " order by fecha_fin asc";
		$sql = $this->sql." $where $order_by";
		return $this->db->query($sql)->result();
	}

	public function get($id) {
		return $this->db->query($this->sql . " where id=$id")->row();
	}

	/**
	 * Agrega una exposición basado en los datos de la ficha técnica
	 * @param \FichaTecnica $ficha
	 * @return int Id de la exposición creada
	 */
	public function agregar($ficha) {
		$datos = array(
			"nombre" => $ficha->nombreExpo,
			"descripcion" => $ficha->descExpo,
			"nota" => $ficha->notaExpo,
			"id_disciplina" => $ficha->idDisciplina,
			"id_condicion" => $ficha->idCondicion,
			"id_disponibilidad" => $ficha->idDisponibilidad,
			"id_sede" => $ficha->idSede,
			"id_usuario" => 0, //TODO
		);
		if (!empty($ficha->fechaInicioExpo))
			$datos["fecha_inicio"] = $ficha->fechaInicioExpo;
		if (!empty($ficha->fechaFinExpo))
			$datos["fecha_fin"] = $ficha->fechaFinExpo;

		$this->db->insert($this->NOMBRE_TABLA, $datos);
		return $this->db->insert_id();
	}

	/**
	 * Actualiza la exposición con los datos proporcionados
	 * @param int $idExpo Id de la exposición a actualizar
	 * @param array $datos Datos a actualizar
	 */
	public function actualizar($idExpo, $datos) {
		return $this->db->update($this->NOMBRE_TABLA, $datos, array("id" => $idExpo));
	}
	
	/**
	 * Actualiza visibilidad del registro
	 * @param int $idExpo
	 * @return type
	 */
	public function publicar($idExpo){
		$datos["visible"] = 1;
		return $this->actualizar($idExpo, $datos);
	}
	
	/**
	 * Actualiza visibilidad del registro
	 * @param int $idExpo
	 * @return type
	 */
	public function ocultar($idExpo){
		$datos["visible"] = 0;
		return $this->actualizar($idExpo, $datos);
	}

	/**
	 * Devuelve un arreglo con estructuras de información con detalles básicos de las exposiciones
	 * @param int $idCondicion Filtro por condición
	 * @return array Arreglo con arreglos que detallan exposiciones encontradas con la condición especificada
	 */
	public function getDetalles($idCondicion){
		/*$this->output->set_header('Content-Type: application/json; charset=utf-8');*/
		$salida = array();
		$tagError = "error";

		$this->load->model("disciplina_model");
		$this->load->model("condicion_model");
		$this->load->model("sede_model");
		$this->load->model("disponibilidad_model");
		$this->load->model("autor_model");
		$this->load->model("obra_model");
		$this->load->model("obra_x_multimedio_model");
		$this->load->model("multimedio_model");
		
		//Consulta exposiciones y valida si hay resultados
		$exposiciones = $this->getVisibles($idCondicion);
		foreach ($exposiciones as $exposicion) {
			$disciplina = $this->disciplina_model->get($exposicion->id_disciplina);
			if (empty($disciplina)) {
				$salida = array($tagError => "No existe disciplina con id " . $exposicion->id_disciplina);
				//$this->escribirJson($salida);
				return $salida;
			}

			$condicion = $this->condicion_model->get($exposicion->id_condicion);
			if (empty($condicion)) {
				$salida = array($tagError => "No existe condición con id " . $exposicion->id_condicion);
				//$this->escribirJson($salida);
				return $salida;
			}

			$sede = $this->sede_model->get($exposicion->id_sede);
			if (empty($sede)) {
				$salida = array($tagError => "No existe sede con id " . $exposicion->id_sede);
				//$this->escribirJson($salida);
				return $salida;
			}

			$disponibilidad = $this->disponibilidad_model->get($exposicion->id_disponibilidad);
			if (empty($disponibilidad)) {
				$salida = array($tagError => "No existe disponibilidad con id " . $exposicion->id_disponibilidad);
				//$this->escribirJson($salida);
				return $salida;
			}

			$alfaFinal = (double) $exposicion->alfa;
			if ($alfaFinal > 1)
				$alfaFinal = 1;
			if ($alfaFinal < 0.1)
				$alfaFinal = 0.1;

			//Llenado de datos Exposición
			$exposicionSalida = array("id" => $exposicion->id, "nombre" => $exposicion->nombre,
				"descripcion" => $exposicion->descripcion, "nota" => $exposicion->nota,
				"idDisciplina" => $exposicion->id_disciplina,
				"disciplina" => $disciplina->nombre,
				"idCondicion" => $exposicion->id_condicion,
				"condicion" => $condicion->nombre,
				"idSede" => $exposicion->id_sede,
				"sede" => $sede->nombre,
				"disponibilidad" => $disponibilidad->nombre,
				"ano" => $exposicion->ano,
				"inicio" => $exposicion->fecha_inicio,
				"fin" => $exposicion->fecha_fin,
				"color" => $disciplina->color,
				"alfa" => $alfaFinal,
				"borde" => $condicion->borde,
				"carpeta_multimedios" => $exposicion->carpeta_multimedios,
				"icono" => $sede->icono);
			if ($exposicion->archivo_thumbnail != '')
				$exposicionSalida["archivo_thumbnail"] = $this->getInfoArchivo($exposicion->archivo_thumbnail);
			if ($exposicion->archivo_portada != '')
				$exposicionSalida["archivo_portada"] = $this->getInfoArchivo($exposicion->archivo_portada);
			$salida[] = $exposicionSalida;
		}//fin ciclo exposiciones

		//$this->escribirJson($salida);
		return $salida;
	}
	
	/**
	 * Devuelve una estructura de información con detalles profundos de la exposición
	 * @param int $idExpo id de la exposición
	 * @return array Detalles de la exposición ó detalles de error
	 */
	public function getDetallesExposicion($idExpo = 0){
		//$this->output->set_header('Content-Type: application/json; charset=utf-8');

		$salida = array();
		$tagError = "error";
		
		$this->load->model("disciplina_model");
		$this->load->model("condicion_model");
		$this->load->model("sede_model");
		$this->load->model("disponibilidad_model");
		$this->load->model("autor_model");
		$this->load->model("obra_model");
		$this->load->model("obra_x_multimedio_model");
		$this->load->model("multimedio_model");
		$this->load->model("articulo_model");
		$this->load->model("hoja_model");

		if ($idExpo == 0 || !is_numeric($idExpo)) {
			$salida = array($tagError => "No se especificó id numérico de exposición");
			//$this->escribirJson($salida);
			return $salida;
		}

		//Consulta exposiciones y valida si hay resultados
		$exposicion = $this->get($idExpo);
		if (empty($exposicion)) {
			$salida = array($tagError => "Exposición con id $idExpo no existe");
			//$this->escribirJson($salida);
			return $salida;
		}

		$disciplina = $this->disciplina_model->get($exposicion->id_disciplina);
		if (empty($disciplina)) {
			$salida = array($tagError => "No existe disciplina con id " . $exposicion->id_disciplina);
			//$this->escribirJson($salida);
			return $salida;
		}

		$condicion = $this->condicion_model->get($exposicion->id_condicion);
		if (empty($condicion)) {
			$salida = array($tagError => "No existe condición con id " . $exposicion->id_condicion);
			//$this->escribirJson($salida);
			return $salida;
		}

		$sede = $this->sede_model->get($exposicion->id_sede);
		if (empty($sede)) {
			$salida = array($tagError => "No existe sede con id " . $exposicion->id_sede);
			//$this->escribirJson($salida);
			return $salida;
		}

		$disponibilidad = $this->disponibilidad_model->get($exposicion->id_disponibilidad);
		if (empty($disponibilidad)) {
			$salida = array($tagError => "No existe disponibilidad con id " . $exposicion->id_disponibilidad);
			//$this->escribirJson($salida);
			return $salida;
		}

		$alfaFinal = (double) $exposicion->alfa;
		if ($alfaFinal > 1)
			$alfaFinal = 1;
		if ($alfaFinal < 0.1)
			$alfaFinal = 0.1;

		//Llenado de datos Exposición
		$salida = array("id" => $idExpo, "nombre" => $exposicion->nombre,
			"descripcion" => $exposicion->descripcion, "nota" => $exposicion->nota,
			"idDisciplina" => $exposicion->id_disciplina,
			"disciplina" => $disciplina->nombre,
			"idCondicion" => $exposicion->id_condicion,
			"condicion" => $condicion->nombre,
			"idSede" => $exposicion->id_sede,
			"sede" => $sede->nombre,
			"disponibilidad" => $disponibilidad->nombre,
			"ano" => $exposicion->ano,
			"inicio" => $exposicion->fecha_inicio,
			"fin" => $exposicion->fecha_fin,
			"color" => $disciplina->color,
			"alfa" => $alfaFinal,
			"borde" => $condicion->borde,
			"carpeta_multimedios" => $exposicion->carpeta_multimedios,
			"icono" => $sede->icono);
		if ($exposicion->archivo_thumbnail != '')
			$salida["archivo_thumbnail"] = $this->getInfoArchivo($exposicion->archivo_thumbnail);
		if ($exposicion->archivo_portada != '')
			$salida["archivo_portada"] = $this->getInfoArchivo($exposicion->archivo_portada);
		if ($exposicion->archivo_contraportada != '')
			$salida["archivo_contraportada"] = $this->getInfoArchivo($exposicion->archivo_contraportada);
		if ($exposicion->archivo_pieza_principal_1 != '')
			$salida["archivo_pieza_principal_1"] = $this->getInfoArchivo($exposicion->archivo_pieza_principal_1);
		if ($exposicion->archivo_pieza_principal_2 != '')
			$salida["archivo_pieza_principal_2"] = $this->getInfoArchivo($exposicion->archivo_pieza_principal_2);
		if ($exposicion->archivo_pieza_principal_3 != '')
			$salida["archivo_pieza_principal_3"] = $this->getInfoArchivo($exposicion->archivo_pieza_principal_3);
		if ($exposicion->archivo_pieza_principal_4 != '')
			$salida["archivo_pieza_principal_4"] = $this->getInfoArchivo($exposicion->archivo_pieza_principal_4);
		if ($exposicion->archivo_pieza_principal_5 != '')
			$salida["archivo_pieza_principal_5"] = $this->getInfoArchivo($exposicion->archivo_pieza_principal_5);

		//Consulta autores y valida si hay resultados
		$autores = $this->autor_model->getEnExposicion($idExpo);
		if (empty($autores)) {
			$salida = array($tagError => "No existen autores en la exposición $idExpo");
			//$this->escribirJson($salida);
			return $salida;
		}
		$listaAutores = array();
		//Recorre autores
		foreach ($autores as $autor) {
			$autorSalida = array("nombre" => $autor->nombre);
			$listaObras = array();
			//Recorremos obras del autor
			$obras = $this->obra_model->getConAutor($autor->id);
			foreach ($obras as $obra) {
				$obraSalida = array("titulo" => $obra->titulo,
					"comentario" => $obra->comentario,
					"elaboracion" => $obra->elaboracion,
					"tecnica" => $obra->tecnica,
					"dimensiones" => $obra->dimensiones);
				$listaMultimedios = array();
				//Recorremos multimedios de la obra
				$multimedios = $this->obra_x_multimedio_model->getConObra($obra->id);
				foreach ($multimedios as $multimedio)
					$listaMultimedios[] = $this->getInfoArchivo($multimedio->id_multimedio);
				$obraSalida["multimedios"] = $listaMultimedios;
				$listaObras[] = $obraSalida;
			}
			$autorSalida["obras"] = $listaObras;
			$listaAutores[] = $autorSalida;
		}
		$salida["autores"] = $listaAutores;

		//Consulta artículos
		$articulos_x_expo = $this->articulo_model->getEnExposicion($idExpo);
		$listaArticulos = array();
		//Recorre articulos
		foreach ($articulos_x_expo as $articulo_expo) {
			$articulo = $this->articulo_model->get($articulo_expo->id_articulo);
			$articuloSalida = array("nombre" => $articulo->nombre, "autor" => $articulo->autor,
					"referencia" => $articulo->referencia);
			$listaHojas = array();
			//Recorremos hojas del articulo
			$hojas = $this->hoja_model->getConArticulo($articulo->id);
			foreach ($hojas as $hoja) {
				$listaHojas[] = $hoja->html;
			}
			$articuloSalida["hojas"] = $listaHojas;
			$listaArticulos[] = $articuloSalida;
		}
		$salida["articulos"] = $listaArticulos;
		//$this->escribirJson($salida);
		return $salida;
	}
	
	private function getInfoArchivo($idArchivo, $incluirPath = FALSE) {
		$archivo = $this->multimedio_model->get($idArchivo);
		if (empty($archivo))
			return NULL;
		$salida = array("nombre" => $archivo->nombre_archivo, "tipo" => $archivo->tipo_archivo);
		if ($incluirPath === TRUE)
			$salida["carpeta"] = $archivo->path;
		return $salida;
	}
	
}
