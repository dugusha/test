<?php
namespace  app\services;
use app\components\Service;
use app\emnus\BookStatus;
use app\models\BookModel;
use app\models\MenuModel;

/**
 * Created by PhpStorm.
 * User: gaoruishen
 * Date: 2019/1/13
 * Time: 11:48 AM
 */

class BookService extends Service
{
    function getMenu($markId){
        $where = [
            "mark_id"=>$markId
        ];
        $ret = BookModel::find()->select(["id","title","status"])->where($where)->orderBy("id desc")->asArray()->all();
        return $ret;
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
        return $ret->asArray()->all();
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

    function updateCon($id,$title,$content){
        $ret = BookModel::updateAll([
            "title"     => $title,
            "content"   => $content,
            "status"    => BookStatus::FINISH
        ],["id"=>$id]);
        return $ret;
    }
}