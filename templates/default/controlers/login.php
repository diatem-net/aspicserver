<?php

use aspic\security\CredentialTicket;
use aspic\Config;
use aspic\security\ipblacklist\IpBlackListService;

class LoginControler{
    private $error;
    
    public function __construct() {
        $this->checkPOST();
    }
    
    public function getError(){
        return $this->error;
    }
    
    private function checkPOST(){
        if(isset($_POST['login']) && isset($_POST['password'])
        && $_POST['login'] != '' && $_POST['password'] != ''){
            $r = CredentialTicket::authentifiate($_POST['login'], $_POST['password']);
        
            if($r){
                if(Config::getIpBlackListEnabled()){
                    IpBlackListService::clear($_SERVER['REMOTE_ADDR']);
                }
                header('Location: '.Config::getServerUrl().'?sid='.$_GET['sid'].'&s='.urlencode($_GET['s']));
            }else{
                if(Config::getIpBlackListEnabled()){
                    IpBlackListService::addTry($_SERVER['REMOTE_ADDR']);
                }
                $this->error = __trad('login_error_noaccount');
            }
        }
    }
}