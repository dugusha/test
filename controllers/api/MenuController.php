<?php
namespace app\controllers\api;

use app\adapter\AdAdapter;
use app\components\Controller;
use app\components\Util;
use app\services\BookService;
use app\services\MenuService;
use yii\base\Exception;

/**
 * Class MenuController
 * @package app\controllers
 */
class MenuController extends Controller
{
    /**
     * 智能图片获取
     * @return mixed
     */
    public function actionGet(){
        $param = Util::getRequestData();
        Util::checkEmpty($param,[
            "mark_id",
        ]);
        return BookService::getInstance()->getMenu($param["mark_id"]);
    }

}