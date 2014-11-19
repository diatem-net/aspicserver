<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

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