<?php

include '../api/config.php';
include '../api/utils/jsonloader.php';
include '../api/security/ticketmanager/ticketservice.php';
include '../api/security/ticketmanager/ticketmanagerfilemethod.php';
use aspic\Config;
use aspic\security\ticketmanager\TicketService;


if(Config::getSecurityCronIp() != $_SERVER['REMOTE_ADDR']){
    exit();
}
if(!isset($_GET['k']) || $_GET['k'] != Config::getSecurityCronKey()){
    exit();
}

TicketService::clear();