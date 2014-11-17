<?php

namespace aspic\security\ticketmanager;

use aspic\Config;
use jin\filesystem\File;

class TicketManagerFileMethod{
    private $dataFolder;
    
    public function __construct() {
	$p = Config::getTicketManagerParameters();
	$this->dataFolder = '../'.$p['storage'];
    }
    
    public function put($id, $data){
	$f = new File($this->dataFolder.$id, true);
	$f->write($data);
    }
    
    public function get($id){
	$f = new File($this->dataFolder.$id, false);
	$c = $f->getContent();
	
	return $c;
    }
    
    public function delete($id){
	$f = new File($this->dataFolder.$id, false);
	$f->delete();
    }
    
    public function clearOld(){
    }
    
    public function clear(){
    }
}
