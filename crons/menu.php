<?php
namespace app\cron;
use app\emnus\MarkStatus;
use app\services\BookService;
use app\services\MarkService;

require_once dirname(__FILE__) . '/console.php';

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);

$stopTime = strtotime("+1 days");
do {
    $runStartTime = time();
    if ($runStartTime > $stopTime) {
        printMsg('exit');
        break;
    }
    $marks = MarkService::getInstance()->getListByStatus([MarkStatus::UPDATE,MarkStatus::ERROR],"status");
    if (empty($marks)) printMsg("无书签");
    foreach ($marks as $item){
        //获取目录
        $menu=file_get_contents($item["url"],false, stream_context_create($arrContextOptions));
        $encode = mb_detect_encoding($menu, array("ASCII","UTF-8","GBK","GB2312","BIG5"));
        if (in_array($encode,["GB2312","CP936"])) $encode = "GBK";
        $menu=iconv($encode,"utf-8//IGNORE", $menu);
        $menu=preg_replace("/\s+/", "", $menu);
        $menuRegular = json_decode($item["menu_regular"],true);
        $menu = [[],[$menu]];
        foreach ($menuRegular as $it){
            preg_match_all($it, $menu[1][0], $menu);
        }
        $menu = $menu[1]??[];
        $count = count($menu);

        //获取记录的最后章节
        $lastBook = BookService::getInstance()->getLastBook($item["id"]);
        $menuKey = array_flip($menu);
        $last = 0;
        if(isset($lastBook["path"])){
            if (!isset($menuKey[$lastBook["path"]])){
                printMsg("《".$item["name"]."》更新异常，".$lastBook["id"].".".$lastBook["path"]."未找到");
                MarkService::getInstance()->setStatus($item["id"],MarkStatus::ERROR);
                continue;
            }else{
                $last = $menuKey[$lastBook["path"]]+1;
            }
        }

        $newBook = [];
        for($i=$last;$i<$count;$i++){
            $newBook[] = [
                "mark_id"  => $item["id"],
                "path"  => $menu[$i],
                "url"   => $item["host"].$menu[$i],
                "title"   => "待获取章节",
                "content"   => "带获取"
            ];
        }
        $updateCount = empty($newBook)?0:BookService::getInstance()->create($newBook);
        MarkService::getInstance()->setStatus($item["id"],MarkStatus::UPDATE);
        printMsg("《".$item["name"]."》更新".$updateCount."章内容");
    }
    sleep(600);
} while (true);

