<?php
/**
 * Created by PhpStorm.
 * User: gaoruishen
 * Date: 2019/1/13
 * Time: 11:51 AM
 */

namespace app\components;
use \Yii;
use yii\base\Component;


class Service extends Component
{
    /**
     * 单例
     * @return static
     */
    public static function  getInstance() {
        $obj = Yii::createObject(static::className());
        if(!Yii::$container->hasSingleton(static::className())) {
            Yii::$container->setSingleton(static::className(),$obj);
        }
        return $obj;
    }
}