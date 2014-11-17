<?php

namespace aspic\security\authentification;

use aspic\utils\DbCnx;
use jin\query\Query;
use jin\query\QueryResult;
use aspic\Config;

class DatabaseMethod{
    public function authentifiateUserWithCredentials($username, $password){
	$r = DbCnx::connect();
	if(!$r){
	    throw new \Exception('Erreur de connexion BDD');
	}
	
	$queryParams = Config::getAuthentificationParameters();
	
	$q = new Query();
	$q->setRequest($queryParams['query']);
	$q->argument($username, Query::$SQL_STRING, '');
	
	$q->execute();
	$qr = $q->getQueryResults();
	
	if($qr->count() != 1){
	    return false;
	}
	
	$storedPassword = $qr->getValueAt($queryParams['passwordFieldName']);
	if($storedPassword != Hash(Config::getSecurityPasswordEcnryptMethod(), $password)){
	    return false;
	}
	
	return true;
    }
}