<?php

namespace aspic;

use aspic\utils\JsonLoader;

/**
 * Gestion des données de configuration Aspic
 * @author Loïc Gerard <lgerard@diatem.net>
 */
class Config{
	/**
	 * Données issues du fichier de configuration
	 * @var array
	 */
	private static $data;


	/**
	 * Version du serveur
	 * @var string
	 */
	private static $version = '0.1.0';

	
	/**
	 * Retourne le nom du template de rendu utilisé
	 * @return string
	 */
	public static function getRenderTemplate(){
		self::loadConfig();
		$t = self::$data['render']['template'];
		if($t == ''){
			return 'default';
		}else{
			return $t;
		}
	}


	/**
	 * Retourne l'url du serveur Aspic
	 * @return string
	 */
	public static function getServerUrl(){
		self::loadConfig();
		return self::$data['security']['serverUrl'];
	}


	/**
	 * Retourne la méthode de sécurité utilisée pour encrypter les données
	 * @return string
	 */
	public static function getSecurityEncryptMethod(){
		self::loadConfig();
		return self::$data['security']['encryptMethod'];
	}


	/**
	 * Retourne le vecteur d'initialisation utiliser pour l'encryption des données
	 * @return string
	 */
	public static function getSecurityInitializationVector(){
		self::loadConfig();
		return self::$data['security']['initializationVector'];
	}


	/**
	 * Retourne l'identifiant du serveur
	 * @return string
	 */
	public static function getSsoUID(){
		self::loadConfig();
		return self::$data['security']['ssoUID'];
	}


	/**
	 * Retourne le type de base de données utilisé. 
	 * @return string supporté : postgresql
	 */
	public static function getDbType(){
		self::loadConfig();
		return self::$data['querying']['database']['dbType'];
	}


	/**
	 * retourne l'adresse de la base de donnée
	 * @return string
	 */
	public static function getDbHost(){
		self::loadConfig();
		return self::$data['querying']['database']['dbHost'];
	}


	/**
	 * Retourne le nom de la base de données
	 * @return string
	 */
	public static function getDbName(){
		self::loadConfig();
		return self::$data['querying']['database']['dbName'];
	}


	/**
	 * Retourne l'utilisateur de la base de données
	 * @return string
	 */
	public static function getDbUser(){
		self::loadConfig();
		return self::$data['querying']['database']['dbUser'];
	}


	/**
	 * Retourne le port du serveur de base de données
	 * @return int
	 */
	public static function getDbPort(){
		self::loadConfig();
		return intval(self::$data['querying']['database']['dbPort']);
	}


	/**
	 * Retourne le password utilisé pour la connexion à la base de données
	 * @return string
	 */
	public static function getDbPassword(){
		self::loadConfig();
		return self::$data['querying']['database']['dbPassword'];
	}


	/**
	 * Retourne le chemin relatif du dossier d'installation de la librairie JIN
	 * @return string
	 */
	public static function getJinLibraryLocation(){
		self::loadConfig();
		return self::$data['librariesLocation']['jin'];
	}


	/**
	 * Retourne la méthode d'encryption utilisée pour stocker les mots de passe des utilisateurs
	 * @return string
	 */
	public static function getSecurityPasswordEcnryptMethod(){
		self::loadConfig();
		return self::$data['security']['passwordEncryptMethod'];
	}


	/**
	 * Retourne la méthode de traitement utilisée pour l'authentification. (database par défaut)
	 * @return string
	 */
	public static function getAuthentificationMethod(){
		self::loadConfig();
		return self::$data['querying']['authentification']['method'];
	}


	/**
	 * Retourne les paramètres de configuration de la méthode d'authentification.
	 * @return array
	 */
	public static function getAuthentificationParameters(){
		self::loadConfig();
		return self::$data['querying']['authentification']['parameters'];
	}


	/**
	 * Retourne la méthode utilisée pour la gestion des jetons d'authentification
	 * @return string
	 */
	public static function getTicketManagerMethod(){
		self::loadConfig();
		return self::$data['ticketManagement']['method'];
	}


	/**
	 * Retourne les paramètres de configuration de la gestion des jetons d'authentification
	 * @return array
	 */
	public static function getTicketManagerParameters(){
		self::loadConfig();
		return self::$data['ticketManagement']['parameters'];
	}


	/**
	 * Retourne la clé privée utilisée pour coder les données internes au serveur
	 * @return string
	 */
	public static function getSecurityServerPrivateKey(){
		self::loadConfig();
		return self::$data['security']['serverPrivateKey'];
	}


	/**
	 * Retourne l'identifiant du serveur Aspic
	 * @return string
	 */
	public static function getSecuritySsoUID(){
		self::loadConfig();
		return self::$data['security']['ssoUID'];
	}


	/**
	 * Retourne si le SSL est activé
	 * @return boolean
	 */
	public static function getSecuritySslEnabled(){
		self::loadConfig();
		return (self::$data['security']['sslEnabled'] == 'true') ? true : false;
	}


