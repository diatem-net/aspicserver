<?php

namespace aspic;

use aspic\Config;

class Logs{
    const CNXFAIL = 1;
    const CNXSUCCESS = 2;
    const SECURITYALERT = 3;
    const PHPERROR = 4;
    const TICKETCHECK = 5;
    
    private static $initialized;
    private static $enabled;
    private static $cnxfail;
    private static $cnxsuccess;
    private static $phperror;
    private static $securityalert;
    private static $ticketcheck;
    
    private static $log_file, $fp;
    
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
	
	self::$log_file = '/var/www/html/aspicserver/logs.txt';
    }
    
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
    
    private static function write($message, $type){
	if (!is_resource(self::$fp)) {
            self::open();
        }
	
	$time = @date('[d/M/Y:H:i:s]');
	
        // write current time, script name and message to the log file
        fwrite(self::$fp, "$time ($type) - $message" . PHP_EOL);
    }
    
    private static function open(){
        self::$fp = fopen(self::$log_file, 'a') or exit("Can't open $lfile!");
    }
    
    private static function close() {
        fclose(self::$fp);
    }
}
