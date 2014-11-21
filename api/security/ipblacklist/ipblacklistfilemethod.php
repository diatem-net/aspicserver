<?php

namespace aspic\security\ipblacklist;

use aspic\Config;
use jin\filesystem\File;
use jin\lang\ListTools;
use aspic\Logs;

class IpBlackListFileMethod{
    private $dataFolder;

    public function __construct(){
        $p = Config::getIpBlackListParameters();
        $this->dataFolder = '../'.$p['storage'];
    }

    public function isBlacklisted($ip){
        $fp = $this->getFilePath($ip);
        if(file_exists($fp)){
            $fc = $this->getFileContent($fp);
            $try = intval(ListTools::ListGetAt($fc, 1, '|'));
            $time = intval(ListTools::ListGetAt($fc, 0, '|'));
            if($try > Config::getIpBlackListMaxAttempts()){
                if($time > time()){
                    return true;
                }else{
                    $this->clear($ip);
                    return false;
                }
            }
        }else{
            return false;
        }
    }

    public function addTry($ip){
        $fp = $this->getFilePath($ip);
        $try = 0;
        if(file_exists($fp)){
            $fc = $this->getFileContent($fp);
            $try = intval(ListTools::ListGetAt($fc, 1, '|'));
            $time = intval(ListTools::ListGetAt($fc, 0, '|'));

            if($time < time()){
                $this->clear($ip);
                $try = 0;
            }
            if($try > Config::getIpBlackListMaxAttempts()){
                Logs::log('Blacklist IP '.$ip, Logs::SECURITYALERT);
            }
        }else{
            $try = 0;
        }

        $try++;
        $this->setFileContent($fp, time()+(Config::getIpBlackListTime()*60).'|'.$try);
    }

    public function clear($ip){
        $f = new File($this->getFilePath($ip), false);
        $f->delete();
    }

    private function getFileContent($filePath){
        $f = new File($filePath, false);
        return $f->getContent();
    }

    private function setFileContent($filePath, $content){
        $f = new File($filePath, true);
        $f->write($content, false);
    }

    private function getFilePath($ip){
        return $this->dataFolder.md5($ip);
    }
}