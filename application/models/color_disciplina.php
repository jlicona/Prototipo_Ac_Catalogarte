<?php
class Color_disciplina extends CI_Model {
    
    public $ESCULTURA_Y_PINTURA = 1;
    public $GRAFICA_Y_DIBUJO = 2;
    public $FOTOGRAFIA = 3;
    public $ARQUITECTURA = 4;
    public $MULTIMEDIA_Y_ARTE_DIGITAL = 5;
    public $OTROS = 6;
    
    private $COLORES;

    public function __construct(){
        $this->COLORES = array(
            $this->ESCULTURA_Y_PINTURA => "#EB2C26",
            $this->GRAFICA_Y_DIBUJO => "#699ECB",
            $this->FOTOGRAFIA => "#981A36",
            $this->ARQUITECTURA => "#E45C91",
            $this->MULTIMEDIA_Y_ARTE_DIGITAL => "#CE3067",
            $this->OTROS => "#231F20"
        );
    }

    public function get($tipoDisciplina){
        return $this->COLORES[$tipoDisciplina];
    }
	
}