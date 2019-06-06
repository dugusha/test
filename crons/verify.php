<?php
namespace app\cron;
use app\emnus\BookStatus;
use app\emnus\MarkStatus;
use app\services\BookService;
use app\services\MarkService;
use yii\base\Exception;

require_once dirname(__FILE__) . '/console.php';



$stopTime = strtotime("+1 days");
do {
//    echo md5(3439507 . "eXVuc2hhbiolNUUlMjElN0UyMHJlbGVhc2UxOCUyNkAlMjglMjltYWxs");exit;
    $runStartTime = time();
    if ($runStartTime > $stopTime) {
        printMsg('exit');
        break;
    }
    printMsg(BookService::getInstance()->verify());
    sleep(300);
} while (true);

