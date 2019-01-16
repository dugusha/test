<?php
namespace app\components;
use Yii;
use yii\db\Exception;

class Util
{
    /**
     * 二维数组按key分组
     */
    public static function array2KeyGroupArray($result, $key) {
        $list = array();
        foreach ($result as $item) {
            $list[$item[$key]][] = $item;
        }
        return $list;
    }
    /**
     * 获取请求参数
     */
    public static function getRequestData() {
        $ret = array();

        $post = Yii::$app->request->post();
        if(!empty($post)) {
            $ret = array_merge($ret, $post);
        }

        $get = Yii::$app->request->get();
        if(!empty($get)) {
            $ret = array_merge($ret, $get);
        }

        $str = file_get_contents("php://input");
        if(!empty($str)) {
            $data =  json_decode($str, true);
            if(!empty($data)) {
                $ret = array_merge($ret, $data);
            }
        }
        return $ret;

    }
    /**
     * check null
     * @return bool
     */
    public static function checkEmpty($params,$keys){
        if(!is_array($keys))
            $keys = explode(',', $keys);
        foreach($keys as $k){
            if (!isset($params[$k])){
                throw new Exception($k.' 为空');
            }
        }
        return true;
    }

    /**
     * 二维数组排序
     */
    public static function myArraySort(&$array, $key, $asc=true) {
        uasort($array, function($v1, $v2) use ($asc, $key) {
            $result = $v1[$key] != $v2[$key] ? $v1[$key] > $v2[$key] ? 1 : -1 : 0;
            return $asc ? $result : -$result;
        });
    }
}