<?php
namespace app\components;
use yii;
use yii\base\Component;

abstract class ApiAdapter extends Component {
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


    private $ch;

    //超时默认时间
    protected $timeout = 3;
    protected $con_timeout = 1;

    protected $opt = [];

    function __construct($config = [])
    {
        parent::__construct($config);
        $this->init();
    }

    function __destruct()
    {
        curl_close($this->ch);
    }

    public function init()
    {
        empty($this->ch) ? $this->ch = curl_init() : curl_reset($this->ch);

        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->con_timeout);
        foreach($this->opt as $type=>$value) {
            curl_setopt($this->ch,$type,$value);
        }
    }

    public function setTimeout($timeout)
    {
        if (is_numeric($timeout) && $timeout >= 0) {
            $this->timeout = intval($timeout);
        }
    }

    public function setConTimeout($timeout)
    {
        if (is_numeric($timeout) && $timeout >= 0) {
            $this->con_timeout = intval($timeout);
        }
    }

    /**
     * 配置参数
     * @param $type
     * @param $value
     * @return bool
     */
    public  function setOpt($type,$value) {
        $this->opt[$type] = $value;
        return curl_setopt($this->ch,$type,$value);
    }

    public function checkResult($result){
        if(empty($result)){
            throw new \ErrorException('接口返回空值');
        }

        if($result['ret'] != 1){
            $msg = $result['data']['msg'];
            throw new \ErrorException('接口'.$msg);
        }
    }

    protected abstract function getApiServer();


    /**
     * GET
     * @params $api_uri 目标URL
     * @params $data 参数(key=>value)
     * @params $decode json decode
     * @return string
     */
    protected function get($api_uri, $data=array(), $decode=true) {
        $url = $this->getApiServer() . $api_uri;

        $result = self::request('GET',$url,$data,array());

        return $decode ? json_decode($result, true) : $result;

    }

    /**
     * post
     * @params $api_uri 目标URL
     * @params $data 参数(key=>value)
     * @params $decode json decode
     * @return string
     */
    protected function post($api_uri, $data = array(), $decode=true){
        $url = $this->getApiServer() . $api_uri;

        $result =$this->request("POST", $url, $data, array());

        return $decode ? json_decode($result, true) : $result;
    }

    /**
     * postjson
     * @params $api_uri 目标URL
     * @params $data 参数(key=>value)
     * @params $decode json decode
     * @return string
     */
    protected function postJson($api_uri, $data = array(), $decode=true){
        $headers = array(
            "Content-type: application/json;charset='utf-8'",
            "Cache-Control: no-cache",
            "Pragma: no-cache"
        );

        $url = $this->getApiServer() . $api_uri;

        $result = $this->request("POST", $url, json_encode($data), $headers);

        return $decode ? json_decode($result, true) : $result;
    }

    protected function request($method, $url, $parameters, $headers,$retryCount = 0){
        $this->init();
        $paramStr = is_array($parameters) ? http_build_query($parameters) : $parameters;
        $headers = array_merge(["Accept: application/json"],$headers);
        curl_setopt($this->ch,CURLOPT_HTTPHEADER,$headers);
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($this->ch, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        // post
        if ($method=='POST') {
            curl_setopt($this->ch,CURLOPT_POST,true);
            curl_setopt($this->ch,CURLOPT_POSTFIELDS,$paramStr);
        } else {
            $url .= '?' . $paramStr;
        }
        curl_setopt($this->ch,CURLOPT_URL,$url);
        curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,true);
        $result = curl_exec($this->ch);

        if(!empty(curl_errno($this->ch))) {
            $err_msg = curl_error($this->ch);
            $err_code = curl_errno($this->ch);
            if($retryCount <=2 && $err_code == 56) {
                return $this->request($method,$url,$parameters,$headers,$retryCount +1);
            }
            \Yii::error(['type'=>'curlError','msg'=>$err_msg,'code'=>$err_code],__METHOD__);
            return <<<ERR
{"ret":0,"error":{"msg":"$err_msg", "code":"$err_code"}};
ERR;
        }
        return $result;
    }

    protected  function getErrorMsg($data){
        $data['error'] = isset($data['error']['msg'])?$data['error']['msg']:$data['error'];
        return $data;
    }


    protected function formatApiResult($result){
        if ($result == null){
            return array();
        }
        if ($result['ret']){
            return $result['data'];
        }
        return $this->getErrorMsg($result);
    }
}