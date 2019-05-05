<?php
namespace app\controllers\api;

use app\components\Controller;
use app\components\Util;
use app\services\BookService;

/**
 * Class MenuController
 * @package app\controllers
 */
class BookController extends Controller
{
    /**
     * 智能图片获取
     * @return mixed
     */
    public function actionGet(){
        $param = Util::getRequestData();
        return BookService::getInstance()->get($param);
    }

    public function actionRefresh(){
        $param = Util::getRequestData();
        Util::checkEmpty($param,[
            "id",
        ]);
        return BookService::getInstance()->refresh($param["id"]);
    }

    public function actionVerify(){
        return BookService::getInstance()->verify();
    }

    public function actionClear(){
        $param = Util::getRequestData();
        Util::checkEmpty($param,[
            "book_id",
            "mark_id"
        ]);
        return BookService::getInstance()->clear($param["book_id"],$param["mark_id"]);
    }

}