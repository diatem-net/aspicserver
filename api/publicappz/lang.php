<?php

namespace aspic\publicappz;

use aspic\utils\JsonLoader;
use aspic\Config;

class Lang{
    
    private static $data;
    private static $codeLang = 'fr';
    
    private static function loadLang(){
	if(!self::$data){
	    if(is_file('../templates/'.Config::getRenderTemplate().'/lang/'.self::$codeLang.'.json')){
		self::$data = JsonLoader::loadFile('../templates/'.Config::getRenderTemplate().'/lang/'.self::$codeLang.'.json');
	    }else{
		self::$data = JsonLoader::loadFile('../templates/default/lang/'.self::$codeLang.'.json');
	    }
	}
    }
    
    public static function get($key){
	self::loadLang();
	if(isset(self::$data[$key])){
	    return self::$data[$key];
	}
	return '['.$key.']';
    }
    
}
