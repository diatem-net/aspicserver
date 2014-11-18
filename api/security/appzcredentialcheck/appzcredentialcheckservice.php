<?php

namespace aspic\security\appzcredentialcheck;

use aspic\Config;
use aspic\security\appzcredentialcheck\DatabaseMethod;

class AppzCredentialCheckService{
    private static $credentialCheckService;
    
    public static function getCredentials($username){
	self::initAppzCredentialCheckService();
	
	return self::$credentialCheckService->getCredentials($username);
    }
    
    private static function initAppzCredentialCheckService(){
	if(!self::$credentialCheckService){
	    if(Config::getAppzCredentialCheckMethod() == 'database'){
		self::$credentialCheckService = new DatabaseMethod();
	    }else{
		throw new \Exception('Methode AppzCredentialCheck non reconnue');
	    }
	}
    }
}