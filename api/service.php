<?php

namespace aspic;

use aspic\utils\JsonLoader;
use aspic\Config;
use jin\lang\ArrayTools;

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
     * Urls de base du service
     * @var array
     */
    private static $baseUrl = array();
	
	/**
	 * Index url utilisée
	 * @var integer	
	 */
	private static $baseUrlIndex = 0;

    /**
     * Url de retour du service après login
     * @var string
     */
    private static $loginReturnUrl;

    /**
     * Url de retour du service après logout
     * @var string
     */
    private static $logoutReturnUrl;

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
				if(is_array($s['url'])){
					self::$baseUrl = $s['url'];
				}else{
					self::$baseUrl[] = $s['url'];
				}
                self::$loginReturnUrl = $s['loginReturnUrl'];
                self::$logoutReturnUrl = $s['logoutReturnUrl'];
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
		$found = false;
		$i = 0;
		foreach(self::$baseUrl AS $baseUrl){
			if (strstr($url, $baseUrl) !== false) {
				self::$baseUrlIndex = $i;
				$found = true;
			}
			$i++;
		}
        
		return $found;
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
    public static function getLoginReturnUrl() {
        self::check();
        return self::$baseUrl[self::$baseUrlIndex].self::$loginReturnUrl;
    }

    /**
     * Retourne l'url de retour du service après logout
     * @return string
     */
    public static function getLogoutReturnUrl() {
        self::check();
        return self::$baseUrl[self::$baseUrlIndex].self::$logoutReturnUrl;
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
