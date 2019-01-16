<?php
/**
 * Created by PhpStorm.
 * User: gaoruishen
 * Date: 2019/1/14
 * Time: 2:58 PM
 */

namespace app\emnus;


class MarkStatus
{
    const UPDATE    = 1;
    const KEPT      = 2;
    const ERROR     = 3;


    public static $enumNames = [
        self::UPDATE    => '更新',
        self::KEPT      => '不更新',
        self::ERROR     => '异常'
    ];

}