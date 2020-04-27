<?php
/**
 * Created by PhpStorm.
 * User: srq
 * Date: 2020/4/27
 * Time: 14:18
 */

namespace App\WebSocket;

use App\Common\Constants;
use App\Common\ErrorCode;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Socket\AbstractInterface\Controller;

class WebSocketController extends Controller {

    //返回格式['errorCode'=>0,'errorMsg'=>'发送成功','msg'=>'你好!','msgType'=>1wenben,'msgId'=>1,;requestId=>1];


    /**
     * 成功情况返回
     * @param array $data
     */
    protected function successSetMessage($data = []) {
        $responseData['errorCode'] = ErrorCode::ERRORCODE_NORMAL;
        $responseData['errorMsg'] = 'success';
        if (is_array($data)) {
            $responseData = array_merge($responseData, $data);
        }

        $this->response()->setMessage(json_encode($responseData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 错误情况返回
     * @param $errorCode
     * @param array $data data['errorMsg']可以覆盖默认的
     */
    protected function failSetMessage($errorCode, $data = []) {
        $responseData['errorCode'] = $errorCode;
        $responseData['errorMsg'] = ErrorCode::getErrorMsgByKey($errorCode);
        if (is_array($data)) {
            if (isset($data['errorMsg']) && empty($data['errorMsg'])) {
                unset($data['errorMsg']);
            }
            $responseData = array_merge($responseData, $data);
        }

        $this->response()->setMessage(json_encode($responseData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 异常情况错误返回
     * @param $e
     * @return bool
     */
    protected function exceptionSetMessage($e) {
        if (!($e instanceof \Exception)) {
            return false;
        }
        $errorCode = $e->getCode();
        $data['errorMsg'] = $e->getMessage();
        $this->failSetMessage($errorCode, $data);
    }

    /**
     * 检查参数
     * @param $params
     * @param $keys
     * @return bool
     */
    protected function checkParams($params, $keys) {
        if (!is_array($params)) {
            $this->failSetMessage(ErrorCode::ERRORCODE_PARAMS_ERROR);
            return false;
        }

        foreach ($keys as $key){
            if(!array_key_exists($key, $params) || empty($params[$key])){
                $data['errorMsg'] = $key . '参数错误';
                $this->failSetMessage(ErrorCode::ERRORCODE_PARAMS_ERROR, $data);
                return false;
            }
        }

        return true;
    }
}