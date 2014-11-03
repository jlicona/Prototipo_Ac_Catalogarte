<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 *  Clase para permitir uso de PHPExcel (ubicado en third_party) en CodeIgniter
 * 
 *  ======================================= 
 *  Author     : Muhammad Surya Ikhsanudin 
 *  License    : Protected 
 *  Email      : mutofiyah@gmail.com 
 *   
 *  Dilarang merubah, mengganti dan mendistribusikan 
 *  ulang tanpa sepengetahuan Author 
 *  ======================================= 
 */  
require_once APPPATH."/third_party/PHPExcel.php"; 
 
class Excel extends PHPExcel { 
    public function __construct() { 
        parent::__construct(); 
    } 
}
