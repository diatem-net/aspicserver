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
use aspic\security\ipblacklist\IpBlackListService;
use aspic\security\accesslog\AccessLogService;

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

        //Verification IP
        if(Config::getIpBlackListEnabled() && IpBlackListService::isBlacklisted($_SERVER['REMOTE_ADDR'])){
            $args = array(
                'errorDetails' => Lang::get('error_ipblacklist'),
                'errorCode' => 600
                );
            $this->currentView = new View('error', $args);
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
	
	//verification extraArguments
	if(isset($_GET['e'])){
	    if(!Config::getExtraArgumentsEnabled()){
		Logs::log('Bad request - wrong extra parameters (#600)', Logs::SECURITYALERT);

		$args = array(
		    'errorDetails' => Lang::get('error_arguments'),
		    'errorCode' => 600
		    );
		$this->currentView = new View('error', $args);
		return;
	    }else{
		$decryptedEA = Service::decodeExtraArguments($_REQUEST['e']);
		if(!$decryptedEA){
		    Logs::log('Bad request - decrypt error (#700)', Logs::SECURITYALERT);

		    $args = array(
			'errorDetails' => Lang::get('error_security'),
			'errorCode' => 700
			);
		    $this->currentView = new View('error', $args);
		    return;
		}else{
		    $eargs = json_decode($decryptedEA);
		    if(!$eargs){
			
			Logs::log('Bad request - decrypt error (#800)', Logs::SECURITYALERT);

			$args = array(
			    'errorDetails' => Lang::get('error_security'),
			    'errorCode' => 800
			    );
			$this->currentView = new View('error', $args);
			return;
		    }else{
			Context::put('extraArguments', $eargs);
		    }
		}
	    }
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
                    header('Location:'.Service::getLogoutReturnUrl()); 
                    exit();
                }else{
		    //UPDATE DATAS
		    if(Context::get('extraArguments')){
			CredentialTicket::updateExtraArguments($uid, Context::get('extraArguments'));
		    }
		    
                    //LOGIN
                    $data = array(
                        'uid' => $uid,
                        'userId' => $callAuth['userId'],
                        'groups' => $callAuth['groups'],
                        'userData' => array(),
			'extraArguments' => $callAuth['extraArguments']
                        );
                    $secured = openssl_encrypt(json_encode($data), Config::getSecurityEncryptMethod(), Service::getPrivateKey(), false, Config::getSecurityInitializationVector());
                    $returnUrl = Service::getLoginReturnUrl();
                    if(StringTools::contains($returnUrl, '?')){
                        $returnUrl .= '&';
                    }else{
                        $returnUrl .= '?';
                    }
                    $returnUrl .= 'sid='.Context::get('serviceId').'&s='.urlencode($secured);
		    
                    //Log de connexion en BDD si activé
                    if(Config::getAccessLogEnabled()){
                        AccessLogService::logAccess($callAuth['userId'], Service::getServiceId());
                    }
                    
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
