<?php
namespace  app\services;
use app\components\Service;
use app\emnus\BookStatus;
use app\emnus\MarkStatus;
use app\models\BookModel;
use app\models\MarkModel;
use app\models\MenuModel;

/**
 * Created by PhpStorm.
 * User: gaoruishen
 * Date: 2019/1/13
 * Time: 11:48 AM
 */

class MarkService extends Service
{
    function get(){
        $ret = MarkModel::find()->select(["id","name"])->where(["in","status",[MarkStatus::UPDATE,MarkStatus::ERROR]])->asArray()->all();
        $where = [
            "and",
            ["in","mark_id",array_column($ret,"id")],
            ["in","status",[BookStatus::FINISH,BookStatus::READY]]
        ];
        $book = BookModel::find()
            ->select("mark_id,count(1) as count")
            ->where($where)
            ->groupBy("mark_id")
            ->asArray()
            ->all();
        if (!empty($book)) $book = array_column($book,null,"mark_id");
        foreach ($ret as &$item) $item["count"] = $book[$item["id"]]["count"]??0;
        return $ret;
    }
    function getListByStatus($status,$order=""){
        $ret = MarkModel::find()->where(["in","status",$status]);
        if(!empty($order))   $ret = $ret->orderBy($order);
        return $ret->asArray()->all();
    }

    function getListByIds($ids){
        $ret = MarkModel::find()->where(["in","id",$ids]);
        return $ret->asArray()->all();
    }

    function setStatus($id, $status){
        $ret = MarkModel::updateAll(["status"=>$status],["id"=>$id]);
        return $ret;
    }
}