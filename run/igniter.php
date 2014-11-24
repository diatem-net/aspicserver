<?php

include '../config/errors.php';

if(ERROR_REPORT){
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}else{
    ini_set('display_errors', 'Off');
}


register_shutdown_function(function() {
    $error = error_get_last();
    if($error !== null){
        echo '<h1>Erreur Fatale !</h1><b>erreur grave à l\'initialisation du serveur. La configuration est certainement erronnée.</b><br><hr>';
        var_dump($error);
    }
});

//Initialize launcher for Aspic classes
include '../api/aspiccore.php';
spl_autoload_register(array('aspic\AspicCore', 'autoload'));

//Initialize jin library
use aspic\Config;
include_once Config::getJinLibraryLocation().'launcher.php';

//Initialize shortcuts
include_once 'shortcuts.php';

set_error_handler(array( 'aspic\publicappz\PublicAppz', 'errorHandler_standard'));
set_exception_handler(array('aspic\publicappz\PublicAppz', 'errorHandler_exceptions'));
register_shutdown_function(array('aspic\publicappz\PublicAppz', 'errorHandler_fatal'));


if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sid'])){
    //Check jeton
    new aspic\restappz\RestAppz();
}else{
    //Authentifiate
    new aspic\publicappz\PublicAppz();
}