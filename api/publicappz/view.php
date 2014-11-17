<?php

namespace aspic\publicappz;

use aspic\Config;
use aspic\publicappz\Lang;

class View{
    private $viewName;
    private $template;
    private $data;
    
    public function __construct($viewName, $data = array()) {
	global $controler;
	$this->viewName = $viewName;
	$this->template = Config::getRenderTemplate();
	$this->data = $data;
    }
    
    public function view(){
	//Include header
	$this->includeFile('header.php');
	
	//Load controler
	$this->includeFile('controlers/'.$this->viewName.'.php');
	$cName = ''.ucfirst($this->viewName).'Controler';
	$GLOBALS['view'] = $this;
	$GLOBALS['controler'] = new $cName($this);
	
	//Include vue
	$this->includeFile('views/'.$this->viewName.'.php');
	
	//Includ footer
	$this->includeFile('footer.php');
    }
    
    public function get($key){
	return $this->data[$key];
    }
    
    private function includeFile($file){
	if(file_exists('../templates/'.$this->template.'/'.$file)){
	    include '../templates/'.$this->template.'/'.$file;
	}else{
	    include '../templates/default/'.$file;
	}
    }
}
