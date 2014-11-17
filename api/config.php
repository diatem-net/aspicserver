<?php

namespace aspic;

use aspic\utils\JsonLoader;

class Config{
    
    private static $data;
    
    private static function loadConfig(){
	if(!self::$data){
	    self::$data = JsonLoader::loadFile('../config/configuration.json');
	}
    }
    
    public static function getRenderTemplate(){
	self::loadConfig();
	$t = self::$data['render']['template'];
	if($t == ''){
	    return 'default';
	}else{
	    return $t;
	}
    }
    
    public static function getServerUrl(){
	self::loadConfig();
	return self::$data['security']['serverUrl'];
    }
    
    public static function getSecurityEncryptMethod(){
	self::loadConfig();
	return self::$data['security']['encryptMethod'];
    }
    
    public static function getSecurityInitializationVector(){
	self::loadConfig();
	return self::$data['security']['initializationVector'];
    }
    
    public static function getSsoUID(){
	self::loadConfig();
	return self::$data['security']['ssoUID'];
    }
    
    public static function getDbType(){
	self::loadConfig();
	return self::$data['querying']['database']['dbType'];
    }
    
    public static function getDbHost(){
	self::loadConfig();
	return self::$data['querying']['database']['dbHost'];
    }
    
    public static function getDbName(){
	self::loadConfig();
	return self::$data['querying']['database']['dbName'];
    }
    
    public static function getDbUser(){
	self::loadConfig();
	return self::$data['querying']['database']['dbUser'];
    }
    
    public static function getDbPort(){
	self::loadConfig();
	return intval(self::$data['querying']['database']['dbPort']);
    }
    
    public static function getDbPassword(){
	self::loadConfig();
	return self::$data['querying']['database']['dbPassword'];
    }
    
    public static function getJinLibraryLocation(){
	self::loadConfig();
	return self::$data['librariesLocation']['jin'];
    }
    
    public static function getSecurityPasswordEcnryptMethod(){
	self::loadConfig();
	return self::$data['security']['passwordEncryptMethod'];
    }
    
    public static function getAuthentificationMethod(){
	self::loadConfig();
	return self::$data['querying']['authentification']['method'];
    }
    
    public static function getAuthentificationParameters(){
	self::loadConfig();
	return self::$data['querying']['authentification']['parameters'];
    }
    
    public static function getTicketManagerMethod(){
	self::loadConfig();
	return self::$data['ticketManagement']['method'];
    }
    
    public static function getTicketManagerParameters(){
	self::loadConfig();
	return self::$data['ticketManagement']['parameters'];
    }
    
    public static function getSecurityServerPrivateKey(){
	self::loadConfig();
	return self::$data['security']['serverPrivateKey'];
    }
    
    public static function getSecuritySsoUID(){
	self::loadConfig();
	return self::$data['security']['ssoUID'];
    }
    
    public static function getAppzCredentialCheckEnabled(){
	self::loadConfig();
	return (self::$data['querying']['appzCredentialCheckQuerying']['enabled'] == 'true') ? true : false;
    }
    
    public static function getAppzCredentialCheckMethod(){
	self::loadConfig();
	return self::$data['querying']['appzCredentialCheckQuerying']['method'];
    }
    
    public static function getAppzCredentialCheckParameters(){
	self::loadConfig();
	return self::$data['querying']['appzCredentialCheckQuerying']['parameters'];
    }
    
}
