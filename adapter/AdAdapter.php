<?php
namespace app\adapter;
use app\components\ApiAdapter;
use yii\db\Exception;

class AdAdapter extends ApiAdapter
{
    protected function getApiServer()
    {
        return 'http://ada.test.yunshanmeicai.com/';
    }

    public function getFrame($key = "") {
        $param = [ "key" => $key];
        $result = $this->postJson('/api/smart-pic/get-frame', $param);
        if($result['ret']!=1){
            throw new Exception(__CLASS__ . ":" . $result['error']);
        }
        return $result['data'];
    }

    /**
     * 智能图片保存
     */
    public function smartPicSave($param)
    {
        $result = $this->postJson('/api/smart-pic/save', $param);
        if($result['ret'] == 1) {
            return $result['data'];
        } else {
            throw new Exception($result['error']['msg']);
        }
    }

    /**
     * 智能图片获取
     */
    public function smartPicGetById($param)
    {
        $result = $this->postJson('/api/smart-pic/get-by-id', $param);
        if($result['ret'] == 1) {
            return $result['data'];
        } else {
            throw new Exception($result['error']['msg']);
        }
    }
}
