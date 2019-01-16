<?php
/**
 * Created by PhpStorm.
 * User: gaoruishen
 * Date: 2019/1/14
 * Time: 2:58 PM
 */

namespace app\emnus;


class BookStatus
{
    const READY     = 1;
    const FINISH    = 2;
    const READ      = 3;


    public static $enumNames = [
        self::READY     => '待更新',
        self::FINISH    => '已更新',
        self::READ      => '已读',
    ];

}