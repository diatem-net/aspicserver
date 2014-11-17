<?php

use aspic\security\CredentialTicket;
use aspic\Config;

class LoginControler{
    public function __construct() {
	$this->checkPOST();
    }
    
    private function checkPOST(){
	if(isset($_POST['login']) && isset($_POST['password'])){
	    $r = CredentialTicket::authentifiate($_POST['login'], $_POST['password']);
	    
	    if($r){
		header('Location: '.Config::getServerUrl().'?sid='.$_GET['sid'].'&s='.urlencode($_GET['s']));
	    }
	}
    }
}