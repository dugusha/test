<?php
namespace app\controllers;

use yii\web\Response;
use yii\web\Controller;
use Yii;

class DefaultController extends Controller
{
    public function init()
    {
        parent::init();
        Yii::$app->response->format =  Response::FORMAT_JSON;
    }

    /**
     * hello
     * @http-method get
     * @request-param void
     * @return array
     */
    public function actionIndex()
    {
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        ob_start();
        include (Yii::$app->basePath.'/web/test/index.html');
        ob_end_flush();
        exit;
    }

    /**
     * @return array
     */
    public function actionError()
    {
        $res = [
            'ret' => 0,
            'msg'=>Yii::$app->errorHandler->exception->getMessage(),
        ];
        return $res;
    }
}