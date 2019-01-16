<?php

require(__DIR__ . '/../config/define.php');
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = \yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/params.php')
);
$app = new yii\web\Application($config);
$app->run();