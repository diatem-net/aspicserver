<?php

namespace aspic\security\accesslog;

use aspic\Config;
use aspic\security\accesslog\AccessLogDatabaseMethod;

class AccessLogService{
    private static $service;


    public static function logAccess($userId, $serviceId){
        self::init();

        self::$service->logAccess($userId, $serviceId);
    }

    private static function init(){
        if(self::$service){
            return;
        }
        
        if(Config::getAccessLogMethod() == 'database'){
            self::$service = new AccessLogDatabaseMethod();
        }else{
            throw new \Exception('Methode non supportée pour la gestion des logs d\'accès');
        }
    }
}