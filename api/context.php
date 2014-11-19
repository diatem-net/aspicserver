<?php

namespace aspic;

/**
 * Classe permettant de gérer les données de contexte
 * @author Loïc Gerard <lgerard@diatem.net>
 */
class Context{
	/**
	 * Données stockées
	 * @var array
	 */
    private static $data = array();
    

    /**
     * Enregistre une donnée
     * @param  string $key  Clé
     * @param  mixed $data Donnée
     */
    public static function put($key, $data){
		self::$data[$key] = $data;
    }
    

    /**
     * Retourne la valeur d'une clé enregistrée
     * @param  string $key Clé
     * @return mixed
     */
    public static function get($key){
		return self::$data[$key];
    }
}
