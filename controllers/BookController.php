<?php
namespace app\controllers;

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

}