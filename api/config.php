<?php

namespace aspic;

use aspic\utils\JsonLoader;

class Config{
    
    private static $data;
    private static $version = '0.1.0';
    
    private static function loadConfig(){
	if(!self::$data){
	    self::$data = JsonLoader::loadPhpFile('../config/configuration.php');
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
    
    public static function getSecuritySslEnabled(){
	self::loadConfig();
	return (self::$data['security']['sslEnabled'] == 'true') ? true : false;
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
    
    public static function getUserDataQueryingEnabled(){
	self::loadConfig();
	return (self::$data['querying']['userDataQuerying']['enabled'] == 'true') ? true : false;
    }
    
    public static function getUserDataQueryingMethod(){
	self::loadConfig();
	return self::$data['querying']['userDataQuerying']['method'];
    }
    
    public static function getUserDataQueryingParameters(){
	self::loadConfig();
	return self::$data['querying']['userDataQuerying']['parameters'];
    }
    
    public static function getSecuritySessionMaxTime(){
	self::loadConfig();
	return self::$data['security']['sessionMaxTime'];
    }
    
    public static function getLogsEnabled(){
	self::loadConfig();
	return (self::$data['logs']['enabled'] == 'true') ? true : false;
    }
    
    public static function getLogsticketCheckEnabled(){
	self::loadConfig();
	return (self::$data['logs']['log_ticketcheck'] == 'true') ? true : false;
    }
    
    public static function getLogsPhpErrorsEnabled(){
	self::loadConfig();
	return (self::$data['logs']['log_phperrors'] == 'true') ? true : false;
    }
    
    public static function getLogsCnxFailEnabled(){
	self::loadConfig();
	return (self::$data['logs']['log_cnxfail'] == 'true') ? true : false;
    }
    
    public static function getLogsCnxSuccessEnabled(){
	self::loadConfig();
	return (self::$data['logs']['log_cnxsuccess'] == 'true') ? true : false;
    }
    
    public static function getLogsSecurityAlertEnabled(){
	self::loadConfig();
	return (self::$data['logs']['log_securityalert'] == 'true') ? true : false;
    }
    
    public static function getSecurityCronKey(){
	self::loadConfig();
	return self::$data['security']['cronKey'];
    }
    
    public static function getSecurityCronIp(){
	self::loadConfig();
	return self::$data['security']['cronIp'];
    }
    
    public static function getHttpOnlyCookiesEnabled(){
	if(PHP_VERSION_ID < 50200){
	    return false;
	}else{
	    return true;
	}
    }
    
    public static function getVersion(){
	return self::$version;
    }
    
}
