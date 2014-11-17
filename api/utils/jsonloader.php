<?php

namespace aspic\utils;

class JsonLoader{
    public static function loadFile($file) {
	$string = file_get_contents($file);
	if(!$string){
	    throw new \Exception('Lecture fichier '.$file.' impossible.');
	}
	$data = json_decode($string, true);
	if(!$data){
	    throw new \Exception('Erreur de traitement JSON du fichier '.$file);
	}
	
	return $data;
    }
   
}