	/**
	 * Retourne si la gestion des autorisations de connexion par application est activé
	 * @return boolean
	 */
	public static function getAppzCredentialCheckEnabled(){
		self::loadConfig();
		return (self::$data['querying']['appzCredentialCheckQuerying']['enabled'] == 'true') ? true : false;
	}


	/**
	 * Retourne la méthode utilisée pour la gestion des autorisations de connexion par application
	 * @return string
	 */
	public static function getAppzCredentialCheckMethod(){
		self::loadConfig();
		return self::$data['querying']['appzCredentialCheckQuerying']['method'];
	}


	/**
	 * Retourne les paramètres de configuration de la gestion des autorisations de connexion par application.
	 * @return array
	 */
	public static function getAppzCredentialCheckParameters(){
		self::loadConfig();
		return self::$data['querying']['appzCredentialCheckQuerying']['parameters'];
	}


	/**
	 * Retourne si la gestion des attributs par utilisateur est activé
	 * @return boolean
	 */
	public static function getUserDataQueryingEnabled(){
		self::loadConfig();
		return (self::$data['querying']['userDataQuerying']['enabled'] == 'true') ? true : false;
	}


	/**
	 * Retourne la méthode utilisée pour la gestion des attributs par utilisateur
	 * @return string
	 */
	public static function getUserDataQueryingMethod(){
		self::loadConfig();
		return self::$data['querying']['userDataQuerying']['method'];
	}


	/**
	 * Retourne les paramètres de configuration de la gestion des attributs par utilisateur
	 * @return array
	 */
	public static function getUserDataQueryingParameters(){
		self::loadConfig();
		return self::$data['querying']['userDataQuerying']['parameters'];
	}


	/**
	 * Retourne la durée maximum d'une session utilisateur (en minutes)
	 * @return int
	 */
	public static function getSecuritySessionMaxTime(){
		self::loadConfig();
		return intval(self::$data['security']['sessionMaxTime']);
	}


	/**
	 * Retourne si les logs sont activés
	 * @return boolean
	 */
	public static function getLogsEnabled(){
		self::loadConfig();
		return (self::$data['logs']['enabled'] == 'true') ? true : false;
	}


	/**
	 * Retourne le nom de fichier utilisé pour les logs
	 * @return string
	 */
	public static function getLogsFile(){
		self::loadConfig();
		return self::$data['logs']['logfile'];
	}


	/**
	 * Retourne si les logs liés aux vérifications de jetons d'authentification sont activés
	 * @return boolean
	 */
	public static function getLogsticketCheckEnabled(){
		self::loadConfig();
		return (self::$data['logs']['log_ticketcheck'] == 'true') ? true : false;
	}


	/**
	 * retourne si les logs liés aux erreurs PHP sont activés
	 * @return boolean
	 */
	public static function getLogsPhpErrorsEnabled(){
		self::loadConfig();
		return (self::$data['logs']['log_phperrors'] == 'true') ? true : false;
	}


	/**
	 * Retourne si les logs liés aux erreurs de connexion sont activés
	 * @return boolean
	 */
	public static function getLogsCnxFailEnabled(){
		self::loadConfig();
		return (self::$data['logs']['log_cnxfail'] == 'true') ? true : false;
	}


	/**
	 * Retourne si les logs liés aux succès de connexion sont activés
	 * @return boolean
	 */
	public static function getLogsCnxSuccessEnabled(){
		self::loadConfig();
		return (self::$data['logs']['log_cnxsuccess'] == 'true') ? true : false;
	}


	/**
	 * Retourne si les logs liés aux alertes de securité sont activés
	 * @return boolean
	 */
	public static function getLogsSecurityAlertEnabled(){
		self::loadConfig();
		return (self::$data['logs']['log_securityalert'] == 'true') ? true : false;
	}


	/**
	 * Retourne la clé de sécurité nécessaire à l'appel des taches CRON
	 * @return string
	 */
	public static function getSecurityCronKey(){
		self::loadConfig();
		return self::$data['security']['cronKey'];
	}


	/**
	 * Retourne l'IP qui doit obligatoirement être utilisée par le serveur pour l'appel de taches CRON
	 * @return string
	 */
	public static function getSecurityCronIp(){
		self::loadConfig();
		return self::$data['security']['cronIp'];
	}


	/**
	 * Retourne si l'option httpOnly peut être activée pour la gestion des cookies sécurisés
	 * @return boolean
	 */
	public static function getHttpOnlyCookiesEnabled(){
		if(PHP_VERSION_ID < 50200){
			return false;
		}else{
			return true;
		}
	}


	/**
	 * Retourne la version
	 * @return string
	 */
	public static function getVersion(){
		return self::$version;
	}


	/**
	 * Chargement du fichier de configuration
	 */
	private static function loadConfig(){
		if(!self::$data){
			self::$data = JsonLoader::loadPhpFile('../config/configuration.php');
		}
	}

}
