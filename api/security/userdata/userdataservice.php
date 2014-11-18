<?php

namespace aspic\security\userdata;

use aspic\Config;
use aspic\security\userdata\DatabaseMethod;

class  UserDataService{
    private static $userDataService;
    
    public static function getUserData($username){
	self::initUserDataService();
	
	return self::$userDataService->getUserData($username);
    }
    
    private static function initUserDataService(){
	if(!self::$userDataService){
	    if(Config::getUserDataQueryingMethod() == 'database'){
		self::$userDataService = new DatabaseMethod();
	    }else{
		throw new \Exception('Methode UserDataService non reconnue');
	    }
	}
    }
}

