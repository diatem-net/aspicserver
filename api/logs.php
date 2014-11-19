<?php

namespace aspic;

use aspic\Config;
use jin\lang\StringTools;


/**
 * Classe de gestion des logs
 * @author Loïc Gerard <lgerard@diatem.net>
 */
class Logs{
	/**
	 * type de log : erreur de connexion
	 */
    const CNXFAIL = 1;


    /**
     * type de log : succès de connexion
     */
    const CNXSUCCESS = 2;


    /**
     * type de log - alertes de sécurité
     */
    const SECURITYALERT = 3;


    /**
     * type de log - erreurs PHP
     */
    const PHPERROR = 4;


    /**
     * type de log - check de jetons de connexion
     */
    const TICKETCHECK = 5;
    

    /**
     * Logs initialisés
     * @var boolean
     */
    private static $initialized;


    /**
     * Logs activés
     * @var boolean
     */
    private static $enabled;


    /**
     * Logs des échecs de connexion activés
     * @var boolean
     */
    private static $cnxfail;


    /**
     * Logs des succès de connexion activés
     * @var boolean
     */
    private static $cnxsuccess;


    /**
     * Logs des erreurs PHP activés
     * @var boolean
     */
    private static $phperror;


    /**
     * Logs des alertes de sécurité activés
     * @var boolean
     */
    private static $securityalert;


    /**
     * Logs des checks de jeton activés
     * @var boolean
     */
    private static $ticketcheck;
    

    /**
     * Chemin du fichier d'enregistrement des logs
     * @var string
     */
    private static $log_file;


    /**
     * Pointeur du fichier
     * @var mixed
     */
    private static $fp;
    

    /**
     * Initialisation des logs
     * @return [type] [description]
     */
    private static function init(){
    	if(self::$initialized){
    		return;
    	}
    	if(Config::getLogsEnabled()){
    		self::$enabled = true;
    	}else{
    		return;
    	}
    	if(Config::getLogsCnxFailEnabled()){
    		self::$cnxfail = true;
    	}
    	if(Config::getLogsCnxSuccessEnabled()){
    		self::$cnxsuccess = true;
    	}
    	if(Config::getLogsPhpErrorsEnabled()){
    		self::$phperror = true;
    	}
    	if(Config::getLogsSecurityAlertEnabled()){
    		self::$securityalert = true;
    	}
    	if(Config::getLogsticketCheckEnabled()){
    		self::$ticketcheck = true;
    	}

        self::$log_file = StringTools::replaceFirst($_SERVER['SCRIPT_FILENAME'], 'run/igniter.php', '').Config::getLogsFile();
    }
    

    /**
     * Enregistre un log
     * @param  string $tolog   Texte à enregistrer
     * @param  int $logtype Type de log. Ex. Logs::CNXFAIL
     */
    public static function log($tolog, $logtype){
    	self::init();
    	if(!self::$enabled){
    		return;
    	}
    	if ($logtype == self::CNXFAIL && self::$cnxfail){
    		self::write($tolog, 'CNXFAIL');
    	}else if($logtype == self::CNXSUCCESS && self::$cnxsuccess){
    		self::write($tolog, 'CNXSUCCESS');
    	}else if($logtype == self::PHPERROR && self::$phperror){
    		self::write($tolog, 'PHPERROR');
    	}else if($logtype == self::SECURITYALERT && self::$securityalert){
    		self::write($tolog, 'SECURITYALERT');
    	}else if($logtype == self::TICKETCHECK && self::$ticketcheck){
    		self::write($tolog, 'TICKETCHECK');
    	}

    }
    

    /**
     * Ecrit dans le fichier
     * @param  string $message Message à enregistrer
     * @param  int $type    Type de log.
     */
    private static function write($message, $type){
    	if (!is_resource(self::$fp)) {
    		self::open();
    	}

    	$time = @date('[d/M/Y:H:i:s]');

        // write current time, script name and message to the log file
    	fwrite(self::$fp, "$time ($type) - $message" . PHP_EOL);
    }
    

    /**
     * Ouvre le fichier en écriture
     */
    private static function open(){
    	self::$fp = fopen(self::$log_file, 'a') or exit("Can't open $lfile!");
    }
    

    /**
     * Ferme le fichier
     */
    private static function close() {
    	fclose(self::$fp);
    }
}
