<?php
/**
 * Created by PhpStorm.
 * User: srq
 * Date: 2020/4/27
 * Time: 13:30
 */

namespace App\Common;


class ErrorCode{


    //@todo srq
    //STATUS_CODE
    //ERRORCODE

    CONST ERRORCODE_NORMAL = 0; //无报错
    CONST ERRORCODE_PARAMS_ERROR = 1; //参数错误
    CONST ERRORCODE_ADMIN_OFFLINE = 2; //没有对应客服
    CONST ERRORCODE_USER_OFFLINE = 3; //没有对应用户

    CONST MSG_TYPE = 1;




    /**
     * 错误码列表
     * @return array
     */
    public static function getErrorMsgList() {
        return [
            self::ERRORCODE_NORMAL => 'success',
            self::ERRORCODE_PARAMS_ERROR => '参数错误',
            self::ERRORCODE_ADMIN_OFFLINE => '没有对应客服',
            self::ERRORCODE_USER_OFFLINE => '没有对应用户',
        ];
    }

    /**
     * 根据错误码找到错误提示
     * @param $errorCode
     * @return mixed|string
     */
    public static function getErrorMsgByKey($errorCode) {
        $errorMsgList = self::getErrorMsgList();
        $errorMsg = $errorMsgList[self::ERRORCODE_NORMAL];
        if (array_key_exists($errorCode, $errorMsgList)) {
            $errorMsg = $errorMsgList[$errorCode];
        }
        return $errorMsg;
    }

}