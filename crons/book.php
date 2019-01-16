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
        $mark = $marks[$item["mark_id"]];
        //获取页面
        try{
            $content=file_get_contents($item["url"]);
        }catch(Exception $e){
            printMsg(json_encode($item)."\t更新失败！！！！！！！");
            continue;
        }
        $encode = mb_detect_encoding($content, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
        $content=iconv($encode,"utf-8", $content);
        $content=preg_replace("/\s+/", "", $content);

        //获取标题
        $titleRegular = json_decode($mark["title_regular"],true);
        $title = [[],[$content]];
        foreach ($titleRegular as $it){
            preg_match_all($it, $title[1][0], $title);
        }
        $title = $title[1][0]??"未取到";

        //获取内容
        $contentRegular = json_decode($mark["content_regular"],true);
        $content = [[],[$content]];
        foreach ($contentRegular as $it){
            preg_match_all($it, $content[1][0], $content);
        }
        $content = $content[1][0]??"";
        $contentReplace = json_decode($mark["content_replace"],true);
        foreach ($contentReplace as $k => $v){
            $content = str_replace($k,$v,$content);
        }
        try{
            $ret = BookService::getInstance()->updateCon($item["id"],$title,$content);
        }catch(Exception $e){
            $ret = 0;
        }
        if ($ret==1) printMsg($item["id"].".".$title."\t更新成功");
        else printMsg($item["id"].".".$title."\t更新失败！！！！！！！");
    }
    sleep(5);
} while (true);

