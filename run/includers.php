<?php

include_once '../api/publicappz/publicappz.php';
include_once '../api/restappz/restappz.php';
include_once '../api/publicappz/view.php';
include_once '../api/publicappz/lang.php';
include_once '../api/config.php';
include_once '../api/context.php';
include_once '../api/utils/jsonloader.php';
include_once '../api/utils/securecookie.php';
include_once '../api/utils/dbcnx.php';
include_once '../api/service.php';
include_once '../api/security/credentialticket.php';
include_once '../api/security/authentification/authservice.php';
include_once '../api/security/authentification/databasemethod.php';
include_once '../api/security/ticketmanager/ticketservice.php';
include_once '../api/security/ticketmanager/ticketmanagerfilemethod.php';

use aspic\Config;
include_once Config::getJinLibraryLocation().'launcher.php';

