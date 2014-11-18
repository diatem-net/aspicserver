<?php

function __trad($code){
    return aspic\publicappz\Lang::get($code);
}

function __version(){
    return aspic\Config::getVersion();
}