<?php

namespace aspic;

class Context{
    private static $data = array();
    
    public static function put($key, $data){
	self::$data[$key] = $data;
    }
    
    public static function get($key){
	return self::$data[$key];
    }
}
