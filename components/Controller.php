<?php
namespace app\components;
use app\models\bssapi\AccountUser;
use yii\base\Exception;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class Controller extends \yii\rest\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    public function runAction($id, $params = [])
    {
        try{
            return parent::runAction($id, $params);
        }catch(Exception $e){
            $result = $e->getMessage();
            return ErrorCode::ErrorResult($result);
        }
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        $result = [
            "code"   => 0,
            "data"  => $result,
        ];
        return $result;
    }
}