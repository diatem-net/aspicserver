<?php

namespace aspic;

class AspicCore{
    public static function autoload($className) {
	$tab = explode('\\', $className);
	$path = strtolower(implode(DIRECTORY_SEPARATOR, $tab)) . '.php';
	$path = str_replace('aspic/', 'api/', $path);
	$path = str_replace('api/aspiccore.php', '', __FILE__) . $path;
	
	if(is_file($path)){
	    require($path);
	}
    }
}

