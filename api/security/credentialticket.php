<?php

namespace aspic\security;

use aspic\Config;
use aspic\utils\SecureCookie;
use aspic\security\authentification\AuthService;
use aspic\security\ticketmanager\TicketService;
use aspic\Service;
use aspic\Context;

class CredentialTicket{
    public static function isGlobalTicketDefined(){
	return SecureCookie::cookieExists(Config::getSsoUID());
    }
    
    public static function authentifiate($username, $password){
	$r = AuthService::authentifiateUserWithCredentials($username, $password);
	
	if($r){
	    //authentification OK. Création du jeton global
	    $uid = TicketService::createUID($username);
	    $data = array(
		'exptime' => '',
		'uagent' => Context::get('userAgent'),
		'userId' => $username,
		'services' => array()
	    );

	    TicketService::put($uid, $data);
	    
	    //Création du cookie
	    SecureCookie::setSecureCookie(Service::getServiceId(), Config::getSecuritySsoUID(), $uid, Config::getSecurityServerPrivateKey(), 0, '', '', false, null);
	    
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
     * @return string|integer	-1 : erreur de securité. -2 : utilisateur n'ayant pas le droit de se connecter à cet applicatif. Sinon groupes de droit locaux. (ALL pour tous droits - par défaut si pas de vérification de droit par applicatif)
     */
    public static function checkServiceCall($uid, $serviceId){
	$ticketContent = self::getGlobalTicketData($uid);
	 
	if($ticketContent['uagent'] != Context::get('userAgent')){
	    return -1;
	}
	
	if(Config::getAppzCredentialCheckEnabled()){
	    if(isset($ticketContent['services'][$serviceId])){
		$groupes = $ticketContent['services'][$serviceId];
		if($groupes == ''){
		    return array('groups' => 'ALL', 'userId' => $ticketContent['userId']);
		}
		return array('groups' => $groupes, 'userId' => $ticketContent['userId']);
	    }else{
		return -2;
	    }
	}
	
	return array('groups' => 'ALL', 'userId' => $ticketContent['userId']);
    }
    
    
    public static function logout($uid){
	SecureCookie::delete(Config::getSecuritySsoUID());
	TicketService::delete($uid);
    }
    
    public static function checkGlobalTicket(){
	$valid = SecureCookie::getSecureCookie(Config::getSecuritySsoUID(), Service::getServiceId(), Config::getSecurityServerPrivateKey(), Context::get('userAgent'), 0);
	if(!$valid){
	    SecureCookie::delete(Config::getSecuritySsoUID());
	}
	return $valid;
    }
    
}
