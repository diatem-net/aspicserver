<?php

namespace aspic\security\ticketmanager;

use aspic\Config;
use aspic\security\ticketmanager\TicketManagerFileMethod;

class TicketService{
    private static $ticketManager;
    
    public static function createUID($username){
	return Hash('md5', uniqid().$username);
    }
    
    public static function put($id, $data){
	self::setTicketManager();
	
	$data = json_encode($data);
	$data = openssl_encrypt($data, Config::getSecurityEncryptMethod(), Config::getSecurityServerPrivateKey(), false, Config::getSecurityInitializationVector());
	self::$ticketManager->put($id, $data);
    }
    
    public static function get($id){
	self::setTicketManager();
	
	$data = self::$ticketManager->get($id);
	$data = openssl_decrypt($data, Config::getSecurityEncryptMethod(), Config::getSecurityServerPrivateKey(), false, Config::getSecurityInitializationVector());
	
	return json_decode($data, true);
    }
    
    public static function delete($id){
	self::setTicketManager();
	
	self::$ticketManager->delete($id);
    }
    
    public static function clearOld(){
	self::setTicketManager();
	
	self::$ticketManager->clearOld();
    }
    
    public static function clear(){
	self::setTicketManager();
	
	self::$ticketManager->clear();
    }
    
    private static function setTicketManager(){
	if(!self::$ticketManager){
	    if(Config::getTicketManagerMethod() == 'file'){
		self::$ticketManager = new TicketManagerFileMethod();
	    }else{
		throw new \Exception('Methode de gestion des jetons inconnue.');
	    }
	}
    }
}
