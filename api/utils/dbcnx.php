<?php

namespace aspic\utils;

use jin\db\DbConnexion;
use aspic\Config;

class DbCnx{
    private static $connected = false;
    
    public static function connect(){
	if(!self::$connected){
	    if(Config::getDbType() == 'postgresql'){
			self::$connected = DbConnexion::connectWithPostgreSql(Config::getDbHost(), Config::getDbUser(), Config::getDbPassword(), Config::getDbPort(), Config::getDbName());
	    }elseif(Config::getDbType() == 'mysql'){
	    	self::$connected = DbConnexion::connectWithMySql(Config::getDbHost(), Config::getDbUser(), Config::getDbPassword(), Config::getDbPort(), Config::getDbName());
	    }
	}
	
	return self::$connected;
    }
}