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
    $where = ["status"=>BookStatus::READY];
    if (!empty($argv[1])) $where["start_id"] = $argv[1];
    $books = BookService::getInstance()->getList($where,100);
    if (empty($books)) printMsg("没有需要更新的");
    $marks = array_values(array_unique(array_column($books,"mark_id")));
    $marks = MarkService::getInstance()->getListByIds($marks);
    $marks = array_column($marks,null,"id");
    foreach ($books as $item){
        $mark = $marks[$item->mark_id];
        $ret=BookService::getInstance()->updateCon($mark,$item);
        if ($ret) printMsg($item->id.".".$item->title."\t更新成功");
        else printMsg($item->id.".".$item->title."\t更新失败！！！！！！！");
    }
    sleep(60);
} while (true);

