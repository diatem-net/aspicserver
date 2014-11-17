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

class PublicAppz{
    
    private $currentView;
    private $service;
    private $userAgent;
    private $jeton;
    private $callUrl;

    public function __construct() {
	$this->securityCheck();
	if(!$this->currentView){
	    $this->credentialTicketCheck();
	}
	
	$this->render();
    }
    
    private function securityCheck(){
	//Vérification des variables GET requises
	if(!isset($_GET['sid']) || !isset($_GET['s'])){
	    $args = array(
		'errorDetails' => Lang::get('error_arguments'),
		'errorCode' => 4
	    );
	    $this->currentView = new View('error', $args);
	    return;
	}
	
	Context::put('serviceId', $_GET['sid']);
	
	//Vérification qu'un service existe
	Service::init($_GET['sid']);
	if(!Service::exists()){
	    $args = array(
		'errorDetails' => Lang::get('error_unknownservice')
	    );
	    $this->currentView = new View('error', $args);
	    return;
	}
	
	//Vérification decryptage des données
	$decrypted = Service::decodeString($_GET['s']);
	
	if(!$decrypted || count($decrypted) != 3){
	    $args = array(
		'errorDetails' => Lang::get('error_security'),
		'errorCode' => 1
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
	    $args = array(
		'errorDetails' => Lang::get('error_security'),
		'errorCode' => 2
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
		$args = array(
		'errorDetails' => Lang::get('error_security'),
		'errorCode' => 3
		);
		$this->currentView = new View('error', $args);
		return;
	    }else if($callAuth == -2){
		$args = array(
		);
		$this->currentView = new View('userunauthorized', $args);
		return;
	    }else{
		
		
		if(isset($_GET['logout'])){
		    //Si LOGOUT

		    CredentialTicket::logout($uid);
		    header('Location:'.Service::getReturnUrl()); 
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

		    header('Location:'.$returnUrl);  
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
