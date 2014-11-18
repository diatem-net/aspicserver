<?php

namespace aspic\security\appzcredentialcheck;

use aspic\utils\DbCnx;
use jin\query\Query;
use jin\query\QueryResult;
use aspic\Config;

class DatabaseMethod{
    public function getCredentials($username){
	echo 'GET CRED';
	
	$r = DbCnx::connect();
	if(!$r){
	    throw new \Exception('Erreur de connexion BDD');
	}
	
	$queryParams = Config::getAppzCredentialCheckParameters();
	
	$q = new Query();
	$q->setRequest($queryParams['query']);
	$q->argument($username, Query::$SQL_STRING, '');
	
	$q->execute();
	$qr = $q->getQueryResults();
	

	if($qr->count() == 0){
	    return false;
	}
	
	$serviceIdField = $queryParams['serviceIdField'];
	$groupNameField = $queryParams['groupNameField'];
	
	$retour = array();
	foreach($qr as $r){
	    if(!isset($retour[$r[$serviceIdField]])){
		$retour[$r[$serviceIdField]] = array();
	    }
	    
	    $retour[$r[$serviceIdField]][] = $r[$groupNameField];
	}
	
	return $retour;
    }
 
}
