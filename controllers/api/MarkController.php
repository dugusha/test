<?php
namespace app\controllers\api;

use app\adapter\AdAdapter;
use app\components\Controller;
use app\components\Util;
use app\services\BookService;
use app\services\MarkService;
use app\services\MenuService;
use yii\base\Exception;

/**
 * Class MenuController
 * @package app\controllers
 */
class MarkController extends Controller
{
    /**
     * 智能图片获取
     * @return mixed
     */
    public function actionGet(){
        return MarkService::getInstance()->get();
    }

}