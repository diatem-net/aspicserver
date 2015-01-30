<?php

namespace aspic;

use aspic\utils\JsonLoader;
use aspic\Config;

/**
 * Gestion des services
 * @author Loïc Gerard <lgerard@diatem.net>
 */
class Service {

    /**
     * ServiceId courant
     * @var int
     */
    private static $sid;

    /**
     * Données des services initialisés
     * @var boolean
     */
    private static $initialized = false;

    /**
     * Clé privée
     * @var string
     */
    private static $privateKey;

    /**
     * Url de base du service
     * @var string
     */
    private static $baseUrl;

    /**
     * Url de retour du service après login/logout
     * @var string
     */
    private static $returnUrl;

    /**
     * Initialisation des données du service courant
     * @param  string $sid ServiceID
     */
    public static function init($sid) {
	self::$sid = $sid;

	$services = JsonLoader::loadPhpFile('../config/services.php');
	foreach ($services AS $k => $s) {
	    if ($k == $sid) {
		self::$initialized = true;
		self::$privateKey = $s['privateKey'];
		self::$baseUrl = $s['url'];
		self::$returnUrl = $s['returnUrl'];
		break;
	    }
	}
    }

    /**
     * Retourne si le service existe
     * @return boolean
     */
    public static function exists() {
	return self::$initialized;
    }

    /**
     * Décode une chaîne encryptée pour échange avec le service
     * @param  string $data Chaîne encodée
     * @return string
     */
    public static function decodeString($data) {
	self::check();

	$decrypted = openssl_decrypt($data, Config::getSecurityEncryptMethod(), self::$privateKey, false, Config::getSecurityInitializationVector());

	if (!$decrypted) {
	    return false;
	}
	return explode('|', $decrypted);
    }

    
    public static function decodeExtraArguments($data) {
	self::check();

	$decrypted = openssl_decrypt($data, Config::getSecurityEncryptMethod(), self::$privateKey, false, Config::getSecurityInitializationVector());

	if (!$decrypted) {
	    return false;
	}
	return $decrypted;
    }

    /**
     * Vérifie si l'url d'appel du service est compatible avec la configuration du service
     * @param  string $url url d'appel
     */
    public static function checkCallerUrl($url) {
	if (strstr($url, self::$baseUrl) === false) {
	    return false;
	}
	return true;
    }

    /**
     * retourne l'ID du service
     * @return string
     */
    public static function getServiceId() {
	self::check();
	return self::$sid;
    }

    /**
     * Retourne l'url de retour du service après login/logouy
     * @return string
     */
    public static function getReturnUrl() {
	self::check();
	return self::$returnUrl;
    }

    /**
     * Retourne la clé privée spécifique au service
     * @return string
     */
    public static function getPrivateKey() {
	self::check();
	return self::$privateKey;
    }

    /**
     * Vérifie si initialisé, si non initialisé, initialise.
     */
    private static function check() {
	if (!self::$initialized) {
	    throw new \Exception('Service non initialisé.');
	}
    }

}
