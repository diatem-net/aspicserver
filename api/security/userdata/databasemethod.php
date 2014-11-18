<?php

namespace aspic\security\userdata;

use aspic\utils\DbCnx;
use jin\query\Query;
use jin\query\QueryResult;
use aspic\Config;

class DatabaseMethod{
    public function getUserData($username){
	$r = DbCnx::connect();
	if(!$r){
	    throw new \Exception('Erreur de connexion BDD');
	}
	
	$queryParams = Config::getUserDataQueryingParameters();
	
	$q = new Query();
	$q->setRequest($queryParams['query']);
	$q->argument($username, Query::$SQL_STRING, '');
	
	$q->execute();
	$qr = $q->getQueryResults();
	
	if($qr->count() != 1){
	    return false;
	}
	
	$retour = array();
	foreach($queryParams['fields'] as $f => $v){
	    $retour[$f] = $qr->getValueAt($v);
	}
	
	return $retour;
    }
 
}
