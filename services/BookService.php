<?php
namespace  app\services;
use app\components\Service;
use app\emnus\BookStatus;
use app\models\BookModel;
use app\models\MarkModel;
use app\models\MenuModel;
use yii\base\ErrorException;

/**
 * Created by PhpStorm.
 * User: gaoruishen
 * Date: 2019/1/13
 * Time: 11:48 AM
 */

class BookService extends Service
{
    function getMenu($markId){
        $mark = MarkModel::find()->where(["id"=>$markId])->asArray()->one();
        $where = [
            "mark_id"=>$markId
        ];
        $book = BookModel::find()->select(["id","title","status"])->where($where)->orderBy("id desc")->asArray()->all();
        return [
            "id"    => $mark["id"],
            "name"  => $mark["name"],
            "list"  => $book
        ];
    }
    function get($param){
        $where = [];
        if (!empty($param["id"])) $where = ["id"=>$param["id"]];
        $ret = BookModel::find()->where($where)->orderBy("id")->limit(1)->one();
        $ret->status = BookStatus::READ;
        $ret->save();
        if ($param["getNext"]!="false"){
            $where = [
                "and",
                [">","id",$param["id"]],
                ["=","mark_id",$ret->mark_id],
            ];
            $ret = BookModel::find()->where($where)->orderBy("id")->limit(1)->asArray()->one();
        }
        return $ret;
    }

    function getList($param,$limit=0){
        $where = ["and"];
        if (!empty($param["status"])) $where[] = ["=","status",$param["status"]];
        if (!empty($param["start_id"])) $where[] = [">","id",$param["start_id"]];
        $ret = BookModel::find()->where($where);
        if (!empty($limit)) $ret->limit($limit);
        return $ret->all();
    }

    function getLastBook($mark_id){
        $ret = BookModel::find()->where(["mark_id"=>$mark_id])->orderBy("id desc")->asArray()->one();
        return $ret;
    }

    function create($data){
        $title = array_keys(current($data));
        foreach ($data as $key => $val){
            $data[$key] = array_values($val);
        }
        return BookModel::getDb()->createCommand()->batchInsert(BookModel::tableName(),$title,$data)->execute();
    }

    function updateCon($mark,$book){
        //获取页面
        try{
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );
            $content=file_get_contents($book->url,false, stream_context_create($arrContextOptions));
        }catch(Exception $e){
            return false;
        }
        try{
            $encode = mb_detect_encoding($content, array("ASCII","UTF-8","GBK","GB2312","BIG5"));
            if (in_array($encode,["GB2312","CP936"])) $encode = "GBK";
            $content=iconv($encode,"utf-8//IGNORE", $content);
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
        }catch(ErrorException $e){
            $title      = "未取到";
            $content    = "未取到,<a href=\"".$book->url."\">跳转查看</a>";
        }
        try{
            $book->title	= $title;
            $book->content	= $content;
            $book->status	= BookStatus::FINISH;
            $ret = $book->save();
        }catch(Exception $e){
            $ret = false;
        }
        return $ret;
    }

    function refresh($id){
        $book = BookModel::find()->where(["id"=>$id])->one();
        $mark = MarkModel::find()->where(["id"=>$book->mark_id])->asArray()->one();
        BookService::getInstance()->updateCon($mark,$book);
        return $book;
    }

    function verify(){
        $where = [
            "and",
            ["=","status",BookStatus::FINISH],
            ["like","content","手打中"]
        ];
        return BookModel::updateAll(["status"=>BookStatus::READY],$where);
    }

    function clear($bookId,$markId){
        $where = [
            "and",
            ["=","mark_id",$markId],
            ["<","id",$bookId]
        ];
        return BookModel::deleteAll($where);
    }
}