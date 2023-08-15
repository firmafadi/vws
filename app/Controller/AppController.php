<?php
namespace Controller;

use \Core\Helper;
use \Controller\LogController;
use \Services_JSON;

class AppController{
    private $json;
    private $thnTagih;
	
    public function __construct(){		
        header('Access-Control-Allow-Origin:*'); 
        $this->json = new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS);
        $this->setThnTagih(15);
    }
    
    public function index(){
        echo 'VTax Web Services';
    }

    public function setThnTagih($thn){
        $this->thnTagih = $thn;
    }

    public function getThnTagih(){
        return $this->thnTagih;
    }
}
