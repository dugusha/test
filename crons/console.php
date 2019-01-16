<?php
date_default_timezone_set('Asia/Shanghai');
require(__DIR__.'/../config/define.php');
$config = require_once(__DIR__.'/../config/main.php');
// register Composer autoloader
require(__DIR__ . '/../vendor/autoload.php');

// include Yii class file
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

// load application configuration
Yii::setAlias('@op',dirname(__DIR__));

//输出信息
function printMsg($msg) {
    echo date("Y-m-d H:i:s") . ":" . $msg . "\n";
}

//输出信息
function p($msg) {
    print_r($msg);exit;
}
// 创建、配置、运行一个应用
$app = new yii\web\Application($config);

