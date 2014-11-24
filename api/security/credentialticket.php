<?php

namespace aspic\security;

use aspic\Config;
use aspic\utils\SecureCookie;
use aspic\security\authentification\AuthService;
use aspic\security\ticketmanager\TicketService;
use aspic\Service;
use aspic\Context;
use aspic\security\userdata\UserDataService;
use aspic\security\appzcredentialcheck\AppzCredentialCheckService;

class CredentialTicket{
    public static function isGlobalTicketDefined(){
	return SecureCookie::cookieExists(Config::getSsoUID());
    }
    
    public static function authentifiate($username, $password){
	$r = AuthService::authentifiateUserWithCredentials($username, $password);
	
	if($r){
	    //authentification OK. Création du jeton global
	    $uid = TicketService::createUID($username);
	    
	    //userData
	    $userData = array();
	    if(Config::getUserDataQueryingEnabled()){
		$userData = UserDataService::getUserData($username);
	    }
	    
	    //services
	    $services = array();
	    if(Config::getAppzCredentialCheckEnabled()){
		$services = AppzCredentialCheckService::getCredentials($username);
	    }
	    
	    $data = array(
		'exptime' => time()+(Config::getSecuritySessionMaxTime()*60),
		'uagent' => Context::get('userAgent'),
		'userId' => $username,
		'userData' => $userData,
		'services' => $services
	    );

	    TicketService::put($uid, $data);
	    
	    //Création du cookie
	    SecureCookie::setSecureCookie(Config::getSecuritySsoUID(), Config::getSecuritySsoUID(), $uid, Config::getSecurityServerPrivateKey(), 0, '', '', Config::getSecuritySslEnabled(), Config::getHttpOnlyCookiesEnabled());
	    
	    return $uid;
	}
	return $r;
    }
    
    public static function getGlobalTicketData($uid){
	return TicketService::get($uid);
    }
    
    /**
     * 
     * @param type $uid
     * @param type $serviceId
     * @return string|integer	-1 : erreur de securité. -2 : utilisateur n'ayant pas le droit de se connecter à cet applicatif. -3 session expirée Sinon groupes de droit locaux. (ALL pour tous droits - par défaut si pas de vérification de droit par applicatif)
     */
    public static function checkServiceCall($uid, $serviceId){
	$ticketContent = self::getGlobalTicketData($uid);
	
	if(!$ticketContent){
	    return -3;
	}
	
	if($ticketContent['uagent'] != Context::get('userAgent')){
	    return -1;
	}
	
	if(Config::getAppzCredentialCheckEnabled()){
	    if(isset($ticketContent['services'][$serviceId])){
		if(intval($ticketContent['exptime']) > time()){
		    $groupes = $ticketContent['services'][$serviceId];
		    if($groupes == ''){
			return array('groups' => array(), 'userId' => $ticketContent['userId'], 'userData' => $ticketContent['userData']);
		    }
		    return array('groups' => $groupes, 'userId' => $ticketContent['userId'], 'userData' => $ticketContent['userData']);
		}else{
		    return -3;
		}
	    }else{
		return -2;
	    }
	}
	
	return array('groups' => array(), 'userId' => $ticketContent['userId'], 'userData' => $ticketContent['userData']);
    }
    
    
    public static function logout($uid){
	SecureCookie::delete(Config::getSecuritySsoUID());
	TicketService::delete($uid);
    }
    
    public static function checkGlobalTicket(){
	$valid = SecureCookie::getSecureCookie(Config::getSecuritySsoUID(), Config::getSecuritySsoUID(), Config::getSecurityServerPrivateKey(), Context::get('userAgent'), 0);
	if(!$valid){
	    SecureCookie::delete(Config::getSecuritySsoUID());
	}
	return $valid;
    }
    
}
