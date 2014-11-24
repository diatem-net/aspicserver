<?php

namespace aspic\security\accesslog;

use aspic\utils\DbCnx;
use jin\query\Query;
use jin\query\QueryResult;
use aspic\Config;

class AccessLogDatabaseMethod{
    public function logAccess($userId, $serviceId){
        $r = DbCnx::connect();
        if(!$r){
            throw new \Exception('Erreur de connexion BDD');
        }

        $params = Config::getAccessLogParameters();
        $q = new Query();
        $q->setRequest($params['query']);
        $q->argument($userId, Query::$SQL_STRING, ':userId');
        $q->argument($serviceId, Query::$SQL_STRING, ':serviceId');

        $q->execute();
    }
}