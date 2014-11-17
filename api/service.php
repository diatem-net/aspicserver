<?php

namespace aspic;

use aspic\utils\JsonLoader;
use aspic\Config;


class Service{
    private static $sid;
    private static $initialized = false;
    private static $privateKey;
    private static $baseUrl;
    private static $returnUrl;
    
    
    public static function init($sid){
	self::$sid = $sid;
	
	$services = JsonLoader::loadFile('../config/services.json');
	foreach($services AS $k => $s){
	    if($k == $sid){
		self::$initialized = true;
		self::$privateKey = $s['privateKey'];
		self::$baseUrl = $s['url'];
		self::$returnUrl = $s['returnUrl'];
		break;
	    }
	}
    }
    
    public static function exists(){
	return self::$initialized;
    }
    
    public static function decodeString($data){
	self::check();
	
	$decrypted = openssl_decrypt($data, Config::getSecurityEncryptMethod(), self::$privateKey, false, Config::getSecurityInitializationVector());

	if(!$decrypted){
	    return false;
	}
	return explode('|', $decrypted);
    }
    
    public static function checkCallerUrl($url){
	if(strstr($url, self::$baseUrl) === false){
	    return false;
	}
	return true;
    }
    
    public static function getServiceId(){
	self::check();
	return self::$sid;
    }
    
    public static function getReturnUrl(){
	self::check();
	return self::$returnUrl;
    }
    
    public static function getPrivateKey(){
	self::check();
	return self::$privateKey;
    }
    
    private static function check(){
	if(!self::$initialized){
	    throw new \Exception('Service non initialisé.');
	}
    }

}

