<?php

// autoload.php @generated by Composer

require_once __DIR__ . '/composer/autoload_real.php';

function p($x=["@222@"],$isNotExit = false){
    header('Content-type: application/json');
    echo json_encode($x);
    if(!$isNotExit) exit;
}
return ComposerAutoloaderInit7892e30bae6c156073b334478bfbabcd::getLoader();
