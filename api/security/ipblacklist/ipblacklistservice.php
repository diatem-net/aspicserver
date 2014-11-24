<?php

namespace aspic\security\ipblacklist;

use aspic\Config;
use aspic\security\ipblacklist\IpBlackListFileMethod;

class IpBlackListService{
    private static $service;

    public static function isBlacklisted($ip){
        self::init();
        return self::$service->isBlacklisted($ip);
    }

    public static function addTry($ip){
        self::init();
        self::$service->addTry($ip);
    }

    public static function clear($ip){
        self::init();
        self::$service->clear($ip);
    }

    private static function init(){
        if(self::$service){
            return;
        }
        
        if(Config::getIpBlackListMethod() == 'file'){
            self::$service = new IpBlackListFileMethod();
        }else{
            throw new \Exception('Methode non supportée pour la gestion de la blackList IP');
        }
    }
}