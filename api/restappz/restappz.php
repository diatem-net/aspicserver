<?php

namespace aspic\restappz;

use aspic\Context;
use aspic\security\CredentialTicket;
use aspic\Service;
use jin\lang\ListTools;
use aspic\Config;
use aspic\Logs;

class RestAppz{
    public function __construct() {
	$this->checkSecurity();
	$this->checkService();
	$this->checkDecode();
	$this->checkUrl();
	$this->checkCredentialTicket();
    }
    
    private function checkSecurity(){
	//Vérification SSL
	if(Config::getSecuritySslEnabled() && empty($_SERVER['HTTPS'])){
	    header('HTTP/1.1 400 Bad Request', true, 400);
	    echo '400 BAD REQUEST';
	    exit();
	}
	
	//Check parameters
	if(!isset($_REQUEST['sid']) || !isset($_REQUEST['s'])){
	    header('HTTP/1.1 400 Bad Request', true, 400);
	    echo '400 BAD REQUEST';
	    exit();
	}
	
	Context::put('serviceId', $_REQUEST['sid']);
    }
    
    private function checkService(){
	//Check service
	Service::init(Context::get('serviceId'));
	if(!Service::exists()){
	    header('HTTP/1.1 401 Unauthorized', true, 401);
	    echo '401 UNAUTHORIZED';
	    exit();
	}
    }
    
    private function checkDecode(){
	//Decodage des données
	$decrypted = Service::decodeString($_REQUEST['s']);
	if(count($decrypted) != 3){
	    header('HTTP/1.1 400 Bad Request', true, 400);
	    echo '400 BAD REQUEST';
	    exit();
	}
	
	Context::put('uid', $decrypted[0]);
	Context::put('callUrl', $decrypted[1]);
	Context::put('userAgent', $decrypted[2]);
	
    }
    
    private function checkUrl(){
	if(!Service::checkCallerUrl(Context::get('callUrl'))){
	    header('HTTP/1.1 401 Unauthorized', true, 401);
	    echo '401 UNAUTHORIZED';
	    exit();
	}
    }
    
    private function checkCredentialTicket(){
	$callAuth = CredentialTicket::checkServiceCall(Context::get('uid'), Context::get('serviceId'));
	
	if($callAuth == -1){
	    header('HTTP/1.1 401 Unauthorized', true, 401);
	    echo '401 UNAUTHORIZED';
	    exit();
	}else if($callAuth == -2){
	    header('HTTP/1.1 401 Unauthorized', true, 401);
	    echo '401 UNAUTHORIZED';
	    exit();
	}else if($callAuth == -3){
	    header('HTTP/1.1 401 Unauthorized', true, 401);
	    echo '401 UNAUTHORIZED';
	    exit();
	}else{
	    //OK retour site
	    Logs::log('Ticket checked successfuly', Logs::TICKETCHECK);
	    
	    
	    
	    $data = array(
		'groups' => $callAuth['groups'],
		'userData' => $callAuth['userData'],
		'userId' => $callAuth['userId']
	    );
	    
	    //echo 'ICI : ';
	    //var_dump($data);
	    //exit;
	    
	    $secured = openssl_encrypt(json_encode($data), Config::getSecurityEncryptMethod(), Service::getPrivateKey(), false, Config::getSecurityInitializationVector());
	    echo $secured;
	}
    }
}
