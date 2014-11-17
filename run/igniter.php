<?php

include_once 'includers.php';
include_once 'shortcuts.php';
use aspic\publicappz\PublicAppz;
use aspic\restappz\RestAppz;

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sid'])){
    //Check jeton
    new RestAppz();
}else{
    //Authentifiate
    new PublicAppz();
}
