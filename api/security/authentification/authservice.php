<?php

namespace aspic\security\authentification;

use aspic\Config;
use aspic\security\authentification\DatabaseMethod;

class AuthService{
    private static $authService;
    
    public static function authentifiateUserWithCredentials($username, $password){
	self::initAuthService();
	
	return self::$authService->authentifiateUserWithCredentials($username, $password);
    }
    
    private static function initAuthService(){
	if(!self::$authService){
	    if(Config::getAuthentificationMethod() == 'database'){
		self::$authService = new DatabaseMethod();
	    }else{
		throw new \Exception('Methode d\'authentification non reconnue');
	    }
	}
    }
}

