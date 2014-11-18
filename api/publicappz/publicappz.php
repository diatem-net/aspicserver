<?php

namespace aspic\publicappz;

use aspic\Config;
use aspic\publicappz\Lang;
use aspic\Service;
use aspic\utils\SecureCookie;
use aspic\security\CredentialTicket;
use aspic\Context;
use aspic\security\ticketmanager\TicketService;
use jin\lang\StringTools;
use aspic\Logs;

class PublicAppz{
    
    private $currentView;
    private $service;
    private $userAgent;
    private $jeton;
    private $callUrl;

    private static $errorCatched;
    
    public function __construct() {

	$this->securityCheck();
	if(!$this->currentView){
	    $this->credentialTicketCheck();
	}

	$this->render();
    }
    
    public static function errorHandler_standard($errno, $errstr, $errfile, $errline){
	var_dump($errfile);
	var_dump($errline);
	var_dump($errstr);
	var_dump($errno);
	
	Logs::log($errstr.' (#'.$errno.') in '.$errfile.' line '.$errline, Logs::PHPERROR);
	
	self::$errorCatched = true;
	
	$args = array(
	    'errorDetails' => Lang::get('error_phperror'),
	    'errorCode' => 12
	);
	$v = new View('error', $args);
	$v->view();
	exit();
    }
    
    public static function errorHandler_exceptions(\Exception $exception){
	self::$errorCatched = true;
	
	Logs::log($exception->getMessage().' (#'.$exception->getCode().') in '.$exception->getFile().' line '.$exception->getLine(), Logs::PHPERROR);
	
	$args = array(
	    'errorDetails' => Lang::get('error_phperror'),
	    'errorCode' => 12
	);
	$v = new View('error', $args);
	$v->view();
	exit();
    }
    
    public static function errorHandler_fatal() {
	$error = error_get_last();
	if ($error !== NULL && !self::$errorCatched) {
	    $errno   = $error["type"];
	    $errfile = $error["file"];
	    $errline = $error["line"];
	    $errstr  = $error["message"];
    
	    Logs::log($errstr.' (#'.$errno.') in '.$errfile.' line '.$errline, Logs::PHPERROR);
	}
    }

    private function securityCheck(){
	//Vérification SSL
	if(Config::getSecuritySslEnabled() && empty($_SERVER['HTTPS'])){
	    Logs::log('no SSL access', Logs::SECURITYALERT);
	    
	    $args = array(
	    );
	    $this->currentView = new View('sslonly', $args);
	    return;
	}
	
	//Vérification des variables GET requises
	if(!isset($_GET['sid']) || !isset($_GET['s'])){
	    Logs::log('Bad request - wrong parameters (#100)', Logs::SECURITYALERT);
	    
	    $args = array(
		'errorDetails' => Lang::get('error_arguments'),
		'errorCode' => 100
	    );
	    $this->currentView = new View('error', $args);
	    return;
	}
	
	Context::put('serviceId', $_GET['sid']);
	
	//Vérification qu'un service existe
	Service::init($_GET['sid']);
	if(!Service::exists()){
	    Logs::log('Bad request - unexistant service (#200)', Logs::SECURITYALERT);
	    
	    $args = array(
		'errorDetails' => Lang::get('error_unknownservice'),
		'errorCode' => 200
	    );
	    $this->currentView = new View('error', $args);
	    return;
	    
	}
	
	//Vérification decryptage des données
	$decrypted = Service::decodeString($_GET['s']);
	
	if(!$decrypted || count($decrypted) != 3){
	    Logs::log('Bad request - decrypt error (#300)', Logs::SECURITYALERT);
	    
	    $args = array(
		'errorDetails' => Lang::get('error_security'),
		'errorCode' => 300
	    );
	    $this->currentView = new View('error', $args);
	    return;
	}
	
	$userAgent = $decrypted[2];
	$callUrl = $decrypted[1];
	Context::put('userAgent', $userAgent);
	Context::put('callUrl', $callUrl);
	
	
	//Vérification Url d'appel
	if(!Service::checkCallerUrl($callUrl)){
	    Logs::log('Bad request - wrong call url (#400)', Logs::SECURITYALERT);
	    
	    $args = array(
		'errorDetails' => Lang::get('error_security'),
		'errorCode' => 400
	    );
	    $this->currentView = new View('error', $args);
	    return;
	}
    }
    
    private function credentialTicketCheck(){
	if(CredentialTicket::isGlobalTicketDefined()){
	    //Cookie global defini : OK
	    $uid = CredentialTicket::checkGlobalTicket();
	    
	    if(!$uid){
		 $this->currentView = new View('login', array());
		return;
	    }
	    
	    //On teste si le service courant a le droit d'appeler
	    $callAuth = CredentialTicket::checkServiceCall($uid, Context::get('serviceId'));
	    if($callAuth == -1){
		Logs::log('Bad request - user agent not correct (#500)', Logs::SECURITYALERT);
		
		$args = array(
		'errorDetails' => Lang::get('error_security'),
		'errorCode' => 500
		);
		$this->currentView = new View('error', $args);
		return;
	    }else if($callAuth == -2){
		Logs::log('User not authorized for this service', Logs::CNXFAIL);
		$args = array(
		);
		$this->currentView = new View('userunauthorized', $args);
		return;
	    
	    }else if($callAuth == -3){
		$args = array(
		);
		$this->currentView = new View('login', $args);
		return;
	    }else{
		
		
		if(isset($_GET['logout'])){
		    //Si LOGOUT
		    Logs::log('Logout successfull', Logs::CNXSUCCESS);
		    
		    CredentialTicket::logout($uid);
		    header('Location:'.Service::getReturnUrl()); 
		    exit();
		}else{
		    //LOGIN
		    $data = array(
			'uid' => $uid,
			'userId' => $callAuth['userId'],
			'groups' => $callAuth['groups'],
			'userData' => array()
			);
		    $secured = openssl_encrypt(json_encode($data), Config::getSecurityEncryptMethod(), Service::getPrivateKey(), false, Config::getSecurityInitializationVector());
		    $returnUrl = Service::getReturnUrl();
		    if(StringTools::contains('?', $returnUrl)){
			$returnUrl .= '&';
		    }else{
			$returnUrl .= '?';
		    }
		    $returnUrl .= 'sid='.Context::get('serviceId').'&s='.urlencode($secured);

		    Logs::log('Login successfull', Logs::CNXSUCCESS);
		    
		    header('Location:'.$returnUrl);
		    exit();
		}
		
		
	    }
	}else{
	    $this->currentView = new View('login', array());
	    return;
	}

    }
    
    
    private function render(){
	$this->currentView->view();
    }
}
