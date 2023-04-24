<?php

function autoload($class){
    $arquivo = __DIR__.'/class/'.$class.'.php';
    if(is_file($arquivo)){
        require_once($arquivo);
    }
}

spl_autoload_register('autoload');