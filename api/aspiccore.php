<?php

namespace aspic;

/**
 * Tâches de bas niveau de l'application Aspic
 * @author Loïc Gerard
 */
class AspicCore{
	/**
	 * Fonction de chargement automatique des classes
	 * @param  string $className Chemin complet de la classe requise
	 */
    public static function autoload($className) {
		$tab = explode('\\', $className);
		$path = strtolower(implode(DIRECTORY_SEPARATOR, $tab)) . '.php';
		$path = str_replace('aspic/', 'api/', $path);
		$path = str_replace('api/aspiccore.php', '', __FILE__) . $path;
		
		if(is_file($path)){
		    require($path);
		}
    }
}

