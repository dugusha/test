<?php
/**
 * Created by PhpStorm.
 * User: gaoruishen
 * Date: 2018/1/24
 * Time: 下午6:19
 */

namespace app\components;


class ErrorCode
{
    const BASE_ERROR_UNPARAM = 1001;



    static $errorList = [
        self::BASE_ERROR_UNPARAM        => "参数缺失",
    ];


    /**
     * @return array
     */
    public static function ErrorResult($errorInfo)
    {
        $ret = [
            "code"  => -1,
            "msg"   => $errorInfo
        ];
        if (is_numeric($errorInfo)){
            if($errorInfo!=0) $ret["code"] = $errorInfo;
            $ret["msg"] = self::$errorList[$errorInfo]??"未知错误";
        }
        return $ret;
    }

}