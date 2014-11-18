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
	try{
	    $f = new File($this->dataFolder.$id, false);
	    $c = $f->getContent();
	    
	    return $c;
	}catch(\Exception $e){
	    return false;
	}
    }
    
    public function delete($id){
	$f = new File($this->dataFolder.$id, false);
	$f->delete();
    }
    
    public function clear(){
	$handle=opendir($this->dataFolder);
	while ($File = readdir($handle)) {
	    if ($File != "." && $File != "..") {
		unlink($this->dataFolder.$File);
		
	    }
	}
	closedir($handle);
    }
}
