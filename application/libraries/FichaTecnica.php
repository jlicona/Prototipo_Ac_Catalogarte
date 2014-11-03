<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * 
 *  ======================================= 
 *  Author     : Axel
 *  ======================================= 
 */  


class FichaObra {
    
    public $titulo;
    public $elaboracion;
    public $tecnica;
    public $dimensiones;
    public $comentarios;
    
    /**
     * Arreglo de string con nombres de archivo
     */
    public $archivos = array();
    
    public function __construct() {}
}


class FichaAutor {
    
    public $nombre;
    
    /**
     * @var FichaObra Arreglo de clases FichaObra
     */
    public $obras = array();
    
    public function __construct() {}             
}

class FichaTecnica {
    
    public $idSede;
    public $idDisciplina;
    public $idCondicion;
    public $idDisponibilidad;
    public $nombreExpo;
    public $descExpo;
    public $notaExpo;
    public $fechaInicioExpo;
    public $fechaFinExpo;
    
    public $totalAutores;
    
    public $portada;
    public $contraportada;
    public $principal1;
    public $principal2;
    public $principal3;
    public $principal4;
    public $principal5;
    
    public $multimedios = array();
    
    public $archivosSobrantes = array();
    public $archivosFaltantes = array();
    
    /**
     * @var FichaAutor Arreglo de clases FichaAutor
     */
    public $autores = array();        
            
    public function __construct() {} 
}
